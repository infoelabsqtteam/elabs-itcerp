<div class="order_detail">
    <div class="row mT10">
    
        <!--Sample Received No.-->
        <div class="col-xs-3 form-group">
            <label for="stb_sample_id">Sample Received No.</label>
            <select
                readonly
                title="[[sampleWithPlaceName]]"
                class="form-control bgWhite"
                ng-model="updateStabilityOrder.stb_sample_id.selectedOption"
                id="stb_sample_id"
                name="stb_sample_id"
                ng-options="testSampleRecevied.name for testSampleRecevied in testSampleReceviedList track by testSampleRecevied.id">
            </select>
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_sample_id.$error" ng-if='erpUpdateStabilityOrderCSForm.stb_sample_id.$dirty || erpUpdateStabilityOrderCSForm.$submitted' role="alert">
                <span ng-message="required" class="error">Sample Received No. is required</span>
            </span>
        </div>
        <!--/Sample Received No.-->
        
        <!--Customer--> 
        <div class="col-xs-3 form-group">
            <label for="product_id">Customer<em class="asteriskRed">*</em></label>							
            <select
                class="form-control bgWhite"
                id="stb_customer_id"
                name="stb_customer_id"
                ng-model="updateStabilityOrder.stb_customer_id.selectedOption"
                ng-required="true"
                ng-change= "funEditCustomerAttachedDetail(updateStabilityOrder.stb_customer_id.selectedOption.customer_id)"
                ng-options="customers.customer_name for customers in customerNameList track by customers.customer_id">
                <option value="">Select Customer</option>
            </select>
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_customer_id.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_customer_id.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
                <span ng-message="required" class="error">Customer name is required</span>
            </span>
        </div>
        <!--/Customer-->
        
        <!--Customer Location-->
        <div class="col-xs-3 form-group">
            <label for="customer_location_id">Customer Location</label>
            <input disabled type="text" ng-model="updateStabilityOrder.stb_customer_city_name" ng-value="updateStabilityOrder.stb_customer_city_name" class="form-control" placeholder="Customer Location">
            <input type="hidden" name="stb_customer_city" ng-model="updateStabilityOrder.stb_customer_city" ng-value="updateStabilityOrder.stb_customer_city" id="stb_customer_city">
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
                ng-model="updateStabilityOrder.stb_mfg_lic_no"
                ng-value="updateStabilityOrder.stb_mfg_lic_no"
                ng-required="true"
                placeholder="Customer Mfg. Lic No.">
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_mfg_lic_no.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_mfg_lic_no.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
                <span ng-message="required" class="error">Customer Mfg. Lic No. is required</span>
            </span>
        </div>
        <!--/Customer MFG LIC Number-->
        
    </div>
        
    <div class="row"> 
        
        <!--Sale Executive-->
        <div class="col-xs-3">
            <label for="stb_sale_executive1">Sales Executive<em class="asteriskRed">*</em></label>
            <select
                class="form-control"
                name="stb_sale_executive"
                ng-model="updateStabilityOrder.stb_sale_executive.selectedOption"
                id="stb_sale_executive"
                ng-required="true"
                ng-options="salesExecutives.name for salesExecutives in salesExecutiveList track by salesExecutives.id">
                <option value="">Select Sales Executive</option>
            </select>
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_sale_executive.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_sale_executive.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
                <span ng-message="required" class="error">Sales Executive is required</span>
            </span>
        </div>
        <!--/Sale Executive-->
        
        <!--Discount Type-->
        <div class="col-xs-3 form-group">
            <label for="stb_discount_type_id">Discount Type<em class="asteriskRed">*</em></label>
            <select
                class="form-control"
                name="stb_discount_type_id"
                ng-model="updateStabilityOrder.stb_discount_type_id"
                id="stb_discount_type_id"
                ng-change="funGetDiscountTypeInput(updateStabilityOrder.stb_discount_type_id.id,'edit')"
                ng-required='true'
                ng-options="item.name for item in discountTypeSetOnEditList track by item.id">
                <option value="">Select Discount Type</option>
            </select>
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_discount_type_id.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_discount_type_id.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
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
                name="stb_discount_value"
                ng-model="updateStabilityOrder.stb_discount_value"
                ng-value="updateStabilityOrder.stb_discount_value"
                id="stb_discount_value"
                ng-required='true'
                placeholder="Discount Value">
            <input
                ng-if="applyDiscountTypeNo"
                readonly
                type="text"
                class="form-control"
                name="stb_discount_value"
                ng-model="updateStabilityOrder.stb_discount_value"
                id="stb_discount_value"
                placeholder="Discount Value">
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_discount_value.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_discount_value.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
                <span ng-message="required" class="error">Discount value is required</span>
            </span>
        </div>
        <!--/Discount Value-->
        
        <!--Billing Type-->
        <div class="col-xs-3">
            <label for="stb_billing_type_id">Billing Type<em class="asteriskRed">*</em></label>
            <select
                class="form-control"
                name="stb_billing_type_id"
                ng-model="updateStabilityOrder.stb_billing_type_id"
                id="stb_billing_type_id"
                ng-required='true'
                ng-change="funValidatePoNoAndPoDate(updateStabilityOrder.stb_billing_type_id.id)"
                ng-options="item.name for item in billingTypeSetOnEditList track by item.id">
                <option value="">Select Billing Type</option>
            </select>
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_billing_type_id.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_billing_type_id.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
                <span ng-message="required" class="error">Billing Type is required</span>
            </span>
        </div>
        <!--/Billing Type-->
        
    </div>
        
    <div class="row">
        
        <!----Invoicng type------->
        <div class="col-xs-3">
            <label for="stb_invoicing_type_id">Invoicing Type<em class="asteriskRed">*</em></label>						   
            <select
                class="form-control"
                name="stb_invoicing_type_id"
                ng-model="updateStabilityOrder.stb_invoicing_type_id"
                id="stb_invoicing_type_id"
                ng-required='true'
                ng-change="funSetEditInvoicingType(updateStabilityOrder.stb_customer_id.selectedOption.customer_id,updateStabilityOrder.stb_invoicing_type_id.id)"
                ng-options="item.name for item in invoicingTypesSetOnEditList track by item.id">
                <option value="">Select Invoicing Type</option>
            </select>
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_invoicing_type_id.$error" ng-if='erpUpdateStabilityOrderCSForm.stb_invoicing_type_id.$dirty || erpUpdateStabilityOrderCSForm.$submitted' role="alert">
                <span ng-message="required" class="error">Invoicing type is required</span>
            </span>	
        </div>    
        <!----/Invoicng type------>
        
        <!--PO No.-->
        <div class="col-xs-3 form-group" ng-if="updateStabilityOrder.stb_billing_type_id.id == 5">
            <label for="stb_po_no">PO No.<em class="asteriskRed">*</em></label>
            <input
                type="text"
                class="form-control"
                id="stb_po_no"
                ng-model="updateStabilityOrder.stb_po_no"
                name="stb_po_no"
                ng-required='true'
                placeholder="PO No.">
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_po_no.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_po_no.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
                <span ng-message="required" class="error">PO No. is required</span>
            </span>
        </div>
        <!--PO No.-->
        
        <!--PO Date-->
        <div class="col-xs-3 form-group" ng-if="updateStabilityOrder.stb_billing_type_id.id == 5">
            <label for="stb_po_date">PO Date<em class="asteriskRed">*</em></label>
            <div class="input-group date">
                <input
                    readonly
                    type="text"
                    id="stb_po_date_add"
                    name="stb_po_date"
                    class="form-control bgWhite"
                    ng-model="updateStabilityOrder.stb_po_date"
                    valid-date
                    placeholder="PO Date">
                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span><script>$(function(){$('#stb_po_date_add').datepicker({dateFormat: "dd-mm-yy",maxDate: 0,numberOfMonths: 1});});</script></div>
                <input type="hidden" name="stb_po_date_prev" ng-model="updateStabilityOrder.stb_po_date_prev" ng-value="updateStabilityOrder.stb_po_date_prev" id="stb_po_date_prev">
            </div>
            <span ng-messages="erpUpdateStabilityOrderCSForm.stb_po_date.$error" ng-if="erpUpdateStabilityOrderCSForm.stb_po_date.$dirty || erpUpdateStabilityOrderCSForm.$submitted" role="alert">
                <span ng-message="required" class="error">PO Date is required</span>
            </span>
        </div>
        <!--/PO Date-->
        
        <!--Reporting To old ng-click="funShowEditReportingStateCityTreeViewPopup(17)-->
        <div class="col-xs-3 form-group">
            <label for="stb_reporting_to">Reporting To</label>
            <a title="Select Custome State" ng-if="updateStabilityOrder.stb_status == 0" href="javascript:;" ng-click="funShowEditReportingCountryViewPopup(1)" class="generate cursor-pointer">Select Country</a>
            <select class="form-control"
                name="stb_reporting_to"
                id="stb_reporting_to"
                ng-model="updateStabilityOrder.stb_reporting_to.selectedOption"
                ng-options="customers.name for customers in customerListReportingData track by customers.id">
                <option value="">Select Customer</option>
            </select>
        </div>
        <!--/Reporting To-->
        
        <!--Invoicing To old ng-click="funShowEditInvoicingStateCityTreeViewPopup(18)"-->
        <div class="col-xs-3 form-group">
            <label for="stb_invoicing_to">Invoicing To</label>
            <a title="Select Custome State" ng-if="updateStabilityOrder.stb_status == 0" href="javascript:;" ng-click="funShowEditInvoicingCountryViewPopup(2)" class="generate cursor-pointer">Select Country</a>
            <select class="form-control"
                name="stb_invoicing_to"                         
                id="stb_invoicing_to"
                ng-model="updateStabilityOrder.stb_invoicing_to.selectedOption"
                ng-options="customerList.name for customerList in customerListInvoicingData track by customerList.id">
                <option value="">Select Customer</option>
            </select>
        </div>
        <!--/Invoicing To-->
    
    </div>
</div>