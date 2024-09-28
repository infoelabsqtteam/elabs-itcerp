<style>
	.c_product_name{
		width: calc(100% - 30px);
		float: left;	
	}
	.c_product_name span{
		float:left;
	}
	.deleteIcon{
		float:left;   margin-top: 10px;
	}
</style>
<div class="row" ng-hide="addCustomerProductFormBladeDiv">
    <div class="panel panel-default">		           
		<div class="panel-body">		
			<div class="row header-form">
				<span class="pull-left headerText"><strong>Add New Customer Product</strong></span>
				<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></span>
			</div>			
			<form method="POST" role="form" id="erpAddCustomerProductForm" name="erpAddCustomerProductForm" novalidate>
				<div class="row">
					<!--Selecet Product view-record -->
					<div class="col-xs-12">
	                    <div class="col-xs-6 form-group">
	                        <label for="product_id">Select Product<em class="asteriskRed">*</em>
							 <a title="Refresh Product List" ng-click="funGetProductList(0)" class='generate cursor-pointer'> Refresh </a></label>
							<a  title="Select Category" data-toggle="modal"  title="Filter Product" ng-click="showProductCatTreeViewPopUp(3)" class='generate cursor-pointer'> Tree View </a>
	                        <select class="form-control"
	                            name="product_id"
	                            id="product_id"
								ng-required="true"
	                            ng-model="customerProduct.product_id.selectedOption"
	                            ng-options="item.name for item in productsListData track by item.id">
	                            <option value="">Select Product</option>
	                        </select>
	                        <span ng-messages="erpAddCustomerProductForm.product_id.$error" ng-if="erpAddCustomerProductForm.product_id.$dirty || erpAddCustomerProductForm.$submitted" role="alert">
	                            <span ng-message="required" class="error">Product is required</span>
	                        </span>
	                    </div>                    
                    	<!--/Selecet Product -->

					<!--Customer Product Name-->
						<div class="col-xs-6 form-group">
							<label for="c_product_name">Product Alias Name<em class="asteriskRed">*</em></label>
								<span>
									<a title="Add New Row" id="#addNewRow" href="javascript:;" ng-click="addRow(1)"><i class="font15 mL5 glyphicon glyphicon-plus"></i></a>
									<input style="display:none;" type="number" class="width70 " title="Add multiple rows" id="mutipleRows" max="5" value="">
								</span>
								<input  class="form-control c_product_name"
									id="c_product_name"
									ng-model="addCustomerProduct.c_product_name"
									name="c_product_name[]"
									ng-required="true"
									placeholder="Customer Product Name">
							<span ng-messages="erpAddCustomerProductForm.c_product_name.$error" ng-if="erpAddCustomerProductForm.c_product_name.$dirty || erpAddCustomerProductForm.$submitted" role="alert">
								<span ng-message="required" class="error">Product Alias name is required</span>
							</span><br>
							<!--/Customer Product Name-->
							
						</div>
					</div>
				</div>			

						<!------ clone row ---------->
					<div class="row" select-last ng-repeat="product_alias in customer_product_alias">
						<div class="col-xs-12"  ng-if="ifAddMoreClickedOnAddEdit" >
							<div class="col-xs-6 form-group"></div>
							<div class="col-xs-6 form-group" >
								<input  class="form-control c_product_name"
									id="c_product_name_[[$index]]"
									name="c_product_name[]"
									ng-required="true"
									ng-model="addCustomerProduct.c_product_name_[[$index]]"
									placeholder="Customer Product Name">
								<span  ng-if = "$index >= 0">
									<a class="deleteIcon" href="javascript:;" title="Delete Row" ng-click="deleteRow($index,deleteNewRow);">
										<i class="font15 removeIcon glyphicon glyphicon-remove"></i>
									</a>
								</span>&nbsp;
							</div>
						</div>
					</div>	
					[[$index]]
					<!--Save Button-->
                    <div class="col-xs-12 form-group text-right mT10">
                        <label for="submit">{{ csrf_field() }}</label>					
                        <button type="submit" ng-disabled="erpAddCustomerProductForm.$invalid" class="btn btn-primary" ng-click="funAddCustomerProducts()">Save</button>
                        <button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                    <!--Save Button-->
		    </form>
		</div>	
    </div>	
</div>