<div class="row" ng-hide="isVisibleViewPODiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">
                    <span class="pull-left">
                        <strong id="form_title">View&nbsp;
                            <span ng-if="viewOPOFieldEnabled == 1">PO&nbsp;:&nbsp;<span ng-bind="viewBranchWiseDPOPOModel.po_no"></span></span>
                            <span ng-if="viewOPOFieldEnabled == 2">Draft&nbsp;PO&nbsp;:&nbsp;<span ng-bind="viewBranchWiseDPOPOModel.dpo_no"></span></span>
                        </strong>
                    </span>
                </div>            
                <div class="navbar-form navbar-right" role="new" style="margin-top: 2px;">
                    <button ng-click="navigateItemPage();" class="btn btn-primary">Back</button>
                </div>
            </div>
            <div class="row">
                <!--PO Item Header Detail-->
                <div class="col-xs-12 form-group view-record">
                
                    <!-- Draft PO No-->
                    <div ng-if="viewOPOFieldEnabled == 2" class="col-xs-4 form-group">
                        <label for="dpo_no">Draft PO No.</label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.dpo_no" class="form-control bgWhite">
                    </div>
                    <!-- /Draft PO No-->
                                            
                    <!-- Draft PO Date-->
                    <div ng-if="viewOPOFieldEnabled == 2" class="col-xs-4 form-group">
                        <label for="dpo_date">Draft PO Date</label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.dpo_date" class="form-control bgWhite">
                    </div>
                    <!-- /Draft PO Date-->
                    
                    <!-- PO No-->
                    <div ng-if="viewOPOFieldEnabled == 1" class="col-xs-4 form-group">
                        <label for="po_no">PO No.</label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.po_no" class="form-control bgWhite">
                    </div>
                    <!-- /PO No-->
                    
                    <!-- PO Date-->
                    <div ng-if="viewOPOFieldEnabled == 1" class="col-xs-4 form-group">
                        <label for="po_date">PO Date</label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.po_date" class="form-control bgWhite">
                    </div>
                    <!-- /PO Date-->
                    
                    <!--Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-4 form-group">
                        <label for="division_id">Branch</label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.division_name" class="form-control bgWhite">
                    </div>
                    <!--/Branch -->

                    <!--Vendor-->
                    <div class="col-xs-4 form-group">
                        <label for="vendor_id">vendor</label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.vendor_name" class="capitalize form-control bgWhite">
                    </div>
                    <!--/Vendor-->
                    
                    <!--Payment Term-->
                    <div class="col-xs-4 form-group">
                        <label for="payment_term">Payment Term</label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.payment_term" class="capitalize form-control bgWhite">
                    </div>
                    <!--/Payment Term-->
                    
                    <!--Amendment No.-->
                    <div ng-if="viewBranchWiseDPOPOModel.amendment_no" class="col-xs-4 form-group">
                        <label for="amendment_no">Amendment No.</label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.amendment_no" class="form-control bgWhite">
                    </div>
                    <!--/Amendment No.-->
                    
                    <!-- Amendment Date-->
                    <div ng-if="viewBranchWiseDPOPOModel.amendment_date" class="col-xs-4 form-group">
                        <label for="amendment_date">Amendment Date</label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.amendment_date" class="form-control bgWhite">
                    </div>
                    <!-- /Amendment Date-->
                    
                    <!--Amendment Detail -->
                    <div ng-if="viewBranchWiseDPOPOModel.amendment_detail" class="col-xs-4 form-group">
                        <label for="amendment_detail">Amendment Detail<em class="asteriskRed">*</em></label>
                        <input disabled ng-model="viewBranchWiseDPOPOModel.amendment_detail" class="form-control bgWhite">
                    </div>
                    <!--/Amendment Detail -->
                    
                </div>
                <!--/PO Item Header Detail-->
                
                <!--PO Detail-->
                <div class="col-xs-12 form-group view-record" id="no-more-tables">
                    <table class="col-sm-12 table-striped table-condensed cf tableThBg tableWidth">
                        
                        <thead>
                            <tr>
                                <th><label>Item* </label></th>			
                                <th><label>Description</label></th>				
                                <th><label>Item Qty</label></th>	
                                <th><label class="tdWidth">Rate</label></th>
                                <th><label class="tdWidth">Amount(Rs.)</label></th>
                            </tr>
                        </thead>
                            
                        <!--PO Item Detail-->
                        <tbody ng-repeat="po_input_obj in view_po_inputs">
                            <tr>
                                <td>
                                    <input disabled ng-model="po_input_obj.item_code" class="form-control bgWhite">
                                </td>
                                <td>
                                    <input disabled ng-model="po_input_obj.item_description" class="form-control bgWhite">
                                </td>
                                <td>
                                    <input disabled ng-model="po_input_obj.purchased_qty" class="form-control bgWhite">
                                </td>
                                <td>
                                    <input disabled ng-model="po_input_obj.item_rate" class="form-control bgWhite">
                                </td>
                                <td>
                                    <input disabled ng-model="po_input_obj.item_amount" class="form-control bgWhite">
                                </td>                                
                            </tr>
                        </tbody>
                        <!--/PO Item Detail-->
                        
                        <!--PO Tax Detail-->                            
                        <tfoot>
                            <tr class="view-record">
                                <td class="width20">&nbsp;</td>
                                <td class="width20"><strong class="right-nav">Total Qty:</strong></td>
                                <td class="width20">
                                    <div class="form-group">
                                        <input disabled ng-model="viewBranchWiseDPOPOModel.total_qty" class="form-control bgWhite">
                                    </div>
                                </td>
                                <td class="width20"><strong class="right-nav">Gross Total:</strong></td>
                                <td class="width20">
                                    <input disabled ng-model="viewBranchWiseDPOPOModel.gross_total" class="form-control bgWhite">
                                </td>
                            </tr>
                            <tr class="view-record">
                                <td class="width20">&nbsp;</td>
                                <td class="width20">&nbsp;</td>
                                <td class="width20">&nbsp;</td>
                                <td class="width20">
                                    <div class="form-group right-nav">
                                        <span class="dpopolabel"><strong>Discount(%)&nbsp;</strong></span>
                                        <span class="dpopotext">
                                            <input disabled ng-model="viewBranchWiseDPOPOModel.item_discount" class="form-control bgWhite">
                                        </span>
                                    </div>
                                </td>
                                <td class="width20">
                                    <div class="form-group">
                                        <input disabled ng-model="viewBranchWiseDPOPOModel.amount_after_discount" class="form-control bgWhite">
                                    </div>
                                </td>
                            </tr>
                            <tr class="view-record">
                                <td class="width20">&nbsp;</td>
                                <td class="width20">&nbsp;</td>
                                <td class="width20">&nbsp;</td>
                                <td class="width20">
                                    <div class="form-group right-nav">
                                        <span class="dpopolabel"><strong>Excise(%)&nbsp;</strong></span>
                                        <span class="dpopotext">
                                            <input disabled ng-model="viewBranchWiseDPOPOModel.excise_duty_rate" class="form-control bgWhite">
                                        </span>
                                    </div>
                                </td>
                                <td class="width20">
                                    <div class="form-group">
                                        <input disabled ng-model="viewBranchWiseDPOPOModel.amount_after_excise_duty_rate" class="form-control bgWhite">
                                    </div>
                                </td>
                            </tr>
                            <tr class="view-record">
                                <td class="width20">&nbsp;</td>
                                <td class="width20">&nbsp;</td>
                                <td class="width20">&nbsp;</td>
                                <td class="width20">
                                    <div class="form-group right-nav">
                                        <span class="dpopolabel"><strong>Sale Tax(%)&nbsp;</strong></span>
                                        <span class="dpopotext">
                                            <input disabled ng-model="viewBranchWiseDPOPOModel.sales_tax_rate" class="form-control bgWhite">
                                        </span>
                                    </div>
                                </td>
                                <td class="width20">
                                    <div class="form-group">
                                        <input disabled ng-model="viewBranchWiseDPOPOModel.amount_after_sales_tax_rate" class="form-control bgWhite">
                                    </div>
                                </td>
                            </tr>
                            <tr class="view-record">
                                <td class="width20">&nbsp;</td>
                                <td class="width20">&nbsp;</td>
                                <td class="width20">&nbsp;</td>
                                <td class="width20">
                                    <strong class="right-nav">Grand Total:</strong>
                                </td>
                                <td class="width20">
                                    <div class="form-group">
                                        <input disabled ng-model="viewBranchWiseDPOPOModel.grand_total" class="form-control bgWhite">
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                        <!--/PO Tax Detail-->
                        
                    </table>
                </div>
                <!--/PO Detail-->
                                    
                <!--Save Button-->
                <div class="col-xs-12 form-group text-right mT20">
                    <span ng-if="viewOPOFieldEnabled == 1 && viewDPOPOStatus == 1"><button title="Edit" class="btn btn-primary" ng-click="funOpenEditDPOForm(viewBranchWiseDPOPOModel.po_hdr_id)">Amend Purchase Order</button></span>
                    <span ng-if="viewOPOFieldEnabled == 1 && viewDPOPOStatus == 1"><button title="Edit" class="btn btn-primary" ng-click="funFinalizePO(viewBranchWiseDPOPOModel.po_hdr_id,divisionID,dpoPoType)">PO Shortclosing</button></span>
                    <span ng-if="viewOPOFieldEnabled == 2 && viewDPOPOStatus == 1"><button title="Edit" class="btn btn-primary" ng-click="funOpenEditDPOForm(viewBranchWiseDPOPOModel.po_hdr_id)">Edit Draft Purchase Order</button></span>
                    <span ng-if="viewOPOFieldEnabled == 2 && viewDPOPOStatus == 1"><button title="Edit" class="btn btn-primary" ng-click="funConvertDPOToPO(viewBranchWiseDPOPOModel.po_hdr_id,divisionID,dpoPoType)">Convert to Purchase Order</button></span>
                    <span ng-if="viewOPOFieldEnabled == 1 && viewDPOPOStatus == 2"><button title="Closed" ng-click="navigateItemPage();" class="btn btn-success">Po Closed</button></span>
                    <span ng-if="viewOPOFieldEnabled == 2 && viewDPOPOStatus == 2"><button title="Closed" ng-click="navigateItemPage();" class="btn btn-success">Draft Po Closed</button></span>
                </div>
                <!--Save Button-->
                    
            </div>
        </div>
    </div>
</div>