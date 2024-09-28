<div class="modal fade" id="add_new" role="dialog">
			<div class="modal-dialog">
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title text-center">Add Inquiry</h4>
				</div>
				<div class="modal-body">
													
				<!--display Messge Div-->
				@include('includes.alertMessagePopup')
				<!--/display Messge Div-->
				
				  <form name='inquiryForm' id="add_inquiry_form" novalidate>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="row">
						<div class="col-xs-12">
							<label for="customer_id">Select Customer <span class="asteriskRed">*</span>:</label>							
							<select class="form-control" name="customer_id"
									ng-model="inquiry.customer_id"
									ng-required='true' id="customer_id"
									ng-options="item.customer_name for item in customerList track by item.customer_id ">
								<option value=""> Select Customer </option>
							</select>
							<span ng-messages="inquiryForm.customer_id.$error" 
							ng-if='inquiryForm.customer_id.$dirty  || inquiryForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please select customer first</span>
							</span>
						</div>
						<div class="col-xs-12">
							<label>Date<span class="asteriskRed">*</span>:</label>						
							<div class="input-group date" data-provide="datepicker">
								<input type="text" class="backWhite form-control" 
									ng-model="inquiry.inquiry_date"
									name="inquiry_date" 
									id="inquiry_date"
									ng-required='true' 
									placeholder="" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<span ng-messages="inquiryForm.inquiry_date.$error" 
							ng-if='inquiryForm.inquiry_date.$dirty  || inquiryForm.$submitted' role="alert">
								<span ng-message="required" class="error">Inquiry date is required </span>
							</span>
						</div>
						<div class="col-xs-12">
							<label>Next Follow-up Date<span class="asteriskRed">*</span>:</label>
							<div class="input-group date" data-provide="datepicker">
								<input type="text" class="backWhite form-control" 
									ng-model="inquiry.followup_date"
									name="followup_date" 
									id="followup_date"
									ng-required='true'
									placeholder="" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<span ng-messages="inquiryForm.followup_date.$error" 
							 ng-if='inquiryForm.followup_date.$dirty  || inquiryForm.$submitted' role="alert">
								<span ng-message="required" class="error">Next follow-up date is required</span>
							</span>
						</div>						
						<div class="col-xs-12">
							<label>Detail<span class="asteriskRed">*</span>:</label>
							<textarea class="form-control" 
									ng-model="inquiry.detail"
									name="inquiry_detail" 
									id="inquiry_detail" 
									ng-required='true'
									placeholder="Inquiry Detail" > </textarea>
							<span ng-messages="inquiryForm.inquiry_detail.$error" 
							 ng-if='inquiryForm.inquiry_detail.$dirty  || inquiryForm.$submitted' role="alert">
								<span ng-message="required" class="error">Inquiry detail is required</span>
							</span>
						</div>
						<div class="col-sm-12">
							<div class="mT20 pull-right">
								<input type="hidden" id="user-id" name="inquiryId">
								<button type='submit'  id='action_button' class='btn btn-primary' ng-click='addInquiry()' > Save </button>
								<button title="Close"  type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
						</div>
					</form>
				</div>
			  </div>
			</div>
		  </div>
        </div>