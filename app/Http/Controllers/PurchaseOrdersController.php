<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use App\PurchaseOrder;
use Session;
use Validator;
use Route;
use DB;

class PurchaseOrdersController extends Controller
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
        global $models,$purchaseOrder;
        $models = new Models();
        $purchaseOrder = new PurchaseOrder();
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
        
        //Paymnet Term
        $paymentTerm = array('1' => 'Monthly','2' => 'Quarterly','3' => 'Semi-Annually','4' => 'Annually',);
        
        return view('inventory.purchase_orders.index',['title' => 'Purchase Orders','_purchaseorders' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function add_po_inputs()
    {
        $error   = '0';
        $message = config('messages.message.error');
        $data    = '';
        
        return view('inventory.purchase_orders.add_po_inputs');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function edit_po_inputs()
    {
        $error   = '0';
        $message = config('messages.message.error');
        $data    = '';
        
        return view('inventory.purchase_orders.edit_po_inputs');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function generatePurchaseOrderNumber()
    {
        global $models,$purchaseOrder;
        
        $error   = '0';
        $message = '';
        $data    = '';
        
        $draftPONumber = $purchaseOrder->generateDPOPONumber('DPO');        
        return response()->json(array('error'=> $error,'message'=> $message,'draftPONumber'=> $draftPONumber));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDraftPurchaseOrder(Request $request)
    {
        global $models,$purchaseOrder;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();        
        
        //Saving record in PO table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
            $user_id = \Auth::user()->id;
            parse_str($request->formData, $formData);
                        
            if(!empty($formData)){                
                $formData = array_filter($formData);
                if(empty($formData['division_id'])){
                    $message = config('messages.message.divisionNameErrorMsg');                
                }else if(empty($formData['vendor_id'])){
                    $message = config('messages.message.vendorNameErrorMsg');
                }else if(empty($formData['payment_term'])){
                    $message = config('messages.message.paymentTermErrorMsg');
                }else if(array_search("", $formData['item_id']) !== false){
                    $message = config('messages.message.itemNameErrorMsg');
                }else if(array_search("", $formData['item_qty']) !== false){
                    $message = config('messages.message.itemQtyErrorMsg');
                }else if(array_search("", $formData['item_rate']) !== false){
                    $message = config('messages.message.itemRateErrorMsg');
                }else if(array_search("", $formData['item_amount']) !== false){
                    $message = config('messages.message.itemAmountErrorMsg');                
                }else if(empty($formData['total_qty'])){
                    $message = config('messages.message.totalQtyErrorMsg');                    
                }else if(empty($formData['gross_total'])){
                    $message = config('messages.message.grossTotalErrorMsg');
                }else if(empty($formData['excise_duty_rate'])){
                    $message = config('messages.message.exciseDutyRateErrorMsg');
                }else if(empty($formData['sales_tax_rate'])){
                    $message = config('messages.message.salesTaxRateErrorMsg');
                }else if(empty($formData['grand_total'])){
                    $message = config('messages.message.grandTotalErrorMsg');
                }else{
                  
                    //Draft PO Header detail
                    $requestData = array('item_id' => $formData['item_id'],'item_qty' => $formData['item_qty'],'item_rate' => $formData['item_rate'],'item_amount' => $formData['item_amount']);
                    
                    unset($formData['_token']);
                    unset($formData['item_id']);
                    unset($formData['item_qty']);                    
                    unset($formData['item_rate']);
                    unset($formData['item_amount']);
                    
                    $formData['dpo_date']   = !empty($formData['dpo_date']) ? $purchaseOrder->getFormatedDate($formData['dpo_date'],$format='Y-m-d') : CURRENTDATE;
                    $formData['created_by'] = $user_id;
                    //echo '<pre>';print_r($formData);die;
                    
                    $message = config('messages.message.draftPoErrorMsg');
                    
                    if(!empty($formData['dpo_no'])){
                        $dpoId = DB::table('po_hdr')->insertGetId($formData);
                        if(!empty($dpoId)){
                            if($this->save_dpo_po_detail($requestData,$dpoId)){
                                $error   = '1';
                                $message = config('messages.message.draftPoSuccessMsg');
                            }                    
                        }
                    }                   
                }                
            }
        }        
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data));	
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPurchaseOrder(Request $request)
    {
        global $models,$purchaseOrder;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();        
        
        //Saving record in PO table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
            $user_id = \Auth::user()->id;
            parse_str($request->formData, $formData);
                        
            if(!empty($formData)){                
                $formData = array_filter($formData);
                if(empty($formData['division_id'])){
                    $message = config('messages.message.divisionNameErrorMsg');                
                }else if(empty($formData['vendor_id'])){
                    $message = config('messages.message.vendorNameErrorMsg');
                }else if(empty($formData['payment_term'])){
                    $message = config('messages.message.paymentTermErrorMsg');
                }else if(array_search("", $formData['item_id']) !== false){
                    $message = config('messages.message.itemNameErrorMsg');
                }else if(array_search("", $formData['item_qty']) !== false){
                    $message = config('messages.message.itemQtyErrorMsg');
                }else if(array_search("", $formData['item_rate']) !== false){
                    $message = config('messages.message.itemRateErrorMsg');
                }else if(array_search("", $formData['item_amount']) !== false){
                    $message = config('messages.message.itemAmountErrorMsg');                
                }else if(empty($formData['total_qty'])){
                    $message = config('messages.message.totalQtyErrorMsg');                    
                }else if(empty($formData['gross_total'])){
                    $message = config('messages.message.grossTotalErrorMsg');
                }else if(empty($formData['excise_duty_rate'])){
                    $message = config('messages.message.exciseDutyRateErrorMsg');
                }else if(empty($formData['sales_tax_rate'])){
                    $message = config('messages.message.salesTaxRateErrorMsg');
                }else if(empty($formData['grand_total'])){
                    $message = config('messages.message.grandTotalErrorMsg');
                }else{
                  
                    //Draft PO Header detail
                    $requestData = array('item_id' => $formData['item_id'],'item_qty' => $formData['item_qty'],'item_rate' => $formData['item_rate'],'item_amount' => $formData['item_amount']);
                    
                    unset($formData['_token']);
                    unset($formData['item_id']);
                    unset($formData['item_qty']);                    
                    unset($formData['item_rate']);
                    unset($formData['item_amount']);
                    
                    $formData['po_date']    = !empty($formData['po_date']) ? $purchaseOrder->getFormatedDate($formData['po_date'],$format='Y-m-d') : CURRENTDATE;
                    $formData['po_no']      = !empty($formData['dpo_no']) ? substr($formData['dpo_no'], 1) : $purchaseOrder->generateDPOPONumber('PO');
                    $formData['created_by'] = $user_id;                    
                    unset($formData['dpo_no']);                    
                    //echo '<pre>';print_r($formData);die;
                    
                    $message = config('messages.message.poErrorMsg');
                    
                    if(!empty($formData['po_no'])){
                        $poId = DB::table('po_hdr')->insertGetId($formData);
                        if(!empty($poId)){
                            if($this->save_dpo_po_detail($requestData,$poId)){
                                $error   = '1';
                                $message = config('messages.message.poSuccessMsg');
                            }                    
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
    public function save_dpo_po_detail($requestData, $dpoPoId)
    {
        global $models,$purchaseOrder;
        
        $dataSave = array();
        
        if(!empty($requestData['item_id']) && !empty($dpoPoId)){
            foreach($requestData['item_id'] as $key => $itemCode){
                $dataSave[$key]['po_hdr_id']     = $dpoPoId;
                $dataSave[$key]['item_id']       = $purchaseOrder->getItemId($itemCode);
                $dataSave[$key]['purchased_qty'] = !empty($requestData['item_qty'][$key]) ? $requestData['item_qty'][$key] : '0';
                $dataSave[$key]['item_rate']     = !empty($requestData['item_rate'][$key]) ? $requestData['item_rate'][$key] : '0';
                $dataSave[$key]['item_amount']   = !empty($requestData['item_amount'][$key]) ? $requestData['item_amount'][$key] : '0';
            }                
            //echo '<pre>';print_r($dataSave);die;                
            if(!empty($dataSave) && DB::table('po_hdr_detail')->insert($dataSave)){
                return true;
            }        
        }
        return false;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePurchaseOrder(Request $request)
    {
        global $models,$purchaseOrder;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = $requestData = array();        
        
        //updating record in PO table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
            parse_str($request->formData, $formData);
                        
            if(!empty($formData)){
                if(empty($formData['division_id'])){
                    $message = config('messages.message.divisionNameErrorMsg');                
                }else if(empty($formData['vendor_id'])){
                    $message = config('messages.message.vendorNameErrorMsg');
                }else if(empty($formData['payment_term'])){
                    $message = config('messages.message.paymentTermErrorMsg');
                }else if(array_search("", $formData['item_id']) !== false){
                    $message = config('messages.message.itemNameErrorMsg');
                }else if(array_search("", $formData['item_qty']) !== false){
                    $message = config('messages.message.itemQtyErrorMsg');
                }else if(array_search("", $formData['item_rate']) !== false){
                    $message = config('messages.message.itemRateErrorMsg');
                }else if(array_search("", $formData['item_amount']) !== false){
                    $message = config('messages.message.itemAmountErrorMsg');                
                }else if(empty($formData['total_qty'])){
                    $message = config('messages.message.totalQtyErrorMsg');                    
                }else if(empty($formData['gross_total'])){
                    $message = config('messages.message.grossTotalErrorMsg');
                }else if(empty($formData['excise_duty_rate'])){
                    $message = config('messages.message.exciseDutyRateErrorMsg');
                }else if(empty($formData['sales_tax_rate'])){
                    $message = config('messages.message.salesTaxRateErrorMsg');
                }else if(empty($formData['grand_total'])){
                    $message = config('messages.message.grandTotalErrorMsg');
                }else{
                  
                    //Draft PO Header detail
                    $requestData = array('po_dtl_id'=> $formData['po_dtl_id'], 'item_id' => $formData['item_id'],'item_qty' => $formData['item_qty'],'item_rate' => $formData['item_rate'],'item_amount' => $formData['item_amount']);                    
                    $po_hdr_id = !empty($formData['po_hdr_id']) ? $formData['po_hdr_id'] : '0';
                    
                    unset($formData['_token']);
                    unset($formData['item_id']);
                    unset($formData['item_qty']);                    
                    unset($formData['item_rate']);
                    unset($formData['item_amount']);
                    unset($formData['po_dtl_id']);
                    unset($formData['po_hdr_id']);    
                    //echo '<pre>';print_r($formData);die;
                    
                    $message = config('messages.message.updatedError');                    
                    if(!empty($po_hdr_id) && !empty($formData)){                        
                        if(DB::table('po_hdr')->where('po_hdr.po_hdr_id',$po_hdr_id)->update($formData)){
							if($this->update_dpo_po_detail($requestData,$po_hdr_id)){
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
    public function update_dpo_po_detail($requestData,$po_hdr_id)
    {
        global $models,$purchaseOrder;
        
        if(isset($requestData['po_dtl_id'])){
            foreach($requestData['po_dtl_id'] as $key => $po_dtl_id){
                $dataSave = array();
                if(empty($po_dtl_id)){
                    $dataSave['po_hdr_id'] = $po_hdr_id;
                }
                if(!empty($requestData['item_id'][$key])){
                    $dataSave['item_id'] = $purchaseOrder->getItemId($requestData['item_id'][$key]);
                }
                if(!empty($requestData['item_qty'][$key])){
                    $dataSave['purchased_qty'] = $requestData['item_qty'][$key];
                }
                if(!empty($requestData['item_rate'][$key])){
                    $dataSave['item_rate'] = $requestData['item_rate'][$key];
                }
                if(!empty($requestData['item_amount'][$key])){
                    $dataSave['item_amount'] = $requestData['item_amount'][$key];
                }                
                //echo '<pre>';print_r($dataSave);die;
                if(!empty($po_dtl_id)){
                    DB::table('po_hdr_detail')->where('po_hdr_detail.po_dtl_id',$po_dtl_id)->update($dataSave);
                }else{                    
                    DB::table('po_hdr_detail')->insert($dataSave);
                }                
            }
            return true;                    
        }
        return false;
    }

    /**
     *  Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDivisionWiseDraftPOOrPOList($division_id,$dpo_po_type)
    {
        $error    = '0';
        $message  = '';
        $data     = '';
        
        $dpoPoObj = DB::table('po_hdr')
                        ->join('divisions','divisions.division_id','po_hdr.division_id')
                        ->join('vendors','vendors.vendor_id','po_hdr.vendor_id')
                        ->leftjoin('po_status','po_status.id','po_hdr.status');
        if(!empty($division_id) && is_numeric($division_id)){
            $dpoPoObj->where('po_hdr.division_id',$division_id);
        }
        if(!empty($dpo_po_type)){   //For DPO/PO Listing
            $dpoPoObj->where('po_hdr.dpo_po_type',$dpo_po_type);
        }        
        $dpoPoObj->select('po_hdr.*','divisions.division_name','vendors.vendor_name','po_status.po_status_name');  
        $dpoPoObj->orderBy('po_hdr.po_hdr_id','DESC');        
        $purchaseOrderList = $dpoPoObj->get();
        
        return response()->json(array('error'=> $error,'message'=> $message,'purchaseOrderList'=> $purchaseOrderList));
    }
    
    /**
    *  Display the specified resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function viewDraftOrPurchaseOrder($po_hdr_id)
    {
        global $models,$purchaseOrder;
         
        $error    = '0';
        $message  = '';
        $data     = '';
        
        $poHeaderData       = $purchaseOrder->getPurchaseOrderHeader($po_hdr_id);
        $poHeaderDetailData = $purchaseOrder->getPurchaseOrderHeaderDetail($po_hdr_id);        
        $error              = !empty($poHeaderData) && !empty($poHeaderDetailData) ? '1' : '0';
        
        //echo '<pre>';print_r($poHeaderData);print_r($poHeaderDetailData);die;
        return response()->json(array('error'=> $error,'message'=> $message, 'poHeaderData'=> $poHeaderData, 'poHeaderDetailData'=> $poHeaderDetailData));
    }
    
    /**
    *  Display the specified resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function convertDraftPOToPurchaseOrder($po_hdr_id)
    {
        global $models,$purchaseOrder;
        
        $error    = '0';
        $message  = config('messages.message.dpoToPOConvertError'); 
        $data     = '';
        $dataSave = array();
        
        $poHeaderData       = DB::table('po_hdr')->where('po_hdr.po_hdr_id','=',$po_hdr_id)->first();
        $poHeaderDetailData = DB::table('po_hdr_detail')->where('po_hdr_detail.po_hdr_id','=',$po_hdr_id)->get();
        if(!empty($poHeaderData) && !empty($poHeaderDetailData)){            
            $new_po_hdr_id = $this->convertDpoToPo($poHeaderData,$poHeaderDetailData);
            if(!empty($new_po_hdr_id) && DB::table('po_hdr')->where('po_hdr.po_hdr_id',$po_hdr_id)->update(['status'=> '2'])){
                $error   = '1';
                $message = config('messages.message.dpoToPOConvertSuccess');
            }                      
        }
        
        //echo '<pre>';print_r($dataSave);die;
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data, 'new_po_hdr_id' => $new_po_hdr_id));       
    }
    
        /**
    *  Display the specified resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function convertDpoToPo($poHeaderData,$poHeaderDetailData)
    {
        global $models,$purchaseOrder;
        
        unset($poHeaderData->po_hdr_id);        
        $poHeaderData->po_no        = substr($poHeaderData->dpo_no,1);
        $poHeaderData->po_date      = CURRENTDATE;
        $poHeaderData->dpo_po_type  = '1';
        $poHeaderData->status       = '1'; 
        if(!empty($poHeaderData->po_no) && !empty($poHeaderData->po_date)){
            $newPoId = DB::table('po_hdr')->insertGetId((array) $poHeaderData);
            if(!empty($newPoId)){
                foreach($poHeaderDetailData as $key => $poHeaderDetail){
                    unset($poHeaderDetail->po_dtl_id);      
                    $poHeaderDetail->po_hdr_id = $newPoId;
                    $newPoDtlId = DB::table('po_hdr_detail')->insertGetId((array) $poHeaderDetail);
                }
            }
            return !empty($newPoId) && !empty($newPoDtlId) ? $newPoId : '0';
        }        
        return false;        
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function amendPurchaseOrder(Request $request)
    {
        global $models,$purchaseOrder;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $data     = $new_amend_po_id = '';
        $formData = array();        
        
        //Saving record in PO table
        if(!empty($request->formData) && $request->isMethod('post')){                        
            
            $user_id = \Auth::user()->id;
            parse_str($request->formData, $formData);
                        
            if(!empty($formData)){
                if(empty($formData['division_id'])){
                    $message = config('messages.message.divisionNameErrorMsg');                
                }else if(empty($formData['vendor_id'])){
                    $message = config('messages.message.vendorNameErrorMsg');
                }else if(empty($formData['payment_term'])){
                    $message = config('messages.message.paymentTermErrorMsg');
                }else if(empty($formData['amendment_no'])){
                    $message = config('messages.message.amendmentNoErrorMsg');
                }else if(empty($formData['amendment_date'])){
                    $message = config('messages.message.amendmentDateErrorMsg');
                }else if(empty($formData['amendment_detail'])){
                    $message = config('messages.message.amendmentDetailErrorMsg');
                }else if(array_search("", $formData['item_id']) !== false){
                    $message = config('messages.message.itemNameErrorMsg');
                }else if(array_search("", $formData['item_qty']) !== false){
                    $message = config('messages.message.itemQtyErrorMsg');
                }else if(array_search("", $formData['item_rate']) !== false){
                    $message = config('messages.message.itemRateErrorMsg');
                }else if(array_search("", $formData['item_amount']) !== false){
                    $message = config('messages.message.itemAmountErrorMsg');                
                }else if(empty($formData['total_qty'])){
                    $message = config('messages.message.totalQtyErrorMsg');                    
                }else if(empty($formData['gross_total'])){
                    $message = config('messages.message.grossTotalErrorMsg');
                }else if(empty($formData['excise_duty_rate'])){
                    $message = config('messages.message.exciseDutyRateErrorMsg');
                }else if(empty($formData['sales_tax_rate'])){
                    $message = config('messages.message.salesTaxRateErrorMsg');
                }else if(empty($formData['grand_total'])){
                    $message = config('messages.message.grandTotalErrorMsg');
                }else{
                    
                    $message = config('messages.message.poAmendErrorMsg');
                  
                    //Draft PO Header detail
                    $requestData = array('item_id' => $formData['item_id'],'item_qty' => $formData['item_qty'],'item_rate' => $formData['item_rate'],'item_amount' => $formData['item_amount']);
                    
                    //Closing Previous PO
                    $po_hdr_id = !empty($formData['po_hdr_id']) ? $formData['po_hdr_id'] : '0';
                    $status    = $purchaseOrder->updateDPOPOStatus($po_hdr_id);                    
                    if($status){        //Previous PO Closed
                        
                        //Assigning user who is amending this PO
                        $formData['created_by']  = $user_id;
                        
                        //unseting the formdata fields
                        unset($formData['_token']);
                        unset($formData['item_id']);
                        unset($formData['item_qty']);                    
                        unset($formData['item_rate']);
                        unset($formData['item_amount']);
                        unset($formData['dpo_no']);
                        unset($formData['po_hdr_id']);
                        unset($formData['po_dtl_id']);
                        //echo '<pre>';print_r($formData);die;
                        
                        if(!empty($formData['po_no'])){
                            $amendPoId = DB::table('po_hdr')->insertGetId($formData);
                            if(!empty($amendPoId) && $this->save_dpo_po_detail($requestData,$amendPoId)){
                                $error           = '1';
                                $new_amend_po_id = $amendPoId;
                                $message         = config('messages.message.poAmendSuccessMsg');                                                   
                            }
                        }  
                    }                 
                }                
            }
        }        
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data, 'new_amend_po_id'=> $new_amend_po_id));	
    }
    
    /**
    *  Display the specified resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function finalizePurchaseOrder($po_hdr_id)
    {
        global $models,$purchaseOrder;
        
        $error    = '0';
        $message  = config('messages.message.poFinalizeErrorMsg'); 
        $data     = '';
        
        $poHeaderData       = DB::table('po_hdr')->where('po_hdr.po_hdr_id','=',$po_hdr_id)->first();
        if(!empty($poHeaderData) && $purchaseOrder->updateDPOPOStatus($po_hdr_id)){            
            $error   = '1';
            $message = config('messages.message.poFinalizeSuccessMsg');                     
        }
        
        //echo '<pre>';print_r($dataSave);die;
        return response()->json(array('error'=> $error,'message'=> $message,'data'=> $data));       
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteDraftOrPurchaseOrder(Request $request, $po_hdr_id)
    {
        $error    = '0';
        $message  = '';
        $data     = '';
        
        try{
            if(DB::table('po_hdr')->where('po_hdr.po_hdr_id','=',$po_hdr_id)->delete()){
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
    public function deleteDraftOrPurchaseOrderDetail(Request $request, $po_dtl_id)
    {
        $error    = '0';
        $message  = '';
        $data     = '';
        
        try{
            if(DB::table('po_hdr_detail')->where('po_hdr_detail.po_dtl_id','=',$po_dtl_id)->delete()){
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
