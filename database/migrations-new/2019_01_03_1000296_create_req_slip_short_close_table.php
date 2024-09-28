<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReqSlipShortCloseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_slip_short_close', function (Blueprint $table) {
	    $table->increments('req_slip_short_close_id');
	    $table->string('req_slip_short_close_dt');
	    $table->string('req_slip_short_close_by');
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
        Schema::dropIfExists('req_slip_short_close');
    }
}
