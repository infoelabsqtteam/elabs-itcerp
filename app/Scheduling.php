<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Scheduling extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'schedulings';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [];

	/**
	 * Formating Scheduling Data
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function formatSchedulingJobData($formData)
	{

		global $order, $models, $mail, $autoCommand;

		$dataRaw = $data = $returnData = array();

		$saveAll = !empty($formData['save_all']) ? array_values($formData['save_all']) : array();
		if (!empty($saveAll)) unset($formData['save_all']);

		if (!empty($formData)) {
			foreach ($formData as $keyName => $valueAll) {
				foreach ($valueAll as $key => $value) {
					if (!empty($value)) {
						if (!empty($value) && $keyName == 'tentative_date') {
							$dataRaw[$key][$keyName] = date('Y-m-d', strtotime($value));
						} else {
							$dataRaw[$key][$keyName] = $value;
						}
					}
				}
			}
		}

		if (!empty($dataRaw)) {
			foreach ($dataRaw as $key => $job) {
				if (!empty($job['order_id']) && empty($order->isOrderBookingCancelled($job['order_id']))) { 		//Filtering the cancalled Order Data
					if (!empty($job['equipment_type_id'])) {
						if (is_array($job) && array_key_exists('tentative_date', $job) && array_key_exists('tentative_time', $job) && array_key_exists('employee_id', $job)) {
							if (!empty($saveAll) && in_array($job['scheduling_id'], $saveAll)) {
								$data[$job['equipment_type_id']]['scheduling'] = $job;
								$data[$job['equipment_type_id']]['scheduling']['saveAll']  = $job['scheduling_id'];
							} else {
								$data[$job['equipment_type_id']]['schedulingOneByOne'][$job['scheduling_id']] = $job;
							}
						} else {
							$data[$job['equipment_type_id']]['scheduling']['schedulingAll'][$job['scheduling_id']] = $job;
						}
					} else {
						if (!empty($job['employee_id'])) {
							$data[$key]['schedulingOne'][$job['scheduling_id']] = $job;
						}
					}
				}
			}
		}

		if (!empty($data)) {
			foreach ($data as $key => $value) {
				if (!empty($value['schedulingOneByOne'])) {
					$returnData[] = $value['schedulingOneByOne'];
				}
				if (!empty($value['scheduling']['saveAll'])) {
					$returnData[] = $value['scheduling'];
				}
				if (!empty($value['schedulingOne'])) {
					$returnData[] = $value['schedulingOne'];
				}
			}
		}

		return !empty($returnData) ? array_values($returnData) : array();
	}

	//Formating Scheduled Assigned Job Data
	public function formatScheduledAssignedJobData($formData)
	{
		$dataRaw = $data = array();
		if (!empty($formData)) {
			foreach ($formData as $keyName => $valueAll) {
				foreach ($valueAll as $key => $value) {
					if (!empty($value) || ($keyName == 'status' && $value == '3')) {
						$dataRaw[$key][$keyName] = $value;
					}
				}
			}
		}
		if (!empty($dataRaw)) {
			foreach ($dataRaw as $key => $job) {
				if (!empty($job) && array_key_exists('status', $job) && array_key_exists('notes', $job) && $job['status'] == '2') {
					$data[$key] = $job;
				} else if (!empty($job) && array_key_exists('status', $job) && $job['status'] == '3') {
					$data[$key] = $job;
				}
			}
		}
		return $data;
	}

	/*************************
	 *Getting Analyst Name involved in an Order
	 *Date : 13-June-2018
	 *Created By:Praveen Singh
	 *************************/
	public function assignedAnalystToAssignedPendingJob($pendingJobData, $division_id, $department_ids)
	{
		if (!empty($pendingJobData)) {
			foreach ($pendingJobData as $pendingJob) {
				$userObj = DB::table('users')
					->join('users_department_detail', 'users_department_detail.user_id', '=', 'users.id')
					->join('role_user', 'users.id', '=', 'role_user.user_id')
					->join('users_equipment_detail', 'users_equipment_detail.user_id', 'users.id')
					->select('users.id', 'users.name', 'users.email', 'role_user.role_id')
					->where('role_user.role_id', '=', '6')
					->where('users.is_sales_person', '=', '0')
					->where('users.status', '=', '1');

				if (!empty($division_id) && is_numeric($division_id)) {
					$userObj->where('users.division_id', '=', $division_id);
				}
				//Filtering records according to department assigned
				if (!empty($department_ids) && is_array($department_ids)) {
					$userObj->whereIn('users_department_detail.department_id', $department_ids);
				}
				//Filtering records according to department assigned
				if (defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER) {
					if (empty($pendingJob->description)) {
						$userObj->where('users_equipment_detail.equipment_type_id', !empty($pendingJob->equipment_type_id) ? $pendingJob->equipment_type_id : '0');
					}
				} else if (!empty($pendingJob->equipment_type_id)) {
					$userObj->where('users_equipment_detail.equipment_type_id', $pendingJob->equipment_type_id);
				}
				$pendingJob->analystOption = $userObj->groupBy('users.id')->orderBy('users.name', 'ASC')->get();
			}
		}
	}

	/*************************
	 *Getting Analyst Name involved in an Order
	 *Date : 13-June-2018
	 *Created By:Praveen Singh
	 *************************/
	public function assignedAnalystToAssignedPendingJob_v1($pendingJobData)
	{
		if (!empty($pendingJobData)) {
			foreach ($pendingJobData as $pendingJob) {
				$userObj = DB::table('users')
					->join('users_department_detail', 'users_department_detail.user_id', 'users.id')
					->join('department_product_categories_link', 'department_product_categories_link.department_id', 'users_department_detail.department_id')
					->join('role_user', 'users.id', 'role_user.user_id')
					->join('users_equipment_detail', 'users_equipment_detail.user_id', 'users.id')
					->select('users.id', 'users.name', 'users.email', 'role_user.role_id')
					->where('role_user.role_id', '=', '6')
					->where('users.is_sales_person', '=', '0')
					->where('users.status', '=', '1');

				if (!empty($pendingJob->division_id) && is_numeric($pendingJob->division_id)) {
					$userObj->where('users.division_id', $pendingJob->division_id);
				}
				//Filtering records according to department assigned
				if (!empty($pendingJob->product_category_id) && is_numeric($pendingJob->product_category_id)) {
					$userObj->where('department_product_categories_link.product_category_id', $pendingJob->product_category_id);
				}
				//Filtering records according to department assigned
				if (defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER) {
					if (empty($pendingJob->description)) {
						$userObj->where('users_equipment_detail.equipment_type_id', !empty($pendingJob->equipment_type_id) ? $pendingJob->equipment_type_id : '0');
					}
				} else if (!empty($pendingJob->equipment_type_id)) {
					$userObj->where('users_equipment_detail.equipment_type_id', $pendingJob->equipment_type_id);
				}
				$pendingJob->analystOption = $userObj->groupBy('users.id')->orderBy('users.name', 'ASC')->get();
			}
		}
	}

	/**
	 * Calculating the Equipment Pendency Equipment Count of all Equipment
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getAllEquipmentPendency($pendingJobData)
	{

		if (!empty($pendingJobData)) {

			$globalEquipmetList = $globalMatchedEquipmetList = array();

			foreach ($pendingJobData as $pendingkey => $pendingJob) {
				if (!empty($pendingJob->division_id) && !empty($pendingJob->product_category_id) && !empty($pendingJob->equipment_type_id)) {
					$columnKey = $pendingJob->division_id . '-' . $pendingJob->product_category_id . '-' . $pendingJob->equipment_type_id;
					$globalEquipmetList[$columnKey] = $columnKey;
				}
			}
			foreach ($globalEquipmetList as $nestedkey => $globalEquipmet) {
				$keyDataId = explode('-', $nestedkey);
				if (!empty($keyDataId[0]) && !empty($keyDataId[1]) && !empty($keyDataId[2])) {
					$globalMatchedEquipmetList[$nestedkey] = DB::table('schedulings')->join('order_master', 'order_master.order_id', 'schedulings.order_id')->where('order_master.division_id', $keyDataId[0])->where('schedulings.product_category_id', $keyDataId[1])->where('schedulings.equipment_type_id', $keyDataId[2])->whereIn('schedulings.status', array(1, 2))->select('schedulings.scheduling_id')->count();
				}
			}
			foreach ($pendingJobData as $key => $values) {
				$values->equipment_pendency = '';
				if (!empty($values->division_id) && !empty($values->product_category_id) && !empty($values->equipment_type_id)) {
					$matchingKey = $values->division_id . '-' . $values->product_category_id . '-' . $values->equipment_type_id;
					$values->equipment_pendency = !empty($globalMatchedEquipmetList[$matchingKey]) ? $globalMatchedEquipmetList[$matchingKey] : '';
				}
			}
		}
	}

	/**
	 * Calculating the Maximaum Equipment Count in a particular Order
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __getMaxEquipmentTypeIdFromAnOrder($orderId)
	{

		global $order, $models, $mail;

		$returnData = array();

		$schedulings = DB::table('schedulings')->where('schedulings.order_id', $orderId)->whereNotNull('schedulings.equipment_type_id')->get();
		if (!empty($schedulings)) {
			foreach ($schedulings as $key => $value) {
				$returnData[$value->equipment_type_id][]   = $value->equipment_type_id;
				$returnDataInfo[$value->equipment_type_id] = count($returnData[$value->equipment_type_id]);
			}
			asort($returnDataInfo);
		}
		$returnDataInfo = !empty($returnDataInfo) ? array_flip($returnDataInfo) : array();

		return !empty($returnDataInfo) ? end($returnDataInfo) : '0';
	}

	/**
	 * Calculating the Maximaum Equipment Count in a particular Order
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __updateParameterHavingEquipmentTypeNull($currentEquipmentTypeId, $orderId, $currentUpdateFields)
	{

		global $order, $models, $mail;

		$nullEquipmentSchedulingData = DB::table('schedulings')->where('schedulings.order_id', $orderId)->whereNull('equipment_type_id')->whereNull('employee_id')->get();

		if (!empty($nullEquipmentSchedulingData)) {
			$maxEqipmentTypeId = $this->__getMaxEquipmentTypeIdFromAnOrder($orderId);
			if (!empty($currentEquipmentTypeId) && !empty($currentUpdateFields) && !empty($maxEqipmentTypeId) && $currentEquipmentTypeId == $maxEqipmentTypeId) {
				foreach ($nullEquipmentSchedulingData as $nullEquipmentScheduling) {
					DB::table('schedulings')->where('schedulings.scheduling_id', $nullEquipmentScheduling->scheduling_id)->where('schedulings.order_id', $nullEquipmentScheduling->order_id)->whereNull('equipment_type_id')->whereNull('employee_id')->update($currentUpdateFields);
					$order->updateOrderStausLog($orderId, '2');	//Updating the Order Log Status
				}
				return true;
			} else {
				return false;
			}
		}
		return true;
	}

	/**
	 * Calculating the Maximaum Equipment Count in a particular Order
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __updateOrderStatusStageAndSendMail($orderId)
	{

		global $order, $models, $mail, $autoCommand;

		//Checking and updating Order Status 3(TESTING) if all orders parameter assigned to Testers
		$currentDateTime		= defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
		$checkOrderStatusUpdated 	= DB::table('order_master')->where('order_master.order_id', $orderId)->where('order_master.status', '2')->first();
		$checkUpdateOrderStatus  	= DB::table('schedulings')->where('schedulings.order_id', $orderId)->whereNull('schedulings.employee_id')->first();
		$checkUpdateInchageStatus	= !empty($checkOrderStatusUpdated) && empty($checkUpdateOrderStatus) ? $this->updateResetOrderInchargeDetail($orderId) : '';
		$updatedScheduledDateStatus	= !empty($checkOrderStatusUpdated) && empty($checkUpdateOrderStatus) ? $order->updateOrderScheduledDateTime($orderId, $currentDateTime) : '';
		$updatedStatus           	= !empty($checkOrderStatusUpdated) && empty($checkUpdateOrderStatus) ? $order->updateOrderStatusToNextPhase($orderId, '3') : '';

		//send mail to customer on Order Scheduled
		$orderInTestingStage    = DB::table('order_master')->where('order_master.order_id', $orderId)->where('order_master.status', '3')->first();
		$orderMailSendStatus	= $order->hasOrderConfirmationMailSent($orderId);
		$sendMailStatus         = !empty($orderInTestingStage->order_id) && empty($orderMailSendStatus) ? $autoCommand->saveAutoMail(array('order_id' => $orderInTestingStage->order_id, 'mailSavingType' => '2')) : '';
		return true;
	}

	/*********************************************************
	 *get Orde Equipment Incharge Detail
	 *Created on:16-Aug-2018
	 *Created By:Praveen Singh
	 *********************************************************/
	public function updateResetOrderInchargeDetail($orderId)
	{

		global $order, $models, $mail, $autoCommand;

		$sectionInhargeDetail = $order->getOrderEquipmentInchargeDetail($orderId);
		if (!empty($sectionInhargeDetail)) {
			foreach ($sectionInhargeDetail as $equipmentTypeId => $inchargeIdArray) {
				if (!empty($inchargeIdArray) && is_array($inchargeIdArray)) {
					foreach ($inchargeIdArray as $key => $inchargeId) {
						$checkInchargeExistence = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', $orderId)->where('order_incharge_dtl.oid_employee_id', $inchargeId)->where('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeId)->first();
						if (empty($checkInchargeExistence)) {
							$dataSave 				= array();
							$dataSave['order_id'] 	        = $orderId;
							$dataSave['oid_employee_id']        = $inchargeId;
							$dataSave['oid_equipment_type_id']  = $equipmentTypeId;
							$dataSave['oid_assign_date']        = CURRENTDATETIME;
							$dataSave['oid_status']             = '0';
							DB::table('order_incharge_dtl')->insertGetId($dataSave);
						} else {
							$dataUpdate = array('order_incharge_dtl.oid_confirm_date' => NULL, 'order_incharge_dtl.oid_confirm_by' => NULL, 'order_incharge_dtl.oid_status' => '0');
							DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', $orderId)->where('order_incharge_dtl.oid_employee_id', $inchargeId)->where('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeId)->update($dataUpdate);
						}
					}
				}
			}
		}
	}

	/*******************************************
	 *Function: Updating Order Expected Due Date(EDD) using no. of days.
	 *Created By: Praveen Singh
	 *Created On : 10-Oct-2019
	 ******************************************/
	function updateOrderExpectedDueDateUsingDays($orderId, $noOfDays, $excludeCalculationLogics = false)
	{

		global $order, $models;

		$orderDetail = $order->getOrderDetail($orderId);
		if (!empty($orderDetail->booking_date) && !empty($noOfDays)) {

			//Add days to current date to calculate the observed expected due date
			$expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $noOfDays . ' day', strtotime($orderDetail->booking_date)));

			if ($excludeCalculationLogics == false) {

				//Checking if any holidays lies between order booking date and Calculated Expected Due Date
				$holidayDayCounts = DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where('holiday_master.division_id', $orderDetail->division_id)->whereBetween(DB::raw("DATE(holiday_master.holiday_date)"), array(date('Y-m-d', strtotime($orderDetail->booking_date)), date('Y-m-d', strtotime($expectedDueDate))))->count();
				if ($holidayDayCounts) {
					$expectedDueDate = $models->validateSundayHoliday_v2($orderDetail->division_id, date(MYSQLDATETIMEFORMAT, strtotime('+' . $holidayDayCounts . ' day', strtotime($expectedDueDate))), '1', '+');
				}

				//Checking there any sunday lies on calculated days,then add number of days according to number of sunday in expected due date
				$sundays = $models->getSundays($orderDetail->booking_date, $expectedDueDate);
				if (!empty($sundays)) {
					$expectedDueDate = $models->validateSundayHoliday_v2($orderDetail->division_id, date(MYSQLDATETIMEFORMAT, strtotime('+' . count($sundays) . ' day', strtotime($expectedDueDate))), '1', '+');
				}

				//Including the Current Day in the Final Expected Due Date
				$expectedDueDate = $models->validateSundayHoliday_v2($orderDetail->division_id, date(MYSQLDATETIMEFORMAT, strtotime('-1 day', strtotime($expectedDueDate))), '1', '-');
			}

			return !empty($expectedDueDate) ? DB::table('order_master')->where('order_master.order_id', $orderId)->update(['order_master.expected_due_date' => $expectedDueDate]) : false;
		}
	}

	/*******************************************
	 *Function : Updating Report Due Date and Department Due date in Order Parameter table using no. of days.
	 *Created By : Praveen Singh
	 *Created On : 10-Oct-2019
	 ******************************************/
	function updateReportDepartmentDueDateUsingDays($order_id, $noOfDays, $excludeCalculationLogics = false)
	{

		global $order, $models;

		$flag = array();

		$orderDetail = $order->getOrderDetail($order_id);
		if (!empty($orderDetail->order_id)) {
			$orderParametersDetail = DB::table('order_parameters_detail')->join('order_master', 'order_master.order_id', 'order_parameters_detail.order_id')->select('order_master.order_id', 'order_master.booking_date', 'order_master.division_id', 'order_parameters_detail.analysis_id', 'order_parameters_detail.time_taken_days', 'order_parameters_detail.report_due_date', 'order_parameters_detail.dept_due_date')->where('order_parameters_detail.order_id', $orderDetail->order_id)->get()->toArray();
			if (!empty($orderParametersDetail)) {
				foreach ($orderParametersDetail as $key => $orderParameter) {

					//Add days to current date to calculate the observed expected due date
					$parameter_report_due_date = $models->validateSundayHoliday_v2($orderParameter->division_id, date(MYSQLDATETIMEFORMAT, strtotime('+' . $noOfDays . ' day', strtotime($orderParameter->booking_date))), '1', '+');

					if ($excludeCalculationLogics == false) {

						//Checking if any holidays lies between order booking date and Calculated Department/Report Due Date
						$holidayDayCounts = DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where('holiday_master.division_id', $orderParameter->division_id)->whereBetween(DB::raw("DATE(holiday_master.holiday_date)"), array(date('Y-m-d', strtotime($parameter_report_due_date)), date('Y-m-d', strtotime($parameter_report_due_date))))->count();
						if ($holidayDayCounts) {
							$parameter_report_due_date = $models->validateSundayHoliday_v2($orderParameter->division_id, date(MYSQLDATETIMEFORMAT, strtotime('+' . $holidayDayCounts . ' day', strtotime($parameter_report_due_date))), '1', '+');
						}

						//Checking there any sunday lies on calculated days,then add number of days according to number of sunday in department/report due date
						$sundays = $models->getSundays($orderParameter->booking_date, $parameter_report_due_date);
						if (!empty($sundays)) {
							$parameter_report_due_date = $models->validateSundayHoliday_v2($orderParameter->division_id, date(MYSQLDATETIMEFORMAT, strtotime('+' . count($sundays) . ' day', strtotime($parameter_report_due_date))), '1', '+');
						}

						//Including the Current Day in the Final Expected Due Date
						$parameter_report_due_date = $models->validateSundayHoliday_v2($orderParameter->division_id, date(MYSQLDATETIMEFORMAT, strtotime('-1 day', strtotime($parameter_report_due_date))), '1', '-');
					}

					if (!empty($orderParameter->analysis_id) && !empty($parameter_report_due_date)) {
						$flag[] = DB::table('order_parameters_detail')->where('order_parameters_detail.analysis_id', $orderParameter->analysis_id)->update(['order_parameters_detail.dept_due_date' => $parameter_report_due_date, 'order_parameters_detail.report_due_date' => $parameter_report_due_date]);
					}
				}

				//Updating Department Due Date and Report Due Date in Order Master
				$maxOrderDepDueDate = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $order_id)->max('order_parameters_detail.dept_due_date');
				$flag[] = !empty($maxOrderDepDueDate) ? DB::table('order_master')->where('order_master.order_id', $order_id)->update(['order_master.order_dept_due_date' => $maxOrderDepDueDate, 'order_master.order_report_due_date' => $maxOrderDepDueDate]) : false;
			}
		}
		return !empty($flag) ? true : false;
	}

	/*******************************************
	 *Function   : Updating Order Expected Due Date Logs
	 *Created By : Praveen Singh
	 *Created On : 26-July-2021
	 ******************************************/
	function updateOrderExpectedDueDateLogs($submittedArray)
	{
		if (
			!empty($submittedArray['order_id'])
			&& !empty($submittedArray['expected_due_date'])
			&& !empty($submittedArray['no_of_days'])
			&& !empty($submittedArray['reason_of_change'])
		) {
			$dataSave = array();
			$dataSave['oeddl_order_id'] 				  = $submittedArray['order_id'];
			$dataSave['oeddl_current_expected_due_date']  = date(MYSQLDATETIMEFORMAT, strtotime($submittedArray['expected_due_date']));
			$dataSave['oeddl_modified_expected_due_date'] = DB::table('order_master')->where('order_master.order_id', $submittedArray['order_id'])->pluck('order_master.expected_due_date')->first();
			$dataSave['oeddl_no_of_days'] 				  = $submittedArray['no_of_days'];
			$dataSave['oeddl_reason_of_change'] 		  = $submittedArray['reason_of_change'];
			$dataSave['oeddl_exclude_logic_calculation']  = !empty($submittedArray['exclude_logic_calculation']) ? '1' : '2';
			$dataSave['oeddl_send_mail_status'] 		  = !empty($submittedArray['send_mail_status']) ? '1' : '2';
			$dataSave['oeddl_modified_date'] 			  = defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
			$dataSave['oeddl_created_by'] 				  = USERID;
			return DB::table('order_expected_due_date_logs')->insertGetId($dataSave);
		} else {
			return false;
		}
	}
}
