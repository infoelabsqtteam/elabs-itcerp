<div class="row" ng-hide="isVisibleAddPODiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Add New Draft PO/PO</strong></span>
                </div>            
                <div class="navbar-form navbar-right" role="new" style="margin-top: 2px;">
                    <button ng-click="navigateItemPage();" class="btn btn-primary">Back</button>
                </div>
            </div>
            <div class="row">
                <form name="erpAddBranchWisePOForm" id="erpAddBranchWisePOForm" method="POST" novalidate>
                    <!--PO Item Header Detail-->
                    <div class="col-xs-12 form-group view-record">
                    
                        <!-- Draft PO No-->
                        <div class="col-xs-4 form-group">
                            <label for="dpo_no">Draft PO No.</label>
                            <input readonly
                                   ng-init="funGenerateDraftPONumber()"
                                   type="text"
                                   id="dpo_no"
                                   ng-value="defaultDpoNo"
                                   ng-model="addBranchWiseDPOPOModel.dpo_no"                                       
                                   name="dpo_no"
                                   class="form-control bgWhite"
                                   placeholder="Draft PO No">
                            <span ng-messages="erpAddBranchWisePOForm.dpo_no.$error" ng-if="erpAddBranchWisePOForm.dpo_no.$dirty || erpAddBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Draft PO No is required</span>
                            </span>
                        </div>
                        <!-- /Draft PO No-->
                                                
                        <!-- Draft PO Date-->
                        <div class="col-xs-4 form-group">
                            <label for="dpo_date">Draft PO Date</label>		
                            <div class="input-group date" data-provide="datepicker">
                                <input readonly type="text"
                                       id="dpo_date"
                                       ng-model="addBranchWiseDPOPOModel.dpo_date"                                       
                                       name="dpo_date"
                                       class="form-control bgWhite"
                                       placeholder="Draft PO Date">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                            </div>
                            <span ng-messages="erpAddBranchWisePOForm.dpo_date.$error" ng-if="erpAddBranchWisePOForm.dpo_date.$dirty || erpAddBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Draft PO Date is required</span>
                            </span>
                        </div>
                        <!-- /Draft PO Date-->
                        
                        <!-- PO Date-->
                        <div class="col-xs-4 form-group">
                            <label for="po_date">PO Date</label>		
                            <div class="input-group date" data-provide="datepicker">
                                <input readonly type="text"
                                       id="po_date"
                                       ng-model="addBranchWiseDPOPOModel.po_date"
                                       name="po_date"
                                       class="form-control bgWhite"
                                       placeholder="PO Date">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                            </div>
                            <span ng-messages="erpAddBranchWisePOForm.po_date.$error" ng-if="erpAddBranchWisePOForm.po_date.$dirty || erpAddBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">PO Date is required</span>
                            </span>
                        </div>
                        <!-- /PO Date-->
                        
                        <!--Branch -->
                        <div ng-if="{{$division_id}} == 0" class="col-xs-4 form-group">
                            <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                            <select class="form-control"
                                name="division_id"
                                id="division_id"
                                ng-required="true"
                                ng-model="addBranchWiseDPOPOModel.division_id"
                                ng-change="funGetDivisionWiseVendors(addBranchWiseDPOPOModel.division_id.id)"
                                ng-options="addDivision.name for addDivision in divisionsCodeList track by addDivision.division_id">
                                <option value="">Select Branch</option>
                            </select>
                            <span ng-messages="erpAddBranchWisePOForm.division_id.$error" ng-if="erpAddBranchWisePOForm.division_id.$dirty  || erpAddBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Branch is required</span>
                            </span>
                        </div>
                        <div ng-if="{{$division_id}} > 0">
                            <input type="hidden" value="{{$division_id}}" name="division_id" ng-model="addBranchWisePOModel.division_id" id="division_id">
                        </div>
                        <!--/Branch -->

                        <!--Vendor-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_id">vendor<em class="asteriskRed">*</em></label>							
                            <select class="form-control"
                                    name="vendor_id"                                
                                    ng-model="addBranchWiseDPOPOModel.vendor_id"
                                    id="vendor_id"                                
                                    ng-options="vendors.vendor_name for vendors in vendorDataList track by vendors.vendor_id"
                                    @if($division_id > 0)ng-init="funGetDivisionWiseVendors({{$division_id}})"@endif ng-required="true">
                                <option value="">Select Vendor</option>
                            </select>
                            <span ng-messages="erpAddBranchWisePOForm.vendor_id.$error" ng-if="erpAddBranchWisePOForm.vendor_id.$dirty  || erpAddBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Vendor is required</span>
                            </span>
                        </div>
                        <!--/Vendor-->
                        
                        <!--Payment Term-->
                        <div class="col-xs-4 form-group">
                            <label for="payment_term">Payment Term<em class="asteriskRed">*</em></label>							
                            <select class="form-control"
                                    name="payment_term"
                                    ng-model="addBranchWiseDPOPOModel.payment_term"
                                    id="payment_term"
                                    ng-required="true">
                                <option value="">Select Payment Term</option>
                                @if(!empty($paymentTerm))
                                    @foreach($paymentTerm as $key => $value)
                                        <option value="{{strtolower($value)}}">{{ucwords($value)}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span ng-messages="erpAddBranchWisePOForm.payment_term.$error" ng-if="erpAddBranchWisePOForm.payment_term.$dirty  || erpAddBranchWisePOForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Payment Term is required</span>
                            </span>
                        </div>
                        <!--/Payment Term-->
                        
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
                                        <label><a title="Add New Row" id="#addNewPORow" href="javascript:;" ng-click="addPORow()"><i class="font15 mL5 glyphicon glyphicon-plus"></i></a></label>
                                    </th>
                                </tr>
                            </thead>
                                
                            <!--PO Item Detail-->
                            <tbody select-item-fields-add ng-repeat="po_input in po_inputs"></tbody>
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
                                                id="total_qty"
                                                ng-model="addBranchWiseDPOPOModel.total_qty" 
                                                class="bgWhite form-control" 
                                                placeholder="Total Qty" />	
                                        </div>
                                    </td>
                                    <td class="width20"><strong class="right-nav">Gross Total:</strong></td>
                                    <td class="width20">
                                        <div class="form-group">
                                            <input type="text" readonly
                                                name="gross_total" 
                                                id="gross_total"
                                                ng-model="addBranchWiseDPOPOModel.gross_total"
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
                                                    id="item_discount"
                                                    ng-model="addBranchWiseDPOPOModel.item_discount" 
                                                    class="form-control"
                                                    ng-change="updateItemIndividualAmount([[$index]])"
                                                    placeholder="0.00" />
                                            </span>
                                        </div>
                                    </td>
                                    <td class="width20">
                                        <div class="form-group">
                                            <input type="text" readonly
                                                id="amount_after_discount"
                                                class="bgWhite form-control"
                                                name="amount_after_discount"
                                                ng-model="addBranchWiseDPOPOModel.amount_after_discount" 
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
                                                    id="excise_duty_rate"
                                                    ng-model="addBranchWiseDPOPOModel.excise_duty_rate" 
                                                    class="form-control"
                                                    ng-required="true"
                                                    ng-change="updateItemIndividualAmount([[$index]])"
                                                    placeholder="0.00" />
                                            </span>
                                        </div>
                                    </td>
                                    <td class="width20">
                                        <div class="form-group">
                                            <input type="text" readonly
                                                id="amount_after_excise_duty_rate"
                                                class="bgWhite form-control"
                                                name="amount_after_excise_duty_rate"
                                                ng-model="addBranchWiseDPOPOModel.amount_after_excise_duty_rate" 
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
                                                    id="sales_tax_rate"
                                                    ng-model="addBranchWiseDPOPOModel.sales_tax_rate" 
                                                    ng-required="true"
                                                    class="form-control"
                                                    ng-change="updateItemIndividualAmount([[$index]])"
                                                    placeholder="0.00" />
                                            </span>
                                        </div>
                                    </td>
                                    <td class="width20">
                                        <div class="form-group">
                                            <input type="text" readonly
                                                id="amount_after_sales_tax_rate"
                                                class="bgWhite form-control"
                                                name="amount_after_sales_tax_rate"
                                                ng-model="addBranchWiseDPOPOModel.amount_after_sales_tax_rate" 
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
                                                id="grand_total"
                                                ng-model="addBranchWiseDPOPOModel.grand_total"
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
                        <button type="submit" ng-disabled="erpAddBranchWisePOForm.$invalid" class="btn btn-primary" ng-click="funAddBranchWiseDraftPO(divisionID,dpoPoType,2)">Create Draft PO</button>
                        <button type="submit" ng-disabled="erpAddBranchWisePOForm.$invalid" class="btn btn-primary" ng-click="funAddBranchWisePO(divisionID,dpoPoType,1)">Create PO</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                    <!--Save Button-->
                        
                </form>
            </div>
        </div>
    </div>
</div>