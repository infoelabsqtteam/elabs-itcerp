<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIgnHdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ign_hdr', function (Blueprint $table) {
            $table->increments('ign_hdr_id');            
            $table->integer('division_id')->unsigned()->index();			          
            $table->date('ign_date')->nullable();
			$table->string('ign_no')->nullable();            
            $table->integer('vendor_id')->unsigned()->index()->nullable();			
            $table->date('vendor_bill_date')->nullable();
			$table->string('vendor_bill_no')->nullable();            
            $table->string('gate_pass_no')->nullable();            
            $table->integer('employee_id')->unsigned()->index()->nullable();			
            $table->string('employee_detail')->nullable();
			$table->decimal('total_bill_amount', 10, 2);
            $table->decimal('total_pass_amount', 10, 2);
			$table->decimal('total_landing_amount', 10, 2);
            $table->decimal('total_sales_tax_amount', 10, 2);
            $table->decimal('total_vat_amount', 10, 2);
            $table->decimal('total_excise_duty_amount', 10, 2);
			$table->integer('created_by')->unsigned()->index()->nullable();			
            $table->timestamps();            
            $table->foreign('division_id')->references('division_id')->on('divisions');
            $table->foreign('vendor_id')->references('vendor_id')->on('vendors');
            $table->foreign('employee_id')->references('id')->on('users');
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
        Schema::dropIfExists('ign_hdr');
    }
}
