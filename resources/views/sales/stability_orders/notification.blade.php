<!-- Modal -->
<div id="stability_order_notification_popup" data-backdrop="static" data-keyboard="false" class="modal fade" role="dialog">
    <div class="modal-dialog width-lg">	
        <div class="modal-content" ng-init="funGetStabilityOrderNotificationList();">
                
            <!--modal-header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 title="Refresh" class="modal-title"><span ng-click="funGetStabilityOrderNotificationList();">Notification(<span ng-bind="stabilityOrderNotificationList.length"></span>)</span></h4>
            </div>
            <!--/modal-header-->
            
            <!--display Messge Div-->
            @include('includes.alertMessagePopup')
            <!--/display Messge Div-->
            
            <!--modal-body-->
            <div id="no-more-tables" class="modal-body custom-nr-scroll">
                <table border="1" class="col-sm-12 table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th>
                                <label class="capitalizeAll" ng-click="sortBy('stb_start_date')">S.No.</label>
                            </th>
                            <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_prototype_no')">Prototype No</label>
                                <span class="sortorder" ng-show="predicate === 'stb_prototype_no'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_prototype_date')">Prototype Date</label>
                                <span class="sortorder" ng-show="predicate === 'stb_prototype_date'" ng-class="{reverse:reverse}"></span>
                            </th>
			    <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_label_name')">Label name</label>
                                <span class="sortorder" ng-show="predicate === 'stb_label_name'" ng-class="{reverse:reverse}"></span>
                            </th>
			    <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_start_date')">Start Date</label>
                                <span class="sortorder" ng-show="predicate === 'stb_start_date'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_end_date')">End Date</label>
                                <span class="sortorder" ng-show="predicate === 'stb_end_date'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_sample_description_name')">Sample Name</label>
                                <span class="sortorder" ng-show="predicate === 'stb_sample_description_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('product_name')">Product Name</label>
                                <span class="sortorder" ng-show="predicate === 'product_name'" ng-class="{reverse:reverse}"></span>
                            </th>
			    <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('test_std_name')">Test Std Name</label>
                                <span class="sortorder" ng-show="predicate === 'test_std_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('test_code')">Product Tests</label>
                                <span class="sortorder" ng-show="predicate === 'test_code'" ng-class="{reverse:reverse}"></span>
                            </th>					
                            <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stability_condition_count')">No. of Orders</label>
                                <span class="sortorder" ng-show="predicate === 'stability_condition_count'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_order_book_status')">Booked Status</label>
                                <span class="sortorder" ng-show="predicate === 'stb_order_book_status'" ng-class="{reverse:reverse}"></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>                            
			<tr ng-repeat="stabilityOrderNotificationObj in stabilityOrderNotificationList track by $index">
			    <td data-title="S.No.">[[$index+1]]</td>
                            <td data-title="Prototype No.">[[stabilityOrderNotificationObj.stb_prototype_no]]</td>
                            <td data-title="Prototype Date">[[stabilityOrderNotificationObj.stb_prototype_date]]</td>
			    <td data-title="Label name">[[stabilityOrderNotificationObj.stb_label_name]]</td>
			    <td data-title="Start Date">[[stabilityOrderNotificationObj.stb_start_date]]</td>
			    <td data-title="End Date">[[stabilityOrderNotificationObj.stb_end_date]]</td>
                            <td data-title="Sample Name">[[stabilityOrderNotificationObj.stb_sample_description_name]]</td>
			    <td data-title="Product Name">[[stabilityOrderNotificationObj.product_name]]</td>
			    <td data-title="Testing Product">[[stabilityOrderNotificationObj.test_std_name]]</td>
			    <td data-title="Product Tests">[[stabilityOrderNotificationObj.test_code]]</td>
			    <td data-title="Stability Conditions" align="center">[[stabilityOrderNotificationObj.stability_condition_count]]</td>
			    <td data-title="Stability Status" align="center">[[stabilityOrderNotificationObj.stb_order_book_status | yesOrNo]]</td>
			</tr>
                        <tr><td data-title="Action" colspan="12" align="center"></td></tr>
                        <tr>
                            <td data-title="Action" colspan="11" align="center">
                                <a href="{{ url('/sales/stability-notifications') }}" title="View" class="btn btn-primary">View All</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->