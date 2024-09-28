<div class="row order-section"> Extra Detail </div>
<div class="order_detail">  
    <div class="row">
        
        <!--Hold Detail-->
        <div class="col-xs-12 mT10">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="edit_hold_type">
                    <input type="checkbox" ng-disabled="canHoldUnholdOrder" ng-checked="isOrderHoldNeeded" id="edit_hold_type" name="hold_type" ng-model="orderSample.hold_order" ng-click="funOrderOnHoldOrNotOnEdit(updateOrder.order_id)"><strong>Hold Detail</strong>
                </label>
            </div>
            <div class="col-xs-8 form-group" ng-show="orderHoldEditFlag">
                <textarea
                    class="form-control textarea"
                    rows="1"
                    ng-disabled="canHoldUnholdOrder"
                    name="hold_reason"                          
                    id="hold_reason"
                    ng-model="updateOrder.hold_reason"
                    placeholder="Hold Reason">
                </textarea>
            </div>
        </div>
        <!--/Hold Detail-->
        
        <!--PO Detail-->
        <div class="col-xs-12" ng-if="updateOrder.billing_type_id.id == 5">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="po_type">
                    <input
                        type="checkbox"
                        ng-disabled="canChangePoTypeOrder || canEditChangePoTypeOrder"
                        id="edit_po_type"
                        name="po_type"
                        ng-model="updateOrder.po_type"
                        ng-click="funEditShowPODetailType(updateOrder.billing_type_id.id);"><strong>PO Detail</strong>
                </label>
            </div>
            <div class="col-xs-4 form-group" ng-show="showEditPOType">
                <label for="po_no">PO No.<em class="asteriskRed">*</em></label>
                <input
                    type="text"
                    ng-disabled="canChangePoTypeOrder || canEditChangePoTypeOrder"
                    class="form-control"
                    id="po_no"
                    ng-model="updateOrder.po_no"
                    ng-value="updateOrder.po_no"
                    name="po_no"
                    placeholder="PO No">
            </div>
            <div class="col-xs-4 form-group" ng-show="showEditPOType">
                <label for="po_date">PO Date<em class="asteriskRed">*</em></label>
                <div class="input-group date">
                    <input
                        type="text"
                        ng-disabled="canChangePoTypeOrder || canEditChangePoTypeOrder"
                        id="po_date_edit"
                        ng-model="updateOrder.po_date"
                        ng-value="updateOrder.po_date"
                        name="po_date"
                        class="form-control"
                        placeholder="PO Date">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span><script>$(function(){$('#po_date_edit').datepicker({dateFormat: "dd-mm-yy",maxDate: 0,numberOfMonths: 1});});</script></div>
                </div>
            </div>   
        </div>
        <!--/PO Detail-->
        
        <!--Sample Type-->
        <div class="col-xs-12">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="sample_type">
                    <input type="checkbox" ng-disabled="canChangeSampleTypeOrder || canEditChangeSampleTypeOrder" id="edit_sample_type" name="sample_type" ng-checked="updateOrder.order_sample_type" ng-model="updateOrder.sample_type" ng-click="funShowEditSampleType();"><strong>Sample Type Detail</strong>
                </label>
            </div>
            <div class="col-xs-8 form-group" ng-show="showEditSampleType">
                <label class="checkbox-inline" for="order_sample_type">
                    <input type="radio" ng-disabled="canChangeSampleTypeOrder || canEditChangeSampleTypeOrder" name="order_sample_type" id="inter_laboratory" ng-model="updateOrder.inter_laboratory" ng-value="1" ng-checked="updateOrder.order_sample_type == 1">&nbsp;Inter Laboratory
                </label>
                <label class="checkbox-inline" for="order_sample_type">
                    <input type="radio" ng-disabled="canChangeSampleTypeOrder || canEditChangeSampleTypeOrder" name="order_sample_type" id="compensatory" ng-model="updateOrder.compensatory" ng-value="2" ng-checked="updateOrder.order_sample_type == 2">&nbsp;Compensatory
                </label>
            </div>
            <div ng-if="updateOrder.order_sample_type">
                <input ng-if="canChangeSampleTypeOrder || canEditChangeSampleTypeOrder" type="hidden" name="sample_type" ng-model="updateOrder.sample_type" ng-value="1">
                <input ng-if="canChangeSampleTypeOrder || canEditChangeSampleTypeOrder" type="hidden" name="order_sample_type" ng-model="updateOrder.order_sample_type" ng-value="updateOrder.order_sample_type">
            </div>
        </div>
        <!--/Sample Type-->
        
        <!--Different Reporting & Invoicing Needed-->
        <div class="col-xs-12 form-group">
            <div class="col-xs-4 form-group">                    
                <label class="checkbox-inline" for="invoicing_needed">
                    <input ng-disabled="canChangeInvoicingTo || canEditChangeInvoicingTo" ng-checked="isInvoicingEditCheckBoxFlag" type="checkbox" id="invoicing_reporting_edit_id" name="invoicing_reporting_type" ng-model="updateOrder.invoicingNeeded" ng-click="funEditShowHideInvoicingNeeded()"><strong>Different Invoicing & Reporting Address</strong>
                </label>
            </div>
            <div class="col-xs-8" ng-show="isInvoicingNeededEditFlag">
                <div class="col-xs-6 form-group" style="margin-left: -7px;">
                    <label for="reporting_to">Reporting To</label>
                    <a title="Select Custome State" ng-click="funShowEditReportingCountryStateViewPopup(1)" class='generate cursor-pointer'>Select Country</a>
                    <select class="form-control"
                        name="reporting_to"
                        id="reporting_to"
                        ng-model="updateOrder.reporting_to.selectedOption"
                        ng-disabled="canChangeInvoicingTo || canEditChangeInvoicingTo"
                        ng-change="funSetSelectedCustomer(updateOrder.reporting_to.selectedOption);"
                        ng-options="customers.name for customers in customerListData track by customers.id">
                        <option value="">Select Customer</option>
                    </select>
                </div>
                <div class="col-xs-6 form-group">
                    <label for="invoicing_to">Invoicing To</label>
                    <a title="Select Custome State" ng-if="!canChangePoTypeOrder"  ng-click="funShowEditInvoicingCountryStateViewPopup(2)" class='generate cursor-pointer'>Select Country</a>
                    <select class="form-control"
                        name="invoicing_to"                         
                        id="invoicing_to"
                        ng-disabled="canChangeInvoicingTo || canEditChangeInvoicingTo"
                        ng-model="updateOrder.invoicing_to.selectedOption"
                        ng-change="funSetSelectedCustomer(updateOrder.invoicing_to.selectedOption);"
                        ng-options="customerList.name for customerList in customerData track by customerList.id">
                        <option value="">Select Customer</option>
                    </select>
                </div>               
            </div>   
        </div>
        <!--/Different Reporting & Invoicing Needed-->

        <!--credit Approval Edit Form div-->
        @include('sales.order_master.creditApprovalEditForm')
        <!--/credit Approval Edit Form div-->
        
    </div>
</div>