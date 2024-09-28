<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class InvoiceHdr extends Model
{
	protected $table = 'invoice_hdr';
	protected $primaryKey = 'invoice_id';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $fillable = [];

	/**
	 * Get Invoice Detail orders
	 * Date : 26-July-2018
	 * Author : Praveen Singh
	 */
	public function getInvoiceHdr($invoiceId)
	{
		return DB::table('invoice_hdr')->where('invoice_hdr.invoice_id', $invoiceId)->first();
	}

	/********************************************
	 * get report details rom order_report_details table
	 * Date :
	 * Author : Praveen SIngh
	 ********************************************/
	public function getOrderInvoiceDetails($invoice_id)
	{
		return DB::table('invoice_hdr')->where('invoice_id', '=', $invoice_id)->first();
	}

	/**
	 * Get Invoice Detail orders
	 * Date : 26-July-2018
	 * Author : Praveen Singh
	 */
	public function getInvoiceHdrDetail($invoiceId)
	{
		return DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_id', $invoiceId)->get();
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : Praveen Singh
	 */
	public function getFirstAndLastDayOfWeek($date, $format = 'Y-m-d')
	{
		$first_day_of_week = date($format, strtotime('Last Monday', strtotime($date)));
		$last_day_of_week  = date($format, strtotime('Next Sunday', strtotime($date)));
		return array($first_day_of_week, $last_day_of_week);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : Praveen Singh
	 */
	public function getFirstAndLastDayOfMonth($date, $format = 'Y-m-d')
	{
		$first_day_of_month = date($format, strtotime('first day of this month', strtotime($date)));
		$last_day_of_month  = date($format, strtotime('last day of this month', strtotime($date)));
		return array($first_day_of_month, $last_day_of_month);
	}

	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : Praveen Singh
	 */
	public function getOrderCostPrice($order_id, $discount_type, $discount_value)
	{
		$returnData    = array();
		$costPriceAmt  = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->sum('selling_price');
		if ($discount_type == '1') {  // if amount
			$returnData = array($costPriceAmt, $discount_value);
		} else if ($discount_type == '2') { //if percentage
			$discountAmt = ($costPriceAmt * $discount_value) / 100;
			$returnData  = array($costPriceAmt, $discountAmt);
		} else if ($discount_type == '3') { // if nodiscount
			$discountAmt = '0';
			$returnData  = array($costPriceAmt, $discountAmt);
		}
		return $returnData;
	}

	/****************************************************
	 *Generating Invoice Number
	 *Format : Prefix-DepartmentName-YYMMDDSERIALNo
	 * Date : 01-03-17
	 * Author : Praveen Singh
	 ****************************************************/
	function generateInvoiceNumber($currentDate, $productCategoryId, $divisionId)
	{

		if (empty($currentDate) || empty($productCategoryId) || empty($divisionId)) return;

		if (defined('GENERATE_INVOICE_WITHOUT_DIVISION') && GENERATE_INVOICE_WITHOUT_DIVISION) {
			return $this->generateInvoiceNumber_v1($currentDate, $productCategoryId, $divisionId);
		} else if (defined('GENERATE_INVOICE_WITH_DIVISION') && GENERATE_INVOICE_WITH_DIVISION) {
			return $this->generateInvoiceNumber_v2($currentDate, $productCategoryId, $divisionId);
		}
	}

	/****************************************************
	 *Generating Invoice Number
	 *Format : Prefix-DepartmentName-YYMMDDSERIALNo
	 ****************************************************/
	function generateInvoiceNumber_v1($currentDate, $productCategoryId, $divisionId)
	{

		if (empty($currentDate) || empty($productCategoryId) || empty($divisionId)) return;

		$invoiceDay   	      = date('d', strtotime($currentDate));
		$invoiceMonth 	      = date('m', strtotime($currentDate));
		$invoiceYear  	      = date('y', strtotime($currentDate));
		$invoiceYearCondition = date('n', strtotime($currentDate)) <= '3' ? date('Y', strtotime($currentDate)) - 1 : date('Y', strtotime($currentDate));
		$invoiceYearCondition = date('Y', strtotime($currentDate));

		//Getting Section Name
		$productCategoryData = DB::table('product_categories')->where('product_categories.p_category_id', $productCategoryId)->first();
		$sectionName         = !empty($productCategoryData->p_category_name) ? substr($productCategoryData->p_category_name, 0, 1) : 'F';

		//In case of all Deparment,Invoice number will be generated according to department wise and current Year
		$maxInvoiceData = DB::table('invoice_hdr')->select('invoice_hdr.invoice_id', 'invoice_hdr.invoice_no')->where('invoice_hdr.product_category_id', $productCategoryId)->whereYear('invoice_hdr.invoice_date', $invoiceYearCondition)->where('invoice_hdr.division_id', $divisionId)->orderBy('invoice_hdr.invoice_id', 'DESC')->limit(1)->first();

		//getting Max Serial Number
		$maxSerialNo = !empty($maxInvoiceData->invoice_no) ? substr($maxInvoiceData->invoice_no, 8) + 1 : '0001';
		$maxSerialNo = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';

		//Combing all to get unique order number
		$invoiceNumber = $sectionName . '-' . $invoiceYear . $invoiceMonth . $invoiceDay . $maxSerialNo;

		return $invoiceNumber;
	}

	/****************************************************
	 *Generating Invoice Number
	 *Format : Prefix-DepartmentName-YYMMDDSERIALNo
	 *Created By : Praveen Singh
	 *Created On : 25-Jan-2019
	 ****************************************************/
	function generateInvoiceNumber_v2($currentDate, $productCategoryId, $divisionId)
	{

		if (empty($currentDate) || empty($productCategoryId) || empty($divisionId)) return;

		$invoiceDay   	  = date('d', strtotime($currentDate));
		$invoiceMonth 	  = date('m', strtotime($currentDate));
		$invoiceYear  	  = date('y', strtotime($currentDate));

		//GETTING INVOICE SERIL RESET STATUS
		$isNewInvoiceSession = $this->__getInvoiceNewSessionDtl($currentDate, $divisionId, $productCategoryId);

		//Getting Division Name
		$divisionData = DB::table('divisions')->where('divisions.division_id', $divisionId)->first();
		$divisionCode = !empty($divisionData->division_code) ? trim($divisionData->division_code) : '00';

		//Getting Section Name
		$productCategoryData = DB::table('product_categories')->where('product_categories.p_category_id', $productCategoryId)->first();
		$sectionName         = !empty($productCategoryData->p_category_name) ? substr($productCategoryData->p_category_name, 0, 1) : 'F';

		//In case of all Deparment,Invoice number will be generated according to department wise and current Year
		$maxInvoiceData = DB::table('invoice_hdr')->select('invoice_hdr.invoice_id', 'invoice_hdr.invoice_no')->where('invoice_hdr.product_category_id', $productCategoryId)->where('invoice_hdr.division_id', $divisionId)->orderBy('invoice_hdr.invoice_id', 'DESC')->limit(1)->first();

		//getting Max Serial Number
		$delimiter   = !empty($maxInvoiceData->invoice_no) && strlen($maxInvoiceData->invoice_no) == '12' ? 8 : 10;
		$maxSerialNo = !empty($maxInvoiceData->invoice_no) ? substr($maxInvoiceData->invoice_no, $delimiter) + 1 : '0001';
		$maxSerialNo = $isNewInvoiceSession ? '0001' : $maxSerialNo;
		$maxSerialNo = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';

		//Combing all to get unique order number
		$invoiceNumber = $sectionName . $divisionCode . '-' . $invoiceYear . $invoiceMonth . $invoiceDay . $maxSerialNo;

		//Returning Invoicing Number
		return $invoiceNumber;
	}

	/****************************************************
	 *Getting Invoice Session Detail
	 *Created By : Praveen Singh
	 *Created On : 28-Jan-2019
	 ****************************************************/
	function __getInvoiceNewSessionDtl($currentDate, $divisionId, $productCategoryId)
	{

		//IF INVOICE NEW SESSION IS STATUS IN THE MONTH OF APRIL EVERY YEAR
		$invoiceSessionData = DB::table('invoice_session_dtl')->where('invoice_session_dtl.product_category_id', $productCategoryId)->where('invoice_session_dtl.division_id', $divisionId)->orderBy('invoice_session_dtl.invoice_session_id', 'DESC')->limit(1)->first();

		//GETTING RESET STATUS OF INVOICE
		if (!empty($invoiceSessionData->invoice_session_status) && date('n', strtotime($currentDate)) == '4') {

			//ASSIGNING STATUS 1
			$resetStatus = '1';
		} else {
			//ASSIGNING STATUS 0
			$resetStatus = '0';
		}

		//RESETTING THE NEW INVOICE SESSION TO 1 FOR EACH DEPARTMENT AND BRANCH IN THE MONTH OF JAN & FEB EVERY YEAR.
		if (in_array(date('n', strtotime($currentDate)), array('1', '2'))) {
			$invoiceSessionAllData = DB::table('invoice_session_dtl')->orderBy('invoice_session_dtl.invoice_session_id', 'DESC')->get()->toArray();
			foreach ($invoiceSessionAllData as $key => $values) {
				if (!empty($values->invoice_session_id) && empty($values->invoice_session_status)) {
					DB::table('invoice_session_dtl')->where('invoice_session_dtl.invoice_session_id', $values->invoice_session_id)->update(['invoice_session_dtl.invoice_session_status' => '1']);
				}
			}
		}

		return $resetStatus;
	}

	/****************************************************
	 *Updating Invoice Session Detail
	 *Created By : Praveen Singh
	 *Created On : 28-Jan-2019
	 ****************************************************/
	function __updateInvoiceNewSessionDtl($invoceSessionStatusArr)
	{

		global $models;

		if (!empty($invoceSessionStatusArr)) {
			foreach ($invoceSessionStatusArr as $divProKey => $valueStatus) {
				if (!empty($valueStatus) && $valueStatus == '1') {
					$divProKeyArr = $models->getExplodedData($divProKey, '-');
					if (!empty($divProKeyArr[0]) && !empty($divProKeyArr[1])) {
						//IF INVOICE NEW SESSION IS TRUE,THEN UPDATE IT WITH FALSE
						DB::table('invoice_session_dtl')->where('invoice_session_dtl.product_category_id', $divProKeyArr[1])->where('invoice_session_dtl.division_id', $divProKeyArr[0])->update(['invoice_session_dtl.invoice_session_status' => '0']);
					}
				}
			}
		}
	}

	/*************************************
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : Praveen Singh
	 **************************************/
	public function getOrderInvoivingPrice($customer_id, $order_id, $discount_type, $discount_value)
	{

		global $order, $models;

		$returnData = $parameterWiseRateData = array();

		//getting customer data**************************************
		$customerData = DB::table('customer_master')->where('customer_master.customer_id', '=', $customer_id)->first();

		//getting Order data*****************************************
		$orderData 	     = DB::table('order_master')->where('order_master.order_id', '=', $order_id)->first();
		$invoicingTypeId     = !empty($orderData->invoicing_type_id) ? $orderData->invoicing_type_id : '0';
		$division_id         = !empty($orderData->division_id) ? $orderData->division_id : '0';
		$product_category_id = !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';
		$customer_city	     = !empty($orderData->customer_city) ? $orderData->customer_city : '0';
		$discount_value	     = !empty($discount_value) ? str_replace('%', '', $discount_value) : '0';

		//Conditional Invoicing Type*********************************
		if (!empty($invoicingTypeId) && !empty($product_category_id)) {
			if ($invoicingTypeId == '1') {			//ITC Parameter Wise
				$sellingPriceAmount = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->sum('order_parameters_detail.selling_price');
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
				$orderDetail           = DB::table('order_master')->select('order_master.order_id', 'order_master.test_standard', 'order_master.customer_id')->where('order_master.order_id', $order_id)->first();
				$orderParametersDetail = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->whereNotNull('equipment_type_id')->get();

				if ($product_category_id == '2') {
					$sellingPriceAmount = $this->getCustomerWiseAssayParameterRates($invoicingTypeId, $customerData->customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail));
				} else {
					$sellingPriceAmount = $this->getCustomerWiseParameterRates($invoicingTypeId, $customerData->customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail));
				}
			}
		}

		//Discount Calculation****************************************
		if ($discount_type == '1') {  				//if case amount
			$returnData  = array($sellingPriceAmount, $discount_value);
		} else if ($discount_type == '2') { 			//if case percentage
			$discountAmt = ($sellingPriceAmount * $discount_value) / 100;
			$returnData  = array($sellingPriceAmount, $discountAmt);
		} else if ($discount_type == '3') { 			//if case nodiscount
			$discountAmt = '0';
			$returnData  = array($sellingPriceAmount, $discountAmt);
		}

		return $returnData;
	}

	/****************************************
	 * Get Customer Wise Parameter Rates
	 * Date : 12-April-2018
	 * Author : Praveen Singh
	 *****************************************/
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

	/**********************************************
	 * Get Customer Wise Assay Parameter Rates
	 * Date : 12-April-2018
	 * Author : Praveen Singh
	 ************************************************/
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
							if (!empty($orderParameters['test_parameter_category_id'])) {
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
			$invoicingRate = in_array('0', $parameterWiseRateData) ? '0' : array_sum($parameterWiseRateData);
		}
		return $invoicingRate;
	}

	/**************************************************************************
	 * get report details rom order_report_details table
	 * Date :
	 * Author : Praveen Singh
	 ***************************************************************************/
	public function getInvoiceDetail($invoice_ids)
	{
		$getInvoiceDetail = array();
		foreach ($invoice_ids as $key => $invoice_id) {
			$getInvoiceDetail[] = DB::table('invoice_hdr')->where('invoice_id', '=', $invoice_id)->select('invoice_hdr.*')->first();
		}
		return $getInvoiceDetail;
	}

	/**********************************************************
	 * Show the form for creating a new resource.
	 * Date :
	 * Author :Praveen Singh
	 ***********************************************************/
	public function getInvoiceData($invoice_id, $invoice_template_type = NULL)
	{

		global $order, $models, $invoice, $numbersToWord, $mail;

		$invoiceData = array();

		if (!empty($invoice_id)) {

			$invoiceDetailList = DB::table('invoice_hdr')
				->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
				->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
				->join('divisions', 'divisions.division_id', 'invoice_hdr.division_id')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->join('product_categories', 'product_categories.p_category_id', 'invoice_hdr.product_category_id')
				->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
				->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_id')
				->join('customer_contact_persons', 'customer_contact_persons.customer_id', 'customer_master.customer_id')
				->join('state_db', 'state_db.state_id', 'customer_master.customer_state')
				->join('city_db', 'customer_master.customer_city', 'city_db.city_id')
				->join('users as invoiceByTb', 'invoiceByTb.id', 'invoice_hdr.created_by')
				->leftjoin('customer_master as invoicing_master', 'invoicing_master.customer_id', 'invoice_hdr_detail.order_invoicing_to')
				->leftjoin('city_db as invoicingToCity', 'invoicingToCity.city_id', 'invoicing_master.customer_city')
				->leftjoin('state_db as invoicingToState', 'invoicingToState.state_id', 'invoicing_master.customer_state')
				->leftJoin('template_dtl', function ($join) {
					$join->on('template_dtl.division_id', '=', 'invoice_hdr.division_id');
					$join->where('template_dtl.template_type_id', '=', '2');
					$join->where('template_dtl.template_status_id', '=', '1');
				})
				->select('divisions.division_name', 'product_categories.p_category_name', 'customer_master.customer_name', 'customer_master.customer_email', 'customer_master.customer_address', 'customer_master.customer_state', 'customer_master.customer_city', 'customer_master.customer_gst_no', 'city_db.city_name as customer_city_name', 'state_db.state_name as customer_state_name', 'order_master.order_date', 'order_master.sample_description_id', 'order_master.customer_id', 'order_master.batch_no', 'order_master.order_no', 'order_master.order_id', 'order_master.discount_type_id', 'order_master.discount_value', 'order_master.product_category_id', 'order_master.billing_type_id', 'order_master.po_no', 'order_master.po_date', 'invoice_hdr.*', 'order_report_details.report_file_name', 'order_report_details.report_no', 'product_master_alias.c_product_name as sample_description', 'invoice_hdr_detail.order_amount', 'invoice_hdr_detail.order_discount', 'invoice_hdr_detail.order_total_amount', 'invoice_hdr_detail.order_sgst_amount', 'invoice_hdr_detail.order_cgst_amount', 'invoice_hdr_detail.order_igst_amount', 'invoice_hdr_detail.order_net_amount', 'invoicing_master.customer_address as altInvoicingAddress', 'invoicingToState.state_name as invoicing_state', 'invoicingToCity.city_name as invoicing_city', 'invoicing_master.customer_name as invoicingCustomerName', 'invoicing_master.customer_gst_no as invoicingCustomerGSTo', 'customer_contact_persons.contact_name1', 'customer_contact_persons.contact_mobile1', 'invoiceByTb.name as invoice_by', 'invoiceByTb.user_signature', 'template_dtl.header_content', 'template_dtl.footer_content')
				->where('invoice_hdr.invoice_id', $invoice_id)
				->orderBy('order_master.po_no', 'ASC')
				->orderBy('order_master.order_no', 'ASC')
				->get();

			if (!empty($invoiceDetailList)) {
				foreach ($invoiceDetailList as $key => $values) {

					list($toEmails, $ccEmails) = $order->getCustomerEmailToCC($values->customer_id);
					$values->to_emails        = array_values($toEmails);
					$values->cc_emails 	      = array_values($ccEmails);
					$values->net_total_wsw    = round(DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_id', $values->invoice_id)->sum('invoice_hdr_detail.order_net_amount'));

					$invoiceData['invoiceHeader'] = array(
						'invoice_id'            	=> $values->invoice_id,
						'invoice_no'            	=> $values->invoice_no,
						'division_name'            	=> $values->division_name,
						'p_category_name'            	=> $values->p_category_name,
						'customer_id'           	=> $values->customer_id,
						'customer_name'         	=> !empty($values->invoicingCustomerName) ? ucfirst($values->invoicingCustomerName) : ucfirst($values->customer_name),
						'customer_email'        	=> $values->customer_email,
						'to_emails'        		=> $values->to_emails,
						'cc_emails'        		=> $values->cc_emails,
						'contact_name1'         	=> $values->contact_name1,
						'contact_mobile1'       	=> $values->contact_mobile1,
						'customer_city_name'    	=> !empty($values->invoicing_city) ? strtoupper($values->invoicing_city) : strtoupper($values->customer_city_name),
						'customer_state_name'   	=> !empty($values->invoicing_state) ? strtoupper($values->invoicing_state) : strtoupper($values->customer_state_name),
						'customer_address'      	=> !empty($values->altInvoicingAddress) ? $values->altInvoicingAddress : $values->customer_address,
						'customer_gst_no'       	=> !empty($values->invoicingCustomerGSTo) ? strtoupper($values->invoicingCustomerGSTo) : strtoupper($values->customer_gst_no),
						'invoice_date'          	=> date(DATEFORMAT, strtotime($values->invoice_date)),
						'invoice_file_name'     	=> $values->invoice_file_name,
						'order_no'          		=> $values->order_no,
						'billing_type'          	=> $values->billing_type_id,
						'invoice_by'			=> $values->invoice_by,
						'user_signature_path'		=> ROOT_DIR . SIGN_PATH,
						'user_signature'		=> $values->user_signature,
						'user_signature_file_path'	=> !empty($values->user_signature) ? ROOT_DIR . SIGN_PATH . $values->user_signature : '',
						'user_signature_file_url'	=> !empty($values->user_signature) ? ROOT_URL . SIGN_PATH . $values->user_signature : '',
						'division_id'   		=> $values->division_id,
						'product_category_id'   	=> $values->product_category_id,
						'invoice_file_name_without_hf' 	=> $values->invoice_file_name_without_hf,
						'header_content' 		=> $values->header_content,
						'footer_content' 		=> $values->footer_content,
					);

					if (!empty($values->billing_type_id) && !empty($values->po_no) && $values->billing_type_id == '5') {
						$poDatekey = !empty($values->po_no) && !empty($values->po_date) ? $values->po_no . '|' . date('d-m-Y', strtotime($values->po_date)) : $values->po_no;
						$invoiceData['invoiceBody'][$poDatekey][$key] = array(
							'order_id'          	=> $values->order_id,
							'po_no'          		=> $values->po_no,
							'name_of_product'   	=> $values->sample_description,
							'batch_no'          	=> $values->batch_no,
							'order_no'          	=> $values->order_no,
							'report_no'         	=> $values->report_no,
							'report_file_name'  	=> $values->report_file_name,
							'amount'            	=> $values->order_amount,
							'basic_rate'            	=> $values->order_total_amount,
							'service_tax'          	=> $models->roundValue($values->order_sgst_amount + $values->order_cgst_amount + $values->order_igst_amount),
							'l1_final_amount'           => $models->roundValue($values->order_net_amount),
						);
					} else {
						$invoiceData['invoiceBody'][$key] = array(
							'order_id'          	=> $values->order_id,
							'po_no'          		=> $values->po_no,
							'name_of_product'   	=> $values->sample_description,
							'batch_no'          	=> $values->batch_no,
							'order_no'          	=> $values->order_no,
							'report_no'         	=> $values->report_no,
							'report_file_name'  	=> $values->report_file_name,
							'amount'            	=> $values->order_amount,
							'basic_rate'            	=> $values->order_total_amount,
							'service_tax'          	=> $models->roundValue($values->order_sgst_amount + $values->order_cgst_amount + $values->order_igst_amount),
							'l1_final_amount'           => $models->roundValue($values->order_net_amount),
						);
					}

					$invoiceData['invoiceFooter'] = array(
						'total'              		=> $values->total_amount,
						'discount'           		=> $values->total_discount,
						'discount_text'      		=> !empty($values->discount_type_id) && $values->discount_type_id == '2' ? '(' . $values->discount_value . '%)' : '0',
						'net_amount'         		=> !empty($values->total_discount) && round($values->total_discount) > '0' ? $models->roundValue($values->total_amount - $values->total_discount) : '',
						'surcharge_amount'   		=> $values->surcharge_amount,
						'extra_amount'       		=> $values->extra_amount,
						'sgst_rate'          		=> $values->sgst_rate,
						'sgst_amount'        		=> $values->sgst_amount,
						'cgst_rate'          		=> $values->cgst_rate,
						'cgst_amount'        		=> $values->cgst_amount,
						'igst_rate'          		=> $values->igst_rate,
						'igst_amount'        		=> $values->igst_amount,
						'net_total_wsw'          	=> number_format($values->net_total_wsw, 2),
						'net_total_in_words_wsw' 	=> strtoupper($numbersToWord->number_to_word($values->net_total_wsw)),
						'net_total'          		=> number_format(round($values->net_total_amount), 2),
						'net_total_in_words' 		=> strtoupper($numbersToWord->number_to_word(round($values->net_total_amount)))
					);
				}
			}
		}
		return $invoiceData;
	}

	/***************************************************************
	 * update Invoice File Name
	 * Date :
	 * Author :Praveen Singh
	 ****************************************************************/
	public function updateInvoiceFileName($invoiceFileNameAndIds)
	{
		if (!empty($invoiceFileNameAndIds)) {
			foreach ($invoiceFileNameAndIds as $invoice_id => $invoiceFileName) {
				if ($invoice_id && $invoiceFileName) {
					DB::table('invoice_hdr')->where('invoice_hdr.invoice_id', '=', $invoice_id)->update(['invoice_hdr.invoice_file_name' => $invoiceFileName]);
				}
			}
		}
	}

	/*******************************************************************
	 * update Invoice File Name
	 * Date :
	 * Author :Praveen Singh
	 ********************************************************************/
	public function updateInvoiceOrderStatusLog($invoiceId)
	{
		global $order, $invoice, $models, $mail;
		$invoiceHdrDetailData = DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_id', '=', $invoiceId)->get();
		if ($invoiceHdrDetailData) {
			foreach ($invoiceHdrDetailData as $invoiceHdrDetail) {
				$orderData = DB::table('order_master')->where('order_id', '=', $invoiceHdrDetail->order_id)->first();
				if (!empty($orderData->order_id)) {
					!empty($orderData->status) && $orderData->status == '8' ? $order->updateOrderStausLog($orderData->order_id, '9') : $order->updateOrderLog($orderData->order_id, '9');
				}
			}
			return true;
		}
	}

	/*******************************************************
	 *for the purpose to send multiple attachements
	 *get all orders for a invoice
	 * Date :
	 * Author :Praveen Singh
	 ********************************************************/
	function getInvoiceOrders($invoiceId)
	{
		$invoices = DB::table('invoice_hdr')
			->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
			->join('order_report_details', 'order_report_details.report_id', 'invoice_hdr_detail.order_id')
			->join('divisions', 'divisions.division_id', 'invoice_hdr.division_id')
			->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_id')
			->join('users as createdBy', 'createdBy.id', 'invoice_hdr.created_by')
			->select('invoice_hdr.*', 'invoice_hdr_detail.invoice_dtl_id', 'customer_master.customer_name', 'customer_master.customer_email', 'divisions.division_name', 'createdBy.name as createdByName', 'order_report_details.report_file_name')
			->where('invoice_hdr.invoice_id', '=', $invoiceId)
			->get();
		return $invoices;
	}

	/********************************************************************
	 * Description : check order dispatched status
	 * Date        : 28-12-2017
	 * Author      : Ruby
	 ***********************************************************************/
	public function orderDispatchedStatus($order_id)
	{
		return DB::table('order_dispatch_dtl')->where('order_id', '=', $order_id)->where('amend_status', '=', 0)->first();
	}

	/********************************************************************
	 * Description : update Customer State Defined In Invoicing-To Column of Order Master
	 * Date        : 05-02-2018
	 * Author      : Praveen Singh
	 ***********************************************************************/
	public function updateCustomerStateDefinedInInvoicingToColumn($orders)
	{
		if (!empty($orders->invoicing_to)) {
			$customerData = DB::table('customer_master')->where('customer_master.customer_id', $orders->invoicing_to)->first();
			$orders->customer_state = $customerData->customer_state;
		}
	}

	/***********************************************************************
	 * Convert a multi-dimensional array into a single-dimensional array.
	 * Date :05-02-2018
	 * Author :Praveen Singh
	 **********************************************************************/
	function assignInvoicingGroupForAssigningRates($values)
	{

		global $order, $models;

		$invoicingGroupName = '';

		$values    = $models->convertObjectToArray($values);
		$subValues = $models->convertObjectToArray(DB::table('order_parameters_detail')
			->select('parentProductCategoryDB.p_category_id as product_category_id', 'productCategoryDB.p_category_id', 'subProductCategoryDB.p_category_id as sub_p_category_id', 'test_parameter_categories.test_para_cat_id as test_parameter_category_id', 'test_parameter.test_parameter_invoicing_parent_id')
			->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
			->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
			->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
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
			if (!empty($orderParameters['product_category_id']) && $orderParameters['product_category_id'] != '2') {
				$invoicingGroupName = $orderParameters['test_parameter_id'];
			} else {
				//Checking the global Invoicing allowed to the parameters
				$isInvoicingNeeded = DB::table('test_parameter')->where('test_parameter.test_parameter_id', $orderParameters['test_parameter_id'])->where('test_parameter.test_parameter_invoicing', '1')->first();
				if (!empty($isInvoicingNeeded) && !empty($orderParameters['test_parameter_id']) && !empty($orderParameters['equipment_type_id'])) {
					if (!empty($orderParameters['detector_id']) && !empty($orderParameters['running_time_id']) && !empty($orderParameters['no_of_injection'])) {	//checking If Detector,Running Time,no of Injection exist
						if (!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1') {
							if (!empty($orderParameters['test_parameter_invoicing_parent_id'])) {
								$invoicingGroupName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['test_parameter_invoicing_parent_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'] . '-' . $orderParameters['detector_id'] . '-' . $orderParameters['running_time_id'];
							} else {
								$invoicingGroupName = $orderParameters['test_parameter_id'];
							}
						} else {
							$invoicingGroupName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'] . '-' . $orderParameters['detector_id'] . '-' . $orderParameters['running_time_id'];
						}
					} else {
						if (!empty($orderParameters['test_parameter_category_id'])) {
							if (!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1') {
								if (!empty($orderParameters['test_parameter_invoicing_parent_id'])) {
									$invoicingGroupName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['test_parameter_invoicing_parent_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
								} else {
									$invoicingGroupName = $orderParameters['test_parameter_id'];
								}
							} else {
								$invoicingGroupName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
							}
						}
					}
				}
			}
		}
		return $invoicingGroupName;
	}

	/***************************************
	 * Get list of companies on page load.
	 * Date :05-02-2018
	 * Author :Praveen Singh
	 ******************************************/
	public function getReportsInvoicingRates($customer_id, $order_id)
	{

		global $order, $models;

		$returnData = $parameterWiseRateData = array();

		$sellingPriceAmount = '0';

		//getting customer data**************************************
		$customerData = DB::table('customer_master')->where('customer_master.customer_id', '=', $customer_id)->first();

		//getting Order data*****************************************
		$orderData 	     = DB::table('order_master')->where('order_master.order_id', '=', $order_id)->first();
		$invoicingTypeId     = !empty($orderData->invoicing_type_id) ? $orderData->invoicing_type_id : '0';
		$division_id         = !empty($orderData->division_id) ? $orderData->division_id : '0';
		$product_category_id = !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';
		$customer_city	     = !empty($orderData->customer_city) ? $orderData->customer_city : '0';

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
					->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
					->where('customer_invoicing_rates.cir_customer_id', '=', $customerData->customer_id)
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
				$orderDetail           = DB::table('order_master')->select('order_master.order_id', 'order_master.test_standard', 'order_master.customer_id')->where('order_master.order_id', $order_id)->first();
				$orderParametersDetail = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->whereNotNull('equipment_type_id')->get();

				if ($product_category_id == '2') {
					$parameterWiseRateData = $this->getCustomerWiseAssayParameterRatesInReport($invoicingTypeId, $customerData->customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail));
				} else {
					$parameterWiseRateData = $this->getCustomerWiseParameterRatesInReport($invoicingTypeId, $customerData->customer_id, $division_id, $product_category_id, $models->convertObjectToArray($orderParametersDetail));
				}
			}
		}
		return array($sellingPriceAmount, $parameterWiseRateData);
	}

	/***************************************
	 * Get Customer Wise Parameter Rates
	 * Date : 12-April-2018
	 * Author : Praveen Singh
	 ******************************************/
	public function getCustomerWiseParameterRatesInReport($invoicing_type_id, $customer_id, $division_id, $product_category_id, $orderParametersDetail)
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
		}
		return $parameterWiseRateData;
	}

	/********************************************
	 * Get Customer Wise Assay Parameter Rates
	 * Date : 12-April-2018
	 * Author : Praveen Singh
	 ********************************************/
	public function getCustomerWiseAssayParameterRatesInReport($invoicing_type_id, $customer_id, $division_id, $product_category_id, $orderParametersDetail)
	{

		global $order, $models;

		$parameterWiseRateData = $paramterInvoicingWithCount = $withDectorsTestCategory = $withDectorsAssayCategory = $withoutDectorsTestCategory = $withoutDectorsTestParentCategory = $withoutDectorsAssayCategory = $withoutDectorsTestCategory = $noOfInjectionWithDectorsCategory = $withDectorsTestCategoryInfo = $withDectorsAssayCategoryInfo = $withoutDectorsAssayCategoryInfo = $withoutDectorsTestParentCategoryInfo = array();
		$invoicingRate 	       = 0;

		if (!empty($orderParametersDetail)) {
			foreach ($orderParametersDetail as $key => $values) {
				$subValues = $models->convertObjectToArray(DB::table('order_parameters_detail')
					->select('parentProductCategoryDB.p_category_id as product_category_id', 'productCategoryDB.p_category_id', 'subProductCategoryDB.p_category_id as sub_p_category_id', 'test_parameter_categories.test_para_cat_id as test_parameter_category_id', 'test_parameter.test_parameter_invoicing_parent_id')
					->join('product_test_dtl', 'product_test_dtl.product_test_dtl_id', 'order_parameters_detail.product_test_parameter')
					->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
					->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
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
									$withoutDectorsTestCategory[$orderParameters['test_parameter_id']] = $orderParameters;
								}
							} else {
								$groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'] . '-' . $orderParameters['detector_id'] . '-' . $orderParameters['running_time_id'];
								$noOfInjectionWithDectorsCategory[$groupedColoumName][] 	= $orderParameters['no_of_injection'];
								$orderParameters['no_of_per_injection']     			= '1';
								$withDectorsAssayCategory[$groupedColoumName][] 		= $orderParameters;
							}
						} else {
							if (!empty($orderParameters['test_parameter_category_id'])) {
								if (!empty($orderParameters['test_parameter_category_id']) && $orderParameters['test_parameter_category_id'] == '1') {
									if (!empty($orderParameters['test_parameter_invoicing_parent_id'])) {
										$groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['test_parameter_invoicing_parent_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
										$withoutDectorsTestParentCategory[$groupedColoumName][] = $orderParameters;
									} else {
										$withoutDectorsTestCategory[$orderParameters['test_parameter_id']] = $orderParameters;
									}
								} else {
									$groupedColoumName = $orderParameters['test_parameter_category_id'] . '-' . $orderParameters['product_category_id'] . '-' . $orderParameters['p_category_id'] . '-' . $orderParameters['sub_p_category_id'] . '-' . $orderParameters['equipment_type_id'];
									$withoutDectorsAssayCategory[$groupedColoumName][] = $values;
								}
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
		}

		return $parameterWiseRateData;
	}

	/***********************************************
	 * Get Customer Wise Assay Parameter Rates
	 * Date : 12-April-2018
	 * Author : Praveen Singh
	 *************************************************/
	public function getInvoiceOrdersList($invoiceList)
	{
		if (!empty($invoiceList)) {
			foreach ($invoiceList as $key => $value) {
				$invoiceOrders = DB::table('invoice_hdr')
					->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
					->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
					->where('invoice_hdr.invoice_id', '=', $value->invoice_id)
					->pluck('order_master.order_no')
					->all();
				$value->related_orders = implode(",", $invoiceOrders);
			}
		}
	}

	/******************************************************
	 * Get Customer Wise Assay Parameter Rates
	 * Date : 12-April-2018
	 * Author : Praveen Singh
	 ********************************************************/
	public function getNetTotalAmtByInvTemplateType($invoiceList)
	{

		global $order, $models;

		foreach ($invoiceList as $invoices) {
			$invoices->net_total_amount = $models->roundValue(round($invoices->net_total_amount));
		}
	}

	/**********************************************
	 * Cancelled invoice with orders
	 * Date : 26-July-2018
	 * Author : Praveen Singh
	 *************************************************/
	public function cancelled_invoice_with_orders($formData)
	{

		global $order, $report, $models, $creditNote, $debitNote;

		//Starting transaction
		DB::beginTransaction();

		try {

			$returnData       		 = array();
			$invoiceId        		 = !empty($formData['invoice_id']) ? $formData['invoice_id'] : '0';
			$cancellationDescription 	 = !empty($formData['cancellation_description']) ? trim($formData['cancellation_description']) : NULL;
			$invoiceCancApprovedBy 	 = !empty($formData['invoice_canc_approved_by']) ? trim($formData['invoice_canc_approved_by']) : NULL;
			$invoiceCancApprovedDateTime = !empty($formData['invoice_canc_approved_date']) ? $models->getFormatedDateTime($formData['invoice_canc_approved_date'], $format = 'Y-m-d') : NULL;
			$invoiceHdr       		 = $this->getInvoiceHdr($invoiceId);
			$invoiceHdrDetail 		 = $this->getInvoiceHdrDetail($invoiceId);

			if (!empty($invoiceHdr) && !empty($invoiceHdrDetail)) {

				//*******************************CASE : 1.Raise a Credit Note***************************************
				$dataSaveCreditNote = array();
				$dataSaveCreditNote['credit_note_no'] 		= $creditNote->generateCreditNoteNumber('CN');
				$dataSaveCreditNote['credit_note_type_id'] 	= '1';
				$dataSaveCreditNote['credit_note_date'] 	= CURRENTDATETIME;
				$dataSaveCreditNote['division_id'] 		= $invoiceHdr->division_id;
				$dataSaveCreditNote['product_category_id'] 	= $invoiceHdr->product_category_id;
				$dataSaveCreditNote['customer_id'] 		= $invoiceHdr->customer_id;
				$dataSaveCreditNote['invoice_id'] 		= $invoiceHdr->invoice_id;
				$dataSaveCreditNote['credit_note_amount'] 	= $invoiceHdr->net_amount;
				$dataSaveCreditNote['credit_note_sgst_rate'] 	= $invoiceHdr->sgst_rate;
				$dataSaveCreditNote['credit_note_sgst_amount'] 	= $invoiceHdr->sgst_amount;
				$dataSaveCreditNote['credit_note_cgst_rate'] 	= $invoiceHdr->cgst_rate;
				$dataSaveCreditNote['credit_note_cgst_amount'] 	= $invoiceHdr->cgst_amount;
				$dataSaveCreditNote['credit_note_igst_rate'] 	= $invoiceHdr->igst_rate;
				$dataSaveCreditNote['credit_note_igst_amount'] 	= $invoiceHdr->igst_amount;
				$dataSaveCreditNote['credit_note_net_amount'] 	= $invoiceHdr->net_total_amount;
				$dataSaveCreditNote['credit_note_remark'] 	= 'Invoice Full Cancel with Ref';
				$dataSaveCreditNote['created_by'] 		= USERID;
				$returnData[] = DB::table('credit_notes')->insertGetId($dataSaveCreditNote);
				//******************************/CASE : 1.Raise a Credit Note***************************************

				//********************CASE : 2.Update the Status of Invoice and Its Detail to cancelled State.******************************
				if (DB::table('invoice_hdr')->where('invoice_hdr.invoice_id', $invoiceHdr->invoice_id)->update(['invoice_hdr.invoice_status' => '2'])) {
					//Saving Invoice Cancellation detail
					$dataSaveInvoiceCancellation = array();
					$dataSaveInvoiceCancellation['invoice_id'] 	                     = $invoiceHdr->invoice_id;
					$dataSaveInvoiceCancellation['invoice_cancelled_date'] 	     = CURRENTDATETIME;
					$dataSaveInvoiceCancellation['invoice_cancellation_description'] = $cancellationDescription;
					$dataSaveInvoiceCancellation['invoice_cancelled_by'] 	     = USERID;
					$dataSaveInvoiceCancellation['invoice_canc_approved_by'] 	     = $invoiceCancApprovedBy;
					$dataSaveInvoiceCancellation['invoice_canc_approved_date'] 	     = $invoiceCancApprovedDateTime;
					if (DB::table('invoice_cancellation_dtls')->insertGetId($dataSaveInvoiceCancellation)) {
						foreach ($invoiceHdrDetail as $invoiceHdrDtl) {		//Updating Invoice Header Detail
							if (!empty($invoiceHdrDtl->invoice_dtl_id)) {
								$returnData[] = DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_id', $invoiceHdrDtl->invoice_hdr_id)->where('invoice_hdr_detail.invoice_dtl_id', $invoiceHdrDtl->invoice_dtl_id)->update(['invoice_hdr_detail.invoice_hdr_status' => '2']);
							}
						}
					}
				}
				//*******************/CASE : 2.Update the Status of Invoice and Its Detail to cancelled State.******************************

				//*************CASE : 3.Update the status of all related Orders to cancelled Stage with remarks as 'Invoice Cancelled'**************
				foreach ($invoiceHdrDetail as $invoiceHdrDtl) {
					$dataSaveorderCancellationDtl = array();
					$dataSaveorderCancellationDtl['order_id']                = $invoiceHdrDtl->order_id;
					$dataSaveorderCancellationDtl['cancellation_type_id']    = '3';
					$dataSaveorderCancellationDtl['cancellation_description'] = 'Invoice Cancelled';
					$dataSaveorderCancellationDtl['cancellation_stage']      = $order->getOrderDetail($invoiceHdrDtl->order_id)->status;
					$dataSaveorderCancellationDtl['cancelled_date']          = CURRENTDATETIME;
					$dataSaveorderCancellationDtl['cancelled_by']            = USERID;
					$dataSaveorderCancellationDtl['cancellation_status']     = '1';
					$orderCancellationId = DB::table('order_cancellation_dtls')->insertGetId($dataSaveorderCancellationDtl);
					$returnData[] = !empty($orderCancellationId) ? $order->updateOrderStausLog($invoiceHdrDtl->order_id, '10') : '0';  //Updating Order Status to Cancelled Mode
					$returnData[] = DB::table('order_master')->where('order_master.order_id', $invoiceHdrDtl->order_id)->update(['order_master.order_reinvoiced_count' => $order->getOrderDetail($invoiceHdrDtl->order_id)->order_reinvoiced_count + 1]); 	//Updating order reinvoiced count
				}
				//*************/CASE : 3.Update the status of all related Orders to cancelled Stage with remarks as 'Invoice Cancelled'**************
			}

			//Committing the queries
			DB::commit();

			return in_array('0', $returnData) ? false : true;
		} catch (\Exception $e) {
			DB::rollback();
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			return false;
		}
	}

	/***************************************************************
	 * Cancelled invoice without orders(Re-Generation of Invoices)
	 * Date : 26-July-2018
	 * Author : Praveen Singh
	 * @param  $invoiceId
	 * @return true/false
	 ***************************************************************/
	public function cancelled_invoice_without_orders($formData)
	{

		global $order, $report, $models, $creditNote, $debitNote;

		//Starting transaction
		DB::beginTransaction();

		try {

			$returnData 		 = array();
			$invoiceId        		 = !empty($formData['invoice_id']) ? $formData['invoice_id'] : '0';
			$cancellationDescription 	 = !empty($formData['cancellation_description']) ? trim($formData['cancellation_description']) : NULL;
			$invoiceCancApprovedBy 	 = !empty($formData['invoice_canc_approved_by']) ? trim($formData['invoice_canc_approved_by']) : NULL;
			$invoiceCancApprovedDateTime = !empty($formData['invoice_canc_approved_date']) ? $models->getFormatedDateTime($formData['invoice_canc_approved_date'], $format = 'Y-m-d') : NULL;
			$invoiceHdr 		 = $this->getInvoiceHdr($invoiceId);
			$invoiceHdrDetail 		 = $this->getInvoiceHdrDetail($invoiceId);

			if (!empty($invoiceHdr) && !empty($invoiceHdrDetail)) {

				//*******************************CASE : 1.Raise a Credit Note***************************************
				$dataSaveCreditNote = array();
				$dataSaveCreditNote['credit_note_no'] 		= $creditNote->generateCreditNoteNumber('CN');
				$dataSaveCreditNote['credit_note_type_id'] 	= '1';
				$dataSaveCreditNote['credit_note_date'] 	= CURRENTDATETIME;
				$dataSaveCreditNote['division_id'] 		= $invoiceHdr->division_id;
				$dataSaveCreditNote['product_category_id'] 	= $invoiceHdr->product_category_id;
				$dataSaveCreditNote['customer_id'] 		= $invoiceHdr->customer_id;
				$dataSaveCreditNote['invoice_id'] 		= $invoiceHdr->invoice_id;
				$dataSaveCreditNote['credit_note_amount'] 	= $invoiceHdr->net_amount;
				$dataSaveCreditNote['credit_note_sgst_rate'] 	= $invoiceHdr->sgst_rate;
				$dataSaveCreditNote['credit_note_sgst_amount'] 	= $invoiceHdr->sgst_amount;
				$dataSaveCreditNote['credit_note_cgst_rate'] 	= $invoiceHdr->cgst_rate;
				$dataSaveCreditNote['credit_note_cgst_amount'] 	= $invoiceHdr->cgst_amount;
				$dataSaveCreditNote['credit_note_igst_rate'] 	= $invoiceHdr->igst_rate;
				$dataSaveCreditNote['credit_note_igst_amount'] 	= $invoiceHdr->igst_amount;
				$dataSaveCreditNote['credit_note_net_amount'] 	= $invoiceHdr->net_total_amount;
				$dataSaveCreditNote['credit_note_remark'] 	= 'Invoice Full Cancel with Ref';
				$dataSaveCreditNote['created_by'] 		= USERID;
				$returnData[] = DB::table('credit_notes')->insertGetId($dataSaveCreditNote);
				//******************************/CASE : 1.Raise a Credit Note***************************************

				//********************CASE : 2.Update the Status of Invoice and Its Detail to cancelled State.******************************
				if (DB::table('invoice_hdr')->where('invoice_hdr.invoice_id', $invoiceHdr->invoice_id)->update(['invoice_hdr.invoice_status' => '2'])) {
					//Saving Invoice Cancellation detail
					$dataSaveInvoiceCancellation = array();
					$dataSaveInvoiceCancellation['invoice_id'] 	                    = $invoiceHdr->invoice_id;
					$dataSaveInvoiceCancellation['invoice_cancelled_date'] 	     = CURRENTDATETIME;
					$dataSaveInvoiceCancellation['invoice_cancellation_description'] = $cancellationDescription;
					$dataSaveInvoiceCancellation['invoice_cancelled_by'] 	     = USERID;
					$dataSaveInvoiceCancellation['invoice_canc_approved_by'] 	     = $invoiceCancApprovedBy;
					$dataSaveInvoiceCancellation['invoice_canc_approved_date'] 	     = $invoiceCancApprovedDateTime;
					if (DB::table('invoice_cancellation_dtls')->insertGetId($dataSaveInvoiceCancellation)) {
						foreach ($invoiceHdrDetail as $invoiceHdrDtl) {		//Updating Invoice Header Detail
							if (!empty($invoiceHdrDtl->invoice_dtl_id)) {
								$returnData[] = DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_id', $invoiceHdrDtl->invoice_hdr_id)->where('invoice_hdr_detail.invoice_dtl_id', $invoiceHdrDtl->invoice_dtl_id)->update(['invoice_hdr_detail.invoice_hdr_status' => '2']);
							}
						}
					}
				}
				//*******************/CASE : 2.Update the Status of Invoice and Its Detail to cancelled State.******************************

				//*************CASE : 3.Update the status of all related Orders to cancelled Stage with remarks as 'Invoice Cancelled'**************
				foreach ($invoiceHdrDetail as $invoiceHdrDtl) {

					//Updating order reinvoiced count
					$returnData[] = DB::table('order_master')->where('order_master.order_id', $invoiceHdrDtl->order_id)->update(['order_master.order_reinvoiced_count' => $order->getOrderDetail($invoiceHdrDtl->order_id)->order_reinvoiced_count + 1]);

					//Updating Current Order Status to Invoicing Stage
					$returnData[] = $order->updateOrderStausLog($invoiceHdrDtl->order_id, '8');
				}
				//*************/CASE : 3.Update the status of all related Orders to cancelled Stage with remarks as 'Invoice Cancelled'**************
			}

			//Committing the queries
			DB::commit();

			return in_array('0', $returnData) ? false : true;
		} catch (\Exception $e) {
			DB::rollback();
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			return false;
		}
	}
}
