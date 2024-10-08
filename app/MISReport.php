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
	public function formatTableHeader($tableHeader, $symbol, $type = '1')
	{

		if ($type == '1') {
			if (!empty($tableHeader)) {
				foreach ($tableHeader as $key => $value) {
					if ($key > 3) {
						$tableHeader[$key] = $value . '(' . $symbol . ')';
					}
				}
				return $tableHeader;
			}
		} elseif ($type == '2') {
			if (!empty($tableHeader)) {
				foreach ($tableHeader as $key => $value) {
					if ($key > 10) {
						$tableHeader[$key] = $value . '(' . $symbol . ')';
					}
				}
				return $tableHeader;
			}
		}
		return $tableHeader;
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getBookedSamplePrice($customer_id, $order_id)
	{

		global $order, $models;

		$returnData = $parameterWiseRateData = array();
		$sellingPriceAmount = '0';

		//getting customer data**************************************
		$customerData = DB::table('customer_master')->where('customer_master.customer_id', '=', $customer_id)->first();

		//getting Order data*****************************************
		$orderData 		 = DB::table('order_master')->where('order_master.order_id', '=', $order_id)->first();
		$invoicingTypeId	 = !empty($orderData->invoicing_type_id) ? $orderData->invoicing_type_id : '0';
		$division_id         	 = !empty($orderData->division_id) ? $orderData->division_id : '0';
		$product_category_id 	 = !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';
		$order_sample_type 	 = !empty($orderData->order_sample_type) ? $orderData->order_sample_type : '0';
		$customer_city	         = !empty($orderData->customer_city) ? $orderData->customer_city : '0';

		//Checking Order Amount in case of inter-laboratory and Compensatory orders
		if (empty($order_sample_type)) {

			//Conditional Invoicing Type*********************************
			if (!empty($invoicingTypeId) && !empty($product_category_id)) {
				if ($invoicingTypeId == '1') {			//ITC Parameter Wise
					$sellingPriceAmount = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->sum('selling_price');
				} else if ($invoicingTypeId == '2') {		//State Wise Product
					$invoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicingTypeId)
						->where('customer_invoicing_rates.cir_state_id', '=', $customerData->customer_state)
						->where('customer_invoicing_rates.cir_c_product_id', '=', $orderData->sample_description_id)
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->first();
					$sellingPriceAmount = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
				} else if ($invoicingTypeId == '3') {		//Customer Wise Product or Fixed rate party

					//In case of fixed Rate Party
					$invoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicingTypeId)
						->where('customer_invoicing_rates.cir_customer_id', '=', $customerData->customer_id)
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->whereNull('customer_invoicing_rates.cir_c_product_id')
						->first();

					//If Product ID is not null,then Customer Wise Product
					if (empty($invoicingData)) {
						$invoicingData = DB::table('customer_invoicing_rates')
							->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicingTypeId)
							->where('customer_invoicing_rates.cir_city_id', '=', $customer_city)
							->where('customer_invoicing_rates.cir_customer_id', '=', $customerData->customer_id)
							->where('customer_invoicing_rates.cir_c_product_id', '=', $orderData->sample_description_id)
							->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
							->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
							->first();
					}
					$sellingPriceAmount = !empty($invoicingData) ? $invoicingData->invoicing_rate : '0';
				} else if ($invoicingTypeId == '4') {		//Customer Wise Parameters

					//getting order parameters of a customers
					$orderParametersDetail = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->whereNotNull('equipment_type_id')->get();

					if ($product_category_id == '2') {
						$sellingPriceAmount = $this->getCustomerWiseAssayParameterRates($invoicingTypeId, $customerData->customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail));
					} else {
						$sellingPriceAmount = $this->getCustomerWiseParameterRates($invoicingTypeId, $customerData->customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail));
					}
				}
			}
		}
		return $sellingPriceAmount;
	}

	/**
	 * Get Customer Wise Parameter Rates
	 * Date : 12-April-2018
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerWiseParameterRates($invoicing_type_id, $customer_id, $division_id, $product_category_id, $orderParametersDetail)
	{

		global $order, $models;

		$parameterWiseRateData = array();
		$invoicingRate 	       = 0;

		if (!empty($orderParametersDetail)) {
			foreach ($orderParametersDetail as $key => $orderParameters) {
				$isInvoicingNeeded = DB::table('test_parameter')->where('test_parameter.test_parameter_id', $orderParameters['test_parameter_id'])->where('test_parameter.test_parameter_invoicing', '1')->first();
				if (!empty($isInvoicingNeeded) && !empty($orderParameters['test_parameter_id']) && !empty($orderParameters['equipment_type_id'])) {
					$parameterWiseRate = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
						->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
						->where('customer_invoicing_rates.cir_parameter_id', '=', $orderParameters['test_parameter_id'])
						->where('customer_invoicing_rates.cir_equipment_type_id', '=', $orderParameters['equipment_type_id'])
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->first();
					$parameterWiseRateData[$orderParameters['test_parameter_id']] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
				}
			}
			$invoicingRate = in_array('0', $parameterWiseRateData) ? '0' : array_sum($parameterWiseRateData);
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
	public function getCustomerWiseAssayParameterRates($invoicing_type_id, $customer_id, $division_id, $product_category_id, $orderParametersDetail)
	{

		global $order, $models;

		$parameterWiseRateData = $paramterInvoicingWithCount = $withDectorsTestCategory = $withDectorsAssayCategory = $withoutDectorsTestCategory = $withoutDectorsTestParentCategory = $withoutDectorsAssayCategory = $withoutDectorsTestCategory = $noOfInjectionWithDectorsCategory = $withDectorsTestCategoryInfo = $withDectorsAssayCategoryInfo = $withoutDectorsAssayCategoryInfo = $withoutDectorsTestParentCategoryInfo = array();
		$invoicingRate 	       = 0;

		if (!empty($orderParametersDetail)) {
			foreach ($orderParametersDetail as $key => $values) {
				$subValues = $models->convertObjectToArray(DB::table('order_parameters_detail')
					->select('parentProductCategoryDB.p_category_id as product_category_id', 'productCategoryDB.p_category_id', 'subProductCategoryDB.p_category_id as sub_p_category_id', 'test_parameter_categories.test_para_cat_id as test_parameter_category_id', 'test_parameter.test_parameter_invoicing_parent_id')
					->join('order_master', 'order_master.order_id', 'order_parameters_detail.order_id')
					->join('product_master', 'product_master.product_id', 'order_master.product_id')
					->join('product_categories as subProductCategoryDB', 'subProductCategoryDB.p_category_id', 'product_master.p_category_id')
					->join('product_categories as productCategoryDB', 'productCategoryDB.p_category_id', 'subProductCategoryDB.parent_id')
					->join('product_categories as parentProductCategoryDB', 'parentProductCategoryDB.p_category_id', 'productCategoryDB.parent_id')
					->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
					->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
					->where('order_parameters_detail.order_id', $values['order_id'])
					->where('order_parameters_detail.product_test_parameter', $values['product_test_parameter'])
					->where('order_parameters_detail.test_parameter_id', $values['test_parameter_id'])
					->first());
				//Merging Values and Sub Vaules
				$orderParameters = !empty($subValues) ? array_merge($values, $subValues) : $values;
				if (!empty($orderParameters)) {
					//Checking the global Invoicing allowed to the parameters
					$isInvoicingNeeded = DB::table('test_parameter')->where('test_parameter.test_parameter_id', $orderParameters['test_parameter_id'])->where('test_parameter.test_parameter_invoicing', '1')->first();
					if (!empty($isInvoicingNeeded) && !empty($orderParameters['test_parameter_id']) && !empty($orderParameters['equipment_type_id'])) {
						if (!empty($orderParameters['detector_id']) && !empty($orderParameters['running_time_id']) && !empty($orderParameters['no_of_injection'])) {	//checking If Detector,Running Time,no of Injection exist
							if (!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1') {
								if (!empty($orderParameters['test_parameter_invoicing_parent_id'])) {
									$groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['test_parameter_invoicing_parent_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'] . '-' . $orderParameters['detector_id'] . '-' . $orderParameters['running_time_id'];
									$noOfInjectionWithDectorsCategory[$groupedColoumName][] 	= $orderParameters['no_of_injection'];
									$orderParameters['no_of_per_injection']    	   		= '1';
									$withDectorsTestCategory[$groupedColoumName][] 		= $orderParameters;
								} else {
									$withoutDectorsTestCategory[$orderParameters['product_test_parameter']] = $orderParameters;
								}
							} else {
								$groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'] . '-' . $orderParameters['detector_id'] . '-' . $orderParameters['running_time_id'];
								$noOfInjectionWithDectorsCategory[$groupedColoumName][] 	= $orderParameters['no_of_injection'];
								$orderParameters['no_of_per_injection']     			= '1';
								$withDectorsAssayCategory[$groupedColoumName][] 		= $orderParameters;
							}
						} else {
							if (!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1') {
								if (!empty($orderParameters['test_parameter_invoicing_parent_id'])) {
									$groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['test_parameter_invoicing_parent_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
									$withoutDectorsTestParentCategory[$groupedColoumName][] = $orderParameters;
								} else {
									$withoutDectorsTestCategory[$orderParameters['product_test_parameter']] = $orderParameters;
								}
							} else {
								$groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
								$withoutDectorsAssayCategory[$groupedColoumName][] = $values;
							}
						}
					}
				}
			}

			//Calculating Rates of Test Parameter Category with Detector,Running Time,no of Injection
			if (!empty($withDectorsTestCategory)) {
				foreach ($withDectorsTestCategory as $nestedkeyWithIds => $values) {
					$withDectorsTestCategoryInfo[$nestedkeyWithIds]['equipment_count'] 		= is_array($values) ? count($values) : 0;
					$withDectorsTestCategoryInfo[$nestedkeyWithIds]['total_injection_count'] 	= isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
					$withDectorsTestCategoryInfo[$nestedkeyWithIds]['invoicing'] 		= current($values);
				}
				foreach ($withDectorsTestCategoryInfo as $nestedkeyWithIds => $values) {
					$keyTestData 				= $models->getExplodedData($nestedkeyWithIds, '-');
					$test_parameter_category_id 		= !empty($keyTestData[0]) ? $keyTestData[0] : '0';
					$test_parameter_invoicing_parent_id 	= !empty($keyTestData[1]) ? $keyTestData[1] : '0';
					$testParameterInvoicingParentData 		= DB::table('test_parameter_invoicing_parents')->where('test_parameter_invoicing_parents.tpip_id', $test_parameter_invoicing_parent_id)->first();
					$product_category_id 			= !empty($keyTestData[2]) ? $keyTestData[2] : '0';
					$p_category_id 				= !empty($keyTestData[3]) ? $keyTestData[3] : '0';
					$sub_p_category_id 				= !empty($keyTestData[4]) ? $keyTestData[4] : '0';
					$equipment_type_id 				= !empty($keyTestData[5]) ? $keyTestData[5] : '0';
					$detector_id 				= !empty($keyTestData[6]) ? $keyTestData[6] : '0';
					$running_time_id 				= !empty($keyTestData[7]) ? $keyTestData[7] : '0';
					$no_of_per_injection 			= !empty($values['invoicing']['no_of_per_injection']) ? trim($values['invoicing']['no_of_per_injection']) : 0;
					if ($test_parameter_invoicing_parent_id == 1) {
						$cir_equipment_count			= !empty($values['equipment_count']) && $values['equipment_count'] == '1' ? '1' : '2';
					} else {
						$cir_equipment_count			= !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
					}
					$total_injection_count			= !empty($values['total_injection_count']) ? array_sum($values['total_injection_count']) : '0';
					$parameterWiseRate = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
						->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->where('customer_invoicing_rates.cir_p_category_id', '=', $p_category_id)
						->where('customer_invoicing_rates.cir_sub_p_category_id', '=', $sub_p_category_id)
						->where('customer_invoicing_rates.cir_test_parameter_category_id', '=', $test_parameter_category_id)
						->where('customer_invoicing_rates.cir_parameter_id', '=', $testParameterInvoicingParentData->test_parameter_id)
						->where('customer_invoicing_rates.cir_equipment_type_id', '=', $equipment_type_id)
						->where('customer_invoicing_rates.cir_equipment_count', '=', $cir_equipment_count)
						->where('customer_invoicing_rates.cir_detector_id', '=', $detector_id)
						->where('customer_invoicing_rates.cir_running_time_id', '=', $running_time_id)
						->where('customer_invoicing_rates.cir_no_of_injection', '=', $no_of_per_injection)
						->where('customer_invoicing_rates.cir_is_detector', '=', '1')
						->first();
					$parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate * $total_injection_count : '0';
				}
			}

			//Calculating Rates of Test Parameter Parent Category without Detector,Running Time,no of Injection
			if (!empty($withoutDectorsTestParentCategory)) {
				foreach ($withoutDectorsTestParentCategory as $nestedkeyWithIds => $values) {
					$withoutDectorsTestParentCategoryInfo[$nestedkeyWithIds]['equipment_count'] 	= is_array($values) ? count($values) : 0;
					$withoutDectorsTestParentCategoryInfo[$nestedkeyWithIds]['total_injection_count'] 	= isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
					$withoutDectorsTestParentCategoryInfo[$nestedkeyWithIds]['invoicing'] 		= current($values);
				}
				foreach ($withoutDectorsTestParentCategoryInfo as $nestedkeyWithIds => $values) {
					$keyTestData 				= $models->getExplodedData($nestedkeyWithIds, '-');
					$test_parameter_category_id 		= !empty($keyTestData[0]) ? $keyTestData[0] : '0';
					$test_parameter_invoicing_parent_id 	= !empty($keyTestData[1]) ? $keyTestData[1] : '0';
					$testParameterInvoicingParentData 		= DB::table('test_parameter_invoicing_parents')->where('test_parameter_invoicing_parents.tpip_id', $test_parameter_invoicing_parent_id)->first();
					$product_category_id 			= !empty($keyTestData[2]) ? $keyTestData[2] : '0';
					$p_category_id 				= !empty($keyTestData[3]) ? $keyTestData[3] : '0';
					$sub_p_category_id 				= !empty($keyTestData[4]) ? $keyTestData[4] : '0';
					$equipment_type_id 				= !empty($keyTestData[5]) ? $keyTestData[5] : '0';
					if ($test_parameter_invoicing_parent_id == 1) {
						$cir_equipment_count			= !empty($values['equipment_count']) && $values['equipment_count'] == '1' ? '1' : '2';
					} else {
						$cir_equipment_count			= !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
					}
					$parameterWiseRate = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
						->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->where('customer_invoicing_rates.cir_p_category_id', '=', $p_category_id)
						->where('customer_invoicing_rates.cir_sub_p_category_id', '=', $sub_p_category_id)
						->where('customer_invoicing_rates.cir_test_parameter_category_id', '=', $test_parameter_category_id)
						->where('customer_invoicing_rates.cir_parameter_id', '=', $testParameterInvoicingParentData->test_parameter_id)
						->where('customer_invoicing_rates.cir_equipment_type_id', '=', $equipment_type_id)
						->where('customer_invoicing_rates.cir_equipment_count', '=', $cir_equipment_count)
						->where('customer_invoicing_rates.cir_is_detector', '=', '2')
						->first();
					$parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
				}
			}

			//Calculating Rates of Test Parameter Category without Detector,Running Time,no of Injection
			if (!empty($withoutDectorsTestCategory)) {
				foreach ($withoutDectorsTestCategory as $key => $values) {
					$parameterWiseRate = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
						->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
						->where('customer_invoicing_rates.cir_parameter_id', '=', $values['test_parameter_id'])
						->where('customer_invoicing_rates.cir_equipment_type_id', '=', $values['equipment_type_id'])
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->first();
					$parameterWiseRateData[$values['test_parameter_id']] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
				}
			}

			//Calculating Rates of Assay Parameter Category with Detector,Running Time,no of Injection
			if (!empty($withDectorsAssayCategory)) {
				foreach ($withDectorsAssayCategory as $nestedkeyWithIds => $values) {
					$withDectorsAssayCategoryInfo[$nestedkeyWithIds]['equipment_count'] 	= is_array($values) ? count($values) : 0;
					$withDectorsAssayCategoryInfo[$nestedkeyWithIds]['total_injection_count'] 	= isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
					$withDectorsAssayCategoryInfo[$nestedkeyWithIds]['invoicing'] 		= current($values);
				}
				foreach ($withDectorsAssayCategoryInfo as $nestedkeyWithIds => $values) {
					$keyAssayData 				= $models->getExplodedData($nestedkeyWithIds, '-');
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
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
						->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->where('customer_invoicing_rates.cir_p_category_id', '=', $p_category_id)
						->where('customer_invoicing_rates.cir_sub_p_category_id', '=', $sub_p_category_id)
						->where('customer_invoicing_rates.cir_test_parameter_category_id', '=', $test_parameter_category_id)
						->where('customer_invoicing_rates.cir_equipment_type_id', '=', $equipment_type_id)
						->where('customer_invoicing_rates.cir_equipment_count', '=', $cir_equipment_count)
						->where('customer_invoicing_rates.cir_detector_id', '=', $detector_id)
						->where('customer_invoicing_rates.cir_running_time_id', '=', $running_time_id)
						->where('customer_invoicing_rates.cir_no_of_injection', '=', $no_of_per_injection)
						->where('customer_invoicing_rates.cir_is_detector', '=', '1')
						->first();
					$parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate * $total_injection_count : '0';
				}
			}

			//Calculating Rates of Assay Parameter Category without Detector,Running Time,no of Injection
			if (!empty($withoutDectorsAssayCategory)) {
				foreach ($withoutDectorsAssayCategory as $nestedkeyWithIds => $values) {
					$withoutDectorsAssayCategoryInfo[$nestedkeyWithIds]['equipment_count'] 	 = is_array($values) ? count($values) : 0;
					$withoutDectorsAssayCategoryInfo[$nestedkeyWithIds]['total_injection_count'] = isset($noOfInjectionWithDectorsCategory[$nestedkeyWithIds]) && is_array($noOfInjectionWithDectorsCategory) ? $noOfInjectionWithDectorsCategory[$nestedkeyWithIds] : 0;
					$withoutDectorsAssayCategoryInfo[$nestedkeyWithIds]['invoicing'] 		 = current($values);
				}
				foreach ($withoutDectorsAssayCategoryInfo as $nestedkeyWithIds => $values) {
					$keyWDAssayData 				= $models->getExplodedData($nestedkeyWithIds, '-');
					$test_parameter_category_id 		= !empty($keyWDAssayData[0]) ? $keyWDAssayData[0] : '0';
					$product_category_id 			= !empty($keyWDAssayData[1]) ? $keyWDAssayData[1] : '0';
					$p_category_id 				= !empty($keyWDAssayData[2]) ? $keyWDAssayData[2] : '0';
					$sub_p_category_id 				= !empty($keyWDAssayData[3]) ? $keyWDAssayData[3] : '0';
					$equipment_type_id 				= !empty($keyWDAssayData[4]) ? $keyWDAssayData[4] : '0';
					$no_of_per_injection 			= !empty($values['invoicing']['no_of_per_injection']) ? trim($values['invoicing']['no_of_per_injection']) : 0;
					$cir_equipment_count			= !empty($values['equipment_count']) ? $values['equipment_count'] : '0';
					$parameterWiseRate = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
						->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->where('customer_invoicing_rates.cir_p_category_id', '=', $p_category_id)
						->where('customer_invoicing_rates.cir_sub_p_category_id', '=', $sub_p_category_id)
						->where('customer_invoicing_rates.cir_test_parameter_category_id', '=', $test_parameter_category_id)
						->where('customer_invoicing_rates.cir_equipment_type_id', '=', $equipment_type_id)
						->where('customer_invoicing_rates.cir_equipment_count', '=', $cir_equipment_count)
						->where('customer_invoicing_rates.cir_is_detector', '=', '2')
						->first();
					$parameterWiseRateData[$nestedkeyWithIds] = !empty($parameterWiseRate->invoicing_rate) ? $parameterWiseRate->invoicing_rate : '0';
				}
			}

			//echo '<pre>';print_r($parameterWiseRateData);die;
			$invoicingRate = in_array('0', $parameterWiseRateData) ? '0' : array_sum($parameterWiseRateData);
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
	public function getCustomerSampleCountAmount($values, $submitedData)
	{

		$sampleCount  = '0';
		$sampleAmount = '0.00';

		$customerSampleCountAmountObj = DB::table('order_master')
			->select('order_master.order_id', 'order_master.order_no', 'order_master.customer_id')
			->whereBetween(DB::raw("DATE(order_master.order_date)"), array($submitedData['date_from'], $submitedData['date_to']))
			->where('order_master.customer_id', $values->customer_id);

		if (!empty($submitedData['division_id'])) {
			$customerSampleCountAmountObj->where('order_master.division_id', $submitedData['division_id']);
		}
		if (!empty($submitedData['product_category_id'])) {
			$customerSampleCountAmountObj->where('order_master.product_category_id', $submitedData['product_category_id']);
		}
		$customerSampleCountAmount = $customerSampleCountAmountObj->get();

		if (!empty($customerSampleCountAmount)) {
			$sampleCount = count($customerSampleCountAmount);
			foreach ($customerSampleCountAmount as $key => $sampleCountAmount) {
				$returnSampleAmount[$key] = $this->getBookedSamplePrice($sampleCountAmount->customer_id, $sampleCountAmount->order_id);
			}
			$sampleAmount = round(array_sum($returnSampleAmount), 2);
		}

		return array($sampleCount, $sampleAmount);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getPlaceWiseSampleCountAmount($values, $submitedData)
	{

		$sampleCount  = '0';
		$sampleAmount = '0.00';

		$placeWiseSampleCountAmountObj = DB::table('order_master')
			->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
			->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
			->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
			->select('order_master.order_id', 'order_master.order_no', 'order_master.customer_id')
			->whereBetween(DB::raw("DATE(order_master.order_date)"),  array($submitedData['date_from'], $submitedData['date_to']))
			->where('customer_master.customer_city', $values->customer_city);

		if (!empty($submitedData['division_id'])) {
			$placeWiseSampleCountAmountObj->where('order_master.division_id', $submitedData['division_id']);
		}
		if (!empty($submitedData['product_category_id'])) {
			$placeWiseSampleCountAmountObj->where('order_master.product_category_id', $submitedData['product_category_id']);
		}
		$placeWiseSampleCountAmount = $placeWiseSampleCountAmountObj->get();

		if (!empty($placeWiseSampleCountAmount)) {
			$sampleCount = count($placeWiseSampleCountAmount);
			foreach ($placeWiseSampleCountAmount as $key => $sampleCountAmount) {
				$returnSampleAmount[$key] = $this->getBookedSamplePrice($sampleCountAmount->customer_id, $sampleCountAmount->order_id);
			}
			$sampleAmount = round(array_sum($returnSampleAmount), 2);
		}

		return array($sampleCount, $sampleAmount);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function get_modification_count($opl_user_id, $opl_order_id, $opl_order_status_id, $error_skip_flag = '1')
	{
		$modificationCount = DB::table('order_process_log')->where(['order_process_log.opl_user_id' => $opl_user_id, 'order_process_log.opl_order_id' => $opl_order_id, 'order_process_log.opl_order_status_id' => $opl_order_status_id])->count();
		return $modificationCount > $error_skip_flag ? $modificationCount - $error_skip_flag : '0';
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function get_order_stage_date($opl_order_id, $opl_order_status_id)
	{

		$data = DB::table('order_process_log')
			->select('order_process_log.opl_id', 'order_process_log.opl_order_id', 'order_process_log.opl_date')
			->where(['order_process_log.opl_order_id' => $opl_order_id, 'order_process_log.opl_order_status_id' => $opl_order_status_id])
			->orderBy('order_process_log.opl_id', 'DESC')
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
	public function checkEquipmentAllotStatus($order_id, $equipmentId)
	{

		$returnData = array();
		$status     = '';

		//checking Order is scheduled or not
		$orderUnscheduled   = DB::table('schedulings')->where(['schedulings.order_id' => $order_id, 'schedulings.status' => '0'])->first();
		$equipmentExistData = DB::table('order_parameters_detail')->where(['order_parameters_detail.order_id' => $order_id, 'order_parameters_detail.equipment_type_id' => $equipmentId])->get();
		if (empty($orderUnscheduled) && !empty($equipmentExistData)) {
			foreach ($equipmentExistData as $key => $equipmentData) {
				$data = DB::table('schedulings')->where(['schedulings.order_id' => $equipmentData->order_id, 'schedulings.order_parameter_id' => $equipmentData->analysis_id, 'schedulings.equipment_type_id' => $equipmentData->equipment_type_id, 'schedulings.status' => '3'])->first();
				$returnData[$key] = !empty($data) ? '1' : '0';
			}
			if (!empty($returnData)) {
				$status = in_array('0', $returnData) ? 'P' : 'C';
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
	public function checkEquipmentAllotStatusOthers($order_id, $allowedEquipmentIds)
	{
		//checking Order is scheduled or not
		$orderUnscheduled = DB::table('schedulings')->where(['schedulings.order_id' => $order_id, 'schedulings.status' => '0'])->first();
		$data 		  = DB::table('schedulings')->where('schedulings.order_id', $order_id)->where('schedulings.status', '<>', '3')->whereNotIn('schedulings.equipment_type_id', $allowedEquipmentIds)->first();
		return empty($orderUnscheduled) && !empty($data) ? 'P' : '';
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function checkTestingStatusOfOrder($order_id)
	{

		$status = '';

		//checking Order is scheduled or not
		$orderUnscheduled  = DB::table('schedulings')->where(['schedulings.order_id' => $order_id, 'schedulings.status' => '0'])->first();
		$data 		   = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->whereNull('order_parameters_detail.test_result')->first();
		if (empty($orderUnscheduled)) {
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
	public function checkScheduledStatusOfOrder($order_id)
	{
		$orderDetail  = DB::table('order_master')->where('order_master.order_id', $order_id)->where('order_master.status', '12')->first();
		if (!empty($orderDetail)) {
			return 'H';
		} else {
			$data = DB::table('schedulings')->where(['schedulings.order_id' => $order_id, 'schedulings.status' => '0'])->first();
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
	public function getSampleLogResultant($type, $divisionId, $departmentId, $postedData)
	{

		if (!empty($type) && !empty($divisionId) && !empty($departmentId) && !empty($postedData)) {

			$currentDate = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
			$fromDate    = $postedData['date_from'];
			$toDate      = $postedData['date_to'];

			if ($type == '1') {		//No. of Packet Received
				return DB::table('samples')
					->where('samples.division_id', $divisionId)
					->where('samples.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(samples.sample_current_date)"), array($fromDate, $toDate))
					->count();
			} else if ($type == '2') {	//No. of Packet Booked
				return DB::table('samples')
					->where('samples.division_id', $divisionId)
					->where('samples.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(samples.sample_booked_date)"), array($fromDate, $toDate))
					->count();
			} else if ($type == '3') {	//No. of Sample Booked
				return DB::table('order_master')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($fromDate, $toDate))
					->count();
			} else if ($type == '4') {	//No. of Sample Hold
				return count(DB::table('order_process_log')
					->join('order_master', 'order_master.order_id', 'order_process_log.opl_order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->where('order_master.status', '<>', '10')
					->whereBetween(DB::raw("DATE(order_process_log.opl_date)"), array($fromDate, $toDate))
					->where('order_process_log.opl_order_status_id', '=', '12')
					->where('order_process_log.opl_amend_status', '=', '0')
					->groupBy('order_process_log.opl_order_id')
					->get());
			} else if ($type == '5') {	//No. of Samples Scheduled
				return DB::table('order_master')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->whereBetween(DB::raw("DATE(order_master.order_scheduled_date)"), array($fromDate, $toDate))
					->count();
			} else if ($type == '6') {	//No. of Samples Analyzed
				return DB::table('order_master')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_master.test_completion_date)"), array($fromDate, $toDate))
					->whereNotIn('order_master.status', array('10', '12'))
					->count();
			} else if ($type == '7') {	//No. of Samples Reviewed
				return DB::table('order_master')
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_report_details.reviewing_date)"), array($fromDate, $toDate))
					->whereNotIn('order_master.status', array('10', '12'))
					->count();
			} else if ($type == '8') {	//No. of Samples Approved
				return DB::table('order_master')
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_report_details.approving_date)"), array($fromDate, $toDate))
					->whereNotIn('order_master.status', array('10', '12'))
					->count();
			} else if ($type == '9') {	//No. of Sample Emailed
				return DB::table('order_master')
					->join('order_mail_dtl', 'order_mail_dtl.order_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_mail_dtl.mail_date)"), array($fromDate, $toDate))
					->where('order_mail_dtl.mail_content_type', '=', '3')
					->whereNotIn('order_master.status', array('10', '12'))
					->where('order_mail_dtl.mail_status', '=', '1')
					->where('order_mail_dtl.mail_active_type', '=', '1')
					->count();
			} else if ($type == '10') {	//No. of report Dispatched
				return DB::table('order_master')
					->join('order_dispatch_dtl', 'order_dispatch_dtl.order_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_dispatch_dtl.dispatch_date)"), array($fromDate, $toDate))
					->where('order_dispatch_dtl.amend_status', '=', '0')
					->whereNotIn('order_master.status', array('10', '12'))
					->count();
			} else if ($type == '11') {	//No. of report Invoiced
				return DB::table('invoice_hdr_detail')
					->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
					->where('invoice_hdr.division_id', $divisionId)
					->where('invoice_hdr.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), array($fromDate, $toDate))
					->where('invoice_hdr.invoice_status', '=', '1')
					->where('invoice_hdr_detail.invoice_hdr_status', '1')
					->count();
			} else if ($type == '12') {	//No of Report Due		
				return DB::table('order_master')
					->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_master.expected_due_date)"), '=', $toDate)
					->where(function ($query) use ($toDate) {
						$query->whereNull('order_report_details.approving_date');
						$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"), '>', $toDate);
					})
					->count();
			} else if ($type == '13') {	//No. of Report Overdue
				return DB::table('order_master')
					->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_master.expected_due_date)"), '<', $toDate)
					->where(function ($query) use ($toDate) {
						$query->whereNull('order_report_details.approving_date');
						$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"), '>', $toDate);
					})
					->count();
			} else if ($type == '14') {	//No. of Invoice Pending
				return DB::table('order_master')
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->leftJoin('invoice_hdr_detail', function ($join) {
						$join->on('order_report_details.report_id', '=', 'invoice_hdr_detail.order_id');
						$join->where('invoice_hdr_detail.invoice_hdr_status', '1');
					})
					->leftJoin('invoice_hdr', function ($join) {
						$join->on('invoice_hdr.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id');
						$join->where('invoice_hdr.invoice_status', '1');
					})
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNull('order_master.order_sample_type')
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_report_details.approving_date)"), '<=', $toDate)
					->where(function ($query) use ($toDate) {
						$query->whereNull('invoice_hdr.invoice_date');
						$query->orWhere(DB::raw("DATE(invoice_hdr.invoice_date)"), '>', $toDate);
					})
					->count();
			} else if ($type == '15') {	//No. of Report Pending
				return DB::table('order_master')
					->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
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
	function getDateWisePartySampleCount($values, $dateRangeData, $submitedData)
	{
		foreach ($dateRangeData as $key => $dateRange) {
			$dateCountObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->where(DB::raw("DATE(order_master.order_date)"), $dateRange)
				->where('order_master.customer_id', $values->customer_id)
				->where('order_master.customer_city', $values->customer_city);
			if (!empty($submitedData['division_id'])) {
				$dateCountObj->where('order_master.division_id', $submitedData['division_id']);
			}
			if (!empty($submitedData['product_category_id'])) {
				$dateCountObj->where('order_master.product_category_id', $submitedData['product_category_id']);
			}
			$dateRangeColumn = date(DATEFORMAT, strtotime($dateRange));
			$values->$dateRangeColumn = $dateCountObj->count();
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getMonthWisePartySampleCount($values, $monthRangeData, $submitedData)
	{
		foreach ($monthRangeData as $key => $monthRanges) {
			$orderMonth 	= date('m', strtotime($monthRanges));
			$orderYear  	= date('Y', strtotime($monthRanges));
			$monthRange    	= date('m-Y', strtotime($monthRanges));
			$monthCountObj 	= DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->whereMonth('order_master.order_date', $orderMonth)
				->whereYear('order_master.order_date', $orderYear)
				->where('order_master.customer_id', $values->customer_id)
				->where('order_master.customer_city', $values->customer_city);
			if (!empty($submitedData['division_id'])) {
				$monthCountObj->where('order_master.division_id', $submitedData['division_id']);
			}
			if (!empty($submitedData['product_category_id'])) {
				$monthCountObj->where('order_master.product_category_id', $submitedData['product_category_id']);
			}
			$values->$monthRange = $monthCountObj->count();
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getTableFooterData($thDate, $submitedData, $type = '1')
	{

		global $order, $models, $misReport;

		$returnData = array();

		if (!empty($thDate)) {
			foreach ($thDate as $key => $thDateValue) {
				if ($type == '1') {
					if ($key > '3') {
						$returnDataObj = DB::table('order_master')
							->join('divisions', 'divisions.division_id', 'order_master.division_id')
							->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
							->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
							->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
							->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
							->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
							->where(DB::raw("DATE(order_master.order_date)"), $models->getFormatedDate($thDateValue));
						if (!empty($submitedData['division_id'])) {
							$returnDataObj->where('order_master.division_id', $submitedData['division_id']);
						}
						if (!empty($submitedData['product_category_id'])) {
							$returnDataObj->where('order_master.product_category_id', $submitedData['product_category_id']);
						}
						$returnData[$thDateValue] = $returnDataObj->count();
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '2') {
					if ($key > '3') {
						$orderMonthYear 	  = explode('-', $thDateValue);
						$orderMonth 		  = !empty($orderMonthYear[0]) ? $orderMonthYear[0] : '0';
						$orderYear 		  = !empty($orderMonthYear[1]) ? $orderMonthYear[1] : '0';
						$returnDataObj = DB::table('order_master')
							->join('divisions', 'divisions.division_id', 'order_master.division_id')
							->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
							->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
							->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
							->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
							->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
							->whereMonth('order_master.order_date', $orderMonth)
							->whereYear('order_master.order_date', $orderYear);
						if (!empty($submitedData['division_id'])) {
							$returnDataObj->where('order_master.division_id', $submitedData['division_id']);
						}
						if (!empty($submitedData['product_category_id'])) {
							$returnDataObj->where('order_master.product_category_id', $submitedData['product_category_id']);
						}
						$returnData[$thDateValue] = $returnDataObj->count();
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '3') {
					if ($key > '4') {
						if ($thDateValue == 'sample_count') {
							$returnData[$thDateValue] = array_sum($submitedData['totalSample']);
						}
						if ($thDateValue == 'sample_amount') {
							$returnData[$thDateValue] = number_format((float) array_sum($submitedData['totalAmount']), 2, '.', '');
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '4') {
					if ($key > '3') {
						if ($thDateValue == 'sample_count') {
							$returnData[$thDateValue] = array_sum($submitedData['totalSample']);
						}
						if ($thDateValue == 'sample_amount') {
							$returnData[$thDateValue] = number_format((float) array_sum($submitedData['totalAmount']), 2, '.', '');
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '5') {
					if ($key > '19') {
						if ($thDateValue == 'ref_inv_date') {
							$returnData[$thDateValue] = 'Total Revenue';
						}
						if ($thDateValue == 'revenue_amount') {
							$returnData[$thDateValue] = number_format((float) array_sum($submitedData['revenue_amount']), 2, '.', '');
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '6') {
					if ($key > '23') {
						if ($thDateValue == 'test_parameter_name') {
							$returnData[$thDateValue] = 'Total';
						}
						if ($thDateValue == 'itc_price') {
							$returnData[$thDateValue] = number_format((float) array_sum($submitedData['itc_price']), 2, '.', '');
						}
						if ($thDateValue == 'sample_amount') {
							$returnData[$thDateValue] = number_format((float) array_sum($submitedData['sample_amount']), 2, '.', '');
						}
						if ($thDateValue == 'customer_discount_price_parameter_wise') {
							$returnData[$thDateValue] = number_format((float) array_sum($submitedData['customer_discount_price_parameter_wise']), 2, '.', '');
						}
						if ($thDateValue == 'customer_price_parameter_wise') {
							$returnData[$thDateValue] = number_format((float) array_sum($submitedData['customer_price_parameter_wise']), 2, '.', '');
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '7') {
					if ($key > '2') {
						if ($thDateValue == 'equipment') {
							$returnData[$thDateValue] = 'Total';
						}
						if ($thDateValue == 'opening_pending') {
							$returnData[$thDateValue] = array_sum($submitedData['total_opening_pending']);
						}
						if ($thDateValue == 'pending') {
							$returnData[$thDateValue] = array_sum($submitedData['total_pending']);
						}
						if ($thDateValue == 'allocated') {
							$returnData[$thDateValue] = array_sum($submitedData['total_allocated']);
						}
						if ($thDateValue == 'completed') {
							$returnData[$thDateValue] = array_sum($submitedData['total_completed']);
						}
						if ($thDateValue == 'over_due') {
							$returnData[$thDateValue] = array_sum($submitedData['total_over_due']);
						}
						if ($thDateValue == 'not_due') {
							$returnData[$thDateValue] = array_sum($submitedData['total_not_due']);
						}
						if ($thDateValue == 'closing') {
							$returnData[$thDateValue] = array_sum($submitedData['total_closing']);
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '8') {
					if ($key > '10') {
						if ($thDateValue == 'TAT') {
							$returnData[$thDateValue] = 'Sum of Yes | No';
						}
						if ($thDateValue == 'within_due_date') {
							$returnData[$thDateValue] = count(array_filter($submitedData['sum_of_yes'])) . ' | ' . count(array_filter($submitedData['sum_of_no']));
						}
						if ($thDateValue == 'days_delay') {
							$returnData[$thDateValue] = 'Total Errors';
						}
						if ($thDateValue == 'no_of_errors') {
							$returnData[$thDateValue] = array_sum(array_filter($submitedData['no_of_error_count']));
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '9') {
					if ($key > '8') {
						if ($thDateValue == 'amended_by') {
							$returnData[$thDateValue] = 'Total Amended';
						}
						if ($thDateValue == 'amendment_count') {
							$returnData[$thDateValue] = count(array_filter($submitedData['total_amendment_count']));
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '10') {
					if ($key > '1') {
						if ($thDateValue == 'date') {
							$returnData[$thDateValue] = 'Total';
						}
						if ($thDateValue) {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData[$thDateValue])), 2);
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '11') {
					if ($key > '4') {
						if ($thDateValue == 'party_name') {
							$returnData[$thDateValue] = 'Total';
						}
						if ($thDateValue == 'amount') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['total_amount'])), 2);
						}
						if ($thDateValue == 'sgst_value') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['total_sgst_value'])), 2);
						}
						if ($thDateValue == 'cgst_value') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['total_cgst_value'])), 2);
						}
						if ($thDateValue == 'igst_value') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['total_igst_value'])), 2);
						}
						if ($thDateValue == 'conveyance') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['total_conveyance'])), 2);
						}
						if ($thDateValue == 'amt_payable') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['total_amt_payable'])), 2);
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '12') {
					if ($key > '0') {
						if ($thDateValue == 'person_wise') {
							$returnData[$thDateValue] = 'Total';
						}
						if ($thDateValue == 'total_ob_target') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['total_ob_target_total'])), 2);
						}
						if ($thDateValue == 'actual_ob_ach_mtd') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['actual_ob_ach_mtd_total'])), 2);
						}
						if ($thDateValue == 'ob_variation') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['ob_variation_total'])), 2);
						}
						if ($thDateValue == 'total_inv_target') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['total_inv_target_total'])), 2);
						}
						if ($thDateValue == 'actual_inv_ach_mtd') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['actual_inv_ach_mtd_total'])), 2);
						}
						if ($thDateValue == 'inv_variation') {
							$returnData[$thDateValue] = round(array_sum(array_filter($submitedData['inv_variation_total'])), 2);
						}
					} else {
						$returnData[$thDateValue] = '';
					}
				} else if ($type == '13') {
					if ($key > '9') {
						if ($thDateValue == 'sales_executive') {
							$returnData[$thDateValue] = 'Total';
						}
						if ($thDateValue == 'amount') {
							$returnData[$thDateValue] = $models->roundValues((array_sum(array_filter($submitedData['total_amount']))));
						}
						if ($thDateValue == 'amount_in_lakh') {
							$returnData[$thDateValue] = $models->roundValues((array_sum(array_filter($submitedData['total_amount_in_lakh']))));
						}
					} else {
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
	function getPendingEquipmentCount($date, $division, $department, $equipmentType, $type)
	{
		if ($type == '1') {		//Openning Pending Count
			return DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->where('order_master.division_id', $division->division_id)
				->where('order_master.product_category_id', $department->p_category_id)
				->where('schedulings.equipment_type_id', $equipmentType->equipment_id)
				->where(DB::raw("DATE(schedulings.scheduled_at)"), '<', $date)
				->where('order_master.status', '<>', '10')
				->where(function ($query) use ($date) {
					$query->whereNull('schedulings.completed_at');
					$query->orWhere(DB::raw("DATE(schedulings.completed_at)"), '>=', $date);
				})
				->count();
		} else if ($type == '2') {		//Pending Count
			return DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->where('order_master.division_id', $division->division_id)
				->where('order_master.product_category_id', $department->p_category_id)
				->where('schedulings.equipment_type_id', $equipmentType->equipment_id)
				->where(DB::raw("DATE(schedulings.scheduled_at)"), $date)
				->where('order_master.status', '<>', '10')
				->whereNull('schedulings.completed_at')
				->count();
		} else if ($type == '3') {		//Allocated Count
			return DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->where('order_master.division_id', $division->division_id)
				->where('order_master.product_category_id', $department->p_category_id)
				->where('schedulings.equipment_type_id', $equipmentType->equipment_id)
				->where(DB::raw("DATE(schedulings.scheduled_at)"), $date)
				->where('order_master.status', '<>', '10')
				->count();
		} else if ($type == '4') {		//Completed Count
			return DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->where('order_master.division_id', $division->division_id)
				->where('order_master.product_category_id', $department->p_category_id)
				->where('schedulings.equipment_type_id', $equipmentType->equipment_id)
				->where(DB::raw("DATE(schedulings.completed_at)"), $date)
				->where('order_master.status', '<>', '10')
				->count();
		} else if ($type == '5') {		//Over Due Count
			return DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->where('order_master.division_id', $division->division_id)
				->where('order_master.product_category_id', $department->p_category_id)
				->where('schedulings.equipment_type_id', $equipmentType->equipment_id)
				->where(DB::raw("DATE(schedulings.completed_at)"), $date)
				->where('order_master.status', '<>', '10')
				->where(DB::raw("DATE(schedulings.completed_at)"), '>', DB::raw("DATE(order_master.expected_due_date)"))
				->count();
		} else if ($type == '6') {		//Not Due Count
			return DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->where('order_master.division_id', $division->division_id)
				->where('order_master.product_category_id', $department->p_category_id)
				->where('schedulings.equipment_type_id', $equipmentType->equipment_id)
				->where(DB::raw("DATE(schedulings.completed_at)"), $date)
				->where('order_master.status', '<>', '10')
				->where(DB::raw("DATE(schedulings.completed_at)"), '<=', DB::raw("DATE(order_master.expected_due_date)"))
				->count();
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getEquipmentAllStatus($values, $formData)
	{

		global $order, $models;

		//*****************Current Date Calculation of Pending*************************	
		$allocatedCurrentObj = DB::table('schedulings')
			->join('order_master', 'order_master.order_id', 'schedulings.order_id')
			->where('schedulings.equipment_type_id', $values->equipment_type_id)
			->where(DB::raw("DATE(order_master.booking_date)"), $models->getFormatedDate($values->booking_date))
			->whereIn('schedulings.status', array('1', '2', '3'));
		if (!empty($formData['division_id'])) {
			$allocatedCurrentObj->where('order_master.division_id', $formData['division_id']);
		}
		if (!empty($formData['product_category_id'])) {
			$allocatedCurrentObj->where('schedulings.product_category_id', $formData['product_category_id']);
		}
		$allocatedCurrent = $allocatedCurrentObj->count();

		$completedCurrentObj = DB::table('schedulings')
			->join('order_master', 'order_master.order_id', 'schedulings.order_id')
			->where('schedulings.equipment_type_id', $values->equipment_type_id)
			->where(DB::raw("DATE(order_master.booking_date)"), $models->getFormatedDate($values->booking_date))
			->whereNotNull('schedulings.completed_at')
			->where('schedulings.status', '3');
		if (!empty($formData['division_id'])) {
			$completedCurrentObj->where('order_master.division_id', $formData['division_id']);
		}
		if (!empty($formData['product_category_id'])) {
			$completedCurrentObj->where('schedulings.product_category_id', $formData['product_category_id']);
		}
		$completedCurrentData = $completedCurrentObj->count();

		//calculating Total
		$allocatedCurrentData  = $allocatedCurrent + $completedCurrentData;
		$pendingCurrenData     = $allocatedCurrentData - $completedCurrentData;
		//*****************/Current Date Calculation of Pending*************************	

		//*****************Previous Date Calculation of Pending*************************	
		$pendingPreviousDataObj = DB::table('schedulings')
			->join('order_master', 'order_master.order_id', 'schedulings.order_id')
			->where('schedulings.equipment_type_id', $values->equipment_type_id)
			->where(DB::raw("DATE(order_master.booking_date)"), '<=', $models->getFormatedDate($values->previous_date))
			->whereIn('schedulings.status', array('1', '2'));
		if (!empty($formData['division_id'])) {
			$pendingPreviousDataObj->where('order_master.division_id', $formData['division_id']);
		}
		if (!empty($formData['product_category_id'])) {
			$pendingPreviousDataObj->where('order_master.product_category_id', $formData['product_category_id']);
		}
		$pendingPreviousData = $pendingPreviousDataObj->count();
		//*****************Previous Date Calculation of Pending*************************	

		//Calculating final Pending
		$openingPending = abs($pendingCurrenData + $pendingPreviousData);
		$openingPending = !empty($openingPending) && is_int($openingPending) ? $openingPending : 0;

		return array($openingPending, $allocatedCurrentData, $completedCurrentData);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getOverDueEquipment($values, $formData)
	{

		global $order, $models;

		$overDueObj = DB::table('schedulings')
			->join('order_master', 'order_master.order_id', 'schedulings.order_id')
			->where('schedulings.equipment_type_id', $values->equipment_type_id)
			->where(DB::raw("DATE(order_master.booking_date)"), $models->getFormatedDate($values->booking_date))
			->where(DB::raw("DATE(order_master.order_report_due_date)"), '<', DB::raw("DATE(schedulings.completed_at)"))
			->where('schedulings.status', '3');
		if (!empty($formData['division_id'])) {
			$overDueObj->where('order_master.division_id', $formData['division_id']);
		}
		if (!empty($formData['product_category_id'])) {
			$overDueObj->where('order_master.product_category_id', $formData['product_category_id']);
		}
		return $overDueObj->count();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getRequiredFieldValue($values, $postedData, $type)
	{

		global $order, $models;

		if ($type == '1') { 		//Total Sample Received(No of Samples allocated during the period)

			$dataObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->whereBetween(DB::raw("DATE(order_master.order_scheduled_date)"), array($postedData['date_from'], $postedData['date_to']))
				->where('schedulings.employee_id', $values->user_id)
				->where('order_master.status', '<>', '10')
				->groupBy('schedulings.order_id');
			if (!empty($postedData['division_id'])) {
				$dataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$dataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			$data = $dataObj->get();
			return !empty($data) ? count($data) : '0';
		} else if ($type == '2') { 	//Total Sample Analysed(No of Samples Completed during the period)

			$dataObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->whereBetween(DB::raw("DATE(order_master.test_completion_date)"), array($postedData['date_from'], $postedData['date_to']))
				->where('schedulings.employee_id', $values->user_id)
				->where('order_master.status', '<>', '10')
				->groupBy('schedulings.order_id');
			if (!empty($postedData['division_id'])) {
				$dataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$dataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			$data = $dataObj->get();
			return !empty($data) ? count($data) : '0';
		} else if ($type == '3') { 	//No of Test Conducted(Test parameters Completed during the period)

			$dataObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->whereBetween(DB::raw("DATE(schedulings.completed_at)"), array($postedData['date_from'], $postedData['date_to']))
				->where('schedulings.employee_id', $values->user_id)
				->where('order_master.status', '<>', '10');
			if (!empty($postedData['division_id'])) {
				$dataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$dataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			return $dataObj->count();
		} else if ($type == '4') { 	//Sample Within TAT

			$dataObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->whereBetween(DB::raw("DATE(order_master.test_completion_date)"), array($postedData['date_from'], $postedData['date_to']))
				->where(DB::raw("DATE(order_master.test_completion_date)"), '<=', DB::raw("DATE(order_master.expected_due_date)"))
				->where('schedulings.employee_id', $values->user_id)
				->where('order_master.status', '<>', '10')
				->groupBy('schedulings.order_id');
			if (!empty($postedData['division_id'])) {
				$dataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$dataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			$data = $dataObj->get();
			return !empty($data) ? count($data) : '0';
		} else if ($type == '5') { 	//Sample beyond TAT

			$dataObj = DB::table('order_master')
				->join('schedulings', 'order_master.order_id', 'schedulings.order_id')
				->whereBetween(DB::raw("DATE(order_master.test_completion_date)"), array($postedData['date_from'], $postedData['date_to']))
				->where(DB::raw("DATE(order_master.test_completion_date)"), '>', DB::raw("DATE(order_master.expected_due_date)"))
				->where('schedulings.employee_id', $values->user_id)
				->where('order_master.status', '<>', '10')
				->groupBy('schedulings.order_id');
			if (!empty($postedData['division_id'])) {
				$dataObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$dataObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			$data = $dataObj->get();
			return !empty($data) ? count($data) : '0';
		} else if ($type == '6') { 	//No of errors

			$error_parameter_count = array();

			$orderIdObj = DB::table('schedulings')
				->join('order_master', 'order_master.order_id', 'schedulings.order_id')
				->whereBetween(DB::raw("DATE(schedulings.completed_at)"), array($postedData['date_from'], $postedData['date_to']))
				->where('schedulings.employee_id', $values->user_id)
				->where('order_master.status', '<>', '10')
				->groupBy('schedulings.order_id');

			if (!empty($postedData['division_id'])) {
				$orderIdObj->where('order_master.division_id', $postedData['division_id']);
			}
			if (!empty($postedData['product_category_id'])) {
				$orderIdObj->where('order_master.product_category_id', $postedData['product_category_id']);
			}
			$orderIds = $orderIdObj->pluck('schedulings.order_id')->all();

			if (!empty($orderIds)) {
				$error_parameter_ids = DB::table('order_process_log')
					->whereIn('order_process_log.opl_order_id', array_values($orderIds))
					->whereNotNull('order_process_log.error_parameter_ids')
					->select('order_process_log.error_parameter_ids')
					->where('order_process_log.opl_order_status_id', '3')
					->whereBetween(DB::raw("DATE(order_process_log.opl_date)"), array($postedData['date_from'], $postedData['date_to']))
					->get();
				if (!empty($error_parameter_ids)) {
					foreach ($error_parameter_ids as $key => $error_parameter_str) {
						$error_parameter_array = array();
						$error_parameter_array = explode(',', $error_parameter_str->error_parameter_ids);
						$error_parameter_count[$key] = DB::table('order_parameters_detail')
							->where('order_parameters_detail.test_performed_by', $values->user_id)
							->whereIn('order_parameters_detail.analysis_id', $error_parameter_array)
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
	function getNoOfErrorCount($values, $postedData)
	{

		$error_parameter_count = array();

		$error_parameter_ids = DB::table('order_process_log')
			->where('order_process_log.opl_order_id', $values->order_id)
			->whereNotNull('order_process_log.error_parameter_ids')
			->select('order_process_log.error_parameter_ids')
			->where('order_process_log.opl_order_status_id', '3')
			->whereBetween(DB::raw("DATE(order_process_log.opl_date)"), array($postedData['date_from'], $postedData['date_to']))
			->get();
		if (!empty($error_parameter_ids)) {
			foreach ($error_parameter_ids as $key => $error_parameter_str) {
				$error_parameter_array = array();
				$error_parameter_array = explode(',', $error_parameter_str->error_parameter_ids);
				$error_parameter_count[$key] = DB::table('order_parameters_detail')
					->where('order_parameters_detail.test_performed_by', $values->employee_id)
					->whereIn('order_parameters_detail.analysis_id', $error_parameter_array)
					->where('order_parameters_detail.test_parameter_id', $values->test_parameter_id)
					->count();
			}
			return array_sum($error_parameter_count);
		}
	}

	/**sort array in asc order
	 *
	 * @return \Illuminate\Http\Response
	 */
	function filterSearchCriteria($searchCriteria)
	{
		foreach ($searchCriteria as $key => $value) {
			if ($key == "division_id") {
				$divisions = DB::table('divisions')->where('divisions.division_id', !empty($value) ? $value : '0')->first();
				$value = !empty($divisions->division_name) ? $divisions->division_name : 'All';
			}
			if ($key == "product_category_id") {
				$productCategories = DB::table('product_categories')->where('product_categories.p_category_id', !empty($value) ? $value : '0')->first();
				$value = !empty($productCategories->p_category_name) ? $productCategories->p_category_name : 'All';
			}
			if ($key == "sale_executive_id") {
				$saleExecutive = DB::table('users')->where('users.id', !empty($value) ? $value : '0')->first();
				$value = !empty($saleExecutive->name) ? $saleExecutive->name : 'All';
			}
			if ($key == "order_status_id") {
				$orderStatus = DB::table('order_status')->where('order_status.order_status_id', !empty($value) ? $value : '0')->first();
				if (in_array('UWPD007', $searchCriteria) || in_array('AWPS013', $searchCriteria)) {
					$value = !empty($orderStatus->order_status_alias) ? $orderStatus->order_status_alias : 'All';
				} else {
					$value = !empty($orderStatus->order_status_name) ? $orderStatus->order_status_name : 'All';
				}
			}
			if ($key == "user_id") {
				$users = DB::table('users')->where('users.id', !empty($value) ? $value : '0')->first();
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
	public function getDailySalesResultant($type, $divisionId, $departmentId, $correpondingDate)
	{

		global $order, $models;

		if (isset($type) && !empty($divisionId) && !empty($departmentId) && !empty($correpondingDate)) {

			$currentDate = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');

			if ($type == '0') {		//No. of Reports Booked
				return DB::table('order_master')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_master.booking_date)"), $correpondingDate)
					->count();
			}
			if ($type == '1') {		//No. of Reports Billing
				return DB::table('invoice_hdr_detail')
					->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
					->where('invoice_hdr.division_id', $divisionId)
					->where('invoice_hdr.product_category_id', $departmentId)
					->where(DB::raw("DATE(invoice_hdr.invoice_date)"), $correpondingDate)
					->count();
			}
			if ($type == '2') {		//Reports Invoiced Amount
				$sampleInvoicedAmount = DB::table('invoice_hdr_detail')
					->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
					->where('invoice_hdr.division_id', $divisionId)
					->where('invoice_hdr.product_category_id', $departmentId)
					->where(DB::raw("DATE(invoice_hdr.invoice_date)"), $correpondingDate)
					->sum('invoice_hdr_detail.order_total_amount');

				$extraInvoicedAmount = DB::table('invoice_hdr_detail')
					->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
					->where('invoice_hdr.division_id', $divisionId)
					->where('invoice_hdr.product_category_id', $departmentId)
					->where(DB::raw("DATE(invoice_hdr.invoice_date)"), $correpondingDate)
					->where('invoice_hdr_detail.invoice_hdr_status', '1')
					->sum('invoice_hdr_detail.extra_amount');

				$creditNotesAmount = DB::table('credit_notes')
					->where('credit_notes.division_id', $divisionId)
					->where('credit_notes.product_category_id', $departmentId)
					->where(DB::raw("DATE(credit_notes.credit_note_date)"), $correpondingDate)
					->sum('credit_notes.credit_note_amount');

				return number_format((float) trim($sampleInvoicedAmount - ($extraInvoicedAmount + $creditNotesAmount)), 2, '.', '');
			}
			if ($type == '3') {		//Invoiced ID
				return DB::table('invoice_hdr_detail')
					->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
					->where('invoice_hdr.division_id', $divisionId)
					->where('invoice_hdr.product_category_id', $departmentId)
					->where(DB::raw("DATE(invoice_hdr.invoice_date)"), $correpondingDate)
					->pluck('invoice_hdr.invoice_id', 'invoice_hdr.invoice_id')
					->all();
			}
		}
	}
	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSampleLogDetail($type, $divisionId, $departmentId, $fromDate, $toDate)
	{

		global $order, $models;

		if (!empty($type) && !empty($divisionId) && !empty($departmentId) && !empty($fromDate) && !empty($toDate)) {

			$currentDate = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');

			if ($type == '1') {		//No. of Packet Received
				$noOfPacketRecieved =  DB::table('samples')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'samples.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'samples.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'samples.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->where('samples.division_id', $divisionId)
					->where('samples.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(samples.sample_current_date)"), array($fromDate, $toDate))
					->select('divisions.division_name as branch', 'departments.department_name as department', 'samples.sample_no', 'customer_master.customer_name', 'city_db.city_name as city', 'samples.sample_current_date as sample_date')
					->orderBy('samples.sample_current_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfPacketRecieved, DATEFORMAT);
				return $noOfPacketRecieved;
			} else if ($type == '2') {	//No. of Packet Booked
				$noOfPacketBooked =  DB::table('samples')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'samples.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'samples.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'samples.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->where('samples.division_id', $divisionId)
					->where('samples.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(samples.sample_booked_date)"), array($fromDate, $toDate))
					->select('departments.department_name as department', 'divisions.division_name as branch', 'samples.sample_no', 'customer_master.customer_name', 'city_db.city_name as city', 'samples.sample_current_date as sample_date', 'samples.sample_booked_date as booking_date')
					->orderBy('samples.sample_current_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfPacketBooked, DATEFORMAT);
				return  $noOfPacketBooked;
			} else if ($type == '3') {	//No. of Sample Booked
				$noOfSampleBooked =  DB::table('order_master')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($fromDate, $toDate))
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_master.expected_due_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfSampleBooked, DATEFORMAT);
				return $noOfSampleBooked;
			} else if ($type == '4') {	//No. of Sample Hold
				$noOfSampleHold = DB::table('order_process_log')
					->join('order_master', 'order_master.order_id', 'order_process_log.opl_order_id')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->where('order_master.status', '<>', '10')
					->whereBetween(DB::raw("DATE(order_process_log.opl_date)"), array($fromDate, $toDate))
					->where('order_process_log.opl_order_status_id', '=', '12')
					->where('order_process_log.opl_amend_status', '=', '0')
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_master.expected_due_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfSampleHold, DATEFORMAT);
				return $noOfSampleHold;
			} else if ($type == '5') {	//No. of Samples Scheduled
				$noOfSampleScheduled =  DB::table('order_master')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->whereBetween(DB::raw("DATE(order_master.order_scheduled_date)"), array($fromDate, $toDate))
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_master.order_scheduled_date as scheduled_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfSampleScheduled, DATEFORMAT);
				return $noOfSampleScheduled;
			} else if ($type == '6') {	//No. of Samples Analyzed
				$noOfSampleAnalyzed =  DB::table('order_master')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_master.test_completion_date)"), array($fromDate, $toDate))
					->whereNotIn('order_master.status', array('10', '12'))
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_master.test_completion_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfSampleAnalyzed, DATEFORMAT);
				return $noOfSampleAnalyzed;
			} else if ($type == '7') {	//No. of Samples Reviewed
				$noOfSampleReviewed =  DB::table('order_master')
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_report_details.reviewing_date)"), array($fromDate, $toDate))
					->whereNotIn('order_master.status', array('10', '12'))
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_report_details.reviewing_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfSampleReviewed, DATEFORMAT);
				return $noOfSampleReviewed;
			} else if ($type == '8') {	//No. of Samples Approved
				$noOfSampleApproved =  DB::table('order_master')
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_report_details.approving_date)"), array($fromDate, $toDate))
					->whereNotIn('order_master.status', array('10', '12'))
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_report_details.approving_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfSampleApproved, DATEFORMAT);
				return $noOfSampleApproved;
			} else if ($type == '9') {	//No. of Sample Emailed
				$noOfSampleEmailed =  DB::table('order_master')
					->join('order_mail_dtl', 'order_mail_dtl.order_id', 'order_master.order_id')
					->join('users', 'order_mail_dtl.mail_by', 'users.id')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_mail_dtl.mail_date)"), array($fromDate, $toDate))
					->where('order_mail_dtl.mail_content_type', '=', '3')
					->whereNotIn('order_master.status', array('10', '12'))
					->where('order_mail_dtl.mail_status', '=', '1')
					->where('order_mail_dtl.mail_active_type', '=', '1')
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'order_master.order_date', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_mail_dtl.mail_date as mail_date', 'order_mail_dtl.mail_date as mail_time', 'users.name as mail_by')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfSampleEmailed, DATEFORMAT);
				return $noOfSampleEmailed;
			} else if ($type == '10') {	//No. of report Dispatched
				$noOfReportDispatched = DB::table('order_master')
					->join('order_dispatch_dtl', 'order_dispatch_dtl.order_id', 'order_master.order_id')
					->join('users', 'order_dispatch_dtl.dispatch_by', 'users.id')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(order_dispatch_dtl.dispatch_date)"), array($fromDate, $toDate))
					->where('order_dispatch_dtl.amend_status', '=', '0')
					->whereNotIn('order_master.status', array('10', '12'))
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_dispatch_dtl.dispatch_date', 'order_dispatch_dtl.dispatch_date as dispatch_time', 'users.name as dispatch_by')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfReportDispatched, DATEFORMAT);
				return $noOfReportDispatched;
			} else if ($type == '11') {	//No. of report Invoiced
				$noOfReportInvoiced =  DB::table('invoice_hdr_detail')
					->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'invoice_hdr.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'invoice_hdr.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'invoice_hdr.customer_invoicing_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->where('invoice_hdr.division_id', $divisionId)
					->where('invoice_hdr.product_category_id', $departmentId)
					->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), array($fromDate, $toDate))
					->where('invoice_hdr.invoice_status', '=', '1')
					->where('invoice_hdr_detail.invoice_hdr_status', '1')
					->select('divisions.division_name as branch', 'departments.department_name as department', 'invoice_hdr.invoice_no', 'customer_master.customer_name', 'city_db.city_name as city', 'invoice_hdr.invoice_date')
					->orderBy('invoice_hdr.invoice_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfReportInvoiced, DATEFORMAT);
				return $noOfReportInvoiced;
			} else if ($type == '12') {	//No of Report Due		
				$noOfReportDue =  DB::table('order_master')
					->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_master.expected_due_date)"), '=', $toDate)
					->where(function ($query) use ($toDate) {
						$query->whereNull('order_report_details.approving_date');
						$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"), '>', $toDate);
					})
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_master.expected_due_date as expected_due_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfReportDue, DATEFORMAT);
				return $noOfReportDue;
			} else if ($type == '13') {	//No. of Report Overdue
				$noOfReportOverDue = DB::table('order_master')
					->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_master.expected_due_date)"), '<', $toDate)
					->where(function ($query) use ($toDate) {
						$query->whereNull('order_report_details.approving_date');
						$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"), '>', $toDate);
					})
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_master.expected_due_date as expected_due_date', 'order_report_details.approving_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfReportOverDue, DATEFORMAT);
				return $noOfReportOverDue;
			} else if ($type == '14') {	//No. of Invoice Pending
				$noOfInvoicePending =  DB::table('order_master')
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->leftJoin('invoice_hdr_detail', function ($join) {
						$join->on('order_report_details.report_id', '=', 'invoice_hdr_detail.order_id');
						$join->where('invoice_hdr_detail.invoice_hdr_status', '1');
					})
					->leftJoin('invoice_hdr', function ($join) {
						$join->on('invoice_hdr.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id');
						$join->where('invoice_hdr.invoice_status', '1');
					})
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'invoice_hdr.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'invoice_hdr.customer_invoicing_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->where('order_master.division_id', $divisionId)
					->whereNull('order_master.order_sample_type')
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_report_details.approving_date)"), '<=', $toDate)
					->where(function ($query) use ($toDate) {
						$query->whereNull('invoice_hdr.invoice_date');
						$query->orWhere(DB::raw("DATE(invoice_hdr.invoice_date)"), '>', $toDate);
					})
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'invoice_hdr.invoice_no', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'invoice_hdr.invoice_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfInvoicePending, DATEFORMAT);
				return $noOfInvoicePending;
			} else if ($type == '15') {	//No. of Report Pending
				$noOfReportPending  = DB::table('order_master')
					->leftjoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
					->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
					->join('divisions', 'divisions.division_id', '=', 'order_master.division_id')
					->join('customer_master', 'customer_master.customer_id', '=', 'order_master.customer_id')
					->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
					->join('product_master_alias', 'product_master_alias.c_product_id', '=', 'order_master.sample_description_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->whereNull('order_report_details.approving_date')
					->select('divisions.division_name as branch', 'departments.department_name as department', 'order_master.order_no', 'product_master_alias.c_product_name as sample_name', 'customer_master.customer_name', 'city_db.city_name as city', 'order_master.order_date', 'order_report_details.approving_date')
					->orderBy('order_master.booking_date', 'ASC')
					->get();

				$models->formatTimeStampFromArray($noOfReportPending, DATEFORMAT);
				return $noOfReportPending;
			}
		}
	}

	/**
	 * Get list of companies on page load.
	 * Date : 23-July-18
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getDelayLogResultant($type, $divisionId, $departmentId, $departmentName, $postedData, $responseData)
	{

		if (!empty($type) && !empty($divisionId) && !empty($departmentId) && !empty($departmentName) && !empty($postedData) && !empty($responseData)) {

			$currentDate = defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
			$fromDate    = $postedData['date_from'];
			$toDate      = $postedData['date_to'];

			if ($type == '1') {		//Number of Reports Due
				return DB::table('order_master')
					->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_master.expected_due_date)"), '=', $toDate)
					->whereNull('order_report_details.approving_date')
					->whereNull('order_master.order_sample_type')
					->count();
			} else if ($type == '2') {		//Number of Reports issued/approved
				return DB::table('order_master')
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->where(DB::raw("DATE(order_master.expected_due_date)"), '=', $toDate)
					->where(DB::raw("DATE(order_report_details.approving_date)"), '=', $toDate)
					->whereNotIn('order_master.status', array('10', '12'))
					->whereNull('order_master.order_sample_type')
					->count();
			} else if ($type == '3') {		//Delay
				$returnValue = $responseData[$divisionId . '1'][$departmentName] - $responseData[$divisionId . '2'][$departmentName];
				return !empty($returnValue) && $returnValue > '0' ? $returnValue : '0';
			} else if ($type == '4') {		//Delay %
				$totalDelay     = $responseData[$divisionId . '3'][$departmentName];
				$totalReportDue = $responseData[$divisionId . '1'][$departmentName];
				$returnValue    = !empty($totalDelay) && !empty($totalReportDue) ? round(($totalDelay / $totalReportDue) * 100, 2) : '0';
				return !empty($returnValue) && $returnValue > '0' ? $returnValue . '%' : '0%';
			} else if ($type == '5') {		//Number of Delay reports
				return DB::table('order_master')
					->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_master.expected_due_date)"), '<', $toDate)
					->whereNull('order_master.order_sample_type')
					->where(function ($query) use ($toDate) {
						$query->whereNull('order_report_details.approving_date');
						$query->orWhere(DB::raw("DATE(order_report_details.approving_date)"), '>', $toDate);
					})
					->count();
			} else if ($type == '6') {		//Report Issued
				return DB::table('order_master')
					->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_master.expected_due_date)"), '<', $toDate)
					->where(DB::raw("DATE(order_report_details.approving_date)"), '=', $toDate)
					->whereNull('order_master.order_sample_type')
					->count();
			} else if ($type == '7') {		//Delay
				$returnValue = $responseData[$divisionId . '5'][$departmentName] - $responseData[$divisionId . '6'][$departmentName];
				return !empty($returnValue) && $returnValue > '0' ? $returnValue : '0';
			} else if ($type == '8') {		//Delay %
				$totalIssuedDelay  = $responseData[$divisionId . '7'][$departmentName];
				$totalReportIssued = $responseData[$divisionId . '5'][$departmentName];
				$returnValue = !empty($totalIssuedDelay) && !empty($totalReportIssued) ? round(($totalIssuedDelay / $totalReportIssued) * 100, 2) : '0';
				return !empty($returnValue) && $returnValue > '0' ? $returnValue . '%' : '0%';
			} else if ($type == '9') {		//Advance report issued
				return DB::table('order_master')
					->leftJoin('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.division_id', $divisionId)
					->where('order_master.product_category_id', $departmentId)
					->whereNotIn('order_master.status', array('10', '12'))
					->where(DB::raw("DATE(order_master.expected_due_date)"), '>', $toDate)
					->where(DB::raw("DATE(order_report_details.approving_date)"), '=', $toDate)
					->whereNull('order_master.order_sample_type')
					->count();
			} else if ($type == '10') {		//Total Pending Reports
				$returnValue = $responseData[$divisionId . '1'][$departmentName] + $responseData[$divisionId . '5'][$departmentName];
				return !empty($returnValue) && $returnValue > '0' ? $returnValue : '0';
			} else if ($type == '11') {		//Total issued
				$returnValue = $responseData[$divisionId . '2'][$departmentName] + $responseData[$divisionId . '6'][$departmentName];
				return !empty($returnValue) && $returnValue > '0' ? $returnValue : '0';
			} else if ($type == '12') {		//Total Issued report
				$returnValue = $responseData[$divisionId . '2'][$departmentName] + $responseData[$divisionId . '6'][$departmentName] + $responseData[$divisionId . '9'][$departmentName];
				return !empty($returnValue) && $returnValue > '0' ? $returnValue : '0';
			} else if ($type == '13') {		//Total Issued %
				$totalIssued = $responseData[$divisionId . '11'][$departmentName];
				$totalIssuedReports = $responseData[$divisionId . '10'][$departmentName];
				$returnValue = !empty($totalIssued) && !empty($totalIssuedReports) ? round(($totalIssued / $totalIssuedReports) * 100, 2) : '0';
				return !empty($returnValue) && $returnValue > '0' ? $returnValue . '%' : '0%';
			} else if ($type == '14') {		//Delay %
				$totalDelayFinal       = $responseData[$divisionId . '11'][$departmentName];
				$totalIssuedDelayFinal = $responseData[$divisionId . '10'][$departmentName];
				$totalIssuedDelayData  = !empty($totalDelayFinal) && !empty($totalIssuedDelayFinal) ? round(($totalDelayFinal / $totalIssuedDelayFinal) * 100, 2) : '0';
				return '100' - $totalIssuedDelayData . '%';
			}
		}
	}

	/**
	 * generate MIS Report::Account Sales Detail:daily_sales_invoice_detail
	 * Scope-2 (25-July-2018),Scope-3 (12-Dec-2018)
	 * @return \Illuminate\Http\Response
	 */
	public function daily_sales_invoice_detail($postedData, $docType)
	{

		global $order, $models;

		$salesInvoiceObj = DB::table('invoice_hdr')
			->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
			->join('divisions', 'divisions.division_id', 'invoice_hdr.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'invoice_hdr.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_invoicing_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->leftJoin('customer_gst_categories', 'customer_gst_categories.cgc_id', 'customer_master.customer_gst_category_id')
			->leftJoin('invoice_cancellation_dtls', 'invoice_cancellation_dtls.invoice_id', 'invoice_hdr.invoice_id')
			->join('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
			->select('divisions.division_name as division', 'departments.department_name as department', 'invoice_hdr.invoice_no as document_type', 'invoice_hdr.invoice_date as bill_date', 'invoice_hdr.invoice_date as bill_month', 'invoice_hdr.invoice_no as bill_no', 'customer_master.customer_code', 'customer_master.customer_name as party_name', 'customer_gst_categories.cgc_name as GST_category', 'state_db.state_name', 'city_db.city_name', 'InvoicingToCustomerDB.customer_code as invoicing_customer_code', 'InvoicingToCustomerDB.customer_name as invoicing_party_name', 'InvoicingToStateDB.state_name as invoicing_state_name', 'InvoicingToCityDB.city_name as invoicing_city_name', 'customer_master.customer_gst_no as gstin', 'InvoicingToCustomerDB.customer_gst_no as invoicing_gst_no', 'salesExecutive.name as sale_executive', 'invoice_cancellation_dtls.invoice_cancellation_description as cancellation_reason', 'invoice_cancellation_dtls.invoice_canc_approved_by as cancellation_approved_by', 'invoice_cancellation_dtls.invoice_canc_approved_date as cancellation_approved_date', 'order_master.po_no', 'invoice_hdr.net_amount as amount', 'invoice_hdr.extra_amount as conveyance', 'invoice_hdr.sgst_amount as sgst_value', 'invoice_hdr.cgst_amount as cgst_value', 'invoice_hdr.igst_amount as igst_value', 'invoice_hdr.net_total_amount as amt_payable', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_no as ref_inv_date', 'invoice_financial_years.ify_name as financial_year', 'order_master.remarks as remark', 'invoice_hdr.invoice_status')
			->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), array($postedData['date_from'], $postedData['date_to']));

		if (!empty($postedData['division_id'])) {
			$salesInvoiceObj->where('invoice_hdr.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$salesInvoiceObj->where('invoice_hdr.product_category_id', $postedData['product_category_id']);
		}
		$salesInvoiceObj->orderBy('customer_master.customer_name', 'ASC');
		$salesInvoiceObj->groupBy('invoice_hdr.invoice_id');
		$salesInvoiceData = $salesInvoiceObj->get();

		if (!empty($salesInvoiceData)) {
			foreach ($salesInvoiceData as $value) {
				$value->document_type  = $docType;
				$value->customer_code  = !empty($value->invoicing_customer_code)  ? $value->invoicing_customer_code : $value->customer_code;
				$value->party_name     = !empty($value->invoicing_party_name)  ? $value->invoicing_party_name : $value->party_name;
				$value->state_name     = !empty($value->invoicing_state_name)  ? $value->invoicing_state_name : $value->state_name;
				$value->city_name      = !empty($value->invoicing_city_name)  ? $value->invoicing_city_name : $value->city_name;
				$value->gstin          = !empty($value->invoicing_gst_no)  ? $value->invoicing_gst_no : $value->gstin;
				$value->sgst_value     = !empty($value->sgst_value) ? $value->sgst_value : '0.00';
				$value->cgst_value     = !empty($value->cgst_value) ? $value->cgst_value : '0.00';
				$value->igst_value     = !empty($value->igst_value) ? $value->igst_value : '0.00';
				$value->bill_date      = !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->bill_month     = !empty($value->bill_date) ? date('F', strtotime($value->bill_date)) : '';
				$value->cancellation_approved_date = !empty($value->cancellation_approved_date) ? date(DATEFORMATEXCEL, strtotime($value->cancellation_approved_date)) : '';
				$value->amount         = !empty($value->amount) ? $models->roundValue($value->amount - $value->conveyance) : '0.00';
				$value->amt_payable    = !empty($value->amt_payable) ? round($value->amt_payable) : '0.00';
				$value->ref_inv_no     = '';
				$value->ref_inv_date   = '';
				$value->remark         = '';
				if (!empty($value->invoice_status)) $value->invoice_status = $value->invoice_status == '1' ? 'Active' : 'Cancelled';
			}
		}
		return $models->convertObjectToArray($salesInvoiceData);
	}

	/**
	 * generate MIS Report::Account Sales Detail:daily_sales_invoice_detail
	 * Scope-2 (25-July-2018),Scope-3 (12-Dec-2018)
	 * @return \Illuminate\Http\Response
	 */
	public function debit_notes_auto_detail($postedData, $docType)
	{

		global $order, $models;

		$debitNotesAutoObj = DB::table('debit_notes')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', '=', 'debit_notes.invoice_id')
			->join('invoice_hdr_detail', 'debit_notes.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id')
			->join('divisions', 'divisions.division_id', 'debit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'debit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'debit_notes.customer_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->join('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->leftJoin('customer_gst_categories', 'customer_gst_categories.cgc_id', 'customer_master.customer_gst_category_id')
			->leftJoin('invoice_cancellation_dtls', 'invoice_cancellation_dtls.invoice_id', 'debit_notes.invoice_id')
			->select('divisions.division_name as division', 'departments.department_name as department', 'debit_notes.debit_note_no as document_type', 'debit_notes.debit_note_date as bill_date', 'invoice_hdr.invoice_date as bill_month', 'debit_notes.debit_note_no as bill_no',				'customer_master.customer_code', 'customer_master.customer_name as party_name', 'customer_gst_categories.cgc_name as GST_category', 'state_db.state_name', 'city_db.city_name', 'InvoicingToCustomerDB.customer_code as invoicing_customer_code', 'InvoicingToCustomerDB.customer_name as invoicing_party_name', 'InvoicingToStateDB.state_name as invoicing_state_name', 'InvoicingToCityDB.city_name as invoicing_city_name', 'customer_master.customer_gst_no as gstin', 'InvoicingToCustomerDB.customer_gst_no as invoicing_gst_no', 'salesExecutive.name as sale_executive', 'invoice_cancellation_dtls.invoice_cancellation_description as cancellation_reason', 'invoice_cancellation_dtls.invoice_canc_approved_by as cancellation_approved_by', 'invoice_cancellation_dtls.invoice_canc_approved_date as cancellation_approved_date', 'order_master.po_no', 'invoice_hdr.net_amount as amount', 'invoice_hdr.extra_amount as conveyance', 'debit_notes.debit_note_sgst_amount as sgst_value', 'debit_notes.debit_note_cgst_amount as cgst_value', 'debit_notes.debit_note_igst_amount as igst_value', 'debit_notes.debit_note_net_amount as amt_payable', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', 'invoice_financial_years.ify_name as financial_year', 'debit_notes.debit_note_remark as remark', 'invoice_hdr.invoice_status')
			->whereBetween(DB::raw("DATE(debit_notes.debit_note_date)"), array($postedData['date_from'], $postedData['date_to']))
			->where('debit_notes.debit_note_type_id', '1');
		if (!empty($postedData['division_id'])) {
			$debitNotesAutoObj->where('debit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$debitNotesAutoObj->where('debit_notes.product_category_id', $postedData['product_category_id']);
		}
		$debitNotesAutoObj->orderBy('customer_master.customer_name', 'ASC');
		$debitNotesAutoObj->groupBy('debit_notes.debit_note_no');
		$debitNotesAutoData = $debitNotesAutoObj->get();

		if (!empty($debitNotesAutoData)) {
			foreach ($debitNotesAutoData as $value) {
				$value->document_type  = $docType;
				$value->customer_code  = !empty($value->invoicing_customer_code)  ? $value->invoicing_customer_code : $value->customer_code;
				$value->party_name     = !empty($value->invoicing_party_name) ? $value->invoicing_party_name : $value->party_name;
				$value->state_name     = !empty($value->invoicing_state_name) ? $value->invoicing_state_name : $value->state_name;
				$value->city_name      = !empty($value->invoicing_city_name) ? $value->invoicing_city_name : $value->city_name;
				$value->gstin          = !empty($value->invoicing_gst_no)  ? $value->invoicing_gst_no : $value->gstin;
				$value->bill_date      = !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->bill_month     = !empty($value->bill_date) ? date('F', strtotime($value->bill_date)) : '';
				$value->cancellation_approved_date = !empty($value->cancellation_approved_date) ? date(DATEFORMATEXCEL, strtotime($value->cancellation_approved_date)) : '';
				$value->ref_inv_no     = !empty($value->ref_inv_no) ? $value->ref_inv_no : '';
				$value->ref_inv_date   = !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->amount         = !empty($value->amount) ? '-' . $models->roundValue($value->amount - $value->conveyance) : '0.00';
				$value->conveyance     = !empty($value->conveyance) && round($value->conveyance) > '0' ? '-' . $value->conveyance : '0.00';
				$value->sgst_value     = !empty($value->sgst_value) ? '-' . $value->sgst_value : '0.00';
				$value->cgst_value     = !empty($value->cgst_value) ? '-' . $value->cgst_value : '0.00';
				$value->igst_value     = !empty($value->igst_value) ? '-' . $value->igst_value : '0.00';
				$value->amt_payable    = !empty($value->amt_payable) ? '-' . round($value->amt_payable) : '0.00';
				if (!empty($value->invoice_status)) $value->invoice_status = $value->invoice_status == '1' ? 'Active' : 'Cancelled';
			}
		}
		return $models->convertObjectToArray($debitNotesAutoData);
	}

	/**
	 * generate MIS Report::Account Sales Detail:daily_sales_invoice_detail
	 * Scope-2 (25-July-2018),Scope-3 (12-Dec-2018)
	 * @return \Illuminate\Http\Response
	 */
	public function debit_notes_manual_detail($postedData, $docType)
	{

		global $order, $models;

		//***********************Debit Notes Manual With Invoice********************************************************************
		$debitNotesManualWithInvoiceObj = DB::table('debit_notes')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', '=', 'debit_notes.invoice_id')
			->join('invoice_hdr_detail', 'debit_notes.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id')
			->join('divisions', 'divisions.division_id', 'debit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'debit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'debit_notes.customer_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->join('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->leftJoin('customer_gst_categories', 'customer_gst_categories.cgc_id', 'customer_master.customer_gst_category_id')
			->leftJoin('invoice_cancellation_dtls', 'invoice_cancellation_dtls.invoice_id', 'debit_notes.invoice_id')
			->select('divisions.division_name as division', 'departments.department_name as department', 'debit_notes.debit_note_no as document_type', 'debit_notes.debit_note_date as bill_date', 'invoice_hdr.invoice_date as bill_month', 'debit_notes.debit_note_no as bill_no', 'customer_master.customer_code', 'customer_master.customer_name as party_name', 'customer_gst_categories.cgc_name as GST_category', 'state_db.state_name', 'city_db.city_name', 'InvoicingToCustomerDB.customer_code as invoicing_customer_code', 'InvoicingToCustomerDB.customer_name as invoicing_party_name', 'InvoicingToStateDB.state_name as invoicing_state_name', 'InvoicingToCityDB.city_name as invoicing_city_name', 'customer_master.customer_gst_no as gstin', 'InvoicingToCustomerDB.customer_gst_no as invoicing_gst_no', 'salesExecutive.name as sale_executive', 'invoice_cancellation_dtls.invoice_cancellation_description as cancellation_reason', 'invoice_cancellation_dtls.invoice_canc_approved_by as cancellation_approved_by', 'invoice_cancellation_dtls.invoice_canc_approved_date as cancellation_approved_date', 'order_master.po_no', 'debit_notes.debit_note_amount as amount', 'invoice_hdr.extra_amount as conveyance', 'debit_notes.debit_note_sgst_amount as sgst_value', 'debit_notes.debit_note_cgst_amount as cgst_value', 'debit_notes.debit_note_igst_amount as igst_value', 'debit_notes.debit_note_net_amount as amt_payable', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', 'invoice_financial_years.ify_name as financial_year', 'debit_notes.debit_reference_no', 'debit_notes.debit_note_remark as remark', 'invoice_hdr.invoice_status')
			->whereBetween(DB::raw("DATE(debit_notes.debit_note_date)"), array($postedData['date_from'], $postedData['date_to']))
			->where('debit_notes.debit_note_type_id', '2')
			->whereNull('debit_notes.debit_reference_no')
			->whereNotNull('debit_notes.invoice_id');
		if (!empty($postedData['division_id'])) {
			$debitNotesManualWithInvoiceObj->where('debit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$debitNotesManualWithInvoiceObj->where('debit_notes.product_category_id', $postedData['product_category_id']);
		}
		$debitNotesManualWithInvoiceObj->orderBy('customer_master.customer_name', 'ASC');
		$debitNotesManualWithInvoiceObj->groupBy('debit_notes.debit_note_no');
		$debitNotesManualWithInvoiceData = $debitNotesManualWithInvoiceObj->get();

		if (!empty($debitNotesManualWithInvoiceData)) {
			foreach ($debitNotesManualWithInvoiceData as $value) {
				$value->document_type  = $docType;
				$value->customer_code  = !empty($value->invoicing_customer_code)  ? $value->invoicing_customer_code : $value->customer_code;
				$value->party_name     = !empty($value->invoicing_party_name) ? $value->invoicing_party_name : $value->party_name;
				$value->state_name     = !empty($value->invoicing_state_name) ? $value->invoicing_state_name : $value->state_name;
				$value->city_name      = !empty($value->invoicing_city_name) ? $value->invoicing_city_name : $value->city_name;
				$value->gstin          = !empty($value->invoicing_gst_no)  ? $value->invoicing_gst_no : $value->gstin;
				$value->bill_date      = !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->bill_month     = !empty($value->bill_date) ? date('F', strtotime($value->bill_date)) : '';
				$value->cancellation_approved_date = !empty($value->cancellation_approved_date) ? date(DATEFORMATEXCEL, strtotime($value->cancellation_approved_date)) : '';
				$value->ref_inv_no     = !empty($value->ref_inv_no) ? $value->ref_inv_no : $value->debit_reference_no;
				$value->ref_inv_date   = !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->amount         = !empty($value->amount) ? $models->roundValue($value->amount) : '0.00';
				$value->conveyance     = '0.00';
				$value->sgst_value     = !empty($value->sgst_value) ? $value->sgst_value : '0.00';
				$value->cgst_value     = !empty($value->cgst_value) ? $value->cgst_value : '0.00';
				$value->igst_value     = !empty($value->igst_value) ? $value->igst_value : '0.00';
				$value->amt_payable    = !empty($value->amt_payable) ? round($value->amt_payable) : '0.00';
				if (!empty($value->invoice_status)) $value->invoice_status = $value->invoice_status == '1' ? 'Active' : 'Cancelled';
			}
		}
		$debitNotesManualWithInvoiceData = $models->convertObjectToArray($debitNotesManualWithInvoiceData);
		//***********************/Debit Notes Manual With Invoice**********************************************************************

		//***********************Debit Notes Manual Without Invoice********************************************************************
		$debitNotesManualWithoutInvoiceObj = DB::table('debit_notes')
			->leftJoin('invoice_hdr', 'invoice_hdr.invoice_id', '=', 'debit_notes.invoice_id')
			->leftJoin('invoice_hdr_detail', 'debit_notes.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id')
			->join('divisions', 'divisions.division_id', 'debit_notes.division_id')
			->leftJoin('department_product_categories_link', 'department_product_categories_link.product_category_id', 'debit_notes.product_category_id')
			->leftJoin('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'debit_notes.customer_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->leftJoin('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
			->leftJoin('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->leftJoin('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->leftJoin('customer_gst_categories', 'customer_gst_categories.cgc_id', 'customer_master.customer_gst_category_id')
			->leftJoin('invoice_cancellation_dtls', 'invoice_cancellation_dtls.invoice_id', 'debit_notes.invoice_id')
			->select('divisions.division_name as division', 'departments.department_name as department', 'debit_notes.debit_note_no as document_type', 'debit_notes.debit_note_date as bill_date', 'invoice_hdr.invoice_date as bill_month', 'debit_notes.debit_note_no as bill_no', 'customer_master.customer_code', 'customer_master.customer_name as party_name', 'customer_gst_categories.cgc_name as GST_category', 'state_db.state_name', 'city_db.city_name', 'InvoicingToCustomerDB.customer_code as invoicing_customer_code', 'InvoicingToCustomerDB.customer_name as invoicing_party_name', 'InvoicingToStateDB.state_name as invoicing_state_name', 'InvoicingToCityDB.city_name as invoicing_city_name', 'customer_master.customer_gst_no as gstin', 'InvoicingToCustomerDB.customer_gst_no as invoicing_gst_no', 'salesExecutive.name as sale_executive', 'invoice_cancellation_dtls.invoice_cancellation_description as cancellation_reason', 'invoice_cancellation_dtls.invoice_canc_approved_by as cancellation_approved_by', 'invoice_cancellation_dtls.invoice_canc_approved_date as cancellation_approved_date', 'order_master.po_no', 'debit_notes.debit_note_amount as amount', 'invoice_hdr.extra_amount as conveyance', 'debit_notes.debit_note_sgst_amount as sgst_value', 'debit_notes.debit_note_cgst_amount as cgst_value', 'debit_notes.debit_note_igst_amount as igst_value', 'debit_notes.debit_note_net_amount as amt_payable', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', 'invoice_financial_years.ify_name as financial_year', 'debit_notes.debit_reference_no', 'debit_notes.debit_note_remark as remark', 'invoice_hdr.invoice_status')
			->whereBetween(DB::raw("DATE(debit_notes.debit_note_date)"), array($postedData['date_from'], $postedData['date_to']))
			->where('debit_notes.debit_note_type_id', '2')
			->whereNotNull('debit_notes.debit_reference_no')
			->whereNull('debit_notes.invoice_id');
		if (!empty($postedData['division_id'])) {
			$debitNotesManualWithoutInvoiceObj->where('debit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$debitNotesManualWithoutInvoiceObj->where('debit_notes.product_category_id', $postedData['product_category_id']);
		}
		$debitNotesManualWithoutInvoiceObj->orderBy('customer_master.customer_name', 'ASC');
		$debitNotesManualWithoutInvoiceObj->groupBy('debit_notes.debit_note_no');
		$debitNotesManualWithoutInvoiceData = $debitNotesManualWithoutInvoiceObj->get();

		if (!empty($debitNotesManualWithoutInvoiceData)) {
			foreach ($debitNotesManualWithoutInvoiceData as $value) {
				$value->document_type  = $docType;
				$value->customer_code  = !empty($value->invoicing_customer_code)  ? $value->invoicing_customer_code : $value->customer_code;
				$value->party_name     = !empty($value->invoicing_party_name) ? $value->invoicing_party_name : $value->party_name;
				$value->state_name     = !empty($value->invoicing_state_name) ? $value->invoicing_state_name : $value->state_name;
				$value->city_name      = !empty($value->invoicing_city_name) ? $value->invoicing_city_name : $value->city_name;
				$value->gstin          = !empty($value->invoicing_gst_no)  ? $value->invoicing_gst_no : $value->gstin;
				$value->bill_date      = !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->bill_month     = !empty($value->bill_date) ? date('F', strtotime($value->bill_date)) : '';
				$value->cancellation_approved_date = !empty($value->cancellation_approved_date) ? date(DATEFORMATEXCEL, strtotime($value->cancellation_approved_date)) : '';
				$value->ref_inv_no     = !empty($value->ref_inv_no) ? $value->ref_inv_no : $value->debit_reference_no;
				$value->ref_inv_date   = !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->amount         = !empty($value->amount) ? $models->roundValue($value->amount) : '0.00';
				$value->conveyance     = '0.00';
				$value->sgst_value     = !empty($value->sgst_value) ? $value->sgst_value : '0.00';
				$value->cgst_value     = !empty($value->cgst_value) ? $value->cgst_value : '0.00';
				$value->igst_value     = !empty($value->igst_value) ? $value->igst_value : '0.00';
				$value->amt_payable    = !empty($value->amt_payable) ? round($value->amt_payable) : '0.00';
				if (!empty($value->invoice_status)) $value->invoice_status = $value->invoice_status == '1' ? 'Active' : 'Cancelled';
			}
		}
		$debitNotesManualWithoutInvoiceData = $models->convertObjectToArray($debitNotesManualWithoutInvoiceData);
		//***********************/Debit Notes Manual Without Invoice********************************************************************

		return array_merge($debitNotesManualWithInvoiceData, $debitNotesManualWithoutInvoiceData);
	}

	/**
	 * generate MIS Report::Account Sales Detail:daily_sales_invoice_detail
	 * Scope-2 (25-July-2018),Scope-3 (12-Dec-2018)
	 * @return \Illuminate\Http\Response
	 */
	public function credit_note_auto_detail($postedData, $docType)
	{

		global $order, $models;

		$creditNoteAutoObj = DB::table('credit_notes')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', '=', 'credit_notes.invoice_id')
			->join('invoice_hdr_detail', 'credit_notes.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->join('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->leftJoin('customer_gst_categories', 'customer_gst_categories.cgc_id', 'customer_master.customer_gst_category_id')
			->leftJoin('invoice_cancellation_dtls', 'invoice_cancellation_dtls.invoice_id', 'credit_notes.invoice_id')
			->select('divisions.division_name as division', 'departments.department_name as department', 'credit_notes.credit_note_no as document_type', 'credit_notes.credit_note_date as bill_date', 'invoice_hdr.invoice_date as bill_month', 'credit_notes.credit_note_no as bill_no', 'customer_master.customer_code', 'customer_master.customer_name as party_name', 'customer_gst_categories.cgc_name as GST_category', 'state_db.state_name', 'city_db.city_name', 'InvoicingToCustomerDB.customer_code as invoicing_customer_code', 'InvoicingToCustomerDB.customer_name as invoicing_party_name', 'InvoicingToStateDB.state_name as invoicing_state_name', 'InvoicingToCityDB.city_name as invoicing_city_name', 'customer_master.customer_gst_no as gstin', 'InvoicingToCustomerDB.customer_gst_no as invoicing_gst_no', 'salesExecutive.name as sale_executive', 'invoice_cancellation_dtls.invoice_cancellation_description as cancellation_reason', 'invoice_cancellation_dtls.invoice_canc_approved_by as cancellation_approved_by', 'invoice_cancellation_dtls.invoice_canc_approved_date as cancellation_approved_date', 'order_master.po_no', 'invoice_hdr.net_amount as amount', 'invoice_hdr.extra_amount as conveyance', 'credit_notes.credit_note_sgst_amount as sgst_value', 'credit_notes.credit_note_cgst_amount as cgst_value', 'credit_notes.credit_note_igst_amount as igst_value', 'credit_notes.credit_note_net_amount as amt_payable', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', 'invoice_financial_years.ify_name as financial_year', 'credit_notes.credit_note_remark as remark', 'invoice_hdr.invoice_status')
			->whereBetween(DB::raw("DATE(credit_notes.credit_note_date)"), array($postedData['date_from'], $postedData['date_to']))
			->where('credit_notes.credit_note_type_id', '1');
		if (!empty($postedData['division_id'])) {
			$creditNoteAutoObj->where('credit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$creditNoteAutoObj->where('credit_notes.product_category_id', $postedData['product_category_id']);
		}
		$creditNoteAutoObj->orderBy('customer_master.customer_name', 'ASC');
		$creditNoteAutoObj->groupBy('credit_notes.credit_note_no');
		$creditNoteAutoData = $creditNoteAutoObj->get();

		if (!empty($creditNoteAutoData)) {
			foreach ($creditNoteAutoData as $value) {
				$value->document_type  = $docType;
				$value->customer_code  = !empty($value->invoicing_customer_code)  ? $value->invoicing_customer_code : $value->customer_code;
				$value->party_name     = !empty($value->invoicing_party_name) ? $value->invoicing_party_name : $value->party_name;
				$value->state_name     = !empty($value->invoicing_state_name) ? $value->invoicing_state_name : $value->state_name;
				$value->city_name      = !empty($value->invoicing_city_name) ? $value->invoicing_city_name : $value->city_name;
				$value->gstin          = !empty($value->invoicing_gst_no)  ? $value->invoicing_gst_no : $value->gstin;
				$value->bill_date      = !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->bill_month     = !empty($value->bill_date) ? date('F', strtotime($value->bill_date)) : '';
				$value->cancellation_approved_date = !empty($value->cancellation_approved_date) ? date(DATEFORMATEXCEL, strtotime($value->cancellation_approved_date)) : '';
				$value->ref_inv_no     = !empty($value->ref_inv_no) ? $value->ref_inv_no : '';
				$value->ref_inv_date   = !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->amount         = !empty($value->amount) ? '-' . $models->roundValue($value->amount - $value->conveyance) : '0.00';
				$value->conveyance     = !empty($value->conveyance) && round($value->conveyance) > '0' ? '-' . $value->conveyance : '0.00';
				$value->sgst_value     = !empty($value->sgst_value) ? '-' . $value->sgst_value : '0.00';
				$value->cgst_value     = !empty($value->cgst_value) ? '-' . $value->cgst_value : '0.00';
				$value->igst_value     = !empty($value->igst_value) ? '-' . $value->igst_value : '0.00';
				$value->amt_payable    = !empty($value->amt_payable) ? '-' . round($value->amt_payable) : '0.00';
				if (!empty($value->invoice_status)) $value->invoice_status = $value->invoice_status == '1' ? 'Active' : 'Cancelled';
			}
		}
		return $models->convertObjectToArray($creditNoteAutoData);
	}

	/**
	 * generate MIS Report::Account Sales Detail:daily_sales_invoice_detail
	 * Scope-2 (25-July-2018),Scope-3 (12-Dec-2018)
	 * @return \Illuminate\Http\Response
	 */
	public function credit_note_manual_detail($postedData, $docType)
	{

		global $order, $models;

		//*************Credit Note Manual With Invoice***********************************************************	
		$creditNoteManualWithInvoiceObj = DB::table('credit_notes')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', '=', 'credit_notes.invoice_id')
			->join('invoice_hdr_detail', 'credit_notes.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->join('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->leftJoin('customer_gst_categories', 'customer_gst_categories.cgc_id', 'customer_master.customer_gst_category_id')
			->leftJoin('invoice_cancellation_dtls', 'invoice_cancellation_dtls.invoice_id', 'credit_notes.invoice_id')
			->select('divisions.division_name as division', 'departments.department_name as department', 'credit_notes.credit_note_no as document_type', 'credit_notes.credit_note_date as bill_date', 'invoice_hdr.invoice_date as bill_month', 'credit_notes.credit_note_no as bill_no', 'customer_master.customer_code', 'customer_master.customer_name as party_name', 'customer_gst_categories.cgc_name as GST_category', 'state_db.state_name', 'city_db.city_name', 'InvoicingToCustomerDB.customer_code as invoicing_customer_code', 'InvoicingToCustomerDB.customer_name as invoicing_party_name', 'InvoicingToStateDB.state_name as invoicing_state_name', 'InvoicingToCityDB.city_name as invoicing_city_name', 'customer_master.customer_gst_no as gstin', 'InvoicingToCustomerDB.customer_gst_no as invoicing_gst_no', 'salesExecutive.name as sale_executive', 'invoice_cancellation_dtls.invoice_cancellation_description as cancellation_reason', 'invoice_cancellation_dtls.invoice_canc_approved_by as cancellation_approved_by', 'invoice_cancellation_dtls.invoice_canc_approved_date as cancellation_approved_date', 'order_master.po_no', 'credit_notes.credit_note_amount as amount', 'invoice_hdr.extra_amount as conveyance', 'credit_notes.credit_note_sgst_amount as sgst_value', 'credit_notes.credit_note_cgst_amount as cgst_value', 'credit_notes.credit_note_igst_amount as igst_value', 'credit_notes.credit_note_net_amount as amt_payable', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', 'invoice_financial_years.ify_name as financial_year', 'credit_notes.credit_reference_no', 'credit_notes.credit_note_remark as remark', 'invoice_hdr.invoice_status')
			->whereBetween(DB::raw("DATE(credit_notes.credit_note_date)"), array($postedData['date_from'], $postedData['date_to']))
			->where('credit_notes.credit_note_type_id', '2')
			->whereNull('credit_notes.credit_reference_no')
			->whereNotNull('credit_notes.invoice_id');
		if (!empty($postedData['division_id'])) {
			$creditNoteManualWithInvoiceObj->where('credit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$creditNoteManualWithInvoiceObj->where('credit_notes.product_category_id', $postedData['product_category_id']);
		}
		$creditNoteManualWithInvoiceObj->groupBy('credit_notes.credit_note_no');
		$creditNoteManualWithInvoiceObj->orderBy('customer_master.customer_name', 'ASC');
		$creditNoteManualWithInvoiceData = $creditNoteManualWithInvoiceObj->get();

		if (!empty($creditNoteManualWithInvoiceData)) {
			foreach ($creditNoteManualWithInvoiceData as $value) {
				$value->document_type  = $docType;
				$value->customer_code  = !empty($value->invoicing_customer_code)  ? $value->invoicing_customer_code : $value->customer_code;
				$value->party_name     = !empty($value->invoicing_party_name) ? $value->invoicing_party_name : $value->party_name;
				$value->state_name     = !empty($value->invoicing_state_name) ? $value->invoicing_state_name : $value->state_name;
				$value->city_name      = !empty($value->invoicing_city_name) ? $value->invoicing_city_name : $value->city_name;
				$value->gstin          = !empty($value->invoicing_gst_no)  ? $value->invoicing_gst_no : $value->gstin;
				$value->bill_date      = !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->bill_month     = !empty($value->bill_date) ? date('F', strtotime($value->bill_date)) : '';
				$value->cancellation_approved_date = !empty($value->cancellation_approved_date) ? date(DATEFORMATEXCEL, strtotime($value->cancellation_approved_date)) : '';
				$value->ref_inv_no     = !empty($value->ref_inv_no) ? $value->ref_inv_no : $value->credit_reference_no;
				$value->ref_inv_date   = !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->amount         = !empty($value->amount) ? '-' . $models->roundValue($value->amount) : '0.00';
				$value->conveyance     = '0.00';
				$value->sgst_value     = !empty($value->sgst_value) ? '-' . $value->sgst_value : '0.00';
				$value->cgst_value     = !empty($value->cgst_value) ? '-' . $value->cgst_value : '0.00';
				$value->igst_value     = !empty($value->igst_value) ? '-' . $value->igst_value : '0.00';
				$value->amt_payable    = !empty($value->amt_payable) ? '-' . round($value->amt_payable) : '0.00';
				if (!empty($value->invoice_status)) $value->invoice_status = $value->invoice_status == '1' ? 'Active' : 'Cancelled';
			}
		}
		$creditNoteManualWithInvoiceData = $models->convertObjectToArray($creditNoteManualWithInvoiceData);
		//*************/Credit Note Manual With Invoice************************************************************************

		//*************Credit Note Manual Without Invoice*********************************************************************
		$creditNoteManualWithoutInvoiceObj = DB::table('credit_notes')
			->leftJoin('invoice_hdr', 'invoice_hdr.invoice_id', '=', 'credit_notes.invoice_id')
			->leftJoin('invoice_hdr_detail', 'credit_notes.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->leftJoin('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->leftJoin('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->leftJoin('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
			->leftJoin('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->leftJoin('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->leftJoin('customer_gst_categories', 'customer_gst_categories.cgc_id', 'customer_master.customer_gst_category_id')
			->leftJoin('invoice_cancellation_dtls', 'invoice_cancellation_dtls.invoice_id', 'invoice_hdr.invoice_id')
			->select('divisions.division_name as division', 'departments.department_name as department', 'credit_notes.credit_note_no as document_type', 'credit_notes.credit_note_date as bill_date', 'invoice_hdr.invoice_date as bill_month', 'credit_notes.credit_note_no as bill_no', 'customer_master.customer_code', 'customer_master.customer_name as party_name', 'customer_gst_categories.cgc_name as GST_category', 'state_db.state_name', 'city_db.city_name', 'InvoicingToCustomerDB.customer_code as invoicing_customer_code', 'InvoicingToCustomerDB.customer_name as invoicing_party_name', 'InvoicingToStateDB.state_name as invoicing_state_name', 'InvoicingToCityDB.city_name as invoicing_city_name', 'customer_master.customer_gst_no as gstin', 'InvoicingToCustomerDB.customer_gst_no as invoicing_gst_no', 'salesExecutive.name as sale_executive', 'invoice_cancellation_dtls.invoice_cancellation_description as cancellation_reason', 'invoice_cancellation_dtls.invoice_canc_approved_by as cancellation_approved_by', 'invoice_cancellation_dtls.invoice_canc_approved_date as cancellation_approved_date', 'order_master.po_no', 'credit_notes.credit_note_amount as amount', 'invoice_hdr.extra_amount as conveyance', 'credit_notes.credit_note_sgst_amount as sgst_value', 'credit_notes.credit_note_cgst_amount as cgst_value', 'credit_notes.credit_note_igst_amount as igst_value', 'credit_notes.credit_note_net_amount as amt_payable', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', 'invoice_financial_years.ify_name as financial_year', 'credit_notes.credit_reference_no', 'credit_notes.credit_note_remark as remark', 'invoice_hdr.invoice_status')
			->whereBetween(DB::raw("DATE(credit_notes.credit_note_date)"), array($postedData['date_from'], $postedData['date_to']))
			->where('credit_notes.credit_note_type_id', '2')
			->whereNotNull('credit_notes.credit_reference_no')
			->whereNull('credit_notes.invoice_id');

		if (!empty($postedData['division_id'])) {
			$creditNoteManualWithoutInvoiceObj->where('credit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$creditNoteManualWithoutInvoiceObj->where('credit_notes.product_category_id', $postedData['product_category_id']);
		}
		$creditNoteManualWithoutInvoiceObj->groupBy('credit_notes.credit_note_no');
		$creditNoteManualWithoutInvoiceObj->orderBy('customer_master.customer_name', 'ASC');
		$creditNoteManualWithoutInvoiceData = $creditNoteManualWithoutInvoiceObj->get();

		if (!empty($creditNoteManualWithoutInvoiceData)) {
			foreach ($creditNoteManualWithoutInvoiceData as $value) {
				$value->document_type  = $docType;
				$value->customer_code  = !empty($value->invoicing_customer_code)  ? $value->invoicing_customer_code : $value->customer_code;
				$value->party_name     = !empty($value->invoicing_party_name) ? $value->invoicing_party_name : $value->party_name;
				$value->state_name     = !empty($value->invoicing_state_name) ? $value->invoicing_state_name : $value->state_name;
				$value->city_name      = !empty($value->invoicing_city_name) ? $value->invoicing_city_name : $value->city_name;
				$value->gstin          = !empty($value->invoicing_gst_no)  ? $value->invoicing_gst_no : $value->gstin;
				$value->bill_date      = !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->bill_month     = !empty($value->bill_date) ? date('F', strtotime($value->bill_date)) : '';
				$value->cancellation_approved_date = !empty($value->cancellation_approved_date) ? date(DATEFORMATEXCEL, strtotime($value->cancellation_approved_date)) : '';
				$value->ref_inv_no     = !empty($value->ref_inv_no) ? $value->ref_inv_no : $value->credit_reference_no;
				$value->ref_inv_date   = !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->amount         = !empty($value->amount) ? '-' . $models->roundValue($value->amount) : '0.00';
				$value->conveyance     = '0.00';
				$value->sgst_value     = !empty($value->sgst_value) ? '-' . $value->sgst_value : '0.00';
				$value->cgst_value     = !empty($value->cgst_value) ? '-' . $value->cgst_value : '0.00';
				$value->igst_value     = !empty($value->igst_value) ? '-' . $value->igst_value : '0.00';
				$value->amt_payable    = !empty($value->amt_payable) ? '-' . round($value->amt_payable) : '0.00';
				if (!empty($value->invoice_status)) $value->invoice_status = $value->invoice_status == '1' ? 'Active' : 'Cancelled';
			}
		}
		$creditNoteManualWithoutInvoiceData = $models->convertObjectToArray($creditNoteManualWithoutInvoiceData);
		//*************/Credit Note Manual Without Invoice*********************************************************************

		return array_merge($creditNoteManualWithInvoiceData, $creditNoteManualWithoutInvoiceData);
	}

	/**
	 * TAT Report : Getting Testing Pending Reason Detail
	 * Created By : Praveen Singh
	 * Created On : 13-June-2019
	 */
	public function getPendingTestingReasonDetail($orderId)
	{
		$returnData = array();
		$data = DB::table('schedulings')->join('equipment_type', 'equipment_type.equipment_id', 'schedulings.equipment_type_id')->where('schedulings.order_id', $orderId)->where('schedulings.status', '2')->whereNotNull('schedulings.notes')->pluck('schedulings.notes', 'equipment_type.equipment_name')->all();
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$returnData[] = $key . '(' . $value . ')';
			}
		}
		return !empty($returnData) ? implode(',', $returnData) : '';
	}

	/**
	 * TAT Report : Getting Order Process Stage Detail
	 * Created By : Praveen Singh
	 * Created On : 13-June-2019
	 */
	public function getOrderProcessStageDetail($values, $isCancelledStatus, $isOrderSampleType)
	{

		global $order, $models;

		//***************Equipment Information****************************************************************
		$allowedEquipmentIds = defined('ALLOWED_TAT_EQUIPMENTS') && !empty(ALLOWED_TAT_EQUIPMENTS) ? array_values(explode(',', trim(ALLOWED_TAT_EQUIPMENTS))) : array(1, 16, 81, 8, 14, 13, 20, 22, 83, 369, 28, 256, 99, 15, 113, 10, 11, 98, 12, 328);
		if (!empty($allowedEquipmentIds)) {
			foreach ($allowedEquipmentIds as $equipmentId) {
				$allowedEquipment = DB::table('equipment_type')->select('equipment_type.equipment_id', 'equipment_type.equipment_name')->where('equipment_type.equipment_id', $equipmentId)->first();
				if (!empty($allowedEquipment)) {
					$equipmentName		= $allowedEquipment->equipment_name;
					$equipmentId 		= $allowedEquipment->equipment_id;
					$statusEquipment		= $this->checkEquipmentAllotStatus($values->order_id, $equipmentId);
					$values->$equipmentName 	= $isCancelledStatus ? '' : $statusEquipment;
				}
			}
		}
		$values->Others = $isCancelledStatus ? '' : $this->checkEquipmentAllotStatusOthers($values->order_id, $allowedEquipmentIds);
		$values->testing_status = $isCancelledStatus ? '' : $this->checkTestingStatusOfOrder($values->order_id);
		$values->testing_pending_reason = $isCancelledStatus ? '' : $this->getPendingTestingReasonDetail($values->order_id);
		//**************/Equipment Information****************************************************************

		//***************Section Incharge Detail**************************************************************
		$sectionInchargeDetail = DB::table('order_process_log')
			->join('order_master', 'order_master.order_id', 'order_process_log.opl_order_id')
			->join('order_status', 'order_status.order_status_id', 'order_process_log.opl_order_status_id')
			->join('users as usersReport', 'usersReport.id', 'order_process_log.opl_user_id')
			->select('order_process_log.*', 'order_status.order_status_name', 'usersReport.name as usersReportName', 'order_master.incharge_reviewing_date')
			->where('order_process_log.opl_order_id', $values->order_id)
			->where('order_process_log.opl_order_status_id', '4')
			->where('order_process_log.opl_amend_status', '0')
			->whereNotNull('order_master.incharge_reviewing_date')
			->whereNotNull('order_process_log.opl_user_id')
			->orderBy('order_process_log.opl_id', 'DESC')
			->first();

		if (!empty($sectionInchargeDetail->incharge_reviewing_date) && !empty($values->testing_status) && $values->testing_status == 'C') {
			$values->incharge_reviewing_date = $isCancelledStatus ? '' : date(DATEFORMATEXCEL, strtotime($sectionInchargeDetail->incharge_reviewing_date));
			$values->incharge_reviewing_time = $isCancelledStatus ? '' : date('h:i A', strtotime($sectionInchargeDetail->incharge_reviewing_date));
			$values->incharge_reviewed_by    = $isCancelledStatus ? '' : $sectionInchargeDetail->usersReportName;
		} else {
			$values->incharge_reviewing_date = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->incharge_reviewing_time = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->incharge_reviewed_by    = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
		}
		//***************Section Incharge Detail**************************************************************

		//***************Reviewing Detail*********************************************************************
		$reviewingDetail = DB::table('order_process_log')
			->join('order_report_details', 'order_report_details.report_id', 'order_process_log.opl_order_id')
			->join('order_status', 'order_status.order_status_id', 'order_process_log.opl_order_status_id')
			->join('users as usersReport', 'usersReport.id', 'order_process_log.opl_user_id')
			->select('order_process_log.*', 'order_status.order_status_name', 'usersReport.name as usersReportName', 'order_report_details.reviewing_date')
			->where('order_process_log.opl_order_id', $values->order_id)
			->where('order_process_log.opl_order_status_id', '5')
			->where('order_process_log.opl_amend_status', '0')
			->whereNotNull('order_report_details.reviewing_date')
			->orderBy('order_process_log.opl_id', 'DESC')
			->first();

		if (!empty($reviewingDetail->reviewing_date) && !empty($values->testing_status) && $values->testing_status == 'C') {
			$values->reviewed_date = $isCancelledStatus ? '' : date(DATEFORMATEXCEL, strtotime($reviewingDetail->reviewing_date));
			$values->reviewed_time = $isCancelledStatus ? '' : date('h:i A', strtotime($reviewingDetail->reviewing_date));
			$values->reviewed_by   = $isCancelledStatus ? '' : $reviewingDetail->usersReportName;
		} else {
			$values->reviewed_date = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->reviewed_time = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->reviewed_by   = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
		}
		//***************Reviewing Detail********************************************************************

		//***************Approving Detail*********************************************************************
		$approvingDetail = DB::table('order_process_log')
			->join('order_report_details', 'order_report_details.report_id', 'order_process_log.opl_order_id')
			->join('order_status', 'order_status.order_status_id', 'order_process_log.opl_order_status_id')
			->join('users as usersReport', 'usersReport.id', 'order_process_log.opl_user_id')
			->select('order_process_log.*', 'order_status.order_status_name', 'usersReport.name as usersReportName', 'order_report_details.approving_date')
			->where('order_process_log.opl_order_id', $values->order_id)
			->where('order_process_log.opl_order_status_id', '7')
			->where('order_process_log.opl_amend_status', '0')
			->whereNotNull('order_report_details.approving_date')
			->orderBy('order_process_log.opl_id', 'DESC')
			->first();

		if (!empty($approvingDetail->approving_date) && !empty($values->reviewed_date) && $models->isValidDate($values->reviewed_date)) {
			$values->approved_date = $isCancelledStatus ? '' : date(DATEFORMATEXCEL, strtotime($approvingDetail->approving_date));
			$values->approved_time = $isCancelledStatus ? '' : date('h:i A', strtotime($approvingDetail->approving_date));
			$values->approved_by   = $isCancelledStatus ? '' : $approvingDetail->usersReportName;
		} else {
			$values->approved_date = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->approved_time = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->approved_by   = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
		}
		//***************Approving Detail********************************************************************

		//***************Email Detail************************************************************************
		$emailedData = DB::table('order_mail_dtl')
			->join('users as mailByTb', 'mailByTb.id', 'order_mail_dtl.mail_by')
			->select('order_mail_dtl.mail_date', 'mailByTb.name as mailBy')
			->where('order_mail_dtl.mail_content_type', '3')
			->where('order_mail_dtl.mail_status', '1')
			->where('order_mail_dtl.mail_active_type', '=', '1')
			->where('order_mail_dtl.order_id', $values->order_id)
			->whereNotNull('order_mail_dtl.mail_date')
			->orderBy('order_mail_dtl.mail_id', 'DESC')
			->first();

		if (!empty($emailedData->mail_date) && !empty($values->approved_date) && $models->isValidDate($values->approved_date)) {
			$values->emailed_date   = $isCancelledStatus ? '' : !empty($emailedData->mail_date) ? date(DATEFORMATEXCEL, strtotime($emailedData->mail_date)) : '';
			$values->emailed_time   = $isCancelledStatus ? '' : !empty($emailedData->mail_date) ? date('h:i A', strtotime($emailedData->mail_date)) : '';
			$values->emailed_by     = $isCancelledStatus ? '' : !empty($emailedData->mailBy) ? $emailedData->mailBy : '';
		} else {
			$values->emailed_date   = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->emailed_time   = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->emailed_by     = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
		}
		//***************/Email Detail*************************************************************************

		//***************Invoicing Detail**********************************************************************
		$invoicingData = DB::table('invoice_hdr_detail')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('users as invoicedByTb', 'invoicedByTb.id', 'invoice_hdr.created_by')
			->select('invoice_hdr.invoice_no', 'invoice_hdr.invoice_date', 'invoice_hdr_detail.order_amount', 'invoicedByTb.name as invoicedBy')
			->groupBy('invoice_hdr_detail.order_id')
			->whereNull('order_master.order_sample_type')
			->whereNotNull('invoice_hdr.invoice_date')
			->where('invoice_hdr_detail.invoice_hdr_status', '1')
			->where('invoice_hdr_detail.order_id', $values->order_id)
			->first();

		if (!empty($invoicingData->invoice_date)) {
			$values->invoice_date   = $isCancelledStatus ? '' : !empty($invoicingData->invoice_date) ? date(DATEFORMATEXCEL, strtotime($invoicingData->invoice_date)) : '';
			$values->invoice_time   = $isCancelledStatus ? '' : !empty($invoicingData->invoice_date) ? date('h:i A', strtotime($invoicingData->invoice_date)) : '';
			$values->invoice_no     = $isCancelledStatus ? '' : !empty($invoicingData->invoice_no) ? $invoicingData->invoice_no : '';
			$values->invoice_by     = $isCancelledStatus ? '' : !empty($invoicingData->invoicedBy) ? $invoicingData->invoicedBy : '';
			$values->sample_amount  = $isCancelledStatus ? '' : !empty($invoicingData->order_amount) ? $invoicingData->order_amount : '';
		} else {
			if ($isCancelledStatus) {
				$values->invoice_date   = '';
				$values->invoice_time   = '';
				$values->invoice_no     = '';
				$values->invoice_by     = '';
				$values->sample_amount  = '';
			} elseif ($isOrderSampleType) {
				$values->invoice_date   = 'I';
				$values->invoice_time   = 'I';
				$values->invoice_no     = 'I';
				$values->invoice_by     = 'I';
				$values->sample_amount  = '';
			} else {
				$values->invoice_date   = $values->testing_status == 'C' && !$isOrderSampleType ? 'P' : '';
				$values->invoice_time   = $values->testing_status == 'C' && !$isOrderSampleType ? 'P' : '';
				$values->invoice_no     = $values->testing_status == 'C' && !$isOrderSampleType ? 'P' : '';
				$values->invoice_by     = $values->testing_status == 'C' && !$isOrderSampleType ? 'P' : '';
				$values->sample_amount  = '';
			}
		}
		//***************/Invoicing Detail**********************************************************************

		//***************Dispatching Detail*********************************************************************
		$dispatchingData = DB::table('order_dispatch_dtl')
			->join('users as dispatchByTb', 'dispatchByTb.id', 'order_dispatch_dtl.dispatch_by')
			->select('order_dispatch_dtl.dispatch_date', 'dispatchByTb.name as dispatchBy')
			->where('order_dispatch_dtl.order_id', $values->order_id)
			->where('order_dispatch_dtl.amend_status', '0')
			->whereNotNull('order_dispatch_dtl.dispatch_date')
			->orderBy('order_dispatch_dtl.dispatch_id', 'DESC')
			->first();

		if (!empty($dispatchingData->dispatch_date) && !empty($values->approved_date) && $models->isValidDate($values->approved_date)) {
			$values->dispatch_date  = $isCancelledStatus ? '' : !empty($dispatchingData->dispatch_date) ? date(DATEFORMATEXCEL, strtotime($dispatchingData->dispatch_date)) : '';
			$values->dispatch_time  = $isCancelledStatus ? '' : !empty($dispatchingData->dispatch_date) ? date('h:i A', strtotime($dispatchingData->dispatch_date)) : '';
			$values->dispatch_by    = $isCancelledStatus ? '' : !empty($dispatchingData->dispatchBy) ? $dispatchingData->dispatchBy : '';
		} else {
			$values->dispatch_date  = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->dispatch_time  = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
			$values->dispatch_by    = $isCancelledStatus ? '' : $values->testing_status == 'C' ? 'P' : '';
		}
		//***************/Dispatching Detail***********************************************************************

		//***************Report Status Detail*********************************************************************
		$reportType   = '';
		$orderDetail  = DB::table('order_master')->where('order_master.order_id', $values->order_id)->first();
		$reportDetail = DB::table('order_report_details')->where('order_report_details.report_id', $values->order_id)->first();
		if (!empty($orderDetail->status) && $orderDetail->status == '10') {
			$reportType = 'Cancelled';
		} elseif (!empty($orderDetail->status) && $orderDetail->status == '12') {
			$reportType = 'Hold';
		} elseif (!empty($reportDetail->report_type) && $reportDetail->report_type == '1') {
			$reportType = 'Final';
		} elseif (!empty($reportDetail->report_type) && $reportDetail->report_type == '2') {
			$reportType = 'Draft';
		}
		$values->report_status = $reportType;
		//***************Report Status Detail*********************************************************************
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getTableSummaryData($submitedData, $type = '1')
	{

		global $order, $models, $misReport;

		$returnData[0]['Summary'] = 'Summary';
		if ($type == '1') {
			$returnData[1]['Total Revenue'] 	   = 'Total Revenue';
			$returnData[1]['Total Revenue Amount'] = !empty($submitedData['revenue_amount']) ? number_format((float) array_sum($submitedData['revenue_amount']), 2, '.', '') : '0.00';
			$returnData[2]['Total Credit']  	   = 'Total Credit';
			$returnData[2]['Total Credit Amount']  = !empty($submitedData['credit_amount']) ? number_format((float) array_sum($submitedData['credit_amount']), 2, '.', '') : '0.00';
			$returnData[3]['Total Amount']  	   = 'Total Amount';
			$returnData[3]['Total Net Amount']     = number_format((float) trim($returnData[1]['Total Revenue Amount'] - $returnData[2]['Total Credit Amount']), 2, '.', '');
			if (!empty($submitedData['credit_amount_summary'])) {
				$returnData[4]['Credit Summary Line Break'] = '';
				$returnData[5]['Credit Summary'] = 'Credit Summary';
				$counter = '6';
				foreach ($submitedData['credit_amount_summary'] as $key => $values) {
					$returnData[$counter]['summary_title'] = $key;
					$returnData[$counter]['summary_amount'] = is_array($values) ? number_format((float) array_sum($values), 2, '.', '') : '0.00';
					$counter++;
				}
			}
		} else if ($type == '2') {
			$returnData[1]['Total Revenue'] 	    = 'Total Revenue';
			$returnData[1]['Total Revenue Amount']  = !empty($submitedData['revenue_amount']) ? number_format((float) array_sum($submitedData['revenue_amount']), 2, '.', '') : '0.00';
			$returnData[2]['Total Credit']  	    = 'Total Credit';
			$returnData[2]['Total Credit Amount']   = !empty($submitedData['credit_amount']) ? number_format((float) array_sum($submitedData['credit_amount']), 2, '.', '') : '0.00';
			if (!empty($submitedData['credit_amount_summary'])) {
				$returnData[3]['Credit Summary Line Break'] = '';
				$returnData[4]['Credit Summary'] = 'Credit Summary';
				$counter = '5';
				foreach ($submitedData['credit_amount_summary'] as $key => $values) {
					$returnData[$counter]['summary_title'] = $key;
					$returnData[$counter]['summary_amount'] = is_array($values) ? number_format((float) array_sum($values), 2, '.', '') : '0.00';
					$counter++;
				}
			}
		}

		return $returnData;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getBranchDepartmentWiseCreditAmount($postedData)
	{

		$returnData = array();

		$creditNotesObj = DB::table('credit_notes')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->whereBetween(DB::raw("DATE(credit_notes.credit_note_date)"), array($postedData['date_from'], $postedData['date_to']));

		if (!empty($postedData['division_id'])) {
			$creditNotesObj->where('credit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$creditNotesObj->where('credit_notes.product_category_id', $postedData['product_category_id']);
		}

		$creditNotesData = $creditNotesObj->select('departments.department_name', 'divisions.division_name', 'credit_notes.*')->get()->toArray();
		if (!empty($creditNotesData)) {
			foreach ($creditNotesData as $key => $values) {
				$returnData[$values->division_name . ' | ' . $values->department_name . ' | Credit Amount'][$values->credit_note_id] = $values->credit_note_amount;
			}
		}

		return $returnData;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getEmployeeBookedSalesTargetAmount($value, $submittedData)
	{
		return DB::table('order_master')
			->where('order_master.sale_executive', $value->ust_user_id)
			->whereBetween(DB::raw("DATE(order_master.booking_date)"), array($submittedData['date_from'], $submittedData['date_to']))
			->sum('order_master.booked_order_amount');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getEmployeeInvoicedSalesTargetAmount($value)
	{
		return DB::table('invoice_hdr_detail')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->where('order_master.sale_executive', $value->ust_user_id)
			->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), array($submittedData['date_from'], $submittedData['date_to']))
			->sum('invoice_hdr_detail.order_amount');
	}

	/**
	 * Generate MIS Report::Sales Report Detail:sales_report_detail_for_sales_invoice
	 * Created On : 22-Jan-2020
	 * Created By : Praveen Singh
	 * Updated On : 07-April-2020
	 * @return array()
	 */
	public function sales_report_detail_for_sales_invoice($postedData, $docType)
	{

		global $order, $models;

		$salesInvoiceObj = DB::table('invoice_hdr_detail')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('divisions', 'divisions.division_id', 'order_master.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'order_master.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_invoicing_id')
			->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
			->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
			->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->join('product_master', 'product_master.product_id', 'order_master.product_id')
			->join('product_categories as subCategory', 'subCategory.p_category_id', 'product_master.p_category_id')
			->join('product_categories as parentCategory', 'parentCategory.p_category_id', 'subCategory.parent_id')
			->leftJoin('users as salesSampler', 'salesSampler.id', 'order_master.sampler_id')
			->leftJoin('test_standard', 'test_standard.test_std_id', 'order_master.defined_test_standard')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->select('divisions.division_name as branch', 'departments.department_name as department', 'divisions.division_name as document_type', 'invoice_hdr.invoice_date as bill_date', 'invoice_hdr.invoice_no', 'invoice_hdr.invoice_date as month', 'invoice_hdr.invoice_date as year', 'customer_master.customer_code as invoicing_customer_code', 'customer_master.customer_name as invoicing_customer_name', 'state_db.state_name as invoicing_state_name', 'city_db.city_name as invoicing_location_name', 'salesExecutive.name as sales_executive_name', 'product_master.product_name', 'order_master.batch_no', 'order_master.order_no as booking_no', 'parentCategory.p_category_name as category_name', 'subCategory.p_category_name as sub_category_name', 'test_standard.test_std_desc as test_std_name', 'order_master.manufactured_by', 'order_master.supplied_by', 'order_master.po_no', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', DB::raw('invoice_hdr_detail.order_amount - invoice_hdr_detail.order_discount as revenue_amount', 'InvoicingToCustomerDB.customer_name as invoiced_customer_name', 'InvoicingToCustomerDB.customer_code as invoiced_customer_code', 'InvoicingToStateDB.state_name as invoiced_state_name', 'InvoicingToCityDB.city_name as invoiced_location_name'),'salesSampler.name as sampler_name')
			->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), array($postedData['date_from'], $postedData['date_to']));

		if (!empty($postedData['division_id'])) {
			$salesInvoiceObj->where('invoice_hdr.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$salesInvoiceObj->where('invoice_hdr.product_category_id', $postedData['product_category_id']);
		}
		if (!empty($postedData['sale_executive_id'])) {
			$salesInvoiceObj->where('customer_master.sale_executive', $postedData['sale_executive_id']);
		}
		if (!empty($postedData['customer_id'])) {
			$salesInvoiceObj->where('invoice_hdr.customer_invoicing_id', $postedData['customer_id']);
		}
		$salesInvoiceObj->orderBy('customer_master.customer_name', 'ASC');
		$salesInvoiceData = $salesInvoiceObj->get()->toArray();

		if (!empty($salesInvoiceData)) {
			foreach ($salesInvoiceData as $key => $value) {
				$value->document_type 		 	 = $docType;
				$value->invoicing_customer_code  = !empty($value->invoiced_customer_code)  ? $value->invoiced_customer_code : $value->invoicing_customer_code;
				$value->invoicing_customer_name  = !empty($value->invoiced_customer_name)  ? $value->invoiced_customer_name : $value->invoicing_customer_name;
				$value->invoicing_state_name     = !empty($value->invoiced_state_name)     ? $value->invoiced_state_name    : $value->invoicing_state_name;
				$value->invoicing_location_name  = !empty($value->invoiced_location_name)  ? $value->invoiced_location_name : $value->invoicing_location_name;
				$value->month	      		 	 = date('F', strtotime($value->month));
				$value->year	      		 	 = date('Y', strtotime($value->year));
				$value->bill_date     		 	 = !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->ref_inv_date  		 	 = !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->ref_inv_no    		 	 = '';
				$value->ref_inv_date  		 	 = '';
			}
		}
		return $models->convertObjectToArray($salesInvoiceData);
	}

	/**
	 * Generate MIS Report::Sales Report Detail:sales_report_detail_for_credit_note_auto_detail
	 * Scope-2 (25-July-2018),Scope-3 (12-Dec-2018)
	 * Updated By : Ruby
	 * Updated On : 07-April-2020
	 * @return \Illuminate\Http\Response
	 */
	public function sales_report_detail_for_credit_note_auto_detail($postedData, $docType)
	{

		global $order, $models;

		$creditNoteAutoObj = DB::table('credit_notes')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', 'credit_notes.invoice_id')
			->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
			->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
			->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
			->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->join('product_master', 'product_master.product_id', 'order_master.product_id')
			->join('product_categories as subCategory', 'subCategory.p_category_id', 'product_master.p_category_id')
			->join('product_categories as parentCategory', 'parentCategory.p_category_id', 'subCategory.parent_id')
			->leftJoin('users as salesSampler', 'salesSampler.id', 'order_master.sampler_id')
			->leftJoin('test_standard', 'test_standard.test_std_id', 'order_master.defined_test_standard')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->select('divisions.division_name as branch', 'departments.department_name as department', 'divisions.division_name as document_type', 'credit_notes.credit_note_date as bill_date', 'credit_notes.credit_note_no as invoice_no', 'credit_notes.credit_note_date as month', 'credit_notes.credit_note_date as year', 'customer_master.customer_code as invoicing_customer_code', 'customer_master.customer_name as invoicing_customer_name', 'state_db.state_name as invoicing_state_name', 'city_db.city_name as invoicing_location_name', 'salesExecutive.name as sales_executive_name', 'product_master.product_name', 'order_master.batch_no', 'order_master.order_no as booking_no', 'parentCategory.p_category_name as category_name', 'subCategory.p_category_name as sub_category_name', 'test_standard.test_std_desc as test_std_name', 'order_master.manufactured_by', 'order_master.supplied_by', 'order_master.po_no', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', 'credit_notes.credit_note_amount as revenue_amount', 'InvoicingToCustomerDB.customer_name as invoiced_customer_name', 'InvoicingToCustomerDB.customer_code as invoiced_customer_code', 'InvoicingToStateDB.state_name as invoiced_state_name', 'InvoicingToCityDB.city_name as invoiced_location_name','salesSampler.name as sampler_name')
			->whereBetween(DB::raw("DATE(credit_notes.credit_note_date)"), array($postedData['date_from'], $postedData['date_to']))
			->where('credit_notes.credit_note_type_id', '1')
			->whereNull('credit_notes.credit_reference_no')
			->whereNotNull('credit_notes.invoice_id');
		if (!empty($postedData['division_id'])) {
			$creditNoteAutoObj->where('credit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$creditNoteAutoObj->where('credit_notes.product_category_id', $postedData['product_category_id']);
		}
		if (!empty($postedData['sale_executive_id'])) {
			$creditNoteAutoObj->where('customer_master.sale_executive', $postedData['sale_executive_id']);
		}
		if (!empty($postedData['customer_id'])) {
			$creditNoteAutoObj->where('credit_notes.customer_id', $postedData['customer_id']);
		}
		$creditNoteAutoObj->groupBy('credit_notes.credit_note_no');
		$creditNoteAutoObj->orderBy('customer_master.customer_name', 'ASC');
		$creditNoteAutoData = $creditNoteAutoObj->get()->toArray();

		if (!empty($creditNoteAutoData)) {
			foreach ($creditNoteAutoData as $value) {
				$value->document_type  		 	= $docType;
				$value->invoicing_customer_code = !empty($value->invoiced_customer_code)  ? $value->invoiced_customer_code : $value->invoicing_customer_code;
				$value->invoicing_customer_name = !empty($value->invoiced_customer_name)  ? $value->invoiced_customer_name : $value->invoicing_customer_name;
				$value->invoicing_state_name    = !empty($value->invoiced_state_name)     ? $value->invoiced_state_name    : $value->invoicing_state_name;
				$value->invoicing_location_name = !empty($value->invoiced_location_name)  ? $value->invoiced_location_name : $value->invoicing_location_name;
				$value->month	       		 	= date('F', strtotime($value->month));
				$value->year	       		 	= date('Y', strtotime($value->year));
				$value->bill_date      		 	= !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->ref_inv_date   		 	= !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->revenue_amount 		 	= !empty($value->revenue_amount) ? '-' . $models->roundValue($value->revenue_amount) : '0.00';
			}
		}
		return $models->convertObjectToArray($creditNoteAutoData);
	}

	/**************************
	 * Generate MIS Report::Sales Report Detail:sales_report_detail_for_credit_note_manual_detail
	 * Scope-2 (25-July-2018),Scope-3 (12-Dec-2018)
	 * @return \Illuminate\Http\Response
	 *************************************/
	public function sales_report_detail_for_credit_note_manual_detail($postedData, $docType)
	{

		global $order, $models;

		//*************Credit Note Manual With Invoice***********************************************************	
		$creditNoteManualWithInvoiceObj = DB::table('credit_notes')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', 'credit_notes.invoice_id')
			->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
			->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
			->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
			->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->join('product_master', 'product_master.product_id', 'order_master.product_id')
			->join('product_categories as subCategory', 'subCategory.p_category_id', 'product_master.p_category_id')
			->join('product_categories as parentCategory', 'parentCategory.p_category_id', 'subCategory.parent_id')
			->leftJoin('users as salesSampler', 'salesSampler.id', 'order_master.sampler_id')
			->leftJoin('test_standard', 'test_standard.test_std_id', 'order_master.defined_test_standard')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->select('divisions.division_name as branch', 'departments.department_name as department', 'divisions.division_name as document_type', 'credit_notes.credit_note_date as bill_date', 'credit_notes.credit_note_no as invoice_no', 'credit_notes.credit_note_date as month', 'credit_notes.credit_note_date as year', 'customer_master.customer_code as invoicing_customer_code', 'customer_master.customer_name as invoicing_customer_name', 'state_db.state_name as invoicing_state_name', 'city_db.city_name as invoicing_location_name', 'salesExecutive.name as sales_executive_name', 'product_master.product_name', 'order_master.batch_no', 'order_master.order_no as booking_no', 'parentCategory.p_category_name as category_name', 'subCategory.p_category_name as sub_category_name', 'test_standard.test_std_desc as test_std_name', 'order_master.manufactured_by', 'order_master.supplied_by', 'order_master.po_no', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', 'credit_notes.credit_note_amount as revenue_amount', 'InvoicingToCustomerDB.customer_name as invoiced_customer_name', 'InvoicingToCustomerDB.customer_code as invoiced_customer_code', 'InvoicingToStateDB.state_name as invoiced_state_name', 'InvoicingToCityDB.city_name as invoiced_location_name','salesSampler.name as sampler_name')
			->whereBetween(DB::raw("DATE(credit_notes.credit_note_date)"), array($postedData['date_from'], $postedData['date_to']))
			->where('credit_notes.credit_note_type_id', '2')
			->whereNull('credit_notes.credit_reference_no')
			->whereNotNull('credit_notes.invoice_id');

		if (!empty($postedData['division_id'])) {
			$creditNoteManualWithInvoiceObj->where('credit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$creditNoteManualWithInvoiceObj->where('credit_notes.product_category_id', $postedData['product_category_id']);
		}
		if (!empty($postedData['sale_executive_id'])) {
			$creditNoteManualWithInvoiceObj->where('customer_master.sale_executive', $postedData['sale_executive_id']);
		}
		if (!empty($postedData['customer_id'])) {
			$creditNoteManualWithInvoiceObj->where('credit_notes.customer_id', $postedData['customer_id']);
		}
		$creditNoteManualWithInvoiceObj->groupBy('credit_notes.credit_note_no');
		$creditNoteManualWithInvoiceObj->orderBy('customer_master.customer_name', 'ASC');
		$creditNoteManualWithInvoiceData = $creditNoteManualWithInvoiceObj->get()->toArray();

		if (!empty($creditNoteManualWithInvoiceData)) {
			foreach ($creditNoteManualWithInvoiceData as $value) {
				$value->document_type  		 	= $docType;
				$value->invoicing_customer_code = !empty($value->invoiced_customer_code)  ? $value->invoiced_customer_code : $value->invoicing_customer_code;
				$value->invoicing_customer_name = !empty($value->invoiced_customer_name)  ? $value->invoiced_customer_name : $value->invoicing_customer_name;
				$value->invoicing_state_name    = !empty($value->invoiced_state_name)     ? $value->invoiced_state_name    : $value->invoicing_state_name;
				$value->invoicing_location_name = !empty($value->invoiced_location_name)  ? $value->invoiced_location_name : $value->invoicing_location_name;
				$value->month	       		 	= date('F', strtotime($value->month));
				$value->year	       		 	= date('Y', strtotime($value->year));
				$value->bill_date      		 	= !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->ref_inv_date   		 	= !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->revenue_amount 		 	= !empty($value->revenue_amount) ? '-' . $models->roundValue($value->revenue_amount) : '0.00';
			}
		}
		$creditNoteManualWithInvoiceData = $models->convertObjectToArray($creditNoteManualWithInvoiceData);
		//*************/Credit Note Manual With Invoice************************************************************************

		//*************Credit Note Manual Without Invoice*********************************************************************
		$creditNoteManualWithoutInvoiceObj = DB::table('credit_notes')
			->leftJoin('invoice_hdr', 'invoice_hdr.invoice_id', 'credit_notes.invoice_id')
			->leftJoin('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
			->leftJoin('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->leftJoin('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->leftJoin('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
			->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
			->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
			->join('users as salesExecutive', 'salesExecutive.id', 'customer_master.sale_executive')
			->leftJoin('product_master', 'product_master.product_id', 'order_master.product_id')
			->leftJoin('product_categories as subCategory', 'subCategory.p_category_id', 'product_master.p_category_id')
			->leftJoin('product_categories as parentCategory', 'parentCategory.p_category_id', 'subCategory.parent_id')
			->leftJoin('test_standard', 'test_standard.test_std_id', 'order_master.defined_test_standard')
			->leftJoin('customer_master as InvoicingToCustomerDB', 'InvoicingToCustomerDB.customer_id', 'invoice_hdr_detail.order_invoicing_to')
			->leftJoin('state_db as InvoicingToStateDB', 'InvoicingToStateDB.state_id', 'InvoicingToCustomerDB.customer_state')
			->leftJoin('city_db as InvoicingToCityDB', 'InvoicingToCityDB.city_id', 'InvoicingToCustomerDB.customer_city')
			->select('divisions.division_name as branch', 'departments.department_name as department', 'divisions.division_name as document_type', 'credit_notes.credit_note_date as bill_date', 'credit_notes.credit_note_no as invoice_no', 'credit_notes.credit_note_date as month', 'credit_notes.credit_note_date as year', 'customer_master.customer_code as invoicing_customer_code', 'customer_master.customer_name as invoicing_customer_name', 'state_db.state_name as invoicing_state_name', 'city_db.city_name as invoicing_location_name', 'salesExecutive.name as sales_executive_name', 'product_master.product_name', 'order_master.batch_no', 'order_master.order_no as booking_no', 'parentCategory.p_category_name as category_name', 'subCategory.p_category_name as sub_category_name', 'test_standard.test_std_desc as test_std_name', 'order_master.manufactured_by', 'order_master.supplied_by', 'order_master.po_no', 'invoice_hdr.invoice_no as ref_inv_no', 'invoice_hdr.invoice_date as ref_inv_date', 'credit_notes.credit_note_amount as revenue_amount', 'InvoicingToCustomerDB.customer_name as invoiced_customer_name', 'InvoicingToCustomerDB.customer_code as invoiced_customer_code', 'InvoicingToStateDB.state_name as invoiced_state_name', 'InvoicingToCityDB.city_name as invoiced_location_name')
			->whereBetween(DB::raw("DATE(credit_notes.credit_note_date)"), array($postedData['date_from'], $postedData['date_to']))
			->where('credit_notes.credit_note_type_id', '2')
			->whereNotNull('credit_notes.credit_reference_no')
			->whereNull('credit_notes.invoice_id');

		if (!empty($postedData['division_id'])) {
			$creditNoteManualWithoutInvoiceObj->where('credit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$creditNoteManualWithoutInvoiceObj->where('credit_notes.product_category_id', $postedData['product_category_id']);
		}
		if (!empty($postedData['sale_executive_id'])) {
			$creditNoteManualWithoutInvoiceObj->where('customer_master.sale_executive', $postedData['sale_executive_id']);
		}
		if (!empty($postedData['customer_id'])) {
			$creditNoteManualWithoutInvoiceObj->where('credit_notes.customer_id', $postedData['customer_id']);
		}
		$creditNoteManualWithoutInvoiceObj->groupBy('credit_notes.credit_note_no');
		$creditNoteManualWithoutInvoiceObj->orderBy('customer_master.customer_name', 'ASC');
		$creditNoteManualWithoutInvoiceData = $creditNoteManualWithoutInvoiceObj->get()->toArray();

		if (!empty($creditNoteManualWithoutInvoiceData)) {
			foreach ($creditNoteManualWithoutInvoiceData as $value) {
				$value->document_type  		 	= $docType;
				$value->invoicing_customer_code = !empty($value->invoiced_customer_code)  ? $value->invoiced_customer_code : $value->invoicing_customer_code;
				$value->invoicing_customer_name = !empty($value->invoiced_customer_name)  ? $value->invoiced_customer_name : $value->invoicing_customer_name;
				$value->invoicing_state_name    = !empty($value->invoiced_state_name)     ? $value->invoiced_state_name    : $value->invoicing_state_name;
				$value->invoicing_location_name = !empty($value->invoiced_location_name)  ? $value->invoiced_location_name : $value->invoicing_location_name;
				$value->month	       		 	= date('F', strtotime($value->month));
				$value->year	       		 	= date('Y', strtotime($value->year));
				$value->bill_date      		 	= !empty($value->bill_date) ? date(DATEFORMATEXCEL, strtotime($value->bill_date)) : '';
				$value->ref_inv_date   		 	= !empty($value->ref_inv_date) ? date(DATEFORMATEXCEL, strtotime($value->ref_inv_date)) : '';
				$value->revenue_amount 		 	= !empty($value->revenue_amount) ? '-' . $models->roundValue($value->revenue_amount) : '0.00';
			}
		}
		$creditNoteManualWithoutInvoiceData = $models->convertObjectToArray($creditNoteManualWithoutInvoiceData);
		//*************/Credit Note Manual Without Invoice*********************************************************************

		return array_merge($creditNoteManualWithInvoiceData, $creditNoteManualWithoutInvoiceData);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getBranchDepartmentWiseSalesInvoiceCreditAmount($postedData)
	{

		$returnData = array();

		$creditNotesObj = DB::table('credit_notes')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
			->whereBetween(DB::raw("DATE(credit_notes.credit_note_date)"), array($postedData['date_from'], $postedData['date_to']));

		if (!empty($postedData['division_id'])) {
			$creditNotesObj->where('credit_notes.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['product_category_id'])) {
			$creditNotesObj->where('credit_notes.product_category_id', $postedData['product_category_id']);
		}
		if (!empty($postedData['sale_executive_id'])) {
			$creditNotesObj->where('customer_master.sale_executive', $postedData['sale_executive_id']);
		}
		if (!empty($postedData['customer_id'])) {
			$creditNotesObj->where('credit_notes.customer_id', $postedData['customer_id']);
		}
		$creditNotesData = $creditNotesObj->select('departments.department_name', 'divisions.division_name', 'credit_notes.*')->get()->toArray();
		if (!empty($creditNotesData)) {
			foreach ($creditNotesData as $key => $values) {
				$returnData[$values->division_name . ' | ' . $values->department_name . ' | Credit Amount'][$values->credit_note_id] = $values->credit_note_amount;
			}
		}
		return $returnData;
	}

	/**
	 * Description : Employee Sales Target Detail Budget and Forcast
	 * Created By : Praveen Singh
	 * Created On : 23,26-Nov-2020
	 */
	function employee_sales_target_detail_budget_forcast_actual_prevactual($postedData)
	{

		global $models;

		$returnArray 	      = array();
		$customerTypeArray    = ['New', 'Current Month', 'Existing'];
		$userSalesTargetTypes = ['1', '2', '3', '4'];
		$monthRangeData       = $models->month_range($postedData['date_from'], $postedData['date_to'], $format = 'Y-m-d');

		$responseDataObj = DB::table('customer_master')
			->join('users', 'users.id', 'customer_master.sale_executive')
			->join('divisions', 'divisions.division_id', 'users.division_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->select('users.id as ust_user_id', 'divisions.division_id as ust_division_id', 'customer_master.customer_id as ust_customer_id', 'customer_master.customer_id as ust_month', 'customer_master.customer_id as ust_year', 'customer_master.customer_id as financial_year', 'divisions.division_name as branch', 'customer_master.customer_id as department', 'customer_master.customer_code', 'customer_master.customer_name', 'state_db.state_name as state', 'city_db.city_name as place', 'customer_master.customer_type as customer_type', 'customer_master.customer_id as type', 'customer_master.customer_id as month', 'users.name as sales_executive', 'customer_master.customer_id as amount', 'customer_master.customer_id as amount_in_lakh');

		if (!empty($postedData['division_id'])) {
			$responseDataObj->where('users.division_id', $postedData['division_id']);
		}
		if (!empty($postedData['sale_executive_id'])) {
			$responseDataObj->where('customer_master.sale_executive', $postedData['sale_executive_id']);
		}
		if (!empty($postedData['customer_id'])) {
			$responseDataObj->where('customer_master.customer_id', $postedData['customer_id']);
		}
		$responseData = $responseDataObj->orderBy('users.name', 'ASC')->get()->toArray();
		if (!empty($responseData) && !empty($monthRangeData)) {
			foreach ($responseData as $key => $values) {
				foreach ($monthRangeData as $monthKey => $monthValues) {
					$postedData['ust_month']	 = date('m', strtotime($monthValues));
					$postedData['ust_year']	 = date('Y', strtotime($monthValues));
					foreach ($userSalesTargetTypes as $userSalesTargetTypeKey => $userSalesTargetTypeValue) {
						$postedData['ust_type_id'] = $userSalesTargetTypeValue;
						if ($userSalesTargetTypeValue == '1') {		//Getting Budget Data
							$returnArray[$key . '-' . $userSalesTargetTypeValue . '-' . date('MY', strtotime($monthValues)) . '-' . $userSalesTargetTypeValue] = $this->employee_sales_target_detail_budget_forcast($values, $postedData);
						} else if ($userSalesTargetTypeValue == '2') {	//Getting Forcast Data
							$returnArray[$key . '-' . $userSalesTargetTypeValue . '-' . date('MY', strtotime($monthValues)) . '-' . $userSalesTargetTypeValue] = $this->employee_sales_target_detail_budget_forcast($values, $postedData);
						} else if ($userSalesTargetTypeValue == '3') {	//Getting Actual Data
							$returnArray[$key . '-' . $userSalesTargetTypeValue . '-' . date('MY', strtotime($monthValues)) . '-' . $userSalesTargetTypeValue] = $this->employee_sales_target_detail_actual($values, $postedData);
						} else if ($userSalesTargetTypeValue == '4' && $this->getCustomerTypeDetail($values->ust_customer_id) == 'Existing') {	//Getting Actual Previous Data
							$postedData['ust_year'] = date('Y', strtotime($monthValues)) - '1';
							$returnArray[$key . '-' . $userSalesTargetTypeValue . '-' . date('MY', strtotime($monthValues)) . '-' . $userSalesTargetTypeValue] = $this->employee_sales_target_detail_actual($values, $postedData);
						}
					}
				}
			}
		}

		return $returnArray;
	}

	/**
	 * Description : Employee Sales Target Detail Budget and Forcast
	 * Created By : Praveen Singh
	 * Created On : 23,26-Nov-2020
	 */
	function employee_sales_target_detail_budget_forcast($values, $postedData)
	{

		global $models;

		$returnData = DB::table('user_sales_target_details')
			->join('users', 'users.id', 'user_sales_target_details.ust_user_id')
			->join('divisions', 'divisions.division_id', 'users.division_id')
			->join('customer_master', 'customer_master.customer_id', 'user_sales_target_details.ust_customer_id')
			->join('product_categories', 'product_categories.p_category_id', 'user_sales_target_details.ust_product_category_id')
			->join('user_sales_target_types', 'user_sales_target_types.usty_id', 'user_sales_target_details.ust_type_id')
			->join('invoice_financial_years', 'invoice_financial_years.ify_id', 'user_sales_target_details.ust_fin_year_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->select('user_sales_target_details.ust_month', 'user_sales_target_details.ust_year', 'invoice_financial_years.ify_name as financial_year', 'divisions.division_name as branch', 'product_categories.p_category_name as department', 'customer_master.customer_code', 'customer_master.customer_name', 'state_db.state_name as state', 'city_db.city_name as place', 'customer_master.customer_type as customer_type', 'user_sales_target_types.usty_name as type', 'user_sales_target_details.ust_date as month', 'users.name as sales_executive', 'user_sales_target_details.ust_user_id', 'user_sales_target_details.ust_amount as amount', 'user_sales_target_details.ust_amount as amount_in_lakh')
			->where('user_sales_target_details.ust_user_id', $values->ust_user_id)
			->where('user_sales_target_details.ust_division_id', $values->ust_division_id)
			->when(!empty($postedData['product_category_id']), function ($query) use ($postedData) {
				return $query->where('user_sales_target_details.ust_product_category_id', $postedData['product_category_id']);
			})
			->where('user_sales_target_details.ust_customer_id', $values->ust_customer_id)
			->where('user_sales_target_details.ust_type_id', $postedData['ust_type_id'])
			->whereMonth('user_sales_target_details.ust_month', $postedData['ust_month'])
			->whereYear('user_sales_target_details.ust_year', $postedData['ust_year'])
			->first();

		$valuesobj 			= new \stdClass();
		$valuesobj->ust_user_id 	= $values->ust_user_id;
		$valuesobj->ust_division_id 	= $values->ust_division_id;
		$valuesobj->ust_customer_id 	= $values->ust_customer_id;
		$valuesobj->financial_year 	= !empty($returnData->financial_year) ? $returnData->financial_year : '';
		$valuesobj->branch		= !empty($returnData->branch) ? $returnData->branch : $values->branch;
		$valuesobj->department		= !empty($returnData->department) ? $returnData->department : '';
		$valuesobj->customer_code	= !empty($returnData->customer_code) ? $returnData->customer_code : $values->customer_code;
		$valuesobj->customer_name	= !empty($returnData->customer_name) ? $returnData->customer_name : $values->customer_name;
		$valuesobj->state		= !empty($returnData->state) ? $returnData->state : $values->state;
		$valuesobj->place		= !empty($returnData->place) ? $returnData->place : $values->place;
		$valuesobj->customer_type	= !empty($returnData->customer_type) ? $returnData->customer_type : '';
		$valuesobj->type		= !empty($returnData->type) ? $returnData->type : (!empty($postedData['ust_type_id']) && $postedData['ust_type_id'] == '1' ? 'Budget' : 'Forcast');
		$valuesobj->month		= !empty($returnData->month) ? $returnData->month : $postedData['ust_month'];
		$valuesobj->sales_executive	= !empty($returnData->sales_executive) ? $returnData->sales_executive : $values->sales_executive;
		$valuesobj->amount		= !empty($returnData->amount) ? $returnData->amount : '';
		$valuesobj->amount_in_lakh	= !empty($returnData->amount_in_lakh) ? $returnData->amount_in_lakh : '';

		return $valuesobj;
	}

	/**
	 * Description : Employee Sales Target Detail Actual
	 * Created By : Praveen Singh
	 * Created On : 23,26-Nov-2020
	 */
	function employee_sales_target_detail_actual($values, $postedData)
	{

		global $models;

		$returnData = DB::table('invoice_hdr')
			->join('divisions', 'divisions.division_id', 'invoice_hdr.division_id')
			->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_id')
			->join('product_categories', 'product_categories.p_category_id', 'invoice_hdr.product_category_id')
			->join('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
			->join('state_db', 'customer_master.customer_state', 'state_db.state_id')
			->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
			->select('invoice_financial_years.ify_name as financial_year', 'divisions.division_name as branch', 'product_categories.p_category_name as department', 'customer_master.customer_code', 'customer_master.customer_name', 'state_db.state_name as state', 'city_db.city_name as place', 'customer_master.customer_type as customer_type', 'customer_master.customer_type as type', 'invoice_hdr.invoice_date as month', 'invoice_hdr.total_amount as amount', 'invoice_hdr.total_amount as amount_in_lakh')
			->where('invoice_hdr.division_id', $values->ust_division_id)
			->when(!empty($postedData['product_category_id']), function ($query) use ($postedData) {
				return $query->where('invoice_hdr.product_category_id', $postedData['product_category_id']);
			})
			->where('invoice_hdr.customer_invoicing_id', $values->ust_customer_id)
			->whereMonth('invoice_hdr.invoice_date', $postedData['ust_month'])
			->whereYear('invoice_hdr.invoice_date', $postedData['ust_year'])
			->where('invoice_hdr.invoice_status', '1')
			->first();

		$valuesobj 			= new \stdClass();
		$valuesobj->ust_user_id 	= $values->ust_user_id;
		$valuesobj->ust_division_id 	= $values->ust_division_id;
		$valuesobj->ust_customer_id 	= $values->ust_customer_id;
		$valuesobj->financial_year 	= !empty($returnData->financial_year) ? $returnData->financial_year : '';
		$valuesobj->branch		= !empty($returnData->branch) ? $returnData->branch : $values->branch;
		$valuesobj->department		= !empty($returnData->department) ? $returnData->department : '';
		$valuesobj->customer_code	= !empty($returnData->customer_code) ? $returnData->customer_code : $values->customer_code;
		$valuesobj->customer_name	= !empty($returnData->customer_name) ? $returnData->customer_name : $values->customer_name;
		$valuesobj->state		= !empty($returnData->state) ? $returnData->state : $values->state;
		$valuesobj->place		= !empty($returnData->place) ? $returnData->place : $values->place;
		$valuesobj->customer_type	= !empty($returnData->customer_type) ? $returnData->customer_type : '';
		$valuesobj->type		= !empty($postedData['ust_type_id']) && $postedData['ust_type_id'] == '3' ? 'Actual' : 'Prev Actual';
		$valuesobj->month		= !empty($returnData->month) ? date('m', strtotime($returnData->month)) : $postedData['ust_month'];
		$valuesobj->sales_executive	= $values->sales_executive;
		$valuesobj->amount		= !empty($returnData->amount) ? $returnData->amount : '';
		$valuesobj->amount_in_lakh	= !empty($returnData->amount_in_lakh) ? $returnData->amount_in_lakh : '';

		return $valuesobj;
	}

	/**
	 * Description : Getting Customer Type Status -New,Current Month,Existing
	 * Created By : Praveen Singh
	 * Created On : 23,26-Nov-2020
	 */
	function getCustomerTypeDetail($customer_id)
	{

		$currentYear  	   = date('Y');
		$previousYear 	   = $currentYear - 1;
		$currentMonth	   = date('m');
		$customerTypeArray = ['New', 'Current Month', 'Existing'];

		$existingCustomer = DB::table('invoice_hdr')->where('invoice_hdr.customer_invoicing_id', $customer_id)->whereYear('invoice_hdr.invoice_date', $previousYear)->count();
		$currentCustomer  = DB::table('invoice_hdr')->where('invoice_hdr.customer_invoicing_id', $customer_id)->whereYear('invoice_hdr.invoice_date', $currentYear)->count();
		if ($existingCustomer && $currentCustomer) {
			$createdThisMonth = DB::table('customer_master')->where('customer_master.customer_id', $customer_id)->whereMonth('customer_master.created_at', $currentMonth)->whereYear('customer_master.created_at', $currentYear)->count();
			if ($createdThisMonth) {
				return $customerTypeArray[1];
			} else {
				return $customerTypeArray[2];
			}
		} else {
			return $customerTypeArray[0];
		}
	}
}
