<div class="row">	
	<div class="header">
		<strong class="pull-left headerText" ng-click="getAllDetectors(EquipmentTypeId)" title="Refresh">Detectors <span ng-if="detectorData.length">([[detectorData.length]])</span></strong>
		<form class="form-inline ng-pristine ng-valid" method="POST" role="form" id="erpFilterDetectorForm" name="erpFilterDetectorForm" action="{{url('master/detector/download')}}">
			{{ csrf_field() }}
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
					<input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="filterDetector">
					<select class="form-control" style="width: 49%;"ng-model="equ_id" name="equipment_type_id"
					ng-options="item.name for item in equipmentTypesList track by item.id" 
					ng-change="getAllDetectors(equ_id.id)">
						<option value="">Select Equipment Type</option>
					</select>
					<button ng-disabled="!detectorData.length" style="width:19%;" type="button"  class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
					Download</button>
					<div class="dropdown-menu" style="top:51px !important;margin-top: 17.5%;">
						<input type="submit"   formtarget="_blank" name="generate_detector_documents" value="Excel"
						class="dropdown-item">
					</div>
				</div>
			</div>
			</form>
		
	</div>
	
	<div id="no-more-tables">
	    <form  method="POST" role="form" id="erpFilterMultiSearchDetectorForm" name="erpFilterMultiSearchDetectorForm" novalidate>
			<table class="col-sm-12 table-striped table-condensed cf">
				<thead class="cf">
					<tr>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('detector_code')">Detector Code  </label>
							<span class="sortorder" ng-show="predicate === 'detector_code'" ng-class="{reverse:reverse}"></span>						
						</th>			
						<th   class="width10">
							<label class="sortlabel" ng-click="sortBy('detector_name')">Detector Name </label>
							<span class="sortorder" ng-show="predicate === 'detector_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('detector_desc')">Detector Description </label>
							<span class="sortorder" ng-show="predicate === 'detector_desc'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment Type </label>
							<span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('p_category_name')">Parent Category</label>
							<span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('created_by')">Created By  </label>
							<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">
							<label class="sortlabel" ng-click="sortBy('status')">Status  </label>
							<span class="sortorder" ng-show="predicate === 'status'" ng-class="{reverse:reverse}"></span>						
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
						<td><input type="text" ng-change="getMultiSearch()" name="search_detector_code" ng-model="searchDetector.search_detector_code"  class="multiSearch form-control width80" placeholder="Code"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_detector_name" ng-model="searchDetector.search_detector_name"  class="multiSearch form-control width80" placeholder="Name"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_detector_desc" ng-model="searchDetector.search_detector_desc"  class="multiSearch form-control width80" placeholder="Description"></td>
						<td class="width10"></td>
						<td class="width10"><input type="text" ng-change="getMultiSearch()" name="search_p_category_name" 	  ng-model="searchDetector.search_p_category_name" class="multiSearch form-control width80" placeholder="Parent Category"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" 	  ng-model="searchDetector.search_created_by" class="multiSearch form-control width80" placeholder="Created By"></td>
						<td><select ng-change="getMultiSearch()" name="search_status" class="form-control multiSearch" ng-model="searchDetector.search_status" ng-options="status.name for status in statusList track by status.id"><option value="">All Status</option></select></td>

						<td><input type="text" ng-change="getMultiSearch()" name="search_created_at"  	  ng-model="searchDetector.search_created_at" class="multiSearch form-control visibility" placeholder="Created On"></td>
						<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" 	  ng-model="searchDetector.search_updated_at" class="multiSearch form-control visibility" placeholder="Updated On"></td>
						<td class="width10">
							<button ng-click="refreshMultisearch()" title="Refresh" value="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
							<button ng-click="closeMultisearch()" title="Close" value="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
						</td>
					</tr>
					<tr dir-paginate="obj in detectorData| filter:filterDetector| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
						<td data-title="detector Code">[[obj.detector_code]]</td>
						<td data-title="detector Name ">[[obj.detector_name]]</td>
						<td data-title="detector Detector Usage Time">
							<span id="detectorlimitedText-[[obj.detector_id]]">
								[[ obj.detector_desc | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
								<a href="javascript:;" ng-click="toggleDescription('detector',obj.detector_id)" ng-show="obj.detector_desc.length > 150" class="readMore">read more...</a>
							</span>
							<span id="detectorfullText-[[obj.detector_id]]" style="display:none;" >
								[[ obj.detector_desc]] 
								<a href="javascript:;" ng-click="toggleDescription('detector',obj.detector_id)" class="readMore">read less...</a>
							</span>
						</td>
						<td data-title="Equipment Type">[[obj.equipment_name]]</td>
						<td data-title="Parent Category Name ">[[obj.p_category_name]]</td>
						<td data-title="Created By">[[obj.createdBy]]</td>
						<td data-title="Status">[[obj.status | activeOrInactiveUsers]]</td>

						<td data-title="Created On">[[obj.created_at]]</td>
						<td data-title="Updated On">[[obj.updated_at]]</td>
						<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
							<a ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" title="Update" class="btn btn-primary btn-sm" ng-click='funEditDetector(obj.detector_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" title="Delete" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteMessage(obj.detector_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
					</tr>
					<tr ng-hide="detectorData.length" class="noRecord"><td colspan="5">No Record Found!</td></tr>
				</tbody>
			</table>
		</form>	
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>