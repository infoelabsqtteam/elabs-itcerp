<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLinkedPoDtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_linked_po_dtl', function (Blueprint $table) {
            $table->increments('olpd_id');
            $table->integer('olpd_order_id')->unsigned()->index()->unique();
            $table->foreign('olpd_order_id')->references('order_id')->on('order_master');            
            $table->integer('olpd_cpo_id')->unsigned()->index();
            $table->foreign('olpd_cpo_id')->references('cpo_id')->on('central_po_dtls');            
            $table->string('olpd_cpo_no');
            $table->string('olpd_cpo_file_name');
            $table->string('olpd_cpo_sample_name');            
            $table->datetime('olpd_date');
            $table->datetime('olpd_cpo_date');
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
        Schema::dropIfExists('order_linked_po_dtl');
    }
}
