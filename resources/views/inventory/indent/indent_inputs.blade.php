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
					placeholder="Description"/>	</textarea>
		</div>
	</td>
	<td>
		<div class="form-group">
			<input type="number" 
				   name="indent_qty[]" 
				   id="indent_qty_[[$index]]"
				   ng-model="indent.indent_qty[[$index]]" 
				   ng-required="true" class="form-control Qty" 
				   placeholder="Indent Qty" />	
		</div>
	</td>
	
	<td>
		<div class="form-group">
			<div class="input-group date" data-provide="datepicker" >
				<input type="text"  
					   ng-keydown="checkkey($event)" 
					   name="required_by_date[]" 
					   id="required_by_date_[[$index]]"
					   ng-model="indent.required_by_date[[$index]]" 
					   ng-required="true" class="form-control Qty" 
					   placeholder="Required by date" />
					   <div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
					    </div>
			</div>
		</div>
	</td>
	<td>
		<input ng-if="indent.indent_no" type="hidden" name="indent_dtl_id[]"  id="indent_dtl_id[[$index]]">
		<div ng-hide="!$index" class="form-group">
			<a href="javascript:;" title="Delete Row" ng-click="deleteRow($index);">
				<i class="font15 removeIcon glyphicon glyphicon-remove"></i>
			</a>
		</div>&nbsp;
	</td>
</tr>