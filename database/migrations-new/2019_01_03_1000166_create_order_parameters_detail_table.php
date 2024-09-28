<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderParametersDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_parameters_detail', function (Blueprint $table) {
            $table->increments('analysis_id');
            $table->integer('order_id')->unsigned()->index();
            $table->integer('product_test_parameter')->unsigned()->index();
            $table->integer('test_param_alternative_id')->unsigned()->index()->nullable();
            $table->integer('test_parameter_id')->unsigned()->index()->nullable();
            $table->integer('equipment_type_id')->unsigned()->index()->nullable();
            $table->integer('method_id')->unsigned()->index()->nullable();
            $table->integer('detector_id')->unsigned()->index()->nullable();
            $table->tinyInteger('display_decimal_place')->nullable();
            $table->string('claim_value')->nullable();
            $table->string('claim_value_unit')->nullable();
            $table->string('standard_value_type')->nullable();
            $table->text('standard_value_from')->nullable();
            $table->text('standard_value_to')->nullable();
            $table->tinyInteger('order_parameter_nabl_scope')->nullable()->comment('0 for without Scope,1 for within Scope');
            $table->string('time_taken_days')->nullable();
            $table->string('time_taken_mins')->nullable();
            $table->dateTime('dept_due_date')->nullable();
	    $table->dateTime('report_due_date')->nullable();
            $table->integer('running_time_id')->unsigned()->index()->nullable(); 
            $table->integer('no_of_injection')->unsigned()->index()->nullable(); 
            $table->decimal('cost_price',10,2)->nullable();
            $table->decimal('selling_price',10,2)->nullable(); 
            $table->integer('test_performed_by')->unsigned()->index()->nullable();
            $table->text('test_result')->nullable();     
            $table->timestamps();
            $table->foreign('test_parameter_id')->references('test_parameter_id')->on('test_parameter');
            $table->foreign('order_id')->references('order_id')->on('order_master')->onDelete('cascade');
            $table->foreign('product_test_parameter')->references('product_test_dtl_id')->on('product_test_dtl');
            $table->foreign('equipment_type_id')->references('equipment_id')->on('equipment_type');
            $table->foreign('method_id')->references('method_id')->on('method_master');            
            $table->foreign('test_performed_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_parameters_detail');
    }
}
