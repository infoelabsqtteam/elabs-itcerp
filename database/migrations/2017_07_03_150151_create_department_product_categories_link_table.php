<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentProductCategoriesLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_product_categories_link', function (Blueprint $table) {
            $table->increments('department_product_categories_id');
            
            $table->integer('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('department_id')->on('departments');
            
            $table->integer('product_category_id')->unsigned()->index();
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
        Schema::dropIfExists('department_product_categories_link');
    }
}
