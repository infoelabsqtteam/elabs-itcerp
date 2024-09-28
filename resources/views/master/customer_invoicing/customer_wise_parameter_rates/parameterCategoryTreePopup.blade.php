<div id="parameterCategoryTreeView" class="modal fade" role="dialog">
	  <div class="modal-dialog mT25">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Test Parameter Categories : [[selectedParameterCategoryName]]</h4>
		  </div>
		  <div class="modalBody modal-body categoryTreeFilter" ng-init="getParameterCategories()">	
				<div class="mT15 mB10 custom-rt-scroll">		  
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