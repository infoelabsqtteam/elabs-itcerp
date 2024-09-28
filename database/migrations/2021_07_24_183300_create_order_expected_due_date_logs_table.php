<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderExpectedDueDateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_expected_due_date_logs', function (Blueprint $table) {
            $table->increments('oeddl_id');
            $table->integer('oeddl_order_id')->unsigned()->index();
            $table->foreign('oeddl_order_id')->references('order_id')->on('order_master');
            $table->dateTime('oeddl_current_expected_due_date');
            $table->dateTime('oeddl_modified_expected_due_date');
            $table->integer('oeddl_no_of_days')->index();
            $table->text('oeddl_reason_of_change');
            $table->tinyInteger('oeddl_exclude_logic_calculation')->nullable()->comment('1 for exclude, 2 for Include');
            $table->tinyInteger('oeddl_send_mail_status')->nullable()->comment('1 for Yes, 2 for No');
            $table->dateTime('oeddl_modified_date');
            $table->integer('oeddl_created_by')->unsigned()->index()->nullable();
            $table->foreign('oeddl_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_expected_due_date_logs');
    }
}
