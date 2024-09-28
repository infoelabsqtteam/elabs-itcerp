<div id="editCountryStateViewPopup" data-backdrop="static" class="modal fade" role="dialog">
	  <div class="modal-dialog mT25">
		    <div class="modal-content">
			      <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
			      </div>
			      <div class="modal-body">
			      
					<div class="row mT15 mB10">
						  
						  <!--Country-->
						  <div class="col-xs-6 form-group">
							    <label for="country_id">Select Country<em class="asteriskRed">*</em></label>
							    <select
								      class="form-control"
								      name="country_id"
								      ng-model="select_country_id"
								      id="country_id"
								      ng-change="funGetStateOnCountryChange(select_country_id.id)"
								      ng-options="item.name for item in countryCodeList track by item.id">
								      <option value="">Select Country</option>
							    </select>
						  </div>
						  <!--/Country-->
					
						  <div class="col-xs-6" ng-if="selectedModuleID == 1">
							    <label for="state_id">Select State<em class="asteriskRed">*</em></label>
							    <select
								      class="form-control"
								      name="state_id"
								      ng-model="select_state_id"
								      id="state_id"
								      ng-change="funGetSelectedReportingToStateId(select_state_id.id)"
								      ng-options="item.name for item in countryStatesList track by item.id ">
								      <option value="">Select State</option>
							    </select>
						  </div>
						  <div class="col-xs-6" ng-if="selectedModuleID == 2">
							    <label for="state_id">Select State<em class="asteriskRed">*</em></label>
							    <select
								      class="form-control"
								      name="state_id"
								      ng-model="select_state_id"
								      id="state_id"
								      ng-change="funGetSelectedInvoicingToStateId(select_state_id.id)"
								      ng-options="item.name for item in countryStatesList track by item.id ">
								      <option value="">Select State</option>
							    </select>
						  </div>
					</div>
			      </div>
		    </div>
	</div>
</div>