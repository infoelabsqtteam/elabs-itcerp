<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentralStpDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('central_stp_dtls', function (Blueprint $table) {
            $table->increments('cstp_id');
            $table->string('cstp_no');
            $table->integer('cstp_customer_id')->unsigned()->index();
            $table->foreign('cstp_customer_id')->references('customer_id')->on('customer_master');
            $table->integer('cstp_customer_city')->unsigned()->index();
	    $table->foreign('cstp_customer_city')->references('city_id')->on('city_db');
            $table->string('cstp_sample_name');
            $table->string('cstp_file_name');
            $table->dateTime('cstp_date');
            $table->tinyInteger('cstp_status')->nullable()->comment('1 for Active,2 for Deactive')->nullable();
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
        Schema::dropIfExists('central_stp_dtls');
    }
}
