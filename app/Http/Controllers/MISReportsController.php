<?php

/*****************************************************
 *Description : Common Function Configuration File
 *Created By  : Praveen-Singh
 *Created On  : 15-Dec-2017
 *Modified On : 10-Oct-2018
 *Package     : ITC-ERP-PKL
 ******************************************************/

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
	public function __construct()
	{

		global $order, $models, $misReport, $mail;

		$order     = new Order();
		$misReport = new MISReport();
		$models    = new Models();
		$mail      = new SendMail();

		$this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$this->auth = Auth::user();
			parent::__construct($this->auth);
			//Checking current request is allowed or not
			$allowedAction = array('index', 'navigation');
			$actionData    = explode('@', Route::currentRouteAction());
			$action        = !empty($actionData[1]) ? trim(strtolower($actionData[1])) : '0';
			if (defined('NOTALlOWEDTONAVIGATE') && empty(NOTALlOWEDTONAVIGATE) && in_array($action, $allowedAction)) {
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
	public function index()
	{

		global $order, $models, $misReport, $mail;

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		//Setting & Resetting Session Data
		Session::set('defaultMisReport', basename(request()->path()));
		Session::set('response_token_in', uniqid());
		Session::forget('response');

		return view('MIS.index', ['title' => 'MIS Reports', '_mis' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * generate MIS Report
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getMISReportTypes(Request $request)
	{

		global $order, $models, $misReport, $mail;

		$error       	  = '1';
		$message     	  = '';
		$data        	  = '';
		$currentDate 	  = date('Y-m-d');
		$formData    	  = array();
		$defaultMisReport = Session::get('defaultMisReport');

		$reportFormTypeObj = DB::table('mis_report_default_types')->select('mis_report_default_types.mis_report_code as id', 'mis_report_default_types.mis_report_name as name')->where('mis_report_default_types.mis_report_status', '1');

		if (!empty($defaultMisReport) && $defaultMisReport != 'all') {
			$reportFormTypeObj->where('mis_report_default_types.mis_report_code', trim($defaultMisReport));
		}
		$reportFormTypes = $reportFormTypeObj->orderBy('mis_report_default_types.mis_report_order_by', 'ASC')->get();

		return response()->json(['error' => $error, 'message' => $message, 'returnData' => $reportFormTypes]);
	}

	/**
	 * generate MIS Report
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generateMISReport(Request $request)
	{

		global $order, $models, $misReport, $mail;

		$error        = '0';
		$message      = config('messages.message.error');
		$data         = '';
		$currentDate  = date('Y-m-d');
		$formData     = $returnData = array();

		//Saving record in orders table
		if ($request->isMethod('post') && !empty($request->formData)) {

			parse_str($request->formData, $formData);
			unset($formData['_token']);
			$searchCriteria = $misReport->filterSearchCriteria($formData);

			//Setting Token Out & Reseting Session Data
			Session::set('response_token_out', Session::get('response_token_in'));
			Session::forget('response');

			if (!empty($formData['mis_report_name'])) {

				$formData['date_from']      			= !empty($formData['date_from']) ? $models->getFormatedDate($formData['date_from']) : '0';
				$formData['date_to']   	    			= !empty($formData['date_to']) ? $models->getFormatedDate($formData['date_to']) : '0';
				$formData['expected_due_date_from']     = !empty($formData['expected_due_date_from']) ? $models->getFormatedDate($formData['expected_due_date_from']) : '0';
				$formData['expected_due_date_to']   	= !empty($formData['expected_due_date_to']) ? $models->getFormatedDate($formData['expected_due_date_to']) : '0';
				$formData['is_display_pcd'] 			= !empty($formData['product_category_id']) && $formData['product_category_id'] == '2' && defined('PHARMA_BACK_DATE_VIEW') && PHARMA_BACK_DATE_VIEW ? '1' : '0';

				if ($formData['mis_report_name'] == 'DBD001') {			//Daily Booking Detail
					$formData['headingTxt'] = 'Daily Booking Detail';
					list($error, $message, $returnData) = $this->daily_booking_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'PWSCDW002') {		//Party Wise Sample Count-Date Wise
					$formData['headingTxt'] = 'Party Wise Sample Count-Date Wise';
					list($error, $message, $returnData) = $this->party_wise_sample_count_date_wise($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'PWSCMW003') {		//Party Wise Sample Count-Month Wise
					$formData['headingTxt'] = 'Party Wise Sample Count-Month Wise';
					list($error, $message, $returnData) = $this->party_wise_sample_count_month_wise($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'MEWBEN004') {		//Marketing Executive Wise-By Executive Name
					$formData['headingTxt'] = 'Marketing Executive Wise-By Executive Name';
					list($error, $message, $returnData) = $this->marketing_executive_name_wise_sample_count($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'MEWBPWSC005') {	//Marketing Executive Wise-By Place Wise Sample Count
					$formData['headingTxt'] = 'Marketing Executive Wise-By Place Wise Sample Count';
					list($error, $message, $returnData) = $this->marketing_executive_place_wise_sample_count($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'TAT006') {		//TAT Report
					$formData['headingTxt'] = 'TAT Report';
					list($error, $message, $returnData) = $this->tat_report($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'UWPD007') {		//User Wise Performance Detail
					$formData['headingTxt'] = 'User Wise Performance Detail';
					list($error, $message, $returnData) = $this->user_wise_performance_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'SLS008') {		//Sample Log Status
					$formData['headingTxt'] = 'Sample Log Status';
					list($error, $message, $returnData) = $this->sample_log_status($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'PWS009') {		//Parameter wise Scheduling
					$formData['headingTxt'] = 'Parameter wise Scheduling';
					list($error, $message, $returnData) = $this->parameter_wise_scheduling($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'SRD010') {		//Sales Report Detail
					$formData['headingTxt'] = 'Sales Report Detail';
					list($error, $message, $returnData) = $this->sales_report_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'DID011') {		//Daily Invoice Detail
					$formData['headingTxt'] = 'Daily Invoice Detail';
					list($error, $message, $returnData) = $this->daily_invoice_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'IWPD012') {		//Instrument Wise Performance Detail
					$formData['headingTxt'] = 'Instrument Wise Performance Detail';
					list($error, $message, $returnData) = $this->instrument_wise_performance_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'AWPS013') {		//Analyst Wise Performance Summary
					$formData['headingTxt'] = 'Analyst Wise Performance Summary';
					list($error, $message, $returnData) = $this->analyst_wise_performance_summary($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'BCD014') {		//Booking Cancellation Detail
					$formData['headingTxt'] = 'Booking Cancellation Detail';
					list($error, $message, $returnData) = $this->booking_cancellation_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'BAD015') {		//Booking Amendment Detail
					$formData['headingTxt'] = 'Booking Amendment Detail';
					list($error, $message, $returnData) = $this->booking_amendment_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'DSD016') {		//Daily Sales Detail
					$formData['headingTxt'] = 'Daily Sales Detail';
					list($error, $message, $returnData) = $this->daily_sales_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'DESD017') {		//Delay Status Detail
					$formData['headingTxt'] = 'Delay Status Detail';
					list($error, $message, $returnData) = $this->delay_status_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'ASR018') {		//Account Sales Detail
					$formData['headingTxt'] = 'Account Sales Detail';
					list($error, $message, $returnData) = $this->account_sales_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'ESTR19') {		//Employee Sales Target Detail
					$formData['headingTxt'] = 'Employee Sales Target Detail';
					list($error, $message, $returnData) = $this->employee_sales_target_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'ESTR20') {		//Employee Sales Target Detail(NEW)
					$formData['headingTxt'] = 'Employee Sales Target Detail';
					list($error, $message, $returnData) = $this->employee_sales_target_detail_v1($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'CAPD21') {		//Client Approval Process Detail
					$formData['headingTxt'] = 'Client Approval Process Detail';
					list($error, $message, $returnData) = $this->client_approval_process_detail($formData, $searchCriteria);
				} else if ($formData['mis_report_name'] == 'ITCVSSM21') {	//ITC Std Price Vs Sample Price Reports
					$formData['headingTxt'] = 'ITC Std. Price Vs Sample Price Reports';
					list($error, $message, $returnData) = $this->itc_std_price_vs_sample_price_detail($formData, $searchCriteria);
				}
			}
		}

		return response()->json(['error' => $error, 'message' => $message, 'returnData' => $returnData]);
	}

	/**
	 * generate MIS Report::generateMISReportDocument
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function generateMISReportDocument(Request $request)
	{

		global $order, $models, $misReport, $mail;

		$error      = '0';
		$message    = config('messages.message.error');
		$returnData = array();

		//Saving record in orders table
		if ($request->isMethod('post') && !empty($request['mis_report_name'])) {
			if (Session::get('response_token_in') == Session::get('response_token_out')) {
				$returnData = Session::get('response');		//Getting Session Data
				if (!empty($returnData)) {
					return $models->generateExcel($returnData);
				} else {
					return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
				}
			} else {
				return redirect('MIS/reports/all')->withErrors(config('messages.message.tokenMismatchErrorMsg'));
			}
		}

		return redirect('dashboard')->withErrors($message);
	}

	/**
	 * generate MIS Report::Daily Booking Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function daily_booking_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $totalSampleAmount = $totalInvoiceAmount = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', 'order_master.invoicing_type_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('users as createdBy', 'createdBy.id', 'order_master.created_by')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('samples', 'samples.sample_id', 'order_master.sample_id')
				->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
				->join('order_status', 'order_status.order_status_id', 'order_master.status')
				->join('product_master', 'product_master.product_id', 'order_master.product_id')
				->join('product_categories as subCategory', 'subCategory.p_category_id', 'product_master.p_category_id')
				->join('product_categories as parentCategory', 'parentCategory.p_category_id', 'subCategory.parent_id')
				->join('test_standard', 'test_standard.test_std_id', 'order_master.test_standard')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->leftJoin('trf_hdrs', 'trf_hdrs.trf_id', 'samples.trf_id')
				->leftJoin('test_standard as test_standard_db', 'test_standard_db.test_std_id', 'order_master.defined_test_standard')
				->leftJoin('invoice_hdr_detail', function ($join) {
					$join->on('order_master.order_id', '=', 'invoice_hdr_detail.order_id');
					$join->where('invoice_hdr_detail.invoice_hdr_status', '1');
				})
				->leftJoin('users as salesSampler', 'salesSampler.id', 'order_master.sampler_id');

			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->select('divisions.division_name as Branch', 'departments.department_name as department', 'order_master.order_id', 'order_master.customer_id', 'customer_master.customer_name as party_name', 'city_db.city_name as place', 'customer_master.customer_gst_no as party_gst_no', 'salesExecutive.name as sales_executive_name', 'parentCategory.p_category_name as category_name', 'subCategory.p_category_name as sub_category_name', 'product_master_alias.c_product_name as product_name', 'test_standard.test_std_name as test_standard', 'test_standard_db.test_std_name as defined_test_standard', 'order_master.batch_no', 'order_sample_priority.sample_priority_name as sample_priority', 'samples.sample_no as sample_receiving_code', 'samples.sample_current_date as sample_receiving_date', 'samples.sample_current_date as sample_receiving_time', 'order_master.order_no as sample_reg_code', 'trf_hdrs.trf_no', 'order_master.order_date as booking_date', 'order_master.order_date as booking_time', 'order_master.booking_date as current_date', 'order_master.booking_date as current_time', 'order_master.expected_due_date', 'customer_invoicing_types.invoicing_type', 'order_master.hold_reason', 'order_status.order_status_id', 'order_status.order_status_name as order_stage', 'createdBy.name as booking_person_name', 'order_master.booked_order_amount as sample_amount', 'invoice_hdr_detail.order_total_amount as invoice_amount','salesSampler.name as sampler_name');
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->select('divisions.division_name as Branch', 'departments.department_name as department', 'order_master.order_id', 'order_master.customer_id', 'customer_master.customer_name as party_name', 'city_db.city_name as place', 'customer_master.customer_gst_no as party_gst_no', 'salesExecutive.name as sales_executive_name', 'parentCategory.p_category_name as category_name', 'subCategory.p_category_name as sub_category_name', 'product_master_alias.c_product_name as product_name', 'test_standard.test_std_name as test_standard', 'test_standard_db.test_std_name as defined_test_standard', 'order_master.batch_no', 'order_sample_priority.sample_priority_name as sample_priority', 'samples.sample_no as sample_receiving_code', 'samples.sample_current_date as sample_receiving_date', 'samples.sample_current_date as sample_receiving_time', 'order_master.order_no as sample_reg_code', 'trf_hdrs.trf_no', 'order_master.order_date as booking_date', 'order_master.order_date as booking_time', 'order_master.expected_due_date', 'customer_invoicing_types.invoicing_type', 'order_master.hold_reason', 'order_status.order_status_id', 'order_status.order_status_name as order_stage', 'createdBy.name as booking_person_name', 'order_master.booked_order_amount as sample_amount', 'invoice_hdr_detail.order_total_amount as invoice_amount','salesSampler.name as sampler_name');
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}

			$responseDataObj->orderBy('customer_master.customer_name', 'ASC')->orderBy('order_master.order_date', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $values) {
					$values->booking_date 		= date(DATEFORMATEXCEL, strtotime($values->booking_date));
					$values->booking_time 		= date('h:i A', strtotime($values->booking_time));
					if (!empty($postedData['is_display_pcd'])) {
						$values->current_date 		= date(DATEFORMATEXCEL, strtotime($values->current_date));
						$values->current_time 		= date('h:i A', strtotime($values->current_time));
					}
					$values->sample_receiving_date	= !empty($values->sample_receiving_date) ? date(DATEFORMATEXCEL, strtotime($values->sample_receiving_date)) : '';
					$values->sample_receiving_time	= !empty($values->sample_receiving_time) ? date('h:i A', strtotime($values->sample_receiving_time)) : '';
					$values->expected_due_date 		= date(DATEFORMATEXCEL, strtotime($values->expected_due_date));
					$values->sample_amount 		= $models->roundValue(!empty($values->sample_amount) ? $values->sample_amount : $misReport->getBookedSamplePrice($values->customer_id, $values->order_id));
					$values->invoice_amount 		= !empty($values->invoice_amount) ? $values->invoice_amount : '';
					$values->order_stage		= !empty($values->order_status_id) && $values->order_status_id == '12' && !empty($values->hold_reason) ? $values->order_stage . '(' . trim($values->hold_reason) . ')' : $values->order_stage;
					$postedData['sample_amount'][$key]	= $values->sample_amount;
					$postedData['invoice_amount'][$key]	= !empty($values->invoice_amount) ? $values->invoice_amount : '0';
				}
			}
		}

		$responseData 		 	= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 		 	= $models->unsetFormDataVariablesArray($responseData, array('order_id', 'customer_id', 'order_status_id', 'hold_reason'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 	 	= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 	 	= !empty($responseData) ? $responseData : array();
		$response['tablefoot']	 	= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '6') : array();
		$error        		 	= !empty($responseData) ? '1' : '0';
		$message      		 	= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Party Wise Sample Count-Date Wise
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function party_wise_sample_count_date_wise($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $responseData = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.customer_id', 'customer_master.customer_name as party', 'city_db.city_name as place', 'order_master.customer_city');

			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}

			$responseDataObj->groupBy('order_master.customer_id', 'order_master.customer_city');
			$responseDataObj->orderBy('customer_master.customer_name', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				$dateRangeData = $models->date_range($postedData['date_from'], $postedData['date_to']);
				if (!empty($dateRangeData)) {
					foreach ($responseData as $key => $values) {
						$misReport->getDateWisePartySampleCount($values, $dateRangeData, $postedData);
					}
				}
			}
		}

		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('customer_id', 'customer_city'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? array_values($responseData) : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '1') : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Party Wise Sample Count-Month Wise
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function party_wise_sample_count_month_wise($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $responseData = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.customer_id', 'customer_master.customer_name as party', 'city_db.city_name as place', 'order_master.customer_city');

			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}

			$responseDataObj->groupBy('order_master.customer_id', 'order_master.customer_city');
			$responseDataObj->orderBy('customer_master.customer_name', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				$monthRangeData = $models->month_range($postedData['date_from'], $postedData['date_to']);
				if (!empty($monthRangeData)) {
					foreach ($responseData as $key => $values) {
						$misReport->getMonthWisePartySampleCount($values, $monthRangeData, $postedData);
					}
				}
			}
		}

		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('customer_id', 'customer_city'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? array_values($responseData) : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '2') : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Marketing Executive Wise-By Executive Name
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function marketing_executive_name_wise_sample_count($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $responseData = $totalSample = $totalAmount = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
				->select('divisions.division_name as branch', 'departments.department_name as department', 'salesExecutive.name as sales_executive', 'order_master.customer_id', 'customer_master.customer_name as party', 'city_db.city_name as place');

			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			if (!empty($postedData['sale_executive_id'])) {
				$responseDataObj->where('customer_master.sale_executive', $postedData['sale_executive_id']);
			}

			$responseDataObj->groupBy('order_master.customer_id');
			$responseDataObj->orderBy('salesExecutive.name', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $values) {
					list($sampleCount, $sampleAmount) = $misReport->getCustomerSampleCountAmount($values, $postedData);
					$values->sample_count = $sampleCount;
					$values->sample_amount = $sampleAmount;
					$postedData['totalSample'][$key] = $sampleCount;
					$postedData['totalAmount'][$key] = $sampleAmount;
				}
			}
		}

		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('customer_id'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? array_values($responseData) : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '3') : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Marketing Executive Wise-By Place Wise Sample Count
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function marketing_executive_place_wise_sample_count($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $responseData = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
				->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'order_master.customer_id', 'salesExecutive.name as sales_executive', 'customer_master.customer_city', 'city_db.city_name as place');

			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			if (!empty($postedData['sale_executive_id'])) {
				$responseDataObj->where('customer_master.sale_executive', $postedData['sale_executive_id']);
			}

			$responseDataObj->groupBy('customer_master.customer_city');
			$responseDataObj->orderBy('city_db.city_name', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $values) {
					list($sampleCount, $sampleAmount) = $misReport->getPlaceWiseSampleCountAmount($values, $postedData);
					$values->sample_count 	= $sampleCount;
					$values->sample_amount 	= $sampleAmount;
					$postedData['totalSample'][$key] = $sampleCount;
					$postedData['totalAmount'][$key] = $sampleAmount;
				}
			}
		}

		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('customer_id', 'customer_city'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? array_values($responseData) : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '4') : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::TAT Report Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function tat_report($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('customer_billing_types', 'customer_billing_types.billing_type_id', 'order_master.billing_type_id')
				->join('users as sales', 'sales.id', 'order_master.sale_executive')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->join('samples', 'samples.sample_id', 'order_master.sample_id');

			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_id', 'customer_master.customer_name as party_name', 'city_db.city_name as place', 'order_master.brand_type as brand', 'customer_billing_types.billing_type', 'product_master_alias.c_product_name as sample_name', 'order_master.order_no as sample_reg_code', 'order_master.batch_no', 'order_master.order_date as sample_reg_date', 'order_master.order_date as sample_reg_time', 'order_master.booking_date as sample_current_reg_date', 'order_master.booking_date as sample_current_reg_time', 'order_sample_priority.sample_priority_name as sample_priority', 'order_master.expected_due_date', 'order_master.status');
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_id', 'customer_master.customer_name as party_name', 'city_db.city_name as place', 'order_master.brand_type as brand', 'customer_billing_types.billing_type', 'product_master_alias.c_product_name as sample_name', 'order_master.order_no as sample_reg_code', 'order_master.batch_no', 'order_master.order_date as sample_reg_date', 'order_master.order_date as sample_reg_time', 'order_sample_priority.sample_priority_name as sample_priority', 'order_master.expected_due_date', 'order_master.status');
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			//Filtering records according to expected due date from and expected due date to
			if (!empty($postedData['expected_due_date_from']) && !empty($postedData['expected_due_date_to'])) {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.expected_due_date)"), array($postedData['expected_due_date_from'], $postedData['expected_due_date_to']));
			} else if (!empty($postedData['expected_due_date_from']) && empty($postedData['expected_due_date_to'])) {
				$responseDataObj->where(DB::raw("DATE(order_master.expected_due_date)"), '>=', $postedData['expected_due_date_from']);
			} else if (empty($postedData['expected_due_date_from']) && !empty($postedData['expected_due_date_to'])) {
				$responseDataObj->where(DB::raw("DATE(order_master.expected_due_date)"), '<=', $postedData['expected_due_date_to']);
			}
			//Filtering Records of Order Status
			if (!empty($postedData['order_status_id'])) {
				$responseDataObj->where('order_master.status', $postedData['order_status_id']);
				if ($postedData['order_status_id'] == '9') {
					$responseDataObj->whereNotIn('order_master.order_id', DB::table('order_dispatch_dtl')->pluck('order_id')->all());
				}
			}
			$responseDataObj->orderBy('order_master.order_date', 'DESC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $values) {

					$isCancelledStatus 		= !empty($order->isOrderBookingCancelled($values->order_id)) ? true : false;
					$isOrderSampleType 		= !empty($order->hasOrderInterLabOrCompensatory($values->order_id)) ? true : false;
					$values->sample_reg_date    = date(DATEFORMATEXCEL, strtotime($values->sample_reg_date));
					$values->sample_reg_time    = date('h:i A', strtotime($values->sample_reg_time));
					if (!empty($postedData['is_display_pcd'])) {
						$values->sample_current_reg_date = date(DATEFORMATEXCEL, strtotime($values->sample_current_reg_date));
						$values->sample_current_reg_time = date('h:i A', strtotime($values->sample_current_reg_time));
					}
					$values->expected_due_date  = date(DATEFORMATEXCEL, strtotime($values->expected_due_date));
					$values->scheduled_status   = $isCancelledStatus ? '' : $misReport->checkScheduledStatusOfOrder($values->order_id);

					//Getting Order process Detail
					$misReport->getOrderProcessStageDetail($values, $isCancelledStatus, $isOrderSampleType);
				}
			}
		}

		//removing unrequired coloums
		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('order_id', 'status'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::User Wise Performance Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function user_wise_performance_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			if (!empty($postedData['order_status_id'])) {
				if (!empty($postedData['user_id'])) {
					$userData = DB::table('users')->where('users.id', $postedData['user_id'])->first();
					$unsetArrayColoumData = array('order_id', 'opl_user_id', 'opl_order_status_id', 'test_parameter_id', 'employee_id', 'analyst_name', 'username');
				} else {
					$unsetArrayColoumData = array('order_id', 'opl_user_id', 'opl_order_status_id', 'test_parameter_id', 'employee_id');
				}
				if (!empty($postedData['order_status_id'])) {
					$roleData = DB::table('order_status')->where('order_status.order_status_id', $postedData['order_status_id'])->first();
				}
				if ($postedData['order_status_id'] == '3') {
					list($responseData, $postedData) = $this->analyst_wise_performance_detail($postedData);
				} else {
					$responseData = $this->all_users_wise_performance_detail($postedData);
				}
			}
		}

		//removing unrequired coloums
		$username			= !empty($userData->name) ? ucwords($userData->name) : 'All';
		$roleName			= !empty($roleData->order_status_alias) ? ucwords($roleData->order_status_alias) : '-';
		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, $unsetArrayColoumData);
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
		$response['tablefoot']		= !empty($postedData['sum_of_yes']) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '8') : array();
		$response['extraHeading']	= $username . ' : ' . $roleName;
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::User Wise Performance Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function analyst_wise_performance_detail($postedData)
	{

		global $order, $models, $misReport, $mail;

		$response = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->join('order_parameters_detail', 'order_parameters_detail.analysis_id', 'schedulings.order_parameter_id')
				->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'schedulings.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('users as analyst', 'analyst.id', 'schedulings.employee_id')
				->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_date as date', 'order_master.order_id', 'product_master_alias.c_product_name as sample_name', 'order_master.order_no as sample_code', 'test_parameter.test_parameter_name', 'test_parameter.test_parameter_name as no_of_test', 'order_master.order_date as booking_date', 'schedulings.scheduled_at as scheduling_date', 'order_master.order_report_due_date as expected_due_date', 'schedulings.completed_at as test_completion_date', 'schedulings.created_at as TAT', 'schedulings.created_at as within_due_date', 'schedulings.created_at as days_delay', 'test_parameter.test_parameter_id', 'schedulings.completed_at as no_of_errors', 'schedulings.employee_id', 'analyst.name as analyst_name')
				->whereBetween(DB::raw("DATE(schedulings.completed_at)"), array($postedData['date_from'], $postedData['date_to']))
				->where('order_master.status', '<>', '10');

			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			if (!empty($postedData['user_id'])) {
				$responseDataObj->where('schedulings.employee_id', $postedData['user_id']);
			}
			$responseDataObj->orderBy('order_master.order_date', 'ASC');
			$responseDataObj->orderBy('order_master.order_no', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $values) {
					$values->TAT 		  = $models->sub_days_between_two_date($values->test_completion_date, $values->scheduling_date);
					$values->within_due_date 	  = strtotime(date(DATEFORMATEXCEL, strtotime($values->test_completion_date))) <= strtotime(date(DATEFORMATEXCEL, strtotime($values->expected_due_date))) ? 'Y' : 'N';
					$values->days_delay 	  = $models->sub_days_between_two_date($values->test_completion_date, $values->expected_due_date) <= $values->TAT ? '' : $values->TAT - $models->sub_days_between_two_date($values->test_completion_date, $values->expected_due_date);
					$values->date 	  	  = !empty($values->date) ? date(DATEFORMATEXCEL, strtotime($values->date)) : '';
					$values->booking_date 	  = !empty($values->booking_date) ? date(DATEFORMATEXCEL, strtotime($values->booking_date)) : '';
					$values->scheduling_date 	  = !empty($values->scheduling_date) ? date(DATEFORMATEXCEL, strtotime($values->scheduling_date)) : '';
					$values->expected_due_date 	  = !empty($values->expected_due_date) ? date(DATEFORMATEXCEL, strtotime($values->expected_due_date)) : '';
					$values->test_completion_date = !empty($values->test_completion_date) ? date(DATEFORMATEXCEL, strtotime($values->test_completion_date)) : '';
					$values->test_parameter_name  = trim($values->test_parameter_name);
					$values->no_of_test 	  = '1';
					$values->no_of_errors 	  = $misReport->getNoOfErrorCount($values, $postedData);
					$postedData['sum_of_yes'][$key] = $values->within_due_date == 'Y' ? $values->within_due_date : '';
					$postedData['sum_of_no'][$key]  = $values->within_due_date == 'N' ? $values->within_due_date : '';
					$postedData['no_of_error_count'][$key] = $values->no_of_errors;
				}
			}

			return array($responseData, $postedData);
		}
	}

	/**
	 * generate MIS Report::User Wise Performance Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function all_users_wise_performance_detail($postedData)
	{

		global $order, $models, $misReport, $mail;

		$response = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to']) && !empty($postedData['order_status_id'])) {

			$responseDataObj = DB::table('order_master')
				->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
				->join('order_process_log', 'order_process_log.opl_order_id', 'order_master.order_id')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('samples', 'samples.sample_id', 'order_master.sample_id')
				->join('users as userRoleDB', 'userRoleDB.id', 'order_process_log.opl_user_id')
				->whereNotNull('order_process_log.opl_user_id');

			if (!empty($postedData['order_status_id'])) {
				$roleData = DB::table('order_status')->where('order_status.order_status_id', $postedData['order_status_id'])->first();
				if ($postedData['order_status_id'] == '1') {
					$responseDataObj->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_id', 'order_process_log.opl_user_id', 'order_process_log.opl_order_status_id', 'customer_master.customer_name as party', 'city_db.city_name as place', 'product_master_alias.c_product_name as sample_name', 'order_master.batch_no', 'userRoleDB.name as username', 'samples.sample_current_date as sample_receiving_date', 'samples.sample_current_date as sample_receiving_time', 'samples.sample_no as sample_receiving_code', 'order_master.order_date as sample_booking_date', 'order_master.order_date as sample_booking_time', 'order_master.order_no as sample_booking_code', 'order_master.order_date as booking_time_limit', 'order_master.order_date as sample_modification_count');
				} else if ($postedData['order_status_id'] == '2') {
					$responseDataObj->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_id', 'order_process_log.opl_user_id', 'order_process_log.opl_order_status_id', 'customer_master.customer_name as party', 'city_db.city_name as place', 'product_master_alias.c_product_name as sample_name', 'order_master.batch_no', 'userRoleDB.name as username', 'order_master.order_date as sample_booking_date', 'order_master.order_date as sample_booking_time', 'order_master.order_no as sample_booking_code', 'order_master.order_scheduled_date as date_of_scheduling', 'order_master.order_scheduled_date as time_of_scheduling', 'order_master.order_date as scheduling_time_limit');
				} else if ($postedData['order_status_id'] == '5') {
					$responseDataObj->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_id', 'order_process_log.opl_user_id', 'order_process_log.opl_order_status_id', 'customer_master.customer_name as party', 'city_db.city_name as place', 'product_master_alias.c_product_name as sample_name', 'order_master.batch_no', 'userRoleDB.name as username', 'order_master.order_no as sample_booking_code', 'order_master.test_completion_date as sample_date_of_completion', 'order_master.test_completion_date as sample_time_of_completion', 'order_report_details.reviewing_date as sample_reviewing_date', 'order_report_details.reviewing_date as sample_reviewing_time', 'order_master.order_date as reviewing_time_limit', 'order_report_details.reviewing_date as reviewing_modification_count');
				} else if ($postedData['order_status_id'] == '6') {
					$responseDataObj->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_id', 'order_process_log.opl_user_id', 'order_process_log.opl_order_status_id', 'customer_master.customer_name as party', 'city_db.city_name as place', 'product_master_alias.c_product_name as sample_name', 'order_master.batch_no', 'userRoleDB.name as username', 'order_master.order_no as sample_booking_code', 'order_report_details.reviewing_date as sample_reviewing_date', 'order_report_details.reviewing_date as sample_reviewing_time', 'order_report_details.finalizing_date as sample_finalizing_date', 'order_report_details.finalizing_date as sample_finalizing_time', 'order_report_details.finalizing_date as finalizing_time_limit', 'order_report_details.finalizing_date as finalizing_modification_count');
				} else if ($postedData['order_status_id'] == '7') {
					$responseDataObj->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_id', 'order_process_log.opl_user_id', 'order_process_log.opl_order_status_id', 'customer_master.customer_name as party', 'city_db.city_name as place', 'product_master_alias.c_product_name as sample_name', 'order_master.batch_no', 'userRoleDB.name as username', 'order_master.order_no as sample_booking_code', 'order_report_details.finalizing_date as sample_finalizing_date', 'order_report_details.finalizing_date as sample_finalizing_time', 'order_report_details.approving_date as sample_approving_date', 'order_report_details.approving_date as sample_approving_time', 'order_report_details.approving_date as approving_time_limit', 'order_report_details.approving_date as approving_modification_count');
				}
				$responseDataObj->where('order_process_log.opl_order_status_id', $postedData['order_status_id']);
			}
			if (!empty($postedData['user_id'])) {
				$userData = DB::table('users')->where('users.id', $postedData['user_id'])->first();
				$responseDataObj->where('order_process_log.opl_user_id', $postedData['user_id']);
			}
			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			$responseDataObj->groupBy('order_process_log.opl_order_id');
			$responseDataObj->orderBy('customer_master.customer_name', 'DESC');
			$responseDataObj->orderBy('order_process_log.opl_date', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $values) {
					if ($values->opl_order_status_id == '1') {	//Booking Users
						$values->booking_time_limit		= $models->date_hour_min_sec_ago_v1($values->sample_booking_date, $values->sample_receiving_date);
						$values->sample_receiving_date		= date(DATEFORMATEXCEL, strtotime($values->sample_receiving_date));
						$values->sample_receiving_time		= date('h:i A', strtotime($values->sample_receiving_time));
						$values->sample_booking_date		= date(DATEFORMATEXCEL, strtotime($values->sample_booking_date));
						$values->sample_booking_time		= date('h:i A', strtotime($values->sample_booking_time));
						$values->sample_modification_count 	= $misReport->get_modification_count($values->opl_user_id, $values->order_id, $values->opl_order_status_id);
					} else if ($values->opl_order_status_id == '2') {	//Scheduling Users
						$values->scheduling_time_limit		= $models->date_hour_min_sec_ago_v1($values->date_of_scheduling, $values->sample_booking_date);
						$values->sample_booking_date		= date(DATEFORMATEXCEL, strtotime($values->sample_booking_date));
						$values->sample_booking_time		= date('h:i A', strtotime($values->sample_booking_time));
						$values->date_of_scheduling		= date(DATEFORMATEXCEL, strtotime($values->date_of_scheduling));
						$values->time_of_scheduling		= date('h:i A', strtotime($values->time_of_scheduling));
					} else if ($values->opl_order_status_id == '5') {	//Reviewing Users
						$values->reviewing_time_limit		= $models->date_hour_min_sec_ago_v1($values->sample_reviewing_date, $values->sample_date_of_completion);
						$values->sample_date_of_completion	= date(DATEFORMATEXCEL, strtotime($values->sample_date_of_completion));
						$values->sample_time_of_completion	= date('h:i A', strtotime($values->sample_time_of_completion));
						$values->sample_reviewing_date		= date(DATEFORMATEXCEL, strtotime($values->sample_reviewing_date));
						$values->sample_reviewing_time		= date('h:i A', strtotime($values->sample_reviewing_time));
						$values->reviewing_modification_count	= $misReport->get_modification_count($values->opl_user_id, $values->order_id, $values->opl_order_status_id, $error_skip_flag = '2');
					} else if ($values->opl_order_status_id == '6') {	//Finalizing Users
						$values->finalizing_time_limit		= $models->date_hour_min_sec_ago_v1($values->sample_finalizing_date, $values->sample_reviewing_date);
						$values->sample_reviewing_date		= date(DATEFORMATEXCEL, strtotime($values->sample_reviewing_date));
						$values->sample_reviewing_time		= date('h:i A', strtotime($values->sample_reviewing_time));
						$values->sample_finalizing_date		= date(DATEFORMATEXCEL, strtotime($values->sample_finalizing_date));
						$values->sample_finalizing_time		= date('h:i A', strtotime($values->sample_finalizing_time));
						$values->finalizing_modification_count	= $misReport->get_modification_count($values->opl_user_id, $values->order_id, $values->opl_order_status_id);
					} else if ($values->opl_order_status_id == '7') {	//Approving Users
						$values->approving_time_limit		= $models->date_hour_min_sec_ago_v1($values->sample_approving_date, $values->sample_finalizing_date);
						$values->sample_finalizing_date		= date(DATEFORMATEXCEL, strtotime($values->sample_finalizing_date));
						$values->sample_finalizing_time		= date('h:i A', strtotime($values->sample_finalizing_time));
						$values->sample_approving_date		= date(DATEFORMATEXCEL, strtotime($values->sample_approving_date));
						$values->sample_approving_time		= date('h:i A', strtotime($values->sample_approving_time));
						$values->approving_modification_count	= $misReport->get_modification_count($values->opl_user_id, $values->order_id, $values->opl_order_status_id);
					}
				}
			}
		}

		return $responseData;
	}

	/**
	 * generate MIS Report::Analyst Wise Performance Summary
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function analyst_wise_performance_summary($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {
			$responseDataObj = DB::table('users')
				->join('users_department_detail', 'users_department_detail.user_id', '=', 'users.id')
				->join('divisions', 'divisions.division_id', 'users.division_id')
				->join('departments', 'departments.department_id', 'users_department_detail.department_id')
				->join('role_user', 'users.id', '=', 'role_user.user_id')
				->join('order_status', 'order_status.role_id', '=', 'role_user.role_id')
				->select('divisions.division_name as branch', 'departments.department_name as department', 'users.id as user_id', 'users.name as analyst_name', 'users.id as total_sample_received', 'users.id as sample_analysed', 'users.id as no_of_test_conducted', 'users.id as sample_within_tat', 'users.id as sample_beyond_tat', 'users.id as no_of_errors', 'users.id as error_percentage', 'users.id as performance')
				->where('order_status.order_status_id', '=', $postedData['order_status_id'])
				->where('users.is_sales_person', '=', '0');

			if (!empty($postedData['division_id']) && is_numeric($postedData['division_id'])) {
				$responseDataObj->where('users.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id']) && is_numeric($postedData['product_category_id'])) {
				$linkedData = DB::table('department_product_categories_link')->where('department_product_categories_link.product_category_id', '=', $postedData['product_category_id'])->first();
				$responseDataObj->where('users_department_detail.department_id', $linkedData->department_id);
			}
			if (!empty($postedData['user_id']) && is_numeric($postedData['user_id'])) {
				$responseDataObj->where('users.id', $postedData['user_id']);
			}
			$responseDataObj->groupBy('users.id', 'users.division_id', 'users_department_detail.department_id');
			$responseDataObj->orderBy('users.name', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $values) {
					$values->total_sample_received = $misReport->getRequiredFieldValue($values, $postedData, $type = '1');
					$values->sample_analysed       = $misReport->getRequiredFieldValue($values, $postedData, $type = '2');
					$values->no_of_test_conducted  = $misReport->getRequiredFieldValue($values, $postedData, $type = '3');
					$values->sample_within_tat     = $misReport->getRequiredFieldValue($values, $postedData, $type = '4');
					$values->sample_beyond_tat     = $misReport->getRequiredFieldValue($values, $postedData, $type = '5');
					$values->no_of_errors     	 = $misReport->getRequiredFieldValue($values, $postedData, $type = '6');
					$values->error_percentage      = !empty($values->no_of_errors) ? round(($values->no_of_errors / $values->no_of_test_conducted) * 100, 2) . '%' : '0%';
					$values->performance           = !empty($values->sample_within_tat) && !empty($values->total_sample_received) ? round(($values->sample_within_tat / $values->total_sample_received) * 100, 2) . '%' : '0%';
				}
			}
		}

		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('user_id'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
		$response['tablefoot']		= array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Sample Log Status
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function sample_log_status($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $responseData  = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$fromDate    = $postedData['date_from'];
			$toDate      = $postedData['date_to'];

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
				->select('product_categories.p_category_id', 'product_categories.p_category_name')
				->where('product_categories.parent_id', '0');

			if (!empty($postedData['division_id'])) {
				$divisionDataList = DB::table('divisions')->where('division_id', $postedData['division_id'])->select('division_id', 'division_name')->get();
			} else {
				$divisionDataList = DB::table('divisions')->select('division_id', 'division_name')->get();
			}
			if (!empty($postedData['product_category_id'])) {
				$departmentDataObj->where('product_categories.p_category_id', $postedData['product_category_id']);
			}
			$departmentDataObj->orderBy('product_categories.p_category_id', 'ASC');
			$departmentData = $departmentDataObj->get();

			if (!empty($coloumArrayData) && !empty($departmentData) && !empty($divisionDataList)) {
				foreach ($divisionDataList as $divisions) {
					foreach ($coloumArrayData as $key => $coloumArray) {

						$totalData 				     	= array();
						$divisionId 					= $divisions->division_id;
						$divisionIdkey					= $divisionId . $key;
						$responseData[$divisionIdkey]['branch']   	= $divisions->division_name;
						$responseData[$divisionIdkey]['department'] 	= $coloumArray;

						foreach ($departmentData as $department) {
							$departmentName 	 	     			= $department->p_category_name;
							$departmentId   	 	     			= $department->p_category_id;
							$countValue     	 	     			= $misReport->getSampleLogResultant($key, $divisions->division_id, $departmentId, $postedData);
							$queryString 				        = 'type=' . $key . '&division_id=' . $divisions->division_id . '&department_id=' . $departmentId . '&from_date=' . $fromDate . '&to_date=' . $toDate;
							$responseData[$divisionIdkey][$departmentName] 	= $countValue . '|' . $queryString;
							$totalData[$divisionIdkey . $departmentId]	   	= $countValue;
						}
						$responseData[$divisionIdkey]['total'] = array_sum($totalData);
					}
				}
			}
		}

		//removing unrequired coloums
		$responseData 			= !empty($responseData) ? array_values(json_decode(json_encode($responseData), true)) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('order_id', 'customer_id'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Daily Invoicing Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function daily_invoice_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $totalSampleAmount = $totalInvoiceAmount = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('invoice_hdr')
				->join('divisions', 'divisions.division_id', 'invoice_hdr.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'invoice_hdr.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
				->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
				->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_invoicing_id')
				->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
				->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
				->leftjoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
				->leftjoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
				->leftjoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
				->select('divisions.division_name as branch', 'departments.department_name as department', 'invoice_hdr.invoice_no as order_no', 'state_db.state_name', 'city_db.city_name', 'InvoicingToStateDB.state_name as invoicing_state_name', 'InvoicingToCityDB.city_name as invoicing_city_name', 'invoice_hdr.invoice_date as bill_date', 'invoice_hdr.invoice_no as bill_no', 'customer_master.customer_name as party_name', 'InvoicingToCustomerDB.customer_name as invoicing_party_name', 'invoice_hdr.net_amount as amount', 'invoice_hdr.extra_amount as conveyance', 'invoice_hdr.sgst_amount as sgst_value', 'invoice_hdr.cgst_amount as cgst_value', 'invoice_hdr.igst_amount as igst_value', 'invoice_hdr.net_total_amount as amt_payable', 'invoice_hdr.invoice_status')
				->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), array($postedData['date_from'], $postedData['date_to']));

			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('invoice_hdr.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('invoice_hdr.product_category_id', $postedData['product_category_id']);
			}

			$responseDataObj->orderBy('customer_master.customer_name', 'ASC');
			$responseDataObj->groupBy('invoice_hdr.invoice_no');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $value) {
					$value->party_name 	  		= !empty($value->invoicing_party_name)  ? $value->invoicing_party_name . '-' . $value->invoicing_city_name . '-' . $value->invoicing_state_name : $value->party_name . '-' . $value->city_name . '-' . $value->state_name;
					$value->sgst_value    		= !empty($value->sgst_value) ? $value->sgst_value : '0.00';
					$value->cgst_value    		= !empty($value->cgst_value) ? $value->cgst_value : '0.00';
					$value->igst_value    		= !empty($value->igst_value) ? $value->igst_value : '0.00';
					$value->bill_date     		= date(DATEFORMATEXCEL, strtotime($value->bill_date));
					$value->amount        		= number_format((float)$value->amount - $value->conveyance, 2, '.', '');
					$value->invoice_status     		= $value->invoice_status == '1' ? 'Active' : 'Inactive';
					$postedData['total_amount'][$key] 	  = $value->amount;
					$postedData['total_sgst_value'][$key] = $value->sgst_value;
					$postedData['total_cgst_value'][$key] = $value->cgst_value;
					$postedData['total_igst_value'][$key] = $value->igst_value;
					$postedData['total_conveyance'][$key] = $value->conveyance;
					$postedData['total_amt_payable'][$key] = $value->amt_payable;
				}
			}
		}

		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('state_name', 'city_name', 'invoicing_party_name', 'invoicing_state_name', 'invoicing_city_name'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '11') : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Instrument Wise Performance Detail
	 * 
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (23-July-2018)
	 */
	public function instrument_wise_performance_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $totalSampleAmount = $totalInvoiceAmount = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$dateRangeData = $models->date_range($postedData['date_from'], $postedData['date_to']);

			$divisionDataObj = DB::table('divisions')->select('division_id', 'division_name');
			$departmentDataObj = DB::table('product_categories')->select('product_categories.p_category_id', 'product_categories.p_category_name')->where('product_categories.parent_id', '0');

			if (!empty($postedData['division_id'])) {
				$divisionDataObj->where('division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$departmentDataObj->where('product_categories.p_category_id', $postedData['product_category_id']);
			}
			$divisionDataObj->orderBy('divisions.division_name', 'ASC');
			$divisionDataList  = $divisionDataObj->get();
			$departmentDataObj->orderBy('product_categories.p_category_id', 'ASC');
			$departmentData    = $departmentDataObj->get();
			$equipmentTypeData = DB::table('equipment_type')->where('equipment_type.is_equipment_selected', '1')->orderBy('equipment_type.equipment_sort_by', 'ASC')->get();

			if (!empty($dateRangeData)) {
				foreach ($dateRangeData as $dateKey => $date) {
					foreach ($divisionDataList as $divisionKey => $division) {
						foreach ($departmentData as $departmentkey => $department) {
							foreach ($equipmentTypeData as $equipmentkey => $equipmentType) {
								$coloumKey = $dateKey . $divisionKey . $departmentkey . $equipmentkey;
								$responseData[$coloumKey]['date']       	= $date;
								$responseData[$coloumKey]['division']   	= $division->division_name;
								$responseData[$coloumKey]['department'] 	= $department->p_category_name;
								$responseData[$coloumKey]['equipment']  	= $equipmentType->equipment_name;
								$responseData[$coloumKey]['opening_pending'] 	= $misReport->getPendingEquipmentCount($date, $division, $department, $equipmentType, $type = '1');
								$responseData[$coloumKey]['pending']    	= $misReport->getPendingEquipmentCount($date, $division, $department, $equipmentType, $type = '2');
								$responseData[$coloumKey]['allocated']  	= $misReport->getPendingEquipmentCount($date, $division, $department, $equipmentType, $type = '3');
								$responseData[$coloumKey]['completed']  	= $misReport->getPendingEquipmentCount($date, $division, $department, $equipmentType, $type = '4');
								$responseData[$coloumKey]['over_due']   	= $misReport->getPendingEquipmentCount($date, $division, $department, $equipmentType, $type = '5');
								$responseData[$coloumKey]['not_due']    	= $misReport->getPendingEquipmentCount($date, $division, $department, $equipmentType, $type = '6');
								$responseData[$coloumKey]['closing']    	= abs(($responseData[$coloumKey]['opening_pending'] + $responseData[$coloumKey]['allocated']) - $responseData[$coloumKey]['completed']);
								$postedData['total_opening_pending'][$coloumKey] = $responseData[$coloumKey]['opening_pending'];
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

		$responseData 			= !empty($responseData) ? array_values(json_decode(json_encode($responseData), true)) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('equipment_type_id', 'previous_date', 'order_date', 'booking_date'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '7') : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Booking Cancellation Detail
	 * 
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (23-July-2018)
	 */
	public function booking_cancellation_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_cancellation_dtls')
				->join('order_master', 'order_master.order_id', 'order_cancellation_dtls.order_id')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('users as createdBy', 'createdBy.id', 'order_master.created_by')
				->join('city_db', 'city_db.city_id', 'order_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_cancellation_types', 'order_cancellation_types.order_cancellation_type_id', 'order_cancellation_dtls.cancellation_type_id')
				->join('order_status', 'order_status.order_status_id', 'order_cancellation_dtls.cancellation_stage')
				->join('users as cancelledBy', 'cancelledBy.id', 'order_cancellation_dtls.cancelled_by')
				->select('divisions.division_name as Branch', 'departments.department_name as department', 'order_master.order_id', 'order_master.customer_id', 'order_master.order_date as booking_date', 'customer_master.customer_name', 'city_db.city_name as customer_place', 'product_master_alias.c_product_name as sample_name', 'order_master.batch_no', 'order_master.order_no as booking_no', 'order_cancellation_types.order_cancellation_type_name as cancellation_type', 'order_cancellation_dtls.cancelled_date', 'order_cancellation_dtls.cancelled_date as cancelled_time', 'order_status.order_status_name as stage_of_cancellation', 'cancelledBy.name as cancelled_by');

			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}

			$responseDataObj->groupBy('order_cancellation_dtls.order_id');
			$responseDataObj->orderBy('customer_master.customer_name', 'ASC')->orderBy('order_master.order_date', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $values) {
					$values->booking_date 		= date(DATEFORMATEXCEL, strtotime($values->booking_date));
					$values->cancelled_date 		= date(DATEFORMATEXCEL, strtotime($values->cancelled_date));
					$values->cancelled_time 		= date('h:i A', strtotime($values->cancelled_date));
				}
			}
		}

		$responseData 		 	= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 		 	= $models->unsetFormDataVariablesArray($responseData, array('order_id', 'customer_id'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 	 	= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 	 	= !empty($responseData) ? $responseData : array();
		$response['tablefoot']	 	= array();
		$error        		 	= !empty($responseData) ? '1' : '0';
		$message      		 	= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Booking Amendment Detail
	 * 
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (23-July-2018)
	 */
	public function booking_amendment_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $totalSampleAmount = $totalInvoiceAmount = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('order_amended_dtl', 'order_amended_dtl.oad_order_id', 'order_master.order_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('users as amendmentBy', 'amendmentBy.id', 'order_amended_dtl.oad_amended_by')
				->join('city_db', 'city_db.city_id', 'order_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_status', 'order_status.order_status_id', 'order_amended_dtl.oad_amended_stage')
				->select('divisions.division_name as Branch', 'departments.department_name as department', 'order_master.order_id', 'customer_master.customer_name as party_name', 'city_db.city_name as place_name', 'order_master.booking_date as booking_date', 'order_master.order_no as booking_no', 'product_master_alias.c_product_name as sample_name', 'order_master.batch_no', 'order_amended_dtl.oad_amended_date as amended_date', 'amendmentBy.name as amended_by', 'order_status.order_status_name as amended_stage', 'order_amended_dtl.oad_amended_by as amendment_count');

			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}

			$responseDataObj->groupBy('order_amended_dtl.oad_order_id');
			$responseDataObj->orderBy('customer_master.customer_name', 'ASC')->orderBy('order_master.order_date', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $values) {
					$values->booking_date 		= date(DATEFORMATEXCEL, strtotime($values->booking_date));
					$values->amended_date 		= date(DATEFORMATEXCEL, strtotime($values->amended_date));
					$values->amendment_count 		= DB::table('order_amended_dtl')->where('order_amended_dtl.oad_order_id', $values->order_id)->count();
					$postedData['total_amendment_count'][$key] = $values->amendment_count;
				}
			}
		}

		$responseData 		 	= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 		 	= $models->unsetFormDataVariablesArray($responseData, array('order_id'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 	 	= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 	 	= !empty($responseData) ? $responseData : array();
		$response['tablefoot']	 	= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '9') : array();
		$error        		 	= !empty($responseData) ? '1' : '0';
		$message      		 	= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Sales Report Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (23-July-2018)
	 */
	public function sales_report_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $responseData = array();

		$creditAmount = '0';

		//************Get Sales Invoice*********************

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			//****************************Daily Sales Invoice*****************************************************
			$responseRawData['SIB'] = $misReport->sales_report_detail_for_sales_invoice($postedData, $docType = 'Sales Invoice');
			//****************************/Daily Sales Invoice****************************************************

			//****************************Debit Note Detail****************************************************
			$responseRawData['CNA'] = $misReport->sales_report_detail_for_credit_note_auto_detail($postedData, $docType = 'Credit Note');
			$responseRawData['CNM'] = $misReport->sales_report_detail_for_credit_note_manual_detail($postedData, $docType = 'Credit Note');
			//****************************Debit Note Detail*****************************************************

			//Merging all Data in single coloum
			$responseData = !empty($responseRawData) ? $models->multiArrayToOneArray($responseRawData) : array();
			$responseData = $models->unsetFormDataVariablesArray($responseData, array('invoice_id', 'customer_id', 'invoiced_customer_code', 'invoiced_customer_name', 'invoiced_state_name', 'invoiced_location_name'));
			if (!empty($responseData)) {
				foreach ($responseData as $key => $value) {
					if (!empty($value['revenue_amount']) && $value['revenue_amount'] > '0') {
						$postedData['revenue_amount'][$key] = $value['revenue_amount'];
					}
				}
				//Calculating Credit Total
				$creditAmountSummaryArray 	     	 = $misReport->getBranchDepartmentWiseSalesInvoiceCreditAmount($postedData);
				$postedData['credit_amount']  	     = !empty($creditAmountSummaryArray) ? array_values($models->array_flatten($creditAmountSummaryArray)) : array();
				$postedData['credit_amount_summary'] = $creditAmountSummaryArray;
			}
		}

		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead']		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody']		= !empty($responseData) ? array_values($responseData) : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '5') : array();
		$response['summary']		= !empty($responseData) ? $misReport->getTableSummaryData($postedData, $type = '1') : array();
		$error   			= !empty($responseData) ? '1' : '0';
		$message 			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Booking Amendment Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (23-July-2018)
	 */
	public function daily_sales_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$responseData = $response = $tableHead = $totalInvoiceAmount = $creditNoteData = $creditNoteInvoiceArray = $totalDataDateWise = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$coloumArrayData = array('0' => 'Reports Booked', '1' => 'Reports Billing', '2' => 'Reports Amount');

			if (!empty($postedData['division_id'])) {
				$divisionDataList = DB::table('divisions')->where('division_id', $postedData['division_id'])->select('division_id', 'division_name')->get();
			} else {
				$divisionDataList = DB::table('divisions')->select('division_id', 'division_name')->get();
			}

			$departmentDataObj = DB::table('product_categories')->where('product_categories.parent_id', '0');
			if (!empty($postedData['product_category_id'])) {
				$departmentDataObj->where('product_categories.p_category_id', $postedData['product_category_id']);
			}
			$departmentDataObj->orderBy('product_categories.p_category_id', 'ASC');
			$departmentData = $departmentDataObj->pluck('product_categories.p_category_name', 'product_categories.p_category_id')->all();
			array_push($departmentData, "SUM");

			if (!empty($coloumArrayData) && !empty($departmentData) && !empty($divisionDataList)) {
				$dateRangeData = $models->date_range($postedData['date_from'], $postedData['date_to']);
				foreach ($divisionDataList as $key => $divisions) {
					foreach ($dateRangeData as $dateRangeKey => $dateRange) {
						$totalData = array();
						foreach ($departmentData as $departmentId => $departmentName) {
							$divisionName = $divisions->division_name;
							$coloumName	  = $dateRange;
							$responseData[$divisionName . $dateRange]['branch']  = $divisions->division_name;
							$responseData[$divisionName . $dateRange]['date']    = $dateRange;
							foreach ($coloumArrayData as $getterKey => $coloumArray) {
								$countValue = $misReport->getDailySalesResultant($getterKey, $divisions->division_id, $departmentId, $dateRange);
								if ($departmentName != 'SUM') {
									$responseData[$divisionName . $dateRange][$departmentName . '|' . $coloumArray]  = $countValue;
									$totalData[$divisionName . $dateRange][$coloumArray][] = $countValue;
								} else {
									$responseData[$divisionName . $dateRange][$departmentName . '|' . $coloumArray] = round(array_sum($totalData[$divisionName . $dateRange][$coloumArray]));
								}
							}
						}
					}
				}

				//Calculating Revenue Total
				foreach ($responseData as $key => $responseValueAll) {
					foreach ($responseValueAll as $keyTotal => $responseValue) {
						$totalDataDateWise[$keyTotal][] = $responseValue;
					}
				}

				//Calculating Credit Total
				$creditAmountSummaryArray 	     = $misReport->getBranchDepartmentWiseCreditAmount($postedData);
				$postedData['revenue_amount']        = !empty($totalDataDateWise) && count(end($totalDataDateWise)) > '0' ?  end($totalDataDateWise) : array();
				$postedData['credit_amount']         = !empty($creditAmountSummaryArray) ? array_values($models->array_flatten($creditAmountSummaryArray)) : array();
				$postedData['credit_amount_summary'] = $creditAmountSummaryArray;
			}
		}

		$responseData 		 	= !empty($responseData) ? array_values(json_decode(json_encode($responseData), true)) : array();
		$responseData 		 	= $models->unsetFormDataVariablesArray($responseData, array('order_id'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 	 	= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 	 	= !empty($responseData) ? $responseData : array();
		$response['tablefoot']	 	= !empty($totalDataDateWise) ? $misReport->getTableFooterData($response['tableHead'], $totalDataDateWise, $type = '10') : array();
		$response['summary']		= !empty($responseData) ? $misReport->getTableSummaryData($postedData, $type = '2') : array();
		$error        		 	= !empty($responseData) ? '1' : '0';
		$message      		 	= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Delay Status Detail
	 * 
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (23-July-2018)
	 */
	public function delay_status_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $responseData = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$coloumArrayData = array(
				'1'  => 'Number of Reports Due',
				'2'  => 'Number of Reports issued',
				'3'  => 'Delay',
				'4'  => 'Delay %',
				'5'  => 'Number of Delay reports',
				'6'  => 'Report Issued',
				'7'  => 'Delay',
				'8'  => 'Delay %',
				'9'  => 'Advance report issued',
				'10' => 'Total Pending Reports',
				'11' => 'Total issued',
				'12' => 'Total Issued report',
				'13' => 'Total Issued %',
				'14' => 'Delay %',
			);

			$departmentDataObj = DB::table('product_categories')->select('product_categories.p_category_id', 'product_categories.p_category_name')->where('product_categories.parent_id', '0');

			if (!empty($postedData['division_id'])) {
				$divisionDataList = DB::table('divisions')->where('division_id', $postedData['division_id'])->select('division_id', 'division_name')->get();
			} else {
				$divisionDataList = DB::table('divisions')->select('division_id', 'division_name')->get();
			}
			if (!empty($postedData['product_category_id'])) {
				$departmentDataObj->where('product_categories.p_category_id', $postedData['product_category_id']);
			}
			$departmentDataObj->orderBy('product_categories.p_category_id', 'ASC');
			$departmentData = $departmentDataObj->get();

			if (!empty($coloumArrayData) && !empty($departmentData) && !empty($divisionDataList)) {
				$serialCounter = '1';
				foreach ($divisionDataList as $divisions) {
					foreach ($coloumArrayData as $keyName => $coloumName) {
						$totalData 				    = array();
						$divisionId 				    = $divisions->division_id;
						$divisionIdkey				    = $divisionId . $keyName;
						$responseData[$divisionIdkey]['S.No']       = $serialCounter++;
						$responseData[$divisionIdkey]['branch']     = $divisions->division_name;
						$responseData[$divisionIdkey]['TAT'] 	    = $coloumName;
						foreach ($departmentData as $department) {
							$departmentName = $department->p_category_name;
							$departmentId   = $department->p_category_id;
							$responseData[$divisionIdkey][$departmentName] = $misReport->getDelayLogResultant($keyName, $divisionId, $departmentId, $departmentName, $postedData, $responseData);;
						}
					}
				}
			}
		}

		//removing unrequired coloums
		$responseData 			= !empty($responseData) ? array_values(json_decode(json_encode($responseData), true)) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('order_id', 'customer_id'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Account Sales Detail
	 * 
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (23-July-2018)
	 */
	public function account_sales_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $responseRawData = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			//****************************Daily Sales Invoice*****************************************************
			$responseRawData['SIB'] = $misReport->daily_sales_invoice_detail($postedData, $docType = 'Sales Invoice');
			//****************************/Daily Sales Invoice****************************************************

			//****************************Credit Note Detail**************************************************
			$responseRawData['DNA'] = $misReport->debit_notes_auto_detail($postedData, $docType = 'Debit Note');
			$responseRawData['DNM'] = $misReport->debit_notes_manual_detail($postedData, $docType = 'Debit Note');
			//****************************Credit Note Detail**************************************************

			//****************************Debit Note Detail****************************************************
			$responseRawData['CNA'] = $misReport->credit_note_auto_detail($postedData, $docType = 'Credit Note');
			$responseRawData['CNM'] = $misReport->credit_note_manual_detail($postedData, $docType = 'Credit Note');
			//****************************Debit Note Detail*****************************************************
		}

		$responseData 			= !empty($responseRawData) ? $models->multiArrayToOneArray($responseRawData) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('invoicing_party_name', 'invoicing_state_name', 'invoicing_city_name', 'debit_reference_no', 'credit_reference_no', 'invoicing_gst_no', 'debit_note_type_id', 'credit_note_type_id', 'manual_amount', 'invoicing_customer_code'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '6') : array();
		$error        			= !empty($responseData) ? '1' : '0';
		$message      			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Account Sales Detail
	 * 
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (21-July-2018)
	 */
	public function viewSampleLogStatusDetail(Request $request)
	{

		global $order, $models, $misReport;

		$responseData = $response = array();

		if ($request->isMethod('post') && !empty($request->formData)) {

			//Parsing Form Data
			parse_str($request->formData, $postedData);

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

			$type 				= !empty($postedData['type']) ? $postedData['type'] : '';
			$division_id 			= !empty($postedData['division_id']) ? $postedData['division_id'] : '';
			$dept_id 				= !empty($postedData['department_id']) ? $postedData['department_id'] : '';
			$from_date 				= !empty($postedData['from_date']) ? $postedData['from_date'] : '';
			$to_date 				= !empty($postedData['to_date']) ? $postedData['to_date'] : '';
			$heading				= !empty($coloumArrayData[$type]) ? $coloumArrayData[$type] : 'Sample Log Status Detail';

			$responseData 			= $misReport->getSampleLogDetail($type, $division_id, $dept_id, $from_date, $to_date);
			$responseData 			= !empty($responseData) ? array_values(json_decode(json_encode($responseData), true)) : array();
			$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('order_id', 'customer_id', 'detail', 'canDispatchOrder'));
			$response['mis_report_name']  	= $heading;
			$response['tableHead'] 		= !empty($responseData) ? array_keys(end($responseData)) : array();
			$response['tableBody'] 		= !empty($responseData) ? $responseData : array();
			$error        			= !empty($responseData) ? '1' : '0';
			$message      			= $error ? '' : config('messages.message.noRecordFound');

			return response()->json(['error' => $error, 'message' => $message, 'returnData' => $response]);
		}
	}

	/**
	 * generate MIS Report::Employee Sales Target Detail
	 * Created By : Praveen Singh
	 * Created On : 23-Nov-2020
	 */
	public function employee_sales_target_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $responseData = $total = $summaryData = array();
		$creditAmount = '0';
		$rupeeSymbol = '&#x20B9;';

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('user_sales_target_details')
				->join('users', 'users.id', 'user_sales_target_details.ust_user_id')
				->join('divisions', 'divisions.division_id', 'users.division_id')
				->leftjoin('customer_master', 'customer_master.customer_id', 'user_sales_target_details.ust_customer_id')
				->leftjoin('product_categories', 'product_categories.p_category_id', 'user_sales_target_details.ust_product_category_id')
				->select('user_sales_target_details.ust_user_id', 'user_sales_target_details.ust_date', 'divisions.division_name as branch', 'users.name as person_wise', 'customer_master.customer_name', 'product_categories.p_category_name as department', 'user_sales_target_details.ust_amount as total_ob_target', 'user_sales_target_details.ust_id as actual_ob_ach_mtd', 'user_sales_target_details.ust_id as ob_variation', 'user_sales_target_details.ust_amount as total_inv_target', 'user_sales_target_details.ust_id as actual_inv_ach_mtd', 'user_sales_target_details.ust_id as inv_variation')
				->whereMonth('user_sales_target_details.ust_date', '>=', date('m', strtotime($postedData['date_from'])))
				->whereMonth('user_sales_target_details.ust_date', '<=', date('m', strtotime($postedData['date_to'])))
				->whereYear('user_sales_target_details.ust_date', '>=', date('Y', strtotime($postedData['date_from'])))
				->whereYear('user_sales_target_details.ust_date', '<=', date('Y', strtotime($postedData['date_to'])));

			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('users.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->leftJoin('users_department_detail', 'users_department_detail.user_id', 'user_sales_target_details.ust_user_id');
				$responseDataObj->leftJoin('department_product_categories_link', 'department_product_categories_link.department_id', 'users_department_detail.department_id');
				$responseDataObj->orWhere('department_product_categories_link.product_category_id', $postedData['product_category_id']);
			}
			if (!empty($postedData['sale_executive_id'])) {
				$responseDataObj->where('user_sales_target_details.ust_user_id', $postedData['sale_executive_id']);
			}

			$responseData = $responseDataObj->orderBy('users.name', 'ASC')->get()->toArray();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $value) {
					$value->actual_ob_ach_mtd  = $misReport->getEmployeeBookedSalesTargetAmount($value, $postedData);
					$value->actual_inv_ach_mtd = $misReport->getEmployeeBookedSalesTargetAmount($value, $postedData);
					$value->ob_variation       = number_format((float) trim($value->total_ob_target - $value->actual_ob_ach_mtd), 2, '.', '');
					$value->inv_variation 	   = number_format((float) trim($value->total_inv_target - $value->actual_inv_ach_mtd), 2, '.', '');
					$postedData['total_ob_target_total'][$key]    = $value->total_ob_target;
					$postedData['actual_ob_ach_mtd_total'][$key]  = $value->actual_ob_ach_mtd;
					$postedData['ob_variation_total'][$key]       = $value->ob_variation;
					$postedData['total_inv_target_total'][$key]   = $value->total_inv_target;
					$postedData['actual_inv_ach_mtd_total'][$key] = $value->actual_inv_ach_mtd;
					$postedData['inv_variation_total'][$key]      = $value->inv_variation;
				}
			}
		}


		$totalColumn			= array(array('TOTAL', array_sum($total)));
		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();

		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('ust_user_id', 'ust_date'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead']		= !empty($responseData) ? $misReport->formatTableHeader(array_keys(end($responseData)), $rupeeSymbol, $type = '1') : array();

		$response['tableBody']		= !empty($responseData) ? array_values($responseData) : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData(array_keys(end($responseData)), $postedData, $type = '12') : array();
		$response['summary']		= array();
		$error   			= !empty($responseData) ? '1' : '0';
		$message 			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Employee Sales Target Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (21-July-2018)
	 */
	public function employee_sales_target_detail_v1($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response     = $responseData = $total = $summaryData = array();
		$creditAmount = '0';
		$rupeeSymbol  = '&#x20B9;';

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			//****************************Budget,Forcast,Actual & Actual Previous**************************************
			$responseData = $misReport->employee_sales_target_detail_budget_forcast_actual_prevactual($postedData);
			//***************************/Budget,Forcast,Actual & Actual Previous**************************************

			if (!empty($responseData)) {
				foreach ($responseData as $key => $value) {
					$value->customer_type      	  	  = $misReport->getCustomerTypeDetail($value->ust_customer_id);
					$value->month  	           	  	  = !empty($value->month) ? date('M', strtotime($value->month)) : '';
					$value->amount             	  	  = !empty($value->amount) ? $models->roundValues($value->amount) : '';
					$value->amount_in_lakh  	  	  = !empty($value->amount_in_lakh) ? $models->convertValues('L', $value->amount_in_lakh) : '';
					$postedData['total_amount'][$key] 	  = $value->amount;
					$postedData['total_amount_in_lakh'][$key] = $value->amount_in_lakh;
				}
			}
		}

		$responseData 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 			= $models->unsetFormDataVariablesArray($responseData, array('ust_user_id', 'ust_division_id', 'ust_product_category_id', 'ust_customer_id', 'ust_month', 'ust_year'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead']		= !empty($responseData) ? $misReport->formatTableHeader(array_keys(end($responseData)), $rupeeSymbol, $type = '2') : array();
		$response['tableBody']		= !empty($responseData) ? array_values($responseData) : array();
		$response['tablefoot']		= !empty($responseData) ? $misReport->getTableFooterData(array_keys(end($responseData)), $postedData, $type = '13') : array();
		$response['summary']		= array();
		$error   			= !empty($responseData) ? '1' : '0';
		$message 			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * generate MIS Report::Client Approval Process Detail
	 *
	 * Created By : Praveen Singh
	 * Created On : Scope-1 (21-July-2018)
	 */
	public function client_approval_process_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $totalSampleAmount = $totalInvoiceAmount = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_client_approval_dtl')
				->join('order_master', 'order_master.order_id', 'order_client_approval_dtl.ocad_order_id')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->select('divisions.division_name as branch', 'departments.department_name as department', 'customer_master.customer_name', 'customer_master.customer_code', 'state_db.state_name as customer_state', 'city_db.city_name as customer_city', 'order_master.order_no', 'order_master.order_date', 'order_client_approval_dtl.ocad_approved_by as approved_by', 'order_client_approval_dtl.ocad_date as date', 'order_client_approval_dtl.ocad_credit_period as credit_period', 'order_client_approval_dtl.ocad_date_upto_amt as date_upto_amount')
				->whereBetween(DB::raw("DATE(order_client_approval_dtl.ocad_date)"), array($postedData['date_from'], $postedData['date_to']));

			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}

			$responseDataObj->orderBy('order_client_approval_dtl.ocad_id', 'ASC')->orderBy('customer_master.customer_name', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $values) {
					$values->order_date 	   = date(DATEFORMATEXCEL, strtotime($values->order_date));
					$values->date 			   = date(DATEFORMATEXCEL, strtotime($values->date));
					$values->date_upto_amount  = date(DATEFORMATEXCEL, strtotime($values->date_upto_amount));
				}
			}
		}

		$responseData 		 			= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 		 			= $models->unsetFormDataVariablesArray($responseData, array('order_id', 'customer_id', 'order_status_id', 'hold_reason'));
		$response['mis_report_name']  	= !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 			= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 		= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 		= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 	 		= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 	 		= !empty($responseData) ? $responseData : array();
		$response['tablefoot']	 		= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '6') : array();
		$error        		 			= !empty($responseData) ? '1' : '0';
		$message      		 			= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}

	/**
	 * ITC Std Price Vs Sample Price Reports
	 *
	 * Created By : Praveen Singh
	 * Created On : 21-Sept-2021
	 */
	public function itc_std_price_vs_sample_price_detail($postedData, $searchCriteria)
	{

		global $order, $models, $misReport, $mail;

		$response = $orderBookingCodeArray = array();

		if (!empty($postedData['date_from']) && !empty($postedData['date_to'])) {

			$responseDataObj = DB::table('order_master')
				->join('order_parameters_detail', 'order_parameters_detail.order_id', 'order_master.order_id')
				->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', 'order_master.invoicing_type_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('users as createdBy', 'createdBy.id', 'order_master.created_by')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('samples', 'samples.sample_id', 'order_master.sample_id')
				->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
				->join('order_status', 'order_status.order_status_id', 'order_master.status')
				->join('product_master', 'product_master.product_id', 'order_master.product_id')
				->join('product_categories as subCategory', 'subCategory.p_category_id', 'product_master.p_category_id')
				->join('product_categories as parentCategory', 'parentCategory.p_category_id', 'subCategory.parent_id')
				->join('test_standard', 'test_standard.test_std_id', 'order_master.test_standard')
				->leftJoin('trf_hdrs', 'trf_hdrs.trf_id', 'samples.trf_id')
				->leftJoin('test_standard as test_standard_db', 'test_standard_db.test_std_id', 'order_master.defined_test_standard')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id');

			if (!empty($postedData['is_display_pcd'])) {
				$responseDataObj->select('divisions.division_name as Branch', 'departments.department_name as department', 'order_master.order_id', 'order_master.customer_id', 'customer_master.customer_name as party_name', 'city_db.city_name as place', 'customer_master.customer_gst_no as party_gst_no', 'salesExecutive.name as sales_executive_name', 'parentCategory.p_category_name as category_name', 'subCategory.p_category_name as sub_category_name', 'product_master_alias.c_product_name as product_name', 'test_standard.test_std_name as test_standard', 'test_standard_db.test_std_name as defined_test_standard', 'order_master.batch_no', 'order_sample_priority.sample_priority_name as sample_priority', 'samples.sample_no as sample_receiving_code', 'samples.sample_current_date as sample_receiving_date', 'samples.sample_current_date as sample_receiving_time', 'order_master.order_no as sample_reg_code', 'trf_hdrs.trf_no', 'order_master.order_date as booking_date', 'order_master.order_date as booking_time', 'order_master.booking_date as current_date', 'order_master.booking_date as current_time', 'order_master.expected_due_date', 'customer_invoicing_types.invoicing_type', 'order_master.hold_reason', 'order_status.order_status_id', 'order_status.order_status_name as order_stage', 'createdBy.name as booking_person_name', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'order_parameters_detail.selling_price as itc_price', 'order_master.booked_order_amount as sample_amount', 'order_parameters_detail.variation_price as customer_discount_price_parameter_wise', 'order_parameters_detail.variation_price as customer_price_parameter_wise');
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			} else {
				$responseDataObj->select('divisions.division_name as Branch', 'departments.department_name as department', 'order_master.order_id', 'order_master.customer_id', 'customer_master.customer_name as party_name', 'city_db.city_name as place', 'customer_master.customer_gst_no as party_gst_no', 'salesExecutive.name as sales_executive_name', 'parentCategory.p_category_name as category_name', 'subCategory.p_category_name as sub_category_name', 'product_master_alias.c_product_name as product_name', 'test_standard.test_std_name as test_standard', 'test_standard_db.test_std_name as defined_test_standard', 'order_master.batch_no', 'order_sample_priority.sample_priority_name as sample_priority', 'samples.sample_no as sample_receiving_code', 'samples.sample_current_date as sample_receiving_date', 'samples.sample_current_date as sample_receiving_time', 'order_master.order_no as sample_reg_code', 'trf_hdrs.trf_no', 'order_master.order_date as booking_date', 'order_master.order_date as booking_time', 'order_master.expected_due_date', 'customer_invoicing_types.invoicing_type', 'order_master.hold_reason', 'order_status.order_status_id', 'order_status.order_status_name as order_stage', 'createdBy.name as booking_person_name', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'order_parameters_detail.selling_price as itc_price', 'order_master.booked_order_amount as sample_amount', 'order_parameters_detail.variation_price as customer_discount_price_parameter_wise', 'order_parameters_detail.variation_price as customer_price_parameter_wise');
				$responseDataObj->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($postedData['date_from'], $postedData['date_to']));
			}
			if (!empty($postedData['division_id'])) {
				$responseDataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$responseDataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			if (!empty($postedData['sale_executive_id'])) {
				$responseDataObj->where('order_master.sale_executive', $postedData['sale_executive_id']);
			}
			if (!empty($postedData['customer_id'])) {
				$responseDataObj->where('order_master.customer_id', $postedData['customer_id']);
			}
			$responseDataObj->orderBy('order_master.order_no', 'ASC')->orderBy('customer_master.customer_name', 'ASC')->orderBy('order_master.order_date', 'ASC');
			$responseData = $responseDataObj->get();

			if (!empty($responseData)) {
				foreach ($responseData as $key => $values) {
					$values->test_parameter_name								= strip_tags(htmlspecialchars_decode(html_entity_decode($values->test_parameter_name)));
					$values->booking_date 										= date(DATEFORMATEXCEL, strtotime($values->booking_date));
					$values->booking_time 										= date('h:i A', strtotime($values->booking_time));
					if (!empty($postedData['is_display_pcd'])) {
						$values->current_date 									= date(DATEFORMATEXCEL, strtotime($values->current_date));
						$values->current_time 									= date('h:i A', strtotime($values->current_time));
					}
					$values->sample_receiving_date								= !empty($values->sample_receiving_date) ? date(DATEFORMATEXCEL, strtotime($values->sample_receiving_date)) : '';
					$values->sample_receiving_time								= !empty($values->sample_receiving_time) ? date('h:i A', strtotime($values->sample_receiving_time)) : '';
					$values->expected_due_date 									= date(DATEFORMATEXCEL, strtotime($values->expected_due_date));
					$values->itc_price 											= $models->roundValue(!empty($values->itc_price) ? $values->itc_price : '0.00');
					$values->sample_amount 										= !in_array($values->sample_reg_code, $orderBookingCodeArray) ? ($models->roundValue(!empty($values->sample_amount) ? $values->sample_amount : $misReport->getBookedSamplePrice($values->customer_id, $values->order_id))) : '';
					$values->customer_discount_price_parameter_wise 			= !empty($values->customer_discount_price_parameter_wise) ? $models->roundValue($values->customer_discount_price_parameter_wise) : '0.00';
					$values->customer_price_parameter_wise 						= $models->roundValue($values->itc_price - $values->customer_discount_price_parameter_wise);
					$values->order_stage										= !empty($values->order_status_id) && $values->order_status_id == '12' && !empty($values->hold_reason) ? $values->order_stage . '(' . trim($values->hold_reason) . ')' : $values->order_stage;
					$postedData['itc_price'][$key]								= $values->itc_price;
					$postedData['sample_amount'][$key]							= $values->sample_amount;
					$postedData['customer_discount_price_parameter_wise'][$key]	= $values->customer_discount_price_parameter_wise;
					$postedData['customer_price_parameter_wise'][$key]			= $values->customer_price_parameter_wise;
					$orderBookingCodeArray[$values->sample_reg_code] 			= $values->sample_reg_code;
				}
			}
		}

		$responseData 		 		= !empty($responseData) ? json_decode(json_encode($responseData), true) : array();
		$responseData 		 		= $models->unsetFormDataVariablesArray($responseData, array('order_id', 'customer_id', 'order_status_id', 'hold_reason'));
		$response['mis_report_name'] = !empty($postedData['headingTxt']) ? $postedData['headingTxt'] : '';
		$response['heading'] 		= !empty($responseData) && !empty($postedData['headingTxt']) ? $postedData['headingTxt'] . '(' . count($responseData) . ')' : '';
		$response['search_key'] 	= !empty($searchCriteria) ? array_keys($searchCriteria) : array();
		$response['search_value'] 	= !empty($searchCriteria) ? array_values($searchCriteria) : array();
		$response['tableHead'] 	 	= !empty($responseData) ? array_keys(end($responseData)) : array();
		$response['tableBody'] 	 	= !empty($responseData) ? $responseData : array();
		$response['tablefoot']	 	= !empty($responseData) ? $misReport->getTableFooterData($response['tableHead'], $postedData, $type = '6') : array();
		$error        		 		= !empty($responseData) ? '1' : '0';
		$message      		 		= $error ? '' : config('messages.message.noRecordFound');

		//Saving Data in Session
		Session::set('response', $response);

		return array($error, $message, $response);
	}
}
