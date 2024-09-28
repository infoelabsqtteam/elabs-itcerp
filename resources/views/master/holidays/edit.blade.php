<div ng-model="editHolidayFormDiv" id="editHolidayDiv" ng-hide="editHolidayFormDiv" >
	
	<div class="row header1">
		<strong class="pull-left headerText">Edit Holiday</strong>
	</div>
		
	<form name='editHolidayForm' id="editHolidayForm" novalidate>
		
		<div class="row">
			<!--Branch -->
			<div class="col-xs-2 form-group">
				<label for="division_id">Branch<em class="asteriskRed">*</em></label>
				<select
					class="form-control"
					name="division_id"
					id="division_id"
					ng-model="edit_holiday.division_id"
					ng-required="true"
					ng-options="division.name for division in divisionsCodeList track by division.id">
					<option value="">Select Branch</option>
				</select>
				<span ng-messages="editHolidayForm.division_id.$error" ng-if="editHolidayForm.division_id.$dirty || editHolidayForm.$submitted" role="alert">
					<span ng-message="required" class="error">Branch is required</span>
				</span>
			</div>
			<!--/Branch -->
	
			<div class="col-xs-3">
				<label for="holiday_name">Holiday Name<em class="asteriskRed">*</em></label>
				<input
					type="text"
					class="form-control"
					ng-model="edit_holiday.holiday_name"
					name="holiday_name"
					id="holiday_name"
					ng-required='true'
					placeholder="Holiday Name" />
				<span ng-messages="editHolidayForm.holiday_name.$error" ng-if='editHolidayForm.holiday_name.$dirty  || editHolidayForm.$submitted' role="alert">
					<span ng-message="required" class="error">Holiday name is required</span>
				</span>
			</div>
			
			<div class="col-xs-2">
			<label for="holiday_date">Holiday Date<em class="asteriskRed">*</em></label>
				<div class="input-group date" data-provide="datepicker">
					<input
						type="text"
						class="bgwhite form-control ng-pristine ng-invalid ng-invalid-required ng-touched"
						name="holiday_date"
						id="holiday_date"
						ng-model="edit_holiday.holiday_date"
						ng-required="true"
						required="required"
						aria-required="true"
						aria-invalid="true"
						placeholder="Holiday Date">
					<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
				</div>
				<span ng-messages="editHolidayForm.holiday_date.$error" ng-if='editHolidayForm.holiday_date.$dirty  || editHolidayForm.$submitted' role="alert">
					<span ng-message="required" class="error">Holiday date is required</span>
				</span>
			</div>
			
			<div class="col-xs-2">
				<label for="holiday_status">Status<em class="asteriskRed">*</em></label>
				<select
					class="form-control"
					name="holiday_status"
					id="holiday_status"
					ng-model="edit_holiday.holiday_status"
					ng-options="status.name for status in statusList track by status.id"
					ng-required="true">
					<option value="">Select Status</option>
				</select>
				<span ng-messages="editHolidayForm.holiday_status.$error" ng-if='editHolidayForm.holiday_status.$dirty  || editHolidayForm.$submitted' role="alert">
					<span ng-message="required" class="error">Status is required</span>
				</span>
			</div>
			
			<div class="col-xs-3">
				<label for="submit">{{ csrf_field() }}</label>
				<input type="hidden" name="holiday_id" ng-model="edit_holiday.holiday_id" ng-value="holiday_id">
				<button title="Update" ng-disabled="editHolidayForm.$invalid" type='button' class='mT26 btn btn-primary  btn-sm' ng-click='updateHoliday()' > Update </button>
				<button title="Close" type='button' class='mT26 btn btn-default  btn-sm' ng-click='showAddForm()'>Close</button>
			</div>
		</div>
	</form>	
</div>