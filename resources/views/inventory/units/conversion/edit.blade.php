<div id="edit_form" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button title="" type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Edit Unit Conversions</h4>
	  </div>
	  <div class="modal-body">
		<!--display Messge Div-->
		@include('includes.alertMessagePopup')
		<!--/display Messge Div--> 
			<form name='editUnitConForm' id="edit_unitcon_form" novalidate>
				<div class="row">
					<div class="col-xs-12">
					   <div class="">     
						<label for="from_unit1">From Unit<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="from_unit_val"
									name="from_unit1" 
									id="from_unit1"
									ng-required='true'
									placeholder="From Unit" />
							<span ng-messages="editUnitConForm.from_unit1.$error" 
							ng-if='editUnitConForm.from_unit1.$dirty  || editUnitConForm.$submitted' role="alert">
								<span ng-message="required" class="error">From unit field is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-12">
					   <div class="">
						<label for="to_unit1">To Unit<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control" 
									ng-model="to_unit_val"
									name="to_unit1" 
									id="to_unit1"
									ng-required='true'
									placeholder="To Unit" />
							<span ng-messages="editUnitConForm.to_unit1.$error" 
							 ng-if='editUnitConForm.to_unit1.$dirty  || editUnitConForm.$submitted' role="alert">
								<span ng-message="required" class="error">To unit field is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-12">
					   <div class="">
						<label for="confirm_factor1">Confirm Factor<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control" 
									ng-model="confirm_factor_val"
									name="confirm_factor1" 
									id="confirm_factor1"
									ng-required='true'
									placeholder="Confirm Factor" />
							<span ng-messages="editUnitConForm.confirm_factor1.$error" 
							 ng-if='editUnitConForm.confirm_factor1.$dirty  || editUnitConForm.$submitted' role="alert">
								<span ng-message="required" class="error">Confirm factor field is required</span>
							</span>
					   </div>
					</div>
					<div class="mT26 col-xs-12">
						<div class="pull-right">
							<input type="hidden" name="unit_conversion_id" ng-value="unit_con_id_val">
							<button title="Update" type='submit' id='edit_button' class=' btn btn-primary' ng-click='updateUnitCon()' > Update </button>
							<button title="Close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>				
	</div>
  </div>
</div>
</div>