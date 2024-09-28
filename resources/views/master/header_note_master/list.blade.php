<div class="row header" ng-init ="getList()">
<strong class="pull-left headerText" ng-click="getList()" title="Refresh">Header Note List <span ng-if="headerNoteData.length">([[headerNoteData.length]])</span></strong>
	<div class="navbar-form navbar-right" role="search">
		<div class="nav-custom">
		  <input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="searchData">
		</div>
	</div>
</div>	
<div class="row">
	<div id="no-more-tables">
		 <!-- show error message -->
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('header_name')">Header Name </label>
						<span class="sortorder" ng-show="predicate === 'header_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th >
						<label class="sortlabel" ng-click="sortBy('header_limit')">Header Limit</label>
						<span class="sortorder" ng-show="predicate === 'header_limit'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('header_status')"> Status </label>
						<span class="sortorder" ng-show="predicate === 'header_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					
					@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
					<th class="width10">Action</th>
					@endif
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="obj in headerNoteData| filter:searchData| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					
					<td data-title="Code">[[obj.header_name]]</td>
					<td data-title="Name">[[obj.header_limit]]</td>
					<td data-title="Status" ng-if ="[[obj.header_status]] == '1'"> Active</td>
					<td data-title="Status" ng-if ="[[obj.header_status]] == '0'"> Deactive</td>
					@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
					<td class="width10">
						@if(defined('EDIT') && EDIT)
						<button type="button"  title="Edit" class="btn btn-primary btn-sm" ng-click='edit(obj.header_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
						@endif
						@if(defined('DELETE') && DELETE)
						<button type="button"  title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.header_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						@endif
					</td>
					@endif
				</tr>
				<tr ng-hide="headerNoteData.length" class="noRecord"><td colspan="4" align="center">No Record Found!</td></tr>
			</tbody>
		</table>
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>