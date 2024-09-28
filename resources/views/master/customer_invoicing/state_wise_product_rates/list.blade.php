<div  ng-hide="listStateWiseProductRateDiv">

	<form class="form-inline" method="POST" target ="blank" action="{{url('master/state-wise-products/download-excel')}}" role="form" id="erpStateWiseProductFilterForm" name="erpStateWiseProductFilterForm" novalidate>
		<label for="submit">{{ csrf_field() }}</label>
		<div class="row header">
			<div role="new" class="navbar-form navbar-left">
				<span ng-click="funGetStateWiseRefreshProductRates(cirStateID,1,1)" class="pull-left"><strong id="form_title">State Wise Product Rate Listing[[defautDivisionId.selectedOption]]<span>([[stateWiseCustomerInvoicingCount]])</span></strong></span>
			</div>
			
			<div role="new" class="navbar-form navbar-right custom-display" style="margin-top:0px;">
					<input type="text" placeholder="Search" name="search_keyword" ng-model="filterStateWiseProductRateList"
					class="form-control ng-pristine ng-untouched ng-valid">
					
						<select  class="form-control width30" name="cir_division_id"
							ng-model="defaultDivisionId.selectedOption"
							id="cir_division_id"
							ng-change = "funGetStateAccToDept(deptID.selectedOption.id,defaultDivisionId.selectedOption.id)"
							ng-options="item.name for item in divisionsCodeList track by item.id">
						</select>
						<select  class="form-control width30" name="cir_product_category_id"
							ng-model="deptID.selectedOption"
							id="cir_product_category_id"
							ng-change = "funGetStateAccToDept(deptID.selectedOption.id,defaultDivisionId.selectedOption.id)"
							ng-options="item.name for item in parentCategoryList track by item.id">
						</select>
						
					<button type="button" ng-disabled="!stateWiseProductRateList.length" class="form-control btn btn-default dropdown dropdown-toggle " data-toggle="dropdown" title="Download">
						Download</button>
						<div class="dropdown-menu" style="">
							<input type="submit" name="generate_state_wise_product_documents"  class="dropdown-item" value="Excel">
						</div>
					
					<div class="">
						<a href="javascript:;"style="float:right;margin-top:3px" ng-if="{{defined('ADD') && ADD}}" ng-click="navigateFormPage(cirStateID,'0','0','add');" class="btn btn-primary">Add New</a>
					</div>
					<input type="hidden" name="cirStateID" value="[[selectedStateId]]">	
					
			</div>		
				
		</div>
	</form>
		
	<div class="row" id="no-more-tables">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="col-sm-12">
					<div class="col-sm-3 text-left custom-scroll">
						<table class="col-sm-12 table-striped table-condensed cf">
							<thead>
								<tr>
									<th>
										<label ng-click="funGetProductWiseStatesList()">State Name ([[productStatesList.length]]) </label>
									</th>
								</tr>
								<tr>
									<td>
										<input type="text" placeholder="Search" ng-model="filterProductStatesList" class="form-control ng-pristine ng-untouched ng-valid">
									</td>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="stateObj in productStatesList | filter : filterProductStatesList" style="padding: 0;" class="gray-listing" title="[[stateObj.name]]">
									<td>
										<div class="col-sm-10 text-left" ng-if="cirStateID != stateObj.id"  ng-click="funGetStateWiseProductRates(stateObj.id,deptID.selectedOption.id,defautDivisionId.selectedOption.id,'modify')">
											[[stateObj.name]]
										</div>
										<div class="col-sm-10 text-left active-gray-listing"  ng-if="cirStateID == stateObj.id"  ng-click="funGetStateWiseProductRates(stateObj.id,deptID.selectedOption.id,defautDivisionId.selectedOption.id,'modify')">
											[[stateObj.name]]
										</div>
										<div class="col-sm-2 editbtn">
											<a style="margin-left: 9px;" href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditSelectedStateProductRate(stateObj.id,stateObj.cir_product_category_id,[[stateObj.cir_division_id]],'modify');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										</div>
									</td>
									</tr>
							</tbody>
						</table>
					</div>
					<div class="col-sm-9">
						<table class="col-sm-12 table-striped table-condensed cf font15">
						<thead>
						<tr>
							<th>
								<label>Product Category</label>
							</th>
							<th>
								<label>Product Name</label>
							</th>
							<th>
								<label>Product Alias Name</label>
							</th>
							<th>
								<label>Rate</label>
							</th>
							<th>
								<label>Delete</label>
							</th>
						</tr>
						</thead>
						<tbody>
							<tr ng-repeat="stateWiseProductRateObj in stateWiseProductRateList | filter : filterStateWiseProductRateList" title="[[stateWiseProductRateObj.c_product_name]]">
									<td>[[stateWiseProductRateOb.product_namej]]
										<span ng-if="stateWiseProductRateObj.dept_name">
											<span>[[!stateWiseProductRateObj.dept_name ? '-' : stateWiseProductRateObj.dept_name]]</span>
										</span>
									</td>
									<td>
										<span>
											[[stateWiseProductRateObj.product_name]]
										</span>
									</td>
									<td>
										<span>
											[[stateWiseProductRateObj.c_product_name]]
										</span>
									</td>
									<td>
									<span>
									[[stateWiseProductRateObj.invoicing_rate]]
									</span>
									</td>
										<td>
										<span >
											<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE }}" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(stateWiseProductRateObj.cir_id,cirStateID,'stateWiseProductRate');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
										</span>
									</td>
								</tr>
						</tbody>
						</table>
						<div ng-if="!productStatesList.length" class="col-sm-12 text-center">No Record Found!</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
