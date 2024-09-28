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
            $table->engine = 'InnoDB';
            $table->increments('analysis_id');
            $table->integer('order_id')->unsigned()->index();
            $table->integer('product_test_parameter')->unsigned()->index();
            $table->integer('test_param_alternative_id')->unsigned()->index();
            $table->integer('test_parameter_id')->unsigned()->index()->nullable();
            $table->integer('equipment_type_id')->unsigned()->index();
            $table->integer('method_id')->unsigned()->index();
            $table->string('claim_value')->nullable();
            $table->string('claim_value_unit')->nullable();
            $table->string('standard_value_type');
            $table->string('standard_value_from');
            $table->string('standard_value_to');
            $table->string('time_taken_days');
            $table->string('time_taken_mins');
            $table->string('measurement_uncertainty')->nullable();
            $table->string('limit_determination')->nullable();
            $table->string('lod')->nullable();
            $table->string('mrpl')->nullable();
            $table->string('validation_protocol')->nullable();
            $table->dateTime('dept_due_date')->nullable();
            $table->dateTime('report_due_date')->nullable();
            $table->integer('oaws_ui_setting_id')->unsigned()->index()->nullable();
            $table->integer('detector_id')->unsigned()->index()->nullable();
            $table->integer('column_id')->unsigned()->index()->nullable();
            $table->integer('instance_id')->unsigned()->index()->nullable();
            $table->integer('running_time_id')->unsigned()->index()->nullable();
            $table->integer('no_of_injection')->unsigned()->index()->nullable();
            $table->decimal('cost_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('variation_price', 10, 2);
            $table->integer('test_performed_by')->unsigned()->index()->nullable();
            $table->text('test_result')->nullable();
            $table->foreign('detector_id')->references('detector_id')->on('detector_master');
            $table->foreign('column_id')->references('column_id')->on('column_master');
            $table->foreign('instance_id')->references('instance_id')->on('instance_master');
            $table->foreign('running_time_id')->references('invoicing_running_time_id')->on('customer_invoicing_running_time');
            $table->foreign('test_parameter_id')->references('test_parameter_id')->on('test_parameter');
            $table->foreign('order_id')->references('order_id')->on('order_master')->onDelete('cascade');
            $table->foreign('product_test_parameter')->references('product_test_dtl_id')->on('product_test_dtl');
            $table->foreign('equipment_type_id')->references('equipment_id')->on('equipment_type');
            $table->foreign('method_id')->references('method_id')->on('method_master');
            $table->foreign('test_performed_by')->references('id')->on('users');
            $table->foreign('oaws_ui_setting_id')->references('oaws_id')->on('order_analyst_window_settings');
            $table->dateTime('analysis_start_date')->comment('Date of start of Analysis')->nullable();
            $table->dateTime('analysis_completion_date')->comment('Date of completion of Analysis')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
