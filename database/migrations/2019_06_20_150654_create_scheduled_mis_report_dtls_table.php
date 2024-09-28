<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledMisReportDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_mis_report_dtls', function (Blueprint $table) {
            $table->increments('smrd_id');
            $table->integer('smrd_division_id')->unsigned()->index();
	    $table->integer('smrd_product_category_id')->unsigned()->index();
            $table->integer('smrd_mis_report_id')->unsigned()->index();
            $table->string('smrd_to_email_address');
            $table->string('smrd_from_email_address');
            $table->integer('smrd_created_by')->unsigned()->index()->nullable();            
	    $table->foreign('smrd_created_by')->references('id')->on('users');
            $table->foreign('smrd_division_id')->references('division_id')->on('divisions');
	    $table->foreign('smrd_product_category_id')->references('p_category_id')->on('product_categories');
            $table->foreign('smrd_mis_report_id')->references('mis_report_id')->on('mis_report_default_types');
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
        Schema::dropIfExists('scheduled_mis_report_dtls');
    }
}
