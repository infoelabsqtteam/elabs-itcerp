<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Company;
use App\Sample;
use App\Order;
use App\Models;
use App\SendMail;
use App\Report;
use App\Customer;
use App\TrfHdr;
use App\FileUpload;
use Session;
use Validator;
use Route;
use DB;
use File;

class FileUploadController extends Controller
{
    /*************************
     * protected Variable.
     **************************/
    protected $auth;

    /*************************************
     * Create a new controller instance.
     * @return void
     **************************************/
    public function __construct()
    {

        global $sample, $order, $models, $mail, $reports, $customer, $trfHdr, $fileUpload;

        $sample = new Sample();
        $order     = new Order();
        $models = new Models();
        $mail     = new SendMail();
        $reports = new Report();
        $customer = new Customer();
        $trfHdr = new TrfHdr();
        $fileUpload = new FileUpload();

        //Checking the User Session
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
        global $sample, $order, $models, $mail, $reports, $customer, $trfHdr, $fileUpload;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processOrderTrfFileUpload(Request $request)
    {

        global $sample, $order, $models, $mail, $reports, $customer, $trfHdr, $fileUpload;

        $error          = '0';
        $message        = config('messages.message.uploadError');
        $data           = '';
        $formData       = $orderLinkedTrfDtlList = array();
        $allowedExt     = array('jpg', 'jpeg', 'pdf', 'JPG', 'JPEG', 'PDF');
        $rootPath       = defined('ROOT_DIR') ? ROOT_DIR : '/opt/lampp/htdocs/itcerp';
        $trfPath        = defined('TRF_PATH') ? TRF_PATH : '/public/images/sales/trfs/';

        try {
            if ($request->isMethod('post')) {

                //Starting transaction
                DB::beginTransaction();

                if (empty($request->oltd_order_id)) {
                    $message = config('messages.message.error');
                } else if (empty($request->oltd_trf_no)) {
                    $message = config('messages.message.trfNoErrorRequired');
                } else if (empty($request->hasFile('fileData'))) {
                    $message = config('messages.message.trfFileErrorRequired');
                } else if (!$fileUpload->validateFileExtension($fileInputType = 'fileData', $request, $allowedExt)) {
                    $message = config('messages.message.trfFileInvalidErrorMsg');
                } else {
                    //Fetching Order Detail
                    $orderData = $order->getOrderDetail($request->oltd_order_id);
                    if (!empty($orderData->order_no)) {

                        //File Uploading Detail
                        $orderNo = !empty($orderData->order_no) ? 'TRF-' . $orderData->order_no : 'TRF' . $request->oltd_order_id . date('dmY');
                        $trfPath = $trfPath . $request->oltd_order_id . '/';

                        //order_linked_trf_dtl
                        $orderLinkedTrfDtl = DB::table('order_linked_trf_dtl')->where('order_linked_trf_dtl.oltd_order_id', $request->oltd_order_id)->first();
                        if (empty($orderLinkedTrfDtl->oltd_id)) {
                            //Saving Data in order_trf_file_dtl
                            $dataSave = array();
                            $dataSave['oltd_order_id']  = $request->oltd_order_id;
                            $dataSave['oltd_trf_no']    = $request->oltd_trf_no;
                            $dataSave['oltd_file_name'] = $request->oltd_trf_no;
                            $dataSave['oltd_date']      = CURRENTDATETIME;
                            $dataSave['created_by']     = USERID;
                            $orderLinkedTrfDtlId        = DB::table('order_linked_trf_dtl')->insertGetId($dataSave);
                        } else if (!empty($orderLinkedTrfDtl->oltd_id)) {

                            //Removing of Linked file from directory
                            if (!empty($orderLinkedTrfDtl->oltd_file_name)) {
                                File::delete($rootPath . $trfPath . $orderLinkedTrfDtl->oltd_file_name);
                            }

                            $dataUpdate = array();
                            $dataUpdate['oltd_trf_no']    = $request->oltd_trf_no;
                            $dataUpdate['oltd_file_name'] = $request->oltd_trf_no;
                            $dataUpdate['oltd_date']      = CURRENTDATETIME;
                            $dataUpdate['created_by']     = USERID;
                            $orderLinkedTrfDtlId          = $orderLinkedTrfDtl->oltd_id;
                            DB::table('order_linked_trf_dtl')->where('order_linked_trf_dtl.oltd_id', $orderLinkedTrfDtlId)->update($dataUpdate);
                        }

                        //Uploading TRF File
                        if (!empty($orderLinkedTrfDtlId)) {
                            $orderTrfFile = $fileUpload->media_uploads($rootPath, $trfPath, $fileInputType = 'fileData', $request, $fileName = $orderNo);
                            if (!empty($orderTrfFile)) {

                                //Updating File Name in order_linked_trf_dtl Table
                                DB::table('order_linked_trf_dtl')->where('order_linked_trf_dtl.oltd_id', $orderLinkedTrfDtlId)->update(['order_linked_trf_dtl.oltd_file_name' => $orderTrfFile]);

                                //Fetching Upload TRF Detail
                                $orderLinkedTrfDtlList = DB::table('order_linked_trf_dtl')->where('order_linked_trf_dtl.oltd_id', $orderLinkedTrfDtlId)->first();
                                if (!empty($orderLinkedTrfDtlList)) {
                                    $orderLinkedTrfDtlList->oltd_file_name_link = url($trfPath . $orderLinkedTrfDtlList->oltd_file_name);
                                }

                                $error = '1';
                                $message = config('messages.message.uploadSuccess');

                                //Committing the queries
                                DB::commit();
                            } else {
                                $message = config('messages.message.uploadError');
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $message = config('messages.message.uploadError');
        }
        return response()->json(array('error' => $error, 'message' => $message, 'orderLinkedTrfDtlList' => $orderLinkedTrfDtlList));
    }

    /***** scope-II 22-06-2018 changes*/
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadEmployeeSignatureImage(Request $request)
    {

        global $sample, $order, $models, $fileUpload;

        $error   = '0';
        $message = config('messages.message.error');
        $data    = '';
        $allowedExtension = array('jpg', 'jpeg', 'png');

        if ($request->isMethod('post') && !empty($request->user_id)) {
            if (empty($request->file('user_signature'))) {
                $message = config('messages.message.fileNotSelected');
            } else if (!$fileUpload->validateFileExtension($fileInputType = 'user_signature', $request, $allowedExtension)) {
                $message = config('messages.message.invalidFileType');
            } else {
                list($signImage, $srcPath) = $fileUpload->uploadSignature($request->user_id, $request);
                if ($signImage) {
                    DB::table('users')->where('users.id', $request->user_id)->update(['users.user_signature' => $signImage]);
                    $error    = '1';
                    $data     = $srcPath;
                    $message = config('messages.message.uploadSuccess');
                } else {
                    $message = config('messages.message.uploadError');
                }
            }
        }
        return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteEmployeeSignatureImage(Request $request, $user_id)
    {

        global $sample, $order, $models, $mail, $reports, $customer, $trfHdr, $fileUpload;

        $error    = '0';
        $message  = config('messages.message.error');
        $data     = '';

        if (!empty($user_id)) {
            $itemData = DB::table('users')->where('users.id', $user_id)->first();
            if (!empty($itemData->user_signature)) {
                $srcPath = $fileUpload->removeUploadedSignature($user_id, $itemData->user_signature);
            }
            DB::table('users')->where('users.id', $user_id)->update(['users.user_signature' => NULL]);
            $error   = '1';
            $data    = $srcPath;
            $message = config('messages.message.itemImageRemoved');
        }
        return response()->json(['error' => $error, 'message' => $message, 'data' => $data]);
    }

    /*******************************************************************
     * Function : Upload Sales Executive CSV File Data in Customer Master 
     * Created By : Praveen Singh
     * Created On : 27-Dec-2021
     *******************************************************************/
    public function uploadSalesExecutivesCsv(Request $request)
    {

        global $models, $customer, $fileUpload;

        $error          = '0';
        $message        = config('messages.message.uploadError');
        $data           = '';
        $formData       = $dataLogArray = $flag = $erorrMessageArray = array();
        $allowedExt     = array('CSV', 'csv');

        try {
            if ($request->isMethod('post')) {
                if (empty($request->file('fileData'))) {
                    $message = config('messages.message.fileNotSelected');
                } else if (!$fileUpload->validateFileExtension('fileData', $request, $allowedExt)) {
                    $message = config('messages.message.invalidFileType');
                } else {
                    $csvFileData = $models->csvToArray($request->file('fileData'));
                    if ($fileUpload->validateCustomerSalesExecutiveCsvFile($csvFileData)) {     //If file structure is valid
                        if (!empty($csvFileData['data'][0])) {   //If file Data is valid
                            //Getting New Sales Executive Customer List
                            foreach ($csvFileData['data'] as $key => $value) {
                                if (!empty($value) && count($value) == '3') {
                                    $formData[$value[1]] = $value[2];
                                }
                            }
                            //Creating Log of Customer Sales Executives
                            if (!empty($formData)) {
                                $counter = 1;
                                foreach ($formData as $csel_customer_code => $csel_sale_executive_code) {
                                    $csel_customer_id       = $models->getIdByValue('customer_master', 'customer_code', $csel_customer_code, 'customer_id');
                                    $csel_sale_executive_id = $models->getIdByValue('users', 'user_code', $csel_sale_executive_code, 'id');
                                    if(!empty($csel_customer_id) && !empty($csel_sale_executive_id)){
                                        $dataLogArray[$counter]['csel_customer_id']         = $csel_customer_id;
                                        $dataLogArray[$counter]['csel_customer_code']       = $csel_customer_code;
                                        $dataLogArray[$counter]['csel_sale_executive_id']   = $csel_sale_executive_id;
                                        $dataLogArray[$counter]['csel_sale_executive_code'] = $csel_sale_executive_code;
                                        $dataLogArray[$counter]['csel_created_by']          = USERID;
                                        $dataLogArray[$counter]['csel_date']                = CURRENTDATETIME;
                                        $counter++;
                                    }else{
                                        $erorrMessageArray[] = '('.$csel_customer_code.'-'.$csel_sale_executive_code.')';
                                    }                                   
                                }
                            }
                            //If No Error found in CSV
                            if(empty($erorrMessageArray)){

                                //Starting transaction
                                DB::beginTransaction();

                                //Updating Customer Sales Executives
                                if (!empty($dataLogArray)) {

                                    //Saving data in customer_sales_executive_logs tables
                                    DB::table('customer_sales_executive_logs')->insert($dataLogArray);

                                    foreach ($dataLogArray as $key => $values) {
                                        $flag[] = DB::table('customer_master')->where('customer_master.customer_code', $values['csel_customer_code'])->where('customer_master.customer_id', $values['csel_customer_id'])->update(['customer_master.sale_executive' => $values['csel_sale_executive_id']]);
                                    }
                                }
                                //Committing the all the changes
                                if (!empty($flag)) {

                                    //Messages
                                    $error   = '1';
                                    $message = config('messages.message.uploadSuccess');

                                    //Commit
                                    DB::commit();
                                }
                            }else{
                                $message = 'Please fix the listed missing records :  <br />'.implode(' || ',$erorrMessageArray);
                            }
                        } else {
                            $message = config('messages.message.dataNotFoundToSaved');
                        }
                    } else {
                        $message = config('messages.message.invalidFileType');
                    }
                }
            }
        } catch (\Exception $e) {
            $message = config('messages.message.uploadError');
        }
        return response()->json(array('error' => $error, 'message' => $message));
    }

    /*******************************************************************
     * Function : Upload Sales Executive CSV File Data in Customer Master 
     * Created By : Praveen Singh
     * Created On : 27-Dec-2021
     *******************************************************************/
    public function uploadOrderPurchaseOrderCsv(Request $request)
    {

        global $models, $customer, $fileUpload;

        $error          = '0';
        $message        = config('messages.message.uploadError');
        $data           = '';
        $formData       = $dataLogArray = $flag = $erorrMessageArray = array();
        $allowedExt     = array('CSV', 'csv');

        //try {
            if ($request->isMethod('post')) {
                if (empty($request->file('fileData'))) {
                    $message = config('messages.message.fileNotSelected');
                } else if (!$fileUpload->validateFileExtension('fileData', $request, $allowedExt)) {
                    $message = config('messages.message.invalidFileType');
                } else {
                    $csvFileData = $models->csvToArray($request->file('fileData'));
                    if ($fileUpload->validatePurchaseOrderCsvFile($csvFileData)) {     //If file structure is valid
                        if (!empty($csvFileData['data'][0])) {   //If file Data is valid
                            foreach ($csvFileData['data'] as $key => $value) {
                                if(empty(trim($value[1]))){
                                    $erorrMessageArray[] = '(MISSING -'.$value[2].'-'.$value[3].')';
                                }elseif(empty(trim($value[2]))){
                                    $erorrMessageArray[] = '('.$value[1].' - MISSING -'.$value[3].')';
                                }elseif(empty(trim($value[3]))){
                                    $erorrMessageArray[] = '('.$value[1].' - '.$value[2].' - MISSING)';
                                }else{
                                    $opol_order_id = $models->getIdByValue('order_master', 'order_no', trim($value[1]), 'order_id');
                                    if(!empty($opol_order_id)){
                                        $dataLogArray[$key]['opol_order_id']    = $opol_order_id;
                                        $dataLogArray[$key]['opol_order_no']    = trim($value[1]);
                                        $dataLogArray[$key]['opol_po_no']       = trim($value[2]);
                                        $dataLogArray[$key]['opol_po_date']     = date('Y-m-d',strtotime(str_replace('/','-',trim($value[3]))));
                                        $dataLogArray[$key]['opol_created_by']  = USERID;
                                    }else{
                                        $erorrMessageArray[] = '(Invalid - '.trim($value[1]).' - '.trim($value[2]).' - '.trim($value[3]).')';
                                    }
                                }
                            }         

                            //If No Error found in CSV
                            if(empty($erorrMessageArray)){

                                //Starting transaction
                                DB::beginTransaction();

                                //Updating Customer Sales Executives
                                if (!empty($dataLogArray)) {

                                    //Saving data in customer_sales_executive_logs tables
                                    DB::table('order_purchase_order_logs')->insert($dataLogArray);

                                    foreach ($dataLogArray as $key => $values) {
                                        if(!empty($values['opol_order_id'])){
                                            $flag[] = DB::table('order_master')->where('order_master.order_id', $values['opol_order_id'])->where('order_master.order_no', $values['opol_order_no'])->update(['order_master.po_no' => $values['opol_po_no'],'order_master.po_date' => $values['opol_po_date']]);
                                        }
                                    }
                                }
                                //Committing the all the changes
                                if (!empty($flag)) {

                                    //Messages
                                    $error   = '1';
                                    $message = config('messages.message.uploadSuccess');

                                    //Commit
                                    DB::commit();
                                }
                            }else{
                                $message = 'Please fix the listed records :  <br />'.implode(' || ',$erorrMessageArray);
                            }
                        } else {
                            $message = config('messages.message.dataNotFoundToSaved');
                        }
                    } else {
                        $message = config('messages.message.invalidFileType');
                    }
                }
            }
        // } catch (\Exception $e) {
        //     $message = config('messages.message.uploadError');
        // }
        
        //echo '<pre>';print_r($dataLogArray);die;
        return response()->json(array('error' => $error, 'message' => $message));
    }

}
