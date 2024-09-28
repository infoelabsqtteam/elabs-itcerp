<div class="row order_detail" ng-hide="IsPreviewList" id="IsPreviewList">

    <!--Header-->
    <div class="header">
	<span class="pull-left headerText"><strong>[[prototypesHdrList.stb_prototype_no]] : [[prototypesHdrList.stb_sample_description_name]] : ([[prototypesHdrDtlList.length]])</strong></span>
	<span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="backButton()">Back</button></span>
    </div>
    <!--/Header-->
	
    <!--Added Stability Order Prototype List-->
    <div class="row mT10" ng-if="prototypesHdrDtlList.length">
	<div id="no-more-tables" class="mT10 col-xs-12 form-group fixed_table">
	    <table border="1" class="col-sm-12 table-striped table-condensed cf">
		<thead class="cf">
		    <tr>
			<th>
			    <label class="sortlabel capitalizeAll">S.No.</label>
			</th>
			<th>
			    <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_label_name')">Label name</label>
			    <span class="sortorder" ng-show="predicate === 'stb_label_name'" ng-class="{reverse:reverse}"></span>
			</th>
			<th>
			    <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_start_date')">Start Date</label>
			    <span class="sortorder" ng-show="predicate === 'stb_start_date'" ng-class="{reverse:reverse}"></span>
			</th>
			<th>
			    <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_end_date')">End Date</label>
			    <span class="sortorder" ng-show="predicate === 'stb_end_date'" ng-class="{reverse:reverse}"></span>
			</th>                            
			<th>
			    <label class="sortlabel capitalizeAll" ng-click="sortBy('product_name')">Product Name</label>
			    <span class="sortorder" ng-show="predicate === 'product_name'" ng-class="{reverse:reverse}"></span>
			</th>
			<th>
			    <label class="sortlabel capitalizeAll" ng-click="sortBy('test_std_name')">Test Std Name</label>
			    <span class="sortorder" ng-show="predicate === 'test_std_name'" ng-class="{reverse:reverse}"></span>
			</th>
			<th>
			    <label class="sortlabel capitalizeAll" ng-click="sortBy('test_code')">Product Tests</label>
			    <span class="sortorder" ng-show="predicate === 'test_code'" ng-class="{reverse:reverse}"></span>
			</th>
			<th>
			    <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_dtl_sample_qty')">Sample Qty</label>
			    <span class="sortorder" ng-show="predicate === 'stb_dtl_sample_qty'" ng-class="{reverse:reverse}"></span>
			</th>
			<th>
			    <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_condition_temperature')">Storage Condition</label>
			    <span class="sortorder" ng-show="predicate === 'stb_condition_temperature'" ng-class="{reverse:reverse}"></span>
			</th>
			<th>
			    <label class="sortlabel capitalizeAll">Action</label>
			</th>
		    </tr>
		</thead>
		<tbody>                            
		    <tr ng-repeat="prototypesHdrDtlObj in prototypesHdrDtlList track by $index">
			<td data-title="S.No.">[[$index+1]]</td>
			<td data-title="Label name">[[prototypesHdrDtlObj.stb_label_name]]</td>
			<td data-title="Start Date">[[prototypesHdrDtlObj.stb_start_date]]</td>
			<td data-title="End Date">[[prototypesHdrDtlObj.stb_end_date]]</td>
			<td data-title="Product Name">[[prototypesHdrDtlObj.product_name]]</td>
			<td data-title="Testing Product">[[prototypesHdrDtlObj.test_std_name]]</td>
			<td data-title="Product Tests">[[prototypesHdrDtlObj.test_code]]</td>
			<td data-title="Sample Qty">[[prototypesHdrDtlObj.stb_dtl_sample_qty]]</td>
			<td data-title="Storage Condition">[[prototypesHdrDtlObj.stb_stability_type_name]]&nbsp;[[prototypesHdrDtlObj.stb_condition_temperature]]</td>
			<td data-title="Action">
			    <span ng-if="prototypesHdrDtlObj.stb_order_hdr_detail_status == 0"><button type="button" ng-click="funFinalPreviewStabilityOrderPrototypes(prototypesHdrDtlObj.stb_order_hdr_id,prototypesHdrDtlObj.stb_order_hdr_dtl_id,prototypesHdrDtlObj.stb_stability_type_id)" title="Order Preview" class="btn btn-primary btn-sm report_btn_span">Preview</button></span>
			    <span ng-if="prototypesHdrDtlObj.stb_order_hdr_detail_status == 1"><button type="button" title="Order Booked" class="btn btn-success btn-sm report_btn_span">Booked</button></span>
			</td>
		    </tr>											
		</tbody>
	    </table>
	</div>
    </div>
    <!--Added Stability Order Prototype List-->
    
</div>