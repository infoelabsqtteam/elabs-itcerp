<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSalesTargetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sales_target_details', function (Blueprint $table) {
            $table->increments('ust_id');
            $table->integer('ust_user_id')->unsigned()->index();
            $table->integer('ust_division_id')->unsigned()->index();
            $table->integer('ust_product_category_id')->unsigned()->index();
            $table->integer('ust_customer_id')->unsigned()->index();
            $table->integer('ust_type_id')->unsigned()->index();
            $table->string('ust_month');
            $table->string('ust_year');
            $table->date('ust_date');
            $table->string('ust_amount');
            $table->tinyInteger('ust_status')->nullable()->comment('1 for Active,2 for Deactive')->nullable();
            $table->integer('ust_fin_year_id')->unsigned()->index();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');            
            $table->foreign('ust_user_id')->references('id')->on('users'); 
            $table->foreign('ust_division_id')->references('division_id')->on('divisions'); 
            $table->foreign('ust_product_category_id')->references('p_category_id')->on('product_categories'); 
            $table->foreign('ust_customer_id')->references('customer_id')->on('customer_master');
            $table->foreign('ust_type_id')->references('usty_id')->on('user_sales_target_types');
            $table->foreign('ust_fin_year_id')->references('ify_id')->on('invoice_financial_years');
            $table->unique(['ust_user_id', 'ust_division_id', 'ust_product_category_id', 'ust_customer_id', 'ust_type_id', 'ust_month', 'ust_year','ust_date']);
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
        Schema::dropIfExists('user_sales_target_details');
    }
}
