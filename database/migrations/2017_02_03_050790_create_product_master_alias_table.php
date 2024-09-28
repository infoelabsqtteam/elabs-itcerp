<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMasterAliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_master_alias', function (Blueprint $table) {
            
			$table->increments('c_product_id');
			$table->string('c_product_name'); 
			$table->string('c_product_status');			
			$table->integer('product_id')->unsigned()->index();
			$table->integer('created_by')->unsigned()->index()->nullable();
            $table->tinyInteger('view_type')->nullable();
			$table->timestamps();
			$table->foreign('product_id')->references('product_id')->on('product_master');
			$table->foreign('created_by')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_master_alias');
    }
}
