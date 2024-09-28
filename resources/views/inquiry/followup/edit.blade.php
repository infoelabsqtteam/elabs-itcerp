<!-- **************************************edit Start customer Modal **************************** -->
		<div class="modal fade" id="edit_followup" role="dialog">
			<div class="modal-dialog">
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title text-center">Edit Followup</h4>
				</div>
				<div class="modal-body">
				 <div ng-hide="IsVisiableErrorMsgPopup_edit" role="alert" class="alert alert-danger">
			         [[errorMessagePopup_edit]]<a ng-click="hideAlertMsgPopup()" href="" class="closeAlert" aria-label="close">×</a>
				  </div>
				<form name='followupEditForm' id="add_folloup_form" novalidate>			  
					<div class="row">
						<div class="col-xs-12">
							<label for="followup_by">Follow-up By <span class="asteriskRed">*</span>:</label>
							<input class="form-control" name="followup_by"
									ng-model="followUp.employeename" readonly
									 id="followup_by">
							<span ng-messages="followupEditForm.followup_by.$error" 
							ng-if='followupEditForm.followup_by.$dirty  || followupEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please select customer first</span>
							</span>
						</div>
						<div class="col-xs-12">
							<label for="folloup_mode">Mode <span class="asteriskRed">*</span>:</label>
							<input  class="backWhite form-control" 
							         id="folloup_mode" readonly
							  ng-model="followUp.mode">
							<span ng-messages="followupEditForm.folloup_mode.$error" 
							 ng-if='followupEditForm.folloup_mode.$dirty  || followupEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please select mode type first</span>
							</span>
						</div>
						<div class="col-xs-12">
							<label>Next Follow-up Date<span class="asteriskRed">*</span>:</label>
							<div class="input-group date" >
								<input type="text" class="backWhite form-control" 
									ng-model="followUp.next_followup_date"
									id="followup_date"
									readonly
									placeholder="" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<span ng-messages="followupEditForm.followup_date.$error" 
							 ng-if='followupEditForm.followup_date.$dirty  || followupEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please add the next Follow-up date</span>
							</span>
						</div>
						<div class="col-xs-12">
							<label>Follow-up Detail<span class="asteriskRed">*</span>:</label>
							<textarea class="backWhite form-control" readonly
									ng-model="followUp.followup_detail"
									id="followup_detail"ng-required='true'
									placeholder="Followup Detail" > </textarea>
							<span ng-messages="followupEditForm.followup_detail.$error" 
							 ng-if='followupEditForm.followup_detail.$dirty  || followupEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please add the next Follow-up date</span>
							</span>
						</div>
						<div class="col-xs-12">
							<label for="status">Status <span class="asteriskRed">*</span>:</label>
							<select class="form-control" name="status"
									ng-model="followupinquiry_status.selectedOption"
									ng-required='true' id="status"
									ng-options="item.name for item in followupinquiry_status.Options track by item.id ">
								<option value=""> Inquiry Status </option>
							</select>
							<span ng-messages="followupEditForm.status.$error" 
							ng-if='followupEditForm.status.$dirty  || followupEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please select customer first</span>
							</span>
						</div>
					<div class="form-group">
						<br/>
						<div class="col-sm-12">
							<div class="mT20 pull-right">
									<input type="hidden" ng-model="followUp.followup_id" ng-value="followUp.followup_id" name="followup_id">
									<input type="hidden" ng-model="followUp.inquiry_id" ng-value="followUp.inquiry_id" name="inquiry_id">
									<button type='submit' id='action_button' class='btn btn-primary' ng-click='updateFollowup(currentInquiryID,currentInquiryNumber,currentInquiryStatus)'>Update</button>
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
		<!-- **************************************edit ednd customer Modal **************************** -->