<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\Department;
use Validator;
use Route;
use DB;

class DepartmentController extends Controller
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
		
        return view('master.department.index',['title' => 'Department','_department' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
	
	//autogenerate code
	public function getAutoGeneratedCode(Request $request)
    {
		global $models;
		$prefix=!empty(config('messages.message.departmentPrefix'))?config('messages.message.departmentPrefix'):'DEPT';
		$code=$models->generateCode($prefix,'departments','department_code','department_id');						  
		return response()->json(['uniqueCode' =>$code]);		
	}
	
    /** create new department
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDepartment(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData); 
				 if(empty($newPostData['department_code'])){
					$returnData = array('error' => config('messages.message.departmentCodeRequired'));
				}else if(empty($newPostData['department_name'])){
					$returnData = array('error' => config('messages.message.departmentNameRequired'));
				}else if(empty($newPostData['department_type'])){
					$returnData = array('error' => config('messages.message.departmentTypeRequired'));
				}else{   
					// check if department already exist or not 
					if($this->isDepartmentExist($newPostData['department_code'],$newPostData['department_name']) == 0){
						$depart_name 	= strtolower($newPostData['department_name']);
						$created = Department::create([
							'department_code' => $newPostData['department_code'],
							'department_name' => ucwords($depart_name),
							'company_id' => $newPostData['company_id'],
							'created_by' => \Auth::user()->id,
							'department_type' => $newPostData['department_type'],
						   ]); 
						//check if department created added in departments table
						if($created->id){  
							$returnData = array('success' => config('messages.message.departmentSaved'));
						}else{
							$returnData = array('error' => config('messages.message.departmentNotSaved'));
						}
					}else{
						$returnData = array('error' => config('messages.message.departmentExist'));
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
     * Get list of departments on page load.
     * Date : 02-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDepartmentsList()
    {
		global $models;
		$depts = DB::table('departments')
				  ->Join('department_type', 'departments.department_type', '=', 'department_type.department_type_id')
				  ->Join('company_master', 'departments.company_id', '=', 'company_master.company_id')
				  ->join('users', 'departments.created_by', '=', 'users.id')
				  ->select('departments.*','departments.created_at as department_created_at','departments.updated_at as department_updated_at','departments.*', 'company_master.*', 'department_type.*','users.name as createdBy')
				  ->orderBy('departments.department_name','ASC')->get();	 
		$models->formatTimeStampFromArray($depts,DATETIMEFORMAT);
		return response()->json([
		   'departmentsList' => $depts,
		]);
    }
	
	/**
     * get departments using multisearch.
     * Date : 19-04-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDeptListMultiSearch(Request $request)
    { 
	    $searchArry=$request['data']['formData'];  		
		global $models;
		$dept = DB::table('departments')
				  ->Join('department_type', 'departments.department_type', '=', 'department_type.department_type_id')
				  ->Join('company_master', 'departments.company_id', '=', 'company_master.company_id')
				  ->join('users', 'departments.created_by', '=', 'users.id')
				  ->select('departments.*','departments.created_at as department_created_at','departments.updated_at as department_updated_at','departments.*', 'company_master.*', 'department_type.*','users.name as createdBy');
				
				if(!empty($searchArry['search_department_code'])){
					$dept->where('departments.department_code','like','%'.$searchArry['search_department_code'].'%');
				}
				if(!empty($searchArry['search_department_name'])){
					$dept->where('departments.department_name','like','%'.$searchArry['search_department_name'].'%');
				}
				if(!empty($searchArry['search_department_type'])){
					$dept->where('department_type.department_type_name','like','%'.$searchArry['search_department_type'].'%');
				}
				if(!empty($searchArry['search_created_by'])){
					$dept->where('users.name','like','%'.$searchArry['search_created_by'].'%');
				}
				if(!empty($searchArry['search_created_at'])){
					$dept->where('departments.created_at','like','%'.$searchArry['search_created_at'].'%');
				}
				if(!empty($searchArry['search_updated_at'])){
					$dept->where('departments.updated_at','like','%'.$searchArry['search_updated_at'].'%');
				}
				
		$deptsList = $dept->orderBy('departments.department_name','ASC')->get();	 
		$models->formatTimeStampFromArray($deptsList,DATETIMEFORMAT);
		return response()->json([
		   'departmentsList' => $deptsList,
		]);
    }

    /**
     * isDepartmentExist Is used to check the dept duplicate entry by dept_code
     * Date : 02-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isDepartmentExist($code,$name) 
    { 
		if(!empty($code)){
			$deptData = DB::table('departments')
						->where('departments.department_code', '=', $code)
						->orwhere('departments.department_name', '=', $name)
						->first(); 
			if(@$deptData->department_id){
				return $deptData->department_id;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
    
    /**
     * Show the form for editing the specified department.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDepartmentData(Request $request)
    {
		$returnData = array();
		$companyData = DB::table('company_master')->select('company_id as id','company_name as name')->get();  //print_r( json_encode($companyData));die;
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				// get user by email id
				$deptData =DB::table('departments')
							->Join('company_master', 'departments.company_id', '=', 'company_master.company_id')
							->select('departments.*','company_master.company_name')
							->where('departments.department_id', '=', $request['data']['id'])
							->first();
				if($deptData->department_id){
					$returnData = array('responseData' => $deptData);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' => config('messages.message.provideAppData'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		} 
		//return response()->json($returnData);	
		return response()->json(['returnData'=>$returnData,'companyData'=>$companyData]);	
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDepartmentData(Request $request)
    {
        $returnData = array();
		if ($request->isMethod('post')) { 
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);  
			 	if(empty($newPostData['department_id']))
				{
					$returnData = array('error' =>  config('messages.message.departmentCodeRequired'));
				}else if(empty($newPostData['department_name1'])){
					$returnData = array('error' => config('messages.message.departmentNameRequired'));
				}else if(empty($newPostData['department_type1'])){
					$returnData = array('error' => config('messages.message.departmentTypeRequired'));
				}else{    
					$newPostData['department_id']=base64_decode($newPostData['department_id']);
					$depart_name 	= strtolower($newPostData['department_name1']);
 
					$updated = DB::table('departments')->where('department_id',$newPostData['department_id'])->update([
						'department_name' => ucwords($depart_name),
						'department_type' => $newPostData['department_type1'],
					   ]);
					//check if data updated in departments table 
                    $returnData = array('success' => config('messages.message.departmentUpdated'));					  
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
    public function deleteDepartment(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){		
				try{ 
					$departments = DB::table('departments')->where('department_id', $request['data']['id'])->delete();
					if($departments){
						$returnData = array('success' => config('messages.message.departmentDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.departmentNotDeleted'));					
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
	/**
     * Get list of departments uploaded by csv
     * Date : 06-04-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function uploadDepartment(Request $request)
    {
		return view('master.department.upload_departments');
	}
	
	/**
     * Get list of departments uploaded by csv
     * Date : 06-04-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function uploadDepartmentPreview(Request $request)
    {   
		global $models;
		$allowedFormat = array('application/vnd.ms-excel','application/csv','text/csv');
		if(empty($_FILES['department']['name'])){
			 $returnData = array('error' => config('messages.message.fileNotSelected'));	
		}else if(in_array($_FILES['department']['type'],$allowedFormat) && $_FILES['department']['size'] > '0'){				
			$header=$models->csvToArray($_FILES['department']['tmp_name']); 
			//check all required fields are filled or not			
			if(in_array('department_code',$header['header']) && in_array('department_name',$header['header']) && in_array('department_type',$header['header'])){				    
					foreach($header['data'] as $key=>$data){
						if(count(array_filter($data)) != count($data)){ $checkEmpty[$key]='Empty record found!';  } 
					} 
					if(!empty($checkEmpty)>0){
						$returnData = array('error' => config('messages.message.allFieldsRequired'));
					}else{
						$csvDeptCodeArray=array();
						$csvDeptNameArray=array();
						$finalDataForDisplay=array();
						$finalDataForSubmit=array();
						$message=count($header['newheader']);
						$header['newheader'][$message]='Message';			
						foreach($header['data'] as $key=>$data){
								$departmentExist=$this->isDepartmentExist($data[0],$data[1]); 
								$dataDisplay=$data;
								if($departmentExist == 0){
									if(in_array($data[0],$csvDeptCodeArray) || in_array($data[1],$csvDeptNameArray)){ 
										$dataDisplay[$message]="Duplicate!";
									}else{ 
										$csvDeptCodeArray[]=$data[0]; 
										$csvDeptNameArray[]=$data[1]; 
										$dataSubmit=$data;
										$dataDisplay[$message]="Success";
									}
								}else{
									$dataDisplay[$message]=config('messages.message.exist');
								}
								$headerColumns=$models->shiftArray($header['newheader']);						
								$dataColumns=$models->shiftArray($dataDisplay);	 					
								if(!empty($dataColumns)){
									$finalDataForDisplay[$key]=array_combine($header['newheader'],$dataColumns);
								}
								if(!empty($dataSubmit)){
									$finalDataForSubmit[$key]=array_combine($header['header'],$dataSubmit);
								}    
						}  			
						if(!empty($finalDataForDisplay)){
							 $returnData = array('success' => "Please check the list below");	
							 $returnData['newheader']=$headerColumns;	
							 $returnData['dataDisplay']=$finalDataForDisplay;
							 $returnData['dataSubmit']=$finalDataForSubmit;
							 $returnData['numberOfSubmitedRecords']=$csvDeptCodeArray;
						}else{
							 $returnData = array('error' => "Some error!");
						}
					}		
			}else{
				$returnData = array('error' => config('messages.message.departmentDetails'));
			}				
		}else{
			 $returnData = array('error' => config('messages.message.invalidFileType'));
		}
		return response()->json(['returnData'=> $returnData]);
	}
	
	    /**
	    * save list of departments uploaded by csv
	    * Date : 06-04-17
	    * Author : nisha
	    * @param  \Illuminate\Http\Request  $request
	    * @return \Illuminate\Http\Response
	    */
	    public function saveUploadedDepartment(Request $request)
	    {   
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$uploaded = array();
				$notUploaded = array();
				$duplicate = array();
				$loggedInUser = \Auth::user()->id; 	 			
				$newPostArray = $request['data']['formData']; 
                foreach($newPostArray as $key=>$newPostData) 
				{ 	
					if($this->isDepartmentExist($newPostData['department_code'],$newPostData['department_name']) == 0){ 
						$formData = array_filter($newPostData);
						$formData['created_by'] = $loggedInUser;
						$formData['company_id'] = '1';
						$created =DB::table('departments')->insertGetId($formData); 
						//check if users created add data in user detail
						if(!empty($created)){
							$uploaded[] = $newPostData['department_code'];
						}else{
							$notUploaded[] = $newPostData['department_code'];
						}
					}else{
						$duplicate[] = $newPostData['department_code']; 
					}  
				}
				$returnData['success'] = config('messages.message.save');				
				if(!empty($uploaded)){
					$returnData['upload']['uploaded']="These department(s) having name(s) ".implode(', ',$uploaded). " has been saved successfully.";
				}
				if(!empty($notUploaded)){
					$returnData['upload']['notUploaded']="Error in saving these department name(s): ".implode(', ',$notUploaded);
				}
				if(!empty($duplicate)){
					$returnData['upload']['duplicate']="These department(s)  having name(s) ".implode(', ',$duplicate). " already exist in our record!";
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}	
			return response()->json(['returnData'=>$returnData]);		
		}
	    }
	    
	    /********************************************************************
	    * Description : View dispatch detail for reports and invoices
	    * Date        : 13-03-2018
	    * Author      : Pratyush Singh
	    * Parameter   : \Illuminate\Http\Request  $request
	    * @return     : \Illuminate\Http\Response
	    ***********************************************************************/
	    public function getDepartmentProductCategoryDetail(Request $request){
	
			global $models;
		
			$error      = '0';
			$message    = config('message.message.error');
			$returnData = array();
			
			$returnData = DB::table('departments')
				    ->leftJoin('department_product_categories_link','departments.department_id','department_product_categories_link.department_id')
				    ->leftJoin('product_categories','product_categories.p_category_id','department_product_categories_link.product_category_id')
				    ->select('departments.department_id','departments.department_name','department_product_categories_link.department_product_categories_id','department_product_categories_link.product_category_id','product_categories.p_category_name')
				    ->orderBy('departments.department_id','DESC')
				    ->get()
				    ->toArray();
				    
			//to formate Dispatch date and Time
			$models->formatTimeStampFromArray($returnData,DATETIMEFORMAT);
			$error = !empty($returnData) ? 1 : 0;
		    
			//echo'<pre>';print_r($returnData); die;
			return response()->json(array('error'=> $error,'message'=> $message,'linkedWithProductCateList' => $returnData));
	    }
	    
	     /********************************************************************
	    * Description : View dispatch detail for reports and invoices
	    * Date        : 13-03-2018
	    * Author      : Pratyush Singh
	    * Parameter   : \Illuminate\Http\Request  $request
	    * @return     : \Illuminate\Http\Response
	    ***********************************************************************/
	    public function updateDepartmentProductCategoryDetail(Request $request){
	
			global $models;
		
			$error    = '0';
			$message  = config('message.message.error');
			$formData = array();
			
			try{			
				    if(!empty($request->formData)){
	    
						//Parsing of Form Data
						parse_str($request->formData, $formData);
						$formData  = array_filter($formData);
						//echo'<pre>';print_r($formData); die;
						
						if(!empty($formData['product_category_id'])){
							    foreach($formData['product_category_id'] as $department_id => $product_category_id){
									$department_id = trim(str_replace("'","",$department_id));
									if(!empty($department_id) && !empty($product_category_id)){
										    $isDepCatLinked = DB::table('department_product_categories_link')->where('department_product_categories_link.department_id','=',$department_id)->first();
										    if(!empty($isDepCatLinked->department_product_categories_id)){
												$dataUpdate = array('department_product_categories_link.product_category_id' => $product_category_id);
												DB::table('department_product_categories_link')->where('department_product_categories_link.department_product_categories_id','=',$isDepCatLinked->department_product_categories_id)->update($dataUpdate);    
												$error   = '1';
												$message = config('messages.message.updated');
										    }else{
												$dataSave = array();
												$dataSave['department_id'] = $department_id;
												$dataSave['product_category_id'] = $product_category_id;
												DB::table('department_product_categories_link')->insertGetId($dataSave);
												$error   = '1';
												$message = config('messages.message.saved');
										    }
									}
							    }
						}
				    }
			} catch(\Exception $e){
				    $error   = '0';
				    $message = config('messages.message.updatedError');
			}
			return response()->json(array('error'=> $error,'message'=> $message));			
	    }
	    
	    
	    
}
