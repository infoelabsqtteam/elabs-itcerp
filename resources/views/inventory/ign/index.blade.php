@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="IgnsController" ng-init="funGetDivisionWiseIgnList({{$division_id}})">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--Display of Item Listing-->
	<div class="row header" ng-hide="isVisibleListIGNDiv">
        <div role="new" class="navbar-form navbar-left">            
			<span class="pull-left"><strong id="form_title">IGN Listing</strong></span>
		</div>            
		<div role="new" class="navbar-form navbar-right">
			<div class="nav-custom">
				<input type="text" placeholder="Search" ng-model="searchIGNs" class="form-control ng-pristine ng-untouched ng-valid">
				<select ng-if="{{$division_id}} == 0" ng-init="funGetDivisions()" class="form-control" ng-model="division" ng-options="selectDivision.name for selectDivision in divisionsCodeList track by selectDivision.division_id" ng-change="funGetDivisionWiseIgnList(division.division_id,dpoPoType)">
					<option value="">All Branch</option>
                </select>
				<a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" ng-click="navigateItemPage(1);" class="btn btn-primary">Add New</a>
			</div>
		</div>
	
        <div id="no-more-tables">
            <table class="col-sm-12 table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
						<th>
							<label class="sortlabel" ng-click="sortBy('ign_no')">IGN No.</label>
							<span class="sortorder" ng-show="predicate === 'ign_no'" ng-class="{reverse:reverse}"></span>						
						</th>						
						<th>
							<label class="sortlabel" ng-click="sortBy(ign_date)">IGN Date</label>
							<span class="sortorder" ng-show="predicate === 'ign_date'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
							<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('vendor_name')">Vendor Name</label>
							<span class="sortorder" ng-show="predicate === 'vendor_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th class="width10">Action</th>
        			</tr>
        		</thead>
        		<tbody>
                    <tr dir-paginate="IGNDataObj in IGNDataList| filter:searchIGNs| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
						<td data-title="IGN Number">[[IGNDataObj.ign_no]]</td>
						<td data-title="IGN Date">[[IGNDataObj.ign_date | date : 'dd/MM/yyyy']]</td>
						<td data-title="Division Name">[[IGNDataObj.division_name | capitalize]]</td>
						<td data-title="Vendor Name">[[IGNDataObj.vendor_name | capitalize]]</td>						
						<td class="width10">						
							<a href="javascript:;" ng-if="{{defined('VIEW') && VIEW}}" title="View" class="btn btn-info btn-sm" ng-click="funViewIGNDetail(IGNDataObj.ign_hdr_id)"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(IGNDataObj.ign_hdr_id,divisionID)"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
        			</tr>
					<tr ng-if="!IGNDataList.length" class="noRecord"><td colspan="8">No Record Found!</td></tr>
        		</tbody>
				<tfoot>
					<tr>
						<td colspan="8">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
        	</table>					  
		</div>
    </div>
	<!--/Display of Item Listing-->
    
    <!--Add Form-->
	@include('inventory.ign.add')
    <!--/Add Form-->
	
	<!--Add Form-->
	@include('inventory.ign.view')
    <!--/Add Form-->
	
	<!--Add Form-->
	@include('inventory.ign.edit')
    <!--/Add Form-->
    
    <!--Including IgnsController-->
    <script type="text/javascript" src="{!! asset('public/ang/controller/IgnsController.js') !!}"></script>
    <!--Including IgnsController-->		
</div>
@endsection