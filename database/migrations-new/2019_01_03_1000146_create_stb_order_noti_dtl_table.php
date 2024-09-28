<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStbOrderNotiDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stb_order_noti_dtl', function (Blueprint $table){            
            $table->increments('stb_order_noti_dtl_id');
            $table->integer('stb_order_hdr_id')->unsigned()->index();
            $table->foreign('stb_order_hdr_id')->references('stb_order_hdr_id')->on('stb_order_hdr')->onDelete('cascade');            
            $table->integer('stb_order_hdr_dtl_id')->unsigned()->index();
            $table->foreign('stb_order_hdr_dtl_id')->references('stb_order_hdr_dtl_id')->on('stb_order_hdr_dtl')->onDelete('cascade');
            $table->dateTime('stb_order_noti_dtl_date');
            $table->dateTime('stb_order_noti_confirm_date')->nullable();
            $table->integer('stb_order_noti_confirm_by')->unsigned()->index()->nullable();
            $table->foreign('stb_order_noti_confirm_by')->references('id')->on('users');            
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
        Schema::dropIfExists('stb_order_noti_dtl');
    }
}
