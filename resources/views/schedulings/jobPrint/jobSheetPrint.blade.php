<div class="row">
    <div class="hideDiv">
        <!--header-->
        <div class="row">
            <div class="header">
                <div class="navbar-form navbar-left">
                    <span class="pull-right"><strong id="form_title">Job Print </strong></span>
                </div>
                <div class="navbar-form navbar-right">
                    <div class="searchbox">
                        <input type="text" placeholder="Search" ng-model="filterSchedulingJobSheet" class="form-control ng-pristine ng-untouched ng-valid">
                    </div>
                </div>
            </div>
        </div>
        <!--header-->

        <!--Scheduling Filter Form-->
        <div class="row panel panel-default" style="margin-top: 0px;" class="hideDiv">
            <div class="panel-body">
                <form class="form-inline" method="GET" role="form" name="erpJobSheetPrintForm" novalidate>
                    <div class="row" ng-init="divisionID = {{ $division_id }}">
                        <!--branch-->
                        <div ng-if="{{ $division_id }} == 0" class="col-xs-2 form-group">
                            <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                            <select class="form-control width200" name="division_id" id="division_id" ng-model="jobSheetPrint.division_id" ng-change="funDateWiseOrders()" ng-required="true" ng-options="division.name for division in divisionsCodeList track by division.id">
                                <option value="">All Branch</option>
                            </select>
                        </div>
                        <div ng-if="{{ $division_id }} > 0">
                            <input type="hidden" id="division_id" name="division_id" value="{{ $division_id }}">
                        </div>
                        <!-- /branch-->

                        <!--Department-->
                        <div class="col-xs-2 form-group">
                            <label for="product_category_id">Department</label>
                            <select class="form-control" name="product_category_id" id="product_category_id" ng-change="funDateWiseOrders()" ng-model="jobSheetPrint.product_category_id" ng-options="item.name for item in parentCategoryList track by item.id">
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <!--/Department-->

                        <!--From-->
                        <div class="col-xs-2 form-group">
                            <label>Date of Booking<em class="asteriskRed">*</em></label>
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control" ng-model="jobSheetPrint.order_date" name="order_date" id="order_date" ng-change="funDateWiseOrders()" placeholder="Date From" ng-required="true" />
                                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            </div>
                        </div>
                        <!--/From-->

                        <!--Order Number-->
                        <div class="col-xs-2 form-group">
                            <label for="from">Select Order Number</label>
                            <select class="form-control col-xs-2" id="order_id" ng-change="funChangeOrderId()" name="order_id" ng-model="jobSheetPrint.order_number" ng-options="item.order_number for item in ordersList track by item.id" ng-required="false">
                                <option value="">All Order Number</option>
                            </select>
                        </div>
                        <!--/Order Number-->

                        <!--Search Button-->
                        <div class="col-xs-2 text-left mT25">
                            <label for="submit">{{ csrf_field() }}</label>
                            <button type="button" title="Filter" ng-disabled="erpJobSheetPrintForm.$invalid" class="btn btn-primary" ng-click="funGetOrderDetail()">Go</button>
                            <button type="button" title="Reset" class="btn btn-default" ng-click="backButton();">Reset</button>
                        </div>
                        <!--/Search Button-->
                    </div>
                </form>
            </div>
        </div>
        <!--/Scheduling Filter Form-->

        <!--display pending record-->
        <div class="row" id="no-more-tables" ng-if="orderData.length">
            <form class="form-inline" method="POST" role="form" name="erpSchedulingJobForm" novalidate>
                <table class="col-sm-12 table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('order_no')">Order No </label>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('customer_name')">Customer Name</label>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('customer_city')">Place</label>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('order_date')">Order Date </label>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('expected_due_date')">Expected Due Date</label>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('sample_description')">Sample Name</label>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('product_name')">Testing Product</label>
                            </th>
                            <th>
                                <label class="sortlabel" ng-click="sortBy('test_code')">Product Tests</label>
                            </th>
                            <th>
                                <label class="sortlabel">Print</label>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr dir-paginate="orderObj in orderData | filter:filterSchedulingJobSheet | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                            <td data-title="Order No" class="ng-binding">[[orderObj.order_no]]</td>
                            <td data-title="Customer Name" class="ng-binding">[[orderObj.customer_name | capitalize]]</td>
                            <td data-title="Customer Name" class="ng-binding">[[orderObj.customer_city | capitalize]]</td>
                            <td data-title="Order Date" class="ng-binding">[[orderObj.order_date]]</td>
                            <td data-title="Expected Due Date" class="ng-binding">
                                <div ng-if="orderObj.hasPermissionEditDddRddEdd == 0"><a title="Permission Denied">[[orderObj.expected_due_date]]</a></div>
                                <div ng-if="orderObj.hasPermissionEditDddRddEdd == 1"><a href="javascript:;" ng-click="funUpdateAndSendExpectedDueDatePopup(orderObj);">[[orderObj.expected_due_date]]</a></div>
                                <div ng-if="orderObj.hasPermissionEditDddRddEdd == 2">
                                    <a href="javascript:;" data-toggle="modal" id="eddModelEddMsg[[orderObj.order_id]]" data-target="#eddModelMsg[[orderObj.order_id]]" title="You cannot change the Expected Due Date">[[orderObj.expected_due_date]]</a>
                                    <div class="modal fade" id="eddModelMsg[[orderObj.order_id]]" role="dialog">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Notification</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>You cannot change the Expected Due Date</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td data-title="Sample Description" class="ng-binding">
                                <span id="samplelimitedText-[[orderObj.order_no]]">
                                    [[ orderObj.sample_description | limitTo :
                                    {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
                                    <a href="javascript:;" ng-click="toggleDescription('sample',orderObj.order_no)" ng-show="orderObj.sample_description.length > 150" class="readMore">read
                                        more...</a>
                                </span>
                                <span id="samplefullText-[[orderObj.order_no]]" style="display:none;">
                                    [[ orderObj.sample_description | capitalize]]
                                    <a href="javascript:;" ng-click="toggleDescription('sample',orderObj.order_no)" class="readMore">read less...</a>
                                </span>
                            </td>
                            <td data-title="Product Name" class="ng-binding">[[orderObj.product_name | capitalize]]</td>
                            <td data-title="Product Test Name" class="ng-binding"><a href="" ng-click="funViewOrder(orderObj.order_id)" title="Edit Order Parameters">[[orderObj.test_code | capitalizeAll]]</a></td>
                            <td class="width10">
                                <span>
                                    <button ng-click="funShowReport(orderObj.order_id,'jobSheetPrint',orderObj.order_no)" title="Print" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i></button>
                                </span>
                            </td>
                        </tr>
                        <tr ng-if="!orderData.length">
                            <td colspan="8">No order found.</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9">
                                <div class="box-footer clearfix">
                                    <dir-pagination-controls></dir-pagination-controls>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>

    <hr />

    <!--job Sheet Order Parameters-->
    @include('schedulings.jobPrint.jobSheetOrderParameters')
    <!--/job Sheet Order Parameters-->

    <!--view Job Report-->
    @include('schedulings.jobPrint.viewJobReport')
    <!--/view Job Report-->

    <!--Add Sample Parameters Popup-->
    @include('schedulings.jobPrint.addSampleParametersPopup')
    <!--/Add Sample Parameters Popup-->

    <!--Update Sample Parameters Popup-->
    @include('schedulings.jobPrint.updateOrderExpectedDueDatePopup')
    <!--/Add Sample Parameters Popup-->

</div>
