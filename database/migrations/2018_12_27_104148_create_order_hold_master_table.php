<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderHoldMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(){
      Schema::create('order_hold_master', function (Blueprint $table) {
          $table->increments('oh_id');
          $table->string('oh_name');
          $table->string('oh_code');
          $table->tinyInteger('oh_status')->unsigned()->index()->comment('0 for Inactive,1 for Active');
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
      Schema::dropIfExists('order_hold_master');

    }
}
