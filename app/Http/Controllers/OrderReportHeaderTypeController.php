<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\Method;

use App\OrderReportHeaderType;

use App\ProductCategory;
use Validator;
use Route;
use DB;

class OrderReportHeaderTypeController extends Controller
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

		global $models, $productCategory, $reportHeader;

		$models = new Models();
		$reportHeader = new OrderReportHeaderType();
		$productCategory = new ProductCategory();

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

		return view('master.order_report_header_types.index', ['title' => 'Order ReportHeader Type Master', '_or_rpt_hdr_type_master' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}


	/** create new detector
	 *  Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function createOrderReportHdrType(Request $request)
	{

		global $reportHeader;

		$returnData = array();

		try {
			if ($request->isMethod('post') && !empty($request['data']['formData'])) {

				$postedData = array();
				parse_str($request['data']['formData'], $postedData);

				if (empty($postedData['division_id'])) {
					$returnData = array('error' => config('messages.message.divisionNameRequired'));
				} else if (empty($postedData['product_category_id'])) {
					$returnData = array('error' => config('messages.message.productCatNameRequired'));
				} else if (empty($postedData['customer_type_id'])) {
					$returnData = array('error' => config('messages.message.customerTypeRequired'));
				} else if (empty($postedData['rpt_hdr_type_id'])) {
					$returnData = array('error' => config('messages.message.rptHdrTypeRequired'));
				} else {
					unset($postedData['_token']);

					$newPostData['orht_division_id'] = $postedData['division_id'];
					$newPostData['orht_product_category_id'] = $postedData['product_category_id'];
					$newPostData['orht_customer_type'] = $postedData['customer_type_id'];
					$newPostData['orht_report_hdr_type'] = $postedData['rpt_hdr_type_id'];
					$newPostData['orht_created_by'] = USERID;

					if (empty($reportHeader->orderReportHdrExist($newPostData))) {
						$created = DB::table('order_report_header_types')->insertGetId($newPostData);
						if ($created) {
							$returnData = array('success' => config('messages.message.orderReportHdrTypeSaved'));
						} else {
							$returnData = array('error' => config('messages.message.orderReportHdrTypeNotSaved'));
						}
					} else {
						$returnData = array('error' => config('messages.message.orderReprtHdrTypeDetailExist'));
					}
				}
			} else {
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			$returnData = array('error' => config('messages.message.error'));
		}
		return response()->json($returnData);
	}
	/**
	 * Get list of companies on page load.
	 * Date : 01-03-17
	 * Author : nisha
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function getOrderReportHdrList(Request $request)
	{
		global $models;
		$searchArry = '';
		$newPostData = $request['data']['formData'];
		parse_str($newPostData, $searchArry);

		$orderRptHdrObj = DB::table('order_report_header_types as orht')
			->leftJoin('divisions', 'divisions.division_id', 'orht.orht_division_id')
			->leftJoin('departments', 'departments.department_id', 'orht.orht_product_category_id')
			->leftJoin('customer_types', 'customer_types.type_id', 'orht.orht_customer_type')
			->leftJoin('report_header_type_default as rhtd', 'rhtd.rhtd_id', 'orht.orht_report_hdr_type')

			->select('orht.orht_id', 'orht.created_at', 'orht.updated_at', 'divisions.division_name', 'departments.department_name', 'customer_types.customer_type', 'rhtd.rhtd_name');

		$orderRptHdrData = $orderRptHdrObj->get();
		$models->formatTimeStampFromArray($orderRptHdrObj, DATETIMEFORMAT);
		
		// echo'<pre>';print_r($orderRptHdrData); die;
		return response()->json(['data' => $orderRptHdrData]);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request)
	{

		$returnData = array();

		if ($request->isMethod('post')) {
			if (!empty($request['data']['id'])) {
				$data = DB::table('order_report_header_types as orht')
					->leftjoin('divisions', 'orht.orht_division_id', 'divisions.division_id')
					->leftjoin('product_categories', 'product_categories.p_category_id', '=', 'orht.orht_product_category_id')
					->select('orht.*', 'divisions.division_name', 'product_categories.p_category_name')
					->where('orht.orht_id', '=', $request['data']['id'])->first();
				if ($data->orht_id) {
					$returnData = array('responseData' => $data);
				} else {
					$returnData = array('error' => config('messages.message.noRecordFound'));
				}
			} else {
				$returnData = array('error' => config('messages.message.dataNotFoundToSaved'));
			}
		} else {
			$returnData = array('error' => config('messages.message.provideAppData'));
		}
		return response()->json($returnData);
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		global $reportHeader;
		$returnData = array();

		if ($request->isMethod('post') && !empty($request['data']['formData'])) {

			//pasrse searlize data 
			$postedData = array();
			parse_str($request['data']['formData'], $postedData);

			if (empty($postedData['division_id'])) {
				$returnData = array('error' => config('messages.message.divisionNameRequired'));
			} else if (empty($postedData['product_category_id'])) {
				$returnData = array('error' => config('messages.message.productCatNameRequired'));
			} else if (empty($postedData['customer_type_id'])) {
				$returnData = array('error' => config('messages.message.customerTypeRequired'));
			} else if (empty($postedData['rpt_hdr_type_id'])) {
				$returnData = array('error' => config('messages.message.rptHdrTypeRequired'));
			} else {
				unset($postedData['_token']);
				try {
					if (!empty($postedData['orht_id'])) {
						$newPostData['orht_division_id'] = $postedData['division_id'];
						$newPostData['orht_product_category_id'] = $postedData['product_category_id'];
						$newPostData['orht_customer_type'] = $postedData['customer_type_id'];
						$newPostData['orht_report_hdr_type'] = $postedData['rpt_hdr_type_id'];
						if (!empty($reportHeader->orderReportHdrExist($newPostData, $postedData['orht_id']))) {
							$updated = DB::table('order_report_header_types')->where('orht_id', $postedData['orht_id'])->update($newPostData);
							if ($updated) {
								//check if data updated in Method table
								$returnData = array('success' => config('messages.message.orderReprtHdrTypeUpdated'));
							} else {
								$returnData = array('success' => config('messages.message.savedNoChange'));
							}
						} else {
							$returnData = array('error' => config('messages.message.orderReprtHdrTypeDetailExist'));
						}
					}
				} catch (\Illuminate\Database\QueryException $ex) {
					$returnData = array('error' => config('messages.message.error'));
				}
			}
		} else {
			$returnData = array('error' => config('messages.message.dataNotFound'));
		}
		return response()->json($returnData);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request)
	{
		$returnData = array();
		if ($request->isMethod('post')) {
			if (!empty($request['data']['id'])) {
				try {
					$detector = DB::table('order_report_header_types')->where('orht_id', $request['data']['id'])->delete();
					if ($detector) {
						$returnData = array('success' => config('messages.message.orderReprtHdrTypeDeleted'));
					} else {
						$returnData = array('error' => config('messages.message.orderReprtHdrTypeNotDeleted'));
					}
				} catch (\Illuminate\Database\QueryException $ex) {
					$returnData = array('error' => config('messages.message.foreignKeyDeleteError'));
				}
			} else {
				$returnData = array('error' => config('messages.message.noRecordFound'));
			}
		}
		return response()->json($returnData);
	}
}
