<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTestParameterEquipmentUsageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_test_parameter_equipment_usage', function (Blueprint $table) {
            $table->increments('equp_usage_id');
            $table->integer('product_test_parameter_id');
            $table->integer('equipment_id');
            $table->integer('usage_time');
            $table->integer('cost');
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
        Schema::dropIfExists('product_test_parameter_equipment_usage');
    }
}
