<div class="row header">
	<strong class="pull-left headerText" ng-click="getProductTestParameters(currentTestId)" title="Refresh">Standard Wise Product Test Parameters <span ng-if="allParaList.length">([[allParaList.length]])</span></strong>	
	<div class="navbar-form navbar-right" role="search">
		<div class="nav-custom">
		  <input type="text" class="seachBox form-control ng-pristine ng-untouched ng-valid" placeholder="Search" ng-model="searchParameters">
		</div>
	</div>
</div>
<div class="row">
	<div id="no-more-tables">
		 <!-- show error message -->
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>														
					<th>
						<label class="sortlabel" ng-click="sortBy('test_parameter_name')">Parameter category</label>
						<span class="sortorder" ng-show="predicate === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>						
					</th>														
					<th>
						<label class="sortlabel" ng-click="sortBy('test_parameter_name')">Parameter</label>
						<span class="sortorder" ng-show="predicate === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>						
					</th>	
					<th>
						<label class="sortlabel" ng-click="sortBy('equipment_name')">Equipment</label>
						<span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('detector_name')">Detector</label>
						<span class="sortorder" ng-show="predicate === 'detector_name'" ng-class="{reverse:reverse}"></span>						
					</th>	
					<th>
						<label class="sortlabel" ng-click="sortBy('method_name')">Method</label>
						<span class="sortorder" ng-show="predicate === 'method_name'" ng-class="{reverse:reverse}"></span>						
					</th>						
					<th>
						<label class="sortlabel" ng-click="sortBy('time_taken_days')">Time Taken<br> <sub>(in days)</sub></label>
						<span class="sortorder" ng-show="predicate === 'time_taken_days'" ng-class="{reverse:reverse}"></span>						
					</th>						
					<th>
						<label class="sortlabel" ng-click="sortBy('time_taken_mins')">Time Taken<br><sub>(in hours:minutes)</sub></label>
						<span class="sortorder" ng-show="predicate === 'time_taken_mins'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('standard_value_from')">Std Value<br> From </label>
						<span class="sortorder" ng-show="predicate === 'standard_value_from'" ng-class="{reverse:reverse}"></span>						
					</th>					
					<th>
						<label class="sortlabel" ng-click="sortBy('standard_value_to')">Std Value<br> To </label>
						<span class="sortorder" ng-show="predicate === 'standard_value_to'" ng-class="{reverse:reverse}"></span>						
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
						<label class="sortlabel" ng-click="sortBy('parameter_decimal_place')">Decimal Place</label>
						<span class="sortorder" ng-show="predicate === 'parameter_decimal_place'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('parameter_nabl_scope')">NABL Scope</label>
						<span class="sortorder" ng-show="predicate === 'parameter_nabl_scope'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
						<span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
					</th>	
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('status')">Status</label>
						<span class="sortorder" ng-show="predicate === 'status'" ng-class="{reverse:reverse}"></span>						
					</th>	
					<th class="width8">
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th class="width10">
						Action
						<a ng-if="allParaList.length" href="javascript:;" title="Filter" ng-hide="multisearchBtnPara" ng-click="openMultisearchPara()" class="btn btn-primary mL10"><i class="fa fa-filter"></i></a>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-hide="multiSearchTrPara">
					<td></td>
					<td><input type="text" placeholder="Parameter Name" name="search_test_parameter_name"  ng-model="searchStdTestPara.search_test_parameter_name"   class="multiSearch form-control"></td>
					<td><input type="text" placeholder="Equipment Name" name="search_equipment_name" ng-model="searchStdTestPara.search_equipment_name"        class="multiSearch form-control"></td>
					<td><input type="text" placeholder="Detector Name" name="search_detector_name" ng-model="searchStdTestPara.search_detector_name"        class="multiSearch form-control"></td>	
					<td><input type="text" placeholder="Method Name" name="search_method_name" ng-model="searchStdTestPara.search_method_name"    	   class="multiSearch form-control"></td>					
					<td><input type="text" placeholder="Time Taken(in days)" name="search_time_taken_days" ng-model="searchStdTestPara.search_time_taken_days" 	   class="multiSearch form-control "></td>
					<td><input type="text" placeholder="Time Taken(in hours:minutes)" name="search_time_taken_mins" ng-model="searchStdTestPara.search_time_taken_mins" 	   class="multiSearch form-control "></td>
					<td><input type="text" placeholder="Std Value From" name="search_standard_value_from"  ng-model="searchStdTestPara.search_standard_value_from" class="multiSearch form-control "></td>					
					<td><input type="text" placeholder="Std Value To" name="search_standard_value_to"    ng-model="searchStdTestPara.search_standard_value_to"   class="multiSearch form-control "></td>
					<td><input type="text" placeholder="Cost Price" name="search_cost_price" ng-model="searchStdTestPara.search_cost_price" 	class="multiSearch form-control "></td>
					<td><input type="text" placeholder="Selling Price" name="search_selling_price" ng-model="searchStdTestPara.search_selling_price"  class="multiSearch form-control "></td>
					<td></td>
					<td></td>
					<td><input type="text" placeholder="Created By" name="search_created_by" ng-model="searchStdTestPara.search_created_by"	    class="multiSearch form-control "></td>
					
					<td><select ng-change="getMultiSearch()" name="search_status" class="form-control multiSearch" ng-model="searchStdTestPara.search_status" ng-options="status.name for status in statusList track by status.id"><option value="">All Status</option></select></td>
					<td><input type="text" placeholder="Created At" name="search_created_at" ng-model="searchStdTestPara.search_created_at" 	class="multiSearch form-control visibility"></td>
					<td class="width10">
						<a href="javascript:;" ng-click="getMultiSearchPara(allParaList_test_id)" class="btn btn-primary btn-sm"><i class="fa fa-search" aria-hidden="true"></i></a>
						<a href="javascript:;" ng-click="refreshMultisearchPara()" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></a>
						<a href="javascript:;" ng-click="closeMultisearchPara()" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></a>
					</td>
				</tr>
				<tr ng-repeat="obj in allParaList|filter:searchParameters | orderBy:predicate:reverse">
					<td data-title="Test Parameter Category">[[obj.test_para_cat_name ? obj.test_para_cat_name : '']]</td>
					<td data-title="Test Parameter" ng-bind-html="obj.test_parameter_name"></td>					
					<td class="text-center" ng-if="!obj.description.length" data-title="Equipment Name">[[obj.equipment_name ? obj.equipment_name :'']]</td>
					<td class="text-center" ng-if="!obj.description.length" data-title="Detector Name">[[obj.detector_name ? obj.detector_name :'']]</td>
					<td class="text-center" ng-if="!obj.description.length" data-title="Method Name">[[obj.method_name ? obj.method_name :'']]</td>
					<td ng-if="!obj.description.length" data-title="Time Taken Days">[[obj.time_taken_days ? obj.time_taken_days :'']]</td>
					<td ng-if="!obj.description.length" data-title="Time Taken Mins">[[obj.time_taken_mins ? obj.time_taken_mins :'']]</td>
					<td ng-if="!obj.description.length" data-title="Standard Value From">[[obj.standard_value_from ? obj.standard_value_from :'']]</td>
					<td ng-if="!obj.description.length" data-title="Standard Value To">[[obj.standard_value_to ? obj.standard_value_to :'']]</td>
					<td ng-if="!obj.description.length" data-title="Cost Price">[[obj.cost_price ? obj.cost_price :'']]</td>
					<td ng-if="!obj.description.length" data-title="Selling Price">[[obj.selling_price ? obj.selling_price :'']]</td>
					<td ng-if="!obj.description.length" data-title="Decimal Place">[[obj.parameter_decimal_place ? obj.parameter_decimal_place : '']]</td>
					<td ng-if="!obj.description.length" data-title="NABL Scope">
						<span ng-if="obj.parameter_nabl_scope == '1'">Yes</span>
						<span ng-if="obj.parameter_nabl_scope == '0'">No</span>
					</td>	
					<td ng-if="obj.description.length" colspan="11" data-title="[[obj.test_parameter_name]]">[[obj.description?obj.description:'']]</td>
					<td data-title="Created By">[[obj.createdBy]]</td>
					<td data-title="Status">[[obj.status | activeOrInactiveUsers]]</td>
					<td data-title="Created On">[[obj.created_at]]</td>
					<td class="width10">
						<a ng-if="!obj.description.length" href="javascript:;" ng-if="{{defined('ADD') && ADD}}" title="Add Alternate Method" ng-hide="addAltMethodBtn" class="btn btn-primary btn-sm" ng-click='addAlternateMethod(obj.test_id,obj.product_test_dtl_id,obj.test_parameter_id,obj.equipment_id)'><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a href="javascript:;"  ng-if="{{defined('EDIT') && EDIT}}" title="Edit" class="btn btn-primary btn-sm" ng-click='editParameterRecord(obj.product_test_dtl_id,obj.test_id,obj.test_parameter_category,obj.test_parameter_id,obj.equipment_id)'><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a title="Delete" ng-if="{{defined('DELETE') && DELETE}}" class="btn btn-danger btn-sm" ng-click='funConfirmDeleteTestParameterMessage(obj.product_test_dtl_id,obj.test_id)'><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>	
				<tr ng-if="!allParaList.length" class="noRecord"><td colspan="13">No Record Found!</td></tr>
			</tbody>
		</table>
	</div>
</div>