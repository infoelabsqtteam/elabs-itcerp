<div class="row" ng-hide="IsViewSaveAsAlternativeHidden" id="erp_view_alternative_form_div">
    <!-- Modal -->
    <div id="viewSaveAsAlternativeModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">        
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Alternative Method</h4>
                </div>
                <div class="modal-body">                   
                    <!--Test to perform-->
                    <div class="row col-xs-12 form-group">
                        <table class="col-sm-12 table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>					
                                    <th>
                                        <label class="sortlabel" ng-click="sortByAlternative('test_parameter_name')">Test Parameter Name </label>
                                        <span class="sortorder" ng-show="alterPropertyName === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>
                                    </th>
                                    <th>
                                        <label class="sortlabel" ng-click="sortByAlternative('equipment_name')">Equipment Name </label>
                                        <span class="sortorder" ng-show="alterPropertyName === 'equipment_name'" ng-class="{reverse:reverse}"></span>
                                    </th>
                                    <th>
                                        <label class="sortlabel" ng-click="sortByAlternative('method_name')">Method Name </label>
                                        <span class="sortorder" ng-show="alterPropertyName === 'method_name'" ng-class="{reverse:reverse}"></span>
                                    </th>
                                    <th>
                                        <label class="sortlabel" ng-click="sortByAlternative('standard_value_from')">Standard Value From </label>
                                        <span class="sortorder" ng-show="alterPropertyName === 'standard_value_from'" ng-class="{reverse:reverse}"></span>
                                    </th>					
                                    <th>
                                        <label class="sortlabel" ng-click="sortByAlternative('standard_value_to')">Standard Value To </label>
                                        <span class="sortorder" ng-show="alterPropertyName === 'standard_value_to'" ng-class="{reverse:reverse}"></span>
                                    </th>					
                                    <th>
                                        <label class="sortlabel" ng-click="sortByAlternative('time_taken')">Time Taken</label>
                                        <span class="sortorder" ng-show="alterPropertyName === 'time_taken'" ng-class="{reverse:reverse}"></span>
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="alterTestProductStandardParamentersObj in saveAsAlterTestProductStandardParamenters track by $index | orderBy:alterPropertyName:reverse">
                                    <td data-title="Test Parameter Name" ng-bind-html="alterTestProductStandardParamentersObj.test_parameter_name"></td>
                                    <td data-title="Equipment Name">[[alterTestProductStandardParamentersObj.equipment_name]]</td>
                                    <td data-title="Method Name">[[alterTestProductStandardParamentersObj.method_name]]</td>
                                    <td data-title="Standard Value From">[[alterTestProductStandardParamentersObj.standard_value_from]]</td>
                                    <td data-title="Standard Value To">[[alterTestProductStandardParamentersObj.standard_value_to]]</td>
                                    <td data-title="time taken">
					<span>[[alterTestProductStandardParamentersObj.time_taken_days ? alterTestProductStandardParamentersObj.time_taken_days+' Days' : '']]</span>
					<span>[[alterTestProductStandardParamentersObj.time_taken_mins ? alterTestProductStandardParamentersObj.time_taken_mins+' Mins' : '']]</span>
				    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" ng-click="selectSaveAsAlternativeTestParameterRow([[alterTestProductStandardParamentersObj.product_test_param_altern_method_id]],[[alterTestProductStandardParamentersObj.product_test_dtl_id]])">Select</button>
                                    </td>
                                </tr>
                                <tr ng-show="!saveAsAlterTestProductStandardParamenters.length"><td>No alternative found.</td></tr>
                            </tbody>
                        </table>      
                    </div>
                    <!--/Test to perform-->                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- Modal content-->
        </div>
    </div>
    <!-- Modal -->    
</div>