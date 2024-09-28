<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use App\OrderAccreditationCertificate;
use Session;
use Validator;
use Route;
use DB;

class orderAccreditationCertificateMasterController extends Controller
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

	global $models,$orderAccreCert;

	$models 	= new Models();
	$orderAccreCert = new OrderAccreditationCertificate();

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

    /**************************
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
    **************************/
    public function index(){

	global $models,$orderAccreCert;

	$user_id               = defined('USERID') ? USERID : '0';
	$division_id   	       = defined('DIVISIONID') ? DIVISIONID : '0';
	$department_ids        = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids              = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids    = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	$showOrderDateCalender = in_array('2',$department_ids) && PHARMA_BACK_DATE_SETTING == 1 ? '1' : '0';

	return view('master.accreditation_certificate_master.index',['title' => 'Accreditation Certificate Master','_accreditation_certificate_master' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    
    /*********************************************************
    *get accreditation certificates list
    *Created on:29-Aug-2018
    *Created By:Ruby
    *********************************************************/	
    public function getCertificatesList(Request $request){
	
	global $models,$orderAccreCert;
	
	$certificatesList = DB::table('order_accreditation_certificate_master')
			    ->join('divisions', 'divisions.division_id', '=', 'order_accreditation_certificate_master.oac_division_id')
			    ->join('product_categories','product_categories.p_category_id','order_accreditation_certificate_master.oac_product_category_id')
			    ->select('order_accreditation_certificate_master.*','divisions.division_name as oac_division_name','product_categories.p_category_name as oac_product_category_name')
			    ->get();
	
	//Formating the Date Time
	$models->formatTimeStampFromArray($certificatesList,DATETIMEFORMAT);
	return response()->json(['certificatesList' => $certificatesList]);
    }
    
    /*********************************************************
    *multi search and simple search on accreditation certificate list
    *Created on:29-Aug-2018
    *Created By:Ruby
    *********************************************************/	
    public function multiSearchAccreditationCertificate(Request $request){
	
	global $models,$orderAccreCert;
	
	$postedArry = $request['data']['formData']; 
	parse_str($postedArry,$searchArry) ;
	
	$keyword =  !empty($searchArry['search_keyword']) ? trim($searchArry['search_keyword']) : '';
	
	$certificate = DB::table('order_accreditation_certificate_master')
			->join('divisions', 'divisions.division_id', '=', 'order_accreditation_certificate_master.oac_division_id')
			->join('product_categories','product_categories.p_category_id','order_accreditation_certificate_master.oac_product_category_id')
			->select('order_accreditation_certificate_master.*','divisions.division_name as oac_division_name','product_categories.p_category_name as oac_product_category_name');
	if(!empty($searchArry['search_oac_division_id'])){
	    $certificate->where('order_accreditation_certificate_master.oac_division_id','=',trim($searchArry['search_oac_division_id']));
	}
	if(!empty($searchArry['search_oac_name'])){
	    $certificate->where('order_accreditation_certificate_master.oac_name','LIKE','%'.trim($searchArry['search_oac_name']).'%');
	}
	if(!empty($searchArry['search_oac_multi_location_lab_value'])){
	    $certificate->where('order_accreditation_certificate_master.oac_multi_location_lab_value','LIKE','%'.trim($searchArry['search_oac_multi_location_lab_value']).'%');
	}
	if(!empty($keyword)){
	    $certificate->where('order_accreditation_certificate_master.oac_name','LIKE','%'.trim($keyword).'%');
	    $certificate->orwhere('order_accreditation_certificate_master.oac_multi_location_lab_value','LIKE','%'.trim($keyword).'%');
	    if(strtolower($keyword) == 'active'){
		$certificate->orwhere('order_accreditation_certificate_master.oac_status','=','1');
	    }else if(strtolower($keyword) == 'inactive'){
		$certificate->orwhere('order_accreditation_certificate_master.oac_status','=','2');
	    }
	}
	
	$certificatesList=$certificate->get();	 
	$models->formatTimeStampFromArray($certificatesList,DATETIMEFORMAT);
	
	//echo'<pre>'; print_r($certificatesList); die;   
	return response()->json(['certificatesList' => $certificatesList]);	
    }

    /*********************************************************
    *save/create accreditation certificate
    *Created on:29-Aug-2018
    *Created By:Ruby
    *********************************************************/	
    public function createAccreditationCertificate(Request $request){

	global $models,$orderAccreCert;
	
	$error       	 	= '0';
	$message		= '';
	$data        	 	= '';
	$customerId  	 	= '0';
	$sampleId    	 	= '0';
	$currentDate     	= !defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('d-m-Y');
	$currentDateTime 	= !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
	$formData    	 	= array();
	
	//Saving record in orders table
	if(!empty($request->data) && $request->isMethod('post')){
	    
	    //Parsing the Serialze Dta
	    parse_str($request->data, $formData);
	    //echo'<pre>'; print_r($formData); die;
	    
	    if(!empty($formData)){
		if(empty($formData['oac_division_id'])){
		    $message = config('messages.message.divisionNameRequired');
		}else if(empty($formData['oac_product_category_id'])){
		    $message = config('messages.message.departmentRequired');
		}else if(empty($formData['oac_name'])){
		    $message = config('messages.message.accreditationCertificateNameRequired');
		}else if(!empty($formData['oac_name']) && strlen($formData['oac_name']) != '6'){
		    $message = config('messages.message.accreditationCertificateNameLength');
		}else if(isset($formData['oac_multi_location_lab_value']) && !strlen($formData['oac_multi_location_lab_value'])){
		    $message = config('messages.message.accreditationMultiLocationRequired');
		}else if(empty($formData['oac_status'])){
		    $message = config('messages.message.accreditationStatusRequired');
		}else if(!empty($formData) && $orderAccreCert->checkRecordExistance($formData)){
		    $message = config('messages.message.exist');
		}else{
		    try{			
			/****** unset variables from array ******/
			$formData = $models->unsetFormDataVariables($formData,array('_token'));			
			$formData['oac_created_by'] = USERID;			
			$saveAccreditationCertificateId = DB::table('order_accreditation_certificate_master')->insertGetId($formData);
			if($saveAccreditationCertificateId){
			    $error   	= '1';
			    $data 	= $saveAccreditationCertificateId;
			    $message 	= config('messages.message.certificateUpdated');
			}else{
			    $message 	= config('messages.message.certificateNotUpdated');					
			}
		    }catch(\Illuminate\Database\QueryException $ex){ 
			$message 	= config('messages.message.certificateNotUpdated');
		    }
		}
	    }
	}
	
	//echo'<pre>'; print_r($formData); die;
	return response()->json(['returnData' => array('error'=> $error,'message' => $message,'data'=> $data)]);
    }

    /*********************************************************
    *edit accreditation certificate data
    *Created on:29-Aug-2018
    *Created By:Ruby
    *********************************************************/	
    public function editAccreditationCertificate(Request $request){
	
	global $models,$orderAccreCert;
	
	$returnData = array();
	
	if ($request->isMethod('post') && !empty($request['data']['id'])){	
	    //get user by email id
	    $accCertData = DB::table('order_accreditation_certificate_master')
		    ->join('divisions', 'divisions.division_id', '=', 'order_accreditation_certificate_master.oac_division_id')
		    ->select('divisions.division_name','order_accreditation_certificate_master.*')
		    ->where('order_accreditation_certificate_master.oac_id', '=', $request['data']['id'])
		    ->first();
	    if($accCertData->oac_id){
		$returnData = array('responseData' => $accCertData);				
	    }else{
		$returnData = array('error' => config('messages.message.noRecordFound'));
	    }
	}else{
	    $returnData = array('error' => config('messages.message.provideAppData'));			
	}
	
	//echo'<pre>'; print_r($returnData);die;	
	return response()->json(['returnData'=>$returnData]);	
    }
    
    /*********************************************************
    *update accreditation certificate data
    *Created on:29-Aug-2018
    *Created By:Ruby
    *********************************************************/	
    public function updateAccreditationCertificate(Request $request){
	
    	global $models,$orderAccreCert;
	
	$returnData = $formData = array();
	
	if($request->isMethod('post') && !empty($request['data']['formData'])) {	    
	    
	    //pasrse searlize data 
	    parse_str($request['data']['formData'], $formData);
		
	    if(empty($formData['oac_division_id'])){
		$returnData = array('error' => config('messages.message.divisionNameRequired'));
	    }else if(empty($formData['oac_product_category_id'])){
		$message = config('messages.message.departmentRequired');
	    }else if(empty($formData['oac_name'])){
		$returnData = array('error' => config('messages.message.accreditationCertificateNameRequired'));
	    }else if(!empty($formData['oac_name']) && strlen($formData['oac_name'])!='6'){
		$returnData = array('error' => config('messages.message.accreditationCertificateNameLength'));
	    }else if(isset($formData['oac_multi_location_lab_value']) && !strlen($formData['oac_multi_location_lab_value'])){
		$returnData = array('error' => config('messages.message.accreditationMultiLocationRequired'));
	    }else if(empty($formData['oac_status'])){
		$returnData = array('error' => config('messages.message.accreditationStatusRequired'));
	    }else{ 
		try{
		    if(!empty($formData['oac_id'])){
			
			//Decoding Id
			$oac_id = base64_decode($formData['oac_id']);
			
			/****** unset variables from array ******/
			$formData = $models->unsetFormDataVariables($formData,array('_token','oac_id'));
			
			//Creating Form Data
			$newPostData['oac_division_id'] 		= $formData['oac_division_id'];
			$newPostData['oac_product_category_id'] 	= $formData['oac_product_category_id'];
			$newPostData['oac_name'] 			= $formData['oac_name'];
			$newPostData['oac_multi_location_lab_value'] 	= $formData['oac_multi_location_lab_value'];
			$newPostData['oac_status'] 			= $formData['oac_status'];
		    
			//check if data updated in order accreditation certificate table
			$updateCertificateData =  DB::table('order_accreditation_certificate_master')->where('oac_id','=',$oac_id)->update($newPostData);
			if($updateCertificateData){
			    $returnData = array('success' => config('messages.message.updated'));			
			}else{
			    $returnData = array('success' => config('messages.message.savedNoChange'));
			}	
		    }else{
			$returnData = array('error' =>  config('messages.message.dataNotFound'));
		    }
		}catch(\Illuminate\Database\QueryException $ex){ 
		    $returnData = array('error' => config('messages.message.exist'));
		}
	    }
	}else{
	   $returnData = array('error' => config('messages.message.provideAppData'));
	}
	
	//echo'<pre>'; print_r($returnData); die;
	return response()->json($returnData);
    }

    /*********************************************************
    *delet accreditation certificate from list
    *Created on:29-Aug-2018
    *Created By:Ruby
    *********************************************************/	
    public function deleteAccreditationCertificate(Request $request){
	
	global $models,$orderAccreCert;
    	
	$returnData = array();
	
	if($request->isMethod('post')){
	    if(!empty($request['data']['id'])){
		try{
		    $divisions = DB::table('order_accreditation_certificate_master')->where('order_accreditation_certificate_master.oac_id', $request['data']['id'])->delete();
		    if($divisions){
			$returnData = array('success' => config('messages.message.certificateDeleted'));
		    }else{
			$returnData = array('error' => config('messages.message.certificateNotDeleted'));					
		    }					
		}catch(\Illuminate\Database\QueryException $ex){ 
		    $returnData = array('error' => config('messages.message.foreignKeyDeleteError'));
		}
	    }else{
		$returnData = array('error' =>config('messages.message.noRecordFound'));
	    }
	}
	return response()->json($returnData);
    }
    
}
