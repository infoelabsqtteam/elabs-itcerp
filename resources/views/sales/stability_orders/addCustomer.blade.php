<div class="row order-section fontbd">Customer Detail</div>
<div class="row mT10">

    <!--Sample Received No-->
    <div class="col-xs-3 form-group">
        <label for="stb_sample_id">Sample Received No.<em class="asteriskRed">*</em></label>
        <select
            class="form-control"
            name="stb_sample_id"
            ng-model="orderStabilityCustomer.stb_sample_id"
            id="stb_sample_id"
            ng-required="true"
            ng-options="testSampleRecevied.name for testSampleRecevied in testSampleReceviedList track by testSampleRecevied.id"
            ng-change="funGetCustomerAttachedSampleDetail(orderStabilityCustomer.stb_sample_id.id);">
            <option value="">Select Sample Received No.</option> 
        </select>
        <span ng-messages="erpCreateStabilityOrderCSForm.stb_sample_id.$error" ng-if='erpCreateStabilityOrderCSForm.stb_sample_id.$dirty || erpCreateStabilityOrderCSForm.$submitted' role="alert">
            <span ng-message="required" class="error">Sample Received No. is required</span>
        </span>
    </div>
    <!--/Sample Received No-->
    
    <!--Customer Name-->
    <div class="col-xs-3 form-group">
        <label for="stb_customer_id">Customer</label>	
        <select
            class="form-control"          
            id="stb_customer_id"
            name="stb_customer_id"
            ng-model="orderStabilityCustomer.stb_customer_id"
            ng-required="true"
            ng-options="customers.customer_name for customers in customerNameList track by customers.customer_id"
            readonly>
        </select>
        <span ng-messages="erpCreateStabilityOrderCSForm.stb_customer_id.$error" ng-if="erpCreateStabilityOrderCSForm.stb_customer_id.$dirty || erpCreateStabilityOrderCSForm.$submitted" role="alert">
            <span ng-message="required" class="error">Customer Name is required</span>
        </span>
    </div>
    <!--/Customer Name-->
    
    <!--Customer Location-->
    <div class="col-xs-3 form-group">
        <label for="city_name">Customer Location</label>
        <input disabled type="text" ng-model="orderStabilityCustomer.stb_customer_city_name" ng-value="orderStabilityCustomer.stb_customer_city_name" class="form-control" placeholder="Customer Location">
        <input type="hidden" name="stb_customer_city" ng-model="orderStabilityCustomer.stb_customer_city" ng-value="orderStabilityCustomer.stb_customer_city" id="stb_customer_city">
    </div>
    <!--Customer Location-->
    
    <!--Customer MFG LIC Number-->
    <div class="col-xs-3 form-group">
        <label for="stb_mfg_lic_no">Customer Mfg. Lic No.<em class="asteriskRed">*</em></label>
        <input
            type="text"
            class="form-control"
            id="stb_mfg_lic_no"
            name="stb_mfg_lic_no"
            ng-model="orderStabilityCustomer.stb_mfg_lic_no"
            ng-required="true"
            placeholder="Customer Mfg. Lic No.">
        <span ng-messages="erpCreateStabilityOrderCSForm.stb_mfg_lic_no.$error" ng-if="erpCreateStabilityOrderCSForm.stb_mfg_lic_no.$dirty || erpCreateStabilityOrderCSForm.$submitted" role="alert">
            <span ng-message="required" class="error">Customer Mfg. Lic No. is required</span>
        </span>
    </div>
    <!--/Customer MFG LIC Number-->	
    
</div>
    
<div class="row"> 
    
    <!--Sale Executive-->
    <div class="col-xs-3 form-group">
        <label for="stb_sale_executive_name">Sale Executive</label>
        <input disabled type="text" ng-model="orderStabilityCustomer.stb_sale_executive_name" ng-value="orderStabilityCustomer.stb_sale_executive_name" class="form-control" placeholder="Sale Executive">
        <input type="hidden" name="stb_sale_executive" ng-model="orderStabilityCustomer.stb_sale_executive" ng-value="orderStabilityCustomer.stb_sale_executive" id="sale_executive">
    </div>
    <!--/Sale Executive-->
    
    <!--Discount Type-->
    <div class="col-xs-3 form-group">
        <label for="stb_discount_type_id">Discount Type</label>
        <input disabled type="text" class="form-control" ng-model="orderStabilityCustomer.stb_discount_type_name" ng-value="orderStabilityCustomer.stb_discount_type_name" placeholder="Discount Type">
        <input type="hidden" name="stb_discount_type_id" ng-model="orderStabilityCustomer.stb_discount_type_id" ng-value="orderStabilityCustomer.stb_discount_type_id" id="stb_discount_type_id">
    </div>
    <!--/Discount Type-->
    
    <!--Discount Percentage-->
    <div class="col-xs-3 form-group">
        <label for="stb_discount_value">Discount Value</label>
        <input readonly type="text" class="form-control" name="stb_discount_value" ng-model="orderStabilityCustomer.stb_discount_value" ng-value="orderStabilityCustomer.stb_discount_value" id="stb_discount_value" placeholder="Discount Value">
    </div>
    <!--/Discount Percentage-->
    
    <!--Billing Type-->
    <div class="col-xs-3">
        <label for="stb_billing_type_id">Billing Type<em class="asteriskRed">*</em></label>
        <select
            class="form-control"
            name="stb_billing_type_id"
            ng-model="orderStabilityCustomer.stb_billing_type_id"
            id="stb_billing_type_id"
            ng-required='true'
            ng-options="item.name for item in billingTypeList track by item.id"
            readonly>
        </select>
        <span ng-messages="erpCreateStabilityOrderCSForm.stb_billing_type_id.$error" ng-if="erpCreateStabilityOrderCSForm.stb_billing_type_id.$dirty || erpCreateStabilityOrderCSForm.$submitted" role="alert">
            <span ng-message="required" class="error">Billing Type is required</span>
        </span>
    </div>
    <!--/Billing Type-->
    
</div>
    
<div class="row">
    
    <!----Invoicng type-->
    <div class="col-xs-3">
        <label for="stb_invoicing_type_id">Invoicing Type<em class="asteriskRed">*</em></label>
        <a href="javascript:;" class="generate" ng-if="invoicingTypeList.length" ng-click="funRefreshInvoicingStructure(sampleID)"><span aria-hidden="true">Refresh</span></a>
        <select
            class="form-control"
            name="stb_invoicing_type_id"
            ng-model="orderStabilityCustomer.stb_invoicing_type_id"
            id="stb_invoicing_type_id"
            ng-required='true'
            ng-options="item.name for item in invoicingTypeList track by item.id"
            readonly>
        </select>
        <span ng-messages="erpCreateStabilityOrderCSForm.stb_invoicing_type_id.$error" ng-if="erpCreateStabilityOrderCSForm.stb_invoicing_type_id.$dirty || erpCreateStabilityOrderCSForm.$submitted" role="alert">
            <span ng-message="required" class="error">Invoicing type is required</span>
        </span>
    </div>
    <!----/Invoicng type------>
    
    <!--PO No.-->
    <div class="col-xs-3 form-group" ng-if="showAddPOTypeInStabilityOrder">
        <label for="stb_po_no">PO No.<em class="asteriskRed">*</em></label>
        <input
            type="text"
            class="form-control"
            id="stb_po_no"
            ng-model="orderStabilityCustomer.stb_po_no"
            name="stb_po_no"
            ng-required='true'
            placeholder="PO No.">
        <span ng-messages="erpCreateStabilityOrderCSForm.stb_po_no.$error" ng-if="erpCreateStabilityOrderCSForm.stb_po_no.$dirty || erpCreateStabilityOrderCSForm.$submitted" role="alert">
            <span ng-message="required" class="error">PO No. is required</span>
        </span>
    </div>
    <!--PO No.-->
    
    <!--PO Date-->
    <div class="col-xs-3 form-group" ng-if="showAddPOTypeInStabilityOrder">
        <label for="stb_po_date">PO Date<em class="asteriskRed">*</em></label>
        <div class="input-group date">
            <input
                readonly
                type="text"
                id="stb_po_date_add"
                name="stb_po_date"
                class="form-control bgWhite"
                ng-model="orderStabilityCustomer.stb_po_date"
		valid-date
                placeholder="PO Date">
            <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span><script>$(function(){$('#stb_po_date_add').datepicker({dateFormat: "dd-mm-yy",maxDate: 0,numberOfMonths: 1});});</script></div>
        </div>
        <span ng-messages="erpCreateStabilityOrderCSForm.stb_po_date.$error" ng-if="erpCreateStabilityOrderCSForm.stb_po_date.$dirty || erpCreateStabilityOrderCSForm.$submitted" role="alert">
            <span ng-message="required" class="error">PO Date is required</span>
        </span>
    </div>
    <!--/PO Date-->
    
    <!--Reporting To old ng-click="funShowReportingStateCityTreeViewPopup(17)"-->
    <div class="col-xs-3 form-group">
        <label for="stb_reporting_to">Reporting To</label>
         <a title="Select Customer country" ng-click="funShowReportingCountryViewPopup(1)" class='generate cursor-pointer'>Select Country</a>
        <select
            class="form-control"
            name="stb_reporting_to"                         
            id="stb_reporting_to"
            ng-model="orderStabilityCustomer.stb_reporting_to"
            ng-options="customerList.name for customerList in customerListReportingData track by customerList.id">
            <option value="">Select Customer</option>
        </select>
    </div>
    <!--/Reporting To-->
    
    <!--Invoicing To old ng-click="funShowInvoicingStateCityTreeViewPopup(18)"-->
    <div class="col-xs-3 form-group">
        <label for="stb_invoicing_to">Invoicing To</label>
        <a title="Select Customer Country" ng-click="funShowInvoicingCountryViewPopup(2)" class='generate cursor-pointer'>Select Country</a>
        <select
            class="form-control"
            name="stb_invoicing_to"                         
            id="stb_invoicing_to"
            ng-model="orderStabilityCustomer.stb_invoicing_to"
            ng-options="customers.name for customers in customerListInvoicingData track by customers.id">
            <option value="">Select Customer</option>
        </select>
    </div>
    <!--/Invoicing To-->
</div>