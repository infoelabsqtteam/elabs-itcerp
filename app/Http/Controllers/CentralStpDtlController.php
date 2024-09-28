<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use App\CentralStpDtl;
use App\FileUpload;
use Session;
use Validator;
use Route;
use DB;
use File;

class CentralStpDtlController extends Controller
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

	global $models,$centralStpDtl,$fileUpload;

	$models = new Models();
        $centralStpDtl = new CentralStpDtl();
        $fileUpload = new FileUpload();
	
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
        
        global $models,$centralStpDtl,$fileUpload;
        
        $user_id            = defined('USERID') ? USERID : '0';
        $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		
        return view('master.central_stp_master.index',['title' => 'Central STPs','_central_stp_master' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request){
        
        global $models,$centralStpDtl,$fileUpload;
        
        $error      = '0';
        $message    = '';
        $data       = array();
        $formData   = $centralStpContentList = array();

	try {
            $data = DB::table('central_stp_dtls')
                    ->join('customer_master','customer_master.customer_id','central_stp_dtls.cstp_customer_id')
                    ->join('city_db','city_db.city_id','central_stp_dtls.cstp_customer_city')
                    ->select('central_stp_dtls.*','customer_master.customer_name as cstp_customer_name','city_db.city_name as cstp_customer_city_name')
                    ->orderBy('central_stp_dtls.cstp_id','ASC')
                    ->get()
                    ->toArray();
    
            //to formate created and updated date
            $models->formatTimeStampFromArray($data,DATETIMEFORMAT);
            
        } catch(\Exception $e){
            $message = config('messages.message.error');
	}
        return response()->json(array('error'=> $error,'message'=> $message, 'centralStpContentList' => $data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createCentralStp(Request $request){
        
        global $models,$centralStpDtl,$fileUpload;
        
        $error      = '0';
        $message    = config('messages.message.error');
        $allowedExt = array('jpg','jpeg','pdf','JPG','JPEG','PDF');
        $rootPath   = defined('ROOT_DIR') ? ROOT_DIR : '/opt/lampp/htdocs/itcerp';
        $stpPath    = defined('STP_PATH') ? STP_PATH : '/public/images/sales/stps/';
        $formData   = $fileData = array();
        
        try {
            if($request->isMethod('post')){
                
                //Starting transaction
                DB::beginTransaction();
                
                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                
                if(empty($request->hasFile('fileData'))){
                    $message = config('messages.message.cstpFileErrorRequired');
                }else if(empty($formData['cstp_no'])){
                    $message = config('messages.message.cstpNoRequired');
                }else if(empty($formData['cstp_customer_id'])){
                    $message = config('messages.message.cstpCustomerNameRequired');
                }else if(empty($formData['cstp_customer_city'])){
                    $message = config('messages.message.cstpCustomerCityRequired');
                }else if(empty($formData['cstp_sample_name'])){
                    $message = config('messages.message.cstpSampleNameRequired');
                }else if(empty($formData['cstp_date'])){
                    $message = config('messages.message.cstpDateRequired');
                }else if(empty($formData['cstp_status'])){
                    $message = config('messages.message.cstpStatusRequired');
                }else if(!$fileUpload->validateFileExtension($fileInputType='fileData',$request,$allowedExt)){
                    $message = config('messages.message.cstpFileInvalidErrorMsg');
                }else if($centralStpDtl->validationPostedStpDtl($formData)){
                    $message = config('messages.message.exist');
                }else{
                    
                    //Creating File Name
                    $fileNameWithOutExt = strtolower($formData['cstp_no']);
                    
                    //Unsetting the variable from request data
		    $formData = $models->unsetFormDataVariables($formData,array('_token'));
                    
                    //Assign Default value to the formdata
                    $formData['cstp_file_name'] = '1';
                    $formData['cstp_date']      = $models->getFormatedDateTime($formData['cstp_date'], $format='Y-m-d');
                    $formData['created_by']     = USERID;
                    
                    //Saving Record to central_stp_dtls
		    $cstpId = DB::table('central_stp_dtls')->insertGetId($formData);
                    
                    //File Uploading
                    $cstp_file_name = $fileUpload->media_uploads($rootPath,$stpPath,$fileInputType='fileData',$request,$fileNameWithOutExt);
                    if(!empty($cstpId) && !empty($cstp_file_name)){
                        
                        //Updating FIle Name
                        DB::table('central_stp_dtls')->where('central_stp_dtls.cstp_id',$cstpId)->update(['central_stp_dtls.cstp_file_name' => $cstp_file_name]);
                    
                        $error   = '1';
                        $message = config('messages.message.saved');
                        
                        //Committing the queries
                        DB::commit();
                    }
                }
            }
        } catch(\Exception $e){
            $message = config('messages.message.savedError');
	}
        
        return response()->json(array('error'=> $error, 'message'=> $message));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyStpDtl(Request $request){
        
        global $models,$centralStpDtl;
        
        $error      = '0';
        $message    = config('messages.message.error');
        $rootPath   = defined('ROOT_DIR') ? ROOT_DIR : '/opt/lampp/htdocs/itcerp';
        $stpPath    = defined('STP_PATH') ? STP_PATH : '/public/images/sales/stps/';
        $data       = array();
        $formData   = array();
        
        try {
            if($request->isMethod('post') && !empty($request->cstp_id)){
                $stpData = $centralStpDtl->getRow($request->cstp_id);
                if(!empty($stpData->cstp_id)){
                    
                    //Removing File Directory
                    if(!empty($stpData->cstp_file_name)){
                        $fileNameDir = $rootPath.$stpPath.$stpData->cstp_file_name;
                        if(file_exists($fileNameDir))File::delete($fileNameDir);
                    }                    
                    //Deleting from table
                    if(DB::table('central_stp_dtls')->where('central_stp_dtls.cstp_id','=',$stpData->cstp_id)->delete()){
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
}
