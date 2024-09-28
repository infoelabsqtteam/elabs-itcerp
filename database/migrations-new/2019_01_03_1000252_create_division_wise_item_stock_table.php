<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionWiseItemStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_wise_item_stock', function (Blueprint $table) {
            $table->increments('division_wise_item_stock_id');
            $table->integer('store_id')->unsigned()->index()->nullable();
            $table->integer('item_id')->unsigned()->index()->nullable();
            $table->integer('division_id')->unsigned()->index()->nullable();
            $table->string('openning_balance');
            $table->date('openning_balance_date');
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->timestamps();
            $table->foreign('store_id')->references('store_id')->on('division_wise_stores');
            $table->foreign('item_id')->references('item_id')->on('item_master');
            $table->foreign('division_id')->references('division_id')->on('divisions');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('division_wise_item_stock');
    }
}
