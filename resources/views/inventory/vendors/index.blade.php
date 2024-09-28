@extends('layouts.app')

@section('content')
    
<div ng-controller="vendorsController" class="container ng-scope" ng-init="funGetDivWiseVendorsList({{$division_id}})">
    
    <!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
    
    <!--Vendor Listing-->
    <div class="row" ng-hide="IsViewVendorList" id="list_orders_div">        
		<!--search-->
		<div class="row header">        
            <div role="new" class="navbar-form navbar-left">            
                <div><strong id="form_title" ng-click="funGetDivWiseVendorsList(divisionID)" title="Refresh">Vendor Listing<span ng-if="vendorListData.length">([[vendorListData.length]])</span></strong></div>            
            </div>            
            <div role="new" class="navbar-form navbar-right">
                <div style="margin: -5px; padding-right: 9px;">
                    <input type="text" placeholder="Search" ng-model="searchVendors" class="form-control ng-pristine ng-untouched ng-valid">
                    <select ng-if="{{$division_id}} == 0" class="form-control " ng-model="division" ng-options="division.name for division in divisionsCodeList track by division.division_id" ng-change="funGetDivWiseVendorsList(division.division_id)">
						<option value="">All Branch</option>
					</select>
					<a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" ng-click="openNewVendorForm()" class="btn btn-primary mB5" id="add_new_vendor">Add New</a>
                </div>
            </div>
        </div>
        <!--/search-->    
            
        <!--display record--> 
        <div class="row" id="no-more-tables">
            <table class="col-sm-12 table-striped table-condensed cf">
                <thead class="cf">
                    <tr>                            
                        <th>
                            <label ng-click="sortBy('vendor_code')" class="sortlabel">Vendor Code </label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'vendor_code'" class="sortorder reverse"></span>
                        </th>
						<th ng-if="{{$division_id}} == 0">
							<label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
							<span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
						</th>
						<th>
                            <label ng-click="sortBy('vendor_name')" class="sortlabel">Vendor Name</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'vendor_name'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('vendor_email')" class="sortlabel">Vendor Email</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'vendor_email'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('created_by')" class="sortlabel">Created By</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'created_by'" class="sortorder reverse ng-hide"></span>
                        </th>   
                        <th>
                            <label ng-click="sortBy('created_at')" class="sortlabel">Created On</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'created_at'" class="sortorder reverse ng-hide"></span>
                        </th>   
                        <th>
                            <label ng-click="sortBy('created_at')" class="sortlabel">Updated On</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'created_at'" class="sortorder reverse ng-hide"></span>
                        </th>                                             
                        <th class="width10">Action</th>
                    </tr>
                </thead>
                <tbody>
					<tr dir-paginate="vendorListDataObj in vendorListData | filter:searchVendors | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                        <td data-title="vendor_code" class="ng-binding">[[vendorListDataObj.vendor_code]]</td>
						<td ng-if="{{$division_id}} == 0" data-title="division_name">[[vendorListDataObj.division_name]]</td>
						<td data-title="vendor_name" class="ng-binding">[[vendorListDataObj.vendor_name]]</td>                        
                        <td data-title="vendor_email" class="ng-binding">[[vendorListDataObj.vendor_email]]</td>
						<td data-title="Created By">[[vendorListDataObj.createdBy]]</td>
						<td data-title="Created At">[[vendorListDataObj.created_at]]</td>
						<td data-title="Created At">[[vendorListDataObj.updated_at]]</td>
						<td class="width10">
                            <a href="javascript:;" ng-if="{{defined('VIEW') && VIEW}}" ng-click="funViewVendor(vendorListDataObj.vendor_id)" class="btn btn-primary btn-sm" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" ng-click="funEditVendor(vendorListDataObj.vendor_id)" class="btn btn-primary btn-sm" title="Update"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a href="javascript:;" ng-if="{{defined('DELETE') && DELETE}}" ng-click="funConfirmDeleteMessage(vendorListDataObj.vendor_id,divisionID)" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        </td>                
                    </tr>                        
                    <tr ng-if="!vendorListData.length"><td>No record found.</td></tr>
                </tbody>
				<tfoot>
					<tr>
						<td colspan="7">
							<div class="box-footer clearfix">
								<dir-pagination-controls></dir-pagination-controls>
							</div>
						</td>
					</tr>
				</tfoot>
            </table>		  
        </div>  
    </div>
    <!--/Vendor Listing-->
    
    <!--vendor form-->
    @include('inventory.vendors.add')
    <!--/vendor form-->
    
    <!--vendor detail-->
    @include('inventory.vendors.view')
    <!--/vendor detail-->
	
	<!--vendor detail-->
    @include('inventory.vendors.edit')
    <!--/vendor detail-->
    
</div>
    
<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/vendorsController.js') !!}"></script>

<style>
</style>
@endsection