<div id="edit_form" ng-hide="editFormDiv">
	<div class="row header">
		<strong class="pull-left headerText"><span  ng-bind="addFormHeading" ng-model="addFormHeading"></span></strong>	
		<strong class="pull-right closeDiv btn btn-primary" style="margin-top: 3px;" ng-click="hideAddForm()">Back</strong>	
	</div>	
	<div class="row mT5">
		<form name='editIndentForm' id="editIndentForm" novalidate>
		  <label for="submit">{{ csrf_field() }}</label>	
				<div class="row">
					<div class="col-xs-6 form-group">
						<label for="indent_date">Indent Date<em class="asteriskRed">*</em></label>
						<div class="input-group date" data-provide="datepicker">
							<input type="text" 
							    class="backWhite form-control" 
								ng-model="editIndent.indent_date"
								name="indent_date" 
								readonly ng-required='true'
								placeholder="Indent Date" />
								<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
								</div>
						</div>
						<span ng-messages="editIndentForm.indent_date.$error" 
						 ng-if='editIndentForm.indent_date.$dirty  || editIndentForm.$submitted' role="alert">
							<span ng-message="required" class="error">Indent Date is required</span>
						</span>
					</div>
					<div class="col-xs-6 form-group" ng-show="editIndent.indent_no">
						<label for="indent_no">Indent Number<em class="asteriskRed">*</em></label>
						<div class="">
							<input type="hidden" ng-if="editIndent.indent_hdr_id" 
									name="indent_hdr_id" 
									ng-value="editIndent.indent_hdr_id" 
									ng-model="editIndent.indent_hdr_id">
							<input type="text" class="backWhite form-control" 
									ng-model="editIndent.indent_no" readonly 
									placeholder="Indent Number" />
						</div>
					</div>
					<?php if(empty($division_id) && $division_id=='0'){ ?>
						<div class="col-xs-6  form-group">
							<label for="division_id">Branch<em class="asteriskRed">*</em></label>						   
							<select class="form-control"
									name="division_id" ng-change="funGetDivisionWiseEmp(editIndent.division_id.selectedOption)"
									ng-model="editIndent.division_id.selectedOption"
									ng-required="true"
									ng-options="item.name for item in divisionsList track by item.id ">
									<option value="">Select Branch</option>
							</select>
							<span ng-messages="editIndentForm.division_id.$error" ng-if='editIndentForm.division_id.$dirty  || editIndentForm.$submitted' role="alert">
								<span ng-message="required" class="error">Branch is required</span>
							</span>
						</div>	
					<?php }else{ ?>			
							<input type="hidden" class="form-control" name="division_id" value="{{$division_id}}">
					<?php } ?>	
					
					<div class="col-xs-6  form-group">
						<label for="indented_by">Indented By<em class="asteriskRed">*</em></label>						   
						<select class="form-control"
								name="indented_by"
								ng-model="editIndent.indented_by.selectedOption"
								ng-required="true"
								ng-options="item.name for item in employeeList track by item.id ">
								<option value="">Select Employee</option>
						</select>
						<span ng-messages="editIndentForm.indented_by.$error" ng-if='editIndentForm.indented_by.$dirty  || editIndentForm.$submitted' role="alert">
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
								<th><label class="tdWidth">Required By Date</label></th>						
								<th>
									<label><a title="Add New Row" id="#addNewRow" href="javascript:;" ng-click='addRow(1)'><i class="font15 mL5 glyphicon glyphicon-plus"></i></a></label>
								</th>
							</tr>
						</thead>
						<tbody ng-if="edit_indent_inputs">
						<tr ng-if="edit_indent_inputs" ng-repeat="obj in indent_inputs">
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
										<textarea type="text" rows=1
												class="backWhite form-control descrip" 
												readonly id="description_[[$index]]"				
												placeholder="Description"/>[[obj.item_description]]</textarea>	
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="number" 
											   value="[[obj.indent_qty | number]]"
											   ng-value="[[obj.indent_qty | number]]" 
											   name="indent_qty[]" 
											   id="indent_qty_[[$index]]"
											   ng-model="editIndent.indent_qty[[$index]]" 
											   ng-required="true" class="form-control Qty" 
											   placeholder="Indent Qty" />	
									</div>
								</td>
								
								<td>
									<div class="form-group">
										<div class="input-group date" data-provide="datepicker" >
											<input type="text"
												   ng-keydown="checkkey($event)"
												   ng-value="obj.required_by_date"
												   name="required_by_date[]" 
												   id="required_by_date_[[$index]]"
												   ng-model="editIndent.required_by_date[[$index]]" 
												   ng-required="true" class="form-control Qty" 
												   placeholder="Required by date" />
												   <div class="input-group-addon">
														<span class="glyphicon glyphicon-th"></span>
													</div>
										</div>
									</div>
								</td>
								<td>
									<input ng-show="editIndent.indent_no" 
										value="[[obj.indent_dtl_id]]" 
										type="hidden" name="indent_dtl_id[]"  
										id="indent_dtl_id[[$index]]"> 
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
						<button title="Save" type="submit" id="edit_button" class="btn btn-primary" ng-click="updateIndent(editIndentID,divisionID)">Update</button>
					</div>
				</div>
			</div>
		</div>
	</form>			
</div> 
	
