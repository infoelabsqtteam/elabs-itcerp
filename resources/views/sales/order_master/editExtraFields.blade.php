<div class="row order-section"> Extra Fields <span class="txt-right"><a href="javascript:;" title="Add field" class="btn btn-primary btn-sm" ng-click="funAddNewFieldColumn()"><i class="fa fa-plus-square" aria-hidden="true"></i></a></span></div>
<div class="order_detail mT10">
    <div class="row" ng-repeat="dynamicColumnName in dynamicColumnNameList track by $index">
        <div class="col-xs-4 form-group">
            <label for="label_name_[[$index+1]]">Field Name [[$index+1]]<em class="asteriskRed">*</em></label>
            <select 
                class="form-control"
                id="edit_order_field_name_[[$index]]"
                ng-model="order_field_name[[$index]]"
                name="order_field_name[]"
                ng-options="item.name for item in dynamicFieldsList track by item.name">
                <option>Select Field Name</option>
            </select>
        </div>
        <div class="col-xs-4 form-group">
            <label for="label_name_[[$index+1]]">Field Value [[$index+1]]<em class="asteriskRed">*</em></label>
            <input 
                type="text"
                class="form-control"
                id="edit_order_field_value_[[$index]]"
                ng-model="dynamicColumnName.order_field_value"
                name="order_field_value[]"
                placeholder="Field Value [[$index+1]]">
        </div>
        <div class="col-xs-4 form-group mT25">
            <a href="javascript:;" title="Remove Field" class="btn btn-danger btn-sm" ng-click="funRemoveNewFieldColumn($index,dynamicColumnName.odf_id)"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
