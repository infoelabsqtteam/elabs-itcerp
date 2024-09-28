<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPurchaseOrderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_purchase_order_logs', function (Blueprint $table) {
            $table->increments('opol_id');
            $table->integer('opol_order_id')->unsigned()->index();
            $table->foreign('opol_order_id')->references('order_id')->on('order_master');
            $table->string('opol_order_no');
            $table->string('opol_po_no');
            $table->date('opol_po_date');
            $table->integer('opol_created_by')->unsigned()->index();
            $table->foreign('opol_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_purchase_order_logs');
    }
}
