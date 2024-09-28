<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStbOrderHdrDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stb_order_hdr_dtl', function (Blueprint $table){
            $table->increments('stb_order_hdr_dtl_id');
            $table->integer('stb_order_hdr_id')->unsigned()->index();
            $table->foreign('stb_order_hdr_id')->references('stb_order_hdr_id')->on('stb_order_hdr')->onDelete('cascade');
            $table->integer('stb_product_id')->unsigned()->index();
            $table->foreign('stb_product_id')->references('product_id')->on('product_master');
            $table->integer('stb_test_standard_id')->unsigned()->index();
            $table->foreign('stb_test_standard_id')->references('test_std_id')->on('test_standard');
            $table->integer('stb_product_test_id')->unsigned()->index();
            $table->foreign('stb_product_test_id')->references('test_id')->on('product_test_hdr');
            $table->dateTime('stb_start_date');
            $table->dateTime('stb_end_date');
            $table->string('stb_label_name');
            $table->tinyInteger('stb_order_book_status')->nullable();
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
        Schema::dropIfExists('stb_order_hdr_dtl');
    }
}
