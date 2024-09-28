<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIgnHdrDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ign_hdr_dtl', function (Blueprint $table) {
            $table->increments('ign_hdr_dtl_id');            
            $table->integer('ign_hdr_id')->unsigned()->index();
            $table->integer('po_hdr_id')->unsigned()->index();			
            $table->integer('item_id')->unsigned()->index();
            $table->date('expiry_date')->nullable();
            $table->integer('bill_qty')->unsigned();
            $table->integer('received_qty')->unsigned();
            $table->integer('ok_qty')->unsigned();
            $table->decimal('bill_rate', 10, 2);
            $table->decimal('pass_rate', 10, 2);
            $table->decimal('landing_cost', 10, 2);           
            $table->timestamps();            
            $table->foreign('item_id')->references('item_id')->on('item_master');
            $table->foreign('po_hdr_id')->references('po_hdr_id')->on('po_hdr');
            $table->foreign('ign_hdr_id')->references('ign_hdr_id')->on('ign_hdr')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ign_hdr_dtl');
    }
}
