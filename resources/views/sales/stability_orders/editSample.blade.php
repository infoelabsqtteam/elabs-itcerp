<div class="row order-section fontbd" style="margin-top:10px!important;">Sample Detail </div>
<div class="order_detail">  
    <div class="row mT10">
    
	<!--Branch-->
	<div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
	    <label for="stb_division_id">Branch<em class="asteriskRed">*</em></label>
	    <select class="form-control"
		name="stb_division_id"
		id="stb_division_id"
		ng-model="updateStabilityOrder.stb_division_id.selectedOption"
		ng-required="true"
		ng-options="division.name for division in divisionsCodeList track by division.id">
		<option value="">Select Branch</option>
	    </select>
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_division_id.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_division_id.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
		<span ng-message="required" class="error">Branch is required</span>
	    </span>
	</div>
	<div ng-if="{{$division_id}} > 0">
	    <input type="hidden" id="stb_division_id" ng-model="updateStabilityOrder.stb_edit_division_id" name="stb_division_id" ng-value="updateStabilityOrder.stb_edit_division_id">
	</div>
	<!--/Branch-->
    
	<!--Prototype date-->
	<div class="col-xs-3 form-group">
	    <label for="stb_prototype_date">Prototype Date<em class="asteriskRed">*</em></label>
	    <div class="input-group">
		<input
		    disabled="true"
		    type="text"
		    id="stb_prototype_date"
		    ng-model="updateStabilityOrder.stb_prototype_date"
		    valid-date
		    name="stb_prototype_date"
		    class="form-control"
		    placeholder="Order Date">
		<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
	    </div>
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_prototype_date.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_prototype_date.$dirty  || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
		<span ng-message="required" class="error">Prototype date is required</span>
	    </span>
	</div>
	<!--/Prototype date-->
    
	<!--Reference No-->
	<div class="col-xs-3 form-group">
	    <label for="stb_reference_no">Letter Reference No.</label>
	    <input
		type="text"
		class="form-control"
		id="stb_reference_no"
		ng-model="updateStabilityOrder.stb_reference_no"
		name="stb_reference_no"
		placeholder="Letter Reference No.">
	</div>
	<!--/Reference No-->
	
	<!--Letter No-->
	<div class="col-xs-3 form-group">
	    <label for="stb_letter_no">Letter Reference Date</label>
	    <input
		type="text"
		class="form-control"
		id="stb_letter_no"
		ng-model="updateStabilityOrder.stb_letter_no"
		name="stb_letter_no"
		placeholder="Letter Reference Date">
	</div>
	<!--/Letter No-->
	
	<!--Sample Description-->
	<div class="col-xs-3 form-group">
	    <label for="stb_sample_description_id">Sample Name<em class="asteriskRed">*</em></label>
	    <a href="javascript:;" class="closeAlert mT5" ng-if="closeAutoSearchList && !addedStabilityOrderPrototypeList.length" ng-click="funCloseAutoSearchList()"><i class="fa fa-close"></i></a>
	    <input
		ng-if="addedStabilityOrderPrototypeList.length"
		readonly
		class="form-control"
		name="stb_sample_description_name" 			
		ng-model="updateStabilityOrder.stb_sample_description_name"
		id="stb_sample_description_name"
		ng-change="getAutoSearchSampleMatches(updateStabilityOrder.stb_sample_description_name,stbSampleID);"
		placeholder="Sample Name"
		ng-required='true'
		autocomplete="off">
	    <input
		ng-if="!addedStabilityOrderPrototypeList.length"
		class="form-control"
		name="stb_sample_description_name" 			
		ng-model="updateStabilityOrder.stb_sample_description_name"
		id="stb_sample_description_name"
		ng-change="getAutoSearchSampleMatches(updateStabilityOrder.stb_sample_description_name,stbSampleID);"
		placeholder="Sample Name"
		ng-required='true'
		autocomplete="off">
	    <input type="hidden" name="stb_product_id" ng-model="updateStabilityOrder.stb_product_id" ng-value="updateStabilityOrder.stb_product_id" id="stb_product_id">
	    <input type="hidden" name="stb_sample_description_id" ng-model="updateStabilityOrder.stb_sample_description_id" ng-value="updateStabilityOrder.stb_sample_description_id" id="stb_sample_description_id">
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_sample_description_name.$error" ng-if='erpUpdateStabilityOrderCSForm.stb_sample_description_name.$dirty || erpUpdateStabilityOrderCSForm.$submitted' role="alert">
		<span ng-message="required" class="error">Sample Name is required</span>
	    </span>
	    <ul ng-if="showAutoSearchList" class="autoSearch">
		<li ng-if="resultItems.length" ng-repeat="sampleObj in resultItems" ng-click="funSetSelectedSampleStabilityOrder(sampleObj.id,sampleObj.name,sampleObj.product_id,'edit');">[[sampleObj.name]]</li>
		<li ng-if="!resultItems.length">No Record Found!</li>
	    </ul>
	</div>
	<!--/Sample Description-->
	
    </div>
	
    <div class="row">
	
	<!--Product Description-->
	<div class="col-xs-3 form-group">
	    <label for="stb_product_description">Product Description</label>
	    <textarea
		rows="1"
		class="form-control description"
		id="stb_product_description"
		ng-model="updateStabilityOrder.stb_product_description"
		name="stb_product_description"
		placeholder="Product Description">
	    </textarea>
	</div>
	<!--/Product Description-->
	
	<!--Sample Pack Code-->
	<div class="col-xs-3 form-group">
	    <label for="stb_sample_pack_code">Sample Pack Code</label>
	    <input
		type="text"
		class="form-control"
		id="stb_sample_pack_code"
		ng-model="updateStabilityOrder.stb_sample_pack_code"
		name="stb_sample_pack_code"
		placeholder="Sample Pack Code">
	</div>
	<!--/Sample Pack Code-->
	
	<!--Sample Pack-->
	<div class="col-xs-3 form-group">
	    <label for="stb_sample_pack">Sample Pack</label>
	    <input
		type="text"
		class="form-control"
		id="stb_sample_pack"
		ng-model="updateStabilityOrder.stb_sample_pack"
		name="stb_sample_pack"
		placeholder="Sample Pack">
	</div>
	<!--/Sample Pack-->
	
	<!--Storage Condition Sample Pack-->
	<div class="col-xs-3 form-group">
	    <label for="stb_storage_cond_sample_pack">Storage Condition Sample Pack</label>
	    <input
		type="text"
		class="form-control"
		id="stb_storage_cond_sample_pack"
		ng-model="updateStabilityOrder.stb_storage_cond_sample_pack"
		name="stb_storage_cond_sample_pack"
		placeholder="Storage Condition Sample Pack">
	</div>
	<!--/Storage Condition Sample Pack-->
    
    </div>
	
    <div class="row">
    
	<!--Orientation-->
	<div class="col-xs-3 form-group">
	    <label for="stb_orientation">Orientation</label>
	    <input
		type="text"
		class="form-control"
		id="stb_orientation"
		ng-model="updateStabilityOrder.stb_orientation"
		name="stb_orientation"
		placeholder="Orientation">
	</div>
	<!--/Orientation-->
    
    </div>

    <div class="row">
	
	<!--batch number-->
	<div class="col-xs-3 form-group">
	    <label for="stb_batch_no">Batch No.<em class="asteriskRed">*</em></label>
	    <input
		type="text"
		class="form-control"
		id="stb_batch_no"
		ng-model="updateStabilityOrder.stb_batch_no"
		ng-required="true"
		name="stb_batch_no"
		ng-blur="funGetSearchSampleMatcheRate(updateStabilityOrder.stb_sample_description_id,customerInvoicingTypeID,stbSampleID,'add');"
		placeholder="batch no">
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_batch_no.$error" ng-if='erpUpdateStabilityOrderCSForm.stb_batch_no.$dirty || erpUpdateStabilityOrderCSForm.$submitted' role="alert">
		<span ng-message="required" class="error">Batch No. is required</span>
	    </span>
	</div>
	<!-- /batch number-->
	
	<!--Sample Qty-->
	<div class="col-xs-3 form-group">
	    <label for="stb_sample_qty">Sample Qty.<em class="asteriskRed">*</em></label>
	    <input
		type="text"
		valid-number
		class="form-control"
		id="stb_sample_qty"
		ng-required="true"
		ng-model="updateStabilityOrder.stb_sample_qty"
		name="stb_sample_qty"
		placeholder="sample qty">
	    <input type="hidden" name="stb_sample_qty_prev" ng-model="updateStabilityOrder.stb_sample_qty_prev" ng-value="updateStabilityOrder.stb_sample_qty_prev" id="stb_sample_qty_prev">
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_sample_qty.$error" ng-if='erpUpdateStabilityOrderCSForm.stb_sample_qty.$dirty || erpUpdateStabilityOrderCSForm.$submitted' role="alert">
		<span ng-message="required" class="error">Sample Qty. is required</span>
	    </span>
	</div>
	<!--/Sample Qty-->
	
	<!--Sample Qty Unit-->
	<div class="col-xs-3 form-group">
	    <label for="stb_sample_qty_unit">Sample Qty. Unit<em class="asteriskRed">*</em></label>
	    <input
		type="text"
		valid-alphabet
		class="form-control"
		id="stb_sample_qty_unit"
		ng-required="true"
		ng-model="updateStabilityOrder.stb_sample_qty_unit"
		name="stb_sample_qty_unit"
		placeholder="sample qty unit">
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_sample_qty_unit.$error" ng-if='erpUpdateStabilityOrderCSForm.stb_sample_qty_unit.$dirty || erpUpdateStabilityOrderCSForm.$submitted' role="alert">
		<span ng-message="required" class="error">Sample Qty. Unit is required</span>
	    </span>
	</div>
	<!--/Sample Qty Unit-->
	
	<!-- Date of manufacturing.-->
	<div class="col-xs-3 form-group">
	    <label for="stb_mfg_date">Date of Mfg.</label>
	    <input
		type="text"
		id="stb_mfg_date"
		ng-model="updateStabilityOrder.stb_mfg_date"
		name="stb_mfg_date"
		class="form-control bgWhite"
		placeholder="Date of Mfg.">
	</div>
	<!-- Date of manufacturing.-->
	
	<!--Date of Expiry-->
	<div class="col-xs-3 form-group">
	    <label for="stb_expiry_date">Date of Expiry</label>
	    <input
		type="text"
		id="stb_expiry_date"
		name="stb_expiry_date"
		ng-model="updateStabilityOrder.stb_expiry_date"
		class="form-control bgWhite"
		placeholder="Date of Expiry">
	</div>
	<!--/Date of Expiry-->
	
    </div>
    
    <div class="row">
	
	<!--Batch Size-->
	<div class="col-xs-3 form-group">
	    <label for="stb_batch_size">Batch Size</label>
	    <input
		type="text"
		class="form-control"
		id="stb_batch_size"
		ng-model="updateStabilityOrder.stb_batch_size"
		name="stb_batch_size"
		placeholder="batch size">
	</div>
	<!--/Batch Size-->
	
	<!--Manufactured By-->
	<div class="col-xs-3 form-group">
	    <label for="stb_manufactured_by">Supplied By<input type="checkbox" ng-model="updateStabilityOrder.suppliedBy" ng-click="funCheckEditCustomer(updateStabilityOrder.suppliedBy,updateStabilityOrder.stb_customer_name,'stb_supplied_by')" ng-checked="updateStabilityOrderDetail.customer_name.length"></label>
	    <input
		type="text"
		class="form-control"
		id="stb_supplied_by"
		ng-model="updateStabilityOrder.stb_supplied_by"
		name="stb_supplied_by"
		placeholder="supplied by">
	</div>
	<!--/Manufactured By-->
	
	<!--Manufactured By-->
	<div class="col-xs-3 form-group">
	    <label for="stb_manufactured_by">Manufactured By<input type="checkbox" ng-model="updateStabilityOrder.manufacturedBy" ng-click="funCheckEditCustomer(updateStabilityOrder.manufacturedBy,updateStabilityOrder.stb_customer_name,'stb_manufactured_by')" ng-checked="updateStabilityOrderDetail.customer_name.length"></label>
	    <input
		type="text"
		class="form-control"
		id="stb_manufactured_by"
		ng-model="updateStabilityOrder.stb_manufactured_by"
		name="stb_manufactured_by"
		placeholder="manufactured by">
	</div>
	<!--/Manufactured By-->
	
	<!--Brand-->
	<div class="col-xs-3 form-group">
	    <label for="stb_brand_type">Brand<em class="asteriskRed">*</em></label>
	    <input
		type="text"
		class="form-control"
		id="stb_brand_type"
		ng-model="updateStabilityOrder.stb_brand_type"
		name="stb_brand_type"
		ng-required="true"
		placeholder="Brand Name">
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_brand_type.$error" ng-if='erpUpdateStabilityOrderCSForm.stb_brand_type.$dirty || erpUpdateStabilityOrderCSForm.$submitted' role="alert">
		<span ng-message="required" class="error">Brand Name is required</span>
	    </span>
	</div>
	<!--/Brand--> 
    </div>
    
    <div class="row">
    
	<!--Sample Priority-->
	<div class="col-xs-3 form-group">
	    <label for="stb_sample_priority_id">Sample Priority<em class="asteriskRed">*</em></label>
	    <select 
		class="form-control"
		name="stb_sample_priority_id"
		ng-model="updateStabilityOrder.stb_sample_priority_id.selectedOption"
		id="stb_sample_priority_id"
		ng-required="true"
		ng-change="funEditSetSurchargeValue(updateStabilityOrder.stb_sample_priority_id.selectedOption.sample_priority_id)"
		ng-options="samplePriority.sample_priority_name for samplePriority in samplePrioritySetOnEditList track by samplePriority.sample_priority_id">
		<option value="">Select Sample Priority</option>
	    </select>
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_sample_priority_id.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_sample_priority_id.$untouched ||erpUpdateStabilityOrderCSForm.stb_sample_priority_id.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
		<span ng-message="required" class="error">Sample Priority is required</span>
	    </span>
	</div>
	<!--/Sample Priority-->
	
	<!--Surcharge Value-->
	<div class="col-xs-3 form-group">
	    <label for="stb_surcharge_value">Surcharge (Rs)<em ng-if="isEditSetSurchargeValueFlag" class="asteriskRed">*</em></label>
	    <input type="text" ng-if="isEditSetSurchargeValueFlag" class="form-control" id="stb_surcharge_value" ng-model="updateStabilityOrder.stb_surcharge_value" ng-required="true" name="stb_surcharge_value" placeholder="Surcharge Value (Rs)">
	    <input type="text" ng-if="!isEditSetSurchargeValueFlag" readonly class="form-control" id="stb_surcharge_value" ng-model="updateStabilityOrder.stb_surcharge_value" name="stb_surcharge_value" placeholder="Surcharge Value (Rs)">
	    <div ng-if="isEditSetSurchargeValueFlag">
		<span ng-messages="erpUpdateStabilityOrderCSForm.stb_surcharge_value.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_surcharge_value.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
		    <span ng-message="required" class="error">Surcharge is required</span>
		</span>
	    </div>
	</div>
	<!--/Surcharge Value-->
	
	<!--Remarks-->
	<div class="col-xs-3 form-group">
	    <label for="stb_remarks">Remark</label>
	    <input type="text" class="form-control" id="stb_remarks" ng-model="updateStabilityOrder.stb_remarks" name="stb_remarks" placeholder="Remarks">
	</div>
	<!--/Remarks-->
	
	<!--PI reference-->
	<div class="col-xs-3 form-group">
	    <label for="stb_pi_reference">PI Reference(if any)</label>
	    <input type="text" class="form-control" id="stb_pi_reference" ng-model="updateStabilityOrder.stb_pi_reference" name="stb_pi_reference" placeholder="pi reference">
	</div>
	<!--/PI reference-->    
	
    </div>

    <div class="row">
    
	<!--Sealed/Unsealed -->
	<div class="col-xs-3 form-group">
	    <label for="stb_is_sealed">Sealed/Unsealed<em class="asteriskRed">*</em></label>
	    <select class="form-control"
		name="stb_is_sealed"
		id="stb_is_sealed"
		ng-model="updateStabilityOrder.stb_is_sealed.selectedOption"
		ng-options="sealedUnsealed.name for sealedUnsealed in sealedUnsealedList track by sealedUnsealed.id"
		ng-required="true">
		<option value="">Select Sealed/Unsealed</option>
	    </select>
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_is_sealed.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_is_sealed.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
		<span ng-message="required" class="error">Sealed/Unsealed is required</span>
	    </span>
	</div>
	<!--/Sealed/Unsealed -->
	
	<!--Signed/Unsigned -->
	<div class="col-xs-3 form-group">
	    <label for="stb_is_signed">Signed/Unsigned<em class="asteriskRed">*</em></label>
	    <select class="form-control"
		name="stb_is_signed"
		id="stb_is_signed"
		ng-model="updateStabilityOrder.stb_is_signed.selectedOption"
		ng-options="signedUnsigned.name for signedUnsigned in signedUnsignedList track by signedUnsigned.id"
		ng-required="true">
		<option value="">Select Signed/Unsigned</option>
	    </select>
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_is_signed.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_is_signed.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
		<span ng-message="required" class="error">Signed/Unsigned is required</span>
	    </span>
	</div>
	<!--/Signed/Unsigned -->
	
	<!--Packing Mode-->
	<div class="col-xs-3 form-group">
	    <label for="stb_packing_mode">Packing Mode<em class="asteriskRed">*</em></label>
	    <input
		type="text"
		class="form-control"
		id="stb_packing_mode"
		ng-model="updateStabilityOrder.stb_packing_mode"
		ng-required="true"
		name="stb_packing_mode"
		placeholder="Packing Mode">
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_packing_mode.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_packing_mode.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
		<span ng-message="required" class="error">Packing Mode is required</span>
	    </span>
	</div>
	<!--/Packing Mode-->
    
	<!-- Sampling date.-->
	<div class="col-xs-3 form-group">
	    <label for="stb_sampling_date">Sampling Date</label>
	    <div class="input-group date">
		<input
		    type="text" 
		    class="form-control bgWhite stb_sampling_datepicker"
		    id="stb_sampling_date" 
		    ng-model="updateStabilityOrder.stb_sampling_date"
		    name="stb_sampling_date"
		    placeholder="Sampling Date"/>
	      <div class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></div>
	    </div>
	</div>
	<!-- /Sampling date.-->
	     
    </div>
    
    <div class="row">
    
	<!--Submission Type -->
	<div class="col-xs-3 form-group">
	    <label for="stb_submission_type">Submission Type <em class="asteriskRed">*</em></label> 
	    <select
		class="form-control"
		name="stb_submission_type"
		ng-model="updateStabilityOrder.stb_submission_type.selectedOption"
		id="stb_submission_type" 
		ng-options="submissionType.name for submissionType in submissionTypeList track by submissionType.id"
		ng-change="funEditSubmissionTypeValue(updateStabilityOrder.stb_submission_type.selectedOption.id)"
		ng-required="true">
		<option value="">Select Submission Type</option> 
	    </select>
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_submission_type.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_submission_type.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
		<span ng-message="required" class="error">Submission type is required</span>
	    </span>		
	</div>
	<!--/Submission Type -->
	
	<!--Advance Value-->
	<div class="col-xs-3 form-group" ng-if="editAdvanceDetailsDisplay">
	    <label for="stb_advance_details">Advance Details<em class="asteriskRed">*</em></label>
	    <textarea rows="1" ng-if="editAdvanceDetailsDisplay" ng-required="true" class="form-control" id="stb_advance_details" ng-model="updateStabilityOrder.stb_advance_details" name="stb_advance_details" placeholder="Advance Details"></textarea>
	    <span ng-messages="erpUpdateStabilityOrderCSForm.stb_advance_details.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_advance_details.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
		<span ng-message="required" class="error">Advance Details is required</span>
	    </span>	
	</div>
	<!--/Advance Value-->
    
	<!--Quotation No.-->
	<div class="col-xs-3 form-group">
	    <label for="stb_quotation_no">Quotation No.</label>
	    <input type="text" class="form-control" id="stb_quotation_no" ng-model="updateStabilityOrder.stb_quotation_no" name="stb_quotation_no" placeholder="Quotation No.">
	</div>
	<!--/Quotation No.-->
    	
	<!--Actual Submission Type-->
	<div class="col-xs-3 form-group">
	    <label for="stb_actual_submission_type">Actual Submission Type</label>
	    <input type="text" class="form-control" id="stb_actual_submission_type" ng-model="updateStabilityOrder.stb_actual_submission_type" name="stb_actual_submission_type" placeholder="Actual Submission Type">
	</div>
	<!--/Actual Submission Type-->
	
	<!--convenience_charge-->
    	<div class="col-xs-3 form-group">
    	    <label for="stb_extra_amount">Extra Amount</label>
    	    <input type="text" class="form-control" id="stb_extra_amount" ng-model="updateStabilityOrder.stb_extra_amount" name="stb_extra_amount" placeholder="Extra Amount">
    	</div>
	<!--/convenience_charge-->
    </div>
</div>