<tr ng-repeat="edit_ign_inputs_obj in edit_ign_inputs">
    <td>
		<div class="form-group">
			<md-autocomplete
				md-input-name="item_id[]"
				md-selected-item-change="funGetPONoOnChange([[$index]],[[item.item_id]],1)"
				md-selected-item="selectedItem"
				md-search-text="edit_ign_inputs_obj.item_code"
				md-items="item in getMatches(edit_ign_inputs_obj.item_code)"
				md-item-text="item.item_code"				
				md-no-cache="false"
				md-clear-button="false"
				ng-required="true"
				title="Item Code"
				placeholder="Search Item Code">
				<md-item-template><span md-highlight-text="searchText" md-highlight-flags="^i">[[item.item_code]]</span></md-item-template>
				<md-not-found>No matches found.</md-not-found>
			</md-autocomplete>
		</div>
	</td>
	<td>
		<div class="form-group">
			<select class="form-control width195"
				name="po_hdr_id[]"
				ng-init="funGetPONoOnChange([[$index]],edit_ign_inputs_obj.item_id,edit_ign_inputs_obj.po_hdr_id)"
				id="edit_po_hdr_id_[[$index]]"
				ng-model="editBranchWiseIGN.po_hdr_id[[$index]]"
				title="PO No.">
				<option value="">Select PO NO</option>
				<option ng-repeat="poNos in purchaseOrderNoList[$index]" value="[[poNos.po_hdr_id]]">[[poNos.po_no]]</option>
			</select>
		</div>
	</td>
	<td>
		<div data-provide="datepicker" class="input-group date" style="margin-top: -15px ! important;">
			<input readonly type="text"
				   id="edit_expirt_date_[[$index]]"
                   ng-value="edit_ign_inputs_obj.expiry_date"
				   ng-model="editBranchWiseIGN.expirt_date[[$index]]"                                       
				   name="expiry_date[]"
				   title="Expiry Date"
				   class="form-control bgWhite width100"
				   placeholder="Expiry Date">
			<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="bill_qty[]"
                ng-value="edit_ign_inputs_obj.bill_qty"
				id="edit_bill_qty_[[$index]]"
				ng-model="editBranchWiseIGN.bill_qty[[$index]]"
				title="Bill Qty"
				class="form-control width75" 
				placeholder="Bill Qty" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="received_qty[]"
                ng-value="edit_ign_inputs_obj.received_qty"
				id="edit_received_qty_[[$index]]"
				ng-model="editBranchWiseIGN.received_qty[[$index]]"
				class="form-control width75"
				title="Received Qty"
				placeholder="Received Qty" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="ok_qty[]"
                ng-value="edit_ign_inputs_obj.ok_qty"
				id="edit_ok_qty_[[$index]]"
				ng-model="editBranchWiseIGN.ok_qty[[$index]]"
				title="OK Qty"
				class="form-control width75" 
				placeholder="OK Qty" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="bill_rate[]"
                ng-value="edit_ign_inputs_obj.bill_rate"
				id="edit_bill_rate_[[$index]]"
				ng-model="editBranchWiseIGN.bill_rate[[$index]]"
				title="Bill Rate"
				class="form-control width75" 
				placeholder="Bill Rate" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="pass_rate[]"
                ng-value="edit_ign_inputs_obj.pass_rate"
				id="edit_pass_rate_[[$index]]"
				ng-model="editBranchWiseIGN.pass_rate[[$index]]" 
				title="Pass Rate"
				class="form-control width75" 
				placeholder="Pass Rate" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="text"
				name="landing_cost[]"
                ng-value="edit_ign_inputs_obj.landing_cost"
				id="edit_landing_cost_[[$index]]"
				ng-model="editBranchWiseIGN.landing_cost[[$index]]" 
				title="Landing Cost"
				class="form-control width115"
				ng-keydown="addMoreOnTab($event)"
				placeholder="Landing Cost" />	
		</div>
	</td>
	<td>
		<div ng-hide="!$index" class="form-group">
            <input type="hidden" id="edit_ign_hdr_dtl_id" ng-value="edit_ign_inputs_obj.ign_hdr_dtl_id" ng-model="editBranchWiseDPOPOModel.ign_hdr_dtl_id" name="ign_hdr_dtl_id[]">
			<a href="javascript:;" title="Remove" ng-click="deleteIGNRowEdit($index,edit_ign_inputs_obj.ign_hdr_dtl_id);"><i class="font15 removeIcon glyphicon glyphicon-remove"></i></a>
		</div>&nbsp;
	</td>
</tr>