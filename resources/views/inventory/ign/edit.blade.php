<div class="row" ng-hide="isVisibleEditIGNDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Edit IGN : <span ng-bind="editBranchWiseIGN.ign_no"></span></strong></span>
                </div>            
                <div class="navbar-form navbar-right" role="new" style="margin-top: 2px;">
                    <button ng-click="navigateItemPage(2);" class="btn btn-primary">Back</button>
                </div>
            </div>
            <div class="row">
                <form name="erpEditBranchWiseIGNForm" id="erpEditBranchWiseIGNForm" method="POST" novalidate>
                    <!--IGN Header Detail-->
                    <div class="col-xs-12 form-group view-record">
                    
                        <!-- IGN No-->
                        <div class="col-xs-4 form-group">
                            <label for="ign_no">IGN No.</label>
                            <input readonly
                                   type="text"
                                   id="edit_ign_no"
                                   ng-model="editBranchWiseIGN.ign_no"
                                   class="form-control bgWhite"
                                   placeholder="IGN No">                            
                        </div>
                        <!-- /IGN No-->
                                                
                        <!-- IGN Date-->
                        <div class="col-xs-4 form-group">
                            <label for="ign_date">IGN Date<em class="asteriskRed">*</em></label>		
                            <div class="input-group date" data-provide="datepicker">
                                <input readonly type="text"
                                       id="edit_ign_date"
                                       ng-model="editBranchWiseIGN.ign_date"                                       
                                       name="ign_date"
                                       ng-required="true"
                                       class="form-control bgWhite"
                                       placeholder="IGN Date">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                            </div>
                            <span ng-messages="erpEditBranchWiseIGNForm.ign_date.$error" ng-if="erpEditBranchWiseIGNForm.ign_date.$dirty || erpEditBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">IGN Date is required</span>
                            </span>
                        </div>
                        <!-- /IGN Date-->
                        
                        <!--Branch -->
                        <div ng-if="{{$division_id}} == 0" class="col-xs-4 form-group">
                            <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                            <select class="form-control"
                                name="division_id"
                                id="edit_division_id"
                                ng-required="true"
                                ng-model="editBranchWiseIGN.division_id.selectedOption"
                                ng-change="funGetDivisionWiseVendors(editBranchWiseIGN.division_id.selectedOption.division_id)"
                                ng-options="addDivision.name for addDivision in divisionsCodeList track by addDivision.division_id">
                                <option value="">Select Branch</option>
                            </select>
                            <span ng-messages="erpEditBranchWiseIGNForm.division_id.$error" ng-if="erpEditBranchWiseIGNForm.division_id.$dirty  || erpEditBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Branch is required</span>
                            </span>
                        </div>
                        <div ng-if="{{$division_id}} > 0">
                            <input type="hidden" value="{{$division_id}}" name="division_id" ng-model="editBranchWiseIGN.division_id" id="edit_division_id">
                        </div>
                        <!--/Branch -->
                        
                        <!--Vendor-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_id">vendor<em class="asteriskRed">*</em></label>							
                            <select class="form-control"
                                    name="vendor_id"                                
                                    ng-model="editBranchWiseIGN.vendor_id.selectedOption"
                                    id="edit_vendor_id"
                                    ng-required="true"
                                    ng-options="vendors.vendor_name for vendors in vendorDataList track by vendors.vendor_id"
                                    @if($division_id > 0)ng-init="funGetDivisionWiseVendors({{$division_id}})"@endif>
                                <option value="">Select Vendor</option>
                            </select>
                            <span ng-messages="erpEditBranchWiseIGNForm.vendor_id.$error" ng-if="erpEditBranchWiseIGNForm.vendor_id.$dirty  || erpEditBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Vendor is required</span>
                            </span>
                        </div>
                        <!--/Vendor-->
                        
                        <!-- Vendor Bill No-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_bill_no">Vendor Bill No.<em class="asteriskRed">*</em></label>
                            <input readonly type="text"
                                   id="edit_vendor_bill_no"
                                   ng-model="editBranchWiseIGN.vendor_bill_no"                                       
                                   name="vendor_bill_no"
                                   class="form-control bgWhite"
                                   placeholder="Vendor Bill No.">
                            <span ng-messages="erpEditBranchWiseIGNForm.vendor_bill_no.$error" ng-if="erpEditBranchWiseIGNForm.vendor_bill_no.$dirty || erpEditBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Vendor Bill No. is required</span>
                            </span>
                        </div>
                        <!-- /Vendor Bill No-->
                        
                        <!-- Vendor Bill Date-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_bill_date">Vendor Bill Date<em class="asteriskRed">*</em></label>		
                            <div class="input-group date" data-provide="datepicker">
                                <input readonly type="text"
                                       id="edit_vendor_bill_date"
                                       ng-model="editBranchWiseIGN.vendor_bill_date"                                       
                                       name="vendor_bill_date"
                                       ng-required="true"
                                       class="form-control bgWhite"
                                       placeholder="Vendor Bill Date">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                            </div>
                            <span ng-messages="erpEditBranchWiseIGNForm.vendor_bill_date.$error" ng-if="erpEditBranchWiseIGNForm.vendor_bill_date.$dirty || erpEditBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Vendor Bill Date is required</span>
                            </span>
                        </div>
                        <!-- /Vendor Bill Date-->
                        
                        <!-- gate Pass No-->
                        <div class="col-xs-4 form-group">
                            <label for="gate_pass_no">Gate Pass No.<em class="asteriskRed">*</em></label>
                            <input readonly type="text"
                                   id="edit_gate_pass_no"
                                   ng-model="editBranchWiseIGN.gate_pass_no"                                       
                                   name="gate_pass_no"
                                   class="form-control bgWhite"
                                   placeholder="Gate Pass No.">
                            <span ng-messages="erpEditBranchWiseIGNForm.gate_pass_no.$error" ng-if="erpEditBranchWiseIGNForm.gate_pass_no.$dirty || erpEditBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Gate Pass No. is required</span>
                            </span>
                        </div>
                        <!-- /Vendor Bill No-->
                        
                        <!--Employee-->
                        <div class="col-xs-4 form-group">
                            <label for="employee_id">Employee<em class="asteriskRed">*</em></label>							
                            <select class="form-control"
                                    name="employee_id"                                
                                    ng-model="editBranchWiseIGN.employee_id.selectedOption"
                                    id="edit_employee_id"
                                    ng-required="true"
                                    ng-options="employees.name for employees in executiveList track by employees.id">
                                <option value="">Select Employee</option>
                            </select>
                            <span ng-messages="erpEditBranchWiseIGNForm.employee_id.$error" ng-if="erpEditBranchWiseIGNForm.employee_id.$dirty  || erpEditBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Employee is required</span>
                            </span>
                        </div>
                        <!--/Employee-->
                        
                        <!--Employee Detail -->
                        <div class="col-xs-4 form-group">
                            <label for="employee_detail">Employee Detail<em class="asteriskRed">*</em></label>
                            <textarea rows="1"
                                class="form-control" 
                                ng-model="editBranchWiseIGN.employee_detail" 
                                name="employee_detail" 
                                id="edit_employee_detail"
                                ng-required='true'
                                placeholder="Employee Detail" /></textarea>
                            <span ng-messages="erpEditBranchWiseIGNForm.employee_detail.$error" ng-if="erpEditBranchWiseIGNForm.employee_detail.$dirty  || erpEditBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Employee Detail is required</span>
                            </span>
                        </div>
                        <!--/Employee Detail -->                               
                        
                    </div>
                    <!--/IGN Header Detail-->
                    
                    <!--IGN Item Detail-->
                    <div class="col-xs-12 form-group view-record maxWidth" id="no-more-tables">
                        <table class="col-sm-12 table-striped table-condensed cf tableThBg tableWidth">
                            
                            <thead>
                                <tr>
                                    <th><label>Item<em class="asteriskRed">*</em></label></th>			
                                    <th><label>PO No.</label></th>				
                                    <th><label>Expiry Date<em class="asteriskRed">*</em></label></th>	
                                    <th><label>Bill Qty<em class="asteriskRed">*</em></label></th>
                                    <th><label>Received Qty<em class="asteriskRed">*</em></label></th>
                                    <th><label>OK Qty<em class="asteriskRed">*</em></label></th>
                                    <th><label>Bill Rate<em class="asteriskRed">*</em></label></th>
                                    <th><label>Pass Rate<em class="asteriskRed">*</em></label></th>
                                    <th><label>Landing Cost(Rs.)<em class="asteriskRed">*</em></label></th>
                                    <th>
                                        <label><a title="Add New Row" id="#editNewPORow" href="javascript:;" ng-click="editIGNRow()"><i class="font15 mL5 glyphicon glyphicon-plus"></i></a></label>
                                    </th>
                                </tr>
                            </thead>
                                
                            <!--IGN Item Detail-->
                            <tbody>
                                @include('inventory.ign.edit_ign_inputs')
                            </tbody>
                            <!--/IGN Item Detail-->
                                                     
                            <!--IGN Tax Detail-->                            
                            <tfoot>
                                <tr class="view-record">
                                    <td colspan="3">&nbsp;</td>
                                    <td><label class="right-nav">Total Bill Amt<em class="asteriskRed">*</em>:</label></td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text"
                                                name="total_bill_amount" 
                                                id="edit_total_bill_amount"
                                                ng-model="editBranchWiseIGN.total_bill_amount"
                                                ng-required="true"
                                                class="width115 form-control" 
                                                placeholder="Total Bill Amt" />	
                                        </div>
                                    </td>
                                    <td><label class="right-nav">Total Pass Amt<em class="asteriskRed">*</em>:</label></td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text"
                                                name="total_pass_amount" 
                                                id="edit_total_pass_amount"
                                                ng-model="editBranchWiseIGN.total_pass_amount"
                                                ng-required="true"
                                                class="width115 form-control" 
                                                placeholder="Total Pass Amt" />	
                                        </div>
                                    </td>
                                    <td><label class="right-nav">Total Landing Amt<em class="asteriskRed">*</em>:</label></td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <input type="text"
                                                name="total_landing_amount" 
                                                id="edit_total_landing_amount"
                                                ng-model="editBranchWiseIGN.total_landing_amount"
                                                ng-required="true"
                                                class="width115 form-control" 
                                                placeholder="Total Landing Amt" />	
                                        </div>
                                    </td>
                                </tr>
                                <tr class="view-record">
                                    <td colspan="3">&nbsp;</td>
                                    <td><label class="right-nav">Service Tax<em class="asteriskRed">*</em>:</label></td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text"
                                                name="total_sales_tax_amount"
                                                id="edit_total_sales_tax_amount"
                                                ng-model="editBranchWiseIGN.total_sales_tax_amount"
                                                ng-required="true"
                                                class="width115 form-control" 
                                                placeholder="Service Tax" />	
                                        </div>
                                    </td>
                                    <td><label class="right-nav">VAT<em class="asteriskRed">*</em>:</label></td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text"
                                                name="total_vat_amount" 
                                                id="edit_total_vat_amount"
                                                ng-model="editBranchWiseIGN.total_vat_amount"
                                                ng-required="true"
                                                class="width115 form-control" 
                                                placeholder="VAT Amt" />	
                                        </div>
                                    </td>
                                    <td><label class="right-nav">Excise Duty<em class="asteriskRed">*</em>:</label></td>
                                    <td colspan="2">
                                        <div class="form-group">
                                            <input type="text"
                                                name="total_excise_duty_amount" 
                                                id="edit_total_excise_duty_amount"
                                                ng-model="editBranchWiseIGN.total_excise_duty_amount"
                                                ng-required="true"
                                                class="width115 form-control" 
                                                placeholder="Excise Duty" />	
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
                        <label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" id="edit_ign_hdr_id" ng-value="editBranchWiseIGN.ign_hdr_id" ng-model="editBranchWiseIGN.ign_hdr_id" name="ign_hdr_id">
                        <button type="submit" ng-disabled="erpEditBranchWiseIGNForm.$invalid" class="btn btn-primary" ng-click="funUpdateBranchWiseIGNDetail(divisionID,editBranchWiseIGN.ign_hdr_id)">Update IGN</button>
                        <button ng-click="funViewIGNDetail(editBranchWiseIGN.ign_hdr_id)" class="btn btn-default">Cancel</button>
                    </div>
                    <!--Save Button-->
                        
                </form>
            </div>
        </div>
    </div>
</div>