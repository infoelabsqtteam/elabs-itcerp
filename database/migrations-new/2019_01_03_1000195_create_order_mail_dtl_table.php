<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMailDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_mail_dtl', function (Blueprint $table) {
	    $table->increments('mail_id');           
	    $table->tinyInteger('mail_content_type')->comment('1-New Party Sample booked,2-order placed,3-Report Generation,4-invoice Generation,5-stability prototype order confirmation,6-stability order Notification');
	    $table->Integer('order_id')->unsigned()->index()->nullable();
	    $table->Integer('stb_order_hdr_id')->unsigned()->index()->nullable();
	    $table->Integer('invoice_id')->unsigned()->index()->nullable();
	    $table->Integer('customer_id')->unsigned()->index();
	    $table->text('mail_header')->nullable();
	    $table->text('mail_body')->nullable();
	    $table->date('mail_date');
	    $table->tinyInteger('mail_type')->comment('1 for final,2 for Draft')->nullable();
	    $table->Integer('mail_by')->nullable();
	    $table->tinyInteger('mail_status')->nullable();
	    $table->tinyInteger('mail_active_type')->comment('Latest Mail Send')->nullable();    	
	    $table->foreign('order_id')->references('order_id')->on('order_master');
	    $table->foreign('customer_id')->references('customer_id')->on('customer_master');
	    $table->foreign('mail_by')->references('id')->on('users');
	    $table->foreign('invoice_id')->references('invoice_id')->on('invoice_hdr');
	    $table->foreign('stb_order_hdr_id')->references('stb_order_hdr_id')->on('stb_order_hdr');
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
        Schema::dropIfExists('order_mail_dtl');
    }
}
