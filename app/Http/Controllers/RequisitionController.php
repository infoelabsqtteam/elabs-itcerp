<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\User;
use App\Requisition;
use Validator;
use Route;
use DB;

class RequisitionController extends Controller
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
        global $models,$requisition;
		$models = new Models();
		$requisition = new Requisition();
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
		
        return view('inventory.requisition_slip.index',['title' => 'Requisition','_requisition' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    } 
	
	//auto generate requisition number
	public function getAutoGenCode($sectionName)
    {  
	   global $models;
	   $number=$models->getAutoGenCode($sectionName);
	   return response()->json(['RequisitionNumber'=>$number]);	
    }
	
	public function get_MRS_inputs()
    {  
	   return view('inventory.requisition_slip.MRS_inputs');
    }

    /** create new Requisition
     *  Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function createRequisition(Request $request)
    {
		global $requisition;
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['formData'])){  
				//pasrse searlize data 
				$newPostData = array();
				$data = array();
				parse_str($request['data']['formData'], $newPostData);
				unset($newPostData['req_slip_dlt_id']);  	
				$itemArray=array_filter($newPostData['item_id']); 
				$requiredQtyArray=array_filter($newPostData['required_qty']); 
				if(count($itemArray)!=count($requiredQtyArray))	
				{
					$returnData = array('error' => config('messages.message.required'));
				}else{
						unset($newPostData['_token']); 
						unset($newPostData['item_id']); 
						unset($newPostData['required_qty']); 
						$reqHdrArray=$newPostData;  
						$reqHdrArray['created_by']=\Auth::user()->id;  
						if(empty($newPostData['req_slip_date'])){
							$returnData = array('error' => config('messages.message.reqSlipDateRequired'));
						}else if(empty($newPostData['req_department_id'])){
							$returnData = array('error' => config('messages.message.reqDepartmentIdRequired'));
						}else if(empty($newPostData['req_by'])){
							$returnData = array('error' => config('messages.message.reqByRequired'));
						}else if(empty($newPostData['division_id'])){
							$returnData = array('error' => config('messages.message.divisionRequired'));
						}else{ 				  
								$createdMRS =DB::table('req_slip_hdr')->insertGetId($reqHdrArray); 
								//if MRS generated save details to details table
								if(!empty($createdMRS)){ 
										foreach($itemArray as $key=>$value){
										 if(!empty($value)){
											$MRSdetailArray['req_slip_hdr_id']=$createdMRS;
											$MRSdetailArray['item_id']=$requisition->getItemId($value);
											$MRSdetailArray['required_qty']=$requiredQtyArray[$key];
											$MRSdetails=DB::table('req_slip_dtl')->insertGetId($MRSdetailArray);
											if(!empty($MRSdetails)){
												$data['saved'][]=$value;
											}else{
												$data['notSavedItem'][]=$value;
											}
										 }
									} 
									if(!empty($MRSdetails)){
									   $returnData = array('success' => config('messages.message.reqSaved'));	
									}else{
									  $returnData = array('success' => config('messages.message.error'));
									}
								}else{
									$returnData = array('error' => config('messages.message.MRSNotGenerated'));
								}
						}
				}				
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
			return response()->json(['returnData'=>$returnData,'data'=>$data]);		
		}
	}
	
    /**
     * Get list of requisitions on page load.
     * Date : 01-03-17
	 * Author : nisha
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRequisitionsList($division_id)
    {   
		global $models;
		$req = DB::table('req_slip_hdr')
					->join('req_slip_dtl','req_slip_hdr.req_slip_id','=','req_slip_dtl.req_slip_hdr_id')
					->leftjoin('departments','req_slip_hdr.req_department_id','=','departments.department_id')
					->leftjoin('divisions','req_slip_hdr.division_id','=','divisions.division_id')
					->leftjoin('users','req_slip_hdr.req_by','=','users.id')
					->leftjoin('item_master','req_slip_dtl.item_id','=','item_master.item_id')
					->join('users as u','req_slip_hdr.created_by','u.id')	
					->select('req_slip_hdr.*','divisions.division_name','req_slip_dtl.req_slip_dlt_id','req_slip_dtl.required_qty','item_master.item_code','item_master.item_name','departments.department_name','users.name','u.name as createdBy');
					if($division_id && is_numeric($division_id))
					{					
					   $req=$req->where('req_slip_hdr.division_id','=',$division_id);
					}
		$requisitions=$req->groupBy('req_slip_dtl.req_slip_hdr_id')->get(); 	
		$models->formatTimeStampFromArray($requisitions,DATETIMEFORMAT);
		return response()->json([
		   'requisitionsList' => $requisitions,
		]);
    } 
	
	
	public function getItemDesc($item_id)
    {
		$res = DB::table('item_master')->select('item_master.item_description')->where('item_id','=',$item_id)->first(); 	
		if(!empty($res))
		{
			$desc=$res->item_description;
		}else{
			$desc="Description not available!";
		}
		return response()->json([
		   'itemDesc' => $desc,
		]);
    }    
	   

	//Get list of methods list
	public function getdepartmentList()
	{
		$departmentList= DB::table('departments')
						->select('department_id as req_department_id','department_name as name')
						->get(); 	
		return response()->json(['departmentList'=> $departmentList]);
	}	   
	   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editRequisitionData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')) {
			if(!empty($request['data']['id'])){ 
				//get user by email id
				$reqhdr = DB::table('req_slip_hdr')
							->leftjoin('departments','req_slip_hdr.req_department_id','=','departments.department_id')
							->leftjoin('divisions','req_slip_hdr.division_id','=','divisions.division_id')
							->leftjoin('users','req_slip_hdr.req_by','=','users.id')
							->select('req_slip_hdr.*','divisions.division_name','departments.department_name','users.name','users.id as user_id')
							->where('req_slip_hdr.req_slip_id','=',$request['data']['id'])
							->first();
				if(!empty($reqhdr)){
					$reqDtl = DB::table('req_slip_hdr')
								->join('req_slip_dtl','req_slip_hdr.req_slip_id','=','req_slip_dtl.req_slip_hdr_id')
								->leftjoin('departments','req_slip_hdr.req_department_id','=','departments.department_id')
								->leftjoin('divisions','req_slip_hdr.division_id','=','divisions.division_id')
								->leftjoin('users','req_slip_hdr.req_by','=','users.id')
								->leftjoin('item_master','req_slip_dtl.item_id','=','item_master.item_id')
								->select('req_slip_dtl.*','item_master.item_id','item_master.item_name','item_master.item_code','item_master.item_description')
								->where('req_slip_dtl.req_slip_hdr_id','=',$request['data']['id'])
								->get(); 
				}			    
				if(!empty($reqhdr) && !empty($reqDtl)){
					$returnData = array('reqhdr' => $reqhdr,'reqDtl' => $reqDtl);				
				}else{
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			}else{
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		}else{
				$returnData = array('error' => config('messages.message.provideAppData'));			
		}
		return response()->json(['returnData'=>$returnData]);	
    }
	
    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRequisitionData(Request $request)
    {
		global $requisition;
        $returnData = array();
		if ($request->isMethod('post')) { 
			if(!empty($request['data']['formData'])){   
				//pasrse searlize data 
				$newPostData = array();
				parse_str($request['data']['formData'], $newPostData);				
				unset($newPostData['req_slip_date']); 		
				$itemArray=array_filter($newPostData['item_id']); 
				$requiredQtyArray=array_filter($newPostData['required_qty']); 
				$reqSlipIdArray=$newPostData['req_slip_dlt_id']; 
				if(count($itemArray)!=count($requiredQtyArray)){
					$returnData = array('error' => config('messages.message.required'));
				}else{
					unset($newPostData['_token']); 
					unset($newPostData['item_id']); 
					unset($newPostData['required_qty']); 
					unset($newPostData['req_slip_dlt_id']);
					
					$reqHdrArray=$newPostData;   
					if(empty($newPostData['req_department_id'])){
						$returnData = array('error' => config('messages.message.reqDepartmentIdRequired'));
					}else if(empty($newPostData['req_by'])){
						$returnData = array('error' => config('messages.message.reqByRequired'));
					}else if(empty($newPostData['division_id'])){
						$returnData = array('error' => config('messages.message.divisionRequired'));
					}else{ 				  
						$updatedMRS =DB::table('req_slip_hdr')->where('req_slip_id','=',$reqHdrArray['req_slip_id'])->update($reqHdrArray); 
						//if MRS generated save details to details table
						foreach($itemArray as $key=>$value){
							if(!empty($value)){
								$MRSdetailArray = array();
								$MRSdetailArray['req_slip_hdr_id']	=	$reqHdrArray['req_slip_id'];
								$MRSdetailArray['req_slip_dlt_id']	=	$reqSlipIdArray[$key];
								$MRSdetailArray['item_id']			=	$requisition->getItemId($value);
								$MRSdetailArray['required_qty']		=	$requiredQtyArray[$key];
								if(!empty($MRSdetailArray['req_slip_dlt_id'])){
									$MRSupdatedDetails=DB::table('req_slip_dtl')->where('req_slip_dlt_id','=',$MRSdetailArray['req_slip_dlt_id'])->update($MRSdetailArray);	
								}else{
									$MRSupdatedDetails=DB::table('req_slip_dtl')->insert($MRSdetailArray);			
								}	
							}								
						}						
						$returnData = array('success' => config('messages.message.reqUpdated'));							
					}
				}	
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteRequisitionData(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){ 
				   try { 
						$requisition = DB::table('req_slip_hdr')->where('req_slip_id', $request['data']['id'])->delete();
						if($requisition){
							$returnData = array('success' => config('messages.message.deleted'));
						}else{
							$returnData = array('error' => config('messages.message.deletedError'));					
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
	
	/**
     *remove data from req_slip_dlt table by  req_slip_dlt_id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteRequisitionDetail(Request $request)
    {
		$returnData = array();
		if ($request->isMethod('post')){
			if(!empty($request['data']['id'])){ 
				   try { 
						$requisition = DB::table('req_slip_dtl')->where('req_slip_dlt_id', $request['data']['id'])->delete();
						if($requisition){
							$returnData = array('success' => config('messages.message.deleted'));
						}else{
							$returnData = array('error' => config('messages.message.deletedError'));					
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
	
}
