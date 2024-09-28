<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamicFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_dynamic_fields', function (Blueprint $table) {
            $table->increments('odfs_id');
            $table->string('dynamic_field_name');
            $table->string('dynamic_field_code')->unique();
            $table->tinyInteger('dynamic_field_status')->nullable();
            $table->integer('odfs_created_by')->unsigned()->index()->nullable();
            $table->foreign('odfs_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_dynamic_fields');
    }
}
