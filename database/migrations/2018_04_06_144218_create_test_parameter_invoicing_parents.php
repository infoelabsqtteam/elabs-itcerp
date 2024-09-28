<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestParameterInvoicingParents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_parameter_invoicing_parents', function (Blueprint $table) {
            $table->increments('tpip_id');
            $table->integer('test_parameter_id')->unsigned()->index();
            $table->foreign('test_parameter_id')->references('test_parameter_id')->on('test_parameter');
            $table->tinyInteger('test_parameter_status');
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
        Schema::dropIfExists('test_parameter_invoicing_parents');
    }
}
