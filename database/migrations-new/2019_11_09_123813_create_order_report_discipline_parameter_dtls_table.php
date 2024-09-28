<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportDisciplineParameterDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_report_discipline_parameter_dtls', function (Blueprint $table) {
            $table->increments('ordp_id');
            $table->integer('ordp_division_id')->unsigned();
            $table->foreign('ordp_division_id')->references('division_id')->on('divisions');
            $table->integer('ordp_product_category_id')->unsigned();
            $table->foreign('ordp_product_category_id','ordpd_product_category_id')->references('p_category_id')->on('product_categories');
            $table->integer('ordp_discipline_id')->unsigned();
            $table->foreign('ordp_discipline_id','ordpd_discipline_id')->references('or_discipline_id')->on('order_report_disciplines');
            $table->integer('ordp_test_parameter_category_id')->unsigned();
            $table->foreign('ordp_test_parameter_category_id','ordpd_test_parameter_category_id')->references('test_para_cat_id')->on('test_parameter_categories');            
            $table->unique(['ordp_division_id','ordp_product_category_id','ordp_discipline_id','ordp_test_parameter_category_id'],'division_product_cat_discipline_param_unique');            
            $table->unique(['ordp_division_id','ordp_product_category_id','ordp_test_parameter_category_id'],'division_product_cat_param_unique'); 
            $table->integer('ordp_created_by')->unsigned()->index()->nullable();
            $table->foreign('ordp_created_by','ordpd_ordp_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_report_discipline_parameter_dtls');
    }
}
