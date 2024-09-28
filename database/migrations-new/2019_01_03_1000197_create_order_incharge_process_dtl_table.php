<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInchargeProcessDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        Schema::create('order_incharge_process_dtl', function (Blueprint $table) {
            $table->increments('oipd_id');
	    $table->integer('oipd_order_id')->unsigned()->index();
            $table->integer('oipd_incharge_id')->unsigned()->index();
	    $table->integer('oipd_analysis_id')->unsigned()->index();
            $table->integer('oipd_user_id')->unsigned()->index();
            $table->datetime('oipd_date')->unsigned()->index();	
            $table->tinyInteger('oipd_status')->nullable()->comment('1 for current,2 for previous');
	    $table->foreign('oipd_incharge_id')->references('oid_id')->on('order_incharge_dtl');
            $table->foreign('oipd_user_id')->references('id')->on('users');
            $table->foreign('oipd_order_id')->references('order_id')->on('order_master');        
            $table->foreign('oipd_analysis_id')->references('analysis_id')->on('order_parameters_detail');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_incharge_process_dtl');

    }
}
