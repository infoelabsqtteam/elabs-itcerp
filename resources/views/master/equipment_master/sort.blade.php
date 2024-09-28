<div class="row" ng-hide="IsViewEquipmentTypeSort" ng-init="getSelectedUnSelectedEquipments();">
    
    <div class="row header">
	<strong class="pull-left headerText" ng-click="getEquipments()" title="Refresh">Sort Equipment Types <span ng-if="equipData.length">([[equipData.length]])</span></strong>
	<div class="navbar-form navbar-right" role="search">
	    <div class="nav-custom">
		<button type="button" title="Back" class="btn btn-primary btn-sm" ng-click="showAddForm()"> Back</button>
	    </div>
	</div>
    </div>
	
    <div class="row col-sm-5">
	<form method="POST" role="form" id="erpSelectEquipmentSortingForm" name="erpSelectEquipmentSortingForm" novalidate>
	    <div id="no-more-tables" class="row col-sm-12 custom-scroll">
		<table class="col-sm-12 table-striped table-condensed cf">
		    <thead class="cf"><tr><th>Select Equipment</th></tr></thead>
		    <tbody>
			<tr ng-repeat="selectEquipmentobj in selectEquipmentList">
			    <td data-title="Equipment Name">
				<span class="txt-left"><input type="checkbox" ng-checked="selectEquipmentobj.is_equipment_selected > 0" id="selectEquipment_[[selectEquipmentobj.equipment_id]]" ng-value="selectEquipmentobj.equipment_id" ng-model="selectEquipment.equipment_id" name="equipment_id[]"></span>&nbsp;[[selectEquipmentobj.equipment_name]]
			    </td>
			</tr>
			<tr ng-if="!selectEquipmentList.length" class="noRecord text-left"><td colspan="1">No Record Found!</td></tr>
		    </tbody>
	       </table>
	    </div>
	    <div class="col-xs-12 form-group text-right mT10" ng-if="selectEquipmentList.length">
		<label for="submit">{{ csrf_field() }}</label>					
		<button type="button" class="btn btn-primary" ng-click="funSaveUpdateSelectSortingEquipment()">Update</button>
	    </div>
	</form>
    </div>
	
    <div class="row col-sm-1">&nbsp;</div>
	
    <div class="row col-sm-6">
	<form method="POST" role="form" id="erpSelectedEquipmentSortingForm" name="erpSelectedEquipmentSortingForm" novalidate>
	    <div id="no-more-tables" class="row col-sm-12 custom-scroll">
		<table class="col-sm-12 table-striped table-condensed cf">
		    <thead class="cf"><tr><th>Selected Equipment</th></tr></thead>
		</table>
		<ul id="equipmentSorting" class="col-sm-12 table-striped table-condensed cf sorting">
		    <li ng-repeat="selectedEquipmentobj in selectedEquipmentList" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input type="hidden" id="selectedEquipment_[[selectedEquipmentobj.equipment_id]]" ng-value="selectedEquipmentobj.equipment_id" ng-model="selectedEquipment.equipment_id" name="equipment_sort_by[]">[[selectedEquipmentobj.equipment_name]]</li>
		    <li ng-if="!selectedEquipmentList.length" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>No Record Found!</li>
		</ul>
	    </div>
	    <div class="col-xs-12 form-group text-right mT10" ng-if="selectedEquipmentList.length">
		<label for="submit">{{ csrf_field() }}</label>					
		<button type="button" class="btn btn-primary" ng-click="funSaveUpdateSelectedSortingEquipment()">Update</button>
	    </div>
	</form>
    </div>
</div>	