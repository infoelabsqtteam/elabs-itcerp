<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDynamicFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_dynamic_field_dtl', function (Blueprint $table) {
            $table->increments('odf_id');
            $table->Integer('order_id')->unsigned()->index();
            $table->foreign('order_id')->references('order_id')->on('order_master');
            $table->string('order_field_name');
            $table->string('order_field_value');
            $table->dateTime('order_field_date');
            $table->integer('odf_created_by')->unsigned()->index()->nullable();
            $table->foreign('odf_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_dynamic_field_dtl');
    }
}
