<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Models;
use App\Setting;
use Session;
use Validator;
use Route;
use DB;

class SettingsController extends Controller
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
		global $setting,$models;
		$setting = new Setting();
		$models  = new Models();
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
		
        return view('settings.index',['title' => 'Setting','_setting' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
	
    /**
     * Display a listing of the resource.
     *  04-08-2017
     * @return \Illuminate\Http\Response
     */
    public function defaultSetting(){
		//echo 'TOTAL_RECORD=>'; print_r(TOTAL_RECORD); echo '<br>';

        $user_id            = defined('USERID') ? USERID : '0';
        $division_id        = defined('DIVISIONID') ? DIVISIONID : '0';     
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';     
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
        return view('settings.default_settings.index',['title' => 'Default Setting','_setting' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDefaultSettings(Request $request){
        $error              = '0';
        $message            = config('messages.message.error');
        $newFormData        = array();
        $defaultSetting     = array();

        if(!empty($request['formData'])){
            parse_str($request['formData'],$newFormData); 
            unset($newFormData ['_token']); 

            if(empty($newFormData['setting_group_id'])){
                    $message = array('error' => config('messages.message.settingGroupRequired'));
            }else if(empty($newFormData['setting_key'])){
                 $message = array('error' => config('messages.message.settingKeyRequired'));
            }else if(isset($newFormData['setting_value'])  && $newFormData['setting_value'] == ''){
                 $message = array('error' => config('messages.message.settingValueRequired'));
            }else{
                $defaultSetting['setting_group_id']  = $newFormData['setting_group_id'];
                $defaultSetting['setting_key']       = strtoupper(strtolower($newFormData['setting_key']));
                $defaultSetting['setting_value']     = $newFormData['setting_value'];
                $defaultSetting['setting_status']    = '1';

                 /* save setting record into table*/
                 $created = DB::table('settings')->insertGetId($defaultSetting);

                if($created){
                        $error   = '1';
                        $message = config('messages.message.saved');;  
                }    
            }
        }         
        return response()->json(['error'=> $error,'message'=> $message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getdefaultSettingList(Request $request){
        $settingList = DB::table('settings')
                        ->join('setting_groups','setting_groups.setting_group_id','=','settings.setting_group_id')
                        ->where('settings.setting_status','=','1')
			->where('settings.is_display','=','1')
                        ->get();

        return response()->json(['settingList'=> $settingList]);       
    }

    /************** edit setting form 05-08-2017 ***********/
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDefaultSettings(Request $request){
        $editSettingForm = '';
        if(!empty($request['setting_id'])){
            $editSettingForm = DB::table('settings')
                    ->join('setting_groups','setting_groups.setting_group_id','=','settings.setting_group_id')
                    ->where('settings.setting_status','=','1')
		    ->where('settings.is_display','=','1')
                    ->where('settings.setting_id','=',$request['setting_id'])->first();    
        }
        //echo'<pre>'; print_r($editSettingForm); die;
        return response()->json(['editSettingForm'=>$editSettingForm]); 
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
    public function UpdateDefaultSettings(Request $request){
        $error              = '0';
        $message            = config('messages.message.error');
        $editFormData       = array();
        $defaultSetting     = array();
		$setting_id			= '';
        if(!empty($request['formData'])){
            parse_str($request['formData'],$editFormData); 
            unset($editFormData ['_token']);
             /* update setting record into table*/
            if(empty($editFormData['setting_group_id'])){
                    $message = array('error' => config('messages.message.settingGroupRequired'));
            }else if(empty($editFormData['setting_key'])){
                 $message = array('error' => config('messages.message.settingKeyRequired'));
            }else if(isset($editFormData['setting_value'])  && $editFormData['setting_value'] == ''){
                 $message = array('error' => config('messages.message.settingValueRequired'));
            }
            else{
                $updated = DB::table('settings')->where('settings.setting_id',$editFormData['setting_id'])->update([
                    'setting_group_id'  => $editFormData['setting_group_id'],
                    'setting_key'       => strtoupper($editFormData['setting_key']),
                    'setting_value'     => $editFormData['setting_value'],
                ]);

                if($updated){
                        $error   = '1';
                        $message = config('messages.message.settingUpdated');
						
                }else{
				 $message = config('messages.message.settingNotUpdated');	
				}
            }
        }

        return response()->json(['error'=> $error,'message'=> $message,'setting_group_id'=>$editFormData['setting_group_id']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteDefaultSettings(Request $request)
    {
        $returnData = array();
        if ($request->isMethod('post')){
            if(!empty($request['data']['setting_id'])){
                try { 
                    $settings = DB::table('settings')->where('setting_id', $request['data']['setting_id'])->delete();
                    if($settings){
                        $returnData = array('success' => config('messages.message.settingDeleted'));
                    }else{
                        $returnData = array('error' => config('messages.message.settingNotDeleted'));                  
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

    /* filter according to setting list 05-08-2017*/
    public function getGroupWiseSettingList(Request $request){
       
        $settingList = DB::table('settings')
                    ->join('setting_groups','setting_groups.setting_group_id','=','settings.setting_group_id')
                    ->where('settings.setting_status','=','1')
		    ->where('settings.is_display','=','1');
		    
        if(!empty($request['setting_group_id'])){
            $settingList->where('settings.setting_group_id','=',$request['setting_group_id']);  
        }
        $fiterSettingList = $settingList->get();
        //echo'<pre>'; print_r($fiterSettingList); die;
        return response()->json(['settingList'=>$fiterSettingList]); 
   }

}
