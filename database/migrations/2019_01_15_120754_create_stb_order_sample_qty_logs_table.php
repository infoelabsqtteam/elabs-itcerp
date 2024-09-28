<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStbOrderSampleQtyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stb_order_sample_qty_logs', function (Blueprint $table) {
            $table->increments('stb_sq_logs_id');            
            $table->integer('stb_order_hdr_id')->unsigned()->index();
            $table->foreign('stb_order_hdr_id')->references('stb_order_hdr_id')->on('stb_order_hdr')->onDelete('cascade');
            $table->string('stb_log_sample_qty');
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
        Schema::dropIfExists('create_stb_order_sample_qty_logs');
    }
}
