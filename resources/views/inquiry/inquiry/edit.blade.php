<!-- **************************************edit Start customer Modal **************************** -->
		<div class="modal fade" id="edit_new" role="dialog">
			<div class="modal-dialog">
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title text-center">Edit Inquiry</h4>
				</div>
				<div class="modal-body">
													
				<!--display Messge Div-->
				@include('includes.alertMessagePopup')
				<!--/display Messge Div-->
				
				  <form name='inquiryEditForm' id="add_inquiry_form" novalidate>
					<div class="row">								
						<div class="col-xs-12">
							<label for="customer_id">Customer Name <span class="asteriskRed">*</span>:</label>							
							<input type="text" class="backWhite form-control" 
									ng-model="inquiry.customername"
									readonly  
									id="inquiry_date"
									ng-required='true' 
									placeholder="" />
							<span ng-messages="inquiryEditForm.customer_id.$error" 
							ng-if='inquiryEditForm.customer_id.$dirty  || inquiryEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please select customer first</span>
							</span>
						</div>
						<div class="col-xs-12">
							<label>Inquiry Date<span class="asteriskRed">*</span>:</label>						
							<div class="input-group date" >
								<input type="text" class="backWhite form-control" 
									ng-model="inquiry.inquiry_date"
									readonly 
									id="inquiry_date"
									ng-required='true' 
									placeholder="" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<span ng-messages="inquiryEditForm.inquiry_date.$error" 
							ng-if='inquiryEditForm.inquiry_date.$dirty  || inquiryEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Inquiry date is required </span>
							</span>
						</div>
						<div class="col-xs-12">
							<label>Next Follow-up Date<span class="asteriskRed">*</span>:</label>
							<div class="input-group date">
								<input type="text" class="backWhite form-control" 
									ng-model="inquiry.next_followup_date"
									readonly
									id="followup_date"
									ng-required='true'
									placeholder="" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<span ng-messages="inquiryEditForm.followup_date.$error" 
							 ng-if='inquiryEditForm.followup_date.$dirty  || inquiryEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Next follow-up date is required</span>
							</span>
						</div>						
						<div class="col-xs-12">
							<label>Inquiry Detail<span class="asteriskRed">*</span>:</label>
							<textarea class="form-control backwhite" 
									ng-model="inquiry.inquiry_detail"
									readonly 
									id="inquiry_detail" 
									ng-required='true'
									placeholder="Inquiry Detail" > </textarea>
							<span ng-messages="inquiryEditForm.inquiry_detail.$error" 
							 ng-if='inquiryEditForm.inquiry_detail.$dirty  || inquiryEditForm.$submitted' role="alert">
								<span ng-message="required" class="error">Inquiry detail is required</span>
							</span>
						</div>
						<div class="col-xs-12">
							<label for="inquiry_status">Status <span class="asteriskRed">*</span>:</label>
							<select class="form-control" name="inquiry_status"
									ng-model="inquiry_status.selectedOption"
									ng-required='true' id="inquiry_status"
									ng-options="item.name for item in inquiry_status.Options track by item.id ">
								<option value=""> Inquiry Status </option>
							</select>
							<span ng-messages="followupForm.inquiry_status.$error" 
							ng-if='followupForm.inquiry_status.$dirty  || followupForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please select customer first</span>
							</span>
						</div>
						<div class="col-sm-12">
							<div class="mT20 pull-right">
								<input type="hidden" name="customer_id" ng-value="inquiry.customer_id" ng-model="inquiry.customer_id">
								<input type="hidden" name="id" ng-value="inquiry.id" ng-model="inquiry.id">
								<button type='submit'  id='action_button' class='btn btn-primary' ng-click='updateInquiry()' > Update </button>
								<button title="Close"  type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						    </div>
						</div>
					</form>
				</div>
			   
			  </div>
			  
			</div>
		  </div>
        </div>
		<!-- **************************************edit ednd customer Modal **************************** -->