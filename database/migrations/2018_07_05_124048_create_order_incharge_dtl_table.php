<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInchargeDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_incharge_dtl', function (Blueprint $table) {
            $table->increments('oid_id');
            $table->integer('order_id')->unsigned()->index();
            $table->integer('oid_employee_id')->unsigned()->index();
            $table->integer('oid_equipment_type_id')->unsigned()->index();
            $table->datetime('oid_assign_date');
            $table->datetime('oid_confirm_date')->nullable();            
            $table->integer('oid_confirm_by')->nullable();
            $table->tinyInteger('oid_status')->nullable();
            $table->foreign('order_id')->references('order_id')->on('order_master');
            $table->foreign('oid_confirm_by')->references('id')->on('users');
            $table->foreign('oid_employee_id')->references('id')->on('users');
            $table->foreign('oid_equipment_type_id')->references('equipment_id')->on('equipment_type');  
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
        Schema::dropIfExists('order_incharge_dtl');
    }
}
