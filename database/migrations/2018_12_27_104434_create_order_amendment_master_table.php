<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAmendmentMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('order_amendment_master', function (Blueprint $table) {
          $table->increments('oam_id');
          $table->string('oam_name');
          $table->string('oam_code');
          $table->tinyInteger('oam_status')->unsigned()->index()->comment('0 for Inactive,1 for Active');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
   public function down(){
      Schema::dropIfExists('order_amendment_master');
   }
}
