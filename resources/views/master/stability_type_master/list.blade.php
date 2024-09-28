<div class="row header" ng-init ="getList()">
<strong class="pull-left headerText" ng-click="getList()" title="Refresh">Stability Type Master List <span ng-if="stabilityTypeData.length">([[stabilityTypeData.length]])</span></strong>
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
					
					<th >
						<label class="sortlabel" ng-click="sortBy('stb_stability_type_name')">Stability Type </label>
						<span class="sortorder" ng-show="predicate === 'stb_stability_type_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('stb_stability_type_status')"> Status </label>
						<span class="sortorder" ng-show="predicate === 'stb_stability_type_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					
					@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
					<th class="width10">Action</th>
					@endif
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="obj in stabilityTypeData| filter:searchData| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					
					<td data-title="Name">[[obj.stb_stability_type_name]]</td>
					<td data-title="Status" ng-if ="[[obj.stb_stability_type_status]] == '1'"> Active</td>
					<td data-title="Status" ng-if ="[[obj.stb_stability_type_status]] == '0'"> Deactive</td>
					@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
					<td class="width10">
						@if(defined('EDIT') && EDIT)
						<button type="button"  title="Edit" class="btn btn-primary btn-sm" ng-click='edit(obj.stb_stability_type_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
						@endif
						@if(defined('DELETE') && DELETE)
						<button type="button"  title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.stb_stability_type_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						@endif
					</td>
					@endif
				</tr>
				<tr ng-hide="stabilityTypeData.length" class="noRecord"><td colspan="4" align="center">No Record Found!</td></tr>
			</tbody>
		</table>
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>