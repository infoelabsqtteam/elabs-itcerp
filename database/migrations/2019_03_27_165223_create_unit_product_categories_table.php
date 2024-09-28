<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_product_categories', function (Blueprint $table) {
            $table->increments('p_category_id');
	    $table->string('p_category_code')->unique();
	    $table->string('p_category_name');
	    $table->integer('p_parent_id')->unsigned()->index();
	    $table->integer('p_company_id')->unsigned()->index();
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->foreign('created_by')->references('id')->on('users');
	    $table->timestamps();
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
        Schema::dropIfExists('unit_product_categories');
    }
}
