<div class="row" ng-hide="addMasterFormBladeDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add defined test standard</strong></span>
				<span><a title="Back" class="btn btn-primary pull-right mT3 mR5" ng-click="backButton()">Back</a></span>
			</div>
				
			<!--Add form-->
			<form name='erpAddMasterForm' id="erpAddMasterForm" novalidate>							
				<div class="row">
					<!--Division Name-->
					<div class="col-xs-4">																
						<label for="odtsd_branch_id" class="outer-lable">Division Name<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="odtsd_branch_id"
								id="odtsd_branch_id"
								ng-model="addMasterModel.odtsd_branch_id"
								ng-change="funGetTestStandardList(addMasterModel.odtsd_product_category_id.id)"
								ng-options="item.name for item in divisionDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Division Name</option>
						</select>
						<span ng-messages="erpAddMasterForm.odtsd_branch_id.$error" ng-if='erpAddMasterForm.odtsd_branch_id.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Division Name is required</span>
						</span>
					</div>
					<!--/Division Name-->
					
					<!--Department Name-->
					<div class="col-xs-4">																
						<label for="odtsd_product_category_id" class="outer-lable">Department Name<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="odtsd_product_category_id"
								id="odtsd_product_category_id"
								ng-model="addMasterModel.odtsd_product_category_id"
								ng-change="funGetTestStandardList(addMasterModel.odtsd_product_category_id.id)"
								ng-options="item.name for item in parentCategoryDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Department Name</option>
						</select>
						<span ng-messages="erpAddMasterForm.odtsd_product_category_id.$error" ng-if='erpAddMasterForm.odtsd_product_category_id.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department Name is required</span>
						</span>
					</div>
					<!--/Department Name-->
				</div>
				
				<div class="row mT30" ng-if="testStandardCheckboxList.length">
					<div class="col-xs-12 form-group">
						<label for="odtsd_test_standard_id" class="outer-lable">Choose Test Standards<em class="asteriskRed">*</em></label>
						<div class="col-xs-12 custom-scroll">							
							<div class="col-xs-4" ng-repeat="testStandard in testStandardCheckboxList">
								<input
									type="checkbox"
									ng-model="addMasterModel.test_std_id[[testStandard.id]]"
									name="odtsd_test_standard_id[]"
									id="odtsd_test_standard_id_[[testStandard.id]]"
									value="[[testStandard.id]]">&nbsp;&nbsp;
								<label class="text-overflow" title="[[testStandard.name]]" for="text1">[[testStandard.name]]</label>
							</div>
							<span ng-messages="erpAddMasterForm.odtsd_test_standard_id.$error" ng-if='erpAddMasterForm.odtsd_test_standard_id.$dirty || erpAddMasterForm.$submitted' role="alert">
								<span ng-message="required" class="error">Test standard name is required</span>
							</span>
						</div>
					</div>
				</div>
				
				<div class="row pull-right mT10 mR10">							
					<!--save button-->
					<label for="submit">{{ csrf_field() }}</label>		
					<span ng-if="{{defined('ADD') && ADD}}">
						<button title="Save" ng-disabled="erpAddMasterForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary btn-sm' ng-click='funAddMaster()'>Save</button>
					</span>
					<button title="Reset"  type="button" class="mT26 btn btn-default btn-sm" ng-click="resetButton()" data-dismiss="modal">Reset</button>
					<!--/save button-->	
				</div>
			</form>
			<!--Add form-->
		</div>
	</div>
</div>