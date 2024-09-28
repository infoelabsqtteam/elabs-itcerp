<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDefinedTestStdDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_defined_test_std_dtl', function (Blueprint $table) {
            $table->increments('odtsd_id');
            $table->Integer('odtsd_branch_id')->unsigned()->index();
            $table->Integer('odtsd_product_category_id')->unsigned()->index();
            $table->Integer('odtsd_test_standard_id')->unsigned()->index();
            $table->Integer('odtsd_created_by')->unsigned()->index()->nullable();
            $table->foreign('odtsd_created_by')->references('id')->on('users');
            $table->foreign('odtsd_branch_id')->references('division_id')->on('divisions');
            $table->foreign('odtsd_product_category_id')->references('p_category_id')->on('product_categories');
            $table->foreign('odtsd_test_standard_id')->references('test_std_id')->on('test_standard');

            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_defined_test_std_dtl');

    }
}
