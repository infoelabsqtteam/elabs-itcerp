<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
	    $table->increments('setting_id');
	    $table->integer('setting_group_id')->unsigned()->index();
	    $table->foreign('setting_group_id')->references('setting_group_id')->on('setting_groups');
	    $table->string('setting_key');          
	    $table->string('setting_value');
	    $table->tinyInteger('setting_status')->nullable();
	    $table->tinyInteger('is_display')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
