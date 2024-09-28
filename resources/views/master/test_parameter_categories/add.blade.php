<div class="row" ng-hide="addTestParaCategoryDiv">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Parameter Categories</strong></span>
				<span><button class="pull-right btn btn-primary mT3 mR3" ng-click="showUploadForm()">Upload</button></span>
				<div class="navbar-form navbar-right" role="search">
					<select style="margin-top: -7px;margin-right: 1px;" class="seachBox form-control" 
							name="seach_category_id"
							ng-model="swithPage"
							ng-change="funSwithPage('{{Request::path()}}')">
						<option ng-selected="{{Request::path() == 'master/test-parameter-categories'}}" id="master/product-categories">Parameter Categories List View</option>
						<option ng-selected="{{Request::path() == 'master/test-parameter-categories/tree-view'}}" id="master/product-categories/tree-view">Parameter Categories Tree View</option>
					</select>
				</div>
			</div>
			<form name='erpAddTestParaCategoryForm' id="erpAddTestParaCategoryForm" novalidate>
				<div class="row">
					<div class="col-xs-3">
					   	<span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>    
						<label for="test_para_cat_code">Parameter Category Code<em class="asteriskRed">*</em></label>						   
						<input type="text"
							class="form-control"
							readonly
							ng-model="addTestParaCategory.test_para_cat_code"
							ng-value="default_test_para_cat_code"
							name="test_para_cat_code" 
							id="test_para_cat_code"
							placeholder="Parameter Category Code" />
					</div>
						
					<div class="col-xs-3">
						<label for="test_para_cat_name">Parameter Category Name<em class="asteriskRed">*</em></label>
							<input type="text" class="form-control" 
									ng-model="addTestParaCategory.test_para_cat_name"
									ng-change="addTestParaCategory.test_para_cat_print_desc=addTestParaCategory.test_para_cat_name"
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
						<label for="test_para_cat_print_desc">Parameter Category Description<em class="asteriskRed">*</em></label>
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
						<a title="Select Parameter Category" data-toggle="modal" ng-click="showParameterCatTreeViewPopUp(8)" class='generate cursor-pointer'>Tree View</a>
						<select class="form-control"
								id="parent_id"
								name="parent_id"
								ng-model="addTestParaCategory.parent_id.selectedOption"
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
					<!-- status---->
					<div class="col-xs-3">
						<label for="status">Status<em class="asteriskRed">*</em></label>	
						<select class="form-control" 
							ng-required='true'  
							name="status" 
							id="status" 
							ng-options="status.name for status in statusList track by status.id"
							ng-model="addTestParaCategory.status.selectedOption">
							<option value="">Select Status</option>
						</select>				   
					
						<span ng-messages="erpAddTestParaCategoryForm.status.$error" ng-if='erpAddTestParaCategoryForm.status.$dirty  || erpAddTestParaCategoryForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>
					<!-- /status---->	
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