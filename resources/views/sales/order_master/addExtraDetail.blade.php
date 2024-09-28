<div class="row order-section"> Extra Detail </div>
<div class="order_detail" ng-show="isSampleId">
    <div class="row">
        
        <!--Hold Detail-->
        <div class="col-xs-12 mT10">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="hold_type"><input type="checkbox" id="add_order_type" name="hold_type" ng-model="orderSample.hold_order" ng-click="funOrderOnHoldOrNot()"><strong>Hold Detail</strong></label>
            </div>
            <div class="col-xs-8 form-group" ng-if="orderHoldAddFlag">
                <textarea
                    class="form-control textarea"
                    rows="1"
                    name="hold_reason"                          
                    id="hold_reason"
                    ng-model="orderSample.hold_reason"
                    placeholder="Hold Reason">
                </textarea>
            </div>
        </div>
        <!--/Hold Detail-->
        
        <!--PO Detail-->
        <div class="col-xs-12" ng-if="orderCustomerDetail.billing_type_id == 5">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="po_type">
                    <input
                        ng-if="orderCustomerDetail.billing_type_id == 5"
                        type="checkbox"
                        ng-disabled="canAddChangePoTypeOrder"
                        id="add_po_type"
                        name="po_type"
                        ng-model="orderSample.po_type"
                        ng-click="funAddShowPODetailType(orderCustomerDetail.billing_type_id);">
                        <strong>Add PO Detail</strong>
                </label>
            </div>
            <div class="col-xs-4 form-group" ng-if="showAddPOType">
                <label for="po_no">PO No.<em class="asteriskRed">*</em></label>
                <input
                    type="text"
                    ng-if="showAddPOType"
                    ng-disabled="canAddChangePoTypeOrder"
                    class="form-control"
                    id="po_no"
                    ng-model="orderSample.po_no"
                    name="po_no"
                    placeholder="PO No.">
            </div>
            <div class="col-xs-4 form-group" ng-if="showAddPOType">
                <label for="po_date">PO Date<em class="asteriskRed">*</em></label>
                <div class="input-group date">
                    <input
                        ng-if="showAddPOType"
                        ng-disabled="canAddChangePoTypeOrder"
                        type="text"
                        id="po_date_add"
                        ng-model="orderSample.po_date"
                        name="po_date"
                        class="form-control bgWhite"
                        placeholder="PO Date">
                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span><script>$(function(){$('#po_date_add').datepicker({dateFormat: "dd-mm-yy",maxDate: 0,numberOfMonths: 1});});</script></div>
                </div>   
            </div>
        </div>
        <!--/PO Detail-->
        
        <!--Sample Type-->
        <div class="col-xs-12">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="sample_type">
                    <input ng-disabled="canAddChangeSampleTypeOrder" type="checkbox" id="add_sample_type" name="sample_type" ng-model="orderSample.sample_type" ng-click="funAddShowSampleType();"><strong>Sample Type Detail</strong>
                </label>
            </div>
            <div class="col-xs-8 form-group" ng-if="showAddSampleType">
                <label class="checkbox-inline" for="order_sample_type">
                    <input ng-disabled="canAddChangeSampleTypeOrder" type="radio" name="order_sample_type" id="inter_laboratory" ng-model="orderSample.inter_laboratory" ng-value="1">&nbsp;Inter Laboratory
                </label>
                <label class="checkbox-inline" for="order_sample_type">
                    <input ng-disabled="canAddChangeSampleTypeOrder" type="radio" name="order_sample_type" id="compensatory" ng-model="orderSample.compensatory" ng-value="2">&nbsp;Compensatory
                </label>
            </div>   
        </div>
        <!--/Sample Type-->
        
        <!--Different Reporting & Invoicing Needed-->
        <div class="col-xs-12">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="invoicing_needed">
                    <input type="checkbox" ng-disabled="canAddChangeInvoicingTo" id="invoicing_reporting_add_id" name="invoicing_reporting_type" ng-model="orderSample.invoicingNeeded" ng-click="funAddShowHideInvoicingNeeded()"><strong>Different Invoicing & Reporting Address</strong>
                </label>
            </div>
            <div class="col-xs-8 form-group" ng-if="isInvoicingNeededAddFlag">
                <div class="col-xs-6 form-group" style="margin-left: -7px;">
                    <label for="reporting_to">Reporting To</label>
                    <!--<a title="Select Custome State" ng-click="funShowReportingStateCityTreeViewPopup(17)" class='generate cursor-pointer'>Tree View</a>-->
                    <a title="Select Custome State" ng-click="funShowReportingCountryStateViewPopup(1)" class='generate cursor-pointer'>Select Country</a>

                    <select class="form-control"
                        name="reporting_to"                         
                        id="reporting_to"
                        ng-disabled="canAddChangeInvoicingTo"
                        ng-model="orderSample.reporting_to"
                        ng-options="customerList.name for customerList in customerListData track by customerList.id">
                        <option value="">Select Customer</option>
                    </select>
                </div>
                <div class="col-xs-6 form-group">
                    <label for="invoicing_to">Invoicing To</label>
                    <!--<a title="Select Custome State"  ng-click="funShowInvoicingStateCityTreeViewPopup(18)" class='generate cursor-pointer'>Tree View</a>-->
                    <a title="Select Custome State"  ng-click="funShowInvoicingCountryStateViewPopup(2)" class='generate cursor-pointer'>Select Country</a>
                    <select class="form-control"
                        name="invoicing_to"                         
                        id="invoicing_to"
                        ng-disabled="canAddChangeInvoicingTo"
                        ng-model="orderSample.invoicing_to"
                        ng-options="customers.name for customers in customerData track by customers.id">
                        <option value="">Select Customer</option>
                    </select>
                </div>               
            </div>   
        </div>
        <!--/Different Reporting & Invoicing Needed-->

        <!--credit Approval Add Form div-->
        @include('sales.order_master.creditApprovalAddForm')
        <!--/credit Approval Add Form div-->
        
    </div>
</div>