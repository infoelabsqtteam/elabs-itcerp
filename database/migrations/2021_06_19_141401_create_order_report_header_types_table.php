<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportHeaderTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_report_header_types', function (Blueprint $table) {
            $table->increments('orht_id');
            $table->Integer('orht_division_id')->unsigned()->index();
			$table->foreign('orht_division_id')->references('division_id')->on('divisions');
            $table->integer('orht_product_category_id')->unsigned();
            $table->foreign('orht_product_category_id')->references('department_id')->on('departments');
            $table->integer('orht_customer_type')->unsigned();
            $table->foreign('orht_customer_type')->references('type_id')->on('customer_types');
            $table->integer('orht_report_hdr_type')->unsigned()->index()->nullaborht_le();
	        $table->foreign('orht_report_hdr_type')->references('rhtd_id')->on('report_header_type_default');
            $table->integer('orht_created_by')->unsigned()->nullable();
            $table->foreign('orht_created_by')->references('id')->on('users');            
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
        Schema::dropIfExists('order_report_header_types');

    }
}
