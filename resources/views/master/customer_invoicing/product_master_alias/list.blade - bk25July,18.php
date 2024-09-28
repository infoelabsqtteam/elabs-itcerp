<div class="row" ng-hide="listCustomerProductFormBladeDiv">
    
    <!--search-->
    <div class="row header">        
        <div role="new" class="navbar-form navbar-left">            
            <div><strong ng-click="funGetCustomerProductsList()" id="form_title">Products Alias Listing<span>([[customerProductsAliasCount]])</span></strong></div>
        </div>
	    <form class="form-inline" method="POST" target ="blank" action="{{url('master/product-alias/download-excel')}}" role="form" id="erpProductAliasFilterForm" name="erpProductAliasFilterForm" novalidate>
		<label for="submit">{{ csrf_field() }}</label>
		<div role="new" class="navbar-form navbar-right">
		    <div class="nav-custom custom-display">
			<input type="text" name="search_keyword" placeholder="Search" ng-model="filterCustomerProduct" class="form-control ng-pristine ng-untouched ng-valid">
			<button type="button" ng-disabled="!customerProductsAliasCount"
			class="form-control btn btn-default dropdown dropdown-toggle " data-toggle="dropdown" title="Download">
			Download</button>
			<div class="dropdown-menu">
			    <input type="submit" name="generate_product_alias_documents"  class="dropdown-item" value="Excel">
			</div>
			<span ng-if="{{defined('ADD') && ADD}}">
			    <button type="button" ng-click="navigateCustomerProductForms()"  class="btn btn-primary mT2">Add New</button>
			</span>
			<input type="hidden" name="product_id" value="[[productId]]">
		    </div>
		</div>
	    </form>
    </div>
	<div class="row" id="no-more-tables">
		<div class="panel panel-default">		           
			<div class="panel-body">
				<div class="col-sm-12">
					<div class="col-sm-3 text-left custom-scroll">
						<table class="col-sm-12 table-striped table-condensed cf">
							<thead>
								<tr>
									<th>
										<label ng-click="funGetProductWiseStatesList()">Products Name ([[getProductList.length]])</label>
									</th>
								</tr>
								<tr>
									<td>
                                        <input type="search" ng-model="searchProductlist" class="multiSearch form-control ng-pristine ng-valid ng-touched" placeholder="Search" size="10">
									</td>	
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="getProductListObj in getProductList | filter : searchProductlist" style="padding: 0;" class="gray-listing" title="[[stateObj.name]]">
									<td>
										<div class="col-sm-10 text-left" ng-if="productId != getProductListObj.product_id"  ng-click="funGetProductWiseAliasNameList(getProductListObj.product_id)">
                                            [[getProductListObj.product_name | capitalize]] 
										</div>
										<div class="col-sm-10 text-left active-gray-listing"  ng-if="productId == getProductListObj.product_id"  ng-click="funGetProductWiseAliasNameList(getProductListObj.product_id)">
											[[getProductListObj.product_name | capitalize]] 
										</div>
										<div class="col-sm-2 " style="width: 18%! important;  margin-left: -5px! important;">
											<span ng-if="{{defined('EDIT') && EDIT}}">
                                                 <button style="margin-left: -14px;"  ng-click="funEditAllCustomerProductMaster(getProductListObj.c_product_id,getProductListObj.product_id)" title="Edit Customer Product" class="btn btn-primary btn-sm"><i aria-hidden="true" class="fa fa-pencil-square-o"></i></button>
                                            </span>
										</div>
									</td>			
									</tr>
							</tbody>
						</table>
					</div>
					<div class="col-sm-9">
						<table class="col-sm-12 table-striped table-condensed cf font15">
						<thead>
						<tr>
							<th><label>Product Name</label></th>
							<th><label>Product Alias Name</label></th>
							<th><label>Created By</label></th>
							<th><label>Action</label></th>
						</tr>		
						</thead>
						<tbody>
							<tr dir-paginate="customerProductObj in customerProductsList | filter:filterCustomerProduct | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
									<td>
										<span ng-click="funEditCustomerProductMaster(customerProductObj.c_product_id)">[[customerProductObj.product_name]]</span>
									</td>
									<td>
										<span ng-click="funEditCustomerProductMaster(customerProductObj.c_product_id)">[[customerProductObj.c_product_name]]</span>
									</td>
									<td>
										<span>[[customerProductObj.createdByName]]</span>
									</td>
									<td>
										<span >
                                            <button  ng-click="funConfirmDeleteMessage(customerProductObj.c_product_id,'0','listDelete')" title="Delete Customer Product" class="btn btn-danger btn-sm"> <i class="fa fa-trash-o" aria-hidden="true"></i></button>
										</span>
									</td>
								</tr>	
						</tbody>
						<tfoot ng-if="customerProductsList.length">
                                <tr>
                                    <td colspan="8">
                                        <div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div>
                                    </td>
                                </tr>                                    
                        </tfoot>
						</table>
					</div>
				</div>
					
			</div>
		</div>
		<div ng-if="!customerProductsList.length" class="col-sm-12">No Record Found!</div>
	</div>

</div>