<div id="add_form" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button title="" type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Add Unit Conversions</h4>
	  </div>
	  <div class="modal-body">
		<!--display Messge Div-->
		@include('includes.alertMessagePopup')
		<!--/display Messge Div--> 
		<form name='unitConversionForm' id="add_unitcon_form" novalidate>
				<div class="row">
					<div class="col-xs-12">
					   <div class="">     
						<label for="from_unit">From Unit<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="unit.from_unit"
									name="from_unit" 
									id="from_unit"
									ng-required='true'
									placeholder="From Unit" />
							<span ng-messages="unitConversionForm.from_unit.$error" 
							ng-if='unitConversionForm.from_unit.$dirty  || unitConversionForm.$submitted' role="alert">
								<span ng-message="required" class="error">From unit field is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-12">
					   <div class="">
						<label for="to_unit">To Unit<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control" 
									ng-model="unit.to_unit"
									name="to_unit" 
									id="to_unit"
									ng-required='true'
									placeholder="To Unit" />
							<span ng-messages="unitConversionForm.to_unit.$error" 
							 ng-if='unitConversionForm.to_unit.$dirty  || unitConversionForm.$submitted' role="alert">
								<span ng-message="required" class="error">To unit field is required</span>
							</span>
					   </div>
					</div>
					<div class="col-xs-12">
					   <div class="">
						<label for="confirm_factor">Confirm Factor<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control" 
									ng-model="unit.confirm_factor"
									name="confirm_factor" 
									id="confirm_factor"
									ng-required='true'
									placeholder="Confirm Factor" />
							<span ng-messages="unitConversionForm.confirm_factor.$error" 
							 ng-if='unitConversionForm.confirm_factor.$dirty  || unitConversionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Confirm factor field is required</span>
							</span>
					   </div>
					</div>
					<div class="mT26 col-xs-12">
						<div class="pull-right">
							<button title="Save" type='submit' id='add_button' class=' btn btn-primary' ng-click='addUnitConversion()' > Save </button>
							<button title="Close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>		
	</div>
  </div>
</div>
</div>