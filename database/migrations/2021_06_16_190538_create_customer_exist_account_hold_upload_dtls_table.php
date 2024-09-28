<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerExistAccountHoldUploadDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_exist_account_hold_upload_dtl', function (Blueprint $table) {
            $table->increments('ceahud_id');
            $table->string('ceahud_customer_code');
            $table->string('ceahud_customer_name');
            $table->string('ceahud_customer_city');
            $table->decimal('ceahud_outstanding_amt', 10, 2)->nullable();
            $table->decimal('ceahud_ab_outstanding_amt', 10, 2)->nullable();
            $table->integer('ceahud_customer_id')->unsigned()->index()->nullable();
            $table->integer('ceahud_created_by')->unsigned()->index()->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('ceahud_customer_id','ceahud_customer_id')->references('customer_id')->on('customer_master');
            $table->foreign('ceahud_created_by','ceahud_created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_exist_account_hold_upload_dtl');
    }
}
