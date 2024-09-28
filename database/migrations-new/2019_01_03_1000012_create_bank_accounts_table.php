<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
	    $table->increments('bank_id');
	    $table->string('bank_code')->unique();
	    $table->string('bank_name');
	    $table->string('branch_name');
	    $table->string('branch_address');
	    $table->string('IFSC');
	    $table->string('MICR');
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
        Schema::dropIfExists('bank_accounts');
    }
}
