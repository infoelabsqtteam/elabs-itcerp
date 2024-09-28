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

class HolidaysController extends Controller
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
    public function index(){
	
	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	
	return view('master.holidays.index',['title' => 'Holidays','_holidays' => 'active']);
    }
    
    /** create new state
    *  Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function createHoliday(Request $request){
	
	$returnData = $newPostData = array();
	
	$error    = '0';
	$message  = config('messages.message.error');
	$data     = '';
	
	if($request->isMethod('post') && !empty($request['data']['formData'])){
	
	    //pasrse searlize data
	    parse_str($request['data']['formData'], $newPostData);
	    
	    if(empty($newPostData['division_id'])){
		$returnData = array('error' => config('messages.message.divisionRequired'));
	    }else if(empty($newPostData['holiday_name'])){
		$returnData = array('error' => config('messages.message.holidayNameRequired'));
	    }else if(empty($newPostData['holiday_date'])){
		$returnData = array('error' => config('messages.message.holidayDateRequired'));
	    }else if(isset($newPostData['holiday_status']) && strlen(trim($newPostData['holiday_status'])) <= '0'){
		$returnData = array('error' => config('messages.message.holidayStatusRequired'));
	    }else{		
		try{
		    //Adding New Dynamic Fields
		    unset($newPostData['_token']);
		    $formData['holiday_name']   = trim($newPostData['holiday_name']);
		    $formData['holiday_date']   = $newPostData['holiday_date'];
		    $formData['holiday_status'] = $newPostData['holiday_status'];
		    $formData['division_id']    = $newPostData['division_id'];
		    
		    if(!empty($formData['holiday_name'])){
			$holidayId = DB::table('holiday_master')->insertGetId($formData);	
			if(!empty($holidayId)){
			    $error   = '1';
			    $data    = $holidayId;
			    $message = config('messages.message.saved');                                    
			}
		    }
		}catch(\Illuminate\Database\QueryException $ex){
		    $message = config('messages.message.exist');
		}
	    }	
	}else{
	    $message  = config('messages.message.error');
	}
	
	return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data));	
    }

    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : Praveen SIngh
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getHolidaysList(){
	
	global $models;
	
	$holidaysList = DB::table('holiday_master')
		    ->join('divisions','divisions.division_id','holiday_master.division_id')
		    ->select('holiday_master.*','divisions.division_name')
		    ->orderBy('holiday_master.holiday_date','ASC')
		    ->get();
	
	//Formating Date and Time
	$models->formatTimeStampFromArray($holidaysList,DATETIMEFORMAT);
	
	//echo '<pre>';print_r($holidaysList);die;
	return response()->json(['holidaysList' => $holidaysList]);
    }   
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editHolidayData(Request $request){
	
	$returnData = array();
	if ($request->isMethod('post')) {
	    if(!empty($request['data']['id'])){
		$holidayData = DB::table('holiday_master')->where('holiday_master.holiday_id', '=', $request['data']['id'])->first();
		if($holidayData->holiday_id){
		    $returnData = array('responseData' => $holidayData);				
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
    public function updateHolidayData(Request $request){
	
        $returnData = $newPostData = array();
	
	if ($request->isMethod('post') && !empty($request['data']['formData'])){
	    try{
		
		//pasrse searlize data		
		parse_str($request['data']['formData'], $newPostData);
		
		if(empty($newPostData['holiday_id'])){
		    $returnData = array('error' => config('messages.message.updatedError'));
		}else if(empty($newPostData['division_id'])){
		    $returnData = array('error' => config('messages.message.divisionRequired'));
		}else if(empty($newPostData['holiday_name'])){
		    $returnData = array('error' => config('messages.message.holidayNameRequired'));
		}else if(empty($newPostData['holiday_date'])){
		    $returnData = array('error' => config('messages.message.holidayDateRequired'));
		}else if(isset($newPostData['holiday_status']) && strlen(trim($newPostData['holiday_status'])) <= '0'){
		    $returnData = array('error' => config('messages.message.holidayStatusRequired'));
		}else{
		    if(DB::table('holiday_master')->where('holiday_id',$newPostData['holiday_id'])->update(['division_id' => $newPostData['division_id'],'holiday_name' => trim($newPostData['holiday_name']),'holiday_status' => $newPostData['holiday_status'],'holiday_date' => $newPostData['holiday_date']])){
			$returnData = array('success' => config('messages.message.holidayUpdated'));	
		    }else{
			$returnData = array('success' => config('messages.message.savedNoChange'));
		    }
		}		   
	    }catch(\Illuminate\Database\QueryException $ex){
		$message = config('messages.message.updatedError');
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
    public function deleteHolidayData(Request $request){
	
	$returnData = array();
	
	if ($request->isMethod('post')){
	    if(!empty($request['data']['id'])){
		try { 
		    $state = DB::table('holiday_master')->where('holiday_id', $request['data']['id'])->delete();
		    if($state){
			$returnData = array('success' => config('messages.message.holidayDeleted'));
		    }else{
			$returnData = array('error' => config('messages.message.holidayNotDeleted'));					
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
