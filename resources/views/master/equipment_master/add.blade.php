<div class="row" ng-hide="IsViewEquipmentTypeAdd">
    <div class="row panel panel-default">
	<div class="panel-body">
	    
	    <div class="row header1">
		<span><strong class="pull-left headerText">Add Equipment Types</strong></span>
		<span><button class="pull-right btn btn-primary mT3 mR3" ng-click="showSortForm()">Sort</button></span>
		<span><button class="pull-right btn btn-primary mT3 mR3" ng-click="showUploadForm()">Upload</button></span>
	    </div>
		
	    <form name='equipmentForm' id="equipmentForm" novalidate>
		<div class="row">						
		    <div class="col-xs-2">
			<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
			<label for="equipment_code">Equipment Code<em class="asteriskRed">*</em></label>						   
			<input type="text"
			    class="form-control"
			    readonly
			    ng-model="equipment_code"
			    ng-bind="equipment_code"
			    name="equipment_code" 
			    id="equipment_code"
			    ng-required='true'
			    placeholder="Equipment Code" />
			<span ng-messages="equipmentForm.equipment_code.$error" ng-if='equipmentForm.equipment_code.$dirty  || equipmentForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Equipment code is required</span>
			</span>
		    </div>
		    <div class="col-xs-3">
			<label for="equipment_name">Equipment Name<em class="asteriskRed">*</em></label>						   
			<input type="text"
			    class="form-control" 
			    ng-model="equipment.equipment_name"
			    name="equipment_name" 
			    ng-change="equipment.equipment_description=equipment.equipment_name"
			    id="equipment_name"
			    ng-required='true'
			    placeholder="Equipment Name" maxlength="{{ defined('LETTERSLIMIT') ? LETTERSLIMIT : 50 }}"/>
				<span ng-messages="equipmentForm.equipment_name.$error" ng-if='equipmentForm.equipment_name.$dirty  || equipmentForm.$submitted' role="alert">
					 <span ng-message="required" class="error">Equipment name is required</span>
				</span>
					
				<span ng-if="countErrorMsg">Count should be below 50.</span>
		    </div>
		    <div class="col-xs-3">
			<label for="equipment_description">Equipment Description<em class="asteriskRed">*</em></label>
			<textarea
			    rows=1
			    class="form-control textarea" 
			    ng-model="equipment.equipment_description"
			    name="equipment_description" 
			    id="equipment_description"
			    ng-required='true'
			    placeholder="Equipment Description" /></textarea>
			<span ng-messages="equipmentForm.equipment_description.$error" ng-if='equipmentForm.equipment_description.$dirty  || equipmentForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Equipment description is required</span>
			</span>
		    </div>
		    <div class="col-xs-2">
			<label for="equipment_capacity">Equipment Capacity<em class="asteriskRed">*</em></label>						   
			<input type="text"
			    class="form-control" 
			    ng-model="equipment.equipment_capacity"
			    name="equipment_capacity" 
			    id="equipment_capacity"
			    ng-required='true'
			    placeholder="Equipment Capacity" />
			<span ng-messages="equipmentForm.equipment_capacity.$error" ng-if='equipmentForm.equipment_capacity.$dirty  || equipmentForm.$submitted' role="alert">
			    <span ng-message="required" class="error">Equipment Capacity is required</span>
			</span>
			</div>
			<div class="col-xs-2">
				<label for="equipment_status">Status<em class="asteriskRed">*</em></label>	
				<select class="form-control" 
					ng-required='true'  
					name="equipment_status" 
					id="equipment_status" 
					ng-options="status.name for status in statusList track by status.id"
					ng-model="equipment_status.selectedOption">
					<option value="">Select Status</option>
				</select>				   
			
				<span ng-messages="equipmentForm.equipment_status.$error" ng-if='equipmentForm.equipment_status.$dirty  || equipmentForm.$submitted' role="alert">
					<span ng-message="required" class="error">Status is required</span>
				</span>
			</div>
		    <div class="col-xs-2" ng-if="!IsViewEquipmentTypeAdd">
			<label for="submit">{{ csrf_field() }}</label>	
			<a ng-if="{{defined('ADD') && ADD}}" href="javascript:;" title="Save" ng-disabled="equipmentForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='addEquipment()' > Save </a>
			<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetAddForm()" data-dismiss="modal">Reset</button>
		    </div>
		</div>
	    </form>	
	</div>
    </div>
</div>