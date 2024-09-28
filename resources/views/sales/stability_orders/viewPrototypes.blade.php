<div class="order_detail">
    <!--Added Stability Order Prototype List-->
    <div class="row mT20" ng-if="addedStabilityOrderPrototypeList.length">
        <div class="col-xs-12">
	    <div class="order-section mT10 text-center fontbd" title="Refresh">
		<span ng-click="funGetAddedStabilityOrderPrototypeList(stbOrderHdrID);">Added List(<span ng-bind="addedStabilityOrderPrototypeList.length"></span>)</span>
		<span class="pull-right"><button type="button" class="btn btn-default" ng-click="toggleButton('view-prototype-class','button-viewprototype-id')"><i id="button-viewprototype-id" class="fa fa-angle-double-up font12"></i></button></span>
	    </div>
            <div id="no-more-tables" class="mT10 col-xs-12 form-group fixed_table view-prototype-class" style="display:none;">
                <table border="1" class="col-sm-12 table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th>
                                <label class="capitalizeAll" ng-click="sortBy('stb_start_date')">S.No.</label>
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
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('product_name')">Testing Product</label>
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
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stability_condition_count')">Storage Condition Count</label>
                                <span class="sortorder" ng-show="predicate === 'stability_condition_count'" ng-class="{reverse:reverse}"></span>
                            </th>
			    <th>
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_order_book_status')">Booked Status</label>
                                <span class="sortorder" ng-show="predicate === 'stb_order_book_status'" ng-class="{reverse:reverse}"></span>
                            </th>
			    <th>
                                <label class="sortlabel capitalizeAll">Action</label>
                            </th>
                        </tr>
                    </thead>
                    <tbody>                            
			<tr ng-repeat="addedStabilityOrderPrototypeObj in addedStabilityOrderPrototypeList track by $index">
			    <td data-title="S.No.">[[$index+1]]</td>
			    <td data-title="Label name">[[addedStabilityOrderPrototypeObj.stb_label_name]]</td>
			    <td data-title="Start Date">[[addedStabilityOrderPrototypeObj.stb_start_date]]</td>
			    <td data-title="End Date">[[addedStabilityOrderPrototypeObj.stb_end_date]]</td>
			    <td data-title="Testing Product">[[addedStabilityOrderPrototypeObj.product_name]]</td>
			    <td data-title="Testing Product">[[addedStabilityOrderPrototypeObj.test_std_name]]</td>
			    <td data-title="Product Tests">[[addedStabilityOrderPrototypeObj.test_code]]</td>
			    <td data-title="Stability Conditions" align="center">[[addedStabilityOrderPrototypeObj.stability_condition_count]]</td>
			    <td data-title="Stability Status" align="center">[[addedStabilityOrderPrototypeObj.stb_order_book_status | yesOrNo]]</td>
			    <td data-title="Action">
				<!--View-->
				<div class="report_btn_div" ng-if="{{defined('VIEW') && VIEW && (defined('IS_ADMIN') && IS_ADMIN  || defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER)}}">
				    <span ng-if="!addedStabilityOrderPrototypeObj.stb_order_hdr_detail_status"><button type="button" ng-click="funEditPrototypeOfStabilityOrder(addedStabilityOrderPrototypeObj.stb_order_hdr_dtl_id,addedStabilityOrderPrototypeObj.stb_order_hdr_id)" title="Edit Prototype" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></span>
				    <span ng-if="addedStabilityOrderPrototypeObj.stb_order_hdr_detail_status"><button type="button" ng-click="funEditPrototypeOfStabilityOrder(addedStabilityOrderPrototypeObj.stb_order_hdr_dtl_id,addedStabilityOrderPrototypeObj.stb_order_hdr_id)" title="View Prototype" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-eye" aria-hidden="true"></i></button></span>
				</div>
				<!--View-->				
				<!--Delete-->
				<div class="report_btn_div" ng-if="{{defined('DELETE') && DELETE && defined('IS_ADMIN') && IS_ADMIN}}">
				    <span ng-if="([[$index+1]] == addedStabilityOrderPrototypeList.length) && (addedStabilityOrderPrototypeList.length != 1) && (addedStabilityOrderPrototypeObj.stb_order_hdr_detail_status == 0)"><button type="button" ng-click="funConfirmPrototypeDeleteMessage(addedStabilityOrderPrototypeObj.stb_order_hdr_dtl_id,addedStabilityOrderPrototypeObj.stb_order_hdr_id)" title="Delete Prototype" class="btn btn-danger btn-sm report_btn_span"><i class="fa fa-trash-o" aria-hidden="true"></i></button></span>
				</div>
				<!--/Delete-->
			    </td>
			</tr>											
		    </tbody>
		    <tfoot>
			<tr><td colspan="10"></td></tr>
			<tr>
			    <td colspan="8"></td>
			    <td valign="middle"><span class="pull-left" ng-if="updateStabilityOrder.stb_order_hdr_detail_status && bookedStabilityConditionList.length"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateStabilityTestFormatReportPopup">Download STF Report</button></span></td>
			    <td valign="middle"><span class="pull-left"><button type="button" ng-disabled="!updateStabilityOrder.valid_email_status" class="btn btn-primary" ng-click="funConfirmMailMessage(updateStabilityOrder.stb_order_hdr_id)">Send E-Mail</button></span></td>
			</tr>
		    </tfoot>
                </table>
            </div>
        </div>	    
    </div>
    <!--Added Stability Order Prototype List-->
</div>