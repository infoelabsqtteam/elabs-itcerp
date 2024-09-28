<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTestHdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_test_hdr', function (Blueprint $table) {
            $table->increments('test_id');
            $table->string('test_code')->unique();
			$table->integer('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('product_id')->on('product_master')->onDelete('cascade');
			$table->integer('test_standard_id')->unsigned()->index();
            $table->foreign('test_standard_id')->references('test_std_id')->on('test_standard');
            $table->string('wef');
            $table->string('upto');
            $table->string('status');
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
        Schema::dropIfExists('product_test_hdr');
    }
}
