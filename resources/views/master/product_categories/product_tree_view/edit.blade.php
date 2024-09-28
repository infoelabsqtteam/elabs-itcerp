	<div class="panel panel-default" ng-hide="editProductFormDiv" >
		<div class="panel-body" id="editProductFormDiv" ng-model="editProductFormDiv">
			<form name='productCategoryEditForm' novalidate>
			<label for="submit">{{ csrf_field() }}</label>	
				<div class="row header1">
					 <strong class="pull-left headerText">Edit Test Product Category</strong>
				</div>
				<div class="row">
					<div class="col-xs-3">
						<label for="p_category_code1">Product Category Code<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control"  readonly
									ng-model="editProCat.p_category_code" 
									ng-value="p_category_code"
									id="p_category_code"
									ng-required='true'
									placeholder="Category Code" />
							<span ng-messages="productCategoryEditForm.p_category_code.$error" 
							ng-if='productCategoryEditForm.p_category_code.$dirty  || productCategoryEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Product category code is required</span>
							</span>
					</div>				
					<div class="col-xs-4">
						<label for="p_category_name1">Product Category Name<em class="asteriskRed">*</em></label>					   
							<input type="text" class="form-control" 
									ng-model="editProCat.p_category_name"
									name="p_category_name" 
									id="p_category_name"
									ng-required='true'
									placeholder="Category Name" />
							<span ng-messages="productCategoryEditForm.p_category_name.$error" 
							ng-if='productCategoryEditForm.p_category_name.$dirty  || productCategoryEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Product category name is required</span>
							</span>
					</div>
					
					<!--Test Parameter Category-->
					<div class="col-xs-3">
						<label for="parent_id">Product Parent Category</label>						
						<select class="form-control"
								id="parent_id"
								name="parent_id"
								ng-model="editTestProductCategory.selectedOption"
								ng-options="item.name for item in testProductCategoryOptions track by item.id ">
							<option value="">Select Parent Category</option>
						</select>						
					</div>
					<!--/Test Parameter Category-->	
					
					<div class="col-xs-2">
						<input type='hidden' name="p_category_id" ng-model="p_category_id"  ng-value="p_category_id" >
						<button title="Update" ng-disabled="productCategoryEditForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='updateProductCategory()' > Update </button>
						<button title="Close" type='button'  class='mT26 btn btn-default' ng-click='hideTreeForms()' > Close </button>
					</div>
				</div>
			</form>	
		</div>
	</div>
