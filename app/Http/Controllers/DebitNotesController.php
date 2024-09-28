<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Models;
use App\DebitNote;
use App\NumbersToWord;
use App\InvoiceHdr;
use Session;
use Validator;
use Route;
use DB;

class DebitNotesController extends Controller
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

		global $debitNote, $models, $numbersToWord, $invoice;

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
		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('payments.debit_notes.index', ['title' => 'Debit Notes', '_debit_note' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function debitNoteNumber()
	{

		global $models, $debitNote;

		$error   = '0';
		$message = '';
		$data    = '';

		$debitNoteNumber = $debitNote->generateDebitNoteNumber('DN');
		return response()->json(array('error' => $error, 'message' => $message, 'debitNoteNumber' => $debitNoteNumber));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function addDebitNote(Request $request)
	{

		global $debitNote, $models, $invoice;

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
					$message = config('messages.message.divisionNameDNErrorMsg');
				} else if (empty($formData['product_category_id'])) {
					$message = config('messages.message.departmentNameDNRequired');
				} else if (empty($formData['customer_id'])) {
					$message = config('messages.message.customerNameDNRequired');
				} else if (isset($formData['invoice_id']) && empty($formData['invoice_id'])) {
					$message = config('messages.message.customerInvoiceNumberRequired');
				} else if (isset($formData['debit_reference_no']) && empty($formData['debit_reference_no'])) {
					$message = config('messages.message.customerDebitRefNoRequired');
				} else if (empty($formData['debit_note_no'])) {
					$message = config('messages.message.debitNoteNoRequired');
				} else if ($debitNote->checkDebitNoteNumber($formData['debit_note_no'])) {
					$message = config('messages.message.debitNoteNoExist');
				} else if (empty($formData['debit_note_date'])) {
					$message = config('messages.message.debitNoteDateRequired');
				} else if (isset($formData['debit_note_amount']) && $formData['debit_note_amount'] == '') {
					$message = config('messages.message.debitNoteAmountRequired');
				} else if (empty($formData['debit_note_remark'])) {
					$message = config('messages.message.debitNoteRemarkRequired');
				} else {
					try {
						//Getting Tax SLab of the Customer Credit Notes
						$formData = $debitNote->calculateDebitNoteTaxSlab($formData);

						//Unsetting the variable from request data
						$formData = $models->unsetFormDataVariables($formData, array('_token'));
						$formData['debit_note_date']    = $models->convertDateFormat($formData['debit_note_date']);
						$formData['debit_note_type_id'] = '2';
						$formData['created_by']         = USERID;

						if (!empty($formData['debit_note_no'])) {
							$debitNoteId = DB::table('debit_notes')->insertGetId($formData);
							if (!empty($debitNoteId)) {
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
	public function getDivisionWiseDebitNotes(Request $request)
	{

		global $models, $debitNote;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = !empty($request->formData) ?  $request->formData : '';

		if (!empty($formData)) {

			//Parsing Form Data
			parse_str($formData, $formData);

			//Format Data
			$debitNoteSearch 	=   !empty($formData['keyword']) ?  trim($formData['keyword']) : '0';

			$dataObj = DB::table('debit_notes')
				->join('divisions', 'divisions.division_id', 'debit_notes.division_id')
				->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'debit_notes.product_category_id')
				->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
				->join('customer_master', 'customer_master.customer_id', 'debit_notes.customer_id')
				->leftJoin('invoice_hdr', 'invoice_hdr.invoice_id', 'debit_notes.invoice_id')
				->join('users as createdBy', 'createdBy.id', 'debit_notes.created_by')
				->select('invoice_hdr.invoice_no as invoice_number', 'debit_notes.*', 'divisions.division_name', 'departments.department_name', 'customer_master.customer_name', 'createdBy.name as createdByName');

			if (!empty($formData['division_id']) && is_numeric($formData['division_id'])) {
				$dataObj->where('debit_notes.division_id', $formData['division_id']);
			}
			if (!empty($debitNoteSearch)) {
				$dataObj->where('customer_master.customer_name', 'LIKE', '%' . $debitNoteSearch . '%')
					->orWhere('customer_master.customer_code', 'LIKE', '%' . $debitNoteSearch . '%')
					->orWhere('debit_notes.debit_reference_no', 'LIKE', '%' . $debitNoteSearch . '%')
					->orWhere('departments.department_name', 'LIKE', '%' . $debitNoteSearch . '%')
					->orWhere('debit_notes.debit_note_remark', 'LIKE', '%' . $debitNoteSearch . '%')
					->orWhere('debit_notes.debit_note_no', 'LIKE', '%' . $debitNoteSearch . '%')
					->orWhere('createdBy.name', 'LIKE', '%' . $debitNoteSearch . '%');
			} else {
				$dataObj->whereMonth('debit_notes.debit_note_date', date('m'));
				$dataObj->whereYear('debit_notes.debit_note_date', date('Y'));
			}
			$this->getDebitNotesMultisearch($dataObj, $formData);
			$debitNotesList   = $dataObj->orderBy('debit_notes.debit_note_id', 'DESC')->get();
		}
		$error            = !empty($debitNotesList) ? 1 : '0';
		$message          = $error ? '' : $message;

		//to formate created and updated date		   
		$models->formatTimeStampFromArray($debitNotesList, DATETIMEFORMAT);

		return response()->json(array('error' => $error, 'message' => $message, 'debitNotesList' => $debitNotesList));
	}



	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response excel download
	 */
	public function generateDebitNotesMasterDocument(Request $request)
	{

		global $models, $debitNote;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';

		$division_id        = !empty($request->division_id) ? $request->division_id : '0';
		$debitNoteSearch    = !empty($request->filterDebitNoteSearch) ?  trim($request->filterDebitNoteSearch) : '0';
		$debitNoteNo        = !empty($request->search_debit_note_no) ?  trim($request->search_debit_note_no) : '0';
		$division           = !empty($request->search_division_id) ?  trim($request->search_division_id) : '0';
		$department         = !empty($request->search_department_name) ?  trim($request->search_department_name) : '0';
		$customerName       = !empty($request->search_customer_name) ?  trim($request->search_customer_name) : '0';
		$debitNoteDate      = !empty($request->search_debit_note_date) ?  trim(date("Y-m-d", strtotime($request->search_debit_note_date))) : '0';
		$debitNoteAmount    = !empty($request->search_debit_note_amount) ?  trim($request->search_debit_note_amount) : '0';
		$debitNoteRemarks   = !empty($request->search_debit_note_remark) ?  trim($request->search_debit_note_remark) : '0';
		$debitNoteCreatedBy = !empty($request->search_created_by) ?  trim($request->search_created_by) : '0';

		$dataObj = DB::table('debit_notes')
			->join('divisions', 'divisions.division_id', 'debit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'debit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'debit_notes.customer_id')
			->leftJoin('invoice_hdr', 'invoice_hdr.invoice_id', 'debit_notes.invoice_id')
			->join('users as createdBy', 'createdBy.id', 'debit_notes.created_by')
			->select('debit_notes.debit_note_no', 'divisions.division_name as branch', 'departments.department_name as department', 'customer_master.customer_name', 'invoice_hdr.invoice_no as invoice_number', 'debit_notes.debit_reference_no as reference_no', 'debit_notes.debit_note_date', 'debit_notes.debit_note_amount as amount', 'debit_notes.debit_note_remark as remark', 'createdBy.name as created_by', 'debit_notes.created_at', 'debit_notes.updated_at');

		if (!empty($division_id) && is_numeric($division_id)) {
			$dataObj->where('debit_notes.division_id', $division_id);
		}
		if (!empty($debitNoteSearch)) {
			$dataObj->orwhere('debit_notes.debit_note_no', 'LIKE', '%' . $debitNoteSearch . '%')
				->orWhere('divisions.division_name', 'LIKE', '%' . $debitNoteSearch . '%')
				->orWhere('departments.department_name', 'LIKE', '%' . $debitNoteSearch . '%')
				->orWhere('customer_master.customer_name', 'LIKE', '%' . $debitNoteSearch . '%')
				->orWhere('debit_notes.debit_reference_no', 'LIKE', '%' . $debitNoteSearch . '%')
				->orWhere('debit_notes.debit_note_remark', 'LIKE', '%' . $debitNoteSearch . '%')
				->orWhere('debit_notes.debit_note_no', 'LIKE', '%' . $debitNoteSearch . '%')
				->orWhere('createdBy.name', 'LIKE', '%' . $debitNoteSearch . '%');
		}
		if (!empty($debitNoteNo)) {
			$dataObj->where('debit_notes.debit_note_no', 'LIKE', '%' . $debitNoteNo . '%');
		}
		if (!empty($division)) {
			$dataObj->where('divisions.division_name', 'LIKE', '%' . $division . '%');
		}
		if (!empty($department)) {
			$dataObj->where('departments.department_name', 'LIKE', '%' . $department . '%');
		}
		if (!empty($customerName)) {
			$dataObj->where('customer_master.customer_name', 'LIKE', '%' . $customerName . '%');
		}
		if (!empty($debitNoteDate)) {
			$dataObj->where('debit_notes.debit_note_date', 'LIKE', '%' . $debitNoteDate . '%');
		}
		if (!empty($debitNoteAmount)) {
			$dataObj->where('debit_notes.debit_note_amount', 'LIKE', '%' . $debitNoteAmount . '%');
		}
		if (!empty($debitNoteRemarks)) {
			$dataObj->where('debit_notes.debit_note_remark', 'LIKE', '%' . $debitNoteRemarks . '%');
		}
		if (!empty($debitNoteCreatedBy)) {
			$dataObj->where('createdBy.name', 'LIKE', '%' . $debitNoteCreatedBy . '%');
		}
		$debitNotesData   = $dataObj->orderBy('debit_notes.debit_note_id', 'DESC')->get();
		$models->formatTimeStampFromArrayExcel($debitNotesData);

		$debitNotesDataList			= !empty($debitNotesData) ? json_decode(json_encode($debitNotesData), true) : array();
		$responseData['heading'] 		= 'Debit Note List :' . '(' . count($debitNotesDataList) . ')';
		$responseData['mis_report_name'] 	= 'Debit Note List';
		$responseData['tableHead'] 		= !empty($debitNotesDataList) ? array_keys(end($debitNotesDataList)) : array();
		$responseData['tableBody'] 		= !empty($debitNotesDataList) ? $debitNotesDataList : array();

		if (!empty($request->generate_debit_notes_documents) && $request->generate_debit_notes_documents == 'Excel') {
			return $models->generateExcel($responseData);
		} else {
			return redirect('dashboard')->withErrors('Permission Denied!');
		}
	}

	/**
	 * get patment debit notes using multisearch.
	 * Date : 30-05-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getDebitNotesMultisearch($dataObj, $searchArry)
	{

		global $models;

		if (!empty($searchArry['search_debit_note_no'])) {
			$dataObj->where('debit_notes.debit_note_no', 'like', '%' . trim($searchArry['search_debit_note_no']) . '%');
		}
		if (!empty($searchArry['search_division_id'])) {
			$dataObj->where('divisions.division_id', '=', trim($searchArry['search_division_id']));
		}
		if (!empty($searchArry['search_department_name'])) {
			$dataObj->where('departments.department_name', '=', trim($searchArry['search_department_name']));
		}
		if (!empty($searchArry['search_customer_name'])) {
			$dataObj->where('customer_master.customer_name', 'like', '%' . trim($searchArry['search_customer_name']) . '%');
		}
		if (!empty($searchArry['search_debit_note_date'])) {
			$dataObj->where('debit_notes.debit_note_date', 'like', '%' . (date("Y-m-d", strtotime(trim($searchArry['search_debit_note_date'])))) . '%');
		}
		if (!empty($searchArry['search_debit_note_amount'])) {
			$dataObj->where('debit_notes.debit_note_amount', 'like', '%' . trim($searchArry['search_debit_note_amount']) . '%');
		}
		if (!empty($searchArry['search_debit_note_remark'])) {
			$dataObj->where('debit_notes.debit_note_remark', 'like', '%' . trim($searchArry['search_debit_note_remark']) . '%');
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
	public function viewDebitNote($debit_note_id)
	{

		global $models, $debitNote;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';

		$debitNoteData = DB::table('debit_notes')
			->join('divisions', 'divisions.division_id', 'debit_notes.division_id')
			->join('department_product_categories_link', 'department_product_categories_link.product_category_id', 'debit_notes.product_category_id')
			->join('departments', 'departments.department_id', 'department_product_categories_link.department_id')
			->join('customer_master', 'customer_master.customer_id', 'debit_notes.customer_id')
			->join('invoice_hdr', 'invoice_hdr.customer_id', 'debit_notes.customer_id')
			->join('users as createdBy', 'createdBy.id', 'debit_notes.created_by')
			->select('invoice_hdr.invoice_no as invoice_number', 'invoice_hdr.invoice_id', 'debit_notes.*', 'divisions.division_name', 'departments.department_name', 'customer_master.customer_name', 'createdBy.name as createdByName')
			->where('debit_notes.debit_note_id', $debit_note_id)
			->orderBy('debit_notes.debit_note_id', 'DESC')
			->first();

		$error    = !empty($debitNoteData) ? 1 : '0';
		$message  = $error ? '' : $message;

		return response()->json(array('error' => $error, 'message' => $message, 'debitNoteData' => $debitNoteData));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateDebitNote(Request $request)
	{

		global $models, $debitNote;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = '';
		$formData = array();

		if ($request->isMethod('post') && !empty($request['formData'])) {

			//pasrse searlize data 				
			parse_str($request['formData'], $formData);
			$debitNoteId = !empty($formData['debit_note_id']) ? $formData['debit_note_id'] : '0';

			if (empty($formData['division_id'])) {
				$message = config('messages.message.divisionNameDNErrorMsg');
			} else if (empty($formData['product_category_id'])) {
				$message = config('messages.message.departmentNameDNRequired');
			} else if (empty($formData['customer_id'])) {
				$message = config('messages.message.customerNameDNRequired');
			} else if (empty($formData['invoice_id'])) {
				$message = config('messages.message.customerInvoiceNumberRequired');
			} else if (empty($formData['debit_note_date'])) {
				$message = config('messages.message.debitNoteDateRequired');
			} else if (isset($formData['debit_note_amount']) && $formData['debit_note_amount'] == '') {
				$message = config('messages.message.debitNoteAmountRequired');
			} else if (empty($formData['debit_note_remark'])) {
				$message = config('messages.message.debitNoteRemarkRequired');
			} else {
				//Unsetting the variable from request data
				$formData = $models->unsetFormDataVariables($formData, array('_token', 'debit_note_id'));
				if (!empty($debitNoteId) && !empty($formData)) {
					if (DB::table('debit_notes')->where('debit_notes.debit_note_id', $debitNoteId)->update($formData)) {
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
		return response()->json(['error' => $error, 'message' => $message, 'data' => $data, 'debit_note_id' => $debitNoteId]);
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

		$dnWithoutInvoicingTo = DB::table('invoice_hdr')
			->join('invoice_hdr_detail', 'invoice_hdr_detail.invoice_hdr_id', '=', 'invoice_hdr.invoice_id')
			->where('invoice_hdr.customer_id', '=', $customer_id)
			->whereNull('invoice_hdr_detail.order_invoicing_to')
			->orderBy('invoice_hdr.invoice_id', 'ASC')
			->pluck('invoice_hdr.invoice_no as name', 'invoice_hdr.invoice_id as id')
			->all();

		$dnWithInvoicingTo = DB::table('invoice_hdr_detail')
			->join('invoice_hdr', 'invoice_hdr.invoice_id', '=', 'invoice_hdr_detail.invoice_hdr_id')
			->where('invoice_hdr_detail.order_invoicing_to', '=', $customer_id)
			->orderBy('invoice_hdr_detail.invoice_hdr_id', 'ASC')
			->pluck('invoice_hdr.invoice_no as name', 'invoice_hdr_detail.invoice_hdr_id as id')
			->all();

		$debitNoteCustomerInvoiceNos = array_keys($dnWithoutInvoicingTo + $dnWithInvoicingTo);
		if (!empty($debitNoteCustomerInvoiceNos)) {
			$returnData = $models->convertObjectToArray(DB::table('invoice_hdr')
				->select('invoice_hdr.invoice_no as name', 'invoice_hdr.invoice_id as id')
				->whereIn('invoice_hdr.invoice_id', array_values($debitNoteCustomerInvoiceNos))
				->orderBy('invoice_hdr.invoice_id', 'ASC')
				->get());
		}

		return response()->json(['error' => $error, 'debit_note_customer_invoice' => $returnData]);
	}

	/**********************************************
	 *Function    : Viewing of Debit Note Detail Auto/Manual
	 *Created By  : Praveen Singh
	 *Created On  : 11-Dec-2018
	 *Modified On : 11-Dec-2018
	 **********************************************/
	public function viewDebitNoteDetail(Request $request)
	{

		global $models, $debitNote, $invoice, $numbersToWord;

		$error           = '0';
		$message         = config('messages.message.error');
		$data            = '';
		$debitDetailList = array();
		$debit_note_id   = !empty($request->dn_id) ? $request->dn_id : '';

		if (!empty($debit_note_id)) {

			$debitNoteDetail = $debitNote->getRow($debit_note_id);

			if (!empty($debitNoteDetail->debit_note_type_id)) {
				if ($debitNoteDetail->debit_note_type_id == '1') {
					$debitDetailList = $debitNote->getAutoDebitNoteDetail($debit_note_id);
				} else {
					$debitDetailList = $debitNote->getManualDebitNoteDetail($debit_note_id);
				}
			}

			$error = !empty($debitDetailList) ? '1' : array();
			$message = '';
		}

		//echo '<pre>';print_r($debitNoteData);echo '</pre>';die;
		return response()->json(['error' => $error, 'message' => $message, 'debit_note_id' => $debit_note_id, 'debitDetailList' => $debitDetailList]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteDebitNote(Request $request, $debit_note_id)
	{

		$error   = '0';
		$message = '';
		$data    = '';

		try {
			if (DB::table('debit_notes')->where('debit_notes.debit_note_id', '=', $debit_note_id)->delete()) {
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
