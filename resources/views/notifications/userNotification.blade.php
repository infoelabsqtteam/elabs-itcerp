<div id="notification_container_ul" style="display:none;" ng-controller="dashboardController" ng-init="initializeNotificationScript();initializeUserRoleContent();" class="notification-content">
    <ul>							
        <li id="notification_container">
            <div id="notification_button"></div>
            <div id="notifications">
                <div id="no-more-tables" ng-repeat="(keydata,valueData) in userRoleContentData" ng-if="valueData.tableBody.length">
                    <h3 class="seeAll capitalizeAll" ng-if="valueData.tableBody.length">[[keydata | removeUnderscores]]</h3>
                    <table class="col-sm-12 table-striped table-condensed cf" ng-if="valueData.tableBody.length">
                        <thead class="cf" ng-if="valueData.tableHead.length">
                            <tr>
                                <th ng-repeat="tableHeadName in valueData.tableHead">
                                    <label class="capitalizeAll">[[tableHeadName | removeUnderscores]]</label>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="valueAll in valueData.tableBody">
                                <td ng-repeat="valueObj in valueAll" data-title="[[valueObj]]" class="ng-binding">[[valueObj]]</td>
                            </tr>
                        </tbody>
                    </table>	
                </div>
                <!--<div class="seeAll"><a href="#">View</a></div>-->
            </div>
        </li>
    </ul>
    <!--including angular controller file-->
    <script type="text/javascript" src="{!! asset('public/ang/controller/dashboardController.js') !!}"></script>
</div>