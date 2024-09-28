<div class="order_detail">  
    <div class="order-section mT10 text-center mB10 fontbd">Add Prototype</div>
    <div class="row mT10">
    
        <!-- From date-->
	<div class="col-xs-2 form-group">
	    <label for="stb_start_date">Start Date<em class="asteriskRed">*</em></label>
	    <div class="input-group">
		<input
		    readonly
		    type="text"
		    id="add_stb_start_date"
		    ng-model="orderAddStabilityPrototype.stb_start_date"
		    name="stb_start_date"
                    ng-required="true"
		    class="form-control bgWhite stb_start_datepicker"
		    placeholder="Start Date">
		<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
	    </div>
	    <span ng-messages="erpCreateStabilityOrderPrototypeForm.stb_start_date.$error" ng-if="erpCreateStabilityOrderPrototypeForm.stb_start_date.$dirty  || erpCreateStabilityOrderPrototypeForm.$submitted" role="alert">
		<span ng-message="required" class="error">Start date is required</span>
	    </span>
	</div>
	<!-- /From date-->
        
        <!-- To date-->
	<div class="col-xs-2 form-group">
	    <label for="stb_end_date">End Date<em class="asteriskRed">*</em></label>
	    <div class="input-group">
		<input
		    readonly
		    type="text"
		    id="add_stb_end_date"
		    ng-model="orderAddStabilityPrototype.stb_end_date"
		    name="stb_end_date"
                    ng-required="true"
		    class="form-control bgWhite stb_end_datepicker"
		    placeholder="End Date">
		<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
	    </div>
	    <span ng-messages="erpCreateStabilityOrderPrototypeForm.stb_end_date.$error" ng-if="erpCreateStabilityOrderPrototypeForm.stb_end_date.$dirty  || erpCreateStabilityOrderPrototypeForm.$submitted" role="alert">
		<span ng-message="required" class="error">End date is required</span>
	    </span>
	</div>
	<!-- /To date-->
        
        <!--Label name-->
	<div class="col-xs-2 form-group">
	    <label for="stb_label_name">Label name<em class="asteriskRed">*</em></label>
	    <input
		type="text"
		class="form-control"
		id="stb_label_name"
		ng-model="orderAddStabilityPrototype.stb_label_name"
                ng-bind="orderAddStabilityPrototype.stb_start_date"
		ng-required="true"
		name="stb_label_name"
		placeholder="Label name">
	    <span ng-messages="erpCreateStabilityOrderPrototypeForm.stb_label_name.$error" ng-if='erpCreateStabilityOrderPrototypeForm.stb_label_name.$dirty || erpCreateStabilityOrderPrototypeForm.$submitted' role="alert">
		<span ng-message="required" class="error">Label name is required</span>
	    </span>
	</div>
	<!--/Label name-->
        
        <!--Test Product-->
        <div class="col-xs-3 form-group">
            <label for="stb_product_id">Product Name<em class="asteriskRed">*</em></label>
            <select
                class="form-control" 
                name="stb_product_id" 
                ng-model="orderAddStabilityPrototype.stb_product_id.selectedOption" 
                id="stb_product_id"
                ng-required="true"
                ng-change="funGetProductTestMaster(orderAddStabilityPrototype.stb_product_id.selectedOption.product_id)"
                ng-options="testProductMaster.product_name for testProductMaster in testProductMasterList track by testProductMaster.product_id">
                <option value="">Select Product Name</option>
            </select> 
            <span ng-messages="erpCreateStabilityOrderPrototypeForm.stb_product_id.$error" ng-if='erpCreateStabilityOrderPrototypeForm.stb_product_id.$dirty  || erpCreateStabilityOrderPrototypeForm.$submitted' role="alert">
                <span ng-message="required" class="error">Product Name is required</span>
            </span>
        </div>
        <!--/Test Product-->
        
        <!--Product Tests-->
        <div class="col-xs-3 form-group">
            <label for="stb_product_test_id" class="width100pc">Product Tests<em class="asteriskRed">*</em></label>
            <select
                class="form-control"
                name="stb_product_test_id"
                ng-model="orderAddStabilityPrototype.stb_product_test_id.selectedOption" 
                id="stb_product_test_id"
                ng-required="true"
                ng-change="funGetAddProductTestMasterTabularList();"
                ng-options="productTestMaster.test_code for productTestMaster in productTestMasterList track by productTestMaster.test_id">
                <option value="">Select Product Tests</option>
            </select>
            <input type="hidden" name="stb_test_standard_id" ng-model="orderAddStabilityPrototype.stb_test_standard_id" ng-value="orderAddStabilityPrototype.stb_test_standard_id" id="stb_test_standard_id">
            <span ng-messages="erpCreateStabilityOrderPrototypeForm.stb_product_test_id.$error" ng-if='erpCreateStabilityOrderPrototypeForm.stb_product_test_id.$dirty  || erpCreateStabilityOrderPrototypeForm.$submitted' role="alert">
                <span ng-message="required" class="error">Product Tests is required</span>
            </span>
        </div>
        <!--/Product Tests-->
    
    </div>
    
    <!--Stability Condition-->
    <div class="row mT20" ng-if="stabilityConditionList.length">
        <div class="col-xs-6">
            <label for="stb_stability_type_id">Storage Condition<em class="asteriskRed">*</em></label>
            <div class="col-sm-12" id="style-default">
                <div class="col-sm-12 pull-left checkbox mB5" ng-repeat="stabilityConditionObj in stabilityConditionList track by $index">
                    <div id="add_stb_condition_chk_div_id" class="pull-left width5">
                        <input type="checkbox" class="add_stb_condition_chk_class" ng-checked="allAddSelectedStabilityConditionArray.indexOf(stabilityConditionObj.id) > -1"  ng-click="funGetAddSampleQtyFieldOnStbSelection(stabilityConditionObj.id,'stb_stability_type_id')" id="stb_stability_type_id[[stabilityConditionObj.id]]" name="stb_stability_type_id[]" ng-model="orderAddStabilityPrototype.add_stb_stability_type_id[[stabilityConditionObj.id]]" ng-value="stabilityConditionObj.id">
                    </div>
                    <div class="pull-left width20" ng-bind="stabilityConditionObj.name"></div>
                    <div class="pull-left width35">
                        <input ng-show="allAddSelectedStabilityConditionArray.indexOf(stabilityConditionObj.id) > -1" type="text" valid-number class="form-control height30 font12 stb_sample_qty_class" oninput="this.style.borderColor='#ccc'" id="stb_sample_qty[[stabilityConditionObj.id]]" ng-model="orderAddStabilityPrototype.stb_sample_qty_[[stabilityConditionObj.id]]" name="stb_sample_qty['[[stabilityConditionObj.id]]']" placeholder="sample qty">
                    </div>
                    <div class="pull-left width35">
                        <input ng-show="allAddSelectedStabilityConditionArray.indexOf(stabilityConditionObj.id) > -1" type="text" class="form-control height30 font12 stb_condition_temp_class" oninput="this.style.borderColor='#ccc'" id="stb_condition_temperature[[stabilityConditionObj.id]]" ng-model="orderAddStabilityPrototype.stb_condition_temperature_[[stabilityConditionObj.id]]" name="stb_condition_temperature['[[stabilityConditionObj.id]]']" placeholder="Stb. Condition Temperature">
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <!--/Stability Condition-->
    
    <!--Test Product Paramenters List-->
    <div class="row mT20" ng-if="checkedAddStbCountForParamentersList && testProductParamentersList.length">
        <div class="headingGray font18"><strong class="text-left ng-binding">Tests Parameters Details<span ng-if="allAddDefaultTestParametersArray.length">([[allAddDefaultTestParametersArray.length]])</span></strong></div>
        <div class="col-xs-12">                    
            <div id="no-more-tables" class="col-xs-12 custom-scroll">
                <table border="1" class="col-sm-12 table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th class="width25">
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('test_parameter_name')">Parameters</label>
                                <span class="sortorder" ng-show="predicate === 'test_parameter_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width10">
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('equipment_name')">Equipment</label>
                                <span class="sortorder" ng-show="predicate === 'equipment_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width10">
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('method_name')">Method</label>
                                <span class="sortorder" ng-show="predicate === 'method_name'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width10">
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('requirement_from_to')">Requirement</label>
                                <span class="sortorder" ng-show="predicate === 'requirement_from_to'" ng-class="{reverse:reverse}"></span>
                            </th>
			    <th class="width10">
                                <label class="sortlabel capitalizeAll" ng-click="sortBy('stb_product_test_stf_id')">STF Selection</label>
                                <span class="sortorder" ng-show="predicate === 'stb_product_test_stf_id'" ng-class="{reverse:reverse}"></span>
                            </th>
                            <th class="width10" ng-repeat="stabilityConditionObj in stabilityConditionList track by $index" ng-if="allAddSelectedStabilityConditionArray.indexOf(stabilityConditionObj.id) !== -1">
                                <label class="sortlabel capitalizeAll">[[stabilityConditionObj.name]]</label>
                                <input type="checkbox" title="Select All" id="check_all_add_stb_condition_div_id[[stabilityConditionObj.id]]" ng-click="funCheckAllStabilityParameters(stabilityConditionObj.id,'check_all_add_stb_condition_div_id','parametersAddCheckBox');">
                            </th>
                        </tr>
                    </thead>
                    <tbody>									
                        <tr ng-repeat="categoryParameters in testProductParamentersList">
                            <td ng-show="categoryParameters.categoryParams.length" colspan="[[tableTrTdColspanI]]" data-title="Test Parameter Category Name">
                                <table border="1" class="col-sm-12 table-striped table-condensed">
                                    <thead><th align="left" colspan="[[tableTrTdColspanI]]">[[categoryParameters.categoryName]]</th></thead>
                                    <tbody class="searchAddParameterPopupFilter" ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">													
                                        <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="subCategoryParameters.description.length">
                                            <td class="width25" id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
                                            <td class="width33" colspan="3">[[subCategoryParameters.description]]</td>
					    <td class="width10" id="stb_product_test_stf_id_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Stability Test Format">
						<input type="checkbox" class="parametersAddSTFCheckBox[[subCategoryParameters.product_test_dtl_id]]" id="stb_product_test_stf_id[[subCategoryParameters.product_test_dtl_id]]" ng-checked="allAddDefaultTestProductSTFArray.indexOf(subCategoryParameters.product_test_dtl_id) > -1" ng-model="orderAddStabilityPrototype.stb_product_test_stf_id[[subCategoryParameters.product_test_dtl_id]]" name="stb_product_test_stf_id['[[subCategoryParameters.product_test_dtl_id]]']" ng-value="1">
					    </td>
                                            <td class="width10" ng-repeat="stabilityConditionObj in stabilityConditionList track by $index" ng-if="allAddSelectedStabilityConditionArray.indexOf(stabilityConditionObj.id) !== -1">
                                                <input type="checkbox" ng-click="funAllAddDefaultTestParametersDetail(subCategoryParameters.product_test_dtl_id,stabilityConditionObj.id,'product_test_dtl_id');" class="parametersAddCheckBox[[stabilityConditionObj.id]]" id="product_test_dtl_id_[[subCategoryParameters.product_test_dtl_id]]_[[stabilityConditionObj.id]]" ng-checked="allAddDefaultTestParametersArray.indexOf(subCategoryParameters.product_test_dtl_id+'-'+stabilityConditionObj.id) > -1" ng-model="orderAddStabilityPrototype.product_test_dtl_id[[subCategoryParameters.product_test_dtl_id]][[stabilityConditionObj.id]]" name="product_test_dtl_id['[[stabilityConditionObj.id]]'][]" ng-value="subCategoryParameters.product_test_dtl_id">
                                            </td>
                                        </tr>
                                        <tr id="alternative_paramter[[subCategoryParameters.product_test_dtl_id]]" ng-if="!subCategoryParameters.description.length">
                                            <td class="width25" id="test_parameter_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Test Parameter Name"><span ng-bind-html="subCategoryParameters.test_parameter_name"></span></td>
                                            <td class="width10" id="equipment_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Equipment Name">[[subCategoryParameters.equipment_name]]</td>
                                            <td class="width10" id="method_name_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Method Name">[[subCategoryParameters.method_name]]</td>
                                            <td class="width10" id="requirement_from_to_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Requirement">[[subCategoryParameters.requirement_from_to]]</td>
					    <td class="width10" id="stb_product_test_stf_id_text[[subCategoryParameters.product_test_dtl_id]]" data-title="Stability Test Format">
						<input type="checkbox" class="parametersAddSTFCheckBox[[subCategoryParameters.product_test_dtl_id]]" id="stb_product_test_stf_id[[subCategoryParameters.product_test_dtl_id]]" ng-checked="allAddDefaultTestProductSTFArray.indexOf(subCategoryParameters.product_test_dtl_id) > -1" ng-model="orderAddStabilityPrototype.stb_product_test_stf_id[[subCategoryParameters.product_test_dtl_id]]" name="stb_product_test_stf_id['[[subCategoryParameters.product_test_dtl_id]]']" ng-value="1">
					    </td>
                                            <td class="width10" ng-repeat="stabilityConditionObj in stabilityConditionList track by $index" ng-if="allAddSelectedStabilityConditionArray.indexOf(stabilityConditionObj.id) !== -1">
                                                <input type="checkbox" ng-click="funAllAddDefaultTestParametersDetail(subCategoryParameters.product_test_dtl_id,stabilityConditionObj.id,'product_test_dtl_id');" class="parametersAddCheckBox[[stabilityConditionObj.id]]" id="product_test_dtl_id_[[subCategoryParameters.product_test_dtl_id]]_[[stabilityConditionObj.id]]" ng-checked="allAddDefaultTestParametersArray.indexOf(subCategoryParameters.product_test_dtl_id+'-'+stabilityConditionObj.id) > -1" ng-model="orderAddStabilityPrototype.product_test_dtl_id[[subCategoryParameters.product_test_dtl_id]][[stabilityConditionObj.id]]" name="product_test_dtl_id['[[stabilityConditionObj.id]]'][]" ng-value="subCategoryParameters.product_test_dtl_id">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>											
                        </tr>											
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Test Product Paramenters List-->
    
</div>