<div class="row" ng-show="{{defined('ADD') && ADD}}">
	<div class="panel panel-default" ng-hide="addDefaultRemarksFormDiv">
		<div class="panel-body" ng-model="addDefaultRemarksFormDiv">			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Remark</strong></span>
				<!--<span><button class="pull-right btn btn-primary mT3 mR3" ng-click="showUploadForm()">Upload</button></span>-->
			</div>			
			<!--Add method form-->
			<form name='erpAddDefaultRemarksForm' id="add_remark_form" novalidate>							
				<div class="row">
					<!--Branch -->
					<div class="col-xs-3 form-group">
					    <label for="division_id">Branch<em class="asteriskRed">*</em></label>
					    <select class="form-control"
						name="division_id"
						id="division_id"
						ng-model="addDefaultRemarks.division_id"
						ng-options="division.name for division in divisionsCodeList track by division.id">
						<option value="">Select Branch</option>
					    </select>
					</div>                    
					<!--/Branch -->
					
					<!--Parent Product Category-->
					<div class="col-xs-2" ng-init="fungetParentCategory()">																
						<label for="category_id" class="outer-lable">Department<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="category_id"
								id="category_id"
								ng-model="addDefaultRemarks.category_id"
								ng-options="item.id as item.name for item in (parentCategoryList | filter:searchProduct.text) track by item.id"
								ng-required='true'>
							<option value="">Select Department</option>
						</select>
						<span ng-messages="testStandardForm.category_id.$error" ng-if='testStandardForm.product_category_id.$dirty || testStandardForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parent Category is required</span>
						</span>
					</div>
					<!--/Parent Product Category-->
					
					
					<!--Remarks-->
					<div class="col-xs-3 form-group">
						<label for="default_remark">Remark<em class="asteriskRed">*</em></label>	
						<textarea row="1" type="text" class="form-control" 
								ng-model="addDefaultRemarks.remark_name"
								name="remark_name" 
								id="remark_name"
								ng-required='true'
								placeholder="Remark">
						</textarea>
						<span ng-messages="erpAddDefaultRemarksForm.remark_name.$error" ng-if='erpAddDefaultRemarksForm.remark_name.$dirty || erpAddDefaultRemarksForm.$submitted' role="alert">
						<span ng-message="required" class="error">Remark is required</span>
						</span>
					</div>
					<!--Remarks-->
							
					<div class="col-xs-2 form-group">
						<label for="module_link">Type<em class="asteriskRed">*</em></label>
						<select class="form-control"
						        name="remark_type"
							id="remark_type"
							ng-model="addDefaultRemarks.remark_type"
							ng-required='true'
							ng-options="remarkTypes.remark_type_name for remarkTypes in remarkTypeList track by remarkTypes.remark_type">
							<option value="">Select Remark Type</option>
						</select>
						<span ng-messages="erpAddDefaultRemarksForm.remark_type.$error" ng-if='erpAddDefaultRemarksForm.remark_type.$dirty  || erpAddDefaultRemarksForm.$submitted' role="alert">
							<span ng-message="required" class="error">Remark Type is required</span>
						</span>
					</div>
                  
					
					<!--Remark Type-->
					
					<div class="col-xs-2 form-group">
						<label for="module_status">Remark Status<em class="asteriskRed">*</em></label>
						<select class="form-control"
							name="remark_status"
							id="remark_status"
							ng-model="addDefaultRemarks.default_remark_status"
							ng-required='true'
							ng-options="statusTypes.remark_status_name for statusTypes in remarkStatusList track by statusTypes.remark_status">
							<option value="">Select Module Status</option>
						</select>
						<span ng-messages="erpAddDefaultRemarksForm.remark_type.$error" ng-if='erpAddDefaultRemarksForm.remark_type.$dirty  || erpAddDefaultRemarksForm.$submitted' role="alert">
							<span ng-message="required" class="error">Module Status is required</span>
						</span>
					</div>
                  
					<!--Remark Type-->
						
					
					
					<!--save button-->
					<div class="col-xs-2">
						<label for="submit">{{ csrf_field() }}</label>		
							<span ng-if="{{defined('ADD') && ADD}}">
								<button title="Save" ng-disabled="erpAddDefaultRemarksForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funAddDefaultRemarks(divisionID,departmentID)'>Save</button>
								<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>

							</span>
					</div>
					<!--/save button-->						
				</div>
			</form>
			<!--Add method form-->
		</div>
	</div>
</div>