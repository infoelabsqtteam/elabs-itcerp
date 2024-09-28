<div class="row" ng-hide="addMasterFormBladeDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Group</strong></span>
			</div>
				
			<!--Add form-->
			<form name='erpAddMasterForm' id="erpAddMasterForm" novalidate>							
				<div class="row">
				
					<!--Group Code-->
					<div class="col-xs-2">
						<span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
						<label for="org_group_code">Group Code<em class="asteriskRed">*</em></label>						   
						<input
							type="text"
							readonly
							class="form-control"
							ng-model="addMasterModel.org_group_code"
							ng-value="default_org_group_code"
							name="org_group_code" 
							id="org_group_code"
							placeholder="Group Code" />
						<span ng-messages="erpAddMasterForm.org_group_code.$error" ng-if='erpAddMasterForm.org_group_code.$dirty  || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Group code is required</span>
						</span>
					</div>
					<!--/Group Code-->
					
					<!--Group Name-->
					<div class="col-xs-2">
						<label for="org_group_name">Group Name<em class="asteriskRed">*</em></label>
						<input
							type="text"
							class="form-control" 
							ng-model="addMasterModel.org_group_name"
							name="org_group_name" 
							id="org_group_name"
							ng-required='true'
							placeholder="Group Name" />
						<span ng-messages="erpAddMasterForm.org_group_name.$error" ng-if='erpAddMasterForm.org_group_name.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Group name is required</span>
						</span>
					</div>
					<!--/Group Name-->
					
					<!--Division Name-->
					<div class="col-xs-2">																
						<label for="org_division_id" class="outer-lable">Division Name<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="org_division_id"
								id="org_division_id"
								ng-model="addMasterModel.org_division_id"
								ng-options="item.name for item in divisionDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Division Name</option>
						</select>
						<span ng-messages="erpAddMasterForm.org_division_id.$error" ng-if='erpAddMasterForm.org_division_id.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Division Name is required</span>
						</span>
					</div>
					<!--/Division Name-->
					
					<!--Department Name-->
					<div class="col-xs-2">																
						<label for="org_product_category_id" class="outer-lable">Department Name<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="org_product_category_id"
								id="org_product_category_id"
								ng-model="addMasterModel.org_product_category_id"
								ng-options="item.name for item in parentCategoryDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Department Name</option>
						</select>
						<span ng-messages="erpAddMasterForm.org_product_category_id.$error" ng-if='erpAddMasterForm.org_product_category_id.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department Name is required</span>
						</span>
					</div>
					<!--/Department Name-->
						
					<!--Group Status-->
					<div class="col-xs-2">																
						<label for="org_group_status" class="outer-lable">Group Status<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="org_group_status"
								id="org_group_status"
								ng-model="addMasterModel.org_group_status"
								ng-options="item.name for item in activeInactionSelectboxList track by item.id"
								ng-required='true'>
							<option value="">Select Group Status</option>
						</select>
						<span ng-messages="erpAddMasterForm.org_group_status.$error" ng-if='erpAddMasterForm.org_group_status.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Group Status is required</span>
						</span>
					</div>
					<!--/Group Status-->
					
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