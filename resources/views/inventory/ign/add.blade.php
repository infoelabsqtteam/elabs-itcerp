<div class="row" ng-hide="isVisibleAddIGNDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Add New IGN</strong></span>
                </div>            
                <div class="navbar-form navbar-right" role="new" style="margin-top: 2px;">
                    <button ng-click="navigateItemPage(2);" class="btn btn-primary">Back</button>
                </div>
            </div>
            <div class="row">
                <form name="erpAddBranchWiseIGNForm" id="erpAddBranchWiseIGNForm" method="POST" novalidate>
                    <!--IGN Header Detail-->
                    <div class="col-xs-12 form-group view-record">
                    
                        <!-- IGN No-->
                        <div class="col-xs-4 form-group">
                            <label for="ign_no">IGN No.</label>
                            <input readonly
                                   ng-init="funGenerateIGNNumber()"
                                   type="text"
                                   id="ign_no"
                                   ng-value="defaultIGNNo"
                                   ng-model="addBranchWiseIGN.ign_no"                                       
                                   name="ign_no"
                                   class="form-control bgWhite"
                                   placeholder="IGN No">
                            <span ng-messages="erpAddBranchWiseIGNForm.ign_no.$error" ng-if="erpAddBranchWiseIGNForm.ign_no.$dirty || erpAddBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">IGN No. is required</span>
                            </span>
                        </div>
                        <!-- /IGN No-->
                                                
                        <!-- IGN Date-->
                        <div class="col-xs-4 form-group">
                            <label for="ign_date">IGN Date<em class="asteriskRed">*</em></label>		
                            <div class="input-group date" data-provide="datepicker">
                                <input readonly type="text"
                                       id="ign_date"
                                       ng-model="addBranchWiseIGN.ign_date"                                       
                                       name="ign_date"
                                       ng-required="true"
                                       class="form-control bgWhite"
                                       placeholder="IGN Date">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                            </div>
                            <span ng-messages="erpAddBranchWiseIGNForm.ign_date.$error" ng-if="erpAddBranchWiseIGNForm.ign_date.$dirty || erpAddBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">IGN Date is required</span>
                            </span>
                        </div>
                        <!-- /IGN Date-->
                        
                        <!--Branch -->
                        <div ng-if="{{$division_id}} == 0" class="col-xs-4 form-group">
                            <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                            <select class="form-control"
                                name="division_id"
                                id="division_id"
                                ng-required="true"
                                ng-model="addBranchWiseIGN.division_id"
                                ng-change="funGetDivisionWiseVendors(addBranchWiseIGN.division_id.id)"
                                ng-options="addDivision.name for addDivision in divisionsCodeList track by addDivision.division_id">
                                <option value="">Select Branch</option>
                            </select>
                            <span ng-messages="erpAddBranchWiseIGNForm.division_id.$error" ng-if="erpAddBranchWiseIGNForm.division_id.$dirty  || erpAddBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Branch is required</span>
                            </span>
                        </div>
                        <div ng-if="{{$division_id}} > 0">
                            <input type="hidden" value="{{$division_id}}" name="division_id" ng-model="addBranchWiseIGN.division_id" id="division_id">
                        </div>
                        <!--/Branch -->
                        
                        <!--Vendor-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_id">vendor<em class="asteriskRed">*</em></label>							
                            <select class="form-control"
                                    name="vendor_id"                                
                                    ng-model="erpAddBranchWiseIGNForm.vendor_id"
                                    id="vendor_id"
                                    ng-required="true"
                                    ng-options="vendors.vendor_name for vendors in vendorDataList track by vendors.vendor_id"
                                    @if($division_id > 0)ng-init="funGetDivisionWiseVendors({{$division_id}})"@endif>
                                <option value="">Select Vendor</option>
                            </select>
                            <span ng-messages="erpAddBranchWiseIGNForm.vendor_id.$error" ng-if="erpAddBranchWiseIGNForm.vendor_id.$dirty  || erpAddBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Vendor is required</span>
                            </span>
                        </div>
                        <!--/Vendor-->
                        
                        <!-- Vendor Bill No-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_bill_no">Vendor Bill No.<em class="asteriskRed">*</em></label>
                            <input type="text"
                                   id="vendor_bill_no"
                                   ng-model="addBranchWiseIGN.vendor_bill_no"                                       
                                   name="vendor_bill_no"
                                   class="form-control bgWhite"
                                   placeholder="Vendor Bill No.">
                            <span ng-messages="erpAddBranchWiseIGNForm.vendor_bill_no.$error" ng-if="erpAddBranchWiseIGNForm.vendor_bill_no.$dirty || erpAddBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Vendor Bill No. is required</span>
                            </span>
                        </div>
                        <!-- /Vendor Bill No-->
                        
                        <!-- Vendor Bill Date-->
                        <div class="col-xs-4 form-group">
                            <label for="vendor_bill_date">Vendor Bill Date<em class="asteriskRed">*</em></label>		
                            <div class="input-group date" data-provide="datepicker">
                                <input readonly type="text"
                                       id="vendor_bill_date"
                                       ng-model="addBranchWiseIGN.vendor_bill_date"                                       
                                       name="vendor_bill_date"
                                       ng-required="true"
                                       class="form-control bgWhite"
                                       placeholder="Vendor Bill Date">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                            </div>
                            <span ng-messages="erpAddBranchWiseIGNForm.vendor_bill_date.$error" ng-if="erpAddBranchWiseIGNForm.vendor_bill_date.$dirty || erpAddBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Vendor Bill Date is required</span>
                            </span>
                        </div>
                        <!-- /Vendor Bill Date-->
                        
                        <!-- gate Pass No-->
                        <div class="col-xs-4 form-group">
                            <label for="gate_pass_no">Gate Pass No.<em class="asteriskRed">*</em></label>
                            <input type="text"
                                   id="gate_pass_no"
                                   ng-model="addBranchWiseIGN.gate_pass_no"                                       
                                   name="gate_pass_no"
                                   class="form-control bgWhite"
                                   placeholder="Gate Pass No.">
                            <span ng-messages="erpAddBranchWiseIGNForm.gate_pass_no.$error" ng-if="erpAddBranchWiseIGNForm.gate_pass_no.$dirty || erpAddBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Gate Pass No. is required</span>
                            </span>
                        </div>
                        <!-- /Vendor Bill No-->
                        
                        <!--Employee-->
                        <div class="col-xs-4 form-group">
                            <label for="employee_id">Employee<em class="asteriskRed">*</em></label>							
                            <select class="form-control"
                                    name="employee_id"                                
                                    ng-model="addBranchWiseIGN.employee_id"
                                    id="employee_id"
                                    ng-required="true"
                                    ng-options="employees.name for employees in executiveList track by employees.id">
                                <option value="">Select Employee</option>
                            </select>
                            <span ng-messages="erpAddBranchWiseIGNForm.employee_id.$error" ng-if="erpAddBranchWiseIGNForm.employee_id.$dirty  || erpAddBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Employee is required</span>
                            </span>
                        </div>
                        <!--/Employee-->
                        
                        <!--Employee Detail -->
                        <div class="col-xs-4 form-group">
                            <label for="employee_detail">Employee Detail<em class="asteriskRed">*</em></label>
                            <textarea rows="1"
                                class="form-control" 
                                ng-model="addBranchWiseIGN.employee_detail" 
                                name="employee_detail" 
                                id="employee_detail"
                                ng-required='true'
                                placeholder="Employee Detail" /></textarea>
                            <span ng-messages="erpAddBranchWiseIGNForm.employee_detail.$error" ng-if="erpAddBranchWiseIGNForm.employee_detail.$dirty  || erpAddBranchWiseIGNForm.$submitted" role="alert">
                                <span ng-message="required" class="error">Employee Detail is required</span>
                            </span>
                        </div>
                        <!--/Employee Detail -->                               
                        
                    </div>
                    <!--/IGN Header Detail-->
                    
                    <!--IGN Item Detail-->
                    <div class="col-xs-12 form-group view-record" id="no-more-tables">
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
                                        <label><a title="Add New Row" id="#addNewPORow" href="javascript:;" ng-click="addIGNRow()"><i class="font15 mL5 glyphicon glyphicon-plus"></i></a></label>
                                    </th>
                                </tr>
                            </thead>
                                
                            <!--IGN Item Detail-->
                            <tbody select-item-fields-add ng-repeat="ign_input in ign_inputs"></tbody>
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
                                                id="total_bill_amount"
                                                ng-model="addBranchWiseIGN.total_bill_amount"
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
                                                id="total_pass_amount"
                                                ng-model="addBranchWiseIGN.total_pass_amount"
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
                                                id="total_landing_amount"
                                                ng-model="addBranchWiseIGN.total_landing_amount"
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
                                                id="total_sales_tax_amount"
                                                ng-model="addBranchWiseIGN.total_sales_tax_amount"
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
                                                id="total_vat_amount"
                                                ng-model="addBranchWiseIGN.total_vat_amount"
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
                                                id="total_excise_duty_amount"
                                                ng-model="addBranchWiseIGN.total_excise_duty_amount"
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
                        <button type="submit" ng-disabled="erpAddBranchWiseIGNForm.$invalid" class="btn btn-primary" ng-click="funAddBranchWiseIGNDetail(divisionID)">Save</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                    <!--Save Button-->
                        
                </form>
            </div>
        </div>
    </div>
</div>