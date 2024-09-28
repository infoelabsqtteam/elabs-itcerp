<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Division;
use App\Item;
use App\Models;
use App\InvoicingTypeCustomerWiseAssayParameter;
use App\State;
use Validator;
use Route;
use DB;

class InvoicingTypeCustomerWiseAssayParametersController extends Controller
{
    /**
    * protected Variable.
    */
    protected $auth;
    
    /**
    * Create a new controller instance.
    * Praveen Singh
    * @return void
    */
    public function __construct(){
	
	global $models,$invoicingTypeCustomerWiseAssayParameter,$state;
	$models = new Models();
	$state  = new State();
	$invoicingTypeCustomerWiseAssayParameter = new InvoicingTypeCustomerWiseAssayParameter();
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
    * Praveen Singh
    * @return \Illuminate\Http\Response
    */
    public function index(){
	
	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	
	return view('master.customer_invoicing.customer_wise_assay_parameter_rates.index',['title' => 'Customer Wise Product Assay Rates','_customer_wise_product_assay_rates' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    
     /**
    * Store a newly created resource in storage.
    * Praveen Singh
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function createCustomerWiseAssayParametersRate(Request $request){
	
        global $models,$invoicingTypeCustomerWiseAssayParameter;
        
        $error    	= '0';
        $message  	= config('messages.message.error');
        $data     	= '';
	$invocingTypeId = '4';
        $formData 	= array();	
        
        //Saving record in table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
	    //parsing form data 
            parse_str($request->formData, $formData);
	    
            if(!empty($formData)){		
		
		if(empty($formData['cir_customer_id'])){
		    $message = config('messages.message.circustomerNameRequired');
		}else if(empty($formData['cir_division_id'])){
		    $message = config('messages.message.divisionNameRequired');
		}else if(empty($formData['cir_product_category_id'])){
		    $message = config('messages.message.cirProductCategoryRequired');
		}else if(empty($formData['cir_p_category_id'])){
		    $message = config('messages.message.cirPCategoryIdRequired');
		}else if(empty($formData['cir_sub_p_category_id'])){
		    $message = config('messages.message.cirSubCategoryIdRequired');
		}else if(empty($formData['cir_test_parameter_category_id'])){
		    $message = config('messages.message.cirTestParameterCategoryRequired');
		}else if(empty($formData['cir_equipment_type_id'])){
		    $message = config('messages.message.ciEquipmentTypeRequired');
		}else if(empty($formData['cir_equipment_count'])){
		    $message = config('messages.message.cirEquipmentCountRequired');
		}else if(!empty($formData['cir_is_detector']) && $formData['cir_is_detector']== '1' && empty($formData['cir_detector_id'])){
		    $message = config('messages.message.cirDetectorNameRequired');
		}else if(!empty($formData['cir_is_detector']) && $formData['cir_is_detector']== '1' && empty($formData['cir_running_time_id'])){
		    $message = config('messages.message.cirRunningTimeRequired');
		}else if(!empty($formData['cir_is_detector']) && $formData['cir_is_detector']== '1' && empty($formData['cir_no_of_injection'])){
		    $message = config('messages.message.cirNoOfInjectionRequired');
		}else if(empty($formData['invoicing_rate'])){
		    $message = config('messages.message.invoicingRateRequired');
		}else if(!empty($invoicingTypeCustomerWiseAssayParameter->checkUniqueStructure($formData,$invocingTypeId))){
		    $message = config('messages.message.exist');
		}else{
		    $formData = $models->unsetFormDataVariables($formData,array('_token'));  //Unsetting the variable from request data
		    $formData['invoicing_type_id'] = $invocingTypeId;
		    $formData['cir_parameter_id']  = !empty($formData['cir_parameter_id']) ? $formData['cir_parameter_id'] : NULL ;
		    $formData['created_by']	   = USERID;
		    //echo'<pre>';print_r($formData);die;
		    
		    if(!empty($formData['invoicing_type_id'])){
			$cirId = DB::table('customer_invoicing_rates')->insertGetId($formData);
			if(!empty($cirId)){
			    $error   = '1';
			    $message = config('messages.message.saved');			                       
			}else{
			    $message = config('messages.message.savedError');
			}
		    }
                }                
            }
        }
        return response()->json(array('error'=> $error,'message'=> $message));	
    }
    
    /**
    * Display the specified resource.
    * Praveen Singh
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function getCustomerWiseAssayParametersRates(Request $request){
	
	global $models,$invoicingTypeCustomerWiseAssayParameter;
	
	$error    	 = '0';
	$message  	 = config('messages.message.error');
	$data     	 = '';
	$invocingTypeId  = '4';
	$cir_customer_id = '0';
	$formData 	 = array();
	
	//Saving record in table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
	    //parsing form data 
            parse_str($request->formData, $formData);
	    //echo '<pre>';print_r($formData);die;
	    
            if(!empty($formData)){
	    
		$cir_customer_id       = !empty($formData['cir_customer_id']) ? $formData['cir_customer_id'] : '0';
		$cirProductCategoryId  = !empty($formData['cir_product_category_id']) ? $formData['cir_product_category_id'] : '1';
		
		$leftSideDataListObj  = DB::table('customer_invoicing_rates')
					->select('customer_master.customer_id','customer_master.customer_name')
					->join('customer_master','customer_master.customer_id','customer_invoicing_rates.cir_customer_id')
					->whereNotNull('customer_invoicing_rates.cir_is_detector')
					->where('customer_invoicing_rates.invoicing_type_id',$invocingTypeId);
					    
		if(!empty($cirProductCategoryId)){
		    $leftSideDataListObj ->where('customer_invoicing_rates.cir_product_category_id',$cirProductCategoryId);
		}
		
		$leftSideDataList = $leftSideDataListObj->groupBy('customer_master.customer_name')->orderBy('customer_master.customer_name','ASC')->get();
		
		$rightSideDataObj = DB::table('customer_invoicing_rates')		
		    ->join('product_categories as department', 'department.p_category_id', '=', 'customer_invoicing_rates.cir_product_category_id')
		    ->join('product_categories as productCategory', 'productCategory.p_category_id', '=', 'customer_invoicing_rates.cir_p_category_id')
		    ->join('product_categories as subProductCategory', 'subProductCategory.p_category_id', '=', 'customer_invoicing_rates.cir_sub_p_category_id')
		    ->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', '=', 'customer_invoicing_rates.cir_test_parameter_category_id')
		    ->leftJoin('test_parameter','test_parameter.test_parameter_id', '=', 'customer_invoicing_rates.cir_parameter_id')
		    ->join('equipment_type','equipment_type.equipment_id','customer_invoicing_rates.cir_equipment_type_id')
		    ->leftJoin('detector_master','detector_master.detector_id','customer_invoicing_rates.cir_detector_id')
		    ->leftJoin('customer_invoicing_running_time','customer_invoicing_running_time.invoicing_running_time_id','customer_invoicing_rates.cir_running_time_id')
		    ->select('customer_invoicing_rates.*','department.p_category_name as department_name','productCategory.p_category_name as category_name','subProductCategory.p_category_name as sub_category_name','test_parameter_categories.test_para_cat_name','test_parameter.test_parameter_name','equipment_type.equipment_name','detector_master.detector_name','customer_invoicing_running_time.invoicing_running_time_key')
		    ->join('users as createdBy','createdBy.id','customer_invoicing_rates.created_by')	
		    ->whereNotNull('customer_invoicing_rates.cir_is_detector')
		    ->where('customer_invoicing_rates.invoicing_type_id',$invocingTypeId);
		
		if(!empty($cir_customer_id) && is_numeric($cir_customer_id)){
		    $rightSideDataObj->where('customer_invoicing_rates.cir_customer_id',$cir_customer_id);
		}
		if(!empty($cirProductCategoryId)){
		    $rightSideDataObj ->where('customer_invoicing_rates.cir_product_category_id',$cirProductCategoryId);
		}
		$rightSideDataList = $rightSideDataObj->orderBy('customer_invoicing_rates.cir_id','ASC')->get();
	    }
	}
	
	$returnData = array('leftSideDataList' => $leftSideDataList,'rightSideDataList' => $rightSideDataList);	
	$error      = !empty($returnData['leftSideDataList']) ? '1' : '0';
	$message    = $error ? '' : $message;
	
	//to formate created and updated date		   
	!empty($returnData['rightSideDataList']) ? $models->formatTimeStampFromArray($returnData['rightSideDataList'],DATETIMEFORMAT) : '';
	
	//echo '<pre>'.$cirProductCategoryId.$cir_customer_id;print_r($returnData);die;
	return response()->json(array('error'=> $error,'message'=> $message,'returnData'=> $returnData, 'cir_customer_id' => $cir_customer_id));
    }
    
    /**
    * Display single customer products rate .
    * Praveen Singh
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function editCustomerWiseAssayParameterRates(Request $request){

	global $models,$invoicingTypeCustomerWiseAssayParameter;
	
	$error    	 = '0';
	$message  	 = config('messages.message.error');
	$data     	 = '';
	$invocingTypeId  = '4';
	$cir_customer_id = '0';
	$formData 	 = array();
	
	//Saving record in table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
	    //parsing form data 
            parse_str($request->formData, $formData);
	    
            if(!empty($formData['cir_customer_id']) && !empty($formData['cir_department_id'])){
		$cir_customer_id           		= !empty($formData['cir_customer_id']) ? $formData['cir_customer_id'] : '0';
		$cir_product_category_id   		= !empty($formData['cir_department_id']) ? $formData['cir_department_id'] : '0';
		$customersList		   		= DB::table('customer_master')->select('customer_id as id','customer_name as name')->where('customer_id',$cir_customer_id)->first();
		$customerWiseAssayParameterRateList 	= $invoicingTypeCustomerWiseAssayParameter->getSelectedCustomerWiseAssayParametersRates($cir_customer_id,$cir_product_category_id,$invocingTypeId);
	    }
	}
	
	$returnData = !empty($customerWiseAssayParameterRateList) ? $customerWiseAssayParameterRateList : array();
	$error      = !empty($customerWiseAssayParameterRateList) ? 1 : '0';
	$message    = $error ? '' : $message;
	
	//echo'<pre>';print_r($returnData); die;
	return response()->json(array('error'=> $error,'customersList' => $customersList, 'returnData'=> $returnData));
    }
    
    /**
     * Show the form for editing the specified resource.
     * Praveen Singh
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCustomerWiseAssayParametersRates(Request $request){
        
	global $models,$invoicingTypeCustomerWiseParameter;
        
	$error    = '0';
	$message  = config('messages.message.error');
	$data     = $updated = '';
	$formData = array();	
        
	if ($request->isMethod('post') && !empty($request['formData'])){
	    
	    //parsing searlize data 				
	    parse_str($request['formData'], $formData);
	    
	    if(empty(array_filter($formData['invoicing_rate']))){
		$message = config('messages.message.invoicingRateRequired');
	    }else{		
		if(!empty($formData['invoicing_rate'])){
		    foreach($formData['invoicing_rate'] as $cirIdKey => $invoicingRate){                    
			$updated = DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_id',str_replace("'", "", $cirIdKey))->update(['customer_invoicing_rates.invoicing_rate' => $invoicingRate]);
			$error   = '1';
		    } 
		}
	    }
	}
	
	if($error){
	    $message = config('messages.message.saved');  
	}else{
	    $message = config('messages.message.error'); 
	}
	
	//echo'<pre>';print_r($formData); die;
	return response()->json(['error'=> $error,'message'=> $message]);
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteCustomerWiseAssayParameterRate(Request $request, $cir_id){
        
	$error   = '0';
        $message = '';
        $data    = '';
        
        try{
            if(DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_id','=',$cir_id)->delete()){
                $error   = '1';
                $message = config('messages.message.deleted');      
            }else{
                $message = config('messages.message.deletedError');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.deletedErrorFKC');
        }             
	return response()->json(['error' => $error,'message' => $message]);
    }
    
    
    
}
