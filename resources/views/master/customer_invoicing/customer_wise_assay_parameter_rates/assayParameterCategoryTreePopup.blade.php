<div id="assayParameterCategoryPopup" ng-if="parameterCategoriesTree.length" class="modal fade" role="dialog">
	  <div class="modal-dialog mT25">
		    <div class="modal-content">
			      <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal">&times;</button>
				    <h4 class="modal-title">Test Parameter Categories</h4>
			      </div>
			      <div class="modalBody modal-body">	
					<div class="mT15 mB10 custom-scroll">		  
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