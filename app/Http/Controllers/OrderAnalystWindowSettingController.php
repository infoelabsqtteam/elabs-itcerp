<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Str;
use Auth;
use App\Models;
use App\OrderAnalystWindowSetting;
use Session;
use Validator;
use Route;
use DB;

class OrderAnalystWindowSettingController extends Controller
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

        global $models, $orderAnalystWindowSetting;

        $models = new Models();
        $orderAnalystWindowSetting = new OrderAnalystWindowSetting();

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

        global $models, $orderAnalystWindowSetting;

        $user_id            = defined('USERID') ? USERID : '0';
        $division_id        = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

        return view('master.order_analyst_window_settings.index', ['title' => 'Defined Test Standard Master', '_order_analyst_window_settings' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function listMasters(Request $request)
    {
        global $models, $orderAnalystWindowSetting;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();

        $masterDataList = DB::table('order_analyst_window_settings')
            ->join('divisions', 'divisions.division_id', 'order_analyst_window_settings.oaws_division_id')
            ->join('product_categories', 'product_categories.p_category_id', 'order_analyst_window_settings.oaws_product_category_id')
            ->join('users', 'users.id', 'order_analyst_window_settings.oaws_created_by')
            ->select('order_analyst_window_settings.*', 'divisions.division_name as oaws_division_name', 'product_categories.p_category_name as oaws_product_category_name', 'users.name as oaws_created_name')
            ->orderBy('order_analyst_window_settings.oaws_division_id', 'DESC')
            ->orderBy('order_analyst_window_settings.oaws_product_category_id', 'DESC')
            ->groupBy(['oaws_unique_id'])
            ->get()
            ->toArray();

        $error    = !empty($masterDataList) ? '1' :  '0';
        $message  = !empty($error) ? '' : config('messages.message.error');

        //to formate created and updated date
        $models->formatTimeStampFromArray($masterDataList, DATETIMEFORMAT);

        foreach ($masterDataList as $key => $values) {
            $values->equipment_list_saved = DB::table('order_analyst_window_settings')
                ->join('equipment_type', 'order_analyst_window_settings.oaws_equipment_type_id', '=', 'equipment_type.equipment_id')
                ->where('order_analyst_window_settings.oaws_unique_id', '=', $values->oaws_unique_id)
                ->where('order_analyst_window_settings.oaws_division_id', '=', $values->oaws_division_id)
                ->where('order_analyst_window_settings.oaws_product_category_id', '=', $values->oaws_product_category_id)
                ->orderBy('equipment_type.equipment_name', 'ASC')
                ->pluck('equipment_type.equipment_name as oaws_equipment_type_name', 'equipment_type.equipment_id as oaws_equipment_type_id')
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

        global $models, $orderAnalystWindowSetting;

        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = $flag = array();
        $tableName = 'order_analyst_window_settings';

        try {

            //Saving record in table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing the form Data
                parse_str($request->formData, $formData);

                if (!empty($formData)) {

                    if (empty($formData['oaws_division_id'])) {
                        $message = config('messages.message.required');
                    } else if (empty($formData['oaws_product_category_id'])) {
                        $message = config('messages.message.required');
                    } else if (empty($formData['oaws_equipment_type_id'])) {
                        $message = config('messages.message.required');
                    } else if(!empty($orderAnalystWindowSetting->validateUniqueRecord($formData))){
                        $message = config('messages.message.existError');
                    } else {

                        //Unsetting the variable from request data
                        $formData       = $models->unsetFormDataVariables($formData, array('_token'));
                        $oaws_unique_id = $orderAnalystWindowSetting->generateUniqueNumber();
                        foreach ($formData['oaws_equipment_type_id'] as $key => $oaws_equipment_type_id) {
                            $flag[] = DB::table($tableName)->insert([
                                'oaws_unique_id'            => $oaws_unique_id,
                                'oaws_division_id'          => $formData['oaws_division_id'],
                                'oaws_product_category_id'  => $formData['oaws_product_category_id'],
                                'oaws_equipment_type_id'    => $oaws_equipment_type_id,
                                'oaws_created_by'           => USERID,
                            ]);
                        }
                        if (!empty($flag)) {
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

        global $models, $orderAnalystWindowSetting;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();

        if ($id) {
            $masterRecord = DB::table('order_analyst_window_settings')->where('order_analyst_window_settings.oaws_id', $id)->first();
            $masterData   = DB::table('order_analyst_window_settings')
                ->join('divisions', 'divisions.division_id', 'order_analyst_window_settings.oaws_division_id')
                ->join('product_categories', 'product_categories.p_category_id', 'order_analyst_window_settings.oaws_product_category_id')
                ->join('users', 'users.id', 'order_analyst_window_settings.oaws_created_by')
                ->select('order_analyst_window_settings.*','divisions.division_name as oaws_division_name', 'product_categories.p_category_name as oaws_product_category_name', 'users.name as oaws_created_name')
                ->where('order_analyst_window_settings.oaws_unique_id', $masterRecord->oaws_unique_id)
                ->where('order_analyst_window_settings.oaws_division_id', $masterRecord->oaws_division_id)
                ->where('order_analyst_window_settings.oaws_product_category_id', $masterRecord->oaws_product_category_id)
                ->first();

            if (!empty($masterData)) {
                $masterData->orsdEquipmentTypeList = array_values(DB::table('order_analyst_window_settings')
                    ->join('equipment_type', 'order_analyst_window_settings.oaws_equipment_type_id', '=', 'equipment_type.equipment_id')
                    ->where('order_analyst_window_settings.oaws_unique_id', '=', $masterData->oaws_unique_id)
                    ->where('order_analyst_window_settings.oaws_division_id', '=', $masterData->oaws_division_id)
                    ->where('order_analyst_window_settings.oaws_product_category_id', '=', $masterData->oaws_product_category_id)
                    ->orderBy('equipment_type.equipment_name', 'ASC')
                    ->pluck('equipment_type.equipment_id as oaws_equipment_type_id','equipment_type.equipment_id as oaws_equipment_type_id')
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
        global $models, $orderAnalystWindowSetting;

        $error     = '0';
        $message   = config('messages.message.error');
        $data      = $oaws_id = '0';
        $formData  = $flag = $all_oaws_ids = $exist_all_oaws_ids = array();
        $tableName = 'order_analyst_window_settings';

        try {

            //Saving record in table
            if (!empty($request->formData) && $request->isMethod('post')) {

                //Parsing the form Data
                parse_str($request->formData, $formData);

                if (empty($formData['oaws_equipment_type_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['oaws_division_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['oaws_product_category_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['oaws_equipment_type_id'])) {
                    $message = config('messages.message.definedTestStandardRequired');
                } else {
                    $oaws_id = !empty($formData['oaws_id']) ? $formData['oaws_id'] : 0;                    
                    if (!empty($oaws_id)) {
                        //Delete Previous Entry
                        $masterRecord   = DB::table('order_analyst_window_settings')->where('order_analyst_window_settings.oaws_id', $oaws_id)->first();
                        $oaws_unique_id = !empty($masterRecord->oaws_unique_id) ? $masterRecord->oaws_unique_id : '0';
                        if(!empty($oaws_unique_id)){
                            $all_oaws_ids = DB::table($tableName)->where('order_analyst_window_settings.oaws_unique_id', $oaws_unique_id)->pluck('oaws_id')->all();
                            if(!empty($all_oaws_ids)){
                                foreach ($all_oaws_ids as $key => $oaws_id) {
                                    if(empty(DB::table('order_parameters_detail')->where('oaws_ui_setting_id',$oaws_id)->count())){
                                        DB::table($tableName)->where('order_analyst_window_settings.oaws_id', $oaws_id)->delete();
                                    }
                                }
                            }
                            //Unsetting the variable from request data
                            $formData = $models->unsetFormDataVariables($formData, array('_token'));
                            foreach ($formData['oaws_equipment_type_id'] as $key => $oaws_equipment_type_id) {
                                $hasEntry = DB::table($tableName)
                                    ->where('order_analyst_window_settings.oaws_unique_id', $oaws_unique_id)
                                    ->where('order_analyst_window_settings.oaws_division_id', $formData['oaws_division_id'])
                                    ->where('order_analyst_window_settings.oaws_product_category_id', $formData['oaws_product_category_id'])
                                    ->where('order_analyst_window_settings.oaws_equipment_type_id', $oaws_equipment_type_id)
                                    ->first();
                                if(empty($hasEntry)){
                                    $flag[] = DB::table($tableName)->insert([
                                        'oaws_unique_id'            => $oaws_unique_id,
                                        'oaws_division_id'          => $formData['oaws_division_id'],
                                        'oaws_product_category_id'  => $formData['oaws_product_category_id'],
                                        'oaws_equipment_type_id'    => $oaws_equipment_type_id,
                                        'oaws_created_by'           => USERID,
                                    ]);
                                }
                            }
                            $oaws_id = DB::table($tableName)->where('order_analyst_window_settings.oaws_division_id', $formData['oaws_division_id'])->where('order_analyst_window_settings.oaws_product_category_id', $formData['oaws_product_category_id'])->whereIn('order_analyst_window_settings.oaws_equipment_type_id', $formData['oaws_equipment_type_id'])->pluck('oaws_id')->first();
                        }
                        if (!empty($flag)) {
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
                $message = config('messages.message.foreignKeyDeleteError');
            } else {
                $message = config('messages.message.foreignKeyDeleteError');
            }
        }
        return response()->json(array('error' => $error, 'message' => $message, 'oaws_id' => $oaws_id));
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
        $tableName = 'order_analyst_window_settings';

        try {
            //Delete Previous Entry
            $masterRecord = DB::table('order_analyst_window_settings')->where('order_analyst_window_settings.oaws_id', $id)->first();
            if (!empty($masterRecord->oaws_unique_id)) {
                $all_oaws_ids = DB::table($tableName)->where('order_analyst_window_settings.oaws_unique_id', $masterRecord->oaws_unique_id)->pluck('oaws_id')->all();
                if(!empty($all_oaws_ids)){
                    foreach ($all_oaws_ids as $key => $oaws_id) {
                        if(empty(DB::table('order_parameters_detail')->where('oaws_ui_setting_id',$oaws_id)->count())){
                            DB::table($tableName)->where('order_analyst_window_settings.oaws_id', $oaws_id)->delete();
                        }
                    }
                }
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
