<div id="editablePartA" ng-if="viewEditPartAForm">
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-12 col-xs-12 bord">
            <div class="col-sm-12 col-xs-12 report">
                <div class="col-sm-1 col-xs-4 upr-case">PART A </div>
                <div class="col-sm-11 col-xs-8 upr-case"><span>:</span> PARTICULARS OF SAMPLE SUBMITTED
                    <a ng-click="toggelEditReportPartAForm()" class="cursor-pointer editLinkCss">Close</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">a)</div>
        <div class="col-md-5 col-xs-5 bord">Nature of Sample<em class="asteriskRed">*</em></div>
        <div class="col-md-6 col-xs-5 bord">
            <!--<span ng-if="viewOrderReport.sample_description">[[viewOrderReport.sample_description]]</span>
				<span ng-if="!viewOrderReport.sample_description"></span>-->
            <input class="form-control" type="text" name="sampleDetails[sample_description]" ng-model="viewEditOrderReport.sample_description" placeholder="Sample Description">
        </div>

    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">b)</div>
        <div class="col-md-5 col-xs-5 bord">
            Grade / Variety / Type / Class / Size etc.
        </div>

        <div class="col-md-6 col-xs-5 bord">
            <input class="form-control" type="text" name="grade_type" ng-model="viewEditOrderReport.grade_type" placeholder="Grade / Variety / Type / Class / Size etc.">
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">c)</div>
        <div class="col-md-5 col-xs-5 bord">Brand Name</div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" class="form-control" ng-model="viewEditOrderReport.brand_type" name="sampleDetails[brand_type]" placeholder="Brand Name">
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">d)</div>
        <div class="col-md-5 col-xs-5 bord">Declared Values,if any</div>
        <div class="col-md-6 col-xs-5 bord">
            <input class="form-control" type="text" name="declared_values" ng-model="viewEditOrderReport.declared_values" placeholder="Declared Values">
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">e)</div>
        <div class="col-md-5 col-xs-5 bord">Code No.</div>
        <div class="col-md-6 col-xs-5 bord">
            <span ng-if="viewEditOrderReport.barcode"><img ng-src="[[viewEditOrderReport.barcode]]" /></span>
            <span ng-if="!viewEditOrderReport.barcode"></span>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">f)</div>
        <div class="col-md-5 col-xs-5 bord">Batch Number<em class="asteriskRed">*</em></div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" class="form-control" ng-model="viewEditOrderReport.batch_no" ng-required="true" name="sampleDetails[batch_no]" placeholder="Batch Number">
            <span ng-messages="orderReportFormByReporter.sampleDetails[batch_no].$error" ng-if='orderReportFormByReporter.sampleDetails[batch_no].$dirty || orderReportFormByReporter.$submitted' role="alert">
                <span ng-message="required" class="error">Batch No. is required</span>
            </span>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">g)</div>
        <div class="col-md-5 col-xs-5 bord">D.O.M</div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" class="form-control" ng-model="viewEditOrderReport.mfg_date" ng-required="true" name="sampleDetails[mfg_date]" placeholder="Date of manufacturing">
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">h)</div>
        <div class="col-md-5 col-xs-5 bord">Date of Expiry</div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" name="sampleDetails[expiry_date]" ng-model="viewEditOrderReport.expiry_date" class="form-control bgWhite" placeholder="Date of Expiry">
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">i)</div>
        <div class="col-md-5 col-xs-5 bord">Sample Quantity</div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" class="form-control" ng-model="viewEditOrderReport.sample_qty" name="sampleDetails[sample_qty]" placeholder="sample qty">
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">j)</div>
        <div class="col-md-5 col-xs-5 bord">Batch Size</div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" class="form-control" id="batch_size" ng-model="viewEditOrderReport.batch_size" name="sampleDetails[batch_size]" placeholder="Batch Size">
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">k)</div>
        <div class="col-md-5 col-xs-5 bord">Mode of Packing<em class="asteriskRed">*</em></div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" class="form-control" id="packing_mode" ng-model="viewEditOrderReport.packing_mode" ng-required="true" name="sampleDetails[packing_mode]" placeholder="Mode of Packing">
            <span ng-messages="orderReportFormByReporter.sampleDetails[packing_mode].$error" ng-if="orderReportFormByReporter.sampleDetails[packing_mode].$dirty || orderReportFormByReporter.$submitted" role="alert">
                <span ng-message="required" class="error">Packing Mode is required</span>
            </span>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">l)</div>
        <div class="col-md-5 col-xs-5 bord">Date of Receipt</div>
        <div class="col-md-6 col-xs-5 bord">
            <span ng-if="viewEditOrderReport.order_date">[[viewOrderReport.order_date]]</span>
            <span ng-if="!viewEditOrderReport.order_date"></span>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">m)</div>
        <div class="col-md-5 col-xs-5 bord">Date of Start</div>
        <div class="col-md-6 col-xs-5 bord">
            <span ng-if="viewEditOrderReport.order_date">[[viewOrderReport.order_date]]</span>
            <span ng-if="!viewEditOrderReport.order_date"></span>

        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">n)</div>
        <div class="col-md-5 col-xs-5 bord">Date of Completion</div>
        <div class="col-md-6 col-xs-5 bord">
            <span ng-if="viewOrderReport.orderAmendStatus && viewOrderReport.is_amended_no">[[orderTrackRecord.reviewing.report_view_date ? orderTrackRecord.reviewing.report_view_date : orderTrackRecord.finalizing.report_view_date]]</span>
            <span ng-if="viewOrderReport.report_date && !viewOrderReport.is_amended_no" ng-bind="viewOrderReport.report_date"></span>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">o)</div>
        <div class="col-md-5 col-xs-5 bord">BIS Seal (Intact/Not Intact/Unsealed) [[viewEditOrderReport.isSealed]]<em class="asteriskRed">*</em></div>
        <div class="col-md-6 col-xs-5 bord">
            <select class="form-control" name="sampleDetails[is_sealed]" id="is_sealed" ng-model="viewEditOrderReport.isSealed" ng-required="true">
                <option value="">Select Sealed/Unsealed</option>
                <option value="1" ng-selected="viewEditOrderReport.is_sealed==1">Sealed</option>
                <option value="0" ng-selected="viewEditOrderReport.is_sealed==0">Unsealed</option>
                <option value="2" ng-selected="viewEditOrderReport.is_sealed==2">Intact</option>
                <option value="3" ng-selected="viewEditOrderReport.is_sealed==3"></option>
            </select>
            <span ng-messages="orderReportFormByReporter.sampleDetails[is_sealed].$error" ng-if="orderReportFormByReporter.sampleDetails[is_sealed].$dirty || orderReportFormByReporter.$submitted" role="alert">
                <span ng-message="required" class="error">Sealed/Unsealed is required</span>
            </span>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">p)</div>
        <div class="col-md-5 col-xs-5 bord">IO'S Signature (Signed/Unsigned)<em class="asteriskRed">*</em></div>
        <div class="col-md-6 col-xs-5 bord">
            <select class="form-control" name="sampleDetails[is_signed]" id="is_signed" ng-model="viewEditOrderReport.isSigned" ng-required="true">
                <option value="">Select Signed/Unsigned</option>
                <option value="1" ng-selected="viewEditOrderReport.is_signed==1">Signed</option>
                <option value="0" ng-selected="viewEditOrderReport.is_signed==0">Unsigned</option>
            </select>
            <span ng-messages="orderReportFormByReporter.is_signed.$error" ng-if="orderReportFormByReporter.is_signed.$dirty || orderReportFormByReporter.$submitted" role="alert">
                <span ng-message="required" class="error">Signed/Unsigned is required</span>
            </span>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">q)</div>
        <div class="col-md-5 col-xs-5 bord">Submitted By</div>
        <div class="col-md-6 col-xs-5 bord">
            <span ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] - [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] )</span>
            <span ng-if="!viewOrderReport.customer_name"></span>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">r)</div>
        <div class="col-md-5 col-xs-5 bord">Any Other Information</div>
        <div class="col-md-6 col-xs-5 bord">
            <textarea class="form-control" id="remarks" rows="1" name="sampleDetails[remarks]" ng-model="viewEditOrderReport.remarks" placeholder="Any Other Information">
				</textarea>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">s)</div>
        <div class="col-md-5 col-xs-5 bord">Manufactured By</div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" class="form-control" id="manufactured_by" ng-model="viewEditOrderReport.manufactured_by" name="sampleDetails[manufactured_by]" placeholder="manufactured By">
        </div>
    </div>
    <div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">t)</div>
        <div class="col-md-5 col-xs-5 bord">Supplied By</div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" class="form-control" id="supplied_by" ng-model="viewEditOrderReport.supplied_by" name="sampleDetails[supplied_by]" placeholder="supplied by">
        </div>
    </div>
	<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
        <div class="col-md-1 col-xs-2 bord">u)</div>
        <div class="col-md-5 col-xs-5 bord">Sample Condition</div>
        <div class="col-md-6 col-xs-5 bord">
            <input type="text" class="form-control" id="sample_condition" ng-model="viewEditOrderReport.sample_condition" name="sampleDetails[sample_condition]" placeholder="Sample Condition">
        </div>
    </div>
</div>