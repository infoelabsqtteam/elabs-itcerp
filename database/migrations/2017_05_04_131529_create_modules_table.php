<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->index();
            $table->string('module_name');
            $table->string('module_link');
            $table->integer('module_level')->unsigned()->index();
            $table->integer('module_status')->unsigned()->index();
            $table->integer('module_sort_by')->unsigned()->index();
			$table->integer('created_by')->unsigned()->index()->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('modules');
    }
}
