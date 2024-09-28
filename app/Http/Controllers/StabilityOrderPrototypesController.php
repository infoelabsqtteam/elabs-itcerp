<?php
/*****************************************************
*StabilityOrders Model File
*Created By:Praveen-Singh
*Created On : 18-Dec-2018
*Modified On : 
*Package : ITC-ERP-PKL
******************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Sample;
use App\Order;
use App\Models;
use App\Setting;
use App\StabilityOrderPrototype;
use App\ProductCategory;
use App\SendMail;
use App\Report;
use App\Customer;
use Session;
use Validator;
use Route;
use DB;
use DNS1D;

class StabilityOrderPrototypesController extends Controller
{
    
    /*************************
    * protected Variable.
    **************************/
    protected $auth;

    /*************************************
    * Create a new controller instance.
    * @return void
    **************************************/
    public function __construct(){

	global $sample,$order,$models,$mail,$reports,$customer,$stbOrderPrototype;

	$sample = new Sample();
	$order 	= new Order();
	$models = new Models();
	$mail 	= new SendMail();
	$reports = new Report();
	$customer = new Customer();
        $stbOrderPrototype = new StabilityOrderPrototype();
	
	//Checking the User Session
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
    
    /*******************************************************
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
    ********************************************************/
    public function index(){

	global $order,$models;

	$user_id               = defined('USERID') ? USERID : '0';
	$division_id   	       = defined('DIVISIONID') ? DIVISIONID : '0';
	$department_ids        = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids              = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids    = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

        return view('sales.stability_orders.index',['title' => 'Stability Orders','_stability_orders' => 'active','user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
    }
    
    /**
    * Get Stability orders list
    * created_at: 02-01-2019
    * Created_by : RUBY
    **/
    public function getStabilityOrdersList(Request $request){
        
        global $order,$models;
        
        $error		= '0';
        $message	= '';
        $data		= '';
        
        //Assigning Condition according to the Role Assigned
        parse_str($request->formData, $formData);
        
        $orderObj = DB::table('stb_order_hdr')
            ->join('divisions','divisions.division_id','stb_order_hdr.stb_division_id')
            ->join('customer_master','customer_master.customer_id','stb_order_hdr.stb_customer_id')
            ->leftJoin('city_db','city_db.city_id','customer_master.customer_city')
            ->join('users as createdBy','createdBy.id','stb_order_hdr.stb_created_by')
            ->join('product_master_alias','product_master_alias.c_product_id','stb_order_hdr.stb_sample_description_id')
            ->leftJoin('order_sample_priority','order_sample_priority.sample_priority_id','stb_order_hdr.stb_sample_priority_id');
        
        $this->setConditionAccordingToRoleAssigned($orderObj,$formData);
        $this->getStabilityOrdersMultiSearch($orderObj,$formData);
        
        $orderObj->select('stb_order_hdr.stb_status','stb_order_hdr.stb_order_hdr_id','stb_order_hdr.stb_prototype_no','stb_order_hdr.stb_prototype_date','stb_order_hdr.stb_sample_description_id','stb_order_hdr.stb_batch_no','stb_order_hdr.stb_remarks','customer_master.customer_name','order_sample_priority.sample_priority_name','divisions.division_name','createdBy.name as createdByName','product_master_alias.c_product_name as sample_description','city_db.city_name as customer_city');
        $orderObj->orderBy('stb_order_hdr.stb_order_hdr_id','DESC');
        $order = $orderObj->get();
        
        //to formate created and updated date
        $models->formatTimeStampFromArray($order,DATETIMEFORMAT);
        
        return response()->json(array('error'=> $error,'message'=> $message,'orderList'=> $order));
    }
    
    /******************************************************************
    * functions to set conditions according to the users roles
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    * Created_at: 02-01-2019
    * Created_by : RUBY
    *******************************************************************/
    public function setConditionAccordingToRoleAssigned($orderObj,$formData){

        global $order,$models;
        
        $user_id        = defined('USERID') ? USERID : '0';
        $division_id    = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids       = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $divisionId     = !empty($formData['division_id']) ? $formData['division_id'] : $division_id;
        $orderDateFrom  = !empty($formData['stability_order_date_from']) ? $formData['stability_order_date_from'] : '0';
        $orderDateTo    = !empty($formData['stability_order_date_to']) ? $formData['stability_order_date_to'] : '0';
        $keyword        = !empty($formData['keyword']) ? trim($formData['keyword']) : '0';
        
        //Filtering records according to department assigned
        if(!empty($department_ids) && is_array($department_ids)){
            $orderObj->whereIn('stb_order_hdr.stb_product_category_id', $department_ids);
        }
        //Filtering records according to division assigned
        if(!empty($divisionId) && is_numeric($divisionId)){
            $orderObj->where('stb_order_hdr.stb_division_id',$divisionId);
        }
        //Filtering records according to from and to order date
        if(!empty($orderDateFrom) && !empty($orderDateTo)){
            $orderObj->whereBetween(DB::raw("DATE(stb_order_hdr.stb_prototype_date)"), array($orderDateFrom, $orderDateTo));
        }else if(!empty($orderDateFrom) && empty($orderDateTo)){
            $orderObj->where(DB::raw("DATE(stb_order_hdr.stb_prototype_date)"),'>=', $orderDateFrom);
        }else if(empty($orderDateFrom) && !empty($orderDateTo)){
            $orderObj->where(DB::raw("DATE(stb_order_hdr.stb_prototype_date)"),'<=', $orderDateTo);
        }else{
            $orderObj->where(DB::raw("MONTH(stb_order_hdr.stb_prototype_date)"), date('m'));
        }
        //Filtering records according to search keyword
        if(!empty($keyword)){
            $orderObj->where('stb_order_hdr.stb_prototype_no','=',$keyword);
        }
        //If logged in User is Order Booker ID:4
        if(defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER){
            $orderObj->where('stb_order_hdr.stb_created_by',$user_id);
        }
    }

    /*************************
    * Show Mulit search records
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    * Created_at: 02-01-2019
    * Created_by : RUBY
    ************************/
    public function getStabilityOrdersMultiSearch($orderObj,$searchArry){

	global $order,$models;
	
	if(!empty($searchArry['search_prototype_no'])){
	    $orderObj->where('stb_order_hdr.stb_prototype_no','LIKE','%'.trim($searchArry['search_prototype_no']).'%');
	}
	if(!empty($searchArry['search_division_id'])){
	    $orderObj->where('divisions.division_id','LIKE','%'.trim($searchArry['stb_division_id']).'%');
	}
	if(!empty($searchArry['search_customer_name'])){
	    $orderObj->where('customer_master.customer_name','LIKE','%'.trim($searchArry['search_customer_name']).'%');
	}
	if(!empty($searchArry['search_prototype_date'])){
	    $orderObj->where(DB::raw("DATE(stb_order_hdr.stb_prototype_date)"),'LIKE','%'.$models->convertDateFormat(trim($searchArry['search_prototype_date'])).'%');
	}
	if(!empty($searchArry['search_sample_description'])){
	    $orderObj->where('product_master_alias.c_product_name','LIKE','%'.trim($searchArry['search_sample_description']).'%');
	}
	if(!empty($searchArry['search_batch_no'])){
	    $orderObj->where('stb_order_hdr.stb_batch_no','LIKE','%'.trim($searchArry['search_batch_no']).'%');
	}
	if(!empty($searchArry['search_sample_priority_name'])){
	    $orderObj->where('order_sample_priority.sample_priority_name','LIKE','%'.trim($searchArry['search_sample_priority_name']).'%');
	}
	if(!empty($searchArry['search_remarks'])){
	    $orderObj->where('stb_order_hdr.stb_remarks','LIKE','%'.trim($searchArry['search_remarks']).'%');
	}	
	if(!empty($searchArry['search_created_by'])){
	    $orderObj->where('createdBy.name','LIKE','%'.trim($searchArry['search_created_by']).'%');
	}
	if(!empty($division_id)){
	    $orderObj->where('stb_order_hdr.stb_division_id',$division_id);
	}
    }
	  
    /***********************************************
    * functions to generate orders excel and pdf
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    * Created On : 02-01-2019
    * Created_by :RUBY
    ************************************************/
    public function generateBranchWiseStabilityOrderPdf(Request $request){
	
	global $order,$models;

	$error		= '0';
	$message	= '';
	$data		= '';
	
	if($request->isMethod('post') && !empty($request->generate_stability_order_documents)){

	    $orderObj = DB::table('stb_order_hdr')
                        ->join('divisions','divisions.division_id','stb_order_hdr.stb_division_id')
                        ->join('customer_master','customer_master.customer_id','stb_order_hdr.stb_customer_id')
                        ->leftJoin('city_db','city_db.city_id','customer_master.customer_city')
                        ->join('users as createdBy','createdBy.id','stb_order_hdr.stb_created_by')
                        ->join('product_master_alias','product_master_alias.c_product_id','stb_order_hdr.stb_sample_description_id')
                        ->leftJoin('order_sample_priority','order_sample_priority.sample_priority_id','stb_order_hdr.stb_sample_priority_id');
    
	    //Assigning Condition according to the Role Assigned
	    $formData = $request->all();
    
	    $this->setConditionAccordingToRoleAssigned($orderObj,$formData);
	    $this->getStabilityOrdersMultiSearch($orderObj,$formData);
    
	    $orderObj->select('stb_order_hdr.stb_prototype_no as prototype_no','stb_order_hdr.stb_prototype_date as prototype_date','stb_order_hdr.stb_batch_no as batch_no','stb_order_hdr.stb_remarks as remarks','customer_master.customer_name','order_sample_priority.sample_priority_name','divisions.division_name','product_master_alias.c_product_name as sample_description','city_db.city_name as customer_city','createdBy.name as createdByName','stb_order_hdr.created_at','stb_order_hdr.updated_at');
            $orderObj->orderBy('stb_order_hdr.stb_order_hdr_id','ASC');
	    $orderObj->orderBy('stb_order_hdr.stb_prototype_date','DESC');
	    $order = $orderObj->get();
            
	    //to formate created and updated date
	    $models->formatTimeStampFromArrayExcel($order,DATEFORMATEXCEL);
	    
	    if(!empty($order)){
		$order 							= !empty($order) ? json_decode(json_encode($order),true) : array();
		$response['heading'] 		= 'Stability-Orders-List'.'('.count($order).')';
		$response['tableHead'] 		= !empty($order) ? array_keys(end($order)) : array();
		$response['tableBody'] 		= !empty($order) ? $order : array();
		$response['tablefoot']		= array();
		$response['mis_report_name']  	= 'stability_order_document';
		
		if($request->generate_stability_order_documents == 'PDF'){
		    $pdfHeaderContent  		= $models->getHeaderFooterTemplate();
		    $response['header_content']	= $pdfHeaderContent->header_content;
		    $response['footer_content']	= $pdfHeaderContent->footer_content;
		    return $models->downloadPDF($response,$contentType='ordersheet');
		}elseif($request->generate_stability_order_documents == 'Excel'){
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
    
    /*****************************************************************
    * Get list of customers on page load.
    * Date : 25-12-2018
    * Author : Praveen Singh
    ******************************************************************/
    public function getCustomerAttachedSampleDetail($sampleId){

	global $models,$customer,$sample;

	$error       = '0';
	$message     = config('messages.message.error');
	$data        = '';
	$currentDate = defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('Y-m-d');	
	
	$sampleData	     = $sample->getSampleInformation($sampleId);
	$customer_id 	     = !empty($sampleData->customer_id) ? $sampleData->customer_id : '0';
	$invoicing_type_id   = !empty($sampleData->invoicing_type_id) ? $sampleData->invoicing_type_id : '0';
	$product_category_id = !empty($sampleData->product_category_id) ? $sampleData->product_category_id : '0';
	$division_id 	     = !empty($sampleData->division_id) ? $sampleData->division_id : '0';
	
	$isFixedRateCustomer = count(DB::table('customer_invoicing_rates')
				->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
				->where('customer_invoicing_rates.cir_customer_id','=',$customer_id)
				->where('customer_invoicing_rates.cir_division_id','=',$division_id)
				->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
				->whereNull('customer_invoicing_rates.cir_c_product_id')
				->first());
	
	$customerAttachedSampleList = DB::table('samples')
		->join('customer_defined_structures','customer_defined_structures.customer_id','=','samples.customer_id')
		->join('customer_master','customer_master.customer_id','=','samples.customer_id')
		->join('divisions','divisions.division_id','samples.division_id')
		->join('customer_invoicing_types','customer_invoicing_types.invoicing_type_id','=','customer_defined_structures.invoicing_type_id')
		->join('customer_discount_types','customer_discount_types.discount_type_id','customer_defined_structures.discount_type_id')
		->join('customer_billing_types','customer_billing_types.billing_type_id','customer_defined_structures.billing_type_id')
		->join('city_db','city_db.city_id','=','customer_master.customer_city')
		->join('users','users.id','customer_master.sale_executive')
		->select('samples.sample_id','samples.sample_no','samples.sample_mode_id','divisions.division_name','customer_defined_structures.*','customer_discount_types.discount_type as discount_type_name','city_db.city_id','city_db.city_name','users.id as sale_executive','users.name','customer_master.customer_name','customer_invoicing_types.invoicing_type','customer_master.mfg_lic_no','customer_master.customer_priority_id','customer_billing_types.billing_type')
		->whereColumn('customer_defined_structures.division_id','=','samples.division_id')
		->whereColumn('customer_defined_structures.product_category_id','=','samples.product_category_id')
		->where('samples.sample_id','=',$sampleId)
		->where('users.is_sales_person','=','1')
		->first();
        
        //Getting Customer Priority List
        $samplePriorityObj = DB::table('order_sample_priority');
        if(!empty($customerAttachedSampleList->customer_priority_id)){
            $samplePriorityObj->where('order_sample_priority.sample_priority_id',$customerAttachedSampleList->customer_priority_id);
        }
        $samplePriorityList = $samplePriorityObj->select('order_sample_priority.sample_priority_id','order_sample_priority.sample_priority_name')->orderBy('sample_priority_id','ASC')->get();
        
	if(!empty($customerAttachedSampleList))$customerAttachedSampleList->fixed_rate_invoicing_type_id = $isFixedRateCustomer;
	$salesExecutiveList = !empty($customerAttachedSampleList) ? $customer->getSalesExecutiveAccordingToDivision($customerAttachedSampleList->division_id): '0';
	$error   	    = !empty($customerAttachedSampleList) ? '1' : '0';
	$message 	    = !empty($customerAttachedSampleList) ? 'Invalid' : $message;

	//echo '<pre>';print_r($customerAttachedSampleList);die;
	return response()->json(['error'=> $error,'message'=> $message,'data'=> $data,'samplePriorityList' => $samplePriorityList,'customerAttachedSampleList' => $customerAttachedSampleList,'currentDate'=>$currentDate, 'salesExecutiveList' => $salesExecutiveList]);
    }
    
    /******************************************************
    * function to get Sample Names on Auto Complete
    * @param  int  $sampleId,$keyword
    * Created by:Praveen Singh
    * @return \Illuminate\Http\Response
    *******************************************************/
    public function getAutoCompleteStabilityOrderSampleNames($sampleId,$keyword){

	global $sample,$order,$models,$mail,$reports;
	
	$isFixedRateCustomer = '0';
	$sampleData	     = $sample->getSampleInformation($sampleId);
	$customer_id 	     = !empty($sampleData->customer_id) ? $sampleData->customer_id : '0';
	$customer_city 	     = !empty($sampleData->customer_city) ? $sampleData->customer_city : '0';
	$customer_state	     = !empty($sampleData->customer_state) ? $sampleData->customer_state : '0';
	$invoicing_type_id   = !empty($sampleData->invoicing_type_id) ? $sampleData->invoicing_type_id : '0';
	$product_category_id = !empty($sampleData->product_category_id) ? $sampleData->product_category_id : '0';
	$division_id 	     = !empty($sampleData->division_id) ? $sampleData->division_id : '0';
	$allCategotyIds      = $models->getAllChildrens($product_category_id);

	$itemObj = DB::table('product_master_alias')
		->join('product_master','product_master.product_id','product_master_alias.product_id')
		->join('product_categories','product_categories.p_category_id','product_master.p_category_id')
		->select('product_master_alias.c_product_id as id',DB::raw('CONCAT(product_master_alias.c_product_name,"|",product_master.product_name) AS name'),'product_master_alias.product_id')
		->where('product_master_alias.c_product_name', 'LIKE', "%$keyword%")
		->whereIn('product_master.p_category_id',$allCategotyIds);

	if($invoicing_type_id == '2'){		//State Wise Product
	    $itemObj->join('customer_invoicing_rates', function($itemObj) use ($invoicing_type_id,$customer_id,$customer_state,$division_id,$product_category_id){
		$itemObj->on('customer_invoicing_rates.cir_c_product_id', '=', 'product_master_alias.c_product_id');
		$itemObj->where('customer_invoicing_rates.invoicing_type_id',$invoicing_type_id);
		$itemObj->where('customer_invoicing_rates.cir_state_id','=',$customer_state);
		$itemObj->where('customer_invoicing_rates.cir_division_id','=',$division_id);
		$itemObj->where('customer_invoicing_rates.cir_product_category_id',$product_category_id);
	    });
	}else if($invoicing_type_id == '3'){ 	//Customer Wise Product or Fixed rate party
	    $isFixedRateCustomer = count(DB::table('customer_invoicing_rates')
				->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
				->where('customer_invoicing_rates.cir_customer_id','=',$customer_id)
				->where('customer_invoicing_rates.cir_division_id','=',$division_id)
				->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
				->whereNull('customer_invoicing_rates.cir_c_product_id')
				->first());
	    if(empty($isFixedRateCustomer)){
		$itemObj->join('customer_invoicing_rates', function($itemObj) use ($invoicing_type_id,$customer_id,$division_id,$product_category_id){
		    $itemObj->on('customer_invoicing_rates.cir_c_product_id', '=', 'product_master_alias.c_product_id');
		    $itemObj->where('customer_invoicing_rates.invoicing_type_id',$invoicing_type_id);
		    $itemObj->where('customer_invoicing_rates.cir_customer_id',$customer_id);
		    $itemObj->where('customer_invoicing_rates.cir_division_id','=',$division_id);
		    $itemObj->where('customer_invoicing_rates.cir_product_category_id',$product_category_id);
		});
	    }
	}

	$itemObj->orderBy('product_master_alias.c_product_name','ASC');
	$itemObj->limit('100');
	$items = $itemObj->get();

	//echo'<pre>';print_r($items);die;
	return response()->json(['itemsList' => $items,'invoicing_type_id' => $invoicing_type_id,'fixed_rate_invoicing_type_id' => $isFixedRateCustomer]);
    }
    
    /***************************************************************
    * save new order /create new order
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    ****************************************************************/
    public function createCustomerSampleStabilityOrder(Request $request){

        global $order,$models,$stbOrderPrototype;

	$error       	 = '0';
	$message     	 = config('messages.message.OrderInternalErrorMsg');
	$data        	 = '';
	$stbOrderHdrId   = '0';
	$currentDate     = !defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('d-m-Y');
	$currentDateTime = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
	$formData    	 = array();

        //Saving record in orders table
	if(!empty($request->formData) && $request->isMethod('post')){

	    //Parsing the Serialze Dta
	    parse_str($request->formData, $formData);
	    //echo '<pre>';print_r($formData);die;

	    if(!empty($formData)){
		if(empty($formData['stb_sample_id'])){
		    $message = config('messages.message.sampleReceivingCodeRequired');
		}else if(empty($formData['stb_sample_description_id']) && empty($formData['stb_sample_description_name'])){
		    $message = config('messages.message.sampleDescriptionRequired');
		}else if(empty($formData['stb_sample_description_id']) && !empty($formData['stb_sample_description_name'])){
		    $message = config('messages.message.sampleDescriptionNotExist');
		}else if(!$stbOrderPrototype->checkSampleAndTestProductCategory($formData['stb_sample_id'],$formData['stb_product_id'])){
		    $message = config('messages.message.mismatchSampleAndTestProductCategory');
		}else if(empty($formData['stb_customer_id'])){
		    $message = config('messages.message.customerNameRequired');
		}else if(empty($formData['stb_customer_city'])){
		    $message = config('messages.message.customerCityRequired');
		}else if(empty($formData['stb_mfg_lic_no'])){
		    $message = config('messages.message.customerLicNumRequired');
		}else if(empty($formData['stb_sale_executive'])){
		    $message = config('messages.message.saleExecutiveRequired');
		}else if(empty($formData['stb_discount_type_id'])){
		    $message = config('messages.message.discountTypeRequired');
		}else if($formData['stb_discount_type_id'] != '3' && empty($formData['stb_discount_value'])){
		    $message = config('messages.message.discountValueRequired');
		}else if(empty($formData['stb_division_id'])){
		    $message = config('messages.message.divisionNameRequired');
		}else if(empty($formData['stb_prototype_date'])){
		    $message = config('messages.message.OrderDateErrorMsg');
		}else if(!$order->isValidDate($formData['stb_prototype_date'])){
		    $message = config('messages.message.OrderInValidOrderDateMsg');
		}else if(empty($formData['stb_batch_no'])){
		    $message = config('messages.message.batchNoRequired');
                }else if(empty($formData['stb_sample_qty'])){
                    $message = config('messages.message.stbSampleQtyRequired');
		}else if(empty($formData['stb_brand_type'])){
		    $message = config('messages.message.brandTypeRequired');
		}else if(isset($formData['is_sealed']) && is_null($formData['is_sealed'])){
		    $message = config('messages.message.isSealedRequired');
		}else if(isset($formData['stb_is_signed']) && is_null($formData['stb_is_signed'])){
		    $message = config('messages.message.isSignedRequired');
		}else if(empty($formData['stb_packing_mode'])){
		    $message = config('messages.message.packingModeRequired');
		}else if(empty($formData['stb_submission_type'])){
		    $message = config('messages.message.submissionTypeRequired');
		}else if(empty($formData['stb_sample_priority_id'])){
		    $message = config('messages.message.samplePriorityIdRequired');
		}else if(!empty($formData['stb_billing_type_id']) && $formData['stb_billing_type_id'] == '5' && empty($formData['stb_po_no'])){
		    $message = config('messages.message.samplePoNoRequired');
		}else if(!empty($formData['stb_billing_type_id']) && $formData['stb_billing_type_id'] == '5' && empty($formData['stb_po_date'])){
		    $message = config('messages.message.samplePoDateRequired');
		}else if(!empty($formData['stb_billing_type_id']) && $formData['stb_billing_type_id'] == '5' && !$order->validatePODate($formData['stb_po_date'])){
		    $message = config('messages.message.validSamplePoDateRequired');
                }else if(empty($formData['stb_invoicing_type_id'])){
		    $message = config('messages.message.invoicingTypeRequired');
		}else{                    
                    try {                        
                        //Setting the variable from request data
                        $formData['stb_prototype_date']    	= $models->get_formatted_date($formData['stb_prototype_date'].' '.date("H:i:s"),$format='Y-m-d H:i:s');
                        $formData['stb_sampling_date'] 		= !empty($formData['stb_sampling_date']) ? $models->get_formatted_date($formData['stb_sampling_date'], $format='Y-m-d H:i:s') : NULL ;
                        $formData['stb_product_category_id']    = $stbOrderPrototype->checkSampleAndTestProductCategory($formData['stb_sample_id'],$formData['stb_product_id']);
                        $formData['stb_status'] 		= '0';
                        $formData['stb_po_no'] 			= !empty($formData['stb_po_no']) ? $formData['stb_po_no'] : NULL;
                        $formData['stb_po_date'] 		= !empty($formData['stb_po_date']) ? $models->get_formatted_date($formData['stb_po_date'].' '.date("H:i:s"), $format='Y-m-d H:i:s') : NULL;
                        $formData['stb_reporting_to']  		= !empty($formData['stb_reporting_to']) ? $formData['stb_reporting_to'] : NULL;
                        $formData['stb_invoicing_to']  		= !empty($formData['stb_invoicing_to']) ? $formData['stb_invoicing_to'] : NULL;
                        $formData['stb_discount_value']  	= !empty($formData['stb_discount_value']) ? $formData['stb_discount_value'] : NULL;
                        $formData['stb_prototype_no']      	= $stbOrderPrototype->generateStabilityOrderNumber($formData);
                        $formData['stb_created_by']    		= USERID;
                        
                        //Unsetting the variable from request data
                        $formData = $models->remove_null_value_from_array($models->unsetFormDataVariables($formData,array('_token','stb_sample_description_name')));
                        $isInvoicingRate = !empty($formData['stb_invoicing_type_id']) && $formData['stb_invoicing_type_id'] != '4' ? $stbOrderPrototype->checkAddCustomerInvoivingRate($formData) : true;
                        if(!empty($isInvoicingRate)){
                            $stbOrderHdrId = DB::table('stb_order_hdr')->insertGetId($formData);
                            if(!empty($stbOrderHdrId)){
                                
                                //Creating and Inserting records in sample Qty Log Table
                                $stbSampleQtyLogArray = array('stb_order_hdr_id' => $stbOrderHdrId, 'stb_sample_qty' => $formData['stb_sample_qty'] + '1', 'stb_sample_qty_prev' => '1');                           
                                $stbOrderPrototype->insertStbOrderSampleQtyLog($stbSampleQtyLogArray);
                                
                                //Updated Sample receiving status in Sample table
                                $stbOrderPrototype->updateSampleStatusOfBookedSample($stbOrderHdrId);
                                
                                $error   = '1';
                                $data    = $stbOrderHdrId;
                                $message = config('messages.message.saved');
                            }else{
                                $message = config('messages.message.savedError');
                            }
                        }else{
                            $message = config('messages.message.InvocingTypeRequired');
                        }
                    }catch(\Illuminate\Database\QueryException $ex){
                        $message = config('messages.message.savedError');
                    }
		}
	    }
	}
	
	//echo '<pre>';print_r($formData);die;
	return response()->json(['error'=> $error,'message'=> $message,'data'=> $data, 'stb_order_hdr_id' => $stbOrderHdrId]);
    }
    
    /*************************************************************
    * dispaly orders list
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    *************************************************************/
    public function viewStabilityOrder(Request $request,$stb_order_id){

        global $order,$models,$stbOrderPrototype;

        $error    = '0';
        $message  = '';
        
        $data = $stbOrderList = $testProductMasterList = array();

        if($stb_order_id){

	    $error                             = '1';
	    $user_id                           = defined('USERID') ? USERID : '0';
	    $equipment_type_ids                = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
	    $role_ids                          = defined('ROLE_IDS') ? ROLE_IDS : '0';
	    $stbOrderList                      = $stbOrderPrototype->getStabilityOrder($stb_order_id);
	    $stbOrderList->stb_prototype_date  = !empty($stbOrderList->stb_prototype_date) ? date(DATEFORMAT,strtotime($stbOrderList->stb_prototype_date)) : '';
	    $stbOrderList->stb_po_date_prev    = !empty($stbOrderList->stb_po_date) ? date(MYSQLDATETIMEFORMAT,strtotime($stbOrderList->stb_po_date)) : '';
	    $stbOrderList->stb_po_date         = !empty($stbOrderList->stb_po_date) ? date(DATEFORMAT,strtotime($stbOrderList->stb_po_date)) : '';
	    $stbOrderList->stb_sampling_date   = !empty($stbOrderList->stb_sampling_date) ? date('Y-m-d H:i a',strtotime($stbOrderList->stb_sampling_date)) : '';
	    $stbOrderList->valid_email_status  = !empty($stbOrderList->to_emails) ? $models->validateEmailIds($stbOrderList->to_emails) : false;
	    $stbOrderList->stb_order_hdr_detail_status = $stbOrderPrototype->hasAnyStabilityOrderPrototypeBooked($stb_order_id);
            
	    //Product Master Dropdown
            if($stbOrderList->stb_product_id){
                $testProductMasterList = DB::table('product_master')->select('product_master.product_id','product_master.product_name')->where('product_master.product_id','=',$stbOrderList->stb_product_id)->orderBy('product_master.product_name')->get();
            }
            
	    //to formate order and Report date
	    $models->formatTimeStamp($stbOrderList,DATETIMEFORMAT);
        }
		  
	//echo '<pre>';print_r($stbOrderList);die;
        return response()->json(array('error'=> $error,'message'=> $message,'testProductMasterList' => $testProductMasterList, 'stbOrderList'=> $stbOrderList));
    }
    
    /****************************************************************
    * Get list of customers on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    ******************************************************************/
    public function getEditCustomerAttachedSampleDetail($stb_order_hdr_id,$sampleId){

	global $models,$order,$customer;

	$error       = '0';
	$message     = config('messages.message.error');
	$data        = '';
	$currentDate = defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('Y-m-d');

	$customerEditAttachedSampleList = DB::table('samples')
	    ->join('stb_order_hdr','stb_order_hdr.stb_sample_id','=','samples.sample_id')
	    ->join('customer_master','customer_master.customer_id','=','stb_order_hdr.stb_customer_id')
	    ->join('divisions','divisions.division_id','stb_order_hdr.stb_division_id')
	    ->join('customer_invoicing_types','customer_invoicing_types.invoicing_type_id','=','stb_order_hdr.stb_invoicing_type_id')
	    ->join('customer_discount_types','customer_discount_types.discount_type_id','stb_order_hdr.stb_discount_type_id')
	    ->join('customer_billing_types','customer_billing_types.billing_type_id','stb_order_hdr.stb_billing_type_id')
	    ->join('state_db','state_db.state_id','=','customer_master.customer_state')
	    ->join('city_db','city_db.city_id','=','stb_order_hdr.stb_customer_city')
	    ->join('users','users.id','stb_order_hdr.stb_sale_executive')
	    ->select('divisions.division_name','users.id as user_id','users.name','customer_master.customer_name','state_db.state_name','city_db.city_name','customer_discount_types.discount_type as discount_type_name','samples.sample_mode_id','samples.sample_id','stb_order_hdr.*','customer_invoicing_types.invoicing_type')
	    ->where('stb_order_hdr.stb_order_hdr_id','=',$stb_order_hdr_id)
	    ->where('samples.sample_id','=',$sampleId)
	    ->where('users.is_sales_person','=','1')
	    ->first();
            
        //Getting Customer Priority List
        $samplePriorityObj = DB::table('order_sample_priority');
        if(!empty($customerEditAttachedSampleList->customer_priority_id)){
            $samplePriorityObj->where('order_sample_priority.sample_priority_id',$customerEditAttachedSampleList->customer_priority_id);
        }
        $samplePriorityList = $samplePriorityObj->select('order_sample_priority.sample_priority_id','order_sample_priority.sample_priority_name')->orderBy('sample_priority_id','ASC')->get();

	$customerNameList   = $customer->getStabilityCustomersAccToDefinedStructure($customerEditAttachedSampleList);
	$salesExecutiveList = !empty($customerEditAttachedSampleList) ? $customer->getSalesExecutiveAccordingToDivision($customerEditAttachedSampleList->stb_division_id): '0';
	$error   	    = !empty($customerEditAttachedSampleList) ? '1' : '0';
	$message 	    = !empty($customerEditAttachedSampleList) ? '' : $message;

	//echo '<pre>';print_r($customerAttachedSampleList);die;
	return response()->json(['error'=> $error,'message'=> $message,'data'=> $data, 'samplePriorityList'=> $samplePriorityList, 'customerAttachedSampleList' => $customerEditAttachedSampleList,'currentDate'=>$currentDate , 'customerNameList' => $customerNameList, 'salesExecutiveList' => $salesExecutiveList]);
    }
    
    /****************************************************************
    * save new order /create new order
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    ******************************************************************/
    public function updateCustomerSampleStabilityOrder(Request $request){

        global $order,$models,$stbOrderPrototype;

	$error       	 = '0';
	$message     	 = config('messages.message.OrderInternalErrorMsg');
	$data        	 = '';
        $stbOrderHdrId   = $has_sample_updated = '0';
	$currentDate     = !defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('d-m-Y');
	$currentDateTime = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
	$formData    	 = array();

        //Saving record in orders table
	if(!empty($request->formData) && $request->isMethod('post')){

	    //Parsing the Serialze Dta
	    parse_str($request->formData, $formData);
            
            //Extracting Primary Key
            $stbOrderHdrId           = !empty($formData['stb_order_hdr_id']) ? $formData['stb_order_hdr_id'] : '0';
	    $stbOrderHdrData         = $stbOrderPrototype->getRow($stbOrderHdrId);
	    $prevSampleDescriptionId = !empty($stbOrderHdrData->stb_sample_description_id) ? $stbOrderHdrData->stb_sample_description_id : '0';

	    if(!empty($formData)){
		if(empty($formData['stb_sample_id'])){
		    $message = config('messages.message.sampleReceivingCodeRequired');
		}else if(empty($formData['stb_sample_description_id']) && empty($formData['stb_sample_description_name'])){
		    $message = config('messages.message.sampleDescriptionRequired');
		}else if(empty($formData['stb_sample_description_id']) && !empty($formData['stb_sample_description_name'])){
		    $message = config('messages.message.sampleDescriptionNotExist');
		}else if(!$stbOrderPrototype->checkSampleAndTestProductCategory($formData['stb_sample_id'],$formData['stb_product_id'])){
		    $message = config('messages.message.mismatchSampleAndTestProductCategory');
		}else if(empty($formData['stb_customer_id'])){
		    $message = config('messages.message.customerNameRequired');
		}else if(empty($formData['stb_customer_city'])){
		    $message = config('messages.message.customerCityRequired');
		}else if(empty($formData['stb_mfg_lic_no'])){
		    $message = config('messages.message.customerLicNumRequired');
		}else if(empty($formData['stb_sale_executive'])){
		    $message = config('messages.message.saleExecutiveRequired');
		}else if(empty($formData['stb_discount_type_id'])){
		    $message = config('messages.message.discountTypeRequired');
		}else if($formData['stb_discount_type_id'] != '3' && empty($formData['stb_discount_value'])){
		    $message = config('messages.message.discountValueRequired');
		}else if(empty($formData['stb_division_id'])){
		    $message = config('messages.message.divisionNameRequired');
		}else if(empty($formData['stb_batch_no'])){
		    $message = config('messages.message.batchNoRequired');
                }else if(empty($formData['stb_sample_qty'])){
                    $message = config('messages.message.stbSampleQtyRequired');
                }else if(!empty($formData['stb_sample_qty']) && !empty($formData['stb_sample_qty_prev']) && $formData['stb_sample_qty'] < $formData['stb_sample_qty_prev']){
                    $message = config('messages.message.stbSampleQtyMismatchError');  
		}else if(empty($formData['stb_brand_type'])){
		    $message = config('messages.message.brandTypeRequired');
		}else if(isset($formData['is_sealed']) && is_null($formData['is_sealed'])){
		    $message = config('messages.message.isSealedRequired');
		}else if(isset($formData['stb_is_signed']) && is_null($formData['stb_is_signed'])){
		    $message = config('messages.message.isSignedRequired');
		}else if(empty($formData['stb_packing_mode'])){
		    $message = config('messages.message.packingModeRequired');
		}else if(empty($formData['stb_submission_type'])){
		    $message = config('messages.message.submissionTypeRequired');
		}else if(empty($formData['stb_sample_priority_id'])){
		    $message = config('messages.message.samplePriorityIdRequired');
		}else if(!empty($formData['stb_billing_type_id']) && $formData['stb_billing_type_id'] == '5' && empty($formData['stb_po_no'])){
		    $message = config('messages.message.samplePoNoRequired');
		}else if(!empty($formData['stb_billing_type_id']) && $formData['stb_billing_type_id'] == '5' && empty($formData['stb_po_date'])){
		    $message = config('messages.message.samplePoDateRequired');
		}else if(!empty($formData['stb_billing_type_id']) && $formData['stb_billing_type_id'] == '5' && !$order->validatePODate($formData['stb_po_date'])){
		    $message = config('messages.message.validSamplePoDateRequired');
                }else if(empty($formData['stb_invoicing_type_id'])){
		    $message = config('messages.message.invoicingTypeRequired');
		}else{                    
                    try {
                        //Creating Sample Qty Logs
                        $stbSampleQtyLogArray = array('stb_order_hdr_id' => $stbOrderHdrId,'stb_sample_qty' => $formData['stb_sample_qty'],'stb_sample_qty_prev' => $formData['stb_sample_qty_prev']);
                        
                        //Setting the variable from request data
                        $formData['stb_sampling_date'] 		= !empty($formData['stb_sampling_date']) ? $models->get_formatted_date($formData['stb_sampling_date'], $format='Y-m-d H:i:s') : NULL ;
                        $formData['stb_product_category_id']    = $stbOrderPrototype->checkSampleAndTestProductCategory($formData['stb_sample_id'],$formData['stb_product_id']);
                        $formData['stb_po_no'] 			= !empty($formData['stb_po_no']) ? $formData['stb_po_no'] : NULL;
                        $formData['stb_po_date'] 		= !empty($formData['stb_po_date']) ? $stbOrderPrototype->get_formatted_po_date($formData['stb_po_date_prev'],$formData['stb_po_date'],$currentDateTime, $format='Y-m-d H:i:s') : NULL;
                        $formData['stb_reporting_to']  		= !empty($formData['stb_reporting_to']) ? $formData['stb_reporting_to'] : NULL;
                        $formData['stb_invoicing_to']  		= !empty($formData['stb_invoicing_to']) ? $formData['stb_invoicing_to'] : NULL;
                        $formData['stb_discount_value']  	= !empty($formData['stb_discount_value']) ? $formData['stb_discount_value'] : NULL;
                        $isInvoicingRate                        = !empty($formData['stb_invoicing_type_id']) && $formData['stb_invoicing_type_id'] != '4' ? $stbOrderPrototype->checkAddCustomerInvoivingRate($formData) : true;
                        
                        if(!empty($isInvoicingRate) && !empty($stbOrderHdrId)){
                            
                            //Unsetting the variable from request data
                            $formData = $models->remove_null_value_from_array($models->unsetFormDataVariables($formData,array('_token','stb_product_category_id','stb_sample_description_name','stb_order_hdr_id','stb_sample_id','stb_sample_qty_prev','stb_po_date_prev')));
 
                            if(DB::table('stb_order_hdr')->where('stb_order_hdr.stb_order_hdr_id','=',$stbOrderHdrId)->update($formData)){
                                
                                //Inserting records in sample Qty Log Table.
                                $stbOrderPrototype->insertStbOrderSampleQtyLog($stbSampleQtyLogArray);
                                
                                $error   	    = '1';
                                $data    	    = $stbOrderHdrId;
				$has_sample_updated = !empty($prevSampleDescriptionId) && !empty($formData['stb_sample_description_id']) && $prevSampleDescriptionId != $formData['stb_sample_description_id'] ? '1' : '0';
                                $message            = config('messages.message.updated');
                            }else{
                                $error   = '1';
                                $data    = $stbOrderHdrId;
                                $message = config('messages.message.savedNoChange');
                            }
                        }else{
                            $message = config('messages.message.InvocingTypeRequired');
                        }
                    }catch(\Illuminate\Database\QueryException $ex){
                        $message = config('messages.message.updatedError');
                    }
		}
	    }
	}
	
	//echo '<pre>';print_r($formData);die;
	return response()->json(['error'=> $error,'message'=> $message, 'has_sample_updated' => $has_sample_updated, 'data'=> $data, 'stb_order_hdr_id' => $stbOrderHdrId]);
    }
    
    /******************************************************************
    * Get list of ProductTestParameters on add order.
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    ******************************************************************/
    public function getProductTestMasterTabularList(Request $request){

	global $order,$models;

	$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = $categoryWiseParamenter = $categoryWiseParamenterSortedArr = $testMasterList = array();
        
         //Saving record in orders table
	if(!empty($request->formData) && $request->isMethod('post')){

	    //Parsing the Serialze Dta
	    parse_str($request->formData, $formData);
            
            $stb_product_test_id = !empty($formData['stb_product_test_id']) ? $formData['stb_product_test_id'] : '0';            
            if($stb_product_test_id){
                
                $testMasterList = DB::table('product_test_hdr')->where('product_test_hdr.test_id','=',$stb_product_test_id)->first();
                
                $productTestParametersList = DB::table('product_test_dtl')
                    ->join('product_test_hdr','product_test_dtl.test_id','product_test_hdr.test_id')
                    ->join('test_parameter','product_test_dtl.test_parameter_id','test_parameter.test_parameter_id')
                    ->join('test_parameter_categories','test_parameter_categories.test_para_cat_id','test_parameter.test_parameter_category_id')
                    ->leftJoin('equipment_type','equipment_type.equipment_id','product_test_dtl.equipment_type_id')
                    ->leftJoin('method_master','method_master.method_id','product_test_dtl.method_id')
                    ->leftJoin('detector_master','detector_master.detector_id','product_test_dtl.detector_id')
                    ->select('product_test_dtl.*','test_parameter.test_parameter_code','test_parameter.test_parameter_name','test_parameter.test_parameter_invoicing','test_parameter.test_parameter_invoicing_parent_id','equipment_type.equipment_name','method_master.method_name','test_parameter_categories.test_para_cat_id','test_parameter_categories.test_para_cat_name','test_parameter_categories.category_sort_by','detector_master.detector_name')
                    ->where('product_test_dtl.test_id',$stb_product_test_id)
                    ->orderBy('product_test_dtl.parameter_sort_by','asc')
                    ->get();
        
                if(!empty($productTestParametersList)){
                    foreach($productTestParametersList as $key => $values){
			$models->getRequirementSTDFromTo($values,$values->standard_value_from,$values->standard_value_to);
			$values->stb_product_test_stf_id 					= '0';
                        $categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']    = $values->category_sort_by;
                        $categoryWiseParamenter[$values->test_para_cat_id]['categoryId']     	= $values->test_para_cat_id;
                        $categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     	= $values->test_para_cat_name;
                        $categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] 	= $values;
                    }
                    $categoryWiseParamenterSortedArr = $models->sortArrayAscOrder(array_values($categoryWiseParamenter));
                    $error    = '1';
                    $message  = '';
                }
            }            
        }
        
	return response()->json(['error'=> $error,'message'=> $message,'data'=> $data,'productTestParametersList' => $categoryWiseParamenterSortedArr, 'testMasterList' => $testMasterList]);
    }
    
    /**************************************************************************
    * save new order / create new order
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    **************************************************************************/
    public function savePrototypeOfStabilityOrder(Request $request){

        global $order,$models,$stbOrderPrototype;

	$error       	  = '0';
	$message     	  = config('messages.message.error');
	$data        	  = '';
        $stbOrderHdrId    = '0';
        $stbOrderHdrDtlId = '0';
	$currentDate      = !defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('d-m-Y');
	$currentDateTime  = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
	$formData    	  = $stbOrderHdrDtlFormData = $stbOrderHdrDtlDetailFormData = array();

        //Saving record in orders table
	if(!empty($request->formData) && $request->isMethod('post')){

	    //Parsing the Serialze Dta
	    parse_str($request->formData, $formData);
            
            if(!empty($formData)){
                if(empty($formData['stb_order_hdr_id'])){
                    $message = config('messages.message.error');
		}else if(empty($formData['stb_start_date'])){
		    $message = config('messages.message.stbStartDateRequired');
		}else if(empty($formData['stb_end_date'])){
                    $message = config('messages.message.stbEndDateRequired');
                }else if($stbOrderPrototype->validate_enddate_prototype_stborder(array('stb_end_date' => $formData['stb_end_date'],'stb_order_hdr_id' => $formData['stb_order_hdr_id']))){
                    $message = config('messages.message.stbEndDateUniqueRequired');
                }else if(empty($formData['stb_label_name'])){
                    $message = config('messages.message.stbLabelNameRequired');
                }else if(empty($formData['stb_product_id'])){
                    $message = config('messages.message.stbProductIdRequired');
                }else if(empty($formData['stb_product_test_id'])){
                    $message = config('messages.message.stbProductTestIdRequired');
                }else if(empty($formData['stb_test_standard_id'])){
                    $message = config('messages.message.stbTestStandardIdRequired');
                }else if(empty($formData['stb_stability_type_id']) || empty(array_filter($formData['stb_stability_type_id']))){
                    $message = config('messages.message.stbStabilityTypeIdRequired');
                }else if(empty($formData['stb_sample_qty']) || $stbOrderPrototype->validate_sample_qty_prototype_stborder($formData) == false){
                    $message = config('messages.message.stbSampleQtyRequired');
                }else if(!empty($formData['stb_sample_qty']) && $stbOrderPrototype->validate_add_sample_qty_availability($formData) == false){
                    $message = config('messages.message.stbSampleQtyUnavailabilityError');
		}else if(empty($formData['stb_condition_temperature']) || $stbOrderPrototype->validate_condition_temperature_prototype_stborder($formData) == false){
                    $message = config('messages.message.stbConditionTemperatureRequired');
		}else if(empty($formData['stb_product_test_stf_id']) || empty(array_filter($formData['stb_product_test_stf_id']))){
                    $message = config('messages.message.stbProductTestStfRequired');
                }else if(empty($formData['product_test_dtl_id'])){
                    $message = config('messages.message.stbProductTestDtlIdRequired');
                }else if(count($formData['stb_stability_type_id']) != count($formData['product_test_dtl_id'])){
                    $message = config('messages.message.stbConditionTestParaMismatchError');
                }else{
                    try {
                        
                        //Starting transaction
                        DB::beginTransaction();
                        
                        $stbOrderHdrDtlFormData = array(                        
                            'stb_order_hdr_id'      => $formData['stb_order_hdr_id'],
                            'stb_product_id'        => $formData['stb_product_id'],
                            'stb_test_standard_id'  => $formData['stb_test_standard_id'],
                            'stb_product_test_id'   => $formData['stb_product_test_id'],
                            'stb_start_date'        => $models->get_formatted_date($formData['stb_start_date'].' '.date("H:i:s"),$format='Y-m-d H:i:s'),
                            'stb_end_date'          => $models->get_formatted_date($formData['stb_end_date'].' '.date("H:i:s"),$format='Y-m-d H:i:s'),
                            'stb_label_name'        => $formData['stb_label_name'],
                            'stb_order_book_status' => '0',                 
                        );
                        
                        $stbOrderHdrDtlDetailFormData = array(
                            'stb_order_hdr_id'      	=> $formData['stb_order_hdr_id'],
                            'stb_order_hdr_dtl_id'  	=> '0',
                            'stb_stability_type_id' 	=> $formData['stb_stability_type_id'],
                            'stb_sample_qty'        	=> $formData['stb_sample_qty'],
			    'stb_condition_temperature' => $formData['stb_condition_temperature'],
			    'stb_product_test_stf_id'   => $formData['stb_product_test_stf_id'],
                            'product_test_dtl_id'   	=> $formData['product_test_dtl_id'],
                        );
                                                
                        if(!empty($stbOrderHdrDtlFormData)){
                            
                            //Unsetting the variable from request data
                            $stbOrderHdrDtlFormData = $models->remove_null_value_from_array($stbOrderHdrDtlFormData);
                            
                            //Saving records in stb_order_hdr_dtl table
                            $stbOrderHdrDtlDetailFormData['stb_order_hdr_dtl_id'] = DB::table('stb_order_hdr_dtl')->insertGetId($stbOrderHdrDtlFormData);
                            
                            if(!empty($stbOrderHdrDtlDetailFormData['stb_order_hdr_dtl_id'])){
                                
                                //Saving records in stb_order_hdr_dtl_detail table
                                DB::table('stb_order_hdr_dtl_detail')->insert($stbOrderPrototype->format_add_stability_order_detail_array($stbOrderHdrDtlDetailFormData));
                                
                                //Saving Record in Stability Notofication table
                                $stbOrderHdrDtlNotiFormData = array('stb_order_hdr_id' => $stbOrderHdrDtlDetailFormData['stb_order_hdr_id'],'stb_order_hdr_dtl_id'  => $stbOrderHdrDtlDetailFormData['stb_order_hdr_dtl_id'],'stb_order_noti_dtl_date' => $stbOrderHdrDtlFormData['stb_end_date']);
                                DB::table('stb_order_noti_dtl')->insertGetId($stbOrderHdrDtlNotiFormData);
                                
                                $error            = '1';
                                $stbOrderHdrId    = $formData['stb_order_hdr_id'];
                                $stbOrderHdrDtlId = $stbOrderHdrDtlDetailFormData['stb_order_hdr_dtl_id'];
                                $message          = config('messages.message.saved');
                                
                                //Committing the queries
                                DB::commit();
                            }else{
                                $data    = $stbOrderHdrId;
                                $message = config('messages.message.savedError');
                            }
                        }else{
                            $message = config('messages.message.InvocingTypeRequired');
                        }
                    }catch(\Illuminate\Database\QueryException $ex){
                        DB::rollback();
                        $message = config('messages.message.savedError');
                    } catch (\Throwable $e) {
                        DB::rollback();
                        $message = config('messages.message.savedError');
                    }
                }
            }
        }        
        return response()->json(['error'=> $error,'message'=> $message, 'stb_order_hdr_id'=> $stbOrderHdrId, 'stb_order_hdr_dtl_id'=> $stbOrderHdrDtlId]);
    }
    
    /************************************************************************
    * Get list of ProductTestParameters on add order.
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    *************************************************************************/
    public function getAddedStabilityOrderPrototypes(Request $request){

	global $order,$models,$stbOrderPrototype;

	$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = $bookedStabilityConditionList = $addedStabilityOrderPrototypeDetail = array();
        
        //Saving record in orders table
	if(!empty($request->formData) && $request->isMethod('post')){

	    //Parsing the Serialze Dta
	    parse_str($request->formData, $formData);
            
            $addedStabilityOrderPrototypeDetail = DB::table('stb_order_hdr_dtl')
                ->join('product_master','product_master.product_id','stb_order_hdr_dtl.stb_product_id')
                ->join('product_test_hdr','product_test_hdr.test_id','stb_order_hdr_dtl.stb_product_test_id')
                ->join('test_standard','test_standard.test_std_id','stb_order_hdr_dtl.stb_test_standard_id')
                ->select('stb_order_hdr_dtl.*','product_master.product_name','test_standard.test_std_name','product_test_hdr.test_code')
                ->where('stb_order_hdr_dtl.stb_order_hdr_id',!empty($formData['stb_order_hdr_id']) ? $formData['stb_order_hdr_id'] : '0')
                ->orderBy('stb_order_hdr_dtl.stb_order_hdr_dtl_id','ASC')
                ->get()
		->toArray();
		
	    $bookedStabilityConditionList = DB::table('stb_order_hdr_dtl_detail')
		->join('order_master','order_master.stb_order_hdr_detail_id','stb_order_hdr_dtl_detail.stb_order_hdr_detail_id')
		->join('order_report_details','order_report_details.report_id','order_master.order_id')
		->join('stb_order_stability_types','stb_order_stability_types.stb_stability_type_id','stb_order_hdr_dtl_detail.stb_stability_type_id')
		->select('stb_order_hdr_dtl_detail.stb_stability_type_id as id','stb_order_stability_types.stb_stability_type_name as name')
		->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',!empty($formData['stb_order_hdr_id']) ? $formData['stb_order_hdr_id'] : '0')
		->where('stb_order_hdr_dtl_detail.stb_order_hdr_detail_status','1')
		->whereNotNull('order_report_details.approving_date')
		->groupBy('stb_order_hdr_dtl_detail.stb_stability_type_id')
		->get()
		->toArray();
            
            if(!empty($addedStabilityOrderPrototypeDetail)){
                $error    = '1';
                $message  = '';
                foreach($addedStabilityOrderPrototypeDetail as $values){
		    $values->stb_order_hdr_detail_status = $stbOrderPrototype->hasStabilityOrderParticularPrototypeBooked($values->stb_order_hdr_id,$values->stb_order_hdr_dtl_id);
                    $values->stability_condition_count = count(DB::table('stb_order_hdr_dtl_detail')->select('stb_order_hdr_dtl_detail.stb_stability_type_id')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$values->stb_order_hdr_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$values->stb_order_hdr_dtl_id)->groupBy('stb_order_hdr_dtl_detail.stb_stability_type_id')->get()->toArray());
                }                
            }
            //to formate created and updated date
            $models->formatTimeStampFromArray($addedStabilityOrderPrototypeDetail,DATETIMEFORMAT);
        }
        
        //echo '<pre>';print_r($addedStabilityOrderPrototypeDetail);die;
        return response()->json(['error'=> $error,'message'=> $message, 'bookedStabilityConditionList' => $bookedStabilityConditionList,'addedStabilityOrderPrototypeDetail'=> $addedStabilityOrderPrototypeDetail]);
    }
    
    /******************************************************************
    * Edit list of ProductTestParameters on add order.
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    *******************************************************************/
    public function editAddedStabilityOrderPrototypes(Request $request){

	global $order,$models,$stbOrderPrototype;

	$error    = '0';
        $message  = config('messages.message.error');
        $data     = '';
        $formData = array();
        
         //Saving record in orders table
	if(!empty($request->formData) && $request->isMethod('post')){

	    //Parsing the Serialze Dta
	    parse_str($request->formData, $formData);
            
            if(!empty($formData['stb_order_hdr_id']) && !empty($formData['stb_order_hdr_dtl_id'])){
                
                //Getting Prototype Detail
                $returnData['header'] = DB::table('stb_order_hdr_dtl')
                    ->join('product_master','product_master.product_id','stb_order_hdr_dtl.stb_product_id')
                    ->join('product_test_hdr','product_test_hdr.test_id','stb_order_hdr_dtl.stb_product_test_id')
                    ->join('test_standard','test_standard.test_std_id','stb_order_hdr_dtl.stb_test_standard_id')
                    ->select('stb_order_hdr_dtl.*','product_master.product_name','test_standard.test_std_name','product_test_hdr.test_code')
                    ->where('stb_order_hdr_dtl.stb_order_hdr_dtl_id',$formData['stb_order_hdr_dtl_id'])
                    ->orderBy('stb_order_hdr_dtl.stb_order_hdr_dtl_id','ASC')
                    ->first();
                
                //Getting Prototype Test Parameters Detail
                $returnData['header_detail'] = !empty($returnData['header']) ? array_values(DB::table('stb_order_hdr_dtl_detail')
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$formData['stb_order_hdr_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$formData['stb_order_hdr_dtl_id'])
                        ->pluck(DB::raw('CONCAT(stb_order_hdr_dtl_detail.stb_product_test_dtl_id,"-",stb_order_hdr_dtl_detail.stb_stability_type_id) AS stb_product_test_dtl_ids'),'stb_order_hdr_dtl_detail.stb_order_hdr_detail_id')
                        ->all()) : array();
                
                //Getting Prototype Stability Condition Detail
                $returnData['stability_detail'] = !empty($returnData['header_detail']) ? array_values(DB::table('stb_order_hdr_dtl_detail')
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$formData['stb_order_hdr_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$formData['stb_order_hdr_dtl_id'])
                        ->pluck('stb_order_hdr_dtl_detail.stb_stability_type_id','stb_order_hdr_dtl_detail.stb_stability_type_id')
                        ->all()) : array();
                //Getting Prototype Stability Sample Qty Detail
                $returnData['sample_qty_detail'] = !empty($returnData['header_detail']) ? DB::table('stb_order_hdr_dtl_detail')
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$formData['stb_order_hdr_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$formData['stb_order_hdr_dtl_id'])
                        ->pluck('stb_order_hdr_dtl_detail.stb_dtl_sample_qty','stb_order_hdr_dtl_detail.stb_stability_type_id')
                        ->all() : array();
		
		//Getting Prototype Stability ondition Temperature Detail
		$returnData['stb_condition_temperature'] = !empty($returnData['header_detail']) ? DB::table('stb_order_hdr_dtl_detail')
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$formData['stb_order_hdr_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$formData['stb_order_hdr_dtl_id'])
                        ->pluck('stb_order_hdr_dtl_detail.stb_condition_temperature','stb_order_hdr_dtl_detail.stb_stability_type_id')
                        ->all() : array();
		
		//Getting Prototype Stability STF Detail
		$returnData['stb_product_test_stf_detail'] = !empty($returnData['header_detail']) ? array_values(DB::table('stb_order_hdr_dtl_detail')
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$formData['stb_order_hdr_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$formData['stb_order_hdr_dtl_id'])
			->where('stb_order_hdr_dtl_detail.stb_product_test_stf_id','1')
                        ->pluck('stb_order_hdr_dtl_detail.stb_product_test_dtl_id','stb_order_hdr_dtl_detail.stb_product_test_dtl_id')
                        ->all()) : array();
		
		//Getting Prototype Stability Disbaled Mode for Edit/View
		$returnData['stb_order_hdr_detail_status'] = $stbOrderPrototype->hasStabilityOrderParticularPrototypeBooked($formData['stb_order_hdr_id'],$formData['stb_order_hdr_dtl_id']);
            }
            
            $error   = !empty($returnData['header']) ? '1' : '0';
            $message = $error ? '' : config('messages.message.error');
            
            //to formate created and updated date
            $models->formatTimeStamp($returnData['header'],DATEFORMAT);
        }
        
        //echo '<pre>';print_r($returnData);die;
        return response()->json(['error'=> $error,'message'=> $message, 'returnData' => $returnData]);
    }
    
    /***********************************************************************
    * save new order /create new order
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    ************************************************************************/
    public function updatePrototypeOfStabilityOrder(Request $request){

        global $order,$models,$stbOrderPrototype;

	$error       	  = '0';
	$message     	  = config('messages.message.error');
	$data        	  = '';
        $stbOrderHdrId    = '0';
        $stbOrderHdrDtlId = '0';
	$currentDate      = !defined('ORDERCURRENTDATE') ? ORDERCURRENTDATE : date('d-m-Y');
	$currentDateTime  = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
	$formData    	  = $stbEditOrderHdrDtlFormData = $stbEditOrderHdrDtlDetailFormData = array();

        //Saving record in orders table
	if(!empty($request->formData) && $request->isMethod('post')){

	    //Parsing the Serialze Dta
	    parse_str($request->formData, $formData);
            
            if(!empty($formData)){
                if(empty($formData['stb_order_hdr_id'])){
                    $message = config('messages.message.error');
                }else if(empty($formData['stb_order_hdr_dtl_id'])){
                    $message = config('messages.message.error');
		}else if(empty($formData['stb_start_date'])){
		    $message = config('messages.message.stbStartDateRequired');
		}else if(empty($formData['stb_end_date'])){
                    $message = config('messages.message.stbEndDateRequired');
                }else if(empty($formData['stb_label_name'])){
                    $message = config('messages.message.stbLabelNameRequired');
                }else if(empty($formData['stb_stability_type_id']) || empty(array_filter($formData['stb_stability_type_id']))){
                    $message = config('messages.message.stbStabilityTypeIdRequired');
                }else if(empty($formData['stb_sample_qty']) || $stbOrderPrototype->validate_sample_qty_prototype_stborder($formData) == false){
                    $message = config('messages.message.stbSampleQtyRequired');
                }else if(!empty($formData['stb_sample_qty']) && $stbOrderPrototype->validate_edit_sample_qty_availability($formData) == false){
                    $message = config('messages.message.stbSampleQtyUnavailabilityError');
		}else if(empty($formData['stb_condition_temperature']) || $stbOrderPrototype->validate_condition_temperature_prototype_stborder($formData) == false){
                    $message = config('messages.message.stbConditionTemperatureRequired');
		}else if(empty($formData['stb_product_test_stf_id']) || empty(array_filter($formData['stb_product_test_stf_id']))){
                    $message = config('messages.message.stbProductTestStfRequired');
                }else if(empty($formData['product_test_dtl_id'])){
                    $message = config('messages.message.stbProductTestDtlIdRequired');
                }else if(count($formData['stb_stability_type_id']) != count($formData['product_test_dtl_id'])){
                    $message = config('messages.message.stbConditionTestParaMismatchError');
                }else{
                    try {
                        
                        //Starting transaction
                        DB::beginTransaction();
                        
                        $stbEditOrderHdrDtlFormData = array(
                            'stb_start_date'        => $models->get_formatted_date($formData['stb_start_date'].' '.date("H:i:s"),$format='Y-m-d H:i:s'),
                            'stb_end_date'          => $models->get_formatted_date($formData['stb_end_date'].' '.date("H:i:s"),$format='Y-m-d H:i:s'),
                            'stb_label_name'        => $formData['stb_label_name'],                
                        );
                        
                        $stbEditOrderHdrDtlDetailFormData = array(
                            'stb_order_hdr_id'      	=> $formData['stb_order_hdr_id'],
                            'stb_order_hdr_dtl_id'  	=> $formData['stb_order_hdr_dtl_id'],
                            'stb_stability_type_id' 	=> $formData['stb_stability_type_id'],
                            'stb_sample_qty'        	=> $formData['stb_sample_qty'],
			    'stb_condition_temperature' => $formData['stb_condition_temperature'],
			    'stb_product_test_stf_id'   => $formData['stb_product_test_stf_id'],
                            'product_test_dtl_id'   	=> $formData['product_test_dtl_id'],
                        );
                                                
                        if(!empty($formData['stb_order_hdr_id']) && !empty($formData['stb_order_hdr_dtl_id'])){
                            
                            //Updating records in stb_order_hdr_dtl table
                            DB::table('stb_order_hdr_dtl')->where('stb_order_hdr_dtl.stb_order_hdr_dtl_id',$formData['stb_order_hdr_dtl_id'])->update($stbEditOrderHdrDtlFormData);

                            //Saving records in stb_order_hdr_dtl_detail table
                            list($newPrototypeTestParamerts,$existPrototypeTestParamerts,$notExistPrototypeTestParamerts) = $stbOrderPrototype->format_edit_stability_order_detail_array($stbEditOrderHdrDtlDetailFormData);
                            
                            //Inseting New Records
                            !empty($newPrototypeTestParamerts) ? DB::table('stb_order_hdr_dtl_detail')->insert($newPrototypeTestParamerts) : '';
                            
                            //Updating Existing records
                            if(!empty($existPrototypeTestParamerts)){
                                foreach($existPrototypeTestParamerts as $key => $values){
                                    !empty($values['stb_order_hdr_id']) && !empty($values['stb_order_hdr_dtl_id']) && !empty($values['stb_stability_type_id']) && !empty($values['stb_product_test_dtl_id']) && !empty($values['stb_dtl_sample_qty'])
                                    ?
                                    DB::table('stb_order_hdr_dtl_detail')
                                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$values['stb_order_hdr_id'])
                                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$values['stb_order_hdr_dtl_id'])
                                        ->where('stb_order_hdr_dtl_detail.stb_stability_type_id',$values['stb_stability_type_id'])
                                        ->where('stb_order_hdr_dtl_detail.stb_product_test_dtl_id',$values['stb_product_test_dtl_id'])
                                        ->update([
					    'stb_order_hdr_dtl_detail.stb_dtl_sample_qty'        => $values['stb_dtl_sample_qty'],
					    'stb_order_hdr_dtl_detail.stb_condition_temperature' => $values['stb_condition_temperature'],
					    'stb_order_hdr_dtl_detail.stb_product_test_stf_id'   => $values['stb_product_test_stf_id']
					])
                                    :
                                    '';
                                }
                            }
                            
                            //Removing records deleted by user
                            !empty($notExistPrototypeTestParamerts) ? DB::table('stb_order_hdr_dtl_detail')->whereIn('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id',$notExistPrototypeTestParamerts)->delete() : '';
                            
                            $error            = '1';
                            $stbOrderHdrId    = $formData['stb_order_hdr_id'];
                            $stbOrderHdrDtlId = $formData['stb_order_hdr_dtl_id'];
                            $message = config('messages.message.updated');
                            
                            //Committing the queries
                            DB::commit();
                        }else{
                            $message = config('messages.message.updatedError');
                        }
                    }catch(\Illuminate\Database\QueryException $ex){
                        DB::rollback();
                        $message = config('messages.message.updatedError');
                    } catch (\Throwable $e) {
                        DB::rollback();
                        $message = config('messages.message.updatedError');
                    }
                }
            }
        }
        
        //echo '<pre>';print_r($returnData);die;
        return response()->json(['error'=> $error,'message'=> $message,'stb_order_hdr_id'=> $stbOrderHdrId, 'stb_order_hdr_dtl_id'=> $stbOrderHdrDtlId]);
    }
    
    /**************************************************************
    * Delete a order
    * @param  int  $id
    * @return \Illuminate\Http\Response
    ***************************************************************/
    public function deletePrototypeStabilityOrder(Request $request, $stb_order_hdr_dtl_id){

	global $order,$models,$stbOrderPrototype;

        $error    = '0';
        $message  = '';
        $data     = '';

        try{
            if(DB::table('stb_order_hdr_dtl')->where('stb_order_hdr_dtl.stb_order_hdr_dtl_id','=',$stb_order_hdr_dtl_id)->where('stb_order_hdr_dtl.stb_order_book_status','0')->delete()){
                $error    = '1';
                $message = config('messages.message.deleted');
            }else{
                $message = config('messages.message.deletedError');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.deletedError');
        }

	return response()->json(['error' => $error,'message' => $message]);
    }
	 
    /**************************************************************
    * Send Mail on save a stability order
    * @param  int  $id
    * @return \Illuminate\Http\Response
    * created_by:RUBY
    * created_on:21-Jan-2019
    ***************************************************************/
    public function sendPrototypeStabilityOrderMail($stb_order_id){
    
	global $order,$models,$stbOrderPrototype,$mail;
	
	$error    = '0';
	$message  = '';
	
	if(!empty($stb_order_id)){
	    $error = 1;
	    $mail->sendMail(array('stability_id' => $stb_order_id, 'mailTemplateType' => '5'));
	    $message = config('messages.message.stbPrototypeMailMsg');
	}
	return response()->json(['error' => $error,'message' => $message]);
    }
	 
    /**************************************************************
    * Delete a order
    * @param  int  $id
    * @return \Illuminate\Http\Response
    ***************************************************************/
    public function deleteStabilityOrder(Request $request,$stb_order_hdr_id){
	
	global $order,$models,$stbOrderPrototype;

        $error    = '0';
        $message  = '';
        $data     = '';

        try{
            if(DB::table('stb_order_hdr')->where('stb_order_hdr.stb_order_hdr_id','=',$stb_order_hdr_id)->where('stb_order_hdr.stb_status','0')->delete()){
                $error    = '1';
                $message = config('messages.message.deleted');
            }else{
                $message = config('messages.message.deletedError');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.deletedError');
        }

	return response()->json(['error' => $error,'message' => $message]);
    }
    
    /**************************************************************
    * Delete a order
    * @param  int  $id
    * @return \Illuminate\Http\Response
    ***************************************************************/
    public function getSelectedTestParametersCheckAll(Request $request){
	
	global $order,$models,$stbOrderPrototype;

        $error    = '0';
        $message  = '';
        $data     = '';
	$formData = $returnData = array();

        try{
	    //Parsing the Serialze Dta
	    parse_str($request->formData, $formData);
	    
	    if(!empty($formData['product_test_dtl_id'])){
		foreach($formData['product_test_dtl_id'] as $stabIdkey => $valuesAll){
		    $stabId = str_replace("'","",$stabIdkey);
		    foreach($valuesAll as $key => $values){
			$returnData[$stabId.$key] = $values.'-'.$stabId;
		    }
		}	    
	    }
	    $returnData = !empty($returnData) ?  array_values($returnData) : array();
	    $error      = !empty($returnData) ? '1' : '0';
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.error');
        }
	return response()->json(['error' => $error,'message' => '', 'selectedTestParametersList' => $returnData]);
    }
	
}
