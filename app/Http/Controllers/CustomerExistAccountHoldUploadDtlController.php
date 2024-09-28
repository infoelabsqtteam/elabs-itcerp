<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Http\Requests;
use Auth;
use App\CustomerExistAccountHoldUploadDtl;
use Validator;
use DB;
use Route;


class CustomerExistAccountHoldUploadDtlController extends Controller
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
        global $models, $customerExistAccountHoldUploadDtl;

        $models = new Models();
        $customerExistAccountHoldUploadDtl = new CustomerExistAccountHoldUploadDtl();

        //Middleware for Auth validation
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->session = Auth::user();
            parent::__construct($this->session);           
            $this->globalCustomPermission = defined('CUSTOMPERMISSIONS') ? CUSTOMPERMISSIONS : array();  //Custom permission
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
        global $models, $customerComCrmEmailAddress;

        $user_id             = defined('USERID') ? USERID : '0';
        $division_id         = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids      = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids            = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids  = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';        
        $hasCustomPermission = !empty($this->globalCustomPermission['account_hold_upload_permission']) ? true : false;

        return view('master.customer_master.customer_exist_account_hold_upload.index', ['title' => 'Upload Customer Exist Account', '_customer_exist_account_hold_upload' => 'active', 'user_id' => $user_id, 'division_id' => $division_id, 'equipment_type_ids' => $equipment_type_ids, 'hasCustomPermission' => $hasCustomPermission]);
    }

    /**************************************************
     * Description : Display a listing of the resource
     * Created By  : Praveen Singh
     * Created On  : 17-June-2021
     *************************************************/
    public function list(Request $request)
    {

        global $models, $customerExistAccountHoldUploadDtl;

        $error               = '0';
        $message             = config('messages.message.error');
        $data                = $downloadDataList = '';
        $formData            = $customerExistAccountHoldUploadList = array();
        $hasCustomPermission = !empty($this->globalCustomPermission['account_hold_upload_permission']) ? true : false;

        if (!empty($hasCustomPermission)) {      //has Permission

            $customerExistAccountHoldUploadList = DB::table('customer_exist_account_hold_upload_dtl')
                ->join('users as createdBy', 'createdBy.id', 'customer_exist_account_hold_upload_dtl.ceahud_created_by')
                ->select('customer_exist_account_hold_upload_dtl.ceahud_customer_code as customer_code','customer_exist_account_hold_upload_dtl.ceahud_customer_name as customer_name','customer_exist_account_hold_upload_dtl.ceahud_customer_city as customer_city','customer_exist_account_hold_upload_dtl.ceahud_outstanding_amt as outstanding_amt','customer_exist_account_hold_upload_dtl.ceahud_ab_outstanding_amt as above_outstanding_amt','createdBy.name as uploaded_by_name','customer_exist_account_hold_upload_dtl.ceahud_customer_id as customer_connected_status','customer_exist_account_hold_upload_dtl.updated_at as updated_at')
                ->orderBy('customer_exist_account_hold_upload_dtl.ceahud_id', 'ASC')
                ->get()
                ->toArray();

            //Format the date time
            $models->formatTimeStampFromArray($customerExistAccountHoldUploadList, DATETIMEFORMAT);

            //Download Data
            $downloadDataList = $models->get_encrypted_string($customerExistAccountHoldUploadList);

            $error   = '1';
            $message = '';
        }else{
            $error   = '0';
            $message = config('messages.message.permissionDenied');
        }

        //echo '<pre>';print_r($customerExistAccountHoldUploadList);die;
        return response()->json(array('error' => $error, 'message' => $message, 'customerExistAccountHoldUploadList' => $customerExistAccountHoldUploadList, 'downloadDataList' => $downloadDataList));
    }

    /**************************************************
     * Description : Uploading of Data
     * Created By  : Praveen Singh
     * Created On  : 17-June-2021
     *************************************************/
    public function upload(Request $request)
    {

        global $models, $customerExistAccountHoldUploadDtl;

        $error               = '0';
        $message             = config('messages.message.error');
        $data                = '';
        $hasCustomPermission = !empty($this->globalCustomPermission['account_hold_upload_permission']) ? true : false;
        
        $formData = $dataSaveRecordData = $dataUpdateRecordData = $messageErrorLog = array();

        try {

            if ($request->isMethod('post') && !empty($request['formData']) && !empty($hasCustomPermission)) {

                //Parsing the Form Data
                parse_str($request->formData, $formData);

                //Formatting Posted Array
                $formData = array_merge($formData, array('file_name' => $request->file_name, 'importFile' => $request->file));

                //Converting file to Array
                $xls       = $models->csvToArray($formData['importFile'], false, true);
                $fileData  = !empty($xls) ? $xls : array();

                if (empty($formData['importFile'])) {
                    $message = config('messages.message.required');
                } elseif (empty($fileData['data'][0])) {
                    $message = config('messages.message.required');
                } elseif (empty($fileData['header'])) {
                    $message = config('messages.message.fileColumnMismatch');
                } elseif (!empty($fileData['header']) && count($fileData['header']) != '6') {
                    $message = config('messages.message.fileColumnMismatch');
                } else {
                    foreach ($fileData['data'] as $key => $value) {
                        if (!empty($value[1])) {    //Validating Customer Code
                            $dataSave = array();
                            $dataSave['ceahud_customer_code']      = $value[1];
                            $dataSave['ceahud_customer_name']      = $models->getIdByValue('customer_master', 'customer_code', trim($value[1]), 'customer_name');
                            $dataSave['ceahud_customer_city']      = $value[3];
                            $dataSave['ceahud_outstanding_amt']    = !empty(trim($value[4])) ? str_replace(',', '', trim($value[4])) : '0.00';
                            $dataSave['ceahud_ab_outstanding_amt'] = !empty(trim($value[5])) ? str_replace(',', '', trim($value[5])) : '0.00';
                            $dataSave['ceahud_customer_id']        = $models->getIdByValue('customer_master', 'customer_code', trim($value[1]), 'customer_id');
                            $dataSave['ceahud_created_by']         = USERID;
                            $customerExistAccountData = $customerExistAccountHoldUploadDtl->validateCustomerExistAccount($dataSave);
                            if (!empty($customerExistAccountData->ceahud_id)) {
                                $dataSave['ceahud_id']  = $customerExistAccountData->ceahud_id;
                                $dataUpdateRecordData[] = $dataSave;
                            } else {
                                $dataSaveRecordData[] = $dataSave;
                            }
                        } else {
                            $messageErrorLog[] = 'ROW No-'($key + 1) . ' : ' . config('messages.message.customerCodeRequired');
                        }
                    }

                    //echo '<pre>';print_r($dataSaveRecordData);print_r($dataUpdateRecordData);echo '</pre>';die;

                    if (empty($messageErrorLog)) {

                        //Begin Transaction
                        DB::beginTransaction();

                        //Saving Record in rpt_sage_shop_sales_purchases table
                        !empty($dataSaveRecordData) ? CustomerExistAccountHoldUploadDtl::insert($dataSaveRecordData) : '';

                        //Updating record in rpt_sage_shop_sales_purchases tables
                        if (!empty($dataUpdateRecordData)) {
                            foreach ($dataUpdateRecordData as $key => $value) {
                                if (!empty($value['ceahud_id'])) {
                                    DB::table('customer_exist_account_hold_upload_dtl')
                                        ->where('customer_exist_account_hold_upload_dtl.ceahud_id', $value['ceahud_id'])
                                        ->where('customer_exist_account_hold_upload_dtl.ceahud_customer_code', $value['ceahud_customer_code'])
                                        ->update([
                                            'ceahud_customer_name'      => $value['ceahud_customer_name'],
                                            'ceahud_customer_city'      => $value['ceahud_customer_city'],
                                            'ceahud_outstanding_amt'    => $value['ceahud_outstanding_amt'],
                                            'ceahud_ab_outstanding_amt' => $value['ceahud_ab_outstanding_amt'],
                                            'ceahud_customer_id'        => $value['ceahud_customer_id'],
                                        ]);
                                }
                            }
                        }

                        //Success Message
                        $error    = '1';
                        $message = config('messages.message.success') . ' | Details : ' . count($dataSaveRecordData) . ' Inserted and ' . count($dataUpdateRecordData) . ' Updated';

                        //commit
                        DB::commit();
                    } else {
                        //Error Message
                        $error   = '0';
                        $message = implode(' | ',$messageErrorLog);
                    }
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = config('messages.message.savedError');
        }

        return response()->json(array('error' => $error, 'message' => $message, 'data' => $data));
    }
}
