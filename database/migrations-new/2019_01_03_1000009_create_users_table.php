<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
	    $table->increments('id');
	    $table->string('user_code')->unique();
	    $table->integer('division_id')->unsigned()->index()->nullable();
	    $table->foreign('division_id')->references('division_id')->on('divisions');
	    $table->integer('role_id')->unsigned()->index()->nullable();
	    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
	    $table->string('name');
	    $table->string('email')->unique();
	    $table->string('password');
	    $table->integer('is_sales_person')->unsigned()->nullable();
	    $table->string('user_signature')->nullable();
	    $table->tinyInteger('status')->nullable();
	    $table->dateTime('activated_at')->nullable();
	    $table->dateTime('deactivated_at')->nullable();
	    $table->rememberToken()->nullable();
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
        Schema::drop('users');
    }
}
