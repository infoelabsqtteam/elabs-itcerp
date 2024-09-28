<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAmendedDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_amended_dtl', function (Blueprint $table) {
            $table->increments('oad_id');
            $table->integer('oad_order_id')->unsigned()->index();
            $table->foreign('oad_order_id')->references('order_id')->on('order_master');
            $table->integer('oad_amended_stage')->unsigned()->index();
            $table->foreign('oad_amended_stage')->references('order_status_id')->on('order_status');
            $table->datetime('oad_amended_date');
            $table->integer('oad_amended_by')->unsigned()->index();
            $table->foreign('oad_amended_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_amended_dtl');
    }
}
