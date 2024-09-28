<div class="row" ng-if="MISReportDivName == 'SLS008'">       
    
    <div class="col-xs-12 report-header">
        <div class="pull-left"><strong id="form_title">Sample Log Status Report<span ng-if="MISReportData.tableBody.length">([[MISReportData.tableBody.length]])</span></strong></div>
    </div>
        <div class="panel panel-default mT20 custom-rt-scroll">
            <div class="panel-body">
                <div id="no-more-tables" class="row col-xs-12">
                    <table class="col-sm-12 table-striped table-condensed cf">
                        <thead class="cf" ng-if="MISReportData.tableHead.length">
                            <tr>
                                <th ng-if="$index == 0" class="text-left" ng-repeat="tableHeadName in MISReportData.tableHead track by $index">
                                    <label ng-click="sortBy('[[tableHeadName]]')" class="sortlabel capitalizeAll">[[tableHeadName | removeUnderscores]]</label>
                                    <span ng-class="{reverse:reverse}" ng-show="predicate === '[[tableHeadName]]'" class="sortorder reverse ng-hide"></span>
                                </th>
                                <th ng-if="$index > 0" class="text-left" ng-repeat="tableHeadName in MISReportData.tableHead track by $index">
                                    <label ng-click="sortBy('[[tableHeadName]]')" class="sortlabel capitalizeAll">[[tableHeadName | removeUnderscores]]</label>
                                    <span ng-class="{reverse:reverse}" ng-show="predicate === '[[tableHeadName]]'" class="sortorder reverse ng-hide"></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr dir-paginate="MISReportTdData in MISReportData.tableBody | filter:filterOrders | itemsPerPage: 300 | orderBy:predicate:reverse">
                                <td ng-if="$index == 0" ng-repeat="MISReportTdObj in MISReportTdData track by $index" data-title="[[MISReportTdObj]]" class="ng-binding">[[MISReportTdObj]]</td>
                                <td ng-if="$index > 0" class="text-left" ng-repeat="(key, MISReportTdObj) in MISReportTdData track by $index" data-title="[[MISReportTdObj]]" class="ng-binding">
                                    <span ng-if="$index == 1">[[MISReportTdObj]]</span>
                                    <span ng-if="$index > 1 && key!='total'">
                                        <a href="javascript:;" ng-if ="MISReportTdObj.split('|')[0] !=0" ng-click="funShowSampleStatusLog(MISReportTdObj.split('|')[1])">
                                            <span  ng-bind="MISReportTdObj.split('|')[0] ? MISReportTdObj.split('|')[0] : MISReportTdObj "></span>
                                        </a>
                                        <span ng-if="MISReportTdObj.split('|')[0] ==0" ng-bind="MISReportTdObj.split('|')[0] ? MISReportTdObj.split('|')[0] : MISReportTdObj "></span>
                                    </span>
                                    <span ng-if="key=='total'">[[MISReportTdObj]]</span>  
                                </td>
                            </tr>                        
                            <tr ng-if="!MISReportData.tableBody.length"><td colspan="8">No order found.</td></tr>
                        </tbody>
                        <tfoot ng-if="MISReportData.tableBody.length">
                            <tr>
                                <td colspan="[[MISReportData.tableHead.length]]">
                                    <div class="box-footer clearfix text-center"><dir-pagination-controls></dir-pagination-controls></div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>	  
                </div>        
            </div>
        </div>
               
    <div class="col-xs-12 text-right"> 
        <div style="margin: -5px; padding-right: 9px;">
            <button ng-click="closeButton();" class="btn btn-primary btn-sm">Close</button>
        </div>
    </div>
    <!--Sample Log Status div-->
    @include('MIS.sampleLogStatus.view_sample_log_status_detail')
    <!--/Sample Log Status div-->
</div>    