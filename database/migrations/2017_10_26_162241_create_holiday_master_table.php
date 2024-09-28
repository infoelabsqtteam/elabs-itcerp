<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidayMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('holiday_master', function (Blueprint $table) {
            $table->increments('holiday_id');
			$table->string('holiday_name')->nullable();
			$table->date('holiday_date');
			$table->tinyInteger('holiday_status')->nullable();	            
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
        Schema::dropIfExists('holiday_master');
    }
}
