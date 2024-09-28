<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceCancellationDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_cancellation_dtls', function (Blueprint $table) {
            $table->increments('invoice_cancellation_id');
            $table->integer('invoice_id')->unsigned()->index();
            $table->datetime('invoice_cancelled_date');
            $table->text('invoice_cancellation_description')->nullable();
            $table->integer('invoice_cancelled_by')->unsigned()->index();
            $table->timestamps();
            
            $table->foreign('invoice_id')->references('invoice_id')->on('invoice_hdr');
            $table->foreign('invoice_cancelled_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_cancellation_dtls');
    }
}
