<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_report_settings', function (Blueprint $table) {
            $table->increments('ors_id');
            $table->integer('ors_type_id')->unsigned()->index()->comment('1 for Customer Defined Fields');
            $table->integer('ors_division_id')->unsigned()->index();
            $table->integer('ors_product_category_id')->unsigned()->index();
            $table->string('ors_column_name');
            $table->integer('ors_created_by')->unsigned()->index()->nullable();
            $table->foreign('ors_created_by')->references('id')->on('users');
            $table->foreign('ors_division_id')->references('division_id')->on('divisions');
            $table->foreign('ors_product_category_id')->references('p_category_id')->on('product_categories');
            $table->unique(['ors_division_id','ors_product_category_id','ors_column_name'], 'ors_division_product_category_column_name_unique');
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
        Schema::dropIfExists('order_report_settings');
    }
}
