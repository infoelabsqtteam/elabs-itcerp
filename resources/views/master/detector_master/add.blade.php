<div class="row" ng-show="{{defined('ADD') && ADD}}">
	<div class="panel panel-default" ng-hide="addDetectorFormDiv">
		<div class="panel-body" ng-model="addDetectorFormDiv">			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Detector</strong></span>
				<span><button class="pull-right btn btn-primary mT3 mR3" ng-click="showUploadForm()">Upload</button></span>
			</div>			
			<!--Add method form-->
			<form name='erpAddDetectorForm' id="add_method_form" novalidate>							
				<div class="row">					
					<!--Detector Code-->
					<div class="col-xs-2">
						<span class="generate"><a title="Generate Code" href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span>
						<label for="method_code">Detector Code<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" readonly
									ng-model="addDetector.detector_code"
									ng-value="default_detector_code"
									name="detector_code" 
									id="detector_code"
									placeholder="Detector Code" />
							<span ng-messages="erpAddDetectorForm.detector_code.$error" ng-if='erpAddDetectorForm.detector_code.$dirty  || erpAddDetectorForm.$submitted' role="alert">
								<span ng-message="required" class="error">Detector code is required</span>
							</span>
					</div>
					<!--/Detector Code-->
					
					<!--Detector Name-->
					<div class="col-xs-2">
						<label for="method_name">Detector Name<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
									ng-model="addDetector.detector_name"
									ng-change="addDetector.detector_desc=addDetector.detector_name"
									name="detector_name" 
									id="detector_name"
									ng-required='true'
									placeholder="Detector Name" />
							<span ng-messages="erpAddDetectorForm.detector_name.$error" ng-if='erpAddDetectorForm.detector_name.$dirty || erpAddDetectorForm.$submitted' role="alert">
								<span ng-message="required" class="error">Detector name is required</span>
							</span>
					</div>
					<!--/Detector Name-->
					
					<!--Detector Description-->
					<div class="col-xs-2">
						<label for="method_desc">Detector Description<em class="asteriskRed">*</em></label>
							<textarea rows=1 type="text" class="form-control" 
									ng-model="addDetector.detector_desc"
									name="detector_desc" 
									id="detector_desc"
									ng-required='true'
									placeholder="Detector Description" /></textarea>
							<span ng-messages="erpAddDetectorForm.detector_desc.$error" ng-if='erpAddDetectorForm.detector_desc.$dirty  || erpAddDetectorForm.$submitted' role="alert">
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
								ng-model="addDetector.equipment_type_id"
								id="equipment_type_id"
								ng-required='true'
								ng-options="item.id as item.name for item in ( equipmentTypesList | filter:searchDropdown.equipText) track by item.id">
							<option value="">Select Equipment Type</option>
						</select>
						<span ng-messages="addtestParameterForm.equipment_type_id.$error" ng-if='addtestParameterForm.equipment_type_id.$dirty || addtestParameterForm.$submitted' role="alert">
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
								ng-model="addDetector.product_category_id"
								ng-options="item.id as item.name for item in (parentCategoryList | filter:searchProduct.text) track by item.id"
								ng-required='true'>
							<option value="">Select Product Section</option>
						</select>
						<span ng-messages="testStandardForm.product_category_id.$error" ng-if='testStandardForm.product_category_id.$dirty || testStandardForm.$submitted' role="alert">
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
							ng-model="addDetector.selectedOption">
							<option value="">Select Status</option>
						</select>				   
					
						<span ng-messages="addDetector.status.$error" ng-if='addDetector.status.$dirty  || addDetector.$submitted' role="alert">
							<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>
					<!-- /detector_status---->	
					<!--save button-->

					<div class="col-xs-2 pull-right">
						<label for="submit">{{ csrf_field() }}</label>		
						<span ng-if="{{defined('ADD') && ADD}}">
							<button title="Save" ng-disabled="erpAddDetectorForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funAddDetector()'>Save</button>
						<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>

						</span>
					</div>
					<!--/save button-->						
				</div>
			</form>
			<!--Add method form-->
		</div>
	</div>
</div>