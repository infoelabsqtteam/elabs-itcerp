<div class="row pdng-20 mT10 mB10">
    <div class="pull-right pull-custom"><button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button></div>
    <div class="row pdng-20 mT10">
        <div class="col-sm-12 col-xs-12 flexCenter header-content" ng-bind-html="viewOrderReport.header_content" ng-if="{{defined('IS_ADMIN') && IS_ADMIN ||  defined('IS_FINALIZER') && IS_FINALIZER || defined('IS_APPROVAL') && IS_APPROVAL}}"></div>
        <div class="col-sm-12 col-xs-12 dis_flex pharma-header-text">
            <div class="col-sm-4 col-xs-4"></div>
            <div class="col-sm-4 col-xs-4">
                <h3 class="text-center cntr_txt" ng-if="viewOrderReport.status==6">FINALIZE REPORT - [[viewOrderReport.department_name | capitalizeAll]]</h3>
                <h3 class="text-center cntr_txt" ng-if="viewOrderReport.status==7">APPROVE REPORT - [[viewOrderReport.department_name | capitalizeAll]]</h3>
                <h3 class="text-center cntr_txt" ng-if="viewOrderReport.status==8">APPROVED REPORT - [[viewOrderReport.department_name | capitalizeAll]]</h3>
            </div>
            <div class="col-sm-4 col-xs-4"></div>
        </div>
    </div>
</div>
