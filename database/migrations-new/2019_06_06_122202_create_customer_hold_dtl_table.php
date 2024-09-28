<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerHoldDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_hold_dtl', function (Blueprint $table) {
            $table->increments('chd_id');
            $table->integer('chd_customer_id')->unsigned()->index();
	    $table->foreign('chd_customer_id')->references('customer_id')->on('customer_master')->onDelete('cascade');
            $table->text('chd_hold_description');
            $table->dateTime('chd_hold_date');
            $table->string('chd_hold_by');
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
        Schema::dropIfExists('customer_hold_dtl');
    }
}
