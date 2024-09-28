<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\Country;
use Validator;
use Route;
use DB;

class CountryController extends Controller
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
	$division_id   	  = defined('DIVISIONID') ? DIVISIONID : '0';		
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	
	return view('master.countries.index',['title' => 'Countries','_countries' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

	 /**
    * Get list of countries on page load.
    * CREATED AT: 01-01-19
    * CREATED BY : RUBY
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getCountriesList(Request $request){
		  global $models;
		  $error	  = '0';
		  $message  = '';
		  $data	  = '';
		  $formData = array();
		  
		  parse_str($request->formData, $formData);
	
		  $countriesObj = DB::table('countries_db')->select('countries_db.*');
		  $countries = $countriesObj->orderBy('countries_db.country_id','ASC')->get();
		  
		  $models->formatTimeStampFromArray($countries,DATETIMEFORMAT);
	
		  return response()->json(['countriesList' => $countries,]);
    }   

    /** create new state
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
	
		  $returnData = array();
	
		  if ($request->isMethod('post')) {
					 if(!empty($request['data']['formData'])){
					
					//pasrse searlize data 
					$newPostData = array(); 
					parse_str($request['data']['formData'], $newPostData);
					
					unset($newPostData['_token']);
					 if(empty($newPostData['country_code'])){
						  $returnData = array('error' => config('messages.message.countryCodeRequired'));
					 }else if(empty($newPostData['country_name'])){
						  $returnData = array('error' => config('messages.message.countryNameRequired'));
					 }else if(!isset($newPostData['country_status'])){
						 $returnData = array('error' => config('messages.message.countryStatusRequired'));
					 }else{
							try{
									 $newPostData['country_level'] = '0';
									 $created = DB::table('countries_db')->insert($newPostData);
								
								  //check if users created add data in user detail
								  if($created){ 
									  $returnData = array('success' => config('messages.message.countrySaved'));
								  }else{
										$returnData = array('error' => config('messages.message.countryNotSaved'));
								  }
							  
							}catch(\Illuminate\Database\QueryException $ex){
							  $returnData = array('error' => config('messages.message.countryCodeExist'));
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
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request){
		  
		  $returnData = array();
		  if ($request->isMethod('post')) {
				if(!empty($request['data']['id'])){
					$data = DB::table('countries_db')
									->select('countries_db.*')
									->where('countries_db.country_id', '=', $request['data']['id'])
									->first();
					
					if($data->country_id){
						$returnData = array('responseData' => $data);				
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
    public function update(Request $request){
	 $returnData = array();
	 
		  if ($request->isMethod('post')) {
				if(!empty($request['data']['formData'])){   
					 //pasrse searlize data 
					 $newPostData = array();
					 parse_str($request['data']['formData'], $newPostData);
					 //echo'<pre>'; print_r($newPostData); die;
	 
					 if(empty($newPostData['country_code'])){
								$returnData = array('error' => config('messages.message.countryCodeRequired'));
					 }else if(empty($newPostData['country_name'])){
						  $returnData = array('error' => config('messages.message.countryNameRequired'));
					 }else if(!isset($newPostData['country_status'])){
						 $returnData = array('error' => config('messages.message.countryStatusRequired'));
					 }else{
						  try{
								$newPostData['country_id'] = base64_decode($newPostData['country_id']);
								
								$updated = DB::table('countries_db')->where('country_id',$newPostData['country_id'])->update([
										'country_code' => $newPostData['country_code'],
										'country_name' => ucwords($newPostData['country_name']),
										'country_status' => $newPostData['country_status'],
										'country_level' => '0',
								]);
								//check if data updated in State table 
								$returnData = array('success' => config('messages.message.countryUpdated'));	
						  }catch(\Illuminate\Database\QueryException $ex){
							  $returnData = array('error' => config('messages.message.countryCodeExist'));
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
    public function delete(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$country = DB::table('countries_db')->where('countries_db.country_id', $request['data']['id'])->delete();
					if($country){
						$returnData = array('success' => config('messages.message.countryDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.countryNotDeleted'));					
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
