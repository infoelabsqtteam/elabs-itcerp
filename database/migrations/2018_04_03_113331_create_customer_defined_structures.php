<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerDefinedStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_defined_structures', function (Blueprint $table) {
	    $table->increments('cdit_id');
	    $table->integer('customer_id')->unsigned()->index();
	    $table->integer('division_id')->unsigned()->index();
	    $table->integer('product_category_id')->unsigned()->index();
	    $table->integer('invoicing_type_id')->unsigned()->index();
	    $table->integer('billing_type_id')->unsigned()->index();
	    $table->integer('discount_type_id')->unsigned()->index();
	    $table->string('discount_value');
	    $table->tinyInteger('tat_editable')->nullable;
	    $table->tinyInteger('customer_invoicing_type_status');
	    $table->foreign('customer_id')->references('customer_id')->on('customer_master');
	    $table->foreign('division_id')->references('division_id')->on('divisions');
	    $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');
	    $table->foreign('invoicing_type_id')->references('invoicing_type_id')->on('customer_invoicing_types');
	    $table->foreign('billing_type_id')->references('billing_type_id')->on('customer_billing_types');
	    $table->foreign('discount_type_id')->references('discount_type_id')->on('customer_discount_types');
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
        Schema::dropIfExists('customer_defined_structures');
    }
}
