<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityDbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_db', function (Blueprint $table) {
            $table->increments('city_id');
            $table->string('city_code')->unique();
            $table->string('city_name');
			$table->integer('state_id')->unsigned()->index();
            $table->foreign('state_id')->references('state_id')->on('state_db')->onDelete('cascade');
			$table->integer('created_by')->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');						
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
        Schema::dropIfExists('city_db');
    }
}
