<div class="row" ng-hide="IsViewInvoiceListing">

    <!--erpFilterInvoicesForm-->
    <form class="form-inline" method="POST" role="form" id="erpFilterInvoicesForm" name="erpFilterInvoicesForm" action="{{SITE_URL.('/sales/invoices/generate-branch-wise-invoices-pdf')}}" novalidate>

        <!--header-->
        <div class="row header">
            <div role="new" class="navbar-form navbar-left">
                <div><strong id="form_title" title="Refresh" ng-click="funRefreshInvoiceList()">Invoice Listing<span ng-if="divisionWiseInvoicesList.length">([[divisionWiseInvoicesList.length]])</span></strong>
                </div>
            </div>
            <div role="new" class="navbar-form navbar-right">
                <div class="custom-row">
                    <input type="text" placeholder="Search" name="keyword" ng-model="filterInvoices.keyword" ng-keypress="funEnterPressHandler($event)" ng-change="funFilterInvoiceSearch(filterInvoices.keyword)" class="form-control ng-pristine ng-untouched ng-valid autoFocus">
                    <span ng-if="{{defined('ADD') && ADD}}">
                        <button type="button" ng-click="navigateInvoiceForm(1,divisionID)" class="btn btn-primary" id="add_new_order" type="button">Generate Invoices</button>
                    </span>
                </div>
            </div>
        </div>
        <!--/header-->

        <!--Invoice Content-->
        <div class="row" id="no-more-tables">
            <!--Filter By-->
            <div class="panel panel-default filterForm" style="margin-top: 0px;">
                <div class="panel-body">
                    <div class="row">

                        <div ng-if="{{$division_id}} == 0" class="col-xs-2 form-group">
                            <label class="width100" for="from">Branch</label>
                            <select class="form-control width200" ng-model="filterInvoices.division_id" name="division_id" ng-options="division.name for division in divisionsCodeList track by division.id">
                                <option value="">All Branch</option>
                            </select>
                        </div>
                        <div ng-if="{{$division_id}} > 0">
                            <input type="hidden" value="{{$division_id}}" ng-model="filterInvoices.division_id" name="division_id">
                        </div>
                        <div class="col-xs-2 form-group">
                            <label class="width100" for="from">Filter From</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterInvoices.order_date_from" name="order_date_from" id="order_date_from" placeholder="Date From" />
                                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            </div>
                        </div>
                        <div class="col-xs-2 form-group">
                            <label class="width100" for="to">Filter To</label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" ng-keypress="funEnterPressHandler($event)" class="bgwhite form-control" ng-model="filterInvoices.order_date_to" name="order_date_to" id="order_date_to" placeholder="Date To" />
                                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            </div>
                        </div>
                        <div ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}" class="col-xs-1 form-group mT30">
                            <div class="checkbox">
                                <label><input title="Dispatch Pendency" type="checkbox" ng-model="filterInvoices.search_dispatch_pendency" name="search_dispatch_pendency" id="search_dispatch_pendency" value="1">&nbsp;Pendency</label>
                            </div>
                        </div>
                        <div ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}" class="col-xs-1 form-group mT30">
                            <div class="checkbox">
                                <label><input title="Dispatch Pendency" type="checkbox" ng-model="filterInvoices.search_dispatch_pendency" name="search_dispatch_pendency" id="search_dispatch_pendency" value="1">&nbsp;Pendency</label>
                            </div>
                        </div>
                        <div ng-if="{{(defined('IS_ADMIN') && IS_ADMIN) || (defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR) || (defined('IS_CRM') && IS_CRM)}}" class="col-xs-1 form-group mT30">
                            <div class="checkbox">
                                <label><input title="Cancelled Invoices" type="checkbox" ng-model="filterInvoices.is_view_cancelled_invoices" name="is_view_cancelled_invoices" id="is_view_cancelled_invoices" value="1">&nbsp;All Cancelled</label>
                            </div>
                        </div>
                        <div class="col-xs-2 form-group mT30">
                            <label for="submit">{{ csrf_field() }}</label>
                            <button type="button" title="Filter" ng-disabled="erpFilterInvoicesForm.$invalid" class="btn btn-primary" ng-click="funFilterInvoiceByStatus()"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <button type="button" ng-disabled="!divisionWiseInvoicesList.length" class="btn btn-primary dropdown dropdown-toggle" data-toggle="dropdown" title="Download"><i aria-hidden="true" class="fa fa-print"></i></button>
                            <div class="dropdown-menu">
                                <input type="submit" formtarget="_blank" name="generate_invoice_documents" value="Excel" class="dropdown-item">
                                <input type="submit" formtarget="_blank" name="generate_invoice_documents" value="PDF" class="dropdown-item">
                            </div>
                            <button type="button" title="Refresh" ng-disabled="erpFilterInvoicesForm.$invalid" class="btn btn-default" ng-click="funRefreshInvoiceList()"><i aria-hidden="true" class="fa fa-refresh"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Filter By-->

            <div class="col-sm-12" class="tableRecord">
                <table class="col-sm-12 table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th>
                                <label ng-click="sortBy('invoice_no')" class="sortlabel">Invoice No</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoice_no'" class="sortorder reverse"></span>
                            </th>
                            <th ng-if="{{$division_id}} == 0">
                                <label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
                                <span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>
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
                                <label ng-click="sortBy('city_name')" class="sortlabel">Related Orders</label>
                            </th>
                            <th>
                                <label ng-click="sortBy('invoice_date')" class="sortlabel">Invoice Date </label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoice_date'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th>
                                <label ng-click="sortBy('net_total_amount')" class="sortlabel">Total Amount(Rs)</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'net_total_amount'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th>
                                <label ng-click="sortBy('dispatch_date')" class="sortlabel">Dispatched Date</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'dispatch_date'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width10">
                                <label ng-click="sortBy('dispatch_by')" class="sortlabel">Dispatched By</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'dispatch_by'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width10">
                                <label ng-click="sortBy('invoice_mail_status_text')" class="sortlabel">Mail Status</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoice_mail_status_text'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width10">
                                <label ng-click="sortBy('ify_name')" class="sortlabel">Financial Year</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'ify_name'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width10">
                                <label ng-click="sortBy('invoiced_by')" class="sortlabel">Invoice By</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoiced_by'" class="sortorder reverse ng-hide"></span>
                            </th>
                            <th class="width10">
                                Action<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
                            </th>
                        </tr>
                    </thead>
                    <tbody total="[[divisionWiseInvoicesList.length]]">
                        <tr ng-hide="multiSearchTr">
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterInvoices.search_invoice_no)" name="search_invoice_no" ng-model="filterInvoices.search_invoice_no" class="multiSearch form-control" placeholder="Invoice No"></td>
                            <td class="width10" ng-if="{{$division_id}} == 0"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterInvoices.search_customer_name)" name="search_customer_name" ng-model="filterInvoices.search_customer_name" class="multiSearch form-control" placeholder="Customer Name"></td>
                            <td></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterInvoices.search_order_no)" name="search_order_no" ng-model="filterInvoices.search_order_no" class="multiSearch form-control" placeholder="Order Number/Report Number"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterInvoices.search_invoice_date)" name="search_invoice_date" ng-model="filterInvoices.search_invoice_date" class="multiSearch form-control" placeholder="Invoice Date"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterInvoices.search_net_total_amount)" name="search_net_total_amount" ng-model="filterInvoices.search_net_total_amount" class="multiSearch form-control" placeholder="Total Amount(Rs)"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterInvoices.search_dispatch_date)" name="search_dispatch_date" ng-model="filterInvoices.search_dispatch_date" class="multiSearch form-control" placeholder="Dispatch date"></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterInvoices.search_dispatch_by)" name="search_dispatch_by" ng-model="filterInvoices.search_dispatch_by" class="multiSearch form-control" placeholder="Dispatch by"></td>
                            <td></td>
                            <td></td>
                            <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterInvoices.search_created_by)" name="search_created_by" ng-model="filterInvoices.search_created_by" class="multiSearch form-control" placeholder="Created By"></td>
                            <td class="width10">
                                <button type="button" ng-click="refreshMultisearch(divisionID)" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                <button type="button" ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        <tr dir-paginate="divisionWiseInvoicesObj in divisionWiseInvoicesList | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                            <td data-title="invoice_no" class="ng-binding">[[divisionWiseInvoicesObj.invoice_no]]</td>
                            <td ng-if="{{$division_id}} == 0" data-title="Division Name">[[divisionWiseInvoicesObj.division_name]]</td>
                            <td data-title="customer_name" class="ng-binding"><a href="javascript:;" title="Customer Detail:" data-toggle="popover" data-content="[[divisionWiseInvoicesObj.customer_invoicing_detail]]" data-trigger="hover">[[divisionWiseInvoicesObj.customer_name]]</a></td>
                            <td data-title="customer_city" class="ng-binding">[[divisionWiseInvoicesObj.customer_city]]</td>
                            <td data-title="related orders" class="ng-binding"><a href="javascript:;" style="margin-left:20px" ng-click="funViewInvoiceOrderDetail(divisionWiseInvoicesObj.invoice_id,1)" title="View Related Orders"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                            <td data-title="invoice_date" class="ng-binding">[[divisionWiseInvoicesObj.invoice_date | date : 'dd-MM-yyyy']]</td>
                            <td data-title="net_total_amount" class="ng-binding">[[divisionWiseInvoicesObj.net_total_amount]]</td>
                            <td data-title="Dispatched Date" class="ng-binding"><span ng-if="!divisionWiseInvoicesObj.dispatch_date">-</i></span><span ng-if="divisionWiseInvoicesObj.dispatch_date">[[divisionWiseInvoicesObj.dispatch_date]]</span></td>
                            <td data-title="Dispatch By" class="ng-binding"><span ng-if="!divisionWiseInvoicesObj.dispatch_by">-</i></span><span ng-if="divisionWiseInvoicesObj.dispatch_by">[[divisionWiseInvoicesObj.dispatch_by]]</span></td>
                            <td data-title="Mail Status" class="ng-binding mail-[[divisionWiseInvoicesObj.invoice_mail_status_text | lowercase]]">[[divisionWiseInvoicesObj.invoice_mail_status_text]]</td>
                            <td data-title="Financial Year" class="ng-binding">[[divisionWiseInvoicesObj.ify_name]]</td>
                            <td data-title="Created By" class="ng-binding">[[divisionWiseInvoicesObj.invoiced_by]]</td>
                            <td class="width10">
                                <div ng-if="{{defined('VIEW') && VIEW}}">

                                    <!--Edit|Update Button-->
                                    <div ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}" class="report_btn_div">
                                        <element ng-if="divisionWiseInvoicesObj.invoice_status == '1'">
                                            <button type="button" ng-click="funModifyInvoice(divisionWiseInvoicesObj.invoice_id,1,1)" class="btn btn-success btn-sm report_btn_span" title="Edit Detail"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                        </element>
                                    </div>
                                    <!--/Edit|Update Button-->

                                    <!--View Button-->
                                    <div class="report_btn_div">
                                        <button type="button" ng-click="funSelectViewInvoiceType(divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no,1)" title="View Invoice" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    </div>
                                    <!--/View Button-->

                                    <!--Dispatch Button-->
                                    <div ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}" class="report_btn_div">
                                        <span ng-if="!divisionWiseInvoicesObj.dispatch_by && divisionWiseInvoicesObj.invoice_status == '1'">
                                            <button type="button" ng-click="funOpenDispatchInvoiceOrderPopup('dispatchInvoiceOrderListPopupWindow','show',divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" type="button" class="btn btn-primary report_btn_span" title="Click to dispatch this Order"><i class="fa fa-buysellads" aria-hidden="true"></i></button>
                                        </span>
                                        <span ng-if="divisionWiseInvoicesObj.dispatch_by && divisionWiseInvoicesObj.invoice_status == '1'">
                                            <button type="button" ng-click="funOpenDispatchInvoiceOrderPopup('dispatchInvoiceOrderListPopupWindow','show',divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" class="btn btn-success btn-sm report_btn_span" title="Dispatched Detail"><i class="fa fa-send" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <div ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}" class="report_btn_div">
                                        <span ng-if="!divisionWiseInvoicesObj.dispatch_by && divisionWiseInvoicesObj.invoice_status == '1'">
                                            <button type="button" ng-click="funOpenDispatchInvoiceOrderPopup('dispatchInvoiceOrderListPopupWindow','show',divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" type="button" class="btn btn-primary report_btn_span" title="Click to dispatch this Order"><i class="fa fa-buysellads" aria-hidden="true"></i></button>
                                        </span>
                                        <span ng-if="divisionWiseInvoicesObj.dispatch_by && divisionWiseInvoicesObj.invoice_status == '1'">
                                            <button type="button" type="button" ng-click="funOpenDispatchInvoiceOrderPopup('dispatchInvoiceOrderListPopupWindow','show',divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" class="btn btn-success btn-sm report_btn_span" title="Dispatched Detail"><i class="fa fa-send" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <!--/Dispatch Button-->

                                    <!--Cancellation/Regeneration Button-->
                                    <div class="report_btn_div" ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
                                        <span ng-if="divisionWiseInvoicesObj.invoice_status == '1'"><button type="button" ng-click="funOpenBootstrapPopup('invoiceCancellationInputPopupWindow','show',divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" title="Cancel Invoice" class="btn btn-danger btn-sm report_btn_span"><i class="fa fa-ban" aria-hidden="true"></i></button></span>
                                        <span ng-if="divisionWiseInvoicesObj.invoice_status == '2'"><button type="button" ng-click="funGetCancelledInvoiceDetail(divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" title="Cancel Detail" class="btn btn-success btn-sm report_btn_span"><i class="fa fa-ban" aria-hidden="true"></i></button></span>
                                        <span ng-if="divisionWiseInvoicesObj.dispatch_by && divisionWiseInvoicesObj.invoice_status == '2'"><button type="button" type="button" ng-click="funOpenDispatchInvoiceOrderPopup('dispatchInvoiceOrderListPopupWindow','show',divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" class="btn btn-success btn-sm report_btn_span" title="Dispatched Detail"><i class="fa fa-send" aria-hidden="true"></i></button></span>
                                    </div>
                                    <div class="report_btn_div" ng-if="{{defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR}}">
                                        <span ng-if="divisionWiseInvoicesObj.invoice_status == '1'"><button type="button" ng-click="funOpenBootstrapPopup('invoiceCancellationInputPopupWindow','show',divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" title="Cancel Invoice" class="btn btn-danger btn-sm report_btn_span"><i class="fa fa-ban" aria-hidden="true"></i></button></span>
                                        <span ng-if="divisionWiseInvoicesObj.invoice_status == '2'"><button type="button" ng-click="funGetCancelledInvoiceDetail(divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" title="Cancel Detail" class="btn btn-success btn-sm report_btn_span"><i class="fa fa-ban" aria-hidden="true"></i></button></span>
                                        <span ng-if="divisionWiseInvoicesObj.dispatch_by && divisionWiseInvoicesObj.invoice_status == '2'"><button type="button" type="button" ng-click="funOpenDispatchInvoiceOrderPopup('dispatchInvoiceOrderListPopupWindow','show',divisionWiseInvoicesObj.invoice_id,divisionWiseInvoicesObj.invoice_no)" class="btn btn-success btn-sm report_btn_span" title="Dispatched Detail"><i class="fa fa-send" aria-hidden="true"></i></button></span>
                                    </div>
                                    <!--Cancellation/Regeneration Button-->

                                    <!--Delete Button-->
                                    <div ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}" class="report_btn_div hidden">
                                        <span ng-if="{{defined('DELETE') && DELETE}}">
                                            <button type="button" ng-click="funConfirmMessage(divisionWiseInvoicesObj.invoice_id,divisionID,'Are you sure you want to delete this record?','listInvoice')" title="Delete Invoice" class="btn btn-danger btn-sm report_btn_span"> <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <!--/Delete Button-->

                                </div>
                            </td>
                        </tr>
                        <tr ng-if="!divisionWiseInvoicesList.length">
                            <td colspan="13">No Invoice found.</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="13">
                                <div class="box-footer clearfix">
                                    <dir-pagination-controls></dir-pagination-controls>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!--Invoice Content-->
    </form>
    <!--/erpFilterInvoicesForm-->

    <!--dispatch Order Popup Window-->
    @include('sales.invoices.dispatchInvoiceOrderListPopupWindow')
    <!--/dispatch Order Popup Window-->

    <!--Invoice Cancellation Popup Window details-->
    @include('sales.invoices.invoiceCancellationPopupWindow')
    <!--Invoice Cancellation Popup Window details-->

    <!--Invoice Cancellation Detail Popup Window details-->
    @include('sales.invoices.invoiceCancellationDetailPopupWindow')
    <!--Invoice Cancellation Detail Popup Window details-->

</div>