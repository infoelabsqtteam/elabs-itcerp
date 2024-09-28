<div class="row" id="editEquipmentDiv" ng-hide="IsViewEquipmentTypeEdit">
	<div class="row panel panel-default">
		<div class="panel-body">
			
			<div class="row header1">
				<strong class="pull-left headerText">Edit Equipment Type</strong>
			</div>
			
			<form name='editEquipmentForm' id="edit_equipment_form" novalidate>
				
				<div class="row">
					<div class="col-xs-3">
						<label for="equipment_code1">Equipment Code<em class="asteriskRed">*</em></label>						   
						<input readonly
							type="text"
							class="form-control"
							ng-model="equipment_code1"
							id="equipment_code1"
							ng-value="equipment_code1"
							placeholder="Equipment Code" />
					</div>
					<div class="col-xs-2">
						<label for="equipment_name1">Equipment Name<em class="asteriskRed">*</em></label>						   
						<input type="text"
							class="form-control" 
							ng-model="equipment_name1" 
							ng-value="equipment_name1"
							name="equipment_name1" 
							id="equipment_name1"
							ng-change="equipment_description1=equipment_name1"
							ng-required='true'
							placeholder="Equipment Name" maxlength="{{ defined('LETTERSLIMIT') ? LETTERSLIMIT : 50 }}"/>
						<span ng-messages="editEquipmentForm.equipment_name1.$error" ng-if='editEquipmentForm.equipment_name1.$dirty  || editEquipmentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Equipment name is required</span>
						</span>
					</div>
					<div class="col-xs-3">
						<label for="equipment_description1">Equipment Description<em class="asteriskRed">*</em></label>
						<textarea rows=1
							type="text"
							class="form-control textarea" 
							ng-model="equipment_description1"  ng-value="equipment_description1" 
							name="equipment_description1" 
							id="equipment_description1"
							ng-required='true'
							placeholder="Equipment Description" /></textarea>
						<span ng-messages="editEquipmentForm.equipment_description1.$error" ng-if='editEquipmentForm.equipment_description1.$dirty  || editEquipmentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Equipment description is required</span>
						</span>
					</div>
					<div class="col-xs-2">
						<label for="equipment_capacity">Equipment Capacity<em class="asteriskRed">*</em></label>						   
						<input type="text"
							class="form-control" 
							ng-model="equipment_capacity1"
							name="equipment_capacity1" 
							id="equipment_capacity1"
							ng-required='true'
							placeholder="Equipment Capacity" />
						<span ng-messages="equipmentForm.equipment_capacity1.$error" ng-if='equipmentForm.equipment_capacity1.$dirty  || equipmentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Equipment Capacity is required</span>
						</span>
					</div>	
					<div class="col-xs-2">
						<label for="equipment_status">Status<em class="asteriskRed">*</em></label>
						<select class="form-control" 
							ng-required='true'  
							name="equipment_status1" 
							id="equipment_status1" 
							ng-options="status.name for status in statusList track by status.id"
							ng-model="equipment_status1.selectedOption">
							<option value="">Select Status</option>
						</select>				   
					
						<span ng-messages="equipmentForm.equipment_status1.$error" ng-if='equipmentForm.equipment_status1.$dirty  || equipmentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>				
					<div class="col-xs-2" ng-if="!IsViewEquipmentTypeEdit">
						<input type="hidden" name="equipment_id1" ng-value="equipment_id1" ng-model="equipment_id1">
						<label for="submit">{{ csrf_field() }}</label>	
						<a href="javascript:;" ng-show="{{defined('EDIT') && EDIT}}" title="Update"  ng-disabled="editEquipmentForm.$invalid" class='mT26 btn btn-primary  btn-sm' ng-click='updateEquipment()' > Update </a>
						<button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()' > Close </button>
					</div>
				</div>
			</form>	
		</div>
	</div>
</div>