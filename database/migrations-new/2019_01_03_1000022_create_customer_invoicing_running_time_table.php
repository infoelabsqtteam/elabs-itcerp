<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInvoicingRunningTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_invoicing_running_time', function (Blueprint $table) {
            $table->increments('invoicing_running_time_id');
            $table->string('invoicing_running_time_code')->unique;
            $table->string('invoicing_running_time');
            $table->string('invoicing_running_time_key');
            $table->tinyInteger('invoicing_running_time_status')->default('1')->nullable();
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
        Schema::dropIfExists('customer_invoicing_running_time');
    }
}
