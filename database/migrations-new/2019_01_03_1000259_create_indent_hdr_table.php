<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndentHdrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indent_hdr', function (Blueprint $table) {
	    $table->increments('indent_hdr_id'); 			
	    $table->integer('division_id')->unsigned()->index();
	    $table->foreign('division_id')->references('division_id')->on('divisions');			
	    $table->date('indent_date');
	    $table->string('indent_no');
	    $table->enum('short_closed', ['0', '1'])->default('0')->nullable();
	    $table->date('short_close_date')->nullable();			
	    $table->integer('indented_by')->unsigned()->index();
	    $table->foreign('indented_by')->references('id')->on('users');			
	    $table->integer('created_by')->unsigned()->index();
	    $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('indent_hdr');
    }
}
