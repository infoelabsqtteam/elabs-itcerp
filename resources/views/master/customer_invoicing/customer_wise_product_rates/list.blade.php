<div class="row" ng-hide="listCustomerWiseProductRateDiv">

    <form class="form-inline" method="POST" target="blank" action="{{url('master/customer-wise-products/download-excel')}}" role="form" id="erpCustomerWiseProductFilterForm" name="erpCustomerWiseProductFilterForm" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <!--heading-->
        <div class="row header">
            <div role="new" class="navbar-form navbar-left">
                <span ng-click="funRefreshCustomerWiseProductRates(0,1,1,'modify','')" class="pull-left"><strong id="form_title">Customer Wise Product Rate Listing<span ng-if="customerWiseProductRateList.rightSideDataList.length">([[customerWiseProductRateList.rightSideDataList.length]])</span></strong></span>
            </div>
            <div role="new" class="navbar-form navbar-right">
                <div class="nav-custom" style="display: flex;position: relative">
                    <input type="text" placeholder="Search" name="search_keyword" ng-model="filterProductCustomersList" class="form-control width25">
                    <select class="form-control width30" name="cir_division_id" ng-model="defaultDivisionId.selectedOption" id="cir_division_id" ng-change="funGetCustomerWiseProductRates(cirCustomerID,customerWiseProductRate.cir_product_category_id.selectedOption.id,defaultDivisionId.selectedOption.id,'',cirSearchKeywordID)" ng-options="item.name for item in divisionsCodeList track by item.id"></select>
                    <select class="form-control width30" ng-init="funGetParentCategory();" name="cir_product_category_id" ng-model="customerWiseProductRate.cir_product_category_id.selectedOption" id="cir_product_category_id" ng-change="funGetCustomerWiseProductRates(cirCustomerID,customerWiseProductRate.cir_product_category_id.selectedOption.id,defaultDivisionId.selectedOption.id,'',cirSearchKeywordID)" ng-options="item.name for item in parentCategoryList track by item.id"></select>
                    <button style="" type="button" ng-disabled="!customerWiseProductRateList.rightSideDataList.length" class="form-control btn btn-default dropdown dropdown-toggle " data-toggle="dropdown" title="Download">Download</button>
                    <div class="dropdown-menu"><input type="submit" name="generate_customer_wise_product_documents" class="dropdown-item" value="Excel"></div>
                    <span ng-if="{{defined('ADD') && ADD}}"><button type="button" class="btn btn-primary" ng-click="navigateFormPage('add');">Add New</button></span>
                    <input type="hidden" name="cir_customer_id" value="[[SelectedCustomer]]">
                </div>
            </div>
        </div>
        <!--/heading-->
    </form>

    <!--display record-->
    <div class="row" id="no-more-tables">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row col-sm-12">
                    <div class="col-sm-4 text-left custom-scroll">
                        <table class="table table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th>
                                        <label ng-click="funGetCustomerWiseProductRates(cirCustomerID,cirDepartmentID,cirDivisionID,'modify');" class="sortlabel">Customer Name<span ng-if="customerWiseProductRateList.leftSideDataList.length">([[customerWiseProductRateList.leftSideDataList.length]])</span></label>
                                        <input type="text" style="width: 200px !important;" ng-change="funSearchCustomerWiseProductRates(cirCustomerID,cirDepartmentID,cirDivisionID,'modify',cirSearchKeyword);" ng-model="cirSearchKeyword" class="form-control leftside-search" placeholder="Search">
                                    </th>
                                    <th class="text-center"><label> City </label></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="customerObj in customerWiseProductRateList.leftSideDataList" style="padding: 0;" title="[[customerObj.customer_name]]">
                                    <td class="paddingTopRight">
                                        <div class="col-sm-12 text-left" ng-if="cirCustomerID != customerObj.customer_id" ng-click="funGetCustomerWiseProductRates(customerObj.customer_id,cirDepartmentID,cirDivisionID,'modify',cirSearchKeywordID)">[[customerObj.customer_name]]</div>
                                        <div class="col-sm-12 text-left active-gray-listing" ng-if="cirCustomerID == customerObj.customer_id" ng-click="funGetCustomerWiseProductRates(customerObj.customer_id,cirDepartmentID,cirDivisionID,'modify',cirSearchKeywordID)">[[customerObj.customer_name]]</div>
                                    </td>
                                    <td class="paddingTopRight">
                                        <div class="col-sm-12 text-left" ng-if="cirCustomerID != customerObj.customer_id" ng-click="funGetCustomerWiseProductRates(customerObj.customer_id,cirDepartmentID,cirDivisionID,'modify',cirSearchKeywordID)">[[customerObj.city_name]]</div>
                                        <div class="col-sm-12 text-left active-gray-listing" ng-if="cirCustomerID == customerObj.customer_id" ng-click="funGetCustomerWiseProductRates(customerObj.customer_id,cirDepartmentID,cirDivisionID,'modify',cirSearchKeywordID)">[[customerObj.city_name]]</div>
                                    </td>
                                    <td class="paddingTopRight">
                                        <a ng-if="customerObj.cir_c_product_id" href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditSelectedCustomerAllProductRate(customerObj.customer_id,cirDepartmentID,cirDivisionID);funGetCustomersCity(customerObj.city_id,customerObj.city_name,'add')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        <a ng-if="!customerObj.cir_c_product_id" href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditCustomerWiseProductRate(customerObj.cir_id);"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-8 text-left">
                        <table class="table table-striped table-condensed cf font15">
                            <thead class="cf">
                                <tr>
                                    <th>
                                        <label ng-click="sortBy('dept_name')" class="sortlabel">Department</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'dept_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('product_name')" class="sortlabel">Product Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'product_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('c_product_name')" class="sortlabel">Product Alias Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'c_product_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('invoicing_rate')" class="sortlabel">Rate</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoicing_rate'" class="sortorder reverse"></span>
                                    </th>
                                    <th ng-if="{{defined('DELETE') && DELETE }}">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr dir-paginate="customerWiseProductRateObj in customerWiseProductRateList.rightSideDataList | filter:filterProductCustomersList | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                                    <td data-title="Department Category" class="ng-binding">[[customerWiseProductRateObj.dept_name]]</td>
                                    <td data-title="Product Category" class="ng-binding">[[customerWiseProductRateObj.product_name]]</td>
                                    <td data-title="Sub Product Category" class="ng-binding">[[customerWiseProductRateObj.c_product_name]]</td>
                                    <td data-title="Invoicing Rate" class="ng-binding">[[customerWiseProductRateObj.invoicing_rate]]</td>
                                    <td data-title="Invoicing Rate" class="ng-binding">
                                        <a href="javascript:;" ng-if="{{defined('DELETE') && DELETE }}" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(customerWiseProductRateObj.cir_id,cirCustomerID,cirDepartmentID,cirDivisionID,'modify',cirSearchKeywordID);"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <tr ng-if="!customerWiseProductRateList.rightSideDataList.length">
                                    <td colspan="8">No Record Found!</td>
                                </tr>
                            </tbody>
                            <tfoot ng-if="customerWiseProductRateList.rightSideDataList.length > {{ defined('PERPAGE') ? PERPAGE : 15 }}">
                                <tr>
                                    <td colspan="5">
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
    </div>
</div>
