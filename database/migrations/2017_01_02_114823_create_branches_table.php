<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
	    $table->increments('branch_id');
	    $table->string('branch_code')->unique();
	    $table->string('branch_name');
	    $table->integer('company_id')->unsigned()->index();
	    $table->foreign('company_id')->references('company_id')->on('company_master')->onDelete('cascade');
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
        Schema::dropIfExists('branches');
    }
}
