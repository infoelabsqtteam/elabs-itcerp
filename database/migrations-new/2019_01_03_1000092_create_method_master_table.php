<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMethodMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('method_master', function (Blueprint $table) {
	    $table->increments('method_id');
	    $table->string('method_code')->unique();
	    $table->string('method_name');
	    $table->unique(['method_name','equipment_type_id','product_category_id']);
	    $table->string('method_desc');
	    $table->integer('equipment_type_id')->unsigned()->index();
	    $table->foreign('equipment_type_id')->references('equipment_id')->on('equipment_type');
	    $table->integer('product_category_id')->unsigned()->index();
	    $table->foreign('product_category_id')->references('p_category_id')->on('product_categories');
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
        Schema::dropIfExists('method_master');
    }
}
