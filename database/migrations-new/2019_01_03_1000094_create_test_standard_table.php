<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestStandardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_standard', function (Blueprint $table) {
	    $table->increments('test_std_id');
	    $table->string('test_std_code')->unique();
	    $table->string('test_std_name');
	    $table->string('test_std_desc');
	    $table->unique(['test_std_name','product_category_id']);
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
        Schema::dropIfExists('test_standard');
    }
}
