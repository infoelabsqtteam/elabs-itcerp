<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Cookie;
use App\Http\Requests;
use Auth;
use App\Company;
use App\ProductCategory;
use App\User;
use App\Report;
use Session;
use Validator;
use DB;
use Crypt;
use App\Helpers\Helper;
use Illuminate\Http\Response;
use App\AutoDataSynchronization;
use App\Order;
use App\Models;
use App\Dashboard;
use App\InvoiceHdr;
use App\NumbersToWord;
use App\AutoCommand;
use DNS1D;
use PDF;
use TCPDF;
use File;
use App\stabilityOrderPrototype;
use App\State;
use App\ScheduledMisReportDtl;
use App\SendMail;
use App\MISReport;

class TestingController extends Controller
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

		die('Unauthorized Access!');

		global $models, $order, $dashboard, $report, $invoice, $stbOrderPrototype, $misReport, $mail, $productCategory, $command, $numbersToWord, $schMisRepDtl, $autocommand;

		$models = new Models();
		$order  = new Order();
		$report = new Report();
		$dashboard = new Dashboard();
		$invoice   = new invoiceHdr();
		$mail   = new SendMail();
		$numbersToWord  = new NumbersToWord();
		$misReport = new MISReport();
		$productCategory   = new ProductCategory();
		$autocommand    = new AutoCommand();
		$command    = new AutoDataSynchronization();
		$schMisRepDtl = new ScheduledMisReportDtl();
		$stbOrderPrototype =   new stabilityOrderPrototype();
		$this->middleware('auth');

		$this->middleware(function ($request, $next) {
			$this->auth = Auth::user();
			parent::__construct($this->auth);
			return $next($request);
		});
	}

	/******************************************************
	 *function to send mail (stability order prototype)
	 *created on: 20-01-2019 
	 *created by : Ruby
	 *sendOrderMailFromStabilityOrderPrototypes
	 ******************************************************/
	public function testing(Request $request)
	{

		global $order, $report, $models, $stbOrderPrototype, $mail, $misReport, $productCategory, $command, $numbersToWord, $schMisRepDtl, $autocommand;

		//Department:
		//Food - 1 - Done
		//Pharma - 2 - Done
		//Water - 3 - Done
		//Helmet - 4 - Done
		//Ayurvedic - 5 - Done
		//Building - 6 - Done
		//Textile - 7 - Done
		//Environment - 8 - Done
		//Others - 308 - Done
		//Cosmetics - 405 - Done

		$product_category_ids = [1, 2, 3, 4, 5, 6, 7, 8, 308, 405];
		//$dateBetween        = ['2022-03-01', '2022-06-30'];
		//$dateBetween        = ['2021-11-01', '2022-02-28'];
		//$dateBetween        = ['2021-07-01', '2021-10-31'];
		//$dateBetween        = ['2021-03-01', '2021-06-31'];
		//$dateBetween        = ['2020-11-01', '2021-02-28'];
		//$dateBetween        = ['2020-07-01', '2020-10-28'];
		//$dateBetween        = ['2020-03-01', '2020-06-31'];
		$dateBetween          = ['2019-11-01', '2020-02-28'];

		// $orderParametersDetail = DB::table('order_parameters_detail-1')
		// 	->join('order_master', 'order_master.order_id', 'order_parameters_detail.order_id')
		// 	->join('order_report_details', 'order_report_details.report_id', 'order_master.order_id')
		// 	->select('order_master.order_id', 'order_master.status', 'order_master.order_no', 'order_master.order_date', 'order_report_details.report_date', 'order_parameters_detail.analysis_id', 'order_parameters_detail.analysis_start_date', 'order_parameters_detail.analysis_completion_date')
		// 	->whereIn('order_master.product_category_id', $product_category_ids)
		// 	->whereBetween('order_master.order_date', $dateBetween)
		// 	->whereNull('order_parameters_detail.analysis_start_date')
		// 	->whereNull('order_parameters_detail.analysis_completion_date')
		// 	->where('order_master.status', '>', '3')
		// 	->orderBy('order_parameters_detail.order_id', 'DESC')
		// 	->get()
		// 	->toArray();

		if (!empty($orderParametersDetail)) {
			foreach ($orderParametersDetail as $key => $values) {
				if (!empty($values->analysis_id) && !empty($values->order_date) && !empty($values->report_date)) {
					//DB::table('order_parameters_detail-1')->whereNull('order_parameters_detail.analysis_start_date')->whereNull('order_parameters_detail.analysis_completion_date')->where('order_parameters_detail.analysis_id', $values->analysis_id)->update(['order_parameters_detail.analysis_start_date' => $values->order_date, 'order_parameters_detail.analysis_completion_date' => $values->report_date]);
				}
			}
		}

		//echo '<pre>';print_r($orderParametersDetail);echo '</pre>';die;

		return view('test', ['stbOrderList' => array(), 'returnData' => array()]);
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{

		global $models, $order, $dashboard;

		$user_id            = defined('USERID') ? USERID : '0';
		$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
		$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
		$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
		$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';

		return view('dashboard.index', ['title' => 'Dashboard', '_dashboard' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids]);
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function testing1(Request $request)
	{

		global $models, $order, $report, $invoice, $state;

		//echo $invoice->generateInvoiceNumber('2019-01-02',2,1);die;
		//echo'<pre>'; print_r($state->countryTreeView());echo'</pre>';die;

		$viewData = array();

		//Expected Due Date Calculation
		//$orderData = DB::table('order_master')
		//	    ->select('order_master.order_id','order_master.status','order_master.booking_date','expected_due_date','dept_due_date','report_due_date','tat_in_days')
		//	    ->where(DB::raw("DATE(order_master.booking_date)"),'LIKE','%2018-09-20%')
		//	    ->orderBy('order_master.order_id-123','ASC')
		//	    ->get();
		//
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	if(!empty($values->order_id)){
		//	    //$order->generateUpdateOrderExpectedDueDate_v3($values->order_id);
		//	}
		//    }
		//}    
		//echo'<pre>'; print_r($orderData);echo'</pre>';
		//die;

		//$orderData = DB::table('order_master')
		//	    ->join('invoice_hdr_detail','invoice_hdr_detail.order_id','order_master.order_id')
		//	    ->select('order_master.order_id','order_master.status','order_master.invoicing_to')
		//	    ->whereNotNull('order_master.invoicing_to')
		//	    ->orderBy('order_master.order_id','ASC')
		//	    ->get();
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	if(!empty($values->order_id) && !empty($values->invoicing_to)){
		//	    DB::table('invoice_hdr_detail-pks')->whereNull('invoice_hdr_detail.order_invoicing_to')->where('invoice_hdr_detail.order_id',$values->order_id)->update(['invoice_hdr_detail.order_invoicing_to' => $values->invoicing_to]);
		//	}
		//    }	    
		//}
		//echo'<pre>'; print_r($orderData);echo'</pre>';
		//die;

		//$orderParametersDetail = DB::table('order_parameters_detail')
		//			->join('order_master','order_master.order_id','order_parameters_detail.order_id')
		//			->select('order_master.order_no','order_parameters_detail.analysis_id','order_master.product_category_id','order_parameters_detail.order_id','order_parameters_detail.display_decimal_place','order_parameters_detail.claim_value','order_parameters_detail.standard_value_type','order_parameters_detail.standard_value_from','order_parameters_detail.standard_value_to')
		//			->where(function($query) {
		//			    $query->whereNotNull('order_parameters_detail.standard_value_from')->orWhereNotNull('order_parameters_detail.standard_value_to');
		//			})
		//			->orderBy('order_parameters_detail.order_id','DESC')
		//			->get();
		//
		//if(!empty($orderParametersDetail)){
		//    foreach($orderParametersDetail as $key => $values){
		//	$this->getRequirementSTDFromTo($values,$values->standard_value_from,$values->standard_value_to);
		//	if(!empty($values->display_decimal_place)){
		//	    //DB::table('order_parameters_detail-123')->whereNull('order_parameters_detail.display_decimal_place')->where('order_parameters_detail.analysis_id',$values->analysis_id)->update(['order_parameters_detail.display_decimal_place' => $values->display_decimal_place]);
		//	    //echo'<pre>'; print_r($values);echo'</pre>';
		//	}
		//    }
		//}
		//echo'<pre>'; print_r($orderParametersDetail);echo'</pre>';
		//die;

		//Insetion of Reviewed By in Order Master
		//$orderData = DB::table('order_process_log')
		//	    ->where('order_process_log.opl_amend_status','0')
		//	    //->where('order_process_log.opl_order_status_id','5')	//reviewed_by
		//	    //->where('order_process_log.opl_order_status_id','6')	//finalized_by
		//	    ->where('order_process_log.opl_order_status_id','7')	//approved_by
		//	    ->whereNotNull('order_process_log.opl_user_id')
		//	    ->groupBy('order_process_log.opl_order_id')
		//	    ->orderBy('order_process_log.opl_order_id','ASC')
		//	    ->orderBy('order_process_log.opl_id','DESC')
		//	    ->get()
		//	    ->toArray();
		//		    
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $order){
		//	$amendedData = DB::table('order_process_log')
		//	    ->where('order_process_log.opl_amend_status','0')
		//	    ->where('order_process_log.opl_order_status_id',$order->opl_order_status_id)
		//	    ->whereNotNull('order_process_log.opl_user_id')
		//	    ->where('order_process_log.opl_order_id',$order->opl_order_id)
		//	    ->select('opl_order_id','opl_user_id')
		//	    ->orderBy('order_process_log.opl_id','DESC')
		//	    ->first();
		//	if(!empty($amendedData->opl_order_id) && !empty($amendedData->opl_user_id)){
		//	    
		//	    //Updating reviewed_by
		//	    //DB::table('order_report_details-123')->whereNull('order_report_details.reviewed_by')->whereNotNull('order_report_details.reviewing_date')->where('order_report_details.report_id',$amendedData->opl_order_id)->update(['order_report_details.reviewed_by' => $amendedData->opl_user_id]);
		//	    
		//	    //Updating finalized_by
		//	    //DB::table('order_report_details-123')->whereNull('order_report_details.finalized_by')->whereNotNull('order_report_details.finalizing_date')->where('order_report_details.report_id',$amendedData->opl_order_id)->update(['order_report_details.finalized_by' => $amendedData->opl_user_id]);
		//	    
		//	    //Updating approved_by
		//	    //DB::table('order_report_details-123')->whereNull('order_report_details.approved_by')->whereNotNull('order_report_details.approving_date')->where('order_report_details.report_id',$amendedData->opl_order_id)->update(['order_report_details.approved_by' => $amendedData->opl_user_id]);
		//	    
		//	    //echo'<pre>'; print_r($amendedData);echo'</pre>';
		//	}
		//    }
		//}
		//echo'<pre>'; print_r($dataSave);echo'</pre>';die;

		//$orderData = DB::table('schedulings')
		//	    ->join('order_master','order_master.order_id','schedulings.order_id')
		//	    ->join('order_report_details','order_report_details.report_id','schedulings.order_id')
		//	    ->select('schedulings.*','order_master.status as order_master_status','order_report_details.reviewing_date','order_master.test_completion_date')
		//	    ->where('order_master.status','>','3')
		//	    ->whereNull('schedulings.completed_at')
		//	    ->whereNotNull('schedulings.employee_id')
		//	    ->orderBy('schedulings.order_id','ASC')
		//	    ->get();
		//
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	if(!empty($values->scheduling_id) && !empty($values->test_completion_date)){
		//	    //DB::table('schedulings-123')->whereNull('schedulings.completed_at')->whereNotNull('schedulings.employee_id')->where('schedulings.scheduling_id',$values->scheduling_id)->update(['schedulings.completed_at' => $values->test_completion_date,'schedulings.status' => '3','notes' => 'Completed']);
		//	}else if(!empty($values->scheduling_id) && !empty($values->reviewing_date)){
		//	    //DB::table('schedulings-123')->whereNull('schedulings.completed_at')->whereNotNull('schedulings.employee_id')->where('schedulings.scheduling_id',$values->scheduling_id)->update(['schedulings.completed_at' => $values->reviewing_date,'schedulings.status' => '3','notes' => 'Completed']);
		//	}
		//    }	    
		//}
		//echo'<pre>'; print_r($orderData);echo'</pre>';die;

		//$inchargeData = DB::table('order_incharge_dtl')->whereNotNull('order_incharge_dtl.oid_equipment_ids')->orderBy('order_incharge_dtl.oid_id','ASC')->get()->toArray();
		//if(!empty($inchargeData)){
		//    foreach($inchargeData as $key => $values){
		//	$values->oid_equipment_type_id = current(explode(',',$values->oid_equipment_ids));
		//	//DB::table('order_incharge_dtl')->where('order_incharge_dtl.oid_id',$values->oid_id)->update(['oid_equipment_type_id' => $values->oid_equipment_type_id]);
		//    }
		//}
		//echo'<pre>'; print_r($inchargeData);echo'</pre>';die;

		//Insersertion cancellation Orders
		//$cancelledOrderData = DB::table('order_process_log')
		//		    ->join('order_master','order_master.order_id','order_process_log.opl_order_id')
		//		    ->where('order_process_log.opl_order_status_id','10')
		//		    ->where('order_master.status','10')
		//		    //->whereNotIN('order_process_log.opl_order_id',DB::table('order_cancellation_dtls')->pluck('order_id')->all())
		//		    ->select('order_process_log.*')
		//		    ->groupBy('order_process_log.opl_order_id')
		//		    ->orderBy('order_process_log.opl_order_id','ASC')
		//		    ->get()
		//		    ->toArray();
		//
		//if(!empty($cancelledOrderData)){
		//    foreach($cancelledOrderData as $key => $cancelledOrder){		
		//	$cancellationStageData 			    = DB::table('order_process_log')->where('order_process_log.opl_order_id',$cancelledOrder->opl_order_id)->whereNotIn('order_process_log.opl_order_status_id',array(10))->orderBy('order_process_log.opl_id','DESC')->first();
		//	$dataSave[$key]['order_id']                 = $cancelledOrder->opl_order_id;
		//	$dataSave[$key]['cancellation_type_id']     = '1';
		//	$dataSave[$key]['cancellation_description'] = 'In-house';
		//	$dataSave[$key]['cancellation_stage']       = !empty($cancellationStageData->opl_order_status_id) ? $cancellationStageData->opl_order_status_id : '1';
		//	$dataSave[$key]['cancelled_date']           = $cancelledOrder->opl_date;
		//	$dataSave[$key]['cancelled_by']             = $cancelledOrder->opl_user_id;
		//	$dataSave[$key]['cancellation_status']      = '1';
		//	$dataSave[$key]['created_at'] 	            = $cancelledOrder->opl_date;
		//	$dataSave[$key]['updated_at'] 	            = $cancelledOrder->opl_date;
		//    }
		//    //DB::table('order_cancellation_dtls-123')->insert($dataSave);
		//}
		//echo'<pre>'; print_r($dataSave);echo'</pre>';
		//die;

		//$invoiceData = DB::table('invoice_hdr_detail') 
		//	    ->join('order_dispatch_dtl', function($join){
		//		$join->on('order_dispatch_dtl.order_id', '=', 'invoice_hdr_detail.order_id');
		//		$join->where('order_dispatch_dtl.amend_status','0');
		//	    })
		//	    ->where('invoice_hdr_detail.invoice_hdr_status','1')
		//	    ->whereNotIN('invoice_hdr_detail.invoice_hdr_id',DB::table('invoice_dispatch_dtls')->pluck('invoice_id')->all())
		//	    ->groupBy('invoice_hdr_detail.invoice_hdr_id')
		//	    ->orderBy('invoice_hdr_detail.invoice_hdr_id','ASC')
		//	    ->select('invoice_hdr_detail.invoice_hdr_id','order_dispatch_dtl.*')
		//	    ->get();
		//
		//if(!empty($invoiceData)){
		//    foreach($invoiceData as $key => $invoices){
		//	if(!empty($invoices->invoice_hdr_id)){			//If Invoice Not Dispatched
		//	    $dataSaveInvoiceHdr = array();
		//	    $dataSaveInvoiceHdr['invoice_id']     		= $invoices->invoice_hdr_id;
		//	    $dataSaveInvoiceHdr['invoice_dispatch_by']  	= $invoices->dispatch_by;
		//	    $dataSaveInvoiceHdr['ar_bill_no']           	= $invoices->ar_bill_no;
		//	    $dataSaveInvoiceHdr['invoice_dispatch_date']	= $invoices->dispatch_date;
		//	    $dataSaveInvoiceHdr['invoice_dispatch_status']	= '1';
		//	    $dataSaveInvoiceHdr['created_at'] 	 		= $invoices->dispatch_date;
		//	    $dataSaveInvoiceHdr['updated_at'] 	 		= $invoices->dispatch_date;
		//	    //DB::table('invoice_dispatch_dtls-123')->insertGetId($dataSaveInvoiceHdr);
		//	    //echo'<pre>'; print_r($dataSaveInvoiceHdr);echo'</pre>';
		//	}
		//    }
		//}
		//die;

		//Insetion of Amended Orders
		//SELECT * FROM `order_process_log` WHERE `opl_amend_status` = 1 and opl_user_id IS NOT NULL group by opl_order_id ORDER BY `opl_order_id` ASC
		//$amendedOrderData = DB::table('order_process_log')
		//                    ->where('order_process_log.opl_amend_status','1')
		//                    ->whereNotIN('order_process_log.opl_order_id',DB::table('order_amended_dtl')->pluck('oad_order_id')->all())
		//                    ->groupBy('order_process_log.opl_order_id')
		//                    ->orderBy('order_process_log.opl_order_id','ASC')
		//                    ->get()
		//                    ->toArray();
		//                    
		//if(!empty($amendedOrderData)){
		//    foreach($amendedOrderData as $key => $amendedOrder){
		//        $amendedData = DB::table('order_process_log')
		//                    ->where('order_process_log.opl_amend_status','1')
		//                    ->where('order_process_log.opl_order_id',$amendedOrder->opl_order_id)
		//                    ->orderBy('order_process_log.opl_id','DESC')
		//                    ->first();
		//        if(!empty($amendedData->opl_order_id)){
		//            $dataSave[$key]['oad_order_id']      = $amendedData->opl_order_id;
		//            $dataSave[$key]['oad_amended_stage'] = $amendedData->opl_order_status_id;
		//            $dataSave[$key]['oad_amended_date']  = $amendedData->opl_date;
		//            $dataSave[$key]['oad_amended_by'] 	 = '1';
		//            $dataSave[$key]['created_at'] 	 = $amendedData->opl_date;
		//            $dataSave[$key]['updated_at'] 	 = $amendedData->opl_date;
		//        }
		//    }
		//    //DB::table('order_amended_dtl-123')->insert($dataSave);
		//}        
		//echo'<pre>'; print_r($dataSave);echo'</pre>';
		//die;

		//Insersertion cancellation Orders
		//$cancelledOrderData = DB::table('order_process_log')
		//                    ->join('order_master','order_master.order_id','order_process_log.opl_order_id')
		//                    ->where('order_process_log.opl_order_status_id','10')
		//                    ->where('order_master.status','10')
		//                    ->whereNotIN('order_process_log.opl_order_id',DB::table('order_cancellation_dtls')->pluck('order_id')->all())
		//                    ->select('order_process_log.*')
		//                    ->groupBy('order_process_log.opl_order_id')
		//                    ->orderBy('order_process_log.opl_order_id','ASC')
		//                    ->get()
		//                    ->toArray();
		//if(!empty($cancelledOrderData)){
		//    foreach($cancelledOrderData as $key => $cancelledOrder){
		//        $cancellation_type_arr = array('1','2');
		//        shuffle($cancellation_type_arr);
		//        $cancellation_type_id = end($cancellation_type_arr);
		//        $dataSave[$key]['order_id']                 = $cancelledOrder->opl_order_id;
		//        $dataSave[$key]['cancellation_type_id']     = $cancellation_type_id;
		//        $dataSave[$key]['cancellation_description'] = $cancellation_type_id == '1' ? 'In-house' : 'Vendor';
		//        $dataSave[$key]['cancellation_stage']       = $cancelledOrder->opl_order_status_id;
		//        $dataSave[$key]['cancelled_date']           = $cancelledOrder->opl_date;
		//        $dataSave[$key]['cancelled_by']             = $cancelledOrder->opl_user_id;
		//        $dataSave[$key]['cancellation_status']      = '1';
		//        $dataSave[$key]['created_at'] 	            = $cancelledOrder->opl_date;
		//        $dataSave[$key]['updated_at'] 	            = $cancelledOrder->opl_date;
		//    }
		//    //DB::table('order_cancellation_dtls-123')->insert($dataSave);
		//}        
		//echo'<pre>'; print_r($dataSave);echo'</pre>';
		//die;	

		//$order_report_details = DB::table('order_report_details')
		//                    ->where(DB::raw("DATE(order_report_details.approving_date_old)"),'LIKE','%1970-01-01%')
		//                    ->select('order_report_details.order_report_id','order_report_details.report_id','order_report_details.finalizing_date','order_report_details.approving_date')
		//                    ->orderBy('order_report_details.report_id','ASC')
		//                    ->get()
		//                    ->toArray();
		//if(!empty($order_report_details)){
		//    foreach($order_report_details as $key => $report){
		//        //DB::table('order_report_details-123')->where('order_report_details.order_report_id',$report->order_report_id)->update(['order_report_details.approving_date' => date('Y-m-d H:i:s',strtotime('+90 seconds', strtotime($report->finalizing_date)))]);
		//    }
		//}
		//echo'<pre>'; print_r($order_report_details);echo'</pre>';
		//die;

		//$descriptionerrorData = DB::table('descriptionerror')->orderBy('descriptionerror.id','ASC')->get();	
		//if(!empty($descriptionerrorData)){
		//    foreach($descriptionerrorData as $key => $descriptionerror){
		//	if(!empty($descriptionerror->product_test_dtl_id)){
		//	    //DB::table('product_test_dtl-123')->where('product_test_dtl.product_test_dtl_id',$descriptionerror->product_test_dtl_id)->where('product_test_dtl.test_id',$descriptionerror->test_id)->update(['description' => '-']);
		//	}		
		//    }
		//}

		//$orderData = DB::table('order_master')
		//	    ->join('customer_defined_structures','customer_defined_structures.customer_id','order_master.customer_id')
		//	    ->whereColumn('customer_defined_structures.product_category_id','=','order_master.product_category_id')
		//	    ->whereColumn('customer_defined_structures.division_id','=','order_master.division_id')
		//	    ->select('order_master.order_id','order_master.status','order_master.customer_id','order_master.product_category_id','order_master.division_id','order_master.order_no','order_master.billing_type_id','customer_defined_structures.billing_type_id as cds_billing_type_id')
		//	    ->whereNull('order_master.billing_type_id')
		//	    ->orderBy('order_master.order_id','ASC')
		//	    ->get();
		//
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	if(!empty($values->order_id) && !empty($values->cds_billing_type_id)){
		//	    //DB::table('order_master-pks')->whereNull('order_master.billing_type_id')->where('order_master.order_id',$values->order_id)->update(['order_master.billing_type_id' => $values->cds_billing_type_id]);
		//	}
		//    }	    
		//}
		//die;

		//$SGST	= defined('SGST') ? SGST : '0';
		//$CGST	= defined('CGST') ? CGST : '0';
		//$IGST	= defined('IGST') ? IGST : '0';
		//
		//$invoiceDtlData = DB::table('invoice_hdr_detail')->whereNull('invoice_hdr_detail.order_net_amount')->whereNotNull('invoice_hdr_detail.invoice_hdr_id')->orderBy('invoice_hdr_detail.invoice_dtl_id','ASC')->get();
		//	
		//if(!empty($invoiceDtlData)){
		//    
		//    foreach($invoiceDtlData as $keydtl => $valueDtl){
		//	
		//	if(!empty($valueDtl->invoice_dtl_id)){
		//	    
		//	    $updateInvoiceHdrDtl = array();
		//	    
		//	    $invoiceData = DB::table('invoice_hdr')->where('invoice_hdr.invoice_id',$valueDtl->invoice_hdr_id)->first();
		//	    
		//	    if(!empty($invoiceData->sgst_amount)){
		//		$updateInvoiceHdrDtl = array(
		//		    'order_sgst_rate'	=> $SGST,
		//		    'order_sgst_amount'	=> ($valueDtl->order_total_amount * $SGST) / 100,
		//		    'order_cgst_rate'	=> $CGST,
		//		    'order_cgst_amount'	=> ($valueDtl->order_total_amount * $CGST) / 100,
		//		    'order_igst_rate'	=> NULL,
		//		    'order_igst_amount'	=> NULL,
		//		    'order_net_amount'  => $valueDtl->order_total_amount + (($valueDtl->order_total_amount * $SGST) / 100) + (($valueDtl->order_total_amount * $CGST) / 100),
		//		);			
		//	    }else if(!empty($invoiceData->igst_amount)){
		//		$updateInvoiceHdrDtl = array(
		//		    'order_sgst_rate'	=> NULL,
		//		    'order_sgst_amount'	=> NULL,
		//		    'order_cgst_rate'	=> NULL,
		//		    'order_cgst_amount'	=> NULL,
		//		    'order_igst_rate'	=> $IGST,
		//		    'order_igst_amount'	=> ($valueDtl->order_total_amount * $IGST) / 100,
		//		    'order_net_amount'  => $valueDtl->order_total_amount + (($valueDtl->order_total_amount * $IGST) / 100),
		//		);
		//	    }else if(empty($invoiceData->igst_amount)){
		//		$updateInvoiceHdrDtl = array(
		//		    'order_net_amount'  => $valueDtl->order_total_amount,
		//		);
		//	    }
		//	    if(!empty($updateInvoiceHdrDtl)){
		//		//DB::table('invoice_hdr_detail-pks')->whereNull('invoice_hdr_detail.order_net_amount')->where('invoice_hdr_detail.invoice_hdr_id',$valueDtl->invoice_hdr_id)->where('invoice_hdr_detail.invoice_dtl_id',$valueDtl->invoice_dtl_id)->update($updateInvoiceHdrDtl);
		//	    }
		//	}
		//    }
		//}
		//die;

		//$orderData = DB::table('order_mail_dtl')->select('order_mail_dtl.mail_id',DB::raw('MAX(order_mail_dtl.mail_id) as max_mail_id'),'order_mail_dtl.invoice_id')->where('order_mail_dtl.mail_status','1')->where('order_mail_dtl.mail_content_type','4')->groupBy('order_mail_dtl.invoice_id')->orderBy('order_mail_dtl.mail_id','DESC')->get();
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	if(!empty($values->max_mail_id) && !empty($values->invoice_id)){
		//	    //DB::table('order_mail_dtl111')->where('order_mail_dtl.invoice_id',$values->invoice_id)->where('order_mail_dtl.mail_id',$values->max_mail_id)->update(['order_mail_dtl.mail_active_type' => 1]);
		//	}
		//    }	    
		//}
		//die('Updated');

		//$orderData = DB::table('order_mail_dtl')->select('order_mail_dtl.mail_id',DB::raw('MAX(order_mail_dtl.mail_id) as max_mail_id'),'order_mail_dtl.order_id')->where('order_mail_dtl.mail_status','1')->where('order_mail_dtl.mail_content_type','3')->groupBy('order_mail_dtl.order_id')->orderBy('order_mail_dtl.mail_id','DESC')->get();
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	if(!empty($values->max_mail_id)){
		//	    //DB::table('order_mail_dtl11')->where('order_mail_dtl.mail_id',$values->max_mail_id)->update(['order_mail_dtl.mail_active_type' => 1]);
		//	}
		//    }	    
		//}
		//die('Updated');

		//$schedulings = DB::table('schedulings')->select('schedulings.scheduling_id','schedulings.order_id','schedulings.scheduled_at',DB::raw('MAX(schedulings.scheduled_at) as scheduled_at_max'))->groupBy('schedulings.order_id')->orderBy('schedulings.scheduled_at','DESC')->get();
		//if(!empty($schedulings)){
		//    foreach($schedulings as $key => $values){
		//	if(!empty($values->order_id) && !empty($values->scheduled_at_max)){
		//	    //DB::table('order_master111')->where('order_master.order_id',$values->order_id)->whereNull('order_master.order_scheduled_date')->update(['order_master.order_scheduled_date' => $values->scheduled_at_max]);
		//	}	
		//    }
		//}
		//echo '<pre>';print_r($schedulings);

		//$orderData = DB::table('order_master')->select('order_master.order_id','order_master.booking_date','order_master.order_no','order_master.sample_id')->groupBy('order_master.sample_id')->orderBy('order_master.sample_id','ASC')->get();
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	if(!empty($values->order_id) && !empty($values->booking_date)){
		//	    DB::table('samples')->where('samples.sample_id',$values->sample_id)->where('samples.sample_status','1')->update(['samples.sample_booked_date' => $values->booking_date]);
		//	}
		//    }	    
		//}
		//die('Updated');

		//$completedOrderData = DB::table('schedulings')
		//		    ->join('order_master','order_master.order_id','schedulings.order_id')
		//		    ->join('order_report_details','order_report_details.report_id','schedulings.order_id')
		//		    ->select('order_report_details.report_id','order_master.division_id','schedulings.product_category_id','schedulings.order_id','order_master.status','schedulings.equipment_type_id','order_report_details.report_microbiological_sign')
		//		    ->whereNotNull('schedulings.completed_at')
		//		    ->where('schedulings.status',3)
		//		    ->where('order_master.status','>','5')
		//		    ->where('order_master.division_id','=','1')
		//		    ->where('schedulings.equipment_type_id','=','22')
		//		    ->groupBy('schedulings.order_id')
		//		    ->whereNull('order_report_details.report_microbiological_sign')
		//		    ->orderBy('order_report_details.report_id','DESC')
		//		    ->get();
		//
		//foreach($completedOrderData as $key => $values){    
		//    if(!empty($values->product_category_id) && $values->product_category_id != '2'){
		//	$orderData 		     = DB::table('order_master')->select('order_master.order_id','order_master.division_id')->where('order_master.order_id','=',$values->order_id)->first();
		//	$orderReportDetail           = DB::table('order_report_details')->where('order_report_details.report_id','=',$values->order_id)->whereNull('order_report_details.report_microbiological_name')->first();
		//	$hasMicrobiologicalEquipment = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id','22')->where('order_parameters_detail.order_id','=',$values->order_id)->first();
		//	if(!empty($orderReportDetail) && !empty($hasMicrobiologicalEquipment)){
		//	    $microbiologistData = DB::table('users')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id','15')->where('users.division_id',$values->division_id)->first();
		//	    if(!empty($microbiologistData->name)){
		//		$reportMicrobiologicalSign = strtolower(preg_replace('/[_]+/','_',preg_replace("/[^a-zA-Z]/", "_", $microbiologistData->name)).'.png');
		//		return DB::table('order_report_details1')->where('order_report_details.report_id',$values->order_id)->update(['order_report_details.report_microbiological_name' => $microbiologistData->name, 'order_report_details.report_microbiological_sign' => $reportMicrobiologicalSign]);    
		//	    }
		//	}    
		//    }
		//}
		//		    
		//echo'<pre>'; print_r($completedOrderData);echo'</pre>';
		//die;

		//$completedOrderData = DB::table('schedulings')
		//		    ->join('order_master','order_master.order_id','schedulings.order_id')
		//		    ->select('schedulings.scheduling_id','schedulings.order_id','schedulings.status as scheduling_status','order_master.status as order_status')
		//		    ->whereNotNull('schedulings.completed_at')
		//		    ->where('schedulings.status',3)
		//		    ->where('order_master.status','>','3')
		//		    ->groupBy('schedulings.order_id')
		//		    ->orderBy('schedulings.scheduling_id','DESC')
		//		    ->get();
		//
		//foreach($completedOrderData as $key => $values){
		//    $data = DB::table('schedulings')
		//	    ->select('scheduling_id','order_id','completed_at','status')
		//	    ->where('order_id',$values->order_id)
		//	    ->where('status','3')
		//	    ->orderBy('completed_at','DESC')
		//	    ->first();	    
		//    if(!empty($data->completed_at)){
		//	//DB::table('order_master')->where('order_master.order_id',$data->order_id)->update(['order_master.test_completion_date' => $data->completed_at]);
		//    }
		//}

		//$viewData = DB::table('order_process_log')
		//		->select('opl_id','opl_order_id')
		//		->whereIn('order_process_log.opl_order_status_id',array(5,6,7))
		//		->groupBy('order_process_log.opl_order_id')
		//		->orderBy('opl_order_id','DESC')
		//		->limit(10)
		//		->get();
		//
		//foreach($viewData as $key => $values){
		//    
		//    $reviewingdate = DB::table('order_process_log')->select('opl_id','opl_order_id','opl_date','opl_order_status_id')->where('opl_order_id',$values->opl_order_id)->where('order_process_log.opl_order_status_id',5)->orderBy('opl_id','DESC')->first();
		//    $finalizingdate = DB::table('order_process_log')->select('opl_id','opl_order_id','opl_date','opl_order_status_id')->where('opl_order_id',$values->opl_order_id)->where('order_process_log.opl_order_status_id',6)->orderBy('opl_id','DESC')->first();
		//    $approvingdate = DB::table('order_process_log')->select('opl_id','opl_order_id','opl_date','opl_order_status_id')->where('opl_order_id',$values->opl_order_id)->where('order_process_log.opl_order_status_id',7)->orderBy('opl_id','DESC')->first();
		//    
		//    $values->reviewing_date = !empty($reviewingdate->opl_date) ? $reviewingdate->opl_date : NULL;
		//    $values->finalizing_date = !empty($finalizingdate->opl_date) ? $finalizingdate->opl_date : NULL;
		//    $values->approving_date = !empty($approvingdate->opl_date) ? $approvingdate->opl_date : NULL;
		//    
		//    DB::table('order_report_details')->whereNull('order_report_details.reviewing_date')->where('order_report_details.report_id',$values->opl_order_id)->update(['order_report_details.reviewing_date' => $values->reviewing_date]);
		//    DB::table('order_report_details')->whereNull('order_report_details.finalizing_date')->where('order_report_details.report_id',$values->opl_order_id)->update(['order_report_details.finalizing_date' => $values->finalizing_date]);
		//    DB::table('order_report_details')->whereNull('order_report_details.approving_date')->where('order_report_details.report_id',$values->opl_order_id)->update(['order_report_details.approving_date' => $values->approving_date]);
		//}

		//$inchargeData = DB::table('order_master')
		//->join('order_incharge_dtl','order_incharge_dtl.order_id','=','order_master.order_id')
		//// ->whereNotNull('order_incharge_dtl.oid_equipment_type_id')
		//->whereNotNull('order_incharge_dtl.oid_confirm_date')
		//->whereNotNull('order_incharge_dtl.oid_confirm_by')
		//->where('order_master.status','4')
		//->select('order_incharge_dtl.*','order_master.order_id','order_master.order_no','order_master.status')
		//->orderBy('order_incharge_dtl.oid_id','ASC')
		////->groupBy('order_master.order_id')
		//->get(); 
		//echo'<pre>'; print_r($inchargeData);echo'</pre>';die;

		//return $command->copyOrdersWithStatusCommand(array());die;
		//return $autocommand->sendVocMailDataDetail(array());die;	
		//return $autocommand->sendScheduledMisReportDetail(array());die;
		//echo $report->__generateNablCodeNumber_v1(120571);die;	
		//echo $report->generateReportNumber($currentDate='2018-10-31 16:22:16',$productCategoryId='2',$divisionId='1');die;
		//$order->generateUpdateOrderExpectedDueDate_v3(145590);
		//return $order->updateReportDepartmentDueDate(145590);die;
		//return $models->validateDateIsHolidayOrSunday(CURRENTDATE);die;
		//echo $stbOrderPrototype->generateStabilityOrderNumber(array('stb_prototype_date' => '2020-01-01','stb_product_category_id' => '2','stb_division_id' => '1'));die;	

		//$orderData = DB::table('order_master')->whereNull('booked_order_amount')->orderBy('order_master.order_id','ASC')->get();
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	$order->updateBookingSampleAmount($values->order_id);
		//    }
		//}
		//die;

		//$orderIds = array(139536, 139251, 135516, 135531, 139450, 140135, 140560, 139424, 139426, 139427, 139430, 139432, 139434, 139443, 139463, 139516, 139597, 139602);
		//foreach($orderIds as $val){
		//    $order->updateReportDepartmentDueDate($val);
		//}
		//die;
		//$booking_date = '2019-09-18 11:00:24';
		//$total_time_taken_days = '3';
		//echo $expectedDueDate = date(MYSQLDATETIMEFORMAT,strtotime('+'.$total_time_taken_days.' day',strtotime($booking_date)));
		//die;

		//$orderData = DB::table('order_parameters_detail')->select('order_id','dept_due_date','report_due_date')->whereNull('dept_due_date')->groupBy('order_parameters_detail.order_id')->get();
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	$order->updateReportDepartmentDueDate($values->order_id);
		//    }
		//}
		//die;

		//$orderData = DB::table('order_master')
		//	    ->join('order_parameters_detail', function($join){
		//		$join->on('order_parameters_detail.order_id','=','order_master.order_id');
		//		$join->whereNotNull('order_parameters_detail.dept_due_date');
		//		$join->whereRaw('order_parameters_detail.dept_due_date IN (SELECT MAX(opd.dept_due_date) FROM order_parameters_detail opd INNER JOIN order_master om ON opd.order_id = om.order_id GROUP BY opd.order_id)');
		//	    })
		//	    ->select('order_master.order_no','order_master.order_id','order_master.division_id','order_master.booking_date','order_master.expected_due_date','order_parameters_detail.dept_due_date','order_parameters_detail.report_due_date')->where(DB::raw("DATE(order_master.booking_date)"),'LIKE','%2019-07-16%')
		//	    ->groupBy('order_master.order_id')
		//	    ->get()->toArray();
		//if(!empty($orderData)){
		//    foreach($orderData as $key => $values){
		//	$values->new_expected_due_date = $models->validateSundayHoliday_v2($values->division_id,$values->expected_due_date,'1','-');
		//    }
		//}
		//echo'<pre>'; print_r($orderData);echo'</pre>';die;

		//$orderArray = array('143498','143499','143501','143502');
		//foreach($orderArray as $key => $orderId){
		//    $order->generateUpdateOrderExpectedDueDate_v3($orderId);
		//    $order->updateReportDepartmentDueDate($orderId);
		//}
		//die;	
		//$orderData = DB::table('order_master')->whereNull('order_master.order_dept_due_date')->get()->toArray();
		//foreach($orderData as $key => $value){
		//    //Updating Department Due Date and Report Due Date in Order Master
		//    $maxOrderDepDueDate = DB::table('order_parameters_detail')->where('order_parameters_detail.order_id',$value->order_id)->max('order_parameters_detail.dept_due_date');
		//    !empty($maxOrderDepDueDate) ? DB::table('order_master-123')->where('order_master.order_id',$value->order_id)->update(['order_master.order_dept_due_date' => $maxOrderDepDueDate,'order_master.order_report_due_date' => $maxOrderDepDueDate]) : false;
		//}
		//die;

		//$invoicedData = DB::table('invoice_hdr')
		//		->join('invoice_hdr_detail','invoice_hdr_detail.invoice_hdr_id','invoice_hdr.invoice_id')
		//		->select('invoice_hdr.invoice_id','invoice_hdr.customer_id','invoice_hdr_detail.order_invoicing_to')
		//		->where('invoice_hdr.invoice_status','1')
		//		->where('invoice_hdr_detail.invoice_hdr_status','1')
		//		//->where('invoice_hdr.invoice_status','2')
		//		//->where('invoice_hdr_detail.invoice_hdr_status','2')
		//		->groupBy('invoice_hdr.invoice_id')
		//		->orderBy('invoice_hdr.invoice_id','ASC')
		//		->get()
		//		->toArray();
		//foreach($invoicedData as $key => $value){
		//    $value->customer_invoicing_id = !empty($value->order_invoicing_to) ? $value->order_invoicing_to : $value->customer_id;
		//    if(!empty($value->invoice_id) && !empty($value->customer_invoicing_id)){
		//	DB::table('invoice_hdr-123')->where('invoice_hdr.invoice_id',$value->invoice_id)->update(['invoice_hdr.customer_invoicing_id' => $value->customer_invoicing_id]);
		//    }
		//}
		//echo'<pre>'; print_r($invoicedData);echo'</pre>';die('Done');

		//$dataSaveArray = $dataSaveArrayExist = array();
		//$employeeSalesData = DB::table('employee_sales_dtl')->orderBy('employee_sales_dtl.ust_id')->get()->toArray();
		//if(!empty($employeeSalesData)){
		//    foreach($employeeSalesData as $key => $values){
		//	$formData = array();
		//	$formData['ust_division_id'] 		= $values->ust_division_id;
		//	$formData['ust_product_category_id'] 	= $values->ust_product_category_id;
		//	$formData['ust_customer_id'] 		= $values->ust_customer_id;
		//	$formData['ust_user_id'] 		= $values->ust_user_id;
		//	$formData['ust_amount'] 		= $values->ust_amount;
		//	$formData['ust_date'] 			= date('Y-m-d',strtotime($values->ust_date));
		//	$formData['ust_status'] 		= $values->ust_status;
		//	$formData['created_by'] 		= $values->created_by;
		//	if(empty(DB::table('user_sales_target_details-111')
		//	    ->where('user_sales_target_details.ust_division_id',$formData['ust_division_id'])
		//	    ->where('user_sales_target_details.ust_product_category_id',$formData['ust_product_category_id'])
		//	    ->where('user_sales_target_details.ust_customer_id',$formData['ust_customer_id'])
		//	    ->where('user_sales_target_details.ust_user_id',$formData['ust_user_id'])
		//	    ->whereDate('user_sales_target_details.ust_date',$formData['ust_date'])
		//	    ->count())){
		//	    //Inserting
		//	    $dataSaveArray[] = DB::table('user_sales_target_details1111')->insertGetId($formData);
		//	}else{
		//	    $dataSaveArrayExist[] = $values->ust_id;
		//	}
		//    }
		//}
		//echo'<pre>'; print_r($dataSaveArrayExist);echo'</pre>';die;

		// $autoDataSynchronization = new AutoDataSynchronization();
		// $data = $autoDataSynchronization->funSynActionType(['actionContentType' => 1]);
		// echo '<pre>';print_r($data);die;

		die('testing blade');

		//echo'<pre>'; print_r($viewData);echo'</pre>';die();
		return view('test', ['viewData' => $viewData]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	function getRequirementSTDFromTo($values, $stdFrom, $stdTo)
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
	function getRequirementSTDFromTo_v2($values, $stdFrom, $stdTo)
	{
		if (!empty($values)) {
			if (!empty($stdFrom) && !empty($stdTo)) {
				if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) == 'n/a') {
					$values->display_decimal_place = NULL;
				} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) != 'n/a') {
					if (is_numeric($stdFrom) && is_numeric($stdTo)) {
						$values->display_decimal_place = 3;
					} else if (is_numeric($stdFrom) && !is_numeric($stdTo)) {
						$values->display_decimal_place = 3;
					} else if (!is_numeric($stdFrom) && is_numeric($stdTo)) {
						$values->display_decimal_place = 3;
					} else {
						$values->display_decimal_place = NULL;
					}
				} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) == 'n/a') {
					if (is_numeric($stdFrom)) {
						$values->display_decimal_place = 3;
					} else {
						$values->display_decimal_place = NULL;
					}
				} else if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) != 'n/a') {
					if (is_numeric($stdTo)) {
						$values->display_decimal_place = 3;
					} else {
						$values->display_decimal_place = NULL;
					}
				}
			} else if (!empty($stdFrom) && empty($stdTo)) {
				if (is_numeric($stdFrom)) {
					$values->display_decimal_place = 3;
				} else {
					$values->display_decimal_place = NULL;
				}
			} else if (empty($stdFrom) && !empty($stdTo)) {
				if (is_numeric($stdTo)) {
					$values->display_decimal_place = 3;
				} else {
					$values->display_decimal_place = NULL;
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
				$values->display_decimal_place = '';
			} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) != 'n/a') {
				if (is_numeric($stdFrom) && is_numeric($stdTo)) {
					$values->display_decimal_place = $decimalPlaces;
				} else if (is_numeric($stdFrom) && !is_numeric($stdTo)) {
					$values->display_decimal_place = $decimalPlaces;
				} else if (!is_numeric($stdFrom) && is_numeric($stdTo)) {
					$values->display_decimal_place = $decimalPlaces;
				} else if (!is_numeric($stdFrom) && !is_numeric($stdTo)) {
					$values->display_decimal_place = NULL;
				}
			} else if (strtolower($stdFrom) != 'n/a' && strtolower($stdTo) == 'n/a') {
				if (is_numeric($stdFrom)) {
					$values->display_decimal_place = $decimalPlaces;
				} else {
					$values->display_decimal_place = NULL;
				}
			} else if (strtolower($stdFrom) == 'n/a' && strtolower($stdTo) != 'n/a') {
				if (is_numeric($stdTo)) {
					$values->display_decimal_place = $decimalPlaces;
				} else {
					$values->display_decimal_place = NULL;
				}
			}
		} else if (!empty($stdFrom) && empty($stdTo)) {
			if (is_numeric($stdFrom)) {
				$values->display_decimal_place = $decimalPlaces;
			} else {
				$values->display_decimal_place = NULL;
			}
		} else if (empty($stdFrom) && !empty($stdTo)) {
			if (is_numeric($stdTo)) {
				$values->display_decimal_place = $decimalPlaces;
			} else {
				$values->display_decimal_place = NULL;
			}
		}
	}

	/*******************************************
	 *Function: generate Order Expected Due Date(EDD)
	 *Created By: Praveen Singh
	 *Created On : 08-July-2018
	 *Modified On : 08-March-2019,11-July-2019
	 ******************************************/
	function generateUpdateOrderExpectedDueDate_v3($order_id, $date = NULL, $columnArray = array())
	{

		global $models;

		$dataSave = array();

		$orderDetail =  DB::table('order_master')->select('order_id', 'division_id', 'order_date', 'booking_date', 'tat_in_days')->where('order_master.order_id', '=', $order_id)->first();

		if (!empty($orderDetail->booking_date)) {

			$expectedDueDate           = !empty($date) ? $date : $orderDetail->booking_date;
			$orderDetail->booking_date = !empty($date) ? $date : $orderDetail->booking_date;

			//Getting Number of days
			list($expectedDueDate, $is_tat_in_day_reupdatable) = $this->__calculateDaysForEDDBySystemOrUserTAT_v3($orderDetail, $expectedDueDate, $columnArray);

			//Finally Updating the Order Master Table
			if (!empty($is_tat_in_day_reupdatable)) {
				$dataSave = array('order_master.expected_due_date' => $expectedDueDate, 'order_master.tat_in_days' => $is_tat_in_day_reupdatable);
			} else {
				$dataSave = array('order_master.expected_due_date' => $expectedDueDate);
			}
			return !empty($dataSave) ? DB::table('order_master')->where('order_master.order_id', $order_id)->update($dataSave) : false;
		}
	}

	/*******************************************
	 *Function : Getting Number of Days to calculate Order Expected Due Date(EDD)
	 *Created By : Praveen Singh
	 *Created On : 30-July-2018
	 *Modified On : 08-March-2019,11-July-2019
	 ******************************************/
	function __calculateDaysForEDDBySystemOrUserTAT_v3($orderDetail, $expectedDueDate, $columnArray)
	{

		global $models;

		/**************************************************************************************************
		STEP 1 : If Customer has Customer-wise-tat defined and there is no Microbiological Instrument in it,then Expected Due Date will be calculated according to STEP 3.
		STEP 2 : If Customer has Customer-wise-tat defined or there is a Microbiological Instrument in it,then Expected Due Date will be calculated by the maximum TAT defined for a test parameter of a particular order irrespective of weekly off,sunday & +1 of after 2:00 pm conditions.
		STEP 3 : If Customer hasn't Customer-wise-tat defined and there is no Microbiological Instrument in it,then Expected Due Date will be calculated by the System normally.
		 ***************************************************************************************************/

		$time_taken_days 	= array();
		$total_time_taken_days  = $is_tat_in_day_reupdatable = '0';
		$editActionType 	= !empty($columnArray['action']) && $columnArray['action'] == 'edit' ? '1' : '0';

		//If User Enters the TAT in Days Values and Selected Parameter has not Microbiological Equipment
		$hasEquipmentMicrobiological = DB::table('order_parameters_detail')->where('order_parameters_detail.equipment_type_id', '22')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->first();

		if (!empty($orderDetail->tat_in_days) && !empty($editActionType)) {			//If Customer Wise TAT will be updated in case of TAT Input is updated in case of Edit Mode

			//Getting Days of TAT Calculation
			$total_time_taken_days = !empty($orderDetail->tat_in_days) && is_numeric($orderDetail->tat_in_days) ? trim($orderDetail->tat_in_days) : '0';
		} else if (!empty($orderDetail->tat_in_days) && empty($hasEquipmentMicrobiological)) {	//STEP 1

			//Getting Days of TAT Calculation
			$total_time_taken_days = !empty($orderDetail->tat_in_days) && is_numeric($orderDetail->tat_in_days) ? trim($orderDetail->tat_in_days) : '0';
		} else if (!empty($orderDetail->tat_in_days) && !empty($hasEquipmentMicrobiological)) { 	//STEP 2

			//Getting Maximum TAT defined from a parameters detail of an order
			$orderParametersDetail = DB::table('order_parameters_detail')->select('order_parameters_detail.time_taken_days', 'order_parameters_detail.time_taken_mins')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->get();
			foreach ($orderParametersDetail as $key => $orderParameter) {
				$time_taken_days[] = !empty($orderParameter->time_taken_days) ? $orderParameter->time_taken_days : '0';
			}
			//Getting Maximum days from all parameter test allocated days
			$total_time_taken_days = !empty($time_taken_days) && array_filter($time_taken_days) ? round(max($time_taken_days)) : '0';
			$total_time_taken_days = !empty($total_time_taken_days) && is_numeric($total_time_taken_days) ? $total_time_taken_days : '0';

			//Checking If TAT-In-Days is re-updatable in case of Microbilogical Equipment Exist
			$is_tat_in_day_reupdatable = !empty($orderDetail->tat_in_days) && !empty($hasEquipmentMicrobiological) ? $total_time_taken_days : '0';
		} else {			    								//STEP 3	    

			//Getting Maximum TAT defined from a parameters detail of an order
			$orderParametersDetail = DB::table('order_parameters_detail')->select('order_parameters_detail.time_taken_days', 'order_parameters_detail.time_taken_mins')->where('order_parameters_detail.order_id', '=', $orderDetail->order_id)->get();
			foreach ($orderParametersDetail as $key => $orderParameter) {
				$time_taken_days[] = !empty($orderParameter->time_taken_days) ? $orderParameter->time_taken_days : '0';
			}
			//Getting Maximum days from all parameter test allocated days
			$total_time_taken_days = !empty($time_taken_days) && array_filter($time_taken_days) ? round(max($time_taken_days)) : '0';
			$total_time_taken_days = !empty($total_time_taken_days) && is_numeric($total_time_taken_days) ? $total_time_taken_days : '0';
		}

		if (!empty($total_time_taken_days) && !empty($orderDetail->booking_date)) {

			$total_time_taken_days = $total_time_taken_days - 1;

			//if booking date after 4.00 PM
			if (strtotime(date('ha', strtotime($orderDetail->booking_date))) > strtotime("4pm")) {
				$total_time_taken_days = round($total_time_taken_days + '1');
			}

			//Add days to current date to calculate the observed expected due date
			$expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $total_time_taken_days . ' day', strtotime($orderDetail->booking_date)));

			//Checking if any holidays lies between order booking date and Calculated Expected Due Date
			$holidayDayCounts = DB::table('holiday_master')->where('holiday_master.division_id', $orderDetail->division_id)->whereBetween(DB::raw("DATE(holiday_master.holiday_date)"), array(date('Y-m-d', strtotime($orderDetail->booking_date)), date('Y-m-d', strtotime($expectedDueDate))))->count();
			if ($holidayDayCounts) {
				$expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . $holidayDayCounts . ' day', strtotime($expectedDueDate)));
			}

			//Checking there any sunday lies on calculated days,then add number of days according to number of sunday in expected due date
			$sundays = $models->getSundays($orderDetail->booking_date, $expectedDueDate);
			if (!empty($sundays)) {
				$expectedDueDate = date(MYSQLDATETIMEFORMAT, strtotime('+' . count($sundays) . ' day', strtotime($expectedDueDate)));
			}

			//Validation of Sunday and Holidays
			$expectedDueDate = $models->validateSundayHoliday_v2($orderDetail->division_id, $expectedDueDate, '1', '+');
		}

		return array($expectedDueDate, $is_tat_in_day_reupdatable);
	}

	/******************************************************
	 *created on: 21-09-2019 
	 *created by : Praveen
	 ******************************************************/
	public function testingAfter(Request $request)
	{

		global $order;

		$error   = '0';
		$message = '';
		$data    = '';

		$orderArray = array('143498');
		foreach ($orderArray as $key => $orderId) {
			$order->generateUpdateOrderExpectedDueDate_v3($orderId);
		}
		return response()->json(array('error' => $error, 'message' => $message));
	}

	/******************************************************
	 *created on: 21-09-2019 
	 *created by : Praveen
	 ******************************************************/
	public function testingBefore(Request $request)
	{

		$error   = '0';
		$message = '';
		$data    = '';

		$orderArray = array('143498');
		foreach ($orderArray as $key => $orderId) {
			$this->generateUpdateOrderExpectedDueDate_v3($orderId);
		}
		return response()->json(array('error' => $error, 'message' => $message));
	}
}
