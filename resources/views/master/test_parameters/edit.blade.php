<div class="row" ng-hide="editTestParameterFormDiv">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Test Parameters</strong></span>
			</div>
			<!--Edit Test Parameter Form-->
			<form name='erpEditTestParameterForm' id="erpEditTestParameterForm" novalidate>		
				<div class="row">
				
					<!--Test Parameter Code-->
					<div class="col-xs-3 form-group">						    
						<label for="test_parameter_code">Parameter Code</label>						   
						<input readonly
							type="text"
							class="form-control"																
							id="test_parameter_code"
							ng-model="editTestParameter.test_parameter_code"									
							placeholder="Parameter Code" />							
					</div>
					<!--/Test Parameter Code-->
					
					<!--Test Parameter Name-->
					<div class="col-xs-3 form-group">
						<span class="generate"><label class="checkbox-inline"><input type="checkbox" id="tpn_edit_editor_status" ng-checked="editTestParameter.tpn_editor_status == 1" ng-click="funEnableDisableTinyMceEditor('tpn_edit_editor_status')" name="tpn_editor_status" value="1">Enable</label></span>
						<label for="test_parameter_name">Parameter Name<em class="asteriskRed">*</em></label>
						<textarea
							ng-if="editTestParameter.tpn_editor_status == 0"
							class="form-control height80" 
							ng-model="editTestParameter.test_parameter_name"
							name="test_parameter_name"						
							id="test_parameter_name"
							ng-required='true'
							ng-change="editTestParameter.test_parameter_print_desc=editTestParameter.test_parameter_name"		
							placeholder="Parameter Name">
						</textarea>
						<textarea
							ng-if="editTestParameter.tpn_editor_status == 1"
							ui-tinymce="tinyMceOptions"
							class="form-control" 
							ng-model="editTestParameter.test_parameter_name"
							name="test_parameter_name"						
							id="test_parameter_name"
							ng-required='true'
							ng-change="editTestParameter.test_parameter_print_desc=editTestParameter.test_parameter_name"		
							placeholder="Parameter Name">
						</textarea>
						<span ng-messages="erpEditTestParameterForm.test_parameter_name.$error" ng-if='erpEditTestParameterForm.test_parameter_name.$dirty || erpEditTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parameter name is required</span>
						</span>
					</div>
					<!--/Test Parameter Name-->
					
					<!--Test Parameter Print Description-->
					<div class="col-xs-3 form-group">
						<span class="generate"><label class="checkbox-inline"><input type="checkbox" id="tpd_edit_editor_status" ng-checked="editTestParameter.tpd_editor_status == 1" ng-click="funEnableDisableTinyMceEditor('tpd_edit_editor_status')" name="tpd_editor_status" value="1">Enable</label></span>
						<label for="test_parameter_print_desc">Parameter Description<em class="asteriskRed">*</em></label>
						<textarea
							ng-if="editTestParameter.tpd_editor_status == 0"
							class="form-control height80" 
							ng-model="editTestParameter.test_parameter_print_desc"
							name="test_parameter_print_desc" 
							id="test_parameter_print_desc"
							ng-required='true'
							placeholder="Parameter Description">
						</textarea>
						<textarea
							ng-if="editTestParameter.tpd_editor_status == 1"
							ui-tinymce="tinyMceOptions"
							class="form-control" 
							ng-model="editTestParameter.test_parameter_print_desc"
							name="test_parameter_print_desc" 
							id="test_parameter_print_desc"
							ng-required='true'
							placeholder="Parameter Description">
						</textarea>
						<span ng-messages="erpEditTestParameterForm.test_parameter_print_desc.$error" ng-if='erpEditTestParameterForm.test_parameter_print_desc.$dirty  || erpEditTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parameter description is required</span>
						</span>
					</div>
					<!--/Test Parameter Print Description-->
					
					<!--Equipment Type-->
					<div class="col-xs-3 form-group">
						<label for="equipment_type_id">Equipment Type<em class="asteriskRed">*</em></label>
						<a href="javascript:;" style="float:right;" class="font12" ng-click="funResetEquipmentTypes('edit_equipment_type_id');">Reset</a>
						<select class="form-control" multiple
								name="equipment_type_id[]"
								ng-model="equipmentSelectedOption"
								id="edit_equipment_type_id"
								ng-required='false'>
							<option value="">Select Equipment Type</option>
							<option ng-repeat="obj in equipmentTypesList" value="[[obj.id]]" id="equip_[[obj.id]]">[[obj.name]]</option>
						</select>
						<span class="textGreen">press ctrl to select multiple</span>
					</div>
					<!--/Equipment Type-->
				</div>
					
				<div class="row">
				
					<!--Test Parameter Category-->
					<div class="col-xs-3 form-group">
						<label for="test_parameter_category_id">Test Parameter Category<em class="asteriskRed">*</em></label>
						<a title="Select Parameter Category" data-toggle="modal" ng-click="showParameterCatTreeViewPopUp(9)" class='generate cursor-pointer'>Tree View</a>
						<select class="form-control"
								name="test_parameter_category_id"
								ng-model="editTestParameter.test_parameter_category_id.selectedOption"
								ng-required='true'
								ng-options="testParameterCategory.name for testParameterCategory in testParameterCategoryList track by testParameterCategory.id ">
							<option value="">Select Parameter Category</option>
						</select>
						<span ng-messages="erpEditTestParameterForm.test_parameter_category_id.$error" ng-if='erpEditTestParameterForm.test_parameter_category_id.$dirty || erpEditTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parameter category is required</span>
						</span>
					</div>
					<!--/Test Parameter Category-->
					
					<!--Decimal Place-->
					<div class="col-xs-3 form-group">
						<label for="test_parameter_decimal_place">Decimal Place(For Limit)<em class="asteriskRed">*</em></label>
						<input
							type="text"
							class="form-control"
							ng-model="editTestParameter.test_parameter_decimal_place"
							name="test_parameter_decimal_place"
							id="test_parameter_decimal_place"
							min="0"
							ng-required="true"
							placeholder="Decimal Place(For Result)">
						<span ng-messages="erpEditTestParameterForm.test_parameter_decimal_place.$error" ng-if='erpEditTestParameterForm.test_parameter_decimal_place.$dirty || erpEditTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Decimal Place is required</span>
						</span>
						<p class="note-info">(Ex:To Display 1.45320 to 3 Decimal Places,please enter 3,then System will display 1.453)</p>
					</div>
					<!--/Decimal Place-->
					
					<!--cost price-->
					<div class="col-xs-3 form-group">
						<label for="cost_price">Cost Price<em class="asteriskRed">*</em></label>
						<input type="text" class="p_input_type form-control" ng-model="editTestParameter.cost_price" name="cost_price" id="cost_price" min="0" ng-required="true" placeholder="Cost Price">
						<span ng-messages="erpEditTestParameterForm.cost_price.$error" ng-if='erpEditTestParameterForm.cost_price.$dirty || erpEditTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Cost price is required</span>
						</span>
					</div>
					<!--/cost price-->
					
					<!--Selling price-->
					<div class="col-xs-3 form-group">
						<label for="selling_price">Selling Price<em class="asteriskRed">*</em></label>
						<input type="text" class="p_input_type form-control" ng-model="editTestParameter.selling_price" name="selling_price" id="selling_price" min="0" ng-required="true" placeholder="Selling Price">
						<span ng-messages="erpEditTestParameterForm.selling_price.$error" ng-if='erpEditTestParameterForm.selling_price.$dirty || erpEditTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Selling price is required</span>
						</span>
					</div>
					<!--/Selling price-->
				</div>
		
				<div class="row">
					
					<!--Parent Parameter Invoicing-->
					<div class="col-xs-3 form-group">
						<label for="test_parameter_invoicing_parent_id">Parent Parameter Invoicing</label>
						<select
							class="form-control"
							name="test_parameter_invoicing_parent_id"
							ng-model="editTestParameter.test_parameter_invoicing_parent_id.selectedOption"
							ng-options="testParameterInvoicingParent.name for testParameterInvoicingParent in testParameterInvoicingParentList track by testParameterInvoicingParent.id">
							<option value="">Select Parent Parameter Invoicing</option>
						</select>
					</div>
					<!--/Parent Parameter Invoicing-->
				
					<!--Parameter Invoicing-->
					<div class="col-xs-3 form-group">
						<label for="test_parameter_invoicing">Parameter Invoicing</label>
						<div class="checkbox">
							<label for="test_parameter_invoicing">
								<input type="checkbox" ng-checked="testParameterInvoicing" ng-model="editTestParameter.test_parameter_invoicing" name="test_parameter_invoicing" id="test_parameter_invoicing">Parameter Invoicing
							</label>
						</div>
					</div>
					<!--/Parameter Invoicing-->
					
					<!--Test Parameter NABL Scope-->
					<div class="col-xs-3 form-group">
						<label for="test_parameter_nabl_scope">NABL Scope</label>
						<div class="checkbox">
						    <label for="test_parameter_nabl_scope">
							<input type="checkbox" ng-checked="testParameterNablScope" ng-model="editTestParameter.test_parameter_nabl_scope" name="test_parameter_nabl_scope" id="test_parameter_nabl_scope">Check to Consider
						    </label>
						</div>
					</div>
					<!--/Test Parameter NABL Scope-->

					<!-- status---->
					<div class="col-xs-3">
						<label for="status">Status<em class="asteriskRed">*</em></label>	
						<select class="form-control" 
							ng-required='true'  
							name="status" 
							id="status" 
							ng-options="status.name for status in statusList track by status.id"
							ng-model="editTestParameter.status.selectedOption">
							<option value="">Select Status</option>
						</select>				   
		
						<span ng-messages="erpEditTestParameterForm.status.$error" ng-if='erpEditTestParameterForm.status.$dirty  || erpEditTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>
					<!-- /status---->

				</div>

				<div class="row">
				
					<!--Include In Parent Parameter Invoicing-->
					<div class="col-xs-3 form-group">
						<label for="tpip_status_id">Include In Parent Parameter Invoicing</label>
						<div class="checkbox">
							<label for="tpip_status_id">
								<input type="checkbox" ng-checked="tpipStatusSelection" ng-model="editTestParameter.tpip_status_id" name="tpip_status_id" id="tpip_status_id">Check to Consider
							</label>
						</div>
					</div>
					<!--/Include In Parent Parameter Invoicing-->	

					<!--Update Button-->
					<div class="col-xs-3 form-group mT10">
						<label for="submit">{{ csrf_field() }}</label>	
						<input type='hidden' id='test_parameter_id' name="test_parameter_id" ng-model="editTestParameter.test_parameter_id" ng-value="editTestParameter.test_parameter_id"> 
						<button title="Update" ng-disabled="erpEditTestParameterForm.$invalid" type='submit' id='edit_button' class=' btn btn-primary' ng-click='updateRecord(testParameterCategory)'>Update</button>
						<button title="Close" type="button" class="btn btn-default" ng-click="closeButton()">Close</button>
					</div>
					<!--/Update Button-->
					
				</div>
					
			</form>
			<!--/Edit Test Parameter Form-->
		</div>
	</div>
</div>