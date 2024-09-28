<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rol_setting', function (Blueprint $table) {
            $table->increments('rol_setting_id');
			$table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('item_id')->on('item_master');
			$table->integer('branch_id')->unsigned()->index();
            $table->foreign('branch_id')->references('branch_id')->on('branches');
			$table->date('date');
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
        Schema::dropIfExists('rol_setting');
    }
}
