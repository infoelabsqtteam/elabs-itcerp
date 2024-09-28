<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedulings', function (Blueprint $table) {           
            $table->increments('scheduling_id');
            $table->tinyInteger('status')->nullable();            
            $table->integer('order_id')->unsigned()->index();            
            $table->integer('order_parameter_id')->unsigned()->index();
            $table->integer('product_category_id')->unsigned()->index();
            $table->integer('equipment_type_id')->unsigned()->index()->nullable();                        
            $table->integer('employee_id')->unsigned()->index()->nullable();            
            $table->date('tentative_date')->nullable();
            $table->time('tentative_time')->nullable();            
            $table->text('notes')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->integer('scheduled_by')->unsigned()->index()->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->timestamps();            
            $table->foreign('order_id')->references('order_id')->on('order_master')->onDelete('cascade');
            $table->foreign('order_parameter_id')->references('analysis_id')->on('order_parameters_detail');
            $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');
            $table->foreign('equipment_type_id')->references('equipment_id')->on('equipment_type');
            $table->foreign('employee_id')->references('id')->on('users');            
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('scheduled_by')->references('id')->on('users');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedulings');
    }
}
