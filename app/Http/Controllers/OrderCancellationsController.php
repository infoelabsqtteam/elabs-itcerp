<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Sample;
use App\Order;
use App\Models;
use App\Setting;
use App\ProductCategory;
use App\SendMail;
use App\Report;
use App\Customer;
use App\OrderCancellationDtl;
use Session;
use Validator;
use Route;
use DB;

class OrderCancellationsController extends Controller
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

	global $sample,$order,$models,$reports,$orCanDtl;

	$sample   = new Sample();
	$order 	  = new Order();
	$models   = new Models();
	$reports  = new Report();
	$orCanDtl = new OrderCancellationDtl();
        
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
        
        global $models,$order,$orCanDtl;

	$user_id               = defined('USERID') ? USERID : '0';
	$division_id   	       = defined('DIVISIONID') ? DIVISIONID : '0';
	$department_ids        = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids              = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids    = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
    }
    
    /***********************
    * Cancel orders with proper reason
    * @param  int  $id
    * @return \Illuminate\Http\Response
    **********************/
    public function cancelOrderBooking(Request $request){

	global $models,$order,$orCanDtl;

	$error    = '0';
	$message  = config('messages.message.error');
	$data     = '';
	$formData = array();

        //Saving record in orders table
	if(!empty($request->formData) && $request->isMethod('post')){

	    //Parsing the Serialze Dta
	    parse_str($request->formData, $formData);
                     
            if(empty($formData['order_id'])){
                $message = config('messages.message.error');
	    }else if(empty($formData['cancellation_type_id'])){
                $message = config('messages.message.cancellationTypeRequired');
            }else if(empty($formData['cancellation_description'])){
                $message = config('messages.message.cancellationDescriptionRequired');
	    }else if(!empty($order->isOrderInvoiceGenerated($formData['order_id']))){
		$message = config('messages.message.cancellationInvalidRequired');
            }else{
                
                //Unsetting the variable from request data
		$formData  = $models->unsetFormDataVariables($formData,array('_token'));
                $formData['cancellation_stage']  = $order->getOrderDetail($formData['order_id'])->status;
                $formData['cancelled_date']      = CURRENTDATETIME;
                $formData['cancelled_by']        = USERID;
                $formData['cancellation_status'] = '1';
                //echo '<pre>';print_r($formData);die;
                
                $orderCancellationId = DB::table('order_cancellation_dtls')->insertGetId($formData);
                if(!empty($orderCancellationId)){
                    $order->updateOrderStausLog($formData['order_id'],'10');        //Updating Order Status to Cancelled Mode
                    $error   = '1';
                    $message = config('messages.message.OrderCancelMsg');
                }else{
                    $message = config('messages.message.OrderCancelFailedMsg');
                }
            }
        }
	return response()->json(['error' => $error,'message' => $message]);
    }
    
    /***********************
    * get Cancelled Order Detail
    * @param  int  $id
    * @return \Illuminate\Http\Response
    **********************/
    public function getCancelledOrderDetail(Request $request){

	global $models,$order,$orCanDtl;

	$error      = '0';
	$message    = config('message.message.error');
	$returnData = array();

	if(!empty($request->formData)){

	    //Parsing of Form Data
	    parse_str($request->formData, $formData);

	    $returnData = DB::table('order_cancellation_dtls')
                        ->join('order_master','order_cancellation_dtls.order_id','order_master.order_id')
                        ->join('order_cancellation_types','order_cancellation_types.order_cancellation_type_id','order_cancellation_dtls.cancellation_type_id')
			->join('users','order_cancellation_dtls.cancelled_by','users.id')
			->join('order_status','order_status.order_status_id','order_cancellation_dtls.cancellation_stage')
			->select('order_master.order_no','order_cancellation_dtls.*','order_status.order_status_name as stage_of_cancellation','order_cancellation_types.order_cancellation_type_name','users.name as cancelled_by_name')
                        ->where('order_cancellation_dtls.order_id','=',$formData['order_id'])
			->first();
	    
	    //to formate Dispatch date and Time
	    $models->formatTimeStamp($returnData,DATETIMEFORMAT);
	    $error = !empty($returnData) ? 1 : 0;
	}
	
	//echo'<pre>';print_r($returnData); die;
	return response()->json(array('error' => $error,'message' => $message,'cancelledOrderDetail' => $returnData));
    }

}
