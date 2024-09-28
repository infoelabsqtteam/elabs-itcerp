<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceHdrDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_hdr_detail', function (Blueprint $table) {
            $table->increments('invoice_dtl_id');
            $table->tinyInteger('invoice_hdr_status')->nullable()->comment('1 for active,2 for cancelled');
            $table->integer('invoice_hdr_id')->unsigned()->index();
            $table->integer('order_id')->unsigned()->index();
            $table->integer('order_invoicing_to')->unsigned()->index()->nullable()->comment('Alais Customer Id');            
            $table->decimal('order_amount', 10, 2);
            $table->decimal('order_discount', 10, 2)->nullable();
            $table->decimal('extra_amount', 10, 2)->nullable();
            $table->decimal('surcharge_amount', 10, 2)->nullable();            
            $table->decimal('order_total_amount', 10, 2)->nullable();
            $table->decimal('order_sgst_rate', 10, 2)->nullable();
            $table->decimal('order_sgst_amount', 10, 2)->nullable();
            $table->decimal('order_cgst_rate', 10, 2)->nullable();
            $table->decimal('order_cgst_amount', 10, 2)->nullable();
            $table->decimal('order_igst_rate', 10, 2)->nullable();
            $table->decimal('order_igst_amount', 10, 2)->nullable();
            $table->decimal('order_net_amount', 10, 2)->nullable();            
            $table->timestamps();            
            $table->foreign('invoice_hdr_id')->references('invoice_id')->on('invoice_hdr')->onDelete('cascade');
            $table->foreign('order_invoicing_to')->references('customer_id')->on('customer_master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_detail');
    }
}