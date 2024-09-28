<!--Invoice generation Form-->
<div class="row panel panel-default">
    <div class="panel-body">
        <div class="row header1">                
            <span class="pull-left headerText"><strong>Invoice Generation</strong></span>
            <span class="pull-right" style="margin:1px;">
                <button ng-click="navigateInvoiceForm(2,divisionID)" class="btn btn-primary" type="button">Back</button>
            </span>
        </div>                
        <div class="row">
            <form method="POST" role="form" id="erpCreateInvoiceForm" name="erpCreateInvoiceForm" novalidate>
		
		<!--branch-->
		<div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
		    <label for="division_id">Select Branch<em class="asteriskRed">*</em></label>
		    <select
			class="form-control"
			name="division_id"
			id="division_id"
			ng-model="invoices.division_id"
			ng-required="true"
			ng-options="division.name for division in divisionsCodeList track by division.id"
			ng-change="funGetBillingTypeCustomerList()">
			<option value="">Select Branch</option>
		    </select>
		</div>
		<!-- /branch-->
		
		<!--Department-->
		<div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">																
		    <label for="product_category_id">Select Department</label>	
		    <select
			class="form-control"
			name="product_category_id"
			id="product_category_id"
			ng-model="invoices.product_category_id"
			ng-options="item.name for item in parentCategoryList track by item.id"
			ng-change="funGetBillingTypeCustomerList()">
		    <option value="">All Department</option>
		    </select>
		</div>
		<!--/Department-->
		
		<!--Department-->
		<div ng-if="{{$division_id}} > 0" class="col-xs-3 form-group">																
		    <label for="product_category_id">Select Department<em class="asteriskRed">*</em></label>	
		    <select
			class="form-control"
			name="product_category_id"
			id="product_category_id"
			ng-model="invoices.product_category_id"
			ng-required="true"
			ng-options="item.name for item in parentCategoryList track by item.id"
			ng-change="funGetBillingTypeCustomerList()">
		    <option value="">Select Department</option>
		    </select>
		</div>
		<!--/Department-->
                
                <!--Billing Type-->
                <div class="col-xs-3 form-group">                        
                    <label for="billing_type">Billing Types<em class="asteriskRed">*</em></label>
                    <select
			ng-init="funListBillingType();"
			class="form-control"
			name="billing_type"
			ng-model="invoices.billing_type"
			id="billing_type"
			ng-required="true"
			ng-options="billingTypes.name for billingTypes in billingTypeList track by billingTypes.id"
			ng-change="funGetBillingTypeCustomerList()">
                        <option value="">Select Billing Type</option>
                    </select>
                    <span ng-messages="erpCreateInvoiceForm.billing_type.$error" ng-if='erpCreateInvoiceForm.billing_type.$dirty  || erpCreateInvoiceForm.$submitted' role="alert">
                        <span ng-message="required" class="error">Billing Type is required</span>
                    </span>
                </div>
                <!--/Billing Type-->
                
                <!--Customer name-->
                <div class="col-xs-3 form-group" ng-if="billingTypeCustomerList.length">
                    <label for="test_product">Customers<em class="asteriskRed">*</em></label>							
                    <select 
			class="form-control"
			name="customer_id"
			ng-model="invoices.customer_id" 
			id="customer_id"
			ng-required="true"
			ng-options="customers.customer_name for customers in billingTypeCustomerList track by customers.customer_id"
			ng-change="funGetCustomerPurchaseOrderList(invoices.billing_type.id);">
                        <option value="">Select Customer</option>
                    </select>
                    <span ng-messages="erpCreateInvoiceForm.customer_id.$error" ng-if='erpCreateInvoiceForm.customer_id.$dirty  || erpCreateInvoiceForm.$submitted' role="alert">
                        <span ng-message="required" class="error">Customer is required</span>
                    </span>
                </div>
                <!--/Customer name-->
		
		<!-- purchase order list-->
		<div class="col-xs-3" ng-if="purchaseOrderNoList.length && invoices.billing_type.id == 5">
		    <label for="prchase order list">Select PO No.</label>
		    <div class="col-sm-12 scrollbar" id="style-default">
			<div class="checkbox" ng-repeat="purchaseOrderNoObj in purchaseOrderNoList track by $index">
			    <input type="checkbox" class="po_orders_no" id="po_order_[[$index]]" name="po_order[]" ng-model="invoices.po_no_[[$index]]" ng-value="purchaseOrderNoObj.po_no">
			    <span class="p_order" ng-bind="purchaseOrderNoObj.purchaseOrder"></span>
			</div>
		    </div>	
		</div>
		<!-- /purchase order list-->
		
		<!-- purchase order list-->
		<div class="col-xs-3" ng-if="!purchaseOrderNoList.length && invoices.billing_type.id == 5 && invoices.customer_id > 0">
		    <label for="prchase order list">Select PO No.</label>
		    <div class="col-sm-12 scrollbar">PO No. not found.</div>	
		</div>
		<!-- /purchase order list-->
		
		<!--Save Button-->
                <div class="col-xs-3 form-group mT25" ng-if="billingTypeCustomerList.length">
		    <label for="submit">{{ csrf_field() }}</label>
		    <button type="submit" ng-disabled="erpCreateInvoiceForm.$invalid" class="btn btn-primary" ng-click="funDisplayOrdersAccToBillingType()">View Pending Invoices</button>
		    <button type="submit" ng-hide="true" ng-disabled="erpCreateInvoiceForm.$invalid" class="btn btn-primary" ng-click="funDisplayInvoicesAccToBillingType(divisionID)">View Invoices</button>
                </div>
                <!--Save Button-->
                
            </form>
        </div>            
    </div>
</div>
<!--/Invoice generation Form-->
