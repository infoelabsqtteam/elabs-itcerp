<div class="row" ng-hide="viewDivisionDiv">
	<div class="row header">
		<strong class="pull-left headerText">Branch Details</strong>	
		<strong class="pull-right closeDiv btn btn-primary" style="margin-top: 3px;" ng-click="hideViewForm()">Back</strong>	
	</div>
		<div class="row" style="margin-top:8px!important;">			
			<div class="col-xs-4">
				<label>Company Name:</label>
				<span class="color-black" 
					  ng-bind="view_division.company_name" 
					  ng-model="view_division.company_name">
				</span>
			</div>
			<div class="col-xs-4">
				<label>Branch Code:</label>
				<span class="color-black" 
					  ng-bind="view_division.division_code" 
					  ng-model="view_division.division_code">
				</span>
			</div>
			<div class="col-xs-4">
				<label>Branch Name:</label>
				<span class="color-black" 
					  ng-bind="view_division.division_name" 
					  ng-model="view_division.division_name">
				</span>
			</div>		
		</div>		
		<div class="row" style="margin-top:8px!important;">					
			<div class="col-xs-4">
				<label>Branch Address:</label>
				<span class="color-black" 
					  ng-bind="view_division.division_address" 
					  ng-model="view_division.division_address">
				</span>
			</div>		
			<div class="col-xs-4">
				<label>Branch City:</label>
				<span class="color-black" 
					  ng-bind="view_division.city_name" 
					  ng-model="view_division.city_name">
				</span>
			</div>
			<div class="col-xs-4">
				<label>Branch PAN:</label>
				<span class="color-black" 
					  ng-bind="view_division.division_PAN" 
					  ng-model="view_division.division_PAN">
				</span>
			</div>		
		</div>
		<div class="row" style="margin-top:8px!important;">					
					
			<div class="col-xs-4">
				<label>Branch VAT:</label>
				<span class="color-black" 
					  ng-bind="view_division.division_VAT_no" 
					  ng-model="view_division.division_VAT_no">
				</span>
			</div>
		</div>
	</div>		
</div>