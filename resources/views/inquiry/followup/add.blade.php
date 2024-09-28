		  
		<!-- ************************************** Start followup Modal **************************** -->
		 <div class="modal fade" id="add_followup" role="dialog">
			<div class="modal-dialog">
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title text-center">Add Follow-up</h4>
				</div>	
				<div class="modal-body">
				  <div ng-hide="IsVisiableErrorMsgPopup_edit" role="alert" class="alert alert-danger">
			         [[errorMessagePopup_edit]]<a ng-click="hideAlertMsgPopup()" href="" class="closeAlert" aria-label="close">×</a>
				  </div>
				  <form name='followupForm' id="add_folloup_form" novalidate>			  
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="row">
						<div class="col-xs-12">
							<label for="followup_by">Follow-up By <span class="asteriskRed">*</span>:</label>
							<select class="form-control" name="followup_by"
									ng-model="followup_by" 
									ng-required='true' id="followup_by"
									ng-options="item.name for item in employeeList track by item.id ">
								<option value=""> Select Employee </option>
							</select>
							<span ng-messages="followupForm.followup_by.$error" 
							 ng-if='followupForm.followup_by.$dirty  || followupForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please select Employee</span>
							</span>
						</div>
						<div class="col-xs-12">
							<label for="folloup_mode">Mode <span class="asteriskRed">*</span>:</label>
							<select  class="form-control" ng-required='true'  
							         name="mode" id="folloup_mode" 
							  ng-options="option.name for option in followupsMode.followupdsModeTypeOptions track by option.id"
							  ng-model="followupsMode.selectedOption"><option value="">Select Mode</option></select>
							<span ng-messages="followupForm.mode.$error" 
							 ng-if='followupForm.mode.$dirty  || followupForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please select mode</span>
							</span>
						</div>						
						<div class="col-xs-12">
							<label>Previous Follow-up Date</label>
							<div class="form-group">
								<input type="text"  readonly   
									   class=" form-control"
									   ng-value="previousData.next_followup_date" 
									   ng-bind="previous_followup_date"/>
							</div>
						</div>						
						<div class="col-xs-12">
							<label>Next Follow-up Date<span class="asteriskRed">*</span>:</label>
							<div class="input-group date" data-provide="datepicker">
								<input type="text" class="backWhite form-control" 
									ng-model="followup_date" readonly
									name="followup_date" 
									id="followup_date"
									ng-required='true'
									placeholder="" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<span ng-messages="followupForm.followup_date.$error" 
							 ng-if='followupForm.followup_date.$dirty  || followupForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please add the next Follow-up date</span>
							</span>
						</div>
						<div class="col-xs-12">
							<label>Previous Follow-up Detail<span class="asteriskRed">*</span>:</label>
							<textarea readonly class=" form-control" 
									ng-model="previousData.followup_detail" 
									placeholder="Previous Followup Detail" > </textarea>
						</div>
						<div class="col-xs-12">
							<label>Follow-up Detail<span class="asteriskRed">*</span>:</label>
							<textarea class="form-control" 
									ng-model="followup_detail"
									name="followup_detail" 
									id="followup_detail" 
									ng-required='true'
									placeholder="Followup Detail" > </textarea>
							<span ng-messages="followupForm.followup_detail.$error" 
							 ng-if='followupForm.followup_detail.$dirty  || followupForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please add the next Follow-up date</span>
							</span>
						</div>
					<div class="form-group">
						<br/>
						<div class="col-sm-12">
							<div class="mT20 pull-right">
									<input type="hidden" ng-value="currentInquiryID" ng-bind="currentInquiryID" name="followupInquiryId">
									<button type='submit' id='action_button' class='btn btn-primary' ng-click='addFollowup(currentInquiryID,currentInquiryNumber,currentInquiryStatus)' > Save</button>
									<button title="Close"  type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
					</form>
				</div>
			  </div>		
			</div>
		  </div>
        </div>
		<!-- ************************************** end followup Modal **************************** -->