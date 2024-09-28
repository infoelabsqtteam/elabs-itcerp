<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceDispatchDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_dispatch_dtls', function (Blueprint $table) {
            $table->increments('invoice_dispatch_id');			
            $table->integer('invoice_id')->unsigned()->index();
            $table->foreign('invoice_id')->references('invoice_id')->on('invoice_hdr');        
	    $table->integer('invoice_dispatch_by')->unsigned()->index();
	    $table->foreign('invoice_dispatch_by')->references('id')->on('users');
	    $table->string('ar_bill_no')->nullable();			
	    $table->tinyInteger('invoice_dispatch_status');           
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
        Schema::dropIfExists('invoice_dispatch_dtls');
    }
}
