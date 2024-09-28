<div class="row">
	<form name="erpAddTestParameterDownLoadForm" target= "blank" method="POST" id="erpAddTestParameterDownLoadForm" action="{{url('master/test-parameter/download-excel')}}" novalidate>
		<label for="submit">{{ csrf_field() }}</label>
		<div class="header">
			<strong class="pull-left headerText" ng-click="refreshMultiSearch()" title="Refresh">Test Parameters <span ng-if="allList.length">([[allList.length]])</span></strong>	
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
					<input type="text" ng-keypress="funEnterPressHandler($event)" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchParameter.keyword" name="keyword" ng-change="funFilterTestParameter(searchParameter.keyword)">		  	
					<button type="button" title="Select Product Category" ng-disabled="!allList .length"  class="btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download" aria-disabled="false">Download</button>
					<div class="dropdown-menu" style="top:auto !important">
						<input type="submit" formtarget="_blank" name="generate_parameter_documents" value="Excel" class="dropdown-item">
					</div>
				</div>
			</div>
		</div>	
	
		<div id="no-more-tables">
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('test_parameter_code')">Test Parameter Code</label>
							<span class="sortorder" ng-show="predicate === 'test_parameter_code'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('test_parameter_name')">Test Parameter Name</label>
							<span class="sortorder" ng-show="predicate === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('test_parameter_print_desc')">Test Parameter Print Description</label>
							<span class="sortorder" ng-show="predicate === 'test_parameter_print_desc'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('test_parameter_category')">Test Parameter Category</label>
							<span class="sortorder" ng-show="predicate === 'test_parameter_category'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('test_parameter_invoicing')">Invoicing Required</label>
							<span class="sortorder" ng-show="predicate === 'test_parameter_invoicing'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('test_parameter_nabl_scope')">NABL Scope</label>
							<span class="sortorder" ng-show="predicate === 'test_parameter_nabl_scope'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('test_parameter_decimal_place')">Decimal Place</label>
							<span class="sortorder" ng-show="predicate === 'test_parameter_decimal_place'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('cost_price')">Cost Price</label>
							<span class="sortorder" ng-show="predicate === 'cost_price'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('selling_price')">Selling Price</label>
							<span class="sortorder" ng-show="predicate === 'selling_price'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment Type</label>
							<span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('created_by')">Created By  </label>
							<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('status')">Status  </label>
							<span class="sortorder" ng-show="predicate === 'status'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width8">
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On  </label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width8">
							<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On  </label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">Action	
							<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-hide="multiSearchTr">
						<td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(searchParameter.test_parameter_code)" name="search_test_parameter_code" ng-model="searchParameter.test_parameter_code" class="multiSearch form-control width100" placeholder="Parameter Code"></td>
						<td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(searchParameter.test_parameter_name)" name="search_test_parameter_name" ng-model="searchParameter.test_parameter_name" class="multiSearch form-control width100" placeholder="Parameter Name"></td>
						<td><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(searchParameter.test_parameter_print_desc)" name="search_test_parameter_print_desc" ng-model="searchParameter.test_parameter_print_desc" class="multiSearch form-control" placeholder="Test Parameter Print Description"></td>
						<td class="width8"><button type="button" title="Select Product Category" ng-click="showParameterCatTreeViewPopUp(11)" class="btn btn-default">Tree View</button><input type="hidden" name="search_test_para_cat_id" ng-model="searchParameter.search_test_para_cat_id" ng-value="searchParameter.search_test_para_cat_id"></td>						
						<td class="width8"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(searchParameter.search_test_parameter_invoicing)" name="search_test_parameter_invoicing" ng-model="searchParameter.search_test_parameter_invoicing" class="multiSearch form-control" placeholder="Invoicing Required"></td>
						<td class="width8"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(searchParameter.search_test_parameter_nabl_scope)" name="search_test_parameter_nabl_scope" ng-model="searchParameter.search_test_parameter_nabl_scope" class="multiSearch form-control" placeholder="NABL Scope"></td>
						<td class="width8"></td>
						<td class="width8"></td>
						<td class="width8"></td>
						<td class="width8"><select class="multiSearch form-control" ng-model="searchParameter.search_equipment_type_id" ng-options="item.name for item in equipmentTypesList track by item.id" ng-change="getMultiSearch(searchParameter.search_equipment_type_id);" name="search_equipment_type_id"><option value="">All Equipment</option></select></td>
						<td class="width8"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(searchParameter.search_created_by)" name="search_created_by" ng-model="searchParameter.search_created_by" class="multiSearch form-control " placeholder="Created By"></td>
						<td><select ng-change="getMultiSearch()" name="search_status" class="form-control multiSearch" ng-model="searchParameter.search_status" ng-options="status.name for status in statusList track by status.id"><option value="">All Status</option></select></td>

						<td class="width8"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(searchParameter.search_created_at)" ng-model="searchParameter.search_created_at" class="multiSearch form-control visibility" placeholder="Created On"></td>
						<td class="width8"><input type="text" ng-keypress="funEnterPressHandler($event)" ng-change="getMultiSearch(searchParameter.search_updated_at)" ng-model="searchParameter.search_updated_at" class="multiSearch form-control visibility" placeholder="Updated On"></td>
						<td class="width10">	
							<button type="button" ng-click="refreshMultiSearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
							<button type="button" ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
						</td>
					</tr>
					<tr dir-paginate="obj in allList | filter : filterParameters | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Parameter Code">[[obj.testParametersList.test_parameter_code]]</td>
						<td data-title="Parameter Name" class="uper-case" ng-bind-html="obj.testParametersList.test_parameter_name"></td>
						<td data-title="Parameter Print Description" class="uper-case" ng-bind-html="obj.testParametersList.test_parameter_print_desc">
							<span id="parameterlimitedText-[[obj.testParametersList.test_parameter_id]]">
								[[ obj.testParametersList.test_parameter_print_desc | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
								<a href="javascript:;" ng-click="toggleDescription('parameter',obj.testParametersList.test_parameter_id)" ng-show="obj.testParametersList.test_parameter_print_desc.length > 150" class="readMore">read more...</a>
							</span>
							<span id="parameterfullText-[[obj.testParametersList.test_parameter_id]]" style="display:none;" >
								[[ obj.testParametersList.test_parameter_print_desc]] 
								<a href="javascript:;" ng-click="toggleDescription('parameter',obj.testParametersList.test_parameter_id)" class="readMore">read less...</a>
							</span>
						</td>
						<td data-title="Parameter Category">[[obj.testParametersList.test_para_cat_name]]</td>
						<td data-title="Invoicing Required">
							<span ng-if="obj.testParametersList.test_parameter_invoicing == '1'">Yes</span>
							<span ng-if="obj.testParametersList.test_parameter_invoicing == '0'">No</span>
						</td>
						<td data-title="NABL Scope">
							<span ng-if="obj.testParametersList.test_parameter_nabl_scope == '1'">Yes</span>
							<span ng-if="obj.testParametersList.test_parameter_nabl_scope == '0'">No</span>
						</td>
						<td data-title="cost price">[[obj.testParametersList.test_parameter_decimal_place]]</td>
						<td data-title="cost price">[[obj.testParametersList.cost_price]]</td>
						<td data-title="selling price">[[obj.testParametersList.selling_price]]</td>					
						<td data-title="Equipment Type">
							<span ng-if="obj.equipmentList.length==1">[[obj.testParametersList.equipment_name]]</span>
							<span ng-if="obj.equipmentList.length>1">
								<a title="Equipments List" class="text-center cursor-pointer" data-toggle="modal" data-target="#myModalEquip_[[obj.testParametersList.test_parameter_id]]"><i class="fa fa-eye" aria-hidden="true"></i></a>
								<div class="modal fade" id="myModalEquip_[[obj.testParametersList.test_parameter_id]]" role="dialog">
									<div class="modal-dialog modal-sm">
										<div class="modal-content">
											<div class="modal-header text-left">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title"><span class="poptitle">Equipment Types</span></h4>
											</div>
											<div class="modal-body">											
												<ul>
													<li ng-repeat="equipment in obj.equipmentList">[[equipment.equipment_name]]</li>
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
						<td data-title="Created By">[[obj.testParametersList.createdBy]]</td>
						<td data-title="Created By">[[obj.testParametersList.status | activeOrInactiveUsers]]</td>
						<td data-title="Created On">[[obj.testParametersList.created_at?obj.testParametersList.created_at:'-']]</td>
						<td data-title="Updated On ">[[obj.testParametersList.updated_at?obj.testParametersList.updated_at:'-']]</td>
						<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
							<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='editRecord(obj.testParametersList.test_parameter_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(testParameterCategory,obj.testParametersList.test_parameter_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-hide="allList.length"  class="noRecord"><td colspan="8">No Record Found!</td></tr>
				</tbody>
			</table>
	
			<div class="box-footer clearfix">
				<dir-pagination-controls></dir-pagination-controls>
			</div>		  
		</div>
	</form>
</div>