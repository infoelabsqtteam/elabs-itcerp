<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_master', function (Blueprint $table) {
	    $table->increments('item_id');
	    $table->integer('item_cat_id')->unsigned()->index();            
	    $table->string('item_code')->unique();
	    $table->string('item_name');
	    $table->integer('item_unit')->unsigned()->index();
	    $table->string('item_barcode')->unique();
	    $table->string('item_description');
	    $table->string('item_long_description')->nullable();
	    $table->string('item_technical_description')->nullable();
	    $table->string('item_specification')->nullable();
	    $table->string('is_perishable')->nullable();
	    $table->string('shelf_life_days')->nullable();
	    $table->string('item_image')->nullable();
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->timestamps();
	    $table->foreign('item_cat_id')->references('item_cat_id')->on('item_categories')->onDelete('cascade');
	    $table->foreign('item_unit')->references('unit_id')->on('units_db');
	    $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_master');
    }
}
