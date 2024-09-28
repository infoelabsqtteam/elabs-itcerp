<div class="row" ng-hide="addMasterFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row header1">
                <span><strong class="pull-left headerText">Add Analyst Window Settings</strong></span>
                <span><a title="Back" class="btn btn-primary pull-right mT3 mR5" ng-click="backButton()">Back</a></span>
            </div>

            <!--Add form-->
            <form name='erpAddMasterForm' id="erpAddMasterForm" novalidate>
                <div class="row">

                    <!--Division Name-->
                    <div class="col-xs-6">
                        <label for="oaws_division_id" class="outer-lable">Division Name<em class="asteriskRed">*</em></label>
                        <select 
                            class="form-control" 
                            name="oaws_division_id" 
                            id="oaws_division_id" 
                            ng-model="addMasterModel.oaws_division_id"
                            ng-change="funGetEmployeeBranchWiseList(addMasterModel.oaws_division_id.id)"
                            ng-options="item.name for item in divisionDropdownList track by item.id" 
                            ng-required='true'>
                            <option value="">Select Division Name</option>
                        </select>
                        <span ng-messages="erpAddMasterForm.oaws_division_id.$error" ng-if='erpAddMasterForm.oaws_division_id.$dirty || erpAddMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Division Name is required</span>
                        </span>
                    </div>
                    <!--/Division Name-->

                    <!--Department Name-->
                    <div class="col-xs-6">
                        <label for="oaws_product_category_id" class="outer-lable">Department Name<em class="asteriskRed">*</em></label>
                        <select 
                            class="form-control"
                            name="oaws_product_category_id" 
                            id="oaws_product_category_id" 
                            ng-model="addMasterModel.oaws_product_category_id"
                            ng-options="item.name for item in parentCategoryDropdownList track by item.id" 
                            ng-required='true'>
                            <option value="">Select Department Name</option>
                        </select>
                        <span ng-messages="erpAddMasterForm.oaws_product_category_id.$error" ng-if='erpAddMasterForm.oaws_product_category_id.$dirty || erpAddMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Department Name is required</span>
                        </span>
                    </div>
                    <!--/Department Name-->

                </div>

                <div class="row mT30" ng-if="equipmentCheckboxList.length">
                    <div class="col-xs-12 form-group">
                        <label for="oaws_equipment_type_id" class="outer-lable">Choose Equipments<em class="asteriskRed">*</em></label>
                        <div class="col-xs-12 custom-scroll">
                            <div class="col-xs-4" ng-repeat="equipment in equipmentCheckboxList">
                                <input 
                                    type="checkbox" 
                                    ng-model="addMasterModel.oaws_equipment_type_id[[equipment.id]]" 
                                    name="oaws_equipment_type_id[]" 
                                    id="oaws_equipment_type_id_[[equipment.id]]"
                                    value="[[equipment.id]]">
                                    &nbsp;&nbsp;
                                    <label class="text-overflow" title="[[equipment.name]]" for="text1">[[equipment.name]]</label>
                            </div>
                            <span ng-messages="erpAddMasterForm.oaws_equipment_type_id.$error" ng-if='erpAddMasterForm.oaws_equipment_type_id.$dirty || erpAddMasterForm.$submitted' role="alert">
                                <span ng-message="required" class="error">Test standard name is required</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!--save button-->
                <div class="row pull-right mT10 mR10">
                    <label for="submit">{{ csrf_field() }}</label>
                    <button title="Save" ng-disabled="erpAddMasterForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary btn-sm' ng-click='funAddMaster()'>Save</button>
                    <button title="Reset" type="button" class="mT26 btn btn-default btn-sm" ng-click="resetButton()" data-dismiss="modal">Reset</button>
                </div>
                <!--/save button-->

            </form>
            <!--Add form-->

        </div>
    </div>
</div>
