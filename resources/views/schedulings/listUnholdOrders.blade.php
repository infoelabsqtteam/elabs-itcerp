<!--display pending record-->
<div class="row mB30" ng-if="pendingUnholdJobData.length">

    <!--header-->
    <div class="row m-0 header">
        <div class="navbar-form navbar-left">
            <span class="pull-left"><strong id="form_title" title="Refresh" ng-click="funGetSchedulingUnholdJobs()">Unhold Job Listing<span ng-if="pendingUnholdJobData.length">([[pendingUnholdJobData.length]])</span></strong></span>
        </div>
        <div class="navbar-form navbar-right">
            <div class="searchbox">
                <input type="text" placeholder="Search" ng-model="searchSchedulingUnholdJob" class="form-control">
            </div>
        </div>
    </div>
    <!--header-->

    <div class="row custom-tb-scroll" id="no-more-tables">
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th>
                        <label ng-click="sortBy('order_no')" class="sortlabel">Order No.</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_no'" class="sortorder reverse"></span>
                    </th>
                    <th>
                        <label ng-click="sortBy('order_date')" class="sortlabel">Booking Date </label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_date'" class="sortorder reverse"></span>
                    </th>                    
                    <th>
                        <label class="sortlabel" ng-click="sortBy('sample_description')">Sample Name</label>
                        <span class="sortorder" ng-show="predicate === 'sample_description'" ng-class="{reverse:reverse}"></span>
                    </th>
                    <th>
                        <label ng-click="sortBy('expected_due_date')" class="sortlabel">Expected Due Date</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'expected_due_date'" class="sortorder reverse ng-hide"></span>
                    </th>
                    <th>
                        <label ng-click="sortBy('order_dept_due_date')" class="sortlabel">Department Due Date</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_dept_due_date'" class="sortorder reverse ng-hide"></span>
                    </th>
                    <th>
                        <label ng-click="sortBy('sample_priority_name')" class="sortlabel">Sample Priority</label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'sample_priority_name'" class="sortorder reverse ng-hide"></span>
                    </th>
                    <th colsapn="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr class="pendingUnholdJob" id="selectedUnholdJob_[[pendingUnholdJobObj.order_id]]" ng-repeat="pendingUnholdJobObj in pendingUnholdJobData | filter : searchSchedulingUnholdJob | orderBy:predicate:reverse">
                    <td data-title="OrderNo" ng-bind="pendingUnholdJobObj.order_no"></td>
                    <td data-title="Booking Date" ng-bind="pendingUnholdJobObj.order_date"></td>
                    <td data-title="Sample Name" ng-bind="pendingUnholdJobObj.sample_description"></td>
                    <td data-title="Expected Due Date" ng-bind="pendingUnholdJobObj.expected_due_date"></td>
                    <td data-title="Department Due Date" ng-bind="pendingUnholdJobObj.order_dept_due_date"></td>
                    <td data-title="Sample Priority" ng-bind="pendingUnholdJobObj.sample_priority_name"></td>
                    <td colspan="2" data-title="Employee Name" class="ng-binding text-left">
                        <button type="button" title="Select To Assign" class="btn btn-primary" ng-click="funFilterSchedulingJobs(pendingUnholdJobObj.order_id)">Select To Assign</button>
                    </td>
                </tr>
                <tr ng-if="!pendingUnholdJobData.length">
                    <td colspan="7">No Unhold Job found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--/display pending record-->
