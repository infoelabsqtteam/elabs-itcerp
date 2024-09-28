<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceSessionDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_session_dtl', function (Blueprint $table) {
            $table->increments('invoice_session_id');
            $table->integer('division_id')->unsigned()->index();
            $table->foreign('division_id')->references('division_id')->on('divisions');
	    $table->integer('product_category_id')->unsigned()->index();
            $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');
            $table->tinyInteger('invoice_session_year')->nullable();
            $table->tinyInteger('invoice_session_status')->default('1')->nullable();
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
        Schema::dropIfExists('invoice_session_dtl');
    }
}
