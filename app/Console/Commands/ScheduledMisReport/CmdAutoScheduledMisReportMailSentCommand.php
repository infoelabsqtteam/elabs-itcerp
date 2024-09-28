<?php

namespace App\Console\Commands\ScheduledMisReport;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models;
use App\Order;
use App\Report;
use App\Customer;
use App\InvoiceHdr;
use App\ScheduledMisReportDtl;
use App\MISReport;
use App\AutoCommand;
use DB;

class CmdAutoScheduledMisReportMailSentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ScheduledMisReport.CmdAutoScheduledMisReportMailSentCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description : Auto Mailing of Scheduled MIS Reports';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        
        global $models,$order,$report,$invoice,$schMisRepDtl,$misReport,$command;
        
        $models     = new Models();
        $order      = new Order();
        $report     = new Report();
        $invoice    = new invoiceHdr();
        $schMisRepDtl = new ScheduledMisReportDtl();
        $misReport = new MISReport();
        $command    = new AutoCommand();
        
        //function for getting constants names
	$models->getDefaultSetting();
        
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        
        global $models,$order,$report,$invoice,$schMisRepDtl,$misReport,$command;
        
        $command->sendAutoMail(array('mailSendingType' => '3'));
    }
}
