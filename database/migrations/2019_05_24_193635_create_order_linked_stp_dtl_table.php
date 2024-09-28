<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLinkedStpDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_linked_stp_dtl', function (Blueprint $table) {
            $table->increments('olsd_id');
            $table->integer('olsd_order_id')->unsigned()->index()->unique();
            $table->foreign('olsd_order_id')->references('order_id')->on('order_master');
            $table->integer('olsd_cstp_id')->unsigned()->index();            
            $table->foreign('olsd_cstp_id')->references('cstp_id')->on('central_stp_dtls');
            $table->string('olsd_cstp_no');
            $table->string('olsd_cstp_file_name');
            $table->string('olsd_cstp_sample_name');
            $table->datetime('olsd_date');
            $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_linked_stp_dtl');
    }
}
