<?php

/*****************************************************
 *Common Function Configuration File
 *Created By  : Praveen-Singh
 *Created On  : 15-Dec-2017
 *Modified On : 10-Oct-2018
 *Package     : ITC-ERP-PKL
 ******************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use PDF;
use File;
use Session;
use App\Helpers;
use ArrayObject;

class Models extends Model
{
	/**
	 * getting all Default Setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getDefaultSetting()
	{
		//setting_keys PERPAGE,IGST,CGST,SGST,INVOICE_PATH,INVOICE_PREFIX,ORDER_PATH,REPORT_PATH,REPORT_PREFIX,TEXTLIMIT,TOTAL_RECORD etc from setting group
		$settingGroups = DB::table('settings')->get();
		if (!empty($settingGroups)) {
			foreach ($settingGroups as $key => $settingGroup) {
				if (url('/') == 'http://localhost:90/itcerp') {
					if (strtoupper($settingGroup->setting_key) == 'SITE_URL' || strtoupper($settingGroup->setting_key) == 'ROOT_URL') {
						if (!defined(strtoupper($settingGroup->setting_key))) define(strtoupper($settingGroup->setting_key), url('/') . '/');
					} elseif (strtoupper($settingGroup->setting_key) == 'DOC_ROOT') {
						if (!defined(strtoupper($settingGroup->setting_key))) define(strtoupper($settingGroup->setting_key), base_path() . '/');
					} elseif (strtoupper($settingGroup->setting_key) == 'ROOT_DIR') {
						if (!defined(strtoupper($settingGroup->setting_key))) define(strtoupper($settingGroup->setting_key), base_path());
					} elseif (strtoupper($settingGroup->setting_key) == 'ROOT_PATH') {
						if (!defined(strtoupper($settingGroup->setting_key))) define(strtoupper($settingGroup->setting_key), 'E:\xampp\htdocs');
					} elseif (!defined(strtoupper($settingGroup->setting_key))) {
						if (!defined(strtoupper($settingGroup->setting_key))) define(strtoupper($settingGroup->setting_key), $settingGroup->setting_value);
					}
				} else {
					if (!defined(strtoupper($settingGroup->setting_key))) define(strtoupper($settingGroup->setting_key), $settingGroup->setting_value);
				}
			}
		}
	}

	/**
	 * Get ID of atable by Its code
	 * Date : 03-Jan-2018
	 * Author : Praveen Singh
	 */
	public function getIdByValue($tableName, $codeColName, $codeColValue, $codeValueId)
	{
		$requiredField = DB::table($tableName)->where($codeColName, '=', $codeColValue)->pluck($codeValueId)->first();
		return !empty($requiredField) ? $requiredField : '';
	}

	/**
	 * Get get First And Last Day Of Week
	 * Date : 03-Jan-2018
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getFirstAndLastDayOfWeek($date, $format = 'Y-m-d')
	{
		$first_day_of_week = date($format, strtotime('Last Monday', strtotime($date)));
		$last_day_of_week  = date($format, strtotime('Next Sunday', strtotime($date)));
		return array($first_day_of_week, $last_day_of_week);
	}

	/**
	 * get First And Last Day Of Month
	 * Date : 03-Jan-2018
	 * Author : Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getFirstAndLastDayOfMonth($date, $format = 'Y-m-d')
	{
		$first_day_of_month = date($format, strtotime('first day of this month', strtotime($date)));
		$last_day_of_month  = date($format, strtotime('last day of this month', strtotime($date)));
		return array($first_day_of_month, $last_day_of_month);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function formatTimeStampFromArray($data, $format = 'Y-m-d', $flag = true)
	{

		global $models, $order, $report;

		foreach ($data as $item) {
			if (!empty($item->created_at)) {
				$item->created_at = !empty($item->created_at) ? date($format, strtotime($item->created_at)) : '-';
			}
			if (!empty($item->updated_at)) {
				$item->updated_at = !empty($item->updated_at) ? date($format, strtotime($item->updated_at)) : '-';
			}
			if (!empty($item->order_date)) {
				$item->order_date = !empty($item->order_date) ? date(DATEFORMAT, strtotime($item->order_date)) : '-';
			}
			if (!empty($item->booking_date)) {
				$item->booking_date = !empty($item->booking_date) ? date(DATEFORMAT, strtotime($item->booking_date)) : '-';
			}
			if (!empty($item->report_date)) {
				$item->report_date = !empty($item->report_date) ? date(DATEFORMAT, strtotime($item->report_date)) : '-';
			}
			if (!empty($item->expected_due_date)) {
				$item->expected_due_date = !empty($item->expected_due_date) ? date(DATEFORMAT, strtotime($item->expected_due_date)) : '-';
			}
			if (!empty($item->order_dept_due_date) && !empty($item->order_id)) {
				if ($flag) {
					$item->order_dept_due_date = $order->getMaxDepartmentDueDate($item->order_id);
				}
				$item->order_dept_due_date = !empty($item->order_dept_due_date) ? date(DATEFORMAT, strtotime($item->order_dept_due_date)) : '-';
			}
			if (!empty($item->order_report_due_date) && !empty($item->order_id)) {
				if ($flag) {
					$item->order_report_due_date = $order->getMaxReportDueDate($item->order_id);
				}
				$item->order_report_due_date = !empty($item->order_report_due_date) ? date(DATEFORMAT, strtotime($item->order_report_due_date)) : '-';
			}
			if (!empty($item->dept_due_date) && !empty($item->order_id)) {
				if ($flag) {
					$item->dept_due_date = $order->getMaxDepartmentDueDate($item->order_id);
				}
				$item->dept_due_date = !empty($item->dept_due_date) ? date(DATEFORMAT, strtotime($item->dept_due_date)) : '-';
			}
			if (!empty($item->report_due_date) && !empty($item->order_id)) {
				if ($flag) {
					$item->report_due_date = $order->getMaxReportDueDate($item->order_id);
				}
				$item->report_due_date = !empty($item->report_due_date) ? date(DATEFORMAT, strtotime($item->report_due_date)) : '-';
			}
			if (!empty($item->tentative_date)) {
				$item->tentative_date = !empty($item->tentative_date) ? date('Y-m-d', strtotime($item->tentative_date)) : '-';
			}
			if (!empty($item->sample_date)) {
				$item->sample_date = !empty($item->sample_date) ? date($format, strtotime($item->sample_date)) : '-';
			}
			if (!empty($item->opl_date)) {
				$item->opl_date = !empty($item->opl_date) ? date($format, strtotime($item->opl_date)) : '-';
			}
			if (!empty($item->invoice_date)) {
				$item->invoice_date = !empty($item->invoice_date) ? date(DATEFORMAT, strtotime($item->invoice_date)) : '-';
			}
			if (!empty($item->dispatch_date)) {
				$item->dispatch_date = !empty($item->dispatch_date) ? date($format, strtotime($item->dispatch_date)) : '-';
			}
			if (!empty($item->sample_name)) {
				$item->sample_name = trim(strip_tags($item->sample_name));
			}
			if (!empty($item->order_status_time)) {
				$item->order_status_time = !empty($item->order_status_time) ? date(DATETIMEFORMAT, strtotime($item->order_status_time)) : '-';
			}
			if (!empty($item->test_parameter)) {
				$item->test_parameter = trim($item->test_parameter);
			}
			if (!empty($item->created_on)) {
				$item->created_on = !empty($item->created_on) ? date($format, strtotime($item->created_on)) : '-';
			}
			if (!empty($item->scheduled_at)) {
				$item->scheduled_at = !empty($item->scheduled_at) ? date($format, strtotime($item->scheduled_at)) : '-';
			}
			if (!empty($item->completed_at)) {
				$item->completed_at = !empty($item->completed_at) ? date($format, strtotime($item->completed_at)) : '-';
			}
			if (!empty($item->order_booking_date)) {
				$item->order_booking_date = !empty($item->order_booking_date) ? date($format, strtotime($item->order_booking_date)) : '-';
			}
			//Display Order Dispatch Status
			if (!empty($item->order_status)) {
				$item->canDispatchOrder = !empty($item->order_status) && $item->order_status > '7' ? $this->getOrderDispatchingStatus($item) : '0';
			}
			if (!empty($item->scheduled_date)) {
				$item->scheduled_date = !empty($item->scheduled_date) ? date(DATEFORMAT, strtotime($item->scheduled_date)) : '-';
			}
			if (!empty($item->reviewing_date)) {
				$item->reviewing_date = !empty($item->reviewing_date) ? date($format, strtotime($item->reviewing_date)) : '-';
			}
			if (!empty($item->approving_date)) {
				$item->approving_date = !empty($item->approving_date) ? date($format, strtotime($item->approving_date)) : '-';
			}
			if (!empty($item->mail_date)) {
				$item->mail_date = !empty($item->mail_date) ? date($format, strtotime($item->mail_date)) : '-';
			}
			if (!empty($item->mail_time)) {
				$item->mail_time = !empty($item->mail_time) ? date('h:i A', strtotime($item->mail_time)) : '-';
			}
			if (!empty($item->dispatch_time)) {
				$item->dispatch_time = !empty($item->dispatch_time) ? date('h:i A', strtotime($item->dispatch_time)) : '-';
			}
			if (!empty($item->order_scheduled_date)) {
				$item->order_scheduled_date = !empty($item->order_scheduled_date) ? date($format, strtotime($item->order_scheduled_date)) : '-';
			}
			if (!empty($item->scheduled_date)) {
				$item->scheduled_date = !empty($item->scheduled_date) ? date($format, strtotime($item->scheduled_date)) : '-';
			}
			if (!empty($item->test_completion_date)) {
				$item->test_completion_date = !empty($item->test_completion_date) ? date($format, strtotime($item->test_completion_date)) : '-';
			}
			if (!empty($item->oid_assign_date)) {
				$item->oid_assign_date = !empty($item->oid_assign_date) ? date($format, strtotime($item->oid_assign_date)) : '-';
			}
			if (!empty($item->oid_confirm_date)) {
				$item->oid_confirm_date = !empty($item->oid_confirm_date) ? date($format, strtotime($item->oid_confirm_date)) : '-';
			}
			if (!empty($item->stb_prototype_date)) {
				$item->stb_prototype_date = !empty($item->stb_prototype_date) ? date($format, strtotime($item->stb_prototype_date)) : '-';
			}
			if (!empty($item->stb_start_date)) {
				$item->stb_start_date = !empty($item->stb_start_date) ? date($format, strtotime($item->stb_start_date)) : '-';
			}
			if (isset($item->oltd_id)) {
				$item->oltd_olsd_id = $order->hasOrderTrfStpDetail($item->order_id);
			}
			if (!empty($item->olsd_cstp_file_name)) {
				$item->olsd_cstp_file_link = url(STP_PATH . $item->olsd_cstp_file_name);
			}
			if (!empty($item->chd_hold_date)) {
				$item->chd_hold_date = !empty($item->chd_hold_date) ? date($format, strtotime($item->chd_hold_date)) : '-';
			}
			if (isset($item->customer_status) && isset($item->status)) {
				$item->customer_status_class = $item->customer_status == '3' && $item->status == '12' ? ' cust-on-hold' : '';
			}
			if (isset($item->chd_hold_description) && isset($item->status) && empty($item->chd_hold_id)) {
				$item->chd_hold_description = $item->customer_status == '3' && $item->status == '12' ? $item->chd_hold_description : '';
			}
			if (!empty($item->ust_date)) {
				$item->ust_date = !empty($item->ust_date) ? date($format, strtotime($item->ust_date)) : '-';
			}
			if (!empty($item->ust_date) && !empty($item->ust_month)) {
				$item->ust_month = !empty($item->ust_date) ? date('F', strtotime($item->ust_date)) : '-';
			}
			if (!empty($item->user_signature)) {
				$item->user_signature = file_exists(DOC_ROOT . SIGN_PATH . $item->user_signature) ? trim($item->user_signature) : 'default.png';
			}
			if (!empty($item->new_customer_hl_status)) {
				$item->new_customer_hl_status = $order->isNewCustomerHasNoOrderInSixMonth($item->new_customer_id);
			}
			if (!empty($item->order_mail_status_text)) {
				$item->order_mail_status_text = !empty($item->order_status) && $item->order_status > '6' ? $this->getMailStatusText($item->order_mail_status) : '';
			}
			if (!empty($item->invoice_mail_status_text)) {
				$item->invoice_mail_status_text = $this->getMailStatusText($item->invoice_mail_status);
			}
			if (!empty($item->customer_invoicing_id)) {
				$item->customer_invoicing_detail = $this->getCustomerInvoicingDetail($item->customer_invoicing_id); //Contact Person name,Contact Person Email and Mobile;
			}
			if (!empty($item->analysis_start_date)) {
				$item->analysis_start_date = !empty($item->analysis_start_date) ? date('d-m-Y', strtotime($item->analysis_start_date)) : '-';
			}
			if (!empty($item->analysis_completion_date)) {
				$item->analysis_completion_date = !empty($item->analysis_completion_date) ? date('d-m-Y', strtotime($item->analysis_completion_date)) : '-';
			}
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function formatTimeStampFromArrayExcel($data, $format = 'Y-m-d')
	{
		global $models, $order, $report;

		if (!empty($data)) {
			foreach ($data as $item) {
				if (!empty($item->created_at)) {
					$item->created_at = !empty($item->created_at) ? date($format, strtotime($item->created_at)) : '-';
				}
				if (!empty($item->updated_at)) {
					$item->updated_at = !empty($item->updated_at) ? date($format, strtotime($item->updated_at)) : '-';
				}
				if (!empty($item->order_date)) {
					$item->order_date = !empty($item->order_date) ? date($format, strtotime($item->order_date)) : '-';
				}
				if (!empty($item->booking_date)) {
					$item->booking_date = !empty($item->booking_date) ? date($format, strtotime($item->booking_date)) : '-';
				}
				if (!empty($item->report_date)) {
					$item->report_date = !empty($item->report_date) ? date($format, strtotime($item->report_date)) : '-';
				}
				if (!empty($item->expected_due_date)) {
					$item->expected_due_date = !empty($item->expected_due_date) ? date($format, strtotime($item->expected_due_date)) : '-';
				}
				if (!empty($item->order_dept_due_date) && !empty($item->order_id)) {
					$item->order_dept_due_date = $order->getMaxDepartmentDueDate($item->order_id);
					$item->order_dept_due_date = !empty($item->order_dept_due_date) ? date($format, strtotime($item->order_dept_due_date)) : '-';
				}
				if (!empty($item->order_report_due_date) && !empty($item->order_id)) {
					$item->order_report_due_date = $order->getMaxReportDueDate($item->order_id);
					$item->order_report_due_date = !empty($item->order_report_due_date) ? date($format, strtotime($item->order_report_due_date)) : '-';
				}
				if (!empty($item->dept_due_date)) {
					$item->dept_due_date = $order->getMaxDepartmentDueDate($item->order_id);
					$item->dept_due_date = !empty($item->dept_due_date) ? date($format, strtotime($item->dept_due_date)) : '-';
				}
				if (!empty($item->report_due_date)) {
					$item->report_due_date = $order->getMaxReportDueDate($item->order_id);
					$item->report_due_date = !empty($item->report_due_date) ? date($format, strtotime($item->report_due_date)) : '-';
				}
				if (!empty($item->tentative_date)) {
					$item->tentative_date = !empty($item->tentative_date) ? date($format, strtotime($item->tentative_date)) : '-';
				}
				if (!empty($item->sample_date)) {
					$item->sample_date = !empty($item->sample_date) ? date($format, strtotime($item->sample_date)) : '-';
				}
				if (!empty($item->opl_date)) {
					$item->opl_date = !empty($item->opl_date) ? date($format, strtotime($item->opl_date)) : '-';
				}
				if (!empty($item->invoice_date)) {
					$item->invoice_date = !empty($item->invoice_date) ? date($format, strtotime($item->invoice_date)) : '-';
				}
				if (!empty($item->dispatch_date)) {
					$item->dispatch_date = !empty($item->dispatch_date) ? date($format, strtotime($item->dispatch_date)) : '-';
				}
				if (!empty($item->sample_name)) {
					$item->sample_name = trim(strip_tags($item->sample_name));
				}
				if (!empty($item->order_status_time)) {
					$item->order_status_time = !empty($item->order_status_time) ? date(DATETIMEFORMAT, strtotime($item->order_status_time)) : '-';
				}
				if (!empty($item->test_parameter)) {
					$item->test_parameter = trim($item->test_parameter);
				}
				if (!empty($item->created_on)) {
					$item->created_on = !empty($item->created_on) ? date($format, strtotime($item->created_on)) : '-';
				}
				if (!empty($item->scheduled_at)) {
					$item->scheduled_at = !empty($item->scheduled_at) ? date($format, strtotime($item->scheduled_at)) : '-';
				}
				if (!empty($item->completed_at)) {
					$item->completed_at = !empty($item->completed_at) ? date($format, strtotime($item->completed_at)) : '-';
				}
				if (!empty($item->updated_on)) {
					$item->updated_on = !empty($item->updated_on) ? date($format, strtotime($item->updated_on)) : '-';
				}
				if (!empty($item->created_at)) {
					$item->created_at = !empty($item->created_at) ? date($format, strtotime($item->created_at)) : '-';
				}
				if (!empty($item->updated_at)) {
					$item->updated_at = !empty($item->updated_at) ? date($format, strtotime($item->updated_at)) : '-';
				}
				if (!empty($item->prototype_date)) {
					$item->prototype_date = !empty($item->prototype_date) ? date($format, strtotime($item->prototype_date)) : '-';
				}
				if (!empty($item->trf_date)) {
					$item->trf_date = !empty($item->trf_date) ? date($format, strtotime($item->trf_date)) : '-';
				}
				if (!empty($item->trf_date) && isset($item->status)) {
					$item->status = isset($item->status) && $item->status == '1' ? 'Booked' : 'Pending';
				}
				if (!empty($item->user_status)) {
					$item->user_status = $item->user_status == '1' ? 'Active' : 'Inactive';
				}
				if (isset($item->is_sales_person)) {
					$item->is_sales_person = $item->is_sales_person == '1' ? 'Yes' : 'No';
				}
				if (!empty($item->ust_date)) {
					$item->ust_date = !empty($item->ust_date) ? date($format, strtotime($item->ust_date)) : '-';
				}
				if (!empty($item->ust_date) && !empty($item->ust_month)) {
					$item->ust_month = !empty($item->ust_date) ? date('F', strtotime($item->ust_date)) : '-';
				}
				if (!empty($item->order_mail_status_text)) {
					$item->order_mail_status = !empty($item->order_status) && $item->order_status > '6' ? $this->getMailStatusText($item->order_mail_status) : '';
				}
				if (!empty($item->invoice_mail_status_text)) {
					$item->invoice_mail_status = $this->getMailStatusText($item->invoice_mail_status);
				}
				if (!empty($item->analysis_start_date)) {
					$item->analysis_start_date = !empty($item->analysis_start_date) ? date('d-m-Y', strtotime($item->analysis_start_date)) : '-';
				}
				if (!empty($item->analysis_completion_date)) {
					$item->analysis_completion_date = !empty($item->analysis_completion_date) ? date('d-m-Y', strtotime($item->analysis_completion_date)) : '-';
				}
			}
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function formatTimeStamp($item, $format = 'Y-m-d')
	{
		if (!empty($item->created_at)) {
			$item->created_at = !empty($item->created_at) ? date($format, strtotime($item->created_at)) : '-';
		}
		if (!empty($item->updated_at)) {
			$item->updated_at = !empty($item->updated_at) ? date($format, strtotime($item->updated_at)) : '-';
		}
		if (!empty($item->order_date)) {
			$item->booking_date_time = !empty($item->order_date) ? date($format, strtotime($item->order_date)) : '-';
			$item->order_date        = !empty($item->order_date) ? date('d-m-Y', strtotime($item->order_date)) : '-';
		}
		if (!empty($item->expected_due_date)) {
			$item->expected_due_date = !empty($item->expected_due_date) ? date($format, strtotime($item->expected_due_date)) : '-';
		}
		if (!empty($item->sampling_date)) {
			$item->sampling_date = !empty($item->sampling_date) ? date('d-m-Y H:i:s', strtotime($item->sampling_date)) : '-';
		}
		if (!empty($item->report_date)) {
			$item->report_date = !empty($item->report_date) ? date(DATEFORMAT, strtotime($item->report_date)) : '-';
		}
		if (!empty($item->order_dept_due_date)) {
			$item->order_dept_due_date = !empty($item->order_dept_due_date) ? date($format, strtotime($item->order_dept_due_date)) : '-';
		}
		if (!empty($item->order_report_due_date)) {
			$item->order_report_due_date = !empty($item->order_report_due_date) ? date($format, strtotime($item->order_report_due_date)) : '-';
		}
		if (!empty($item->dept_due_date)) {
			$item->dept_due_date = !empty($item->dept_due_date) ? date($format, strtotime($item->dept_due_date)) : '-';
		}
		if (!empty($item->report_due_date)) {
			$item->report_due_date = !empty($item->report_due_date) ? date($format, strtotime($item->report_due_date)) : '-';
		}
		if (!empty($item->opl_date)) {
			$item->opl_date = !empty($item->opl_date) ? date(DATEFORMAT, strtotime($item->opl_date)) : '-';
		}
		if (!empty($item->sample_date)) {
			$item->sample_date_org = !empty($item->sample_date) ? date(MYSQLDATETIMEFORMAT, strtotime($item->sample_date)) : '-';
			$item->sample_date     = !empty($item->sample_date) ? date(MYSQLDATFORMAT, strtotime($item->sample_date)) : '-';
		}
		if (!empty($item->sample_current_date)) {
			$item->sample_current_date = !empty($item->sample_current_date) ? date(MYSQLDATFORMAT, strtotime($item->sample_current_date)) : '-';
		}
		if (!empty($item->dispatch_date)) {
			$item->dispatch_date = !empty($item->dispatch_date) ? date($format, strtotime($item->dispatch_date)) : '-';
		}
		if (!empty($item->cancelled_date)) {
			$item->cancelled_date = !empty($item->cancelled_date) ? date($format, strtotime($item->cancelled_date)) : '-';
		}
		if (!empty($item->recovered_date)) {
			$item->recovered_date = !empty($item->recovered_date) ? date($format, strtotime($item->recovered_date)) : '-';
		}
		if (!empty($item->reviewing_date)) {
			$item->reviewing_date = !empty($item->reviewing_date) ? date($format, strtotime($item->reviewing_date)) : '-';
		}
		if (!empty($item->incharge_reviewing_date)) {
			$item->incharge_reviewing_date = !empty($item->incharge_reviewing_date) ? date($format, strtotime($item->incharge_reviewing_date)) : '-';
		}
		if (!empty($item->finalizing_date)) {
			$item->finalizing_date = !empty($item->finalizing_date) ? date($format, strtotime($item->finalizing_date)) : '-';
		}
		if (!empty($item->invoice_cancelled_date)) {
			$item->invoice_cancelled_date = !empty($item->invoice_cancelled_date) && strtotime($item->invoice_cancelled_date) > '0' ? date($format, strtotime($item->invoice_cancelled_date)) : '-';
		}
		if (!empty($item->invoice_canc_approved_date)) {
			$item->invoice_canc_approved_date = !empty($item->invoice_canc_approved_date) && strtotime($item->invoice_canc_approved_date) > '0' ? date($format, strtotime($item->invoice_canc_approved_date)) : '-';
		}
		if (!empty($item->stb_start_date)) {
			$item->stb_start_date = !empty($item->stb_start_date) ? date($format, strtotime($item->stb_start_date)) : '-';
		}
		if (!empty($item->stb_end_date)) {
			$item->stb_end_date = !empty($item->stb_end_date) ? date($format, strtotime($item->stb_end_date)) : '-';
		}
		if (!empty($item->ust_date)) {
			$item->ust_date = !empty($item->ust_date) ? date($format, strtotime($item->ust_date)) : '-';
		}
		if (!empty($item->ust_date) && !empty($item->ust_month)) {
			$item->ust_month = !empty($item->ust_date) ? date('F', strtotime($item->ust_date)) : '-';
		}
		if (!empty($item->ocad_date)) {
			$item->ocad_date = !empty($item->ocad_date) ? date($format, strtotime($item->ocad_date)) : '-';
		}
		if (!empty($item->ocad_date_upto_amt)) {
			$item->ocad_date_upto_amt = !empty($item->ocad_date_upto_amt) ? date($format, strtotime($item->ocad_date_upto_amt)) : '-';
		}
		if (!empty($item->analysis_start_date)) {
			$item->analysis_start_date = !empty($item->analysis_start_date) ? date('d-m-Y', strtotime($item->analysis_start_date)) : '-';
		}
		if (!empty($item->analysis_completion_date)) {
			$item->analysis_completion_date = !empty($item->analysis_completion_date) ? date('d-m-Y', strtotime($item->analysis_completion_date)) : '-';
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function formatCustomTimeStampFromArray($data, $format = 'Y-m-d', $coloumNameArray = array())
	{
		foreach ($data as $item) {
			foreach ($coloumNameArray as $coloumName) {
				if (!empty($item->order_date) && $coloumName == 'order_date') {
					$item->order_date = !empty($item->order_date) ? date($format, strtotime($item->order_date)) : '-';
				}
				if (!empty($item->expected_due_date) && $coloumName == 'expected_due_date') {
					$item->expected_due_date = !empty($item->expected_due_date) ? date($format, strtotime($item->expected_due_date)) : '-';
				}
			}
		}
	}

	/**
	 * function to display order sample type
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getOrderDispatchingStatus($items)
	{

		global $models, $order;

		if (!empty($items->dispatch_status)) {
			return 1; 			//Dispatched Orders,Hide the Dispatch Button
		} else {
			if (!empty($items->billing_type_id) && $items->billing_type_id == '4' && empty($items->dispatch_status)) {
				return 2; 		//Dispatching Orders,Show the Dispatched Button
			} else if (!empty($items->order_sample_type) && in_array($items->order_sample_type, array('1', '2')) && empty($items->dispatch_status)) {
				return 2; 		//Dispatching Orders,Show the Dispatched Button
			} else if (!empty($items->order_id) && $items->billing_type_id == '1' && !empty($order->isBookingOrderAmendedOrNot($items->order_id)) && empty($items->dispatch_status)) {
				return 2; 		//Dispatching Orders,Show the Dispatched Button in case of Daily Order which get amended
			} else if (!empty($items->billing_type_id) && $items->billing_type_id == '5' && empty($items->dispatch_status)) {
				return 2; 		//Dispatching Orders,Show the Dispatched Button
			} else {
				return 3; 		//Not Eligible for Dispatched means order has Daily Billing
			}
		}
	}

	/**
	 * function to can Update Booking Order Status
	 *
	 *CASE 1:If order is Monthly and Status is greater than > 7
	 *CASE 2:If order is Lab/Inter Order and Status is greater than > 7
	 *CASE 2:If order is Daily Order and IS amended Order and Status is greater than > 7
	 *
	 */
	function canUpdateBookingOrderStatus($items)
	{

		global $models, $order;

		if (empty($items->dispatch_status)) {
			if ($items->status == '8') {
				return false;
			} else if ($items->status == '9') {
				return true;
			}
		}
	}

	/********************************************************************
	 * Description : function to check wheather invoice generated before dispacthing
	 * Date        : 05-05-2018(used in reportscontoller.php in dispatch function)
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ***********************************************************************/
	public function checkInvoiceGeneratedOrNot($order_id)
	{
		$amendedWithInvoiceStatus = DB::table('order_process_log')
			->join('invoice_hdr_detail', 'invoice_hdr_detail.order_id', '=', 'order_process_log.opl_order_id')
			->where('order_process_log.opl_order_id', '=', $order_id)
			->where('order_process_log.opl_order_status_id', '8')
			->whereNotNull('order_process_log.opl_user_id')
			->where('invoice_hdr_detail.invoice_hdr_status', '1')
			->first();
		if (!empty($amendedWithInvoiceStatus)) {
			return true;    //Invoice Generated
		} else {
			return false;
		}
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getAutoGenCode($sectionName)
	{
		return $sectionName . date('d') . date('m') . date('y') . time();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function unsetFormDataVariableByValues($formData, $unsetKeyData)
	{
		foreach ($unsetKeyData as $valueName) {
			if (($key = array_search($valueName, $formData)) !== false) {
				unset($formData[$key]);
			}
		}
		return $formData;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function unsetFormDataVariables($formData, $unsetKeyData)
	{
		foreach ($unsetKeyData as $keyName) {
			if (array_key_exists($keyName, $formData)) unset($formData[$keyName]);
		}
		return $formData;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function unsetFormDataVariablesArray($formData, $unsetKeyData)
	{
		foreach ($formData as $key => $keyData) {
			foreach ($unsetKeyData as $keyName) {
				unset($keyData[$keyName]);
			}
			$formData[$key] = $keyData;
		}
		return $formData;
	}

	/**
	 * Check multiple key in array
	 *
	 * @return \Illuminate\Http\Response
	 */
	function in_array_all($needles, $haystack)
	{
		if (!empty($needles) && !empty($haystack)) {
			if (is_array($needles)) {
				foreach ($needles as $needle) {
					if (in_array($needle, $haystack)) {
						return true;
					}
				}
			} else {
				if (in_array($needles, $haystack)) {
					return true;
				}
			}
		} else {
			return false;
		}
	}

	/**
	 * read csv file data and convert to array
	 *
	 * @return \Illuminate\Http\Response
	 */
	function csvToArray($fileName)
	{
		$handle = fopen($fileName, "r");
		$fields = fgetcsv($handle, 1000, ",");
		foreach ($fields as $k => $val) {
			$header['header'][$k] = strtoupper(str_replace('_', ' ', $val));
		}
		while (($result = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$header['data'][] = $result;
		}
		fclose($handle);
		return $header;
	}

	/**
	 * Parsing CSV File
	 */
	public function parseCSV($file)
	{
		$fileData = array();
		if (($handle = fopen($file, 'r')) !== false) {
			while (($row = fgetcsv($handle)) !== false) {
				foreach ($row as &$field) {
					$field = trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', env('EUROSIGN'), $field));
				}
				$fileData[] = $row;
			}
			fclose($handle);
		}
		return array_filter($fileData);
	}

	/**
	 * shift last column to first
	 *
	 * @return \Illuminate\Http\Response
	 */
	function shiftArray($arr)
	{
		$dataLast = array();
		$arrLast[]  = array_pop($arr);
		$arrayColumns = array_merge($arrLast, $arr);
		return $arrayColumns;
	}

	/**
	 * Removing of extra Slashes from URL
	 */
	function removeExtraSlash($url)
	{
		return preg_replace('/([^:])(\/{2,})/', '$1/', $url);
	}

	/**
	 * convert date from 01/04/2017 to 2017-01-04 formate
	 *
	 * @return \Illuminate\Http\Response
	 */
	function dateConvert($date)
	{
		return date_format(date_create($date), "Y-m-d");
	}

	function convertDateFormat($date)
	{
		return date("Y-m-d", strtotime($date));
	}

	function convertObjectToArray($data)
	{
		return json_decode(json_encode($data), true);
	}

	function convertArrayToObject($data)
	{
		return json_decode(json_encode($data), false);
	}

	/*************************
	 *Validate order date
	 *Date that needs to be tested goes here
	 *************************/
	function isValidDate($date)
	{
		if (strpos($date, '-') !== false) {
			list($dd, $mm, $yyyy) = explode('-', $date);
			if (checkdate($mm, $dd, $yyyy)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/*************************
	 *function to get formated date
	 *Check date format
	 *************************/
	function get_formatted_date($date, $format = 'Y-m-d')
	{
		return date($format, strtotime($date));
	}

	/*************************
	 *function to get formated date and time
	 *Check date and time format
	 *************************/
	function add_current_time_in_date($date, $format = 'Y-m-d')
	{
		return date($format, strtotime($date)) . ' ' . date("H:i:s");
	}

	/**
	 * Recursively filter an array
	 *
	 * @param array $array
	 * @param callable $callback
	 *
	 * @return array
	 */
	function array_filter_recursive($array)
	{
		foreach ($array as &$value) {
			if (is_array($value)) {
				//$value = array_filter($value);
			}
		}
		return $array;
	}

	/**
	 * Recursively filter an array
	 *
	 * @param array $array
	 * @param callable $callback
	 *
	 * @return array
	 */
	function remove_null_value_from_array($array)
	{
		return array_filter($array, function ($value) {
			return $value !== '';
		});
	}

	/**
	 * Recursively filter an array
	 *
	 * @param array $array
	 * @param callable $callback
	 *
	 * @return array
	 */
	function remove_null_blank_value_from_array($array)
	{
		return array_filter($array, function ($value) {
			return strlen($value) > 0;
		});
	}

	/**
	 * Recursively filter an array
	 *
	 * @param array $array
	 * @param callable $callback
	 *
	 * @return array
	 */
	function get_encrypted_string($array)
	{
		return !empty($array) ? encrypt(json_encode($array)) : array();
	}

	/**
	 * Recursively filter an array
	 *
	 * @param array $array
	 * @param callable $callback
	 *
	 * @return array
	 */
	function get_decrypted_string($string)
	{
		return !empty($string) ? json_decode(decrypt($string)) : array();
	}

	/**
	 * Recursively filter an array
	 *
	 * @param array $array
	 * @param callable $callback
	 *
	 * @return array
	 */
	function assign_null_value_in_array($arrayData)
	{

		$returnData = array();

		if (!empty($arrayData)) {
			foreach ($arrayData as $levelOne => $valueAll) {
				foreach ($valueAll as $levelTwo => $levelOneValue) {
					if (!empty($levelOneValue) && is_array($levelOneValue)) {
						foreach ($levelOneValue as $levelThree => $levelTwoValue) {
							$returnData[$levelOne][$levelTwo][$levelThree] = isset($levelTwoValue) && strlen($levelTwoValue) > 0 ? trim($levelTwoValue) : null;
						}
					} else {
						$returnData[$levelOne][$levelTwo] = isset($levelOneValue) && strlen($levelOneValue) > 0 ? trim($levelOneValue) : null;
					}
				}
			}
		}
		return $returnData;
	}

	function generateCode($prefix, $tableName, $fieldName, $uniqueId)
	{

		//getting Max Serial Number
		$maxCode = DB::table($tableName)->select('*')->orderBy($uniqueId, 'DESC')->limit(1)->first();
		$maxSerialNo  = !empty($maxCode->$uniqueId) ? $maxCode->$uniqueId + 1 : '1';

		//Combing all to get unique order number
		return str_replace(' ', '', $prefix . $maxSerialNo);
	}

	function getParameterEquipmentTypeId($test_parameter_id)
	{
		$data = DB::table('test_parameter_equipment_types')->select('equipment_type_id')->where('test_parameter_id', '=', $test_parameter_id)->get()->toArray();
		if (!empty($data))
			return $data;
		else
			return false;
	}

	function getExplodedData($data, $delimater, $part = NULL)
	{
		$dataArray = explode($delimater, $data);
		return isset($part) ? trim($dataArray[$part]) : $dataArray;
	}

	function getParameterName($test_parameter_id)
	{
		$data = DB::table('test_parameter')->select('test_parameter_name')->where('test_parameter_id', '=', $test_parameter_id)->first();
		if (!empty($data))
			return $data->test_parameter_name;
		else
			return false;
	}

	function getMainProductCatParentId($p_category_id)
	{
		$data = DB::table('product_categories')->where('p_category_id', '=', $p_category_id)->first();
		if (isset($data->parent_id) && $data->parent_id > 0) {
			return $this->getMainProductCatParentId($data->parent_id);
		}
		return !empty($data->p_category_id) ? $data->p_category_id : '0';
	}

	//get product_category_name by product_category_id
	function getMainProductCatParentName($p_category_id)
	{
		$data = DB::table('product_categories')->where('p_category_id', '=', $p_category_id)->first();
		if (isset($data->parent_id) && $data->parent_id > 0) {
			return $this->getMainProductCatParentName($data->parent_id);
		}
		return !empty($data->p_category_name) ? trim($data->p_category_name) : '';
	}

	//get product_category_name by product_category_id
	function getAllChildrens($parent_id)
	{
		$tree = array();
		if (!empty($parent_id)) {
			$tree = DB::table('product_categories')->where('parent_id', '=', $parent_id)->pluck('p_category_id')->toArray();
			foreach ($tree as $key => $val) {
				$ids = $this->getAllChildrens($val);
				$tree = array_merge($tree, $ids);
			}
		}
		return $tree;
	}

	//Date that needs to be tested goes here
	function getFormatedDate($date, $format = 'Y-m-d')
	{
		return date($format, strtotime($date));
	}

	//Date that needs to be tested goes here
	function getFormatedDateTime($date, $format = 'Y-m-d')
	{
		return date($format, strtotime($date)) . ' ' . date("H:i:s");
	}

	//get method id using method name
	public function getMethodId($method_name = NULL)
	{
		if (!empty($method_name)) {
			$methodData = DB::table('method_master')->select('method_master.method_id')->where('method_master.method_name', trim($method_name))->first();
		}
		return !empty($methodData->method_id) ? trim($methodData->method_id) : '0';
	}

	/**
	 * Convert html to string formate
	 *
	 * @return \Illuminate\Http\Response
	 */
	function htmlToStringFormate($data)
	{
		foreach ($data as $item) {
			if (!empty($item->name)) {
				$item->name = strip_tags($item->name);
			}
		}
	}

	/**
	 * function for Saving PDF in DB
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generatePDF($contentIds, $contentType = 'order')
	{

		global $order, $report, $models, $invoice, $mail, $numbersToWord;

		$pdfData = $viewData = $response = array();

		if (!empty($contentIds)) {

			if ($contentType == 'order') {

				//directory Path
				$dirName        = DOC_ROOT . ORDER_PATH;
				$jobOrderPrefix = JOB_ORDER_PREFIX;
				$viewFileName   = 'sales.order_master.generateJobOrder';

				if (is_array($contentIds)) {
					foreach ($contentIds as $key => $contentId) {
						$pdfData[$contentId] = $order->viewOrderDetail($contentId);
					}
				} else {
					$pdfData[$contentIds] = $order->viewOrderDetail($contentIds);
				}

				if (!is_dir($dirName)) {
					mkdir($dirName, 0777, true);
				}

				if (!empty($pdfData) && is_dir($dirName)) {
					foreach ($pdfData as $key => $value) {
						$fileName    = $jobOrderPrefix . $value['order']['order_no'] . '.pdf';
						$fileNameDir = preg_replace('/(\/+)/', '/', $dirName . $fileName);
						view()->share('viewData', $value);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						if ($pdf->save($fileNameDir)) {
							$response[$value['order']['order_id']] = $fileName;
						}
					}
				}
			} else if ($contentType == 'jobSheet') {

				//directory Path
				$dirName        = DOC_ROOT . ANALYTICAL_PATH;
				$viewFileName   = 'schedulings.jobPrint.generateAnalyticalSheet';

				$reportContentIds               = !empty($contentIds['order_id']) ? $contentIds['order_id'] : '0';
				$containAnalyticalOrCalculation = !empty($contentIds['downloadType']) ? $contentIds['downloadType'] : '0';
				$analyticalCalculationPrefix    = $containAnalyticalOrCalculation == '1' ? '-as' : '-cs';

				if ($reportContentIds) {
					if (is_array($reportContentIds)) {
						foreach ($reportContentIds as $key => $contentId) {
							$pdfData[$contentId] = $order->viewOrderDetail($contentId);
						}
					} else {
						$pdfData[$reportContentIds] = $order->viewOrderDetail($reportContentIds);
					}

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}

					if (!empty($pdfData) && is_dir($dirName)) {
						foreach ($pdfData as $key => $value) {
							$fileName    = $value['order']['order_no'] . $analyticalCalculationPrefix . '.pdf';
							$fileNameDir = preg_replace('/(\/+)/', '/', $dirName . $fileName);
							view()->share('containAnalyticalOrCalculation', $containAnalyticalOrCalculation);
							view()->share('viewData', $value);
							$pdf = PDF::loadView($viewFileName);
							$pdf->setPaper('a4', 'portrait')->setWarnings(false);
							if ($pdf->save($fileNameDir)) {
								$response[$value['order']['order_id']] = $fileName;
							}
						}
					}
				}
			} else if ($contentType == 'report') {

				//directory Path
				$dirName      = DOC_ROOT . REPORT_PATH;
				$reportPrefix = REPORT_PREFIX;
				$viewFileName = 'sales.reports.generateReport';

				$reportContentIds       = !empty($contentIds['order_id']) ? $contentIds['order_id'] : '0';
				$hasContainHeaderFooter = !empty($contentIds['downloadType']) ? $contentIds['downloadType'] : '0';
				$headerFooterFilePrefix = $hasContainHeaderFooter == '1' ? '-hf' : '-whf';

				if ($reportContentIds) {
					if (is_array($reportContentIds)) {
						foreach ($reportContentIds as $key => $contentId) {
							$pdfData[$contentId] = $order->viewReportDetail($contentId);
						}
					} else {
						$pdfData[$reportContentIds] = $order->viewReportDetail($reportContentIds);
					}
					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}
					if (!empty($pdfData) && is_dir($dirName)) {
						foreach ($pdfData as $key => $value) {
							$fileName    = $value['order']['order_no'] . $headerFooterFilePrefix . '.pdf';
							$fileNameDir = preg_replace('/(\/+)/', '/', $dirName . $fileName);
							view()->share('viewData', $value);
							view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
							$pdf = PDF::loadView($viewFileName);
							$pdf->setPaper('a4', 'portrait')->setWarnings(false);
							if ($pdf->save($fileNameDir)) {
								$response[$value['order']['order_report_id']] = $fileName;
							}
						}
					}
				}
			} else if ($contentType == 'invoice') {

				//directory Path
				$dirName      = DOC_ROOT . INVOICE_PATH;
				$viewFileName = 'sales.invoices.generateInvoice';

				if (is_array($contentIds)) {
					foreach ($contentIds as $key => $contentId) {
						$pdfData[$contentId] = $invoice->getInvoiceData($contentId);
					}
				} else {
					$pdfData[$contentIds] = $invoice->getInvoiceData($contentIds);
				}

				if (!is_dir($dirName)) {
					mkdir($dirName, 0777, true);
				}

				if (!empty($pdfData) && is_dir($dirName)) {
					foreach ($pdfData as $key => $value) {
						$fileName    = $value['invoiceHeader']['invoice_no'] . '.pdf';
						$fileNameDir = preg_replace('/(\/+)/', '/', $dirName . $fileName);
						view()->share('viewData', $value);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						if ($pdf->save($fileNameDir)) {
							$response[$value['invoiceHeader']['invoice_id']] = $fileName;
						}
					}
				}
			} else if ($contentType == 'invoicewhf') {

				//directory Path
				$dirName                = DOC_ROOT . INVOICE_PATH;
				$viewFileName 	        = 'sales.invoices.generateInvoice';
				$invoiceContentIds      = !empty($contentIds['invoice_id']) ? $contentIds['invoice_id'] : '0';
				$hasContainHeaderFooter = !empty($contentIds['downloadType']) ? $contentIds['downloadType'] : '0';
				$headerFooterFilePrefix = $hasContainHeaderFooter == '1' ? '-hf' : '-whf';

				if ($invoiceContentIds) {
					if (is_array($invoiceContentIds)) {
						foreach ($invoiceContentIds as $key => $contentId) {
							$pdfData[$contentId] = $invoice->getInvoiceData($contentId);
						}
					} else {
						$pdfData[$invoiceContentIds] = $invoice->getInvoiceData($invoiceContentIds);
					}

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}

					if (!empty($pdfData) && is_dir($dirName)) {
						foreach ($pdfData as $key => $value) {
							$fileName    = $value['invoiceHeader']['invoice_no'] . $headerFooterFilePrefix . '.pdf';
							$fileNameDir = preg_replace('/(\/+)/', '/', $dirName . $fileName);
							view()->share('viewData', $value);
							view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
							$pdf = PDF::loadView($viewFileName);
							$pdf->setPaper('a4', 'portrait')->setWarnings(false);
							if ($pdf->save($fileNameDir)) {
								$response[$value['invoiceHeader']['invoice_id']] = $fileName;
							}
						}
					}
				}
			} else if ($contentType == 'analystSheet') {

				//directory Path
				$dirName      = DOC_ROOT . ANALYST_PATH;
				$viewFileName = 'schedulings.jobPrint.generateAnalystJobSheetPrint';

				if (!empty($contentIds)) {

					$pdfData[] = $contentIds;
					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}

					if (!empty($pdfData) && is_dir($dirName)) {
						foreach ($pdfData as $key => $value) {
							$fileName    = 'analyst-sheet-' . date('mY') . '.pdf';
							$fileNameDir = preg_replace('/(\/+)/', '/', $dirName . $fileName);
							view()->share('viewData', $value);
							$pdf = PDF::loadView($viewFileName);
							$pdf->setPaper('a4', 'landscape')->setWarnings(false);
							if ($pdf->save($fileNameDir)) {
								$response[$key] = $fileName;
							}
						}
					}
				}
			} else if ($contentType == 'product_test') {

				$dirName        = DOC_ROOT . PRODUCT_TEST_PATH;
				$productTestPrefix = PRODUCT_TEST_PREFIX;
				$viewFileName   = 'master.standard_wise_product_test.product_tests.generateProductTestParameterList';
				$pdfData[$contentIds] = $this->getProductTestParametersList($contentIds);

				if (!is_dir($dirName)) {
					mkdir($dirName, 0777, true);
				}

				if (!empty($pdfData) && is_dir($dirName)) {
					$fileName    = $productTestPrefix . $pdfData[$contentIds]['testDetails']['test_code'] . '.pdf';
					$fileNameDir = preg_replace('/(\/+)/', '/', $dirName . $fileName);
					view()->share('viewData', $pdfData[$contentIds]);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'portrait')->setWarnings(false);
					if ($pdf->save($fileNameDir)) {
						$response[$pdfData[$contentIds]['testDetails']['test_id']] = $fileName;
					}
				}
			}
		}
		return $response;
	}

	/**
	 * function for Saving and downloadingPDF in DB
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function downloadSavePDF($contentId, $contentType = 'order')
	{

		global $order, $report, $models, $invoice, $mail, $numbersToWord, $creditNote, $debitNote;

		if (!empty($contentId)) {

			$viewData = array();

			if ($contentType == 'order') {

				$jobOrderPrefix = JOB_ORDER_PREFIX;
				$viewFileName = 'sales.order_master.generateJobOrder';
				$pdfData        = $order->viewOrderDetail($contentId);

				if (!empty($pdfData) && is_dir($dirName)) {
					$fileName    = $jobOrderPrefix . $pdfData['order']['order_no'] . '.pdf';
					$fileNameDir = preg_replace('/(\/+)/', '/', $dirName . $fileName);
					view()->share('viewData', $pdfData);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'portrait')->setWarnings(false);
					return $pdf->save($fileNameDir)->download($fileName);
				}
			} else if ($contentType == 'report') {

				$dirName      = DOC_ROOT . REPORT_PATH;
				$reportPrefix = REPORT_PREFIX;
				$viewFileName = 'sales.reports.generateReport';
				$pdfData      = $order->viewReportDetail($contentId);

				if (!empty($pdfData) && is_dir($dirName)) {
					$fileName    = $pdfData['order']['order_no'] . '.pdf';
					$fileNameDir = preg_replace('/(\/+)/', '/', $dirName . $fileName);
					view()->share('viewData', $pdfData);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'portrait')->setWarnings(false);
					return $pdf->save($fileNameDir)->download($fileName);
				}
			} else if ($contentType == 'invoice') {

				//directory Path
				$dirName      		= DOC_ROOT . INVOICE_PATH;
				$viewFileName 	    	= 'sales.invoices.generateInvoice';
				$invoiceContentId       = !empty($contentId['invoice_id']) ? $contentId['invoice_id'] : '0';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';
				$headerFooterFilePrefix = $hasContainHeaderFooter == '1' ? '-hf' : '-whf';

				if ($invoiceContentId) {

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}
					$pdfData = $invoice->getInvoiceData($invoiceContentId);

					if (!empty($pdfData) && is_dir($dirName)) {
						$fileNameDownload = $pdfData['invoiceHeader']['invoice_no'] . '.pdf';
						$fileNameDir 	  = preg_replace('/(\/+)/', '/', $dirName . $fileNameDownload);
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						return $pdf->save($fileNameDir)->stream($fileNameDownload, array('Attachment' => 0));
					}
				}
			} else if ($contentType == 'credit_note') {

				//directory Path
				$dirName      		= DOC_ROOT . INVOICE_PATH;
				$viewFileName 	    	= 'payments.credit_notes.generateCreditNote';
				$invoiceContentId       = !empty($contentId['credit_note_id']) ? $contentId['credit_note_id'] : '0';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';

				if ($invoiceContentId) {

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}
					$pdfData = $creditNote->getCreditNoteDetail($invoiceContentId);

					if (!empty($pdfData) && is_dir($dirName)) {

						$fileNameDownload = str_replace(' ', '_', $pdfData['creditNoteHeader']['invoice_no'] . '.pdf');
						$fileNameDir 	  = preg_replace('/(\/+)/', '/', $dirName . $fileNameDownload);
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						return $pdf->stream($fileNameDownload, array('Attachment' => 0));
					}
				}
			} else if ($contentType == 'debit_note') {

				//directory Path
				$dirName      		= DOC_ROOT . INVOICE_PATH;
				$viewFileName 	    	= 'payments.debit_notes.generateDebitNote';
				$debitContentId       	= !empty($contentId['debit_note_id']) ? $contentId['debit_note_id'] : '0';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';

				if ($debitContentId) {

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}
					$pdfData = $debitNote->getDebitNoteDetail($debitContentId);

					if (!empty($pdfData) && is_dir($dirName)) {

						$fileNameDownload = str_replace(' ', '_', $pdfData['debitNoteHeader']['invoice_no'] . '.pdf');
						$fileNameDir 	  = preg_replace('/(\/+)/', '/', $dirName . $fileNameDownload);
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						return $pdf->stream($fileNameDownload, array('Attachment' => 0));
					}
				}
			}
		}
	}

	/**
	 * function for Saving and downloadingPDF in DB
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function downloadSaveDynamicPDF($contentId, $contentType = 'orderConfirmation')
	{

		global $order, $report, $models, $invoice, $numbersToWord;

		if (!empty($contentId)) {

			$viewData = array();

			if ($contentType == 'orderConfirmation') {

				//directory Path
				$dirName 	  	= DOC_ROOT . TEMP_PATH;
				$viewFileName 	  	= 'sales.order_master.sendOrderConfirmation';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';
				$reportContentId  	= !empty($contentId['order_id']) ? $contentId['order_id'] : '0';

				if ($reportContentId) {

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}
					$pdfData = $order->viewOrderConfirmationDetail($reportContentId);

					if (!empty($pdfData) && is_dir($dirName)) {
						$fileNameDownload = $pdfData['order']['order_no'] . '.pdf';
						$fileNameDir 	  = preg_replace('/(\/+)/', '/', $dirName . $fileNameDownload);
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						$pdf->save($fileNameDir);
						return array($fileNameDownload, $fileNameDir);
					}
				}
			} else if ($contentType == 'invoice') {

				//directory Path
				$dirName      		= !empty($contentId['doc_root']) ? trim($contentId['doc_root']) : DOC_ROOT . REPORT_PATH;
				$viewFileName 	    	= 'sales.invoices.generateInvoice';
				$invoiceContentId       = !empty($contentId['invoice_id']) ? $contentId['invoice_id'] : '0';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';
				$invoiceTemplateType    = !empty($contentId['invoiceTemplateType']) ? $contentId['invoiceTemplateType'] : '0';

				if ($invoiceContentId) {

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}
					$pdfData = $invoice->getInvoiceData($invoiceContentId);

					if (!empty($pdfData) && is_dir($dirName)) {

						$fileNameDownload = $pdfData['invoiceHeader']['invoice_no'] . '.pdf';
						$fileNameDir 	  = preg_replace('/(\/+)/', '/', $dirName . $fileNameDownload);
						view()->share('invoiceTemplateType', $invoiceTemplateType);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						view()->share('viewData', $pdfData);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						$pdf->save($fileNameDir);
						return array($fileNameDownload, $fileNameDir);
					}
				}
			} else if ($contentType == 'report') {

				//directory Path
				$dirName      		= !empty($contentId['doc_root']) ? trim($contentId['doc_root']) : DOC_ROOT . REPORT_PATH;
				$reportPrefix 		= REPORT_PREFIX;
				$viewFileName 		= 'sales.reports.generateReport';
				$reportContentId        = !empty($contentId['order_id']) ? $contentId['order_id'] : '0';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '1';
				$hasContainEquipment    = !empty($contentId['reportWithEquipment']) ? $contentId['reportWithEquipment'] : '0';
				$notContainEquipment    = !empty($contentId['reportWithoutEquipment']) ? $contentId['reportWithoutEquipment'] : '0';
				$hasReportWithRightLogo = !empty($contentId['reportWithRightLogo']) ? $contentId['reportWithRightLogo'] : '0';
				$notContainLimit 	= !empty($contentId['reportWithoutLimit']) ? $contentId['reportWithoutLimit'] : '0';
				$withOutPartWiseReport  = !empty($contentId['withoutPartwiseReport']) ? $contentId['withoutPartwiseReport'] : '0';
				$signatureTypeStatus    = !empty($contentId['signatureType']) ? $contentId['signatureType'] : '8';
				$withOrWithoutSignature    = ($hasContainHeaderFooter == '1' && $signatureTypeStatus == '8') || ($hasContainHeaderFooter == '3' && $signatureTypeStatus == '10') ? '1' : '0';
				$reportWithDisciplineGroup = !empty($contentId['reportWithDisciplineGroup']) ? $contentId['reportWithDisciplineGroup'] : '0';

				if ($reportContentId) {

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}

					$pdfData = $order->generateReportDetail($reportContentId, $contentId);
					$pdfData = $models->customizeReportDetail($pdfData, $contentId);

					if (!empty($pdfData) && is_dir($dirName)) {

						$fileNameDownload = $pdfData['order']['order_no'] . '.pdf';
						$fileNameDir 	  = preg_replace('/(\/+)/', '/', $dirName . $fileNameDownload);
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						view()->share('hasContainEquipment', $hasContainEquipment);
						view()->share('notContainEquipment', $notContainEquipment);
						view()->share('notContainLimit', $notContainLimit);
						view()->share('withOutPartWiseReport', $withOutPartWiseReport);
						view()->share('withOrWithoutSignature', $withOrWithoutSignature);
						view()->share('reportWithDisciplineGroup', $reportWithDisciplineGroup);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						$pdf->save($fileNameDir);
						return array($fileNameDownload, $fileNameDir);
					}
				}
			} else if ($contentType == 'voc') {

				//directory Path
				$dirName 	  	= ROOT_DIR . VOC_PATH;
				$viewFileName 	    	= 'email.templates.voc.generateCustomerVoc';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';

				if ($contentId) {

					$pdfData = $contentId;
					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}

					if (!empty($pdfData) && is_dir($dirName)) {
						$fileNameDownload = 'VOC' . $pdfData['customer_code'] . $pdfData['customer_id'] . date('dmY') . '.pdf';
						$fileNameDir 	  = preg_replace('/(\/+)/', '/', $dirName . $fileNameDownload);
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						$pdf->save($fileNameDir);
						return array($fileNameDownload, $fileNameDir);
					}
				}
			}
		}
	}

	/**
	 * function for Saving and downloadingPDF in DB
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function downloadSaveMailPDF($contentId, $contentType = 'order')
	{
         
		global $order, $report, $models, $invoice, $mail, $numbersToWord, $creditNote;

		if (!empty($contentId)) {

			$viewData = array();

			if ($contentType == 'report') {

				//directory Path
				$dirName      		   		= DOC_ROOT . REPORT_PATH;
				$reportPrefix 		   		= REPORT_PREFIX;
				$viewFileName 		   		= 'sales.reports.generateReport';
				$reportContentId           	= !empty($contentId['order_id']) ? $contentId['order_id'] : '0';
				$hasContainHeaderFooter    	= !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';
				$sendMailToPartType        	= !empty($contentId['sendMailType']) ? $contentId['sendMailType'] : '0';
				$hasContainEquipment       	= !empty($contentId['reportWithEquipment']) ? $contentId['reportWithEquipment'] : '0';
				$notContainEquipment       	= !empty($contentId['reportWithoutEquipment']) ? $contentId['reportWithoutEquipment'] : '0';
				$nablReportGenerationType  	= !empty($contentId['nabl_report_generation_type']) ? $contentId['nabl_report_generation_type'] : '0';
				$reportWithRightLogo       	= !empty($contentId['reportWithRightLogo']) ? $contentId['reportWithRightLogo'] : '0';
				$notContainLimit 	   		= !empty($contentId['reportWithoutLimit']) ? $contentId['reportWithoutLimit'] : '0';
				$withOutPartWiseReport     	= !empty($contentId['withoutPartwiseReport']) ? $contentId['withoutPartwiseReport'] : '0';
				$signatureTypeStatus       	= !empty($contentId['signatureType']) ? $contentId['signatureType'] : '0';
				$reportWithOutForm50Format 	= !empty($contentId['reportWithOutForm50Format']) ? $contentId['reportWithOutForm50Format'] : '0';
				$withOrWithoutSignature    	= ($hasContainHeaderFooter == '1' && $signatureTypeStatus == '8') || ($hasContainHeaderFooter == '3' && $signatureTypeStatus == '10') ? '1' : '0';
				$reportWithDisciplineGroup 	= !empty($contentId['reportWithDisciplineGroup']) ? $contentId['reportWithDisciplineGroup'] : '0';
				$reportWithEICFormat       	= !empty($contentId['reportWithEICFormat']) ? $contentId['reportWithEICFormat'] : '0';
				$reportWithoutForm39Format  = !empty($contentId['reportWithoutForm39Format']) ? $contentId['reportWithoutForm39Format'] : '0';
				$reportCosmeticWithForm39Format = !empty($contentId['reportCosmeticWithForm39Format']) ? $contentId['reportCosmeticWithForm39Format'] : '0';
				$nablLogoHeaderMarginStatus	= !empty($reportWithRightLogo) && in_array($reportWithRightLogo, array('17')) ? '0' : '1';

				if ($reportContentId) {

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}
					$pdfData = $order->generateReportDetail($reportContentId, $contentId);
					$pdfData = $models->customizeReportDetail($pdfData, $contentId);
                    //dd($pdfData);
					if (!empty($pdfData) && is_dir($dirName)) {

						$fileNameDownload = $pdfData['order']['order_no'] . '.pdf';
						$fileNameDir 	  = preg_replace('/(\/+)/', '/', $dirName . $fileNameDownload);
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						view()->share('hasContainEquipment', $hasContainEquipment);
						view()->share('notContainEquipment', $notContainEquipment);
						view()->share('notContainLimit', $notContainLimit);
						view()->share('withOutPartWiseReport', $withOutPartWiseReport);
						view()->share('withOrWithoutSignature', $withOrWithoutSignature);
						view()->share('reportWithOutForm50Format', $reportWithOutForm50Format);
						view()->share('reportWithDisciplineGroup', $reportWithDisciplineGroup);
						view()->share('reportWithRightLogo', $reportWithRightLogo);
						view()->share('nablLogoHeaderMarginStatus', $nablLogoHeaderMarginStatus);
						view()->share('reportWithEICFormat', $reportWithEICFormat);
						view()->share('reportCosmeticWithForm39Format', $reportCosmeticWithForm39Format);
						view()->share('reportWithoutForm39Format', $reportWithoutForm39Format);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);

						//Sending Report Mail To Customer/Party**********************
						if ($sendMailToPartType == '4' && $pdf->save($fileNameDir)) {
							try {
								if ($mail->sendMail(array('postedData' => $contentId, 'order_id' => $reportContentId, 'mailTemplateType' => '3'))) {
									//File::delete($fileNameDir);
									$report->updateReportTypeOnReportGeneration($reportContentId, $hasContainHeaderFooter);
									return $pdf->stream($fileNameDownload, array('Attachment' => 0));
								} else {
									Session::put('errorMsg', config('messages.message.mailSendErrorMsg'));
									return redirect('dashboard');
								}
							} catch (Exception $ex) {
								Session::put('errorMsg', config('messages.message.mailSendErrorMsg'));
								return redirect('dashboard');
							}
						} else {
							return $pdf->stream($fileNameDownload, array('Attachment' => 0));
						}
					}
				}
			} else if ($contentType == 'invoice') {

				//directory Path
				$dirName      		= DOC_ROOT . INVOICE_PATH;
				$viewFileName 	    	= 'sales.invoices.generateInvoice';
				$invoiceContentId       = !empty($contentId['invoice_id']) ? $contentId['invoice_id'] : '0';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';
				$sendMailToPartType     = !empty($contentId['sendMailType']) ? $contentId['sendMailType'] : '0';
				$invoiceTemplateType    = !empty($contentId['invoiceTemplateType']) ? $contentId['invoiceTemplateType'] : '0';

				if ($invoiceContentId) {

					if (!is_dir($dirName)) {
						mkdir($dirName, 0777, true);
					}
					$pdfData = $invoice->getInvoiceData($invoiceContentId);

					if (!empty($pdfData) && is_dir($dirName)) {

						$fileNameDownload = $pdfData['invoiceHeader']['invoice_no'] . '.pdf';
						$fileNameDir 	  = preg_replace('/(\/+)/', '/', $dirName . $fileNameDownload);
						view()->share('invoiceTemplateType', $invoiceTemplateType);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						view()->share('viewData', $pdfData);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);

						//Sending Report Mail To Customer/Party**********************
						if ($sendMailToPartType == '3' && $pdf->save($fileNameDir)) {
							try {
								if ($mail->sendMail(array('invoice_id' => $invoiceContentId, 'mailTemplateType' => '4'))) {
									//File::delete($fileNameDir);
									return $pdf->stream($fileNameDownload, array('Attachment' => 0));
								} else {
									Session::put('errorMsg', config('messages.message.mailSendErrorMsg'));
									return redirect('dashboard');
								}
							} catch (Exception $ex) {
								Session::put('errorMsg', config('messages.message.mailSendErrorMsg'));
								return redirect('dashboard');
							}
						} else {
							return $pdf->stream($fileNameDownload, array('Attachment' => 0));
						}
					}
				}
			}
		}
	}

	/**
	 * function for downloadingPDF in DB
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function downloadPDF($contentId, $contentType = 'order')
	{

		global $order, $report, $models, $invoice, $mail, $numbersToWord, $trfHdr;

		if (!empty($contentId)) {

			$viewData = array();

			if ($contentType == 'order') {

				//directory Path
				$jobOrderPrefix 		= JOB_ORDER_PREFIX;
				$viewFileWithoutPartyDetail = 'sales.order_master.generateJobOrderWithoutPartyDetail';
				$viewFileWithPartyDetail   	= 'sales.order_master.generateJobOrderWithPartyDetail';
				$reportContentId        	= !empty($contentId['order_id']) ? $contentId['order_id'] : '0';
				$hasContainHeaderFooter 	= !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';
				$headerFooterFilePrefix 	= $hasContainHeaderFooter == '1' ? '-hf' : '-whf';
				$viewFileName			= $hasContainHeaderFooter == '1' ? $viewFileWithoutPartyDetail : $viewFileWithPartyDetail;

				if ($reportContentId) {
					$pdfData = $order->viewOrderDetail($reportContentId);
					if (!empty($pdfData)) {
						$fileNameDownload = $jobOrderPrefix . $pdfData['order']['order_no'] . '.pdf';
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						return $pdf->stream($fileNameDownload, array('Attachment' => 0));
					}
				}
			} else if ($contentType == 'jobSheet') {

				//directory Path
				$rootDir			= defined('ROOT_DIR') ? ROOT_DIR : '/var/www/html/itclims';
				//dd($rootDir,url('/public/images/template_logo.png'));
				$viewFileName   		= 'schedulings.jobPrint.generateAnalyticalSheet';
				$reportContentId                = !empty($contentId['order_id']) ? $contentId['order_id'] : '0';
				$containAnalyticalOrCalculation = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';
				$analyticalCalculationPrefix    = $containAnalyticalOrCalculation == '1' ? '-as' : '-cs';
                
				if ($reportContentId) {
					$pdfData = $order->viewOrderDetail($reportContentId);
					//dd($pdfData);
					if (!empty($pdfData)) {
						$fileNameDownload = $pdfData['order']['order_no'] . $analyticalCalculationPrefix . '.pdf';
						view()->share('containAnalyticalOrCalculation', $containAnalyticalOrCalculation);
						view()->share('rootDir', $rootDir);
						view()->share('viewData', $pdfData);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						return $pdf->stream($fileNameDownload, array('Attachment' => 0));
					}
				}
			} else if ($contentType == 'report') {

				//directory Path
				$reportPrefix 		= REPORT_PREFIX;
				$viewFileName 		= 'sales.reports.generateReport';
				$reportContentId        = !empty($contentId['order_id']) ? $contentId['order_id'] : '0';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';
				$headerFooterFilePrefix = $hasContainHeaderFooter == '1' ? '-hf' : '-whf';

				if ($reportContentId) {
					$pdfData = $order->viewReportDetail($reportContentId);
					if (!empty($pdfData)) {
						$fileNameDownload = $pdfData['order']['order_no'] . $headerFooterFilePrefix . '.pdf';
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						return $pdf->stream($fileNameDownload, array('Attachment' => 0));
					}
				}
			} else if ($contentType == 'invoice') {

				//directory Path
				$viewFileName 	    	= 'sales.invoices.generateInvoice';
				$invoiceContentId       = !empty($contentId['invoice_id']) ? $contentId['invoice_id'] : '0';
				$hasContainHeaderFooter = !empty($contentId['downloadType']) ? $contentId['downloadType'] : '0';
				$headerFooterFilePrefix = $hasContainHeaderFooter == '1' ? '-hf' : '-whf';

				if ($invoiceContentId) {
					$pdfData = $invoice->getInvoiceData($invoiceContentId);
					if (!empty($pdfData)) {
						$fileNameDownload = $pdfData['invoiceHeader']['invoice_no'] . $headerFooterFilePrefix . '.pdf';
						view()->share('viewData', $pdfData);
						view()->share('hasContainHeaderFooter', $hasContainHeaderFooter);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						return $pdf->stream($fileNameDownload, array('Attachment' => 0));
					}
				}
			} else if ($contentType == 'analystSheet') {

				//directory Path
				$viewFileName = 'schedulings.jobPrint.generateAnalystJobSheetPrint';
				$pdfData = !empty($contentId) ? $contentId : array();
                
				if (!empty($pdfData)) {
					$fileNameDownload = $pdfData['heading'] . '-analyst-sheet-' . date('mY') . '.pdf';
					view()->share('viewData', $pdfData);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'landscape')->setWarnings(false);
					return $pdf->stream($fileNameDownload, array('Attachment' => 0));
				}
			} else if ($contentType == 'product_test') {
				$viewFileName   	= 'master.standard_wise_product_test.product_tests.generateBranchWiseProductTestParameter';
				$pdfData 		= $contentId;

				if (!empty($pdfData)) {
					$testCodeName = end($pdfData['tableBody']);
					$fileName    = $testCodeName['test_code'] . '.pdf';
					view()->share('viewData', $pdfData);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'landscape')->setWarnings(false);
					return $pdf->download($fileName);
				}
			} else if ($contentType == 'report_pendency') {

				$dirName        = DOC_ROOT;
				$reportPrefix   = REPORT_PREFIX;
				$viewFileName  	= 'sales.reports.generateBranchWiseReport';
				$pdfData 	= $contentId;

				if (!empty($pdfData)) {
					$fileNameDownload = 'reports.pdf';
					view()->share('viewData', $pdfData);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'landscape')->setWarnings(false);
					return $pdf->stream($fileNameDownload, array('Attachment' => 0));
				}
			} else if ($contentType == 'ordersheet') {

				$dirName        = DOC_ROOT;
				$reportPrefix   = REPORT_PREFIX;
				$viewFileName  	= 'sales.order_master.generateBranchWiseOrder';
				$pdfData 	= $contentId;

				if (!empty($pdfData)) {
					$fileNameDownload = 'orders.pdf';
					view()->share('viewData', $pdfData);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'landscape')->setWarnings(false);
					return $pdf->stream($fileNameDownload, array('Attachment' => 0));
				}
			} else if ($contentType == 'samplesheet') {

				$dirName        = DOC_ROOT;
				$reportPrefix   = REPORT_PREFIX;
				$viewFileName  	= 'sales.samples.generateBranchWiseSample';
				$pdfData 	= $contentId;

				if (!empty($pdfData)) {
					$fileNameDownload = 'samples.pdf';
					view()->share('viewData', $pdfData);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'landscape')->setWarnings(false);
					return $pdf->stream($fileNameDownload, array('Attachment' => 0));
				}
			} else if ($contentType == 'invoicesheet') {

				$dirName        = DOC_ROOT;
				$reportPrefix   = REPORT_PREFIX;
				$viewFileName  	= 'sales.invoices.generateBranchWiseInvoice';
				$pdfData 	= $contentId;

				if (!empty($pdfData)) {
					$fileNameDownload = 'invoices.pdf';
					view()->share('viewData', $pdfData);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'landscape')->setWarnings(false);
					return $pdf->stream($fileNameDownload, array('Attachment' => 0));
				}
			} else if ($contentType == 'invoiceWithReports') {

				//directory Path
				$reportPrefix 	  = REPORT_PREFIX;
				$viewFileName 	  = 'sales.reports.generateReport';
				$reportContentId  = !empty($contentId['order_id']) ? $contentId['order_id'] : '0';

				if ($reportContentId) {
					if (!empty($reportContentId) && is_array($reportContentId)) {
						foreach ($reportContentId as $key => $reportId) {
							$pdfData[$key] = $order->viewReportDetail($reportId);
						}
					}
					if (!empty($pdfData)) {
						$fileNameDownload = $contentType . '_' . date('dmYHis') . '.pdf';
						view()->share('hasContainHeaderFooter', '3');
						view()->share('viewData', $pdfData);
						view()->share('withOrWithoutSignature', '1');
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						return $pdf->stream($fileNameDownload, array('Attachment' => 0));
					}
				}
			} else if ($contentType == 'STF') {

				$dirName        = DOC_ROOT;
				$reportPrefix   = REPORT_PREFIX;
				$viewFileName  	= 'sales.stability_orders.generateBranchWiseSTFReport';
				$pdfData 	= $contentId;

				if (!empty($pdfData)) {
					$fileNameDownload = 'STF-' . $pdfData['part_a']['prototype_no'] . '.pdf';
					view()->share('viewData', $pdfData);
					$pdf = PDF::loadView($viewFileName);
					$pdf->setPaper('a4', 'landscape')->setWarnings(false);
					return $pdf->stream($fileNameDownload, array('Attachment' => 0));
				}
			} else if ($contentType == 'TRF') {

				//directory Path
				$rootDir         = defined('DOC_ROOT') ? DOC_ROOT : '/var/www/html/itcerp/';
				$viewFileName  	 = 'sales.trfs.generateTRFSheet';
				$trfContentId   = !empty($contentId['trf_id']) ? $contentId['trf_id'] : '0';

				if ($trfContentId) {
					$pdfData = $trfHdr->viewTrfDetail($trfContentId);
					if (!empty($pdfData)) {
						$fileNameDownload = $pdfData['trfHdr']['trf_no'] . '.pdf';
						view()->share('rootDir', $rootDir);
						view()->share('viewData', $pdfData);
						$pdf = PDF::loadView($viewFileName);
						$pdf->setPaper('a4', 'portrait')->setWarnings(false);
						return $pdf->stream($fileNameDownload, array('Attachment' => 0));
					}
				}
			}
		}
	}

	/**
	 * function for EXCEL
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function generateExcel($documentData)
	{

		// file name for download
		$fileNamePrefix = !empty($documentData['mis_report_name']) ? str_replace(' ', '_', strtolower($documentData['mis_report_name'])) : 'reports';

		$headingPrefix  = !empty($documentData['heading']) ? trim($documentData['heading']) : 'Reports';
		$fileName       = $fileNamePrefix . '_' . date('Ymd') . ".xls";

		// headers for download
		setlocale(LC_MONETARY, 'en_IN.UTF-8');
		header("Content-Disposition: attachment; filename=\"$fileName\"");
		header("Content-Type: application/vnd.ms-excel;");

		//display Heading names
		echo $headingPrefix . "\n\n";

		//Displaying Extra Detail
		if (!empty($documentData['extraHeading'])) {
			echo $documentData['extraHeading'] . "\n\n";
		}

		//Displaying Search Criteria
		if (!empty($documentData['search_key'])) {
			echo 'SEARCH CRITERIA :' . "\n";
			$searchCriteriaKey  = $documentData['search_key'];
			$searchCriteriaKey = array_map(function ($searchCriteriaKey) {
				return $this->filterTableHead($searchCriteriaKey);
			}, $searchCriteriaKey);
			echo implode("\t", $searchCriteriaKey) . "\n";		//display column names as first row
			$searchCriteriavalue  = $documentData['search_value'];
			echo implode("\t", array_values($searchCriteriavalue)) . "\n\n";	//display column names as first row
		}

		//Table Header Name
		$headingData = $documentData['tableHead'];
		$headingData = array_map(function ($headingData) {
			return $this->filterTableHead($headingData);
		}, $headingData);
		echo implode("\t", $headingData) . "\n";	//display column names as first row

		//Table Body Data
		if (!empty($documentData['tableBody'])) {
			foreach ($documentData['tableBody'] as $row) {
				$row = $this->filterTableBody($row);
				echo implode("\t", array_values($row)) . "\n";
			}
		}

		//display one blank TR
		echo "\n";

		//Table Foot Data
		if (!empty($documentData['tablefoot'])) {
			foreach ($documentData['tablefoot'] as $key => $row) {
				echo implode("\t", array_values($row)) . "\n";
			}
		}

		//display one blank TR
		echo "\n";

		//Table Foot Data
		if (!empty($documentData['summary'])) {
			foreach ($documentData['summary'] as $key => $row) {
				echo implode("\t", array_values($row)) . "\n";
			}
		}
		exit;
	}

	/**
	 * function for downloadingPDF in DB
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function downloadExcel($documentData, $submitedData)
	{

		// file name for download
		$fileNamePrefix = !empty($submitedData['mis_report_name']) ? trim($submitedData['mis_report_name']) : 'reports';

		$headingPrefix  = !empty($submitedData['heading']) ? trim($submitedData['heading']) : 'Reports';
		$fileName       = $fileNamePrefix . '_' . date('Ymd') . ".xls";

		// headers for download
		setlocale(LC_MONETARY, 'en_IN.UTF-8');
		header("Content-Disposition: attachment; filename=\"$fileName\"");
		header("Content-Type: application/vnd.ms-excel");

		//display Heading names
		echo $headingPrefix . "\n\n";

		//Displaying Extra Detail
		if (!empty($documentData['extraHeading'])) {
			echo $documentData['extraHeading'] . "\n\n";
		}

		//Table Header Name
		$headingData = $documentData['tableHead'];

		$headingData = array_map(function ($headingData) {
			return $this->filterTableHead($headingData);
		}, $headingData);
		echo implode("\t", $headingData) . "\n";	//display column names as first row

		//Table Body Data
		if (!empty($documentData['tableBody'])) {
			foreach ($documentData['tableBody'] as $row) {
				$row = $this->filterTableBody($row);
				echo implode("\t", array_values($row)) . "\n";
			}
		}

		//display one blank TR
		echo "\n";

		//Table Foot Data
		if (!empty($documentData['tablefoot'])) {
			foreach ($documentData['tablefoot'] as $key => $row) {
				echo implode("\t", array_values($row)) . "\n";
			}
		}
		exit;
	}

	/**
	 * function for downloadingPDF in DB
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function saveExcel($documentData, $path)
	{

		$excelData = array();

		// file name for download
		$fileName = !empty($documentData['file_name']) ? trim($documentData['file_name'] . ".xls") : 'reports.xls';

		//Upload Path
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}
		$uploadPath = $path . '/' . $fileName;

		//display Heading names
		$excelData[] = !empty($documentData['heading']) ? trim($documentData['heading']) : 'Reports';
		$excelData[] = "\n\n";

		//Table Header Name
		$headingData = $documentData['tableHead'];
		$headingData = array_map(function ($headingData) {
			return $this->filterTableHead($headingData);
		}, $headingData);
		$excelData[] = implode("\t", $headingData) . "\n";	//display column names as first row

		//Table Body Data
		if (!empty($documentData['tableBody'])) {
			foreach ($documentData['tableBody'] as $row) {
				$row = $this->filterTableBody($row);
				$excelData[] = implode("\t", array_values($row)) . "\n";
			}
		}

		//display one blank TR
		$excelData[] = "\n";

		//Table Foot Data
		if (!empty($documentData['tablefoot'])) {
			foreach ($documentData['tablefoot'] as $key => $row) {
				$excelData[] = implode("\t", array_values($row)) . "\n";
			}
		}
		return file_put_contents($uploadPath, $excelData) ? $uploadPath : '';
	}

	/**sort array in asc order
	 *
	 * @return \Illuminate\Http\Response
	 */
	function filterTableHead($tableHead)
	{
		return htmlspecialchars_decode(str_replace('_', ' ', strtoupper($tableHead)));
	}

	/**sort array in asc order
	 *
	 * @return \Illuminate\Http\Response
	 */
	function filterTableBody($tableBody)
	{
		$returnBodyArray = array();
		foreach ($tableBody as $key => $value) {
			if (strpos($value, '|') !== false) {
				$stripValue = explode('|', $value);
				$value      = !empty($stripValue) ? $stripValue[0] : $value;
			}
			$returnBodyArray[] = trim(preg_replace('/\s+/', ' ', strip_tags($value)));
		}
		return $returnBodyArray;
	}

	/**
	 * function to customize Report Detail
	 * Created By : Praveen Singh
	 * Created On : 20-Dec-2017
	 * @return \Illuminate\Http\Response
	 */
	public function customizeReportDetail_v1($pdfData, $customizeArray = array())
	{
		global $order, $report, $models, $invoice, $mail, $numbersToWord, $creditNote;

		if (!empty($customizeArray)) {
			if (!empty($customizeArray['reportWithRightLogo']) || (!empty($pdfData['order']['nabl_no']) && $pdfData['order']['has_order_fp_nabl_scope'] == 'F')) {
				$pdfData['order']['header_content'] = str_replace("rightSection", "", $pdfData['order']['header_content']);
			}
		}
		return $pdfData;
	}

	/**
	 * function to customize Report Detail
	 * Created By : Praveen Singh
	 * Modified On : 28-Nov-2018,25-June-2021
	 */
	public function customizeReportDetail($pdfData, $customizeArray = array())
	{
		global $order, $report, $models, $invoice, $mail, $numbersToWord, $creditNote;

		if (!empty($customizeArray)) {
			if ((!empty($customizeArray['reportWithRightLogo']) && in_array($customizeArray['reportWithRightLogo'], array('7', '16'))) || (!empty($pdfData['order']['nabl_no']) && $pdfData['order']['has_order_fp_nabl_scope'] == 'F')) {
				$pdfData['order']['header_content'] 				= str_replace("rightSection", "", $pdfData['order']['header_content']);
				$pdfData['order']['reportWithRightLogoWithoutNabl'] = !empty($customizeArray['reportWithRightLogo']) && in_array($customizeArray['reportWithRightLogo'], array('7', '16')) ? '1' : '0';
			}
			//For Pharma Department Only
			if (!empty($pdfData['order']['division_id']) && !empty($pdfData['order']['product_category_id']) && $pdfData['order']['division_id'] == '1' && $pdfData['order']['product_category_id'] == '2') {
				$headerTypetext = $order->getHeaderTypeTextbasedOnCustomerTypes($pdfData['order']['division_id'], $pdfData['order']['product_category_id'], $pdfData['order']['customer_id']);
				$pdfData['order']['header_content'] = str_replace("PH_HEADER_TYPE_VAR", $headerTypetext, $pdfData['order']['header_content']);
			}
			//Report Without Form39
			if (!empty($customizeArray['reportWithoutForm39Format'])) {
				$pdfData['order']['header_content'] = str_replace("NOT_REQUIRED", "hidden", $pdfData['order']['header_content']);
			}
		}
		return $pdfData;
	}

	/**
	 * get product test parameter and parameter category list.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getProductTestParametersList($test_id)
	{

		$returnData = array();

		$testDetails = DB::table('product_test_hdr')
			->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
			->join('test_standard', 'test_standard.test_std_id', 'product_test_hdr.test_standard_id')
			->where('product_test_hdr.test_id', $test_id)
			->first();

		$productTestParametersList = DB::table('product_test_dtl')
			->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
			->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
			->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', 'test_parameter.test_parameter_category_id')
			->leftJoin('equipment_type', 'equipment_type.equipment_id', 'product_test_dtl.equipment_type_id')
			->leftJoin('method_master', 'method_master.method_id', 'product_test_dtl.method_id')
			->select('product_test_dtl.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'equipment_type.equipment_name', 'method_master.method_name', 'test_parameter_categories.test_para_cat_id', 'test_parameter_categories.test_para_cat_name', 'test_parameter_categories.category_sort_by')
			->where('product_test_dtl.test_id', $test_id)
			->orderBy('product_test_dtl.parameter_sort_by', 'asc')
			->get();

		$categoryWiseParamenter = array();
		if (!empty($productTestParametersList)) {
			foreach ($productTestParametersList as $key => $values) {
				$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']     = $values->category_sort_by;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']     = $values->test_para_cat_id;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']     = $values->test_para_cat_name;
				$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] = $values;
			}
			$categoryWiseParamenter = array_values($categoryWiseParamenter);
		}

		$testParameterList = $this->sortArrayAscOrder($categoryWiseParamenter);

		if (!empty($testDetails) && !empty($testParameterList)) {
			$returnData['testDetails'] = $testDetails;
			$returnData['testParameterList'] = $testParameterList;
			$returnData  = json_decode(json_encode($returnData), true);
		}
		return $returnData;
	}

	/**sort array in asc order
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sortArrayAscOrder($dataArray)
	{
		$sortedArr = array();
		foreach ($dataArray as $key => $row) {
			$sortedArr[$key] = $row;
		}
		array_multisort($sortedArr, SORT_ASC, $dataArray);
		return $sortedArr;
	}

	//get table unique id by table field name
	public function getTableUniqueIdByName($table_name, $field_name, $field_value, $return_field)
	{
		if (!empty($field_value)) {
			$data = DB::table($table_name)->where($field_name, '=', $field_value)->first();
			if (isset($data->$return_field)) {
				return $data->$return_field;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Creating date collection between two dates
	 * @param string since any date, time or datetime format
	 * @param string until any date, time or datetime format
	 * @param string step
	 * @param string date of output format
	 * @return array
	 */
	function date_range($firstDate, $lastDate, $step = '+1 day', $format = 'Y-m-d')
	{

		$dates   = array();
		$current = strtotime($firstDate);
		$last    = strtotime($lastDate);
		while ($current <= $last) {
			$dates[] = date($format, $current);
			$current = strtotime($step, $current);
		}
		return $dates;
	}

	/**
	 * Creating date collection between two dates
	 * @param string since any date, time or datetime format
	 * @param string until any date, time or datetime format
	 * @param string step
	 * @param string date of output format
	 * @return array
	 */
	function itc_get_number_of_days($firstDate, $lastDate, $format = '%a')
	{
		$first_date = date_create($firstDate);
		$last_date  = date_create($lastDate);
		$date_diff  = date_diff($first_date, $last_date);
		return $date_diff->format($format);
	}

	/**
	 * Creating date collection between two dates
	 * @param string since any date, time or datetime format
	 * @param string until any date, time or datetime format
	 * @param string step
	 * @param string date of output format
	 * @return array
	 */
	function add_days_in_date($date, $days, $format = 'Y-m-d')
	{
		return date($format, strtotime($date . ' + ' . $days . ' days'));
	}

	/**
	 * Creating date collection between two dates
	 * @param string since any date, time or datetime format
	 * @param string until any date, time or datetime format
	 * @param string step
	 * @param string date of output format
	 * @return array
	 */
	function sub_days_in_date($date, $days, $format = 'Y-m-d')
	{
		return date($format, strtotime($date . ' - ' . $days . ' days'));
	}

	/**
	 * Creating date collection between two dates
	 * @param string since any date, time or datetime format
	 * @param string until any date, time or datetime format
	 * @param string step
	 * @param string date of output format
	 * @return array
	 */
	public function sub_days_between_two_date($fromDate, $toDate)
	{
		$daysLeft = 0;
		$daysLeft = abs(strtotime($fromDate) - strtotime($toDate));
		$days     = round($daysLeft / (60 * 60 * 24));
		return $days;
	}

	/**
	 * Creating date collection between two dates
	 * @param string since any date, time or datetime format
	 * @param string until any date, time or datetime format
	 * @param string step
	 * @param string date of output format
	 * @return array
	 */
	function month_range($firstDate, $lastDate, $format = 'd-m-Y')
	{

		$output = array();
		$time   = strtotime($firstDate);
		$last   = date('m-Y', strtotime($lastDate));

		do {
			$month     = date('m-Y', $time);
			$monthData = date($format, $time);
			$output[]  = $monthData;
			$time      = strtotime('+1 month', $time);
		} while ($month != $last);

		return $output;
	}

	/*
    * @author : Praveen Singh
    * @method : date_hour_min_sec_ago
    */
	function date_hour_min_sec_ago($fromDate, $toDate)
	{

		$string = "";

		//global  MONTHS, MONTH, DAYS, DAY, YEARS, YEAR, MINUTE, MINS, WEEK, WEEKS;
		$time = $this->__dateDiffNew($fromDate, $toDate);

		if (array_key_exists('year', $time)) {
			$string .= $time['year'];
			if (array_key_exists('month', $time)) {
				$string .= $time['month'];
			}
		} else if (array_key_exists('month', $time)) {
			if (array_key_exists('week', $time)) {
				if ($time['week'] > 3) {
					$time['month'] = $time['month'] + 1;
				}
			}
			if ($time['month'] != 1)
				$string .= $time['month'] . " " . 'Months';
			else
				$string .= $time['month'] . " " . 'Month';
		} else if (array_key_exists('week', $time)) {
			if (array_key_exists('day', $time)) {
				if ($time['day'] > 3) {
					$time['week'] = $time['week'] + 1;
				}
			}
			if ($time['week'] != 1)
				$string .= $time['week'] . " " . 'Weeks';
			else
				$string .= $time['week'] . " " . 'Week';
		} else if (array_key_exists('day', $time)) {
			if (array_key_exists('hour', $time)) {
				if ($time['hour'] > 13) {
					$time['day'] = $time['day'] + 1;
				}
			}
			if ($time['day'] != 1)
				$string .= $time['day'] . " " . 'Days';
			else
				$string .= $time['day'] . " " . 'Day';
		} else if (array_key_exists('hour', $time)) {
			if (array_key_exists('minute', $time)) {
				if ($time['minute'] > 30) {
					$time['hour'] = $time['hour'] + 1;
				}
			}
			if ($time['hour'] != 1)
				$string .= $time['hour'] . " " . 'Hrs';
			else
				$string .= $time['hour'] . " " . 'Hr';
		} else if (array_key_exists('minute', $time)) {
			if ($time['minute'] != 1)
				$string .= $time['minute'] . " " . 'Mins';
			else
				$string .= $time['minute'] . " " . 'Min';
		} else {
			$string .= "0 " . 'Min';
		}
		return $string;
	}

	/*
    * @author : Praveen Singh
    * @method : date_hour_min_sec_ago
    */
	function date_hour_min_sec_ago_v1($fromDate, $toDate)
	{

		$string = "";

		//global  MONTHS, MONTH, DAYS, DAY, YEARS, YEAR, MINUTE, MINS, WEEK, WEEKS;
		$time = $this->__dateDiffNew($fromDate, $toDate);

		if (array_key_exists('year', $time)) {
			$string .= $time['year'];
			if (array_key_exists('month', $time)) {
				if ($time['month'] != 1)
					$string .= " " . $time['month'] . " " . 'Months';
				else
					$string .= " " . $time['month'] . " " . 'Month';
			}
		} else if (array_key_exists('month', $time)) {
			if ($time['month'] != 1)
				$string .= $time['month'] . " " . 'Months';
			else
				$string .= $time['month'] . " " . 'Month';

			if (array_key_exists('week', $time)) {
				if ($time['week'] != 1)
					$string .= " " . $time['week'] . " " . 'Weeks';
				else
					$string .= " " . $time['week'] . " " . 'Week';
			}
		} else if (array_key_exists('week', $time)) {
			if ($time['week'] != 1)
				$string .= $time['week'] . " " . 'Weeks';
			else
				$string .= $time['week'] . " " . 'Week';

			if (array_key_exists('day', $time)) {
				if ($time['day'] != 1)
					$string .= " " . $time['day'] . " " . 'Days';
				else
					$string .= " " . $time['day'] . " " . 'Day';
			}
		} else if (array_key_exists('day', $time)) {
			if ($time['day'] != 1)
				$string .= $time['day'] . " " . 'Days';
			else
				$string .= $time['day'] . " " . 'Day';

			if (array_key_exists('hour', $time)) {
				if ($time['hour'] != 1)
					$string .= " " . $time['hour'] . " " . 'Hrs';
				else
					$string .= " " . $time['hour'] . " " . 'Hr';
			}
		} else if (array_key_exists('hour', $time)) {
			if ($time['hour'] != 1)
				$string .= $time['hour'] . " " . 'Hrs';
			else
				$string .= $time['hour'] . " " . 'Hr';

			if (array_key_exists('minute', $time)) {
				if ($time['minute'] != 1)
					$string .= " " . $time['minute'] . " " . 'Mins';
				else
					$string .= " " . $time['minute'] . " " . 'Min';
			}
		} else if (array_key_exists('minute', $time)) {
			if ($time['minute'] != 1)
				$string .= $time['minute'] . " " . 'Mins';
			else
				$string .= $time['minute'] . " " . 'Min';

			if (array_key_exists('second', $time)) {
				if ($time['second'] != 1)
					$string .= " " . $time['second'] . " " . 'Secs';
				else
					$string .= " " . $time['second'] . " " . 'Sec';
			}
		} else if (array_key_exists('second', $time)) {
			if ($time['second'] != 1)
				$string .= $time['second'] . " " . 'Secs';
			else
				$string .= $time['second'] . " " . 'Sec';
		}
		return $string;
	}

	/*
    * @author        Praveen Singh
    * @method        __dateDiffNew
    */
	function __dateDiffNew($time1, $time2, $precision = 6)
	{

		$count = 0;
		$times = array();

		// Returns difference as days, months ,weeks, seconds ,minutes etc.
		// If not numeric then convert texts to unix timestamps
		if (!is_int($time1)) {
			$time1 = strtotime($time1);
		}
		if (!is_int($time2)) {
			$time2 = strtotime($time2);
		}

		// Then swap time1 and time2
		if ($time1 > $time2) {
			$ttime = $time1;
			$time1 = $time2;
			$time2 = $ttime;
		} else {
			return $times;
		}

		// Set up intervals and diffs arrays
		$intervals = array('year', 'month', 'week', 'day', 'hour', 'minute', 'second');
		$diffs     = array();

		// Loop thru all intervals
		foreach ($intervals as $interval) {
			// Set default diff to 0
			$diffs[$interval] = 0;
			// Create temp time from time1 and interval
			$ttime = strtotime("+1 " . $interval, $time1);
			// Loop until temp time is smaller than time2
			while ($time2 >= $ttime) {
				$time1 = $ttime;
				$diffs[$interval]++;
				// Create new temp time from time1 and interval
				$ttime = strtotime("+1 " . $interval, $time1);
			}
		}

		// Loop thru all diffs
		foreach ($diffs as $interval => $value) {
			// Break if we have needed precission
			if ($count >= $precision) {
				break;
			}
			// Add value and interval
			// if value is bigger than 0
			if ($value > 0) {
				// Add value and interval to times array
				$times[$interval] = $value;
				$count++;
			}
		}
		return $times;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function formDataVariables($formData, $invoicingTypeId)
	{

		$returnData 			= array();
		$cirCustomerId 			= !empty($formData['cir_customer_id']) ? $formData['cir_customer_id'] : '';
		$cirProductCategoryId 		= !empty($formData['cir_product_category_id']) ? $formData['cir_product_category_id'] : '';
		$cirCustParameterIdData		= !empty($formData['cir_parameter_id']) ? array_values($formData['cir_parameter_id']) : array();
		$equipmentTypeData 		= !empty($formData['cir_equipment_type_id']) ? array_values($formData['cir_equipment_type_id']) : array();
		$testStandardData		= !empty($formData['cir_test_standard_id']) ? array_values($formData['cir_test_standard_id']) : array();
		$cirDivisionId 			= !empty($formData['cir_division_id']) ? $formData['cir_division_id'] : '';

		if (!empty($cirCustomerId) && !empty($formData['invoicing_rate'])) {
			foreach ($formData['invoicing_rate'] as $key => $rate) {
				$returnData[$key]['cir_customer_id']   		= $cirCustomerId;
				$returnData[$key]['invoicing_type_id'] 		= $invoicingTypeId;
				$returnData[$key]['cir_parameter_id']  		= !empty($cirCustParameterIdData[$key]) ? $cirCustParameterIdData[$key] : '';
				$returnData[$key]['invoicing_rate']    		= !empty($rate) ? $rate : '';
				$returnData[$key]['cir_equipment_type_id']  	= !empty($equipmentTypeData[$key]) ? $equipmentTypeData[$key] : '';
				$returnData[$key]['cir_test_standard_id']  	= !empty($testStandardData[$key]) ? $testStandardData[$key] : '';
				$returnData[$key]['cir_product_category_id']	= $cirProductCategoryId;
				$returnData[$key]['created_by']  		= USERID;
				$returnData[$key]['cir_division_id']   		= $cirDivisionId;
			}
		}
		return $returnData;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getRequirementSTDFromToNew($values, $stdFrom, $stdTo)
	{
		if (!empty($values)) {
			if (!empty($stdFrom) && !empty($stdTo)) {
				if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) == 'n/a') {
					$values->requirement_from_to = '';
				} else if (strtolower($values->standard_value_type) == 'numeric') {
					$values->requirement_from_to = round($stdFrom, 3) . '-' . round($stdTo, 3);
				} else if (strtolower($values->standard_value_type) == 'alphanumeric') {
					$values->requirement_from_to = trim($stdFrom) . '-' . round($stdTo, 3);
				} else if (strtolower($values->standard_value_type) == 'na') {
					$values->requirement_from_to = trim($stdFrom) . '-' . trim($stdTo);
				}
			} else if (!empty($stdFrom) && empty($stdTo)) {
				if (strtolower($stdFrom) == 'n/a') {
					$values->requirement_from_to = '';
				} else if (is_numeric($stdFrom)) {
					$values->requirement_from_to = round($stdFrom, 3);
				} else {
					$values->requirement_from_to = trim($stdFrom);
				}
			} else if (empty($stdFrom) && !empty($stdTo)) {
				if (strtolower($stdTo) == 'n/a') {
					$values->requirement_from_to = '';
				} else if (is_numeric($stdTo)) {
					$values->requirement_from_to = round($stdTo, 3);
				} else {
					$values->requirement_from_to = trim($stdTo);
				}
			}
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getRequirementSTDFromTobk13Sept18($values, $stdFrom, $stdTo)
	{
		if (!empty($values)) {
			if ($values->product_category_id == '2') {
				$this->getRequirementSTDFromTo_v3($values, $stdFrom, $stdTo);
			} else {
				$this->getRequirementSTDFromTo_v2($values, $stdFrom, $stdTo);
			}
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getRequirementSTDFromTo($values, $stdFrom, $stdTo)
	{
		$this->getRequirementSTDFromTo_v4($values, $stdFrom, $stdTo);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getRequirementSTDFromTo_v2($values, $stdFrom, $stdTo)
	{
		if (!empty($values)) {
			if (!empty($stdFrom) && !empty($stdTo)) {
				if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) == 'n/a') {
					$values->requirement_from_to = '';
				} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) != 'n/a') {
					if (is_numeric($stdFrom) && is_numeric($stdTo)) {
						$values->requirement_from_to = round($stdFrom, 3) . '-' . round($stdTo, 3);
					} else if (is_numeric($stdFrom) && !is_numeric($stdTo)) {
						$values->requirement_from_to = round($stdFrom, 3) . '-' . $stdTo;
					} else if (!is_numeric($stdFrom) && is_numeric($stdTo)) {
						$values->requirement_from_to = $stdFrom . '-' . round($stdTo, 3);
					} else {
						$values->requirement_from_to = $stdFrom . '-' . $stdTo;
					}
				} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) == 'n/a') {
					if (is_numeric($stdFrom)) {
						$values->requirement_from_to = round($stdFrom, 3);
					} else {
						$values->requirement_from_to = $stdFrom;
					}
				} else if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) != 'n/a') {
					if (is_numeric($stdTo)) {
						$values->requirement_from_to = round($stdTo, 3);
					} else {
						$values->requirement_from_to = $stdTo;
					}
				}
			} else if (!empty($stdFrom) && empty($stdTo)) {
				if (is_numeric($stdFrom)) {
					$values->requirement_from_to = round($stdFrom, 3);
				} else {
					$values->requirement_from_to = $stdFrom;
				}
			} else if (empty($stdFrom) && !empty($stdTo)) {
				if (is_numeric($stdTo)) {
					$values->requirement_from_to = round($stdTo, 3);
				} else {
					$values->requirement_from_to = $stdTo;
				}
			}
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getRequirementSTDFromTo_v3($values, $stdFrom, $stdTo)
	{

		$decimalPlaces = 3;
		$decimalFlag   = 0;
		if (!empty($values->claim_value)) {
			$claimValueArray = explode('.', $values->claim_value);
			if (isset($claimValueArray[1])) {
				$decimalFlag = 1;
				$decimalPlaces = strlen($claimValueArray[1]);
			} else {
				$decimalPlaces = 3;
			}
		}
		if (!empty($stdFrom) && !empty($stdTo)) {
			if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) == 'n/a') {
				$values->requirement_from_to = '';
			} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) != 'n/a') {
				if (is_numeric($stdFrom) && is_numeric($stdTo)) {
					if ($decimalFlag) {
						$standard_value_from_array   = explode('.', $stdFrom);
						$standard_value_to_array     = explode('.', $stdTo);
						$decimal_value_from_pII      = isset($standard_value_from_array[1]) ? '.' . substr($standard_value_from_array[1], 0, $decimalPlaces) : '';
						$decimal_value_to_pII        = isset($standard_value_to_array[1]) ? '.' . substr($standard_value_to_array[1], 0, $decimalPlaces) : '';
						$values->requirement_from_to = $standard_value_from_array[0] . $decimal_value_from_pII . '-' . $standard_value_to_array[0] . $decimal_value_to_pII;
					} else {
						$values->requirement_from_to = round($stdFrom, $decimalPlaces) . '-' . round($stdTo, $decimalPlaces);
					}
				} else if (is_numeric($stdFrom) && !is_numeric($stdTo)) {
					if ($decimalFlag) {
						$standard_value_from_array    = explode('.', $stdFrom);
						$decimal_value_from_pII       = isset($standard_value_from_array[1]) ? '.' . substr($standard_value_from_array[1], 0, $decimalPlaces) : '';
						$values->requirement_from_to  = $standard_value_from_array[0] . $decimal_value_from_pII . '-' . $stdTo;
					} else {
						$values->requirement_from_to = round($stdFrom, $decimalPlaces) . '-' . $stdTo;
					}
				} else if (!is_numeric($stdFrom) && is_numeric($stdTo)) {
					if ($decimalFlag) {
						$standard_value_to_array     = explode('.', $stdTo);
						$decimal_value_to_pII        = isset($standard_value_to_array[1]) ? '.' . substr($standard_value_to_array[1], 0, $decimalPlaces) : '';
						$values->requirement_from_to = $stdFrom . '-' . $standard_value_to_array[0] . $decimal_value_to_pII;
					} else {
						$values->requirement_from_to = $stdFrom . '-' . round($stdTo, $decimalPlaces);
					}
				} else if (!is_numeric($stdFrom) && !is_numeric($stdTo)) {
					$values->requirement_from_to = $stdFrom . '-' . $stdTo;
				}
			} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) == 'n/a') {
				if (is_numeric($stdFrom)) {
					if ($decimalFlag) {
						$standard_value_from_array   = explode('.', $stdFrom);
						$decimal_value_from_pII      = isset($standard_value_from_array[1]) ? '.' . substr($standard_value_from_array[1], 0, $decimalPlaces) : '';
						$values->requirement_from_to = $standard_value_from_array[0] . $decimal_value_from_pII;
					} else {
						$values->requirement_from_to = round($stdFrom, $decimalPlaces);
					}
				} else {
					$values->requirement_from_to = $stdFrom;
				}
			} else if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) != 'n/a') {
				if (is_numeric($stdTo)) {
					if ($decimalFlag) {
						$standard_value_to_array     = explode('.', $stdTo);
						$decimal_value_to_pII        = isset($standard_value_to_array[1]) ? '.' . substr($standard_value_to_array[1], 0, $decimalPlaces) : '';
						$values->requirement_from_to = $standard_value_to_array[0] . $decimal_value_to_pII;
					} else {
						$values->requirement_from_to = round($stdTo, $decimalPlaces);
					}
				} else {
					$values->requirement_from_to = $stdTo;
				}
			}
		} else if (!empty($stdFrom) && empty($stdTo)) {
			if (is_numeric($stdFrom)) {
				if ($decimalFlag) {
					$standard_value_from_array 	 = explode('.', $stdFrom);
					$decimal_value_from_pII 	 = isset($standard_value_from_array[1]) ? '.' . substr($standard_value_from_array[1], 0, $decimalPlaces) : '';
					$values->requirement_from_to = $standard_value_from_array[0] . $decimal_value_from_pII;
				} else {
					$values->requirement_from_to = round($stdFrom, $decimalPlaces);
				}
			} else {
				$values->requirement_from_to = $stdFrom;
			}
		} else if (empty($stdFrom) && !empty($stdTo)) {
			if (is_numeric($stdTo)) {
				if ($decimalFlag) {
					$standard_value_to_array	 = explode('.', $stdTo);
					$decimal_value_to_pII   	 = isset($standard_value_to_array[1]) ? '.' . substr($standard_value_to_array[1], 0, $decimalPlaces) : '';
					$values->requirement_from_to = $standard_value_to_array[0] . $decimal_value_to_pII;
				} else {
					$values->requirement_from_to = round($stdTo, $decimalPlaces);
				}
			} else {
				$values->requirement_from_to = $stdTo;
			}
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getRequirementSTDFromTo_v4($values, $stdFrom, $stdTo)
	{
		if (!empty($values->display_decimal_place)) {
			$stdFrom = trim($stdFrom);
			$stdTo   = trim($stdTo);
			$decimalPlaces = $values->display_decimal_place;
			if (!empty($stdFrom) && !empty($stdTo)) {
				if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) == 'n/a') {
					$values->requirement_from_to = '';
				} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) != 'n/a') {
					if (is_numeric($stdFrom) && is_numeric($stdTo)) {
						if (!empty($values->claim_value) && strpos(floatval($stdFrom), '.') !== false) {
							$stdFrom = $this->returnValue($stdFrom, $decimalPlaces);
						} else {
							$stdFrom = $this->roundValues($stdFrom, $decimalPlaces);
						}
						if (!empty($values->claim_value) && strpos(floatval($stdTo), '.') !== false) {
							$stdTo = $this->returnValue($stdTo, $decimalPlaces);
						} else {
							$stdTo = $this->roundValues($stdTo, $decimalPlaces);
						}
						$values->requirement_from_to = $stdFrom . '-' . $stdTo;
					} else if (is_numeric($stdFrom) && !is_numeric($stdTo)) {
						if (!empty($values->claim_value) && strpos(floatval($stdFrom), '.') !== false) {
							$stdFrom = $this->returnValue($stdFrom, $decimalPlaces);
						} else {
							$stdFrom = $this->roundValues($stdFrom, $decimalPlaces);
						}
						$values->requirement_from_to = $stdFrom . '-' . $stdTo;
					} else if (!is_numeric($stdFrom) && is_numeric($stdTo)) {
						if (!empty($values->claim_value) && strpos(floatval($stdTo), '.') !== false) {
							$stdTo = $this->returnValue($stdTo, $decimalPlaces);
						} else {
							$stdTo = $this->roundValues($stdTo, $decimalPlaces);
						}
						$values->requirement_from_to = $stdFrom . '-' . $stdTo;
					} else if (!is_numeric($stdFrom) && !is_numeric($stdTo)) {
						$values->requirement_from_to = $stdFrom . '-' . $stdTo;
					}
				} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) == 'n/a') {
					if (is_numeric($stdFrom)) {
						if (!empty($values->claim_value) && strpos(floatval($stdFrom), '.') !== false) {
							$stdFrom = $this->returnValue($stdFrom, $decimalPlaces);
						} else {
							$stdFrom = $this->roundValues($stdFrom, $decimalPlaces);
						}
						$values->requirement_from_to = $stdFrom;
					} else {
						$values->requirement_from_to = $stdFrom;
					}
				} else if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) != 'n/a') {
					if (is_numeric($stdTo)) {
						if (!empty($values->claim_value) && strpos(floatval($stdTo), '.') !== false) {
							$stdTo = $this->returnValue($stdTo, $decimalPlaces);
						} else {
							$stdTo = $this->roundValues($stdTo, $decimalPlaces);
						}
						$values->requirement_from_to = $stdTo;
					} else {
						$values->requirement_from_to = $stdTo;
					}
				}
			} else if (!empty($stdFrom) && empty($stdTo)) {
				if (is_numeric($stdFrom)) {
					if (!empty($values->claim_value) && strpos(floatval($stdFrom), '.') !== false) {
						$stdFrom = $this->returnValue($stdFrom, $decimalPlaces);
					} else {
						$stdFrom = $this->roundValues($stdFrom, $decimalPlaces);
					}
					$values->requirement_from_to = $stdFrom;
				} else {
					$values->requirement_from_to = $stdFrom;
				}
			} else if (empty($stdFrom) && !empty($stdTo)) {
				if (is_numeric($stdTo)) {
					if (!empty($values->claim_value) && strpos(floatval($stdTo), '.') !== false) {
						$stdTo = $this->returnValue($stdTo, $decimalPlaces);
					} else {
						$stdTo = $this->roundValues($stdTo, $decimalPlaces);
					}
					$values->requirement_from_to = $stdTo;
				} else {
					$values->requirement_from_to = $stdTo;
				}
			}
		} else {
			$stdFrom = trim($stdFrom);
			$stdTo   = trim($stdTo);
			if (!empty($stdFrom) && !empty($stdTo)) {
				if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) == 'n/a') {
					$values->requirement_from_to = '';
				} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) != 'n/a') {
					$values->requirement_from_to = $stdFrom . '-' . $stdTo;
				} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) == 'n/a') {
					$values->requirement_from_to = $stdFrom;
				} else if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) != 'n/a') {
					$values->requirement_from_to = $stdTo;
				}
			} else if (!empty($stdFrom) && empty($stdTo)) {
				$values->requirement_from_to = $stdFrom;
			} else if (empty($stdFrom) && !empty($stdTo)) {
				$values->requirement_from_to = $stdTo;
			}
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getLevelOfRecoveryFromTestResult($values, $delemeter = '|')
	{
		$test_result = !empty($values->test_result) ? explode('|', $values->test_result) : array();
		$values->test_result = !empty($test_result[0]) ? $test_result[0] : $values->test_result;
		$values->recovery_level = !empty($test_result[1]) ? $test_result[1] : '';
	}

	/**
	 * Display a Rounding of number.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function roundValue($number, $place = '2')
	{
		return number_format((float) $number, $place, '.', '');
	}

	/**
	 * Display a Rounding of number.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function roundValues($number, $place = '2')
	{
		return !empty($number) && !empty(round($number)) ? round($number, $place) : $this->roundValue($number, $place);
	}

	/**
	 * Display a decimal place value without round off of number.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function returnValue($data, $place = '3', $delimater = '.')
	{
		$value = '';
		$dataArray = explode($delimater, $data);
		if (!empty($dataArray)) {
			if (isset($dataArray[0])) $value .= $dataArray[0];
			if (isset($dataArray[1])) $value .= '.' . substr($dataArray[1], 0, $place);
		}
		return $value;
	}

	/**
	 * Display a Rounding of number.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function convertValues($type, $number, $place = '2')
	{
		if ($type == 'T') {
			$number = $number / 1000;
		} elseif ($type == 'L') {
			$number = $number / 10000;
		} else {
			$number = $number;
		}
		return !empty($number) && !empty(round($number)) ? round($number, $place) : $this->roundValue($number, $place);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getSundays($start, $end)
	{
		$timestamp1 = strtotime($start);
		$timestamp2 = strtotime($end);
		$sundays    = array();
		$oneDay     = 60 * 60 * 24;
		for ($i = $timestamp1; $i <= $timestamp2; $i += $oneDay) {
			$day = date('N', $i);
			if ($day == 7) {
				$sundays[] = date('Y-m-d', $i);
				$i += 6 * $oneDay;
			}
		}
		return array_filter($sundays);
	}

	/**
	 * Description : Global Percentage Calculation
	 * Created By : Praveen Singh
	 * Created On : 29-Feb-2020
	 */
	public function globalPercentageCalculation($firstElement, $secondElement, $decimalPlace = '2')
	{
		return !empty($firstElement) && is_numeric($firstElement)
			&& !empty($secondElement) && !empty(round($secondElement))
			? $this->roundValues((($firstElement / $secondElement) * 100), $decimalPlace) : '0.00';
	}

	/**
	 * Description : Global Percentage Calculation
	 * Created By : Praveen Singh
	 * Created On : 29-Feb-2020
	 */
	public function globalCommissionCalculation($firstElement, $secondElement, $decimalPlace = '2')
	{
		return !empty($firstElement) && is_numeric($firstElement)
			&& !empty($secondElement) && is_numeric($secondElement)
			? $this->roundValues((($firstElement * $secondElement) / 100), $decimalPlace) : '0.00';
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function get_date_range_excluding_sundays($firstDate, $lastDate, $step = '+1 day', $format = 'Y-m-d')
	{

		$dayRange = $sundays = array();
		$current  = strtotime($firstDate);
		$last     = strtotime($lastDate);
		while ($current <= $last) {
			$dayRange[] = date($format, $current);
			$current = strtotime($step, $current);
		}
		if (!empty($dayRange)) {
			foreach ($dayRange as $key => $date) {
				$timestamp = strtotime($date);
				$day = date('N', $timestamp);
				if ($day == '7') {
					$sundays[] = $date;
					unset($dayRange[$key]);
				}
			}
			sort($dayRange);
			$firstDate = current($dayRange);
			$lastDate = !empty($sundays) ? date($format, strtotime('+' . count($sundays) . ' day', strtotime(end($dayRange)))) : end($dayRange);
			return array($firstDate, $lastDate);
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function checkDateIsSunday($currentDate)
	{
		return date('N', strtotime($currentDate)) == 7 ? true : false;
	}

	/**
	 * Displaying mail Setting Variables-Booking
	 *
	 * @return \Illuminate\Http\Response
	 */
	function __mailSettingVariablesBooking($productCategoryId)
	{

		$emailTemplateBlade = $fromName = $fromEmail = '';

		if ($productCategoryId == '1') {			//Food Department
			$emailTemplateBlade = 'email.templates.food.emailOrderBooking';
			$fromName 	    = FROM_NAME_FOOD_BOOKING;
			$fromEmail 	    = FROM_EMAIL_FOOD_BOOKING;
		} else if ($productCategoryId == '2') {		//Pharma Department
			$emailTemplateBlade = 'email.templates.pharma.emailOrderBooking';
			$fromName 	    = FROM_NAME_PHARMA_BOOKING;
			$fromEmail 	    = FROM_EMAIL_PHARMA_BOOKING;
		} else if ($productCategoryId == '3') {		//Water Department
			$emailTemplateBlade = 'email.templates.water.emailOrderBooking';
			$fromName 	    = FROM_NAME_WATER_BOOKING;
			$fromEmail 	    = FROM_EMAIL_WATER_BOOKING;
		} else if ($productCategoryId == '4') {		//helmet Department
			$emailTemplateBlade = 'email.templates.helmet.emailOrderBooking';
			$fromName 	    = FROM_NAME_HELMET_BOOKING;
			$fromEmail 	    = FROM_EMAIL_HELMET_BOOKING;
		} else if ($productCategoryId == '5') {		//ayurvedic Department
			$emailTemplateBlade = 'email.templates.ayurvedic.emailOrderBooking';
			$fromName 	    = FROM_NAME_AYURVEDIC_BOOKING;
			$fromEmail 	    = FROM_EMAIL_AYURVEDIC_BOOKING;
		} else if ($productCategoryId == '6') {		//building Department
			$emailTemplateBlade = 'email.templates.building.emailOrderBooking';
			$fromName 	    = FROM_NAME_BUILDING_BOOKING;
			$fromEmail 	    = FROM_EMAIL_BUILDING_BOOKING;
		} else if ($productCategoryId == '7') {		//Textile Department
			$emailTemplateBlade = 'email.templates.textile.emailOrderBooking';
			$fromName 	    = FROM_NAME_TEXTILE_BOOKING;
			$fromEmail 	    = FROM_EMAIL_TEXTILE_BOOKING;
		} else if ($productCategoryId == '8') {		//environment Department
			$emailTemplateBlade = 'email.templates.environment.emailOrderBooking';
			$fromName 	    = FROM_NAME_ENVIRONMENT_BOOKING;
			$fromEmail 	    = FROM_EMAIL_ENVIRONMENT_BOOKING;
		} else {
			$emailTemplateBlade = 'email.templates.food.emailOrderBooking';
			$fromName 	    = FROM_NAME_FOOD_BOOKING;
			$fromEmail 	    = FROM_EMAIL_FOOD_BOOKING;
		}

		return array($emailTemplateBlade, $fromName, $fromEmail);
	}

	/**
	 * Displaying mail Setting Variables-Reporting
	 *
	 * @return \Illuminate\Http\Response
	 */
	function __mailSettingVariablesReports($productCategoryId)
	{

		$emailTemplateBlade = $fromName = $fromEmail = '';

		if ($productCategoryId == '1') {			//Food Department
			$emailTemplateBlade = 'email.templates.food.emailReport';
			$fromName 	    = FROM_NAME_FOOD_REPORT;
			$fromEmail 	    = FROM_EMAIL_FOOD_REPORT;
		} else if ($productCategoryId == '2') {		//Pharma Department
			$emailTemplateBlade = 'email.templates.pharma.emailReport';
			$fromName 	    = FROM_NAME_PHARMA_REPORT;
			$fromEmail 	    = FROM_EMAIL_PHARMA_REPORT;
		} else if ($productCategoryId == '3') {		//Water Department
			$emailTemplateBlade = 'email.templates.water.emailReport';
			$fromName 	    = FROM_NAME_WATER_REPORT;
			$fromEmail 	    = FROM_EMAIL_WATER_REPORT;
		} else if ($productCategoryId == '4') {		//helmet Department
			$emailTemplateBlade = 'email.templates.helmet.emailReport';
			$fromName 	    = FROM_NAME_HELMET_REPORT;
			$fromEmail 	    = FROM_EMAIL_HELMET_REPORT;
		} else if ($productCategoryId == '5') {		//ayurvedic Department
			$emailTemplateBlade = 'email.templates.ayurvedic.emailReport';
			$fromName 	    = FROM_NAME_AYURVEDIC_REPORT;
			$fromEmail 	    = FROM_EMAIL_AYURVEDIC_REPORT;
		} else if ($productCategoryId == '6') {		//building Department
			$emailTemplateBlade = 'email.templates.building.emailReport';
			$fromName 	    = FROM_NAME_BUILDING_REPORT;
			$fromEmail 	    = FROM_EMAIL_BUILDING_REPORT;
		} else if ($productCategoryId == '7') {		//textile Department
			$emailTemplateBlade = 'email.templates.textile.emailReport';
			$fromName 	    = FROM_NAME_TEXTILE_REPORT;
			$fromEmail 	    = FROM_EMAIL_TEXTILE_REPORT;
		} else if ($productCategoryId == '8') {		//environment Department
			$emailTemplateBlade = 'email.templates.environment.emailReport';
			$fromName 	    = FROM_NAME_ENVIRONMENT_REPORT;
			$fromEmail 	    = FROM_EMAIL_ENVIRONMENT_REPORT;
		} else {
			$emailTemplateBlade = 'email.templates.food.emailReport';
			$fromName 	    = FROM_NAME_FOOD_REPORT;
			$fromEmail 	    = FROM_EMAIL_FOOD_REPORT;
		}

		return array($emailTemplateBlade, $fromName, $fromEmail);
	}

	/**
	 * Displaying mail Setting Variables-Invoicing
	 *
	 * @return \Illuminate\Http\Response
	 */
	function __mailSettingVariablesInvoics($productCategoryId)
	{

		$emailTemplateBlade = $fromName = $fromEmail = '';

		if ($productCategoryId == '1') {		//Food Department
			$emailTemplateBlade = 'email.templates.food.emailInvoice';
			$fromName 	    = FROM_NAME_FOOD_INVOICE;
			$fromEmail 	    = FROM_EMAIL_FOOD_INVOICE;
		} else if ($productCategoryId == '2') {	//Pharma Department
			$emailTemplateBlade = 'email.templates.pharma.emailInvoice';
			$fromName 	    = FROM_NAME_PHARMA_INVOICE;
			$fromEmail 	    = FROM_EMAIL_PHARMA_INVOICE;
		} else if ($productCategoryId == '3') {	//Water Department
			$emailTemplateBlade = 'email.templates.water.emailInvoice';
			$fromName 	    = FROM_NAME_WATER_INVOICE;
			$fromEmail 	    = FROM_EMAIL_WATER_INVOICE;
		} else if ($productCategoryId == '4') {		//helmet Department
			$emailTemplateBlade = 'email.templates.helmet.emailInvoice';
			$fromName 	    = FROM_NAME_HELMET_INVOICE;
			$fromEmail 	    = FROM_EMAIL_HELMET_INVOICE;
		} else if ($productCategoryId == '5') {		//helmet Department
			$emailTemplateBlade = 'email.templates.ayurvedic.emailInvoice';
			$fromName 	    = FROM_NAME_AYURVEDIC_INVOICE;
			$fromEmail 	    = FROM_EMAIL_AYURVEDIC_INVOICE;
		} else if ($productCategoryId == '6') {	//building Department
			$emailTemplateBlade = 'email.templates.building.emailInvoice';
			$fromName 	    = FROM_NAME_BUILDING_INVOICE;
			$fromEmail 	    = FROM_EMAIL_BUILDING_INVOICE;
		} else if ($productCategoryId == '7') {	//textile Department
			$emailTemplateBlade = 'email.templates.textile.emailInvoice';
			$fromName 	    = FROM_NAME_TEXTILE_INVOICE;
			$fromEmail 	    = FROM_EMAIL_TEXTILE_INVOICE;
		} else if ($productCategoryId == '8') {	//environment Department
			$emailTemplateBlade = 'email.templates.environment.emailInvoice';
			$fromName 	    = FROM_NAME_ENVIRONMENT_INVOICE;
			$fromEmail 	    = FROM_EMAIL_ENVIRONMENT_INVOICE;
		} else {
			$emailTemplateBlade = 'email.templates.food.emailInvoice';
			$fromName 	    = FROM_NAME_FOOD_INVOICE;
			$fromEmail 	    = FROM_EMAIL_FOOD_INVOICE;
		}

		return array($emailTemplateBlade, $fromName, $fromEmail);
	}

	/**
	 * Displaying mail Setting Variables-Booking
	 *
	 * @return \Illuminate\Http\Response
	 */
	function __mailSettingVariablesStabiltyOrder($productCategoryId)
	{

		$emailTemplateBlade = $fromName = $fromEmail = '';

		if ($productCategoryId == '1') {			//Food Department
			$emailTemplateBlade = 'email.templates.food.emailStbPrototypeOrder';
			$fromName 	    = FROM_NAME_FOOD_BOOKING;
			$fromEmail 	    = FROM_EMAIL_FOOD_BOOKING;
		} else if ($productCategoryId == '2') {		//Pharma Department
			$emailTemplateBlade = 'email.templates.pharma.emailStbPrototypeOrder';
			$fromName 	    		= FROM_NAME_PHARMA_BOOKING;
			$fromEmail 	    = FROM_EMAIL_PHARMA_BOOKING;
		} else if ($productCategoryId == '3') {		//Water Department
			$emailTemplateBlade = 'email.templates.water.emailStbPrototypeOrder';
			$fromName 	    = FROM_NAME_WATER_BOOKING;
			$fromEmail 	    = FROM_EMAIL_WATER_BOOKING;
		} else if ($productCategoryId == '4') {		//helmet Department
			$emailTemplateBlade = 'email.templates.helmet.emailStbPrototypeOrder';
			$fromName 	    = FROM_NAME_HELMET_BOOKING;
			$fromEmail 	    = FROM_EMAIL_HELMET_BOOKING;
		} else if ($productCategoryId == '5') {		//ayurvedic Department
			$emailTemplateBlade = 'email.templates.ayurvedic.emailStbPrototypeOrder';
			$fromName 	    = FROM_NAME_AYURVEDIC_BOOKING;
			$fromEmail 	    = FROM_EMAIL_AYURVEDIC_BOOKING;
		} else if ($productCategoryId == '6') {		//building Department
			$emailTemplateBlade = 'email.templates.building.emailStbPrototypeOrder';
			$fromName 	    = FROM_NAME_BUILDING_BOOKING;
			$fromEmail 	    = FROM_EMAIL_BUILDING_BOOKING;
		} else if ($productCategoryId == '7') {		//Textile Department
			$emailTemplateBlade = 'email.templates.textile.emailStbPrototypeOrder';
			$fromName 	    = FROM_NAME_TEXTILE_BOOKING;
			$fromEmail 	    = FROM_EMAIL_TEXTILE_BOOKING;
		} else if ($productCategoryId == '8') {		//environment Department
			$emailTemplateBlade = 'email.templates.environment.emailStbPrototypeOrder';
			$fromName 	    = FROM_NAME_ENVIRONMENT_BOOKING;
			$fromEmail 	    = FROM_EMAIL_ENVIRONMENT_BOOKING;
		}

		return array($emailTemplateBlade, $fromName, $fromEmail);
	}

	/**
	 * Displaying mail Setting Variables-Booking
	 *
	 * @return \Illuminate\Http\Response
	 */
	function __mailSettingVariablesStabiltyOrderNotification($productCategoryId)
	{

		$emailTemplateBlade = $fromName = $fromEmail = '';

		if ($productCategoryId == '1') {			//Food Department
			$emailTemplateBlade = 'email.templates.food.emailStabilityOrderNotification';
			$fromName 	    = FROM_NAME_FOOD_BOOKING;
			$fromEmail 	    = FROM_EMAIL_FOOD_BOOKING;
		} else if ($productCategoryId == '2') {		//Pharma Department
			$emailTemplateBlade = 'email.templates.pharma.emailStabilityOrderNotification';
			$fromName 	    		= FROM_NAME_PHARMA_BOOKING;
			$fromEmail 	    = FROM_EMAIL_PHARMA_BOOKING;
		} else if ($productCategoryId == '3') {		//Water Department
			$emailTemplateBlade = 'email.templates.water.emailStabilityOrderNotification';
			$fromName 	    = FROM_NAME_WATER_BOOKING;
			$fromEmail 	    = FROM_EMAIL_WATER_BOOKING;
		} else if ($productCategoryId == '4') {		//helmet Department
			$emailTemplateBlade = 'email.templates.helmet.emailStabilityOrderNotification';
			$fromName 	    = FROM_NAME_HELMET_BOOKING;
			$fromEmail 	    = FROM_EMAIL_HELMET_BOOKING;
		} else if ($productCategoryId == '5') {		//ayurvedic Department
			$emailTemplateBlade = 'email.templates.ayurvedic.emailStabilityOrderNotification';
			$fromName 	    = FROM_NAME_AYURVEDIC_BOOKING;
			$fromEmail 	    = FROM_EMAIL_AYURVEDIC_BOOKING;
		} else if ($productCategoryId == '6') {		//building Department
			$emailTemplateBlade = 'email.templates.building.emailStabilityOrderNotification';
			$fromName 	    = FROM_NAME_BUILDING_BOOKING;
			$fromEmail 	    = FROM_EMAIL_BUILDING_BOOKING;
		} else if ($productCategoryId == '7') {		//Textile Department
			$emailTemplateBlade = 'email.templates.textile.emailStabilityOrderNotification';
			$fromName 	    = FROM_NAME_TEXTILE_BOOKING;
			$fromEmail 	    = FROM_EMAIL_TEXTILE_BOOKING;
		} else if ($productCategoryId == '8') {		//environment Department
			$emailTemplateBlade = 'email.templates.environment.emailStabilityOrderNotification';
			$fromName 	    = FROM_NAME_ENVIRONMENT_BOOKING;
			$fromEmail 	    = FROM_EMAIL_ENVIRONMENT_BOOKING;
		}

		$emailTemplateBlade = 'email.templates.common.emailStabilityOrderNotification';
		return array($emailTemplateBlade, $fromName, $fromEmail);
	}

	/**
	 * Displaying mail Setting Variables-Booking
	 *
	 * @return \Illuminate\Http\Response
	 */
	function __mailSettingVariables($sectionType)
	{

		$fromName  = env('MAIL_FROM_NAME', defined('FROM_NAME') ? FROM_NAME : 'ITC-LAB-PKL');
		$fromEmail = env('MAIL_FROM_EMAIL', defined('FROM_EMAIL') ? FROM_EMAIL : 'report-invoice@itclabs.com');

		if ($sectionType == '1') {		//New Customer Registration in Sample Receiving
			$emailTemplateBlade = 'email.templates.common.emailSampleBooking';
		} else if ($sectionType == '2') {		//Order Confirmation Mail
			$emailTemplateBlade = 'email.templates.common.emailOrderBooking';
		} else if ($sectionType == '3') {		//Report Generation Mail
			$emailTemplateBlade = 'email.templates.common.emailTestReport';
		} else if ($sectionType == '4') {		//Invoice Generation Mail
			$emailTemplateBlade = 'email.templates.common.emailInvoice';
		} else if ($sectionType == '5') {		//Stability prototype order confirmation Mail
			$emailTemplateBlade = 'email.templates.common.emailStabilityOrderPrototype';
		} else if ($sectionType == '6') {		//Stability order Notification Mail
			$emailTemplateBlade = 'email.templates.common.emailStabilityOrder';
		} else if ($sectionType == '7') {		//Scheduled MIS Report Mail
			$emailTemplateBlade = 'email.templates.common.emailScheduledMisReport';
		} else if ($sectionType == '8') {		//Scheduled MIS Report Mail
			$emailTemplateBlade = 'email.templates.common.emailCustomerOnOrdeHold';
		} else if ($sectionType == '9') {		//Expected Due Date Change
			$emailTemplateBlade = 'email.templates.common.emailCustomerOnEddChange';
		}

		return array($emailTemplateBlade, $fromName, $fromEmail);
	}

	/*************************
	 *Claim value validation on add order
	 * @param  \Illuminate\Http\Request  $request
	 * 26-02-2018
	 * @return \Illuminate\Http\Response
	 ************************/
	public function changeArrayValues($data)
	{
		foreach ($data as $k => $v) {
			if ($v == '5') {
				$data[$k] = '0';
			} else {
				$data[$k] = $v;
			}
		}
		return $data;
	}

	/*************************
	 *Claim value validation on add order
	 * @param  \Illuminate\Http\Request  $request
	 * 26-02-2018
	 * @return \Illuminate\Http\Response
	 ************************/
	public function claimValueValidation($orderParameterDetail)
	{
		if (!empty($orderParameterDetail)) {
			$claimDependent = !empty($orderParameterDetail['claim_dependent']) ? array_filter($orderParameterDetail['claim_dependent']) : '';
			$claimValue     = !empty($orderParameterDetail['claim_value']) ? array_filter($orderParameterDetail['claim_value']) : NULL;
			if (!empty($claimDependent) && count($claimDependent) != count($claimValue)) {
				return true;
			} else {
				return false;
			}
		}
	}

	/*************************
	 *Claim value validation on edit order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function claimValueValidationOnEdit($orderParameterDetail)
	{
		$claimValue = array();
		if (!empty($orderParameterDetail)) {
			foreach ($orderParameterDetail as $key => $orderParameterData) {
				$id = str_replace("'", "", $key);
				if (isset($orderParameterData['claim_value'])) {
					$claimValue[] = !empty($orderParameterData['claim_value']) ? $orderParameterData['claim_value'] : NULL;
				}
				if ($id == 'new') {
					foreach ($orderParameterData as $key1 => $newData) {
						$claimValue[] = $newData['claim_value'];
					}
				}
			}
			if (isset($orderParameterDetail['claim_dependent']) && isset($claimValue) && (count(array_filter($orderParameterDetail['claim_dependent'])) != count(array_filter($claimValue)))) {
				return true;
			} else {
				return false;
			}
		}
	}

	/*************************
	 * Claim value unit validation on add order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function claimUnitValidation($orderParameterDetail)
	{
		if (!empty($orderParameterDetail)) {
			$claimDependent = !empty($orderParameterDetail['claim_dependent']) ? array_filter($orderParameterDetail['claim_dependent']) : '';
			$claimUnit 	    = !empty($orderParameterDetail['claim_value_unit']) ? array_filter($orderParameterDetail['claim_value_unit']) : NULL;
			if (!empty($claimDependent) && (count($claimDependent) != count($claimUnit))) {
				return true;
			} else {
				return false;
			}
		}
	}

	/*************************
	 *Claim value unit validation on edit order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function claimUnitValidationOnEdit($orderParameterDetail)
	{
		$claim_value_unit = array();
		if (!empty($orderParameterDetail)) {
			foreach ($orderParameterDetail as $key => $orderParameterData) {
				$id = str_replace("'", "", $key);
				if (isset($orderParameterData['claim_value_unit'])) {
					$claim_value_unit[] = !empty($orderParameterData['claim_value_unit']) ? $orderParameterData['claim_value_unit'] : NULL;
				}
				if ($id == 'new') {
					foreach ($orderParameterData as $key1 => $newData) {
						$claim_value_unit[] = $newData['claim_value_unit'];
					}
				}
			}
			array_filter($claim_value_unit);
			if (isset($orderParameterDetail['claim_dependent']) && isset($claim_value_unit) && (count(array_filter($orderParameterDetail['claim_dependent'])) != count(array_filter($claim_value_unit)))) {
				return true;
			} else {
				return false;
			}
		}
	}

	/*************************
	 * Running Time validation on add order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function runningTimeValidation($orderParameterDetail)
	{
		if (!empty($orderParameterDetail['cwap_invoicing_required'])) {
			$cwapInvoicingRequired = !empty($orderParameterDetail['cwap_invoicing_required']) ? array_filter($orderParameterDetail['cwap_invoicing_required']) : array();
			$postedRunningTimeId   = !empty($orderParameterDetail['running_time_id']) ? array_filter($this->changeArrayValues($orderParameterDetail['running_time_id'])) : array();
			if (!empty($cwapInvoicingRequired) && !empty($postedRunningTimeId)) {
				if (count($cwapInvoicingRequired) == count($postedRunningTimeId)) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return true;
		}
	}

	/*************************
	 * Running Time validation on add order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 ************************/
	public function runningTimeEditValidation($orderParameterDetailRaw)
	{

		$orderParameterDetail = $flag = array();

		//Newly Added Parameters
		if (!empty($orderParameterDetailRaw)) {
			foreach ($orderParameterDetailRaw as $key => $values) {
				$key = str_replace("'", "", $key);
				if ($key == 'new') {
					$orderParameterDetail = array_values($values);
				}
			}
		}
		if (!empty($orderParameterDetail)) {
			foreach ($orderParameterDetail as $orderParameters) {
				if (!empty($orderParameters['cwap_invoicing_required'])) {
					if (empty($orderParameters['running_time_id'])) {
						$flag[] = '0';
					} else {
						$flag[] = '1';
					}
				} else {
					$flag[] = '1';
				}
			}
		} else {
			$flag[] = '1';
		}

		return !empty($flag) && in_array(0, $flag) ? false : true;
	}

	/**********************************************
	 * Running Time validation on add order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 **********************************************/
	public function noOfInjectionValidation($orderParameterDetail)
	{
		if (!empty($orderParameterDetail['cwap_invoicing_required'])) {
			$cwapInvoicingRequired = !empty($orderParameterDetail['cwap_invoicing_required']) ? array_filter($orderParameterDetail['cwap_invoicing_required']) : array();
			$postedNo0fInjection   = !empty($orderParameterDetail['no_of_injection']) ? array_filter($orderParameterDetail['no_of_injection']) : array();
			if (!empty($cwapInvoicingRequired) && !empty($postedNo0fInjection)) {
				if (count($cwapInvoicingRequired) == count($postedNo0fInjection)) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return true;
		}
	}

	/**********************************************
	 * Running Time validation on add order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 **********************************************/
	public function noOfInjectionEditValidation($orderParameterDetailRaw)
	{

		$orderParameterDetail = $flag = array();

		//Newly Added Parameters
		if (!empty($orderParameterDetailRaw)) {
			foreach ($orderParameterDetailRaw as $key => $values) {
				$key = str_replace("'", "", $key);
				if ($key == 'new') {
					$orderParameterDetail = $values;
				}
			}
		}
		if (!empty($orderParameterDetail)) {
			foreach ($orderParameterDetail as $orderParameters) {
				if (!empty($orderParameters['cwap_invoicing_required'])) {
					if (empty($orderParameters['no_of_injection'])) {
						$flag[] = '0';
					} else {
						$flag[] = '1';
					}
				} else {
					$flag[] = '1';
				}
			}
		} else {
			$flag[] = '1';
		}

		return !empty($flag) && in_array(0, $flag) ? false : true;
	}

	/**********************************************
	 * DT Limit validation on Add order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 **********************************************/
	public function checkAddDTLimitValidation($orderParameterDetailRaw)
	{

		$orderParameterDetail = array();

		if (!empty($orderParameterDetailRaw)) {
			foreach ($orderParameterDetailRaw as $keyParameter => $orderParametersData) {
				foreach ($orderParametersData as $key => $parameters) {
					$parameters = empty($parameters) ? null : $parameters;
					$orderParameterDetail[$key][$keyParameter] = $parameters;
				}
			}
			foreach ($orderParameterDetail as $key => $values) {
				if ($values['test_parameter_id'] == '25235' && empty($values['standard_value_to'])) {
					return false;
				}
			}
		}
		return true;
	}

	/**********************************************
	 * DT Limit validation on Edit order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 **********************************************/
	public function checkEditDTLimitValidation($orderParameterDetailRaw)
	{

		$orderParameterDetail = array();

		//Newly Added Parameters
		if (!empty($orderParameterDetailRaw)) {
			foreach ($orderParameterDetailRaw as $key => $values) {
				$key = str_replace("'", "", $key);
				if ($key == 'new') {
					$orderParameterDetail = $values;
				}
			}
		}
		if (!empty($orderParameterDetail)) {
			foreach ($orderParameterDetail as $key => $values) {
				if ($values['test_parameter_id'] == '25235' && empty($values['standard_value_to'])) {
					return false;
				}
			}
		}
		return true;
	}

	/**********************************************
	 * Running Time validation on add order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 **********************************************/
	public function validateDecimalValueOnAdd($orderParameterDetailRaw)
	{

		$orderParameterDetail = array();

		if (!empty($orderParameterDetailRaw)) {
			foreach ($orderParameterDetailRaw as $keyParameter => $orderParametersData) {
				foreach ($orderParametersData as $key => $parameters) {
					$parameters = empty($parameters) ? null : $parameters;
					$orderParameterDetail[$key][$keyParameter] = $parameters;
				}
			}
			foreach ($orderParameterDetail as $key => $values) {
				if (!empty($values['display_decimal_place']) && strval($values['display_decimal_place']) !== strval(intval($values['display_decimal_place']))) {
					return false;
				}
			}
		}
		return true;
	}

	/**********************************************
	 * Running Time validation on add order
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 **********************************************/
	public function validateDecimalValueOnEdit($orderParameterDetailRaw)
	{

		$orderParameterDetailPrev = $orderParameterDetailNew = $flag = array();

		//Newly Added Parameters
		if (!empty($orderParameterDetailRaw)) {
			if (isset($orderParameterDetailRaw['claim_dependent'])) unset($orderParameterDetailRaw['claim_dependent']);
			foreach ($orderParameterDetailRaw as $key => $values) {
				$key = str_replace("'", "", $key);
				if ($key == 'new') {
					$orderParameterDetailNew = $values;
				} else {
					$orderParameterDetailPrev[$key] = $values;
				}
			}
			$orderParameterDetail = $orderParameterDetailPrev + $orderParameterDetailNew;
		}
		if (!empty($orderParameterDetail)) {
			foreach ($orderParameterDetail as $orderParameters) {
				if (!empty($orderParameters['display_decimal_place']) && strval($orderParameters['display_decimal_place']) !== strval(intval($orderParameters['display_decimal_place']))) {
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Display a listing of the resource.
	 * 17-04-2018
	 * @return \Illuminate\Http\Response
	 */
	function getHolidays($start, $end, $holidays)
	{
		$startDateTimeStamp = strtotime($start);
		$endDateTimeStamp   = strtotime($end);
		$totalHolidays      = array();
		$oneDay     	    = 60 * 60 * 24;
		if ($holidays) {
			for ($i = $startDateTimeStamp; $i <= $endDateTimeStamp; $i += $oneDay) {
				foreach ($holidays as $holiday) {
					$holidayDate = $holiday->holiday_date;
					$holiday_time_stamp = strtotime($holidayDate);
					if ($startDateTimeStamp <= $holiday_time_stamp) {
						$totalHolidays[] = date('Y-m-d', $holiday_time_stamp);
					}
					$i += count($totalHolidays) * $oneDay;
				}
			}
		}
		return array_unique($totalHolidays);
	}

	/**
	 * Convert a multi-dimensional array into a single-dimensional array.
	 * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
	 * @param  array $array The multi-dimensional array.
	 * @return array
	 */
	function multiArrayToOneArray($arrayData)
	{
		$data = array();
		foreach ($arrayData as $arrayDataAll) {
			if (!empty($arrayDataAll) && is_array($arrayDataAll)) {
				foreach ($arrayDataAll as $arrayOne) {
					$data[] = $arrayOne;
				}
			}
		}
		return $data;
	}

	/**
	 * Convert a multi-dimensional array into a single-dimensional array.
	 * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
	 * @param  array $array The multi-dimensional array.
	 * @return array
	 */
	function array_flatten($array)
	{
		if (!is_array($array)) {
			return false;
		}
		$result = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = array_merge($result, array_flatten($value));
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	/*************************
	 *function to get Common header and footer on pdf's
	 *Date : 22-05-2018
	 *Created By:Praveen Singh
	 *************************/
	public function getHeaderFooterTemplate()
	{
		return DB::table('template_dtl')->select('template_dtl.header_content', 'template_dtl.footer_content')->where('template_dtl.template_type_id', '=', '3')->where('template_dtl.template_status_id', '=', '1')->first();
	}

	/*************************
	 *function to get Common header and footer on pdf's
	 *Date : 22-05-2018
	 *Created By:Praveen Singh
	 *************************/
	public function withInOrBeforeTat($reportDate, $expectedDueDate)
	{
		if (strtotime($reportDate) <= strtotime($expectedDueDate)) {
			return "Y";
		} else {
			return "N";
		}
	}

	/*************************
	 *function to get Common header and footer on pdf's
	 *Date : 22-05-2018
	 *Created By:Praveen Singh
	 *************************/
	public function filterScheduleTableHead($tableHead)
	{
		$headArr = array();
		if (!empty($tableHead)) {
			foreach ($tableHead as $headValue) {
				$headArr[] = str_replace('_', ' ', strtoupper($headValue));
			}
		}
		return $headArr;
	}

	/*************************
	 *function to get Common header and footer on pdf's
	 *Date : 22-05-2018
	 *Created By:Praveen Singh
	 *************************/
	public function performancePercentage($orderDetail, $tatCount)
	{
		$percentage = '';
		$tatCount   = !empty($tatCount) ? count(array_filter($tatCount)) : '';
		$orderCount = !empty($orderDetail) ? count($orderDetail) : '';
		$percentage = (!empty($orderDetail) && !empty($tatCount)) ? number_format(($tatCount / $orderCount) * 100, 2) : '0';
		return $percentage;
	}

	/*************************
	 *function to validate Sunday and Holiday
	 *Date : 08-08-2018
	 *Created By:Praveen Singh
	 *************************/
	function validateSundayHoliday($providedDate)
	{
		if (!empty($providedDate)) {
			if (date('N', strtotime($providedDate)) == '7') {
				$providedDate = date(MYSQLDATETIMEFORMAT, strtotime('+1 day', strtotime($providedDate)));
				return $this->validateSundayHoliday($providedDate);
			}
			if (DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where(DB::raw("DATE(holiday_master.holiday_date)"), date('Y-m-d', strtotime($providedDate)))->count()) {
				$providedDate = date(MYSQLDATETIMEFORMAT, strtotime('+1 day', strtotime($providedDate)));
				return $this->validateSundayHoliday($providedDate);
			}
		}
		return $providedDate;
	}

	/**************************************************
	 *function to validate Sunday and Holiday
	 *Created By : Praveen Singh
	 *Created On : 08-08-2018
	 *Modified On : 13-Sept-2018
	 ***************************************************/
	function validateSundayHoliday_v1($providedDate, $noOfDays = '1', $operator = '+')
	{
		if (!empty($providedDate)) {
			if (date('N', strtotime($providedDate)) == '7') {
				$providedDate = date(MYSQLDATETIMEFORMAT, strtotime('' . $operator . $noOfDays . ' day', strtotime($providedDate)));
				return $this->validateSundayHoliday_v1($providedDate, $noOfDays, $operator);
			}
			if (DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where(DB::raw("DATE(holiday_master.holiday_date)"), date('Y-m-d', strtotime($providedDate)))->count()) {
				$providedDate = date(MYSQLDATETIMEFORMAT, strtotime('' . $operator . $noOfDays . ' day', strtotime($providedDate)));
				return $this->validateSundayHoliday_v1($providedDate, $noOfDays, $operator);
			}
		}
		return $providedDate;
	}

	/**************************************************
	 *function to validate upcomming Sunday and Holiday
	 *Created By : Praveen Singh
	 *Created On : 08-08-2018
	 *Modified On : 14-Sept-2018
	 ***************************************************/
	function validateSundayHoliday_v2($divisionId, $providedDate, $noOfDays = '1', $operator = '+')
	{
		if (!empty($providedDate)) {
			if (date('N', strtotime($providedDate)) == '7') {
				$providedDate = date(MYSQLDATETIMEFORMAT, strtotime('' . $operator . $noOfDays . ' day', strtotime($providedDate)));
				return $this->validateSundayHoliday_v2($divisionId, $providedDate, $noOfDays, $operator);
			}
			if (DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where('holiday_master.division_id', $divisionId)->where(DB::raw("DATE(holiday_master.holiday_date)"), date('Y-m-d', strtotime($providedDate)))->count()) {
				$providedDate = date(MYSQLDATETIMEFORMAT, strtotime('' . $operator . $noOfDays . ' day', strtotime($providedDate)));
				return $this->validateSundayHoliday_v2($divisionId, $providedDate, $noOfDays, $operator);
			}
		}
		return $providedDate;
	}

	/*************************
	 *function to validate Sunday and Holiday
	 *Date : 08-08-2018
	 *Created By:Praveen Singh
	 *************************/
	function validateEmailIds($emailIds)
	{
		$emailContent = $flag = array();
		if (!empty($emailIds)) {
			if (is_array($emailIds)) {
				$emailContent = array_filter($emailIds);
			} else {
				$emailContent[] = $emailIds;
			}
			foreach ($emailContent as $key => $emailId) {
				if (filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
					$flag[$key] = '1';
				} else {
					$flag[$key] = '0';
				}
			}
		}
		return !empty($flag) && in_array('0', $flag) ? false : true;
	}

	/*************************
	 *function to validate Sunday and Holiday
	 *Date : 08-08-2018
	 *Created By:Praveen Singh
	 *************************/
	function validateMailEmailIds($emailIds)
	{
		$flag = array();
		if (!empty($emailIds)) {
			if (is_array($emailIds)) {
				$emailContent = array_filter($emailIds);
			} else {
				$emailContent[] = $emailIds;
			}
			foreach ($emailContent as $key => $emailId) {
				if (filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
					$flag[$key] = $emailId;
				}
			}
		}
		return $flag;
	}

	/***************************************************************
	 *function to generate unique code
	 *Date : 28-12-2018
	 *Created By:Ruby
	 ****************************************************************/
	function generateCode_v1($prefix, $tableName, $fieldName, $uniqueId)
	{

		//getting Max Serial Number
		$maxCode = DB::table($tableName)->select('*')->orderBy($uniqueId, 'DESC')->limit(1)->first();
		$maxSerialNo  = !empty($maxCode->$uniqueId) ? $maxCode->$uniqueId + 01 : '01';
		$uniqueCode = (strlen($maxSerialNo) == '1') ? $prefix . '0' . $maxSerialNo : $prefix . $maxSerialNo;

		//Combing all to get unique order number
		return str_replace(' ', '', $uniqueCode);
	}

	/**
	 * Last date of a month of a year
	 */
	function get_last_day_of_the_month($date = '')
	{

		$month  = date('m', strtotime($date));
		$year   = date('Y', strtotime($date));
		$result = strtotime("{$year}-{$month}-01");
		$result = strtotime('-1 second', strtotime('+1 month', $result));
		return date('d', $result);
	}

	/**
	 * Get no. of month before date
	 */
	function get_no_of_month_before_date($date, $monthNumber = '1', $format = 'Y-m-d')
	{
		return date($format, strtotime($date . ' -' . $monthNumber . ' months'));
	}

	/**
	 * Get no. of month before date
	 */
	function get_no_of_month_after_date($date, $monthNumber = '1', $format = 'Y-m-d')
	{
		return date($format, strtotime($date . ' +' . $monthNumber . ' months'));
	}

	/**
	 * Last date of a month of a year
	 *
	 * @param[in] $date - Integer. Default = Current Month
	 *
	 * @return Last date of the month and year in yyyy-mm-dd format
	 */
	function exact_date_diff($fromDate, $toDate)
	{

		$returnData = array();

		// Declare and define two dates 
		$date1 = strtotime($fromDate);
		$date2 = strtotime($toDate);

		// Formulate the Difference between two dates 
		$diff = abs($date2 - $date1);

		// To get the year divide the resultant date into 
		// total seconds in a year (365*60*60*24) 
		$years = floor($diff / (365 * 60 * 60 * 24));
		$returnData['year'] = $years;

		// To get the month, subtract it with years and 
		// divide the resultant date into 
		// total seconds in a month (30*60*60*24) 
		$months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
		$returnData['month'] = $months;

		// To get the day, subtract it with years and 
		// months and divide the resultant date into 
		// total seconds in a days (60*60*24) 
		$days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
		$returnData['day'] = $days;

		// To get the hour, subtract it with years, 
		// months & seconds and divide the resultant 
		// date into total seconds in a hours (60*60) 
		$hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
		$returnData['hour'] = $hours;

		// To get the minutes, subtract it with years, 
		// months, seconds and hours and divide the 
		// resultant date into total seconds i.e. 60 
		$minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
		$returnData['minute'] = $minutes;

		// To get the minutes, subtract it with years, 
		// months, seconds, hours and minutes 
		$seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
		$returnData['second'] = $seconds;

		return $returnData;
	}

	/**
	 * Get the needed authorization credentials from the request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	function validateFirstLoginAndExpiryPassword($user_id)
	{

		global $models;

		$bladeName = array();

		$userData 	      = DB::table('users')->where('users.id', '=', $user_id)->first();
		$defaultExpiryDays    = defined('PASSOWRD_EXPIRY_DAYS') ? PASSOWRD_EXPIRY_DAYS : '30';
		$passwordChangeDate   = !empty($userData->password_changed_at) && !empty(strtotime($userData->password_changed_at)) ? $userData->password_changed_at : '0';
		$calculatedExpiryDayArr = !empty($passwordChangeDate) ? array_filter($models->exact_date_diff($passwordChangeDate, date('Y-m-d H:i:s'))) : array();
		if (empty($calculatedExpiryDayArr)) {
			$bladeName = array('my_account.passwordexpiry', 'Change Password', true);
		} else if (array_key_exists('day', $calculatedExpiryDayArr) && $calculatedExpiryDayArr['day'] > $defaultExpiryDays) {
			$bladeName = array('my_account.passwordexpiry', 'Reset Password', false);
		}
		return $bladeName;
	}

	/*************************
	 *function: To validate Date Is Third Saturday of Month
	 *Date : 22-07-2019
	 *Created By:Praveen Singh
	 *************************/
	function validateDateIsThirdSaturdayofMonth($currentDate)
	{
		$monthSaturdays = array();
		$currentDate = date('Y-m-d', strtotime($currentDate));
		list($firstDay, $lastDay) = $this->getFirstAndLastDayOfMonth($currentDate);
		while ($firstDay != $lastDay) {
			if (date('l', strtotime($firstDay)) == 'Saturday') {
				$monthSaturdays[$firstDay] = $firstDay;
			}
			$firstDay = date('Y-m-d', strtotime($firstDay . ' +1 day'));
		};
		$monthSaturdays = !empty($monthSaturdays) ? array_values(array_slice($monthSaturdays, -2, 1)) : array();
		return !empty($monthSaturdays) && in_array($currentDate, $monthSaturdays) ? true : false;
	}

	/*************************
	 *function	: To validate Date lies in allocated Holiday or in sunday.
	 *Date 	: 22-07-2019
	 *Created By	: Praveen Singh
	 *************************/
	function validateDateIsHolidayOrSunday($currentDate, $divisionId)
	{

		//Checking Holidays
		$holidayDay = count(DB::table('holiday_master')->where('holiday_master.holiday_status', '1')->where('holiday_master.division_id', $divisionId)->where(DB::raw("DATE(holiday_master.holiday_date)"), date('Y-m-d', strtotime($currentDate)))->first()) > 0 ? true : false;

		//Checking Sundays
		$isCurrentDateSundays = date('N', strtotime($currentDate)) == '7' ? true : false;

		//Checking third Saturdays
		$isCurrentDateSaturday = $this->validateDateIsThirdSaturdayofMonth($currentDate);

		return $holidayDay == true || $isCurrentDateSundays == true || $isCurrentDateSaturday == true ? false : true;
	}

	/*************************
	 *function	: For Getting Financial Year of Invoice
	 *Date 	: 26-Oct-2020
	 *Created By : Praveen Singh
	 *************************/
	function getInvoiceFinancialYear($currentDate)
	{
		return DB::table('invoice_financial_years')->whereDate('invoice_financial_years.ify_from_date', '<=', date('Y-m-d', strtotime($currentDate)))->whereDate('invoice_financial_years.ify_to_date', '>=', date('Y-m-d', strtotime($currentDate)))->pluck('invoice_financial_years.ify_id')->first();
	}
	/*************************
	 *function	: For Getting NABL List on job sheet print
	 *Date 	: 05-April-2021
	 *Created By : Ruby Thakur
	 *************************/
	function getNablScopeList()
	{
		return array((object)array('id' => '0', 'name' => 'No'), (object)array('id' => '1', 'name' => 'Yes'));
	}

	/*****************************************
	 *function	 : For Getting Email Address of Commercial CRM
	 *Date 	     : 21-May-2021
	 *Created By : Praveen Singh
	 *****************************************/
	function getComCrmEmailAddresses($customer_id)
	{
		$customerHoldOrder = DB::table('order_master')
			->where('order_master.customer_id', '=', $customer_id)
			->where('order_master.status', '12')
			->orderBy('order_master.order_id', 'DESC')
			->first();

		$customerComCrmData = DB::table('customer_com_crm_email_addresses')
			->where('customer_com_crm_email_addresses.cccea_division_id', '=', !empty($customerHoldOrder->division_id) ? $customerHoldOrder->division_id : '0')
			->where('customer_com_crm_email_addresses.cccea_product_category_id', '=', !empty($customerHoldOrder->product_category_id) ? $customerHoldOrder->product_category_id : '0')
			->pluck('customer_com_crm_email_addresses.cccea_email', 'customer_com_crm_email_addresses.cccea_email')
			->all();

		return !empty($customerComCrmData) ? array_values($customerComCrmData)  : array();
	}

	/*****************************************
	 *function	 : For Getting Email Address of Commercial CRM
	 *Date 	     : 05-Oct-2021
	 *Created By : Praveen Singh
	 *****************************************/
	function getBackDateBookingDepartments()
	{
		return array_values(!empty(BACK_DATE_BOOKING_DEPARTMENTS) && defined('BACK_DATE_BOOKING_DEPARTMENTS') ? explode(',', BACK_DATE_BOOKING_DEPARTMENTS) : [2, 6, 308]);
	}

	/*****************************************
	 *function	 : For Getting Email Address of Commercial CRM
	 *Date 	     : 05-Oct-2021
	 *Created By : Praveen Singh
	 *****************************************/
	function hasBackDateBookingDepartments($product_category_id = NULL)
	{
		$department_ids			 = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : array();
		$department_backdate_ids = $this->getBackDateBookingDepartments();

		if (!empty($product_category_id)) {
			return $this->in_array_all($product_category_id, $department_backdate_ids) ? '1' : '0';
		} else {
			return $this->in_array_all($department_ids, $department_backdate_ids) ? '1' : '0';
		}
	}

	/*****************************************
	 *function	 : For Getting Email Address of Commercial CRM
	 *Date 	     : 05-Oct-2021
	 *Created By : Praveen Singh
	 *****************************************/
	function hasBackDateBookingEnabledInDepartment($product_category_id = NULL)
	{
		$department_ids			    = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : array();
		$department_backdate_status = defined('BACK_DATE_BOOKING_SETTING') ? trim(BACK_DATE_BOOKING_SETTING) : '0';
		$department_backdate_ids    = $this->getBackDateBookingDepartments();
		$isAdminLogin  				= defined('IS_ADMIN') && IS_ADMIN ? '1' : '0';

		if ($isAdminLogin) {
			return $department_backdate_status ? '1' : '0';
		} else {
			if (!empty($product_category_id)) {
				return $this->in_array_all($product_category_id, $department_backdate_ids) && $department_backdate_status ? '1' : '0';
			} else {
				return $this->in_array_all($department_ids, $department_backdate_ids) && $department_backdate_status ? '1' : '0';
			}
		}
	}

	/***********************************************
	 *function to Check the Order Confirmation Mail Sent or Not
	 *Created On : 23-Dec-2021
	 *Created By : Praveen-Singh
	 **********************************************/
	public function getMailStatusText($order_mail_status_id)
	{
		$mailStatusTextArray  = DB::table('order_mail_status')->pluck('order_mail_status_name', 'order_mail_status_id')->all();
		$order_mail_status_id = !is_null($order_mail_status_id) ? $order_mail_status_id : '2';
		return !empty($mailStatusTextArray[$order_mail_status_id]) ? ucwords($mailStatusTextArray[$order_mail_status_id]) : '';
	}

	/***********************************************
	 *function to Check the Order Confirmation Mail Sent or Not
	 *Created On : 23-Dec-2021
	 *Created By : Praveen-Singh
	 **********************************************/
	public function getMailStatusTextId($order_mail_status_text)
	{
		$mailStatusTextArray    = DB::table('order_mail_status')->pluck('order_mail_status_id', 'order_mail_status_name')->all();
		$order_mail_status_text = trim(ucwords(strtolower($order_mail_status_text)));
		return !empty($mailStatusTextArray[$order_mail_status_text]) ? ucwords($mailStatusTextArray[$order_mail_status_text]) : '';
	}

	/***********************************************
	 *function to get Invoiving Customer Contact Person name,Contact Person Email and Mobile
	 *Created On : 23-Dec-2021
	 *Created By : Praveen-Singh
	 **********************************************/
	public function getCustomerInvoicingDetail($customer_id)
	{
		$returnData = [];
		$customerDetail = DB::table('customer_contact_persons')->where('customer_contact_persons.customer_id', '=', $customer_id)->first();
		if (!empty($customerDetail)) {
			if (!empty($customerDetail->contact_name1)) {
				$returnData[] = '<strong>Contact Person Name : </strong>' . $customerDetail->contact_name1;
			}
			if (!empty($customerDetail->contact_email1)) {
				$returnData[] = '<strong>Contact Person Email : </strong>' . $customerDetail->contact_email1;
			}
			if (!empty($customerDetail->contact_mobile1)) {
				$returnData[] = '<strong>Contact Person Mobile : </strong>' . $customerDetail->contact_mobile1;
			}
			return !empty($returnData) ? implode('<br />', $returnData) : '';
		}
	}
}
