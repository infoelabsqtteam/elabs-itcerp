<!--table data-->
<div class="row tableRecord">
    <table class="col-sm-12 table-striped table-condensed cf">
        <thead class="cf">
            <tr>
                <th>
                    <label ng-click="sortBy('order_no')" class="sortlabel">Order No.</label>
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
                <th ng-if="{{defined('IS_ADMIN') || defined('IS_DISPATCHER') || defined('IS_CRM') }}">
                    <label ng-click="sortBy('customer_code')" class="sortlabel">Customer Code</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_code'" class="sortorder reverse ng-hide"></span>
                </th>
                <th ng-if="{{!defined('IS_TESTER')}}">
                    <label ng-click="sortBy('customer_name')" class="sortlabel">Customer Name</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_name'" class="sortorder reverse ng-hide"></span>
                </th>
                <th ng-if="{{!defined('IS_TESTER')}}">
                    <label ng-click="sortBy('city_name')" class="sortlabel">Place</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'city_name'" class="sortorder reverse ng-hide"></span>
                </th>
                <th ng-if="{{!defined('IS_TESTER')}}">
                    <label ng-click="sortBy('billing_type')" class="sortlabel">Billing Type</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'billing_type'" class="sortorder reverse ng-hide"></span>
                </th>
                <th>
                    <label ng-click="sortBy('order_date')" class="sortlabel">Order Date </label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_date'" class="sortorder reverse ng-hide"></span>
                </th>
                <th ng-if="{{!defined('IS_DISPATCHER') && !defined('IS_TESTER')}}">
                    <label ng-click="sortBy('expected_due_date')" class="sortlabel">Expected Due Date</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'expected_due_date'" class="sortorder reverse ng-hide"></span>
                </th>
                <th ng-if="{{defined('IS_ADMIN') || defined('IS_TESTER')}}">
                    <label ng-click="sortBy('order_dept_due_date')" class="sortlabel">Department Due Date</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_dept_due_date'" class="sortorder reverse ng-hide"></span>
                </th>
                <th>
                    <label ng-click="sortBy('nabl_no')" class="sortlabel">NABL No.</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'nabl_no'" class="sortorder reverse"></span>
                </th>
                <th>
                    <label ng-click="sortBy('report_no')" class="sortlabel">Report No.</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'report_no'" class="sortorder reverse"></span>
                </th>
                <th>
                    <label ng-click="sortBy('report_date')" class="sortlabel">Report Date </label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'report_date'" class="sortorder reverse ng-hide"></span>
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
                <th class="width8" ng-if="{{!defined('IS_DISPATCHER')}}">
                    <label ng-click="sortBy('remarks')" class="sortlabel">Remarks</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'remarks'" class="sortorder reverse ng-hide"></span>
                </th>
                <th class="width8" ng-if="{{(defined('IS_ADMIN') && IS_ADMIN) || (defined('IS_TESTER') && IS_TESTER)}}">
                    <label ng-click="sortBy('olsd_cstp_no')" class="sortlabel">STP No.</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'olsd_cstp_no'" class="sortorder reverse ng-hide"></span>
                </th>
                <th class="width8">
                    <label ng-click="sortBy('order_status_time')" class="sortlabel">Status Time</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_status_time'" class="sortorder reverse ng-hide"></span>
                </th>
                <th class="width8">
                    <label ng-click="sortBy('order_mail_status_text')" class="sortlabel">Mail Status</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_mail_status_text'" class="sortorder reverse ng-hide"></span>
                </th>
                <th class="width8">
                    <label ng-click="sortBy('order_status_name')" class="sortlabel">Status</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_status_name'" class="sortorder reverse ng-hide"></span>
                </th>
                <th class="width8" ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}">
                    <label ng-click="sortBy('dispatch_date')" class="sortlabel">Dispatched Date</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'dispatch_date'" class="sortorder reverse ng-hide"></span>
                </th>
                <th class="width10" ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}">
                    <label ng-click="sortBy('dispatch_by')" class="sortlabel">Dispatched By</label>
                    <span ng-class="{reverse:reverse}" ng-show="predicate === 'dispatch_by'" class="sortorder reverse ng-hide"></span>
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
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_order_no)" name="search_order_no" ng-model="filterReport.search_order_no" class="multiSearch form-control width80" placeholder="Order No"></td>
                <td class="width10" ng-if="{{$division_id}} == 0"></td>
                <td></td>
                <td ng-if="{{defined('IS_ADMIN') || defined('IS_DISPATCHER') || defined('IS_CRM') }}"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_customer_code)" name="search_customer_code" ng-model="filterReport.search_customer_code" class="multiSearch form-control width80" placeholder="Code"></td>
                <td ng-if="{{!defined('IS_TESTER')}}"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_customer_name)" name="search_customer_name" ng-model="filterReport.search_customer_name" class="multiSearch form-control width80" placeholder="Name"></td>
                <td ng-if="{{!defined('IS_TESTER')}}"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_customer_city)" name="search_customer_city" ng-model="filterReport.search_customer_city" class="multiSearch form-control" placeholder="Place"></td>
                <td ng-if="{{!defined('IS_TESTER')}}"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_billing_type)" name="search_billing_type" ng-model="filterReport.search_billing_type" class="multiSearch form-control width80" placeholder="Billing Type"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_order_date)" name="search_order_date" ng-model="filterReport.search_order_date" class="multiSearch form-control width80" placeholder="Order Date"></td>
                <td ng-if="{{!defined('IS_DISPATCHER') && !defined('IS_TESTER')}}"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.expected_due_date)" name="search_expected_due_date" ng-model="filterReport.expected_due_date" class="multiSearch form-control width80" placeholder="EDD"></td>
                <td ng-if="{{defined('IS_ADMIN') || defined('IS_TESTER')}}"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_dept_due_date)" name="search_dept_due_date" ng-model="filterReport.search_dept_due_date" class="multiSearch form-control width80" placeholder="DDD"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_nabl_no)" name="search_nabl_no" ng-model="filterReport.search_nabl_no" class="multiSearch form-control width80" placeholder="NABL No"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_report_no)" name="search_report_no" ng-model="filterReport.search_report_no" class="multiSearch form-control width80" placeholder="Report No"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_report_date)" name="search_report_date" ng-model="filterReport.search_report_date" class="multiSearch form-control width80" placeholder="Report Date"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_sample_description)" name="search_sample_description" ng-model="filterReport.search_sample_description" class="multiSearch form-control width80" placeholder="SD"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_batch_no)" name="search_batch_no" ng-model="filterReport.search_batch_no" class="multiSearch form-control width80" placeholder="Batch No"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_sample_priority_name)" name="search_sample_priority_name" ng-model="filterReport.search_sample_priority_name" class="multiSearch form-control width80" placeholder="SP"></td>
                <td ng-if="{{(defined('IS_ADMIN') && IS_ADMIN) || (defined('IS_TESTER') && IS_TESTER)}}"></td>
                <td ng-if="{{!defined('IS_DISPATCHER')}}"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_remarks)" name="search_remarks" ng-model="filterReport.search_remarks" class="multiSearch form-control width80" placeholder="Remarks"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_status_time)" name="search_status_time" ng-model="filterReport.search_status_time" class="multiSearch form-control width80" placeholder="Status Time"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_order_mail_status)" name="search_order_mail_status" ng-model="filterReport.search_order_mail_status" class="multiSearch form-control width80" placeholder="Mail Status"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_status)" name="search_status" ng-model="filterReport.search_status" class="multiSearch form-control width80" placeholder="Status"></td>
                <td ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_dispatch_date)" name="search_dispatch_date" ng-model="filterReport.search_dispatch_date" class="multiSearch form-control width80" placeholder="Dispatch date"></td>
                <td ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_dispatch_by)" name="search_dispatch_by" ng-model="filterReport.search_dispatch_by" class="multiSearch form-control width80" placeholder="Dispatch by"></td>
                <td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(filterReport.search_created_by)" name="search_created_by" ng-model="filterReport.search_created_by" class="multiSearch form-control " placeholder="Created By"></td>
                <td class="width10">
                    <button type="button" ng-click="refreshMultisearch(divisionID)" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                    <button type="button" ng-click="closeMultisearch();funRefreshReportByStatus();" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
                </td>
            </tr>
            <tr dir-paginate="getBranchWiseReportObj in getBranchWiseReports | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Report No" class="ng-binding">[[getBranchWiseReportObj.order_no]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{$division_id}} == 0" data-title="Division Name">[[getBranchWiseReportObj.division_name]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Prototype No" class="ng-binding">[[getBranchWiseReportObj.stb_prototype_no]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{defined('IS_ADMIN') || defined('IS_DISPATCHER') || defined('IS_CRM') }}" data-title="Customer Code" class="ng-binding">[[getBranchWiseReportObj.customer_code]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{!defined('IS_TESTER')}}" data-title="Customer Name" class="ng-binding">[[getBranchWiseReportObj.customer_name]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{!defined('IS_TESTER')}}" data-title="Customer city" class="ng-binding">[[getBranchWiseReportObj.customer_city]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{!defined('IS_TESTER')}}">[[getBranchWiseReportObj.billing_type]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Report Date" class="ng-binding">[[getBranchWiseReportObj.order_date]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{!defined('IS_DISPATCHER') && !defined('IS_TESTER')}}" data-title="Expected Due Date" class="ng-binding">[[getBranchWiseReportObj.expected_due_date]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{defined('IS_ADMIN') || defined('IS_TESTER')}}" data-title="Department Due Date" class="ng-binding">[[getBranchWiseReportObj.order_dept_due_date]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="NABL No" class="ng-binding">[[getBranchWiseReportObj.nabl_no ? getBranchWiseReportObj.nabl_no : '']]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Report No" class="ng-binding">[[getBranchWiseReportObj.report_no ? getBranchWiseReportObj.report_no : '']]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Report Date" class="ng-binding">[[getBranchWiseReportObj.report_date ? getBranchWiseReportObj.report_date : '']]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Sample Description" class="ng-binding">[[getBranchWiseReportObj.sample_description]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Batch No" class="ng-binding">[[getBranchWiseReportObj.batch_no]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="sample_priority_name" class="ng-binding">[[getBranchWiseReportObj.sample_priority_name ? getBranchWiseReportObj.sample_priority_name : '']]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Remarks" class="ng-binding" ng-if="{{!defined('IS_DISPATCHER')}}">
                    <span id="samplelimitedText-[[getBranchWiseReportObj.order_no]]">
                        [[ getBranchWiseReportObj.remarks ? getBranchWiseReportObj.remarks : '-' | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
                        <a href="javascript:;" ng-click="toggleDescription('sample',getBranchWiseReportObj.order_no)" ng-show="getBranchWiseReportObj.remarks.length > 150" class="readMore">read more...</a>
                    </span>
                    <span id="samplefullText-[[getBranchWiseReportObj.order_no]]" style="display:none;">
                        [[getBranchWiseReportObj.remarks ? getBranchWiseReportObj.remarks : '-']]
                        <a href="javascript:;" ng-click="toggleDescription('sample',getBranchWiseReportObj.order_no)" class="readMore">read less...</a>
                    </span>
                </td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{(defined('IS_ADMIN') && IS_ADMIN) || (defined('IS_TESTER') && IS_TESTER)}}">
                    <a ng-if="getBranchWiseReportObj.olsd_cstp_file_link" href="[[getBranchWiseReportObj.olsd_cstp_file_link]]" title="Connected STP Detail" target="_blank" ng-bind="getBranchWiseReportObj.olsd_cstp_no"></a>
                </td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="order Status Time" class="ng-binding">[[getBranchWiseReportObj.order_status_time]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Order Mail Status" class="ng-binding mail-[[getBranchWiseReportObj.order_mail_status_text | lowercase]]">[[getBranchWiseReportObj.order_mail_status_text]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="order status name" class="ng-binding">
                    <span ng-if="getBranchWiseReportObj.order_status == '3' || getBranchWiseReportObj.order_status == '4'" style="color:[[getBranchWiseReportObj.color_code]];"><a href="javascript:;" title="View Incharges" ng-click="funViewAllocatedSectionIncharges(getBranchWiseReportObj.order_id,getBranchWiseReportObj.order_no);">[[getBranchWiseReportObj.order_status_name]]</a></span>
                    <span ng-if="getBranchWiseReportObj.order_status != '3' && getBranchWiseReportObj.order_status != '4'" style="color:[[getBranchWiseReportObj.color_code]];">[[getBranchWiseReportObj.order_status_name]]</span>
                </td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}" data-title="Dispatched Date" class="ng-binding">
                    <span ng-if="!getBranchWiseReportObj.dispatch_date">-</i></span>
                    <span ng-if="getBranchWiseReportObj.dispatch_date">[[getBranchWiseReportObj.dispatch_date]]</span>
                </td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}" data-title="Dispatch By" class="ng-binding">
                    <span ng-if="!getBranchWiseReportObj.dispatch_by">-</i></span>
                    <span ng-if="getBranchWiseReportObj.dispatch_by">[[getBranchWiseReportObj.dispatch_by]]</span>
                </td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" data-title="Created By" class="ng-binding">[[getBranchWiseReportObj.createdByName]]</td>
                <td style="background:[[getBranchWiseReportObj.sample_priority_color_code]]" class="width10">

                    <!--list Action-->
                    @include('sales.reports.listAction')
                    <!--/list Action-->

                </td>
            </tr>
            <tr ng-if="!getBranchWiseReports.length">
                <td colspan="20">No report found.</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td ng-if="getBranchWiseReports.length" colspan="[[getBranchWiseReports.length]]">
                    <div class="box-footer clearfix">
                        <dir-pagination-controls></dir-pagination-controls>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<!--table data-->
