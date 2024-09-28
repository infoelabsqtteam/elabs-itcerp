<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndentHdrDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indent_hdr_detail', function (Blueprint $table) {
            $table->increments('indent_dtl_id');
			$table->integer('indent_hdr_id')->unsigned()->index();
			$table->foreign('indent_hdr_id')->references('indent_hdr_id')->on('indent_hdr')->onDelete('cascade');
			$table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('item_id')->on('item_master');
			$table->integer('indent_qty');
			$table->integer('qty_on_po');
			$table->integer('qty_purchased_on_po');
			$table->integer('qty_purchased_direct');
			$table->integer('qty_short_closed');
			$table->date('required_by_date');
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
        Schema::dropIfExists('indent_hdr_detail');
    }
}
