<div ng-show="{{defined('ADD') && ADD}}">
	<div class="panel panel-default" ng-hide="addProductFormDiv">
		<div class="panel-body">
			<form name='productForm' id="add_product_form" novalidate>
				<label for="submit">{{ csrf_field() }}</label>						
				<div class="row header1">
					<strong class="pull-left headerText">Add Product</strong>	
					<button title="Upload" style="margin-top: 3px;margin-right: 3px;" ng-click="showUploadProductForm()" class="btn btn-primary pull-right">Upload</button>							
				</div>					
				<div class="row">				
					<div class="col-xs-3">
						<span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
						<label for="product_code">Product Code<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" readonly
									ng-model="product_code"
									ng-bind="product_code"
									name="product_code" 
									id="product_code"
									ng-required='true'
									placeholder="Product Code" />
							<span ng-messages="productForm.product_code.$error" 
							ng-if='productForm.product_code.$dirty  || productForm.$submitted' role="alert">
								<span ng-message="required" class="error">Product code is required</span>
							</span>
					</div>
					<div class="col-xs-3">
						<label for="product_barcode">Product Barcode<em class="asteriskRed"></em></label>						   
							<input type="text" class="form-control" 
									ng-model="product.product_barcode"
									ng-value="product_barcode"
									name="product_barcode" 
									id="product_barcode"
									placeholder="Product Barcode" />
							<span ng-messages="productForm.product_barcode.$error" 
							ng-if='productForm.product_barcode.$dirty  || productForm.$submitted' role="alert">
								<span ng-message="required" class="error">Product barcode is required</span>
							</span>
					</div>						
					<div class="col-xs-3">
						<label for="product_name">Product Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="product.product_name"
									name="product_name"
									ng-change="product.product_description=product.product_name"									
									id="product_name"
									ng-required='true'
									placeholder="Product Name" />
							<span ng-messages="productForm.product_name.$error" 
							ng-if='productForm.product_name.$dirty  || productForm.$submitted' role="alert">
								<span ng-message="required" class="error">Product name is required</span>
							</span>
					</div>
					<div class="col-xs-3">
						<label for="product_description">Product Description<em class="asteriskRed">*</em></label>
							<textarea rows=1 type="text" class="form-control" 
									ng-model="product.product_description"
									name="product_description" 
									id="product_description"
									ng-required='true'
									placeholder="Product Description" /></textarea>
							<span ng-messages="productForm.product_description.$error" 
							 ng-if='productForm.product_description.$dirty  || productForm.$submitted' role="alert">
								<span ng-message="required" class="error">Product description is required</span>
							</span>
					</div>	
				</div>	
				<div class="row mT20">				
					<div class="col-xs-3">	
						<label for="product_description"> Select Product Category<em class="asteriskRed">*</em></label>
						<a title="Select Category" data-toggle="modal"  title="Select Product Category" ng-click="showProductCatTreeViewPopUp(2)" class='generate cursor-pointer'> Tree View </a>
						<select class="form-control"
								required="true"
								name="p_category_id"
								ng-model="addTestProductCategory.selectedOption"
								ng-options="item.name for item in testProductCategoryOptions track by item.id">
							<option value="">Select Product Category</option>
						</select>
					    <span ng-messages="productForm.p_category_id.$error" 
						ng-if='productForm.p_category_id.$dirty  || productForm.$submitted' role="alert">
							<span ng-message="required" class="error">Product category is required</span>
						</span>
					</div>								
					<div class="col-xs-3">
						<button ng-show="{{defined('ADD') && ADD}}" ng-disabled="productForm.$invalid" href="javascript:;" title="Save"  class='mT26 btn btn-primary' ng-click='addProduct(seachCategoryId)' > Save </button>
						<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetAddForm()" data-dismiss="modal">Reset</button>
					</div>
				</div>
			</form>	
		</div>
	</div>
</div>

