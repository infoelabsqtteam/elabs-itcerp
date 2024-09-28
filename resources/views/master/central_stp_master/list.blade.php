<div class="row" ng-hide="isViewListDiv">
	<div class="header">
		<strong class="pull-left headerText" ng-click="funGetCentralContentListing()" title="Refresh">Central STPs Listing <span ng-if="centralContentList.length">([[centralContentList.length]])</span></strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
				<input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="filterCentralContent">
			</div>
		</div>
	</div>
	<div id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('cstp_no')">STP NO.</label>
						<span class="sortorder" ng-show="predicate === 'cctp_no'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cstp_customer_name')">Customer Name</label>
						<span class="sortorder" ng-show="predicate === 'cstp_customer_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cstp_customer_city_name_name')">Place</label>
						<span class="sortorder" ng-show="predicate === 'cstp_customer_city_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cstp_sample_name')">Sample Name</label>
						<span class="sortorder" ng-show="predicate === 'cstp_sample_name'" ng-class="{reverse:reverse}"></span>						
					</th>					
					<th>
						<label class="sortlabel" ng-click="sortBy('cstp_file_name')">STP File Name</label>
						<span class="sortorder" ng-show="predicate === 'cstp_file_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cstp_date')">STP Date & Time</label>
						<span class="sortorder" ng-show="predicate === 'cstp_date'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cstp_status')">STP Status</label>
						<span class="sortorder" ng-show="predicate === 'cstp_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">Action</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="centralContentObj in centralContentList| filter : filterCentralContent | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="STP No">[[centralContentObj.cstp_no]]</td>
					<td data-title="STP Customer Name">[[centralContentObj.cstp_customer_name]]</td>
					<td data-title="STP Customer City">[[centralContentObj.cstp_customer_city_name]]</td>
					<td data-title="STP Sample Name">[[centralContentObj.cstp_sample_name]]</td>
					<td data-title="STP File Name"><a href="{{url(STP_PATH)}}/[[centralContentObj.cstp_file_name]]" title="download" target="_blank" ng-bind="centralContentObj.cstp_file_name"></a></td>
					<td data-title="STP DATE">[[centralContentObj.cstp_date]]</td>
					<td data-title="Central STP Status"><span ng-if="centralContentObj.cstp_status == 1">Active</span><span ng-if="centralContentObj.cstp_status == 2">Inactive</span></td>
					<td data-title="Updated On">[[centralContentObj.updated_at]]</td>
					<td class="width10">
						@if(defined('DELETE') && DELETE)	
							<button title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(centralContentObj.cstp_id)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						@endif
					</td>
				</tr>
				<tr ng-if="!centralContentList.length" class="noRecord"><td colspan="9">No Record Found!</td></tr>
			</tbody>
			<tfoot>
				<tr ng-if="centralContentList.length">
					<td colspan="9"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>