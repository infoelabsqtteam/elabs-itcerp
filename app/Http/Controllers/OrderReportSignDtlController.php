<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use App\OrderReportSignDtl;
use Session;
use Validator;
use Route;
use DB;

class OrderReportSignDtlController extends Controller
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

        global $models, $orderReportSign;

        $models = new Models();
        $orderReportSign = new OrderReportSignDtl();

        //MiddleWare CHecking Loin Inn Authentication
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

        global $models, $orderReportSign;

        $user_id            = defined('USERID') ? USERID : '0';
        $division_id        = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

        return view('master.order_report_signatures.index', ['title' => 'Defined Test Standard Master', '_order_report_signatures' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function listMasters(Request $request)
    {
        global $models, $orderReportSign;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();

        $masterDataList = DB::table('order_report_sign_dtls')
            ->join('users as employees', 'employees.id', 'order_report_sign_dtls.orsd_employee_id')
            ->join('divisions', 'divisions.division_id', 'order_report_sign_dtls.orsd_division_id')
            ->join('product_categories', 'product_categories.p_category_id', 'order_report_sign_dtls.orsd_product_category_id')
            ->join('users', 'users.id', 'order_report_sign_dtls.orsd_created_by')
            ->select('order_report_sign_dtls.*', 'employees.name as orsd_employee_name', 'divisions.division_name as orsd_division_name', 'product_categories.p_category_name as orsd_product_category_name', 'users.name as orsd_created_name')
            ->orderBy('order_report_sign_dtls.orsd_employee_id', 'DESC')
            ->orderBy('order_report_sign_dtls.orsd_division_id', 'DESC')
            ->orderBy('order_report_sign_dtls.orsd_product_category_id', 'DESC')
            ->groupBy(['order_report_sign_dtls.orsd_employee_id', 'order_report_sign_dtls.orsd_division_id', 'order_report_sign_dtls.orsd_product_category_id'])
            ->get()
            ->toArray();

        $error    = !empty($masterDataList) ? '1' :  '0';
        $message  = !empty($error) ? '' : config('messages.message.error');

        //to formate created and updated date
        $models->formatTimeStampFromArray($masterDataList, DATETIMEFORMAT);

        foreach ($masterDataList as $key => $values) {
            $values->equipment_list_saved = DB::table('order_report_sign_dtls')
                ->join('equipment_type', 'order_report_sign_dtls.orsd_equipment_type_id', '=', 'equipment_type.equipment_id')
                ->where('order_report_sign_dtls.orsd_employee_id', '=', $values->orsd_employee_id)
                ->where('order_report_sign_dtls.orsd_division_id', '=', $values->orsd_division_id)
                ->where('order_report_sign_dtls.orsd_product_category_id', '=', $values->orsd_product_category_id)
                ->orderBy('equipment_type.equipment_name', 'ASC')
                ->pluck('equipment_type.equipment_name as orsd_equipment_type_name', 'equipment_type.equipment_id as orsd_equipment_type_id')
                ->all();
        }
        return response()->json(array('error' => $error, 'message' => $message, 'masterDataList' => $masterDataList));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createMaster(Request $request)
    {

        global $models, $orderReportSign;

        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = array();
        $tableName = 'order_report_sign_dtls';

        try {

            //Saving record in table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing the form Data
                parse_str($request->formData, $formData);

                if (!empty($formData)) {

                    if (empty($formData['orsd_employee_id'])) {
                        $message = config('messages.message.required');
                    } else if (empty($formData['orsd_division_id'])) {
                        $message = config('messages.message.required');
                    } else if (empty($formData['orsd_product_category_id'])) {
                        $message = config('messages.message.required');
                    } else if (empty($formData['orsd_equipment_type_id'])) {
                        $message = config('messages.message.required');
                    } else if(!empty($orderReportSign->validateUniqueRecord($formData))){
                        $message = config('messages.message.existError');
                    } else {

                        //Unsetting the variable from request data
                        $formData = $models->unsetFormDataVariables($formData, array('_token'));
                        foreach ($formData['orsd_equipment_type_id'] as $key => $orsd_equipment_type_id) {
                            $saved = DB::table($tableName)->insert([
                                'orsd_employee_id'          => $formData['orsd_employee_id'],
                                'orsd_division_id'          => $formData['orsd_division_id'],
                                'orsd_product_category_id'  => $formData['orsd_product_category_id'],
                                'orsd_equipment_type_id'    => $orsd_equipment_type_id,
                                'orsd_created_by'           => USERID,
                            ]);
                        }
                        if (!empty($saved)) {
                            $error   = '1';
                            $message = config('messages.message.saved');
                        } else {
                            $message = config('messages.message.savedError');
                        }
                    }
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            if ($ex->errorInfo[1] == '1062') {
                $message = config('messages.message.uniqueKeyError');
            } else {
                $message = config('messages.message.existError');
            }
        }
        return response()->json(array('error' => $error, 'message' => $message, 'data' => $data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function viewMaster(Request $request, $id)
    {

        global $models, $orderReportSign;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();

        if ($id) {
            $masterRecord = DB::table('order_report_sign_dtls')->where('order_report_sign_dtls.orsd_id', $id)->first();
            $masterData   = DB::table('order_report_sign_dtls')
                ->join('users as employees', 'employees.id', 'order_report_sign_dtls.orsd_employee_id')
                ->join('divisions', 'divisions.division_id', 'order_report_sign_dtls.orsd_division_id')
                ->join('product_categories', 'product_categories.p_category_id', 'order_report_sign_dtls.orsd_product_category_id')
                ->join('users', 'users.id', 'order_report_sign_dtls.orsd_created_by')
                ->select('order_report_sign_dtls.*', 'employees.name as orsd_employee_name', 'divisions.division_name as orsd_division_name', 'product_categories.p_category_name as orsd_product_category_name', 'users.name as orsd_created_name')
                ->where('order_report_sign_dtls.orsd_employee_id', $masterRecord->orsd_employee_id)
                ->where('order_report_sign_dtls.orsd_division_id', $masterRecord->orsd_division_id)
                ->where('order_report_sign_dtls.orsd_product_category_id', $masterRecord->orsd_product_category_id)
                ->first();

            if (!empty($masterData)) {
                $masterData->orsdEquipmentTypeList = array_values(DB::table('order_report_sign_dtls')
                    ->join('equipment_type', 'order_report_sign_dtls.orsd_equipment_type_id', '=', 'equipment_type.equipment_id')
                    ->where('order_report_sign_dtls.orsd_employee_id', '=', $masterData->orsd_employee_id)
                    ->where('order_report_sign_dtls.orsd_division_id', '=', $masterData->orsd_division_id)
                    ->where('order_report_sign_dtls.orsd_product_category_id', '=', $masterData->orsd_product_category_id)
                    ->orderBy('equipment_type.equipment_name', 'ASC')
                    ->pluck('equipment_type.equipment_id as orsd_equipment_type_id','equipment_type.equipment_id as orsd_equipment_type_id')
                    ->all());
            }

            $error    = !empty($masterData) ? 1 : '0';
            $message  = $error ? '' : $message;
        }
        return response()->json(array('error' => $error, 'message' => $message, 'editMasterData' => $masterData));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function updateMaster(Request $request)
    {
        global $models, $orderReportSign;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();
        $tableName = 'order_report_sign_dtls';

        try {

            //Saving record in table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing the form Data
                parse_str($request->formData, $formData);

                if (empty($formData['orsd_employee_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['orsd_division_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['orsd_product_category_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['orsd_equipment_type_id'])) {
                    $message = config('messages.message.definedTestStandardRequired');
                } else {

                    $orsd_id = !empty($formData['orsd_id']) ? $formData['orsd_id'] : 0;
                    if (!empty($orsd_id)) {

                        //Delete Previous Entry
                        DB::table($tableName)->where('order_report_sign_dtls.orsd_employee_id', $formData['orsd_employee_id'])->where('order_report_sign_dtls.orsd_division_id', $formData['orsd_division_id'])->where('order_report_sign_dtls.orsd_product_category_id', $formData['orsd_product_category_id'])->delete();

                        //Unsetting the variable from request data
                        $formData = $models->unsetFormDataVariables($formData, array('_token', 'orsd_id'));
                        foreach ($formData['orsd_equipment_type_id'] as $key => $orsd_equipment_type_id) {
                            $saved = DB::table($tableName)->insert([
                                'orsd_employee_id'          => $formData['orsd_employee_id'],
                                'orsd_division_id'          => $formData['orsd_division_id'],
                                'orsd_product_category_id'  => $formData['orsd_product_category_id'],
                                'orsd_equipment_type_id'    => $orsd_equipment_type_id,
                                'orsd_created_by'           => USERID,
                            ]);
                        }

                        if (!empty($saved)) {
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
            if ($ex->errorInfo[1] == '1062') {
                $message = config('messages.message.uniqueKeyError');
            } else {
                $message = config('messages.message.updatedError');
            }
        }
        return response()->json(array('error' => $error, 'message' => $message, 'data' => $data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroyMaster($id)
    {
        global $models;

        $error   = '0';
        $message = '';
        $data    = '';
        $tableName = 'order_report_sign_dtls';

        try {
            $row = DB::table($tableName)->where('orsd_id', $id)->first();
            if ($row) {
                DB::table($tableName)->where('orsd_employee_id', $row->orsd_employee_id)->where('orsd_division_id', $row->orsd_division_id)->where('orsd_product_category_id', $row->orsd_product_category_id)->delete();
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
