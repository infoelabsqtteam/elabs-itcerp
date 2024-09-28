<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_parameters', function (Blueprint $table) {
	    $table->increments('division_parameter_id');
	    $table->integer('division_id')->unsigned()->index();
	    $table->foreign('division_id')->references('division_id')->on('divisions')->onDelete('cascade');
	    $table->string('division_address');
	    $table->integer('division_country')->unsigned()->index();
	    $table->foreign('division_country')->references('country_id')->on('countries_db');
	    $table->integer('division_state')->unsigned()->index();
	    $table->foreign('division_state')->references('state_id')->on('state_db');
	    $table->integer('division_city')->unsigned()->index();
	    $table->foreign('division_city')->references('city_id')->on('city_db');
	    $table->string('division_PAN');
	    $table->string('division_VAT_no');
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
        Schema::dropIfExists('division_parameters');
    }
}
