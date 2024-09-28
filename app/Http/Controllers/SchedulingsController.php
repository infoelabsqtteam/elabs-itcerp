<?php

/*****************************************************
 *Schedulings Controller File
 *Created By:Praveen-Singh
 *Created On : 15-Dec-2017
 *Modified On : 10-Oct-2018
 *Package : ITC-ERP-PKL
 ******************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use App\Order;
use App\Scheduling;
use App\SendMail;
use App\AutoCommand;
use Session;
use Validator;
use Route;
use DB;

class SchedulingsController extends Controller
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

		global $order, $models, $scheduling, $mail, $autoCommand;

		$order  	 = new Order();
		$models 	 = new Models();
		$scheduling  = new Scheduling();
		$mail   	 = new SendMail();
		$autoCommand = new AutoCommand();

		//Checking Login
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

		global $order, $models, $scheduling;

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('schedulings.index', ['title' => 'Schedulings', '_schedulings' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getPendingJobs(Request $request)
	{
		global $order, $models, $scheduling;

		$error      = '0';
		$message    = '';
		$data       = '';
		$token_flag = true;
		$filterData = array();

		//Access Permission
		if (!defined('IS_ADMIN') && !defined('IS_JOB_SCHEDULER')) {
			$token_flag = false;
		} else {

			//parsing request data
			parse_str($request->formData, $filterData);

			$user_id             = defined('USERID') ? USERID : '0';
			$department_ids      = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$user_department_ids = defined('USER_DEPARTMENT_IDS') ? USER_DEPARTMENT_IDS : '0';
			$role_ids            = defined('ROLE_IDS') ? ROLE_IDS : '0';
			$equipment_type_ids  = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
			$currentDate	 	 = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
			$division_id	 	 = !empty($filterData['division_id']) ? $filterData['division_id'] : '0';
			$department_ids      = !empty($filterData['product_category_id']) ? array($filterData['product_category_id']) : $department_ids;

			$pendingJobObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_parameters_detail', 'order_parameters_detail.analysis_id', 'schedulings.order_parameter_id')
				->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
				->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_dtl.test_id')
				->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
				->leftJoin('equipment_type', 'equipment_type.equipment_id', 'schedulings.equipment_type_id')
				->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
				->leftJoin('users as employee', 'employee.id', 'schedulings.employee_id')
				->select('schedulings.scheduling_id', 'schedulings.order_parameter_id', 'schedulings.employee_id', 'schedulings.tentative_date', 'schedulings.tentative_time', 'schedulings.product_category_id', 'schedulings.equipment_type_id', 'schedulings.scheduled_at', 'schedulings.completed_at', 'order_master.order_id', 'order_master.division_id', 'order_master.order_no', 'order_master.sample_description_id', 'order_master.order_date', 'order_master.expected_due_date', 'order_parameters_detail.dept_due_date', 'order_parameters_detail.report_due_date', 'order_sample_priority.sample_priority_name as sample_priority', 'product_master_alias.c_product_name as sample_description', 'order_parameters_detail.analysis_id', 'order_parameters_detail.product_test_parameter', 'order_parameters_detail.method_id', 'order_parameters_detail.test_performed_by', 'product_test_dtl.description', 'test_parameter.test_parameter_id', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'equipment_type.equipment_capacity', 'method_master.method_name', 'employee.name as employee_name');

			//cancelled orders not visisble any where
			$pendingJobObj->whereNotIn('order_master.status', array('10', '12'));

			//Filtering records according to department assigned
			if (!empty($department_ids) && is_array($department_ids)) {
				$pendingJobObj->whereIn('order_master.product_category_id', $department_ids);
			}
			//Filtering records according to division assigned
			if (!empty($division_id) && is_numeric($division_id)) {
				$pendingJobObj->where('order_master.division_id', $division_id);
			}
			//if filter form submitted
			if (!empty($filterData['equipment_type_id'])) {
				$pendingJobObj->where('schedulings.equipment_type_id', '=', $filterData['equipment_type_id']);
			}
			if (!empty($filterData['status'])) {
				$pendingJobObj->where('schedulings.status', '=', $filterData['status']);
			} else {
				//If logged in User is Tester ID:5
				if (defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER) {
					$pendingJobObj->whereIn('schedulings.status', array('0'));
				} else {
					$pendingJobObj->whereIn('schedulings.status', array('0'));
				}
			}
			if (!empty($filterData['employee_id'])) {
				$pendingJobObj->where('schedulings.employee_id', '=', $filterData['employee_id']);
			}

			//If Unhold Order Selected
			if (!empty($filterData['unhold_order_id'])) {
				$pendingJobObj->where('schedulings.order_id', '=', $filterData['unhold_order_id']);
			} else {
				//if filter form submitted
				if (!empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
					$pendingJobObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($filterData['order_date_from'], $filterData['order_date_to']));
				} else if (!empty($filterData['order_date_from']) && empty($filterData['order_date_to'])) {
					$pendingJobObj->where(DB::raw("DATE(order_master.order_date)"), '>=', $filterData['order_date_from']);
				} else if (empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
					$pendingJobObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $filterData['order_date_to']);
				} else if (empty($filterData)) {
					$pendingJobObj->where(DB::raw("DATE(order_master.order_date)"), $currentDate);
				}
			}

			$pendingJobObj->orderBy('order_master.order_date', 'DESC');
			$pendingJobData = $pendingJobObj->get()->toArray();

			//to formate created and updated date		   
			$models->formatTimeStampFromArray($pendingJobData, DATEFORMAT, false);

			//assigning analyst to the assigned parameters
			//$scheduling->assignedAnalystToAssignedPendingJob($pendingJobData,$division_id,$user_department_ids);
			$scheduling->assignedAnalystToAssignedPendingJob_v1($pendingJobData);

			//calculating equipment Pendency Count
			$scheduling->getAllEquipmentPendency($pendingJobData);
		}

		//Setting permission Data
		$pendingJobData = $token_flag ? $pendingJobData : array();

		//echo '<pre>';print_r($pendingJobData);die;
		return response()->json(array('error' => $error, 'message' => $message, 'pendingJobData' => $pendingJobData));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generateAnalystSheetDocuments(Request $request)
	{

		global $order, $models, $scheduling;

		$error      = '0';
		$message    = '';
		$data       = '';
		$filterData = array();

		//parsing request data
		if ($request->isMethod('post') && !empty($request->generate_analyst_sheet_documents)) {

			$filterData 	 = $request->all();
			$user_id             = defined('USERID') ? USERID : '0';
			$department_ids      = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$user_department_ids = defined('USER_DEPARTMENT_IDS') ? USER_DEPARTMENT_IDS : '0';
			$role_ids            = defined('ROLE_IDS') ? ROLE_IDS : '0';
			$equipment_type_ids  = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
			$currentDate	 = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
			$division_id         = !empty($filterData['division_id']) ? $filterData['division_id'] : '0';
			$department_ids      = !empty($filterData['product_category_id']) ? array($filterData['product_category_id']) : $department_ids;

			$pendingJobObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->leftJoin('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_parameters_detail', 'order_parameters_detail.analysis_id', 'schedulings.order_parameter_id')
				->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
				->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_dtl.test_id')
				->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
				->leftJoin('equipment_type', 'equipment_type.equipment_id', 'schedulings.equipment_type_id')
				->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
				->leftJoin('users as employee', 'employee.id', 'schedulings.employee_id')
				->select('order_master.order_id', 'order_master.division_id', 'schedulings.product_category_id', 'schedulings.equipment_type_id', 'order_master.order_date as booking_date', 'product_master_alias.c_product_name as sample_description as sample_name', 'order_master.order_no as report_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'equipment_type.equipment_capacity', 'equipment_type.equipment_capacity as equipment_pendency', 'method_master.method_name', 'order_parameters_detail.dept_due_date', 'order_sample_priority.sample_priority_name as sample_priority', 'schedulings.scheduled_at', 'schedulings.completed_at', 'schedulings.tentative_date', 'schedulings.tentative_time', 'order_master.barcode', 'employee.name as analyst_name');

			//cancelled orders not visisble any where
			$pendingJobObj->whereNotIn('order_master.status', array('10', '12'));

			//Filtering records according to department assigned
			if (!empty($department_ids) && is_array($department_ids)) {
				$pendingJobObj->whereIn('order_master.product_category_id', $department_ids);
			}

			//Filtering records according to division assigned
			if (!empty($division_id) && is_numeric($division_id)) {
				$pendingJobObj->where('order_master.division_id', $division_id);
			}

			//If logged in User is Tester ID:5
			if (defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER) {
				$pendingJobObj->whereIn('schedulings.status', array('0', '1'));
			}

			//if filter form submitted
			if (!empty($filterData['equipment_type_id'])) {
				$pendingJobObj->where('schedulings.equipment_type_id', '=', $filterData['equipment_type_id']);
			}
			if (isset($filterData['status'])) {
				if ($filterData['status'] != null) {
					$pendingJobObj->where('schedulings.status', '=', $filterData['status']);
				} else {
					$pendingJobObj->whereIn('schedulings.status', array('0', '1'));
				}
			} else {
				$pendingJobObj->whereIn('schedulings.status', array('0'));
			}

			if (!empty($filterData['employee_id'])) {
				$pendingJobObj->where('schedulings.employee_id', '=', $filterData['employee_id']);
				$analystData 	= DB::table('users')->select('users.id', 'users.name')->where('users.id', $filterData['employee_id'])->first();
				$headingName 	= !empty($analystData->name) ? $analystData->name : '';
			}
			if (!empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
				$pendingJobObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($filterData['order_date_from'], $filterData['order_date_to']));
			} else if (!empty($filterData['order_date_from']) && empty($filterData['order_date_to'])) {
				$pendingJobObj->where(DB::raw("DATE(order_master.order_date)"), '>=', $filterData['order_date_from']);
			} else if (empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
				$pendingJobObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $filterData['order_date_to']);
			} else if (empty($filterData)) {
				$pendingJobObj->where(DB::raw("DATE(order_master.order_date)"), $currentDate);
			}

			//scheduling_id			
			$pendingJobObj->orderBy('order_master.order_date', 'ASC');
			$pendingJobData = $pendingJobObj->get();

			//to formate created and updated date		   
			$models->formatTimeStampFromArrayExcel($pendingJobData, DATEFORMATEXCEL);

			//calculating equipment Pendency Count
			$scheduling->getAllEquipmentPendency($pendingJobData);

			if (!empty($pendingJobData)) {
				$pendingJobData 		= !empty($pendingJobData) ? json_decode(json_encode($pendingJobData), true) : array();
				$pendingJobData 		= $models->unsetFormDataVariablesArray($pendingJobData, array('division_id', 'product_category_id', 'equipment_type_id', 'order_id', 'canDispatchOrder', 'equipment_capacity', 'equipment_pendency'));
				$response['tableHead'] 		= !empty($pendingJobData) ? array_keys(end($pendingJobData)) : array();
				$response['tableBody'] 		= !empty($pendingJobData) ? $pendingJobData : array();
				$response['tablefoot']		= array();
				if ($filterData['generate_analyst_sheet_documents'] == 'PDF') {
					$pdfHeaderContent  		= $models->getHeaderFooterTemplate();
					$response['header_content']	= $pdfHeaderContent->header_content;
					$response['footer_content']	= $pdfHeaderContent->footer_content;
					$response['heading']	= !empty($headingName) ? $headingName : 'All';
					return $models->downloadPDF($response, $contentType = 'analystSheet');
				} else if ($filterData['generate_analyst_sheet_documents'] == 'Excel') {
					$response['mis_report_name']  	= 'analyst_sheet_documents';
					$response['heading'] 		= 'Analyst Sheet Documents' . '(' . count($pendingJobData) . ')';
					return $models->generateExcel($response);
				}
			} else {
				Session::put('errorMsg', config('messages.message.noRecordFound'));
				return redirect('dashboard');
			}
		}

		//echo '<pre>';print_r($pendingJobData);die;
		Session::put('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		return redirect('dashboard');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generateAnalystSheetPdf(Request $request)
	{

		global $order, $models, $scheduling;

		$error      = '0';
		$message    = '';
		$data       = '';
		$filterData = array();

		//parsing request data
		if ($request->isMethod('post') && !empty($request->generate_analyst_pdf)) {

			$filterData 	 = $request->all();
			$user_id             = defined('USERID') ? USERID : '0';
			$department_ids      = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$user_department_ids = defined('USER_DEPARTMENT_IDS') ? USER_DEPARTMENT_IDS : '0';
			$role_ids            = defined('ROLE_IDS') ? ROLE_IDS : '0';
			$equipment_type_ids  = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
			$currentDate	 = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
			$division_id         = !empty($filterData['division_id']) ? $filterData['division_id'] : '0';
			$department_ids      = !empty($filterData['product_category_id']) ? array($filterData['product_category_id']) : $department_ids;

			$pendingJobObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->leftJoin('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_parameters_detail', 'order_parameters_detail.analysis_id', 'schedulings.order_parameter_id')
				->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
				->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_dtl.test_id')
				->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
				->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
				->leftJoin('equipment_type', 'equipment_type.equipment_id', 'schedulings.equipment_type_id')
				->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
				->leftJoin('users as employee', 'employee.id', 'schedulings.employee_id')
				->select('product_master_alias.c_product_name as sample_description', 'schedulings.scheduling_id', 'schedulings.order_parameter_id', 'schedulings.employee_id', 'schedulings.tentative_date', 'schedulings.tentative_time', 'schedulings.product_category_id', 'order_master.order_id', 'order_master.order_no', 'order_master.sample_description_id', 'order_master.order_date', 'order_master.expected_due_date', 'order_master.barcode', 'order_parameters_detail.analysis_id', 'order_parameters_detail.product_test_parameter', 'order_parameters_detail.equipment_type_id', 'order_parameters_detail.method_id', 'test_parameter.test_parameter_id', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'employee.name as employee_name', 'order_parameters_detail.test_performed_by', 'order_sample_priority.sample_priority_name as sample_priority');

			//cancelled orders not visisble any where
			$pendingJobObj->whereNotIn('order_master.status', array('10', '12'));

			//Filtering records according to department assigned
			if (!empty($department_ids) && is_array($department_ids)) {
				$pendingJobObj->whereIn('order_master.product_category_id', $department_ids);
			}

			//Filtering records according to division assigned
			if (!empty($division_id) && is_numeric($division_id)) {
				$pendingJobObj->where('order_master.division_id', $division_id);
			}

			//If logged in User is Tester ID:5
			if (defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER) {
				$pendingJobObj->whereIn('schedulings.status', array('0', '1'));
			}

			//if filter form submitted
			if (!empty($filterData['equipment_type_id'])) {
				$pendingJobObj->where('schedulings.equipment_type_id', '=', $filterData['equipment_type_id']);
			}
			if (isset($filterData['status'])) {
				if ($filterData['status'] != null) {
					$pendingJobObj->where('schedulings.status', '=', $filterData['status']);
				} else {
					$pendingJobObj->whereIn('schedulings.status', array('0', '1'));
				}
			} else {
				$pendingJobObj->whereIn('schedulings.status', array('0'));
			}

			if (!empty($filterData['employee_id'])) {
				$pendingJobObj->where('schedulings.employee_id', '=', $filterData['employee_id']);
				$analystData 	       = DB::table('users')->select('users.id', 'users.name')->where('users.id', $filterData['employee_id'])->first();
				$returnData['heading'] = !empty($analystData->name) ? $analystData->name : '';
			}
			if (!empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
				$pendingJobObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($filterData['order_date_from'], $filterData['order_date_to']));
			} else if (!empty($filterData['order_date_from']) && empty($filterData['order_date_to'])) {
				$pendingJobObj->where(DB::raw("DATE(order_master.order_date)"), '>=', $filterData['order_date_from']);
			} else if (empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
				$pendingJobObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $filterData['order_date_to']);
			} else if (empty($filterData)) {
				$pendingJobObj->where(DB::raw("DATE(order_master.order_date)"), $currentDate);
			}

			//scheduling_id			
			$pendingJobObj->orderBy('order_master.order_date', 'ASC');
			$pendingJobData = $pendingJobObj->get();

			//to formate created and updated date		   
			$models->formatTimeStampFromArrayExcel($pendingJobData, DATEFORMATEXCEL);

			//assigning analyst to the assigned parameters
			//$scheduling->assignedAnalystToAssignedPendingJob($pendingJobData,$division_id,$user_department_ids);

			if (!empty($pendingJobData)) {
				$returnData['data'] = $pendingJobData;
				return $models->downloadPDF($returnData, $contentType = 'analystSheet');
			} else {
				Session::put('errorMsg', config('messages.message.noRecordFound'));
				return redirect('dashboard');
			}
		}

		//echo '<pre>';print_r($pendingJobData);die;
		Session::put('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		return redirect('dashboard');
	}

	/**
	 * Update Scheduling Jobs
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function updateSchedulingJobs(Request $request)
	{

		global $order, $models, $scheduling, $mail, $autoCommand;

		$error    	= '0';
		$message  	= config('messages.message.updatedError');
		$data     	= '';
		$currentDate 	= defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
		$formData 	= $schedulingData = $dataUpdate = $orderIdStatusExist = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			parse_str($request->formData, $formData);
			$formData = array_filter($formData);

			//to formate created and updated date
			$schedulingData = $scheduling->formatSchedulingJobData($formData);

			if (!empty($schedulingData)) {
				foreach ($schedulingData as $key => $job) {
					if (!empty($job['saveAll'])) {

						$schedulingId    	= !empty($job['scheduling_id']) ? $job['scheduling_id'] : '0';
						$orderId         	= !empty($job['order_id']) ? $job['order_id'] : '0';
						$equipmentTypeId        = !empty($job['equipment_type_id']) ? $job['equipment_type_id'] : '0';
						$updateSaveAllFields    = array('tentative_date' => $job['tentative_date'], 'tentative_time' => $job['tentative_time'], 'employee_id' => $job['employee_id'], 'scheduled_at' => $currentDate, 'scheduled_by' => USERID, 'status' => '1');
						$orderDate       	= date('Y-m-d', strtotime($order->getOrderDate($orderId)));
						$currentSchedulingData  = DB::table('schedulings')->where('schedulings.scheduling_id', $schedulingId)->first();

						if (!empty($orderDate) && strtotime($job['tentative_date']) < strtotime($orderDate)) {
							$error   = '0';
							$message = config('messages.message.InvalidTentativeDate');
							return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
						} else {
							//Updating Main Thread of Scheduling
							if (!empty($updateSaveAllFields) && DB::table('schedulings')->where('schedulings.scheduling_id', $schedulingId)->where('schedulings.equipment_type_id', $equipmentTypeId)->update($updateSaveAllFields)) {
								$order->updateOrderStausLog($orderId, '2');								//Updating the Order Log Status
								$scheduling->__updateParameterHavingEquipmentTypeNull($equipmentTypeId, $orderId, $updateSaveAllFields);  //Updating the Parameter having NULL Value mainly Description Parameter
								$scheduling->__updateOrderStatusStageAndSendMail($orderId); 						//Updating the Order Log Status and Sending Mail Notification
								$error = '1';
							}
							//Updating All Thread of Scheduling
							if (!empty($job['schedulingAll'])) {
								foreach ($job['schedulingAll'] as $key => $schedulingJobs) {
									//Updating Scheduling records AND Order Status ID:2(SCHEDULING) record
									if (!empty($updateSaveAllFields) && DB::table('schedulings')->where('schedulings.scheduling_id', $schedulingJobs['scheduling_id'])->where('schedulings.equipment_type_id', $schedulingJobs['equipment_type_id'])->update($updateSaveAllFields)) {
										$order->updateOrderStausLog($schedulingJobs['order_id'], '2');			//Updating the Order Log Status
										$scheduling->__updateParameterHavingEquipmentTypeNull($schedulingJobs['equipment_type_id'], $schedulingJobs['order_id'], $updateSaveAllFields);		//Updating the Parameter having NULL Value mainly Description Parameter
										$scheduling->__updateOrderStatusStageAndSendMail($schedulingJobs['order_id']);     //Updating the Order Log Status and Sending Mail Notification
										$error = '1';
									}
								}
							}
						}
					} else {
						//Updating Single Thread of Scheduling
						if (!empty($job) && is_array($job)) {
							foreach ($job as $key => $schedulingJob) {
								$orderDate = date('Y-m-d', strtotime($order->getOrderDate($schedulingJob['order_id'])));
								if (!empty($orderDate) && strtotime($schedulingJob['tentative_date']) < strtotime($orderDate)) {
									$error   = '0';
									$message = config('messages.message.InvalidTentativeDate');
									return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
								} else {
									//Updating Scheduling records AND Order Status ID:2(SCHEDULING) record
									$updateSaveFields = array('tentative_date' => $schedulingJob['tentative_date'], 'tentative_time' => $schedulingJob['tentative_time'], 'employee_id' => $schedulingJob['employee_id'], 'scheduled_at' => $currentDate, 'scheduled_by' => USERID, 'status' => '1');
									if (!empty($schedulingJob['equipment_type_id'])) {
										if (!empty($updateSaveFields) && DB::table('schedulings')->where('schedulings.scheduling_id', $schedulingJob['scheduling_id'])->where('schedulings.equipment_type_id', $schedulingJob['equipment_type_id'])->update($updateSaveFields)) {
											$order->updateOrderStausLog($schedulingJob['order_id'], '2');											//Updating the Order Log Status
											$scheduling->__updateParameterHavingEquipmentTypeNull($schedulingJob['equipment_type_id'], $schedulingJob['order_id'], $updateSaveFields);		//Updating the Parameter having NULL Value mainly Description Parameter
											$scheduling->__updateOrderStatusStageAndSendMail($schedulingJob['order_id']);     									//Updating the Order Log Status and Sending Mail Notification
											$error = '1';
										}
									} else {
										if (!empty($updateSaveFields) && DB::table('schedulings')->where('schedulings.scheduling_id', $schedulingJob['scheduling_id'])->update($updateSaveFields)) {
											$order->updateOrderStausLog($schedulingJob['order_id'], '2');											//Updating the Order Log Status
											$scheduling->__updateOrderStatusStageAndSendMail($schedulingJob['order_id']);     									//Updating the Order Log Status and Sending Mail Notification
											$error = '1';
										}
									}
								}
							}
						}
					}
				}
			}

			$message = $error ? config('messages.message.updated') : config('messages.message.updatedError');
			return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function jobs()
	{

		global $order, $models, $scheduling;

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('schedulings.jobs', ['title' => 'Jobs', '_jobs' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getAssignedJobs(Request $request)
	{

		global $order, $models, $scheduling;

		$error      = '0';
		$message    = '';
		$data       = '';
		$filterData = array();

		//Parsing Posted data
		parse_str($request->formData, $filterData);

		$user_id            	= defined('USERID') ? USERID : '0';
		$department_ids     	= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           	= defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids 	= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		$currentDate	    	= defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
		$department_ids      	= !empty($filterData['product_category_id']) ? array($filterData['product_category_id']) : $department_ids;
		$sampleWiseDisplay      = !empty($filterData['schedule_type']) && $filterData['schedule_type'] == '1' ? true : false;
		$orderBy  		= '0';

		$assignedJobObj = DB::table('schedulings')
			->join('order_master', 'order_master.order_id', 'schedulings.order_id')
			->join('product_categories', 'product_categories.p_category_id', 'schedulings.product_category_id')
			->leftJoin('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
			->join('order_parameters_detail', 'order_parameters_detail.analysis_id', 'schedulings.order_parameter_id')
			->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
			->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_dtl.test_id')
			->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
			->leftJoin('equipment_type', 'equipment_type.equipment_id', 'schedulings.equipment_type_id')
			->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
			->leftJoin('users as employee', 'employee.id', 'schedulings.employee_id')
			->orwhere('schedulings.employee_id', '<>', NULL);

		//cancelled orders not visisble any where	
		$assignedJobObj->whereNotIn('order_master.status', array('10', '12'));

		//Filtering records according to department assigned
		if (!empty($department_ids) && is_array($department_ids)) {
			$assignedJobObj->whereIn('order_master.product_category_id', $department_ids);
		}
		//Filtering records according to division assigned
		if (!empty($filterData['division_id']) && is_numeric($filterData['division_id'])) {
			$assignedJobObj->where('order_master.division_id', $filterData['division_id']);
		}
		//if filter form submitted	  
		if (!empty($filterData['equipment_type_id'])) {
			$assignedJobObj->where('schedulings.equipment_type_id', '=', $filterData['equipment_type_id']);
		}
		//Filter data by Status
		if (!empty($filterData['status'])) {
			$assignedJobObj->where('schedulings.status', '=', $filterData['status']);
		}
		//Filter data by Employee Name
		if (defined('IS_TESTER') && IS_TESTER) {
			$assignedJobObj->where('schedulings.employee_id', '=', $user_id);
		} else if (!empty($filterData['employee_id'])) {
			$assignedJobObj->where('schedulings.employee_id', '=', $filterData['employee_id']);
		}
		if (!empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
			$assignedJobObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($filterData['order_date_from'], $filterData['order_date_to']));
		} else if (!empty($filterData['order_date_from']) && empty($filterData['order_date_to'])) {
			$assignedJobObj->where(DB::raw("DATE(order_master.order_date)"), '>=', $filterData['order_date_from']);
		} else if (empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
			$assignedJobObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $filterData['order_date_to']);
		} else if (empty($filterData)) {
			$assignedJobObj->where(DB::raw("DATE(order_master.order_date)"), $currentDate);
		}
		//Filtering records according to expected due date from and expected due date to 
		if (!empty($filterData['expected_due_date_from']) && !empty($filterData['expected_due_date_to'])) {
			$assignedJobObj->whereBetween(DB::raw("DATE(order_master.expected_due_date)"), array($filterData['expected_due_date_from'], $filterData['expected_due_date_to']));
		} else if (!empty($filterData['expected_due_date_from']) && empty($filterData['expected_due_date_to'])) {
			$assignedJobObj->where(DB::raw("DATE(order_master.expected_due_date)"), '>=', $filterData['expected_due_date_from']);
		} else if (empty($filterData['expected_due_date_from']) && !empty($filterData['expected_due_date_to'])) {
			$assignedJobObj->where(DB::raw("DATE(order_master.expected_due_date)"), '<=', $filterData['expected_due_date_to']);
		}
		//Filtering records according to Scheduled date from and Scheduled date to 
		if (!empty($filterData['scheduled_date_from']) && !empty($filterData['scheduled_date_to'])) {
			$assignedJobObj->whereBetween(DB::raw("DATE(schedulings.scheduled_at)"), array($filterData['scheduled_date_from'], $filterData['scheduled_date_to']));
		} else if (!empty($filterData['scheduled_date_from']) && empty($filterData['scheduled_date_to'])) {
			$assignedJobObj->where(DB::raw("DATE(schedulings.scheduled_at)"), '>=', $filterData['scheduled_date_from']);
		} else if (empty($filterData['scheduled_date_from']) && !empty($filterData['scheduled_date_to'])) {
			$assignedJobObj->where(DB::raw("DATE(schedulings.scheduled_at)"), '<=', $filterData['scheduled_date_to']);
		}
		//Filtering records according to Completed date from and Completed date to
		$completionDateColumn = !empty($sampleWiseDisplay) ? 'order_master.test_completion_date' : 'schedulings.completed_at';
		if (!empty($filterData['completed_date_from']) && !empty($filterData['completed_date_to'])) {
			$assignedJobObj->whereBetween(DB::raw("DATE($completionDateColumn)"), array($filterData['completed_date_from'], $filterData['completed_date_to']));
		} else if (!empty($filterData['completed_date_from']) && empty($filterData['completed_date_to'])) {
			$assignedJobObj->where(DB::raw("DATE($completionDateColumn)"), '>=', $filterData['completed_date_from']);
		} else if (empty($filterData['completed_date_from']) && !empty($filterData['completed_date_to'])) {
			$assignedJobObj->where(DB::raw("DATE($completionDateColumn)"), '<=', $filterData['completed_date_to']);
		}
		if (!empty($sampleWiseDisplay)) {
			$assignedJobObj->select('order_master.order_id', 'order_master.order_no', 'order_master.sample_description_id', 'product_master_alias.c_product_name as sample_name', 'product_categories.p_category_name as department_name', 'order_master.order_date', 'order_master.expected_due_date', 'order_master.order_dept_due_date as dept_due_date', 'order_master.order_report_due_date as report_due_date', 'order_parameters_detail.analysis_id', 'order_parameters_detail.product_test_parameter', 'order_parameters_detail.equipment_type_id', 'order_parameters_detail.method_id', 'test_parameter.test_parameter_id', 'schedulings.scheduling_id', 'schedulings.order_parameter_id', 'schedulings.employee_id', 'schedulings.tentative_date', 'schedulings.tentative_time', 'schedulings.status as scheduling_status', 'schedulings.notes', 'employee.name as employee_name', 'order_parameters_detail.test_performed_by', 'order_master.test_completion_date as completed_at');
		} else {
			$assignedJobObj->select('order_master.order_id', 'order_master.order_no', 'order_master.sample_description_id', 'product_master_alias.c_product_name as sample_name', 'product_categories.p_category_name as department_name', 'order_master.order_date', 'order_master.expected_due_date', 'order_parameters_detail.dept_due_date', 'order_parameters_detail.report_due_date', 'order_parameters_detail.analysis_id', 'order_parameters_detail.product_test_parameter', 'order_parameters_detail.equipment_type_id', 'order_parameters_detail.method_id', 'test_parameter.test_parameter_id', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'schedulings.scheduling_id', 'schedulings.order_parameter_id', 'schedulings.employee_id', 'schedulings.tentative_date', 'schedulings.tentative_time', 'schedulings.status as scheduling_status', 'schedulings.notes', 'employee.name as employee_name', 'order_parameters_detail.test_performed_by', 'schedulings.scheduled_at', 'schedulings.completed_at');
		}
		$assignedJobObj->orderBy('schedulings.scheduling_id', 'DESC');
		!empty($sampleWiseDisplay) ? $assignedJobObj->groupBy('order_master.order_no') : '';
		$assignedJobs = $assignedJobObj->get();

		//to formate created and updated date		   
		$models->formatTimeStampFromArray($assignedJobs, DATEFORMAT, !empty($sampleWiseDisplay) ? true : false);

		return response()->json(array('error' => $error, 'message' => $message, 'sampleWiseDisplay' => $sampleWiseDisplay, 'assignedJobs' => $assignedJobs));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generateAssignedJobDocuments(Request $request)
	{

		global $order, $models, $scheduling;

		$error      = '0';
		$message    = '';
		$data       = '';
		$filterData = array();

		//parsing request data
		if ($request->isMethod('post') && !empty($request->generate_assign_jobs_documents)) {

			//Parsing Posted data
			$filterData 	= $request->all();
			$user_id            = defined('USERID') ? USERID : '0';
			$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
			$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
			$currentDate	= defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
			$department_ids     = !empty($filterData['product_category_id']) ? array($filterData['product_category_id']) : $department_ids;
			$sampleWiseDisplay  = !empty($filterData['schedule_type']) && $filterData['schedule_type'] == '1' ? true : false;
			$subHeading		= 'All';

			$assignedJobObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->leftJoin('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_parameters_detail', 'order_parameters_detail.analysis_id', 'schedulings.order_parameter_id')
				->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
				->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_dtl.test_id')
				->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
				->leftJoin('equipment_type', 'equipment_type.equipment_id', 'schedulings.equipment_type_id')
				->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
				->leftJoin('users as employee', 'employee.id', 'schedulings.employee_id')
				->orwhere('schedulings.employee_id', '<>', NULL);

			//cancelled orders not visisble any where	
			$assignedJobObj->whereNotIn('order_master.status', array('10', '12'));

			//Filtering records according to department assigned
			if (!empty($department_ids) && is_array($department_ids)) {
				$assignedJobObj->whereIn('order_master.product_category_id', $department_ids);
			}
			//Filtering records according to division assigned
			if (!empty($filterData['division_id']) && is_numeric($filterData['division_id'])) {
				$assignedJobObj->where('order_master.division_id', $filterData['division_id']);
			}
			//if filter form submitted	  
			if (!empty($filterData['equipment_type_id'])) {
				$assignedJobObj->where('schedulings.equipment_type_id', '=', $filterData['equipment_type_id']);
			}
			//Filter data by Status
			if (!empty($filterData['status'])) {
				$assignedJobObj->where('schedulings.status', '=', $filterData['status']);
			}
			//Filter data by Employee Name
			if (defined('IS_TESTER') && IS_TESTER) {
				$assignedJobObj->where('schedulings.employee_id', '=', $user_id);
				$subHeading = $models->getTableUniqueIdByName($table_name = 'users', $field_name = 'id', $field_value = $user_id, $return_field = 'name');
			} else if (!empty($filterData['employee_id'])) {
				$assignedJobObj->where('schedulings.employee_id', '=', $filterData['employee_id']);
				$subHeading = $models->getTableUniqueIdByName($table_name = 'users', $field_name = 'id', $field_value = $filterData['employee_id'], $return_field = 'name');
			}
			if (!empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
				$assignedJobObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($filterData['order_date_from'], $filterData['order_date_to']));
			} else if (!empty($filterData['order_date_from']) && empty($filterData['order_date_to'])) {
				$assignedJobObj->where(DB::raw("DATE(order_master.order_date)"), '>=', $filterData['order_date_from']);
			} else if (empty($filterData['order_date_from']) && !empty($filterData['order_date_to'])) {
				$assignedJobObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $filterData['order_date_to']);
			} else if (empty($filterData)) {
				$assignedJobObj->where(DB::raw("DATE(order_master.order_date)"), $currentDate);
			}
			//Filtering records according to expected due date from and expected due date to 
			if (!empty($filterData['expected_due_date_from']) && !empty($filterData['expected_due_date_to'])) {
				$assignedJobObj->whereBetween(DB::raw("DATE(order_master.expected_due_date)"), array($filterData['expected_due_date_from'], $filterData['expected_due_date_to']));
			} else if (!empty($filterData['expected_due_date_from']) && empty($filterData['expected_due_date_to'])) {
				$assignedJobObj->where(DB::raw("DATE(order_master.expected_due_date)"), '>=', $filterData['expected_due_date_from']);
			} else if (empty($filterData['expected_due_date_from']) && !empty($filterData['expected_due_date_to'])) {
				$assignedJobObj->where(DB::raw("DATE(order_master.expected_due_date)"), '<=', $filterData['expected_due_date_to']);
			}
			//Filtering records according to Scheduled date from and Scheduled date to 
			if (!empty($filterData['scheduled_date_from']) && !empty($filterData['scheduled_date_to'])) {
				$assignedJobObj->whereBetween(DB::raw("DATE(schedulings.scheduled_at)"), array($filterData['scheduled_date_from'], $filterData['scheduled_date_to']));
			} else if (!empty($filterData['scheduled_date_from']) && empty($filterData['scheduled_date_to'])) {
				$assignedJobObj->where(DB::raw("DATE(schedulings.scheduled_at)"), '>=', $filterData['scheduled_date_from']);
			} else if (empty($filterData['scheduled_date_from']) && !empty($filterData['scheduled_date_to'])) {
				$assignedJobObj->where(DB::raw("DATE(schedulings.scheduled_at)"), '<=', $filterData['scheduled_date_to']);
			}
			//Filtering records according to Completed date from and Completed date to 
			if (!empty($filterData['completed_date_from']) && !empty($filterData['completed_date_to'])) {
				$assignedJobObj->whereBetween(DB::raw("DATE(schedulings.completed_at)"), array($filterData['completed_date_from'], $filterData['completed_date_to']));
			} else if (!empty($filterData['completed_date_from']) && empty($filterData['completed_date_to'])) {
				$assignedJobObj->where(DB::raw("DATE(schedulings.completed_at)"), '>=', $filterData['completed_date_from']);
			} else if (empty($filterData['completed_date_from']) && !empty($filterData['completed_date_to'])) {
				$assignedJobObj->where(DB::raw("DATE(schedulings.completed_at)"), '<=', $filterData['completed_date_to']);
			}
			if (!empty($sampleWiseDisplay)) {
				$assignedJobObj->select('order_master.order_id', 'order_master.order_date as booking_date', 'product_master_alias.c_product_name as sample_name', 'order_master.order_no as report_code', 'order_master.expected_due_date', 'order_master.order_dept_due_date as dept_due_date', 'order_master.test_completion_date as completed_at', 'schedulings.tentative_date', 'schedulings.tentative_time', 'employee.name as employee_name as analyst_name', 'schedulings.notes');
			} else {
				$assignedJobObj->select('order_master.order_id', 'order_master.order_date as booking_date', 'product_master_alias.c_product_name as sample_name', 'order_master.order_no as report_code', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name as parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'order_master.expected_due_date', 'order_parameters_detail.dept_due_date', 'schedulings.scheduled_at', 'schedulings.completed_at', 'schedulings.tentative_date', 'schedulings.tentative_time', 'employee.name as employee_name as analyst_name', 'schedulings.notes');
			}
			$assignedJobObj->orderBy('schedulings.scheduling_id', 'DESC');
			!empty($sampleWiseDisplay) ? $assignedJobObj->groupBy('order_master.order_no') : '';
			$assignedJobs = $assignedJobObj->get();

			//to formate created and updated date		   
			$models->formatTimeStampFromArrayExcel($assignedJobs, DATEFORMATEXCEL);

			if (!empty($assignedJobs)) {
				$assignedJobs 		= !empty($assignedJobs) ? json_decode(json_encode($assignedJobs), true) : array();
				$assignedJobs 		= $models->unsetFormDataVariablesArray($assignedJobs, array('canDispatchOrder', 'order_id'));
				$response['tableHead'] 	= !empty($assignedJobs) ? array_keys(end($assignedJobs)) : array();
				$response['tableBody'] 	= !empty($assignedJobs) ? $assignedJobs : array();
				$response['tablefoot']	= array();
				$response['heading'] 	= 'Assign Job List : ' . $subHeading . ' : ' . '(' . count($assignedJobs) . ')';
				if ($filterData['generate_assign_jobs_documents'] == 'PDF') {
					$pdfHeaderContent = $models->getHeaderFooterTemplate();
					$response['header_content'] = $pdfHeaderContent->header_content;
					$response['footer_content'] = $pdfHeaderContent->footer_content;
					return $models->downloadPDF($response, $contentType = 'analystSheet');
				} else if ($filterData['generate_assign_jobs_documents'] == 'Excel') {
					$response['mis_report_name'] = 'analyst_sheet_documents';
					return $models->generateExcel($response);
				}
			} else {
				Session::put('errorMsg', config('messages.message.noRecordFound'));
				return redirect('dashboard');
			}
		}

		//echo '<pre>';print_r($pendingJobData);die;
		Session::put('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		return redirect('dashboard');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function updateScheduledAssignedJobs(Request $request)
	{

		global $order, $models, $scheduling;

		$error    = '0';
		$message  = config('messages.message.updatedError');
		$data     = '';
		$formData = $schedulingData = $dataUpdate = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			parse_str($request->formData, $formData);
			$formData = array_filter($formData);

			//to formate created and updated date		   
			$schedulingData = $scheduling->formatScheduledAssignedJobData($formData);
			$message        = config('messages.message.updateJobStatusError');

			if (!empty($schedulingData)) {
				foreach ($schedulingData as $key => $job) {

					//Setting Updatus
					$dataUpdate = array('status' => $job['status'], 'notes' => !empty($job['notes']) ? $job['notes'] : '');

					//Checking if Test result was filled by Tester or not in case of completed
					if ($job['status'] == '3') {
						$checkUpdateTestResult = DB::table('order_parameters_detail')->where('order_parameters_detail.analysis_id', $job['order_parameter_id'])->whereNotNull('order_parameters_detail.test_result')->first();
						if (!empty($checkUpdateTestResult)) {
							DB::table('schedulings')->where('schedulings.scheduling_id', $job['scheduling_id'])->update($dataUpdate);
							$error   = '1';
							$message = config('messages.message.updated');
						} else {
							$error   = '0';
							$message = config('messages.message.updateJobStatusError');
							return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
						}
					} else {
						DB::table('schedulings')->where('schedulings.scheduling_id', $job['scheduling_id'])->update($dataUpdate);
						$error   = '1';
						$message = config('messages.message.updated');
					}
				}
			}

			return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
		}
	}

	/**
	 * job Sheet Print
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function jobSheetPrint()
	{

		global $order, $models, $scheduling;

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('schedulings.jobPrint.index', ['title' => 'Job Sheet Print', '_jobsheetprint' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * get date wise order numbers
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getJobSheetPrintOrderNumbers(Request $request)
	{

		global $order, $models, $scheduling;

		$error    = '0';
		$message  = '';
		$data     = '';
		$formData = $orderScheduling = array();

		if (!empty($request->formData) && $request->isMethod('post')) {

			//Serializing the Form Data
			parse_str($request->formData, $formData);

			if (!empty($formData['order_date'])) {

				$user_id         = defined('USERID') ? USERID : '0';
				$department_ids  = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
				$role_ids        = defined('ROLE_IDS') ? ROLE_IDS : '0';
				$department_ids  = !empty($formData['product_category_id']) ? array($formData['product_category_id']) : $department_ids;

				$orderSchedulingObj = DB::table('schedulings')
					->join('order_master', 'order_master.order_id', 'schedulings.order_id')
					->select('order_master.order_id as id', 'order_master.order_no as order_number')
					->whereNotIn('order_master.status', array('10', '12'));

				//Filtering records according to Order Date assigned
				if (!empty($formData['order_date'])) {
					$orderSchedulingObj->where(DB::raw("DATE(order_master.order_date)"), 'like', '%' . $models->convertDateFormat($formData['order_date']) . '%');
				}
				//Filtering records according to division assigned
				if (!empty($formData['division_id']) && is_numeric($formData['division_id'])) {
					$orderSchedulingObj->where('order_master.division_id', $formData['division_id']);
				}
				//Filtering records according to division assigned
				if (!empty($department_ids) && is_array($department_ids)) {
					$orderSchedulingObj->whereIn('order_master.product_category_id', $department_ids);
				}
				$orderScheduling = $orderSchedulingObj->groupBy('order_master.order_id')->orderBy('order_master.order_id')->get();
			}
		}

		return response()->json(array('error' => $error, 'orderNumberList' => $orderScheduling, 'message' => $message));
	}

	/**
	 * get date wise order numbers
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getJobSheetPrintOrders(Request $request)
	{

		global $order, $models, $scheduling;

		$error    = '0';
		$message  = '';
		$data     = '';
		$hasPermissionEditDddRddEdd = '0';
		$formData = $orderScheduling = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Serializing the Form Data
			parse_str($request->formData, $formData);

			if (!empty($formData['order_date']) && (defined('IS_ADMIN') || defined('IS_JOB_SCHEDULER') || defined('IS_CRM'))) {

				$user_id         = defined('USERID') ? USERID : '0';
				$department_ids  = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
				$role_ids        = defined('ROLE_IDS') ? ROLE_IDS : '0';
				$department_ids  = !empty($formData['product_category_id']) ? array($formData['product_category_id']) : $department_ids;

				$orderSchedulingObj = DB::table('schedulings')
					->join('order_master', 'order_master.order_id', 'schedulings.order_id')
					->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
					->join('product_master', 'product_master.product_id', 'order_master.product_id')
					->join('product_test_hdr', 'product_test_hdr.test_id', 'order_master.product_test_id')
					->join('divisions', 'divisions.division_id', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
					->leftJoin('city_db', 'city_db.city_id', 'customer_master.customer_city')
					->join('users as createdBy', 'createdBy.id', 'order_master.created_by')
					->leftJoin('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id');

				//cancelled orders not visisble any where	
				$orderSchedulingObj->whereNotIn('order_master.status', array('10', '12'));

				//Filtering records according to Order Date assigned
				if (!empty($formData['order_date'])) {
					$orderSchedulingObj->where(DB::raw("DATE(order_master.order_date)"), 'like', '%' . $models->convertDateFormat($formData['order_date']) . '%');
				}
				//Filtering records according to division assigned
				if (!empty($formData['division_id']) && is_numeric($formData['division_id'])) {
					$orderSchedulingObj->where('order_master.division_id', $formData['division_id']);
				}
				//Filtering records according to division assigned
				if (!empty($department_ids) && is_array($department_ids)) {
					$orderSchedulingObj->whereIn('order_master.product_category_id', $department_ids);
				}
				//Filtering records according to division assigned
				if (!empty($formData['order_id'])) {
					$orderSchedulingObj->where('order_master.order_id', $formData['order_id']);
				}

				$orderSchedulingObj->select('order_master.order_id', 'order_master.status', 'order_master.order_no', 'order_master.order_date', 'order_master.sample_description_id', 'order_master.remarks', 'order_master.expected_due_date', 'order_master.tat_in_days', 'customer_master.customer_name', 'product_master.product_name', 'product_test_hdr.test_code', 'product_master_alias.c_product_name as sample_description', 'city_db.city_name as customer_city');
				$orderScheduling = $orderSchedulingObj->groupBy('order_master.order_id')->orderBy('order_master.order_date', 'DESC')->get();

				//to formate order and Report date		   
				$models->formatCustomTimeStampFromArray($orderScheduling, DATETIMEFORMAT, $coloumNameArray = array('order_date', 'expected_due_date'));

				//Assigning Permission for Update Expected Due Date/Report  Due Date/Department  Due Date
				if (!empty($orderScheduling)) {
					foreach ($orderScheduling as $value) {
						if ((strtotime(date('Y-m-d', strtotime($value->expected_due_date))) >= strtotime(date('Y-m-d'))) && ($value->status <= '3')) {
							if (defined('IS_ADMIN') && IS_ADMIN) {
								$value->hasPermissionEditDddRddEdd = '1';
							} else if (defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER) {
								$value->hasPermissionEditDddRddEdd = empty($order->isOrderSchedulingCompleted($value->order_id)) ? '1' : '0';
							} else if (defined('IS_CRM') && IS_CRM) {
								$value->hasPermissionEditDddRddEdd = '1';
							} else {
								$value->hasPermissionEditDddRddEdd = '0';
							}
						} else {
							$value->hasPermissionEditDddRddEdd = '2';
						}
					}
				}
			}
		}

		return response()->json(array('error' => $error, 'message' => $message, 'orderList' => $orderScheduling));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function viewOrder(Request $request, $order_id)
	{

		global $order, $models;

		$error   = '0';
		$message = '';
		$data    = '';
		$data    = $rawTestProductStdParaList = $hasPermissionToSaveTestResult = $categoryWiseParamenter = $hasPermissionToFinaliseForInvoice = $categoryWiseParamenterArr = $errorParameterIdsArr = array();

		if ($order_id) {

			$error              		= '1';
			$equipment_type_ids 		= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
			$role_ids           		= defined('ROLE_IDS') ? ROLE_IDS : '0';
			$user_id            		= defined('USERID') ? USERID : '0';
			$orderList              		= $order->getOrder($order_id);
			$errorParameterIdsArr   		= !empty($orderList) ? explode(',', $orderList->error_parameter_ids) : array();
			$testProductStdParaList 		= $order->getOrderParameters($order_id);
			$orderPerformerRecord		= $order->getOrderPerformerRecord($order_id);
			$allowedExceptionParameters         = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)');

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
					if (!empty($values->test_parameter_name) && strtolower($values->test_parameter_name) == 'reference to protocol') {
						if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
							$values->description = $values->test_result;
						}
					}
					$rawTestProductStdParaList[$values->analysis_id] = $values;

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
					//************/Assignuing permission to Add the Parameter result*********                    
				}
			}

			$hasPermToSaveTestResult    = !empty($hasPermissionToSaveTestResult) ? 1 : 0;
			$hasPermToInvoiceTestResult = !empty($hasPermissionToFinaliseForInvoice) && !in_array("", $hasPermissionToFinaliseForInvoice) ? 1 : 0;

			if (!empty($rawTestProductStdParaList)) {
				foreach ($rawTestProductStdParaList as $analysis_id => $values) {
					$models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to);
					$categoryWiseParamenter[$values->test_para_cat_id]['testId']     	      = $values->test_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']      = $values->category_sort_by;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']          = $values->test_para_cat_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']        = $values->test_para_cat_name;
					$categoryWiseParamenter[$values->test_para_cat_id]['productCategoryName'] = str_replace(' ', '', strtolower($values->test_para_cat_name));
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][]    = $values;
				}
				$categoryWiseParamenterArr = $models->sortArrayAscOrder(array_values($categoryWiseParamenter));
			}
		}

		return response()->json(['error' => $error, 'message' => $message, 'hasPermToSaveTestResult' => $hasPermToSaveTestResult, 'hasPermToInvoiceTestResult' => $hasPermToInvoiceTestResult, 'orderList' => $orderList, 'orderParameterList' => $categoryWiseParamenterArr, 'orderTrackRecord' => $orderPerformerRecord]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getEditFormData(Request $request, $test_para_cat_id, $product_category_id)
	{

		global $models;

		$parameterList = $methodList = $equipmentList = array();

		$analysis_id = !empty($request->data) ? $request->data : '';

		$orderParameterList = DB::table('order_parameters_detail')
			->where('order_parameters_detail.analysis_id', '=', $analysis_id)
			->select('order_parameters_detail.claim_value', 'order_parameters_detail.standard_value_from', 'order_parameters_detail.standard_value_to', 'order_parameters_detail.equipment_type_id', 'order_parameters_detail.method_id', 'order_parameters_detail.test_parameter_id', 'order_parameters_detail.order_parameter_nabl_scope')
			->first();
		$parameterList	= DB::table('test_parameter')
			->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', '=', 'test_parameter_categories.test_para_cat_id')
			->select('test_parameter_id as id', 'test_parameter_name as name')
			->where('test_parameter_categories.test_para_cat_id', $test_para_cat_id)
			->orderBy('test_parameter_name', 'ASC')
			->get()
			->toArray();

		$models->htmlToStringFormate($parameterList);

		$methodList = DB::table('method_master')
			->select('method_id as id', 'method_name as name')
			->where('method_master.product_category_id', '=', $product_category_id)
			->orderBy('method_id', 'ASC')
			->get()
			->toArray();

		$equipmentList = DB::table('equipment_type')
			->select('equipment_id as id', 'equipment_name as name')
			->orderBy('name', 'ASC')
			->get()
			->toArray();

		$nablList = $models->getNablScopeList();

		return response()->json(['parameterList' => $parameterList, 'methodList' => $methodList, 'equipmentList' => $equipmentList, 'nablScopeList' => $nablList, 'orderParameterList' => $orderParameterList]);
	}

	/*****
	 ****
	 **** 02 dec,2017 add more parameter into order parameter list
	 *****
	 *****/
	public function getAddFormData(Request $request, $order_id)
	{

		global $models;

		$parameterList = $methodList = $equipmentList = array();

		$product_category_id = DB::table('order_master')->where('order_master.order_id', '=', $order_id)->select('order_master.product_category_id', 'order_master.product_test_id')->first();

		$parameterList = DB::table('test_parameter')
			->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', '=', 'test_parameter_categories.test_para_cat_id')
			->select('test_parameter_id as id', 'test_parameter_name as name')
			->where('test_parameter_categories.product_category_id', $product_category_id->product_category_id)
			->orderBy('test_parameter_name', 'ASC')->get()->toArray();

		$models->htmlToStringFormate($parameterList);
		$methodList = DB::table('method_master')->select('method_id as id', 'method_name as name')->where('method_master.product_category_id', '=', $product_category_id->product_category_id)->orderBy('method_name', 'ASC')->get()->toArray();
		$equipmentList = DB::table('equipment_type')->select('equipment_id as id', 'equipment_name as name')->orderBy('name', 'ASC')->get()->toArray();

		return response()->json(['parameterList' => $parameterList, 'methodList' => $methodList, 'equipmentList' => $equipmentList]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function saveParameterDetails(Request $request)
	{

		global $models, $order;

		$error   = '0';
		$message = $expected_due_date = '';
		$updated = $formData = array();

		if ($request->isMethod('post') && !empty($request['data']['formData'])) {

			//Parsing Serialize data
			parse_str($request['data']['formData'], $formData);

			if ($models->claimValueValidation($formData['order_parameters_detail'])) {
				$message = config('messages.message.claimValueErrorMsg');
			} else if ($models->claimUnitValidation($formData['order_parameters_detail'])) {
				$message = config('messages.message.claimValueUnitErrorMsg');
			} else {

				$orderId = !empty($formData['order_id']) ? $formData['order_id'] : '0';
				unset($formData['order_parameters_detail']['claim_dependent']);

				if (!empty($orderId) && !empty($formData['order_parameters_detail'])) {
					foreach ($formData['order_parameters_detail'] as $keyParameter => $orderParametersData) {
						foreach ($orderParametersData as $key => $value) {
							$orderParametersDataSave[$key]['order_id']    = $orderId;
							$orderParametersDataSave[$key][$keyParameter] = empty($value) ? null : $value;
						}
					}
					if (!empty($orderParametersDataSave)) {
						foreach ($orderParametersDataSave as $key => $orderParameters) {
							$orderParametersDetail = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $orderParameters['order_id'])->where('order_parameters_detail.product_test_parameter', $orderParameters['product_test_parameter'])->first();
							if (empty($orderParametersDetail)) {
								$analysisId = DB::table('order_parameters_detail')->insertGetId($orderParameters);
								if ($analysisId) {
									$order->updateOrderSchedulingJobs($analysisId); //Adding Order Parameter in Scheduling table
									$error = '1';
									$message = config('messages.message.parameterAdded');
								}
							}
						}

						//Re-Generate/Update Order Expected Due Date in case of New Test Parameter added by the User
						$order->generateUpdateOrderExpectedDueDate_v3($orderId);

						//Re-calculation of Report Due Date and Department Due Date in case of Sample Edited by User
						$order->updateReportDepartmentDueDate($orderId);

						//Getting Calculated expected_due_date
						$expected_due_date = $models->get_formatted_date($order->getOrderColumValue($orderId, 'expected_due_date'), DATETIMEFORMAT);
					}
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'expected_due_date' => $expected_due_date));
	}

	/**
	 * add more parameter into order parameter list
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getAllFormData(Request $request, $order_id)
	{

		global $models;

		$parameterList = $methodList = $equipmentList = array();

		$product_category_id = DB::table('order_master')->where('order_master.order_id', '=', $order_id)->select('order_master.product_category_id', 'order_master.product_test_id')->first();

		$parameterList = DB::table('test_parameter')
			->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', '=', 'test_parameter_categories.test_para_cat_id')
			->select('test_parameter_id as id', 'test_parameter_name as name')
			->where('test_parameter_categories.product_category_id', $product_category_id->product_category_id)
			->orderBy('test_parameter_name', 'ASC')->get()->toArray();

		$models->htmlToStringFormate($parameterList);
		$methodList = DB::table('method_master')->select('method_id as id', 'method_name as name', 'method_name as label')->where('method_master.product_category_id', '=', $product_category_id->product_category_id)->orderBy('method_name', 'ASC')->get()->toArray();
		$equipmentList = DB::table('equipment_type')->select('equipment_id as id', 'equipment_name as name')->orderBy('name', 'ASC')->get()->toArray();

		return response()->json(['parameterList' => $parameterList, 'methodList' => $methodList, 'equipmentList' => $equipmentList]);
	}

	/**
	 * add more parameter into order parameter list
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateParameterDetails(Request $request)
	{

		global $models;

		$returnData = $postData = $updatedArr = array();

		if ($request->isMethod('post')) {
			if (!empty($request['data']['formData'])) {

				//Parsing Serialize data
				parse_str($request['data']['formData'], $postData);

				$formData = $models->unsetFormDataVariables($postData, array('product_category_id'));

				if (!empty($formData)) {
					foreach ($formData as $key => $newPostData) {
						$newPostData['equipment_type_id'] = !empty($newPostData['equipment_type_id']) ? $newPostData['equipment_type_id'] : NULL;
						$newPostData['method_id'] = !empty($newPostData['method_id']) ? $newPostData['method_id'] : NULL;
						$newPostData['order_parameter_nabl_scope'] = !empty($newPostData['order_parameter_nabl_scope']) ? $newPostData['order_parameter_nabl_scope'] : '0';
						$updated =  DB::table('order_parameters_detail')->where('analysis_id', '=', $key)->update($newPostData);
						if ($updated) {
							//if(!empty($newPostData['equipment_type_id'])){						
							DB::table('schedulings')->where('order_parameter_id', '=', $key)->update(['equipment_type_id' => $newPostData['equipment_type_id']]);
							//}
							$updatedArr[] = $key;
						}
						if (!empty($updatedArr)) {
							$returnData = array('success' => config('messages.message.parameterUpdated'));
						} else {
							$returnData = array('success' => config('messages.message.savedNoChange'));
						}
					}
				}
			} else {
				$returnData = array('error' =>  config('messages.message.dataNotFound'));
			}
		} else {
			$returnData = array('error' => config('messages.message.dataNotFound'));
		}
		return response()->json($returnData);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function checkEquipment($orderId, $type)
	{

		$error = '0';
		$message = config('messages.message.error');
		$data = array();
		$flag = '0';
		$formData = array();
		$attachedEquipment = '';

		$order_id = !empty($orderId) ? $orderId : '';
		if (!empty($order_id)) {
			$chkEquipment = DB::table('order_parameters_detail')
				->join('equipment_type', 'equipment_type.equipment_id', 'order_parameters_detail.equipment_type_id')
				->where('order_parameters_detail.order_id', '=', $order_id)->get();
		}
		if (!empty($chkEquipment)) {
			foreach ($chkEquipment as $key => $value) {
				if (strtolower($value->equipment_name) == 'hplc' || strtolower($value->equipment_name) == 'chemically') {
					$error = '1';
					$attachedEquipment = strtolower($value->equipment_name);
				}
			}
		}
		if ($type == 'returnJson') {
			return response()->json(array('error' => $error, 'attachedEquipment' => $attachedEquipment));
		} else {
			return (array('error' => $error, 'attachedEquipment' => $attachedEquipment));
		}
	}

	/* job sheet print pdf generation 25-07-2017*/
	public function generateJobSheetPdf(Request $request)
	{

		$error = '0';
		$message = config('messages.message.error');
		$data = array();
		$flag = '0';
		$formData = array();

		if (!empty($request['job_sheet_file'])) {
			$formData = array_filter($request->all());
			//generate pdf file in public/images/sales/temp folder
			$job_sheet_file = $formData['job_sheet_file'];
			list($type, $job_sheet_file) = explode(';', $job_sheet_file);
			list(, $job_sheet_file) = explode(',', $job_sheet_file);
			$job_sheet_file = base64_decode($job_sheet_file);
			if (!file_exists(DOC_ROOT . JOB_SHEET_PRINT_PATH)) {
				mkdir(DOC_ROOT . JOB_SHEET_PRINT_PATH, 0777, true);
			} else {
				$files = glob(DOC_ROOT . JOB_SHEET_PRINT_PATH . '/*.pdf'); //get all file names
				foreach ($files as $file) {
					if (is_file($file))
						unlink($file); //delete all files from temp folder
				}
			}
			$pdf = fopen(DOC_ROOT . JOB_SHEET_PRINT_PATH . $formData['job_sheet_file_name'], 'w');
			fwrite($pdf, $job_sheet_file);
			fclose($pdf);
			$pdfUrl = JOB_SHEET_PRINT_PATH . $formData['job_sheet_file_name'];
			$error = 1;
		}

		return response()->json(array('error' => $error, 'pdfUrl' => $pdfUrl));
	}

	/**
	 * generate Job Order PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateAnalyticalSheetPdf(Request $request)
	{

		global $order, $report, $models;

		$error 	  = '0';
		$message  = config('messages.message.error');
		$data 	  = $analyticalSheetFileName = '';
		$formData = $analyticalData = array();

		if ($request->isMethod('post') && !empty($request->order_id) && !empty($request->downloadType) && isset($request->actionType)) {
			$analyticalData = $order->getOrder($request->order_id);
			if (!empty($analyticalData->order_no)) {
				if (!empty($request->actionType)) {	// For Generation		
					$analyticFileNameData = $models->generatePDF($request->all(), $contentType = 'jobSheet');
					if (!empty($analyticFileNameData)) {
						foreach ($analyticFileNameData as $orderId => $analyticalSheetFileName) {
							if ($orderId && $analyticalSheetFileName) {
								$fieldName = $request->downloadType == '1' ? 'order_master.job_analytical_sheet_file' : 'order_master.job_analytical_sheet_cal_file';
								DB::table('order_master')->where('order_master.order_id', '=', $orderId)->update([$fieldName => $analyticalSheetFileName]);
								$error                   = '1';
								$message 				 = config('messages.message.fileGenerationMsg');
								$analyticalSheetFileName = preg_replace('/(\/+)/', '/', ANALYTICAL_PATH . $analyticalSheetFileName);
							}
						}
					}
				} else {	// For Download
					$message        = config('messages.message.fileDownloadErrorMsg');
					$analyticalData = $order->getOrder($request->order_id);
					if ($request->downloadType == '1') {
						if (!empty($analyticalData->job_analytical_sheet_file)) {
							$error          		 = '1';
							$message 	    		 = '';
							$analyticalSheetFileName = preg_replace('/(\/+)/', '/', ANALYTICAL_PATH . $analyticalData->job_analytical_sheet_file);
						}
					} else if ($request->downloadType == '2') {
						if (!empty($analyticalData->job_analytical_sheet_cal_file)) {
							$error          		 = '1';
							$message 	    		 = '';
							$analyticalSheetFileName = preg_replace('/(\/+)/', '/', ANALYTICAL_PATH . $analyticalData->job_analytical_sheet_cal_file);
						}
					}
				}
			}
		}

		return response()->json(array('error' => $error, 'message' => $message, 'analyticalSheetFileName' => $analyticalSheetFileName, 'analyticalDataList' => $analyticalData));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteOrderParameter(Request $request)
	{

		global $order, $models;

		$error    = '0';
		$message  = '';
		$data     = $expected_due_date = '';

		try {
			$orderId    = !empty($request->order_id) ? $request->order_id : '0';
			$analysisId = !empty($request->analysis_id) ? $request->analysis_id : '0';
			if ($orderId && $analysisId) {

				//Starting transaction
				DB::beginTransaction();

				$checkParameterCount = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $orderId)->count();
				if ($checkParameterCount > '1') {
					if (DB::table('order_parameters_detail')->where('order_parameters_detail.analysis_id', '=', $analysisId)->delete()) {

						//Insert Discipline Name and Group Name in order_discipline_group_dtl
						$order->insertUpdateDisciplineGroupDetail($orderId);

						//Re-Generate/Update Order Expected Due Date in case of New Test Parameter added by the User
						$order->generateUpdateOrderExpectedDueDate_v3($orderId);

						//Re-calculation of Report Due Date and Department Due Date in case of Sample Edited by User
						$order->updateReportDepartmentDueDate($orderId);

						//Getting Calculated expected_due_date
						$expected_due_date = $models->get_formatted_date($order->getOrderColumValue($orderId, 'expected_due_date'), DATETIMEFORMAT);

						//Messages
						$error    = '1';
						$message = config('messages.message.deleted');

						//Committing the queries
						DB::commit();
					} else {
						$message = config('messages.message.deletedError');
					}
				} else {
					$message = config('messages.message.deletedParameterError');
				}
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			DB::rollback();
			$message = config('messages.message.foreignKeyDeleteError');
		}
		return response()->json(['error' => $error, 'message' => $message, 'expected_due_date' => $expected_due_date]);
	}

	/**05 dec 2017*/
	/**
	 * Get list of ProductTestParameters on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getEditProductTestParametersList(Request $request, $testId, $orderId)
	{

		global $order, $models;

		$productTestParametersList = DB::table('product_test_dtl')
			->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
			->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
			->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
			->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
			->leftJoin('equipment_type', 'equipment_type.equipment_id', 'product_test_dtl.equipment_type_id')
			->leftJoin('method_master', 'method_master.method_id', 'product_test_dtl.method_id')
			->select('product_test_dtl.*', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.category_sort_by', 'product_master.product_name', 'product_master.p_category_id', 'order_parameters_detail.product_test_parameter', 'order_parameters_detail.order_id', 'test_parameter.test_parameter_nabl_scope')
			->where('product_test_dtl.test_id', $testId)
			->leftJoin('order_parameters_detail', function ($join) use ($orderId) {
				$join->on('order_parameters_detail.product_test_parameter', '=', 'product_test_dtl.product_test_dtl_id');
				$join->where('order_parameters_detail.order_id', $orderId);
			})
			->groupBy('product_test_dtl.product_test_dtl_id')
			->orderBy('product_test_dtl.parameter_sort_by', 'ASC')
			->get();

		$categoryWiseParamenter = array();
		if (!empty($productTestParametersList)) {
			foreach ($productTestParametersList as $key => $values) {
				$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']   = $values->category_sort_by;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']       = $values->test_para_cat_id;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     = $values->test_para_cat_name;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] = $values;
			}
			$categoryWiseParamenter = array_values($categoryWiseParamenter);
		}

		$categoryWiseParamenterSortedArr = $models->sortArrayAscOrder($categoryWiseParamenter);

		return response()->json(['productTestParametersList' => $categoryWiseParamenterSortedArr]);
	}

	/**
	 * Get list of parameters on page .
	 * Date : 30-06-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getEditProductTestParameters(Request $request)
	{

		global $order, $models;

		$categoryWiseParamenter = $paraIdArr = $categoryWiseParamenterSortedArr = array();

		if (!empty($request['data']['formData'])) {

			//Parsing the form Data
			parse_str($request['data']['formData'], $newPostData);

			if (!empty($newPostData['product_test_dtl_id'])) {

				$productTestDtlIds = array_filter(array_unique($newPostData['product_test_dtl_id']));

				$productTestParametersList = DB::table('product_test_dtl')
					->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
					->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
					->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
					->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
					->leftJoin('equipment_type', 'equipment_type.equipment_id', 'product_test_dtl.equipment_type_id')
					->leftJoin('method_master', 'method_master.method_id', 'product_test_dtl.method_id')
					->select('product_test_dtl.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.category_sort_by', 'product_master.product_name', 'product_master.p_category_id as product_category_id', 'test_parameter.test_parameter_nabl_scope')
					->whereIn('product_test_dtl.product_test_dtl_id', $productTestDtlIds)
					->orderBy('product_test_dtl.parameter_sort_by', 'ASC')
					->get();

				$categoryWiseParamenter = array();

				if (!empty($productTestParametersList)) {
					foreach ($productTestParametersList as $key => $values) {
						$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']   = $values->category_sort_by;
						$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']       = $values->test_para_cat_id;
						$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     = $values->test_para_cat_name;
						$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] = $values;
					}
					$categoryWiseParamenter = array_values($categoryWiseParamenter);
					$categoryWiseParamenterSortedArr = $models->sortArrayAscOrder($categoryWiseParamenter);
				}
			}
		}
		return response()->json(['productTestParametersList' => $categoryWiseParamenterSortedArr]);
	}

	/***********************************
	 * Update Order Expected Due Date
	 * Date : 18-12-2017
	 * Author : Praveen Singh
	 ***********************************/
	public function updateOrderExpectedDueDate(Request $request)
	{

		global $order, $models, $scheduling, $mail;

		$error       	 = '0';
		$message     	 = config('messages.message.error');
		$data        	 = '';
		$currentDate	 = !defined('CURRENTDATE') ? CURRENTDATETIME : date('Y-m-d');
		$formData    	 = array();

		//Updating record in orders table
		if ($request->isMethod('post') && !empty($request->formData)) {

			try {
				//Parsing the Serialze Dta
				parse_str($request->formData, $formData);

				if (empty($formData['order_id'])) {
					$message = config('messages.message.error');
				} else if (empty($formData['no_of_days'])) {
					$message = config('messages.message.expectedDueDayRequired');
				} else if (empty($formData['reason_of_change'])) {
					$message = config('messages.message.required');
				} else if (empty($formData['expected_due_date'])) {
					$message = config('messages.message.expectedDueDateRequired');
				} else {

					//Starting transaction
					DB::beginTransaction();

					$orderId  				  = !empty($formData['order_id']) ? $formData['order_id'] : '0';
					$noOfDays 				  = !empty($formData['no_of_days']) ? $formData['no_of_days'] : '0';
					$reasonOfChange 		  = !empty($formData['reason_of_change']) ? $formData['reason_of_change'] : false;
					$sendMailStatus 		  = !empty($formData['send_mail_status']) ? true : '0';
					$excludeCalculationLogics = !empty($formData['exclude_logic_calculation']) ? true : false;

					if (!empty($orderId) && !empty($noOfDays) && !empty($reasonOfChange)) {

						//Updating Order Expected Due Date(EDD) using no. of days.
						$eddStatus = $scheduling->updateOrderExpectedDueDateUsingDays($orderId, $noOfDays, $excludeCalculationLogics);

						//Updating Report Due Date and Department Due date in Order Parameter table using no. of days.
						$dddStatus = $scheduling->updateReportDepartmentDueDateUsingDays($orderId, $noOfDays, $excludeCalculationLogics);

						if ($eddStatus && $dddStatus) {

							//Updating record in the order_expected_due_date_logs table
							$eddDddLogStatus = $scheduling->updateOrderExpectedDueDateLogs($formData);

							if ($eddDddLogStatus) {

								//send mail to EDD Change
								if ($sendMailStatus) {
									$mail->sendMail(array('order_id' => $orderId, 'mailTemplateType' => '8', 'current_edd_log_id' => $eddDddLogStatus));
								}

								//Success Msg
								$error   = '1';
								$message = config('messages.message.updated');

								//Committing the queries
								DB::commit();
							}
						}
					}
				}
			} catch (\Illuminate\Database\QueryException $ex) {
				DB::rollback();
				$message = config('messages.message.error');
			}
		}

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
	}

	/***********************************
	 * Function   : Get Unhold Sample List
	 * Created On : 29-Dec-2021
	 * Created By : Praveen Singh
	 ***********************************/
	public function getSchedulingUnholdJobs(Request $request)
	{

		global $order, $models, $scheduling;

		$error      = '0';
		$message    = '';
		$data       = '';
		$token_flag = true;
		$filterData = array();

		//Access Permission
		if (!defined('IS_ADMIN') && !defined('IS_JOB_SCHEDULER')) {
			$token_flag = false;
		} else {

			//parsing request data
			parse_str($request->formData, $filterData);

			$user_id             = defined('USERID') ? USERID : '0';
			$department_ids      = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$user_department_ids = defined('USER_DEPARTMENT_IDS') ? USER_DEPARTMENT_IDS : '0';
			$role_ids            = defined('ROLE_IDS') ? ROLE_IDS : '0';
			$equipment_type_ids  = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
			$currentDate	 	 = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
			$division_id	 	 = !empty($filterData['division_id']) ? $filterData['division_id'] : '0';
			$department_ids      = !empty($filterData['product_category_id']) ? array($filterData['product_category_id']) : $department_ids;

			$pendingUnholdJobObj = DB::table('order_master')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_process_log', 'order_process_log.opl_order_id', 'order_master.order_id')
				->select('order_master.order_id', 'order_master.order_no', 'order_master.order_date', 'order_master.order_dept_due_date', 'order_master.expected_due_date', 'order_sample_priority.sample_priority_name', 'product_master_alias.c_product_name as sample_description')
				->where('order_master.status', '1')
				->whereIn('order_process_log.opl_order_status_id', ['12']);

			//Filtering records according to department assigned
			if (!empty($department_ids) && is_array($department_ids)) {
				$pendingUnholdJobObj->whereIn('order_master.product_category_id', $department_ids);
			}
			//Filtering records according to division assigned
			if (!empty($division_id) && is_numeric($division_id)) {
				$pendingUnholdJobObj->where('order_master.division_id', $division_id);
			}
			$pendingUnholdJobData = $pendingUnholdJobObj->groupBy('order_master.order_id')->orderBy('order_master.order_date', 'DESC')->get()->toArray();

			//to formate created and updated date		   
			$models->formatTimeStampFromArray($pendingUnholdJobData, DATEFORMAT, false);
		}

		//Setting permission Data
		$pendingUnholdJobData = $token_flag ? $pendingUnholdJobData : array();

		//echo '<pre>';print_r($pendingUnholdJobData);die;
		return response()->json(array('error' => $error, 'message' => $message, 'pendingUnholdJobData' => $pendingUnholdJobData));
	}
}
