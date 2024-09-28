<div class="panel panel-default" ng-model="addProductFormDiv" ng-hide="addProductFormDiv">
	<div class="panel-body">
		<form name='productCategoryAddForm' novalidate>
			<div class="row header1">
				<strong class="pull-left headerText">Add Test Product Category</strong>								
			</div>
			<div class="row">
			<label for="submit">{{ csrf_field() }}</label>	
				<div class="col-xs-3">
					<label for="p_category_code">Product Category Code<em class="asteriskRed">*</em></label>
					<span class="generate text-right"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>					   
					<input type="text" class="form-control" 
						readonly
						ng-model="p_category_code"
						ng-bind="p_category_code"
						name="p_category_code" 
						id="p_category_code"
						ng-required='true'
						placeholder="Category Code" />										
				</div>
				<div class="col-xs-4">
					<label for="p_category_name">Product Category Name<em class="asteriskRed">*</em></label>						   
						<input type="text" class="form-control" 
								ng-model="p_category_name"
								name="p_category_name" 
								id="p_category_name"
								ng-required='true'
								placeholder="Category Name" />
						<span ng-messages="productCategoryAddForm.p_category_name.$error" 
						ng-if='productCategoryAddForm.p_category_name.$dirty  || productCategoryAddForm.$submitted' role="alert">
							<span ng-message="required" class="error">Product category name is required</span>
						</span>
				</div>								

				<!--Test Parameter Category-->
				<div class="col-xs-3">
					<label for="parent_id">Product Parent Category</label>						
					<select class="form-control"
							id="parent_id"
							name="parent_id"
							ng-model="addTestProductCategory.selectedOption"
							ng-options="item.name for item in testProductCategoryOptions track by item.id ">
						<option value="">Select Parent Category</option>
					</select>						
				</div>
				<!--/Test Parameter Category-->		
				<div class="col-xs-2">					
				   <a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" title="Save" ng-disabled="productCategoryAddForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='addProductCategory()'> Save </a>
					<button title="Close"  type="button" class="mT26 btn btn-default" ng-click="hideTreeForms()">Close</button>
				</div>
			</div>
			</form>	
		</div>
	</div>
