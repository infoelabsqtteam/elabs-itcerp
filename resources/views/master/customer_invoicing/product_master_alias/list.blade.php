<div class="row" ng-hide="listCustomerProductFormBladeDiv">
    <form class="form-inline" method="POST" target="blank" action="{{url('master/product-alias/download-excel')}}" role="form" id="erpProductAliasFilterForm" name="erpProductAliasFilterForm" novalidate>

        <!--search-->
        <div class="row header">
            <div role="new" class="navbar-form navbar-left">
                <div><strong ng-click="funGetCustomerProductsList(0)" id="form_title">Products Alias Listing<span>([[customerProductsAliasCount]])</span></strong></div>
            </div>
            <label for="submit">{{ csrf_field() }}</label>
            <div role="new" class="navbar-form navbar-right">
                <div class="nav-custom custom-display">
                    <input type="text" ng-keypress="funEnterPressHandler($event)" placeholder="Search" ng-model="filterCustomerProduct" class="form-control ng-pristine ng-untouched ng-valid">
                    <select class="form-control width30" ng-init="funGetParentCategory();" name="product_category_id" ng-model="productMasterAliasFilter.product_category_id.selectedOption" id="product_category_id" ng-change="funGetCustomerProductsList(0)" ng-options="item.name for item in parentCategoryList track by item.id"></select>
                    <button type="button" ng-disabled="!customerProductAliasList" class="form-control btn btn-default dropdown dropdown-toggle " data-toggle="dropdown" title="Download">Download</button>
                    <div class="dropdown-menu">
                        <input type="submit" name="generate_product_alias_documents" class="dropdown-item" value="Excel">
                    </div>
                    <span ng-if="{{defined('ADD') && ADD}}">
                        <button type="button" ng-click="navigateCustomerProductForms()" class="btn btn-primary mT2">Add New</button>
                    </span>
                </div>
            </div>

        </div>
        <div class="row" id="no-more-tables">
            <div class="panel panel-default">
                <div class="panel-body col-sm-12">
                    <div class="col-sm-3 text-left custom-scroll">
                        <table class="table table-striped table-condensed cf">
                            <thead>
                                <tr>
                                    <th>
                                        <label ng-click="funGetCustomerProductsList()">Products Name ([[customerProductAliasList.leftSideDataList.length]])</label>
                                    </th>
                                    <td>
                                        <input type="text" ng-keypress="funEnterPressHandler($event)" name="search_keyword" ng-change="funSearchProductAliasTerm(search_keyword)" ng-model="search_keyword" class="invoicingSearch form-control" placeholder="Search" size="10">
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="customerProductAliasListObj in customerProductAliasList.leftSideDataList" style="padding: 0;" class="gray-listing" title="[[stateObj.name]]">
                                    <td>
                                        <div title="[[selectedProductID]]/[[customerProductAliasListObj.product_id]]" class="txt-left capitalize" ng-if="selectedProductID != customerProductAliasListObj.product_id" ng-click="funGetProductListing(customerProductAliasListObj.product_id)">[[customerProductAliasListObj.product_name | capitalize]]</div>
                                        <div title="[[selectedProductID]]/[[customerProductAliasListObj.product_id]]" class="txt-left capitalize active-gray-listing" ng-if="selectedProductID == customerProductAliasListObj.product_id" ng-click="funGetProductListing(customerProductAliasListObj.product_id)">[[customerProductAliasListObj.product_name | capitalize]]</div>
                                    </td>
                                    <td>
                                        <div class="txt-right" ng-if="{{defined('EDIT') && EDIT}}"><button type="button" ng-keypress="funEnterPressHandler($event)" style="margin-left: -14px;" ng-click="funEditAllCustomerProductMaster(customerProductAliasListObj.c_product_id,customerProductAliasListObj.product_id)" title="Edit Customer Product" class="btn btn-primary btn-sm"><i aria-hidden="true" class="fa fa-pencil-square-o"></i></button></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-9">
                        <table class="table table-striped table-condensed cf font15">
                            <thead>
                                <tr>
                                    <th><label>Department Name</label></th>
                                    <th><label>Product Name</label></th>
                                    <th><label>Product Alias Name</label></th>
                                    <th><label>Created By</label></th>
                                    <th><label>Action</label></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr dir-paginate="customerProductObj in customerProductAliasList.rightSideDataList | filter:filterCustomerProduct | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                                    <td>[[customerProductObj.department_name]]</td>
                                    <td>
                                        <div ng-click="funEditCustomerProductMaster(customerProductObj.c_product_id)">[[customerProductObj.product_name]]</div>
                                    </td>
                                    <td>
                                        <div ng-click="funEditCustomerProductMaster(customerProductObj.c_product_id)">[[customerProductObj.c_product_name]]</div>
                                    </td>
                                    <td>[[customerProductObj.createdByName]]</td>
                                    <td>
                                        <button type="button" ng-keypress="funEnterPressHandler($event)" ng-click="funConfirmDeleteMessage(customerProductObj.c_product_id,customerProductObj.product_id,'listDelete')" title="Delete Customer Product" class="btn btn-danger btn-sm"> <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                                <tr ng-if="!customerProductAliasList.rightSideDataList.length">
                                    <td colspan="5">No Record Found!</td>
                                </tr>
                            </tbody>
                            <tfoot ng-if="customerProductAliasList.rightSideDataList.length">
                                <tr>
                                    <td colspan="4">
                                        <div class="box-footer clearfix">
                                            <dir-pagination-controls></dir-pagination-controls>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
