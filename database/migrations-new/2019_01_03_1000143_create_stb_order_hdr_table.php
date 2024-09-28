<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStbOrderHdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stb_order_hdr', function (Blueprint $table) {                      
            $table->increments('stb_order_hdr_id');
	    $table->string('stb_prototype_no');
            $table->integer('stb_status')->tinyInteger;            
            $table->integer('stb_division_id')->unsigned()->index();
            $table->foreign('stb_division_id')->references('division_id')->on('divisions');            
            $table->integer('stb_product_category_id')->unsigned()->index();
            $table->foreign('stb_product_category_id')->references('p_category_id')->on('product_categories');            
            $table->integer('stb_sample_id')->unsigned()->index()->nullable();
            $table->foreign('stb_sample_id')->references('sample_id')->on('samples');            
            $table->integer('stb_customer_id')->unsigned()->index();
            $table->foreign('stb_customer_id')->references('customer_id')->on('customer_master');            
            $table->integer('stb_sale_executive')->unsigned()->index();
            $table->foreign('stb_sale_executive')->references('id')->on('users');            
            $table->text('stb_mfg_lic_no');
	    $table->integer('stb_customer_city')->unsigned()->index();
            $table->foreign('stb_customer_city')->references('city_id')->on('city_db');            
            $table->integer('stb_discount_type_id')->unsigned()->index();
            $table->foreign('stb_discount_type_id')->references('discount_type_id')->on('customer_discount_types');            
	    $table->string('stb_discount_value')->nullable();            
            $table->integer('stb_invoicing_type_id')->unsigned()->index();
            $table->foreign('stb_invoicing_type_id')->references('invoicing_type_id')->on('customer_invoicing_types');            
            $table->integer('stb_billing_type_id')->unsigned()->index();
            $table->foreign('stb_billing_type_id')->references('billing_type_id')->on('customer_billing_types');
            $table->dateTime('stb_prototype_date');
            $table->integer('stb_product_id')->unsigned()->index();
            $table->integer('stb_sample_description_id')->unsigned()->index();
            $table->foreign('stb_sample_description_id')->references('c_product_id')->on('product_master_alias');            
            $table->string('stb_batch_no');
            $table->string('stb_letter_no')->nullable();
	    $table->string('stb_reference_no')->nullable();
            $table->string('stb_brand_type');      
	    $table->string('stb_batch_size')->nullable();
	    $table->string('stb_sample_qty')->nullable();
	    $table->string('stb_sample_qty_unit')->nullable();
            $table->integer('stb_sample_priority_id')->unsigned()->index();
            $table->foreign('stb_sample_priority_id')->references('sample_priority_id')->on('order_sample_priority');            
            $table->tinyInteger('stb_is_sealed')->unsigned()->index()->comment('0 for unsealed,1 for sealed,2 for Intact,3 for N/A');
	    $table->tinyInteger('stb_is_signed')->unsigned()->index()->comment('0 for unsigned,1 for signed,2 for N/A');            
            $table->string('stb_packing_mode')->nullable();
            $table->string('stb_quotation_no')->nullable();
	    $table->tinyInteger('stb_submission_type')->unsigned()->index()->nullable();
	    $table->integer('stb_actual_submission_type')->nullable();
            $table->text('stb_advance_details')->nullable();
            $table->string('stb_pi_reference')->nullable();
            $table->string('stb_manufactured_by')->nullable();
            $table->string('stb_supplied_by')->nullable();
            $table->string('stb_mfg_date')->nullable();
            $table->string('stb_expiry_date')->nullable();	    
            $table->text('stb_remarks')->nullable();
            $table->dateTime('stb_sampling_date')->nullable();
            $table->decimal('stb_extra_amount', 10, 2)->nullable();
	    $table->string('stb_product_description')->nullable();
	    $table->string('stb_sample_pack')->nullable();
	    $table->string('stb_storage_cond_sample_pack')->nullable();
	    $table->string('stb_sample_pack_code')->nullable();
	    $table->string('stb_orientation')->nullable();
	    $table->integer('stb_created_by')->unsigned()->index()->nullable();
            $table->foreign('stb_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('stb_order_hdr');
    }
}
