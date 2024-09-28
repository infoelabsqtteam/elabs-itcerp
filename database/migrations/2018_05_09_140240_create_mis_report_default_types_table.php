<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMisReportDefaultTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mis_report_default_types', function (Blueprint $table) {
			$table->increments('mis_report_id');
            $table->string('mis_report_code');
			$table->string('mis_report_name');
			$table->tinyInteger('mis_report_status');
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
        Schema::dropIfExists('mis_report_default_types');
    }
}
