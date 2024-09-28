<?php

/*****************************************************
 *Description : Auto Synchronization of Orders With Status for Web Module.
 *Created By  : Praveen-Singh
 *Created On  : 08-April-2019
 *Modified On : 08-April-2019
 ******************************************************/

namespace App\Console\Commands\WebModules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models;
use App\Order;
use App\Report;
use App\Customer;
use App\NumbersToWord;
use App\InvoiceHdr;
use App\AutoDataSynchronization;
use DB;

class CmdAutoCopyOrdersWithStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WebModules.CmdAutoCopyOrdersWithStatusCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description:Auto Copying of Orders with Status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        global $models, $order, $report, $invoice, $numbersToWord, $autoSynCommand;

        $models     = new Models();
        $order      = new Order();
        $report     = new Report();
        $invoice    = new invoiceHdr();
        $numbersToWord  = new NumbersToWord();
        $autoSynCommand = new AutoDataSynchronization();

        //function for getting constants names (17-08-2017)
        $models->getDefaultSetting();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        global $models, $order, $report, $invoice, $numbersToWord, $autoSynCommand;

        $autoSynCommand->funSynActionType(array('actionContentType' => '1'));
    }
}
