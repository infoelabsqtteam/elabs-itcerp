<div class="row" ng-hide="IsViewList" id="IsViewList">

    <!--erpFilterOrderForm-->
    <form class="form-inline" method="POST" role="form" id="erpFilterOrderForm" name="erpFilterOrderForm" action="{{ url('sales/orders/generate-branch-wise-order-pdf') }}" novalidate>

        <!--search-->
        <div class="row header">
            <div role="new" class="navbar-form navbar-left">
                <strong id="form_title" title="Refresh" ng-click="funRefreshOrdersList()">Order Listing<span ng-if="orderData.length">([[orderData.length]])</span></strong>
            </div>
            <div role="new" class="navbar-form navbar-right">
                <div class="nav-custom">
                    <input type="text" placeholder="Search" ng-model="filterOrders.keyword" name="keyword" ng-keypress="funEnterPressHandler($event)" ng-change="funFilterOrderOnBarCodeScan(filterOrders.keyword)" class="form-control ng-pristine ng-untouched ng-valid autoFocus">
                    <span ng-if="{{defined('IS_ADMIN') && defined('ADD')}}">
                        <button type="button" class="form-control btn btn-primary btn-sm" title="Upload PO CSV" data-toggle="modal" data-target="#upload_purchase_order_csv_modal">Upload PO</button>
                    </span>
                    <span ng-if="{{defined('ADD') && ADD}}">
                        <button type="button" ng-click="openNewOrderForm()" class="btn btn-primary" id="add_new_order" type="button"> [[buttonText]] </button>
                    </span>                    
                </div>
            </div>
        </div>
        <!--/search-->

        <!--display record-->
        <div class="row" id="no-more-tables">
            <!--Filter By-->
            <div class="panel panel-default filterForm" style="margin-top: 0px;">
                <div class="panel-body">
                    <div class="row">
                        <div ng-if="{{$division_id}} == 0" class="col-xs-2 form-group">
                            <label for="from">Branch</label>
                            <select class="form-control width200" ng-model="filterOrders.division_id" name="division_id" ng-options="division.name for division in divisionsCodeList track by division.id">
                                <option value="">All Branch</option>
                            </select>
                        </div>
                        <div ng-if="{{$division_id}} > 0">
                            <input type="hidden" value="{{$division_id}}" name="division_id">
                        </div>
                        <div class="col-xs-2 form-group">
                            <label for="from">Filter From</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterOrders.order_date_from" name="order_date_from" id="order_date_from" placeholder="Date From" />
                                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            </div>
                        </div>
                        <div class="col-xs-2 form-group">
                            <label for="to">Filter To</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterOrders.order_date_to" name="order_date_to" id="order_date_to" placeholder="Date To" />
                                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            </div>
                        </div>
                        <div class="col-xs-2 form-group mT30">
                            <label for="submit">{{ csrf_field() }}</label>
                            <button type="button" title="Filter" ng-disabled="erpFilterOrderForm.$invalid" class="btn btn-primary" ng-click="funFilterOrderByStatus()"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <button type="button" ng-disabled="!orderData.length" class="btn btn-primary dropdown dropdown-toggle" data-toggle="dropdown" title="Download"><i aria-hidden="true" class="fa fa-print"></i></button>
                            <div class="dropdown-menu">
                                <input type="submit" formtarget="_blank" name="generate_order_documents" value="Excel" class="dropdown-item">
                                <input type="submit" formtarget="_blank" name="generate_order_documents" value="PDF" class="dropdown-item">
                            </div>
                            <button type="button" title="Refresh" ng-disabled="erpFilterOrderForm.$invalid" class="btn btn-default" ng-click="funRefreshOrdersList()"><i aria-hidden="true" class="fa fa-refresh"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Filter By-->

            <!--Listing of Orders-->
            <div class="col-sm-12 tableRecord">
                <table class="col-sm-12 table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th>
                                <label ng-click="sortBy('order_no')" class="sortlabel">Order No. </label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_no'" class="sortorder reverse"></span>
                            </th>
                            <th ng-if="{{$division_id}} == 0">
                                <label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
                                <span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label ng-click="sortBy('stb_prototype_no')" class="sortlabel">Prototype No.</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'stb_prototype_no'" class="sortorder reverse"></span>
                            </th>
                            <th>
                                <label ng-click="sortBy('customer_code')" class="sortlabel">Customer Code</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_code'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th>
                                <label ng-click="sortBy('customer_name')" class="sortlabel">Customer Name</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_name'" class="sortorder reverse ng-hide"></span>
                            </th>                            
                            <th>
                                <label ng-click="sortBy('city_name')" class="sortlabel">Place</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'city_name'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th>
                                <label ng-click="sortBy('order_date')" class="sortlabel">Order Date </label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_date'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th>
                                <label ng-click="sortBy('expected_due_date')" class="sortlabel">Expected Due Date</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'expected_due_date'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th>
                                <label ng-click="sortBy('sample_description')" class="sortlabel">Sample Description</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_description'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th>
                                <label ng-click="sortBy('batch_no')" class="sortlabel">Batch No.</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'batch_no'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width8">
                                <label ng-click="sortBy('sample_priority_name')" class="sortlabel">Sample Priority</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_priority_name'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width8">
                                <label ng-click="sortBy('remarks')" class="sortlabel">Remarks</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'remarks'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width8">
                                <label ng-click="sortBy('order_status_name')" class="sortlabel">Status</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_status_name'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width8">
                                <label ng-click="sortBy('createdByName')" class="sortlabel">Created By</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'createdByName'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width10">Action
                                <button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-hide="multiSearchTr">
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_order_no)" name="search_order_no" ng-model="filterOrders.search_order_no" class="multiSearch form-control" placeholder="OrderNo"></td>
                            <td class="width10" ng-if="{{$division_id}} == 0"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_stb_prototype_no)" name="search_stb_prototype_no" ng-model="filterOrders.search_stb_prototype_no" class="multiSearch form-control" placeholder="Prototype No"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_customer_code)" name="search_customer_code" ng-model="filterOrders.search_customer_code" class="multiSearch form-control" placeholder="Code"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_customer_name)" name="search_customer_name" ng-model="filterOrders.search_customer_name" class="multiSearch form-control" placeholder="Name"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_customer_city)" name="search_customer_city" ng-model="filterOrders.search_customer_city" class="multiSearch form-control" placeholder="Place"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_order_date)" name="search_order_date" ng-model="filterOrders.search_order_date" class="multiSearch form-control" placeholder="dd-mm-yyyy"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_expected_due_date)" name="search_expected_due_date" ng-model="filterOrders.search_expected_due_date" class="multiSearch form-control" placeholder="dd-mm-yyyy"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_sample_description)" name="search_sample_description" ng-model="filterOrders.search_sample_description" class="multiSearch form-control" placeholder="Sample Description"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_batch_no)" name="search_batch_no" ng-model="filterOrders.search_batch_no" class="multiSearch form-control" placeholder="Batch No."></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_sample_priority_name)" name="search_sample_priority_name" ng-model="filterOrders.search_sample_priority_name" class="multiSearch form-control" placeholder="Sample Priority"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_remarks)" name="search_remarks" ng-model="filterOrders.search_remarks" class="multiSearch form-control" placeholder="Remarks"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_status)" name="search_status" ng-model="filterOrders.search_status" class="multiSearch form-control" placeholder="Status"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterOrders.search_created_by)" name="search_created_by" ng-model="filterOrders.search_created_by" class="multiSearch form-control" placeholder="Created By"></td>
                            <td class="width10">
                                <button type="button" ng-click="refreshMultisearch(divisionID)" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                <button type="button" ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        <tr dir-paginate="orderObj in orderData | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                            <td data-title="Order No" class="ng-binding[[orderObj.customer_status_class]]"><span title="[[orderObj.product_name]]">[[orderObj.order_no]]<span ng-if="orderObj.order_id == newOrderActive" class="won"><sup>NEW</sup></span></span></td>
                            <td ng-if="{{$division_id}} == 0" data-title="Division Name" class="ng-binding[[orderObj.customer_status_class]]">[[orderObj.division_name]] </td>
                            <td data-title="Prototype No" class="ng-binding[[orderObj.customer_status_class]]">[[orderObj.stb_prototype_no]]</td>
                            <td data-title="Customer Code" class="ng-binding[[orderObj.customer_status_class]]">[[orderObj.customer_code]]</td>
                            <td data-title="Customer Name" class="ng-binding[[orderObj.customer_status_class]]">[[orderObj.customer_name]]</td>
                            <td data-title="Customer City" class="ng-binding[[orderObj.customer_status_class]]">[[orderObj.customer_city]]</td>
                            <td data-title="Order Date" class="ng-binding[[orderObj.customer_status_class]]">[[orderObj.order_date]]</td>
                            <td data-title="Expected Due Date" class="ng-binding[[orderObj.customer_status_class]]">[[orderObj.expected_due_date]]</td>
                            <td data-title="Sample Description" class="ng-binding[[orderObj.customer_status_class]]"> [[ orderObj.sample_description]] </td>
                            <td data-title="Batch No" class="ng-binding[[orderObj.customer_status_class]]"> [[ orderObj.batch_no]] </td>
                            <td data-title="Remarks" class="ng-binding[[orderObj.customer_status_class]]">[[orderObj.sample_priority_name ? orderObj.sample_priority_name : '-']]</td>
                            <td data-title="Remarks" class="ng-binding[[orderObj.customer_status_class]]">
                                <span id="samplelimitedText-[[orderObj.order_no]]">
                                    [[ orderObj.remarks | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
                                    <a href="javascript:;" ng-click="toggleDescription('sample',orderObj.order_no)" ng-show="orderObj.remarks.length > 150" class="readMore">read more...</a>
                                </span>
                                <span id="samplefullText-[[orderObj.order_no]]" style="display:none;">
                                    [[ orderObj.remarks]]
                                    <a href="javascript:;" ng-click="toggleDescription('sample',orderObj.order_no)" class="readMore">read less...</a>
                                </span>
                            </td>
                            <td data-title="order status name" class="ng-binding[[orderObj.customer_status_class]]">
                                <span ng-if="orderObj.status == 12" title="[[orderObj.chd_hold_description]]" style="color:red">[[orderObj.order_status_name]]</span>
                                <span ng-if="orderObj.status < 12" title="[[orderObj.chd_hold_description]]" style="color:black">[[orderObj.order_status_name]]</span>
                            </td>
                            <td data-title="Created By" class="ng-binding[[orderObj.customer_status_class]]">[[orderObj.createdByName]]</td>
                            <td class="width10">

                                <!--View As Option-->
                                <div class="report_btn_div" ng-if="{{defined('VIEW') && VIEW}}">
                                    <span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
                                        <button type="button" ng-click="funViewOrdersStatistics(orderObj.order_id)" title="View Order Statistics" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-area-chart" aria-hidden="true"></i></button>
                                    </span>
                                    <span ng-if="{{defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}">
                                        <button type="button" ng-click="funViewOrder(orderObj.order_id)" title="View Order" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    </span>
                                    <span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
                                        <button type="button" ng-click="funViewOrderLog(orderObj.order_id,orderObj.order_no,divisionID)" title="View Order Log" class="btn btn-primary btn-sm report_btn_span"> <i class="fa fa-line-chart" aria-hidden="true"></i></button>
                                    </span>
                                </div>
                                <!--View As Option-->

                                <!--File Upload As Option-->
                                <div class="report_btn_div" ng-if="{{defined('VIEW') && VIEW}}">
                                    <div ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
                                        <span ng-if="orderObj.oltd_olsd_id">
                                            <button type="button" ng-click="funOpenFileUploadWindow(orderObj.order_id,orderObj.order_no)" title="Open Upload Window" class="btn btn-success btn-sm report_btn_span"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                        </span>
                                        <span ng-if="!orderObj.oltd_olsd_id">
                                            <button type="button" ng-click="funOpenFileUploadWindow(orderObj.order_id,orderObj.order_no)" title="Open Upload Window" class="btn btn-danger btn-sm report_btn_span"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <div ng-if="{{defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}">
                                        <span ng-if="orderObj.oltd_olsd_id">
                                            <button type="button" ng-click="funOpenFileUploadWindow(orderObj.order_id,orderObj.order_no)" title="Open Upload Window" class="btn btn-success btn-sm report_btn_span"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                        </span>
                                        <span ng-if="!orderObj.oltd_olsd_id">
                                            <button type="button" ng-click="funOpenFileUploadWindow(orderObj.order_id,orderObj.order_no)" title="Open Upload Window" class="btn btn-danger btn-sm report_btn_span"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <div ng-if="{{defined('IS_CRM') && IS_CRM}}">
                                        <span ng-if="orderObj.oltd_olsd_id">
                                            <button type="button" ng-click="funOpenFileUploadWindow(orderObj.order_id,orderObj.order_no)" title="Open Upload Window" class="btn btn-success btn-sm report_btn_span"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <!--/File Upload As Option-->

                                <!--Unhold As Option-->
                                <div class="report_btn_div" ng-if="{{defined('VIEW') && VIEW}}">
                                    <div ng-if="orderObj.status == 12 && !orderObj.invoice_generated_id">
                                        <span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
                                            <button type="button" ng-click="funConfirmMessage(orderObj.order_id,'Are you sure to unhold this orders ?','unhold')" title="Unhold Order" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-pause" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <!--Unhold As Option-->

                                <!--Edit As Option-->
                                <div class="report_btn_div" ng-if="orderObj.status != '10' && orderObj.status != '12'">
                                    <div class="report_btn_div" ng-if="{{defined('EDIT') && EDIT && defined('IS_ADMIN') && IS_ADMIN}}">
                                        <span ng-if="orderObj.status"><button type="button" ng-click="funEditOrder(orderObj.order_id)" title="Edit Order" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></span>
                                    </div>
                                    <div class="report_btn_div" ng-if="{{defined('EDIT') && EDIT && defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}">
                                        <span ng-if="orderObj.status == 12 || orderObj.status == 1"><button type="button" ng-click="funEditOrder(orderObj.order_id)" title="Edit Order" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></span>
                                    </div>
                                </div>
                                <!--/Edit As Option-->

                                <!--Save As Option-->
                                <div class="report_btn_div hidden" ng-if="{{defined('EDIT') && EDIT && defined('IS_ADMIN') && IS_ADMIN}}">
                                    <span ng-if="orderObj.status"><button type="button" ng-click="funOpenSaveAsOrder(orderObj.order_id)" title="Save As Another Order" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span>
                                </div>
                                <div class="report_btn_div hidden" ng-if="{{defined('EDIT') && EDIT && defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}">
                                    <span ng-if="orderObj.status"><button type="button" ng-click="funOpenSaveAsOrder(orderObj.order_id)" title="Save As Another Order" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span>
                                </div>
                                <!--/Save As Option-->

                                <!--Cancel As Option-->
                                <div class="report_btn_div" ng-if="{{defined('VIEW') && VIEW}}">
                                    <span ng-if="orderObj.status != '10' && !orderObj.invoice_generated_id"><button type="button" ng-click="funOpenOrderCancelPopup('orderCancellationInputPopupWindow',orderObj.order_no,orderObj.order_id)" title="Cancel Order" class="btn btn-danger btn-sm report_btn_span"><i class="fa fa-ban" aria-hidden="true"></i></button></span>
                                    <span ng-if="orderObj.status == '10' && !orderObj.invoice_generated_id"><button type="button" ng-click="funGetCancelledOrderDetail(orderObj.order_id)" title="Cancel Order Detail" class="btn btn-success btn-sm report_btn_span"><i class="fa fa-ban" aria-hidden="true"></i></button></span>
                                </div>
                                <!--/Cancel As Option-->

                                <!--Delete As Option-->
                                <div class="report_btn_div" ng-if="{{defined('DELETE') && DELETE}} && orderObj.status != '10' && orderObj.status != '12' && !orderObj.invoice_generated_id">
                                    <button type="button" ng-click="funConfirmDeleteMessage(orderObj.order_id,divisionID)" title="Delete Order" class="btn btn-danger btn-sm report_btn_span"> <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                </div>
                                <!--/Delete As Option-->
                            </td>
                        </tr>
                        <tr ng-if="!orderData.length">
                            <td colspan="9">No order found.</td>
                        </tr>
                    </tbody>
                    <tfoot ng-if="orderData.length">
                        <tr>
                            <td colspan="9">
                                <div class="box-footer clearfix">
                                    <dir-pagination-controls></dir-pagination-controls>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!--/Listing of Orders-->
        </div>
    </form>
    <!--/erpFilterOrderForm-->

    <!--Upload Purchase Order Popup Window-->
    @include('sales.order_master.uploadOrderPurchaseOrderCsv')
    <!--/Upload Purchase Order Popup Window-->

</div>
