<div class="row" ng-hide="addTestParaCategoryDiv">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Parameter Categories</strong></span>
			</div>
			<form name='erpAddTestParaCategoryForm' id="erpAddTestParaCategoryForm" novalidate>
				<div class="row">
					<div class="col-xs-3">
					   	<span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>    
						<label for="test_para_cat_code">Code<em class="asteriskRed">*</em></label>						   
						<input type="text"
							class="form-control"
							readonly
							ng-model="addTestParaCategory.test_para_cat_code"
							ng-value="default_test_para_cat_code"
							name="test_para_cat_code" 
							id="test_para_cat_code"
							placeholder="Category Code" />
					</div>
						
					<div class="col-xs-3">
						<label for="test_para_cat_name">Parameter Category Name<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control" 
									ng-model="addTestParaCategory.test_para_cat_name"
									name="test_para_cat_name" 
									id="test_para_cat_name"
									ng-required='true'
									placeholder="Category Name" />
							<span ng-messages="erpAddTestParaCategoryForm.test_para_cat_name.$error" 
							 ng-if='erpAddTestParaCategoryForm.test_para_cat_name.$dirty  || erpAddTestParaCategoryForm.$submitted' role="alert">
								<span ng-message="required" class="error">Category name is required</span>
							</span>
					</div>
					<div class="col-xs-3">
						<label for="test_para_cat_print_desc">Parameter Category Print Description<em class="asteriskRed">*</em></label>
							<textarea  type="text" class="form-control" 
									ng-model="addTestParaCategory.test_para_cat_print_desc"
									name="test_para_cat_print_desc" 
									id="test_para_cat_print_desc"
									ng-required='true' rows=1
									placeholder="Category Print Description"></textarea>
							<span ng-messages="erpAddTestParaCategoryForm.test_para_cat_print_desc.$error" 
							 ng-if='erpAddTestParaCategoryForm.test_para_cat_print_desc.$dirty  || erpAddTestParaCategoryForm.$submitted' role="alert">
								<span ng-message="required" class="error">Category print description is required</span>
							</span>
					</div>
						
					<!--Test Parameter Category-->
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
					<!--/Test Parameter Category-->							
				</div>
					
				<div class="row mT20">					
					<!--Parent Category-->
					<div class="col-xs-3"  ng-if="DisplayProductSection">																
						<label for="product_category_id">Product Section<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="product_category_id"
								id="product_category_id"
								ng-model="addTestParaCategory.product_category_id"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required='true'>
							<option value="">Select Parent Category</option>
						</select>
						<span ng-messages="erpAddTestParaCategoryForm.product_category_id.$error" ng-if='erpAddTestParaCategoryForm.product_category_id.$dirty || erpAddTestParaCategoryForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parent Category is required</span>
						</span>
					</div>																
					<input ng-if="!DisplayProductSection" type="hidden" class="form-control"
								name="product_category_id"
								ng-model="ProductCategoryId"
								ng-value="ProductCategoryId">
					<!--/Parent Category-->
					
					<!--Save button-->
					<div class="mT26 col-xs-3 pull-left">
						<label for="submit">{{ csrf_field() }}</label>	
						<button title="Save" ng-disabled="erpAddTestParaCategoryForm.$invalid" type='submit' id='add_button' class=' btn btn-primary' ng-click='addTestParaCat()'>Save</button>
						<button title="Reset"  type="button" class="btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>
					</div>
					<!--/Save button-->
				</div>
			</form>		
		</div>
	</div>
</div>