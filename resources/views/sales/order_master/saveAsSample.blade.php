<div class="row" style="background: #ccc;padding: 5;margin: 0;width:100%;">Sample Detail</div>
<div class="row mT10">				
    
    <!--Branch -->
    <div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
        <select class="form-control"
            name="division_id"
            id="division_id"
            ng-model="saveAsOrder.division_id.selectedOption"
            ng-required="true"
            ng-options="division.name for division in divisionsCodeList track by division.id">
            <option value="">Select Branch</option>
        </select>
        <span ng-messages="erpSaveAsOrderForm.division_id.$error" ng-if="erpSaveAsOrderForm.division_id.$dirty || erpSaveAsOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Branch is required</span>
        </span>
    </div>
    <div ng-if="{{$division_id}} > 0">
        <input type="hidden" id="division_id" ng-model="order.division_id" name="division_id" value="{{$division_id}}">
    </div>
    <!--/Branch -->
    
    <!-- Order date.-->
    <div class="col-xs-3 form-group">
	<label for="order_date">Order Date<em class="asteriskRed">*</em></label>
	<div ng-if="{{!defined('IS_ADMIN') && defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER && $showOrderDateCalender == '0'}}">
	    <input
		type="text"
		id="order_date"
		ng-model="saveAsOrder.order_date"
		ng-required="true"
		name="order_date"
		class="form-control bgWhite"
		readonly>
	</div>
	<div ng-if="{{!defined('IS_ADMIN') && defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER && $showOrderDateCalender == '1'}}">
	    <div class="input-group date">
		<input
		    readonly
		    type="text"
		    id="order_date"
		    ng-model="saveAsOrder.order_date"
		    ng-required="true"
		    name="order_date"
		    class="form-control order_date_add_saveas"
		    placeholder="Order Date">
		<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
	    </div>   
	</div>
	<div ng-if="{{!defined('IS_ORDER_BOOKER') && defined('IS_ADMIN') && IS_ADMIN}}">
	    <div class="input-group date">
		<input
		    readonly
		    type="text"
		    id="order_date"
		    ng-model="saveAsOrder.order_date"
		    ng-required="true"
		    name="order_date"
		    class="form-control order_date_add_saveas"
		    placeholder="Order Date">
		<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
	    </div>   
	</div>
	<span ng-messages="erpSaveAsOrderForm.order_date.$error" ng-if="erpSaveAsOrderForm.order_date.$dirty || erpSaveAsOrderForm.$submitted" role="alert">
	    <span ng-message="required" class="error">Order date is required</span>
	</span>
    </div>
    <!-- /Order date.-->
    
    <!--Reference No-->
    <div class="col-xs-3 form-group">
        <label for="reference_no">Letter Reference No.</label>
	<input
	    type="text"
	    class="form-control"
	    id="reference_no"
	    ng-model="saveAsOrder.reference_no"
	    name="reference_no"
	    placeholder="Letter Reference No.">
        <span ng-messages="erpSaveAsOrderForm.reference_no.$error" ng-if='erpSaveAsOrderForm.reference_no.$dirty || erpSaveAsOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Letter Reference No. is required</span>
        </span>
    </div>
    <!--/Reference No-->
    
    <!--Letter No-->
    <div class="col-xs-3 form-group">
        <label for="letter_no">Letter Reference Date</label>
        <input
	    type="text"
	    class="form-control"
	    id="letter_no"
	    ng-model="saveAsOrder.letter_no"
	    name="letter_no"
	    placeholder="Letter Reference Date">
	<span ng-messages="erpSaveAsOrderForm.letter_no.$error" ng-if='erpSaveAsOrderForm.letter_no.$dirty || erpSaveAsOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Letter Reference Date is required</span>
        </span>
    </div>
    <!--/Letter No-->
    
    <!--Sample Description-->
    <div class="col-xs-3 form-group">
        <label for="sample_description">Sample Name<em class="asteriskRed">*</em></label>
	<input 
	    class="form-control"
	    name="sample_description" 			
	    ng-model="saveAsOrder.sample_description_name"
	    id="savas_sample_description"
	    ng-blur="funGetSearchSampleMatcheRate(saveAsOrder.sample_description,customerInvoicingTypeID,sampleID,'saveAs');"
	    ng-change="getAutoSearchSampleMatches(saveAsOrder.sample_description_name,sampleID)"
	    placeholder="Sample Name"
	    ng-required='true'
	    autocomplete="off">
	<span ng-messages="erpSaveAsOrderForm.sample_description.$error" ng-if='erpSaveAsOrderForm.sample_description.$dirty || erpSaveAsOrderForm.$submitted' role="alert">
	    <span ng-message="required" class="error">Sample Name is required</span>
	</span>
	<ul ng-if="showAutoSearchList" class="autoSearch">
	    <li ng-if="resultItems.length" ng-repeat="sampleObj in resultItems" ng-click="funsetSelectedSample(sampleObj.id,sampleObj.name,'saveAs');">[[sampleObj.name]]</li>
	    <li ng-if="!resultItems.length" >No Record Found!</li>
	</ul>
    </div>
    <!--/Sample Description-->
</div>

<div class="row">
    
    <!--batch number-->
    <div class="col-xs-3 form-group">
        <label for="batch_no">Batch No.<em class="asteriskRed">*</em></label>
        <input
	    type="text"
	    class="form-control"
	    id="batch_no"
	    ng-model="saveAsOrder.batch_no"
	    ng-required="true"
	    name="batch_no"
	    placeholder="batch no">
        <span ng-messages="erpSaveAsOrderForm.batch_no.$error" ng-if="erpSaveAsOrderForm.batch_no.$dirty || erpSaveAsOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Batch No. is required</span>
        </span>
    </div>
    <!-- /batch number-->
    
    <!-- Date of manufacturing.-->
    <div class="col-xs-3 form-group">
        <label for="mfg_date">Date of Mfg.</label>
        <input
	    type="text"
	    id="mfg_date"
	    ng-model="saveAsOrder.mfg_date"
	    name="mfg_date"
	    class="form-control bgWhite"
	    placeholder="Date of Mfg.">
    </div>
    <!-- Date of manufacturing.-->
    
    <!--Date of Expiry-->
    <div class="col-xs-3 form-group">
        <label for="expiry_date">Date of Expiry</label>
        <input
	    type="text"
	    id="expiry_date"
	    name="expiry_date"
	    ng-model="saveAsOrder.expiry_date"
	    class="form-control bgWhite"
	    placeholder="Date of Expiry">
    </div>
    <!--/Date of Expiry-->					
    
    <!--Batch Size-->
    <div class="col-xs-3 form-group">
        <label for="batch_size">Batch Size</label>
        <input
	    type="text"
	    class="form-control"
	    id="batch_size"
	    ng-model="saveAsOrder.batch_size"
	    name="batch_size"
	    placeholder="batch size">
    </div>
    <!--/Batch Size-->
</div>
    
<div class="row">
    
    <!--Sample Qty-->
    <div class="col-xs-3 form-group">
        <label for="sample_qty">Sample Qty.</label>
        <input
	    type="text"
	    class="form-control"
	    id="sample_qty"
	    ng-model="saveAsOrder.sample_qty"
	    name="sample_qty"
	    placeholder="sample qty">
    </div>
    <!--/Sample Qty-->
    
    <!--Manufactured By-->
    <div class="col-xs-3 form-group">
        <label for="manufactured_by">Supplied By<input type="checkbox"  ng-model="saveAsOrder.suppliedBy" ng-click="checkSaveAsCustomer(saveAsOrder.suppliedBy,saveAsOrder.customer_name,'supplied_by')" ng-checked = "saveAsOrder.supplied_by.length"></label>
        <input
	    type="text"
	    class="form-control"
	    id="supplied_by"
	    ng-model="saveAsOrder.supplied_by"
	    name="supplied_by"
	    placeholder="supplied by">
    </div>
    <!--/Manufactured By-->
    
    <!--Manufactured By-->
    <div class="col-xs-3 form-group">
        <label for="manufactured_by">Manufactured By <input type="checkbox" ng-model="saveAsOrder.manufacturedBy" ng-click="checkSaveAsCustomer(saveAsOrder.manufacturedBy,saveAsOrder.customer_name,'manufactured_by')" ng-checked = "saveAsOrder.manufactured_by.length"></label>
        <input
	    type="text"
	    class="form-control"
	    id="manufactured_by"
	    ng-model="saveAsOrder.manufactured_by"
	    name="manufactured_by"
	    placeholder="manufactured by">
    </div>
    <!--/Manufactured By-->
    
    <!--PI reference-->
    <div class="col-xs-3 form-group">
        <label for="pi_reference">PI Reference(if any)</label>
        <input
	    type="text"
	    class="form-control"
	    id="pi_reference"
	    ng-model="saveAsOrder.pi_reference"
	    name="pi_reference"
	    placeholder="PI reference">
    </div>
    <!--/PI reference--> 
    
</div>
    
<div class="row">

    <!--Sample Priority-->
    <div class="col-xs-3 form-group">
        <label for="sample_priority_id">Sample Priority<em class="asteriskRed">*</em></label>
        <select
	    ng-if="orderSaveAsSamplePriorityId == 4"
	    class="form-control"
	    readonly
	    name="sample_priority_id"
	    ng-model="saveAsOrder.sample_priority_id.selectedOption"
	    id="sample_priority_id"
	    ng-required="true"
	    ng-options="samplePriority.sample_priority_name for samplePriority in samplePriorityCRMList track by samplePriority.sample_priority_id">
	    <option value="">Select Sample Priority</option>
        </select>
	<select
		ng-if="orderSaveAsSamplePriorityId < 4"
		class="form-control"
                name="sample_priority_id"
                ng-model="saveAsOrder.sample_priority_id.selectedOption"
                id="sample_priority_id"
		ng-required="true"
                ng-change="funSaveAsSetSurchargeValue(saveAsOrder.sample_priority_id.selectedOption.sample_priority_id)"
                ng-options="samplePriority.sample_priority_name for samplePriority in samplePriorityList track by samplePriority.sample_priority_id">
            <option value="">Select Sample Priority</option>
        </select>
	<span ng-messages="erpSaveAsOrderForm.sample_priority_id.$error" ng-if="erpSaveAsOrderForm.sample_priority_id.$dirty || erpSaveAsOrderForm.$submitted" role="alert">
	    <span ng-message="required" class="error">Sample Priority is required</span>
	</span>
    </div>
    <!--/Sample Priority-->
    
    <!--Surcharge Value-->
    <div class="col-xs-3 form-group">
        <label for="surcharge_value">Surcharge (Rs)<em ng-if="isSaveAsSetSurchargeValueFlag" class="asteriskRed">*</em></label>
        <input type="text" ng-if="isSaveAsSetSurchargeValueFlag" class="form-control" id="surcharge_value" ng-model="saveAsOrder.surcharge_value" ng-required="true" name="surcharge_value" placeholder="Surcharge Value (Rs)">
        <input type="text" ng-if="!isSaveAsSetSurchargeValueFlag" readonly class="form-control" id="surcharge_value" ng-model="order.surcharge_value" name="surcharge_value" placeholder="Surcharge Value (Rs)">
        <div ng-if="isSaveAsSetSurchargeValueFlag">
            <span ng-messages="erpSaveAsOrderForm.surcharge_value.$error" ng-if="erpSaveAsOrderForm.surcharge_value.$dirty || erpSaveAsOrderForm.$submitted" role="alert">
                <span ng-message="required" class="error">Surcharge is required</span>
            </span>
        </div>
    </div>
    <!--/Surcharge Value-->
    
    <!--Brand-->
    <div class="col-xs-3 form-group">
	<label for="brand_type">Brand</label>
	<input type="text" class="form-control" id="brand_type" ng-model="saveAsOrder.brand_type" name="brand_type" placeholder="Brand">
    </div>
    <!--/Brand--> 
    
    <!--Remarks-->
    <div class="col-xs-3 form-group">
        <label for="remarks">Remark</label>
        <input type="text" class="form-control" id="remarks" ng-model="saveAsOrder.remarks" name="remarks" placeholder="Remarks">
    </div>
    <!--/Remarks--> 
    
</div>

<div class="row">
    
    <!--Sealed/Unsealed -->
    <div class="col-xs-3 form-group">
        <label for="is_sealed">Sealed/Unsealed<em class="asteriskRed">*</em></label>
	<select
	    class="form-control"
	    ng-required="true"
	    name="is_sealed"
	    id="is_sealed" 
	    ng-options="sealedUnsealed.name for sealedUnsealed in sealedUnsealedList track by sealedUnsealed.id"
	    ng-model="saveAsOrder.is_sealed.selectedOption">
        </select>		
        <span ng-messages="erpSaveAsOrderForm.is_sealed.$error" ng-if="erpSaveAsOrderForm.is_sealed.$dirty || erpSaveAsOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Sealed/Unsealed is required</span>
        </span>
    </div>
    <!--/Sealed/Unsealed -->
    
    <!--Signed/Unsigned -->
    <div class="col-xs-3 form-group">
        <label for="is_signed">Signed/Unsigned<em class="asteriskRed">*</em></label>
        <select
	    class="form-control"
	    ng-required="true"
	    name="is_signed"
	    id="is_signed" 
	    ng-options="signedUnsigned.name for signedUnsigned in signedUnsignedList track by signedUnsigned.id"
	    ng-model="saveAsOrder.is_signed.selectedOption">
        </select>
        <span ng-messages="erpSaveAsOrderForm.is_signed.$error" ng-if="erpSaveAsOrderForm.is_signed.$dirty || erpSaveAsOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Signed/Unsigned is required</span>
        </span>
    </div>
    <!--/Signed/Unsigned -->
    
    <!--Packing Mode-->
    <div class="col-xs-3 form-group">
        <label for="packing_mode">Packing Mode<em class="asteriskRed">*</em></label>
        <input
	    type="text"
	    class="form-control"
	    id="packing_mode"
	    ng-model="saveAsOrder.packing_mode"
	    ng-required="true"
	    name="packing_mode"
	    placeholder="Packing Mode">
        <span ng-messages="erpSaveAsOrderForm.packing_mode.$error" ng-if="erpSaveAsOrderForm.packing_mode.$dirty || erpSaveAsOrderForm.$submitted" role="alert">
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
		id="sampling_date_saveas"
		ng-model="saveAsOrder.sampling_date" 
		name="sampling_date"
		placeholder="Sampling Date">
            <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span></span>
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
		ng-change="funSaveAsSubmissionTypeValue(saveAsOrder.submission_type.selectedOption.id)"
		name="submission_type"
		id="submission_type" 
		ng-options="submissionType.name for submissionType in submissionTypeList track by submissionType.id"
		ng-model="saveAsOrder.submission_type.selectedOption">
	    </select>
	    <span ng-messages="erpSaveAsOrderForm.submission_type.$error" ng-if="erpSaveAsOrderForm.submission_type.$dirty || erpSaveAsOrderForm.$submitted" role="alert">
		<span ng-message="required" class="error">Submission type is required</span>
	    </span>		
	</div>
	<!--/Submission Type -->    
	
	<!--Advance Value-->
	<div class="col-xs-3 form-group" ng-if="saveAsAdvanceDetailsDisplay">
	    <label for="advance_details">Advance Details<em class="asteriskRed">*</em></label>
	    <textarea
		rows="1"
		class="form-control"
		ng-required="true"
		id="advance_details"
		ng-model="saveAsOrder.advance_details"
		name="advance_details"
		placeholder="Advance Details">
	    </textarea>
	</div>
	<!--/Advance Value-->
	
	<!--Quotation No.-->
	<div class="col-xs-3 form-group">
	    <label for="quotation_no">Quotation No.</label>
	    <input
		type="text"
		class="form-control"
		id="quotation_no"
		ng-model="saveAsOrder.quotation_no"
		name="quotation_no"
		placeholder="Quotation No.">
	</div>
	<!--/Quotation No.-->
	
	<!--Actual Submission Type-->
	<div class="col-xs-3 form-group">
	    <label for="actual_submission_type">Actual Submission Type</label>
	    <input
		type="text"
		class="form-control"
		id="actual_submission_type"
		ng-model="saveAsOrder.actual_submission_type"
		name="actual_submission_type"
		placeholder="Actual Submission Type">
	</div>
	<!--/Actual Submission Type-->
	
	<!--convenience_charge-->
	<div class="col-xs-3 form-group">
	    <label for="extra_amount">Extra Amount</label>
	    <input
		type="text"
		class="form-control"
		id="extra_amount"
		ng-model="saveAsOrder.extra_amount"
		name="extra_amount"
		placeholder="Extra Amount">
	</div>
	<!--/convenience_charge-->
 </div>