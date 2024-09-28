<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportTemplateDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_dtl', function (Blueprint $table) {
	    $table->increments('template_id');
	    $table->integer('template_type_id')->unsigned()->index();
            $table->integer('division_id')->unsigned()->index();
	    $table->integer('product_category_id')->nullable();
	    $table->text('header_content');
            $table->text('footer_content')->nullable();
	    $table->tinyInteger('template_status_id');
	    $table->foreign('template_type_id')->references('template_type_id')->on('template_types');
            $table->foreign('division_id')->references('division_id')->on('divisions');
            $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');
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
        Schema::dropIfExists('template_dtl');
    }
}
