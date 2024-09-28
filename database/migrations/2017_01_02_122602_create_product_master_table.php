<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_master', function (Blueprint $table){
            $table->increments('product_id');
			$table->integer('p_category_id')->unsigned()->index();
            $table->foreign('p_category_id')->references('p_category_id')->on('product_categories')->onDelete('cascade');
			$table->string('product_code')->unique();
			$table->string('product_barcode');
			$table->string('product_name');
			$table->text('product_description');
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
        Schema::dropIfExists('product_master');
    }
}
