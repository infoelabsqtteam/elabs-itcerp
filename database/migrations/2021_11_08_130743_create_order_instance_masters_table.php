<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInstanceMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instance_master', function (Blueprint $table) {
            $table->increments('instance_id');
			$table->string('instance_code')->unique();
			$table->string('instance_name')->unique();
			$table->string('instance_desc');
			$table->integer('equipment_type_id')->unsigned()->index();
			$table->foreign('equipment_type_id')->references('equipment_id')->on('equipment_type');
			$table->integer('product_category_id')->unsigned()->index();
            $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');
			$table->integer('created_by')->unsigned()->index()->nullable();
			$table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('instance_master');
    }
}
