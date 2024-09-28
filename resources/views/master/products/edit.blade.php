<div ng-show="{{defined('EDIT') && EDIT}}">
	<div class="panel panel-default"  ng-hide="editProductFormDiv" >
		<div class="panel-body" ng-model="editProductFormDiv" id="editProductDiv">
			<form name='editProductForm' id="edit_product_form" novalidate>
			  <label for="submit">{{ csrf_field() }}</label>	
				<div class="row header1">
					<strong class="pull-left headerText">Edit Product</strong>								
				</div>
				<div class="row">
					<div class="col-xs-3">
						<label for="product_code">Product Code<em class="asteriskRed">*</em></label>						   
						<input readonly 
							type="text" class="form-control"  
							ng-model="editProduct.product_code"
							id="product_code" 
							ng-value="product_code"
							placeholder="Product Code" />
					</div>
					<div class="col-xs-3">
						<label for="product_barcode1">Product Barcode<em class="asteriskRed"></em></label>						   
							<input type="text" class="form-control" 
									ng-model="editProduct.product_barcode" 									
									name="product_barcode" 
									id="product_barcode"
									placeholder="Product Barcode" />
							<span ng-messages="editProductForm.product_barcode.$error" 
							ng-if='editProductForm.product_barcode1.$dirty  || editProductForm.$submitted' role="alert">
								<span ng-message="required" class="error">Product barcode is required</span>
							</span>
					</div>
					<div class="col-xs-3">
						<label for="product_name">Product Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="editProduct.product_name"
									name="product_name" ng-value="product_name"
									id="product_name"
									ng-required='true'
									ng-change="editProduct.product_description=editProduct.product_name"
									placeholder="Product Name" />
							<span ng-messages="editProductForm.product_name.$error" 
							ng-if='editProductForm.product_name.$dirty  || editProductForm.$submitted' role="alert">
								<span ng-message="required" class="error">Product name is required</span>
							</span>
					</div>
					<div class="col-xs-3">
						<label for="product_description">Product Description<em class="asteriskRed">*</em></label>
							<textarea rows=1 type="text" class="form-control" 
									ng-model="editProduct.product_description"  
									ng-value="product_description" 
									name="product_description" 
									id="product_description"
									ng-required='true'
									placeholder="Product Description" /></textarea>
							<span ng-messages="editProductForm.product_description.$error" 
							 ng-if='editProductForm.product_description.$dirty  || editProductForm.$submitted' role="alert">
								<span ng-message="required" class="error">Product description is required</span>
							</span>
					</div>	
				</div>	
				<div class="row mT20">				
					<div class="col-xs-3">	
						<label for="product_description"> Select Product Category<em class="asteriskRed">*</em></label>
						<a ng-init="getProductCategories()" data-toggle="modal"  title="Select Product Category" ng-click="showProductCatTreeViewPopUp(2)" class='generate cursor-pointer'> Tree View </a>
						<select class="form-control"
								required="true"
								name="p_category_id"
								ng-model="addTestProductCategory.selectedOption"
								ng-options="item.name for item in testProductCategoryOptions track by item.id">
							<option value="">Select Product Category</option>
						</select>
						<span ng-messages="editProductForm.product_category_name.$error" 
						ng-if='editProductForm.product_category_name.$dirty  || editProductForm.$submitted' role="alert">
							<span ng-message="required" class="error">Product category is required</span>
						</span>
					</div>		
					<div class="col-xs-3">
							<input type="hidden" name="product_id" ng-value="product_id" ng-model="product_id">
							<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update"   ng-disabled="editProductForm.$invalid" class='mT26 btn btn-primary' ng-click='updateProduct(seachCategoryId)' > Update </a>
							<button title="Close" ng-disabled="editProductForm.$invalid" type='button' class='mT26 btn btn-default' ng-click='showAddForm()' > Close </button>
					</div>
				</div>
			</form>	
		</div>
	</div>
</div>