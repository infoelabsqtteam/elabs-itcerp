<div ng-model="addHolidayFormDiv" ng-hide="addHolidayFormDiv">
    <div class="row header1">
        <strong class="pull-left headerText">Add Holiday</strong>
    </div>
    <form name='holidayForm' id="holidayForm" novalidate>
        
        <div class="row">
	    
	    <!--Branch -->
	    <div class="col-xs-2 form-group">
		<label for="division_id">Branch<em class="asteriskRed">*</em></label>
		<select class="form-control"
		    name="division_id"
		    id="division_id"
		    ng-model="holiday.division_id"
		    ng-required="true"
		    ng-options="division.name for division in divisionsCodeList track by division.id">
		    <option value="">Select Branch</option>
		</select>
		<span ng-messages="holidayForm.division_id.$error" ng-if="holidayForm.division_id.$dirty || holidayForm.$submitted" role="alert">
		    <span ng-message="required" class="error">Branch is required</span>
		</span>
	    </div>
	    <!--/Branch -->
	
            <div class="col-xs-3">
                <label for="holiday_name">Holiday Name<em class="asteriskRed">*</em></label>
                <input
		    type="text"
		    class="form-control"
		    ng-model="holiday.holiday_name"
		    name="holiday_name"
		    id="holiday_name"
		    ng-required='true'
		    placeholder="Holiday Name" />
                <span ng-messages="holidayForm.holiday_name.$error" ng-if='holidayForm.holiday_name.$dirty  || holidayForm.$submitted' role="alert">
		    <span ng-message="required" class="error">Holiday name is required</span>
                </span>
            </div>
            <div class="col-xs-2">
		<label for="holiday_date">Holiday Date<em class="asteriskRed">*</em></label>
		<div class="input-group date" data-provide="datepicker">
		    <input
			type="text"
			ng-model="holiday.holiday_date"
			class="bgwhite form-control ng-pristine ng-invalid ng-invalid-required ng-touched"
			name="holiday_date"
			id="holiday_date"
			placeholder="Holiday Date"
			ng-required="true"
			required="required"
			aria-required="true"
			aria-invalid="true">
		    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
		</div>
		<span ng-messages="holidayForm.holiday_date.$error" ng-if='holidayForm.holiday_date.$dirty || holidayForm.$submitted' role="alert">
		    <span ng-message="required" class="error">Holiday date is required</span>
		</span>
            </div>
	    <div class="col-xs-2">
                <label for="holiday_status">Status<em class="asteriskRed">*</em></label>
		    <select
			class="form-control"
			name="holiday_status"
			id="holiday_status"
			ng-model="holiday.holiday_status"
			ng-options="status.name for status in statusList track by status.id"
			ng-required="true">
			<option value="">Select Status</option>
		    </select>
                <span ng-messages="holidayForm.holiday_status.$error" ng-if='holidayForm.holiday_status.$dirty  || holidayForm.$submitted' role="alert">
		    <span ng-message="required" class="error">Status is required</span>
                </span>
            </div>
            <div class="col-xs-2">
		<label for="submit">{{ csrf_field() }}</label>
		<button title="Save" ng-disabled="holidayForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='addHoliday()'> Save </button>
		<button type='button' id='reset_button' class=' mT26 btn btn-default' ng-click='resetHoliday()' title="Reset"> Reset </button>
            </div>
        </div>
    </form>
</div>