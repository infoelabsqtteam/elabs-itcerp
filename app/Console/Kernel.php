<?php
/*****************************************************
*Description : Common Function Configuration File
*Created By  : Praveen-Singh
*Created On  : 15-Dec-2017
*Modified On : 30-May-2019
*Package     : ITC-ERP-PKL
******************************************************/

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
   protected $commands = [
      Commands\VOC\CmdAutoVocMailDataCommand::class,                                   //Command FOR VOC
      Commands\VOC\CmdAutoVocMailSentCommand::class,                                   //Command FOR VOC
      Commands\OrderConfirmation\CmdAutoOrderConfirmationMailSentCommand::class,       //Command For order Confirmation Mail Sending
      Commands\WebModules\CmdAutoCopyOrdersWithStatusCommand::class,                   //Command For Auto Synchronization of Orders With Status for Web Module.
      Commands\ScheduledMisReport\CmdAutoScheduledMisReportMailSentCommand::class,     //Command For Auto Mailing of Scheduled MIS Reports.
      Commands\StorageCount\StrorageCount::class,                                      //Command For Counting the Storage.
   ];

   /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
   protected function schedule(Schedule $schedule){
      
      //***********AUTO MAILING Module**********************************************************************************
      
      //Command FOR VOC
      $schedule->command('command:VOC.CmdAutoVocMailDataCommand')->everyMinute();
      $schedule->command('command:VOC.CmdAutoVocMailSentCommand')->everyMinute();
      
      //Command For order Confirmation mail sending
      $schedule->command('command:OrderConfirmation.CmdAutoOrderConfirmationMailSentCommand')->everyMinute();
      
      //Command For Auto Mailing of Scheduled MIS Reports.
      $schedule->command('command:ScheduledMisReport.CmdAutoScheduledMisReportMailSentCommand')->everyMinute();
      
      //***********/AUTO MAILING Module**********************************************************************************
      
      //***********WEB-MODULE : AUTO DATA SYNCHRONIZATION MODULE*********************************************************
      
      //Auto Synchronization of Orders With Status for Web Module.
      $schedule->command('command:WebModules.CmdAutoCopyOrdersWithStatusCommand')->everyMinute();
      
      //***********/WEB-MODULE : AUTO DATA SYNCHRONIZATION MODULE**********************************************************
   }

   /**
   * Register the Closure based commands for the application.
   *
   * @return void
   */
   protected function commands(){      
      require base_path('routes/console.php');
   }
   
}
