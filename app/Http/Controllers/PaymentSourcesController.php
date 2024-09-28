<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\PaymentSources;
use Validator;
use Route;
use DB;

class PaymentSourcesController extends Controller
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
     * Display a listing of departments.
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
		
        return view('master.payment_sources.index',['title' => 'Payment Sources','_payment_sources' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
	
    /** create new payment source
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPaymentSources(Request $request)
    {   
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);  
				unset($newPostData['_token']);
 				$newPostData['created_by']=Auth::user()->id;
				$newPostData['payment_source_name']= ucwords($newPostData['payment_source_name']);
				if(empty($newPostData['payment_source_name'])){
					$returnData = array('error' => config('messages.message.paymentSourceNameRequired'));
				}else if(empty($newPostData['payment_source_description'])){
					$returnData = array('error' => config('messages.message.paymentSourceDescRequired'));
				}else{   
					// check if payment source already exist or not 
					if($this->isSourceExist($newPostData['payment_source_name']) == 0){
						$created = DB::table('payment_sources')->insert($newPostData);  
						//check if payment source created in payment_sources table
						if($created){  
							$returnData = array('success' => config('messages.message.paymentSourceSaved'));
						}else{
							$returnData = array('error' => config('messages.message.savedError'));
						}
					}else{
						$returnData = array('error' => config('messages.message.paymentSourceExist'));
					}
				}
			}else{
				$returnData = array('error' =>  config('messages.message.dataNotFound'));
			}
		}else{
			$returnData = array('error' =>  config('messages.message.dataNotFound'));
		} 
		return response()->json($returnData);		
    }

    /**
     * Get list of patment sources on page load.
     * Date : 16-05-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPaymentSources()
    {
		global $models;
		$sources = DB::table('payment_sources')
				  ->join('users', 'payment_sources.created_by', '=', 'users.id')
				  ->select('payment_sources.*','payment_sources.created_at as created_at','payment_sources.updated_at as updated_at','users.name as createdBy')
				  ->get();	 
		$models->formatTimeStampFromArray($sources,DATETIMEFORMAT);
		return response()->json([
		   'paymentSources' => $sources,
		]);
    }
	
	/**
     * get patment sources using multisearch.
     * Date : 19-04-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPaymentSourcesMultisearch(Request $request)
    {   
	    $searchArry=$request['data']['formData']; 	
		global $models;
		$sourcesObj = DB::table('payment_sources')
				  ->join('users', 'payment_sources.created_by', '=', 'users.id')
				  ->select('payment_sources.*','payment_sources.created_at as created_at','payment_sources.updated_at as updated_at','users.name as createdBy');
				  
				if(!empty($searchArry['search_payment_source_name'])){
					$sourcesObj->where('payment_sources.payment_source_name','like','%'.$searchArry['search_payment_source_name'].'%');
				}
				if(!empty($searchArry['search_payment_source_description'])){
					$sourcesObj->where('payment_sources.payment_source_description','like','%'.$searchArry['search_payment_source_description'].'%');
				}			
				if(!empty($searchArry['search_created_by'])){
					$sourcesObj->where('users.name','like','%'.$searchArry['search_created_by'].'%');
				}
				if(!empty($searchArry['search_status'])){
					if(strtolower($searchArry['search_status'])=='active'){
						$sourcesObj->where('payment_sources.status','=',1);						
					}else if(strtolower($searchArry['search_status'])=='deactive'){
						$sourcesObj->where('payment_sources.status','=',0);	
					}
				}
				
		$sources=$sourcesObj->get();	 
		$models->formatTimeStampFromArray($sources,DATETIMEFORMAT);
		return response()->json([
		   'paymentSources' => $sources,
		]); 
    }

    /**
     * function used to check the patment sources duplicate entry by name
     * Date : 16-05-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isSourceExist($name) 
    { 
		if(!empty($code)){
			$data = DB::table('payment_sources')
						->orwhere('payment_sources.payment_source_name', '=', $name)
						->first(); 
			if(@$data->payment_source_id){
				return $data->payment_source_id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
    
    /**
     * Show the form for editing the specified patment source.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPaymentSources(Request $request)
    {    
		$returnData = array();
		if($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				$source =DB::table('payment_sources')
							->where('payment_sources.payment_source_id', '=', $request['data']['id'])
							->first();
				if($source->payment_source_id){
					$returnData = array('responseData' => $source);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' => config('messages.message.provideAppData'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		} 
		return response()->json(['returnData'=>$returnData]);	
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePaymentSources(Request $request)
    { 
        $returnData = array();
		if ($request->isMethod('post')) { 
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData); 
			    unset($newPostData['_token']);
			 	if(empty($newPostData['payment_source_id'])){
					$returnData = array('error' => config('messages.message.paymentSourceIdRequired'));
				}else if(empty($newPostData['edit_payment_source_name'])){
					$returnData = array('error' => config('messages.message.paymentSourceNameRequired'));
				}else if(empty($newPostData['edit_payment_source_description'])){
					$returnData = array('error' => config('messages.message.paymentSourceDescRequired'));
				}else{    
					$payment_source_id=base64_decode($newPostData['payment_source_id']);
					$saveData['payment_source_name']= ucwords($newPostData['edit_payment_source_name']);
					$saveData['payment_source_description']=$newPostData['edit_payment_source_description'];
					$saveData['status']=$newPostData['status'];
					$created = DB::table('payment_sources')->where('payment_source_id','=',$payment_source_id)->update($saveData);  
					//check if data updated in departments table 
                    $returnData = array('success' => config('messages.message.updated'));					  
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFound'));
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
    public function deletePaymentSources(Request $request)
    {   
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){		
				try{ 
					$source = DB::table('payment_sources')->where('payment_source_id', $request['data']['id'])->delete();
					if($source){
						$returnData = array('success' => config('messages.message.deleted'));
					}else{
						$returnData = array('error' => config('messages.message.deletedError'));					
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
