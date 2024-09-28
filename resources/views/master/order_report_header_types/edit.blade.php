<div class="row" ng-if="editFormDiv">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<strong class="pull-left headerText">Edit</strong>
				<button title="Close" type='button' class='mT6 btn btn-primary pull-right'  style="position: relative;top: -3px;" ng-click='back()'>Back</button>
			</div>
			<form name="erpEditForm" id="erpEditForm" novalidate>

				<div class="row">
					
						
					
					<!--Branch Code-->
					<div class="col-xs-3">
						<label for="division_id">Branch <em class="asteriskRed">*</em></label>
							<select
								class="form-control" 
								id="division_id"
								name="division_id"
								ng-model="editOrderRptHdr.division_id.selectedOption"
								ng-options="item.name for item in divisionsCodeList track by item.id"
								ng-required="true">
							<option value="">Select Branch </option>
						</select>
						<span ng-messages="erpEditForm.division_id.$error" ng-if='erpEditForm.division_id.$dirty  || erpEditForm.$submitted' role="alert">
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
							ng-model="editOrderRptHdr.product_category_id.selectedOption"
							ng-options="item.name for item in parentCategoryList track by item.id"
							ng-required="true">
							<option value="">Select Department</option>
						</select>
						<span ng-messages="erpEditForm.product_category_id.$error" ng-if='erpEditForm.product_category_id.$dirty || erpEditForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>
					
					<!--/Department Name-->
					<div class="col-xs-3">
						<label for="">Customer Type<em class="asteriskRed">*</em></label>
						<select
							class="form-control" 
							name="customer_type_id"
							ng-model="editOrderRptHdr.customer_type_id.selectedOption"

							ng-options="item.name for item in customerTypeList track by item.id"
							ng-required="true">
							<option value="">Select customer Type</option>
						</select>
						<span ng-messages="erpEditForm.customer_type_id.$error" ng-if='erpEditForm.customer_type_id.$dirty  || erpEditForm.$submitted' role="alert">
							<span ng-message="required" class="error">Customer Type is required</span>
						</span>
					</div>
					<!--/Template Type-->
					<!-- report header Type -->
					<div class="col-xs-3">
						<label for="">Report Header Type<em class="asteriskRed">*</em></label>
						<select
							class="form-control" 
							name="rpt_hdr_type_id"
							ng-model="editOrderRptHdr.rpt_hdr_type_id.selectedOption"
							ng-options="item.name for item in reportHdrTypeList track by item.id"
							ng-required="true">
							<option value="">Select Report Header Type</option>
						</select>
						<span ng-messages="erpEditForm.rpt_hdr_type_id.$error" ng-if='erpEditForm.rpt_hdr_type_id.$dirty  || erpEditForm.$submitted' role="alert">
							<span ng-message="required" class="error">Report Header Type is required</span>
						</span>
					</div>
					<!-- /report header Type -->
				</div>
				
					
				
				
				<!--Update button-->
				<div class="row">					
					<div class="col-xs-2  pull-right">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" name="orht_id" ng-value="editOrderRptHdr.orht_id" ng-model="editOrderRptHdr.orht_id">
						<button type="submit" title="Update" ng-disabled="erpEditForm.$invalid" class='mT26 btn btn-primary  btn-sm' ng-click='funUpdateDetail()'>Update</button>							
						<button title="Close" type='button' class='mT26 btn btn-default btn-sm' ng-click='back()'>Close</button>
					</div>				
				</div>
				<!--/Update button-->
			</form>	
		</div>
	</div>
</div>