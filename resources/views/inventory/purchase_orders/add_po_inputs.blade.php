<tr>
    <td>
		<div class="form-group">
			<md-autocomplete
				md-input-name="item_id[]"
				md-selected-item-change="funGetItemDescOnChange([[$index]],[[item.item_id]],1)"
				md-selected-item="selectedItem"
				md-search-text="searchText"
				md-items="item in getMatches(searchText)"
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
				id="description_[[$index]]"				
				placeholder="Description"></textarea>
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number"
				name="item_qty[]" 
				id="item_qty_[[$index]]"
				ng-model="addBranchWiseDPOPOModel.item_qty[[$index]]" 
				ng-required="true"
				class="itemQty form-control" 
				placeholder="Item Qty"
				ng-change="updateItemIndividualAmount([[$index]])" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="text"
				name="item_rate[]" 
				id="item_rate_[[$index]]"
				ng-model="addBranchWiseDPOPOModel.item_rate[[$index]]" 
				ng-required="true"
				class="form-control" 
				placeholder="Item Rate"
				ng-change="updateItemIndividualAmount([[$index]])" />	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="text"
				   readonly
			       ng-keydown="addMoreOnTab($event)"
				   name="item_amount[]" 
				   id="item_amount_[[$index]]"
				   ng-model="addBranchWiseDPOPOModel.item_amount[[$index]]" 
				   class="itemAmount form-control bgWhite" 
				   placeholder="Item Amount" />	
		</div>
	</td>
	<td>
		<div ng-hide="!$index" class="form-group">
			<a href="javascript:;" title="Remove" ng-click="deletePORow($index,0);"><i class="font15 removeIcon glyphicon glyphicon-remove"></i></a>
		</div>&nbsp;
	</td>
</tr>