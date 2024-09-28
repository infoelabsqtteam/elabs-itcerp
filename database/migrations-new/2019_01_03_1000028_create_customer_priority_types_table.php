<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPriorityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('customer_priority_types', function (Blueprint $table) {
	       $table->increments('customer_priority_id');
	       $table->string('customer_priority_name');
	       $table->tinyInteger('customer_priority_status')->nullable();	
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
           Schema::dropIfExists('customer_priority_types');
    }
}
