<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_parameter', function (Blueprint $table) {
	    $table->increments('test_parameter_id');
	    $table->string('test_parameter_code')->unique();
	    $table->string('test_parameter_name');
	    $table->text('test_parameter_print_desc');
	    $table->integer('test_parameter_category_id')->unsigned()->index();
	    $table->foreign('test_parameter_category_id')->references('test_para_cat_id')->on('test_parameter_categories')->onDelete('cascade');
	    $table->integer('test_parameter_invoicing')->nullable();
	    $table->integer('test_parameter_invoicing_parent_id')->nullable();
	    $table->tinyInteger('test_parameter_nabl_scope')->nullable();
	    $table->tinyInteger('test_parameter_decimal_place')->nullable();
	    $table->decimal('cost_price', 10, 2)->nullable();
	    $table->decimal('selling_price', 10, 2)->nullable();
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
        Schema::dropIfExists('test_parameter');
    }
}
