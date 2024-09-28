<div class="row" ng-show="showAccreditationCertificateEditForm">
	<div class="panel panel-default">
		<div class="panel-body">
			
			<div class="row header1"><strong class="pull-left headerText">Edit Accreditation Certificate  <b><span ng-model="company_name" ng-bind="company_name"></span></b></strong></div>
			
			<form name='editAccreditationCertificateForm' id="editAccreditationCertificateForm" novalidate><label for="submit">{{ csrf_field() }}</label>
				<div class="row">
					<div class="col-xs-2">
						<label for="accreditation_certificate_branch">Branch<em class="asteriskRed">*</em></label>
						<select
							class="form-control"
							name="oac_division_id"
							ng-model="edit_accreditation_certificate.oac_division_id.selectedOption"
							id="edit_oac_division_id"
							ng-required='true'
							ng-options="item.name for item in divisionsCodeList track by item.id">
						</select>
						<span ng-messages="editAccreditationCertificateForm.oac_division_id.$error" ng-if='editAccreditationCertificateForm.oac_division_id.$dirty  || editAccreditationCertificateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Branch is required</span>
						</span>
					</div>
					
					<div class="col-xs-2 form-group">																
						<label for="product_category_id">Select Department</label>	
						<select
							class="form-control"
							name="oac_product_category_id"
							id="edit_oac_product_category_id"
							ng-required='true'
							ng-model="edit_accreditation_certificate.oac_product_category_id.selectedOption"
							ng-options="item.name for item in parentCategoryList track by item.id">
							<option value="">All Department</option>
						</select>
						<span ng-messages="editAccreditationCertificateForm.oac_product_category_id.$error" ng-if='editAccreditationCertificateForm.oac_product_category_id.$dirty  || editAccreditationCertificateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>

					<div class="col-xs-2">
						<label for="accreditation_certificate_name">Accreditation Certificate Name<em class="asteriskRed">*</em></label>
						<input type="text"
							class="form-control"  
							ng-model="edit_accreditation_certificate.oac_name"
							name="oac_name" 
							id="edit_oac_name"
							ng-required='true'
							maxlength='6'
							placeholder="Enter only 6 digits or characters"/>
						<span ng-messages="editAccreditationCertificateForm.oac_name.$error" ng-if='editAccreditationCertificateForm.oac_name.$dirty  || editAccreditationCertificateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Accreditation certificate name is required</span>
						</span>
					</div>						
						
					<div class="col-xs-2">
						<label for="multi_location_lab">Multi Location Lab:<em class="asteriskRed">*</em></label>						   
						<select
							class="form-control"
							name="oac_multi_location_lab_value"
							id="oac_multi_location_lab_value"
							ng-required='true'
							ng-model="edit_accreditation_certificate.oac_multi_location_lab_value">
							<option value="">Select Multi Location Lab</option>
							<option ng-selected="edit_accreditation_certificate.oac_multi_location_lab_value == values" ng-repeat="values in multiLocationLabArrayList" value="[[values]]">[[values]]</option>
						</select>
						<span ng-messages="editAccreditationCertificateForm.oac_multi_location_lab_value.$error" ng-if='editAccreditationCertificateForm.oac_multi_location_lab_value.$dirty  || editAccreditationCertificateForm.$submitted' role="alert">
						    <span ng-message="required" class="error">Multi loaction lab is required</span>
						</span>
					</div>
						
					<div class="col-xs-2">
						<label for="status">Status:<em class="asteriskRed">*</em></label>						   
						<select
							class="form-control"
							name="oac_status"
							ng-model="edit_accreditation_certificate.oac_status.selectedOption"
							id="edit_oac_status"
							ng-required='true'
							ng-options="item.name for item in accreditationStatusList track by item.id ">
							<option value="">Select Status</option>
						</select>
						<span ng-messages="editAccreditationCertificateForm.oac_status.$error" ng-if='editAccreditationCertificateForm.oac_status.$dirty  || editAccreditationCertificateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status  is required</span>
						</span>
					</div>
					
					<div class="col-xs-2 pull-right mT26">
						<div class="pull-right">							
							<input type='hidden' id='oac_id' name="oac_id" ng-value="oac_id" > 
							<button  ng-disabled="editAccreditationCertificateForm.$invalid" type='submit' id='edit_button' title="Update" class=' btn btn-primary' ng-click='funUpdateAccreditationCertificate();' > Update </button>			
							<button type="button" class="btn btn-default" ng-click="hideEditForm()" title="Close">Close</button>
						</div>
					</div>
				</div>
			</form>		
		</div>
	</div>
</div>