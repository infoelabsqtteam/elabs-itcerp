<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReqSlipDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_slip_dtl', function (Blueprint $table) {
	    $table->increments('req_slip_dlt_id');
	    $table->integer('req_slip_hdr_id')->unsigned()->index();
	    $table->foreign('req_slip_hdr_id')->references('req_slip_id')->on('req_slip_hdr')->onDelete('cascade');
	    $table->integer('item_id')->unsigned()->index();
	    $table->foreign('item_id')->references('item_id')->on('item_master');
	    $table->integer('required_qty');
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
        Schema::dropIfExists('req_slip_dtl');
    }
}
