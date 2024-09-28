<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderHeaderNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_header_notes', function (Blueprint $table) {
	    $table->increments('header_id');			
	    $table->string('header_name')->unsigned()->index();
	    $table->string('header_limit')->unsigned()->index()->comment('For DT parameter limit')->nullable();
	    $table->integer('header_status')->nullable(); 
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->foreign('created_by')->references('id')->on('users');
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
       Schema::dropIfExists('order_header_notes');
    }
}
