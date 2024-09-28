@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="DivisionWiseItemsController" ng-init="funBranchWiseItems({{$division_id}})">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--Display of Item Listing-->
	<div class="row header">
	
		<div role="new" class="navbar-form navbar-left">            
			<span class="pull-left"><strong id="form_title" ng-Click="funBranchWiseItems(divisionID)" title="Refresh">Branch Wise Items Listing([[itemDataDivisionList.length]])</strong></span>
		</div>            
		<div role="new" class="navbar-form navbar-right">
			<div class="searchbox">
				<input type="text" placeholder="Search" ng-model="searchBranchItems" class="form-control ng-pristine ng-untouched ng-valid">
				@if(empty($division_id))
					<select class="form-control" ng-model="division.division_id" id="division_id" name="division_id" ng-options="division.name for division in divisionsCodeList track by division.id" ng-change="funBranchWiseItems(division.division_id.id)">
						<option value="">All Branch</option>
					</select>
				@endif
			</div>
		</div>
	
        <div id="no-more-tables" ng-hide="listBranchItemFormDiv">
            <table class="col-sm-12 table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('item_code')">Item Code</label>
							<span class="sortorder" ng-show="predicate === 'item_code'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('item_name')">Item Name</label>
							<span class="sortorder" ng-show="predicate === 'item_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
							<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('created_at')">Added On</label>
							<span class="sortorder" ng-show="predicate === 'created_at'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('updated_at')">Modified On</label>
							<span class="sortorder" ng-show="predicate === 'updated_at'" ng-class="{reverse:reverse}"></span>						
						</th>
                        <th>
							<label class="sortlabel" ng-click="sortBy('msl')">MSL  </label>
							<span class="sortorder" ng-show="predicate === 'msl'" ng-class="{reverse:reverse}"></span>						
						</th>							
						<th>
							<label class="sortlabel" ng-click="sortBy('rol')">ROL</label>
							<span class="sortorder" ng-show="predicate === 'rol'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th colspan="2">							
							<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update All" class="btn btn-primary btn-sm" ng-click="navigateItemPage(divisionID)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> All</a>
						</th>
        			</tr>
        		</thead>
        		<tbody>
                    <tr dir-paginate="itemDataListObj in itemDataDivisionList | filter:searchBranchItems | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse" ng-include="getBranchWiseItemTemplate(itemDataListObj.division_wise_item_id)"></tr>
					<tr ng-if="!itemDataDivisionList.length" class="noRecord"><td colspan="8">No Record Found!</td></tr>
        		</tbody>
				<tfoot ng-if="listBranchItemFormDivPaginate">
					<tr>
						<td colspan="8">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
        	</table>
			
			 <script type="text/ng-template" id="display">
                <td data-title="Item Code">[[itemDataListObj.item_code]]</td>						
                <td data-title="Item Name ">[[itemDataListObj.item_name]]</td>
				<td data-title="Item Name ">[[itemDataListObj.division_name]]</td>
                <td data-title="Added On">[[itemDataListObj.created_at]]</td>
                <td data-title="Updated">[[itemDataListObj.updated_at]]</td>
                <td data-title="MSL">[[itemDataListObj.msl ? itemDataListObj.msl : '-']]</td>
                <td data-title="ROL">[[itemDataListObj.rol ? itemDataListObj.rol : '-']]</td>
                <td class="width10">						
                    <a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" title="Update" class="btn btn-info btn-sm" ng-click="funEditBranchItem(itemDataListObj)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                </td>
            </script>
                
            <script type="text/ng-template" id="edit">
                <td data-title="Item Code">[[itemDataListObj.item_code]]</td>						
                <td data-title="Item Name ">[[itemDataListObj.item_name]]</td>
				<td data-title="Item Name ">[[itemDataListObj.division_name]]</td>
                <td data-title="Added On">[[itemDataListObj.created_at]]</td>
                <td data-title="Updated">[[itemDataListObj.updated_at]]</td>
                <td data-title="MSL">                    
                    <input type="text" class="form-control" ng-model="editBranchWise.msl" name="msl" placeholder="MSL">
                </td>						
                <td data-title="ROL">                    
                    <input type="text" class="form-control" ng-model="editBranchWise.rol" name="rol" placeholder="ROL">
                </td>						
                <td class="width155">
					@if(empty($division_id))
						<button title="Save" class="btn btn-info btn-sm" ng-click="funSaveBranchItem(itemDataListObj.division_wise_item_id,division.division_id.id)">Save</button>
                    @else
						<button title="Save" class="btn btn-info btn-sm" ng-click="funSaveBranchItem(itemDataListObj.division_wise_item_id,divisionID)">Save</button>
					@endif
					<button title="Close" class="btn btn-info btn-sm" ng-click="resetButton();">Close</button>
                </td>
            </script>				
		</div>
			
		<!--bulk Update of records-->
		@include('inventory.division_wise_items.updates')
		<!--/bulk Update of records-->		
			
	</div>
	<!--/Display of Item Listing-->
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/DivisionWiseItemsController.js') !!}"></script>
@endsection