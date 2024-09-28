<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\OrderReportNoteRemarkDefault;
use App\ProductCategory;
use Validator;
use Route;
use DB;

class OrderReportNoteRemarkDefaultController extends Controller
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
        global $models,$productCategory;
		$models = new Models();
		$productCategory = new ProductCategory();
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
	
        return view('master.default_remarks.index',['title' => 'Default Remarks','_default_remarks' => 'active','user_id' => $user_id,'division_id' => $division_id,'department_ids' => $department_ids]);
    }
    
    	
	/**
     * Get list of remarks on page load.
     * Date : 05-04-18
	 * Author : Pratyush
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getBranchWiseDefaultRemarks(Request $request,$division_id=NULL,$department_id=NULL){
		
	global $models;
	$formData = array();
	parse_str($request['data']['formData'],$formData);
//echo'<pre>'; print_r($formData['remark_type']); die;
	$defaultRemarkObj = DB::table('order_report_note_remark_default')
			->join('divisions','divisions.division_id','order_report_note_remark_default.division_id')
			->join('product_categories', 'product_categories.p_category_id', '=', 'order_report_note_remark_default.product_category_id')
			->join('users', 'order_report_note_remark_default.created_by','users.id')
			->select('order_report_note_remark_default.*','divisions.division_name','product_categories.p_category_name','users.name as createdBy');
	
	//Filtering records according to division assigned
	if(!empty($formData['division_id']) && is_numeric($formData['division_id'])){
	    $defaultRemarkObj->where('order_report_note_remark_default.division_id',$formData['division_id']);
	}
	//Filtering records according to department assigned
	if(!empty($formData['product_category_id']) && is_numeric($formData['product_category_id'])){			
	    $defaultRemarkObj->where('order_report_note_remark_default.product_category_id', $formData['product_category_id']);
	}
	//Filtering records according to department assigned
	if(!empty($formData['remark_type']) && is_numeric($formData['remark_type'])){			
	    $defaultRemarkObj->where('order_report_note_remark_default.type', $formData['remark_type']);
	}
	$defaultRemarkObj->orderBy('order_report_note_remark_default.remark_id','DESC');        
	$defaultRemarks = $defaultRemarkObj->get();
	  //echo '<pre>';print_r($defaultRemarks);die;
	//Formating Date Coloums
	$models->formatTimeStampFromArray($defaultRemarks,DATETIMEFORMAT);
	
	return response()->json(['defaultRemarkList' => $defaultRemarks]);
    }
    
    

	
    /** create new remarks
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDefaultRemarks(Request $request)
    {
		$returnData = array(); 
 		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array(); 
				parse_str($request['data']['formData'], $newPostData);   //print_r($newPostData);   die;
				unset($newPostData['_token']);
				if(empty($newPostData['category_id']))
				{
					$returnData = array('error' => config('messages.message.departmentRequired'));
				}else if(empty($newPostData['remark_name'])){
					$returnData = array('error' => config('messages.message.defaultRemarkRequired'));
				}else if(empty($newPostData['remark_type'])){
					$returnData = array('error' => config('messages.message.defaultRemarkTypeRequired'));
				}else if(empty($newPostData['remark_status'])){
					$returnData = array('error' => config('messages.message.defaultRemarkStatusRequired'));
				}
				else{ 
					// check if state already exist or not 
								 $created = OrderReportNoteRemarkDefault::create([
								'division_id' => $newPostData['division_id'],
								'product_category_id' => $newPostData['category_id'],
								'remark_name' => $newPostData['remark_name'],
								'type' =>$newPostData['remark_type'],
								'remark_status' =>$newPostData['remark_status'],
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
     * Show the form for editing the specified remarks from list.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDefaultRemarkData(Request $request){
		
		$returnData = array();
		
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){
				//get user by email id
				$data = DB::table('order_report_note_remark_default')
						->where('order_report_note_remark_default.remark_id', '=', $request['data']['id'])
						->first();
			if($data->remark_id){
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
     * Update the specified remarks in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDefaultRemarksData(Request $request){
		
        $returnData = array();
		
		if ($request->isMethod('post') && !empty($request['data']['formData'])){
				
			//pasrse searlize data 
			$newPostData = array();
			parse_str($request['data']['formData'], $newPostData); //print_r($newPostData);   die;
			
			if(empty($newPostData['category_id'])){
				$returnData = array('error' => config('messages.message.departmentRequired'));
			}else if(empty($newPostData['remark_name'])){
				$returnData = array('error' => config('messages.message.defaultRemarkRequired'));
			}else if(empty($newPostData['remark_type'])){
				$returnData = array('error' => config('messages.message.defaultRemarkTypeRequired'));
			}else if(!isset(($newPostData['remark_status'])) && empty($newPostData['remark_status'])){
				$returnData = array('error' => config('messages.message.defaultRemarkStatusRequired'));
			}else{
				
					if(!empty($newPostData['remark_id'])){
					$updated = DB::table('order_report_note_remark_default')->where('remark_id',$newPostData['remark_id'])->update([
							'division_id' => $newPostData['division_id'],
							'product_category_id' 	=> $newPostData['category_id'],
							'remark_name' 	        => $newPostData['remark_name'],
							'type' 	                => $newPostData['remark_type'],
							'remark_status' 	=> $newPostData['remark_status'],
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
    public function deleteDefaultRemarksData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$defaultRemark = DB::table('order_report_note_remark_default')->where('remark_id', $request['data']['id'])->delete();
					if($defaultRemark){
						$returnData = array('success' => config('messages.message.defaultRemarkDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.defaultRemarkNotDeleted'));					
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
