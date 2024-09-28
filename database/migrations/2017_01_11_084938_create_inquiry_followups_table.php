<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryFollowupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_followups', function (Blueprint $table) {
            $table->increments('followup_id');
			$table->integer('inquiry_id')->unsigned();
            $table->foreign('inquiry_id')->references('id')->on('inquiry');
			$table->integer('followup_by')->unsigned();			
            $table->foreign('followup_by')->references('id')->on('users');
			$table->enum('mode', ['visit', 'phone','email','other']);
			$table->LONGTEXT('followup_detail');
			$table->date('next_followup_date');
			$table->enum('status', ['open', 'closed','won'])->default('open');
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
        Schema::dropIfExists('inquiry_followups');
    }
}
