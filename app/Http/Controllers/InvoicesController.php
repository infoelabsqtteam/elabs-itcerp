<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Company;
use App\Order;
use App\Models;
use App\InvoiceHdr;
use App\InvoiceHdrDetail;
use App\NumbersToWord;
use App\SendMail;
use App\Report;
use App\CreditNote;
use App\DebitNote;
use App\Customer;
use Session;
use Validator;
use Route;
use DB;
use PDF;

class InvoicesController extends Controller
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

		global $models, $order, $customer, $report, $invoice, $numbersToWord, $mail, $creditNote, $debitNote;

		$models         = new Models();
		$order          = new Order();
		$report 		= new Report();
		$customer       = new Customer();
		$invoice        = new invoiceHdr();
		$mail           = new SendMail();
		$numbersToWord  = new NumbersToWord();
		$creditNote 	= new CreditNote();
		$debitNote 		= new DebitNote();

		//Checking the User Session
		$this->middleware('auth');

		$this->middleware(function ($request, $next) {
			$this->auth = Auth::user();
			parent::__construct($this->auth);
			//Checking current request is allowed or not
			$allowedAction = array('index', 'navigation');
			$actionData = explode('@', Route::currentRouteAction());
			$action = !empty($actionData[1]) ? trim(strtolower($actionData[1])) : '0';
			if (defined('NOTALlOWEDTONAVIGATE') && empty(NOTALlOWEDTONAVIGATE) && in_array($action, $allowedAction)) {
				return redirect('dashboard')->withErrors('Permission Denied!');
			}
			return $next($request);
		});
	}

	/**********************************************
	 * Description : View of Invoice landing page
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 **********************************************/
	public function index()
	{

		global $order, $models, $invoice, $mail;

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id        = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('sales.invoices.index', ['title' => 'Invoices', '_invoices' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids, 'department_ids' => $department_ids]);
	}

	/**********************************************
	 * Description : Listing of all Invoices
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 **********************************************/
	public function getBranchWiseInvoices(Request $request)
	{

		global $order, $models, $customer, $invoice;

		$error       = '0';
		$message     = config('messages.message.error');
		$data        = '';
		$formData    = array();
		$currentDate = date('Y-m-d');

		//Assigning Condition according to the Role Assigned
		parse_str($request->formData, $formData);

		$invoicesObj = DB::table('invoice_hdr')
			->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
			->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
			->join('order_report_details', 'order_report_details.report_id', 'invoice_hdr_detail.order_id')
			->join('divisions', 'divisions.division_id', 'invoice_hdr.division_id')
			->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_id')
			->join('users as invoicedBy', 'invoicedBy.id', 'invoice_hdr.created_by')
			->join('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
			->leftJoin('city_db', 'city_db.city_id', 'customer_master.customer_city')
			->leftJoin('invoice_dispatch_dtls', function ($join) {
				$join->on('invoice_dispatch_dtls.invoice_id', '=', 'invoice_hdr.invoice_id');
				$join->where('invoice_dispatch_dtls.invoice_dispatch_status', '1');
				$join->whereRaw('invoice_dispatch_dtls.invoice_dispatch_id IN (SELECT MAX(idd.invoice_dispatch_id) FROM invoice_dispatch_dtls idd WHERE idd.invoice_id = invoice_hdr.invoice_id)');
			})
			->leftJoin('order_mail_dtl', function ($join) {
				$join->on('order_mail_dtl.invoice_id', '=', 'invoice_hdr.invoice_id');
				$join->whereNotNull('order_mail_dtl.invoice_id');
				$join->where('order_mail_dtl.mail_active_type', '1');
				$join->where('order_mail_dtl.mail_content_type', '4');
			})
			->leftJoin('users as invoiceDispatchByDB', 'invoiceDispatchByDB.id', 'invoice_dispatch_dtls.invoice_dispatch_by')
			->select('invoice_hdr.*', 'order_master.status as orderstatus', 'order_master.order_id', 'customer_master.customer_name', 'customer_master.customer_state', 'divisions.division_name', 'invoicedBy.name as invoiced_by', 'order_report_details.report_file_name', 'city_db.city_name as customer_city', 'order_master.order_no', 'invoice_dispatch_dtls.invoice_dispatch_date as dispatch_date', 'invoiceDispatchByDB.name as dispatch_by', 'invoice_financial_years.ify_name', 'invoice_hdr.invoice_id as invoice_mail_status_text', 'order_mail_dtl.mail_status as invoice_mail_status');

		$this->setConditionAccordingToRoleAssigned($invoicesObj, $formData);
		$this->getInvoicesMultisearch($invoicesObj, $formData);

		$invoiceList = $invoicesObj->groupBy('invoice_hdr.invoice_id')->orderBy('invoice_hdr.invoice_id', 'DESC')->get()->toArray();

		//to formate created and updated date
		$models->formatTimeStampFromArray($invoiceList, DATETIMEFORMAT);

		//to Get Net Total Amount According to Invoice Template Type
		$invoice->getNetTotalAmtByInvTemplateType($invoiceList);

		return response()->json(['invoiceList' => $invoiceList]);
	}

	/**********************************************
	 * Description : PDF Download of all Invoices
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 **********************************************/
	public function generateBranchWiseInvoicesPdf(Request $request)
	{

		global $order, $models, $invoice;

		$error       = '0';
		$message     = config('messages.message.error');
		$data        = '';
		$formData    = array();
		$currentDate = date('Y-m-d');

		if ($request->isMethod('post') && !empty($request->generate_invoice_documents)) {

			//Assigning Condition according to the Role Assigned
			$formData = $request->all();

			$invoicesObj = DB::table('invoice_hdr')
				->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
				->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
				->join('order_report_details', 'order_report_details.report_id', 'invoice_hdr_detail.order_id')
				->join('divisions', 'divisions.division_id', 'invoice_hdr.division_id')
				->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_id')
				->join('users as invoicedBy', 'invoicedBy.id', 'invoice_hdr.created_by')
				->join('invoice_financial_years', 'invoice_financial_years.ify_id', 'invoice_hdr.inv_fin_yr_id')
				->leftJoin('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->leftJoin('invoice_dispatch_dtls', function ($join) {
					$join->on('invoice_dispatch_dtls.invoice_id', '=', 'invoice_hdr.invoice_id');
					$join->where('invoice_dispatch_dtls.invoice_dispatch_status', '1');
					$join->whereRaw('invoice_dispatch_dtls.invoice_dispatch_id IN (SELECT MAX(idd.invoice_dispatch_id) FROM invoice_dispatch_dtls idd WHERE idd.invoice_id = invoice_hdr.invoice_id)');
				})
				->leftJoin('order_mail_dtl', function ($join) {
					$join->on('order_mail_dtl.invoice_id', '=', 'invoice_hdr.invoice_id');
					$join->whereNotNull('order_mail_dtl.invoice_id');
					$join->where('order_mail_dtl.mail_active_type', '1');
					$join->where('order_mail_dtl.mail_content_type', '4');
				})
				->leftJoin('users as invoiceDispatchByDB', 'invoiceDispatchByDB.id', 'invoice_dispatch_dtls.invoice_dispatch_by')
				->select('invoice_hdr.invoice_id', 'invoice_hdr.invoice_no', 'divisions.division_name as branch', 'customer_master.customer_name', 'customer_master.customer_state', 'city_db.city_name as place', 'order_master.order_no as related_orders', 'invoice_hdr.invoice_date', 'invoice_hdr.net_total_amount', 'invoice_dispatch_dtls.invoice_dispatch_date as dispatch_date', 'invoiceDispatchByDB.name as dispatch_by', 'invoicedBy.name as invoiced_by', 'invoice_financial_years.ify_name as financial_year', 'invoice_hdr.invoice_id as invoice_mail_status_text', 'order_mail_dtl.mail_status as invoice_mail_status');

			$this->setConditionAccordingToRoleAssigned($invoicesObj, $formData);
			$this->getInvoicesMultisearch($invoicesObj, $formData);

			$invoiceList = $invoicesObj->groupBy('invoice_hdr.invoice_id')->orderBy('invoice_hdr.invoice_id', 'DESC')->get();

			//to formate created and updated date
			$models->formatTimeStampFromArrayExcel($invoiceList, DATEFORMATEXCEL);

			//to Get Net Total Amount According to Invoice Template Type
			$invoice->getNetTotalAmtByInvTemplateType($invoiceList);

			if (!empty($invoiceList)) {

				//to get all invoice order list
				$invoice->getInvoiceOrdersList($invoiceList);

				$invoicesData 			= !empty($invoiceList) ? json_decode(json_encode($invoiceList), true) : array();
				$invoicesData 			= $models->unsetFormDataVariablesArray($invoicesData, array('invoice_mail_status_text', 'canDispatchOrder', 'customer_state', 'invoice_template_type'));
				$response['heading'] 		= 'Invoices List' . '(' . count($invoicesData) . ')';
				$response['tableHead'] 		= !empty($invoicesData) ? array_keys(end($invoicesData)) : array();
				$response['tableBody'] 		= !empty($invoicesData) ? $invoicesData : array();
				$response['tablefoot']		= array();
				$response['mis_report_name']  	= 'invoice_document';

				if ($request->generate_invoice_documents == 'PDF') {
					$pdfHeaderContent  		= $models->getHeaderFooterTemplate();
					$response['header_content']	= $pdfHeaderContent->header_content;
					$response['footer_content']	= $pdfHeaderContent->footer_content;
					return $models->downloadPDF($response, $contentType = 'invoicesheet');
				} elseif ($request->generate_invoice_documents == 'Excel') {
					return $models->generateExcel($response);
				}
			} else {
				Session::put('errorMsg', config('messages.message.noRecordFound'));
				return redirect('dashboard');
			}
		}
		Session::put('errorMsg', config('messages.message.fileDownloadErrorMsg'));
		return redirect('dashboard');
	}

	/**********************************************
	 * Description : Listing of all Invoices
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 **********************************************/
	public function setConditionAccordingToRoleAssigned($invoicesObj, $formData)
	{

		global $order, $models;

		$user_id            	 = defined('USERID') ? USERID : '0';
		$division_id        	 = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     	 = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           	 = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids 	 = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		$divisionId     	 = !empty($formData['division_id']) ? $formData['division_id'] : $division_id;
		$invoiceDateFrom  	 = !empty($formData['order_date_from']) ? $formData['order_date_from'] : '0';
		$invoiceDateTo    	 = !empty($formData['order_date_to']) ? $formData['order_date_to'] : '0';
		$keyword        	 = !empty($formData['keyword']) ? $formData['keyword'] : '0';
		$isViewCancelledInvoices = !empty($formData['is_view_cancelled_invoices']) ? $formData['is_view_cancelled_invoices'] : '0';

		//If logged in User is Invoicer ID:8 AND Order Status ID:8(INVOICE GENERATOR)
		if (defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR) {
			//$invoicesObj->OrWhere('invoice_hdr.created_by', $user_id);
		}
		//Filtering records according to division assigned
		if (!empty($divisionId) && is_numeric($divisionId)) {
			$invoicesObj->where('invoice_hdr.division_id', $divisionId);
		}
		//Filtering records according to department assigned
		if (!empty($department_ids) && is_array($department_ids)) {
			$invoicesObj->whereIn('invoice_hdr.product_category_id', $department_ids);
		}
		//Filtering records according to from and to order date
		if (!empty($invoiceDateFrom) && !empty($invoiceDateTo)) {
			$invoicesObj->whereBetween(DB::raw("DATE(invoice_hdr.invoice_date)"), array($invoiceDateFrom, $invoiceDateTo));
		} else if (!empty($invoiceDateFrom) && empty($invoiceDateTo)) {
			$invoicesObj->where(DB::raw("DATE(invoice_hdr.invoice_date)"), '>=', $invoiceDateFrom);
		} else if (empty($invoiceDateFrom) && !empty($invoiceDateTo)) {
			$invoicesObj->where(DB::raw("DATE(invoice_hdr.invoice_date)"), '<=', $invoiceDateTo);
		} else {
			$invoicesObj->where(DB::raw("MONTH(invoice_hdr.invoice_date)"), date('m'));
			$invoicesObj->where(DB::raw("YEAR(invoice_hdr.invoice_date)"), date('Y'));
		}
		//Filtering records according to search keyword
		if (!empty($keyword)) {
			$invoicesObj->where('invoice_hdr.invoice_no', 'LIKE', '%' . $keyword);
		}
		//Filtering Active and Cancelled Invoices
		if (!empty($isViewCancelledInvoices)) {
			$invoicesObj->join('invoice_cancellation_dtls', 'invoice_cancellation_dtls.invoice_id', 'invoice_hdr.invoice_id');
			$invoicesObj->where('invoice_hdr.invoice_status', '2');
			$invoicesObj->where('invoice_hdr_detail.invoice_hdr_status', '2');
		} else {
			$invoicesObj->where('invoice_hdr.invoice_status', '1');
			$invoicesObj->where('invoice_hdr_detail.invoice_hdr_status', '1');
		}
	}

	/**********************************************
	 * Description : Search on Listing of all Invoices
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 **********************************************/
	public function getInvoicesMultisearch($invoicesObj, $searchArry)
	{

		global $order, $models;

		if (!empty($searchArry['search_order_no'])) {
			$invoicesObj->where('order_master.order_no', 'like', '%' . trim($searchArry['search_order_no']) . '%');
		}
		if (!empty($searchArry['search_invoice_no'])) {
			$invoicesObj->where('invoice_hdr.invoice_no', 'like', '%' . trim($searchArry['search_invoice_no']) . '%');
		}
		if (!empty($searchArry['search_division_id']) && is_numeric($searchArry['search_division_id'])) {
			$invoicesObj->where('divisions.division_name', 'like', '%' . trim($searchArry['search_division_id']) . '%');
		}
		if (!empty($searchArry['search_customer_name'])) {
			$invoicesObj->where('customer_master.customer_name', 'like', '%' . trim($searchArry['search_customer_name']) . '%');
		}
		if (!empty($searchArry['search_invoice_date'])) {
			$invoicesObj->where('invoice_hdr.invoice_date', 'like', '%' . $models->getFormatedDate($searchArry['search_invoice_date'], MYSQLDATFORMAT) . '%');
		}
		if (!empty($searchArry['search_net_total_amount'])) {
			$invoicesObj->where('invoice_hdr.net_total_amount', 'like', '%' . trim($searchArry['search_net_total_amount']) . '%');
		}
		if (!empty($searchArry['search_created_by'])) {
			$invoicesObj->where('invoicedBy.name', 'like', '%' . trim($searchArry['search_created_by']) . '%');
		}
		if (!empty($searchArry['search_dispatch_pendency'])) {
			$invoicesObj->whereNull('invoice_dispatch_dtls.invoice_dispatch_date');
		}
		if (!empty($searchArry['search_dispatch_date'])) {
			$invoicesObj->where('invoice_dispatch_dtls.invoice_dispatch_date', 'LIKE', '%' . $models->getFormatedDate($searchArry['search_dispatch_date'], MYSQLDATFORMAT) . '%');
		}
		if (!empty($searchArry['search_dispatch_by'])) {
			$invoicesObj->where('invoiceDispatchByDB.name', 'LIKE', '%' . $searchArry['search_dispatch_by'] . '%');
		}
	}

	/**********************************************
	 * Description : Get Billing Type Customer List
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 **********************************************/
	public function getBillingTypeCustomerList(Request $request)
	{

		global $order, $models;

		$billingTypeCustomerList = array();

		//Saving record in orders table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the Serialze Dta
			parse_str($request->formData, $formData);

			$user_id            	     = defined('USERID') ? USERID : '0';
			$defaultDepartmentIds     	     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$defaultDivisionId        	     = defined('DIVISIONID') ? DIVISIONID : '0';
			$defaultUserDepartmentIds        = defined('USER_DEPARTMENT_IDS') ? USER_DEPARTMENT_IDS : '0';
			$formData['division_id']   	     = !empty($formData['division_id']) ? $formData['division_id'] : $defaultDivisionId;
			$formData['product_category_id'] = !empty($formData['product_category_id']) ? array($formData['product_category_id']) : $defaultUserDepartmentIds;
			$formData['billing_type']	     = !empty($formData['billing_type']) ? $formData['billing_type'] : '0';

			if (!empty($formData['division_id']) && !empty($formData['billing_type'])) {

				$billingTypeCustomerObj = DB::table('customer_master')
					->join('order_master', 'order_master.customer_id', 'customer_master.customer_id')
					->leftJoin('invoice_hdr_detail', function ($join) {
						$join->on('order_master.order_id', '=', 'invoice_hdr_detail.order_id');
						$join->where('invoice_hdr_detail.invoice_hdr_status', '1');
					})
					->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
					->join('city_db', 'city_db.city_id', 'order_master.customer_city')
					->select('customer_master.customer_id', DB::raw('CONCAT(customer_master.customer_name,"/",city_db.city_name) AS customer_name'))
					->where('order_master.division_id', $formData['division_id'])
					->whereNull('order_master.order_sample_type')
					->whereNull('invoice_hdr_detail.order_id')
					->where('order_master.status', '8');    //completed for report and ready for invoice generation

				if (!empty($formData['product_category_id']) && is_array($formData['product_category_id'])) {
					$billingTypeCustomerObj->whereIn('order_master.product_category_id', $formData['product_category_id']);
				}
				if ($formData['billing_type'] == '5') { 		//Po-Wise
					$billingTypeCustomerObj->where('order_master.billing_type_id', '=', $formData['billing_type']);
					$billingTypeCustomerObj->whereNotNull('order_master.po_no');
				} else {
					$billingTypeCustomerObj->where('order_master.billing_type_id', '=', $formData['billing_type']);
				}
				$billingTypeCustomerObj->groupBy('order_master.customer_id');
				$billingTypeCustomerObj->orderBy('customer_master.customer_name', 'ASC');
				$billingTypeCustomerList = $billingTypeCustomerObj->get();
			}
		}

		return response()->json(['billingTypeCustomerList' => $billingTypeCustomerList]);
	}

	/**********************************************
	 * Description : Get Customer Billing Type Orders
	 * Date 	   : 26-July-2018
	 * Author      : Praveen Singh
	 **********************************************/
	public function getCustomerBillingTypeOrders(Request $request)
	{

		global $order, $models, $invoice;

		$error               = '0';
		$message             = config('messages.message.error');
		$data  		     	 = $hasGenerateInvoiceButton    = '';
		$currentDate 	     = date('Y-m-d');
		$product_category_id = $division_id = $billing_type = $customer_id = '0';
		$formData            = $canGenerateInvoice = $customerBillingTypeOrders = array();

		//Listing of orders according to billing type
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the form Data
			parse_str($request->formData, $formData);

			if (empty($formData['customer_id'])) {
				$error   = '0';
				$message = config('messages.message.customerSelectionRequired');
			} else {
				$user_id            			= defined('USERID') ? USERID : '0';
				$defaultDepartmentIds     		= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
				$defaultDivisionId        		= defined('DIVISIONID') ? DIVISIONID : '0';
				$defaultUserDepartmentIds   		= defined('USER_DEPARTMENT_IDS') ? USER_DEPARTMENT_IDS : '0';
				$formData['division_id']   		= !empty($formData['division_id']) ? $formData['division_id'] : $defaultDivisionId;
				$formData['product_category_id']   	= !empty($formData['product_category_id']) ? array($formData['product_category_id']) : $defaultUserDepartmentIds;
				$formData['billing_type']		= !empty($formData['billing_type']) ? $formData['billing_type'] : '0';
				$formData['customer_id']		= !empty($formData['customer_id']) ? $formData['customer_id'] : '0';

				if (!empty($formData['division_id']) && !empty($formData['customer_id']) && !empty($formData['billing_type'])) {

					$division_id	 = !empty($formData['division_id']) ? $formData['division_id'] : '0';
					$product_category_id = !empty($formData['product_category_id']) ? $formData['product_category_id'] : '0';
					$billing_type	 = !empty($formData['billing_type']) ? $formData['billing_type'] : '0';
					$customer_id	 = !empty($formData['customer_id']) ? $formData['customer_id'] : '0';

					$customerBillingTypeOrdersObj = DB::table('order_master')
						->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
						->leftJoin('invoice_hdr_detail', function ($join) {
							$join->on('order_master.order_id', '=', 'invoice_hdr_detail.order_id');
							$join->where('invoice_hdr_detail.invoice_hdr_status', '1');
						})
						->leftJoin('customer_master as invocingTo_customer_master', 'invocingTo_customer_master.customer_id', 'order_master.invoicing_to')
						->leftJoin('city_db as invoicingToCity', 'invoicingToCity.city_id', 'invocingTo_customer_master.customer_city')
						->leftJoin('state_db as invoicingToState', 'invoicingToState.state_id', 'invocingTo_customer_master.customer_state')
						->leftJoin('order_linked_po_dtl', 'order_linked_po_dtl.olpd_order_id', 'order_master.order_id')
						->leftJoin('customer_gst_tax_slab_types', 'customer_gst_tax_slab_types.cgtst_id', 'customer_master.customer_gst_tax_slab_type_id')
						->leftJoin('test_standard as test_standard_db', 'test_standard_db.test_std_id', 'order_master.defined_test_standard')
						->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
						->join('order_report_details', 'order_report_details.report_id', '=', 'order_master.order_id')
						->join('customer_discount_types', 'customer_discount_types.discount_type_id', '=', 'order_master.discount_type_id')
						->where('order_master.customer_id', $formData['customer_id'])
						->whereNull('invoice_hdr_detail.order_id')
						->where('order_master.status', '8')    //completed for report and ready for invoice generation
						->where('order_master.billing_type_id', '=', $formData['billing_type'])
						->where('order_master.division_id', $formData['division_id']);

					if (!empty($formData['product_category_id']) && is_array($formData['product_category_id'])) {
						$customerBillingTypeOrdersObj->whereIn('order_master.product_category_id', $formData['product_category_id']);
					}
					if ($formData['billing_type'] == '1') {              	//Daily
						$customerBillingTypeOrdersObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $currentDate);
					} else if ($formData['billing_type'] == '2') {      	//Regular
						$customerBillingTypeOrdersObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $currentDate);
					} else if ($formData['billing_type'] == '3') {      	//Weekly
						list($weekFirstDate, $weekLastDate) = $models->getFirstAndLastDayOfWeek($currentDate, $format = 'Y-m-d');
						$customerBillingTypeOrdersObj->whereBetween(DB::raw("DATE(order_master.order_date)"), array($weekFirstDate, $weekLastDate));
					} else if ($formData['billing_type'] == '4') {      	//Monthly
						list($monthFirstDate, $monthLastDate) = $models->getFirstAndLastDayOfMonth($currentDate, $format = 'Y-m-d');
						$customerBillingTypeOrdersObj->where(DB::raw("DATE(order_master.order_date)"), '<=', $currentDate);
					} else if ($formData['billing_type'] == '5') { 		//Po-Wise
						if (empty($formData['po_order'])) {
							$error = '0';
							$message = config('messages.message.invoicePONumber');
							return response()->json(['error' => $error, 'message' => $message]);
						} else {
							$customerBillingTypeOrdersObj->whereIn("order_master.po_no", array_values($formData['po_order']));
						}
					}

					$customerBillingTypeOrdersObj->select('order_master.*', 'order_report_details.report_date', 'order_report_details.report_no', 'order_report_details.report_file_name', 'product_master_alias.c_product_name as sample_description', 'customer_discount_types.discount_type', 'order_linked_po_dtl.olpd_cpo_no', 'order_linked_po_dtl.olpd_cpo_file_name', 'olpd_cpo_date', DB::raw('CONCAT(invocingTo_customer_master.customer_name,"/",invoicingToState.state_name,"/",invoicingToCity.city_name) AS invocingto_customer_name'), 'customer_gst_tax_slab_types.cgtst_name', 'test_standard_db.test_std_name as defined_test_standard_name');
					$customerBillingTypeOrdersObj->orderBy('order_master.order_date', 'DESC');
					$customerBillingTypeOrders = $customerBillingTypeOrdersObj->get();

					foreach ($customerBillingTypeOrders as $values) {
						list($perTotalAmount, $perTotalDiscount) = $invoice->getOrderInvoivingPrice($values->customer_id, $values->order_id, $values->discount_type_id, $values->discount_value);
						$values->olpd_cpo_no			= !empty($values->olpd_cpo_no) && !empty($values->olpd_cpo_date) ? $values->olpd_cpo_no . ' | ' . date('d-m-Y', strtotime($values->olpd_cpo_date)) : '';
						$values->olpd_cpo_file_link		= !empty($values->olpd_cpo_file_name) ? url(PO_PATH . $values->olpd_cpo_file_name) : '';
						$values->order_reinvoiced_class		= !empty($values->order_reinvoiced_count) ? 'fontbd' : '';
						$values->totalAmount                    = number_format($perTotalAmount, 2);
						$values->netDiscount                    = number_format($perTotalDiscount, 2);
						$values->extra_amount                   = !empty($values->extra_amount) ? round($values->extra_amount) : '0.00';
						$values->netAmount                      = number_format($perTotalAmount + $values->extra_amount - $perTotalDiscount, 2);
						$canGenerateInvoice[$values->order_id]  = $values->totalAmount;
					}
					$error                     			= !empty($customerBillingTypeOrders) ? '1' : '0';
					$message                   			= $error ? '' : $message;
					$hasGenerateInvoiceButton  			= !empty($canGenerateInvoice) && in_array('0.00', $canGenerateInvoice) ? '0' : '1';
				}
			}
		}

		return response()->json(['error' => $error, 'message' => $message, 'division_id' => $division_id, 'product_category_id' => $product_category_id, 'billing_type' => $billing_type, 'customer_id' => $customer_id, 'hasGenerateInvoiceButton' => $hasGenerateInvoiceButton, 'customerBillingTypeOrders' => $customerBillingTypeOrders]);
	}

	/**********************************************
	 * Description : Get Customer Billing Type Invoices
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 **********************************************/
	public function getCustomerBillingTypeInvoices(Request $request)
	{

		global $order, $invoice;

		$error        = '0';
		$message      = config('messages.message.error');
		$data         = '';
		$currentDate  = date('Y-m-d');
		$billing_type = $customer_id = '';
		$formData     = $customerBillingTypeInvoices = array();

		//Listing of Invoices according to billing type
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing Value of form data
			parse_str($request->formData, $formData);

			if (empty($formData['customer_id'])) {
				$error   = '0';
				$message = config('messages.message.selectCustomerForInvoiceDisplay');
			} else {
				$user_id            		= defined('USERID') ? USERID : '0';
				$defaultDepartmentIds     	= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
				$defaultDivisionId        	= defined('DIVISIONID') ? DIVISIONID : '0';
				$defaultUserDepartmentIds   	= defined('USER_DEPARTMENT_IDS') ? USER_DEPARTMENT_IDS : '0';
				$formData['division_id']   	= !empty($formData['division_id']) ? $formData['division_id'] : $defaultDivisionId;
				$formData['product_category_id'] = !empty($formData['product_category_id']) ? array($formData['product_category_id']) : $defaultUserDepartmentIds;
				$formData['billing_type']	= !empty($formData['billing_type']) ? $formData['billing_type'] : '0';
				$formData['customer_id']	= !empty($formData['customer_id']) ? $formData['customer_id'] : '0';

				if (!empty($formData['division_id']) && !empty($formData['customer_id']) && !empty($formData['billing_type'])) {

					$division_id  	 = $formData['division_id'];
					$product_category_id = $formData['product_category_id'];
					$billing_type 	 = $formData['billing_type'];
					$customer_id  	 = $formData['customer_id'];

					$customerBillingTypeInvoicesObj = DB::table('invoice_hdr')
						->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_id')
						->select('invoice_hdr.*', 'customer_master.customer_name')
						->where('invoice_hdr.customer_id', $customer_id)
						->where('invoice_hdr.division_id', $division_id)
						->where('invoice_hdr.invoice_type', '=', $billing_type)
						->where('invoice_hdr.invoice_status', '1');

					if (!empty($product_category_id) && is_array($product_category_id)) {
						$customerBillingTypeInvoicesObj->whereIn('invoice_hdr.product_category_id', $product_category_id);
					}

					$customerBillingTypeInvoicesObj->orderBy('invoice_hdr.invoice_date', 'DESC');
					$customerBillingTypeInvoices = $customerBillingTypeInvoicesObj->get();
					$error   = '1';
					$message = '';
				}
			}
		}

		return response()->json(['error' => $error, 'message' => $message, 'billing_type' => $billing_type, 'customer_id' => $customer_id, 'customerInvoicesList' => $customerBillingTypeInvoices]);
	}

	/********************************************************************
	 * Description : update Customer State Defined In Invoicing-To Column of Order Master
	 * Date        : 05-02-2018
	 * Author      : Ruby Singh
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ***********************************************************************/
	public function getPurchaseOrderDetail(Request $request)
	{

		global $order, $models;

		$error      = '0';
		$message    = config('message.message.error');
		$returnData = array();

		//Saving record in orders table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the Serialze Dta
			parse_str($request->formData, $formData);

			$user_id            		= defined('USERID') ? USERID : '0';
			$defaultDepartmentIds     		= defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
			$defaultDivisionId        		= defined('DIVISIONID') ? DIVISIONID : '0';
			$defaultUserDepartmentIds   	= defined('USER_DEPARTMENT_IDS') ? USER_DEPARTMENT_IDS : '0';
			$formData['division_id']   		= !empty($formData['division_id']) ? $formData['division_id'] : $defaultDivisionId;
			$formData['product_category_id']   	= !empty($formData['product_category_id']) ? array($formData['product_category_id']) : $defaultUserDepartmentIds;
			$formData['billing_type']		= !empty($formData['billing_type']) ? $formData['billing_type'] : '0';

			if (!empty($formData['division_id']) && isset($formData['product_category_id']) && !empty($formData['billing_type']) && !empty($formData['customer_id'])) {

				$returnObj = DB::table('order_master')
					->select('order_master.po_no', 'order_master.po_date', DB::raw('CONCAT(order_master.po_no,"/",order_master.po_date) as purchaseOrder'))
					->whereNotNull('order_master.po_no')
					->where('order_master.customer_id', '=', $formData['customer_id'])
					->where('order_master.division_id', $formData['division_id'])
					->where('order_master.billing_type_id', '=', $formData['billing_type'])
					->where('order_master.status', '=', '8')
					->whereNull('order_master.order_sample_type');

				if (!empty($formData['product_category_id']) && is_array($formData['product_category_id'])) {
					$returnObj->whereIn('order_master.product_category_id', $formData['product_category_id']);
				}
				$returnData = $returnObj->groupBy('order_master.po_no')->orderBy('order_master.po_no', 'ASC')->get()->toArray();

				if (!empty($returnData)) {
					foreach ($returnData as $value) {
						$value->purchaseOrder = $value->po_no . ' | ' . date('d-m-Y', strtotime($value->po_date));
					}
				}
			}
		}

		$returnData = !empty($returnData) ? $returnData : array();
		$error      = !empty($returnData) ? '1' : '0';

		return response()->json(array('error' => $error, 'message' => $message, 'purchaseOrderNoList' => $returnData));
	}

	/**********************************************
	 * Description : Generate Invoices
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 **********************************************/
	public function generateInvoices(Request $request)
	{

		global $order, $models, $customer, $invoice;

		$error           = '0';
		$message         = config('messages.message.error');
		$data            = '';
		$billing_type_id = '';
		$customer_id	 = '';

		try {

			//Starting transaction
			DB::beginTransaction();

			//Parsing the form Data
			parse_str($request->formData, $formData);

			if (!empty($formData['order_id']) && is_array($formData['order_id']) && !empty($formData['division_id']) && isset($formData['product_category_id']) && !empty($formData['billing_type_id']) && !empty($formData['customer_id'])) {

				$currentDate         	= defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');
				$SGST                	= defined('SGST') ? SGST : '0';
				$CGST                	= defined('CGST') ? CGST : '0';
				$IGST                	= defined('IGST') ? IGST : '0';
				$division_id 	     	= !empty($formData['division_id']) ? $formData['division_id'] : '0';
				$product_category_id 	= !empty($formData['product_category_id']) ? $formData['product_category_id'] : '0';
				$billing_type_id     	= !empty($formData['billing_type_id']) ? $formData['billing_type_id'] : '0';
				$customer_id 	     	= !empty($formData['customer_id']) ? $formData['customer_id'] : '0';
				$order_ids 	     		= !empty($formData['order_id']) ? $formData['order_id'] : array(0);
				$customerData	     	= $customer->getCustomers($customer_id);

				if (!empty($customerData->customer_gst_tax_slab_type_id)) {		//Checking Tax Slab Type defined for this customer or not

					//function arguments
					$fun_args = array(
						'currentDate' 			=> $currentDate,
						'order_ids' 			=> $order_ids,
						'customer_id' 			=> $customer_id,
						'division_id' 			=> $division_id,
						'product_category_id' 	=> $product_category_id,
						'billing_type_id' 		=> $billing_type_id,
						'SGST' 					=> $SGST,
						'CGST' 					=> $CGST,
						'IGST' 					=> $IGST
					);

					if ($billing_type_id == '1') {              //Daily
						$invoiceIds = $this->generateInvoiceDaily($fun_args);
					} else if ($billing_type_id == '2') {      //Regular
						$invoiceIds = $this->generateInvoiceRegular($fun_args);
					} else if ($billing_type_id == '3') {      //Weekly
						$invoiceIds = $this->generateInvoiceWeekly($fun_args);
					} else if ($billing_type_id == '4') {      //Monthly
						$invoiceIds = $this->generateInvoiceMonthly($fun_args);
					} else if ($billing_type_id == '5') {      //Monthly
						$invoiceIds = $this->generateInvoicePOWise($fun_args);
					}
					if (!empty($invoiceIds)) {

						$error   = '1';
						$message = config('messages.message.invoicesSuccess');

						//Committing the queries
						DB::commit();
					}
				} else {
					$message = config('messages.message.NoTaxSlabDefinedForThisCustomer');
				}
			}
		} catch (\Exception $e) {
			DB::rollback();
		} catch (\Throwable $e) {
			DB::rollback();
		}

		return response()->json(['error' => $error, 'message' => $message, 'billing_type' => $billing_type_id, 'customer_id' => $customer_id]);
	}

	/**********************************************
	 * Description : Generate Invoice Daily
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 * Modifed On  : 02-Sept-2020
	 **********************************************/
	public function generateInvoiceDaily($arguments)
	{

		global $order, $models, $invoice;

		$flag = $invoiceInfo = $invoiceHdrData = $invoiceHdrDetailInfo = $totalAmount = $totalDiscount = $invoceSessionStatusArr = array();

		try {

			//Converting arrays keys into variables
			extract($arguments);

			$orderInvoicesObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('division_parameters', 'division_parameters.division_id', 'divisions.division_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->select('order_master.order_id', 'order_master.order_no', 'order_master.order_date', 'order_master.product_category_id', 'order_master.customer_id', 'order_master.division_id', 'order_master.discount_type_id', 'order_master.discount_value', 'order_master.surcharge_value', 'order_master.extra_amount', 'division_parameters.division_state', 'customer_master.customer_state', 'customer_master.customer_gst_tax_slab_type_id', 'customer_master.customer_gst_category_id', 'customer_master.customer_gst_type_id', 'order_master.invoicing_to', 'order_master.billing_type_id')
				->where('order_master.customer_id', $customer_id)
				->where('order_master.billing_type_id', '=', $billing_type_id)
				->where('order_master.division_id', $division_id)
				->whereIn('order_master.order_id', $order_ids)
				->where('order_master.status', '8');    //completed for report and ready for invoice generation

			if (!empty($product_category_id) && is_numeric($product_category_id)) {
				$orderInvoicesObj->where('order_master.product_category_id', $product_category_id);
			}
			$orderInvoicesData = $orderInvoicesObj->orderBy('order_master.order_date', 'DESC')->get();

			if (!empty($orderInvoicesData)) {
				foreach ($orderInvoicesData as $key => $orders) {
					$invoice->updateCustomerStateDefinedInInvoicingToColumn($orders); //Updating Customer State According to Invocing-To Defined at the time of Order Booking
					list($perTotalAmount, $perTotalDiscount) = $invoice->getOrderInvoivingPrice($orders->customer_id, $orders->order_id, $orders->discount_type_id, $orders->discount_value);
					$orders->order_amount               = $perTotalAmount;
					$orders->order_discount             = $perTotalDiscount;
					$invoiceHdrData[$orders->order_id]  = $orders;
					$totalAmount[$key]                  = $perTotalAmount;
					$totalDiscount[$key]                = $perTotalDiscount;
					$totalSurchargeAmount[$key]         = $orders->surcharge_value;
					$totalExtraAmount[$key]             = $orders->extra_amount;
					$invoceSessionStatusArr[$orders->division_id . '-' . $orders->product_category_id] = $invoice->__getInvoiceNewSessionDtl($currentDate, $orders->division_id, $orders->product_category_id);
					$invoiceHdr                         = new invoiceHdr();
					$invoiceHdr->division_id            = $orders->division_id;
					$invoiceHdr->product_category_id    = $orders->product_category_id;
					$invoiceHdr->invoice_no             = $invoice->generateInvoiceNumber($currentDate, $orders->product_category_id, $orders->division_id);
					$invoiceHdr->invoice_date           = $currentDate;
					$invoiceHdr->inv_fin_yr_id 		= $models->getInvoiceFinancialYear($currentDate);
					$invoiceHdr->invoice_type           = $orders->billing_type_id;
					$invoiceHdr->customer_gst_tax_slab_type_id = $orders->customer_gst_tax_slab_type_id;
					$invoiceHdr->customer_id            = $orders->customer_id;
					$invoiceHdr->customer_invoicing_id  = !empty($orders->invoicing_to) ? $orders->invoicing_to : $orders->customer_id;
					$invoiceHdr->total_amount           = array_sum($totalAmount);
					$invoiceHdr->total_discount         = array_sum($totalDiscount);
					$invoiceHdr->surcharge_amount       = array_sum($totalSurchargeAmount);
					$invoiceHdr->extra_amount           = array_sum($totalExtraAmount);
					$invoiceHdr->net_amount             = $invoiceHdr->surcharge_amount + $invoiceHdr->extra_amount + ($invoiceHdr->total_amount - $invoiceHdr->total_discount);
					if ($orders->customer_gst_tax_slab_type_id == '1') {		//Tax Slab according to Global Tax SLab Setting
						if ($orders->customer_gst_category_id != '4') {         //For Customer GST Category is not equal to SEZ
							if ($orders->division_state == $orders->customer_state) {         //For SGST and CGST Taxing
								$invoiceHdr->sgst_rate          = $SGST;
								$invoiceHdr->sgst_amount        = ($invoiceHdr->net_amount * $SGST) / 100;
								$invoiceHdr->cgst_rate          = $CGST;
								$invoiceHdr->cgst_amount        = ($invoiceHdr->net_amount * $CGST) / 100;
								$invoiceHdr->net_total_amount   = $invoiceHdr->net_amount + $invoiceHdr->sgst_amount + $invoiceHdr->cgst_amount;
							} else {                                                  	//For IGST Taxing
								$invoiceHdr->igst_rate          = $IGST;
								$invoiceHdr->igst_amount        = ($invoiceHdr->net_amount * $IGST) / 100;
								$invoiceHdr->net_total_amount   = $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
							}
						} else {
							$invoiceHdr->igst_rate          = $IGST;
							$invoiceHdr->igst_amount        = ($invoiceHdr->net_amount * $IGST) / 100;
							$invoiceHdr->net_total_amount   = $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
						}
					} else if ($orders->customer_gst_tax_slab_type_id == '2') {
						$invoiceHdr->net_total_amount = $invoiceHdr->net_amount;
					}
					$invoiceHdr->created_by    = USERID;
				}
				if (!empty($invoiceHdrData) && !empty($invoiceHdr->save()) && !empty($invoiceHdr->invoice_id)) {
					foreach ($invoiceHdrData as $key => $orderValue) {
						$invoiceHdrDetail                   	= new InvoiceHdrDetail();
						$invoiceHdrDetail->invoice_hdr_status 	= '1';
						$invoiceHdrDetail->invoice_hdr_id   	= $invoiceHdr->invoice_id;
						$invoiceHdrDetail->order_id         	= $orderValue->order_id;
						$invoiceHdrDetail->order_invoicing_to   = $orderValue->invoicing_to;
						$invoiceHdrDetail->order_amount     	= $orderValue->order_amount;
						$invoiceHdrDetail->order_discount   	= $orderValue->order_discount;
						$invoiceHdrDetail->extra_amount     	= $orderValue->extra_amount;
						$invoiceHdrDetail->surcharge_amount 	= $orderValue->surcharge_value;
						$invoiceHdrDetail->order_total_amount 	= $invoiceHdrDetail->extra_amount + $invoiceHdrDetail->surcharge_amount + ($invoiceHdrDetail->order_amount - $invoiceHdrDetail->order_discount);
						if ($orderValue->customer_gst_tax_slab_type_id == '1') {	       //Tax Slab according to Global Tax SLab Setting
							if ($orderValue->customer_gst_category_id != '4') {         //For Customer GST Category is not equal to SEZ
								if ($orderValue->division_state == $orderValue->customer_state) {         //For SGST and CGST Taxing
									$invoiceHdrDetail->order_sgst_rate    = $SGST;
									$invoiceHdrDetail->order_sgst_amount  = ($invoiceHdrDetail->order_total_amount * $SGST) / 100;
									$invoiceHdrDetail->order_cgst_rate    = $CGST;
									$invoiceHdrDetail->order_cgst_amount  = ($invoiceHdrDetail->order_total_amount * $CGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_sgst_amount + $invoiceHdrDetail->order_cgst_amount;
								} else {                                                  		//For IGST Taxing
									$invoiceHdrDetail->order_igst_rate    = $IGST;
									$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
								}
							} else {
								$invoiceHdrDetail->order_igst_rate    = $IGST;
								$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
								$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
							}
						} else if ($orderValue->customer_gst_tax_slab_type_id == '2') {
							$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount;
						}
						$invoiceHdrDetail->save();
						if (!empty($invoiceHdrDetail->invoice_dtl_id)) {
							$order->updateOrderStausLog($invoiceHdrDetail->order_id, '8');	//Manage order process log
							if (!empty($invoice->orderDispatchedStatus($invoiceHdrDetail->order_id))) {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '11');
							} else {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '9');
							}
							$invoiceHdrDetailInfo[$invoiceHdrDetail->order_id] = $invoiceHdrDetail->order_id;
						}
					}
					if (!empty($invoiceHdrDetailInfo)) {
						!empty($invoceSessionStatusArr) ? $invoice->__updateInvoiceNewSessionDtl($invoceSessionStatusArr) : '';	//Updating Invoice Session Detail		
						$flag[] = $invoiceHdr->invoice_id;
					}
				}
			}
		} catch (\Exception $e) {
			return false;
		}

		return $flag;
	}

	/**********************************************
	 * Description : Generate Invoicer Regular
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 * Modifed On  : 03-Sept-2020
	 **********************************************/
	public function generateInvoiceRegular($arguments)
	{

		global $order, $models, $invoice;

		$flag = $invoiceInfo = $invoiceHdrData = $invoiceHdrDetailInfo = $totalAmount = $totalDiscount = $invoceSessionStatusArr = array();

		try {

			//Converting arrays keys into variables
			extract($arguments);

			$orderInvoicesObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('division_parameters', 'division_parameters.division_id', 'divisions.division_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->select('order_master.order_id', 'order_master.order_no', 'order_master.order_date', 'order_master.product_category_id', 'order_master.customer_id', 'order_master.division_id', 'order_master.discount_type_id', 'order_master.discount_value', 'order_master.surcharge_value', 'order_master.extra_amount', 'division_parameters.division_state', 'customer_master.customer_state', 'customer_master.customer_gst_tax_slab_type_id', 'customer_master.customer_gst_category_id', 'customer_master.customer_gst_type_id', 'order_master.invoicing_to', 'order_master.billing_type_id')
				->where('order_master.customer_id', $customer_id)
				->where('order_master.billing_type_id', '=', $billing_type_id)
				->where('order_master.division_id', $division_id)
				->whereIn('order_master.order_id', $order_ids)
				->where('order_master.status', '8');                 //completed for report and ready for invoice generation

			if (!empty($product_category_id) && is_numeric($product_category_id)) {
				$orderInvoicesObj->where('order_master.product_category_id', $product_category_id);
			}
			$orderInvoicesData = $orderInvoicesObj->orderBy('order_master.order_date', 'DESC')->get();

			if (!empty($orderInvoicesData)) {
				foreach ($orderInvoicesData as $key => $orders) {
					$invoice->updateCustomerStateDefinedInInvoicingToColumn($orders); //Updating Customer State According to Invocing-To Defined at the time of Order Booking
					list($perTotalAmount, $perTotalDiscount) = $invoice->getOrderInvoivingPrice($orders->customer_id, $orders->order_id, $orders->discount_type_id, $orders->discount_value);
					$invoceSessionStatusArr[$orders->division_id . '-' . $orders->product_category_id] = $invoice->__getInvoiceNewSessionDtl($currentDate, $orders->division_id, $orders->product_category_id);
					$orders->order_amount           	 	= $perTotalAmount;
					$orders->order_discount         	 	= $perTotalDiscount;
					$invoiceHdr                    	 	= new invoiceHdr();
					$invoiceHdr->division_id        	 	= $orders->division_id;
					$invoiceHdr->product_category_id   	 	= $orders->product_category_id;
					$invoiceHdr->invoice_no         	 	= $invoice->generateInvoiceNumber($currentDate, $orders->product_category_id, $orders->division_id);
					$invoiceHdr->invoice_date       	 	= $currentDate;
					$invoiceHdr->inv_fin_yr_id 		    	= $models->getInvoiceFinancialYear($currentDate);
					$invoiceHdr->invoice_type       	 	= $orders->billing_type_id;
					$invoiceHdr->customer_gst_tax_slab_type_id 	= $orders->customer_gst_tax_slab_type_id;
					$invoiceHdr->customer_id        	 	= $orders->customer_id;
					$invoiceHdr->customer_invoicing_id   	= !empty($orders->invoicing_to) ? $orders->invoicing_to : $orders->customer_id;
					$invoiceHdr->total_amount       	 	= $perTotalAmount;
					$invoiceHdr->total_discount     	 	= $perTotalDiscount;
					$invoiceHdr->surcharge_amount   	 	= !empty($orders->surcharge_value) ? $orders->surcharge_value : '0.00';
					$invoiceHdr->extra_amount       	 	= !empty($orders->extra_amount) ? $orders->extra_amount : '0.00';
					$invoiceHdr->net_amount         	 	= $invoiceHdr->surcharge_amount + $invoiceHdr->extra_amount + ($invoiceHdr->total_amount - $invoiceHdr->total_discount);
					if ($orders->customer_gst_tax_slab_type_id == '1') {				//Tax Slab according to Global Tax Slab Setting
						if ($orders->customer_gst_category_id != '4') {         			//For Customer GST Category is not equal to SEZ
							if ($orders->division_state == $orders->customer_state) {         	//For SGST and CGST Taxing
								$invoiceHdr->sgst_rate         	 = $SGST;
								$invoiceHdr->sgst_amount       	 = ($invoiceHdr->net_amount * $SGST) / 100;
								$invoiceHdr->cgst_rate         	 = $CGST;
								$invoiceHdr->cgst_amount       	 = ($invoiceHdr->net_amount * $CGST) / 100;
								$invoiceHdr->net_total_amount  	 = $invoiceHdr->net_amount + $invoiceHdr->sgst_amount + $invoiceHdr->cgst_amount;
							} else {                                                  		//For IGST Taxing
								$invoiceHdr->igst_rate         	 = $IGST;
								$invoiceHdr->igst_amount       	 = ($invoiceHdr->net_amount * $IGST) / 100;
								$invoiceHdr->net_total_amount  	 = $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
							}
						} else {                                                  		//For IGST Taxing
							$invoiceHdr->igst_rate         	 = $IGST;
							$invoiceHdr->igst_amount       	 = ($invoiceHdr->net_amount * $IGST) / 100;
							$invoiceHdr->net_total_amount  	 = $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
						}
					} else if ($orders->customer_gst_tax_slab_type_id == '2') {
						$invoiceHdr->net_total_amount  	 = $invoiceHdr->net_amount;
					}
					$invoiceHdr->created_by    = USERID;
					if (!empty($invoiceHdr->save()) && !empty($invoiceHdr->invoice_id)) {
						$invoiceHdrDetail                   	= new InvoiceHdrDetail();
						$invoiceHdrDetail->invoice_hdr_status 	= '1';
						$invoiceHdrDetail->invoice_hdr_id   	= $invoiceHdr->invoice_id;
						$invoiceHdrDetail->order_id         	= $orders->order_id;
						$invoiceHdrDetail->order_invoicing_to   = $orders->invoicing_to;
						$invoiceHdrDetail->order_amount     	= $orders->order_amount;
						$invoiceHdrDetail->order_discount   	= $orders->order_discount;
						$invoiceHdrDetail->extra_amount     	= $orders->extra_amount;
						$invoiceHdrDetail->surcharge_amount 	= $orders->surcharge_value;
						$invoiceHdrDetail->order_total_amount 	= $invoiceHdrDetail->extra_amount + $invoiceHdrDetail->surcharge_amount + ($invoiceHdrDetail->order_amount - $invoiceHdrDetail->order_discount);
						if ($orders->customer_gst_tax_slab_type_id == '1') {					//Tax Slab according to Global Tax Slab Setting
							if ($orders->customer_gst_category_id != '4') {         				//For Customer GST Category is not equal to SEZ
								if ($orders->division_state == $orders->customer_state) {         		//For SGST and CGST Taxing
									$invoiceHdrDetail->order_sgst_rate    = $SGST;
									$invoiceHdrDetail->order_sgst_amount  = ($invoiceHdrDetail->order_total_amount * $SGST) / 100;
									$invoiceHdrDetail->order_cgst_rate    = $CGST;
									$invoiceHdrDetail->order_cgst_amount  = ($invoiceHdrDetail->order_total_amount * $CGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_sgst_amount + $invoiceHdrDetail->order_cgst_amount;
								} else {                                                  			//For IGST Taxing
									$invoiceHdrDetail->order_igst_rate    = $IGST;
									$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
								}
							} else {                                                  				//For IGST Taxing
								$invoiceHdrDetail->order_igst_rate    = $IGST;
								$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
								$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
							}
						} else if ($orders->customer_gst_tax_slab_type_id == '2') {
							$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount;
						}
						$invoiceHdrDetail->save();
						if (!empty($invoiceHdrDetail->invoice_dtl_id)) {
							$order->updateOrderStausLog($invoiceHdrDetail->order_id, '8');	//Manage order process log
							if (!empty($invoice->orderDispatchedStatus($invoiceHdrDetail->order_id))) {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '11');
							} else {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '9');
							}
							!empty($invoceSessionStatusArr) ? $invoice->__updateInvoiceNewSessionDtl($invoceSessionStatusArr) : ''; //Updating Invoice Session Detail
							$flag[] = $invoiceHdr->invoice_id;
						}
					}
				}
			}
		} catch (\Exception $e) {
			return false;
		}

		return $flag;
	}

	/**********************************************
	 * Description : Generate Invoicer Weekly
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 * Modifed On  : 03-Sept-2020
	 **********************************************/
	public function generateInvoiceWeekly($arguments)
	{

		global $order, $models, $invoice;

		$flag = $invoiceInfo = $invoiceHdrData = $invoiceHdrDetailInfo = $totalAmount = $totalDiscount = $invoceSessionStatusArr = array();

		try {

			//Converting arrays keys into variables
			extract($arguments);

			$orderInvoicesObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('division_parameters', 'division_parameters.division_id', 'divisions.division_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->select('order_master.order_id', 'order_master.order_no', 'order_master.order_date', 'order_master.product_category_id', 'order_master.customer_id', 'order_master.division_id', 'order_master.discount_type_id', 'order_master.discount_value', 'order_master.surcharge_value', 'order_master.extra_amount', 'division_parameters.division_state', 'customer_master.customer_state', 'customer_master.customer_gst_tax_slab_type_id', 'customer_master.customer_gst_category_id', 'customer_master.customer_gst_type_id', 'order_master.invoicing_to', 'order_master.billing_type_id')
				->where('order_master.customer_id', $customer_id)
				->where('order_master.billing_type_id', '=', $billing_type_id)
				->where('order_master.division_id', $division_id)
				->whereIn('order_master.order_id', $order_ids)
				->where('order_master.status', '8');    //completed for report and ready for invoice generation

			if (!empty($product_category_id) && is_numeric($product_category_id)) {
				$orderInvoicesObj->where('order_master.product_category_id', $product_category_id);
			}
			$orderInvoicesData = $orderInvoicesObj->orderBy('order_master.order_date', 'DESC')->get();

			if (!empty($orderInvoicesData)) {
				foreach ($orderInvoicesData as $key => $orders) {
					$invoice->updateCustomerStateDefinedInInvoicingToColumn($orders); //Updating Customer State According to Invocing-To Defined at the time of Order Booking
					list($perTotalAmount, $perTotalDiscount) = $invoice->getOrderInvoivingPrice($orders->customer_id, $orders->order_id, $orders->discount_type_id, $orders->discount_value);
					$orders->order_amount                    = $perTotalAmount;
					$orders->order_discount                  = $perTotalDiscount;
					$invoiceHdrData[$orders->order_id]       = $orders;
					$totalAmount[$key]                       = $perTotalAmount;
					$totalDiscount[$key]                     = $perTotalDiscount;
					$totalSurchargeAmount[$key]              = $orders->surcharge_value;
					$totalExtraAmount[$key]                  = $orders->extra_amount;
					$invoceSessionStatusArr[$orders->division_id . '-' . $orders->product_category_id] = $invoice->__getInvoiceNewSessionDtl($currentDate, $orders->division_id, $orders->product_category_id);
					$invoiceHdr                              = new invoiceHdr();
					$invoiceHdr->division_id                 = $orders->division_id;
					$invoiceHdr->product_category_id         = $orders->product_category_id;
					$invoiceHdr->invoice_no                  = $invoice->generateInvoiceNumber($currentDate, $orders->product_category_id, $orders->division_id);
					$invoiceHdr->invoice_date                = $currentDate;
					$invoiceHdr->inv_fin_yr_id 		     = $models->getInvoiceFinancialYear($currentDate);
					$invoiceHdr->invoice_type                = $orders->billing_type_id;
					$invoiceHdr->customer_gst_tax_slab_type_id = $orders->customer_gst_tax_slab_type_id;
					$invoiceHdr->customer_id                 = $orders->customer_id;
					$invoiceHdr->customer_invoicing_id       = !empty($orders->invoicing_to) ? $orders->invoicing_to : $orders->customer_id;
					$invoiceHdr->total_amount                = array_sum($totalAmount);
					$invoiceHdr->total_discount              = array_sum($totalDiscount);
					$invoiceHdr->surcharge_amount            = array_sum($totalSurchargeAmount);
					$invoiceHdr->extra_amount                = array_sum($totalExtraAmount);
					$invoiceHdr->net_amount                  = $invoiceHdr->surcharge_amount + $invoiceHdr->extra_amount + ($invoiceHdr->total_amount - $invoiceHdr->total_discount);
					if ($orders->customer_gst_tax_slab_type_id == '1') {					//Tax Slab according to Global Tax Slab Setting
						if ($orders->customer_gst_category_id != '4') {         				//For Customer GST Category is not equal to SEZ
							if ($orders->division_state == $orders->customer_state) {         		//For SGST and CGST Taxing
								$invoiceHdr->sgst_rate        	 = $SGST;
								$invoiceHdr->sgst_amount      	 = ($invoiceHdr->net_amount * $SGST) / 100;
								$invoiceHdr->cgst_rate        	 = $CGST;
								$invoiceHdr->cgst_amount      	 = ($invoiceHdr->net_amount * $CGST) / 100;
								$invoiceHdr->net_total_amount 	 = $invoiceHdr->net_amount + $invoiceHdr->sgst_amount + $invoiceHdr->cgst_amount;
							} else {                                                  			//For IGST Taxing
								$invoiceHdr->igst_rate        	 = $IGST;
								$invoiceHdr->igst_amount      	 = ($invoiceHdr->net_amount * $IGST) / 100;
								$invoiceHdr->net_total_amount 	 = $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
							}
						} else {                                                  			//For IGST Taxing
							$invoiceHdr->igst_rate        	 = $IGST;
							$invoiceHdr->igst_amount      	 = ($invoiceHdr->net_amount * $IGST) / 100;
							$invoiceHdr->net_total_amount 	 = $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
						}
					} else if ($orders->customer_gst_tax_slab_type_id == '2') {
						$invoiceHdr->net_total_amount = $invoiceHdr->net_amount;
					}
					$invoiceHdr->created_by    = USERID;
				}
				if (!empty($invoiceHdrData) && !empty($invoiceHdr->save()) && !empty($invoiceHdr->invoice_id)) {
					foreach ($invoiceHdrData as $key => $orderValue) {
						$invoiceHdrDetail                   	= new InvoiceHdrDetail();
						$invoiceHdrDetail->invoice_hdr_status 	= '1';
						$invoiceHdrDetail->invoice_hdr_id   	= $invoiceHdr->invoice_id;
						$invoiceHdrDetail->order_id         	= $orderValue->order_id;
						$invoiceHdrDetail->order_invoicing_to   = $orderValue->invoicing_to;
						$invoiceHdrDetail->order_amount     	= $orderValue->order_amount;
						$invoiceHdrDetail->order_discount   	= $orderValue->order_discount;
						$invoiceHdrDetail->extra_amount     	= $orderValue->extra_amount;
						$invoiceHdrDetail->surcharge_amount 	= $orderValue->surcharge_value;
						$invoiceHdrDetail->order_total_amount 	= $invoiceHdrDetail->extra_amount + $invoiceHdrDetail->surcharge_amount + ($invoiceHdrDetail->order_amount - $invoiceHdrDetail->order_discount);
						if ($orderValue->customer_gst_tax_slab_type_id == '1') {					//Tax Slab according to Global Tax Slab Setting
							if ($orderValue->customer_gst_category_id != '4') {         				//For Customer GST Category is not equal to SEZ
								if ($orderValue->division_state == $orderValue->customer_state) {         	//For SGST and CGST Taxing
									$invoiceHdrDetail->order_sgst_rate    = $SGST;
									$invoiceHdrDetail->order_sgst_amount  = ($invoiceHdrDetail->order_total_amount * $SGST) / 100;
									$invoiceHdrDetail->order_cgst_rate    = $CGST;
									$invoiceHdrDetail->order_cgst_amount  = ($invoiceHdrDetail->order_total_amount * $CGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_sgst_amount + $invoiceHdrDetail->order_cgst_amount;
								} else {                                                  			//For IGST Taxing
									$invoiceHdrDetail->order_igst_rate    = $IGST;
									$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
								}
							} else {                                                  				//For IGST Taxing
								$invoiceHdrDetail->order_igst_rate    = $IGST;
								$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
								$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
							}
						} else if ($orderValue->customer_gst_tax_slab_type_id == '2') {
							$invoiceHdrDetail->order_net_amount = $invoiceHdrDetail->order_total_amount;
						}
						$invoiceHdrDetail->save();
						if (!empty($invoiceHdrDetail->invoice_dtl_id)) {
							$order->updateOrderStausLog($invoiceHdrDetail->order_id, '8');	//Manage order process log
							if (!empty($invoice->orderDispatchedStatus($invoiceHdrDetail->order_id))) {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '11');
							} else {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '9');
							}
							$invoiceHdrDetailInfo[$invoiceHdrDetail->order_id] = $invoiceHdrDetail->order_id;
						}
					}
					if (!empty($invoiceHdrDetailInfo)) {
						!empty($invoceSessionStatusArr) ? $invoice->__updateInvoiceNewSessionDtl($invoceSessionStatusArr) : ''; //Updating Invoice Session Detail
						$flag[] = $invoiceHdr->invoice_id;
					}
				}
			}
		} catch (\Exception $e) {
			return false;
		}

		return $flag;
	}

	/**********************************************
	 * Description : Generate Invoicer Monthly
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 * Modifed On  : 03-Sept-2020
	 **********************************************/
	public function generateInvoiceMonthly($arguments)
	{

		global $order, $models, $invoice;

		$flag = $invoiceInfo = $invoiceHdrData = $invoiceHdrDetailInfo = $totalAmount = $totalDiscount = $invoceSessionStatusArr = array();

		try {

			//Converting arrays keys into variables
			extract($arguments);

			$orderInvoicesObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('division_parameters', 'division_parameters.division_id', 'divisions.division_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->select('order_master.order_id', 'order_master.order_no', 'order_master.order_date', 'order_master.product_category_id', 'order_master.customer_id', 'order_master.division_id', 'order_master.discount_type_id', 'order_master.discount_value', 'order_master.surcharge_value', 'order_master.extra_amount', 'division_parameters.division_state', 'customer_master.customer_state', 'customer_master.customer_gst_tax_slab_type_id', 'customer_master.customer_gst_category_id', 'customer_master.customer_gst_type_id', 'order_master.invoicing_to', 'order_master.billing_type_id')
				->where('order_master.customer_id', $customer_id)
				->where('order_master.billing_type_id', '=', $billing_type_id)
				->where('order_master.division_id', $division_id)
				->whereIn('order_master.order_id', $order_ids)
				->where('order_master.status', '8');   //completed for report and ready for invoice generation

			if (!empty($product_category_id) && is_numeric($product_category_id)) {
				$orderInvoicesObj->where('order_master.product_category_id', $product_category_id);
			}
			$orderInvoicesData = $orderInvoicesObj->orderBy('order_master.order_date', 'DESC')->get();

			if (!empty($orderInvoicesData)) {
				foreach ($orderInvoicesData as $key => $orders) {
					$invoice->updateCustomerStateDefinedInInvoicingToColumn($orders); //Updating Customer State According to Invocing-To Defined at the time of Order Booking
					list($perTotalAmount, $perTotalDiscount) = $invoice->getOrderInvoivingPrice($orders->customer_id, $orders->order_id, $orders->discount_type_id, $orders->discount_value);
					$orders->order_amount                   = $perTotalAmount;
					$orders->order_discount                 = $perTotalDiscount;
					$invoiceHdrData[$orders->order_id]      = $orders;
					$totalAmount[$key]                      = $perTotalAmount;
					$totalDiscount[$key]                    = $perTotalDiscount;
					$totalSurchargeAmount[$key]             = $orders->surcharge_value;
					$totalExtraAmount[$key]                 = $orders->extra_amount;
					$invoceSessionStatusArr[$orders->division_id . '-' . $orders->product_category_id] = $invoice->__getInvoiceNewSessionDtl($currentDate, $orders->division_id, $orders->product_category_id);
					$invoiceHdr                             = new invoiceHdr();
					$invoiceHdr->division_id                = $orders->division_id;
					$invoiceHdr->product_category_id        = $orders->product_category_id;
					$invoiceHdr->invoice_no                 = $invoice->generateInvoiceNumber($currentDate, $orders->product_category_id, $orders->division_id);
					$invoiceHdr->invoice_date               = $currentDate;
					$invoiceHdr->inv_fin_yr_id 		    = $models->getInvoiceFinancialYear($currentDate);
					$invoiceHdr->invoice_type               = $orders->billing_type_id;
					$invoiceHdr->customer_gst_tax_slab_type_id = $orders->customer_gst_tax_slab_type_id;
					$invoiceHdr->customer_id                = $orders->customer_id;
					$invoiceHdr->customer_invoicing_id      = !empty($orders->invoicing_to) ? $orders->invoicing_to : $orders->customer_id;
					$invoiceHdr->total_amount               = array_sum($totalAmount);
					$invoiceHdr->total_discount             = array_sum($totalDiscount);
					$invoiceHdr->surcharge_amount           = array_sum($totalSurchargeAmount);
					$invoiceHdr->extra_amount               = array_sum($totalExtraAmount);
					$invoiceHdr->net_amount                 = $invoiceHdr->surcharge_amount + $invoiceHdr->extra_amount + ($invoiceHdr->total_amount - $invoiceHdr->total_discount);
					if ($orders->customer_gst_tax_slab_type_id == '1') {					//Tax Slab according to Global Tax Slab Setting
						if ($orders->customer_gst_category_id != '4') {         				//For Customer GST Category is not equal to SEZ
							if ($orders->division_state == $orders->customer_state) {         		//For SGST and CGST Taxing
								$invoiceHdr->sgst_rate        	= $SGST;
								$invoiceHdr->sgst_amount      	= ($invoiceHdr->net_amount * $SGST) / 100;
								$invoiceHdr->cgst_rate        	= $CGST;
								$invoiceHdr->cgst_amount      	= ($invoiceHdr->net_amount * $CGST) / 100;
								$invoiceHdr->net_total_amount 	= $invoiceHdr->net_amount + $invoiceHdr->sgst_amount + $invoiceHdr->cgst_amount;
							} else {                                                  			//For IGST Taxing
								$invoiceHdr->igst_rate        	= $IGST;
								$invoiceHdr->igst_amount      	= ($invoiceHdr->net_amount * $IGST) / 100;
								$invoiceHdr->net_total_amount 	= $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
							}
						} else {                                                  			//For IGST Taxing
							$invoiceHdr->igst_rate        	= $IGST;
							$invoiceHdr->igst_amount      	= ($invoiceHdr->net_amount * $IGST) / 100;
							$invoiceHdr->net_total_amount 	= $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
						}
					} else if ($orders->customer_gst_tax_slab_type_id == '2') {
						$invoiceHdr->net_total_amount 	= $invoiceHdr->net_amount;
					}
					$invoiceHdr->created_by    = USERID;
				}
				if (!empty($invoiceHdrData) && !empty($invoiceHdr->save()) && !empty($invoiceHdr->invoice_id)) {
					foreach ($invoiceHdrData as $key => $orderValue) {
						$invoiceHdrDetail                   	= new InvoiceHdrDetail();
						$invoiceHdrDetail->invoice_hdr_status 	= '1';
						$invoiceHdrDetail->invoice_hdr_id   	= $invoiceHdr->invoice_id;
						$invoiceHdrDetail->order_id         	= $orderValue->order_id;
						$invoiceHdrDetail->order_invoicing_to   = $orderValue->invoicing_to;
						$invoiceHdrDetail->order_amount     	= $orderValue->order_amount;
						$invoiceHdrDetail->order_discount   	= $orderValue->order_discount;
						$invoiceHdrDetail->extra_amount     	= $orderValue->extra_amount;
						$invoiceHdrDetail->surcharge_amount 	= $orderValue->surcharge_value;
						$invoiceHdrDetail->order_total_amount 	= $invoiceHdrDetail->extra_amount + $invoiceHdrDetail->surcharge_amount + ($invoiceHdrDetail->order_amount - $invoiceHdrDetail->order_discount);
						if ($orderValue->customer_gst_tax_slab_type_id == '1') {					//Tax Slab according to Global Tax Slab Setting
							if ($orderValue->customer_gst_category_id != '4') {         				//For Customer GST Category is not equal to SEZ
								if ($orderValue->division_state == $orderValue->customer_state) {         	//For SGST and CGST Taxing
									$invoiceHdrDetail->order_sgst_rate    = $SGST;
									$invoiceHdrDetail->order_sgst_amount  = ($invoiceHdrDetail->order_total_amount * $SGST) / 100;
									$invoiceHdrDetail->order_cgst_rate    = $CGST;
									$invoiceHdrDetail->order_cgst_amount  = ($invoiceHdrDetail->order_total_amount * $CGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_sgst_amount + $invoiceHdrDetail->order_cgst_amount;
								} else {                                                  			//For IGST Taxing
									$invoiceHdrDetail->order_igst_rate    = $IGST;
									$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
								}
							} else {                                                  				//For IGST Taxing
								$invoiceHdrDetail->order_igst_rate    = $IGST;
								$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
								$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
							}
						} else if ($orderValue->customer_gst_tax_slab_type_id == '2') {
							$invoiceHdrDetail->order_net_amount = $invoiceHdrDetail->order_total_amount;
						}
						$invoiceHdrDetail->save();
						if (!empty($invoiceHdrDetail->invoice_dtl_id)) {
							$order->updateOrderStausLog($invoiceHdrDetail->order_id, '8');	//Manage order process log
							if (!empty($invoice->orderDispatchedStatus($invoiceHdrDetail->order_id))) {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '11');
							} else {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '9');
							}
							$invoiceHdrDetailInfo[$invoiceHdrDetail->order_id] = $invoiceHdrDetail->order_id;
						}
					}
					if (!empty($invoiceHdrDetailInfo)) {
						!empty($invoceSessionStatusArr) ? $invoice->__updateInvoiceNewSessionDtl($invoceSessionStatusArr) : ''; //Updating Invoice Session Detail
						$flag[] = $invoiceHdr->invoice_id;
					}
				}
			}
		} catch (\Exception $e) {
			return false;
		}

		return $flag;
	}

	/**********************************************
	 * Description : Generate Invoicer PO Wise
	 * Date 	  : 26-July-2018
	 * Author      : Praveen Singh
	 * Modifed On  : 04-Sept-2020
	 **********************************************/
	public function generateInvoicePOWise($arguments)
	{

		global $order, $models, $invoice;

		$flag = $invoiceInfo = $invoiceHdrData = $invoiceHdrDetailInfo = $totalAmount = $totalDiscount = $invoceSessionStatusArr = array();

		try {

			//Converting arrays keys into variables
			extract($arguments);

			$orderInvoicesObj = DB::table('order_master')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('division_parameters', 'division_parameters.division_id', 'divisions.division_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->select('order_master.order_id', 'order_master.order_no', 'order_master.po_no', 'order_master.po_date', 'order_master.order_date', 'order_master.product_category_id', 'order_master.customer_id', 'order_master.division_id', 'order_master.discount_type_id', 'order_master.discount_value', 'order_master.surcharge_value', 'order_master.extra_amount', 'division_parameters.division_state', 'customer_master.customer_state', 'customer_master.customer_gst_tax_slab_type_id', 'customer_master.customer_gst_category_id', 'customer_master.customer_gst_type_id', 'order_master.invoicing_to', 'order_master.billing_type_id')
				->where('order_master.customer_id', $customer_id)
				->where('order_master.billing_type_id', '=', $billing_type_id)
				->where('order_master.division_id', $division_id)
				->whereIn('order_master.order_id', $order_ids)
				->whereNotNull('order_master.po_no')
				->where('order_master.status', '8');    //completed for report and ready for invoice generation

			if (!empty($product_category_id) && is_numeric($product_category_id)) {
				$orderInvoicesObj->where('order_master.product_category_id', $product_category_id);
			}
			$orderInvoicesData = $orderInvoicesObj->orderBy('order_master.order_date', 'DESC')->get();

			if (!empty($orderInvoicesData)) {
				foreach ($orderInvoicesData as $key => $orders) {
					$invoice->updateCustomerStateDefinedInInvoicingToColumn($orders); //Updating Customer State According to Invocing-To Defined at the time of Order Booking
					list($perTotalAmount, $perTotalDiscount) = $invoice->getOrderInvoivingPrice($orders->customer_id, $orders->order_id, $orders->discount_type_id, $orders->discount_value);
					$orders->order_amount               = $perTotalAmount;
					$orders->order_discount             = $perTotalDiscount;
					$invoiceHdrData[$orders->order_id]  = $orders;
					$totalAmount[$key]                  = $perTotalAmount;
					$totalDiscount[$key]                = $perTotalDiscount;
					$totalSurchargeAmount[$key]         = $orders->surcharge_value;
					$totalExtraAmount[$key]             = $orders->extra_amount;
					$invoceSessionStatusArr[$orders->division_id . '-' . $orders->product_category_id] = $invoice->__getInvoiceNewSessionDtl($currentDate, $orders->division_id, $orders->product_category_id);
					$invoiceHdr                         = new invoiceHdr();
					$invoiceHdr->division_id            = $orders->division_id;
					$invoiceHdr->product_category_id    = $orders->product_category_id;
					$invoiceHdr->invoice_no             = $invoice->generateInvoiceNumber($currentDate, $orders->product_category_id, $orders->division_id);
					$invoiceHdr->invoice_date           = $currentDate;
					$invoiceHdr->inv_fin_yr_id 		= $models->getInvoiceFinancialYear($currentDate);
					$invoiceHdr->invoice_type           = $orders->billing_type_id;
					$invoiceHdr->customer_gst_tax_slab_type_id = $orders->customer_gst_tax_slab_type_id;
					$invoiceHdr->customer_id            = $orders->customer_id;
					$invoiceHdr->customer_invoicing_id  = !empty($orders->invoicing_to) ? $orders->invoicing_to : $orders->customer_id;
					$invoiceHdr->total_amount           = array_sum($totalAmount);
					$invoiceHdr->total_discount         = array_sum($totalDiscount);
					$invoiceHdr->surcharge_amount       = array_sum($totalSurchargeAmount);
					$invoiceHdr->extra_amount           = array_sum($totalExtraAmount);
					$invoiceHdr->net_amount             = $invoiceHdr->surcharge_amount + $invoiceHdr->extra_amount + ($invoiceHdr->total_amount - $invoiceHdr->total_discount);
					if ($orders->customer_gst_tax_slab_type_id == '1') {					//Tax Slab according to Global Tax Slab Setting
						if ($orders->customer_gst_category_id != '4') {         				//For Customer GST Category is not equal to SEZ
							if ($orders->division_state == $orders->customer_state) {         		//For SGST and CGST Taxing
								$invoiceHdr->sgst_rate          = $SGST;
								$invoiceHdr->sgst_amount        = ($invoiceHdr->net_amount * $SGST) / 100;
								$invoiceHdr->cgst_rate          = $CGST;
								$invoiceHdr->cgst_amount        = ($invoiceHdr->net_amount * $CGST) / 100;
								$invoiceHdr->net_total_amount   = $invoiceHdr->net_amount + $invoiceHdr->sgst_amount + $invoiceHdr->cgst_amount;
							} else {                                                  			//For IGST Taxing
								$invoiceHdr->igst_rate          = $IGST;
								$invoiceHdr->igst_amount        = ($invoiceHdr->net_amount * $IGST) / 100;
								$invoiceHdr->net_total_amount   = $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
							}
						} else {                                                  			//For IGST Taxing
							$invoiceHdr->igst_rate          = $IGST;
							$invoiceHdr->igst_amount        = ($invoiceHdr->net_amount * $IGST) / 100;
							$invoiceHdr->net_total_amount   = $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
						}
					} else if ($orders->customer_gst_tax_slab_type_id == '2') {
						$invoiceHdr->net_total_amount   = $invoiceHdr->net_amount;
					}
					$invoiceHdr->created_by             = USERID;
				}
				if (!empty($invoiceHdrData) && !empty($invoiceHdr->save()) && !empty($invoiceHdr->invoice_id)) {
					foreach ($invoiceHdrData as $key => $orderValue) {
						$invoiceHdrDetail                   	= new InvoiceHdrDetail();
						$invoiceHdrDetail->invoice_hdr_status 	= '1';
						$invoiceHdrDetail->invoice_hdr_id   	= $invoiceHdr->invoice_id;
						$invoiceHdrDetail->order_id         	= $orderValue->order_id;
						$invoiceHdrDetail->order_invoicing_to   = $orderValue->invoicing_to;
						$invoiceHdrDetail->order_amount     	= $orderValue->order_amount;
						$invoiceHdrDetail->order_discount   	= $orderValue->order_discount;
						$invoiceHdrDetail->extra_amount     	= $orderValue->extra_amount;
						$invoiceHdrDetail->surcharge_amount 	= $orderValue->surcharge_value;
						$invoiceHdrDetail->order_total_amount 	= $invoiceHdrDetail->extra_amount + $invoiceHdrDetail->surcharge_amount + ($invoiceHdrDetail->order_amount - $invoiceHdrDetail->order_discount);
						if ($orderValue->customer_gst_tax_slab_type_id == '1') {					//Tax Slab according to Global Tax Slab Setting
							if ($orderValue->customer_gst_category_id != '4') {         				//For Customer GST Category is not equal to SEZ
								if ($orderValue->division_state == $orderValue->customer_state) {         	//For SGST and CGST Taxing
									$invoiceHdrDetail->order_sgst_rate    = $SGST;
									$invoiceHdrDetail->order_sgst_amount  = ($invoiceHdrDetail->order_total_amount * $SGST) / 100;
									$invoiceHdrDetail->order_cgst_rate    = $CGST;
									$invoiceHdrDetail->order_cgst_amount  = ($invoiceHdrDetail->order_total_amount * $CGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_sgst_amount + $invoiceHdrDetail->order_cgst_amount;
								} else {                                                  			//For IGST Taxing
									$invoiceHdrDetail->order_igst_rate    = $IGST;
									$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
									$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
								}
							} else {                                                  				//For IGST Taxing
								$invoiceHdrDetail->order_igst_rate    = $IGST;
								$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
								$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
							}
						} else if ($orderValue->customer_gst_tax_slab_type_id == '2') {
							$invoiceHdrDetail->order_net_amount = $invoiceHdrDetail->order_total_amount;
						}
						$invoiceHdrDetail->save();
						if (!empty($invoiceHdrDetail->invoice_dtl_id)) {
							$order->updateOrderStausLog($invoiceHdrDetail->order_id, '8');	//Manage order process log
							if (!empty($invoice->orderDispatchedStatus($invoiceHdrDetail->order_id))) {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '11');
							} else {
								$order->updateOrderStatusToNextPhase($invoiceHdrDetail->order_id, '9');
							}
							$invoiceHdrDetailInfo[$invoiceHdrDetail->order_id] = $invoiceHdrDetail->order_id;
						}
					}
					if (!empty($invoiceHdrDetailInfo)) {
						!empty($invoceSessionStatusArr) ? $invoice->__updateInvoiceNewSessionDtl($invoceSessionStatusArr) : ''; //Updating Invoice Session Detail
						$flag[] = $invoiceHdr->invoice_id;
					}
				}
			}
		} catch (\Exception $e) {
			return false;
		}

		return $flag;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function viewInvoice($invoice_id)
	{

		global $order, $models, $invoice, $numbersToWord;

		$error       = '0';
		$message     = '';
		$data        = '';
		$invoiceData = array();

		$invoiceData = $invoice->getInvoiceHdr($invoice_id);
		$error	     = !empty($invoiceData) ? '1' : '0';

		return response()->json(['error' => $error, 'message' => $message, 'invoice_id' => $invoice_id, 'invoiceDetailList' => $invoiceData]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getInvoiceDetail($invoice_id)
	{

		global $order, $models, $invoice, $numbersToWord;

		$error       = '0';
		$message     = config('messages.message.error');
		$data        = '';
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
				->select('divisions.division_name', 'product_categories.p_category_name', 'customer_master.customer_name', 'customer_master.customer_email', 'customer_master.customer_address', 'customer_master.customer_state', 'customer_master.customer_city', 'customer_master.customer_gst_no', 'city_db.city_name as customer_city_name', 'state_db.state_name as customer_state_name', 'order_master.order_date', 'order_master.sample_description_id', 'order_master.batch_no', 'order_master.order_no', 'order_master.order_id', 'order_master.discount_type_id', 'order_master.discount_value', 'order_master.product_category_id', 'order_master.billing_type_id', 'order_master.po_no', 'order_master.po_date', 'invoice_hdr.*', 'order_report_details.report_file_name', 'order_report_details.report_no', 'product_master_alias.c_product_name as sample_description', 'invoice_hdr_detail.order_amount', 'invoice_hdr_detail.order_discount', 'invoice_hdr_detail.order_total_amount', 'invoice_hdr_detail.order_sgst_amount', 'invoice_hdr_detail.order_cgst_amount', 'invoice_hdr_detail.order_igst_amount', 'invoice_hdr_detail.order_net_amount', 'invoicing_master.customer_address as altInvoicingAddress', 'invoicingToState.state_name as invoicing_state', 'invoicingToCity.city_name as invoicing_city', 'invoicing_master.customer_name as invoicingCustomerName', 'invoicing_master.customer_gst_no as invoicingCustomerGSTo', 'invoiceByTb.name as invoice_by', 'invoiceByTb.user_signature', 'template_dtl.header_content', 'template_dtl.footer_content')
				->where('invoice_hdr.invoice_id', $invoice_id)
				->orderBy('order_master.po_no', 'ASC')
				->orderBy('order_master.order_no', 'ASC')
				->get()
				->toArray();

			if (!empty($invoiceDetailList)) {
				foreach ($invoiceDetailList as $key => $values) {
					$values->net_total_wsw = round(DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_id', $values->invoice_id)->sum('invoice_hdr_detail.order_net_amount'));
					$invoiceData['invoiceHeader']   	= array(
						'invoice_id'            		=> $values->invoice_id,
						'invoice_no'            		=> $values->invoice_no,
						'division_name'            		=> $values->division_name,
						'p_category_name'           	=> $values->p_category_name,
						'customer_name'         		=> !empty($values->invoicingCustomerName) ? ucfirst($values->invoicingCustomerName) : ucfirst($values->customer_name),
						'customer_city_name'    		=> !empty($values->invoicing_city) ? strtoupper($values->invoicing_city) : strtoupper($values->customer_city_name),
						'customer_state_name'   		=> !empty($values->invoicing_state) ? strtoupper($values->invoicing_state) : strtoupper($values->customer_state_name),
						'customer_address'      		=> !empty($values->altInvoicingAddress) ? $values->altInvoicingAddress : $values->customer_address,
						'customer_gst_no'       		=> !empty($values->invoicingCustomerGSTo) ? strtoupper($values->invoicingCustomerGSTo) : strtoupper($values->customer_gst_no),
						'invoice_date'          		=> date(DATEFORMAT, strtotime($values->invoice_date)),
						'order_no'          			=> $values->order_no,
						'billing_type'          		=> $values->billing_type_id,
						'invoice_by'					=> $values->invoice_by,
						'user_sign_path'				=> SITE_URL . SIGN_PATH,
						'user_signature'				=> $values->user_signature,
						'division_id'   				=> $values->division_id,
						'product_category_id'   		=> $values->product_category_id,
						'invoice_file_name'     		=> $values->invoice_file_name,
						'invoice_file_name_without_hf' 	=> $values->invoice_file_name_without_hf,
						'header_content' 				=> $values->header_content,
						'footer_content' 				=> $values->footer_content,
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
							'basic_rate'            => $values->order_total_amount,
							'service_tax'          	=> $models->roundValue($values->order_sgst_amount + $values->order_cgst_amount + $values->order_igst_amount),
							'l1_final_amount'       => $models->roundValue($values->order_net_amount),
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
							'basic_rate'           	=> $values->order_total_amount,
							'service_tax'          	=> $models->roundValue($values->order_sgst_amount + $values->order_cgst_amount + $values->order_igst_amount),
							'l1_final_amount'       => $models->roundValue($values->order_net_amount),
						);
					}

					$invoiceData['invoiceFooter'] = array(
						'total'              		=> $values->total_amount,
						'discount'           		=> $values->total_discount,
						'discount_text'      		=> !empty($values->discount_type_id) && $values->discount_type_id == '2' ? '(' . $values->discount_value . '%)' : '0',
						'net_amount'         		=> !empty($values->total_discount) && round($values->total_discount) > '0' ? number_format((float) $values->total_amount - $values->total_discount, 2, '.', '') : '',
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
			$error = '1';
			$message = '';
		}

		return response()->json(['error' => $error, 'message' => $message, 'invoice_id' => $invoice_id, 'invoiceDetailList' => $invoiceData]);
	}

	/**
	 * generate final report pdf
	 *
	 * $Request
	 * @return \Illuminate\Http\Response
	 */
	public function uploadInvoicePdf(Request $request)
	{

		global $order, $invoice, $models, $mail;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = array();
		$flag     = '0';
		$formData = array();

		if (!empty($request['invoice_file'])) {
			$formData = array_filter($request->all());
			if (!empty($formData['invoice_id'])) {
				$invoice_id = $invoice->getOrderInvoiceDetails($formData['invoice_id']);
				if (!empty($invoice_id->invoice_id)) {
					$updated = DB::table('invoice_hdr')->where('invoice_id', '=', $invoice_id->invoice_id)->update(['invoice_file_name' => $formData['invoice_file_name']]);
					if ($updated) {
						//generate pdf file in public/images/sales/invoices folder
						$invoice_file = $formData['invoice_file'];
						list($type, $invoice_file) = explode(';', $invoice_file);
						list(, $invoice_file) = explode(',', $invoice_file);
						$invoice_file = base64_decode($invoice_file);
						if (!file_exists(DOC_ROOT . INVOICE_PATH)) {
							mkdir(DOC_ROOT . INVOICE_PATH, 0777, true);
						}
						$pdf = fopen(DOC_ROOT . INVOICE_PATH . $formData['invoice_file_name'], 'w');
						fwrite($pdf, $invoice_file);
						fclose($pdf);

						//******************send mail to customers********************//
						$mailTemplateType = "3";
						$requiredData = array('invoice_id' => $invoice_id->invoice_id, 'mailTemplateType' => $mailTemplateType);
						$mail->sendMail($requiredData);
						//******************send mail to customers********************//

						$message = config('messages.message.invoiceGenerated');
						$error = 1;
					}
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'formData' => $formData));
	}

	/**
	 * Dispatch Order.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function dispatchInvoiceWithReports(Request $request)
	{

		global $order, $models, $invoice;

		$error            = '0';
		$message          = config('messages.message.OrderDispatchedFailedMsg');
		$currentDateTime  = CURRENTDATETIME;
		$user_id          = defined('USERID') ? USERID : '0';
		$invoiceId	  = '0';
		$invoiceNo     	  = '';
		$formData         = array();

		try {
			//Listing of Invoices according to billing type
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing Value of form data
				parse_str($request->formData, $formData);

				if (empty($formData['dispatch_date'])) {
					$message = config('messages.message.dispatchDateRequired');
				} else if (empty($formData['invoice_id'])) {
					$message = config('messages.message.error');
				} else {
					//Unsetting the variable from request data
					$formData         = $models->unsetFormDataVariables($formData, array('_token'));
					$arNumber         = !empty($formData['ar_bill_no']) ? trim(strtoupper($formData['ar_bill_no'])) : NULL;
					$invoiceId        = !empty($formData['invoice_id']) ? $formData['invoice_id'] : '0';
					$dispatchDate     = !empty($formData['dispatch_date']) ? $models->get_formatted_date($formData['dispatch_date'], $format = 'Y-m-d') : '0';
					$dispatchDatetime = $order->getFormatedDateTime($formData['dispatch_date'], $format = 'Y-m-d');
					$invoiceHdrData   = $invoice->getInvoiceHdr($invoiceId);
					$invoiceNo        = !empty($invoiceHdrData->invoice_no) ? $invoiceHdrData->invoice_no : '';

					//Saving of Invoice Dispatch Detail		    
					if (!empty($invoiceHdrData->invoice_id)) {
						$invoiceDispatchDtls = DB::table('invoice_dispatch_dtls')->where('invoice_dispatch_dtls.invoice_dispatch_status', '1')->where('invoice_dispatch_dtls.invoice_id', $invoiceHdrData->invoice_id)->where('invoice_dispatch_dtls.ar_bill_no', $arNumber)->where(DB::raw("DATE(invoice_dispatch_dtls.invoice_dispatch_date)"), $dispatchDate)->first();
						if (empty($invoiceDispatchDtls)) {			//If Invoice Not Dispatched
							$dataSaveInvoiceHdr = array();
							$dataSaveInvoiceHdr['invoice_id']     		= $invoiceHdrData->invoice_id;
							$dataSaveInvoiceHdr['invoice_dispatch_by']  	= $user_id;
							$dataSaveInvoiceHdr['ar_bill_no']           	= $arNumber;
							$dataSaveInvoiceHdr['invoice_dispatch_date']	= $dispatchDatetime;
							$dataSaveInvoiceHdr['invoice_dispatch_status']	= '1';
							if (DB::table('invoice_dispatch_dtls')->insertGetId($dataSaveInvoiceHdr)) {
								$error   = '1';
								$message = config('messages.message.invoiceDispatchedMsg');
							} else {
								$error = '0';
								$message = config('messages.message.savedError');
							}
						} else if (!empty($invoiceDispatchDtls->invoice_dispatch_id)) {
							$error = '0';
							$message = config('messages.message.existError');
						}
						//Saving of Dispatch Detail of Invoice Related Orders Orders
						$invoiceHdrDetail = DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_id', $invoiceHdrData->invoice_id)->get();
						if (!empty($invoiceHdrDetail)) {
							foreach ($invoiceHdrDetail as $invoiceHdr) {
								$orderDispatchDtl = DB::table('order_dispatch_dtl')->where('order_dispatch_dtl.order_id', $invoiceHdr->order_id)->where('order_dispatch_dtl.ar_bill_no', $arNumber)->where(DB::raw("DATE(order_dispatch_dtl.dispatch_date)"), $dispatchDate)->where('order_dispatch_dtl.amend_status', '=', '0')->first();
								if (empty($orderDispatchDtl)) {
									$dataSave                   = array();
									$dataSave['order_id']       = $invoiceHdr->order_id;
									$dataSave['dispatch_by']    = $user_id;
									$dataSave['ar_bill_no']     = $arNumber;
									$dataSave['dispatch_date']  = $dispatchDatetime;
									DB::table('order_dispatch_dtl')->insertGetId($dataSave);
								}
								//update Order Status and Log
								$order->updateOrderStausLog($invoiceHdr->order_id, '11');
							}
						}
					}
				}
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.error');
		}

		return response()->json(['error' => $error, 'message' => $message, 'invoice_id' => $invoiceId, 'invoice_no' => $invoiceNo]);
	}

	/********************************************************************
	 * Description : View dispatch detail for invoices
	 * Date        : 30-July-2018
	 * Author      : Praveen Singh
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ***********************************************************************/
	public function getInvoiceDispatchDetail(Request $request)
	{

		global $order, $models, $invoice;

		$error      = '0';
		$message    = config('message.message.error');
		$invoiceId  = '0';
		$invoiceStatus = '0';
		$returnData = array();

		try {
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing of Form Data
				parse_str($request->formData, $formData);

				$invoiceId = !empty($formData['invoice_id']) ? $formData['invoice_id'] : '0';
				$invoiceHdrData = $invoice->getInvoiceHdr($invoiceId);
				$invoiceStatus = !empty($invoiceHdrData->invoice_status)  ? $invoiceHdrData->invoice_status : '0';

				$returnData = DB::table('invoice_dispatch_dtls')
					->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_dispatch_dtls.invoice_id')
					->join('users', 'invoice_dispatch_dtls.invoice_dispatch_by', 'users.id')
					->where('invoice_dispatch_dtls.invoice_dispatch_status', '1')
					->where('invoice_dispatch_dtls.invoice_id', $invoiceId)
					->select('invoice_hdr.invoice_no', 'invoice_dispatch_dtls.ar_bill_no', 'invoice_dispatch_dtls.invoice_dispatch_date as dispatch_date', 'users.name as dispatched_by')
					->orderBy('invoice_dispatch_dtls.invoice_dispatch_id', 'ASC')
					->get()
					->toArray();

				//to formate Dispatch date and Time
				$models->formatTimeStampFromArray($returnData, DATETIMEFORMAT);
				$error = !empty($returnData) ? 1 : 0;
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.error');
		}

		return response()->json(array('error' => $error, 'message' => $message, 'invoice_id' => $invoiceId, 'invoice_status' => $invoiceStatus, 'dispatchDetail' => $returnData));
	}

	/********************************************************************
	 * Description : update Customer State Defined In Invoicing-To Column of Order Master
	 * Date        : 05-02-2018
	 * Author      : Praveen Singh
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ***********************************************************************/
	public function viewInvoiceOrderDetail(Request $request)
	{

		global $order, $models, $invoice;

		$error      	= '0';
		$message    	= config('message.message.error');
		$invoiceNumber 	= '';
		$invoice_id 	= !empty($request['formData']) ? $request['formData'] : '';
		$returnData 	= array();

		if ($invoice_id) {

			$invoiceData   = $invoice->getInvoiceHdr($invoice_id);
			$invoiceNumber = !empty($invoiceData->invoice_no) ? $invoiceData->invoice_no : '';
			$returnData    = DB::table('invoice_hdr_detail')
				->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_hdr_detail.invoice_hdr_id')
				->join('product_categories', 'product_categories.p_category_id', 'invoice_hdr.product_category_id')
				->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
				->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
				->join('order_sample_priority', 'order_sample_priority.sample_priority_id', 'order_master.sample_priority_id')
				->join('order_status', 'order_status.order_status_id', 'order_master.status')
				->join('divisions', 'divisions.division_id', 'order_master.division_id')
				->join('customer_master', 'customer_master.customer_id', 'order_master.customer_id')
				->join('city_db', 'city_db.city_id', 'customer_master.customer_city')
				->join('product_master_alias', 'product_master_alias.c_product_id', 'order_master.sample_description_id')
				->leftJoin('order_dispatch_dtl', function ($join) {
					$join->on('order_dispatch_dtl.order_id', '=', 'order_master.order_id');
					$join->where('order_dispatch_dtl.amend_status', '0');
					$join->whereRaw('order_dispatch_dtl.dispatch_id IN (SELECT MAX(odd.dispatch_id) FROM order_dispatch_dtl odd INNER JOIN order_master om ON odd.order_id = om.order_id GROUP BY odd.order_id)');
				})
				->leftJoin('users as dispatchByDB', 'dispatchByDB.id', 'order_dispatch_dtl.dispatch_by')
				->select('divisions.division_name', 'product_categories.p_category_name as department_name', 'customer_master.customer_name', 'city_db.city_name as place', 'order_master.order_date', 'product_master_alias.c_product_name as sample_description', 'order_sample_priority.sample_priority_name as sample_priority', 'order_master.order_no', 'order_report_details.report_no', 'invoice_hdr.invoice_no', 'invoice_hdr_detail.order_amount', 'invoice_hdr_detail.order_discount', 'order_dispatch_dtl.dispatch_date', 'order_dispatch_dtl.ar_bill_no as dispatched_no', 'dispatchByDB.name as dispatcher', 'order_status.order_status_name as status')
				->where('invoice_hdr_detail.invoice_hdr_id', '=', $invoice_id)
				->get()
				->toArray();

			$error   = !empty($returnData) ? '1' : '0';
			$message = !empty($error) ? '' : $message;

			//Formating the Date and Time
			$models->formatTimeStampFromArray($returnData, DATETIMEFORMAT);
		}
		return response()->json(array('error' => $error, 'message' => $message, 'orderDetail' => $returnData, 'downloadList' => $models->get_encrypted_string($returnData), 'invoiceNumber' => $invoiceNumber));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function viewInvoicingReport(Request $request, $order_id)
	{

		global $order, $report, $models, $invoice;

		$error   = '0';
		$message = '';
		$data    = '';
		$rawTestProductStdParaList = $categoryWiseParamenter = $categoryWiseParamenterArr = array();

		if ($order_id) {

			$error              		= '1';
			$equipment_type_ids 		= defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
			$role_ids           		= defined('ROLE_IDS') ? ROLE_IDS : '0';
			$user_id            		= defined('USERID') ? USERID : '0';
			$orderList              	= $order->getOrder($order_id);
			$testProductStdParaList 	= $order->getOrderParameters($order_id);
			$allowedExceptionParameters = array('description', 'description(cl:3.2.1)', 'description(cl:3.2)');

			list($invoicingRates, $invoicingParameterRates) = $invoice->getReportsInvoicingRates($orderList->customer_id, $orderList->order_id);
			$orderList->invoiceRate = !empty($invoicingRates) ?  $invoicingRates : '0';
			$invoicingParameterRate['invoiceRate'] = !empty($invoicingParameterRates) ? $invoicingParameterRates : array();

			//invoicing_type_id to formate order and Report date
			$models->formatTimeStamp($orderList, DATETIMEFORMAT);

			if (!empty($testProductStdParaList)) {
				foreach ($testProductStdParaList as $key => $values) {
					//checking if desccription has been edited or not
					if (!empty($values->test_parameter_name) && in_array(strtolower($values->test_parameter_name), $allowedExceptionParameters)) {
						if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
							$values->description = $values->test_result;
						}
					}
					if (!empty($values->test_parameter_name) && strtolower($values->test_parameter_name) == 'reference to protocol') {
						if (!empty($values->test_result) && strtolower($values->test_result) != 'n/a') {
							$values->description = $values->test_result;
						}
					}
					$rawTestProductStdParaList[$values->analysis_id]  = $values;
				}
			}

			if (!empty($rawTestProductStdParaList)) {
				foreach ($rawTestProductStdParaList as $analysis_id => $values) {
					$values->invoicingGroupName = $invoice->assignInvoicingGroupForAssigningRates($values);
					$models->getRequirementSTDFromTo($values, $values->standard_value_from, $values->standard_value_to);
					$categoryWiseParamenter[$values->test_para_cat_id]['categorySortBy']   	= $values->category_sort_by;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryId']          	= $values->test_para_cat_id;
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryName']          = $values->test_para_cat_name;
					$categoryWiseParamenter[$values->test_para_cat_id]['productCategoryName']   = str_replace(' ', '', strtolower($values->test_para_cat_name));
					foreach ($invoicingParameterRate['invoiceRate'] as $invoiceRateKey => $val) {
						if ($invoiceRateKey == $values->invoicingGroupName) {
							$values->invoicingRates = number_format($val, 2);
						}
					}
					$categoryWiseParamenter[$values->test_para_cat_id]['categoryParams'][] = $values;
				}
				$categoryWiseParamenterArr = $models->sortArrayAscOrder(array_values($categoryWiseParamenter));
			}
		}
		return response()->json(['error' => $error, 'message' => $message, 'orderList' => $orderList, 'orderParameterList' => $categoryWiseParamenterArr]);
	}

	/**
	 * DELETE.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteInvoice(Request $request, $invoice_id)
	{

		global $order, $report, $models, $invoice;

		$error   = '0';
		$message = '';
		$data    = '';
		$currentDate = defined('CURRENTDATETIME') ? CURRENTDATETIME : date('Y-m-d H:i:s');

		try {
			if (DB::table('invoice_hdr')->where('invoice_hdr.invoice_id', '=', $invoice_id)->delete()) {
				$error = '1';
				$message = config('messages.message.invoiceDeleteMsg');
			} else {
				$message = config('messages.message.invoiceForeignKeConstFail');
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.invoiceForeignKeConstFail');
		}
		return response()->json(['error' => $error, 'message' => $message]);
	}

	/**
	 * process Invoice Cancellation.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function processInvoiceCancellation(Request $request)
	{

		global $order, $report, $models, $invoice, $creditNote, $debitNote;

		$error            = '0';
		$message          = config('messages.message.error');
		$currentDateTime  = CURRENTDATETIME;
		$user_id          = defined('USERID') ? USERID : '0';
		$formData         = array();

		try {
			//Listing of Invoices according to billing type
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing Value of form data
				parse_str($request->formData, $formData);

				if (empty($formData['invoice_id'])) {
					$message = config('messages.message.errorInProcessing');
				} else if (empty($formData['cancelledWithRelatedOrders'])) {
					$message = config('messages.message.cancelledWithRelatedOrdersError');
				} else if (empty($formData['cancellation_description'])) {
					$message = config('messages.message.cancellationDescriptionError');
				} else if (empty($formData['invoice_canc_approved_by'])) {
					$message = config('messages.message.invoiceCancApprovedByError');
				} else if (empty($formData['invoice_canc_approved_date'])) {
					$message = config('messages.message.invoiceCancApprovedDateError');
				} else {
					if ($formData['cancelledWithRelatedOrders'] == '1') {
						$status  = $invoice->cancelled_invoice_with_orders($formData);
						$message = $status ? config('messages.message.invoiceCancelledMsg') : config('messages.message.errorInProcessing');
					} elseif ($formData['cancelledWithRelatedOrders'] == '2') {
						$status = $invoice->cancelled_invoice_without_orders($formData);
						$message = $status ? config('messages.message.invoiceRegeneratedOpenMsg') : config('messages.message.errorInProcessing');
					}
					$error = $status ? '1' : '0';
				}
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.errorInProcessing');
		}
		return response()->json(['error' => $error, 'message' => $message]);
	}

	/********************************************************************
	 * Description : View Cancelled Invoice Detail
	 * Date        : 27-07-2018
	 * Author      : Praveen Singh
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ***********************************************************************/
	public function getCancelledInvoiceDetail(Request $request)
	{

		global $order, $models;

		$error      = '0';
		$message    = config('message.message.error');
		$returnData = array();

		if (!empty($request->formData)) {

			//Parsing of Form Data
			parse_str($request->formData, $formData);

			$returnData = DB::table('invoice_cancellation_dtls')
				->join('invoice_hdr', 'invoice_hdr.invoice_id', 'invoice_cancellation_dtls.invoice_id')
				->join('users', 'invoice_cancellation_dtls.invoice_cancelled_by', 'users.id')
				->select('invoice_hdr.invoice_no', 'invoice_cancellation_dtls.*', 'users.name as invoiceCancelledBy')
				->where('invoice_cancellation_dtls.invoice_id', '=', $formData['invoice_id'])
				->first();

			//to formate Dispatch date and Time
			$models->formatTimeStamp($returnData, DATETIMEFORMAT);
			$error = !empty($returnData) ? 1 : 0;
		}

		return response()->json(array('error' => $error, 'message' => $message, 'invoiceCancelledDetail' => $returnData));
	}

	/********************************************************************
	 * Description : Function to get related reports of an Invoice
	 * Date        : 09-04-2019
	 * Author      : RUBY
	 * Modified By : Praveen Singh
	 * Modified On : 10-04-2019
	 ***********************************************************************/
	public function getRelatedReports(Request $request)
	{

		$error      = '0';
		$message    = config('message.message.error');
		$returnData = $formData = array();

		try {
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing Value of form data
				parse_str($request->formData, $formData);

				$reportIdArrData = DB::table('invoice_hdr')
					->join('customer_master', 'customer_master.customer_id', 'invoice_hdr.customer_id')
					->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', 'invoice_hdr.invoice_id')
					->join('order_master', 'order_master.order_id', 'invoice_hdr_detail.order_id')
					->leftJoin('customer_master as reporting_master', 'reporting_master.customer_id', 'order_master.reporting_to')
					->where('invoice_hdr_detail.invoice_hdr_id', '=', $formData['invoice_id'])
					->select('order_master.order_id', 'order_master.order_no', 'customer_master.customer_name', 'order_master.reporting_to', 'reporting_master.customer_name as reportingCustomerName')
					->get()
					->toArray();

				if (!empty($reportIdArrData)) {
					foreach ($reportIdArrData as $key => $value) {
						if (!empty($value->reporting_to) && !empty($value->reportingCustomerName)) {
							$returnData[str_replace(' ', '_', strtolower($value->reportingCustomerName))][] = $value;
						} else {
							$returnData[str_replace(' ', '_', strtolower($value->customer_name))][] = $value;
						}
					}
				}
				$error = !empty($returnData) ? '1' : '0';
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.errorInProcessing');
		}

		return response()->json(array('error' => $error, 'message' => $message, 'invoiceReportsDetail' => $returnData));
	}

	/******************************************************
	 * description : Editing of Invoice
	 * Created By : Praveen Singh
	 * Created On : 28-March-2022
	 *******************************************************/
	public function editInvoiceDetail($invoice_id)
	{

		global $order, $models, $invoice, $numbersToWord;

		$error       = '0';
		$message     = config('messages.message.error');
		$data        = '';
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
				->select('divisions.division_name', 'product_categories.p_category_name', 'customer_master.customer_name', 'customer_master.customer_email', 'customer_master.customer_address', 'customer_master.customer_state', 'customer_master.customer_city', 'customer_master.customer_gst_no', 'city_db.city_name as customer_city_name', 'state_db.state_name as customer_state_name', 'order_master.order_date', 'order_master.sample_description_id', 'order_master.batch_no', 'order_master.order_no', 'order_master.order_id', 'order_master.discount_type_id', 'order_master.discount_value', 'order_master.product_category_id', 'order_master.billing_type_id', 'order_master.po_no', 'order_master.po_date', 'invoice_hdr.*', 'order_report_details.report_file_name', 'order_report_details.report_no', 'product_master_alias.c_product_name as sample_description', 'invoice_hdr_detail.order_amount', 'invoice_hdr_detail.order_discount', 'invoice_hdr_detail.order_total_amount', 'invoice_hdr_detail.order_sgst_amount', 'invoice_hdr_detail.order_cgst_amount', 'invoice_hdr_detail.order_igst_amount', 'invoice_hdr_detail.order_net_amount', 'invoicing_master.customer_address as altInvoicingAddress', 'invoicingToState.state_name as invoicing_state', 'invoicingToCity.city_name as invoicing_city', 'invoicing_master.customer_name as invoicingCustomerName', 'invoicing_master.customer_gst_no as invoicingCustomerGSTo', 'invoiceByTb.name as invoice_by', 'invoiceByTb.user_signature', 'template_dtl.header_content', 'template_dtl.footer_content')
				->where('invoice_hdr.invoice_id', $invoice_id)
				->orderBy('order_master.po_no', 'ASC')
				->orderBy('order_master.order_no', 'ASC')
				->get();

			if (!empty($invoiceDetailList)) {
				foreach ($invoiceDetailList as $key => $values) {

					$values->net_total_wsw = round(DB::table('invoice_hdr_detail')->where('invoice_hdr_detail.invoice_hdr_id', $values->invoice_id)->sum('invoice_hdr_detail.order_net_amount'));

					$invoiceData['invoiceHeader'] = array(
						'invoice_id'            	=> $values->invoice_id,
						'invoice_no'            	=> $values->invoice_no,
						'division_name'            	=> $values->division_name,
						'p_category_name'           => $values->p_category_name,
						'customer_name'         	=> !empty($values->invoicingCustomerName) ? ucfirst($values->invoicingCustomerName) : ucfirst($values->customer_name),
						'customer_city_name'    	=> !empty($values->invoicing_city) ? strtoupper($values->invoicing_city) : strtoupper($values->customer_city_name),
						'customer_state_name'   	=> !empty($values->invoicing_state) ? strtoupper($values->invoicing_state) : strtoupper($values->customer_state_name),
						'customer_address'      	=> !empty($values->altInvoicingAddress) ? $values->altInvoicingAddress : $values->customer_address,
						'customer_gst_no'       	=> !empty($values->invoicingCustomerGSTo) ? strtoupper($values->invoicingCustomerGSTo) : strtoupper($values->customer_gst_no),
						'invoice_date'          	=> date(DATEFORMAT, strtotime($values->invoice_date)),
						'order_no'          		=> $values->order_no,
						'billing_type'          	=> $values->billing_type_id,
						'invoice_by'				=> $values->invoice_by,
						'user_sign_path'			=> SITE_URL . SIGN_PATH,
						'user_signature'			=> $values->user_signature,
						'division_id'   			=> $values->division_id,
						'product_category_id'   	=> $values->product_category_id,
						'invoice_file_name'     	=> $values->invoice_file_name,
						'invoice_file_name_without_hf' 	=> $values->invoice_file_name_without_hf,
						'header_content' 				=> $values->header_content,
						'footer_content' 				=> $values->footer_content,
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
							'basic_rate'            => $values->order_total_amount,
							'service_tax'          	=> $models->roundValue($values->order_sgst_amount + $values->order_cgst_amount + $values->order_igst_amount),
							'l1_final_amount'       => $models->roundValue($values->order_net_amount),
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
							'basic_rate'           	=> $values->order_total_amount,
							'service_tax'          	=> $models->roundValue($values->order_sgst_amount + $values->order_cgst_amount + $values->order_igst_amount),
							'l1_final_amount'       => $models->roundValue($values->order_net_amount),
						);
					}

					$invoiceData['invoiceFooter'] = array(
						'total'              		=> $values->total_amount,
						'discount'           		=> $values->total_discount,
						'discount_text'      		=> !empty($values->discount_type_id) && $values->discount_type_id == '2' ? '(' . $values->discount_value . '%)' : '0',
						'net_amount'         		=> !empty($values->total_discount) && round($values->total_discount) > '0' ? number_format((float) $values->total_amount - $values->total_discount, 2, '.', '') : '',
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
			$error = '1';
			$message = '';
		}

		return response()->json(['error' => $error, 'message' => $message, 'invoice_id' => $invoice_id, 'invoiceDetailList' => $invoiceData]);
	}

	/******************************************************
	 * description : Editing of Invoice
	 * Created By : Praveen Singh
	 * Created On : 28-March-2022
	 *******************************************************/
	public function updateInvoiceDetail(Request $request)
	{
		global $order, $models, $invoice, $numbersToWord, $customer;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = array();

		try {

			//Saving record in orders table
			if (!empty($request->formData) && $request->isMethod('post')) {

				//Parsing the Serialze Dta
				parse_str($request->formData, $formData);

				if (empty($formData['invoice_id'])) {
					$message = config('messages.message.error');
				} elseif (in_array('sgstRate', array_keys($formData)) && in_array('cgstRate', array_keys($formData)) && empty($formData['sgstRate']) && empty($formData['cgstRate'])) {
					$message = config('messages.message.required');
				} elseif (in_array('igstRate', array_keys($formData)) && empty($formData['igstRate'])) {
					$message = config('messages.message.required');
				} else {

					//Starting transaction
					DB::beginTransaction();

					$invoice_id   		= !empty($formData['invoice_id']) ? $formData['invoice_id'] : '0';
					$SGST         		= !empty($formData['sgstRate']) ? $formData['sgstRate'] : '0';
					$CGST         		= !empty($formData['cgstRate']) ? $formData['cgstRate'] : '0';
					$IGST         		= !empty($formData['igstRate']) ? $formData['igstRate'] : '0';
					$invoiceHdr       	= InvoiceHdr::find($invoice_id); //Invoice Hdr 
					$invoiceHdrDataList	= InvoiceHdrDetail::where('invoice_hdr_id', $invoice_id)->pluck('invoice_dtl_id', 'invoice_dtl_id')->all(); //Invoice Hdr Detail

					if (!empty($invoiceHdr) && !empty($invoiceHdrDataList)) {

						//Updating Invoice Hdr Table
						if (in_array('sgstRate', array_keys($formData)) && !empty($invoiceHdr->sgst_rate) && !empty($invoiceHdr->cgst_rate)) {  //For SGST and CGST Taxing
							$invoiceHdr->sgst_rate          = $SGST;
							$invoiceHdr->sgst_amount        = ($invoiceHdr->net_amount * $SGST) / 100;
							$invoiceHdr->cgst_rate          = $CGST;
							$invoiceHdr->cgst_amount        = ($invoiceHdr->net_amount * $CGST) / 100;
							$invoiceHdr->net_total_amount   = $invoiceHdr->net_amount + $invoiceHdr->sgst_amount + $invoiceHdr->cgst_amount;
							$invoiceHdr->save();
						} elseif (in_array('igstRate', array_keys($formData)) && !empty($invoiceHdr->igst_rate)) {								//For IGST Taxing
							$invoiceHdr->igst_rate          = $IGST;
							$invoiceHdr->igst_amount        = ($invoiceHdr->net_amount * $IGST) / 100;
							$invoiceHdr->net_total_amount   = $invoiceHdr->net_amount + $invoiceHdr->igst_amount;
							$invoiceHdr->save();
						}

						//Updating Invoice Hdr Detail Table
						if (!empty($invoiceHdrDataList)) {
							foreach ($invoiceHdrDataList as $key => $invoice_dtl_id) {
								$invoiceHdrDetail = InvoiceHdrDetail::find($invoice_dtl_id);			//Invoice Hdr Detail
								if (!empty($invoiceHdrDetail)) {
									if (in_array('sgstRate', array_keys($formData)) && in_array('cgstRate', array_keys($formData)) && !empty($invoiceHdrDetail->order_sgst_rate) && !empty($invoiceHdrDetail->order_cgst_rate)) {  //For SGST and CGST Taxing         //For SGST and CGST Taxing
										$invoiceHdrDetail->order_sgst_rate    = $SGST;
										$invoiceHdrDetail->order_sgst_amount  = ($invoiceHdrDetail->order_total_amount * $SGST) / 100;
										$invoiceHdrDetail->order_cgst_rate    = $CGST;
										$invoiceHdrDetail->order_cgst_amount  = ($invoiceHdrDetail->order_total_amount * $CGST) / 100;
										$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_sgst_amount + $invoiceHdrDetail->order_cgst_amount;
										$invoiceHdrDetail->save();
									} elseif (in_array('igstRate', array_keys($formData)) && !empty($invoiceHdrDetail->order_igst_rate)) {                                             		//For IGST Taxing
										$invoiceHdrDetail->order_igst_rate    = $IGST;
										$invoiceHdrDetail->order_igst_amount  = ($invoiceHdrDetail->order_total_amount * $IGST) / 100;
										$invoiceHdrDetail->order_net_amount   = $invoiceHdrDetail->order_total_amount + $invoiceHdrDetail->order_igst_amount;
										$invoiceHdrDetail->save();
									}
								}
							}
						}

						//Committing the queries
						DB::commit();

						$error   = '1';
						$message = config('messages.message.updated');
					} else {
						$error   = '1';
						$message = config('messages.message.updatedError');
					}
				}
			}
		} catch (\Exception $e) {
			DB::rollback();
		} catch (\Throwable $e) {
			DB::rollback();
		}
		return response()->json(['error' => $error, 'message' => $message]);
	}
}
