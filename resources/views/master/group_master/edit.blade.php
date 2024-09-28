<div class="row" ng-hide="editMasterFormBladeDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Group : [[editMasterModel.org_group_name]]</strong></span>
			</div>
				
			<!--Edit form-->
			<form name='erpEditMasterForm' id="erpEditMasterForm" novalidate>							
				<div class="row">
				
					<!--Group Code-->
					<div class="col-xs-2">
						<label for="org_group_code">Group Code<em class="asteriskRed">*</em></label>						   
						<input
							type="text"
							ng-disabled="true"
							class="form-control"
							ng-model="editMasterModel.org_group_code"
							ng-value="default_org_group_code"
							id="org_group_code"
							placeholder="Group Code" />
						<span ng-messages="erpEditMasterForm.org_group_code.$error" ng-if='erpEditMasterForm.org_group_code.$dirty  || erpEditMasterForm.$submitted' role="alert">
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
							ng-model="editMasterModel.org_group_name"
							name="org_group_name" 
							id="org_group_name"
							ng-required='true'
							placeholder="Group Name" />
						<span ng-messages="erpEditMasterForm.org_group_name.$error" ng-if='erpEditMasterForm.org_group_name.$dirty || erpEditMasterForm.$submitted' role="alert">
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
								ng-model="editMasterModel.org_division_id"
								ng-options="item.name for item in divisionDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Division Name</option>
						</select>
						<span ng-messages="erpEditMasterForm.org_division_id.$error" ng-if='erpEditMasterForm.org_division_id.$dirty || erpEditMasterForm.$submitted' role="alert">
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
								ng-model="editMasterModel.org_product_category_id"
								ng-options="item.name for item in parentCategoryDropdownList track by item.id"
								ng-required='true'>
							<option value="">Select Department Name</option>
						</select>
						<span ng-messages="erpEditMasterForm.org_product_category_id.$error" ng-if='erpEditMasterForm.org_product_category_id.$dirty || erpEditMasterForm.$submitted' role="alert">
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
								ng-model="editMasterModel.org_group_status"
								ng-options="item.name for item in activeInactionSelectboxList track by item.id"
								ng-required='true'>
							<option value="">Select Group Status</option>
						</select>
						<span ng-messages="erpEditMasterForm.org_group_status.$error" ng-if='erpEditMasterForm.org_group_status.$dirty || erpEditMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Group Status is required</span>
						</span>
					</div>
					<!--/Group Status-->
					
					<!--Update button-->
					<div class="col-xs-2">
						<label for="submit">{{ csrf_field() }}</label>		
						<span ng-if="{{defined('EDIT') && EDIT}}">
							<input type="hidden" name="org_group_id" ng-value="editMasterModel.org_group_id" ng-model="editMasterModel.org_group_id">
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