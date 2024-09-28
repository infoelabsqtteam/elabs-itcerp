<?php

/*****************************************************
 *Description : Common Function Configuration File
 *Created By  : Praveen-Singh
 *Created On  : 15-Dec-2017
 *Modified On : 30-May-2019
 *Package     : ITC-ERP-PKL
 ******************************************************/

namespace App;

use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use DB;
use File;

class AutoCommand extends Model
{
	/****************************************
	 *function    : Save Auto mailing Data.
	 *Created By  : Praveen Singh
	 *Created On  : 01-July-2018
	 *Modified by : Praveen Singh
	 *Modified On : 03-July-2018
	 *****************************************/
	function saveAutoMail($requiredData)
	{
		global $models, $order, $report, $invoice;

		/*****************************************************
		 *if mailSavingType = 1 then saving data for VOC .
		 *if mailSavingType = 2 then saving data for Order Confirmation .
		 ******************************************************/
		if (!empty($requiredData['mailSavingType']) && defined('SEND_MAIL_NOTIFICATION') && SEND_MAIL_NOTIFICATION  == '1') {
			if ($requiredData['mailSavingType'] == '1') {
				//return $this->saveVocMailDataDetail($requiredData);
			}
			if ($requiredData['mailSavingType'] == '2') {
				return $this->saveOrderConfirmationMailDataDetail($requiredData);
			}
		}
		return true;
	}

	/****************************************
	 *function    : Sending Auto mail Data.
	 *Created By  : Praveen Singh
	 *Created On  : 03-July-2018
	 *Modified by : Praveen Singh
	 *Modified On : 04-July-2018
	 *****************************************/
	function sendAutoMail($requiredData)
	{
		global $models, $order, $report, $invoice;

		/*****************************************************
		 *if mailSendingType = 1 then mail for VOC.
		 *if mailSendingType = 2 then mail for order Confirmation.
		 *if mailSendingType = 3 then mail for Scheduled Mis-Report.
		 ******************************************************/
		if (!empty($requiredData['mailSendingType']) && defined('SEND_MAIL_NOTIFICATION') && SEND_MAIL_NOTIFICATION  == '1') {
			if ($requiredData['mailSendingType'] == '1') {
				//return $this->sendVocMailDataDetail($requiredData);
			}
			if ($requiredData['mailSendingType'] == '2') {
				return $this->sendOrderConfirmationMailDataDetail($requiredData);
			}
			if ($requiredData['mailSendingType'] == '3') {
				return $this->sendScheduledMisReportDetail($requiredData);
			}
		}
		return true;
	}

	/****************************************
	 *function    : Inserting Voc Mail Data.
	 *Created By  : Ruby Thakur
	 *Created On  :01-July-2018
	 *Modified by : Praveen Singh
	 *Modified On : 03-July-2018
	 *****************************************/
	function saveVocMailDataDetail($mailingData)
	{

		global $models, $order, $report, $invoice;

		//Getting first and last day of the Current Month
		list($monthFirstDate, $monthLastDate) = $models->getFirstAndLastDayOfMonth(date('Y-m-d'), $format = 'Y-m-d');

		$customerData = DB::table('customer_master')
			->select('customer_master.customer_id')
			->join('order_master', 'order_master.customer_id', 'customer_master.customer_id')
			->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
			->whereBetween(DB::raw("DATE(order_report_details.approving_date)"), array($monthFirstDate, $monthLastDate))
			->whereNotNull('order_report_details.approving_date')
			->groupBy('customer_master.customer_id')
			->orderBy('customer_master.customer_id')
			->get();

		if (!empty($customerData)) {
			foreach ($customerData as $key => $values) {
				$smdOrderIds = DB::table('order_master')
					->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->where('order_master.customer_id', '=', $values->customer_id)
					->whereBetween(DB::raw("DATE(order_report_details.approving_date)"), array($monthFirstDate, $monthLastDate))
					->whereNotNull('order_report_details.approving_date')
					->pluck('order_master.order_id')
					->all();
				if (!empty($smdOrderIds)) {
					$dataSave 			   			= array();
					$dataSave['smd_content_type']  	= '1';
					$dataSave['smd_customer_id']   	= $values->customer_id;
					$dataSave['smd_order_ids']     	= implode(',', array_values($smdOrderIds));
					$dataSave['smd_template_name'] 	= 'email.templates.voc.emailCustomerVoc';
					$dataSave['smd_date'] 	   		= date('Y-m-d H:i:s');
					$dataSave['smd_status']        	= '0';
					DB::table('scheduled_mail_dtl')->insertGetId($dataSave);
				}
			}
		}
	}

	/****************************************
	 *function    : Saving Order Confirmation Mail Data.
	 *Created By  : Praveen Singh
	 *Created On  : 03-July-2018
	 *Modified by : Praveen Singh
	 *Modified On : 04-July-2018
	 *****************************************/
	public function saveOrderConfirmationMailDataDetail($mailingData)
	{

		global $models, $order, $report, $invoice;

		$orderId  = !empty($mailingData['order_id']) ? $mailingData['order_id'] : '0';

		if ($orderId) {
			$orderDetail = $order->getOrderDetail($orderId);
			if (!empty($orderDetail->order_id) && !empty($orderDetail->product_category_id) && !$order->isOrderBackDateBooking($orderDetail->order_id)) {
				list($emailTemplateBlade, $fromName, $fromEmail) = $models->__mailSettingVariables($mailingData['mailTemplateType'] = '2');
				$dataSave 						= array();
				$dataSave['smd_content_type'] 	= '2';
				$dataSave['smd_customer_id'] 	= $orderDetail->customer_id;
				$dataSave['smd_order_ids'] 		= $orderDetail->order_id;
				$dataSave['smd_template_name'] 	= $emailTemplateBlade;
				$dataSave['smd_date'] 			= date('Y-m-d H:i:s');
				$dataSave['smd_status'] 		= '0';
				DB::table('scheduled_mail_dtl')->insertGetId($dataSave);
			}
		}
	}

	/****************************************
	 *function    : Sending Voc Mail Sent Data.
	 *Created By  : Ruby Thakur
	 *Created On  : 01-July-2018
	 *Modified by : Praveen Singh
	 *Modified On : 03-July-2018,14-Feb-2020
	 *****************************************/
	public function sendVocMailDataDetail($mailingData)
	{

		global $models, $order, $report, $invoice;

		$response   = $responseData = array();
		$siteName   = defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';
		$mailStatus = '0';

		//Deleting Mail Data whose semd mail counter exceed 3 times
		DB::table('scheduled_mail_dtl')->where('scheduled_mail_dtl.smd_mail_counter', '=', '2')->where('scheduled_mail_dtl.smd_content_type', '=', '1')->delete();

		$autoScheduledDtlData = DB::table('scheduled_mail_dtl')
			->select('scheduled_mail_dtl.smd_id', 'scheduled_mail_dtl.smd_template_name', 'scheduled_mail_dtl.smd_customer_id', 'scheduled_mail_dtl.smd_order_ids')
			->where('scheduled_mail_dtl.smd_status', '=', '0')
			->where('scheduled_mail_dtl.smd_content_type', '=', '1')
			->orderBy('scheduled_mail_dtl.smd_customer_id')
			->take('500')
			->get();

		if (!empty($autoScheduledDtlData)) {
			foreach ($autoScheduledDtlData as $key => $values) {

				list($toEmails, $ccEmails) = $order->getCustomerEmailToCC($values->smd_customer_id);
				$totalRecipient = !empty($toEmails) ? count($toEmails) : '0';
				$customerData   = DB::table('customer_master')->select('customer_master.customer_id', 'customer_master.customer_code', 'customer_master.customer_email', 'customer_master.customer_name')->where('customer_id', '=', $values->smd_customer_id)->first();

				$asdOrderIds    = !empty($values->smd_order_ids) ? array_values(explode(',', $values->smd_order_ids)) : array();
				$orderDetail    = DB::table('order_master')
					->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
					->join('scheduled_mail_dtl', 'scheduled_mail_dtl.smd_customer_id', 'order_master.customer_id')
					->join('samples', 'samples.sample_id', 'order_master.sample_id')
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
					->whereIn('order_master.order_id', $asdOrderIds)
					->where('order_master.customer_id', '=', $values->smd_customer_id)
					->where('scheduled_mail_dtl.smd_status', '=', '0')
					->whereNotNull('order_report_details.approving_date')
					->select('order_master.order_id as s_no', 'order_master.order_no', 'product_master_alias.c_product_name as sample_description', 'order_master.booking_date as order_booking_date', 'order_master.expected_due_date', 'order_report_details.report_date')
					->groupBy('order_master.order_id')
					->orderBy('order_master.order_no')
					->get()
					->toArray();

				//formating the time stamp	
				$models->formatTimeStampFromArray($orderDetail, 'd-m-Y');

				if (!empty($orderDetail)) {

					$tatCount = array();
					foreach ($orderDetail as $keyIn => $data) {
						$data->s_no 	  = $keyIn + 1;
						$data->TAT 	  = $models->sub_days_between_two_date($data->expected_due_date, $data->order_booking_date);
						$data->within_TAT = $models->withInOrBeforeTat($data->report_date, $data->expected_due_date);
						$tatCount[$keyIn] = !empty($data->within_TAT) && $data->within_TAT == 'Y' ? $data->within_TAT : '';
					}
					//summary Detail
					$summaryDetail['sample_count']   	 = count($orderDetail);
					$summaryDetail['With_in_TAT']    	 = count(array_filter($tatCount));
					$summaryDetail['performance_(in_%)'] = $models->performancePercentage($orderDetail, $tatCount);

					$userData = array(
						'orderData'    	=> $orderDetail,
						'toEmails'   	=> $models->validateMailEmailIds($toEmails),
						'template_name' => $values->smd_template_name,
						'customer_id'   => $customerData->customer_id,
						'customer_code' => $customerData->customer_code,
						'customer'		=> $customerData->customer_name,
						'from_name'		=> defined('FROM_NAME') ? FROM_NAME : 'Administrator ITC LAB',
						'from_email'	=> defined('FROM_EMAIL') ? FROM_EMAIL : 'itcerp@itclabs.com',
						'voc_file_name'	=> 'voice_of_customer.xlsx',
						'subject' 		=> $siteName . ' : Booking Detail : ' . date('F-Y') . ' : ' . $customerData->customer_name,
					);
				}

				$responseData 					= !empty($userData) ? json_decode(json_encode($userData), true) : array();
				$response['customer_id']        = $userData['customer_id'];
				$response['customer_code']      = $userData['customer_code'];
				$response['customer_name']      = $userData['customer'];
				$response['heading']            = 'SAMPLE DETAIL:';
				$response['tableHead'] 			= !empty($responseData['orderData']) ? $models->filterScheduleTableHead(array_keys(end($responseData['orderData']))) : array();
				$response['tableBody'] 			= !empty($responseData) ? $responseData : array();
				$response['summaryHeading']     = 'SUMMARY:';
				$response['summaryTableHead'] 	= !empty($summaryDetail) ? $models->filterScheduleTableHead(array_keys($summaryDetail)) : array();
				$response['summaryTableBody'] 	= !empty($summaryDetail) ? $summaryDetail : array();

				if (!empty($response['tableBody']['template_name']) && !empty($response['tableBody']['toEmails'])) {
					Mail::send($response['tableBody']['template_name'], ['order' => $response], function ($msg) use ($response) {
						$msg->from($response['tableBody']['from_email'], $response['tableBody']['from_name']);
						if (!empty($response['tableBody']['toEmails'])) $msg->to($response['tableBody']['toEmails']);
						$msg->subject($response['tableBody']['subject']);
						if (!empty($response['tableBody']['voc_file_name']) && file_exists(public_path() . '/images/voc/' . $response['tableBody']['voc_file_name'])) $msg->attach(public_path() . '/images/voc/' . $response['tableBody']['voc_file_name']);
					});
					$mailStatus = $totalRecipient == count(Mail::failures()) ? '0' : '1';		//Getting Mail Status
				}

				//Saving Record in Order Mail Detail
				$dataSave 			   = array();
				$dataSave['voc_customer_id']   	   = $values->smd_customer_id;
				$dataSave['voc_order_ids'] 	   = $values->smd_order_ids;
				$dataSave['voc_template_name']     = $values->smd_template_name;
				$dataSave['voc_mail_date'] 	   = date('Y-m-d H:i:s');
				$dataSave['voc_status'] 	   = $mailStatus;
				DB::table('voc_mail_dtl')->insertGetId($dataSave);

				if ($mailStatus) {

					//Creating PDF of VOC
					$this->generateVocPdfOfCustomer($response);

					//Deleting Send Mail Raw Data
					DB::table('scheduled_mail_dtl')->where('scheduled_mail_dtl.smd_content_type', '=', '1')->where('smd_id', '=', $values->smd_id)->delete();
				} else {
					//Updating Mail end Counter
					$counterDetail = DB::table('scheduled_mail_dtl')->where('smd_id', '=', $values->smd_id)->first();
					DB::table('scheduled_mail_dtl')->where('scheduled_mail_dtl.smd_id', '=', $values->smd_id)->update(['scheduled_mail_dtl.smd_mail_counter' => $counterDetail->smd_mail_counter + 1]);
				}
			}
		}
	}

	/****************************************
	 *function    : Creating PDF of VOC
	 *Created By  : Praveen Singh
	 *Created On  : 27-April-2019
	 *Modified by : Praveen Singh
	 *Modified On : 27-April-2019,14-Feb-2020
	 *****************************************/
	public function generateVocPdfOfCustomer($response)
	{

		global $models, $order, $report, $invoice;

		if (!empty($response['customer_code'])) {

			//Web Portal Destination Path
			$rootPath 	     = defined('ROOT_PATH') ? ROOT_PATH : '/var/www/html/';
			$vocDesPath      = $rootPath . 'itclabs/public/images/voc/';
			$currentDateTime = date('Y-m-d H:i:s');

			//Checking Customer exist in Web Portal or Not
			$customerDetail = DB::connection('mysql2')->table('users')->where('users.code', !empty($response['customer_code']) ? trim($response['customer_code']) : '0')->where('users.role_id', '3')->first();
			if (!empty($customerDetail->id)) {

				//Getting Common Header and Footer
				$pdfHeaderContent 	    = $models->getHeaderFooterTemplate();
				$response['header_content'] = !empty($pdfHeaderContent->header_content) ? $pdfHeaderContent->header_content : 'VOC';
				$response['footer_content'] = !empty($pdfHeaderContent->footer_content) ? $pdfHeaderContent->footer_content : '';;

				//File Path Detail both Source and Destination
				list($vocFileName, $vocFileDir) = $models->downloadSaveDynamicPDF($response, $contentType = 'voc');
				if (!empty($vocFileName) && file_exists($vocFileDir)) {

					//Checking Customer Detail already exist
					$vocCustomerExist = DB::connection('mysql2')->table('voc_dtl')->where('voc_dtl.user_id', $customerDetail->id)->first();

					//Deleting the file from Directory of already exist Customer
					if (!empty($vocCustomerExist->voc_file_name) && file_exists($vocDesPath . $vocCustomerExist->voc_file_name)) {
						//File::delete($vocDesPath.$vocCustomerExist->voc_file_name);
					}

					//Coping report file from src to desc folder
					//shell_exec("cp -p $vocFileDir $vocDesPath");
					$voc_file_name_content = base64_encode(file_get_contents($vocFileDir));

					//Inseting or Updating the record in a voc_dtl table
					if (empty($vocCustomerExist->voc_id)) {
						$dataSave = array();
						$dataSave['user_id'] 	   	   = $customerDetail->id;
						$dataSave['voc_file_name'] 	   = $vocFileName;
						$dataSave['voc_file_name_content'] = trim($voc_file_name_content);
						$dataSave['voc_date'] 	   	   = $currentDateTime;
						$dataSave['created_at']	   	   = $currentDateTime;
						$dataSave['modified_at']   	   = $currentDateTime;
						DB::connection('mysql2')->table('voc_dtl')->insertGetId($dataSave);
					} else if (!empty($vocCustomerExist->voc_id)) {
						$dataUpdate 			     = array();
						$dataUpdate['voc_file_name'] 	     = $vocFileName;
						$dataUpdate['voc_file_name_content'] = trim($voc_file_name_content);
						$dataUpdate['voc_date'] 	     = $currentDateTime;
						$dataUpdate['created_at']	     = $currentDateTime;
						$dataUpdate['modified_at']   	     = $currentDateTime;
						DB::connection('mysql2')->table('voc_dtl')->where('voc_dtl.voc_id', $vocCustomerExist->voc_id)->update($dataUpdate);
					}
				}
			}
		}
	}

	/****************************************
	 *function    : Sending Order Confirmation Mail Data.
	 *Created By  : Praveen Singh
	 *Created On  : 03-July-2018
	 *Modified by : Praveen Singh
	 *Modified On : 04-July-2018
	 *****************************************/
	public function sendOrderConfirmationMailDataDetail($mailingData)
	{

		global $models, $order, $report, $invoice;

		$siteName   = defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';
		$mailStatus = '0';

		//Deleting Mail Data whose semd mail counter exceed 3 times
		DB::table('scheduled_mail_dtl')->where('scheduled_mail_dtl.smd_mail_counter', '=', '3')->where('scheduled_mail_dtl.smd_content_type', '=', '2')->delete();

		$scheduledMailData = DB::table('scheduled_mail_dtl')
			->select('scheduled_mail_dtl.smd_id', 'scheduled_mail_dtl.smd_date', 'scheduled_mail_dtl.smd_template_name', 'scheduled_mail_dtl.smd_customer_id', 'scheduled_mail_dtl.smd_order_ids')
			->where('scheduled_mail_dtl.smd_content_type', '=', '2')
			->where('scheduled_mail_dtl.smd_status', '=', '0')
			->whereNotNull('scheduled_mail_dtl.smd_order_ids')
			->whereNotNull('scheduled_mail_dtl.smd_template_name')
			->orderBy('scheduled_mail_dtl.smd_id', 'ASC')
			->take('500')
			->get()
			->toArray();

		if (!empty($scheduledMailData)) {
			foreach ($scheduledMailData as $key => $values) {

				$orderDetail = $order->getOrder(!empty($values->smd_order_ids) ? $values->smd_order_ids : '0');
				if (!empty($orderDetail->order_no)) {

					$division_id = !empty($orderDetail->division_id) ? $orderDetail->division_id : '0';
					$product_category_id = !empty($orderDetail->product_category_id) ? $orderDetail->product_category_id : '0';

					list($header_content, $footer_content) 				= $order->getDynamicHeaderFooterTemplate($template_type_id = '4', $division_id, $product_category_id);
					list($emailTemplateBlade, $fromName, $fromEmail) 	= $models->__mailSettingVariables($mailingData['mailTemplateType'] = '2');
					$emailSubject   		 	  						= implode(' | ', array($orderDetail->customer_name, $orderDetail->sample_description, $orderDetail->batch_no, $orderDetail->order_no));
					$totalRecipient 		 	  						= count($orderDetail->to_emails) + count($orderDetail->cc_emails);
					$emailTemplateBlade 	 	  						= !empty($values->smd_template_name) ? $values->smd_template_name : $emailTemplateBlade;
					list($fileName, $fileNameDir) 						= $division_id == '1' ? $models->downloadSaveDynamicPDF(array('order_id' => $orderDetail->order_id), $contentType = 'orderConfirmation') : array(0, 0);

					$userData = array(
						'name'    			=> $orderDetail->contact_name1,
						'toEmails'    		=> $models->validateMailEmailIds($orderDetail->to_emails),
						'ccEmails'    		=> $models->validateMailEmailIds($orderDetail->cc_emails),
						'order_no'			=> $division_id == '1' ? $orderDetail->order_no : '',
						'sample_no'			=> $division_id == '2' ? $orderDetail->sample_no : '',
						'sample_name'		=> $orderDetail->sample_description,
						'batch_no'			=> $orderDetail->batch_no,
						'expected_due_date'	=> $models->validateSundayHoliday_v2($division_id, $models->add_days_in_date($orderDetail->expected_due_date, '1', $format = 'Y-m-d'), '1', '+'),
						'from_name'			=> $fromName,
						'from_email'		=> $fromEmail,
						'header_content'	=> $header_content,
						'footer_content'	=> $footer_content,
						'fileName' 			=> !empty($fileNameDir) ? $fileNameDir : '',
						'subject' 			=> $siteName . ' : Order-Booking-Confirmation : ' . $emailSubject,	//Customer Name,Sample Name,Batch No. & Order Booking No.
					);

					if (!empty($emailTemplateBlade) && !empty($userData['toEmails'])) {
						Mail::send($emailTemplateBlade, ['user' => $userData], function ($msg) use ($userData) {
							$msg->from($userData['from_email'], $userData['from_name']);
							if (!empty($userData['toEmails'])) $msg->to($userData['toEmails']);
							if (!empty($userData['ccEmails'])) $msg->cc($userData['ccEmails']);
							$msg->subject($userData['subject']);
							if (!empty($userData['fileName'])) $msg->attach($userData['fileName']);
						});
						$mailStatus = $totalRecipient == count(Mail::failures()) ? '0' : '1';		//Getting Mail Status	
					} else {
						$mailStatus = '0'; 	//If Mail Template Not Found/Getting Mail Status	
					}

					//Saving Record in Order Mail Detail
					$dataSave 				= array();
					$dataSave['mail_content_type'] 	= '2';
					$dataSave['order_id'] 	 		= $orderDetail->order_id;
					$dataSave['customer_id'] 		= $orderDetail->customer_id;
					$dataSave['mail_header'] 		= $userData['subject'];
					$dataSave['mail_body'] 	 		= !empty($emailTemplateBlade) ? $emailTemplateBlade : NULL;
					$dataSave['mail_date'] 	 		= !empty($values->smd_date) ? trim(date('Y-m-d', strtotime($values->smd_date)) . ' ' . date("H:i:s")) : date('Y-m-d H:i:s');
					$dataSave['mail_by'] 	 		= '1';
					$dataSave['mail_status'] 		= $mailStatus;
					$dataSave['created_at'] 		= !empty($values->smd_date) ? trim(date('Y-m-d', strtotime($values->smd_date)) . ' ' . date("H:i:s")) : date('Y-m-d H:i:s');
					$dataSave['updated_at'] 		= !empty($values->smd_date) ? trim(date('Y-m-d', strtotime($values->smd_date)) . ' ' . date("H:i:s")) : date('Y-m-d H:i:s');
					DB::table('order_mail_dtl')->insertGetId($dataSave);

					if ($mailStatus) {
						//Deleting Send Mail Raw Data
						DB::table('scheduled_mail_dtl')->where('scheduled_mail_dtl.smd_content_type', '=', '2')->where('smd_id', '=', $values->smd_id)->delete();
					} else {
						//Updating Mail end Counter
						$counterDetail = DB::table('scheduled_mail_dtl')->where('smd_id', '=', $values->smd_id)->first();
						DB::table('scheduled_mail_dtl')->where('scheduled_mail_dtl.smd_id', '=', $values->smd_id)->update(['scheduled_mail_dtl.smd_mail_counter' => $counterDetail->smd_mail_counter + 1]);
					}

					//Deleting Temp Directory File
					!empty($userData['fileName']) ? File::delete($userData['fileName']) : '';
				}
			}
		}
	}

	/****************************************
	 *function    : Sending Scheduled MIS-Report Detail
	 *Created By  : Praveen Singh
	 *Created On  : 24-June-2019
	 *Modified by : Praveen Singh
	 *Modified On : 24-June-2019
	 *****************************************/
	public function sendScheduledMisReportDetail($mailingData)
	{

		global $models, $order, $report, $invoice, $schMisRepDtl, $misReport;

		$siteName       = defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';
		$currentDate	= defined('CURRENTDATE') ? CURRENTDATE : date('Y-m-d');
		$dirName      	= defined('MIS_PATH') ? ROOT_DIR . MIS_PATH : '/var/www/html/itcerp/public/images/mis/';
		$mailStatus     = '0';
		$attachments	= array();

		$scheduledMisReportMasterData = DB::table('scheduled_mis_report_dtls')
			->join('divisions', 'divisions.division_id', 'scheduled_mis_report_dtls.smrd_division_id')
			->join('product_categories', 'product_categories.p_category_id', 'scheduled_mis_report_dtls.smrd_product_category_id')
			->select('scheduled_mis_report_dtls.*', 'product_categories.p_category_name', 'divisions.division_name')
			->groupBy('scheduled_mis_report_dtls.smrd_division_id', 'scheduled_mis_report_dtls.smrd_product_category_id', 'scheduled_mis_report_dtls.smrd_mis_report_id')
			->orderBy('scheduled_mis_report_dtls.smrd_product_category_id', 'ASC')
			->get()
			->toArray();

		if (!empty($scheduledMisReportMasterData)) {
			foreach ($scheduledMisReportMasterData as $key => $values) {

				$attachments		 	= array();
				$division_id 	         = !empty($values->smrd_division_id) ? $values->smrd_division_id : '0';
				$product_category_id     = !empty($values->smrd_product_category_id) ? $values->smrd_product_category_id : '0';
				$mis_report_id           = !empty($values->smrd_mis_report_id) ? $values->smrd_mis_report_id : '0';
				$values->is_display_pcd  = $product_category_id == '2' && defined('PHARMA_BACK_DATE_VIEW') && PHARMA_BACK_DATE_VIEW ? '1' : '0';
				$uploadPath		 		= $dirName . $division_id . $product_category_id . $mis_report_id;
				$divisionName    	 	= !empty($values->division_name) ? strtoupper($values->division_name) : '-';
				$departmentName    	 	= !empty($values->p_category_name) ? strtoupper($values->p_category_name) : '-';
				$requiredDate   	 	= $models->validateSundayHoliday_v2($division_id, $currentDate, '1', '+');

				if ($division_id && $models->validateDateIsHolidayOrSunday($currentDate, $division_id) && $product_category_id && $mis_report_id) {

					if ($mis_report_id == '6') {					//TAT Report

						//Getting Primary and Seconday Email IDS
						$priSecEmailAddresses  = $schMisRepDtl->getSchMisPriSecEmailAddresses($values);

						//Last Required(3) Month TAT Department and Branch Wise
						$requiredBeforeDate    = $models->get_no_of_month_before_date($requiredDate, '3');
						$fullTatReportFileData = $schMisRepDtl->generate_scheduled_tat_report($values, $requiredBeforeDate, '1');
						$attachments[]         = $models->saveExcel($fullTatReportFileData, $uploadPath);

						//Due Today-reports having expected due date of today but not approved. = 25-06-2019
						$todayDueDate 		= $models->validateSundayHoliday_v2($division_id, $models->add_days_in_date($requiredDate, '1'), '1', '+');
						$dueTodayReportFileData = $schMisRepDtl->generate_scheduled_tat_report($values, $todayDueDate, '2');
						$attachments[]  	= $models->saveExcel($dueTodayReportFileData, $uploadPath);

						//Over Due - reports having expected due date till yesterday but not approved. <= 24-06-2019
						$overDueReportFileData = $schMisRepDtl->generate_scheduled_tat_report($values, $requiredDate, '3');
						$attachments[] 	       = $models->saveExcel($overDueReportFileData, $uploadPath);

						//Advance - reports having expected due date of tomorrow but not approved. = 26-06-2019
						$advanceDate 	       = $models->validateSundayHoliday_v2($division_id, $models->add_days_in_date($models->validateSundayHoliday_v2($division_id, $models->add_days_in_date($requiredDate, '1'), '1', '+'), '1'), '1', '+');
						$advanceReportFileData = $schMisRepDtl->generate_scheduled_tat_report($values, $advanceDate, '4');
						$attachments[]         = $models->saveExcel($advanceReportFileData, $uploadPath);

						//Approved today - reports having approved date of today. = 24-06-2019
						$approvedReportFileData = $schMisRepDtl->generate_scheduled_tat_report($values, $requiredDate, '5');
						$attachments[] 	        = $models->saveExcel($approvedReportFileData, $uploadPath);
					}

					list($header_content, $footer_content) 	   = $order->getDynamicHeaderFooterTemplate($template_type_id = '5', $division_id, $product_category_id);
					list($emailTemplateBlade, $fromName, $fromEmail) = $models->__mailSettingVariables($mailingData['mailTemplateType'] = '7');
					$emailSubject 				   = $siteName . ' : ' . $divisionName . ' : ' . $departmentName . ' : TAT-REPORT(' . date('d-m-Y', strtotime($todayDueDate)) . ')';
					$totalRecipient 				   = !empty($priSecEmailAddresses) ? count($priSecEmailAddresses['smrd_to_email_address']) + count($priSecEmailAddresses['smrd_from_email_address']) : '0';

					$userData = array(
						'toEmails'    		=> $models->validateMailEmailIds($priSecEmailAddresses['smrd_to_email_address']),
						'ccEmails'    		=> $models->validateMailEmailIds($priSecEmailAddresses['smrd_from_email_address']),
						'from_name'			=> $fromName,
						'from_email'		=> $fromEmail,
						'header_content' 	=> $header_content,
						'footer_content' 	=> $footer_content,
						'main_content'		=> $schMisRepDtl->get_main_content_data($values, $requiredDate, $todayDueDate, $advanceDate, $dueTodayReportFileData, $overDueReportFileData, $advanceReportFileData),
						'fileNameArray'		=> $attachments,
						'subject' 			=> $emailSubject,
					);

					if (!empty($emailTemplateBlade) && !empty($userData['toEmails'])) {
						Mail::send($emailTemplateBlade, ['user' => $userData], function ($msg) use ($userData) {
							$msg->from($userData['from_email'], $userData['from_name']);
							if (!empty($userData['toEmails'])) $msg->to($userData['toEmails']);
							if (!empty($userData['ccEmails'])) $msg->cc($userData['ccEmails']);
							$msg->subject($userData['subject']);
							if (!empty($userData['fileNameArray'])) {
								foreach ($userData['fileNameArray'] as $fileNameDir) {
									$msg->attach($fileNameDir);
								}
							}
						});
					}
				}
			}
		}
	}
}
