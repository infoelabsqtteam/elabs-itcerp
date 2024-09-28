<form method="POST" role="form" id="erpSaveTestParamResultReportForm" name="erpSaveTestParamResultReportForm" novalidate>	
	<div class="container pdng-20 botm-table" style="padding:10px;border: 1px solid;">
		<!-- part C div start here -->
		<div class="container-fluid pdng-20 botm-table" id="foorPartC">
			<!--Main Category Div-->
			<div ng-repeat="categoryParameters in orderParametersList">
				<div ng-if="addOrderReport.stability_note && addOrderReport.product_category_id == '2' && categoryParameters.productCategoryName == addOrderReport.testParametersWithoutSpace" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0;">
					<div class="col-sm-11 col-xs-11">
						<h4 class="text-center">[[addOrderReport.stability_note]]</h4>
					</div>
				</div>
				<div ng-if="addOrderReport.header_note && addOrderReport.product_category_id == '2' && categoryParameters.productCategoryName == addOrderReport.assayParametersWithoutSpace" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding: 0px;">
					<div class="col-sm-11 col-xs-11">
						<h4 class="text-center">[[addOrderReport.header_note]]</h4>
					</div>
				</div>					
				<div ng-if="categoryParameters.categoryName == addOrderReport.testParametersWithSpace" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord fontbd text-upper' : 'col-md-3 col-xs-3 bord fontbd text-upper']]">[[categoryParameters.categoryName]]</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord fontbd' : 'col-md-3 col-xs-3 bord fontbd']]">RESULT</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">LIMIT</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">DATE OF START OF ANALYSIS</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">DATE OF COMPLETION OF ANALYSIS</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">DETECTOR</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">COLUMN</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">RUNNING TIME</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">INSTANCE</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">NO. OF INJECTIONS</div>
				</div>
				<div ng-if="categoryParameters.categoryName == addOrderReport.assayParametersWithSpace" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd text-upper' : 'col-md-2 col-xs-2 bord fontbd text-upper']]">[[categoryParameters.categoryName]]</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">RESULT</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">CLAIM</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-1 col-xs-1 bord fontbd']]">LIMIT</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-1 col-xs-1 bord fontbd']]">METHOD</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">DATE OF START OF ANALYSIS</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">DATE OF COMPLETION OF ANALYSIS</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">DETECTOR</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">COLUMN</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">RUNNING TIME</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">INSTANCE</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">NO. OF INJECTIONS</div>
				</div>
				<div ng-if="categoryParameters.categoryName != addOrderReport.testParametersWithSpace && categoryParameters.categoryName != addOrderReport.assayParametersWithSpace" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord fontbd text-upper' : 'col-md-3 col-xs-3 bord fontbd text-upper']]">[[categoryParameters.categoryName]]</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord fontbd' : 'col-md-3 col-xs-3 bord fontbd']]">RESULT</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">LIMIT</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">DATE OF START OF ANALYSIS</div>
					<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord fontbd' : 'col-md-2 col-xs-2 bord fontbd']]">DATE OF COMPLETION OF ANALYSIS</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">DETECTOR</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">COLUMN</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">RUNNING TIME</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">INSTANCE</div>
					<div ng-if="addOrderReport.hasReportAWUISetting == 1" class="col-md-1 col-xs-1 bord fontbd">NO. OF INJECTIONS</div>
				</div>
				
				<!--Sub Category Div-->
				<div ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">

					<!--Test Parameter Category and its parameter Div-->
					
					<!--Description Category Div-->
					<div ng-if="categoryParameters.categoryName == addOrderReport.testParametersWithSpace && subCategoryParameters.description.length">
						<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-3 col-xs-3 bord fontbd text-left' : 'col-md-3 col-xs-3 bord text-left']]">
								<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
								<span ng-if="!subCategoryParameters.test_parameter_name"><span>
							</div>
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-9 col-xs-9 bord text-justify' : 'col-md-9 col-xs-9 bord text-justify']]">
								<input 
									type="text"
									class="form-control [[subCategoryParameters.errorClass]]"
									id="test_result[[subCategoryParameters.analysis_id]]"
									name="test_result['[[subCategoryParameters.analysis_id]]']"
									placeholder="enter test result"
									value="[[subCategoryParameters.description ? subCategoryParameters.description : '']]">
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
					<!--/Description Category Div-->

					<!--Test Parameter Category Div-->
					<div ng-if="categoryParameters.categoryName == addOrderReport.testParametersWithSpace && !subCategoryParameters.description.length">
						<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord text-left' : 'col-md-3 col-xs-3 bord text-left']]">
								<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
								<span ng-if="!subCategoryParameters.test_parameter_name"><span>
							</div>
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord' : 'col-md-3 col-xs-3 bord']]">
								@if(empty($equipment_type_ids))
									<div>
										<input type="text" 
											class="width_45 form-control [[subCategoryParameters.errorClass]]"
											id="test_result[[subCategoryParameters.analysis_id]]"
											name="test_result['[[subCategoryParameters.analysis_id]]']"
											placeholder="enter test result"
											value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
										<span ng-if="subCategoryParameters.errorMessage" role="alert">
										    <span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
										</span>
									</div>
								@else                                                        
									<div ng-if="[[subCategoryParameters.has_employee_equipment_type]] == 1">
										<input type="text"                                                               
											class="width_45 form-control [[subCategoryParameters.errorClass]]"
											id="test_result[[subCategoryParameters.analysis_id]]"
											name="test_result['[[subCategoryParameters.analysis_id]]']"
											placeholder="enter test result"
											value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
									</div>
									<span ng-if="subCategoryParameters.errorMessage" role="alert">
										<span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
									</span>	
									<span ng-if="[[subCategoryParameters.has_employee_equipment_type]] == 0"></span>
								@endif
							</div>

							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">
								<span ng-if="subCategoryParameters.requirement_from_to">[[subCategoryParameters.requirement_from_to]]
									&nbsp;<span ng-if="subCategoryParameters.claim_value_unit "> [[subCategoryParameters.claim_value_unit | capitalizeAll]]</span>
								</span>
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

						</div>
					</div>
					<!--/Test Parameter Category Div-->

					<!--/Test Parameter Category and its parameter Div-->

					<!--Assay Parameter Category and its parameter Div-->
					
					<!--Assay description Parameter Category Div-->
					<div ng-if="categoryParameters.categoryName == addOrderReport.assayParametersWithSpace && subCategoryParameters.description.length">
						<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-3 col-xs-3 bord fontbd text-left' : 'col-md-3 col-xs-3 bord fontbd text-left']]">
								<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
								<span ng-if="!subCategoryParameters.test_parameter_name"><span>
							</div>
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-9 col-xs-9 bord text-justify' : 'col-md-9 col-xs-9 bord text-justify']]">
								<input type="text"
									class="form-control [[subCategoryParameters.errorClass]]"
									id="test_result[[subCategoryParameters.analysis_id]]"
									name="test_result['[[subCategoryParameters.analysis_id]]']"
									placeholder="enter test result"
									value="[[subCategoryParameters.description ? subCategoryParameters.description : '']]">
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
								</div>
							</div>
						</div>
					</div>
					<!--/Assay description Parameter Category Div-->
					
					<!--Assay Test Parameter Category Div-->
					<div ng-if="categoryParameters.categoryName == addOrderReport.assayParametersWithSpace && !subCategoryParameters.description.length"">
						<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord text-left' : 'col-md-2 col-xs-2 bord text-left']]">
								<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
								<span ng-if="!subCategoryParameters.test_parameter_name"><span>
							</div>
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">
								@if(empty($equipment_type_ids))
									<div>
										<input type="text"
											class="form-control [[subCategoryParameters.errorClass]]"
											id="test_result[[subCategoryParameters.analysis_id]]"
											name="test_result['[[subCategoryParameters.analysis_id]]']"
											placeholder="enter test result"
											value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
										<span ng-if="subCategoryParameters.errorMessage" role="alert">
										    <span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
										</span>
									</div>
								@else
									<div ng-if="[[subCategoryParameters.has_employee_equipment_type]] == 1">
										<input type="text"                                                               
											class="form-control [[subCategoryParameters.errorClass]]"
											id="test_result[[subCategoryParameters.analysis_id]]"
											name="test_result['[[subCategoryParameters.analysis_id]]']"
											placeholder="enter test result"
											value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
										<span ng-if="subCategoryParameters.errorMessage" role="alert">
										    <span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
										</span>
									</div>
									<span ng-if="[[subCategoryParameters.has_employee_equipment_type]] == 0"></span>
								@endif
							</div>
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">
								<span ng-if="subCategoryParameters.claim_value ">
									<span ng-if="subCategoryParameters.claim_value">[[subCategoryParameters.claim_value]]</span>
									<span ng-if="subCategoryParameters.claim_value_unit "> [[subCategoryParameters.claim_value_unit| capitalizeAll]]</span>
								</span>
							</div>
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-1 col-xs-1 bord']]">
								<span ng-if="subCategoryParameters.requirement_from_to">[[subCategoryParameters.requirement_from_to]]
									&nbsp;<span ng-if="subCategoryParameters.claim_value_unit "> [[subCategoryParameters.claim_value_unit | capitalizeAll]]</span>
								</span>
							</div>
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-1 col-xs-1 bord']]">
								<span ng-if="subCategoryParameters.method_name">[[subCategoryParameters.method_name]]</span>
								<span ng-if="!subCategoryParameters.method_name"><span>
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

						</div>
					</div>
					<!--Assay Test Parameter Category Div-->

					<!--/Assay Parameter Category and its parameter Div-->
					
					<!--Other Parameter Category and its Parameter Div-->
					
					<!--Descrption Parameter Div-->	
					<div ng-if="categoryParameters.categoryName != addOrderReport.testParametersWithSpace && categoryParameters.categoryName != addOrderReport.assayParametersWithSpace && subCategoryParameters.description.length">
						<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-3 col-xs-3 bord fontbd text-left' : 'col-md-3 col-xs-3 bord text-left']]">
								<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
								<span ng-if="!subCategoryParameters.test_parameter_name"><span>
							</div>
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-9 col-xs-9 bord text-justify' : 'col-md-9 col-xs-9 bord text-justify']]">
								<input type="text"
									class="form-control [[subCategoryParameters.errorClass]]"
									id="test_result[[subCategoryParameters.analysis_id]]"
									name="test_result['[[subCategoryParameters.analysis_id]]']"
									placeholder="enter test result"
									value="[[subCategoryParameters.description ? subCategoryParameters.description : '']]">
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
								</div>
							</div>
						</div>
					</div>
					<!--/Descrption Parameter Div-->

					<!--Test Parameter Category Div-->
					<div ng-if="categoryParameters.categoryName != addOrderReport.testParametersWithSpace && categoryParameters.categoryName != addOrderReport.assayParametersWithSpace && !subCategoryParameters.description.length"">
						<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord text-left' : 'col-md-3 col-xs-3 bord text-left']]">
								<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
								<span ng-if="!subCategoryParameters.test_parameter_name"><span>
							</div>
							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-2 col-xs-2 bord' : 'col-md-3 col-xs-3 bord']]">
								@if(empty($equipment_type_ids))
									<div>
										<input type="text" 
											class="width_45 form-control [[subCategoryParameters.errorClass]]"
											id="test_result[[subCategoryParameters.analysis_id]]"
											name="test_result['[[subCategoryParameters.analysis_id]]']"
											placeholder="enter test result"
											value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
										<span ng-if="subCategoryParameters.errorMessage" role="alert">
										    <span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
										</span>
									</div>
								@else                                                        
									<div ng-if="[[subCategoryParameters.has_employee_equipment_type]] == 1">
										<input type="text"                                                               
											class="width_45 form-control [[subCategoryParameters.errorClass]]"
											id="test_result[[subCategoryParameters.analysis_id]]"
											name="test_result['[[subCategoryParameters.analysis_id]]']"
											placeholder="enter test result"
											value="[[subCategoryParameters.test_result ? subCategoryParameters.test_result : '']]">
									</div>
									<span ng-if="subCategoryParameters.errorMessage" role="alert">
										<span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
									</span>	
									<span ng-if="[[subCategoryParameters.has_employee_equipment_type]] == 0"></span>
								@endif
							</div>

							<div class="[[addOrderReport.hasReportAWUISetting == 1 ? 'col-md-1 col-xs-1 bord' : 'col-md-2 col-xs-2 bord']]">
								<span ng-if="subCategoryParameters.requirement_from_to">[[subCategoryParameters.requirement_from_to]]
									&nbsp;<span ng-if="subCategoryParameters.claim_value_unit "> [[subCategoryParameters.claim_value_unit | capitalizeAll]]</span>
								</span>
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

						</div>	
					</div>
					<!--Test Parameter Category Div-->

					<!--/Other Parameter Category Parameter Div-->
				</div>
				<!--/Sub Category Div-->
			</div>
			<!--/Main Category Div-->
		</div>
		<!-- part C div start here -->
		   
		<!-- part D div start here -->
		<div class="container-fluid pdng-20 botm-table " id="foorPartD">
			<div class="col-md-12 col-xs-12 botm-row  bord" style="padding:0">		
				<!--Save Button-->   
				<div class="col-md-12 col-xs-12 text-center mrgn_20 mT10 mB10">
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
	</div>
</form>