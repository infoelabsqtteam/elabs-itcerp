<div class="row" ng-hide="editMasterFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row header1">
                <strong class="pull-left headerText">Edit Instance : [[editMasterModel.instance_code]]</strong>
            </div>

            <form name='erpEditMasterForm' id="erpEditMasterForm" novalidate>

                <div class="row">

                    <!--Instance Code-->
                    <div class="col-xs-2">
                        <label for="default_instance_code">Instance Code<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" readonly ng-model="editMasterModel.instance_code" ng-value="default_instance_code" name="instance_code" id="edit_instance_code" placeholder="Instance Code" />
                        <span ng-messages="erpEditMasterForm.instance_code.$error" ng-if='erpEditMasterForm.instance_code.$dirty  || erpEditMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Instance code is required</span>
                        </span>
                    </div>
                    <!--/Instance Code-->

                    <!--Instance Name-->
                    <div class="col-xs-2">
                        <label for="instance_name">Instance Name<em class="asteriskRed">*</em></label>
                        <input type="text" class="form-control" ng-model="editMasterModel.instance_name" ng-change="editMasterModel.instance_desc=editMasterModel.instance_name" name="instance_name" id="edit_instance_name" ng-required='true' placeholder="Instance Name" />
                        <span ng-messages="erpEditMasterForm.instance_name.$error" ng-if='erpEditMasterForm.instance_name.$dirty || erpEditMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Instance name is required</span>
                        </span>
                    </div>
                    <!--/Instance Name-->

                    <!--Instance Description-->
                    <div class="col-xs-2">
                        <label for="instance_desc">Instance Description<em class="asteriskRed">*</em></label>
                        <textarea rows=1 type="text" class="form-control" ng-model="editMasterModel.instance_desc" name="instance_desc" id="edit_instance_desc" ng-required='true' placeholder="Instance Description" />
                        </textarea>
                        <span ng-messages="erpEditMasterForm.instance_desc.$error" ng-if='erpEditMasterForm.instance_desc.$dirty  || erpEditMasterForm.$submitted' role="alert">
                            <span ng-message="required" class="error">Instance description is required</span>
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
                        <input type="hidden" name="instance_id" ng-value="editMasterModel.instance_id" ng-model="editMasterModel.instance_id">
                        <button type="submit" title="Update" ng-disabled="erpEditMasterForm.$invalid" class='mT26 btn btn-primary  btn-sm' ng-click='funUpdateMaster()'>Update</button>
                        <button title="Back" type='button' class='mT26 btn btn-default btn-sm' ng-click='navigatePage()'>Back</button>
                    </div>
                    <!--/Update button-->
                </div>
				
            </form>

        </div>
    </div>
</div>
