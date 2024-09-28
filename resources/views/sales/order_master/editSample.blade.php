<div class="row order-section" style="margin-top:10px!important;">Sample Detail</div>
<div class="row mT10">
    <!--Branch -->
    <div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
        <select class="form-control" name="division_id" id="division_id" ng-model="updateOrder.division_id.selectedOption" ng-required="true" ng-options="division.name for division in divisionsCodeList track by division.id">
            <option value="">Select Branch</option>
        </select>
        <span ng-messages="erpUpdateOrderForm.division_id.$error" ng-if="erpUpdateOrderForm.division_id.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Branch is required</span>
        </span>
    </div>
    <div ng-if="{{$division_id}} > 0">
        <input type="hidden" id="division_id" ng-model="order.division_id" name="division_id" value="{{$division_id}}">
    </div>
    <!--/Branch -->

    <!-- Order date.-->
    <div class="col-xs-3 form-group">
        <label for="order_date">Order Date</label>
        <input readonly type="text" id="order_date" ng-model="updateOrder.order_date" ng-required="true" name="order_date" class="form-control" placeholder="Order Date">
    </div>
    <!-- /Order date.-->

    <!--Reference No-->
    <div class="col-xs-3 form-group">
        <label for="reference_no">Letter Reference No.</label>
        <input type="text" class="form-control" id="reference_no" ng-model="updateOrder.reference_no" name="reference_no" placeholder="Letter Reference No.">
        <span ng-messages="erpUpdateOrderForm.reference_no.$error" ng-if='erpUpdateOrderForm.reference_no.$dirty  || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Letter Reference No. is required</span>
        </span>
    </div>
    <!--/Reference No-->

    <!--Letter No-->
    <div class="col-xs-3 form-group">
        <label for="letter_no">Letter Reference Date</label>
        <input type="text" class="form-control" id="letter_no" ng-model="updateOrder.letter_no" name="letter_no" placeholder="Letter Reference Date">
        <span ng-messages="erpUpdateOrderForm.letter_no.$error" ng-if='erpUpdateOrderForm.letter_no.$dirty || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Letter Reference Date is required</span>
        </span>
    </div>
    <!--/Letter No-->

    <!--Sample Description-->
    <div class="col-xs-3 form-group">
        <label for="sample_description">Sample Name<em class="asteriskRed">*</em></label>
        <a href="javascript:;" class="closeAlert mT5" ng-if="closeAutoSearchList" ng-click="funCloseAutoSearchList()"><i class="fa fa-close"></i></a>
        <input class="form-control" name="sample_description" ng-model="updateOrder.sample_description" id="edit_sample_description" ng-change="getAutoSearchSampleMatches(updateOrder.sample_description,sampleID);" placeholder="Sample Name" ng-required='true' autocomplete="off">
        <span ng-messages="erpUpdateOrderForm.sample_description.$error" ng-if='erpUpdateOrderForm.sample_description.$dirty || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Sample Name is required</span>
        </span>
        <ul ng-if="showAutoSearchList" class="autoSearch">
            <li ng-if="resultItems.length" ng-repeat="sampleObj in resultItems" ng-click="funsetSelectedSample(sampleObj.id,sampleObj.name,'edit');">[[sampleObj.name]]</li>
            <li ng-if="!resultItems.length">No Record Found!</li>
        </ul>
    </div>
    <!--/Sample Description-->
</div>

<div class="row">

    <!--batch number-->
    <div class="col-xs-3 form-group">
        <label for="batch_no">Batch No.<em class="asteriskRed">*</em></label>
        <input type="text" class="form-control" id="batch_no" ng-model="updateOrder.batch_no" ng-blur="funGetSearchSampleMatcheRate(updateOrder.sample_description,customerInvoicingTypeID,sampleID,'edit');" ng-required="true" name="batch_no" placeholder="batch no">
        <span ng-messages="erpUpdateOrderForm.batch_no.$error" ng-if='erpUpdateOrderForm.batch_no.$dirty || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Batch No. is required</span>
        </span>
    </div>
    <!-- /batch number-->

    <!-- Date of manufacturing.-->
    <div class="col-xs-3 form-group">
        <label for="mfg_date">Date of Mfg.</label>
        <input type="text" id="mfg_date" ng-model="updateOrder.mfg_date" name="mfg_date" class="form-control bgWhite" placeholder="Date of Mfg.">
    </div>
    <!-- Date of manufacturing.-->

    <!--Date of Expiry-->
    <div class="col-xs-3 form-group">
        <label for="expiry_date">Date of Expiry</label>
        <input type="text" id="expiry_date" name="expiry_date" ng-model="updateOrder.expiry_date" class="form-control bgWhite" placeholder="Date of Expiry">
    </div>
    <!--/Date of Expiry-->

    <!--Batch Size-->
    <div class="col-xs-3 form-group">
        <label for="batch_size">Batch Size</label>
        <input type="text" class="form-control" id="batch_size" ng-model="updateOrder.batch_size" name="batch_size" placeholder="batch size">
    </div>
    <!--/Batch Size-->

</div>

<div class="row">

    <!--Sample Qty-->
    <div class="col-xs-3 form-group">
        <label for="sample_qty">Sample Qty.</label>
        <input type="text" class="form-control" id="sample_qty" ng-model="updateOrder.sample_qty" name="sample_qty" placeholder="sample qty">
    </div>
    <!--/Sample Qty-->

    <!--Manufactured By-->
    <div class="col-xs-3 form-group">
        <label for="manufactured_by">Supplied By<input type="checkbox" ng-model="updateOrder.suppliedBy" ng-click="checkEditCustomer(updateOrder.suppliedBy,updateOrder.customer_name,'supplied_by')" ng-checked="updateOrder.supplied_by.length"></label>
        <input type="text" class="form-control" id="supplied_by" ng-model="updateOrder.supplied_by" name="supplied_by" placeholder="supplied by">
    </div>
    <!--/Manufactured By-->

    <!--Manufactured By-->
    <div class="col-xs-3 form-group">
        <label for="manufactured_by">Manufactured By <input type="checkbox" ng-model="updateOrder.manufacturedBy" ng-click="checkEditCustomer(updateOrder.manufacturedBy,updateOrder.customer_name,'manufactured_by')" ng-checked="updateOrder.manufactured_by.length"></label>
        <input type="text" class="form-control" id="manufactured_by" ng-model="updateOrder.manufactured_by" name="manufactured_by" placeholder="manufactured by">
    </div>
    <!--/Manufactured By-->

    <!--PI reference-->
    <div class="col-xs-3 form-group">
        <label for="pi_reference">PI Reference(if any)</label>
        <input type="text" class="form-control" id="pi_reference" ng-model="updateOrder.pi_reference" name="pi_reference" placeholder="pi reference">
    </div>
    <!--/PI reference-->

</div>

<div class="row">

    <!--Sample Priority-->
    <div class="col-xs-3 form-group">
        <label for="sample_priority_id">Sample Priority<em class="asteriskRed">*</em></label>
        <select ng-if="orderEditSamplePriorityId == 4" class="form-control" readonly name="sample_priority_id" ng-model="updateOrder.sample_priority_id.selectedOption" id="sample_priority_id" ng-required="true" ng-options="samplePriority.sample_priority_name for samplePriority in samplePriorityCRMList track by samplePriority.sample_priority_id">
            <option value="">Select Sample Priority</option>
        </select>
        <select ng-if="orderEditSamplePriorityId < 4" class="form-control" name="sample_priority_id" ng-model="updateOrder.sample_priority_id.selectedOption" id="sample_priority_id" ng-required="true" ng-change="funEditSetSurchargeValue(updateOrder.sample_priority_id.selectedOption.sample_priority_id)" ng-options="samplePriority.sample_priority_name for samplePriority in samplePriorityList track by samplePriority.sample_priority_id">
            <option value="">Select Sample Priority</option>
        </select>
        <span ng-messages="erpUpdateOrderForm.sample_priority_id.$error" ng-if="erpUpdateOrderForm.sample_priority_id.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Sample Priority is required</span>
        </span>
    </div>
    <!--/Sample Priority-->

    <!--Surcharge Value-->
    <div class="col-xs-3 form-group">
        <label for="surcharge_value">Surcharge (Rs)<em ng-if="isEditSetSurchargeValueFlag" class="asteriskRed">*</em></label>
        <input type="text" ng-if="isEditSetSurchargeValueFlag" class="form-control" id="surcharge_value" ng-model="updateOrder.surcharge_value" ng-required="true" name="surcharge_value" placeholder="Surcharge Value (Rs)">
        <input type="text" ng-if="!isEditSetSurchargeValueFlag" readonly class="form-control" id="surcharge_value" ng-model="order.surcharge_value" name="surcharge_value" placeholder="Surcharge Value (Rs)">
        <div ng-if="isEditSetSurchargeValueFlag">
            <span ng-messages="erpUpdateOrderForm.surcharge_value.$error" ng-if="erpUpdateOrderForm.surcharge_value.$dirty || erpUpdateOrderForm.$submitted" role="alert">
                <span ng-message="required" class="error">Surcharge is required</span>
            </span>
        </div>
    </div>
    <!--/Surcharge Value-->

    <!--Brand-->
    <div class="col-xs-3 form-group">
        <label for="brand_type">Brand<em class="asteriskRed">*</em></label>
        <input type="text" class="form-control" id="brand_type" ng-model="updateOrder.brand_type" name="brand_type" ng-required="true" placeholder="Brand">
        <span ng-messages="erpUpdateOrderForm.brand_type.$error" ng-if='erpUpdateOrderForm.brand_type.$dirty || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Brand Name is required</span>
        </span>
    </div>
    <!--/Brand-->

    <!--Remarks-->
    <div class="col-xs-3 form-group">
        <label for="remarks">Remark</label>
        <input type="text" class="form-control" id="remarks" ng-model="updateOrder.remarks" name="remarks" placeholder="Remarks">
    </div>
    <!--/Remarks-->

</div>

<div class="row">

    <!--Sealed/Unsealed -->
    <div class="col-xs-3 form-group">
        <label for="is_sealed">Sealed/Unsealed<em class="asteriskRed">*</em></label>
        <select class="form-control" ng-required="true" name="is_sealed" id="is_sealed" ng-options="sealedUnsealed.name for sealedUnsealed in sealedUnsealedList track by sealedUnsealed.id" ng-model="updateOrder.is_sealed.selectedOption">
        </select>
        <span ng-messages="erpUpdateOrderForm.is_sealed.$error" ng-if="erpUpdateOrderForm.is_sealed.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Sealed/Unsealed is required</span>
        </span>
    </div>
    <!--/Sealed/Unsealed -->

    <!--Signed/Unsigned -->
    <div class="col-xs-3 form-group">
        <label for="is_signed">Signed/Unsigned<em class="asteriskRed">*</em></label>
        <select class="form-control" ng-required="true" name="is_signed" id="is_signed" ng-options="signedUnsigned.name for signedUnsigned in signedUnsignedList track by signedUnsigned.id" ng-model="updateOrder.is_signed.selectedOption">
        </select>
        <span ng-messages="erpUpdateOrderForm.is_signed.$error" ng-if="erpUpdateOrderForm.is_signed.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Signed/Unsigned is required</span>
        </span>
    </div>
    <!--/Signed/Unsigned -->

    <!--Packing Mode-->
    <div class="col-xs-3 form-group">
        <label for="packing_mode">Packing Mode<em class="asteriskRed">*</em></label>
        <input type="text" class="form-control" id="packing_mode" ng-model="updateOrder.packing_mode" ng-required="true" name="packing_mode" placeholder="Packing Mode">
        <span ng-messages="erpUpdateOrderForm.packing_mode.$error" ng-if="erpUpdateOrderForm.packing_mode.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Packing Mode is required</span>
        </span>
    </div>
    <!--/Packing Mode-->

    <!-- Sampling date.-->
    <div class="col-xs-3 form-group">
        <label for="sampling_date">Sampling Date</label>
        <div class="input-group date">
            <input type="text" class="form-control bgWhite" id="sampling_date_edit" ng-model="updateOrder.sampling_date" name="sampling_date" placeholder="Sampling Date">
            <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
        </div>
    </div>
    <!-- /Sampling date.-->

</div>
<div class="row">

    <!--Submission Type -->
    <div class="col-xs-3 form-group">
        <label for="submission_type">Submission Type <em class="asteriskRed">*</em></label>
        <select class="form-control" ng-required="true" ng-change="funEditSubmissionTypeValue(updateOrder.submission_type.selectedOption.id)" name="submission_type" id="submission_type" ng-options="submissionType.name for submissionType in submissionTypeList track by submissionType.id" ng-model="updateOrder.submission_type.selectedOption">
        </select>
        <span ng-messages="erpUpdateOrderForm.submission_type.$error" ng-if="erpUpdateOrderForm.submission_type.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Submission type is required</span>
        </span>
    </div>
    <!--/Submission Type -->

    <!--Advance Value-->
    <div class="col-xs-3 form-group" ng-if="editAdvanceDetailsDisplay">
        <label for="advance_details">Advance Details<em class="asteriskRed">*</em></label>
        <textarea rows="1" class="form-control" ng-required="true" id="advance_details" ng-model="updateOrder.advance_details" name="advance_details" placeholder="Advance Details"></textarea>
    </div>
    <!--/Advance Value-->

    <!--Quotation No.-->
    <div class="col-xs-3 form-group">
        <label for="quotation_no">Quotation No.</label>
        <input type="text" class="form-control" id="quotation_no" ng-model="updateOrder.quotation_no" name="quotation_no" placeholder="Quotation No.">
    </div>
    <!--/Quotation No.-->

    <!--Actual Submission Type-->
    <div class="col-xs-3 form-group">
        <label for="actual_submission_type">Actual Submission Type</label>
        <input type="text" class="form-control" id="actual_submission_type" ng-model="updateOrder.actual_submission_type" name="actual_submission_type" placeholder="Actual Submission Type">
    </div>
    <!--/Actual Submission Type-->

    <!--convenience_charge-->
    <div class="col-xs-3 form-group">
        <label for="extra_amount">Extra Amount</label>
        <input type="text" class="form-control" id="extra_amount" ng-model="updateOrder.extra_amount" name="extra_amount" placeholder="Extra Amount">
    </div>
    <!--/convenience_charge-->
</div>

<div class="row">

    <!--Sample Condition-->
    <div class="col-xs-3 form-group">
        <label for="sample_condition">Sample Condition<em class="asteriskRed">*</em></label>
        <input type="text" class="form-control" id="sample_condition_edit" ng-model="updateOrder.sample_condition" name="sample_condition" ng-required="true" placeholder="Sample Condition">
        <span ng-messages="erpUpdateOrderForm.sample_condition.$error" ng-if="erpUpdateOrderForm.sample_condition.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Sample Condition is required</span>
        </span>
    </div>
    <!--/Sample Condition-->

    <!--Sampler -->
    <div class="col-xs-3 form-group">
        <label for="sampler_id">Sampler</label>
        <select class="form-control" name="sampler_id" id="edit_sampler_id" ng-options="samplerDropdown.name for samplerDropdown in samplerDropdownList track by samplerDropdown.id" ng-model="updateOrder.sampler_id">
            <option value="">Select Sampler</option>
        </select>
        <span ng-messages="erpCreateOrderForm.sampler_id.$error" ng-if="erpCreateOrderForm.sampler_id.$dirty || erpCreateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Sampler is required</span>
        </span>
    </div>
    <!--/Sampler-->

</div>
