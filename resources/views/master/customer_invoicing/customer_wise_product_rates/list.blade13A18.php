<div class="row" ng-hide="listCustomerWiseProductRateDiv">

    <!--heading-->
    <div class="row header">
        <div role="new" class="navbar-form navbar-left">
            <span ng-click="funGetCustomerWiseProductRates(0,cirDepartmentID,'modify')" class="pull-left"><strong id="form_title">Customer Wise Product Rate Listing<span ng-if="customerWiseProductRateList.rightSideDataList.length">([[customerWiseProductRateList.rightSideDataList.length]])</span></strong></span>
        </div>
        <div role="new" class="navbar-form navbar-right">
            <div class="nav-custom">
                <input type="text" placeholder="Search" ng-model="filterProductCustomersList" class="form-control ng-pristine ng-untouched ng-valid" >
                <select class="form-control selectDept"
                  name="cir_product_category_id" style="margin-top: -6px;"
                  ng-model="defaultDivisionId.selectedOption"
                  id="cir_product_category_id"
                  ng-change = "funGetCustomerWiseProductRates(cirCustomerID,customerWiseProductRate.cir_product_category_id.selectedOption.id,defaultDivisionId.selectedOption.id)"
                  ng-options="item.name for item in divisionsCodeList track by item.id">
                </select>
                <select
          			class="form-control"
          			ng-init="funGetParentCategory();"
          			name="cir_product_category_id"
          			ng-model="customerWiseProductRate.cir_product_category_id.selectedOption"
          			id="cir_product_category_id"
          			ng-change = "funGetCustomerWiseProductRates(cirCustomerID,customerWiseProductRate.cir_product_category_id.selectedOption.id)"
          			ng-options="item.name for item in parentCategoryList track by item.id"
          			>
                </select>
                <span ng-if="{{defined('ADD') && ADD}}">
                    <button type="button" class="btn btn-primary" ng-click="addFormPage('add');">Add New</button>
                </span>
            </div>
        </div>
    </div>
    <!--/heading-->

    <!--display record-->
    <div class="row" id="no-more-tables">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row col-sm-12">
                    <div class="col-sm-4 text-left custom-scroll">
                        <table class="col-sm-12 table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>
					<th>
					    <label ng-click="funGetCustomerWiseProductRates(cirCustomerID,cirDepartmentID,'modify');" class="sortlabel">Customer Name<span ng-if="customerWiseProductRateList.leftSideDataList.length">([[customerWiseProductRateList.leftSideDataList.length]])</span></label>
					    <input type="text" style="width: 150px ! important;" ng-model="filterLeftSideCustomerDataList" class="form-control ng-pristine ng-untouched ng-valid leftside-search" placeholder="Search">
					</th>
					<th class="text-center"><label> City </label></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="customerObj in customerWiseProductRateList.leftSideDataList | filter : filterLeftSideCustomerDataList" style="padding: 0;" title="[[customerObj.customer_name]]">
					<td class="paddingTopRight">
						<div class="col-sm-12 text-left" ng-if="cirCustomerID != customerObj.customer_id" ng-click="funGetCustomerWiseProductRates(customerObj.customer_id,cirDepartmentID,cirDivisionID,'modify')">[[customerObj.customer_name]]</div>
						<div class="col-sm-12 text-left active-gray-listing" ng-if="cirCustomerID == customerObj.customer_id" ng-click="funGetCustomerWiseProductRates(customerObj.customer_id,cirDepartmentID,cirDivisionID,'modify')">[[customerObj.customer_name]]</div>
					</td>
					<td class="paddingTopRight">
						<div class="col-sm-12 text-left" ng-if="cirCustomerID != customerObj.customer_id" ng-click="funGetCustomerWiseProductRates(customerObj.customer_id,cirDepartmentID,cirDivisionID,'modify')">[[customerObj.city_name]]</div>
						<div class="col-sm-12 text-left active-gray-listing" ng-if="cirCustomerID == customerObj.customer_id" ng-click="funGetCustomerWiseProductRates(customerObj.customer_id,cirDepartmentID,cirDivisionID,'modify')">[[customerObj.city_name]]</div>
					</td>
					<td class="paddingTopRight">
						<a ng-if="customerObj.cir_c_product_id" href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditSelectedCustomerAllProductRate(customerObj.customer_id,cirDepartmentID,cirDivisionID);funGetCustomersCity(customerObj.city_id,customerObj.city_name,'add')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a ng-if="!customerObj.cir_c_product_id" href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditCustomerWiseProductRate(customerObj.cir_id);"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>[[customerObj.cir_customer_id]]</a>
					</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-8 text-left">
                        <table class="col-sm-12 table-striped table-condensed cf font15">
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
						<td ng-click="funEditCustomerWiseProductRate(customerWiseProductRateObj.cir_id);" data-title="Department Category" class="ng-binding">[[customerWiseProductRateObj.dept_name]]</td>
						<td ng-click="funEditCustomerWiseProductRate(customerWiseProductRateObj.cir_id);" data-title="Product Category" class="ng-binding">[[customerWiseProductRateObj.product_name]]</td>
						<td ng-click="funEditCustomerWiseProductRate(customerWiseProductRateObj.cir_id);" data-title="Sub Product Category" class="ng-binding">[[customerWiseProductRateObj.c_product_name]]</td>
						<td ng-click="funEditCustomerWiseProductRate(customerWiseProductRateObj.cir_id);" data-title="Invoicing Rate" class="ng-binding">[[customerWiseProductRateObj.invoicing_rate]]</td>
						<td data-title="Invoicing Rate" class="ng-binding">
						    <a href="javascript:;" ng-if="{{defined('DELETE') && DELETE }}" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(customerWiseProductRateObj.cir_id,cirCustomerID,cirDepartmentID,formType);"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-if="!customerWiseProductRateList.rightSideDataList.length"><td colspan="8">No Record Found!</td></tr>
				</tbody>
				<tfoot ng-if="customerWiseProductRateList.rightSideDataList.length > {{ defined('PERPAGE') ? PERPAGE : 15 }}">
					<tr>
					    <td colspan="5"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
					</tr>
				</tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
