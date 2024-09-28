<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_master', function (Blueprint $table) {
	    $table->increments('customer_id');
	    $table->string('customer_code')->unique();
	    $table->string('customer_name');
	    $table->string('customer_email');
	    $table->string('customer_mobile')->nullable();
	    $table->string('customer_phone')->nullable();
	    $table->string('customer_address');
	    $table->integer('customer_country')->unsigned()->index();
	    $table->foreign('customer_country')->references('country_id')->on('countries_db');
	    $table->integer('customer_state')->unsigned()->index();
	    $table->foreign('customer_state')->references('state_id')->on('state_db');
	    $table->integer('customer_city')->unsigned()->index();
	    $table->foreign('customer_city')->references('city_id')->on('city_db');
	    $table->integer('customer_pincode');
	    $table->integer('customer_type');
	    $table->integer('billing_type')->unsigned()->index()->nullable();
	    $table->foreign('billing_type')->references('billing_type_id')->on('customer_billing_types');
	    $table->integer('invoicing_type_id')->unsigned()->index()->nullable();
	    $table->foreign('invoicing_type_id')->references('invoicing_type_id')->on('customer_invoicing_types');
	    $table->integer('sale_executive')->unsigned()->index();
	    $table->foreign('sale_executive')->references('id')->on('users');			
	    $table->integer('discount_type');   
	    $table->string('discount_value')->nullable();
	    $table->string('customer_vat_cst')->nullable();
	    $table->string('mfg_lic_no')->nullable();		
	    $table->integer('ownership_type')->unsigned()->index()->nullable();		
	    $table->integer('company_type')->unsigned()->index()->nullable();			
	    $table->string('owner_name')->nullable();
	    $table->string('customer_pan_no')->nullable();
	    $table->string('customer_tan_no')->nullable();
	    $table->integer('customer_gst_category_id')->unsigned()->index();
	    $table->integer('customer_gst_type_id')->unsigned()->index();
	    $table->integer('customer_gst_tax_slab_type_id')->unsigned()->index();    
	    $table->string('customer_gst_no')->nullable();	    
	    $table->integer('customer_priority_id')->nullable();
	    $table->foreign('customer_priority_id')->references('sample_priority_id')->on('order_sample_priority');
	    $table->string('bank_account_no')->nullable();
	    $table->string('bank_account_name')->nullable();
	    $table->string('bank_name')->nullable();
	    $table->string('bank_branch_name')->nullable();
	    $table->string('bank_rtgs_ifsc_code')->nullable();
	    $table->tinyInteger('customer_status')->default(1)->nullable()->comment('0 for Pending,1 for active,2 for inactive,3 for hold');
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
        Schema::dropIfExists('customer_master');
    }
}
