<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReportDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_report_disciplines', function (Blueprint $table) {
            $table->increments('or_discipline_id');
            $table->string('or_discipline_code')->unique();
            $table->string('or_discipline_name')->unique();
            $table->tinyInteger('or_discipline_status')->nullable()->comment('1 for Active,2 for Deactive')->nullable();
            $table->integer('or_discipline_created_by')->unsigned()->index()->nullable();
            $table->foreign('or_discipline_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('order_report_disciplines');
    }
}
