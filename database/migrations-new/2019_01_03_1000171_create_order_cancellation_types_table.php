<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCancellationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_cancellation_types', function (Blueprint $table) {
            $table->increments('order_cancellation_type_id')->index();
            $table->string('order_cancellation_type_name')->index();
            $table->tinyInteger('order_cancellation_type_status')->index();
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
        Schema::dropIfExists('order_cancellation_types');
    }
}
