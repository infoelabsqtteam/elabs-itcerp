<div class="modal fade" data-backdrop="static" data-keyboard="false" id="productTestClonePopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg">	
		<div class="row modal-content">
			<form name="CloneProductTestForm" id="clone_product_test_form" novalidate>
		
				<!--modal-header-->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Clone : Standard Wise Product Test</h4>
				</div>				
				<!--/modal-header-->
				
				<!--modal-body-->
				<div class="modal-body">
					<div class="row">
						<div id="successMessage" ng-hide="IsVisiablePopUpSuccessMsg" role="alert" class="alert alert-success ng-hide" aria-hidden="true">
							<span ng-bind-html="successMessage" class="ng-binding"></span>
							<span class="text-right"><a ng-click="hideAlertMsg()" href="" class="closeAlert" aria-label="close">×</a></span>
						</div>
						<div id="popUpErrorMessage" ng-hide="IsVisiablePopUpErrorMsg" role="alert" class="alert alert-danger" aria-hidden="false" style="">
							<span ng-bind-html="errorMessage" class="ng-binding"></span>
							<span class="text-right"><a ng-click="hideAlertMsg()" href="" class="closeAlert" aria-label="close">×</a></span>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<label for="Product">Product <em class="asteriskRed">*</em></label>
							<a title="Select Category" data-toggle="modal" ng-click="showProductCatTreeViewPopUp(3)" class="generate cursor-pointer"> Tree View </a>
							<select class="form-control" 
								name="product_id"
								ng-model="product_id.selectedOption"
								id="product_id"
								ng-required='true'
								ng-options="item.name for item in productList track by item.id">
							<option value="">Select Product</option>
							</select>
						</div>
						<div class="col-xs-3">
							<label for="test_standard_id" class="outer-lable">
								<span class="filter-lable">Test Standard <em class="asteriskRed">*</em></span>
								<span class="filterCatLink hidden">
									<a title="Search Test Standard" ng-hide="searchStandardFilterBtn" href="javascript:;" ng-click="showStandardDropdownSearchFilter()"><i class="fa fa-filter"></i></a> 
								</span>
								<span ng-hide="searchStandardFilterInput" class="filter-span">
									<input type="text" class="filter-text" placeholder="Enter keyword" ng-model="searchStandard.stdText"/>
									<button title="Close Filter" type="button" class="close filter-close" ng-click="hideStandardDropdownSearchFilter()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</span>	
							</label>						   
							<select
								class="form-control"
								name="test_standard_id"
								ng-required='true' 
								ng-change="setStandardSelectedOption(test_standard_id.selectedOption)" 
								ng-model="test_standard_id.selectedOption"
								ng-options="item.id as item.name for item in (testStandardsList | filter:searchStandard.stdText) track by item.id">
								<option value="">Select Test Standard</option>
							</select>				
							<span ng-messages="CloneProductTestForm.test_standard_id.$error" ng-if='CloneProductTestForm.test_standard_id.$dirty  || CloneProductTestForm.$submitted' role="alert">
								<span ng-message="required" class="error">Test standard is required</span>
							</span>
						</div>					
						<div class="col-xs-3 form-group">
							<label for="wef">Wef:</label>
							<div class="input-group date"  data-provide="datepicker">
								<input
									type="text"
									class="backWhite form-control" 
									ng-model="editProductTest.wef"
									name="wef"
									readonly
									id="wef"
									placeholder="Wef" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
							</div>
							<span ng-messages="editProductTestForm.wef.$error" ng-if='editProductTestForm.wef.$dirty  || editProductTestForm.$submitted' role="alert">
								<span ng-message="required" class="error">Wef is required</span>
							</span>
						</div>	
						<div class="col-xs-3 form-group">
							<label for="upto">Upto:</label>
							<div class="input-group date"  data-provide="datepicker">
								<input
									type="text"
									class="backWhite form-control" 
									ng-model="editProductTest.upto"
									readonly
									name="upto" 
									id="upto"
									placeholder="upto" />
								<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
							</div>
							<span ng-messages="editProductTestForm.upto.$error" ng-if='editProductTestForm.upto.$dirty  || editProductTestForm.$submitted' role="alert">
								<span ng-message="required" class="error">Upto is required</span>
							</span>
						</div>	
					</div>
					<div class="row mT30">
						<div class="col-sm-12 header1">
							<strong class="pull-left headerText" style="margin-left: -7px;">Test Parameters([[allParaList.length]])</strong>
						</div>
						<div id="no-more-tables" class="col-xs-12 form-group fixed_table custom-scroll">
							<table class="col-sm-12 table-striped table-condensed cf">
								<thead class="cf">
									<tr>
										<th>
											<div class="checkbox"><input type="checkbox" ng-model="selectedAll" id="selectedAll" ng-click="toggleAll()"></div></th>
										<th>
											<label class="sortlabel" ng-click="sortBy('test_parameter_name')">Parameter category</label>
											<span class="sortorder" ng-show="predicate === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>						
										</th>														
										<th>
											<label class="sortlabel" ng-click="sortBy('test_parameter_name')">Parameter</label>
											<span class="sortorder" ng-show="predicate === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>						
										</th>	
										<th>
											<label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment</label>
											<span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>						
										</th>						
										<th>
											<label class="sortlabel" ng-click="sortBy('method_name')">Method</label>
											<span class="sortorder" ng-show="predicate === 'method_name'" ng-class="{reverse:reverse}"></span>						
										</th>						
										<th>
											<label class="sortlabel" ng-click="sortBy('time_taken_days')">Time Taken<br> <sub>(in days)</sub></label>
											<span class="sortorder" ng-show="predicate === 'time_taken_days'" ng-class="{reverse:reverse}"></span>						
										</th>						
										<th>
											<label class="sortlabel" ng-click="sortBy('time_taken_mins')">Time Taken<br><sub>(in hours:minutes)</sub></label>
											<span class="sortorder" ng-show="predicate === 'time_taken_mins'" ng-class="{reverse:reverse}"></span>						
										</th>
										<th>
											<label class="sortlabel" ng-click="sortBy('standard_value_from')">Std Value<br> From </label>
											<span class="sortorder" ng-show="predicate === 'standard_value_from'" ng-class="{reverse:reverse}"></span>						
										</th>					
										<th>
											<label class="sortlabel" ng-click="sortBy('standard_value_to')">Std Value<br> To </label>
											<span class="sortorder" ng-show="predicate === 'standard_value_to'" ng-class="{reverse:reverse}"></span>						
										</th>						
										<th>
											<label class="sortlabel" ng-click="sortBy('cost_price')">Cost Price</label>
											<span class="sortorder" ng-show="predicate === 'cost_price'" ng-class="{reverse:reverse}"></span>						
										</th>						
										<th>
											<label class="sortlabel" ng-click="sortBy('selling_price')">Selling Price</label>
											<span class="sortorder" ng-show="predicate === 'selling_price'" ng-class="{reverse:reverse}"></span>						
										</th>	
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="obj in allParaList | orderBy:predicate:reverse">
										<td><input type="checkbox" class="parametersCheckBox" ng-checked="allPopupSelectedParametersArray.indexOf(obj.product_test_dtl_id) > -1" id="checkOneParameter_[[obj.product_test_dtl_id]]" ng-model="selectAllCheckbox" ng-click="funCheckParameterCheckedOrNot([[obj.product_test_dtl_id]])" name="test_parameters[]" value="[[obj.product_test_dtl_id]]"></td>
										<td data-title="Test Parameter Category">[[obj.test_para_cat_name?obj.test_para_cat_name:'']]</td>
										<td data-title="Test Parameter" ng-bind-html="obj.test_parameter_name"></td>					
										<td class="text-center" ng-if="!obj.description.length" data-title="Equipment Name">[[obj.equipment_name?obj.equipment_name:'-']]</td>
										<td class="text-center"  ng-if="!obj.description.length" data-title="Method Name">[[obj.method_name?obj.method_name:'-']]</td>
										<td ng-if="!obj.description.length" data-title="Time Taken Days">[[obj.time_taken_days?obj.time_taken_days:'-']]</td>
										<td ng-if="!obj.description.length" data-title="Time Taken Mins">[[obj.time_taken_mins?obj.time_taken_mins:'-']]</td>
										<td ng-if="!obj.description.length" data-title="Standard Value From">[[obj.standard_value_from?obj.standard_value_from:'']]</td>
										<td ng-if="!obj.description.length" data-title="Standard Value To">[[obj.standard_value_to?obj.standard_value_to:'']]</td>
										<td ng-if="!obj.description.length" data-title="Cost Price">[[obj.cost_price?obj.cost_price:'0.00']]</td>
										<td ng-if="!obj.description.length" data-title="Selling Price">[[obj.selling_price?obj.selling_price:'0.00']]</td>					
										<td ng-if="obj.description.length" colspan="8" data-title="[[obj.test_parameter_name]]">[[obj.description?obj.description:'Description Not Available']]</td>
									</tr>
									<tr ng-if="!allParaList.length"  class="noRecord"><td colspan="11">No Record Found!</td></tr>
									<tr ng-if="allParaListPaginate" class="text-left"></tr>											
								</tbody>
							</table>
						</div>
					</div>
				</div>					
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>
					<button title="Save"  type='cancel' class='mT26 btn btn-primary' ng-click='funAddMoreProductTest()'> Save </button>
					<button title="Close"  type='cancel' class='mT26 btn btn-default' ng-click='funClose()'> Close </button>
				</div>					
			</form>
		</div>
	</div>
</div>