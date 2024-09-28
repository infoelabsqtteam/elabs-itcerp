<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Models;
use App\CentralPoDtl;
use App\FileUpload;
use Session;
use Validator;
use Route;
use DB;
use File;

class CentralPoDtlController extends Controller
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

	global $models,$centralPoDtl,$fileUpload;

	$models = new Models();
        $centralPoDtl = new CentralPoDtl();
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
        
        global $models,$centralPoDtl,$fileUpload;
        
        $user_id            = defined('USERID') ? USERID : '0';
        $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
        $department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
        $role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';		
        $equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
		
        return view('master.central_po_master.index',['title' => 'Central POs','_central_po_master' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request){
        
        global $models,$centralPoDtl,$fileUpload;
        
        $error      = '0';
        $message    = '';
        $data       = array();
        $formData   = $centralStpContentList = array();

	try {
            $data = DB::table('central_po_dtls')
                    ->join('customer_master','customer_master.customer_id','central_po_dtls.cpo_customer_id')
                    ->join('city_db','city_db.city_id','central_po_dtls.cpo_customer_city')
                    ->select('central_po_dtls.*','customer_master.customer_name as cpo_customer_name','city_db.city_name as cpo_customer_city_name')
                    ->orderBy('central_po_dtls.cpo_id','ASC')
                    ->get()
                    ->toArray();
    
            //to formate created and updated date
            $models->formatTimeStampFromArray($data,DATETIMEFORMAT);
            
        } catch(\Exception $e){
            $message = config('messages.message.error');
	}
        return response()->json(array('error'=> $error,'message'=> $message, 'centralPoContentList' => $data));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createCentralPo(Request $request){
        
        global $models,$centralPoDtl,$fileUpload;
        
        $error      = '0';
        $message    = config('messages.message.error');
        $allowedExt = array('jpg','jpeg','pdf','JPG','JPEG','PDF');
        $rootPath   = defined('ROOT_DIR') ? ROOT_DIR : '/opt/lampp/htdocs/itcerp';
        $stpPath    = defined('PO_PATH') ? PO_PATH : '/public/images/sales/pos/';
        $formData   = $fileData = array();
        
        try {
            if($request->isMethod('post')){
                
                //Starting transaction
                DB::beginTransaction();
                
                //Parsing Serialize form data
                parse_str($request->formData, $formData);
                
                if(empty($request->hasFile('fileData'))){
                    $message = config('messages.message.cpoFileErrorRequired');
                }else if(empty($formData['cpo_no'])){
                    $message = config('messages.message.cpoNoRequired');
                }else if(empty($formData['cpo_customer_id'])){
                    $message = config('messages.message.cpoCustomerNameRequired');
                }else if(empty($formData['cpo_customer_city'])){
                    $message = config('messages.message.cpoCustomerCityRequired');
                }else if(empty($formData['cpo_sample_name'])){
                    $message = config('messages.message.cpoSampleNameRequired');
                }else if(empty($formData['cpo_date'])){
                    $message = config('messages.message.cpoDateRequired');
                }else if(empty($formData['cpo_amount'])){
                    $message = config('messages.message.cpoAmountRequired');
                }else if(empty($formData['cpo_status'])){
                    $message = config('messages.message.cpoStatusRequired');
                }else if(!$fileUpload->validateFileExtension($fileInputType='fileData',$request,$allowedExt)){
                    $message = config('messages.message.cpoFileInvalidErrorMsg');
                }else if($centralPoDtl->validationPostedPoDtl($formData)){
                    $message = config('messages.message.exist');
                }else{
                    
                    //Creating File Name
                    $fileNameWithOutExt = strtolower($formData['cpo_no']);
                    
                    //Unsetting the variable from request data
		    $formData = $models->unsetFormDataVariables($formData,array('_token'));
                    
                    //Assign Default value to the formdata
                    $formData['cpo_file_name'] = '1';
                    $formData['cpo_date']      = $models->getFormatedDateTime($formData['cpo_date'], $format='Y-m-d');
                    $formData['created_by']    = USERID;
                    
                    //Saving Record to central_po_dtls
		    $cpoId = DB::table('central_po_dtls')->insertGetId($formData);
                    
                    //File Uploading
                    $cpo_file_name = $fileUpload->media_uploads($rootPath,$stpPath,$fileInputType='fileData',$request,$fileNameWithOutExt);
                    if(!empty($cpoId) && !empty($cpo_file_name)){
                        
                        //Updating FIle Name
                        DB::table('central_po_dtls')->where('central_po_dtls.cpo_id',$cpoId)->update(['central_po_dtls.cpo_file_name' => $cpo_file_name]);
                    
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
    public function destroyPoDtl(Request $request){
        
        global $models,$centralPoDtl,$fileUpload;
        
        $error      = '0';
        $message    = config('messages.message.error');
        $rootPath   = defined('ROOT_DIR') ? ROOT_DIR : '/opt/lampp/htdocs/itcerp';
        $stpPath    = defined('PO_PATH') ? PO_PATH : '/public/images/sales/pos/';
        $formData   = array();
        
        try {
            if($request->isMethod('post') && !empty($request->cpo_id)){
                $poData = $centralPoDtl->getRow($request->cpo_id);
                if(!empty($poData->cpo_id)){
                    
                    //Removing File Directory
                    if(!empty($poData->cpo_file_name)){
                        $fileNameDir = $rootPath.$stpPath.$poData->cpo_file_name;
                        if(file_exists($fileNameDir))File::delete($fileNameDir);
                    }                    
                    //Deleting from table
                    if(DB::table('central_po_dtls')->where('central_po_dtls.cpo_id','=',$poData->cpo_id)->delete()){
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
