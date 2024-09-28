<div class="row" ng-hide="addTestParameterFormDiv">
	<div class="panel panel-default">
		<div class="panel-body">
		<form name="erpAddTestParameterDownLoadForm" target= "blank" method="POST" id="erpAddTestParameterDownLoadForm" action="{{url('master/test-parameter/download-excel')}}" novalidate>
				<label for="submit">{{ csrf_field() }}</label>

			<div class="row header1">
				<span><strong class="pull-left headerText">Add Test Parameters</strong></span>
				<span><button class="pull-right btn btn-primary  form-control" style="width:65px" ng-click="showUploadForm()">Upload</button></span>
					<select class="form-control" style="float: right;width: 120px;" onchange="this.form.submit();" name="downloadType">
						<option >Select Download Type</option>
						<option value="excel"> Excel</option>
					</select>
			</div>
		</form>
			<!--add test Parameter Form-->
			<form name='erpAddTestParameterForm' id="erpAddTestParameterForm" novalidate>
				<div class="row">	
					<!--Test Parameter Code-->
					<div class="col-xs-3">
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
					<div class="col-xs-3">
						<label for="test_parameter_name">Parameter Name<em class="asteriskRed">*</em></label>	
						<textarea
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
					<div class="col-xs-3">
						<label for="test_parameter_print_desc">Parameter Description<em class="asteriskRed">*</em></label>
							<textarea
									ui-tinymce="tinyMceOptions"
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
					<div class="col-xs-3">
						<label for="equipment_type_id">Equipment Type<em class="asteriskRed">*</em></label>
						<a href="javascript:;" style="float:right;" class="font12" ng-click="funResetEquipmentTypes('equipment_type_id');">Reset</a>
						<select class="form-control"
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
				
				<div class="row mT20">
				<!--Test Parameter Category-->
					<div class="col-xs-3">
						<label for="test_parameter_category_id">Parameter Category<em class="asteriskRed">*</em></label>
						<a title="Select Parameter Category" data-toggle="modal" ng-click="showParameterCatTreeViewPopUp(9)" class='generate cursor-pointer'>Tree View</a>
						<select class="form-control"
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
					<!--cost price-->
					<div class="col-xs-3">
						<label for="test_parameter_category_id">Cost Price<em class="asteriskRed">*</em></label>
						<input type="text" class="p_input_type form-control" ng-model="addTestParameter.cost_price" name="cost_price" id="cost_price" min="0" ng-required="true" placeholder="Cost Price">
						<span ng-messages="erpAddTestParameterForm.cost_price.$error" ng-if='erpAddTestParameterForm.cost_price.$dirty || erpAddTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Cost price is required</span>
						</span>
					</div>
					<!--/cost price-->
					<!--Selling price-->
					<div class="col-xs-3">
						<label for="test_parameter_category_id">Selling Price<em class="asteriskRed">*</em></label>
						<input type="text" class="p_input_type form-control" ng-model="addTestParameter.selling_price" name="selling_price" id="selling_price" min="0" ng-required="true" placeholder="Selling Price">
						<span ng-messages="erpAddTestParameterForm.selling_price.$error" ng-if='erpAddTestParameterForm.selling_price.$dirty || erpAddTestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Selling price is required</span>
						</span>
					</div>
					<!--/Selling price-->
					<!--Save button-->
					<div class="mT26 col-xs-3 pull-right">
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