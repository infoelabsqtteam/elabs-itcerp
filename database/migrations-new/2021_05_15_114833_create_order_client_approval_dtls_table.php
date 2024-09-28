<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderClientApprovalDtlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_client_approval_dtl', function (Blueprint $table) {
            $table->increments('ocad_id');
            $table->string('ocad_approved_by');
            $table->date('ocad_date');  
            $table->integer('ocad_credit_period')->index();
            $table->date('ocad_date_upto_amt'); 
            $table->tinyInteger('ocad_status')->default(1)->nullable()->comment('1 for active,2 for Inactive');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_client_approval_dtl');
    }
}
