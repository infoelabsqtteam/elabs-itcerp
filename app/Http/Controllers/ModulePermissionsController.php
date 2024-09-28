<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Item;
use App\Models;
use Validator;
use DB;
use Route;
use App\Helpers\Helper;

class ModulePermissionsController extends Controller
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
		
        return view('roles.module_permissions.index',['title' => 'Module Permissions','_module_permissions' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);

    }
    
	/**
     * Display a listing of the all sub module menus.
     *
     * @return \Illuminate\Http\Response
     */  
	public function getSubModuleMenuList($role_id,$module_id,$sub_module_id)
    {    
		if(!empty($sub_module_id) && !empty($role_id) && !empty($sub_module_id)){
			$prePermissionsList = $this->getModulePreviousPermissions($role_id,$module_id,$sub_module_id);
			$subModuleMenuList = DB::table('modules')
						->select('id as id','module_name as name')
						->where('modules.parent_id','=',$sub_module_id)
						->get()->toArray();
			if(!empty($prePermissionsList)){
				foreach($prePermissionsList as $pre){
							$menuArr['module_menu_id'][]=$pre->module_menu_id;
							$menuArr[$pre->module_menu_id]['add']=$pre->add;
							$menuArr[$pre->module_menu_id]['edit']=$pre->edit;
							$menuArr[$pre->module_menu_id]['view']=$pre->view;
							$menuArr[$pre->module_menu_id]['delete']=$pre->delete;
				} 
				if(!empty($menuArr['module_menu_id'])){
				   foreach($subModuleMenuList as $key=>$menu){
						if(in_array($menu->id,$menuArr['module_menu_id'])){ 
							$menu->selected=true; 
							if($menuArr[$menu->id]['add']==1){ $menu->add=true;  }else{ $menu->add=false;  }
							if($menuArr[$menu->id]['edit']==1){ $menu->edit=true;  }else{ $menu->edit=false;  }
							if($menuArr[$menu->id]['view']==1){ $menu->view=true;  }else{ $menu->view=false;  }
							if($menuArr[$menu->id]['delete']==1){ $menu->delete=true;  }else{ $menu->delete=false;  }
						}else{ 
							$menu->selected=false;
							$menu->add=false;  
							$menu->edit=false;
							$menu->view=false; 
							$menu->delete=false; 							
						}
					}
				}
			}else{
				foreach($subModuleMenuList as $key=>$menu){
						$menu->selected=false; 
						$menu->add=false;  
						$menu->edit=false;
						$menu->view=false; 
						$menu->delete=false; 
				}
			}
			return response()->json(['subModuleMenuList' => $subModuleMenuList,'prePermissionsList' => $prePermissionsList]);
		}
    } 
	
	/**
     * get module previous permissions.
     *
     * @return \Illuminate\Http\Response
     */
	public function getModulePreviousPermissions($role_id,$module_id,$sub_module_id)
    {   
		$prePermissionsList=array();
		if(!empty($role_id) && !empty($module_id) && !empty($sub_module_id)){
			$prePermissionsList=DB::table('module_permissions')
							->where('role_id','=',$role_id)
							->where('module_id','=',$module_id)
							->where('sub_module_id','=',$sub_module_id)
							->get()->toArray();
		}
		return $prePermissionsList;
    }  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveModulePermissions(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){ 
				$newPostData = array();
				$user_id=Auth::user()->id;
				parse_str($request['data']['formData'], $newPostData); 
				DB::table('module_permissions')->select('*')->where('role_id','=',$newPostData['role_id'])->where('module_id','=',$newPostData['module_id'])->where('sub_module_id','=',$newPostData['sub_module_id'])->delete();
				if(!empty($newPostData['module_menu_id'])){	
					foreach($newPostData['module_menu_id'] as $menu){ 
						$moduleData['role_id']=$newPostData['role_id'];
						$moduleData['module_id']=$newPostData['module_id'];
						$moduleData['sub_module_id']=$newPostData['sub_module_id']; 
						$moduleData['module_menu_id']='';
						$moduleData['module_menu_id']=$menu;
						$moduleData['created_by']=$user_id;
						$optionData=array();
						$saveModule=array();
						if(!empty($newPostData[$menu])){
							foreach($newPostData[$menu] as $key=>$option){
							   $optionData[strtolower($option)] = '1'; 
							}
							$finalSubmitArr=array_merge($moduleData,$optionData);
							$saveModule[]=DB::table('module_permissions')->insert($finalSubmitArr);
						}
					}
					$returnData = array('success' =>  config('messages.message.modulePermissionsSaved'));				
			    }else{
					//$returnData = array('error' =>  config('messages.message.modulePermissionsEmpty'));
					$returnData = array('success' =>  config('messages.message.modulePermissionsSaved'));
				} 
			}else{
				$returnData = array('error' =>  config('messages.message.dataNotFound'));
			}
		}else{
			$returnData = array('error' =>  config('messages.message.dataNotFound'));
		} 
		return response()->json($returnData);
    }
	
}
