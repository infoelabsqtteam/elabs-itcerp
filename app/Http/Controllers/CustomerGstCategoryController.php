<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\CustomerGstCategory;
use App\Http\Requests;
use Auth;
use Validator;
use DB;
use Route;

class CustomerGstCategoryController extends Controller
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

        global $models, $customerGstCategory;

        $models = new Models();
        $customerGstCategory = new CustomerGstCategory();

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

        global $models, $customerGstCategory;

        $user_id            = defined('USERID') ? USERID : '0';
        $division_id        = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

        return view('master.customer_master.customer_gst_category.index', ['title' => 'Customer GST Category', '_customergstcategory' => 'active', 'user_id' => $user_id, 'division_id' => $division_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listCgCategory(Request $request)
    {

        global $models, $customerGstCategory;

        $error      = '0';
        $message    = config('messages.message.error');
        $data       = '';
        $formData   = array();
        $returnData = array();

        $returnData = DB::table('customer_gst_categories')
            ->where('customer_gst_categories.cgc_status', '1')
            ->orderBy('customer_gst_categories.cgc_id', 'ASC')
            ->get()
            ->toArray();

        //to formate created and updated date
        $models->formatTimeStampFromArray($returnData, DATETIMEFORMAT);

        return response()->json(['error' => $error, 'message' => $message, 'customerGstCategoryList' => $returnData]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCgCategory(Request $request)
    {

        global $models, $customerGstCategory;

        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = array();

        try {
            //Saving record in orders table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                //echo '<pre>';print_r($formData);die;

                if (empty($formData['cgc_name'])) {
                    $message = config('messages.message.cgcNameRequired');
                } else if (empty($formData['cgc_status'])) {
                    $message = config('messages.message.cgcStatusRequired');
                } else {

                    //Unsetting the variable from request data
                    $formData = $models->unsetFormDataVariables($formData, array('_token'));

                    //Saving the data in customer_gst_categories Master
                    DB::table('customer_gst_categories')->insertGetId($formData);

                    $error   = '1';
                    $message = config('messages.message.success');
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = config('messages.message.savedError');
        }

        return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCgCategory(Request $request)
    {

        global $models, $customerGstCategory;

        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = array();

        try {
            //Saving record in orders table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                //echo '<pre>';print_r($formData);die;

                if (!empty($formData['id'])) {
                    $error  = '1';
                    $message = '';
                    $returnData = $customerGstCategory->getRow($formData['id']);
                } else {
                    $message = config('messages.message.error');
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = config('messages.message.savedError');
        }

        return response()->json(['error' => $error, 'message' => $message, 'customerGstCategory' => $returnData]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCgCategory(Request $request)
    {

        global $models, $customerGstCategory;

        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = array();

        try {
            //Saving record in orders table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                //echo '<pre>';print_r($formData);die;

                if (empty($formData['cgc_id'])) {
                    $message = config('messages.message.error');
                } else if (empty($formData['cgc_name'])) {
                    $message = config('messages.message.cgcNameRequired');
                } else if (empty($formData['cgc_status'])) {
                    $message = config('messages.message.cgcStatusRequired');
                } else {

                    //Getting primary Id of the Table
                    $cgcId = !empty($formData['cgc_id']) ? $formData['cgc_id'] : '0';

                    //Unsetting the variable from request data
                    $formData = $models->unsetFormDataVariables($formData, array('_token', 'cgc_id'));

                    //Updating the data in customer_gst_categories Master
                    if (DB::table('customer_gst_categories')->where('customer_gst_categories.cgc_id', $cgcId)->update($formData)) {
                        $error   = '1';
                        $message = config('messages.message.updated');
                    } else {
                        $error   = '1';
                        $message = config('messages.message.savedNoChange');
                    }
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = config('messages.message.updatedError');
        }

        return response()->json(['error' => $error, 'message' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCgCategory(Request $request)
    {

        global $models, $customerGstCategory;

        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = array();

        try {
            //Saving record in orders table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing Serialize form data
                parse_str($request->formData, $formData);

                if (empty($formData['cgc_id'])) {
                    $message = config('messages.message.error');
                } else {

                    //Getting primary Id of the Table
                    $cgcId = !empty($formData['cgc_id']) ? $formData['cgc_id'] : '0';
                    $dataExist = DB::table('customer_master')->where('customer_master.customer_gst_category_id', '=', $cgcId)->first();

                    if (empty($dataExist) && DB::table('customer_gst_categories')->where('customer_gst_categories.cgc_id', '=', $cgcId)->delete()) {
                        $error    = '1';
                        $message = config('messages.message.deleted');
                    } else {
                        $message = config('messages.message.deletedError');
                    }
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = config('messages.message.deletedError');
        }

        return response()->json(['error' => $error, 'message' => $message]);
    }
}
