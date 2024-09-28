<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTestParameterAlternMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_test_parameter_altern_method', function (Blueprint $table) {
	    $table->increments('product_test_param_altern_method_id');
	    $table->integer('product_test_dtl_id')->unsigned()->index();
	    $table->foreign('product_test_dtl_id')->references('product_test_dtl_id')->on('product_test_dtl')->onDelete('cascade');
	    $table->integer('test_id')->unsigned()->index();
	    $table->foreign('test_id')->references('test_id')->on('product_test_hdr')->onDelete('cascade');
	    $table->integer('test_parameter_id')->unsigned()->index();
	    $table->foreign('test_parameter_id')->references('test_parameter_id')->on('test_parameter')->onDelete('cascade');
	    $table->integer('equipment_type_id')->unsigned()->index()->nullable();
	    $table->foreign('equipment_type_id')->references('equipment_id')->on('equipment_type');
	    $table->integer('method_id')->unsigned()->index()->nullable();;
	    $table->foreign('method_id')->references('method_id')->on('method_master');
	    $table->integer('detector_id')->unsigned()->index()->nullable();
	    $table->foreign('detector_id')->references('detector_id')->on('detector_master');	    
	    $table->string('standard_value_type')->nullable();
	    $table->text('standard_value_from')->nullable();
	    $table->text('standard_value_to')->nullable();	    
	    $table->integer('claim_dependent')->unsigned()->index();
	    $table->string('time_taken_days')->nullable();
	    $table->string('time_taken_mins')->nullable();	    
	    $table->integer('running_time_id')->unsigned()->index()->nullable();
	    $table->integer('no_of_injection')->unsigned()->index()->nullable();	    
	    $table->decimal('cost_price', 10, 2)->nullable();
	    $table->decimal('selling_price', 10, 2)->nullable();
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
        Schema::dropIfExists('product_test_parameter_altern_method');
    }
}
