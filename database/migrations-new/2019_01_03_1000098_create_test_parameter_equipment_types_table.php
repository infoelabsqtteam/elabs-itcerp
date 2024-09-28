<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestparameterEquipmentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_parameter_equipment_types', function (Blueprint $table) {
	    $table->increments('id');
	    $table->integer('test_parameter_id')->unsigned()->index();
	    $table->foreign('test_parameter_id')->references('test_parameter_id')->on('test_parameter')->onDelete('cascade');
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
        Schema::dropIfExists('equipment_master');
    }
}
