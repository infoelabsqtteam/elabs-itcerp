<div class="row" ng-hide="editMasterFormBladeDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Analyst Window Settings</strong></span>
				<span><a title="Back" class="btn btn-primary pull-right mT3 mR5" ng-click="backButton()">Back</a></span>
			</div>
				
			<!--Add form-->
			<form name='erpEditMasterForm' id="erpEditMasterForm" novalidate>							
				<div class="row">

					<!--Division Name-->
					<div class="col-xs-6">																
						<label for="oaws_division_id" class="outer-lable">Division Name<em class="asteriskRed">*</em></label>	
						<select class="form-control"
							name="oaws_division_id"
							id="oaws_division_id_edit"
							ng-model="editMasterModel.oaws_division_id.selectedOption"
							ng-options="divisions.name for divisions in divisionDropdownList track by divisions.id">
							ng-required='true'>							
						</select>
						<span ng-messages="erpEditMasterForm.oaws_division_id.$error" ng-if='erpEditMasterForm.oaws_division_id.$dirty || erpEditMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Division Name is required</span>
						</span>
					</div>
					<!--/Division Name-->
					
					<!--Department Name-->
					<div class="col-xs-6">																
						<label for="oaws_product_category_id" class="outer-lable">Department Name<em class="asteriskRed">*</em></label>	
						<select
							class="form-control"
							name="oaws_product_category_id"
							id="oaws_product_category_id_edit"
							ng-model="editMasterModel.oaws_product_category_id.selectedOption"
							ng-options="item.name for item in parentCategoryDropdownList track by item.id"
							ng-required='true'>							
						</select>
						<span ng-messages="erpEditMasterForm.oaws_product_category_id.$error" ng-if='erpEditMasterForm.oaws_product_category_id.$dirty || erpEditMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department Name is required</span>
						</span>
					</div>
					<!--/Department Name-->

				</div>
					
				<div class="row mT30" ng-if="equipmentCheckboxList.length">
					<div class="col-xs-12 form-group">
						<label for="edit_oaws_equipment_type_id" class="outer-lable">Choose Equipments<em class="asteriskRed">*</em></label>
						<div class="col-xs-12 custom-scroll">							
							<div class="col-xs-4" ng-repeat="equipment in equipmentCheckboxList">
								<input
									type="checkbox"
									ng-model="editMasterModel.test_std_id[[equipment.id]]"
									name="oaws_equipment_type_id[]"
									id="edit_oaws_equipment_type_id[[equipment.id]]"
									value="[[equipment.id]]"
									ng-checked="editMasterModel.orsdEquipmentTypeList.indexOf(equipment.id) > -1">
									&nbsp;&nbsp;
									<label class="text-overflow" title="[[equipment.name]]" for="[[equipment.name]]">[[equipment.name]]</label>
							</div>
							<span ng-messages="erpEditMasterForm.oaws_equipment_type_id.$error" ng-if='erpEditMasterForm.oaws_equipment_type_id.$dirty || erpEditMasterForm.$submitted' role="alert">
								<span ng-message="required" class="error">Equipment name is required</span>
							</span>
						</div>
					</div>
				</div>
				
				<div class="row pull-right mT10 mR10">							
					<!--save button-->
					<label for="submit">{{ csrf_field() }}</label>		
					<input type="hidden" name="oaws_id" ng-value="editMasterModel.oaws_id" ng-model="editMasterModel.oaws_id">
					<button title="Save" ng-disabled="erpEditMasterForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary btn-sm' ng-click='funUpdateMaster()'>Save</button>
					<button title="Close" type='button' class='mT26 btn btn-default btn-sm' ng-click='backButton()'>Back</button>
					<!--/save button-->	
				</div>
					
			</form>
			<!--Add form-->
		</div>
	</div>
</div>