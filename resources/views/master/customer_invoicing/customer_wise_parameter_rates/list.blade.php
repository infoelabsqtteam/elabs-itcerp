<div class="row" ng-if="listCustomerWiseProductRateDiv">

    <!--heading-->
    <form class="form-inline" method="POST" target="blank" action="{{url('master/customer-wise-parameters/download-excel')}}" role="form" id="erpCustomerWiseParametersFilterForm" name="erpCustomerWiseParametersFilterForm" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row header">
            <div role="new" class="navbar-form navbar-left">
                <span ng-click="funGetRefreshCustomerWiseParameterRates(0,1,1,'')" class="pull-left"><strong id="form_title">Customer Wise Parameters Rate Listing<span ng-if="customerWiseParameterRateList.rightSideDataList.length">([[customerWiseParameterRateList.rightSideDataList.length]])</span></strong></span>
            </div>
            <div role="new" class="navbar-form navbar-right">
                <div class="nav-custom" style="display: flex;position: relative">
                    <input type="text" placeholder="Search" name="search_keyword" ng-model="filterListCustomerWiseParameterRate" class="form-control ng-pristine ng-untouched ng-valid">
                    <select class="form-control width30 " name="cir_division_id" ng-model="defaultDivisionId.selectedOption" id="cir_division_id" ng-required='true' ng-change="funCustomerWiseParameterAccToDept(cirCustomerID,deptID.selectedOption.id,defaultDivisionId.selectedOption.id,cirSearchKeywordID)" ng-options="item.name for item in divisionsCodeList track by item.id"></select>
                    <select class="form-control width30 " name="cir_product_category_id" ng-model="deptID.selectedOption" id="cir_product_category_id" ng-required='true' ng-change="funCustomerWiseParameterAccToDept(cirCustomerID,deptID.selectedOption.id,defaultDivisionId.selectedOption.id,cirSearchKeywordID)" ng-options="item.name for item in parentCategoryList track by item.id"></select>
                    <button type="button" ng-disabled="!customerWiseParameterRateList.rightSideDataList.length" class="form-control btn btn-default dropdown dropdown-toggle " data-toggle="dropdown" title="Download">Download</button>
                    <div class="dropdown-menu"><input type="submit" name="generate_customer_parameter_documents" class="dropdown-item" value="Excel"></div>
                    <span ng-if="{{defined('ADD') && ADD}}"><button style="top:2px;" type="button" class="btn btn-primary" ng-click="navigateFormPage('add');">Add New</button></span>
                    <input type="hidden" name="cir_customer_id" value="[[cirCustomerID]]">
                </div>
            </div>
        </div>
    </form>
    <!--/heading-->

    <!--display record-->
    <div class="row" id="no-more-tables">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row col-sm-12">
                    <div class="col-sm-3 text-left custom-scroll">
                        <table class="table table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
                                    <th>
                                        <label ng-click="funGetCustomerWiseParameterRates(0,deptID.selectedOption.id,defaultDivisionId.selectedOption.id,'');" class="sortlabel">Customer Name<span ng-if="customerWiseParameterRateList.leftSideDataList.length">([[customerWiseParameterRateList.leftSideDataList.length]])</span></label>
                                        <input type="text" style="width:200px!important;" ng-change="funSearchCustomerWiseParameterRates(cirCustomerID, cirDeptID, cirDivisionID, cirSearchKeyword);" id="cirSearchKeyword" ng-model="cirSearchKeyword" class="form-control leftside-search" placeholder="Search">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="customerObj in customerWiseParameterRateList.leftSideDataList" style="padding: 0;" title="[[customerObj.customer_name]]">
                                    <td data-title="Customer Name" class="ng-binding">
                                        <div class="col-sm-10 text-left" ng-if="cirCustomerID != customerObj.customer_id" ng-click="funGetCustomerWiseParameterRates(customerObj.customer_id,deptID.selectedOption.id,defaultDivisionId.selectedOption.id,cirSearchKeywordID)">[[customerObj.customer_name]]</div>
                                        <div class="col-sm-10 text-left active-gray-listing" ng-if="cirCustomerID == customerObj.customer_id" ng-click="funGetCustomerWiseParameterRates(customerObj.customer_id,deptID.selectedOption.id,defaultDivisionId.selectedOption.id,cirSearchKeywordID)">[[customerObj.customer_name]]</div>
                                        <div class="col-sm-2 editbtn"><a style="margin-left: 9px;" href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditSelectedCustomerParametersRate(customerObj.customer_id);"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-9 text-left">
                        <table class="table table-striped table-condensed cf font15">
                            <thead class="cf">
                                <tr>
                                    <th>
                                        <label ng-click="sortBy('p_category_name')" class="sortlabel">Product Category</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'p_category_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('test_para_cat_name')" class="sortlabel">Parameter Category</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'test_para_cat_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('test_parameter_name')" class="sortlabel">Parameter Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'test_parameter_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('equipment_name')" class="sortlabel">Equipment Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'equipment_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th><label>Test Standard</label></th>
                                    <th>
                                        <label ng-click="sortBy('invoicing_rate')" class="sortlabel">Rate</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoicing_rate'" class="sortorder reverse"></span>
                                    </th>
                                    <th ng-if="{{defined('DELETE') && DELETE }}">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr dir-paginate="customerWiseParameterRateObj in customerWiseParameterRateList.rightSideDataList | filter:filterListCustomerWiseParameterRate | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                                    <td data-title="Product Category" class="ng-binding">[[customerWiseParameterRateObj.p_category_name]]</td>
                                    <td data-title="Parameter Category" class="ng-binding">[[customerWiseParameterRateObj.test_para_cat_name]]</td>
                                    <td data-title="Parameter Name" data="[[customerWiseParameterRateObj.cir_parameter_id]]" class="ng-binding" ng-bind-html="customerWiseParameterRateObj.test_parameter_name"></td>
                                    <td data-title="Equipment Name" class="ng-binding">[[customerWiseParameterRateObj.equipment_name]]</td>
                                    <td data-title="Test Standard" class="ng-binding">
                                        <span ng-if="!customerWiseParameterRateObj.test_std_name">-</span>
                                        <span ng-if="customerWiseParameterRateObj.test_std_name" ng-bind-html="customerWiseParameterRateObj.test_std_name">[[customerWiseParameterRateObj.test_std_name]]</span>
                                    </td>
                                    <td data-title="Invoicing Rate" class="ng-binding">[[customerWiseParameterRateObj.invoicing_rate]]</td>
                                    <td ng-if="{{defined('DELETE') && DELETE }}" data-title="Invoicing Rate" class="ng-binding">
                                        <a href="javascript:;" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(customerWiseParameterRateObj.cir_id,cirCustomerID, cirDeptID, cirDivisionID, cirSearchKeywordID);"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <tr ng-if="!customerWiseParameterRateList.rightSideDataList.length">
                                    <td colspan="8">No Record Found!</td>
                                </tr>
                            </tbody>
                            <tfoot ng-if="customerWiseParameterRateList.rightSideDataList.length > {{ defined('PERPAGE') ? PERPAGE : 15 }}">
                                <tr>
                                    <td colspan="8">
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
