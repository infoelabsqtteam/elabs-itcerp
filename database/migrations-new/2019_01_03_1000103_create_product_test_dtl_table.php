<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTestDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_test_dtl', function (Blueprint $table) {
	    $table->increments('product_test_dtl_id');
	    $table->integer('test_id')->unsigned()->index();
	    $table->integer('test_parameter_id')->unsigned()->index();
	    $table->integer('parameter_sort_by')->nullable();
	    $table->integer('method_id')->unsigned()->index()->nullable();
	    $table->integer('equipment_type_id')->unsigned()->index()->nullable();
	    $table->integer('detector_id')->unsigned()->index()->nullable();
	    $table->integer('claim_dependent')->unsigned()->index();
	    $table->tinyInteger('parameter_decimal_place')->nullable();
	    $table->string('standard_value_type')->nullable();
	    $table->text('standard_value_from')->nullable();
	    $table->text('standard_value_to')->nullable();	    
	    $table->string('time_taken_days')->nullable();
	    $table->string('time_taken_mins')->nullable();
	    $table->decimal('cost_price', 10, 2)->nullable();
	    $table->decimal('selling_price', 10, 2)->nullable();
	    $table->tinyInteger('parameter_nabl_scope')->nullable();
	    $table->text('description')->nullable();
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->timestamps();
	    $table->foreign('test_id')->references('test_id')->on('product_test_hdr')->onDelete('cascade');
	    $table->foreign('test_parameter_id')->references('test_parameter_id')->on('test_parameter');
	    $table->foreign('equipment_type_id')->references('equipment_id')->on('equipment_type');
	    $table->foreign('method_id')->references('method_id')->on('method_master');
	    $table->foreign('detector_id')->references('detector_id')->on('detector_master');
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
        Schema::dropIfExists('product_test_dtl');
    }
}
