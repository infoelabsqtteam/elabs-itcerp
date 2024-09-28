<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrfHdrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trf_hdrs', function (Blueprint $table) {
            $table->increments('trf_id');
            $table->tinyInteger('trf_status')->nullable()->comment('0 for pending,2 for Booked');  
            $table->string('trf_no');
            $table->dateTime('trf_date');
            $table->integer('trf_division_id')->unsigned()->index();
            $table->foreign('trf_division_id')->references('division_id')->on('divisions');
            $table->integer('trf_product_category_id')->unsigned()->index();
            $table->foreign('trf_product_category_id')->references('p_category_id')->on('product_categories');
            $table->tinyInteger('trf_type')->nullable()->comment('1 for Master Data Selection,2 for Manual Data Addition');
            $table->integer('trf_customer_id')->unsigned()->index();
            $table->foreign('trf_customer_id')->references('customer_id')->on('customer_master');
            $table->integer('trf_product_test_id')->unsigned()->index()->nullable();
            $table->foreign('trf_product_test_id')->references('test_id')->on('product_test_hdr');
            //MasterData
            $table->integer('trf_test_standard_id')->unsigned()->index()->nullable();
            $table->foreign('trf_test_standard_id')->references('test_std_id')->on('test_standard');
            $table->integer('trf_p_category_id')->unsigned()->index()->nullable();
            $table->foreign('trf_p_category_id')->references('p_category_id')->on('product_categories');
	    $table->integer('trf_sub_p_category_id')->unsigned()->index()->nullable();
            $table->foreign('trf_sub_p_category_id')->references('p_category_id')->on('product_categories');
            $table->integer('trf_product_id')->unsigned()->index()->nullable();
            $table->foreign('trf_product_id')->references('product_id')->on('product_master');
            //ManualData
            $table->string('trf_test_standard_name')->nullable();
            $table->string('trf_product_name')->nullable();
            //CommonData
            $table->string('trf_sample_name');
            $table->string('trf_mfg_lic_no')->nullable();
            $table->string('trf_manufactured_by')->nullable();
	    $table->string('trf_supplied_by')->nullable();            
            $table->string('trf_mfg_date')->nullable();
	    $table->string('trf_expiry_date')->nullable();            
            $table->string('trf_batch_no')->nullable();
            $table->string('trf_batch_size')->nullable();
            $table->string('trf_sample_qty')->nullable();            
            $table->string('trf_reporting_to')->nullable();
	    $table->string('trf_invoicing_to')->nullable();
            $table->string('trf_reporting_address')->nullable();
            $table->string('trf_invoicing_address')->nullable();
            $table->integer('trf_storage_condition_id')->unsigned()->index()->nullable();
            $table->foreign('trf_storage_condition_id')->references('trf_sc_id')->on('trf_storge_condition_dtls');
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
        Schema::dropIfExists('trf_hdrs');
    }
}
