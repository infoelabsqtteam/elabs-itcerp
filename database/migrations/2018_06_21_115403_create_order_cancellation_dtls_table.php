<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCancellationDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_cancellation_dtls', function (Blueprint $table) {
            $table->increments('order_cancellation_id');
            $table->integer('order_id')->unsigned()->index();
            $table->integer('cancellation_type_id')->unsigned()->index();
            $table->text('cancellation_description');
	    $table->datetime('cancelled_date');
            $table->datetime('recovered_date')->nullable();
            $table->integer('cancelled_by')->unsigned()->index();
            $table->integer('cancellation_stage')->unsigned()->index()->comment('Order Cancellation Stage');
            $table->tinyInteger('cancellation_status')->comment('1 for Cancelled,2 for recovered')->unsigned()->index();
            $table->foreign('order_id')->references('order_id')->on('order_master');
            $table->foreign('cancelled_by')->references('id')->on('users');
            $table->foreign('cancellation_type_id')->references('order_cancellation_type_id')->on('order_cancellation_types');
            $table->foreign('cancellation_stage')->references('order_status_id')->on('order_status');
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
        Schema::dropIfExists('order_cancellation_dtls');
    }
}
