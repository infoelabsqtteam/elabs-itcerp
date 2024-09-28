<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\CustomDefineField;
use Validator;
use Route;
use DB;

class CustomDefinedFieldsController extends Controller
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
	
        return view('master.custom_defined_fields.index',['title' => 'Custom Defined Fields','_custom_defined_fields' => 'active','user_id' => $user_id]);
    }
    
    	
	/**
     * Get list of remarks on page load.
     * Date : 05-04-18
	 * Author : Pratyush
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCustomDefinedFields(Request $request){
		
	global $models;
	
	$customDefinedFieldsObj = DB::table('custom_defined_fields')
			->join('users', 'custom_defined_fields.created_by','users.id')
			->select('custom_defined_fields.*','users.name as createdBy');
	
	
	
	$customDefinedFieldsObj->orderBy('custom_defined_fields.label_id','DESC');        
	$customFields = $customDefinedFieldsObj->get();
	//print_r($customFields);die;
	//Formating Date Coloums
	$models->formatTimeStampFromArray($customFields,DATETIMEFORMAT);
	
	return response()->json(['customFieldList' => $customFields]);
    }


	
    /** create new remarks
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCustomDefinedFields(Request $request)
    {
	        global $item,$models;
		$returnData = array(); 
 		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array(); 
				parse_str($request['data']['formData'], $newPostData);   //print_r($newPostData);   die;
				unset($newPostData['_token']);
				 if(empty($newPostData['label_name'])){
					$returnData = array('error' => config('messages.message.labelNameRequired'));
				}else if(empty($newPostData['label_value'])){
					$returnData = array('error' => config('messages.message.labelValueRequired'));
				}else if(empty($newPostData['label_status'])){
					$returnData = array('error' => config('messages.message.labelStatusRequired'));
				}
				else{
				    
					
							$created = CustomDefineField::create([
							'label_name' => $newPostData['label_name'],
							'label_value' => $newPostData['label_value'],
							'label_status' => $newPostData['label_status'],
							'created_by' => \Auth::user()->id,
						   ]);
						  
						
						//check if users created add data in user detail
						if($created->id){ 
							$returnData = array('success' => config('messages.message.saved'));
						}else{
							$returnData = array('error' => config('messages.message.savedError'));
						}
					
				}
				}
			else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
				}
			}
			    else{
			    $returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			} 
		return response()->json($returnData); 		
    }
    


    
    /**
     * Show the form for editing the specified Custom Fields from list.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCustomDefinedFields(Request $request){
		
		$returnData = array();
		
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				//get user by email id
				$data = DB::table('custom_defined_fields')
						->where('custom_defined_fields.label_id', '=', $request['data']['id'])
						->first();
			if($data->label_id){
					$returnData = array('responseData' => $data);				
			    }else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}			
					    //echo '<pre>';print_r($data);die;
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		}
		return response()->json($returnData);	
    }
    /**
     * Update the Custom Fields in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCustomDefinedFields(Request $request){
		
        $returnData = array();
		
		if ($request->isMethod('post') && !empty($request['data']['formData'])){
				
			//pasrse searlize data 
			$newPostData = array();
			parse_str($request['data']['formData'], $newPostData); //print_r($newPostData);   die;
			
			 if(empty($newPostData['label_name'])){
				$returnData = array('error' => config('messages.message.labelNameRequired'));
			}else if(empty($newPostData['label_value'])){
				$returnData = array('error' => config('messages.message.labelValueRequired'));
			}else if(empty($newPostData['label_status'])){
				$returnData = array('error' => config('messages.message.labelStatusRequired'));
			}else{
				
					if(!empty($newPostData['label_id'])){
					$updated = DB::table('custom_defined_fields')->where('label_id',$newPostData['label_id'])->update([
							'label_name' 	        => $newPostData['label_name'],
							'label_value' 	        => $newPostData['label_value'],
							'label_status' 	        => $newPostData['label_status'],
						]);
						if($updated){
							//check if data updated in Method table 
							$returnData = array('success' => config('messages.message.saved'));		
						}else{
							$returnData = array('success' => config('messages.message.savedNoChange'));
						}
					}else{
						$returnData = array('error' =>  config('messages.message.dataNotFound'));
					}
							 
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
    public function deleteCustomDefinedFields(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$customDefinedFields = DB::table('custom_defined_fields')->where('label_id', $request['data']['id'])->delete();
					if($customDefinedFields){
						$returnData = array('success' => config('messages.message.customDefinedFieldskDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.CustomDefinedFieldsNotDeleted'));					
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
