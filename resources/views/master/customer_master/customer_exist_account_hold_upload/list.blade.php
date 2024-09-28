<div class="row" ng-hide="listMasterFormBladeDiv">

    <!--search-->
    <div class="row header">
        <div role="new" class="navbar-form navbar-left">
            <div>
                <strong id="form_title"><span ng-click="funGetUploadMasterList()">Customer CRM Listing<span ng-if="customerExistAccountHoldUploadList.length"></span>([[customerExistAccountHoldUploadList.length]])</strong>
            </div>
        </div>
        <div role="new" class="navbar-form navbar-right">
            <div class="nav-custom custom-display">
                <input type="text" class="form-control" placeholder="Search" ng-model="searchMasterField"">
				<form class="form-inline" method="POST" role="form" name="erpMasterDownloadForm" action="{{url('master/customer/download-excel-cust-exist-account')}}"  target= "blank" novalidate>
					<button type="button" ng-disabled="!downloadContentList && customerExistAccountHoldUploadList.length" class="form-control btn btn-primary dropdown dropdown-toggle" data-toggle="dropdown" title="Download">Download</button>
					<div class="dropdown-menu" style="top:34px !important">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" ng-value="downloadContentList" name="downloadContentList" id="downloadContentList">
						<input type="submit" formtarget="_blank" name="generate_documents" value="Excel" class="dropdown-item">
					</div>
				</form>
            </div>
        </div>
        <!--/search-->
	</div>

	<!--display record-->
	<div class="row" id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('customer_code')">Customer Code</label>
						<span class="sortorder" ng-show="predicate === 'customer_code'" ng-class="{reverse:reverse}"></span>
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('customer_name')">Customer Name</label>
						<span class="sortorder" ng-show="predicate === 'customer_name'" ng-class="{reverse:reverse}"></span>
					</th>
					<th>
						<label  class="sortlabel" ng-click="sortBy('customer_city')">Customer City</label>
						<span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_city'" class="sortorder reverse ng-hide"></span>
					</th>
					<th>
						<label ng-click="sortBy('outstanding_amt')" class="sortlabel">Outstanding Amount</label>
						<span ng-class="{reverse:reverse}" ng-show="predicate === 'outstanding_amt'" class="sortorder reverse ng-hide"></span>
					</th>
					<th>
						<label ng-click="sortBy('above_outstanding_amt')" class="sortlabel"> &#62;90 Outstanding Amount</label>
						<span ng-class="{reverse:reverse}" ng-show="predicate === 'above_outstanding_amt'" class="sortorder reverse ng-hide"></span>
					</th>
					<th class="width8">
						<label ng-click="sortBy('customer_connected_status')" class="sortlabel">Status</label>
						<span ng-class="{reverse:reverse}" ng-show="predicate === 'customer_connected_status'" class="sortorder reverse ng-hide"></span>
					</th>
					<th class="width8">
						<label ng-click="sortBy('uploaded_by_name')" class="sortlabel">Uploaded By</label>
						<span ng-class="{reverse:reverse}" ng-show="predicate === 'uploaded_by_name'" class="sortorder reverse ng-hide"></span>
					</th>
					<th class="width8">
						<label ng-click="sortBy('updated_at')" class="sortlabel">Uploaded On</label>
						<span ng-class="{reverse:reverse}" ng-show="predicate === 'updated_at'" class="sortorder reverse ng-hide"></span>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="customerExistAccountHoldUploadObj in customerExistAccountHoldUploadList | filter:searchMasterField | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
					<td data-title="Customer Code">[[customerExistAccountHoldUploadObj.customer_code]]</td>
					<td data-title="Customer Name">[[customerExistAccountHoldUploadObj.customer_name]]</td>
					<td data-title="Customer City">[[customerExistAccountHoldUploadObj.customer_city]]</td>
					<td data-title="Outstanding Amount">[[customerExistAccountHoldUploadObj.outstanding_amt]]</td>
					<td data-title="Above Outstanding Amount">[[customerExistAccountHoldUploadObj.above_outstanding_amt]]</td>
					<td data-title="Status" ng-class="customerExistAccountHoldUploadObj.customer_connected_status ? 'text-green' : 'text-danger'">[[customerExistAccountHoldUploadObj.customer_connected_status ? 'Connected' : 'Not Found']]</td>
					<td data-title="Uploaded By">[[customerExistAccountHoldUploadObj.uploaded_by_name]]</td>
					<td data-title="Uploaded On">[[customerExistAccountHoldUploadObj.updated_at]]</td>
				</tr>
				<tr ng-if="!customerExistAccountHoldUploadList.length">
					<td colspan="8">No record found.</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="[[customerExistAccountHoldUploadList.length]]">
						<div class="box-footer clearfix">
							<dir-pagination-controls></dir-pagination-controls>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
