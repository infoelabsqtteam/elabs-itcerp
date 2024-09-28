<div ng-model="editParaFormDiv" ng-hide="editParaFormDiv">
    <form name='editParaForm' id="edit_test_parameter_form" novalidate>
        <label for="submit">{{ csrf_field() }}</label>
        <div class="row">
            <div class="col-xs-3 form-group">
                <label for="test_parameter_id">Parameter <em class="asteriskRed">*</em></label>
                <a title="Select Parameter Category" data-toggle="modal" ng-click="showParameterCatTreeViewPopUp(12)" class='generate cursor-pointer'>Tree View</a>
                <input class="form-control" autocomplete="off" ng-model="editProductTestParameter.test_parameter_name" ng-bind-html="editProductTestParameter.test_parameter_name" ng-change="getAutoSearchParameterMatches(editProductTestParameter.test_parameter_name,globalProductCategoryId,globalParameterCategoryId);" ng-required='true' placeholder="Parameter">
                <input type="hidden" name="test_parameter_id" ng-model="editProductTestParameter.test_parameter_id" ng-value="editProductTestParameter.test_parameter_id" ng-bind="editProductTestParameter.test_parameter_id" ng-required='true'>
                <span ng-messages="editProductTestParameter.test_parameter_id.$error" ng-if='editProductTestParameter.test_parameter_id.$dirty  || editProductTestParameter.$submitted' role="alert">
                    <span ng-message="required" class="error">Parameter is required</span>
                </span>
                <ul ng-if="parameterNameList.length" class="autoSearch">
                    <li ng-repeat="parameterObj in parameterNameList" ng-click="funsetAutoSelectedParameter(parameterObj.id,parameterObj.name,'edit');funSetTestParameterDetail(parameterObj,'edit');">[[parameterObj.name]]</li>
                    <li ng-if="!parameterNameList.length">No Record Found!</li>
                </ul>
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
                <select class="form-control" ng-if="globalProductCategoryId == 2" name="equipment_type_id" ng-model="editProductTestParameter.equipment_type_id.selectedOption" ng-required='true' ng-change="setEquipmentSelectedOption(editProductTestParameter.equipment_type_id.selectedOption);getEquipDetectorsList(globalProductCategoryId,editProductTestParameter.equipment_type_id.selectedOption.id);" ng-options="item.id as item.name for item in (parameterEquipmentList | filter:searchEquipment.equipmentText) track by item.id ">
                    <option value="">Select Equipment</option>
                </select>
                <select class="form-control" ng-if="globalProductCategoryId != 2" name="equipment_type_id" ng-model="editProductTestParameter.equipment_type_id.selectedOption" ng-required='true' ng-change="getMethodsList(globalProductCategoryId,editProductTestParameter.equipment_type_id.selectedOption);" ng-options="item.id as item.name for item in (parameterEquipmentList | filter:searchEquipment.equipmentText) track by item.id ">
                    <option value="">Select Equipment</option>
                </select>
                <span ng-messages="editParaForm.equipment_type_id.$error" ng-if='editParaForm.equipment_type_id.$dirty  || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Equipment is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="[[globalProductCategoryId]] == 2 && !showDescriptionTextarea">
                <label for="equipment_type_id" class="outer-lable">Detector</label>
                <select class="form-control" id="detector_id" name="detector_id" ng-model="editProductTestParameter.detector_id.selectedOption" ng-options="item.id as item.name for item in detectorList track by item.id ">
                    <option value="">Select Detector</option>
                </select>
                <span ng-messages="editParaForm.detector_id.$error" ng-if='editParaForm.detector_id.$dirty  || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Detector is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="[[globalProductCategoryId]] != 2 && !showDescriptionTextarea">
                <label for="equipment_type_id" class="outer-lable">Detector</label>
                <select class="form-control" id="detector_id" name="detector_id" ng-model="editProductTestParameter.detector_id.selectedOption" ng-options="item.id as item.name for item in detectorList track by item.id ">
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
                <select class="form-control" name="method_id" ng-model="editProductTestParameter.method_id.selectedOption" ng-required='true' ng-change="setMethodSelectedOption(editProductTestParameter.method_id.selectedOption)" ng-options="item.id as item.name for item in (methodList | filter:searchMethod.methodText) track by item.id ">
                    <option value="">Select Method</option>
                </select>
                <span ng-messages="editParaForm.method_id.$error" ng-if='editParaForm.method_id.$dirty  || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Method is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="showDescriptionTextarea">
                <label for="description">Description</label>
                <textarea class="p_input_type form-control" ng-model="editProductTestParameter.description" ng-value="nullValue" name="description" rows="1" id="description" placeholder="Description" />
                </textarea>
                <span ng-messages="editParaForm.description.$error" ng-if='editParaForm.description.$dirty  || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Description is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="showDescriptionTextarea">
                <input type='hidden' name="para_test_id" ng-value="para_test_id" ng-model="para_test_id">
                <input type='hidden' name="product_test_dtl_id" ng-value="product_test_dtl_id">
                <button ng-show="{{defined('EDIT') && EDIT}}" title="Update" ng-disabled="editParaForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='updateParameterRecord(para_test_id,product_test_dtl_id)'> Update </button>
                <button type='button' title="Cancel" class='mT26 btn btn-default' ng-click='cancelEditPara()'> Cancel </button>
            </div>
        </div>

        <div class="row" ng-if="!showDescriptionTextarea">
            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="claim_dependent">Claim Dependent<span class="asteriskRed">*</span></label>
                <select class="form-control" ng-required='true' name="claim_dependent" id="claim_dependent" ng-options="option.name for option in editProductTestParameter.claimDependentList.claimOptions track by option.id" ng-model="editProductTestParameter.claimDependentList.selectedClaim">
                    <option value="">Select Claims Dependent</option>
                </select>
                <span ng-messages="editParaForm.claim_dependent.$error" ng-if='editParaForm.claim_dependent.$dirty  || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Claim Dependent is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="time_taken_days">Time<sup>(In Days)</sup><span class="asteriskRed">*</span></label>
                <input type="number" min=0 class="text form-control" ng-model="editProductTestParameter.time_taken_days" name="time_taken_days" id="time_taken_days" ng-required='true' placeholder="Time " />
                <span ng-messages="editParaForm.time_taken_days.$error" ng-if='editParaForm.time_taken_days.$dirty || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Time is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="time_taken_mins">Time<sup>(In Hours/Minutes)</sup><span class="asteriskRed">*</span></label>
                <input type="text" class="text form-control" ng-model="editProductTestParameter.time_taken_mins" ng-value="editProductTestParameter.time_taken_mins" name="time_taken_mins" ui-date id="time_taken_mins" ng-required='true' placeholder="Time " />
                <span ng-messages="editParaForm.time_taken_mins.$error" ng-if='editParaForm.time_taken_mins.$dirty || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Time is required</span>
                </span>
            </div>

            <div class="col-xs-3 form-group" ng-if="!showDescriptionTextarea">
                <label for="standard_value_type">Standard Value Type<span class="asteriskRed">*</span></label>
                <select class="form-control" ng-required='true' name="standard_value_type" id="standard_value_type" ng-options="item.name for item in editProductTestParameter.data_types.availableTypeOptions track by item.id" ng-model="editProductTestParameter.data_types.selectedOption" ng-change="onTypeChange(editProductTestParameter.data_types.selectedOption.id)">
                    <option value="">Standard Value Type</option>
                </select>
                <span ng-messages="editParaForm.standard_value_type.$error" ng-if='editParaForm.standard_value_type.$dirty  || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Standard value type is required</span>
                </span>
            </div>
        </div>

        <div class="row" ng-if="!showDescriptionTextarea">
            <div class="col-xs-3 form-group">
                <label for="standard_value_from">Standard Value From<span ng-if="stdValFromToHide" class="asteriskRed">*</span></label>
                <input type="text" ng-if="stdValFromToHide" class="form-control" ng-model="editProductTestParameter.standard_value_from" name="standard_value_from" id="standard_value_from1" ng-required='true' placeholder="Standard Value From" />
                <input type="text" ng-if="!stdValFromToHide" class="form-control" ng-model="editProductTestParameter.standard_value_from" name="standard_value_from" id="standard_value_from1" placeholder="Standard Value From" />
                <div ng-if="stdValFromToHide">
                    <span ng-messages="editParaForm.standard_value_from.$error" ng-if='editParaForm.standard_value_from.$dirty  || editParaForm.$submitted' role="alert">
                        <span ng-message="required" class="error">Standard value from is required</span>
                    </span>
                </div>
            </div>

            <div class="col-xs-3 form-group">
                <label for="standard_value_to">Standard Value To<span ng-if="stdValFromToHide" class="asteriskRed">*</span></label>
                <input type="text" ng-if="stdValFromToHide" class="form-control" ng-model="editProductTestParameter.standard_value_to" name="standard_value_to" id="standard_value_to1" ng-required='true' placeholder="Standard Value To" />
                <input type="text" ng-if="!stdValFromToHide" class="form-control" ng-model="editProductTestParameter.standard_value_to" name="standard_value_to" id="standard_value_to1" placeholder="Standard Value To" />
                <div ng-if="stdValFromToHide">
                    <span ng-messages="editParaForm.standard_value_to.$error" ng-if='editParaForm.standard_value_to.$dirty  || editParaForm.$submitted' role="alert">
                        <span ng-message="required" class="error">Standard value to is required</span>
                    </span>
                </div>
            </div>
            <div class="col-xs-3 form-group">
                <label for="cost_price">Cost Price<span class="asteriskRed">*</span></label>
                <input type="text" class="p_input_type form-control" ng-model="editProductTestParameter.cost_price" name="cost_price" min=0 id="cost_price" ng-required='true' placeholder="Cost Price" />
                <span ng-messages="editParaForm.cost_price.$error" ng-if='editParaForm.cost_price.$dirty  || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Cost price is required</span>
                </span>
            </div>
            <div class="col-xs-3 form-group">
                <label for="selling_price">Selling Price<span class="asteriskRed">*</span></label>
                <input type="text" class="p_input_type form-control" ng-model="editProductTestParameter.selling_price" name="selling_price" min=0 id="selling_price" ng-required='true' placeholder="Selling Price" />
                <span ng-messages="editParaForm.selling_price.$error" ng-if='editParaForm.selling_price.$dirty  || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Selling Price is required</span>
                </span>
            </div>
        </div>

        <div class="row" ng-if="!showDescriptionTextarea">

            <!--Decimal Place-->
            <div class="col-xs-3 form-group">
                <label for="parameter_decimal_place">Decimal Place(For Limit)<em class="asteriskRed">*</em></label>
                <input type="text" class="form-control" ng-model="editProductTestParameter.parameter_decimal_place" name="parameter_decimal_place" id="parameter_decimal_place" min="0" ng-required="true" placeholder="Decimal Place(For Result)">
                <span ng-messages="editParaForm.parameter_decimal_place.$error" ng-if='editParaForm.parameter_decimal_place.$dirty || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Decimal Place is required</span>
                </span>
                <p class="note-info">(Ex:To Display 1.45320 to 3 Decimal Places,please enter 3,then System will display 1.453)</p>
            </div>
            <!--/Decimal Place-->

            <!--Measurement Uncertainty-->
            <div class="col-xs-3 form-group">
                <label for="measurement_uncertainty">Measurement of Uncertainty</label>
                <input type="text" class="p_input_type form-control" ng-model="editProductTestParameter.measurement_uncertainty" name="measurement_uncertainty" id="edit_measurement_uncertainty" placeholder="Measurement Uncertainty" />
            </div>
            <!--/Measurement Uncertainty-->

            <!--Limit Determination-->
            <div class="col-xs-3 form-group">
                <label for="limit_determination">Limit of Determination</label>
                <input type="text" class="p_input_type form-control" ng-model="editProductTestParameter.limit_determination" name="limit_determination" id="edit_limit_determination" placeholder="Limit Determination" />
            </div>
            <!--/Limit Determination-->

            <!--LOD-->
            <div class="col-xs-3 form-group">
                <label for="lod">LOD</label>
                <input type="text" class="p_input_type form-control" ng-model="editProductTestParameter.lod" name="lod" id="edit_lod" placeholder="LOD" />
            </div>
            <!--/LOD-->
        </div>

        <div class="row" ng-if="!showDescriptionTextarea">

            <!--MRPL-->
            <div class="col-xs-3 form-group">
                <label for="mrpl">MRPL</label>
                <input type="text" class="p_input_type form-control" ng-model="editProductTestParameter.mrpl" name="mrpl" id="edit_mrpl" placeholder="MRPL" />
            </div>
            <!--/MRPL-->

            <!--Validation Protocol-->
            <div class="col-xs-3 form-group">
                <label for="edit_validation_protocol">Validation Protocol</label>
                <input type="text" class="p_input_type form-control" ng-model="editProductTestParameter.validation_protocol" name="validation_protocol" id="edit_validation_protocol" placeholder="Validation Protocol" />
            </div>
            <!--/Validation Protocol-->

            <!--Parameter NABL Scope-->
            <div class="col-xs-3 form-group">
                <label for="parameter_nabl_scope">NABL Scope</label>
                <div class="checkbox">
                    <label for="parameter_nabl_scope">
                        <input type="checkbox" ng-checked="editDefaultParameterNablScope" ng-model="editProductTestParameter.parameter_nabl_scope" name="parameter_nabl_scope" id="parameter_nabl_scope">
                        Check to Consider
                    </label>
                </div>
            </div>
            <!--/Parameter NABL Scope-->

            <!-- status---->
            <div class="col-xs-3">
                <label for="status">Status<em class="asteriskRed">*</em></label>
                <select class="form-control" ng-required='true' name="status" id="status" ng-options="status.name for status in statusList track by status.id" ng-model="editProductTestParameter.status.selectedOption">
                    <option value="">Select Status</option>
                </select>

                <span ng-messages="editParaForm.status.$error" ng-if='editParaForm.status.$dirty  || editParaForm.$submitted' role="alert">
                    <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
            <!-- /status---->

        </div>

        <div class="row" ng-if="!showDescriptionTextarea">

            <!--Action-->
            <div class="col-xs-3 form-group mT10">
                <input type='hidden' name="para_test_id" ng-value="para_test_id" ng-model="para_test_id">
                <input type='hidden' name="product_test_dtl_id" ng-value="product_test_dtl_id">
                <input type="hidden" name="product_category_id" ng-value="globalProductCategoryId">
                <button ng-show="{{defined('EDIT') && EDIT}}" title="Update" ng-disabled="editParaForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='updateParameterRecord(para_test_id,product_test_dtl_id)'> Update </button>
                <button type='button' title="Cancel" class='mT26 btn btn-default' ng-click='cancelEditPara()'> Cancel </button>
            </div>
            <!--/Action-->

        </div>
    </form>
</div>
