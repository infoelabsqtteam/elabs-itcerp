<div class="modal fade" data-backdrop="static" data-keyboard="false" id="erpCustomerOnHoldPopupDivId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
	<div class="modal-dialog">
		<div class="row modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-left"><span ng-click="funGetHoldCustomerDtl(customerOnHold.chd_customer_id)">Hold Detail : <span ng-if="customerOnHold.chd_customer_name" ng-bind="customerOnHold.chd_customer_name"></span></span></h4>
			</div>
		
			<div id="modal-body-diol" class="modal-body">
				
				<!--Alert Message Popup-->
				@include('includes.alertMessagePopup')
				<!--/Alert Message Popup-->
				
				<!--Hold Form--> 
				<div class="row">
					<form method="POST" role="form" id="erpHoldCustomerPopupForm" name="erpHoldCustomerPopupForm" novalidate>	
						<!--Hold Description-->
						<div class="col-xs-12 form-group">
							<label for="chd_hold_description">Hold Description</label>
							<textarea
								class="form-control"
								id="chd_hold_description"
								ng-model="customerOnHold.chd_hold_description"
								name="chd_hold_description"
								ng-required="true"
								placeholder="Hold Description">
							</textarea>
							<span ng-messages="erpHoldCustomerPopupForm.chd_hold_description.$error" ng-if="erpHoldCustomerPopupForm.chd_hold_description.$dirty || erpHoldCustomerPopupForm.$submitted" role="alert">
								<span ng-message="required" class="error">Hold Description is required</span>
							</span>
						</div>
						<!--/Hold Description-->
						
						<!-- Hold date-->
						<div class="col-xs-5 form-group">
							<label for="chd_hold_date">Hold Date<em class="asteriskRed">*</em></label>
							<div class="input-group date" data-provide="datepicker">
								<input readonly
									type="text"
									id="chd_hold_date"
									ng-model="customerOnHold.chd_hold_date"
									ng-required="true"
									name="chd_hold_date"
									class="form-control bgWhite"
									placeholder="Hold Date">
								<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
							</div>   
							<span ng-messages="erpHoldCustomerPopupForm.chd_hold_date.$error" ng-if="erpHoldCustomerPopupForm.chd_hold_date.$dirty  || erpHoldCustomerPopupForm.$submitted" role="alert">
								<span ng-message="required" class="error">Hold date is required</span>
							</span>
						</div>
						<!-- /Hold date-->
						
						<!-- Hold By-->
						<div class="col-xs-5 form-group">
							<label for="chd_hold_by">Hold By<em class="asteriskRed">*</em></label>
							<input
								type="text"
								class="form-control"
								id="chd_hold_by"
								name="chd_hold_by"
								ng-model="customerOnHold.chd_hold_by"
								ng-required="true"
								placeholder="Hold By">
							<span ng-messages="erpHoldCustomerPopupForm.chd_hold_by.$error" ng-if="erpHoldCustomerPopupForm.chd_hold_by.$dirty || erpHoldCustomerPopupForm.$submitted" role="alert">
								<span ng-message="required" class="error">Hold By is required</span>
							</span>
						</div>
						<!-- /Hold By-->
						
						<!--Save Button-->
						<div class="col-xs-2 form-group mT25">
							<label for="submit">{{ csrf_field() }}</label>
							<input type="hidden" ng-value="customerOnHold.chd_customer_id" name="chd_customer_id" ng-model="customerOnHold.chd_customer_id">
							<input type="hidden" ng-value="3" name="chd_customer_status" ng-model="customerOnHold.chd_customer_status">
							<input ng-if="{{ (defined('VIEW') && VIEW) && ((defined('IS_ADMIN') && IS_ADMIN) || (defined('IS_CRM') && IS_CRM)) }}" type="submit" ng-disabled="erpHoldCustomerPopupForm.$invalid" value="Save" ng-click="funHoldCustomer()" class="btn btn-primary">
						</div>
						<!--/Save Button-->
					</form>
				</div>
				<!--/Hold Form--> 
				
				<!--Hold Detail--> 
				<div class="mT20" ng-if="customerOnHoldList.length">
					<table class="col-sm-12 table-striped table-condensed cf font13">
						<thead class="cf">
							<tr>                            
								<th><label class="sortlabel">S.No.</label></th>
								<th><label class="sortlabel">Hold Description</label></th>
								<th><label class="sortlabel">Hold Date</label></th>
								<th><label class="sortlabel">Hold By</label></th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="customerOnHoldObj in customerOnHoldList | orderBy:predicate:reverse track by $index" ng-if="$index < 1">
								<td data-title="S.No." class="ng-binding">[[$index + 1]]</td>
								<td data-title="Hold Description" class="ng-binding">[[customerOnHoldObj.chd_hold_description]]</td>
								<td data-title="Hold Date" class="ng-binding">[[customerOnHoldObj.chd_hold_date]]</td>
								<td data-title="Hold By" class="ng-binding">[[customerOnHoldObj.chd_hold_by]]</td>     
							</tr>
							<tr ng-if="!customerOnHoldList.length"><td colspan="4">No record found.</td></tr>
						</tbody>							
					</table>
				</div>
				<!--/Hold Detail-->
			</div>
			<div class="modal-footer mT10">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>						
			</div>
		</div>
	</div>
</div>