<div class="row" ng-hide="editMasterFormBladeDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Discipline : [[editMasterModel.or_discipline_name]]</strong></span>
			</div>
				
			<!--Edit form-->
			<form name='erpEditMasterForm' id="erpEditMasterForm" novalidate>							
				<div class="row">
				
					<!--Discipline Code-->
					<div class="col-xs-2">
						<label for="or_discipline_code">Discipline Code<em class="asteriskRed">*</em></label>						   
						<input
							type="text"
							ng-disabled="true"
							class="form-control"
							ng-model="editMasterModel.or_discipline_code"
							id="or_discipline_code"
							placeholder="Discipline Code" />
						<span ng-messages="erpEditMasterForm.or_discipline_code.$error" ng-if='erpEditMasterForm.or_discipline_code.$dirty  || erpEditMasterForm.$submitted' role="alert">
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
							ng-model="editMasterModel.or_discipline_name"
							name="or_discipline_name" 
							id="or_discipline_name"
							ng-required='true'
							placeholder="Discipline Name" />
						<span ng-messages="erpEditMasterForm.or_discipline_name.$error" ng-if='erpEditMasterForm.or_discipline_name.$dirty || erpEditMasterForm.$submitted' role="alert">
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
								ng-model="editMasterModel.or_discipline_status"
								ng-options="item.name for item in activeInactionSelectboxList track by item.id"
								ng-required='true'>
							<option value="">Select Discipline Status</option>
						</select>
						<span ng-messages="erpEditMasterForm.or_discipline_status.$error" ng-if='erpEditMasterForm.or_discipline_status.$dirty || erpEditMasterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Discipline Status is required</span>
						</span>
					</div>
					<!--/Discipline Status-->
					
					<!--Update button-->
					<div class="col-xs-2">
						<label for="submit">{{ csrf_field() }}</label>		
						<span ng-if="{{defined('ADD') && ADD}}">
							<input type="hidden" name="or_discipline_id" ng-value="editMasterModel.or_discipline_id" ng-model="editMasterModel.or_discipline_id">
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