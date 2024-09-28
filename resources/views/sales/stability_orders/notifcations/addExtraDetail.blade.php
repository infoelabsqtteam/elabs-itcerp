<div class="row order-section"> Extra Detail </div>
<div class="order_detail mT10">
    <div class="row">
        
        <!--PO Detail-->
        <div class="col-xs-12" ng-if="updateOrder.billing_type_id.id == 5">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="po_type">
                    <input
                        ng-if="updateOrder.billing_type_id.id == 5"
                        ng-init="funAddShowPODetailType(updateOrder.billing_type_id.id);"
                        type="checkbox"
                        id="add_po_type"
                        name="po_type"
                        ng-model="updateOrder.po_type"
                        ng-checked="showAddPOType"
                        ng-click="funAddShowPODetailType(updateOrder.billing_type_id.id);">
                        <strong>Add PO Detail</strong>
                </label>
            </div>
            <div class="col-xs-4 form-group" ng-if="showAddPOType">
                <label for="po_no">PO No.<em class="asteriskRed">*</em></label>
                <input
                    type="text"
                    ng-if="showAddPOType"
                    class="form-control"
                    id="po_no"
                    ng-required='true'
                    ng-model="updateOrder.stb_po_no"
                    name="po_no"
                    placeholder="PO No.">
            </div>
            <div class="col-xs-4 form-group" ng-if="showAddPOType">
                <label for="po_date">PO Date<em class="asteriskRed">*</em></label>
                <div class="input-group date">
                    <input
                        ng-if="showAddPOType"
                        type="text"
                        id="po_date_add"
                        ng-model="updateOrder.stb_po_date"
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
                    <input type="checkbox" id="add_sample_type" name="sample_type" ng-model="updateOrder.sample_type" ng-click="funAddShowSampleType();"><strong>Sample Type Detail</strong>
                </label>
            </div>
            <div class="col-xs-8 form-group" ng-if="showAddSampleType">
                <label class="checkbox-inline" for="order_sample_type">
                    <input type="radio" name="order_sample_type" id="inter_laboratory" ng-model="updateOrder.inter_laboratory" ng-value="1">&nbsp;Inter Laboratory
                </label>
                <label class="checkbox-inline" for="order_sample_type">
                    <input type="radio" name="order_sample_type" id="compensatory" ng-model="updateOrder.compensatory" ng-value="2">&nbsp;Compensatory
                </label>
            </div>   
        </div>
        <!--/Sample Type-->
        
        <!--Different Reporting & Invoicing Needed-->
        <div class="col-xs-12">
            <div class="col-xs-4 form-group">
                <label class="checkbox-inline" for="invoicing_needed">
                    <input type="checkbox" id="invoicing_reporting_add_id" ng-model="updateOrder.invoicingNeeded" ng-click="funAddShowHideInvoicingNeeded()"><strong>Different Invoicing & Reporting Address</strong>
                </label>
            </div>
            <div class="col-xs-8 form-group" ng-if="isInvoicingNeededAddFlag">
                <div class="col-xs-6 form-group" style="margin-left: -7px;">
                    <label for="reporting_to">Reporting To</label>
                    <a title="Select Custome State" ng-if="ReportingStateCityTreeView" ng-click="funShowReportingStateCityTreeViewPopup(17)" class='generate cursor-pointer'>Tree View</a>
                    <select class="form-control"
                        name="reporting_to"
                        ng-disabled="!customerListData.length"
                        id="reporting_to"
                        ng-model="updateOrder.reporting_to"
                        ng-options="customerList.name for customerList in customerListData track by customerList.id">
                    </select>
                </div>
                <div class="col-xs-6 form-group">
                    <label for="invoicing_to">Invoicing To</label>
                    <a title="Select Custome State" ng-if="InvoicingStateCityTreeView" ng-click="funShowInvoicingStateCityTreeViewPopup(18)" class='generate cursor-pointer'>Tree View</a>
                    <select class="form-control"
                        name="invoicing_to"
                        ng-disabled="!customerData.length"
                        id="invoicing_to"
                        ng-model="updateOrder.invoicing_to"
                        ng-options="customers.name for customers in customerData track by customers.id">
                    </select>
                </div>               
            </div>   
        </div>
        <!--/Different Reporting & Invoicing Needed-->
        
    </div>
</div>