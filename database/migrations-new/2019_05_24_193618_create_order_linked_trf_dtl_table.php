<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLinkedTrfDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_linked_trf_dtl', function (Blueprint $table) {
            $table->increments('oltd_id');
            $table->integer('oltd_order_id')->unsigned()->index()->unique();
            $table->foreign('oltd_order_id')->references('order_id')->on('order_master');
            $table->string('oltd_trf_no');
            $table->string('oltd_file_name');
            $table->datetime('oltd_date');
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
        Schema::dropIfExists('order_linked_trf_dtl');
    }
}
