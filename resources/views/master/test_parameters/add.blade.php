<div class="row" ng-hide="addTestParameterFormDiv">
    <div class="panel panel-default">
		<div class="panel-body">
			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Test Parameters</strong></span>
				<span><button class="pull-right btn btn-primary  form-control" style="width:65px" ng-click="showUploadForm()">Upload</button></span>
			</div>
			
			<!--add test Parameter Form-->
			<form name='erpAddTestParameterForm' id="erpAddTestParameterForm" novalidate>
				<div class="row">	
					
					<!--Test Parameter Code-->
					<div class="col-xs-3 form-group">
						<span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>     
						<label for="test_parameter_code">Parameter Code<em class="asteriskRed">*</em></label>						   
						<input type="text"
							class="form-control"
							readonly									
							name="test_parameter_code" 
							id="test_parameter_code"
							ng-model="addTestParameter.test_parameter_code"
							ng-value="default_test_parameter_code"
							placeholder="Parameter Code" />
						<span ng-messages="erpAddTestParameterForm.test_parameter_code.$error" ng-if='erpAddTestParameterForm.test_parameter_code.$dirty  || erpAddTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parameter code is required</span>
						</span>
					</div>
					<!--/Test Parameter Code-->
					
					<!--Test Parameter Name-->
					<div class="col-xs-3 form-group">
						<span class="generate"><label class="checkbox-inline"><input type="checkbox" id="tpn_add_editor_status" ng-model="addTestParameter.tpn_editor_status" name="tpn_editor_status" value="1">Enable</label></span> 
						<label for="test_parameter_name">Parameter Name<em class="asteriskRed">*</em></label>	
						<textarea
							ng-if="!addTestParameter.tpn_editor_status"
							class="form-control height80" 
							ng-model="addTestParameter.test_parameter_name"
							name="test_parameter_name" 
							id="test_parameter_name"
							ng-required="true"
							ng-change="addTestParameter.test_parameter_print_desc=addTestParameter.test_parameter_name"
							placeholder="Parameter Name">
						</textarea>
						<textarea
							ng-if="addTestParameter.tpn_editor_status"
							ui-tinymce="tinyMceOptions"
							class="form-control" 
							ng-model="addTestParameter.test_parameter_name"
							name="test_parameter_name" 
							id="test_parameter_name"
							ng-required="true"
							ng-change="addTestParameter.test_parameter_print_desc=addTestParameter.test_parameter_name"
							placeholder="Parameter Name">
						</textarea>
						<span ng-messages="erpAddTestParameterForm.test_parameter_name.$error" ng-if='erpAddTestParameterForm.test_parameter_name.$dirty || erpAddTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parameter name is required</span>
						</span>
					</div>
					<!--/Test Parameter Name-->
					
					<!--Test Parameter Description-->
					<div class="col-xs-3 form-group">
						<span class="generate"><label class="checkbox-inline"><input type="checkbox" id="tpd_add_editor_status" ng-model="addTestParameter.tpd_editor_status" name="tpd_editor_status" value="1">Enable</label></span> 
						<label for="test_parameter_print_desc">Parameter Description<em class="asteriskRed">*</em></label>
						<textarea
							ng-if="!addTestParameter.tpd_editor_status"
							class="form-control height80"									
							ng-model="addTestParameter.test_parameter_print_desc"
							name="test_parameter_print_desc" 
							id="test_parameter_print_desc"
							ng-required='true'
							placeholder="Parameter Description">
						</textarea>
						<textarea
							ui-tinymce="tinyMceOptions"
							ng-if="addTestParameter.tpd_editor_status"
							class="form-control"									
							ng-model="addTestParameter.test_parameter_print_desc"
							name="test_parameter_print_desc" 
							id="test_parameter_print_desc"
							ng-required='true'
							placeholder="Parameter Description">
						</textarea>
						<span ng-messages="erpAddTestParameterForm.test_parameter_print_desc.$error" 
						ng-if='erpAddTestParameterForm.test_parameter_print_desc.$dirty || erpAddTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parameter description is required</span>
						</span>
					</div>
					<!--/Test Parameter Description-->
					
					<!--Equipment Type-->
					<div class="col-xs-3 form-group">
						<label for="equipment_type_id">Equipment Type<em class="asteriskRed">*</em></label>
						<a href="javascript:;" style="float:right;" class="font12" ng-click="funResetEquipmentTypes('equipment_type_id');">Reset</a>
						<select
							class="form-control"
							multiple
							name="equipment_type_id[]"
							ng-model="addTestParameter.equipment_type_id"
							id="equipment_type_id"
							ng-required='true'
							ng-options="item.name for item in equipmentTypesList track by item.id">
						<option value="">Select Equipment Type</option>
						</select>
						<span ng-messages="erpAddTestParameterForm.equipment_type_id.$error" ng-if='erpAddTestParameterForm.equipment_type_id.$dirty  || erpAddTestParameterForm.$submitted' role="alert">
						<span ng-message="required" class="error">Select Equipment Type</span>
						</span>
						<span class="textGreen">press ctrl to select multiple</span>
					</div>
					<!--/Equipment Type-->
				</div>
				
				<div class="row">
					
					<!--Test Parameter Category-->
					<div class="col-xs-3 form-group">
						<label for="test_parameter_category_id">Parameter Category<em class="asteriskRed">*</em></label>
						<a title="Select Parameter Category" data-toggle="modal" ng-click="showParameterCatTreeViewPopUp(9)" class='generate cursor-pointer'>Tree View</a>
						<select
							class="form-control"
							name="test_parameter_category_id"
							ng-model="addTestParameter.test_parameter_category_id.selectedOption"
							ng-required='true'
							ng-options="testParameterCategory.name for testParameterCategory in testParameterCategoryList track by testParameterCategory.id ">
							<option value="">Select Parameter Category</option>
						</select>
						<span ng-messages="erpAddTestParameterForm.test_parameter_category_id.$error" ng-if='erpAddTestParameterForm.test_parameter_category_id.$dirty || erpAddTestParameterForm.$submitted' role="alert">
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
							ng-model="addTestParameter.test_parameter_decimal_place"
							name="test_parameter_decimal_place"
							id="test_parameter_decimal_place"
							min="0"
							ng-required="true"
							placeholder="Decimal Place(For Result)">
						<span ng-messages="erpAddTestParameterForm.test_parameter_decimal_place.$error" ng-if='erpAddTestParameterForm.test_parameter_decimal_place.$dirty || erpAddTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Decimal Place is required</span>
						</span>
						<p class="note-info">(Ex:To Display 1.45320 to 3 Decimal Places,please enter 3,then System will display 1.453)</p>
					</div>
					<!--/Decimal Place-->
					
					<!--cost price-->
					<div class="col-xs-3 form-group">
						<label for="cost_price">Cost Price<em class="asteriskRed">*</em></label>
						<input type="text" class="p_input_type form-control" ng-model="addTestParameter.cost_price" name="cost_price" id="cost_price" min="0" ng-required="true" placeholder="Cost Price">
						<span ng-messages="erpAddTestParameterForm.cost_price.$error" ng-if='erpAddTestParameterForm.cost_price.$dirty || erpAddTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Cost price is required</span>
						</span>
					</div>
					<!--/cost price-->
					
					<!--Selling price-->
					<div class="col-xs-3 form-group">
						<label for="selling_price">Selling Price<em class="asteriskRed">*</em></label>
						<input type="text" class="p_input_type form-control" ng-model="addTestParameter.selling_price" name="selling_price" id="selling_price" min="0" ng-required="true" placeholder="Selling Price">
						<span ng-messages="erpAddTestParameterForm.selling_price.$error" ng-if='erpAddTestParameterForm.selling_price.$dirty || erpAddTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Selling price is required</span>
						</span>
					</div>
					<!--/Selling price-->		    
							
				</div>
				
				<div class="row">
					
					<!--Parent Parameter Invoicing-->
					<div class="col-xs-3 form-group" ng-init="getTestParameterInvoicingParents();">
						<label for="test_parameter_invoicing_parent_id">Parent Parameter Invoicing</label>
						<select
							class="form-control"
							name="test_parameter_invoicing_parent_id"
							ng-model="addTestParameter.test_parameter_invoicing_parent_id"
							ng-options="testParameterInvoicingParent.name for testParameterInvoicingParent in testParameterInvoicingParentList track by testParameterInvoicingParent.id">
							<option value="">Select Parent Parameter Invoicing</option>
						</select>
					</div>
					<!--/Parent Parameter Invoicing-->
					
					<!--Parameter Invoicing-->
					<div class="col-xs-3 form-group" ng-init="addTestParameter.test_parameter_invoicing=true">
						<label for="test_parameter_invoicing">Parameter Invoicing</label>
						<div class="checkbox">
							<label for="test_parameter_invoicing">
							<input type="checkbox" ng-model="addTestParameter.test_parameter_invoicing" name="test_parameter_invoicing" id="test_parameter_invoicing">Check to Consider
							</label>
						</div>
					</div>
					<!--/Parameter Invoicing-->
					
					<!--Test Parameter NABL Scope-->
					<div class="col-xs-3 form-group">
						<label for="test_parameter_nabl_scope">NABL Scope</label>
						<div class="checkbox">
							<label for="test_parameter_nabl_scope">
							<input type="checkbox" ng-model="addTestParameter.test_parameter_nabl_scope" name="test_parameter_nabl_scope" id="test_parameter_nabl_scope">Check to Consider
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
							ng-model="addTestParameter.status.selectedOption">
							<option value="">Select Status</option>
						</select>				   

						<span ng-messages="erpAddTestParameterForm.status.$error" ng-if='erpAddTestParameterForm.status.$dirty  || erpAddTestParameterForm.$submitted' role="alert">
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
								<input type="checkbox" ng-model="addTestParameter.tpip_status_id" name="tpip_status_id" id="tpip_id_edit">Check to Consider
							</label>
						</div>
					</div>
					<!--/Include In Parent Parameter Invoicing-->	

					<!--Save button-->
					<div class="col-xs-3 form-group mT10">
						<label for="submit">{{ csrf_field() }}</label>	
						<button title="Save" ng-disabled="erpAddTestParameterForm.$invalid" type='submit' id='add_button' class='btn btn-primary' ng-click='addRecord(testParameterCategory)'> Save </button>
						<button title="Reset"  type="button" class="btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>
					</div>
					<!--/Save button-->		
					
				</div>
			
			</form>
			<!--/add test Parameter Form-->
		</div>
    </div>
</div>