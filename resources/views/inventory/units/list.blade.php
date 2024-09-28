<div class="row">	
		<div class="header">
			<strong class="pull-left headerText"  ng-click="getUnits()" title="Refresh">Units <span ng-if="unitdata.length">([[unitdata.length]])</span> </strong>	
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom"">
					<input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchUnits">
					<a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" type="button" class="btn btn-primary btn-md" data-toggle="modal" title="Add New Record" data-target="#add_form">Add New</a>
				</div>
			</div>
		</div>
    
        <div id="no-more-tables">
			 <!-- show error message -->
            <table class="col-sm-12 table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('unit_code')">Unit Code  </label>
							<span class="sortorder" ng-show="predicate === 'unit_code'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('unit_name')">Unit Name </label>
							<span class="sortorder" ng-show="predicate === 'unit_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('unit_desc')">Unit Description </label>
							<span class="sortorder" ng-show="predicate === 'unit_desc'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
							<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">Action</th>
        			</tr>
        		</thead>
        		<tbody>
                    <tr dir-paginate="obj in unitdata| filter:searchUnits| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Unit Code">[[obj.unit_code]]</td>
        				<td data-title="Unit Name ">[[obj.unit_name]]</td>
        				<td data-title="Unit Description ">[[obj.unit_desc]]</td>
        				<td data-title="Created By">[[obj.createdBy]]</td>
        				<td data-title="Created At ">[[obj.created_at]]</td>
        				<td data-title="Created At ">[[obj.updated_at]]</td>
						<td class="width10">
							<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" class="btn btn-primary btn-sm" ng-click='editUnit(obj.unit_id)' title="Update"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.unit_id)' title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
        			</tr>
					<tr ng-if="!unitdata.length"  class="noRecord"><td colspan="5">No Record Found!</td></tr>
        		</tbody>
				<tfoot ng-if="unitdata.length > {{PERPAGE}}">
					<tr>
						<td colspan="8">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
        	</table>	  
		</div>
	</div>