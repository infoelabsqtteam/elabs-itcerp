<?php

namespace App\Http\Controllers;
use Cookie;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Company;
use App\ProductCategory;
use App\User;
use Session;
use Validator;
use DB;
use Crypt;
use App\Helpers\Helper;
use Illuminate\Http\Response;
use App\Order;
use App\Models;
use App\Report;
use App\Dashboard;
use DNS1D;
use PDF;
use TCPDF;
use Illuminate\Support\Facades\Mail;


class DashboardController extends Controller
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
	
	global $models,$order,$report,$dashboard;

	$models = new Models();
	$order  = new Order();
	$report = new Report();
	$dashboard  = new Dashboard();
	$this->middleware('auth');
	
        $this->middleware(function($request,$next) {
            $this->auth = Auth::user();
	    parent::__construct($this->auth);
            return $next($request);
        });
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
	
	global $models,$order,$report,$dashboard;
	
	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_id            = defined('ROLEID') ? ROLEID : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : '0';
	$updateInchargeDtl  = $order->saveUpdateOrderInchargeDetailOnLogin($division_id,$department_ids,$role_id);
	
	return view('dashboard.index',['title' => 'Dashboard','_dashboard' => 'active','user_id' => $user_id,'division_id' => $division_id,'equipment_type_ids' => $equipment_type_ids]);
    }
    
    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Http\Response
    */
    public function multiSessionError(Request $request){
	
	global $models,$order,$dashboard;
	
        return view('errors.multiSessionError',['title' => 'Multi-Session-Error','_multiSessionError' => 'active']);
    }
    
    /**
    * Log the user out of the application.
    *
    * @return \Illuminate\Http\Response
    */
    public function getDashboardContent(Request $request){
	
	global $models,$order,$dashboard;
	
	$error	  	    = '0';
        $message  	    = config('messages.message.error');
	$notRequriedRoles   = array('1','2','3','14','15');
	$user_id            = defined('USERID') ? USERID : '0';
	$division_id   	    = defined('DIVISIONID') ? array_filter(array(DIVISIONID)) : array();
	$department_ids     = defined('DEPARTMENT_IDS') ? array_filter(DEPARTMENT_IDS) : array();
	$role_id            = defined('ROLEID') ? ROLEID : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
	$equipment_type_ids = defined('EQUIPMENT_TYPE_IDS') ? EQUIPMENT_TYPE_IDS : array();
	$roleData	    = DB::table('roles')->where('roles.id',$role_id)->pluck('id')->all();
	$returnData	    = array();
	
	if(!empty($roleData)){
	    foreach($roleData as $key => $role){
		
		$roleDetail = DB::table('roles')->where('roles.id',$role)->first();
		$columnName	= strtolower(!empty($roleDetail->name) ? str_replace(' ','_',$roleDetail->name) : $roleDetail->name);
		
		if($role == '4'){		//Order Booker
		    $returnData[$columnName] = $dashboard->getOrderBookerContent($division_id,$department_ids,$roleDetail);
		}
		if($role == '5'){		//Scheduler
		    $returnData[$columnName] = $dashboard->getSchedulerContent($division_id,$department_ids,$roleDetail);
		}
		if($role == '6'){		//Tester
		    $returnData[$columnName] = $dashboard->getTesterContent($user_id,$division_id,$department_ids,$roleDetail);
		}
		if($role == '8'){		//Reviewer
		    $returnData[$columnName] = $dashboard->getReviewerContent($division_id,$department_ids,$roleDetail);
		}
		if($role == '9'){		//Finalizer
		    $returnData[$columnName] = $dashboard->getFinalizerContent($division_id,$department_ids,$roleDetail);
		}
		if($role == '10'){		//Approval(QA)
		    $returnData[$columnName] = $dashboard->getApprovalContent($division_id,$department_ids,$roleDetail);
		}
		if($role == '11'){		//Invoicer
		    $returnData[$columnName] = $dashboard->getInvoicerContent($division_id,$department_ids,$roleDetail);
		}
		if($role == '12'){		//Dispatcher
		    $returnData[$columnName] = $dashboard->getDispatcherContent($division_id,$department_ids,$roleDetail);
		}
		if($role == '13'){		//CRM
		    $returnData[$columnName] = $dashboard->getCRMContent($division_id,$department_ids,$roleDetail);
		}
	    }
	    
	    $returnData[$columnName]['tableHead'] = !empty($returnData[$columnName]['tableBody']) ? array_keys(reset($returnData[$columnName]['tableBody'])) : array();		
	    $error   = !empty($returnData[$columnName]['tableBody']) ? '1' : '0';
	    $message = '';
	}
	
	//echo '<pre>';print_r($returnData);die;	
	return response()->json(['error' => $error, 'message' => $message, 'returnData' => $returnData]);	
    }

    public function sendTestMail(Request $request) {
	    $data = $request->all();

	    //$messageBody = $this->getMessageBody($data);

	    Mail::raw('I am test', function ($message) {
	        $message->from('karan.parihar5@gmail.com', 'Testing ITC LAB EMAIL');
	        $message->to('karanparihar5@gmail.com');
	        $message->subject('Testing ITC LAB EMAIL');
	    });

	    // check for failures
	    if (Mail::failures()) {
	    	dd('ddd');
	        // return response showing failed emails
	    }
dd('success');
	    // otherwise everything is okay ...
	    return redirect()->back();
	}

}
