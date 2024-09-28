<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_master', function (Blueprint $table) {
	    $table->increments('company_id');
	    $table->string('company_code')->unique();
	    $table->string('company_name');
	    $table->string('company_address');
	    $table->integer('company_city')->unsigned()->index();
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->timestamps();
	    $table->foreign('company_city')->references('city_id')->on('city_db');
	    //$table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_master');
    }
}
