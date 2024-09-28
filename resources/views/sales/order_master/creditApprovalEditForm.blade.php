<div class="col-xs-12">
    <div class="col-xs-4 form-group">
        <label class="checkbox-inline" for="client_approval_needed">
            <input 
                type="checkbox"
                id="client_approval_needed_edit_id" 
                name="client_approval_needed"
                ng-model="updateOrder.client_approval_needed"
                value="1"
                ng-click="funEditShowHideClientApprovalNeeded(updateOrder.clientApprovalList)">
                <strong>Client Approval Detail</strong>
        </label>
    </div>
    <div class="col-xs-8 form-inline mB20 pL30" ng-if="isClientApprovalNeededEditFlag">
        <div class="form-group">
            <input 
                type="text" 
                ng-if="isRoleAdminForClientApprovalEdit"
                id="ocad_approved_by_edit" 
                name="ocad_approved_by" 
                class="form-control"
                ng-model="updateOrder.ocad_approved_by"
                ng-required="true"
                ng-class="erpUpdateOrderForm.ocad_approved_by.$touched && erpUpdateOrderForm.ocad_approved_by.$invalid ? 'border-danger' : ''"
                placeholder="Approved By(*)">
            <input 
                type="text" 
                ng-if="!isRoleAdminForClientApprovalEdit"
                readonly
                id="ocad_approved_by_edit" 
                name="ocad_approved_by" 
                class="form-control"
                ng-model="updateOrder.ocad_approved_by"
                ng-required="true"
                ng-class="erpUpdateOrderForm.ocad_approved_by.$touched && erpUpdateOrderForm.ocad_approved_by.$invalid ? 'border-danger' : ''"
                placeholder="Approved By(*)">
        </div>
        <div class="form-group">
            <div ng-if="isRoleAdminForClientApprovalEdit" class="input-group date">
                <input
                    type="text"
                    id="ocad_date_edit"
                    ng-model="updateOrder.ocad_date"
                    name="ocad_date"
                    ng-required="true"
                    datepicker
                    readonly
                    class="form-control"
                    ng-class="erpUpdateOrderForm.ocad_date.$touched && erpUpdateOrderForm.ocad_date.$invalid ? 'border-danger' : ''"
                    placeholder="Date(*)">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
            </div>
            <div ng-if="!isRoleAdminForClientApprovalEdit" class="input-group date">
                <input
                    type="text"
                    id="ocad_date_edit"
                    ng-model="updateOrder.ocad_date"
                    name="ocad_date"
                    readonly
                    class="form-control"
                    ng-class="erpUpdateOrderForm.ocad_date.$touched && erpUpdateOrderForm.ocad_date.$invalid ? 'border-danger' : ''"
                    placeholder="Date(*)">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
            </div>
        </div>

        <div class="form-group">
            <input 
                type="text" 
                ng-if="isRoleAdminForClientApprovalEdit"
                id="ocad_credit_period_edit" 
                name="ocad_credit_period" 
                class="form-control" 
                valid-number
                ng-model="updateOrder.ocad_credit_period"
                ng-class="erpUpdateOrderForm.ocad_credit_period.$touched && erpUpdateOrderForm.ocad_credit_period.$invalid ? 'border-danger' : ''"
                ng-required="true"
                placeholder="Credit Period(*)">
            <input 
                type="text" 
                ng-if="!isRoleAdminForClientApprovalEdit"
                readonly
                id="ocad_credit_period_edit" 
                name="ocad_credit_period" 
                class="form-control" 
                valid-number
                ng-model="updateOrder.ocad_credit_period"
                ng-class="erpUpdateOrderForm.ocad_credit_period.$touched && erpUpdateOrderForm.ocad_credit_period.$invalid ? 'border-danger' : ''"
                ng-required="true"
                placeholder="Credit Period(*)">
        </div>

        <div class="form-group">
            <div ng-if="isRoleAdminForClientApprovalEdit" class="input-group date">
                <input
                    type="text"
                    id="ocad_date_upto_amt_edit"
                    ng-model="updateOrder.ocad_date_upto_amt"
                    name="ocad_date_upto_amt"
                    ng-required="true"
                    datepicker
                    readonly
                    class="form-control"
                    ng-class="erpUpdateOrderForm.ocad_date_upto_amt.$invalid ? 'border-danger' : ''"
                    placeholder="Date Upto Amount(*)">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
            </div>
            <div ng-if="!isRoleAdminForClientApprovalEdit" class="input-group date">
                <input
                    type="text"
                    id="ocad_date_upto_amt_edit_not"
                    ng-model="updateOrder.ocad_date_upto_amt"
                    name="ocad_date_upto_amt"
                    readonly
                    class="form-control"
                    ng-class="erpUpdateOrderForm.ocad_date_upto_amt.$invalid ? 'border-danger' : ''"
                    placeholder="Date Upto Amount(*)">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
            </div>
        </div>

    </div>
</div>