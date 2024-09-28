<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Company;
use App\IgnHdr;
use App\Models;
use App\Item;
use App\Setting;
use Session;
use Validator;
use Route;
use DB;

class IgnsController extends Controller
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
        global $ignHdr,$models,$item;
        $ignHdr = new IgnHdr();
        $models = new Models();
		$item = new Item();
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
		
        return view('inventory.ign.index',['title' => 'IGNs','_igns' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function generateIgnNumber()
    {
        global $models,$ignHdr;
        
        $error   = '0';
        $message = '';
        $data    = '';
        
        $ignNumber = $ignHdr->generateIgnNo('IGN');        
        return response()->json(array('error'=> $error,'message'=> $message,'ignNumber'=> $ignNumber));
    }
	
	/**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function add_ign_inputs()
    {
        $error   = '0';
        $message = config('messages.message.error');
        $data    = '';
        
        return view('inventory.ign.add_ign_inputs');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDivisionWiseIGNList($division_id)
    {
        global $models,$ignHdr;
		
		$IGNDataObj = DB::table('ign_hdr')
                    ->join('divisions','divisions.division_id','ign_hdr.division_id')
                    ->join('vendors','vendors.vendor_id','ign_hdr.vendor_id')
		            ->select('ign_hdr.*','divisions.division_name','vendors.vendor_name');
				   
		if(!empty($division_id) && is_numeric($division_id)){
			$IGNDataObj->where('ign_hdr.division_id',$division_id);
		}
		
		$IGNDataList = $IGNDataObj->orderBy('ign_hdr.ign_hdr_id','DESC')->get();	
		
		//to formate created and updated date		   
		$models->formatTimeStampFromArray($IGNDataList,DATETIMEFORMAT);    
		
		return response()->json(['IGNDataList' => $IGNDataList]);		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveIGNRecord(Request $request)
    {
        global $models,$ignHdr;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();	
        
        //Saving record in orders table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
            $user_id = \Auth::user()->id;;
            parse_str($request->formData, $formData);
                                    
            if(!empty($formData)){                
                $formData = array_filter($formData);
                if(empty($formData['ign_no'])){
                    $message = config('messages.message.ignNoRequiredMsg');
                }else if(empty($formData['ign_date'])){
                    $message = config('messages.message.ignDateRequiredMsg');
                }else if(empty($formData['division_id'])){
                    $message = config('messages.message.divisionRequiredMsg');
                }else if(empty($formData['vendor_id'])){
                    $message = config('messages.message.vendorRequiredMsg');
                }else if(empty($formData['vendor_bill_no'])){
                    $message = config('messages.message.vendorBillNoRequiredMsg');
				}else if($ignHdr->checkVendorBillNumber($formData['vendor_bill_no'])){
					$message = config('messages.message.vendorBillNoExistMsg');
                }else if(empty($formData['vendor_bill_date'])){
                    $message = config('messages.message.vendorBillDateRequiredMsg');
                }else if(empty($formData['gate_pass_no'])){
                    $message = config('messages.message.gatePassNoRequiredMsg');				
				}else if($ignHdr->checkGatePassNumber($formData['gate_pass_no'])){
					$message = config('messages.message.gatePassNoExistMsg');
                }else if(empty($formData['employee_id'])){
                    $message = config('messages.message.employeeRequiredMsg');
                }else if(empty($formData['employee_detail'])){
                    $message = config('messages.message.employeeDetailRequiredMsg');					
				}else if(array_search("", $formData['item_id']) !== false){
                    $message = config('messages.message.itemNameErrorMsg');
				}else if(array_search("", $formData['expiry_date']) !== false){
                    $message = config('messages.message.expiryDateErrorMsg');
				}else if(array_search("", $formData['bill_qty']) !== false){
                    $message = config('messages.message.billQtyErrorMsg');
				}else if(array_search("", $formData['received_qty']) !== false){
                    $message = config('messages.message.receivedQtyErrorMsg');
				}else if(array_search("", $formData['ok_qty']) !== false){
                    $message = config('messages.message.okQtyErrorMsg');
				}else if(array_search("", $formData['bill_rate']) !== false){
                    $message = config('messages.message.billRateErrorMsg');
				}else if(array_search("", $formData['pass_rate']) !== false){
                    $message = config('messages.message.passRateErrorMsg');
				}else if(array_search("", $formData['landing_cost']) !== false){
                    $message = config('messages.message.landingCostErrorMsg');
				}else if(empty($formData['total_bill_amount'])){
                    $message = config('messages.message.totalBillAmountErrorMsg');
				}else if(empty($formData['total_pass_amount'])){
                    $message = config('messages.message.totalPassAmountErrorMsg');
                }else{
					
					//Draft PO Header detail
					$requestData = array(
						'item_id' 		=> $formData['item_id'],
						'po_hdr_id'     => $formData['po_hdr_id'],
						'expiry_date' 	=> $formData['expiry_date'],
						'bill_qty' 		=> $formData['bill_qty'],
						'received_qty' 	=> $formData['received_qty'],
						'ok_qty' 		=> $formData['ok_qty'],
						'bill_rate' 	=> $formData['bill_rate'],
						'pass_rate' 	=> $formData['pass_rate'],
						'landing_cost' 	=> $formData['landing_cost']										 
					);
					
					$formData = $models->unsetFormDataVariables($formData,array('_token','item_id','po_hdr_id','expiry_date','bill_qty','received_qty','ok_qty','bill_rate','pass_rate','landing_cost'));                        
                    $formData['created_by'] = $user_id;					
					//echo '<pre>';print_r($formData);die;
                    
                    if(!empty($formData['ign_no'])){
                        $ignHdrId = DB::table('ign_hdr')->insertGetId($formData);
                        if(!empty($ignHdrId) && $this->save_ign_hdr_detail($requestData,$ignHdrId)){
                            $error   = '1';
                            $message = config('messages.message.success');                                          
                        }
                    }                   
                }                
            }
        }        
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data));	
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function save_ign_hdr_detail($requestData, $ignHdrId)
    {
        global $models,$item;        
        $dataSave = array();
        
        if(!empty($requestData['item_id']) && !empty($ignHdrId)){
            foreach($requestData['item_id'] as $key => $itemCode){
                $dataSave[$key]['ign_hdr_id']   = $ignHdrId;
                $dataSave[$key]['item_id']      = $item->getItemId($itemCode);
				$dataSave[$key]['po_hdr_id']    = !empty($requestData['po_hdr_id'][$key]) ? $requestData['po_hdr_id'][$key] : null;								
                $dataSave[$key]['expiry_date']  = !empty($requestData['expiry_date'][$key]) ? $requestData['expiry_date'][$key] : null;
				$dataSave[$key]['bill_qty']     = !empty($requestData['bill_qty'][$key]) ? $requestData['bill_qty'][$key] : '0';
				$dataSave[$key]['received_qty'] = !empty($requestData['received_qty'][$key]) ? $requestData['received_qty'][$key] : '0';
				$dataSave[$key]['ok_qty']       = !empty($requestData['ok_qty'][$key]) ? $requestData['ok_qty'][$key] : '0';
				$dataSave[$key]['bill_rate']    = !empty($requestData['bill_rate'][$key]) ? $requestData['bill_rate'][$key] : '0';				
                $dataSave[$key]['pass_rate']    = !empty($requestData['pass_rate'][$key]) ? $requestData['pass_rate'][$key] : '0';
                $dataSave[$key]['landing_cost'] = !empty($requestData['landing_cost'][$key]) ? $requestData['landing_cost'][$key] : '0';
			}                
            //echo '<pre>';print_r($dataSave);die;                
            if(!empty($dataSave) && DB::table('ign_hdr_dtl')->insert($dataSave)){
                return true;
            }        
        }
        return false;
    }
	
	/**
    *  Display the specified resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function viewIGNDetail($ign_hdr_id)
    {
        global $models,$ignHdr;
         
        $error    = '0';
        $message  = '';
        $data     = '';
        
        $ignHeaderData       = $ignHdr->getIGNHeader($ign_hdr_id);
        $ignHeaderDetailData = $ignHdr->getIGNHeaderDetail($ign_hdr_id);        
        $error               = !empty($ignHeaderData) && !empty($ignHeaderDetailData) ? '1' : '0';
        
        //echo '<pre>';print_r($ignHeaderData);print_r($ignHeaderDetailData);die;
        return response()->json(array('error'=> $error,'message'=> $message, 'ignHeaderData'=> $ignHeaderData, 'ignHeaderDetailData'=> $ignHeaderDetailData));
    }
	
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateIGNRecord(Request $request)
    {
        global $models,$ignHdr;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = $requestData = array();	
        
        //Updating record in orders table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
            $user_id = \Auth::user()->id;;
            parse_str($request->formData, $formData);
                                    
            if(!empty($formData)){                
				$ign_hdr_id = !empty($formData['ign_hdr_id']) ? $formData['ign_hdr_id'] : '0';
                if(empty($formData['ign_date'])){
                    $message = config('messages.message.ignDateRequiredMsg');
                }else if(empty($formData['division_id'])){
                    $message = config('messages.message.divisionRequiredMsg');
                }else if(empty($formData['vendor_id'])){
                    $message = config('messages.message.vendorRequiredMsg');
                }else if(empty($formData['vendor_bill_no'])){
                    $message = config('messages.message.vendorBillNoRequiredMsg');
				}else if($ignHdr->checkVendorBillNumber($formData['vendor_bill_no'],'edit',$ign_hdr_id)){
					$message = config('messages.message.vendorBillNoExistMsg');
                }else if(empty($formData['vendor_bill_date'])){
                    $message = config('messages.message.vendorBillDateRequiredMsg');
                }else if(empty($formData['gate_pass_no'])){
                    $message = config('messages.message.gatePassNoRequiredMsg');
				}else if($ignHdr->checkGatePassNumber($formData['gate_pass_no'],'edit',$ign_hdr_id)){
					$message = config('messages.message.gatePassNoExistMsg');
                }else if(empty($formData['employee_id'])){
                    $message = config('messages.message.employeeRequiredMsg');
                }else if(empty($formData['employee_detail'])){
                    $message = config('messages.message.employeeDetailRequiredMsg');					
				}else if(array_search("", $formData['item_id']) !== false){
                    $message = config('messages.message.itemNameErrorMsg');
				}else if(array_search("", $formData['expiry_date']) !== false){
                    $message = config('messages.message.expiryDateErrorMsg');
				}else if(array_search("", $formData['bill_qty']) !== false){
                    $message = config('messages.message.billQtyErrorMsg');
				}else if(array_search("", $formData['received_qty']) !== false){
                    $message = config('messages.message.receivedQtyErrorMsg');
				}else if(array_search("", $formData['ok_qty']) !== false){
                    $message = config('messages.message.okQtyErrorMsg');
				}else if(array_search("", $formData['bill_rate']) !== false){
                    $message = config('messages.message.billRateErrorMsg');
				}else if(array_search("", $formData['pass_rate']) !== false){
                    $message = config('messages.message.passRateErrorMsg');
				}else if(array_search("", $formData['landing_cost']) !== false){
                    $message = config('messages.message.landingCostErrorMsg');
				}else if(empty($formData['total_bill_amount'])){
                    $message = config('messages.message.totalBillAmountErrorMsg');
				}else if(empty($formData['total_pass_amount'])){
                    $message = config('messages.message.totalPassAmountErrorMsg');
                }else{
					
					//Draft PO Header detail
					$requestData = array(
						'ign_hdr_dtl_id'  	=> $formData['ign_hdr_dtl_id'],
						'item_id' 			=> $formData['item_id'],
						'po_hdr_id'     	=> $formData['po_hdr_id'],
						'expiry_date' 		=> $formData['expiry_date'],
						'bill_qty' 			=> $formData['bill_qty'],
						'received_qty' 		=> $formData['received_qty'],
						'ok_qty' 			=> $formData['ok_qty'],
						'bill_rate' 		=> $formData['bill_rate'],
						'pass_rate' 		=> $formData['pass_rate'],
						'landing_cost' 		=> $formData['landing_cost']										 
					);
										
					$formData = $models->unsetFormDataVariables($formData,array('_token','ign_hdr_id','ign_hdr_dtl_id','item_id','po_hdr_id','expiry_date','bill_qty','received_qty','ok_qty','bill_rate','pass_rate','landing_cost'));                        
                    $formData['created_by'] = $user_id;					
					//echo '<pre>';print_r($formData);die;
					
					$message = config('messages.message.updatedError');                    
                    if(!empty($ign_hdr_id) && !empty($formData)){                        
                        if(DB::table('ign_hdr')->where('ign_hdr.ign_hdr_id',$ign_hdr_id)->update($formData)){
							if($this->update_ign_hdr_detail($requestData,$ign_hdr_id)){
								$error   = '1';
								$message = config('messages.message.updated');
							}
						}else{
							$error   = '1';
							$message = config('messages.message.savedNoChange');  
						}
                    }                 
                }                
            }
        }        
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data));	
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_ign_hdr_detail($requestData,$ign_hdr_id)
    {
        global $models,$item;
        
        if(isset($requestData['ign_hdr_dtl_id'])){
            foreach($requestData['ign_hdr_dtl_id'] as $key => $ign_hdr_dtl_id){
                $dataSave = array();
                if(empty($ign_hdr_dtl_id)){
                    $dataSave['ign_hdr_id'] = $ign_hdr_id;
                }
                if(!empty($requestData['item_id'][$key])){
                    $dataSave['item_id'] = $item->getItemId($requestData['item_id'][$key]);
                }
				
                $dataSave['po_hdr_id'] = !empty($requestData['po_hdr_id'][$key]) ? $requestData['po_hdr_id'][$key] : null;
				
                if(!empty($requestData['expiry_date'][$key])){
                    $dataSave['expiry_date'] = $requestData['expiry_date'][$key];
                }
                if(!empty($requestData['bill_qty'][$key])){
                    $dataSave['bill_qty'] = $requestData['bill_qty'][$key];
                }
				if(!empty($requestData['received_qty'][$key])){
                    $dataSave['received_qty'] = $requestData['received_qty'][$key];
                }
				if(!empty($requestData['ok_qty'][$key])){
                    $dataSave['ok_qty'] = $requestData['ok_qty'][$key];
                }
				if(!empty($requestData['bill_qty'][$key])){
                    $dataSave['bill_qty'] = $requestData['bill_qty'][$key];
                }
				if(!empty($requestData['bill_rate'][$key])){
                    $dataSave['bill_rate'] = $requestData['bill_rate'][$key];
                }
				if(!empty($requestData['pass_rate'][$key])){
                    $dataSave['pass_rate'] = $requestData['pass_rate'][$key];
                }
				if(!empty($requestData['landing_cost'][$key])){
                    $dataSave['landing_cost'] = $requestData['landing_cost'][$key];
                }
                //echo '<pre>';print_r($dataSave);die;
                if(!empty($ign_hdr_dtl_id)){
                    DB::table('ign_hdr_dtl')->where('ign_hdr_dtl.ign_hdr_dtl_id',$ign_hdr_dtl_id)->update($dataSave);
                }else{                    
                    DB::table('ign_hdr_dtl')->insert($dataSave);
                }                
            }
            return true;                    
        }
        return false;
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteIGNRecord(Request $request, $ign_hdr_id)
    {
        $error    = '0';
        $message  = '';
        $data     = '';
        
        try{
            if(DB::table('ign_hdr')->where('ign_hdr.ign_hdr_id','=',$ign_hdr_id)->delete()){
                $error    = '1';
                $message = config('messages.message.deleted');      
            }else{
                $message = config('messages.message.deletedError');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.deletedErrorFKC');
        }             
		return response()->json(['error' => $error,'message' => $message]);
    }
	
	/**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteIGNHdrDtlData(Request $request, $ign_hdr_dtl_id)
    {
        $error    = '0';
        $message  = '';
        $data     = '';
        
        try{
            if(DB::table('ign_hdr_dtl')->where('ign_hdr_dtl.ign_hdr_dtl_id','=',$ign_hdr_dtl_id)->delete()){
                $error    = '1';
                $message = config('messages.message.deleted');      
            }else{
                $message = config('messages.message.deletedError');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.deletedErrorFKC');
        }             
		return response()->json(['error' => $error,'message' => $message]);
    }
}
