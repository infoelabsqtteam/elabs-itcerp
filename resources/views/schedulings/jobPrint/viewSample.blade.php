<hr>
<div class="row" style="background: #ccc;padding: 5;margin: 0;width:100%;"><strong>Sample Detail</strong></div>
<div class="row mT10">				
    <!--Branch -->
    <div  class="col-xs-4 form-group">
        <label for="division_id">Branch</label> : <span ng-bind="printOrderReport.division_name"></span>   
    </div>
    <!--/Branch -->
    
    <!-- Order date.-->
    <div class="col-xs-4 form-group">
        <label for="order_date">Order Date</label> : <span ng-bind="printOrderReport.order_date"></span>
    </div>
    <!-- /Order date.-->
    
    <!-- Expected Due Date.-->
    <div class="col-xs-4 form-group">
        <label for="expected_due_date">Expected Due Date</label> : <span ng-bind="printOrderReport.expected_due_date"></span>
    </div>
    <!-- /Expected Due Date-->

</div>

<div class="row">
    <!-- report date.-->
    <div class="col-xs-4 form-group">
        <label for="order_date">Report Date</label> : 
        <span ng-if="printOrderReport.report_date.length" ng-bind="printOrderReport.report_date"></span>
        <span ng-if="!printOrderReport.report_date.length">-</span>
    </div>
    <!-- /report date.-->
    <!--Sample Description-->
    <div class="col-xs-4 form-group">
        <label for="sample_description">Sample Description</label> : <span ng-bind="printOrderReport.sample_description"></span>
    </div>
    <!--/Sample Description-->
    <!--Reference No-->
    <div class="col-xs-4 form-group">
        <label for="reference_no">Reference No.</label> : <span ng-bind="printOrderReport.reference_no"></span>
    </div>
    <!--/Reference No-->
</div>

<div class="row">
    <!--batch number-->
    <div class="col-xs-4 form-group">
        <label for="batch_no">Batch No.</label> : <span ng-bind="printOrderReport.batch_no"></span>
    </div>
    <!-- /batch number-->
    
    <!-- Date of manufacturing.-->
    <div class="col-xs-4 form-group">
        <label for="mfg_date">Date of Mfg.</label> :
        <span ng-if="printOrderReport.mfg_date.length" ng-bind="printOrderReport.mfg_date"></span>
        <span ng-if="!printOrderReport.mfg_date.length">-</span>
    </div>
    <!-- Date of manufacturing.-->
    
    <!--Date of Expiry-->
    <div class="col-xs-4 form-group">
        <label for="expiry_date">Date of Expiry</label> :
        <span ng-if="printOrderReport.expiry_date.length" ng-bind="printOrderReport.expiry_date"></span>
        <span ng-if="!printOrderReport.expiry_date.length">-</span>       
    </div>
    <!--/Date of Expiry-->
    
</div>
    
<div class="row">

    <!--Batch Size-->
    <div class="col-xs-4 form-group">
        <label for="batch_size">Batch Size</label> : 
        <span ng-if="printOrderReport.batch_size.length" ng-bind="printOrderReport.batch_size"></span>
        <span ng-if="!printOrderReport.batch_size.length">-</span>
    </div>
    <!--/Batch Size-->
    
    <!--Sample Qty-->
    <div class="col-xs-4 form-group">
        <label for="sample_qty">Sample Qty.</label> : 
        <span ng-if="printOrderReport.sample_qty.length" ng-bind="printOrderReport.sample_qty"></span>
        <span ng-if="!printOrderReport.sample_qty.length">-</span>
    </div>
    <!--/Sample Qty-->
    
    <!--Manufactured By-->
    <div class="col-xs-4 form-group">
        <label for="supplied_by">Supplied By</label> : 
        <span ng-if="printOrderReport.supplied_by.length" ng-bind="printOrderReport.supplied_by"></span>
        <span ng-if="!printOrderReport.supplied_by.length">-</span>
    </div>
    <!--/Manufactured By-->
  
</div>
    
<div class="row">
    <!--Manufactured By-->
    <div class="col-xs-4 form-group">
        <label for="manufactured_by">Manufactured By</label> : 
        <span ng-if="printOrderReport.manufactured_by.length" ng-bind="printOrderReport.manufactured_by"></span>
        <span ng-if="!printOrderReport.manufactured_by.length">-</span>
    </div>
    <!--/Manufactured By-->
    <!-- Barcode.-->
    <div class="col-xs-4 form-group">
        <label for="barcode">Security Code/Barcode</label> : 
        <span ng-if="printOrderReport.barcode.length"><img ng-src="[[printOrderReport.barcode]]"></span>
        <span ng-if="!printOrderReport.barcode.length">-</span>
    </div>
    <!-- /Barcode.-->

    <!--Sample Priority-->
    <div class="col-xs-3 form-group">
        <label for="sample_priority_id">Sample Priority</label> : 
        <span ng-if="printOrderReport.sample_priority_name.length" ng-bind="printOrderReport.sample_priority_name"></span>
        <span ng-if="!printOrderReport.sample_priority_name.length">-</span>
    </div>
    <!--/Sample Priority-->
</div>

<div class="row">
    <!--Surcharge Value-->
    <div class="col-xs-4 form-group">
        <label for="surcharge_value">Surcharge</label> :
        <span ng-if="printOrderReport.surcharge_value.length" ng-bind="printOrderReport.surcharge_value"></span>
        <span ng-if="!printOrderReport.surcharge_value.length">-</span>
    </div>
    <!--/Surcharge Value-->
     <!--Remarks-->
    <div class="col-xs-4 form-group">
        <label for="remarks">Remark</label> : 
        <span ng-if="printOrderReport.remarks.length" ng-bind="printOrderReport.remarks"></span>
        <span ng-if="!printOrderReport.remarks.length">-</span>
    </div>
    <!--/Remarks-->
    <!--PI reference-->
    <div class="col-xs-4 form-group">
        <label for="pi_reference">PI Reference(if any)</label> : 
        <span ng-if="printOrderReport.pi_reference.length" ng-bind="printOrderReport.pi_reference"></span>
        <span ng-if="!printOrderReport.pi_reference.length">-</span>
    </div>
    <!--/PI reference-->    
</div>
    
<div class="row">
<!--Sealed/Unsealed -->
    <div class="col-xs-4 form-group">
        <label for="is_sealed">Sealed/Unsealed</label> :        
        <span ng-if="printOrderReport.is_sealed == 1">Sealed</span>
        <span ng-if="printOrderReport.is_sealed == 0">Unsealed</span>
    </div>
    <!--/Sealed/Unsealed -->
<!--Signed/Unsigned -->
    <div class="col-xs-4 form-group">
        <label for="is_signed">Signed/Unsigned</label> :
        <span ng-if="printOrderReport.is_signed == 1">Signed</span>
        <span ng-if="printOrderReport.is_signed == 0">Unsigned</span>        
    </div>
    <!--/Signed/Unsigned -->
    
    <!--Packing Mode-->
    <div class="col-xs-4 form-group">
        <label for="packing_mode">Packing Mode</label> :
        <span ng-if="printOrderReport.packing_mode.length" ng-bind="printOrderReport.packing_mode"></span>
        <span ng-if="!printOrderReport.packing_mode.length">-</span>
    </div>
    <!--/Packing Mode-->
       
</div>
   <div class="row">
   <!--Submission Type -->
    <div class="col-xs-4 form-group">
        <label for="submission_type">Submission Type</label> :        
        <span ng-if="printOrderReport.submission_type == 1">Direct</span>
        <span ng-if="printOrderReport.submission_type == 2">Courier</span>
        <span ng-if="printOrderReport.submission_type == 3">Marketing Executive</span>     
    </div>
    <!--/Submission Type --> 
    <!-- Sampling date.-->
    <div class="col-xs-4 form-group">
        <label for="sampling_date">Sampling Date</label> : <span ng-bind="printOrderReport.sampling_date"></span>
    </div>
    <!-- /Sampling date.-->
    
    <!--Quotation No.-->
    <div class="col-xs-4 form-group">
        <label for="quotation_no">Quotation No.</label> :
        <span ng-if="printOrderReport.quotation_no.length" ng-bind="printOrderReport.quotation_no"></span>
        <span ng-if="!printOrderReport.quotation_no.length">-</span>
    </div>
    <!--/Quotation No.-->
   </div> 
<div class="row">
    <!--Brand-->
    <div class="col-xs-4 form-group">
        <label for="brand_type">Brand</label> : 
        <span ng-if="printOrderReport.brand_type.length" ng-bind="printOrderReport.brand_type"></span>
        <span ng-if="!printOrderReport.brand_type.length">-</span>
    </div>
    <!--/Brand-->
    <!--Actual Submission Type-->
    <div class="col-xs-4 form-group">
        <label for="actual_submission_type">Actual Submission Type</label> : 
        <span ng-if="printOrderReport.actual_submission_type.length" ng-bind="printOrderReport.actual_submission_type"></span>
        <span ng-if="!printOrderReport.actual_submission_type.length">-</span>
    </div>
    <!--/Actual Submission Type-->
    
    <!--Invoicing Needed-->
    <div class="col-xs-4 form-group">
        <label for="invoicing_needed">Invoicing Needed : 
        <span ng-if="printOrderReport.reporting_to.length || printOrderReport.invoicing_to.length">Yes</span>
        <span ng-if="!printOrderReport.reporting_to.length || !printOrderReport.invoicing_to.length">No</span>
    </div>
    <!--Invoicing Needed-->
    
</div>
    
<div class="row">

    <!--Reporting To-->
    <div class="col-xs-6 form-group">
        <label for="reporting_to">Reporting To</label> :
        <span ng-if="printOrderReport.reporting_to.length" ng-bind="printOrderReport.reporting_to"></span>
        <span ng-if="!printOrderReport.reporting_to.length">-</span>
    </div>
    <!--/Reporting To-->
    
    <!--Invoicing To-->
    <div class="col-xs-6 form-group">
        <label for="invoicing_to">Invoicing To</label> : 
        <span ng-if="printOrderReport.invoicing_to.length" ng-bind="printOrderReport.invoicing_to"></span>
        <span ng-if="!printOrderReport.invoicing_to.length">-</span>
    </div>
    <!--/Invoicing To-->
    
</div>