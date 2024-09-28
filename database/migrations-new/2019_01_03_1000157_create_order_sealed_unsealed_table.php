<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSealedUnsealedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('order_sealed_unsealed', function (Blueprint $table) {
         $table->increments('osus_id');
         $table->string('osus_name');
         $table->tinyInteger('osus_status')->unsigned()->index()->comment('0 for Inactive,1 for Active');
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
      Schema::dropIfExists('order_sealed_unsealed');
    }
}
