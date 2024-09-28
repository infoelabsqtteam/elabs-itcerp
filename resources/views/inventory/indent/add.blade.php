<div id="add_form" ng-hide="addFormDiv">
	<div class="row header">
		<strong class="pull-left headerText"><span  ng-bind="addFormHeading" ng-model="addFormHeading"></span></strong>	
		<strong class="pull-right closeDiv btn btn-primary" style="margin-top: 3px;" ng-click="hideAddForm()">Back</strong>	
	</div>	
	<div class="row mT5">
		<form name='indentForm' id="indentForm" novalidate>
		     <label for="submit">{{ csrf_field() }}</label>	
				<div class="row">			
					<div class="col-xs-6 form-group">
						<label for="indent_date">Indent Date<em class="asteriskRed">*</em></label>
						<div class="input-group date"  data-provide="datepicker">
							<input type="text" 
							    class="backWhite form-control" 
								ng-model="indent.indent_date"
								name="indent_date" 
								readonly ng-required='true'
								placeholder="Indent Date" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
						<span ng-messages="indentForm.indent_date.$error" 
						 ng-if='indentForm.indent_date.$dirty  || indentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Indent Date is required</span>
						</span>
					</div>					
					<div class="col-xs-6 form-group">
						<label for="indent_no">Indent Number<em class="asteriskRed">*</em></label>
							<input type="text" ng-init="funGetIndentNumber();"
							    class="backWhite form-control" 
								name="indent_no"  
								ng-value="IndentNumber"
								readonly ng-required='true'
								placeholder="Indent Number" />
						<span ng-messages="indentForm.indent_no.$error" 
						 ng-if='indentForm.indent_no.$dirty  || indentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Indent Date is required</span>
						</span>
					</div>
					<?php if(empty($division_id) && $division_id=='0'){ ?>
						<div class="col-xs-6  form-group">
							<label for="division_id">Branch<em class="asteriskRed">*</em></label>						   
							<select class="form-control"
									name="division_id" ng-change="funGetDivisionWiseEmp(indent.division_id.selectedOption.id)"
									ng-model="indent.division_id.selectedOption"
									ng-required="true"
									ng-options="item.name for item in divisionsList track by item.id ">
									<option value="">Select Branch</option>
							</select>
							<span ng-messages="indentForm.division_id.$error" ng-if='indentForm.division_id.$dirty  || indentForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch is required</span>
							</span>
						</div>	
					<?php }else{ ?>			
							<input type="hidden" ng-required="true"  class="form-control" name="division_id" value="{{$division_id}}">
					<?php } ?>	
					
					<div class="col-xs-6  form-group">
						<label for="indented_by">Indented By<em class="asteriskRed">*</em></label>						   
						<select class="form-control"
								name="indented_by"
								ng-model="indent.indented_by.selectedOption"
								ng-required="true"
								ng-options="item.name for item in employeeList track by item.id ">
								<option value="">Select Employee</option>
						</select>
						<span ng-messages="indentForm.indented_by.$error" ng-if='indentForm.indented_by.$dirty  || indentForm.$submitted' role="alert">
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
								<th><label>Item* </label></th>			
								<th><label>Description</label></th>				
								<th><label>Indent Qty*</label></th>	
								<th class="width155"><label>Required By Date</label></th>						
								<th>
									<label><input style="display:none;" type="number" class="width70 " title="Add multiple rows" id="mutipleRows" min=1 max=20 value="">
									<a title="Add New Row" id="#addNewRow" href="javascript:;" ng-click='addRow(1)'><i class="font15 mL5 glyphicon glyphicon-plus"></i></a></label>
								</th>
							</tr>
						</thead>
						<tbody ng-if="add_indent_inputs" select-last ng-repeat="indent_inputs in indent_inputs">
						  
						</tbody>
					</table>		  
				</div>
			</div>
			<div class="row mT5">
				<div class="col-xs-12 ">
					<div class="pull-right">
						<button title="Save" ng-disabled="indentForm.$invalid" type="submit" id="add_button" class="btn btn-primary" ng-click="addIndent(divisionID)">Save</button>
					</div>
				</div>
			</div>
		</div>
	</form>			
</div> 
	
