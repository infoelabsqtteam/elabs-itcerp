<div id="listCustomer" ng-hide="listCustomer">	
		
	<!--Display Heading-->
	<form class="form-inline" method="POST" role="form" name="erpHoldUnholdCustomerListForm" id="erpHoldUnholdCustomerListForm" novalidate>
		<label for="submit">{{ csrf_field() }}</label>
		<div class="row header">
			<div class="pull-left mT5">
				<strong class="headerText" style="width:300px;" ng-click="funGetHoldCustomers(limitFrom,limitTo)" title="Refresh">Hold Customers <span ng-if="custdata.length">([[custdata.length]])</span></strong>
			</div>	
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
					<div style="float: left;color:#000;position: relative;">
						<input type="text" class="form-control ng-pristine ng-untouched ng-valid" placeholder="Enter Customer Code" ng-change="getMultiSearch(searchCustomer.search_keyword)" ng-model="searchCustomer.search_keyword">
						<input class="form-control hidden" style="float: left;width:70px;" type="number" title="Skip" placeholder="Skip" name="limitFrom" ng-change="funGetLimitFromCustomers(searchCustomer.limitFrom)" ng-model="searchCustomer.limitFrom">
						<span class="pull-left form-control hidden">TO</span>
						<input class="form-control hidden" style="float: left;width:70px;" type="number" title="Take" placeholder="Take" name="limitTo" ng-change="funGetLimitToCustomers(searchCustomer.limitTo)" ng-model="searchCustomer.limitTo">
					</div>
				</div>
			</div>
		</div>
		
		<!--/Display Heading-->
		<br/>
		
		<!--display Listing of Customer-->
		<div class="row">
			<div id="no-more-tables">
				<!-- show error message -->
				<table class="col-sm-12 table-striped table-condensed cf">
					<thead class="cf">
						<tr>
							<td>#</td>
							<th>
								<label class="sortlabel" ng-click="sortBy('customer_code')">Customer Code  </label>
								<span class="sortorder" ng-show="predicate === 'customer_code'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('logic_customer_code')">Logic Customer Code  </label>
								<span class="sortorder" ng-show="predicate === 'logic_customer_code'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('customer_name')">Customer Name  </label>
								<span class="sortorder" ng-show="predicate === 'customer_name'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('customer_address')">Customer Address </label>
								<span class="sortorder" ng-show="predicate === 'customer_address'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('customer_email1')">Customer Email</label>
								<span class="sortorder" ng-show="predicate === 'customer_email1'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th class="width8">
								<label class="sortlabel" ng-click="sortBy('billing_type')">Billing Type</label>
								<span class="sortorder" ng-show="predicate === 'billing_type'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th class="width8">
								<label class="sortlabel" ng-click="sortBy('billing_type')">Customer Priority</label>
								<span class="sortorder" ng-show="predicate === 'customer_priority_id'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th class="width8">
								<label class="sortlabel" ng-click="sortBy('invoicing_type_id')">Invoicing Type</label>
								<span class="sortorder" ng-show="predicate === 'invoicing_type_id'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th class="width8">
								<label class="sortlabel" ng-click="sortBy('customer_status')">Customer Status</label>
								<span class="sortorder" ng-show="predicate === 'customer_status'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th class="width8">
								<label class="sortlabel" ng-click="sortBy('chd_hold_date')">Hold On</label>
								<span class="sortorder" ng-show="predicate === 'chd_hold_date'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th class="width8">
								<label class="sortlabel" ng-click="sortBy('chd_hold_by')">Hold By</label>
								<span class="sortorder" ng-show="predicate === 'chd_hold_by'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th class="width8">
								<label class="sortlabel" ng-click="sortBy('chd_hold_description')">Hold Description</label>
								<span class="sortorder" ng-show="predicate === 'chd_hold_description'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>Action<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary mL10"><i class="fa fa-filter"></i></button></th> 		
						</tr>
					</thead>
					<tbody>	
						<tr ng-hide="multiSearchTr">
							<td></td>
							<td><input type="text" ng-change="getMultiSearch(searchCustomer.search_customer_code)" name="search_customer_code" ng-model="searchCustomer.search_customer_code" class="multiSearch form-control " placeholder="Customer Code"></td>
							<td><input type="text" ng-change="getMultiSearch(searchCustomer.search_logic_customer_code)" name="search_logic_customer_code" ng-model="searchCustomer.search_logic_customer_code" class="multiSearch form-control " placeholder="Logic Customer Code"></td>
							<td><input type="text" ng-change="getMultiSearch(searchCustomer.search_customer_name)" name="search_customer_name" ng-model="searchCustomer.search_customer_name" class="multiSearch form-control " placeholder="Customer Name"></td>
							<td><input type="text" ng-change="getMultiSearch(searchCustomer.search_customer_address)" name="search_customer_address" ng-model="searchCustomer.search_customer_address" class="multiSearch form-control " placeholder="Customer Address"></td>
							<td><input type="text" ng-change="getMultiSearch(searchCustomer.search_customer_email)" name="search_customer_email" ng-model="searchCustomer.search_customer_email" class="multiSearch form-control " placeholder="Customer Email"></td>
							<td><input type="text" ng-change="getMultiSearch(searchCustomer.search_billing_type)" name="search_billing_type" ng-model="searchCustomer.search_billing_type" class="multiSearch form-control " placeholder="Billing Type"></td>
							<td></td>
							<td><input type="text" ng-change="getMultiSearch(searchCustomer.search_invoicing_type)" name="search_invoicing_type" ng-model="searchCustomer.search_invoicing_type" class="multiSearch form-control " placeholder="Invoicing Type"></td>	
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td class="width10">
								<button ng-click="refreshMultisearch()" type="button" class="btn btn-primary btn-sm" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></button>
								<button ng-click="closeMultisearch()" type="button" class="btn btn-default btn-sm" title="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
							</td>
						</tr>
						<tr ng-class="obj.chd_hold_status == '3' || obj.chd_hold_status == '4' ? 'fontbd' : '' " dir-paginate="obj in custdata | filter:filterCustomers | itemsPerPage: {{ defined('SUBPERPAGE') ? SUBPERPAGE : 15 }} | orderBy:predicate:reverse" >
							<td>
								<input
									style="width: 20px!important;"
									class="unHoldCheckBox"
									type="checkbox"
									ng-model="holdUnholdCustomers[[obj.customer_id]]"
									id="hold_unhold_[[obj.customer_id]]"
									name="customer_id[]"
									ng-value="obj.customer_id"
									ng-click="funCheckAtLeastOneIsChecked(obj.customer_id)">
							</td>
							<td data-title="Customer Code">[[obj.customer_code ? obj.customer_code :'-']]</td>
							<td data-title="Logic Customer Code">[[obj.logic_customer_code ? obj.logic_customer_code : '-']]</td>
							<td data-title="Customer Name">[[obj.customer_name ? obj.customer_name : '-']]</td>
							<td data-title="Customer Address">[[obj.customer_address ? obj.customer_address : '-' | capitalize]]</td>							
							<td data-title="Customer Email1">[[obj.customer_email ? obj.customer_email : '-']]</td>
							<td style="text-transform: uppercase;" data-title="Billing Type">[[obj.billingType ? obj.billingType : '-']]</td>
							<td style="text-transform: uppercase;" data-title="Customer Priority Type">[[obj.customerPriorityType ? obj.customerPriorityType : '-']]</td>
							<td style="text-transform: uppercase;" data-title="Invoicing Type">[[obj.invoicing_type ? obj.invoicing_type : '-']]</td>
							<td data-title="Customer Status">
								<span ng-if="obj.customer_status == '0'">Pending</span>
								<span ng-if="obj.customer_status == '1'">Active</span>
								<span ng-if="obj.customer_status == '2'">Inactive</span>
								<span ng-if="obj.customer_status == '3'">Hold</span>
							</td>
							<td data-title="chd_hold_date">[[obj.created_at ? obj.chd_hold_date : '' ]]</td>
							<td class="capitalize" data-title="chd_hold_by">[[obj.chd_hold_by ? obj.chd_hold_by : '' ]]</td>
							<td class="capitalize" data-title="chd_hold_description">[[obj.updated_at ? obj.chd_hold_description : '' ]]</td>
							<td class="width10" ng-if="{{ (defined('EDIT') && EDIT) || (defined('VIEW') && VIEW)}}">
								<a href="javascript:;" ng-if="{{defined('VIEW') && VIEW}} && obj.customer_status == 3" title="Unhold" class="btn btn-success btn-sm" ng-click='funConfirmUnholdMessage(obj.customer_id,1)'><i class="fa fa-play" aria-hidden="true"></i></a>
							</td>
						</tr>
						<tr ng-if="!custdata.length" class="noRecord"><td colspan="14">No Record Found!</td></tr>
					</tbody>
					<tfoot>
						<tr ng-if="custdata.length">
							<td colspan="13">
								<div class="box-footer clearfix">
									<dir-pagination-controls></dir-pagination-controls>
								</div>
							</td>
							<td colspan="1">
								<button type="button" ng-disabled="sendMailBtn" ng-click="funConfirmHoldMailMessage()" class="btn btn-primary btn-sm">Send Mail<span ng-if="checkboxesCount > 0">([[checkboxesCount ? checkboxesCount : '0']])</span></button>
							</td>
						</tr>
					</tfoot>
				</table>					  
			</div>
		</div>
		<!--/display Listing of Customer-->
	</form>
</div>