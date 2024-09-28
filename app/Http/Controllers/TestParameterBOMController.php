<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\TestParameterBOM;
use Validator;
use Route;
use DB;

class TestParameterBOMController extends Controller
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
    public function index()
    {	
		$user_id            = defined('USERID') ? USERID : '0';
        $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
        return view('standard_wise_product_test.test_parametersBOM',['user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
	
	/************************************
	* Description : create new product-test-parameters of test parameter category
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request
	* @return     : \Illuminate\Http\Response
	************************************/
    public function createTestParametersBOM(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);    
				if(empty($newPostData['test_id']))
				{
					$returnData = array('error' => config('messages.message.productTestCodeRequired'));
				}else if(empty($newPostData['product_test_dtl_id'])){
					$returnData = array('error' => config('messages.message.parameterCodeRequired'));
				}else if(empty($newPostData['item_id'])){
					$returnData = array('error' => config('messages.message.itemCodeRequired'));
				}else if(empty($newPostData['consumed_qty'])){
					$returnData = array('error' => config('messages.message.consumptionRequired'));
				}else{    
				    // check if test_parameter already exist or not 
					//if($this->isExist($newPostData['product_test_dtl_id']) == 0){
						$created = TestParameterBOM::create([
							'test_id' => $newPostData['test_id'],
							'product_test_dtl_id' => $newPostData['product_test_dtl_id'],
							'item_id' => $newPostData['item_id'],
							'consumed_qty' => $newPostData['consumed_qty'],
						  ]);
						if($created->id){ 
							$returnData = array('success' => config('messages.message.testParameterBomSaved'));
						}else{
							$returnData = array('error' => config('messages.message.testParameterBomNotSaved'));
						}
					/* }else{
						$returnData = array('error' => config('messages.message.parameterExist'));
					} */
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
    public function isExist($product_test_dtl_id) 
    { 
		if(!empty($product_test_dtl_id)){
			$data = DB::table('product_test_dtl')
						->where('product_test_dtl.product_test_dtl_id', '=', $product_test_dtl_id)
						->first(); 
			if(@$data->product_test_dtl_id){
				return $data->product_test_dtl_id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
    
	/************************************
	* Description : Get list of test_parameters bom on page load
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request
	* @return     : \Illuminate\Http\Response
	************************************/
    public function getTestParametersBOMList()
    {
		 $testParametersBOM = DB::table('product_test_parameter_bom')
				->join('product_test_hdr','product_test_parameter_bom.test_id','product_test_hdr.test_id')
				->join('product_test_dtl','product_test_parameter_bom.product_test_dtl_id','product_test_dtl.product_test_dtl_id')
				->join('item_master','product_test_parameter_bom.item_id','item_master.item_id')
				->select('product_test_parameter_bom.*','product_test_hdr.test_code','product_test_dtl.product_test_dtl_id','item_master.item_code')
				->get();	
			return response()->json([
			   'allList' => $testParametersBOM,
			]);
    }   	
		
	/************************************
	* Description : Show the form for editing the Test Parameters and get previous saved data
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	* @return     : \Illuminate\Http\Response
	************************************/
    public function editTestParametersBOM(Request $request)
    {
		$returnData = array();
		$productTestCodeList= DB::table('product_test_hdr')->select('test_id as id','test_code as name')->get(); 
		$testParameterList= $parameterCodeList= DB::table('product_test_dtl')->select('product_test_dtl_id as id','product_test_dtl_id as name')->get();
		$itemList= DB::table('item_master')->select('item_id as id','item_code as name')->get(); 	
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				$resultData = DB::table('product_test_parameter_bom')
									->where('product_test_parameter_bom.test_BOM_id', '=', $request['data']['id'])
									->first();
				if($resultData->product_test_dtl_id){
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
		return response()->json(['returnData'=>$returnData,'productTestCodeList'=>$productTestCodeList,'testParameterList'=>$testParameterList,'itemList'=>$itemList]);		
    }

	/************************************
	* Description : Update the specified resource in storage using test_item_id
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	* @return     : \Illuminate\Http\Response
	************************************/
    public function updateTestParametersBOM(Request $request)
    {
        $returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){     
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData); 
				if(empty($newPostData['test_BOM_id1']))
				{
					$returnData = array('error' => config('messages.message.testParameterIdRequired'));
				}if(empty($newPostData['test_id1']))
				{
					$returnData = array('error' => config('messages.message.productTestCodeRequired'));
				}else if(empty($newPostData['product_test_dtl_id1'])){
					$returnData = array('error' => config('messages.message.parameterCodeRequired'));
				}else if(empty($newPostData['item_id1'])){
					$returnData = array('error' => config('messages.message.itemCodeRequired'));
				}else if(empty($newPostData['consumed_qty1'])){
					$returnData = array('error' => config('messages.message.consumptionRequired'));
				}else{
 					$newPostData['test_BOM_id1']=base64_decode($newPostData['test_BOM_id1']);
					$updated = DB::table('product_test_parameter_bom')->where('test_BOM_id',$newPostData['test_BOM_id1'])->update([
						'test_id' => $newPostData['test_id1'],
						'product_test_dtl_id' => $newPostData['product_test_dtl_id1'],
						'consumed_qty' => $newPostData['consumed_qty1']
					   ]);
					//check if data updated in test_parameter table 
					if($updated){ 
                       $returnData = array('success' => config('messages.message.parameterBOMUpdated'));
					 }else{  
						$returnData = array('error' => config('messages.message.parameterBOMNotUpdated'));
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
	* Description : delete product test_parameters using test_item_id
	* Date        : 01-09-17
	* Author      : nisha
	* Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	* @return     : \Illuminate\Http\Response
	************************************/
    public function deleteTestParametersBOM(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$test_parameter_category = DB::table('product_test_parameter_bom')->where('test_BOM_id', $request['data']['id'])->delete();
					if($test_parameter_category){
						$returnData = array('success' => config('messages.message.productTestParametersBOMDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.productTestParametersBOMNotDeleted'));					
					}
				}catch(\Illuminate\Database\QueryException $ex){ 
				   $returnData = array('error' => "Cannot delete or update a parent row: a foreign key constraint fails!");
				}
			}else{
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}
		}
		return response()->json($returnData);
    }
}
