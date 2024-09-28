<div class="row" ng-if="listAccreditationCertificates">
	<form class="form-inline" method="POST" role="form"  id="erpFilterCityListingForm" name="erpFilterAccreditationCertificateForm" target="blank"  novalidate>

		<div class=" header">
			<strong class="pull-left headerText" ng-click="getCertificatesList();" title="Refresh">Accreditation Certificates<span ng-if="certificatesListData.length">([[certificatesListData.length]])</span></strong>	
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
					<input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-change="getAccCertificateKeywordSearch(searchAccCertificate.filterCertificates);" ng-model="searchAccCertificate.filterCertificates" name="search_keyword">
				</div>
			</div>
		</div>
		<div id="no-more-tables">
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('oac_division_name')">Branch</label>
							<span class="sortorder" ng-show="predicate === 'oac_division_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('oac_product_category_name')">Department</label>
							<span class="sortorder" ng-show="predicate === 'oac_product_category_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('oac_name')">Accreditation Certificate Name</label>
							<span class="sortorder" ng-show="predicate === 'oac_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('oac_multi_location_lab_value')">Multi Location Labs</label>
							<span class="sortorder" ng-show="predicate === 'oac_multi_location_lab_value'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('oac_status')">Status</label>
							<span class="sortorder" ng-show="predicate === 'oac_status'" ng-class="{reverse:reverse}"></span>						
						</th>			
						<th  class="width10">
							<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th  class="width10">
							<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">Action 					
							<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary mL10"><i class="fa fa-filter"></i></button>     			
						</th>
					</tr>
				</thead>
				<tbody>		
					<tr ng-hide="multiSearchTr">
						<td><select class="form-control multiSearch" name="search_oac_division_id" ng-model="searchAccCertificate.search_division_name" ng-change="getMultiSearch(searchAccCertificate.search_division_name)" id="accreditation_certificate_branch" ng-required='true' ng-options="item.name for item in divisionsCodeList track by item.id"><option value="">All Branch</option></select></td>
						<td></td>
						<td><input type="text" ng-change="getMultiSearch(searchAccCertificate.search_oac_name)" name="search_oac_name" ng-model="searchAccCertificate.search_oac_name" class="multiSearch form-control width80" placeholder="Certificate Name"></td>
						<td><input type="text" ng-change="getMultiSearch(searchAccCertificate.oac_multi_location_lab_value)" name="search_oac_multi_location_lab_value" ng-model="searchAccCertificate.oac_multi_location_lab_value" class="multiSearch form-control width80" placeholder="Location"></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="width10">
							<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
							<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
						</td>
					</tr>
					<tr dir-paginate="obj in certificatesListData| filter:filterCertificates | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="Branch Name">[[obj.oac_division_name]]</td>
						<td data-title="Department Name">[[obj.oac_product_category_name]]</td>
						<td data-title="Company Code">[[obj.oac_name]]</td>
						<td data-title="Company Code">[[obj.oac_multi_location_lab_value]]</td>
						<td data-title="Company Code"><span ng-if="obj.oac_status==1">Active</span><span ng-if="obj.oac_status==2">Inactive</span></td>
						<td  data-title="Created On ">[[obj.created_at]]</td>
						<td  data-title="Updated On">[[obj.updated_at]]</td>
						<td class="width10">
							<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" class="btn btn-primary btn-sm" ng-click='funEditAccreditationCertificate(obj.oac_id)' title="Update"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.oac_id)' title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>						
					<tr ng-hide="certificatesListData.length" class="noRecord"><td colspan="6">No Record Found!</td></tr>
				</tbody>
				<tfoot>
					<tr ng-hide="certificatesListData.length" class="noRecord"><td colspan="6"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td></tr>
				</tfoot>
			</table>			
		</div>
	</form>
</div>
