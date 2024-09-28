<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSignedUnsignedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(){
      Schema::create('order_signed_unsigned', function (Blueprint $table) {
         $table->increments('osu_id');
         $table->string('osu_name');
         $table->tinyInteger('osu_status')->unsigned()->index()->comment('0 for Inactive,1 for Active');
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
      Schema::dropIfExists('order_signed_unsigned');
    }
}
