<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_type', function (Blueprint $table) {
	    $table->increments('equipment_id');
	    $table->string('equipment_code')->unique();
	    $table->string('equipment_name')->unique();
	    $table->integer('equipment_capacity');
	    $table->text('equipment_description');
	    $table->integer('product_category_id')->unsigned()->index()->nullable();
	    $table->tinyInteger('is_equipment_selected')->nullable();
	    $table->tinyInteger('equipment_sort_by')->nullable();
	    $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');
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
        Schema::dropIfExists('equipment_master');
    }
}
