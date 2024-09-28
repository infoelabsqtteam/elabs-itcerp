<div class="row" ng-if="listCustomerWiseAssayParameterRateDiv">

    <!--heading-->
    <form class="form-inline" method="POST" target ="blank" action="{{url('master/customer-wise-assay-parameters/download-excel')}}" role="form" id="erpCustomerWiseAssayParametersFilterForm" name="erpCustomerWiseAssayParametersFilterForm" novalidate>
	<label for="submit">{{ csrf_field() }}</label>
	<div class="row header">        
	    <div role="new" class="navbar-form navbar-left">            
		<span ng-click="funGetCustomerWiseAssayParameterList(0,2)" class="pull-left"><strong id="form_title">Customer Wise Assay Parameters Rate Listing<span ng-if="customerWiseAssayParameterRateList.rightSideDataList.length">([[customerWiseAssayParameterRateList.rightSideDataList.length]])</span></strong></span>
	    </div>
	    <div role="new" class="navbar-form navbar-right">
		<div class="nav-custom" style="display: flex;position: relative">
		    <input type="text" name="search_keyword" placeholder="Search" ng-model="filterCustomerWiseAssayParameterRate" class="form-control ng-pristine ng-untouched ng-valid" >
		    <select
			class="form-control"
			ng-init="funGetParentCategory();"
			name="cir_product_category_id"
			ng-model="searchCustomerWiseAssayParameter.cir_product_category_id"
			id="cir_product_category_id"
			ng-change = "funGetCustomerWiseAssayParameterList(cirCustomerID,searchCustomerWiseAssayParameter.cir_product_category_id.id)"
			ng-options="item.name for item in parentCategoryLevelZeroList track by item.id"
			ng-required='true'>
		    </select>
		    <button type="button" ng-disabled="!customerWiseAssayParameterRateList.rightSideDataList.length" class="form-control btn btn-default dropdown dropdown-toggle " data-toggle="dropdown" title="Download">
			Download</button>
			<div class="dropdown-menu">
				<input type="submit" name="generate_customer_assay_parameter_documents"  class="dropdown-item" value="Excel">
			</div>
		         
		    <span ng-if="{{defined('ADD') && ADD}}">
			<button type="button" tyle="top:2px;"class="btn btn-primary" ng-click="navigateFormPage('add');">Add New</button>
		    </span>
		    <input type="hidden" name="cir_customer_id" value="[[cirCustomerID]]">
		</div>
	    </div>
	</div>
    </form>
    <!--/heading-->
    
    <!--display record--> 
    <div class="row" id="no-more-tables">
        <div class="panel panel-default">		           
            <div class="panel-body">					
                <div class="row col-sm-12">
                    <div class="col-sm-3 text-left custom-scroll">
                        <table class="col-sm-12 table-striped table-condensed cf">
                            <thead class="cf">
                                <tr>                            
                                    <th>
                                        <label ng-click="funGetCustomerWiseAssayParameterList(cirCustomerID,cirDepartmentID);" class="sortlabel">Customer Name<span ng-if="customerWiseAssayParameterRateList.leftSideDataList.length">([[customerWiseAssayParameterRateList.leftSideDataList.length]])</span></label>
                                        <input type="text" style="width: 150px ! important;" ng-model="filterLeftSideCustomerAssayDataList" class="form-control ng-pristine ng-untouched ng-valid leftside-search" placeholder="Search">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="customerObj in customerWiseAssayParameterRateList.leftSideDataList | filter : filterLeftSideCustomerAssayDataList" style="padding: 0;" title="[[customerObj.customer_name]]">
                                    <td data-title="Customer Name" class="ng-binding">
                                        <div class="col-sm-10 text-left" ng-if="cirCustomerID != customerObj.customer_id" ng-click="funGetCustomerWiseAssayParameterList(customerObj.customer_id,cirDepartmentID)">[[customerObj.customer_name]]</div>
                                        <div class="col-sm-10 text-left active-gray-listing" ng-if="cirCustomerID == customerObj.customer_id" ng-click="funGetCustomerWiseAssayParameterList(customerObj.customer_id,cirDepartmentID)">[[customerObj.customer_name]]</div>
                                        <div class="col-sm-2 editbtn" ng-if="{{defined('EDIT') && EDIT}}"><a style="margin-left: 9px;" href="javascript:;" title="Update Record" class="btn btn-info btn-sm" ng-click="funEditCustomerWiseAssayParametersRate(customerObj.customer_id,cirDepartmentID);"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></div>
                                    </td>
                                </tr>									
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-9 text-left">
                        <table class="col-sm-12 table-striped table-condensed cf font15">
                            <thead class="cf">
                                <tr>                            
                                    <th>
                                        <label ng-click="sortBy('department_name')" class="sortlabel">Department</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'department_name'" class="sortorder reverse"></span>
                                    </th>
				    <th>
                                        <label ng-click="sortBy('category_name')" class="sortlabel">Product Category</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'category_name'" class="sortorder reverse"></span>
                                    </th>
				    <th>
                                        <label ng-click="sortBy('sub_category_name')" class="sortlabel">Sub Product Category</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'sub_category_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('test_para_cat_name')" class="sortlabel">Parameter Category</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'test_para_cat_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('test_parameter_name')" class="sortlabel">Parameter Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'test_parameter_name'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('equipment_name')" class="sortlabel">Equipment Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'equipment_name'" class="sortorder reverse"></span>
                                    </th>
				    <th>
                                        <label ng-click="sortBy('cir_equipment_count')" class="sortlabel">Equipment Count</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'cir_equipment_count'" class="sortorder reverse"></span>
                                    </th>
				    <th>
                                        <label ng-click="sortBy('detector_name')" class="sortlabel">Detector Name</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'detector_name'" class="sortorder reverse"></span>
                                    </th>
				    <th>
                                        <label ng-click="sortBy('invoicing_running_time_key')" class="sortlabel">Running Time</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoicing_running_time_key'" class="sortorder reverse"></span>
                                    </th>
				    <th>
                                        <label ng-click="sortBy('cir_no_of_injection')" class="sortlabel">No. of Injection</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'cir_no_of_injection'" class="sortorder reverse"></span>
                                    </th>
                                    <th>
                                        <label ng-click="sortBy('invoicing_rate')" class="sortlabel">Rate</label>
                                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'invoicing_rate'" class="sortorder reverse"></span>
                                    </th>
                                    <th ng-if="{{defined('DELETE') && DELETE }}">Action</th>
                                </tr>
                            </thead>
			    <tbody>
				<tr dir-paginate="customerWiseParameterRateObj in customerWiseAssayParameterRateList.rightSideDataList | filter:filterCustomerWiseAssayParameterRate | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
				    <td data-title="Department Category" class="ng-binding">[[customerWiseParameterRateObj.department_name]]</td>
				    <td data-title="Product Category" class="ng-binding">[[customerWiseParameterRateObj.category_name]]</td>
				    <td data-title="Sub Product Category" class="ng-binding">[[customerWiseParameterRateObj.sub_category_name]]</td>
				    <td data-title="Parameter Category" class="ng-binding">[[customerWiseParameterRateObj.test_para_cat_name]]</td>
				    <td data-title="Parameter Name" data="[[customerWiseParameterRateObj.cir_parameter_id]]" class="ng-binding" ng-bind-html="customerWiseParameterRateObj.test_parameter_name"></td>
				    <td data-title="Equipment Name" class="ng-binding">[[customerWiseParameterRateObj.equipment_name]]</td>
				    <td data-title="Equipment Count" class="ng-binding">[[customerWiseParameterRateObj.cir_equipment_count]]</td>
				    <td data-title="Detector Name" class="ng-binding">[[customerWiseParameterRateObj.detector_name]]</td>
				    <td data-title="Running Name" class="ng-binding">[[customerWiseParameterRateObj.invoicing_running_time_key]]</td>
				    <td data-title="No. of Injection" class="ng-binding">[[customerWiseParameterRateObj.cir_no_of_injection]]</td>
				    <td data-title="Invoicing Rate" class="ng-binding">[[customerWiseParameterRateObj.invoicing_rate]]</td>
				    <td ng-if="{{defined('DELETE') && DELETE }}" data-title="Invoicing Rate" class="ng-binding">
					<a href="javascript:;" title="Delete Record" class="btn btn-danger btn-sm" ng-click="funConfirmDeleteMessage(customerWiseParameterRateObj.cir_id,cirCustomerID,cirDepartmentID);"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
				    </td>
				</tr>
				<tr ng-if="!customerWiseAssayParameterRateList.rightSideDataList.length"><td colspan="8">No Record Found!</td></tr>
			    </tbody>
                            <tfoot ng-if="customerWiseAssayParameterRateList.rightSideDataList.length > {{ defined('PERPAGE') ? PERPAGE : 15 }}">
                                <tr>
                                    <td colspan="8"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
                                </tr>                                    
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>