<tr>
    <td>
		<div class="form-group">
			<md-autocomplete
				md-input-name="item_id[]"
				md-selected-item-change="funGetPONoOnChange([[$index]],[[item.item_id]],0)"
				md-selected-item="selectedItem"
				md-search-text="searchText"
				md-items="item in getMatches(searchText)"
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
			<select class="form-control width100"
				name="po_hdr_id[]"
				id="po_hdr_id_[[$index]]"
				ng-model="addBranchWiseIGN.po_hdr_id[[$index]]"
				title="PO No."
				ng-options="po.po_no for po in purchaseOrderNoList[$index] track by po.po_hdr_id">
				<option value="">PO No.</option>
			</select>
		</div>
	</td>
	<td>
		<div data-provide="datepicker" class="input-group date" style="margin-top: -15px ! important;">
			<input readonly type="text"
				   id="expirt_date_[[$index]]"
				   ng-model="addBranchWiseIGN.expirt_date[[$index]]"                                       
				   name="expiry_date[]"
				   ng-required="true"
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
				id="bill_qty_[[$index]]"
				ng-model="addBranchWiseIGN.bill_qty[[$index]]" 
				ng-required="true"
				title="Bill Qty"
				class="form-control width100" 
				placeholder="Bill Qty" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="received_qty[]" 
				id="received_qty_[[$index]]"
				ng-model="addBranchWiseIGN.received_qty[[$index]]" 
				ng-required="true"
				class="form-control width100"
				title="Received Qty"
				placeholder="Received Qty" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="ok_qty[]" 
				id="ok_qty_[[$index]]"
				ng-model="addBranchWiseIGN.ok_qty[[$index]]" 
				ng-required="true"
				title="OK Qty"
				class="form-control width100" 
				placeholder="OK Qty" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="bill_rate[]" 
				id="bill_rate_[[$index]]"
				ng-model="addBranchWiseIGN.bill_rate[[$index]]" 
				ng-required="true"
				title="Bill Rate"
				class="form-control width100" 
				placeholder="Bill Rate" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="pass_rate[]" 
				id="pass_rate_[[$index]]"
				ng-model="addBranchWiseIGN.pass_rate[[$index]]" 
				ng-required="true"
				title="Pass Rate"
				class="form-control width100" 
				placeholder="Pass Rate" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="text"
				name="landing_cost[]" 
				id="landing_cost_[[$index]]"
				ng-model="addBranchWiseIGN.landing_cost[[$index]]" 
				ng-required="true"
				title="Landing Cost"
				class="form-control width115"
				ng-keydown="addMoreOnTab($event)"
				placeholder="Landing Cost" />	
		</div>
	</td>
	<td>
		<div ng-hide="!$index" class="form-group">
			<a href="javascript:;" title="Remove" ng-click="deleteIGNRowAdd($index,0);"><i class="font15 removeIcon glyphicon glyphicon-remove"></i></a>
		</div>&nbsp;
	</td>
</tr>