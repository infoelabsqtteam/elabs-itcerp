<div class="row" ng-hide="listPaymentFormBladeDiv">
    
    <!--search-->
    <div class="row header">        
        <div role="new" class="navbar-form navbar-left">            
            <div><strong id="form_title">Payments Made Listing<span ng-if="paymentsMadeList.length">([[paymentsMadeList.length]])</span></strong></div>
        </div>            
        <div role="new" class="navbar-form navbar-right">
            <div class="nav-custom">
                <input type="text" placeholder="Search" ng-model="filterPaymentMade" class="form-control ng-pristine ng-untouched ng-valid">
                <select ng-if="{{$division_id}} == 0" class="form-control" ng-model="divisions" ng-options="division.name for division in divisionsCodeList track by division.division_id" 
                ng-change="funGetBranchWisePaymentMade(divisions.division_id)">
                    <option value="">All Branch</option>
                </select>
                <span ng-if="{{defined('ADD') && ADD}}">
                    <button type="button" ng-click="navigatePaymentMadePage()" class="btn btn-primary">Add New</button>
                </span>
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
                        <label ng-click="sortBy('payment_made_no')" class="sortlabel">Payment Made No.</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'payment_made_no'" class="sortorder reverse"></span>
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
                        <label ng-click="sortBy('payment_made_date')" class="sortlabel">Payment Made Date </label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'payment_made_date'" class="sortorder reverse ng-hide"></span>
                    </th>
                    <th class="width8">
                        <label ng-click="sortBy('payment_made_amount')" class="sortlabel">Amount Made(Rs.)</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'payment_made_amount'" class="sortorder reverse ng-hide"></span>
                    </th> 
                    <th class="width8">
                        <label ng-click="sortBy('payment_source_name')" class="sortlabel">Paid From</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'payment_source_name'" class="sortorder reverse ng-hide"></span>
                    </th>	
                    <th class="width8">
                        <label ng-click="sortBy('createdByName')" class="sortlabel">Created By</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'createdByName'" class="sortorder reverse ng-hide"></span>
                    </th>						
                    <th class="width10">Action
						<button type="button" title="Filter" ng-hide="multisearchBtn" ng-click="openMultisearch()" class="pull-right btn btn-primary"><i class="fa fa-filter"></i></button>					
					</th>
                </tr>
            </thead>
            <tbody>
				<tr ng-hide="multiSearchTr">
					<td><input type="text" ng-change="getMultiSearch()" name="search_payment_made_no" ng-model="searchPaymentMade.search_payment_made_no" 	class="multiSearch form-control width80" placeholder="Payment Made No."></td>
					<td ng-if="{{$division_id}} == 0" class="width10"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_vendor_name"  ng-model="searchPaymentMade.search_vendor_name" class="multiSearch form-control width80" placeholder="Vendor Name"></td>
					<td><input type="text" ng-change="getMultiSearch()" name="search_payment_made_date" ng-model="searchPaymentMade.search_payment_made_date" class="multiSearch form-control width80" placeholder="Payment Made Date"></td>
					<td class="width10"><input type="text" ng-change="getMultiSearch()" name="search_payment_made_amount" ng-model="searchPaymentMade.search_payment_made_amount" class="multiSearch form-control width80" placeholder="Amount Made(Rs.)"></td>
					<td class="width10"><input type="text" ng-change="getMultiSearch()" name="search_payment_source_name" ng-model="searchPaymentMade.search_payment_source_name" class="multiSearch form-control width80" placeholder="Paid From"></td>
					<td class="width10"><input type="text" ng-change="getMultiSearch()" name="search_created_by" ng-model="searchPaymentMade.search_created_by" class="multiSearch form-control width80" placeholder="Created By"></td>
					<td class="width10">
						<button ng-click="refreshMultisearch()" title="Refresh" class="btn btn-primary btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i></button>
						<button ng-click="closeMultisearch()" title="Close" class="btn btn-default btn-sm"><i class="fa fa-times" aria-hidden="true"></i></button>
					</td>
				</tr>				
                <tr dir-paginate="paymentsMadeObj in paymentsMadeList | filter:filterPaymentMade | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                    <td data-title="payment_made_no" class="ng-binding">[[paymentsMadeObj.payment_made_no]]</span></td>
                    <td ng-if="{{$division_id}} == 0" data-title="Division Name ">[[paymentsMadeObj.division_name]]</td>
                    <td data-title="Vendor Name" class="ng-binding">[[paymentsMadeObj.vendor_name]]</td>
                    <td data-title="Payment made date" class="ng-binding">[[paymentsMadeObj.payment_made_date | date : 'dd-MM-yyyy']]</td>
                    <td data-title="Payment made amount" class="ng-binding">[[paymentsMadeObj.payment_made_amount]]</td>      
                    <td data-title="Payment source name" class="ng-binding">[[paymentsMadeObj.payment_source_name]]</td>						
                    <td data-title="Created By" class="ng-binding">[[paymentsMadeObj.createdByName]]</td>						
                    <td class="width10">
                        <span ng-if="{{defined('EDIT') && EDIT}}">
                            <button ng-click="funEditPaymentMade(paymentsMadeObj.payment_made_hdr_id)" title="Edit Payment Made" class="btn btn-primary btn-sm"><i aria-hidden="true" class="fa fa-pencil-square-o"></i></button>
                        </span>
                        <span ng-if="{{defined('DELETE') && DELETE}}">
                            <button ng-click="funConfirmDeleteMessage(paymentsMadeObj.payment_made_hdr_id,divisionID)" title="Delete Payment Made" class="btn btn-danger btn-sm"> <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                        </span>
                    </td>                
                </tr>                        
                <tr ng-if="!paymentsMadeList.length"><td colspan="8">No record found.</td></tr>
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