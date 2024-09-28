<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAccreditationCertificateMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_accreditation_certificate_master', function (Blueprint $table) {
            $table->increments('oac_id');
            $table->integer('oac_division_id')->unsigned()->index();
            $table->string('oac_name');
            $table->integer('oac_multi_location_lab_value');
            $table->tinyInteger('oac_status')->nullable()->comment('0 for inactive,1 for active');
            $table->integer('created_by')->nullable()->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('oac_division_id')->references('division_id')->on('divisions');   
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
        Schema::dropIfExists('order_accreditation_certificate_master');
    }
}
