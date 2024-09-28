<div class="row order-section">Customer Detail</div>
<div class="row mT10">

    <!--Sample Received No-->
    <div class="col-xs-3 form-group">
        <label for="sample_id">Sample Received No.<em class="asteriskRed">*</em></label>
        <select
            readonly
            class="form-control bgWhite"
            title="[[sampleWithPlaceName]]"
            name="sample_id"
            ng-model="updateOrder.sample_id"
            id="sample_id"
            ng-required="true"
            ng-options="testSampleRecevied.name for testSampleRecevied in testSampleReceviedList track by testSampleRecevied.id">
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
            class="form-control bgWhite"          
            id="customer_id"
            name="customer_id"
            ng-model="updateOrder.customer_id"
            ng-required="true"
            ng-options="customers.customer_name for customers in customerNameList track by customers.customer_id"
            readonly>
        </select> 
    </div>
    <!--/Customer Code-->
    
    <!--Customer Location-->
    <div class="col-xs-3 form-group">
        <label for="city_name">Customer Location</label>
        <input disabled type="text" ng-model="updateOrder.customer_city_name" ng-value="updateOrder.customer_city_name" class="form-control bgWhite" placeholder="Customer Location">
        <input type="hidden" name="customer_city" ng-model="updateOrder.customer_city" ng-value="updateOrder.customer_city" id="customer_city">
    </div>
    <!--Customer Location-->
    
    <!--Customer MFG LIC Number-->
    <div class="col-xs-3 form-group">
        <label for="mfg_lic_no">Customer Mfg. Lic No.<em class="asteriskRed">*</em></label>
        <input
            type="text"
            class="form-control"
            id="mfg_lic_no"
            name="mfg_lic_no"
            ng-model="updateOrder.mfg_lic_no"
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
        <input disabled type="text" ng-model="updateOrder.sale_executive_name" ng-value="updateOrder.sale_executive_name" class="form-control bgWhite" placeholder="Sale Executive">
        <input type="hidden" name="sale_executive" ng-model="updateOrder.sale_executive" ng-value="updateOrder.sale_executive" id="sale_executive">
    </div>
    <!--/Sale Executive-->
    
    <!--Discount Type-->
    <div class="col-xs-3 form-group">
        <label for="discount_type_id">Discount Type</label>
        <input disabled type="text" class="form-control bgWhite" ng-model="updateOrder.discount_type_name" ng-value="updateOrder.discount_type_name" placeholder="Discount Type">
        <input type="hidden" name="discount_type_id" ng-model="updateOrder.discount_type_id" ng-value="updateOrder.discount_type_id" id="discount_type_id">
    </div>
    <!--/Discount Type-->
    
    <!--Discount Percentage-->
    <div class="col-xs-3 form-group">
        <label for="discount_value">Discount Value</label>
        <input readonly type="text" class="form-control bgWhite" name="discount_value" ng-model="updateOrder.discount_value" ng-value="updateOrder.discount_value" id="discount_value" placeholder="Discount Value">
    </div>
    <!--/Discount Percentage-->
    
    <!--Billing Type-->
    <div class="col-xs-3">
        <label for="billing_type_id">Billing Type<em class="asteriskRed">*</em></label>
        <select
            class="form-control bgWhite"
            name="billing_type_id"
            ng-model="updateOrder.billing_type_id"
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
        <select
            class="form-control bgWhite"
            name="invoicing_type_id"
            ng-model="updateOrder.invoicing_type_id"
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
 