<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReqSlipHdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('req_slip_hdr', function (Blueprint $table) {
	    $table->increments('req_slip_id');
	    $table->date('req_slip_date');
	    $table->string('req_slip_no');
	    $table->integer('req_department_id')->unsigned()->index();
	    $table->foreign('req_department_id')->references('department_id')->on('departments');
	    $table->integer('req_by')->unsigned()->index();
	    $table->foreign('req_by')->references('id')->on('users');
	    $table->tinyInteger('status')->default(1);
	    $table->date('short_close_date');
	    $table->integer('division_id')->unsigned()->index();
	    $table->foreign('division_id')->references('division_id')->on('divisions');			
	    $table->integer('created_by')->unsigned()->index();
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
        Schema::dropIfExists('req_slip_hdr');
    }
}
