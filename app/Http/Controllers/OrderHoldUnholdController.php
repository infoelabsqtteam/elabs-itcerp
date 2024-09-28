<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\User;
use App\Module;
use App\Customer;
use App\Order;
use App\SendMail;
use Validator;
use DB;
use Route;

class OrderHoldUnholdController extends Controller
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

        global $models, $customer, $module, $order, $sendMail;

        $models   = new Models();
        $customer = new Customer();
        $module   = new Module();
        $order       = new Order();
        $sendMail = new SendMail();

        //Checking Session
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->session = Auth::user();
            parent::__construct($this->session);
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

        global $models, $customer;

        $user_id            = defined('USERID') ? USERID : '0';
        $division_id           = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
        $customPermission   = defined('CUSTOMPERMISSIONS') ? CUSTOMPERMISSIONS : array();
        $hasHoldUnHoldPerm  = !empty($customPermission['hold_unhold_permission']) ? true : false;

        return view('master.customer_master.hold_unhold_customers.index', ['title' => 'Hold Unhold Customers', '_hold_unhold_customers' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids, 'hasHoldUnHoldPerm' => $hasHoldUnHoldPerm]);
    }

    /**
     * Get hold list of customers on page load.
     * Date : 08-04-31
     * Author : Ruby
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getHoldCustomerList(Request $request)
    {

        global $models;

        $error          = '0';
        $message           = config('messages.message.error');
        $customers       = array();

        $user_id            = defined('USERID') ? USERID : '0';
        $division_id        = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

        $searchArry        = !empty($request['data']['formData']) ? $request['data']['formData'] : array();
        $limitFrom         = isset($searchArry['limitFrom']) ? $searchArry['limitFrom'] : '0';
        $limitTo           = isset($searchArry['limitTo']) ? $searchArry['limitTo'] : TOTAL_RECORD;
        $customPermission  = defined('CUSTOMPERMISSIONS') ? CUSTOMPERMISSIONS : array();
        $hasHoldUnHoldPerm = !empty($customPermission['hold_unhold_permission']) ? true : false;

        if (!empty($hasHoldUnHoldPerm)) {

            $error   = '1';
            $message = '';

            $customerObj = DB::table('customer_master')
                ->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', '=', 'customer_master.invoicing_type_id')
                ->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
                ->join('state_db', 'state_db.state_id', '=', 'customer_master.customer_state')
                ->join('customer_billing_types', 'customer_master.billing_type', '=', 'customer_billing_types.billing_type_id')
                ->join('users', 'customer_master.sale_executive', '=', 'users.id')
                ->join('users as u', 'customer_master.created_by', '=', 'u.id')
                ->leftJoin('order_sample_priority', 'order_sample_priority.sample_priority_id', '=', 'customer_master.customer_priority_id')
                ->leftJoin('customer_hold_dtl', function ($join) {
                    $join->on('customer_hold_dtl.chd_customer_id', 'customer_master.customer_id');
                    $join->whereRaw('customer_hold_dtl.chd_id IN (SELECT MAX(chd.chd_id) FROM customer_hold_dtl chd WHERE chd.chd_customer_id = customer_master.customer_id)');
                })
                ->when(!empty($division_id), function ($query) use ($division_id) {
                    $query->join('order_master', 'order_master.customer_id', 'customer_master.customer_id');
                    return $query->where('order_master.division_id', '=', $division_id);
                })
                ->select('users.*', 'order_sample_priority.sample_priority_name as customerPriorityType', 'customer_billing_types.billing_type as billingType', 'customer_master.*', 'customer_master.created_at as customer_created_at', 'customer_master.customer_id as new_customer_id','customer_master.updated_at as customer_updated_at', 'state_db.*', 'city_db.*', 'u.name as createdBy', 'customer_invoicing_types.invoicing_type','customer_hold_dtl.chd_hold_description','customer_hold_dtl.chd_hold_date','customer_hold_dtl.chd_hold_status','customer_hold_dtl.chd_id as chd_hold_id','customer_hold_dtl.chd_hold_by')
                ->where('customer_master.customer_status', '3')
                ->orderBy('customer_hold_dtl.chd_id', 'DESC')
                ->groupBy('customer_master.customer_id');

            //Appling Multisearch
            $this->getCustomerListMultisearch($searchArry, $customerObj);
            $customers = $customerObj->get()->toArray();

            //Format the date time
            $models->formatTimeStampFromArray($customers, DATETIMEFORMAT);
        } else {
            $error   = '0';
            $message = config('messages.message.permissionDenied');
        }

        //echo '<pre>';print_r($customers);die;
        return response()->json(['error' => $error, 'message' => $message, 'customersList' => $customers,]);
    }

    /**
     * get customers using multisearch.
     * Date : 08-04-31
     * Author : Ruby
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCustomerListMultisearch($searchArry, $customerObj)
    {

        global $models;

        if (!empty($searchArry['search_keyword'])) {
            $customerObj->where('customer_master.customer_code', '=', $searchArry['search_keyword']);
        }
        if (!empty($searchArry['search_customer_code'])) {
            $customerObj->where('customer_master.customer_code', 'LIKE', '%' . $searchArry['search_customer_code'] . '%');
        }
        if (!empty($searchArry['search_logic_customer_code'])) {
            $customerObj->where('customer_master.logic_customer_code', 'LIKE', '%' . $searchArry['search_logic_customer_code'] . '%');
        }
        if (!empty($searchArry['search_customer_name'])) {
            $customerObj->where('customer_master.customer_name', 'like', '%' . $searchArry['search_customer_name'] . '%');
        }
        if (!empty($searchArry['search_customer_address'])) {
            $customerObj->where('customer_master.customer_address', 'like', '%' . $searchArry['search_customer_address'] . '%');
        }
        if (!empty($searchArry['search_customer_email'])) {
            $customerObj->where('customer_master.customer_email', 'like', '%' . $searchArry['search_customer_email'] . '%');
        }
        if (!empty($searchArry['search_billing_type'])) {
            $customerObj->where('customer_billing_types.billing_type', 'like', '%' . $searchArry['search_billing_type'] . '%');
        }
        if (!empty($searchArry['search_invoicing_type'])) {
            $customerObj->where('customer_invoicing_types.invoicing_type', 'like', '%' . $searchArry['search_invoicing_type'] . '%');
        }
        if (!empty($searchArry['search_created_by'])) {
            $customerObj->where('u.name', 'like', '%' . $searchArry['search_created_by'] . '%');
        }
        if (!empty($searchArry['search_created_at'])) {
            $customerObj->where('customer_master.created_at', 'like', '%' . $searchArry['search_created_at'] . '%');
        }
        if (!empty($searchArry['search_updated_at'])) {
            $customerObj->where('customer_master.updated_at', 'like', '%' . $searchArry['search_updated_at'] . '%');
        }
        return $customerObj;
    }

    /**
     * Unhold customer and its orders
     * Date : 08-04-31
     * Author : Ruby
     */
    public function unholdCustomer(Request $request)
    {

        global $models, $order, $customer;

        $error           = '0';
        $message         = config('messages.message.unHoldError');
        $data            = '';
        $formData        = array();

        try {

            //Saving record in table
            if (!empty($request->all()) && $request->isMethod('post')) {

                //Parsing the form Data
                $formData = $request->all();

                if (empty($formData['chd_customer_id'])) {
                    $message = config('messages.message.error');
                } else if (empty($formData['chd_customer_status'])) {
                    $message = config('messages.message.error');
                } else {

                    //Starting transaction
                    DB::beginTransaction();

                    //Updating Customer Detail
                    if (DB::table('customer_master')->where('customer_id', $formData['chd_customer_id'])->update(['customer_master.customer_status' => $formData['chd_customer_status']])) {

                        //Unholding all order which is in hold Stage
                        $order->unHoldAllOrderOfCustomer($formData['chd_customer_id'], '1');

                        //Success Message
                        $error = '1';
                        $message = config('messages.message.unHoldSuccess');

                        //Committing the queries
                        DB::commit();
                    } else {
                        $error = '1';
                        $message = config('messages.message.savedNoChange');
                    }
                }
            }
        } catch (\Exception $e) {
            $message = config('messages.message.unHoldError');
        }
        return response()->json(array('error' => $error, 'message' => $message, 'data' => $data));
    }

    /**
     * Desc: Sending Mail to Hold customer
     * Date : 12-April-2021
     * Author : Praveen Singh
     */
    public function sendMailToCustomers(Request $request)
    {

        global $models, $customer, $sendMail;

        $error           = '0';
        $message         = config('messages.message.error');
        $formData        = $flag = array();

        try {
            //Saving record in table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing the form Data
                parse_str($request->formData, $formData);

                if (empty($formData['customer_id'])) {
                    $message = config('messages.message.checkBoxRequiredError');
                } else {
                    foreach ($formData['customer_id'] as $customer_id) {
                        $flag[] = $sendMail->sendMail(['mailTemplateType' => '7', 'customer_id' => $customer_id]);
                    }
                    if (!empty($flag)) {
                        $error   = '1';
                        $message = config('messages.message.mailSendSuccessMsg');
                    } else {
                        $error   = '0';
                        $message = config('messages.message.mailSendErrorMsg');
                    }
                }
            }
        } catch (\Exception $e) {
            $message = config('messages.message.error');
        }

        return response()->json(array('error' => $error, 'message' => $message));
    }
}
