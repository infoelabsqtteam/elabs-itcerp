<div id="edit_form" ng-hide="editFormDiv">	
	<div class="mT5 mB10">
		<!--display Messge Div-->
		@include('includes.alertMessagePopup')
		<!--/display Messge Div-->
	</div>	
	<div class="row header">
		<span class="pull-left headerText"><strong>Edit Customer</strong></span>
		<span class="pull-right pull-custom">
			<button type="button" class="btn btn-primary" ng-click='hideEditForm()'>Back</button>
		</span>
	</div>
	<div class="row mT5">
		<form method="POST" name='editCustomerForm' id="editCustomerForm" novalidate>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="customer_code1">Customer Code<em class="asteriskRed">*</em></label>
					<input type="text"
						class="form-control"
						ng-model="customer_code1"
						readonly
						id="customer_code1"
						ng-required='true'
						placeholder="Customer Code" />
					<span ng-messages="editCustomerForm.customer_code1.$error" ng-if='editCustomerForm.customer_code1.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer code is required</span>
					</span>
				</div>
				
				<div class="col-xs-3 form-group">
					<label for="logic_customer_code1">Logic Customer Code<em class="asteriskRed">*</em></label>
					<input type="text"
						class="form-control"
						ng-model="logic_customer_code1"
						id="logic_customer_code1"
						name="logic_customer_code"
						ng-required='true'
						placeholder="Logic Customer Code" />
					<span ng-messages="editCustomerForm.logic_customer_code.$error" ng-if='editCustomerForm.logic_customer_code.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Logic Customer code is required</span>
					</span>
				</div>

				<div class="col-xs-3 form-group">
					<label for="customer_name1">Customer Name<em class="asteriskRed">*</em></label>
					<input type="text"
						class="form-control"
						ng-model="customer_name1"
						name="customer_name"
						id="customer_name1"
						ng-required='true'
						placeholder="Customer Name"
						ng-readonly="{{!defined('IS_ADMIN')}}" />
					<span ng-messages="editCustomerForm.customer_name.$error" ng-if='editCustomerForm.customer_name.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_email1">Customer Email<em class="asteriskRed">*</em></label>
					<input type="email"
						class="form-control"
						ng-model="customer_email1"
						id="customer_email1"
						ng-required='true'
						name="customer_email"
						placeholder="Customer Email" />
					<span ng-messages="editCustomerForm.customer_email1.$error" ng-if='editCustomerForm.customer_email1.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer email is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_mobile1">Customer Mobile</label>
					<input type="text"
						class="form-control"
						ng-model="customer_mobile1"
						name="customer_mobile"
						ng-minlength ='10'
						ng-maxlength ='10'
						id="customer_mobile1"
						placeholder="Customer Mobile" />
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_phone1">Customer Phone</label>
					<input type="text"
						class="form-control"
						ng-model="customer_phone1"
						name="customer_phone"
						ng-minlength ='10'
						ng-maxlength ='12'
						id="customer_phone1"
						placeholder="Customer Phone" />
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_address1">Customer Address<em class="asteriskRed">*</em></label>
					<textarea
						rows=1
						class="form-control"
						ng-model="customer_address1"
						name="customer_address"
						id="customer_address1"
						ng-required='true'
						placeholder="Customer Address"
						ng-readonly="{{!defined('IS_ADMIN')}}">
					</textarea>
					<span ng-messages="editCustomerForm.customer_address.$error" ng-if='editCustomerForm.customer_address.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer address is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_pincode1">Customer Pincode<em class="asteriskRed">*</em></label>
					<input type="text"
						class="form-control"
						ng-model="customer_pincode1"
						name="customer_pincode"
						id="customer_pincode1"
						ng-pattern="/^[0-9]/"
						ng-required='true'
						placeholder="Customer Pincode" />
					<span ng-show="editCustomerForm.customer_pincode.$error.pattern" class="error">Not a valid pincode!</span>
					<span ng-messages="editCustomerForm.customer_pincode.$error" ng-if='editCustomerForm.customer_pincode.$dirty  || editCustomerForm.$submitted' role="alert">
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
						ng-model="customer_country1.selectedOption"
						ng-change="funGetStateOnCountryChange(customer_country1.selectedOption.id)"
						id="country_id1"
						ng-required='true'
						ng-options="item.name for item in countryCodeList track by item.id ">
						<option value="" disabled='true'>Customer Country</option>
					</select>
					<span ng-messages="editCustomerForm.country_id.$error" ng-if='editCustomerForm.country_id.$dirty  || stateForm.$submitted' role="alert">
						<span ng-message="required" class="error">State country is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_state1">Customer State:<em class="asteriskRed">*</em></label>
						<select class="form-control"
							name="customer_state"
							ng-model="customer_state1.selectedOption"
							id="customer_state1"
							ng-required='true'
							ng-change="funGetCityOnStateChange(customer_state1.selectedOption.id)"
							ng-options="item.name for item in statesCodeList track by item.id">
							<option value="" disabled='true'>Select Customer State</option>
						</select>
					<span ng-messages="editCustomerForm.customer_state.$error" ng-if='editCustomerForm.customer_state.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer state is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_city1">Customer City:<em class="asteriskRed">*</em></label>
						<select class="form-control"
							name="customer_city"
							ng-model="customer_city1.selectedOption"
							id="customer_city1"
							ng-required='true'
							ng-options="item.name for item in citiesCodesList track by item.id ">
							<option value="" disabled='true'>Select Customer City</option>
						</select>
					<span ng-messages="editCustomerForm.customer_city.$error" ng-if='editCustomerForm.customer_city.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer city is required</span>
					</span>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="customer_type1">Customer Type<span class="asteriskRed">*</span>:</label>
					<select class="form-control"
						ng-model="customer_type1.selectedOption"
						name="customer_type"
						id="customer_type1"
						ng-options="item.name for item in customerTypes track by item.id"
						ng-required='true'>
						<option value="">Customer Type</option>
					</select>
					<span ng-messages="editCustomerForm.customer_type.$error" ng-if='editCustomerForm.customer_type.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer Type is required</span>
					</span>
				</div>

				<div class="col-xs-3 form-group">
					<label for="billing_type1">Billing Type<em class="asteriskRed">*</em></label>
					<select class="form-control"
							name="billing_type"
							ng-model="billing_type1.selectedOption"
							id="billing_type1"
							ng-required='true'
							ng-options="item.name for item in billingTypes track by item.id">
							<option value="">Select Billing Type</option>
					</select>
					<span ng-messages="editCustomerForm.billing_type.$error" ng-if='editCustomerForm.billing_type.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Billing type is required</span>
					</span>
				</div>

				<!----invoicng type-->
				<div class="col-xs-3 form-group">
					<label for="invoicing_type_id1">Invoicing Type<em class="asteriskRed">*</em></label>
					<select class="form-control"
							name="invoicing_type_id"
							ng-model="invoicing_type_id1.selectedOption"
							id="invoicing_type_id1"
							ng-required='true'
							ng-options="item.name for item in invoicingTypes track by item.id ">
							<option value="">Select Invoicing Type</option>
					</select>
					<span ng-messages="editCustomerForm.invoicing_type_id.$error" ng-if='editCustomerForm.invoicing_type_id.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Invoicing type is required</span>
					</span>
				</div>
				<!----/invoicng type------>

			</div>
			<div class="row">

				<!--Sales Territory-->
				<div class="col-xs-3 form-group" ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
					<label for="division_id1">Sales Territory<em class="asteriskRed">*</em></label>
					<select class="form-control bgWhite"
						ng-model="division_id1.selectedOption"
						id="division_id1"
						name="division_id"
						ng-required='true'
						ng-options="item.name for item in divisionsCodeList track by item.id"
						ng-change="funGetSalesExecutives(division_id1.selectedOption.id)">
						<option value="">Select Sales Territory</option>
					</select>
					<span ng-messages="editCustomerForm.division_id.$error" ng-if='editCustomerForm.division_id.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Sales Territory is required</span>
					</span>
				</div>
				<!--/Sales Territory-->

				<!--Sale Executive-->
				<div class="col-xs-3 form-group" ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
					<label for="sale_executive1">Sales Executive<em class="asteriskRed">*</em></label>
					<select class="form-control bgWhite"
						name="sale_executive"
						ng-model="sale_executive1.selectedOption"
						id="sale_executive1"
						ng-required="true"
						ng-options="salesExecutives.name for salesExecutives in salesExecutiveList track by salesExecutives.id">
						<option value="">Select Sales Executive</option>
					</select>
					<span ng-messages="editCustomerForm.sale_executive.$error" ng-if='editCustomerForm.sale_executive.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Sales Executive is required</span>
					</span>
				</div>
				<!--/Sale Executive-->
				
				<!--Sales Territory-->
				<div class="col-xs-3 form-group" ng-if="{{!defined('IS_ADMIN')}}">
					<label for="division_id1">Sales Territory<em class="asteriskRed">*</em></label>
					<select class="form-control bgWhite"
						ng-model="division_id1.selectedOption"
						id="division_id1"
						name="division_id"
						ng-required='true'
						ng-disabled="true"
						ng-options="item.name for item in divisionsCodeList track by item.id"
						ng-change="funGetSalesExecutives(division_id1.selectedOption.id)">
						<option value="">Select Sales Territory</option>
					</select>
					<span ng-messages="editCustomerForm.division_id.$error" ng-if='editCustomerForm.division_id.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Sales Territory is required</span>
					</span>
				</div>
				<!--/Sales Territory-->

				<!--Sale Executive-->
				<div class="col-xs-3 form-group" ng-if="{{!defined('IS_ADMIN')}}">
					<label for="sale_executive1">Sales Executive<em class="asteriskRed">*</em></label>
					<select class="form-control bgWhite"
						name="sale_executive"
						ng-disabled="true"
						ng-model="sale_executive1.selectedOption"
						id="sale_executive1"
						ng-required="true"
						ng-options="salesExecutives.name for salesExecutives in salesExecutiveList track by salesExecutives.id">
						<option value="">Select Sales Executive</option>
					</select>
					<input type="hidden" name="sale_executive" ng-model="sale_executive_id" ng-value="sale_executive_id">
					<span ng-messages="editCustomerForm.sale_executive.$error" ng-if='editCustomerForm.sale_executive.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Sales Executive is required</span>
					</span>
				</div>
				<!--/Sale Executive-->

				<!--Discount Type-->
				<div class="col-xs-3 form-group">
					<label for="discount_type1">Discount Type<span class="asteriskRed">*</span>:</label>
					<select class="form-control"
						name="discount_type"
						id="discount_type1"
						ng-change="funGetDiscountType(discount_types1.selectedOption.id,'edit')"
						ng-options="item.name for item in discountTypes track by item.id"
						ng-model="discount_types1.selectedOption"
						ng-required='true'>
						<option value="">Discount Type</option>
					</select>
					<span ng-messages="editCustomerForm.discount_type.$error" ng-if='editCustomerForm.discount_type.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Discount Type is required</span>
					</span>
				</div>
				<!--/Discount Type-->

				<!--Discount Value-->
				<div class="col-xs-3 form-group" ng-if="DiscountTypeYes">
					<label for="discount_value1">Discount Value<span class="asteriskRed">*</span>:</label>
					<input type="text"
						class="form-control"
						ng-model="discount_value1"
						name="discount_value"
						id="discount_value1"
						ng-required='true'
						placeholder="Discount Value" />
					<span ng-messages="editCustomerForm.discount_value.$error" ng-if='editCustomerForm.discount_value.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Discount Value is required</span>
					</span>
				</div>
				<!--/Discount Value-->

				<!--Discount Value-->
				<div class="col-xs-3 form-group" ng-if="DiscountTypeNo">
					<label for="discount_value1">Discount Value</label>
					<input type="text"
						class="form-control"
						ng-model="discount_value1"
						name="discount_value"
						id="discount_value1"
						readonly
						placeholder="Discount Value" />
				</div>
				<!--/Discount Value-->
			</div>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="mfg_lic_no1">Mfg. Licence Number</label>
					<input type="text"
						class="form-control"
						ng-model="mfg_lic_no1"
						name="mfg_lic_no"
						id="mfg_lic_no1"
						placeholder="Mfg. Licence Number"/>
				</div>

				<!--customer vat-cst-->
				<div class="col-xs-3 form-group">
					<label for="customer_vat_cst1">VAT-CST</label>
					<input type="text"
						class="form-control"
						ng-model="customer_vat_cst1"
						name="customer_vat_cst"
						id="customer_vat_cst1"
						placeholder="Customer VAT-CST" />
					<span ng-messages="editCustomerForm.customer_vat_cst.$error" ng-if='editCustomerForm.customer_vat_cst.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">VAT-CST is required</span>
					</span>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="contact_designate11">Contact Person Designation 1</label>
					<input type="text"
						class="form-control"
						ng-model="contact_designate11"
						name="contact_designate1"
						id="contact_designate11"
						placeholder="Contact Person Designation" />
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_name11">Contact Person Name 1<em class="asteriskRed">*</em></label>
					<input type="text"
						class="form-control"
						ng-model="contact_name11"
						name="contact_name1"
						id="contact_name11"
						ng-required='true'
						placeholder="Contact Person Name" />
					<span ng-messages="editCustomerForm.contact_name1.$error" ng-if='editCustomerForm.contact_name1.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Contact person name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label class="width100pc" for="contact_mobile11">
						<span class="txt-left">Contact Person Mobile 1<em ng-if="!disable_contact_mobile11" class="asteriskRed">*</em></span>
						<span class="txt-right font11"><input type="checkbox" id="disable_contact_mobile11" name="disable_contact_mobile1" ng-model="disable_contact_mobile11">Disable Required</span>
					</label>
					<input type="number"
						min=0
						class="form-control"
						ng-model="contact_mobile11"
						name="contact_mobile1"
						id="contact_mobile11"
						ng-if="!disable_contact_mobile11"
						ng-required='true'
						ng-minlength ='10'
						ng-maxlength ='10'
						placeholder="Contact Person Mobile" />
					<input type="number"
						min=0
						class="form-control"
						ng-model="contact_mobile11"
						name="contact_mobile1"
						id="contact_mobile11"
						ng-if="disable_contact_mobile11"
						ng-minlength ='10'
						ng-maxlength ='10'
						placeholder="Contact Person Mobile" />
					<span ng-messages="editCustomerForm.contact_mobile1.$error" ng-if='editCustomerForm.contact_mobile1.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Contact person mobile is required</span>
						<span ng-message="minlength" class="error">Min. 10 Characters</span>
						<span ng-message="maxlength" class="error">Max. 10 Characters</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_email11">Contact Person Email 1</label>
					<input type="email"
						class="form-control"
						ng-model="contact_email11"
						name="contact_email1"
						id="contact_email11"
						placeholder="Contact Person Email" />
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_designate22">Account Person Designation</label>
					<input type="text"
						class="form-control"
						ng-model="contact_designate22"
						name="contact_designate2"
						id="contact_designate22"
						placeholder="Account Person Designation" />
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_name22">Account Person Name</label>
					<input type="text"
						class="form-control"
						ng-model="contact_name22"
						name="contact_name2"
						id="contact_name22"
						placeholder="Account Person Name" />
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_mobile22">Account Person Mobile</label>
					<input type="number"
						min=0
						class="form-control"
						ng-model="contact_mobile22"
						name="contact_mobile2"
						id="contact_mobile22"
						ng-minlength ='10'
						ng-maxlength ='10'
						placeholder="Account Person Mobile" />
					<span ng-messages="editCustomerForm.contact_mobile2.$error" ng-if='editCustomerForm.contact_mobile2.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Account Person Mobile is required</span>
						<span ng-message="minlength" class="error">Min. 10 Characters</span>
						<span ng-message="maxlength" class="error">Max. 10 Characters</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="contact_email22">Account Person Email</label>
					<input type="email"
						class="form-control"
						ng-model="contact_email22"
						name="contact_email2"
						id="contact_email22"
						placeholder="Account Person Email" />
					<span ng-messages="editCustomerForm.contact_email2.$error" ng-if='editCustomerForm.contact_email2.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Account Person Email is required</span>
						<span ng-show="editCustomerForm.contact_email2.$error.email" class="error">Enter a valid email!</span>
					</span>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="ownership_type1">Ownership Type</label>
					<select class="form-control"
						name="ownership_type"
						ng-model="ownership_type1.selectedOption"
						id="ownership_type1"
						ng-options="item.name for item in ownershipTypes track by item.id">
						<option value="">Select Ownership Type</option>
					</select>
					<span ng-messages="editCustomerForm.ownership_type.$error" ng-if='editCustomerForm.ownership_type.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Ownership Type is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="company_type1">Company Type</label>
					<select class="form-control"
						name="company_type"
						ng-model="company_type1.selectedOption"
						id="company_type1"
						ng-options="item.name for item in companyTypes track by item.id">
						<option value="">Select Company Type</option>
					</select>
					<span ng-messages="editCustomerForm.company_type.$error" ng-if='editCustomerForm.company_type.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Company Type is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="owner_name1">Owner/Prop. Name</label>
					<input type="text"
						class="form-control"
						ng-model="owner_name1"
						name="owner_name"
						id="owner_name1"
						placeholder="Owner/Prop. Name" />
					<span ng-messages="editCustomerForm.owner_name.$error" ng-if='editCustomerForm.owner_name.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Owner name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_pan_no1">PAN Number</label>
					<input type="text"
						class="form-control"
						ng-model="customer_pan_no1"
						name="customer_pan_no"
						id="customer_pan_no1"
						placeholder="PAN No" />
					<span ng-messages="editCustomerForm.customer_pan_no.$error" ng-if='editCustomerForm.customer_pan_no.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">PAN no is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="customer_tan_no1">TAN Number</label>
					<input type="text"
						class="form-control"
						ng-model="customer_tan_no1"
						name="customer_tan_no"
						id="customer_tan_no1"
						placeholder="TAN No" />
					<span ng-messages="editCustomerForm.customer_tan_no.$error" ng-if='editCustomerForm.customer_tan_no.$dirty  || editCustomerForm.$submitted' role="alert">
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
						ng-model="customer_priority1.selectedOption">
						<option value="">Customer Priority Type</option>
					</select>
				</div>
				<!--Customer Priority-->
			</div>
			<hr>
			<div class="row">
				<!--Customer GST Category-->
				<div class="col-xs-3 form-group">
					<label for="company_type">Customer GST Category<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						name="customer_gst_category_id"
						ng-model="customer_gst_category_id1"
						id="customer_gst_category_id"
						ng-change="funEditSetGstTypeGstTaxSlabInputTypeField()"
						ng-required='true'
						ng-options="item.name for item in customerGSTCategoriesList track by item.id">
						<option value="">Select Customer GST Category</option>
					</select>
					<span ng-messages="editCustomerForm.customer_gst_category_id.$error" ng-if='editCustomerForm.customer_gst_category_id.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer GST Category is required</span>
					</span>
				</div>
				<!--/Customer GST Category-->
				
				<!--Customer GST Type-->
				<div class="col-xs-3 form-group">
					<label for="company_type">Customer GST Type<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						name="customer_gst_type_id"
						ng-model="customer_gst_type_id1"
						id="customer_gst_type_id1"
						ng-change="funEditSetGstNumberValueForInputField(customer_gst_type_id1)"
						ng-required='true'
						ng-options="item.name for item in customerGstTypesList track by item.id">
					</select>
					<span ng-messages="editCustomerForm.customer_gst_type_id.$error" ng-if='editCustomerForm.customer_gst_type_id.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer GST Type is required</span>
					</span>
				</div>				
				<!--/Customer GST Type-->
				
				<!--GST Number-->
				<div class="col-xs-3 form-group" ng-if="customer_gst_type_id1.id && customer_gst_type_id1.id != 4">
					<label for="customer_gst_no">GST Number<em class="asteriskRed">*</em></label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer_gst_no1"
						name="customer_gst_no" 
						id="customer_gst_no1"
						ng-required='true' />
					<span ng-messages="editCustomerForm.customer_gst_no.$error" ng-if='editCustomerForm.customer_gst_no.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">GST No is required</span>
					</span>
				</div>
				<!--/GST Number-->
				
				<!--GST Number-->
				<div class="col-xs-3 form-group" ng-if="!customer_gst_type_id1.id || customer_gst_type_id1.id == 4">
					<label for="customer_gst_no">GST Number<em class="asteriskRed">*</em></label>						   
					<input type="text"
						class="form-control" 
						ng-model="customer_gst_no1"
						name="customer_gst_no" 
						id="customer_gst_no1"
						ng-required='true'
						style="padding-left:50px;"/>
					<span class="gstNumber" style="left:13px;top:32px;position: absolute;padding-left: 10px;color:#555">GSTN:</span>
					<span ng-messages="editCustomerForm.customer_gst_no.$error" ng-if='editCustomerForm.customer_gst_no.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">GST no is required</span>
					</span>
				</div>
				<!--/GST Number-->
				
				<!--Customer GST Tax Slab Type-->
				<div class="col-xs-3 form-group">
					<label for="company_type">Customer GST Tax Slab Type<em class="asteriskRed">*</em></label>						   
					<select class="form-control"
						name="customer_gst_tax_slab_type_id"
						ng-model="customer_gst_tax_slab_type_id1"
						id="customer_gst_tax_slab_type_id1"
						ng-required='true'
						ng-options="item.name for item in customerGstTaxSlabTypesList track by item.id">
						<option value="">Select Customer GST Tax Slab Type</option>
					</select>
					<span ng-messages="editCustomerForm.customer_gst_tax_slab_type_id.$error" ng-if='editCustomerForm.customer_gst_tax_slab_type_id.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Customer GST Tax Slab Type is required</span>
					</span>
				</div>
				<!--/Customer GST Tax Slab Type-->
			</div>
			<hr>
			<div class="row">
				<div class="col-xs-3 form-group">
					<label for="bank_account_no1">Bank Account Number</label>
					<input type="text"
						class="form-control"
						ng-model="bank_account_no1"
						name="bank_account_no"
						id="bank_account_no1"
						placeholder="Bank Account Number" />
					<span ng-messages="editCustomerForm.bank_account_no.$error" ng-if='editCustomerForm.bank_account_no.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Bank account number is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="bank_account_name1">Beneficiary Account Name</label>
					<input type="text"
						class="form-control"
						ng-model="bank_account_name1"
						name="bank_account_name"
						id="bank_account_name1"
						placeholder="Beneficiary Account Name" />
					<span ng-messages="editCustomerForm.bank_account_name.$error" ng-if='editCustomerForm.bank_account_name.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Beneficiary account name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="bank_name1">Bank Name</label>
					<input type="text"
						class="form-control"
						ng-model="bank_name1"
						name="bank_name"
						id="bank_name1"
						placeholder="Bank Name" />
					<span ng-messages="editCustomerForm.bank_name.$error" ng-if='editCustomerForm.bank_name.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Bank name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="bank_branch_name1">Bank Branch Name/Address</label>
					<input type="text"
						class="form-control"
						ng-model="bank_branch_name1"
						name="bank_branch_name"
						id="bank_branch_name1"
						placeholder="Bank Branch Name" />
					<span ng-messages="editCustomerForm.bank_branch_name.$error" ng-if='editCustomerForm.bank_branch_name.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Bank branch name is required</span>
					</span>
				</div>
				<div class="col-xs-3 form-group">
					<label for="bank_rtgs_ifsc_code1">Bank RTGS IFSC Code</label>
					<input type="text"
						class="form-control"
						ng-model="bank_rtgs_ifsc_code1"
						name="bank_rtgs_ifsc_code"
						id="bank_rtgs_ifsc_code1"
						placeholder="Bank RTGS IFSC Code" />
					<span ng-messages="editCustomerForm.bank_rtgs_ifsc_code.$error" ng-if='editCustomerForm.bank_rtgs_ifsc_code.$dirty  || editCustomerForm.$submitted' role="alert">
						<span ng-message="required" class="error">Bank RTGS IFSC code is required</span>
					</span>
				</div>
			</div>
			<hr>
			<div class="row mT10 col-xs-12">
				<div class="pull-right">
					<label for="submit">{{ csrf_field() }}</label>
					<input type="hidden" name="contact_id" ng-model="contact_id" ng-value="contact_id">
					<input type="hidden" name="customer_id" ng-model="customer_id" ng-value="customer_id">
					<input type="hidden" value="[[state_code1]]" name="state_code">
					<button title="Update" type="button" ng-disabled="editCustomerForm.$invalid" id="edit_button" class="btn btn-primary" ng-click="updateCustomer()">Update</button>
					<button title="Close"  type='button' class='btn btn-default' ng-click='hideEditForm()'>Close</button>
				</div>
			</div>
		</form>
	</div>
</div>
