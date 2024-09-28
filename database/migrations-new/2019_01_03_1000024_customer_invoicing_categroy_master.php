<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerInvoicingCategroyMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('customer_invoicing_category_master', function (Blueprint $table) {
         $table->increments('cicm_id');
         $table->string('cicm_name');
         $table->string('cicm_gst_type');
         $table->tinyInteger('cicm_tax_slab_type')->unsigned()->index()->comment('0 for NA,1 for Applicable');
         $table->tinyInteger('cicm_status')->unsigned()->index()->comment('0 for Inactive,1 for Active');
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
        Schema::dropIfExists('customer_invoicing_category_master');
    }
}
