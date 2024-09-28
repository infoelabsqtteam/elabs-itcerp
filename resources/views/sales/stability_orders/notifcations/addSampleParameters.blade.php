<div class="row order-section">Products & Tests Detail</div>
<div class="order_detail">
	 <div class="row mT10">

		  <!--Test Product-->
		  <div class="col-xs-3 form-group">
			   <label for="product_id">Testing Product<em class="asteriskRed">*</em></label>
			   <select
				    class="form-control"
				    name="product_id"
				    ng-model="updateOrder.product_id"
				    id="product_id"
				    ng-required="true"
				    ng-options="products.product_name for products in testProductList track by products.product_id">
			   </select>
			   <input type="hidden" name="product_category_id" id="product_category_id" ng-value="updateOrder.stb_product_category_id" ng-model="updateOrder.stb_product_category_id">
			   <span ng-messages="erpCreateOrderForm.product_id.$error" ng-if='erpCreateOrderForm.product_id.$dirty || erpCreateOrderForm.$submitted' role="alert">
				    <span ng-message="required" class="error">Testing Product is required</span>
			   </span>
		  </div>
		  <!--/Test Product-->
		  
		  <!--Product Tests-->
		  <div class="col-xs-3 form-group">
			   <label for="product_test_id" class="width100pc">Product Tests<em class="asteriskRed">*</em></label>
			   <select
				    class="form-control"
				    name="product_test_id"
				    ng-model="updateOrder.product_test_id"
				    id="product_test_id"
				    ng-required="true"
				    ng-options="productTest.test_code for productTest in productTestListing track by productTest.test_id">
			   </select>
			   <input type="hidden" ng-value="updateOrder.stb_test_standard_id" name="test_standard" ng-model="updateOrder.stb_test_standard_id" id="test_standard">
			   <span ng-messages="erpCreateOrderForm.product_test_id.$error" ng-if='erpCreateOrderForm.product_test_id.$dirty || erpCreateOrderForm.$submitted' role="alert">
				    <span ng-message="required" class="error">Product Tests is required</span>
			   </span>
		  </div>
		  <!--/Product Tests-->

		  <!--Header Note-->
		  <div class="col-xs-3 form-group">
			   <label for="header_note">Header note</label>
			   <input
				   class="form-control"
				   name="header_note"
				   ng-model="updateOrder.header_note"
				   id="header_note"
				   ng-change="getAutoSearchHeaderNoteMatches(updateOrder.header_note);"
				   placeholder="Header note"
				   autocomplete="off">
			   <span ng-messages="erpCreateOrderForm.header_note.$error" ng-if='erpCreateOrderForm.header_note.$dirty || erpCreateOrderForm.$submitted' role="alert">
				   <span ng-message="required" class="error">Header note is required</span>
			   </span>
			   <ul ng-if="showHeaderNoteAutoSearchList" class="autoSearch">
				   <li ng-if="headerNotesList.length" ng-repeat="headerNoteObj in headerNotesList" data-title="[[headerNoteObj.id]]" ng-click="funSetSelectedHeaderNote(headerNoteObj.name,headerNoteObj.header_limit,'add');" ng-bind="headerNoteObj.name"></li>
			   </ul>
		  </div>
		  <!--/Header Note-->
	  
		  <!--Stability Note-->
		  <div class="col-xs-3 form-group">
			   <label for="stability_note">Real Time Stability</label>
			   <input
				    class="form-control"
				    name="stability_note"
				    ng-model="updateOrder.stability_note"
				    id="stability_note"
				    ng-value="updateOrder.stability_note"
				    ng-change="getAutoSearchStabilityNoteMatches(updateOrder.stability_note);"
				    placeholder="Stability Note"
				    autocomplete="off">
			   <span ng-messages="erpCreateOrderForm.stability_note.$error" ng-if='erpCreateOrderForm.stability_note.$dirty || erpCreateOrderForm.$submitted' role="alert">
				    <span ng-message="required" class="error">Real Time Stability is required</span>
			   </span>
			   <ul ng-if="showStabilityNoteAutoSearchList" class="autoSearch">
				    <li ng-if="stabilityNotesList.length" ng-repeat="stabilityNoteObj in stabilityNotesList" ng-click="funSetSelectedStabilityNote(stabilityNoteObj.name,'add');" ng-bind="stabilityNoteObj.name"></li>
			   </ul>
		  </div>
		  <!--/Stability Note-->
	  
		  <!--Test to perform-->
		  <div class="col-xs-12 form-group" ng-show="testProductParamentersList.length">
			   <div class="row">
				    <div id="no-more-tables" class="fixed_table">
					     <table border="1" class="col-sm-12 table-striped table-condensed cf">
						      <thead class="cf">
							       <tr>
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
										<label class="sortlabel" ng-click="sortBy('claim_dependent')">Claim(%)</label>
										<span class="sortorder" ng-show="predicate === 'claim_dependent'" ng-class="{reverse:reverse}"></span>
									</th>
									<th>
										<label class="sortlabel" ng-click="sortBy('claim_value_unit')">Claim Unit</label>
										<span class="sortorder" ng-show="predicate === 'claim_value_unit'" ng-class="{reverse:reverse}"></span>
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
									<th ng-if="istableTrTdVisibleID"><label>Running Time</label></th>
										 
									<th ng-if="istableTrTdVisibleID"><label>No. of Injections</label></th>
										 
									<th class="hidden"><label>Action</label></th>
							       </tr>
						      </thead>
						      <tbody class="tableParameterList">
							       <tr ng-repeat="categoryParameters in testProductParamentersList">
									<td ng-show="categoryParameters.categoryParams.length" colspan="[[tableTrTdColspanLevelI]]" data-title="Test Parameter Category Name">
										 <table border="1" class="col-sm-12 table-striped table-condensed">
											  <thead><th align="left" colspan="[[tableTrTdColspanLevelI]]">[[categoryParameters.categoryName]]</th></thead>
											  <tbody ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
												   <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.description.length">
													    <td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
													    <td class="width100pc" colspan="[[tableTrTdColspanLevelII]]">
														<span>[[subCategoryParameters.description]]</span>
														<input type="hidden" value="[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[product_test_parameter][]">
														<input type="hidden" value="[[subCategoryParameters.test_parameter_id]]" name="order_parameters_detail[test_parameter_id][]" >
														<input type="hidden" value="" name="order_parameters_detail[equipment_type_id][]" id="equipment_type_id[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[method_id][]" id="method_id[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[detector_id][]" id="detector_id[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[standard_value_type][]" id="standard_value_type[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[standard_value_from][]" id="standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[standard_value_to][]" id="standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[order_parameter_nabl_scope][]" id="order_parameter_nabl_scope[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[time_taken_days][]" id="time_taken_days[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[time_taken_mins][]" id="time_taken_mins[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[display_decimal_place][]" id="display_decimal_place[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[cost_price][]" id="cost_price[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[selling_price][]" id="selling_price[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="0" name="order_parameters_detail[test_param_alternative_id][]" id="test_param_alternative_id[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[claim_value][]" id="claim_value[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" value="" name="order_parameters_detail[claim_value_unit][]" id="claim_value_unit[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" ng-if="istableTrTdVisibleID" value="" name="order_parameters_detail[running_time_id][]" id="running_time_id_[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" ng-if="istableTrTdVisibleID" value="" name="order_parameters_detail[no_of_injection][]" id="no_of_injection_[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" ng-if="istableTrTdVisibleID" value="[[subCategoryParameters.cwap_invoicing_required]]" name="order_parameters_detail[cwap_invoicing_required][]" id="cwap_invoicing_required_[[subCategoryParameters.product_test_dtl_id]]">
														<input type="hidden" ng-if="istableTrTdVisibleID" value="[[subCategoryParameters.test_parameter_invoicing_parent_id]]" name="order_parameters_detail[test_parameter_invoicing_parent_id][]" id="test_parameter_invoicing_parent[[subCategoryParameters.product_test_dtl_id]]">
													    </td>
												   </tr>
												   <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="!subCategoryParameters.description.length">
													    <td id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name">
														     <span class="parameterLft" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
														     <span class="parameterRht" ng-if="subCategoryParameters.defined_invoicing_rate == '0' && customerInvoicingTypeID == '4' "><i class="fa fa-times-circle btn-danger parameterIcon" aria-hidden="true"></i></span>
														     <span class="parameterRht" ng-if="subCategoryParameters.defined_invoicing_rate && subCategoryParameters.defined_invoicing_rate != '0' && customerInvoicingTypeID == '4' "><i class="fa fa-check-circle btn-success parameterIcon" aria-hidden="true"></i></span>	
													    </td>
													    <td id="equipment_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Equipment Name">[[subCategoryParameters.equipment_name]]</td>
													    <td id="method_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Method Name">[[subCategoryParameters.method_name]]</td>
													    <td id="detector_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Detector Name">[[subCategoryParameters.detector_name]]</td>
													    <td align="left" id="claim_value_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value">
														     <input
															      type="text"
															      ng-if="subCategoryParameters.claim_dependent"
															      class="form-control widthPx80"
															      id="claim_value[[subCategoryParameters.product_test_dtl_id]]"
															      ng-change="funChangeTestParameterValueAccToClaim('[[subCategoryParameters.product_test_dtl_id]]','add')"
															      ng-model="updateOrder.claim_value[[subCategoryParameters.product_test_dtl_id]]"
															      name="order_parameters_detail[claim_value][]"
															      placeholder="Claim Value">
														     <input type="hidden" ng-if="subCategoryParameters.claim_dependent" name="order_parameters_detail[claim_dependent][]" value="[[subCategoryParameters.claim_dependent]]">
														     <input type="hidden" ng-if="!subCategoryParameters.claim_dependent" id="claim_value[[subCategoryParameters.product_test_dtl_id]]" ng-model="updateOrder.claim_value[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[claim_value][]" value="">
														     <input type="hidden" value="[[subCategoryParameters.standard_value_from]]" id="org_standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.standard_value_to]]" id="org_standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
													    </td>
													    <td align="left" id="claim_value_unit_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Claim Value Unit">
														     <input
															      type="text"
															      ng-if="subCategoryParameters.claim_dependent"
															      class="form-control widthPx80"
															      id="claim_value_unit[[subCategoryParameters.product_test_dtl_id]]"
															      ng-model="updateOrder.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]"
															      name="order_parameters_detail[claim_value_unit][]"
															      ng-change="funChangeTestParameterValueAccToClaimUnit('[[subCategoryParameters.product_test_dtl_id]]')"
															      placeholder="Claim Unit">
														     <input
															      type="hidden"
															      ng-if="!subCategoryParameters.claim_dependent"
															      id="claim_value_unit[[subCategoryParameters.product_test_dtl_id]]"
															      ng-model="updateOrder.claim_value_unit[[subCategoryParameters.product_test_dtl_id]]"
															      name="order_parameters_detail[claim_value_unit][]">
													    </td>
													    <td id="standard_value_from_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value From"><span ng-if="subCategoryParameters.standard_value_from">[[subCategoryParameters.standard_value_from]]</span></td>
													    <td id="standard_value_to_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value To" ng-if="subCategoryParameters.test_parameter_id != 25235">[[subCategoryParameters.standard_value_to]]</td>
													    <td id="standard_value_to_text_dt[[subCategoryParameters.product_test_dtl_id]]" data-title="Standard Value To" ng-if="subCategoryParameters.test_parameter_id == 25235"><input type="text" readonly class="form-control min-width120" ng-if="subCategoryParameters.test_parameter_id == 25235" value="[[dt_standard_value_to_add]]" placeholder="select Header Note" id="dt_standard_value_to_add[[subCategoryParameters.product_test_dtl_id]]"></td>
													    <td id="display_decimal_place_text[[subCategoryParameters.product_test_dtl_id]]" align="left" data-title="Result Decimal Place">
														     Upto : <input
															      type="text"
															      class="form-control widthPx40"
															      id="display_decimal_place[[subCategoryParameters.product_test_dtl_id]]"
															      ng-model="updateOrder.display_decimal_place[[subCategoryParameters.product_test_dtl_id]]"
															      name="order_parameters_detail[display_decimal_place][]"
															      ng-value="subCategoryParameters.parameter_decimal_place"
															      placeholder="Decimal Place">
													    </td>
													    <td id="parameter_nabl_scope_text[[subCategoryParameters.parameter_nabl_scope]]" data-title="NABL Scope">[[subCategoryParameters.parameter_nabl_scope | yesOrNo]]</td>
													    <td data-title="time taken">
														<span id="time_taken_days_text[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.time_taken_days != 0.00 || subCategoryParameters.time_taken_days != 0">[[subCategoryParameters.time_taken_days ? subCategoryParameters.time_taken_days+' Days' : '']]</span>
														<span id="time_taken_mins_text[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.time_taken_days != 0.00 || subCategoryParameters.time_taken_days != 0">[[subCategoryParameters.time_taken_mins ? ' & ' +subCategoryParameters.time_taken_mins+' Mins' : '']]</span>
													    </td>
													    <td ng-if="istableTrTdVisibleID" align="left" id="running_time_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Running Time">
														     <select
															      ng-if="subCategoryParameters.cwap_invoicing_required == 1"
															      id="running_time_id_[[subCategoryParameters.product_test_dtl_id]]"
															      class="form-control min-width120"
															      name="order_parameters_detail[running_time_id][]"
															      ng-change="funRequiredUnRequiredRunningTimeOrNot(subCategoryParameters.product_test_dtl_id,'add')"
															      ng-model="updateOrder.running_time_id[[subCategoryParameters.product_test_dtl_id]]"
															      ng-options="item.name for item in subCategoryParameters.runningTimeSelectboxList track by item.id">
															      <option value="">Select Running Time</option>
														     </select>
														     <input type="hidden" ng-if="subCategoryParameters.cwap_invoicing_required == 0" value="" name="order_parameters_detail[running_time_id][]" id="running_time_id_[[subCategoryParameters.product_test_dtl_id]]">
													    </td>
													    <td ng-if="istableTrTdVisibleID" align="left" id="no_of_injection_text[[subCategoryParameters.product_test_dtl_id]]" data-title="No. of Injection">
														     <input
															      type="text"
															      ng-if="subCategoryParameters.cwap_invoicing_required == 1"
															      valid-number
															      class="form-control widthPx40"
															      id="no_of_injection_[[subCategoryParameters.product_test_dtl_id]]"
															      ng-model="updateOrder.no_of_injection[[subCategoryParameters.product_test_dtl_id]]"
															      name="order_parameters_detail[no_of_injection][]"
															      placeholder="No. of Injection">
														     <input type="hidden" ng-if="subCategoryParameters.cwap_invoicing_required == 0" value="" name="order_parameters_detail[no_of_injection][]" id="no_of_injection_[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.cwap_invoicing_required]]" name="order_parameters_detail[cwap_invoicing_required][]" id="cwap_invoicing_required_[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.test_parameter_invoicing_parent_id]]" name="order_parameters_detail[test_parameter_invoicing_parent_id][]" id="test_parameter_invoicing_parent[[subCategoryParameters.product_test_dtl_id]]">
													    </td>
													    <td align="center">
														     <input type="hidden" value="[[subCategoryParameters.product_test_dtl_id]]" name="order_parameters_detail[product_test_parameter][]" ng-model="updateOrder.product_test_parameter" id="product_test_parameter[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.test_parameter_id]]" name="order_parameters_detail[test_parameter_id][]" ng-model="updateOrder.test_parameter_id" id="test_parameter_id[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.equipment_type_id]]" name="order_parameters_detail[equipment_type_id][]" ng-model="updateOrder.equipment_type_id" id="equipment_type_id[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.method_id]]" name="order_parameters_detail[method_id][]" ng-model="updateOrder.method_id" id="method_id[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.detector_id]]" name="order_parameters_detail[detector_id][]" ng-model="updateOrder.detector_id" id="detector_id[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.standard_value_type]]" name="order_parameters_detail[standard_value_type][]" ng-model="updateOrder.standard_value_type" id="standard_value_type[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.standard_value_from]]" name="order_parameters_detail[standard_value_from][]" ng-model="updateOrder.standard_value_from" id="standard_value_from[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" ng-if="subCategoryParameters.test_parameter_id != 25235" value="[[subCategoryParameters.standard_value_to]]" name="order_parameters_detail[standard_value_to][]" ng-model="updateOrder.standard_value_to[[subCategoryParameters.product_test_dtl_id]]" id="standard_value_to[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" ng-if="subCategoryParameters.test_parameter_id == 25235" value="[[dt_standard_value_to_add]]" name="order_parameters_detail[standard_value_to][]" ng-model="updateOrder.standard_value_to[[subCategoryParameters.product_test_dtl_id]]" id="standard_value_to_dt[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.parameter_nabl_scope]]" name="order_parameters_detail[order_parameter_nabl_scope][]" ng-model="updateOrder.order_parameter_nabl_scope" id="order_parameter_nabl_scope[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.time_taken_days]]" name="order_parameters_detail[time_taken_days][]" ng-model="updateOrder.time_taken_days" id="time_taken_days[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.time_taken_mins]]" name="order_parameters_detail[time_taken_mins][]" ng-model="updateOrder.time_taken_mins" id="time_taken_mins[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.cost_price]]" name="order_parameters_detail[cost_price][]" ng-model="updateOrder.cost_price" id="cost_price[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="[[subCategoryParameters.selling_price]]" name="order_parameters_detail[selling_price][]" ng-model="updateOrder.selling_price" id="selling_price[[subCategoryParameters.product_test_dtl_id]]">
														     <input type="hidden" value="0" name="order_parameters_detail[test_param_alternative_id][]" ng-model="updateOrder.test_param_alternative_id" id="test_param_alternative_id[[subCategoryParameters.product_test_dtl_id]]">
														     <button type="button" title="Select Alternative" class="hidden btn btn-primary btn-sm" ng-click="alternativeTestParameterPopup([[subCategoryParameters.product_test_dtl_id]])">A</button>
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
		  <!--/Test to perform-->    
	 </div>
</div>	 
    
<div class="row order-section" ng-if="updateOrder.stb_tat_editable">Additional Detail</div>
<div class="order_detail mT20" ng-if="updateOrder.stb_tat_editable">
	 <!--TAT Detail-->
	 <div class="col-xs-3 form-group">
		  <label for="tat_in_days">TAT(In Days)<em class="asteriskRed">*</em></label>
		  <input
			   type="text"
			   valid-number
			   ng-if="updateOrder.stb_tat_editable"
			   class="form-control"
			   name="tat_in_days"
			   ng-model="updateOrder.tat_in_days"
			   ng-value="updateOrder.tat_in_days"
			   id="tat_in_days"
			   ng-required="true"
			   placeholder="TAT in Days">
		  <span ng-messages="erpCreateOrderForm.tat_in_days.$error" ng-if="erpCreateOrderForm.tat_in_days.$dirty || erpCreateOrderForm.$submitted" role="alert">
			   <span ng-message="required" class="error">TAT(In Days) is required</span>
		  </span>
	 </div>
	 <!--/TAT Detail-->
</div>
