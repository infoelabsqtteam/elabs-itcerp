<div class="row order-section" style="margin-top:10px!important;">Sample Detail </div>
<div class="order_detail">  
    <div class="row mT10">				
	
	<!--Branch -->
	<div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
	    <label for="division_id">Branch<em class="asteriskRed">*</em></label>
	    <select class="form-control"
		name="division_id"
		id="division_id"
		ng-model="updateOrder.division_id"
		ng-required="true"
		ng-options="division.name for division in divisionsCodeList track by division.id">
		<option value="">Select Branch</option>
	    </select>
	    <span ng-messages="erpCreateOrderForm.division_id.$error" ng-if="erpCreateOrderForm.division_id.$dirty || erpCreateOrderForm.$submitted" role="alert">
		<span ng-message="required" class="error">Branch is required</span>
	    </span>
	</div>
	<div ng-if="{{$division_id}} > 0">
	    <input type="hidden" id="division_id" ng-model="updateOrder.division_id" name="division_id" value="{{$division_id}}">
	</div>
	<!--/Branch -->
    
	<!-- Order date-->
	<div class="col-xs-3 form-group">
	    <label for="order_date">Order Date<em class="asteriskRed">*</em></label>
	    <div ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
		<div class="input-group date">
		    <input
			readonly
			type="text"
			id="order_date"
			ng-model="updateOrder.order_date"
			ng-required="true"
			name="order_date"
			class="form-control order_date_add"
			placeholder="Order Date">
		    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
		</div>
		<span ng-messages="erpCreateOrderForm.order_date.$error" ng-if="erpCreateOrderForm.order_date.$dirty  || erpCreateOrderForm.$submitted" role="alert">
		    <span ng-message="required" class="error">Order date is required</span>
		</span>
	    </div>
	    <div ng-if="{{defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}">
		<input
		    type="text"
		    id="order_date"
		    ng-model="updateOrder.order_date"
		    name="order_date"
		    ng-required="true"
		    class="form-control"
		    readonly>
		<span ng-messages="erpCreateOrderForm.order_date.$error" ng-if="erpCreateOrderForm.order_date.$dirty  || erpCreateOrderForm.$submitted" role="alert">
		    <span ng-message="required" class="error">Order date is required</span>
		</span>
	    </div>
	</div>
	<!-- /Order date-->
    
	<!--Letter Reference No-->
	<div class="col-xs-3 form-group">
	    <label for="reference_no">Letter Reference No.</label>
	    <input type="text" class="form-control" id="reference_no" ng-model="updateOrder.stb_reference_no" ng-value="updateOrder.stb_reference_no" name="reference_no" placeholder="Letter Reference No.">
	    <span ng-messages="erpCreateOrderForm.reference_no.$error" ng-if='erpCreateOrderForm.reference_no.$dirty  || erpCreateOrderForm.$submitted' role="alert">
		<span ng-message="required" class="error">Letter Reference No. is required</span>
	    </span>
	</div>
	<!--/Reference No-->
	
	<!--Letter Reference Date-->
	<div class="col-xs-3 form-group">
	    <label for="letter_no">Letter Reference Date</label>
	    <input  type="text" class="form-control" id="letter_no" ng-model="updateOrder.stb_letter_no" ng-value="updateOrder.stb_letter_no" name="letter_no" placeholder="Letter Reference Date">
	    <span ng-messages="erpCreateOrderForm.letter_no.$error" ng-if='erpCreateOrderForm.letter_no.$dirty || erpCreateOrderForm.$submitted' role="alert">
		<span ng-message="required" class="error">Letter Reference Date is required</span>
	    </span>
	</div>
	<!--/Letter Reference Date-->
	
	<!--Sample Description-->                           
	<div class="col-xs-3 form-group">
	    <label for="sample_description">Sample Name<em class="asteriskRed">*</em></label>
	    <a href="javascript:;" class="closeAlert mT5" ng-if="closeAutoSearchList" ng-click="funCloseAutoSearchList()"><i class="fa fa-close"></i></a>
	    <input
		class="form-control"
		name="sample_description" 			
		ng-model="updateOrder.stb_sample_description_name"
		ng-value="updateOrder.stb_sample_description_name"
		id="add_sample_description"
		ng-change="getAutoSearchSampleMatches(updateOrder.stb_sample_description_name,stbSampleID);"
		placeholder="Sample Name"
		ng-required='true'
		readonly
		autocomplete="off">
	    <input type="hidden" name="sample_description_id" id="sample_description_id" ng-value="updateOrder.stb_sample_description_id" ng-model="updateOrder.stb_sample_description_id">
	    <span ng-messages="erpCreateOrderForm.sample_description.$error" ng-if='erpCreateOrderForm.sample_description.$dirty || erpCreateOrderForm.$submitted' role="alert">
		<span ng-message="required" class="error">Sample Name is required</span>
	    </span>
	</div>
	<!--/Sample Description-->
    </div>

    <div class="row">
	
	<!--Batch number-->
	<div class="col-xs-3 form-group">
	    <label for="batch_no">Batch No.<em class="asteriskRed">*</em></label>
	    <input
		type="text"
		class="form-control"
		id="batch_no"
		ng-model="updateOrder.stb_batch_no"
		ng-value="updateOrder.stb_batch_no"
		ng-required="true"
		name="batch_no"
		ng-blur="funGetSearchSampleMatcheRate(updateOrder.stb_sample_description_name,customerInvoicingTypeID,stbSampleID,'add');"
		placeholder="batch no">
	    <span ng-messages="erpCreateOrderForm.batch_no.$error" ng-if='erpCreateOrderForm.batch_no.$dirty || erpCreateOrderForm.$submitted' role="alert">
		<span ng-message="required" class="error">Batch No. is required</span>
	    </span>
	</div>
	<!-- /Batch number-->
	
	<!-- Date of manufacturing.-->
	<div class="col-xs-3 form-group">
	    <label for="mfg_date">Date of Mfg.</label>
	    <input type="text" id="mfg_date" ng-model="updateOrder.stb_mfg_date" ng-value="updateOrder.stb_mfg_date" name="mfg_date" class="form-control bgWhite" placeholder="Date of Mfg.">
	</div>
	<!-- Date of manufacturing.-->
	
	<!--Date of Expiry-->
	<div class="col-xs-3 form-group">
	    <label for="expiry_date">Date of Expiry</label>
	    <input type="text" id="expiry_date" name="expiry_date" ng-model="updateOrder.stb_expiry_date" class="form-control bgWhite" placeholder="Date of Expiry">
	</div>
	<!--/Date of Expiry-->					
	
	<!--Batch Size-->
	<div class="col-xs-3 form-group">
	    <label for="batch_size">Batch Size</label>
	    <input type="text" class="form-control" id="batch_size" ng-model="updateOrder.stb_batch_size" name="batch_size" placeholder="batch size">
	</div>
	<!--/Batch Size-->
	
    </div>
    
    <div class="row">
	
	<!--Sample Qty-->
	<div class="col-xs-3 form-group">
	    <label class="col-xs-12 p0" for="sample_qty">Sample Qty.<em class="asteriskRed">*</em></label>
	    <div class="col-xs-2 p0">
		<input
		    readonly
		    type="text"
		    valid-number
		    class="form-control"
		    id="sample_qty"
		    ng-required="true"
		    ng-model="updateOrder.stb_dtl_sample_qty"
		    ng-value="updateOrder.stb_dtl_sample_qty"
		    name="sample_qty"
		    style="border-radius:4px 0 0 4px;border-right: medium none;"
		    placeholder="sample qty">
	    </div>
	    <div class="col-xs-10 p0">
		<input
		    readonly
		    type="text"
		    valid-alphabet
		    class="form-control"
		    id="sample_qty_unit"
		    name="sample_qty_unit" 
		    ng-model="updateOrder.stb_sample_qty_unit"
		    ng-value="updateOrder.stb_sample_qty_unit"
		    style="border-radius:0 4px 4px 0;border-left: medium none;"
		    placeholder="sample qty unit">
		<span ng-messages="erpCreateOrderForm.sample_qty.$error" ng-if='erpCreateOrderForm.sample_qty.$dirty || erpCreateOrderForm.$submitted' role="alert">
		    <span ng-message="required" class="error">Batch No. is required</span>
		</span>
	    </div>
	</div>
	<!--/Sample Qty-->
	
	<!--Manufactured By-->
	<div class="col-xs-3 form-group">
	    <label for="manufactured_by">Supplied By<input type="checkbox" ng-model="updateOrder.suppliedBy" ng-click="funCheckCustomerDetail(updateOrder.suppliedBy,updateOrder.customer_name,'supplied_by')" ng-checked="updateOrder.stb_supplied_by.length > 0"></label>
	    <input type="text" class="form-control" id="supplied_by" ng-model="updateOrder.stb_supplied_by" name="supplied_by" placeholder="supplied by">
	</div>
	<!--/Manufactured By-->
	
	<!--Manufactured By-->
	<div class="col-xs-3 form-group">
	    <label for="manufactured_by">Manufactured By<input type="checkbox" ng-model="updateOrder.manufacturedBy" ng-click="funCheckCustomerDetail(updateOrder.manufacturedBy,updateOrder.customer_name,'manufactured_by')" ng-checked="updateOrder.stb_manufactured_by.length > 0"></label>
	    <input type="text" class="form-control" id="manufactured_by" ng-model="updateOrder.stb_manufactured_by" name="manufactured_by" placeholder="manufactured by">
	</div>
	<!--/Manufactured By-->
	
	<!--Brand-->
	<div class="col-xs-3 form-group">
	    <label for="brand_type">Brand<em class="asteriskRed">*</em></label>
	    <input
		type="text"
		class="form-control"
		id="brand_type"
		ng-model="updateOrder.stb_brand_type"
		name="brand_type"
		ng-required="true"
		placeholder="Brand Name">
	    <span ng-messages="erpCreateOrderForm.brand_type.$error" ng-if='erpCreateOrderForm.brand_type.$dirty || erpCreateOrderForm.$submitted' role="alert">
		<span ng-message="required" class="error">Brand Name is required</span>
	    </span>
	</div>
	<!--/Brand--> 
    </div>
    
    <div class="row">
    
	<!--Sample Priority-->
	<div class="col-xs-3 form-group">
	    <label for="sample_priority_id">Sample Priority<em class="asteriskRed">*</em></label>
	    <select 
		    class="form-control"
		    name="sample_priority_id"
		    ng-model="updateOrder.sample_priority_id"
		    id="sample_priority_id"
		    ng-required="true"
		    ng-change="funSetSurchargeValue(updateOrder.stb_sample_priority_id.sample_priority_id)"
		    ng-options="samplePriority.sample_priority_name for samplePriority in samplePriorityList track by samplePriority.sample_priority_id">
		<option value="">Select Sample Priority</option>
	    </select>
	    <span ng-messages="erpCreateOrderForm.sample_priority_id.$error" ng-if="updateOrder.sample_priority_id.sample_priority_id == 0 || erpCreateOrderForm.sample_priority_id.$untouched ||erpCreateOrderForm.sample_priority_id.$dirty || erpCreateOrderForm.$submitted" role="alert">
		<span ng-message="required" class="error">Sample Priority is required</span>
	    </span>
	</div>
	<!--/Sample Priority-->
	
	<!--Surcharge Value-->
	<div class="col-xs-3 form-group">
	    <label for="surcharge_value">Surcharge (Rs)<em ng-if="isSetSurchargeValueFlag" class="asteriskRed">*</em></label>
	    <input type="text" ng-if="isSetSurchargeValueFlag" class="form-control" id="surcharge_value" ng-model="updateOrder.stb_surcharge_value" ng-required="true" name="surcharge_value" placeholder="Surcharge Value (Rs)">
	    <input type="text" ng-if="!isSetSurchargeValueFlag" readonly class="form-control" id="surcharge_value" ng-model="updateOrder.stb_surcharge_value" name="surcharge_value" placeholder="Surcharge Value (Rs)">
	    <div ng-if="isSetSurchargeValueFlag">
		<span ng-messages="erpCreateOrderForm.surcharge_value.$error" ng-if="erpCreateOrderForm.surcharge_value.$dirty || erpCreateOrderForm.$submitted" role="alert">
		    <span ng-message="required" class="error">Surcharge is required</span>
		</span>
	    </div>
	</div>
	<!--/Surcharge Value-->
	
	<!--Remarks-->
	<div class="col-xs-3 form-group">
	    <label for="remarks">Remark</label>
	    <input type="text" class="form-control" id="remarks" ng-model="updateOrder.stb_remarks" name="remarks" placeholder="Remarks">
	</div>
	<!--/Remarks-->
	
	<!--PI reference-->
	<div class="col-xs-3 form-group">
	    <label for="pi_reference">PI Reference(if any)</label>
	    <input type="text" class="form-control" id="pi_reference" ng-model="updateOrder.stb_pi_reference" name="pi_reference" placeholder="pi reference">
	</div>
	<!--/PI reference-->    
	
    </div>

    <div class="row">
    
	<!--Sealed/Unsealed -->
	<div class="col-xs-3 form-group">
	    <label for="is_sealed">Sealed/Unsealed<em class="asteriskRed">*</em></label>
	    <select class="form-control"
		name="is_sealed"
		id="is_sealed"
		ng-model="updateOrder.is_sealed"
		ng-options="sealedUnsealed.name for sealedUnsealed in sealedUnsealedList track by sealedUnsealed.id"
		ng-required="true">
		<option value="">Select Sealed/Unsealed</option>
	    </select>
	    <span ng-messages="erpCreateOrderForm.is_sealed.$error" ng-if="erpCreateOrderForm.is_sealed.$dirty || erpCreateOrderForm.$submitted" role="alert">
		<span ng-message="required" class="error">Sealed/Unsealed is required</span>
	    </span>
	</div>
	<!--/Sealed/Unsealed -->
	
	<!--Signed/Unsigned -->
	<div class="col-xs-3 form-group">
	    <label for="is_signed">Signed/Unsigned<em class="asteriskRed">*</em></label>
	    <select class="form-control"
		name="is_signed"
		id="is_signed"
		ng-model="updateOrder.is_signed"
		ng-options="signedUnsigned.name for signedUnsigned in signedUnsignedList track by signedUnsigned.id"
		ng-required="true">
		<option value="">Select Signed/Unsigned</option>
	    </select>
	    <span ng-messages="erpCreateOrderForm.is_signed.$error" ng-if="erpCreateOrderForm.is_signed.$dirty || erpCreateOrderForm.$submitted" role="alert">
		<span ng-message="required" class="error">Signed/Unsigned is required</span>
	    </span>
	</div>
	<!--/Signed/Unsigned -->
	
	<!--Packing Mode-->
	<div class="col-xs-3 form-group">
	    <label for="packing_mode">Packing Mode<em class="asteriskRed">*</em></label>
	    <input type="text" class="form-control" id="packing_mode" ng-model="updateOrder.stb_packing_mode" ng-required="true" name="packing_mode" placeholder="Packing Mode">
	    <span ng-messages="erpCreateOrderForm.packing_mode.$error" ng-if="erpCreateOrderForm.packing_mode.$dirty || erpCreateOrderForm.$submitted" role="alert">
		<span ng-message="required" class="error">Packing Mode is required</span>
	    </span>
	</div>
	<!--/Packing Mode-->
    
	<!-- Sampling date.-->
	<div class="col-xs-3 form-group">
	    <label for="sampling_date">Sampling Date</label>
	    <div class="input-group date">
		<input
		    type="text" 
		    class="form-control bgWhite"
		    id="sampling_date_add" 
		    ng-model="updateOrder.stb_sampling_date"
		    name="sampling_date" 
		    placeholder="Sampling Date"/>
	      <div class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></div>
	    </div>
	</div>
	<!-- /Sampling date.-->
	     
    </div>
    
    <div class="row">
    
	<!--Submission Type -->
	<div class="col-xs-3 form-group">
	    <label for="submission_type">Submission Type <em class="asteriskRed">*</em></label> 
	    <select
		class="form-control"
		ng-required="true"
		ng-model="updateOrder.submission_type"
		ng-change="funSubmissionTypeValue(updateOrder.submission_type.id)"
		name="submission_type"
		id="submission_type" 
		ng-options="submissionType.name for submissionType in submissionTypeList track by submissionType.id">
	    </select>
	    <span ng-messages="erpCreateOrderForm.submission_type.$error" ng-if="erpCreateOrderForm.submission_type.$dirty || erpCreateOrderForm.$submitted" role="alert">
		<span ng-message="required" class="error">Submission type is required</span>
	    </span>		
	</div>
	<!--/Submission Type -->
	
	<!--Advance Value-->
	<div class="col-xs-3 form-group" ng-if="advanceDetailsDisplay">
	    <label for="advance_details">Advance Details<em class="asteriskRed">*</em></label>
	    <textarea rows="1" ng-required="true" class="form-control" id="advance_details" ng-model="updateOrder.stb_advance_details" name="advance_details" placeholder="Advance Details"></textarea>
	    
	    <span ng-messages="erpCreateOrderForm.advance_details.$error" ng-if="erpCreateOrderForm.advance_details.$dirty || erpCreateOrderForm.$submitted" role="alert">
		<span ng-message="required" class="error">Advance Details is required</span>
	    </span>	
	</div>
	<!--/Advance Value-->
    
	<!--Quotation No.-->
	<div class="col-xs-3 form-group">
	    <label for="quotation_no">Quotation No.</label>
	    <input type="text" class="form-control" id="quotation_no" ng-model="updateOrder.stb_quotation_no" name="quotation_no" placeholder="Quotation No.">
	</div>
	<!--/Quotation No.-->
    	
	<!--Actual Submission Type-->
	<div class="col-xs-3 form-group">
	    <label for="actual_submission_type">Actual Submission Type</label>
	    <input type="text" class="form-control" id="actual_submission_type" ng-model="updateOrder.stb_actual_submission_type" name="actual_submission_type" placeholder="Actual Submission Type">
	</div>
	<!--/Actual Submission Type-->
	
	<!--convenience_charge-->
    	<div class="col-xs-3 form-group">
    	    <label for="extra_amount">Extra Amount</label>
    	    <input type="text" class="form-control" id="extra_amount" ng-model="updateOrder.stb_extra_amount" name="extra_amount" placeholder="Extra Amount">
    	</div>
	<!--/convenience_charge-->
    </div>
</div>