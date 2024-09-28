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

class HeaderNoteController extends Controller
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
		
		return view('master.header_note_master.index',['title' => 'Header Notes List','_headerNoteList' => 'active']);
    }
	
	 
    /** save record
    *  Show the form for creating a new resource.
    * created_by : Ruby
	 * created_on : 27-12-2018
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request){
	
	$returnData = array();
	$userId = defined('USERID') ? USERID : '0';
	$error    = '0';
	$message  = config('messages.message.error');
	$data     = '';
	
	if($request->isMethod('post') && !empty($request['data']['formData'])){
	
	    //pasrse searlize data 
	    $newPostData = array();
	    
	    parse_str($request['data']['formData'], $newPostData);
	    unset($newPostData['_token']);
	    
		 
	    if(empty($newPostData['header_name'])){
		$returnData = array('error' => config('messages.message.headerNameRequired'));
	    }else if(isset($newPostData['header_status']) && $newPostData['header_status'] == ''){
		$returnData = array('error' => config('messages.message.headerStatusRequired'));
	    }else{		
		  try{
				//Adding New Dynamic Fields
				$formData['header_name']   = ucwords(strtolower($newPostData['header_name']));
				$formData['header_limit']  =  $newPostData['header_limit'];
				$formData['header_status'] = $newPostData['header_status'];
  
				$formData = array_filter($newPostData);
				$formData['created_by'] 	 = $userId;
			
				if(!empty($formData['header_name'])){
					 $save = DB::table('order_header_notes')->insertGetId($formData);	
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
    * created_on : 27-12-2018
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getHeaderNoteList(){
		 
	global $models;
	$headerNoteList = DB::table('order_header_notes')->orderBy('order_header_notes.header_id','ASC')->get();
	$models->formatTimeStampFromArray($headerNoteList,DATETIMEFORMAT);
	return response()->json(['headerNoteList' => $headerNoteList,]);
    }   
   
    /**
     * Show the form for editing the specified resource.
     * created_by : Ruby
	  * created_on : 27-12-2018
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				$amendData = DB::table('order_header_notes')
								->where('order_header_notes.header_id', '=', $request['data']['id'])
								->first();
				
				if($amendData->header_id){
					$returnData = array('responseData' => $amendData);				
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
	  * created_on : 27-12-2018
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
    
		  $returnData = array();
		  $userId = defined('USERID') ? USERID : '0';
		  if ($request->isMethod('post')) {
				if(!empty($request['data']['formData'])){   
					//pasrse searlize data 
					 $newPostData = array();
					 parse_str($request['data']['formData'], $newPostData);
					 unset($newPostData['_token']);
					 if(empty($newPostData['header_name'])){
						  $returnData = array('error' => config('messages.message.headerNameRequired'));
					 }else if(isset($newPostData['header_status']) && $newPostData['header_status'] == ''){
						  $returnData = array('error' => config('messages.message.headerStatusRequired'));
				    }else{
						  try{
								$header_limit = !empty($newPostData['header_limit']) ? $newPostData['header_limit'] : NULL;
								
								if(!empty($newPostData['header_name'])){
									 $updated = DB::table('order_header_notes')->where('order_header_notes.header_id',$newPostData['header_id'])
									 ->update([
										  'header_name' 		=> ucwords($newPostData['header_name']),
										  'header_limit' 		=> $header_limit,
										  'header_status' 	=> $newPostData['header_status'],
										  'created_by'			=> $userId,
									 ]);
									 if(!empty($updated)){
										$returnData = array('success' => config('messages.message.headerNoteUpdated'));  
									 }else{
										 $returnData = array('success' => config('messages.message.savedNoChange'));   
									 }
								}
						  }catch(\Illuminate\Database\QueryException $ex){
								$returnData = array('error' => config('messages.message.exist')); 
						  }
					 }
					 //}
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
	  * created_on : 27-12-2018
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$headerNote = DB::table('order_header_notes')->where('order_header_notes.header_id', $request['data']['id'])->delete();
					if($headerNote){
						$returnData = array('success' => config('messages.message.headerNoteDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.headerNoteNotDeleted'));					
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
