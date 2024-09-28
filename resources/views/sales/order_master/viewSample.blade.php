<div class="row" style="background: #ccc;padding: 5;margin: 0;width:100%;">Sample Detail</div>
<div class="row mT10">				
    <!--Branch -->
    <div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
        <label for="division_id">Branch</label> : <span ng-bind="viewOrder.division_name"></span>   
    </div>
    <!--/Branch -->
    
    <!-- Order date.-->
    <div class="col-xs-3 form-group">
        <label for="order_date">Order Date</label> : <span ng-bind="viewOrder.order_date"></span>
    </div>
    <!-- /Order date-->
    
    <!-- Expected Due Date.-->
    <div class="col-xs-3 form-group">
        <label for="expected_due_date">Expected Due Date</label> : <span ng-bind="viewOrder.expected_due_date"></span>
    </div>
    <!-- /Expected Due Date-->
    
    <!--Reference No-->
    <div class="col-xs-3 form-group">
        <label for="reference_no">Reference No.</label> : <span ng-bind="viewOrder.reference_no"></span>
    </div>
    <!--/Reference No-->

</div>

<div class="row">    
    <!--Sample Description-->
    <div class="col-xs-3 form-group">
        <label for="sample_description">Sample Description</label> : <span ng-bind="viewOrder.sample_description"></span>
    </div>
    <!--/Sample Description-->
</div>

<div class="row">
    
    <!--batch number-->
    <div class="col-xs-3 form-group">
        <label for="batch_no">Batch No.</label> : <span ng-bind="viewOrder.batch_no"></span>
    </div>
    <!-- /batch number-->
    
    <!-- Date of manufacturing.-->
    <div class="col-xs-3 form-group">
        <label for="mfg_date">Date of Mfg.</label> :
        <span ng-if="viewOrder.mfg_date.length" ng-bind="viewOrder.mfg_date"></span>
        <span ng-if="!viewOrder.mfg_date.length">-</span>
    </div>
    <!-- Date of manufacturing.-->
    
    <!--Date of Expiry-->
    <div class="col-xs-3 form-group">
        <label for="expiry_date">Date of Expiry</label> :
        <span ng-if="viewOrder.expiry_date.length" ng-bind="viewOrder.expiry_date"></span>
        <span ng-if="!viewOrder.expiry_date.length">-</span>       
    </div>
    <!--/Date of Expiry-->					
    
    <!--Batch Size-->
    <div class="col-xs-3 form-group">
        <label for="batch_size">Batch Size</label> : 
        <span ng-if="viewOrder.batch_size.length" ng-bind="viewOrder.batch_size"></span>
        <span ng-if="!viewOrder.batch_size.length">-</span>
    </div>
    <!--/Batch Size-->
    
</div>
    
<div class="row">
    
    <!--Sample Qty-->
    <div class="col-xs-3 form-group">
        <label for="sample_qty">Sample Qty.</label> : 
        <span ng-if="viewOrder.sample_qty.length" ng-bind="viewOrder.sample_qty"></span>
        <span ng-if="!viewOrder.sample_qty.length">-</span>
    </div>
    <!--/Sample Qty-->
    
    <!--Manufactured By-->
    <div class="col-xs-3 form-group">
        <label for="supplied_by">Supplied By</label> : 
        <span ng-if="viewOrder.supplied_by.length" ng-bind="viewOrder.supplied_by"></span>
        <span ng-if="!viewOrder.supplied_by.length">-</span>
    </div>
    <!--/Manufactured By-->
    
    <!--Manufactured By-->
    <div class="col-xs-3 form-group">
        <label for="manufactured_by">Manufactured By</label> : 
        <span ng-if="viewOrder.manufactured_by.length" ng-bind="viewOrder.manufactured_by"></span>
        <span ng-if="!viewOrder.manufactured_by.length">-</span>
    </div>
    <!--/Manufactured By-->	
    
    <!-- Barcode.-->
    <div class="col-xs-3 form-group">
        <label for="barcode">Security Code/Barcode</label> : 
        <span ng-if="viewOrder.barcode.length"><img ng-src="[[viewOrder.barcode]]"/></span>
        <span ng-if="!viewOrder.barcode.length">-</span>
    </div>
    <!-- /Barcode.-->
    
</div>
    
<div class="row">

    <!--Sample Priority-->
    <div class="col-xs-3 form-group">
        <label for="sample_priority_id">Sample Priority</label> : 
        <span ng-if="viewOrder.sample_priority_name.length" ng-bind="viewOrder.sample_priority_name"></span>
        <span ng-if="!viewOrder.sample_priority_name.length">-</span>
    </div>
    <!--/Sample Priority-->
    
    <!--Surcharge Value-->
    <div class="col-xs-3 form-group">
        <label for="surcharge_value">Surcharge</label> :
        <span ng-if="viewOrder.surcharge_value.length" ng-bind="viewOrder.surcharge_value"></span>
        <span ng-if="!viewOrder.surcharge_value.length">-</span>
    </div>
    <!--/Surcharge Value-->
    
    <!--Remarks-->
    <div class="col-xs-3 form-group">
        <label for="remarks">Remark</label> : 
        <span ng-if="viewOrder.remarks.length" ng-bind="viewOrder.remarks"></span>
        <span ng-if="!viewOrder.remarks.length">-</span>
    </div>
    <!--/Remarks-->
    
    <!--PI reference-->
    <div class="col-xs-3 form-group">
        <label for="pi_reference">PI Reference(if any)</label> : 
        <span ng-if="viewOrder.pi_reference.length" ng-bind="viewOrder.pi_reference"></span>
        <span ng-if="!viewOrder.pi_reference.length">-</span>
    </div>
    <!--/PI reference-->    
    
</div>

<div class="row">
    
    <!--Sealed/Unsealed -->
    <div class="col-xs-3 form-group">
        <label for="is_sealed">Sealed/Unsealed</label> :        
        <span ng-if="viewOrder.is_sealed == 1">Sealed</span>
        <span ng-if="viewOrder.is_sealed == 0">Unsealed</span>
    </div>
    <!--/Sealed/Unsealed -->
    
    <!--Signed/Unsigned -->
    <div class="col-xs-3 form-group">
        <label for="is_signed">Signed/Unsigned</label> :
        <span ng-if="viewOrder.is_signed == 1">Signed</span>
        <span ng-if="viewOrder.is_signed == 0">Unsigned</span>        
    </div>
    <!--/Signed/Unsigned -->
    
    <!--Packing Mode-->
    <div class="col-xs-3 form-group">
        <label for="packing_mode">Packing Mode</label> :
        <span ng-if="viewOrder.packing_mode.length" ng-bind="viewOrder.packing_mode"></span>
        <span ng-if="!viewOrder.packing_mode.length">-</span>
    </div>
    <!--/Packing Mode-->
    
    <!--Submission Type -->
    <div class="col-xs-3 form-group">
        <label for="submission_type">Submission Type</label> :        
        <span ng-if="viewOrder.submission_type == 1">Direct</span>
        <span ng-if="viewOrder.submission_type == 2">Courier</span>
        <span ng-if="viewOrder.submission_type == 3">Marketing Executive</span>     
    </div>
    <!--/Submission Type -->    
    
</div>
    
<div class="row">
    
    <!-- Sampling date.-->
    <div class="col-xs-3 form-group">
        <label for="sampling_date">Sampling Date</label> : <span ng-bind="viewOrder.sampling_date"></span>
    </div>
    <!-- /Sampling date.-->
    
    <!--Quotation No.-->
    <div class="col-xs-3 form-group">
        <label for="quotation_no">Quotation No.</label> :
        <span ng-if="viewOrder.quotation_no.length" ng-bind="viewOrder.quotation_no"></span>
        <span ng-if="!viewOrder.quotation_no.length">-</span>
    </div>
    <!--/Quotation No.-->
    
    <!--Brand-->
    <div class="col-xs-3 form-group">
        <label for="brand_type">Brand</label> : 
        <span ng-if="viewOrder.brand_type.length" ng-bind="viewOrder.brand_type"></span>
        <span ng-if="!viewOrder.brand_type.length">-</span>
    </div>
    <!--/Brand--> 
    
    <!--Actual Submission Type-->
    <div class="col-xs-3 form-group">
        <label for="actual_submission_type">Actual Submission Type</label> : 
        <span ng-if="viewOrder.actual_submission_type.length" ng-bind="viewOrder.actual_submission_type"></span>
        <span ng-if="!viewOrder.actual_submission_type.length">-</span>
    </div>
    <!--/Actual Submission Type-->
       
</div>
    
<div class="row">
    
    <!--Invoicing Needed-->
    <div class="col-xs-6 form-group">
        <label for="invoicing_needed">Invoicing Needed : 
        <span ng-if="viewOrder.reporting_to.length || viewOrder.invoicing_to.length">Yes</span>
        <span ng-if="!viewOrder.reporting_to.length || !viewOrder.invoicing_to.length">No</span>
    </div>
    <!--Invoicing Needed-->

    <!--Reporting To-->
    <div class="col-xs-6 form-group">
        <label for="reporting_to">Reporting To</label> :
        <span ng-if="viewOrder.reporting_to.length" ng-bind="viewOrder.reporting_to"></span>
        <span ng-if="!viewOrder.reporting_to.length">-</span>
    </div>
    <!--/Reporting To-->
    
    <!--Invoicing To-->
    <div class="col-xs-6 form-group">
        <label for="invoicing_to">Invoicing To</label> : 
        <span ng-if="viewOrder.invoicing_to.length" ng-bind="viewOrder.invoicing_to"></span>
        <span ng-if="!viewOrder.invoicing_to.length">-</span>
    </div>
    <!--/Invoicing To-->
    
</div>