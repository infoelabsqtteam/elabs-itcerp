<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProcessLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_process_log', function (Blueprint $table) {
            $table->increments('opl_id');
			$table->integer('opl_order_id')->unsigned()->index();           
			$table->integer('opl_order_status_id')->unsigned()->index();
			$table->dateTime('opl_date');            
            $table->integer('opl_user_id')->unsigned()->index();
			$table->tinyInteger('opl_current_stage')->nullable();
            $table->tinyInteger('opl_amend_status')->unsigned()->index();
			$table->tinyInteger('opl_amend_by')->unsigned()->index();
            $table->text('note')->nullable();
            $table->string('error_parameter_ids')->nullable();
			$table->timestamps();
			$table->foreign('opl_order_id')->references('order_id')->on('order_master'); 
			$table->foreign('opl_order_status_id')->references('order_status_id')->on('order_status'); 
			$table->foreign('opl_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_process_log');
    }
}
