<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_notes', function (Blueprint $table) {
	    $table->increments('debit_note_id');
	    $table->integer('debit_note_type_id')->unsigned()->index();
	    $table->integer('division_id')->unsigned()->index();
	    $table->integer('product_category_id')->unsigned()->index();
	    $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');	
	    $table->integer('customer_id')->unsigned()->index();
	    $table->integer('invoice_id')->unsigned()->index()->nullable();
	    $table->string('debit_reference_no')->nullable();	
	    $table->string('debit_note_no')->unique();	
	    $table->date('debit_note_date');									
	    $table->decimal('debit_note_amount', 10, 2);	    
	    $table->decimal('debit_note_sgst_rate', 10, 2)->nullable();
	    $table->decimal('debit_note_sgst_amount', 10, 2)->nullable();
	    $table->decimal('debit_note_cgst_rate', 10, 2)->nullable();
	    $table->decimal('debit_note_cgst_amount', 10, 2)->nullable();
	    $table->decimal('debit_note_igst_rate', 10, 2)->nullable();
	    $table->decimal('debit_note_igst_amount', 10, 2)->nullable();
	    $table->decimal('debit_note_net_amount', 10, 2);	    
	    $table->string('debit_note_remark');            
	    $table->integer('created_by')->unsigned()->index()->nullable();            
	    $table->foreign('created_by')->references('id')->on('users');
	    $table->foreign('division_id')->references('division_id')->on('divisions');
	    $table->foreign('customer_id')->references('customer_id')->on('customer_master');
	    $table->foreign('invoice_id')->references('invoice_id')->on('invoice_hdr');
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
        Schema::dropIfExists('debit_notes');
    }
}
