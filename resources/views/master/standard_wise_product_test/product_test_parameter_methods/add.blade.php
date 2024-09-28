<div ng-model="addParaFormDiv" ng-hide="addParaFormDiv">
    <form name='addtestParameterForm' id="add_test_parameter_form" novalidate ng-click="closeAutoSearch()">
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row">
            <div class="col-xs-3 form-group">
                <label for="test_parameter_id">Parameter<em class="asteriskRed">*</em></label>
                <a title="Select Parameter Category" data-toggle="modal" ng-click="showParameterCatTreeViewPopUp(12)" class='generate cursor-pointer'>Tree View</a>
                <input class="form-control" autocomplete="off" ng-model="productTestParameter.test_parameter_name" ng-change="getAutoSearchParameterMatches(productTestParameter.test_parameter_name,globalProductCategoryId,globalParameterCategoryId);" ng-required='true' placeholder="Parameter">
                <input type="hidden" name="test_parameter_id" ng-model="productTestParameter.test_parameter_id" ng-value="productTestParameter.test_parameter_id" ng-bind="productTestParameter.test_parameter_id" ng-required='true'>
                <span ng-messages="addtestParameterForm.test_parameter_id.$error" ng-if='addtestParameterForm.test_parameter_id.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Parameter is required</span>
                </span>
                <span id="results_count"></span>
                <ul ng-if="parameterNameList.length" class="autoSearch">
                    <li ng-repeat="parameterObj in parameterNameList" ng-click="funsetAutoSelectedParameter(parameterObj.id,parameterObj.name,'add');funSetTestParameterDetail(parameterObj,'add');" ng-bind-html="parameterObj.name"></li>
                </ul>
                <ul ng-if="showAutoSearchParameterList && !parameterNameList.length" class="autoSearch">
                    <li> No Record Found! </li>
                </ul>
            </div>

            <div class="col-xs-3 form-group" ng-if="showDescriptionTextarea">
                <label for="description">Description <em class="asteriskRed">*</em> </label>
                <textarea class="p_input_type form-control" ng-model="productTestParameter.description" ng-value="nullValue" name="description" rows="1" id="description" placeholder="Description" />
                </textarea>
                <span ng-messages="addtestParameterForm.description.$error" ng-if='addtestParameterForm.description.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Description is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="showDescriptionTextarea">
                <input type='hidden' name="current_test_id" ng-value='current_test_id' ng-model='current_test_id'>
                <a href="javascript:;" ng-show="{{defined('ADD') && ADD}}" type='submit' class='mT26 btn btn-primary' ng-click='addParametersRecord()' title="Save Parameters">Save</a>
                <button title="Reset" type="button" class="mT26 btn btn-default" ng-click="resetParaForm()" data-dismiss="modal">Reset</button>
            </div>

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="equipment_type_id" class="outer-lable">
                    <span class="filter-lable">Equipment<em class="asteriskRed">*</em></span>
                    <span class="filterCatLink">
                        <a title="Search Equipment" ng-hide="searchEquipmentFilterBtn" href="javascript:;" ng-click="showEquipmentDropdownSearchFilter()"><i class="fa fa-filter"></i></a>
                    </span>
                    <span ng-hide="searchEquipmentFilterInput" class="filter-span-col-2">
                        <input type="text" class="filter-text" placeholder="Enter keyword" ng-model="searchEquipment.equipmentText" />
                        <button title="Close Filter" type="button" class="close filter-close" ng-click="hideEquipmentDropdownSearchFilter()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </span>
                </label>
                <select class="form-control" ng-if="globalProductCategoryId == 2" name="equipment_type_id" ng-model="productTestParameter.equipment_type_id.selectedOption" ng-required='true' ng-change="getEquipDetectorsList(globalProductCategoryId,productTestParameter.equipment_type_id.selectedOption);" ng-options="item.id as item.name for item in (parameterEquipmentList | filter:searchEquipment.equipmentText) track by item.id ">
                    <option value="">Select Equipment</option>
                </select>
                <select class="form-control" ng-if="globalProductCategoryId != 2" name="equipment_type_id" ng-model="productTestParameter.equipment_type_id.selectedOption" ng-required='true' ng-change="getMethodsList(globalProductCategoryId,productTestParameter.equipment_type_id.selectedOption);getEquipDetectorsList(globalProductCategoryId,productTestParameter.equipment_type_id.selectedOption);" ng-options="item.id as item.name for item in (parameterEquipmentList | filter:searchEquipment.equipmentText) track by item.id ">
                    <option value="">Select Equipment</option>
                </select>
                <span ng-messages="addtestParameterForm.equipment_type_id.$error" ng-if='addtestParameterForm.equipment_type_id.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Equipment is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea && globalProductCategoryId == 2">
                <label for="equipment_type_id" class="outer-lable"><span class="filter-lable">Detector</span></label>
                <select class="form-control" name="detector_id" ng-model="productTestParameter.detector_id" ng-options="item.id as item.name for item in detectorList track by item.id ">
                    <option value="">Select Detector</option>
                </select>
                <span ng-messages="addtestParameterForm.detector_id.$error" ng-if='addtestParameterForm.detector_id.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Detector is required</span>
                </span>
            </div>
            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea && globalProductCategoryId != 2">
                <label for="equipment_type_id" class="outer-lable"><span class="filter-lable">Detector</span></label>
                <select class="form-control" name="detector_id" ng-model="productTestParameter.detector_id" ng-options="item.id as item.name for item in detectorList track by item.id">
                    <option value="">Select Detector</option>
                </select>
            </div>

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="method_id" class="outer-lable">
                    <span class="filter-lable">Method<em class="asteriskRed">*</em></span>
                    <span class="filterCatLink">
                        <a title="Search Method" ng-hide="searchMethodFilterBtn" href="javascript:;" ng-click="showMethodDropdownSearchFilter()"><i class="fa fa-filter"></i></a>
                    </span>
                    <span ng-hide="searchMethodFilterInput" class="filter-span-col-2">
                        <input type="text" class="filter-text" placeholder="Enter keyword" ng-model="searchMethod.methodText" />
                        <button title="Close Filter" type="button" class="close filter-close" ng-click="hideMethodDropdownSearchFilter()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </span>
                </label>
                <select class="form-control" name="method_id" ng-model="productTestParameter.method_id.selectedOption" ng-required='true' id="method_id" ng-options="item.id as item.name for item in (methodList | filter:searchMethod.methodText) track by item.id ">
                    <option value="">Select Method</option>
                </select>
                <span ng-messages="addtestParameterForm.method_id.$error" ng-if='addtestParameterForm.method_id.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Method is required</span>
                </span>
            </div>

        </div>
        <div class="row" ng-if="!showDescriptionTextarea">

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="standard_value_type" title="Claim Dependent">Select Claim Dependent<span class="asteriskRed">*</span></label>
                <select class="form-control" ng-required='true' name="claim_dependent" id="claim_dependent" ng-options="option.name for option in productTestParameter.claimDependentList.claimOptions track by option.id" ng-model="productTestParameter.claimDependentList.selectedClaim">
                    <option value="">Select Claim Dependent</option>
                </select>
                <span ng-messages="addtestParameterForm.claim_dependent.$error" ng-if='addtestParameterForm.claim_dependent.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Claim Dependent is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="time_taken_days">Time<sup>(In Days)</sup><span class="asteriskRed">*</span></label>
                <input type="number" min=0 class="p_input_type form-control" ng-model="productTestParameter.time_taken_days" name="time_taken_days" id="time_taken_days" ng-required='true' placeholder="Time(In Days)" />
                <span ng-messages="addtestParameterForm.time_taken_days.$error" ng-if='addtestParameterForm.time_taken_days.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Time<sup>(In Days)</sup> is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="time_taken">Time<sup>(In Hours:Minutes)</sup><span class="asteriskRed">*</span></label>
                <input type="text" class="text p_input_type form-control" ng-model="productTestParameter.time_taken_mins" name="time_taken_mins" ui-date value="00:00" id="time_taken_mins" ng-required='true' placeholder="Time(In Hours:Minutes)" />
                <span ng-messages="addtestParameterForm.time_taken_mins.$error" ng-if='addtestParameterForm.time_taken_mins.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Time(In Hours:Minutes) is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="standard_value_type">Standard Value Type<span class="asteriskRed">*</span></label>
                <select class="form-control" ng-required='true' name="standard_value_type" id="standard_value_type" ng-options="item.name for item in productTestParameter.data_types.availableTypeOptions track by item.id" ng-model="productTestParameter.data_types.selectedOption" ng-change="onTypeChange(productTestParameter.data_types.selectedOption.id)">
                    <option value="">Standard Value Type</option>
                </select>
                <span ng-messages="addtestParameterForm.standard_value_type.$error" ng-if='addtestParameterForm.standard_value_type.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Standard value type is required</span>
                </span>
            </div>
        </div>

        <div class="row" ng-if="!showDescriptionTextarea">
            <div class="col-xs-3 form-group">
                <label for="standard_value_from">Standard Value From <span ng-if="stdValFromToHide" class="asteriskRed">*</span></label>
                <input type="text" ng-if="stdValFromToHide" class="p_input_type form-control" ng-model="productTestParameter.standard_value_from" name="standard_value_from" id="standard_value_from" ng-required='true' placeholder="Standard Value From" />
                <input type="text" ng-if="!stdValFromToHide" class="p_input_type form-control" ng-model="productTestParameter.standard_value_from" name="standard_value_from" id="standard_value_from" placeholder="Standard Value From" />
                <div ng-if="stdValFromToHide">
                    <span ng-messages="addtestParameterForm.standard_value_from.$error" ng-if='addtestParameterForm.standard_value_from.$dirty  || addtestParameterForm.$submitted' role="alert">
                        <span ng-message="required" class="error">Standard value from is required</span>
                    </span>
                </div>
            </div>
            <div class="col-xs-3 form-group">
                <label for="standard_value_to">Standard Value To<span ng-if="stdValFromToHide" class="asteriskRed">*</span></label>
                <input type="text" ng-if="stdValFromToHide" class="p_input_type form-control" ng-model="productTestParameter.standard_value_to" name="standard_value_to" id="standard_value_to" ng-required='true' placeholder="Standard Value To" />
                <input type="text" ng-if="!stdValFromToHide" class="p_input_type form-control" ng-model="productTestParameter.standard_value_to" name="standard_value_to" id="standard_value_to" placeholder="Standard Value To" />
                <div ng-if="stdValFromToHide">
                    <span ng-messages="addtestParameterForm.standard_value_to.$error" ng-if='addtestParameterForm.standard_value_to.$dirty  || addtestParameterForm.$submitted' role="alert">
                        <span ng-message="required" class="error">Standard value to is required</span>
                    </span>
                </div>
            </div>

            <div class="col-xs-3 form-group">
                <label for="cost_price">Cost Price<span class="asteriskRed">*</span></label>
                <input type="text" class="p_input_type form-control" ng-model="productTestParameter.cost_price" name="cost_price" id="cost_price" min=0 ng-required='true' placeholder="Cost Price" />
                <span ng-messages="addtestParameterForm.cost_price.$error" ng-if='addtestParameterForm.cost_price.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Cost price is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group">
                <label for="selling_price">Selling Price<span class="asteriskRed">*</span></label>
                <input type="text" class="p_input_type form-control" ng-model="productTestParameter.selling_price" name="selling_price" id="selling_price" min=0 ng-required='true' placeholder="Selling Price" />
                <span ng-messages="addtestParameterForm.selling_price.$error" ng-if='addtestParameterForm.selling_price.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Selling Price is required</span>
                </span>
            </div>
        </div>

        <div class="row" ng-if="!showDescriptionTextarea">

            <!--Decimal Place-->
            <div class="col-xs-3 form-group">
                <label for="parameter_decimal_place">Decimal Place(For Limit)<em class="asteriskRed">*</em></label>
                <input type="text" class="form-control" ng-model="productTestParameter.parameter_decimal_place" name="parameter_decimal_place" id="parameter_decimal_place" min="0" ng-required="true" placeholder="Decimal Place(For Result)">
                <span ng-messages="addtestParameterForm.parameter_decimal_place.$error" ng-if='addtestParameterForm.parameter_decimal_place.$dirty || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Decimal Place is required</span>
                </span>
                <p class="note-info">(Ex:To Display 1.45320 to 3 Decimal Places,please enter 3,then System will display 1.453)</p>
            </div>
            <!--/Decimal Place-->

            <!--Measurement Uncertainty-->
            <div class="col-xs-3 form-group">
                <label for="measurement_uncertainty">Measurement of Uncertainty</label>
                <input type="text" class="p_input_type form-control" ng-model="productTestParameter.measurement_uncertainty" name="measurement_uncertainty" id="measurement_uncertainty" placeholder="Measurement Uncertainty" />
            </div>
            <!--/Measurement Uncertainty-->

            <!--Limit Determination-->
            <div class="col-xs-3 form-group">
                <label for="limit_determination">Limit of Determination</label>
                <input type="text" class="p_input_type form-control" ng-model="productTestParameter.limit_determination" name="limit_determination" id="limit_determination" placeholder="Limit Determination" />
            </div>
            <!--/Limit Determination-->

            <!--LOD-->
            <div class="col-xs-3 form-group">
                <label for="lod">LOD</label>
                <input type="text" class="p_input_type form-control" ng-model="productTestParameter.lod" name="lod" id="lod" placeholder="LOD" />
            </div>
            <!--/LOD-->
        </div>

        <div class="row" ng-if="!showDescriptionTextarea">

            <!--MRPL-->
            <div class="col-xs-3 form-group">
                <label for="mrpl">MRPL</label>
                <input type="text" class="p_input_type form-control" ng-model="productTestParameter.mrpl" name="mrpl" id="mrpl" placeholder="MRPL" />
            </div>
            <!--/MRPL-->

            <!--Validation Protocol-->
            <div class="col-xs-3 form-group">
                <label for="validation_protocol">Validation Protocol</label>
                <input type="text" class="p_input_type form-control" ng-model="productTestParameter.validation_protocol" name="validation_protocol" id="validation_protocol" placeholder="Validation Protocol" />
            </div>
            <!--/Validation Protocol-->

            <!--Parameter NABL Scope-->
            <div class="col-xs-3 form-group">
                <label for="parameter_nabl_scope">NABL Scope</label>
                <div class="checkbox">
                    <label for="parameter_nabl_scope">
                        <input type="checkbox" ng-checked="addDefaultParameterNablScope" ng-model="productTestParameter.parameter_nabl_scope" name="parameter_nabl_scope" id="parameter_nabl_scope">
                        Check to Consider
                    </label>
                </div>
            </div>
            <!--/Parameter NABL Scope-->

            <!-- status---->
            <div class="col-xs-3">
                <label for="status">Status<em class="asteriskRed">*</em></label>
                <select class="form-control" ng-required='true' name="status" id="status" ng-options="status.name for status in statusList track by status.id" ng-model="productTestParameter.status.selectedOption">
                    <option value="">Select Status</option>
                </select>
                <span ng-messages="addtestParameterForm.status.$error" ng-if='addtestParameterForm.status.$dirty  || addtestParameterForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
            <!-- /status---->
        </div>

        <div class="row" ng-if="!showDescriptionTextarea">

            <!--Action-->
            <div class="col-xs-3 form-group mT10">
                <input type="hidden" name="product_category_id" ng-value="globalProductCategoryId">
                <input type='hidden' name="current_test_id" ng-value='current_test_id' ng-model='current_test_id'>
                <a href="javascript:;" ng-show="{{defined('ADD') && ADD}}" type='submit' ng-disabled="addtestParameterForm.$invalid" class='mT26 btn btn-primary' ng-click='addParametersRecord()' title="Save Parameters">Save</a>
                <button title="Reset" type="button" class="mT26 btn btn-default" ng-click="resetParaForm()" data-dismiss="modal">Reset</button>
            </div>
            <!--/Action-->
        </div>
    </form>
</div>
