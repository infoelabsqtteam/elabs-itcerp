<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerCompanyTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_company_type', function (Blueprint $table) {
            $table->increments('company_type_id');
			$table->string('company_type_code')->nullable();
			$table->string('company_type_name')->nullable();
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
        Schema::dropIfExists('customer_company_type');
    }
}
