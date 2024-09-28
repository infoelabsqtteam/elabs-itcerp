<div class="row header">
	<strong ng-click="getProductTestParaAltMethods(altTestId,altProductTestDtlId,altTestParameterId)" class="pull-left headerText" ng-bind="altHeading" ng-model="altHeading"></strong> 
	<div class="navbar-form navbar-right" role="search">
		<div class="nav-custom">	
		  <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchAlt">
	</div></div>
</div>	
<div class="row">
	<div id="no-more-tables">
		 <!-- show error message -->
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>						
					<th>
						<label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment Name </label>
						<span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('detector_name')">Detector Name </label>
						<span class="sortorder" ng-show="predicate === 'detector_name'" ng-class="{reverse:reverse}"></span>						
					</th>	
					<th>
						<label class="sortlabel" ng-click="sortBy('method_name')">Method Name</label>
						<span class="sortorder" ng-show="predicate === 'method_name'" ng-class="{reverse:reverse}"></span>						
					</th>					
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('time_taken_days')">Time Taken <sub>(in days)</sub></label>
						<span class="sortorder" ng-show="predicate === 'time_taken_days'" ng-class="{reverse:reverse}"></span>						
					</th>						
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('time_taken_mins')">Time Taken<sub>(in hours:minutes)</sub></label>
						<span class="sortorder" ng-show="predicate === 'time_taken_mins'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('standard_value_from')">Standard Value From </label>
						<span class="sortorder" ng-show="predicate === 'standard_value_from'" ng-class="{reverse:reverse}"></span>						
					</th>					
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('standard_value_to')">Standard Value To </label>
						<span class="sortorder" ng-show="predicate === 'standard_value_to'" ng-class="{reverse:reverse}"></span>						
					</th>					
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('cost_price')">Cost Price</label>
						<span class="sortorder" ng-show="predicate === 'cost_price'" ng-class="{reverse:reverse}"></span>						
					</th>						
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('selling_price')">Selling Price</label>
						<span class="sortorder" ng-show="predicate === 'selling_price'" ng-class="{reverse:reverse}"></span>						
					</th>	
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('created_at')">Created By</label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>	
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10"><label>Action
					<a ng-if="allAltMethodList.length" href="javascript:;" title="Filter" ng-hide="multisearchBtnParaAlt" ng-click="openMultisearchParaAlt()" class="btn btn-primary mL10"><i class="fa fa-filter"></i></a></label>
				</tr>
			</thead>
			<tbody>
				<tr ng-hide="multiSearchTrParaAlt">
					<td><input type="text" placeholder="Equipment"  name="search_equipment_name" 	   ng-model="searchStdTestParaAlt.search_equipment_name"        class="multiSearch form-control"></td>
					<td><input type="text" placeholder="Method"  	 name="search_detector_name" 		   ng-model="searchStdTestParaAlt.search_detector_name"    	    class="multiSearch form-control"></td>				

					<td><input type="text" placeholder="Method"  	 name="search_method_name" 		   ng-model="searchStdTestParaAlt.search_method_name"    	    class="multiSearch form-control"></td>					
				    <td><input type="text" placeholder="Time(in days)" 		  name="search_time_taken_days" ng-model="searchStdTestParaAlt.search_time_taken_days" 	class="multiSearch form-control"></td>
					<td><input type="text" placeholder="Time(in hours:minutes)" name="search_time_taken_mins" ng-model="searchStdTestParaAlt.search_time_taken_mins" 	class="multiSearch form-control"></td>			
					<td><input type="text" placeholder="Std Value From"  name="search_standard_value_from" ng-model="searchStdTestParaAlt.search_standard_value_from" class="multiSearch form-control "></td>					
					<td><input type="text" placeholder="Std Value To"    name="search_standard_value_to"   ng-model="searchStdTestParaAlt.search_standard_value_to"   class="multiSearch form-control "></td>
					<td><input type="text" placeholder="Cost Price"      name="search_cost_price" 	 ng-model="searchStdTestParaAlt.search_cost_price" 	class="multiSearch form-control "></td>
					<td><input type="text" placeholder="Selling Price"   name="search_selling_price" ng-model="searchStdTestParaAlt.search_selling_price"  class="multiSearch form-control "></td>
					<td><input type="text" placeholder="Created By"      name="search_created_by" 	 ng-model="searchStdTestParaAlt.search_created_by"	    class="multiSearch form-control "></td>
					<td><input type="text" placeholder="Created At"      name="search_created_at"    ng-model="searchStdTestParaAlt.search_created_at" 	class="multiSearch form-control visibility"></td>
					<td class="width10">
						<a href="javascript:;" ng-click="getMultiSearchParaAlt(alt_test_id,alt_dtl_id,alt_test_parameter_id)" class="btn btn-primary btn-sm"><i class="fa fa-search" aria-hidden="true"></i></a>
						<a href="javascript:;" ng-click="refreshMultisearchParaAlt(alt_test_id,alt_dtl_id,alt_test_parameter_id)" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></a>
						<a href="javascript:;" ng-click="closeMultisearchParaAlt()" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></a>
					</td>
				</tr>
				<tr ng-repeat="obj in allAltMethodList | filter:searchAlt | orderBy:predicate:reverse">
					<td data-title="Equipment Name">[[obj.equipment_name?obj.equipment_name:'-']]</td>
					<td data-title="Equipment Name">[[obj.detector_name?obj.detector_name:'-']]</td>
					<td data-title="Method Name">[[obj.method_name?obj.method_name:'']]</td>
					<td data-title="Time Taken Days">[[obj.time_taken_days?obj.time_taken_days+' Days':'']]</td>
					<td data-title="Time Taken Mins">[[obj.time_taken_mins?obj.time_taken_mins:'']]</td>
					<td data-title="Standard Value From">[[obj.standard_value_from?obj.standard_value_from:'']]</td>
					<td data-title="Standard Value To">[[obj.standard_value_to?obj.standard_value_to:'']]</td>
					<td data-title="Cost Price">[[obj.cost_price?obj.cost_price:'']]</td>
					<td data-title="Selling Price">[[obj.selling_price?obj.selling_price:'']]</td>
					<td data-title="Created By">[[obj.createdBy]]</td>
					<td data-title="Created On">[[obj.created_at]]</td>
					<td class="width10" ng-if="{{(defined('EDIT') && EDIT) || (defined('DELETE') && DELETE)}}">
						<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" class="btn btn-primary btn-sm" ng-click='editAltMethodRecord(obj.product_test_param_altern_method_id,obj.equipment_id)' title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteAltMethodMessage(obj.product_test_param_altern_method_id,obj.test_id,obj.product_test_dtl_id,obj.test_parameter_id)' title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>	
				<tr ng-if="!allAltMethodList.length" class="noRecord"><td colspan="13">No Record Found!</td></tr>
					
			</tbody>
		</table>
	</div>
</div>