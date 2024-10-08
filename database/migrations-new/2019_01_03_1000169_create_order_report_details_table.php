<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_report_details', function (Blueprint $table) {
            $table->increments('order_report_id');
            $table->integer('report_id')->unsigned()->index();
            $table->string('report_no')->nullable();
            $table->foreign('report_id')->references('order_id')->on('order_master'); 		
            $table->string('nabl_no')->nullable();     
            $table->dateTime('report_date')->nullable();
            $table->dateTime('reviewing_date')->nullable();
            $table->integer('reviewed_by')->unsigned()->index()->nullable();
            $table->foreign('reviewed_by')->references('id')->on('users');	
            $table->dateTime('finalizing_date')->nullable();
            $table->integer('finalized_by')->unsigned()->index()->nullable();
            $table->foreign('finalized_by')->references('id')->on('users');	
            $table->dateTime('approving_date')->nullable();
            $table->integer('approved_by')->unsigned()->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->tinyInteger('report_type')->nullable();            
            $table->string('with_amendment_no')->nullable();
            $table->string('is_amended_no')->nullable();           
            $table->string('grade_type')->nullable();
            $table->string('declared_values')->nullable();
            $table->tinyInteger('ref_sample_value')->nullable();
            $table->tinyInteger('result_drived_value')->nullable();
            $table->tinyInteger('deviation_value')->nullable();
            $table->text('remark_value')->nullable();    
            $table->string('test_standard_value')->nullable();
            $table->text('note_value')->nullable();
            $table->string('report_file_name')->nullable();
            $table->string('report_file_name_without_hf')->nullable();
            $table->string('report_microbiological_name')->nullable();
            $table->string('report_microbiological_sign')->nullable();
            $table->text('header_content')->nullable();
            $table->text('footer_content')->nullable();
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
        Schema::dropIfExists('order_report_details');
    }
}
