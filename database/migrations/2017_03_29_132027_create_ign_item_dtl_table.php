<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIgnItemDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ign_item_dtl', function (Blueprint $table) {
            $table->increments('ign_item_dtl_id');            
            $table->integer('ign_hdr_dtl_id')->unsigned()->index();			
            $table->string('po_no');
            $table->integer('ign_po_qty')->unsigned();            
            $table->timestamps();
            $table->foreign('ign_hdr_dtl_id')->references('ign_hdr_dtl_id')->on('ign_hdr_dtl')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ign_item_dtl');
    }
}
