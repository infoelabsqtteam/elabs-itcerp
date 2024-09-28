<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStateDbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_db', function (Blueprint $table) {
            $table->increments('state_id');
            $table->string('state_code')->unique();
            $table->string('state_name');
				$table->integer('country_id')->unsigned()->index();
				$table->tinyInteger('state_level')->default('1');
				$table->tinyInteger('state_status')->default('1');
            $table->foreign('country_id')->references('country_id')->on('countries_db')->onDelete('cascade');
			
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
        Schema::dropIfExists('state_db');
    }
}
