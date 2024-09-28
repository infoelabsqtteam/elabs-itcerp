<?php

namespace App\Http\Controllers;
use App\Models;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\ProductTestParameterAlternativeMethod;
use Validator;
use Route;
use DB;

class ProductTestParaAlternativeMethodController  extends Controller
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
		$this->middleware('auth'); 
		$this->middleware(function ($request, $next) {
            $this->session = Auth::user();
			parent::__construct($this->session);
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
	
	/************************************
	* Description : create new product-test-parameters of test parameter category
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request
	* @return     : \Illuminate\Http\Response
	************************************/
    public function createTestAlternativeMethod(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);   
					$newPostData['alt_test_id']=base64_decode($newPostData['alt_test_id']);
 					$newPostData['alt_dtl_id']=base64_decode($newPostData['alt_dtl_id']);
 					$newPostData['alt_test_parameter_id']=base64_decode($newPostData['alt_test_parameter_id']);
					if(!empty($newPostData['alt_cost_price']) && $newPostData['alt_cost_price'] >= $newPostData['alt_selling_price']){
						$returnData = array('error' => config('messages.message.costGreaterSellingRequired'));
					}else if(empty($newPostData['alt_method_id'])){						
					$returnData = array('error' => config('messages.message.methodNameRequired'));
					}else if(empty($newPostData['alt_equipment_type_id'])){
					$returnData = array('error' => config('messages.message.equipmentNameRequired'));
					}else if(!empty($newPostData['product_category_id']) && $newPostData['product_category_id'] =='2' && empty($newPostData['alt_detector_id'])){
						$returnData = array('error' => config('messages.message.detectorRequired'));
					}else{
						unset($newPostData['product_category_id']);
						if($this->isExist($newPostData) == 0){
							$created = ProductTestParameterAlternativeMethod::create([
										'created_by' => \Auth::user()->id,
										'product_test_dtl_id' => $newPostData['alt_dtl_id'],
										'test_id' => $newPostData['alt_test_id'],
										'test_parameter_id' => $newPostData['alt_test_parameter_id'],
										'standard_value_type' => $newPostData['alt_standard_value_type'],
										'standard_value_from' => $newPostData['alt_standard_value_from'],
										'standard_value_to' => $newPostData['alt_standard_value_to'],
										'equipment_type_id' => $newPostData['alt_equipment_type_id'],
										'detector_id'	=> !empty($newPostData['alt_detector_id']) ? $newPostData['alt_detector_id'] : NULL,
										'method_id' => $newPostData['alt_method_id'],
										'claim_dependent' => $newPostData['alt_claim_dependent'],								
										'time_taken_mins' => $newPostData['alt_time_taken_mins'],								
										'time_taken_days' => $newPostData['alt_time_taken_days'],
										'cost_price' => $newPostData['alt_cost_price'],
										'selling_price' => $newPostData['alt_selling_price']
										]);
							if($created->id){ 
								$returnData = array('success' => config('messages.message.parameterAlternativeSaved'),'test_parameter_id'=>$newPostData['alt_test_parameter_id'],'test_id'=>$newPostData['alt_test_id'],'product_test_dtl_id'=>$newPostData['alt_dtl_id']);
							}else{
								$returnData = array('error' => config('messages.message.parameterAlternativeNotSaved'));
							}
						}else{
							$returnData = array('error' => config('messages.message.alternativeAlreadyExist'));
						}
					}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
		} 
		return response()->json($returnData);		
    }

	/************************************
	* Description : isExist Is used to check the test_parameter duplicate entry by code
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request
	* @return     : \Illuminate\Http\Response
	************************************/
    public function isExist($newPostData) 
    { 
		if(!empty($newPostData)){	
			$data = DB::table('product_test_parameter_altern_method')
						->where('product_test_dtl_id', '=',$newPostData['alt_dtl_id'])
						->where('test_id', '=', $newPostData['alt_test_id'])
						->where('test_parameter_id', '=', $newPostData['alt_test_parameter_id'])
						->where('standard_value_type', '=', $newPostData['alt_standard_value_type'])
						->where('standard_value_from', '=', $newPostData['alt_standard_value_from'])
						->where('standard_value_to', '=', $newPostData['alt_standard_value_to'])
						->where('equipment_type_id', '=', $newPostData['alt_equipment_type_id'])
						->where('method_id', '=', $newPostData['alt_method_id'])
						->where('time_taken_mins', '=', $newPostData['alt_time_taken_mins'])
						->where('time_taken_days', '=', $newPostData['alt_time_taken_days'])
						->where('claim_dependent', '=', $newPostData['alt_claim_dependent'])
						->where('cost_price', '=', $newPostData['alt_cost_price'])
						->first(); 
			if(@$data->product_test_param_altern_method_id){
				return $data->product_test_param_altern_method_id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
    
	/************************************
	* Description : Get list of alternative methods on page load
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request
	* @return     : \Illuminate\Http\Response
	************************************/
    public function getTestAlternativeMethodList(Request $request)
    {   
     	if ($request->isMethod('post')) {  
 			if(!empty($request['data']['id'])){ 
				global $models;
				$allAltMethodList = DB::table('product_test_parameter_altern_method')
					->leftjoin('detector_master','detector_master.detector_id','product_test_parameter_altern_method.detector_id')
					->leftjoin('equipment_type','product_test_parameter_altern_method.equipment_type_id','equipment_type.equipment_id')
					->leftjoin('method_master','product_test_parameter_altern_method.method_id','method_master.method_id')
					->join('users','product_test_parameter_altern_method.created_by','users.id')
					->where('product_test_parameter_altern_method.test_parameter_id',$request['data']['id'])
					->where('product_test_parameter_altern_method.test_id',$request['data']['test_id'])
					->where('product_test_parameter_altern_method.product_test_dtl_id',$request['data']['product_test_dtl_id'])
					->select('detector_master.detector_name','detector_master.detector_id','product_test_parameter_altern_method.*','equipment_type.*','method_master.*','users.name as createdBy','users.name as createdBy')
					->orderBy('product_test_parameter_altern_method.product_test_param_altern_method_id','desc')
					->get();  
				$models->formatTimeStampFromArray($allAltMethodList,DATETIMEFORMAT);
				$returnData	=array('allAltMethodList'=>$allAltMethodList); 		
			}else{
				$returnData = array('error' =>  config('messages.message.dataNotFound'));
			} 
		}else{
			$returnData = array('error' => config('messages.message.dataNotFound'));
		} 
		return response()->json($returnData);
	}
	
	/************************************
	* Description : Get list of alternative methods using multisearch filter
	* Date        : 29-04-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request
	* @return     : \Illuminate\Http\Response
	************************************/
    public function getTestAlternativeMethodListMultisearch(Request $request)
    {   
     		global $models;
			$searchArry=$request['data']['formData'];			
			$allAltMethodObj = DB::table('product_test_parameter_altern_method')
				->leftjoin('detector_master','detector_master.detector_id','product_test_parameter_altern_method.detector_id')
				->join('equipment_type','product_test_parameter_altern_method.equipment_type_id','equipment_type.equipment_id')
				->join('method_master','product_test_parameter_altern_method.method_id','method_master.method_id')
				->join('users','product_test_parameter_altern_method.created_by','users.id')				
				->select('detector_master.detector_name','product_test_parameter_altern_method.*','equipment_type.*','method_master.*','users.name as createdBy','users.name as createdBy');
				if(!empty($searchArry['search_equipment_name'])){
					$allAltMethodObj->where('equipment_type.equipment_name','like','%'.$searchArry['search_equipment_name'].'%');
				}
				if(!empty($searchArry['search_detector_name'])){
					$allAltMethodObj->where('detector_master.detector_name','like','%'.$searchArry['search_detector_name'].'%');
				}
				if(!empty($searchArry['search_method_name'])){
					$allAltMethodObj->where('method_master.method_name','like','%'.$searchArry['search_method_name'].'%');
				}
				if(!empty($searchArry['search_time_taken_days'])){
					$allAltMethodObj->where('product_test_parameter_altern_method.time_taken_days','like','%'.$searchArry['search_time_taken_days'].'%');
				}
				if(!empty($searchArry['search_time_taken_mins'])){
					$allAltMethodObj->where('product_test_parameter_altern_method.time_taken_mins','like','%'.$searchArry['search_time_taken_mins'].'%');
				}
				if(!empty($searchArry['search_standard_value_from'])){
					$allAltMethodObj->where('product_test_parameter_altern_method.standard_value_from','like','%'.$searchArry['search_standard_value_from'].'%');
				}
				if(!empty($searchArry['search_standard_value_to'])){
					$allAltMethodObj->where('product_test_parameter_altern_method.standard_value_to','like','%'.$searchArry['search_standard_value_to'].'%');
				}
				if(!empty($searchArry['search_cost_price'])){
					$allAltMethodObj->where('product_test_parameter_altern_method.cost_price','like','%'.$searchArry['search_cost_price'].'%');
				}
				if(!empty($searchArry['search_selling_price'])){
					$allAltMethodObj->where('product_test_parameter_altern_method.selling_price','like','%'.$searchArry['search_selling_price'].'%');
				}
				if(!empty($searchArry['search_created_by'])){
					$allAltMethodObj->where('users.name','like','%'.$searchArry['search_created_by'].'%');
				}
				if(!empty($searchArry['search_created_at'])){
					$allAltMethodObj->where('product_test_parameter_altern_method.created_at','like','%'.$searchArry['search_created_at'].'%');
				}
				$allAltMethodObj->where('product_test_parameter_altern_method.test_parameter_id',base64_decode($searchArry['search_test_parameter_id']))
				->where('product_test_parameter_altern_method.test_id',base64_decode($searchArry['search_test_id']))
				->where('product_test_parameter_altern_method.product_test_dtl_id',base64_decode($searchArry['search_product_test_dtl_id']));
			$allAltMethodList=$allAltMethodObj->get();  
			$models->formatTimeStampFromArray($allAltMethodList,DATETIMEFORMAT);
			$returnData	=array('allAltMethodList'=>$allAltMethodList); 		
		return response()->json($returnData);

	}
	/************************************
	* Description : Get list of test_parameters on page load
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request
	* @return     : \Illuminate\Http\Response
	************************************/
    public function getTestParameterDetails(Request $request)
    {   
		$testDetail=array();
		if ($request->isMethod('post')) { 
			if(!empty($request['data']['id'])){ 
				$proTestParameter = DB::table('product_test_dtl')
					->join('test_parameter','product_test_dtl.test_parameter_id','test_parameter.test_parameter_id')
					->select('product_test_dtl.*','test_parameter.test_parameter_name')
					->where('product_test_dtl.test_parameter_id',$request['data']['id'])
					->Where('product_test_dtl.product_test_dtl_id',$request['data']['product_test_dtl_id'])
					->Where('product_test_dtl.test_id',$request['data']['test_id'])
					->first();     			
				 $testDetails = DB::table('product_test_hdr')
					->join('test_standard','product_test_hdr.test_standard_id','test_standard.test_std_id')
					->join('product_master','product_master.product_id','product_test_hdr.product_id')
					->select('product_test_hdr.*','test_standard.test_std_name','product_master.product_name')
					->where('product_test_hdr.test_id',$request['data']['test_id'])
					->first();  					
				$returnData	=array('proTestParameter'=>$proTestParameter,'testDetails'=>$testDetails); 		
			}else{
				$returnData = array('error' =>  config('messages.message.dataNotFound'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFound'));
		} 
		return response()->json($returnData);
	}
		
	/************************************
	* Description : Show the form for editing the Test Parameters alt method and get previous saved data
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	* @return     : \Illuminate\Http\Response
	************************************/
    public function editTestAlternativeMethod(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				$resultData = DB::table('product_test_parameter_altern_method')
							->leftjoin('detector_master','detector_master.detector_id','product_test_parameter_altern_method.detector_id')
							->where('product_test_param_altern_method_id', '=', $request['data']['id'])
							->first();
				if($resultData->product_test_param_altern_method_id){
					$returnData = array('responseData' => $resultData);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		}
		return response()->json(['returnData'=>$returnData]);		
    }

	/************************************
	* Description : Update the specified resource in storage using test_parameter_id
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	* @return     : \Illuminate\Http\Response
	************************************/
    public function updateTestAlternativeMethod(Request $request)
    {
        $returnData = array();
		//$updated = '';
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){     
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);
					if(empty($newPostData['product_test_param_altern_method_id'])){
					    $returnData = array('error' => config('messages.message.parameterNotFound'));
					}else if(empty($newPostData['alt_method_id1'])){						
					$returnData = array('error' => config('messages.message.methodNameRequired'));
					}else if(empty($newPostData['alt_equipment_type_id1'])){
					$returnData = array('error' => config('messages.message.equipmentNameRequired'));
					}else if(!empty($newPostData['alt_cost_price1']) && $newPostData['alt_cost_price1'] >= $newPostData['alt_selling_price1']){
						$returnData = array('error' => config('messages.message.costGreaterSellingRequired'));
					}else if(!empty($newPostData['product_category_id']) && $newPostData['product_category_id'] =='2' && empty($newPostData['alt_detector_id1'])){
						$returnData = array('error' => config('messages.message.detectorRequired'));
					}else{
						unset($newPostData['product_category_id']);
						$newPostData['altern_method_id']=base64_decode($newPostData['product_test_param_altern_method_id']);
						$updated = DB::table('product_test_parameter_altern_method')
							->where('product_test_param_altern_method_id',$newPostData['altern_method_id'])
							->update([
									'standard_value_type' => $newPostData['alt_standard_value_type1'],
									'standard_value_from' => $newPostData['alt_standard_value_from1'],
									'standard_value_to' => $newPostData['alt_standard_value_to1'],
									'method_id' => !empty($newPostData['alt_method_id1']) ? $newPostData['alt_method_id1'] : NULL ,
									'equipment_type_id' => !empty($newPostData['alt_equipment_type_id1']) ? $newPostData['alt_equipment_type_id1'] : NULL,
									'detector_id' => !empty($newPostData['alt_detector_id1']) ? $newPostData['alt_detector_id1'] : NULL,
									'claim_dependent' => $newPostData['alt_claim_dependent1'],								
									'time_taken_mins' => $newPostData['alt_time_taken_mins1'],								
									'time_taken_days' => $newPostData['alt_time_taken_days1'],
									'cost_price' => $newPostData['alt_cost_price1'],
									'selling_price' => $newPostData['alt_selling_price1'],
								]);
						if($updated){
							$returnData = array('success' => config('messages.message.altMethodUpdated'),'test_id' => $newPostData['alt_test_id1'],'product_test_dtl_id' => $newPostData['alt_product_test_dtl_id1'],'test_parameter_id' => $newPostData['alt_test_parameter_id1']); 
						}else{
							$returnData = array('success' => config('messages.message.savedNoChange'),'test_id' => $newPostData['alt_test_id1'],'product_test_dtl_id' => $newPostData['alt_product_test_dtl_id1'],'test_parameter_id' => $newPostData['alt_test_parameter_id1']); 
						}
					}  
			}else{
				$returnData = array('error' =>  config('messages.message.dataNotFound'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFound'));
		} 
		return response()->json($returnData);
    }

	/************************************
	* Description : delete product test_parameters_alternative_method using product_test_param_altern_method_id
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	* @return     : \Illuminate\Http\Response
	************************************/
    public function deleteTestAlternativeMethod(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$test_parameter_category = DB::table('product_test_parameter_altern_method')->where('product_test_param_altern_method_id', $request['data']['id'])->delete();
					if($test_parameter_category){
						$returnData = array('success' => config('messages.message.productTestAltMethodDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.productTestAltMethodNotDeleted'));					
					}
				}catch(\Illuminate\Database\QueryException $ex){ 
				   $returnData = array('error' => config('messages.message.foreignKeyDeleteError'));
				}
			}else{
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}
		}
		return response()->json($returnData);
    }
}
