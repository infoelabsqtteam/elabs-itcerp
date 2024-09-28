<div class="col-xs-12">
    <div class="col-xs-4 form-group">
        <label class="checkbox-inline" for="client_approval_needed">
            <input 
                type="checkbox"
                id="client_approval_needed_add_id" 
                name="client_approval_needed"
                ng-model="orderSample.client_approval_needed"
                value="1"
                ng-click="funAddShowHideClientApprovalNeeded()">
                <strong>Client Approval Detail</strong>
        </label>
    </div>
    <div class="col-xs-8 form-inline mB20 pL30" ng-if="isClientApprovalNeededAddFlag">
        <div class="form-group">
            <input 
                type="text" 
                id="ocad_approved_by_add" 
                name="ocad_approved_by" 
                class="form-control"
                ng-model="orderSample.ocad_approved_by"
                ng-required="true"
                ng-class="erpCreateOrderForm.ocad_approved_by.$touched && erpCreateOrderForm.ocad_approved_by.$invalid ? 'border-danger' : ''"
                placeholder="Approved By(*)">
        </div>
        <div class="form-group">
            <div class="input-group date">
                <input
                    type="text"
                    id="ocad_date_add"
                    ng-model="orderSample.ocad_date"
                    name="ocad_date"
                    datepicker
                    readonly
                    ng-required="true"
                    class="form-control"
                    ng-class="erpCreateOrderForm.ocad_date.$touched && erpCreateOrderForm.ocad_date.$invalid ? 'border-danger' : ''"
                    placeholder="Date(*)">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
		    </div>
        </div>
        <div class="form-group">
            <input 
                type="text" 
                id="ocad_credit_period_add" 
                name="ocad_credit_period" 
                class="form-control" 
                valid-number
                ng-model="orderSample.ocad_credit_period"
                ng-class="erpCreateOrderForm.ocad_credit_period.$touched && erpCreateOrderForm.ocad_credit_period.$invalid ? 'border-danger' : ''"
                ng-required="true"
                placeholder="Credit Period(*)">
        </div>
        <div class="form-group">
            <div class="input-group date">
                <input
                    type="text"
                    id="ocad_date_upto_amt_add"
                    datepicker
                    readonly
                    ng-model="orderSample.ocad_date_upto_amt"
                    name="ocad_date_upto_amt"
                    ng-required="true"
                    class="form-control"
                    ng-class="erpCreateOrderForm.ocad_date_upto_amt.$touched && erpCreateOrderForm.ocad_date_upto_amt.$invalid ? 'border-danger' : ''"
                    placeholder="Date Upto Amount(*)">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
		    </div>   
        </div>
    </div>
</div>