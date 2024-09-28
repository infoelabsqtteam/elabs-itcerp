<div class="row">	
	<form method="POST" role="form" id="erpMasterListingForm" name="erpMasterListingForm" novalidate>
		
		<div class="header">
			<strong class="pull-left headerText" ng-click="funListMaster();" title="Refresh">Discipline-Parameter Categories<span ng-if="masterDataList.length">([[masterDataList.length]])</span></strong>
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
					<input type="text" class="form-control" name="searchKeyword" placeholder="Search" ng-model="filterGroupModel">
				</div>
			</div>
		</div>
		
		<div id="no-more-tables">			
			<table class="col-sm-12 table table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('ordp_division_name')">Division</label>
							<span class="sortorder" ng-show="predicate === 'ordp_division_name'" ng-class="{reverse:reverse}"></span>						
						</th>			
						<th   class="width10">
							<label class="sortlabel" ng-click="sortBy('ordp_product_category_name')">Department</label>
							<span class="sortorder" ng-show="predicate === 'ordp_product_category_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th   class="width10">
							<label class="sortlabel" ng-click="sortBy('ordp_discipline_name')">Discipline Name</label>
							<span class="sortorder" ng-show="predicate === 'ordp_discipline_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						
						<th class="width10">
							<label class="sortlabel">Parameter Categories</label>						
						</th>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('ordp_created_name')">Created By</label>
							<span class="sortorder" ng-show="predicate === 'ordp_created_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th  class="width8">
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width8" >
							<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">Action</th>
					</tr>
				</thead>
				<tbody>
					<tr dir-paginate="masterDataobj in masterDataList| filter:filterGroupModel | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Division Name">[[masterDataobj.ordp_division_name]]</td>
						<td data-title="Department Name">[[masterDataobj.ordp_product_category_name]]</td>
						<td data-title="Discipline Name">[[masterDataobj.ordp_discipline_name]]</td>
						<td data-title="Parameter Category">
							<span ng-if="masterDataobj.ordpTestParameterCategoryList.length">
								<a href="javascript:;" title="[[employeeTitle]]" ng-mouseover="funArrayToString(masterDataobj.ordpTestParameterCategoryList,'name')" class="text-center" data-toggle="modal" data-target="#myModalTestParameterCategory_[[masterDataobj.ordp_id]]"><i class="fa fa-eye" aria-hidden="true"></i></a>
								<div class="modal fade" id="myModalTestParameterCategory_[[masterDataobj.ordp_id]]" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header text-left">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title"><span class="poptitle">Total Parameter Categories([[masterDataobj.ordpTestParameterCategoryList.length]])</span></h4>
											</div>
											<div ng-if="masterDataobj.ordpTestParameterCategoryList.length <= 10" class="modal-body">
												<ul>
													<li id="[[ordpTestParameterCategoryObj.id]]" ng-repeat="ordpTestParameterCategoryObj in masterDataobj.ordpTestParameterCategoryList">[[ordpTestParameterCategoryObj.name]]</li>
												</ul>
											</div>
											<div ng-if="masterDataobj.ordpTestParameterCategoryList.length > 10" class="modal-body custom-sm-scroll">
												<ul>
													<li id="[[ordpTestParameterCategoryObj.id]]" ng-repeat="ordpTestParameterCategoryObj in masterDataobj.ordpTestParameterCategoryList">[[ordpTestParameterCategoryObj.name]]</li>
												</ul>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>							 
							</span>
						</td>
						<td data-title="Created By">[[masterDataobj.ordp_created_name]]</td>
						<td data-title="Created On">[[masterDataobj.created_at]]</td>
						<td data-title="Updated On">[[masterDataobj.updated_at]]</td>
						<td class="width10">
							<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditMaster(masterDataobj.ordp_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(masterDataobj.ordp_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-if="!masterDataList.length" class="noRecord"><td colspan="9">No Record Found!</td></tr>
				</tbody>
				<tfoot>
					<tr ng-if="masterDataList.length">
						<td colspan="[[masterDataList.length]]">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</form>	
</div>