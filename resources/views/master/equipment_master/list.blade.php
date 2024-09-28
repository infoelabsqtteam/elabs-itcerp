<div class="row" ng-hide="IsViewEquipmentTypeList">
    
    <div class="row header">
	<strong class="pull-left headerText" ng-click="getEquipments()" title="Refresh">Equipment Types <span ng-if="equipData.length">([[equipData.length]])</span></strong>
		<form class="form-inline ng-pristine ng-valid" method="POST" role="form" id="erpFilterEquipmentForm" name="erpFilterEquipmentForm" action="{{url('master/equipments/download')}}">
				{{ csrf_field() }}
			<div class="navbar-form navbar-right" role="search">
				<div class="nav-custom">
					<input type="text" class="seachBox form-control ng-pristine ng-valid ng-touched" placeholder="Search" ng-model="filterEquipment">
					<button ng-disabled="!equipData.length" style="" type="button"  class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
							Download</button>
					<div class="dropdown-menu" style="top:34px !important;margin-top: 16.7%;">
						<input type="submit"   formtarget="_blank" name="generate_equipment_documents" value="Excel"
						class="dropdown-item">
					</div>
				</div>
				
			</div>
		</form>
    </div>
	
    <div class="row">
	<div id="no-more-tables">
	    <table class="col-sm-12 table-striped table-condensed cf">
		<thead class="cf">
		    <tr>
			 <th>
				 <label class="sortlabel" ng-click="sortBy('equipment_code')">Equipment Code  </label>
				 <span class="sortorder" ng-show="predicate === 'equipment_code'" ng-class="{reverse:reverse}"></span>						
			 </th>			
			 <th>
				 <label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment Name </label>
				 <span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>						
			 </th>
			 <th>
				 <label class="sortlabel" ng-click="sortBy('equipment_capacity')">Equipment Capacity </label>
				 <span class="sortorder" ng-show="predicate === 'equipment_capacity'" ng-class="{reverse:reverse}"></span>						
			 </th>
			 <th>
				 <label class="sortlabel" ng-click="sortBy('equipment_description')">Equipment Description </label>
				 <span class="sortorder" ng-show="predicate === 'equipment_description'" ng-class="{reverse:reverse}"></span>						
			 </th>	
			 <th class="width10">
				 <label class="sortlabel" ng-click="sortBy('created_by')">Created By  </label>
				 <span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
			 </th>
			 <th class="width10">
				<label class="sortlabel" ng-click="sortBy('Status')">Status  </label>
				<span class="sortorder" ng-show="predicate === 'status'" ng-class="{reverse:reverse}"></span>						
			</th>					
			 <th class="width8">
				 <label class="sortlabel" ng-click="sortBy('created_at')">Created On </label>
				 <span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
			 </th>
			 <th class="width8">
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
			<td><input type="text" ng-change="getMultiSearch()" name="search_equipment_code"  ng-model="searchEquipment.search_equipment_code" 	class="multiSearch form-control width80" placeholder="Eq. Code"></td>
			<td><input type="text" ng-change="getMultiSearch()" name="search_equipment_name"  ng-model="searchEquipment.search_equipment_name" 	class="multiSearch form-control width80" placeholder="Eq. Name"></td>
			<td><input type="text" ng-change="getMultiSearch()" name="search_equipment_desc"  ng-model="searchEquipment.search_equipment_desc"  class="multiSearch form-control width80" placeholder="Eq. Description"></td>
			<td></td>
			<td><input type="text" ng-change="getMultiSearch()" name="search_created_by" 	  ng-model="searchEquipment.search_created_by" 	    class="multiSearch form-control width80" placeholder="Created By"></td>
		
			<td><select ng-change="getMultiSearch()" name="search_status" class="form-control multiSearch" ng-model="searchEquipment.search_status" ng-options="status.name for status in statusList track by status.id"><option value="">All Status</option></select></td>

			</td>
			<td><input type="text" ng-change="getMultiSearch()" name="search_created_at"  	  ng-model="searchEquipment.search_created_at" 	    class="multiSearch form-control visibility"  placeholder="Created On"></td>
			<td><input type="text" ng-change="getMultiSearch()" name="search_updated_at" 	  ng-model="searchEquipment.search_updated_at" 	    class="multiSearch form-control visibility" placeholder="Updated On"></td>
			<td class="width10">
			    <button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
			    <button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
			</td>
		     </tr>
		     <tr dir-paginate="obj in equipData | filter:filterEquipment | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" >
			<td data-title="Equipment Code">[[obj.equipment_code]]</td>
			<td data-title="Equipment Name">[[obj.equipment_name]]</td>
			<td data-title="Equipment Capacity">[[obj.equipment_capacity]]</td>
			<td data-title="Equipment Description">
			    <span id="equipmentlimitedText-[[obj.equipment_id]]">
				[[ obj.equipment_description | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
				<a href="javascript:;" ng-click="toggleDescription('equipment',obj.equipment_id)" ng-show="obj.equipment_description.length > 150" class="readMore">read more...</a>
			    </span>
			    <span id="equipmentfullText-[[obj.equipment_id]]" style="display:none;" >
				[[ obj.equipment_description]] 
				<a href="javascript:;" ng-click="toggleDescription('equipment',obj.equipment_id)" class="readMore">read less...</a>
			    </span>
			</td>
			<td data-title="Created By">[[obj.createdBy]]</td>
			<td data-title="Created By">[[obj.status | activeOrInactiveUsers]]</td>

			<td data-title="Created On">[[obj.created_at]]</td>
			<td data-title="Updated On">[[obj.updated_at]]</td>
			<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">						
			    <a title="Update" ng-if="{{defined('EDIT') && EDIT}}" href="javascript:;" class="btn btn-primary btn-sm" ng-click='editEquipment(obj.equipment_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>			
			    <a title="Delete" ng-if="{{defined('DELETE') && DELETE}}" href="javascript:;" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(obj.equipment_id)"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
			</td>
		     </tr>
		     <tr ng-hide="equipData.length" class="noRecord text-left"><td colspan="8">No Record Found!</td></tr>
		</tbody>
		<tfoot>
		    <tr><td class="text-left" colspan="8"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td></tr>
		</tfoot>
	   </table>
	   		  
	</div>
    </div>
</div>	