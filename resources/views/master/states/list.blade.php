<div class="row col-sm-12">
	<!--erpFilterOrderForm-->
	<form class="form-inline" method="POST" role="form" action="{{url('master/states/download-excel')}}" target= "blank"  id="erpFilterStateForm" name="erpFilterStateForm" novalidate>
		<label for="submit">{{ csrf_field() }}</label>
		<div class="row header">
			<strong class="pull-left headerText" ng-click="getStates()" title="Refresh">States  <span ng-if="stateData.length">([[stateData.length]])</span></strong>
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom custom-display">
					<input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="searchState">
					<select class="form-control"
						name="country_id"
						ng-model="filterState.country_id"
						id="country_id"
						ng-options="item.name for item in countryCodeList track by item.id"
						ng-change="getStates()">
					    <option value="">Select Country</option>
					</select>
					<button type="button" ng-disabled="!stateData.length" class="form-control btn btn-default dropdown dropdown-toggle downloadStateBtn" data-toggle="dropdown" title="Download">
					Download</button>
					<div class="dropdown-menu">
						<input type="submit"  name="generate_state_documents"  class="dropdown-item" value="Excel">
					</div>	
				</div>
			</div>
				
		</div>
		
		<div class="row">
			<div id="no-more-tables">
				<table class="col-sm-12 table-striped table-condensed cf">
					<thead class="cf">
						<tr>
							<th class="tdWidth">
								<label class="sortlabel" ng-click="sortBy('state_code')">State Code  </label>
								<span class="sortorder" ng-show="predicate === 'state_code'" ng-class="{reverse:reverse}"></span>						
							</th>			
							<th>
								<label class="sortlabel" ng-click="sortBy('state_name')">State Name </label>
								<span class="sortorder" ng-show="predicate === 'state_name'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('country_id')">Country </label>
								<span class="sortorder" ng-show="predicate === 'country_id'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('state_status')">Status </label>
								<span class="sortorder" ng-show="predicate === 'state_status'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('created_by')">Created By  </label>
								<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('created_at')">Created At </label>
								<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('updated_at')">Updated At</label>
								<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th class="width10">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="obj in stateData| filter:searchState| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
							<td data-title="State Code">[[obj.state_code]]</td>
							<td data-title="State Name ">[[obj.state_name]]</td>
							<td data-title="Country Name">[[obj.country_name]]</td>
							<td data-title="Status">[[obj.state_status | activeOrInactive ]]</td>
							<td data-title="Created By">[[obj.createdBy]]</td>
							<td data-title="Created On">[[obj.created_at]]</td>
							<td data-title="Updated On">[[obj.updated_at]]</td>
							<td class="width10">
								@if(defined('EDIT') && EDIT)
								<button type="button" title="Edit" class="btn btn-primary btn-sm" ng-click='editState(obj.state_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
								@endif
								@if(defined('DELETE') && DELETE)
								<button type="button"  title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.state_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
								@endif
							</td>
						</tr>
						<tr ng-if="!stateData.length" class="noRecord"><td colspan="7">No Record Found!</td></tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="7"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</form>
</div>