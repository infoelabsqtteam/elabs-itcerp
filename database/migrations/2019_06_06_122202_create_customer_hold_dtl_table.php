<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerHoldDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_hold_dtl', function (Blueprint $table) {
            $table->increments('chd_id');
            $table->integer('chd_customer_id')->unsigned()->index();
            $table->foreign('chd_customer_id')->references('customer_id')->on('customer_master')->onDelete('cascade');
            $table->text('chd_hold_description');
            $table->dateTime('chd_hold_date');
            $table->string('chd_hold_by');
            $table->tinyInteger('chd_hold_status')->unsigned()->index()->comment('1 for Credit Collection,2 for Account Hold, 3 for Account Hold due to Uploaded Record');
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
        Schema::dropIfExists('customer_hold_dtl');
    }
}
