<div class="row" ng-hide="addMasterFormBladeDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Discipline</strong></span>
			</div>
				
			<!--Add form-->
			<form name='erpAddMasterForm' id="erpAddMasterForm" novalidate>							
				<div class="row">
				
					<!--Discipline Code-->
					<div class="col-xs-2">
						<span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
						<label for="or_discipline_code">Discipline Code<em class="asteriskRed">*</em></label>						   
						<input
							type="text"
							readonly
							class="form-control"
							ng-model="addMasterModel.or_discipline_code"
							ng-value="default_or_discipline_code"
							name="or_discipline_code" 
							id="or_discipline_code"
							placeholder="Discipline Code" />
						<span ng-messages="erpAddMasterForm.or_discipline_code.$error" ng-if='erpAddMasterForm.or_discipline_code.$dirty  || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Discipline code is required</span>
						</span>
					</div>
					<!--/Discipline Code-->
					
					<!--Discipline Name-->
					<div class="col-xs-2">
						<label for="or_discipline_name">Discipline Name<em class="asteriskRed">*</em></label>						   
						<input
							type="text"
							class="form-control" 
							ng-model="addMasterModel.or_discipline_name"
							name="or_discipline_name" 
							id="or_discipline_name"
							ng-required='true'
							placeholder="Discipline Name" />
						<span ng-messages="erpAddMasterForm.or_discipline_name.$error" ng-if='erpAddMasterForm.or_discipline_name.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Discipline name is required</span>
						</span>
					</div>
					<!--/Discipline Name-->
						
					<!--Discipline Status-->
					<div class="col-xs-2">																
						<label for="or_discipline_status" class="outer-lable">Discipline Status<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="or_discipline_status"
								id="or_discipline_status"
								ng-model="addMasterModel.or_discipline_status"
								ng-options="item.name for item in activeInactionSelectboxList track by item.id"
								ng-required='true'>
							<option value="">Select Discipline Status</option>
						</select>
						<span ng-messages="erpAddMasterForm.or_discipline_status.$error" ng-if='erpAddMasterForm.or_discipline_status.$dirty || erpAddMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Discipline Status is required</span>
						</span>
					</div>
					<!--/Discipline Status-->
					
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