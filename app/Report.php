<?php

/*****************************************************
 *Report Model File
 *Created By:Praveen-Singh
 *Created On : 15-Dec-2017
 *Modified On : 29-Feb-2020
 *Package : ITC-ERP-PKL
 ******************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Report extends Model
{
	/*************************
	 * get report details from order_report_details table
	 *************************/
	public function getOrderReportDetails($report_id)
	{
		return DB::table('order_report_details')->where('report_id', '=', $report_id)->first();
	}

	/*************************
	 *Generating Report Number
	 *Format : Prefix-DepartmentName-YYMMDDSERIALNo
	 *************************/
	function generateReportNumber($currentDate, $productCategoryId, $divisionId, $incMaxSerialNo = NULL)
	{

		global $models;

		if (!empty($currentDate) && !empty($productCategoryId) && !empty($divisionId)) {

			$reportDay   			= date('d', strtotime($currentDate));
			$reportMonth 			= date('m', strtotime($currentDate));
			$reportYear  			= date('Y', strtotime($currentDate));
			$reportDYear 			= date('y', strtotime($currentDate));
			$backDateDepartmentArr  = $models->getBackDateBookingDepartments(); //array('2', '6','308');
			$productCategoryData 	= DB::table('product_categories')->where('product_categories.p_category_id', $productCategoryId)->first();
			$sectionName         	= !empty($productCategoryData->p_category_name) ? substr($productCategoryData->p_category_name, 0, 1) : 'F';

			//getting Max Serial Number
			if (!empty($incMaxSerialNo)) {
				$maxSerialNo = $incMaxSerialNo != '9999' ? str_pad($incMaxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';
			} else {
				//In case of Pharma Deparment,order number will be generated according to current month and current day
				//if (in_array($productCategoryId, $backDateDepartmentArr)) {
				if ($models->hasBackDateBookingDepartments($productCategoryId)) {
					$maxReportData = DB::table('order_report_details')->join('order_master', 'order_report_details.report_id', 'order_master.order_id')->select('order_report_details.report_id', 'order_report_details.report_no')->where('order_master.product_category_id', $productCategoryId)->whereDay('order_report_details.report_date', $reportDay)->whereMonth('order_report_details.report_date', $reportMonth)->whereYear('order_report_details.report_date', $reportYear)->where('order_master.division_id', $divisionId)->orderBy('order_report_details.report_no', 'DESC')->limit(1)->first();
				} else {
					$maxReportData = DB::table('order_report_details')->join('order_master', 'order_report_details.report_id', 'order_master.order_id')->select('order_report_details.order_report_id', 'order_report_details.report_id', 'order_report_details.report_no')->where('order_master.product_category_id', $productCategoryId)->whereMonth('order_report_details.report_date', $reportMonth)->whereYear('order_report_details.report_date', $reportYear)->where('order_master.division_id', $divisionId)->orderBy('order_report_details.report_no', 'DESC')->limit(1)->first();
				}
				$maxSerialNo = !empty($maxReportData->report_no) ? substr($maxReportData->report_no, 10) + 1 : '0001';
				$maxSerialNo = $maxSerialNo != '9999' ? str_pad($maxSerialNo, 4, '0', STR_PAD_LEFT) : '0001';
			}

			//Combing all to get unique order number
			$reportNumber = REPORT_PREFIX . $sectionName . '-' . $reportDYear . $reportMonth . $reportDay . $maxSerialNo;

			//Validating Uniqueness of Report Number
			$isReportNoAlreadyExist = DB::table('order_report_details')->where('order_report_details.report_no', '=', $reportNumber)->first();

			if (empty($isReportNoAlreadyExist)) {
				return $reportNumber;
			} else {
				return $this->generateReportNumber($currentDate, $productCategoryId, $divisionId, round($maxSerialNo + 1));
			}
		}
	}

	/*************************
	 *Generating Report Number
	 *Save report number
	 *************************/
	function updateGenerateReportNumberDate($orderId, $reportDate, $backReportDate = NULL)
	{

		global $order, $models;

		if (!empty($orderId)) {
			$dataSave   	= $reportData = array();
			$orderData 		= DB::table('order_master')->where('order_master.order_id', '=', $orderId)->first();
			$reportDetailData 	= DB::table('order_report_details')->where('order_report_details.report_id', '=', $orderId)->first();
			if (!empty($orderData->order_id)) {
				if (empty($reportDetailData->report_no) && empty($reportDetailData->report_date)) {
					$reportData['report_no']   = $this->generateReportNumber($reportDate, $orderData->product_category_id, $orderData->division_id);
					$reportData['report_date'] = $reportDate;
					return DB::table('order_report_details')->where('report_id', $orderId)->update($reportData);
				} else if (!empty($backReportDate) && !empty($reportDetailData->report_no) && !empty($reportDetailData->report_date)) {
					$reportData['report_no']   = $this->generateReportNumber($reportDate, $orderData->product_category_id, $orderData->division_id);
					$reportData['report_date'] = $reportDate;
					return DB::table('order_report_details')->where('report_id', $orderId)->update($reportData);
				}
			}
		}
		return false;
	}

	/*************************
	 *Update order status
	 *while saving a report
	 *************************/
	public function updateReportOrderStatusLog($orderId)
	{
		global $order, $report, $models, $invoice, $mail, $numbersToWord;
		$reportData = $order->getOrder($orderId);
		if ($reportData) {
			!empty($reportData->status) && $reportData->status == '7' ? $order->updateOrderStausLog($orderId, '8') : $order->updateOrderLog($orderId, '8');
			return true;
		}
		return false;
	}

	/*************************
	 * Update analyst/tester job/order assigned status
	 *
	 *************************/
	public function updateAnalystJobAssignedStatus($order_id, $order_parameter_id, $employee_id)
	{
		if (defined('IS_TESTER') && IS_TESTER) {
			$whereCondition = array('schedulings.order_id' => $order_id, 'schedulings.order_parameter_id' => $order_parameter_id, 'schedulings.employee_id' => $employee_id);
		} else {
			$whereCondition = array('schedulings.order_id' => $order_id, 'schedulings.order_parameter_id' => $order_parameter_id);
		}
		if (DB::table('schedulings')->where($whereCondition)->update(['status' => '3', 'notes' => 'Completed', 'completed_at' => defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s')])) {
			return true;
		} else {
			return false;
		}
	}

	/*************************
	 * Get user whose task status is uncompleted
	 *
	 *************************/
	public function getUserRoleIdTaskUncompleted($role_ids)
	{
		if (!empty($role_ids)) {
			return DB::table('order_status')
				->whereIn('order_status.role_id', $role_ids)
				->pluck('order_status.order_status_id')
				->toArray();
		}
	}

	/*************************
	 * Get user whose task status is completed
	 *
	 *************************/
	public function getUserOrderIdTaskCompleted($role_ids, $user_id)
	{
		if (!empty($role_ids) && !empty($user_id)) {
			return DB::table('order_status')
				->join('order_process_log', 'order_process_log.opl_order_status_id', 'order_status.order_status_id')
				->whereIn('order_status.role_id', $role_ids)
				->where('order_process_log.opl_user_id', $user_id)
				->groupBy('order_process_log.opl_order_id')
				->pluck('order_process_log.opl_order_id')
				->toArray();
		}
	}

	/*************************
	 * Get user whose task status is completed
	 * updated on 19-JAN-2018
	 *************************/
	public function getOrderStageWithOrWithoutAmendment($order_id)
	{

		global $order, $report, $models, $invoice, $mail, $numbersToWord;

		$reportData = $order->getOrder($order_id);
		if (!empty($reportData->status) && $reportData->status == '7') {

			$orderSampleType  = !empty($reportData->order_sample_type) && in_array($reportData->order_sample_type, array('1', '2')) ? true : false;
			$amendedOrderData = DB::table('order_process_log')->where('order_process_log.opl_amend_status', '1')->where('order_process_log.opl_order_id', $order_id)->orderBy('order_process_log.opl_id', 'DESC')->limit(1)->first();
			$checkInvoice     =  DB::table('order_process_log')->join('invoice_hdr_detail', 'invoice_hdr_detail.order_id', '=', 'order_process_log.opl_order_id')->where('order_process_log.opl_order_id', '=', $order_id)->where('order_process_log.opl_order_status_id', '8')->where('order_process_log.opl_amend_status', '1')->where('invoice_hdr_detail.invoice_hdr_status', '1')->whereNotNull('order_process_log.opl_user_id')->first();

			if (!empty($amendedOrderData)) {
				if (empty($checkInvoice) && empty($orderSampleType)) {
					return array('7', '8');
				} else {
					return array('7', '9');
				}
			} else {
				return $orderSampleType ? array('7', '9') : array('7', '8');
			}
		}
		return array(0, 0);
	}

	/*************************
	 *Update Report Type On Report Generation
	 *Praveen Singh
	 *Date : 10-01-2018
	 *************************/
	function updateReportTypeOnReportGeneration($orderId, $reportType)
	{
		$reportDetailData = DB::table('order_report_details')->where('order_report_details.report_id', '=', $orderId)->first();
		return !empty($reportDetailData) && !empty($reportType) ? DB::table('order_report_details')->where('order_report_details.report_id', $orderId)->update(['order_report_details.report_type' => $reportType]) : false;
	}

	/*************************
	 *Get all parent product categories
	 *Date : 06-02-2018
	 *************************/
	public function getParentProductCategories()
	{
		$colArr =   array();
		$catData = DB::table('product_categories')->where('product_categories.parent_id', '0');
		if (empty($colArr)) {
			$data =  $catData->pluck('p_category_id')->toArray();
		} else {
			$data = $catData->select('p_category_id')->get();
		}
		return !empty($data) ? $data : array();
	}

	/*************************
	 *Checking Back Date Booking Allowed in Pharma Department
	 *Date : 07-02-2018
	 *************************/
	public function checkBackDateBookingAllowed($orderList)
	{
		$orderDate = strtotime(date(DATEFORMAT, strtotime($orderList->order_date)));
		$bookingDate = strtotime(date(DATEFORMAT, strtotime($orderList->booking_date)));
		return $orderDate != $bookingDate ? '1' : '0';
	}

	/*************************
	 *Checking Order Date And Report Data Validation
	 *Date : 06-02-2018
	 *************************/
	public function checkOrderDateAndReportDataValidation($orderId, $reportDate)
	{
		$orderData = DB::table('order_master')->where('order_master.order_id', '=', $orderId)->first();
		if (!empty($orderData->order_date)) {
			$orderDate  = strtotime(date(DATEFORMAT, strtotime($orderData->order_date)));
			$reportDate = strtotime(date(DATEFORMAT, strtotime($reportDate)));
			return $reportDate >= $orderDate ? '1' : '0';
		} else {
			return false;
		}
	}

	/****
	 ***** Check quality standard of report
	 *****/
	public function getStandardQualityStampOrNot($orderDetail)
	{
		$greenStandardQualityCustomers =  array('740', '750');
		$order_id = !empty($orderDetail->order_id) ? $orderDetail->order_id : '0';
		if (!empty($orderDetail->order_report_id) && $orderDetail->customer_type == '1' && $orderDetail->product_category_id == '2') {
			$reportDetail = DB::table('order_report_details')
				->join('order_report_note_remark_default', 'order_report_note_remark_default.remark_name', '=', 'order_report_details.remark_value')
				->whereIn('order_report_note_remark_default.is_display_stamp', [1, 2])
				->whereColumn('order_report_note_remark_default.remark_name', 'order_report_details.remark_value')
				->where('order_report_details.report_id', '=', $order_id)
				->where('order_report_note_remark_default.product_category_id', '=', $orderDetail->product_category_id)
				->select('order_report_note_remark_default.is_display_stamp')
				->first();
			if (!empty($reportDetail)) {
				$reportDetail->stampType =  in_array($orderDetail->customer_id, $greenStandardQualityCustomers) ?  true : false;
			}
			return $reportDetail;
		} else {
			return false;
		}
	}

	/*************************
	 *Saving Microbilogical Name other than Pharma Department
	 *Date : 02-05-2018
	 *Created By:Praveen Singh
	 *************************/
	public function updateMicroBiologicalName($postedData)
	{

		global $order, $models;

		if (!empty($postedData['report_id']) && !empty($postedData['product_category_id']) && $postedData['product_category_id'] != '2') {
			$isOrderBookingAmended	 = $order->isBookingOrderAmendedOrNot($postedData['report_id']);
			$orderData 			 = DB::table('order_master')->select('order_master.order_id', 'order_master.division_id')->where('order_master.order_id', '=', $postedData['report_id'])->first();
			$orderReportDetail           = DB::table('order_report_details')->where('order_report_details.report_id', '=', $postedData['report_id'])->whereNull('order_report_details.report_microbiological_name')->first();
			$hasMicrobiologicalEquipment = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $postedData['report_id'])->first();
			if (empty($isOrderBookingAmended)) {
				if (!empty($orderReportDetail) && !empty($hasMicrobiologicalEquipment)) {
					$microbiologistData = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', '15')->where('users.division_id', $orderData->division_id)->first();
					if (!empty($microbiologistData->name) && !empty($microbiologistData->user_signature)) {
						//$reportMicrobiologicalSign = strtolower(preg_replace('/[_]+/','_',preg_replace("/[^a-zA-Z]/", "_", $microbiologistData->name)).'.png');
						return DB::table('order_report_details')->where('order_report_details.report_id', $postedData['report_id'])->update(['order_report_details.report_microbiological_name' => $microbiologistData->name, 'order_report_details.report_microbiological_sign' => $microbiologistData->user_signature]);
					} else {
						return false;
					}
				}
			}
		}
		return true;
	}

	/*************************
	 *Description 	: Saving Microbilogical Name other than Pharma Department
	 *Created By		: Praveen Singh
	 *Created On 	: 10-June-2018
	 *Modified On 	: 17-Oct-2020
	 *Modified By	: Praveen Singh
	 *************************/
	public function updateMicroBiologicalName_v1($postedData)
	{

		global $order, $models;

		if (!empty($postedData['report_id']) && !empty($postedData['product_category_id']) && $postedData['product_category_id'] != '2') {

			$hasConfirmSIMicrobiologicalEquipment = !empty($postedData['equipment_type_id']) && in_array('22', array_values(array_unique($postedData['equipment_type_id']))) ? true : false;
			$orderData 			     	  = DB::table('order_master')->select('order_master.order_id', 'order_master.division_id', 'order_master.product_category_id')->where('order_master.order_id', '=', $postedData['report_id'])->first();
			$isOrderBookingAmended	     	  = $order->isBookingOrderAmendedOrNot($postedData['report_id']);
			$hasMicrobiologicalEquipment     	  = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $postedData['report_id'])->first();
			$orderReportMicrobiologicalExist 	  = DB::table('order_report_microbiological_dtl')->where('order_report_microbiological_dtl.report_id', $postedData['report_id'])->first();

			if (!empty($hasConfirmSIMicrobiologicalEquipment)) {
				if (!empty($orderData) && empty($isOrderBookingAmended) && !empty($hasMicrobiologicalEquipment) && empty($orderReportMicrobiologicalExist)) {
					$hasUserMicroEquipmentAllocated = !empty($postedData['si_user_id']) ? $order->hasUserMicroEquipmentAllocated($postedData) : '1';
					if (!empty($hasUserMicroEquipmentAllocated)) {
						$microbiologistObj = DB::table('users')
							->join('role_user', 'users.id', 'role_user.user_id')
							->join('users_equipment_detail', 'users_equipment_detail.user_id', 'users.id')
							->join('users_department_detail', 'users_department_detail.user_id', 'users.id')
							->join('department_product_categories_link', 'department_product_categories_link.department_id', 'users_department_detail.department_id')
							->where('role_user.role_id', '15')
							->where('users.status', '=', '1')
							->where('users_equipment_detail.equipment_type_id', '22')
							->where('users.division_id', $orderData->division_id)
							->where('department_product_categories_link.product_category_id', $orderData->product_category_id)
							->select('users.*');
						//In case of Session Incharge
						if (!empty($postedData['si_user_id'])) {
							$microbiologistObj->where('users.id', $postedData['si_user_id']);
						}
						$microbiologistData = $microbiologistObj->orderBy('users.id', 'DESC')->first();
						if (empty($microbiologistData->name)) {
							return array('0', config('messages.message.microbiologistRightErrorMsg'));       //No Micro-Biological assign for this order 
						} else if (empty($microbiologistData->user_signature)) {
							return array('0', config('messages.message.sectionInchargeSignErrorMsg'));       //Please upload the Micro-Biological User Signature 
						} else {
							if (empty($orderReportMicrobiologicalExist->ormbd_id)) {
								$dataSave = array();
								$dataSave['report_id'] 			 		 = trim($postedData['report_id']);
								$dataSave['user_id'] 			 		 = trim($microbiologistData->id);
								$dataSave['report_microbiological_name'] = trim($microbiologistData->name);
								$dataSave['report_microbiological_sign'] = trim($microbiologistData->user_signature);
								$status 				 				 = DB::table('order_report_microbiological_dtl')->insertGetId($dataSave);
								return array($status, 'Success');
							}
						}
					} else {
						return array('0', config('messages.message.microbiologistRightErrorMsg'));     		//Please assign the Micro-Biological Instrument
					}
				}
			}
		}
		return array('1', 'Success');
	}

	/*************************
	 *Saving Microbilogical Name other than Pharma Department
	 *Date : 02-05-2018
	 *Created By:Praveen Singh
	 *************************/
	public function updateSaveTestReportHeaderFooterContent($reportId)
	{

		global $order, $models;

		if (!empty($reportId)) {
			$orderData         = DB::table('order_master')->select('order_master.order_id', 'order_master.division_id', 'order_master.customer_id', 'order_master.product_category_id')->where('order_master.order_id', '=', $reportId)->first();
			$orderReportHeader = DB::table('order_report_details')->where('order_report_details.report_id', '=', $reportId)->whereNull('order_report_details.header_content')->first();
			$orderReportFooter = DB::table('order_report_details')->where('order_report_details.report_id', '=', $reportId)->whereNull('order_report_details.footer_content')->first();
			if (!empty($orderReportHeader) && !empty($orderReportFooter)) {
				list($header_content, $footer_content) = $order->getDynamicHeaderFooterTemplate('1', $orderData->division_id, $orderData->product_category_id);
				if ($header_content && $footer_content) {
					//For Pharma Department Only
					if (!empty($orderData->division_id) && !empty($orderData->product_category_id) && $orderData->division_id == '1' && $orderData->product_category_id == '2') {
						$headerTypetext = $order->getHeaderTypeTextbasedOnCustomerTypes($orderData->division_id, $orderData->product_category_id, $orderData->customer_id);
						$header_content = str_replace("PH_HEADER_TYPE_VAR", $headerTypetext, $header_content);
					}
					return DB::table('order_report_details')->where('order_report_details.report_id', $reportId)->update(['order_report_details.header_content' => $header_content, 'order_report_details.footer_content' => $footer_content]);
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
		return false;
	}

	/*************************
	 *Updating Report Reviewing Date
	 *Created on:12-June-2018
	 *Created By:Praveen Singh
	 *************************/
	function updateReportReviewingDate($formData, $reviewingDate)
	{
		global $order, $report, $models;

		if (!empty($formData['report_id']) && !empty($reviewingDate)) {
			$orderData 	= DB::table('order_master')->where('order_master.order_id', '=', $formData['report_id'])->first();
			$reportData = DB::table('order_report_details')->where('order_report_details.report_id', '=', $formData['report_id'])->first();
			if (!empty($orderData->order_no) && !empty($reportData->report_no)) {
				//CASE 1:Review Date Updated if Order is not amended
				//CASE 2:Review Date Updated if Order is amended and checkbox is checked
				if (!empty($order->isBookingOrderAmendedOrNot($formData['report_id']))) {
					$flag = !empty($formData['is_amended_no']) || !empty($reportData->is_amended_no) ? '1' : '0';
				} else {
					$flag = '1';
				}
				if ($flag) {
					return DB::table('order_report_details')->where('order_report_details.report_id', $formData['report_id'])->update(['order_report_details.reviewing_date' => $reviewingDate, 'order_report_details.reviewed_by' => USERID]);
				}
			}
		}
		return false;
	}

	/*************************
	 *Updating Report Finalizing Date Date
	 *Created on:12-June-2018
	 *Created By:Praveen Singh
	 *************************/
	function updateReportFinalizingDate($formData, $finalizingDate)
	{

		global $order, $report, $models;

		if (!empty($formData['order_id']) && !empty($finalizingDate)) {
			$orderData 	= DB::table('order_master')->where('order_master.order_id', '=', $formData['order_id'])->first();
			$reportData = DB::table('order_report_details')->where('order_report_details.report_id', '=', $formData['order_id'])->first();
			if (!empty($orderData->order_no) && !empty($reportData->report_no)) {
				//CASE 1:Finalizing Date Updated if Order is not amended
				//CASE 2:Finalizing Date Updated if Order is amended with prefix 'A'
				if (!empty($order->isBookingOrderAmendedOrNot($formData['order_id']))) {
					$flag = !empty($reportData->is_amended_no) ? '1' : '0';
				} else {
					$flag = '1';
				}
				if ($flag) {
					return DB::table('order_report_details')->where('order_report_details.report_id', $formData['order_id'])->update(['order_report_details.finalizing_date' => $finalizingDate, 'order_report_details.finalized_by' => USERID]);
				}
			}
		}
		return false;
	}

	/*************************
	 *Updating Report Approving Date Date
	 *Created on:12-June-2018
	 *Created By:Praveen Singh
	 *************************/
	function updateReportApprovingDate($reportId, $approvingDate)
	{

		global $order, $report, $models;

		if (!empty($reportId) && !empty($approvingDate)) {
			$orderData 	= DB::table('order_master')->where('order_master.order_id', '=', $reportId)->first();
			$reportData = DB::table('order_report_details')->where('order_report_details.report_id', '=', $reportId)->first();
			if (!empty($orderData->order_no) && !empty($reportData->report_no)) {
				//CASE 1:Approving Date Updated if Order is not amended
				//CASE 2:Approving Date Updated if Order is amended with prefix 'A'
				if (!empty($order->isBookingOrderAmendedOrNot($reportId))) {
					$flag = !empty($reportData->is_amended_no) ? '1' : '0';
				} else {
					$flag = '1';
				}
				if ($flag) {
					return DB::table('order_report_details')->where('order_report_details.report_id', $reportId)->update(['order_report_details.approving_date' => $approvingDate, 'order_report_details.approved_by' => USERID]);
				}
			}
		}
		return false;
	}

	/*************************
	 *Update last confirm date of the order by section incharge
	 *Created on:12-June-2018
	 *Created By:Praveen Singh
	 *************************/
	public function updateReportInchargeReviewingDate($order_id)
	{

		global $models, $order;

		$orderInchargeDtlStatus = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', '=', $order_id)->whereNull('order_incharge_dtl.oid_confirm_by')->where('order_incharge_dtl.oid_status', '=', '0')->count();
		$inchargeReviewingDate = DB::table('order_incharge_dtl')->where('order_id', '=', $order_id)->whereNotNull('order_incharge_dtl.oid_confirm_date')->select('order_incharge_dtl.oid_confirm_date')->orderBy('order_incharge_dtl.oid_confirm_date', 'DESC')->first();
		if (empty($orderInchargeDtlStatus) && !empty($inchargeReviewingDate->oid_confirm_date)) {
			//Updating oipd_status of order_incharge_process_dtl to 2
			DB::table('order_incharge_process_dtl')->where('oipd_order_id', $order_id)->update(['order_incharge_process_dtl.oipd_status' => '2']);

			//Updating incharge_reviewing_date in order Master
			return empty($order->isBookingOrderAmendedOrNot($order_id)) ? DB::table('order_master')->where('order_id', '=', $order_id)->update(['incharge_reviewing_date' => $inchargeReviewingDate->oid_confirm_date]) : true;
		} else {
			return false;
		}
	}

	/*************************
	 *Update last confirm date of the order by section incharge
	 *Created on:12-Sept-2019
	 *Created By:Praveen Singh
	 *************************/
	public function updateReportInchargeReviewingDateOnAmendment($order_id)
	{

		global $models, $order;

		$orderInchargeDtlStatus = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', '=', $order_id)->whereNull('order_incharge_dtl.oid_confirm_by')->where('order_incharge_dtl.oid_status', '=', '0')->count();
		$inchargeReviewingDate = DB::table('order_incharge_dtl')->where('order_id', '=', $order_id)->whereNotNull('order_incharge_dtl.oid_confirm_date')->select('order_incharge_dtl.oid_confirm_date')->orderBy('order_incharge_dtl.oid_confirm_date', 'DESC')->first();
		if (empty($orderInchargeDtlStatus) && !empty($inchargeReviewingDate->oid_confirm_date)) {
			//Updating oipd_status of order_incharge_process_dtl to 2
			DB::table('order_incharge_process_dtl')->where('oipd_order_id', $order_id)->update(['order_incharge_process_dtl.oipd_status' => '2']);

			//Updating incharge_reviewing_date in order Master | Getting is_amended_no Status from Report Detail
			$amendedReportDetail = $this->getOrderReportDetails($order_id);
			return !empty($order->isBookingOrderAmendedOrNot($order_id)) && !empty($amendedReportDetail->is_amended_no) ? DB::table('order_master')->where('order_id', '=', $order_id)->update(['incharge_reviewing_date' => $inchargeReviewingDate->oid_confirm_date]) : true;
		} else {
			return false;
		}
	}

	/*************************
	 *Reset Reviewing ,Finalizing and Approving Date On Need Modification
	 *Created on : 09-April-2019
	 *Created By: Praveen Singh
	 *************************/
	public function resetRevFinAppDateOnNeedModification($reportId)
	{
		return DB::table('order_report_details')->where('order_report_details.report_id', $reportId)->update(['order_report_details.reviewing_date' => NULL, 'order_report_details.reviewed_by' => NULL, 'order_report_details.finalizing_date' => NULL, 'order_report_details.finalized_by' => NULL, 'order_report_details.approving_date' => NULL, 'order_report_details.approved_by' => NULL]);
	}

	/*************************
	 *quality Stamp On Web View
	 *Created on:25-June-2018
	 *Created By:
	 *************************/
	function qualityStampOnWebView($orderList)
	{
		$checkReportQuality = $this->getStandardQualityStampOrNot($orderList);
		if (!empty($checkReportQuality)) {
			foreach ($checkReportQuality as $key => $value) {
				$orderList->$key = $value;
			}
		}
		return $orderList;
	}

	/****************************************************
	 *get Summary Statistics Log
	 *Created on:17-Aug-2018
	 *Created By:Praveen Singh
	 *****************************************************/
	public function summaryStatistics($role_ids, $userWiseRoles, $user_id, $division_id, $department_ids, $equipment_type_ids)
	{

		global $order, $report, $models;

		$returnData = array();

		if (empty($userWiseRoles)) {
			$userWiseRolesData = DB::table('order_status')
				->join('roles', 'roles.id', '=', 'order_status.role_id')
				->select('order_status.*', 'roles.name as role_name')
				->where('order_status.status', '1')
				->whereNotIn('order_status.order_status_id', array('1', '4', '10', '11'))
				->get();
			if (!empty($userWiseRolesData)) {
				foreach ($userWiseRolesData as $value) {
					if ($value->order_status_id == '9') { 		//Dispatcher
						$dispatchingOrderDaily = DB::table('order_master')
							->select('order_master.order_id', 'order_master.order_no')
							->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
							->join('customer_billing_types', 'customer_billing_types.billing_type_id', 'order_master.billing_type_id')
							->leftJoin('order_dispatch_dtl', function ($join) {
								$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
								$join->where('order_dispatch_dtl.amend_status', '0');
							})
							->whereIn('order_master.status', array('8', $value->order_status_id))
							->where('order_master.billing_type_id', '=', '1')
							->whereNull('order_dispatch_dtl.order_id')
							->count();

						$dispatchingOrderMonthly = DB::table('order_master')
							->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
							->join('customer_billing_types', 'customer_billing_types.billing_type_id', 'order_master.billing_type_id')
							->leftJoin('order_dispatch_dtl', function ($join) {
								$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
								$join->where('order_dispatch_dtl.amend_status', '0');
							})
							->where('customer_master.billing_type', '=', '4')
							->whereIn('order_master.status', array('8', $value->order_status_id))
							->whereNull('order_dispatch_dtl.order_id')
							->count();

						$returnData[$value->order_status_name . '(Daily)']   = $dispatchingOrderDaily;
						$returnData[$value->order_status_name . '(Monthly)'] = $dispatchingOrderMonthly;
					} else {
						$returnData[$value->order_status_name] = DB::table('order_master')->where('order_master.status', $value->order_status_id)->where('order_master.status', '<>', '10')->count();
					}
				}
			}
		} else {
			$userWiseRolesData = DB::table('order_status')
				->join('roles', 'roles.id', '=', 'order_status.role_id')
				->select('order_status.*', 'roles.name as role_name')
				->where('order_status.status', '1')
				->whereNotIn('order_status.order_status_id', array('1', '10', '11'))
				->whereIn('order_status.order_status_id', $userWiseRoles)
				->get();
			if (!empty($userWiseRolesData)) {
				foreach ($userWiseRolesData as $value) {
					if ($value->order_status_id == '3') { 		//For Tester
						$returnData[$value->order_status_name] = count(DB::table('order_master')
							->join('schedulings', 'schedulings.order_id', 'order_master.order_id')
							->where('schedulings.employee_id', '=', $user_id)
							->where('schedulings.status', '<>', '3')
							->where('order_master.status', $value->order_status_id)
							->where('order_master.division_id', $division_id)
							->whereIn('order_master.product_category_id', $department_ids)
							->groupBy('order_master.order_id')
							->get());
					} else if ($value->order_status_id == '4') { 		//For Section Incharge
						$returnData[$value->order_status_name] = DB::table('order_master')
							->whereIn('order_master.status', array('3', '4'))
							->where('order_master.division_id', $division_id)
							->whereIn('order_master.product_category_id', $department_ids)
							->whereIn('order_master.order_id', $report->getSectionInchargeOrderDetail($user_id, $equipment_type_ids))
							->count();
					} else if ($value->order_status_id == '9') { //Dispatcher

						$dispatchingOrderDaily = DB::table('order_master')
							->select('order_master.order_id', 'order_master.order_no')
							->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
							->join('customer_billing_types', 'customer_billing_types.billing_type_id', 'order_master.billing_type_id')
							->leftJoin('order_dispatch_dtl', function ($join) {
								$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
								$join->where('order_dispatch_dtl.amend_status', '0');
							})
							->where('order_master.billing_type_id', '=', '1')
							->whereIn('order_master.status', array('8', $value->order_status_id))
							->where('order_master.division_id', $division_id)
							->whereIn('order_master.product_category_id', $department_ids)
							->whereNull('order_dispatch_dtl.order_id')
							->count();

						$dispatchingOrderMonthly = DB::table('order_master')
							->select('order_master.order_id', 'order_master.order_no')
							->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
							->join('customer_billing_types', 'customer_billing_types.billing_type_id', 'order_master.billing_type_id')
							->leftJoin('order_dispatch_dtl', function ($join) {
								$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
								$join->where('order_dispatch_dtl.amend_status', '0');
							})
							->where('customer_master.billing_type', '=', '4')
							->whereIn('order_master.status', array('8', $value->order_status_id))
							->where('order_master.division_id', $division_id)
							->whereIn('order_master.product_category_id', $department_ids)
							->whereNull('order_dispatch_dtl.order_id')
							->count();

						$returnData[$value->order_status_name . '(Daily)']   = $dispatchingOrderDaily;
						$returnData[$value->order_status_name . '(Monthly)'] = $dispatchingOrderMonthly;
					} else {
						$returnData[$value->order_status_name] = DB::table('order_master')->where('order_master.division_id', $division_id)->whereIn('order_master.product_category_id', $department_ids)->where('order_master.status', $value->order_status_id)->where('order_master.status', '<>', '10')->count();
					}
				}
			}
		}
		return $returnData;
	}

	/***
	 *** check current status of incharge order
	 *** if 1 do not show coonfirm button again
	 *** if 0 show confirm button 
	 ***/
	public function currentInchargeOrderStatus($order_id, $user_id)
	{
		$confirmStatus = '';
		$inchargeStatusObj = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', '=', $order_id);
		defined('IS_SECTION_INCHARGE') && IS_SECTION_INCHARGE ? $inchargeStatusObj->where('order_incharge_dtl.oid_employee_id', '=', $user_id) : '';
		$inchargeStatus = $inchargeStatusObj->where('order_incharge_dtl.oid_status', '0')->whereNull('order_incharge_dtl.oid_confirm_by')->first();
		return !empty($inchargeStatus) ? '1' : '0';
	}

	/**
	 * Description : Updating Order Amended Detail
	 * Date : 16-July-2018
	 * Author : Praveen Singh
	 */
	function updateOrderAmendDetail($orderId, $currentOrderStage)
	{
		if (!empty($orderId) && !empty($currentOrderStage)) {

			//Updating Log Detail
			DB::table('order_process_log')->where('order_process_log.opl_order_id', '=', $orderId)->where('order_process_log.opl_order_status_id', '>', '4')->update(['order_process_log.opl_amended_by' => USERID, 'order_process_log.opl_amend_status' => '1']);

			//Updating Order Dispatch Detail	
			DB::table('order_dispatch_dtl')->where('order_id', '=', $orderId)->update(['amend_status' => '1']);

			//Saving Order Amend Detail
			$dataSave 			 	= array();
			$dataSave['oad_order_id'] 	 	= $orderId;
			$dataSave['oad_amended_stage'] 	= $currentOrderStage;
			$dataSave['oad_amended_date'] 	= CURRENTDATETIME;
			$dataSave['oad_amended_by'] 	= USERID;
			return !empty($dataSave) && DB::table('order_amended_dtl')->insert($dataSave) ? true : false;
		}
		return false;
	}

	/*************************
	 *Description : Saving od data in order_incharge_process_dtl table
	 *Created on:02-Aug-2018
	 *Created By:Praveen Singh
	 *************************/
	function getErrorParametersDetail($orderId)
	{

		global $order, $models;

		$error_parameter_array = array();

		$error_parameter_ids = DB::table('order_process_log')
			->where('order_process_log.opl_order_id', $orderId)
			->whereNotNull('order_process_log.error_parameter_ids')
			->select('order_process_log.error_parameter_ids', 'order_process_log.note')
			->where('order_process_log.opl_order_status_id', '3')
			->get();
		if (!empty($error_parameter_ids)) {
			foreach ($error_parameter_ids as $key => $error_parameter_str) {
				$error_parameter_ids_arr = explode(',', $error_parameter_str->error_parameter_ids);
				foreach ($error_parameter_ids_arr as $key => $error_parameter) {
					$error_parameter_array[$error_parameter] = trim(strip_tags($error_parameter_str->note));
				}
			}
		}
		return $error_parameter_array;
	}

	/**********************************************************
	 *Description : Reset Section Incharge Visibility
	 *Created on:02-Aug-2018
	 *Created By:Praveen Singh
	 **********************************************************/
	function resetOrderSectionInchargeDetail($orderId, $analysisArr)
	{
		if (!empty($orderId) && !empty($analysisArr) && is_array($analysisArr)) {
			$equipmentTypeIds = DB::table('order_parameters_detail')
				->where('order_id', $orderId)
				->whereNotNull('order_parameters_detail.equipment_type_id')
				->whereIn('order_parameters_detail.analysis_id', $analysisArr)
				->groupBy('order_parameters_detail.equipment_type_id')
				->pluck('order_parameters_detail.equipment_type_id')
				->all();
			$dataSave = array('order_incharge_dtl.oid_confirm_date' => NULL, 'order_incharge_dtl.oid_confirm_by' => NULL, 'order_incharge_dtl.oid_status' => '0');
			return !empty($equipmentTypeIds) ? DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', '=', $orderId)->whereIn('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeIds)->update($dataSave) : false;
		}
	}

	/*********************************************************
	 *Description : Saving od data in order_incharge_process_dtl table
	 *Created on:02-Aug-2018
	 *Created By:Praveen Singh
	 ************************************************************/
	function updateOrderInchargeProcessDetail($formData)
	{
		if (!empty($formData['report_id']) && !empty($formData['analysis_id']) && is_array($formData['analysis_id'])) {
			if (!empty($formData['oipd_incharge_id']) && defined('IS_SECTION_INCHARGE') && !empty(IS_SECTION_INCHARGE)) { 			//In case of Section Incharge
				foreach ($formData['analysis_id'] as $key => $analysisId) {
					$dataSave = array();
					$dataSave['oipd_order_id'] 		= $formData['report_id'];
					$dataSave['oipd_analysis_id'] 	= $analysisId;
					$dataSave['oipd_incharge_id']	= $formData['oipd_incharge_id'];
					$dataSave['oipd_user_id'] 		= USERID;
					$dataSave['oipd_date'] 		= CURRENTDATETIME;
					$dataSave['oipd_status'] 		= '1';
					DB::table('order_incharge_process_dtl')->insertGetId($dataSave);
				}
				//Updating Order Incharge Detail
				$dataSave 	  = array('order_incharge_dtl.oid_confirm_date' => NULL, 'order_incharge_dtl.oid_confirm_by' => NULL, 'order_incharge_dtl.oid_status' => '0');
				$equipmentTypeIds = DB::table('order_parameters_detail')->where('order_id', $formData['report_id'])->whereNotNull('order_parameters_detail.equipment_type_id')->whereIn('order_parameters_detail.analysis_id', $formData['analysis_id'])->groupBy('order_parameters_detail.equipment_type_id')->pluck('order_parameters_detail.equipment_type_id')->all();
				$orderInchargeDtl = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', '=', $formData['report_id'])->where('order_incharge_dtl.oid_id', $formData['oipd_incharge_id'])->first();
				!empty($equipmentTypeIds) && !empty($orderInchargeDtl->oid_employee_id) ? DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', '=', $formData['report_id'])->where('order_incharge_dtl.oid_employee_id', $orderInchargeDtl->oid_employee_id)->whereIn('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeIds)->update($dataSave) : '';
				return true;
			} else { 							//In case of Administrator
				//Updating Order Incharge Detail
				return $this->resetOrderSectionInchargeDetail($formData['report_id'], $formData['analysis_id']);
			}
		}
	}

	/*************************
	 *Description : Saving od data in order_incharge_process_dtl table
	 *Created on:02-Aug-2018
	 *Created By:Praveen Singh
	 *************************/
	function updateNeedModifInOrderInchargeProcessDtl($orderId, $analysisIds)
	{
		$orderInchargeIdData = DB::table('order_incharge_process_dtl')->where('order_incharge_process_dtl.oipd_order_id', $orderId)->whereIn('order_incharge_process_dtl.oipd_analysis_id', $analysisIds)->where('order_incharge_process_dtl.oipd_status', '1')->get()->toArray();
		if (!empty($orderInchargeIdData)) {
			//Updating oipd_status of order_incharge_process_dtl to 2
			return DB::table('order_incharge_process_dtl')->where('order_incharge_process_dtl.oipd_order_id', $orderId)->whereIn('order_incharge_process_dtl.oipd_analysis_id', $analysisIds)->update(['order_incharge_process_dtl.oipd_status' => '2']);
		}
	}

	/*************************
	 *Description : Saving od data in order_incharge_process_dtl table
	 *Created on:02-Aug-2018
	 *Created By:Praveen Singh
	 *************************/
	function getSectionInchargeOrderDetail($user_id, $equipment_type_ids)
	{
		$returnData = array();
		$orderDetail = DB::table('order_incharge_dtl')->select('order_id', 'oid_equipment_type_id')->where('order_incharge_dtl.oid_status', '0')->where('order_incharge_dtl.oid_employee_id', '=', $user_id)->whereIn('order_incharge_dtl.oid_equipment_type_id', $equipment_type_ids)->get()->toArray();
		if (!empty($orderDetail)) {
			$equipment_type_ids = DB::table('order_incharge_dtl')->where('order_incharge_dtl.oid_status', '0')->where('order_incharge_dtl.oid_employee_id', '=', $user_id)->whereIn('order_incharge_dtl.oid_equipment_type_id', $equipment_type_ids)->groupBy('order_incharge_dtl.oid_equipment_type_id')->pluck('order_incharge_dtl.oid_equipment_type_id')->all();
			foreach ($orderDetail as $key => $values) {
				$checkUpdateOrderStatus = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', $values->order_id)->whereIn('order_parameters_detail.equipment_type_id', $equipment_type_ids)->whereNull('order_parameters_detail.test_result')->first();
				if (empty($checkUpdateOrderStatus)) $returnData[$values->order_id] = $values->order_id;
			}
		}
		//echo '<pre>';print_r($returnData);die;
		return array_values($returnData);
	}

	/****************************************************
	 *Description : Validate Confirm/NeedModification Action By Section Incharge
	 *Created on:17-Aug-2018
	 *Created By:Praveen Singh
	 *****************************************************/
	public function validateConfirmNeedModificationActionBySI($formData)
	{

		$equipmentTypeIdArr                 = !empty($formData['equipment_type_id']) ? array_unique($formData['equipment_type_id']) : array();
		$orderId 	    		    = !empty($formData['report_id']) ? trim($formData['report_id']) : '0';
		$orderSectionInchargeIds 	    = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', $orderId)->whereIn('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeIdArr)->pluck('order_incharge_dtl.oid_id')->all();
		$needModificationActionByAnySIOrNot = DB::table('order_incharge_process_dtl')->where('order_incharge_process_dtl.oipd_order_id', $orderId)->whereIn('order_incharge_process_dtl.oipd_incharge_id', $orderSectionInchargeIds)->where('order_incharge_process_dtl.oipd_status', '1')->first();
		$confirmActionByAnySIOrNot 	    = DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', $orderId)->whereIn('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeIdArr)->whereNull('order_incharge_dtl.oid_confirm_by')->where('order_incharge_dtl.oid_status', '=', '0')->pluck('order_incharge_dtl.oid_id')->all();

		if (empty($needModificationActionByAnySIOrNot) && !empty($confirmActionByAnySIOrNot)) {
			return true;
		} else if (empty($needModificationActionByAnySIOrNot) && empty($confirmActionByAnySIOrNot)) {
			return true;
		} else {
			return false;
		}
	}

	/****************************************************
	 *Description : Get Order Equipment Incharge Detail
	 *Created on:17-Aug-2018
	 *Created By:Praveen Singh
	 *****************************************************/
	public function updateConfirmStatusOfSectionIncharge($formData, $user_id)
	{

		global $order, $report, $models;

		$dataSave = array();

		$equipmentTypeIdArr = !empty($formData['equipment_type_id']) ? array_unique($formData['equipment_type_id']) : array();
		$orderId 	    = !empty($formData['report_id']) ? trim($formData['report_id']) : '0';

		if (!empty($orderId) && !empty($equipmentTypeIdArr) && is_array($equipmentTypeIdArr) && !empty($user_id)) {

			//Updating Confirm Status of Section Incharge wrto Equipment Detail
			$dataSave = array('order_incharge_dtl.oid_confirm_date' => CURRENTDATETIME, 'order_incharge_dtl.oid_confirm_by' => $user_id, 'order_incharge_dtl.oid_status' => '1');
			DB::table('order_incharge_dtl')->where('order_incharge_dtl.order_id', '=', $orderId)->whereNull('order_incharge_dtl.oid_confirm_by')->where('order_incharge_dtl.oid_status', '=', '0')->whereIn('order_incharge_dtl.oid_equipment_type_id', $equipmentTypeIdArr)->update($dataSave);

			//1 if atleast any one Section Incharge doesnot confirm the report
			//2 if all Section Incharge confirm the report
			return $order->hasOrderConfirmedByAllSectionIncharges($orderId);
		}
		return false;
	}

	/*************************
	 *Description : function to update smaple name
	 * on editing a report(on reviewer end)
	 *************************/
	public function updateSampleNameAliasByReviewer($orderId, $sampleDescriptionName)
	{

		$orderData 	     = DB::table('order_master')->where('order_id', '=', $orderId)->select('order_master.product_id', 'order_master.sample_description_id')->first();
		$productId 	     = !empty($orderData->product_id) ? $orderData->product_id : '0';
		$sampleDescriptionId = !empty($orderData->sample_description_id) ? $orderData->sample_description_id : '0';

		$sampleAliasData = DB::table('product_master_alias')->where('product_master_alias.product_id', '=', $productId)->where('product_master_alias.c_product_name', '=', trim($sampleDescriptionName))->first();
		if (!empty($sampleAliasData)) {
			return $sampleAliasData->c_product_id;
		} else {
			$dataSave			  = array();
			$dataSave['c_product_name']   = trim($sampleDescriptionName);
			$dataSave['product_id'] 	  = $productId;
			$dataSave['created_by'] 	  = USERID;
			$dataSave['view_type'] 	  = '1';
			$dataSave['c_product_status'] = '1';
			return DB::table('product_master_alias')->insertGetId($dataSave);
		}
	}

	/*************************************
	 * Description : Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : Praveen Singh
	 ************************************/
	public function validateInvoivingRateAtReviewer($invoicingArray)
	{

		global $order, $models;

		$order_id 	   	= !empty($invoicingArray['order_id']) ? $invoicingArray['order_id'] : '0';
		$sample_description_id  = !empty($invoicingArray['sample_description_id']) ? $invoicingArray['sample_description_id'] : '0';
		$orderData    	   	= DB::table('order_master')->where('order_master.order_id', '=', $order_id)->first();
		$customer_id       	= !empty($orderData->customer_id) ? $orderData->customer_id : '0';
		$customer_city       	= !empty($orderData->customer_city) ? $orderData->customer_city : '0';
		$invoicing_type_id 	= !empty($orderData->invoicing_type_id) ? $orderData->invoicing_type_id : '0';
		$division_id          	= !empty($orderData->division_id) ? $orderData->division_id : '0';
		$product_category_id  	= !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';
		$order_sample_type  	= !empty($orderData->order_sample_type) ? $orderData->order_sample_type : '0';

		if ($invoicing_type_id == '3') {		//Customer Wise Product or Fixed rate party

			if (!empty($order_sample_type)) {
				return true;
			} else {
				//In case of fixed Rate Party
				$invoicingData = DB::table('customer_invoicing_rates')
					->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
					->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
					->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
					->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
					->whereNull('customer_invoicing_rates.cir_c_product_id')
					->first();

				//If Product ID is not null,then Customer Wise Product
				if (empty($invoicingData)) {
					$invoicingData = DB::table('customer_invoicing_rates')
						->where('customer_invoicing_rates.invoicing_type_id', '=', $invoicing_type_id)
						->where('customer_invoicing_rates.cir_division_id', '=', $division_id)
						->where('customer_invoicing_rates.cir_product_category_id', '=', $product_category_id)
						->where('customer_invoicing_rates.cir_customer_id', '=', $customer_id)
						->where('customer_invoicing_rates.cir_city_id', '=', $customer_city)
						->where('customer_invoicing_rates.cir_c_product_id', '=', $sample_description_id)
						->first();
					return !empty($invoicingData->invoicing_rate) ? true : false;
				} else {
					return true;
				}
			}
		} else {
			return true;
		}
	}

	/**********************************************
	 * Description : Update parameter details of parameters having no equipment type on reviewer-end 
	 * Created by :Ruby
	 * Created by :27-Aug-2018
	 **********************************************/
	public function updateOrderParameterDataByReviewer($orderParameterDetailData)
	{
		$flag = array();
		if (!empty($orderParameterDetailData)) {
			foreach ($orderParameterDetailData as $analysis_id => $parameterData) {
				$analysis_id         = !empty($analysis_id) ? str_replace("'", "", $analysis_id) : '0';
				$flag[$analysis_id]  = DB::table('order_parameters_detail')->where('order_parameters_detail.analysis_id', $analysis_id)->update(['order_parameters_detail.test_result' => $parameterData]);
			}
		}
		return $flag;
	}

	/**************************************************************
	 * Description : generate Update NABL Code Number
	 * Created by :Praveen Singh
	 * Created by :30-Aug-2018
	 ***************************************************************/
	public function __generateNablCodeNumber($orderId)
	{

		$orderData    	      = DB::table('order_master')->where('order_master.order_id', '=', $orderId)->first();
		$reportData    	      = $this->getOrderReportDetails($orderId);
		$division_id          = !empty($orderData->division_id) ? $orderData->division_id : '0';
		$product_category_id  = !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';

		//1-6 : Integration of Accreditation Cerificate Number according to Branch.
		$accreditationCertiData    = DB::table('order_accreditation_certificate_master')->where('order_accreditation_certificate_master.oac_division_id', '=', $division_id)->where('order_accreditation_certificate_master.oac_status', '1')->first();
		$accreditationCerificateNo = !empty($accreditationCertiData->oac_name) ? trim($accreditationCertiData->oac_name) : '';

		//7-8 : Integration of financial year(Report Approving Year) like '18'
		$financialYear	= !empty($reportData->approving_date) ? date('y', strtotime($reportData->approving_date)) : date('y');

		//9 : Integration of multilocation code '0' for main location and '1','2','3'.. for sub-location.
		$multilocationCode = isset($accreditationCertiData->oac_multi_location_lab_value) && strlen($accreditationCertiData->oac_multi_location_lab_value) ? trim($accreditationCertiData->oac_multi_location_lab_value) : '0';

		//10-17 : Integration of 8 digit code according to branch wise.
		$maxNablCodeData = DB::table('order_report_details')->join('order_master', 'order_report_details.report_id', 'order_master.order_id')->select('order_report_details.order_report_id', 'order_report_details.nabl_no')->where('order_master.division_id', $division_id)->orderBy('order_report_details.nabl_no', 'DESC')->limit(1)->first();
		$maxSerialNo 	 = !empty($maxNablCodeData->nabl_no) ? substr($maxNablCodeData->nabl_no, 9, 8) + 1 : '00000001';
		$maxSerialNo 	 = $maxSerialNo != '999999999' ? str_pad($maxSerialNo, 8, '0', STR_PAD_LEFT) : '00000001';

		//18 : Integration of 'F'/'P'.F if all Test Parmeter of an order has NABL Scope defined else for mix 'P'.			
		$finalNablScopeDigit = $this->__getFullyPartialNullNablScopeReport($orderId);

		return $accreditationCerificateNo . $financialYear . $multilocationCode . $maxSerialNo . $finalNablScopeDigit;
	}

	/**************************************************************
	 * Description : generate Update NABL Code Number
	 * Created by :Praveen Singh
	 * Created by :30-Aug-2018
	 * Modified by :Praveen Singh
	 * Modified by :28-June-2019
	 ***************************************************************/
	public function __generateNablCodeNumber_v1($orderId, $arrayOption = array())
	{

		$orderData    	      		= DB::table('order_master')->where('order_master.order_id', '=', $orderId)->first();
		$reportData    	      		= $this->getOrderReportDetails($orderId);
		$approvingYear    		= !empty($reportData->approving_date) ? date('Y', strtotime($reportData->approving_date)) : date('Y');
		$division_id          		= !empty($orderData->division_id) ? $orderData->division_id : '0';
		$product_category_id  		= !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';
		$incMaxSerialNo			= !empty($arrayOption['incMaxSerialNo']) ? $arrayOption['incMaxSerialNo'] : '0';

		//1-6 : Integration of Accreditation Cerificate Number according to Branch.
		$accreditationCertiData    	= DB::table('order_accreditation_certificate_master')->where('order_accreditation_certificate_master.oac_division_id', '=', $division_id)->where('order_accreditation_certificate_master.oac_product_category_id', $product_category_id)->where('order_accreditation_certificate_master.oac_status', '1')->first();
		$accreditationCerificateNo 	= !empty($accreditationCertiData->oac_name) ? trim($accreditationCertiData->oac_name) : '';

		//7-8 : Integration of financial year(Report Approving Year) like '18'
		$financialYear			= !empty($reportData->approving_date) ? date('y', strtotime($reportData->approving_date)) : date('y');

		//9 : Integration of multilocation code '0' for main location and '1','2','3'.. for sub-location.
		$multilocationCode 		= isset($accreditationCertiData->oac_multi_location_lab_value) && strlen($accreditationCertiData->oac_multi_location_lab_value) ? trim($accreditationCertiData->oac_multi_location_lab_value) : '0';

		//10-17 : Integration of 8 digit code according to branch wise || Recursive Validation
		if (!empty($incMaxSerialNo)) {
			$maxSerialNo	= $incMaxSerialNo != '999999999' ? str_pad($incMaxSerialNo, 8, '0', STR_PAD_LEFT) : '00000001';
		} else {
			$maxNablCodeData 	= DB::table('order_report_details')->join('order_master', 'order_report_details.report_id', 'order_master.order_id')->select('order_report_details.order_report_id', 'order_report_details.nabl_no', 'order_report_details.nabl_multi_location_lab_value')->where('order_report_details.nabl_multi_location_lab_value', $multilocationCode)->whereYear('order_report_details.approving_date', $approvingYear)->where('order_master.division_id', $division_id)->whereNotNull('order_report_details.nabl_no')->orderBy('order_report_details.nabl_no', 'DESC')->limit(1)->first();
			$maxSerialNo 	= !empty($maxNablCodeData->nabl_no) ? substr($maxNablCodeData->nabl_no, 9, 8) + 1 : '00000001';
			$maxSerialNo	= $maxSerialNo != '999999999' ? str_pad($maxSerialNo, 8, '0', STR_PAD_LEFT) : '00000001';
		}

		//18 : Integration of 'F'/'P'.F if all Test Parmeter of an order has NABL Scope defined else for mix 'P'.			
		$finalNablScopeDigit 		= $this->__getFullyPartialNullNablScopeReport($orderId, $arrayOption);

		//Generated NABL Number
		$nablNumber 			= $accreditationCerificateNo . $financialYear . $multilocationCode . $maxSerialNo . $finalNablScopeDigit;

		//Validating Uniqueness of NABL Number
		$isNablNoAlreadyExist		= DB::table('order_report_details')->where('order_report_details.nabl_no', '=', $nablNumber)->first();
		if (empty($isNablNoAlreadyExist)) {
			return $nablNumber; 
		} else {
            
			$arrayOption['incMaxSerialNo'] = round($maxSerialNo + 1);
           
			return $this->__generateNablCodeNumber_v1($orderId, $arrayOption);
		}
	}

	/**************************************************************
	 * Description : Getting NABL Multi Location Lab Value
	 * Created by :Praveen Singh
	 * Created by :28-June-2019
	 ***************************************************************/
	public function __getNablMultiLocationLabValue($orderId)
	{
		$orderData    	        = DB::table('order_master')->where('order_master.order_id', '=', $orderId)->first();
		$division_id            = !empty($orderData->division_id) ? $orderData->division_id : '0';
		$product_category_id    = !empty($orderData->product_category_id) ? $orderData->product_category_id : '0';
		$accreditationCertiData = DB::table('order_accreditation_certificate_master')->where('order_accreditation_certificate_master.oac_division_id', '=', $division_id)->where('order_accreditation_certificate_master.oac_product_category_id', $product_category_id)->where('order_accreditation_certificate_master.oac_status', '1')->first();
		return isset($accreditationCertiData->oac_multi_location_lab_value) && strlen($accreditationCertiData->oac_multi_location_lab_value) ? trim($accreditationCertiData->oac_multi_location_lab_value) : '0';
	}

	/****************************************************************
	 * Description : Getting Fully/Partial/Null Order NABL Scope
	 * Created by :Praveen Singh
	 * Created by :27-Sept-2018
	 ****************************************************************/
	public function __getFullyPartialNullNablScopeReport($orderId, $arrayOption = array())
	{

		$finalScopeDigit = '';

		//System will not considered standard parameter(like Reference to protocol,Description etc.) in the calculation of "F"  & "P".
		//20 : Integration of 'F'/'P'.F if all Test Parmeter of an order has NABL Scope defined else for mix 'P'.
		$parameterOptionType  = !empty($arrayOption['reportWithRightLogo']) ? $arrayOption['reportWithRightLogo'] : '0';
		$orderParameterDetail = DB::table('order_parameters_detail')
			->whereNotNull('order_parameters_detail.equipment_type_id')
			->whereNotNull('order_parameters_detail.order_parameter_nabl_scope')
			->where('order_parameters_detail.order_id', '=', $orderId)
			->when(!empty($arrayOption['nabl_report_generation_type']) && !empty($parameterOptionType) && in_array($parameterOptionType, array('7', '16')), function ($query) {
				return $query->where('order_parameters_detail.order_parameter_nabl_scope', '=', '1');
			})
			->pluck('order_parameters_detail.order_parameter_nabl_scope', 'order_parameters_detail.test_parameter_id')
			->all();
		if (in_array('1', $orderParameterDetail) && in_array('0', $orderParameterDetail)) {
			$finalScopeDigit = 'P';
		} else if (in_array('1', $orderParameterDetail) && !in_array('0', $orderParameterDetail)) {
			$finalScopeDigit = 'F';
		}
		return $finalScopeDigit;
	}

	/****************************************************************
	 * Description : Getting Fully/Partial/Null Order NABL Scope
	 * Created by :Praveen Singh
	 * Created by :27-Sept-2018
	 ****************************************************************/
	public function __validateNablCodeGenerationAppliciability($orderId, $nablCodeActivationDate)
	{
		return DB::table('order_report_details')->whereNotNull('order_report_details.finalizing_date')->whereDate('order_report_details.finalizing_date', '>=', date('Y-m-d', strtotime($nablCodeActivationDate)))->where('order_report_details.report_id', $orderId)->first();
	}

	/****************************************************************
	 * Description : Generation and Updatation of NABL Code Number
	 * Created by :Praveen Singh
	 * Created by :30-Aug-2018
	 ****************************************************************/
	public function updateGenerateNablCodeNoInReport($orderId, $arrayOption = array())
	{

		global $order, $models;

		//To generate a NABL Code below cases must be staisfy
		//CASE 1 : Order Status must be in Approving Stage
		//CASE 2 : Report No must be generated
		//CASE 3 : NABL No. sholud be blank
		//CASE 4 : NABL Scope must be 'F : Fully || P : Partial'

		$nablCodeActivationDate	= defined('NABL_CODE_ACTIVATION_DATE') ? trim(NABL_CODE_ACTIVATION_DATE) : '2020-07-15';
		$reportGeneratibility   = $this->__validateNablCodeGenerationAppliciability($orderId, $nablCodeActivationDate);
		if (!empty($reportGeneratibility)) {
			$nablCodeLimit = defined('NABL_CODE_GENERATION_LIMIT') ? trim(NABL_CODE_GENERATION_LIMIT) : '18';
			$orderData	   = $order->getOrderDetail($orderId);
			$reportData	   = $this->getOrderReportDetails($orderId);
			if (empty($this->checkBackDateBookingAllowed($orderData)) && !empty($reportData->report_no) && empty($reportData->nabl_no)) {
				$nablScope            = $this->__getFullyPartialNullNablScopeReport($orderId, $arrayOption);
				$nablNo    	      	  = !empty($nablScope) && $nablScope == 'F' ? $this->__generateNablCodeNumber_v1($orderId, $arrayOption) : '';
				
				$nablMultiLocLabValue = !empty($nablScope) && $nablScope == 'F' ? $this->__getNablMultiLocationLabValue($orderId) : '';
				return !empty($nablNo) && strlen($nablNo) == $nablCodeLimit ? DB::table('order_report_details')->where('order_report_details.report_id', $orderId)->update(['order_report_details.nabl_multi_location_lab_value' => $nablMultiLocLabValue, 'order_report_details.nabl_no' => $nablNo]) : false;
			}
		}
		return true;
	}

	/****************************************************************
	 * Description : Has Order Applicable For Nabl Scope
	 * Created by :Praveen Singh
	 * Created by :07-Sept-2018
	 ****************************************************************/
	public function hasOrderApplicableForNablScope($orderId)
	{

		$nablCodeLimit	 = defined('NABL_CODE_GENERATION_LIMIT') ? trim(NABL_CODE_GENERATION_LIMIT) : '18';
		$savedNableUlrNo = DB::table('order_report_details')->where('order_report_details.report_id', $orderId)->whereNotNull('order_report_details.nabl_no')->first();

		if (!empty($savedNableUlrNo)) {
			return strlen($savedNableUlrNo->nabl_no) == $nablCodeLimit ? substr($savedNableUlrNo->nabl_no, -1) : '2';	//Display the Right Log Checkbox
		} else {
			$nablScopeDigit = $this->__getFullyPartialNullNablScopeReport($orderId);
			if (!empty($nablScopeDigit)) {
				$unSavedNableUlrNo = $this->__generateNablCodeNumber_v1($orderId);
				if (strlen($unSavedNableUlrNo) == $nablCodeLimit) {
					return $nablScopeDigit;		//Display the Right Log Checkbox
				} else {
					return '3'; 			//Acceration Number missing,Alert the Message	
				}
			} else {
				return '2'; 					//NABL Scope Missing,then Hide the Right Log Checkbox	   
			}
		}
	}

	/****************************************************************
	 * Description : Has Order Applicable For Nabl Scope
	 * Created by  : Praveen Singh
	 * Created by  : 30-July-2020
	 ****************************************************************/
	public function hasOrderApplicableForNablScope_v1($orderId)
	{
		$nablScopeDigit = $this->__getFullyPartialNullNablScopeReport($orderId);
		if (!empty($nablScopeDigit)) {
			return $nablScopeDigit;		//Display the Right Log Checkbox
		} else {
			return '2'; 			//NABL Scope Missing,then Hide the Right Log Checkbox	   
		}
	}

	/****************************************************************
	 * Description : Has Order Applicable For Nabl Scope
	 * Created by :Praveen Singh
	 * Created by :07-Sept-2018
	 ****************************************************************/
	public function hasOrderApplicableForNablScopeAsteriskSymbol($orderId, $arrayOption = array())
	{

		$parameterOptionType    = !empty($arrayOption['reportWithRightLogo']) ? $arrayOption['reportWithRightLogo'] : '0';
		$nablCodeLimit	 	= defined('NABL_CODE_GENERATION_LIMIT') ? trim(NABL_CODE_GENERATION_LIMIT) : '18';
		$savedNableUlrNo 	= DB::table('order_report_details')->where('order_report_details.report_id', $orderId)->whereNotNull('order_report_details.nabl_no')->first();

		if (!empty($parameterOptionType) && in_array($parameterOptionType, array('7', '16'))) {
			if (!empty($savedNableUlrNo)) {
				return strlen($savedNableUlrNo->nabl_no) == $nablCodeLimit ? '1' : '0';	//Display the Right Log Checkbox
			} else {
				$nablScopeDigit = $this->__getFullyPartialNullNablScopeReport($orderId);
				if (!empty($nablScopeDigit)) {
					$unSavedNableUlrNo = $this->__generateNablCodeNumber_v1($orderId);
					if (strlen($unSavedNableUlrNo) == $nablCodeLimit) {
						return '1';				//Display the Right Log Checkbox
					} else {
						return '0'; 			//Acceration Number missing,Alert the Message	
					}
				} else {
					return '0'; 					//NABL Scope Missing,then Hide the Right Log Checkbox	   
				}
			}
		}
		return '0';
	}

	/****************************************************************
	 * Description : Has Order Applicable For Nabl Scope
	 * Created by :Praveen Singh
	 * Created by :15-July-2020
	 ****************************************************************/
	public function hasOrderApplicableForNablScopeAsteriskSymbolInView($orderId)
	{

		$nablCodeLimit	 	= defined('NABL_CODE_GENERATION_LIMIT') ? trim(NABL_CODE_GENERATION_LIMIT) : '18';
		$savedNableUlrNo 	= DB::table('order_report_details')->where('order_report_details.report_id', $orderId)->whereNotNull('order_report_details.nabl_no')->first();

		if (!empty($savedNableUlrNo)) {
			return strlen($savedNableUlrNo->nabl_no) == $nablCodeLimit ? '1' : '0';	//Display the Right Log Checkbox
		} else {
			$nablScopeDigit = $this->__getFullyPartialNullNablScopeReport($orderId);
			if (!empty($nablScopeDigit)) {
				$unSavedNableUlrNo = $this->__generateNablCodeNumber_v1($orderId);
				if (strlen($unSavedNableUlrNo) == $nablCodeLimit) {
					return '1';				//Display the Right Log Checkbox
				} else {
					return '0'; 			//Acceration Number missing,Alert the Message	
				}
			} else {
				return '0'; 					//NABL Scope Missing,then Hide the Right Log Checkbox	   
			}
		}
	}

	/*************************
	 *Description : Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
	 *Created By:Praveen Singh
	 *Created on:29-Nov-2018
	 *************************/
	function getNonNablAndOutsourceSymbolTR_v1($values, $nablTestParameterDetail)
	{

		global $order, $models;

		$values->non_nabl_category_symbol = $values->non_nabl_parameter_symbol = '';

		if (!empty($nablTestParameterDetail[$values->test_para_cat_id])) {
			if (empty(array_filter($nablTestParameterDetail[$values->test_para_cat_id]))) {	//If Category has only non-nabl scope parameter
				$values->non_nabl_category_symbol = '';
			} else if (empty($values->order_parameter_nabl_scope)) {				//If not then individual checking of Test Parameter Scope
				$values->non_nabl_parameter_symbol = '&#x2A;';
			}
		}
		//If any Test parameter is tested as Outsource Company
		if (!empty($values->test_performed_by) && $values->test_performed_by == '100') {
			$values->non_nabl_parameter_symbol = !empty($values->non_nabl_parameter_symbol) ? $values->non_nabl_parameter_symbol . '&#47;' . '&#x2A;&#x2A;' : '&#x2A;&#x2A;';
		}
	}

	/*************************
	 *Description : Getting Non NABL and Outsource Sample Symbol in Test Report(TR)
	 *Created By:RUBY
	 *Created on:20-Feb-2019
	 *Modified By :Praveen Singh
	 *Modified On : 22-Feb-2019
	 *************************/
	function getNonNablAndOutsourceSymbolTR($values, $nablTestParameterDetail, $orderNablScopeSymbol)
	{
		global $order, $models;

		$values->non_nabl_category_symbol = $values->non_nabl_parameter_symbol = '';

		if (!empty($orderNablScopeSymbol)) {
			if (empty($values->order_parameter_nabl_scope) && strlen($values->order_parameter_nabl_scope) > '0') {				//If not then individual checking of Test Parameter Scope
				$values->non_nabl_parameter_symbol = '&#x2A;';
			}
		}
		//If any Test parameter is tested as Outsource Company
		if (!empty($values->test_performed_by) && $values->test_performed_by == '100') {
			$values->non_nabl_parameter_symbol = !empty($values->non_nabl_parameter_symbol) ? $values->non_nabl_parameter_symbol . '&#47;' . '&#x2A;&#x2A;' : '&#x2A;&#x2A;';
		}
	}

	/****************************************************************
	 * Description : Getting Fully NABL Scope/Partial NABL Scope/Outsource Sample Scope
	 * Created by :Praveen Singh
	 * Created On :13-Dec-2018
	 ****************************************************************/
	public function getFullyPartialNablOutsourceSampleScope($orderId)
	{
		$orderParameterCount   = DB::table('order_parameters_detail')->whereNotNull('order_parameters_detail.equipment_type_id')->where('order_parameters_detail.order_id', '=', $orderId)->count();
		$partialNablScopeI     = DB::table('order_parameters_detail')->whereNotNull('order_parameters_detail.equipment_type_id')->where('order_parameters_detail.order_id', '=', $orderId)->whereNotNull('order_parameters_detail.order_parameter_nabl_scope')->where('order_parameters_detail.order_parameter_nabl_scope', '=', '1')->whereIn('order_parameters_detail.order_parameter_nabl_scope', array('0', '1'))->count();
		$partialNablScopeII    = DB::table('order_parameters_detail')->whereNotNull('order_parameters_detail.equipment_type_id')->where('order_parameters_detail.order_id', '=', $orderId)->whereNotNull('order_parameters_detail.order_parameter_nabl_scope')->where('order_parameters_detail.order_parameter_nabl_scope', '=', '0')->whereIn('order_parameters_detail.order_parameter_nabl_scope', array('0', '1'))->count();
		$outsourceSampleScope  = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $orderId)->where('order_parameters_detail.test_performed_by', '100')->count();
		if (($partialNablScopeI && $partialNablScopeII) || $outsourceSampleScope) {
			return true;
		} else {
			return false;
		}
	}

	/****************************************************************
	 * Description : Getting Fully NABL Scope/Partial NABL Scope/Outsource Sample Scope
	 * Created by :Praveen Singh
	 * Created On :13-Dec-2018
	 ****************************************************************/
	public function getFullyPartialNablOutsourceSampleScope_v1($orderId, $arrayOption = array())
	{
		$parameterOptionType   = !empty($arrayOption['reportWithRightLogo']) ? $arrayOption['reportWithRightLogo'] : '0';
		$orderParameterCount   = DB::table('order_parameters_detail')->whereNotNull('order_parameters_detail.equipment_type_id')->where('order_parameters_detail.order_id', '=', $orderId)->count();
		$partialNablScopeI     = DB::table('order_parameters_detail')->whereNotNull('order_parameters_detail.equipment_type_id')->where('order_parameters_detail.order_id', '=', $orderId)->whereNotNull('order_parameters_detail.order_parameter_nabl_scope')->where('order_parameters_detail.order_parameter_nabl_scope', '=', '1')->whereIn('order_parameters_detail.order_parameter_nabl_scope', array('0', '1'))->count();
		$partialNablScopeII    = DB::table('order_parameters_detail')->whereNotNull('order_parameters_detail.equipment_type_id')->where('order_parameters_detail.order_id', '=', $orderId)->whereNotNull('order_parameters_detail.order_parameter_nabl_scope')->where('order_parameters_detail.order_parameter_nabl_scope', '=', '0')->whereIn('order_parameters_detail.order_parameter_nabl_scope', array('0', '1'))->count();
		$outsourceSampleScope  = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $orderId)->where('order_parameters_detail.test_performed_by', '100')->count();
		if ((in_array($parameterOptionType, array('7', '16'))) && (($partialNablScopeI && $partialNablScopeII) || $outsourceSampleScope)) {
			return true;
		} else {
			return false;
		}
	}

	/****************************************************************
	 * Description : Getting Fully NABL Scope/Partial NABL Scope Sample Scope
	 * Created by :Praveen Singh
	 * Created On :13-Nov-2020
	 ****************************************************************/
	public function getFullyPartialNablSampleScope($orderId, $arrayOption = array())
	{
		$orderData	       = DB::table('order_master')->where('order_id', '=', $orderId)->first();
		$parameterOptionType   = !empty($arrayOption['reportWithRightLogo']) ? $arrayOption['reportWithRightLogo'] : '0';
		$partialNablScopeI     = DB::table('order_parameters_detail')->whereNotNull('order_parameters_detail.equipment_type_id')->where('order_parameters_detail.order_id', '=', $orderId)->whereNotNull('order_parameters_detail.order_parameter_nabl_scope')->where('order_parameters_detail.order_parameter_nabl_scope', '=', '1')->whereIn('order_parameters_detail.order_parameter_nabl_scope', array('0', '1'))->count();
		$partialNablScopeII    = DB::table('order_parameters_detail')->whereNotNull('order_parameters_detail.equipment_type_id')->where('order_parameters_detail.order_id', '=', $orderId)->whereNotNull('order_parameters_detail.order_parameter_nabl_scope')->where('order_parameters_detail.order_parameter_nabl_scope', '=', '0')->whereIn('order_parameters_detail.order_parameter_nabl_scope', array('0', '1'))->count();
		if ((in_array($parameterOptionType, array('7', '16'))) && (($partialNablScopeI && $partialNablScopeII))) {
			return !empty($orderData->division_id) && $orderData->division_id == '1' ? true : false;
		} else {
			return false;
		}
	}

	/****************************************************************
	 * Description : Getting Outsource Sample Scope
	 * Created by :Praveen Singh
	 * Created On :13-Nov-2020
	 ****************************************************************/
	public function getOutsourceSampleScope($orderId, $arrayOption = array())
	{
		$parameterOptionType   = !empty($arrayOption['reportWithRightLogo']) ? $arrayOption['reportWithRightLogo'] : '0';
		$outsourceSampleScope  = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $orderId)->where('order_parameters_detail.test_performed_by', '100')->count();
		if ($outsourceSampleScope) {
			return true;
		} else {
			return false;
		}
	}

	/*************************
	 *Description : Getting Inactive User Detail of an order
	 *Date : 13-June-2018
	 *Created By:Praveen Singh
	 *************************/
	public function getOrderInactiveAnalystDetail($orderId)
	{
		$returnData = DB::table('schedulings')->join('users', 'users.id', 'schedulings.employee_id')->where('schedulings.order_id', $orderId)->where('users.status', '2')->pluck('users.name', 'users.id')->all();
		return !empty($returnData) ? config('messages.message.inactiveAnalystErrorMsg') . implode(',', $returnData) : '';
	}

	/*************************
	 *Description : Getting Inactive User Detail of an order
	 *Date : 13-June-2018
	 *Created By:Praveen Singh
	 *************************/
	public function updateReportGroupNameAsPerDiscipline($postedData)
	{
		$insetflag = array();
		if (!empty($postedData['report_id']) && !empty($postedData['group_id'])) {
			$groupIdArray = array_filter($postedData['group_id']);
			if (!empty($groupIdArray)) {
				foreach ($postedData['group_id'] as $disciplineKey => $groupId) {
					$insetflag[] = !empty($groupId) ? DB::table('order_discipline_group_dtl')->where('order_discipline_group_dtl.order_id', '=', $postedData['report_id'])->where('order_discipline_group_dtl.discipline_id', '=', trim(str_replace("'", "", $disciplineKey)))->update(['order_discipline_group_dtl.group_id' => $groupId]) : false;
				}
			}
		}
		return !empty($insetflag) ? true : false;
	}

	/*************************
	 *Update Report Incharge Name and Signature
	 *Created on:20-Oct-2021
	 *Created By:Praveen Singh
	 *************************/
	public function updateTestReportInchargeNameSignature($order_id)
	{

		global $models, $order;

		$orderData = $order->getOrderDetail($order_id);
		if (!empty($orderData->order_id)) {
			$orsd_equipment_type_ids = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $orderData->order_id)->whereNotNull('order_parameters_detail.equipment_type_id')->pluck('order_parameters_detail.equipment_type_id', 'order_parameters_detail.equipment_type_id')->all();
			if (!empty($orsd_equipment_type_ids)) {
				$orsd_employee_id = DB::table('order_report_sign_dtls')
					->where('order_report_sign_dtls.orsd_division_id', $orderData->division_id)
					->where('order_report_sign_dtls.orsd_product_category_id', $orderData->product_category_id)
					->whereIn('order_report_sign_dtls.orsd_equipment_type_id', array_values($orsd_equipment_type_ids))
					->orderBy('order_report_sign_dtls.orsd_id', 'DESC')
					->pluck('order_report_sign_dtls.orsd_employee_id')
					->first();
				if (!empty($orsd_employee_id)) {
					$sectionInchargeDetail 						= DB::table('users')->where('users.id', $orsd_employee_id)->first();
					$order_report_middle_authorized_signs_exist = DB::table('order_report_middle_authorized_signs')->where('order_report_middle_authorized_signs.ormad_order_id', $orderData->order_id)->pluck('order_report_middle_authorized_signs.ormad_employee_id')->first();
					if (empty($order_report_middle_authorized_signs_exist)) {
						$dataSave = array();
						$dataSave['ormad_order_id'] 	 = trim($orderData->order_id);
						$dataSave['ormad_employee_id'] 	 = trim($orsd_employee_id);
						$dataSave['ormad_employee_name'] = trim($sectionInchargeDetail->name);
						$dataSave['ormad_employee_sign'] = trim($sectionInchargeDetail->user_signature);
						if (!empty($dataSave['ormad_employee_sign'])) {
							return DB::table('order_report_middle_authorized_signs')->insertGetId($dataSave);
						} else {
							return false;
						}
					}
				}
			}
		}
		return true;
	}

	/*************************
	 *Get Detector | Column | Instance | Running Time  Dropdown List
	 *Created By:Praveen Singh
	 *Created on:09-Nov-2021
	 *************************/
	public function getDetectorColumnInstanceRunningTimeDropdown($values, $orderList)
	{
		global $models, $order;

		//Detector Dropdown
		$values->detectorOptions = !empty($values->equipment_type_id) ? DB::table('detector_master')
			->where('detector_master.equipment_type_id', $values->equipment_type_id)
			->where('detector_master.product_category_id', $orderList->product_category_id)
			->where('detector_master.product_category_id', $orderList->product_category_id)
			->select('detector_master.detector_id as id', 'detector_master.detector_name as name')
			->orderBy('detector_master.detector_name', 'ASC')
			->get()
			->toArray() : [];

		//Column Dropdown
		$values->columnOptions = !empty($values->equipment_type_id) ? DB::table('column_master')
			->where('column_master.equipment_type_id', $values->equipment_type_id)
			->where('column_master.product_category_id', $orderList->product_category_id)
			->select('column_master.column_id as id', 'column_master.column_name as name')
			->orderBy('column_master.column_name', 'ASC')
			->get()
			->toArray() : [];

		//Instance Dropdown
		$values->instanceOptions = !empty($values->equipment_type_id) ? DB::table('instance_master')
			->where('instance_master.equipment_type_id', $values->equipment_type_id)
			->where('instance_master.product_category_id', $orderList->product_category_id)
			->select('instance_master.instance_id as id', 'instance_master.instance_name as name')
			->orderBy('instance_master.instance_name', 'ASC')
			->get()
			->toArray() : [];

		//Running Time Dropdown
		$values->runningtimeOptions = !empty($values->equipment_type_id) ? DB::table('customer_invoicing_running_time')
			//->where('customer_invoicing_running_time.equipment_type_id', $values->equipment_type_id)
			//->where('customer_invoicing_running_time.product_category_id', $orderList->product_category_id)
			->select('customer_invoicing_running_time.invoicing_running_time_id as id', 'customer_invoicing_running_time.invoicing_running_time as name')
			->orderBy('customer_invoicing_running_time.invoicing_running_time', 'ASC')
			->get()
			->toArray() : [];
	}

	/****************************************************************
	 * Description : Has Order Applicable For Analyst UT Setting
	 * Created by :Praveen Singh
	 * Created by :07-Nov-2021
	 ****************************************************************/
	public function hasApplicableForAnalystUiWindowSetting($order_id)
	{
		$hasAppForAnalystWindowSetting = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id', '=', $order_id)->whereNotNull('order_parameters_detail.oaws_ui_setting_id')->count();
		return $hasAppForAnalystWindowSetting ? '1' : '0';
	}

	/****************************************************************
	 * Description : Validating Non Required fields of Test Result Window
	 * Created by :Praveen Singh
	 * Created by :11-Nov-2021
	 ****************************************************************/
	public function validateNonRequiredFields($formData)
	{
		$flag = true;

		if (!empty($formData['oaws_ui_setting_id']) && !empty(count(array_filter($formData['oaws_ui_setting_id'])))) {

			$oaws_ui_setting_required_count 		= !empty($formData['oaws_ui_setting_id']) ? count(array_filter($formData['oaws_ui_setting_id'])) : '0';
			$oaws_detector_id_required_count 		= !empty($formData['detector_id']) ? count(array_filter($formData['detector_id'])) : '0';
			$oaws_column_id_required_count 			= !empty($formData['column_id']) ? count(array_filter($formData['column_id'])) : '0';
			$oaws_running_time_id_required_count 	= !empty($formData['running_time_id']) ? count(array_filter($formData['running_time_id'])) : '0';
			$oaws_instance_id_required_count 		= !empty($formData['instance_id']) ? count(array_filter($formData['instance_id'])) : '0';
			$oaws_no_of_injection_required_count 	= !empty($formData['no_of_injection']) ? count(array_filter($formData['no_of_injection'])) : '0';

			//dd($oaws_ui_setting_required_count,$oaws_detector_id_required_count,$oaws_column_id_required_count,$oaws_running_time_id_required_count,$oaws_instance_id_required_count,$oaws_no_of_injection_required_count);

			if (($oaws_ui_setting_required_count != $oaws_detector_id_required_count)
				||
				($oaws_ui_setting_required_count != $oaws_column_id_required_count)
				||
				($oaws_ui_setting_required_count != $oaws_running_time_id_required_count)
				||
				($oaws_ui_setting_required_count != $oaws_instance_id_required_count)
				||
				($oaws_ui_setting_required_count != $oaws_no_of_injection_required_count)
			) {
				$flag = false;
			}
		}
		return $flag;
	}
}
