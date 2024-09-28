<div class="row" ng-show="{{defined('EDIT') && EDIT}}">
	<div class="panel panel-default" ng-hide="editMethodFormDiv" >
		<div class="panel-body">
			<div class="row header1">
				<strong class="pull-left headerText">Edit Method</strong>				
			</div>
			<form name='erpEditMethodForm' id="erpEditMethodForm" novalidate>
				
				<div class="row">
					<!--Method Code-->
					<div class="col-xs-2">
						<label for="method_code">Method Code<em class="asteriskRed">*</em></label>						   
						<input readonly
							type="text"
							class="form-control"
							ng-model="editMethod.method_code"
							id="method_code"
							ng-value="method_code"
							placeholder="Method Code" />
					</div>
					<!--/Method Code-->
					
					<!--Method Name-->
					<div class="col-xs-2">
						<label for="method_name">Method Name<em class="asteriskRed">*</em></label>						   
						<input type="text" class="form-control" 
								ng-model="editMethod.method_name"
								ng-change="editMethod.method_desc=editMethod.method_name"
								name="method_name" 
								id="method_name"
								ng-required='true'
								placeholder="Method Name" />
						<span ng-messages="erpEditMethodForm.method_name.$error" ng-if='erpEditMethodForm.method_name.$dirty || erpEditMethodForm.$submitted' role="alert">
							<span ng-message="required" class="error">Method name is required</span>
						</span>
					</div>
					<!--/Method Name-->
					
					<!--Method Description-->
					<div class="col-xs-2">
						<label for="method_desc">Method Description<em class="asteriskRed">*</em></label>
						<textarea rows="1"
								class="form-control" 
								ng-model="editMethod.method_desc"								
								name="method_desc" 
								id="method_desc"
								ng-required='true'
								placeholder="Method Description">
						</textarea>
						<span ng-messages="erpEditMethodForm.method_desc.$error" ng-if='erpEditMethodForm.method_desc.$dirty  || erpEditMethodForm.$submitted' role="alert">
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
								ng-model="editMethod.equipment_type_id.selectedOption"
								id="equipment_type_id"
								ng-required='true'
								ng-options="item.name for item in equipmentTypesList track by item.id">
							<option value="">Select Equipment Type</option>
						</select>
						<span ng-messages="erpEditMethodForm.equipment_type_id.$error"ng-if='erpEditMethodForm.equipment_type_id.$dirty || addtestParameterForm.$submitted' role="alert">
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
								ng-model="editMethod.product_category_id.selectedOption"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required='true'>
							<option value="">Select Product Section</option>
						</select>
						<span ng-messages="erpEditMethodForm.product_category_id.$error" ng-if='erpEditMethodForm.product_category_id.$dirty || testStandardForm.$submitted' role="alert">
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
							ng-model="editMethod.status.selectedOption">
							<option value="">Select Status</option>
						</select>				   
					
						<span ng-messages="erpEditMethodForm.status.$error" ng-if='erpEditMethodForm.status.$dirty  || erpEditMethodForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>
					<!-- /method_status---->
					<!--Update button-->
					<div class="col-xs-2">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" name="method_id" ng-value="editMethod.method_id" ng-model="editMethod.method_id">
						<span ng-if="{{defined('EDIT') && EDIT}}" >
							<button type="submit" title="Update" ng-disabled="erpEditMethodForm.$invalid" class='mT26 btn btn-primary  btn-sm' ng-click='funUpdateMethod()'>Update</button>							
						</span>
						<button title="Close" type='button' class='mT26 btn btn-default btn-sm' ng-click='showAddForm()'>Close</button>
					</div>
					<!--/Update button-->
				</div>
			</form>	
		</div>
	</div>
</div>