<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models;
use App\Http\Requests;
use Auth;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;
use Route;
use Session;

class AccountSettingController extends Controller
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
	    
	    /************************************
	    * Description : Display current user details and change pwd form
	    * Date        : 4-may-2017
	    * Author      : nisha
	    * Parameter   : \Illuminate\Http\Request  $request
	    * @return     : \Illuminate\Http\Response
	    ************************************/
	    public function index(){
			
			$user_id            = defined('USERID') ? USERID : '0';
			$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
			$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
			$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
			
			return view('my_account.index',['title' => 'My Account','_my_account' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
	    }
	    
	    /************************************
	    * Description : Display current user details and change pwd form
	    * Date        : 4-may-2017
	    * Author      : nisha
	    * Parameter   : \Illuminate\Http\Request  $request
	    * @return     : \Illuminate\Http\Response
	    ************************************/
	    public function changePassword(){			
			return view('my_account.passwordexpiry',['title' => 'Change Password','_reset_password' => 'active']);
	    }
	    
	    /************************************
	    * Description : Display current user details and change pwd form
	    * Date        : 4-may-2017
	    * Author      : nisha
	    * Parameter   : \Illuminate\Http\Request  $request
	    * @return     : \Illuminate\Http\Response
	    ************************************/
	    public function expiryPassword(){			
			return view('my_account.passwordexpiry',['title' => 'Reset Password','_reset_password' => 'active']);
	    }
		
	    /************************************
	    * Description : get current logeedin user details
	    * Date        : 4-may-2017
	    * Author      : nisha
	    * Parameter   : \Illuminate\Http\Request  $request
	    * @return     : \Illuminate\Http\Response
	    ************************************/
	    public function getAccountDetails(){
					
			$user_id            = defined('USERID') ? USERID : '0';
			$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
			$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
			$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
			
			if(!$division_id){
				    $user = DB::table('users')->join('roles', 'roles.id', '=', 'users.role_id')->where('users.id', '=', $user_id)->first();
				    $userDetail['userData'] = $user;
			}else{
				    $user = DB::table('users')
						->leftJoin('roles', 'roles.id', '=', 'users.role_id')
						->join('divisions', 'users.division_id', '=', 'divisions.division_id')				
						->join('users as u', 'users.created_by', '=', 'u.id')
						->select('divisions.division_name','users.id', 'users.name', 'users.email','u.name as createdBy','users.created_at', 'users.updated_at', 'users.user_code', 'users.is_sales_person', 'users.role_id')
						->where('users.id', '=', $user_id)
						->first();
					
				    $userDetail['userData'] = $user;
				    
				    $userDetail['departments']= DB::table('users_department_detail')
					    ->join('departments', 'users_department_detail.department_id', '=', 'departments.department_id')
					    ->select('users_department_detail.user_id','users_department_detail.department_id','departments.department_name')
					    ->where('users_department_detail.user_id','=',$user->id)
					    ->get();
					    
				    $userDetail['roles'] = DB::table('role_user')
					    ->join('roles', 'roles.id', '=', 'role_user.role_id')
					    ->select('role_user.user_id','role_user.role_id','roles.name')
					    ->where('role_user.user_id','=',$user->id)
					    ->get();
					    
				    $userDetail['equipmentTypes'] = DB::table('users_equipment_detail')
					    ->join('equipment_type', 'users_equipment_detail.equipment_type_id', '=', 'equipment_type.equipment_id')
					    ->select('users_equipment_detail.user_id','users_equipment_detail.equipment_type_id','equipment_type.equipment_name')
					    ->where('users_equipment_detail.user_id','=',$user->id)
					    ->get();
			    
				    $userDetail = json_decode(json_encode($userDetail),true);				
			}
					
			return response()->json(['userDetail' => $userDetail]);
	    }
		
	    public function updatePassword(Request $request){
			$returnData = array(); 
			if ($request->isMethod('post')) {
				    if(!empty($request['data']['formData'])){  
						
						//pasrse searlize data 
						$newPostData = array();
						parse_str($request['data']['formData'], $newPostData); 
						$password_confirmation = $newPostData['password_confirmation'];
						$user = Auth::user();
						
						if(Hash::check($newPostData['old_password'],Auth::user()->password))
						{                
							    if($newPostData['password']!=$newPostData['password_confirmation'])
							    {
								    $returnData = array('error' =>  config('messages.message.pwdNotMatch'));
							    }else{ 
								    $pwd['password'] = bcrypt($newPostData['password']);
								    DB::table('users')->where('id',Auth::user()->id)->update($pwd);
								    $returnData = array('success' =>  config('messages.message.pwdUpdated'));
							    }
						}else{
							    $returnData = array('error' =>  config('messages.message.invalidOldPwd'));					
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
	    * Show the form for editing the specified resource.
	    *
	    * @param  int  $id
	    * @return \Illuminate\Http\Response
	    */
	    public function updateAccount(Request $request){
			
			global $models;
			
			$error    = '0';
			$message  = config('messages.message.error');
			$data     = '';
			$formData = array();	
			
			if ($request->isMethod('post') && !empty($request['formData'])){
			
				    //pasrse searlize data 				
				    parse_str($request['formData'], $formData);           
				    $userId = !empty($formData['user_id']) ? $formData['user_id'] : '0';
				    
				    if(empty($formData['name'])){
						$message = config('messages.message.nameErrorMsg');			
				    }else{
						//Unsetting the variable from request data
						$formData = $models->unsetFormDataVariables($formData,array('_token','user_id'));
							
						if(!empty($userId) && !empty($formData)){
						    if(DB::table('users')->where('users.id',$userId)->update($formData)){
										$error   = '1';
										$message = config('messages.message.updated');    
									}else{
										$error   = '1';
										$message = config('messages.message.savedNoChange');  
									}
						}else{
						    $message = config('messages.message.updatedError'); 
						}
				    }			
			}
			return response()->json(['error'=> $error,'message'=> $message,'data'=> $data,'user_id' => $userId]);
	    }
	    
	    /**
	    * Show the form for editing the specified resource.
	    *
	    * @param  int  $id
	    * @return \Illuminate\Http\Response
	    */
	    public function switchUserAssignedRole(Request $request){
			
			global $models;
			
			$error    = '0';
			$message  = config('messages.message.error');
			$data     = '';
			$formData = array();
			$userId   = defined('USERID') ? USERID : '0';
			
			if ($request->isMethod('post') && !empty($userId)){
			
				    //pasrse searlize data
				    $formData = $request->all();
				    
				    if(empty($formData['role_id'])){
						Session::put('errorMsg', config('messages.message.updatedError'));			
				    }else{
						//Unsetting the variable from request data
						$formData = $models->unsetFormDataVariables($formData,array('_token'));
							
						if(!empty($request['role_id'])){
							    if(DB::table('users')->where('users.id',$userId)->update($formData)){
									Session::put('successMsg', config('messages.message.updatedError'));	
							    }else{
									Session::put('errorMsg', config('messages.message.savedNoChange'));	
							    }
						}else{
							    Session::put('errorMsg', config('messages.message.updatedError'));
						}
				    }			
			}
			return redirect::back();
			
	    }
	    
	    /**************************************************
	     *Desc : Change Password
	     *Created By : Praveen Singh
	     *Created On : 28-May-2019
	     **************************************************/
	    public function processResetPassword(Request $request){
			
			if($request->isMethod('post') && !empty($request->all())){
				    
				    //Current Session Data
				    $user = Auth::user();
				    
				    if(!empty($user->id)){				    
						$this->validate($request, [
							    'old_password'     => 'required',
							    'new_password'     => 'required|min:6',
							    'confirm_password' => 'required|same:new_password',
						]);
						
						if(!Hash::check(Input::get('old_password'), $user->password)){
							    return redirect()->back()->with('errorMsg',config('messages.message.invalidOldPwd'));
						}else{
							    $user->password = Hash::make(Input::get('new_password'));
							    $user->password_changed_at = date('Y-m-d H:i:s');
							    if($user->save()){
									return redirect('dashboard')->with('successMsg', config('messages.message.passwordChangeSuccess'));   
							    }else{
									return redirect()->back()->with('errorMsg', config('messages.message.passwordChangeError'));
							    }
							    
						}
				    }
			}
			return redirect()->back()->with('message', config('messages.message.error'));
	    }
}
