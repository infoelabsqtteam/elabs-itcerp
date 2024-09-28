<div class="row" id="add_form" ng-hide="addFormDiv">	
	<div class="mT5 mB10">
		<!--display Messge Div-->
		@include('includes.alertMessagePopup')
		<!--/display Messge Div-->
	</div>	
	<div class="header">
		<div class="navbar-form navbar-left" role="new">            
			<span class="pull-left"><strong id="form_title">Add Customer</strong></span>
		</div>            
		<div class="navbar-form navbar-right" role="new">
			<strong class="pull-right closeDiv btn btn-primary" style="margin:-4px 0;" ng-click="hideAddForm()">Back</strong>
		</div>
	</div>
	<div class="mT5">
		<form method="POST" id="customerForm" name="customerForm" novalidate>
		
			<div class="row">
				<div class="col-xs-3 form-group">
					<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
					<label for="customer_code">Customer Code<em class="asteriskRed">*</em></label>						   
					<input type="text"
						class="form-control"
						readonly
						ng-model="customer_code"
						ng-bind="customer_code"
						name="customer_code" 
						id="customer_code"
						ng-required='true'
						placeholder="Customer Code" />
					<span ng-messages="customerForm.customer_code.$error" ng-if='customerForm.customer_code.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer code is required</span>
					</span>
				</div>
					
				<div class="col-xs-3 form-group">
					<label for="customer_name">Customer Name<em class="asteriskRed">*</em></label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.customer_name"
						name="customer_name" 
						id="customer_name"
						ng-required='true'
						placeholder="Customer Name" />
					<span ng-messages="customerForm.customer_name.$error" ng-if='customerForm.customer_name.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer name is required</span>
					</span>
				</div>
					
				<div class="col-xs-3 form-group">
					<label for="customer_email">Customer Email<em class="asteriskRed">*</em></label>						   
					<input type="email"
						class="form-control" 
						ng-model="customer.customer_email"
						name="customer_email" 
						id="customer_email"
						ng-required='true'
						placeholder="Customer Email" />
					<span ng-messages="customerForm.customer_email.$error" ng-if='customerForm.customer_email.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer email is required</span>
						<span ng-show="customerForm.customer_email.$error.email" class="error">Enter a valid email!</span>
					</span>
				</div>	
				<div class="col-xs-3 form-group">
					<label for="customer_mobile">Customer Mobile</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.customer_mobile"
						name="customer_mobile" 
						ng-minlength ='10'
						ng-maxlength ='10' 
						id="customer_mobile"
						placeholder="Customer Mobile" />
				</div>
				
				<div class="col-xs-3 form-group">
					<label for="customer_phone">Customer Phone</label>						   
					<input type="text"
					       class="form-control" 
						ng-model="customer.customer_phone"
						name="customer_phone" 
						ng-minlength ='10'
						ng-maxlength ='12' 
						id="customer_phone"
						placeholder="Customer Phone" />
				</div>
				
				<div class="col-xs-3 form-group">
					<label for="customer_address">Customer Address<em class="asteriskRed">*</em></label>
					<textarea rows=1
						class="form-control" 
						ng-model="customer.customer_address"
						name="customer_address" 
						id="customer_address"
						ng-required='true'
						placeholder="Customer Address"/>
					</textarea>
					<span ng-messages="customerForm.customer_address.$error" ng-if='customerForm.customer_address.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer address is required</span>
					</span>
				</div>
				
				<div class="col-xs-3 form-group">
					<label for="customer_name">Customer Pincode<em class="asteriskRed">*</em></label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.customer_pincode"
						name="customer_pincode" 
						id="customer_pincode"
						ng-pattern="/^[0-9]/"
						ng-required='true'
						placeholder="Customer Pincode" />
					<span ng-show="customerForm.customer_pincode.$error.pattern" class="error">Not a valid pincode!</span>
						<span ng-messages="customerForm.customer_pincode.$error" ng-if='customerForm.customer_pincode.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer Pincode is required</span>
					</span>
				</div>
			</div>	
			<hr>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="country_id">Customer Country<em class="asteriskRed">*</em></label>
					<select
						class="form-control"
						name="customer_country"
						ng-model="customer.customer_country"
						ng-change="funGetStateOnCountryChange(customer.customer_country.id)"
						id="country_id"
						ng-required='true'
						ng-options="item.name for item in countryCodeList track by item.id ">
						<option value="">Select Country</option>
					</select>
					<span ng-messages="customerForm.country_id.$error" ng-if='customerForm.country_id.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">State country is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_state">Customer State:<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						name="customer_state"
						ng-model="customer_state"
						id="customer_state"
						ng-required='true'
						ng-change="funGetCityOnStateChange(customer_state.id)"
						ng-options="item.name for item in statesCodeList track by item.id">
						<option value="">Select Customer State</option>
					</select>
					<span ng-messages="customerForm.customer_state.$error" ng-if='customerForm.customer_state.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer state is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_city">Customer City:<em class="asteriskRed">*</em></label>						   
					<select
						class="form-control"
						name="customer_city"
						ng-model="customer.customer_city"
						id="customer_city"
						ng-required='true'
						ng-options="item.name for item in citiesCodesList track by item.id ">
					<option value="">Select Customer City</option>
					</select>
					<span ng-messages="customerForm.customer_city.$error" ng-if='customerForm.customer_city.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer city is required</span>
					</span>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="customer_type">Customer Type<span class="asteriskRed">*</span>:</label>
					<select
						class="form-control"
						ng-model="customer.customer_type"
						name="customer_type"
						id="customer_type"  
						ng-options="item.name for item in customerTypes track by item.id"
						ng-required='true'>
						<option value="">Customer Type</option>
					</select>
					<span ng-messages="customerForm.customer_type.$error" ng-if='customerForm.customer_type.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer Type is required</span>
					</span>
				</div>
					
				<div class="col-xs-3 form-group">
					<label for="billing_type">Billing Type<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						name="billing_type"
						ng-model="customer.billing_type"
						id="billing_type"
						ng-required='true'
						ng-options="item.name for item in billingTypes track by item.id">
						<option value="">Select Billing Type</option>
					</select>
					<span ng-messages="customerForm.billing_type.$error" ng-if='customerForm.billing_type.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Billing type is required</span>
					</span>
				</div>
				
				<!----invoicng type-->
				<div class="col-xs-3 form-group">
					<label for="invoicing_type_id">Invoicing Type<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						name="invoicing_type_id"
						ng-model="customer.invoicing_type_id"
						id="invoicing_type_id"
						ng-required='true'
						ng-options="item.name for item in invoicingTypes track by item.id ">
						<option value="">Select Invoicing Type</option>
					</select>
					<span ng-messages="customerForm.invoicing_type_id.$error" ng-if='customerForm.invoicing_type_id.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Invoicing type is required</span>
					</span>
				</div>
				<!----/invoicng type------>
			</div>
			
			<div class="row">
			
				<!--Sales Territory-->
				<div class="col-xs-3 form-group">
					<label for="division_id">Sales Territory<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						ng-model="customer.division_id"
						id="division_id"
						name="division_id"
						ng-required='true'
						ng-options="item.name for item in divisionsCodeList track by item.id "
						ng-change="funGetSalesExecutives(customer.division_id.id)">
						<option value="">Select Sales Territory</option>
					</select>
					<span ng-messages="customerForm.division_id.$error" ng-if='customerForm.division_id.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Sales Territory is required</span>
					</span>
				</div>
				<!--/Sales Territory-->
				  
				<!--Sale Executive-->
				<div class="col-xs-3 form-group">
					<label for="sale_executive">Sales Executive<em class="asteriskRed">*</em></label>
					<select class="form-control"
						name="sale_executive"
						ng-model="customer.sale_executive"
						id="sale_executive"
						ng-required='true'
						ng-options="salesExecutives.name for salesExecutives in salesExecutiveList track by salesExecutives.id">
						<option value="">Select Sales Executive</option>
					</select>
					<span ng-messages="customerForm.sale_executive.$error" ng-if='customerForm.sale_executive.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Sales Executive is required</span>
					</span>
				</div>
				<!--/Sale Executive-->
				
				<!--Discount Type-->
				<div class="col-xs-3 form-group">
					<label for="discount_type">Discount Type<span class="asteriskRed">*</span>:</label>
					<select class="form-control"
						name="discount_type"
						id="discount_type"  
						ng-change="funGetDiscountType(discount_type.id,'add')"
						ng-options="item.name for item in discountTypes track by item.id"
						ng-model="discount_type"
						ng-required='true'>
						<option value="">Discount Type</option>
					</select>
					<span ng-messages="customerForm.discount_type.$error" ng-if='customerForm.discount_type.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Discount Type is required</span>
					</span>
				</div>
				<!--/Discount Type-->
				
				<!--Discount Value-->
				<div class="col-xs-3 form-group" ng-if="DiscountTypeYes">
					<label for="discount_value">Discount Value<span class="asteriskRed">*</span>:</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.discount_value"
						name="discount_value" 
						id="discount_value"
						ng-required='true'
						placeholder="Discount Value" />
					<span ng-messages="customerForm.discount_value.$error" ng-if='customerForm.discount_value.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Discount Value is required</span>
					</span>
				</div>
				<!--/Discount Value-->
				
				<!--Discount Value-->
				<div class="col-xs-3 form-group" ng-if="DiscountTypeNo">
					<label for="discount_value">Discount Value</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.discount_value"
						name="discount_value" 
						id="discount_value"
						readonly
						placeholder="Discount Value" />
				</div>
				<!--/Discount Value-->
				
				<div class="col-xs-3 form-group">
					<label for="mfg_lic_no">Mfg. Licence Number</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.mfg_lic_no"
						name="mfg_lic_no" 
						id="mfg_lic_no"
						placeholder="Mfg. Licence Number"/>
				</div>
				
				<!--customer vat-cst-->
				<div class="col-xs-3 form-group">
					<label for="customer_vat_cst">VAT-CST</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.customer_vat_cst"
						name="customer_vat_cst" 
						id="customer_vat_cst"
						placeholder="Customer VAT-CST" />
					<span ng-messages="customerForm.customer_vat_cst.$error" ng-if='customerForm.customer_vat_cst.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">VAT-CST is required</span>
					</span>
				</div>
			</div>	
			<hr>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="contact_designate1">Contact Person Designation 1</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.contact_designate1"
						name="contact_designate1" 
						id="contact_designate1"
						placeholder="Contact Person Designation" />
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_name1">Contact Person Name 1<em class="asteriskRed">*</em></label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.contact_name1"
						name="contact_name1" 
						id="contact_name1"
						ng-required='true'
						placeholder="Contact Person Name" />
					<span ng-messages="customerForm.contact_name1.$error" ng-if='customerForm.contact_name1.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Contact person name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label class="width100pc" for="contact_mobile1">
						<span class="txt-left">Contact Person Mobile 1<em ng-if="!customer.disable_contact_mobile1" class="asteriskRed">*</em></span>
						<span class="txt-right font11"><input type="checkbox" id="disable_contact_mobile1" name="disable_contact_mobile1" ng-model="customer.disable_contact_mobile1">Disable Required</span>
					</label>						   
					<input type="number"
						min=0
						class="form-control"
						ng-if="!customer.disable_contact_mobile1"
						ng-model="customer.contact_mobile1"
						name="contact_mobile1" 
						id="contact_mobile1"
						ng-required='true'
						ng-minlength ='10'
						ng-maxlength ='10' 
						placeholder="Contact Person Mobile" />
					<input type="number"
						min=0
						class="form-control" 
						ng-model="customer.contact_mobile1"
						name="contact_mobile1" 
						id="contact_mobile1"
						ng-if="customer.disable_contact_mobile1"
						ng-minlength ='10'
						ng-maxlength ='10' 
						placeholder="Contact Person Mobile" />
					<span ng-messages="customerForm.contact_mobile1.$error" ng-if='customerForm.contact_mobile1.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Contact person mobile is required</span>
						<span ng-message="minlength" class="error">Min. 10 Characters</span>
						<span ng-message="maxlength" class="error">Max. 10 Characters</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_email1">Contact Person Email 1</label>						   
					<input
						type="email"
						class="form-control" 
						ng-model="customer.contact_email1"
						name="contact_email1" 
						id="contact_email1"
						placeholder="Contact Person Email" />						
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_designate2">Account Person Designation</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.contact_designate2"
						name="contact_designate2" 
						id="contact_designate2"
						placeholder="Account Person Designation" />
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_name2">Account Person Name</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.contact_name2"
						name="contact_name2" 
						id="contact_name2"
						placeholder="Account Person Name" />
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_mobile2">Account Person Mobile</label>						   
					<input type="number"
						min=0
						class="form-control" 
						ng-model="customer.contact_mobile2"
						name="contact_mobile2" 
						id="contact_mobile2"
						ng-minlength ='10'
						ng-maxlength ='10'
						placeholder="Account Person Mobile" />
					<span ng-messages="customerForm.contact_mobile2.$error" ng-if='customerForm.contact_mobile2.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Contact person mobile is required</span>
						<span ng-message="minlength" class="error">Min. 10 Characters</span>
						<span ng-message="maxlength" class="error">Max. 10 Characters</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_email2">Account Person Email</label>						   
					<input type="email"
						class="form-control" 
						ng-model="customer.contact_email2"
						name="contact_email2" 
						id="contact_email2"
						placeholder="Account Person Email" />
					<span ng-messages="customerForm.contact_email2.$error" ng-if='customerForm.contact_email2.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Contact person email is required</span>   
					</span>
					<span ng-show="customerForm.contact_email2.$error.email" class="error">Enter a valid email!</span>
				</div>
			</div>	
			<hr>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="ownership_type">Ownership Type</label>						   
					<select class="form-control"
						name="ownership_type" ng-model="customer.ownership_type"
						id="ownership_type"  
						ng-options="item.name for item in ownershipTypes track by item.id">
						<option value="">Select Ownership Type</option>
					</select>
					<span ng-messages="customerForm.ownership_type.$error" ng-if='customerForm.ownership_type.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Ownership Type is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="company_type">Company Type</label>						   
					<select class="form-control"
						name="company_type" ng-model="customer.company_type"
						id="company_type"  
						ng-options="item.name for item in companyTypes track by item.id">
						<option value="">Select Company Type</option>
					</select>
					<span ng-messages="customerForm.company_type.$error" ng-if='customerForm.company_type.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Company Type is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="owner_name">Owner/Prop. Name</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.owner_name"
						name="owner_name" 
						id="owner_name"
						placeholder="Owner/Prop. Name" />
					<span ng-messages="customerForm.owner_name.$error" ng-if='customerForm.owner_name.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Owner name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_pan_no">PAN Number</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.customer_pan_no"
						name="customer_pan_no" 
						id="customer_pan_no"
						placeholder="PAN No" />
					<span ng-messages="customerForm.customer_pan_no.$error" ng-if='customerForm.customer_pan_no.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">PAN no is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_tan_no">TAN Number</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.customer_tan_no"
						name="customer_tan_no" 
						id="customer_tan_no"								
						placeholder="TAN No" />
					<span ng-messages="customerForm.customer_tan_no.$error" ng-if='customerForm.customer_tan_no.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">TAN no is required</span>
					</span>
				</div>
				<!--Customer Priority-->
				<div class="col-xs-3 form-group">
					<label for="discount_type">Customer Priority :</label>
					<select
						class="form-control"
						name="customer_priority_id"
						id="customer_priority_id"
						ng-options="item.sample_priority_name for item in customerPriorityTypes track by item.sample_priority_id"
						ng-model="customer_priority">
						<option value="">Customer Priority Type</option>
					</select>
				</div>
				<!--/Customer Priority-->
			</div>			
			<hr>
			<div class="row">
				<!--Customer GST Category-->
				<div class="col-xs-3 form-group">
					<label for="company_type">Customer GST Category<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						name="customer_gst_category_id"
						ng-model="customer.customer_gst_category_id"
						id="customer_gst_category_id"
						ng-change="funSetGstTypeGstTaxSlabInputTypeField()"
						ng-required='true'
						ng-options="item.name for item in customerGSTCategoriesList track by item.id">
						<option value="">Select Customer GST Category</option>
					</select>
					<span ng-messages="customerForm.customer_gst_category_id.$error" ng-if='customerForm.customer_gst_category_id.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer GST Category is required</span>
					</span>
				</div>
				<!--/Customer GST Category-->
				
				<!--Customer GST Type-->
				<div class="col-xs-3 form-group">
					<label for="company_type">Customer GST Type<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						name="customer_gst_type_id"
						ng-model="customer.customer_gst_type_id"
						id="customer_gst_type_id"
						ng-change="funSetGstNumberValueForInputField(customer.customer_gst_type_id)"
						ng-required='true'
						ng-options="item.name for item in customerGstTypesList track by item.id">
					</select>
					<span ng-messages="customerForm.customer_gst_type_id.$error" ng-if='customerForm.customer_gst_type_id.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer GST Type is required</span>
					</span>
				</div>				
				<!--/Customer GST Type-->
				
				<!--GST Number-->
				<div class="col-xs-3 form-group" ng-if="customer.customer_gst_type_id.id && customer.customer_gst_type_id.id != 4">
					<label for="customer_gst_no">GST Number<em class="asteriskRed">*</em></label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.customer_gst_no"
						name="customer_gst_no" 
						id="customer_gst_no"
						ng-required='true' />
					<span ng-messages="customerForm.customer_gst_no.$error" ng-if='customerForm.customer_gst_no.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">GST No is required</span>
					</span>
				</div>
				<!--/GST Number-->
				
				<!--GST Number-->
				<div class="col-xs-3 form-group" ng-if="!customer.customer_gst_type_id.id || customer.customer_gst_type_id.id == 4">
					<label for="customer_gst_no">GST Number<em class="asteriskRed">*</em></label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.customer_gst_no"
						name="customer_gst_no" 
						id="customer_gst_no"
						ng-required='true'
						style="padding-left:50px;"/>
					<span class="gstNumber" style="left:13px;top:32px;position: absolute;padding-left: 10px;color:#555">GSTN:</span>
					<span ng-messages="customerForm.customer_gst_no.$error" ng-if='customerForm.customer_gst_no.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">GST no is required</span>
					</span>
				</div>
				<!--/GST Number-->
				
				<!--Customer GST Tax Slab Type-->
				<div class="col-xs-3 form-group">
					<label for="company_type">Customer GST Tax Slab Type<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						name="customer_gst_tax_slab_type_id"
						ng-model="customer.customer_gst_tax_slab_type_id"
						id="customer_gst_tax_slab_type_id"
						ng-required='true'
						ng-options="item.name for item in customerGstTaxSlabTypesList track by item.id">
						<option value="">Select Customer GST Tax Slab Type</option>
					</select>
					<span ng-messages="customerForm.customer_gst_tax_slab_type_id.$error" ng-if='customerForm.customer_gst_tax_slab_type_id.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer GST Tax Slab Type is required</span>
					</span>
				</div>
				<!--/Customer GST Tax Slab Type-->
			</div>				
			<hr>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="bank_account_no">Bank Account Number</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.bank_account_no"
						name="bank_account_no" 
						id="bank_account_no"
						placeholder="Bank Account Number" />
					<span ng-messages="customerForm.bank_account_no.$error" ng-if='customerForm.bank_account_no.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Bank account number is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="bank_account_name">Beneficiary Account Name</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.bank_account_name"
						name="bank_account_name" 
						id="bank_account_name"
						placeholder="Beneficiary Account Name" />
					<span ng-messages="customerForm.bank_account_name.$error" ng-if='customerForm.bank_account_name.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Beneficiary account name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="bank_name">Bank Name</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.bank_name"
						name="bank_name" 
						id="bank_name"
						placeholder="Bank Name" />
					<span ng-messages="customerForm.bank_name.$error" ng-if='customerForm.bank_name.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Bank name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="bank_branch_name">Bank Branch Name/Address</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.bank_branch_name"
						name="bank_branch_name" 
						id="bank_branch_name"
						placeholder="Bank Branch Name" />
					<span ng-messages="customerForm.bank_branch_name.$error" ng-if='customerForm.bank_branch_name.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Bank branch name is required</span>
					</span>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="bank_rtgs_ifsc_code">Bank RTGS IFSC Code</label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer.bank_rtgs_ifsc_code"
						name="bank_rtgs_ifsc_code" 
						id="bank_rtgs_ifsc_code"
						placeholder="Bank RTGS IFSC Code" />
					<span ng-messages="customerForm.bank_rtgs_ifsc_code.$error" ng-if='customerForm.bank_rtgs_ifsc_code.$dirty  || customerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Bank RTGS IFSC code is required</span>
					</span>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="mT26 col-xs-12 ">
					<div class="pull-right">
						<input type="hidden" value="[[customer.stateCode]]" name="state_code">
						<label for="submit">{{ csrf_field() }}</label>	
						<button title="Save" type="button" ng-disabled="customerForm.$invalid" id="add_button" class="btn btn-primary" ng-click="addCustomer()">Save</button>
						<button title="Reset" type="button" class="btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>
					</div>
				</div>
			</div>
		</form>	
	</div>
</div> 