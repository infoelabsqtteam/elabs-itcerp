<div class="row" ng-if="editCustomerWiseAssayParametersRateDiv">

    <!--heading-->
    <div class="row header">        
	<div role="new" class="navbar-form navbar-left">            
	    <div><strong>Edit Customer Wise Assay Parameters Rate : <span ng-if="customerListData.name">[[customerListData.name]]</span><span ng-if="customerWiseParameterRateEditListing.length">([[customerWiseParameterRateEditListing.length]])</span></strong></div>
	</div>            
	<div role="new" class="navbar-form navbar-right">
	    <div class="nav-custom">
		<input type="text" placeholder="Search" ng-model="filterEditCustomerWiseAssayParameterRate" class="form-control ng-pristine ng-untouched ng-valid">
		<span ng-if="{{defined('ADD') && ADD}}">
		    <button type="button" class="btn btn-primary" ng-click="navigateFormPage('list');">Back</button>
		</span>
	    </div>
	</div>
    </div>
    <!--/heading-->
    
    <!--display record--> 
    <div class="row" id="no-more-tables">
	<div class="panel panel-default">		           
	    <div class="panel-body">					
		<div class="row">
		    <form method="POST" role="form" id="erpEditCustomerWiseAssayParametersRateForm" name="erpEditCustomerWiseAssayParametersRateForm" novalidate>
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
				</tr>							
			    </thead>
				    
			    <tbody>
				<tr dir-paginate="customerWiseAssayParameterRateEditObj in customerWiseAssayParameterRateEditListing | filter:filterEditCustomerWiseAssayParameterRate | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:predicate:reverse">
				    <td data-title="Department Category" class="ng-binding">[[customerWiseAssayParameterRateEditObj.department_name]]</td>
				    <td data-title="Product Category" class="ng-binding">[[customerWiseAssayParameterRateEditObj.category_name]]</td>
				    <td data-title="Sub Product Category" class="ng-binding">[[customerWiseAssayParameterRateEditObj.sub_category_name]]</td>
				    <td data-title="Parameter Category" class="ng-binding">[[customerWiseAssayParameterRateEditObj.test_para_cat_name]]</td>
				    <td data-title="Parameter Name" data="[[customerWiseAssayParameterRateEditObj.cir_parameter_id]]" class="ng-binding" ng-bind-html="customerWiseAssayParameterRateEditObj.test_parameter_name"></td>
				    <td data-title="Equipment Name" class="ng-binding">[[customerWiseAssayParameterRateEditObj.equipment_name]]</td>
				    <td data-title="Equipment Count" class="ng-binding">[[customerWiseAssayParameterRateEditObj.cir_equipment_count]]</td>
				    <td data-title="Detector Name" class="ng-binding">[[customerWiseAssayParameterRateEditObj.detector_name]]</td>
				    <td data-title="Running Name" class="ng-binding">[[customerWiseAssayParameterRateEditObj.invoicing_running_time_key]]</td>
				    <td data-title="No. of Injection" class="ng-binding">[[customerWiseAssayParameterRateEditObj.cir_no_of_injection]]</td>
				    <td data-title="Test Standard" class="ng-binding">
					<input type="number"
					    min="0"
					    class="form-control invoicing_rate"
					    ng-model="editCustomerWiseAssayParametersRate.invoicing_rate_[[customerWiseAssayParameterRateEditObj.cir_id]]"
					    name="invoicing_rate['[[customerWiseAssayParameterRateEditObj.cir_id]]']"
					    id="invoicing_rate_[[$index]]"
					    class="form-control"
					    ng-value="customerWiseAssayParameterRateEditObj.invoicing_rate"
					    placeholder="Rate">
				    </td>
				</tr>
			    </tbody>
				    
			    <tfoot ng-if="customerWiseAssayParameterRateEditListing.length">
				<tr>
				    <td colspan="11"><div class="box-footer clearfix"><dir-pagination-controls></dir-pagination-controls></div></td>
				</tr>
				<tr>                            
				    <td colspan="11">
					<!--Save Button-->
					<div class="row">
					    <div class="col-xs-12 form-group text-right mT10">
						<label for="submit">{{ csrf_field() }}</label>
						<button type="button" ng-disabled="erpEditCustomerWiseAssayParametersRateForm.$invalid" class="btn btn-primary" ng-click="funUpdateCustomerWiseAssayParametersRate(cirCustomerID,cirDepartmentID)">Update</button>
						<button type="button" class="btn btn-default" ng-click="navigateFormPage('list');">Cancel</button>
					    </div>
					</div>
					<!--Save Button-->
				    </td>
				</tr>
			    </tfoot>
			</table>
		    </form>	
		</div>
	    </div>			
	</div>
    </div>   
</div>