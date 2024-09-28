<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStbOrderHdrDtlDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stb_order_hdr_dtl_detail', function (Blueprint $table) {
            $table->increments('stb_order_hdr_detail_id');            
            $table->integer('stb_order_hdr_id')->unsigned()->index();
            $table->foreign('stb_order_hdr_id')->references('stb_order_hdr_id')->on('stb_order_hdr')->onDelete('cascade');            
            $table->integer('stb_order_hdr_dtl_id')->unsigned()->index();
            $table->foreign('stb_order_hdr_dtl_id')->references('stb_order_hdr_dtl_id')->on('stb_order_hdr_dtl')->onDelete('cascade');
            $table->integer('stb_stability_type_id')->unsigned()->index();
            $table->foreign('stb_stability_type_id')->references('stb_stability_type_id')->on('stb_order_stability_types');
            $table->integer('stb_product_test_dtl_id')->unsigned()->index();
            $table->foreign('stb_product_test_dtl_id')->references('product_test_dtl_id')->on('product_test_dtl');
            $table->string('stb_dtl_sample_qty');
            $table->string('stb_condition_temperature');
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
        Schema::dropIfExists('stb_order_hdr_dtl_detail');
    }
}
