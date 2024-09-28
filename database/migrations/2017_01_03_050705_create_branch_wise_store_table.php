<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchWiseStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_wise_store', function (Blueprint $table) {
            $table->increments('store_id');
            $table->string('store_code')->unique();
            $table->string('store_name');
			$table->integer('branch_id')->unsigned()->index();
            $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
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
        Schema::dropIfExists('branch_wise_store');
    }
}
