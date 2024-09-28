    <div class="row">
		<div class="header">
			<strong class="pull-left headerText" ng-click="getPaymentSources()" title="Refresh">Payment Sources <span ng-if="paymentSources.length">([[paymentSources.length]])</span></strong>	
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
				 <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="filterPayment">
				</div>
			</div>
		</div>
        <div id="no-more-tables">
            <table class="col-sm-12 table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('payment_source_name')">Payment Source Name  </label>
							<span class="sortorder" ng-show="predicate === 'payment_source_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('payment_source_description')">Payment Source Description  </label>
							<span class="sortorder" ng-show="predicate === 'payment_source_description'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('status')">Status </label>
							<span class="sortorder" ng-show="predicate === 'status'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('created_by')">Created By  </label>
							<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th  class="width10">
							<label class="sortlabel" ng-click="sortBy('created_on')">Created On  </label>
							<span class="sortorder" ng-show="predicate === 'created_on'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th  class="width10">
							<label class="sortlabel" ng-click="sortBy('updated_on')">Updated On  </label>
							<span class="sortorder" ng-show="predicate === 'updated_on'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">Action
							<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
						</th>
        			</tr>
        		</thead>
        		<tbody>
					<tr ng-hide="multiSearchTr">
						<td><input type="text" ng-change="getMultiSearch()" name="search_payment_source_name" ng-model="searchPayment.search_payment_source_name" 	class="multiSearch form-control width80" placeholder="Payment Source Name"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_payment_source_description" ng-model="searchPayment.search_payment_source_description" 	class="multiSearch form-control width80" placeholder="Payment Source Description"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_status"  ng-model="searchPayment.search_status"  class="multiSearch form-control width80" placeholder="Status"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" ng-model="searchPayment.search_created_by" class="multiSearch form-control width80" placeholder="Created By"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_created_at" ng-model="searchPayment.search_created_at" class="multiSearch form-control width80 visibility" placeholder="Created On"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" ng-model="searchPayment.search_updated_at" class="multiSearch form-control width80 visibility" placeholder="Updated On"></td>
						<td class="width10">
							<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
							<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
						</td>
					</tr>
                    <tr dir-paginate="obj in paymentSources| filter : filterPayment | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Payment Source Name">[[obj.payment_source_name]]</td>
						<td data-title="Payment Source Description">[[obj.payment_source_description]]</td>
        				<td data-title="Status" ng-if="obj.status==1">Active</td>
        				<td data-title="Status" ng-if="obj.status==0">Deactive</td>
        				<td data-title="Created By">[[obj.createdBy]]</td>
        				<td data-title="Created at">[[obj.created_at]]</td>
        				<td data-title="Updated at">[[obj.updated_at]]</td>
						@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
						<td class="width10">
						  @if(defined('EDIT') && EDIT)
							<button title="Update" class="btn btn-primary btn-sm"  ng-click='editPaymentSources(obj.payment_source_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>						
						  @endif
						  @if(defined('DELETE') && DELETE)	
							<button title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.payment_source_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						  @endif
						</td>
						@endif
        			</tr>
					<tr ng-hide="paymentSources.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
        		</tbody>
        	</table>
			
			<div class="box-footer clearfix">
                <dir-pagination-controls></dir-pagination-controls>
            </div>		  
		</div>
	</div>