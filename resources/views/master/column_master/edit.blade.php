<div class="row" ng-hide="editMasterFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row header1">
                <strong class="pull-left headerText">Edit Column : [[editMasterModel.column_code]]</strong>
            </div>

            <form name='erpEditMasterForm' id="erpEditMasterForm" novalidate>

                <div class="row">

                    <!--Column Code-->
                    <div class="col-xs-2">
                        <label for="default_column_code">Column Code<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" readonly ng-model="editMasterModel.column_code" ng-value="default_column_code" name="column_code" id="edit_column_code" placeholder="Column Code" />
                        <span ng-messages="erpEditMasterForm.column_code.$error" ng-if='erpEditMasterForm.column_code.$dirty  || erpEditMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Column code is required</span>
                        </span>
                    </div>
                    <!--/Column Code-->

                    <!--Column Name-->
                    <div class="col-xs-2">
                        <label for="column_name">Column Name<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" ng-model="editMasterModel.column_name" ng-change="editMasterModel.column_desc=editMasterModel.column_name" name="column_name" id="edit_column_name" ng-required='true' placeholder="Column Name" />
                        <span ng-messages="erpEditMasterForm.column_name.$error" ng-if='erpEditMasterForm.column_name.$dirty || erpEditMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Column name is required</span>
                        </span>
                    </div>
                    <!--/Column Name-->

                    <!--Column Description-->
                    <div class="col-xs-2">
                        <label for="column_desc">Column Description<em class="asteriskRed">*</em></label>
                        <textarea rows=1 type="text" class="form-control" ng-model="editMasterModel.column_desc" name="column_desc" id="edit_column_desc" ng-required='true' placeholder="Column Description" />
                        </textarea>
                        <span ng-messages="erpEditMasterForm.column_desc.$error" ng-if='erpEditMasterForm.column_desc.$dirty  || erpEditMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Column description is required</span>
                        </span>
                    </div>
                    <!--/Master Description-->

                    <!--Equipment Type-->
                    <div class="col-xs-2">
                        <label for="equipment_type_id" class="outer-lable">Equipment Type<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="equipment_type_id" ng-model="editMasterModel.equipment_type_id" id="edit_equipment_type_id" ng-required='true' ng-options="item.name for item in equipmentTypesDropdownList track by item.id">
                            <option value="">Select Equipment Type</option>
                        </select>
                        <span ng-messages="erpEditMasterForm.equipment_type_id.$error" ng-if='erpEditMasterForm.equipment_type_id.$dirty || erpEditMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Select Equipment Type</span>
                        </span>
                    </div>
                    <!--/Equipment Type-->

                    <!--Parent Product Category-->
                    <div class="col-xs-2">
                        <label for="product_category_id" class="outer-lable">Product Section<em class="asteriskRed">*</em></label>
                        <select class="form-control" name="product_category_id" id="edit_product_category_id" ng-model="editMasterModel.product_category_id" ng-options="item.name for item in parentCategoryDropdownList track by item.id" ng-required='true'>
                            <option value="">Select Product Section</option>
                        </select>
                        <span ng-messages="erpEditMasterForm.product_category_id.$error" ng-if='erpEditMasterForm.product_category_id.$dirty || erpEditMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Parent Category is required</span>
                        </span>
                    </div>
                    <!--/Parent Product Category-->

                    <!--Update button-->
                    <div class="col-xs-2 pull-right">
                        <label for="submit">{{ csrf_field() }}</label>
                        <input type="hidden" name="column_id" ng-value="editMasterModel.column_id" ng-model="editMasterModel.column_id">
                        <button type="submit" title="Update" ng-disabled="erpEditMasterForm.$invalid" class='mT26 btn btn-primary  btn-sm' ng-click='funUpdateMaster()'>Update</button>
                        <button title="Back" type='button' class='mT26 btn btn-default btn-sm' ng-click='navigatePage()'>Back</button>
                    </div>
                    <!--/Update button-->
                </div>
				
            </form>

        </div>
    </div>
</div>
