<div class="col-md-12" id="editablePartA" ng-if="viewEditPartAForm">
    <span ng-if="viewOrderReport.status == 5">
        <span ng-if="{{ (defined('IS_REPORTER') && IS_REPORTER) || (defined('IS_REVIEWER') && IS_REVIEWER) || (defined('IS_ADMIN') && IS_ADMIN) }} && viewOrderReport.orderAmendStatus==1">
            <span class="upr-case">Amendment No:</span>
            <span>
                <input class="" ng-model="viewOrderReport.amended_no" type="checkbox" name="is_amended_no" value="A" ng-checked="checkedAmendmentNo" ng-disabled="disabledAmendmentNo">
                <input class="" type="hidden" name="amended_status" value="[[viewOrderReport.orderAmendStatus]]">
            </span>
        </span>
        <span ng-if="{{ (defined('IS_REPORTER') && IS_REPORTER) || (defined('IS_REVIEWER') && IS_REVIEWER) || (defined('IS_ADMIN') && IS_ADMIN) }}">
            <a ng-click="toggelEditReportPartAForm()" class="cursor-pointer editLinkCss">Close</a>
        </span>
    </span>

    <table class="table table-bordered" style="margin:0;">
        <tbody>
            <tr>
                <td colspan="2">
                    <span class="bold font_lable editableSampleName">Sample Name</span>
                    <span class="bold">:</span>
                    <input class="width_45 width_45_display form-control" type="text" name="sampleDetails[sample_description]" ng-model="viewEditOrderReport.sample_description" placeholder="Sample Description">
                </td>
            </tr>
            <tr>
                <td class="width50"><span class="bold font_lable ">Supplied By</span><span class="bold">:</span>
                    <span class="text-input-part-A">
                        <input type="text" class="width_45 width_45_display form-control" id="supplied_by" ng-model="viewOrderReport.supplied_by" name="sampleDetails[supplied_by]" placeholder="supplied by">
                    </span>
                </td>
                <td class="width50"><span class="bold font_lable">Report Date</span><span class="bold">:</span>
                    <span ng-if="!viewOrderReport.report_date"> </span>
                    <span ng-if="viewOrderReport.report_date"> [[viewOrderReport.report_date]]</span>
                </td>
            </tr>
            <tr>
                <td class="width50"><span class="bold font_lable">Manufactured By</span><span class="bold">:</span>
                    <span class="text-input-part-A">
                        <input type="text" class="width_45 width_45_display form-control" id="manufactured_by" ng-model="viewOrderReport.manufactured_by" name="sampleDetails[manufactured_by]" placeholder="manufactured By">
                    </span>
                </td>
                <td><span class="bold font_lable">Booking Code</span><span class="bold">:</span>
                    <span ng-if="viewOrderReport.barcode">[[viewOrderReport.order_no]]</span>
                    <span ng-if="!viewOrderReport.barcode">-</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:0px!important;">
                    <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0" ng-if="viewOrderReport.nabl_no">
                        <tr>
                            <td class=" bodr_top bodr_bottom width50 border_left">
                                <span class="bold font_lable">Submitted By</span>
                                <span class="bold">:</span>
                                <span class="text-value" ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]]</span>
                                <span class="text-value" ng-if="!viewOrderReport.customer_name"></span>
                            </td>
                            <td class="width50 border_right">
                                <span ng-if="viewOrderReport.nabl_no">
                                    <span class="bold font_lable">NABL Code</span><span class="bold">:</span>
                                    <span ng-if="viewOrderReport.nabl_no">[[viewOrderReport.nabl_no]]</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="bodr_top width50 border_left">
                                <span class="bold font_lable">Mfg. Lic. No.</span>
                                <span class="bold">:</span>
                                <span class="text-value" ng-if="viewOrderReport.mfg_lic_no">[[viewOrderReport.mfg_lic_no]]</span>
                                <span class="text-value" ng-if="!viewOrderReport.mfg_lic_no"></span>
                            </td>
                            <td class="bodr_top bodr_bottom border_right">
                                <span class="bold font_lable">Booking Date</span>
                                <span class="bold">:</span>
                                <span class="text-value" ng-if="viewOrderReport.order_date">[[viewOrderReport.order_date]]</span>
                                <span class="text-value" ng-if="!viewOrderReport.order_date"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bodr_bottom border_left">
                                <span class="bold editableSampleName">Address</span>
                                <span class="bold">:</span>
                                <span class="text-value" ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] -
                                    [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] )</span>
                                <span class="text-value" ng-if="!viewOrderReport.customer_name"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="width50 border_right border_bottom">
                                <span class="bold font_lable">Party Ref. No</span><span class="bold">:</span>
                                <span ng-if="viewOrderReport.reference_no">[[viewOrderReport.reference_no]]</span>
                                <span ng-if="!viewOrderReport.reference_no"></span>
                            </td>
                            <td class="width50 border_bottom"></td>
                        </tr>
                    </table>

                    <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0" ng-if="!viewOrderReport.nabl_no">
                        <tr>
                            <td class=" bodr_top bodr_bottom width50 border_left">
                                <span class="bold font_lable">Submitted By</span>
                                <span class="bold">:</span>
                                <span class="text-value" ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]]</span>
                                <span class="text-value" ng-if="!viewOrderReport.customer_name"></span>
                            </td>
                            <td class="bodr_top bodr_bottom border_right">
                                <span class="bold font_lable">Booking Date</span>
                                <span class="bold">:</span>
                                <span class="text-value" ng-if="viewOrderReport.order_date">[[viewOrderReport.order_date]]</span>
                                <span class="text-value" ng-if="!viewOrderReport.order_date"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bodr_bottom border_left">
                                <span class="bold editableSampleName">Address</span>
                                <span class="bold">:</span>
                                <span class="text-value" ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] -
                                    [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] )</span>
                                <span class="text-value" ng-if="!viewOrderReport.customer_name"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="bodr_top width50 border_left border_bottom">
                                <span class="bold font_lable">Mfg. Lic. No.</span>
                                <span class="bold">:</span>
                                <span class="text-value" ng-if="viewOrderReport.mfg_lic_no">[[viewOrderReport.mfg_lic_no]]</span>
                                <span class="text-value" ng-if="!viewOrderReport.mfg_lic_no"></span>
                            </td>
                            <td><span class="bold font_lable border_right border_bottom">Party Ref. No</span>
                                <span class="bold">:</span>
                                <span ng-if="viewOrderReport.reference_no">[[viewOrderReport.reference_no]]</span>
                                <span ng-if="!viewOrderReport.reference_no"></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tr>
            <tr>
                <td colspan="2" style="padding:0!important">
                    <table class="table table-bordered bodr_top bodr_bottom" style="background:transparent;margin:0">
                        <tr>
                            <td class=" bodr_top width25"><span class="bold font_lable">Batch No</span><span class="bold">:</span>
                                <span class="text-input-part-A">
                                    <input type="text" class="width_45  width_45_display form-control" ng-model="viewOrderReport.batch_no" ng-required="true" name="sampleDetails[batch_no]" placeholder="Batch Number">
                                    <span ng-messages="orderReportFormByReporter.sampleDetails[batch_no].$error" ng-if='orderReportFormByReporter.sampleDetails[batch_no].$dirty || orderReportFormByReporter.$submitted' role="alert">
                                        <span ng-message="required" class="error">Batch No. is required</span>
                                    </span>
                                </span>
                            </td>
                            <td class="bodr_top width25"><span class="bold font_lable">Batch Size</span><span class="bold">:</span>
                                <span class="text-input-part-A">
                                    <input type="text" class="width_45 width_45_display form-control" ng-model="viewOrderReport.batch_size" name="sampleDetails[batch_size]" placeholder="Batch Size">
                                </span>
                            </td>
                            <td class="bodr_top width25"><span class="bold font_lable">Party Ref. Date</span><span class="bold">:</span>
                                <span ng-if="viewOrderReport.letter_no">[[viewOrderReport.letter_no]]</span>
                                <span ng-if="!viewOrderReport.letter_no"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="bodr_bottom"><span class="bold font_lable">D/M</span><span class="bold">:</span>
                                <span class="text-input-part-A">
                                    <input type="text" class="width_45 width_45_display form-control" ng-model="viewOrderReport.mfg_date" name="sampleDetails[mfg_date]" placeholder="Date of Mfg.">
                                </span>
                            </td>

                            <td class="bodr_bottom"><span class="bold font_lable">D/E</span><span class="bold">:</span>
                                <span class="text-input-part-A">
                                    <input type="text" class="width_45 width_45_display form-control" ng-model="viewOrderReport.expiry_date" name="sampleDetails[expiry_date]" placeholder="Date of Expiry">
                                </span>
                            </td>

                            <td class="bodr_bottom" style="border-bottom:0px!important;">
                                <span class="bold font_lable">Sample Qty</span><span class="bold">:</span>
                                <input type="text" class="width_45 width_45_display form-control " ng-model="viewOrderReport.sample_qty" name="sampleDetails[sample_qty]" placeholder="sample qty">
                            </td>
                            <!--<td class="bodr_bottom" style="border-bottom:0px!important;"></td>-->
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
</div>