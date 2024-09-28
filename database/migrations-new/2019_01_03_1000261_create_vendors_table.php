<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
	    $table->increments('vendor_id');
	    $table->string('vendor_code')->unique();
	    $table->integer('division_id')->unsigned()->index();
	    $table->string('vendor_name');
	    $table->string('vendor_email');
	    $table->integer('vendor_mobile');
	    $table->integer('vendor_state')->unsigned()->index();
	    $table->integer('vendor_city')->unsigned()->index();
	    $table->string('vendor_pincode');	
	    $table->string('vendor_address');			
	    $table->string('vendor_cust_code');
	    $table->string('vendor_website');
	    $table->string('vat_no');		
	    $table->string('gst_no');				
	    $table->string('contact_person_name');
	    $table->string('contact_person_email');
	    $table->string('contact_person_mobile');			
	    $table->string('credit_days');	
	    $table->integer('created_by')->unsigned()->index()->nullable();		
	    $table->timestamps();			
	    $table->foreign('division_id')->references('division_id')->on('divisions');
	    $table->foreign('vendor_state')->references('state_id')->on('state_db');
	    $table->foreign('vendor_city')->references('city_id')->on('city_db');
	    $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
