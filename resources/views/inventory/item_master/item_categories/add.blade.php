<div ng-show="{{defined('ADD') && ADD}}">
	<div class="panel panel-default " ng-model="addItemFormDiv" ng-hide="addItemFormDiv" >
		<div class="panel-body">
			<form name='itemCategoryAddForm' novalidate>
				<div class="row header1">
						<strong class="pull-left headerText">Add Item Category</strong>								
				</div>
				<div class="row">
					<div class="col-xs-3">
						<label for="item_cat_code">Item Category Code<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" readonly
									ng-model="item_cat_code"
									ng-bind="item_cat_code"
									name="item_cat_code" 
									id="item_cat_code"
									ng-required='true'
									placeholder="Category Code" />
							<span ng-messages="itemCategoryAddForm.item_cat_code.$error" 
							ng-if='itemCategoryAddForm.item_cat_code.$dirty  || itemCategoryAddForm.$submitted' role="alert">
								<span ng-message="required" class="error">Item category code is required</span>
							</span>
					</div>
					<div class="col-xs-3">
						<label for="item_cat_name">Item Category Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="item_cat_name"
									name="item_cat_name" 
									id="item_cat_name"
									ng-required='true'
									placeholder="Category Name" />
							<span ng-messages="itemCategoryAddForm.item_cat_name.$error" 
							ng-if='itemCategoryAddForm.item_cat_name.$dirty  || itemCategoryAddForm.$submitted' role="alert">
								<span ng-message="required" class="error">Item category name is required</span>
							</span>
					</div>
					<div class="col-xs-3" ng-show="itemCategoryList.length">
						<label for="item_parent_cat">Item Parent Category:</label>
							<select class="form-control" name="item_parent_cat"
									id="item_parent_cat" ng-model="item_parent_cat"
									ng-options="item.name for item in itemCategoryList track by item.id">
								<option value="">Select Item Category</option>
							</select>
					</div>
					<div class="col-xs-3">
					   <a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" title="Save"   ng-disabled="itemCategoryAddForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='addItemCategory()' > Save </a>
						<button  type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetForm()' title="Reset"> Reset </button>

					</div>
				</div>
			</form>	
		</div>
	</div>
</div>