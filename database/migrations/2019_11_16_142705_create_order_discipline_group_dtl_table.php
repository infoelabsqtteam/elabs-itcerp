<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDisciplineGroupDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_discipline_group_dtl', function (Blueprint $table) {
            $table->increments('odg_id');
            $table->Integer('order_id')->unsigned()->index();
            $table->foreign('order_id')->references('order_id')->on('order_master');
            $table->integer('test_parameter_category_id')->unsigned();
            $table->foreign('test_parameter_category_id','odg_test_parameter_category_id')->references('test_para_cat_id')->on('test_parameter_categories');            
            $table->integer('discipline_id')->unsigned();
            $table->foreign('discipline_id')->references('or_discipline_id')->on('order_report_disciplines');  
            $table->integer('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('org_group_id')->on('order_report_groups');        
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
        Schema::dropIfExists('order_discipline_group_dtl');
    }
}
