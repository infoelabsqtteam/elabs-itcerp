<div ng-if="isViewOrdersStatisticSection" class="container-fluid pdng-20 botm-table hideContentOnPdf" id="foorPartC">  
    <div class="col-md-12 col-xs-12 botm-row mrgn_20 dis_flex" style="padding:0">
        <div class="col-md-12 col-xs-12 bord">
            <div class="col-sm-12 col-xs-12 report">
                <span class="font10"><a href="javascript:;" ng-click="funViewOrdersStatistics(viewOrder.order_id)">Refresh </a></span>
                <ol class="progtrckr" data-progtrckr-steps="10">[[orderTrackingObj.track]]                
                    <li ng-if="orderTrackingObj.track && orderTrackingObj.track.opl_current_stage == 0" ng-repeat="orderTrackingObj in orderTrackingList" title="[[orderTrackingObj.track.opl_date]]" class="progtrckr-done">[[orderTrackingObj.order_status_name]]</li>
                    <li ng-if="orderTrackingObj.track && orderTrackingObj.track.opl_current_stage == 1 && orderTrackingObj.order_status_id != 11" ng-repeat="orderTrackingObj in orderTrackingList" title="[[orderTrackingObj.track.opl_date]]" class="progtrckr-current">[[orderTrackingObj.order_status_name]]</li>
                    <li ng-if="!orderTrackingObj.track" ng-repeat="orderTrackingObj in orderTrackingList" title="[[orderTrackingObj.order_status_name]]" class="progtrckr-todo">[[orderTrackingObj.order_status_name]]</li>
                </ol>
            </div>
        </div>
    </div>
    <link href="{!! asset('public/css/track.css') !!}"  rel="stylesheet" type="text/css"/>
</div>