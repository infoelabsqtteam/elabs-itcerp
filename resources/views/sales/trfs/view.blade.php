<div ng-hide="IsViewDetailList" id="IsViewDetailList">
    
    <!--Header-->
    <div class="row header">        
	<div role="new" class="navbar-form navbar-left">            
	    <strong id="form_title" title="Refresh" ng-click="funRefreshTrfsList()">TRF Detal : <span ng-bind="trfHdrList.trf_no"></span></strong>
	</div>
	<div role="new" class="navbar-form navbar-right">
	    <div class="nav-custom">
		<button type="button" class="btn btn-primary" ng-click="backButton(0)">Back</button>
	    </div>
	</div>
    </div>
    <!--/Header-->
    
    <!--Listing of TRF Detail-->
    <div class="row tableRecord mT10">
	<div class="row order-section">TRF Detail:</div>
	<table class="col-sm-12 table-striped table-condensed cf">
	    <tbody>
		<tr>
		    <th>TRF No.</th><td ng-bind="trfHdrList.trf_no"></td>
		    <th>TRF Status</th><td ng-bind="trfHdrList.trf_status"></td>
		</tr>
		<tr>
		    <th>Branch</th><td ng-bind="trfHdrList.trf_division_name"></td>
		    <th>Department</th><td ng-bind="trfHdrList.trf_product_category_name"></td>
		</tr>
		<tr>
		    <th>Customer Name</th><td ng-bind="trfHdrList.trf_customer_name"></td>
		    <th>Customer City</th><td ng-bind="trfHdrList.trf_city_name"></td>
		</tr>
		<tr>
		    <th>Product Test Name</th><td ng-bind="trfHdrList.trf_product_test_name"></td>
		    <th>Test Standard Name</th><td ng-bind="trfHdrList.trf_test_standard_name"></td>
		</tr>
		<tr>
		    <th>Product Category</th><td ng-bind="trfHdrList.trf_p_category_name"></td>
		    <th>Product Sub Category</th><td ng-bind="trfHdrList.trf_sub_p_category_name"></td>
		</tr>
		<tr>
		    <th>Product Name</th><td ng-bind="trfHdrList.trf_product_name"></td>
		    <th>Sample Name</th><td ng-bind="trfHdrList.trf_sample_name"></td>
		</tr>
		<tr>
		    <th>Mfg Lic. No</th><td ng-bind="trfHdrList.trf_mfg_lic_no"></td>
		</tr>
		<tr>
		    <th>Manufactured By</th><td ng-bind="trfHdrList.trf_manufactured_by"></td>
		    <th>Supplied By</th><td ng-bind="trfHdrList.trf_supplied_by"></td>
		</tr>
		<tr>
		    <th>Mfg. Date</th><td ng-bind="trfHdrList.trf_mfg_date"></td>
		    <th>Expiry Date</th><td ng-bind="trfHdrList.trf_expiry_date"></td>
		</tr>
		<tr>
		    <th>Batch No.</th><td ng-bind="trfHdrList.trf_batch_no"></td>
		    <th>Batch Size</th><td ng-bind="trfHdrList.trf_batch_size"></td>
		</tr>
		<tr>
		    <th>Sample Qty.</th><td ng-bind="trfHdrList.trf_sample_qty"></td>
		</tr>
		<tr ng-if="trfHdrList.reporting_customer_name || trfHdrList.invoicing_customer_name">
		    <th ng-if="trfHdrList.reporting_customer_name">Reporting To</th><td ng-bind="trfHdrList.reporting_customer_name"></td>
		    <th ng-if="trfHdrList.invoicing_customer_name">Invoicing To</th><td ng-bind="trfHdrList.invoicing_customer_name"></td>
		</tr>
		<tr ng-if="trfHdrList.trf_reporting_address || trfHdrList.trf_invoicing_address">
		    <th ng-if="trfHdrList.trf_reporting_address">Reporting Address</th><td ng-bind="trfHdrList.trf_reporting_address"></td>
		    <th ng-if="trfHdrList.trf_invoicing_address">Invoicing Address</th><td ng-bind="trfHdrList.trf_invoicing_address"></td>
		</tr>
		<tr>
		    <th>Storage Condition Name</th><td ng-bind="trfHdrList.trf_storage_condition_name"></td>
		    <th>Active/Deactive Status</th><td ng-bind="trfHdrList.trf_active_deactive_status_name"></td>
		</tr>
	    </tbody>
	</table>
    </div>
    
    <div class="row tableRecord mT20">
	<div class="row order-section">TRF Parameter Detail<span ng-if="trfHdrDtlList.length">([[trfHdrDtlList.length]])</span>:</div>
	<table class="col-sm-12 table-striped table-condensed cf">
	    <thead>
		<tr>
		    <th>S.No.</th>
		    <th>Test Parameter</th>
		</tr>
	    </thead>
	    <tbody ng-if="trfHdrDtlList.length">
		<tr ng-repeat="trfHdrDtlObj in trfHdrDtlList track by $index">
		    <td data-title="S.No">[[$index + 1]]</td>
		    <td data-title="Test Parameter Name"><span ng-bind-html="trfHdrDtlObj.trf_test_parameter_name"></span></td>               
		</tr>                        
		<tr ng-if="!trfHdrDtlList.length"><td colspan="2">No record found.</td></tr>
	    </tbody>
	</table>
    </div>
    
    <div class="row col-sm-12 tableRecord mT20">
	<div class="col-sm-5">&nbsp;</div>
	<div class="col-sm-4">
	    <form name="generateTrfPdf" id="generateTrfPdf" action="{{ url('sales/trfs/trf-generate-pdf') }}" method="POST">
		<label for="submit">{{ csrf_field() }}</label>
		<input type="hidden" ng-value="trfHdrList.trf_id" ng-model="trfHdrList.trf_id" name="trf_id" id="trf_id">
		<input type="submit" formtarget="_blank" name="generate_trf_pdf" value="Download PDF" class="btn btn-primary btn-sm">
	    </form>
	</div>
	<div class="col-sm-4">&nbsp;</div>
    </div>
    <!--/Listing of TRF Detail-->
    
</div>