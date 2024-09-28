<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledMailDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_mail_dtl', function (Blueprint $table) {
            $table->increments('smd_id');
            $table->integer('smd_customer_id')->unsigned()->index();
            $table->foreign('smd_customer_id')->references('customer_id')->on('customer_master');
            $table->longText('smd_order_ids');
            $table->tinyInteger('smd_content_type')->comment('1 for VOC,2 for Order Confirmation')->unsigned()->index();
            $table->string('smd_template_name');
            $table->tinyInteger('smd_status')->comment('0 for unsend,1 for send')->unsigned()->index();
            $table->datetime('smd_mail_date');
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
        Schema::dropIfExists('scheduled_mail_dtl');
    }
}
