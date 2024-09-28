<div id="edit_form" ng-hide="editFormDiv">
	<div class="row header">
		<strong class="pull-left headerText"><span  ng-bind="addFormHeading" ng-model="addFormHeading"></span></strong>	
		<strong class="pull-right closeDiv btn btn-primary" style="margin-top: 3px;" ng-click="hideAddForm()">Back</strong>	
	</div>	
	<div class="row mT5">
		<form name='editRequisitionForm' id="editRequisitionForm" novalidate>
		<label for="submit">{{ csrf_field() }}</label>
				<div class="row">			
					<div class="col-xs-6 form-group">
						<label for="req_slip_date">Slip Date<em class="asteriskRed">*</em></label>
						<div class="input-group date"  data-provide="datepicker">
							<input type="text" 
							    class="backWhite form-control" 
								ng-model="editRequisition.req_slip_date"
								name="req_slip_date" 
								readonly ng-required='true'
								placeholder="Slip Date" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
						<span ng-messages="editRequisitionForm.req_slip_date.$error" 
						 ng-if='editRequisitionForm.req_slip_date.$dirty  || editRequisitionForm.$submitted' role="alert">
							<span ng-message="required" class="error">Slip Date is required</span>
						</span>
					</div>
					<div class="col-xs-6 form-group" ng-show="editRequisition.req_slip_no">
						<label for="req_slip_no">Slip Number<em class="asteriskRed">*</em></label>
						<div class="">
							<input type="hidden" name="req_slip_id" ng-value="editRequisition.req_slip_id" ng-model="editRequisition.req_slip_id">
							<input type="text" class="backWhite form-control" ng-model="editRequisition.req_slip_no" readonly placeholder="Slip Number" />
						</div>
					</div>		
					<div class="col-xs-6  form-group">
						<label for="req_department_id">Department<em class="asteriskRed">*</em></label>						   
						<select class="form-control" 
									name="req_department_id" 
									ng-model="editRequisition.req_department_id.selectedOption"
									ng-required='true'
									ng-options="item.name for item in departmentList track by item.req_department_id">
								<option value="">Select Department</option>
						</select>
						<span ng-messages="editRequisitionForm.req_department_id.$error" ng-if='editRequisitionForm.req_department_id.$dirty  || editRequisitionForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>
					<?php if(empty($division_id) && $division_id=='0'){ ?>
						<div class="col-xs-6  form-group">
							<label for="division_id">Branch<em class="asteriskRed">*</em></label>						   
							<select class="form-control"
									name="division_id" ng-change="funGetDivisionWiseEmp(editRequisition.division_id.selectedOption.id)"
									ng-model="editRequisition.division_id.selectedOption"
									ng-required="true"
									ng-options="item.name for item in divisionsList track by item.id ">
									<option value="">Select Branch</option>
							</select>
							<span ng-messages="editRequisitionForm.division_id.$error" ng-if='editRequisitionForm.division_id.$dirty  || editRequisitionForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch is required</span>
							</span>
						</div>	
					<?php }else{ ?>			
							<input type="hidden" class="form-control" name="division_id" value="{{$division_id}}">
					<?php } ?>	
					
					<div class="col-xs-6  form-group">
						<label for="req_by">Issued By<em class="asteriskRed">*</em></label>						   
						<select class="form-control"
								name="req_by"
								ng-model="editRequisition.req_by.selectedOption"
								ng-required="true"
								ng-options="item.name for item in employeeList track by item.id ">
								<option value="">Select Employee</option>
						</select>
						<span ng-messages="editRequisitionForm.req_by.$error" ng-if='editRequisitionForm.req_by.$dirty  || editRequisitionForm.$submitted' role="alert">
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
								<th>
									<label>Required Qty*</label>
								</th>						
								<th>
									<label><a title="Add New Row" id="#addNewRow" href="javascript:;" ng-click='addRow(1)'><i class="font15 mL5 glyphicon glyphicon-plus"></i></a></label>
								</th>
							</tr>
						</thead>
						<tbody ng-if="edit_MRS_inputs" ng-repeat="obj in MRS_inputs" elem-ready="someMethod()">
						   <tr ng-if="edit_MRS_inputs">
								<td>
									<div class="form-group">
										<md-autocomplete
											md-input-name="item_id[]"
											md-selected-item-change="funGetItemDescOnChange([[$index]],[[item.item_id]])"
											md-selected-item="selectedItem"
											md-search-text="obj.item_code"
											md-items="item in getMatches(obj.item_code)"
											md-item-text="item.item_code"				
											md-no-cache="false"
											md-clear-button="false"
											placeholder="Search Item Code">
											<md-item-template><span md-highlight-text="searchText" md-highlight-flags="^i">[[item.item_code]]</span></md-item-template>
											<md-not-found>No matches found.</md-not-found>
										</md-autocomplete>
									</div>
								</td>
								<td>
									<div class="form-group">
										<textarea type="text"  rows=1
												class="backWhite form-control descrip"  
												readonly id="description_[[$index]]"/>[[obj.item_description]]</textarea>
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="number" 
											   ng-keydown="checkkey($event)" 
											   name="required_qty[]" 
											   id="required_qty_[[$index]]"
											   value="[[obj.required_qty | number]]"
											   ng-value="[[obj.required_qty | number]]" 
											   ng-model="editRequisition.required_qty[[$index]]" 
											   class="form-control Qty" 
											   placeholder="Required Qty" />	
									</div>
								</td>
								<td>
									<input  ng-show="editRequisition.req_slip_no" 
											ng-value="[[obj.req_slip_dlt_id]]" 
											value="[[obj.req_slip_dlt_id]]" 
											type="hidden" 
											name="req_slip_dlt_id[]"  id="req_slip_dlt_id[[$index]]">
									<div ng-hide="!$index" class="form-group">
										<a href="javascript:;" title="Delete Row" ng-click="deleteRow($index);">
											<i class="font15 removeIcon glyphicon glyphicon-remove"></i>
										</a>
									</div>&nbsp;
								</td>
							</tr>
						</tbody>
					</table>		  
				</div>
			</div>
			<div class="row mT5">
				<div class="col-xs-12 ">
					<div class="pull-right">
						<button title="Update" type="submit" ng-disabled="editRequisitionForm.$invalid" class="btn btn-primary" ng-click="updateRequisition(editRequisitionID,divisionID)">Update</button>
					</div>
				</div>
			</div>
		</div>
	</form>			
</div>