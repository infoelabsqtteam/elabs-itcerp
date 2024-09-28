<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Models;
use App\CreditNote;
use App\DebitNote;
use App\NumbersToWord;
use App\InvoiceHdr;
use Session;
use Validator;
use Route;
use DB;

class CreditNotesController extends Controller
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

		global $creditNote, $models, $numbersToWord, $invoice;

		$creditNote = new CreditNote();
		$debitNote = new DebitNote();
		$models    = new Models();
		$invoice   = new invoiceHdr();
		$numbersToWord = new NumbersToWord();

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
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		global $creditNote, $models;

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('payments.credit_notes.index', ['title' => 'Credit Notes', '_credit_note' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function creditNoteNumber()
	{

		global $models, $creditNote;

		$error   = '0';
		$message = '';
		$data    = '';

		$creditNoteNumber = $creditNote->generateCreditNoteNumber('CN');
		return response()->json(array('error' => $error, 'message' => $message, 'creditNoteNumber' => $creditNoteNumber));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function addCreditNote(Request $request)
	{

		global $creditNote, $models, $invoice;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//Parsing the form Data
			parse_str($request->formData, $formData);
			$formData = array_filter($formData);

			if (!empty($formData)) {

				if (empty($formData['division_id'])) {
					$message = config('messages.message.divisionNameCNErrorMsg');
				} else if (empty($formData['product_category_id'])) {
					$message = config('messages.message.departmentNameCNRequired');
				} else if (empty($formData['customer_id'])) {
					$message = config('messages.message.customerNameCNRequired');
				} else if (isset($formData['invoice_id']) && empty($formData['invoice_id'])) {
					$message = config('messages.message.customerInvoiceNumberRequired');
				} else if (isset($formData['credit_reference_no']) && empty($formData['credit_reference_no'])) {
					$message = config('messages.message.customerCreditRefNoRequired');
				} else if (empty($formData['credit_note_no'])) {
					$message = config('messages.message.creditNoteNoRequired');
				} else if ($creditNote->checkCreditNoteNumber($formData['credit_note_no'])) {
					$message = config('messages.message.creditNoteNoExist');
				} else if (empty($formData['credit_note_date'])) {
					$message = config('messages.message.creditNoteDateRequired');
				} else if (isset($formData['credit_note_amount']) && $formData['credit_note_amount'] == '') {
					$message = config('messages.message.creditNoteAmountRequired');
				} else if (empty($formData['credit_note_remark'])) {
					$message = config('messages.message.creditNoteRemarkRequired');
				} else {
					try {
						//Getting Tax SLab of the Customer Credit Notes
						$formData = $creditNote->calculateCreditNoteTaxSlab($formData);

						//Unsetting the variable from request data
						$formData = $models->unsetFormDataVariables($formData, array('_token'));
						$formData['credit_note_date'] = $models->convertDateFormat($formData['credit_note_date']);
						$formData['credit_note_type_id'] = '2';
						$formData['created_by']          = USERID;

						if (!empty($formData['credit_note_no'])) {
							$creditNoteId = DB::table('credit_notes')->insertGetId($formData);
							if (!empty($creditNoteId)) {
								$error   = '1';
								$message = config('messages.message.saved');
							}
						} else {
							$message = config('messages.message.savedError');
						}
					} catch (\Illuminate\Database\QueryException $ex) {
						$message = config('messages.message.savedError');
					}
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message, 'data' => $data));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getDivisionWiseCreditNotes(Request $request)
	{

		global $models, $creditNote;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = !empty($request->formData) ?  $request->formData : '';

		if (!empty($formData)) {

			//Parsing form Data
			parse_str($formData, $formData);

			$creditNoteSearch 	=   !empty($formData['keyword']) ?  trim($formData['keyword']) : '';
			$dataObj = DB::table('credit_notes')
				->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
				->leftJoin('invoice_hdr', 'invoice_hdr.invoice_id', 'credit_notes.invoice_id')
				->join('users as createdBy', 'createdBy.id', 'credit_notes.created_by')
				->select('invoice_hdr.invoice_no', 'credit_notes.*', 'divisions.division_name', 'departments.department_name', 'customer_master.customer_name', 'createdBy.name as createdByName');

			if (!empty($formData['division_id']) && is_numeric($formData['division_id'])) {
				$dataObj->where('credit_notes.division_id', $formData['division_id']);
			}
			if (!empty($creditNoteSearch)) {
				$creditNoteSearch = trim($creditNoteSearch);
				$dataObj->where('customer_master.customer_name', 'LIKE', '%' . $creditNoteSearch . '%')
					->orWhere('customer_master.customer_code', 'LIKE', '%' . $creditNoteSearch . '%')
					->orWhere('credit_notes.credit_reference_no', 'LIKE', '%' . $creditNoteSearch . '%')
					->orWhere('departments.department_name', 'LIKE', '%' . $creditNoteSearch . '%')
					->orWhere('credit_notes.credit_note_remark', 'LIKE', '%' . $creditNoteSearch . '%')
					->orWhere('invoice_hdr.invoice_no', 'LIKE', '%' . $creditNoteSearch . '%')
					->orWhere('credit_notes.credit_note_no', 'LIKE', '%' . $creditNoteSearch . '%')
					->orWhere('createdBy.name', 'LIKE', '%' . $creditNoteSearch . '%');
			} else {
				$dataObj->whereMonth('credit_notes.credit_note_date', date('m'));
				$dataObj->whereYear('credit_notes.credit_note_date', date('Y'));
			}
			$this->getCreditNotesMultisearch($dataObj, $formData);
			$creditNotesList  = $dataObj->orderBy('credit_notes.credit_note_id', 'DESC')->get();
		}

		$error            = !empty($creditNotesList) ? 1 : '0';
		$message          = $error ? '' : $message;

		//to formate created and updated date		   
		$models->formatTimeStampFromArray($creditNotesList, DATETIMEFORMAT);

		return response()->json(array('error' => $error, 'message' => $message, 'creditNotesList' => $creditNotesList));
	}

	/**
	 *generate credit notes excel file
	 *
	 **/
	public function generateCreditNotesMasterDocument(Request $request)
	{

		global $models;

		$responseData = array();

		$creditNoteSearch 	= !empty($request->filterCreditNoteSearch) ?  trim($request->filterCreditNoteSearch) : '0';

		$creditNoteNo 		=   !empty($request->search_credit_note_no) ?  trim($request->search_credit_note_no) : '0';
		$division		=   !empty($request->search_division_id) ?  trim($request->search_division_id) : '0';
		$department		=   !empty($request->search_department_name) ?  trim($request->search_department_name) : '0';
		$customerName		=   !empty($request->search_customer_name) ?  trim($request->search_customer_name) : '0';
		$creditNoteDate  	=   !empty($request->search_credit_note_date) ?  trim(date("Y-m-d", strtotime($request->search_credit_note_date))) : '0';
		$creditNoteAmount	=   !empty($request->search_credit_note_amount) ?  trim($request->search_credit_note_amount) : '0';
		$creditNoteRemarks	=   !empty($request->search_credit_note_remark) ?  trim($request->search_credit_note_remark) : '0';
		$creditNoteCreatedBy	=   !empty($request->search_created_by) ?  trim($request->search_created_by) : '0';

		$creditNoteDataObj = DB::table('credit_notes')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
			->leftJoin('invoice_hdr', 'invoice_hdr.invoice_id', 'credit_notes.invoice_id')
			->join('users as createdBy', 'createdBy.id', 'credit_notes.created_by')
			->select('credit_notes.credit_note_no', 'divisions.division_name as branch', 'departments.department_name as department', 'customer_master.customer_name', 'invoice_hdr.invoice_no as invoice_number', 'credit_notes.credit_reference_no as reference_no', 'credit_notes.credit_note_date', 'credit_notes.credit_note_amount as amount', 'credit_notes.credit_note_remark as remark', 'createdBy.name as created_by', 'credit_notes.created_at', 'credit_notes.updated_at');

		if (!empty($request->division_id) && is_numeric($request->division_id)) {
			$creditNoteDataObj->where('credit_notes.division_id', $request->division_id);
		}
		if (!empty($request->filterCreditNoteSearch)) {
			$creditNoteSearch = trim($creditNoteSearch);
			$creditNoteDataObj->orwhere('credit_notes.credit_note_no', 'LIKE', '%' . $creditNoteSearch . '%')
				->orWhere('divisions.division_name', 'LIKE', '%' . $creditNoteSearch . '%')
				->orWhere('departments.department_name', 'LIKE', '%' . $creditNoteSearch . '%')
				->orWhere('customer_master.customer_name', 'LIKE', '%' . $creditNoteSearch . '%')
				->orWhere('credit_notes.credit_reference_no', 'LIKE', '%' . $creditNoteSearch . '%')
				->orWhere('credit_notes.credit_note_no', 'LIKE', '%' . $creditNoteSearch . '%')
				->orWhere('credit_notes.credit_note_remark', 'LIKE', '%' . $creditNoteSearch . '%')
				->orWhere('createdBy.name', 'LIKE', '%' . $creditNoteSearch . '%');
		}
		if (!empty($creditNoteNo)) {
			$creditNoteDataObj->where('credit_notes.credit_note_no', 'LIKE', '%' . $creditNoteNo . '%');
		}
		if (!empty($department)) {
			$creditNoteDataObj->where('departments.department_name', 'LIKE', '%' . $department . '%');
		}
		if (!empty($customerName)) {
			$creditNoteDataObj->where('customer_master.customer_name', 'LIKE', '%' . $customerName . '%');
		}
		if (!empty($creditNoteDate)) {
			$creditNoteDataObj->where('credit_notes.credit_note_date', 'LIKE', '%' . $creditNoteDate . '%');
		}
		if (!empty($creditNoteAmount)) {
			$creditNoteDataObj->where('credit_notes.credit_note_amount', 'LIKE', '%' . $creditNoteAmount . '%');
		}
		if (!empty($creditNoteRemarks)) {
			$creditNoteDataObj->where('credit_notes.credit_note_remark', 'LIKE', '%' . $creditNoteRemarks . '%');
		}
		if (!empty($creditNoteCreatedBy)) {
			$creditNoteDataObj->where('createdBy.name', 'LIKE', '%' . $creditNoteCreatedBy . '%');
		}

		$creditNoteDataObj->orderBy('credit_notes.credit_note_id', 'DESC');
		$creditNoteData =  $creditNoteDataObj->get();

		$models->formatTimeStampFromArrayExcel($creditNoteData);

		$creditNoteDataList			= !empty($creditNoteData) ? json_decode(json_encode($creditNoteData), true) : array();
		$responseData['heading'] 		= 'Credit Note List :' . '(' . count($creditNoteDataList) . ')';
		$responseData['mis_report_name'] 	= 'Credit Note List';
		$responseData['tableHead'] 		= !empty($creditNoteDataList) ? array_keys(end($creditNoteDataList)) : array();
		$responseData['tableBody'] 		= !empty($creditNoteDataList) ? $creditNoteDataList : array();

		if (!empty($request->generate_credit_notes_documents) && $request->generate_credit_notes_documents == 'Excel') {
			return $models->generateExcel($responseData);
		} else {
			return redirect('dashboard')->withErrors('Permission Denied!');
		}
	}

	/**
	 * get patment credit notes using multisearch.
	 * Date : 30-05-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getCreditNotesMultisearch($dataObj, $searchArry)
	{

		global $models;

		if (!empty($searchArry['search_credit_note_no'])) {
			$dataObj->where('credit_notes.credit_note_no', 'like', '%' . trim($searchArry['search_credit_note_no']) . '%');
		}
		if (!empty($searchArry['search_department_name'])) {
			$dataObj->where('departments.department_name', 'like', '%' . trim($searchArry['search_department_name']) . '%');
		}
		if (!empty($searchArry['search_customer_name'])) {
			$dataObj->where('customer_master.customer_name', 'like', '%' . trim($searchArry['search_customer_name']) . '%');
		}
		if (!empty($searchArry['search_credit_note_date'])) {
			$dataObj->where('credit_notes.credit_note_date', 'like', '%' . date("Y-m-d", strtotime(trim($searchArry['search_credit_note_date']))) . '%');
		}
		if (!empty($searchArry['search_credit_note_amount'])) {
			$dataObj->where('credit_notes.credit_note_amount', 'like', '%' . trim($searchArry['search_credit_note_amount']) . '%');
		}
		if (!empty($searchArry['search_credit_note_remark'])) {
			$dataObj->where('credit_notes.credit_note_remark', 'like', '%' . trim(($searchArry['search_credit_note_remark'])) . '%');
		}
		if (!empty($searchArry['search_created_by'])) {
			$dataObj->where('createdBy.name', 'like', '%' . trim($searchArry['search_created_by']) . '%');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function viewCreditNote($credit_note_id)
	{

		global $models, $creditNote;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';

		$creditNoteData = DB::table('credit_notes')
			->join('divisions', 'divisions.division_id', 'credit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'credit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'credit_notes.customer_id')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', 'credit_notes.invoice_id')
			->join('users as createdBy', 'createdBy.id', 'credit_notes.created_by')
			->select('invoice_hdr.invoice_no as invoice_number', 'invoice_hdr.invoice_id', 'credit_notes.*', 'divisions.division_name', 'departments.department_name', 'customer_master.customer_name', 'createdBy.name as createdByName')
			->where('credit_notes.credit_note_id', $credit_note_id)
			->orderBy('credit_notes.credit_note_id', 'DESC')
			->first();

		$error    = !empty($creditNoteData) ? 1 : '0';
		$message  = $error ? '' : $message;
		return response()->json(array('error' => $error, 'message' => $message, 'creditNoteData' => $creditNoteData));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateCreditNote(Request $request)
	{

		global $models, $creditNote;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = array();

		if ($request->isMethod('post') && !empty($request['formData'])) {

			//pasrse searlize data 				
			parse_str($request['formData'], $formData);

			$creditNoteId = !empty($formData['credit_note_id']) ? $formData['credit_note_id'] : '0';

			if (empty($formData['division_id'])) {
				$message = config('messages.message.divisionNameCNErrorMsg');
			} else if (empty($formData['product_category_id'])) {
				$message = config('messages.message.departmentNameCNRequired');
			} else if (empty($formData['customer_id'])) {
				$message = config('messages.message.customerNameCNRequired');
			} else if (empty($formData['invoice_id'])) {
				$message = config('messages.message.customerInvoiceNumberRequired');
			} else if (empty($formData['credit_note_date'])) {
				$message = config('messages.message.creditNoteDateRequired');
			} else if (isset($formData['credit_note_amount']) && $formData['credit_note_amount'] == '') {
				$message = config('messages.message.creditNoteAmountRequired');
			} else if (empty($formData['credit_note_remark'])) {
				$message = config('messages.message.creditNoteRemarkRequired');
			} else {
				//Unsetting the variable from request data
				$formData = $models->unsetFormDataVariables($formData, array('_token', 'credit_note_id'));

				if (!empty($creditNoteId) && !empty($formData)) {
					if (DB::table('credit_notes')->where('credit_notes.credit_note_id', $creditNoteId)->update($formData)) {
						$error   = '1';
						$message = config('messages.message.updated');
					} else {
						$error   = '1';
						$message = config('messages.message.savedNoChange');
					}
				} else {
					$message = config('messages.message.updatedError');
				}
			}
		}
		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'credit_note_id' => $creditNoteId]);
	}

	/**********************************************
	 *Function    : Get selected customer invoices
	 *Created By  : Praveen Singh
	 *Created On  : 17-Dec-2018
	 *Modified On : 17-Dec-2018
	 **********************************************/
	public function getCustomerInvoiceNumbers($customer_id)
	{

		global $models, $debitNote;

		$error      = '0';
		$message    = '';
		$returnData = array();

		$cnWithoutInvoicingTo = DB::table('invoice_hdr')
			->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', '=', 'invoice_hdr.invoice_id')
			->where('invoice_hdr.customer_id', '=', $customer_id)
			->whereNull('invoice_hdr_detail.order_invoicing_to')
			->where('invoice_hdr.invoice_status', '=', '1')	//Active Invoice
			->orderBy('invoice_hdr.invoice_id', 'ASC')
			->pluck('invoice_hdr.invoice_no as name', 'invoice_hdr.invoice_id as id')
			->all();

		$cnWithInvoicingTo = DB::table('invoice_hdr_detail')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id')
			->where('invoice_hdr_detail.order_invoicing_to', '=', $customer_id)
			->where('invoice_hdr_detail.invoice_hdr_status', '=', '1')	///Active Invoice
			->orderBy('invoice_hdr_detail.invoice_hdr_id', 'ASC')
			->pluck('invoice_hdr.invoice_no as name', 'invoice_hdr_detail.invoice_hdr_id as id')
			->all();

		$creditNoteCustomerInvoiceNos = array_keys($cnWithoutInvoicingTo + $cnWithInvoicingTo);
		if (!empty($creditNoteCustomerInvoiceNos)) {
			$returnData = $models->convertObjectToArray(DB::table('invoice_hdr')
				->select('invoice_hdr.invoice_no as name', 'invoice_hdr.invoice_id as id')
				->whereIn('invoice_hdr.invoice_id', array_values($creditNoteCustomerInvoiceNos))
				->get());
		}

		return response()->json(['error' => $error, 'credit_note_customer_invoice' => $returnData]);
	}

	/**********************************************
	 *Function    : Viewing of Credit Note Detail Auto/Manual
	 *Created By  : Praveen Singh
	 *Created On  : 11-Dec-2018
	 *Modified On : 11-Dec-2018
	 **********************************************/
	public function viewCreditNoteDetail(Request $request)
	{

		global $creditNote, $models, $invoice, $numbersToWord;

		$error            = '0';
		$message          = config('messages.message.error');
		$data             = '';
		$creditDetailList = array();
		$credit_note_id = !empty($request->cn_id) ? $request->cn_id : '';

		if (!empty($credit_note_id)) {

			$creditNoteDetail = $creditNote->getRow($credit_note_id);

			if (!empty($creditNoteDetail->credit_note_type_id)) {
				if ($creditNoteDetail->credit_note_type_id == '1') {
					$creditDetailList = $creditNote->getAutoCreditNoteDetail($credit_note_id);
				} else {
					$creditDetailList = $creditNote->getManualCreditNoteDetail($credit_note_id);
				}
			}

			$error = !empty($creditDetailList) ? '1' : array();
			$message = '';
		}

		return response()->json(['error' => $error, 'message' => $message, 'credit_note_id' => $credit_note_id, 'creditDetailList' => $creditDetailList]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteCreditNote(Request $request, $credit_note_id)
	{

		$error   = '0';
		$message = '';
		$data    = '';

		try {
			if (DB::table('credit_notes')->where('credit_notes.credit_note_id', '=', $credit_note_id)->delete()) {
				$error   = '1';
				$message = config('messages.message.deleted');
			} else {
				$message = config('messages.message.deletedError');
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$message = config('messages.message.deletedErrorFKC');
		}
		return response()->json(['error' => $error, 'message' => $message]);
	}
}
