<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_navigations', function (Blueprint $table) {
	    $table->increments('id');
	    $table->integer('role_id')->unsigned()->index();
	    $table->integer('module_id')->unsigned()->index()->nullable();
	    $table->integer('module_menu_id')->unsigned()->index();
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->timestamps();            
	    $table->foreign('role_id')->references('id')->on('roles');            
	    $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
	    $table->foreign('module_menu_id')->references('id')->on('modules');
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
        Schema::dropIfExists('module_navigations');
    }
}
