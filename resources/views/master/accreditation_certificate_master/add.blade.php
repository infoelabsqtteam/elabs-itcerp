<div class="row" ng-show="showAccreditationCertificateAddForm">
	<div class="panel panel-default">
		<div class="panel-body">
			
			<div class="row header1"><strong class="pull-left headerText">Add Accreditation Certificate</strong></div>
			
			<form name="accreditationCertificateForm" id="accreditationCertificateForm" novalidate>
				<div class="row">

					<div class="col-xs-2">
						<label for="accreditation_certificate_branch">Branch<em class="asteriskRed">*</em></label>
						<select
							class="form-control"
							name="oac_division_id"
							ng-model="accreditation_certificate.branch"
							id="accreditation_certificate_branch"
							ng-required='true'
							ng-options="item.name for item in divisionsCodeList track by item.id ">
							<option value="">Select Branch</option>
						</select>
						<span ng-messages="accreditationCertificateForm.oac_division_id.$error" ng-if='accreditationCertificateForm.oac_division_id.$dirty  || accreditationCertificateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Branch is required</span>
						</span>
					</div>
					
					<div class="col-xs-2 form-group">																
						<label for="product_category_id">Select Department</label>	
						<select
							class="form-control"
							name="oac_product_category_id"
							id="oac_product_category_id"
							ng-required='true'
							ng-model="accreditation_certificate.oac_product_category_id"
							ng-options="item.name for item in parentCategoryList track by item.id">
							<option value="">All Department</option>
						</select>
						<span ng-messages="accreditationCertificateForm.oac_product_category_id.$error" ng-if='accreditationCertificateForm.oac_product_category_id.$dirty  || accreditationCertificateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>

					<div class="col-xs-2">
						<label for="accreditation_certificate_name">Accreditation Certificate Name<em class="asteriskRed">*</em></label>
						<input type="text"
							class="form-control"  
							ng-model="accreditation_certificate.name"
							name="oac_name" 
							id="accreditation_certificate_name"
							ng-required='true'
							maxlength='6'
							placeholder="Enter only 6 digits or characters"/>
						<span ng-messages="accreditationCertificateForm.oac_name.$error" ng-if='accreditationCertificateForm.oac_name.$dirty  || accreditationCertificateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Accreditation certificate name is required</span>
						</span>
					</div>						
						
					<div class="col-xs-2">
						<label for="multi_location_lab">Multi Location Lab:<em class="asteriskRed">*</em></label>						   
						<select class="form-control"
							name="oac_multi_location_lab_value"
							ng-model="accreditation_certificate.oac_multi_location_lab_value"
							id="oac_multi_location_lab_value"
							ng-required='true'>
							<option value="">Select Multi Location Lab</option>
							<option ng-repeat="values in multiLocationLabArrayList" value="[[values]]" ng-selected="values == 0">[[values]]</option>
						</select>
						<span ng-messages="accreditationCertificateForm.oac_multi_location_lab_value.$error" ng-if='accreditationCertificateForm.oac_multi_location_lab_value.$dirty  || accreditationCertificateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Multi loaction lab is required</span>
						</span>
					</div>
						
					<div class="col-xs-2">
						<label for="status">Status:<em class="asteriskRed">*</em></label>						   
						<select
							class="form-control"
							name="oac_status"
							ng-model="accreditation_certificate.oac_status.selectedOption"
							id="accreditation_certificate_status"
							ng-required='true'
							ng-options="item.name for item in accreditationStatusList track by item.id ">
							<option value="">Select Status</option>
						</select>
						<span ng-messages="accreditationCertificateForm.oac_status.$error" ng-if='accreditationCertificateForm.oac_status.$dirty  || accreditationCertificateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status  is required</span>
						</span>
					</div>
					
					<div class="mT26 col-xs-2 pull-right">
						<div class="pull-right">	
							<label for="submit">{{ csrf_field() }}</label>	
							<button type='button' id='add_button' class='btn btn-primary' ng-click='addAccreditationCertificate();' title="Save"> Save </button>
							<button type='button' id='reset_button' class=' btn btn-default' ng-click='resetForm();' title="Reset"> Reset </button>
						</div>
					</div>
				</div>
			</form>		
		</div>
	</div>
</div>