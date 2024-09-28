<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Session;
use Validator;
use Route;
use App\Company;
use App\Order;
use App\Models;
use App\Report;
use App\Setting;
use App\ProductCategory;
use App\InvoiceHdr;
use App\InvoiceHdrDetail;
use App\NumbersToWord;
use App\SendMail;

class DownloadController extends Controller
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
		
        global $order,$report,$models,$invoice,$mail,$numbersToWord;
	
        $order   	= new Order();
	$report 	= new Report();
        $models  	= new Models();
	$invoice 	= new invoiceHdr();
	$numbersToWord  = new NumbersToWord();
	$mail    	= new SendMail();
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
    * generate Job Order PDF.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function generateAnalyticalSheetIPdf(Request $request){
		
	global $order,$report,$models,$invoice,$mail,$numbersToWord;
	
        if($request->isMethod('post') && !empty($request->order_id) && !empty($request->downloadType) && !empty($request->generate_analytical_sheet_I_pdf)){
	    return $models->downloadPDF($request->all(),$contentType='order');
	}else{
	    $message = config('messages.message.fileDownloadErrorMsg');
	    return redirect('dashboard')->with('errorMsg',$message);
	}
    }
    
    /**
    * generate Job Order PDF.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function generateAnalyticalSheetIIPdf(Request $request){
		
	global $order,$report,$models,$invoice,$mail,$numbersToWord;
	
        if($request->isMethod('post') && !empty($request->order_id) && !empty($request->downloadType) && !empty($request->generate_analytical_sheet_II_pdf)){
	    return $models->downloadPDF($request->all(),$contentType='jobSheet');
	}else{
	    $message = config('messages.message.fileDownloadErrorMsg');
	    return redirect('dashboard')->with('errorMsg',$message);
	}
    }
    
    /**
    * generate Job Order PDF.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function generateReportPdf(Request $request){
	
	global $order,$report,$models,$invoice,$mail,$numbersToWord;
	
	if($request->isMethod('post') && !empty($request->order_id) && !empty($request->downloadType) && !empty($request->generate_report_pdf)){
	    
	    $currentDateTime = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
	    $currentDateTime = !empty($request->back_report_date) ? $models->getFormatedDateTime($request->back_report_date) : $currentDateTime;
	    
	    //updating Order Staus and Log
	    list($currentOrderStage,$nextOrderStage) = $report->getOrderStageWithOrWithoutAmendment($request->order_id);
	    
	    if(!empty($currentOrderStage) && !empty($nextOrderStage)){
		$report->updateReportApprovingDate($request->order_id,$currentDateTime);
		$order->updateOrderStausLog($request->order_id,$currentOrderStage,$currentDateTime);
		$order->updateOrderStatusToNextPhase($request->order_id,$nextOrderStage,$currentDateTime);
	    }
	    return $models->downloadSaveMailPDF($request->all(),$contentType='report');
	}else{
	    $message = config('messages.message.fileDownloadErrorMsg');
	    return redirect('dashboard')->with('errorMsg',$message);
	}
    }
    
    /**
    * generate Job Order PDF.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function generateInvoicePdf(Request $request){
	
	global $order,$models,$invoice,$mail,$numbersToWord;
	
        if($request->isMethod('post') && !empty($request->invoice_id) && !empty($request->downloadType) && !empty($request->generate_invoice_pdf)){
	    return $models->downloadSaveMailPDF($request->all(),$contentType='invoice');
	}else{
	    $message = config('messages.message.fileDownloadErrorMsg');
	    return redirect('dashboard')->with('errorMsg',$message);
	}
    }
	
    /************************************
     * Description : Download customers excel
     * Date        : 29-01-18
     * Parameter   : 
     * @return     : \Illuminate\Http\Response
     ************************************/
    public function generateCustomerMasterDocuments(Request $request){
    
	global $models;

	$responseData = $filterData = array();
	$filterData = $request->all();
	$customers = DB::table('customer_master')
	    ->leftJoin('order_sample_priority','order_sample_priority.sample_priority_id','=','customer_master.customer_priority_id')
	    ->join('customer_invoicing_types','customer_invoicing_types.invoicing_type_id','=','customer_master.invoicing_type_id')
	    ->join('countries_db','countries_db.country_id','=','customer_master.customer_country')
	    ->join('state_db','state_db.state_id','=','customer_master.customer_state')
	    ->join('city_db','city_db.city_id','=','customer_master.customer_city')
	    ->join('customer_billing_types','customer_master.billing_type','=','customer_billing_types.billing_type_id')
	    ->join('users','customer_master.sale_executive','=','users.id')
	    ->join('users as u','customer_master.created_by', '=', 'u.id')
	    ->select('customer_master.customer_code','customer_master.customer_name','customer_master.customer_address','customer_master.customer_email','customer_master.customer_mobile','customer_master.customer_phone','countries_db.country_name as customer_country','state_db.state_name as customer_state','city_db.city_name as customer_city','customer_master.customer_pincode','customer_master.customer_vat_cst','customer_billing_types.billing_type as billing_type','order_sample_priority.sample_priority_name as customer_priority_type','customer_invoicing_types.invoicing_type','customer_master.mfg_lic_no','customer_master.customer_pan_no','customer_master.customer_tan_no','customer_master.customer_gst_no','customer_master.bank_account_no','customer_master.bank_account_name','customer_master.bank_name','customer_master.bank_branch_name','customer_master.bank_rtgs_ifsc_code','u.name as created_by')
	    ->orderBy('customer_id','ASC')
	    ->get();
	    
	$customersList          		= !empty($customers) ? json_decode(json_encode($customers),true) : array();
	$filterData['heading'] 			= 'All Customers List :'.'('.count($customers).')';
	$filterData['mis_report_name'] 		= 'Customers List';
	$responseData['tableHead'] 		= !empty($customersList) ? array_keys(end($customersList)) : array();
	$responseData['tableBody'] 		= !empty($customersList) ? $customersList : array();

	if(!empty($request->generate_customer_documents) && strtolower($request->generate_customer_documents) == 'excel'){
		return $models->downloadExcel($responseData,$filterData);
	}else{
		return redirect('dashboard')->withErrors('Permission Denied!');
	}
    }
    
    /************************************
     * Description : Download parameter excel
     * Date        : 30-01-18
     * Parameter   : 
     * @return     : \Illuminate\Http\Response
     ************************************/
    public function generateParameterMasterDocuments(Request $request){
	
	global $models;
	
	$returnData = array();
	
	if(!empty($request->generate_parameter_documents) && $request->generate_parameter_documents == 'Excel'){
	    
		$parameterObj = DB::table('test_parameter')
				    ->join('test_parameter_categories','test_parameter_categories.test_para_cat_id','test_parameter.test_parameter_category_id')
				    ->leftJoin('product_categories','test_parameter_categories.product_category_id','product_categories.p_category_id')
				    ->leftJoin('users as createdBy','test_parameter.created_by', '=', 'createdBy.id')

					->leftJoin('test_parameter_equipment_types','test_parameter.test_parameter_id','test_parameter_equipment_types.test_parameter_id')
				    ->leftJoin('equipment_type','test_parameter_equipment_types.equipment_type_id','equipment_type.equipment_id')
				    ->join('users', 'test_parameter.created_by', '=', 'users.id')
				    ->select('product_categories.p_category_name as department','test_parameter.test_parameter_code as test_parameter_code','test_parameter.test_parameter_name','test_parameter_categories.test_para_cat_name as test_parameter_category','test_parameter.cost_price','test_parameter.selling_price','equipment_type.equipment_name as equipment_type','createdBy.name as created_by','test_parameter.created_at as created_on','test_parameter.updated_at as updated_on');

		if(!empty($request->test_parameter_category_id)){
		    $parameters = $parameterObj->where('test_parameter.test_parameter_category_id','=',$request->test_parameter_category_id);
		}

		if(!empty($request->equipment_type_id)){
		    $parameters = $parameterObj->where('test_parameter_equipment_types.equipment_type_id','=',$request->equipment_type_id);
		}
		$allParametersList = $parameterObj->groupBy('test_parameter.test_parameter_id')->get();

		

	    //Changing Date Time Format
	    $models->formatTimeStampFromArrayExcel($allParametersList,DATEFORMATEXCEL);
	    
	    $allParametersList  		= !empty($allParametersList) ? json_decode(json_encode($allParametersList),true) : array();
	    $allParametersList 			= $models->unsetFormDataVariablesArray($allParametersList,array('canDispatchOrder'));	    
	    $filterData['heading'] 		= 'All Parameters List('.count($allParametersList).')';
	    $filterData['mis_report_name'] 	= 'parameters_list_';
	    $responseData['tableHead'] 		= !empty($allParametersList) ? array_keys(end($allParametersList)) : array();
	    $responseData['tableBody'] 		= !empty($allParametersList) ? $allParametersList : array();
		//echo'<pre>'; print_r($responseData); die;
	    return $models->downloadExcel($responseData,$filterData);
	}else{
	    return redirect()->back()->withErrors('Error in generating!');
	}
    }
	
    /************************************
     * Description : Download product test excel
     * Date        : 30-01-18
     * Parameter   : 
     * @return     : \Illuminate\Http\Response
     ************************************/
    public function generateTestMasterDocuments(Request $request){
	
	global $order,$models,$invoice,$mail,$numbersToWord;
	
	$user_id            		= defined('USERID') ? USERID : '0';
	$department_ids     		= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           		= defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids 		= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	$request['product_category_id'] = !empty($request['product_category_id']) ? array($request['product_category_id']) : $department_ids;

	$testParametersObj = DB::table('product_test_dtl')
			    ->join('product_test_hdr','product_test_dtl.test_id','product_test_hdr.test_id')
			    ->join('product_master','product_master.product_id','product_test_hdr.product_id')
			    ->join('product_categories','product_master.p_category_id','product_categories.p_category_id')
			    ->leftJoin('product_categories as parent_category','product_categories.parent_id','parent_category.p_category_id')
			    ->leftJoin('product_categories as product_section','product_section.p_category_id','parent_category.parent_id')	
			    ->join('test_standard','test_standard.test_std_id','product_test_hdr.test_standard_id')
			    ->leftJoin('test_parameter','product_test_dtl.test_parameter_id','test_parameter.test_parameter_id')
			    ->leftJoin('test_parameter_categories','test_parameter.test_parameter_category_id','test_parameter_categories.test_para_cat_id')
			    ->leftJoin('equipment_type','product_test_dtl.equipment_type_id','equipment_type.equipment_id')
			    ->leftJoin('method_master','product_test_dtl.method_id','method_master.method_id')
			    ->join('users','product_test_dtl.created_by','users.id')
			    ->select('product_test_hdr.test_code','product_categories.p_category_name as product_category','parent_category.p_category_name as product_sub_category','product_master.product_name','test_standard.test_std_name','product_test_hdr.wef','product_test_hdr.upto','test_parameter.test_parameter_name as test_parameter','test_parameter_categories.test_para_cat_name as test_parameter_category','equipment_type.equipment_name as equipment','method_master.method_name as method','product_test_dtl.standard_value_from','product_test_dtl.standard_value_to','users.name as created_by', 'product_test_hdr.created_at as created_on');
	
	if(!empty($request->test_id)){
	    $test_id = !empty($request->test_id) ? $request->test_id  :  '0';
	    $testParametersObj->where('product_test_dtl.test_id',$test_id);	
	}
	if(!empty($request->product_category_id) && is_array($request['product_category_id'])){
	    $testParametersObj->whereIn('product_categories.p_category_id',$request->product_category_id);
	    $testParametersObj->orWhereIn('product_section.p_category_id',$request->product_category_id);
	}
	 
	//Filtering records according to search keyword
	if(!empty($request->keyword)){
	    $keyword = $request->keyword;
		$testParametersObj->where('product_test_hdr.test_code','like','%'.$keyword.'%')
			->Orwhere('product_test_hdr.test_code','like','%'.$keyword.'%')
			->Orwhere('product_categories.p_category_name','=','%'.$keyword.'%')
			->Orwhere('product_master.product_name','like','%'.$keyword.'%')
			->Orwhere('test_standard.test_std_name','like','%'.$keyword.'%')
			->Orwhere('product_test_hdr.wef','like','%'.$models->convertDateFormat($keyword).'%')
			->Orwhere('product_test_hdr.upto','like','%'.$models->convertDateFormat($keyword).'%')
			->Orwhere('users.name','like','%'.$keyword.'%')
			->Orwhere('product_section.p_category_name','like','%'.$keyword.'%')
			->Orwhere('product_test_hdr.created_at','like','%'.date("Y-m-d", strtotime($keyword)).'%')
			->Orwhere('product_test_hdr.updated_at','like','%'.$keyword.'%');
	}
	if(!empty($request->search_test_code)){
					$testParametersObj->where('product_test_hdr.test_code','like','%'.$request->search_test_code.'%');
				}
				if(!empty($request->search_p_category_id)){
					$testParametersObj->where('product_categories.p_category_id','=',$request->search_p_category_id);
				}
				if(!empty($request->search_product_name)){
					$testParametersObj->where('product_master.product_name','like','%'.$request->search_product_name.'%');
				}
				if(!empty($request->search_test_std_name)){
					$testParametersObj->where('test_standard.test_std_name','like','%'.$request->search_test_std_name.'%');
				}
				if(!empty($request->search_wef)){
					$testParametersObj->where('product_test_hdr.wef','like','%'.$models->convertDateFormat($request->search_wef).'%');
				}
				if(!empty($request->search_upto)){
					$testParametersObj->where('product_test_hdr.upto','like','%'.$models->convertDateFormat($request->search_upto).'%');
				}
				if(!empty($request->search_created_by)){
					$testParametersObj->where('users.name','like','%'.$request->search_created_by.'%');
				}
				if(!empty($request->search_product_section_name)){
					$testParametersObj->where('product_section.p_category_name','like','%'.$request->search_product_section_name.'%');
				}
				if(!empty($request->search_created_at)){
					$testParametersObj->where('product_test_hdr.created_at','like','%'.date("Y-m-d", strtotime($request->search_created_at)).'%');
				}
				if(!empty($request->search_updated_at)){
					$testParametersObj->where('product_test_hdr.updated_at','like','%'.$request->search_updated_at.'%');
				}	
	$testProducts	= $testParametersObj->orderBy('product_test_dtl.test_id','ASC')->get();
	$models->formatTimeStampFromArrayExcel($testProducts,DATEFORMATEXCEL);		
	//echo'<pre>'; print_r(count($testProducts));die;
	
	$testProductsList          			= !empty($testProducts) ? json_decode(json_encode($testProducts),true) : array();
	$testProductsList 					= $models->unsetFormDataVariablesArray($testProductsList,array('canDispatchOrder'));	    
	$responseData['heading'] 			= 'Test Product List :'.'('.count($testProductsList).')';
	$responseData['mis_report_name'] 	= 'Test Product List';
	$responseData['tableHead'] 			= !empty($testProductsList) ? array_keys(end($testProductsList)) : array();
	$responseData['tableBody'] 			= !empty($testProductsList) ? $testProductsList : array();
	//echo'<pre>'; print_r($responseData);die;

	if(!empty($request->generate_product_test_documents) && $request->generate_product_test_documents == 'PDF'){
	    $pdfHeaderContent 			= $models->getHeaderFooterTemplate();
	    $responseData['header_content']	= $pdfHeaderContent->header_content;
	    $responseData['footer_content']	= $pdfHeaderContent->footer_content;
	    return $models->downloadPDF($responseData,$contentType='product_test');
	}else if(!empty($request->generate_product_test_documents) && $request->generate_product_test_documents == 'Excel'){
	    return $models->generateExcel($responseData);
	}else{
	    return redirect('dashboard')->withErrors('Permission Denied!');
	}  
    }
	
	 /*******************************************************Scope-II ****************************************/
    /************************************
     *
     * Description : Download detectors excel
     * Date        : 13-07-18
     * Parameter   : 
     * @return     : \Illuminate\Http\Response
     ************************************/
    public function generateDetectorsDocuments(Request $request){
		
		global $models;
		
		$returnData = array();
		
		$equipment_type_id = !empty($request->equipment_type_id) ? $request->equipment_type_id : '';
	
		if(!empty($request->generate_detector_documents) && $request->generate_detector_documents == 'Excel'){
		 
			$detector = DB::table('detector_master')
						->join('equipment_type','detector_master.equipment_type_id','equipment_type.equipment_id')
						->join('product_categories', 'product_categories.p_category_id', '=', 'detector_master.product_category_id')
						->join('users', 'detector_master.created_by','users.id')
						->select('detector_master.detector_code','detector_master.detector_name','detector_master.detector_desc','equipment_type.equipment_name as equipment_type','product_categories.p_category_name as parent_category','users.name as createdBy','detector_master.created_at as created_on','detector_master.updated_at as updated_on');
						
				if(!empty($equipment_type_id)){
					$detector->where('detector_master.equipment_type_id','=',$equipment_type_id);	
				}		
			$allDetectorList=$detector->get();		

			//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($allDetectorList,DATEFORMATEXCEL);
		   
			$allDetectorList  				= !empty($allDetectorList) ? json_decode(json_encode($allDetectorList),true) : array();
			$allDetectorList 				= $models->unsetFormDataVariables($allDetectorList,array('canDispatchOrder'));	    
			$filterData['heading'] 			= 'All Detectors List('.count($allDetectorList).')';
			$filterData['mis_report_name'] 	= 'detectors_list_';
			$responseData['tableHead'] 		= !empty($allDetectorList) ? array_keys(end($allDetectorList)) : array();
			$responseData['tableBody'] 		= !empty($allDetectorList) ? $allDetectorList : array();
			
			//echo'<pre>'; print_r($request->all()); die;
			return $models->downloadExcel($responseData,$filterData);
		}else{
			return redirect()->back()->withErrors('Error in generating!');
		}
    }
	/************************************
     *
     * Description : Download equipments excel
     * Date        : 13-07-18
     * Parameter   : 
     * @return     : \Illuminate\Http\Response
     ************************************/
    public function generateEquipmentsDocuments(Request $request){
		global $models;
		if(!empty($request->generate_equipment_documents) && $request->generate_equipment_documents == 'Excel'){
			$allEquipmentsList = DB::table('equipment_type')
			->leftjoin('users', 'equipment_type.created_by', '=', 'users.id')
			->select('equipment_type.equipment_code','equipment_type.equipment_name','equipment_type.equipment_capacity','equipment_type.equipment_description','users.name as createdBy','equipment_type.created_at as created_on','equipment_type.updated_at as updated_on')
			->get();
			
			//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($allEquipmentsList,DATEFORMATEXCEL);
		   
			$allEquipmentsList  				= !empty($allEquipmentsList) ? json_decode(json_encode($allEquipmentsList),true) : array();
			$allEquipmentsList 				= $models->unsetFormDataVariables($allEquipmentsList,array('canDispatchOrder'));	    
			$filterData['heading'] 			= 'All Equipments List('.count($allEquipmentsList).')';
			$filterData['mis_report_name'] 	= 'equipments_list_';
			$responseData['tableHead'] 		= !empty($allEquipmentsList) ? array_keys(end($allEquipmentsList)) : array();
			$responseData['tableBody'] 		= !empty($allEquipmentsList) ? $allEquipmentsList : array();
			
			//echo'<pre>'; print_r($request->all()); die;
			return $models->downloadExcel($responseData,$filterData);
		}else{
			return redirect()->back()->withErrors('Error in generating!');
		}
	}
	
	/************************************
     *
     * Description : Download methods excel
     * Date        : 13-07-18
     * Parameter   : 
     * @return     : \Illuminate\Http\Response
     ************************************/
    public function generateMethodsDocuments(Request $request){
		
		global $models;
	
		$equipment_type_id = !empty($request->equipment_type_id) ? $request->equipment_type_id : '';

		if(!empty($request->generate_method_documents) && $request->generate_method_documents == 'Excel'){
			$method = DB::table('method_master')
					->join('equipment_type','method_master.equipment_type_id','equipment_type.equipment_id')
					->join('product_categories', 'product_categories.p_category_id', '=', 'method_master.product_category_id')
					->join('users', 'method_master.created_by','users.id')
					->select('method_master.method_code','method_master.method_name','method_master.method_desc','equipment_type.equipment_name as equipment_type','product_categories.p_category_name as parent_category','users.name as createdBy','method_master.created_at as method_created_at','method_master.updated_at as method_updated_at');
					
				if(!empty($equipment_type_id)){
					$method->where('method_master.equipment_type_id','=',$equipment_type_id);	
				}		
			$methodsList=$method->get();
		//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($methodsList,DATEFORMATEXCEL);
		   
			$methodsList  				= !empty($methodsList) ? json_decode(json_encode($methodsList),true) : array();
			$methodsList 					= $models->unsetFormDataVariables($methodsList,array('canDispatchOrder'));	    
			$filterData['heading'] 				= 'All Methods List('.count($methodsList).')';
			$filterData['mis_report_name'] 		= 'methods_list_';
			$responseData['tableHead'] 			= !empty($methodsList) ? array_keys(end($methodsList)) : array();
			$responseData['tableBody'] 			= !empty($methodsList) ? $methodsList : array();
			
			//echo'<pre>'; print_r($responseData); die;
			return $models->downloadExcel($responseData,$filterData);
		}else{
			return redirect()->back()->withErrors('Error in generating!');
		}
	}	
	/************************************
     *
     * Description : Download methods excel
     * Date        : 13-07-18
     * Parameter   : 
     * @return     : \Illuminate\Http\Response
     ************************************/
    public function generateTestStandardDocuments(Request $request){
		
		global $models;
	
		$product_category_id = !empty($request->product_category_id) ? $request->product_category_id : '';

		if(!empty($request->generate_test_standard_documents) && $request->generate_test_standard_documents == 'Excel'){
			$testStandardObj = DB::table('test_standard')						  
						->join('users', 'test_standard.created_by', '=', 'users.id')
						->join('product_categories', 'product_categories.p_category_id', '=', 'test_standard.product_category_id')
						->select('test_standard.test_std_code as test_standard_code','test_standard.test_std_name as test_standard_name','product_categories.p_category_name as parent_product_category','users.name as createdBy','test_standard.created_at as created_on','test_standard.updated_at as updated_on');
				if(!empty($product_category_id)){
					$testStandardObj->where('test_standard.product_category_id','=',$product_category_id);
				}
			$testStdList=$testStandardObj->get();
			//Changing Date Time Format
				$models->formatTimeStampFromArrayExcel($testStdList);
			   
				$testStdList  						= !empty($testStdList) ? json_decode(json_encode($testStdList),true) : array();
				$testStdList 						= $models->unsetFormDataVariables($testStdList,array('canDispatchOrder'));	    
				$filterData['heading'] 				= 'All Test Stanadards List('.count($testStdList).')';
				$filterData['mis_report_name'] 		= 'test_standards_list_';
				$responseData['tableHead'] 			= !empty($testStdList) ? array_keys(end($testStdList)) : array();
				$responseData['tableBody'] 			= !empty($testStdList) ? $testStdList : array();
				
				return $models->downloadExcel($responseData,$filterData);
		}else{
			return redirect()->back()->withErrors('Error in generating!');	
		}
	}
	
	/************************************
     *
     * Description : Download methods excel
     * Date        : 13-07-18
     * Parameter   : 
     * @return     : \Illuminate\Http\Response
     ************************************/
    public function generateTestParameterCategoriesDocuments(Request $request){

		global $models;
		$parent_id = !empty($request->parent_id) ? $request->parent_id : '';
		
		if(!empty($request->generate_parameter_categories_documents) && $request->generate_parameter_categories_documents == 'Excel'){
		
			$dataObj = DB::table('test_parameter_categories')
						->leftjoin('test_parameter_categories as category','test_parameter_categories.parent_id','=','category.test_para_cat_id')
						->join('product_categories', 'product_categories.p_category_id', '=', 'test_parameter_categories.product_category_id')
						->join('users', 'test_parameter_categories.created_by', '=', 'users.id')				   
						->select('test_parameter_categories.test_para_cat_code as parameter_category_code','test_parameter_categories.test_para_cat_name as parameter_category_name','test_parameter_categories.test_para_cat_print_desc as parameter_category_description','category.test_para_cat_name as parent_category','product_categories.p_category_name as product_section','users.name as createdBy','test_parameter_categories.created_at','test_parameter_categories.updated_at');
						if(!empty($parent_id)){
							$dataObj->where('test_parameter_categories.parent_id','=',$parent_id)
									->orwhere('test_parameter_categories.test_para_cat_id','=',$parent_id);
						}
			$dataList =	$dataObj->get();

			//Changing Date Time Format
				$models->formatTimeStampFromArrayExcel($dataList);

				$dataList  							= !empty($dataList) ? json_decode(json_encode($dataList),true) : array();
				$dataList 							= $models->unsetFormDataVariables($dataList,array('canDispatchOrder'));	    
				$filterData['heading'] 				= 'All Test Stanadards List('.count($dataList).')';
				$filterData['mis_report_name'] 		= 'test_standards_list_';
				$responseData['tableHead'] 			= !empty($dataList) ? array_keys(end($dataList)) : array();
				$responseData['tableBody'] 			= !empty($dataList) ? $dataList : array();
				
				//echo'<pre>'; print_r($responseData); die;
				return $models->downloadExcel($responseData,$filterData);
		}else{
			return redirect()->back()->withErrors('Error in generating!');	
		}
	}
}