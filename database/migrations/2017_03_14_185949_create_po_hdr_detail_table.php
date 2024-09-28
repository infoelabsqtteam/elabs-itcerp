<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoHdrDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_hdr_detail', function (Blueprint $table) {
            $table->increments('po_dtl_id');
			$table->integer('po_hdr_id')->unsigned()->index();
			$table->foreign('po_hdr_id')->references('po_hdr_id')->on('po_hdr')->onDelete('cascade');
			$table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('item_id')->on('item_master');
			$table->decimal('item_rate', 10, 2);
			$table->integer('purchased_qty');
			$table->decimal('item_amount', 10, 2);
			$table->integer('short_close_qty');
			$table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('po_hdr_detail');
    }
}
