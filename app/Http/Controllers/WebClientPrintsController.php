<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

include_once(base_path().'\vendor\WebClientPrint\WebClientPrint.php');
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\ClientPrintJob;
use Neodynamic\SDK\Web\NetworkPrinter;
use Neodynamic\SDK\Web\UserSelectedPrinter;
use Neodynamic\SDK\Web\PrintFile;
use Neodynamic\SDK\Web\DefaultPrinter;

use App\Http\Requests;
use Auth;
use App\Order;
use App\Models;
use App\Report;
use App\Customer;
use App\PrintWebClient;
use Session;
use Validator;
use Route;
use DB;

class WebClientPrintsController extends Controller
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
        global $order,$models,$reports,$printWebClient;

	$order 	= new Order();
	$models = new Models();
	$reports = new Report();
        $printWebClient = new PrintWebClient();
        
        $this->middleware('auth'); 
        $this->middleware(function ($request, $next) {
        $this->session = Auth::user();
            parent::__construct($this->session);
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
    public function index()
    {
        global $order,$models,$reports,$printWebClient;
        
        $user_id            = defined('USERID') ? USERID : '0';
        $division_id   	    = defined('DIVISIONID') ? DIVISIONID : '0';		
	$department_ids     = defined('DEPARTMENT_IDS') ? DEPARTMENT_IDS : '0';
	$role_ids           = defined('ROLE_IDS') ? ROLE_IDS : '0';
    }
    
    /****************************************
    *function    : Printing multiple files is possible too!
    *Created By  : Praveen Singh
    *Created On  : 21-July-2018
    *Modified by : 
    *Modified On : 
    *****************************************/
    public function sendMultipleFileToPrinter(Request $request){
        
        global $order,$models,$reports,$printWebClient;
        
        //Create a ClientPrintJob and set the PrintFile objects
        $cpj = new ClientPrintJob();

        //Create array of PrintFile objects you want to print
        $fileGroup = array(
            new PrintFile(DOC_ROOT.REPORT_PATH.'ICF-1712110002.pdf', 'ICF-1712110002.pdf', NULL),
            new PrintFile(DOC_ROOT.REPORT_PATH.'ICF-1712110003.pdf', 'ICF-1712110002.pdf', NULL),
            new PrintFile(DOC_ROOT.REPORT_PATH.'ICF-1712130003.pdf', 'ICF-1712110002.pdf', NULL),
            new PrintFile(DOC_ROOT.REPORT_PATH.'ICF-1712160007.pdf', 'ICF-1712110002.pdf', NULL),         
        );

        //set files to print
        //$cpj->clientPrinter = new DefaultPrinter();
        $cpj->clientPrinter = new NetworkPrinter('192.168.10.10', '192.168.4.25', 9100);
        //$cpj->clientPrinter = new UserSelectedPrinter();
        $cpj->printFileGroup = $fileGroup;
        $cpj->sendToClient();
    }
}
