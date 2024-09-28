<div class="row" ng-hide="IsViewReportList">

    <!--/form-->
    <form class="form-inline" method="POST" role="form" id="erpFilterReportForm" name="erpFilterReportForm" action="{{ url('sales/reports/generate-branch-wise-report-pdf') }}">

        <!--header-->
        <div class="row header">
            <div role="new" class="navbar-form navbar-left">
                <span class="pull-left"><strong id="form_title" title="Refresh" ng-click="funRefreshReportByStatus()"><span>Total</span><span ng-if="getBranchWiseReports.length">([[getBranchWiseReports.length]])</span></strong></span>
            </div>
            <div class="col-sm-9 summary" ng-if="summaryStatistics">
                <ul>
                    <li>Pendency : [</li>
                    <li ng-repeat="(key, value) in summaryStatistics">[[key]] : [[value]]</li>
                    <li>]</li>
                </ul>
            </div>
            <div role="new" class="navbar-form navbar-right">
                <div class="searchbox">
                    <input type="text" ng-keypress="funEnterPressHandler($event)" placeholder="Search" ng-model="filterReport.keyword" ng-change="funSearchReportByStatus(filterReport.keyword)" name="keyword" class="form-control ng-pristine ng-untouched ng-valid autoFocus">
                </div>
            </div>
        </div>
        <!--header-->

        <!--listing record-->
        <div id="no-more-tables">

            <!--Filter Action-->
            @include('sales.reports.listFilter')
            <!--/Filter Action-->

            <!--List Content-->
            @include('sales.reports.listContent')
            <!--/List Content-->

        </div>
        <!--listing record-->
    </form>
    <!--/form-->

    <!--dispatch Order Details Popup Window-->
    @include('sales.reports.dispatchOrderWithListPopupWindow')
    <!--/dispatch Order Popup Window-->

    <!--Section Incharge Order Details Popup Window-->
    @include('sales.reports.sectionInchargeOrderDetailPopupWindow')
    <!--Section Incharge Order Details Popup Window-->

    <!--Abbreviation-->
    <div class="row mT30" ng-init="funGetSamplePriorityList();" ng-if="samplePriorityList && getBranchWiseReports.length">
        <table border="1" class="col-sm-3 table-striped table-condensed cf text-center">
            <tbody>
                <tr>
                    <td class="font13"><strong>Abbreviation</strong></td>
                    <td class="font11" ng-repeat="samplePriorityObj in samplePriorityList" style="background:[[samplePriorityObj.sample_priority_color_code]]">[[samplePriorityObj.sample_priority_name]]</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!--/Abbreviation-->

</div>
