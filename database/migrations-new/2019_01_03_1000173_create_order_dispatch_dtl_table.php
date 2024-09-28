<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDispatchDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_dispatch_dtl', function (Blueprint $table) {
	    $table->increments('dispatch_id');			
	    $table->integer('order_id')->unsigned()->index();
	    $table->foreign('order_id')->references('order_id')->on('order_master'); 
	    $table->integer('dispatch_by')->unsigned()->index();
	    $table->foreign('dispatch_by')->references('id')->on('users');
	    $table->string('ar_bill_no');
	    $table->datetime('dispatch_date');			
	    $table->tinyInteger('amend_status');           
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
        Schema::dropIfExists('order_dispatch_dtl');
    }
}
