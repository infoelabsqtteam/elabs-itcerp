<?php
/*****************************************************
*StabilityOrders Model File
*Created By:Praveen-Singh
*Created On : 18-Dec-2018
*Modified On : 
*Package : ITC-ERP-PKL
******************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class StabilityOrderPrototype extends Model
{
    protected $table = 'stb_order_hdr';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    /**
    * get Order Details
    * Date :
    * Author :
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getRow($id) {
	return DB::table('stb_order_hdr')->where('stb_order_hdr_id','=',$id)->first();
    }
    
    /*************************
     *function to get formated date
     *Check date format
    *************************/
    function get_formatted_po_date($previous_date,$po_date,$current_date,$format='Y-m-d'){
	$time = !empty($previous_date) ? date('H:i:s',strtotime($previous_date)) : date('H:i:s',strtotime($current_date));
	return date($format,strtotime($po_date.' '.$time));
    }
    
    /*************************
     *function to get order
     *related  all informations
    *************************/
    function getStabilityOrder($id){
	
	global $order,$models;
	
	$orderList = DB::table('stb_order_hdr')
		->join('divisions','divisions.division_id','stb_order_hdr.stb_division_id')
                ->join('customer_master','customer_master.customer_id','stb_order_hdr.stb_customer_id')
		->join('customer_contact_persons','customer_contact_persons.customer_id','customer_master.customer_id')
		->join('customer_invoicing_types','customer_invoicing_types.invoicing_type_id','=','stb_order_hdr.stb_invoicing_type_id')
                ->join('customer_billing_types','customer_billing_types.billing_type_id','=','stb_order_hdr.stb_billing_type_id')
		->join('customer_types','customer_types.type_id','customer_master.customer_type')
		->join('customer_discount_types','customer_discount_types.discount_type_id','stb_order_hdr.stb_discount_type_id')
		->join('users as sales','sales.id','stb_order_hdr.stb_sale_executive')
		->join('users as createdBy','createdBy.id','stb_order_hdr.stb_created_by')
		->join('product_master','product_master.product_id','stb_order_hdr.stb_product_id')
		->join('product_categories','product_categories.p_category_id','product_master.p_category_id')		
		->join('city_db','city_db.city_id','stb_order_hdr.stb_customer_city')
		->join('state_db','state_db.state_id','customer_master.customer_state')
		->join('countries_db','countries_db.country_id','customer_master.customer_country')
		->join('product_master_alias','product_master_alias.c_product_id','stb_order_hdr.stb_sample_description_id')
		->join('department_product_categories_link','department_product_categories_link.product_category_id','stb_order_hdr.stb_product_category_id')
		->join('departments','departments.department_id','department_product_categories_link.department_id')
		->join('order_sample_priority','order_sample_priority.sample_priority_id','stb_order_hdr.stb_sample_priority_id')
		->join('samples','samples.sample_id','stb_order_hdr.stb_sample_id')
		->join('sample_modes','sample_modes.sample_mode_id','stb_order_hdr.stb_submission_type')
		->leftJoin('customer_master as reporting_master','reporting_master.customer_id','stb_order_hdr.stb_reporting_to')
		->leftJoin('city_db as reportngToCity','reportngToCity.city_id','reporting_master.customer_city')
		->leftJoin('state_db as reportngToState','reportngToState.state_id','reporting_master.customer_state')
		->leftJoin('customer_master as invoicing_master','invoicing_master.customer_id','stb_order_hdr.stb_invoicing_to')
		->leftJoin('city_db as invoicingToCity','invoicingToCity.city_id','invoicing_master.customer_city')
		->leftJoin('state_db as invoicingToState','invoicingToState.state_id','invoicing_master.customer_state')
		->select('stb_order_hdr.*','divisions.division_name','customer_master.customer_priority_id','customer_master.customer_type','customer_master.customer_name','customer_master.customer_address','customer_types.customer_type as customerType','customer_master.customer_email','city_db.city_name','state_db.state_name','sales.name as sale_executive_name','product_master.product_name','order_sample_priority.sample_priority_name','createdBy.name as createdByName','customer_discount_types.discount_type','samples.sample_no','sample_modes.sample_mode_name','product_master_alias.c_product_name as stb_sample_description_name','customer_invoicing_types.*','departments.*','createdBy.user_signature','reportngToCity.city_name as reporting_city','reportngToState.state_name as reporting_state','invoicingToState.state_name as invoicing_state','invoicingToCity.city_name as invoicing_city','reporting_master.customer_id as reportingCustomerId','reporting_master.customer_name as reportingCustomerName','reporting_master.customer_address as altReportingAddress','invoicing_master.customer_id as invoicingCustomerId','invoicing_master.customer_name as invoicingCustomerName','invoicing_master.customer_address as altInvoicingAddress','customer_contact_persons.contact_name1','customer_contact_persons.contact_mobile1','stb_order_hdr.stb_billing_type_id','countries_db.country_name','product_categories.p_category_name','customer_billing_types.billing_type')
		->where('stb_order_hdr.stb_order_hdr_id','=',$id)
		->first();
		
	if(!empty($orderList->stb_customer_id)){
	   list($toEmails,$ccEmails) 	= $order->getCustomerEmailToCC($orderList->stb_customer_id);
	   $orderList->to_emails     	= array_values($toEmails);
	   $orderList->cc_emails     	= array_values($ccEmails);
	   $orderList->sample_recieved  =  !empty($orderList->sample_no) ? ($orderList->sample_no.'/'.$orderList->customer_name.'/'.$orderList->state_name.'/'.$orderList->city_name) : '';
	}
	
	return $orderList;
    }
    
    /*************************
     *function to get order parameters
     *
    *************************/
    function getStabilityOrderParameters($order_id,$stb_order_hdr_id,$stb_order_hdr_dtl_id,$stb_stability_type_id){
	return DB::table('order_parameters_detail')
	    ->join('stb_order_hdr_dtl_detail','stb_order_hdr_dtl_detail.stb_product_test_dtl_id','order_parameters_detail.product_test_parameter')
	    ->join('product_test_dtl','product_test_dtl.product_test_dtl_id','order_parameters_detail.product_test_parameter')
	    ->join('product_test_hdr','product_test_dtl.test_id','product_test_hdr.test_id')
	    ->join('product_master','product_master.product_id','product_test_hdr.product_id')
	    ->join('test_parameter','order_parameters_detail.test_parameter_id','test_parameter.test_parameter_id')
	    ->join('test_parameter_categories','test_parameter_categories.test_para_cat_id','test_parameter.test_parameter_category_id')
	    ->leftJoin('equipment_type','equipment_type.equipment_id','order_parameters_detail.equipment_type_id')
	    ->leftJoin('method_master','method_master.method_id','order_parameters_detail.method_id')
	    ->leftJoin('detector_master','detector_master.detector_id','order_parameters_detail.detector_id')
	    ->leftJoin('customer_invoicing_running_time','customer_invoicing_running_time.invoicing_running_time_id','order_parameters_detail.running_time_id')
	    ->select('stb_order_hdr_dtl_detail.stb_product_test_stf_id','order_parameters_detail.product_test_parameter','order_parameters_detail.test_parameter_id','test_parameter.test_parameter_name','order_parameters_detail.display_decimal_place','order_parameters_detail.claim_value','order_parameters_detail.standard_value_from','order_parameters_detail.standard_value_to','order_parameters_detail.test_result')
	    ->where('order_parameters_detail.order_id','=',$order_id)
	    ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id','=',$stb_order_hdr_id)
	    ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id','=',$stb_order_hdr_dtl_id)
	    ->where('stb_order_hdr_dtl_detail.stb_stability_type_id','=',$stb_stability_type_id)
	    ->where('stb_order_hdr_dtl_detail.stb_product_test_stf_id','=','1')
	    ->orderBy('product_test_dtl.parameter_sort_by','ASC')
	    ->get()
	    ->toArray();
    }
	 
    /*************************
     *function to get order
     *related  all informations
    *************************/
    function getStabilityOrderHdrDtl($id){
	return DB::table('stb_order_hdr_dtl')
	    ->join('product_master','product_master.product_id','stb_order_hdr_dtl.stb_product_id')
	    ->join('product_test_hdr','product_test_hdr.test_id','stb_order_hdr_dtl.stb_product_test_id')
	    ->join('test_standard','test_standard.test_std_id','stb_order_hdr_dtl.stb_test_standard_id')
	    ->select('stb_order_hdr_dtl.*','product_master.product_name','test_standard.test_std_name','product_test_hdr.test_code')
	    ->where('stb_order_hdr_dtl.stb_order_hdr_dtl_id',$id)
	    ->orderBy('stb_order_hdr_dtl.stb_order_hdr_dtl_id','ASC')
	    ->first();
    }
    
    /*************************
     *function to get order
     *related  all informations
    *************************/
    function getStabilityOrderHdrDetailDtl($stb_order_hdr_id,$stb_order_hdr_dtl_id,$stb_stability_type_id){
	return DB::table('stb_order_hdr_dtl_detail')
	    ->join('stb_order_stability_types','stb_order_stability_types.stb_stability_type_id','stb_order_hdr_dtl_detail.stb_stability_type_id')
	    ->select('stb_order_hdr_dtl_detail.*','stb_order_stability_types.stb_stability_type_name')
	    ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$stb_order_hdr_id)
	    ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$stb_order_hdr_dtl_id)
	    ->where('stb_order_hdr_dtl_detail.stb_stability_type_id',$stb_stability_type_id)
	    ->first();
    }
    
    /*************************
     *function to get order
     *related  all informations
    *************************/
    function getStabilityOrderPrototypesDtl($id){    
	return DB::table('stb_order_hdr_dtl')
	    ->join('stb_order_hdr_dtl_detail','stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id','stb_order_hdr_dtl.stb_order_hdr_dtl_id')
	    ->join('stb_order_stability_types','stb_order_stability_types.stb_stability_type_id','stb_order_hdr_dtl_detail.stb_stability_type_id')
	    ->join('product_master','product_master.product_id','stb_order_hdr_dtl.stb_product_id')
	    ->join('product_test_hdr','product_test_hdr.test_id','stb_order_hdr_dtl.stb_product_test_id')
	    ->join('test_standard','test_standard.test_std_id','stb_order_hdr_dtl.stb_test_standard_id')
	    ->select('stb_order_hdr_dtl_detail.stb_stability_type_id','stb_order_hdr_dtl_detail.stb_order_hdr_detail_status','stb_order_hdr_dtl_detail.stb_condition_temperature','stb_order_stability_types.stb_stability_type_name','stb_order_hdr_dtl_detail.stb_dtl_sample_qty','stb_order_hdr_dtl.*','product_master.product_name','test_standard.test_std_name','product_test_hdr.test_code')
	    ->where('stb_order_hdr_dtl.stb_order_hdr_dtl_id',$id)
	    ->groupBy('stb_order_hdr_dtl_detail.stb_stability_type_id')
	    ->orderBy('stb_order_hdr_dtl_detail.stb_stability_type_id','ASC')
	    ->get()
	    ->toArray();
    }
    
    /**
    * Checking Sample Receiving Category and Test product Category
    * Author : Praveen Singh
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    function checkSampleAndTestProductCategory($sampleId,$productId){
        
        global $order,$models;
        
        $productMaster     = DB::table('product_master')->where('product_master.product_id',$productId)->first();
        $productCategoryId = $models->getMainProductCatParentId(!empty($productMaster->p_category_id) ? $productMaster->p_category_id : '0');
	$sampleData = DB::table('samples')->where('samples.sample_id',$sampleId)->where('samples.product_category_id',!empty($productCategoryId) ? $productCategoryId : '0')->first();
	return !empty($sampleData->product_category_id) ? $sampleData->product_category_id : '0';
    }
    
    /****************************************************
    *Generating Order Number
    *Format : DepartmentName-YYMMDDSERIALNo
    *****************************************************/
    function generateStabilityOrderNumber($formData){
	
	$stbOrderNumber     = '';               		
        $currentDay         = date('d');
        $currentMonth       = date('m');
        $currentYear        = date('y');
        $orderDate          = !empty($formData['stb_prototype_date']) ? $formData['stb_prototype_date'] : date('Y-m-d');			
        $orderDay           = date('d',strtotime($orderDate));
        $orderMonth         = date('m',strtotime($orderDate));
        $orderYear          = date('Y',strtotime($orderDate));
        $orderDYear         = date('y',strtotime($orderDate));
        $productCategoryId  = !empty($formData['stb_product_category_id']) ? $formData['stb_product_category_id'] : '0';
        $divisionId         = !empty($formData['stb_division_id']) ? $formData['stb_division_id'] : '0';
        
        //Getting Section Name
        $divisionData    = DB::table('divisions')->where('divisions.division_id',$divisionId)->first();
        $divisionCode    = !empty($divisionData->division_code) ? trim($divisionData->division_code) : '00';
        $productTestData = DB::table('product_categories')->where('product_categories.p_category_id',$productCategoryId)->first();
        $sectionName     = !empty($productTestData->p_category_name) ? substr($productTestData->p_category_name,0,1) : 'F';
        
        //In case of Pharma Deparment,order number will be generated according to current month and current day
        $maxOrderData = DB::table('stb_order_hdr')->select('stb_order_hdr.stb_order_hdr_id','stb_order_hdr.stb_prototype_no')->where('stb_order_hdr.stb_product_category_id',$productCategoryId)->whereMonth('stb_order_hdr.stb_prototype_date',$orderMonth)->whereYear('stb_order_hdr.stb_prototype_date',$orderYear)->where('stb_order_hdr.stb_division_id',$divisionId)->orderBy('stb_order_hdr.stb_order_hdr_id','DESC')->limit(1)->first();
        
        //getting Max Serial Number					
        $maxSerialNo  = !empty($maxOrderData->stb_prototype_no) ? substr($maxOrderData->stb_prototype_no,10) + 1: '0001';
        $maxSerialNo  = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';
        
        //Combing all to get unique order number
        $stbOrderNumber = $sectionName.$divisionCode.'-'.$orderDYear.$orderMonth.$orderDay.$maxSerialNo;
        
        //Checking Order No exist in a DB or Not.If Yes,then regenerating the Order Number
        $ifOrderNoExist = DB::table('stb_order_hdr')->select('stb_order_hdr.stb_order_hdr_id','stb_order_hdr.stb_prototype_no')->where('stb_order_hdr.stb_prototype_no',$stbOrderNumber)->first();
        if(!empty($ifOrderNoExist->stb_prototype_no)){
            //getting Max Serial Number					
            $maxSerialNo  = !empty($ifOrderNoExist->stb_prototype_no) ? substr($ifOrderNoExist->stb_prototype_no,10) + 1: '0001';
            $maxSerialNo  = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';
            
            //Combing all to get unique order number
            $stbOrderNumber = $sectionName.$divisionCode.'-'.$orderDYear.$orderMonth.$orderDay.$maxSerialNo;
        }
        
        //echo '<pre>';print_r($maxOrderData);die;            
        return $stbOrderNumber;
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : Praveen Singh
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function checkAddCustomerInvoivingRate($postedData){

	global $order,$models;

	$orderParametersDetail = array();
	$invoicingPriceAllocated = '0';

	if(!empty($postedData['stb_order_sample_type'])){
	    return true;
	}else{
            $sample_description_id  = !empty($postedData['stb_sample_description_id']) ? $postedData['stb_sample_description_id'] : '0';
	    $customer_id            = !empty($postedData['stb_customer_id']) ? $postedData['stb_customer_id'] : '0';
            $customer_city          = !empty($postedData['stb_customer_city']) ? $postedData['stb_customer_city'] : '0';
	    $division_id            = !empty($postedData['stb_division_id']) ? $postedData['stb_division_id'] : '0';
	    $invoicing_type_id 	    = !empty($postedData['stb_invoicing_type_id']) ? $postedData['stb_invoicing_type_id'] : '0';
	    $product_category_id    = !empty($postedData['stb_product_category_id']) ? $postedData['stb_product_category_id'] : '0';

	    if(!empty($customer_id) && !empty($invoicing_type_id) && !empty($product_category_id)){

		//getting customer data**************************************
		$customerData = DB::table('customer_master')->where('customer_master.customer_id','=',$customer_id)->first();
            
		if(!empty($customerData)){

		    //Conditional Invoicing Type*********************************
		    if($invoicing_type_id == '1'){			//ITC Parameter Wise
			$invoicingPriceAllocated = !empty($postedData['order_parameters_detail']['test_parameter_id']) ? true : false;
		    }else if($invoicing_type_id == '2'){		//State Wise Product
			$invoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
						->where('customer_invoicing_rates.cir_state_id','=',$customerData->customer_state)
						->where('customer_invoicing_rates.cir_c_product_id','=',$sample_description_id)
						->where('customer_invoicing_rates.cir_division_id','=',$division_id)
						->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
						->first();
			$invoicingPriceAllocated = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
		    }else if($invoicing_type_id == '3'){		//Customer Wise Product or Fixed rate party

			//In case of fixed Rate Party
			$invoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
						->where('customer_invoicing_rates.cir_customer_id','=',$customerData->customer_id)
						->where('customer_invoicing_rates.cir_division_id','=',$division_id)
						->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
						->whereNull('customer_invoicing_rates.cir_c_product_id')
						->first();

			//If Product ID is not null,then Customer Wise Product
			if(empty($invoicingData)){
				$invoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
						->where('customer_invoicing_rates.cir_city_id','=',$customer_city)
						->where('customer_invoicing_rates.cir_customer_id','=',$customerData->customer_id)
						->where('customer_invoicing_rates.cir_c_product_id','=',$sample_description_id)
						->where('customer_invoicing_rates.cir_division_id','=',$division_id)
						->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
						->first();
			}
			$invoicingPriceAllocated = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
		    }else if($invoicing_type_id == '4'){		//Customer Wise Parameters
			if(!empty($postedData['order_parameters_detail'])){
			    foreach($postedData['order_parameters_detail'] as $keyParameter => $orderParametersData){
				foreach($orderParametersData as $key => $values){
				    $orderParametersDetail[$key][$keyParameter] = empty($values) ? null : $values;
				}
			    }
			}
			if($product_category_id == '2'){
			    $invoicingPriceAllocated = $this->getCustomerWiseAssayParameterRates($invoicing_type_id,$customerData->customer_id,$division_id,$product_category_id,$orderParametersDetail,$returnType=array());
			}else{
			    $invoicingPriceAllocated = $this->getCustomerWiseParameterRates($invoicing_type_id,$customerData->customer_id,$division_id,$product_category_id,$orderParametersDetail,$returnType=array());
			}
		    }
		}
	    }
	    return $invoicingPriceAllocated;
	}
    }
    
    //Updating Sample Status of booked Order in  samples table
    function updateSampleStatusOfBookedSample($stbOrderHdrId){
	$orderData = DB::table('stb_order_hdr')->where('stb_order_hdr.stb_order_hdr_id',$stbOrderHdrId)->first();
	return !empty($orderData->stb_sample_id) ? DB::table('samples')->where('samples.sample_id',$orderData->stb_sample_id)->update(['samples.sample_booked_date' => defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s'),'samples.sample_status' => '1']) : false;
    }
    
    /*************************
    *function to format array by index
    *Date : 10-01-2019
    *Created By:Praveen Singh
    *************************/
    function format_add_stability_order_detail_array($arrayData){	
	$returnData = array();	
	if(!empty($arrayData)){
	    foreach($arrayData['product_test_dtl_id'] as $stabIdkey => $valuesAll){
		$stabId = str_replace("'","",$stabIdkey);
		foreach($valuesAll as $key => $values){
		    $returnData[$key.$stabId]['stb_order_hdr_id']          = $arrayData['stb_order_hdr_id'];
		    $returnData[$key.$stabId]['stb_order_hdr_dtl_id']      = $arrayData['stb_order_hdr_dtl_id'];
		    $returnData[$key.$stabId]['stb_stability_type_id']     = $stabId;
		    $returnData[$key.$stabId]['stb_product_test_dtl_id']   = $values;
		    $returnData[$key.$stabId]['stb_dtl_sample_qty']        = $arrayData['stb_sample_qty'][$stabIdkey];
		    $returnData[$key.$stabId]['stb_condition_temperature'] = $arrayData['stb_condition_temperature'][$stabIdkey];
		    $returnData[$key.$stabId]['stb_product_test_stf_id']   = !empty($arrayData['stb_product_test_stf_id']["'".$values."'"]) ? trim($arrayData['stb_product_test_stf_id']["'".$values."'"]) : '0';
		}
	    }	    
	}
	return array_values($returnData);	
    }
    
    /*************************
    *function to format array by index
    *Date : 10-01-2019
    *Created By:Praveen Singh
    *************************/
    function format_edit_stability_order_detail_array($arrayData){
        
        global $order,$models;
	
	$returnData = $stb_product_test_dtl_data = $stb_stability_type_data = $existPrototypeTestParamerts = $newPrototypeTestParamerts = $notExistPrototypeTestParamerts = array();
	
	if(!empty($arrayData)){
	    foreach($arrayData['product_test_dtl_id'] as $stabIdkey => $valuesAll){
		$stabId = str_replace("'","",$stabIdkey);
                $stb_stability_type_data[] = $stabId;
		foreach($valuesAll as $key => $values){
                    $stb_product_test_dtl_data[$stabId][] = $values;
		    $returnData[$key.$stabId]['stb_order_hdr_id']          = $arrayData['stb_order_hdr_id'];
		    $returnData[$key.$stabId]['stb_order_hdr_dtl_id']      = $arrayData['stb_order_hdr_dtl_id'];
		    $returnData[$key.$stabId]['stb_stability_type_id']     = $stabId;
		    $returnData[$key.$stabId]['stb_product_test_dtl_id']   = $values;
		    $returnData[$key.$stabId]['stb_dtl_sample_qty']        = $arrayData['stb_sample_qty'][$stabIdkey];
		    $returnData[$key.$stabId]['stb_condition_temperature'] = $arrayData['stb_condition_temperature'][$stabIdkey];
		    $returnData[$key.$stabId]['stb_product_test_stf_id']   = !empty($arrayData['stb_product_test_stf_id']["'".$values."'"]) ? trim($arrayData['stb_product_test_stf_id']["'".$values."'"]) : '0';
		}
            }
            
            //Getting Removed Product Test Detail Id
            if(!empty($returnData)){
                foreach($returnData as $key => $values){
                    $existData = DB::table('stb_order_hdr_dtl_detail')
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$values['stb_order_hdr_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$values['stb_order_hdr_dtl_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_stability_type_id',$values['stb_stability_type_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_product_test_dtl_id',$values['stb_product_test_dtl_id'])
                        ->first();
                    if(!empty($existData)){
                        $existPrototypeTestParamerts[$key] = $values;
                    }else{
                        $newPrototypeTestParamerts[$key] = $values;
                    }
                }
            }
            
            //Getting Removed Product Test Detail Id
            if(!empty($stb_stability_type_data)){
                $notExistPrototypeTestParamerts[] = DB::table('stb_order_hdr_dtl_detail')
                        ->whereNotIn('stb_order_hdr_dtl_detail.stb_stability_type_id',$stb_stability_type_data)
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$arrayData['stb_order_hdr_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$arrayData['stb_order_hdr_dtl_id'])
                        ->pluck('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id')
                        ->all();
            }
            if(!empty($stb_product_test_dtl_data)){
                foreach($stb_product_test_dtl_data as $keyStabId => $values){
                    $notExistPrototypeTestParamerts[] = DB::table('stb_order_hdr_dtl_detail')
                        ->whereNotIn('stb_order_hdr_dtl_detail.stb_product_test_dtl_id',array_values($values))
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$arrayData['stb_order_hdr_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$arrayData['stb_order_hdr_dtl_id'])
                        ->where('stb_order_hdr_dtl_detail.stb_stability_type_id',$keyStabId)
                        ->pluck('stb_order_hdr_dtl_detail.stb_order_hdr_detail_id')
                        ->all();
                }
            }
            $notExistPrototypeTestParamerts = !empty($notExistPrototypeTestParamerts) ? $models->array_flatten($notExistPrototypeTestParamerts) : array();
        }    
	return array(array_values($newPrototypeTestParamerts),array_values($existPrototypeTestParamerts),array_values($notExistPrototypeTestParamerts));
    }
    
    /*************************
    *function to format array by index
    *Date : 10-01-2019
    *Created By:Praveen Singh
    *************************/
    function validate_sample_qty_prototype_stborder($columnArray){        
        $returnData = array();
        if(!empty($columnArray['stb_stability_type_id']) && !empty($columnArray['stb_sample_qty'])){
            foreach($columnArray['stb_stability_type_id'] as $key => $values){
                if(empty($columnArray['stb_sample_qty']["'".$values."'"])){
                    $returnData[] = '0';
                }else{
                    $returnData[] = '1';
                }
            }
        }else{
            $returnData[] = '0';
        }
        return in_array('0',$returnData) ? false : true;
    }
    
    /*************************
    *function to format array by index
    *Date : 28-01-2019
    *Created By:Praveen Singh
    *************************/
    function validate_condition_temperature_prototype_stborder($columnArray){        
        $returnData = array();
        if(!empty($columnArray['stb_stability_type_id']) && !empty($columnArray['stb_condition_temperature'])){
            foreach($columnArray['stb_stability_type_id'] as $key => $values){
                if(empty($columnArray['stb_condition_temperature']["'".$values."'"])){
                    $returnData[] = '0';
                }else{
                    $returnData[] = '1';
                }
            }
        }else{
            $returnData[] = '0';
        }
        return in_array('0',$returnData) ? false : true;
    }
    
    /*************************
    *function to format array by index
    *Date : 10-01-2019
    *Created By:Praveen Singh
    *************************/
    function validate_add_sample_qty_availability($columnArray){
        if(!empty($columnArray['stb_order_hdr_id'])){
            $orderHdrData         = $this->getRow($columnArray['stb_order_hdr_id']);
            $sampleQtyParent      = !empty($orderHdrData->stb_sample_qty) ? trim($orderHdrData->stb_sample_qty) : '0';
	    $stabilityTypeList    = DB::table('stb_order_stability_types')->where('stb_order_stability_types.stb_stability_type_status','1')->pluck('stb_order_stability_types.stb_stability_type_id')->all();
            $stability_type_array = !empty($columnArray['stb_stability_type_id']) ? array_filter($columnArray['stb_stability_type_id']) : array();
            $sampleQtyChild       = !empty($columnArray['stb_sample_qty']) ? array_sum(array_filter($columnArray['stb_sample_qty'])) : '0';
            $sampleQtyChildren    = $sampleQtyChild + array_sum(array_values(DB::table('stb_order_hdr_dtl_detail')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$columnArray['stb_order_hdr_id'])->whereIn('stb_order_hdr_dtl_detail.stb_stability_type_id',$stabilityTypeList)->pluck('stb_order_hdr_dtl_detail.stb_dtl_sample_qty',DB::raw('CONCAT(stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id,"-",stb_order_hdr_dtl_detail.stb_stability_type_id) AS stb_product_test_dtl_ids'))->all()));   
	    if($sampleQtyChildren > $sampleQtyParent){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    
    /*************************
    *function to format array by index
    *Date : 10-01-2019
    *Created By:Praveen Singh
    *************************/
    function validate_edit_sample_qty_availability($columnArray){
        if(!empty($columnArray['stb_order_hdr_id'])){
            $orderHdrData         = $this->getRow($columnArray['stb_order_hdr_id']);
            $sampleQtyParent      = !empty($orderHdrData->stb_sample_qty) ? trim($orderHdrData->stb_sample_qty) : '0';
	    $stabilityTypeList    = DB::table('stb_order_stability_types')->where('stb_order_stability_types.stb_stability_type_status','1')->pluck('stb_order_stability_types.stb_stability_type_id')->all();
            $stability_type_array = !empty($columnArray['stb_stability_type_id']) ? array_filter($columnArray['stb_stability_type_id']) : array();
            $sampleQtyChild       = !empty($columnArray['stb_sample_qty']) ? array_sum(array_filter($columnArray['stb_sample_qty'])) : '0';
 	    $sampleQtyChildren    = $sampleQtyChild + array_sum(array_values(DB::table('stb_order_hdr_dtl_detail')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$columnArray['stb_order_hdr_id'])->whereNotIn('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',array($columnArray['stb_order_hdr_dtl_id']))->whereIn('stb_order_hdr_dtl_detail.stb_stability_type_id',$stabilityTypeList)->pluck('stb_order_hdr_dtl_detail.stb_dtl_sample_qty',DB::raw('CONCAT(stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id,"-",stb_order_hdr_dtl_detail.stb_stability_type_id) AS stb_product_test_dtl_ids'))->all()));
	    if($sampleQtyChildren > $sampleQtyParent){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    
    /*************************
    *function to validate start-end and end-date prototype stb order
    *Date : 10-01-2019
    *Created By:Praveen Singh
    *************************/
    function validate_startenddate_prototype_stborder_v1($columnArray=array()){        
        return DB::table('stb_order_hdr_dtl')
            ->where('stb_order_hdr_dtl.stb_order_hdr_id',!empty($columnArray['stb_order_hdr_id']) ? $columnArray['stb_order_hdr_id'] : '0')
            ->where(function($query) use ($columnArray) {
		$query->where(DB::raw("DATE(stb_order_hdr_dtl.stb_end_date)"), '>=', date('Y-m-d',strtotime($columnArray['stb_start_date'])));
		$query->orWhere(DB::raw("DATE(stb_order_hdr_dtl.stb_end_date)"), '>=', date('Y-m-d',strtotime($columnArray['stb_end_date'])));
            })
            ->count();
    }
    
    /*************************
    *function to validate end-date prototype stb order
    *Created On : 10-01-2019
    *Created By:Praveen Singh
    *Modified On : 22-Feb-2019
    *************************/
    function validate_enddate_prototype_stborder($columnArray=array()){        
        return DB::table('stb_order_hdr_dtl')
            ->where('stb_order_hdr_dtl.stb_order_hdr_id',!empty($columnArray['stb_order_hdr_id']) ? $columnArray['stb_order_hdr_id'] : '0')
            ->where(DB::raw("DATE(stb_order_hdr_dtl.stb_end_date)"), '>=', date('Y-m-d',strtotime($columnArray['stb_end_date'])))
            ->count();
    }
    
    /*************************
    *function to insert records in sample Qty Log Table.
    *Date : 15-01-2019
    *Created By:Praveen Singh
    *************************/
    function insertStbOrderSampleQtyLog($sampleQtylogArray){
        $isSampleQtyUpdated  = !empty($sampleQtylogArray['stb_sample_qty']) && !empty($sampleQtylogArray['stb_sample_qty_prev']) && $sampleQtylogArray['stb_sample_qty'] != $sampleQtylogArray['stb_sample_qty_prev'] && $sampleQtylogArray['stb_sample_qty'] > $sampleQtylogArray['stb_sample_qty_prev'] ? true : false;
        if($isSampleQtyUpdated){
            $dataSave = array();
            $dataSave['stb_order_hdr_id']   = $sampleQtylogArray['stb_order_hdr_id'];
            $dataSave['stb_log_sample_qty'] = $sampleQtylogArray['stb_sample_qty'] - $sampleQtylogArray['stb_sample_qty_prev'];
            DB::table('stb_order_sample_qty_logs')->insert($dataSave);
        }
    }
    
    /*************************
    *function to format array by index
    *Date : 10-01-2019
    *Created By:Praveen Singh
    *************************/
    function getOrderHdrDetailProductTestDtls($columnArray){
	return DB::table('stb_order_hdr_dtl_detail')
		->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$columnArray['stb_order_hdr_id'])
		->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$columnArray['stb_order_hdr_dtl_id'])
		->where('stb_order_hdr_dtl_detail.stb_stability_type_id',$columnArray['stb_stability_type_id'])
		->pluck('stb_order_hdr_dtl_detail.stb_product_test_dtl_id')
		->all();
    }
    
    /*************************
    *function to format array by index
    *Date : 10-01-2019
    *Created By:Praveen Singh
    *************************/
    function getOrderHdrDetailSampleQtyDtl($columnArray){
	$returnData = DB::table('stb_order_hdr_dtl_detail')
		->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$columnArray['stb_order_hdr_id'])
		->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$columnArray['stb_order_hdr_dtl_id'])
		->where('stb_order_hdr_dtl_detail.stb_stability_type_id',$columnArray['stb_stability_type_id'])
		->first();
	return !empty($returnData->stb_dtl_sample_qty) ? trim($returnData->stb_dtl_sample_qty) : '';
    }
    
    /*************************
    *function to get stability order detail
    *Date : 21-01-2019
    *Created By:RUBY
    *************************/
    public function getStabilityData($stabilityId){
	
	global $models,$order,$invoice;
	
	$stabilityId = !empty($stabilityId) ? $stabilityId : '0';
	
	$returnData = DB::table('stb_order_hdr')
		->leftJoin('stb_order_hdr_dtl','stb_order_hdr_dtl.stb_order_hdr_id','stb_order_hdr.stb_order_hdr_id')
		->join('product_master','product_master.product_id','stb_order_hdr_dtl.stb_product_id')
		->join('test_standard','test_standard.test_std_id','stb_order_hdr_dtl.stb_test_standard_id')
		->join('product_test_hdr','stb_order_hdr_dtl.stb_product_test_id','product_test_hdr.test_id')
		->where('stb_order_hdr_dtl.stb_order_hdr_id',$stabilityId)
		->select('stb_order_hdr_dtl.*','stb_order_hdr.stb_order_hdr_id as sod_id','product_master.*','test_standard.*','product_test_hdr.*')
		->get()
		->toArray();

	if(!empty($returnData)){
	    foreach($returnData as $key => $values){
		$values->stb_start_date   = !empty($values->stb_start_date) ? date('Y-m-d',strtotime($values->stb_start_date)) : '';
		$values->stb_end_date     = !empty($values->stb_end_date) ? date('Y-m-d',strtotime($values->stb_end_date)) : '';
		$values->prototypeParams  = $this->getParametersList($values->stb_product_test_id,$values->stb_order_hdr_dtl_id,$values->stb_order_hdr_id);
	    }
	}
	
	//Formating Date and Time
	$models->convertObjectToArray($returnData);
	
	return !empty($returnData) ? $returnData : array();
    }
    
    /**************************************************************
    * Get Prototype Parameter details
    * @param  int  $id
    * @return \Illuminate\Http\Response
    * created_by:RUBY
    * created_on:21-Jan-2019
    ***************************************************************/
    public function getParametersList($productTestId,$stbOrderHdrDtlId,$stabilityOrderHdrId){
	
	global $order,$models;
	
	$productTestId 		=  !empty($productTestId) ? $productTestId : '0';
	$stbOrderHdrDtlId 	=  !empty($stbOrderHdrDtlId) ? $stbOrderHdrDtlId : '0';
	$stabilityOrderHdrId 	=  !empty($stabilityOrderHdrId) ? $stabilityOrderHdrId : '0';
	
	$parametersData =  DB::table('stb_order_hdr_dtl_detail')
			    ->join('stb_order_stability_types','stb_order_stability_types.stb_stability_type_id','stb_order_hdr_dtl_detail.stb_stability_type_id')
			    ->join('product_test_dtl','product_test_dtl.product_test_dtl_id','stb_order_hdr_dtl_detail.stb_product_test_dtl_id')
			    ->join('test_parameter','product_test_dtl.test_parameter_id','test_parameter.test_parameter_id')
			    ->join('test_parameter_categories','test_parameter_categories.test_para_cat_id','test_parameter.test_parameter_category_id')
			    ->leftJoin('equipment_type','equipment_type.equipment_id','product_test_dtl.equipment_type_id')
			    ->leftJoin('method_master','method_master.method_id','product_test_dtl.method_id')
			    ->leftJoin('detector_master','detector_master.detector_id','product_test_dtl.detector_id')
			    ->select('stb_order_hdr_dtl_detail.stb_condition_temperature','test_parameter.test_parameter_name as parameters','equipment_type.equipment_name as equipment','method_master.method_name as method','detector_master.detector_name as detector','product_test_dtl.standard_value_from as standard_value_from','product_test_dtl.standard_value_to as standard_value_to','stb_order_stability_types.stb_stability_type_name','product_test_dtl.parameter_decimal_place as decimal_place','product_test_dtl.parameter_nabl_scope as nabl_Scope','product_test_dtl.time_taken_days as time_taken','test_parameter.test_parameter_invoicing','test_parameter_categories.test_para_cat_name')
			    ->where('product_test_dtl.test_id',$productTestId)
			    ->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$stbOrderHdrDtlId)
			    ->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$stabilityOrderHdrId)
			    ->orderBy('product_test_dtl.parameter_sort_by','ASC')
			    ->get()
			    ->toArray();
	    
	if(!empty($parametersData)){
	    foreach($parametersData as $key => $paramterDetail){
		  $models->getRequirementSTDFromTo($paramterDetail,$paramterDetail->standard_value_from,$paramterDetail->standard_value_to);
		  $prototpeParameters[$paramterDetail->stb_stability_type_name.'('.$paramterDetail->stb_condition_temperature.')'][$paramterDetail->test_para_cat_name][] = $paramterDetail;
	    }
	}
	
	//echo'<pre>'; print_r($prototpeParameters); die;
	return !empty($prototpeParameters) ? $prototpeParameters  : array(); 
    }
    
    /***********************************************
    *function to Check any Stability Prototype Order has been Booked or Not
    *Created On :23-Jan-2019
    *Created By:Praveen-Singh
    **********************************************/
    public function hasAnyStabilityOrderPrototypeBooked($stb_order_hdr_id){
	return DB::table('stb_order_hdr_dtl_detail')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$stb_order_hdr_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_detail_status','1')->count();
    }
    
    /***********************************************
    *function to Check any Stability Prototype Order has been Booked or Not
    *Created On :23-Jan-2019
    *Created By:Praveen-Singh
    **********************************************/
    public function hasStabilityOrderParticularPrototypeBooked($stb_order_hdr_id,$stb_order_hdr_dtl_id){
	return count(DB::table('stb_order_hdr_dtl_detail')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id',$stb_order_hdr_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id',$stb_order_hdr_dtl_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_detail_status','1')->first());
    }
    
    
}
