<?php

namespace App\Console\Commands\VOC;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models;
use App\Order;
use App\Report;
use App\Customer;
use App\InvoiceHdr;
use App\AutoCommand;
use DB;

class CmdAutoVocMailDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:VOC.CmdAutoVocMailDataCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'VOC Mail Data Saving Command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        global $models, $order, $report, $invoice, $command;

        $models   = new Models();
        $order    = new Order();
        $report   = new Report();
        $invoice  = new invoiceHdr();
        $command  = new AutoCommand();

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

        global $models, $order, $report, $invoice, $command;

        $command->saveAutoMail(array('mailSavingType' => '1'));
    }
}
