<div class="row" ng-hide="IsViewList" id="IsViewList">
	
	<!--Form : Send Mail Notification Of Stb Order-->
	<form name="erpSendMailNotificationOfStbOrderForm" id="erpSendMailNotificationOfStbOrderForm" role="form" method="POST" novalidate>
	
	    <!--search-->
	    <div class="header">        
		<div role="new" class="navbar-form navbar-left">            
		    <strong id="form_title" title="Refresh" ng-click="funGetStabilityOrderNotificationList();">Stability Notification<span ng-if="stabilityOrderNotificationList.length">(<span ng-bind="stabilityOrderNotificationList.length"></span>)</span></strong>
		</div>            
		<div role="new" class="navbar-form navbar-right">
		    <div class="nav-custom">
			<input type="text" placeholder="Search" ng-model="filterStabiltyNotification.keyword" name="keyword" class="form-control">
		    </div>
		</div>
	    </div>
	    <!--/search-->    
		
	    <!--Listing of Stability Notification-->
	    <div id="no-more-tables">
		<table border="1" class="col-sm-12 table-striped table-condensed cf">
		    <thead class="cf">
			<tr>
			    <th>
				<label class="capitalizeAll"><input type="checkbox" ng-click="funSelectNotificationAll();" class="select_all_notification_ckbox" id="select_all_stb_notification">&nbsp;All</label>
			    </th>
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
			    <th>
				<label class="sortlabel capitalizeAll">Action</label>
			    </th>
			</tr>
		    </thead>
		    <tbody>                            
			<tr ng-repeat="stabilityOrderNotificationObj in stabilityOrderNotificationList | filter:filterStabiltyNotification.keyword track by $index">
			    <td data-title="Select Checkbox"><input type="checkbox" class="select_all_notification_ckbox" id="stb_order_hdr_dtl_id_[[stabilityOrderNotificationObj.stb_order_hdr_dtl_id]]" ng-click="funCheckAtLeastOneNotificationIsChecked(stabilityOrderNotificationObj.stb_order_hdr_dtl_id)" name="stb_order_hdr_dtl_id[]" ng-model="stabilityOrderNotificationObj.stb_order_hdr_dtl_chk[[$index]][[stabilityOrderNotificationObj.stb_order_hdr_dtl_id]]" ng-value="stabilityOrderNotificationObj.stb_order_hdr_dtl_id"></td>
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
			    <td data-title="Action">
				<div class="report_btn_div" ng-if="{{defined('VIEW') && VIEW && (defined('IS_ADMIN') && IS_ADMIN  || defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER)}}">
				    <span ng-if="stabilityOrderNotificationObj.stb_order_book_status == 0"><button type="button" ng-disabled="stabilityOrderNotificationObj.create_order_button_status" ng-click="funGetStabilityOrderPrototypesDetail(stabilityOrderNotificationObj.stb_order_hdr_id,stabilityOrderNotificationObj.stb_order_hdr_dtl_id)" title="View Detail" class="btn btn-primary btn-sm report_btn_span"><i class="fa fa-eye" aria-hidden="true"></i></button></span>
				</div>
			    </td>
			</tr>
			<tr ng-if="!stabilityOrderNotificationList.length"><td colspan="14">No record found.</td></tr>
		    </tbody>
		    <tfoot>
			<tr ng-if="stabilityOrderNotificationList.length"><td colspan="14"></td></tr>
			<tr ng-if="stabilityOrderNotificationList.length">
			    <td colspan="14" align="right">
				<button type="button" ng-disabled="sendMailNotificationBtn" ng-click="funConfirmMailNotificationMessage()" title="Send Mail Notification" class="btn btn-primary btn-sm">Send Notification</button>
			    </td>
			</tr>
		    </tfoot>
		</table>
	    </div>
	    <!--/Listing of Stability Notification-->
	
	</form>
	<!-- /Form : Send Mail Notification Of Stb Order-->
</div>