<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use App\TrfHdr;
use Session;
use Validator;
use Route;
use DB;

class TrfHdrController extends Controller
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
	
        global $models,$trfHdr;
	
        $models = new Models();
        $trfHdr = new TrfHdr();
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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
	
	global $models,$trfHdr;
	
	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		
        return view('sales.trfs.index',['title' => 'TRFs','_trf' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBranchWiseTrfs(Request $request)
    {
        global $models,$trfHdr;

	$error		= '0';
	$message	= '';
	$data		= '';

	$trfObj = DB::table('trf_hdrs')
	    ->join('divisions','divisions.division_id','trf_hdrs.trf_division_id')
            ->join('department_product_categories_link','department_product_categories_link.product_category_id','trf_hdrs.trf_product_category_id')
	    ->join('departments','departments.department_id','department_product_categories_link.department_id')
            ->join('customer_master','customer_master.customer_id','trf_hdrs.trf_customer_id')
	    ->join('city_db','city_db.city_id','customer_master.customer_city')
            ->leftJoin('samples','samples.trf_id','trf_hdrs.trf_id');

	//Assigning Condition according to the Role Assigned
	parse_str($request->formData, $formData);

	$this->setConditionAccordingToRoleAssigned($trfObj,$formData);
	$this->getOrdersMultiSearch($trfObj,$formData);

	$trfObj->select('trf_hdrs.*','customer_master.customer_name as trf_customer_name','divisions.division_name as trf_division_name','departments.department_name as trf_product_category_name','city_db.city_name as trf_customer_city','samples.sample_no');
	$trfObj->orderBy('trf_hdrs.trf_id','DESC');
	$trfData = $trfObj->get();

	//to formate created and updated date
	$models->formatTimeStampFromArray($trfData,DATETIMEFORMAT);

	return response()->json(array('error'=> $error,'message'=> $message,'trfDataList'=> $trfData));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBranchWiseTrfsPdf(Request $request)
    {
        global $models,$trfHdr;

	$error		= '0';
	$message	= '';
	$data		= '';

	$trfObj = DB::table('trf_hdrs')
	    ->join('divisions','divisions.division_id','trf_hdrs.trf_division_id')
            ->join('department_product_categories_link','department_product_categories_link.product_category_id','trf_hdrs.trf_product_category_id')
	    ->join('departments','departments.department_id','department_product_categories_link.department_id')
            ->join('customer_master','customer_master.customer_id','trf_hdrs.trf_customer_id')
	    ->join('city_db','city_db.city_id','customer_master.customer_city')
            ->leftJoin('samples','samples.trf_id','trf_hdrs.trf_id');

	//Assigning Condition according to the Role Assigned
	parse_str($request->formData, $formData);

	$this->setConditionAccordingToRoleAssigned($trfObj,$formData);
	$this->getOrdersMultiSearch($trfObj,$formData);

	$trfObj->select('trf_hdrs.trf_no','divisions.division_name as branch','departments.department_name as Department','customer_master.customer_name as customer_name','city_db.city_name as customer_city','trf_hdrs.trf_date','trf_hdrs.trf_sample_name as sample_name','trf_hdrs.trf_batch_no','samples.sample_no','trf_hdrs.trf_status as status');
	$trfObj->orderBy('trf_hdrs.trf_id','DESC');
	$trfData = $trfObj->get();

	//to formate created and updated date
	$models->formatTimeStampFromArrayExcel($trfData,DATEFORMATEXCEL);

	if(!empty($trfData)){		
            $trfData 				= !empty($trfData) ? json_decode(json_encode($trfData),true) : array();
            $trfData 				= $models->unsetFormDataVariablesArray($trfData,array('canDispatchOrder','_token'));
            $response['heading'] 		= 'TRF-List'.'('.count($trfData).')';
            $response['tableHead'] 		= !empty($trfData) ? array_keys(end($trfData)) : array();
            $response['tableBody'] 		= !empty($trfData) ? $trfData : array();
            $response['tablefoot']		= array();
            $response['mis_report_name']  	= 'TRF_Document_'.date('dmY');
            
            if($request->generate_trf_documents == 'PDF'){
                $pdfHeaderContent  		= $models->getHeaderFooterTemplate();
                $response['header_content']	= $pdfHeaderContent->header_content;
                $response['footer_content']	= $pdfHeaderContent->footer_content;
                return $models->downloadPDF($response,$contentType='ordersheet');
            }else if($request->generate_trf_documents == 'Excel'){
                return $models->generateExcel($response);
            }
        }else{
            Session::put('errorMsg', config('messages.message.noRecordFound'));
            return redirect('dashboard');
        }
    }
    
    /******************************************************************
    * functions to set conditions according to the users roles
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    *******************************************************************/
    public function setConditionAccordingToRoleAssigned($trfObj,$formData){

	global $models,$trfHdr;

	$user_id        = defined('USERID') ? USERID : '0';
	$division_id   	= defined('DIVISIONID') ? DIVISIONID : '0';
	$department_ids = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids       = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$divisionId     = !empty($formData['division_id']) ? $formData['division_id'] : $division_id;
        $department_ids = !empty($formData['product_category_id']) ? array($formData['product_category_id']) : $department_ids;
	$trfDateFrom    = !empty($formData['trf_date_from']) ? $formData['trf_date_from'] : '0';
	$trfDateTo      = !empty($formData['trf_date_to']) ? $formData['trf_date_to'] : '0';
	$keyword        = !empty($formData['keyword']) ? trim($formData['keyword']) : '0';
        $trfStatus      = isset($formData['trf_status']) ? trim($formData['trf_status']) : '0';

	//Filtering records according to department assigned
	if(!empty($department_ids) && is_array($department_ids)){
	    $trfObj->whereIn('trf_hdrs.trf_product_category_id', $department_ids);
	}
	//Filtering records according to division assigned
	if(!empty($divisionId) && is_numeric($divisionId)){
	    $trfObj->where('trf_hdrs.trf_division_id',$divisionId);
	}
	//Filtering records according to from and to order date
	if(!empty($trfDateFrom) && !empty($trfDateTo)){
	    $trfObj->whereBetween(DB::raw("DATE(trf_hdrs.trf_date)"), array($trfDateFrom, $trfDateTo));
	}else if(!empty($trfDateFrom) && empty($trfDateTo)){
	    $trfObj->where(DB::raw("DATE(trf_hdrs.trf_date)"),'>=', $trfDateFrom);
	}else if(empty($trfDateFrom) && !empty($trfDateTo)){
	    $trfObj->where(DB::raw("DATE(trf_hdrs.trf_date)"),'<=', $trfDateTo);
	}else{
	    $trfObj->where(DB::raw("MONTH(trf_hdrs.trf_date)"), date('m'));
	}        
        //Filtering records according to TRF Status assigned
	if(isset($trfStatus) && is_numeric($trfStatus)){
	    $trfObj->where('trf_hdrs.trf_status',$trfStatus);
	}
	//Filtering records according to search keyword
	if(!empty($keyword)){
	    $trfObj->where('trf_hdrs.trf_no','=',$keyword);
	}
    }

    /*************************
    * Show Mulit search records
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    ************************/
    public function getOrdersMultiSearch($trfObj,$searchArry){

	global $models,$trfHdr;

	if(!empty($searchArry['search_trf_no'])){
	    $trfObj->where('trf_hdrs.trf_no','LIKE','%'.trim($searchArry['search_trf_no']).'%');
	}
	if(!empty($searchArry['search_trf_division_name'])){
	    $trfObj->where('divisions.division_name','LIKE','%'.trim($searchArry['search_trf_division_name']).'%');
	}
	if(!empty($searchArry['search_trf_customer_name'])){
	    $trfObj->where('customer_master.customer_name','LIKE','%'.trim($searchArry['search_trf_customer_name']).'%');
	}
	if(!empty($searchArry['search_trf_date'])){
	    $trfObj->where(DB::raw("DATE(trf_hdrs.trf_date)"),'LIKE','%'.$models->convertDateFormat(trim($searchArry['search_trf_date'])).'%');
	}
	if(!empty($searchArry['search_trf_sample_name'])){
	    $trfObj->where('trf_hdrs.trf_sample_name','LIKE','%'.trim($searchArry['search_trf_sample_name']).'%');
	}
	if(!empty($searchArry['search_trf_batch_no'])){
	    $trfObj->where('trf_hdrs.trf_batch_no','LIKE','%'.trim($searchArry['search_trf_batch_no']).'%');
	}
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewTrf(Request $request)
    {
        global $models,$trfHdr;

	$error   = '0';
	$message = '';
	$data    = '';
        $trfHdrResponse = $trfHdrDtlResponse = array();
        
        if($request->isMethod('post') && !empty($request->trf_id)){
            
            $trfId = !empty($request->trf_id) ? $request->trf_id : '0';    
            $trfHdrResponse = DB::table('trf_hdrs')
                ->join('divisions','divisions.division_id','trf_hdrs.trf_division_id')
                ->join('department_product_categories_link','department_product_categories_link.product_category_id','trf_hdrs.trf_product_category_id')
                ->join('departments','departments.department_id','department_product_categories_link.department_id')
                ->join('customer_master','customer_master.customer_id','trf_hdrs.trf_customer_id')
                ->join('city_db','city_db.city_id','customer_master.customer_city')
                ->leftJoin('trf_storge_condition_dtls','trf_storge_condition_dtls.trf_sc_id','trf_hdrs.trf_storage_condition_id')
                ->leftJoin('product_test_hdr','product_test_hdr.test_id','trf_hdrs.trf_product_test_id')
                ->leftJoin('test_standard','test_standard.test_std_id','trf_hdrs.trf_test_standard_id')
                ->leftJoin('product_master','product_master.product_id','trf_hdrs.trf_product_id')
                ->leftJoin('product_categories as trfPcategoryDB','trfPcategoryDB.p_category_id','trf_hdrs.trf_p_category_id')
                ->leftJoin('product_categories as trfSubCategoryDB','trfSubCategoryDB.p_category_id','trf_hdrs.trf_sub_p_category_id')
                ->leftJoin('customer_master as reporting_master','reporting_master.customer_code','trf_hdrs.trf_reporting_to')
                ->leftJoin('customer_master as invoicing_master','invoicing_master.customer_code','trf_hdrs.trf_invoicing_to')
                ->select('trf_hdrs.*','reporting_master.customer_name as reporting_customer_name','invoicing_master.customer_name as invoicing_customer_name','trf_storge_condition_dtls.trf_sc_name as trf_storage_condition_name','divisions.division_name as trf_division_name','departments.department_name as trf_product_category_name','customer_master.customer_name as trf_customer_name','city_db.city_name as trf_city_name','product_test_hdr.test_code as trf_product_test_name','test_standard.test_std_name as trf_j_test_standard_name','product_master.product_name as trf_j_product_name','trfPcategoryDB.p_category_name as trf_p_category_name','trfSubCategoryDB.p_category_name as trf_sub_p_category_name')
                ->where('trf_hdrs.trf_id',$trfId)
                ->first();
                
            if(!empty($trfHdrResponse->trf_id)){
                
                $trfHdrResponse->trf_status = isset($trfHdrResponse->trf_status) && $trfHdrResponse->trf_status == '1' ? 'Booked' : 'Pending';
                $trfHdrResponse->trf_active_deactive_status_name = !empty($trfHdrResponse->trf_active_deactive_status) && $trfHdrResponse->trf_active_deactive_status == '1' ? 'Active' : 'Deactive';
                $trfHdrResponse->trf_product_test_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_product_test_name : '';
                $trfHdrResponse->trf_test_standard_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_j_test_standard_name : $trfHdrResponse->trf_test_standard_name;
                $trfHdrResponse->trf_product_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_j_product_name : $trfHdrResponse->trf_product_name;
                $trfHdrResponse->trf_p_category_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_p_category_name : '-';
                $trfHdrResponse->trf_sub_p_category_name = !empty($trfHdrResponse->trf_type) && $trfHdrResponse->trf_type == '1' ? $trfHdrResponse->trf_sub_p_category_name : '-';
                
                //Assigning TRF Data to the Array
                $response['trfHdr'] = $models->convertObjectToArray($trfHdrResponse);
                
                $trfHdrDtlResponse = DB::table('trf_hdr_dtls')
                                ->leftJoin('test_parameter','test_parameter.test_parameter_id','trf_hdr_dtls.trf_test_parameter_id')
                                ->select('trf_hdr_dtls.*','test_parameter.test_parameter_name as trf_j_test_parameter_name')
                                ->where('trf_hdr_dtls.trf_hdr_id',$trfHdrResponse->trf_id)
                                ->get()
                                ->toArray();
                if(!empty($trfHdrDtlResponse)){
                    foreach($trfHdrDtlResponse as $key => $values){
                        $values->trf_test_parameter_name = !empty($values->trf_j_test_parameter_name) ? $values->trf_j_test_parameter_name : $values->trf_test_parameter_name;
                    }
                    //Assigning TRF Data to the Array
                    $response['trfHdrDtl'] = $models->convertObjectToArray($trfHdrDtlResponse);
                }                
            }
        }
        
        //echo '<pre>';print_r($response);die;
	return response()->json(array('error'=> $error,'message'=> $message,'response'=> $response));
    }

}
