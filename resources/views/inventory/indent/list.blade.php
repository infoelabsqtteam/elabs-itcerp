<div id="listIndent" ng-hide="listIndent">	
	<!--display Heading-->
	<div class="row header">
		<strong class="pull-left headerText">Indent Slips <span ng-if="indentdata.length">([[indentdata.length]])</span></strong>	
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
			  <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchReq">
				<select ng-if="{{$division_id}} == 0"  class="form-control"
						name="search_division_id" 
						ng-model="search_division_id"
						ng-required="true" ng-change="getIndents(search_division_id.id)"
						ng-options="item.name for item in divisionsList track by item.id ">
						<option value="">Select Branch</option>
				</select>
				<a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" title="Add New Record"  type="button" class="btn btn-info btn-md" ng-click="addIndentForm()">Add New</a>
			</div>
		</div>
	</div>	
	<!--/display Heading-->
	
	<!--display Listing of Indent-->
	<div class="row">
		<div id="no-more-tables">
			 <!-- show error message -->
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('indent_no')">Indent Number  </label>
							<span class="sortorder" ng-show="predicate === 'indent_no'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('indent_date')">Indent Date</label>
							<span class="sortorder" ng-show="predicate === 'indent_date'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('name')">Indented By</label>
							<span class="sortorder" ng-show="predicate === 'name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('division_name')">Branch </label>
							<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
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
					<tr dir-paginate="obj in indentdata| filter:searchReq| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Indent Slip Number">[[obj.indent_no?obj.indent_no:'-']]</td>			
						<td data-title="Indent Date">[[obj.indent_date?obj.indent_date:'-'  | date : 'dd-MM-yyyy']]</td>			
						<td data-title="Department">[[obj.name?obj.name:'-']]</td>			
						<td data-title="Department">[[obj.division_name?obj.division_name:'-']]</td>
						<td data-title="Created By">[[obj.createdBy]]</td>
						<td data-title="Created Date">[[obj.created_at?obj.created_at:'-' ]]</td>
						<td data-title="Updated Date">[[obj.updated_at?obj.updated_at:'-' ]]</td>
						<td class="width10">
							<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Edit" class="btn btn-info btn-sm" ng-click='funEditIndent(obj.indent_hdr_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(divisionID,obj.indent_hdr_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-if="!indentdata.length"  class="noRecord"><td colspan="8">No Record Found!</td></tr>
				</tbody>
				<tfoot ng-if="indentdata.length > {{PERPAGE}}">
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
	<!--/display Listing of Indent-->
</div>	