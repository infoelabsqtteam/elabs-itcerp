<form method="POST" role="form" id="erpSaveTestParamResultReportForm" name="erpSaveTestParamResultReportForm" novalidate>	
	<!-- part C div start here -->
	<div class="container-fluid pdng-20 botm-table" id="foorPartC" >
		<div class="col-md-12 col-xs-12 botm-row mrgn_20 dis_flex" style="padding:0">
			<div class="col-md-12 col-xs-12 bord">
				<div class="col-sm-12 col-xs-12 report"><div class="col-sm-11  col-xs-10 upr-case">TEST RESULTS</div></div>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
			<div class="col-md-1 col-xs-1 bord">S.No</div>
			<div class="col-md-3 col-xs-3 bord">Test Parameter</div>
			<div class="col-md-2 col-xs-2 bord">Inst. Used</div>
			<div class="col-md-2 col-xs-2 bord">Method</div>
			<div class="col-md-2 col-xs-2 bord">Requirement</div>
			<div class="col-md-2 col-xs-2 bord">Result</div>
		</div>

		<div ng-repeat="categoryParameters in orderParametersList">
			<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
				<div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
					<div class="col-md-11 col-xs-11 bord">
						<div class="col-sm-12 col-xs-12" ng-if="categoryParameters.categoryId != '7'">
							<div class="col-sm-12 col-xs-12 text-left"><b>[[categoryParameters.categoryName ]]</b></div>
						</div>
						<div class="col-sm-12 col-xs-12" ng-if="categoryParameters.categoryId == '7'">
							<div class="col-sm-4 col-xs-4 text-left"><b>[[categoryParameters.categoryName ]]</b></div>
							<div class="col-sm-8 col-xs-8 text-right">
								<div class="col-sm-10 col-xs-10 text-left color-green fontbd font12 mT5">TO COPY RESULT IN ALL TEST PARAMETERS,ENTER THE VALUE IN FIRST TEST PARAMETERS AND CLICK ON COPY BUTTON</div>
								<div class="col-sm-2 col-xs-2 text-right"><button type="button" class="btn btn-primary" ng-click="funCopyPesticideResidueResult('copyPesticideResidueResult')">Copy Result</button></div>
							</div>
						</div>
					</div>
			</div>
			<div ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
				<div ng-if = "subCategoryParameters.description.length">
					<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
						<div class="col-md-1 col-xs-1 bord">[[subCategoryParameters.charNumber]])</div>
						<div class="col-md-3 col-xs-3 bord text-left">
							<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
							<span ng-if="!subCategoryParameters.test_parameter_name">N/A<span>
						</div>
						<div class="col-md-8 col-xs-8 bord text-justify">
							<input type="text"
								class="form-control [[subCategoryParameters.errorClass]]"
								id="test_result[[subCategoryParameters.analysis_id]]"
								name="test_result['[[subCategoryParameters.analysis_id]]']"
								placeholder="enter test result"
								value="[[subCategoryParameters.description ? subCategoryParameters.description : '']]">
							<span ng-if="subCategoryParameters.errorMessage" role="alert">
								<span class="error" ng-bind-html="subCategoryParameters.errorMessage"></span>
							</span>
						</div>
					</div>
				</div>
				<div ng-if="!subCategoryParameters.description.length">
					<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
						<div class="col-md-1 col-xs-1 bord">[[subCategoryParameters.charNumber]])</div>
						<div class="col-md-3 col-xs-3 bord text-left">
							<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
							<span ng-if="!subCategoryParameters.test_parameter_name"><span>
						</div>
						<div class="col-md-2 col-xs-2 bord">
							<span ng-if="subCategoryParameters.equipment_name">[[subCategoryParameters.equipment_name]]</span>
							<span ng-if="!subCategoryParameters.equipment_name"><span>
						</div>
						<div class="col-md-2 col-xs-2 bord">
							<span ng-if="subCategoryParameters.method_name">[[subCategoryParameters.method_name]]</span>
							<span ng-if="!subCategoryParameters.method_name"><span>
						</div>
						<div class="col-md-2 col-xs-2 bord">
							[[subCategoryParameters.requirement_from_to]]
						</div>
						<div class="col-md-2 col-xs-2 bord">					
							@if(empty($equipment_type_ids))							
								<div>
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
								<div ng-if="[[subCategoryParameters.has_employee_equipment_type]] == 1">
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
			</div>
		</div>
   </div>
   <!-- /part C div end here -->
   
   <!-- part D div start here -->
   <div class="container-fluid pdng-20 botm-table " id="foorPartD">
		<div class="col-md-12 col-xs-12 botm-row  bord" style="padding:0">			
			<!--Save Button-->   
			<div class="col-md-12 col-xs-12">
				<div class=" col-sm-12 col-xs-12 text-center mrgn_20 mT10 mB10">
					<span ng-if="addOrderReport.status == 3">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" id="test_performed_by" ng-model="report.test_performed_by" name="test_performed_by" ng-value="{{$user_id}}">
						<input type="hidden" id="order_id" ng-model="report.order_id" name="order_id" ng-value="addOrderReport.order_id">
						<button type="submit" class="btn btn-primary" ng-click="funSaveTestParametersResultByTester(divisionID)">Save</button>
					</span>
					<button type="button" class="btn btn-primary" ng-click="backButton()">Back</button>
					<h6 class="pull-right text-info">Note:&nbsp;For EIC Report, add Level of recovery(LOR) in format RESULT | LOR</h6>
				</div>
			</div>
			<!--Save Button-->
		</div>
	</div>
</form>
	