	<div ng-model="editAlternateFormDiv" ng-hide="editAlternateFormDiv">
		<form name='editAltMethodForm' novalidate>	
			<label for="submit">{{ csrf_field() }}</label>	
			<div class="row">			
				<div class="col-xs-2" ng-if="globalProductCategoryId != 2">
					<label for="alt_equipment_type_id1" class="outer-lable">
						 <span class="filter-lable">Equipment<em class="asteriskRed">*</em></span>
						 <span class="filterCatLink">
							<a title="Search Equipment" ng-hide="searchEquipmentFilterBtn" href="javascript:;" ng-click="showEquipmentDropdownSearchFilter()"><i class="fa fa-filter"></i></a>
						 </span>
						 <span ng-hide="searchEquipmentFilterInput" class="filter-span-col-2">
							<input type="text" class="filter-text" placeholder="Enter keyword" ng-model="searchEquipment.equipmentText"/>
							<button title="Close Filter" type="button" class="close filter-close" ng-click="hideEquipmentDropdownSearchFilter()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						 </span>	
					</label>
					<select class="form-control"
							name="alt_equipment_type_id1"
							ng-model="editAltMethod.alt_equipment_type_id.selectedOption" 
							ng-required='true'
							ng-change="getMethodsList(globalProductCategoryId,editAltMethod.alt_equipment_type_id.selectedOption);setAltEquipmentSelectedOption(editAltMethod.alt_equipment_type_id.selectedOption)"						
							ng-options="item.id as item.name for item in (parameterEquipmentList | filter:searchEquipment.equipmentText) track by item.id ">
						<option value="">Select Equipment</option>
					</select>
					<span ng-messages="editAltMethodForm.alt_equipment_type_id1.$error"
					 ng-if='editAltMethodForm.alt_equipment_type_id1.$dirty  || editAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Equipment is required</span>
					</span>
				</div>	
				<div class="col-xs-2" ng-if="globalProductCategoryId == 2">
					<label for="alt_equipment_type_id1" class="outer-lable">
						 <span class="filter-lable">Equipment<em class="asteriskRed">*</em></span>
						 <span class="filterCatLink">
							<a title="Search Equipment" ng-hide="searchEquipmentFilterBtn" href="javascript:;" ng-click="showEquipmentDropdownSearchFilter()"><i class="fa fa-filter"></i></a>
						 </span>
						 <span ng-hide="searchEquipmentFilterInput" class="filter-span-col-2">
							<input type="text" class="filter-text" placeholder="Enter keyword" ng-model="searchEquipment.equipmentText"/>
							<button title="Close Filter" type="button" class="close filter-close" ng-click="hideEquipmentDropdownSearchFilter()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						 </span>	
					</label>
					<select class="form-control"
							name="alt_equipment_type_id1"
							ng-model="editAltMethod.alt_equipment_type_id.selectedOption" 
							ng-required='true'
							ng-change="setAltEquipmentSelectedOption(editAltMethod.alt_equipment_type_id.selectedOption)"						
							ng-options="item.id as item.name for item in (parameterEquipmentList | filter:searchEquipment.equipmentText) track by item.id ">
						<option value="">Select Equipment</option>
					</select>
					<span ng-messages="editAltMethodForm.alt_equipment_type_id1.$error"
					 ng-if='editAltMethodForm.alt_equipment_type_id1.$dirty  || editAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Equipment is required</span>
					</span>
				</div>
				<div class="col-xs-2" ng-if="!showDescriptionTextarea && globalProductCategoryId == 2" >
					<label for="equipment_type_id" class="outer-lable">
						 <span class="filter-lable">Detector<em class="asteriskRed">*</em></span></label>
					<select class="form-control" 
							name="alt_detector_id1"
							ng-model="editAltMethod.alt_detector_id.selectedOption" 
							ng-required='true'
							ng-change="setAltDetectorSelectedOption(editAltMethod.alt_detector_id.selectedOption)"
							ng-options="item.id as item.name for item in detectorList track by item.id ">
						<option value="">Select Detector</option>
					</select>
						<!---                in case of pharma section- detector is required---------->
						<span ng-messages="editAltMethodForm.alt_detector_id1.$error" 
					 ng-if='editAltMethodForm.alt_detector_id1.$dirty  || editAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Detector is required</span>
						</span>	
						<!---                /in case of pharma section----------->
				</div>
				<div class="col-xs-2" ng-if="!showDescriptionTextarea && globalProductCategoryId != 2"  >
					<label for="equipment_type_id" class="outer-lable">
						 <span class="filter-lable">Detector</span></label>
					<select class="form-control" 
							name="alt_detector_id1"
							ng-change="setAltDetectorSelectedOption(editAltMethod.alt_detector_id.selectedOption)"
							ng-model="editAltMethod.alt_detector_id.selectedOption" 
							ng-options="item.id as item.name for item in detectorList track by item.id ">
						<option value="">Select Detector</option>
					</select>
						<!---                /in case of pharma section----------->
				</div>
				<div class="col-xs-2">
					<label for="alt_method_id1" class="outer-lable">
						 <span class="filter-lable">Method<em class="asteriskRed">*</em></span>
						 <span class="filterCatLink">
							<a title="Search Method" ng-hide="searchMethodFilterBtn" href="javascript:;" ng-click="showMethodDropdownSearchFilter()"><i class="fa fa-filter"></i></a>
						 </span>
						 <span ng-hide="searchMethodFilterInput" class="filter-span-col-2">
							<input type="text" class="filter-text" placeholder="Enter keyword" ng-model="searchMethod.methodText"/>
							<button title="Close Filter" type="button" class="close filter-close" ng-click="hideMethodDropdownSearchFilter()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						 </span>	
					</label>
					<select class="form-control"
							name="alt_method_id1"
							ng-model="editAltMethod.alt_method_id.selectedOption" 
							ng-required='true'  
							ng-change="setAltMethodSelectedOption(editAltMethod.alt_method_id.selectedOption)"
							ng-options="item.id as item.name for item in (methodList | filter:searchMethod.methodText) track by item.id ">
						<option value="">Select Method Name</option>
					</select>
					<span ng-messages="editAltMethodForm.alt_method_id1.$error" 
					 ng-if='editAltMethodForm.alt_method_id1.$dirty  || editAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Method name is required</span>
					</span>
				</div>
				<div class="col-xs-2">
					<label for="alt_claim_dependent1">Claim Dependent<span class="asteriskRed">*</span></label>
						<select class="form-control"
								ng-required='true'  
								name="alt_claim_dependent1"
								id="alt_claim_dependent1"
								ng-options="option.name for option in editAltMethod.altClaimDependent.availableTypeOptions track by option.id"
								ng-model="editAltMethod.altClaimDependent.selectedClaim">
							<option value="">Select Claims Dependent</option>		
						</select>
						<span ng-messages="editAltMethodForm.alt_claim_dependent1.$error" ng-if='editAltMethodForm.alt_claim_dependent1.$dirty  || editAltMethodForm.$submitted' role="alert">
							<span ng-message="required" class="error">Claim Dependent is required</span>
						</span>
				</div>							
				<div class="col-xs-2">
					<label for="alt_time_taken_days1">Time Taken<sup>(In Days)</sup><span class="asteriskRed">*</span></label>
						<input type="number" min=0 class="text form-control" 
									ng-model="editAltMethod.alt_time_taken_days" 
									name="alt_time_taken_days1" 
									id="alt_time_taken_days1" ng-required='true'
									placeholder="Time Taken" />
							<span ng-messages="editAltMethodForm.alt_time_taken_days1.$error" 
							ng-if='editAltMethodForm.alt_time_taken_days1.$dirty  || editAltMethodForm.$submitted' role="alert">
								<span ng-message="required" class="error">Time is required</span>
						</span>
				</div>
				<div class="col-xs-2">
					<label for="alt_time_taken_mins1">Time Taken<sup>(In Hours/Minutes)</sup><span class="asteriskRed">*</span></label>
						<input type="text" class="text form-control" 
									ng-model="editAltMethod.alt_time_taken_mins"
									ng-value="editAltMethod.alt_time_taken_mins" 
									name="alt_time_taken_mins1"  
									ui-date 
									id="alt_time_taken_mins1"
									ng-required='true'						
									placeholder="Time Taken" />
							<span ng-messages="editParaForm.alt_time_taken_mins1.$error" 
							ng-if='editParaForm.alt_time_taken_mins1.$dirty  || editParaForm.$submitted' role="alert">
								<span ng-message="required" class="error">Time is required</span>
						</span>
				</div>
				
			</div>
			<div class="row">
			<div class="col-xs-2">
					<label for="alt_standard_value_type1">Std Value Type<span class="asteriskRed">*</span>:</label>
						<select class="form-control" ng-required='true'  
							name="alt_standard_value_type1" 
							id="alt_standard_value_type1"  
							ng-options="item.name for item in editAltMethod.altdataTypes.availableTypeOptions track by item.id"
							ng-model="editAltMethod.altdataTypes.selectedOption" 
							ng-change="onTypeChange(editAltMethod.altdataTypes.selectedOption.id)">
						<option value="">Std Value Type</option></select>
						<span ng-messages="editAltMethodForm.alt_standard_value_type1.$error" 
						 ng-if='editAltMethodForm.alt_standard_value_type1.$dirty  || editAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Standard value type is required</span>
						</span>
				</div>
				<div class="col-xs-2">
						<label for="alt_standard_value_from1">Standard Value From<span ng-if = "stdValFromToHide" class="asteriskRed">*</span>:</label>						   
							<input type="text" class="form-control" ng-if = "stdValFromToHide"
									ng-model="editAltMethod.alt_standard_value_from"
									name="alt_standard_value_from1" 
									id="alt_standard_value_from1"
									ng-required='true'
									placeholder="Standard Value From" />
							<input type="text" class="form-control" ng-if = "!stdValFromToHide"
									ng-model="editAltMethod.alt_standard_value_from"
									name="alt_standard_value_from1" 
									id="alt_standard_value_from1"
									placeholder="Standard Value From" />		
							<div ng-if = "stdValFromToHide">
								<span ng-messages="editAltMethodForm.alt_standard_value_from1.$error" 
								ng-if='editAltMethodForm.alt_standard_value_from1.$dirty  || editAltMethodForm.$submitted' role="alert">
								<span ng-message="required" class="error">Standard value from is required</span>
								</span>
							</div>
				</div>
				<div class="col-xs-2">
						<label for="alt_standard_value_to1">Standard Value To<span ng-if = "stdValFromToHide" class="asteriskRed">*</span></label>						   
							<input type="text" class="form-control"  ng-if = "stdValFromToHide"
									ng-model="editAltMethod.alt_standard_value_to"
									name="alt_standard_value_to1" 
									id="alt_standard_value_to1"
									ng-required='true'
									placeholder="Standard Value To" />
							<input type="text" class="form-control"  ng-if = "!stdValFromToHide"
									ng-model="editAltMethod.alt_standard_value_to"
									name="alt_standard_value_to1" 
									id="alt_standard_value_to1"
									placeholder="Standard Value To" />
							<div ng-if = "stdValFromToHide">
								<span ng-messages="editAltMethodForm.alt_standard_value_to1.$error" 
								ng-if='editAltMethodForm.alt_standard_value_to1.$dirty  || editAltMethodForm.$submitted' role="alert">
								<span ng-message="required" class="error">Standard value to is required</span>
								</span>
							</div>
				</div>								
				<div class="col-xs-2">
					<label for="alt_cost_price1">Cost Price<span class="asteriskRed">*</span>:</label>						   
						<input type="number" class="form-control" 
								ng-model="editAltMethod.alt_cost_price"
								name="alt_cost_price1" 
								id="alt_cost_price1"
								ng-required='true' min=0
								placeholder="Cost Price" />
						<span ng-messages="editAltMethodForm.alt_cost_price1.$error" 
						ng-if='editAltMethodForm.alt_cost_price1.$dirty || editAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Cost price is required</span>
						</span>
				</div>								
				<div class="col-xs-2">
					<label for="alt_selling_price1">Selling Price<em class="asteriskRed">*</em>:</label>						   
						<input type="number" class="form-control" 
								ng-model="editAltMethod.alt_selling_price"
								name="alt_selling_price1" min=0
								id="alt_selling_price1"
								ng-required='true'
								placeholder="Selling Price" />
						<span ng-messages="editAltMethodForm.alt_selling_price1.$error" 
						ng-if='editAltMethodForm.alt_selling_price1.$dirty  || editAltMethodForm.$submitted' role="alert">
							<span ng-message="required" class="error">Selling price is required</span>
						</span>
				</div>
				<div class="col-xs-2">
						<input type='hidden' name="alt_test_parameter_id1" ng-value='alt_test_parameter_id1' ng-model='alt_test_parameter_id1'> 
						<input type='hidden' name="alt_test_id1" ng-value='alt_test_id1' ng-model='alt_test_id1'> 
						<input type='hidden' name="alt_product_test_dtl_id1" ng-value='alt_product_test_dtl_id1' ng-model='alt_product_test_dtl_id1'> 
						<input type='hidden' name="product_test_param_altern_method_id" ng-value='product_test_param_altern_method_id' ng-model='product_test_param_altern_method_id'> 
						<a ng-show="{{defined('EDIT') && EDIT}}"  href="javascript:;" ng-disabled="editAltMethodForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='updateAlternateMethod()' title="Update"> Update </a>
						<button type='button' class='mT26 btn btn-default' ng-click='cancelEditAlt()' title="Cancel"> Cancel </button>
				</div>
			</div>
	</form>	
	</div>	