<div class="row" ng-hide="addMasterFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row header-form">
                <span class="pull-left headerText"><strong>Upload Customer Account Detail</strong></span>
            </div>

            <form method="POST" role="form" id="erpAddBWMasterForm" name="erpAddBWMasterForm" novalidate>

                <div class="row">

                    <!--Upload -->
                    <div class="col-xs-3 form-group">   
                        <label for="importFile">Select Upload File<em class="asteriskRed">*</em></label>						   
                        <input
                            type="file"
                            class="btn btn-default col-xs-12 text-left" 
                            name="importFile"
                            id="importFile"
                            valid-file
                            ng-model="addBWMaster.importFile">
                        <p><a target="_blank"href="{{SITE_URL}}/public/sample/itcerp_customer_exist_account_master.csv" class="generate pull-right">Download Format</a></p>
                        <span ng-messages="erpAddBWMasterForm.importFile.$error" ng-if='erpAddBWMasterForm.importFile.$dirty || erpAddBWMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Upload File Name is required</span>
                        </span>
                    </div>
                    <!--/Upload -->

                    <!--Save Button-->
                    <div class="col-xs-2 form-group text-left mT25">
                        <label for="submit">{{ csrf_field() }}</label>
                        <button type="submit" ng-disabled="erpAddBWMasterForm.$invalid" class="btn btn-primary" ng-click="funAddUploadMaster()">Upload</button>
                        <button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                    <!--Save Button-->
                </div>
            </form>
        </div>
    </div>
</div>
