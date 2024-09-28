<div class="row" ng-hide="isSchedulingViewList">

    <!--header-->
    <div class="row header">
        <div class="navbar-form navbar-left">
            <span class="pull-left"><strong id="form_title" title="Refresh" ng-click="funGetDivisionWisePendingJobs(divisionID,GlobalFilterStatus)">Job Listing<span ng-if="pendingJobData.length">([[pendingJobData.length]])</span></strong></span>
        </div>
        <div class="navbar-form navbar-right">
            <div class="searchbox">
                <input type="text" placeholder="Search" ng-model="searchSchedulingJob" class="form-control ng-pristine ng-untouched ng-valid">
            </div>
        </div>
    </div>
    <!--header-->

    <!--Scheduling Filter Form-->
    <div class="row" id="no-more-tables">
        <div class="panel panel-default" style="margin-top: 0px;" ng-init="funGetEmployeeList({{ $division_id }})">
            <div class="panel-body">
                <div class="row">
                    <form class="form-inline col-xs-12" method="POST" role="form" name="erpSchedulingFilterForm" action="{{ url('scheduling/generate-analyst-sheet-documents') }}">

                        <div class="row">

                            <!--branch-->
                            <div ng-if="{{ $division_id }} == 0" class="col-xs-2 form-group">
                                <label for="division_id">Branch</label>
                                <select class="form-control width200" name="division_id" id="division_id" ng-model="filterSchedulingJob.division_id" ng-options="division.name for division in divisionsCodeList track by division.id">
                                    <option value="">All Branch</option>
                                </select>
                            </div>
                            <div ng-if="{{ $division_id }} > 0">
                                <input type="hidden" id="division_id" name="division_id" value="{{ $division_id }}">
                            </div>
                            <!-- /branch-->

                            <!--Department-->
                            <div class="col-xs-2 form-group">
                                <label for="product_category_id">Department</label>
                                <select class="form-control" name="product_category_id" id="product_category_id" ng-model="filterSchedulingJob.product_category_id" ng-options="item.name for item in parentCategoryList track by item.id">
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            <!--/Department-->

                            <!--Analyst-->
                            <div ng-if="{{ defined('IS_ADMIN') && IS_ADMIN }}" class="col-xs-2 form-group">
                                <label for="from">Analyst</label>
                                <select style="width:180px;" class="form-control" id="employee_id" name="employee_id" ng-model="filterSchedulingJob.employee_id" ng-options="employees.name for employees in employeeDataList track by employees.id">
                                    <option value="">All Analyst</option>
                                </select>
                            </div>
                            <!--/Analyst-->

                            <!--Analyst-->
                            <div ng-if="{{ defined('IS_JOB_SCHEDULER') && IS_JOB_SCHEDULER }}" class="col-xs-2 form-group">
                                <label for="from">Analyst</label>
                                <select style="width:180px;" class="form-control" id="employee_id" name="employee_id" ng-model="filterSchedulingJob.employee_id" ng-options="employees.name for employees in employeeDataList track by employees.id">
                                    <option value="">All Analyst</option>
                                </select>
                            </div>
                            <!--/Analyst-->

                            <!--Equipment Type-->
                            <div class="col-xs-2 form-group">
                                <label for="from">Equipment</label>
                                <select style="width:180px;" class="form-control" id="equipment_type_id" name="equipment_type_id" ng-model="filterSchedulingJob.equipment_type_id" ng-options="item.name for item in equipmentTypesList track by item.id">
                                    <option value="">All Equipment Type</option>
                                </select>
                            </div>
                            <!--/Equipment Type-->

                            <!--Job Status-->
                            <div class="col-xs-2 form-group">
                                <label for="from">Job Status</label>
                                <select style="width:180px;" class="form-control" id="status" name="status" ng-model="filterSchedulingJob.status">
                                    <option value="">Select Status</option>
                                    <option value="0">Unassigned Job</option>
                                    <option value="1">Assigned Job</option>
                                </select>
                            </div>
                            <!--/Job Status-->

                            <!--From-->
                            <div class="col-xs-2 form-group">
                                <label for="from">Booking Date From</label>
                                <div class="input-group date" data-provide="datepicker">
                                    <input type="text" style="width:150px;" class="bgwhite form-control" ng-model="filterSchedulingJob.order_date_from" name="order_date_from" id="order_date_from" placeholder="Date From" />
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                </div>
                            </div>
                            <!--/From-->

                            <!--To-->
                            <div class="col-xs-2 form-group mT20">
                                <label for="to">Booking Date To</label>
                                <div class="input-group date" data-provide="datepicker">
                                    <input type="text" style="width:150px;" class="bgwhite form-control" ng-model="filterSchedulingJob.order_date_to" name="order_date_to" id="order_date_to" placeholder="Date To" />
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                </div>
                            </div>
                            <!--/To-->
                      
                            <div class="col-xs-4 form-group mT30">
                                <label for="submit">{{ csrf_field() }}</label>
                                <button type="button" title="Filter" ng-disabled="erpSchedulingFilterForm.$invalid" class="btn btn-primary btn-sm" ng-click="funFilterSchedulingJobs()">Go</button>
                                <button type="button" ng-disabled="!pendingJobData.length" class="dropdown btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown"><i aria-hidden="true" class="fa fa-print font16"></i></button>
                                <div class="dropdown-menu">
                                    <input type="submit" formtarget="_blank" name="generate_analyst_sheet_documents" value="Excel" class="dropdown-item">
                                    <input type="submit" formtarget="_blank" name="generate_analyst_sheet_documents" value="PDF" class="dropdown-item">
                                </div>
                                <button type="button" title="Reset" class="btn btn-default" ng-click="resetButton()"><i aria-hidden="true" class="fa fa-refresh font16"></i></button>
                            </div>

                        </div>
                        <!--Action Button-->

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/Scheduling Filter Form-->

    <!--display pending record-->
    <div class="row custom-pt-scroll" id="no-more-tables">
        <form class="form-inline col-xs-12" method="POST" role="form" name="erpSchedulingJobForm" novalidate>
            <table class="col-sm-12 table-striped table-condensed cf">
                <thead class="cf">
                    <tr>
                        <th style="width: 9%;">
                            <label ng-click="sortBy('order_date')" class="sortlabel">Booking Date </label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_date'" class="sortorder reverse"></span>
                        </th>
                        <th>
                            <label class="sortlabel" ng-click="sortBy('sample_description')">Sample Name</label>
                            <span class="sortorder" ng-show="predicate === 'sample_description'" ng-class="{reverse:reverse}"></span>
                        </th>
                        <th style="width: 9%;">
                            <label ng-click="sortBy('order_no')" class="sortlabel">Report Code</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_no'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th style="padding-left: 20px;">
                            <label ng-click="sortBy('test_parameter_name')" class="sortlabel">Parameter Name</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'test_parameter_name'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('equipment_name')" class="sortlabel">Equipment Name</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'equipment_name'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('equipment_capacity')" class="sortlabel">Equipment Capacity</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'equipment_capacity'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('equipment_pendency')" class="sortlabel">Equipment Pendency</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'equipment_pendency'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('method_name')" class="sortlabel">Method Name</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'method_name'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('expected_due_date')" class="sortlabel">Expected Due Date</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'expected_due_date'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('dept_due_date')" class="sortlabel">Department Due Date</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'dept_due_date'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('scheduled_at')" class="sortlabel">Scheduled Date</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'scheduled_at'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('completed_at')" class="sortlabel">Completed Date</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'completed_at'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('sample_priority_name')" class="sortlabel">Sample Priority</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_priority_name'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('tentative_date')" class="sortlabel">Tentative Date</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'tentative_date'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th>
                            <label ng-click="sortBy('tentative_time')" class="sortlabel">Tentative Time</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'tentative_time'" class="sortorder reverse ng-hide"></span>
                        </th>
                        <th colsapn="2" style="width: 177px;">
                            <label ng-click="sortBy('employee_name')" class="sortlabel">Analyst Name</label>
                            <span ng-class="{reverse:reverse}" ng-show="predicate === 'employee_name'" class="sortorder reverse ng-hide"></span>
                            <span ng-if="selectedJobArr.length" style="float: right;"><button type="submit" ng-click="funUpdateSchedulingJobs(divisionID);" class="btn btn-primary btn-sm">Update</button></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="pendingJobObj in pendingJobData | filter:searchSchedulingJob | itemsPerPage: {{ defined('PERPAGE') ? PERPAGE + 50 : 50 }} | orderBy:predicate:reverse">
                        <td data-title="Booking Date" data="[[pendingJobObj.scheduling_id]]" ng-bind="pendingJobObj.order_date" class="ng-binding"></td>
                        <td data-title="Sample Name" ng-bind="pendingJobObj.sample_description" class="ng-binding"></td>
                        <td data-title="Report Code" ng-bind="pendingJobObj.order_no" class="ng-binding"></td>
                        <td data-title="Parameter Name" ng-bind-html="pendingJobObj.test_parameter_name" class="ng-binding"></td>
                        <td data-title="Equipment Name" ng-bind="pendingJobObj.equipment_name" class="ng-binding"></td>
                        <td data-title="Equipment Capacity" ng-bind="pendingJobObj.equipment_capacity" class="ng-binding"></td>
                        <td data-title="Equipment Pendency" ng-bind="pendingJobObj.equipment_pendency" class="ng-binding"></td>
                        <td data-title="Method Name" ng-bind="pendingJobObj.method_name" class="ng-binding"></td>
                        <td data-title="Expected Due Date" ng-bind="pendingJobObj.expected_due_date" class="ng-binding"></td>
                        <td data-title="Department Due Date" ng-bind="pendingJobObj.dept_due_date" class="ng-binding"></td>
                        <td data-title="Scheduled Date" ng-bind="pendingJobObj.scheduled_at" class="ng-binding"></td>
                        <td data-title="Completed Date" ng-bind="pendingJobObj.completed_at" class="ng-binding"></td>
                        <td data-title="Sample Priority" ng-bind="pendingJobObj.sample_priority" class="ng-binding"></td>
                        <td data-title="Tentative Date" class="ng-binding text-left">
                            <input type="hidden" name="scheduling_id[]" ng-value="pendingJobObj.scheduling_id" ng-model="scheduling.scheduling_id">
                            <input type="hidden" name="order_id[]" ng-value="pendingJobObj.order_id" ng-model="scheduling.order_id">
                            <input type="hidden" name="equipment_type_id[]" ng-model="pendingJobObj.equipment_type_id" ng-value="pendingJobObj.equipment_type_id">
                            <span title="Tentative Date" id="spanTentativeDate[[pendingJobObj.scheduling_id]]" class="defaultSpanDiv spanTextContentClass[[pendingJobObj.equipment_type_id]]" ng-click="funShowHideDiv(pendingJobObj.scheduling_id,pendingJobObj.employee_id,pendingJobObj.equipment_type_id)">[[pendingJobObj.tentative_date ? pendingJobObj.tentative_date : '+']]</span>
                            <span id="spanTentativeDateContentId[[pendingJobObj.scheduling_id]]" class="inputDiv[[pendingJobObj.scheduling_id]] spanInputDiv spanInputContentClass[[pendingJobObj.equipment_type_id]]">
                                <div class="input-group date" data-provide="datepicker">
                                    <input data-provide="datepicker" type="text" class="form-control tentative-date tentative_date_time_analyst_[[pendingJobObj.order_id]]" id="tentative_date_[[pendingJobObj.scheduling_id]]" name="tentative_date[]" ng-value="pendingJobObj.tentative_date" ng-model="scheduling.tentative_date" placeholder="Tentative Date">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                </div>
                            </span>
                        </td>
                        <td data-title="Tentative Time" class="ng-binding text-left">
                            <span title="Tentative Time" id="spanTentativeTime[[pendingJobObj.scheduling_id]]" class="defaultSpanDiv spanTextContentClass[[pendingJobObj.equipment_type_id]]" ng-click="funShowHideDiv(pendingJobObj.scheduling_id,pendingJobObj.employee_id,pendingJobObj.equipment_type_id)">[[pendingJobObj.tentative_time
                                ? pendingJobObj.tentative_time : '+']]</span>
                            <span id="spanTentativeTimeContentId[[pendingJobObj.scheduling_id]]" class="inputDiv[[pendingJobObj.scheduling_id]] spanInputDiv spanInputContentClass[[pendingJobObj.equipment_type_id]]">
                                <div class="input-group bootstrap-timepicker">
                                    <input type="text" class="form-control tentative-time tentative_date_time_analyst_[[pendingJobObj.order_id]]" id="tentative_time_[[pendingJobObj.scheduling_id]]" name="tentative_time[]" ng-value="pendingJobObj.tentative_time" ng-model="scheduling.tentative_time" placeholder="Tentative Time">
                                    <script type="text/javascript">
                                        $(function() {
                                            $('.tentative-time').timepicker();
                                        });

                                    </script>
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-time"></span></div>
                                </div>
                            </span>
                        </td>
                        <td colspan="2" data-title="Employee Name" class="ng-binding text-left">
                            <span id="spanEmployeeId[[pendingJobObj.scheduling_id]]" class="defaultSpanDiv spanTextContentClass[[pendingJobObj.equipment_type_id]]" ng-click="funShowHideDiv(pendingJobObj.scheduling_id,pendingJobObj.employee_id,pendingJobObj.equipment_type_id)">[[pendingJobObj.employee_name ? pendingJobObj.employee_name : '+']]</span>
                            <span id="spanEmployeeContentId[[pendingJobObj.scheduling_id]]" class="inputDiv[[pendingJobObj.scheduling_id]] spanInputDiv spanInputContentClass[[pendingJobObj.equipment_type_id]]">
                                <select style="margin-top: 15px;" class="form-control analyst tentative_date_time_analyst_[[pendingJobObj.order_id]]" id="employee_id_[[pendingJobObj.scheduling_id]]" name="employee_id[]" ng-model="scheduling.employee_id" ng-options="employees.name for employees in pendingJobObj.analystOption track by employees.id">
                                    <option value="">Select Analyst</option>
                                </select>
                                <input type="checkbox" title="Common Equipment Selection" ng-if="pendingJobObj.equipment_type_id" class="checkUncheckAllClass[[pendingJobObj.scheduling_id]]" id="checkUncheckAllid[[pendingJobObj.scheduling_id]]" ng-model="scheduling.save_all" ng-value="pendingJobObj.scheduling_id" ng-click="funCheckUncheckPendingJobAll(pendingJobObj.scheduling_id,pendingJobObj.order_id,pendingJobObj.equipment_type_id)" name="save_all[]">
                                <a href="javascript:;" title="Close" class="generate" ng-click="funCancelAction(pendingJobObj.scheduling_id,pendingJobObj.order_id,pendingJobObj.equipment_type_id)"><span class="fontbd font12" aria-hidden="true">&#x2716;</span></a>
                            </span>
                        </td>
                    </tr>
                    <tr ng-if="!pendingJobData.length">
                        <td colspan="16">No Job found.</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="16">
                            <div class="box-footer clearfix">
                                <dir-pagination-controls></dir-pagination-controls>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
    <!--/display pending record-->
</div>
