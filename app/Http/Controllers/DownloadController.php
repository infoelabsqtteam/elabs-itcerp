<?php

/*****************************************************
 *Order Model File
 *Created By:Praveen-Singh
 *Created On : 15-Dec-2017
 *Modified On : 10-Oct-2018
 *Package : ITC-ERP-PKL
 ******************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Session;
use Validator;
use Route;
use App\Company;
use App\Order;
use App\Models;
use App\Report;
use App\Setting;
use App\ProductCategory;
use App\InvoiceHdr;
use App\InvoiceHdrDetail;
use App\NumbersToWord;
use App\SendMail;
use App\CreditNote;
use App\DebitNote;
use App\TrfHdr;
use App\StabilityOrderPrototype;

class DownloadController extends Controller
{
	/**
	 * protected Variable.
	 */
	protected $auth;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

		global $order, $report, $models, $invoice, $mail, $numbersToWord, $creditNote, $debitNote, $stbOrderPrototype, $trfHdr;

		$order   			= new Order();
		$report 			= new Report();
		$models  			= new Models();
		$invoice 			= new invoiceHdr();
		$numbersToWord  	= new NumbersToWord();
		$mail    			= new SendMail();
		$creditNote    		= new CreditNote();
		$debitNote  		= new DebitNote();
		$trfHdr 			= new TrfHdr();
		$stbOrderPrototype  = new StabilityOrderPrototype();

		$this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$this->auth = Auth::user();
			parent::__construct($this->auth);
			//Checking current request is allowed or not
			$allowedAction = array('index', 'navigation');
			$actionData    = explode('@', Route::currentRouteAction());
			$action        = !empty($actionData[1]) ? trim(strtolower($actionData[1])) : '0';
			if (defined('NOTALlOWEDTONAVIGATE') && empty(NOTALlOWEDTONAVIGATE) && in_array($action, $allowedAction)) {
				return redirect('dashboard')->withErrors('Permission Denied!');
			}
			return $next($request);
		});
	}

	/**
	 * generate Job Order PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateAnalyticalSheetIPdf(Request $request)
	{

		global $order, $report, $models, $invoice, $mail, $numbersToWord;

		if ($request->isMethod('post') && !empty($request->order_id) && !empty($request->downloadType) && !empty($request->generate_analytical_sheet_I_pdf)) {
			return $models->downloadPDF($request->all(), $contentType = 'order');
		} else {
			return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/**
	 * generate Job Order PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateAnalyticalSheetIIPdf(Request $request)
	{

		global $order, $report, $models, $invoice, $mail, $numbersToWord;

		if ($request->isMethod('post') && !empty($request->order_id) && !empty($request->downloadType) && !empty($request->generate_analytical_sheet_II_pdf)) {
			return $models->downloadPDF($request->all(), $contentType = 'jobSheet');
		} else {
			return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/**
	 * generate Job Order PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateReportPdf(Request $request)
	{

		global $order, $report, $models, $invoice, $mail, $numbersToWord;

		if ($request->isMethod('post') && !empty($request->order_id) && !empty($request->downloadType) && !empty($request->generate_report_pdf)) {

			$orderData = $order->getOrderDetail($request->order_id);
			if (!empty($orderData->customer_id) && !empty($order->isCustomerPutOnHold($orderData->customer_id))) {
				return redirect('dashboard')->with('alertMsg', config('messages.message.reportApprovingError'));
			} else {
				$defaultDateTime = !defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
				$currentDateTime = !empty($request->back_report_date) ? $models->getFormatedDateTime($request->back_report_date) : $defaultDateTime;

				//updating Order Staus and Log
				list($currentOrderStage, $nextOrderStage) = $report->getOrderStageWithOrWithoutAmendment($request->order_id);
				if (!empty($currentOrderStage) && !empty($nextOrderStage)) {
					$report->updateReportApprovingDate($request->order_id, $currentDateTime);							//Updating Report Reviewing Date
					$order->updateOrderStausLog($request->order_id, $currentOrderStage, $currentDateTime);					//Updating Previous Order Stage		
					$order->updateOrderStatusToNextPhase($request->order_id, $nextOrderStage, $currentDateTime);					//Updating Current/Next Order Stage		
				}
				//Updating and Generating NABL Code
				if (!empty($request->reportWithRightLogo) && in_array($request->reportWithRightLogo, array('7', '16'))) {
					 $report->updateGenerateNablCodeNoInReport($request->order_id, $request->all());
					
				}
				return $models->downloadSaveMailPDF($request->all(), $contentType = 'report');
			}
		} else {
			return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/**
	 * generate Job Order PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateInvoicePdf(Request $request)
	{

		global $order, $models, $invoice, $mail, $numbersToWord;

		if ($request->isMethod('post') && !empty($request->invoice_id) && !empty($request->downloadType) && !empty($request->generate_invoice_pdf)) {
			
			return $models->downloadSaveMailPDF($request->all(), $contentType = 'invoice');
		} else {
			return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/**
	 * generate Job Order PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateCreditNotePdf(Request $request)
	{

		global $order, $models, $invoice, $mail, $numbersToWord, $creditNote;

		if ($request->isMethod('post') && !empty($request->credit_note_id) && !empty($request->downloadType) && !empty($request->generate_credit_note_pdf)) {
			return $models->downloadSavePDF($request->all(), $contentType = 'credit_note');
		} else {
			return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/**
	 * generate Job Order PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateDebitNotePdf(Request $request)
	{

		global $order, $models, $invoice, $mail, $numbersToWord, $debitNote;

		if ($request->isMethod('post') && !empty($request->debit_note_id) && !empty($request->downloadType) && !empty($request->generate_debit_note_pdf)) {
			return $models->downloadSavePDF($request->all(), $contentType = 'debit_note');
		} else {
			return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 * Description : Download customers excel
	 * Date        : 14-08-18
	 * Created By  : Praveen Singh
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateCustomerMasterDocuments(Request $request)
	{

		global $models;

		$responseData = $filterData = array();

		$filterData = $request->all();

		$customerObj = DB::table('customer_master')
			->join('customer_contact_persons', 'customer_master.customer_id', '=', 'customer_contact_persons.customer_id')
			->join('customer_invoicing_types', 'customer_invoicing_types.invoicing_type_id', '=', 'customer_master.invoicing_type_id')
			->join('countries_db', 'countries_db.country_id', '=', 'customer_master.customer_country')
			->join('state_db', 'state_db.state_id', '=', 'customer_master.customer_state')
			->join('city_db', 'city_db.city_id', '=', 'customer_master.customer_city')
			->join('customer_billing_types', 'customer_master.billing_type', '=', 'customer_billing_types.billing_type_id')
			->join('users as saleExecutiveDb', 'customer_master.sale_executive', '=', 'saleExecutiveDb.id')
			->join('divisions as saleTerritoryDb', 'saleTerritoryDb.division_id', 'saleExecutiveDb.division_id')
			->join('users as u', 'customer_master.created_by', '=', 'u.id')
			->join('customer_types', 'customer_types.type_id', 'customer_master.customer_type')
			->join('customer_discount_types', 'customer_discount_types.discount_type_id', 'customer_master.discount_type')
			->join('customer_gst_categories', 'customer_gst_categories.cgc_id', '=', 'customer_master.customer_gst_category_id')
			->join('customer_gst_types', 'customer_gst_types.cgt_id', '=', 'customer_master.customer_gst_type_id')
			->join('customer_gst_tax_slab_types', 'customer_gst_tax_slab_types.cgtst_id', '=', 'customer_master.customer_gst_tax_slab_type_id')
			->leftJoin('customer_company_type', 'customer_master.company_type', '=', 'customer_company_type.company_type_id')
			->leftJoin('customer_ownership_type', 'customer_master.ownership_type', '=', 'customer_ownership_type.ownership_id')
			->leftJoin('order_sample_priority', 'order_sample_priority.sample_priority_id', '=', 'customer_master.customer_priority_id')
			->select('customer_master.customer_id as id', 'customer_master.customer_code', 'customer_master.logic_customer_code', 'customer_master.customer_name', 'customer_master.customer_email', 'customer_master.customer_mobile', 'customer_master.customer_phone', 'customer_master.customer_pincode', 'customer_master.customer_address', 'customer_master.customer_address as mailing_address', 'countries_db.country_name as customer_country', 'state_db.state_name as customer_state', 'city_db.city_name as customer_city', 'customer_types.customer_type', 'customer_billing_types.billing_type as billing_type', 'customer_invoicing_types.invoicing_type', 'saleTerritoryDb.division_name as sales_territory', 'saleExecutiveDb.name as sale_executive_name', 'customer_discount_types.discount_type', 'customer_master.discount_value', 'customer_master.mfg_lic_no', 'customer_master.customer_vat_cst', 'customer_company_type.company_type_name', 'customer_ownership_type.ownership_name', 'customer_master.owner_name', 'customer_master.customer_pan_no', 'customer_master.customer_tan_no', 'order_sample_priority.sample_priority_name as customer_priority_type', 'customer_gst_categories.cgc_name as customer_gst_category', 'customer_gst_types.cgt_name as customer_gst_type',			     'customer_gst_tax_slab_types.cgtst_name as customer_gst_tax_slab_type', 'customer_master.customer_gst_no', 'customer_master.bank_account_no', 'customer_master.bank_account_name', 'customer_master.bank_name', 'customer_master.bank_branch_name', 'customer_master.bank_rtgs_ifsc_code', 'customer_contact_persons.contact_name1', 'customer_contact_persons.contact_name2 as account_person_name', 'customer_contact_persons.contact_designate1', 'customer_contact_persons.contact_designate2 as account_person_designation', 'customer_contact_persons.contact_mobile1', 'customer_contact_persons.contact_mobile2 as account_person_mobile', 'customer_contact_persons.contact_email1', 'customer_contact_persons.contact_email2 as account_person_email', 'u.name as created_by', 'customer_master.created_at', 'u.name as created_by', 'customer_master.updated_at')
			->orderBy('customer_master.customer_id', 'ASC');

		//Get search customers data list
		$this->setCustomerSearchCondition($customerObj, $filterData);
		$customers = $customerObj->get()->toArray();

		if (!empty($customers)) {
			foreach ($customers as $key => $customerData) {
				$customerEmails = DB::table('customer_email_addresses')->where('customer_email_addresses.customer_id', $customerData->id)->pluck('customer_email')->all();
				$customerData->mailing_address = !empty($customerEmails) ? implode(', ', $customerEmails) : '';
			}
		}

		$customersList          	= !empty($customers) ? json_decode(json_encode($customers), true) : array();
		$filterData['heading'] 		= 'All Customers List :' . '(' . count($customers) . ')';
		$filterData['mis_report_name'] 	= 'Customers List';
		$responseData['tableHead'] 	= !empty($customersList) ? array_keys(end($customersList)) : array();
		$responseData['tableBody'] 	= !empty($customersList) ? $customersList : array();

		if (!empty($request->generate_customer_documents) && strtolower($request->generate_customer_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 * Description : Download customers excel for searched customers
	 * Date        : 14-08-18
	 * Created By  : Praveen Singh
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function setCustomerSearchCondition($customerObj, $filterData)
	{

		if (!empty($filterData['search_customer_code'])) {
			$customerObj->where('customer_master.customer_code', 'like', '%' . trim($filterData['search_customer_code']) . '%');
		}
		if (!empty($filterData['search_customer_name'])) {
			$customerObj->where('customer_master.customer_name', 'like', '%' . trim($filterData['search_customer_name']) . '%');
		}
		if (!empty($filterData['search_customer_address'])) {
			$customerObj->where('customer_master.customer_address', 'like', '%' . trim($filterData['search_customer_address']) . '%');
		}
		if (!empty($filterData['search_customer_email'])) {
			$customerObj->where('customer_master.customer_email', 'like', '%' . trim($filterData['search_customer_email']) . '%');
		}
		if (!empty($filterData['search_billing_type'])) {
			$customerObj->where('customer_billing_types.billing_type', 'like', '%' . trim($filterData['search_billing_type']) . '%');
		}
		if (!empty($filterData['search_invoicing_type'])) {
			$customerObj->where('customer_invoicing_types.invoicing_type', 'like', '%' . trim($filterData['search_invoicing_type']) . '%');
		}
		if (!empty($filterData['search_created_by'])) {
			$customerObj->where('u.name', 'like', '%' . trim($filterData['search_created_by']) . '%');
		}
		if (!empty($filterData['search_created_at'])) {
			$customerObj->where('customer_master.created_at', 'like', '%' . trim($filterData['search_created_at']) . '%');
		}
		if (!empty($filterData['search_updated_at'])) {
			$customerObj->where('customer_master.updated_at', 'like', '%' . trim($filterData['search_updated_at']) . '%');
		}
	}

	/************************************
	 * Description : Download product test excel
	 * Date        : 30-01-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateTestMasterDocuments(Request $request)
	{

		global $order, $models, $invoice, $mail, $numbersToWord;

		$user_id            		= defined('USERID') ? USERID : '0';
		$department_ids     		= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           		= defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids 		= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		$request['product_category_id'] = !empty($request['product_category_id']) ? array($request['product_category_id']) : $department_ids;

		$testParametersObj = DB::table('product_test_dtl')
			->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
			->join('product_master', 'product_master.product_id', 'product_test_hdr.product_id')
			->join('product_categories', 'product_master.p_category_id', 'product_categories.p_category_id')
			->leftJoin('product_categories as parent_category', 'product_categories.parent_id', 'parent_category.p_category_id')
			->leftJoin('product_categories as product_section', 'product_section.p_category_id', 'parent_category.parent_id')
			->join('test_standard', 'test_standard.test_std_id', 'product_test_hdr.test_standard_id')
			->leftJoin('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
			->leftJoin('test_parameter_categories', 'test_parameter.test_parameter_category_id', 'test_parameter_categories.test_para_cat_id')
			->leftJoin('equipment_type', 'product_test_dtl.equipment_type_id', 'equipment_type.equipment_id')
			->leftJoin('method_master', 'product_test_dtl.method_id', 'method_master.method_id')
			->leftJoin('detector_master', 'detector_master.detector_id', 'product_test_dtl.detector_id')
			->join('users', 'product_test_dtl.created_by', 'users.id')
			->select('product_test_dtl.product_test_dtl_id', 'product_test_hdr.test_code', 'product_categories.p_category_name as product_category', 'parent_category.p_category_name as product_sub_category', 'product_master.product_name', 'test_standard.test_std_name', 'product_test_hdr.wef', 'product_test_hdr.upto', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name as test_parameter', 'test_parameter_categories.test_para_cat_name as test_parameter_category', 'equipment_type.equipment_name as equipment', 'method_master.method_name as method', 'detector_master.detector_name', 'product_test_dtl.claim_dependent', 'product_test_dtl.parameter_decimal_place', 'product_test_dtl.standard_value_type', 'product_test_dtl.standard_value_from', 'product_test_dtl.standard_value_to', 'product_test_dtl.time_taken_days', 'product_test_dtl.time_taken_mins', 'product_test_dtl.parameter_nabl_scope', 'product_test_dtl.description', 'users.name as created_by', 'product_test_hdr.created_at as created_on');

		if (!empty($request->test_id)) {
			$test_id = !empty($request->test_id) ? $request->test_id  :  '0';
			$testParametersObj->where('product_test_dtl.test_id', $test_id);
		}
		if (!empty($request->product_category_id) && is_array($request['product_category_id'])) {
			$testParametersObj->whereIn('product_categories.p_category_id', $request->product_category_id);
			$testParametersObj->orWhereIn('product_section.p_category_id', $request->product_category_id);
		}

		//Filtering records according to search keyword
		$this->generateFiterTestMasterDocuments($testParametersObj, $request);

		$testProducts	= $testParametersObj->orderBy('product_test_dtl.test_id', 'ASC')->get();
		$models->formatTimeStampFromArrayExcel($testProducts, DATEFORMATEXCEL);

		$testProductsList          		= !empty($testProducts) ? json_decode(json_encode($testProducts), true) : array();
		$testProductsList 				= $models->unsetFormDataVariablesArray($testProductsList, array('canDispatchOrder'));
		$responseData['heading'] 		= 'Test Product List :' . '(' . count($testProductsList) . ')';
		$responseData['mis_report_name']= 'Test Product List';
		$responseData['tableHead'] 		= !empty($testProductsList) ? array_keys(end($testProductsList)) : array();
		$responseData['tableBody'] 		= !empty($testProductsList) ? $testProductsList : array();

		if (!empty($request->generate_product_test_documents) && $request->generate_product_test_documents == 'PDF') {
			$pdfHeaderContent 			= $models->getHeaderFooterTemplate();
			$responseData['header_content']	= $pdfHeaderContent->header_content;
			$responseData['footer_content']	= $pdfHeaderContent->footer_content;
			return $models->downloadPDF($responseData, $contentType = 'product_test');
		} else if (!empty($request->generate_product_test_documents) && $request->generate_product_test_documents == 'Excel') {
			return $models->generateExcel($responseData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 * Description : Download detectors excel
	 * Date        : 13-07-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateFiterTestMasterDocuments($testParametersObj, $request)
	{

		global $models;

		if (!empty($request->keyword)) {
			$keyword = $request->keyword;
			$testParametersObj->where('product_test_hdr.test_code', 'like', '%' . $keyword . '%')
				->Orwhere('product_test_hdr.test_code', 'like', '%' . $keyword . '%')
				->Orwhere('product_categories.p_category_name', '=', '%' . $keyword . '%')
				->Orwhere('product_master.product_name', 'like', '%' . $keyword . '%')
				->Orwhere('test_standard.test_std_name', 'like', '%' . $keyword . '%')
				->Orwhere('product_test_hdr.wef', 'like', '%' . $models->convertDateFormat($keyword) . '%')
				->Orwhere('product_test_hdr.upto', 'like', '%' . $models->convertDateFormat($keyword) . '%')
				->Orwhere('users.name', 'like', '%' . $keyword . '%')
				->Orwhere('product_section.p_category_name', 'like', '%' . $keyword . '%')
				->Orwhere('product_test_hdr.created_at', 'like', '%' . date("Y-m-d", strtotime($keyword)) . '%')
				->Orwhere('product_test_hdr.updated_at', 'like', '%' . $keyword . '%');
		}
		if (!empty($request->search_test_code)) {
			$testParametersObj->where('product_test_hdr.test_code', 'like', '%' . $request->search_test_code . '%');
		}
		if (!empty($request->search_p_category_id)) {
			$testParametersObj->where('product_categories.p_category_id', '=', $request->search_p_category_id);
		}
		if (!empty($request->search_product_name)) {
			$testParametersObj->where('product_master.product_name', 'like', '%' . $request->search_product_name . '%');
		}
		if (!empty($request->search_test_std_name)) {
			$testParametersObj->where('test_standard.test_std_name', 'like', '%' . $request->search_test_std_name . '%');
		}
		if (!empty($request->search_wef)) {
			$testParametersObj->where('product_test_hdr.wef', 'like', '%' . $models->convertDateFormat($request->search_wef) . '%');
		}
		if (!empty($request->search_upto)) {
			$testParametersObj->where('product_test_hdr.upto', 'like', '%' . $models->convertDateFormat($request->search_upto) . '%');
		}
		if (!empty($request->search_created_by)) {
			$testParametersObj->where('users.name', 'like', '%' . $request->search_created_by . '%');
		}
		if (!empty($request->search_product_section_name)) {
			$testParametersObj->where('product_section.p_category_name', 'like', '%' . $request->search_product_section_name . '%');
		}
		if (!empty($request->search_created_at)) {
			$testParametersObj->where('product_test_hdr.created_at', 'like', '%' . date("Y-m-d", strtotime($request->search_created_at)) . '%');
		}
		if (!empty($request->search_updated_at)) {
			$testParametersObj->where('product_test_hdr.updated_at', 'like', '%' . $request->search_updated_at . '%');
		}
	}

	/************************************
	 * Description : Download detectors excel
	 * Date        : 13-07-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateDetectorsDocuments(Request $request)
	{

		global $models;

		$returnData = array();

		$equipment_type_id = !empty($request->equipment_type_id) ? $request->equipment_type_id : '';

		if (!empty($request->generate_detector_documents) && $request->generate_detector_documents == 'Excel') {

			$detector = DB::table('detector_master')
				->join('equipment_type', 'detector_master.equipment_type_id', 'equipment_type.equipment_id')
				->join('product_categories', 'product_categories.p_category_id', '=', 'detector_master.product_category_id')
				->join('users', 'detector_master.created_by', 'users.id')
				->select('detector_master.detector_code', 'detector_master.detector_name', 'detector_master.detector_desc', 'equipment_type.equipment_name as equipment_type', 'product_categories.p_category_name as parent_category', 'users.name as createdBy', 'detector_master.created_at as created_on', 'detector_master.updated_at as updated_on');

			if (!empty($equipment_type_id)) {
				$detector->where('detector_master.equipment_type_id', '=', $equipment_type_id);
			}
			$allDetectorList = $detector->orderBy('detector_master.detector_id', 'ASC')->get();

			//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($allDetectorList, DATEFORMATEXCEL);

			$allDetectorList  			= !empty($allDetectorList) ? json_decode(json_encode($allDetectorList), true) : array();
			$allDetectorList 			= $models->unsetFormDataVariables($allDetectorList, array('canDispatchOrder'));
			$filterData['heading'] 		= 'All Detectors List(' . count($allDetectorList) . ')';
			$filterData['mis_report_name'] 	= 'detectors_list_';
			$responseData['tableHead'] 		= !empty($allDetectorList) ? array_keys(end($allDetectorList)) : array();
			$responseData['tableBody'] 		= !empty($allDetectorList) ? $allDetectorList : array();

			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect()->back()->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}
	/************************************
	 *
	 * Description : Download equipments excel
	 * Date        : 13-07-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateEquipmentsDocuments(Request $request)
	{

		global $models;

		if (!empty($request->generate_equipment_documents) && $request->generate_equipment_documents == 'Excel') {

			$allEquipmentsList = DB::table('equipment_type')
				->leftjoin('users', 'equipment_type.created_by', '=', 'users.id')
				->select('equipment_type.equipment_code', 'equipment_type.equipment_name', 'equipment_type.equipment_capacity', 'equipment_type.equipment_description', 'users.name as createdBy', 'equipment_type.created_at as created_on', 'equipment_type.updated_at as updated_on')
				->get();

			//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($allEquipmentsList, DATEFORMATEXCEL);

			$allEquipmentsList  		= !empty($allEquipmentsList) ? json_decode(json_encode($allEquipmentsList), true) : array();
			$allEquipmentsList 			= $models->unsetFormDataVariables($allEquipmentsList, array('canDispatchOrder'));
			$filterData['heading'] 		= 'All Equipments List(' . count($allEquipmentsList) . ')';
			$filterData['mis_report_name'] 	= 'equipments_list_';
			$responseData['tableHead'] 		= !empty($allEquipmentsList) ? array_keys(end($allEquipmentsList)) : array();
			$responseData['tableBody'] 		= !empty($allEquipmentsList) ? $allEquipmentsList : array();

			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect()->back()->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 *
	 * Description : Download methods excel
	 * Date        : 13-07-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateMethodsDocuments(Request $request)
	{

		global $models;

		$equipment_type_id = !empty($request->equipment_type_id) ? $request->equipment_type_id : '';

		if (!empty($request->generate_method_documents) && $request->generate_method_documents == 'Excel') {

			$method = DB::table('method_master')
				->join('equipment_type', 'method_master.equipment_type_id', 'equipment_type.equipment_id')
				->join('product_categories', 'product_categories.p_category_id', '=', 'method_master.product_category_id')
				->join('users', 'method_master.created_by', 'users.id')
				->select('method_master.method_code', 'method_master.method_name', 'method_master.method_desc', 'equipment_type.equipment_name as equipment_type', 'product_categories.p_category_name as parent_category', 'users.name as createdBy', 'method_master.created_at as created_on', 'method_master.updated_at as updated_on');

			if (!empty($equipment_type_id)) {
				$method->where('method_master.equipment_type_id', '=', $equipment_type_id);
			}
			$methodsList = $method->get();

			//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($methodsList, DATEFORMATEXCEL);

			$methodsList  		    = !empty($methodsList) ? json_decode(json_encode($methodsList), true) : array();
			$methodsList 		    = $models->unsetFormDataVariables($methodsList, array('canDispatchOrder'));
			$filterData['heading'] 	    = 'All Methods List(' . count($methodsList) . ')';
			$filterData['mis_report_name']  = 'methods_list_';
			$responseData['tableHead'] 	    = !empty($methodsList) ? array_keys(end($methodsList)) : array();
			$responseData['tableBody'] 	    = !empty($methodsList) ? $methodsList : array();

			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect()->back()->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 *
	 * Description : Download test standard excel
	 * Date        : 13-07-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateTestStandardDocuments(Request $request)
	{

		global $models;

		$product_category_id = !empty($request->product_category_id) ? $request->product_category_id : '';

		if (!empty($request->generate_test_standard_documents) && $request->generate_test_standard_documents == 'Excel') {

			$testStandardObj = DB::table('test_standard')
				->join('users', 'test_standard.created_by', '=', 'users.id')
				->join('product_categories', 'product_categories.p_category_id', '=', 'test_standard.product_category_id')
				->select('test_standard.test_std_code as test_standard_code', 'test_standard.test_std_name as test_standard_name', 'test_standard.test_std_desc as test_standard_desc', 'product_categories.p_category_name as parent_product_category', 'users.name as createdBy', 'test_standard.created_at as created_on', 'test_standard.updated_at as updated_on');

			if (!empty($product_category_id)) {
				$testStandardObj->where('test_standard.product_category_id', '=', $product_category_id);
			}
			$testStdList = $testStandardObj->get();

			//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($testStdList);

			$testStdList  			= !empty($testStdList) ? json_decode(json_encode($testStdList), true) : array();
			$testStdList 			= $models->unsetFormDataVariables($testStdList, array('canDispatchOrder'));
			$filterData['heading'] 		= 'All Test Stanadards List(' . count($testStdList) . ')';
			$filterData['mis_report_name'] 	= 'test_standards_list_';
			$responseData['tableHead'] 	= !empty($testStdList) ? array_keys(end($testStdList)) : array();
			$responseData['tableBody'] 	= !empty($testStdList) ? $testStdList : array();

			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect()->back()->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 * Description : Download parameterCategories excel
	 * Date        : 13-07-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateTestParameterCategoriesDocuments(Request $request)
	{

		global $models;

		$parent_id = !empty($request->parent_id) ? $request->parent_id : '';

		if (!empty($request->generate_parameter_categories_documents) && $request->generate_parameter_categories_documents == 'Excel') {

			$dataObj = DB::table('test_parameter_categories')
				->leftjoin('test_parameter_categories as category', 'test_parameter_categories.parent_id', '=', 'category.test_para_cat_id')
				->join('product_categories', 'product_categories.p_category_id', '=', 'test_parameter_categories.product_category_id')
				->join('users', 'test_parameter_categories.created_by', '=', 'users.id')
				->select('test_parameter_categories.test_para_cat_code as parameter_category_code', 'test_parameter_categories.test_para_cat_name as parameter_category_name', 'test_parameter_categories.test_para_cat_print_desc as parameter_category_description', 'category.test_para_cat_name as parent_category', 'product_categories.p_category_name as product_section', 'users.name as createdBy', 'test_parameter_categories.created_at', 'test_parameter_categories.updated_at');

			if (!empty($parent_id)) {
				$dataObj->where('test_parameter_categories.parent_id', '=', $parent_id)->orwhere('test_parameter_categories.test_para_cat_id', '=', $parent_id);
			}
			$dataList =	$dataObj->get();

			//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($dataList);

			$dataList  				= !empty($dataList) ? json_decode(json_encode($dataList), true) : array();
			$dataList 			    	= $models->unsetFormDataVariables($dataList, array('canDispatchOrder'));
			$filterData['heading'] 		= 'All Test Stanadards List(' . count($dataList) . ')';
			$filterData['mis_report_name'] 	= 'test_standards_list_';
			$responseData['tableHead'] 		= !empty($dataList) ? array_keys(end($dataList)) : array();
			$responseData['tableBody'] 		= !empty($dataList) ? $dataList : array();

			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect()->back()->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 *
	 * Description : Download test product categories excel
	 * Date        : 13-07-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateTestProductCategoriesDocuments(Request $request)
	{

		global $models;

		$parent_id = !empty($request->search_category_id) ? $request->search_category_id : '';

		if (!empty($request->generate_product_categories_documents) && $request->generate_product_categories_documents == 'Excel') {

			$productsObj = DB::table('product_categories')
				->leftjoin('product_categories as category', 'product_categories.parent_id', 'category.p_category_id')
				->join('users', 'product_categories.created_by', '=', 'users.id')
				->select('product_categories.p_category_code as test_product_category_code', 'product_categories.p_category_name as test_product_category_name', 'category.p_category_name as test_product_parent_category', 'users.name as createdBy', 'product_categories.created_at as created_on', 'product_categories.updated_at as updated_on');

			if (!empty($parent_id) && is_numeric($parent_id)) {
				$productsObj->where('product_categories.parent_id', '=', $parent_id);
			}
			$productCatList = $productsObj->orderBy('product_categories.p_category_id', 'ASC')->get();

			//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($productCatList);

			$productCatList  			= !empty($productCatList) ? json_decode(json_encode($productCatList), true) : array();
			$productCatList 			= $models->unsetFormDataVariables($productCatList, array('canDispatchOrder'));
			$filterData['heading'] 		= 'All Product Categories  List(' . count($productCatList) . ')';
			$filterData['mis_report_name'] 	= 'product_categories_list_';
			$responseData['tableHead'] 		= !empty($productCatList) ? array_keys(end($productCatList)) : array();
			$responseData['tableBody'] 		= !empty($productCatList) ? $productCatList : array();

			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect()->back()->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/**
	 * generate Job Order PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateTestProductsDocuments(Request $request)
	{

		global $models;

		$p_category_id = !empty($request->product_category_id) ? $request->product_category_id : '';

		if (!empty($request->generate_products_documents) && $request->generate_products_documents == 'Excel') {
			$pro = DB::table('product_master')
				->join('product_categories', 'product_categories.p_category_id', 'product_master.p_category_id')
				->join('product_categories as category', 'product_categories.parent_id', 'category.p_category_id')
				->join('users', 'product_master.created_by', '=', 'users.id')
				->select('product_master.product_code', 'product_master.product_name', 'product_master.product_description', 'product_categories.p_category_name', 'category.p_category_name as product_category', 'users.name as createdBy', 'product_master.created_at as created_on', 'product_master.updated_at as updated_on');

			if (!empty($p_category_id)) {
				$pro = $pro->where('product_master.p_category_id', '=', $p_category_id)->Where('product_categories.p_category_id', '=', $p_category_id);
			}

			$productsList = $pro->orderBy('product_master.product_id')->get();

			//Changing Date Time Format
			$models->formatTimeStampFromArrayExcel($productsList);

			$productsList  				= !empty($productsList) ? json_decode(json_encode($productsList), true) : array();
			$productsList 			    	= $models->unsetFormDataVariables($productsList, array('canDispatchOrder'));
			$filterData['heading'] 			= 'All Test Product  List(' . count($productsList) . ')';
			$filterData['mis_report_name'] 		= 'test_products_list_';
			$responseData['tableHead'] 		= !empty($productsList) ? array_keys(end($productsList)) : array();
			$responseData['tableBody'] 		= !empty($productsList) ? $productsList : array();

			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect()->back()->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/*******************************************************************
	 * generate generate Invoice Reports Pdf
	 * Created By : Praveen Singh
	 * Created On : 21-July-2018
	 *******************************************************************/
	public function generateInvoiceRelatedReportPdf(Request $request)
	{

		global $order, $models, $invoice, $mail, $numbersToWord;
		if ($request->isMethod('post') && !empty($request->order_id) && !empty($request->generate_invoice_related_reports_pdf)) {
			return $models->downloadPDF($request->all(), $contentType = 'invoiceWithReports');
		} else {
			return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 * Description : Download departments excel
	 * Date        : 29-01-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateDepartmentMasterDocuments(Request $request)
	{

		global $models;

		$responseData = $filterData = array();

		$filterData = $request->all();

		$departments = DB::table('departments')
			->join('department_type', 'departments.department_type', '=', 'department_type.department_type_id')
			->join('company_master', 'departments.company_id', '=', 'company_master.company_id')
			->join('users', 'departments.created_by', '=', 'users.id')
			->select('departments.department_code', 'departments.department_name', 'department_type.department_type_name as department_type', 'users.name as createdBy', 'departments.created_at', 'departments.updated_at')
			->orderBy('departments.department_id', 'ASC')
			->get();

		$models->formatTimeStampFromArrayExcel($departments, DATETIMEFORMAT);

		$departmentsList          	= !empty($departments) ? json_decode(json_encode($departments), true) : array();
		$filterData['heading'] 		= 'All Departments List :' . '(' . count($departments) . ')';
		$filterData['mis_report_name'] 	= 'Departments List';
		$responseData['tableHead'] 	= !empty($departmentsList) ? array_keys(end($departmentsList)) : array();
		$responseData['tableBody'] 	= !empty($departmentsList) ? $departmentsList : array();

		if (!empty($request->generate_department_documents) && strtolower($request->generate_department_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 * Description : Download states excel
	 * Date        : 29-01-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateStateMasterDocuments(Request $request)
	{

		global $models;

		$responseData = $filterData = array();

		$filterData = $request->all();

		$countryId = !empty($filterData['country_id']) && is_numeric($filterData['country_id']) ? $filterData['country_id'] : '101';
		$stateObj = DB::table('state_db')
			->join('countries_db', 'countries_db.country_id', '=', 'state_db.country_id')
			->join('users', 'state_db.created_by', '=', 'users.id')
			->select('state_db.state_code', 'state_db.state_name', 'countries_db.country_name', 'users.name as createdBy', 'state_db.created_at', 'state_db.updated_at')
			->where('countries_db.country_status', '=', '1');

		//Filtering records according to country
		if (!empty($countryId)) {
			$stateObj->where('state_db.country_id', $countryId);
		}

		$states = $stateObj->orderBy('countries_db.country_name', 'ASC')->get();
		$models->formatTimeStampFromArrayExcel($states, DATETIMEFORMAT);

		$statesList          		= !empty($states) ? json_decode(json_encode($states), true) : array();
		$filterData['heading'] 		= 'All States List :' . '(' . count($states) . ')';
		$filterData['mis_report_name'] 	= 'States List';
		$responseData['tableHead'] 	= !empty($statesList) ? array_keys(end($statesList)) : array();
		$responseData['tableBody'] 	= !empty($statesList) ? $statesList : array();

		//echo '<pre>';print_r($responseData);die;	
		if (!empty($request->generate_state_documents) && strtolower($request->generate_state_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 * Description : Download states excel
	 * Date        : 29-01-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateCityMasterDocuments(Request $request)
	{

		global $models;

		$responseData = $filterData = array();

		$filterData = $request->all();

		$countryId = !empty($filterData['country_id']) && is_numeric($filterData['country_id']) ? $filterData['country_id'] : '101';

		$citieObj = DB::table('city_db')
			->join('users', 'city_db.created_by', '=', 'users.id')
			->join('state_db', 'state_db.state_id', '=', 'city_db.state_id')
			->select('city_db.city_code', 'city_db.city_name', 'state_db.state_name', 'users.name as createdBy', 'city_db.created_at', 'city_db.updated_at')
			->where('state_db.country_id', '=', '101');

		//Filtering records according to country
		if (!empty($filterData['state_id']) && is_numeric($filterData['state_id'])) {
			$citieObj->where('city_db.state_id', $filterData['state_id']);
		}

		$cities = $citieObj->orderBy('city_db.city_id', 'desc')->get();
		$models->formatTimeStampFromArrayExcel($cities, DATETIMEFORMAT);

		$citiesList          		= !empty($cities) ? json_decode(json_encode($cities), true) : array();
		$filterData['heading'] 		= 'All Cities List :' . '(' . count($cities) . ')';
		$filterData['mis_report_name'] 	= 'Cities List';
		$responseData['tableHead'] 	= !empty($citiesList) ? array_keys(end($citiesList)) : array();
		$responseData['tableBody'] 	= !empty($citiesList) ? $citiesList : array();

		if (!empty($request->generate_city_documents) && strtolower($request->generate_city_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 * Description : Download Employees excel
	 * Date        : 29-01-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateEmployeesMasterDocuments(Request $request)
	{

		global $models;

		$responseData = $filterData = array();
		$error   = '1';
		$message = '';

		$filterData = $request->all();

		$getBranchWiseEmployeeObj = DB::table('users')
			->join('divisions', 'users.division_id', '=', 'divisions.division_id')
			->leftJoin('users_department_detail', 'users_department_detail.user_id', '=', 'users.id')
			->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
			->leftJoin('users_equipment_detail', 'users_equipment_detail.user_id', 'users.id')
			->join('users as u', 'users.created_by', '=', 'u.id')
			->select('divisions.division_name', 'users.id', 'users.user_code as employee_code', 'users.name', 'users.email', 'users.status as departments', 'users.status as roles', 'users.status as equipments', 'users.is_sales_person as is_sales_person', 'users.status as user_status', 'u.name as created_by', 'users.created_at as created_at', 'users.updated_at as updated_at');

		//Filter records according to search condition
		$this->getEmployeeSearchDataExcel($getBranchWiseEmployeeObj, $filterData);

		//Filtering Data based on Search Condition
		$employeeBranchWiseList = $getBranchWiseEmployeeObj->groupBy('users.id')->orderBy('users.id', 'DESC')->get()->toArray();

		//Formating the Array Data
		$models->formatTimeStampFromArrayExcel($employeeBranchWiseList, DATETIMEFORMAT);

		$employeeRelatedDataList 	= !empty($employeeBranchWiseList) ? $this->employeeRelatedAllDataList($employeeBranchWiseList) : array();
		$employees          		= !empty($employeeRelatedDataList) ? json_decode(json_encode($employeeRelatedDataList), true) : array();
		$employees 			= $models->unsetFormDataVariablesArray($employees, array('id'));
		$filterData['heading'] 		= 'All Employees List :' . '(' . count($employeeRelatedDataList) . ')';
		$filterData['mis_report_name'] 	= 'Employees List';
		$responseData['tableHead'] 	= !empty($employees) ? array_keys(end($employees)) : array();
		$responseData['tableBody'] 	= !empty($employees) ? $employees : array();

		if (!empty($request->generate_employee_documents) && strtolower($request->generate_employee_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/****
	 *
	 *employees related data in comma separeted format.
	 *
	 ****/
	public function employeeRelatedAllDataList($employeeBranchWiseList)
	{

		$detailedArr = array();

		if (!empty($employeeBranchWiseList)) {
			foreach ($employeeBranchWiseList as $key => $user) {

				$employeeList[$key]['userData'] = $user; 							//**** user details

				$employeeList[$key]['departments'] = DB::table('users_department_detail')    			//**** user departments details
					->join('departments', 'users_department_detail.department_id', '=', 'departments.department_id')
					->where('users_department_detail.user_id', '=', $user->id)
					->pluck('departments.department_name')
					->all();
				$employeeList[$key]['departments'] =  implode(',', $employeeList[$key]['departments']); 		//**** user departments details in comma format

				$employeeList[$key]['roles'] = DB::table('role_user') 						//**** user roles details
					->join('roles', 'roles.id', '=', 'role_user.role_id')
					->where('role_user.user_id', '=', $user->id)
					->pluck('roles.name')
					->all();
				$employeeList[$key]['roles'] = implode(',', $employeeList[$key]['roles']);			//**** user roles details in comma format

				$employeeList[$key]['equipmentType'] = DB::table('users_equipment_detail') 			//**** user equipments details
					->join('equipment_type', 'users_equipment_detail.equipment_type_id', '=', 'equipment_type.equipment_id')
					->where('users_equipment_detail.user_id', '=', $user->id)
					->pluck('equipment_type.equipment_name')
					->all();
				$employeeList[$key]['equipmentType'] = implode(',', $employeeList[$key]['equipmentType']);  	//**** user equipmentType details in comma format
			}
			if (!empty($employeeList)) {
				foreach ($employeeList as $employeeData) {
					$completeDetail 			= $employeeData['userData'];
					$completeDetail->departments 	= $employeeData['departments'];
					$completeDetail->roles 		= $employeeData['roles'];
					$completeDetail->equipments 	= $employeeData['equipmentType'];
					$detailedArr[] 			= $completeDetail;
				}
			}
		}
		return !empty($detailedArr) ? $detailedArr : false;
	}
	/****
	 *
	 *search data for employees excel list
	 *
	 ****/
	public function getEmployeeSearchDataExcel($getBranchWiseEmployeeObj, $formData)
	{

		global $models, $user;

		$user_id            	= defined('USERID') ? USERID : '0';
		$department_ids     	= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_id           	= defined('ROLEID') ? ROLEID : '0';
		$role_ids           	= defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids 	= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		//Filtering records according to keyword assigned
		if (!empty($formData['search_keyword'])) {
			$keyword = trim($formData['search_keyword']);
			$getBranchWiseEmployeeObj->where(function ($getBranchWiseEmployeeObj) use ($keyword) {
				$getBranchWiseEmployeeObj->where('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('users.email', 'LIKE', '%' . $keyword . '%');
			});
		}
		//Filtering records according to user_code assigned
		if (!empty($formData['search_user_code'])) {
			$getBranchWiseEmployeeObj->where('users.user_code', 'like', '%' . $formData['search_user_code'] . '%');
		}
		//Filtering records according to name assigned
		if (!empty($formData['search_name'])) {
			$getBranchWiseEmployeeObj->where('users.name', 'like', '%' . $formData['search_name'] . '%');
		}
		//Filtering records according to division assigned
		if (!empty($formData['search_division']) && is_numeric($formData['search_division'])) {
			$getBranchWiseEmployeeObj->where('users.division_id', $formData['search_division']);
		}
		//Filtering records according to department assigned
		if (!empty($formData['search_department']) && is_numeric($formData['search_department'])) {
			$getBranchWiseEmployeeObj->where('users_department_detail.department_id', $formData['search_department']);
		}
		//Filtering records according to roles assigned
		if (!empty($formData['search_role']) && is_numeric($formData['search_role'])) {
			$getBranchWiseEmployeeObj->where('role_user.role_id', $formData['search_role']);
		}
		if (!empty($formData['search_equipment'])) {
			$getBranchWiseEmployeeObj->where('users_equipment_detail.equipment_type_id', '=', $formData['search_equipment']);
		}
		if (!empty($formData['search_email'])) {
			$getBranchWiseEmployeeObj->where('users.email', 'like', '%' . $formData['search_email'] . '%');
		}
		if (!empty($formData['search_company_name'])) {
			$getBranchWiseEmployeeObj->where('company_master.company_name', 'like', '%' . $formData['search_company_name'] . '%');
		}
		if (!empty($formData['search_is_sales_person'])) {
			if (trim(strtolower($formData['search_is_sales_person'])) == 'yes') {
				$getBranchWiseEmployeeObj->where('users.is_sales_person', '=', 1);
			} else if (strtolower($formData['search_is_sales_person']) == 'no') {
				$getBranchWiseEmployeeObj->where('users.is_sales_person', '=', 0);
			}
		}
		if (!empty($formData['search_status'])) {
			$getBranchWiseEmployeeObj->where('users.status', $formData['search_status']);
		}
		if (!empty($formData['search_is_assign_job'])) {
			if (trim(strtolower($formData['search_is_assign_job'])) == 'yes') {
				$getBranchWiseEmployeeObj->where('users.is_assign_job', '=', 1);
			} else if (strtolower($formData['search_is_assign_job']) == 'no') {
				$getBranchWiseEmployeeObj->where('users.is_assign_job', '=', Null)->orwhere('users.is_assign_job', '=', 0);
			}
		}
		if (!empty($formData['search_created_by'])) {
			$getBranchWiseEmployeeObj->where('u.name', 'like', '%' . $formData['search_created_by'] . '%');
		}
	}

	/************************************
	 * Description : Download Employees excel
	 * Date        : 29-01-18
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateProductAliasMasterDocuments(Request $request)
	{

		global $models;

		$responseData = $filterData = array();
		$error        = '1';
		$message      = '';
		$filterData = $request->all();
		$keyword    = !empty($filterData['search_keyword']) ? $filterData['search_keyword'] : '';

		$customerProductsListObj = DB::table('product_master_alias')
			->join('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->join('users as createdBy', 'createdBy.id', 'product_master_alias.created_by')
			->select('product_master_alias.c_product_name as name', 'product_master.product_name', 'createdBy.name as created_by', 'product_master.created_at', 'product_master.updated_at')
			->orderBy('product_master_alias.c_product_name', 'DESC');

		if (!empty($filterData['product_id'])) {
			$customerProductsListObj->where('product_master_alias.product_id', '=', $filterData['product_id']);
		}
		if (!empty($keyword)) {
			$customerProductsListObj->where('product_master.product_name', 'like', '%' . $keyword . '%')->orwhere('product_master_alias.c_product_name', 'like', '%' . $keyword . '%');
		}

		$customerProductsList = $customerProductsListObj->get();
		$models->formatTimeStampFromArrayExcel($customerProductsList, DATETIMEFORMAT);

		$productAliaList		= !empty($customerProductsList) ? json_decode(json_encode($customerProductsList), true) : array();
		$employees 			= $models->unsetFormDataVariablesArray($productAliaList, array('id'));
		$filterData['heading'] 		= 'All Product Alias List :' . '(' . count($customerProductsList) . ')';
		$filterData['mis_report_name'] 	= 'Product Alias List';
		$responseData['tableHead'] 	= !empty($productAliaList) ? array_keys(end($productAliaList)) : array();
		$responseData['tableBody'] 	= !empty($productAliaList) ? $productAliaList : array();

		if (!empty($request->generate_product_alias_documents) && strtolower($request->generate_product_alias_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateStateWiseProductRatesDocuments(Request $request)
	{

		global $models, $invoicingTypeStateWiseProduct;

		$responseData = $filterData = array();
		$stateWiseCustomerInvoicingCount = '0';
		$filterData = $request->all();

		$cir_product_cat_id	= !empty($filterData['cir_product_category_id']) ? $filterData['cir_product_category_id'] : '0';
		$cir_division_id 	= !empty($filterData['cir_division_id']) ? $filterData['cir_division_id'] : '0';
		$cir_state_id 		= !empty($filterData['cirStateID']) ? $filterData['cirStateID'] : '0';
		$keyword 		= !empty($filterData['search_keyword']) ? $filterData['search_keyword'] : '';


		$stateWiseProductRateObj = DB::table('customer_invoicing_rates')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'customer_invoicing_rates.cir_product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('product_master_alias', 'product_master_alias.c_product_id', 'customer_invoicing_rates.cir_c_product_id')
			->join('state_db', 'state_db.state_id', '=', 'customer_invoicing_rates.cir_state_id')
			->leftJoin('product_master', 'product_master.product_id', 'product_master_alias.product_id')
			->join('users as createdBy', 'createdBy.id', 'customer_invoicing_rates.created_by')
			->select('departments.department_name as department', 'state_db.state_name', 'product_master.product_name', 'product_master_alias.c_product_name', 'createdBy.name as createdByName', 'customer_invoicing_rates.invoicing_rate', 'customer_invoicing_rates.cir_product_category_id', 'customer_invoicing_rates.cir_division_id', 'customer_invoicing_rates.cir_state_id')
			->where('customer_invoicing_rates.invoicing_type_id', '2');

		//Filtering records according to keyword assigned
		if (!empty($keyword) && $cir_division_id && $cir_product_cat_id) {
			$stateWiseProductRateObj->where('departments.department_name', 'like', '%' . $keyword . '%')
				->orwhere('product_master.product_name', 'like', '%' . $keyword . '%')
				->orwhere('product_master_alias.c_product_name', 'like', '%' . $keyword . '%')
				->orwhere('customer_invoicing_rates.invoicing_rate', 'like', '%' . $keyword . '%');
		}
		if (!empty($cir_state_id) && is_numeric($cir_state_id)) {
			$stateWiseProductRateObj->where('customer_invoicing_rates.cir_state_id', $cir_state_id);
		}
		if ($cir_product_cat_id) {
			$stateWiseProductRateObj->where('customer_invoicing_rates.cir_product_category_id', $cir_product_cat_id);
		}
		if ($cir_division_id) {
			$stateWiseProductRateObj->where('customer_invoicing_rates.cir_division_id', $cir_division_id);
		}
		$stateWiseProductRates = $stateWiseProductRateObj->orderBy('customer_invoicing_rates.cir_id', 'ASC')->get();
		if (!empty($stateWiseProductRates)) {
			$stateWiseCustomerInvoicingCount = DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.invoicing_type_id', '2')->count();
		}

		$models->formatTimeStampFromArrayExcel($stateWiseProductRates, DATETIMEFORMAT);

		$stateWiseProductRateList	= !empty($stateWiseProductRates) ? json_decode(json_encode($stateWiseProductRates), true) : array();
		$stateWiseProductRateList 	= $models->unsetFormDataVariablesArray($stateWiseProductRateList, array('cir_product_category_id', 'cir_division_id', 'cir_state_id'));
		$filterData['heading'] 		= 'All State Wise Products List :' . '(' . count($stateWiseProductRates) . ')';
		$filterData['mis_report_name'] 	= 'State Wise Products List';
		$responseData['tableHead'] 	= !empty($stateWiseProductRateList) ? array_keys(end($stateWiseProductRateList)) : array();
		$responseData['tableBody'] 	= !empty($stateWiseProductRateList) ? $stateWiseProductRateList : array();

		if (!empty($request->generate_state_wise_product_documents) && strtolower($request->generate_state_wise_product_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}
	/**
	 * Display the specified resource.
	 * download  customer wise product excel
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateCustomerWiseProductRatesDocuments(Request $request)
	{

		global $models, $invoicingTypeStateWiseProduct;

		$invocingTypeId  = '3';
		$cir_customer_id = '0';
		$customerWiseProductsObj = array();
		$filterData = $request->all();

		if (!empty($filterData)) {

			$cir_customer_id   		= !empty($filterData['cir_customer_id']) ? $filterData['cir_customer_id'] : '0';
			$cirProductCategoryId   	= !empty($filterData['cir_product_category_id']) ? $filterData['cir_product_category_id'] : '1';
			$cirDivisionId		= !empty($filterData['cir_division_id']) ? $filterData['cir_division_id'] : '1';
			$keyword 			= !empty($filterData['search_keyword']) ? trim($filterData['search_keyword']) : '';

			$customerWiseProductsObj = DB::table('customer_invoicing_rates')
				->join('customer_master', 'customer_master.customer_id', 'customer_invoicing_rates.cir_customer_id')
				->leftjoin('city_db', 'city_db.city_id', 'customer_invoicing_rates.cir_city_id')
				->leftjoin('state_db', 'state_db.state_id', 'city_db.state_id')
				->leftjoin('product_master_alias', 'product_master_alias.c_product_id', 'customer_invoicing_rates.cir_c_product_id')
				->leftjoin('product_master', 'product_master.product_id', 'product_master_alias.product_id')
				->join('product_categories', 'product_categories.p_category_id', '=', 'customer_invoicing_rates.cir_product_category_id')
				->join('users as createdBy', 'createdBy.id', 'customer_invoicing_rates.created_by')
				->whereNull('cir_parameter_id')
				->where('customer_invoicing_rates.invoicing_type_id', $invocingTypeId)
				->select(
					'product_categories.p_category_name as department',
					'product_master.product_name',
					'product_master_alias.c_product_name as product_alias_name',
					'customer_master.customer_name',
					'city_db.city_name',
					'createdBy.name as created_by',
					'customer_invoicing_rates.invoicing_rate',
					'customer_invoicing_rates.created_at',
					'customer_invoicing_rates.updated_at'
				);
			//Filtering records according to keyword assigned
			if (!empty($keyword) && $cirProductCategoryId && $cirDivisionId) {
				$customerWiseProductsObj->where('product_categories.p_category_name', 'like', '%' . $keyword . '%')
					->orwhere('product_master.product_name', 'like', '%' . $keyword . '%')
					->orwhere('product_master_alias.c_product_name', 'like', '%' . $keyword . '%')
					->orwhere('customer_invoicing_rates.invoicing_rate', 'like', '%' . $keyword . '%');
			}
			if (!empty($cirDivisionId)) {
				$customerWiseProductsObj->where('customer_invoicing_rates.cir_division_id', $cirDivisionId);
			}
			if (!empty($cirProductCategoryId)) {
				$customerWiseProductsObj->where('customer_invoicing_rates.cir_product_category_id', $cirProductCategoryId);
			}
			if (!empty($cir_customer_id) && is_numeric($cir_customer_id)) {
				$customerWiseProductsObj->where('customer_invoicing_rates.cir_customer_id', $cir_customer_id);
			}

			$customerWiseProducts = $customerWiseProductsObj->orderBy('customer_invoicing_rates.cir_id', 'ASC')->get();
			$models->formatTimeStampFromArrayExcel($customerWiseProducts, DATETIMEFORMAT);

			$customerWiseProductsList		= !empty($customerWiseProducts) ? json_decode(json_encode($customerWiseProducts), true) : array();
			$filterData['heading'] 		= 'All Customer Wise Products List :' . '(' . count($customerWiseProducts) . ')';
			$filterData['mis_report_name'] 	= 'Customer Wise Products List';
			$responseData['tableHead'] 		= !empty($customerWiseProductsList) ? array_keys(end($customerWiseProductsList)) : array();
			$responseData['tableBody'] 		= !empty($customerWiseProductsList) ? $customerWiseProductsList : array();

			if (!empty($request->generate_customer_wise_product_documents) && strtolower($request->generate_customer_wise_product_documents) == 'excel') {
				return $models->downloadExcel($responseData, $filterData);
			} else {
				return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
			}
		}
	}
	/**
	 * Display the specified resource.
	 * download  customer wise parameters excel
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateCustomerWiseParametersDocument(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseParameter;

		$invocingTypeId  = '4';
		$cir_customer_id = '0';
		$filterData 	 = array();
		$filterData      = $request->all();

		//Saving record in table
		if (!empty($filterData)) {

			$cir_customer_id   		= !empty($filterData['cir_customer_id']) ? $filterData['cir_customer_id'] : '0';
			$cirProductCategoryId 	= !empty($filterData['cir_product_category_id']) ? $filterData['cir_product_category_id'] : '1';
			$cirDivisionId   		= !empty($filterData['cir_division_id']) ? $filterData['cir_division_id'] : '1';
			$keyword 			= !empty($filterData['search_keyword']) ? $filterData['search_keyword'] : '';

			$customerParametersListObj  = DB::table('customer_invoicing_rates')
				->join('customer_master', 'customer_master.customer_id', 'customer_invoicing_rates.cir_customer_id')
				->join('equipment_type', 'equipment_type.equipment_id', 'customer_invoicing_rates.cir_equipment_type_id')
				->join('test_parameter', 'customer_invoicing_rates.cir_parameter_id', '=', 'test_parameter.test_parameter_id')
				->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', '=', 'test_parameter_categories.test_para_cat_id')
				->join('product_categories', 'product_categories.p_category_id', '=', 'test_parameter_categories.product_category_id')
				->join('users as createdBy', 'createdBy.id', 'customer_invoicing_rates.created_by')
				->leftJoin('test_standard', 'test_standard.test_std_id', 'customer_invoicing_rates.cir_test_standard_id')
				->select('product_categories.p_category_name as product_category', 'customer_master.customer_name', 'test_parameter_categories.test_para_cat_name as parameter_category', 'test_parameter.test_parameter_name as parameter_name', 'equipment_type.equipment_name', 'test_standard.test_std_name as test_standard', 'customer_invoicing_rates.invoicing_rate', 'createdBy.name as created_by', 'customer_invoicing_rates.created_at', 'customer_invoicing_rates.updated_at')
				->where('customer_invoicing_rates.invoicing_type_id', $invocingTypeId);

			if (!empty($cirProductCategoryId)) {
				$customerParametersListObj->where('customer_invoicing_rates.cir_product_category_id', $cirProductCategoryId);
			}
			if (!empty($cir_customer_id) && is_numeric($cir_customer_id)) {
				$customerParametersListObj->where('customer_invoicing_rates.cir_customer_id', $filterData['cir_customer_id']);
			}
			if (!empty($cirDivisionId)) {
				$customerParametersListObj->where('customer_invoicing_rates.cir_division_id', $cirDivisionId);
			}
			//Filtering records according to keyword assigned
			if (!empty($keyword)) {
				$customerParametersListObj->where('product_categories.p_category_name', 'like', '%' . $keyword . '%')
					->orwhere('customer_master.customer_name', 'like', '%' . $keyword . '%')
					->orwhere('test_parameter_categories.test_para_cat_name', 'like', '%' . $keyword . '%')
					->orwhere('test_parameter.test_parameter_name', 'like', '%' . $keyword . '%')
					->orwhere('equipment_type.equipment_name', 'like', '%' . $keyword . '%')
					->orwhere('createdBy.name', 'like', '%' . $keyword . '%')
					->orwhere('test_standard.test_std_name', 'like', '%' . $keyword . '%')
					->orwhere('customer_invoicing_rates.invoicing_rate', 'like', '%' . $keyword . '%');
			}
			$customerParameters = $customerParametersListObj->orderBy('customer_invoicing_rates.cir_id', 'ASC')->get();
			$models->formatTimeStampFromArrayExcel($customerParameters, DATETIMEFORMAT);

			$customerParametersList		= !empty($customerParameters) ? json_decode(json_encode($customerParameters), true) : array();
			$filterData['heading'] 		= 'All Customer Wise Parameters List :' . '(' . count($customerParameters) . ')';
			$filterData['mis_report_name'] 	= 'Customer Wise Parameters List';
			$responseData['tableHead'] 		= !empty($customerParametersList) ? array_keys(end($customerParametersList)) : array();
			$responseData['tableBody'] 		= !empty($customerParametersList) ? $customerParametersList : array();

			if (!empty($request->generate_customer_parameter_documents) && strtolower($request->generate_customer_parameter_documents) == 'excel') {
				return $models->downloadExcel($responseData, $filterData);
			} else {
				return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
			}
		}
	}

	/**
	 * Display the specified resource.
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateCustomerWiseAssayParametersDocument(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseAssayParameter;

		$invocingTypeId  = '4';
		$cir_customer_id = '0';
		$filterData 	 = array();
		$filterData 	 = $request->all();

		if (!empty($filterData)) {

			$cir_customer_id       = !empty($filterData['cir_customer_id']) ? $filterData['cir_customer_id'] : '0';
			$cirProductCategoryId  = !empty($filterData['cir_product_category_id']) ? $filterData['cir_product_category_id'] : '1';
			$keyword 		   = !empty($filterData['search_keyword']) ? $filterData['search_keyword'] : '';

			$customerWiseAssayParametersObj  = DB::table('customer_invoicing_rates')
				->join('customer_master', 'customer_master.customer_id', 'customer_invoicing_rates.cir_customer_id')
				->join('product_categories as department', 'department.p_category_id', '=', 'customer_invoicing_rates.cir_product_category_id')
				->join('product_categories as productCategory', 'productCategory.p_category_id', '=', 'customer_invoicing_rates.cir_p_category_id')
				->join('product_categories as subProductCategory', 'subProductCategory.p_category_id', '=', 'customer_invoicing_rates.cir_sub_p_category_id')
				->join('test_parameter_categories', 'test_parameter_categories.test_para_cat_id', '=', 'customer_invoicing_rates.cir_test_parameter_category_id')
				->leftJoin('test_parameter', 'test_parameter.test_parameter_id', '=', 'customer_invoicing_rates.cir_parameter_id')
				->join('equipment_type', 'equipment_type.equipment_id', 'customer_invoicing_rates.cir_equipment_type_id')
				->leftJoin('detector_master', 'detector_master.detector_id', 'customer_invoicing_rates.cir_detector_id')
				->leftJoin('customer_invoicing_running_time', 'customer_invoicing_running_time.invoicing_running_time_id', 'customer_invoicing_rates.cir_running_time_id')
				->join('users as createdBy', 'createdBy.id', 'customer_invoicing_rates.created_by')
				->select('department.p_category_name as department', 'customer_master.customer_name', 'productCategory.p_category_name as product_category', 'subProductCategory.p_category_name as sub_product_category', 'test_parameter_categories.test_para_cat_name as parameter_category', 'test_parameter.test_parameter_name as parameter_name', 'equipment_type.equipment_name', 'customer_invoicing_rates.cir_equipment_count as equipment_count', 'detector_master.detector_name', 'customer_invoicing_running_time.invoicing_running_time_key as running_time', 'customer_invoicing_rates.invoicing_rate', 'createdBy.name as created_by', 'customer_invoicing_rates.created_at', 'customer_invoicing_rates.updated_at')
				->whereNotNull('customer_invoicing_rates.cir_is_detector')
				->where('customer_invoicing_rates.invoicing_type_id', $invocingTypeId);

			if (!empty($cir_customer_id) && is_numeric($cir_customer_id)) {
				$customerWiseAssayParametersObj->where('customer_invoicing_rates.cir_customer_id', $cir_customer_id);
			}
			if (!empty($cirProductCategoryId)) {
				$customerWiseAssayParametersObj->where('customer_invoicing_rates.cir_product_category_id', $cirProductCategoryId);
			}
			//Filtering records according to keyword assigned
			if (!empty($keyword)) {
				$customerWiseAssayParametersObj->where('test_parameter_categories.test_para_cat_name', 'like', '%' . $keyword . '%')
					->orwhere('test_parameter.test_parameter_name', 'like', '%' . $keyword . '%')
					->orwhere('productCategory.p_category_name', 'like', '%' . $keyword . '%')
					->orwhere('subProductCategory.p_category_name', 'like', '%' . $keyword . '%')
					->orwhere('equipment_type.equipment_name', 'like', '%' . $keyword . '%')
					->orwhere('detector_master.detector_name', 'like', '%' . $keyword . '%');
			}

			$customerWiseAssayParameters = $customerWiseAssayParametersObj->orderBy('customer_invoicing_rates.cir_id', 'ASC')->get();
			$models->formatTimeStampFromArrayExcel($customerWiseAssayParameters, DATETIMEFORMAT);

			$customerWiseAssayParametersList	= !empty($customerWiseAssayParameters) ? json_decode(json_encode($customerWiseAssayParameters), true) : array();
			$filterData['heading'] 		= 'All Customer Wise Assay Parameters List :' . '(' . count($customerWiseAssayParameters) . ')';
			$filterData['mis_report_name'] 	= 'Customer Wise Assay Parameters List';
			$responseData['tableHead'] 		= !empty($customerWiseAssayParametersList) ? array_keys(end($customerWiseAssayParametersList)) : array();
			$responseData['tableBody'] 		= !empty($customerWiseAssayParametersList) ? $customerWiseAssayParametersList : array();

			if (!empty($request->generate_customer_assay_parameter_documents) && strtolower($request->generate_customer_assay_parameter_documents) == 'excel') {
				return $models->downloadExcel($responseData, $filterData);
			} else {
				return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
			}
		}
	}
	/************************************
	 * Description : Download country excel
	 * Date        : 28-01-19
	 * Parameter   : 
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateCountryMasterDocuments(Request $request)
	{

		global $models;

		$responseData = $filterData = array();

		$filterData = $request->all();

		$countryObj = DB::table('countries_db')->select('countries_db.country_code', 'countries_db.country_name', 'countries_db.country_phone_code', 'countries_db.country_level');
		$country = $countryObj->orderBy('countries_db.country_code', 'ASC')->get();
		$models->formatTimeStampFromArrayExcel($country, DATETIMEFORMAT);

		$countryList          		= !empty($country) ? json_decode(json_encode($country), true) : array();
		$filterData['heading'] 		= 'All Country List :' . '(' . count($countryList) . ')';
		$filterData['mis_report_name'] 	= 'Country List';
		$responseData['tableHead'] 	= !empty($countryList) ? array_keys(end($countryList)) : array();
		$responseData['tableBody'] 	= !empty($countryList) ? $countryList : array();

		if (!empty($request->generate_country_documents) && strtolower($request->generate_country_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}


	/***********************************************
	 * FUNCTIONS TO GENERATE BRANCH WISE STABILITY TEST FORMAT REPORT
	 * Created On : 31-01-2019
	 * Created By :Praveen Singh
	 ************************************************/
	public function downloadBWStabilityTestFormatReport(Request $request)
	{

		global $order, $models, $stbOrderPrototype;

		$error	    = '0';
		$message    = '';
		$data	    = '';
		$returnData = $testParameterDetail = $summaryDetail = $summaryDetailColoum = $bookedOrderDetailInfo = $testParameterColumnCount = $testParameterColumnNames = $finalTestParameterDetail = array();

		if (!$request->isMethod('post') || empty($request->stb_order_hdr_id) || empty($request->stb_stability_type_id) || empty($request->generate_stability_test_format_documents) || $request->generate_stability_test_format_documents != 'Generate STF Report') return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));

		//Header Footer Content********************************************************************************
		$pdfHeaderContent 	      = $models->getHeaderFooterTemplate();
		$returnData['header_content'] = $pdfHeaderContent->header_content;
		$returnData['footer_content'] = $pdfHeaderContent->footer_content;
		//Header Footer Content********************************************************************************

		//Getting Part A Detail************************************************************
		$stbOrderHdrData         = $stbOrderPrototype->getStabilityOrder($request->stb_order_hdr_id);
		$stbOrderHdrDtlData   	 = DB::table('stb_order_hdr_dtl')->join('product_master', 'product_master.product_id', 'stb_order_hdr_dtl.stb_product_id')->join('test_standard', 'test_standard.test_std_id', 'stb_order_hdr_dtl.stb_test_standard_id')->select('stb_order_hdr_dtl.*', 'product_master.product_name', 'test_standard.test_std_name')->where('stb_order_hdr_dtl.stb_order_hdr_id', $request->stb_order_hdr_id)->first();
		$stbOrderHdrDtlLabelData = DB::table('stb_order_hdr_dtl')->join('stb_order_hdr_dtl_detail', 'stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id', 'stb_order_hdr_dtl.stb_order_hdr_dtl_id')->where('stb_order_hdr_dtl.stb_order_hdr_id', $request->stb_order_hdr_id)->where('stb_order_hdr_dtl_detail.stb_stability_type_id', $request->stb_stability_type_id)->orderBy('stb_order_hdr_dtl.stb_label_name', 'ASC')->pluck('stb_order_hdr_dtl.stb_label_name', 'stb_order_hdr_dtl.stb_label_name')->all();
		$stabilityTypeData	 = DB::table('stb_order_stability_types')->where('stb_order_stability_types.stb_stability_type_id', $request->stb_stability_type_id)->first();
		$stbOrderHdrDtlDetail    = DB::table('stb_order_hdr_dtl_detail')->where('stb_order_hdr_dtl_detail.stb_order_hdr_id', $request->stb_order_hdr_id)->where('stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id', $stbOrderHdrDtlData->stb_order_hdr_dtl_id)->where('stb_order_hdr_dtl_detail.stb_stability_type_id', $request->stb_stability_type_id)->first();

		$sampleInformationDetail = array(
			'prototype_no' 		   => !empty($stbOrderHdrData->stb_prototype_no) ? $stbOrderHdrData->stb_prototype_no : '',
			'sample_description_name'  	   => !empty($stbOrderHdrData->stb_sample_description_name) ? $stbOrderHdrData->stb_sample_description_name : '',
			'product_description'	   => !empty($stbOrderHdrData->stb_product_description) ? $stbOrderHdrData->stb_product_description : '',
			'stability_type_name'          => !empty($stabilityTypeData->stb_stability_type_name) ? strtoupper($stabilityTypeData->stb_stability_type_name . ' Storage Condition') : '',
			'product_name' 		   => !empty($stbOrderHdrDtlData->product_name) ? $stbOrderHdrDtlData->product_name : '',
			'protocol' 	   		   => !empty($stbOrderHdrDtlData->test_std_name) ? $stbOrderHdrDtlData->test_std_name : '',
			'batch_no'     		   => !empty($stbOrderHdrData->stb_batch_no) ? $stbOrderHdrData->stb_batch_no : '',
			'storage_condition'		   => !empty($stbOrderHdrDtlDetail->stb_condition_temperature) ? $stbOrderHdrDtlDetail->stb_condition_temperature : '',
			'date_of_manufacturing'	   => !empty($stbOrderHdrData->stb_mfg_date) ? $stbOrderHdrData->stb_mfg_date : '',
			'date_of_expiry'		   => !empty($stbOrderHdrData->stb_expiry_date) ? $stbOrderHdrData->stb_expiry_date : '',
			'date_of_incubation'	   => !empty($stbOrderHdrData->stb_prototype_date) ? date(DATEFORMATEXCEL, strtotime($stbOrderHdrData->stb_prototype_date)) : '',
			'frequency_of_testing'	   => !empty($stbOrderHdrDtlLabelData) ? implode(', ', $stbOrderHdrDtlLabelData) : '',
			'sample_qty'		   => !empty($stbOrderHdrData->stb_sample_qty) ? $stbOrderHdrData->stb_sample_qty : '',
			'sample_qty_unit'		   => !empty($stbOrderHdrData->stb_sample_qty_unit) ? $stbOrderHdrData->stb_sample_qty_unit : '',
			'packing_mode'		   => !empty($stbOrderHdrData->stb_packing_mode) ? $stbOrderHdrData->stb_packing_mode : '',
			'sample_pack_code'	   	   => !empty($stbOrderHdrData->stb_sample_pack_code) ? $stbOrderHdrData->stb_sample_pack_code : '',
			'sample_pack'		   => !empty($stbOrderHdrData->stb_sample_pack) ? $stbOrderHdrData->stb_sample_pack : '',
			'storage_cond_sample_pack' 	   => !empty($stbOrderHdrData->stb_storage_cond_sample_pack) ? $stbOrderHdrData->stb_storage_cond_sample_pack : '',
			'orientation'		   => !empty($stbOrderHdrData->stb_orientation) ? $stbOrderHdrData->stb_orientation : '',
		);
		$returnData['part_a']	= !empty($sampleInformationDetail) ? $sampleInformationDetail : array();
		//Getting Part A Detail************************************************************

		//Getting Part B Detail*********************************************************************
		$bookedOrderDetail = DB::table('stb_order_hdr_dtl_detail')
			->join('order_master', 'order_master.stb_order_hdr_detail_id', 'stb_order_hdr_dtl_detail.stb_order_hdr_detail_id')
			->join('stb_order_hdr_dtl', 'stb_order_hdr_dtl.stb_order_hdr_dtl_id', 'stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id')
			->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
			->whereNotNull('order_report_details.approving_date')
			->whereNotIn('order_master.status', array('10', '12'))
			->whereIn('stb_order_hdr_dtl_detail.stb_product_test_stf_id', ['0', '1'])
			->where('stb_order_hdr_dtl_detail.stb_order_hdr_detail_status', '1')
			->where('stb_order_hdr_dtl_detail.stb_order_hdr_id', '=', $request->stb_order_hdr_id)
			->where('stb_order_hdr_dtl_detail.stb_stability_type_id', '=', $request->stb_stability_type_id)
			->select('order_master.order_id', 'order_master.division_id', 'order_master.product_category_id', 'order_master.order_no', 'order_master.order_date', 'stb_order_hdr_dtl.stb_label_name', 'stb_order_hdr_dtl.stb_start_date', 'stb_order_hdr_dtl.stb_end_date', 'stb_order_hdr_dtl_detail.stb_order_hdr_id', 'stb_order_hdr_dtl_detail.stb_order_hdr_dtl_id', 'stb_order_hdr_dtl_detail.stb_stability_type_id')
			->orderBy('order_master.order_no', 'ASC')
			->get()
			->toArray();

		if (!empty($bookedOrderDetail)) {
			foreach ($bookedOrderDetail as $key => $values) {
				$productTestDtlIds = array();
				$productTestDtlIds = DB::table('stb_order_hdr_dtl_detail')
					->where('stb_order_hdr_dtl_detail.stb_order_hdr_id', $values->stb_order_hdr_id)
					->where('stb_order_hdr_dtl_detail.stb_stability_type_id', $values->stb_stability_type_id)
					->where('stb_order_hdr_dtl_detail.stb_product_test_stf_id', '=', '1')
					->pluck('stb_order_hdr_dtl_detail.stb_product_test_dtl_id', 'stb_order_hdr_dtl_detail.stb_product_test_dtl_id')
					->all();
				$values->productTestDtlIds = DB::table('order_parameters_detail')
					->join('test_parameter', 'test_parameter.test_parameter_id', 'order_parameters_detail.test_parameter_id')
					->leftJoin('method_master', 'method_master.method_id', 'order_parameters_detail.method_id')
					->where('order_parameters_detail.order_id', $values->order_id)
					->whereIn('order_parameters_detail.product_test_parameter', array_values($productTestDtlIds))
					->select('order_parameters_detail.order_id', 'order_parameters_detail.product_test_parameter', 'order_parameters_detail.test_parameter_id', 'test_parameter.test_parameter_name', 'order_parameters_detail.display_decimal_place', 'order_parameters_detail.claim_value', 'order_parameters_detail.claim_value_unit', 'order_parameters_detail.standard_value_from', 'order_parameters_detail.standard_value_to', 'order_parameters_detail.test_result', 'method_master.method_name')
					->get()
					->toArray();
			}
			$counter = 0;
			foreach ($bookedOrderDetail as $key => $values) {
				foreach ($values->productTestDtlIds as $keyOne => $valueOne) {
					$models->getRequirementSTDFromTo($valueOne, $valueOne->standard_value_from, $valueOne->standard_value_to);
					$valueOne->order_no 		= $values->order_no;
					$valueOne->stb_label_name 		= $values->stb_label_name;
					$valueOne->stb_end_date 		= $values->stb_end_date;
					$valueOne->limit			= isset($valueOne->requirement_from_to) ? $valueOne->requirement_from_to : '';
					$bookedOrderDetailInfo[$counter] 	= $valueOne;
					$counter++;
				}
			}
		}
		if (!empty($bookedOrderDetailInfo)) {
			foreach ($bookedOrderDetailInfo as $key => $values) {
				$testParameterDetail[$values->test_parameter_id]['parameter_name'] = $values->test_parameter_name;
				$testParameterDetail[$values->test_parameter_id]['method'] = $values->method_name;
				$testParameterDetail[$values->test_parameter_id]['claim'] = $values->claim_value . ' ' . $values->claim_value_unit;
				$testParameterDetail[$values->test_parameter_id]['limit'] = $values->limit;
				$testParameterDetail[$values->test_parameter_id][$values->stb_label_name . ' (' . $values->order_no . ')'] = trim($values->test_result);
			}
			foreach ($testParameterDetail as $key => $values) {
				$testParameterColumnCount[$key] = count($values);
			}
		}
		if (!empty($testParameterDetail) && !empty($testParameterColumnCount)) {
			$highestColumnCount = !empty($testParameterColumnCount) ? max($testParameterColumnCount) : '0';
			foreach ($testParameterDetail as $key => $values) {
				if (count($values) == $highestColumnCount) {
					$testParameterColumnNames = array_keys($values);
				}
			}
			foreach ($testParameterColumnNames as $keyName => $testParameterColumnName) {
				foreach ($testParameterDetail as $key => $valuesAll) {
					$newArray = array();
					if (!array_key_exists($testParameterColumnName, $valuesAll)) {
						$newArray[$testParameterColumnName] = '';
						$finalTestParameterDetail[$key] = array_merge($valuesAll, $newArray);
					} else {
						$finalTestParameterDetail[$key] = $valuesAll;
					}
				}
			}
		}
		$returnData['part_b'] = array('thead' => $testParameterColumnNames, 'tbody' => $finalTestParameterDetail);
		//Getting Part B Detail*********************************************************************

		//Getting Part C Detail*********************************************************************************
		if (!empty($bookedOrderDetail)) {
			foreach ($bookedOrderDetail as $key => $values) {
				$reportDetail = DB::table('order_report_details')->join('users as usersReviewdDb', 'usersReviewdDb.id', 'order_report_details.reviewed_by')->join('users as usersApprovedDb', 'usersApprovedDb.id', 'order_report_details.approved_by')->select('order_report_details.report_type', 'usersReviewdDb.name as reviewed_by', 'usersApprovedDb.name as approved_by')->where('order_report_details.report_id', $values->order_id)->first();
				$reportTypeDetail = DB::table('order_report_details')->join('order_report_note_remark_default', 'order_report_note_remark_default.remark_name', '=', 'order_report_details.remark_value')->whereIn('order_report_note_remark_default.is_display_stamp', [1, 2])->whereColumn('order_report_note_remark_default.remark_name', 'order_report_details.remark_value')->where('order_report_details.report_id', '=', $values->order_id)->where('order_report_note_remark_default.product_category_id', '=', $values->product_category_id)->select('order_report_note_remark_default.is_display_stamp')->first();
				if (!empty($reportTypeDetail->is_display_stamp) && $reportTypeDetail->is_display_stamp == '1') {
					$report_type = 'Done';
				} else if (!empty($reportTypeDetail->is_display_stamp) && $reportTypeDetail->is_display_stamp == '2') {
					$report_type = 'Not Done';
				} else {
					$report_type = '';
				}
				$summaryDetail[$key]['period_of_keeping'] = $values->stb_label_name;
				$summaryDetail[$key]['order_booking_no']  = !empty($values->order_no) ? $values->order_no : '';
				$summaryDetail[$key]['analysis_start_on'] = !empty($values->order_date) ? date(DATEFORMAT, strtotime($values->order_date))  : '';
				$summaryDetail[$key]['reviewed_by'] 	  = !empty($reportDetail->reviewed_by) ? $reportDetail->reviewed_by : '';
				$summaryDetail[$key]['verified_by'] 	  = !empty($reportDetail->approved_by) ? $reportDetail->approved_by : '';
				$summaryDetail[$key]['remarks'] 	  = !empty($reportDetail->stability_remark_value) ? trim($reportDetail->stability_remark_value) : '';;
			}
			$summaryDetailColoum = !empty($summaryDetail) ? array_keys(end($summaryDetail)) : array();
		}
		$returnData['part_c'] = array('thead' => $summaryDetailColoum, 'tbody' => $summaryDetail);
		//Getting Part C Detail*********************************************************************************

		if (!empty($sampleInformationDetail) && !empty($finalTestParameterDetail) && !empty($summaryDetail)) {
			return $models->downloadPDF($returnData, $contentType = 'STF');
		} else {
			return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/**
	 * generate TRF PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function generateTrfSheetPdf(Request $request)
	{

		global $order, $report, $models, $invoice, $mail, $numbersToWord, $trfHdr;

		if ($request->isMethod('post') && !empty($request->trf_id) && !empty($request->generate_trf_pdf)) {
			return $models->downloadPDF($request->all(), $contentType = 'TRF');
		} else {
			return redirect('dashboard')->with('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/**
	 * generate TRF PDF.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getRelatedInvoiceReportDocument(Request $request)
	{

		global $models;

		$responseData = $filterData = array();

		if (!empty($request->downloadContentList)) {
			$downloadContentList 		= $models->convertObjectToArray($models->get_decrypted_string($request->downloadContentList));
			$filterData['heading'] 		= 'Related Invoice Reports :' . '(' . count($downloadContentList) . ')';
			$filterData['mis_report_name'] 	= 'Related Invoice Reports';
			$responseData['tableHead'] 		= !empty($downloadContentList) ? array_keys(end($downloadContentList)) : array();
			$responseData['tableBody'] 		= !empty($downloadContentList) ? $downloadContentList : array();
		}

		if (!empty($responseData['tableBody']) && !empty($request->generate_related_invoice_orders_documents) && strtolower($request->generate_related_invoice_orders_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}

	/************************************
	 * Description : Download customers excel
	 * Date        : 14-08-18
	 * Created By  : Praveen Singh
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function generateCustomerExistAccountDocuments(Request $request)
	{

		global $models;

		$responseData = $filterData = array();

		if (!empty($request->downloadContentList)) {
			$downloadContentList = $models->convertObjectToArray($models->get_decrypted_string($request->downloadContentList));
			$downloadContentList = $models->unsetFormDataVariablesArray($downloadContentList, array('customer_connected_status'));
			if (!empty($downloadContentList)) {
				$filterData['heading'] 			= 'Customer Exist Account Detail' . '(' . count($downloadContentList) . ')';
				$filterData['mis_report_name'] 	= 'Customer Exist Account Detail';
				$responseData['tableHead'] 		= !empty($downloadContentList) ? array_keys(end($downloadContentList)) : array();
				$responseData['tableBody'] 		= !empty($downloadContentList) ? $downloadContentList : array();
			}
		}

		if (!empty($responseData['tableBody']) && !empty($request->generate_documents) && strtolower($request->generate_documents) == 'excel') {
			return $models->downloadExcel($responseData, $filterData);
		} else {
			return redirect('dashboard')->withErrors(config('messages.message.fileDownloadErrorMsg'));
		}
	}
}
