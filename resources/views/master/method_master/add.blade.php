<div class="row" ng-show="{{defined('ADD') && ADD}}">
	<div class="panel panel-default" ng-hide="addMethodFormDiv">
		<div class="panel-body" ng-model="addMethodFormDiv">			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Method</strong></span>
				<span><button class="pull-right btn btn-primary mT3 mR3" ng-click="showUploadForm()">Upload</button></span>
			</div>			
			<!--Add method form-->
			<form name='erpAddMethodForm' id="add_method_form" novalidate>							
				<div class="row">					
					<!--Method Code-->
					<div class="col-xs-2">
						<span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
						<label for="method_code">Method Code<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" readonly
									ng-model="addMethod.method_code"
									ng-value="default_method_code"
									name="method_code" 
									id="method_code"
									placeholder="Method Code" />
							<span ng-messages="erpAddMethodForm.method_code.$error" ng-if='erpAddMethodForm.method_code.$dirty  || erpAddMethodForm.$submitted' role="alert">
								<span ng-message="required" class="error">Method code is required</span>
							</span>
					</div>
					<!--/Method Code-->
					
					<!--Method Name-->
					<div class="col-xs-2">
						<label for="method_name">Method Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="addMethod.method_name"
									ng-change="addMethod.method_desc=addMethod.method_name"
									name="method_name" 
									id="method_name"
									ng-required='true'
									placeholder="Method Name" />
							<span ng-messages="erpAddMethodForm.method_name.$error" ng-if='erpAddMethodForm.method_name.$dirty || erpAddMethodForm.$submitted' role="alert">
								<span ng-message="required" class="error">Method name is required</span>
							</span>
					</div>
					<!--/Method Name-->
					
					<!--Method Description-->
					<div class="col-xs-2">
						<label for="method_desc">Method Description<em class="asteriskRed">*</em></label>
							<textarea rows=1 type="text" class="form-control" 
									ng-model="addMethod.method_desc"
									name="method_desc" 
									id="method_desc"
									ng-required='true'
									placeholder="Method Description" /></textarea>
							<span ng-messages="erpAddMethodForm.method_desc.$error" ng-if='erpAddMethodForm.method_desc.$dirty  || erpAddMethodForm.$submitted' role="alert">
								<span ng-message="required" class="error">Method description is required</span>
							</span>
					</div>
					<!--/Method Description-->
					
					<!--Equipment Type-->
					<div class="col-xs-2">
						<label for="equipment_type_id" class="outer-lable">
							 <span class="filter-lable">Equipment Type<em class="asteriskRed">*</em></span>
							 <span class="filterCatLink"><a title="Search Equipment Type" ng-hide="searchFilterBtn" href="javascript:;" ng-click="showDropdownSearchFilter()"><i class="fa fa-filter"></i></a> </span>
							 <span ng-hide="searchFilterInput" class="filter-span">
								<input type="text" class="filter-text" placeholder="Enter keyword" ng-model="searchDropdown.equipText"/>
								<button title="Close Filter" type="button" class="close filter-close" ng-click="hideDropdownSearchFilter()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							 </span>	
						</label>								
						<select class="form-control"
								name="equipment_type_id"
								ng-model="addMethod.equipment_type_id"
								id="equipment_type_id"
								ng-required='true'
								ng-options="item.id as item.name for item in ( equipmentTypesList | filter:searchDropdown.equipText) track by item.id">
							<option value="">Select Equipment Type</option>
						</select>
						<span ng-messages="addtestParameterForm.equipment_type_id.$error" ng-if='addtestParameterForm.equipment_type_id.$dirty || addtestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Equipment Type</span>
						</span>
					</div>
					<!--/Equipment Type-->
						
					<!--Parent Product Category-->
					<div class="col-xs-2" ng-init="fungetParentCategory()">																
						<label for="product_category_id" class="outer-lable">Product Section<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="product_category_id"
								id="product_category_id"
								ng-model="addMethod.product_category_id"
								ng-options="item.id as item.name for item in (parentCategoryList | filter:searchProduct.text) track by item.id"
								ng-required='true'>
							<option value="">Select Product Section</option>
						</select>
						<span ng-messages="erpAddMethodForm.product_category_id.$error" ng-if='erpAddMethodForm.product_category_id.$dirty || erpAddMethodForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parent Category is required</span>
						</span>
					</div>
					<!--/Parent Product Category-->
					<!-- method_status---->
					<div class="col-xs-2">
						<label for="status">Status<em class="asteriskRed">*</em></label>	
						<select class="form-control" 
							ng-required='true'  
							name="status" 
							id="status" 
							ng-options="status.name for status in methodStatusList track by status.id"
							ng-model="addMethod.method_status.selectedOption">
							<option value="">Select Status</option>
						</select>				   
					
						<span ng-messages="erpAddMethodForm.status.$error" ng-if='erpAddMethodForm.status.$dirty  || erpAddMethodForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>
					<!-- /method_status---->	
					<!--save button-->
					<div class="col-xs-2 pull-right">
						<label for="submit">{{ csrf_field() }}</label>		
						<span ng-if="{{defined('ADD') && ADD}}">
							<button title="Save" ng-disabled="erpAddMethodForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funAddMethod()'>Save</button>
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