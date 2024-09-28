<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\State;
use Validator;
use Route;
use DB;

class StateController extends Controller
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
    public function index()
    {
	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	
	return view('master.states.index',['title' => 'States','_states' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

    /** create new state
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createState(Request $request){
	
	$returnData = array();
	
	if ($request->isMethod('post')) {
	    if(!empty($request['data']['formData'])){
		
		//pasrse searlize data 
		$newPostData = array(); 
		parse_str($request['data']['formData'], $newPostData);   //print_r($newPostData);   die;
		unset($newPostData['_token']);
		
		if(empty($newPostData['state_code'])){
		    $returnData = array('error' => config('messages.message.stateCodeRequired'));
		}else if(empty($newPostData['state_name'])){
		    $returnData = array('error' => config('messages.message.stateNameRequired'));
		}else if(empty($newPostData['country_id'])){
		    $returnData = array('error' => config('messages.message.stateCountryRequired'));
		}else if(!isset($newPostData['state_status'])){
		    $returnData = array('error' => config('messages.message.stateStatusRequired'));
		}else{
		    try{
				// check if state already exist or not 
				if($this->isStateExist($newPostData['state_code'],$newPostData['state_name']) == 0){ 
					 $created = State::create([
					'state_code' => $newPostData['state_code'],
					'country_id' => $newPostData['country_id'],
					'state_name' => $newPostData['state_name'],
					'state_status' => $newPostData['state_status'],
					'created_by' => \Auth::user()->id,
					 ]);						
					 //check if users created add data in user detail
					 if($created->id){ 
					$returnData = array('success' => config('messages.message.stateSaved'));
					 }else{
					$returnData = array('error' => config('messages.message.stateNotSaved'));
					 }
				}else{
					 $returnData = array('error' => config('messages.message.stateCodeExist'));
				}
		    }catch(\Illuminate\Database\QueryException $ex){
				$returnData = array('error' => config('messages.message.stateCodeExist'));
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

    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getStatesList(Request $request){
	
	global $models;
	
	$error	  = '0';
	$message  = '';
	$data	  = '';
	$formData = array();
	
	parse_str($request->formData, $formData);
	//echo '<pre>';print_r($formData);die;
	
	$countryId = !empty($formData['country_id']) && is_numeric($formData['country_id']) ? $formData['country_id'] : '1';
	
	$stateObj = DB::table('state_db')
		->join('countries_db', 'countries_db.country_id', '=', 'state_db.country_id')
		->join('users', 'state_db.created_by', '=', 'users.id')
		->select('state_db.*','countries_db.country_name','users.name as createdBy')
		->where('countries_db.country_status','=','1');
		
	//Filtering records according to country
	if(!empty($countryId)){
	    $stateObj->where('state_db.country_id',$countryId);
	}
	$states = $stateObj->orderBy('countries_db.country_name','ASC')->orderBy('state_db.state_name','DESC')->get();		
	$models->formatTimeStampFromArray($states,DATETIMEFORMAT);
	
	return response()->json(['statesList' => $states,]);
    }   


    /**
    * isStateExist Is used to check the state duplicate entry by state_code
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function isStateExist($state_code,$state_name){ 
	if(!empty($state_code)){
	    $stateData = DB::table('state_db')
			->Join('users', 'state_db.created_by', '=', 'users.id')
			->where('state_db.state_code', '=', $state_code)
			->orwhere('state_db.state_name', '=', $state_name)
			->first(); 
	    if(!empty($stateData->state_id)){
		return $stateData->state_id;
	    }else{
		return false;
	    }
	}else{
	    return false;
	}
    }	
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function editStateData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				$stateData = DB::table('state_db')
								->leftJoin('countries_db', 'countries_db.country_id', '=', 'state_db.country_id')
								->select('state_db.*','countries_db.country_id')
								->where('state_db.state_id', '=', $request['data']['id'])
								->first();
				
				if($stateData->state_id){
					$returnData = array('responseData' => $stateData);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		}
		return response()->json($returnData);	
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function updateStateData(Request $request){
	
	$returnData = array();
	 if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){   
		  
		  //pasrse searlize data 
		  $newPostData = array();
		  parse_str($request['data']['formData'], $newPostData);
		  
			 if(empty($newPostData['state_code'])){
				  $returnData = array('error' => config('messages.message.stateCodeRequired'));
			 }else if(empty($newPostData['state_id'])){
				  $returnData = array('error' => config('messages.message.stateCodeRequired'));
			 }else if(empty($newPostData['state_name'])){
				  $returnData = array('error' => config('messages.message.stateNameRequired'));
			 }else if(empty($newPostData['country_id'])){
				  $returnData = array('error' => config('messages.message.stateCountryRequired'));
			 }else if(!isset($newPostData['state_status'])){
				  $returnData = array('error' => config('messages.message.stateStatusRequired'));
			 }else{
				  try{
						$newPostData['state_id']=base64_decode($newPostData['state_id']);
						$stateName = strtolower($newPostData['state_name']);
						$updated = DB::table('state_db')->where('state_id',$newPostData['state_id'])->update([
							 'state_code' => $newPostData['state_code'],
							 'country_id' => $newPostData['country_id'],
							 'state_name' => ucwords($stateName),
							 'state_status' => $newPostData['state_status'],
						]);
						//check if data updated in State table 
						$returnData = array('success' => config('messages.message.stateUpdated'));	
				  }catch(\Illuminate\Database\QueryException $ex){
					  $returnData = array('error' => config('messages.message.stateCodeExist'));
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

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteStateData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$state = DB::table('state_db')->where('state_id', $request['data']['id'])->delete();
					if($state){
						$returnData = array('success' => config('messages.message.stateDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.stateNotDeleted'));					
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
