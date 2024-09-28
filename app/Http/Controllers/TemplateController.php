<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\Method;
use App\Template;

use App\ProductCategory;
use Validator;
use Route;
use DB;

class TemplateController extends Controller
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
	
        global $models,$productCategory,$template;
	
	$models = new Models();
	$template = new Template();
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
    public function index(){
	
	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		
        return view('master.templates.report_templates.index',['title' => 'Reports Template Master','_reports_template_master' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

	
    /** create new detector
     *  Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createReportTemplate(Request $request){
	
	global $template;
	
	$returnData = array();
	
	try{	
	    if ($request->isMethod('post') && !empty($request['data']['formData'])) {
		    
		$newPostData = array();
		parse_str($request['data']['formData'], $newPostData);
		
		if(empty($newPostData['template_type_id'])){
		    $returnData = array('error' => config('messages.message.templatesTypeRequired'));
		}else if(empty($newPostData['division_id'])){
		    $returnData = array('error' => config('messages.message.divisionNameRequired'));
		}else if(empty($newPostData['product_category_id']) && ($newPostData['template_type_id']=='1')){
		    $returnData = array('error' => config('messages.message.productCatNameRequired'));
		}else if(empty($newPostData['header_content']) && in_array($newPostData['template_type_id'],array('1','2','3'))){
		    $returnData = array('error' => config('messages.message.templateHeaderContent'));
		}else if(empty($newPostData['footer_content']) && in_array($newPostData['template_type_id'],array('1','4','5','6','7'))){
		    $returnData = array('error' => config('messages.message.templateFooterContent'));
		}else{
		    unset($newPostData['_token']);
		    $newPostData['template_status_id'] = '1';		    
		    if(empty($template->templateExist($newPostData))){
			$created = DB::table('template_dtl')->insertGetId($newPostData);
			if($created){
			    $returnData = array('success' => config('messages.message.templateSaved'));
			}else{
			    $returnData = array('error' => config('messages.message.templateNotSaved'));
			}    
		    }else{
			$returnData = array('error' => config('messages.message.templatesDetailExist'));
		    }
		}
	    }else{
		$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
	    }
	}catch(\Illuminate\Database\QueryException $ex){
	    $returnData = array('error' => config('messages.message.templatesDetailExist'));
	}
	return response()->json($returnData);		
    }
    /**
     * Get list of companies on page load.
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTemplateList(Request $request){
	global $models;
	$searchArry = '';
	$newPostData=$request['data']['formData'];
	parse_str($newPostData,$searchArry);
	
	$reportTemplateDetailObj = DB::table('template_dtl')
		    ->leftJoin('template_types','template_types.template_type_id','template_dtl.template_type_id')
		    ->leftJoin('divisions','template_dtl.division_id','divisions.division_id')
		    ->leftJoin('product_categories', 'product_categories.p_category_id', '=', 'template_dtl.product_category_id')
		    ->select('template_dtl.*','divisions.division_name','product_categories.p_category_name','template_types.template_type_name');
	if(!empty($searchArry['division_id'])){
	    $reportTemplateDetailObj->where('template_dtl.division_id','=',$searchArry['division_id']);
	}
	if(!empty($searchArry['product_category_id'])){
	    $reportTemplateDetailObj->where('template_dtl.product_category_id','=',$searchArry['product_category_id']);
	}
	if(!empty($searchArry['template_type_id'])){
	    $reportTemplateDetailObj->where('template_types.template_type_id','=',$searchArry['template_type_id']);
	}
	$reportTemplateDetail = $reportTemplateDetailObj->get();
	$models->formatTimeStampFromArray($reportTemplateDetail,DATETIMEFORMAT);
	//echo'<pre>';print_r($reportTemplateDetail); die;
	return response()->json(['reportTemplateDetailList' => $reportTemplateDetail]);
    }   
   
      
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editReportTemplate(Request $request){

	$returnData = array();
	
	if ($request->isMethod('post')) {
	    if(!empty($request['data']['id'])){
		$data =DB::table('template_dtl')
		    ->leftjoin('divisions','template_dtl.division_id','divisions.division_id')
		    ->leftjoin('product_categories', 'product_categories.p_category_id', '=', 'template_dtl.product_category_id')
		    ->select('template_dtl.*','divisions.division_name','product_categories.p_category_name')
		    ->where('template_dtl.template_id','=',$request['data']['id'])->first();
		if($data->template_id){
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateReportTemplate(Request $request){
	global $template;
        $returnData = array();
		
	if ($request->isMethod('post') && !empty($request['data']['formData'])){
			
		//pasrse searlize data 
		$newPostData = array();
		parse_str($request['data']['formData'], $newPostData);
		if(empty($newPostData['template_type_id'])){
		    $returnData = array('error' => config('messages.message.templatesTypeRequired'));
		}else if(empty($newPostData['division_id'])){
		    $returnData = array('error' => config('messages.message.divisionNameRequired'));
		}else if(empty($newPostData['product_category_id']) && ($newPostData['template_type_id']=='1')){
		    $returnData = array('error' => config('messages.message.productCatNameRequired'));
		}else if(empty($newPostData['header_content']) && in_array($newPostData['template_type_id'],array('1','2','3'))){
		    $returnData = array('error' => config('messages.message.templateHeaderContent'));
		}else if(empty($newPostData['footer_content']) && in_array($newPostData['template_type_id'],array('1','4','5','6','7'))){
		    $returnData = array('error' => config('messages.message.templateFooterContent'));
		}else{
		    unset($newPostData['_token']);
		    $newPostData['template_status_id'] = '1';
		    try {
			if(!empty($newPostData['template_id'])){
			    $updated = DB::table('template_dtl')->where('template_id',$newPostData['template_id'])->update($newPostData);
			    if($updated){
				    //check if data updated in Method table 
				    $returnData = array('success' => config('messages.message.templateUpdated'));		
			    }else{
				    $returnData = array('success' => config('messages.message.savedNoChange'));
			    }
			}
		    }catch(\Illuminate\Database\QueryException $ex){ 
		       $returnData = array('error' => config('messages.message.templatesDetailExist'));
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
    public function deleteReportTemplate(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){
				try { 
					$detector = DB::table('template_dtl')->where('template_id', $request['data']['id'])->delete();
					if($detector){
						$returnData = array('success' => config('messages.message.templateDeleted'));
					}else{
						$returnData = array('error' => config('messages.message.templateNotDeleted'));					
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
