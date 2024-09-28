<?php

/*****************************************************
 *Mail Sending Configuration File
 *Created By:Praveen-Singh
 *Created On : 15-Jan-2018
 *Modified On : 28-May-2018
 *Package : ITC-ERP-PKL
 ******************************************************/

namespace App;

use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;
use App\Models;
use App\Order;
use App\Customer;
use App\InvoiceHdr;
use App\StabilityOrderPrototype;

class SendMail extends Model
{

	public function __construct()
	{

		global $models, $order, $invoice, $stbOrderPrototype;

		$models 			= new Models();
		$order          	= new Order();
		$invoice        	= new invoiceHdr();
		$stbOrderPrototype 	= new StabilityOrderPrototype();
	}

	/****************************************
	 *send mail to customer
	 *****************************************/
	function sendMail($requiredData)
	{

		global $models, $order, $invoice;

		/*******************************************************************************************************
		 *if mailTemplateType = 1 then mail for sample booked(New Customer).
		 *if mailTemplateType = 2 then mail for order placed.
		 *if mailTemplateType = 3 then mail for Report Generation.
		 *if mailTemplateType = 4 then mail for invoice Generation.
		 *if mailTemplateType = 5 then mail for stability prototype order confirmation.
		 *if mailTemplateType = 6 then mail for stability order Notification.
		 *if mailTemplateType = 7 then mail for order Hold Notification.
		 *if mailTemplateType = 8 then mail for Expected Due Date Change
		 ********************************************************************************************************/

		if (defined('SEND_MAIL_NOTIFICATION') && SEND_MAIL_NOTIFICATION  == '1') {
			if ($requiredData['mailTemplateType'] == '1') {
				return $this->sendMailToCustomerOnSampleBooking($requiredData);
			}
			if ($requiredData['mailTemplateType'] == '2') {
				return $this->sendMailToCustomerOnOrderPlace($requiredData);
			}
			if ($requiredData['mailTemplateType'] == '3') {
				return $this->sendMailToCustomerForReport($requiredData);
			}
			if ($requiredData['mailTemplateType'] == '4') {
				return $this->sendMailToCustomerForInvoice($requiredData);
			}
			if ($requiredData['mailTemplateType'] == '5') {
				return $this->sendMailToCustomerForStabilityOrder($requiredData);
			}
			if ($requiredData['mailTemplateType'] == '6') {
				return $this->sendMailToCustomerForStabilityOrderNotification($requiredData);
			}
			if ($requiredData['mailTemplateType'] == '7') {
				return $this->sendMailToCustomerOnOrderHoldNotification($requiredData);
			}
			if ($requiredData['mailTemplateType'] == '8') {
				return $this->sendMailToCustomerOnEdDateChangeNotification($requiredData);
			}
		}
		return true;
	}

	/************************************************************
	 **if mailTemplateType = 1 then send Mail To Customer On Sample Booking
	 *
	 *send Mail To Customer On New Customer Sample Registration
	 ************************************************************/
	function sendMailToCustomerOnSampleBooking($mailingData)
	{

		global $models, $order, $invoice;

		$dataSave   	= array();
		$mailStatus	= '0';
		$sampleId   	= !empty($mailingData['sample_id']) ? $mailingData['sample_id'] : '0';
		$adminMail  	= 'deepika.sharma@itclabs.com';
		$siteName   	= defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';
		$totalRecipient = '1';
		$sampleData 	= DB::table('samples')->where('samples.sample_id', '=', $sampleId)->select('samples.*')->first();

		//Getting Mail Setting variables
		list($emailTemplateBlade, $fromName, $fromEmail) = $models->__mailSettingVariables($mailingData['mailTemplateType']);

		//Setting Mailing Data
		$userData = array(
			'adminEmail' => $adminMail,
			'name'       => $sampleData->customer_name,
			'email'      => $sampleData->customer_email,
			'sampleNo'   => $sampleData->sample_no,
			'from_name'  => $fromName,
			'from_email' => $fromEmail,
			'subject'    => $siteName . ' : New Customer Registration Request from Sample Receiving!'
		);

		//Sending Mail
		if (!empty($emailTemplateBlade) && !empty($models->validateMailEmailIds($userData['adminEmail']))) {
			Mail::send($emailTemplateBlade, ['user' => $userData], function ($msg) use ($userData) {
				$msg->from($userData['from_email'], $userData['from_name']);
				$msg->to($userData['adminEmail']);
				$msg->subject($userData['subject']);
			});
			$mailStatus = $totalRecipient == count(Mail::failures()) ? 0 : 1;
		}

		$dataSave['mail_content_type'] 	= $mailingData['mailTemplateType'];
		$dataSave['customer_id'] 	= $sampleData->customer_id;
		$dataSave['mail_header'] 	= $userData['subject'];
		$dataSave['mail_body'] 	 	= $emailTemplateBlade;
		$dataSave['mail_date'] 	 	= CURRENTDATETIME;
		$dataSave['mail_by'] 	 	= USERID;
		$dataSave['mail_status'] 	= $mailStatus;
		DB::table('order_mail_dtl')->insertGetId($dataSave);
		return $mailStatus;

		return Mail::failures() ? 0 : 1;
	}

	/************************************************************
	 **if mailTemplateType = 2 then send Mail To Customer On Order Place
	 *
	 *send Mail To Customer On Order Place
	 ************************************************************/
	function sendMailToCustomerOnOrderPlace($mailingData)
	{

		global $models, $order, $invoice;

		$mailStatus = '0';
		$dataSave  = array();
		$orderId   = !empty($mailingData['order_id']) ? $mailingData['order_id'] : '0';
		$siteName  = defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';

		if ($orderId) {
			$orderDetail = $order->getOrder($orderId);
			if (!empty($orderDetail->order_no)) {

				list($header_content, $footer_content) = $order->getDynamicHeaderFooterTemplate($template_type_id = '4', $orderDetail->division_id, $orderDetail->product_category_id);
				list($emailTemplateBlade, $fromName, $fromEmail) = $models->__mailSettingVariables($mailingData['mailTemplateType']);
				$emailSubject   = implode(' | ', array($orderDetail->customer_name, $orderDetail->sample_description, $orderDetail->batch_no, $orderDetail->order_no));
				$totalRecipient = count($orderDetail->to_emails) + count($orderDetail->cc_emails);

				$userData = array(
					'name'    		=> $orderDetail->contact_name1,
					'toEmails'    		=> $models->validateMailEmailIds($orderDetail->to_emails),
					'ccEmails'    		=> $models->validateMailEmailIds($orderDetail->cc_emails),
					'order_no'		=> $orderDetail->order_no,
					'sample_name'		=> $orderDetail->sample_description,
					'batch_no'		=> $orderDetail->batch_no,
					'expected_due_date'	=> $orderDetail->expected_due_date,
					'from_name'		=> $fromName,
					'from_email'		=> $fromEmail,
					'expected_due_date'	=> $orderDetail->expected_due_date,
					'header_content'	=> $header_content,
					'footer_content'	=> $footer_content,
					'subject' 		=> $siteName . ' : Order-Booking-Confirmation : ' . $emailSubject,	//Customer Name,Sample Name,Batch No. & Order Booking No.
				);

				if (!empty($emailTemplateBlade) && !empty($userData['toEmails'])) {
					Mail::send($emailTemplateBlade, ['user' => $userData], function ($msg) use ($userData) {
						$msg->from($userData['from_email'], $userData['from_name']);
						if (!empty($userData['toEmails'])) $msg->to($userData['toEmails']);
						if (!empty($userData['ccEmails'])) $msg->cc($userData['ccEmails']);
						$msg->subject($userData['subject']);
					});
					$mailStatus = $totalRecipient == count(Mail::failures()) ? 0 : 1;
				}

				$dataSave['mail_content_type'] 	= $mailingData['mailTemplateType'];
				$dataSave['order_id'] 	 	= $orderDetail->order_id;
				$dataSave['customer_id'] 	= $orderDetail->customer_id;
				$dataSave['mail_header'] 	= $userData['subject'];
				$dataSave['mail_body'] 	 	= $emailTemplateBlade;
				$dataSave['mail_date'] 	 	= CURRENTDATETIME;
				$dataSave['mail_by'] 	 	= USERID;
				$dataSave['mail_status'] 	= $mailStatus;
				DB::table('order_mail_dtl')->insertGetId($dataSave);
				return $mailStatus;
			}
		}
		return false;
	}

	/************************************************************
	 **if mailTemplateType = 3 then mail for Report Generation.
	 *
	 *Send Mail To Customer On Report Generation
	 ************************************************************/
	function sendMailToCustomerForReport($mailingData)
	{

		global $models, $order, $invoice;

		$mailStatus 		= '0';
		$dirName     		= SITE_URL . REPORT_PATH;
		$siteName    		= defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';
		$dataSave    		= array();
		$reportId    		= !empty($mailingData['order_id']) ? $mailingData['order_id'] : '0';
		$reportTypeMailType = !empty($mailingData['postedData']['downloadType']) ? $mailingData['postedData']['downloadType'] : '0';

		if (!empty($reportId)) {
			$orderDetail = $order->getOrder($reportId);
			if (!empty($orderDetail->report_no)) {

				list($header_content, $footer_content) 			 = $order->getDynamicHeaderFooterTemplate($template_type_id = '5', $orderDetail->division_id, $orderDetail->product_category_id);
				list($emailTemplateBlade, $fromName, $fromEmail) = $models->__mailSettingVariables($mailingData['mailTemplateType']);
				$emailSubject   								 = implode(' | ', array($orderDetail->customer_name, $orderDetail->sample_description, $orderDetail->batch_no, $orderDetail->order_no));
				$totalRecipient 								 = count($orderDetail->to_emails) + count($orderDetail->cc_emails);

				$userData = array(
					'name'     			=> $orderDetail->contact_name1,
					'email'   			=> $orderDetail->customer_email,
					'toEmails'    		=> $models->validateMailEmailIds($orderDetail->to_emails),
					'ccEmails'    		=> $models->validateMailEmailIds($orderDetail->cc_emails),
					'order_no'			=> $orderDetail->order_no,
					'sample_name'		=> $orderDetail->sample_description,
					'fileName' 			=> $orderDetail->order_no . '.pdf',
					'dirName'  			=> $dirName,
					'from_name'			=> $fromName,
					'from_email'		=> $fromEmail,
					'header_content'	=> $header_content,
					'footer_content'	=> $footer_content,
					'subject'  			=> $siteName . ' : TEST REPORT : ' . $emailSubject,	//Customer Name,Sample Name,Batch No. & Order Booking No.
				);

				if (!empty($emailTemplateBlade) && !empty($userData['toEmails'])) {
					Mail::send($emailTemplateBlade, ['user' => $userData], function ($msg) use ($userData) {
						$msg->from($userData['from_email'], $userData['from_name']);
						if (!empty($userData['toEmails'])) $msg->to($userData['toEmails']);
						if (!empty($userData['ccEmails'])) $msg->cc($userData['ccEmails']);
						$msg->subject($userData['subject']);
						$msg->attach($userData['dirName'] . $userData['fileName']);
					});
					$mailStatus = $totalRecipient == count(Mail::failures()) ? 0 : 1;
				}

				$dataSave['mail_content_type'] 	= $mailingData['mailTemplateType'];
				$dataSave['order_id'] 	 	= $orderDetail->order_id;
				$dataSave['customer_id'] 	= $orderDetail->customer_id;
				$dataSave['mail_header'] 	= $userData['subject'];
				$dataSave['mail_body'] 	 	= $emailTemplateBlade;
				$dataSave['mail_date'] 	 	= CURRENTDATETIME;
				$dataSave['mail_type'] 	 	= $reportTypeMailType;
				$dataSave['mail_by'] 	 	= USERID;
				$dataSave['mail_status'] 	= $mailStatus;
				$mailId						= DB::table('order_mail_dtl')->insertGetId($dataSave);

				if (!empty($mailId) && !empty($mailStatus)) {
					DB::table('order_mail_dtl')->where('order_mail_dtl.mail_content_type', $mailingData['mailTemplateType'])->where('order_mail_dtl.order_id', $orderDetail->order_id)->update(['order_mail_dtl.mail_active_type' => NULL]);
					DB::table('order_mail_dtl')->where('order_mail_dtl.mail_content_type', $mailingData['mailTemplateType'])->where('order_mail_dtl.mail_id', $mailId)->update(['order_mail_dtl.mail_active_type' => '1']);
				}
				return $mailStatus;
			}
		}
		return false;
	}

	/************************************************************
	 **if mailTemplateType = 4 then mail for Invoice Generation.
	 *
	 *Send Mail To Customer On Invoice Generation
	 ************************************************************/
	function sendMailToCustomerForInvoice($mailingData)
	{

		global $models, $order, $invoice;

		$mailStatus = '0';
		$dirName    = SITE_URL . INVOICE_PATH;
		$siteName   = defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';
		$dataSave   = array();
		$invoiceId  = !empty($mailingData['invoice_id']) ? $mailingData['invoice_id'] : '0';

		if ($invoiceId) {
			$invoiceData = $invoice->getInvoiceData($invoiceId);
			if (!empty($invoiceData['invoiceHeader']['invoice_no'])) {

				list($header_content, $footer_content) 			 = $order->getDynamicHeaderFooterTemplate($template_type_id = '6', $invoiceData['invoiceHeader']['division_id'], $invoiceData['invoiceHeader']['product_category_id']);
				list($emailTemplateBlade, $fromName, $fromEmail) = $models->__mailSettingVariables($mailingData['mailTemplateType']);
				$totalRecipient 								 = count($invoiceData['invoiceHeader']['to_emails']) + count($invoiceData['invoiceHeader']['cc_emails']);

				$userData = array(
					'name'    			=> $invoiceData['invoiceHeader']['contact_name1'],
					'email'   			=> $invoiceData['invoiceHeader']['customer_email'],
					'toEmails'    		=> $models->validateMailEmailIds($invoiceData['invoiceHeader']['to_emails']),
					'ccEmails'    		=> $models->validateMailEmailIds($invoiceData['invoiceHeader']['cc_emails']),
					'invoice_file_name'	=> $invoiceData['invoiceHeader']['invoice_no'] . '.pdf',
					'dirName'			=> $dirName,
					'order_no'			=> $invoiceData['invoiceHeader']['order_no'],
					'invoice_no'		=> $invoiceData['invoiceHeader']['invoice_no'],
					'from_name'			=> $fromName,
					'from_email'		=> $fromEmail,
					'header_content'	=> $header_content,
					'footer_content'	=> $footer_content,
					'subject' 			=> $siteName . ' : Invoice Mail!',
				);

				if (!empty($emailTemplateBlade) && !empty($userData['toEmails'])) {
					Mail::send($emailTemplateBlade, ['user' => $userData], function ($msg) use ($userData) {
						$msg->from($userData['from_email'], $userData['from_name']);
						if (!empty($userData['toEmails'])) $msg->to($userData['toEmails']);
						if (!empty($userData['ccEmails'])) $msg->cc($userData['ccEmails']);
						$msg->subject($userData['subject']);
						$msg->attach($userData['dirName'] . $userData['invoice_file_name']);
					});
					$mailStatus = $totalRecipient == count(Mail::failures()) ? 0 : 1;
				}

				$dataSave['mail_content_type'] 	= $mailingData['mailTemplateType'];
				$dataSave['invoice_id'] 		= $invoiceData['invoiceHeader']['invoice_id'];
				$dataSave['customer_id'] 		= $invoiceData['invoiceHeader']['customer_id'];
				$dataSave['mail_header'] 		= $userData['subject'];
				$dataSave['mail_body'] 	 		= $emailTemplateBlade;
				$dataSave['mail_date'] 	 		= CURRENTDATETIME;
				$dataSave['mail_by'] 	 		= USERID;
				$dataSave['mail_status'] 		= $mailStatus;
				$mailId 						= DB::table('order_mail_dtl')->insertGetId($dataSave);

				if (!empty($mailId) && !empty($mailStatus)) {
					DB::table('order_mail_dtl')->where('order_mail_dtl.mail_content_type', $mailingData['mailTemplateType'])->where('order_mail_dtl.invoice_id', $invoiceData['invoiceHeader']['invoice_id'])->update(['order_mail_dtl.mail_active_type' => NULL]);
					DB::table('order_mail_dtl')->where('order_mail_dtl.mail_content_type', $mailingData['mailTemplateType'])->where('order_mail_dtl.mail_id', $mailId)->update(['order_mail_dtl.mail_active_type' => '1']);
				}
				return $mailStatus;
			}
		}
		return false;
	}

	/************************************************************
	 **if mailTemplateType = 5 then mail for stability prototype order confirmation.
	 *
	 *Send Mail To Customer for stability Prototype order confirmation.
	 ************************************************************/
	function sendMailToCustomerForStabilityOrder($mailingData)
	{

		global $models, $order, $invoice, $stbOrderPrototype;

		$mailStatus  = '0';
		$dataSave    = array();
		$siteName    = defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';
		$stabilityId = !empty($mailingData['stability_id']) ? $mailingData['stability_id'] : '0';

		if ($stabilityId) {

			$StabilityData 			   			= $stbOrderPrototype->getStabilityOrder($stabilityId);
			$StabilityData->stb_prototype_date 	= !empty($StabilityData->stb_prototype_date) ? date(DATEFORMAT, strtotime($StabilityData->stb_prototype_date)) : '';
			$divisionId 		   	   			= !empty($StabilityData->stb_division_id) ? $StabilityData->stb_division_id : '0';
			$productCategoryId 		   			= !empty($StabilityData->stb_product_category_id) ? $StabilityData->stb_product_category_id : '0';

			if (!empty($StabilityData) && !empty($divisionId) && !empty($productCategoryId)) {

				list($header_content, $footer_content) 			 = $order->getDynamicHeaderFooterTemplate($template_type_id = '7', $divisionId, $productCategoryId);
				list($emailTemplateBlade, $fromName, $fromEmail) = $models->__mailSettingVariables($mailingData['mailTemplateType']);
				$stabilityDetail 								 = $stbOrderPrototype->getStabilityData($stabilityId);
				$totalRecipient 								 = count($StabilityData->to_emails) + count($StabilityData->cc_emails);

				$userData = array(
					'name'    			=> $StabilityData->customer_name,
					'email'   			=> $StabilityData->customer_email,
					'toEmails'    		=> $models->validateMailEmailIds($StabilityData->to_emails),
					'ccEmails'    		=> $models->validateMailEmailIds($StabilityData->cc_emails),
					'prototype_order_no' => $StabilityData->stb_prototype_no,
					'from_name'			=> $fromName,
					'from_email'		=> $fromEmail,
					'header_content'	=> $header_content,
					'footer_content'	=> $footer_content,
					'subject' 			=> $siteName . ' : Stability-Order-Booking-Confirmation!',
				);

				if (!empty($emailTemplateBlade) && !empty($models->validateMailEmailIds($userData['toEmails']))) {
					Mail::send($emailTemplateBlade, ['user' => $userData, 'stbOrderList' => $StabilityData, 'returnData' => $stabilityDetail], function ($msg) use ($userData) {
						$msg->from($userData['from_email'], $userData['from_name']);
						if (!empty($userData['toEmails'])) $msg->to($userData['toEmails']);
						if (!empty($userData['ccEmails'])) $msg->cc($userData['ccEmails']);
						$msg->subject($userData['subject']);
					});
					$mailStatus = $totalRecipient == count(Mail::failures()) ? 0 : 1;
				}

				$dataSave['mail_content_type'] 	= $mailingData['mailTemplateType'];
				$dataSave['stb_order_hdr_id'] 	= $StabilityData->stb_order_hdr_id;
				$dataSave['customer_id'] 		= $StabilityData->stb_customer_id;
				$dataSave['mail_header'] 		= $userData['subject'];
				$dataSave['mail_body'] 	 		= $emailTemplateBlade;
				$dataSave['mail_date'] 	 		= CURRENTDATETIME;
				$dataSave['mail_by'] 	 		= USERID;
				$dataSave['mail_status'] 		= $mailStatus;
				$mailId 						= DB::table('order_mail_dtl')->insertGetId($dataSave);
				if (!empty($mailId) && !empty($mailStatus)) {
					DB::table('order_mail_dtl')->where('order_mail_dtl.mail_content_type', $mailingData['mailTemplateType'])->where('order_mail_dtl.invoice_id', $StabilityData->stb_order_hdr_id)->update(['order_mail_dtl.mail_active_type' => NULL]);
					DB::table('order_mail_dtl')->where('order_mail_dtl.mail_content_type', $mailingData['mailTemplateType'])->where('order_mail_dtl.mail_id', $mailId)->update(['order_mail_dtl.mail_active_type' => '1']);
				}
				return $mailStatus;
			}
		}
		return false;
	}

	/************************************************************
	 **if mailTemplateType = 6 then mail for stability order notification.
	 *
	 *Send Mail To Customer for stability order notification.
	 ************************************************************/
	function sendMailToCustomerForStabilityOrderNotification($mailingData)
	{

		global $models, $order, $invoice, $stbOrderPrototype;

		$mailStatus  		= '0';
		$dataSave   		= array();
		$siteName    		= defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';
		$stbOrderHdrDtlIds 	= !empty($mailingData['stb_order_hdr_dtl_id']) ? $mailingData['stb_order_hdr_dtl_id'] : '0';

		if (!empty($stbOrderHdrDtlIds) && is_array($stbOrderHdrDtlIds)) {
			foreach ($stbOrderHdrDtlIds as $key => $stbOrderHdrDtlId) {

				$stbOrderHdrDtlData = $stbOrderPrototype->getStabilityOrderHdrDtl($stbOrderHdrDtlId);
				$stabilityId        = !empty($stbOrderHdrDtlData->stb_order_hdr_id) ? $stbOrderHdrDtlData->stb_order_hdr_id : '0';

				if ($stabilityId) {

					//Getting Stability Order Detail
					$stabilityOrderData 					= $stbOrderPrototype->getStabilityOrder($stabilityId);
					$stabilityOrderData->stb_prototype_date = !empty($stabilityOrderData->stb_prototype_date) ? date(DATEFORMAT, strtotime($stabilityOrderData->stb_prototype_date)) : '';
					$divisionId	   							= !empty($StabilityData->stb_division_id) ? $StabilityData->stb_division_id : '0';
					$productCategoryId 						= !empty($stabilityOrderData->stb_product_category_id) ? $stabilityOrderData->stb_product_category_id : '0';

					//Getting Stability Prototype Detail wrt Stability Types
					$stabilityOrderPrototypesData = $stbOrderPrototype->getStabilityOrderPrototypesDtl($stbOrderHdrDtlId);
					$stabilityOrderOnePrototype   = !empty($stabilityOrderPrototypesData) ? reset($stabilityOrderPrototypesData) : array();
					$models->formatTimeStampFromArray($stabilityOrderPrototypesData, DATEFORMAT);

					if (!empty($stabilityOrderData) && !empty($divisionId) && !empty($productCategoryId)) {

						$stabilityDetail 								 = $stbOrderPrototype->getStabilityData($stabilityId);
						list($header_content, $footer_content) 			 = $order->getDynamicHeaderFooterTemplate($template_type_id = '7', $divisionId, $productCategoryId);
						list($emailTemplateBlade, $fromName, $fromEmail) = $models->__mailSettingVariables($mailingData['mailTemplateType']);
						$totalRecipient 								 = count($stabilityOrderData->to_emails) + count($stabilityOrderData->cc_emails);

						$userData = array(
							'name'    				=> $stabilityOrderData->customer_name,
							'email'   				=> $stabilityOrderData->customer_email,
							'toEmails'    			=> $models->validateMailEmailIds($stabilityOrderData->to_emails),
							'ccEmails'    			=> $models->validateMailEmailIds($stabilityOrderData->cc_emails),
							'prototype_order_no'	=> $stabilityOrderData->stb_prototype_no,
							'from_name'				=> $fromName,
							'from_email'			=> $fromEmail,
							'header_content'		=> $header_content,
							'footer_content'		=> $footer_content,
							'subject' 				=> $siteName . ' : Stability-Order-Notification!',
						);

						if (!empty($emailTemplateBlade) && !empty($userData['toEmails'])) {
							Mail::send($emailTemplateBlade, ['user' => $userData, 'stbOrderList' => $stabilityOrderData, 'stabilityOrderOnePrototype' => $stabilityOrderOnePrototype, 'stabilityOrderPrototypesList' => $stabilityOrderPrototypesData], function ($msg) use ($userData) {
								$msg->from($userData['from_email'], $userData['from_name']);
								if (!empty($userData['toEmails'])) $msg->to($userData['toEmails']);
								if (!empty($userData['ccEmails'])) $msg->cc($userData['ccEmails']);
								$msg->subject($userData['subject']);
							});
							$mailStatus = $totalRecipient == count(Mail::failures()) ? '0' : '1';
						}

						$dataSave['mail_content_type'] 	= $mailingData['mailTemplateType'];
						$dataSave['stb_order_hdr_id'] 	= $stabilityOrderData->stb_order_hdr_id;
						$dataSave['customer_id'] 		= $stabilityOrderData->stb_customer_id;
						$dataSave['mail_header'] 		= $userData['subject'];
						$dataSave['mail_body'] 	 		= $emailTemplateBlade;
						$dataSave['mail_date'] 	 		= CURRENTDATETIME;
						$dataSave['mail_by'] 	 		= USERID;
						$dataSave['mail_status'] 		= $mailStatus;
						$mailId 						= DB::table('order_mail_dtl')->insertGetId($dataSave);

						if (!empty($mailId) && !empty($mailStatus)) {
							DB::table('order_mail_dtl')->where('order_mail_dtl.mail_content_type', $mailingData['mailTemplateType'])->where('order_mail_dtl.invoice_id', $stabilityOrderData->stb_order_hdr_id)->update(['order_mail_dtl.mail_active_type' => NULL]);
							DB::table('order_mail_dtl')->where('order_mail_dtl.mail_content_type', $mailingData['mailTemplateType'])->where('order_mail_dtl.mail_id', $mailId)->update(['order_mail_dtl.mail_active_type' => '1']);
						}
					}
				}
			}
			return true;
		}
		return false;
	}

	/************************************************************
	 **if mailTemplateType = 7 then send Mail To Customer On Order Hold
	 *
	 *send Mail To Customer On Order Hold
	 ************************************************************/
	function sendMailToCustomerOnOrderHoldNotification($mailingData)
	{

		global $models, $order, $invoice;

		$mailStatus = '0';
		$dataSave   = array();
		$customerId = !empty($mailingData['customer_id']) ? $mailingData['customer_id'] : '0';
		$siteName   = defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';

		if ($customerId) {
			$customerDetail = DB::table('customer_master')->where('customer_master.customer_id', '=', $customerId)->first();
			if (!empty($customerDetail->customer_id)) {

				$loggedUserEmail     							  = User::where('role_id', '13')->where('id', USERID)->pluck('email')->first();
				$salesExecUser  								  = User::find($customerDetail->sale_executive);
				$comCrmUser										  = $models->getComCrmEmailAddresses($customerDetail->customer_id);
				list($header_content, $footer_content) 			  = $order->getDynamicHeaderFooterTemplate($template_type_id = '3');
				list($emailTemplateBlade, $fromName, $fromEmail)  = $models->__mailSettingVariables(8);
				list($toEmails, $ccEmails) 						  = $order->getCustomerEmailToCC($customerDetail->customer_id);
				$emailSubject   								  = implode(' | ', array($customerDetail->customer_code, $customerDetail->customer_name));
				$ccEmails										  = array_filter(array_merge($ccEmails, $comCrmUser, array($loggedUserEmail, $salesExecUser->email)));
				$totalRecipient 								  = count($toEmails) + count($ccEmails);

				$userData = array(
					'name'    			=> $customerDetail->customer_name,
					'toEmails'    		=> $models->validateMailEmailIds($toEmails),
					'ccEmails'    		=> $models->validateMailEmailIds($ccEmails),
					'from_name'			=> $fromName,
					'from_email'		=> $fromEmail,
					'header_content'	=> $header_content,
					'footer_content'	=> $footer_content,
					'subject' 			=> $siteName . ' : Order Hold Notification : ' . $emailSubject,	//Customer Name,Sample Name,Batch No. & Order Booking No.
				);

				if (!empty($emailTemplateBlade) && !empty($userData['toEmails'])) {
					Mail::send($emailTemplateBlade, ['user' => $userData], function ($msg) use ($userData) {
						$msg->from($userData['from_email'], $userData['from_name']);
						if (!empty($userData['toEmails'])) $msg->to($userData['toEmails']);
						if (!empty($userData['ccEmails'])) $msg->cc($userData['ccEmails']);
						$msg->subject($userData['subject']);
					});
					$mailStatus = $totalRecipient == count(Mail::failures()) ? 0 : 1;
				}

				$dataSave['mail_content_type'] 	= $mailingData['mailTemplateType'];
				$dataSave['order_id'] 	 		= NULL;
				$dataSave['customer_id'] 		= $customerId;
				$dataSave['mail_header'] 		= $userData['subject'];
				$dataSave['mail_body'] 	 		= $emailTemplateBlade;
				$dataSave['mail_date'] 	 		= CURRENTDATETIME;
				$dataSave['mail_by'] 	 		= USERID;
				$dataSave['mail_status'] 		= $mailStatus;
				DB::table('order_mail_dtl')->insertGetId($dataSave);
				return $mailStatus;
			}
		}
		return false;
	}

	/************************************************************
	 **if mailTemplateType = 8 then mail for Expected Due Date Change
	 *
	 *send Mail To Customer On Expected Due Dte Change
	 ************************************************************/
	function sendMailToCustomerOnEdDateChangeNotification($mailingData)
	{

		global $models, $order, $invoice;

		$mailStatus = '0';
		$dataSave   = array();

		$siteName   	 = defined('SITE_NAME') ? '[' . SITE_NAME . ']' : '[ITC-LAB]';
		$currentEddLogId = !empty($mailingData['current_edd_log_id']) ? $mailingData['current_edd_log_id'] : '0';
		$orderId 		 = !empty($mailingData['order_id']) ? $mailingData['order_id'] : '0';

		if (!empty($orderId)) {

			$orderDetail 									  = $order->getOrder($orderId);
			$customerDetail 								  = DB::table('customer_master')->where('customer_master.customer_id', $orderDetail->customer_id)->first();
			list($header_content, $footer_content) 			  = $order->getDynamicHeaderFooterTemplate(3);
			list($emailTemplateBlade, $fromName, $fromEmail)  = $models->__mailSettingVariables(9);
			list($toEmails, $ccEmails) 						  = $order->getCustomerEmailToCC($customerDetail->customer_id);
			$emailSubject   								  = implode(' | ', array($customerDetail->customer_code, $customerDetail->customer_name));
			$totalRecipient 								  = count($toEmails) + count($ccEmails);

			$userData = array(
				'name'    			=> $customerDetail->customer_name,
				'toEmails'    		=> $models->validateMailEmailIds($toEmails),
				'ccEmails'    		=> $models->validateMailEmailIds($ccEmails),
				'from_name'			=> $fromName,
				'from_email'		=> $fromEmail,
				'header_content'	=> $header_content,
				'footer_content'	=> $footer_content,
				'expected_due_date'	=> date(DATETIMEFORMAT, strtotime($orderDetail->expected_due_date)),
				'subject' 			=> $siteName . ' : Order Expected Due Date Notification : ' . $emailSubject,	//Customer Name,Sample Name,Batch No. & Order Booking No.
			);

			if (!empty($emailTemplateBlade) && !empty($userData['toEmails'])) {
				Mail::send($emailTemplateBlade, ['user' => $userData], function ($msg) use ($userData) {
					$msg->from($userData['from_email'], $userData['from_name']);
					if (!empty($userData['toEmails'])) $msg->to($userData['toEmails']);
					if (!empty($userData['ccEmails'])) $msg->cc($userData['ccEmails']);
					$msg->subject($userData['subject']);
				});
				$mailStatus = $totalRecipient == count(Mail::failures()) ? 0 : 1;
			}

			$dataSave['mail_content_type'] 	= $mailingData['mailTemplateType'];
			$dataSave['order_id'] 	 		= $orderId;
			$dataSave['customer_id'] 		= $customerDetail->customer_id;
			$dataSave['mail_header'] 		= $userData['subject'];
			$dataSave['mail_body'] 	 		= $emailTemplateBlade;
			$dataSave['mail_date'] 	 		= CURRENTDATETIME;
			$dataSave['mail_by'] 	 		= USERID;
			$dataSave['mail_status'] 		= $mailStatus;
			DB::table('order_mail_dtl')->insertGetId($dataSave);

			return $mailStatus;
		}

		return true;
	}
}
