<div class="row" ng-show="{{defined('EDIT') && EDIT}}">
	<div class="panel panel-default" ng-hide="editDetectorFormDiv" >
		<div class="panel-body">
			<div class="row header1">
				<strong class="pull-left headerText">Edit Detector</strong>				
			</div>
			<form name='erpEditDetectorForm' id="erpEditDetectorForm" novalidate>
				
				<div class="row">
					<!--Detector Code-->
					<div class="col-xs-2">
						<label for="detector_code">Detector Code<em class="asteriskRed">*</em></label>						   
						<input readonly
							type="text"
							class="form-control"
							ng-model="editDetector.detector_code"
							id="detector_code"
							ng-value="detector_code"
							placeholder="Detector Code" />
					</div>
					<!--/Detector Code-->
					
					<!--Detector Name-->
					<div class="col-xs-2">
						<label for="detector_name">Detector Name<em class="asteriskRed">*</em></label>						   
						<input type="text" class="form-control" 
								ng-model="editDetector.detector_name"
								ng-change="editDetector.detector_desc=editDetector.detector_name"
								name="detector_name" 
								id="detector_name"
								ng-required='true'
								placeholder="Detector Name" />
						<span ng-messages="erpEditDetectorForm.detector_name.$error" ng-if='erpEditDetectorForm.detector_name.$dirty || erpEditDetectorForm.$submitted' role="alert">
							<span ng-message="required" class="error">Detector name is required</span>
						</span>
					</div>
					<!--/Detector Name-->
					
					<!--Detector Description-->
					<div class="col-xs-2">
						<label for="detector_desc">Detector Description<em class="asteriskRed">*</em></label>
						<textarea rows="1"
								class="form-control" 
								ng-model="editDetector.detector_desc"								
								name="detector_desc" 
								id="detector_desc"
								ng-required='true'
								placeholder="Detector Description">
						</textarea>
						<span ng-messages="erpEditDetectorForm.detector_desc.$error" ng-if='erpEditDetectorForm.detector_desc.$dirty  || erpEditDetectorForm.$submitted' role="alert">
							<span ng-message="required" class="error">Detector description is required</span>
						</span>
					</div>
					<!--/Detector Description-->
					
					<!--Equipment Type-->
					<div class="col-xs-2">
						<label for="equipment_type_id" class="outer-lable">
							 <span class="filter-lable">Equipment Type<em class="asteriskRed">*</em></span>
							 <span class="filterCatLink"><a title="Search Equipment Type" ng-hide="searchFilterBtn" href="javascript:;" ng-click="showDropdownSearchFilter()"><i class="fa fa-filter"></i></a> </span>
							 <span ng-hide="searchFilterInput" class="filter-span">
								<input type="text" class="filter-text" placeholder="Enter keyword" ng-model="searchDropdown.equipText"/>
								<button title="Close Filter" type="button" class="close filter-close" ng-click="hideDropdownSearchFilter()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							 </span>	
						</label>								
						<select class="form-control"
								name="equipment_type_id"
								ng-model="editDetector.equipment_type_id.selectedOption"
								id="equipment_type_id"
								ng-required='true'
								ng-options="item.name for item in equipmentTypesList track by item.id">
							<option value="">Select Equipment Type</option>
						</select>
						<span ng-messages="erpEditDetectorForm.equipment_type_id.$error"ng-if='erpEditDetectorForm.equipment_type_id.$dirty || addtestParameterForm.$submitted' role="alert">
							<span ng-message="required" class="error">Select Equipment Type</span>
						</span>
					</div>
					<!--/Equipment Type-->
					
					<!--Parent Product Category-->
					<div class="col-xs-2" ng-init="fungetParentCategory()">																
						<label for="product_category_id" class="outer-lable">Product Section<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="product_category_id"
								id="product_category_id"
								ng-model="editDetector.product_category_id.selectedOption"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required='true'>
							<option value="">Select Product Section</option>
						</select>
						<span ng-messages="erpEditDetectorForm.product_category_id.$error" ng-if='erpEditDetectorForm.product_category_id.$dirty || testStandardForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parent Category is required</span>
						</span>
					</div>
					<!--/Parent Product Category-->
					<!-- detector_status---->
					<div class="col-xs-2">
						<label for="status">Status<em class="asteriskRed">*</em></label>	
						<select class="form-control" 
							ng-required='true'  
							name="status" 
							id="status" 
							ng-options="status.name for status in statusList track by status.id"
							ng-model="editDetector.status.selectedOption">
							<option value="">Select Status</option>
						</select>				   
					
						<span ng-messages="erpEditDetectorForm.status.$error" ng-if='erpEditDetectorForm.status.$dirty  || erpEditDetectorForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>
					<!-- /detector_status---->	
					<!--Update button-->
					<div class="col-xs-2 pull-right">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" name="detector_id" ng-value="editDetector.detector_id" ng-model="editDetector.detector_id">
						<span ng-if="{{defined('EDIT') && EDIT}}" >
							<button type="submit" title="Update" ng-disabled="erpEditDetectorForm.$invalid" class='mT26 btn btn-primary  btn-sm' ng-click='funUpdateDetector()'>Update</button>							
						</span>
						<button title="Close" type='button' class='mT26 btn btn-default btn-sm' ng-click='showAddForm()'>Close</button>
					</div>
					<!--/Update button-->
				</div>
			</form>	
		</div>
	</div>
</div>