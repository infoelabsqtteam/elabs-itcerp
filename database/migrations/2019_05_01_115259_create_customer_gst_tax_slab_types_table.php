<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerGstTaxSlabTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_gst_tax_slab_types', function (Blueprint $table) {
            $table->increments('cgtst_id');
            $table->string('cgtst_code');
            $table->string('cgtst_name');
            $table->tinyInteger('cgtst_status');
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
        Schema::dropIfExists('customer_gst_tax_slab_types');
    }
}
