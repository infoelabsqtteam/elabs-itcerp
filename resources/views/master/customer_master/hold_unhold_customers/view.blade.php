<div class="row" ng-hide="viewCustomerDiv">
	<div class="row header">
		<strong class="pull-left headerText">Customer Details</strong>	
		<strong class="pull-right closeDiv btn btn-primary" style="margin-top: 3px;" ng-click="hideAddForm()">Back</strong>	
	</div>
		<div class="row" style="margin-top:8px!important;">			
			<div class="col-xs-3">
				<label>Customer Code:</label>
					<span class="color-black" 
						  ng-bind="view_customer_code" 
						  ng-model="view_customer_code">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Customer Name:</label>
					<span class="color-black" 
						  ng-bind="view_customer_name" 
						  ng-model="view_customer_name">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Customer Email:</label>
					<span class="color-black" 
						  ng-bind="view_customer_email" 
						  ng-model="view_customer_email">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Customer Mobile:</label>
					<span class="color-black" 
						  ng-bind="view_customer_mobile" 
						  ng-model="view_customer_mobile">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Customer Phone:</label>
					<span class="color-black" 
						  ng-bind="view_customer_phone" 
						  ng-model="view_customer_phone">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Customer Address:</label>
					<span class="color-black" 
						  ng-bind="view_customer_address" 
						  ng-model="view_customer_address">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Customer State:</label>
					<span class="color-black" 
						  ng-bind="view_customer_state" 
						  ng-model="view_customer_state">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Customer City:</label>
					<span class="color-black" 
						  ng-bind="view_customer_city" 
						  ng-model="view_customer_city">
					</span>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-3">
				<label>Customer Type:</label>
					<span class="color-black" 
						  ng-bind="view_customer_type" 
						  ng-model="view_customer_type">
					</span>
			</div>	
			<div class="col-xs-3">
				<label>Billing Type:</label>
					<span class="color-black" 
						  ng-bind="view_billing_type" 
						  ng-model="view_billing_type">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Invoicing Type:</label>
					<span class="color-black" 
						  ng-bind="view_invoicing_type" 
						  ng-model="view_invoicing_type">
					</span>
			</div>	
			<div class="col-xs-3">
				<label>Sales Executive:</label>
					<span class="color-black" 
						  ng-bind="view_sale_executive" 
						  ng-model="view_sale_executive">
					</span>
			</div>
			
			<div class="col-xs-3">
				<label>Discount Type:</label>
					<span class="color-black" 
						  ng-bind="view_discount_type" 
						  ng-model="view_discount_type">
					</span>
			</div>				
			<div class="col-xs-3">
				<label>Discount Value:</label>
					<span class="color-black" 
						  ng-bind="view_discount_value" 
						  ng-model="view_discount_value">
					</span>
			</div>					
			<div class="col-xs-3">
				<label>Mfg. Licence Number:</label>
					<span class="color-black" 
						  ng-bind="view_mfg_lic_no" 
						  ng-model="view_mfg_lic_no">
					</span>
			</div>						
			<div class="col-xs-3">
				<label>VAT-CST:</label>
					<span class="color-black" 
						  ng-bind="view_customer_vat_cst" 
						  ng-model="view_customer_vat_cst">
					</span>
			</div>	
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-3">
				<label>Contact Person Designation 1:</label>
					<span class="color-black" 
						  ng-bind="view_contact_designate1" 
						  ng-model="view_contact_designate1">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Contact Person Name 1:</label>
					<span class="color-black" 
						  ng-bind="view_contact_name1" 
						  ng-model="view_contact_name1">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Contact Person Mobile 1:</label>
					<span class="color-black" 
						  ng-bind="view_contact_mobile1" 
						  ng-model="view_contact_mobile1">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Contact Person Email 1:</label>
					<span class="color-black" 
						  ng-bind="view_contact_email1" 
						  ng-model="view_contact_email1">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Contact Person Designation 2:</label>
					<span class="color-black" 
						  ng-bind="view_contact_designate2" 
						  ng-model="view_contact_designate2">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Contact Person Name 2:</label>
					<span class="color-black" 
						  ng-bind="view_contact_name2" 
						  ng-model="view_contact_name2">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Contact Person Mobile 2:</label>
					<span class="color-black" 
						  ng-bind="view_contact_mobile2" 
						  ng-model="view_contact_mobile2">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Contact Person Email 2:</label>
					<span class="color-black" 
						  ng-bind="view_contact_email2" 
						  ng-model="view_contact_email2">
					</span>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-3">
				<label>Ownership Type:</label>
					<span class="color-black" 
						  ng-bind="view_ownership_type" 
						  ng-model="view_ownership_type">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Company Type:</label>
					<span class="color-black" 
						  ng-bind="view_company_type" 
						  ng-model="view_company_type">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Owner/Prop. Name:</label>
					<span class="color-black" 
						  ng-bind="view_owner_name" 
						  ng-model="view_owner_name">
					</span>
			</div>
			<div class="col-xs-3">
				<label>PAN Number:</label>
					<span class="color-black" 
						  ng-bind="view_customer_pan_no" 
						  ng-model="view_customer_pan_no">
					</span>
			</div>
			<div class="col-xs-3">
				<label>TAN Number:</label>
					<span class="color-black" 
						  ng-bind="view_customer_tan_no" 
						  ng-model="view_customer_tan_no">
					</span>
			</div>
			<div class="col-xs-3">
				<label>GST Number:</label>
					<span class="color-black" 
						  ng-bind="view_customer_gst_no" 
						  ng-model="view_customer_gst_no">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Customer Priority:</label>
					<span class="color-black" 
						  ng-bind="view_customer_priority" 
						  ng-model="view_customer_priority">
					</span>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-3">
				<label>Bank Account Number:</label>
					<span class="color-black" 
						  ng-bind="view_bank_account_no" 
						  ng-model="view_bank_account_no">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Beneficiary Account Name:</label>
					<span class="color-black" 
						  ng-bind="view_bank_account_name" 
						  ng-model="view_bank_account_name">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Bank Name:</label>
					<span class="color-black" 
						  ng-bind="view_bank_name" 
						  ng-model="view_bank_name">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Bank Branch Name/Address:</label>
					<span class="color-black" 
						  ng-bind="view_bank_branch_name" 
						  ng-model="view_bank_branch_name">
					</span>
			</div>
			<div class="col-xs-3">
				<label>Bank RTGS IFSC Code:</label>
					<span class="color-black" 
						  ng-bind="view_bank_rtgs_ifsc_code" 
						  ng-model="view_bank_rtgs_ifsc_code">
					</span>
			</div>
		</div>
	</div>		
</div>