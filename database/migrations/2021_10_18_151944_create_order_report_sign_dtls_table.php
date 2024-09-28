<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportSignDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_report_sign_dtls', function (Blueprint $table) {
            $table->increments('orsd_id');
            $table->integer('orsd_employee_id')->unsigned()->index();
            $table->integer('orsd_division_id')->unsigned()->index();
            $table->integer('orsd_product_category_id')->unsigned()->index();
            $table->integer('orsd_equipment_type_id')->unsigned()->index();
            $table->integer('orsd_created_by')->unsigned()->index();
            $table->foreign('orsd_employee_id')->references('id')->on('users');
            $table->foreign('orsd_division_id')->references('division_id')->on('divisions');
            $table->foreign('orsd_product_category_id')->references('p_category_id')->on('product_categories');
            $table->foreign('orsd_equipment_type_id')->references('equipment_id')->on('equipment_type');
            $table->foreign('orsd_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_report_sign_dtls');
    }
}
