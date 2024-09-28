<div class="row" ng-hide="isVisibleViewIGNDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">View IGN:<span ng-bind="viewBranchWiseIGN.ign_no"></span></strong></span>
                </div>
                <div class="navbar-form navbar-right" role="new" style="margin-top: 2px;">
                    <button ng-click="navigateItemPage(2);" class="btn btn-primary">Back</button>
                </div>
            </div>
            <div class="row">
                <form name="erpViewBranchWiseIGNForm" id="erpViewBranchWiseIGNForm" method="POST" novalidate>
                    <!--IGN Header Detail-->
                    <div class="col-xs-12 form-group view-record">
                    
                        <!-- IGN No-->
                        <div class="col-xs-4 form-group">
                            <label for="ign_no">IGN No.</label>
                            <input disabled ng-model="viewBranchWiseIGN.ign_no" class="form-control bgWhite">
                        </div>
                        <!-- /IGN No-->
                                                
                        <!-- IGN Date-->
                        <div class="col-xs-4 form-group">
                            <label for="ign_date">IGN Date</label>
                            <input disabled ng-model="viewBranchWiseIGN.ign_date" class="form-control bgWhite">
                        </div>
                        <!-- /IGN Date-->
                        
                        <!--Branch -->
                        <div ng-if="{{$division_id}} == 0" class="col-xs-4 form-group">
                            <label for="division_id">Branch</label>
                            <input disabled ng-model="viewBranchWiseIGN.division_name" class="form-control bgWhite">                            
                        </div>
                        <!--/Branch -->
                        
                        <!--Vendor-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_id">vendor</label>
                            <input disabled ng-model="viewBranchWiseIGN.vendor_name" class="capitalize form-control bgWhite">
                        </div>
                        <!--/Vendor-->
                        
                        <!-- Vendor Bill No-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_bill_no">Vendor Bill No.</label>
                            <input disabled ng-model="viewBranchWiseIGN.vendor_bill_no" class="capitalize form-control bgWhite">
                        </div>
                        <!-- /Vendor Bill No-->
                        
                        <!-- Vendor Bill Date-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_bill_date">Vendor Bill Date</label>
                            <input disabled ng-model="viewBranchWiseIGN.vendor_bill_date" class="capitalize form-control bgWhite">
                        </div>
                        <!-- /Vendor Bill Date-->
                        
                        <!-- gate Pass No-->
                        <div class="col-xs-4 form-group">
                            <label for="gate_pass_no">Gate Pass No.</label>
                            <input disabled ng-model="viewBranchWiseIGN.gate_pass_no" class="capitalize form-control bgWhite">
                        </div>
                        <!-- /Vendor Bill No-->
                        
                        <!--Employee-->
                        <div class="col-xs-4 form-group">
                            <label for="employee_id">Employee</label>
                            <input disabled ng-model="viewBranchWiseIGN.employee_name" class="capitalize form-control bgWhite">
                        </div>
                        <!--/Employee-->
                        
                        <!--Employee Detail -->
                        <div class="col-xs-4 form-group">
                            <label for="employee_detail">Employee Detail</label>
                            <input disabled ng-model="viewBranchWiseIGN.employee_detail" class="form-control bgWhite">
                        </div>
                        <!--/Employee Detail -->                               
                        
                    </div>
                    <!--/IGN Header Detail-->
                    
                    <!--IGN Item Detail-->
                    <div class="col-xs-12 form-group view-record" id="no-more-tables">
                        <table class="col-sm-12 table-striped table-condensed cf tableThBg tableWidth">
                            
                            <thead>
                                <tr>
                                    <th><label>Item</label></th>			
                                    <th><label>PO No.</label></th>				
                                    <th><label>Expiry Date</label></th>	
                                    <th><label>Bill Qty</label></th>
                                    <th><label>Received Qty</label></th>
                                    <th><label>OK Qty</label></th>
                                    <th><label>Bill Rate</label></th>
                                    <th><label>Pass Rate</label></th>
                                    <th><label>Landing Cost(Rs.)</label></th>
                            </thead>
                                
                            <!--IGN Item Detail-->
                            <tbody ng-repeat="view_ign_input_obj in view_ign_inputs">
                                <tr>
                                    <td>
                                        <input disabled ng-model="view_ign_input_obj.item_code" class="width115 form-control bgWhite">
                                    </td>
                                    <td>
                                        <input ng-if="view_ign_input_obj.po_no.length" disabled ng-model="view_ign_input_obj.po_no" class="width155 form-control bgWhite">
                                        <input ng-if="!view_ign_input_obj.po_no.length" disabled value="-" class="width155 form-control bgWhite">
                                    </td>
                                    <td>
                                        <input disabled ng-model="view_ign_input_obj.expiry_date" class="form-control bgWhite">
                                    </td>
                                    <td>
                                        <input disabled ng-model="view_ign_input_obj.bill_qty" class="form-control bgWhite">
                                    </td>
                                    <td>
                                        <input disabled ng-model="view_ign_input_obj.received_qty" class="form-control bgWhite">
                                    </td>
                                    <td>
                                        <input disabled ng-model="view_ign_input_obj.ok_qty" class="form-control bgWhite">
                                    </td>
                                    <td>
                                        <input disabled ng-model="view_ign_input_obj.bill_rate" class="form-control bgWhite">
                                    </td>
                                    <td>
                                        <input disabled ng-model="view_ign_input_obj.pass_rate" class="form-control bgWhite">
                                    </td>
                                    <td>
                                        <input disabled ng-model="view_ign_input_obj.landing_cost" class="form-control bgWhite">
                                    </td>  
                                </tr>
                            </tbody>
                            <!--/IGN Item Detail-->
                                                     
                            <!--IGN Tax Detail-->                            
                            <tfoot>
                                <tr class="view-record">
                                    <td colspan="3">&nbsp;</td>
                                    <td><label class="right-nav">Total Bill Amt:</label></td>
                                    <td>
                                        <div class="form-group">
                                            <input disabled ng-model="viewBranchWiseIGN.total_bill_amount" class="capitalize form-control bgWhite">
                                        </div>
                                    </td>
                                    <td><label class="right-nav">Total Pass Amt:</label></td>
                                    <td>
                                        <div class="form-group">
                                            <input disabled ng-model="viewBranchWiseIGN.total_pass_amount" class="capitalize form-control bgWhite">
                                        </div>
                                    </td>
                                    <td><label class="right-nav">Total Landing Amt:</label></td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <input disabled ng-model="viewBranchWiseIGN.total_landing_amount" class="capitalize form-control bgWhite">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="view-record">
                                    <td colspan="3">&nbsp;</td>
                                    <td><label class="right-nav">Service Tax:</label></td>
                                    <td>
                                        <div class="form-group">
                                            <input disabled ng-model="viewBranchWiseIGN.total_sales_tax_amount" class="capitalize form-control bgWhite">
                                        </div>
                                    </td>
                                    <td><label class="right-nav">VAT:</label></td>
                                    <td>
                                        <div class="form-group">
                                            <input disabled ng-model="viewBranchWiseIGN.total_vat_amount" class="capitalize form-control bgWhite">
                                        </div>
                                    </td>
                                    <td><label class="right-nav">Excise Duty:</label></td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <input disabled ng-model="viewBranchWiseIGN.total_excise_duty_amount" class="capitalize form-control bgWhite">
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                            <!--/IGN Tax Detail-->
                            
                        </table>
                    </div>
                    <!--/IGN Item Detail-->
                                        
                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right">
                        <a href="javascript:;" ng-if="{{defined('EDIT') && EDIT}}" type="submit" class="btn btn-primary" ng-click="funOpenBranchWiseIGNDetailForm(viewBranchWiseIGN.ign_hdr_id)">Edit IGN</a>
                    </div>
                    <!--Save Button-->
                        
                </form>
            </div>
        </div>
    </div>
</div>