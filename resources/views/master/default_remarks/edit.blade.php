<div class="row" ng-show="{{defined('EDIT') && EDIT}}">
	<div class="panel panel-default" ng-hide="editDefaultRemarksFormDiv">
		<div class="panel-body" ng-model="editDefaultRemarksFormDiv">			
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Remark</strong></span>
				<span><button class="pull-right btn btn-primary mT3 mR3" ng-click="showUploadForm()">Upload</button></span>
			</div>			
			<!--Add method form-->
			<form name='erpEditDefaultRemarksForm' id="erpEditDefaultRemarksForm" novalidate>							
				<div class="row">
					
					<div class="col-xs-3 form-group">
					    <label for="division_id">Branch<em class="asteriskRed">*</em></label>
					    <select class="form-control"
							name="division_id"
							id="division_id"
							ng-model="editDefaultRemarks.division_id.selectedOption"
							ng-options="division.name for division in divisionsCodeList track by division.id">
						<option value="">Select Branch</option>
					    </select>
					</div>
					
					<!--Parent Product Category-->
					<div class="col-xs-2">																
						<label for="product_category_id" class="outer-lable">Department<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="category_id"
								id="product_category_id"
								ng-model="editDefaultRemarks.product_category_id.selectedOption"
								ng-options="item.id as item.name for item in (parentCategoryList | filter:searchProduct.text) track by item.id"
								ng-required='true'>
							<option value="">Select Department</option>
						</select>
						<span ng-messages="testStandardForm.product_category_id.$error" ng-if='testStandardForm.product_category_id.$dirty || testStandardForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parent Category is required</span>
						</span>
					</div>
					<div class="col-xs-3 form-group">
						<label for="default_remark">Description<em class="asteriskRed">*</em></label>	
						<textarea row="1" type="text" class="form-control" 
							ng-model="editDefaultRemarks.remark_name"
							name="remark_name" 
							id="remark_name"
							ng-required='true'
							placeholder="Remark">
						</textarea>
						<span ng-messages="erpEditDefaultRemarksForm.remark_name.$error" ng-if='erpAddDefaultRemarksForm.remark_name.$dirty || erpAddDefaultRemarksForm.$submitted' role="alert">
							<span ng-message="required" class="error">Description is required</span>
						</span>
					</div>
							
                                        <div class="col-xs-2 form-group">
						<label for="module_link">Type<em class="asteriskRed">*</em></label>
						<select class="form-control"
						        name="remark_type"
							id="remark_type"
							ng-model="editDefaultRemarks.remark_type.selectedOption"
							ng-required='true'
							ng-options="remarkTypes.remark_type_name for remarkTypes in remarkTypeList track by remarkTypes.remark_type">
							<option value="">Select Remark Type</option>
						</select>
						<span ng-messages="erpEditDefaultRemarksForm.remark_type.$error" ng-if='erpAddDefaultRemarksForm.remark_type.$dirty  || erpAddDefaultRemarksForm.$submitted' role="alert">
							<span ng-message="required" class="error">Remark Type is required</span>
						</span>
					</div>
                  
					
					<!--Remark Type-->
					
					<div class="col-xs-2 form-group">
						<label for="module_status">Status<em class="asteriskRed">*</em></label>
						<select class="form-control"
							name="remark_status"
							id="remark_status"
							ng-model="editDefaultRemarks.remark_status.selectedOption"
							ng-required='true'
							ng-options="statusTypes.remark_status_name for statusTypes in remarkStatusList track by statusTypes.remark_status">
							<option value="">Select Module Status</option>
						</select>
						<span ng-messages="erpEditDefaultRemarksForm.remark_status.$error" ng-if='erpEditDefaultRemarksForm.remark_status.$dirty  || erpEditDefaultRemarksForm.$submitted' role="alert">
							<span ng-message="required" class="error">Remark Status is required</span>
						</span>
					</div>
                  
					<!--Remark Type-->
						
					
					
					<!--save button-->
					<div class="col-xs-2">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" name="remark_id" ng-value="editDefaultRemarks.remark_id" ng-model="editDefaultRemarks.remark_id">
						<span ng-if="{{defined('EDIT') && EDIT}}">
							<button title="Save" ng-disabled="erpEditDefaultRemarksForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funUpdateDefaultRemarks(divisionID,departmentID)'>Save</button>
						<button title="Close" type='button' class='mT26 btn btn-default btn-sm' ng-click='showAddForm()'>Close</button>

						</span>
					</div>
					<!--/save button-->						
				</div>
			</form>
			<!--Add method form-->
		</div>
	</div>
</div>