<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInvoicingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_invoicing_rates', function (Blueprint $table) {            
	    $table->increments('cir_id');
	    $table->integer('invoicing_type_id')->unsigned()->index();
	    $table->foreign('invoicing_type_id')->references('invoicing_type_id')->on('customer_invoicing_types');
	    $table->integer('cir_division_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_division_id')->references('division_id')->on('divisions');
	    $table->integer('cir_customer_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_customer_id')->references('customer_id')->on('customer_master');
	    $table->integer('cir_country_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_country_id')->references('country_id')->on('countries_db');
	    $table->integer('cir_state_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_state_id')->references('state_id')->on('state_db');
	    $table->integer('cir_city_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_city_id')->references('city_id')->on('city_db');
	    $table->integer('cir_c_product_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_c_product_id')->references('c_product_id')->on('product_master_alias');
	    $table->integer('cir_product_category_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_product_category_id')->references('p_category_id')->on('product_categories');
	    $table->integer('cir_p_category_id')->unsigned()->index()->nullable();
	    $table->integer('cir_sub_p_category_id')->unsigned()->index()->nullable();
	    $table->integer('cir_test_parameter_category_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_test_parameter_category_id')->references('test_para_cat_id')->on('test_parameter_categories');
	    $table->integer('cir_parameter_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_parameter_id')->references('test_parameter_id')->on('test_parameter');
	    $table->integer('cir_equipment_type_id')->unsigned()->index()->nullable();
	    $table->integer('cir_test_standard_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_test_standard_id')->references('test_std_id')->on('test_standard');
	    $table->integer('cir_equipment_count')->unsigned()->index()->nullable();
	    $table->tinyInteger('cir_is_detector')->nullable();
	    $table->integer('cir_detector_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_detector_id')->references('detector_id')->on('detector_master');
	    $table->integer('cir_running_time_id')->unsigned()->index()->nullable();
	    $table->foreign('cir_running_time_id')->references('invoicing_running_time_id')->on('customer_invoicing_running_time');
	    $table->integer('cir_no_of_injection')->unsigned()->index()->nullable();
	    $table->decimal('invoicing_rate', 10, 2)->nullable();	    
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
        Schema::dropIfExists('customer_invoicing_rates');
    }
}
