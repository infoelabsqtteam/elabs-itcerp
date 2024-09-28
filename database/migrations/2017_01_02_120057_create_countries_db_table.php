<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesDbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries_db', function (Blueprint $table) {
            $table->increments('country_id');
            $table->string('country_code')->unique;
            $table->string('country_name');
            $table->integer('country_phone_code');
            $table->tinyInteger('country_status');
            $table->tinyInteger('country_level')->default('0');
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
        Schema::dropIfExists('countries_db');
    }
}
