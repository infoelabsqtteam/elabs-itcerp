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

class StabilityTypeController extends Controller
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
    public function index(){
		  $user_id            = defined('USERID') ? USERID : '0';
		  $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
		  $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		  $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
		  $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		return view('master.stability_type_master.index',['title' => 'Stability Type List','_stabilityTypeList' => 'active']);
    }
	 
	 
    /** save amendment record
    *  Show the form for creating a new resource.
    * created_by : Ruby
	 * created_on : 25-02-2019
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request){
	
	$returnData = array();
	
	$error    = '0';
	$message  = config('messages.message.error');
	$data     = '';
	
	if($request->isMethod('post') && !empty($request['data']['formData'])){
	
	    //pasrse searlize data 
	    $newPostData = array();
	    
	    parse_str($request['data']['formData'], $newPostData);
	    unset($newPostData['_token']);
	    
		 
	    if(empty($newPostData['stb_stability_type_name'])){
		$returnData = array('error' => config('messages.message.stabilityTypeRequired'));
	    }else if(isset($newPostData['stb_stability_type_status']) && $newPostData['stb_stability_type_status'] == ''){
		$returnData = array('error' => config('messages.message.stabilityTypeStatusRequired'));
	    }else{		
		  try{
				//Adding New Dynamic Fields
				$formData['stb_stability_type_name']   = ucwords(strtolower($newPostData['stb_stability_type_name']));
				$formData['stb_stability_type_status'] = $newPostData['stb_stability_type_status'];
				if(!empty($formData['stb_stability_type_name'])){
				$save = DB::table('stb_order_stability_types')->insertGetId($formData);	
					 if(!empty($save)){
						  $error   = '1';
						  $data    = $save;
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
	 * Get list of amendments on page load.
	 * created_by : Ruby
	 * created_on : 25-02-2019
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
    public function getList(){
		 
		global $models;
		$stabilityTypeDataList = DB::table('stb_order_stability_types')->orderBy('stb_order_stability_types.stb_stability_type_id','DESC')->get()->toArray();
		$models->formatTimeStampFromArray($stabilityTypeDataList,DATETIMEFORMAT);
		return response()->json(['stabilityTypeDataList' => $stabilityTypeDataList,]);
    }   
   
    /**
     * Show the form for editing the specified resource.
     * created_by : Ruby
	  * created_on : 25-02-2019
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				$data = DB::table('stb_order_stability_types')
								->where('stb_order_stability_types.stb_stability_type_id', '=', $request['data']['id'])
								->first();
				
				if($data->stb_stability_type_id){
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
     * created_by : Ruby
	  * created_on : 25-02-2019
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
					 if(empty($newPostData['stb_stability_type_name'])){
						  $returnData = array('error' => config('messages.message.stabilityTypeRequired'));
					 }else if(isset($newPostData['stb_stability_type_status']) && $newPostData['stb_stability_type_status'] == ''){
						  $returnData = array('error' => config('messages.message.stabilityTypeStatusRequired'));
					 }else{
						  try{
						  $updated = DB::table('stb_order_stability_types')->where('stb_order_stability_types.stb_stability_type_id',$newPostData['stb_stability_type_id'])->update([
								'stb_stability_type_status' 	=> $newPostData['stb_stability_type_status'],
								'stb_stability_type_name' 		=> ucwords($newPostData['stb_stability_type_name']),
						  ]);
						
						  if(!empty($updated)){
								 $returnData = array('success' => config('messages.message.stabilityTypeUpdated'));  
							}else{
								$returnData = array('success' => config('messages.message.savedNoChange'));   
							}
						  }catch(\Illuminate\Database\QueryException $ex){
								$returnData = array('error' => config('messages.message.exist')); 
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
     * created_by : Ruby
	  * created_on : 25-02-2019
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$data = DB::table('stb_order_stability_types')->where('stb_order_stability_types.stb_stability_type_id', $request['data']['id'])->delete();
					if($data){
						$returnData = array('success' => config('messages.message.stabilityTypeDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.stabilityTypeNotDeleted'));					
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
