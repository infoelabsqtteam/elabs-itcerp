<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\customerComCrmEmailAddress;
use Validator;
use DB;
use Route;

class CustomerComCrmEmailAddressController extends Controller
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
        global $models, $customerComCrmEmailAddress;

        $models = new Models();
        $customerComCrmEmailAddress = new customerComCrmEmailAddress();

        //Middleware for Auth validation
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
        global $models, $customerComCrmEmailAddress;

        $user_id            = defined('USERID') ? USERID : '0';
        $division_id        = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

        return view('master.customer_master.customer_com_crm_email_addresses.index', ['title' => 'Customer Com CRM Addresses', '_customer_com_crm_email_addresses' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
    }

    /**
     * Display a listing of the resource.
     * get all customers data list
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {

        global $models, $customerComCrmEmailAddress;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();

        $customerComCrmsList = DB::table('customer_com_crm_email_addresses')
            ->join('divisions', 'divisions.division_id', '=', 'customer_com_crm_email_addresses.cccea_division_id')
            ->join('product_categories', 'product_categories.p_category_id', '=', 'customer_com_crm_email_addresses.cccea_product_category_id')
            ->join('users as createdBy', 'createdBy.id', 'customer_com_crm_email_addresses.cccea_created_by')
            ->select('divisions.division_name as cccea_division_name', 'product_categories.p_category_name as cccea_product_category_name', 'customer_com_crm_email_addresses.*', 'createdBy.name as cccea_created_by_name')
            ->orderBy('customer_com_crm_email_addresses.cccea_name', 'ASC')
            ->get()
            ->toArray();

        //Format the date time
        $models->formatTimeStampFromArray($customerComCrmsList, DATETIMEFORMAT);

        //echo '<pre>';print_r($customerComCrmsList);die;
        return response()->json(array('error' => $error, 'message' => $message, 'customerComCrmsList' => $customerComCrmsList));
    }

    /** create new customer
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        global $models, $customerComCrmEmailAddress;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();

        try {

            if ($request->isMethod('post') && !empty($request['formData'])) {

                //parse searlize data
                parse_str($request['formData'], $formData);

                if (empty($formData['cccea_division_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['cccea_product_category_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['cccea_name'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['cccea_email'])) {
                    $message = config('messages.message.required');
                } else if (!empty($customerComCrmEmailAddress->isCustomerComCrmExist($formData, 'add'))) {
                    $message = config('messages.message.existError');
                } else {

                    //Unsetting the variable from request data
                    $formData                       = $models->unsetFormDataVariables($formData, array('_token'));
                    $formData['cccea_name']         = trim($formData['cccea_name']);
                    $formData['cccea_email']        = trim($formData['cccea_email']);
                    $formData['cccea_status']       = '1';
                    $formData['cccea_created_by']   = USERID;

                    if (!empty(DB::table('customer_com_crm_email_addresses')->insertGetId($formData))) {
                        $error   = '1';
                        $message = config('messages.message.saved');
                    } else {
                        $message = config('messages.message.savedError');
                    }
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = config('messages.message.savedError');
        }

        return response()->json(array('error' => $error, 'message' => $message, 'data' => $data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = $editCustomerComCrmData = array();

        if (!empty($id)) {
            // get user by email id
            $editCustomerComCrmData = DB::table('customer_com_crm_email_addresses')->where('customer_com_crm_email_addresses.cccea_id', '=', $id)->first();
        }

        $error    = !empty($editCustomerComCrmData) ? '1' : '0';
        $message  = $error ? '' : $message;

        return response()->json(array('error' => $error, 'message' => $message, 'editCustomerComCrmData' => $editCustomerComCrmData));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        global $models, $customerComCrmEmailAddress;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();

        try {

            if ($request->isMethod('post') && !empty($request['formData'])) {

                //parse searlize data
                parse_str($request['formData'], $formData);
                //echo'<pre>';print_r($formData); die;

                if (empty($formData['cccea_division_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['cccea_product_category_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['cccea_name'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['cccea_email'])) {
                    $message = config('messages.message.required');
                } else if (!empty($customerComCrmEmailAddress->isCustomerComCrmExist($formData, 'edit'))) {
                    $message = config('messages.message.existError');
                } else {

                    $cccea_id                   = !empty($formData['cccea_id']) ? $formData['cccea_id'] : '0';
                    $formData['cccea_name']     = trim($formData['cccea_name']);
                    $formData['cccea_email']    = trim($formData['cccea_email']);
                    $formData['cccea_status']   = !empty($formData['cccea_status']) ? $formData['cccea_status'] : '1';

                    //Unsetting the variable from request data
                    $formData = $models->unsetFormDataVariables($formData, array('_token', 'cccea_id'));
                    if (!empty($cccea_id) && !empty($formData)) {
                        if (DB::table('customer_com_crm_email_addresses')->where('customer_com_crm_email_addresses.cccea_id', $cccea_id)->update($formData)) {
                            $error   = '1';
                            $message = config('messages.message.updated');
                        } else {
                            $error   = '1';
                            $message = config('messages.message.savedNoChange');
                        }
                    } else {
                        $message = config('messages.message.updatedError');
                    }
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = config('messages.message.updatedError');
        }

        return response()->json(array('error' => $error, 'message' => $message, 'data' => $data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($cccea_id)
    {

        $error   = '0';
        $message = '';
        $data    = '';

        try {
            if (DB::table('customer_com_crm_email_addresses')->where('customer_com_crm_email_addresses.cccea_id', '=', $cccea_id)->delete()) {
                $error   = '1';
                $message = config('messages.message.deleted');
            } else {
                $message = config('messages.message.deletedError');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = config('messages.message.deletedErrorFKC');
        }
        return response()->json(['error' => $error, 'message' => $message]);
    }
}
