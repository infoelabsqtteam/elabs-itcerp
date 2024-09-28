@extends('layouts.app')

@section('content')
	
<div class="container" ng-controller="PurchaseOrdersController" ng-init="funGetDivisionWisePurchaseOrders({{$division_id}},1)">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--Display of Item Listing-->
	<div class="row header" ng-hide="isVisibleListPODiv">
	
		<div role="new" class="navbar-form navbar-left">            
			<span class="pull-left"><strong id="form_title">Purchase Order Listing</strong></span>
		</div>            
		<div role="new" class="navbar-form navbar-right">
			<div class="nav-custom">
				<input type="text" placeholder="Search" ng-model="searchPurchaseOrders" class="form-control ng-pristine ng-untouched ng-valid">
                <select class="form-control" ng-model="dpoPoTypeList.selectedOption" ng-options="poType.name for poType in dpoPoTypeList.availableTypeOptions track by poType.id" ng-change="funGetDivisionWisePurchaseOrders(divisionID,dpoPoTypeList.selectedOption.id)">
                </select>
				<select ng-if="{{$division_id}} == 0" ng-init="funGetDivisions()" class="form-control" ng-model="division" ng-options="selectDivision.name for selectDivision in divisionsCodeList track by selectDivision.division_id" ng-change="funGetDivisionWisePurchaseOrders(division.division_id,dpoPoType)">
					<option value="">All Branch</option>
                </select>
				<a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" ng-click="navigateItemPage();" class="btn btn-primary">Add New</a>
			</div>
		</div>
	
        <div id="no-more-tables">
            <table class="col-sm-12 table-striped table-condensed cf">
        		<thead class="cf">
        			<tr>
						<th ng-if="dpoPoType == 1">
							<label class="sortlabel" ng-click="sortBy('po_no')">PO No.</label>
							<span class="sortorder" ng-show="predicate === 'po_no'" ng-class="{reverse:reverse}"></span>						
						</th>						
						<th ng-if="dpoPoType == 1">
							<label class="sortlabel" ng-click="sortBy(po_date)">PO Date</label>
							<span class="sortorder" ng-show="predicate === 'po_date'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th ng-if="dpoPoType == 2">
							<label class="sortlabel" ng-click="sortBy('dpo_no')">DPO No.</label>
							<span class="sortorder" ng-show="predicate === 'dpo_no'" ng-class="{reverse:reverse}"></span>						
						</th>						
						<th ng-if="dpoPoType == 2">
							<label class="sortlabel" ng-click="sortBy(dpo_date)">DPO Date</label>
							<span class="sortorder" ng-show="predicate === 'dpo_date'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
							<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('vendor_name')">Vendor Name</label>
							<span class="sortorder" ng-show="predicate === 'vendor_name'" ng-class="{reverse:reverse}"></span>						
						</th>						
						<th ng-if="dpoPoType == 1">
							<label class="sortlabel" ng-click="sortBy('amendment_date')">Amendment Date</label>
							<span class="sortorder" ng-show="predicate === 'amendment_date'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th ng-if="dpoPoType == 1">
							<label class="sortlabel" ng-click="sortBy('short_close_date')">Shortclose Date</label>
							<span class="sortorder" ng-show="predicate === 'short_close_date'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('grand_total')">Grand Total(Rs.)</label>
							<span class="sortorder" ng-show="predicate === 'grand_total'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
							<label class="sortlabel" ng-click="sortBy('po_status')">Status</label>
							<span class="sortorder" ng-show="predicate === 'po_status'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th colspan="2"></th>
        			</tr>
        		</thead>
        		<tbody>
                    <tr dir-paginate="purchaseOrderObj in purchaseOrderList| filter:searchPurchaseOrders| itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
						<td ng-if="dpoPoType == 1" data-title="PO Number">
							<a href="javascript:;" ng-click="funViewDpoOrPO(purchaseOrderObj.po_hdr_id)">[[purchaseOrderObj.po_no]]</a>
						</td>
						<td ng-if="dpoPoType == 1" data-title="PO Date">[[purchaseOrderObj.po_date]]</td>
						<td ng-if="dpoPoType == 2" data-title="DPO Number">
							<a href="javascript:;" ng-click="funViewDpoOrPO(purchaseOrderObj.po_hdr_id)">[[purchaseOrderObj.dpo_no]]</a>
						</td>
						<td ng-if="dpoPoType == 2" data-title="DPO Date">[[purchaseOrderObj.dpo_date]]</td>
						<td data-title="Branch Name">[[purchaseOrderObj.division_name | capitalize]]</td>
						<td data-title="Vendor Name">[[purchaseOrderObj.vendor_name | capitalize]]</td>
						<td ng-if="dpoPoType == 1" data-title="Amendment Date" class="ng-binding">
							<span ng-if="purchaseOrderObj.amendment_date">[[purchaseOrderObj.amendment_date | date : 'dd/MM/yyyy']]</span>
							<span class="text-center" ng-if="!purchaseOrderObj.amendment_date">-</span>
						</td>
						<td ng-if="dpoPoType == 1" data-title="Shortclose Date" class="ng-binding">
							<span ng-if="purchaseOrderObj.short_close_date">[[purchaseOrderObj.short_close_date | date : 'dd/MM/yyyy']]</span>
							<span class="text-center" ng-if="!purchaseOrderObj.short_close_date">-</span>
						</td>
						<td data-title="Grand Total">[[purchaseOrderObj.grand_total]]</td>
						<td data-title="Status">
							<span ng-if="purchaseOrderObj.status == 1" class="po-open">[[purchaseOrderObj.po_status_name | uppercase]]</span>
							<span ng-if="purchaseOrderObj.status == 2" class="po-closed">[[purchaseOrderObj.po_status_name | uppercase]]</span>
						</td>
						<td class="tdLeft tdWidth20">						
							<a href="javascript:;" ng-if="{{defined('VIEW') && VIEW}}" title="View" class="btn btn-info btn-sm" ng-click="funViewDpoOrPO(purchaseOrderObj.po_hdr_id)"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" title="Delete" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(purchaseOrderObj.po_hdr_id,divisionID,dpoPoType)"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</td>
        			</tr>
					<tr ng-if="!purchaseOrderList.length" class="noRecord"><td colspan="8">No Record Found!</td></tr>
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
	@include('inventory.purchase_orders.add')
    <!--/Add Form-->
    
    <!--Edit Form-->
	@include('inventory.purchase_orders.edit')
    <!--/Edit Form-->
	
	<!--Edit Form-->
	@include('inventory.purchase_orders.view')
    <!--/Edit Form-->
    
    <script type="text/javascript" src="{!! asset('public/ang/controller/PurchaseOrdersController.js') !!}"></script>
		
</div>
@endsection