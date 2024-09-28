	<div class="row header">
		<strong class="pull-left headerText">Company Details</strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
			  <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid"  title="Search" placeholder="Search" ng-model="searchCompines">
			</div>
		</div>
	</div>
	<div class="row">
		<div id="no-more-tables">
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('company_code')">Company Code  </label>
							<span class="sortorder" ng-show="predicate === 'company_code'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('company_name')">Company Name  </label>
							<span class="sortorder" ng-show="predicate === 'company_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('city_name')">Company City </label>
							<span class="sortorder" ng-show="predicate === 'city_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('company_address')">Company Address </label>
							<span class="sortorder" ng-show="predicate === 'company_address'" ng-class="{reverse:reverse}"></span>						
						</th>	
						<th>
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
						</th>		
						<th>
							<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
						</th>						
						<th colspan="2"><label>Action</label></th>
					</tr>
				</thead>
				<tbody>
					<tr dir-paginate="obj in compdata | filter:searchCompines | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Company Code">[[obj.company_code]]</td>
						<td data-title="Company Name">[[obj.company_name]]</td>						
						<td data-title="Company City">[[obj.city_name]]</td>
						<td data-title="Company Address">[[obj.company_address]]</td>
						<td data-title="Created On">[[obj.created_at]]</td>
						<td data-title="Updated On">[[obj.updated_at]]</td>
						<td>
						@if(defined('EDIT') && EDIT)
							<button class="btn btn-primary btn-sm" title="Update" ng-click='editCompany(obj.company_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
						@endif
						</td>
					</tr>
					<tr ng-hide="compdata.length"  class="noRecord"><td colspan="5">No Record Found!</td></tr>
				</tbody>
			</table>
			
			<div class="box-footer clearfix">
				<dir-pagination-controls></dir-pagination-controls>
			</div>		  
		</div>
	</div>