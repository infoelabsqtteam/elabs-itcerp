<div class="row" ng-hide="addMasterFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row header1">
                <span><strong class="pull-left headerText">Add Column</strong></span>
            </div>

            <!--Add method form-->
            <form name='erpAddMasterForm' id="erpAddMasterForm" novalidate>

                <div class="row">

                    <!--Column Code-->
                    <div class="col-xs-2">
                        <span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
                        <label for="method_code">Column Code<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" readonly ng-model="addMasterModel.column_code" ng-value="default_column_code" name="column_code" id="column_code" placeholder="Column Code" />
                        <span ng-messages="erpAddMasterForm.column_code.$error" ng-if='erpAddMasterForm.column_code.$dirty  || erpAddMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Column code is required</span>
                        </span>
                    </div>
                    <!--/Column Code-->

                    <!--Column Name-->
                    <div class="col-xs-2">
                        <label for="method_name">Column Name<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" ng-model="addMasterModel.column_name" ng-change="addMasterModel.column_desc=addMasterModel.column_name" name="column_name" id="column_name" ng-required='true' placeholder="Column Name" />
                        <span ng-messages="erpAddMasterForm.column_name.$error" ng-if='erpAddMasterForm.column_name.$dirty || erpAddMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Column name is required</span>
                        </span>
                    </div>
                    <!--/Column Name-->

                    <!--Column Description-->
                    <div class="col-xs-2">
                        <label for="method_desc">Column Description<em class="asteriskRed">*</em></label>
                        <textarea rows=1 type="text" class="form-control" ng-model="addMasterModel.column_desc" name="column_desc" id="column_desc" ng-required='true' placeholder="Column Description" />
                        </textarea>
                        <span ng-messages="erpAddMasterForm.column_desc.$error" ng-if='erpAddMasterForm.column_desc.$dirty  || erpAddMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Column description is required</span>
                        </span>
                    </div>
                    <!--/Master Description-->

                    <!--Equipment Type-->
                    <div class="col-xs-2">
                        <label for="equipment_type_id" class="outer-lable">Equipment Type<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="equipment_type_id" ng-model="addMasterModel.equipment_type_id" id="equipment_type_id" ng-required='true' ng-options="item.name for item in equipmentTypesDropdownList track by item.id">
                            <option value="">Select Equipment Type</option>
                        </select>
                        <span ng-messages="erpAddMasterForm.equipment_type_id.$error" ng-if='erpAddMasterForm.equipment_type_id.$dirty || erpAddMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Select Equipment Type</span>
                        </span>
                    </div>
                    <!--/Equipment Type-->

                    <!--Parent Product Category-->
                    <div class="col-xs-2">
                        <label for="product_category_id" class="outer-lable">Product Section<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="product_category_id" id="product_category_id" ng-model="addMasterModel.product_category_id" ng-options="item.name for item in parentCategoryDropdownList track by item.id" ng-required='true'>
                            <option value="">Select Product Section</option>
                        </select>
                        <span ng-messages="erpAddMasterForm.product_category_id.$error" ng-if='erpAddMasterForm.product_category_id.$dirty || erpAddMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Parent Category is required</span>
                        </span>
                    </div>
                    <!--/Parent Product Category-->

                    <!--save button-->
                    <div class="col-xs-2 pull-right">
                        <label for="submit">{{ csrf_field() }}</label>
                        <button title="Save" ng-disabled="erpAddMasterForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funAddMaster()'>Save</button>
                        <button title="Reset" type="button" class="mT26 btn btn-default" ng-click="resetButton()" data-dismiss="modal">Reset</button>
                    </div>
                    <!--/save button-->
					
                </div>

            </form>
            <!--Add method form-->

        </div>
    </div>
</div>
