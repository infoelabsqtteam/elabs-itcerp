<div class="row order-section">Customer Detail</div>

<div class="row mT10">

    <!--Sample Received No.-->
    <div class="col-xs-3 form-group">
	<label for="sample_id">Sample Received No.</label>
	<!--<a href="javascript:;" class="generate" ng-if="updateOrder.order_id" ng-click="funEditOrder(updateOrder.order_id)"><span aria-hidden="true">Refresh</span></a>-->
	<select
	    disabled
	    title="[[sampleWithPlaceName]]"
	    class="form-control bgWhite"
	    ng-model="updateOrder.sample_id.selectedOption"
	    id="sample_id"
	    ng-options="testSampleRecevied.name for testSampleRecevied in testSampleReceviedList track by testSampleRecevied.id">
	    <option value="">Select Sample Received No.</option>
	</select>
	<span ng-messages="erpUpdateOrderForm.sample_id.$error" ng-if='erpUpdateOrderForm.sample_id.$dirty || erpUpdateOrderForm.$submitted' role="alert">
	    <span ng-message="required" class="error">Sample Received No. is required</span>
	</span>
    </div>
    <!--/Sample Received No.-->
    
    <!--Customer--> 
    <div class="col-xs-3 form-group">
        <label for="product_id">Customer<em class="asteriskRed">*</em></label>							
        <select
	    class="form-control bgWhite"
	    id="customer_id"
	    name="customer_id"
	    ng-model="updateOrder.customer_id.selectedOption"
	    ng-required="true"
	    ng-change= "funEditCustomerAttachedDetail(updateOrder.customer_id.selectedOption.customer_id)"
	    ng-options="customers.customer_name for customers in customerNameList track by customers.customer_id">
            <option value="">Select Customer</option>
        </select>
	<span ng-messages="erpUpdateOrderForm.customer_id.$error" ng-if="erpUpdateOrderForm.customer_id.$dirty || erpUpdateOrderForm.$submitted" role="alert">
	    <span ng-message="required" class="error">Customer name is required</span>
	</span>
    </div>
    <!--/Customer-->
    
    <!--Customer Address/Location-->
    <div class="col-xs-3 form-group">
        <label for="customer_location_id">Customer Address/Location</label>
        <input disabled ng-model="updateOrder.customer_address_detail" title="[[updateOrder.customer_address_detail]]" ng-value="updateOrder.customer_address_detail" class="form-control" placeholder="Customer Address/Location">
	<input type="hidden" name="customer_city" ng-model="updateOrder.customer_city" ng-value="updateOrder.customer_city" id="customer_city">
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
	    ng-model="updateOrder.mfg_lic_no"
	    ng-value="updateOrder.mfg_lic_no"
	    ng-required="true"
	    placeholder="Customer Mfg. Lic No.">
        <span ng-messages="erpUpdateOrderForm.mfg_lic_no.$error" ng-if="erpUpdateOrderForm.mfg_lic_no.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Customer Mfg. Lic No. is required</span>
        </span>
    </div>
    <!--/Customer MFG LIC Number-->	
    
</div>

<div class="row">

    <!--Sale Executive-->
    <div class="col-xs-3">
	<label for="sale_executive1">Sales Executive<em class="asteriskRed">*</em></label>
	<select
	    class="form-control"
	    name="sale_executive"
	    ng-model="updateOrder.sale_executive.selectedOption"
	    id="sale_executive"
	    ng-required="true"
	    ng-options="salesExecutives.name for salesExecutives in salesExecutiveList track by salesExecutives.id">
	    <option value="">Select Sales Executive</option>
	</select>
	<span ng-messages="erpUpdateOrderForm.sale_executive.$error" ng-if="erpUpdateOrderForm.sale_executive.$dirty || erpUpdateOrderForm.$submitted" role="alert">
	    <span ng-message="required" class="error">Sales Executive is required</span>
	</span>
    </div>
    <!--/Sale Executive-->
    
    <!--Discount Type-->
    <div class="col-xs-3 form-group">
	<label for="discount_type_id">Discount Type<em class="asteriskRed">*</em></label>
        <select
            class="form-control"
            name="discount_type_id"
            ng-model="updateOrder.discount_type_id"
            id="discount_type_id"
	    ng-change="funGetDiscountTypeInput(updateOrder.discount_type_id.id,'edit')"
            ng-required='true'
            ng-options="item.name for item in discountTypeSetOnEditList track by item.id">
	    <option value="">Select Discount Type</option>
        </select>
        <span ng-messages="erpUpdateOrderForm.discount_type_id.$error" ng-if="erpUpdateOrderForm.discount_type_id.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Discount Type is required</span>
        </span>
    </div>
    <!--/Discount Type-->
    
    <!--Discount Value-->
    <div class="col-xs-3 form-group">
        <label for="discount_percentage">Discount value<em ng-if="applyDiscountTypeYes" class="asteriskRed">*</em></label>
        <input
	    ng-if="applyDiscountTypeYes"
	    type="text"
	    class="form-control"
	    name="discount_value"
	    ng-model="updateOrder.discount_value"
	    ng-value="updateOrder.discount_value"
	    id="discount_value"
	    ng-required='true'
	    placeholder="Discount Value">
	<input
	    ng-if="applyDiscountTypeNo"
	    readonly
	    type="text"
	    class="form-control"
	    name="discount_value"
	    ng-model="updateOrder.discount_value"
	    id="discount_value"
	    placeholder="Discount Value">
	<span ng-messages="erpUpdateOrderForm.discount_value.$error" ng-if="erpUpdateOrderForm.discount_value.$dirty || erpUpdateOrderForm.$submitted" role="alert">
	    <span ng-message="required" class="error">Discount value is required</span>
	</span>
    </div>
    <!--/Discount Value-->
    
    <!--Billing Type-->
    <div class="col-xs-3">
        <label for="billing_type_id">Billing Type<em class="asteriskRed">*</em></label>
        <select
            class="form-control"
            name="billing_type_id"
            ng-model="updateOrder.billing_type_id"
            id="billing_type_id"
            ng-required='true'
            ng-options="item.name for item in billingTypeSetOnEditList track by item.id">
	    <option value="">Select Billing Type</option>
        </select>
        <span ng-messages="erpUpdateOrderForm.billing_type_id.$error" ng-if="erpUpdateOrderForm.billing_type_id.$dirty || erpUpdateOrderForm.$submitted" role="alert">
            <span ng-message="required" class="error">Billing Type is required</span>
        </span>
    </div>
    <!--/Billing Type-->

</div>

<div class="row">
    
    <!----Invoicng type------->
    <div class="col-xs-3">
        <label for="invoicing_type_id">Invoicing Type<em class="asteriskRed">*</em></label>						   
        <select
	    class="form-control"
	    name="invoicing_type_id"
	    ng-model="updateOrder.invoicing_type_id"
	    id="invoicing_type_id"
	    ng-required='true'
	    ng-change="funSetEditInvoicingType(updateOrder.customer_id.selectedOption.customer_id,updateOrder.invoicing_type_id.id)"
	    ng-options="item.name for item in invoicingTypesSetOnEditList track by item.id">
	    <option value="">Select Invoicing Type</option>
        </select>
        <span ng-messages="erpUpdateOrderForm.invoicing_type_id.$error" ng-if='erpUpdateOrderForm.invoicing_type_id.$dirty || erpUpdateOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Invoicing type is required</span>
        </span>	
    </div>    
    <!----/Invoicng type------>

</div>