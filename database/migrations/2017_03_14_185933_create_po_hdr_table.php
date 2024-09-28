<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoHdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_hdr', function (Blueprint $table) {
            $table->increments('po_hdr_id');
			$table->integer('division_id')->unsigned()->index();
			$table->foreign('division_id')->references('division_id')->on('divisions');
			
			$table->tinyInteger('dpo_po_type'); 
			$table->tinyInteger('status')->default(1);
			$table->date('dpo_date')->nullable();
			$table->string('dpo_no')->nullable();
			$table->date('po_date');
			$table->string('po_no');
			
			$table->integer('vendor_id')->unsigned()->index()->nullable();
			$table->foreign('vendor_id')->references('vendor_id')->on('vendors');
			$table->string('payment_term');
			
			$table->string('amendment_no')->nullable();
			$table->date('amendment_date')->nullable();
			$table->text('amendment_detail')->nullable();
	
			$table->integer('total_qty');
			$table->decimal('gross_total', 10, 2);			
			$table->decimal('item_discount', 10, 2);
			$table->decimal('amount_after_discount', 10, 2);
			$table->decimal('excise_duty_rate', 10, 2);
			$table->decimal('amount_after_excise_duty_rate', 10, 2);
			$table->decimal('sales_tax_rate', 10, 2);
			$table->decimal('amount_after_sales_tax_rate', 10, 2);
			$table->decimal('grand_total', 10, 2);
			$table->date('short_close_date')->nullable();
			
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
        Schema::dropIfExists('po_hdr');
    }
}
