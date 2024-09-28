<div class="row header" ng-init ="getAmendmentList()">
<strong class="pull-left headerText" ng-click="getAmendmentList()" title="Refresh">Amendments List <span ng-if="amendmentData.length">([[amendmentData.length]])</span></strong>
	<div class="navbar-form navbar-right" role="search">
		<div class="nav-custom">
		  <input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="searchAmendment">
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
						<label class="sortlabel" ng-click="sortBy('oam_code')">Amendment Code </label>
						<span class="sortorder" ng-show="predicate === 'oam_code'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th >
						<label class="sortlabel" ng-click="sortBy('oam_name')">Amendment Name</label>
						<span class="sortorder" ng-show="predicate === 'oam_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('oam_status')"> Status </label>
						<span class="sortorder" ng-show="predicate === 'oam_status'" ng-class="{reverse:reverse}"></span>						
					</th>
					
					@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
					<th class="width10">Action</th>
					@endif
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="obj in amendmentData| filter:searchAmendment| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					
					<td data-title="Code">[[obj.oam_code]]</td>
					<td data-title="Name">[[obj.oam_name]]</td>
					<td data-title="Status" ng-if ="[[obj.oam_status]] == '1'"> Active</td>
					<td data-title="Status" ng-if ="[[obj.oam_status]] == '0'"> Deactive</td>
					@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
					<td class="width10">
						@if(defined('EDIT') && EDIT)
						<button type="button"  title="Edit" class="btn btn-primary btn-sm" ng-click='editAmendment(obj.oam_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
						@endif
						@if(defined('DELETE') && DELETE)
						<button type="button"  title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.oam_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
						@endif
					</td>
					@endif
				</tr>
				<tr ng-hide="amendmentData.length" class="noRecord"><td colspan="4" align="center">No Record Found!</td></tr>
			</tbody>
		</table>
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>