<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDepartmentDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('users_department_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();            
	    $table->integer('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('department_id')->on('departments');
            $table->timestamps();
	    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_detail');
    }
}
