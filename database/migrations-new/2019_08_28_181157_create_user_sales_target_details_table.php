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
            Schema::create('user_sales_target_details', function (Blueprint $table) {
            $table->increments('ust_id');
            $table->integer('ust_user_id')->unsigned()->index();
            $table->integer('ust_division_id')->unsigned()->index();
            $table->integer('ust_product_category_id')->unsigned()->index();
            $table->integer('ust_customer_id')->unsigned()->index();            
            $table->string('ust_amount');
            $table->date('ust_date');
            $table->tinyInteger('ust_status')->nullable()->comment('1 for Active,2 for Deactive')->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');            
            $table->foreign('ust_user_id')->references('id')->on('users'); 
            $table->foreign('ust_division_id')->references('division_id')->on('divisions'); 
            $table->foreign('ust_product_category_id')->references('p_category_id')->on('product_categories'); 
            $table->foreign('ust_customer_id')->references('customer_id')->on('customer_master'); 
            $table->timestamps();
        });
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
