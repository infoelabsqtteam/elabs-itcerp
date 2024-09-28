<div class="row" ng-hide="isVisibleAddSampleDiv">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row header-form">
                <div role="new" class="navbar-form navbar-left">
                    <span class="pull-left"><strong id="form_title">Add New Sample</strong></span>
                </div>
                <div role="new" class="navbar-form navbar-right"></div>
            </div>
            <form name="erpAddBranchWiseSampleForm" id="erpAddBranchWiseSampleForm" method="POST" novalidate>
                <div class="row">

                    <!--Branch -->
                    <div ng-if="{{$division_id}} == 0" class="col-xs-3 form-group">
                        <label for="division_id">Branch<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="division_id" id="division_id" ng-model="addBranchWiseSample.division_id" ng-options="division.name for division in divisionsCodeList track by division.id">
                            <option value="">Select Branch</option>
                        </select>
                    </div>
                    <div ng-if="{{$division_id}} > 0">
                        <input type="hidden" value="{{$division_id}}" name="division_id" ng-model="addBranchWiseSample.division_id" id="division_id">
                    </div>
                    <!--/Branch -->

                    <!--Parent Product Category-->
                    <div class="col-xs-3 form-group" ng-if="{{$division_id}} == 0">
                        <label for="product_category_id">Department<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="product_category_id" id="product_category_id" ng-model="addBranchWiseSample.product_category_id" ng-change="funTrfSelectBoxList(addBranchWiseSample.division_id.id,addBranchWiseSample.product_category_id.id)" ng-options="item.name for item in parentCategoryList track by item.id" ng-required='true'>
                            <option value="">Select Department</option>
                        </select>
                    </div>
                    <div class="col-xs-3 form-group" ng-if="{{$division_id}} > 0">
                        <label for="product_category_id">Department<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="product_category_id" id="product_category_id" ng-model="addBranchWiseSample.product_category_id" ng-change="funTrfSelectBoxList({{$division_id}},addBranchWiseSample.product_category_id.id)" ng-options="item.name for item in parentCategoryList track by item.id" ng-required='true'>
                            <option value="">Select Department</option>
                        </select>
                    </div>
                    <!--/Parent Product Category-->

                    <!--TRF No.-->
                    <div class="col-xs-3 form-group" ng-if="trfSelectBoxList.length">
                        <label for="trf_id">TRF No.</label>
                        <select class="form-control" name="trf_id" id="trf_id" ng-model="addBranchWiseSample.trf_id" ng-change="funGetTrfInvolvedCustomer(addBranchWiseSample.trf_id.id)" ng-options="item.name for item in trfSelectBoxList track by item.id">
                            <option value="">Select TRF No.</option>
                        </select>
                    </div>
                    <!--TRF No.-->

                    <!--Customer-->
                    <div class="col-xs-3 form-group" ng-if="!isAddNewCustomerSample">
                        <label class="width100" for="customer_id">Customer<em class="asteriskRed">*</em>
                            <span class="pull-right">
                                <a class="generate mL5 cursor-pointer" title="Select Customer" ng-click="funShowCountryStateViewPopup(1)">Select</a>
                                <a href="javascript:;" class="generate" title="New Customer" ng-click="funToAddNewCustomer(1)">New</a>
                            </span>
                        </label>
                        <select class="form-control" name="customer_id" id="customer_id" ng-required="true" ng-model="addBranchWiseSample.customer_id" ng-options="customerList.name for customerList in customerListData track by customerList.id">
                            <option value="">Select Customer</option>
                        </select>
                    </div>
                    <!--/Customer-->

                    <!-- Customer if New-->
                    <div class="col-xs-3 form-group" ng-if="isAddNewCustomerSample">
                        <label for="customer_id">Customer<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" name="customer_name" id="customer_name" ng-required="true" ng-model="addBranchWiseSample.customer_name" placeholder="Customer">
                    </div>

                    <div class="col-xs-3 form-group" ng-if="isAddNewCustomerSample">
                        <label for="customer_id">Customer Email</label>
                        <a href="javascript:;" class="generate" ng-click="funToAddNewCustomer(2)">Back</a>
                        <input type="text" class="form-control" name="customer_email" id="customer_email" ng-model="addBranchWiseSample.customer_email" placeholder="Customer Email">
                    </div>
                    <!-- /Customer if New-->

                    <!--Sample Mode-->
                    <div class="col-xs-3 form-group">
                        <label for="sample_mode">Sample Mode<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="sample_mode_id" id="sample_mode_id" ng-model="addBranchWiseSample.sample_mode_id" ng-options="item.name for item in sampleModeList track by item.id" ng-required="true">
                            <option value="">Select Sample Mode</option>
                        </select>
                    </div>
                    <!--/Sample Mode-->

                    <!--Sampling Date-->
                    <div class="col-xs-3 form-group" ng-if="showOrderDateCalender == 1">
                        <label for="to">Sample Date<em class="asteriskRed">*</em></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" class="bgwhite form-control" ng-model="addBranchWiseSample.sample_date" name="sample_date" id="sample_date" placeholder="Sampling Date" ng-required="true" />
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                        </div>
                    </div>
                    <div ng-if="showOrderDateCalender == 0">
                        <input type="hidden" ng-model="addBranchWiseSample.sample_date" value="{{CURRENTDATETIME}}" name="sample_date" id="sample_date" />
                    </div>
                    <!--Sampling Date-->

                    <!--Sample Mode Description-->
                    <div class="col-xs-3 form-group">
                        <label for="sample_mode_description">Mode Description</label>
                        <textarea row="1" class="form-control" id="sample_mode_description" ng-model="addBranchWiseSample.sample_mode_description" name="sample_mode_description" placeholder="Mode Description">
                        </textarea>
                    </div>
                    <!--/Sample Mode Description-->

                    <!--Remarks-->
                    <div class="col-xs-3 form-group">
                        <label for="remarks">Remark</label>
                        <textarea row="1" class="form-control" id="remarks" ng-model="addBranchWiseSample.remarks" name="remarks" placeholder="Remarks">
                        </textarea>
                    </div>
                    <!--/Remarks-->

                    <!--Save Button-->
                    <div class="col-xs-12 form-group text-right">
                        <label for="submit">{{ csrf_field() }}</label>
                        <button type="submit" ng-disabled="erpAddBranchWiseSampleForm.$invalid" class="btn btn-primary" ng-click="funAddBranchWiseSample(divisionID,sampleStatusID)">Save</button>
                        <button type="button" class="btn btn-default" ng-click="resetButton()">Reset</button>
                    </div>
                    <!--Save Button-->

                </div>
            </form>
        </div>
    </div>
</div>
