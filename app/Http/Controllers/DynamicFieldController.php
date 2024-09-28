<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Models;
use App\DynamicField;
use Validator;
use Route;
use DB;

class DynamicFieldController extends Controller
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
    public function index()
    {
        $user_id            = defined('USERID') ? USERID : '0';
        $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		
        return view('master.dynamic_fields.index',['title' => 'Dynamic Fields Master','_company_master' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

    public function listing()
    {
		global $models;
        $dynamicFields = DB::table('order_dynamic_fields')
                    ->join('users','users.id','order_dynamic_fields.odfs_created_by')
                    ->select('order_dynamic_fields.*','users.name as createdBy')
					->get();
		$models->formatTimeStampFromArray($dynamicFields,DATETIMEFORMAT);					
		return response()->json(['dynamicFieldsList' => $dynamicFields]);
    }   

    /** create new company
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  

				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData); 

				if(empty($newPostData['dynamic_field_name'])){
					$returnData = array('error' => config('messages.message.dynamicFieldNameRequired'));
				}else if(empty($newPostData['dynamic_field_code'])){
					$returnData = array('error' => config('messages.message.dynamicFieldCodeRequired'));
				}else if(empty($newPostData['dynamic_field_status'])){
					$returnData = array('error' => config('messages.message.dynamicFieldStatusRequired'));
				}else{
                    unset($newPostData['_token']);
                    if($this->isDynamicFieldExist($newPostData['dynamic_field_code']) == 0){
                        $created = DynamicField::create([
                            'dynamic_field_code'   => trim(ucwords(strtolower($newPostData['dynamic_field_code']))),
							'dynamic_field_name'   => trim(ucwords(strtolower($newPostData['dynamic_field_name']))),
                            'dynamic_field_status' => $newPostData['dynamic_field_status'],
                            'odfs_created_by' 	   => USERID,
                            ]);
                        
                        //check if users created add data in user detail
                        if($created->id){ 
                            $returnData = array('success' => config('messages.message.dynamicFieldSaved'));
                        }else{
                            $returnData = array('error' => config('messages.message.dynamicFieldNotSaved'));
                        }
                    }else{
						$returnData = array('error' => config('messages.message.dynamicFieldExist'));
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
     * isDynamicFieldExist Is used to check the dupicate code
     * Date : 2-16-2021
	 * Author : Anjana
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isDynamicFieldExist($dynamic_field_code) 
    { 
		if(!empty($dynamic_field_code)){
			$Data = DB::table('order_dynamic_fields')->where('order_dynamic_fields.dynamic_field_code', '=', $dynamic_field_code)->first(); 
			if(!empty($Data->odfs_id)){
				return $Data->odfs_id;
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
    public function editDynamicFieldData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){

				// get user by email id
				$dynamicFieldData = DB::table('order_dynamic_fields')->select('order_dynamic_fields.*')->where('order_dynamic_fields.odfs_id', '=', $request['data']['id'])->first();
				
				if($dynamicFieldData->odfs_id){
					$returnData = array('responseData' => $dynamicFieldData);				
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDynamicFieldData(Request $request)
    {
        $returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){

				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);   
				
				if(empty($newPostData['dynamic_field_name']))
				{
					$returnData = array('error' => config('messages.message.dynamicFieldNameRequired'));
				}else if(empty($newPostData['dynamic_field_code'])){
					$returnData = array('error' => config('messages.message.dynamicFieldCodeRequired'));
				}else if(empty($newPostData['dynamic_field_status'])){
					$returnData = array('error' => config('messages.message.dynamicFieldStatusRequired'));
				}else{
					$updated = DB::table('order_dynamic_fields')->where('odfs_id',$newPostData['odfs_id'])->update([
						'dynamic_field_name'   => trim(ucwords(strtolower($newPostData['dynamic_field_name']))),
						'dynamic_field_status' => $newPostData['dynamic_field_status'],
					   ]);
                     $returnData = array('success' => config('messages.message.dynamicFieldUpdated'));
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
	public function deleteDynamicFieldData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
						$company = DB::table('order_dynamic_fields')->where('odfs_id', $request['data']['id'])->delete();
						if($company){
							$returnData = array('success' => config('messages.message.dynamicFieldDeleted'));
						}else{
							$returnData = array('error' => config('messages.message.dynamicFieldNotDeleted'));					
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
