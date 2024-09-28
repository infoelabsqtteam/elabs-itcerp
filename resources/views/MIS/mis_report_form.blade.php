<div class="row">
    <form name="erpGenerateMISReportForm" id="erpGenerateMISReportForm" action="{{ url('MIS/generate-mis-report-doc') }}" method="post" novalidate>
        <div class="header-form" style="margin: 0px ! important;">
            <div role="new" class="navbar-form navbar-left">
                <span ng-if="MISReportData.tableBody.length" ng-click="toggleButton();" class="pull-left"><strong id="form_title">Search Criteria Form</strong></span>
                <span ng-if="!MISReportData.tableBody.length" class="pull-left"><strong id="form_title">Search Criteria Form</strong></span>
            </div>
            <div ng-if="MISReportData.tableBody.length" role="new" class="navbar-form navbar-right">
                <span class="pull-right" style="margin: -5px 2px;">
                    <button type="button" class="btn btn-primary" ng-click="closeButton();" title="Back">Back</button>
                    <button type="submit" formtarget="_blank" class="btn btn-primary">Export as Excel</button>
                </span>
            </div>
        </div>
        <div class="panel panel-default" id="misGenerateReportFormDiv">
            <div class="panel-body" style="margin-bottom: -30px;">
                <div class="row">

                    <!--Report Types-->
                    <div class="col-xs-2 form-group">
                        <label for="mis_report_name">Select Report Type<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="mis_report_name" id="mis_report_name" ng-required='true' ng-model="generateMISReport.mis_report_name" ng-options="MISReportTypes.name for MISReportTypes in MISReportTypesList track by MISReportTypes.id" ng-change="funShowReportDataAreaDiv(generateMISReport.mis_report_name.id)">
                            <option value="">Select Report Type</option>
                        </select>
                    </div>
                    <!--/Report Types-->

                    <!--Branch-->
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv != 'ESTR19' && MISReportResultDiv != 'ESTR20'">
                        <label for="division_id">Branch</label>
                        <select class="form-control" ng-model="generateMISReport.division_id" id="division_id" name="division_id" ng-options="item.name for item in divisionsCodeList track by item.id " ng-change="funGetSalesExecutives(generateMISReport.division_id.id)">
                            <option value="">All Branch</option>
                        </select>
                    </div>
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv == 'ESTR19' || MISReportResultDiv == 'ESTR20'">
                        <label for="division_id">Branch</label>
                        <select class="form-control" ng-model="generateMISReport.division_id" id="division_id" name="division_id" ng-options="item.name for item in divisionsCodeList track by item.id " ng-change="funGetEmployeeSalesTargetList(generateMISReport.division_id.id)">
                            <option value="">All Branch</option>
                        </select>
                    </div>
                    <!--/Branch-->

                    <!--Department / Parent Product Category-->
                    <div class="col-xs-2 form-group">
                        <label for="product_category_id">Department</label>
                        <select class="form-control" name="product_category_id" id="product_category_id" ng-model="generateMISReport.product_category_id" ng-options="item.name for item in parentCategoryList track by item.id">
                            <option value="">All Department</option>
                        </select>
                    </div>
                    <!--/Department / Parent Product Category-->

                    <!--From Date-->
                    <div class="col-xs-2 form-group">
                        <label for="date_from">Date From<em class="asteriskRed">*</em></label>
                        <div class="input-group" ng-init="funGetFromToDateInit();">
                            <input type="text" class="bgwhite form-control" ng-model="generateMISReport.date_from" name="date_from" id="date_from" ng-required='true' placeholder="Date From" />
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                        </div>
                    </div>
                    <!--/From Date-->

                    <!--To Date-->
                    <div class="col-xs-2 form-group">
                        <label for="date_to">Date To<em class="asteriskRed">*</em></label>
                        <div class="input-group" ng-init="funGetFromToDateInit();">
                            <input type="text" class="bgwhite form-control" ng-model="generateMISReport.date_to" name="date_to" id="date_to" ng-required='true' placeholder="Date To" />
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                        </div>
                    </div>
                    <!--/To Date-->

                    <!--Expected Due Date From-->
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv == 'TAT006'">
                        <label for="date_to">Expected Due Date From</label>
                        <div class="input-group" ng-init="funGetFromToDateInit();">
                            <input type="text" class="bgwhite form-control" ng-model="generateMISReport.expected_due_date_from" name="expected_due_date_from" id="expected_due_date_from" placeholder="Expected Due Date From" />
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                        </div>
                    </div>
                    <!--/Expected Due Date From-->

                    <!--Expected Due Date To-->
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv == 'TAT006'">
                        <label for="date_to">Expected Due Date To</label>
                        <div class="input-group" ng-init="funGetFromToDateInit();">
                            <input type="text" class="bgwhite form-control" ng-model="generateMISReport.expected_due_date_to" name="expected_due_date_to" id="expected_due_date_to" placeholder="Expected Due Date To" />
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                        </div>
                    </div>
                    <!--/Expected Due Date To-->

                    <!--Expected Due Date To-->
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv == 'TAT006'">
                        <label for="sale_executive_id">Order Process Stage</label>
                        <select class="form-control" name="order_status_id" ng-model="generateMISReport.order_status_id" id="order_status_id" ng-options="defaultOrderStagePhase.name for defaultOrderStagePhase in defaultOrderStagePhaseList track by defaultOrderStagePhase.id">
                            <option value="">All Order Stage</option>
                        </select>
                    </div>
                    <!--/Expected Due Date To-->

                    <!--Employee Sale Target-->
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv == 'ESTR19' || MISReportResultDiv == 'ESTR20'">
                        <label for="sale_executive_id">Sales Executive</label>
                        <select class="form-control" name="sale_executive_id" ng-model="generateMISReport.sale_executive_id" ng-change="funGetCustomerList(generateMISReport.sale_executive_id.id)" id="sale_executive_id" ng-options="employeeSalesTarget.name for employeeSalesTarget in employeeSalesTargetList track by employeeSalesTarget.id">
                            <option value="">All Sales Executive</option>
                        </select>
                    </div>
                    <!--/Employee Sale Target-->

                    <!--Sale Executive-->
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv == 'MEWBEN004' || MISReportResultDiv == 'MEWBPWSC005' || MISReportResultDiv == 'SRD010' || MISReportResultDiv == 'ITCVSSM21'">
                        <label for="sale_executive_id">Sales Executive</label>
                        <select ng-if="MISReportResultDiv != 'ITCVSSM21'" class="form-control" name="sale_executive_id" ng-model="generateMISReport.sale_executive_id" id="sale_executive_id" ng-options="salesExecutives.name for salesExecutives in salesExecutiveList track by salesExecutives.id">
                            <option value="">All Sales Executive</option>
                        </select>
                        <select ng-if="MISReportResultDiv == 'ITCVSSM21'" class="form-control" name="sale_executive_id" ng-change="funGetCustomerList(generateMISReport.sale_executive_id.id)" ng-model="generateMISReport.sale_executive_id" id="sale_executive_id" ng-options="salesExecutives.name for salesExecutives in salesExecutiveList track by salesExecutives.id">
                            <option value="">All Sales Executive</option>
                        </select>
                    </div>
                    <!--/Sale Executive-->

                    <!--User Roles-->
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv == 'UWPD007' || MISReportResultDiv == 'AWPS013'" ng-init="funUserDefaultRoleList()">
                        <label for="order_status_id">Select User Role<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="order_status_id" ng-model="generateMISReport.order_status_id" id="order_status_id" ng-required="true" ng-change="funGetUserNameByRoles(generateMISReport.order_status_id.id,generateMISReport.division_id.id,generateMISReport.product_category_id.id)" ng-options="userDefaultStatus.name for userDefaultStatus in userDefaultStatusList track by userDefaultStatus.id">
                            <option value="">Select User Role</option>
                        </select>
                    </div>
                    <!--User Roles-->

                    <!--Users-->
                    <div class="col-xs-2 form-group" ng-if="(MISReportResultDiv == 'UWPD007' || MISReportResultDiv == 'AWPS013') && userNameByRoleList.length">
                        <label for="user_id">Select User</label>
                        <select class="form-control" name="user_id" ng-model="generateMISReport.user_id" id="user_id" ng-options="userNameByRole.name for userNameByRole in userNameByRoleList track by userNameByRole.id">
                            <option value="">All User</option>
                        </select>
                    </div>
                    <!--Users-->

                    <!--Customer-->
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv == 'SRD010'">
                        <label for="customer_id">Customer</label>
                        <select class="form-control" name="customer_id" id="customer_id" ng-model="generateMISReport.customer_id" ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">All Customer</option>
                        </select>
                    </div>
                    <div class="col-xs-2 form-group" ng-if="MISReportResultDiv == 'ESTR19' || MISReportResultDiv == 'ESTR20' || MISReportResultDiv == 'ITCVSSM21'">
                        <label for="customer_id">Customer</label>
                        <select class="form-control" name="customer_id" id="customer_id" ng-model="generateMISReport.customer_id" ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">All Customer</option>
                        </select>
                    </div>
                    <!--/Customer-->

                    <!--Save Button-->
                    <div class="col-xs-2 form-group text-left">
                        <label for="submit">{{ csrf_field() }}</label>
                        <div class="form-group mT20">
                            <button type="button" ng-disabled="erpGenerateMISReportForm.$invalid" class="btn btn-primary" ng-click="funGenerateMISReport()">Generate</button>
                            <button type='button' id='reset_button' class='btn btn-default' ng-click='resetForm()' title="Reset">Reset</button>
                        </div>
                    </div>
                    <!--Save Button-->
                </div>
            </div>
        </div>
    </form>
</div>

