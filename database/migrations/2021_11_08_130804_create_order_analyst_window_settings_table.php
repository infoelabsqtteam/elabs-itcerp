<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAnalystWindowSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_analyst_window_settings', function (Blueprint $table) {
            $table->increments('oaws_id');
            $table->integer('oaws_unique_id')->unsigned()->index();
            $table->integer('oaws_division_id')->unsigned()->index();
            $table->integer('oaws_product_category_id')->unsigned()->index();
            $table->integer('oaws_equipment_type_id')->unsigned()->index();
            $table->integer('oaws_created_by')->unsigned()->index();
            $table->foreign('oaws_division_id')->references('division_id')->on('divisions');
            $table->foreign('oaws_product_category_id')->references('p_category_id')->on('product_categories');
            $table->foreign('oaws_equipment_type_id')->references('equipment_id')->on('equipment_type');
            $table->foreign('oaws_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_analyst_window_settings');
    }
}
