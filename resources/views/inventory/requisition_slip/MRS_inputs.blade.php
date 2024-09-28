<tr>
    <td>
		<div class="form-group">
			<md-autocomplete
				md-input-name="item_id[]"
				md-selected-item-change="funGetItemDescOnChange([[$index]],[[item.item_id]])"
				md-selected-item="selectedItem"
				md-search-text="searchText"
				md-items="item in getMatches(searchText)"
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
					placeholder="Description"/></textarea>	
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number" 
			       ng-keydown="checkkey($event)" 
				   name="required_qty[]" 
				   id="required_qty_[[$index]]"
				   ng-model="requisition.required_qty[[$index]]" 
				   ng-required="true" class="form-control Qty" 
				   placeholder="Required Qty" />	
		</div>
	</td>
	<td>
		<input ng-show="requisition.req_slip_no" type="hidden" name="req_slip_dlt_id[]"  id="req_slip_dlt_id[[$index]]">
		<div ng-hide="!$index" class="form-group">
			<a href="javascript:;" title="Delete Row" ng-click="deleteRow($index);">
				<i class="font15 removeIcon glyphicon glyphicon-remove""></i>
			</a>
		</div>&nbsp;
	</td>
</tr>