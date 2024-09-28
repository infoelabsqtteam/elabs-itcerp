<div class="row">
	<!--erpFilterOrderForm-->
	<form class="form-inline" method="POST" role="form" action="{{url('master/cities/download-excel')}}" id="erpFilterCityListingForm" name="erpFilterCityListingForm" target="blank"  novalidate>
		<label for="submit">{{ csrf_field() }}</label>
		<div class="row header">
			<strong class="pull-left headerText" ng-click="getCities(StateId)" title="Refresh">Cities <span ng-if="cityData.length">([[cityData.length]])</span></strong>
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom custom-display">
					<input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="searchCity">
					<select	class="form-control"
						ng-model="filterCity.country_id"
						ng-options="item.name for item in countryCodeList track by item.id"
						ng-change="funFilterStateOnCountryChange(filterCity.country_id.id)">
					    <option value="">Select Country</option>
					</select>
					<select class="form-control"
						name="state_id"
						ng-model="filterCity.city_state"
						ng-options="item.name for item in filterStatesList track by item.id "
						ng-change="getCities(filterCity.city_state.id)">
						<option value="">All State</option>
					</select>
					<button type="button" ng-disabled="!cityData.length" class="form-control btn btn-default dropdown dropdown-toggle " data-toggle="dropdown" title="Download">
					Download</button>
					<div class="dropdown-menu">
						<input type="submit" name="generate_city_documents"  class="dropdown-item" value="Excel">
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
								<label class="sortlabel" ng-click="sortBy('city_code')">City Code  </label>
								<span class="sortorder" ng-show="predicate === 'city_code'" ng-class="{reverse:reverse}"></span>						
							</th>			
							<th>
								<label class="sortlabel" ng-click="sortBy('city_name')">City Name </label>
								<span class="sortorder" ng-show="predicate === 'city_name'" ng-class="{reverse:reverse}"></span>						
							</th>
							<th>
								<label class="sortlabel" ng-click="sortBy('state_id')">State </label>
								<span class="sortorder" ng-show="predicate === 'state_id'" ng-class="{reverse:reverse}"></span>						
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
						<tr dir-paginate="obj in cityData| filter:searchCity| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
							<td data-title="City Code">[[obj.city_code]]</td>
							<td data-title="City Name ">[[obj.city_name]]</td>
							<td data-title="State Name">[[obj.state_name]]</td>
							<td data-title="Created By">[[obj.createdBy]]</td>
							<td data-title="Created On">[[obj.created_at]]</td>
							<td data-title="Updated On">[[obj.updated_at]]</td>
							<td class="width10">
								@if(defined('EDIT') && EDIT)
								<button type="button" title="Edit" class="btn btn-primary btn-sm" ng-click='editCity(obj.city_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
								@endif
								@if(defined('DELETE') && DELETE)
								<button type="button"  title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.city_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
								@endif
							</td>
						</tr>
						<tr ng-if="!cityData.length" class="noRecord"><td colspan="7">No Record Found!</td></tr>
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