<div class="row header" ng-init ="getHolidays()">
	<strong class="pull-left headerText" ng-click="getHolidays()" title="Refresh">Holidays  <span ng-if="holidayData.length">([[holidayData.length]])</span></strong>
	<div class="navbar-form navbar-right" role="search">
		<div class="nav-custom">
			<input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="searchHoliday">
		</div>
	</div>
</div>	
<div class="row">
	<div id="no-more-tables">
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('division_name')">Branch Name</label>
						<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('holiday_name')">Holiday Name</label>
						<span class="sortorder" ng-show="predicate === 'holiday_name'" ng-class="{reverse:reverse}"></span>						
					</th>			
					<th class="tdWidth">
						<label class="sortlabel" ng-click="sortBy('holiday_date')">Holiday Date </label>
						<span class="sortorder" ng-show="predicate === 'holiday_date'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="tdWidth">
						<label class="sortlabel" ng-click="sortBy('holiday_status')">Holiday Status </label>
						<span class="sortorder" ng-show="predicate === 'holiday_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					
					@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
						<th class="width10">Action</th>
					@endif
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="obj in holidayData| filter:searchHoliday| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Holiday Name">[[obj.division_name]]</td>
					<td data-title="Holiday Name">[[obj.holiday_name]]</td>
					<td data-title="Holiday Date">[[obj.holiday_date]]</td>
					<td data-title="Holiday Status"><span ng-if="obj.holiday_status == 1">Active</span><span ng-if="obj.holiday_status == 0">Deactive</span></td>
					@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
					<td class="width10">
						@if(defined('EDIT') && EDIT)
							<button title="Edit" class="btn btn-primary btn-sm" ng-click='editHoliday(obj.holiday_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
						@endif
						@if(defined('DELETE') && DELETE)
							<button title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.holiday_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						@endif
					</td>
					@endif
				</tr>
				<tr ng-hide="holidayData.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
				</tr>
			<tfoot>
		</table>		
	</div>
</div>