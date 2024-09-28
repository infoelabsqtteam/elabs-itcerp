<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitConversionDbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_conversion_db', function (Blueprint $table) {
            $table->increments('unit_conversion_id');
			$table->string('from_unit');
			$table->string('to_unit');
			$table->string('confirm_factor');
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
        Schema::dropIfExists('unit_conversion_db');
    }
}
