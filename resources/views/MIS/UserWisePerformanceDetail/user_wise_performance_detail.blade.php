<div class="row" ng-if="MISReportDivName == 'UWPD007'">       
    
    <div class="col-xs-12 report-header">     
        <div class="col-xs-6 col-sm-6 text-left"><strong id="form_title">User Wise Performance Report<span ng-if="MISReportData.tableBody.length">([[MISReportData.tableBody.length]])</span></strong></div>
        <div class="col-xs-6 col-sm-6 text-right" ng-if="MISReportData.extraHeading"><strong id="form_title">[[MISReportData.extraHeading]]</strong></div>
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
                            <td ng-repeat="(key, value) in MISReportTdData" data-title="[[key]]" class="ng-binding">
                                <span ng-if="key == 'test_parameter_name'" ng-bind-html="value"></span>
                                <span ng-if="key != 'test_parameter_name'" ng-bind="value"></span>
                            </td>
                        </tr>
                        <tr ng-repeat="MISReportTrFootObj in MISReportData.tablefoot">
                            <td ng-repeat="MISReportFootTdObj in MISReportTrFootObj" data-title="[[MISReportFootTdObj]]" class="ng-binding capitalizeAll"><strong>[[MISReportFootTdObj]]</strong></td>
                        </tr>
                        <tr ng-if="!MISReportData.tableBody.length"><td colspan="8">No order found.</td></tr>
                    </tbody>
                    <tfoot ng-if="MISReportData.tableBody.length">
                        <tr>
                            <td colspan="[[MISReportData.tableHead.length]]">
                                <div class="box-footer clearfix text-center">
                                    <dir-pagination-controls></dir-pagination-controls>
                                </div>
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