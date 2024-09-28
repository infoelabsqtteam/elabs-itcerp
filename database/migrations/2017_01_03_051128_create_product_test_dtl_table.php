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
	    $table->string('standard_value_type')->default('');
	    $table->string('standard_value_from')->default('NULL');
	    $table->string('standard_value_to')->default('NULL');
	    $table->integer('equipment_type_id')->unsigned()->index();
	    $table->integer('method_id')->unsigned()->index();
	    $table->integer('claim_dependent')->unsigned()->index();
	    $table->string('time_taken_days');
	    $table->string('time_taken_mins');
	    $table->decimal('cost_price', 10, 2);
	    $table->decimal('selling_price', 10, 2);	    
	    $table->string('measurement_uncertainty')->nullable();
	    $table->string('limit_determination')->nullable();
	    $table->string('lod')->nullable();
	    $table->string('mrpl')->nullable();
	    $table->string('validation_protocol')->nullable();	    
	    $table->text('description');
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->integer('parameter_sort_by');
	    $table->integer('detector_id')->unsigned()->index();
	    $table->timestamps();
	    $table->foreign('test_id')->references('test_id')->on('product_test_hdr')->onDelete('cascade');
	    $table->foreign('test_parameter_id')->references('test_parameter_id')->on('test_parameter');
	    $table->foreign('equipment_type_id')->references('equipment_id')->on('equipment_type');
	    $table->foreign('method_id')->references('method_id')->on('method_master');
	    $table->foreign('created_by')->references('id')->on('users');
	    $table->foreign('detector_id')->references('detector_id')->on('detector_master');

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
