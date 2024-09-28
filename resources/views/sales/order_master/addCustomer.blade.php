<div class="row order-section">Customer Detail</div>
<div class="row mT10">

    <!--Sample Received No-->
    <div class="col-xs-3 form-group">
        <label for="sample_id">Sample Received No.<em class="asteriskRed">*</em></label>
        <span ng-if="isViewInteralTransferSampleLink" class="generate">Internal Transfer:<input type="checkbox" name="interal_transfer_sample" ng-click="funOpenInternalTransferSamplePopup(sampleID);" id="interal_transfer_sample"></span>
        <select
            class="form-control"
            name="sample_id"
            ng-model="orderCustomer.sample_id"
            id="sample_id"
            ng-required="true"
            ng-options="testSampleRecevied.name for testSampleRecevied in testSampleReceviedList track by testSampleRecevied.id"
            ng-change="funGetCustomerAttachedSampleDetail(orderCustomer.sample_id.id);">
            <option value="">Select Sample Received No.</option> 
        </select>
        <span ng-messages="erpCreateOrderForm.sample_id.$error" ng-if='erpCreateOrderForm.sample_id.$dirty || erpCreateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Sample Received No. is required</span>
        </span>
    </div>
    <!--/Sample Received No-->
    
    <!--Customer Code-->
    <div class="col-xs-3 form-group">
        <label for="customer_id">Customer</label>	
        <select
            class="form-control"          
            id="customer_id"
            name="customer_id"
            ng-model="orderCustomer.customer_id"
            ng-required="true"
            ng-options="customers.customer_name for customers in customerNameList track by customers.customer_id"
            readonly>
        </select> 
    </div>
    <!--/Customer Code-->
    
    <!--Customer Address/Location-->
    <div class="col-xs-3 form-group">
        <label for="city_name">Customer Address/Location</label>
        <input disabled ng-model="orderCustomer.customer_address_detail" title="[[orderCustomer.customer_address_detail]]" ng-value="orderCustomer.customer_address_detail" class="form-control" placeholder="Customer Address/Location">
        <input type="hidden" name="customer_city" ng-model="orderCustomer.customer_city" ng-value="orderCustomer.customer_city" id="customer_city">
    </div>
    <!--Customer Address/Location-->
    
    <!--Customer MFG LIC Number-->
    <div class="col-xs-3 form-group">
        <label for="mfg_lic_no">Customer Mfg. Lic No.<em class="asteriskRed">*</em></label>
        <input
            type="text"
            class="form-control"
            id="mfg_lic_no"
            name="mfg_lic_no"
            ng-model="orderCustomer.mfg_lic_no"
            ng-required="true"
            placeholder="Customer Mfg. Lic No.">
        <span ng-messages="erpCreateOrderForm.mfg_lic_no.$error" ng-if="erpCreateOrderForm.mfg_lic_no.$dirty || erpCreateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Customer Mfg. Lic No. is required</span>
        </span>
    </div>
    <!--/Customer MFG LIC Number-->	
    
</div>

<div class="row"> 
    
    <!--Sale Executive-->
    <div class="col-xs-3 form-group">
        <label for="sale_executive_name">Sale Executive</label>
        <input disabled type="text" ng-model="orderCustomer.sale_executive_name" ng-value="orderCustomer.sale_executive_name" class="form-control" placeholder="Sale Executive">
        <input type="hidden" name="sale_executive" ng-model="orderCustomer.sale_executive" ng-value="orderCustomer.sale_executive" id="sale_executive">
    </div>
    <!--/Sale Executive-->
    
    <!--Discount Type-->
    <div class="col-xs-3 form-group">
        <label for="discount_type_id">Discount Type</label>
        <input disabled type="text" class="form-control" ng-model="orderCustomer.discount_type_name" ng-value="orderCustomer.discount_type_name" placeholder="Discount Type">
        <input type="hidden" name="discount_type_id" ng-model="orderCustomer.discount_type_id" ng-value="orderCustomer.discount_type_id" id="discount_type_id">
    </div>
    <!--/Discount Type-->
    
    <!--Discount Percentage-->
    <div class="col-xs-3 form-group">
        <label for="discount_value">Discount Value</label>
        <input readonly type="text" class="form-control" name="discount_value" ng-model="orderCustomer.discount_value" ng-value="orderCustomer.discount_value" id="discount_value" placeholder="Discount Value">
    </div>
    <!--/Discount Percentage-->
    
    <!--Billing Type-->
    <div class="col-xs-3">
        <label for="billing_type_id">Billing Type<em class="asteriskRed">*</em></label>
        <select
            class="form-control"
            name="billing_type_id"
            ng-model="orderCustomer.billing_type_id"
            id="billing_type_id"
            ng-required='true'
            ng-options="item.name for item in billingTypeList track by item.id"
            readonly>
        </select>
        <span ng-messages="erpCreateOrderForm.billing_type_id.$error" ng-if="erpCreateOrderForm.billing_type_id.$dirty || erpCreateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Billing Type is required</span>
        </span>
    </div>
    <!--/Billing Type-->
    
</div>
<div class="row">
    
    <!----Invoicng type-->
    <div class="col-xs-3">
        <label for="invoicing_type_id">Invoicing Type<em class="asteriskRed">*</em></label>
        <a href="javascript:;" class="generate" ng-if="invoicingTypeList.length" ng-click="funRefreshInvoicingStructure(sampleID)"><span aria-hidden="true">Refresh</span></a>
        <select
            class="form-control"
            name="invoicing_type_id"
            ng-model="orderCustomer.invoicing_type_id"
            id="invoicing_type_id"
            ng-required='true'
            ng-options="item.name for item in invoicingTypeList track by item.id"
            readonly>
        </select>
        <span ng-messages="erpCreateOrderForm.invoicing_type_id.$error" ng-if="erpCreateOrderForm.invoicing_type_id.$dirty || erpCreateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Invoicing type is required</span>
        </span>
    </div>
    <!----/Invoicng type------>

</div>
 