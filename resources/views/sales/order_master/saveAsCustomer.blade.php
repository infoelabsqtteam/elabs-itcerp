<div class="row" style="background: #ccc;padding: 5;margin: 0;width:100%;">Customer Detail</div>

<div class="row mT10">

    <!--Sample Received No.-->
    <div class="col-xs-3 form-group">
	<label for="sample_id">Sample Received No.</label>							
	<select
	    class="form-control bgWhite"
	    ng-model="saveAsOrder.sample_id.selectedOption"
	    id="sample_id"
	    title="[[sampleWithPlaceName]]"
	    name="sample_id"
	    ng-options="testSampleRecevied.name for testSampleRecevied in testSampleReceviedList track by testSampleRecevied.id">
	</select>
	<span ng-messages="erpSaveASOrderForm.sample_id.$error" ng-if='erpSaveASOrderForm.sample_id.$dirty || erpSaveASOrderForm.$submitted' role="alert">
	    <span ng-message="required" class="error">Sample Received No. is required</span>
	</span>
    </div>
    <!--/Sample Received No.-->
    
    <!--Customer--> 
    <div class="col-xs-3 form-group">
        <label for="product_id">Customer<em class="asteriskRed">*</em></label>							
        <select
	    class="form-control bgWhite"
	    readonly
	    id="customer_id"
	    name="customer_id"
	    ng-model="saveAsOrder.customer_id.selectedOption"
	    ng-required="true"
	    ng-options="customers.customer_name for customers in customerNameList track by customers.customer_id">
        </select>
	<span ng-messages="erpSaveASOrderForm.customer_id.$error" ng-if="erpSaveASOrderForm.customer_id.$dirty || erpSaveASOrderForm.$submitted" role="alert">
	    <span ng-message="required" class="error">Customer name is required</span>
	</span>
    </div>
    <!--/Customer-->
    
    <!--Customer Location-->
    <div class="col-xs-3 form-group">
        <label for="customer_location_id">Customer Location</label>
        <input disabled type="text" ng-model="saveAsOrder.customer_city_name" ng-value="saveAsOrder.customer_city_name" class="form-control" placeholder="Customer Location">
	<input type="hidden" name="customer_city" ng-model="saveAsOrder.customer_city" ng-value="saveAsOrder.customer_city" id="customer_city">
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
	    ng-model="saveAsOrder.mfg_lic_no"
	    ng-value="saveAsOrder.mfg_lic_no"
	    ng-required="true"
	    placeholder="Customer Mfg. Lic No.">
        <span ng-messages="erpSaveASOrderForm.mfg_lic_no.$error" ng-if="erpSaveASOrderForm.mfg_lic_no.$dirty || erpSaveASOrderForm.$submitted" role="alert">
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
	    ng-model="saveAsOrder.sale_executive.selectedOption"
	    id="sale_executive"
	    ng-required="true"
	    ng-options="salesExecutives.name for salesExecutives in salesExecutiveList track by salesExecutives.id">
	    <option value="">Select Sales Executive</option>
	</select>
	<span ng-messages="erpSaveASOrderForm.sale_executive.$error" ng-if="erpSaveASOrderForm.sale_executive.$dirty || erpSaveASOrderForm.$submitted" role="alert">
	    <span ng-message="required" class="error">Sales Executive is required</span>
	</span>
    </div>
    <!--/Sale Executive-->
    
    <!--Discount Type-->
    <div class="col-xs-3 form-group">
        <label for="discount_type">Discount Type</label>
        <input disabled type="text" class="form-control bgWhite" ng-model="saveAsOrder.discount_type_name" placeholder="Discount Type">
	<input type="hidden" name="discount_type_id" ng-model="saveAsOrder.discount_type_id" ng-value="saveAsOrder.discount_type_id" id="discount_type_id">
    </div>
    <!--/Discount Type-->
    
    <!--Discount Percentage-->
    <div class="col-xs-3 form-group">
        <label for="discount_percentage">Discount Percentage</label>
        <input readonly type="text" class="form-control" name="discount_value" ng-model="saveAsOrder.discount_value" ng-value="saveAsOrder.discount_value" id="discount_value" placeholder="Discount Value">
    </div>
    <!--/Discount Percentage-->
	
    <!----invoicng type------->
    <div class="col-xs-3">
        <label for="invoicing_type_id">Invoicing Type<em class="asteriskRed">*</em></label>						   
        <select class="form-control bgWhite"
	    name="invoicing_type_id"
	    ng-model="saveAsOrder.invoicing_type_id"
	    id="invoicing_type_id"
	    ng-required='true'
	    ng-options="item.name for item in invoicingTypeList track by item.id">
        </select>
        <span ng-messages="erpSaveASOrderForm.invoicing_type_id.$error" ng-if='erpSaveASOrderForm.invoicing_type_id.$dirty || erpSaveASOrderForm.$submitted' role="alert">
            <span ng-message="required" class="error">Invoicing type is required</span>
        </span>	
    </div>    
    <!----/invoicng type------>
    
    <!--Billing Type-->
    <div>
        <input type="hidden" name="billing_type_id" ng-model="saveAsOrder.billing_type_id" ng-value="saveAsOrder.billing_type_id" id="billing_type_id">
    </div>
    <!--/Billing Type-->

</div>