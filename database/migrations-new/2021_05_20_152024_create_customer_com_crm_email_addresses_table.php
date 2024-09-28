<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerComCrmEmailAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_com_crm_email_addresses', function (Blueprint $table) {
            $table->increments('cccea_id');
            $table->integer('cccea_customer_id')->unsigned()->index();
            $table->foreign('cccea_customer_id')->references('customer_id')->on('customer_master');
	        $table->integer('cccea_division_id')->unsigned()->index();
            $table->foreign('cccea_division_id')->references('division_id')->on('divisions');
	        $table->integer('cccea_product_category_id')->unsigned()->index();
            $table->foreign('cccea_product_category_id')->references('p_category_id')->on('product_categories');
			$table->string('cccea_name');
            $table->string('cccea_email');
			$table->tinyInteger('cccea_status')->nullable()->comment('1 for active,2 for Inactive');
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
        Schema::dropIfExists('customer_com_crm_email_addresses');
    }
}
