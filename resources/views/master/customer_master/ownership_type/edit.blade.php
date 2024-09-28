<div ng-model="editOwnershipFormDiv" id="editOwnershipDiv" ng-hide="editOwnershipFormDiv" >
			<div class="row header1">
				<strong class="pull-left headerText">Edit Ownership</strong>
			</div>
			<form name='editOwnershipForm' id="edit_ownership_form" novalidate>
				<label for="submit">{{ csrf_field() }}</label>	
				<div class="row">
					<div class="col-xs-3">
						<label for="ownership_code1">Ownership Code<em class="asteriskRed">*</em></label>						   
						<input  readonly type="text"
								class="form-control" 
								ng-model="edit_ownership.ownership_code"
								placeholder="Ownership Code" />
					</div>
					<div class="col-xs-3">
						<label for="ownership_name1">Ownership Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="edit_ownership.ownership_name" 
									name="ownership_name" 
									ng-required='true'
									placeholder="Ownership Name" />
							<span ng-messages="editOwnershipForm.ownership_name1.$error" 
							ng-if='editOwnershipForm.ownership_name1.$dirty  || editOwnershipForm.$submitted' role="alert">
								<span ng-message="required" class="error">Ownership name is required</span>
							</span>
					</div>
					<div class="col-xs-3">
							<input type="hidden" name="ownership_id" ng-model="ownership_id" ng-value="ownership_id">
							<button title="Update"  ng-disabled="editOwnershipForm.$invalid" type='submit' class='mT26 btn btn-primary  btn-sm' ng-click='updateOwnership()' > Update </button>
							<button title="Close"  type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()'> Close </button>
					</div>
				</div>
			</form>	
		</div>