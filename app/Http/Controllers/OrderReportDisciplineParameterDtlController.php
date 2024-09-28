<?php
/*****************************
*Created By  : Praveen Singh
*Created On  : 09-Nov-2019
*Modified On : 09-Nov-2019
******************************/

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models;
use App\OrderReportDisciplineParameterDtl;
use Auth;
use Session;
use Validator;
use Route;
use DB;

class OrderReportDisciplineParameterDtlController extends Controller
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
	
        global $models,$orderDisciplineParameterDtl;
	
        $models = new Models();
	$orderDisciplineParameterDtl = new OrderReportDisciplineParameterDtl();

        //MiddleWare CHecking Loin Inn Authentication
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
	
	global $models,$orderDisciplineParameterDtl;
	
	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		
        return view('master.discipline_parameter_category.index',['title' => 'Discipline Parameter Category Master','_discipline_parameter_category' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createMaster(Request $request){
	
        global $models,$orderDisciplineParameterDtl;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = $dataSave = array();
        $tableName = 'order_report_discipline_parameter_dtls';
        
        //Saving record in table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
	    //Parsing the form Data
            parse_str($request->formData, $formData);
            
            if(empty($formData['ordp_division_id'])){
                $message = config('messages.message.required');
            }else if(empty($formData['ordp_product_category_id'])){
                $message = config('messages.message.required'); 
            }else if(empty($formData['ordp_discipline_id'])){
                $message = config('messages.message.required');
            }else if(empty($formData['ordp_test_parameter_category_id'])){
                $message = config('messages.message.checkBoxRequiredError'); 
            } else{
                try{
                    //Creating dat Save Array
                    if(!empty($formData['ordp_test_parameter_category_id'])){
                        foreach($formData['ordp_test_parameter_category_id'] as $key => $value){
                            $dataSave[$key]['ordp_division_id']                = $formData['ordp_division_id'];
                            $dataSave[$key]['ordp_product_category_id']        = $formData['ordp_product_category_id'];
                            $dataSave[$key]['ordp_discipline_id']              = $formData['ordp_discipline_id'];
                            $dataSave[$key]['ordp_test_parameter_category_id'] = $value;
                            $dataSave[$key]['ordp_created_by']                 = USERID;
                        }
                    }
                    if(!empty($dataSave) && DB::table($tableName)->insert($dataSave)){
                        $error   = '1';
                        $message = config('messages.message.saved');
                    }else{
                        $message = config('messages.message.savedError');    
                    }
                }catch(\Illuminate\Database\QueryException $ex){
                    $message = config('messages.message.existError');
                }
            }
        }        
        return response()->json(array('error'=> $error,'message'=> $message,'data' => $data));	
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function listMasters(Request $request){
	
	global $models,$orderDisciplineParameterDtl;
	
	$error    = '0';
	$message  = config('messages.message.error');
	$data     = '';
	$formData = array();
	
	$masterDataList = DB::table('order_report_discipline_parameter_dtls')
                    ->join('divisions','divisions.division_id','order_report_discipline_parameter_dtls.ordp_division_id')
                    ->join('product_categories','product_categories.p_category_id','order_report_discipline_parameter_dtls.ordp_product_category_id')
                    ->join('order_report_disciplines','order_report_disciplines.or_discipline_id','order_report_discipline_parameter_dtls.ordp_discipline_id')
                    ->join('users','users.id','order_report_discipline_parameter_dtls.ordp_created_by')
                    ->select('order_report_discipline_parameter_dtls.*','divisions.division_name as ordp_division_name','product_categories.p_category_name as ordp_product_category_name','order_report_disciplines.or_discipline_name as ordp_discipline_name','users.name as ordp_created_name')
                    ->orderBy('product_categories.p_category_name','DESC')
                    ->groupBy('order_report_discipline_parameter_dtls.ordp_division_id','order_report_discipline_parameter_dtls.ordp_product_category_id','order_report_discipline_parameter_dtls.ordp_discipline_id')
                    ->get()
                    ->toArray();
                    
        if(!empty($masterDataList)){
            foreach($masterDataList as $values){
                $values->ordpTestParameterCategoryList = array_values(DB::table('order_report_discipline_parameter_dtls')
                    ->join('test_parameter_categories','test_parameter_categories.test_para_cat_id','order_report_discipline_parameter_dtls.ordp_test_parameter_category_id')
                    ->where('order_report_discipline_parameter_dtls.ordp_division_id',$values->ordp_division_id)
                    ->where('order_report_discipline_parameter_dtls.ordp_product_category_id',$values->ordp_product_category_id)
                    ->where('order_report_discipline_parameter_dtls.ordp_discipline_id',$values->ordp_discipline_id)
                    ->select('order_report_discipline_parameter_dtls.ordp_test_parameter_category_id as id','test_parameter_categories.test_para_cat_name as name')
                    ->get()
                    ->toArray());
            }
        }
                    
        $error    = !empty($masterDataList) ? '1' :  '0';
	$message  = !empty($error) ? '' :config('messages.message.error');

	//to formate created and updated date		   
	$models->formatTimeStampFromArray($masterDataList,DATETIMEFORMAT);
	
	return response()->json(array('error' => $error,'message' => $message,'masterDataList' => $masterDataList));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function viewMaster(Request $request,$id){
        
        global $models,$orderDisciplineParameterDtl;
	
	$error    = '0';
	$message  = config('messages.message.error');
	$data     = '';
	$formData = array();
        
        if($id){            
            $orderDisciplineParameterData = $orderDisciplineParameterDtl->getRow($id);
            $masterData = DB::table('order_report_discipline_parameter_dtls')
                    ->join('divisions','divisions.division_id','order_report_discipline_parameter_dtls.ordp_division_id')
                    ->join('product_categories','product_categories.p_category_id','order_report_discipline_parameter_dtls.ordp_product_category_id')
                    ->join('order_report_disciplines','order_report_disciplines.or_discipline_id','order_report_discipline_parameter_dtls.ordp_discipline_id')
                    ->join('users','users.id','order_report_discipline_parameter_dtls.ordp_created_by')
                    ->select('order_report_discipline_parameter_dtls.*','divisions.division_name as ordp_division_name','product_categories.p_category_name as ordp_product_category_name','order_report_disciplines.or_discipline_name as ordp_discipline_name','users.name as ordp_created_name')
                    ->where('order_report_discipline_parameter_dtls.ordp_division_id',$orderDisciplineParameterData->ordp_division_id)
                    ->where('order_report_discipline_parameter_dtls.ordp_product_category_id',$orderDisciplineParameterData->ordp_product_category_id)
                    ->where('order_report_discipline_parameter_dtls.ordp_discipline_id',$orderDisciplineParameterData->ordp_discipline_id)
                    ->first();
            if(!empty($masterData)){
                $masterData->ordpTestParameterCategoryList = array_values(DB::table('order_report_discipline_parameter_dtls')
                    ->where('order_report_discipline_parameter_dtls.ordp_division_id',$orderDisciplineParameterData->ordp_division_id)
                    ->where('order_report_discipline_parameter_dtls.ordp_product_category_id',$orderDisciplineParameterData->ordp_product_category_id)
                    ->where('order_report_discipline_parameter_dtls.ordp_discipline_id',$orderDisciplineParameterData->ordp_discipline_id)
                    ->pluck('order_report_discipline_parameter_dtls.ordp_test_parameter_category_id','order_report_discipline_parameter_dtls.ordp_test_parameter_category_id')
                    ->all());
            }
            $error    = !empty($masterData) ? 1 : '0';
            $message  = $error ? '' : $message;
        }
        
        //echo '<pre>';print_r($masterData);die;
	return response()->json(array('error' => $error,'message' => $message,'editMasterData' => $masterData));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function updateMaster(Request $request){
        
        global $models,$orderDisciplineParameterDtl;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();
        $tableName = 'order_report_discipline_parameter_dtls';
        
        //Saving record in table
        if(!empty($request->formData) && $request->isMethod('post')){
            
            //Parsing the form Data
            parse_str($request->formData, $formData);
            
            if(empty($formData['ordp_id'])){
                $message  = config('messages.message.error');
            }else if(empty($formData['ordp_division_id'])){
                $message = config('messages.message.required');
            }else if(empty($formData['ordp_product_category_id'])){
                $message = config('messages.message.required'); 
            }else if(empty($formData['ordp_discipline_id'])){
                $message = config('messages.message.required');
            }else if(empty($formData['ordp_test_parameter_category_id'])){
                $message = config('messages.message.checkBoxRequiredError'); 
            } else{
                try{
                    //Deleting Previous Entry
                    DB::table('order_report_discipline_parameter_dtls')->where('order_report_discipline_parameter_dtls.ordp_division_id',$formData['ordp_division_id'])->where('order_report_discipline_parameter_dtls.ordp_product_category_id',$formData['ordp_product_category_id'])->where('order_report_discipline_parameter_dtls.ordp_discipline_id',$formData['ordp_discipline_id'])->delete();
                    
                    //Creating dat Save Array
                    if(!empty($formData['ordp_test_parameter_category_id'])){
                        foreach($formData['ordp_test_parameter_category_id'] as $key => $value){
                            $dataSave[$key]['ordp_division_id']                = $formData['ordp_division_id'];
                            $dataSave[$key]['ordp_product_category_id']        = $formData['ordp_product_category_id'];
                            $dataSave[$key]['ordp_discipline_id']              = $formData['ordp_discipline_id'];
                            $dataSave[$key]['ordp_test_parameter_category_id'] = $value;
                            $dataSave[$key]['ordp_created_by']                 = USERID;
                        }
                    }
                    if(!empty($dataSave) && DB::table($tableName)->insert($dataSave)){
                        $error   = '1';
                        $message = config('messages.message.updated');
                    }else{
                        $message = config('messages.message.savedNoChange');    
                    }                    
                }catch(\Illuminate\Database\QueryException $ex){
                    $message = config('messages.message.updatedError');
                }
            }
        }        
        return response()->json(array('error' => $error,'message' => $message,'data' => $data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroyMaster($id){
        
        global $models,$orderDisciplineParameterDtl;
        
        $error   = '0';
        $message = '';
        $data    = '';
        $tableName = 'order_report_discipline_parameter_dtls';
        
        try{
            $orderDisciplineParameterData = $orderDisciplineParameterDtl->getRow($id);
            if(!empty($orderDisciplineParameterData->ordp_id) && DB::table('order_report_discipline_parameter_dtls')
                ->where('order_report_discipline_parameter_dtls.ordp_division_id',$orderDisciplineParameterData->ordp_division_id)
                ->where('order_report_discipline_parameter_dtls.ordp_product_category_id',$orderDisciplineParameterData->ordp_product_category_id)
                ->where('order_report_discipline_parameter_dtls.ordp_discipline_id',$orderDisciplineParameterData->ordp_discipline_id)
                ->delete()){
                $error   = '1';
                $message = config('messages.message.deleted');      
            }else{
                $message = config('messages.message.deletedError');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.deletedError');
        }             
	return response()->json(['error' => $error,'message' => $message]);
    }
}
