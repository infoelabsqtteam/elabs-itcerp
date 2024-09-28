<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestParameterCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::create('test_parameter_categories', function (Blueprint $table) {
	    $table->increments('test_para_cat_id');
	    $table->string('test_para_cat_code')->unique();
	    $table->string('test_para_cat_name')->unique(['test_para_cat_name','product_category_id']);
	    $table->string('test_para_cat_print_desc');
	    $table->string('parent_id')->nullable();
	    $table->integer('level')->nullable();
	    $table->integer('category_sort_by')->unsigned()->index()->nullable();
	    $table->integer('product_category_id')->unsigned()->index();
	    $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');	    
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
        Schema::dropIfExists('test_parameter_categories');
    }
}
