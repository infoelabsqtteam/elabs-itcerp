<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use App\UserSalesTargetDetail;
use Session;
use Validator;
use Route;
use DB;

class UserSalesTargetDetailController extends Controller
{
    /*************************
    * protected Variable.
    **************************/
    protected $auth;

    /*************************************
    * Create a new controller instance.
    * @return void
    **************************************/
    public function __construct(){

	global $models,$userSalesTargetDetail;

	$models = new Models();
        $userSalesTargetDetail = new UserSalesTargetDetail();
	
	//Checking the User Session
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->auth = Auth::user();
	    parent::__construct($this->auth);
	    //Checking current request is allowed or not
	    $allowedAction = array('index','navigation');
	    $actionData    = explode('@',Route::currentRouteAction());
	    $action        = !empty($actionData[1]) ? trim(strtolower($actionData[1])): '0';
	    if(defined('NOTALlOWEDTONAVIGATE') && empty(NOTALlOWEDTONAVIGATE) && in_array($action,$allowedAction)){
		return redirect('dashboard')->withErrors('Permission Denied!');
	    }
            return $next($request);
        });
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        
        global $models,$userSalesTargetDetail;
        
        $user_id            = defined('USERID') ? USERID : '0';
        $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		
        return view('master.employee.userSalesTargertMaster.index',['title' => 'Sales Target','_sales_target_master' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userSalesTargetlisting(Request $request){
        
        global $models,$userSalesTargetDetail;
        
        $error      = '0';
        $message    = '';
        $data       = array();
        $formData   = $centralStpContentList = array();

	try {
            $data = DB::table('user_sales_target_details')
                ->join('users','users.id','user_sales_target_details.ust_user_id')
                ->join('divisions','divisions.division_id','users.division_id')
                ->join('customer_master','customer_master.customer_id','user_sales_target_details.ust_customer_id')
                ->join('product_categories','product_categories.p_category_id','user_sales_target_details.ust_product_category_id')
                ->join('user_sales_target_types','user_sales_target_types.usty_id','user_sales_target_details.ust_type_id')
		->select('user_sales_target_details.*','user_sales_target_types.usty_name','users.name as employee_name','divisions.division_name',DB::raw('YEAR(user_sales_target_details.ust_date) ust_year, MONTH(user_sales_target_details.ust_date) ust_month'),'customer_master.customer_name','product_categories.p_category_name as dept_name')
                ->orderBy('users.name','ASC')
                ->orderBy('user_sales_target_details.ust_id','ASC')
                ->get()
                ->toArray();
    
            //to formate created and updated date
            $models->formatTimeStampFromArray($data,DATETIMEFORMAT);
            
        } catch(\Exception $e){
            $message = config('messages.message.error');
	}

        return response()->json(array('error'=> $error,'message'=> $message, 'userSalesTargetList' => $data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createUserSalesTarget(Request $request){
        
        global $models,$userSalesTargetDetail;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $formData = array();
        
        try {
            if($request->isMethod('post')){
                
                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                
                if(empty($formData['ust_user_id'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_amount'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_division_id'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_product_category_id'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_customer_id'])){
                    $message = config('messages.message.required');
		}else if(empty($formData['ust_type_id'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_date'])){
                    $message = config('messages.message.required');
                }else if($userSalesTargetDetail->validationUserSalesTarget($formData)){
                    $message = config('messages.message.existError');
                }else{
                    //Unsetting the variable from request data
		    $formData = $models->unsetFormDataVariables($formData,array('_token'));
                    
                    //Assign Default value to the formdata
		    $formData['ust_date']   	 = date('Y-m-d',strtotime($formData['ust_date']));
		    $formData['ust_month']   	 = date('m',strtotime($formData['ust_date']));
		    $formData['ust_year']   	 = date('Y',strtotime($formData['ust_date']));
		    $formData['ust_fin_year_id'] = $models->getInvoiceFinancialYear($formData['ust_date']);
                    $formData['created_by'] 	 = USERID;
                    
                    //Saving Record to central_po_dtls
                    if(!empty(DB::table('user_sales_target_details')->insertGetId($formData))){                    
                        $error   = '1';
                        $message = config('messages.message.saved');
                    }
                }
            }
        } catch(\Exception $e){
            $message = config('messages.message.savedError');
	}
        
        return response()->json(array('error'=> $error, 'message'=> $message));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewUserSalesTarget($id){
        
        global $models,$userSalesTargetDetail;
        
        $error    = '0';
        $message  = '';
        $data     = $formData = array();

	try {
            $data = DB::table('user_sales_target_details')->where('user_sales_target_details.ust_id',$id)->first();

            //to formate created and updated date		   
            $models->formatTimeStamp($data,MYSQLDATFORMAT);
            
        } catch(\Exception $e){
            $message = config('messages.message.error');
	}
        return response()->json(array('error'=> $error,'message'=> $message, 'userSalesTarget' => $data));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserSalesTarget(Request $request){
        
        global $models,$userSalesTargetDetail;
        
        $error    = '0';
        $message  = config('messages.message.error');
        $formData = array();
        
        try {
            if($request->isMethod('post')){
                
                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                
                if(empty($formData['ust_id'])){
                    $message = config('messages.message.error');
                }else if(empty($formData['ust_user_id'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_amount'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_division_id'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_product_category_id'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_customer_id'])){
                    $message = config('messages.message.required');
		}else if(empty($formData['ust_type_id'])){
                    $message = config('messages.message.required');
                }else if(empty($formData['ust_date'])){
                    $message = config('messages.message.required');
                }else if($userSalesTargetDetail->validationUserSalesTarget($formData,'edit')){
                    $message = config('messages.message.existError');
                }else{
                    
                    //Primary Id of the Table
                    $ustId = !empty($formData['ust_id']) ? trim($formData['ust_id']) : '0';
                    
                    //Unsetting the variable from request data
		    $formData = $models->unsetFormDataVariables($formData,array('_token','ust_id'));
                    
                    //Assign Default value to the formdata
                    $formData['ust_date'] 	 = date('Y-m-d',strtotime($formData['ust_date']));
		    $formData['ust_month']   	 = date('m',strtotime($formData['ust_date']));
		    $formData['ust_year']   	 = date('Y',strtotime($formData['ust_date']));
		    $formData['ust_fin_year_id'] = $models->getInvoiceFinancialYear($formData['ust_date']);
		    
                    //Updating Record
                    if(!empty($ustId)){
			$updateStatus = DB::table('user_sales_target_details')->where('user_sales_target_details.ust_id',$ustId)->update($formData);
			if($updateStatus){
			    $error   = '1';
			    $message = config('messages.message.updated');
			}else{
			    $error   = '1';
			    $message = config('messages.message.savedNoChange');
			}
                    }else{
                        $message = config('messages.message.updatedError');
                    }
                }
            }
        } catch(\Exception $e){
            $message = config('messages.message.updatedError');
	}        
        return response()->json(array('error'=> $error, 'message'=> $message));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyUserSalesTarget(Request $request){
        
        global $models,$userSalesTargetDetail;
        
        $error      = '0';
        $message    = config('messages.message.error');
        $formData   = array();
        
        try {
            //Deleting from table
            if($request->isMethod('post') && !empty($request->ust_id)){
                $userSalesTargetData = $userSalesTargetDetail->getRow($request->ust_id);
                if(!empty($userSalesTargetData->ust_id)){
                    if(DB::table('user_sales_target_details')->where('user_sales_target_details.ust_id','=',$userSalesTargetData->ust_id)->delete()){
                        $error    = '1';
                        $message = config('messages.message.deleted');
                    }else{
                        $message = config('messages.message.deletedError');
                    }
                }
            }
        } catch(\Exception $e){
            $message = config('messages.message.error');
	}
        
        return response()->json(array('error'=> $error, 'message'=> $message));
    }
    
    /********************************************
    * Description : Upload Sales Target file
    * Created By : Praveen Singh
    * Created On : 23-Nov-2020
    ********************************************/
    public function uploadSalesTargetCSV(Request $request)
    {
        global $models,$userSalesTargetDetail;
        
        $message      = config('messages.message.error');
        $error        = '0';
        $responseData = $csvdata = $dataUpdateRecordData = $dataSaveRecordData = $errorMsgArray = array();
	
        try {
            if(empty($request->file('file'))) {
                $message = config('messages.message.fileNotSelected');
            }elseif (!in_array($request->file('file')->getClientOriginalExtension(), array('csv'))) {
                $message = config('messages.message.invalidFileType');
            } else {
		
                //Getting Excel Data
                $csvdata = $models->csvToArray($request->file);
		
                if (empty($csvdata['data'][0])) {
                    $message = config('messages.message.noRecordFound');
                }elseif(count($csvdata['header']) != '7') {
                    $message = config('messages.message.fileColumnMismatch');
                }else{
                    
                    //Begin Transaction
                    DB::beginTransaction();
                
                    foreach($csvdata['data'] as $key => $value) {
			$value[5] 					= str_replace('/','-',$value[5]);
			$responseData[$key]['ust_user_id'] 		= $models->getIdByValue('users', 'user_code',$value[4],'id');
			$responseData[$key]['ust_division_id'] 		= $models->getIdByValue('divisions', 'division_name',$value[0],'division_id');
			$responseData[$key]['ust_product_category_id'] 	= $models->getIdByValue('department_product_categories_link', 'department_id',$models->getIdByValue('departments', 'department_name',$value[1],'department_id'),'product_category_id');
			$responseData[$key]['ust_customer_id'] 		= $models->getIdByValue('customer_master', 'customer_code',$value[2],'customer_id');
			$responseData[$key]['ust_type_id'] 		= $models->getIdByValue('user_sales_target_types', 'usty_name',$value[3],'usty_id');
			$responseData[$key]['ust_fin_year_id'] 		= !empty($value[5]) ? $models->getInvoiceFinancialYear(date('Y-m-d',strtotime($value[5]))) : '';
			$responseData[$key]['ust_month'] 		= !empty($value[5]) ? date('m',strtotime($value[5])) : '';
			$responseData[$key]['ust_year'] 		= !empty($value[5]) ? date('Y',strtotime($value[5])) : '';
			$responseData[$key]['ust_date'] 		= !empty($value[5]) ? date('Y-m-d',strtotime($value[5])) : '';
			$responseData[$key]['ust_amount'] 		= !empty($value[6]) ? $models->roundValue($value[6]) : '';
			$responseData[$key]['ust_status'] 		= '1';
			$responseData[$key]['created_by'] 		= USERID;
		    }
		    foreach($responseData as $key => $value) {    
			$lineNumber = $key + 1;			
			if (empty($value['ust_user_id'])) {
			    $errorMsgArray[] = "Invalid Employee Code: Row-".$lineNumber;
			}
			if (empty($value['ust_division_id'])) {
			    $errorMsgArray[] = "Invalid Division Name : Row-".$lineNumber;
			}
			if (empty($value['ust_product_category_id'])) {
			    $errorMsgArray[] = "Invalid Department Name : Row-".$lineNumber;
			}
			if (empty($value['ust_customer_id'])) {
			    $errorMsgArray[] = "Invalid Customer Code : Row-".$lineNumber;
			}
			if (empty($value['ust_type_id'])) {
			    $errorMsgArray[] = "Invalid Type Name : Row-".$lineNumber;
			}
			if (empty($value['ust_date'])) {
			    $errorMsgArray[] = "Invalid Date : Row-".$lineNumber;
			}
			if (empty($value['ust_amount'])) {
			    $errorMsgArray[] = "Invalid Amount : Row-".$lineNumber;
			}
		    }		    
		    if(empty($errorMsgArray)){
			if(!empty($responseData)){
			    foreach($responseData as $key => $value) {
				$existUserSalesTargetCsvData = $userSalesTargetDetail->validationUserSalesTargetCsvData($value);
				if (!empty($existUserSalesTargetCsvData->ust_id)) {
				    $value['ust_id'] = $existUserSalesTargetCsvData->ust_id;
				    $dataUpdateRecordData[$value['ust_user_id'].$value['ust_division_id'].$value['ust_product_category_id'].$value['ust_customer_id'].$value['ust_type_id'].$value['ust_month'].$value['ust_year'].$value['ust_date']] = $value;
				}else{
				    $dataSaveRecordData[$value['ust_user_id'].$value['ust_division_id'].$value['ust_product_category_id'].$value['ust_customer_id'].$value['ust_type_id'].$value['ust_month'].$value['ust_year'].$value['ust_date']] = $value;
				}
			    }
			
			    //Saving Record in user_sales_target_details table
			    !empty($dataSaveRecordData) ? DB::table('user_sales_target_details')->insert(array_values($dataSaveRecordData)) : '';
			    
			    //Updating record in user_sales_target_details tables
			    if (!empty($dataUpdateRecordData)) {
				foreach ($dataUpdateRecordData as $key => $value) {
				    if (!empty($value['ust_id'])) {
					DB::table('user_sales_target_details')->where('user_sales_target_details.ust_id', $value['ust_id'])->update(['user_sales_target_details.ust_amount'=> $value['ust_amount']]);
				    }
				}
			    }
			    
			    //Success Message
			    $error = '1';
			    $message = config('messages.message.success') . ' | Details : ' . count($dataSaveRecordData) . ' Inserted and ' . count($dataUpdateRecordData) . ' Updated';
			    
			    //commit
			    DB::commit();
			}else{
			    $error   = '0';
			    $message = config('messages.message.uploadError');
			}
		    }else{
			$error	 = '0';
			$message = implode('&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;',$errorMsgArray);
		    }
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $message = config('messages.message.uploadError');
        }
        return response()->json(['error' => $error, 'message' => $message]);
    }
}
