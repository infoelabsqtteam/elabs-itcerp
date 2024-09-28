<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisions', function (Blueprint $table) {
	    $table->increments('division_id');
	    $table->string('division_code')->unique();
	    $table->string('division_name');
	    $table->integer('company_id')->unsigned()->index();
	    $table->integer('created_by')->unsigned()->index()->nullable();
	    $table->timestamps();
	    $table->foreign('company_id')->references('company_id')->on('company_master')->onDelete('cascade');
	    //$table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('divisions');
    }
}
