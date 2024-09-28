<div class="row" ng-hide="isViewListDiv">
	<div class="header">
		<strong class="pull-left headerText" ng-click="funGetCentralContentListing()" title="Refresh">Central POs Listing <span ng-if="centralContentList.length">([[centralContentList.length]])</span></strong>	
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
						<label class="sortlabel" ng-click="sortBy('cpo_no')">PO NO.</label>
						<span class="sortorder" ng-show="predicate === 'cctp_no'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cpo_customer_name')">Customer Name</label>
						<span class="sortorder" ng-show="predicate === 'cpo_customer_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cpo_customer_city_name_name')">Place</label>
						<span class="sortorder" ng-show="predicate === 'cpo_customer_city_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cpo_sample_name')">Sample Name</label>
						<span class="sortorder" ng-show="predicate === 'cpo_sample_name'" ng-class="{reverse:reverse}"></span>						
					</th>					
					<th>
						<label class="sortlabel" ng-click="sortBy('cpo_file_name')">PO File Name</label>
						<span class="sortorder" ng-show="predicate === 'cpo_file_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cpo_date')">PO Date & Time</label>
						<span class="sortorder" ng-show="predicate === 'cpo_date'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cpo_amount')">PO Amount</label>
						<span class="sortorder" ng-show="predicate === 'cpo_amount'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('cpo_status')">PO Status</label>
						<span class="sortorder" ng-show="predicate === 'cpo_status'" ng-class="{reverse:reverse}"></span>						
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
					<td data-title="PO No">[[centralContentObj.cpo_no]]</td>
					<td data-title="PO Customer Name">[[centralContentObj.cpo_customer_name]]</td>
					<td data-title="PO Customer City">[[centralContentObj.cpo_customer_city_name]]</td>
					<td data-title="PO Sample Name">[[centralContentObj.cpo_sample_name]]</td>
					<td data-title="PO File Name"><a href="{{url(PO_PATH)}}/[[centralContentObj.cpo_file_name]]" title="download" target="_blank" ng-bind="centralContentObj.cpo_file_name"></a></td>
					<td data-title="PO DATE">[[centralContentObj.cpo_date]]</td>
					<td data-title="PO Amount">[[centralContentObj.cpo_amount]]</td>
					<td data-title="Central PO Status"><span ng-if="centralContentObj.cpo_status == 1">Active</span><span ng-if="centralContentObj.cpo_status == 2">Inactive</span></td>
					<td data-title="Updated On">[[centralContentObj.updated_at]]</td>
					<td class="width10">
						@if(defined('DELETE') && DELETE)	
							<button title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(centralContentObj.cpo_id)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
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