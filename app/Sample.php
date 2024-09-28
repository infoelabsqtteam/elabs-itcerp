<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Sample extends Model
{
	protected $table = 'samples';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['product_category_id', 'customer_id', 'customer_name', 'mode', 'mode_description', 'remarks', 'created_by'];

	/**
	 * Sample Number Generation.
	 * Format : DepartmentName-YYMMDDSSERIALNo
	 */
	function generateSampleNumber($postedData)
	{

		global $models;

		if (!empty($postedData['sample_date']) && !empty($postedData['division_id']) && !empty($postedData['product_category_id'])) {

			$currentDay   			= date('d');
			$currentMonth 			= date('m');
			$currentYear  			= date('y');
			$sampleDate   			= $postedData['sample_date'];
			$sampleDay    			= date('d', strtotime($sampleDate));
			$sampleMonth  			= date('m', strtotime($sampleDate));
			$sampleYear   			= date('Y', strtotime($sampleDate));
			$sampleDYear  			= date('y', strtotime($sampleDate));
			$divisionId   			= $postedData['division_id'];
			$productCategoryId 		= $postedData['product_category_id'];
			$backDateDepartmentArr  = $models->getBackDateBookingDepartments(); //array('2', '6','308');

			//Getting Section Name
			$divisionData = DB::table('divisions')->where('divisions.division_id', $divisionId)->first();
			$divisionCode = !empty($divisionData->division_code) ? trim($divisionData->division_code) : '00';
			$sectionData  = DB::table('product_categories')->where('product_categories.p_category_id', $productCategoryId)->first();
			$sectionName  = !empty($sectionData->p_category_name) ? substr($sectionData->p_category_name, 0, 1) : 'F';

			//getting Max Serial Number
			//if (in_array($productCategoryId, $backDateDepartmentArr)) {
			if ($models->hasBackDateBookingDepartments($productCategoryId)) {
				$maxSampleData = DB::table('samples')->select('samples.sample_id', 'samples.sample_no')->where('samples.product_category_id', $productCategoryId)->whereDay('samples.sample_date', $sampleDay)->whereMonth('samples.sample_date', $sampleMonth)->whereYear('samples.sample_date', $sampleYear)->where('samples.division_id', $divisionId)->orderBy('samples.sample_no', 'DESC')->orderBy('samples.sample_id', 'DESC')->limit(1)->first();
			} else {
				$maxSampleData = DB::table('samples')->select('samples.sample_id', 'samples.sample_no')->where('samples.product_category_id', $productCategoryId)->whereMonth('samples.sample_date', $sampleMonth)->whereYear('samples.sample_date', $sampleYear)->where('samples.division_id', $divisionId)->orderBy('samples.sample_no', 'DESC')->orderBy('samples.sample_id', 'DESC')->limit(1)->first();
			}

			$maxSerialNo   = !empty($maxSampleData->sample_no) ? substr($maxSampleData->sample_no, 11) + 1 : '0001';
			$maxSerialNo   = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';

			//Combing all to get unique order number
			return $sectionName . $divisionCode . '-' . $sampleDYear . $sampleMonth . $sampleDay . 'S' . $maxSerialNo;
		}
		return false;
	}

	/**
	 * Sample Number Generation.
	 * Format : DepartmentName-YYMMDDSSERIALNo
	 */
	function reGenerateSampleNumber($postedData, $sampleId)
	{

		global $models;

		if (!empty($sampleId) && !empty($postedData['sample_date']) && !empty($postedData['division_id']) && !empty($postedData['product_category_id'])) {

			$sampleData       		= DB::table('samples')->where('samples.sample_id', $sampleId)->first();
			$orgProductCategoryId 	= $sampleData->product_category_id;
			$postedProductCategoryId	= $postedData['product_category_id'];
			$orgSampleDate    		= strtotime(date(DATEFORMAT, strtotime($sampleData->sample_date)));
			$postedSampleDate 		= strtotime(date(DATEFORMAT, strtotime($postedData['sample_date'])));

			if (($orgProductCategoryId != $postedProductCategoryId) || ($orgSampleDate != $postedSampleDate)) {
				return $this->generateSampleNumber($postedData);
			} else {
				return $sampleData->sample_no;
			}
		}
		return false;
	}

	/**
	 * Sample Number Generation.
	 * Format : DepartmentName-YYMMDDSSERIALNo
	 */
	function updateSampleDate($postedData, $sampleId)
	{

		global $models;

		if (!empty($sampleId) && !empty($postedData['division_id']) && !empty($postedData['product_category_id'])) {

			$sampleData       		= DB::table('samples')->where('samples.sample_id', $sampleId)->first();
			$orgProductCategoryId 	= $sampleData->product_category_id;
			$postedProductCategoryId	= $postedData['product_category_id'];
			$orgSampleDate    		= strtotime(date(DATEFORMAT, strtotime($sampleData->sample_date)));
			$postedSampleDate 		= strtotime(date(DATEFORMAT, strtotime($postedData['sample_date'])));
			$currentDateTime 		= !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');

			if (($orgProductCategoryId != $postedProductCategoryId) || ($orgSampleDate != $postedSampleDate)) {
				return $this->getFormatedSampleDate($postedData['sample_date'], $currentDateTime);
			} else {
				return $sampleData->sample_date;
			}
		}
	}

	/**
	 * Sample Number Generation.
	 * Format : DepartmentName-YYMMDDSSERIALNo
	 */
	function updateSampleCurrentDate($postedData, $sampleId)
	{

		global $models;

		if (!empty($sampleId) && !empty($postedData['division_id']) && !empty($postedData['product_category_id'])) {

			$sampleData       		= DB::table('samples')->where('samples.sample_id', $sampleId)->first();
			$orgProductCategoryId 	= $sampleData->product_category_id;
			$postedProductCategoryId	= $postedData['product_category_id'];
			$orgSampleDate    		= strtotime(date(DATEFORMAT, strtotime($sampleData->sample_date)));
			$postedSampleDate 		= strtotime(date(DATEFORMAT, strtotime($postedData['sample_date'])));
			$currentDateTime 		= !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');

			if (($orgProductCategoryId != $postedProductCategoryId) || ($orgSampleDate != $postedSampleDate)) {
				return $currentDateTime;
			} else {
				return $sampleData->sample_current_date;
			}
		}
	}

	/**
	 * isCustomerExist Is used to check the customer duplicate entry by customer_email
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function isCustomerExist($customer_email)
	{
		$customerData = DB::table('samples')->where('samples.customer_email', '=', $customer_email)->first();
		if (!empty($customerData->customer_email)) {
			return $customerData->customer_email;
		} else {
			return false;
		}
	}

	/**
	 * isCustomerExist Is used to check the customer duplicate entry by customer_email
	 * Date : 01-03-17
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getFormatedSampleDate($sampleDate, $currentTime)
	{
		if (!empty($sampleDate)) {
			return date(MYSQLDATFORMAT, strtotime($sampleDate)) . ' ' . date("H:i:s", strtotime($currentTime));
		} else {
			return $currentTime;
		}
	}

	/**
	 * isCustomerExist Is used to check the customer duplicate entry by customer_email
	 * Date : 01-03-17
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getSampleInformation($sampleId)
	{
		return DB::table('samples')
			->join('customer_defined_structures', 'customer_defined_structures.customer_id', 'samples.customer_id')
			->join('divisions', 'divisions.division_id', 'samples.division_id')
			->join('customer_master', 'customer_master.customer_id', 'customer_defined_structures.customer_id')
			->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', 'customer_defined_structures.invoicing_type_id')
			->select('samples.sample_id', 'samples.customer_id', 'samples.product_category_id', 'customer_master.customer_city', 'customer_master.customer_state', 'customer_defined_structures.invoicing_type_id', 'samples.division_id', 'customer_defined_structures.tat_editable')
			->whereColumn('customer_defined_structures.division_id', '=', 'samples.division_id')
			->whereColumn('customer_defined_structures.product_category_id', '=', 'samples.product_category_id')
			->where('samples.sample_id', $sampleId)
			->first();
	}

	/**
	 * isCustomerExist Is used to check the customer duplicate entry by customer_email
	 * Date : 01-03-17
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function validateTrfDetailOnEdit($formData)
	{
		return DB::table('samples')
			->where('samples.trf_id', !empty($formData['trf_id']) ? $formData['trf_id'] : '0')
			->where('samples.division_id', !empty($formData['division_id']) ? $formData['division_id'] : '0')
			->where('samples.customer_id', !empty($formData['customer_id']) ? $formData['customer_id'] : '0')
			->where('samples.product_category_id', !empty($formData['product_category_id']) ? $formData['product_category_id'] : '0')
			->where('samples.sample_id', !empty($formData['sample_id']) ? $formData['sample_id'] : '0')
			->count();
	}
}
