<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoIndentDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_indent_detail', function (Blueprint $table) {
            $table->increments('po_indent_dtl_id');
			$table->integer('po_hdr_id')->unsigned()->index();
			$table->foreign('po_hdr_id')->references('po_hdr_id')->on('po_hdr')->onDelete('cascade');
			$table->integer('indent_dtl_id')->unsigned()->index();
			$table->foreign('indent_dtl_id')->references('indent_dtl_id')->on('indent_hdr_detail')->onDelete('cascade');
			$table->integer('qty');
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
        Schema::dropIfExists('po_indent_detail');
    }
}
