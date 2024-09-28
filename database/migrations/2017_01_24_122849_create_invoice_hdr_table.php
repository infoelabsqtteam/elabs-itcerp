<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceHdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_hdr', function (Blueprint $table) {
	    $table->engine = 'InnoDB';
	    $table->increments('invoice_id');
	    $table->tinyInteger('invoice_status');
	    $table->integer('division_id')->unsigned()->index();
	    $table->integer('product_category_id')->unsigned()->index();
	    $table->string('invoice_no');
	    $table->string('invoice_file_name');
	    $table->string('invoice_file_name_without_hf');
	    $table->datetime('invoice_date');
	    $table->string('invoice_type');
	    $table->integer('customer_id')->unsigned()->index();
	    $table->integer('customer_invoicing_id')->unsigned()->index();
	    $table->decimal('total_amount', 10, 2);
	    $table->decimal('total_discount', 10, 2);
	    $table->decimal('surcharge_amount', 10, 2)->nullable();
	    $table->decimal('extra_amount', 10, 2)->nullable();
	    $table->decimal('net_amount', 10, 2);
	    $table->decimal('sgst_rate', 10, 2);
	    $table->decimal('sgst_amount', 10, 2);
	    $table->decimal('cgst_rate', 10, 2);
	    $table->decimal('cgst_amount', 10, 2);
	    $table->decimal('igst_rate', 10, 2);
	    $table->decimal('igst_amount', 10, 2);
	    $table->decimal('net_total_amount', 10, 2);
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->foreign('created_by')->references('id')->on('users');
	    $table->timestamps();            
	    $table->foreign('division_id')->references('division_id')->on('divisions');
	    $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');            
	    $table->foreign('customer_id')->references('customer_id')->on('customer_master');
	    $table->foreign('customer_invoicing_id')->references('customer_id')->on('customer_master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_hdr');
    }
}
