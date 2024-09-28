<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentReceivedHdrTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('payment_received_hdr', function (Blueprint $table){
            $table->increments('payment_received_hdr_id');			
	    $table->integer('division_id')->unsigned()->index()->nullable();
	    $table->integer('customer_id')->unsigned()->index()->nullable();
	    $table->integer('payment_source_id')->unsigned()->index()->nullable();
	    $table->string('payment_received_no')->unique();
	    $table->date('payment_received_date');				
	    $table->decimal('payment_amount_received', 10, 2);			
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->timestamps();			
	    $table->foreign('created_by')->references('id')->on('users');
	    $table->foreign('division_id')->references('division_id')->on('divisions');	
	    $table->foreign('customer_id')->references('customer_id')->on('customer_master');
	    $table->foreign('payment_source_id')->references('payment_source_id')->on('payment_sources');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
