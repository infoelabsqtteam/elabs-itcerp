<div class="row header">
	<strong class="pull-left headerText" ng-click="getCompanyTypes()" title="Refresh">Company Types  <span ng-if="companyData.length">([[companyData.length]])</span></strong>
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
			  <input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="searchCompany">
			</div>
        </div>
	</div>	
    <div class="row">
        <div id="no-more-tables">
			 <!-- show error message -->
            <table class="col-sm-12 table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
						<th class="tdWidth">
							<label class="sortlabel" ng-click="sortBy('company_type_code')">Company Code  </label>
							<span class="sortorder" ng-show="predicate === 'company_type_code'" ng-class="{reverse:reverse}"></span>						
						</th>			
						<th>
							<label class="sortlabel" ng-click="sortBy('company_type_name')">Company Name </label>
							<span class="sortorder" ng-show="predicate === 'company_type_name'" ng-class="{reverse:reverse}"></span>						
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
						@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
						<th class="width10">Action</th>
						@endif
        			</tr>
        		</thead>
        		<tbody>
                    <tr dir-paginate="obj in companyData | filter:searchCompany| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Company Code">[[obj.company_type_code]]</td>
        				<td data-title="Company Name ">[[obj.company_type_name]]</td>
						<td data-title="Created By">[[obj.createdBy]]</td>
        				<td data-title="Created On">[[obj.created_at]]</td>
        				<td data-title="Updated On">[[obj.updated_at]]</td>
						@if(defined('EDIT') && EDIT || defined('DELETE') && DELETE)
						<td class="width10">
							@if(defined('EDIT') && EDIT)
							<button title="Edit" class="btn btn-primary btn-sm" ng-click='editCompany(obj.company_type_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
						    @endif
							@if(defined('DELETE') && DELETE)
							<button title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.company_type_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></button>
							@endif
						</td>
						@endif
        			</tr>
        			<tr ng-hide="companyData.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
        		</tbody>
        	</table>
			<div class="box-footer clearfix">
                <dir-pagination-controls></dir-pagination-controls>
            </div>		  
		</div>
	</div>