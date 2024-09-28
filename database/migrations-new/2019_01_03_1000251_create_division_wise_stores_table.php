<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionWiseStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_wise_stores', function (Blueprint $table) {
            $table->increments('store_id');            
            $table->string('store_code');
            $table->string('store_name');
            $table->integer('division_id')->unsigned()->index()->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('division_id')->references('division_id')->on('divisions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('division_wise_stores');
    }
}
