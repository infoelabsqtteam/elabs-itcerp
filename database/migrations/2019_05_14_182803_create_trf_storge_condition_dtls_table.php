<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrfStorgeConditionDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trf_storge_condition_dtls', function (Blueprint $table) {
            $table->increments('trf_sc_id');
            $table->string('trf_sc_name');
            $table->tinyInteger('trf_sc_status')->nullable();
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
        Schema::dropIfExists('trf_storge_condition_dtls');
    }
}
