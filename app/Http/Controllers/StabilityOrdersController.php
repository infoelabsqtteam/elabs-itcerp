<?php

/*****************************************************
 *StabilityOrders Model File
 *Created By:Praveen-Singh
 *Created On : 18-Dec-2018
 *Modified On : 
 *Package : ITC-ERP-PKL
 ******************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Sample;
use App\Order;
use App\Models;
use App\Setting;
use App\StabilityOrderPrototype;
use App\StabilityOrder;
use App\ProductCategory;
use App\SendMail;
use App\Report;
use App\Customer;
use Session;
use Validator;
use Route;
use DB;
use DNS1D;

class StabilityOrdersController extends Controller
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

        global $sample, $order, $models, $mail, $report, $customer, $stbOrder, $stbOrderPrototype;

        $sample = new Sample();
        $order     = new Order();
        $models = new Models();
        $mail     = new SendMail();
        $report = new Report();
        $customer = new Customer();
        $stbOrder = new StabilityOrder();
        $stbOrderPrototype = new StabilityOrderPrototype();

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
     ********************************************************/
    public function index()
    {

        global $order, $models, $stbOrder, $stbOrderPrototype;

        $user_id               = defined('USERID') ? USERID : '0';
        $division_id              = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids        = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids              = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids    = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
        $showOrderDateCalender = '0';

        return view('sales.stability_orders.notifcations.index', ['title' => 'Stability Notifications', '_stability_notifications' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids, 'showOrderDateCalender' => $showOrderDateCalender]);
    }

    /***************************************************************
     * Get Stability Notification list
     * Created_at: 17-01-2019
     * Created_by : Praveen Singh
     ****************************************************************/
    public function getStabilityOrderNotificationList(Request $request)
    {

        global $order, $models, $report, $stbOrder, $stbOrderPrototype;

        $error            = '0';
        $message        = '';
        $data            = '';
        $user_id            = defined('USERID') ? USERID : '0';
        $division_id           = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $role_id            = defined('ROLEID') ? ROLEID : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
        $currentDateTime    = defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
        $stbNotifications   = array();

        $stbNotificationObj = DB::table('stb_order_noti_dtl')
            ->join('stb_order_hdr', 'stb_order_hdr.stb_order_hdr_id', 'stb_order_noti_dtl.stb_order_hdr_id')
            ->join('stb_order_hdr_dtl', 'stb_order_hdr_dtl.stb_order_hdr_dtl_id', 'stb_order_noti_dtl.stb_order_hdr_dtl_id')
            ->join('stb_order_hdr_dtl_detail', 'stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id', 'stb_order_noti_dtl.stb_order_hdr_dtl_id')
            ->join('product_master', 'product_master.product_id', 'stb_order_hdr_dtl.stb_product_id')
            ->join('product_test_hdr', 'product_test_hdr.test_id', 'stb_order_hdr_dtl.stb_product_test_id')
            ->join('test_standard', 'test_standard.test_std_id', 'stb_order_hdr_dtl.stb_test_standard_id')
            ->join('product_master_alias', 'product_master_alias.c_product_id', 'stb_order_hdr.stb_sample_description_id')
            ->select('stb_order_hdr.stb_prototype_no', 'stb_order_hdr.stb_prototype_date', 'product_master_alias.c_product_name as stb_sample_description_name', DB::raw("DATEDIFF(stb_order_noti_dtl.stb_order_noti_dtl_date,current_date) as stb_noti_before_current_date"), DB::raw("DATEDIFF(current_date,stb_order_noti_dtl.stb_order_noti_dtl_date) as stb_noti_after_current_date"), 'stb_order_hdr_dtl.*', 'stb_order_noti_dtl.stb_order_noti_dtl_date', 'product_master.product_name', 'test_standard.test_std_name', 'product_test_hdr.test_code')
            ->whereNull('stb_order_noti_dtl.stb_order_noti_confirm_date')
            ->whereNull('stb_order_noti_dtl.stb_order_noti_confirm_by')
            ->where('stb_order_hdr_dtl.stb_order_book_status', '0')
            ->where('stb_order_hdr_dtl_detail.stb_order_hdr_detail_status', '0');

        if (defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER && defined('USERID') && USERID) {
            $stbNotificationObj->where('stb_order_hdr.stb_created_by', USERID);
        }
        if (!empty($division_id)) {
            $stbNotificationObj->where('stb_order_hdr.stb_division_id', $division_id);
        }
        if (!empty($department_ids)) {
            $stbNotificationObj->whereIn('stb_order_hdr.stb_product_category_id', $department_ids);
        }
        $stbNotificationObj->groupBy('stb_order_noti_dtl.stb_order_hdr_dtl_id');
        $stbNotificationObj->orderBy('stb_order_noti_dtl.stb_order_noti_dtl_date', 'ASC');
        $stbNotificationList = $stbNotificationObj->get()->toArray();

        if (!empty($stbNotificationList)) {
            $error = '1';
            foreach ($stbNotificationList as $key => $values) {
                if (in_array($values->stb_noti_before_current_date, array('1', '2')) || $values->stb_noti_after_current_date == '0' || $values->stb_noti_after_current_date > '0') {
                    $values->stability_condition_count  = count(DB::table('stb_order_hdr_dtl_detail')->select('stb_order_hdr_dtl_detail.stb_stability_type_id')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id', $values->stb_order_hdr_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id', $values->stb_order_hdr_dtl_id)->groupBy('stb_order_hdr_dtl_detail.stb_stability_type_id')->get()->toArray());
                    $values->create_order_button_status = strtotime(date('Y-m-d', strtotime($currentDateTime))) >= strtotime(date('Y-m-d', strtotime($values->stb_order_noti_dtl_date))) ? false : true;
                    $stbNotifications[$key] = $values;
                }
            }
        }

        //Formatting date Columns
        $models->formatTimeStampFromArray($stbNotifications, DATETIMEFORMAT);

        //echo '<pre>';print_r($stbNotifications);die;
        return response()->json(array('error' => $error, 'message' => $message, 'stbNotificationList' => $stbNotifications));
    }

    /************************************************************************
     * Get list of ProductTestParameters on add order.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *************************************************************************/
    public function getStabilityOrderPrototypesDetail(Request $request)
    {

        global $order, $models, $stbOrder, $stbOrderPrototype;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = $returnData = array();

        //Saving record in orders table
        if (!empty($request->formData) && $request->isMethod('post')) {

            //Parsing the Serialze Dta
            parse_str($request->formData, $formData);

            $stb_order_hdr_id = !empty($formData['stb_order_hdr_id']) ? $formData['stb_order_hdr_id'] : '0';
            $stb_order_hdr_dtl_id = !empty($formData['stb_order_hdr_dtl_id']) ? $formData['stb_order_hdr_dtl_id'] : '0';

            if ($stb_order_hdr_id && $stb_order_hdr_dtl_id) {
                $returnData['stb_order_hdr'] = $stbOrderPrototype->getStabilityOrder($stb_order_hdr_id);
                $returnData['stb_order_hdr_dtl'] = DB::table('stb_order_hdr_dtl')
                    ->join('stb_order_hdr_dtl_detail', 'stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id', 'stb_order_hdr_dtl.stb_order_hdr_dtl_id')
                    ->join('stb_order_stability_types', 'stb_order_stability_types.stb_stability_type_id', 'stb_order_hdr_dtl_detail.stb_stability_type_id')
                    ->join('product_master', 'product_master.product_id', 'stb_order_hdr_dtl.stb_product_id')
                    ->join('product_test_hdr', 'product_test_hdr.test_id', 'stb_order_hdr_dtl.stb_product_test_id')
                    ->join('test_standard', 'test_standard.test_std_id', 'stb_order_hdr_dtl.stb_test_standard_id')
                    ->select('stb_order_hdr_dtl_detail.stb_stability_type_id', 'stb_order_hdr_dtl_detail.stb_order_hdr_detail_status', 'stb_order_stability_types.stb_stability_type_name', 'stb_order_hdr_dtl_detail.stb_dtl_sample_qty', 'stb_order_hdr_dtl_detail.stb_condition_temperature', 'stb_order_hdr_dtl.*', 'product_master.product_name', 'test_standard.test_std_name', 'product_test_hdr.test_code')
                    ->where('stb_order_hdr_dtl.stb_order_hdr_dtl_id', $stb_order_hdr_dtl_id)
                    ->groupBy('stb_order_hdr_dtl_detail.stb_stability_type_id')
                    ->orderBy('stb_order_hdr_dtl_detail.stb_stability_type_id', 'ASC')
                    ->get()
                    ->toArray();

                //Formatting date Columns
                !empty($returnData['stb_order_hdr_dtl']) ? $models->formatTimeStampFromArray($returnData['stb_order_hdr_dtl'], DATETIMEFORMAT) : '';
            }
            $error    = !empty($returnData['stb_order_hdr']) ? '1' : '0';
            $message  = '';
        }

        //echo '<pre>';print_r($returnData);die;
        return response()->json(['error' => $error, 'message' => $message, 'returnData' => $returnData]);
    }

    /************************************************************************
     * Get list of ProductTestParameters on add order.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *************************************************************************/
    public function getFinalPreviewStabilityOrder(Request $request)
    {

        global $order, $sample, $models, $stbOrder, $stbOrderPrototype;

        $error              = '0';
        $message            = config('messages.message.error');
        $data               = '';
        $formData           = $categoryWiseParamenterArr = array();
        $user_id            = defined('USERID') ? USERID : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $currentDate        = !defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('d-m-Y');

        //Saving record in orders table
        if (!empty($request->formData) && $request->isMethod('post')) {

            //Parsing the Serialze Dta
            parse_str($request->formData, $formData);

            $stb_order_hdr_id      = !empty($formData['stb_order_hdr_id']) ? $formData['stb_order_hdr_id'] : '0';
            $stb_order_hdr_dtl_id  = !empty($formData['stb_order_hdr_dtl_id']) ? $formData['stb_order_hdr_dtl_id'] : '0';
            $stb_stability_type_id = !empty($formData['stb_stability_type_id']) ? $formData['stb_stability_type_id'] : '0';

            if ($stb_order_hdr_id && $stb_order_hdr_dtl_id && $stb_stability_type_id) {

                $error                             = '1';
                $message                           = '';
                $stbOrderList                      = $stbOrderPrototype->getStabilityOrder($stb_order_hdr_id);
                $stbOrderHdrDtl                    = $stbOrderPrototype->getStabilityOrderHdrDtl($stb_order_hdr_dtl_id);
                $stbOrderHdrDetailDtl              = $stbOrderPrototype->getStabilityOrderHdrDetailDtl($stb_order_hdr_id, $stb_order_hdr_dtl_id, $stb_stability_type_id);
                $productTestDtlIds                 = $stbOrderPrototype->getOrderHdrDetailProductTestDtls($formData);
                $sampleData                       = $sample->getSampleInformation($stbOrderList->stb_sample_id);
                $stbOrderList->stb_tat_editable    = isset($sampleData->tat_editable) ? $sampleData->tat_editable : NULL;
                $stbOrderList->stb_dtl_sample_qty  = $stbOrderPrototype->getOrderHdrDetailSampleQtyDtl($formData);
                $stbOrderList->stb_prototype_date  = !empty($stbOrderList->stb_prototype_date) ? date(DATEFORMAT, strtotime($stbOrderList->stb_prototype_date)) : '';
                $stbOrderList->stb_po_date_prev    = !empty($stbOrderList->stb_po_date) ? date(MYSQLDATETIMEFORMAT, strtotime($stbOrderList->stb_po_date)) : '';
                $stbOrderList->stb_po_date         = !empty($stbOrderList->stb_po_date) ? date(DATEFORMAT, strtotime($stbOrderList->stb_po_date)) : '';
                $stbOrderList->stb_sampling_date   = !empty($stbOrderList->stb_sampling_date) ? date('Y-m-d H:i a', strtotime($stbOrderList->stb_sampling_date)) : '';

                if (!empty($productTestDtlIds)) {

                    //Getting All order stability
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
                        ->whereIn('product_test_dtl.product_test_dtl_id', array_values($productTestDtlIds))
                        ->orderBy('product_test_dtl.parameter_sort_by', 'asc')
                        ->get();

                    //Getting All Parameter Rates 
                    $orderParameterRates['invoiceRates'] = $order->getOrderInvoicingRatesInDetail($sampleData, $productTestParametersList);

                    if (!empty($productTestParametersList)) {
                        foreach ($productTestParametersList as $key => $values) {
                            $values->invoicingGroupName                        = $order->assignInvoicingGroupForAssigningRates($values, $sampleData);
                            if (!empty($orderParameterRates['invoiceRates'])) foreach ($orderParameterRates['invoiceRates'] as $invoiceRateKey => $val) {
                                if ($invoiceRateKey == $values->invoicingGroupName) {
                                    $values->defined_invoicing_rate = round($val);
                                }
                            }
                            $values->cwap_invoicing_required                                  = $values->test_para_cat_id == '2' || !empty($values->test_parameter_invoicing_parent_id) ? '1' : '0';
                            $values->runningTimeSelectboxList                            = $runningTimeList;
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

            return response()->json(array('error' => $error, 'message' => $message, 'currentDate' => $currentDate, 'order_stability' => $orderStabilityList, 'stbOrderList' => $stbOrderList, 'stbOrderHdrDtl' => $stbOrderHdrDtl, 'stbOrderHdrDetailDtl' => $stbOrderHdrDetailDtl, 'productTestParametersList' => $categoryWiseParamenterSortedArr));
        }
    }

    /*************************
     * save new order /create new order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     ************************/
    public function createOrder(Request $request)
    {

        global $order, $sample, $models, $stbOrder, $stbOrderPrototype;

        try {

            $error                = '0';
            $message              = config('messages.message.OrderInternalErrorMsg');
            $data                 = '';
            $customerId           = '0';
            $sampleId             = '0';
            $currentDate         = !defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('d-m-Y');
            $currentDateTime     = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
            $formData             = $previousOrderDetail     = array();
            $previousOrderCount      = $finalTypeSave = $orderId = '';

            //Saving record in orders table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing the Serialze Dta
                parse_str($request->formData, $formData);

                if (!empty($formData)) {
                    if (empty($formData['stb_order_hdr_id']) || empty($formData['stb_order_hdr_dtl_id']) || empty($formData['stb_stability_type_id'])) {
                        $message = config('messages.message.OrderInternalErrorMsg');
                    } else if (empty($formData['sample_id'])) {
                        $message = config('messages.message.sampleReceivingCodeRequired');
                    } else if (!$order->checkSampleAndTestProductCategory($formData['sample_id'], $models->getMainProductCatParentId($formData['product_category_id']))) {
                        $message = config('messages.message.mismatchSampleAndTestProductCategory');
                    } else if (empty($formData['customer_id'])) {
                        $message = config('messages.message.customerNameRequired');
                    } else if (empty($formData['customer_city'])) {
                        $message = config('messages.message.customerCityRequired');
                    } else if (empty($formData['mfg_lic_no'])) {
                        $message = config('messages.message.customerLicNumRequired');
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
                    } else if (empty($formData['sample_description']) && empty($formData['sample_description_id'])) {
                        $message = config('messages.message.sampleDescriptionRequired');
                    } else if (empty($formData['batch_no'])) {
                        $message = config('messages.message.batchNoRequired');
                    } else if (empty($formData['sample_qty'])) {
                        $message = config('messages.message.stbSampleQtyRequired');
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
                    } else if (empty($formData['sample_priority_id'])) {
                        $message = config('messages.message.samplePriorityIdRequired');
                    } else if (empty($formData['billing_type_id'])) {
                        $message = config('messages.message.billingTypeRequired');
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
                    } else {

                        //Starting transaction
                        DB::beginTransaction();

                        //Sending customer name to the response for save more button
                        $stb_order_hdr_id      = !empty($formData['stb_order_hdr_id']) ? $formData['stb_order_hdr_id'] : '0';
                        $stb_order_hdr_dtl_id  = !empty($formData['stb_order_hdr_dtl_id']) ? $formData['stb_order_hdr_dtl_id'] : '0';
                        $stb_stability_type_id = !empty($formData['stb_stability_type_id']) ? $formData['stb_stability_type_id'] : '0';
                        $customerId               = !empty($formData['customer_id']) ? $formData['customer_id'] : '0';
                        $sampleId              = !empty($formData['sample_id']) ? $formData['sample_id'] : '0';

                        //Setting the variable from request data
                        $formData['order_date']            = $order->getFormatedDateTime($formData['order_date'], $format = 'Y-m-d');
                        $formData['booking_date']            = $currentDateTime;
                        $formData['sampling_date']         = !empty($formData['sampling_date']) && !empty($formData['sampling_date']) ? $order->getFormatedDate($formData['sampling_date'], $format = 'Y-m-d H:i:s') : NULL;
                        $formData['product_category_id']     = $models->getMainProductCatParentId($formData['product_category_id']);
                        $formData['order_no']              = $order->generateOrderNumber($formData);
                        $formData['barcode']            = 'data:image/png;base64,' . DNS1D::getBarcodePNG($formData['order_no'], 'C128');
                        $formData['header_note']          = !empty($formData['header_note']) ? $order->createAndUpdateHeaderNote($formData['header_note']) : NULL;
                        $formData['stability_note']          = !empty($formData['stability_note']) ? $order->createAndUpdateStabilityNote($formData['stability_note']) : '';
                        $formData['status']             = '1';
                        $formData['po_no']             = !empty($formData['po_type']) && !empty($formData['po_no']) ? $formData['po_no'] : NULL;
                        $formData['po_date']             = !empty($formData['po_type']) && !empty($formData['po_date']) ? $order->getFormatedDateTime($formData['po_date'], $format = 'Y-m-d') : NULL;
                        $formData['reporting_to']          = !empty($formData['reporting_to']) ? $formData['reporting_to'] : NULL;
                        $formData['invoicing_to']          = !empty($formData['invoicing_to']) ? $formData['invoicing_to'] : NULL;
                        $formData['discount_value']          = !empty($formData['discount_value']) ? $formData['discount_value'] : NULL;
                        $formData['sample_qty']          = !empty($formData['sample_qty_unit']) ? trim($formData['sample_qty'] . ' ' . $formData['sample_qty_unit']) : trim($formData['sample_qty']);
                        $formData['created_by']            = USERID;

                        //Unsetting the variable from request data
                        $formData = $models->remove_null_value_from_array($models->unsetFormDataVariables($formData, array('_token', 'sample_description', 'test_param_alternative_id', 'order_parameters_detail', 'po_type', 'sample_type', 'hold_type', 'stb_order_hdr_id', 'stb_order_hdr_dtl_id', 'stb_stability_type_id', 'sample_qty_unit')));
                        if (!empty($formData['order_no']) && !empty($formData['sample_description_id'])) {
                            if (!empty($order->checkAddCustomerInvoivingRate($formData['sample_description_id'], $request->formData))) {

                                //Saving Order Master Detail
                                $orderId = DB::table('order_master')->insertGetId($formData);

                                //Saving Order Parameter Detail
                                DB::table('order_parameters_detail')->insert($stbOrder->get_formated_order_parameter_detail($request->formData, $orderId));

                                //Adding Order Parameter in Scheduling table
                                $order->createOrderSchedulingJobs($orderId);

                                //Generation of Expected Due Date and saving to the order master table
                                $order->generateUpdateOrderExpectedDueDate_v3($orderId);

                                //Saving/Updating Report Due Date and Department Due date in Order Parameter table
                                $order->updateReportDepartmentDueDate($orderId);

                                //Updating Sample Status of booked Order in  samples table
                                $order->UpdateSampleStatusOfBookedSample($orderId);

                                //Updating order progress log  table at the time of booking
                                $order->updateOrderStausLog($orderId, $formData['status']);

                                //Updating Order Booked Amount
                                $order->updateBookingSampleAmount($orderId,$formData);

                                //Updating order progress log  table at the time of booking
                                $stbOrder->updateStabilityPrototypeDetailStatus($orderId, $stb_order_hdr_id, $stb_order_hdr_dtl_id, $stb_stability_type_id);

                                $error   = '1';
                                $data    = $orderId;
                                $message = config('messages.message.OrderPlacedMsg');

                                //Committing the queries
                                DB::commit();
                            } else {
                                $error   = '0';
                                $message = '';
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

        return response()->json(['error' => $error, 'message' => $message, $data = $orderId]);
    }

    /**************************************************************
     * Send Mail Notification to stability order Customers
     * created_by:Praveen Singh
     * created_on:25-Feb-2019
     ***************************************************************/
    public function sendMailNotificationOfStabilityOrder(Request $request)
    {

        global $order, $models, $stbOrderPrototype, $mail;

        $error    = '0';
        $message  = '';
        $formData = array();

        //Saving record in orders table
        if (!empty($request->formData) && $request->isMethod('post')) {

            //Parsing the Serialze Dta
            parse_str($request->formData, $formData);

            if (!empty($formData['stb_order_hdr_dtl_id'])) {
                $mail->sendMail(array('stb_order_hdr_dtl_id' => array_values($formData['stb_order_hdr_dtl_id']), 'mailTemplateType' => '6'));
                $message = config('messages.message.mailSendSuccessMsg');
                $error = 1;
            }
        }

        return response()->json(['error' => $error, 'message' => $message]);
    }
}
