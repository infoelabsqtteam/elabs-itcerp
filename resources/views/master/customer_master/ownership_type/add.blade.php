<div ng-model="addOwnershipFormDiv" ng-hide="addOwnershipFormDiv" >
<div class="row header1">
<strong class="pull-left headerText">Add Ownership</strong>
</div>
<form name='ownershipForm' id="add_ownership_form" novalidate>	
<label for="submit">{{ csrf_field() }}</label>					
<div class="row">						
	<div class="col-xs-3">
		<label for="ownership_code">Ownership Code<em class="asteriskRed">*</em></label>						   
			<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
			<input type="text" class="form-control"  readonly
					ng-model="ownership_code"
					ng-bind="ownership_code"
					name="ownership_code" 
					id="ownership_code"
					ng-required='true'
					placeholder="Ownership Code" />
			<span ng-messages="ownershipForm.ownership_code.$error" 
			ng-if='ownershipForm.ownership_code.$dirty  || ownershipForm.$submitted' role="alert">
				<span ng-message="required" class="error">Ownership code is required</span>
			</span>
	</div>
	<div class="col-xs-3">
		<label for="ownership_name">Ownership Name<em class="asteriskRed">*</em></label>						   
			<input type="text" class="form-control" 
					ng-model="ownership.ownership_name"
					name="ownership_name" 
					id="ownership_name"
					ng-required='true'
					placeholder="Ownership Name" />
			<span ng-messages="ownershipForm.ownership_name.$error" 
			ng-if='ownershipForm.ownership_name.$dirty  || ownershipForm.$submitted' role="alert">
				<span ng-message="required" class="error">Ownership name is required</span>
			</span>
	</div>
	<div class="col-xs-3">
		<div class="">
<button title="Save"   ng-disabled="ownershipForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='addOwnership()' > Save </button>
<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>

		</div>
	</div>
</div>
</form>	
</div>