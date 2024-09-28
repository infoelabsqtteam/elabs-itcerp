<!-- Modal -->
<div id="addSchedulingProductParametersPopup"  class="modal fade" role="dialog">
	<div class="modal-dialog  modal-lg">	
		<div class="modal-content">
			<form method="POST" role="form" id="testParametersForm" name="testParametersForm" novalidate>
				<!--modal-header-->
				<div class="modal-header">
					<button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
					<span style="float: right; padding: 0px; margin: -5px;margin-top: -16px;padding-right: 14px;"><a href="javascript:;" ng-click="funAddMoreTestProductStandardParamentersPopup(orderPopUpID,productTestDtlPopupID);">Refresh</a></span>
 
				<!--<input type="text"  placeholder="Search" ng-model="search" class="form-control modalSearch" style="margin-right: -60px !important;">-->
				<h4 class="modal-title" ng-click="refreshCheckboxArray()">Products Test Parameters ([[allPopupSelectedParametersArray.length]])</h4>
				</div>
				<!--/modal-header-->
				
				<!--modal-body-->
				<div class="modal-body custom-scroll">
					<div class="row mT10" ng-show="testProductParamentersList.length">                    
						<div id="no-more-tables" class="col-xs-12 form-group fixed_table">
							<table class="col-sm-12 table-striped table-condensed  cf">
								<thead class="cf">
									<tr>					
										<th class="width5 text-center">
											<label><input type="checkbox" ng-disabled="testProductParamentersList.length > 0" ng-model="selectedAll" id="selectedAll" ng-click="toggleAll()"></label>
										</th>
										<th  class="width25 text-center" >
											<label class="sortlabel" ng-click="sortBy('test_parameter_name')">Test Parameter</label>
											<span class="sortorder" ng-show="predicate === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>
										</th>
										<th  class="width15 text-center" >
											<label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment</label>
											<span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>
										</th>
										<th class="width10 text-center" >
											<label class="sortlabel" ng-click="sortBy('method_name')">Method</label>
											<span class="sortorder" ng-show="predicate === 'method_name'" ng-class="{reverse:reverse}"></span>
										</th>
										
										<th  class="width15 text-center" >
											<label class="sortlabel" ng-click="sortBy('standard_value_type')">Std Value Type</label>
											<span class="sortorder" ng-show="predicate === 'standard_value_type'" ng-class="{reverse:reverse}"></span>
										</th>
										<th  class="width10 text-center" >
											<label class="sortlabel" ng-click="sortBy('standard_value_from')">Std Value From</label>
											<span class="sortorder" ng-show="predicate === 'standard_value_from'" ng-class="{reverse:reverse}"></span>
										</th>					
										<th class="width15 text-center" >
											<label class="sortlabel" ng-click="sortBy('standard_value_to')">Std Value To</label>
											<span class="sortorder" ng-show="predicate === 'standard_value_to'" ng-class="{reverse:reverse}"></span>
										</th>
										<th class="width10 text-center" >
											<label class="sortlabel" ng-click="sortBy('test_parameter_nabl_scope')">NABL</label>
											<span class="sortorder" ng-show="predicate === 'test_parameter_nabl_scope'" ng-class="{reverse:reverse}"></span>
										</th>					
										<th class="width10 text-center" >
											<label class="sortlabel" ng-click="sortBy('time_taken')">Time Taken</label>
											<span class="sortorder" ng-show="predicate === 'time_taken'" ng-class="{reverse:reverse}"></span>
										</th>
									</tr>
								</thead>
								<tbody>									
									<tr ng-repeat="categoryParameters in testProductParamentersList | filter:search">
										<td ng-show="categoryParameters.categoryParams.length" colspan="10" data-title="Test Parameter Category Name">
											<strong>[[categoryParameters.categoryName]] </strong>
											<table class="col-sm-12 table-striped table-condensed">
												<tbody ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">													
													<tr id ="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.description.length">
														<td class="width5" class="text-center" data-title="Select Parameter">
															<input type="checkbox" class="parametersCheckBox" ng-checked="subCategoryParameters.product_test_dtl_id == subCategoryParameters.product_test_parameter" ng-disabled="subCategoryParameters.product_test_dtl_id == subCategoryParameters.product_test_parameter" id="checkOneParameter_[[subCategoryParameters.product_test_dtl_id]]" ng-model="selectAllCheckbox" ng-click="funCheckParameterCheckedOrNotValues([[subCategoryParameters.product_test_dtl_id]])" name="product_test_dtl_id[]" value="[[subCategoryParameters.product_test_dtl_id]]">
														</td>
														<td class=" width25 text-left" id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
														<td class="width55" colspan = "6">[[subCategoryParameters.description]]</td>
													</tr>
													<tr id ="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="!subCategoryParameters.description.length">
														<td class="width5" class="text-center" data-title="Select Parameter" data="[[subCategoryParameters.product_test_parameter]]"> 
															<input type="checkbox" class="parametersCheckBox" ng-checked="subCategoryParameters.product_test_dtl_id == subCategoryParameters.product_test_parameter" ng-disabled="subCategoryParameters.product_test_dtl_id == subCategoryParameters.product_test_parameter" id="checkOneParameter_[[subCategoryParameters.product_test_dtl_id]]" ng-model="selectAllCheckbox" ng-click="funCheckParameterCheckedOrNotValues([[subCategoryParameters.product_test_dtl_id]])" name="product_test_dtl_id[]" value="[[subCategoryParameters.product_test_dtl_id]]">
														</td>
														<td class=" width25 text-left" id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
														<td class="width15 text-center"  id="equipment_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Equipment Name">[[subCategoryParameters.equipment_name]]</td>
														<td  class="width10 text-center"  id="method_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Method Name">[[subCategoryParameters.method_name]]</td>
														
														<td  class="width15 text-center" id="std_val_type_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value Type">[[subCategoryParameters.standard_value_type | capitalize]]</td>	
														<td class="width10 text-center"  id="standard_value_from_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value From">
															<span ng-if="subCategoryParameters.standard_value_from!=''">[[subCategoryParameters.standard_value_from]]</span>
															<span ng-if="subCategoryParameters.standard_value_from == ''">-</span>
														</td>
														<td  class="width10 text-center"  id="standard_value_to_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value To">
															<span ng-if="subCategoryParameters.standard_value_to!=''">[[subCategoryParameters.standard_value_to]]</span>
															<span ng-if="subCategoryParameters.standard_value_to == ''">-</span>
														</td>
														<td  class="width10 text-center"  id="nabl_scope_text[[subCategoryParameters.product_test_dtl_id]]" data-title="NABL scope">[[(subCategoryParameters.test_parameter_nabl_scope =='0' || !subCategoryParameters.test_parameter_nabl_scope) ? 'No' : 'Yes']]</td>
														<td   class="width10 text-center"  data-title="time taken">
															<span id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_days ? subCategoryParameters.time_taken_days+' Days' : '']]</span>
															<span id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]">[[subCategoryParameters.time_taken_mins ? subCategoryParameters.time_taken_mins+' Mins' : '']]</span>
														</td>
													</tr>
												</tbody>
											</table>
										</td>											
									</tr>											
								</tbody>
							</table>
						</div>
					</div>
				</div>					
				<div class="modal-footer">
				
					<button type="button" class="btn btn-primary" ng-click="funUpdateTestProductParamenterPopup()">Update</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>					
			</form>
		</div>
	</div>
</div>
<!-- Modal -->