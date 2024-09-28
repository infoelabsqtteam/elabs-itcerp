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
            $table->integer('division_id')->unsigned()->index();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('is_sales_person')->unsigned()->nullable();
            $table->integer('is_sampler_person')->unsigned()->nullable();
            $table->integer('user_signature')->unsigned()->index()->nullable();
            $table->tinyInteger('status');
            $table->dateTime('activated_at');
            $table->dateTime('deactivated_at');
            $table->rememberToken();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('division_id')->references('division_id')->on('divisions');
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
