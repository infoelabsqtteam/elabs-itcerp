<div class="row" ng-if="addCustomerWiseAssayParametersRateDiv">
	<div class="panel panel-default">		           
		<div class="panel-body">	
			<div class="row header-form">
				<span class="pull-left headerText"><strong>Add Customer Wise Assay Parameters</strong></span>
				<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigateFormPage('list')">Back</button></span>
			</div>			
			<form method="POST" role="form" id="erpAddCustomerWiseAssayParametersRateForm" name="erpAddCustomerWiseAssayParametersRateForm" novalidate>
				<div class="row">
					
					<!--Customer-->
					<div class="col-xs-3 form-group">
						<label for="cir_city_id">Select Customer<em class="asteriskRed">*</em></label>
						<!--<a title="Select Custome State" ng-click="funShowStateCityTreeViewPopup(15)" class='generate cursor-pointer'>Tree View</a>-->
						<a  title="Filter Customer City" class="generate mL5 cursor-pointer" ng-click="funShowCountryStateViewPopup(1)">Select Country</a>

						<select
							class="form-control"
							name="cir_customer_id"							
							id="cir_customer_id"
							ng-required="true"
							ng-model="addCustomerWiseAssayParametersRate.cir_customer_id"
							ng-change="funSetSelectedCustomer(addCustomerWiseAssayParametersRate.cir_customer_id.id);"
							ng-options="customerList.name for customerList in customerListData track by customerList.id">
							<option value="">Select Customer</option>
						</select>
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.cir_customer_id.$error" ng-if="erpAddCustomerWiseAssayParametersRateForm.cir_customer_id.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted" role="alert">
							<span ng-message="required" class="error">Customer is required</span>
						</span>
					</div>
					<!--/Customer-->
					
					<!--Branch-->
					<div class="col-xs-3 form-group">
						<label for="product_category_id">Branch<em class="asteriskRed">*</em></label>
						<select
							class="form-control"
							name="cir_division_id"
							id="cir_division_id"
							ng-model="addCustomerWiseAssayParametersRate.cir_division_id"
							ng-options="item.name for item in divisionsCodeList track by item.id"
							ng-required='true'>
							<option value="">Select Branch</option>
						</select>
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.cir_division_id.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.cir_division_id.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Branch is required</span>
						</span>
					</div>
					<!--/Branch-->
					
					<!--Department-->
					<div class="col-xs-3 form-group">
						<label for="product_category_id">Department<em class="asteriskRed">*</em></label>	
						<select
							class="form-control"
							name="cir_product_category_id"
							id="cir_product_category_id"
							ng-change="funProductCategoryLevelOne(addCustomerWiseAssayParametersRate.cir_product_category_id.id)"
							ng-model="addCustomerWiseAssayParametersRate.cir_product_category_id"
							ng-options="item.name for item in parentCategoryLevelZeroList track by item.id"
							ng-required='true'>
							<option value="">Select Department</option>
						</select>
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.product_category_id.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.product_category_id.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>						
					</div>
					<!--/Department-->
					
					<!--Product Category Level One-->
					<div class="col-xs-3 form-group">
						<label for="cir_p_category_id">Product Category<em class="asteriskRed">*</em></label>	
						<select
							class="form-control"
							name="cir_p_category_id"
							id="cir_p_category_id"
							ng-change="funProductCategoryLevelTwo(addCustomerWiseAssayParametersRate.cir_p_category_id.id)"
							ng-model="addCustomerWiseAssayParametersRate.cir_p_category_id"
							ng-options="item.name for item in productCategoryLevelOneList track by item.id"
							ng-required='true'>
							<option value="">Select Product Category</option>
						</select>
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.cir_p_category_id.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.cir_p_category_id.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>						
					</div>
					<!--/Product Category Level One-->
					
					<!--Product Category Level Two-->
					<div class="col-xs-3 form-group">
						<label for="cir_sub_p_category_id">Sub Product Category<em class="asteriskRed">*</em></label>	
						<select
							class="form-control"
							name="cir_sub_p_category_id"
							id="cir_sub_p_category_id"
							ng-model="addCustomerWiseAssayParametersRate.cir_sub_p_category_id"
							ng-options="item.name for item in productCategoryLevelTwoList track by item.id"
							ng-required='true'>
							<option value="">Select Product Category</option>
						</select>
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.cir_sub_p_category_id.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.cir_sub_p_category_id.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>						
					</div>
					<!--/Product Category Level Two-->
					
					<!--Test Parameter Category-->
					<div class="col-xs-3 form-group">
						<label for="parameter_category_id">Parameter Category<em class="asteriskRed">*</em></label>	
						<select
							class="form-control"
							name="cir_test_parameter_category_id"
							id="cir_test_parameter_category_id"
							ng-model="addCustomerWiseAssayParametersRate.cir_test_parameter_category_id"
							ng-change="funGetParameterListFromParaCategory(addCustomerWiseAssayParametersRate.cir_test_parameter_category_id.id)"
							ng-options="item.name for item in parametersCategoryList track by item.id"
							ng-required='true'>
							<option value="">Select Parameter Category</option>
						</select>
						<span  ng-messages="erpAddCustomerWiseParametersRateForm.cir_test_parameter_category_id.$error" ng-if='erpAddCustomerWiseParametersRateForm.cir_test_parameter_category_id.$dirty || erpAddCustomerWiseParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parameter Category is required</span>
						</span>						
					</div>	
					<!--/Test Parameter Category-->
					
					<!--Parameter-->
					<div class="col-xs-3 form-group" ng-if="globalParameterCategoryID">
						<label for="test_parameter_id">Parameter</label>
						<input
							class="form-control"
							autocomplete="off"					
							ng-model="addCustomerWiseAssayParametersRate.test_parameter_name" 
							ng-change="getAutoSearchAssayParameterMatches(addCustomerWiseAssayParametersRate.test_parameter_name,globalParameterCategoryID);"
							placeholder="Parameter">
						<input type="hidden" name="cir_parameter_id" ng-model="addCustomerWiseAssayParametersRate.test_parameter_id" ng-value="addCustomerWiseAssayParametersRate.test_parameter_id">
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.test_parameter_name.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.test_parameter_name.$dirty  || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parameter is required</span>
						</span>
						<ul ng-if="showAutoSearchParameterList && testParametersList.length" class="autoSearch">
						   <li ng-repeat="testParametersObj in testParametersList" ng-click="funSetAutoSelectedAssayParameter(testParametersObj.id,testParametersObj.name,'add')" ng-bind-html="testParametersObj.name"></li>
						</ul>
						<ul ng-if="showAutoSearchParameterList && !testParametersList.length" class="autoSearch"><li>No Record Found!</li></ul>
					</div>
					<!--/Parameter-->
					
					<!--Equipment-->
					<div class="col-xs-3 form-group">
						<label for="cir_equipment_type_id">Equipment<em class="asteriskRed">*</em></label>
						<select
							class="form-control"
							id="cir_equipment_type_id"
							name="cir_equipment_type_id"
							ng-model="addCustomerWiseAssayParametersRate.cir_equipment_type_id"
							ng-change="funGetDetectorAccToEquipment(addCustomerWiseAssayParametersRate.cir_equipment_type_id.id,parentProductCategoryID)"
							ng-options="item.name for item in parameterEquipmentList track by item.id"
							ng-required='true'>
							<option value="">Select Equipment</option>
						</select>						
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.cir_equipment_type_id.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.cir_equipment_type_id.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Equipment is required</span>
						</span>						
					</div>
					<!--/Equipment-->
					
					<!--Equipment Count-->
					<div class="col-xs-3 form-group">
						<label for="cir_equipment_count">Equipment Count<em class="asteriskRed">*</em></label>
						<input
						    type="text"
						    class="form-control"
						    id="cir_equipment_count"
						    ng-model="addCustomerWiseAssayParametersRate.cir_equipment_count"
						    name="cir_equipment_count"
						    ng-required='true'
						    placeholder="Equipment Count">
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.cir_equipment_count.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.cir_equipment_count.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Equipment Count is required</span>
						</span>	
					</div>
					<!--Equipment Count-->
					
					<!--With or Without Equipment-->
					<div class="col-xs-3 form-group mT25" ng-init="addCustomerWiseAssayParametersRate.is_detector = 2">
						<label for="is_detector">Detector<em class="asteriskRed">*</em></label>
						<label class="radio-inline"><input type="radio" name="cir_is_detector" ng-required='true' ng-model="addCustomerWiseAssayParametersRate.is_detector" ng-click="funCheckDetechtorAvailability(addCustomerWiseAssayParametersRate.is_detector)" ng-value="1">With</label>
						<label class="radio-inline"><input type="radio" name="cir_is_detector" ng-required='true' ng-model="addCustomerWiseAssayParametersRate.is_detector" ng-click="funCheckDetechtorAvailability(addCustomerWiseAssayParametersRate.is_detector)" ng-value="2">Without</label>
					</div>
					<!--/With or Without Equipment-->					
				</div>
				
				<!--with Detector Content Div-->
				<div class="row" ng-if="withDetectorContentDiv">
					
					<!--Detector-->
					<div class="col-xs-3 form-group">
						<label for="cir_equipment_type_id">Detector<em class="asteriskRed">*</em></label>
						<select
							class="form-control"
							id="cir_detector_id"
							name="cir_detector_id"
							ng-model="addCustomerWiseAssayParametersRate.cir_detector_id"
							ng-options="item.id as item.name for item in detectorList track by item.id"
							ng-required='true'>
							<option value="">Select Detector</option>
						</select>						
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.cir_detector_id.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.cir_detector_id.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Detector is required</span>
						</span>						
					</div>
					<!--/Detector-->
					
					<!--Running Time-->
					<div class="col-xs-3 form-group" ng-init="funGetRunningTimeList()">
						<label for="cir_equipment_type_id">Running Time<em class="asteriskRed">*</em></label>
						<select
							class="form-control"
							id="cir_running_time_id"
							name="cir_running_time_id"
							ng-model="addCustomerWiseAssayParametersRate.cir_running_time_id"
							ng-options="item.name for item in runningTimeList track by item.id"
							ng-required='true'>
							<option value="">Select Running Time</option>
						</select>						
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.cir_running_time_id.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.cir_running_time_id.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Running Time is required</span>
						</span>						
					</div>
					<!--Running Time-->
					
					<!--No of Injection-->
					<div class="col-xs-3 form-group">
						<label for="cir_no_of_injection">No. of Injection<em class="asteriskRed">*</em></label>
						<input
						    type="text"
						    class="form-control"
						    id="cir_no_of_injection"
						    ng-model="addCustomerWiseAssayParametersRate.cir_no_of_injection"
						    name="cir_no_of_injection"
						    ng-required='true'
						    placeholder="No. of Injection">
						<span ng-messages="erpAddCustomerWiseAssayParametersRateForm.cir_no_of_injection.$error" ng-if='erpAddCustomerWiseAssayParametersRateForm.cir_no_of_injection.$dirty || erpAddCustomerWiseAssayParametersRateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Running Time is required</span>
						</span>	
					</div>
					<!--/No of Injection-->					
				</div>
				<!--with Detector Content Div-->
				
				<div class="row">				
					<!--Invoicing Rate-->
					<div class="col-xs-3 form-group">
						<label for="invoicing_rate">Rate<em class="asteriskRed">*</em></label>
						<input
						type="text"
						class="form-control"
						ng-required='true'
						name="invoicing_rate"
						id="invoicing_rate"
						ng-model="addCustomerWiseAssayParametersRate.invoicing_rate"
						placeholder="Rate">
					</div>
					<!--/Invoicing Rate-->
				</div>
				
				
				<!--Save Button-->
				<div class="row" ng-if="customerWiseParametersRateAddListing">
				    <div class="col-xs-12 form-group text-right mT10">
					<label for="submit">{{ csrf_field() }}</label>	
					<button type="button" class="btn btn-primary" ng-click="funAddCustomerWiseAssayParametersRate()">Save</button>
					<button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>		
				    </div>
				</div>
				<!--Save Button-->  
			</form>
		</div>	
	</div>	
</div>