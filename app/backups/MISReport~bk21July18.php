<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MISReport extends Model
{
    protected $table = '';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
    protected $fillable = [];
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getBookedSamplePrice($customer_id, $order_id){
	
	global $order,$models;
	    
	$returnData = $parameterWiseRateData = array();
	
	//getting customer data**************************************
	$customerData = DB::table('customer_master')
					->join('customer_invoicing_types','customer_invoicing_types.invoicing_type_id','=','customer_master.invoicing_type_id')
					->select('customer_master.*','customer_invoicing_types.invoicing_type')
					->where('customer_master.customer_id','=',$customer_id)
					->first();
	
	//getting Order data*****************************************
	$orderData 		 = DB::table('order_master')->where('order_master.order_id','=',$order_id)->first();
	$invoicingTypeId	 = !empty($orderData->invoicing_type_id) ? $orderData->invoicing_type_id : '0';
	$division_id         	 = !empty($orderData->division_id) ? $orderData->division_id : '0';
	$product_category_id 	 = !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';
	$customer_city	         = !empty($orderData->customer_city) ? $orderData->customer_city : '0';
	
	//Conditional Invoicing Type*********************************
	if(!empty($invoicingTypeId) && !empty($product_category_id)){
		if($invoicingTypeId == '1'){			//ITC Parameter Wise
			$sellingPriceAmount = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->sum('selling_price');
		}else if($invoicingTypeId == '2'){		//State Wise Product
			$invoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id','=',$invoicingTypeId)
						->where('customer_invoicing_rates.cir_state_id','=',$customerData->customer_state)
						->where('customer_invoicing_rates.cir_c_product_id','=',$orderData->sample_description_id)
						->where('customer_invoicing_rates.cir_division_id','=',$division_id)
						->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
						->first();
			$sellingPriceAmount = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';				
		}else if($invoicingTypeId == '3'){		//Customer Wise Product or Fixed rate party
			
			//In case of fixed Rate Party
			$invoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id','=',$invoicingTypeId)
						->where('customer_invoicing_rates.cir_customer_id','=',$customerData->customer_id)
						->where('customer_invoicing_rates.cir_division_id','=',$division_id)
						->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
						->first();
			
			//If Product ID is not null,then Customer Wise Product
			if(!empty($invoicingData->cir_c_product_id)){
				$invoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id','=',$invoicingTypeId)
						->where('customer_invoicing_rates.cir_city_id','=',$customer_city)
						->where('customer_invoicing_rates.cir_customer_id','=',$customerData->customer_id)
						->where('customer_invoicing_rates.cir_c_product_id','=',$orderData->sample_description_id)
						->where('customer_invoicing_rates.cir_division_id','=',$division_id)
						->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
						->first();					
			}				
			$sellingPriceAmount = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
		}else if($invoicingTypeId == '4'){		//Customer Wise Parameters
		    
		    //getting order parameters of a customers
		    $orderParametersDetail = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->whereNotNull('equipment_type_id')->get();
		    
		    if($product_category_id == '2'){
			$sellingPriceAmount = $this->getCustomerWiseAssayParameterRates($invoicingTypeId,$customerData->customer_id,$division_id,$product_category_id,$models->convertObjectToArray($orderParametersDetail));
		    }else{
			$sellingPriceAmount = $this->getCustomerWiseParameterRates($invoicingTypeId,$customerData->customer_id,$division_id,$product_category_id,$models->convertObjectToArray($orderParametersDetail));
		    }			    
		}
	}
	
	//echo '<pre>';print_r($sellingPriceAmount);die;
	return $sellingPriceAmount;
    }
    
    /**
    * Get Customer Wise Parameter Rates
    * Date : 12-April-2018
    * Author : Praveen Singh
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getCustomerWiseParameterRates($invoicing_type_id,$customer_id,$division_id,$product_category_id,$orderParametersDetail){

	global $order,$models;

	$parameterWiseRateData = array();
	$invoicingRate 	       = 0;

	if(!empty($orderParametersDetail)){
	    foreach($orderParametersDetail as $key => $orderParameters){
		$isInvoicingNeeded = DB::table('test_parameter')->where('test_parameter.test_parameter_id',$orderParameters['test_parameter_id'])->where('test_parameter.test_parameter_invoicing','1')->first();
		if(!empty($isInvoicingNeeded) && !empty($orderParameters['test_parameter_id']) && !empty($orderParameters['equipment_type_id'])){
		    $parameterWiseRate = DB::table('customer_invoicing_rates')
			    ->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
			    ->where('customer_invoicing_rates.cir_customer_id','=',$customer_id)
			    ->where('customer_invoicing_rates.cir_parameter_id','=',$orderParameters['test_parameter_id'])
			    ->where('customer_invoicing_rates.cir_equipment_type_id','=',$orderParameters['equipment_type_id'])
			    ->where('customer_invoicing_rates.cir_division_id','=',$division_id)
			    ->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
			    ->first();
		    $parameterWiseRateData[$orderParameters['test_parameter_id']] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
		}
	    }
	    $invoicingRate = in_array('0',$parameterWiseRateData) ? '0' : array_sum($parameterWiseRateData);
	}
	return $invoicingRate;
    }

    /**
    * Get Customer Wise Assay Parameter Rates
    * Date : 12-April-2018
    * Author : Praveen Singh
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getCustomerWiseAssayParameterRates($invoicing_type_id,$customer_id,$division_id,$product_category_id,$orderParametersDetail){

	global $order,$models;
	
	$parameterWiseRateData = $paramterInvoicingWithCount = $withDectorsTestCategory = $withDectorsAssayCategory = $withoutDectorsTestCategory = $withoutDectorsTestParentCategory = $withoutDectorsAssayCategory = $withoutDectorsTestCategory = $noOfInjectionWithDectorsCategory = $withDectorsTestCategoryInfo = $withDectorsAssayCategoryInfo = $withoutDectorsAssayCategoryInfo = $withoutDectorsTestParentCategoryInfo = array();
	$invoicingRate 	       = 0;

	if(!empty($orderParametersDetail)){
	    foreach($orderParametersDetail as $key => $values){
		
		$subValues = $models->convertObjectToArray(DB::table('order_parameters_detail')
			    ->select('parentProductCategoryDB.p_category_id as product_category_id','productCategoryDB.p_category_id','subProductCategoryDB.p_category_id as sub_p_category_id','test_parameter_categories.test_para_cat_id as test_parameter_category_id','test_parameter.test_parameter_invoicing_parent_id')
			    ->join('product_test_dtl','product_test_dtl.product_test_dtl_id','order_parameters_detail.product_test_parameter')
			    ->join('product_test_hdr','product_test_dtl.test_id','product_test_hdr.test_id')
			    ->join('product_master','product_master.product_id','product_test_hdr.product_id')
			    ->join('product_categories as subProductCategoryDB','subProductCategoryDB.p_category_id','product_master.p_category_id')
			    ->join('product_categories as productCategoryDB','productCategoryDB.p_category_id','subProductCategoryDB.parent_id')
			    ->join('product_categories as parentProductCategoryDB','parentProductCategoryDB.p_category_id','productCategoryDB.parent_id')
			    ->join('test_parameter','test_parameter.test_parameter_id','order_parameters_detail.test_parameter_id')
			    ->join('test_parameter_categories','test_parameter_categories.test_para_cat_id','test_parameter.test_parameter_category_id')
			    ->where('order_parameters_detail.order_id',$values['order_id'])
			    ->where('order_parameters_detail.product_test_parameter',$values['product_test_parameter'])
			    ->where('order_parameters_detail.test_parameter_id',$values['test_parameter_id'])
			    ->first());
		
		//Merging Values and Sub Vaules
		$orderParameters = !empty($subValues) ? array_merge($values,$subValues) : $values;
		if(!empty($orderParameters)){
		    //Checking the global Invoicing allowed to the parameters
		    $isInvoicingNeeded = DB::table('test_parameter')->where('test_parameter.test_parameter_id',$orderParameters['test_parameter_id'])->where('test_parameter.test_parameter_invoicing','1')->first();
		    if(!empty($isInvoicingNeeded) && !empty($orderParameters['test_parameter_id']) && !empty($orderParameters['equipment_type_id'])){
			if(!empty($orderParameters['detector_id']) && !empty($orderParameters['running_time_id']) && !empty($orderParameters['no_of_injection'])){	//checking If Detector,Running Time,no of Injection exist
			    if(!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1'){
				if(!empty($orderParameters['test_parameter_invoicing_parent_id'])){
				    $groupedColoumName = $orderParameters['test_parameter_category_id'].'-'.$orderParameters['test_parameter_invoicing_parent_id'].'-'.$orderParameters['product_category_id'].'-'.$orderParameters['p_category_id'].'-'.$orderParameters['sub_p_category_id'].'-'.$orderParameters['equipment_type_id'].'-'.$orderParameters['detector_id'].'-'.$orderParameters['running_time_id'];
				    $noOfInjectionWithDectorsCategory[$groupedColoumName][] 	= $orderParameters['no_of_injection'];
				    $orderParameters['no_of_per_injection']    	   		= '1';
				    $withDectorsTestCategory[$groupedColoumName][] 		= $orderParameters;
				}else{
				    $withoutDectorsTestCategory[$orderParameters['product_test_parameter']] = $orderParameters;
				}
			    }else{
				$groupedColoumName = $orderParameters['test_parameter_category_id'].'-'.$orderParameters['product_category_id'].'-'.$orderParameters['p_category_id'].'-'.$orderParameters['sub_p_category_id'].'-'.$orderParameters['equipment_type_id'].'-'.$orderParameters['detector_id'].'-'.$orderParameters['running_time_id'];
				$noOfInjectionWithDectorsCategory[$groupedColoumName][] 	= $orderParameters['no_of_injection'];
				$orderParameters['no_of_per_injection']     			= '1';
				$withDectorsAssayCategory[$groupedColoumName][] 		= $orderParameters;
			    }
			}else{
			    if(!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1'){
				if(!empty($orderParameters['test_parameter_invoicing_parent_id'])){
				    $groupedColoumName = $orderParameters['test_parameter_category_id'].'-'.$orderParameters['test_parameter_invoicing_parent_id'].'-'.$orderParameters['product_category_id'].'-'.$orderParameters['p_category_id'].'-'.$orderParameters['sub_p_category_id'].'-'.$orderParameters['equipment_type_id'];
				    $withoutDectorsTestParentCategory[$groupedColoumName][] = $orderParameters;
				}else{
				    $withoutDectorsTestCategory[$orderParameters['product_test_parameter']] = $orderParameters;
				}
			    }else{
				$groupedColoumName = $orderParameters['test_parameter_category_id'].'-'.$orderParameters['product_category_id'].'-'.$orderParameters['p_category_id'].'-'.$orderParameters['sub_p_category_id'].'-'.$orderParameters['equipment_type_id'];
				$withoutDectorsAssayCategory[$groupedColoumName][] = $values;
			    }
			}
		    }
		}
	    }

	    //Calculating Rates of Test Parameter Category with Detector,Running Time,no of Injection
	    if(!empty($withDectorsTestCategory)){
		foreach($withDectorsTestCategory as $nestedkeyWithIds => $values){
		    $withDectorsTestCategoryInfo[$nestedkeyWithIds]['equipment_count'] 		= is_array($values) ? count($values) : 0;
		    $withDectorsTestCategoryInfo[$nestedkeyWithIds]['total_injection_count'] 	= isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
		    $withDectorsTestCategoryInfo[$nestedkeyWithIds]['invoicing'] 		= current($values);
		}		
		foreach($withDectorsTestCategoryInfo as $nestedkeyWithIds => $values){
		    $keyTestData 				= $models->getExplodedData($nestedkeyWithIds,'-');
		    $test_parameter_category_id 		= !empty($keyTestData[0]) ? $keyTestData[0] : '0';
		    $test_parameter_invoicing_parent_id 	= !empty($keyTestData[1]) ? $keyTestData[1] : '0';
		    $testParameterInvoicingParentData 		= DB::table('test_parameter_invoicing_parents')->where('test_parameter_invoicing_parents.tpip_id',$test_parameter_invoicing_parent_id)->first();
		    $product_category_id 			= !empty($keyTestData[2]) ? $keyTestData[2] : '0';
		    $p_category_id 				= !empty($keyTestData[3]) ? $keyTestData[3] : '0';
		    $sub_p_category_id 				= !empty($keyTestData[4]) ? $keyTestData[4] : '0';
		    $equipment_type_id 				= !empty($keyTestData[5]) ? $keyTestData[5] : '0';
		    $detector_id 				= !empty($keyTestData[6]) ? $keyTestData[6] : '0';
		    $running_time_id 				= !empty($keyTestData[7]) ? $keyTestData[7] : '0';
		    $no_of_per_injection 			= !empty($values['invoicing']['no_of_per_injection']) ? trim($values['invoicing']['no_of_per_injection']) : 0;
		    if($test_parameter_invoicing_parent_id == 1){
			$cir_equipment_count			= !empty($values['equipment_count']) && $values['equipment_count'] == '1' ? '1' : '2';
		    }else{
			$cir_equipment_count			= !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
		    }
		    $total_injection_count			= !empty($values['total_injection_count']) ? array_sum($values['total_injection_count']) : '0';
		    $parameterWiseRate = DB::table('customer_invoicing_rates')
			->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
			->where('customer_invoicing_rates.cir_customer_id','=',$customer_id)
			->where('customer_invoicing_rates.cir_division_id','=',$division_id)
			->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
			->where('customer_invoicing_rates.cir_p_category_id','=',$p_category_id)
			->where('customer_invoicing_rates.cir_sub_p_category_id','=',$sub_p_category_id)
			->where('customer_invoicing_rates.cir_test_parameter_category_id','=',$test_parameter_category_id)
			->where('customer_invoicing_rates.cir_parameter_id','=',$testParameterInvoicingParentData->test_parameter_id)
			->where('customer_invoicing_rates.cir_equipment_type_id','=',$equipment_type_id)
			->where('customer_invoicing_rates.cir_equipment_count','=',$cir_equipment_count)
			->where('customer_invoicing_rates.cir_detector_id','=',$detector_id)
			->where('customer_invoicing_rates.cir_running_time_id','=',$running_time_id)
			->where('customer_invoicing_rates.cir_no_of_injection','=',$no_of_per_injection)
			->where('customer_invoicing_rates.cir_is_detector','=','1')
			->first();
		    $parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate * $total_injection_count : '0';
		}
	    }
	    
	    //Calculating Rates of Test Parameter Parent Category without Detector,Running Time,no of Injection
	    if(!empty($withoutDectorsTestParentCategory)){
		foreach($withoutDectorsTestParentCategory as $nestedkeyWithIds => $values){
		    $withoutDectorsTestParentCategoryInfo[$nestedkeyWithIds]['equipment_count'] 	= is_array($values) ? count($values) : 0;
		    $withoutDectorsTestParentCategoryInfo[$nestedkeyWithIds]['total_injection_count'] 	= isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
		    $withoutDectorsTestParentCategoryInfo[$nestedkeyWithIds]['invoicing'] 		= current($values);
		}		
		foreach($withoutDectorsTestParentCategoryInfo as $nestedkeyWithIds => $values){
		    $keyTestData 				= $models->getExplodedData($nestedkeyWithIds,'-');
		    $test_parameter_category_id 		= !empty($keyTestData[0]) ? $keyTestData[0] : '0';
		    $test_parameter_invoicing_parent_id 	= !empty($keyTestData[1]) ? $keyTestData[1] : '0';
		    $testParameterInvoicingParentData 		= DB::table('test_parameter_invoicing_parents')->where('test_parameter_invoicing_parents.tpip_id',$test_parameter_invoicing_parent_id)->first();
		    $product_category_id 			= !empty($keyTestData[2]) ? $keyTestData[2] : '0';
		    $p_category_id 				= !empty($keyTestData[3]) ? $keyTestData[3] : '0';
		    $sub_p_category_id 				= !empty($keyTestData[4]) ? $keyTestData[4] : '0';
		    $equipment_type_id 				= !empty($keyTestData[5]) ? $keyTestData[5] : '0';
		    if($test_parameter_invoicing_parent_id == 1){
			$cir_equipment_count			= !empty($values['equipment_count']) && $values['equipment_count'] == '1' ? '1' : '2';
		    }else{
			$cir_equipment_count			= !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
		    }
		    $parameterWiseRate = DB::table('customer_invoicing_rates')
			->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
			->where('customer_invoicing_rates.cir_customer_id','=',$customer_id)
			->where('customer_invoicing_rates.cir_division_id','=',$division_id)
			->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
			->where('customer_invoicing_rates.cir_p_category_id','=',$p_category_id)
			->where('customer_invoicing_rates.cir_sub_p_category_id','=',$sub_p_category_id)
			->where('customer_invoicing_rates.cir_test_parameter_category_id','=',$test_parameter_category_id)
			->where('customer_invoicing_rates.cir_parameter_id','=',$testParameterInvoicingParentData->test_parameter_id)
			->where('customer_invoicing_rates.cir_equipment_type_id','=',$equipment_type_id)
			->where('customer_invoicing_rates.cir_equipment_count','=',$cir_equipment_count)
			->where('customer_invoicing_rates.cir_is_detector','=','2')
			->first();
		    $parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
		}
	    }

	    //Calculating Rates of Test Parameter Category without Detector,Running Time,no of Injection
	    if(!empty($withoutDectorsTestCategory)){
		foreach($withoutDectorsTestCategory as $key => $values){
		    $parameterWiseRate = DB::table('customer_invoicing_rates')
			    ->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
			    ->where('customer_invoicing_rates.cir_customer_id','=',$customer_id)
			    ->where('customer_invoicing_rates.cir_parameter_id','=',$values['test_parameter_id'])
			    ->where('customer_invoicing_rates.cir_equipment_type_id','=',$values['equipment_type_id'])
			    ->where('customer_invoicing_rates.cir_division_id','=',$division_id)
			    ->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
			    ->first();
		    $parameterWiseRateData[$values['test_parameter_id']] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
		}
	    }

	    //Calculating Rates of Assay Parameter Category with Detector,Running Time,no of Injection
	    if(!empty($withDectorsAssayCategory)){
		foreach($withDectorsAssayCategory as $nestedkeyWithIds => $values){
		    $withDectorsAssayCategoryInfo[$nestedkeyWithIds]['equipment_count'] 	= is_array($values) ? count($values) : 0;
		    $withDectorsAssayCategoryInfo[$nestedkeyWithIds]['total_injection_count'] 	= isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
		    $withDectorsAssayCategoryInfo[$nestedkeyWithIds]['invoicing'] 		= current($values);
		}
		foreach($withDectorsAssayCategoryInfo as $nestedkeyWithIds => $values){
		    $keyAssayData 				= $models->getExplodedData($nestedkeyWithIds,'-');
		    $test_parameter_category_id 		= !empty($keyAssayData[0]) ? $keyAssayData[0] : '0';
		    $product_category_id 			= !empty($keyAssayData[1]) ? $keyAssayData[1] : '0';
		    $p_category_id 				= !empty($keyAssayData[2]) ? $keyAssayData[2] : '0';
		    $sub_p_category_id 				= !empty($keyAssayData[3]) ? $keyAssayData[3] : '0';
		    $equipment_type_id 				= !empty($keyAssayData[4]) ? $keyAssayData[4] : '0';
		    $detector_id 				= !empty($keyAssayData[5]) ? $keyAssayData[5] : '0';
		    $running_time_id 				= !empty($keyAssayData[6]) ? $keyAssayData[6] : '0';
		    $no_of_per_injection 			= !empty($values['invoicing']['no_of_per_injection']) ? trim($values['invoicing']['no_of_per_injection']) : 0;
		    $cir_equipment_count			= !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
		    $total_injection_count			= !empty($values['total_injection_count']) ? array_sum($values['total_injection_count']) : '0';
		    $parameterWiseRate = DB::table('customer_invoicing_rates')
			->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
			->where('customer_invoicing_rates.cir_customer_id','=',$customer_id)
			->where('customer_invoicing_rates.cir_division_id','=',$division_id)
			->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
			->where('customer_invoicing_rates.cir_p_category_id','=',$p_category_id)
			->where('customer_invoicing_rates.cir_sub_p_category_id','=',$sub_p_category_id)
			->where('customer_invoicing_rates.cir_test_parameter_category_id','=',$test_parameter_category_id)
			->where('customer_invoicing_rates.cir_equipment_type_id','=',$equipment_type_id)
			->where('customer_invoicing_rates.cir_equipment_count','=',$cir_equipment_count)
			->where('customer_invoicing_rates.cir_detector_id','=',$detector_id)
			->where('customer_invoicing_rates.cir_running_time_id','=',$running_time_id)
			->where('customer_invoicing_rates.cir_no_of_injection','=',$no_of_per_injection)
			->where('customer_invoicing_rates.cir_is_detector','=','1')
			->first();
		    $parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate * $total_injection_count : '0';
		}
	    }

	    //Calculating Rates of Assay Parameter Category without Detector,Running Time,no of Injection
	    if(!empty($withoutDectorsAssayCategory)){
		foreach($withoutDectorsAssayCategory as $nestedkeyWithIds => $values){
		    $withoutDectorsAssayCategoryInfo[$nestedkeyWithIds]['equipment_count'] 	 = is_array($values) ? count($values) : 0;
		    $withoutDectorsAssayCategoryInfo[$nestedkeyWithIds]['total_injection_count'] = isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
		    $withoutDectorsAssayCategoryInfo[$nestedkeyWithIds]['invoicing'] 		 = current($values);
		}
		foreach($withoutDectorsAssayCategoryInfo as $nestedkeyWithIds => $values){
		    $keyWDAssayData 				= $models->getExplodedData($nestedkeyWithIds,'-');
		    $test_parameter_category_id 		= !empty($keyWDAssayData[0]) ? $keyWDAssayData[0] : '0';
		    $product_category_id 			= !empty($keyWDAssayData[1]) ? $keyWDAssayData[1] : '0';
		    $p_category_id 				= !empty($keyWDAssayData[2]) ? $keyWDAssayData[2] : '0';
		    $sub_p_category_id 				= !empty($keyWDAssayData[3]) ? $keyWDAssayData[3] : '0';
		    $equipment_type_id 				= !empty($keyWDAssayData[4]) ? $keyWDAssayData[4] : '0';
		    $no_of_per_injection 			= !empty($values['invoicing']['no_of_per_injection']) ? trim($values['invoicing']['no_of_per_injection']) : 0;
		    $cir_equipment_count			= !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
		    $parameterWiseRate = DB::table('customer_invoicing_rates')
			->where('customer_invoicing_rates.invoicing_type_id','=',$invoicing_type_id)
			->where('customer_invoicing_rates.cir_customer_id','=',$customer_id)
			->where('customer_invoicing_rates.cir_division_id','=',$division_id)
			->where('customer_invoicing_rates.cir_product_category_id','=',$product_category_id)
			->where('customer_invoicing_rates.cir_p_category_id','=',$p_category_id)
			->where('customer_invoicing_rates.cir_sub_p_category_id','=',$sub_p_category_id)
			->where('customer_invoicing_rates.cir_test_parameter_category_id','=',$test_parameter_category_id)
			->where('customer_invoicing_rates.cir_equipment_type_id','=',$equipment_type_id)
			->where('customer_invoicing_rates.cir_equipment_count','=',$cir_equipment_count)
			->where('customer_invoicing_rates.cir_is_detector','=','2')
			->first();
		    $parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
		}
	    }

	    //echo '<pre>';print_r($parameterWiseRateData);die;
	    $invoicingRate = in_array('0',$parameterWiseRateData) ? '0' : array_sum($parameterWiseRateData);
	}
	return $invoicingRate;
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getCustomerSampleCountAmount($values,$submitedData){
	
	$sampleCount  = '0';
	$sampleAmount = '0.00';
	
	$customerSampleCountAmountObj = DB::table('order_master')
		->select('order_master.order_id','order_master.order_no','order_master.customer_id')
		->whereBetween(DB::raw("DATE(order_master.order_date)"), array($submitedData['date_from'], $submitedData['date_to']))
		->where('order_master.customer_id',$values->customer_id);
		
	if(!empty($submitedData['division_id'])){
	    $customerSampleCountAmountObj->where('order_master.division_id',$submitedData['division_id']);
	}
	if(!empty($submitedData['product_category_id'])){
	    $customerSampleCountAmountObj->where('order_master.product_category_id',$submitedData['product_category_id']);
	}
	$customerSampleCountAmount = $customerSampleCountAmountObj->get();

	if(!empty($customerSampleCountAmount)){
		$sampleCount = count($customerSampleCountAmount);
		foreach($customerSampleCountAmount as $key => $sampleCountAmount){
		    $returnSampleAmount[$key] = $this->getBookedSamplePrice($sampleCountAmount->customer_id, $sampleCountAmount->order_id);
		}
		$sampleAmount = round(array_sum($returnSampleAmount),2);
	}
		
	return array($sampleCount,$sampleAmount);
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getPlaceWiseSampleCountAmount($values,$submitedData){
	
	$sampleCount  = '0';
	$sampleAmount = '0.00';
	
	$placeWiseSampleCountAmountObj = DB::table('order_master')
			->join('customer_master','customer_master.customer_id','order_master.customer_id')
			->join('city_db','city_db.city_id','customer_master.customer_city')
			->join('state_db','state_db.state_id','customer_master.customer_state')
			->select('order_master.order_id','order_master.order_no','order_master.customer_id')
			->whereBetween(DB::raw("DATE(order_master.order_date)"),  array($submitedData['date_from'], $submitedData['date_to']))
			->where('customer_master.customer_city',$values->customer_city);
			
	if(!empty($submitedData['division_id'])){
	    $placeWiseSampleCountAmountObj->where('order_master.division_id',$submitedData['division_id']);
	}
	if(!empty($submitedData['product_category_id'])){
	    $placeWiseSampleCountAmountObj->where('order_master.product_category_id',$submitedData['product_category_id']);
	}
	$placeWiseSampleCountAmount = $placeWiseSampleCountAmountObj->get();
					
	if(!empty($placeWiseSampleCountAmount)){
	    $sampleCount = count($placeWiseSampleCountAmount);
	    foreach($placeWiseSampleCountAmount as $key => $sampleCountAmount){
		$returnSampleAmount[$key] = $this->getBookedSamplePrice($sampleCountAmount->customer_id, $sampleCountAmount->order_id);
	    }
	    $sampleAmount = round(array_sum($returnSampleAmount),2);
	}
	
	return array($sampleCount,$sampleAmount);
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function get_modification_count($opl_user_id,$opl_order_id,$opl_order_status_id){
	$modificationCount = DB::table('order_process_log')->where(['order_process_log.opl_user_id' => $opl_user_id,'order_process_log.opl_order_id' => $opl_order_id,'order_process_log.opl_order_status_id' => $opl_order_status_id])->count();
	return $modificationCount > '1' ? $modificationCount - 1 : '0';
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function get_order_stage_date($opl_order_id,$opl_order_status_id){
	
	$data = DB::table('order_process_log')
		->select('order_process_log.opl_id','order_process_log.opl_order_id','order_process_log.opl_date')
		->where(['order_process_log.opl_order_id' => $opl_order_id,'order_process_log.opl_order_status_id' => $opl_order_status_id])
		->orderBy('order_process_log.opl_id','DESC')
		->limit(1)
		->first();
		
	return !empty($data->opl_date) ? $data->opl_date : '0';
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function checkEquipmentAllotStatus($order_id,$equipmentId){
	
	$returnData = array();
	$status     = '';
	
	//checking Order is scheduled or not
	$orderUnscheduled   = DB::table('schedulings')->where(['schedulings.order_id' => $order_id,'schedulings.status' => '0'])->first();
	$equipmentExistData = DB::table('order_parameters_detail')->where(['order_parameters_detail.order_id' => $order_id,'order_parameters_detail.equipment_type_id' => $equipmentId])->get();
	if(empty($orderUnscheduled) && !empty($equipmentExistData)){
	    foreach($equipmentExistData as $key => $equipmentData){
		$data = DB::table('schedulings')->where(['schedulings.order_id' => $equipmentData->order_id,'schedulings.order_parameter_id' => $equipmentData->analysis_id,'schedulings.equipment_type_id' => $equipmentData->equipment_type_id,'schedulings.status' => '3'])->first();
		$returnData[$key] = !empty($data) ? '1' : '0';
	    }
	    if(!empty($returnData)){
		$status = in_array('0',$returnData) ? 'P' : 'C';
	    }
	}
	return $status;
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function checkEquipmentAllotStatusOthers($order_id,$allowedEquipmentIds){
	//checking Order is scheduled or not
	$orderUnscheduled = DB::table('schedulings')->where(['schedulings.order_id' => $order_id,'schedulings.status' => '0'])->first();
	$data 		  = DB::table('schedulings')->where('schedulings.order_id',$order_id)->where('schedulings.status','<>', '3')->whereNotIn('schedulings.equipment_type_id',$allowedEquipmentIds)->first();
        return empty($orderUnscheduled) && !empty($data) ? 'P' : '';
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function checkTestingStatusOfOrder($order_id){
	
	$status = '';
	
	//checking Order is scheduled or not
	$orderUnscheduled  = DB::table('schedulings')->where(['schedulings.order_id' => $order_id,'schedulings.status' => '0'])->first();
	$data 		   = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id',$order_id)->whereNull('order_parameters_detail.test_result')->first();
	if(empty($orderUnscheduled)){
	    $status = empty($data) ? 'C' : 'P';
	}
	return $status;
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function checkScheduledStatusOfOrder($order_id){
	$orderDetail  = DB::table('order_master')->where('order_master.order_id',$order_id)->where('order_master.status','12')->first();
	if(!empty($orderDetail)){
	    return 'H';
	}else{
	    $data = DB::table('schedulings')->where(['schedulings.order_id' => $order_id,'schedulings.status' => '0'])->first();
	    return empty($data) ? 'C' : 'P';
	}	
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getSampleLogResultant($type,$divisionId,$departmentId,$postedData){
	
	if(!empty($type) && !empty($divisionId) && !empty($departmentId) && !empty($postedData)){
	    
	    $currentDate = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
	    $fromDate    = $postedData['date_from'];
	    $toDate      = $postedData['date_to'];
	    
	    if($type == '1'){		//No. of Packet Received
		return DB::table('samples')
		    ->where('samples.division_id',$divisionId)
		    ->where('samples.product_category_id',$departmentId)
		    ->whereBetween(DB::raw("DATE(samples.sample_current_date)"), array($fromDate, $toDate))
		    ->count();
	    }else if($type == '2'){	//No. of Packet Booked
		return DB::table('samples')
		    ->where('samples.division_id',$divisionId)
		    ->where('samples.product_category_id',$departmentId)
		    ->whereBetween(DB::raw("DATE(samples.sample_booked_date)"), array($fromDate, $toDate))
		    ->count();
	    }else if($type == '3'){	//No. of Sample Booked
		return DB::table('order_master')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->whereBetween(DB::raw("DATE(order_master.booking_date)"),array($fromDate, $toDate))
		    ->count();
	    }else if($type == '4'){	//No. of Sample Hold
		return count(DB::table('order_process_log')
		    ->join('order_master','order_master.order_id','order_process_log.opl_order_id')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->where('order_master.status','<>','10')
		    ->whereBetween(DB::raw("DATE(order_process_log.opl_date)"),array($fromDate, $toDate))
		    ->where('order_process_log.opl_order_status_id','=','12')
		    ->where('order_process_log.opl_amend_status','=','0')
		    ->groupBy('order_process_log.opl_order_id')
		    ->get());
	    }else if($type == '5'){	//No. of Samples Scheduled
		return DB::table('order_master')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->whereBetween(DB::raw("DATE(order_master.order_scheduled_date)"),array($fromDate, $toDate))
		    ->count();
	    }else if($type == '6'){	//No. of Samples Analyzed
		return DB::table('order_master')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereBetween(DB::raw("DATE(order_master.test_completion_date)"),array($fromDate, $toDate))
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->count();
	    }else if($type == '7'){	//No. of Samples Reviewed
		return DB::table('order_master')
		    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereBetween(DB::raw("DATE(order_report_details.reviewing_date)"),array($fromDate, $toDate))
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->count();
	    }else if($type == '8'){	//No. of Samples Approved
		return DB::table('order_master')
		    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereBetween(DB::raw("DATE(order_report_details.approving_date)"),array($fromDate, $toDate))
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->count();
	    }else if($type == '9'){	//No. of Sample Emailed
		return DB::table('order_master')
		    ->join('order_mail_dtl','order_mail_dtl.order_id','order_master.order_id')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereBetween(DB::raw("DATE(order_mail_dtl.mail_date)"), array($fromDate, $toDate))
		    ->where('order_mail_dtl.mail_content_type','=','3')
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->where('order_mail_dtl.mail_status','=','1')
		    ->where('order_mail_dtl.mail_active_type','=','1')
		    ->count();
	    }else if($type == '10'){	//No. of report Dispatched
		return DB::table('order_master')
		    ->join('order_dispatch_dtl','order_dispatch_dtl.order_id','order_master.order_id')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereBetween(DB::raw("DATE(order_dispatch_dtl.dispatch_date)"), array($fromDate, $toDate))
		    ->where('order_dispatch_dtl.amend_status','=','0')
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->count();
	    }else if($type == '11'){	//No. of report Invoiced
		return DB::table('invoice_hdr_detail')
		    ->join('invoice_hdr','invoice_hdr.invoice_id','invoice_hdr_detail.invoice_hdr_id')
		    ->where('invoice_hdr.division_id',$divisionId)
		    ->where('invoice_hdr.product_category_id',$departmentId)
		    ->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"),array($fromDate, $toDate))
		    ->where('invoice_hdr.invoice_status','=','1')
		    ->count();
	    }else if($type == '12'){	//No of Report Due		
		return DB::table('order_master')
		    ->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->where(DB::raw("DATE(order_master.expected_due_date)"),'=',$toDate)
		    ->where(function($query) use ($toDate) {
			$query->whereNull('order_report_details.approving_date');
			$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"),'>',$toDate);			    
		    })
		    ->count();
	    }else if($type == '13'){	//No. of Report Overdue
		return DB::table('order_master')
		    ->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->where(DB::raw("DATE(order_master.expected_due_date)"),'<',$toDate)
		    ->where(function($query) use ($toDate) {
			$query->whereNull('order_report_details.approving_date');
			$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"),'>',$toDate);			    
		    })
		    ->count();
	    }else if($type == '14'){	//No. of Invoice Pending
		return DB::table('order_master')
		    ->join('order_report_details','order_report_details.report_id','order_master.order_id')
		    ->leftJoin('invoice_hdr_detail','order_report_details.report_id','invoice_hdr_detail.order_id')
		    ->leftJoin('invoice_hdr','invoice_hdr.invoice_id','invoice_hdr_detail.invoice_hdr_id')	
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->where(DB::raw("DATE(order_report_details.approving_date)"),'<=',$toDate)
		    ->where(function($query) use ($toDate) {
			$query->whereNull('invoice_hdr.invoice_date');
			$query->orWhere(DB::raw("DATE(invoice_hdr.invoice_date)"),'>',$toDate);			    
		    })
		    ->count();		
	    }else if($type == '15'){	//No. of Report Pending
		return DB::table('order_master')
		    ->leftJoin('order_report_details','order_report_details.report_id','order_master.order_id')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->whereNotIn('order_master.status',array('10','12'))
		    ->whereNull('order_report_details.approving_date')
		    ->count();		
	    }
	}
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function getDateWisePartySampleCount($values,$dateRangeData,$submitedData){	
	foreach($dateRangeData as $key => $dateRange){
	    $dateCountObj = DB::table('order_master')
		->join('divisions','divisions.division_id','order_master.division_id')
		->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
		->join('departments','departments.department_id','department_product_categories_link.department_id')
		->join('customer_master','customer_master.customer_id','order_master.customer_id')
		->join('city_db','city_db.city_id','customer_master.customer_city')
		->join('state_db','state_db.state_id','customer_master.customer_state')
		->where(DB::raw("DATE(order_master.order_date)"),$dateRange)
		->where('order_master.customer_id',$values->customer_id)
		->where('order_master.customer_city',$values->customer_city);
		if(!empty($submitedData['division_id'])){
		    $dateCountObj->where('order_master.division_id',$submitedData['division_id']);
		}
		if(!empty($submitedData['product_category_id'])){
		    $dateCountObj->where('order_master.product_category_id',$submitedData['product_category_id']);
		}
	    $dateRangeColumn = date(DATEFORMAT,strtotime($dateRange));
	    $values->$dateRangeColumn = $dateCountObj->count();
	}
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function getMonthWisePartySampleCount($values,$monthRangeData,$submitedData){
	foreach($monthRangeData as $key => $monthRanges){
	    $orderMonth 	= date('m',strtotime($monthRanges));
	    $orderYear  	= date('Y',strtotime($monthRanges));
	    $monthRange    	= date('m-Y',strtotime($monthRanges));
	    $monthCountObj 	= DB::table('order_master')
		->join('divisions','divisions.division_id','order_master.division_id')
		->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
		->join('departments','departments.department_id','department_product_categories_link.department_id')
		->join('customer_master','customer_master.customer_id','order_master.customer_id')
		->join('city_db','city_db.city_id','customer_master.customer_city')
		->join('state_db','state_db.state_id','customer_master.customer_state')
		->whereMonth('order_master.order_date',$orderMonth)
		->whereYear('order_master.order_date',$orderYear)
		->where('order_master.customer_id',$values->customer_id)
		->where('order_master.customer_city',$values->customer_city);
		if(!empty($submitedData['division_id'])){
		    $monthCountObj->where('order_master.division_id',$submitedData['division_id']);
		}
		if(!empty($submitedData['product_category_id'])){
		    $monthCountObj->where('order_master.product_category_id',$submitedData['product_category_id']);
		}
	    $values->$monthRange = $monthCountObj->count();
	}
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function getTableFooterData($thDate,$submitedData,$type='1'){
	
	global $order,$models,$misReport;
	
	$returnData = array();
	
	if(!empty($thDate)){
	    foreach($thDate as $key => $thDateValue){
		if($type == '1'){
		    if($key > '3'){
			$returnDataObj = DB::table('order_master')
			    ->join('divisions','divisions.division_id','order_master.division_id')
			    ->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
			    ->join('departments','departments.department_id','department_product_categories_link.department_id')
			    ->join('customer_master','customer_master.customer_id','order_master.customer_id')
			    ->join('city_db','city_db.city_id','customer_master.customer_city')
			    ->join('state_db','state_db.state_id','customer_master.customer_state')
			    ->where(DB::raw("DATE(order_master.order_date)"),$models->getFormatedDate($thDateValue));
			    if(!empty($submitedData['division_id'])){
				$returnDataObj->where('order_master.division_id',$submitedData['division_id']);
			    }
			    if(!empty($submitedData['product_category_id'])){
				$returnDataObj->where('order_master.product_category_id',$submitedData['product_category_id']);
			    }
			    $returnData[$thDateValue] = $returnDataObj->count();
		    }else{
			$returnData[$thDateValue] = '';
		    }  
		}else if($type == '2'){
		    if($key > '3'){
			$orderMonthYear 	  = explode('-',$thDateValue);
			$orderMonth 		  = !empty($orderMonthYear[0]) ? $orderMonthYear[0] : '0';
			$orderYear 		  = !empty($orderMonthYear[1]) ? $orderMonthYear[1] : '0';			
			$returnDataObj = DB::table('order_master')
			    ->join('divisions','divisions.division_id','order_master.division_id')
			    ->join('department_product_categories_link','department_product_categories_link.product_category_id','order_master.product_category_id')
			    ->join('departments','departments.department_id','department_product_categories_link.department_id')
			    ->join('customer_master','customer_master.customer_id','order_master.customer_id')
			    ->join('city_db','city_db.city_id','customer_master.customer_city')
			    ->join('state_db','state_db.state_id','customer_master.customer_state')
			    ->whereMonth('order_master.order_date',$orderMonth)
			    ->whereYear('order_master.order_date',$orderYear);
			    if(!empty($submitedData['division_id'])){
				$returnDataObj->where('order_master.division_id',$submitedData['division_id']);
			    }
			    if(!empty($submitedData['product_category_id'])){
				$returnDataObj->where('order_master.product_category_id',$submitedData['product_category_id']);
			    }		    
			    $returnData[$thDateValue] = $returnDataObj->count();
		    }else{
			$returnData[$thDateValue] = '';
		    }
		}else if($type == '3'){
		    if($key > '4'){
			if($thDateValue == 'sample_count'){
			    $returnData[$thDateValue] = array_sum($submitedData['totalSample']);
			}
			if($thDateValue == 'sample_amount'){
			    $returnData[$thDateValue] = number_format((float) array_sum($submitedData['totalAmount']),2, '.', '');
			}			
		    }else{
			$returnData[$thDateValue] = '';
		    }		    
		}else if($type == '4'){
		    if($key > '3'){
			if($thDateValue == 'sample_count'){
			    $returnData[$thDateValue] = array_sum($submitedData['totalSample']);
			}
			if($thDateValue == 'sample_amount'){
			    $returnData[$thDateValue] = number_format((float) array_sum($submitedData['totalAmount']),2, '.', '');
			}			
		    }else{
			$returnData[$thDateValue] = '';
		    }
		}else if($type == '5'){
		    if($key > '11'){
			if($thDateValue == 'supplied_by'){
			    $returnData[$thDateValue] = 'Total Revenue';
			}
			if($thDateValue == 'revenue_amount'){
			    $returnData[$thDateValue] = number_format((float) array_sum($submitedData['revenue_amount']),2, '.', '');
			}			
		    }else{
			$returnData[$thDateValue] = '';
		    }		    
		}else if($type == '6'){
		    if($key > '15'){
			if($thDateValue == 'sample_amount'){
			    $returnData[$thDateValue] = number_format((float) array_sum($submitedData['sample_amount']), 2, '.', '');
			}
			if($thDateValue == 'invoice_amount'){
			    $returnData[$thDateValue] = number_format((float) array_sum($submitedData['invoice_amount']), 2, '.', '');
			}			
		    }else{
			$returnData[$thDateValue] = '';
		    }		    
		}else if($type == '7'){
		    if($key > '2'){
			if($thDateValue == 'equipment'){
			    $returnData[$thDateValue] = 'Total';
			}
			if($thDateValue == 'opening_pending'){
			    $returnData[$thDateValue] = array_sum($submitedData['total_opening_pending']);
			}
			if($thDateValue == 'pending'){
			    $returnData[$thDateValue] = array_sum($submitedData['total_pending']);
			}
			if($thDateValue == 'allocated'){
			    $returnData[$thDateValue] = array_sum($submitedData['total_allocated']);
			}
			if($thDateValue == 'completed'){
			    $returnData[$thDateValue] = array_sum($submitedData['total_completed']);
			}
			if($thDateValue == 'over_due'){
			    $returnData[$thDateValue] = array_sum($submitedData['total_over_due']);
			}
			if($thDateValue == 'not_due'){
			    $returnData[$thDateValue] = array_sum($submitedData['total_not_due']);
			}
			if($thDateValue == 'closing'){
			    $returnData[$thDateValue] = array_sum($submitedData['total_closing']);
			}
		    }else{
			$returnData[$thDateValue] = '';
		    }		    
		}else if($type == '8'){
		    if($key > '10'){
			if($thDateValue == 'TAT'){
			    $returnData[$thDateValue] = 'Sum of Yes | No';
			}
			if($thDateValue == 'within_due_date'){
			    $returnData[$thDateValue] = count(array_filter($submitedData['sum_of_yes'])).' | '.count(array_filter($submitedData['sum_of_no']));
			}
			if($thDateValue == 'days_delay'){
			    $returnData[$thDateValue] = 'Total Errors';
			}
			if($thDateValue == 'no_of_errors'){
			    $returnData[$thDateValue] = array_sum(array_filter($submitedData['no_of_error_count']));
			}
		    }else{
			$returnData[$thDateValue] = '';
		    }		    
		}else if($type == '9'){
		    if($key > '8'){
			if($thDateValue == 'amended_by'){
			    $returnData[$thDateValue] = 'Total Amended';
			}
			if($thDateValue == 'amendment_count'){
			    $returnData[$thDateValue] = count(array_filter($submitedData['total_amendment_count']));
			}
		    }else{
			$returnData[$thDateValue] = '';
		    }
		}else if($type == '10'){
		    if($key > '1'){
			if($thDateValue == 'date'){
			    $returnData[$thDateValue] = 'Total';
			}
			if($thDateValue){
			    $returnData[$thDateValue] = round(array_sum(array_filter($submitedData[$thDateValue])),2);
			}
		    }else{
			$returnData[$thDateValue] = '';
		    }		    
		}
	    }
	}
	return array($returnData);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function getPendingEquipmentCount($date,$division,$department,$equipmentType,$type){
	if($type == '1'){		//Openning Pending Count
	    return DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('order_master.division_id',$division->division_id)
		->where('order_master.product_category_id',$department->p_category_id)
		->where('schedulings.equipment_type_id',$equipmentType->equipment_id)
		->where(DB::raw("DATE(schedulings.scheduled_at)"),'<', $date)
		->where(function($query) use ($date) {
		    $query->whereNull('schedulings.completed_at');
		    $query->orWhere(DB::raw("DATE(schedulings.completed_at)"),'>=',$date);			    
		})
		->count();
	}else if($type == '2'){		//Pending Count
	    return DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('order_master.division_id',$division->division_id)
		->where('order_master.product_category_id',$department->p_category_id)
		->where('schedulings.equipment_type_id',$equipmentType->equipment_id)
		->where(DB::raw("DATE(schedulings.scheduled_at)"), $date)
		->whereNull('schedulings.completed_at')
		->count();
	}else if($type == '3'){		//Allocated Count
	    return DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('order_master.division_id',$division->division_id)
		->where('order_master.product_category_id',$department->p_category_id)
		->where('schedulings.equipment_type_id',$equipmentType->equipment_id)
		->where(DB::raw("DATE(schedulings.scheduled_at)"), $date)
		->count();
	}else if($type == '4'){		//Completed Count
	    return DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('order_master.division_id',$division->division_id)
		->where('order_master.product_category_id',$department->p_category_id)
		->where('schedulings.equipment_type_id',$equipmentType->equipment_id)
		->where(DB::raw("DATE(schedulings.completed_at)"), $date)
		->count();
	}else if($type == '5'){		//Over Due Count
	    return DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('order_master.division_id',$division->division_id)
		->where('order_master.product_category_id',$department->p_category_id)
		->where('schedulings.equipment_type_id',$equipmentType->equipment_id)
		->where(DB::raw("DATE(schedulings.completed_at)"), $date)
		->where(DB::raw("DATE(schedulings.completed_at)"),'>',DB::raw("DATE(order_master.expected_due_date)"))
		->count();
	}else if($type == '6'){		//Not Due Count
	    return DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('order_master.division_id',$division->division_id)
		->where('order_master.product_category_id',$department->p_category_id)
		->where('schedulings.equipment_type_id',$equipmentType->equipment_id)
		->where(DB::raw("DATE(schedulings.completed_at)"), $date)
		->where(DB::raw("DATE(schedulings.completed_at)"),'<=',DB::raw("DATE(order_master.expected_due_date)"))
		->count();
	}
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function getEquipmentAllStatus($values,$formData){
	
	global $order,$models;
	
	//*****************Current Date Calculation of Pending*************************	
	$allocatedCurrentObj = DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('schedulings.equipment_type_id',$values->equipment_type_id)
		->where(DB::raw("DATE(order_master.booking_date)"), $models->getFormatedDate($values->booking_date))
		->whereIn('schedulings.status',array('1','2','3'));		
	if(!empty($formData['division_id'])){
	    $allocatedCurrentObj->where('order_master.division_id',$formData['division_id']);
	}
	if(!empty($formData['product_category_id'])){
	    $allocatedCurrentObj->where('schedulings.product_category_id',$formData['product_category_id']);
	}
	$allocatedCurrent = $allocatedCurrentObj->count();
	
	$completedCurrentObj = DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('schedulings.equipment_type_id',$values->equipment_type_id)
		->where(DB::raw("DATE(order_master.booking_date)"), $models->getFormatedDate($values->booking_date))
		->whereNotNull('schedulings.completed_at')
		->where('schedulings.status','3');		
	if(!empty($formData['division_id'])){
	    $completedCurrentObj->where('order_master.division_id',$formData['division_id']);
	}
	if(!empty($formData['product_category_id'])){
	    $completedCurrentObj->where('schedulings.product_category_id',$formData['product_category_id']);
	}
	$completedCurrentData = $completedCurrentObj->count();
	
	//calculating Total
	$allocatedCurrentData  = $allocatedCurrent + $completedCurrentData;
	$pendingCurrenData     = $allocatedCurrentData - $completedCurrentData;
	//*****************/Current Date Calculation of Pending*************************	
	
	//*****************Previous Date Calculation of Pending*************************	
	$pendingPreviousDataObj = DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('schedulings.equipment_type_id',$values->equipment_type_id)
		->where(DB::raw("DATE(order_master.booking_date)"),'<=', $models->getFormatedDate($values->previous_date))
		->whereIn('schedulings.status',array('1','2'));	
	if(!empty($formData['division_id'])){
	    $pendingPreviousDataObj->where('order_master.division_id',$formData['division_id']);
	}
	if(!empty($formData['product_category_id'])){
	    $pendingPreviousDataObj->where('order_master.product_category_id',$formData['product_category_id']);
	}
	$pendingPreviousData = $pendingPreviousDataObj->count();
	//*****************Previous Date Calculation of Pending*************************	
	
	//Calculating final Pending
	$openingPending = abs($pendingCurrenData + $pendingPreviousData);	
	$openingPending = !empty($openingPending) && is_int($openingPending) ? $openingPending : 0;
	
	return array($openingPending,$allocatedCurrentData,$completedCurrentData);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function getOverDueEquipment($values,$formData){
	
	global $order,$models;

	$overDueObj = DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->where('schedulings.equipment_type_id',$values->equipment_type_id)
		->where(DB::raw("DATE(order_master.booking_date)"), $models->getFormatedDate($values->booking_date))
		->where(DB::raw("DATE(order_master.report_due_date)"),'<',DB::raw("DATE(schedulings.completed_at)"))
		->where('schedulings.status','3');		
	if(!empty($formData['division_id'])){
	    $overDueObj->where('order_master.division_id',$formData['division_id']);
	}
	if(!empty($formData['product_category_id'])){
	    $overDueObj->where('order_master.product_category_id',$formData['product_category_id']);
	}
	return $overDueObj->count();
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function getRequiredFieldValue($values,$postedData,$type){
	
	global $order,$models;
	
	if($type == '1'){ 		//Total Sample Received(No of Samples allocated during the period)
	    
	    $dataObj = DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->whereBetween(DB::raw("DATE(order_master.order_scheduled_date)"), array($postedData['date_from'], $postedData['date_to']))
		->where('schedulings.employee_id',$values->user_id)
		->where('order_master.status','<>','10')
		->groupBy('schedulings.order_id');	
	    if(!empty($postedData['division_id'])){
		$dataObj->where('order_master.division_id',$postedData['division_id']);
	    }
	    if(!empty($postedData['product_category_id'])){
		$dataObj->where('order_master.product_category_id',$postedData['product_category_id']);
	    }
	    $data = $dataObj->get();
	    return !empty($data) ? count($data) : '0';
	
	}else if($type == '2'){ 	//Total Sample Analysed(No of Samples Completed during the period)
	    
	    $dataObj = DB::table('schedulings')
		->join('order_master','order_master.order_id','schedulings.order_id')
		->whereBetween(DB::raw("DATE(order_master.test_completion_date)"), array($postedData['date_from'], $postedData['date_to']))
		->where('schedulings.employee_id',$values->user_id)
		->where('order_master.status','<>','10')
		->groupBy('schedulings.order_id');		
	    if(!empty($postedData['division_id'])){
		$dataObj->where('order_master.division_id',$postedData['division_id']);
	    }
	    if(!empty($postedData['product_category_id'])){
		$dataObj->where('order_master.product_category_id',$postedData['product_category_id']);
	    }
	    $data = $dataObj->get();
	    return !empty($data) ? count($data) : '0';
	
	}else if($type == '3'){ 	//No of Test Conducted(Test parameters Completed during the period)
	    
	    $dataObj = DB::table('schedulings')
		    ->join('order_master','order_master.order_id','schedulings.order_id')
		    ->whereBetween(DB::raw("DATE(schedulings.completed_at)"), array($postedData['date_from'], $postedData['date_to']))
		    ->where('schedulings.employee_id',$values->user_id)
		    ->where('order_master.status','<>','10');
	    if(!empty($postedData['division_id'])){
		$dataObj->where('order_master.division_id',$postedData['division_id']);
	    }
	    if(!empty($postedData['product_category_id'])){
		$dataObj->where('order_master.product_category_id',$postedData['product_category_id']);
	    }
	    return $dataObj->count();
	
	}else if($type == '4'){ 	//Sample Within TAT
	    
	    $dataObj = DB::table('schedulings')
		    ->join('order_master','order_master.order_id','schedulings.order_id')
		    ->whereBetween(DB::raw("DATE(order_master.test_completion_date)"), array($postedData['date_from'], $postedData['date_to']))
		    ->where(DB::raw("DATE(order_master.test_completion_date)"),'<=',DB::raw("DATE(order_master.expected_due_date)"))
		    ->where('schedulings.employee_id',$values->user_id)
		    ->where('order_master.status','<>','10')
		    ->groupBy('schedulings.order_id');		    
	    if(!empty($postedData['division_id'])){
		$dataObj->where('order_master.division_id',$postedData['division_id']);
	    }
	    if(!empty($postedData['product_category_id'])){
		$dataObj->where('order_master.product_category_id',$postedData['product_category_id']);
	    }
	    $data = $dataObj->get();
	    return !empty($data) ? count($data) : '0';
	
	}else if($type == '5'){ 	//Sample beyond TAT
	    
	    $dataObj = DB::table('order_master')
		    ->join('schedulings','order_master.order_id','schedulings.order_id')
		    ->whereBetween(DB::raw("DATE(order_master.test_completion_date)"), array($postedData['date_from'], $postedData['date_to']))
		    ->where(DB::raw("DATE(order_master.test_completion_date)"),'>',DB::raw("DATE(order_master.expected_due_date)"))
		    ->where('schedulings.employee_id',$values->user_id)
		    ->where('order_master.status','<>','10')
		    ->groupBy('schedulings.order_id');
	    if(!empty($postedData['division_id'])){
		$dataObj->where('order_master.division_id',$postedData['division_id']);
	    }
	    if(!empty($postedData['product_category_id'])){
		$dataObj->where('order_master.product_category_id',$postedData['product_category_id']);
	    }
	    $data = $dataObj->get();
	    return !empty($data) ? count($data) : '0';
	
	}else if($type == '6'){ 	//No of errors
	    
	    $error_parameter_count = array();
	    
	    $orderIdObj = DB::table('schedulings')
		    ->join('order_master','order_master.order_id','schedulings.order_id')
		    ->whereBetween(DB::raw("DATE(schedulings.completed_at)"), array($postedData['date_from'], $postedData['date_to']))
		    ->where('schedulings.employee_id',$values->user_id)
		    ->where('order_master.status','<>','10')
		    ->groupBy('schedulings.order_id');
		    
	    if(!empty($postedData['division_id'])){
		$orderIdObj->where('order_master.division_id',$postedData['division_id']);
	    }
	    if(!empty($postedData['product_category_id'])){
		$orderIdObj->where('order_master.product_category_id',$postedData['product_category_id']);
	    }
	    $orderIds = $orderIdObj->pluck('schedulings.order_id')->all();
		    
	    if(!empty($orderIds)){
		$error_parameter_ids = DB::table('order_process_log')
				      ->whereIn('order_process_log.opl_order_id',array_values($orderIds))
				      ->whereNotNull('order_process_log.error_parameter_ids')
				      ->select('order_process_log.error_parameter_ids')
				      ->where('order_process_log.opl_order_status_id','3')
				      ->whereBetween(DB::raw("DATE(order_process_log.opl_date)"), array($postedData['date_from'], $postedData['date_to']))
				      ->get();
		if(!empty($error_parameter_ids)){
		    foreach($error_parameter_ids as $key => $error_parameter_str){
			$error_parameter_array = array();
			$error_parameter_array = explode(',',$error_parameter_str->error_parameter_ids);
			$error_parameter_count[$key] = DB::table('order_parameters_detail')
						    ->where('order_parameters_detail.test_performed_by',$values->user_id)
						    ->whereIn('order_parameters_detail.analysis_id',$error_parameter_array)
						    ->count();
		    }
		}
	    }	    
	    return array_sum($error_parameter_count);
	}	
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function getNoOfErrorCount($values,$postedData){
	
	$error_parameter_count = array();
	
	$error_parameter_ids = DB::table('order_process_log')
			    ->where('order_process_log.opl_order_id',$values->order_id)
			    ->whereNotNull('order_process_log.error_parameter_ids')
			    ->select('order_process_log.error_parameter_ids')
			    ->where('order_process_log.opl_order_status_id','3')
			    ->whereBetween(DB::raw("DATE(order_process_log.opl_date)"), array($postedData['date_from'], $postedData['date_to']))
			    ->get();
	if(!empty($error_parameter_ids)){
	    foreach($error_parameter_ids as $key => $error_parameter_str){
		$error_parameter_array = array();
		$error_parameter_array = explode(',',$error_parameter_str->error_parameter_ids);
		$error_parameter_count[$key] = DB::table('order_parameters_detail')
					    ->where('order_parameters_detail.test_performed_by',$values->employee_id)
					    ->whereIn('order_parameters_detail.analysis_id',$error_parameter_array)
					    ->where('order_parameters_detail.test_parameter_id',$values->test_parameter_id)
					    ->count();
	    }
	    return array_sum($error_parameter_count);		    
	}
    }
    
    /**sort array in asc order
    *
    * @return \Illuminate\Http\Response
    */
    function filterSearchCriteria($searchCriteria){
	foreach($searchCriteria as $key => $value){    
	    if($key == "division_id"){
		$divisions = DB::table('divisions')->where('divisions.division_id',!empty($value) ? $value : '0')->first();
		$value = !empty($divisions->division_name) ? $divisions->division_name : 'All';
	    }
	    if($key == "product_category_id"){
		$productCategories = DB::table('product_categories')->where('product_categories.p_category_id',!empty($value) ? $value : '0')->first();
		$value = !empty($productCategories->p_category_name) ? $productCategories->p_category_name : 'All';
	    }
	    if($key == "sale_executive_id"){
		$saleExecutive = DB::table('users')->where('users.id',!empty($value) ? $value : '0')->first();
		$value = !empty($saleExecutive->name) ? $saleExecutive->name : 'All';
	    }
	    if($key == "order_status_id"){
		$orderStatus = DB::table('order_status')->where('order_status.order_status_id',!empty($value) ? $value : '0')->first();
		if(in_array('UWPD007',$searchCriteria) || in_array('AWPS013',$searchCriteria)){
		    $value = !empty($orderStatus->order_status_alias) ? $orderStatus->order_status_alias : 'All';
		}else{
		    $value = !empty($orderStatus->order_status_name) ? $orderStatus->order_status_name : 'All';
		}		    
	    }
	    if($key == "user_id"){
		$users = DB::table('users')->where('users.id',!empty($value) ? $value : '0')->first();
		$value = !empty($users->name) ? $users->name : 'All';
	    }
	    $searchCriteria[$key] = !empty($value) ? trim($value) : '';
	    
	}
	unset($searchCriteria['mis_report_name']);
	return $searchCriteria;
    }
    
    /**
    * Get list of companies on page load.
    * Date : 01-03-17
    * Author : nisha
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getDailySalesResultant($type,$divisionId,$departmentId,$correpondingDate){
	
	global $order,$models;
	
	if(isset($type) && !empty($divisionId) && !empty($departmentId) && !empty($correpondingDate)){
	    
	    $currentDate = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
	    
	    if($type == '0'){		//No. of Reports Booked
		return DB::table('order_master')
		    ->where('order_master.division_id',$divisionId)
		    ->where('order_master.product_category_id',$departmentId)
		    ->where(DB::raw("DATE(order_master.booking_date)"), $correpondingDate)
		    ->count();
	    }
	    if($type == '1'){		//No. of Reports Billing
		return DB::table('invoice_hdr_detail')
		    ->join('invoice_hdr','invoice_hdr.invoice_id','invoice_hdr_detail.invoice_hdr_id')
		    ->where('invoice_hdr.division_id',$divisionId)
		    ->where('invoice_hdr.product_category_id',$departmentId)
		    ->where(DB::raw("DATE(invoice_hdr.invoice_date)"),$correpondingDate)
		    ->where('invoice_hdr.invoice_status','=','1')
		    ->count();		
	    }
	    if($type == '2'){		//No. of Reports Amount
		$orderAmount = DB::table('invoice_hdr_detail')
		    ->join('invoice_hdr','invoice_hdr.invoice_id','invoice_hdr_detail.invoice_hdr_id')
		    ->where('invoice_hdr.division_id',$divisionId)
		    ->where('invoice_hdr.product_category_id',$departmentId)
		    ->where(DB::raw("DATE(invoice_hdr.invoice_date)"),$correpondingDate)
		    ->where('invoice_hdr.invoice_status','=','1')
		    ->sum('invoice_hdr_detail.order_amount');
		
		$orderDiscount = DB::table('invoice_hdr_detail')
		    ->join('invoice_hdr','invoice_hdr.invoice_id','invoice_hdr_detail.invoice_hdr_id')
		    ->where('invoice_hdr.division_id',$divisionId)
		    ->where('invoice_hdr.product_category_id',$departmentId)
		    ->where(DB::raw("DATE(invoice_hdr.invoice_date)"),$correpondingDate)
		    ->where('invoice_hdr.invoice_status','=','1')
		    ->sum('invoice_hdr_detail.order_discount');
		    
		return $models->roundValue($orderAmount - $orderDiscount);
	    }
	}
    }
    
}
