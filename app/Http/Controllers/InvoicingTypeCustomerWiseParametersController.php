<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Division;
use App\Item;
use App\Models;
use App\InvoicingTypeCustomerWiseParameter;
use App\State;
use Validator;
use Route;
use DB;

class InvoicingTypeCustomerWiseParametersController extends Controller
{
	/**
	 * protected Variable.
	 */
	protected $auth;

	/**
	 * Create a new controller instance.
	 * Praveen Singh
	 * @return void
	 */
	public function __construct()
	{

		global $models, $invoicingTypeCustomerWiseParameter, $state;
		$models = new Models();
		$state  = new State();
		$invoicingTypeCustomerWiseParameter = new InvoicingTypeCustomerWiseParameter();
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
	 * Praveen Singh
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('master.customer_invoicing.customer_wise_parameter_rates.index', ['title' => 'Customer Wise Product Rates', '_debit_note' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Display single customer products rate .
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getSelectedCustomerParameterRate(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseParameter;

		$error    	= '0';
		$message  	= config('messages.message.error');
		$data     	= '';
		$invocingTypeId = '4';
		$formData 	= $parametersRateList = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//parsing form data
			parse_str($request->formData, $formData);
			$formData = array_filter($formData);

			if (!empty($formData['parameter_category_id'])) {

				$cir_customer_id        = !empty($formData['cir_customer_id']) ? $formData['cir_customer_id'] : '';
				$product_category_id    = !empty($formData['cir_product_category_id']) ? $formData['cir_product_category_id'] : '';
				$parameter_category_id  = !empty($formData['parameter_category_id']) ? $formData['parameter_category_id'] : '';

				$parametersRateList = $invoicingTypeCustomerWiseParameter->getAllParameters($product_category_id, $parameter_category_id);
				$testStandardList   = DB::table('test_standard')->select('test_standard.test_std_id', 'test_standard.test_std_name')->where('test_standard.product_category_id', '=', $product_category_id)->orderBy('test_standard.test_std_name', 'ASC')->get();

				if (!empty($parametersRateList)) {
					foreach ($parametersRateList as $key => $parametersRates) {
						$parametersRates->test_standard = $testStandardList;
					}
				}
			}
		}
		$error    	    = !empty($parametersRateList) ? 1 : '0';
		$message            = $error ? '' : config('messages.message.categoryParametersNotFound');

		//echo'<pre>';print_r($parametersRateList); die;
		return response()->json(array('error' => $error, 'message' => $message, 'parametersRateList' => $parametersRateList));
	}

	/**
	 * Store a newly created resource in storage.
	 * Praveen Singh
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function createCustomerWiseParametersRate(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseParameter;

		$error    	= '0';
		$message  	= config('messages.message.error');
		$data     	= '';
		$invocingTypeId = '4';
		$formData 	= array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//parsing form data
			parse_str($request->formData, $formData);

			if (!empty($formData)) {

				if (empty($formData['cir_customer_id'])) {
					$message = config('messages.message.customerNameRequired');
				} else if (empty($formData['cir_division_id'])) {
					$message = config('messages.message.divisionRequired');
				} else if (empty($formData['cir_product_category_id'])) {
					$message = config('messages.message.productCatNameRequired');
				} else if (empty($formData['cir_parameter_id'])) {
					$message = config('messages.message.parameterRequired');
				} else if (empty(array_filter($formData['invoicing_rate']))) {
					$message = config('messages.message.invoicingRateRequired');
				} else {

					$formData 	       	= $models->unsetFormDataVariables($formData, array('_token', 'parameter_category_id'));  //Unsetting the variable from request data
					$invoicingRateData 	= $models->formDataVariables($formData, $invoicingTypeId = 4);

					if (!empty($invoicingRateData)) {
						foreach ($invoicingRateData as $key => $invoicingRates) {
							$customerWiseParameters = DB::table('customer_invoicing_rates')
								->where('customer_invoicing_rates.cir_customer_id', $formData['cir_customer_id'])
								->where('customer_invoicing_rates.invoicing_type_id', $invoicingRates['invoicing_type_id'])
								->where('customer_invoicing_rates.cir_product_category_id', $invoicingRates['cir_product_category_id'])
								->where('customer_invoicing_rates.cir_parameter_id', $invoicingRates['cir_parameter_id'])
								->where('customer_invoicing_rates.cir_equipment_type_id', $invoicingRates['cir_equipment_type_id'])
								->first();

							if (!empty($customerWiseParameters->cir_id) && !empty($invoicingRates['invoicing_rate']) && $invoicingRates['invoicing_rate'] != '0.00') {
								$status[] = DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_id', '=', $customerWiseParameters->cir_id)->update(['customer_invoicing_rates.invoicing_rate' => $invoicingRates['invoicing_rate'], 'customer_invoicing_rates.cir_test_standard_id' => $invoicingRates['cir_test_standard_id']]);
							} else {
								$status[] = !empty($invoicingRates['invoicing_rate']) && $invoicingRates['invoicing_rate'] != '0.00' ? DB::table('customer_invoicing_rates')->insertGetId($invoicingRates) : '0';
							}
						}
					}
					if (!empty($status)) {
						$error   = '1';
						$message = config('messages.message.saved');
					} else {
						$message = config('messages.message.error');
					}
				}
			}
		}
		return response()->json(array('error' => $error, 'message' => $message));
	}

	/**
	 * Display the specified resource.
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getCustomerWiseParametersRates(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseParameter;

		$error    	 	 = '0';
		$message  	 	 = config('messages.message.error');
		$data     	 	 = '';
		$invocingTypeId  = '4';
		$cir_customer_id = '0';
		$formData 	 	 = $leftSideDataList = $rightSideDataList = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//parsing form data
			parse_str($request->formData, $formData);

			if (!empty($formData)) {

				$cir_customer_id   		= !empty($formData['cir_customer_id']) ? $formData['cir_customer_id'] : '0';
				$cirProductCategoryId   = !empty($formData['dept_id']) ? $formData['dept_id'] : '1';
				$cirDivisionId   		= !empty($formData['division_id']) ? $formData['division_id'] : '1';
				$cirSearchKeyword  		= !empty($formData['cir_search_keyword']) ? $formData['cir_search_keyword'] : '';

				//***************Left Side Data************************************************************
				$leftSideDataListObj  = DB::table('customer_invoicing_rates')
					->select('customer_master.customer_id', 'customer_master.customer_name', 'customer_invoicing_rates.cir_customer_id')
					->join('customer_master', 'customer_master.customer_id', 'customer_invoicing_rates.cir_customer_id')
					->where('customer_invoicing_rates.invoicing_type_id', $invocingTypeId);

				if (!empty($cirProductCategoryId)) {
					$leftSideDataListObj->where('customer_invoicing_rates.cir_product_category_id', $cirProductCategoryId);
				}
				if (!empty($cirDivisionId)) {
					$leftSideDataListObj->where('customer_invoicing_rates.cir_division_id', $cirDivisionId);
				}
				if (!empty($cirSearchKeyword)) {
					$leftSideDataListObj->where('customer_master.customer_name', 'LIKE', '%' . $cirSearchKeyword . '%');
				} else {
					$leftSideDataListObj->whereMonth('customer_invoicing_rates.created_at', date('m'));
					$leftSideDataListObj->whereYear('customer_invoicing_rates.created_at', date('Y'));
				}
				$leftSideDataList 		 = $leftSideDataListObj->groupBy('customer_master.customer_name')->orderBy('customer_master.customer_name', 'ASC')->get()->toArray();
				$default_cir_customer_id = !empty($leftSideDataList[0]->cir_customer_id) ? $leftSideDataList[0]->cir_customer_id : '0';
				//***************/Left Side Data************************************************************

				//***************Right Side Data************************************************************
				if (!empty($leftSideDataList)) {
					$cir_customer_id  = !empty($cir_customer_id) ? $cir_customer_id : $default_cir_customer_id;
					$rightSideDataObj = DB::table('customer_invoicing_rates')
						->select('customer_invoicing_rates.*', 'product_categories.p_category_name', 'test_parameter_categories.test_para_cat_name', 'test_parameter.test_parameter_name', 'test_standard.test_std_name', 'equipment_type.equipment_name')
						->leftJoin('equipment_type', 'equipment_type.equipment_id', 'customer_invoicing_rates.cir_equipment_type_id')
						->join('test_parameter', 'customer_invoicing_rates.cir_parameter_id', '=', 'test_parameter.test_parameter_id')
						->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', '=', 'test_parameter_categories.test_para_cat_id')
						->join('product_categories', 'product_categories.p_category_id', '=', 'test_parameter_categories.product_category_id')
						->join('users as createdBy', 'createdBy.id', 'customer_invoicing_rates.created_by')
						->leftJoin('test_standard', 'test_standard.test_std_id', 'customer_invoicing_rates.cir_test_standard_id')
						->where('customer_invoicing_rates.invoicing_type_id', $invocingTypeId);

					if (!empty($cir_customer_id) && is_numeric($cir_customer_id)) {
						$rightSideDataObj->where('customer_invoicing_rates.cir_customer_id', $cir_customer_id);
					}
					if (!empty($cirProductCategoryId)) {
						$rightSideDataObj->where('customer_invoicing_rates.cir_product_category_id', $cirProductCategoryId);
					}
					if (!empty($cirDivisionId)) {
						$rightSideDataObj->where('customer_invoicing_rates.cir_division_id', $cirDivisionId);
					}
					$rightSideDataList = $rightSideDataObj->orderBy('customer_invoicing_rates.cir_id', 'ASC')->get()->toArray();
				}
				//**************/Right Side Data************************************************************
			}
		}

		$returnData = array('leftSideDataList' => $leftSideDataList, 'rightSideDataList' => $rightSideDataList);
		$error      = !empty($returnData['leftSideDataList']) ? 1 : '0';
		$message    = $error ? '' : $message;

		//to formate created and updated date
		!empty($returnData['rightSideDataList']) ? $models->formatTimeStampFromArray($returnData['rightSideDataList'], DATETIMEFORMAT) : '';

		//echo '<pre>';print_r($returnData);die;
		return response()->json(array('error' => $error, 'message' => $message, 'returnData' => $returnData, 'cir_customer_id' => $cir_customer_id));
	}

	/**
	 * Display single customer products rate .
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function editSelectedCustomerParameterRate(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseParameter;

		$error    	 = '0';
		$message  	 = config('messages.message.error');
		$data     	 = '';
		$invocingTypeId  = '4';
		$cir_customer_id = '0';
		$formData 	 = $allSelectedTestStandard = array();

		//Saving record in table
		if (!empty($request->formData) && $request->isMethod('post')) {

			//parsing form data
			parse_str($request->formData, $formData);

			if (!empty($formData['cir_customer_id'])) {
				$cir_customer_id   		   = !empty($formData['cir_customer_id']) ? $formData['cir_customer_id'] : '0';
				$customersList			   = DB::table('customer_master')->select('customer_id as id', 'customer_name as name')->where('customer_id', $cir_customer_id)->first();
				$customerParameterRateList = $invoicingTypeCustomerWiseParameter->getSelectedCustomerParametersRateList($cir_customer_id, $invocingTypeId);

				if (!empty($customerParameterRateList)) {
					foreach ($customerParameterRateList as $key => $customerParameterRates) {
						$allSelectedTestStandard[$customerParameterRates->cir_id] = $customerParameterRates->cir_test_standard_id;
						$customerParameterRates->testStandardList = DB::table('test_standard')->select('test_standard.test_std_id', 'test_standard.test_std_name')->where('test_standard.product_category_id', '=', $customerParameterRates->cir_product_category_id)->orderBy('test_standard.test_std_name', 'ASC')->get();
					}
				}
			}
		}

		$data 	 		 		 = !empty($customerParameterRateList) ? $customerParameterRateList : array();
		$allSelectedTestStandard = !empty($allSelectedTestStandard) ? array_filter($allSelectedTestStandard) : array();
		$error   		 		 = !empty($customerParameterRateList) ? 1 : '0';
		$message 		 		 = $error ? '' : $message;

		return response()->json(array('error' => $error, 'customersList' => $customersList, 'returnData' => $data, 'allSelectedTestStandard' => $allSelectedTestStandard));
	}

	/**
	 * Show the form for editing the specified resource.
	 * Praveen Singh
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function UpdateCustomerWiseParametersRate(Request $request)
	{

		global $models, $invoicingTypeCustomerWiseParameter;

		$error    = '0';
		$message  = config('messages.message.error');
		$data     = $updated = '';
		$formData = array();

		if ($request->isMethod('post') && !empty($request['formData'])) {

			//parsing searlize data
			parse_str($request['formData'], $formData);

			if (empty(array_filter($formData['invoicing_rate']))) {
				$message = config('messages.message.invoicingRateRequired');
			} else {
				if (!empty($formData['invoicing_rate'])) {
					foreach ($formData['invoicing_rate'] as $cirIdKey => $invoicingRate) {
						$updated = DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_id', str_replace("'", "", $cirIdKey))->update(['customer_invoicing_rates.invoicing_rate' => $invoicingRate]);
						$error   = '1';
					}
				}
				if (!empty($formData['cir_test_standard_id'])) {
					foreach ($formData['cir_test_standard_id'] as $cirIdKey => $cirTestStandardId) {
						$updated = DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_id', str_replace("'", "", $cirIdKey))->update(['customer_invoicing_rates.cir_test_standard_id' => $cirTestStandardId]);
						$error   = '1';
					}
				}
			}
		}

		if ($error) {
			$message = config('messages.message.saved');
		} else {
			$message = config('messages.message.error');
		}

		//echo'<pre>';print_r($formData); die;
		return response()->json(['error' => $error, 'message' => $message]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteCustomerWiseParameterRate(Request $request, $cir_id)
	{

		$error   = '0';
		$message = '';
		$data    = '';

		try {
			if (DB::table('customer_invoicing_rates')->where('customer_invoicing_rates.cir_id', '=', $cir_id)->delete()) {
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

	/**
	 * Display customers list that contain parameters in customer_invoicing_rates table
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getParameterCustomerList()
	{
		$parameterCustomersList = DB::table('customer_master')
			->join('customer_invoicing_rates', 'customer_invoicing_rates.cir_customer_id', 'customer_master.customer_id')

			->select('customer_master.customer_id', 'customer_master.customer_name', 'customer_invoicing_rates.cir_product_category_id', 'customer_invoicing_rates.cir_id')
			->where('customer_invoicing_rates.invoicing_type_id', 4)
			->groupBy('customer_master.customer_id')
			->orderBy('customer_master.customer_name')
			->get();
		//	echo'<pre>'; print_r($parameterCustomersList); die;
		return json_encode(['parameterCustomersList' => $parameterCustomersList]);
	}
}
