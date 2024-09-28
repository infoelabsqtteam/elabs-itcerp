<div class="row" ng-hide="IsViewVendorDetail" id="erp_create_vendor_form_div">
    <div class="panel">
		
        <!--search-->
		<div class="row header">        
            <div role="new" class="navbar-form navbar-left">            
                <div><strong id="form_title">Vendor Detail : [[vendorName ? vendorName : '-']]</strong></div>            
            </div>            
            <div role="new" class="navbar-form navbar-right">
                <div style="margin: -5px; padding-right: 9px;">                   
                    <button ng-click="openNewVendorForm()" class="btn btn-primary" id="add_new_vendor_id" type="button">Back</button>
                </div>
            </div>
        </div>
        <!--/search-->
        
        <!--form body-->
		<div class="row panel-body">            
			<form method="POST" role="form" id="erpViewVendorForm" name="erpViewVendorForm" novalidate>				
				
                <!--fields-->
				<div class="row panel-default">
				
					<!-- Vendor Code-->
                    <div class="col-xs-6 form-group view-record">
                        <span for="vendor_code"><span class="leftspan">Vendor Code : </span>[[vendorCode ? vendorCode : '-']]</span>
                    </div>
                    <!-- /Vendor Code-->
								
					<!-- Vendor Code-->
                    <div class="col-xs-6 form-group view-record">
                        <span for="vendor_code"><span class="leftspan">Vendor Code : </span>[[vendorCode ? vendorCode : '-']]</span>
                    </div>
                    <!-- /Vendor Code-->
                    
                    <!-- Vendor Name-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vendor_name"><span class="leftspan">Vendor Name : </span>[[vendorName ? vendorName : '-']]</span>
                    </div>
                    <!-- /Vendor Name-->
                    
                    <!-- Vendor Email-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vendor_email"><span class="leftspan">Vendor Email : </span>[[vendorEmail ? vendorEmail : '-']]</span>
                    </div>
                    <!-- /Vendor Email-->
					
					<!--Vendor Branch-->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-6 form-group view-record">
						<span for="vendor_division"><span class="leftspan">Branch : </span>[[vendorDivisionName ? vendorDivisionName : '-']]</span>
					</div>
                    <!--/Vendor Branch-->
					
					<!--Vendor State-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vendor_state"><span class="leftspan">State : </span>[[venderState ? venderState : '-']]</span>						
					</div>
                    <!--/Vendor State-->
					
					<!--Vendor City-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vendor_city"><span class="leftspan">City : </span>[[vendorCity ? vendorCity : '-']]</span>
					</div>
                    <!--/Vendor City-->
					
					 <!--Vendor Mobile-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vendor_mobile"><span class="leftspan">Vendor Mobile : </span>[[vendorMobile ? vendorMobile : '-']]</span>
					</div>
                    <!--/Vendor Mobile-->
                    
                    <!--Vendor Pincode-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vendor_pincode"><span class="leftspan">Pincode : </span>[[vendorPincode ? vendorPincode : '-']]</span>
					</div>
                    <!--/Vendor Pincode-->
                    
                    <!--Vendor Address-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vendor_pincode"><span class="leftspan">Address : </span>[[vendorAddress ? vendorAddress : '-']]</span>
					</div>
                    <!--/Vendor Address-->
                    
                    <!--Our Code for that vendor-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vendor_cust_code"><span class="leftspan">Our Code for that Vendor : </span>[[vendorCustCode ? vendorCustCode : '-']]</span>
					</div>
                    <!--/Our Code for that vendor-->
                    
                    <!--vendor website-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vendor_website"><span class="leftspan">Vendor Website : </span>[[vendorWebsite ? vendorWebsite : '-']]</span>
					</div>
                    <!--/vendor website-->
                    
                    <!--VAT Number-->
                    <div class="col-xs-6 form-group view-record">
						<span for="vat_no"><span class="leftspan">VAT Number : </span>[[vendorVATNo ? vendorVATNo : '-']]</span>
					</div>
                    <!--/VAT Number-->
                    
                    <!--GST Number-->
                    <div class="col-xs-6 form-group view-record">
						<span for="gst_no"><span class="leftspan">GST Number : </span>[[vendorGSTNo ? vendorGSTNo : '-']]</span>
					</div>
                    <!--/GST Number-->
                    
                    <!--Contact Person Name-->
                    <div class="col-xs-6 form-group view-record">
						<span for="contact_person_name"><span class="leftspan">Contact Person Name : </span>[[contactPersonName ? contactPersonName : '-']]</span>
					</div>
                    <!--/Contact Person Name-->
                    
                    <!--Contact Person email-->
                    <div class="col-xs-6 form-group view-record">
						<span for="contact_person_email"><span class="leftspan">Contact Person Email : </span>[[contactPersonEmail ? contactPersonEmail : '-']]</span>
					</div>
                    <!--/Contact Person email-->
                    
                    <!--Contact Person mobile-->
                    <div class="col-xs-6 form-group view-record">
						<span for="contact_person_Mobile"><span class="leftspan">Contact Person Mobile : </span>[[contactPersonMobile ? contactPersonMobile : '-']]</span>
					</div>
                    <!--/Contact Person mobile-->
                    
                    <!--Credit Days-->
                    <div class="col-xs-6 form-group view-record">
						<span for="credit_days"><span class="leftspan">Credit Days : </span>[[creditDays ? creditDays : '-']]</span>
					</div>
                    <!--/Credit Days-->
                    
                </div>
                <!--/fields-->
                
                <!--button-->                
                <div class="row">
                    <div class="col-xs-12 form-group text-right">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" id="division_id" ng-model="vendor.division_id" name="division_id" value="{{$division_id}}">
						<button ng-click="funEditVendor(vendorId)" class="btn btn-primary btn-sm">Edit Vendor</button>
                    </div>                   
                </div>
                <!--/button-->
                
            </form>
        </div>
        <!--/form body-->
    </div>
</div>