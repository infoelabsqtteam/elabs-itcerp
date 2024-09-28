<div class="row" style="background: #ccc;padding: 5;margin: 0;width:100%;"> Extra Detail </div>
<div class="order_detail">  
    <div class="row">
        
        <!--Hold Detail-->
        <div class="col-xs-12 mT10">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="saveas_hold_type">
                    <input type="checkbox" ng-disabled="canHoldUnholdOrder" ng-checked="isOrderHoldNeeded" id="saveas_hold_type" name="hold_type" ng-model="orderSample.hold_order" ng-click="funOrderOnHoldOrNotOnSaveAs(saveAsOrder.order_id)"><strong>Hold Detail</strong>
                </label>
            </div>
            <div class="col-xs-8 form-group" ng-show="orderHoldSaveAsFlag">
                <textarea
                    class="form-control textarea"
                    rows="1"
                    ng-disabled="canHoldUnholdOrder"
                    name="hold_reason"                          
                    id="hold_reason"
                    ng-model="saveAsOrder.hold_reason"
                    placeholder="Hold Reason">
                </textarea>
            </div>
        </div>
        <!--/Hold Detail-->
        
        <!--PO Detail-->
        <div class="col-xs-12">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="po_type">
                    <input type="checkbox" ng-disabled="canChangePoTypeOrder || canSaveAsChangePoTypeOrder" ng-checked="isOrderPOTypeNeeded" id="saveas_po_type" name="po_type" ng-model="saveAsOrder.po_type" ng-click="funSaveAsShowPODetailType();"><strong>PO Detail</strong>
                </label>
            </div>
            <div class="col-xs-4 form-group" ng-show="showSaveAsPOType">
                <label for="po_no">PO No.</label>
                <input type="text" ng-disabled="canChangePoTypeOrder || canSaveAsChangePoTypeOrder" class="form-control" id="po_no" ng-model="saveAsOrder.po_no" ng-value="saveAsOrder.po_no" name="po_no" placeholder="PO No">
            </div>
            <div class="col-xs-4 form-group" ng-show="showSaveAsPOType">
                <label for="po_date">PO Date</label>
                <div class="input-group date">
                    <input type="text" ng-disabled="canChangePoTypeOrder || canSaveAsChangePoTypeOrder" id="po_date_saveas" ng-model="saveAsOrder.po_date" ng-value="saveAsOrder.po_date" name="po_date" class="form-control bgWhite" placeholder="PO Date">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span><script>$(function(){$('#po_date_saveas').datepicker({dateFormat: "dd-mm-yy",maxDate: 0,numberOfMonths: 1});});</script></div>
                </div>   
            </div>   
        </div>
        <!--/PO Detail-->
        
        <!--Sample Type-->
        <div class="col-xs-12">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="sample_type">
                    <input type="checkbox" ng-disabled="canChangeSampleTypeOrder || canSaveAsChangeSampleTypeOrder" id="saveas_sample_type" name="sample_type" ng-checked="saveAsOrder.order_sample_type" ng-model="saveAsOrder.sample_type" ng-click="funShowSaveAsSampleType();"><strong>Sample Type Detail</strong>
                </label>
            </div>
            <div class="col-xs-8 form-group" ng-show="showSaveAsSampleType">
                <label class="checkbox-inline" for="order_sample_type">
                    <input type="radio" ng-disabled="canChangeSampleTypeOrder || canSaveAsChangeSampleTypeOrder" name="order_sample_type" id="inter_laboratory" ng-model="saveAsOrder.inter_laboratory" ng-value="1" ng-checked="saveAsOrder.order_sample_type == 1">&nbsp;Inter Laboratory
                </label>
                <label class="checkbox-inline" for="order_sample_type">
                    <input type="radio" ng-disabled="canChangeSampleTypeOrder || canSaveAsChangeSampleTypeOrder" name="order_sample_type" id="compensatory" ng-model="saveAsOrder.compensatory" ng-value="2" ng-checked="saveAsOrder.order_sample_type == 2">&nbsp;Compensatory
                </label>
            </div>   
        </div>
        <!--/Sample Type-->
        
        <!--Different Reporting & Invoicing Needed-->
        <div class="col-xs-12 form-group">
            <div class="col-xs-4 form-group">                    
                <label class="checkbox-inline" for="invoicing_needed">
                    <input ng-disabled="canSaveAsChangeInvoicingTo" ng-checked="isInvoicingSaveAsCheckBoxFlag" type="checkbox" id="invoicing_reporting_saveas_id" ng-model="saveAsOrder.invoicingNeeded" ng-click="funSaveAsShowHideInvoicingNeeded()"><strong>Different Invoicing & Reporting Address</strong>
                </label>
            </div>
            <div class="col-xs-8" ng-show="isInvoicingNeededSaveAsFlag">
                <div class="col-xs-6 form-group" style="margin-left: -7px;">
                    <label for="reporting_to">Reporting To</label>
                    <a title="Select Custome State" ng-click="funShowSaveAsReportingStateCityTreeViewPopup(17)" class='generate cursor-pointer'>Tree View</a>
                    <select class="form-control"
                        name="reporting_to"
                        id="reporting_to"
                        ng-model="saveAsOrder.reporting_to.selectedOption"
                        ng-disabled="canSaveAsChangeInvoicingTo"
                        ng-change="funSetSelectedCustomer(saveAsOrder.reporting_to.selectedOption);"
                        ng-options="customers.name for customers in customerListData track by customers.id">
                        <option value="">Select Customer</option>
                    </select>
                </div>
                <div class="col-xs-6 form-group">
                    <label for="invoicing_to">Invoicing To</label>
                    <a title="Select Custome State" ng-if="!canChangePoTypeOrder" ng-click="funShowSaveAsInvoicingStateCityTreeViewPopup(18)" class='generate cursor-pointer'>Tree View</a>
                    <select class="form-control"
                        name="invoicing_to"                         
                        id="invoicing_to"
                        ng-disabled="canChangeInvoicingTo || canSaveAsChangeInvoicingTo"
                        ng-model="saveAsOrder.invoicing_to.selectedOption"
                        ng-change="funSetSelectedCustomer(saveAsOrder.invoicing_to.selectedOption);"
                        ng-options="customerList.name for customerList in customerData track by customerList.id">
                        <option value="">Select Customer</option>
                    </select>
                </div>               
            </div>   
        </div>
        <!--/Different Reporting & Invoicing Needed-->        
    </div>
</div>