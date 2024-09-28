<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSalesExecutiveLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_sales_executive_logs', function (Blueprint $table) {
            $table->increments('csel_id');
            $table->integer('csel_customer_id')->unsigned()->index();
            $table->string('csel_customer_code');            
            $table->integer('csel_sale_executive_id')->unsigned()->index();
            $table->string('csel_sale_executive_code');            
            $table->integer('csel_created_by')->unsigned()->index()->nullable();
            $table->dateTime('csel_date');			
            $table->foreign('csel_customer_id')->references('customer_id')->on('customer_master');           
            $table->foreign('csel_sale_executive_id')->references('id')->on('users');
            $table->foreign('csel_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('customer_sales_executive_logs');
    }
}
