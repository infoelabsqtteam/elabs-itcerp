<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMasterTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_master', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('order_id');
			$table->string('order_no');
			$table->integer('status')->unsigned()->index();
			$table->integer('division_id')->unsigned()->index();
			$table->integer('product_category_id')->unsigned()->index();
			$table->integer('customer_id')->unsigned()->index();
			$table->integer('sale_executive')->unsigned()->index();
			$table->integer('sample_id')->unsigned()->index()->nullable();
			$table->dateTime('order_date');
			$table->dateTime('booking_date')->comment('Current Date of Order Booking');
			$table->integer('product_id')->unsigned()->index();
			$table->string('product_as_per_customer')->nullable();
			$table->integer('test_standard')->unsigned()->index();
			$table->integer('product_test_id')->unsigned()->index();
			$table->integer('sample_description')->unsigned()->index();
			$table->string('manufactured_by')->nullable();
			$table->string('supplied_by')->nullable();
			$table->string('mfg_date')->nullable();
			$table->string('expiry_date')->nullable();
			$table->string('batch_no')->nullable();
			$table->string('batch_size')->nullable();
			$table->string('sample_qty')->nullable();
			$table->string('pi_reference')->nullable();
			$table->string('reference_no');
			$table->longText('barcode')->nullable();
			$table->text('remarks')->nullable();
			$table->text('mfg_lic_no');
			$table->integer('customer_city')->unsigned()->index();
			$table->integer('sample_priority_id')->unsigned()->index()->nullable();
			$table->integer('discount_type_id')->unsigned()->index();
			$table->string('discount_value')->nullable();
			$table->tinyInteger('is_sealed')->unsigned()->index()->comment('0 for unsealed,1 for sealed,2 for Intact,3 for N/A');
			$table->tinyInteger('is_signed')->unsigned()->index()->comment('0 for unsigned,1 for signed,2 for N/A');
			$table->string('brand_type')->nullable();
			$table->string('packing_mode')->nullable();
			$table->dateTime('sampling_date')->nullable();
			$table->dateTime('expected_due_date')->nullable();
			$table->dateTime('order_scheduled_date')->nullable();
			$table->dateTime('test_completion_date')->nullable();
			$table->dateTime('incharge_reviewing_date')->nullable();
			$table->dateTime('dept_due_date_1')->nullable();
			$table->dateTime('report_due_date_1')->nullable();
			$table->string('quotation_no')->nullable();
			$table->tinyInteger('submission_type')->unsigned()->index()->nullable();
			$table->integer('actual_submission_type')->nullable();
			$table->text('advance_details')->nullable();
			$table->text('reporting_to')->nullable();
			$table->text('invoicing_to')->nullable();
			$table->text('hold_reason')->nullable();
			$table->decimal('surcharge_value', 10, 2)->nullable();
			$table->dateTime('dispatched_date_time')->nullable();
			$table->text('header_note')->nullable();
			$table->text('stability_note')->nullable();
			$table->decimal('extra_amount', 10, 2)->nullable();
			$table->string('sample_condition')->nullable();
			$table->integer('created_by')->unsigned()->index()->nullable();
			$table->string('letter_no');
			$table->integer('invoicing_type_id')->unsigned()->index();
			$table->integer('billing_type_id')->unsigned()->index();
			$table->dateTime('po_date');
			$table->string('po_no');
			$table->tinyInteger('order_sample_type')->comment('1 for inter-laboratory,2 for Compensatory');
			$table->string('job_order_file');
			$table->string('job_analytical_sheet_file');
			$table->string('job_analytical_sheet_cal_file');
			$table->integer('order_reinvoiced_count');
			$table->tinyInteger('tat_in_days')->comment('Entered tat in days');
			$table->integer('stb_order_hdr_detail_id')->unsigned()->index();			
			$table->integer('sample_description_id')->unsigned()->index();
			$table->integer('sampler_id')->unsigned()->index()->nullable();
			$table->foreign('invoicing_type_id')->references('invoicing_type_id')->on('customer_invoicing_types');
			$table->foreign('billing_type_id')->references('billing_type_id')->on('customer_billing_types');
			$table->foreign('sample_description_id')->references('c_product_id')->on('product_master_alias');
			$table->foreign('stability_id')->references('stability_id')->on('order_stability_notes');
			$table->foreign('status')->references('order_status_id')->on('order_status');
			$table->foreign('division_id')->references('division_id')->on('divisions');
			$table->foreign('customer_id')->references('customer_id')->on('customer_master');
			$table->foreign('sale_executive')->references('id')->on('users');
			$table->foreign('product_id')->references('product_id')->on('product_master');
			$table->foreign('product_test_id')->references('test_id')->on('product_test_hdr');
			$table->foreign('test_standard')->references('test_std_id')->on('test_standard');
			$table->foreign('discount_type_id')->references('discount_type_id')->on('customer_discount_types');
			$table->foreign('sample_priority_id')->references('sample_priority_id')->on('order_sample_priority');
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('sample_id')->references('sample_id')->on('samples');
			$table->foreign('product_category_id')->references('p_category_id')->on('product_categories');
			$table->foreign('stb_order_hdr_detail_id`')->references('stb_order_hdr_detail_id`')->on('stb_order_hdr_dtl_detail');			
			$table->integer('defined_test_standard')->unsigned()->index()->nullable();
			$table->foreign('defined_test_standard')->references('test_std_id')->on('test_standard');
			$table->foreign('sampler_id')->references('id')->on('users');
			$table->foreign('customer_city')->references('city_id')->on('city_db');
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
		Schema::dropIfExists('order_master');
	}
}
