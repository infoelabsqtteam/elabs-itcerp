<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Session;
use Validator;
use Route;
use DB;
use App\ScheduledMisReportDtl;
use App\Order;
use App\Models;

class ScheduledMisReportDtlController extends Controller
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
    public function __construct(){
    
        global $order,$models,$schMisRepDtl;
        
        $order  = new Order();
        $models = new Models();
        $schMisRepDtl = new ScheduledMisReportDtl();
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
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
    
        global $order,$models,$schMisRepDtl;
        
        $user_id            = defined('USERID') ? USERID : '0';
        $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
        
        return view('MIS.ScheduledMISReports.index',['title' => 'Scheduled MIS Reports','_scheduled_mis_report' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listScheduledMisReport(Request $request){
        
        global $models,$schMisRepDtl;
        
        $error      = '0';
        $message    = config('messages.message.error');
        $data       = '';
        $returnData = array();
        
        $returnData = DB::table('scheduled_mis_report_dtls')
                    ->join('divisions','divisions.division_id','scheduled_mis_report_dtls.smrd_division_id')
                    ->join('product_categories','product_categories.p_category_id','scheduled_mis_report_dtls.smrd_product_category_id')
                    ->join('mis_report_default_types','mis_report_default_types.mis_report_id','scheduled_mis_report_dtls.smrd_mis_report_id')
                    ->select('scheduled_mis_report_dtls.*','divisions.division_name as smrd_division_name','product_categories.p_category_name as smrd_product_category_name','mis_report_default_types.mis_report_name as smrd_mis_report_name')
                    ->groupBy('scheduled_mis_report_dtls.smrd_division_id','scheduled_mis_report_dtls.smrd_product_category_id','scheduled_mis_report_dtls.smrd_mis_report_id')
                    ->orderBy('scheduled_mis_report_dtls.smrd_id','DESC')
                    ->get()
                    ->toArray();
                    
        if(!empty($returnData)){
            foreach($returnData as $key => $values){
                $values->smrd_to_email_address = DB::table('scheduled_mis_report_dtls')
                                                ->join('divisions','divisions.division_id','scheduled_mis_report_dtls.smrd_division_id')
                                                ->join('product_categories','product_categories.p_category_id','scheduled_mis_report_dtls.smrd_product_category_id')
                                                ->join('mis_report_default_types','mis_report_default_types.mis_report_id','scheduled_mis_report_dtls.smrd_mis_report_id')
                                                ->where('scheduled_mis_report_dtls.smrd_division_id',$values->smrd_division_id)
                                                ->where('scheduled_mis_report_dtls.smrd_product_category_id',$values->smrd_product_category_id)
                                                ->where('scheduled_mis_report_dtls.smrd_mis_report_id',$values->smrd_mis_report_id)
                                                ->whereNotNull('scheduled_mis_report_dtls.smrd_to_email_address')
                                                ->pluck('scheduled_mis_report_dtls.smrd_to_email_address')
                                                ->all();
                $values->smrd_from_email_address = DB::table('scheduled_mis_report_dtls')
                                                ->join('divisions','divisions.division_id','scheduled_mis_report_dtls.smrd_division_id')
                                                ->join('product_categories','product_categories.p_category_id','scheduled_mis_report_dtls.smrd_product_category_id')
                                                ->join('mis_report_default_types','mis_report_default_types.mis_report_id','scheduled_mis_report_dtls.smrd_mis_report_id')
                                                ->where('scheduled_mis_report_dtls.smrd_division_id',$values->smrd_division_id)
                                                ->where('scheduled_mis_report_dtls.smrd_product_category_id',$values->smrd_product_category_id)
                                                ->where('scheduled_mis_report_dtls.smrd_mis_report_id',$values->smrd_mis_report_id)
                                                ->whereNotNull('scheduled_mis_report_dtls.smrd_from_email_address')
                                                ->pluck('scheduled_mis_report_dtls.smrd_from_email_address')
                                                ->all();
            }
        }
        
        //to formate created and updated date
	$models->formatTimeStampFromArray($returnData,DATETIMEFORMAT);
        
        //echo '<pre>';print_r($returnData);die;
        return response()->json(['error' => $error,'message' => $message,'scheduledMisReportList' => $returnData]);
        
    }

    /**
    * Saving of MIS Scheduled Report
    * Created By : Praveen Singh
    * created On : 20-June-2019
    */
    public function saveScheduledMisReport(Request $request){
        
        global $order,$models,$schMisRepDtl;
        
        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = array();
        
        try{
            //Saving record in orders table
            if(!empty($request->formData) && $request->isMethod('post')){
    
                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                //echo '<pre>';print_r($formData);die;
                
                if(empty($formData['smrd_division_id'])){
		    $message = config('messages.message.required');
		}else if(empty($formData['smrd_product_category_id'])){
		    $message = config('messages.message.required');
                }else if(empty($formData['smrd_mis_report_id'])){
		    $message = config('messages.message.required');
                }else if(empty($formData['smrd_to_email_address'])){
		    $message = config('messages.message.required');
                }else if($schMisRepDtl->validateMisReportExistence($formData)){
		    $message = config('messages.message.existError');
                }else{
                    
                    //Unsetting the variable from request data
		    $formData = $models->unsetFormDataVariables($formData,array('_token'));
			    
                    //Getting formatted primary and Seconday Data
                    list($formDataPrimary,$formDataSeconday) = $schMisRepDtl->formatPriSecScheduledEmailData($formData);
                    
                    //Saving primary Email Data
                    $primaryStatus = !empty($formDataPrimary) ? DB::table('scheduled_mis_report_dtls')->insert($formDataPrimary) : '';
                    
                    //Saving seconday Email Data
                    $secondaryStatus = !empty($formDataSeconday) ? DB::table('scheduled_mis_report_dtls')->insert($formDataSeconday) : '';
                    
                    if($primaryStatus || $secondaryStatus){
                        $error   = '1';
                        $message = config('messages.message.success');
                    }
                }
            }        
        } catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.savedError');
        } 
        
        return response()->json(['error' => $error,'message' => $message,'data' => $data]);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editScheduledMisReport(Request $request){
        
        global $models,$schMisRepDtl;
        
        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = array();
        
        try{
            //Saving record in orders table
            if(!empty($request->formData) && $request->isMethod('post')){
    
                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                
                if(!empty($formData['id'])){
                    $error  = '1';
                    $message = '';
                    $returnData = $schMisRepDtl->getRow($formData['id']);
                }else{
                    $message = config('messages.message.error');
                }
            }
        } catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.savedError');
        }
        
        return response()->json(['error' => $error, 'message' => $message, 'editScheduledMisReportList' => $returnData]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateScheduledMisReport(Request $request){
        
        global $models,$schMisRepDtl;
        
        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = array();
        
        try{
            //Saving record in orders table
            if(!empty($request->formData) && $request->isMethod('post')){
    
                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                
                if(empty($formData['smrd_division_id'])){
		    $message = config('messages.message.required');
		}else if(empty($formData['smrd_product_category_id'])){
		    $message = config('messages.message.required');
                }else if(empty($formData['smrd_mis_report_id'])){
		    $message = config('messages.message.required');
                }else if(empty($formData['smrd_to_email_address'])){
		    $message = config('messages.message.required');
                }else{
                    
                    //Unsetting the variable from request data
		    $formData = $models->unsetFormDataVariables($formData,array('_token'));
                    
                    //Deleting the previous entry
                    DB::table('scheduled_mis_report_dtls')->where('scheduled_mis_report_dtls.smrd_division_id',$formData['smrd_division_id'])->where('scheduled_mis_report_dtls.smrd_product_category_id',$formData['smrd_product_category_id'])->where('scheduled_mis_report_dtls.smrd_mis_report_id',$formData['smrd_mis_report_id'])->delete();
                        
                    //Getting formatted primary and Seconday Data
                    list($formDataPrimary,$formDataSeconday) = $schMisRepDtl->formatPriSecScheduledEmailData($formData);
                    
                    //Saving primary Email Data
                    $primaryStatus = !empty($formDataPrimary) ? DB::table('scheduled_mis_report_dtls')->insert($formDataPrimary) : '';
                    
                    //Saving seconday Email Data
                    $secondaryStatus = !empty($formDataSeconday) ? DB::table('scheduled_mis_report_dtls')->insert($formDataSeconday) : '';
                    
                    if($primaryStatus || $secondaryStatus){
                        $error   = '1';
                        $message = config('messages.message.updated');
                    }else{
                        $message = config('messages.message.updatedError');
                    }
                }
            }
        } catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.updatedError');
        }
        
        return response()->json(['error' => $error, 'message' => $message]);        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteScheduledMisReport(Request $request){

	global $models,$schMisRepDtl;
        
        $error     = '0';
        $message   = config('messages.message.error');
        $data      = '';
        $formData  = array();
        
        try{            
            //Saving record in orders table
            if(!empty($request->formData) && $request->isMethod('post')){
    
                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                
                if(empty($formData['smrd_id'])){
		    $message = config('messages.message.error');
                }else{                    
                    //Getting primary Id of the Table
                    $schMisReportDtlData = $schMisRepDtl->getRow(!empty($formData['smrd_id']) ? $formData['smrd_id'] : '0');
                    if(!empty($schMisReportDtlData) && DB::table('scheduled_mis_report_dtls')->where('scheduled_mis_report_dtls.smrd_division_id',$schMisReportDtlData->smrd_division_id)->where('scheduled_mis_report_dtls.smrd_product_category_id',$schMisReportDtlData->smrd_product_category_id)->where('scheduled_mis_report_dtls.smrd_mis_report_id',$schMisReportDtlData->smrd_mis_report_id)->delete()){
                        $error    = '1';
                        $message = config('messages.message.deleted');
                    }else{
                        $message = config('messages.message.deletedError');
                    }
                }
            }
        }catch(\Illuminate\Database\QueryException $ex){
            $message = config('messages.message.deletedError');
        }

	return response()->json(['error' => $error,'message' => $message]);
    }

}
