<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerEmailAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_email_addresses', function (Blueprint $table) {
	    $table->increments('customer_email_id');
	    $table->integer('customer_id')->unsigned()->index();
	    $table->foreign('customer_id')->references('customer_id')->on('customer_master')->onDelete('cascade');
	    $table->string('customer_email');
	    $table->string('customer_email_type');
	    $table->string('customer_email_status')->nullable();
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
        Schema::dropIfExists('customer_email_addresses');
    }
}
