<div class="row">
	<div id="no-more-tables">
		 <!-- show error message -->
		<table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf">
				<tr>
					<th>
						<label class="sortlabel" ng-click="sortBy('from_unit')">From Unit  </label>
						<span class="sortorder" ng-show="predicate === 'from_unit'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('to_unit')">To Unit </label>
						<span class="sortorder" ng-show="predicate === 'to_unit'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('confirm_factor')">Confirm Factor </label>
						<span class="sortorder" ng-show="predicate === 'confirm_factor'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('created_at')">Created On</label>
						<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th>
						<label class="sortlabel" ng-click="sortBy('updated_at')">Updated On</label>
						<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
					</th>
					<th colspan="2"></th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="obj in unitcondata| filter:searchUnitCon| itemsPerPage: 5 | orderBy:predicate:reverse" >
					<td data-title="Unit Code">[[obj.from_unit]]</td>
					<td data-title="Unit Name ">[[obj.to_unit]]</td>
					<td data-title="Unit Description ">[[obj.confirm_factor]]</td>
					<td data-title="Unit Description ">[[obj.created_at]]</td>
					<td data-title="Unit Description ">[[obj.updated_at]]</td>
					<td><button  title="Edit" class="btn btn-primary btn-sm" ng-click='editConUnit(obj.unit_conversion_id)'>Edit</button></td>
					<td><button title="Delete" class="btn btn-danger btn-sm" ng-click='deleteConUnit(obj.unit_conversion_id)'>Delete</button></td>
				</tr>
				<tr ng-hide="unitcondata.length"  class="noRecord"><td colspan="5">No Record Found!</td></tr>
			</tbody>
		</table>
		
		<div class="box-footer clearfix">
			<dir-pagination-controls></dir-pagination-controls>
		</div>		  
	</div>
</div>