<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Module;
use App\Models;
use Validator;
use DB;
use Session;
use Route;
use App\Helpers\Helper;

class ModulesController extends Controller
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
        global $modules,$models;        
        $models  = new Models();
		$modules = new Module();
        $this->middleware('auth');
        $this->middleware(function ($request, $next){			
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
		
        return view('roles.modules.index',['title' => 'Modules','_modules' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getModuleCategory(Request $request)
    {
        $data = DB::table('modules')	
				   ->select('modules.id','modules.module_name')
				   ->where('modules.module_status','1')
				   ->whereIn('modules.module_level',array('0','1'))
				   ->get();
		return response()->json(['moduleCategoryList' => $data]);
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getModuleList($module_id)
    {
		global $modules,$models;
		
        $dataObj = DB::table('modules')
					->join('users as createdBy','createdBy.id','modules.created_by')
					->select('modules.*','createdBy.name as createdByName')
					->whereIn('modules.module_level',array('0','1','2'));
		
		if(!empty($module_id) && is_numeric($module_id)){
			$dataObj->where('modules.parent_id',$module_id);
		}
		
		$data = $dataObj->orderBy('modules.id','DESC')->get();
		
		//to formate created and updated date		   
		$models->formatTimeStampFromArray($data,DATETIMEFORMAT);
		
		return response()->json(['moduleDataList' => $data]);
    }

	/**
     * get patment sources using multisearch.
     * Date : 19-04-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getModulesMultisearch(Request $request)
    {   
	    $searchArry=$request['data']['formData']; //print_r($searchArry); die;	
		global $models;
		$dataObj = DB::table('modules')
					->join('users as createdBy','createdBy.id','modules.created_by')
					->select('modules.*','createdBy.name as createdByName')
					->whereIn('modules.module_level',array('0','1','2'));
					
				if(!empty($searchArry['search_module_id'])){
					$dataObj->where('modules.parent_id','=',$searchArry['search_module_id']);
				}
				if(!empty($searchArry['search_module_name'])){
					$dataObj->where('modules.module_name','like','%'.$searchArry['search_module_name'].'%');
				}
				if(!empty($searchArry['search_module_link'])){
					$dataObj->where('modules.module_link','like','%'.$searchArry['search_module_link'].'%');
				}	
				if(!empty($searchArry['search_module_level'])){
					$dataObj->where('modules.module_level','like','%'.$searchArry['search_module_level'].'%');
				}			
				if(!empty($searchArry['search_created_by'])){
					$dataObj->where('createdBy.name','like','%'.$searchArry['search_created_by'].'%');
				}
				if(!empty($searchArry['search_status'])){
					if(strtolower($searchArry['search_status'])=='active'){
						$dataObj->where('modules.module_status','=',1);						
					}else if(strtolower($searchArry['search_status'])=='deactive' || strtolower($searchArry['search_status'])=='inactive'){
						$dataObj->where('modules.module_status','=',0);	
					}
				}
				
		$data = $dataObj->orderBy('modules.id','DESC')->get(); 
		$models->formatTimeStampFromArray($data,DATETIMEFORMAT);
		return response()->json(['moduleDataList' => $data]); 
    }

    /** create new item
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function addNewModule(Request $request)
	{
        global $modules,$models;
        
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $returnData = $formData = array();
		
		if ($request->isMethod('post') && !empty($request['formData'])){ 
				
            //pasrse searlize data 				
            parse_str($request['formData'], $formData);
            if(empty($formData['module_name'])){
                $message = config('messages.message.moduleNameRequired');
            }else if(!isset($formData['module_level'])){
                $message = config('messages.message.moduleLevelRequired');
            }else if(!isset($formData['module_status'])){
                $message = config('messages.message.moduleStatusRequired');
            }else{
                unset($formData['_token']);				
                $formData['created_by'] = USERID;
				//echo '<pre>';print_r($formData);die;				
				if(!empty($formData)){
					$formData = array_map('trim', $formData);
					if(!$modules->isModuleLinkExist($formData['module_name'],$formData['module_link'],$formData['module_level'],$type='add')){
						$created = DB::table('modules')->insertGetId($formData);
						if($created){
							$error    = '1';
							$message = config('messages.message.saved');
						}else{
							$message = config('messages.message.savedError');
						}									
					}else{
						$message = config('messages.message.moduleLinkExist');
					}
                }else{
                    $message = config('messages.message.savedError'); 
                }
            }			
		}
        
		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data]);	
    }
	
	/**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function viewModule(Request $request,$module_id)
    {
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
				
		if(!empty($module_id)){			
			$moduleDetailList = DB::table('modules')->where('modules.id', '=', $module_id)->first();
            $message = '';
            $error   = !empty($moduleDetailList) ? 1 : 0;
		}
		
        //echo '<pre>';print_r($moduleDetailList);die;	
        return response()->json(array('error'=> $error,'message'=> $message,'moduleDetailList'=> $moduleDetailList));
    }
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateModule(Request $request)
    {
        global $modules,$models;
        
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
		$formData = array();
        
        if ($request->isMethod('post') && !empty($request['formData'])){
            
            //pasrse searlize data 				
            parse_str($request['formData'], $formData);
            $module_id = !empty($formData['id']) ? $formData['id'] : '0';
			
            if(empty($formData['module_name'])){
                $message = config('messages.message.moduleNameRequired');
            }else if(!isset($formData['module_level'])){
                $message = config('messages.message.moduleLevelRequired');
            }else if(!isset($formData['module_status'])){
                $message = config('messages.message.moduleStatusRequired');
            }else{
				//checking if Module Category Changes then remove all navigation setting
				$flag = $modules->isModuleCategoryChanged($formData);
				
                unset($formData['_token']);
                unset($formData['id']);	
                //echo '<pre>';print_r($formData);die;
				
                if(!empty($module_id) && !empty($formData)){
					$formData = array_map('trim', $formData);
					if($modules->isModuleLinkExist($formData['module_name'],$formData['module_link'],$formData['module_level'],$type='edit',$module_id)){
						
						//if changes then deleting all navigation setting for all roles
						$flag == true ? $modules->deleteModuleNavigationChanged($module_id) : '';					
                        
						//Updating records in modules table
						if(DB::table('modules')->where('modules.id',$module_id)->update($formData)){
							$error    = '1';
							$message = config('messages.message.updated');
						}else{
							$error   = '1';
							$message = config('messages.message.savedNoChange');  
						}
                    }else{
                        $message = config('messages.message.moduleLinkExist');
                    }
                }else{
                    $message = config('messages.message.updatedError'); 
                }
            }			
		}
        
		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data,'module_id' => $module_id]);
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteModule(Request $request,$module_id)
    {		
		$error   = '0';
        $message = config('messages.message.error');
        $data    = '';
        
        try{
			$moduleData = DB::table('modules')->where('modules.id',$module_id)->first();
			if(!empty($moduleData)){				
				if(DB::table('modules')->where('modules.id','=',$module_id)->delete()){
					$error    = '1';
					$message = config('messages.message.deleted');      
				} 
			}else{
                $message = config('messages.message.moduleDataNotFound');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.moduleForeignKeyConsFail');
        }             
		return response()->json(['error' => $error,'message' => $message]);
    }
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function navigation(Request $request){
		
		$user_id       = defined('USERID') ? USERID : '0';
        $division_id   = defined('DIVISIONID') ? DIVISIONID : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
    
        return view('roles.modules.navigation',['title' => 'Navigation','_navigation' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
	}
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getMenuSubmenuList($module_id,$role_id){
		
		$error   = '0';
        $message = config('messages.message.error');
        $data    = '';
		
		//getting all modules
		$modules = Module::with('children')
						  ->select('modules.id','modules.parent_id','modules.module_name','module_level')
						  ->where('modules.parent_id',$module_id)
						  ->get();
						  
		$menuSubmenuList = json_decode(json_encode($modules), true);
		
		//getting all checked modules
		$selectedModuleList = DB::table('module_navigations')
							->where('module_navigations.role_id',$role_id)
							->where('module_navigations.module_id',$module_id)
							->pluck('module_navigations.module_menu_id');
		
		//echo '<pre>';print_r($menuSubmenuList);die;
		return response()->json(['error' => $error,'message' => $message,'menuSubmenuList' => $menuSubmenuList , 'selectedModuleList' => $selectedModuleList]);
	}	
	
	/**
     * get all modules with children in tree formate
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getNavigationAllModulesList(Request $request){
		$error   = '0';
        $message = config('messages.message.error');
        $data    = '';
		
		//getting all modules
		$modules = Module::with(['children' => function ($q){$q->orderBy('module_sort_by');}])
						  ->select('modules.id','modules.parent_id','modules.module_name','module_level')
						  ->orderBy('modules.module_sort_by')
						  ->get()->toArray();
	
		$allModulesList = json_decode(json_encode($modules), true);
		//print_r($allModulesList); die;
		return response()->json(['error' => $error,'message' => $message,'allModulesList' => $allModulesList]);
	}
	
	/**
     * save all modules with children with sorting order
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveNavigationOrdering(Request $request){
		$error    = '0';
        $message  = config('messages.message.updatedError');
        $data     = '';
        $dataSave = $formData = array();
		
		if(!empty($request['data'])){
			$moduleSortedArr=$request['data'][0];
			$counter=0;
			foreach($moduleSortedArr as $mKey=>$module){  
			     $moduleData['module_sort_by']='';
				 $counter++;
				 $moduleData['module_sort_by']=$counter;
				 $update = DB::table('modules')->where('modules.id',$module['id'])->update($moduleData);
				 $subCounter=0;
				 foreach($module['items'] as $key=>$menu){
					 $subCounter++;	
					 $menuData['module_sort_by']=$subCounter;
					 $update = DB::table('modules')->where('modules.id',$menu['id'])->update($menuData);
				 }
			}
			$error    = '1';
            $message = config('messages.message.updated'); 
		}
		return response()->json(['error' => $error,'message' => $message]);
	}
	
	/**
     * save all modules menu with sorting order
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveNavigationMenuOrdering(Request $request){
		$error    = '0';
        $message  = config('messages.message.updatedError');
        $data     = '';
        $dataSave = $formData = array();
		
		if(!empty($request['data'])){
			$moduleSortedArr=$request['data'][0]; 
			foreach($moduleSortedArr as $mKey=>$module){
				 $subCounter=0;
				 foreach($module['itemsLevelTwo'] as $key=>$menu){
					 $subCounter++;	
					 $menuData['module_sort_by']=$subCounter;
					 $update = DB::table('modules')->where('modules.id',$menu['id'])->update($menuData);
				 }
			}  
			$error    = '1';
            $message = config('messages.message.updated'); 
		}
		return response()->json(['error' => $error,'message' => $message]);
	}
	
	/** create new item
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function addNavigationModule(Request $request)
	{
        global $modules,$models;
        
		$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $dataSave = $formData = array();
		
		if ($request->isMethod('post') && !empty($request['formData'])){ 
				
            //pasrse searlize data 				
            parse_str($request['formData'], $formData);
			if(empty($formData['role_id'])){
                $message = config('messages.message.moduleNameRequired');
            }else if(empty($formData['module_id'])){
                $message = config('messages.message.moduleStatusRequired');
            }else{
				//Deleting all setting first
				if(DB::table('module_navigations')
								->where('module_navigations.role_id','=',$formData['role_id'])
								->where('module_navigations.module_id','=',$formData['module_id'])
								->delete()){
					$error   = '1';
					$message = config('messages.message.saved');
				}else{
					$error   = '1';
					$message = config('messages.message.noChangeErrorMsg');
				}
				
				//Inserting all navigation settings
				if(!empty($formData['module_menu_id'])){
					foreach($formData['module_menu_id'] as $key => $value){
						$dataSave[$key]['role_id']        = $formData['role_id'];
						$dataSave[$key]['module_id']      = $formData['module_id'];
						$dataSave[$key]['module_menu_id'] = $value;
						$dataSave[$key]['created_by']     = USERID;
					}
				}
				
				//echo '<pre>';print_r($dataSave);die;
				
				//Saving the record in module_navigations tables
				if(!empty($dataSave) && DB::table('module_navigations')->insert($dataSave)){
					$error    = '1';
					$message = config('messages.message.saved');
				}
            }			
		}
        
		return response()->json(['error'=> $error,'message'=> $message,'data'=> $data]);	
    }
	
	
	
}
