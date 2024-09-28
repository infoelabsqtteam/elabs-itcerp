<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Company;
use App\Order;
use App\Models;
use App\Report;
use App\ProductCategory;
use Session;
use Validator;
use Route;
use DB;

class ReportsController extends Controller{

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
        global $order,$models,$report,$productCategory;
        $order  = new Order();
        $models = new Models();
        $report = new Report();
	$productCategory = new ProductCategory();

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
    public function index(){

	global $order,$report,$models;

	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

	return view('sales.reports.index',['title' => 'Reports','_reports' => 'active','user_id' => $user_id,'division_id' => $division_id,'role_ids' => $role_ids,'equipment_type_ids' => $equipment_type_ids]);
    }
	/**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getBranchWiseReports(Request $request){

	global $order,$report,$models;

        $error              	= '0';
        $message    	    	= '';
        $data               	= '';
        $formData 	    	= array();
		$user_id            	= defined('USERID') ? USERID : '0';
		$department_ids     	= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_id           	= defined('ROLEID') ? ROLEID : '0';
		$role_ids           	= defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids 	= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		$userWiseRoles 		= $report->getUserRoleIdTaskUncompleted(array($role_id));
		$orderIdTasksCompleted  = $report->getUserOrderIdTaskCompleted($role_ids,$user_id);

	//Assigning Condition according to the Role Assigned
	parse_str($request->formData, $formData);

        $getBranchWiseReportObj = DB::table('order_master')
	    ->leftJoin('invoice_hdr_detail','invoice_hdr_detail.order_id','order_master.order_id')
	    ->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
	    ->join('order_status','order_status.order_status_id','order_master.status')
	    ->leftjoin('order_report_details','order_master.order_id','order_report_details.report_id')
	    ->join('divisions','divisions.division_id','order_master.division_id')
	    ->join('customer_master','customer_master.customer_id','order_master.customer_id')
	    ->leftjoin('city_db','customer_master.customer_city','city_db.city_id')
	    ->leftjoin('customer_billing_types','customer_billing_types.billing_type_id','order_master.billing_type_id')
	    ->join('users as createdBy','createdBy.id','order_master.created_by')
	    ->join('order_sample_priority','order_sample_priority.sample_priority_id','order_master.sample_priority_id')
	    ->leftJoin('order_dispatch_dtl', function($join){
		$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
		$join->where('order_dispatch_dtl.amend_status','0');
	    })
	    ->leftJoin('users as dispatchBy', 'dispatchBy.id', 'order_dispatch_dtl.dispatch_by')
	    ->leftJoin('order_process_log', function($join){
		$join->on('order_process_log.opl_order_id', '=', 'order_master.order_id');
		$join->where('order_process_log.opl_current_stage','1');
		$join->where('order_process_log.opl_amend_status','0');
	    });
		
	$this->setConditionAccordingToRoleAssigned($getBranchWiseReportObj,$userWiseRoles,$formData);
	$this->getReportsMultisearch($getBranchWiseReportObj,$userWiseRoles,$formData);
	$summaryStatistics = $this->summaryStatistics($userWiseRoles);		//summary Statistics of the Users

	$getBranchWiseReportObj->select('order_master.order_no','order_master.product_category_id','order_master.order_id','order_master.order_date','order_report_details.*','order_report_details.report_no','order_master.sample_description_id','order_master.remarks','order_master.expected_due_date','customer_master.customer_name','order_sample_priority.sample_priority_name','order_master.status as order_status','order_status.order_status_name','order_status.color_code','divisions.division_name','createdBy.name as createdByName','order_report_details.report_file_name','product_master_alias.c_product_name as sample_description','customer_billing_types.billing_type_id','customer_billing_types.billing_type','city_db.city_name as customer_city','order_dispatch_dtl.dispatch_id as dispatch_status','order_dispatch_dtl.amend_status','order_dispatch_dtl.dispatch_date','dispatchBy.name as dispatch_by','order_process_log.opl_date as order_status_time','order_master.dept_due_date','order_master.report_due_date','order_master.order_sample_type','invoice_hdr_detail.invoice_dtl_id as invoice_status');
	$getBranchWiseReportObj->groupBy('order_master.order_id');
	$getBranchWiseReportObj->orderBy('order_master.order_date','DESC');
	$getBranchWiseReports = $getBranchWiseReportObj->get();

	//to formate created and updated date
	$models->formatTimeStampFromArray($getBranchWiseReports,DATETIMEFORMAT);

	//echo '<pre>';print_r($getBranchWiseReports);die;
        return response()->json(array('error'=> $error,'message'=> $message,'summaryStatistics'=> $summaryStatistics,'getBranchWiseReports'=> $getBranchWiseReports));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function generateBranchWiseReportPdf(Request $request){
	
	global $order,$report,$models;

        $error    = '0';
        $message  = '';
        $data     = '';
        $formData = array();
	
	if($request->isMethod('post') && !empty($request->generate_report_documents)){
	    
	    $user_id            = defined('USERID') ? USERID : '0';
	    $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	    $role_id           	= defined('ROLEID') ? ROLEID : '0';
	    $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
	    $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	    $userWiseRoles 	= $report->getUserRoleIdTaskUncompleted(array($role_id));
	    $orderIdTasksCompleted = $report->getUserOrderIdTaskCompleted($role_ids,$user_id);
    
	    //Assigning Condition according to the Role Assigned
	    $formData = $request->all();
    
	    $getBranchWiseReportObj = DB::table('order_master')
		->leftJoin('invoice_hdr_detail','invoice_hdr_detail.order_id','order_master.order_id')
		->join('product_master_alias','product_master_alias.c_product_id','order_master.sample_description_id')
		->join('order_status','order_status.order_status_id','order_master.status')
		->leftjoin('order_report_details','order_master.order_id','order_report_details.report_id')
		->join('divisions','divisions.division_id','order_master.division_id')
		->join('customer_master','customer_master.customer_id','order_master.customer_id')
		->leftjoin('city_db','customer_master.customer_city','city_db.city_id')
		->leftjoin('customer_billing_types','customer_billing_types.billing_type_id','order_master.billing_type_id')
		->join('users as createdBy','createdBy.id','order_master.created_by')
		->join('order_sample_priority','order_sample_priority.sample_priority_id','order_master.sample_priority_id')
		->leftJoin('order_dispatch_dtl', function($join){
		    $join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
		    $join->where('order_dispatch_dtl.amend_status','0');
		})
		->leftJoin('users as dispatchBy', 'dispatchBy.id', 'order_dispatch_dtl.dispatch_by')
		->leftJoin('order_process_log', function($join){
		    $join->on('order_process_log.opl_order_id', '=', 'order_master.order_id');
		    $join->where('order_process_log.opl_current_stage','1');
		    $join->where('order_process_log.opl_amend_status','0');
		});
    
	    $this->setConditionAccordingToRoleAssigned($getBranchWiseReportObj,$userWiseRoles,$formData);
	    $this->getReportsMultisearch($getBranchWiseReportObj,$userWiseRoles,$formData);
    
	    $getBranchWiseReportObj->select('order_master.order_no','divisions.division_name as branch','customer_master.customer_name','city_db.city_name as place','customer_billing_types.billing_type','order_master.order_date','order_master.expected_due_date','order_report_details.report_no','order_report_details.report_date','product_master_alias.c_product_name as sample_description','order_sample_priority.sample_priority_name as sample_priority','remarks','order_process_log.opl_date as status_time','order_status.order_status_name as status','createdBy.name as created_by');
	    $getBranchWiseReportObj->groupBy('order_master.order_id');
	    $getBranchWiseReportObj->orderBy('order_master.order_date','DESC');
	    $getBranchWiseReports = $getBranchWiseReportObj->get();
    
	    //to formate created and updated date
	    $models->formatTimeStampFromArrayExcel($getBranchWiseReports,DATEFORMATEXCEL);
	    
	    if(!empty($getBranchWiseReports)){
		
		$getBranchWiseReports 	= !empty($getBranchWiseReports) ? json_decode(json_encode($getBranchWiseReports),true) : array();
		$getBranchWiseReports 	= $models->unsetFormDataVariablesArray($getBranchWiseReports,array('product_category_id','canDispatchOrder'));
		$response['heading'] 	= !empty($getBranchWiseReports) ? 'Total Reports('.count($getBranchWiseReports).')' : 'Total Report';
		$response['tableHead']  = !empty($getBranchWiseReports) ? array_keys(end($getBranchWiseReports)) : array();
		$response['tableBody']  = !empty($getBranchWiseReports) ? $getBranchWiseReports : array();
		$response['tablefoot']	= array();
		
		if($formData['generate_report_documents'] == 'PDF'){
		    $pdfHeaderContent  		= $models->getHeaderFooterTemplate();
		    $response['header_content']	= $pdfHeaderContent->header_content;
		    $response['footer_content']	= $pdfHeaderContent->footer_content;
		    return $models->downloadPDF($response,$contentType='report_pendency');
		}elseif($formData['generate_report_documents'] == 'Excel'){
		    $response['mis_report_name']	= 'report_document';
		    return $models->generateExcel($response);
		}
	    }else{
		Session::put('errorMsg', config('messages.message.noRecordFound'));
		return redirect('dashboard');
	    }
	}
	
	Session::put('errorMsg', config('messages.message.fileDownloadErrorMsg'));
	return redirect('dashboard');
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setConditionAccordingToRoleAssigned($getBranchWiseReportObj,$userWiseRoles,$formData){

	global $order,$report,$models;

	$user_id            	= defined('USERID') ? USERID : '0';
	$department_ids     	= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           	= defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids 	= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	$divisionId     	= !empty($formData['division_id']) ? $formData['division_id'] : '0';
	$statusTypeId   	= !empty($formData['status_id']) ? $formData['status_id'] : '0';
	$orderDateFrom  	= !empty($formData['order_date_from']) ? $formData['order_date_from'] : '0';
	$orderDateTo    	= !empty($formData['order_date_to']) ? $formData['order_date_to'] : '0';
	$expectedDueDateFrom  	= !empty($formData['expected_due_date_from']) ? $formData['expected_due_date_from'] : '0';
	$expectedDueDateTo    	= !empty($formData['expected_due_date_to']) ? $formData['expected_due_date_to'] : '0';
	$keyword        	= !empty($formData['keyword']) ? $formData['keyword'] : '0';
	$searchFromAll        	= !empty($formData['order_search_all']) ? $formData['order_search_all'] : '0';
	$orderDispatchPendency  = !empty($formData['order_search_dispatch_pendency']) ? $formData['order_search_dispatch_pendency'] : '0';

	//cancelled orders not visisble any where
	$getBranchWiseReportObj->whereNotIn('order_master.status',array('10','12'));

	//************gettting records according to roles**************************************
	if(empty($searchFromAll) && !empty($userWiseRoles) && is_array($userWiseRoles)){

	    //For all User Roles
	    $getBranchWiseReportObj->whereIn('order_master.status', $userWiseRoles);

	    if(in_array('3',$userWiseRoles)){	//Tester
		$getBranchWiseReportObj->join('schedulings','schedulings.order_id','order_master.order_id');
		$getBranchWiseReportObj->where('schedulings.employee_id','=',$user_id);
		$getBranchWiseReportObj->where('schedulings.status','=','1');
	    }else if(in_array('4',$userWiseRoles)){	//Section Incharge
		$getBranchWiseReportObj->join('order_incharge_dtl', function($join) use ($user_id) {
		    $join->on('order_incharge_dtl.order_id', '=', 'order_master.order_id');
		    $join->whereNull('order_incharge_dtl.oid_confirm_date');
		    $join->whereNull('order_incharge_dtl.oid_confirm_by');
		    $join->where('order_incharge_dtl.oid_employee_id','=',$user_id);
		});
	    }
	}else{
	    if(in_array('3',$userWiseRoles)){
		$getBranchWiseReportObj->join('schedulings','schedulings.order_id','order_master.order_id');
		$getBranchWiseReportObj->where('schedulings.employee_id','=',$user_id);
	    }else if(in_array('4',$userWiseRoles)){	//Section Incharge
		$getBranchWiseReportObj->join('order_incharge_dtl', function($join) use ($user_id) {
		    $join->on('order_incharge_dtl.order_id', '=', 'order_master.order_id');
		    $join->whereNull('order_incharge_dtl.oid_confirm_date');
		    $join->whereNull('order_incharge_dtl.oid_confirm_by');
		    $join->where('order_incharge_dtl.oid_employee_id','=',$user_id);
		});
	    }
	}
	//************/gettting records according to roles**************************************

	//Filtering records according to department assigned
	if(!empty($department_ids) && is_array($department_ids)){
	    $getBranchWiseReportObj->whereIn('order_master.product_category_id', $department_ids);
	}
	//Filtering records according to division assigned
	if(!empty($divisionId) && is_numeric($divisionId)){
	    $getBranchWiseReportObj->where('order_master.division_id',$divisionId);
	}
	//Filtering records according to from and to order date
	if(!empty($orderDateFrom) && !empty($orderDateTo)){
	    $getBranchWiseReportObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($orderDateFrom, $orderDateTo));
	}else if(!empty($orderDateFrom) && empty($orderDateTo)){
	    $getBranchWiseReportObj->where(DB::raw("DATE(order_master.order_date)"),'>=', $orderDateFrom);
	}else if(empty($orderDateFrom) && !empty($orderDateTo)){
	    $getBranchWiseReportObj->where(DB::raw("DATE(order_master.order_date)"),'<=', $orderDateTo);
	}else{
	    $getBranchWiseReportObj->where(DB::raw("MONTH(order_master.order_date)"), date('m'));
	}
	//Filtering records according to expected due date from and expected due date to
	if(!empty($expectedDueDateFrom) && !empty($expectedDueDateTo)){
	    $getBranchWiseReportObj->whereBetween(DB::raw("DATE(order_master.expected_due_date)"), array($expectedDueDateFrom, $expectedDueDateTo));
	}else if(!empty($expectedDueDateFrom) && empty($expectedDueDateTo)){
	    $getBranchWiseReportObj->where(DB::raw("DATE(order_master.expected_due_date)"),'>=', $expectedDueDateFrom);
	}else if(empty($expectedDueDateFrom) && !empty($expectedDueDateTo)){
	    $getBranchWiseReportObj->where(DB::raw("DATE(order_master.expected_due_date)"),'<=', $expectedDueDateTo);
	}
	//Filtering records according to search keyword
	if(!empty($keyword)){
	    $getBranchWiseReportObj->where('order_master.order_no','=',$keyword);
	}
	//Condition for Checking Pendency Count
	if(!empty($orderDispatchPendency)){
	    $getBranchWiseReportObj->whereNotIn('order_master.order_id',DB::table('order_dispatch_dtl')->pluck('order_id')->all());	   
	}
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getReportsMultisearch($getBranchWiseReportObject,$userWiseRoles,$searchArry){

	global $order,$report,$models;

	if(!empty($searchArry['search_order_no'])){
	    $getBranchWiseReportObject->where('order_master.order_no','LIKE','%'.$searchArry['search_order_no'].'%');
	}
	if(!empty($searchArry['search_division_id'])){
	    $getBranchWiseReportObject->where('divisions.division_name','LIKE','%'.$searchArry['search_division_id'].'%');
	}
	if(!empty($searchArry['search_customer_name']) && !in_array('3',$userWiseRoles)){
	    $getBranchWiseReportObject->where('customer_master.customer_name','LIKE','%'.$searchArry['search_customer_name'].'%');
	}
	if(!empty($searchArry['search_billing_type'])){
	    $getBranchWiseReportObject->where('customer_billing_types.billing_type','LIKE','%'.$searchArry['search_billing_type'].'%');
	}
	if(!empty($searchArry['search_order_date'])){
	    $getBranchWiseReportObject->where('order_master.order_date','LIKE','%'.$models->getFormatedDate($searchArry['search_order_date'],MYSQLDATFORMAT).'%');
	}
	if(!empty($searchArry['search_expected_due_date'])){
	    $getBranchWiseReportObject->where('order_master.expected_due_date','LIKE','%'.$models->getFormatedDate($searchArry['search_expected_due_date'],MYSQLDATFORMAT).'%');
	}
	if(!empty($searchArry['search_dept_due_date'])){
	    $getBranchWiseReportObject->where('order_master.dept_due_date','LIKE','%'.$models->getFormatedDate($searchArry['search_dept_due_date'],MYSQLDATFORMAT).'%');
	}
	if(!empty($searchArry['search_report_no'])){
	    $getBranchWiseReportObject->where('order_report_details.report_no','LIKE','%'.$searchArry['search_report_no'].'%');
	}
	if(!empty($searchArry['search_report_date'])){
	    $getBranchWiseReportObject->where('order_report_details.report_date','LIKE','%'.$models->getFormatedDate($searchArry['search_report_date'],MYSQLDATFORMAT).'%');
	}
	if(!empty($searchArry['search_sample_description'])){
	    $getBranchWiseReportObject->where('product_master_alias.c_product_name','LIKE','%'.$searchArry['search_sample_description'].'%');
	}
	if(!empty($searchArry['search_sample_priority_name'])){
	    $getBranchWiseReportObject->where('order_sample_priority.sample_priority_name','LIKE','%'.$searchArry['search_sample_priority_name'].'%');
	}
	if(!empty($searchArry['search_remarks'])){
	    $getBranchWiseReportObject->where('order_master.remarks','LIKE','%'.$searchArry['search_remarks'].'%');
	}
	if(!empty($searchArry['search_status_time'])){
	    $getBranchWiseReportObject->where('order_process_log.opl_date','LIKE','%'.$models->getFormatedDate($searchArry['search_status_time'],MYSQLDATFORMAT).'%');
	}
	if(!empty($searchArry['search_status'])){
	    $getBranchWiseReportObject->where('order_status.order_status_name','LIKE','%'.$searchArry['search_status'].'%');
	}
	if(!empty($searchArry['search_created_by'])){
	    $getBranchWiseReportObject->where('createdBy.name','LIKE','%'.$searchArry['search_created_by'].'%');
	}
	if(!empty($searchArry['search_dispatch_date'])){
	    $getBranchWiseReportObject->where('order_dispatch_dtl.dispatch_date','LIKE','%'.$models->getFormatedDate($searchArry['search_dispatch_date'],MYSQLDATFORMAT).'%');
	}
	if(!empty($searchArry['search_dispatch_by'])){
	    $getBranchWiseReportObject->where('dispatchBy.name','LIKE','%'.$searchArry['search_dispatch_by'].'%');
	}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function summaryStatistics($userWiseRoles){

	global $order,$report,$models;

	$returnData	    = array();
	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

	if(empty($userWiseRoles)){
	    $userWiseRolesData = DB::table('order_status')
				->join('roles', 'roles.id', '=', 'order_status.role_id')
				->select('order_status.*','roles.name as role_name')
				->where('order_status.status','1')
				->whereNotIn('order_status.order_status_id',array('1','10','11','12'))
				->get();
	    if(!empty($userWiseRolesData)){
		foreach($userWiseRolesData as $value){
		    if($value->order_status_id == '9'){ 		//Dispatcher
			$dispatchingOrderDaily = DB::table('order_master')
						->select('order_master.order_id','order_master.order_no')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('customer_billing_types','customer_billing_types.billing_type_id','order_master.billing_type_id')
						->leftJoin('order_dispatch_dtl', function($join){
						    $join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
						    $join->where('order_dispatch_dtl.amend_status','0');
						})
						->where('order_master.status','<>','10')
						->where('order_master.status',$value->order_status_id)
						->where('order_master.billing_type_id','=','1')
						->whereNull('order_dispatch_dtl.order_id')
						->count();
						
			$dispatchingOrderMonthly = DB::table('order_master')
				    ->join('customer_master','customer_master.customer_id','order_master.customer_id')
				    ->join('customer_billing_types','customer_billing_types.billing_type_id','order_master.billing_type_id')
				    ->leftJoin('order_dispatch_dtl', function($join){
					$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
					$join->where('order_dispatch_dtl.amend_status','0');
				    })
				    ->where('customer_master.billing_type','=','4')
				    ->where('order_master.status',$value->order_status_id)
				    ->whereNull('order_dispatch_dtl.order_id')
				    ->count();

			$returnData[$value->order_status_name.'(Daily)']   = $dispatchingOrderDaily;
			$returnData[$value->order_status_name.'(Monthly)'] = $dispatchingOrderMonthly;
		    }else{
			$returnData[$value->order_status_name] = DB::table('order_master')->where('order_master.status',$value->order_status_id)->where('order_master.status','<>','10')->count();
		    }
		}
	    }
	}else{
	    $userWiseRolesData = DB::table('order_status')
				->join('roles', 'roles.id', '=', 'order_status.role_id')
				->select('order_status.*','roles.name as role_name')
				->where('order_status.status','1')
				->whereNotIn('order_status.order_status_id',array('1','10','11'))
				->whereIn('order_status.order_status_id',$userWiseRoles)
				->get();
	    if(!empty($userWiseRolesData)){
		foreach($userWiseRolesData as $value){
		    if($value->order_status_id == '3'){ 		//For Tester
			$returnData[$value->order_status_name] = count(DB::table('order_master')
				    ->join('schedulings','schedulings.order_id','order_master.order_id')
				    ->where('schedulings.employee_id','=',$user_id)
				    ->where('schedulings.status','<>','3')
				    ->where('order_master.status','<>','10')
				    ->where('order_master.status',$value->order_status_id)
				    ->where('order_master.division_id',$division_id)
				    ->whereIn('order_master.product_category_id',$department_ids)
				    ->groupBy('order_master.order_id')
				    ->get());
		    }else if($value->order_status_id == '9'){ //Dispatcher

			$dispatchingOrderDaily = DB::table('order_master')
						->select('order_master.order_id','order_master.order_no')
						->join('customer_master','customer_master.customer_id','order_master.customer_id')
						->join('customer_billing_types','customer_billing_types.billing_type_id','order_master.billing_type_id')
						->leftJoin('order_dispatch_dtl', function($join){
						    $join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
						    $join->where('order_dispatch_dtl.amend_status','0');
						})
						->where('order_master.billing_type_id','=','1')
						->where('order_master.status','<>','10')
						->where('order_master.status',$value->order_status_id)
						->where('order_master.division_id',$division_id)
						->whereIn('order_master.product_category_id',$department_ids)
						->whereNull('order_dispatch_dtl.order_id')
						->count();
						
			$dispatchingOrderMonthly = DB::table('order_master')
				    ->select('order_master.order_id','order_master.order_no')
				    ->join('customer_master','customer_master.customer_id','order_master.customer_id')
				    ->join('customer_billing_types','customer_billing_types.billing_type_id','order_master.billing_type_id')
				    ->leftJoin('order_dispatch_dtl', function($join){
					$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
					$join->where('order_dispatch_dtl.amend_status','0');
				    })
				    ->where('customer_master.billing_type','=','4')
				    ->where('order_master.status','<>','10')
				    ->where('order_master.status',$value->order_status_id)
				    ->where('order_master.division_id',$division_id)
				    ->whereIn('order_master.product_category_id',$department_ids)
				    ->whereNull('order_dispatch_dtl.order_id')
				    ->count();

			$returnData[$value->order_status_name.'(Daily)']   = $dispatchingOrderDaily;
			$returnData[$value->order_status_name.'(Monthly)'] = $dispatchingOrderMonthly;
		    }else{
			$returnData[$value->order_status_name] = DB::table('order_master')->where('order_master.division_id',$division_id)->whereIn('order_master.product_category_id',$department_ids)->where('order_master.status',$value->order_status_id)->where('order_master.status','<>','10')->count();
		    }
		}
	    }
	}
	return $returnData;
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function viewOrder(Request $request,$order_id){

        global $order,$models,$report;

        $error   = '0';
        $message = '';
        $data    = $hasMicrobiologicalEquipment = $hasPermToSaveTestResult = $hasPermToInvoiceTestResult = '';
        $rawTestProductStdParaList = $hasPermissionToSaveTestResult = $categoryWiseParamenter = $hasPermissionToFinaliseForInvoice = $errorParameterIdsArr = $categoryWiseParamenterArr = $orderHasClaimValueOrNot = array();

        if($order_id){
	    $error              						= '1';
	    $equipment_type_ids 						= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
	    $role_ids           						= defined('ROLE_IDS') ? ROLE_IDS : '0';
	    $user_id            						= defined('USERID') ? USERID : '0';
	    $orderList              					= $order->getOrder($order_id);
	    $orderList->isBackDateBookingAllowed        = !empty($orderList->product_category_id) ? $report->checkBackDateBookingAllowed($orderList) : '0';
	    $orderList->testParametersWithSpace 		= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
	    $orderList->assayParametersWithSpace 		= defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
	    $orderList->assayParametersWithoutSpace 	= defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ","",ASSAY_PARAMETERS)) : '';
	    $orderList->testParametersWithoutSpace 		= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ","", TEST_PARAMETERS)) : '';
	    $orderList->orderAmendStatus 				= !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0' ;
	    $errorParameterIdsArr   					= !empty($orderList)? explode(',',$orderList->error_parameter_ids): array();
	    $testProductStdParaList 					= $order->getOrderParameters($order_id);
	    $orderPerformerRecord						= $order->getOrderPerformerRecord($order_id);
	    $checkReportQuality 						= $report->qualityStampOnWebView($orderList);
	    //to formate order and Report date
	    $models->formatTimeStamp($orderList,DATETIMEFORMAT);

	    if(!empty($testProductStdParaList)){
                foreach($testProductStdParaList as $key => $values){

		    if(!empty($errorParameterIdsArr)){
			if(in_array($values->analysis_id,$errorParameterIdsArr)){
			    $values->errorClass = "errorClass";
			}else{
			    $values->errorClass = "";
			}
		    }

		    //checking if desccription has been edited or not
		    $allowedExceptionParameters = array('description','description(cl:3.2.1)','description(cl:3.2)','reference to protocol');
		    if(!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name),$allowedExceptionParameters)){
			if(!empty($values->test_result) && strtolower($values->test_result) != 'n/a'){
			    $values->description = $values->test_result;
			}
		    }

		    //************Assignuing permission to Add the Parameter result*********
		    $values->has_employee_equipment_type = '0';
		    if(!empty($equipment_type_ids)){
			if(in_array($values->equipment_type_id,$equipment_type_ids)){
			    $values->has_employee_equipment_type = '1';
			}else{
			    $values->has_employee_equipment_type = '0';
			}
		    }else if(defined('IS_ADMIN') && IS_ADMIN){ //Admin has all the permission
			$values->has_employee_equipment_type = '1';
		    }

		    //Checking if enable/disable of save button
		    if(!empty($values->has_employee_equipment_type)){
			$hasPermissionToSaveTestResult[$values->equipment_type_id] = $values->equipment_type_id;
		    }

		    //Checking if all test result performed or not
		    $hasPermissionToFinaliseForInvoice[$values->analysis_id] = $values->test_result;

		    $rawTestProductStdParaList[$values->analysis_id]  = $values;
		    $orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
		    //************/Assignuing permission to Add the Parameter result*********
                }
            }

            if(!empty($rawTestProductStdParaList)){
                foreach($rawTestProductStdParaList as $analysis_id => $values){
                    $models->getRequirementSTDFromTo($values,$values->standard_value_from,$values->standard_value_to);
		    $orderHasClaimValueOrNot[] 					                = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
		    $categoryWiseParameter[$values->test_para_cat_id]['categorySortBy']   	= $values->category_sort_by;
		    $categoryWiseParameter[$values->test_para_cat_id]['categoryId']          	= $values->test_para_cat_id;
                    $categoryWiseParameter[$values->test_para_cat_id]['categoryName']          = $values->test_para_cat_name;
		    $categoryWiseParameter[$values->test_para_cat_id]['productCategoryName']   = str_replace(' ','',strtolower($values->test_para_cat_name));
                    $categoryWiseParameter[$values->test_para_cat_id]['categoryParams'][] 	= $values;
                }
		foreach($categoryWiseParameter as $categoryWiseParameterAll){
		    $charNum = 'a';
		    foreach($categoryWiseParameterAll['categoryParams'] as $values){
			$values->charNumber = $charNum++; 
		    }
		}
	    }
	    
	    $hasMicrobiologicalEquipment = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
            $hasPermToSaveTestResult	 = !empty($hasPermissionToSaveTestResult) ? '1' : '0';
            $hasPermToInvoiceTestResult	 = !empty($hasPermissionToFinaliseForInvoice) && !in_array("",$hasPermissionToFinaliseForInvoice) ? '1' : '0';
	    $orderList->hasClaimValue	 = array_filter($orderHasClaimValueOrNot);
	    $categoryWiseParamenterArr	 = !empty($categoryWiseParameter) ? $models->sortArrayAscOrder(array_values($categoryWiseParameter)) : array();
	}

        return response()->json(['error'=> $error, 'message'=> $message, 'hasMicrobiologicalEquipment' => $hasMicrobiologicalEquipment,'hasPermToSaveTestResult' => $hasPermToSaveTestResult, 'hasPermToInvoiceTestResult' => $hasPermToInvoiceTestResult, 'orderList'=> $orderList, 'orderParameterList' => $categoryWiseParamenterArr, 'orderTrackRecord' => $orderPerformerRecord]);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function viewOrderParameterByTester(Request $request,$order_id){

        global $order,$models,$report;

	$error   = '0';
        $message = '';
        $data    = $hasMicrobiologicalEquipment = $hasPermToSaveTestResult = $hasPermToInvoiceTestResult = '';
        $rawTestProductStdParaList = $hasPermissionToSaveTestResult = $categoryWiseParamenter = $hasPermissionToFinaliseForInvoice = $errorParameterIdsArr = $categoryWiseParamenterArr = $orderHasClaimValueOrNot = array();

        if($order_id){

	    $error              			= '1';
	    $equipment_type_ids 			= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
	    $role_ids           			= defined('ROLE_IDS') ? ROLE_IDS : '0';
	    $user_id            			= defined('USERID') ? USERID : '0';
	    $orderList              			= $order->getOrder($order_id);
	    $orderList->isBackDateBookingAllowed 	= !empty($orderList) ? $report->checkBackDateBookingAllowed($orderList) : '0';
	    $orderList->testParametersWithSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
	    $orderList->assayParametersWithSpace 	= defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
	    $orderList->assayParametersWithoutSpace 	= defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ","",ASSAY_PARAMETERS)) : '';
	    $orderList->testParametersWithoutSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ","", TEST_PARAMETERS)) : '';
	    $orderList->orderAmendStatus 		= !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0' ;
	    $errorParameterIdsArr   			= !empty($orderList)? explode(',',$orderList->error_parameter_ids): array();
	    $testProductStdParaList 			= defined('IS_TESTER') && IS_TESTER ? $order->getAsssignedOrderParameterForTester($order_id,$user_id) : $order->getOrderParameters($order_id);
	    $orderPerformerRecord 			= $order->getOrderPerformerRecord($order_id);

	    //to formate order and Report date
	    $models->formatTimeStamp($orderList,DATETIMEFORMAT);

            if(!empty($testProductStdParaList)){
                foreach($testProductStdParaList as $key => $values){

		    if(!empty($errorParameterIdsArr)){
			if(in_array($values->analysis_id,$errorParameterIdsArr)){
			    $values->errorClass = "errorClass";
			}else{
			    $values->errorClass = "";
			}
		    }

		    //checking if desccription has been edited or not
		    $allowedExceptionParameters = array('description','description(cl:3.2.1)','description(cl:3.2)','reference to protocol');
		    if(!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name),$allowedExceptionParameters)){
			if(!empty($values->test_result) && strtolower($values->test_result) != 'n/a'){
			    $values->description = $values->test_result;
			}
		    }

		    //************Assignuing permission to Add the Parameter result*********
		    $values->has_employee_equipment_type = '0';
		    if(!empty($equipment_type_ids)){
			if(in_array($values->equipment_type_id,$equipment_type_ids)){
			    $values->has_employee_equipment_type = '1';
			}else{
			    $values->has_employee_equipment_type = '0';
			}
		    }else if(defined('IS_ADMIN') && IS_ADMIN){ //Admin has all the permission
			$values->has_employee_equipment_type = '1';
		    }

		    //Checking if enable/disable of save button
		    if(!empty($values->has_employee_equipment_type)){
			$hasPermissionToSaveTestResult[$values->equipment_type_id] = $values->equipment_type_id;
		    }
		    //Checking if all test result performed or not
		    $hasPermissionToFinaliseForInvoice[$values->analysis_id] = $values->test_result;

		    $rawTestProductStdParaList[$values->analysis_id]  = $values;
		    $orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
		    //************/Assignuing permission to Add the Parameter result*********
                }
            }

            if(!empty($rawTestProductStdParaList)){
                foreach($rawTestProductStdParaList as $analysis_id => $values){
                    $models->getRequirementSTDFromTo($values,$values->standard_value_from,$values->standard_value_to);
		    $orderHasClaimValueOrNot[] 					           	= !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
		    $categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']   	= $values->category_sort_by;
		    $categoryWiseParamenter[$values->test_para_cat_id]['categoryId']       	= $values->test_para_cat_id;
                    $categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     	= $values->test_para_cat_name;
		    $categoryWiseParamenter[$values->test_para_cat_id]['productCategoryName'] 	= str_replace(' ','',strtolower($values->test_para_cat_name));
                    $categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] 	= $values;
                }
		foreach($categoryWiseParamenter as $categoryWiseParameterAll){
		    $charNum = 'a';
		    foreach($categoryWiseParameterAll['categoryParams'] as $values){
			$values->charNumber = $charNum++; 
		    }
		}
	    }

	    $hasMicrobiologicalEquipment = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
            $hasPermToSaveTestResult     = !empty($hasPermissionToSaveTestResult) ? '1' : '0';
            $hasPermToInvoiceTestResult  = !empty($hasPermissionToFinaliseForInvoice) && !in_array("",$hasPermissionToFinaliseForInvoice) ? 1 : 0;
	    $orderList->hasClaimValue	 = array_filter($orderHasClaimValueOrNot);
	    $categoryWiseParamenterArr 	 = !empty($categoryWiseParamenter) ? $models->sortArrayAscOrder(array_values($categoryWiseParamenter)) : array();
        }

	//echo'<pre>';print_r($categoryWiseParamenterArr); die;
        return response()->json(['error'=> $error, 'message'=> $message, 'hasMicrobiologicalEquipment' => $hasMicrobiologicalEquipment, 'hasPermToSaveTestResult' => $hasPermToSaveTestResult, 'hasPermToInvoiceTestResult' => $hasPermToInvoiceTestResult, 'orderList'=> $orderList, 'orderParameterList' => $categoryWiseParamenterArr, 'orderTrackRecord' => $orderPerformerRecord]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveOrderTestParametersResult(Request $request){

	global $order,$models,$report;

	$error    = '0';
	$message  = config('messages.message.error');
	$data     = $hasPermToInvoiceTestResult = '';
	$flag     = '0';
	$orderId  = '0';
	$formData = $hasPermissionToFinForInvoice = array();

        //Saving record in orders table
        if(!empty($request->formData) && $request->isMethod('post')){

	    //Unsetting the variable from request data
	    parse_str($request->formData, $formData);
	    $formData 		= array_filter($formData);
	    $formData 		= $models->unsetFormDataVariables($formData,array('_token'));
	    $orderId            = !empty($formData['order_id']) ? $formData['order_id'] : '0';
	    $test_performed_by  = !empty($formData['test_performed_by']) ? $formData['test_performed_by'] : '0';
	    $formData           = !empty($formData['test_result']) ? array_filter($formData['test_result']) : array();

            if(empty($formData)){
                $error   = '0';
                $message = config('messages.message.testParamRequired');
            }else{
                if($test_performed_by){

		    //Case if Equipment type and Method IS NOT NULL
                    foreach($formData as $analysis_id => $test_result){
                        $analysis_id = str_replace("'", "", $analysis_id);
                        if($analysis_id && DB::table('order_parameters_detail')->where('analysis_id',$analysis_id)->update(['test_performed_by' => $test_performed_by,'test_result'=> $test_result])){
			    $flag = 1;
			    $report->updateAnalystJobAssignedStatus($orderId,$analysis_id,$test_performed_by);	//updating the Job Completed Status on 26-Oct-2017
			   
			}
                    }
                }
                //Checking if all order paramenters test has been performed by Tester if Yes,then Changing the Order Status for Reporting ID:4
                if(!empty($orderId)){

		    //Updating Order Status ID:2(SCHEDULING) record
		    $order->updateOrderLog($orderId,'3');

		    $checkOrderStatusUpdated    = DB::table('order_master')->where('order_master.order_id',$orderId)->where('order_master.status','3')->first();
		    $checkUpdateOrderStatus     = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id',$orderId)->whereNull('order_parameters_detail.test_result')->first();
		    $testCompletionDateStatus   = !empty($checkOrderStatusUpdated) && empty($checkUpdateOrderStatus) ? $order->updateTestCompletionDateTime($orderId,CURRENTDATETIME) : '';
		    $orderSectionInchargeDetail = !empty($checkOrderStatusUpdated) && empty($checkUpdateOrderStatus) ? $report->updateOrderSectionInchargeDetail($orderId) : '';
		    $moveToNextStageStatus      = !empty($checkOrderStatusUpdated) && empty($checkUpdateOrderStatus) ? $order->updateOrderStatusToNextPhase($orderId,'4') : '';
                }
                $error	 = '1';
                $message = $flag ? config('messages.message.saved') : config('messages.message.savedNoChange');
            }
        }
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data, 'orderId'=> $orderId));
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveOrderForInvoice($order_id){

	global $order,$models;

        $error       = '0';
        $message     = config('messages.message.error');
        $data        = '';
        $flag        = '0';
        $cureentDate = date('Y-m-d');
	$reportDate  = $order->getFormatedDateTime($cureentDate,$format='Y-m-d');
        $formData    = array();

        if(!empty($order_id)){
            if(DB::table('order_master')->where('order_master.order_id',$order_id)->update(['order_master.status' => '1','order_master.report_date' => $reportDate])){
                $message = config('messages.message.orderMovedForInvoice');
		$error   = 1;
            }
            $orderData   = DB::table('order_master')->where('order_master.order_id',$order_id)->first();
            $division_id = !empty($orderData->division_id) ? $orderData->division_id : '0';
        }
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data, 'division_id' => $division_id));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteReport(Request $request, $order_id){
	
        global $report;
	
	$error    = '0';
        $message  = '';
        $data     = '';

        try{
            $checkInvoiceProcessing = DB::table('order_master')->where('order_master.order_id','=',$order_id)->where('order_master.status','2')->first();
            if(empty($checkInvoiceProcessing) && DB::table('order_master')->where('order_master.order_id','=',$order_id)->where('order_master.status','1')->delete()){
		$report_id=$report->getOrderReportDetails($order_id);
		$error    = '1';
		$message = config('messages.message.reportDeleteMsg');
            }else{
                $message = config('messages.message.reportForeignKeConstFail');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.reportForeignKeConstFail');
        }
	
	return response()->json(['error' => $error,'message' => $message]);
    }

    /**
    * generate final report
    *
    * $Request
    * @return \Illuminate\Http\Response
    */
    public function saveFinalReport(Request $request){

        global $order,$models;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = array();
        $flag     = '0';
        $formData = array();
	
        //Saving record in order_Report_Details table
        if(!empty($request->formData) && $request->isMethod('post')){
	    
	    //Parsing the Serialize Data
            parse_str($request->formData, $formData);
            $formData = array_filter($formData);
            unset($formData['_token']);
	    
	    if(!empty($formData['report_id'])){
		$report_id=DB::table('order_report_details')->where('report_id','=',$formData['report_id'])->first();
		if(!empty($report_id->order_report_id)){
		    $updated=DB::table('order_report_details')->where('order_report_id','=',$report_id->order_report_id)->update($formData);
		    if($updated){
			$message = config('messages.message.reportDetailsUpdated');
			$error   = 1;
		    }else{
			$message = config('messages.message.savedNoChange');
			$error   = 1;
		    }
		}else{
		    if(DB::table('order_report_details')->insert($formData)){
			$message = config('messages.message.reportDetailsSaved');
			$error   = 1;
		    }
		}
	    }
        }
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData));
    }

    /**
    * generate final report pdf
    *
    * $Request
    * @return \Illuminate\Http\Response
    */
    public function uploadReportPdf(Request $request){
	
        global $order,$report,$models;

	$report_path 	= DOC_ROOT.REPORT_PATH;
        $error    	= '0';
        $message  	= config('messages.message.error');
        $data     	= array();
        $flag     	= '0';
        $formData 	= array();
	
        if(!empty($request['report_file'])){
	    
	    $formData = array_filter($request->all());
	    
	    if(!empty($formData['report_id']) && !empty($formData['report_file'])){
		
		$report_id=$report->getOrderReportDetails($formData['report_id']);
		
		if(!empty($report_id->order_report_id)){
		    
		    $updated=DB::table('order_report_details')->where('order_report_id','=',$report_id->order_report_id)->update(['report_file_name'=>$formData['report_file_name']]);
		    
		    if($updated){
			
			$report_file=$formData['report_file'];
	    
			//generate pdf file in public/images/sales/reports folder
			list($type, $report_file) = explode(';', $report_file);
			list(, $report_file) = explode(',', $report_file);
			$report_file = base64_decode($report_file);
	    
			if (!file_exists($report_path)) {
			    mkdir($report_path, 0777, true);
			}
			$pdf = fopen ($report_path.$formData['report_file_name'],'w');
			fwrite ($pdf,$report_file);
			fclose ($pdf);
	    
			$message = config('messages.message.reportGenerated');
			$error   = 1;
		    }
		}
	    }
	}

        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData,'reportPDFurl'=> REPORT_PATH.$formData['report_file_name']));
    }

    /**
     * save report by reporter
     *
     * $Request
     * @return \Illuminate\Http\Response
     */
    public function saveFinalReportByReports(Request $request,$formtype){
	
        global $order,$report,$models;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = array();
        $flag     = '0';
        $formData = array();
	
        //Saving record in order_Report_Details table
        if(!empty($request->formData) && $request->isMethod('post')){

            //Parsing the Serialize Data
	    parse_str($request->formData, $formData);
            $formData = array_filter($formData);
            unset($formData['_token']);

            //product category section wise save form by reporter,1 for food,2 for pharma
	    if(!empty($formData['product_category_id']) && $formData['product_category_id'] == '1' || $formData['product_category_id'] == '4' || $formData['product_category_id'] == '5' || $formData['product_category_id'] == '6' || $formData['product_category_id'] == '7' || $formData['product_category_id'] == '8'){
		
		unset($formData['analysis_id']);
		unset($formData['product_category_id']);
		
		if(empty($formData['ref_sample_value'])){
		    $message = config('messages.message.refSampleValueRequired');
		}else if(empty($formData['result_drived_value'])){
		    $message = config('messages.message.resultDrivedValueRequired');
		}else if(empty($formData['deviation_value'])){
		    $message = config('messages.message.deviationValueRequired');
		}else if(empty($formData['remark_value'])){
		    $message = config('messages.message.remarkValue');
		}else{
		    if(!empty($formData['report_id'])){
			$report_id=$report->getOrderReportDetails($formData['report_id']);
			if(!empty($report_id->order_report_id)){
			    DB::table('order_report_details')->where('order_report_id','=',$report_id->order_report_id)->update($formData);
			    $order->updateOrderStausLog($formData['report_id'],'4');
			    //update order log an dorder master staus
			    if($formtype == 'confirm'){
				$order->updateOrderStatusToNextPhase($formData['report_id'],'5');
			    }
			    $message = config('messages.message.reportDetailsUpdated');
			    $error   = 1;
			}else{
			    if(DB::table('order_report_details')->insert($formData)){
				$message = config('messages.message.reportDetailsSaved');
				$error   = 1;
			    }
			}
		    }
		}
	    }else if(!empty($formData['product_category_id']) && ($formData['product_category_id'] == '2')){
		
		unset($formData['product_category_id']);
		unset($formData['note_by_reviewer']);

		if(empty($formData['report_id'])){
		    $message = config('messages.message.noRecordFound');
		}else if(empty($formData['remark_value'])){
		    $message = config('messages.message.remarkValue');
		}else{
		    if(!empty($formData['report_id'])){
			$report_id=$report->getOrderReportDetails($formData['report_id']);
			if(!empty($report_id->order_report_id)){
			    DB::table('order_report_details')->where('order_report_id','=',$report_id->order_report_id)->update($formData);
			    //update order log an dorder master staus
			    if($formtype=='confirm'){
				$order->updateOrderStausLog($formData['report_id'],5);
			    }
			    $message = config('messages.message.reportDetailsUpdated');
			    $error   = 1;
			}else{
			    if(DB::table('order_report_details')->insert($formData)){
				$message = config('messages.message.reportDetailsSaved');
				$error   = 1;
			    }
			}
		    }
		}
	    }else{
		$message = config('messages.message.undefinedProductSection');
	    }
        }
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData));
    }
    /**
     * save report by reviewer
     *
     * $Request
     * @return \Illuminate\Http\Response
     */
    public function saveFinalReportByReviewer(Request $request,$formtype){

        global $order,$report,$models;

	$error    	    = '0';
	$message  	    = config('messages.message.error');
	$data     	    = array();
	$flag     	    = '0';
	$formData 	    = array();
	$orderData 	    = array();
	$currentDateTime    = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
	$allDepartmentIds   = array(1,3,4,6,7,8,81,148);

	//Saving record in order_Report_Details table
	if(!empty($request->formData) && $request->isMethod('post')){

	    //Parsing the form Data
	    parse_str($request->formData, $formData);
	    $orderData 			  	= !empty($formData['sampleDetails']) ? $formData['sampleDetails'] : array();
	    $formData['note_by_reviewer'] 	= !empty($formData['note_by_reviewer']) ? $formData['note_by_reviewer'] : '';
	    $amendedStatus 		  	= !empty($formData['amended_status']) ? $formData['amended_status'] : '';
	    $productCategoryId 		  	= !empty($formData['product_category_id']) ? $formData['product_category_id'] : '0';
	    $isAmendNo  			= !empty($formData['is_amended_no']) ? true : false;
	    unset($formData['_token']);
	    unset($formData['sampleDetails']);
	    unset($formData['amended_status']);

	    //product category section wise save form by reporter,1 for food,2 for pharma
	    if(!empty($formData['product_category_id']) && in_array($formData['product_category_id'],$allDepartmentIds)){

		unset($formData['product_category_id']);

		if(empty($formData['ref_sample_value'])){
		    $message = config('messages.message.refSampleValueRequired');
		}else if(!empty($orderData) && empty($orderData['sample_description'])){
		    $message = config('messages.message.sampleDescriptionRequired');
		}else if(!empty($orderData) && empty($orderData['batch_no'])){
		    $message = config('messages.message.batchNoRequired');
		}else if(!empty($orderData) && empty($orderData['packing_mode'])){
		    $message = config('messages.message.packingModeRequired');
		}else if(!empty($orderData) && is_null($orderData['is_sealed'])){
		    $message = config('messages.message.isSealedRequired');
		}else if(!empty($orderData) && is_null($orderData['is_signed'])){
		    $message = config('messages.message.isSignedRequired');
		}else if(empty($formData['result_drived_value'])){
		    $message = config('messages.message.resultDrivedValueRequired');
		}else if(empty($formData['deviation_value'])){
		    $message = config('messages.message.deviationValueRequired');
		}else if(empty($formData['remark_value'])){
		    $message = config('messages.message.remarkValue');
		}else{
		    if(!empty($formData['report_id'])){

			//Updating Log Status
			$order->updateOrderStausLog($formData['report_id'],'5');
			
			//Seeting Current As Report Date
			$reportDate = !empty($formData['report_date']) ? $order->getFormatedDateTime($formData['report_date'], $format='Y-m-d') : $currentDateTime;
			//orderData when reviewer update report part A
			if(!empty($orderData)){
			    $orderData['test_standard'] 	 = !empty($formData['test_standard']) ? $formData['test_standard'] : '';
			    $orderData['sample_description_id']  = $order->updateAliaOnEditReportSampleName($formData['report_id'],$orderData['sample_description']);
			    unset($orderData['sample_description']);
			    $orderUpdated = DB::table('order_master')->where('order_id','=',$formData['report_id'])->update($orderData);
			}

			unset($formData['test_standard']);
			unset($formData['note_by_reviewer']);
			unset($formData['report_date']);
			unset($formData['back_report_date']);

			//if report alredy generated then it will upate the report data otherwise it will generate new report
			$reportData = $report->getOrderReportDetails($formData['report_id']);

			if(!empty($reportData->order_report_id)){

			    $formData['with_amendment_no'] = !empty($formData['with_amendment_no']) && (strtolower($formData['with_amendment_no'])!='n/a') ? $formData['with_amendment_no'] : '' ;
			    $updateOrder = DB::table('order_report_details')->where('order_report_id','=',$reportData->order_report_id)->update($formData);
			    
			    //Saving Header and Footer Content of Test Report
			    if($report->updateSaveTestReportHeaderFooterContent($formData['report_id'])){
				//update order log an dorder master staus
				if($formtype == 'confirm' && !empty($reportDate)){

				    if($report->updateMicroBiologicalName($formData,$productCategoryId)){
					$report->updateGenerateReportNumberDate($formData['report_id'],$reportDate);
					$report->updateReportReviewingDate($formData,$reportDate);
					!empty($isAmendNo)? $report->updateGenerateReportNumberDate($formData['report_id'],$reportDate,$reportDate) : '';
					$order->updateOrderStatusToNextPhase($formData['report_id'],'6');					
					$error   = 1;
					$message = config('messages.message.reportDetailsUpdated');
				    }else{
				       $message = config('messages.message.microbiologistNotFoundError');
				    }				
				}else{
				    $message = config('messages.message.reportDetailsUpdated');
				    $error   = 1;
				}
			    }else{
				$message = config('messages.message.reportHeaderFooterErrorMsg');
			    }			    
			}else{
			    if(DB::table('order_report_details')->insert($formData)){
				//Saving Header and Footer Content of Test Report
				if($report->updateSaveTestReportHeaderFooterContent($formData['report_id'])){
				    $message = config('messages.message.reportDetailsSaved');
				    $error   = 1;
				}else{
				    $message = config('messages.message.reportHeaderFooterErrorMsg');
				}
			    }
			}
		    }
		}
	    }else if(!empty($formData['product_category_id']) && $formData['product_category_id'] == '2' || $formData['product_category_id'] == '5'){

		unset($formData['product_category_id']);
		array_filter($formData);

		if(!empty($orderData) && empty($orderData['batch_no'])){
		    $message = config('messages.message.batchNoRequired');
		}else if(isset($formtype) && $formtype == 'confirm' && !empty($formData['back_report_date']) && empty($formData['report_date'])){
		    $message = config('messages.message.reportDateRequiredError');
		}else if(isset($formtype) && $formtype == 'confirm' && !empty($formData['back_report_date']) && !$report->checkOrderDateAndReportDataValidation($formData['report_id'],$formData['report_date'])){
		    $message = config('messages.message.reportDateValidationError');
		}elseif(empty($formData['remark_value'])){
		    $message = config('messages.message.remarkValue');
		}else{
		    if(!empty($formData['report_id'])){
			
			//Getting ReportDate From Blade
			$reportDate     = !empty($formData['report_date']) ? $order->getFormatedDateTime($formData['report_date'], $format='Y-m-d') : $currentDateTime;
			$backReportDate = isset($formData['back_report_date']) ? $formData['back_report_date'] : '0';
			
			//Updating Log Status
			$order->updateOrderStausLog($formData['report_id'],'5',$reportDate);

			//orderData when reviewer update report part A
			if(!empty($orderData)){
			    $orderUpdated = DB::table('order_master')->where('order_id','=',$formData['report_id'])->update($orderData);
			}

			unset($formData['note_by_reviewer']);
			unset($formData['report_date']);
			unset($formData['back_report_date']);

			//if report alredy generated then it will upate the report data otherwise it will generate new report
			$reportData = $report->getOrderReportDetails($formData['report_id']);

			if(!empty($reportData->order_report_id)){

			    //Updating Report Detail Table
			    DB::table('order_report_details')->where('order_report_id','=',$reportData->order_report_id)->update($formData);
			    
			    //Saving Header and Footer Content of Test Report
			    if($report->updateSaveTestReportHeaderFooterContent($formData['report_id'])){
				//update order log an dorder master staus
				if($formtype == 'confirm' && !empty($reportDate)){
				    if($report->updateMicroBiologicalName($formData,$productCategoryId)){
					$report->updateGenerateReportNumberDate($formData['report_id'],$reportDate,$backReportDate);
					$report->updateReportReviewingDate($formData,$reportDate);
					!empty($isAmendNo)? $report->updateGenerateReportNumberDate($formData['report_id'],$reportDate,$reportDate) : '';
					$order->updateOrderStatusToNextPhase($formData['report_id'],'6');
					$error   = 1;
					$message = config('messages.message.reportDetailsUpdated');
				    }else{
				       $message = config('messages.message.microbiologistNotFoundError');
				    }
				}else{
				    $error   = 1;
				    $message = config('messages.message.reportDetailsUpdated');
				}
			    }else{
				$message = config('messages.message.reportHeaderFooterErrorMsg');
			    }  
			}else{
			    if(DB::table('order_report_details')->insert($formData)){				
				//Saving Header and Footer Content of Test Report
				if($report->updateSaveTestReportHeaderFooterContent($formData['report_id'])){
				    $message = config('messages.message.reportDetailsSaved');
				    $error   = 1;
				}else{
				    $message = config('messages.message.reportHeaderFooterErrorMsg');
				}
			    }
			}
		    }
		}
	    }else{
		$message = config('messages.message.undefinedProductSection');
	    }
        }
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData));
    }

    /**
    * update report status and move report to next stage
    *
    * $Request
    * @return \Illuminate\Http\Response
    */
    public function moveOrderToNextStage(Request $request){
	
	global $order,$report,$models;

        $error    	 = '0';
        $message  	 = config('messages.message.error');
        $data     	 = array();
        $flag     	 = '0';
        $formData 	 = array();
	$currentDateTime = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');

        //Saving record in order_Report_Details table
        if(!empty($request->formData) && $request->isMethod('post')){
	    
	    //Parsing the Serialize Data
	    parse_str($request->formData, $formData);
	    $formData = array_filter($formData);
	    unset($formData['_token']);
	    
	    //Seeting Current As Report Date
	    $reportDate = !empty($formData['report_date']) ? $order->getFormatedDateTime($formData['report_date'], $format='Y-m-d') : $currentDateTime;
	    
	    if(!empty($formData['order_id'])){
		
		$order_id 		= $formData['order_id'];
		$orderData 		= $order->getOrder($order_id);
		$orderStatusFinalizer 	= $formData['order_status'];
		$orderStatusQAAprroval 	= $orderStatusFinalizer + 1;
		
		//if report alredy generated then it will upate the report data otherwise it will generate new report
		$report_id = $report->getOrderReportDetails($formData['order_id']);
		$updated   = $order->updateOrderStausLog($order_id,$orderStatusFinalizer,$reportDate);
		
		if($updated){
		    !empty($orderStatusFinalizer) && $orderStatusFinalizer == '6' ? $report->updateReportFinalizingDate($formData,$reportDate) : '';	//Updating Finalizing Date
		    $order->updateOrderStatusToNextPhase($order_id,$orderStatusQAAprroval,$reportDate);
		    $message = config('messages.message.reportDetailsUpdated');
		    $error   = 1;
		}else{
		    $message = config('messages.message.savedNoChange');
		    $error   = 1;
		}
	    }
        }

        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData));
    }
    
    /**
    * need modification in report by QA department and send to reporter (update report status to 5)
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function needReportModification(Request $request){

	global $order,$report,$models;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = array();
        $flag     = '0';
        $formData = array();

        //Saving record in order_Report_Details table
        if(!empty($request->formData) && $request->isMethod('post')){
	    
	    //Parsing the Serialize Data
            parse_str($request->formData, $formData);
            $formData = array_filter($formData);
            unset($formData['_token']);
	    
	    if(!empty($formData['order_id'])){
		
		$order_id 	  = $formData['order_id'];
		$orderData 	  = $order->getOrder($order_id);
		$updatedPrevious  = $order->updateOrderStausLog($order_id,$orderData->status);
		$updatedCurrent	  = $order->updateOrderStausLog($order_id,'5');
		
		if($updatedCurrent){
		    !empty($formData['note']) ? $order->updateOrderStausLogNote($order_id,$formData['note']) : '';	//Updating Notes
		    $message = config('messages.message.reportDetailsUpdated');
		    $error   = 1;
		}else{
		    $message = config('messages.message.savedNoChange');
		    $error   = 1;
		}
	    }
        }
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData));
    }
    
    /**
    * need modification in report by reporter  and update parameter detail table when need modification
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function needReportModificationByReporter(Request $request){

	global $order,$report,$models;

	$error    		= '0';
	$message  		= config('messages.message.error');
	$data     		= array();
	$flag     		= '0';
	$formData 		= array();
	$analysisArr 		= array();
	$error_parameter_ids 	= null;

        //Saving record in order_Report_Details table
        if(!empty($request->formData) && $request->isMethod('post')){
	    
	    //Parsing the Serialize Data
	    parse_str($request->formData, $formData);
	    $formData = array_filter($formData);
	    unset($formData['_token']);

	    if(!empty($formData['report_id'])){

		$order_id 	 	= $formData['report_id'];
		$order_status 	 	= '3';
		$analysisArr 	 	= !empty($formData['analysis_id'])?$formData['analysis_id']:array();
		$orderData 	 	= $order->getOrder($order_id);
		$updatedPrevious 	= $order->updateOrderStausLog($order_id,$orderData->status);
		$updatedCurrent  	= $order->updateOrderStausLog($order_id,$order_status);
		$updatedNote 	 	= $order->updateOrderStausLogNote($order_id,$formData['note_by_reporter']);
		$updatedErrorTestReport = $order->updateOrderStausLogErrorTestReport($analysisArr,$order_id);
		
		if($updatedCurrent && $updatedNote && $updatedErrorTestReport){
		    $message = config('messages.message.reportDetailsUpdated');
		    $error   = 1;
		}else{
		    $message = config('messages.message.savedNoChange');
		    $error   = 1;
		}
	    }
	}
	return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData));
    }
    

    /* Need modification in report by reviewer
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function needReportModificationByReviewer(Request $request){

	global $order,$report,$models;

	$error    = '0';
	$message  = config('messages.message.error');
	$data     = array();
	$flag     = '0';
	$formData = $analysisArr = array();
	$error_parameter_ids = null;

        //Saving record in order_Report_Details table
        if(!empty($request->formData) && $request->isMethod('post')){

            parse_str($request->formData, $formData);
            $formData = array_filter($formData);
            unset($formData['_token']);
	    
	    if(!empty($formData['report_id'])){
		
		$order_id 			= $formData['report_id'];
		$order_status 			= '3';
		$analysisArr 			= !empty($formData['analysis_id'])?$formData['analysis_id']:array();
		$orderData 			= $order->getOrder($order_id);
		$updatedPrevious		= $order->updateOrderStausLog($order_id,$orderData->status);
		$updatedCurrent 		= $order->updateOrderStausLog($order_id,$order_status);
		$updatedNote 			= $order->updateOrderStausLogNote($order_id,$formData['note_by_reviewer']);
		$updatedErrorTestReport 	= $order->updateOrderStausLogErrorTestReport($analysisArr,$order_id);
		$resetOrderInchargeDetails 	= $report->resetOrderInchargeDetail($order_id); 

		if($updatedCurrent && $updatedNote && $updatedErrorTestReport && $resetOrderInchargeDetails){
		    $message = config('messages.message.reportDetailsUpdated');
		    $error   = 1;
		}else{
		    $message = config('messages.message.savedNoChange');
		    $error   = 1;
		}
	    }
        }
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData));
    }

    /* Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function getNoteRemarkReportOptions(Request $request, $product_category_id){

	global $order,$report,$models;

	$error 	  = '0';
	$message  = config('messages.message.error');
	$data 	  = '';
	$formData = $returnData = array();

	if($request->isMethod('get') && !empty($product_category_id)){
	    $orderReportNoteRemarkDefault = DB::table('order_report_note_remark_default')->where('order_report_note_remark_default.product_category_id','=',$product_category_id)->orderBy('order_report_note_remark_default.remark_id','ASC')->get();
	    if(!empty($orderReportNoteRemarkDefault)){
		$error 	  = '1';
		$message  = '';
		foreach($orderReportNoteRemarkDefault as $key => $orderReportNoteRemark){
		    if($orderReportNoteRemark->product_category_id == '2'){  //Case 'Pharma'
			if($orderReportNoteRemark->type == '1'){
			    $returnData['Notes'][] = $orderReportNoteRemark->remark_name;
			}
			if($orderReportNoteRemark->type == '2'){ //Case 'All except Pharma'
			    $returnData['Remarks'][] = $orderReportNoteRemark->remark_name;
			}
		    }else{
			if($orderReportNoteRemark->type == '2'){
			    $returnData['Remarks'][] = $orderReportNoteRemark->remark_name;
			}
			$returnData['NoteWithInput'][] = 'NoteWithInput';
		    }
		}
	    }
	}

	//echo '<pre>';print_r($returnData);die;
	return response()->json(array('error' => $error, 'message' => $message, 'returnData' => $returnData));
    }
    
    /* Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function getDepartmentTestStd(Request $request, $product_category_id){

	global $order,$report,$models;

	$error 	  = '0';
	$message  = config('messages.message.error');
	$data 	  = '';
	$formData = $returnData = array();

	$getAllTestStdAccToDept = Db::table('test_standard')->where('test_standard.product_category_id','=',$product_category_id)->select('test_standard.test_std_id','test_standard.test_std_name')->get();
	if(!empty($getAllTestStdAccToDept)){
	    $error 	= '1';
	    $returnData = $getAllTestStdAccToDept;
	}
	return response()->json(array('error' => $error,'getAllTestStdAccToDept' => $returnData));
    }

    /* Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function amendReport(Request $request, $order_id){

	global $report,$order;

	$error    = '0';
	$message  = '';
	$data     = '';

	if(!empty($order_id)){
	    DB::table('order_process_log')->where('order_process_log.opl_order_id','=',$order_id)->where('order_process_log.opl_order_status_id','>','4')->update(['order_process_log.opl_amended_by' => USERID, 'order_process_log.opl_amend_status'=>'1']);
	    $orderData 		= $order->getOrder($order_id);
	    $order->updateOrderStausLog($order_id,$orderData->status);
	    $order->updateOrderStatusToNextPhase($order_id,'5');
	    $order->updateAmendStatus($order_id,'1');

	    $message = config('messages.message.reportDetailsUpdated');
	    $error   = 1;
	}
	return response()->json(array('error' => $error,'message' => $message));
    }
    
    /* Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function editReport($order_id){
	
	global $report,$order;
	
	$error    = '0';
	$message  = '';
	$data     = '';
	
	if(!empty($order_id)){
	    $editOrder = $order->getOrder($order_id);
	    $error    = '1';
	}
	return response()->json(array('error' => $error,'viewEditReportData' => $editOrder));
    }
    
    /****** dispatch a order from reporting section****/
    public function dispatchReport(Request $request){

	global $models,$order,$report;

	$error    = '0';
	$message  = '';

	if(!empty($request['formData'])){

	    //Parsing of Form Data
	    parse_str($request->formData, $formData);

	    if(!empty($formData['order_id'])){

		$items 			   			= $order->getOrder($formData['order_id']);
		$formData['dispatch_date'] 	= $order->getFormatedDateTime($formData['dispatch_date'], $format='Y-m-d');
		$formData['dispatch_by']   	= USERID;

		//Unsetting the Unrequired fields.
		$formData = $models->unsetFormDataVariables($formData,array('_token'));
		
		//CASE 1:If order is Monthly and Status is greater than > 7
		//CASE 2:If order is Lab/Inter Order and Status is greater than > 7
		//CASE 2:If order is Daily Order and IS amended Order and Status is greater than > 7
		if($models->canUpdateBookingOrderStatus($items)){
		    $order->updateOrderStausLog($items->order_id,'11');
		}else{
		    $order->updateOrderLog($items->order_id,'11');
		}
		if(!empty($items->order_id)){		    
		    $orderDispatchDtl = DB::table('order_dispatch_dtl')->where('order_dispatch_dtl.order_id',$items->order_id)->where('order_dispatch_dtl.amend_status','=','0')->first();
		    if(empty($orderDispatchDtl)){
			DB::table('order_dispatch_dtl')->insertGetId($formData);
			$error   = '1';
			$message = config('messages.message.OrderDispatchedMsg');
		    }else{
			DB::table('order_dispatch_dtl')->where('order_dispatch_dtl.amend_status','=','0')->where('order_dispatch_dtl.order_id',$items->order_id)->update($formData);
			$error   = '1';
			$message = config('messages.message.OrderDispatchedMsg');
		    }		    
		}
	    }
	}
	return response()->json(array('error' => $error,'message' => $message));
    }  
    
    /********************************************************************
    * Description : View dispatch detail for reports and invoices
    * Date        : 13-03-2018
    * Author      : Pratyush Singh
    * Parameter   : \Illuminate\Http\Request  $request
    * @return     : \Illuminate\Http\Response
    ***********************************************************************/
    public function getDispatchDetail(Request $request){

	global $order,$models;

	$error      = '0';
	$message    = config('message.message.error');
	$returnData = array();

	if(!empty($request->formData)){

	    //Parsing of Form Data
	    parse_str($request->formData, $formData);

	    $returnData = DB::table('order_dispatch_dtl')
			->where('order_dispatch_dtl.order_id','=',$formData['order_id'])
			->join('users','order_dispatch_dtl.dispatch_by','users.id')
			->join('order_master','order_dispatch_dtl.order_id','order_master.order_id')
			->select('order_master.order_no','order_dispatch_dtl.ar_bill_no','order_dispatch_dtl.dispatch_date','users.name as dispatched_by')
			->first();
	    
	    //to formate Dispatch date and Time
	    $models->formatTimeStamp($returnData,DATETIMEFORMAT);
	    $error = !empty($returnData) ? 1 : 0;
	}
	
	//echo'<pre>';print_r($returnData); die;
	return response()->json(array('error'=>$error,'message'=>$message,'dispatchDetail' => $returnData));
    }
    
     /** scope-2(04-07-2018)
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function viewOrderBySectionIncharge(Request $request,$order_id){

        global $order,$models,$report;

        $error   = '0';
        $message = '';
        $data    = $hasMicrobiologicalEquipment = $hasPermToSaveTestResult = $hasPermToInvoiceTestResult = '';
        $rawTestProductStdParaList = $hasPermissionToSaveTestResult = $categoryWiseParamenter = $hasPermissionToFinaliseForInvoice = $errorParameterIdsArr = $categoryWiseParamenterArr = $orderHasClaimValueOrNot = array();

        if($order_id){
	    $error              					= '1';
	    $equipment_type_ids 					= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
	    $role_ids           					= defined('ROLE_IDS') ? ROLE_IDS : '0';
	    $user_id            					= defined('USERID') ? USERID : '0';
	    $orderList              				= $order->getOrder($order_id);
	    $orderList->isBackDateBookingAllowed    = !empty($orderList->product_category_id) ? $report->checkBackDateBookingAllowed($orderList) : '0';
	    $orderList->testParametersWithSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? TEST_PARAMETERS : '';
	    $orderList->assayParametersWithSpace 	= defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? ASSAY_PARAMETERS : '';
	    $orderList->assayParametersWithoutSpace = defined('ASSAY_PARAMETERS') && !empty(ASSAY_PARAMETERS) ? strtolower(str_replace(" ","",ASSAY_PARAMETERS)) : '';
	    $orderList->testParametersWithoutSpace 	= defined('TEST_PARAMETERS') && !empty(TEST_PARAMETERS) ? strtolower(str_replace(" ","", TEST_PARAMETERS)) : '';
	    $orderList->orderAmendStatus 			= !empty($order->isBookingOrderAmendedOrNot($order_id)) ? '1' : '0' ;
	    $errorParameterIdsArr   				= !empty($orderList)? explode(',',$orderList->error_parameter_ids): array();
	    $testProductStdParaList 				= defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE ? $order->getAsssignedOrderParameterForSectionIncharge($order_id,$equipment_type_ids) : $order->getOrderParameters($order_id);
	    $orderPerformerRecord					= $order->getOrderPerformerRecord($order_id);
	    $checkReportQuality 					= $report->qualityStampOnWebView($orderList);
		
		// don't show confirm button to section incharge if once he approve report
		$orderList->inchargeStatus				= $report->currentInchargeOrderStatus($order_id,$user_id);
		
		//to formate order and Report date
	    $models->formatTimeStamp($orderList,DATETIMEFORMAT);

	    if(!empty($testProductStdParaList)){
                foreach($testProductStdParaList as $key => $values){

		    if(!empty($errorParameterIdsArr)){
			if(in_array($values->analysis_id,$errorParameterIdsArr)){
			    $values->errorClass = "errorClass";
			}else{
			    $values->errorClass = "";
			}
		    }

		    //checking if desccription has been edited or not
		    $allowedExceptionParameters = array('description','description(cl:3.2.1)','description(cl:3.2)','reference to protocol');
		    if(!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name),$allowedExceptionParameters)){
			if(!empty($values->test_result) && strtolower($values->test_result) != 'n/a'){
			    $values->description = $values->test_result;
			}
		    }

		    //************Assignuing permission to Add the Parameter result*********
		    $values->has_employee_equipment_type = '0';
		    if(!empty($equipment_type_ids)){
			if(in_array($values->equipment_type_id,$equipment_type_ids)){
			    $values->has_employee_equipment_type = '1';
			}else{
			    $values->has_employee_equipment_type = '0';
			}
		    }else if(defined('IS_ADMIN') && IS_ADMIN){ //Admin has all the permission
			$values->has_employee_equipment_type = '1';
		    }

		    //Checking if enable/disable of save button
		    if(!empty($values->has_employee_equipment_type)){
			$hasPermissionToSaveTestResult[$values->equipment_type_id] = $values->equipment_type_id;
		    }

		    //Checking if all test result performed or not
		    $hasPermissionToFinaliseForInvoice[$values->analysis_id] = $values->test_result;

		    $rawTestProductStdParaList[$values->analysis_id]  = $values;
		    $orderEquipmentDetail[$values->equipment_type_id] = !empty($values->equipment_name) ? strtolower($values->equipment_name) : '';
		    //************/Assignuing permission to Add the Parameter result*********
                }
            }

            if(!empty($rawTestProductStdParaList)){
                foreach($rawTestProductStdParaList as $analysis_id => $values){
                    $models->getRequirementSTDFromTo($values,$values->standard_value_from,$values->standard_value_to);
		    $orderHasClaimValueOrNot[] 					                = !empty($values->claim_value) && $values->test_para_cat_name == $orderList->assayParametersWithSpace ? $values->claim_value : '';
		    $categoryWiseParameter[$values->test_para_cat_id]['categorySortBy']   	= $values->category_sort_by;
		    $categoryWiseParameter[$values->test_para_cat_id]['categoryId']          	= $values->test_para_cat_id;
                    $categoryWiseParameter[$values->test_para_cat_id]['categoryName']          = $values->test_para_cat_name;
		    $categoryWiseParameter[$values->test_para_cat_id]['productCategoryName']   = str_replace(' ','',strtolower($values->test_para_cat_name));
                    $categoryWiseParameter[$values->test_para_cat_id]['categoryParams'][] 	= $values;
                }
		foreach($categoryWiseParameter as $categoryWiseParameterAll){
		    $charNum = 'a';
		    foreach($categoryWiseParameterAll['categoryParams'] as $values){
			$values->charNumber = $charNum++; 
		    }
		}
	    }
	    
	    $hasMicrobiologicalEquipment = !empty($orderList->report_microbiological_name) ? $orderList->report_microbiological_name : '0';
            $hasPermToSaveTestResult	 = !empty($hasPermissionToSaveTestResult) ? '1' : '0';
            $hasPermToInvoiceTestResult	 = !empty($hasPermissionToFinaliseForInvoice) && !in_array("",$hasPermissionToFinaliseForInvoice) ? '1' : '0';
	    $orderList->hasClaimValue	 = array_filter($orderHasClaimValueOrNot);
	    $categoryWiseParamenterArr	 = !empty($categoryWiseParameter) ? $models->sortArrayAscOrder(array_values($categoryWiseParameter)) : array();
	}

        return response()->json(['error'=> $error, 'message'=> $message, 'hasMicrobiologicalEquipment' => $hasMicrobiologicalEquipment,'hasPermToSaveTestResult' => $hasPermToSaveTestResult, 'hasPermToInvoiceTestResult' => $hasPermToInvoiceTestResult, 'orderList'=> $orderList, 'orderParameterList' => $categoryWiseParamenterArr, 'orderTrackRecord' => $orderPerformerRecord]);
    }
	
    
    	/**
     * save report by reporter
     *
     * $Request
     * @return \Illuminate\Http\Response
     */
    public function saveFinalReportBySectionIncharge(Request $request,$formtype){
	
        global $order,$report,$models;

        $error    		= '0';
        $message  		= config('messages.message.error');
        $data     		= array();
        $flag     		= '0';
        $formData 		= array();
	$allDepartmentIds   	= array(1,2,3,4,6,7,8,81,148);
	$user_id            	= defined('USERID') ? USERID : '0';

        //Saving record in order_Report_Details table
        if(!empty($request->formData) && $request->isMethod('post')){

            //Parsing the Serialize Data
	    parse_str($request->formData, $formData);
            $formData = array_filter($formData);
            unset($formData['_token']);
	    
	    if(!empty($formData['product_category_id']) && !empty($formData['report_id'])){
		
			if($formtype == 'confirm'){
				
				//Updating Log Status
				$order->updateOrderLog($formData['report_id'],'4');
				
				//Update order_incharge_dtl status and confirm date
				//0 if order already moved to testing Order Stage
				//1 if atleast any one Section Incharge doesnot confirm the report
				//2 if all Section Incharge confirm the report
				$inchageStatus = $report->updateSectionInchargeStatus($formData['report_id'],$user_id);
				if(!empty($inchageStatus)){
				if($inchageStatus == '1'){
					$error   = 1;
					$message = config('messages.message.reportDetailsUpdated');
				}else{
					$error   = 1;
					$report->updateReportInchargeReviewingDate($formData['report_id']);
					$order->updateOrderStatusToNextPhase($formData['report_id'],'5');
					$message = config('messages.message.reportDetailsUpdated');			    
				}			
				}else{
				$message = config('messages.message.reportStageMessage');
				$error   = 0;
				}
			}				
	    }else{
		$message = config('messages.message.undefinedProductSection');
	    }
        }
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData));
    }
    
    /**
    * need modification in report by reporter  and update parameter detail table when need modification
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function needReportModificationBySectionIncharge(Request $request){

	global $order,$report,$models;

	$error    		= '0';
	$message  		= config('messages.message.error');
	$data     		= array();
	$flag     		= '0';
	$formData 		= array();
	$analysisArr 		= array();
	$error_parameter_ids 	= null;

        //Saving record in order_Report_Details table
        if(!empty($request->formData) && $request->isMethod('post')){
	    
	    //Parsing the Serialize Data
	    parse_str($request->formData, $formData);
	    $formData = array_filter($formData);
	    unset($formData['_token']);

	    if(!empty($formData['report_id'])){

		$order_id 	 		= $formData['report_id'];
		$order_status 	 		= '3';
		$analysisArr 	 		= !empty($formData['analysis_id'])?$formData['analysis_id']:array();
		$orderData 	 		= $order->getOrder($order_id);
		$updatedPrevious 		= $order->updateOrderStausLog($order_id,$orderData->status);
		$updatedCurrent  		= $order->updateOrderStausLog($order_id,$order_status);
		$updatedNote 	 		= $order->updateOrderStausLogNote($order_id,$formData['note_by_reporter']);
		$updatedErrorTestReport 	= $order->updateOrderStausLogErrorTestReport($analysisArr,$order_id);
		$resetOrderInchargeDetails 	= $report->resetOrderInchargeDetail($order_id); 
		
		if($updatedCurrent && $updatedNote && $updatedErrorTestReport && $resetOrderInchargeDetails){
		    $message = config('messages.message.reportDetailsUpdated');
		    $error   = 1;
		}else{
		    $message = config('messages.message.savedNoChange');
		    $error   = 1;
		}
	    }
	}
	return response()->json(array('error'=> $error,'message'=> $message,'data'=> $formData));
    }
}
