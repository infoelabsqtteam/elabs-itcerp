<div class="row header" ng-hide="isVisibleListSampleDiv">
    <div role="new" class="navbar-form navbar-left">            
        <span class="pull-left"><strong id="form_title" ng-click="funGetBranchWiseSamples(divisionID)">Samples<span ng-if="sampleDataList.length > 0">([[sampleDataList.length]])</span></strong></span>
    </div>
	
    <div role="new" class="navbar-form navbar-right mT1" style="position:relative">
	<form class="form-inline" method="POST" role="form" id="erpFilterSampleForm" name="erpFilterSampleForm" action="{{url('sales/samples/generate-branch-wise-sample-pdf')}}">
	     <label for="submit">{{ csrf_field() }}</label>        
	    <div style="margin: -5px; padding-right: 9px;">
		<input type="text" placeholder="Search" ng-model="filterSample.searchSamples" class="form-control ng-pristine ng-untouched ng-valid">
		<select
		    ng-if="{{$division_id}} == 0"
		    class="form-control"
		    ng-model="filterSample.division"
		    name="division_id"
		    ng-options="division.name for division in divisionsCodeList track by division.id"
		    ng-change="funGetBranchWiseSamples(filterSample.division.id,sampleStatusID)">
		    <option value="">All Branch</option>
		</select>
		<select
		    class="form-control"
		    ng-model="filterSample.status"
		    name="sample_status_id"
		    ng-options="sampleAction.name for sampleAction in sampleStatusList track by sampleAction.id"
		    ng-change="funGetBranchWiseSamples(divisionID,filterSample.status.id)">
		    <option value="">All Status</option>
		</select>
		<button type="button" ng-disabled="!sampleDataList.length" class="form-control btn btn-default dropdown dropdown-toggle" data-toggle="dropdown" title="Download">
		Download</button>
		<div class="dropdown-menu" style="top:34px !important">
		    <input type="submit"  formtarget="_blank" name="generate_sample_documents" value="Excel"
		    class="dropdown-item">
		    <input type="submit"  formtarget="_blank" name="generate_sample_documents" value="PDF"
		    class="dropdown-item">
		</div>
	    </div>
	</form>
    </div>
    
    <div id="no-more-tables">
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('sample_no')">Sample No.</label>
                        <span class="sortorder" ng-show="predicate === 'sample_no'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th ng-if="{{$division_id}} == 0">
                        <label class="sortlabel" ng-click="sortBy('division_name')">Branch</label>
                        <span class="sortorder" ng-show="predicate === 'division_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('p_category_name')">Department</label>
                        <span class="sortorder" ng-show="predicate === 'p_category_name'" ng-class="{reverse:reverse}"></span>						
                    </th>                                            
                    <th>
                        <label class="sortlabel" ng-click="sortBy('sample_date')">Sample Date</label>
                        <span class="sortorder" ng-show="predicate === 'sample_date'" ng-class="{reverse:reverse}"></span>						
                    </th>                    
                    
                    <th>
                        <label class="sortlabel" ng-click="sortBy('customer_name')">Customer Name</label>
                        <span class="sortorder" ng-show="predicate === 'customer_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
		    <th>
			<label ng-click="sortBy('customer_name')" class="sortlabel">Place</label>
			<span ng-class="{reverse:reverse}" ng-show="predicate === 'city_name'" class="sortorder reverse ng-hide"></span>
                    </th> 
                    <th>
                        <label class="sortlabel" ng-click="sortBy('customer_name')">Customer Email</label>
                        <span class="sortorder" ng-show="predicate === 'customer_email'" ng-class="{reverse:reverse}"></span>                        
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('sample_mode_name')">Sample Mode</label>
                        <span class="sortorder" ng-show="predicate === 'sample_mode_name'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('sample_mode_description')">Description</label>
                        <span class="sortorder" ng-show="predicate === 'sample_mode_description'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('remarks')">Remarks</label>
                        <span class="sortorder" ng-show="predicate === 'remarks'" ng-class="{reverse:reverse}"></span>						
                    </th>
		    <th>
                        <label class="sortlabel" ng-click="sortBy('trf_no')">TRF No.</label>
                        <span class="sortorder" ng-show="predicate === 'trf_no'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('sample_status')">Status</label>
                        <span class="sortorder" ng-show="predicate === 'sample_status'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th>
                        <label class="sortlabel" ng-click="sortBy('created_by')">Created By</label>
                        <span class="sortorder" ng-show="predicate === 'created_by'" ng-class="{reverse:reverse}"></span>						
                    </th>
                    <th class="width10">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="sampleDataListObj in sampleDataList| filter:filterSample.searchSamples | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
                    <td data-title="Sample No" ng-bind="sampleDataListObj.sample_no"> </td>
                    <td ng-if="{{$division_id}} == 0" data-title="Division Name">[[sampleDataListObj.division_name]]</td>
                    <td data-title="Section Name" ng-bind="sampleDataListObj.p_category_name"></td>                    
                    <td data-title="Sample Date" ng-bind="sampleDataListObj.sample_date"></td>                    
                    <td data-title="Customer Name">
			<span ng-if="sampleDataListObj.customer_name" ng-bind="sampleDataListObj.customer_name"></span>
			<span ng-if="sampleDataListObj.customer_name_new && !sampleDataListObj.customer_name" ng-bind="sampleDataListObj.customer_name_new"></span>
		    </td>
		    <td data-title="Customer City">
                        <span ng-if="sampleDataListObj.customer_city" ng-bind="sampleDataListObj.customer_city"></span>
                    </td>
                    <td data-title="Customer Name">
                        <span ng-if="sampleDataListObj.customer_email" ng-bind="sampleDataListObj.customer_email"></span>
                        <span  ng-if="sampleDataListObj.new_customer_email && !sampleDataListObj.customer_email" ng-bind="sampleDataListObj.new_customer_email"></span>
                    </td>
                    <td data-title="Sample Mode" ng-bind="sampleDataListObj.sample_mode_name"></td>
                    <td data-title="Sample Mode Description">
			<span id="sampleLimitedText_[[sampleDataListObj.sample_id]]" class="ng-binding">
                            [[ sampleDataListObj.sample_mode_description | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
			    <a class="readMore" ng-show="sampleDataListObj.sample_mode_description.length > 150" ng-click="toggleDescription('sample',sampleDataListObj.sample_id)" href="javascript:;" aria-hidden="false">read more...  </a>
			</span>
			<span id="sampleFullText_[[sampleDataListObj.sample_id]]" style="display:none;" class="ng-binding">
			    [[sampleDataListObj.sample_mode_description ? sampleDataListObj.sample_mode_description : '-']]
			    <a class="readLess" ng-click="toggleDescription('sample',sampleDataListObj.sample_id)" href="javascript:;">read less...</a>
                        </span>
		    </td>    
                    <td data-title="Remark">
                        <span id="remarkLimitedText_[[sampleDataListObj.sample_id]]" class="ng-binding">
                            [[ sampleDataListObj.remarks | limitTo : {{ defined('TEXTLIMIT') ? TEXTLIMIT : 150 }} ]]
			    <a class="readMore" ng-show="sampleDataListObj.remarks.length > 150" ng-click="toggleRemark('remark',sampleDataListObj.sample_id)" href="javascript:;" aria-hidden="false">read more... </a>
			</span>
			<span id="remarkFullText_[[sampleDataListObj.sample_id]]" style="display:none;" class="ng-binding">
			    [[sampleDataListObj.remarks ? sampleDataListObj.remarks : '-']]
			    <a class="readLess" ng-click="toggleRemark('remark',sampleDataListObj.sample_id)" href="javascript:;">read less...</a>
                        </span>
                    </td>
		    <td data-title="Sample Mode" ng-bind="sampleDataListObj.trf_no"></td>
                    <td data-title="Sample Status">
			<span class="po-open" ng-if="sampleDataListObj.sample_status == 0">Received</span>
			<span class="po-closed" ng-if="sampleDataListObj.sample_status == 1">Booked</span>
			<span class="po-wait" ng-if="sampleDataListObj.sample_status == 2">Waiting</span>
			<span class="po-wait" ng-if="sampleDataListObj.sample_status == 3">Hold</span>
		    </td>
                    <td data-title="Created By" ng-bind="sampleDataListObj.createdByName"></td>                    
                    <td class="width10">
			<a href="javascript:;" ng-disabled="sampleDataListObj.sample_status == 1" ng-if="{{defined('EDIT') && EDIT}}" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditSample(sampleDataListObj.sample_id)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="javascript:;" ng-disabled="sampleDataListObj.sample_status == 1 || sampleDataListObj.sample_status == 2" ng-if="{{defined('EDIT') && EDIT}}" title="Close Receiving" ng-click="funConfirmCloseMessage(sampleDataListObj.sample_id,divisionID,sampleStatusID)" class="btn btn-info btn-sm"><i class="fa fa-window-close" aria-hidden="true"></i></a>
			<a href="javascript:;" ng-disabled="sampleDataListObj.sample_status == 1" ng-if="{{defined('DELETE') && DELETE}} && !sampleDataListObj.trf_no" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(sampleDataListObj.sample_id,divisionID,sampleStatusID)"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                </tr>					
                <tr ng-if="!sampleDataList.length" class="noRecord"><td colspan="14">No Record Found!</td></tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="14"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
                </tr>
            </tfoot>
        </table>	  
    </div>
</div>