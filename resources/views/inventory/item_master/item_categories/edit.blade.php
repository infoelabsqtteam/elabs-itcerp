<div ng-show="{{defined('EDIT') && EDIT}}">
	<div class="panel panel-default " ng-model="editItemFormDiv" ng-hide="editItemFormDiv">
		<div class="panel-body">
			<form name='itemCategoryEditForm' novalidate>
				<div class="row header1">
						<strong class="pull-left headerText">Edit  Item Category </strong>
				</div>
				<div class="row">
					<div class="col-xs-3">
						<label for="item_cat_code1">Item Category Code<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control"  readonly
									ng-model="item_cat_code1" ng-value="item_cat_code1"
									id="item_cat_code1"
									name="item_cat_code1"
									ng-required='true'
									placeholder="Category Code" />
							<span ng-messages="itemCategoryEditForm.item_cat_code1.$error" 
							ng-if='itemCategoryEditForm.item_cat_code1.$dirty  || itemCategoryEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Item category code is required</span>
							</span>
					</div>
					<div class="col-xs-2">
						<label for="item_cat_name1">Item Category Name<em class="asteriskRed">*</em></label>						   
							<input type="text"  class="form-control" 
									ng-model="item_cat_name1" ng-value="item_cat_name1"
									name="item_cat_name1" id="item_cat_name1"
									ng-required='true'
									placeholder="Category Name" />
							<span ng-messages="itemCategoryEditForm.item_cat_name1.$error" 
							ng-if='itemCategoryEditForm.item_cat_name1.$dirty  || itemCategoryEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Item category name is required</span>
							</span>
					</div>								
					<input  type="hidden"
							ng-model="item_name_old"
							ng-value="item_name_old"
							name="item_name_old" 
							id="item_name_old"
							placeholder="Item Name Old" />
					<div class="col-xs-3">
						<label for="item_parent_cat1">Item Parent Category:</label>
							<select class="form-control" name="item_parent_cat1"
									id="item_parent_cat1" ng-model="selectedParentCategory.selectedOption"
									ng-options="item.name for item in itemCategoryList track by item.id ">
								<option value="">Select Item Category</option>
							</select>
					</div>
					<div class="col-xs-2">
							<input type='hidden' name="item_cat_id1" ng-model="item_cat_id1"  ng-value="item_cat_id1" >
							<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update" ng-disabled="itemCategoryEditForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='updateItemCategory()' > Update </a>
							<button title="Close"  type='button'  class='mT26 btn btn-default' ng-click='showAddForm()'> Close </button>
					</div>
				</div>
			</form>	
		</div>
	</div>
</div>