<div ng-if="productCategoriesTree" id="orderTestingProductCategory" class="modal fade" role="dialog">
	  <div class="modal-dialog mT25">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Test Product Categories : [[selectedProductCategoryName]]</h4>
		        <div class="text-right"><a href="javascript:;" ng-click="getProductCategories();">Refresh</a></div>
		  </div>
		  <div class="modalBody modal-body custom-scroll">	
				<div class="mT15 mB10">		  
					<div 
					  data-angular-treeview="true"
					  data-tree-model="productCategoriesTree"
					  data-node-id="p_category_id"
					  data-node-label="p_category_name"
					  data-node-level="level"
					  data-node-children="children" >
					</div>
				</div>
		  </div>
		</div>
	</div>
</div>