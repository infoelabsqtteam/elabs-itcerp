<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Company;
use App\Order;
use App\Models;
use App\Report;
use App\ReportSetting;
use Session;
use Validator;
use Route;
use DB;

class ReportSettingController extends Controller
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
    public function __construct(){

        global $order,$models,$report,$reportSetting;
	
        $order  = new Order();
        $models = new Models();
        $report = new Report();
        $reportSetting = new ReportSetting();
	
	//Checking the User Session
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->auth = Auth::user();
	    parent::__construct($this->auth);
	    //Checking current request is allowed or not
	    $allowedAction = array('index','navigation');
	    $actionData    = explode('@',Route::currentRouteAction());
	    $action        = !empty($actionData[1]) ? trim(strtolower($actionData[1])): '0';
	    if(defined('NOTALlOWEDTONAVIGATE') && empty(NOTALlOWEDTONAVIGATE) && in_array($action,$allowedAction)){
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
    public function index(){

	global $order,$models,$report,$reportSetting;

	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

	return view('sales.reports.report_settings.index',['title' => 'Report Settings','_test_report_settings' => 'active','user_id' => $user_id,'division_id' => $division_id,'role_ids' => $role_ids,'equipment_type_ids' => $equipment_type_ids]);
    }

    /*************************
    * Getting the Order Master Coloum Detail
    * Created By : Praveen Singh
    * Created On : 17-June-2019
    ************************/
    public function getOrderMasterColumnDtl(){
        
        global $order,$models,$report;
        
        return response()->json(array('orderMasterColumnList' => $order->getColumnList(false)));
    }
    
    /*************************
    * Save-Update Report Column Settings
    * Created By : Praveen Singh
    * Created On : 17-June-2019
    ************************/
    public function getBranchWiseReportColumns(Request $request){
		
        global $order,$models,$reportSetting;
        
	$error    = '0';
	$message  = '';
	$data     = '';
	$formData = $branchWiseSelectedColumnList = $allSelectedOrderMasterColumnList = array();
        
        try {        
            //Saving record in orders table
            if(!empty($request->formData) && $request->isMethod('post')){
                
                //Parsing the Serialze Dta
                parse_str($request->formData, $formData);
             
                if(!empty($formData['ors_division_id']) && !empty($formData['ors_product_category_id'])){
                    $branchWiseSelectedColumnList = DB::table('order_report_settings')
                                                    ->where('order_report_settings.ors_division_id',$formData['ors_division_id'])
                                                    ->where('order_report_settings.ors_product_category_id',$formData['ors_product_category_id'])
                                                    ->orderBy('order_report_settings.ors_id','ASC')
                                                    ->get()
                                                    ->toArray();
                    $allSelectedOrderMasterColumnList = DB::table('order_report_settings')
                                                    ->where('order_report_settings.ors_division_id',$formData['ors_division_id'])
                                                    ->where('order_report_settings.ors_product_category_id',$formData['ors_product_category_id'])
                                                    ->orderBy('order_report_settings.ors_id','ASC')
                                                    ->pluck('order_report_settings.ors_column_name')
                                                    ->all();
                }  
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.error');
        }
        
        return response()->json(['error' => $error,'message' => $message,'branchWiseSelectedColumnList' => $branchWiseSelectedColumnList,'allSelectedOrderMasterColumnList' => $allSelectedOrderMasterColumnList]);	
    }

    /*************************
    * Save-Update Report Column Settings
    * Created By : Praveen Singh
    * Created On : 17-June-2019
    ************************/
    public function saveUpdateReportColumnSettings(Request $request){
		
        global $order,$models,$reportSetting;
        
	$error    = '0';
	$message  = config('messages.message.error');
	$data     = '';
	$flag  	  = array();
	$formData = array();
        
        try {
            
            //Starting transaction
            DB::beginTransaction();
        
            //Saving record in orders table
            if(!empty($request->formData) && $request->isMethod('post')){
                
                //Parsing the Serialze Dta
                parse_str($request->formData, $formData);
             
                if(empty($formData['ors_division_id']) || empty($formData['ors_product_category_id']) || empty($formData['ors_column_name'])){
                    $message = config('messages.message.required');
                }else{
                    
                    //Removing the previous records
                    DB::table('order_report_settings')->where('order_report_settings.ors_division_id',$formData['ors_division_id'])->where('order_report_settings.ors_product_category_id',$formData['ors_product_category_id'])->delete();
                    
                    //Saving the New records in the table
                    if(!empty($formData['ors_column_name'])){
                        $formData['ors_column_name'] = array_filter($formData['ors_column_name']);
                        foreach($formData['ors_column_name'] as $key => $orsColumnName){
                            $dataSave = array();
                            $dataSave['ors_type_id'] = $formData['ors_type_id'];
                            $dataSave['ors_division_id'] = $formData['ors_division_id'];
                            $dataSave['ors_product_category_id'] = $formData['ors_product_category_id'];
                            $dataSave['ors_column_name'] = $orsColumnName;
                            $dataSave['ors_created_by'] = USERID;
                            $flag[$key] = DB::table('order_report_settings')->insertGetId($dataSave);
                        }
                    }                    
                    if(!empty($flag) && !in_array('0',$flag)){
                        
                        $error   = '1';
                        $message = config('messages.message.saved');
                        $formData = array('division_id' => $formData['ors_division_id'], 'product_category_id' => $formData['ors_product_category_id']);
                        
                        //Committing the queries
                        DB::commit();
                    }
                    
                }  
            }
        }catch(\Illuminate\Database\QueryException $ex){
            DB::rollback();
            $message = config('messages.message.savedError');
        }
        return response()->json(['error' => $error,'message' => $message,'formData' => $formData]);	
    }
}
