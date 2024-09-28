<div class="row" ng-hide="isVisibleEditSampleDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">
                    <span class="pull-left"><strong id="form_title">Edit Sample : <span ng-bind="editBranchWiseSample.sample_no"></span></strong></span>
                </div>
                <div role="new" class="navbar-form navbar-right"></div>
            </div>
            <form name="erpEditBranchWiseSampleForm" id="erpEditBranchWiseSampleForm" method="POST" novalidate>
                <div class="row">

                    <!--Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
                        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="division_id" id="division_id" ng-model="editBranchWiseSample.division_id.selectedOption" ng-options="division.name for division in divisionsCodeList track by division.id">
                            <option value="">Select Branch</option>
                        </select>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
                        <input type="hidden" value="{{$division_id}}" name="division_id" ng-model="editBranchWiseSample.division_id" id="division_id">
                    </div>
                    <!--/Branch -->

                    <!--Parent Product Category-->
                    <div class="col-xs-3 form-group">
                        <label for="product_category_id">Department<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="product_category_id" id="product_category_id" ng-model="editBranchWiseSample.product_category_id.selectedOption" ng-options="item.name for item in parentCategoryList track by item.id" ng-required='true'>
                            <option value="">Select Department</option>
                        </select>
                    </div>
                    <!--/Parent Product Category-->

                    <!--TRF No.-->
                    <div class="col-xs-3 form-group" ng-if="editBranchWiseSample.trf_id">
                        <label for="trf_id">TRF No.</label>
                        <select class="form-control" name="trf_id" id="trf_id" ng-model="editBranchWiseSample.trf_id" ng-options="item.name for item in trfSelectBoxList track by item.id">
                        </select>
                    </div>
                    <!--TRF No.-->

                    <!--Customer-->
                    <div class="col-xs-3 form-group" ng-if="!isEditNewCustomerSample">
                        <label class="width100" for="customer_id">Customer<em class="asteriskRed">*</em><span class="pull-right"><a title="Filter Customer City" class="generate mL5 cursor-pointer" ng-click="funShowCountryStateViewPopup(1)">Select</a></span></label>
                        <select class="form-control" name="customer_id" id="customer_id" ng-required="true" ng-model="editBranchWiseSample.customer_id.selectedOption" ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">Select Customer</option>
                        </select>
                    </div>
                    <!--/Customer-->

                    <!-- Customer if New.-->
                    <div class="col-xs-3 form-group" ng-if="isEditNewCustomerSample">
                        <label for="customer_id">Customer<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" name="customer_name" id="customer_name" ng-required="true" ng-model="editBranchWiseSample.customer_name_new" placeholder="Customer">
                    </div>
                    <!-- /Customer if New.-->

                    <!--Sampling Date-->
                    <div class="col-xs-3 form-group" ng-if="{{$division_id}} == 0">
                        <label for="to">Sample Date<em class="asteriskRed">*</em></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" class="bgwhite form-control" ng-model="editBranchWiseSample.sample_date" name="sample_date" id="sample_date" placeholder="Sampling Date" ng-required="true" />
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                        </div>
                    </div>
                    <!--Sampling Date-->

                    <!--Sampling Date-->
                    <div ng-if="{{$division_id}} > 0">
                        <input type="hidden" ng-model="editBranchWiseSample.sample_date" ng-value="editBranchWiseSample.sample_date" name="sample_date" id="sample_date" />
                    </div>
                    <!--Sampling Date-->

                    <!--Sample Mode-->
                    <div class="col-xs-3 form-group">
                        <label for="sample_mode">Sample Mode<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="sample_mode_id" id="sample_mode_id" ng-model="editBranchWiseSample.sample_mode_id.selectedOption" ng-options="item.name for item in sampleModeList track by item.id" ng-required="true">
                            <option value="">Select Sample Mode</option>
                        </select>
                    </div>
                    <!--/Sample Mode-->

                    <!--Sample Mode Description-->
                    <div class="col-xs-3 form-group">
                        <label for="sample_mode_description">Mode Description</label>
                        <textarea row="1" class="form-control" id="sample_mode_description" ng-model="editBranchWiseSample.sample_mode_description" name="sample_mode_description" placeholder="Mode Description">
                        </textarea>
                    </div>
                    <!--/Sample Mode Description-->

                    <!--Remarks-->
                    <div class="col-xs-3 form-group">
                        <label for="remarks">Remark</label>
                        <textarea row="1" class="form-control" id="remarks" ng-model="editBranchWiseSample.remarks" name="remarks" placeholder="Remarks">
                        </textarea>
                    </div>
                    <!--/Remarks-->

                    <!--Update Button-->
                    <div class="col-xs-12 form-group text-right">
                        <label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" ng-model="editBranchWiseSample.sample_id" name="sample_id" ng-value="editBranchWiseSample.sample_id" id="sample_id">
                        <input type="hidden" ng-model="editBranchWiseSample.sample_no" name="sample_no" ng-value="editBranchWiseSample.sample_no" id="sample_no">
                        <input type="hidden" ng-model="editBranchWiseSample.sample_date_org" ng-value="editBranchWiseSample.sample_date_org" name="sample_date_org" id="sample_date_org" />
                        <button type="submit" ng-disabled="erpEditBranchWiseSampleForm.$invalid" class="btn btn-primary" ng-click="funUpdateBranchWiseSample(divisionID,sampleStatusID)">Update</button>
                        <button type="button" class="btn btn-default" ng-click="backButton()">Back</button>
                    </div>
                    <!--Update Button-->

                </div>
            </form>
        </div>
    </div>
</div>
