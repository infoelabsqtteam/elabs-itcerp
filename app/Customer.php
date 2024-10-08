<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Third Party Service for user role ...
 * URI https://github.com/httpoz/roles
 */

use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;
use DB;

class Customer extends Authenticatable implements HasRoleContract
{
	/**
	 * Third Party Service for user role ...
	 * URI https://github.com/httpoz/roles
	 */
	use Notifiable, HasRole;

	protected $table = 'customer_master';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['customer_code', 'customer_name', 'customer_address', 'customer_city', 'our_code_with_that_customer', 'customer_mobile', 'customer_phone', 'customer_email1', 'billing_type', 'mfg_lic_no', 'customer_type', 'discount_type', 'discount_value', 'customer_vat_cst', 'created_by', 'customer_country',];

	//this is a recommended way to declare event handlers
	protected static function boot()
	{
		parent::boot();
		static::deleting(function ($record) {});
	}

	/***********************************************************
	 * function to get Customers
	 * @param  int  $id(02-Feb-2018)
	 * @return \Illuminate\Http\Response
	 ************************************************************/
	public function getCustomers($customer_id = NULL)
	{
		if ($customer_id) {
			return DB::table('customer_master')->where('customer_master.customer_id', '=', $customer_id)->first();
		} else {
			return DB::table('customer_master')->select('customer_master.customer_id', 'customer_master.customer_name', 'customer_master.customer_email')->orderBy('customer_master.customer_name', 'ASC')->get()->toArray();
		}
	}

	/***********************************************************
	 * function to get Customers
	 * @param  int  $id(02-Feb-2018)
	 * @return \Illuminate\Http\Response
	 ************************************************************/
	public function getCustomersAccToDefinedStructure($customerDetail)
	{

		global $models, $order;

		$dataObj = DB::table('customer_master')
			->select('customer_master.customer_id', 'customer_master.customer_name', 'customer_master.customer_email')
			->join('customer_defined_structures', 'customer_defined_structures.customer_id', '=', 'customer_master.customer_id');
		if (!empty($customerDetail->division_id)) {
			$dataObj->where('customer_defined_structures.division_id', '=', $customerDetail->division_id);
		}
		if (!empty($customerDetail->product_category_id)) {
			$dataObj->where('customer_defined_structures.product_category_id', '=', $customerDetail->product_category_id);
		}
		if (!empty($order->isOrderInvoiceGenerated($customerDetail->order_id))) {
			$dataObj->where('customer_defined_structures.customer_id', '=', $customerDetail->customer_id);
		}
		$dataObj->orderBy('customer_master.customer_name', 'ASC');
		return $dataObj->get();
	}

	/***********************************************************
	 * function to get Customers
	 * 03-Jan-2019
	 * Praveen Singh
	 ************************************************************/
	public function getStabilityCustomersAccToDefinedStructure($customerDetail)
	{

		global $models, $order;

		$dataObj = DB::table('customer_master')
			->select('customer_master.customer_id', 'customer_master.customer_name', 'customer_master.customer_email')
			->join('customer_defined_structures', 'customer_defined_structures.customer_id', '=', 'customer_master.customer_id');
		if (!empty($customerDetail->stb_division_id)) {
			$dataObj->where('customer_defined_structures.division_id', '=', $customerDetail->stb_division_id);
		}
		if (!empty($customerDetail->stb_product_category_id)) {
			$dataObj->where('customer_defined_structures.product_category_id', '=', $customerDetail->stb_product_category_id);
		}
		$dataObj->orderBy('customer_master.customer_name', 'ASC');
		return $dataObj->get();
	}

	/***********************************************************
	 * function to get Sales Executive According To Division
	 * @param  int  $id(02-Feb-2018)
	 * @return \Illuminate\Http\Response
	 ************************************************************/
	public function getSalesExecutiveAccordingToDivision($division_id)
	{
		return DB::table('users')->where('users.is_sales_person', '=', '1')->where('users.division_id', '=', $division_id)->get();
	}

	/**
	 * Is Customer Code Exist Is used to check the customer duplicate entry by customer_code
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function isCustomerCodeExist($customer_code)
	{
		$customerData = DB::table('customer_master')->where('customer_master.customer_code', '=', trim($customer_code))->first();
		return empty($customerData->customer_code) ? true : false;
	}

	/**
	 * Is Customer Email Exist Is used to check the customer duplicate entry by customer_code
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function isCustomerEmailExist($customer_email, $id = NULL)
	{
		if ($id) {
			$customerData = DB::table('customer_master')->where('customer_master.customer_id', '=', $id)->where('customer_master.customer_email', '=', trim($customer_email))->first();
			if (!empty($customerData)) {
				return true;
			} else {
				$customerData = DB::table('customer_master')->where('customer_master.customer_email', '=', trim($customer_email))->first();
				return empty($customerData->customer_email) ? true : false;
			}
		} else {
			$customerData = DB::table('customer_master')->where('customer_master.customer_email', '=', trim($customer_email))->first();
			return empty($customerData->customer_email) ? true : false;
		}
	}

	/**
	 * Validate Mobile Number Is used to check the customer duplicate entry by customer_code
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function validateMobileNumber($customer_mobile, $id = NULL)
	{
		if (preg_match("/^[7-9][0-9]{9}$/", $customer_mobile)) {
			if ($id) {
				$customerData = DB::table('customer_master')->where('customer_master.customer_id', '=', $id)->where('customer_master.customer_mobile', '=', trim($customer_mobile))->first();
				if (!empty($customerData)) {
					return true;
				} else {
					$customerData = DB::table('customer_master')->where('customer_master.customer_mobile', '=', trim($customer_mobile))->first();
					return empty($customerData->customer_email) ? true : false;
				}
			} else {
				$customerData = DB::table('customer_master')->where('customer_master.customer_mobile', '=', trim($customer_mobile))->first();
				return empty($customerData->customer_email) ? true : false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Validate Phone Number Is used to check the customer duplicate entry by customer_code
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function validatePhoneNumber($phoneNumber, $id = NULL)
	{
		if (preg_match("/[0-9]{3}[-][0-9]{6,8}$/", $phoneNumber)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * CHECK EMAIL EXIST OR NOT
	 * Date : 09-01-2018
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function validateCustomerEmailAddresses($customer_email, $customer_id, $id = NULL)
	{
		if ($id) {
			$customerData = DB::table('customer_email_addresses')
				->where('customer_email_addresses.customer_id', $customer_id)
				->where('customer_email_addresses.customer_email', trim($customer_email))
				->where('customer_email_addresses.customer_email_id', $id)
				->first();
			if (!empty($customerData)) {
				return true;
			} else {
				$customerData = DB::table('customer_email_addresses')->where('customer_email_addresses.customer_email', '=', trim($customer_email))->where('customer_email_addresses.customer_id', $customer_id)->first();
				return empty($customerData->customer_email) ? true : false;
			}
		} else {
			$customerData = DB::table('customer_email_addresses')->where('customer_email_addresses.customer_email', '=', trim($customer_email))->where('customer_email_addresses.customer_id', $customer_id)->first();
			return empty($customerData->customer_email) ? true : false;
		}
	}

	/**
	 * CHECK EMAIL EXIST OR NOT
	 * Date : 09-01-2018
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function validateCustomerEmailTypes($customer_id, $customer_email_type, $customer_email_id = NULL)
	{
		if (!empty($customer_email_type) && $customer_email_type == 'P') {
			if ($customer_email_id) {
				$primaryEmailData = DB::table('customer_email_addresses')->where('customer_email_addresses.customer_email_id', $customer_email_id)->where('customer_email_addresses.customer_id', $customer_id)->where('customer_email_addresses.customer_email_type', '=', $customer_email_type)->count();
				if (!empty($primaryEmailData)) {
					return false;
				} else {
					return DB::table('customer_email_addresses')->where('customer_email_addresses.customer_id', $customer_id)->where('customer_email_addresses.customer_email_type', '=', $customer_email_type)->count();
				}
			} else {
				return DB::table('customer_email_addresses')->where('customer_email_addresses.customer_id', $customer_id)->where('customer_email_addresses.customer_email_type', '=', $customer_email_type)->count();
			}
		} else {
			return false;
		}
	}

	/**
	 * SAVE NEW CUSTOMER EMAIL AS PRIAMRY EMAIL
	 * Date : 11-01-2018
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function savePrimaryEmailDetail($customerId, $customerEmail)
	{
		$primaryEmailData = array();
		$primaryEmailData['customer_id'] = $customerId;
		$primaryEmailData['customer_email'] = $customerEmail;
		$primaryEmailData['customer_email_type'] = 'P';
		$primaryEmailData['customer_email_status'] = '1';
		$saved = DB::table('customer_email_addresses')->insertGetId($primaryEmailData);
		if ($saved) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * UPDATE CUSTOMER EMAIL AS PRIAMRY EMAIL
	 * Date : 22-01-2018
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function UpdatePrimaryEmailDetail($c_id, $new_email)
	{

		$updateCustomerEmail = array();

		$getCustomerData = DB::table('customer_master')->where('customer_master.customer_id', $c_id)->first();
		$emailToUpdate = !empty($getCustomerData) ? $getCustomerData->customer_email : '';

		if (!empty($emailToUpdate) && $new_email) {
			$updateCustomerEmail = !empty($new_email) ? $new_email : '';
			$getCustomerEmailData = DB::table('customer_email_addresses')
				->where('customer_email_addresses.customer_id', $c_id)
				->where('customer_email_addresses.customer_email', $emailToUpdate)
				->where('customer_email_addresses.customer_email_type', 'P')
				->update(['customer_email_addresses.customer_email' => $updateCustomerEmail]);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * get customer data for gst number.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerData($gstNo, $customerId = null)
	{

		$customerDetailArray = array();

		$gstNumber =  !empty($gstNo) ? $gstNo : NULL;
		$customerDetailArray['customerGst'] = DB::table('customer_master')->where('customer_master.customer_id', '=', $customerId)->where('customer_master.customer_gst_no', '=', $gstNumber)->first();
		$customerDetailArray['customerGstCount'] = DB::table('customer_master')->where('customer_master.customer_gst_no', '=', $gstNumber)->count();

		return $customerDetailArray;
	}

	/**
	 * CUSTOMER GST NUMBER VALIDATION
	 * Date : 17-01-2018
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function gstNumberValidation($customerGstTypeId, $gstNumber, $stateId)
	{

		$returnArray = array();

		if (!empty($customerGstTypeId) && $gstNumber && $stateId) {

			$stateData 		= DB::table('state_db')->where('state_db.state_id', '=', $stateId)->first();
			$stateCode      	= !empty($stateData->state_code) ? $stateData->state_code : '0';
			$number1to2 	= substr($gstNumber, 0, 2);
			$number3to7 	= substr($gstNumber, 2, 5);
			$number8to11 	= substr($gstNumber, 7, 4);
			$number12th 	= substr($gstNumber, 11, 1);
			$number13th 	= substr($gstNumber, 12, 1);
			$number14th 	= substr($gstNumber, 13, 1);
			$number15th 	= substr($gstNumber, 14, 1);
			$number 		= $number1to2 . $number3to7 . $number8to11 . $number12th . $number13th . $number14th . $number15th;

			if ($customerGstTypeId != '4') {
				return $returnArray;
			} else if (strlen($gstNumber) == '15') {
				if (!is_numeric($number1to2)) {
					$returnArray[] = 'GSTN:1st to 2nd digits should be customer state code.';
				}
				if (!ctype_alpha($number3to7)) {
					$returnArray[] = 'GSTN:3rd to 7th digits should be alphabets only.';
				}
				if (!is_numeric($number8to11)) {
					$returnArray[] = 'GSTN:8th to 11th digits should be numeric only.';
				}
				if (!ctype_alpha($number12th)) {
					$returnArray[] = 'GSTN:12th digit should be alphabet only.';
				}
				if (!ctype_alnum($number13th)) {
					$returnArray[] = 'GSTN:13th digit should be number/alphabet only.';
				}
				if ($number1to2 != $stateCode) {
					$returnArray[] = '1st to 2nd digits should be customer state code.';
				}
				if (preg_match('/^[0-9A-Z]*([0-9][A-Z]|[A-Z][0-9])[0-9A-Z]*$/', $number) == false) {
					$returnArray[] = 'GSTN:Please enter valid GST number or have minimum 15 characters or please insert code in capital letters.';
				}
			} else {
				$returnArray[] = 'GSTN:Please enter valid GST number or have minimum 15 characters or please insert code in capital letters.';
			}
		}
		return count($returnArray) > 1 ? implode(" | ", $returnArray) : implode("", $returnArray);
	}

	/**
	 * Is Customer Logic Code Exist
	 * Date : 07-Jan-2020
	 * Author : Praveen Singh
	 */
	public function isLogicCustomerCodeExist($logic_customer_code, $customerId)
	{
		if (!empty($logic_customer_code)) {
			$reportingCodeConstant = defined('REPORTING_CODE') ? REPORTING_CODE : 'Reporting Code';
			if (strtolower(str_replace(' ', '', $logic_customer_code)) == strtolower(str_replace(' ', '', $reportingCodeConstant))) {
				return true;
			} else {
				$data = DB::table('customer_master')->where('customer_master.customer_id', trim($customerId))->where('customer_master.logic_customer_code', trim($logic_customer_code))->count();
				if ($data) {
					return true;
				} else {
					if (DB::table('customer_master')->where('customer_master.logic_customer_code', trim($logic_customer_code))->count()) {
						return false;
					} else {
						return true;
					}
				}
			}
		} else {
			return false;
		}
	}
}
