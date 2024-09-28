<div class="row" ng-if="MISReportDivName == 'TAT006'">       
    
    <div class="col-xs-12 report-header">
        <div class="pull-left"><strong id="form_title">Tat Report<span ng-if="MISReportData.tableBody.length">([[MISReportData.tableBody.length]])</span></strong></div>
    </div>
    
    <div class="panel panel-default mT20">
        <div class="panel-body">
            <!--display record--> 
            <div id="no-more-tables" class="row custom-rt-scroll">
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
                        <tr dir-paginate="MISReportTdData in MISReportData.tableBody | filter:filterOrders | itemsPerPage: 50 | orderBy:predicate:reverse">
                            <td ng-repeat="MISReportTdObj in MISReportTdData" data-title="[[MISReportTdObj]]" class="ng-binding">[[MISReportTdObj]]</td>
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
            <!--display record-->            
        </div>
        <div class="col-xs-12 text-right mT20"> 
            <div style="margin: -5px; padding-right: 9px;">
                <button ng-click="closeButton();" class="btn btn-primary btn-sm">Close</button>
            </div>
        </div>
    </div>
</div>    