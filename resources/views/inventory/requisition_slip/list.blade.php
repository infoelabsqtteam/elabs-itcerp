<div id="listRequisition" ng-hide="listRequisition">	
	<!--display Heading-->
	<div class="row header">
		<strong class="pull-left headerText">Requisition Slips <span ng-if="requisitiondata.length">([[requisitiondata.length]])</span> </strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
			  <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchReq">				   
				<select ng-if="{{$division_id}} == 0"  class="form-control"
						name="search_division_id"
						ng-model="search_division_id.selectedOption"
						ng-required="true" ng-change="getRequisitions(search_division_id.selectedOption.id)"
						ng-options="item.name for item in divisionsList track by item.id ">
						<option value="">Select Branch</option>
				</select>	
				<a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" title="Add New Record"  type="button" class="btn btn-info" ng-click="addRequisitionForm()">Add New</a>
			
			</div>
		</div>
	</div>	
	<!--/display Heading-->
	
	<div class="row">
		<div id="no-more-tables">
			 <!-- show error message -->
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('req_slip_no')">Requisition Slip Number  </label>
							<span class="sortorder" ng-show="predicate === 'req_slip_no'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('req_slip_date')">Requisition Date  </label>
							<span class="sortorder" ng-show="predicate === 'req_slip_date'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('department_name')">Department Name  </label>
							<span class="sortorder" ng-show="predicate === 'department_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('name')">Issued By  </label>
							<span class="sortorder" ng-show="predicate === 'name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('division_id')">Branch </label>
							<span class="sortorder" ng-show="predicate === 'division_id'" ng-class="{reverse:reverse}"></span>						
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
				<tbody >
					<tr dir-paginate="obj in requisitiondata | filter:searchReq | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Requisition Slip Number">[[obj.req_slip_no?obj.req_slip_no:'-']]</td>			
						<td data-title="Requisition Date">[[obj.req_slip_date?obj.req_slip_date:'-'  | date : 'dd-MM-yyyy']]</td>			
						<td data-title="Department">[[obj.department_name?obj.department_name:'-']]</td>			
						<td data-title="Employee Name">[[obj.name?obj.name:'-']]</td>			
						<td data-title="Branch">[[obj.division_name?obj.division_name:'-']]</td>
						<td data-title="Created By">[[obj.createdBy]]</td>							
						<td data-title="Created Date">[[obj.created_at?obj.created_at:'-' ]]</td>
						<td data-title="Updated Date">[[obj.updated_at?obj.updated_at:'-' ]]</td>
						<td class="width10">
							<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record"  class="btn btn-info btn-sm" ng-click='funEditRequisition(obj.req_slip_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete"  class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.req_slip_id,divisionID)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-if="!requisitiondata.length" class="noRecord"><td colspan="10">No Record Found!</td></tr>
				</tbody>
				<tfoot ng-if="requisitiondata.length > {{PERPAGE}}">
					<tr>
						<td colspan="9">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>	