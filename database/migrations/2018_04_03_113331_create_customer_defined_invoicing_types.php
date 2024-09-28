<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerDefinedInvoicingTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_defined_invoicing_type', function (Blueprint $table) {
        $table->increments('cdit_id');
			$table->integer('customer_id')->unsigned()->index();
			$table->foreign('customer_master')->references('customer_id')->on('customer_id');
            $table->integer('product_cayegory_id')->unsigned()->index();
			$table->foreign('product_categories')->references('p_category_id')->on('product_cayegory_id');
            $table->integer('invoicing_type_id')->unsigned()->index();
			$table->foreign('customer_invoicing_types')->references('invoicing_type_id')->on('invoicing_type_id');
			
			$table->integer('billing_type_id')->unsigned()->index();
			$table->foreign('customer_billing_types')->references('billing_type_id')->on('billing_type_id');
			
			$table->integer('discount_type_id')->unsigned()->index();
			$table->foreign('customer_discount_types')->references('discount_type_id')->on('discount_type_id');
			
			$table->string('discount_value');
            $table->tinyInteger('customer_invoicing_type_status');
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
        Schema::dropIfExists('customer_defined_invoicing_type');
    }
}
