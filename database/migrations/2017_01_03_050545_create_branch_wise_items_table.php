<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchWiseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_wise_items', function (Blueprint $table) {
            $table->increments('item_id');
			$table->integer('branch_id')->unsigned()->index();
            $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
			$table->string('MSL');
			$table->string('ROL');
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
        Schema::dropIfExists('branch_wise_items');
    }
}
