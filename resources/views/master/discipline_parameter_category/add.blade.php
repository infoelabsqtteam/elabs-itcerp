<div class="row" ng-hide="addMasterFormBladeDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Discipline-Parameter Categories</strong></span>
			</div>
				
			<!--Add form-->
			<form name='erpAddMasterForm' id="erpAddMasterForm" novalidate>							
				<div class="row">
			
					<!--Division Name-->
					<div class="col-xs-2">																
						<label for="ordp_division_id" class="outer-lable">Select Division<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="ordp_division_id"
								id="ordp_division_id"
								ng-model="addMasterModel.ordp_division_id"
								ng-options="item.name for item in divisionDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Division</option>
						</select>
						<span ng-messages="erpAddMasterForm.ordp_division_id.$error" ng-if='erpAddMasterForm.ordp_division_id.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Division is required</span>
						</span>
					</div>
					<!--/Division Name-->
					
					<!--Department Name-->
					<div class="col-xs-2">																
						<label for="ordp_product_category_id" class="outer-lable">Select Department<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="ordp_product_category_id"
								id="ordp_product_category_id"
								ng-model="addMasterModel.ordp_product_category_id"
								ng-change="funGetParameterCatDropdownList(addMasterModel.ordp_product_category_id.id)"
								ng-options="item.name for item in parentCategoryDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Department</option>
						</select>
						<span ng-messages="erpAddMasterForm.ordp_product_category_id.$error" ng-if='erpAddMasterForm.ordp_product_category_id.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>
					<!--/Department Name-->
						
					<!--Discipline Name-->
					<div class="col-xs-2">																
						<label for="ordp_discipline_id" class="outer-lable">Select Discipline<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="ordp_discipline_id"
								id="ordp_discipline_id"
								ng-model="addMasterModel.ordp_discipline_id"
								ng-options="item.name for item in disciplineDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Discipline</option>
						</select>
						<span ng-messages="erpAddMasterForm.ordp_discipline_id.$error" ng-if='erpAddMasterForm.ordp_discipline_id.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Discipline is required</span>
						</span>
					</div>
					<!--/Discipline Name-->
					
					<!--Parameter Category-->
					<div class="col-xs-4">
						<label for="ordp_test_parameter_category_id">Select Parameter Category<em class="asteriskRed">*</em></label>
						<div class="col-sm-12 scrollbar">
							<div class="checkbox mT5" ng-repeat="parameterCatDropdownObj in parameterCatDropdownList track by $index">
								<input
									type="checkbox"
									class="checkbox refreshCheckBox"
									id="ordp_test_parameter_category_id_[[$index]]"
									name="ordp_test_parameter_category_id[]"
									ng-value="parameterCatDropdownObj.id">
									<span class="p_order" ng-bind="parameterCatDropdownObj.name"></span>
							</div>
						</div>	
					</div>
					<!--/Parameter Category-->
					
					<!--save button-->
					<div class="col-xs-2">
						<label for="submit">{{ csrf_field() }}</label>		
						<span ng-if="{{defined('ADD') && ADD}}">
							<button title="Save" ng-disabled="erpAddMasterForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary btn-sm' ng-click='funAddMaster()'>Save</button>
						</span>
						<button title="Reset"  type="button" class="mT26 btn btn-default btn-sm" ng-click="resetButton()" data-dismiss="modal">Reset</button>
					</div>
					<!--/save button-->						
				</div>
			</form>
			<!--Add form-->
		</div>
	</div>
</div>