<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSamplePriorityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_sample_priority', function (Blueprint $table) {
            $table->increments('sample_priority_id');
            $table->string('sample_priority_code')->unique;
            $table->string('sample_priority_name');
            $table->tinyInteger('sample_priority_status')->default('1');
            $table->string('order_sample_priority')->nullable();
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
        Schema::dropIfExists('order_sample_priority');
    }
}
