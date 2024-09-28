<?php

/*****************************************************
 *Orders Controller File
 *Created By:Praveen-Singh
 *Created On : 15-Dec-2017
 *Modified On : 10-Oct-2018
 *Package : ITC-ERP-PKL
 ******************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Company;
use App\Sample;
use App\Order;
use App\Models;
use App\Setting;
use App\ProductCategory;
use App\SendMail;
use App\Report;
use App\Customer;
use App\TrfHdr;
use App\OrderDynamicField;
use App\OrderClientApprovalDtl;
use Session;
use Validator;
use Route;
use DB;
use DNS1D;

class OrdersController extends Controller
{
	/*************************
	 * protected Variable.
	 **************************/
	protected $auth;

	/*************************************
	 * Create a new controller instance.
	 * @return void
	 **************************************/
	public function __construct()
	{

		global $sample, $order, $models, $mail, $reports, $customer, $trfHdr, $orderDynamicField, $orderClientApproval;

		$sample = new Sample();
		$order 	= new Order();
		$models = new Models();
		$mail 	= new SendMail();
		$reports = new Report();
		$customer = new Customer();
		$trfHdr = new TrfHdr();
		$orderDynamicField = new OrderDynamicField();
		$orderClientApproval = new OrderClientApprovalDtl();

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

	/*******************************************************
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 * Date : 07-02-2018
	 ********************************************************/
	public function index()
	{

		global $order, $models;

		$user_id               = defined('USERID') ? USERID : '0';
		$division_id   	       = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids        = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : array();
		$role_ids              = defined('ROLE_IDS') ? ROLE_IDS : array();
		$equipment_type_ids    = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
		$showOrderDateCalender = $models->hasBackDateBookingEnabledInDepartment();

		return view('sales.order_master.index', ['title' => 'Orders', '_orders' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids, 'showOrderDateCalender' => $showOrderDateCalender]);
	}

	/*********************************************************
	 * Get orders detail.
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 **********************************************************/
	public function getOrders(Request $request)
	{

		global $order, $models;

		$error	 = '0';
		$message = '';
		$data	 = '';

		$orderObj = DB::table('order_master')
			->join('divisions', 'divisions.division_id', 'order_master.division_id')
			->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
			->join('city_db', 'city_db.city_id', 'order_master.customer_city')
			->join('users as createdBy', 'createdBy.id', 'order_master.created_by')
			->join('product_master', 'product_master.product_id', 'order_master.product_id')
			->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
			->join('order_status', 'order_status.order_status_id', 'order_master.status')
			->leftJoin('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
			->leftJoin('stb_order_hdr_dtl_detail', function ($join) {
				$join->on('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id', '=', 'order_master.stb_order_hdr_detail_id');
				$join->whereNotNull('order_master.stb_order_hdr_detail_id');
			})
			->leftJoin('invoice_hdr_detail', function ($join) {
				$join->on('invoice_hdr_detail.order_id', '=', 'order_master.order_id');
				$join->where('invoice_hdr_detail.invoice_hdr_status', '1');
			})
			->leftJoin('customer_hold_dtl', function ($join) {
				$join->on('customer_hold_dtl.chd_customer_id', '=', 'customer_master.customer_id');
				$join->whereRaw('customer_hold_dtl.chd_id IN (SELECT MAX(chd.chd_id) FROM customer_hold_dtl chd INNER JOIN customer_master cm ON chd.chd_customer_id = cm.customer_id GROUP BY chd.chd_customer_id)');
			})
			->leftJoin('order_linked_trf_dtl', 'order_linked_trf_dtl.oltd_order_id', 'order_master.order_id')
			->leftJoin('stb_order_hdr', 'stb_order_hdr.stb_order_hdr_id', 'stb_order_hdr_dtl_detail.stb_order_hdr_id');

		//Assigning Condition according to the Role Assigned
		parse_str($request->formData, $formData);

		$this->setConditionAccordingToRoleAssigned($orderObj, $formData);
		$this->getOrdersMultiSearch($orderObj, $formData);

		$orderObj->select('order_master.status', 'order_master.order_id', 'stb_order_hdr.stb_prototype_no', 'order_master.order_no', 'order_master.order_date', 'order_master.sample_description_id', 'order_master.batch_no', 'order_master.remarks', 'order_master.expected_due_date', 'customer_master.customer_code', 'customer_master.customer_name', 'customer_master.customer_status', 'order_status.order_status_name', 'order_status.color_code', 'order_sample_priority.sample_priority_name', 'divisions.division_name', 'createdBy.name as createdByName', 'product_master_alias.c_product_name as sample_description', 'city_db.city_name as customer_city', 'product_master.product_name', 'invoice_hdr_detail.invoice_dtl_id as invoice_generated_id', 'order_linked_trf_dtl.oltd_id', 'customer_hold_dtl.chd_hold_description');
		$orderObj->orderBy('order_master.order_id', 'DESC');
		$orderList = $orderObj->get();

		//to formate created and updated date
		$models->formatTimeStampFromArray($orderList, DATETIMEFORMAT);

		return response()->json(array('error' => $error, 'message' => $message, 'orderList' => $orderList));
	}

	/***********************************************
	 * functions to generate orders excel and pdf
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 * Created On : 18-05-2018
	 ************************************************/
	public function generateBranchWiseOrderPdf(Request $request)
	{

		global $order, $models;

		$error		= '0';
		$message	= '';
		$data		= '';

		if ($request->isMethod('post') && !empty($request->generate_order_documents)) {

			//Assigning Condition according to the Role Assigned
			$formData = $request->all();

			$orderObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('order_parameters_detail', 'order_parameters_detail.order_id', 'order_master.order_id')
				->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
				->leftJoin('equipment_type', 'equipment_type.equipment_id', 'order_parameters_detail.equipment_type_id')
				->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
				->join('city_db', 'city_db.city_id', 'order_master.customer_city')
				->join('users as createdBy', 'createdBy.id', 'order_master.created_by')
				->join('product_master', 'product_master.product_id', 'order_master.product_id')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('order_status', 'order_status.order_status_id', 'order_master.status')
				->leftJoin('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->leftJoin('stb_order_hdr_dtl_detail', function ($join) {
					$join->on('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id', '=', 'order_master.stb_order_hdr_detail_id');
					$join->whereNotNull('order_master.stb_order_hdr_detail_id');
				})
				->leftJoin('stb_order_hdr', 'stb_order_hdr.stb_order_hdr_id', 'stb_order_hdr_dtl_detail.stb_order_hdr_id');

			$this->setConditionAccordingToRoleAssigned($orderObj, $formData);
			$this->getOrdersMultiSearch($orderObj, $formData);

			$orderObj->select('order_master.order_no', 'divisions.division_name as branch', 'stb_order_hdr.stb_prototype_no', 'customer_master.customer_code', 'customer_master.customer_name', 'city_db.city_name as place', 'order_master.order_date', 'order_master.expected_due_date', 'product_master_alias.c_product_name as sample_description', 'product_master.product_name as sample_description', 'order_master.batch_no', 'order_sample_priority.sample_priority_name', 'order_master.remarks', 'order_status.order_status_name as status', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'createdBy.name as created_by');
			$orderObj->orderBy('order_master.order_date', 'DESC');
			$order = $orderObj->get();

			//to formate created and updated date
			$models->formatTimeStampFromArrayExcel($order, DATEFORMATEXCEL);

			if (!empty($order)) {
				$order 				= !empty($order) ? json_decode(json_encode($order), true) : array();
				$order 				= $models->unsetFormDataVariablesArray($order, array('canDispatchOrder', '_token'));
				$response['heading'] 		= 'Orders-List' . '(' . count($order) . ')';
				$response['tableHead'] 		= !empty($order) ? array_keys(end($order)) : array();
				$response['tableBody'] 		= !empty($order) ? $order : array();
				$response['tablefoot']		= array();
				$response['mis_report_name']  	= 'order_document';

				if ($request->generate_order_documents == 'PDF') {
					$pdfHeaderContent  		= $models->getHeaderFooterTemplate();
					$response['header_content']	= $pdfHeaderContent->header_content;
					$response['footer_content']	= $pdfHeaderContent->footer_content;
					return $models->downloadPDF($response, $contentType = 'ordersheet');
				} elseif ($request->generate_order_documents == 'Excel') {
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

	/******************************************************************
	 * functions to set conditions according to the users roles
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 *******************************************************************/
	public function setConditionAccordingToRoleAssigned($orderObj, $formData)
	{

		global $order, $models;

		$user_id        = defined('USERID') ? USERID : '0';
		$division_id   	= defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids       = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$divisionId     = !empty($formData['division_id']) ? $formData['division_id'] : $division_id;
		$orderDateFrom  = !empty($formData['order_date_from']) ? $formData['order_date_from'] : '0';
		$orderDateTo    = !empty($formData['order_date_to']) ? $formData['order_date_to'] : '0';
		$keyword        = !empty($formData['keyword']) ? trim($formData['keyword']) : '0';

		//Filtering records according to department assigned
		if (!empty($department_ids) && is_array($department_ids)) {
			$orderObj->whereIn('order_master.product_category_id', $department_ids);
		}
		//Filtering records according to division assigned
		if (!empty($divisionId) && is_numeric($divisionId)) {
			$orderObj->where('order_master.division_id', $divisionId);
		}
		//Filtering records according to from and to order date
		if (!empty($orderDateFrom) && !empty($orderDateTo)) {
			$orderObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($orderDateFrom, $orderDateTo));
		} else if (!empty($orderDateFrom) && empty($orderDateTo)) {
			$orderObj->where(DB::raw("DATE(order_master.order_date)"), '>=', $orderDateFrom);
		} else if (empty($orderDateFrom) && !empty($orderDateTo)) {
			$orderObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $orderDateTo);
		} else if (empty($keyword)) {
			$orderObj->whereDay('order_master.order_date', date('d'));
			$orderObj->whereMonth('order_master.order_date', date('m'));
			$orderObj->whereYear('order_master.order_date', date('Y'));
		}
		//If logged in User is Order Booker ID:4
		if (defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER) {
			$orderObj->where('order_master.created_by', $user_id);
		}
		//If logged in User ob Scheduler ID:5
		if (defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER) {
			$orderObj->join('schedulings', 'schedulings.order_id', 'order_master.order_id');
			$orderObj->where('schedulings.employee_id', $user_id);
			$orderObj->groupBy('schedulings.order_id');
		}
		//Filtering records according to search keyword
		if (!empty($keyword)) {
			$orderObj->where('order_master.order_no', '=', $keyword);
		}
	}

	/*************************
	 * Show Mulit search records
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function getOrdersMultiSearch($orderObj, $searchArry)
	{

		global $order, $models;

		if (!empty($searchArry['search_order_no'])) {
			$orderObj->where('order_master.order_no', 'LIKE', '%' . trim($searchArry['search_order_no']) . '%');
		}
		if (!empty($searchArry['search_division_id'])) {
			$orderObj->where('divisions.division_id', 'LIKE', '%' . trim($searchArry['search_division_id']) . '%');
		}
		if (!empty($searchArry['search_customer_name'])) {
			$orderObj->where('customer_master.customer_name', 'LIKE', '%' . trim($searchArry['search_customer_name']) . '%');
		}
		if (!empty($searchArry['search_order_date'])) {
			$orderObj->where(DB::raw("DATE(order_master.order_date)"), 'LIKE', '%' . $models->convertDateFormat(trim($searchArry['search_order_date'])) . '%');
		}
		if (!empty($searchArry['search_sample_description'])) {
			$orderObj->where('product_master_alias.c_product_name', 'LIKE', '%' . trim($searchArry['search_sample_description']) . '%');
		}
		if (!empty($searchArry['search_batch_no'])) {
			$orderObj->where('order_master.batch_no', 'LIKE', '%' . trim($searchArry['search_batch_no']) . '%');
		}
		if (!empty($searchArry['search_sample_priority_name'])) {
			$orderObj->where('order_sample_priority.sample_priority_name', 'LIKE', '%' . trim($searchArry['search_sample_priority_name']) . '%');
		}
		if (!empty($searchArry['search_remarks'])) {
			$orderObj->where('order_master.remarks', 'LIKE', '%' . trim($searchArry['search_remarks']) . '%');
		}
		if (!empty($searchArry['search_status'])) {
			$orderObj->where('order_status.order_status_name', 'LIKE', '%' . $searchArry['search_status'] . '%');
		}
		if (!empty($searchArry['search_created_by'])) {
			$orderObj->where('createdBy.name', 'LIKE', '%' . trim($searchArry['search_created_by']) . '%');
		}
		if (!empty($division_id)) {
			$orderObj->where('order_master.division_id', $division_id);
		}
		if (!empty($searchArry['search_stb_prototype_no'])) {
			$orderObj->where('stb_order_hdr.stb_prototype_no', 'LIKE', '%' . trim($searchArry['search_stb_prototype_no']) . '%');
		}
		if (!empty($searchArry['search_customer_code'])) {
			$orderObj->where('customer_master.customer_code', 'LIKE', '%' . trim($searchArry['search_customer_code']) . '%');
		}
		if (!empty($searchArry['search_customer_city'])) {
			$orderObj->where('city_db.city_name', 'LIKE', '%' . trim($searchArry['search_customer_city']) . '%');
		}
		if (!empty($searchArry['search_expected_due_date'])) {
			$orderObj->where(DB::raw("DATE(order_master.expected_due_date)"), 'LIKE', '%' . $models->convertDateFormat(trim($searchArry['search_expected_due_date'])) . '%');
		}
	}

	/*************************
	 * Get order log history.
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function getOrderLog($order_id)
	{

		global $order, $models;

		$error    = '0';
		$message  = '';
		$orderLogList  = array();

		if ($order_id) {
			$error        = '1';
			$orderLogList = $order->getOrderLogRecord($order_id);
		}
		//to formate created and updated date
		$models->formatTimeStampFromArray($orderLogList, DATETIMEFORMAT);

		return response()->json(array('error' => $error, 'message' => $message, 'orderLogList' => $orderLogList));
	}

	/*************************
	 * dispaly orders list
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function viewOrder(Request $request, $order_id)
	{

		global $order, $models, $orderClientApproval;

		$error    = '0';
		$message  = '';
		$data     = $orderList = $categoryWiseParamenter = $orderTracking = $categoryWiseParamenterArr = $orderTatInDayDetail = $orderClientApprovalList = array();

		if ($order_id) {

			$error              	 = '1';
			$user_id            	 = defined('USERID') ? USERID : '0';
			$equipment_type_ids 	 = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
			$role_ids          		 = defined('ROLE_IDS') ? ROLE_IDS : '0';
			$orderList               = $order->getOrder($order_id);
			$testProductStdParaList  = defined('IS_TESTER') && IS_TESTER ? $order->getAsssignedOrderParameters($order_id, $user_id) : $order->getOrderParameters($order_id);
			$orderTracking 	   	     = $order->getOrderTrackRecord($order_id);	//Get order tracking stages
			$orderClientApprovalList = $orderClientApproval->getOrderClientApprovalDetail($order_id);	//Get Order Client Approval Process Detail

			//to formate order and Report date
			$models->formatTimeStamp($orderList, DATETIMEFORMAT);

			$rawTestProductStdParaList = array();
			if (!empty($testProductStdParaList)) {
				$counter = '1';
				foreach ($testProductStdParaList as $key => $values) {

					$values->counter = $counter++;

					//checking if desccription has been edited or not
					$allowedExceptionParameters = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)');
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
					$orderTatInDayDetail[$values->time_taken_days]   = !empty($values->time_taken_days) ? trim($values->time_taken_days) : '0';
				}
			}

			if (!empty($rawTestProductStdParaList)) {
				foreach ($rawTestProductStdParaList as $key => $values) {
					$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']   = $values->category_sort_by;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']       = $values->test_para_cat_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     = $values->test_para_cat_name;
					$categoryWiseParamenter[$values->test_para_cat_id]['productCatID']     = $values->p_category_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['testId']     	   = $values->test_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['testCode']     	   = $values->test_code;
					$categoryWiseParamenter[$values->test_para_cat_id]['productId']        = $values->product_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['productName']      = $values->product_name;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] = $values;
				}
			}

			$orderList->po_date   				= !empty($orderList->po_date) ? date(DATEFORMAT, strtotime($orderList->po_date)) : '';
			$orderList->canHoldUnholdOrder  	= DB::table('order_process_log')->where('order_process_log.opl_order_id', $order_id)->whereNotIn('order_process_log.opl_order_status_id', array('1', '12'))->count();
			$orderList->maxTatInDayNumber		= !empty($orderTatInDayDetail) ? max($orderTatInDayDetail) : '0';
			$orderList->totalOrderParameters	= !empty($testProductStdParaList) ? count($testProductStdParaList) : '0';
			$orderList->clientApprovalList 		= !empty($orderClientApprovalList) ? $orderClientApprovalList : false;
			$orderList->isRoleAdministrator	    = (defined('IS_ADMIN') && IS_ADMIN) ? true : false;
			$categoryWiseParamenterArr 			= !empty($categoryWiseParamenter) ? $models->sortArrayAscOrder(array_values($categoryWiseParamenter)) : array();       //sort category array
		}

		return response()->json(array('error' => $error, 'message' => $message, 'orderList' => $orderList, 'orderParameterList' => $categoryWiseParamenterArr, 'orderTrackingList' => $orderTracking));
	}

	/*************************
	 * save new order /create new order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function createOrder(Request $request)
	{

		global $order, $models;

		$error       	 	 = '0';
		$message     	 	 = config('messages.message.OrderInternalErrorMsg');
		$data        	 	 = '';
		$customerId  	 	 = '0';
		$sampleId    	 	 = '0';
		$currentDate     	 = defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('d-m-Y');
		$currentDateTime 	 = defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
		$formData    	 	 = $poConnectivityData = array();
		$previousOrderDetail = '';
		$previousOrderCount  = '';
		$finalTypeSave 		 = '';

		try {

			//Saving record in orders table
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing the Serialze Dta
				parse_str($request->formData, $formData);

				if (!empty($formData)) {
					if (empty($formData['sample_id'])) {
						$message = config('messages.message.sampleReceivingCodeRequired');
					} else if (!$order->checkSampleAndTestProductCategory($formData['sample_id'], $models->getMainProductCatParentId($formData['product_category_id']))) {
						$message = config('messages.message.mismatchSampleAndTestProductCategory');
					} else if (empty($formData['customer_id'])) {
						$message = config('messages.message.customerNameRequired');
					} else if (empty($formData['customer_city'])) {
						$message = config('messages.message.customerCityRequired');
					} else if (empty($formData['mfg_lic_no'])) {
						$message = config('messages.message.customerLicNumRequired');
					} else if (empty($formData['billing_type_id'])) {
						$message = config('messages.message.billingTypeRequired');
					} else if (empty($formData['invoicing_type_id'])) {
						$message = config('messages.message.invoicingTypeRequired');
					} else if (empty($formData['sale_executive'])) {
						$message = config('messages.message.saleExecutiveRequired');
					} else if (empty($formData['discount_type_id'])) {
						$message = config('messages.message.discountTypeRequired');
					} else if ($formData['discount_type_id'] != '3' && empty($formData['discount_value'])) {
						$message = config('messages.message.discountValueRequired');
					} else if (empty($formData['division_id'])) {
						$message = config('messages.message.divisionNameRequired');
					} else if (empty($formData['order_date'])) {
						$message = config('messages.message.OrderDateErrorMsg');
					} else if (!$order->isValidDate($formData['order_date'])) {
						$message = config('messages.message.OrderInValidOrderDateMsg');
					} else if (empty($formData['sample_description'])) {
						$message = config('messages.message.sampleDescriptionRequired');
					} else if (empty($formData['batch_no'])) {
						$message = config('messages.message.batchNoRequired');
					} else if (empty($formData['brand_type'])) {
						$message = config('messages.message.brandTypeRequired');
					} else if (isset($formData['is_sealed']) && is_null($formData['is_sealed'])) {
						$message = config('messages.message.isSealedRequired');
					} else if (isset($formData['is_signed']) && is_null($formData['is_signed'])) {
						$message = config('messages.message.isSignedRequired');
					} else if (empty($formData['packing_mode'])) {
						$message = config('messages.message.packingModeRequired');
					} else if (empty($formData['submission_type'])) {
						$message = config('messages.message.submissionTypeRequired');
					} else if (!$order->checkBookingAndSampleReceivingDate($formData['order_date'], $formData['sample_id'])) {
						$message = config('messages.message.orderDateSampleReceDateMismatch');
					} else if (isset($formData['sampling_date']) && !$order->checkBookingAndSamplingDate($formData['order_date'], $formData['sampling_date'])) {
						$message = config('messages.message.orderDateSamplingDateMismatch');
					} else if ($order->claimValueValidation($formData['order_parameters_detail'])) {
						$message = config('messages.message.claimValueErrorMsg');
					} else if ($order->claimUnitValidation($formData['order_parameters_detail'])) {
						$message = config('messages.message.claimValueUnitErrorMsg');
					} else if (empty($formData['order_parameters_detail'])) {
						$message = config('messages.message.OrderProductTestParamsMsg');
					} else if (isset($formData['header_note']) && empty($formData['header_note'])) {
						$message = config('messages.message.headerNoteRequired');
					} else if (isset($formData['stability_note']) && empty($formData['stability_note'])) {
						$message = config('messages.message.stabilityIdRequired');
					} else if (empty($formData['sample_priority_id'])) {
						$message = config('messages.message.samplePriorityIdRequired');
					} else if (!empty($formData['billing_type_id']) && $formData['billing_type_id'] == '5' && empty($formData['po_no'])) {
						$message = config('messages.message.samplePoNoRequired');
					} else if (!empty($formData['billing_type_id']) && $formData['billing_type_id'] == '5' && empty($formData['po_date'])) {
						$message = config('messages.message.samplePoDateRequired');
					} else if (!empty($formData['billing_type_id']) && $formData['billing_type_id'] == '5' && !$order->validatePODate($formData['po_date'])) {
						$message = config('messages.message.validSamplePoDateRequired');
					} else if (isset($formData['sample_type']) && empty($formData['order_sample_type'])) {
						$message = config('messages.message.sampleTypeRequired');
					} else if (isset($formData['hold_type']) && empty($formData['hold_reason'])) {
						$message = config('messages.message.sampleHoldTypeRequired');
					} else if (isset($formData['invoicing_reporting_type']) && empty($formData['reporting_to']) && empty($formData['invoicing_to'])) {
						$message = config('messages.message.reportingToOrInvoicingToRequired');
					} else if (!empty($formData['order_parameters_detail']) && !$order->validateDecimalValueOnAdd($formData['order_parameters_detail'])) {
						$message = config('messages.message.decimalPlaceValueError');
					} else if (!empty($formData['order_parameters_detail']) && !$order->runningTimeValidation($formData['order_parameters_detail'])) {
						$message = config('messages.message.runningTimeRequiredErrorMsg');
					} else if (!empty($formData['order_parameters_detail']) && !$order->noOfInjectionValidation($formData['order_parameters_detail'])) {
						$message = config('messages.message.noOfInjectionRequiredErrorMsg');
					} else if (!empty($formData['order_parameters_detail']) && !$order->checkAddDTLimitValidation($formData['order_parameters_detail'])) {
						$message = config('messages.message.disintegrationTimeRequiredErrorMsg');
					} else if (!empty($formData['order_parameters_detail']) && !$order->validateNablScopeBackDateBookingOnAdd($formData, $currentDate)) {
						$message = config('messages.message.nablScopeBackDateBookingError');
					} else if (isset($formData['tat_in_days']) && (empty($formData['tat_in_days']) || !is_numeric($formData['tat_in_days']))) {
						$message = config('messages.message.tatInDaysRequiredErrorMsg');
					} else if (isset($formData['order_field_name']) && (empty($formData['order_field_name']) || count($formData['order_field_name']) != count(array_filter($formData['order_field_name'])))) {
						$message = config('messages.message.orderFieldNameErrorRequired');
					} else if (isset($formData['order_field_value']) && (empty($formData['order_field_value']) || count($formData['order_field_value']) != count(array_filter($formData['order_field_value'])))) {
						$message = config('messages.message.orderFieldValueErrorRequired');
					} else if (!empty($formData['client_approval_needed']) && count(array_filter(array($formData['ocad_approved_by'], $formData['ocad_date'], $formData['ocad_credit_period'], $formData['ocad_date_upto_amt']))) != '4') {
						$message = config('messages.message.clientApprovalFieldsError');
					} else if (empty($formData['sample_condition'])) {
						$message = config('messages.message.sampleConditionErrorRequired');
					} else {

						//Starting transaction
						DB::beginTransaction();

						//Getting Dynamic Field Name/Value Array
						$dynamicFieldNameValueArray = array_filter(['order_field_name' => !empty($formData['order_field_name']) ? $formData['order_field_name'] : NULL, 'order_field_value' => !empty($formData['order_field_value']) ? $formData['order_field_value'] : NULL]);

						//Sending customer name to the response for save more button
						$customerId    				= !empty($formData['customer_id']) ? $formData['customer_id'] : '0';
						$sampleId      				= !empty($formData['sample_id']) ? $formData['sample_id'] : '0';
						$finalTypeSave 				= !empty($formData['final_type_save']) ? $formData['final_type_save'] : '0';
						$duplicateOrder 			= !empty($formData['duplicate_save']) ? $formData['duplicate_save'] : '0';
						$poConnectivityData 		= array('customer_id' => !empty($formData['customer_id']) ? $formData['customer_id'] : '', 'customer_city' => !empty($formData['customer_city']) ? $formData['customer_city'] : '', 'po_no' => !empty($formData['po_no']) ? $formData['po_no'] : '', 'po_date' => !empty($formData['po_date']) ? $formData['po_date'] : '',);
						$clientApprovalFieldArray   = !empty($formData['client_approval_needed']) ? array('ocad_approved_by' => $formData['ocad_approved_by'], 'ocad_date' => $formData['ocad_date'], 'ocad_credit_period' => $formData['ocad_credit_period'], 'ocad_date_upto_amt' => $formData['ocad_date_upto_amt']) : array();

						//Setting the variable from request data
						$formData['order_date']    			= $order->getFormatedDateTime($formData['order_date'], $format = 'Y-m-d');
						$formData['booking_date']    		= $currentDateTime;
						$formData['sampling_date'] 			= !empty($formData['sampling_date']) && !empty($formData['sampling_date']) ? $order->getFormatedDate($formData['sampling_date'], $format = 'Y-m-d H:i:s') : NULL;
						$formData['product_category_id'] 	= $models->getMainProductCatParentId($formData['product_category_id']);
						$formData['order_no']      			= $order->generateOrderNumber($formData);
						$formData['barcode']				= 'data:image/png;base64,' . DNS1D::getBarcodePNG($formData['order_no'], 'C128');
						$formData['sample_description_id']  = $order->createAliaAndUpdateOrderSampleName($request->formData);
						$formData['header_note']  			= !empty($formData['header_note']) ? $formData['header_note'] : NULL;
						$formData['stability_note']  		= !empty($formData['stability_note']) ? $order->createAndUpdateStabilityNote($formData['stability_note']) : '';
						$formData['created_by']    			= USERID;
						$formData['billing_type_id']        = $formData['billing_type_id'];
						$formData['status'] 				= isset($formData['hold_type']) && !empty($formData['hold_reason']) ? '12' : '1';
						$formData['po_no'] 					= isset($formData['po_type']) && !empty($formData['po_no']) ? $formData['po_no'] : NULL;
						$formData['po_date'] 				= isset($formData['po_type']) && !empty($formData['po_date']) ? $order->getFormatedDateTime($formData['po_date'], $format = 'Y-m-d') : NULL;
						$formData['reporting_to']  			= isset($formData['invoicing_reporting_type']) && !empty($formData['reporting_to']) ? $formData['reporting_to'] : NULL;
						$formData['invoicing_to']  			= isset($formData['invoicing_reporting_type']) && !empty($formData['invoicing_to']) ? $formData['invoicing_to'] : NULL;
						$formData['discount_value']  		= !empty($formData['discount_value']) ? $formData['discount_value'] : NULL;
						$formData['defined_test_standard'] 	= !empty($formData['defined_test_standard']) ? $formData['defined_test_standard'] : NULL;
						$formData['sampler_id'] 			= !empty($formData['sampler_id']) ? $formData['sampler_id'] : NULL;

						//Checking Dulplicate Reords using customer Name,Sample Name and batch No
						$previousOrderDetail 				= $this->getPreviousOrderDetail($customerId, $sampleId, $formData);

						if (empty($previousOrderDetail) || !empty($duplicateOrder)) {

							//Unsetting the variable from request data
							$formData = $models->unsetFormDataVariables($formData, array('_token', 'sample_description', 'test_param_alternative_id', 'order_parameters_detail', 'final_type_save', 'po_type', 'sample_type', 'hold_type', 'invoicing_reporting_type', 'duplicate_save', 'order_field_name', 'order_field_value', 'client_approval_needed', 'ocad_approved_by', 'ocad_date', 'ocad_credit_period', 'ocad_date_upto_amt'));

							if (!empty($formData['order_no']) && !empty($formData['sample_description_id'])) {
								if (!empty($order->checkAddCustomerInvoivingRate($formData['sample_description_id'], $request->formData))) {

									//Saving the Order data in Order Master
									$orderId = DB::table('order_master')->insertGetId($formData);

									//Saving the Order Related Data in others table
									$this->save_order_parameter_detail($request->formData, $orderId, $finalTypeSave, $formData['status']);

									//Updating Order Status based on Customer Hold Status if Client Approval is not checked
									empty($clientApprovalFieldArray) ? $order->updateOrderStatusOnCustomerHoldStatus($orderId, $formData) : '';

									//Connecting Uploaded PO with teh Central Location PO Detail
									!empty($formData['po_no']) ? $order->connectUpdatePoWithCentralPoLoc($poConnectivityData, $orderId) : '';

									//Saving Dynamic Field Name/Value Array in order_dynamic_field_dtl tables
									$order->save_order_dynamic_field_detail($dynamicFieldNameValueArray, $orderId);

									//Saving Client Approval Detail
									!empty($clientApprovalFieldArray) ? $order->updateOrderClientApprovalProcessDetail($clientApprovalFieldArray, $orderId) : '';

									//Updating Order Booked Amount
									$order->updateBookingSampleAmount($orderId, $formData);

									//Calculating Price Variation between Sample Amount and ITC Standard Price List
									$order->__calculatePriceVariationBetSampleAmtAndItcStdPrice($orderId);

									//Updating Analysat Window Setting in order_analyst_window_settings table
									$order->__updateReportAnalystWindowUISettings($orderId);

									$error   = '1';
									$data    = $orderId;
									$message = config('messages.message.OrderPlacedMsg');

									//Committing the queries
									DB::commit();
								} else {
									$message = config('messages.message.InvocingTypeRequired');
								}
							}
						} else {
							$error = '0';
							$message = '';
							$previousOrderCount = count($previousOrderDetail);
							$data = $previousOrderDetail;
						}
					}
				}
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			DB::rollback();
			$message = config('messages.message.OrderInternalErrorMsg');
		} catch (\Throwable $e) {
			DB::rollback();
			$message = config('messages.message.OrderInternalErrorMsg');
		}

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'customer_id' => $customerId, 'sample_id' => $sampleId, 'currentDate' => $currentDate, 'previousOrderCount' => $previousOrderCount, 'finalTypeSave' => $finalTypeSave]);
	}

	/****
	 *** for order duplicacy check
	 **** Get previous order acording to selected customer
	 ***
	 ****/
	function getPreviousOrderDetail($customerId, $sampleId, $formData)
	{

		global $order, $models;

		$previousOrderDetail = DB::table('order_master')
			->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
			->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
			->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
			->where('order_master.customer_id', '=', $customerId)
			->where('order_master.batch_no', '=', $formData['batch_no'])
			->where('order_master.sample_description_id', '=', $formData['sample_description_id'])
			->select('product_master_alias.c_product_name', DB::raw('CONCAT(customer_master.customer_name,"/",city_db.city_name) AS customer_name'), 'order_master.order_no', 'order_master.batch_no', 'order_master.order_date')
			->get()
			->toArray();

		$models->formatTimeStampFromArray($previousOrderDetail, DATETIMEFORMAT);

		return $previousOrderDetail;
	}

	/*************************
	 * Save order parameters details on add order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function save_order_parameter_detail($product_test_dtl_data_raw, $orderId, $finalTypeSave, $orderStatus)
	{

		global $order, $models, $mail;

		$orderParametersDataSave = array();

		if (!empty($product_test_dtl_data_raw) && !empty($orderId)) {

			//Parsing the form Data
			parse_str($product_test_dtl_data_raw, $product_test_dtl_data);

			if (!empty($product_test_dtl_data['order_parameters_detail'])) {
				if (!empty($product_test_dtl_data['order_parameters_detail']['claim_dependent'])) {
					unset($product_test_dtl_data['order_parameters_detail']['claim_dependent']);
				}
				if (!empty($product_test_dtl_data['order_parameters_detail']['cwap_invoicing_required'])) {
					unset($product_test_dtl_data['order_parameters_detail']['cwap_invoicing_required']);
				}
				if (!empty($product_test_dtl_data['order_parameters_detail']['test_parameter_invoicing_parent_id'])) {
					unset($product_test_dtl_data['order_parameters_detail']['test_parameter_invoicing_parent_id']);
				}
				foreach ($product_test_dtl_data['order_parameters_detail'] as $keyParameter => $orderParametersData) {
					foreach ($orderParametersData as $key => $orderParameters) {
						$orderParameters = isset($orderParameters) && strlen($orderParameters) > 0 ? trim($orderParameters) : null;
						$orderParametersDataSave[$key]['order_id']    = $orderId;
						$orderParametersDataSave[$key][$keyParameter] = $orderParameters;
					}
				}
				if (!empty($orderParametersDataSave) && DB::table('order_parameters_detail')->insert($orderParametersDataSave)) {

					//Generation of Expected Due Date and saving to the order master table
					$order->generateUpdateOrderExpectedDueDate_v3($orderId);

					//Adding Order Parameter in Scheduling table
					$order->createOrderSchedulingJobs($orderId);

					//Saving/Updating Report Due Date and Department Due date in Order Parameter table
					$order->updateReportDepartmentDueDate($orderId);

					//Updating Sample Status of booked Order in samples table
					if ($finalTypeSave) {
						$order->UpdateSampleStatusOfBookedSample($orderId);
					}

					//Insert Discipline Name and Group Name in order_discipline_group_dtl
					$order->insertUpdateDisciplineGroupDetail($orderId);

					//Updating order progress log  table at the time of booking
					$order->updateOrderStausLog($orderId, $orderStatus);
				}
			}
		}
	}

	/*************************
	 * update order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function updateOrder(Request $request)
	{

		global $order, $models;

		$error      	= '0';
		$message    	= config('messages.message.OrderInternalErrorMsg');
		$data       	= '';
		$orderId    	= '0';
		$status     	= '1';
		$formData   	= $poConnectivityData = array();
		$logStatusFlag 	= '0';

		try {

			//Saving record in orders table
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing Serialize form data
				parse_str($request->formData, $formData);

				if (!empty($formData)) {

					//Extracting Order Parameter Detail
					$orderParameter     		= !empty($formData['order_parameters_detail']) ? $formData['order_parameters_detail'] : '';
					$orderId  	    			= !empty($formData['order_id']) ? $formData['order_id'] : '0';
					$isOrderInvoiced    		= $order->isOrderInvoiceGenerated($orderId);
					$poConnectivityData 		= array('customer_id' => !empty($formData['customer_id']) ? $formData['customer_id'] : '', 'customer_city' => !empty($formData['customer_city']) ? $formData['customer_city'] : '', 'po_no' => !empty($formData['po_no']) ? $formData['po_no'] : '', 'po_date' => !empty($formData['po_date']) ? $formData['po_date'] : '',);
					$clientApprovalFieldArray   = !empty($formData['client_approval_needed']) ? array('ocad_approved_by' => $formData['ocad_approved_by'], 'ocad_date' => $formData['ocad_date'], 'ocad_credit_period' => $formData['ocad_credit_period'], 'ocad_date_upto_amt' => $formData['ocad_date_upto_amt']) : array();

					if (empty($formData['customer_id'])) {
						$message = config('messages.message.customerNameRequired');
					} else if (empty($formData['customer_city'])) {
						$message = config('messages.message.customerCityRequired');
					} else if (empty($formData['mfg_lic_no'])) {
						$message = config('messages.message.customerLicNumRequired');
					} else if (empty($formData['billing_type_id'])) {
						$message = config('messages.message.billingTypeRequired');
					} else if (empty($formData['invoicing_type_id'])) {
						$message = config('messages.message.invoicingTypeRequired');
					} else if (empty($formData['sale_executive'])) {
						$message = config('messages.message.saleExecutiveRequired');
					} else if (empty($formData['discount_type_id'])) {
						$message = config('messages.message.discountTypeRequired');
					} else if ($formData['discount_type_id'] != '3' && empty($formData['discount_value'])) {
						$message = config('messages.message.discountValueRequired');
					} else if (empty($formData['division_id'])) {
						$message = config('messages.message.divisionNameRequired');
					} else if (empty($formData['order_date'])) {
						$message = config('messages.message.OrderDateErrorMsg');
					} else if (empty($formData['sample_description'])) {
						$message = config('messages.message.sampleDescriptionRequired');
					} else if (empty($formData['batch_no'])) {
						$message = config('messages.message.batchNoRequired');
					} else if (empty($formData['brand_type'])) {
						$message = config('messages.message.brandTypeRequired');
					} else if (isset($formData['is_sealed']) && is_null($formData['is_sealed'])) {
						$message = config('messages.message.isSealedRequired');
					} else if (isset($formData['is_signed']) && is_null($formData['is_signed'])) {
						$message = config('messages.message.isSignedRequired');
					} else if (empty($formData['packing_mode'])) {
						$message = config('messages.message.packingModeRequired');
					} else if (empty($formData['submission_type'])) {
						$message = config('messages.message.submissionTypeRequired');
					} else if (empty($formData['sample_priority_id'])) {
						$message = config('messages.message.samplePriorityIdRequired');
					} else if ($order->claimValueValidationOnEdit($orderParameter)) {
						$message = config('messages.message.claimValueErrorMsg');
					} else if ($order->claimUnitValidationOnEdit($orderParameter)) {
						$message = config('messages.message.claimValueUnitErrorMsg');
					} else if (isset($formData['header_note']) && empty($formData['header_note'])) {
						$message = config('messages.message.headerNoteRequired');
					} else if (empty($isOrderInvoiced) && isset($formData['po_type']) && $formData['billing_type_id'] == '5' && empty($formData['po_no'])) {
						$message = config('messages.message.samplePoNoRequired');
					} else if (empty($isOrderInvoiced) && isset($formData['po_type']) && $formData['billing_type_id'] == '5' && empty($formData['po_date'])) {
						$message = config('messages.message.samplePoDateRequired');
					} else if (empty($isOrderInvoiced) && isset($formData['po_type']) && $formData['billing_type_id'] == '5' && !$order->validatePODate($formData['po_date'])) {
						$message = config('messages.message.validSamplePoDateRequired');
					} else if (isset($formData['sample_type']) && empty($formData['order_sample_type'])) {
						$message = config('messages.message.sampleTypeRequired');
					} else if (isset($formData['hold_type']) && empty($formData['hold_reason'])) {
						$message = config('messages.message.sampleHoldTypeRequired');
					} else if (isset($formData['invoicing_reporting_type']) && empty($formData['reporting_to']) && empty($formData['invoicing_to'])) {
						$message = config('messages.message.reportingToOrInvoicingToRequired');
					} else if (!empty($formData['order_parameters_detail']) && !$order->validateDecimalValueOnEdit($formData['order_parameters_detail'])) {
						$message = config('messages.message.decimalPlaceValueError');
					} else if (!empty($formData['order_parameters_detail']) && !$order->runningTimeEditValidation($formData['order_parameters_detail'])) {
						$message = config('messages.message.runningTimeRequiredErrorMsg');
					} else if (!empty($formData['order_parameters_detail']) && !$order->noOfInjectionEditValidation($formData['order_parameters_detail'])) {
						$message = config('messages.message.noOfInjectionRequiredErrorMsg');
					} else if (!empty($formData['order_parameters_detail']) && !$order->checkEditDTLimitValidation($formData['order_parameters_detail'])) {
						$message = config('messages.message.disintegrationTimeRequiredErrorMsg');
					} else if (isset($formData['tat_in_days']) && (empty($formData['tat_in_days']) || !is_numeric($formData['tat_in_days']))) {
						$message = config('messages.message.tatInDaysRequiredErrorMsg');
					} else if (isset($formData['order_field_name']) && (empty($formData['order_field_name']) || count($formData['order_field_name']) != count(array_filter($formData['order_field_name'])))) {
						$message = config('messages.message.orderFieldNameErrorRequired');
					} else if (isset($formData['order_field_value']) && (empty($formData['order_field_value']) || count($formData['order_field_value']) != count(array_filter($formData['order_field_value'])))) {
						$message = config('messages.message.orderFieldValueErrorRequired');
					} else if (!empty($formData['client_approval_needed']) && count(array_filter(array($formData['ocad_approved_by'], $formData['ocad_date'], $formData['ocad_credit_period'], $formData['ocad_date_upto_amt']))) != '4') {
						$message = config('messages.message.clientApprovalFieldsError');
					} else if (empty($formData['sample_condition'])) {
						$message = config('messages.message.sampleConditionErrorRequired');
					} else {

						//Starting transaction
						DB::beginTransaction();

						//Error Msg if order has not been placed.
						$message = config('messages.message.OrderNotUpdatedMsg');

						//Extracting Order Parameter Detail
						$orderParameter = !empty($formData['order_parameters_detail']) ? $formData['order_parameters_detail'] : '';

						//Condition for Re-calculation of Expected Due Date in case of TAT Days
						$isCheckedTATInDays = !empty($formData['is_checked_tat_in_days']) ? '1' : '0';

						//Getting Dynamic Field Name/Value Array
						$dynamicFieldNameValueArray = array_filter(['order_field_name' => !empty($formData['order_field_name']) ? $formData['order_field_name'] : NULL, 'order_field_value' => !empty($formData['order_field_value']) ? $formData['order_field_value'] : NULL]);

						//Setting order Master form data
						$formData['advance_details'] 		= !empty($formData['advance_details']) ? $formData['advance_details'] : '';
						$formData['advance_details'] 		= $formData['submission_type'] > '1' ? '' : $formData['advance_details'];
						$formData['sample_priority_id'] 	= !empty($formData['sample_priority_id']) ? $formData['sample_priority_id'] : NULL;
						$formData['sample_description_id']  = $order->updateAliasOnUpdateOrderSampleName($orderId, $request->formData);
						$formData['header_note']  			= !empty($formData['header_note']) ? $formData['header_note'] : NULL;
						$formData['sampling_date'] 			= !empty($formData['sampling_date']) ? $order->getFormatedDate($formData['sampling_date'], $format = 'Y-m-d H:i:s') : NULL;
						$formData['po_no'] 					= isset($formData['po_type']) && !empty($formData['po_no']) ? $formData['po_no'] : NULL;
						$formData['po_date'] 				= isset($formData['po_type']) && !empty($formData['po_date']) ? $order->getFormatedDateTime($formData['po_date'], $format = 'Y-m-d') : NULL;
						$formData['order_sample_type']		= isset($formData['sample_type']) && !empty($formData['order_sample_type']) ? $formData['order_sample_type'] : NULL;
						$formData['reporting_to']  			= isset($formData['invoicing_reporting_type']) && !empty($formData['reporting_to']) ? $formData['reporting_to'] : NULL;
						$formData['invoicing_to']  			= isset($formData['invoicing_reporting_type']) && !empty($formData['invoicing_to']) ? $formData['invoicing_to'] : NULL;
						$formData['discount_value']  		= !empty($formData['discount_value']) ? $formData['discount_value'] : NULL;
						$formData['defined_test_standard'] 	= !empty($formData['defined_test_standard']) ? $formData['defined_test_standard'] : NULL;
						$formData['sampler_id'] 			= !empty($formData['sampler_id']) ? $formData['sampler_id'] : NULL;

						//If Hold Checkbox checked by the User
						if (isset($formData['hold_type']) && !empty($formData['hold_reason'])) {
							$formData['status'] = '12';
							$status 			= '12';
							$logStatusFlag 		= '1';
						}

						//Unsetting the variable from request data
						if (!empty($isOrderInvoiced) || empty($formData['po_type'])) {
							$dataUnsetUpdateColumn = array('_token', 'sample_description', 'order_id', 'order_date', 'product_id', 'product_test_id', 'order_parameters_detail', 'test_standard', 'po_type', 'po_no', 'po_date', 'sample_type', 'invoicing_reporting_type', 'hold_type', 'is_checked_tat_in_days', 'order_field_name', 'order_field_value', 'client_approval_needed', 'ocad_approved_by', 'ocad_date', 'ocad_credit_period', 'ocad_date_upto_amt');
						} else {
							$dataUnsetUpdateColumn = array('_token', 'sample_description', 'order_id', 'order_date', 'product_id', 'product_test_id', 'order_parameters_detail', 'test_standard', 'po_type', 'sample_type', 'invoicing_reporting_type', 'hold_type', 'is_checked_tat_in_days', 'order_field_name', 'order_field_value', 'ocad_approved_by', 'client_approval_needed', 'ocad_date', 'ocad_credit_period', 'ocad_date_upto_amt');
						}
						$formData = $models->unsetFormDataVariables($formData, $dataUnsetUpdateColumn);

						if (!empty($orderId) && !empty($formData['sample_description_id'])) {
							if (!empty($order->checkEditCustomerInvoivingRate($formData['sample_description_id'], $request->formData))) {

								//Updating Sample Receiving Customer_id ID if User changes the Customer Name of Booked Order
								$order->updateCustomerDetailInSamplesAOrderMaster($formData, $orderId);

								//Updating Order Master
								DB::table('order_master')->where('order_id', '=', $orderId)->update($formData);

								//Updating Oder Parameter Detail
								!empty($orderParameter) ? $this->updateOrderParameterDetails($orderId, $orderParameter, $isCheckedTATInDays) : '';

								//Updating order progress log table at the time of editing an order
								!empty($logStatusFlag) ? $order->updateOrderStausLog($orderId, $status) : $order->updateOrderLog($orderId, $status);

								//Connecting Uploaded PO with teh Central Location PO Detail
								!empty($formData['po_no']) ? $order->connectUpdatePoWithCentralPoLoc($poConnectivityData, $orderId) : '';

								//Insert Discipline Name and Group Name in order_discipline_group_dtl
								$order->insertUpdateDisciplineGroupDetail($orderId);

								//Updating Order Booked Amount
								$order->updateBookingSampleAmount($orderId, $formData);

								//Saving Dynamic Field Name/Value Array in order_dynamic_field_dtl tables
								$order->save_order_dynamic_field_detail($dynamicFieldNameValueArray, $orderId);

								//Saving Client Approval Detail
								!empty($clientApprovalFieldArray) ? $order->updateOrderClientApprovalProcessDetail($clientApprovalFieldArray, $orderId) : '';

								//Calculating Price Variation between Sample Amount and ITC Standard Price List
								$order->__calculatePriceVariationBetSampleAmtAndItcStdPrice($orderId);

								//Updating Analysat Window Setting in order_analyst_window_settings table
								$order->__updateReportAnalystWindowUISettings($orderId);

								$error   = '1';
								$data    = $orderId;
								$message = config('messages.message.updated');

								//Committing the queries
								DB::commit();
							} else {
								$message = config('messages.message.InvocingTypeRequired');
							}
						}
					}
				}
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			DB::rollback();
			$message = config('messages.message.OrderInternalErrorMsg');
		} catch (\Throwable $e) {
			DB::rollback();
			$message = config('messages.message.OrderInternalErrorMsg');
		}

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'status' => $status, 'orderId' => $orderId]);
	}

	/*************************
	 * update order parameters detail
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function updateOrderParameterDetails($orderId, $postData, $isCheckedTATInDays)
	{

		global $order, $models;

		$returnData = array();

		if (!empty($postData)) {
			if (isset($postData['claim_dependent'])) unset($postData['claim_dependent']);
			$postData = $models->assign_null_value_in_array($postData);
			foreach ($postData as $key => $newPostData) {
				if (!empty($newPostData)) {
					$id = str_replace("'", "", $key);
					if ($id == 'new') {
						foreach ($newPostData as $newData) {
							if (array_key_exists('cwap_invoicing_required', $newData)) unset($newData['cwap_invoicing_required']);
							if (array_key_exists('test_parameter_invoicing_parent_id', $newData)) unset($newData['test_parameter_invoicing_parent_id']);
							$newData['order_id'] = $orderId;
							$inserted = DB::table('order_parameters_detail')->insertGetId($newData);
							if (!empty($inserted)) {
								$order->updateOrderSchedulingJobs($inserted);
							}
						}

						//Re-Generate/Update Order Expected Due Date in case of New Test Parameter added by the User
						!empty($orderId) && empty($order->isOrderBookingHoldByUser($orderId)) ? $order->generateUpdateOrderExpectedDueDate_v3($orderId, NULL, array('action' => 'edit')) : '';

						//Re-calculation of Report Due Date and Department Due Date in case of Sample Edited by User
						!empty($orderId) && empty($order->isOrderBookingHoldByUser($orderId)) ? $order->updateReportDepartmentDueDate($orderId) : '';
					} else {
						$updated = DB::table('order_parameters_detail')->where('order_parameters_detail.analysis_id', '=', $id)->update($newPostData);
					}
				}
			}

			//Re-Generate/Update Order Expected Due Date in case of TAT Days modified by the User
			!empty($isCheckedTATInDays) && empty($order->isOrderBookingHoldByUser($orderId)) ? $order->generateUpdateOrderExpectedDueDate_v3($orderId, NULL, array('action' => 'edit')) : '';

			//Re-calculation of Report Due Date and Department Due Date in case of Sample Edited by User
			!empty($isCheckedTATInDays) && empty($order->isOrderBookingHoldByUser($orderId)) ? $order->updateReportDepartmentDueDate($orderId) : '';

			if (!empty($updated) && !empty($inserted)) {
				$returnData = array('success' => config('messages.message.parameterUpdated'));
			} else {
				$returnData = array('success' => config('messages.message.error'));
			}
		} else {
			$returnData = array('error' => config('messages.message.dataNotFound'));
		}

		return response()->json($returnData);
	}

	/*************************
	 * Get list of ProductTestParameters on add order.
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function getProductTestParametersList(Request $request, $test_id)
	{

		global $order, $models;

		$error      	= '0';
		$message    	= config('messages.message.error');
		$data       	= '';
		$formData   	= array();

		$categoryWiseParamenter = $categoryWiseParamenterSortedArr = array();

		$testStandardList = DB::table('product_test_hdr')->where('product_test_hdr.test_id', '=', $test_id)->first();
		$productTestParametersList = DB::table('product_test_dtl')
			->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
			->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
			->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
			->leftJoin('equipment_type', 'equipment_type.equipment_id', 'product_test_dtl.equipment_type_id')
			->leftJoin('method_master', 'method_master.method_id', 'product_test_dtl.method_id')
			->leftJoin('detector_master', 'detector_master.detector_id', 'product_test_dtl.detector_id')
			->select('product_test_dtl.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'test_parameter.test_parameter_invoicing', 'test_parameter.test_parameter_invoicing_parent_id', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.category_sort_by', 'detector_master.detector_name')
			->where('product_test_dtl.test_id', $test_id)
			->orderBy('product_test_dtl.parameter_sort_by', 'asc')
			->get();

		if (!empty($productTestParametersList)) {
			foreach ($productTestParametersList as $key => $values) {
				$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']    = $values->category_sort_by;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']     	= $values->test_para_cat_id;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     	= $values->test_para_cat_name;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] 	= $values;
			}
			$categoryWiseParamenter 	     = array_values($categoryWiseParamenter);
			$categoryWiseParamenterSortedArr = $models->sortArrayAscOrder($categoryWiseParamenter);
		}

		return response()->json(['productTestParametersList' => $categoryWiseParamenterSortedArr, 'testStandardList' => $testStandardList]);
	}

	/***********************
	 * Get list of product test parameters
	 * modifed on : 10-APril-2018
	 * Modified By: Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function getProductTestParameters(Request $request)
	{

		global $sample, $order, $models, $reports, $trfHdr;

		$error      	= '0';
		$message    	= config('messages.message.error');
		$data       	= '';
		$formData   	= $test_parameters_ids = array();

		$categoryWiseParamenter = $orderStabilityList = $categoryWiseParamenterSortedArr = $orderParameterRates = array();

		if (!empty($request['data']['formData'])) {

			//Parsing the form Data
			parse_str($request['data']['formData'], $formData);

			if (!empty($formData['test_parameters'])) {
				$test_parameters_ids = array_values($formData['test_parameters']);
			} else if (!empty($formData['trf_id'])) {
				$test_parameters_ids = $trfHdr->getTrfProductTestDtlIds($formData['trf_id']);
			}

			if (!empty($formData['sample_id']) && !empty($test_parameters_ids)) {

				//Getting All order stability
				$sampleData	    = $sample->getSampleInformation($formData['sample_id']);
				$orderStabilityList = DB::table('order_stability_notes')->orderBy('order_stability_notes.stability_name', 'ASC')->get();
				$runningTimeList    = DB::table('customer_invoicing_running_time')->select('customer_invoicing_running_time.invoicing_running_time_id as id', 'customer_invoicing_running_time.invoicing_running_time as name')->where('customer_invoicing_running_time.invoicing_running_time_status', '1')->get();

				$productTestParametersList = DB::table('product_test_dtl')
					->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
					->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
					->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
					->leftJoin('equipment_type', 'equipment_type.equipment_id', 'product_test_dtl.equipment_type_id')
					->leftJoin('method_master', 'method_master.method_id', 'product_test_dtl.method_id')
					->leftJoin('detector_master', 'detector_master.detector_id', 'product_test_dtl.detector_id')
					->select('product_test_dtl.*', 'product_test_dtl.product_test_dtl_id as product_test_parameter', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'test_parameter.test_parameter_invoicing', 'test_parameter.test_parameter_invoicing_parent_id', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.category_sort_by', 'detector_master.detector_name')
					->whereIn('product_test_dtl.product_test_dtl_id', array_unique($test_parameters_ids))
					->orderBy('product_test_dtl.parameter_sort_by', 'asc')
					->get();

				//Getting All Parameter Rates 
				$orderParameterRates['invoiceRates'] = $order->getOrderInvoicingRatesInDetail($sampleData, $productTestParametersList);

				if (!empty($productTestParametersList)) {
					foreach ($productTestParametersList as $key => $values) {
						$values->invoicingGroupName 					       = $order->assignInvoicingGroupForAssigningRates($values, $sampleData);
						if (!empty($orderParameterRates['invoiceRates'])) foreach ($orderParameterRates['invoiceRates'] as $invoiceRateKey => $val) {
							if ($invoiceRateKey == $values->invoicingGroupName) {
								$values->defined_invoicing_rate = round($val);
							}
						}
						$values->cwap_invoicing_required	       			       = $values->test_para_cat_id == '2' || !empty($values->test_parameter_invoicing_parent_id) ? '1' : '0';
						$values->runningTimeSelectboxList 				       = $runningTimeList;
						$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']   = $values->category_sort_by;
						$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']       = $values->test_para_cat_id;
						$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     = $values->test_para_cat_name;
						$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] = $values;
					}
					//sort Array Asc Order
					$categoryWiseParamenterSortedArr = $models->sortArrayAscOrder(array_values($categoryWiseParamenter));
				}
			}
		}

		return response()->json(['productTestParametersList' => $categoryWiseParamenterSortedArr, 'order_stability' => $orderStabilityList]);
	}

	/*************************
	 * Get list of Product Test Parameters on edit.
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function getEditProductTestParametersList($test_id, $orderId)
	{

		global $order, $models;

		$productTestParametersList = DB::table('product_test_dtl')
			->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
			->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
			->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
			->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
			->leftJoin('equipment_type', 'equipment_type.equipment_id', 'product_test_dtl.equipment_type_id')
			->leftJoin('method_master', 'method_master.method_id', 'product_test_dtl.method_id')
			->leftJoin('detector_master', 'detector_master.detector_id', 'product_test_dtl.detector_id')
			->select('product_test_dtl.*', 'order_parameters_detail.product_test_parameter', 'order_parameters_detail.order_id', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.category_sort_by', 'detector_master.detector_name')
			->where('product_test_dtl.test_id', $test_id)
			->leftJoin('order_parameters_detail', function ($join) use ($orderId) {
				$join->on('order_parameters_detail.product_test_parameter', '=', 'product_test_dtl.product_test_dtl_id');
				$join->where('order_parameters_detail.order_id', $orderId);
			})
			->orderBy('product_test_dtl.parameter_sort_by', 'asc')
			->groupBy('product_test_dtl.product_test_dtl_id')
			->get();

		$categoryWiseParamenter = array();
		if (!empty($productTestParametersList)) {
			foreach ($productTestParametersList as $key => $values) {
				$analysis_id =  !empty($orderDetailOrderId) ? $orderDetailOrderId->analysis_id : '';
				$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']     	= $values->category_sort_by;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']     		= $values->test_para_cat_id;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     		= $values->test_para_cat_name;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] 		= $values;
			}
			//sort Array Asc Order
			$categoryWiseParamenterSortedArr = $models->sortArrayAscOrder(array_values($categoryWiseParamenter));
		}

		return response()->json(['productTestParametersList' => $categoryWiseParamenterSortedArr]);
	}

	/*************************
	 * Get list of product test parameters on edit order .
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function getEditProductTestParameters(Request $request)
	{

		global $sample, $order, $models;

		$formData = $categoryWiseParamenter = $order_stability = $categoryWiseParamenterSortedArr = array();

		if (!empty($request['data']['formData'])) {

			//Parsing the form Data
			parse_str($request['data']['formData'], $formData);

			//Getting All order stability
			$sampleData	     		   = $sample->getSampleInformation($formData['sample_id']);
			$sampleData->customer_id 	   = !empty($formData['customer_id']) ? $formData['customer_id'] : $sampleData->customer_id;
			$sampleData->invoicing_type_id = !empty($formData['invoicing_type_id']) ? $formData['invoicing_type_id'] : $sampleData->invoicing_type_id;
			$order_stability 		   = DB::table('order_stability_notes')->orderBy('order_stability_notes.stability_name', 'ASC')->get();
			$runningTimeList 		   = DB::table('customer_invoicing_running_time')->select('customer_invoicing_running_time.invoicing_running_time_id as id', 'customer_invoicing_running_time.invoicing_running_time as name')->where('customer_invoicing_running_time.invoicing_running_time_status', '1')->get();

			if (!empty($formData['test_parameters'])) {

				$productTestParametersList = DB::table('product_test_dtl')
					->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
					->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
					->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
					->leftJoin('equipment_type', 'equipment_type.equipment_id', 'product_test_dtl.equipment_type_id')
					->leftJoin('method_master', 'method_master.method_id', 'product_test_dtl.method_id')
					->leftJoin('detector_master', 'detector_master.detector_id', 'product_test_dtl.detector_id')
					->select('product_test_dtl.*', 'product_test_dtl.product_test_dtl_id as product_test_parameter', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter.test_parameter_invoicing_parent_id', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.category_sort_by', 'detector_master.detector_name')
					->whereIn('product_test_dtl.product_test_dtl_id', array_values($formData['test_parameters']))
					->orderBy('product_test_dtl.parameter_sort_by', 'asc')
					->get();

				//Getting All Parameter Rates 
				$orderParameterRates['invoiceRates'] = $order->getOrderInvoicingRatesInDetail($sampleData, $productTestParametersList);

				if (!empty($productTestParametersList)) {
					foreach ($productTestParametersList as $key => $values) {
						$values->invoicingGroupName 					       = $order->assignInvoicingGroupForAssigningRates($values, $sampleData);
						if (!empty($orderParameterRates['invoiceRates'])) foreach ($orderParameterRates['invoiceRates'] as $invoiceRateKey => $val) {
							if ($invoiceRateKey == $values->invoicingGroupName) {
								$values->defined_invoicing_rate = round($val);
							}
						}
						$values->cwap_invoicing_required	       			       		= $values->test_para_cat_id == '2' || !empty($values->test_parameter_invoicing_parent_id) ? '1' : '0';
						$values->runningTimeSelectboxList 				       		= $runningTimeList;
						$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']    	= $values->category_sort_by;
						$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']     	= $values->test_para_cat_id;
						$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     	= $values->test_para_cat_name;
						$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] 	= $values;
					}
					//sort Array Asc Order
					$categoryWiseParamenterSortedArr = $models->sortArrayAscOrder(array_values($categoryWiseParamenter));
				}
			}
		}

		return response()->json(['productTestParametersList' => $categoryWiseParamenterSortedArr, 'order_stability' => $order_stability]);
	}

	/***********************
	 * Alternative product test parameters
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 **********************/
	public function getAlterProductTestParameters(Request $request, $product_test_dtl_id)
	{

		global $order, $models;

		$alternativeTestProParamsList = DB::table('product_test_parameter_altern_method')
			->join('product_test_hdr', 'product_test_hdr.test_id', 'product_test_parameter_altern_method.test_id')
			->join('test_parameter', 'test_parameter.test_parameter_id', 'product_test_parameter_altern_method.test_parameter_id')
			->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
			->join('equipment_type', 'equipment_type.equipment_id', 'product_test_parameter_altern_method.equipment_type_id')
			->join('method_master', 'method_master.method_id', 'product_test_parameter_altern_method.method_id')
			->select('product_test_parameter_altern_method.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name')
			->where('product_test_parameter_altern_method.product_test_dtl_id', $product_test_dtl_id)
			->get();

		return response()->json(['alternativeTestProParamsList' => $alternativeTestProParamsList]);
	}

	/***********************
	 * Select alternative  product test parameters
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 **********************/
	public function reSelectTestStandardParameters(Request $request, $product_test_param_altern_method_id)
	{

		global $order, $models;

		$alterSelectedTestProParamsList = $order->getAlternativeParametersDetail($product_test_param_altern_method_id);

		return response()->json(['alterSelectedTestProParamsList' => $alterSelectedTestProParamsList]);
	}

	/***********************
	 * Delete a order
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 **********************/
	public function deleteOrder(Request $request, $order_id)
	{

		global $order, $models;

		$error    = '0';
		$message  = '';
		$data     = '';

		try {
			$checkOrderProcessing = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $order_id)->where('order_parameters_detail.test_result', '<>', null)->first();
			if (empty($checkOrderProcessing) && DB::table('order_master')->where('order_master.order_id', '=', $order_id)->delete()) {
				$error    = '1';
				$message = config('messages.message.OrderDeleteMsg');
			} else {
				$message = config('messages.message.orderForeignKeConstraintFail');
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.orderForeignKeConstraintFail');
		}

		return response()->json(['error' => $error, 'message' => $message]);
	}

	/********************************************************************************
	 * Delete orders parameters
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 *********************************************************************************/
	public function deleteOrderParameter($order_id, $analysis_id)
	{

		global $order, $models;

		$error    = '0';
		$message  = '';
		$data     = '';

		try {
			$checkParameterCount = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $order_id)->count();
			if ($checkParameterCount > '1') {
				if (DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $order_id)->where('order_parameters_detail.analysis_id', '=', $analysis_id)->delete()) {
					$error   = '1';
					$message = config('messages.message.deleted');
				} else {
					$message = config('messages.message.deletedError');
				}
			} else {
				$message = config('messages.message.deletedParameterError');
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.orderForeignKeConstraintFail');
		}

		return response()->json(['error' => $error, 'message' => $message]);
	}

	/***********************
	 *Cancel order
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 **********************/
	public function cancelOrder(Request $request, $orderId)
	{

		global $order, $models;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$status   = '10';

		if (!empty($orderId)) {
			//Updating order progress log  table at the time of booking
			$cancelOrder = $order->updateOrderStausLog($orderId, $status);
			if ($cancelOrder) {
				$error    = '1';
				$message = config('messages.message.OrderCancelMsg');
			} else {
				$message = config('messages.message.OrderCancelFailedMsg');
			}
		}

		return response()->json(['error' => $error, 'message' => $message]);
	}

	/***********************
	 * upload order pdf
	 * $Request
	 * @return \Illuminate\Http\Response
	 **********************/
	public function uploadOrderPdf(Request $request)
	{

		global $order, $invoice, $models;

		$error 	    = '0';
		$message    = config('messages.message.error');
		$data 	    = array();
		$flag 	    = '0';
		$formData   = array();

		if (!empty($request['order_file'])) {
			$formData = array_filter($request->all());
			if (!empty($formData['order_id'])) {
				$order_id = $order->getOrderDetail($formData['order_id']);
				if (!empty($order_id->order_id)) {
					$updated = DB::table('order_master')->where('order_id', '=', $order_id->order_id)->update(['job_order_file' => $formData['job_order_file']]);
					if ($updated) {
						//generate pdf file in public/images/sales/invoices folder
						$order_file = $formData['order_file'];
						list($type, $order_file) = explode(';', $order_file);
						list(, $order_file) = explode(',', $order_file);
						$order_file = base64_decode($order_file);
						if (!file_exists(DOC_ROOT . ORDER_PATH)) {
							mkdir(DOC_ROOT . ORDER_PATH, 0777, true);
						}
						$pdf = fopen(DOC_ROOT . ORDER_PATH . $formData['job_order_file'], 'w');
						fwrite($pdf, $order_file);
						fclose($pdf);
						$message = config('messages.message.OrdePdfMsg');
						$error = 1;
					}
				}
			}
		}

		return response()->json(array('error' => $error, 'message' => $message, 'formData' => $formData));
	}

	/***********************
	 * generate Job Order PDF.
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 **********************/
	public function generateJobOrderPdf(Request $request)
	{

		global $order, $invoice, $models;

		$error 		= '0';
		$message 	= config('messages.message.error');
		$data 		= $jobOrderFile = '';
		$formData   = array();

		if ($request->isMethod('post') && !empty($request->order_id)) {
			$jobOrderData = $models->generatePDF($request->order_id, $contentType = 'order');
			if (!empty($jobOrderData)) {
				foreach ($jobOrderData as $orderId => $jobOrderFile) {
					if ($orderId && $jobOrderFile) {
						DB::table('order_master')->where('order_id', '=', $orderId)->update(['job_order_file' => $jobOrderFile]);
						$error 		  = '1';
						$message 	  = config('messages.message.fileGenerationMsg');
						$jobOrderFile = preg_replace('/(\/+)/', '/', ORDER_PATH . $jobOrderFile);
					}
				}
			}
		}

		return response()->json(array('error' => $error, 'message' => $message, 'jobOrderFile' => $jobOrderFile));
	}

	/*********************
	 * search on add product test parameter popup
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 ***********************/
	public function parametersSearch(Request $request)
	{

		global $models;

		$categoryWiseParamenter = array();

		if (!empty($request['formData'])) {

			//Parsing Serilaze Data
			parse_str($request['formData'], $formData);

			$productTestParametersList = DB::table('product_test_dtl')
				->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
				->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
				->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
				->leftJoin('equipment_type', 'equipment_type.equipment_id', 'product_test_dtl.equipment_type_id')
				->leftJoin('method_master', 'method_master.method_id', 'product_test_dtl.method_id')
				->select('product_test_dtl.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.category_sort_by')
				->where('product_test_dtl.test_id', $formData['test_id'])
				->where('test_parameter.test_parameter_name', 'like', '%' . $formData['keyword'] . '%')
				->orderBy('product_test_dtl.parameter_sort_by', 'asc')
				->get();

			if (!empty($productTestParametersList)) {
				foreach ($productTestParametersList as $key => $values) {
					$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']     	= $values->category_sort_by;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']     		= $values->test_para_cat_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     		= $values->test_para_cat_name;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] 		= $values;
				}
				$categoryWiseParamenter = array_values($categoryWiseParamenter);
			}
			$categoryWiseParamenterSortedArr = $models->sortArrayAscOrder($categoryWiseParamenter);
		}

		return response()->json(['productTestParametersList' => $categoryWiseParamenterSortedArr]);
	}

	/*********************
	 * search on edit product test parameter popup
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 ***********************/
	public function EditParametersSearch(Request $request)
	{

		global $order, $models;

		$categoryWiseParamenter = array();

		if (!empty($request['formData'])) {

			parse_str($request['formData'], $formData);
			$orderId = !empty($formData['order_id']) ? $formData['order_id'] : '';

			$productTestParametersList = DB::table('product_test_dtl')
				->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
				->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
				->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
				->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
				->leftJoin('equipment_type', 'equipment_type.equipment_id', 'product_test_dtl.equipment_type_id')
				->leftJoin('method_master', 'method_master.method_id', 'product_test_dtl.method_id')
				->select('order_parameters_detail.product_test_parameter', 'order_parameters_detail.order_id', 'product_test_dtl.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.category_sort_by')
				->leftJoin('order_parameters_detail', function ($join) use ($orderId) {
					$join->on('order_parameters_detail.product_test_parameter', '=', 'product_test_dtl.product_test_dtl_id');
					$join->where('order_parameters_detail.order_id', $orderId);
				})
				->where('product_test_dtl.test_id', $formData['test_id'])
				->where('test_parameter.test_parameter_name', 'like', '%' . $formData['keyword'] . '%')
				->orderBy('product_test_dtl.parameter_sort_by', 'asc')
				->groupBy('product_test_dtl.product_test_dtl_id')
				->get();

			if (!empty($productTestParametersList)) {
				foreach ($productTestParametersList as $key => $values) {
					$analysis_id =  !empty($orderDetailOrderId) ? $orderDetailOrderId->analysis_id : '';
					$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']     	= $values->category_sort_by;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']     		= $values->test_para_cat_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     		= $values->test_para_cat_name;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] 		= $values;
				}
				$categoryWiseParamenter = array_values($categoryWiseParamenter);
			}
			$categoryWiseParamenterSortedArr = $models->sortArrayAscOrder($categoryWiseParamenter);
		}

		return response()->json(['productTestParametersList' => $categoryWiseParamenterSortedArr]);
	}

	/*********************
	 * function to unhold a order
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 ***********************/
	public function unHoldOrder($order_id)
	{

		global $order, $models, $reports;

		$error   	 = '0';
		$message 	 =  config('messages.message.error');
		$currentDateTime = defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');

		if (!empty($order_id)) {

			$orderData = $order->getOrderDetail($order_id);
			if (!empty($orderData->customer_id) && empty($order->isCustomerPutOnHold($orderData->customer_id))) {

				$orderStatus = $order->getLastOrderStage($order_id);
				$unHoldOrder = DB::table('order_master')->where('order_master.order_id', '=', $order_id)->update(['status' => $orderStatus, 'hold_reason' => NULL]);

				if (!empty($unHoldOrder)) {

					//Updating Order Expected Due Date in case of order unhold by the User
					$order->generateUpdateOrderExpectedDueDate_v3($order_id, $currentDateTime, array('action' => 'edit'));

					//Updating Report Due Date and Department Due Date in case of order unhold by the User
					$order->updateReportDepartmentDueDate($order_id, $currentDateTime);

					//Updating Order Status
					$order->updateOrderStausLog($order_id, $orderStatus);

					$error   = '1';
					$message = config('messages.message.unHoldSuccessMsg');
				} else {
					$error   = '0';
					$message = config('messages.message.errorProcessingMsg');
				}
			} else {
				$error   = '1';
				$message = config('messages.message.customerHoldMsg');
			}
		}

		return response()->json(array('error' => $error, 'message' => $message));
	}

	/*********************
	 * function to get Sample Names on Auto Complete
	 * @param  int  $sampleId,$keyword
	 * Created by:Praveen Singh
	 * @return \Illuminate\Http\Response
	 ***********************/
	public function getAutoCompleteSampleNames($sampleId, $keyword)
	{

		global $sample, $order, $models, $mail, $reports;

		$isFixedRateCustomer = '0';
		$sampleData	     = $sample->getSampleInformation($sampleId);
		$customer_id 	     = !empty($sampleData->customer_id) ? $sampleData->customer_id : '0';
		$customer_city 	     = !empty($sampleData->customer_city) ? $sampleData->customer_city : '0';
		$customer_state	     = !empty($sampleData->customer_state) ? $sampleData->customer_state : '0';
		$invoicing_type_id   = !empty($sampleData->invoicing_type_id) ? $sampleData->invoicing_type_id : '0';
		$product_category_id = !empty($sampleData->product_category_id) ? $sampleData->product_category_id : '0';
		$division_id 	     = !empty($sampleData->division_id) ? $sampleData->division_id : '0';
		$allCategotyIds      = $models->getAllChildrens($product_category_id);

		$itemObj = DB::table('product_master_alias')
			->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->join('product_categories', 'product_categories.p_category_id', 'product_master.p_category_id')
			->select('product_master_alias.c_product_id as id', DB::raw('CONCAT(product_master_alias.c_product_name,"|",product_master.product_name) AS name'))
			->where('product_master_alias.c_product_name', 'LIKE', "%$keyword%")
			->whereIn('product_master.p_category_id', $allCategotyIds);

		if ($invoicing_type_id == '2') {		//State Wise Product
			$itemObj->join('customer_invoicing_rates', function ($itemObj) use ($invoicing_type_id, $customer_id, $customer_state, $division_id, $product_category_id) {
				$itemObj->on('customer_invoicing_rates.cir_c_product_id', '=', 'product_master_alias.c_product_id');
				$itemObj->where('customer_invoicing_rates.invoicing_type_id', $invoicing_type_id);
				$itemObj->where('customer_invoicing_rates.cir_state_id', '=', $customer_state);
				$itemObj->where('customer_invoicing_rates.cir_division_id', '=', $division_id);
				$itemObj->where('customer_invoicing_rates.cir_product_category_id', $product_category_id);
			});
		} else if ($invoicing_type_id == '3') { 	//Customer Wise Product or Fixed rate party
			$isFixedRateCustomer = count(DB::table('customer_invoicing_rates')
				->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
				->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
				->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
				->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
				->whereNull('customer_invoicing_rates.cir_c_product_id')
				->first());
			if (empty($isFixedRateCustomer)) {
				$itemObj->join('customer_invoicing_rates', function ($itemObj) use ($invoicing_type_id, $customer_id, $division_id, $product_category_id) {
					$itemObj->on('customer_invoicing_rates.cir_c_product_id', '=', 'product_master_alias.c_product_id');
					$itemObj->where('customer_invoicing_rates.invoicing_type_id', $invoicing_type_id);
					$itemObj->where('customer_invoicing_rates.cir_customer_id', $customer_id);
					$itemObj->where('customer_invoicing_rates.cir_division_id', '=', $division_id);
					$itemObj->where('customer_invoicing_rates.cir_product_category_id', $product_category_id);
				});
			}
		}

		$itemObj->orderBy('product_master_alias.c_product_name', 'ASC');
		$itemObj->limit('100');
		$items = $itemObj->get();

		return response()->json(['itemsList' => $items, 'invoicing_type_id' => $invoicing_type_id, 'fixed_rate_invoicing_type_id' => $isFixedRateCustomer]);
	}

	/*************************
	 * check Customer Wise ProductRate
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function getCustomerStateAndProductWiseRate(Request $request)
	{

		global $sample, $order, $models, $mail, $reports;

		$error       	 	= '0';
		$message     	 	= config('messages.message.error');
		$data        	 	= '';
		$sample_description_id 	= '0';
		$formData    	 	= array();

		//Saving record in orders table
		if ($request->isMethod('post') && !empty($request->all())) {

			//Parsing the Serialze Dta
			$formData = $request->all();

			if (!empty($formData['sample_description']) && !empty($formData['invoicing_type_id']) && !empty($formData['sample_id'])) {

				$sampleData	       = $sample->getSampleInformation($formData['sample_id']);
				$customer_id 	       = !empty($sampleData->customer_id) ? $sampleData->customer_id : '0';
				$customer_city	       = !empty($sampleData->customer_city) ? $sampleData->customer_city : '0';
				$customer_state	       = !empty($sampleData->customer_state) ? $sampleData->customer_state : '0';
				$invoicing_type_id     = !empty($sampleData->invoicing_type_id) ? $sampleData->invoicing_type_id : '0';
				$product_category_id   = !empty($sampleData->product_category_id) ? $sampleData->product_category_id : '0';
				$division_id 	       = !empty($sampleData->division_id) ? $sampleData->division_id : '0';
				$sample_description    = !empty($formData['sample_description']) ? trim($formData['sample_description']) : '';
				$allCategotyIds        = $models->getAllChildrens($product_category_id);
				$sample_description_id = $order->getSampleDescriptionDetail($formData['sample_description'], $allCategotyIds, $invoicing_type_id, $product_category_id);

				$invoicingDataObj = DB::table('customer_invoicing_rates')
					->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
					->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
					->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id);

				if ($invoicing_type_id == '2') {		//State Wise Product

					$invoicingDataObj->join('product_master_alias', 'customer_invoicing_rates.cir_c_product_id', 'product_master_alias.c_product_id');
					$invoicingDataObj->join('product_master', 'product_master.product_id', 'product_master_alias.product_id');
					$invoicingDataObj->join('product_categories', 'product_categories.p_category_id', 'product_master.p_category_id');
					$invoicingDataObj->where('customer_invoicing_rates.cir_state_id', '=', $customer_state);
					$invoicingDataObj->where('product_master_alias.c_product_name', $sample_description);
					$invoicingDataObj->whereIn('product_master.p_category_id', $allCategotyIds);
				} else if ($invoicing_type_id == '3') { 	//Customer Wise Product or Fixed rate party

					//In case of fixed Rate Party
					$fixedInvoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
						->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->whereNull('customer_invoicing_rates.cir_c_product_id')
						->first();

					//If Product ID is not null,then Customer Wise Product
					if (empty($fixedInvoicingData)) {
						$invoicingDataObj->join('product_master_alias', 'customer_invoicing_rates.cir_c_product_id', 'product_master_alias.c_product_id');
						$invoicingDataObj->join('product_master', 'product_master.product_id', 'product_master_alias.product_id');
						$invoicingDataObj->join('product_categories', 'product_categories.p_category_id', 'product_master.p_category_id');
						$invoicingDataObj->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id);
						$invoicingDataObj->where('customer_invoicing_rates.cir_city_id', '=', $customer_city);
						$invoicingDataObj->where('product_master_alias.c_product_name', $sample_description);
						$invoicingDataObj->whereIn('product_master.p_category_id', $allCategotyIds);
					} else {
						$invoicingDataObj->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id);
						$invoicingDataObj->whereNull('customer_invoicing_rates.cir_c_product_id');
					}
				}
				$invoicingData 	= $invoicingDataObj->first();
				$error 	 	= !empty($invoicingData->invoicing_rate) ? '1' : '0';
				$message 	= !empty($invoicingData->invoicing_rate) ? '' : config('messages.message.InvocingTypeRequired');
			}
		}

		return response()->json(array('error' => $error, 'message' => $message, 'sample_description_id' => $sample_description_id));
	}

	/**
	 * Get list of customers on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerAttachedSampleDetail($sampleId)
	{

		global $models, $customer, $sample, $trfHdr;

		$error       = '0';
		$message     = config('messages.message.error');
		$data        = '';
		$currentDate = defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('Y-m-d');
		$trfHdrCount = '0';
		$customerNameList = $trfHdrData = array();

		$sampleData	     = $sample->getSampleInformation($sampleId);
		$customer_id 	     = !empty($sampleData->customer_id) ? $sampleData->customer_id : '0';
		$invoicing_type_id   = !empty($sampleData->invoicing_type_id) ? $sampleData->invoicing_type_id : '0';
		$product_category_id = !empty($sampleData->product_category_id) ? $sampleData->product_category_id : '0';
		$division_id 	     = !empty($sampleData->division_id) ? $sampleData->division_id : '0';

		$isFixedRateCustomer = count(DB::table('customer_invoicing_rates')
			->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
			->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
			->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
			->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
			->whereNull('customer_invoicing_rates.cir_c_product_id')
			->first());

		$customerAttachedSampleList = DB::table('samples')
			->join('customer_defined_structures', 'customer_defined_structures.customer_id', '=', 'samples.customer_id')
			->join('customer_master', 'customer_master.customer_id', '=', 'samples.customer_id')
			->join('divisions', 'divisions.division_id', 'samples.division_id')
			->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', '=', 'customer_defined_structures.invoicing_type_id')
			->join('customer_discount_types', 'customer_discount_types.discount_type_id', 'customer_defined_structures.discount_type_id')
			->join('customer_billing_types', 'customer_billing_types.billing_type_id', 'customer_defined_structures.billing_type_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
			->join('users', 'users.id', 'customer_master.sale_executive')
			->select('samples.sample_id', 'samples.sample_no', 'samples.trf_id', 'samples.sample_mode_id', 'divisions.division_name', 'customer_defined_structures.*', 'customer_discount_types.discount_type as discount_type_name', 'city_db.city_id', 'city_db.city_name', 'users.id as sale_executive', 'users.name', 'customer_master.customer_name', 'customer_master.customer_address', DB::raw('CONCAT(customer_master.customer_address,",",state_db.state_name,",",city_db.city_name) AS customer_address_detail'), 'customer_invoicing_types.invoicing_type', 'customer_master.mfg_lic_no', 'customer_master.customer_priority_id', 'customer_billing_types.billing_type')
			->whereColumn('customer_defined_structures.division_id', '=', 'samples.division_id')
			->whereColumn('customer_defined_structures.product_category_id', '=', 'samples.product_category_id')
			->where('samples.sample_id', '=', $sampleId)
			->where('users.is_sales_person', '=', '1')
			->first();

		if (!empty($customerAttachedSampleList->trf_id)) {
			$trfHdrData = $trfHdr->viewTrfDetail($customerAttachedSampleList->trf_id);
			$trfHdrCount = count($trfHdrData);
		}

		if (!empty($customerAttachedSampleList)) $customerAttachedSampleList->fixed_rate_invoicing_type_id = $isFixedRateCustomer;
		$salesExecutiveList = !empty($customerAttachedSampleList) ? $customer->getSalesExecutiveAccordingToDivision($customerAttachedSampleList->division_id) : '0';
		$error   	    = !empty($customerAttachedSampleList) ? '1' : '0';
		$message 	    = !empty($customerAttachedSampleList) ? 'Invalid' : $message;

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'trfHdrCount' => $trfHdrCount, 'trfHdrData' => $trfHdrData, 'customerAttachedSampleList' => $customerAttachedSampleList, 'currentDate' => $currentDate, 'customerNameList' => $customerNameList, 'salesExecutiveList' => $salesExecutiveList]);
	}

	/**
	 * Get list of customers on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getEditCustomerAttachedSampleDetail($orderId, $sampleId)
	{

		global $models, $order, $customer;

		$error       = '0';
		$message     = config('messages.message.error');
		$data        = '';
		$currentDate = defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('Y-m-d');

		$customerEditAttachedSampleList = DB::table('samples')
			->join('order_master', 'order_master.sample_id', '=', 'samples.sample_id')
			->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
			->join('divisions', 'divisions.division_id', 'order_master.division_id')
			->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', '=', 'order_master.invoicing_type_id')
			->join('customer_discount_types', 'customer_discount_types.discount_type_id', 'order_master.discount_type_id')
			->join('customer_billing_types', 'customer_billing_types.billing_type_id', 'order_master.billing_type_id')
			->join('state_db', 'state_db.state_id', '=', 'customer_master.customer_state')
			->join('city_db', 'city_db.city_id', '=', 'order_master.customer_city')
			->join('users', 'users.id', 'order_master.sale_executive')
			->select('divisions.division_name', 'users.id as user_id', 'users.name', 'customer_master.customer_name', 'state_db.state_name', 'city_db.city_name', 'customer_discount_types.discount_type as discount_type_name', 'samples.sample_mode_id', 'samples.sample_id', 'order_master.*', 'customer_invoicing_types.invoicing_type', 'order_master.mfg_lic_no', 'customer_master.customer_address', DB::raw('CONCAT(customer_master.customer_address,",",state_db.state_name,",",city_db.city_name) AS customer_address_detail'))
			->where('order_master.order_id', '=', $orderId)
			->where('samples.sample_id', '=', $sampleId)
			->where('users.is_sales_person', '=', '1')
			->first();

		$customerNameList   = $customer->getCustomersAccToDefinedStructure($customerEditAttachedSampleList);
		$salesExecutiveList = !empty($customerEditAttachedSampleList) ? $customer->getSalesExecutiveAccordingToDivision($customerEditAttachedSampleList->division_id) : '0';
		$error   	    = !empty($customerEditAttachedSampleList) ? '1' : '0';
		$message 	    = !empty($customerEditAttachedSampleList) ? '' : $message;

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'customerAttachedSampleList' => $customerEditAttachedSampleList, 'currentDate' => $currentDate, 'customerNameList' => $customerNameList, 'salesExecutiveList' => $salesExecutiveList]);
	}

	/**
	 * Get Customer Attached Detail
	 * Date : 12-10-2018
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerAttachedDetail($customer_id, $sample_id)
	{

		global $models, $customer, $sample;

		$error    = '0';
		$message  = config('messages.message.OrderInternalErrorMsg');
		$data     = '';

		$sampleData	     = $sample->getSampleInformation($sample_id);
		$division_id 	     = !empty($sampleData->division_id) ? $sampleData->division_id : '0';
		$product_category_id = !empty($sampleData->product_category_id) ? $sampleData->product_category_id : '0';

		$customerAttachedList = DB::table('customer_defined_structures')
			->join('customer_master', 'customer_master.customer_id', '=', 'customer_defined_structures.customer_id')
			->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', '=', 'customer_defined_structures.invoicing_type_id')
			->join('customer_discount_types', 'customer_discount_types.discount_type_id', 'customer_defined_structures.discount_type_id')
			->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
			->join('users', 'users.id', 'customer_master.sale_executive')
			->select('customer_defined_structures.*', 'customer_master.sale_executive', 'customer_master.customer_name', 'city_db.city_id', 'city_db.city_name', 'customer_discount_types.discount_type as discount_type_name', 'customer_invoicing_types.invoicing_type', 'customer_master.mfg_lic_no')
			->where('customer_defined_structures.customer_id', '=', $customer_id)
			->where('customer_defined_structures.division_id', '=', $division_id)
			->where('customer_defined_structures.product_category_id', '=', $product_category_id)
			->where('users.is_sales_person', '=', '1')
			->first();

		$salesExecutiveList = !empty($customerAttachedList) ? $customer->getSalesExecutiveAccordingToDivision($customerAttachedList->division_id) : '0';
		$totalOrderCount    = DB::table('order_master')->where('order_master.sample_id', '=', $sample_id)->count();
		$error   	    = !empty($customerAttachedList) ? '1' : '0';
		$message 	    = !empty($customerAttachedList) ? config('messages.message.totalOrderInSampleCountMsg') . $totalOrderCount : config('messages.message.customerDefinedInvoicingError');

		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'customerAttachedList' => $customerAttachedList, 'salesExecutiveList' => $salesExecutiveList]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getOrderTrfFileUploadDtl(Request $request)
	{

		global $models, $order, $customer;

		$error          = '0';
		$message        = '';
		$data           = '';
		$formData       = $custSTPSampleDropDownList = array();
		$rootPath       = defined('ROOT_DIR') ? ROOT_DIR : '/opt/lampp/htdocs/itcerp';
		$trfPath        = defined('TRF_PATH') ? TRF_PATH : '/public/images/sales/trfs/';

		try {
			if ($request->isMethod('post') && !empty($request->order_id)) {

				//Fetching Upload TRF Detail
				$orderLinkedTrfDtlList = DB::table('order_linked_trf_dtl')->where('order_linked_trf_dtl.oltd_order_id', $request->order_id)->orderBy('order_linked_trf_dtl.oltd_id', 'DESC')->first();
				if (!empty($orderLinkedTrfDtlList)) {
					$error = '1';
					$orderLinkedTrfDtlList->oltd_file_name_link = url($trfPath . $orderLinkedTrfDtlList->oltd_order_id . '/' . $orderLinkedTrfDtlList->oltd_file_name);
				}

				//Getting Customer Wise Sample Name form central_stp_dtls Table
				$orderData = $order->getOrderDetail($request->order_id);
				if (!empty($orderData->customer_id)) {
					$custSTPSampleDropDownList = DB::table('central_stp_dtls')
						->select('central_stp_dtls.cstp_id as id', 'central_stp_dtls.cstp_sample_name as name')
						->where('central_stp_dtls.cstp_customer_id', $orderData->customer_id)
						->orderBy('central_stp_dtls.cstp_sample_name', 'ASC')
						->get()
						->toArray();

					$custSTPSampleSelectedList = DB::table('order_linked_stp_dtl')
						->select('order_linked_stp_dtl.olsd_cstp_id', 'order_linked_stp_dtl.olsd_cstp_sample_name')
						->where('order_linked_stp_dtl.olsd_order_id', $orderData->order_id)
						->first();
				}
			}
		} catch (\Exception $e) {
			$message = config('messages.message.error');
		}

		return response()->json(array('error' => $error, 'message' => $message, 'orderLinkedTrfDtlList' => $orderLinkedTrfDtlList, 'custSTPSampleDropDownList' => $custSTPSampleDropDownList, 'custSTPSampleSelectedList' => $custSTPSampleSelectedList));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getOrderSTPNoAccToSampleName(Request $request)
	{

		global $models, $order, $customer;

		$error    = '0';
		$message  = '';
		$data     = '';
		$formData = $returnData = array();

		try {
			if ($request->isMethod('post') && !empty($request->cstp_sample_name)) {

				//Getting STP No. form central_stp_dtls Table
				$returnData = DB::table('central_stp_dtls')
					->select('central_stp_dtls.cstp_id as id', 'central_stp_dtls.cstp_no as name')
					->where('central_stp_dtls.cstp_sample_name', 'LIKE', base64_decode($request->cstp_sample_name))
					->orderBy('central_stp_dtls.cstp_no', 'ASC')
					->get()
					->toArray();

				$error = '1';
			}
		} catch (\Exception $e) {
			$message = config('messages.message.error');
		}

		return response()->json(array('error' => $error, 'message' => $message, 'custSTPNoAccToSampleNameList' => $returnData));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function saveOrderStpDetail(Request $request)
	{

		global $models, $order, $customer;

		$error    = '0';
		$message  = config('messages.message.saveError');
		$data     = '';
		$formData = $returnData = array();

		try {
			if ($request->isMethod('post') && !empty($request->formData)) {

				//Parsing Form Data
				parse_str($request->formData, $formData);

				if (empty($formData['olsd_cstp_id'])) {
					$message = config('messages.message.saveError');
				} else if (empty($formData['olsd_order_id'])) {
					$message = config('messages.message.saveError');
				} else {

					//Getting Central STP Detail
					$centralStpDtls = DB::table('central_stp_dtls')->where('central_stp_dtls.cstp_id', $formData['olsd_cstp_id'])->first();
					if (!empty($centralStpDtls)) {
						//Checking Record Exist or not			
						$orderLinkedStpDtl = DB::table('order_linked_stp_dtl')->where('order_linked_stp_dtl.olsd_order_id', $formData['olsd_order_id'])->first();
						if (empty($orderLinkedStpDtl->olsd_id)) { 		//Saving of Data
							$dataSave = array();
							$dataSave['olsd_order_id'] 		= $formData['olsd_order_id'];
							$dataSave['olsd_cstp_id']  		= $centralStpDtls->cstp_id;
							$dataSave['olsd_cstp_no']  		= $centralStpDtls->cstp_no;
							$dataSave['olsd_cstp_file_name']   	= $centralStpDtls->cstp_file_name;
							$dataSave['olsd_cstp_sample_name'] 	= $centralStpDtls->cstp_sample_name;
							$dataSave['olsd_date']  	       	= CURRENTDATETIME;
							$dataSave['created_by']  	       	= USERID;
							DB::table('order_linked_stp_dtl')->insertGetId($dataSave);
							$error = '1';
							$message = config('messages.message.saved');
						} else if (!empty($orderLinkedStpDtl->olsd_id)) {		//Updating of Data
							$dataUpdate = array();
							$dataUpdate['olsd_cstp_id']  	 = $centralStpDtls->cstp_id;
							$dataUpdate['olsd_cstp_no']  	 = $centralStpDtls->cstp_no;
							$dataUpdate['olsd_cstp_file_name']   = $centralStpDtls->cstp_file_name;
							$dataUpdate['olsd_cstp_sample_name'] = $centralStpDtls->cstp_sample_name;
							$dataSave['olsd_date']  	       	 = CURRENTDATETIME;
							$dataSave['created_by']  	       	 = USERID;
							DB::table('order_linked_stp_dtl')->where('order_linked_stp_dtl.olsd_id', $orderLinkedStpDtl->olsd_id)->update($dataUpdate);
							$error = '1';
							$message = config('messages.message.updated');
						}
					}
				}
			}
		} catch (\Exception $e) {
			$message = config('messages.message.saveError');
		}

		return response()->json(array('error' => $error, 'message' => $message, 'custSTPNoAccToSampleNameList' => ''));
	}

	/********************************************************************************
	 * Removing Dynamic Field Name/Value Row
	 * Created By : Praveen Singh
	 * Created On : 30-Jan-2020
	 *********************************************************************************/
	public function removeDynamicFieldRowData($id)
	{

		global $models;

		$error    = '0';
		$message  = '';
		$data     = '';

		try {
			if (DB::table('order_dynamic_field_dtl')->where('order_dynamic_field_dtl.odf_id', $id)->delete()) {
				$error   = '1';
				$message = config('messages.message.deleted');
			} else {
				$message = config('messages.message.deletedError');
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.deletedError');
		}

		return response()->json(['error' => $error, 'message' => $message]);
	}
}
