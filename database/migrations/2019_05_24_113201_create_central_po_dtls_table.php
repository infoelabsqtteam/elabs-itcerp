<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCentralPoDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('central_po_dtls', function (Blueprint $table) {
            $table->increments('cpo_id');
            $table->string('cpo_no');
            $table->integer('cpo_customer_id')->unsigned()->index();
            $table->foreign('cpo_customer_id')->references('customer_id')->on('customer_master');
            $table->integer('cpo_customer_city')->unsigned()->index();
	    $table->foreign('cpo_customer_city')->references('city_id')->on('city_db');
            $table->string('cpo_sample_name');
            $table->string('cpo_file_name');
            $table->decimal('cpo_amount', 10, 2);
            $table->dateTime('cpo_date');
            $table->tinyInteger('stp_status')->nullable()->comment('1 for Active,2 for Deactive')->nullable();
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
        Schema::dropIfExists('central_po_dtls');
    }
}
