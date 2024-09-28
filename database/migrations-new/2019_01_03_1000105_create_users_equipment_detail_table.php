<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersEquipmentDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('users_equipment_detail', function (Blueprint $table) {
	    $table->increments('id');
	    $table->integer('user_id')->unsigned()->index();
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	    $table->integer('equipment_type_id')->unsigned()->index();
	    $table->foreign('equipment_type_id')->references('equipment_id')->on('equipment_type');
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
        Schema::dropIfExists('users_detail');
    }
}
