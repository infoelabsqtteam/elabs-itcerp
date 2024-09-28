<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportNoteRemarkDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('order_report_note_remark_default', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('remark_id');
            $table->integer('division_id')->unsigned()->index();
            $table->integer('product_category_id')->unsigned()->index();
            $table->text('remark_name');
            $table->tinyInteger('type')->comment('1 for notes and 2 for remarks');
            $table->tinyInteger('is_display_stamp')->nullable()->comment('1 for standard quality stamp,2 for not standard quality stamp');
            $table->tinyInteger('remark_status')->nullable()->default(1);
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('division_id')->references('division_id')->on('divisions');
            $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('order_report_note_remark_default');
    }
}
