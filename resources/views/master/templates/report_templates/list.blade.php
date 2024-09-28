<div ng-if="showTemplatesList">
	<div class="header">
		<strong class="pull-left headerText" ng-click="getTemplatesList();" title="Refresh">Total Templates <span ng-if="templatesData.length">([[templatesData.length]])</span></strong>
		<div class="navbar-form navbar-right" role="search">
			<div class="nav-custom">
				<input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="filterReportTemplates">
				<button ng-click="addForm()" class="btn btn-primary ng-binding" id="add_new_order" type="button"> Add New </button>
			</div>
		</div>
	</div>
	
	<div id="no-more-tables">
		<form  method="POST" role="form" id="erpFilterMultiSearchTemplateForm" name="erpFilterMultiSearchTemplateForm" novalidate>
			<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th class="width10">
						<label class="sortlabel" ng-click="sortBy('created_by')">Branch</label>
						<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('p_category_name')">Department</label>
						<span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>						
					</th>
				    <th>
						<label class="sortlabel" ng-click="sortBy('template_type_id')">Template Type</label>
						<span class="sortorder" ng-show="predicate === 'template_type_id'" ng-class="{reverse:reverse}"></span>						
					</th>	
					<th>
						<label class="sortlabel" ng-click="sortBy('header_content')">Header Content</label>
						<span class="sortorder" ng-show="predicate === 'header_content'" ng-class="{reverse:reverse}"></span>						
					</th>
						<th>
						<label class="sortlabel" ng-click="sortBy('footer_content')">Footer Content</label>
						<span class="sortorder" ng-show="predicate === 'footer_content'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th  class="width8">
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On </label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8" >
						<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">Action					
						<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>
					</th>
			
				</tr>
			</thead>
			<tbody>
				<tr ng-hide="multiSearchTr">
					<td>
						<select  class="form-control multiSearch" name="division_id"
							ng-model="searchTemplate.division_id" id="division_id"
							ng-options="item.name for item in divisionsCodeList track by item.id">
						<option value="">All Branchs</option>
						</select>
					</td>
					<td>
						<select class="form-control multiSearch"
							name="product_category_id"
							id="product_category_id"
							ng-model="searchTemplate.product_category_id"
							ng-options="item.name for item in parentCategoryList track by item.id"
							>
						<option value="">All Departments</option>
						</select>
					</td>
					<td>
						<select class="form-control multiSearch "
								name="template_type_id"
								id="template_type_id"
								ng-model="searchTemplate.template_type_id"
								ng-options="item.name for item in templatesTypeList track by item.id"
								>
							<option value="">All Template Types</option>
						</select>
					</td>
					<td></td><td></td><td></td><td></td>
					<td class="width10">
						<button ng-click="getMultiSearch();" class="btn btn-primary btn-sm" title="Refresh"><i class="fa fa-search" aria-hidden="true"></i></button>
						<button ng-click="refreshMultisearch()" title="Refresh" value="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close" value="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>
				<tr dir-paginate="obj in templatesData| filter:filterReportTemplates| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
					<td data-title="Division Name">[[obj.division_name]]</td>
					<td data-title="Parent Category Name ">[[obj.p_category_name]]</td>
					<td data-title="template type">[[obj.template_type_name]]</td>
					<td data-title="Header Content">
						<span id="headerTemplateLimitedText-[[obj.template_id]]">
							[[ obj.header_content | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 200 }} ]]
							<a href="javascript:;" ng-click="toggleDescription('headerTemplate',obj.template_id)" ng-show="obj.header_content.length > 150" class="readMore">read more...</a>
						</span>
						<span id="headerTemplateFullText-[[obj.template_id]]" style="display:none;" >
							[[ obj.header_content]] 
							<a href="javascript:;" ng-click="toggleDescription('headerTemplate',obj.template_id)" class="readMore">read less...</a>
						</span>
						
					</td>
					<td data-title="Footer Content">
						<span ng-if="obj.footer_content" id="footerTemplateLimitedText-[[obj.template_id]]">
							[[ obj.footer_content | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 200 }} ]]
							<a href="javascript:;" ng-click="toggleDescription('footerTemplate',obj.template_id)" ng-show="obj.header_content.length > 150" class="readMore">read more...</a>
						</span>
						<span ng-if="obj.footer_content" id="footerTemplateFullText-[[obj.template_id]]" style="display:none;" >
							[[ obj.footer_content]] 
							<a href="javascript:;" ng-click="toggleDescription('footerTemplate',obj.template_id)" class="readMore">read less...</a>
						</span>
					</td>
					<td data-title="Created On">[[obj.created_at]]</td>
					<td data-title="Updated On">[[obj.updated_at]]</td>
					<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
						<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditTemplateContent(obj.template_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.template_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
				<tr ng-hide="templatesData.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
				</tbody>
				<tfoot>
					<tr class="noRecord">
						<td colspan="5">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>
