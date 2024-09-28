<div ng-init="getSelectedUnSelectedEquipments();">
    
    <div class="row header">
	<strong class="pull-left headerText" ng-click="getCustomerDifinedTestReportFields(divisionID,productCategoryID)" title="Refresh">Customer Defined Fields<span ng-if="branchWiseSelectedColumnList.length">([[branchWiseSelectedColumnList.length]])</span></strong>
    </div>
    
    <div class="row">
	<div class="row col-sm-9">
	    <form method="POST" role="form" id="erpCustomerDifinedTestReportFieldForm" name="erpCustomerDifinedTestReportFieldForm" novalidate>
		
		<!--Branch-->
		<div class="col-xs-6 form-group">
		    <label for="ors_division_id">Branch<em class="asteriskRed">*</em></label>
		    <select
			class="form-control" 
			name="ors_division_id"
			id="ors_division_id"
			ng-change="getCustomerDifinedTestReportFields(customerDefinedTestReportField.ors_division_id.id,customerDefinedTestReportField.ors_product_category_id.id)"
			ng-model="customerDefinedTestReportField.ors_division_id"
			ng-options="item.name for item in divisionsCodeList track by item.id"
			ng-required="true">
			<option value="">Select Branch</option>
		    </select>
		    <span ng-messages="erpCustomerDifinedTestReportFieldForm.ors_division_id.$error" ng-if='erpCustomerDifinedTestReportFieldForm.ors_division_id.$dirty  || erpCustomerDifinedTestReportFieldForm.$submitted' role="alert">
			<span ng-message="required" class="error">Branch is required</span>
		    </span>
		</div>
		<!--/Branch-->
		
		<!--Department-->
		<div class="col-xs-6 form-group">
		    <label for="ors_product_category_id">Department<em class="asteriskRed">*</em></label>						   
		    <select
			class="form-control" 
			id="ors_product_category_id"
			name="ors_product_category_id"
			ng-change="getCustomerDifinedTestReportFields(customerDefinedTestReportField.ors_division_id.id,customerDefinedTestReportField.ors_product_category_id.id)"
			ng-model="customerDefinedTestReportField.ors_product_category_id"
			ng-options="item.name for item in parentCategoryList track by item.id"
			ng-required="true">
			<option value="">Select Department</option>
		    </select>
		    <span ng-messages="erpCustomerDifinedTestReportFieldForm.ors_product_category_id.$error" ng-if='erpCustomerDifinedTestReportFieldForm.ors_product_category_id.$dirty || erpCustomerDifinedTestReportFieldForm.$submitted' role="alert">
			<span ng-message="required" class="error">Department is required</span>
		    </span>
		</div>
		<!--/Department-->
		
		<!--Select Fields-->
		<div class="col-sm-12 form-group">
		    <label for="ors_column_name">Select Fields<em class="asteriskRed">*</em></label>
		    <div class="custom-scroll">
			<table class="col-sm-12 table-striped table-condensed cf">
			    <tbody>
				<tr ng-repeat="orderMasterColumnObj in orderMasterColumnList">
				    <td data-title="Column Name">
					<span class="txt-left"><input type="checkbox" ng-click="validateCheckboxField(orderMasterColumnObj.id)" ng-checked="allSelectedOrderMasterColumnArray.indexOf(orderMasterColumnObj.id) > -1" id="customerDefinedTestReportField_[[orderMasterColumnObj.id]]" ng-value="orderMasterColumnObj.id" ng-model="customerDefinedTestReportField.ors_column_name_[[orderMasterColumnObj.id]]" name="ors_column_name[]"></span>&nbsp;[[orderMasterColumnObj.name]]
				    </td>
				</tr>
				<tr ng-if="!orderMasterColumnList.length" class="noRecord text-left"><td colspan="1">No Record Found!</td></tr>
			    </tbody>
		       </table>
		    </div>
		</div>
		<!--/Select Fields-->
		
		<!--Button-->
		<div class="col-xs-12 form-group text-right mT10" ng-if="orderMasterColumnList.length">
		    <label for="submit">{{ csrf_field() }}</label>
		    <input type="hidden" value="1" name="ors_type_id" ng-model="customerDefinedTestReportField.ors_type_id">
		    <button type="button" class="btn btn-primary" ng-disabled="erpCustomerDifinedTestReportFieldForm.$invalid || !allSelectedOrderMasterColumnArray.length" ng-click="funSaveUpdateColumnName()">Save</button>
		</div>
		<!--Button-->
		
	    </form>
	</div>
	    
	<div class="row col-sm-3">
	    <form>
		<div id="no-more-tables" class="row col-sm-12 custom-scroll">
		    <table class="col-sm-12 table-striped table-condensed cf">
			<thead class="cf"><tr><th>Selected Customer Defined Fields</th></tr></thead>
		    </table>
		    <ul class="col-sm-12 table-striped table-condensed cf">
			<li style="list-style-type: none;" ng-repeat="branchWiseSelectedColumnListObj in branchWiseSelectedColumnList track  by $index">[[$index+1]].&nbsp;[[branchWiseSelectedColumnListObj.ors_column_name | removeUnderscores]]</li>
			<li style="list-style-type: none;" ng-if="!branchWiseSelectedColumnList.length">No Record Found!</li>
		    </ul>
		</div>
	    </form>
	</div>
    </div>
</div>	