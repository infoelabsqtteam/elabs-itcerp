<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStbOrderStabilityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stb_order_stability_types', function (Blueprint $table) {
            $table->increments('stb_stability_type_id');
            $table->string('stb_stability_type_name');
            $table->tinyInteger('stb_stability_type_status')->unsigned()->index()->comment('0 for Inactive,1 for Active');
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
        Schema::dropIfExists('stb_order_stability_types');
    }
}
