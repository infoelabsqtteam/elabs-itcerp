<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportMiddleAuthorizedSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_report_middle_authorized_signs', function (Blueprint $table) {
            $table->increments('ormad_id');
            $table->Integer('ormad_order_id')->unsigned()->index();
            $table->integer('ormad_employee_id')->unsigned()->index();
            $table->string('ormad_employee_sign');
            $table->foreign('ormad_order_id')->references('order_id')->on('order_master');
            $table->foreign('ormad_employee_id')->references('id')->on('users');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_report_middle_authorized_signs');
    }
}
