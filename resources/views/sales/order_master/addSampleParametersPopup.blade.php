<!-- Modal -->
<div id="productParametersPopup" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">	
		<div class="modal-content">
			<form method="POST" role="form" id="testParametersForm" name="testParametersForm" novalidate>
				
				<!--modal-header-->
				<div class="modal-header">
					<button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" ng-click="refreshCheckboxArray()">Products Test Parameters  [[note]]  ([[allPopupSelectedParametersArray.length]])</h4>
					<div class="noteSelected headerNotes">
						<label> Header Note </label><input type="checkbox" id="header_note" ng-click="funHeaderNoteCheck()">
						<label class="pL10"> Real Time Stability</label><input type="checkbox" id="real_time_stability" ng-click="funRealTimeStabilityStatusCheck()">
					</div>
					<div class="col-sm-12 text-left mT20">
						<input type="text" placeholder="Search" ng-model="searchKeyword" class="form-control" ng-change="funFilterParameterPopupList(searchKeyword,'searchAddParameterPopupFilter')">
					</div>
				</div>
				<!--/modal-header-->
				
				<!--modal-body-->
				<div class="modal-body custom-scroll">
					<div class="row mT10" ng-show="testProductParamentersList.length">                    
						<div id="no-more-tables" class="col-xs-12 form-group fixed_table">
							<table border="1" lass="col-sm-12 table-striped table-condensed cf">
								<thead class="cf">
									<tr>					
										<th style="width:10px!important;">
											<label class="checkboxlabel"><input type="checkbox" ng-model="selectedAll" id="selectedAll" ng-click="toggleAll()"></label>
										</th>
										<th>
											<label class="sortlabel" ng-click="sortBy('test_parameter_name')">Parameters</label>
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
											<label class="sortlabel" ng-click="sortBy('detector_name')">Detector</label>
											<span class="sortorder" ng-show="predicate === 'detector_name'" ng-class="{reverse:reverse}"></span>
										</th>
										<th>
											<label class="sortlabel" ng-click="sortBy('standard_value_from')">Std. Value From</label>
											<span class="sortorder" ng-show="predicate === 'standard_value_from'" ng-class="{reverse:reverse}"></span>
										</th>					
										<th>
											<label class="sortlabel" ng-click="sortBy('standard_value_to')">Std. Value To</label>
											<span class="sortorder" ng-show="predicate === 'standard_value_to'" ng-class="{reverse:reverse}"></span>
										</th>
										<th>
											<label class="sortlabel" ng-click="sortBy('parameter_decimal_place')">Decimal Place</label>
											<span class="sortorder" ng-show="predicate === 'parameter_decimal_place'" ng-class="{reverse:reverse}"></span>
										</th>
										<th>
											<label class="sortlabel" ng-click="sortBy('parameter_nabl_scope')">NABL Scope</label>
											<span class="sortorder" ng-show="predicate === 'parameter_nabl_scope'" ng-class="{reverse:reverse}"></span>
										</th>
										<th>
											<label class="sortlabel" ng-click="sortBy('time_taken')">Time Taken</label>
											<span class="sortorder" ng-show="predicate === 'time_taken'" ng-class="{reverse:reverse}"></span>
										</th>
									</tr>
								</thead>
								<tbody>									
									<tr ng-repeat="categoryParameters in testProductParamentersList">
										<td ng-show="categoryParameters.categoryParams.length" colspan="10" data-title="Test Parameter Category Name">
											<table border="1" class="col-sm-12 table-striped table-condensed">
												<thead><th align="left" colspan="10">[[categoryParameters.categoryName]]</th></thead>
												<tbody class="searchAddParameterPopupFilter" ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">													
													<tr id ="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.description.length">
														<td class="width5" data-title="Select Parameter"><input type="checkbox" class="parametersCheckBox" ng-checked="allPopupSelectedParametersArray.indexOf(subCategoryParameters.product_test_dtl_id) > -1" id="checkOneParameter_[[subCategoryParameters.product_test_dtl_id]]" ng-model="selectAllCheckbox" ng-click="funCheckParameterCheckedOrNotValues(subCategoryParameters.product_test_dtl_id);" name="test_parameters[]" value="[[subCategoryParameters.product_test_dtl_id]]"></td>
														<td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
														<td class="width55" colspan="8">[[subCategoryParameters.description]]</td>
													</tr>
													<tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="!subCategoryParameters.description.length">
														<td class="width5" data-title="Select Parameter"><input type="checkbox" class="parametersCheckBox" ng-checked="allPopupSelectedParametersArray.indexOf(subCategoryParameters.product_test_dtl_id) > -1" id="checkOneParameter_[[subCategoryParameters.product_test_dtl_id]]" ng-model="selectAllCheckbox" ng-click="funCheckParameterCheckedOrNotValues(subCategoryParameters.product_test_dtl_id);" name="test_parameters[]" value="[[subCategoryParameters.product_test_dtl_id]]"></td>
														<td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
														<td id="equipment_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Equipment Name">[[subCategoryParameters.equipment_name]]</td>
														<td id="method_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Method Name">[[subCategoryParameters.method_name]]</td>
														<td id="detector_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Detector Name">[[subCategoryParameters.detector_name]]</td>
														<td id="standard_value_from_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value From"><span ng-if="subCategoryParameters.standard_value_from">[[subCategoryParameters.standard_value_from]]</span></td>
														<td id="standard_value_to_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value To"><span ng-if="subCategoryParameters.standard_value_to">[[subCategoryParameters.standard_value_to]]</span></td>
														<td id="parameter_decimal_place_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Decimal Place">[[subCategoryParameters.parameter_decimal_place]]</td>
														<td id="parameter_nabl_scope_text[[subCategoryParameters.parameter_nabl_scope]]" data-title="NABL Scope">[[subCategoryParameters.parameter_nabl_scope | yesOrNo]]</td>
														<td data-title="time taken">
															<span id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.time_taken_days != 0.00 || subCategoryParameters.time_taken_days != 0">[[subCategoryParameters.time_taken_days ? subCategoryParameters.time_taken_days+' Days' : '']]</span>
															<span id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.time_taken_mins != 0.00 || subCategoryParameters.time_taken_mins != 0">[[subCategoryParameters.time_taken_mins ? ' & ' +subCategoryParameters.time_taken_mins+' Mins' : '']]</span>
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
					<button type="button" id="trf_parameters_btn" class="btn btn-primary" ng-click="funGetTestProductStandardParamenters(sampleID,globalProductCategoryID,customerInvoicingTypeID)">OK</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>					
			</form>
		</div>
	</div>
	<style>
	.noteSelected{float: right;margin-right: 62px;padding: 0px 52px;}
	span.noteSelected label {padding: 0px 9px;}
	</style>
</div>
<!-- Modal -->