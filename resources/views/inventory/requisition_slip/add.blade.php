<div id="add_form" ng-hide="addFormDiv">
	<div class="row header">
		<strong class="pull-left headerText"><span  ng-bind="addFormHeading" ng-model="addFormHeading"></span></strong>	
		<strong class="pull-right closeDiv btn btn-primary" style="margin-top: 3px;" ng-click="hideAddForm()">Back</strong>	
	</div>	
	<div class="row mT5">
		<form name='requisitionForm' id="requisitionForm" novalidate>
		<label for="submit">{{ csrf_field() }}</label>
				<div class="row">			
					<div class="col-xs-6 form-group">
						<label for="req_slip_date">Slip Date<em class="asteriskRed">*</em></label>
						<div class="input-group date"  data-provide="datepicker">
							<input type="text" 
							    class="backWhite form-control" 
								ng-model="requisition.req_slip_date"
								name="req_slip_date" 
								readonly ng-required='true'
								placeholder="Slip Date" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
						<span ng-messages="requisitionForm.req_slip_date.$error" 
						 ng-if='requisitionForm.req_slip_date.$dirty  || requisitionForm.$submitted' role="alert">
							<span ng-message="required" class="error">Slip Date is required</span>
						</span>
					</div>
					<div class="col-xs-6 form-group">
						<label for="req_slip_no">Slip Number<em class="asteriskRed">*</em></label>
						<div class="">
							<input type="text" class="backWhite form-control"
								name="req_slip_no"
								ng-model="requisition.req_slip_no"  
								ng-init="funGetRequisitionNumber();"
								readonly ng-value="RequisitionNumber"
								placeholder="Slip Number" />
						</div>
					</div>		
					<div class="col-xs-6  form-group">
						<label for="req_department_id">Department<em class="asteriskRed">*</em></label>						   
						<select class="form-control" 
									name="req_department_id" 
									ng-model="requisition.req_department_id.selectedOption"
									ng-required='true'
									ng-options="item.name for item in departmentList track by item.req_department_id">
								<option value="">Select Department</option>
						</select>
						<span ng-messages="requisitionForm.req_department_id.$error" ng-if='requisitionForm.req_department_id.$dirty  || requisitionForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>
					<?php if(empty($division_id) && $division_id=='0'){ ?>
						<div class="col-xs-6  form-group">
							<label for="division_id">Branch<em class="asteriskRed">*</em></label>						   
							<select class="form-control"
									name="division_id" ng-change="funGetDivisionWiseEmp(requisition.division_id.selectedOption.id)"
									ng-model="requisition.division_id.selectedOption"
									ng-required="true"
									ng-options="item.name for item in divisionsList track by item.id ">
									<option value="">Select Branch</option>
							</select>
							<span ng-messages="requisitionForm.division_id.$error" ng-if='requisitionForm.division_id.$dirty  || requisitionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch is required</span>
							</span>
						</div>	
					<?php }else{ ?>			
							<input type="hidden" ng-required="true"  class="form-control" name="division_id" value="{{$division_id}}">
					<?php } ?>	
					
					<div class="col-xs-6  form-group">
						<label for="req_by">Issued By<em class="asteriskRed">*</em></label>						   
						<select class="form-control"
								name="req_by"
								ng-model="requisition.req_by.selectedOption"
								ng-required="true"
								ng-options="item.name for item in employeeList track by item.id ">
								<option value="">Select Employee</option>
						</select>
						<span ng-messages="requisitionForm.req_by.$error" ng-if='requisitionForm.req_by.$dirty  || requisitionForm.$submitted' role="alert">
							<span ng-message="required" class="error">Issued By is required</span>
						</span>
					</div>	
				</div>
				
				<div class="row">
					<div id="no-more-tables">
					 <!-- show error message -->
					<table class="col-sm-12 table-striped table-condensed cf tableThBg tableWidth">
						<thead>
							<tr>
								<th>
									<label>Item* </label>
								</th>			
								<th>
									<label>Description</label>
								</th>				
								<th class="width155">
									<label>Required Qty*</label>
								</th>						
								<th>
									<label><input type="number" style="display:none;" class="width60" title="Add multiple rows" id="mutipleRows" min=1 max=20 value=""><a title="Add New Row" id="#addNewRow" href="javascript:;" ng-click='addRow(1)'><i class="font15 mL5 glyphicon glyphicon-plus"></i></a></label>
								</th>
							</tr>
						</thead>
						<tbody ng-if="add_MRS_inputs" select-last ng-repeat="MRS_inputs in MRS_inputs">
						  
						</tbody>
					</table>		  
				</div>
			</div>
			<div class="row mT5">
				<div class="col-xs-12 ">
					<div class="pull-right">
						<button title="Save" ng-disabled="requisitionForm.$invalid" type="submit" id="add_button" class="btn btn-primary" ng-click="addRequisition(divisionID)">Save</button>
					</div>
				</div>
			</div>
		</div>
	</form>			
</div> 
	
