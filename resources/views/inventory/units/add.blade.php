<div class="row" ng-hide="hideUnitAddForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Unit</strong></span>
			</div>
			<form name='unitForm' id="add_unit_form" novalidate>
			<div class="row">
				<div class="col-xs-3">
					<span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>  
					<label for="unit_code">Unit Code<em class="asteriskRed">*</em></label>						   
						<input type="text" class="form-control" readonly
								ng-model="unit_code"
								ng-bind="unit_code" 
								name="unit_code" 
								id="unit_code"
								ng-required='true'
								placeholder="Unit Code" />
						<span ng-messages="unitForm.unit_code.$error" 
						ng-if='unitForm.unit_code.$dirty  || unitForm.$submitted' role="alert">
							<span ng-message="required" class="error">Unit code is required</span>
						</span>
				</div>
				<div class="col-xs-3">
				   <div class="">
					<label for="unit_name">Unit Name<em class="asteriskRed">*</em></label>
						<input type="text" class="form-control" 
								ng-model="unit.unit_name"
								name="unit_name" 
								id="unit_name"
								ng-required='true'
								placeholder="Unit Name" />
						<span ng-messages="unitForm.unit_name.$error" 
						 ng-if='unitForm.unit_name.$dirty  || unitForm.$submitted' role="alert">
							<span ng-message="required" class="error">Unit name is required</span>
						</span>
				   </div>
				</div>
				<div class="col-xs-3">
				   <div class="">
					<label for="unit_desc">Unit Description<em class="asteriskRed">*</em></label>
						<input type="text" class="form-control" 
								ng-model="unit.unit_desc"
								name="unit_desc" 
								id="unit_desc"
								ng-required='true'
								placeholder="Unit Description" />
						<span ng-messages="unitForm.unit_desc.$error" 
						 ng-if='unitForm.unit_desc.$dirty  || unitForm.$submitted' role="alert">
							<span ng-message="required" class="error">Unit description is required</span>
						</span>
				   </div>
				</div>
				<div class="mT26 col-xs-3">
					<div class="pull-left">
						<button  ng-disabled="unitForm.$invalid" type='submit' id='add_button' class=' btn btn-primary' ng-click='addUnit()' title="Save" > Save </button>
					    <button type="button" class="btn btn-default" ng-click="resetForm()" title="Reset">Reset</button>

					</div>
				</div>
			</div>
		</form>		
		</div>
	</div>
</div>