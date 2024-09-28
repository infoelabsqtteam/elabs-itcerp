<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerContactPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contact_persons', function (Blueprint $table) {
            $table->increments('contact_id');
			$table->integer('customer_id')->unsigned()->index();            
			$table->string('contact_name1')->nullable();
			$table->string('contact_name2')->nullable();
			$table->string('contact_designate1')->nullable();
			$table->string('contact_designate2')->nullable();
			$table->bigInteger('contact_mobile1')->nullable();
			$table->bigInteger('contact_mobile2')->nullable();
			$table->string('contact_email1')->nullable();
			$table->string('contact_email2')->nullable();
			$table->string('is_main')->nullable();
            $table->timestamps();
			$table->foreign('customer_id')->references('customer_id')->on('customer_master')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_contact_persons');
    }
}
