<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_report_groups', function (Blueprint $table) {
            $table->increments('org_group_id');
            $table->string('org_group_code')->unique();
            $table->string('org_group_name')->unique();
            $table->integer('org_division_id')->unsigned()->index();
            $table->foreign('org_division_id')->references('division_id')->on('divisions');
            $table->integer('org_product_category_id')->unsigned()->index();
            $table->foreign('org_product_category_id')->references('p_category_id')->on('product_categories');
            $table->tinyInteger('org_group_status')->nullable()->comment('1 for Active,2 for Deactive')->nullable();
            $table->integer('org_group_created_by')->unsigned()->index()->nullable();
            $table->foreign('org_group_created_by')->references('id')->on('users');
            $table->unique(['org_group_name','org_division_id','org_product_category_id'],'group_division_product_cat_unique');
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
        Schema::dropIfExists('order_report_groups');
    }
}
