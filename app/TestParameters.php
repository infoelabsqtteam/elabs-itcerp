<?php

namespace App;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use HttpOz\Roles\Traits\HasRole;
use HttpOz\Roles\Contracts\HasRole as HasRoleContract;
use Illuminate\Database\Eloquent\Model;

class TestParameters extends Authenticatable implements HasRoleContract
{
	use Notifiable;

	/**
	 * Third Party Service for user role ...
	 * URI https://github.com/httpoz/roles
	 */
	use Notifiable, HasRole;

	protected $table = 'test_parameter';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $fillable = [
		'test_parameter_code', 'department_id', 'test_parameter_name', 'test_parameter_print_desc', 'test_parameter_category_id', 'test_parameter_invoicing', 'test_parameter_invoicing_parent_id', 'test_parameter_nabl_scope', 'test_parameter_decimal_place', 'created_by'
	];

	function getParameterEquipmentName($equipment_id)
	{
		$data = DB::table('equipment_type')->select('equipment_name')->where('equipment_id', '=', $equipment_id)->first();
		if (!empty($data))
			return $data->equipment_name;
		else
			return false;
	}

	function getParameterEquipmentIdByName($equipment_name)
	{
		$data = DB::table('equipment_type')->select('equipment_id')->where('equipment_name', '=', $equipment_name)->first();
		if (!empty($data))
			return $data->equipment_id;
		else
			return false;
	}

	/************************************
	 * Description : is parameter name exist
	 * Date        : 26-12-2017
	 * Author      : Praveen Singh
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function getGlobalFilteredTestParameters($test_parameter_name, $test_parameter_category_id)
	{

		$returnData  = array();

		$allTestParameterData = DB::table('test_parameter')->where('test_parameter.test_parameter_name', 'LIKE', '%' . strip_tags(strtolower($test_parameter_name)) . '%')->where('test_parameter.test_parameter_category_id', '=', $test_parameter_category_id)->get();

		if (!empty($allTestParameterData)) {
			foreach ($allTestParameterData as $key => $testParameter) {
				$returnData[$testParameter->test_parameter_id] = strip_tags($testParameter->test_parameter_name);
			}
		}
		return $returnData;
	}

	/************************************
	 * Description : isExist Is used to check the test_parameter duplicate entry by code
	 * Date        : 01-09-17
	 * Author      : nisha
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function isTestParameterCodeExist($test_parameter_code)
	{
		$data = DB::table('test_parameter')->where('test_parameter.test_parameter_code', '=', $test_parameter_code)->first();
		if (!empty($data->test_parameter_code)) {
			return $data->test_parameter_code;
		} else {
			return false;
		}
	}

	/************************************
	 * Description : isEquipmentExist Is used to check the test_parameter duplicate entry with equipment
	 * Date        : 20-07-17
	 * Author      : nisha
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function isEquipmentExist($test_parameter_id, $equipment_type_id)
	{

		if (!empty($test_parameter_id) && !empty($equipment_type_id)) {
			$data = DB::table('test_parameter_equipment_types')
				->where('test_parameter_equipment_types.test_parameter_id', '=', $test_parameter_id)
				->where('test_parameter_equipment_types.equipment_type_id', '=', $equipment_type_id)
				->first();
			if (!empty($data->test_parameter_id)) {
				return $data->test_parameter_id;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/************************************
	 * Description : is parameter name exist
	 * Date        : 26-12-2017
	 * Author      : Praveen Singh
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function isParameterNameExist($test_parameter_name, $test_parameter_category_id, $testParameterId = NULL)
	{

		$globalTestParameters = $this->getGlobalFilteredTestParameters($test_parameter_name, $test_parameter_category_id);
		$test_parameter_name  = strip_tags($test_parameter_name);

		if (empty($testParameterId)) {
			if (!in_array($test_parameter_name, $globalTestParameters)) {
				return false;
			} else {
				return true;
			}
		} else {
			if (in_array($test_parameter_name, $globalTestParameters) && array_key_exists($testParameterId, $globalTestParameters)) {
				return false;
			} else {
				if (!in_array($test_parameter_name, $globalTestParameters)) {
					return false;
				} else {
					return true;
				}
			}
		}
	}
}
