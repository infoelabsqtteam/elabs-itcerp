<div class="row" ng-hide="editTestParaCategoryDiv">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Test Parameter Categories</strong></span>
			</div>
			<form name='erpEditTestParaCategoryForm' id="erpEditTestParaCategoryForm" novalidate>
				<div class="row">
					<!--Code-->
					<div class="col-xs-3">
						<label for="test_para_cat_code">Code<em class="asteriskRed">*</em></label>						   
						<input readonly
							   type="text"							   
							   class="form-control" 							
							   id="test_para_cat_code"
							   ng-model="editTestParaCategory.test_para_cat_code"
							   placeholder="Category Code" />
						<span ng-messages="erpEditTestParaCategoryForm.test_para_cat_code.$error" ng-if='erpEditTestParaCategoryForm.test_para_cat_code.$dirty  || erpEditTestParaCategoryForm.$submitted' role="alert">
							<span ng-message="required" class="error">Code is required</span>
						</span>
					</div>
					<!--Code-->
					
					<!--Test Parameter Category Name-->
					<div class="col-xs-3">
						<label for="test_para_cat_name">Test Parameter Category Name<em class="asteriskRed">*</em></label>
						<input type="text"
							   class="form-control" 
							   ng-model="editTestParaCategory.test_para_cat_name"
							   name="test_para_cat_name" 
							   id="test_para_cat_name"
							   ng-required='true'
							   placeholder="Category Name" />
						<span ng-messages="erpEditTestParaCategoryForm.test_para_cat_name.$error" ng-if='erpEditTestParaCategoryForm.test_para_cat_name.$dirty  || erpEditTestParaCategoryForm.$submitted' role="alert">
							<span ng-message="required" class="error">Category name is required</span>
						</span>
					</div>
					<!--/Test Parameter Category Name-->
					
					<!--Test Parameter Category Print Description-->
					<div class="col-xs-3">
						<label for="test_para_cat_print_desc">Test Parameter Category Print Description<em class="asteriskRed">*</em></label>
						<textarea  type="text" class="form-control" 
								ng-model="editTestParaCategory.test_para_cat_print_desc"
								name="test_para_cat_print_desc" 
								id="test_para_cat_print_desc"
								ng-required='true'
								rows="1"
								placeholder="Category Print Description">							
						</textarea>
						<span ng-messages="erpEditTestParaCategoryForm.test_para_cat_print_desc.$error" ng-if='erpEditTestParaCategoryForm.test_para_cat_print_desc.$dirty || erpEditTestParaCategoryForm.$submitted' role="alert">
							<span ng-message="required" class="error">Category print description is required</span>
						</span>
					</div>
					<!--/Test Parameter Category Print Description-->
					
					<!--Parameter Category-->
					<div class="col-xs-3">
						<label for="parent_id">Parameter Category</label>
						<select class="form-control"
								id="parent_id"
								name="parent_id"
								ng-model="parent_id.selectedOption"
								ng-options="testParameterCategory.name for testParameterCategory in testParameterCategoryOptions track by testParameterCategory.id ">
							<option value="">Select Parameter Category</option>
						</select>						
					</div>
					<!--/Parameter Category-->
					
				</div>
				
				<div class="row">
					<!--<div class="col-xs-3" ng-init="fungetParentCategory()">																
						<label for="product_category_id">Parent Category<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="product_category_id"
								id="product_category_id"
								ng-model="editTestParaCategory.product_category_id.selectedOption"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required='true'>
							<option value="">Select Parent Category</option>
						</select>
						<span ng-messages="erpEditTestParaCategoryForm.product_category_id.$error" ng-if='erpEditTestParaCategoryForm.product_category_id.$dirty || erpEditTestParaCategoryForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parent Category is required</span>
						</span>
					</div>-->
					
					<!--Update button-->				
					<div class="mT26 col-xs-12 pull-left">
						<input type='hidden' id='test_para_cat_id' name="test_para_cat_id" ng-model="editTestParaCategory.test_para_cat_id" ng-value="editTestParaCategory.test_para_cat_id"> 
						<button title="Update" ng-disabled="erpEditTestParaCategoryForm.$invalid" type='submit' id='edit_button' class=' btn btn-primary' ng-click='funUpdateTestParameter()'> Update </button>
						<button title="Close" type="button" class="btn btn-default" ng-click="closeButton()">Close</button>
					</div>
					<!--Update button-->
					
				</div>
					
			</form>		
		</div>
	</div>
</div>