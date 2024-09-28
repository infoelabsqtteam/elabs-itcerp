<div class="row header">
	<strong class="pull-left headerText" ng-click="getProductCategories()" title="Refresh">Test Product Categories Tree View<span ng-if="prodata.length">([[prodata.length]])</span></strong>	
	<div class="navbar-form navbar-right" role="search">
		<select style="margin-top: -7px;margin-right: 1px;" class="seachBox form-control" 
				name="seach_category_id"
				ng-model="swithPage"
				ng-change="funSwithPage('{{Request::path()}}')">
			<option ng-selected="{{Request::path() == 'master/product-categories'}}" id="master/product-categories">Product Categories List View</option>
			<option ng-selected="{{Request::path() == 'master/product-categories/tree-view'}}" id="master/product-categories/tree-view">Product Categories Tree View</option>
		</select>
	</div>
</div>
<div class="row categoryTreeFilter">
	<div  data-angular-treeview="true"
		  data-tree-model="productCategoriesTree"
		  data-node-id="p_category_id"
		  data-node-label="p_category_name"
		  data-node-level="level"
		  data-node-children="children" >
	</div>
</div>