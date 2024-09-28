<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStabilityNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_stability_notes', function (Blueprint $table) {
			$table->increments('stability_id');
			$table->string('stability_name');			
			$table->tinyInteger('stability_status')->nullable();
			$table->integer('created_by');
			$table->timestamps();
			$table->foreign('created_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_stability_notes');
    }
}
