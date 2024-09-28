<div class="row header">
	<strong class="pull-left headerText" ng-click="funGetParameterCategoryList()" title="Refresh">Test Parameter Categories Tree View<span ng-if="prodata.length">([[prodata.length]])</span></strong>	
	<div class="navbar-form navbar-right" role="search">
		<select style="margin-top: -7px;margin-right: 1px;" class="seachBox form-control" 
				name="seach_category_id"
				ng-model="swithPage"
				ng-change="funSwithPage('{{Request::path()}}')">
			<option ng-selected="{{Request::path() == 'master/test-parameter-categories'}}" id="master/product-categories">Parameter Categories List View</option>
			<option ng-selected="{{Request::path() == 'master/test-parameter-categories/tree-view'}}" id="master/product-categories/tree-view">Parameter Categories Tree View</option>
		</select>
		<select style="margin-top: -7px;margin-right: 1px;" class="form-control"
				name="categoryTree_product_category_id"
				id="categoryTree_product_category_id"
				ng-change="funFilterCategoryTree(filterCategoryId.id)"
				ng-model="filterCategoryId"
				ng-options="item.name for item in parentCategoryList track by item.id">
			<option value="">Select Parent Category</option>
		</select>
	</div>
</div>
<div class="row categoryTreeFilter">
	<div 
		  data-angular-treeview="true"
		  data-tree-model="parameterCategoriesTree"
		  data-node-id="test_para_cat_id"
		  data-node-label="test_para_cat_name"
		  data-node-level="level"
		  data-node-children="children" >
	</div>
</div>