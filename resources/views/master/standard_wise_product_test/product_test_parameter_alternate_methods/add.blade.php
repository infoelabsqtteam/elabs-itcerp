<div ng-model="addAlternateFormDiv" ng-hide="addAlternateFormDiv" >
	<form name='addAltMethodForm' novalidate>
		<label for="submit">{{ csrf_field() }}</label>			
		<div class="row">						
			<div class="col-xs-2" ng-if="globalProductCategoryId != 2">
				<label for="alt_equipment_type_id" class="outer-lable">
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
						name="alt_equipment_type_id"
						ng-model="addAltMethod.alt_equipment_type_id.selectedOption" 
						ng-required='true' 
						ng-change="getEquipDetectorsList(globalProductCategoryId,addAltMethod.alt_equipment_type_id.selectedOption);getMethodsList(globalProductCategoryId,addAltMethod.alt_equipment_type_id.selectedOption);setAddAltEquipmentSelectedOption(addAltMethod.alt_equipment_type_id.selectedOption);"
						ng-options="item.id as item.name for item in (parameterEquipmentList | filter:searchEquipment.equipmentText) track by item.id ">
					<option value="">Select Equipment</option>
				</select>
				<span ng-messages="addAltMethodForm.alt_equipment_type_id.$error"
				 ng-if='addAltMethodForm.alt_equipment_type_id.$dirty  || addAltMethodForm.$submitted' role="alert">
					<span ng-message="required" class="error">Method name is required</span>
				</span>
			</div>	
			<div class="col-xs-2" ng-if="globalProductCategoryId == 2">
				<label for="alt_equipment_type_id" class="outer-lable">
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
						name="alt_equipment_type_id"
						ng-model="addAltMethod.alt_equipment_type_id.selectedOption" 
						ng-required='true' 
						ng-change="getEquipDetectorsList(globalProductCategoryId,addAltMethod.alt_equipment_type_id.selectedOption);setAddAltEquipmentSelectedOption(addAltMethod.alt_equipment_type_id.selectedOption);"
						ng-options="item.id as item.name for item in (parameterEquipmentList | filter:searchEquipment.equipmentText) track by item.id ">
					<option value="">Select Equipment</option>
				</select>
				<span ng-messages="addAltMethodForm.alt_equipment_type_id.$error"
				 ng-if='addAltMethodForm.alt_equipment_type_id.$dirty  || addAltMethodForm.$submitted' role="alert">
					<span ng-message="required" class="error">Equipment name is required</span>
				</span>
			</div>
			<div class="col-xs-2" ng-if="!showDescriptionTextarea && globalProductCategoryId == 2" >
					<label for="equipment_type_id" class="outer-lable">
						 <span class="filter-lable">Detector<em class="asteriskRed">*</em></span></label>
					<select class="form-control" 
							name="alt_detector_id"
							ng-model="addAltMethod.detector_id" 
							ng-required='true'
							ng-options="item.id as item.name for item in detectorList track by item.id ">
						<option value="">Select Detector</option>
					</select>
						<!---                in case of pharma section- detector is required---------->
						<span ng-messages="addAltMethodForm.alt_detector_id.$error" 
					 ng-if='addAltMethodForm.alt_detector_id.$dirty  || addAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Detector is required</span>
						</span>	
						<!---                /in case of pharma section----------->
				</div>
				<div class="col-xs-2" ng-if="!showDescriptionTextarea && globalProductCategoryId != 2"  >
					<label for="equipment_type_id" class="outer-lable">
						 <span class="filter-lable">Detector</span></label>
					<select class="form-control" 
							name="alt_detector_id"
							ng-model="addAltMethod.detector_id" 
							ng-options="item.id as item.name for item in detectorList track by item.id ">
						<option value="">Select Detector</option>
					</select>
						<!---                /in case of pharma section----------->
				</div>
			<div class="col-xs-2">
				<label for="alt_method_id" class="outer-lable">
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
						name="alt_method_id"
						ng-model="addAltMethod.alt_method_id.selectedOption" 
						ng-required='true' 
						ng-change="setAddAltMethodSelectedOption(addAltMethod.alt_method_id.selectedOption)"
						ng-options="item.id as item.name for item in (methodList | filter:searchMethod.methodText) track by item.id ">
					<option value="">Select Method Name</option>
				</select>
				<span ng-messages="addAltMethodForm.alt_method_id.$error" 
				 ng-if='addAltMethodForm.alt_method_id.$dirty  || addAltMethodForm.$submitted' role="alert">
					<span ng-message="required" class="error">Method name is required</span>
				</span>
			</div>	
				<div class="col-xs-2">
					<label for="alt_claim_dependent">Claim Dependent<span class="asteriskRed">*</span></label>
						<select class="form-control"
								ng-required='true'  
								name="alt_claim_dependent"
								id="alt_claim_dependent"
								ng-options="option.name for option in addAltMethod.altClaimDependent.availableTypeOptions track by option.id"
								ng-model="addAltMethod.altClaimDependent.selectedClaim">
							<option value="">Select Claims Dependent</option>		
						</select>
						<span ng-messages="addAltMethodForm.alt_claim_dependent.$error" ng-if='addAltMethodForm.alt_claim_dependent.$dirty  || addAltMethodForm.$submitted' role="alert">
							<span ng-message="required" class="error">Claim Dependent is required</span>
						</span>
				</div>
					
				<div class="col-xs-2">
					<label for="alt_time_taken_days">Time Taken<sup>(In Days)</sup><span class="asteriskRed">*</span></label>
						<input type="number" min=0 class="p_input_type form-control" 
								ng-model="addAltMethod.alt_time_taken_days" 
								name="alt_time_taken_days" 
								id="alt_time_taken_days"
								ng-required='true'
								placeholder="Time Taken(In Days)"/>
						<span ng-messages="addAltMethodForm.alt_time_taken_days.$error" ng-if='addAltMethodForm.alt_time_taken_days.$dirty  || addAltMethodForm.$submitted' role="alert">
								<span ng-message="required" class="error">Time Taken(In Days) is required</span>
						</span>
				</div>
				<div class="col-xs-2">
					<label for="alt_time_taken_mins">Time Taken<sup>(In Hours:Minutes)</sup><span class="asteriskRed">*</span></label>
						<input type="text" class="text p_input_type form-control" 
									ng-model="addAltMethod.alt_time_taken_mins"
									name="alt_time_taken_mins" ui-date
									id="alt_time_taken_mins"
									ng-required='true'
									placeholder="Time Taken(In Hours:Minutes)"/>
						<span ng-messages="addAltMethodForm.alt_time_taken_mins.$error" ng-if='addAltMethodForm.alt_time_taken_mins.$dirty  || addAltMethodForm.$submitted' role="alert">
							<span ng-message="required" class="error">Time Taken(In Hours:Minutes) is required</span>
						</span>
				</div>
		</div>
		<div class="row">
			<div class="col-xs-2">
				<label for="alt_standard_value_type">Standard Value Type<span class="asteriskRed">*</span>:</label>
					<select class="form-control" ng-required='true'  
						name="alt_standard_value_type"
						id="alt_standard_value_type"  
						ng-options="item.name for item in addAltMethod.altdataTypes.availableTypeOptions track by item.id"
						ng-model="addAltMethod.altdataTypes.selectedOption" 
						ng-change="onTypeChange(addAltMethod.altdataTypes.selectedOption.id)">
					<option value="">Standard Value Type</option></select>
					<span ng-messages="addAltMethodForm.alt_standard_value_type.$error" 
					 ng-if='addAltMethodForm.alt_standard_value_type.$dirty  || addAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Standard value type is required</span>
					</span>
			</div>
			<div class="col-xs-2">
					<label for="alt_standard_value_from">Standard Value From<span ng-if = "stdValFromToHide" class="asteriskRed">*</span>:</label>						   
						<input type="text" class="form-control" ng-if = "stdValFromToHide"
								ng-model="addAltMethod.alt_standard_value_from"
								name="alt_standard_value_from" 
								id="alt_standard_value_from"
								ng-required='true'
								placeholder="Standard Value From" />
						<input type="text" class="form-control" ng-if = "!stdValFromToHide"
								ng-model="addAltMethod.alt_standard_value_from"
								name="alt_standard_value_from" 
								id="alt_standard_value_from"
								placeholder="Standard Value From" />		
							<div ng-if = "stdValFromToHide">
								<span ng-messages="addAltMethodForm.alt_standard_value_from.$error" 
								ng-if='addAltMethodForm.alt_standard_value_from.$dirty  || addAltMethodForm.$submitted' role="alert">
								<span ng-message="required" class="error">Standard value from is required</span>
								</span>	
							</div>	
						
			</div>
			<div class="col-xs-2">
					<label for="alt_standard_value_to">Standard Value To<span ng-if = "stdValFromToHide" class="asteriskRed">*</span></label>						   
						<input type="" class="form-control" ng-if = "stdValFromToHide"
								ng-model="addAltMethod.alt_standard_value_to"
								name="alt_standard_value_to" 
								id="alt_standard_value_to"
								ng-required='true'
								placeholder="Standard Value To" />
						<input type="" class="form-control" ng-if = "!stdValFromToHide"
								ng-model="addAltMethod.alt_standard_value_to"
								name="alt_standard_value_to" 
								id="alt_standard_value_to"
								placeholder="Standard Value To" />
						<div ng-if = "stdValFromToHide">		
							<span ng-messages="addAltMethodForm.alt_standard_value_to.$error" 
						ng-if='addAltMethodForm.alt_standard_value_to.$dirty  || addAltMethodForm.$submitted' role="alert">
							<span ng-message="required" class="error">Standard value to is required</span>
							</span>
						</div>
			</div>								
			<div class="col-xs-2">
				<label for="alt_cost_price">Cost Price<em class="asteriskRed">*</em>:</label>						   
					<input type="number" class="form-control" 
							ng-model="addAltMethod.alt_cost_price"
							name="alt_cost_price" min=0
							id="alt_cost_price"
							ng-required='true'
							placeholder="Cost Price" />
					<span ng-messages="addAltMethodForm.alt_cost_price.$error" 
					ng-if='addAltMethodForm.alt_cost_price.$dirty  || addAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Cost price is required</span>
					</span>
			</div>								
			<div class="col-xs-2">
				<label for="alt_selling_price">Selling Price<em class="asteriskRed">*</em>:</label>						   
					<input type="number" class="form-control" 
							ng-model="addAltMethod.alt_selling_price"
							name="alt_selling_price" 
							id="alt_selling_price" min=0
							ng-required='true' 
							placeholder="Selling Price" />
					<span ng-messages="addAltMethodForm.alt_selling_price.$error" 
					ng-if='addAltMethodForm.alt_selling_price.$dirty  || addAltMethodForm.$submitted' role="alert">
						<span ng-message="required" class="error">Selling price is required</span>
					</span>
			</div>
			<div class="col-xs-2">
				<input type='hidden' name="alt_test_parameter_id" ng-value='alt_test_parameter_id' ng-model='alt_test_parameter_id'> 
				<input type='hidden' name="alt_test_id" ng-value='alt_test_id' ng-model='alt_test_id'> 
				<input type='hidden' name="alt_dtl_id" ng-value='alt_dtl_id' ng-model='alt_dtl_id'>
				<input type="hidden" name="product_category_id" ng-value="globalProductCategoryId">
				<a href="javascript:;" ng-show="{{defined('ADD') && ADD}}"  ng-disabled="addAltMethodForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='saveAlternateMethod()' title="Save Parameters"> Save </a>
				<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetAltMethodForm()" data-dismiss="modal">Reset</button>
			</div>
		</div>
	</form>	
</div>