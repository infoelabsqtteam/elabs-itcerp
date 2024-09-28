<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMadeHdrTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('payment_made_hdr', function (Blueprint $table){
            $table->increments('payment_made_hdr_id');			
			$table->integer('division_id')->unsigned()->index()->nullable();
			$table->integer('vendor_id')->unsigned()->index()->nullable();
			$table->string('payment_made_no')->unique();	
			$table->date('payment_made_date');									
			$table->decimal('payment_made_amount', 10, 2);
			$table->text('payment_source_id');
			$table->integer('created_by')->unsigned()->index()->nullable();
			$table->timestamps();
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('division_id')->references('division_id')->on('divisions');	
			$table->foreign('vendor_id')->references('vendor_id')->on('vendors');
			$table->foreign('payment_source_id')->references('payment_source_id')->on('payment_sources');	
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_made_hdr');
    }
}
