<div class="row" style="background: #ccc;padding: 5;margin: 0;width:100%;">Sample Detail</div>
<div class="row mT10">				
    <!--Branch -->
    <div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
        <label for="division_id">Branch</label> : <span ng-bind="viewOrderReport.division_name"></span>   
    </div>
    <!--/Branch -->
    
    <!-- Order date.-->
    <div class="col-xs-3 form-group">
        <label for="order_date">Order Date</label> : <span ng-bind="viewOrderReport.order_date"></span>
    </div>
    <!-- /Order date.-->
    
    <!-- Expected Due Date.-->
    <div class="col-xs-3 form-group">
        <label for="expected_due_date">Expected Due Date</label> : <span ng-bind="viewOrderReport.expected_due_date"></span>
    </div>
    <!-- /Expected Due Date-->
    
    <!-- report date.-->
    <div class="col-xs-3 form-group">
        <label for="order_date">Report Date</label> : 
        <span ng-if="viewOrderReport.report_date.length" ng-bind="viewOrderReport.report_date"></span>
        <span ng-if="!viewOrderReport.report_date.length">-</span>
    </div>
    <!-- /report date.-->

</div>

<div class="row">
    
    <!--Reference No-->
    <div class="col-xs-3 form-group">
        <label for="reference_no">Reference No.</label> : <span ng-bind="viewOrderReport.reference_no"></span>
    </div>
    <!--/Reference No-->
    
</div>

<div class="row">

    <!--Sample Description-->
    <div class="col-xs-3 form-group">
        <label for="sample_description">Sample Description</label> : <span ng-bind="viewOrderReport.sample_description"></span>
    </div>
    <!--/Sample Description-->
    
    <!--batch number-->
    <div class="col-xs-3 form-group">
        <label for="batch_no">Batch No.</label> : <span ng-bind="viewOrderReport.batch_no"></span>
    </div>
    <!-- /batch number-->
    
    <!-- Date of manufacturing.-->
    <div class="col-xs-3 form-group">
        <label for="mfg_date">Date of Mfg.</label> :
        <span ng-if="viewOrderReport.mfg_date.length" ng-bind="viewOrderReport.mfg_date"></span>
        <span ng-if="!viewOrderReport.mfg_date.length">-</span>
    </div>
    <!-- Date of manufacturing.-->
    
    <!--Date of Expiry-->
    <div class="col-xs-3 form-group">
        <label for="expiry_date">Date of Expiry</label> :
        <span ng-if="viewOrderReport.expiry_date.length" ng-bind="viewOrderReport.expiry_date"></span>
        <span ng-if="!viewOrderReport.expiry_date.length">-</span>       
    </div>
    <!--/Date of Expiry-->
    
</div>
    
<div class="row">

    <!--Batch Size-->
    <div class="col-xs-3 form-group">
        <label for="batch_size">Batch Size</label> : 
        <span ng-if="viewOrderReport.batch_size.length" ng-bind="viewOrderReport.batch_size"></span>
        <span ng-if="!viewOrderReport.batch_size.length">-</span>
    </div>
    <!--/Batch Size-->
    
    <!--Sample Qty-->
    <div class="col-xs-3 form-group">
        <label for="sample_qty">Sample Qty.</label> : 
        <span ng-if="viewOrderReport.sample_qty.length" ng-bind="viewOrderReport.sample_qty"></span>
        <span ng-if="!viewOrderReport.sample_qty.length">-</span>
    </div>
    <!--/Sample Qty-->
    
    <!--Manufactured By-->
    <div class="col-xs-3 form-group">
        <label for="supplied_by">Supplied By</label> : 
        <span ng-if="viewOrderReport.supplied_by.length" ng-bind="viewOrderReport.supplied_by"></span>
        <span ng-if="!viewOrderReport.supplied_by.length">-</span>
    </div>
    <!--/Manufactured By-->
    
    <!--Manufactured By-->
    <div class="col-xs-3 form-group">
        <label for="manufactured_by">Manufactured By</label> : 
        <span ng-if="viewOrderReport.manufactured_by.length" ng-bind="viewOrderReport.manufactured_by"></span>
        <span ng-if="!viewOrderReport.manufactured_by.length">-</span>
    </div>
    <!--/Manufactured By-->
    
</div>
    
<div class="row">

    <!-- Barcode.-->
    <div class="col-xs-3 form-group">
        <label for="barcode">Security Code/Barcode</label> : 
        <span ng-if="viewOrderReport.barcode.length" ng-bind="viewOrderReport.barcode"></span>
        <span ng-if="!viewOrderReport.barcode.length">-</span>
    </div>
    <!-- /Barcode.-->

    <!--Sample Priority-->
    <div class="col-xs-3 form-group">
        <label for="sample_priority_id">Sample Priority</label> : 
        <span ng-if="viewOrderReport.sample_priority_name.length" ng-bind="viewOrderReport.sample_priority_name"></span>
        <span ng-if="!viewOrderReport.sample_priority_name.length">-</span>
    </div>
    <!--/Sample Priority-->
    
    <!--Surcharge Value-->
    <div class="col-xs-3 form-group">
        <label for="surcharge_value">Surcharge</label> :
        <span ng-if="viewOrderReport.surcharge_value.length" ng-bind="viewOrderReport.surcharge_value"></span>
        <span ng-if="!viewOrderReport.surcharge_value.length">-</span>
    </div>
    <!--/Surcharge Value-->
    
    <!--Remarks-->
    <div class="col-xs-3 form-group">
        <label for="remarks">Remark</label> : 
        <span ng-if="viewOrderReport.remarks.length" ng-bind="viewOrderReport.remarks"></span>
        <span ng-if="!viewOrderReport.remarks.length">-</span>
    </div>
    <!--/Remarks-->
    
</div>

<div class="row">
    
    <!--PI reference-->
    <div class="col-xs-3 form-group">
        <label for="pi_reference">PI Reference(if any)</label> : 
        <span ng-if="viewOrderReport.pi_reference.length" ng-bind="viewOrderReport.pi_reference"></span>
        <span ng-if="!viewOrderReport.pi_reference.length">-</span>
    </div>
    <!--/PI reference-->    
    
    <!--Sealed/Unsealed -->
    <div class="col-xs-3 form-group">
        <label for="is_sealed">Sealed/Unsealed</label> :        
        <span ng-if="viewOrderReport.is_sealed == 1">Sealed</span>
        <span ng-if="viewOrderReport.is_sealed == 0">Unsealed</span>
    </div>
    <!--/Sealed/Unsealed -->
    
    <!--Signed/Unsigned -->
    <div class="col-xs-3 form-group">
        <label for="is_signed">Signed/Unsigned</label> :
        <span ng-if="viewOrderReport.is_signed == 1">Signed</span>
        <span ng-if="viewOrderReport.is_signed == 0">Unsigned</span>        
    </div>
    <!--/Signed/Unsigned -->
    
    <!--Packing Mode-->
    <div class="col-xs-3 form-group">
        <label for="packing_mode">Packing Mode</label> :
        <span ng-if="viewOrderReport.packing_mode.length" ng-bind="viewOrderReport.packing_mode"></span>
        <span ng-if="!viewOrderReport.packing_mode.length">-</span>
    </div>
    <!--/Packing Mode-->
    
</div>
    
<div class="row">

    <!--Submission Type -->
    <div class="col-xs-3 form-group">
        <label for="submission_type">Submission Type</label> :        
        <span ng-if="viewOrderReport.submission_type == 1">Direct</span>
        <span ng-if="viewOrderReport.submission_type == 2">Courier</span>
        <span ng-if="viewOrderReport.submission_type == 3">Marketing Executive</span>     
    </div>
    <!--/Submission Type -->    
    
    <!-- Sampling date.-->
    <div class="col-xs-3 form-group">
        <label for="sampling_date">Sampling Date</label> : <span ng-bind="viewOrderReport.sampling_date"></span>
    </div>
    <!-- /Sampling date.-->
    
    <!--Quotation No.-->
    <div class="col-xs-3 form-group">
        <label for="quotation_no">Quotation No.</label> :
        <span ng-if="viewOrderReport.quotation_no.length" ng-bind="viewOrderReport.quotation_no"></span>
        <span ng-if="!viewOrderReport.quotation_no.length">-</span>
    </div>
    <!--/Quotation No.-->
    
    <!--Brand-->
    <div class="col-xs-3 form-group">
        <label for="brand_type">Brand</label> : 
        <span ng-if="viewOrderReport.brand_type.length" ng-bind="viewOrderReport.brand_type"></span>
        <span ng-if="!viewOrderReport.brand_type.length">-</span>
    </div>
    <!--/Brand-->
    
</div>
    
<div class="row">

    <!--Actual Submission Type-->
    <div class="col-xs-6 form-group">
        <label for="actual_submission_type">Actual Submission Type</label> : 
        <span ng-if="viewOrderReport.actual_submission_type.length" ng-bind="viewOrderReport.actual_submission_type"></span>
        <span ng-if="!viewOrderReport.actual_submission_type.length">-</span>
    </div>
    <!--/Actual Submission Type-->
    
    <!--Invoicing Needed-->
    <div class="col-xs-6 form-group">
        <label for="invoicing_needed">Invoicing Needed : 
        <span ng-if="viewOrderReport.reporting_to.length || viewOrderReport.invoicing_to.length">Yes</span>
        <span ng-if="!viewOrderReport.reporting_to.length || !viewOrderReport.invoicing_to.length">No</span>
    </div>
    <!--Invoicing Needed-->
    
</div>
    
<div class="row">

    <!--Reporting To-->
    <div class="col-xs-6 form-group">
        <label for="reporting_to">Reporting To</label> :
        <span ng-if="viewOrderReport.reporting_to.length" ng-bind="viewOrderReport.reporting_to"></span>
        <span ng-if="!viewOrderReport.reporting_to.length">-</span>
    </div>
    <!--/Reporting To-->
    
    <!--Invoicing To-->
    <div class="col-xs-6 form-group">
        <label for="invoicing_to">Invoicing To</label> : 
        <span ng-if="viewOrderReport.invoicing_to.length" ng-bind="viewOrderReport.invoicing_to"></span>
        <span ng-if="!viewOrderReport.invoicing_to.length">-</span>
    </div>
    <!--/Invoicing To-->
    
</div>