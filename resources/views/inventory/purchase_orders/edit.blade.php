<div class="row" ng-hide="isVisibleEditPODiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">   
                    <span class="pull-left">
                        <strong id="form_title">Edit&nbsp;
                            <span ng-if="editOPOFieldEnabled == 1">PO&nbsp;:&nbsp;<span ng-bind="editBranchWiseDPOPOModel.po_no"></span></span>
                            <span ng-if="editOPOFieldEnabled == 2">Draft&nbsp;PO&nbsp;:&nbsp;<span ng-bind="editBranchWiseDPOPOModel.dpo_no"></span></span>
                        </strong>
                    </span>
                </div>            
                <div class="navbar-form navbar-right" role="new" style="margin-top: 2px;">
                    <button ng-click="navigateItemPage();" class="btn btn-primary">Back</button>
                </div>
            </div>
            <div class="row">
                <form name="erpEditBranchWisePOForm" id="erpEditBranchWisePOForm" method="POST" novalidate>
                    
                    <!--PO Item Header Detail-->
                    <div class="col-xs-12 form-group view-record">
                    
                        <!-- Draft PO No-->
                        <div ng-if="editOPOFieldEnabled == 2" class="col-xs-4 form-group">
                            <label for="dpo_no">Draft PO No.</label>
                            <input readonly
                                   type="text"
                                   id="edit_dpo_no"
                                   ng-model="editBranchWiseDPOPOModel.dpo_no"                                       
                                   name="dpo_no"
                                   class="form-control bgWhite"
                                   placeholder="Draft PO No" />
                        </div>
                        <!-- /Draft PO No-->
                                                
                        <!-- Draft PO Date-->
                        <div ng-if="editOPOFieldEnabled == 2" class="col-xs-4 form-group">
                            <label for="dpo_date">Draft PO Date</label>		
                            <div class="input-group date" data-provide="datepicker">
                                <input readonly type="text"
                                       id="edit_dpo_date"
                                       ng-model="editBranchWiseDPOPOModel.dpo_date"                                       
                                       name="dpo_date"
                                       class="form-control bgWhite"
                                       placeholder="Draft PO Date" />
                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                            </div>
                            <span ng-messages="erpEditBranchWisePOForm.dpo_date.$error" ng-if="erpEditBranchWisePOForm.dpo_date.$dirty || erpEditBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Draft PO Date is required</span>
                            </span>
                        </div>
                        <!-- /Draft PO Date-->
                        
                        <!-- PO No-->
                        <div ng-if="editOPOFieldEnabled == 1" class="col-xs-4 form-group">
                            <label for="po_no">PO No.</label>
                            <input readonly
                                   type="text"
                                   id="edit_po_no"
                                   ng-model="editBranchWiseDPOPOModel.po_no"                                       
                                   name="po_no"
                                   class="form-control bgWhite"
                                   placeholder="PO No" />
                        </div>
                        <!-- /PO No-->
                                                
                        <!-- PO Date-->
                        <div ng-if="editOPOFieldEnabled == 1" class="col-xs-4 form-group">
                            <label for="po_date">PO Date</label>		
                            <div class="input-group date" data-provide="datepicker">
                                <input readonly type="text"
                                       id="edit_po_date"
                                       ng-model="editBranchWiseDPOPOModel.po_date"
                                       name="po_date"
                                       class="form-control bgWhite"
                                       placeholder="PO Date" />
                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                            </div>
                            <span ng-messages="erpEditBranchWisePOForm.po_date.$error" ng-if="erpEditBranchWisePOForm.po_date.$dirty || erpEditBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">PO Date is required</span>
                            </span>
                        </div>
                        <!-- /PO Date-->
                        
                        <!--Branch -->
                        <div ng-if="{{$division_id}} == 0" class="col-xs-4 form-group">
                            <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                            <select class="form-control"
                                name="division_id"
                                id="edit_division_id"
                                ng-required="true"
                                ng-model="editBranchWiseDPOPOModel.division_id.selectedOption"
                                ng-change="funGetDivisionWiseVendors(editBranchWiseDPOPOModel.division_id.id)"
                                ng-options="addDivision.name for addDivision in divisionsCodeList track by addDivision.division_id">
                                <option value="">Select Branch</option>
                            </select>
                            <span ng-messages="erpEditBranchWisePOForm.division_id.$error" ng-if="erpEditBranchWisePOForm.division_id.$dirty  || erpEditBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Branch is required</span>
                            </span>
                        </div>
                        <div ng-if="{{$division_id}} > 0">
                            <input type="hidden" value="{{$division_id}}" name="division_id" ng-model="erpEditBranchWisePOForm.division_id" id="division_id">
                        </div>
                        <!--/Branch -->

                        <!--Vendor-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_id">vendor<em class="asteriskRed">*</em></label>							
                            <select class="form-control"
                                    name="vendor_id"                                
                                    ng-model="editBranchWiseDPOPOModel.vendor_id.selectedOption"
                                    id="edit_vendor_id"                                
                                    ng-options="vendors.vendor_name for vendors in vendorDataList track by vendors.vendor_id"
                                    @if($division_id > 0)ng-init="funGetDivisionWiseVendors({{$division_id}})"@endif ng-required="true">
                                <option value="">Select Vendor</option>
                            </select>
                            <span ng-messages="erpEditBranchWisePOForm.vendor_id.$error" ng-if="erpEditBranchWisePOForm.vendor_id.$dirty  || erpEditBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Vendor is required</span>
                            </span>
                        </div>
                        <!--/Vendor-->
                        
                        <!--Payment Term-->
                        <div class="col-xs-4 form-group">
                            <label for="payment_term">Payment Term<em class="asteriskRed">*</em></label>							
                            <select class="form-control"
                                    name="payment_term"
                                    ng-model="editBranchWiseDPOPOModel.payment_term.selectedOption"
                                    ng-options="paymentTerm.payment_term_name for paymentTerm in paymentTermList.availableTypeOptions track by paymentTerm.payment_term"
                                    id="edit_payment_term"
                                    ng-required="true">
                                <option value="">Select Payment Term</option>
                            </select>
                            <span ng-messages="erpEditBranchWisePOForm.payment_term.$error" ng-if="erpEditBranchWisePOForm.payment_term.$dirty  || erpEditBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Payment Term is required</span>
                            </span>
                        </div>
                        <!--/Payment Term-->
                        
                        <!--Amendment No.-->
                        <div ng-if="editOPOFieldEnabled == 1" class="col-xs-4 form-group">
                            <label for="amendment_no">Amendment No.<em class="asteriskRed">*</em></label>						   
                            <input readonly
                                type="text"
                                class="form-control" 
                                ng-model="editBranchWiseDPOPOModel.amendment_no"
                                name="amendment_no" 
                                id="edit_amendment_no"
                                ng-required='true'
                                placeholder="Amendment No." />
                            <span ng-messages="erpEditBranchWisePOForm.amendment_no.$error" ng-if="erpEditBranchWisePOForm.amendment_no.$dirty || erpEditBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Amendment No. is required</span>
                            </span>
                        </div>
                        <!--/Amendment No.-->
                        
                        <!-- Amendment Date-->
                        <div ng-if="editOPOFieldEnabled == 1" class="col-xs-4 form-group">
                            <label for="amendment_date">Amendment Date<em class="asteriskRed">*</em></label>		
                            <div class="input-group date" data-provide="datepicker">
                                <input readonly type="text"
                                       id="edit_amendment_date"
                                       ng-model="editBranchWiseDPOPOModel.amendment_date"
                                       ng-required="true"
                                       name="amendment_date"
                                       class="form-control bgWhite"
                                       placeholder="Amendment Date">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                            </div>
                            <span ng-messages="erpEditBranchWisePOForm.amendment_date.$error" ng-if="erpEditBranchWisePOForm.amendment_date.$dirty || erpEditBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Amendment Date is required</span>
                            </span>
                        </div>
                        <!-- /Amendment Date-->
                        
                        <!--Amendment Detail -->
                        <div ng-if="editOPOFieldEnabled == 1" class="col-xs-4 form-group">
                            <label for="amendment_detail">Amendment Detail<em class="asteriskRed">*</em></label>
                            <textarea rows="2"
                                class="form-control" 
                                ng-model="editBranchWiseDPOPOModel.amendment_detail" 
                                name="amendment_detail" 
                                id="edit_amendment_detail"
                                ng-required='true'
                                placeholder="Amendment Detail" /></textarea>
                            <span ng-messages="erpEditBranchWisePOForm.amendment_detail.$error" ng-if="erpEditBranchWisePOForm.amendment_detail.$dirty  || erpEditBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Amendment Detail is required</span>
                            </span>
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
                                    <th><label>Item Qty<em class="asteriskRed">*</em></label></th>	
                                    <th><label class="tdWidth">Rate<em class="asteriskRed">*</em></label></th>
                                    <th><label class="tdWidth">Amount(Rs.)</label></th>
                                    <th>
                                        <label><a title="Add New Row" id="#editPORow" href="javascript:;" ng-click="editPORow()"><i class="font15 mL5 glyphicon glyphicon-plus"></i></a></label>
                                    </th>
                                </tr>
                            </thead>
                                
                            <!--PO Item Detail-->
                            <tbody ng-repeat="edit_po_inputs_obj in edit_po_inputs">
                                @include('inventory.purchase_orders.edit_po_inputs')
                            </tbody>
                            <!--/PO Item Detail-->
                            
                            <!--PO Tax Detail-->                            
                            <tfoot>
                                <tr class="view-record">
                                    <td class="width20">&nbsp;</td>
                                    <td class="width20"><strong class="right-nav">Total Qty:</strong></td>
                                    <td class="width20">
                                        <div class="form-group">
                                            <input type="text" readonly
                                                name="total_qty" 
                                                id="edit_total_qty"
                                                ng-model="editBranchWiseDPOPOModel.total_qty" 
                                                class="bgWhite form-control" 
                                                placeholder="Total Qty" />	
                                        </div>
                                    </td>
                                    <td class="width20"><strong class="right-nav">Gross Total:</strong></td>
                                    <td class="width20">
                                        <div class="form-group">
                                            <input type="text" readonly
                                                name="gross_total" 
                                                id="edit_gross_total"
                                                ng-model="editBranchWiseDPOPOModel.gross_total"
                                                class="bgWhite form-control" 
                                                placeholder="Gross Total" />	
                                        </div>
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
                                                <input type="text"
                                                    name="item_discount" 
                                                    id="edit_item_discount"
                                                    ng-model="editBranchWiseDPOPOModel.item_discount" 
                                                    class="form-control"
                                                    ng-change="editUpdateItemIndividualAmount([[$index]])"
                                                    placeholder="0.00" />
                                            </span>
                                        </div>
                                    </td>
                                    <td class="width20">
                                        <div class="form-group">
                                            <input type="text" readonly
                                                id="edit_amount_after_discount"
                                                class="bgWhite form-control"
                                                name="amount_after_discount"
                                                ng-model="editBranchWiseDPOPOModel.amount_after_discount" 
                                                placeholder="0.00" />	
                                        </div>
                                    </td>
                                </tr>
                                <tr class="view-record">
                                    <td class="width20">&nbsp;</td>
                                    <td class="width20">&nbsp;</td>
                                    <td class="width20">&nbsp;</td>
                                    <td class="width20">
                                        <div class="form-group right-nav">
                                            <span class="dpopolabel"><strong>Excise(%)&nbsp;</strong><em class="asteriskRed">*</em></span>
                                            <span class="dpopotext">
                                                <input type="text"
                                                    name="excise_duty_rate" 
                                                    id="edit_excise_duty_rate"
                                                    ng-model="editBranchWiseDPOPOModel.excise_duty_rate" 
                                                    class="form-control"
                                                    ng-required="true"
                                                    ng-change="editUpdateItemIndividualAmount([[$index]])"
                                                    placeholder="0.00" />
                                            </span>
                                        </div>
                                    </td>
                                    <td class="width20">
                                        <div class="form-group">
                                            <input type="text" readonly
                                                id="edit_amount_after_excise_duty_rate"
                                                class="bgWhite form-control"
                                                name="amount_after_excise_duty_rate"
                                                ng-model="editBranchWiseDPOPOModel.amount_after_excise_duty_rate" 
                                                placeholder="0.00" />	
                                        </div>
                                    </td>
                                </tr>
                                <tr class="view-record">
                                    <td class="width20">&nbsp;</td>
                                    <td class="width20">&nbsp;</td>
                                    <td class="width20">&nbsp;</td>
                                    <td class="width20">
                                        <div class="form-group right-nav">
                                            <span class="dpopolabel"><strong>Sale Tax(%)&nbsp;</strong><em class="asteriskRed">*</em></span>
                                            <span class="dpopotext">
                                                <input type="text"
                                                    name="sales_tax_rate" 
                                                    id="edit_sales_tax_rate"
                                                    ng-model="editBranchWiseDPOPOModel.sales_tax_rate" 
                                                    ng-required="true"
                                                    class="form-control"
                                                    ng-change="editUpdateItemIndividualAmount([[$index]])"
                                                    placeholder="0.00" />
                                            </span>
                                        </div>
                                    </td>
                                    <td class="width20">
                                        <div class="form-group">
                                            <input type="text" readonly
                                                id="edit_amount_after_sales_tax_rate"
                                                class="bgWhite form-control"
                                                name="amount_after_sales_tax_rate"
                                                ng-model="editBranchWiseDPOPOModel.amount_after_sales_tax_rate" 
                                                placeholder="0.00" />	
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
                                            <input type="text" readonly
                                                name="grand_total" 
                                                id="edit_grand_total"
                                                ng-model="editBranchWiseDPOPOModel.grand_total"
                                                class="bgWhite form-control" 
                                                placeholder="0.00" />
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
                        <label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" id="po_hdr_id" ng-value="editBranchWiseDPOPOModel.po_hdr_id" ng-model="editBranchWiseDPOPOModel.po_hdr_id" name="po_hdr_id">
                        <!--Amending PO Button-->
                        <span ng-if="editOPOFieldEnabled == 1">
                            <button type="submit" ng-disabled="erpEditBranchWisePOForm.$invalid" class="btn btn-primary" ng-click="funAmendBranchWisePO(editBranchWiseDPOPOModel.po_hdr_id,divisionID,dpoPoType)">Amend</button>
                        </span>
                        <!--/Amending PO Button-->                        
                        <!--Editing DPO Button-->
                        <span ng-if="editOPOFieldEnabled == 2">
                            <button type="submit" class="btn btn-primary" ng-click="funUpdateBranchWiseDraftPO(editBranchWiseDPOPOModel.po_hdr_id,divisionID,dpoPoType)">Update</button>
                        </span>
                        <!--/Editing DPO Button-->
                    </div>
                    <!--Save Button-->
                    
                </form>
            </div>
        </div>
    </div>
</div>