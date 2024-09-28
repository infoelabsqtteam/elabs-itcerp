<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\Inquiry;
use App\Followup;
use Validator;
use Route;
use DB;

class InquiryController extends Controller
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
    public function index()
    {
		$user_id            = defined('USERID') ? USERID : '0';
        $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		
        return view('inquiry.index',['title' => 'Inquiries','_inquiry' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]); 
    } 
	
    /**
     * getInquiries used to get the list of inquiry
     * Date : 14-01-17
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
    public function getInquiries(Request $request)
    {
		$inquiry = DB::table('inquiry')
				->leftjoin('inquiry_followups', 'inquiry_followups.inquiry_id', '=', 'inquiry.id')
				->leftjoin('users', 'inquiry_followups.followup_by', '=', 'users.id')
				->join('customer_master', 'inquiry.customer_id', '=', 'customer_master.customer_id')
				->join('users as u','inquiry.created_by','u.id')
				->select('inquiry.*','users.name', 'customer_master.customer_name','u.name as createdBy');
				if(!empty($request['data']['current_status'])){
				   $inquiry->where('inquiry_status', '=',$request['data']['current_status']);
				}
		$res=$inquiry->groupBy('inquiry.id')->orderBy('inquiry_id','desc')->get();
				
		return response()->json([
		   'inquiryList' => $res,
		]);
    } 
	
	/**
     * get previous inquiry data
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
    public function getpreviousInquiriesReport(Request $request)
    {  
		$returnData = array();
		global $models;
		if ($request->isMethod('post')) {
			if(!empty($request['data']['inq_id'])){ 
				$previousData =  DB::table('inquiry_followups')->where('inquiry_id',$request['data']['inq_id'])->orderBy('followup_id','desc')->get();	
				$models->formatTimeStampFromArray($previousData,DATEFORMAT);
				//return the followups json array
				if(count($previousData)>0){
					$returnData = array('responseData' => $previousData->first());					
				}else{
					$returnData = array('error' => config('messages.message.inquiryNotFound'));					
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));			
		}
		return response()->json($returnData);
    } 

    /**
     * createInquiry add new inqury to database
     * Date : 14-01-17
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
	public function createInquiry(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);  
				if(empty($newPostData['customer_id']))
				{
					$returnData = array('error' => config('messages.message.inquiryCustomerRequired'));
				}else if(empty($newPostData['inquiry_date'])){
					$returnData = array('error' => config('messages.message.iniquryDateRequired'));
				}else if(strtotime($newPostData['followup_date']) < strtotime($newPostData['inquiry_date'])){ 
					$returnData = array('error' => config('messages.message.invalidNextFollowDate'));
				}else{
					   $created = Inquiry::create([
						'customer_id' 			=> $newPostData['customer_id'],
						'inquiry_no' 			=> 'ITC'.date('y').date('d').rand(),
						'inquiry_detail' 		=> $newPostData['inquiry_detail'],
						'inquiry_date' 			=> $newPostData['inquiry_date'],
						'next_followup_date' 	=> $newPostData['followup_date'],		
						'created_by' 			=> \Auth::user()->id,		
					   ]);					
					//check if users created add data in user detail
					if($created->id){
						if($newPostData['followup_date'] != ''){
						  $created = Followup::create([
							'inquiry_id' 			=> $created->id,
							'followup_by' 			=> \Auth::user()->id,
							'mode' 					=> 'other',
							'followup_detail' 		=> $newPostData['inquiry_detail'],						
							'next_followup_date' 	=> $newPostData['followup_date'],
							'created_by' 			=> \Auth::user()->id,
						   ]);							
						}
						$returnData = array('success' => config('messages.message.inquiryGenerated'));
					}else{
						$returnData = array('error' => config('messages.message.inquryNotGenerated'));
					}
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
		}
		return response()->json($returnData);		
    }
    /**
     * createInquiryFolloup add new fooloup under inquiry
     * Date : 14-01-17
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
	public function createInquiryFolloup(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData); 
				//print_r($newPostData['followupInquiryId']); die;
				if(empty($newPostData['followupInquiryId']))
				{
					$returnData = array('error' => config('messages.message.inquryIdRequired'));
				}elseif($newPostData['followup_by'] == '' || $newPostData['followup_by'] == 'undefined'){
					$returnData = array('error' => config('messages.message.inquiryFollowupRequired'));
				}elseif($newPostData['mode'] == '' || $newPostData['mode'] == 'undefined'){
					$returnData = array('error' => config('messages.message.inquiryFollowupRequired'));					
				}else{ 
						$inquiry_date=DB::table('inquiry_followups')->where('inquiry_id',$newPostData['followupInquiryId'])->orderBy('followup_id')->first();  
						if(!empty($inquiry_date)){
							$next_followup_date=$inquiry_date->next_followup_date; 	
						}else{						
							$inquiry_date=DB::table('inquiry')->where('id',$newPostData['followupInquiryId'])->orderBy('id')->first(); 	
						    $next_followup_date=$inquiry_date->next_followup_date;
						}
						$data['inquiry_id'] = $newPostData['followupInquiryId'];
						$data['followup_by'] = $newPostData['followup_by'];
						$data['mode'] = $newPostData['mode'];
						$data['followup_detail'] = $newPostData['followup_detail'];
						$data['next_followup_date'] = $newPostData['followup_date'];
						$data['status'] = 'open';							    
						$data['created_by'] = \Auth::user()->id;							    
						if(!empty($next_followup_date) && strtotime($next_followup_date)>=strtotime($data['next_followup_date'])){ 
							$returnData = array('error' => config('messages.message.invalidFollowupDate'));	
						}else{
							$created = DB::table('inquiry_followups')->insertGetId($data);
							//check if users created add data in user detail
							if($created){
								$returnData = array('success' => config('messages.message.inquryFolloupGenerated'));
							}else{
								$returnData = array('error' => config('messages.message.inquryFolloupNotGenerated'));
							}
						}
					
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
		}
		return response()->json($returnData);		
    }
	
		 
	 /*
	 * get Inquirie details to update
	 * Date : 19-frb-2017
	 * Author : nisha
	 * @return \Illuminate\Http\Response
	 */
	public function editInquiry(Request $request)
    {  
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['inquiry_id'])){ 
				$inquiry = DB::table('inquiry')
							->join('customer_master', 'inquiry.customer_id', '=', 'customer_master.customer_id')
							->select('inquiry.*','customer_master.customer_name as customername')
							->where('inquiry.id', '=', $request['data']['inquiry_id'])->first();
				//return the followups json array
				if(count($inquiry)>0){
					$returnData = array('responseData' => $inquiry);					
				}else{
					$returnData = array('error' => config('messages.message.inquiryNotFound'));					
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));			
		}
		return response()->json($returnData);
    }	
	
	
	/*
	 * get Inquirie details to update
	 * Date : 19-frb-2017
	 * Author : nisha
	 * @return \Illuminate\Http\Response
	 */
	public function updateInquiry(Request $request)
    {   
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);  //print_r($newPostData); die;
				$data['inquiry_status']=$newPostData['inquiry_status']; 
				$inquiry = DB::table('inquiry')->where('inquiry.id', '=', $newPostData['id'])->update($data); //print_r($data); 
				//return the followups json array
				    if($data['inquiry_status']=='won' || $data['inquiry_status']=='closed'){
						    $followup['inquiry_id']=$newPostData['id'];
						    $followup['followup_by']=\Auth::user()->id;
						    if($data['inquiry_status']=='closed'){ $followup['followup_detail']='Inquiry Closed.'; }else{ $followup['followup_detail']='Inquiry Won.'; }
						    $followup['mode']='other';
						    $followup['status']=$data['inquiry_status'];
						    $followup['created_by']=\Auth::user()->id;	 
							DB::table('inquiry_followups')->insert($followup);
					}
					$returnData = array('success' => config('messages.message.updated'));								
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));			
		}
		return response()->json($returnData);
    }
	/*
	 * edit Followup details to update
	 * Date : 19-frb-2017
	 * Author : nisha
	 * @return \Illuminate\Http\Response
	 */
	public function editFollowup(Request $request)
    {  
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['followup_id'])){
				$followup = DB::table('inquiry_followups')
							->join('inquiry', 'inquiry_followups.inquiry_id', '=', 'inquiry.id')
							->join('customer_master', 'inquiry.customer_id', '=', 'customer_master.customer_id')
							->join('users', 'inquiry_followups.followup_by', '=', 'users.id')
							->select('inquiry.*','customer_master.customer_name as customername','inquiry_followups.*', 'users.name as employeename')
							->where('inquiry_followups.followup_id', '=', $request['data']['followup_id'])->first();
				//return the followups json array
				if(count($followup)>0){
					$returnData = array('responseData' => $followup);					
				}else{
					$returnData = array('error' => config('messages.message.inquiryFollowupNotFound'));					
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));			
		}
		return response()->json($returnData);
    }	
 	/*
	 * update Followup details to update
	 * Date : 19-frb-2017
	 * Author : nisha
	 * @return \Illuminate\Http\Response
	 */	
	public function updateFollowInquiry(Request $request)
    {   
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){
				//pasrse searlize data 
				$newPostData = array();
				$user_id=\Auth::user()->id;
				parse_str($request['data']['formData'], $newPostData);   
				$data['status']=$newPostData['status'];
				$data['followup_by']=$user_id; 		
				$inquiry = DB::table('inquiry_followups')->where('inquiry_followups.followup_id', '=', $newPostData['followup_id'])->update($data);
				//return the followups json array
                    if($data['status']=='won' || $data['status']=='closed'){
						$inquirydata['inquiry_status']=$data['status'];
						$inquiry = DB::table('inquiry')->where('inquiry.id', '=',$newPostData['inquiry_id'])->update($inquirydata);  
					}					
					$returnData = array('success' => config('messages.message.updated'),'inquiry_status' => $data['status']);							
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));			
		}
		return response()->json($returnData);
    }

    /**
     * getFolloupsByInquiryId
     * Date : 14-01-17
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
    public function getFolloupsByInquiryId(Request $request)
    {
		$returnData = array();
		global $models;
		if ($request->isMethod('post')){
			if(!empty($request['data']['inquiry_id'])){ 
				$followup = DB::table('inquiry_followups')
							->join('inquiry', 'inquiry_followups.inquiry_id', '=', 'inquiry.id')
							->join('customer_master', 'inquiry.customer_id', '=', 'customer_master.customer_id')
							->join('users', 'inquiry_followups.followup_by', '=', 'users.id')
							->leftjoin('users as u','inquiry_followups.created_by','u.id')
							->select('inquiry.*','customer_master.customer_name as customername','inquiry_followups.*', 'users.name as employeename','u.name as createdBy')
							->where('inquiry_followups.inquiry_id', '=', $request['data']['inquiry_id'])
							->orderBy('inquiry_followups.followup_id', 'asc')
							->get();
				$inquiry = DB::table('inquiry')
						->join('customer_master', 'inquiry.customer_id', '=', 'customer_master.customer_id')
						->select('inquiry.*','customer_master.customer_name as customername')
						->where('inquiry.id', '=', $request['data']['inquiry_id'])
						->get();
				$models->formatTimeStampFromArray($followup,DATEFORMAT);
				//return the followups json array
				if(count($followup)>0){
					$returnData = array('folloupsList' => $followup,'inquiryList' => $inquiry);					
				}else{
					$returnData = array('error' => config('messages.message.inquiryFollowupNotFound'));					
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));			
		}
		return response()->json($returnData);
    }
    /**
     * deleteInquiry used to delete inquiry
     * Date : 14-01-17
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
    public function deleteInquiry(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['inquiry_id'])){
				try{
					$folloups = DB::table('inquiry')->where('id', $request['data']['inquiry_id'])->delete();
					if($folloups){
						$returnData = array('success' => config('messages.message.inquiryDeletedSuccessfully'));
					}else{
						$returnData = array('error' => config('messages.message.inquiryDeletedFailled'));					
					}
				}catch(\Illuminate\Database\QueryException $ex){ 
					   $returnData = array('error' => "Cannot delete or update a parent row: a foreign key constraint fails!");
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));			
		}
		return response()->json($returnData);
		
    }

    /**
     * deleteFolloup used to delete folloup of inquiry
     * Date : 14-01-17
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
    public function deleteFolloup(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['followup_id'])){ 
				$folloups = DB::table('inquiry_followups')->where('followup_id', $request['data']['followup_id'])->delete();
				if($folloups){
					$returnData = array('success' => config('messages.message.folloupDeletedSuccessfully'));
				}else{
					$returnData = array('error' => config('messages.message.folloupDeletedFailled'));					
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));			
		}
		return response()->json($returnData);
		
    }

    /**
     * getCustomerList used to fetch the customer list from customer master
     * Date : 14-01-17
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
	public function getCustomerList()
    {
		$customerData = $users = DB::table('customer_master')->get();
		
		return json_encode($customerData);
    }

    /**
     * inquiryReport
     * Date : 26-01-17
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
    public function inquiryReport()
    {
        return view('inquiry.reports');		
    }
    /**
     * getinquiriesReport is used to fetch the all inquiry and fooloup and generate report
     * Date : 26-01-17
	 * Author : nisha
     * @return \Illuminate\Http\Response
     */
    public function getinquiriesReport(Request $request)
    {  
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);    //unserialize data  
				$newPostData=array_filter($newPostData);
                //print_r($newPostData); die;				
				if(empty($newPostData['inquiry_date_from']))
				{
					$returnData = array('error' => config('messages.message.dateFromRequired'));
				}else{
					if(!empty($newPostData['customer_id'])){ 
						$customerArry=array_filter($newPostData['customer_id']);
					}
					if(!empty($newPostData['employee_id'])){ 
						$employeeArry=array_filter($newPostData['employee_id']);
					}
					if(!empty($newPostData['inquiry_date'])){ 
						$inquiry_date=$newPostData['inquiry_date'];
					  }	
					if(!empty($newPostData['status'])){ 
					    $statusArry=array_filter($newPostData['status']); 
					 }	
					if(!empty($newPostData['mode'])){ 
					    $modeArry=array_filter($newPostData['mode']); 
					 }
					$reportData =DB::table('inquiry')
							    ->join('inquiry_followups', 'inquiry.id', '=', 'inquiry_followups.inquiry_id')
							    ->leftjoin('users', 'inquiry_followups.followup_by', '=', 'users.id')
							    ->leftjoin('customer_master', 'inquiry.customer_id', '=', 'customer_master.customer_id')
							    ->select('inquiry.*','inquiry_followups.*','customer_master.customer_name','users.name');
								if(!empty($newPostData['inquiry_date_to'])){
								  $reportData->whereBetween('inquiry_followups.next_followup_date',[$newPostData['inquiry_date_from'], $newPostData['inquiry_date_to']]);
								}else{
								  $reportData->where('inquiry_followups.next_followup_date','=',$newPostData['inquiry_date_from']);
								}
								
								if(!empty($inquiry_date)){
								  $reportData->where('inquiry.inquiry_date','=',$inquiry_date);
								}
								if(!empty($customerArry)){
								  $reportData->whereIn('inquiry.customer_id',$customerArry);
								}
								if(!empty($employeeArry)){
								  $reportData->whereIn('inquiry_followups.followup_by',$employeeArry);
								}
								if(!empty($statusArry)){ 
								  $reportData->whereIn('inquiry.inquiry_status',$statusArry);
								}
								if(!empty($modeArry)){ 
								  $reportData->whereIn('inquiry_followups.mode',$modeArry);
								}
								$sql=$reportData->orderBy('inquiry_followups.next_followup_date', 'asc')->get();
						//return the followups json array
						if(count($sql) >0){
							$returnData = array('reportData' => $sql);					
						}else{
							$returnData = array('error' => config('messages.message.inquiryReportDataNotFound'));					
						}				
				}					
			}else{
				$returnData = array('error' => config('messages.message.inquiryReportDateRequired'));
			}
		}else{
			$returnData = array('error' => config('messages.message.noRecordFound'));			
		}
		return response()->json($returnData);
    }
}
