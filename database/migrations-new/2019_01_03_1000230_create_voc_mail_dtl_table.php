<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVocMailDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voc_mail_dtl', function (Blueprint $table) {
            $table->increments('voc_id');
            $table->integer('voc_customer_id')->unsigned()->index();
            $table->foreign('voc_customer_id')->references('customer_id')->on('customer_master');
            $table->longText('voc_order_ids');
            $table->string('voc_template_name');
            $table->tinyInteger('voc_status')->comment('0 for unsend,1 for send')->unsigned()->index();
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
        Schema::dropIfExists('voc_mail_dtl');
    }
}
