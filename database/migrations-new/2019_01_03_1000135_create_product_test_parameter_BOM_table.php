<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTestParameterBOMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_test_parameter_BOM', function (Blueprint $table) {
	    $table->increments('test_BOM_id');
	    $table->integer('test_id')->unsigned()->index();
	    $table->foreign('test_id')->references('test_id')->on('product_test_hdr')->onDelete('cascade');
	    $table->integer('product_test_dtl_id')->unsigned()->index();
	    $table->foreign('product_test_dtl_id')->references('product_test_dtl_id')->on('product_test_dtl')->onDelete('cascade');
	    $table->integer('item_id')->unsigned()->index();
	    $table->foreign('item_id')->references('item_id')->on('item_master')->onDelete('cascade');
	    $table->string('consumed_qty');
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
        Schema::dropIfExists('product_test_parameter_BOM');
    }
}
