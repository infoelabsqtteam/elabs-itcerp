<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
	    $table->increments('sample_id');
	    $table->string('sample_no');
	    $table->integer('division_id')->unsigned()->index();
	    $table->integer('product_category_id')->unsigned()->index();
	    $table->date('sample_date');
	    $table->dateTime('sample_current_date');
	    $table->dateTime('sample_booked_date')->nullable();
	    $table->integer('customer_id')->unsigned()->index()->nullable();
	    $table->string('customer_name')->nullable();
	    $table->string('customer_email')->nullable()->unique();  
	    $table->integer('sample_mode_id')->unsigned()->index();
	    $table->integer('trf_id')->unsigned()->index();
	    $table->longText('sample_mode_description')->nullable();
	    $table->tinyInteger('sample_status')->nullable();
	    $table->tinyInteger('internal_transfer')->nullable();	
	    $table->longText('remarks')->nullable();
	    $table->integer('created_by')->unsigned()->index()->nullable();	    
	    $table->foreign('division_id')->references('division_id')->on('divisions');
	    $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');	    
	    $table->foreign('customer_id')->references('customer_id')->on('customer_master');  
	    $table->foreign('sample_mode_id')->references('sample_mode_id')->on('sample_modes');
	    $table->foreign('trf_id')->references('trf_id')->on('trf_hdrs');
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
        Schema::dropIfExists('samples');
    }
}
