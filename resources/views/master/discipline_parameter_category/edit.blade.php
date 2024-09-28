<div class="row" ng-hide="editMasterFormBladeDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Discipline-Parameter Categories :</strong></span>
			</div>
				
			<!--Edit form-->
			<form name='erpEditMasterForm' id="erpEditMasterForm" novalidate>							
				<div class="row">
				
					<!--Division Name-->
					<div class="col-xs-2">																
						<label for="ordp_division_id" class="outer-lable">Select Division<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="ordp_division_id"
								id="ordp_division_id"
								ng-model="editMasterModel.ordp_division_id"
								ng-options="item.name for item in divisionDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Division</option>
						</select>
						<span ng-messages="erpEditMasterForm.ordp_division_id.$error" ng-if='erpEditMasterForm.ordp_division_id.$dirty || erpEditMasterForm.$submitted' role="alert">
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
								ng-model="editMasterModel.ordp_product_category_id"
								ng-change="funGetParameterCatDropdownList(editMasterModel.ordp_product_category_id.id)"
								ng-options="item.name for item in parentCategoryDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Department</option>
						</select>
						<span ng-messages="erpEditMasterForm.ordp_product_category_id.$error" ng-if='erpEditMasterForm.ordp_product_category_id.$dirty || erpEditMasterForm.$submitted' role="alert">
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
								ng-model="editMasterModel.ordp_discipline_id"
								ng-options="item.name for item in disciplineDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Discipline</option>
						</select>
						<span ng-messages="erpEditMasterForm.ordp_discipline_id.$error" ng-if='erpEditMasterForm.ordp_discipline_id.$dirty || erpEditMasterForm.$submitted' role="alert">
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
								    id="edit_ordp_test_parameter_category_id_[[$index]]"
								    ng-checked="editMasterModel.ordpTestParameterCategoryList.indexOf(parameterCatDropdownObj.id) > -1"
								    name="ordp_test_parameter_category_id[]"
								    ng-value="parameterCatDropdownObj.id">
								    <span class="p_order" ng-bind="parameterCatDropdownObj.name"></span>
							</div>
						</div>
					</div>
					<!--/Parameter Category-->
					
					<!--Update button-->
					<div class="col-xs-2">
						<label for="submit">{{ csrf_field() }}</label>		
						<span ng-if="{{defined('EDIT') && EDIT}}">
							<input type="hidden" id="ordp_id" name="ordp_id" ng-value="editMasterModel.ordp_id" ng-model="editMasterModel.ordp_id">
							<button title="Save" ng-disabled="erpEditMasterForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary btn-sm' ng-click='funUpdateMaster()'>Update</button>
						</span>
						<button title="Close" type='button' class='mT26 btn btn-default btn-sm' ng-click='backButton()'>Back</button>
					</div>
					<!--/Update button-->						
				</div>
			</form>
			<!--Edit form-->
		</div>
	</div>
</div>