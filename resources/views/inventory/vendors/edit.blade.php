<div class="row" ng-hide="IsViewVendorEdit" id="erp_create_vendor_form_div">
    <div class="panel">
		
        <!--search-->
		<div class="row header">        
            <div role="new" class="navbar-form navbar-left">            
                <div><strong id="form_title">Update Vendor</strong></div>            
            </div>            
           
        </div>
        <!--/search-->
        
        <!--form body-->
		<div class="row panel-body">            
			<form method="POST" role="form" id="erpUpdateVendorForm" name="erpUpdateVendorForm" novalidate>				
				
                <!--fields-->
				<div class="row panel-default">
				
					<!--Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-6 form-group">
                        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control"
                            name="division_id"
                            id="division_id"
                            ng-model="vendor.edit_division_id.selectedOption"
                            ng-options="division.name for division in divisionsCodeList track by division.division_id">
                            <option value="">Select Branch</option>
                        </select>
                        <span ng-messages="erpUpdateVendorForm.division_id.$error" ng-if="erpUpdateVendorForm.division_id.$dirty || erpUpdateVendorForm.$submitted" role="alert">
                            <span ng-message="required" class="error">Branch is required</span>
                        </span>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
                        <input type="hidden" id="division_id" ng-model="vendor.division_id" name="division_id" value="{{$division_id}}">
                    </div>
                    <!--/Branch -->
								
					<!-- Vendor Code-->
                    <div class="col-xs-6 form-group">
                        <label for="vendor_code">Vendor Code</label>
                        <input readonly type="text" id="vendor_code" name="vendor_code" ng-model="vendor.vendor_code" ng-required="true" class="form-control bgWhite" placeholder="Vendor Code">                        
						<span ng-messages="erpUpdateVendorForm.vendor_code.$error" ng-if="erpUpdateVendorForm.vendor_code.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Vendor Code is required</span>
						</span>
                    </div>
                    <!-- /Vendor Code-->
                    
                    <!-- Vendor Name-->
                    <div class="col-xs-6 form-group">
                        <label for="vendor_name">Vendor Name<em class="asteriskRed">*</em></label>
                        <input type="text" id="vendor_name" name="vendor_name" ng-model="vendor.vendor_name" ng-required="true" class="form-control" placeholder="Vendor Name">
						<span ng-messages="erpUpdateVendorForm.vendor_name.$error" ng-if="erpUpdateVendorForm.vendor_name.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Vendor Name is required</span>
						</span>
                    </div>
                    <!-- /Vendor Name-->
                    
                    <!-- Vendor email-->
                    <div class="col-xs-6 form-group">
                        <label for="vendor_email">Vendor Email</label>
                        <input readonly type="email" id="vendor_email" name="vendor_email" ng-model="vendor.vendor_email" ng-required="true" class="form-control bgWhite" placeholder="Vendor Email">
						<span ng-messages="erpUpdateVendorForm.vendor_email.$error" ng-if="erpUpdateVendorForm.vendor_email.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Vendor Email is required</span>
							<span ng-show="erpCreateVendorForm.vendor_email.$error.email" class="error">Enter a valid email!</span>
						</span>
                    </div>
                    <!-- /Vendor email-->
                    
                    <!--Vendor State-->
                    <div class="col-xs-6 form-group">
						<label for="vendor_state">State<em class="asteriskRed">*</em></label>						   
						<select ng-init="funGetStateOnLoad()" class="form-control" name="vendor_state" ng-model="vendor.vendor_state.selectedOption" id="vendor_state" ng-required="true" ng-change="funGetCityOnStateChange(vendor.vendor_state.id)" ng-options="states.name for states in stateNameCodeList track by states.id">
							<option value="">Select State</option>
						</select>
						<span ng-messages="erpUpdateVendorForm.vendor_state.$error" ng-if="erpUpdateVendorForm.vendor_state.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">State is required</span>
						</span>
					</div>
                    <!--/Vendor State-->
                    
                    <!--Vendor City-->
                    <div class="col-xs-6 form-group">
						<label for="vendor_city">City<em class="asteriskRed">*</em></label>						   
						<select class="form-control" name="vendor_city" ng-model="vendor.vendor_city.selectedOption" id="vendor_city" ng-required="true" ng-options="cities.name for cities in citieNameCodeList track by cities.id">
							<option value="">Select City</option>
						</select>
						<span ng-messages="erpUpdateVendorForm.vendor_city.$error" ng-if="erpUpdateVendorForm.vendor_city.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">City is required</span>
						</span>
					</div>
                    <!--/Vendor City-->
					
					<!--Vendor Mobile-->
                    <div class="col-xs-6 form-group">
						<label for="vendor_mobile">Vendor Mobile<em class="asteriskRed">*</em></label>						   
						<input type="number" class="form-control" ng-model="vendor.vendor_mobile" name="vendor_mobile" ng-minlength ="10" ng-maxlength ="10" id="vendor_mobile" ng-required="true" placeholder="Vendor Mobile" />
						<span ng-messages="erpUpdateVendorForm.vendor_mobile.$error" ng-if="erpUpdateVendorForm.vendor_mobile.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Vendor Mobile is required</span>
							<span ng-message="minlength" class="error">Min. 10 Characters</span>
							<span ng-message="maxlength" class="error">Max. 10 Characters</span>
						</span>
					</div>
                    <!--/Vendor Mobile-->
                    
                    <!--Vendor Pincode-->
                    <div class="col-xs-6 form-group">
						<label for="vendor_pincode">Pincode<em class="asteriskRed">*</em></label>						   
						<input type="number" class="form-control" name="vendor_pincode" ng-model="vendor.vendor_pincode" ng-minlength ="6" ng-maxlength ="6" id="vendor_pincode" ng-required="true" placeholder="Pincode" />
						<span ng-messages="erpUpdateVendorForm.vendor_pincode.$error" ng-if="erpUpdateVendorForm.vendor_pincode.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Pincode is required</span>
							<span ng-message="minlength" class="error">Min. 6 Characters</span>
							<span ng-message="maxlength" class="error">Max. 6 Characters</span>
						</span>
					</div>
                    <!--/Vendor Pincode-->
                    
                    <!--Vendor Address-->
                    <div class="col-xs-6 form-group">
						<label for="vendor_address">Vendor Address<em class="asteriskRed">*</em></label>
						<textarea rows="1" class="form-control" name="vendor_address" ng-model="vendor.vendor_address" id="vendor_address" ng-required="true" placeholder="Address" /></textarea>
						<span ng-messages="erpUpdateVendorForm.vendor_address.$error" ng-if="erpUpdateVendorForm.vendor_address.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Address is required</span>
						</span>
					</div>
                    <!--/Vendor Address-->
				</div>
					
				<div class="row panel-default">
                    
                    <!--Our Code for that vendor-->
                    <div class="col-xs-6 form-group">
						<label for="vendor_cust_code">Our Code for that Vendor</label>
						<input type="text" id="vendor_cust_code" name="vendor_cust_code" ng-model="vendor.vendor_cust_code" class="form-control" placeholder="Our Code for that Vendor">
						<span ng-messages="erpUpdateVendorForm.vendor_cust_code.$error" ng-if="erpUpdateVendorForm.vendor_cust_code.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Our Code for that Vendor is required</span>
						</span>
					</div>
                    <!--/Our Code for that vendor-->
                    
                    <!--vendor website-->
                    <div class="col-xs-6 form-group">
						<label for="vendor_website">Vendor Website</label>
						<input type="text" id="vendor_website" name="vendor_website" ng-model="vendor.vendor_website" class="form-control" placeholder="Vendor Website">
						<span ng-messages="erpUpdateVendorForm.vendor_website.$error" ng-if="erpUpdateVendorForm.vendor_website.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Vendor Website is required</span>
						</span>
					</div>
                    <!--/vendor website-->
                    
                    <!--VAT Number-->
                    <div class="col-xs-6 form-group">
						<label for="vat_no">VAT Number</label>
						<input type="text" id="vat_no" name="vat_no" ng-model="vendor.vat_no" class="form-control" placeholder="VAT Number">
						<span ng-messages="erpUpdateVendorForm.vat_no.$error" ng-if="erpUpdateVendorForm.vat_no.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">VAT Number is required</span>
						</span>
					</div>
                    <!--/VAT Number-->
                    
                    <!--GST Number-->
                    <div class="col-xs-6 form-group">
						<label for="gst_no">GST Number</label>
						<input type="text" id="gst_no" name="gst_no" ng-model="vendor.gst_no" class="form-control" placeholder="GST Number">
						<span ng-messages="erpUpdateVendorForm.gst_no.$error" ng-if="erpUpdateVendorForm.gst_no.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">GST Number is required</span>
						</span>
					</div>
                    <!--/GST Number-->
                    
                    <!--Contact Person Name-->
                    <div class="col-xs-6 form-group">
						<label for="contact_person_name">Contact Person Name</label>
						<input type="text" id="contact_person_name" name="contact_person_name" ng-model="vendor.contact_person_name" class="form-control" placeholder="Contact Person Name">
						<span ng-messages="erpUpdateVendorForm.contact_person_name.$error" ng-if="erpUpdateVendorForm.contact_person_name.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Contact Person Name is required</span>
						</span>
					</div>
                    <!--/Contact Person Name-->
                    
                    <!--Contact Person email-->
                    <div class="col-xs-6 form-group">
						<label for="contact_person_email">Contact Person Email</label>
						<input type="email" id="contact_person_email" name="contact_person_email" ng-model="vendor.contact_person_email" class="form-control" placeholder="Contact Person Email">
						<span ng-messages="erpUpdateVendorForm.contact_person_email.$error" ng-if="erpUpdateVendorForm.contact_person_email.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Contact Person Email is required</span>
							<span ng-show="erpUpdateVendorForm.contact_person_email.$error.email" class="error">Enter a valid email!</span>
						</span>
					</div>
                    <!--/Contact Person email-->
                    
                    <!--Contact Person mobile-->
                    <div class="col-xs-6 form-group">
						<label for="contact_person_mobile">Contact Person Mobile</label>
						<input type="number" class="form-control" name="contact_person_mobile" ng-model="vendor.contact_person_mobile" ng-minlength ="10" ng-maxlength ="10" id="contact_person_mobile" placeholder="Contact Person Mobile" />
						<span ng-messages="erpUpdateVendorForm.contact_person_mobile.$error" ng-if="erpUpdateVendorForm.contact_person_mobile.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Contact Person Mobile is required</span>
							<span ng-message="minlength" class="error">Min. 10 Characters</span>
							<span ng-message="maxlength" class="error">Max. 10 Characters</span>
						</span>
					</div>
                    <!--/Contact Person mobile-->
                    
                    <!--Credit Days-->
                    <div class="col-xs-6 form-group">
						<label for="credit_days">Credit Days</label>
						<input type="text" id="credit_days" name="credit_days" ng-model="vendor.credit_days" class="form-control" placeholder="Credit Days">
						<span ng-messages="erpUpdateVendorForm.credit_days.$error" ng-if="erpUpdateVendorForm.credit_days.$dirty || erpUpdateVendorForm.$submitted" role="alert">
							<span ng-message="required" class="error">Credit Days is required</span>
						</span>
					</div>
                    <!--/Credit Days-->
                    
                </div>
                <!--/fields-->
                
                <!--button-->                
                <div class="row panel-default">
                    <div class="col-xs-12 form-group text-right">
						<label for="submit">{{ csrf_field() }}</label>						
						<input type="hidden" id="vendor_id" ng-model="vendor.vendor_id" name="vendor_id" ng-value="vendor.vendor_id">
						<button type="submit" class="btn btn-primary" ng-disabled="erpUpdateVendorForm.$invalid" ng-click="funUpdateVendor(divisionID)">Update</button>						                        
					    <button ng-click="openNewVendorForm()" class="btn btn-default" id="add_new_vendor_id" title="Close" type="button">Close</button>

					</div>                   
                </div>
                <!--/button-->
                
            </form>
        </div>
        <!--/form body-->
    </div>
</div>