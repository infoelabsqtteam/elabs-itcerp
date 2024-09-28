<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomDefinedFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('custom_defined_fields', function (Blueprint $table) {
			$table->increments('label_id');
			$table->string('label_name');
			$table->string('label_value');
			$table->tinyInteger('label_status');
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
        Schema::dropIfExists('custom_defined_fields');

    }
}
