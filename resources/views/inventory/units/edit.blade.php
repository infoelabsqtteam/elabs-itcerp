<div class="row" ng-hide="hideUnitEditForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Unit</strong></span>
			</div>
			<form name='editUnitForm' id="edit_unit_form" novalidate>
				<div class="row">
					<div class="col-xs-3">
					   <div class="">     
						<label for="unit_code1">Unit Code<em class="asteriskRed">*</em></label>						   
							<input readonly type="text" class="form-control"  ng-model="unit_code_val"
									id="unit_code1"
									name="unit_code1"
									placeholder="Unit Code" />
					   </div>
					</div>
					<div class="col-xs-3">
					   <div class="">
						<label for="unit_name1">Unit Name<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control"  
									name="unit_name1" ng-model="unit_name_val"
									ng-required='true'
									placeholder="Unit Name" />
							<span ng-messages="editUnitForm.unit_name1.$error" 
							 ng-if='editUnitForm.unit_name1.$dirty  || editUnitForm.$submitted' role="alert">
								<span ng-message="required" class="error">Unit name is required</span>
							</span>
					   </div>
					</div>
					<input type="hidden"
							name="unit_name_old" 
							ng-model="unit_name_old"
							ng-value="unit_name_old"
							placeholder="Unit Name Old" />
					<div class="col-xs-3">
					   <div class="">
						<label for="unit_desc1">Unit Description<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control" 
									ng-model="unit_desc_val"
									name="unit_desc1" 
									id="unit_desc1"
									ng-required='true'
									placeholder="Unit Description" />
							<span ng-messages="editUnitForm.unit_desc1.$error" 
							 ng-if='editUnitForm.unit_desc1.$dirty  || editUnitForm.$submitted' role="alert">
								<span ng-message="required" class="error">Unit description is required</span>
							</span>
					   </div>
					</div>
					<div class="mT26 col-xs-3">
						<div class="pull-left">
							<input type="hidden" name="unit_id1" ng-value="unit_id_val">
							<button ng-disabled="editUnitForm.$invalid"type='submit' id='edit_button' class='btn btn-primary' ng-click='updateUnit()' title="Update" > Update </button>
							<button type="button" class="btn btn-default" ng-click="hideEditForm()" title="Close">Close</button>
						</div>
					</div>
				</div>
			</form>			
		</div>
	</div>
</div>