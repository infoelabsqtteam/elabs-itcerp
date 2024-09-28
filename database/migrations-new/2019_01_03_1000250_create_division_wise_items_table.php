<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionWiseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_wise_items', function (Blueprint $table) {
            $table->increments('division_wise_item_id');
            $table->integer('item_id')->unsigned()->index();
            $table->integer('division_id')->unsigned()->index()->nullable();
	    $table->string('msl');
            $table->string('rol');
	    $table->integer('created_by')->unsigned()->index()->nullable();
            $table->timestamps();
	    $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('item_id')->references('item_id')->on('item_master');
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
        Schema::dropIfExists('division_wise_items');
    }
}
