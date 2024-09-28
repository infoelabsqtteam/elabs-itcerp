<tr>
    <td>
		<div class="form-group">
			<md-autocomplete
				md-input-name="item_id[]"
				md-selected-item-change="funGetItemDescOnChange([[$index]],[[item.item_id]],0)"
				md-selected-item="selectedItem"
				md-search-text="edit_po_inputs_obj.item_code"
				md-items="item in getMatches(edit_po_inputs_obj.item_code)"
				md-item-text="item.item_code"				
				md-no-cache="false"
				md-clear-button="false"
				ng-required="true"
				placeholder="Search Item Code">
				<md-item-template><span md-highlight-text="searchText" md-highlight-flags="^i">[[item.item_code]]</span></md-item-template>
				<md-not-found>No matches found.</md-not-found>
			</md-autocomplete>
		</div>
	</td>
	<td>
		<div class="form-group">
            <textarea readonly
				class="bgWhite form-control description"
                ng-bind="edit_po_inputs_obj.item_description"
				id="edit_description_[[$index]]"				
				placeholder="Description">
            </textarea>
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="item_qty[]" 
				id="edit_item_qty_[[$index]]"
                ng-value="edit_po_inputs_obj.purchased_qty"
				ng-model="editBranchWiseDPOPOModel.item_qty[[$index]]"
				class="editItemQty form-control" 
				placeholder="Item Qty"
				ng-change="editUpdateItemIndividualAmount([[$index]])" />
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="text"
				name="item_rate[]" 
				id="edit_item_rate_[[$index]]"
                ng-value="edit_po_inputs_obj.item_rate"
				ng-model="editBranchWiseDPOPOModel.item_rate[[$index]]"
				class="form-control" 
				placeholder="Item Rate"
				ng-change="editUpdateItemIndividualAmount([[$index]])" />
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="text"
				   readonly
			       ng-keydown="editMoreOnTab($event)"
				   name="item_amount[]"
                   ng-value="edit_po_inputs_obj.item_amount"
				   id="edit_item_amount_[[$index]]"
				   ng-model="editBranchWiseDPOPOModel.item_amount[[$index]]" 
				   class="editItemAmount form-control bgWhite" 
				   placeholder="Item Amount" />	
		</div>
	</td>
	<td>
		<div ng-hide="!$index" class="form-group">
            <input type="hidden" id="po_dtl_id" ng-value="edit_po_inputs_obj.po_dtl_id" ng-model="editBranchWiseDPOPOModel.po_dtl_id" name="po_dtl_id[]">
			<a href="javascript:;" title="Remove" ng-click="deleteEditPORow($index,edit_po_inputs_obj.po_dtl_id);"><i class="font15 removeIcon glyphicon glyphicon-remove"></i></a>
		</div>&nbsp;
	</td>
</tr>