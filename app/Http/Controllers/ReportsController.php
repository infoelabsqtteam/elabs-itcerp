<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Company;
use App\Order;
use App\Models;
use App\Report;
use App\ProductCategory;
use Session;
use Validator;
use Route;
use DB;

class ReportsController extends Controller
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

		global $order, $models, $report, $productCategory;

		$order  = new Order();
		$models = new Models();
		$report = new Report();
		$productCategory = new ProductCategory();

		//Checking the User Session
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

		global $order, $report, $models;

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('sales.reports.index', ['title' => 'Reports', '_reports' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'role_ids' => $role_ids, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getBranchWiseReports(Request $request)
	{

		global $order, $report, $models;

		$error              	= '0';
		$message    	    	= '';
		$data               	= '';
		$token_flag 			= true;
		$dateFlag				= true;
		$searchAllOnOff			= '0';
		$formData 				= $rolewiseColumnArr = $summaryStatistics = $commanColumnArr = array();
		$user_id            	= defined('USERID') ? USERID : '0';
		$division_id   	    	= defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     	= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : array();
		$role_id           		= defined('ROLEID') ? ROLEID : '0';
		$role_ids           	= defined('ROLE_IDS') ? ROLE_IDS : array();
		$equipment_type_ids 	= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
		$userWiseRoles 			= $report->getUserRoleIdTaskUncompleted(array($role_id));
		$orderIdTasksCompleted  = $report->getUserOrderIdTaskCompleted($role_ids, $user_id);

		//Access Permission
		if (in_array($role_id, array('14', '15'))) {
			$token_flag = false;
		} else {
			//Assigning Condition according to the Role Assigned
			parse_str($request->formData, $formData);

			$getBranchWiseReportObj = DB::table('order_master')
				->join('order_parameters_detail', 'order_parameters_detail.order_id', 'order_master.order_id')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_status', 'order_status.order_status_id', 'order_master.status')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('users as createdBy', 'createdBy.id', 'order_master.created_by')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->leftjoin('order_report_details', 'order_master.order_id', 'order_report_details.report_id')
				->leftjoin('city_db', 'order_master.customer_city', 'city_db.city_id')
				->leftjoin('customer_billing_types', 'customer_billing_types.billing_type_id', 'order_master.billing_type_id')
				->leftJoin('invoice_hdr_detail', function ($join) {
					$join->on('invoice_hdr_detail.order_id', '=', 'order_master.order_id');
					$join->where('invoice_hdr_detail.invoice_hdr_status', '1');
				})
				->leftJoin('order_dispatch_dtl', function ($join) use ($role_id) {
					$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
					$join->where('order_dispatch_dtl.amend_status', '0');
					if ($role_id == '12') {
						$join->whereRaw('order_dispatch_dtl.dispatch_id IN (SELECT MAX(odd.dispatch_id) FROM order_dispatch_dtl odd WHERE odd.order_id = order_master.order_id)');
					}
				})
				->leftJoin('users as dispatchBy', 'dispatchBy.id', 'order_dispatch_dtl.dispatch_by')
				->leftJoin('order_process_log', function ($join) {
					$join->on('order_process_log.opl_order_id', '=', 'order_master.order_id');
					$join->where('order_process_log.opl_current_stage', '1');
					$join->where('order_process_log.opl_amend_status', '0');
				})
				->leftJoin('stb_order_hdr_dtl_detail', function ($join) {
					$join->on('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id', '=', 'order_master.stb_order_hdr_detail_id');
					$join->whereNotNull('order_master.stb_order_hdr_detail_id');
				})
				->leftJoin('order_mail_dtl', function ($join) {
					$join->on('order_mail_dtl.order_id', '=', 'order_master.order_id');
					$join->whereNotNull('order_mail_dtl.order_id');
					$join->where('order_mail_dtl.mail_active_type', '=', '1');
					$join->where('order_mail_dtl.mail_content_type', '3');
				})
				->leftJoin('stb_order_hdr', 'stb_order_hdr.stb_order_hdr_id', 'stb_order_hdr_dtl_detail.stb_order_hdr_id')
				->leftJoin('order_linked_stp_dtl', 'order_linked_stp_dtl.olsd_order_id', 'order_master.order_id');

			//Sub-function for Global filter and all condition
			$this->setConditionAccordingToRoleAssigned($getBranchWiseReportObj, $userWiseRoles, $formData);
			$this->getReportsMultisearch($getBranchWiseReportObj, $userWiseRoles, $formData);

			//Search All Functionality On/Off Checker and Summary Statistics Detail
			$searchAllOnOff    = !empty($formData['order_search_all']) ? '1' : '0';
			$summaryStatistics = $report->summaryStatistics($role_ids, $userWiseRoles, $user_id, $division_id, $department_ids, $equipment_type_ids);		//summary Statistics of the Users

			//Column Selection based on Roles
			$commanColumnArr = array('order_master.order_id', 'order_master.order_no', 'order_master.product_category_id', 'order_master.order_date', 'stb_order_hdr.stb_prototype_no', 'order_report_details.*', 'order_master.sample_description_id', 'order_master.remarks', 'order_master.expected_due_date', 'customer_master.customer_code', 'customer_master.customer_name', 'order_sample_priority.sample_priority_name', 'order_sample_priority.sample_priority_color_code', 'order_master.status as order_status', 'order_status.order_status_name', 'order_status.color_code', 'divisions.division_name', 'createdBy.name as createdByName', 'product_master_alias.c_product_name as sample_description', 'customer_billing_types.billing_type_id', 'customer_billing_types.billing_type', 'city_db.city_name as customer_city', 'order_dispatch_dtl.dispatch_id as dispatch_status', 'order_dispatch_dtl.amend_status', 'order_dispatch_dtl.dispatch_date', 'dispatchBy.name as dispatch_by', 'order_process_log.opl_date as order_status_time', 'order_master.order_sample_type', 'invoice_hdr_detail.invoice_dtl_id as invoice_status', 'order_linked_stp_dtl.olsd_cstp_no', 'order_linked_stp_dtl.olsd_cstp_file_name', 'order_mail_dtl.mail_status as order_mail_status', 'order_master.order_id as order_mail_status_text', 'order_master.batch_no');
			if (in_array('4', $userWiseRoles) || in_array('1', $userWiseRoles)) {
				$rolewiseColumnArr = array('order_master.order_dept_due_date', 'order_master.order_report_due_date', 'order_incharge_dtl.oid_confirm_by');
			} else if (in_array('3', $userWiseRoles)) {		//Tester
				$dateFlag = true;
				$rolewiseColumnArr = array('order_parameters_detail.dept_due_date as order_dept_due_date', 'order_parameters_detail.report_due_date as order_report_due_date');
			}
			$getBranchWiseReportObj->select(array_merge($commanColumnArr, $rolewiseColumnArr));
			$getBranchWiseReportObj->groupBy('order_master.order_id');
			$getBranchWiseReportObj->orderBy('order_master.order_date', 'DESC');
			$getBranchWiseReports = $getBranchWiseReportObj->get();

			//to formate created and updated date
			$models->formatTimeStampFromArray($getBranchWiseReports, DATETIMEFORMAT, true);
		}

		//Setting permission Data
		$getBranchWiseReports = $token_flag ? $getBranchWiseReports : array();

		return response()->json(array('error' => $error, 'message' => $message, 'searchAllOnOff' => $searchAllOnOff, 'summaryStatistics' => $summaryStatistics, 'getBranchWiseReports' => $getBranchWiseReports));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generateBranchWiseReportPdf(Request $request)
	{

		global $order, $report, $models;

		$error    = '0';
		$message  = '';
		$data     = '';
		$formData = array();

		if ($request->isMethod('post') && !empty($request->generate_report_documents)) {

			$user_id            = defined('USERID') ? USERID : '0';
			$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$role_id           	= defined('ROLEID') ? ROLEID : '0';
			$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
			$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
			$userWiseRoles 	= $report->getUserRoleIdTaskUncompleted(array($role_id));
			$orderIdTasksCompleted = $report->getUserOrderIdTaskCompleted($role_ids, $user_id);

			//Assigning Condition according to the Role Assigned
			$formData = $request->all();

			$getBranchWiseReportObj = DB::table('order_master')
				->join('order_parameters_detail', 'order_parameters_detail.order_id', 'order_master.order_id')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_status', 'order_status.order_status_id', 'order_master.status')
				->leftjoin('order_report_details', 'order_master.order_id', 'order_report_details.report_id')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->leftjoin('city_db', 'customer_master.customer_city', 'city_db.city_id')
				->leftjoin('customer_billing_types', 'customer_billing_types.billing_type_id', 'order_master.billing_type_id')
				->join('users as createdBy', 'createdBy.id', 'order_master.created_by')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->leftJoin('invoice_hdr_detail', function ($join) {
					$join->on('invoice_hdr_detail.order_id', '=', 'order_master.order_id');
					$join->where('invoice_hdr_detail.invoice_hdr_status', '1');
				})
				->leftJoin('order_dispatch_dtl', function ($join) use ($role_id) {
					$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
					$join->where('order_dispatch_dtl.amend_status', '0');
					if ($role_id == '12') {
						$join->whereRaw('order_dispatch_dtl.dispatch_id IN (SELECT MAX(odd.dispatch_id) FROM order_dispatch_dtl odd WHERE odd.order_id = order_master.order_id)');
					}
				})
				->leftJoin('users as dispatchByDB', 'dispatchByDB.id', 'order_dispatch_dtl.dispatch_by')
				->leftJoin('order_process_log', function ($join) {
					$join->on('order_process_log.opl_order_id', '=', 'order_master.order_id');
					$join->where('order_process_log.opl_current_stage', '1');
					$join->where('order_process_log.opl_amend_status', '0');
				})
				->leftJoin('stb_order_hdr_dtl_detail', function ($join) {
					$join->on('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id', '=', 'order_master.stb_order_hdr_detail_id');
					$join->whereNotNull('order_master.stb_order_hdr_detail_id');
				})
				->leftJoin('order_mail_dtl', function ($join) {
					$join->on('order_mail_dtl.order_id', '=', 'order_master.order_id');
					$join->whereNotNull('order_mail_dtl.order_id');
					$join->where('order_mail_dtl.mail_active_type', '=', '1');
					$join->where('order_mail_dtl.mail_content_type', '3');
				})
				->leftJoin('stb_order_hdr', 'stb_order_hdr.stb_order_hdr_id', 'stb_order_hdr_dtl_detail.stb_order_hdr_id');

			$this->setConditionAccordingToRoleAssigned($getBranchWiseReportObj, $userWiseRoles, $formData);
			$this->getReportsMultisearch($getBranchWiseReportObj, $userWiseRoles, $formData);

			//Column Selection based on Roles
			$commanColumnArr = array('order_master.order_id', 'order_master.order_no', 'divisions.division_name as branch', 'stb_order_hdr.stb_prototype_no', 'customer_master.customer_code', 'customer_master.customer_name', 'city_db.city_name as place', 'customer_billing_types.billing_type', 'order_master.order_date', 'order_master.expected_due_date', 'order_report_details.nabl_no', 'order_report_details.report_no', 'order_report_details.report_date', 'order_master.batch_no', 'product_master_alias.c_product_name as sample_description', 'order_sample_priority.sample_priority_name as sample_priority', 'order_master.order_dept_due_date', 'remarks', 'order_process_log.opl_date as status_time', 'order_master.status as order_status', 'order_status.order_status_name as status', 'dispatchByDB.name as dispatchBy', 'order_dispatch_dtl.dispatch_date', 'createdBy.name as created_by', 'order_master.order_id as order_mail_status_text', 'order_mail_dtl.mail_status as order_mail_status');
			if (in_array('3', $userWiseRoles)) {		//Tester
				$rolewiseColumnArr = array('order_parameters_detail.dept_due_date as order_dept_due_date', 'order_parameters_detail.report_due_date as order_report_due_date');
			} else {
				$rolewiseColumnArr = array('order_master.order_dept_due_date', 'order_master.order_report_due_date');
			}
			$getBranchWiseReportObj->select(array_merge($commanColumnArr, $rolewiseColumnArr));
			$getBranchWiseReportObj->groupBy('order_master.order_id');
			$getBranchWiseReportObj->orderBy('order_master.order_date', 'DESC');
			$getBranchWiseReports = $getBranchWiseReportObj->get();

			//to formate created and updated date
			$models->formatTimeStampFromArrayExcel($getBranchWiseReports, DATEFORMATEXCEL);

			if (!empty($getBranchWiseReports)) {

				$getBranchWiseReports 	= !empty($getBranchWiseReports) ? json_decode(json_encode($getBranchWiseReports), true) : array();
				$getBranchWiseReports 	= $models->unsetFormDataVariablesArray($getBranchWiseReports, array('order_id', 'product_category_id', 'canDispatchOrder', 'order_mail_status_text', 'order_status'));
				$response['heading'] 	= !empty($getBranchWiseReports) ? 'Total Reports(' . count($getBranchWiseReports) . ')' : 'Total Report';
				$response['tableHead']  = !empty($getBranchWiseReports) ? array_keys(end($getBranchWiseReports)) : array();
				$response['tableBody']  = !empty($getBranchWiseReports) ? $getBranchWiseReports : array();
				$response['tablefoot']	= array();

				if ($formData['generate_report_documents'] == 'PDF') {
					$pdfHeaderContent  		= $models->getHeaderFooterTemplate();
					$response['header_content']	= $pdfHeaderContent->header_content;
					$response['footer_content']	= $pdfHeaderContent->footer_content;
					return $models->downloadPDF($response, $contentType = 'report_pendency');
				} elseif ($formData['generate_report_documents'] == 'Excel') {
					$response['mis_report_name']	= 'report_document';
					return $models->generateExcel($response);
				}
			} else {
				Session::put('errorMsg', config('messages.message.noRecordFound'));
				return redirect('dashboard');
			}
		}

		Session::put('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		return redirect('dashboard');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function setConditionAccordingToRoleAssigned($getBranchWiseReportObj, $userWiseRoles, $formData)
	{

		global $order, $report, $models;

		$user_id            	= defined('USERID') ? USERID : '0';
		$division_id   	        = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     	= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : array();
		$role_ids           	= defined('ROLE_IDS') ? ROLE_IDS : array();
		$equipment_type_ids 	= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
		$divisionId     		= !empty($formData['division_id']) ? $formData['division_id'] : $division_id;
		$statusTypeId   		= !empty($formData['status_id']) ? $formData['status_id'] : '0';
		$orderDateFrom  		= !empty($formData['order_date_from']) ? $formData['order_date_from'] : '0';
		$orderDateTo    		= !empty($formData['order_date_to']) ? $formData['order_date_to'] : '0';
		$expectedDueDateFrom  	= !empty($formData['expected_due_date_from']) ? $formData['expected_due_date_from'] : '0';
		$expectedDueDateTo    	= !empty($formData['expected_due_date_to']) ? $formData['expected_due_date_to'] : '0';
		$departmentDueDateFrom  = !empty($formData['dept_due_date_from']) ? $formData['dept_due_date_from'] : '0';
		$departmentDueDateTo  	= !empty($formData['dept_due_date_to']) ? $formData['dept_due_date_to'] : '0';
		$keyword        		= !empty($formData['keyword']) ? $formData['keyword'] : '0';
		$searchFromAll        	= !empty($formData['order_search_all']) ? '1' : '0';
		$orderDispatchPendency  = !empty($formData['order_search_dispatch_pendency']) ? $formData['order_search_dispatch_pendency'] : '0';

		//cancelled orders not visisble any where
		$getBranchWiseReportObj->whereNotIn('order_master.status', array('10', '12'));

		//************gettting records according to roles**************************************
		if (empty($searchFromAll) && !empty($userWiseRoles) && is_array($userWiseRoles)) {
			if (in_array('3', $userWiseRoles)) {		//Tester		
				//For all User Roles
				$getBranchWiseReportObj->whereIn('order_master.status', $userWiseRoles);
				$getBranchWiseReportObj->join('schedulings', 'schedulings.order_id', 'order_master.order_id');
				$getBranchWiseReportObj->where('schedulings.employee_id', '=', $user_id);
				$getBranchWiseReportObj->whereIn('schedulings.status', array('1', '2'));
			} else if (in_array('4', $userWiseRoles) || in_array('1', $userWiseRoles)) {	//Section Incharge
				$getBranchWiseReportObj->join('order_incharge_dtl', function ($join) use ($report, $user_id, $equipment_type_ids) {
					$join->on('order_incharge_dtl.order_id', '=', 'order_parameters_detail.order_id');
					$join->whereColumn('order_incharge_dtl.oid_equipment_type_id', '=', 'order_parameters_detail.equipment_type_id');
					$join->whereNull('order_incharge_dtl.oid_confirm_date');
					$join->whereNull('order_incharge_dtl.oid_confirm_by');
					$join->where('order_incharge_dtl.oid_status', '0');
					$join->whereNotNull('order_parameters_detail.test_performed_by');
					$join->whereNotNull('order_parameters_detail.test_result');
					$join->whereIn('order_parameters_detail.order_id', $report->getSectionInchargeOrderDetail($user_id, $equipment_type_ids));
					$join->where('order_incharge_dtl.oid_employee_id', $user_id);
				});
			} else if (in_array('9', $userWiseRoles) || in_array('11', $userWiseRoles)) {	//Dispatcher		
				//For all User Roles
				array_push($userWiseRoles, '8');	//Pusing Invoixing Stage Orders
				$getBranchWiseReportObj->whereIn('order_master.status', $userWiseRoles);
				$getBranchWiseReportObj->whereIn('order_master.billing_type_id', array('4'));
				//Condition for Checking Pendency Count
				if (!empty($orderDispatchPendency)) {
					$getBranchWiseReportObj->whereNotNull('order_dispatch_dtl.dispatch_by')->whereNotNull('order_dispatch_dtl.dispatch_date');
				} else {
					$getBranchWiseReportObj->whereNull('order_dispatch_dtl.dispatch_by')->whereNull('order_dispatch_dtl.dispatch_date');
				}
			} else {
				//For all User Roles
				$getBranchWiseReportObj->whereIn('order_master.status', $userWiseRoles);
			}
		} else {
			if (in_array('3', $userWiseRoles)) {
				$getBranchWiseReportObj->join('schedulings', 'schedulings.order_id', 'order_master.order_id');
				$getBranchWiseReportObj->where('schedulings.employee_id', '=', $user_id);
			} else if (in_array('4', $userWiseRoles) || in_array('1', $userWiseRoles)) {	//Section Incharge		
				$getBranchWiseReportObj->join('order_incharge_dtl', function ($join) use ($report, $user_id, $equipment_type_ids) {
					$join->on('order_incharge_dtl.order_id', '=', 'order_parameters_detail.order_id');
					$join->whereColumn('order_incharge_dtl.oid_equipment_type_id', '=', 'order_parameters_detail.equipment_type_id');
					$join->whereNull('order_incharge_dtl.oid_confirm_date');
					$join->whereNull('order_incharge_dtl.oid_confirm_by');
					$join->whereNotNull('order_parameters_detail.test_performed_by');
					$join->whereNotNull('order_parameters_detail.test_result');
					$join->whereIn('order_parameters_detail.order_id', $report->getSectionInchargeOrderDetail($user_id, $equipment_type_ids));
					$join->where('order_incharge_dtl.oid_employee_id', $user_id);
				});
			}
		}
		//************/gettting records according to roles**************************************

		//Filtering records according to department assigned
		if (!empty($department_ids) && is_array($department_ids)) {
			$getBranchWiseReportObj->whereIn('order_master.product_category_id', $department_ids);
		}
		//Filtering records according to division assigned
		if (!empty($divisionId) && is_numeric($divisionId)) {
			$getBranchWiseReportObj->where('order_master.division_id', $divisionId);
		}
		//Filtering records according to from and to order date
		if (!empty($orderDateFrom) && !empty($orderDateTo)) {
			$getBranchWiseReportObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($orderDateFrom, $orderDateTo));
		} else if (!empty($orderDateFrom) && empty($orderDateTo)) {
			$getBranchWiseReportObj->where(DB::raw("DATE(order_master.order_date)"), '>=', $orderDateFrom);
		} else if (empty($orderDateFrom) && !empty($orderDateTo)) {
			$getBranchWiseReportObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $orderDateTo);
		} else if (empty($keyword) && empty($userWiseRoles)) {
			$getBranchWiseReportObj->where(DB::raw("DATE(order_master.order_date)"), date('Y-m-d'));
		}
		//Filtering records according to expected due date from and expected due date to
		if (!empty($expectedDueDateFrom) && !empty($expectedDueDateTo)) {
			$getBranchWiseReportObj->whereBetween(DB::raw("DATE(order_master.expected_due_date)"), array($expectedDueDateFrom, $expectedDueDateTo));
		} else if (!empty($expectedDueDateFrom) && empty($expectedDueDateTo)) {
			$getBranchWiseReportObj->where(DB::raw("DATE(order_master.expected_due_date)"), '>=', $expectedDueDateFrom);
		} else if (empty($expectedDueDateFrom) && !empty($expectedDueDateTo)) {
			$getBranchWiseReportObj->where(DB::raw("DATE(order_master.expected_due_date)"), '<=', $expectedDueDateTo);
		}
		//Filtering records according to Department due date from and Department due date to
		if (!empty($userWiseRoles) && in_array('3', $userWiseRoles)) {
			if (!empty($departmentDueDateFrom) && !empty($departmentDueDateTo)) {
				$departmentDueDateOrderId = $order->getMaxDepartmentDueDateOrderDetail('between', $departmentDueDateFrom, $departmentDueDateTo, USERID);
				$getBranchWiseReportObj->whereIn('order_master.order_id', $departmentDueDateOrderId);
			} else if (!empty($departmentDueDateFrom) && empty($departmentDueDateTo)) {
				$departmentDueDateOrderId = $order->getMaxDepartmentDueDateOrderDetail('greaterThenEqualTo', $departmentDueDateFrom, $departmentDueDateTo, USERID);
				$getBranchWiseReportObj->whereIn('order_master.order_id', $departmentDueDateOrderId);
			} else if (empty($departmentDueDateFrom) && !empty($departmentDueDateTo)) {
				$departmentDueDateOrderId = $order->getMaxDepartmentDueDateOrderDetail('lessThenEqualTo', $departmentDueDateFrom, $departmentDueDateTo, USERID);
				$getBranchWiseReportObj->whereIn('order_master.order_id', $departmentDueDateOrderId);
			}
		} else {
			if (!empty($departmentDueDateFrom) && !empty($departmentDueDateTo)) {
				$getBranchWiseReportObj->whereBetween(DB::raw("DATE('order_master.order_dept_due_date')"), array($departmentDueDateFrom, $departmentDueDateTo));
			} else if (!empty($departmentDueDateFrom) && empty($departmentDueDateTo)) {
				$getBranchWiseReportObj->where(DB::raw("DATE('order_master.order_dept_due_date')"), '>=', $departmentDueDateFrom);
			} else if (empty($departmentDueDateFrom) && !empty($departmentDueDateTo)) {
				$getBranchWiseReportObj->where(DB::raw("DATE('order_master.order_dept_due_date')"), '<=', $departmentDueDateTo);
			}
		}
		//Filtering records according to search keyword
		if (!empty($keyword)) {
			$getBranchWiseReportObj->where('order_master.order_no', '=', trim($keyword));
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getReportsMultisearch($getBranchWiseReportObj, $userWiseRoles, $searchArry)
	{

		global $order, $report, $models;

		if (!empty($searchArry['search_order_no'])) {
			$getBranchWiseReportObj->where('order_master.order_no', 'LIKE', '%' . trim($searchArry['search_order_no']) . '%');
		}
		if (!empty($searchArry['search_batch_no'])) {
			$getBranchWiseReportObj->where('order_master.batch_no', 'LIKE', '%' . trim($searchArry['search_batch_no']) . '%');
		}
		if (!empty($searchArry['search_division_id'])) {
			$getBranchWiseReportObj->where('divisions.division_name', 'LIKE', '%' . trim($searchArry['search_division_id']) . '%');
		}
		if (!empty($searchArry['search_customer_code'])) {
			$getBranchWiseReportObj->where('customer_master.customer_code', 'LIKE', '%' . trim($searchArry['search_customer_code']) . '%');
		}
		if (!empty($searchArry['search_customer_name'])) {
			$getBranchWiseReportObj->where('customer_master.customer_name', 'LIKE', '%' . trim($searchArry['search_customer_name']) . '%');
		}
		if (!empty($searchArry['search_customer_city'])) {
			$getBranchWiseReportObj->where('city_db.city_name', 'LIKE', '%' . trim($searchArry['search_customer_city']) . '%');
		}
		if (!empty($searchArry['search_billing_type'])) {
			$getBranchWiseReportObj->where('customer_billing_types.billing_type', 'LIKE', '%' . trim($searchArry['search_billing_type']) . '%');
		}
		if (!empty($searchArry['search_order_date'])) {
			$getBranchWiseReportObj->where('order_master.order_date', 'LIKE', '%' . $models->getFormatedDate(trim($searchArry['search_order_date']), MYSQLDATFORMAT) . '%');
		}
		if (!empty($searchArry['search_expected_due_date'])) {
			$getBranchWiseReportObj->where('order_master.expected_due_date', 'LIKE', '%' . $models->getFormatedDate(trim($searchArry['search_expected_due_date']), MYSQLDATFORMAT) . '%');
		}
		if (!empty($searchArry['search_dept_due_date'])) {
			$getBranchWiseReportObj->where('order_master.order_dept_due_date', 'LIKE', '%' . $models->getFormatedDate(trim($searchArry['search_dept_due_date']), MYSQLDATFORMAT) . '%');
		}
		if (!empty($searchArry['search_nabl_no'])) {
			$getBranchWiseReportObj->where('order_report_details.nabl_no', 'LIKE', '%' . trim($searchArry['search_nabl_no']) . '%');
		}
		if (!empty($searchArry['search_report_no'])) {
			$getBranchWiseReportObj->where('order_report_details.report_no', 'LIKE', '%' . trim($searchArry['search_report_no']) . '%');
		}
		if (!empty($searchArry['search_report_date'])) {
			$getBranchWiseReportObj->where('order_report_details.report_date', 'LIKE', '%' . $models->getFormatedDate(trim($searchArry['search_report_date']), MYSQLDATFORMAT) . '%');
		}
		if (!empty($searchArry['search_sample_description'])) {
			$getBranchWiseReportObj->where('product_master_alias.c_product_name', 'LIKE', '%' . trim($searchArry['search_sample_description']) . '%');
		}
		if (!empty($searchArry['search_sample_priority_name'])) {
			$getBranchWiseReportObj->where('order_sample_priority.sample_priority_name', 'LIKE', '%' . trim($searchArry['search_sample_priority_name']) . '%');
		}
		if (!empty($searchArry['search_remarks'])) {
			$getBranchWiseReportObj->where('order_master.remarks', 'LIKE', '%' . trim($searchArry['search_remarks']) . '%');
		}
		if (!empty($searchArry['search_status_time'])) {
			$getBranchWiseReportObj->where('order_process_log.opl_date', 'LIKE', '%' . $models->getFormatedDate(trim($searchArry['search_status_time']), MYSQLDATFORMAT) . '%');
		}
		if (!empty($searchArry['search_status'])) {
			$getBranchWiseReportObj->where('order_status.order_status_name', 'LIKE', '%' . $searchArry['search_status'] . '%');
		}
		if (!empty($searchArry['search_created_by'])) {
			$getBranchWiseReportObj->where('createdBy.name', 'LIKE', '%' . trim($searchArry['search_created_by']) . '%');
		}
		if (!empty($searchArry['search_dispatch_date'])) {
			$getBranchWiseReportObj->where('order_dispatch_dtl.dispatch_date', 'LIKE', '%' . $models->getFormatedDate(trim($searchArry['search_dispatch_date']), MYSQLDATFORMAT) . '%');
		}
		if (!empty($searchArry['search_dispatch_by'])) {
			$getBranchWiseReportObj->where('dispatchBy.name', 'LIKE', '%' . trim($searchArry['search_dispatch_by']) . '%');
		}
		if (!empty($searchArry['search_order_mail_status'])) {
			$order_mail_status_id = $order->getMailStatusText(trim($searchArry['search_order_mail_status']));
			$order_mail_status_id = $order_mail_status_id == '2' ? '' : $order_mail_status_id;
			$getBranchWiseReportObj->where('order_mail_dtl.mail_status', '=', $order_mail_status_id);
		}
	}

	/**
	 * View of Order and Its Parameters Detail
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function viewOrder(Request $request, $order_id)
	{

		global $order, $models, $report;

		$error   = '0';
		$message = $inactive_analyst_message = '';
		$data    = $hasMicrobiologicalEquipment = $hasPermToSaveTestResult = $hasPermToInvoiceTestResult = '';
		$orderParameterList = $disciplineWiseParametersList = $rawTestProductStdParaList = $hasPermissionToSaveTestResult = $categoryWiseParamenter = $hasPermissionToFinaliseForInvoice = $errorParameterIdsArr = $categoryWiseParamenterArr = $orderHasClaimValueOrNot = $nablTestParameterDetail = $descriptionWiseParameterList = $disciplineWiseParameterList = $categoryWiseParameterList = array();

		if ($order_id) {
			$error              					= '1';
			$nablCodeActivationDate					= defined('NABL_CODE_ACTIVATION_DATE') ? trim(NABL_CODE_ACTIVATION_DATE) : '2020-07-15';
			$equipment_type_ids 					= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
			$role_ids           					= defined('ROLE_IDS') ? ROLE_IDS : '0';
			$user_id            					= defined('USERID') ? USERID : '0';
			$allowedExceptionParameters 			= array('description', 'description(cl:3.2.1)', 'description(cl:3.2)', 'reference to protocol');
			$orderList              				= $order->getOrder($order_id);
			$orderList->isBackDateBookingAllowed    = !empty($orderList->product_category_id) ? $report->checkBackDateBookingAllowed($orderList) : '0';
			$orderList->testParametersWithSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
			$orderList->assayParametersWithSpace 	= defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
			$orderList->assayParametersWithoutSpace = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ", "", ASSAY_PARAMETERS)) : '';
			$orderList->testParametersWithoutSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ", "", TEST_PARAMETERS)) : '';
			$orderList->orderAmendStatus 			= !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0';
			$orderList->order_nabl_scope_symbol		= $report->hasOrderApplicableForNablScopeAsteriskSymbolInView($order_id);	//Checking Order is applicable for NABL Scope * Symbol
			$orderList->order_nabl_scope			= $report->hasOrderApplicableForNablScope_v1($order_id);			//Checking Order is applicable for NABL Number
			$orderList->order_nabl_os_remark_scope	= $report->getFullyPartialNablOutsourceSampleScope($order_id);			//Getting Fully NABL Scope/Partial NABL Scope/Outsource Sample Scope
			$orderList->customer_hold_message		= !empty($order->isCustomerPutOnHold($orderList->customer_id)) ? config('messages.message.reportApprovingError') : ''; 	//If Customer is put on hold
			$orderList->nabl_report_generation_type	= !empty($report->__validateNablCodeGenerationAppliciability($order_id, $nablCodeActivationDate)) ? '1' : '0'; //Report Generation Type - Older or Newer
			$errorParameterIdsArr   				= !empty($orderList) ? explode(',', $orderList->error_parameter_ids) : array();
			$testProductStdParaList 				= $order->getOrderParameters($order_id);
			$orderPerformerRecord					= $order->getOrderPerformerRecord($order_id);
			$checkReportQuality 					= $report->qualityStampOnWebView($orderList);

			//to formate order and Report date
			$models->formatTimeStamp($orderList, DATETIMEFORMAT);

			if (!empty($testProductStdParaList)) {
				foreach ($testProductStdParaList as $key => $values) {

					if (!empty($errorParameterIdsArr)) {
						if (in_array($values->analysis_id, $errorParameterIdsArr)) {
							$values->errorClass = "errorClass";
						} else {
							$values->errorClass = "";
						}
					}

					//checking if desccription has been edited or not
					if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
						if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
							$values->description = $values->test_result;
						}
					}

					//************Assignuing permission to Add the Parameter result*********
					$values->has_employee_equipment_type = '0';
					if (!empty($equipment_type_ids)) {
						if (in_array($values->equipment_type_id, $equipment_type_ids)) {
							$values->has_employee_equipment_type = '1';
						} else {
							$values->has_employee_equipment_type = '0';
						}
					} else if (defined('IS_ADMIN') && IS_ADMIN) { //Admin has all the permission
						$values->has_employee_equipment_type = '1';
					}

					//Checking if enable/disable of save button
					if (!empty($values->has_employee_equipment_type)) {
						$hasPermissionToSaveTestResult[$values->equipment_type_id] = $values->equipment_type_id;
					}

					//Checking if all test result performed or not
					$hasPermissionToFinaliseForInvoice[$values->analysis_id] = $values->test_result;

					//Getting NABL Status of Test Parameter according to category
					if (!empty($values->equipment_type_id)) {
						$nablTestParameterDetail[$values->test_para_cat_id][$values->test_parameter_id] = trim($values->order_parameter_nabl_scope);
					}

					$rawTestProductStdParaList[$values->analysis_id]  = $values;
					$orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
				}
			}

			//ORDER PARAMETER DATA FOR FOOD & ENVIRONMENT
			if (!empty($orderList->product_category_id) && in_array($orderList->product_category_id, array('1', '3', '6', '8'))) {

				if (!empty($rawTestProductStdParaList)) {
					foreach ($rawTestProductStdParaList as $analysis_id => $values) {

						$models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to); 			//Getting Requirement STD From & STD To
						$report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol); 	//Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
						$orderHasClaimValueOrNot[] = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';

						if (!empty($values->test_parameter_name) && in_array(strtolower(strip_tags($values->test_parameter_name)), $allowedExceptionParameters)) {
							$descriptionWiseParameterList[$values->test_para_cat_id]['categorySortBy']     = $values->category_sort_by;
							$descriptionWiseParameterList[$values->test_para_cat_id]['categoryId']         = $values->test_para_cat_id;
							$descriptionWiseParameterList[$values->test_para_cat_id]['categoryName']       = $values->test_para_cat_name;
							$descriptionWiseParameterList[$values->test_para_cat_id]['productCategoryName'] = str_replace(' ', '', strtolower($values->test_para_cat_name));
							$descriptionWiseParameterList[$values->test_para_cat_id]['categoryNameSymbol'] = $values->non_nabl_category_symbol;
							$descriptionWiseParameterList[$values->test_para_cat_id]['categoryParams'][]   = $values;
						} else if (!empty($values->discipline_id)) {
							$disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['discipline_id']          = $values->discipline_id;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['discipline_name']        = $values->discipline_name;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['group_id']               = $values->group_id;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['group_name']             = $values->group_name;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categorySortBy']        = $values->category_sort_by;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryId']            = $values->test_para_cat_id;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryName']          = $values->test_para_cat_name;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryNameSymbol']    = $values->non_nabl_category_symbol;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['productCategoryName']   = str_replace(' ', '', strtolower($values->test_para_cat_name));
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryParams'][]      = $values;
						} else {
							$categoryWiseParameterList[$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
							$categoryWiseParameterList[$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
							$categoryWiseParameterList[$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
							$categoryWiseParameterList[$values->test_para_cat_id]['categoryNameSymbol']   = $values->non_nabl_category_symbol;
							$categoryWiseParameterList[$values->test_para_cat_id]['productCategoryName']  = str_replace(' ', '', strtolower($values->test_para_cat_name));
							$categoryWiseParameterList[$values->test_para_cat_id]['categoryParams'][]     = $values;
						}
					}

					if (!empty($descriptionWiseParameterList)) {
						foreach ($descriptionWiseParameterList as $descriptionWiseParameter) {
							$charNum = 'a';
							foreach ($descriptionWiseParameter['categoryParams'] as $values) {
								$values->charNumber = $charNum++;
							}
						}
					}
					if (!empty($disciplineWiseParametersList)) {
						foreach ($disciplineWiseParametersList as $key => $disciplineWiseParameterListAll) {
							foreach ($disciplineWiseParameterListAll['disciplineDtl'] as $keyLevelOne => $disciplineWiseParameter) {
								$charNum = 'a';
								if (is_array($disciplineWiseParameter) && !empty($disciplineWiseParameter)) {
									foreach ($disciplineWiseParameter['categoryParams'] as $values) {
										$values->charNumber = $charNum++;
									}
								}
							}
						}
					}
					if (!empty($categoryWiseParameterList)) {
						foreach ($categoryWiseParameterList as $categoryWiseParameter) {
							$charNum = 'a';
							foreach ($categoryWiseParameter['categoryParams'] as $values) {
								$values->charNumber = $charNum++;
							}
						}
					}
				}

				$inactive_analyst_message      = $report->getOrderInactiveAnalystDetail($order_id);
				$hasMicrobiologicalEquipment   = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
				$hasPermToSaveTestResult       = !empty($hasPermissionToSaveTestResult) ? '1' : '0';
				$hasPermToInvoiceTestResult    = !empty($hasPermissionToFinaliseForInvoice) && !in_array("", $hasPermissionToFinaliseForInvoice) ? '1' : '0';
				$orderList->hasClaimValue      = array_filter($orderHasClaimValueOrNot);
				$descriptionWiseParameterList  = !empty($descriptionWiseParameterList) ? $models->sortArrayAscOrder(array_values($descriptionWiseParameterList)) : array();
				$categoryWiseParameterList     = !empty($categoryWiseParameterList) ? $models->sortArrayAscOrder(array_values($categoryWiseParameterList)) : array();
				$disciplineWiseParametersList  = !empty($disciplineWiseParametersList) ? $models->sortArrayAscOrder(array_values($disciplineWiseParametersList)) : array();
				$orderParameterList	       	   = ['descriptionWiseParameterList' => $descriptionWiseParameterList, 'categoryWiseParameterList' => $categoryWiseParameterList, 'disciplineWiseParametersList' => $disciplineWiseParametersList];
			} else {			//For Other Departments

				if (!empty($rawTestProductStdParaList)) {
					foreach ($rawTestProductStdParaList as $analysis_id => $values) {
						$models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to); 			//Getting Requirement STD From & STD To
						$report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol); 	//Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
						$orderHasClaimValueOrNot[] = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
						$orderParameterList[$values->test_para_cat_id]['categorySortBy']        = $values->category_sort_by;
						$orderParameterList[$values->test_para_cat_id]['categoryId']            = $values->test_para_cat_id;
						$orderParameterList[$values->test_para_cat_id]['categoryName']          = $values->test_para_cat_name;
						$orderParameterList[$values->test_para_cat_id]['categoryNameSymbol']    = $values->non_nabl_category_symbol;
						$orderParameterList[$values->test_para_cat_id]['productCategoryName']   = str_replace(' ', '', strtolower($values->test_para_cat_name));
						$orderParameterList[$values->test_para_cat_id]['categoryParams'][]      = $values;
					}
					foreach ($orderParameterList as $categoryWiseParameterAll) {
						$charNum = 'a';
						foreach ($categoryWiseParameterAll['categoryParams'] as $values) {
							$values->charNumber = $charNum++;
						}
					}
				}

				$inactive_analyst_message    = $report->getOrderInactiveAnalystDetail($order_id);
				$hasMicrobiologicalEquipment = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
				$hasPermToSaveTestResult     = !empty($hasPermissionToSaveTestResult) ? '1' : '0';
				$hasPermToInvoiceTestResult  = !empty($hasPermissionToFinaliseForInvoice) && !in_array("", $hasPermissionToFinaliseForInvoice) ? '1' : '0';
				$orderList->hasClaimValue    = array_filter($orderHasClaimValueOrNot);
				$orderParameterList	     	 = !empty($orderParameterList) ? $models->sortArrayAscOrder(array_values($orderParameterList)) : array();
			}
		}
        //dd($orderList, $orderParameterList);
		//echo '<pre>';print_r($orderParameterList);die;
		return response()->json(['error' => $error, 'message' => $message, 'inactive_analyst_message' => $inactive_analyst_message, 'hasMicrobiologicalEquipment' => $hasMicrobiologicalEquipment, 'hasPermToSaveTestResult' => $hasPermToSaveTestResult, 'hasPermToInvoiceTestResult' => $hasPermToInvoiceTestResult, 'orderList' => $orderList, 'orderParameterList' => $orderParameterList, 'orderTrackRecord' => $orderPerformerRecord]);
	}

	/** scope-2(04-07-2018)
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function viewOrderBySectionIncharge(Request $request, $order_id)
	{

		global $order, $models, $report;

		$error   = '0';
		$message = '';
		$data    = $hasMicrobiologicalEquipment = $hasPermToSaveTestResult = $hasPermToInvoiceTestResult = '';
		$orderParameterList = $disciplineWiseParametersList = $rawTestProductStdParaList = $hasPermissionToSaveTestResult = $categoryWiseParamenter = $hasPermissionToFinaliseForInvoice = $errorParameterIdsArr = $categoryWiseParamenterArr = $orderHasClaimValueOrNot = $nablTestParameterDetail = $descriptionWiseParameterList = $disciplineWiseParameterList = $categoryWiseParameterList = array();

		if ($order_id) {
			$error              					= '1';
			$nablCodeActivationDate					= defined('NABL_CODE_ACTIVATION_DATE') ? trim(NABL_CODE_ACTIVATION_DATE) : '2020-07-15';
			$equipment_type_ids 					= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
			$role_ids           					= defined('ROLE_IDS') ? ROLE_IDS : '0';
			$user_id            					= defined('USERID') ? USERID : '0';
			$orderList              				= $order->getOrder($order_id);
			$orderList->isBackDateBookingAllowed    = !empty($orderList->product_category_id) ? $report->checkBackDateBookingAllowed($orderList) : '0';
			$orderList->testParametersWithSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
			$orderList->assayParametersWithSpace 	= defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
			$orderList->assayParametersWithoutSpace = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ", "", ASSAY_PARAMETERS)) : '';
			$orderList->testParametersWithoutSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ", "", TEST_PARAMETERS)) : '';
			$orderList->orderAmendStatus 			= !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0';
			$orderList->incharge_confirm_status		= $report->currentInchargeOrderStatus($order_id, $user_id); //Confirm button to section incharge if once he approve report
			$orderList->order_nabl_scope_symbol		= $report->hasOrderApplicableForNablScopeAsteriskSymbolInView($order_id);	//Checking Order is applicable for NABL Scope * Symbol
			$orderList->order_nabl_scope			= $report->hasOrderApplicableForNablScope_v1($order_id);			//Checking Order is applicable for NABL Number
			$orderList->order_nabl_os_remark_scope	= $report->getFullyPartialNablOutsourceSampleScope($order_id);			//Getting Fully NABL Scope/Partial NABL Scope/Outsource Sample Scope
			$orderList->customer_hold_message		= !empty($order->isCustomerPutOnHold($orderList->customer_id)) ? config('messages.message.reportApprovingError') : ''; 	//If Customer is put on hold
			$orderList->nabl_report_generation_type	= !empty($report->__validateNablCodeGenerationAppliciability($order_id, $nablCodeActivationDate)) ? '1' : '0'; //Report Generation Type - Older or Newer		
			$errorParameterIdsArr   				= !empty($orderList) ? explode(',', $orderList->error_parameter_ids) : array();
			$testProductStdParaList 				= defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE ? $order->getAsssignedOrderParameterForSectionIncharge($order_id, $equipment_type_ids) : $order->getOrderParameters($order_id);
			$orderPerformerRecord					= $order->getOrderPerformerRecord($order_id);
			$checkReportQuality 					= $report->qualityStampOnWebView($orderList);

			//to formate order and Report date
			$models->formatTimeStamp($orderList, DATETIMEFORMAT);

			if (!empty($testProductStdParaList)) {
				foreach ($testProductStdParaList as $key => $values) {

					if (!empty($errorParameterIdsArr)) {
						if (in_array($values->analysis_id, $errorParameterIdsArr)) {
							$values->errorClass = "errorClass";
						} else {
							$values->errorClass = "";
						}
					}

					//checking if desccription has been edited or not
					$allowedExceptionParameters = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)', 'reference to protocol');
					if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
						if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
							$values->description = $values->test_result;
						}
					}

					//************Assignuing permission to Add the Parameter result*********
					$values->has_employee_equipment_type = '0';
					if (!empty($equipment_type_ids)) {
						if (in_array($values->equipment_type_id, $equipment_type_ids)) {
							$values->has_employee_equipment_type = '1';
						} else {
							$values->has_employee_equipment_type = '0';
						}
					} else if (defined('IS_ADMIN') && IS_ADMIN) { //Admin has all the permission
						$values->has_employee_equipment_type = '1';
					}

					//Checking if enable/disable of save button
					if (!empty($values->has_employee_equipment_type)) {
						$hasPermissionToSaveTestResult[$values->equipment_type_id] = $values->equipment_type_id;
					}

					//Checking if all test result performed or not
					$hasPermissionToFinaliseForInvoice[$values->analysis_id] = $values->test_result;

					$rawTestProductStdParaList[$values->analysis_id]  = $values;
					$orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
					//************/Assignuing permission to Add the Parameter result*********
				}
			}

			//ORDER PARAMETER DATA FOR FOOD & ENVIRONMENT
			if (!empty($orderList->product_category_id) && in_array($orderList->product_category_id, array('1', '3', '6', '7', '8', '308', '405'))) {

				if (!empty($rawTestProductStdParaList)) {
					foreach ($rawTestProductStdParaList as $analysis_id => $values) {

						$models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to); 			//Getting Requirement STD From & STD To
						$report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol); 	//Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
						$orderHasClaimValueOrNot[] = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';

						if (!empty($values->test_parameter_name) && in_array(strtolower(strip_tags($values->test_parameter_name)), $allowedExceptionParameters)) {
							$descriptionWiseParameterList[$values->test_para_cat_id]['categorySortBy']     = $values->category_sort_by;
							$descriptionWiseParameterList[$values->test_para_cat_id]['categoryId']         = $values->test_para_cat_id;
							$descriptionWiseParameterList[$values->test_para_cat_id]['categoryName']       = $values->test_para_cat_name;
							$descriptionWiseParameterList[$values->test_para_cat_id]['productCategoryName'] = str_replace(' ', '', strtolower($values->test_para_cat_name));
							$descriptionWiseParameterList[$values->test_para_cat_id]['categoryNameSymbol'] = $values->non_nabl_category_symbol;
							$descriptionWiseParameterList[$values->test_para_cat_id]['categoryParams'][]   = $values;
						} else if (!empty($values->discipline_id)) {
							$disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['discipline_id']          = $values->discipline_id;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['discipline_name']        = $values->discipline_name;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['group_id']               = $values->group_id;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineHdr']['group_name']             = $values->group_name;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categorySortBy']        = $values->category_sort_by;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryId']            = $values->test_para_cat_id;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryName']          = $values->test_para_cat_name;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryNameSymbol']    = $values->non_nabl_category_symbol;
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['productCategoryName']   = str_replace(' ', '', strtolower($values->test_para_cat_name));
							$disciplineWiseParametersList[$values->discipline_id]['disciplineDtl'][$values->test_para_cat_id]['categoryParams'][]      = $values;
						} else {
							$categoryWiseParameterList[$values->test_para_cat_id]['categorySortBy']       = $values->category_sort_by;
							$categoryWiseParameterList[$values->test_para_cat_id]['categoryId']           = $values->test_para_cat_id;
							$categoryWiseParameterList[$values->test_para_cat_id]['categoryName']         = $values->test_para_cat_name;
							$categoryWiseParameterList[$values->test_para_cat_id]['categoryNameSymbol']   = $values->non_nabl_category_symbol;
							$categoryWiseParameterList[$values->test_para_cat_id]['productCategoryName']  = str_replace(' ', '', strtolower($values->test_para_cat_name));
							$categoryWiseParameterList[$values->test_para_cat_id]['categoryParams'][]     = $values;
						}
					}

					if (!empty($descriptionWiseParameterList)) {
						foreach ($descriptionWiseParameterList as $descriptionWiseParameter) {
							$charNum = 'a';
							foreach ($descriptionWiseParameter['categoryParams'] as $values) {
								$values->charNumber = $charNum++;
							}
						}
					}
					if (!empty($disciplineWiseParametersList)) {
						foreach ($disciplineWiseParametersList as $key => $disciplineWiseParameterListAll) {
							foreach ($disciplineWiseParameterListAll['disciplineDtl'] as $keyLevelOne => $disciplineWiseParameter) {
								$charNum = 'a';
								if (is_array($disciplineWiseParameter) && !empty($disciplineWiseParameter)) {
									foreach ($disciplineWiseParameter['categoryParams'] as $values) {
										$values->charNumber = $charNum++;
									}
								}
							}
						}
					}
					if (!empty($categoryWiseParameterList)) {
						foreach ($categoryWiseParameterList as $categoryWiseParameter) {
							$charNum = 'a';
							foreach ($categoryWiseParameter['categoryParams'] as $values) {
								$values->charNumber = $charNum++;
							}
						}
					}
				}

				$hasMicrobiologicalEquipment   = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
				$hasPermToSaveTestResult       = !empty($hasPermissionToSaveTestResult) ? '1' : '0';
				$hasPermToInvoiceTestResult    = !empty($hasPermissionToFinaliseForInvoice) && !in_array("", $hasPermissionToFinaliseForInvoice) ? '1' : '0';
				$orderList->hasClaimValue      = array_filter($orderHasClaimValueOrNot);
				$descriptionWiseParameterList  = !empty($descriptionWiseParameterList) ? $models->sortArrayAscOrder(array_values($descriptionWiseParameterList)) : array();
				$categoryWiseParameterList     = !empty($categoryWiseParameterList) ? $models->sortArrayAscOrder(array_values($categoryWiseParameterList)) : array();
				$disciplineWiseParametersList  = !empty($disciplineWiseParametersList) ? $models->sortArrayAscOrder(array_values($disciplineWiseParametersList)) : array();
				$orderParameterList	       	   = ['descriptionWiseParameterList' => $descriptionWiseParameterList, 'categoryWiseParameterList' => $categoryWiseParameterList, 'disciplineWiseParametersList' => $disciplineWiseParametersList];
			} else {			//For Other Departments

				if (!empty($rawTestProductStdParaList)) {
					foreach ($rawTestProductStdParaList as $analysis_id => $values) {
						$models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to); 			//Getting Requirement STD From & STD To
						$report->getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderList->order_nabl_scope_symbol); 	//Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
						$orderHasClaimValueOrNot[] = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
						$orderParameterList[$values->test_para_cat_id]['categorySortBy']        = $values->category_sort_by;
						$orderParameterList[$values->test_para_cat_id]['categoryId']            = $values->test_para_cat_id;
						$orderParameterList[$values->test_para_cat_id]['categoryName']          = $values->test_para_cat_name;
						$orderParameterList[$values->test_para_cat_id]['categoryNameSymbol']    = $values->non_nabl_category_symbol;
						$orderParameterList[$values->test_para_cat_id]['productCategoryName']   = str_replace(' ', '', strtolower($values->test_para_cat_name));
						$orderParameterList[$values->test_para_cat_id]['categoryParams'][]      = $values;
					}
					foreach ($orderParameterList as $categoryWiseParameterAll) {
						$charNum = 'a';
						foreach ($categoryWiseParameterAll['categoryParams'] as $values) {
							$values->charNumber = $charNum++;
						}
					}
				}

				$hasMicrobiologicalEquipment = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
				$hasPermToSaveTestResult     = !empty($hasPermissionToSaveTestResult) ? '1' : '0';
				$hasPermToInvoiceTestResult  = !empty($hasPermissionToFinaliseForInvoice) && !in_array("", $hasPermissionToFinaliseForInvoice) ? '1' : '0';
				$orderList->hasClaimValue    = array_filter($orderHasClaimValueOrNot);
				$orderParameterList	     	 = !empty($orderParameterList) ? $models->sortArrayAscOrder(array_values($orderParameterList)) : array();
			}

			//Checking Section Incharge is allocation to this Report
			if (!empty($order_id) && empty($order->getOrderEquipmentInchargeDetail($order_id))) {
				$error = '0';
				$message = config('messages.message.sectionInchargeMissingErrorMsg');
			}
		}

		//echo '<pre>';print_r($orderParameterList);die;
		return response()->json(['error' => $error, 'message' => $message, 'hasMicrobiologicalEquipment' => $hasMicrobiologicalEquipment, 'hasPermToSaveTestResult' => $hasPermToSaveTestResult, 'hasPermToInvoiceTestResult' => $hasPermToInvoiceTestResult, 'orderList' => $orderList, 'orderParameterList' => $orderParameterList, 'orderTrackRecord' => $orderPerformerRecord]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function viewOrderParameterByTester(Request $request, $order_id)
	{

		global $order, $models, $report;

		$error   = '0';
		$message = '';
		$data    = $hasMicrobiologicalEquipment = $hasPermToSaveTestResult = $hasPermToInvoiceTestResult = '';
		$rawTestProductStdParaList = $hasPermissionToSaveTestResult = $categoryWiseParamenter = $hasPermissionToFinaliseForInvoice = $errorParameterIdsArr = $categoryWiseParamenterArr = $orderHasClaimValueOrNot = array();

		if ($order_id) {

			$error              					= '1';
			$equipment_type_ids 					= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
			$role_ids           					= defined('ROLE_IDS') ? ROLE_IDS : '0';
			$user_id            					= defined('USERID') ? USERID : '0';
			$orderList              				= $order->getOrder($order_id);
			$orderList->hasReportAWUISetting 		= !empty($orderList) ? $report->hasApplicableForAnalystUiWindowSetting($order_id) : '0';
			$orderList->isBackDateBookingAllowed 	= !empty($orderList) ? $report->checkBackDateBookingAllowed($orderList) : '0';
			$orderList->testParametersWithSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
			$orderList->assayParametersWithSpace 	= defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
			$orderList->assayParametersWithoutSpace = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ", "", ASSAY_PARAMETERS)) : '';
			$orderList->testParametersWithoutSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ", "", TEST_PARAMETERS)) : '';
			$orderList->orderAmendStatus 			= !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0';
			$errorParameterIdsArr   				= $report->getErrorParametersDetail($order_id);
			$testProductStdParaList 				= defined('IS_TESTER') && IS_TESTER ? $order->getAsssignedOrderParameterForTester($order_id, $user_id) : $order->getOrderParameters($order_id);
			$orderPerformerRecord 					= $order->getOrderPerformerRecord($order_id);

			//to formate order and Report date
			$models->formatTimeStamp($orderList, DATETIMEFORMAT);

			if (!empty($testProductStdParaList)) {
				foreach ($testProductStdParaList as $key => $values) {

					if (!empty($errorParameterIdsArr)) {
						$values->errorClass = "";
						$values->errorMessage = "";
						if (empty($values->test_result) && array_key_exists($values->analysis_id, $errorParameterIdsArr)) {
							$values->errorClass = "errorClass";
							$values->errorMessage = !empty($errorParameterIdsArr[$values->analysis_id]) ? $errorParameterIdsArr[$values->analysis_id] : '';
						}
					}

					//checking if desccription has been edited or not
					$allowedExceptionParameters = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)', 'reference to protocol');
					if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
						if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
							$values->description = $values->test_result;
						}
					}

					//************Assignuing permission to Add the Parameter result*********
					$values->has_employee_equipment_type = '0';
					if (!empty($equipment_type_ids)) {
						if (in_array($values->equipment_type_id, $equipment_type_ids)) {
							$values->has_employee_equipment_type = '1';
						} else {
							$values->has_employee_equipment_type = '0';
						}
					} else if (defined('IS_ADMIN') && IS_ADMIN) { //Admin has all the permission
						$values->has_employee_equipment_type = '1';
					}

					//Checking if enable/disable of save button
					if (!empty($values->has_employee_equipment_type)) {
						$hasPermissionToSaveTestResult[$values->equipment_type_id] = $values->equipment_type_id;
					}
					//Checking if all test result performed or not
					$hasPermissionToFinaliseForInvoice[$values->analysis_id] = $values->test_result;

					$rawTestProductStdParaList[$values->analysis_id]  = $values;
					$orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
					//************/Assignuing permission to Add the Parameter result*********
				}
			}

			if (!empty($rawTestProductStdParaList)) {
				foreach ($rawTestProductStdParaList as $analysis_id => $values) {
					$values->analysis_start_date 	  = !empty($values->analysis_start_date) ? $models->getFormatedDate($values->analysis_start_date, 'd-m-Y') : NULL;
					$values->analysis_completion_date = !empty($values->analysis_completion_date) ? $models->getFormatedDate($values->analysis_completion_date, 'd-m-Y') : NULL;
					$models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to);
					$report->getDetectorColumnInstanceRunningTimeDropdown($values, $orderList);
					$orderHasClaimValueOrNot[] = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
					$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']   		= $values->category_sort_by;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']       		= $values->test_para_cat_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     		= $values->test_para_cat_name;
					$categoryWiseParamenter[$values->test_para_cat_id]['productCategoryName'] 	= str_replace(' ', '', strtolower($values->test_para_cat_name));
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] 		= $values;
				}
				foreach ($categoryWiseParamenter as $categoryWiseParameterAll) {
					$charNum = 'a';
					foreach ($categoryWiseParameterAll['categoryParams'] as $values) {
						$values->charNumber = $charNum++;
					}
				}
			}

			$hasMicrobiologicalEquipment = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
			$hasPermToSaveTestResult     = !empty($hasPermissionToSaveTestResult) ? '1' : '0';
			$hasPermToInvoiceTestResult  = !empty($hasPermissionToFinaliseForInvoice) && !in_array("", $hasPermissionToFinaliseForInvoice) ? 1 : 0;
			$orderList->hasClaimValue	 = array_filter($orderHasClaimValueOrNot);
			$categoryWiseParamenterArr 	 = !empty($categoryWiseParamenter) ? $models->sortArrayAscOrder(array_values($categoryWiseParamenter)) : array();
		}

		//echo '<pre>';print_r($categoryWiseParamenterArr);die;
		return response()->json(['error' => $error, 'message' => $message, 'hasMicrobiologicalEquipment' => $hasMicrobiologicalEquipment, 'hasPermToSaveTestResult' => $hasPermToSaveTestResult, 'hasPermToInvoiceTestResult' => $hasPermToInvoiceTestResult, 'orderList' => $orderList, 'orderParameterList' => $categoryWiseParamenterArr, 'orderTrackRecord' => $orderPerformerRecord]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function saveOrderTestParametersResult(Request $request)
	{

		global $order, $models, $report;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$orderId  = '0';
		$flag     = $formData = $analysisIds = array();

		//Saving record in orders table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Unsetting the variable from request data
			parse_str($request->formData, $formData);

			$formData 					 = $models->unsetFormDataVariables($formData, array('_token'));
			$orderId            		 = !empty($formData['order_id']) ? $formData['order_id'] : '0';
			$test_performed_by  		 = !empty($formData['test_performed_by']) ? $formData['test_performed_by'] : '0';
			$testResultArray    		 = !empty($formData['test_result']) ? array_filter($formData['test_result']) : array();
			$analysisStartDateArray 	 = !empty($formData['analysis_start_date']) ? array_filter($formData['analysis_start_date']) : array();
			$analysisCompletionDateArray = !empty($formData['analysis_completion_date']) ? array_filter($formData['analysis_completion_date']) : array();

			if (empty($testResultArray)) {
				$message = config('messages.message.testParamRequired');
			} elseif (empty($analysisStartDateArray)) {
				$message = config('messages.message.required');
			} elseif (empty($analysisCompletionDateArray)) {
				$message = config('messages.message.required');
			} elseif (!$order->getOrderEquipmentInchargeDetail($orderId)) {
				$message = config('messages.message.sectionInchargeMissingErrorMsg');
			} elseif (empty($test_performed_by)) {
				$message = config('messages.message.error');
			} elseif (isset($formData['oaws_ui_setting_id']) && empty($report->validateNonRequiredFields($formData))) {
				$message = config('messages.message.required');
			} else {
				if (!empty($testResultArray) && !empty($test_performed_by)) {

					//Case if Equipment type and Method IS NOT NULL
					foreach ($testResultArray as $analysis_id_key => $test_result) {
						$analysis_id = trim(str_replace("'", "", $analysis_id_key));
						if (!empty($analysis_id)) {

							$detector_id		 	  = !empty($formData['detector_id'][$analysis_id_key]) ? trim($formData['detector_id'][$analysis_id_key]) : NULL;
							$column_id			 	  = !empty($formData['column_id'][$analysis_id_key]) ? trim($formData['column_id'][$analysis_id_key]) : NULL;
							$running_time_id 	 	  = !empty($formData['running_time_id'][$analysis_id_key]) ? trim($formData['running_time_id'][$analysis_id_key]) : NULL;
							$instance_id 		 	  = !empty($formData['instance_id'][$analysis_id_key]) ? trim($formData['instance_id'][$analysis_id_key]) : NULL;
							$no_of_injection 	 	  = !empty($formData['no_of_injection'][$analysis_id_key]) ? trim($formData['no_of_injection'][$analysis_id_key]) : NULL;
							$analysis_start_date 	  = !empty($formData['analysis_start_date'][$analysis_id_key]) ? $models->getFormatedDateTime(trim($formData['analysis_start_date'][$analysis_id_key]), 'Y-m-d') : NULL;
							$analysis_completion_date = !empty($formData['analysis_completion_date'][$analysis_id_key]) ? $models->getFormatedDateTime(trim($formData['analysis_completion_date'][$analysis_id_key]), 'Y-m-d') : NULL;

							$hasModifiedOrExistData = DB::table('order_parameters_detail')
								->where('order_parameters_detail.analysis_id', $analysis_id)
								->where('order_parameters_detail.detector_id', trim($detector_id))
								->where('order_parameters_detail.running_time_id', trim($running_time_id))
								->where('order_parameters_detail.column_id', trim($column_id))
								->where('order_parameters_detail.instance_id', trim($instance_id))
								->where('order_parameters_detail.no_of_injection', trim($no_of_injection))
								->where('order_parameters_detail.test_result', trim($test_result))
								->first();

							if (empty($hasModifiedOrExistData)) {

								//Saving Analysis ID in Array
								$analysisIds[$analysis_id] = $analysis_id;

								//DB Update Array
								$updateResultArray = [
									'detector_id' 				=> $detector_id,
									'column_id' 				=> $column_id,
									'running_time_id' 			=> $running_time_id,
									'instance_id' 				=> $instance_id,
									'no_of_injection' 			=> $no_of_injection,
									'analysis_start_date' 		=> $analysis_start_date,
									'analysis_completion_date' 	=> $analysis_completion_date,
									'test_performed_by' 		=> trim($test_performed_by),
									'test_result' 				=> trim($test_result)
								];

								$flag[] = DB::table('order_parameters_detail')->where('analysis_id', $analysis_id)->update($updateResultArray);
								$report->updateAnalystJobAssignedStatus($orderId, $analysis_id, $test_performed_by);	//updating the Job Completed Status
							}
						}
					}
				}

				//Checking if all order paramenters test has been performed by Tester if Yes,then Changing the Order Status for Reporting ID:4
				if (!empty($orderId)) {

					//Updating Order Status ID:2(SCHEDULING) record
					$order->updateOrderLog($orderId, '3');

					//Updating Incharge Need Modification Detail
					!empty($analysisIds) ? $report->updateNeedModifInOrderInchargeProcessDtl($orderId, $analysisIds) : '';

					$checkOrderStatusUpdated    = DB::table('order_master')->where('order_master.order_id', $orderId)->where('order_master.status', '3')->first();
					$checkUpdateOrderStatus     = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $orderId)->whereNull('order_parameters_detail.test_result')->first();
					$testCompletionDateStatus   = !empty($checkOrderStatusUpdated) && empty($checkUpdateOrderStatus) ? $order->updateTestCompletionDateTime($orderId, CURRENTDATETIME) : '';
					$moveToNextStageStatus      = !empty($checkOrderStatusUpdated) && empty($checkUpdateOrderStatus) ? $order->updateOrderStatusToNextPhase($orderId, '4') : '';
				}

				$error	 = '1';
				$message = !empty($flag) && in_array('1', $flag) ? config('messages.message.saved') : config('messages.message.savedNoChange');
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $data, 'orderId' => $orderId));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function saveOrderForInvoice($order_id)
	{

		global $order, $models;

		$error       = '0';
		$message     = config('messages.message.error');
		$data        = '';
		$flag        = '0';
		$cureentDate = date('Y-m-d');
		$reportDate  = $order->getFormatedDateTime($cureentDate, $format = 'Y-m-d');
		$formData    = array();

		if (!empty($order_id)) {
			if (DB::table('order_master')->where('order_master.order_id', $order_id)->update(['order_master.status' => '1', 'order_master.report_date' => $reportDate])) {
				$message = config('messages.message.orderMovedForInvoice');
				$error   = 1;
			}
			$orderData   = DB::table('order_master')->where('order_master.order_id', $order_id)->first();
			$division_id = !empty($orderData->division_id) ? $orderData->division_id : '0';
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $data, 'division_id' => $division_id));
	}

	/**
	 * generate final report
	 *
	 * $Request
	 * @return \Illuminate\Http\Response
	 */
	public function saveFinalReport(Request $request)
	{

		global $order, $models;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = array();
		$flag     = '0';
		$formData = array();

		//Saving record in order_Report_Details table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the Serialize Data
			parse_str($request->formData, $formData);
			$formData = array_filter($formData);
			unset($formData['_token']);

			if (!empty($formData['report_id'])) {
				$report_id = DB::table('order_report_details')->where('report_id', '=', $formData['report_id'])->first();
				if (!empty($report_id->order_report_id)) {
					$updated = DB::table('order_report_details')->where('order_report_id', '=', $report_id->order_report_id)->update($formData);
					if ($updated) {
						$message = config('messages.message.reportDetailsUpdated');
						$error   = 1;
					} else {
						$message = config('messages.message.savedNoChange');
						$error   = 1;
					}
				} else {
					if (DB::table('order_report_details')->insert($formData)) {
						$message = config('messages.message.reportDetailsSaved');
						$error   = 1;
					}
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData));
	}

	/**
	 * generate final report pdf
	 *
	 * $Request
	 * @return \Illuminate\Http\Response
	 */
	public function uploadReportPdf(Request $request)
	{

		global $order, $report, $models;

		$report_path 	= DOC_ROOT . REPORT_PATH;
		$error    	= '0';
		$message  	= config('messages.message.error');
		$data     	= array();
		$flag     	= '0';
		$formData 	= array();

		if (!empty($request['report_file'])) {

			$formData = array_filter($request->all());

			if (!empty($formData['report_id']) && !empty($formData['report_file'])) {

				$report_id = $report->getOrderReportDetails($formData['report_id']);

				if (!empty($report_id->order_report_id)) {

					$updated = DB::table('order_report_details')->where('order_report_id', '=', $report_id->order_report_id)->update(['report_file_name' => $formData['report_file_name']]);

					if ($updated) {

						$report_file = $formData['report_file'];

						//generate pdf file in public/images/sales/reports folder
						list($type, $report_file) = explode(';', $report_file);
						list(, $report_file) = explode(',', $report_file);
						$report_file = base64_decode($report_file);

						if (!file_exists($report_path)) {
							mkdir($report_path, 0777, true);
						}
						$pdf = fopen($report_path . $formData['report_file_name'], 'w');
						fwrite($pdf, $report_file);
						fclose($pdf);

						$message = config('messages.message.reportGenerated');
						$error   = 1;
					}
				}
			}
		}

		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData, 'reportPDFurl' => REPORT_PATH . $formData['report_file_name']));
	}

	/**
	 * save report by reporter
	 *
	 * $Request
	 * @return \Illuminate\Http\Response
	 */
	public function saveFinalReportByReports(Request $request, $formtype)
	{

		global $order, $report, $models;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = array();
		$flag     = '0';
		$formData = array();

		//Saving record in order_Report_Details table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the Serialize Data
			parse_str($request->formData, $formData);
			$formData = array_filter($formData);
			unset($formData['_token']);

			//product category section wise save form by reporter,1 for food,2 for pharma
			if (!empty($formData['product_category_id']) && $formData['product_category_id'] == '1' || $formData['product_category_id'] == '4' || $formData['product_category_id'] == '5' || $formData['product_category_id'] == '6' || $formData['product_category_id'] == '7' || $formData['product_category_id'] == '8') {

				unset($formData['analysis_id']);
				unset($formData['product_category_id']);

				if (empty($formData['ref_sample_value'])) {
					$message = config('messages.message.refSampleValueRequired');
				} else if (empty($formData['result_drived_value'])) {
					$message = config('messages.message.resultDrivedValueRequired');
				} else if (empty($formData['deviation_value'])) {
					$message = config('messages.message.deviationValueRequired');
				} else if (empty($formData['remark_value'])) {
					$message = config('messages.message.remarkValue');
				} else {
					if (!empty($formData['report_id'])) {
						$report_id = $report->getOrderReportDetails($formData['report_id']);
						if (!empty($report_id->order_report_id)) {
							DB::table('order_report_details')->where('order_report_id', '=', $report_id->order_report_id)->update($formData);
							$order->updateOrderStausLog($formData['report_id'], '4');
							//update order log an dorder master staus
							if ($formtype == 'confirm') {
								$order->updateOrderStatusToNextPhase($formData['report_id'], '5');
							}
							$message = config('messages.message.reportDetailsUpdated');
							$error   = 1;
						} else {
							if (DB::table('order_report_details')->insert($formData)) {
								$message = config('messages.message.reportDetailsSaved');
								$error   = 1;
							}
						}
					}
				}
			} else if (!empty($formData['product_category_id']) && ($formData['product_category_id'] == '2')) {

				unset($formData['product_category_id']);
				unset($formData['note_by_reviewer']);

				if (empty($formData['report_id'])) {
					$message = config('messages.message.noRecordFound');
				} else if (empty($formData['remark_value'])) {
					$message = config('messages.message.remarkValue');
				} else {
					if (!empty($formData['report_id'])) {
						$report_id = $report->getOrderReportDetails($formData['report_id']);
						if (!empty($report_id->order_report_id)) {
							DB::table('order_report_details')->where('order_report_id', '=', $report_id->order_report_id)->update($formData);
							//update order log an dorder master staus
							if ($formtype == 'confirm') {
								$order->updateOrderStausLog($formData['report_id'], 5);
							}
							$message = config('messages.message.reportDetailsUpdated');
							$error   = 1;
						} else {
							if (DB::table('order_report_details')->insert($formData)) {
								$message = config('messages.message.reportDetailsSaved');
								$error   = 1;
							}
						}
					}
				}
			} else {
				$message = config('messages.message.undefinedProductSection');
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData));
	}

	/**
	 * Description : save report by reviewer
	 * Created By : Praveen Singh
	 * Modified On : 28-March-2019
	 */
	public function saveFinalReportByReviewer(Request $request, $formtype)
	{

		global $order, $report, $models;

		$error    	    = '0';
		$message  	    = config('messages.message.error');
		$data     	    = array();
		$flag     	    = '0';
		$formData 	    = array();
		$orderData 	    = array();
		$currentDateTime    = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
		$allDepartmentIds   = array(1, 3, 4, 6, 7, 8, 81, 148, 308, 405);

		//Saving record in order_Report_Details table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the form Data
			parse_str($request->formData, $formData);

			$orderData 			  			= !empty($formData['sampleDetails']) ? $formData['sampleDetails'] : array();
			$formData['note_by_reviewer']   = !empty($formData['note_by_reviewer']) ? $formData['note_by_reviewer'] : '';
			$amendedStatus 		  			= !empty($formData['amended_status']) ? $formData['amended_status'] : '';
			$productCategoryId 		  		= !empty($formData['product_category_id']) ? $formData['product_category_id'] : '0';
			$isAmendNoSubmitted				= !empty($formData['is_amended_no']) ? true : false;
			$orderParameterData 			= !empty($formData['orderParameterDetail']) ? $formData['orderParameterDetail'] : array();

			//Unsetting Unrequired Variables
			$formData = $models->unsetFormDataVariables($formData, array('_token', 'sampleDetails', 'amended_status'));

			if (!empty($formData['product_category_id'])) {

				if (in_array($formData['product_category_id'], $allDepartmentIds)) {

					//Save the data submitted by All Department except Pharma/Ayurveda
					list($error, $message) = $this->__saveFinalReportByReviewerPartWTPA($flag, $message, $error, $formtype, $currentDateTime, $formData, $orderData, $amendedStatus, $productCategoryId, $isAmendNoSubmitted, $orderParameterData);
				} else if (in_array($formData['product_category_id'], array(2, 5))) {

					//Save the data submitted by Pharma/Ayurveda Only
					list($error, $message) = $this->__saveFinalReportByReviewerPartWPA($flag, $message, $error, $formtype, $currentDateTime, $formData, $orderData, $amendedStatus, $productCategoryId, $isAmendNoSubmitted, $orderParameterData);
				} else {

					//Save the data submitted by All Department except Pharma/Ayurveda
					list($error, $message) = $this->__saveFinalReportByReviewerPartWTPA($flag, $message, $error, $formtype, $currentDateTime, $formData, $orderData, $amendedStatus, $productCategoryId, $isAmendNoSubmitted, $orderParameterData);
				}
			} else {
				$message = config('messages.message.undefinedProductSection');
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData));
	}

	/**
	 * Description : save report by reviewer for All except Pharma and Ayurvedic
	 * Created By : Praveen Singh
	 * Created On : 28-March-2019
	 */
	public function __saveFinalReportByReviewerPartWTPA($flag, $message, $error, $formtype, $currentDateTime, $formData, $orderData, $amendedStatus, $productCategoryId, $isAmendNoSubmitted, $orderParameterData)
	{

		global $order, $report, $models;

		//Unsetting Unrequired Variables
		$formData = $models->unsetFormDataVariables($formData, array('product_category_id'));

		if (empty($formData['report_id'])) {
			$message = config('messages.message.error');
		} else if (empty($formData['ref_sample_value'])) {
			$message = config('messages.message.refSampleValueRequired');
		} else if (!empty($orderData) && empty($orderData['sample_description'])) {
			$message = config('messages.message.sampleDescriptionRequired');
		} else if (!empty($orderData) && empty($orderData['batch_no'])) {
			$message = config('messages.message.batchNoRequired');
		} else if (!empty($orderData) && empty($orderData['packing_mode'])) {
			$message = config('messages.message.packingModeRequired');
		} else if (!empty($orderData) && is_null($orderData['is_sealed'])) {
			$message = config('messages.message.isSealedRequired');
		} else if (!empty($orderData) && is_null($orderData['is_signed'])) {
			$message = config('messages.message.isSignedRequired');
		} else if (!empty($orderData) && empty($orderData['sample_condition'])) {
			$message = config('messages.message.sampleConditionErrorRequired');
		} else if (empty($formData['result_drived_value'])) {
			$message = config('messages.message.resultDrivedValueRequired');
		} else if (empty($formData['deviation_value'])) {
			$message = config('messages.message.deviationValueRequired');
		} else if (empty($formData['remark_value'])) {
			$message = config('messages.message.remarkValue');
		} else if (!empty($order->isOrderStabilityOrder($formData['report_id'])) && empty($formData['stability_remark_value'])) {
			$message = config('messages.message.stabilityRemarkValueRequired');
		} else if (!empty($formData['discipline_id']) && empty($formData['group_id'])) {
			$message = config('messages.message.groupNameRequiredErrorMsg');
		} else if (!empty($formData['discipline_id']) && !empty($formData['group_id']) && count(array_filter($formData['discipline_id'])) != count(array_filter($formData['group_id']))) {
			$message = config('messages.message.groupNameAllRequiredErrorMsg');
		} else {
			if (!empty($formData['report_id'])) {

				//Checking Invoicing Rate of Entered Sample Name
				if (!empty($orderData['sample_description'])) {
					$orderData['test_standard'] 		= !empty($formData['test_standard']) ? $formData['test_standard'] : '';
					$orderData['sample_description_id'] = $report->updateSampleNameAliasByReviewer($formData['report_id'], $orderData['sample_description']);
					$orderData['sample_condition']      = !empty($orderData['sample_condition']) ? $orderData['sample_condition'] : 'NA';
					$orderData 							= $models->unsetFormDataVariables($orderData, array('sample_description'));
					if (!$report->validateInvoivingRateAtReviewer(array('order_id' => $formData['report_id'], 'sample_description_id' => $orderData['sample_description_id']))) {
						$flag = '1';
					}
				}

				//If No error found in Invoicing Rate of the Customer
				if (!$flag) {

					//Saving Group Name in order_discipline_group_dtl table
					if (!empty($formData['discipline_id']) && !empty($formData['group_id'])) {

						//Saving Group Name in order_discipline_group_dtl table
						$report->updateReportGroupNameAsPerDiscipline($formData);

						//Unsetting Unrequired Variables
						$formData = $models->unsetFormDataVariables($formData, array('discipline_id', 'group_id'));
					}

					//if report alredy generated then it will upate the report data otherwise it will generate new report
					$orderDetail  = $order->getOrderDetail($formData['report_id']);
					$reportDetail = $report->getOrderReportDetails($formData['report_id']);

					//Updating Log Status
					$order->updateOrderStausLog($formData['report_id'], '5');

					//Seeting Current As Report Date
					$reportDate = !empty($formData['report_date']) ? $order->getFormatedDateTime($formData['report_date'], $format = 'Y-m-d') : $currentDateTime;

					//orderData when reviewer update report part A
					!empty($orderData) ? DB::table('order_master')->where('order_id', '=', $formData['report_id'])->update($orderData) : '';

					//update parameter details of parameters having no equipment type
					!empty($orderParameterData) ? $report->updateOrderParameterDataByReviewer($orderParameterData) : '';

					//Unsetting Unrequired Variables
					$formData = $models->unsetFormDataVariables($formData, array('test_standard', 'note_by_reviewer', 'report_date', 'back_report_date', 'orderParameterDetail'));

					if (!empty($reportDetail->order_report_id)) {

						//Updating Report Detail Table
						$formData['with_amendment_no'] = !empty($formData['with_amendment_no']) && (strtolower($formData['with_amendment_no']) != 'n/a') ? $formData['with_amendment_no'] : '';
						$updateOrder = DB::table('order_report_details')->where('order_report_id', '=', $reportDetail->order_report_id)->update($formData);

						//Getting is_amended_no Status from Report Detail
						$isAmendNoSaved = $report->getOrderReportDetails($formData['report_id'])->is_amended_no;

						//Saving Header and Footer Content of Test Report
						if ($report->updateSaveTestReportHeaderFooterContent($formData['report_id'])) {
							//update order log an dorder master staus
							if ($formtype == 'confirm' && !empty($reportDate)) {
								$report->updateGenerateReportNumberDate($formData['report_id'], $reportDate);
								$report->updateReportReviewingDate($formData, $reportDate);
								!empty($isAmendNoSubmitted) || !empty($isAmendNoSaved) ? $report->updateReportInchargeReviewingDateOnAmendment($formData['report_id']) : ''; //Update Report Incharge Reviewing Date
								!empty($isAmendNoSubmitted) || !empty($isAmendNoSaved) ? $report->updateGenerateReportNumberDate($formData['report_id'], $reportDate, $reportDate) : '';
								$order->updateOrderStatusToNextPhase($formData['report_id'], '6');
								$error   = 1;
								$message = config('messages.message.reportDetailsUpdated');
							} else {
								$message = config('messages.message.reportDetailsUpdated');
								$error   = 1;
							}
						} else {
							$message = config('messages.message.reportHeaderFooterErrorMsg');
						}
					} else {
						if (DB::table('order_report_details')->insert($formData)) {
							//Saving Header and Footer Content of Test Report
							if ($report->updateSaveTestReportHeaderFooterContent($formData['report_id'])) {
								$message = config('messages.message.reportDetailsSaved');
								$error   = 1;
							} else {
								$message = config('messages.message.reportHeaderFooterErrorMsg');
							}
						}
					}
				} else {
					$error   = '0';
					$message = config('messages.message.InvocingTypeRequired');
				}
			}
		}
		return array($error, $message);
	}

	/**
	 * Description : save report by reviewer for Pharma and Ayurvedic
	 * Created By : Praveen Singh
	 * Created On : 28-March-2019
	 */
	public function __saveFinalReportByReviewerPartWPA($flag, $message, $error, $formtype, $currentDateTime, $formData, $orderData, $amendedStatus, $productCategoryId, $isAmendNoSubmitted, $orderParameterData)
	{

		global $order, $report, $models;

		//Unsetting Unrequired Variables
		$formData = $models->unsetFormDataVariables($formData, array('product_category_id'));

		if (empty($formData['report_id'])) {
			$message = config('messages.message.error');
		} else if (!empty($orderData) && empty($orderData['sample_description'])) {
			$message = config('messages.message.sampleDescriptionRequired');
		} else if (!empty($orderData) && empty($orderData['batch_no'])) {
			$message = config('messages.message.batchNoRequired');
		} else if (isset($formtype) && $formtype == 'confirm' && !empty($formData['back_report_date']) && empty($formData['report_date'])) {
			$message = config('messages.message.reportDateRequiredError');
		} else if (isset($formtype) && $formtype == 'confirm' && !empty($formData['back_report_date']) && !$report->checkOrderDateAndReportDataValidation($formData['report_id'], $formData['report_date'])) {
			$message = config('messages.message.reportDateValidationError');
		} elseif (empty($formData['remark_value'])) {
			$message = config('messages.message.remarkValue');
		} else if (!empty($order->isOrderStabilityOrder($formData['report_id'])) && empty($formData['stability_remark_value'])) {
			$message = config('messages.message.stabilityRemarkValueRequired');
		} else if (!empty($formData['discipline_id']) && empty($formData['group_id'])) {
			$message = config('messages.message.groupNameRequiredErrorMsg');
		} else if (!empty($formData['discipline_id']) && !empty($formData['group_id']) && count(array_filter($formData['discipline_id'])) != count(array_filter($formData['group_id']))) {
			$message = config('messages.message.groupNameAllRequiredErrorMsg');
		} else {
			if (!empty($formData['report_id'])) {

				//Checking Invoicing Rate of Entered Sample Name
				if (!empty($orderData['sample_description'])) {
					$orderData['sample_description_id'] = $report->updateSampleNameAliasByReviewer($formData['report_id'], $orderData['sample_description']);
					$orderData['sample_condition']      = !empty($orderData['sample_condition']) ? $orderData['sample_condition'] : 'NA';
					$orderData = $models->unsetFormDataVariables($orderData, array('sample_description'));
					if (!$report->validateInvoivingRateAtReviewer(array('order_id' => $formData['report_id'], 'sample_description_id' => $orderData['sample_description_id']))) {
						$flag = '1';
					}
				}

				//If No error found in Invoicing Rate of the Customer
				if (!$flag) {

					//Saving Group Name in order_discipline_group_dtl table
					if (!empty($formData['discipline_id']) && !empty($formData['group_id'])) {

						//Saving Group Name in order_discipline_group_dtl table
						$report->updateReportGroupNameAsPerDiscipline($formData);

						//Unsetting Unrequired Variables
						$formData = $models->unsetFormDataVariables($formData, array('discipline_id', 'group_id'));
					}

					//if report alredy generated then it will upate the report data otherwise it will generate new report
					$orderDetail  = $order->getOrderDetail($formData['report_id']);
					$reportDetail = $report->getOrderReportDetails($formData['report_id']);

					//Getting ReportDate From Blade
					$reportDate     = !empty($formData['report_date']) ? $order->getFormatedDateTime($formData['report_date'], $format = 'Y-m-d') : $currentDateTime;
					$backReportDate = isset($formData['back_report_date']) ? $formData['back_report_date'] : '0';

					//Updating Log Status
					$order->updateOrderStausLog($formData['report_id'], '5', $reportDate);

					//orderData when reviewer update report part A
					!empty($orderData) ? DB::table('order_master')->where('order_id', '=', $formData['report_id'])->update($orderData) : '';

					//Update parameter details of parameters having no equipment type
					!empty($orderParameterData) ? $report->updateOrderParameterDataByReviewer($orderParameterData) : '';

					//Unsetting Unrequired Variables
					$formData = $models->unsetFormDataVariables($formData, array('note_by_reviewer', 'report_date', 'back_report_date', 'orderParameterDetail'));

					if (!empty($reportDetail->order_report_id)) {

						//Updating Report Detail Table
						DB::table('order_report_details')->where('order_report_id', '=', $reportDetail->order_report_id)->update($formData);

						//Getting is_amended_no Status from Report Detail
						$isAmendNoSaved = $report->getOrderReportDetails($formData['report_id'])->is_amended_no;

						//Saving Header and Footer Content of Test Report
						if ($report->updateSaveTestReportHeaderFooterContent($formData['report_id'])) {
							//update order log an dorder master staus
							if ($formtype == 'confirm' && !empty($reportDate)) {
								$report->updateGenerateReportNumberDate($formData['report_id'], $reportDate, $backReportDate);
								$report->updateReportReviewingDate($formData, $reportDate);
								!empty($isAmendNoSubmitted) || !empty($isAmendNoSaved) ? $report->updateReportInchargeReviewingDateOnAmendment($formData['report_id']) : ''; 		//Update Report Incharge Reviewing Date
								!empty($isAmendNoSubmitted) || !empty($isAmendNoSaved) ? $report->updateGenerateReportNumberDate($formData['report_id'], $reportDate, $reportDate) : '';
								$order->updateOrderStatusToNextPhase($formData['report_id'], '6');
								$error   = 1;
								$message = config('messages.message.reportDetailsUpdated');
							} else {
								$error   = 1;
								$message = config('messages.message.reportDetailsUpdated');
							}
						} else {
							$message = config('messages.message.reportHeaderFooterErrorMsg');
						}
					} else {
						if (DB::table('order_report_details')->insert($formData)) {
							//Saving Header and Footer Content of Test Report
							if ($report->updateSaveTestReportHeaderFooterContent($formData['report_id'])) {
								$message = config('messages.message.reportDetailsSaved');
								$error   = 1;
							} else {
								$message = config('messages.message.reportHeaderFooterErrorMsg');
							}
						}
					}
				} else {
					$error   = '0';
					$message = config('messages.message.InvocingTypeRequired');
				}
			}
		}
		return array($error, $message);
	}

	/**
	 * update report status and move report to next stage
	 *
	 * $Request
	 * @return \Illuminate\Http\Response
	 */
	public function moveOrderToNextStage(Request $request)
	{

		global $order, $report, $models;

		$error    	 = '0';
		$message  	 = config('messages.message.error');
		$data     	 = array();
		$flag     	 = '0';
		$formData 	 = array();
		$currentDateTime = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');

		//Saving record in order_Report_Details table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the Serialize Data
			parse_str($request->formData, $formData);
			$formData = array_filter($formData);
			unset($formData['_token']);

			//Seeting Current As Report Date
			$reportDate = !empty($formData['report_date']) ? $order->getFormatedDateTime($formData['report_date'], $format = 'Y-m-d') : $currentDateTime;

			if (!empty($formData['order_id'])) {

				$order_id 		= $formData['order_id'];
				$orderData 		= $order->getOrder($order_id);
				$orderStatusFinalizer 	= $formData['order_status'];
				$orderStatusQAAprroval 	= $orderStatusFinalizer + 1;

				//if report alredy generated then it will upate the report data otherwise it will generate new report
				$report_id = $report->getOrderReportDetails($formData['order_id']);
				$updated   = $order->updateOrderStausLog($order_id, $orderStatusFinalizer, $reportDate);

				if ($updated) {
					!empty($orderStatusFinalizer) && $orderStatusFinalizer == '6' ? $report->updateReportFinalizingDate($formData, $reportDate) : '';	//Updating Finalizing Date
					$order->updateOrderStatusToNextPhase($order_id, $orderStatusQAAprroval, $reportDate);
					$message = config('messages.message.reportDetailsUpdated');
					$error   = 1;
				} else {
					$message = config('messages.message.savedNoChange');
					$error   = 1;
				}
			}
		}

		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData));
	}

	/**
	 * need modification in report by QA department and send to reporter (update report status to 5)
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function needReportModification(Request $request)
	{

		global $order, $report, $models;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = array();
		$flag     = '0';
		$formData = array();

		//Saving record in order_Report_Details table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the Serialize Data
			parse_str($request->formData, $formData);
			$formData = array_filter($formData);
			unset($formData['_token']);

			if (!empty($formData['order_id'])) {

				$order_id 	  = $formData['order_id'];
				$orderData 	  = $order->getOrder($order_id);
				$updatedPrevious  = $order->updateOrderStausLog($order_id, $orderData->status);
				$updatedCurrent	  = $order->updateOrderStausLog($order_id, '5');

				if ($updatedCurrent) {
					!empty($formData['note']) ? $order->updateOrderStausLogNote($order_id, $formData['note']) : '';	//Updating Notes
					$message = config('messages.message.reportDetailsUpdated');
					$error   = 1;
				} else {
					$message = config('messages.message.savedNoChange');
					$error   = 1;
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData));
	}

	/**
	 * need modification in report by reporter  and update parameter detail table when need modification
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function needReportModificationByReporter(Request $request)
	{

		global $order, $report, $models;

		$error    		= '0';
		$message  		= config('messages.message.error');
		$data     		= array();
		$flag     		= '0';
		$formData 		= array();
		$analysisArr 		= array();
		$error_parameter_ids 	= null;

		//Saving record in order_Report_Details table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the Serialize Data
			parse_str($request->formData, $formData);
			$formData = array_filter($formData);
			unset($formData['_token']);

			if (!empty($formData['report_id'])) {

				$order_id 	 		= $formData['report_id'];
				$order_status 	 	= '3';
				$analysisArr 	 	= !empty($formData['analysis_id']) ? $formData['analysis_id'] : array();
				$orderData 	 		= $order->getOrder($order_id);
				$updatedPrevious 	= $order->updateOrderStausLog($order_id, $orderData->status);
				$updatedCurrent  	= $order->updateOrderStausLog($order_id, $order_status);
				$updatedNote 	 	= $order->updateOrderStausLogNote($order_id, $formData['note_by_reporter']);
				$updatedErrorTestReport = $order->updateOrderStausLogErrorTestReport($analysisArr, $order_id);

				if ($updatedCurrent && $updatedNote && $updatedErrorTestReport) {
					$message = config('messages.message.reportDetailsUpdated');
					$error   = 1;
				} else {
					$message = config('messages.message.savedNoChange');
					$error   = 1;
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData));
	}

	/* Need modification in report by reviewer
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
	public function needReportModificationByReviewer(Request $request)
	{

		global $order, $report, $models;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = array();
		$flag     = '0';
		$formData = $analysisArr = array();
		$error_parameter_ids = null;

		//Saving record in order_Report_Details table
		if (!empty($request->formData) && $request->isMethod('post')) {

			parse_str($request->formData, $formData);
			$formData = array_filter($formData);
			unset($formData['_token']);

			if (!empty($formData['report_id'])) {

				$order_id 					= $formData['report_id'];
				$order_status 				= '3';
				$analysisArr 				= !empty($formData['analysis_id']) ? $formData['analysis_id'] : array();
				$orderData 					= $order->getOrder($order_id);
				$updatedPrevious			= $order->updateOrderLog($order_id, $orderData->status);
				$updatedCurrent 			= $order->updateOrderStausLog($order_id, $order_status);
				$updatedNote 				= $order->updateOrderStausLogNote($order_id, $formData['note_by_reviewer']);
				$updatedErrorTestReport 	= $order->updateOrderStausLogErrorTestReport($analysisArr, $order_id);
				$resetOrderInchargeDetails 	= $report->resetOrderSectionInchargeDetail($order_id, $analysisArr);

				if ($updatedCurrent && $updatedNote && $updatedErrorTestReport && $resetOrderInchargeDetails) {
					$message = config('messages.message.reportDetailsUpdated');
					$error   = 1;
				} else {
					$message = config('messages.message.savedNoChange');
					$error   = 1;
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData));
	}

	/* Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
	public function getNoteRemarkReportOptions(Request $request)
	{

		global $order, $report, $models;

		$error 	  = '0';
		$message  = config('messages.message.error');
		$data 	  = '';
		$formData = $returnData = array();

		if ($request->isMethod('post') && !empty($request->division_id) && !empty($request->product_category_id)) {
			$orderReportNoteRemarkDefault = DB::table('order_report_note_remark_default')
				->where('order_report_note_remark_default.division_id', '=', $request->division_id)
				->where('order_report_note_remark_default.product_category_id', '=', $request->product_category_id)
				->where('order_report_note_remark_default.remark_status', '1')
				->orderBy('order_report_note_remark_default.remark_id', 'ASC')
				->get();
			if (!empty($orderReportNoteRemarkDefault)) {
				$error 	  = '1';
				$message  = '';
				foreach ($orderReportNoteRemarkDefault as $key => $orderReportNoteRemark) {
					if ($orderReportNoteRemark->product_category_id == '2') {  	//Case 'Pharma'
						if ($orderReportNoteRemark->type == '1') {
							$returnData['Notes'][] = trim($orderReportNoteRemark->remark_name);
						}
						if ($orderReportNoteRemark->type == '2') { 		//Case 'All except Pharma'
							$returnData['Remarks'][] = trim($orderReportNoteRemark->remark_name);
						}
					} else {
						if ($orderReportNoteRemark->type == '2') {
							$returnData['Remarks'][] = trim($orderReportNoteRemark->remark_name);
						}
						$returnData['NoteWithInput'][] = 'NoteWithInput';
					}
				}
				$returnData['stabilityRemarkWithInput'][] = 'stabilityRemarkWithInput';
			}
		}

		return response()->json(array('error' => $error, 'message' => $message, 'returnData' => $returnData));
	}

	/* Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
	public function getDepartmentTestStd(Request $request, $product_category_id)
	{

		global $order, $report, $models;

		$error 	  = '0';
		$message  = config('messages.message.error');
		$data 	  = '';
		$formData = $returnData = array();

		$getAllTestStdAccToDept = Db::table('test_standard')->where('test_standard.product_category_id', '=', $product_category_id)->select('test_standard.test_std_id', 'test_standard.test_std_name')->get();
		if (!empty($getAllTestStdAccToDept)) {
			$error 	= '1';
			$returnData = $getAllTestStdAccToDept;
		}
		return response()->json(array('error' => $error, 'getAllTestStdAccToDept' => $returnData));
	}

	/* Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
	public function amendReport(Request $request, $order_id)
	{

		global $report, $order;

		$error    = '0';
		$message  = '';
		$data     = '';

		if (!empty($order_id)) {
			$orderData = $order->getOrder($order_id);
			$report->updateOrderAmendDetail($order_id, $orderData->status);	//Updating Order Amend Detail
			$order->updateOrderLog($order_id, $orderData->status); 		//Updating Order Current Detail
			$order->updateOrderStatusToNextPhase($order_id, '5');		//Updating Order Status and Its Phase Detail

			$message = config('messages.message.reportDetailsUpdated');
			$error   = 1;
		}
		return response()->json(array('error' => $error, 'message' => $message));
	}

	/* Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
	public function editReport($order_id)
	{

		global $report, $order;

		$error    = '0';
		$message  = '';
		$data     = '';

		if (!empty($order_id)) {
			$editOrder = $order->getOrder($order_id);
			$error    = '1';
		}
		return response()->json(array('error' => $error, 'viewEditReportData' => $editOrder));
	}

	/* Dispatch a order from reporting section
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
	public function dispatchReport(Request $request)
	{

		global $models, $order, $report;

		$error    = '0';
		$message  = '';
		$orderId  = '0';
		$orderNo  = '';

		try {
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing of Form Data
				parse_str($request->formData, $formData);

				//Unsetting the Unrequired fields.
				$formData = $models->unsetFormDataVariables($formData, array('_token'));
				$orderId = !empty($formData['order_id']) ? $formData['order_id'] : '0';
				$dispatchDate = !empty($formData['dispatch_date']) ? $models->get_formatted_date($formData['dispatch_date'], $format = 'Y-m-d') : '0';
				$items = $order->getOrder($orderId);
				$orderNo = !empty($items->order_no) ? $items->order_no : '';

				if (!empty($items->order_id)) {
					$formData['ar_bill_no']    = !empty($formData['ar_bill_no']) ? trim(strtoupper($formData['ar_bill_no'])) : NULL;;
					$formData['dispatch_date'] = $order->getFormatedDateTime($formData['dispatch_date'], $format = 'Y-m-d');
					$formData['dispatch_by']   = USERID;

					//CASE 1:If order is Monthly and Status is greater than > 7
					//CASE 2:If order is Lab/Inter Order and Status is greater than > 7
					//CASE 2:If order is Daily Order and IS amended Order and Status is greater than > 7
					if ($models->canUpdateBookingOrderStatus($items)) {
						$order->updateOrderStausLog($items->order_id, '11');
					} else {
						$order->updateOrderLog($items->order_id, '11');
					}
					if (!empty($items->order_id)) {
						$orderDispatchDtl = DB::table('order_dispatch_dtl')->where('order_dispatch_dtl.order_id', $items->order_id)->where('order_dispatch_dtl.ar_bill_no', $formData['ar_bill_no'])->where(DB::raw("DATE(order_dispatch_dtl.dispatch_date)"), $dispatchDate)->where('order_dispatch_dtl.amend_status', '=', '0')->first();
						if (empty($orderDispatchDtl)) {
							if (DB::table('order_dispatch_dtl')->insertGetId($formData)) {
								$error   = '1';
								$message = config('messages.message.OrderDispatchedMsg');
							} else {
								$message = config('messages.message.savedError');
							}
						} else if (!empty($orderDispatchDtl->dispatch_id)) {
							$message = config('messages.message.existError');
						} else {
							$message = config('messages.message.savedError');
						}
					}
				}
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.error');
		}
		return response()->json(array('error' => $error, 'message' => $message, 'order_id' => $orderId, 'order_no' => $orderNo));
	}

	/********************************************************************
	 * Description : View dispatch detail for reports and invoices
	 * Date        : 13-03-2018
	 * Author      : Pratyush Singh
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ***********************************************************************/
	public function getDispatchDetail(Request $request)
	{

		global $order, $models;

		$error      = '0';
		$orderId    = '0';
		$message    = config('message.message.error');
		$returnData = array();

		try {
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing of Form Data
				parse_str($request->formData, $formData);
				$orderId = !empty($formData['order_id']) ? $formData['order_id'] : '0';

				$returnData = DB::table('order_dispatch_dtl')
					->where('order_dispatch_dtl.order_id', '=', $orderId)
					->join('users', 'order_dispatch_dtl.dispatch_by', 'users.id')
					->join('order_master', 'order_dispatch_dtl.order_id', 'order_master.order_id')
					->select('order_master.order_no', 'order_dispatch_dtl.ar_bill_no', 'order_dispatch_dtl.dispatch_date', 'users.name as dispatched_by')
					->where('order_dispatch_dtl.amend_status', '=', '0')
					->orderBy('order_dispatch_dtl.dispatch_id', 'ASC')
					->get()
					->toArray();

				//to formate Dispatch date and Time
				$models->formatTimeStampFromArray($returnData, DATETIMEFORMAT);
				$error = !empty($returnData) ? '1' : '0';
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.error');
		}

		return response()->json(array('error' => $error, 'message' => $message, 'order_id' => $orderId, 'dispatchDetail' => $returnData));
	}

	/**
	 * save report by reporter
	 *
	 * $Request
	 * @return \Illuminate\Http\Response
	 */
	public function saveFinalReportBySectionIncharge(Request $request, $formtype)
	{

		global $order, $report, $models;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = array();
		$formData = array();
		$user_id  = defined('USERID') ? USERID : '0';

		try {
			//Saving record in order_Report_Details table
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing the Serialize Data
				parse_str($request->formData, $formData);

				if (!empty($formData['product_category_id']) && !empty($formData['report_id'])) {

					$formData['si_user_id'] = defined('IS_ADMIN') && IS_ADMIN ? '0' : $user_id;
					unset($formData['_token']);

					if ($formtype == 'confirm') {

						if ($report->validateConfirmNeedModificationActionBySI($formData)) {

							//Updating Micro Bilological Name in a report
							list($status, $errorMsg) = $report->updateMicroBiologicalName_v1($formData);
							if ($status) {

								//Update order_incharge_dtl status and confirm date
								//0 if order already moved to testing Order Stage
								//1 if atleast any one Section Incharge doesnot confirm the report
								//2 if all Section Incharge confirm the report

								$inchageStatus = $report->updateConfirmStatusOfSectionIncharge($formData, $user_id);
								if (!empty($inchageStatus)) {
									if ($inchageStatus == '1') {
										$order->updateOrderLog($formData['report_id'], '4');	//Updating Log Status
										$error   = '1';
										$message = config('messages.message.reportDetailsUpdated');
									} else {
										//Update Report Incharge Name and Signature
										if (!empty($report->updateTestReportInchargeNameSignature($formData['report_id']))) {
											$report->updateReportInchargeReviewingDate($formData['report_id']); 		//Update Report Incharge Reviewing Date
											$order->updateOrderLog($formData['report_id'], '4');						//Updating Log Status
											$order->updateOrderStatusToNextPhase($formData['report_id'], '5');			//Updating Log Status
											$error   = '1';
											$message = config('messages.message.reportDetailsUpdated');
										} else {
											$error   = '0';
											$message =  config('messages.message.sectionInchargeSignErrorMsg');
										}
									}
								}
							} else {
								$error   = '0';
								$message = $errorMsg;
							}
						} else {
							$message = config('messages.message.actionAlreadyPerformedMsg');
							$error   = '2';
						}
					}
				} else {
					$message = config('messages.message.undefinedProductSection');
				}
			}
		} catch (\Throwable $e) {
			$message = config('messages.message.error');
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData));
	}

	/**
	 * need modification in report by reporter  and update parameter detail table when need modification
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function needReportModificationBySectionIncharge(Request $request)
	{

		global $order, $report, $models;

		$error    		= '0';
		$message  		= config('messages.message.error');
		$data     		= array();
		$flag     		= '0';
		$formData = $newDataArr	= array();
		$analysisArr 		= array();
		$error_parameter_ids 	= null;
		$user_id            	= defined('USERID') ? USERID : '0';

		//Saving record in order_Report_Details table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the Serialize Data
			parse_str($request->formData, $formData);
			$formData = array_filter($formData);
			unset($formData['_token']);

			if (!empty($formData['report_id'])) {
				if ($report->validateConfirmNeedModificationActionBySI($formData)) {

					$order_id 	 		= $formData['report_id'];
					$order_status 	 	= '3';
					$analysisArr 	 	= !empty($formData['analysis_id']) ? array_values($formData['analysis_id']) : array();
					$updatedPrevious 		= $order->updateOrderLog($order_id, '4');
					$updatedCurrent  		= $order->updateOrderStausLog($order_id, $order_status);
					$updatedNote 	 	= $order->updateOrderStausLogNote($order_id, $formData['note_by_reporter']);
					$updatedErrorTestReport 	= $order->updateOrderStausLogErrorTestReport($analysisArr, $order_id);
					$updatedInchargeProcess 	= $report->updateOrderInchargeProcessDetail($formData);

					if ($updatedCurrent && $updatedNote && $updatedErrorTestReport && $updatedInchargeProcess) {
						$message = config('messages.message.reportDetailsUpdated');
						$error   = 1;
					} else {
						$message = config('messages.message.savedNoChange');
						$error   = 1;
					}
				} else {
					$message = config('messages.message.actionAlreadyPerformedMsg');
					$error   = 2;
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $formData));
	}

	/********************************************************************
	 * Description : View dispatch detail for reports and invoices
	 * Date        : 13-03-2018
	 * Author      : Pratyush Singh
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ***********************************************************************/
	public function getOrderSectionInchargeDetail(Request $request)
	{

		global $order, $models;

		$error      = '0';
		$message    = config('message.message.error');
		$returnData = array();

		if (!empty($request->formData)) {

			//Parsing of Form Data
			parse_str($request->formData, $formData);

			$returnData = DB::table('order_incharge_dtl')
				->join('order_master', 'order_master.order_id', 'order_incharge_dtl.order_id')
				->join('users as oidEmployeeDB', 'order_incharge_dtl.oid_employee_id', 'oidEmployeeDB.id')
				->leftJoin('users as oidConfirmDB', 'order_incharge_dtl.oid_confirm_by', 'oidConfirmDB.id')
				->join('equipment_type', 'equipment_type.equipment_id', 'order_incharge_dtl.oid_equipment_type_id')
				->select('order_incharge_dtl.*', 'oidEmployeeDB.name as incharge_name', 'oidConfirmDB.name as incharge_confirm_by', 'equipment_type.equipment_name')
				->where('order_incharge_dtl.order_id', '=', !empty($formData['order_id']) ? $formData['order_id'] : '0')
				->orderBy('equipment_type.equipment_name')
				->get();

			//to formate Dispatch date and Time
			$models->formatTimeStampFromArray($returnData, DATETIMEFORMAT);
			$error = !empty($returnData) ? 1 : 0;
		}

		return response()->json(array('error' => $error, 'message' => $message, 'viewSInchargeData' => $returnData));
	}

	/********************************************************************
	 * Description : View dispatch detail for reports and invoices
	 * Date        : 13-03-2018
	 * Author      : Pratyush Singh
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ***********************************************************************/
	public function getRefreshedOrderSectionInchargeDetail(Request $request)
	{

		global $order, $models;

		$error      = '0';
		$message    = config('message.message.error');
		$returnData = array();

		if (!empty($request->formData)) {

			//Parsing of Form Data
			parse_str($request->formData, $formData);

			$orderId   = !empty($formData['order_id']) ? $formData['order_id'] : '0';
			$orderData = $order->getOrder($orderId);

			//Refreshing the Section Incharge Detail
			!empty($orderData->status) && $orderData->status == '4' ? $order->updateOrderEquipmentInchargeDetail_v1($orderId) : '';

			$returnData = DB::table('order_incharge_dtl')
				->join('order_master', 'order_master.order_id', 'order_incharge_dtl.order_id')
				->join('users as oidEmployeeDB', 'order_incharge_dtl.oid_employee_id', 'oidEmployeeDB.id')
				->leftJoin('users as oidConfirmDB', 'order_incharge_dtl.oid_confirm_by', 'oidConfirmDB.id')
				->join('equipment_type', 'equipment_type.equipment_id', 'order_incharge_dtl.oid_equipment_type_id')
				->select('order_incharge_dtl.*', 'oidEmployeeDB.name as incharge_name', 'oidConfirmDB.name as incharge_confirm_by', 'equipment_type.equipment_name')
				->where('order_incharge_dtl.order_id', '=', $orderId)
				->orderBy('equipment_type.equipment_name')
				->get();

			//to formate Dispatch date and Time
			$models->formatTimeStampFromArray($returnData, DATETIMEFORMAT);
			$error = !empty($returnData) ? 1 : 0;
		}

		return response()->json(array('error' => $error, 'message' => $message, 'viewSInchargeData' => $returnData));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteReport(Request $request, $order_id)
	{

		global $report;

		$error    = '0';
		$message  = '';
		$data     = '';

		try {
			$checkInvoiceProcessing = DB::table('order_master')->where('order_master.order_id', '=', $order_id)->where('order_master.status', '2')->first();
			if (empty($checkInvoiceProcessing) && DB::table('order_master')->where('order_master.order_id', '=', $order_id)->where('order_master.status', '1')->delete()) {
				$report_id = $report->getOrderReportDetails($order_id);
				$error    = '1';
				$message = config('messages.message.reportDeleteMsg');
			} else {
				$message = config('messages.message.reportForeignKeConstFail');
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.reportForeignKeConstFail');
		}

		return response()->json(['error' => $error, 'message' => $message]);
	}
}
