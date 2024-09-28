<?php

/*****************************
 *Created By  : Ruby
 *Created On  : 06-April-2020
 *Modified On : 
 ******************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use Session;
use Validator;
use Route;
use DB;

class OrderDefinedTestStandardController extends Controller
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

        global $models;

        $models = new Models();

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

        global $models, $orderReportGroup;

        $user_id            = defined('USERID') ? USERID : '0';
        $division_id           = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

        return view('master.defined_test_standard_master.index', ['title' => 'Defined Test Standard Master', '_defined_test_standard_master' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createMaster(Request $request)
    {

        global $models;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();
        $tableName = 'order_defined_test_std_dtl';

        //Saving record in table
        if (!empty($request->formData) && $request->isMethod('post')) {

            //Parsing the form Data
            parse_str($request->formData, $formData);
            if (!empty($formData)) {

                if (empty($formData['odtsd_branch_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['odtsd_product_category_id'])) {
                    $message = config('messages.message.required');
                } else if (empty($formData['odtsd_test_standard_id'])) {
                    $message = config('messages.message.definedTestStandardRequired');
                } else {

                    try {

                        //Unsetting the variable from request data
                        $formData = $models->unsetFormDataVariables($formData, array('_token'));
                        foreach ($formData['odtsd_test_standard_id'] as $key => $testStdId) {
                            $saved = DB::table($tableName)->insert([
                                'odtsd_branch_id'           => $formData['odtsd_branch_id'],
                                'odtsd_product_category_id' => $formData['odtsd_product_category_id'],
                                'odtsd_test_standard_id' => $testStdId,
                                'odtsd_created_by' => USERID,
                            ]);
                        }
                        if (!empty($saved)) {
                            $error   = '1';
                            $message = config('messages.message.saved');
                        } else {
                            $message = config('messages.message.savedError');
                        }
                    } catch (\Illuminate\Database\QueryException $ex) {
                        $error_code = $ex->errorInfo[1];
                        if ($error_code == '1062') {
                            $message = config('messages.message.uniqueKeyError');
                        } else {
                            $message = config('messages.message.existError');
                        }
                    }
                }
            }
        }
        return response()->json(array('error' => $error, 'message' => $message, 'data' => $data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function listMasters(Request $request)
    {
        global $models;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();

        $masterDataList = DB::table('order_defined_test_std_dtl')
            ->join('divisions', 'divisions.division_id', 'order_defined_test_std_dtl.odtsd_branch_id')
            ->join('product_categories', 'product_categories.p_category_id', 'order_defined_test_std_dtl.odtsd_product_category_id')
            ->join('users', 'users.id', 'order_defined_test_std_dtl.odtsd_created_by')
            ->select('order_defined_test_std_dtl.*', 'order_defined_test_std_dtl.modified_at as updated_at', 'divisions.division_name as odtsd_division_name', 'product_categories.p_category_name as odtsd_product_category_name', 'users.name as odtsd_created_name')
            ->groupBy('order_defined_test_std_dtl.odtsd_branch_id')
            ->groupBy('order_defined_test_std_dtl.odtsd_product_category_id')
            ->get()
            ->toArray();
        $error    = !empty($masterDataList) ? '1' :  '0';
        $message  = !empty($error) ? '' : config('messages.message.error');
        //to formate created and updated date
        $models->formatTimeStampFromArray($masterDataList, DATETIMEFORMAT);

        foreach ($masterDataList as $key => $values) {
            $values->test_standard_list = DB::table('order_defined_test_std_dtl')
                ->join('test_standard', 'order_defined_test_std_dtl.odtsd_test_standard_id', '=', 'test_standard.test_std_id')
                ->where('order_defined_test_std_dtl.odtsd_branch_id', '=', $values->odtsd_branch_id)
                ->where('order_defined_test_std_dtl.odtsd_product_category_id', '=', $values->odtsd_product_category_id)
                ->pluck('test_standard.test_std_name as odtsd_test_std_name', 'test_standard.test_std_id as odtsd_test_standard_id')
                ->toArray();
        }

        return response()->json(array('error' => $error, 'message' => $message, 'masterDataList' => $masterDataList));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function viewMaster(Request $request, $id)
    {

        global $models, $orderReportGroup;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();

        if ($id) {
            $record = DB::table('order_defined_test_std_dtl')->where('order_defined_test_std_dtl.odtsd_id', $id)->first();

            $masterData = DB::table('order_defined_test_std_dtl')
                ->join('divisions', 'divisions.division_id', 'order_defined_test_std_dtl.odtsd_branch_id')
                ->join('product_categories', 'product_categories.p_category_id', 'order_defined_test_std_dtl.odtsd_product_category_id')
                ->join('users', 'users.id', 'order_defined_test_std_dtl.odtsd_created_by')
                ->select('order_defined_test_std_dtl.*', 'divisions.division_name as odtsd_division_name', 'product_categories.p_category_name as odtsd_product_category_name', 'users.name as odtsd_created_name')
                ->where('order_defined_test_std_dtl.odtsd_branch_id', $record->odtsd_branch_id)
                ->where('order_defined_test_std_dtl.odtsd_product_category_id', $record->odtsd_product_category_id)
                ->first();

            if (!empty($masterData)) {
                $masterData->odtsdTestStandardList = array_values(DB::table('order_defined_test_std_dtl')
                    ->join('test_standard', 'order_defined_test_std_dtl.odtsd_test_standard_id', '=', 'test_standard.test_std_id')
                    ->where('order_defined_test_std_dtl.odtsd_branch_id', $record->odtsd_branch_id)
                    ->where('order_defined_test_std_dtl.odtsd_product_category_id', $record->odtsd_product_category_id)

                    ->pluck('order_defined_test_std_dtl.odtsd_test_standard_id', 'test_standard.test_std_id', 'test_standard.test_std_name')
                    ->all());
            }
            //echo'<pre>'; print_r($masterData); die;
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

        global $models, $orderReportGroup;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();
        $tableName = 'order_defined_test_std_dtl';

        //Saving record in table
        if (!empty($request->formData) && $request->isMethod('post')) {

            //Parsing the form Data
            parse_str($request->formData, $formData);
            if (empty($formData['odtsd_branch_id'])) {
                $message = config('messages.message.required');
            } else if (empty($formData['odtsd_product_category_id'])) {
                $message = config('messages.message.required');
            } else if (empty($formData['odtsd_test_standard_id'])) {
                $message = config('messages.message.definedTestStandardRequired');
            } else {
                try {
                    $odtsd_id = !empty($formData['odtsd_id']) ? $formData['odtsd_id'] : 0;
                    //Delete Previous Entry
                    DB::table($tableName)->where('order_defined_test_std_dtl.odtsd_branch_id', $formData['odtsd_branch_id'])->where('order_defined_test_std_dtl.odtsd_product_category_id', $formData['odtsd_product_category_id'])->delete();

                    //Unsetting the variable from request data
                    $formData = $models->unsetFormDataVariables($formData, array('_token', 'odtsd_id'));
                    foreach ($formData['odtsd_test_standard_id'] as $key => $testStdId) {
                        $saved = DB::table($tableName)->insert([
                            'odtsd_branch_id'           => $formData['odtsd_branch_id'],
                            'odtsd_product_category_id' => $formData['odtsd_product_category_id'],
                            'odtsd_test_standard_id'    => $testStdId,
                            'odtsd_created_by'          => USERID,
                        ]);
                    }
                    if (!empty($odtsd_id)) {
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
                } catch (\Illuminate\Database\QueryException $ex) {
                    if ($error_code == '1062') {
                        $message = config('messages.message.uniqueKeyError');
                    } else {
                        $message = config('messages.message.updatedError');
                    }
                }
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
        $tableName = 'order_defined_test_std_dtl';

        try {
            $row = DB::table($tableName)->where('odtsd_id', $id)->first();

            if ($row) {
                DB::table($tableName)->where('odtsd_branch_id', $row->odtsd_branch_id)->where('odtsd_product_category_id', $row->odtsd_product_category_id)->delete();
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
