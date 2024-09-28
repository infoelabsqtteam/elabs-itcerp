<form method="POST" role="form" id="erpSaveTestParamResultReportForm" name="erpSaveTestParamResultReportForm" novalidate>	
	
	<!-- part C div start here -->
	<div class="container-fluid pdng-20 botm-table" id="foorPartC" >
		
		<!-- Heading -->
		<div class="col-md-12 col-xs-12 botm-row mrgn_20 dis_flex" style="padding:0">
			<div class="col-md-12 col-xs-12 bord">
				<div class="col-sm-12 col-xs-12 report upr-case p0 m0">TEST RESULTS</div>
			</div>
		</div>
		<!-- /Heading -->

		<!-- Table Head -->
		<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
			<div class="col-md-1 col-xs-1 bord fontbd">S.No.</div>
			<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord fontbd' : 'col-md-3 col-xs-3 bord fontbd']]">TEST PARAMETER</div>
			<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">INSTRUMENT</div>
			<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">METHOD</div>
			<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">REQUIREMENT</div>
			<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">DETECTOR</div>
			<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">COLUMN</div>
			<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">RUNNING TIME</div>
			<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">INSTANCE</div>
			<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">NO. OF INJECTIONS</div>
			<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">DATE OF START OF ANALYSIS</div>
			<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">DATE OF COMPLETION OF ANALYSIS</div>
			<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">RESULT</div>
		</div>
		<!-- /Table Head -->

		<!-- Table Body -->
		<div ng-repeat="categoryParameters in orderParametersList">
			
			<!-- Category Detail -->
			<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
				<div class="col-md-1 col-xs-1 bord fontbd"><b>[[$index+1]].</b></div>
				<div class="col-md-11 col-xs-11 bord">
					<div class="col-sm-12 col-xs-12" ng-if="categoryParameters.categoryId != '7'">
						<div class="col-sm-12 col-xs-12 text-left"><b>[[categoryParameters.categoryName]]</b></div>
					</div>
					<div class="col-sm-12 col-xs-12" ng-if="categoryParameters.categoryId == '7'">
						<div class="col-sm-4 col-xs-4 text-left"><b>[[categoryParameters.categoryName]]</b></div>
						<div class="col-sm-8 col-xs-8 text-right">
							<div class="col-sm-10 col-xs-10 text-left color-green fontbd font12 mT5">TO COPY RESULT IN ALL TEST PARAMETERS,ENTER THE VALUE IN FIRST TEST PARAMETERS AND CLICK ON COPY BUTTON</div>
							<div class="col-sm-2 col-xs-2 text-right"><button type="button" class="btn btn-primary" ng-click="funCopyPesticideResidueResult('copyPesticideResidueResult')">Copy Result</button></div>
						</div>
					</div>
				</div>
			</div>
			<!-- /Category Detail -->

			<!-- Category Wise Test Parameter Detail -->
			<div ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
				
				<!-- If Test Parameter Name is description -->
				<div ng-if="subCategoryParameters.description.length">
					<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
						<div class="col-md-1 col-xs-1 bord">[[subCategoryParameters.charNumber]])</div>
						<div class="col-md-3 col-xs-3 bord text-left">
							<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
							<span ng-if="!subCategoryParameters.test_parameter_name">N/A<span>
						</div>
						<div class="col-md-8 col-xs-8 bord text-justify">
							<input 
								type="text"
								class="form-control [[subCategoryParameters.errorClass]]" 
								id="test_result[[subCategoryParameters.analysis_id]]" 
								name="test_result['[[subCategoryParameters.analysis_id]]']" 
								placeholder="enter test result" 
								value="[[subCategoryParameters.description ? subCategoryParameters.description : '']]" />
							<span ng-if="subCategoryParameters.errorMessage" role="alert">
								<span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
							</span>
							<div ng-if="addOrderReport.hasReportAWUISetting == 1">
								<input type="hidden" id="detector_id_[[subCategoryParameters.analysis_id]]" name="detector_id['[[subCategoryParameters.analysis_id]]']" ng-model="subCategoryParameters.detector_id" ng-value="subCategoryParameters.detector_id">
								<input type="hidden" id="column_id_[[subCategoryParameters.analysis_id]]" name="column_id['[[subCategoryParameters.analysis_id]]']" ng-model="subCategoryParameters.column_id" ng-value="subCategoryParameters.column_id">
								<input type="hidden" id="running_time_id_[[subCategoryParameters.analysis_id]]" name="running_time_id['[[subCategoryParameters.analysis_id]]']" ng-model="subCategoryParameters.running_time_id" ng-value="subCategoryParameters.running_time_id">
								<input type="hidden" id="instance_id_[[subCategoryParameters.analysis_id]]" name="instance_id['[[subCategoryParameters.analysis_id]]']" ng-model="subCategoryParameters.instance_id" ng-value="subCategoryParameters.instance_id">
								<input type="hidden" id="no_of_injection_[[subCategoryParameters.analysis_id]]" name="no_of_injection['[[subCategoryParameters.analysis_id]]']" ng-model="subCategoryParameters.no_of_injection" ng-value="subCategoryParameters.no_of_injection">
								<input type="hidden" id="oaws_ui_setting_id_[[subCategoryParameters.analysis_id]]" name="oaws_ui_setting_id['[[subCategoryParameters.analysis_id]]']" ng-model="subCategoryParameters.oaws_ui_setting_id" ng-value="subCategoryParameters.oaws_ui_setting_id">
								<input type="hidden" id="analysis_start_date_id_[[subCategoryParameters.analysis_id]]" name="analysis_start_date['[[subCategoryParameters.analysis_id]]']" ng-model="subCategoryParameters.analysis_start_date" ng-value="subCategoryParameters.analysis_start_date">
								<input type="hidden" id="analysis_completion_date_id_[[subCategoryParameters.analysis_id]]" name="analysis_completion_date['[[subCategoryParameters.analysis_id]]']" ng-model="subCategoryParameters.analysis_completion_date" ng-value="subCategoryParameters.analysis_completion_date">
							</div>
						</div>
					</div>
				</div>
				<!-- If Test Parameter Name is description -->

				<!-- If Test Parameter Name is other than description -->
				<div ng-if="!subCategoryParameters.description.length">
					
					<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
						
						<div data-title="charNumber" class="col-md-1 col-xs-1 bord">[[subCategoryParameters.charNumber]])</div>
						
						<div data-title="test_parameter_name" class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord text-left' : 'col-md-3 col-xs-3 bord text-left']]">
							<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
							<span ng-if="!subCategoryParameters.test_parameter_name"><span>
						</div>

						<div data-title="equipment_name" class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">
							<span ng-if="subCategoryParameters.equipment_name">[[subCategoryParameters.equipment_name]]</span>
							<span ng-if="!subCategoryParameters.equipment_name"><span>
						</div>

						<div data-title="method_name" class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">
							<span ng-if="subCategoryParameters.method_name">[[subCategoryParameters.method_name]]</span>
							<span ng-if="!subCategoryParameters.method_name"><span>
						</div>

						<div data-title="requirement_from_to" class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">[[subCategoryParameters.requirement_from_to]]</div>

						<div ng-if="addOrderReport.hasReportAWUISetting == 1" data-title="Detector" class="col-md-1 col-xs-1 bord">
							<select
								class="form-control selectbox-sm"
								ng-if="subCategoryParameters.oaws_ui_setting_id"
								ng-init="funEditSetSelectedAWISValue('detector_id_',subCategoryParameters.detector_id,subCategoryParameters.analysis_id)"
								id="detector_id_[[subCategoryParameters.analysis_id]]" 
								name="detector_id['[[subCategoryParameters.analysis_id]]']" 
								ng-model="subCategoryParameters.detector_id"
								ng-value="subCategoryParameters.detector_id"
								ng-options="detectors.name for detectors in subCategoryParameters.detectorOptions track by detectors.id">
								<option value="">Select Detector</option>
							</select>
							<input
								type="hidden"
								ng-if="!subCategoryParameters.oaws_ui_setting_id"
								id="detector_id_[[subCategoryParameters.analysis_id]]" 
								name="detector_id['[[subCategoryParameters.analysis_id]]']" 
								ng-model="subCategoryParameters.detector_id"
								ng-value="subCategoryParameters.detector_id">
						</div>

						<div ng-if="addOrderReport.hasReportAWUISetting == 1" data-title="Column" class="col-md-1 col-xs-1 bord">
							<select
								ng-if="subCategoryParameters.oaws_ui_setting_id"
								class="form-control selectbox-sm"
								ng-init="funEditSetSelectedAWISValue('column_id_',subCategoryParameters.column_id,subCategoryParameters.analysis_id)"
								id="column_id_[[subCategoryParameters.analysis_id]]" 
								name="column_id['[[subCategoryParameters.analysis_id]]']" 
								ng-model="subCategoryParameters.column_id"
								ng-value="subCategoryParameters.column_id"
								ng-options="columns.name for columns in subCategoryParameters.columnOptions track by columns.id">
								<option value="">Select Column</option>
							</select>
							<input
								type="hidden"
								ng-if="!subCategoryParameters.oaws_ui_setting_id"
								id="column_id_[[subCategoryParameters.analysis_id]]" 
								name="column_id['[[subCategoryParameters.analysis_id]]']" 
								ng-model="subCategoryParameters.column_id"
								ng-value="subCategoryParameters.column_id">
						</div>

						<div ng-if="addOrderReport.hasReportAWUISetting == 1" data-title="Running Time" class="col-md-1 col-xs-1 bord">
							<select
								ng-if="subCategoryParameters.oaws_ui_setting_id"
								class="form-control selectbox-sm"
								ng-init="funEditSetSelectedAWISValue('running_time_id_',subCategoryParameters.running_time_id,subCategoryParameters.analysis_id)"
								id="running_time_id_[[subCategoryParameters.analysis_id]]" 
								name="running_time_id['[[subCategoryParameters.analysis_id]]']" 
								ng-model="subCategoryParameters.running_time_id"
								ng-value="subCategoryParameters.running_time_id"
								ng-options="runningtimes.name for runningtimes in subCategoryParameters.runningtimeOptions track by runningtimes.id">
								<option value="">Select Running Time</option>
							</select>
							<input
								type="hidden"
								ng-if="!subCategoryParameters.oaws_ui_setting_id"
								id="running_time_id_[[subCategoryParameters.analysis_id]]" 
								name="running_time_id['[[subCategoryParameters.analysis_id]]']" 
								ng-model="subCategoryParameters.running_time_id"
								ng-value="subCategoryParameters.running_time_id">
						</div>

						<div ng-if="addOrderReport.hasReportAWUISetting == 1" data-title="Instance" class="col-md-1 col-xs-1 bord">
							<select
								ng-if="subCategoryParameters.oaws_ui_setting_id"
								class="form-control selectbox-sm"
								ng-init="funEditSetSelectedAWISValue('instance_id_',subCategoryParameters.instance_id,subCategoryParameters.analysis_id)"
								id="instance_id_[[subCategoryParameters.analysis_id]]" 
								name="instance_id['[[subCategoryParameters.analysis_id]]']" 
								ng-model="subCategoryParameters.instance_id"
								ng-value="subCategoryParameters.instance_id"
								ng-options="instances.name for instances in subCategoryParameters.instanceOptions track by instances.id">
								<option value="">Select Instance</option>
							</select>
							<input
								type="hidden"
								ng-if="!subCategoryParameters.oaws_ui_setting_id"
								id="instance_id_[[subCategoryParameters.analysis_id]]" 
								name="instance_id['[[subCategoryParameters.analysis_id]]']" 
								ng-model="subCategoryParameters.instance_id"
								ng-value="subCategoryParameters.instance_id">
						</div>

						<div ng-if="addOrderReport.hasReportAWUISetting == 1" data-title="No. of Injections" class="col-md-1 col-xs-1 bord">
							<input
								ng-if="subCategoryParameters.oaws_ui_setting_id"	
								type="text"
								class="form-control"
								id="no_of_injection_[[subCategoryParameters.analysis_id]]"
								name="no_of_injection['[[subCategoryParameters.analysis_id]]']"
								placeholder="enter test no. of injection"
								ng-model="subCategoryParameters.no_of_injection"
								ng-value="subCategoryParameters.no_of_injection">
							<input
								type="hidden"
								ng-if="!subCategoryParameters.oaws_ui_setting_id"
								id="no_of_injection_[[subCategoryParameters.analysis_id]]"
								name="no_of_injection['[[subCategoryParameters.analysis_id]]']"
								ng-model="subCategoryParameters.no_of_injection"
								ng-value="subCategoryParameters.no_of_injection">
							<input
								type="hidden"
								id="oaws_ui_setting_id_[[subCategoryParameters.analysis_id]]"
								name="oaws_ui_setting_id['[[subCategoryParameters.analysis_id]]']"
								ng-model="subCategoryParameters.oaws_ui_setting_id"
								ng-value="subCategoryParameters.oaws_ui_setting_id">
						</div>

						<div data-title="analysis_start_date" class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">					
							<div class="col-md-12 col-xs-12">
								<div class="input-group date" data-date-start-date="-300d" data-date-format="dd-mm-yyyy" data-provide="datepicker">
									<input
										type="text"
										readonly
										class="form-control bgWhite"
										id="analysis_start_date_id_[[subCategoryParameters.analysis_id]]"
										name="analysis_start_date['[[subCategoryParameters.analysis_id]]']"
										placeholder="select date"
										ng-value="subCategoryParameters.analysis_start_date">
									<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
									<span ng-if="subCategoryParameters.errorMessage" role="alert">
										<span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
									</span>
								</div>
							</div>							
						</div>

						<div data-title="analysis_completion_date" class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">					
							<div class="col-md-12 col-xs-12">
								<div class="input-group date" data-date-start-date="-300d" data-date-format="dd-mm-yyyy" data-provide="datepicker">
									<input
										type="text"
										readonly
										class="form-control bgWhite"
										id="analysis_completion_date_id_[[subCategoryParameters.analysis_id]]"
										name="analysis_completion_date['[[subCategoryParameters.analysis_id]]']"
										placeholder="select date"
										ng-value="subCategoryParameters.analysis_completion_date">
									<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
									<span ng-if="subCategoryParameters.errorMessage" role="alert">
										<span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
									</span>
								</div>
							</div>							
						</div>
						
						<div data-title="test_result" class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">					
							@if(empty($equipment_type_ids))							
								<div class="col-md-12 col-xs-12">
									<input
										type="text"
										ng-if="categoryParameters.categoryId != '7'"
										class="form-control [[subCategoryParameters.errorClass]]"
										id="test_result[[subCategoryParameters.analysis_id]]"
										name="test_result['[[subCategoryParameters.analysis_id]]']"
										placeholder="enter test result"
										value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
									<input
										type="text"
										ng-if="categoryParameters.categoryId == '7'"
										class="form-control copyPesticideResidueResult [[subCategoryParameters.errorClass]]"
										id="test_result[[subCategoryParameters.analysis_id]]"
										name="test_result['[[subCategoryParameters.analysis_id]]']"
										placeholder="enter test result"
										value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
									<span ng-if="subCategoryParameters.errorMessage" role="alert">
										<span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
									</span>
								</div>
							@else                                                        
								<div class="col-md-12 col-xs-12" ng-if="[[subCategoryParameters.has_employee_equipment_type]] == 1">
									<input
										type="text"
										ng-if="categoryParameters.categoryId != '7'"
										class="form-control [[subCategoryParameters.errorClass]]"
										id="test_result[[subCategoryParameters.analysis_id]]"
										name="test_result['[[subCategoryParameters.analysis_id]]']"
										placeholder="enter test result"
										value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
									<input
										type="text"
										ng-if="categoryParameters.categoryId == '7'"
										class="form-control copyPesticideResidueResult [[subCategoryParameters.errorClass]]"
										id="test_result[[subCategoryParameters.analysis_id]]"
										name="test_result['[[subCategoryParameters.analysis_id]]']"
										placeholder="enter test result"
										value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
									<span ng-if="subCategoryParameters.errorMessage" role="alert">
										<span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
									</span>
								</div>
								<div ng-if="[[subCategoryParameters.has_employee_equipment_type]] == 0"></div>
							@endif
						</div>
					</div>
				</div>
				<!-- If Test Parameter Name is other than description -->

			</div>
			<!-- Category Wise Test Parameter Detail -->

		</div>
		<!-- /Table Body -->

   </div>
   <!-- /part C div end here -->
   
   <!-- part D div start here -->
   <div class="container-fluid pdng-20 botm-table " id="foorPartD">
		<div class="col-md-12 col-xs-12 botm-row  bord" style="padding:0">			
			<!--Save Button-->   
			<div class="col-md-12 col-xs-12  text-center mrgn_20 mT10 mB10">
				<div class="col-sm-4 col-xs-4"></div>
				<div class="col-sm-4 col-xs-4">
					<span ng-if="addOrderReport.status == 3">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" id="test_performed_by" ng-model="report.test_performed_by" name="test_performed_by" ng-value="{{$user_id}}">
						<input type="hidden" id="order_id" ng-model="report.order_id" name="order_id" ng-value="addOrderReport.order_id">
						<button type="submit" class="btn btn-primary" ng-click="funSaveTestParametersResultByTester(divisionID)">Save</button>
					</span>
					<button type="button" class="btn btn-primary" ng-click="backButton()">Back</button>
				</div>
				<div class="col-sm-4 col-xs-4">
					<h6 class="pull-right text-info">Note:&nbsp;For EIC Report, add Level of recovery(LOR) in format RESULT | LOR</h6>
				</div>
			</div>
			<!--Save Button-->
		</div>
	</div>
</form>