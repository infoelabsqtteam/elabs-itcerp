<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Session;
use Validator;
use Route;
use DB;
use DNS1D;
use App\Company;
use App\Order;
use App\Models;
use App\Setting;
use App\ProductCategory;
use App\MISReport;
use App\SendMail;

class MISReportsController extends Controller
{
	    /**
	    * protected Variable.
	    */
	    protected $auth;
	    
	    /**
	    * Create a new controller instance.
	    *
	    * @return void
	    */
	    public function __construct(){
		    
			global $order,$models,$misReport,$mail;
			
			$order     = new Order();
			$misReport = new MISReport();
			$models    = new Models();
			$mail      = new SendMail();
			$this->middleware('auth');
			$this->middleware(function ($request, $next) {
				$this->auth = Auth::user();
				parent::__construct($this->auth);
				//Checking current request is allowed or not
				$allowedAction = array('index','navigation');
				$actionData    = explode('@',Route::currentRouteAction());
				$action        = !empty($actionData[1]) ? trim(strtolower($actionData[1])): '0';			
				if(defined('NOTALlOWEDTONAVIGATE') && empty(NOTALlOWEDTONAVIGATE) && in_array($action,$allowedAction)){
				return redirect('dashboard')->withErrors('Permission Denied!');
				}
				return $next($request);
			});	
	    }
	    
	    /**
	     * Display a listing of the resource.
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function index(){
			
			global $order,$models,$misReport,$mail;
			
			$user_id            = defined('USERID') ? USERID : '0';
			$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
			$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
			$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
			Session::set('defaultMisReport', basename(request()->path()));
			
			return view('MIS.index',['title' => 'MIS Reports','_mis' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
	    }
	    
		/**
		* generate MIS Report
		*
		* @return \Illuminate\Http\Response
		*/
	    public function getMISReportTypes(Request $request) {
	
			global $order,$models,$misReport,$mail;
		
			$error       	  = '1';
			$message     	  = '';
			$data        	  = '';
			$currentDate 	  = date('Y-m-d');
			$formData    	  = array();
			$defaultMisReport = Session::get('defaultMisReport');
			
			$reportFormTypeObj = DB::table('mis_report_default_types')->select('mis_report_default_types.mis_report_code as id','mis_report_default_types.mis_report_name as name')->where('mis_report_default_types.mis_report_status','1');

			if(!empty($defaultMisReport) && $defaultMisReport != 'all'){
				    $reportFormTypeObj->where('mis_report_default_types.mis_report_code',trim($defaultMisReport));
			}
			$reportFormTypes = $reportFormTypeObj->orderBy('mis_report_default_types.mis_report_order_by','ASC')->get();   
			
			//echo '<pre>';print_r($reportFormTypes);echo '</pre>';die;
			return response()->json(['error' => $error, 'message' => $message, 'returnData' => $reportFormTypes]);
	    }
	    
	    /**
	     * generate MIS Report
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function generateMISReport(Request $request) {
	
			global $order,$models,$misReport,$mail;
		
			$error        = '0';
			$message      = config('messages.message.error');
			$data         = '';
			$currentDate  = date('Y-m-d');
			$formData     = $returnData = array();
				
			//Saving record in orders table
			if($request->isMethod('post') && !empty($request->formData)){
					
				    parse_str($request->formData, $formData);
				    unset($formData['_token']);
				    $searchCriteria = $misReport->filterSearchCriteria($formData);
				    
				    //Reseting Session Data
				    Session::forget('response');
				    
				    if(!empty($formData['mis_report_name'])){
						
						$formData['date_from']      		= !empty($formData['date_from']) ? $models->getFormatedDate($formData['date_from']) : '0';
						$formData['date_to']   	    		= !empty($formData['date_to']) ? $models->getFormatedDate($formData['date_to']) : '0';
						$formData['expected_due_date_from']     = !empty($formData['expected_due_date_from']) ? $models->getFormatedDate($formData['expected_due_date_from']) : '0';
						$formData['expected_due_date_to']   	= !empty($formData['expected_due_date_to']) ? $models->getFormatedDate($formData['expected_due_date_to']) : '0';
						$formData['is_display_pcd'] 		= !empty($formData['product_category_id']) && $formData['product_category_id'] == '2' && defined('PHARMA_BACK_DATE_VIEW') && PHARMA_BACK_DATE_VIEW ? '1' : '0';
												
						if($formData['mis_report_name'] == 'DBD001'){			//Daily Booking Detail
							list($error,$message,$returnData) = $this->daily_booking_detail($formData,$searchCriteria);	    
						}else if($formData['mis_report_name'] == 'PWSCDW002'){		//Party Wise Sample Count-Date Wise
							list($error,$message,$returnData) = $this->party_wise_sample_count_date_wise($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'PWSCMW003'){		//Party Wise Sample Count-Month Wise
							list($error,$message,$returnData) = $this->party_wise_sample_count_month_wise($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'MEWBEN004'){		//Marketing Executive Wise-By Executive Name
							list($error,$message,$returnData) = $this->marketing_executive_name_wise_sample_count($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'MEWBPWSC005'){	//Marketing Executive Wise-By Place Wise Sample Count
							list($error,$message,$returnData) = $this->marketing_executive_place_wise_sample_count($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'TAT006'){		//TAT Report
							list($error,$message,$returnData) = $this->tat_report($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'UWPD007'){		//User Wise Performance Detail
							list($error,$message,$returnData) = $this->user_wise_performance_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'SLS008'){		//Sample Log Status
							list($error,$message,$returnData) = $this->sample_log_status($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'PWS009'){		//Parameter wise Scheduling
							list($error,$message,$returnData) = $this->parameter_wise_scheduling($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'SRD010'){		//Sales Report Detail
							list($error,$message,$returnData) = $this->sales_report_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'DID011'){		//Daily Invoice Detail
							list($error,$message,$returnData) = $this->daily_invoice_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'IWPD012'){		//Instrument Wise Performance Detail
							list($error,$message,$returnData) = $this->instrument_wise_performance_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'AWPS013'){		//Analyst Wise Performance Summary
							list($error,$message,$returnData) = $this->analyst_wise_performance_summary($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'BCD014'){		//Booking Cancellation Detail
							list($error,$message,$returnData) = $this->booking_cancellation_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'BAD015'){		//Booking Amendment Detail
							list($error,$message,$returnData) = $this->booking_amendment_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'DSD016'){		//Daily Sales Detail
							list($error,$message,$returnData) = $this->daily_sales_detail($formData,$searchCriteria);
						}						
				    }
			}
			
			//echo '<pre>';print_r($returnData);echo '</pre>';die;
			return response()->json(['error' => $error, 'message' => $message, 'returnData' => $returnData]);
	    }
	    
	    /**
	    * generate MIS Report
	    *
	    * @return \Illuminate\Http\Response
	    */
	    public function generateMISReportDocument_v1(Request $request) {
	
			global $order,$models,$misReport,$mail;
		
			$error       = '0';
			$message     = config('messages.message.error');
			$heading     = '';
			$currentDate = date('Y-m-d');
			$formData    = $returnData = array();
				
			//Saving record in orders table
			if($request->isMethod('post') && !empty($request->all())){
					
				    $formData = $request->all();
				    unset($formData['_token']);
				    
				    if(!empty($formData['mis_report_name'])){
						
						$formData['date_from']      		= !empty($formData['date_from']) ? $models->getFormatedDate($formData['date_from']) : '0';
						$formData['date_to']   	    		= !empty($formData['date_to']) ? $models->getFormatedDate($formData['date_to']) : '0';
						$formData['expected_due_date_from']     = !empty($formData['expected_due_date_from']) ? $models->getFormatedDate($formData['expected_due_date_from']) : '0';
						$formData['expected_due_date_to']   	= !empty($formData['expected_due_date_to']) ? $models->getFormatedDate($formData['expected_due_date_to']) : '0';
						$formData['is_display_pcd'] 		= !empty($formData['product_category_id']) && $formData['product_category_id'] == '2' && defined('PHARMA_BACK_DATE_VIEW') && PHARMA_BACK_DATE_VIEW ? '1' : '0';
				    
						if($formData['mis_report_name'] == 'DBD001'){			//Daily Booking Detail
							$formData['headingTxt'] = 'Daily Booking Detail';
							list($error,$message,$returnData) = $this->daily_booking_detail($formData,$searchCriteria);	    
						}else if($formData['mis_report_name'] == 'PWSCDW002'){		//Party Wise Sample Count-Date Wise
							$formData['headingTxt'] = 'Party Wise Sample Count-Date Wise';
							list($error,$message,$returnData) = $this->party_wise_sample_count_date_wise($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'PWSCMW003'){		//Party Wise Sample Count-Month Wise
							$formData['headingTxt'] = 'Party Wise Sample Count-Month Wise';
							list($error,$message,$returnData) = $this->party_wise_sample_count_month_wise($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'MEWBEN004'){		//Marketing Executive Wise-By Executive Name
							$formData['headingTxt'] = 'Marketing Executive Wise-By Executive Name';
							list($error,$message,$returnData) = $this->marketing_executive_name_wise_sample_count($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'MEWBPWSC005'){	//Marketing Executive Wise-By Place Wise Sample Count
							$formData['headingTxt'] = 'Marketing Executive Wise-By Place Wise Sample Count';
							list($error,$message,$returnData) = $this->marketing_executive_place_wise_sample_count($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'TAT006'){	//TAT Report
							$formData['headingTxt'] = 'TAT Report';
							list($error,$message,$returnData) = $this->tat_report($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'UWPD007'){	//User Wise Performance Detail
							$formData['headingTxt'] = 'User Wise Performance Detail';
							list($error,$message,$returnData) = $this->user_wise_performance_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'SLS008'){	//Sample Log Status
							$formData['headingTxt'] = 'Sample Log Status';
							list($error,$message,$returnData) = $this->sample_log_status($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'SRD010'){	//Sample Log Status
						        $formData['headingTxt'] = 'Sales Report Detail';
							list($error,$message,$returnData) = $this->sales_report_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'DID011'){	//Daily Invoice Detail
						        $formData['headingTxt'] = 'Daily Invoicing Detail';
						        list($error,$message,$returnData) = $this->daily_invoice_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'IWPD012'){		//Instrument Wise Performance Detail
						        $formData['headingTxt'] = 'Instrument Wise Performance Detail';
							list($error,$message,$returnData) = $this->instrument_wise_performance_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'AWPS013'){		//Analyst Wise Performance Summary
						        $formData['headingTxt'] = 'Analyst Wise Performance Summary';
							list($error,$message,$returnData) = $this->analyst_wise_performance_summary($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'BCD014'){		//Booking Cancellation Detail
						        $formData['headingTxt'] = 'Booking Cancellation Detail';
							list($error,$message,$returnData) = $this->booking_cancellation_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'BAD015'){		//Booking Amendment Detail
						        $formData['headingTxt'] = 'Booking Amendment Detail';
							list($error,$message,$returnData) = $this->booking_amendment_detail($formData,$searchCriteria);
						}else if($formData['mis_report_name'] == 'DSD016'){		//Daily Sales Detail
						        $formData['headingTxt'] = 'Daily Sales Detail';
							list($error,$message,$returnData) = $this->daily_sales_detail($formData,$searchCriteria);
						}                                   				
				    }
			}
			
			//echo '<pre>';print_r($returnData);echo '</pre>';die;
			return $models->generateExcel($returnData);    
	    }
	    
	    /**
	    * generate MIS Report
	    *
	    * @return \Illuminate\Http\Response
	    */
	    public function generateMISReportDocument(Request $request) {
	
			global $order,$models,$misReport,$mail;
			
			$error       = '0';
			$message     = config('messages.message.error');
			$heading     = '';
			$currentDate = date('Y-m-d');
			$formData    = $returnData = array();
				
			//Saving record in orders table
			if($request->isMethod('post') && !empty($request->all())){
					
				    $formData = $request->all();				    
				    if(!empty($formData['mis_report_name'])){
						
						if($formData['mis_report_name'] == 'DBD001'){			//Daily Booking Detail
							$formData['headingTxt'] = 'Daily Booking Detail';
						}else if($formData['mis_report_name'] == 'PWSCDW002'){		//Party Wise Sample Count-Date Wise
							$formData['headingTxt'] = 'Party Wise Sample Count-Date Wise';
						}else if($formData['mis_report_name'] == 'PWSCMW003'){		//Party Wise Sample Count-Month Wise
							$formData['headingTxt'] = 'Party Wise Sample Count-Month Wise';
						}else if($formData['mis_report_name'] == 'MEWBEN004'){		//Marketing Executive Wise-By Executive Name
							$formData['headingTxt'] = 'Marketing Executive Wise-By Executive Name';
						}else if($formData['mis_report_name'] == 'MEWBPWSC005'){	//Marketing Executive Wise-By Place Wise Sample Count
							$formData['headingTxt'] = 'Marketing Executive Wise-By Place Wise Sample Count';
						}else if($formData['mis_report_name'] == 'TAT006'){		//TAT Report
							$formData['headingTxt'] = 'TAT Report';
						}else if($formData['mis_report_name'] == 'UWPD007'){		//User Wise Performance Detail
							$formData['headingTxt'] = 'User Wise Performance Detail';
						}else if($formData['mis_report_name'] == 'SLS008'){		//Sample Log Status
							$formData['headingTxt'] = 'Sample Log Status';
						}else if($formData['mis_report_name'] == 'SRD010'){		//Sample Log Status
						        $formData['headingTxt'] = 'Sales Report Detail';
						}else if($formData['mis_report_name'] == 'DID011'){		//Daily Invoice Detail
						        $formData['headingTxt'] = 'Daily Invoicing Detail';
						}else if($formData['mis_report_name'] == 'IWPD012'){		//Instrument Wise Performance Detail
						        $formData['headingTxt'] = 'Instrument Wise Performance Detail';
						}else if($formData['mis_report_name'] == 'AWPS013'){		//Analyst Wise Performance Summary
						        $formData['headingTxt'] = 'Analyst Wise Performance Summary';
						}else if($formData['mis_report_name'] == 'BCD014'){		//Booking Cancellation Detail
						        $formData['headingTxt'] = 'Booking Cancellation Detail';
						}else if($formData['mis_report_name'] == 'BAD015'){		//Booking Amendment Detail
						        $formData['headingTxt'] = 'Booking Amendment Detail';
						}else if($formData['mis_report_name'] == 'DSD016'){		//Daily Sales Detail
						        $formData['headingTxt'] = 'Daily Sales Detail';
						}
						
						//Getting Session Data
						$returnData = Session::get('response');	
				    }
			}
			
			//echo '<pre>';print_r($returnData);echo '</pre>';die;
			return $models->generateExcel($returnData);    
	    }
	    
	    /**
	     * generate MIS Report::Daily Booking Detail
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function daily_booking_detail($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$response = $totalSampleAmount = $totalInvoiceAmount = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('order_master')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('customer_invoicing_types','customer_invoicing_types.invoicing_type_id','order_master.invoicing_type_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('users as createdBy','createdBy.id','order_master.created_by')
						->join('city_db','city_db.city_id','customer_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
						->join('samples','samples.sample_id','order_master.sample_id')
						->join('order_sample_priority','order_sample_priority.sample_priority_id','order_master.sample_priority_id')
						->leftJoin('invoice_hdr_detail','invoice_hdr_detail.order_id','order_master.order_id');
				    
				    if(!empty($postedData['is_display_pcd'])){
						$responseDataObj->select('departments.department_name','divisions.division_name as Branch','order_master.order_id','order_master.customer_id','customer_master.customer_name as party_name','city_db.city_name as place','product_master_alias.c_product_name as product_name','order_master.batch_no','order_sample_priority.sample_priority_name as sample_priority','samples.sample_no as sample_receiving_code','samples.sample_date as sample_receiving_date','samples.sample_date as sample_receiving_time','order_master.order_no as sample_reg_code','order_master.order_date as booking_date','order_master.order_date as booking_time','order_master.booking_date as current_date','order_master.booking_date as current_time','order_master.expected_due_date','customer_invoicing_types.invoicing_type','createdBy.name as booking_person_name','invoice_hdr_detail.invoice_dtl_id as sample_amount','invoice_hdr_detail.order_amount as invoice_amount');
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }else{
						$responseDataObj->select('departments.department_name','divisions.division_name as Branch','order_master.order_id','order_master.customer_id','customer_master.customer_name as party_name','city_db.city_name as place','product_master_alias.c_product_name as product_name','order_master.batch_no','order_sample_priority.sample_priority_name as sample_priority','samples.sample_no as sample_receiving_code','samples.sample_date as sample_receiving_date','samples.sample_date as sample_receiving_time','order_master.order_no as sample_reg_code','order_master.order_date as booking_date','order_master.order_date as booking_time','order_master.expected_due_date','customer_invoicing_types.invoicing_type','createdBy.name as booking_person_name','invoice_hdr_detail.invoice_dtl_id as sample_amount','invoice_hdr_detail.order_amount as invoice_amount');
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    
				    $responseDataObj->orderBy('customer_master.customer_name','ASC')->orderBy('order_master.order_date','ASC');
				    $responseData = $responseDataObj->get();
			    
				    if(!empty($responseData)){
						foreach($responseData as $key => $values){
							    $values->booking_date 		= date(DATEFORMATEXCEL,strtotime($values->booking_date));
							    $values->booking_time 		= date('h:i A',strtotime($values->booking_time));
							    if(!empty($postedData['is_display_pcd'])){
							    $values->current_date 		= date(DATEFORMATEXCEL,strtotime($values->current_date));
							    $values->current_time 		= date('h:i A',strtotime($values->current_time));
							    }
							    $values->sample_receiving_date	= !empty($values->sample_receiving_date) ? date(DATEFORMATEXCEL,strtotime($values->sample_receiving_date)) : '';
							    $values->sample_receiving_time	= !empty($values->sample_receiving_date) ? date('h:i A',strtotime($values->sample_receiving_date)) : '';
							    $values->expected_due_date 		= date(DATEFORMATEXCEL,strtotime($values->expected_due_date));
							    $values->sample_amount 		= $misReport->getBookedSamplePrice($values->customer_id, $values->order_id);
							    $values->invoice_amount 		= !empty($values->invoice_amount) ? $values->invoice_amount : '';
							    $postedData['sample_amount'][$key]	= $misReport->getBookedSamplePrice($values->customer_id, $values->order_id);
							    $postedData['invoice_amount'][$key]	= !empty($values->invoice_amount) ? $values->invoice_amount : '0';
						}
				    }
			}

			$responseData 		 	= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 		 	= $models->unsetFormDataVariablesArray($responseData,array('order_id','customer_id'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 	 	= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 	 	= !empty($responseData) ? $responseData : array();
			$response['tablefoot']	 	= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='6') : array();
			$error        		 	= !empty($responseData) ? '1' : '0';
			$message      		 	= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	    /**
	    * generate MIS Report::Party Wise Sample Count-Date Wise
	    *
	    * @return \Illuminate\Http\Response
	    */
	    public function party_wise_sample_count_date_wise($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$response = $responseData = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('order_master')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('city_db','city_db.city_id','customer_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->select('departments.department_name as department','divisions.division_name as branch','order_master.customer_id','customer_master.customer_name as party','city_db.city_name as place','order_master.customer_city');
				    
				    if(!empty($postedData['is_display_pcd'])){
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }else{
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    
				    $responseDataObj->groupBy('order_master.customer_id','order_master.customer_city');
				    $responseDataObj->orderBy('customer_master.customer_name','ASC');
				    $responseData = $responseDataObj->get();
				
				    if(!empty($responseData)){
						$dateRangeData = $models->date_range($postedData['date_from'], $postedData['date_to']);
						if(!empty($dateRangeData)){
							    foreach($responseData as $key => $values){
									$misReport->getDateWisePartySampleCount($values,$dateRangeData,$postedData);									
							    }
						}
				    }
			}
			
			$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('customer_id','customer_city'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? array_values($responseData) : array();
			$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='1') : array();
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);

			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	     /**
	     * generate MIS Report::Party Wise Sample Count-Month Wise
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function party_wise_sample_count_month_wise($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$response = $responseData = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('order_master')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('city_db','city_db.city_id','customer_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->select('departments.department_name as department','divisions.division_name as branch','order_master.customer_id','customer_master.customer_name as party','city_db.city_name as place','order_master.customer_city');
				    
				    if(!empty($postedData['is_display_pcd'])){
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }else{
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    
				    $responseDataObj->groupBy('order_master.customer_id','order_master.customer_city');
				    $responseDataObj->orderBy('customer_master.customer_name','ASC');
				    $responseData = $responseDataObj->get();
				
				    if(!empty($responseData)){
						$monthRangeData = $models->month_range($postedData['date_from'], $postedData['date_to']);
						if(!empty($monthRangeData)){
							    foreach($responseData as $key => $values){
									$misReport->getMonthWisePartySampleCount($values,$monthRangeData,$postedData);
							    }
						}
				    }
			}
			
			$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('customer_id','customer_city'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? array_values($responseData) : array();
			$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='2') : array();
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	     /**
	     * generate MIS Report::Marketing Executive Wise-By Executive Name
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function marketing_executive_name_wise_sample_count($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$response = $responseData = $totalSample = $totalAmount = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('order_master')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('city_db','city_db.city_id','customer_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->join('users as salesExecutive','salesExecutive.id','order_master.sale_executive')
						->select('departments.department_name as department','divisions.division_name as branch','salesExecutive.name as sales_executive','order_master.customer_id','customer_master.customer_name as party','city_db.city_name as place');
				    
				    if(!empty($postedData['is_display_pcd'])){
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }else{
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }		
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    if(!empty($postedData['sale_executive_id'])){
						$responseDataObj->where('order_master.sale_executive',$postedData['sale_executive_id']);
				    }
				    
				    $responseDataObj->groupBy('order_master.customer_id');
				    $responseDataObj->orderBy('salesExecutive.name','ASC');
				    $responseData = $responseDataObj->get();
				
				    if(!empty($responseData)){
						foreach($responseData as $key => $values){
							list($sampleCount,$sampleAmount) = $misReport->getCustomerSampleCountAmount($values,$postedData);
							$values->sample_count 	= $sampleCount;
							$values->sample_amount 	= $sampleAmount;
							$postedData['totalSample'][$key] = $sampleCount;
							$postedData['totalAmount'][$key] = $sampleAmount;
						}
				    }
			}
			
			$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('customer_id'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? array_values($responseData) : array();
			$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='3') : array();
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	    /**
	     * generate MIS Report::Marketing Executive Wise-By Place Wise Sample Count
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function marketing_executive_place_wise_sample_count($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$response = $responseData = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('order_master')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('city_db','city_db.city_id','customer_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->join('users as salesExecutive','salesExecutive.id','order_master.sale_executive')
						->select('departments.department_name as department','divisions.division_name as branch','order_master.customer_id','salesExecutive.name as sales_executive','customer_master.customer_city','city_db.city_name as place');
				    
				    if(!empty($postedData['is_display_pcd'])){
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }else{
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    if(!empty($postedData['sale_executive_id'])){
						$responseDataObj->where('order_master.sale_executive',$postedData['sale_executive_id']);
				    }
				    
				    $responseDataObj->groupBy('customer_master.customer_city');
				    $responseDataObj->orderBy('city_db.city_name','ASC');
				    $responseData = $responseDataObj->get();				    
				    
				    if(!empty($responseData)){
						foreach($responseData as $key => $values){
							list($sampleCount,$sampleAmount) = $misReport->getPlaceWiseSampleCountAmount($values,$postedData);
							$values->sample_count 	= $sampleCount;
							$values->sample_amount 	= $sampleAmount;
							$postedData['totalSample'][$key] = $sampleCount;
							$postedData['totalAmount'][$key] = $sampleAmount;
						}
				    }
			}
			
			$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('customer_id','customer_city'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? array_values($responseData) : array();
			$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='4') : array();
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	    /**
	    * generate MIS Report::TAT Report Detail
	    *
	    * @return \Illuminate\Http\Response
	    */
	    public function tat_report($postedData,$searchCriteria){
		
			global $order,$models,$misReport,$mail;
			
			$response = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('order_master')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('customer_billing_types','customer_billing_types.billing_type_id','customer_master.billing_type')
						->join('users as sales','sales.id','order_master.sale_executive')
						->join('city_db','city_db.city_id','customer_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
						->join('order_sample_priority','order_sample_priority.sample_priority_id','order_master.sample_priority_id')
						->join('samples','samples.sample_id','order_master.sample_id');
						
				    if(!empty($postedData['is_display_pcd'])){
						$responseDataObj->select('departments.department_name as department','divisions.division_name as branch','order_master.order_id','customer_master.customer_name as party_name','city_db.city_name as place','order_master.brand_type as brand','customer_billing_types.billing_type','product_master_alias.c_product_name as sample_name','order_master.order_no as sample_reg_code','order_master.batch_no','order_master.order_date as sample_reg_date','order_master.order_date as sample_reg_time','order_master.booking_date as sample_current_reg_date','order_master.booking_date as sample_current_reg_time','order_sample_priority.sample_priority_name as sample_priority','order_master.expected_due_date','order_master.status');
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }else{
						$responseDataObj->select('departments.department_name as department','divisions.division_name as branch','order_master.order_id','customer_master.customer_name as party_name','city_db.city_name as place','order_master.brand_type as brand','customer_billing_types.billing_type','product_master_alias.c_product_name as sample_name','order_master.order_no as sample_reg_code','order_master.batch_no','order_master.order_date as sample_reg_date','order_master.order_date as sample_reg_time','order_sample_priority.sample_priority_name as sample_priority','order_master.expected_due_date','order_master.status');
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }					    
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }				    
				    //Filtering records according to expected due date from and expected due date to 
				    if(!empty($postedData['expected_due_date_from']) && !empty($postedData['expected_due_date_to'])){
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.expected_due_date)"), array($postedData['expected_due_date_from'], $postedData['expected_due_date_to']));
				    }else if(!empty($postedData['expected_due_date_from']) && empty($postedData['expected_due_date_to'])){
						$responseDataObj->where(DB::raw("DATE(order_master.expected_due_date)"),'>=', $postedData['expected_due_date_from']);  
				    }else if(empty($postedData['expected_due_date_from']) && !empty($postedData['expected_due_date_to'])){
						$responseDataObj->where(DB::raw("DATE(order_master.expected_due_date)"),'<=', $postedData['expected_due_date_to']);  
				    }
				    //Filtering Records of Order Status
				    if(!empty($postedData['order_status_id'])){
						$responseDataObj->where('order_master.status',$postedData['order_status_id']);
						if($postedData['order_status_id'] == '9'){
							    $responseDataObj->whereNotIn('order_master.order_id',DB::table('order_dispatch_dtl')->pluck('order_id')->all());	
						}						
				    }
				    $responseDataObj->orderBy('order_master.order_date','DESC');
				    $responseData = $responseDataObj->get();
				    
				    if(!empty($responseData)){
						foreach($responseData as $values){
							    
							    $isCancelledStatus 		= !empty($order->hasOrderCancelled($values->order_id)) ? true : false;
							    $isOrderSampleType 		= !empty($order->hasOrderInterLabOrCompensatory($values->order_id)) ? true : false;
							    
							    $values->sample_reg_date    = date(DATEFORMATEXCEL,strtotime($values->sample_reg_date));
							    $values->sample_reg_time    = date('h:i A',strtotime($values->sample_reg_time));
							    if(!empty($postedData['is_display_pcd'])){
							    $values->sample_current_reg_date = date(DATEFORMATEXCEL,strtotime($values->sample_current_reg_date));
							    $values->sample_current_reg_time = date('h:i A',strtotime($values->sample_current_reg_time));
							    }
							    $values->expected_due_date  = date(DATEFORMATEXCEL,strtotime($values->expected_due_date));
							    $values->scheduled_status   = $isCancelledStatus ? '' : $misReport->checkScheduledStatusOfOrder($values->order_id);
							
							    //***************Equipment Information****************************************************************			    
							    $allowedEquipmentIds  = array('1','16','81','8','11','14','13','20','98','12','22');
							    foreach($allowedEquipmentIds as $equipmentId){
									$allowedEquipment = DB::table('equipment_type')->select('equipment_type.equipment_id','equipment_type.equipment_name')->where('equipment_type.equipment_id',$equipmentId)->first();
									if(!empty($allowedEquipment)){
										    $equipmentName		= $allowedEquipment->equipment_name;
										    $equipmentId 		= $allowedEquipment->equipment_id;
										    $statusEquipment		= $misReport->checkEquipmentAllotStatus($values->order_id,$equipmentId);
										    $values->$equipmentName 	= $isCancelledStatus ? '' : $statusEquipment;
									}
							    }						
							    $values->Others 	    = $isCancelledStatus ? '' : $misReport->checkEquipmentAllotStatusOthers($values->order_id,$allowedEquipmentIds);
							    $values->testing_status = $isCancelledStatus ? '' : $misReport->checkTestingStatusOfOrder($values->order_id);
							    //**************/Equipment Information****************************************************************
							    
							    //***************Reviewing Detail*********************************************************************						
							    $reviewingDetail = DB::table('order_process_log')
							            ->join('order_report_details','order_report_details.report_id','order_process_log.opl_order_id')
								    ->join('order_status','order_status.order_status_id','order_process_log.opl_order_status_id')
								    ->join('users as usersReport','usersReport.id','order_process_log.opl_user_id')
								    ->select('order_process_log.*','order_status.order_status_name','usersReport.name as usersReportName','order_report_details.reviewing_date')
								    ->where('order_process_log.opl_order_id',$values->order_id)
								    ->where('order_process_log.opl_order_status_id','5')
								    ->where('order_process_log.opl_amend_status','0')
								    ->orderBy('order_process_log.opl_id','DESC')
								    ->first();
							    
							    if(!empty($reviewingDetail)){
									$values->reviewed_date = $isCancelledStatus ? '' : date(DATEFORMATEXCEL,strtotime($reviewingDetail->reviewing_date));
									$values->reviewed_time = $isCancelledStatus ? '' : date('h:i A',strtotime($reviewingDetail->reviewing_date));
									$values->reviewed_by   = $isCancelledStatus ? '' : $reviewingDetail->usersReportName;
							    }else{
									$values->reviewed_date = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
									$values->reviewed_time = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
									$values->reviewed_by   = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';    
							    }
							    //***************Reviewing Detail********************************************************************
							    
							    //***************Approving Detail*********************************************************************						
							    $approvingDetail = DB::table('order_process_log')
							            ->join('order_report_details','order_report_details.report_id','order_process_log.opl_order_id')
								    ->join('order_status','order_status.order_status_id','order_process_log.opl_order_status_id')
								    ->join('users as usersReport','usersReport.id','order_process_log.opl_user_id')
								    ->select('order_process_log.*','order_status.order_status_name','usersReport.name as usersReportName','order_report_details.approving_date')
								    ->where('order_process_log.opl_order_id',$values->order_id)
								    ->where('order_process_log.opl_order_status_id','7')
								    ->where('order_process_log.opl_amend_status','0')
								    ->orderBy('order_process_log.opl_id','DESC')
								    ->first();
							    
							    if(!empty($approvingDetail)){
									$values->approved_date = $isCancelledStatus ? '' : date(DATEFORMATEXCEL,strtotime($approvingDetail->approving_date));
									$values->approved_time = $isCancelledStatus ? '' : date('h:i A',strtotime($approvingDetail->approving_date));
									$values->approved_by   = $isCancelledStatus ? '' : $approvingDetail->usersReportName;
							    }else{
									$values->approved_date = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
									$values->approved_time = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
									$values->approved_by   = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';    
							    }
							    //***************Approving Detail********************************************************************
							    
							    //***************Email Detail************************************************************************
							    $emailedData = DB::table('order_mail_dtl')
									->join('users as mailByTb', 'mailByTb.id', 'order_mail_dtl.mail_by')
									->select('order_mail_dtl.mail_date','mailByTb.name as mailBy')
									->where('order_mail_dtl.mail_content_type','3')
									->where('order_mail_dtl.mail_status','1')
									->where('order_mail_dtl.mail_active_type','=','1')
									->where('order_mail_dtl.order_id',$values->order_id)
									->orderBy('order_mail_dtl.mail_id','DESC')
									->first();
							    
							    if(!empty($emailedData)){					
									$values->emailed_date   = $isCancelledStatus ? '' : !empty($emailedData->mail_date) ? date(DATEFORMATEXCEL,strtotime($emailedData->mail_date)) : '';
									$values->emailed_time   = $isCancelledStatus ? '' : !empty($emailedData->mail_date) ? date('h:i A',strtotime($emailedData->mail_date)) : '';
									$values->emailed_by     = $isCancelledStatus ? '' : !empty($emailedData->mailBy) ? $emailedData->mailBy : '';
							    }else{
									$values->emailed_date   = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
									$values->emailed_time   = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
									$values->emailed_by     = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';    
							    }
							    //***************/Email Detail*************************************************************************
							    
							    //***************Invoicing Detail**********************************************************************
							    $invoicingData = DB::table('invoice_hdr_detail')
									->join('invoice_hdr','invoice_hdr.invoice_id','invoice_hdr_detail.invoice_hdr_id')
									->join('order_master','order_master.order_id','invoice_hdr_detail.order_id')
									->join('users as invoicedByTb','invoicedByTb.id','invoice_hdr.created_by')
									->select('invoice_hdr.invoice_no','invoice_hdr.invoice_date','invoice_hdr_detail.order_amount','invoicedByTb.name as invoicedBy')
									->groupBy('invoice_hdr_detail.order_id')
									->whereNull('order_master.order_sample_type')
									->where('invoice_hdr_detail.order_id',$values->order_id)
									->first();
							    
							    if(!empty($invoicingData)){
									$values->invoice_date   = $isCancelledStatus ? '' : !empty($invoicingData->invoice_date) ? date(DATEFORMATEXCEL,strtotime($invoicingData->invoice_date)) : '';
									$values->invoice_time   = $isCancelledStatus ? '' : !empty($invoicingData->invoice_date) ? date('h:i A',strtotime($invoicingData->invoice_date)) : '';
									$values->invoice_no     = $isCancelledStatus ? '' : !empty($invoicingData->invoice_no) ? $invoicingData->invoice_no : '';
									$values->invoice_by     = $isCancelledStatus ? '' : !empty($invoicingData->invoicedBy) ? $invoicingData->invoicedBy : '';
									$values->sample_amount  = $isCancelledStatus ? '' : !empty($invoicingData->order_amount) ? $invoicingData->order_amount : '';
							    }else{
									if($isCancelledStatus){
										    $values->invoice_date   = '';
										    $values->invoice_time   = '';
										    $values->invoice_no     = '';
										    $values->invoice_by     = '';
										    $values->sample_amount  = '';
									}elseif($isOrderSampleType){
										    $values->invoice_date   = 'I';
										    $values->invoice_time   = 'I';
										    $values->invoice_no     = 'I';
										    $values->invoice_by     = 'I';
										    $values->sample_amount  = '';  
									}else{
										    $values->invoice_date   = $values->testing_status == 'C' && !$isOrderSampleType ? 'P' : '';
										    $values->invoice_time   = $values->testing_status == 'C' && !$isOrderSampleType ? 'P' : '';
										    $values->invoice_no     = $values->testing_status == 'C' && !$isOrderSampleType ? 'P' : '';
										    $values->invoice_by     = $values->testing_status == 'C' && !$isOrderSampleType ? 'P' : '';
										    $values->sample_amount  = '';
									}									
							    }
							    //***************/Invoicing Detail**********************************************************************
							    
							    //***************Dispatching Detail*********************************************************************
							    $dispatchingData = DB::table('order_dispatch_dtl')
									->leftJoin('users as dispatchByTb', 'dispatchByTb.id', 'order_dispatch_dtl.dispatch_by')
									->select('order_dispatch_dtl.dispatch_date','dispatchByTb.name as dispatchBy')
									->where('order_dispatch_dtl.order_id',$values->order_id)
									->where('order_dispatch_dtl.amend_status','0')
									->first();
							    
							    if(!empty($dispatchingData)){
									$values->dispatch_date  = $isCancelledStatus ? '' : !empty($dispatchingData->dispatch_date) ? date(DATEFORMATEXCEL,strtotime($dispatchingData->dispatch_date)) : '';
									$values->dispatch_time  = $isCancelledStatus ? '' : !empty($dispatchingData->dispatch_date) ? date('h:i A',strtotime($dispatchingData->dispatch_date)) : '';
									$values->dispatch_by    = $isCancelledStatus ? '' : !empty($dispatchingData->dispatchBy) ? $dispatchingData->dispatchBy : '';
							    }else{
									$values->dispatch_date  = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
									$values->dispatch_time  = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
									$values->dispatch_by    = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
							    }						
							    //***************/Dispatching Detail***********************************************************************
							    
							    //***************Report Status Detail*********************************************************************
							    $reportType   = '';
							    $orderDetail  = DB::table('order_master')->where('order_master.order_id',$values->order_id)->first();
							    $reportDetail = DB::table('order_report_details')->where('order_report_details.report_id',$values->order_id)->first();
							    if(!empty($orderDetail->status) && $orderDetail->status == '10'){
								    $reportType = 'Cancelled';
							    }elseif(!empty($orderDetail->status) && $orderDetail->status == '12'){
								    $reportType = 'Hold';
							    }elseif(!empty($reportDetail->report_type) && $reportDetail->report_type == '1'){
								     $reportType = 'Final';    
							    }elseif(!empty($reportDetail->report_type) && $reportDetail->report_type == '2'){
								     $reportType = 'Draft';    
							    }
							    $values->report_status = $reportType;  
							    //***************Report Status Detail*********************************************************************
						}
				    }
			}
		    
			//removing unrequired coloums
			$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('order_id','status'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
						
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);	
	    }
	    
	    /**
	    * generate MIS Report::User Wise Performance Detail
	    *
	    * @return \Illuminate\Http\Response
	    */
	    public function user_wise_performance_detail($postedData,$searchCriteria){
			
			global $order,$models,$misReport,$mail;
			
			$response = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				    
				    if(!empty($postedData['order_status_id'])){
						if(!empty($postedData['user_id'])){
							    $userData = DB::table('users')->where('users.id',$postedData['user_id'])->first();
							    $unsetArrayColoumData = array('order_id','opl_user_id','opl_order_status_id','sample_receiving_date','test_parameter_id','employee_id','analyst_name');
						}else{
							    $unsetArrayColoumData = array('order_id','opl_user_id','opl_order_status_id','sample_receiving_date','test_parameter_id','employee_id');   
						}
						if(!empty($postedData['order_status_id'])){
							    $roleData = DB::table('order_status')->where('order_status.order_status_id',$postedData['order_status_id'])->first();
						}						
						if($postedData['order_status_id'] == '3'){
							    list($responseData,$postedData) = $this->analyst_wise_performance_detail($postedData);  
						}else{
							    $responseData = $this->all_users_wise_performance_detail($postedData);   
						}						
				    }
			}
	
			//removing unrequired coloums
			$username			= !empty($userData->name) ? ucwords($userData->name) : 'All';
			$roleName			= !empty($roleData->order_status_alias) ? ucwords($roleData->order_status_alias) : '-';
			$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,$unsetArrayColoumData);
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
			$response['tablefoot']		= !empty($postedData['sum_of_yes']) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='8') : array();
			$response['extraHeading']	= $username.' : '.$roleName;
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
			
	    }
	    
	    /**
	    * generate MIS Report::User Wise Performance Detail
	    *
	    * @return \Illuminate\Http\Response
	    */
	    public function analyst_wise_performance_detail($postedData){
		
			global $order,$models,$misReport,$mail;
			
			$response = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				    
				    $responseDataObj = DB::table('schedulings')				    		
						->join('order_master','order_master.order_id','schedulings.order_id')
						->join('order_parameters_detail','order_parameters_detail.analysis_id','schedulings.order_parameter_id')
						->join('test_parameter','test_parameter.test_parameter_id','order_parameters_detail.test_parameter_id')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','schedulings.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
						->join('users as analyst','analyst.id','schedulings.employee_id')
						->select('divisions.division_name as branch','departments.department_name as department','order_master.order_date as date','order_master.order_id','product_master_alias.c_product_name as sample_name','order_master.order_no as sample_code','test_parameter.test_parameter_name','test_parameter.test_parameter_name as no_of_test','order_master.order_date as booking_date','schedulings.scheduled_at as scheduling_date','order_master.report_due_date as expected_due_date','schedulings.completed_at as test_completion_date','schedulings.created_at as TAT','schedulings.created_at as within_due_date','schedulings.created_at as days_delay','test_parameter.test_parameter_id','schedulings.completed_at as no_of_errors','schedulings.employee_id','analyst.name as analyst_name')
						->whereBetween(DB::raw("DATE(schedulings.completed_at)"), array($postedData['date_from'], $postedData['date_to']))
						->where('order_master.status','<>','10');
				    
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    if(!empty($postedData['user_id'])){
						$responseDataObj->where('schedulings.employee_id',$postedData['user_id']);
				    }
				    $responseDataObj->orderBy('order_master.order_date','ASC');
				    $responseDataObj->orderBy('order_master.order_no','ASC');
				    $responseData = $responseDataObj->get();
				    
				    if(!empty($responseData)){
						foreach($responseData as $key => $values){
							    $values->TAT 		  = $models->sub_days_between_two_date($values->test_completion_date,$values->scheduling_date);
							    $values->within_due_date 	  = strtotime(date(DATEFORMATEXCEL,strtotime($values->test_completion_date))) <= strtotime(date(DATEFORMATEXCEL,strtotime($values->expected_due_date))) ? 'Y' : 'N';
							    $values->days_delay 	  = $models->sub_days_between_two_date($values->test_completion_date,$values->expected_due_date) <= $values->TAT ? '' : $values->TAT - $models->sub_days_between_two_date($values->test_completion_date,$values->expected_due_date);
							    $values->date 	  	  = !empty($values->date) ? date(DATEFORMATEXCEL,strtotime($values->date)) : '';
							    $values->booking_date 	  = !empty($values->booking_date) ? date(DATEFORMATEXCEL,strtotime($values->booking_date)) : '';
							    $values->scheduling_date 	  = !empty($values->scheduling_date) ? date(DATEFORMATEXCEL,strtotime($values->scheduling_date)) : '';
							    $values->expected_due_date 	  = !empty($values->expected_due_date) ? date(DATEFORMATEXCEL,strtotime($values->expected_due_date)) : '';
							    $values->test_completion_date = !empty($values->test_completion_date) ? date(DATEFORMATEXCEL,strtotime($values->test_completion_date)) : '';
							    $values->test_parameter_name  = trim($values->test_parameter_name);
							    $values->no_of_test 	  = '1';
							    $values->no_of_errors 	  = $misReport->getNoOfErrorCount($values,$postedData);
							    $postedData['sum_of_yes'][$key] = $values->within_due_date == 'Y' ? $values->within_due_date : '';
							    $postedData['sum_of_no'][$key]  = $values->within_due_date == 'N' ? $values->within_due_date : '';
							    $postedData['no_of_error_count'][$key] = $values->no_of_errors;
						}
				    }
				    
				    //echo'<pre>'; print_r($postedData);die;
				    return array($responseData,$postedData);
			}
	    }
	    
	    /**
	    * generate MIS Report::User Wise Performance Detail
	    *
	    * @return \Illuminate\Http\Response
	    */
	    public function all_users_wise_performance_detail($postedData){
		
			global $order,$models,$misReport,$mail;
			
			$response = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
			
				    $responseDataObj = DB::table('order_process_log')
						->join('order_master','order_process_log.opl_order_id','order_master.order_id')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('city_db','city_db.city_id','customer_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
						->join('samples','samples.sample_id','order_master.sample_id')
						->select('departments.department_name as department','divisions.division_name as branch','order_master.order_id','order_process_log.opl_user_id','order_process_log.opl_order_status_id','customer_master.customer_name as party','city_db.city_name as place','product_master_alias.c_product_name as sample_name','order_master.batch_no','order_master.order_no as sample_reg_code','samples.sample_date as sample_receiving_date','order_master.order_date as sample_reg_date')
						->whereNotNull('order_process_log.opl_user_id');
				    
				    if(!empty($postedData['is_display_pcd'])){
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }else{
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }				    
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    if(!empty($postedData['order_status_id'])){
						$roleData = DB::table('order_status')->where('order_status.order_status_id',$postedData['order_status_id'])->first();
						$responseDataObj->where('order_process_log.opl_order_status_id',$postedData['order_status_id']);
				    }
				    if(!empty($postedData['user_id'])){
						$userData = DB::table('users')->where('users.id',$postedData['user_id'])->first();
						$responseDataObj->where('order_process_log.opl_user_id',$postedData['user_id']);
				    }
				    
				    $responseDataObj->groupBy('order_process_log.opl_order_id');
				    $responseDataObj->orderBy('customer_master.customer_name','DESC');
				    $responseDataObj->orderBy('order_process_log.opl_date','ASC');
				    $responseData = $responseDataObj->get();
				    
				    if(!empty($responseData)){
						foreach($responseData as $values){
							    $values->sample_reg_date = date(DATEFORMATEXCEL,strtotime($values->sample_reg_date));
							    $modificationCount = $misReport->get_modification_count($values->opl_user_id, $values->order_id,$values->opl_order_status_id);
							    $bookingDate       = $misReport->get_order_stage_date($values->order_id,'1');
							    $testingDate       = $misReport->get_order_stage_date($values->order_id,'3');
							    $reportingDate     = $misReport->get_order_stage_date($values->order_id,'4');
							    $reviewingDate     = $misReport->get_order_stage_date($values->order_id,'5');
							    $finalizingDate    = $misReport->get_order_stage_date($values->order_id,'6');
							    $approvingDate     = $misReport->get_order_stage_date($values->order_id,'7');
							    $invoicingDate     = $misReport->get_order_stage_date($values->order_id,'8');
							    if($postedData['order_status_id'] == '1'){ //Booking Users
								$values->booking_time_limit         = $models->date_hour_min_sec_ago($bookingDate, $values->sample_receiving_date);
								$values->booking_modification_count = $modificationCount;
							    }
							    if($postedData['order_status_id'] == '3'){ //Analyst Users
								$values->analyst_time_limit         = $models->date_hour_min_sec_ago($bookingDate, $testingDate);
								$values->analyst_modification_count = $modificationCount;
							    }
							    if($postedData['order_status_id'] == '4'){ //Reporting Users
								$values->reporting_time_limit         = $models->date_hour_min_sec_ago($bookingDate, $reportingDate);
								$values->reporting_modification_count = $modificationCount;
							    }		    
							    if($postedData['order_status_id'] == '5'){ //Reviewing Users
								$values->reviewing_time_limit         = $models->date_hour_min_sec_ago($reportingDate, $reviewingDate);
								$values->reviewing_modification_count = $modificationCount;
							    }
							    if($postedData['order_status_id'] == '6'){ //Finalizing Users
								$values->finalizing_time_limit         = $models->date_hour_min_sec_ago($reviewingDate, $finalizingDate);
								$values->finalizing_modification_count = $modificationCount;
							    }
							    if($postedData['order_status_id'] == '7'){ //Approving Users
								$values->approving_time_limit         = $models->date_hour_min_sec_ago($finalizingDate, $approvingDate);
								$values->approving_modification_count = $modificationCount;
							    }
							    if($postedData['order_status_id'] == '8'){ //Invoicing Users
								$values->invoicing_time_limit         = $models->date_hour_min_sec_ago($approvingDate, $invoicingDate);
								$values->invoicing_modification_count = $modificationCount;
							    }
						}
				    }
			}
			
			//echo'<pre>'; print_r($responseData); die;
			return $responseData;	
	    }
	    
	    /**
	     * generate MIS Report::Analyst Wise Performance Summary
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function analyst_wise_performance_summary($postedData,$searchCriteria) {
			
			global $order,$models,$misReport,$mail;
		
			$response = array();
	    			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				    $responseDataObj = DB::table('users')
						->join('users_department_detail', 'users_department_detail.user_id', '=', 'users.id')
						->join('role_user', 'users.id', '=', 'role_user.user_id')
						->join('order_status', 'order_status.role_id', '=', 'role_user.role_id')
						->select('users.id as user_id','users.name as analyst_name','users.id as total_sample_received','users.id as sample_analysed','users.id as no_of_test_conducted','users.id as sample_within_tat','users.id as sample_beyond_tat','users.id as no_of_errors','users.id as error_percentage','users.id as performance')
						->where('order_status.order_status_id', '=', $postedData['order_status_id'])
						->where('users.is_sales_person', '=', '0');
				    
				    if(!empty($postedData['division_id']) && is_numeric($postedData['division_id'])){
					$responseDataObj->where('users.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id']) && is_numeric($postedData['product_category_id'])){
					$linkedData = DB::table('department_product_categories_link')->where('department_product_categories_link.product_category_id', '=', $postedData['product_category_id'])->first();
					$responseDataObj->where('users_department_detail.department_id',$linkedData->department_id);
				    }
				    if(!empty($postedData['user_id']) && is_numeric($postedData['user_id'])){
					$responseDataObj->where('users.id',$postedData['user_id']);
				    }
				    $responseDataObj->groupBy('users.id');
				    $responseDataObj->orderBy('users.name','ASC');
				    $responseData = $responseDataObj->get();
				    				    
				    if(!empty($responseData)){
						foreach($responseData as $key => $values){
							  $values->total_sample_received = $misReport->getRequiredFieldValue($values,$postedData,$type='1');
							  $values->sample_analysed       = $misReport->getRequiredFieldValue($values,$postedData,$type='2');
							  $values->no_of_test_conducted  = $misReport->getRequiredFieldValue($values,$postedData,$type='3');
							  $values->sample_within_tat     = $misReport->getRequiredFieldValue($values,$postedData,$type='4');
							  $values->sample_beyond_tat     = $misReport->getRequiredFieldValue($values,$postedData,$type='5');
							  $values->no_of_errors     	 = $misReport->getRequiredFieldValue($values,$postedData,$type='6');
							  $values->error_percentage      = !empty($values->no_of_errors) ? round(($values->no_of_errors / $values->no_of_test_conducted) * 100,2).'%' : '0%';
							  $values->performance           = !empty($values->sample_within_tat) && !empty($values->total_sample_received) ? round(($values->sample_within_tat / $values->total_sample_received) * 100,2).'%' : '0%';
						}
				    }
			}
			
			$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();	    
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('user_id'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
			$response['tablefoot']		= array();
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo '<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	    /**
	     * generate MIS Report::Sample Log Status
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function sample_log_status($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$response = $responseData = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){

				    $coloumArrayData = array(
						'1' => 'No. of Packet Received',
						'2' => 'No. of Packet Booked',
						'3' => 'No. of Sample Booked',
						'4' => 'No. of Sample Hold',
						'5' => 'No. of Sample Scheduled',
						'6' => 'No. of Sample Analyzed',
						'7' => 'No. of Sample Reviewed',
						'8' => 'No. of Sample Approved',
						'9' => 'No. of Sample Emailed',
						'10' => 'No. of report Dispatched',
						'11' => 'No of report Invoiced',
						'12' => 'No of Report Due',
						'13' => 'No. of Report Overdue',
						'14' => 'No. of Invoice Pending',
						'15' => 'No. of Report Pending',
				    );
				    $departmentDataObj = DB::table('product_categories')
						->select('product_categories.p_category_id','product_categories.p_category_name')
						->where('product_categories.parent_id','0');
						
				    if(!empty($postedData['division_id'])){
						$divisionDataList = DB::table('divisions')->where('division_id',$postedData['division_id'])->select('division_id','division_name')->get();
				    }else{
						$divisionDataList = DB::table('divisions')->select('division_id','division_name')->get();	
				    }
				    if(!empty($postedData['product_category_id'])){
						$departmentDataObj->where('product_categories.p_category_id',$postedData['product_category_id']);
				    }
				    $departmentDataObj->orderBy('product_categories.p_category_id','ASC');
				    $departmentData = $departmentDataObj->get();
						
				    if(!empty($coloumArrayData) && !empty($departmentData) && !empty($divisionDataList)){		
						foreach($divisionDataList as $divisions){    
							    foreach($coloumArrayData as $key => $coloumArray){
									$totalData 				     	= array();
									$divisionId 					= $divisions->division_id;
									$divisionIdkey					= $divisionId.$key;
									$responseData[$divisionIdkey]['branch']   	= $divisions->division_name;
									$responseData[$divisionIdkey]['department'] 	= $coloumArray;
									
									foreach($departmentData as $department){
										    $departmentName 	 	     			= $department->p_category_name;
										    $departmentId   	 	     			= $department->p_category_id;
										    $countValue     	 	     			= $misReport->getSampleLogResultant($key,$divisions->division_id,$departmentId,$postedData);
										    $responseData[$divisionIdkey][$departmentName] 	= $countValue;
										    $totalData[$divisionIdkey.$departmentId]	   	= $countValue;
									}
									$responseData[$divisionIdkey]['total'] = array_sum($totalData);

							    }
						}
				    }
			}
			
			//removing unrequired coloums
			$responseData 			= !empty($responseData) ? array_values(json_decode(json_encode($responseData),true)) : array();
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('order_id','customer_id'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
			
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			echo'<pre>'; print_r($response['tableBody']); die;

			//Saving Data in Session
			Session::set('response', $response);
			
			
			return array($error,$message,$response);
	    }
	    
	    /**
	    * generate MIS Report::Sales Report Detail
	    *
	    * @return \Illuminate\Http\Response
	    */
	    public function sales_report_detail($postedData,$searchCriteria){
	
			global $order,$models,$misReport,$mail;
		
			$response = $responseData = $total = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('invoice_hdr_detail')
						->join('invoice_hdr','invoice_hdr.invoice_id','invoice_hdr_detail.invoice_hdr_id')
						->join('order_master','order_master.order_id','invoice_hdr_detail.order_id')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('city_db','city_db.city_id','customer_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->join('users as salesExecutive','salesExecutive.id','order_master.sale_executive')
						->join('product_master','product_master.product_id','order_master.product_id')
						->join('product_categories as subCategory','subCategory.p_category_id','product_master.p_category_id')
						->leftJoin('product_categories as parentCategory','parentCategory.p_category_id','subCategory.parent_id')
						->select('departments.department_name','divisions.division_name as branch','invoice_hdr.invoice_date as date','invoice_hdr.invoice_date as month','invoice_hdr.invoice_date as year','customer_master.customer_name','city_db.city_name as location','salesExecutive.name as sales_executive_name','parentCategory.p_category_name as category_name','subCategory.p_category_name as sub_category_name','product_master.product_name','order_master.manufactured_by','order_master.supplied_by','invoice_hdr_detail.order_amount as revenue_amount','invoice_hdr_detail.order_amount as revenue_amount','invoice_hdr_detail.order_discount','invoice_hdr_detail.extra_amount','invoice_hdr_detail.surcharge_amount')
						->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), array($postedData['date_from'], $postedData['date_to']));
				    
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('invoice_hdr.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    if(!empty($postedData['sale_executive_id'])){
						$responseDataObj->where('order_master.sale_executive',$postedData['sale_executive_id']);
				    }
				    
				    $responseData = $responseDataObj->orderBy('invoice_hdr.invoice_date','DESC')->get();
				    
				    if(!empty($responseData)){
						foreach($responseData as $key => $value){
							$value->date  		= date('d',strtotime($value->date));
							$value->month 		= date('F',strtotime($value->month));
							$value->year  		= date('Y',strtotime($value->year));
							$value->revenue_amount 	= number_format((float) $value->revenue_amount - $value->order_discount + $value->extra_amount + $value->surcharge_amount, 2, '.', '');
							$postedData['revenue_amount'][$key] = $value->revenue_amount;
						}						
				    }
			}
			
			$totalColumn			= array(array('TOTAL',array_sum($total)));
			$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('order_id','customer_id','order_discount','extra_amount','surcharge_amount'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead']		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody']		= !empty($responseData) ? array_values($responseData) : array();
			$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='5') : array();
			$error   			= !empty($responseData) ? '1' : '0';
			$message 			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	    /**
	     * generate MIS Report::Daily Invoicing Detail
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function daily_invoice_detail($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$response = $totalSampleAmount = $totalInvoiceAmount = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('invoice_hdr')
						->join('invoice_hdr_detail','invoice_hdr_detail.invoice_hdr_id','invoice_hdr.invoice_id')
						->join('customer_master','customer_master.customer_id','invoice_hdr.customer_id')
						->join('state_db','customer_master.customer_state','state_db.state_id')
						->join('city_db','customer_master.customer_city','city_db.city_id')
						->leftjoin('order_master','order_master.order_id','invoice_hdr_detail.order_id')
						->leftjoin('customer_master as InvoicingToCustomerDB','InvoicingToCustomerDB.customer_id','order_master.invoicing_to')
						->leftjoin('state_db as InvoicingToStateDB','InvoicingToStateDB.state_id','InvoicingToCustomerDB.customer_state')
						->leftjoin('city_db as InvoicingToCityDB','InvoicingToCityDB.city_id','InvoicingToCustomerDB.customer_city')
						->select('invoice_hdr.invoice_no as order_no','state_db.state_name','city_db.city_name','InvoicingToStateDB.state_name as invoicing_state_name','InvoicingToCityDB.city_name as invoicing_city_name','invoice_hdr.invoice_date as bill_date','invoice_hdr.invoice_no as bill_no','customer_master.customer_name as party_name','InvoicingToCustomerDB.customer_name as invoicing_party_name','invoice_hdr.net_amount as amount','invoice_hdr.extra_amount as conveyance','invoice_hdr.sgst_amount as sgst_value','invoice_hdr.cgst_amount as cgst_value','invoice_hdr.igst_amount as igst_value','invoice_hdr.extra_amount as conveyance','invoice_hdr.net_total_amount as amt_payable')
						->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), array($postedData['date_from'], $postedData['date_to']));
				    				    
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('invoice_hdr.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('invoice_hdr.product_category_id',$postedData['product_category_id']);
				    }
				    
				    $responseDataObj->orderBy('customer_master.customer_name','ASC');
				    $responseDataObj->groupBy('invoice_hdr.invoice_no');
				    $responseData = $responseDataObj->get();
				    
				    if(!empty($responseData)){
						foreach($responseData as $value){
							    $value->party_name 	  = !empty($value->invoicing_party_name)  ? $value->invoicing_party_name.'-'.$value->invoicing_city_name.'-'.$value->invoicing_state_name : $value->party_name.'-'.$value->city_name.'-'.$value->state_name;
							    $value->sgst_value    = !empty($value->sgst_value) ? $value->sgst_value : '0.00';
							    $value->cgst_value    = !empty($value->cgst_value) ? $value->cgst_value : '0.00';
							    $value->igst_value    = !empty($value->igst_value) ? $value->igst_value : '0.00';
							    $value->bill_date     = date(DATEFORMATEXCEL,strtotime($value->bill_date));
							    $value->amount        = number_format((float)$value->amount - $value->conveyance,2,'.','');
						}
				    }
			}
			
			$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();	    
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('state_name','city_name','invoicing_party_name','invoicing_state_name','invoicing_city_name'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
			$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='6') : array();
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo '<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	     /**
	     * generate MIS Report::Instrument Wise Performance Detail
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function instrument_wise_performance_detail($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$response = $totalSampleAmount = $totalInvoiceAmount = array();
	    			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				    
				    $dateRangeData = $models->date_range($postedData['date_from'],$postedData['date_to']);
				    
				    $divisionDataObj = DB::table('divisions')->select('division_id','division_name');
				    $departmentDataObj = DB::table('product_categories')->select('product_categories.p_category_id','product_categories.p_category_name')->where('product_categories.parent_id','0');
						
				    if(!empty($postedData['division_id'])){
						$divisionDataObj->where('division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$departmentDataObj->where('product_categories.p_category_id',$postedData['product_category_id']);
				    }				    
				    $divisionDataObj->orderBy('divisions.division_name','ASC');
				    $divisionDataList  = $divisionDataObj->get();
				    $departmentDataObj->orderBy('product_categories.p_category_id','ASC');
				    $departmentData    = $departmentDataObj->get();				    
				    $equipmentTypeData = DB::table('equipment_type')->where('equipment_type.is_equipment_selected','1')->orderBy('equipment_type.equipment_sort_by','ASC')->get();
				    
				    if(!empty($dateRangeData)){
						foreach($dateRangeData as $dateKey => $date){
							    foreach($divisionDataList as $divisionKey => $division){
									foreach($departmentData as $departmentkey => $department){
										    foreach($equipmentTypeData as $equipmentkey => $equipmentType){
												$coloumKey = $dateKey.$divisionKey.$departmentkey.$equipmentkey;
												$responseData[$coloumKey]['date']       	= $date;
												$responseData[$coloumKey]['division']   	= $division->division_name;
												$responseData[$coloumKey]['department'] 	= $department->p_category_name;
												$responseData[$coloumKey]['equipment']  	= $equipmentType->equipment_name;
												$responseData[$coloumKey]['opening_pending'] 	= $misReport->getPendingEquipmentCount($date,$division,$department,$equipmentType,$type='1');
												$responseData[$coloumKey]['pending']    	= $misReport->getPendingEquipmentCount($date,$division,$department,$equipmentType,$type='2');
												$responseData[$coloumKey]['allocated']  	= $misReport->getPendingEquipmentCount($date,$division,$department,$equipmentType,$type='3');
												$responseData[$coloumKey]['completed']  	= $misReport->getPendingEquipmentCount($date,$division,$department,$equipmentType,$type='4');
												$responseData[$coloumKey]['over_due']   	= $misReport->getPendingEquipmentCount($date,$division,$department,$equipmentType,$type='5');
												$responseData[$coloumKey]['not_due']    	= $misReport->getPendingEquipmentCount($date,$division,$department,$equipmentType,$type='6');
												$responseData[$coloumKey]['closing']    	= abs(($responseData[$coloumKey]['opening_pending'] + $responseData[$coloumKey]['allocated']) - $responseData[$coloumKey]['completed']);
												$postedData['total_opening_pending'][$coloumKey]= $responseData[$coloumKey]['opening_pending'];
												$postedData['total_pending'][$coloumKey]   	= $responseData[$coloumKey]['pending'];
												$postedData['total_allocated'][$coloumKey] 	= $responseData[$coloumKey]['allocated'];
												$postedData['total_completed'][$coloumKey] 	= $responseData[$coloumKey]['completed'];
												$postedData['total_over_due'][$coloumKey]  	= $responseData[$coloumKey]['over_due'];
												$postedData['total_not_due'][$coloumKey]   	= $responseData[$coloumKey]['not_due'];
												$postedData['total_closing'][$coloumKey]   	= $responseData[$coloumKey]['closing'];
										    }
									}
							    }
						}
				    }			    
			}
			
			$responseData 			= !empty($responseData) ? array_values(json_decode(json_encode($responseData),true)) : array();	    
			$responseData 			= $models->unsetFormDataVariablesArray($responseData,array('equipment_type_id','previous_date','order_date','booking_date'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
			$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='7') : array();
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo '<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	    /**
	     * generate MIS Report::Booking Cancellation Detail
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function booking_cancellation_detail($postedData,$searchCriteria){
	
			global $order,$models,$misReport,$mail;
		
			$response = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('order_cancellation_dtls')
						->join('order_master','order_master.order_id','order_cancellation_dtls.order_id')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('users as createdBy','createdBy.id','order_master.created_by')
						->join('city_db','city_db.city_id','order_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
						->join('order_cancellation_types','order_cancellation_types.order_cancellation_type_id','order_cancellation_dtls.cancellation_type_id')
						->join('order_status','order_status.order_status_id','order_cancellation_dtls.cancellation_stage')
						->join('users as cancelledBy','cancelledBy.id','order_cancellation_dtls.cancelled_by')
						->select('departments.department_name','divisions.division_name as Branch','order_master.order_id','order_master.customer_id','order_master.order_date as booking_date','customer_master.customer_name','city_db.city_name as customer_place','product_master_alias.c_product_name as sample_name','order_master.batch_no','order_master.order_no as booking_no','order_cancellation_types.order_cancellation_type_name as cancellation_type','order_cancellation_dtls.cancelled_date','order_cancellation_dtls.cancelled_date as cancelled_time','order_status.order_status_name as stage_of_cancellation','cancelledBy.name as cancelled_by');
				    
				    if(!empty($postedData['is_display_pcd'])){
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }else{
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    
				    $responseDataObj->groupBy('order_cancellation_dtls.order_id');
				    $responseDataObj->orderBy('customer_master.customer_name','ASC')->orderBy('order_master.order_date','ASC');
				    $responseData = $responseDataObj->get();
			    
				    if(!empty($responseData)){
						foreach($responseData as $key => $values){
							    $values->booking_date 		= date(DATEFORMATEXCEL,strtotime($values->booking_date));
							    $values->cancelled_date 		= date(DATEFORMATEXCEL,strtotime($values->cancelled_date));
							    $values->cancelled_time 		= date('h:i A',strtotime($values->cancelled_date));
						}
				    }
			}

			$responseData 		 	= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 		 	= $models->unsetFormDataVariablesArray($responseData,array('order_id','customer_id'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 	 	= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 	 	= !empty($responseData) ? $responseData : array();
			$response['tablefoot']	 	= array();
			$error        		 	= !empty($responseData) ? '1' : '0';
			$message      		 	= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	    /**
	     * generate MIS Report::Booking Amendment Detail
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function booking_amendment_detail($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$response = $totalSampleAmount = $totalInvoiceAmount = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				
				    $responseDataObj = DB::table('order_master')
						->join('divisions','divisions.division_id','order_master.division_id')
						->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
						->join('departments','departments.department_id','department_product_categories_link.department_id')
						->join('order_amended_dtl','order_amended_dtl.oad_order_id','order_master.order_id')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('users as amendmentBy','amendmentBy.id','order_amended_dtl.oad_amended_by')
						->join('city_db','city_db.city_id','order_master.customer_city')
						->join('state_db','state_db.state_id','customer_master.customer_state')
						->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
						->join('order_status','order_status.order_status_id','order_amended_dtl.oad_amended_stage')
						->select('departments.department_name','divisions.division_name as Branch','order_master.order_id','customer_master.customer_name as party_name','city_db.city_name as place_name','order_master.booking_date as booking_date','order_master.order_no as booking_no','product_master_alias.c_product_name as sample_name','order_master.batch_no','order_amended_dtl.oad_amended_date as amended_date','amendmentBy.name as amended_by','order_status.order_status_name as amended_stage','order_amended_dtl.oad_amended_by as amendment_count');
				    
				    if(!empty($postedData['is_display_pcd'])){
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }else{
						$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));	
				    }
				    if(!empty($postedData['division_id'])){
						$responseDataObj->where('order_master.division_id',$postedData['division_id']);
				    }
				    if(!empty($postedData['product_category_id'])){
						$responseDataObj->where('order_master.product_category_id',$postedData['product_category_id']);
				    }
				    
				    $responseDataObj->groupBy('order_amended_dtl.oad_order_id');
				    $responseDataObj->orderBy('customer_master.customer_name','ASC')->orderBy('order_master.order_date','ASC');
				    $responseData = $responseDataObj->get();
			    
				    if(!empty($responseData)){
						foreach($responseData as $key => $values){
							    $values->booking_date 		= date(DATEFORMATEXCEL,strtotime($values->booking_date));
							    $values->amended_date 		= date(DATEFORMATEXCEL,strtotime($values->amended_date));
							    $values->amendment_count 		= DB::table('order_amended_dtl')->where('order_amended_dtl.oad_order_id',$values->order_id)->count();
							    $postedData['total_amendment_count'][$key] = $values->amendment_count;
						}
				    }
			}

			$responseData 		 	= !empty($responseData) ? json_decode(json_encode($responseData),true) : array();
			$responseData 		 	= $models->unsetFormDataVariablesArray($responseData,array('order_id'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 	 	= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 	 	= !empty($responseData) ? $responseData : array();
			$response['tablefoot']	 	= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'],$postedData,$type='9') : array();
			$error        		 	= !empty($responseData) ? '1' : '0';
			$message      		 	= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
	    /**
	     * generate MIS Report::Booking Amendment Detail
	     *
	     * @return \Illuminate\Http\Response
	     */
	    public function daily_sales_detail($postedData,$searchCriteria) {
	
			global $order,$models,$misReport,$mail;
		
			$responseData = $response = $tableHead = $totalInvoiceAmount = array();
			
			if(!empty($postedData['date_from']) && !empty($postedData['date_to'])){
				    
				    $coloumArrayData = array('0' => 'Reports Booked','1' => 'Reports Billing','2' => 'Reports Amount');
				    
				    if(!empty($postedData['division_id'])){
						$divisionDataList = DB::table('divisions')->where('division_id',$postedData['division_id'])->select('division_id','division_name')->get();
				    }else{
						$divisionDataList = DB::table('divisions')->select('division_id','division_name')->get();	
				    }
				    
				    $departmentDataObj = DB::table('product_categories')->where('product_categories.parent_id','0');
				    if(!empty($postedData['product_category_id'])){
						$departmentDataObj->where('product_categories.p_category_id',$postedData['product_category_id']);
				    }
				    $departmentDataObj->orderBy('product_categories.p_category_id','ASC');
				    $departmentData = $departmentDataObj->pluck('product_categories.p_category_name','product_categories.p_category_id')->all();
				    array_push($departmentData,"SUM");
	
				    if(!empty($coloumArrayData) && !empty($departmentData) && !empty($divisionDataList)){
						$dateRangeData = $models->date_range($postedData['date_from'], $postedData['date_to']);
						foreach($divisionDataList as $key => $divisions){
							    $totalDataDateWise = array();
							    foreach($dateRangeData as $dateRangeKey => $dateRange){
									$totalData = array();
									foreach($departmentData as $departmentId => $departmentName){
										    $divisionName = $divisions->division_name;
										    $coloumName	  = $dateRange;
										    $responseData[$divisionName.$dateRange]['branch']  = $divisions->division_name;
										    $responseData[$divisionName.$dateRange]['date']    = $dateRange;
										    foreach($coloumArrayData as $getterKey => $coloumArray){
												$countValue = $misReport->getDailySalesResultant($getterKey,$divisions->division_id,$departmentId,$dateRange);
												$responseData[$divisionName.$dateRange][$departmentName.'|'.$coloumArray]  = $countValue;
												$totalData[$divisionName.$dateRange][$coloumArray][]   			   = $countValue;
												if($departmentName == 'SUM'){
													$responseData[$divisionName.$dateRange][$departmentName.'|'.$coloumArray] = round(array_sum($totalData[$divisionName.$dateRange][$coloumArray]));    
												}
										    }										    
									}
							    }
						}
						foreach($responseData as $key => $responseValueAll){
							    foreach($responseValueAll as $keyTotal => $responseValue){
									$totalDataDateWise[$keyTotal][] = $responseValue;
							    }
						}
				    }				    
			}
			
			$responseData 		 	= !empty($responseData) ? array_values(json_decode(json_encode($responseData),true)) : array();
			$responseData 		 	= $models->unsetFormDataVariablesArray($responseData,array('order_id'));
			$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
			$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'].'('.count($responseData).')' : '';
			$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
			$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
			$response['tableHead'] 	 	= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 	 	= !empty($responseData) ? $responseData : array();
			$response['tablefoot']	 	= !empty($totalDataDateWise) ? $misReport->getTableFooterData($response['tableHead'],$totalDataDateWise,$type='10') : array();
			$error        		 	= !empty($responseData) ? '1' : '0';
			$message      		 	= $error ? '' : config('messages.message.noRecordFound');
			
			//Saving Data in Session
			Session::set('response', $response);
			
			//echo'<pre>'; print_r($response); die;
			return array($error,$message,$response);
	    }
	    
}