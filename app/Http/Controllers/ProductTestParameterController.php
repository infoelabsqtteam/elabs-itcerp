<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\ProductTestParameter;
use Validator;
use Route;
use DB;

class ProductTestParameterController extends Controller
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

		global $models, $productTestParameter;

		$models = new Models();
		$productTestParameter = new ProductTestParameter();

		//checking auth login
		$this->middleware('auth');

		$this->middleware(function ($request, $next) {
			$this->session = Auth::user();
			parent::__construct($this->session);
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

	/************************************
	 * Description : Get list of test_parameters on page load
	 * Date        : 01-09-17
	 * Author      : nisha
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function getProductTestParametersList(Request $request)
	{

		global $models, $productTestParameter;

		if ($request->isMethod('post') && !empty($request['data']['id'])) {

			$testParameters = DB::table('product_test_dtl')
				->leftjoin('detector_master', 'detector_master.detector_id', 'product_test_dtl.detector_id')
				->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
				->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
				->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', 'test_parameter_categories.test_para_cat_id')
				->leftjoin('equipment_type', 'product_test_dtl.equipment_type_id', 'equipment_type.equipment_id')
				->leftjoin('method_master', 'product_test_dtl.method_id', 'method_master.method_id')
				->join('users', 'product_test_dtl.created_by', 'users.id')
				->select('detector_master.detector_name', 'detector_master.detector_id', 'test_parameter_categories.test_para_cat_name', 'product_test_dtl.*', 'product_test_hdr.test_code', 'equipment_type.equipment_name', 'equipment_type.equipment_id', 'test_parameter.test_parameter_name', 'method_master.method_name', 'users.name as createdBy', 'test_parameter.test_parameter_category_id')
				->where('product_test_dtl.test_id', $request['data']['id'])
				->orderBy('product_test_dtl.parameter_sort_by', 'ASC')
				->get()
				->toArray();

			$models->formatTimeStampFromArray($testParameters, DATETIMEFORMAT);
			$returnData	= array('allParaList' => $testParameters, 'allParaList_test_id' => $request['data']['id']);
		} else {
			$returnData = array('error' => config('messages.message.dataNotFound'));
		}

		return response()->json($returnData);
	}

	/************************************
	 * Description : Get list of test_parameters on page load
	 * Date        : 01-09-17
	 * Author      : nisha
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function getProductTestParametersListMultisearch(Request $request)
	{

		global $models, $productTestParameter;

		$searchArry = $request['data']['formData'];

		$testParametersObj = DB::table('product_test_dtl')
			->join('product_test_hdr', 'product_test_dtl.test_id', 'product_test_hdr.test_id')
			->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
			->join('test_parameter_categories', 'test_parameter.test_parameter_category_id', 'test_parameter_categories.test_para_cat_id')
			->leftjoin('equipment_type', 'product_test_dtl.equipment_type_id', 'equipment_type.equipment_id')
			->leftjoin('method_master', 'product_test_dtl.method_id', 'method_master.method_id')
			->leftjoin('detector_master', 'product_test_dtl.detector_id', 'detector_master.detector_id')
			->join('users', 'product_test_dtl.created_by', 'users.id')
			->select('detector_master.detector_name', 'product_test_dtl.*', 'product_test_hdr.test_code', 'equipment_type.equipment_name', 'test_parameter.test_parameter_name', 'method_master.method_name', 'users.name as createdBy', 'test_parameter.test_parameter_category_id', 'test_parameter_categories.test_para_cat_name');

		if (!empty($searchArry['search_test_parameter_name'])) {
			$testParametersObj->where('test_parameter.test_parameter_name', 'like', '%' . $searchArry['search_test_parameter_name'] . '%');
		}
		if (!empty($searchArry['search_equipment_name'])) {
			$testParametersObj->where('equipment_type.equipment_name', 'like', '%' . $searchArry['search_equipment_name'] . '%');
		}
		if (!empty($searchArry['search_detector_name'])) {
			$testParametersObj->where('detector_master.detector_name', 'like', '%' . $searchArry['search_detector_name'] . '%');
		}
		if (!empty($searchArry['search_method_name'])) {
			$testParametersObj->where('method_master.method_name', 'like', '%' . $searchArry['search_method_name'] . '%');
		}
		if (!empty($searchArry['search_time_taken_days'])) {
			$testParametersObj->where('product_test_dtl.time_taken_days', 'like', '%' . $searchArry['search_time_taken_days'] . '%');
		}
		if (!empty($searchArry['search_time_taken_mins'])) {
			$testParametersObj->where('product_test_dtl.time_taken_mins', 'like', '%' . $searchArry['search_time_taken_mins'] . '%');
		}
		if (!empty($searchArry['search_standard_value_from'])) {
			$testParametersObj->where('product_test_dtl.standard_value_from', 'like', '%' . $searchArry['search_standard_value_from'] . '%');
		}
		if (!empty($searchArry['search_standard_value_to'])) {
			$testParametersObj->where('product_test_dtl.standard_value_to', 'like', '%' . $searchArry['search_standard_value_to'] . '%');
		}
		if (!empty($searchArry['search_cost_price'])) {
			$testParametersObj->where('product_test_dtl.cost_price', 'like', '%' . $searchArry['search_cost_price'] . '%');
		}
		if (!empty($searchArry['search_selling_price'])) {
			$testParametersObj->where('product_test_dtl.selling_price', 'like', '%' . $searchArry['search_selling_price'] . '%');
		}
		if (!empty($searchArry['search_created_by'])) {
			$testParametersObj->where('users.name', 'like', '%' . $searchArry['search_created_by'] . '%');
		}
		if (!empty($searchArry['search_status'])) {
			$testParametersObj->where('product_test_dtl.status', '=', $searchArry['search_status']);
		}
		if (!empty($searchArry['search_created_at'])) {
			$testParametersObj->where('product_test_dtl.created_at', 'like', '%' . $searchArry['search_created_at'] . '%');
		}

		$testParameters = $testParametersObj->where('product_test_dtl.test_id', $searchArry['search_test_id'])->get();
		$models->formatTimeStampFromArray($testParameters, DATETIMEFORMAT);

		$returnData = array('allParaList' => $testParameters, 'allParaList_test_id' => $searchArry['search_test_id']);
		return response()->json($returnData);
	}

	/************************************
	 * Description : create new product-test-parameters of test parameter category
	 * Date        : 01-09-17
	 * Author      : nisha
	 * Parameter   : \Illuminate\Http\Request  $request
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function createProductTestParameters(Request $request)
	{

		global $models, $productTestParameter;

		$returnData = $newPostData = array();

		if ($request->isMethod('post') && !empty($request['data']['formData'])) {

			//pasrse searlize data
			parse_str($request['data']['formData'], $newPostData);

			$parameter_name   = $models->getParameterName($newPostData['test_parameter_id']);
			$parameterDescArr = array('description', 'reference to protocol');
			$nullValueArr     = array('na', 'n/a', '', 'NA', 'N/a', 'n/A');

			if (empty($newPostData['current_test_id'])) {
				$returnData = array('error' => config('messages.message.testCodeRequired'));
			} else if (empty($parameter_name)) {
				$returnData = array('error' => config('messages.message.parameterNotFound'));
			} else if (empty($newPostData['test_parameter_id'])) {
				$returnData = array('error' => config('messages.message.parameterRequired'));
			} else if (in_array(strtolower($parameter_name), $parameterDescArr) && empty($newPostData['description'])) {
				$returnData = array('error' => config('messages.message.descriptionRequired'));
			} else if (empty($newPostData['standard_value_type']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.stdValueTypeRequired'));
			} else if (!isset($newPostData['standard_value_from']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.stdValuefromRequired'));
			} else if (!isset($newPostData['standard_value_to']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.stdValuetoRequired'));
			} else if (empty($newPostData['method_id']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.methodNameRequired'));
			} else if (empty($newPostData['equipment_type_id']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.equipmentNameRequired'));
			} else if (!isset($newPostData['claim_dependent']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.claimDependentRequired'));
			} else if (!isset($newPostData['time_taken_mins']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.timeTakenRequired'));
			} else if (!isset($newPostData['time_taken_days']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.timeTakenRequired'));
			} else if (isset($newPostData['cost_price']) && $newPostData['cost_price'] != 0 && !isset($newPostData['cost_price']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.costPriceRequired'));
			} else if (!empty($newPostData['cost_price']) && filter_var($newPostData['cost_price'], FILTER_VALIDATE_FLOAT) === false) {
				$returnData = array('error' => 'Please enter valid cost price !');
			} else if (isset($newPostData['selling_price']) && $newPostData['selling_price'] != 0 && !isset($newPostData['selling_price']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
				$returnData = array('error' => config('messages.message.sellingPriceRequired'));
			} else if (!empty($newPostData['selling_price']) && filter_var($newPostData['selling_price'], FILTER_VALIDATE_FLOAT) === false) {
				$returnData = array('error' => 'Please enter valid selling price !');
			} else if (!in_array(strtolower($parameter_name), $parameterDescArr) && isset($newPostData['cost_price']) && $newPostData['cost_price'] >= $newPostData['selling_price']) {
				$returnData = array('error' => config('messages.message.costGreaterSellingRequired'));
			} else if (isset($newPostData['parameter_decimal_place']) && !strlen($newPostData['parameter_decimal_place'])) {
				$returnData = array('error' => config('messages.message.decimalPlaceError'));
			} else {
				try {
					//unsetting data
					unset($newPostData['product_category_id']);

					$newPostData['current_test_id']   = base64_decode($newPostData['current_test_id']);
					$newPostData['parameter_sort_by'] = $productTestParameter->getParameterSortNumber($newPostData['current_test_id']);               //auto sort parameter
					$newPostData['description']       = !empty($newPostData['description']) ? trim($newPostData['description']) : NULL;

					//check if test_parameter already exist or not 
					if (empty($productTestParameter->validateParameterExistenceInTestMaster($newPostData['current_test_id'], $newPostData['test_parameter_id']))) {
						if (in_array(strtolower($parameter_name), $parameterDescArr)) {
							$created = ProductTestParameter::create([
								'created_by' 		=> USERID,
								'test_id' 		=> $newPostData['current_test_id'],
								'test_parameter_id' 	=> $newPostData['test_parameter_id'],
								'description' 		=> !empty($newPostData['description']) ? $newPostData['description'] : NULL,
								'parameter_sort_by' 	=> $newPostData['parameter_sort_by']
							]);
						} else {
							$created = ProductTestParameter::create([
								'created_by' 				=> USERID,
								'test_id' 					=> $newPostData['current_test_id'],
								'test_parameter_id' 		=> $newPostData['test_parameter_id'],
								'standard_value_type' 		=> $newPostData['standard_value_type'],
								'standard_value_from' 		=> in_array($newPostData['standard_value_from'], $nullValueArr) ? NULL : $newPostData['standard_value_from'],
								'standard_value_to' 		=> in_array($newPostData['standard_value_to'], $nullValueArr) ? NULL : $newPostData['standard_value_to'],
								'equipment_type_id' 		=> $newPostData['equipment_type_id'],
								'detector_id' 				=> !empty($newPostData['detector_id']) ? $newPostData['detector_id'] : NULL,
								'method_id' 				=> $newPostData['method_id'],
								'claim_dependent' 			=> $newPostData['claim_dependent'],
								'time_taken_mins' 			=> $newPostData['time_taken_mins'],
								'time_taken_days' 			=> $newPostData['time_taken_days'],
								'cost_price' 				=> $newPostData['cost_price'],
								'selling_price' 			=> $newPostData['selling_price'],
								'description' 				=> $newPostData['description'],
								'parameter_sort_by' 		=> $newPostData['parameter_sort_by'],
								'parameter_decimal_place' 	=> $newPostData['parameter_decimal_place'],
								'parameter_nabl_scope' 		=> !empty($newPostData['parameter_nabl_scope']) ? '1' : '0',
								'measurement_uncertainty' 	=> !empty($newPostData['measurement_uncertainty']) ? $newPostData['measurement_uncertainty'] : NULL,
								'limit_determination' 		=> !empty($newPostData['limit_determination']) ? $newPostData['limit_determination'] : NULL,
								'lod' 						=> !empty($newPostData['lod']) ? $newPostData['lod'] : NULL,
								'mrpl' 						=> !empty($newPostData['mrpl']) ? $newPostData['mrpl'] : NULL,
								'validation_protocol' 		=> !empty($newPostData['validation_protocol']) ? $newPostData['validation_protocol'] : NULL,
							]);
						}
						if (!empty($created->id)) {
							$returnData = array('success' => config('messages.message.parameterSaved'), 'test_id' => $newPostData['current_test_id']);
						} else {
							$returnData = array('error' => config('messages.message.parameterNotSaved'));
						}
					} else {
						$returnData = array('error' => config('messages.message.parameterNameExistError'));
					}
				} catch (\Illuminate\Database\QueryException $ex) {
					$returnData = array('error' => config('messages.message.parameterNameExistError'));
				}
			}
		} else {
			$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
		}

		return response()->json($returnData);
	}

	/************************************
	 * Description : Show the form for editing the Test Parameters and get previous saved data
	 * Date        : 01-09-17
	 * Author      : nisha
	 * Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function editProductTestParameters(Request $request)
	{

		global $models, $productTestParameter;

		$returnData = array();

		$productTestList   = DB::table('product_test_hdr')->select('test_id as id', 'test_code as name')->get();
		$testParameterList = DB::table('test_parameter')->select('test_parameter_id as id', 'test_parameter_code as name')->get();

		if ($request->isMethod('post') && !empty($request['data']['id'])) {

			$resultData = DB::table('product_test_dtl')
				->leftjoin('detector_master', 'detector_master.detector_id', 'product_test_dtl.detector_id')
				->leftjoin('equipment_type', 'product_test_dtl.equipment_type_id', 'equipment_type.equipment_id')
				->join('test_parameter', 'product_test_dtl.test_parameter_id', 'test_parameter.test_parameter_id')
				->where('product_test_dtl.product_test_dtl_id', '=', $request['data']['id'])
				->select('detector_master.detector_name', 'detector_master.detector_id', 'product_test_dtl.*', 'test_parameter.test_parameter_code', 'test_parameter.test_parameter_name', 'test_parameter.test_parameter_print_desc', 'test_parameter.test_parameter_category_id', 'equipment_type.*', 'product_test_dtl.status as prodTestDtlStatus')
				->first();

			if ($resultData->product_test_dtl_id) {
				$resultData->test_parameter_name = strip_tags($resultData->test_parameter_name);
				$resultData->test_parameter_print_desc = strip_tags($resultData->test_parameter_print_desc);
				$returnData = array('responseData' => $resultData);
			} else {
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}
		} else {
			$returnData = array('error' => config('messages.message.provideAppData'));
		}

		return response()->json(['returnData' => $returnData, 'productTestList' => $productTestList, 'testParameterList' => $testParameterList]);
	}

	/************************************
	 * Description : Update the specified resource in storage using test_parameter_id
	 * Date        : 01-09-17
	 * Author      : nisha
	 * Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function updateProductTestParameters(Request $request)
	{

		global $models, $productTestParameter;

		$returnData = $newPostData = array();

		try {
			if ($request->isMethod('post') && !empty($request['data']['formData'])) {

				//parsing the values	
				parse_str($request['data']['formData'], $newPostData);

				$parameter_name   = $models->getParameterName($newPostData['test_parameter_id']);
				$parameterDescArr = array('description', 'reference to protocol');
				$nullValueArr     = array('na', 'n/a', '', 'NA', 'N/a', 'n/A');

				if (empty($newPostData['product_test_dtl_id'])) {
					$returnData = array('error' => config('messages.message.testParameterIdRequired'));
				}
				if (empty($newPostData['para_test_id'])) {
					$returnData = array('error' => config('messages.message.testCodeRequired'));
				} else if (empty($newPostData['test_parameter_id'])) {
					$returnData = array('error' => config('messages.message.parameterRequired'));
				} else if (empty($newPostData['standard_value_type']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.stdValueTypeRequired'));
				} else if (!isset($newPostData['standard_value_from']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.stdValuefromRequired'));
				} else if (!isset($newPostData['standard_value_to']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.stdValuetoRequired'));
				} else if (empty($newPostData['method_id']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.methodNameRequired'));
				} else if (empty($newPostData['equipment_type_id']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.equipmentNameRequired'));
				} else if (!isset($newPostData['claim_dependent']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.claimDependentRequired'));
				} else if (!isset($newPostData['time_taken_mins']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.timeTakenRequired'));
				} else if (!isset($newPostData['time_taken_days']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.timeTakenRequired'));
				} else if (isset($newPostData['cost_price']) && $newPostData['cost_price'] != 0 && !isset($newPostData['cost_price']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.costPriceRequired'));
				} else if (!empty($newPostData['cost_price']) && filter_var($newPostData['cost_price'], FILTER_VALIDATE_FLOAT) === false) {
					$returnData = array('error' => 'Please enter valid cost price !');
				} else if (isset($newPostData['selling_price']) && $newPostData['selling_price'] != 0 && !isset($newPostData['selling_price']) && !in_array(strtolower($parameter_name), $parameterDescArr)) {
					$returnData = array('error' => config('messages.message.sellingPriceRequired'));
				} else if (!empty($newPostData['selling_price']) && filter_var($newPostData['selling_price'], FILTER_VALIDATE_FLOAT) === false) {
					$returnData = array('error' => 'Please enter valid selling price !');
				} else if (!in_array(strtolower($parameter_name), $parameterDescArr) && isset($newPostData['cost_price']) && $newPostData['cost_price'] >= $newPostData['selling_price']) {
					$returnData = array('error' => config('messages.message.costGreaterSellingRequired'));
				} else if (isset($newPostData['parameter_decimal_place']) && !strlen($newPostData['parameter_decimal_place'])) {
					$returnData = array('error' => config('messages.message.decimalPlaceError'));
				} else {

					//Unsetting the variable
					unset($newPostData['product_category_id']);

					$newPostData['test_id']				= base64_decode($newPostData['para_test_id']);
					$newPostData['product_test_dtl_id']	= base64_decode($newPostData['product_test_dtl_id']);
					$newPostData['description']			= !empty($newPostData['description']) ? trim($newPostData['description']) : NULL;

					if (!empty($parameter_name) && in_array(strtolower($parameter_name), $parameterDescArr)) {
						$updated = DB::table('product_test_dtl')
							->where('product_test_dtl_id', $newPostData['product_test_dtl_id'])
							->where('test_id', $newPostData['test_id'])
							->update([
								'product_test_dtl_id' 	=> $newPostData['product_test_dtl_id'],
								'test_id' 				=> $newPostData['test_id'],
								'test_parameter_id' 	=> $newPostData['test_parameter_id'],
								'description' 			=> $newPostData['description'],
								'status' 				=> $newPostData['status'],
							]);
					} else {
						$updated = DB::table('product_test_dtl')
							->where('product_test_dtl_id', $newPostData['product_test_dtl_id'])
							->where('test_id', $newPostData['test_id'])
							->update([
								'product_test_dtl_id' 		=> $newPostData['product_test_dtl_id'],
								'test_id' 					=> $newPostData['test_id'],
								'test_parameter_id' 		=> $newPostData['test_parameter_id'],
								'standard_value_type' 		=> $newPostData['standard_value_type'],
								'standard_value_from' 		=> in_array($newPostData['standard_value_from'], $nullValueArr) ? NULL : $newPostData['standard_value_from'],
								'standard_value_to' 		=> in_array($newPostData['standard_value_to'], $nullValueArr) ? NULL : $newPostData['standard_value_to'],
								'equipment_type_id' 		=> $newPostData['equipment_type_id'],
								'detector_id' 				=> !empty($newPostData['detector_id']) ? $newPostData['detector_id'] : NULL,
								'method_id' 				=> $newPostData['method_id'],
								'claim_dependent' 			=> $newPostData['claim_dependent'],
								'time_taken_mins' 			=> $newPostData['time_taken_mins'],
								'time_taken_days' 			=> $newPostData['time_taken_days'],
								'cost_price' 				=> $newPostData['cost_price'],
								'selling_price' 			=> $newPostData['selling_price'],
								'parameter_decimal_place' 	=> $newPostData['parameter_decimal_place'],
								'parameter_nabl_scope' 		=> !empty($newPostData['parameter_nabl_scope']) ? '1' : '0',
								'measurement_uncertainty' 	=> !empty($newPostData['measurement_uncertainty']) ? $newPostData['measurement_uncertainty'] : NULL,
								'limit_determination' 		=> !empty($newPostData['limit_determination']) ? $newPostData['limit_determination'] : NULL,
								'lod' 						=> !empty($newPostData['lod']) ? $newPostData['lod'] : NULL,
								'mrpl' 						=> !empty($newPostData['mrpl']) ? $newPostData['mrpl'] : NULL,
								'validation_protocol' 		=> !empty($newPostData['validation_protocol']) ? $newPostData['validation_protocol'] : NULL,
								'status'	 				=> !empty($newPostData['status']) ? $newPostData['status'] : '1'
							]);
					}
					if ($updated) {
						$returnData = array('success' => config('messages.message.parameterUpdated'), 'product_test_dtl_id' => $newPostData['product_test_dtl_id']);
					} else {
						$returnData = array('success' => config('messages.message.savedNoChange'), 'product_test_dtl_id' => $newPostData['product_test_dtl_id']);
					}
				}
			} else {
				$returnData = array('error' => config('messages.message.dataNotFound'));
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$returnData = array('error' =>  config('messages.message.parameterNameExistError'));
		}

		return response()->json($returnData);
	}

	/************************************
	 * Description : delete product test_parameters using test_parameter_id
	 * Date        : 01-09-17
	 * Author      : nisha
	 * Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function deleteProductTestParameters(Request $request)
	{

		global $models, $productTestParameter;

		$returnData = array();

		if ($request->isMethod('post')) {
			if (!empty($request['data']['id'])) {
				try {
					$test_parameter_category = DB::table('product_test_dtl')->where('product_test_dtl_id', $request['data']['id'])->delete();
					if ($test_parameter_category) {
						$returnData = array('success' => config('messages.message.productTestParametersDeleted'));
					} else {
						$returnData = array('error' => config('messages.message.productTestParametersNotDeleted'));
					}
				} catch (\Illuminate\Database\QueryException $ex) {
					$returnData = array('error' => "Cannot delete or update a parent row: a foreign key constraint fails!");
				}
			} else {
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}
		}
		return response()->json($returnData);
	}

	/************************************
	 * Description : Getting Parameters Prices
	 * Date        : 01-09-17
	 * Author      : nisha
	 * Parameter   : \Illuminate\Http\Request  $request  (int  $request['data']['id'])
	 * @return     : \Illuminate\Http\Response
	 ************************************/
	public function getParameterPrice(Request $request, $parameter_id)
	{

		global $models, $productTestParameter;

		$parameterPrice = DB::table('test_parameter')->where('test_parameter_id', '=', $parameter_id)->select('test_parameter.cost_price', 'test_parameter.selling_price')->first();
		return response()->json($parameterPrice);
	}
}
