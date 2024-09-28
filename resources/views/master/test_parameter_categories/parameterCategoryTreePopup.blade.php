<div id="parameterCategoryTreeView" class="modal fade" role="dialog">
	  <div class="modal-dialog mT25">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Test Parameter Categories : [[selectedParameterCategoryName]]</h4>
		  </div>
		  
		  <div class="modalBody modal-body categoryTreeFilter custom-scroll">	
		  		 <div class="row">						
					<!--Parent Product Category-->
					<div class="col-xs-6 text-right">																
						<label for="product_category_id">Select Parent Category</label>
					</div>
					<div class="col-xs-6 text-left" ng-init="fungetParentCategory()">	
						<select class="form-control"
								name="categoryTree_product_category_id"
								id="categoryTree_product_category_id"
								ng-change="funFilterCategoryTree(categoryTree_product_category_id.id)"
								ng-model="categoryTree_product_category_id"
								ng-options="item.name for item in parentCategoryList track by item.id">
							<option value="">Select Parent Category</option>
						</select>							
					</div>
					<!--/Parent Product Category-->
				</div>
				<div class="modalBodymT15 mB10">		  
					<div 
					  data-angular-treeview="true"
					  data-tree-model="parameterCategoriesTree"
					  data-node-id="test_para_cat_id"
					  data-node-label="test_para_cat_name"
					  data-node-level="level"
					  data-node-children="children" >
					</div>
				</div>
		  </div>
		</div>
	</div>
</div>