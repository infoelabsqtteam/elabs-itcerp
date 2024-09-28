<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('customer_id')->on('customer_master');
			$table->string('inquiry_no');			
			$table->LONGTEXT('inquiry_detail');			
			$table->date('inquiry_date');			
			$table->date('next_followup_date');			
			$table->enum('inquiry_status', ['open', 'closed','won'])->default('open');
			$table->integer('created_by')->unsigned()->index()->nullable();
			$table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('inquiry');
    }
}
