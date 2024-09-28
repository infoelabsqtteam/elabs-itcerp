<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrfHdrDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trf_hdr_dtls', function (Blueprint $table) {
            $table->increments('trf_hdr_dtl_id');
            $table->integer('trf_hdr_id')->unsigned()->index();
            $table->foreign('trf_hdr_id')->references('trf_id')->on('trf_hdrs')->onDelete('cascade');
            $table->integer('trf_product_test_dtl_id')->unsigned()->index()->nullable();
            $table->foreign('trf_product_test_dtl_id')->references('product_test_dtl_id')->on('product_test_dtl');
            $table->integer('trf_test_parameter_id')->unsigned()->index()->nullable();
            $table->foreign('trf_test_parameter_id')->references('test_parameter_id')->on('test_parameter');
            $table->string('trf_test_parameter_name')->nullable();
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
        Schema::dropIfExists('trf_hdr_dtls');
    }
}
