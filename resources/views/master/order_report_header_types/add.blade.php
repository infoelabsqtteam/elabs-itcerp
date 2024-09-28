<div class="row" ng-show="addFormDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add</strong></span>
			</div>			
			<!--Add method form-->
			<form name="erpAddOrderRptHdrTypeForm" id="erpAddOrderRptHdrTypeForm"  novalidate>							
				<div class="row">
					
					
					<!--Branch Code-->
					<div class="col-xs-3">
						<label for="division_id">Branch<em class="asteriskRed">*</em></label>
						<select
							class="form-control" 
							name="division_id"
							id="division_id"
							ng-model="addOrderRptHdrType.division_id"
							ng-options="item.name for item in divisionsCodeList track by item.id"
							ng-required="true">
							<option value="">Select Branch</option>
						</select>
						<span ng-messages="erpAddOrderRptHdrTypeForm.division_id.$error" ng-if='erpAddOrderRptHdrTypeForm.division_id.$dirty  || erpAddOrderRptHdrTypeForm.$submitted' role="alert">
							<span ng-message="required" class="error">Branch is required</span>
						</span>
					</div>
					<!--/Branch Code-->
					
					<!--Department Name-->
					<div class="col-xs-3">
						<label for="product_category_id">Department<em class="asteriskRed">*</em></label>						   
							<select
								class="form-control" 
								id="product_category_id"
								name="product_category_id"
								ng-model="addOrderRptHdrType.product_category_id"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required="true">
							<option value="">Select Department</option>
						</select>
						<span ng-messages="erpAddOrderRptHdrTypeForm.product_category_id.$error" ng-if='erpAddOrderRptHdrTypeForm.product_category_id.$dirty || erpAddOrderRptHdrTypeForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>
					
					<!--Customer Type-->	
					<div class="col-xs-3">
						<label for="">Customer Type<em class="asteriskRed">*</em></label>
						<select
							class="form-control" 
							name="customer_type_id"
							ng-model="addOrderRptHdrType.customer_type_id"

							ng-options="item.name for item in customerTypeList track by item.id"
							ng-required="true">
							<option value="">Select customer Type</option>
						</select>
						<span ng-messages="erpAddOrderRptHdrTypeForm.customer_type_id.$error" ng-if='erpAddOrderRptHdrTypeForm.customer_type_id.$dirty  || erpAddOrderRptHdrTypeForm.$submitted' role="alert">
							<span ng-message="required" class="error">Customer Type is required</span>
						</span>
					</div>
					<!--/Customer Type-->	
					<!-- report header Type -->
					<div class="col-xs-3">
						<label for="">Report Header Type<em class="asteriskRed">*</em></label>
						<select
							class="form-control" 
							name="rpt_hdr_type_id"
							ng-model="addOrderRptHdrType.rpt_hdr_type_id"
							ng-options="item.name for item in reportHdrTypeList track by item.id"
							ng-required="true">
							<option value="">Select Report Header Type</option>
						</select>
						<span ng-messages="erpAddOrderRptHdrTypeForm.rpt_hdr_type_id.$error" ng-if='erpAddOrderRptHdrTypeForm.rpt_hdr_type_id.$dirty  || erpAddOrderRptHdrTypeForm.$submitted' role="alert">
							<span ng-message="required" class="error">Report Header Type is required</span>
						</span>
					</div>
					<!-- /report header Type -->
				</div>
			<!--save button-->
				<div class="col-xs-2 pull-right">
					<label for="submit">{{ csrf_field() }}</label>		
					<button title="Save" ng-disabled="erpAddOrderRptHdrTypeForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funAddOderRptHdrTypeForm()'>Save</button>
					<button title="Reset" type="button" class="mT26 btn btn-default" ng-click="resetForm()">Reset</button>
				</div>
				<!--/save button-->						
			</form>
			<!--Add method form-->
		</div>
	</div>
</div>