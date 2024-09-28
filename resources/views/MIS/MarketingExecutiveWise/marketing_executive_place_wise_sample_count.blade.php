<div class="row" ng-if="MISReportDivName == 'MEWBPWSC005'">

    <div class="col-xs-12 report-header">
        <div class="pull-left"><strong id="form_title">Marketing Executive Wise-By Place Wise Sample Count<span ng-if="MISReportData.tableBody.length">([[MISReportData.tableBody.length]])</span></strong></div>
    </div>
                
    <div class="panel panel-default mT20 custom-rt-scroll">
        <div class="panel-body">
            <div id="no-more-tables" class="row col-xs-12">
                <table class="col-sm-12 table-striped table-condensed cf">
                    <thead class="cf" ng-if="MISReportData.tableHead.length">
                        <tr>
                            <th ng-repeat="tableHeadName in MISReportData.tableHead">
                                <label ng-click="sortBy('[[tableHeadName]]')" class="sortlabel capitalizeAll">[[tableHeadName | removeUnderscores]]</label>
                                <span ng-class="{reverse:reverse}" ng-show="predicate === '[[tableHeadName]]'" class="sortorder reverse ng-hide"></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr dir-paginate="MISReportTdData in MISReportData.tableBody | filter:filterOrders | itemsPerPage: 300 | orderBy:predicate:reverse">
                            <td ng-repeat="MISReportTdObj in MISReportTdData" data-title="[[MISReportTdObj]]" class="ng-binding">[[MISReportTdObj]]</td>
                        </tr>
                        <tr ng-repeat="MISReportTrFootObj in MISReportData.tablefoot">
                            <td ng-repeat="MISReportFootTdObj in MISReportTrFootObj" data-title="[[MISReportFootTdObj]]" class="ng-binding capitalizeAll"><strong>[[MISReportFootTdObj]]</strong></td>
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
</div>    