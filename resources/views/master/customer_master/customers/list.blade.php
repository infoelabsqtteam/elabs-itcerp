<div id="listCustomer" ng-hide="listCustomer">

    <!--Display Heading-->
    <form class="form-inline" method="POST" role="form" name="erpCustomerListForm" action="{{url('master/customer/download-excel')}}" target="blank" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row header">
            <div class="pull-left mT5">
                <strong class="headerText" style="width:300px;" ng-click="funGetCustomers(limitFrom,limitTo)" title="Refresh">Customers <span ng-if="custdata.length">([[custdata.length]])</span></strong>
            </div>
            <div class="navbar-form navbar-right" role="search">
                <div class="nav-custom">
                    <a ng-if="{{defined('ADD') && ADD}}" title="Upload CSV Records" class="form-control btn btn-primary hidden" href="{{ url('/customers/upload')}}">Upload</a>
                    <span ng-if="{{defined('ADD') && ADD}}"><button type="button" class="form-control btn btn-primary btn-sm" title="Upload Sales Executive CSV" data-toggle="modal" data-target="#upload_sales_executives_csv_modal">Upload SE</button></span>
                    <span ng-if="{{defined('ADD') && ADD}}"><button title="Add New Record" type="button" class="form-control btn btn-primary btn-md" ng-click="addCustomerForm()">Add New</button></span>
                    <div style="float: left;color:#000;position: relative;">
                        <input type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Enter Customer Code" ng-change="funGetCustomers(limitFrom,limitTo)" ng-model="searchCustomer.search_keyword">
                        <input class="form-control" style="float: left;width:70px;" type="number" title="Skip" placeholder="Skip" name="limitFrom" ng-change="funGetLimitFromCustomers(searchCustomer.limitFrom)" ng-model="searchCustomer.limitFrom">
                        <span class="pull-left form-control">TO</span>
                        <input class="form-control" style="float: left;width:70px;" type="number" title="Take" placeholder="Take" name="limitTo" ng-change="funGetLimitToCustomers(searchCustomer.limitTo)" ng-model="searchCustomer.limitTo">
                        <button type="button" ng-disabled="!custdata.length" class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
                            Download</button>
                        <div class="dropdown-menu" style="top:34px !important">
                            <input type="submit" formtarget="_blank" name="generate_customer_documents" value="Excel" class="dropdown-item">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/Display Heading-->

        <!--display Listing of Customer-->
        <div class="row">
            <div id="no-more-tables">
                <!-- show error message -->
                <table class="col-sm-12 table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('customer_code')">Code </label>
                                <span class="sortorder" ng-show="predicate === 'customer_code'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('logic_customer_code')">Logic Code </label>
                                <span class="sortorder" ng-show="predicate === 'logic_customer_code'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('customer_name')">Name </label>
                                <span class="sortorder" ng-show="predicate === 'customer_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('city_name')">City </label>
                                <span class="sortorder" ng-show="predicate === 'city_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('customer_address')">Address </label>
                                <span class="sortorder" ng-show="predicate === 'customer_address'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('customer_email1')">Email</label>
                                <span class="sortorder" ng-show="predicate === 'customer_email1'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width8">
                                <label class="sortlabel" ng-click="sortBy('billing_type')">Billing Type</label>
                                <span class="sortorder" ng-show="predicate === 'billing_type'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width8">
                                <label class="sortlabel" ng-click="sortBy('billing_type')">Priority</label>
                                <span class="sortorder" ng-show="predicate === 'customer_priority_id'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width8">
                                <label class="sortlabel" ng-click="sortBy('invoicing_type_id')">Invoicing Type</label>
                                <span class="sortorder" ng-show="predicate === 'invoicing_type_id'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width8">
                                <label class="sortlabel" ng-click="sortBy('customer_status')">Status</label>
                                <span class="sortorder" ng-show="predicate === 'customer_status'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width8">
                                <label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
                                <span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width8">
                                <label class="sortlabel" ng-click="sortBy('customer_created_at')">Created On</label>
                                <span class="sortorder" ng-show="predicate === 'customer_created_at'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width8">
                                <label class="sortlabel" ng-click="sortBy('customer_created_at')">Updated On</label>
                                <span class="sortorder" ng-show="predicate === 'customer_created_at'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>Action<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary mL10"><i class="fa fa-filter"></i></button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-hide="multiSearchTr">
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_customer_code)" name="search_customer_code" ng-model="searchCustomer.search_customer_code" class="multiSearch form-control " placeholder="Code"></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_logic_customer_code)" name="search_logic_customer_code" ng-model="searchCustomer.search_logic_customer_code" class="multiSearch form-control " placeholder="Logic Code"></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_customer_name)" name="search_customer_name" ng-model="searchCustomer.search_customer_name" class="multiSearch form-control " placeholder="Name"></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_customer_city)" name="search_customer_city" ng-model="searchCustomer.search_customer_city" class="multiSearch form-control " placeholder="City"></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_customer_address)" name="search_customer_address" ng-model="searchCustomer.search_customer_address" class="multiSearch form-control " placeholder="Address"></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_customer_email)" name="search_customer_email" ng-model="searchCustomer.search_customer_email" class="multiSearch form-control " placeholder="Email"></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_billing_type)" name="search_billing_type" ng-model="searchCustomer.search_billing_type" class="multiSearch form-control " placeholder="Billing Type"></td>
                            <td></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_invoicing_type)" name="search_invoicing_type" ng-model="searchCustomer.search_invoicing_type" class="multiSearch form-control " placeholder="Invoicing Type"></td>
                            <td></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_created_by)" name="search_created_by" ng-model="searchCustomer.search_created_by" class="multiSearch form-control " placeholder="Created By"></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_created_at)" name="search_created_at" ng-model="searchCustomer.search_created_at" class="multiSearch form-control visibility" placeholder="Created On"></td>
                            <td><input type="text" ng-change="getMultiSearch(searchCustomer.search_updated_at)" name="search_updated_at" ng-model="searchCustomer.search_updated_at" class="multiSearch form-control visibility" placeholder="Updated On"></td>
                            <td class="width10">
                                <button ng-click="refreshMultisearch()" type="button" class="btn btn-primary btn-sm" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                <button ng-click="closeMultisearch()" type="button" class="btn btn-default btn-sm" title="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        <tr dir-paginate="obj in custdata | filter:filterCustomers | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                            <td data-title="Customer Code">[[obj.customer_code ? obj.customer_code : '']]</td>
                            <td data-title="Logic Customer Code">[[obj.logic_customer_code ? obj.logic_customer_code : '']]</td>
                            <td data-title="Customer Name">[[obj.customer_name ? obj.customer_name : '']]</td>
                            <td data-title="Customer City">[[obj.city_name ? obj.city_name : '']]</td>
                            <td data-title="Customer Address">[[obj.customer_address ? obj.customer_address : '' | capitalize]]</td>
                            <td data-title="Customer Email1">[[obj.customer_email ? obj.customer_email : '']]</td>
                            <td style="text-transform: uppercase;" data-title="Billing Type">[[obj.billingType ? obj.billingType : '']]</td>
                            <td style="text-transform: uppercase;" data-title="Customer Priority Type">[[obj.customerPriorityType ? obj.customerPriorityType : '']]</td>
                            <td style="text-transform: uppercase;" data-title="Invoicing Type">[[obj.invoicing_type ? obj.invoicing_type : '']]</td>
                            <td data-title="Customer Status">
                                <span ng-if="obj.customer_status == '0'">Pending</span>
                                <span ng-if="obj.customer_status == '1'">Active</span>
                                <span ng-if="obj.customer_status == '2'">Inactive</span>
                                <span ng-if="obj.customer_status == '3'">Hold</span>
                            </td>
                            <td data-title="Created By">[[obj.createdBy ? obj.createdBy : '' ]]</td>
                            <td data-title="Created At">[[obj.created_at ? obj.customer_created_at : '' ]]</td>
                            <td data-title="Updated At">[[obj.updated_at ? obj.customer_updated_at : '' ]]</td>
                            <td class="width10" ng-if="{{ (defined('EDIT') && EDIT) || (defined('DELETE') && DELETE) || (defined('VIEW') && VIEW)}}">
                                <a href="javascript:;" ng-if="{{defined('VIEW') && VIEW}}" title="View" class="btn btn-primary btn-sm hidden" ng-click='viewCustomerDetails(obj.customer_id,obj.division_id)'><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" title="Add Customer Email Addresses" ng-click="funShowCustomerEmailAddressesForm(obj.customer_id,obj.division_id)" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                <a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Edit" class="btn btn-primary btn-sm" ng-click='editCustomer(obj.customer_id,obj.division_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <a href="javascript:;" ng-if="{{defined('VIEW') && VIEW}} && obj.customer_status == 1" title="Hold" class="btn btn-danger btn-sm" ng-click="funOpenCustomerHoldPopup('erpCustomerOnHoldPopupDivId','show',obj.customer_id,obj.customer_name)"><i class="fa fa-stop" aria-hidden="true"></i></a>
                                <a href="javascript:;" ng-if="{{(defined('IS_ADMIN') && IS_ADMIN) && (defined('VIEW') && VIEW)}} && obj.customer_status == 3" title="Unhold" class="btn btn-success btn-sm" ng-click='funConfirmUnholdMessage(obj.customer_id,1)'><i class="fa fa-play" aria-hidden="true"></i></a>
                                <a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(obj.customer_id,'deleteCustomerRecord')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        <tr ng-if="!custdata.length" class="noRecord">
                            <td colspan="14">No Record Found!</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr ng-if="custdata.length">
                            <td colspan="[[custdata.length]]">
                                <div class="box-footer clearfix">
                                    <dir-pagination-controls></dir-pagination-controls>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
        <!--/display Listing of Customer-->
    </form>

    <!--dispatch Order Popup Window-->
    @include('master.customer_master.customers.customerHoldDetailPopupWindow')
    <!--/dispatch Order Popup Window-->

    <!--dispatch Order Popup Window-->
    @include('master.customer_master.customers.uploadSalesExeCsv')
    <!--/dispatch Order Popup Window-->

</div>
