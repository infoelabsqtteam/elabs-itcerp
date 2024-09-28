<form name="orderReportForm" id="orderReportForm" novalidate>	
	<!-- part A div start here -->
	<div class="container-fluid pdng-20 botm-table" id="foorPartA">
		<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
			<div class="col-md-6 col-xs-6 bord">
				<div class="col-sm-12 col-xs-12 report">
					<div class="col-sm-6 col-xs-5 upr-case">Test Report as per IS</div>
					<div class="col-sm-6 col-xs-7 upr-case"><span>:</span>[[viewOrderReport.test_std_name]]</div>
				</div>
			</div>
			<div class="col-md-6 col-xs-6 bord">
				<div class="col-sm-12 col-xs-12 report">
					<div class="col-sm-6 col-xs-5 upr-case">With Amendment No.(s)</div>
					<div class="col-sm-6 col-xs-7 upr-case">
						<span>:</span> 
						<span ng-if="viewOrderReport.with_amendment_no">[[viewOrderReport.with_amendment_no]]</span>
						<span ng-if="!viewOrderReport.with_amendment_no"></span> 
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-12 col-xs-12 bord">
				<div class="col-sm-12 col-xs-12 report"><div class="col-sm-1 col-xs-4 upr-case">PART A</div>
					<div class="col-sm-11 col-xs-8 upr-case"><span>:</span> PARTICULARS OF SAMPLE SUBMITTED </div>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">a)</div>
			<div class="col-md-5 col-xs-5 bord">Nature of Sample</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.sample_description">[[viewOrderReport.sample_description]]</span>
				<span ng-if="!viewOrderReport.sample_description"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">b)</div>
			<div class="col-md-5 col-xs-5 bord">Grade / Variety / Type / Class / Size etc.</div>
			<div class="col-md-6 col-xs-5 bord">				
				<span ng-if="!showGradeType" ng-modal="grade_type">
					<span ng-if="viewOrderReport.grade_type">[[viewOrderReport.grade_type]]</span>
					<span ng-if="!viewOrderReport.grade_type"></span>
				</span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">c)</div>
			<div class="col-md-5 col-xs-5 bord">Brand Name</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.brand_type">[[viewOrderReport.brand_type]]</span>
				<span ng-if="!viewOrderReport.brand_type"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">d)</div>
			<div class="col-md-5 col-xs-5 bord">Declared Values,if any</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.declared_values">[[viewOrderReport.declared_values]]</span>
				<span ng-if="!viewOrderReport.declared_values"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">e)</div>
			<div class="col-md-5 col-xs-5 bord">Code No.</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.barcode"><img ng-src="[[viewOrderReport.barcode]]"></span>
				<span ng-if="!viewOrderReport.barcode"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">f)</div>
			<div class="col-md-5 col-xs-5 bord">Batch Number</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.batch_no">[[viewOrderReport.batch_no]]</span>
				<span ng-if="!viewOrderReport.batch_no"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">g)</div>
			<div class="col-md-5 col-xs-5 bord">D.O.M</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.mfg_date">[[viewOrderReport.mfg_date]]</span>
				<span ng-if="!viewOrderReport.mfg_date"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">h)</div>
			<div class="col-md-5 col-xs-5 bord">Date of Expiry</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.expiry_date">[[viewOrderReport.expiry_date]]</span>
				<span ng-if="!viewOrderReport.expiry_date"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">i)</div>
			<div class="col-md-5 col-xs-5 bord">Sample Quantity</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.sample_qty">[[viewOrderReport.sample_qty]]</span>
				<span ng-if="!viewOrderReport.sample_qty"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">j)</div>
			<div class="col-md-5 col-xs-5 bord">Batch Size</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.batch_size">[[viewOrderReport.batch_size]]</span>
				<span ng-if="!viewOrderReport.batch_size"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">k)</div>
			<div class="col-md-5 col-xs-5 bord">Mode of Packing</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.packing_mode">[[viewOrderReport.packing_mode]]</span>
				<span ng-if="!viewOrderReport.packing_mode"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">l)</div>
			<div class="col-md-5 col-xs-5 bord">Date of Receipt</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.order_date">[[viewOrderReport.order_date]]</span>
				<span ng-if="!viewOrderReport.order_date"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">m)</div>
			<div class="col-md-5 col-xs-5 bord">Date of Start</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.analysis_start_date">[[viewOrderReport.analysis_start_date]]</span>
				<span ng-if="!viewOrderReport.analysis_start_date"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">n)</div>
			<div class="col-md-5 col-xs-5 bord">Date of Completion</div>
			<div class="col-md-6 col-xs-5 bord">				
				<span ng-if="viewOrderReport.orderAmendStatus && viewOrderReport.is_amended_no">[[viewOrderReport.reviewing_date ? viewOrderReport.reviewing_date : viewOrderReport.finalizing_date]]</span>
				<span ng-if="viewOrderReport.analysis_completion_date && !viewOrderReport.is_amended_no" ng-bind="viewOrderReport.analysis_completion_date"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">o)</div>
			<div class="col-md-5 col-xs-5 bord">BIS Seal (Intact/Not Intact/Unsealed)</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.is_sealed==0">Unsealed</span>
				<span ng-if="viewOrderReport.is_sealed==1">Sealed</span>
				<span ng-if="viewOrderReport.is_sealed==2">Intact</span>
				<span ng-if="!viewOrderReport.is_sealed==3"></span></div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">p)</div>
			<div class="col-md-5 col-xs-5 bord">IO'S Signature (Signed/Unsigned)</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.is_signed==0">Unsigned</span>
				<span ng-if="viewOrderReport.is_signed==1">Signed</span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">q)</div>
			<div class="col-md-5 col-xs-5 bord">Any Other Information</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.remarks">[[viewOrderReport.remarks]]</span>
				<span ng-if="!viewOrderReport.remarks"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">r)</div>
			<div class="col-md-5 col-xs-5 bord">Submitted By</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.customer_name">[[viewOrderReport.customer_name]] - [[viewOrderReport.city_name]] ( [[viewOrderReport.state_name]] )</span>
				<span ng-if="!viewOrderReport.customer_name"></span>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">s)</div>
			<div class="col-md-5 col-xs-5 bord">Manufactured By</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.manufactured_by">[[viewOrderReport.manufactured_by]]</span>
				<span ng-if="!viewOrderReport.manufactured_by"></span></div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-1 col-xs-2 bord">t)</div>
			<div class="col-md-5 col-xs-5 bord">Supplied By</div>
			<div class="col-md-6 col-xs-5 bord">
				<span ng-if="viewOrderReport.supplied_by">[[viewOrderReport.supplied_by]]</span>
				<span ng-if="!viewOrderReport.supplied_by"></span>
			</div>
		</div>
	</div>
	<!-- /part A div end here -->
	
	<!-- part B div start here -->
	<div class="container-fluid pdng-20 botm-table" id="foorPartB">
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-12 col-xs-12 bord">
				<div class="col-sm-12 col-xs-12 report"><div class="col-sm-1 col-xs-4 upr-case">Part B</div><div class="col-sm-11  col-xs-8 upr-case"><span>:</span> SUPPLIMENTARY INFORMATIONS</div></div>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 dis_flex botm-row" style="padding:0">
			<div class="col-md-12 col-xs-12 bord">
				<div class="col-sm-12 col-xs-12 report border-bottom-dotted" style="font-weight:normal!important">
					<div class="col-sm-1 col-xs-1">a</div>
					<div class="col-sm-5 col-xs-8">Reference to sampling procedure, whenever applicable.</div>
					<div class="col-sm-6 col-xs-3">
						<div class="float-left"> : </div> 
						<div class="left-nav mL10">
							<span ng-if="viewOrderReport.ref_sample_value_name">[[viewOrderReport.ref_sample_value_name]]</span>
							<span ng-if="!viewOrderReport.ref_sample_value_name">-</span>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-xs-12 report border-bottom-dotted" style="font-weight:normal!important">
					<div class="col-sm-1 col-xs-1">b</div>
						<div class="col-sm-5 col-xs-8">
							Supporting documents for the measurement taken and results derived like graphs, tables, sketches and/or photographs as appropriate to test reports, if any
						</div>
					<div class="col-sm-6 col-xs-3"><span class="float-left"> : </span> 
						<div class="left-nav mL10">
							<span ng-if="viewOrderReport.result_drived_value_name">[[viewOrderReport.result_drived_value_name]]</span>
							<span ng-if="!viewOrderReport.result_drived_value_name">-</span>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-xs-12 report " style="font-weight:normal!important">
					<div class="col-sm-1 col-xs-1">c</div>
					<div class="col-sm-5 col-xs-8">Deviation from the test methods as prescribed in relevent ISS/WORK instruments, if any</div>
					<div class="col-sm-6 col-xs-3">
						<div class="float-left"> : </div> 
						<div class="left-nav mL10">
							<span ng-if="viewOrderReport.deviation_value_name">[[viewOrderReport.deviation_value_name]]</span>
							<span ng-if="!viewOrderReport.deviation_value_name">-</span>
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /part Bdiv start here -->
	
	<!-- part C div start here -->
	<div class="container-fluid pdng-20 botm-table mT30" id="foorPartC">
		<div class="col-md-12 col-xs-12 botm-row mrgn_20 dis_flex" style="padding:0">
			<div class="col-md-12 col-xs-12 bord">
				<div class="col-sm-12 col-xs-12 report"><div class="col-sm-1 col-xs-2 upr-case">Part C</div><div class="col-sm-11  col-xs-10 upr-case"><span>:</span>TEST RESULTS </div></div>
			</div>
		</div>
		<div class="col-md-12 col-xs-12 botm-row dis_flex " style="padding:0">
			<div class="col-md-12 col-xs-1 mB5"> <b>Test Details</b> :&nbsp;&nbsp;
				<span ng-if="viewOrderReport.header_note">[[viewOrderReport.header_note | capitalize]]</span>
				<span ng-if="!viewOrderReport.header_note"></span>
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
		
		<!--ORDER PARAMETERS LIST-->
		<div ng-if="orderParametersList">
			
			<!--DESCRIPTION WISE PARAMETER LIST-->
			<div ng-if="orderParametersList.descriptionWiseParameterList.length" ng-repeat="descriptionWiseParameters in orderParametersList.descriptionWiseParameterList">
				
				<!--CATEGORY NAME-->
				<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
					<div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
					<div class="col-md-11 col-xs-11 bord">
						<div class="col-sm-12 col-xs-12 text-left">
							<span class="text-left"><strong>[[descriptionWiseParameters.categoryName]]</strong></span>
							<span class="text-left" ng-if="descriptionWiseParameters.categoryNameSymbol"><strong ng-bind-html="descriptionWiseParameters.categoryNameSymbol"></strong></span>
						</div>
					</div>
				</div>
				<!--/CATEGORY NAME-->
			
				<div ng-repeat="descriptionWiseParameterObj in descriptionWiseParameters.categoryParams track by $index">
					<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
						<div class="col-md-1 col-xs-1 bord">[[descriptionWiseParameterObj.charNumber]])</div>
						<div class="col-md-3 col-xs-3 bord text-left">
							<span ng-if="descriptionWiseParameterObj.test_parameter_name" ng-bind-html="descriptionWiseParameterObj.test_parameter_name"></span>
							<span ng-if="!descriptionWiseParameterObj.test_parameter_name"><span>
						</div>
						<div class="col-md-8 col-xs-8 bord text-justify">
							<span ng-if="descriptionWiseParameterObj.description">[[descriptionWiseParameterObj.description]]</span>
							<span ng-if="!descriptionWiseParameterObj.description"><span>
						</div>
					</div>
				</div>
			</div>
			<!--/DESCRIPTION WISE PARAMETER LIST-->
			
			<!--CATEGORY WISE PARAMETER LIST-->
			<div ng-if="orderParametersList.categoryWiseParameterList.length" ng-repeat="categoryWiseParameters in orderParametersList.categoryWiseParameterList">
				
				<!--CATEGORY NAME-->
				<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
					<div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
					<div class="col-md-11 col-xs-11 bord">
						<div class="col-sm-12 col-xs-12 text-left">
							<span class="text-left"><strong>[[categoryWiseParameters.categoryName]]</strong></span>
							<span class="text-left" ng-if="categoryWiseParameters.categoryNameSymbol"><strong ng-bind-html="categoryWiseParameters.categoryNameSymbol"></strong></span>
						</div>
					</div>
				</div>
				<!--/CATEGORY NAME-->
				
				<div ng-repeat="subCategoryParameters in categoryWiseParameters.categoryParams track by $index">
					<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
						<div class="col-md-1 col-xs-1 bord">[[subCategoryParameters.charNumber]])</div>
						<div class="col-md-3 col-xs-3 bord text-left">
							<span ng-if="subCategoryParameters.test_parameter_name" class="txt-left" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
							<span ng-if="subCategoryParameters.non_nabl_parameter_symbol" ng-bind-html="subCategoryParameters.non_nabl_parameter_symbol"></span>
						</div>
						<div class="col-md-2 col-xs-2 bord">
							<span ng-if="subCategoryParameters.equipment_name">[[subCategoryParameters.equipment_name]]</span>
							<span ng-if="!subCategoryParameters.equipment_name"><span>
						</div>
						<div class="col-md-2 col-xs-2 bord">
							<span ng-if="subCategoryParameters.method_name">[[subCategoryParameters.method_name]]</span>
							<span ng-if="!subCategoryParameters.method_name"><span>
						</div>
						<div class="col-md-2 col-xs-2 bord">[[subCategoryParameters.requirement_from_to]]</div>
						<div class="col-md-2 col-xs-2 bord">
							<span ng-if="subCategoryParameters.test_result">[[subCategoryParameters.test_result]]</span>
							<span ng-if="!subCategoryParameters.test_result"><span>
						</div>
					</div>
				</div>
			</div>
			<!--/CATEGORY WISE PARAMETER LIST-->
			
			<!--DISCIPLINE WISE PARAMETER LIST-->
			<div ng-if="orderParametersList.disciplineWiseParametersList.length" ng-repeat="disciplineWiseParameters in orderParametersList.disciplineWiseParametersList">
				
				<!--DISCIPLINE NAME-->
				<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0" ng-if="disciplineWiseParameters.disciplineHdr.discipline_name">
					<div class="col-md-1 col-xs-1 bord"></div>
					<div class="col-md-11 col-xs-11 bord">
						<div class="col-sm-12 col-xs-12 text-left p0"><strong>Discipline&nbsp;:&nbsp;[[disciplineWiseParameters.disciplineHdr.discipline_name]]</strong></div>
					</div>
				</div>
				<!--/DISCIPLINE NAME-->
				
				<!--GROUP NAME-->
				<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0" ng-if="disciplineWiseParameters.disciplineHdr.group_name">
					<div class="col-md-1 col-xs-1 bord"></div>
					<div class="col-md-11 col-xs-11 bord">
						<div class="col-sm-12 col-xs-12 text-left p0"><strong>Group&nbsp;:&nbsp;[[disciplineWiseParameters.disciplineHdr.group_name]]</strong></div>
					</div>
				</div>
				<!--/GROUP NAME-->
				
				<!--PARAMETER LIST-->
				<div ng-repeat="categoryWiseParameters in disciplineWiseParameters.disciplineDtl">
					
					<!--CATEGORY NAME-->
					<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
						<div class="col-md-1 col-xs-1 bord"><b>[[$index+1]].</b></div>
						<div class="col-md-11 col-xs-11 bord">
							<div class="col-sm-12 col-xs-12 text-left p0">
								<span class="text-left"><strong>[[categoryWiseParameters.categoryName]]</strong></span>
								<span class="text-left" ng-if="categoryWiseParameters.categoryNameSymbol"><strong ng-bind-html="categoryWiseParameters.categoryNameSymbol"></strong></span>
							</div>
						</div>
					</div>
					<!--/CATEGORY NAME-->
					
					<div ng-repeat="subCategoryParameters in categoryWiseParameters.categoryParams track by $index">
						<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
							<div class="col-md-1 col-xs-1 bord">[[subCategoryParameters.charNumber]])</div>
							<div class="col-md-3 col-xs-3 bord text-left">
								<span ng-if="subCategoryParameters.test_parameter_name" class="txt-left" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
								<span ng-if="subCategoryParameters.non_nabl_parameter_symbol" ng-bind-html="subCategoryParameters.non_nabl_parameter_symbol"></span>
							</div>
							<div class="col-md-2 col-xs-2 bord">
								<span ng-if="subCategoryParameters.equipment_name">[[subCategoryParameters.equipment_name]]</span>
								<span ng-if="!subCategoryParameters.equipment_name"><span>
							</div>
							<div class="col-md-2 col-xs-2 bord">
								<span ng-if="subCategoryParameters.method_name">[[subCategoryParameters.method_name]]</span>
								<span ng-if="!subCategoryParameters.method_name"><span>
							</div>
							<div class="col-md-2 col-xs-2 bord">[[subCategoryParameters.requirement_from_to]]</div>
							<div class="col-md-2 col-xs-2 bord">
								<span ng-if="subCategoryParameters.test_result">[[subCategoryParameters.test_result]]</span>
								<span ng-if="!subCategoryParameters.test_result"><span>
							</div>
						</div>
					</div>
				</div>
				<!--/PARAMETER LIST-->				
			</div>
			<!--DISCIPLINE WISE PARAMETER LIST-->
		</div>
		<!--/ORDER PARAMETERS LIST-->
		
		<div class="col-md-12 col-xs-12 bord-remark" ng-if="orderParametersList.length && viewOrderReport.order_nabl_os_remark_scope">Remarks&nbsp;:&nbsp;'&#x2A;' represents categories/test parameters not covered under NABL&nbsp;&#124;&nbsp;'&#x2A;&#x2A;' represents outsource sample</div>
	</div>
	<!-- /part C div end here -->
   
	<!-- part D div start here -->
	<div class="container-fluid pdng-20 botm-table" id="foorPartD">
		<div class="col-md-12 col-xs-12 botm-row  bord" style="padding:0">
			<!-------------------------REPORT NEED MODIFICATION NOTE------------------------------->
			<div ng-if="displayNote" class="col-md-12 col-xs-12 mrgn_20 mT20 mB20" ng-if="viewOrderReport.note.length">
				<div class="col-sm-1 col-xs-4 uprcase">NOTE :</div>
				<div class="col-sm-10 col-xs-8">
				     <span ng-if="viewOrderReport.note" ng-bind-html="viewOrderReport.note"></span>
				</div>
			</div>	
			<!-------------------------/REPORT NEED MODIFICATION NOTE------------------------------->	
			
			<!-------------------------REPORT REMARKS------------------------------->
			<div class="col-md-12 col-xs-12 mrgn_20 mT20 mB20">
				<div class="col-sm-2 col-xs-2 upr-case">Part D : REMARKS :</div>
				<div class="col-sm-8 col-xs-8">
				     <span ng-if="viewOrderReport.remark_value">[[viewOrderReport.remark_value]]</span>
				     <span ng-if="!viewOrderReport.remark_value"></span>
				</div>
				<div class="col-md-12 col-xs-12 mT20" ng-if="viewOrderReport.remark_value && viewOrderReport.isBackDateBookingAllowed == 1">
					<div class="col-sm-12 col-xs-12" ng-init="funGetReviewReportDateInit()">
						<div class="col-sm-3 col-xs-3 upr-case">Select Date<em class="asteriskRed">*</em></div>
						<div class="col-sm-9 col-xs-9">
							<input type="text" id="report_date" ng-model="viewOrderReport.report_date" ng-value="viewOrderReport.report_date" name="report_date" class="form-control bgWhite" placeholder="Report Date" autocomplete="off">
							<input type="hidden" id="back_report_date" ng-model="viewOrderReport.isBackDateBookingAllowed" ng-value="viewOrderReport.isBackDateBookingAllowed" name="back_report_date">
						</div>
					</div>
				</div>
			</div>
			<!-------------------------/REPORT REMARKS------------------------------->
			
			<!-------------------------REPORT NOTE------------------------------->
			<div ng-if="viewOrderReport.note_value" class="col-md-12 col-xs-12 mrgn_20 mT20 mB20">
				<div class="col-sm-2 col-xs-2 upr-case">NOTE :</div>
				<div class="col-sm-8 col-xs-8">
				     <span ng-if="viewOrderReport.note_value">[[viewOrderReport.note_value]]</span>
				     <span ng-if="!viewOrderReport.note_value"></span>
				</div>
			</div>
			<!-------------------------/REPORT NOTE------------------------------->
			<div class="col-md-12 col-xs-12 mT30 mB20">
				<div class="col-sm-4 col-xs-4 text-left mrgn_20 mT30" ng-if="viewOrderReport.status >= 4 && !viewOrderReport.report_microbiological_name">
					<h5>
						<b>Report Date</b> :
						<span ng-if="!viewOrderReport.report_date">  </span>
						<span ng-if="viewOrderReport.report_date"> [[viewOrderReport.report_date]]</span>
					</h5>
				</div>
				<div class="col-sm-4 col-xs-4 text-center mrgn_20 mT30" ng-if="viewOrderReport.status >= 4 && viewOrderReport.report_microbiological_name">
					<p ng-if="viewOrderReport.report_microbiological_sign" style="width:100%;"><img ng-if="viewOrderReport.report_microbiological_sign_path" height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[viewOrderReport.report_microbiological_name]]" ng-src="[[viewOrderReport.report_microbiological_sign_path]]" /></p>
					<h5 ng-if="viewOrderReport.incharge_reviewing_date">[[viewOrderReport.incharge_reviewing_date]]</h5>
					<p ng-if="viewOrderReport.report_microbiological_name" style="font-size:10px;width:100%;">[[viewOrderReport.report_microbiological_name]]</p>				
					<h4 ng-if="viewOrderReport.report_microbiological_name">[Tech Manager (Micro.)]</h4>
				</div>
				<div class="col-sm-4 col-xs-4 text-center mrgn_20 mT30">
					<p ng-if="viewOrderReport.status >= 6" style="width:100%;"><img height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[orderTrackRecord.reviewing.user_signature]]" ng-src="{{SITE_URL.SIGN_PATH}}[[orderTrackRecord.reviewing.user_signature]]"/></p>
					<p ng-if="viewOrderReport.status >= 6" style="font-size:10px;width:100%;">[[orderTrackRecord.reviewing.username]]</p>				
					<h5 ng-if="viewOrderReport.status >= 6">[[viewOrderReport.reviewing_date]]</h5>
					<h4 ng-if="viewOrderReport.status >= 6">Reviewer</h4>
				</div>
				<div class="col-sm-4 col-xs-4 text-right mrgn_20 mT30">
					<p ng-if="viewOrderReport.status >= 7" style="width:100%;"><img height="40px" width="100px" style="margin:-40px 0px 0px; padding-left: 15px;" ng-alt="[[orderTrackRecord.finalizing.user_signature]]" ng-src="{{SITE_URL.SIGN_PATH}}[[orderTrackRecord.finalizing.user_signature]]"/></p>
					<h5 ng-if="viewOrderReport.status >= 7">[[viewOrderReport.finalizing_date]]</h5>
					<h4 ng-if="viewOrderReport.status >= 7">[[orderTrackRecord.finalizing.username]]</h4>
					<h4 ng-if="viewOrderReport.status >= 7">[Tech Manager]</h4>
				</div>
			</div>
			
			<div class="col-md-12 col-xs-12 hideContentOnPdf mT20 mB10">
				<div class="col-sm-12 col-xs-12 text-center mrgn_20">
					<input type="hidden" name="order_id" ng-modal="viewOrderReport.order_id" ng-value="viewOrderReport.order_id">
					<input type="hidden" name="order_status" ng-modal="viewOrderReport.status" ng-value="viewOrderReport.status">
					<!----------btns according to current status of report---------------->
					
							<!----Action for update Report status By FINALIZER---->
							 <span ng-if="{{defined('IS_FINALIZER') && IS_FINALIZER}}">
								<span ng-if="viewOrderReport.status==6">
									<input type="submit" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'ConfirmReportByQADepartment','Are you sure you want to confirm this record?')" value="Confirm" class="btn btn-primary">
									<button type="submit" data-toggle="modal" ng-click="funOpenNeedModificationModal('ByQaDept')" class="btn btn-primary hideNeedModificationOnPdf">Need Modification</button>
								</span>
							 </span>
							 <span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
								<span ng-if="viewOrderReport.status==6">
									<input type="submit" ng-click="funConfirmReportStatus(viewOrderReport.order_id,'ConfirmReportByQADepartment','Are you sure you want to confirm this record?')" value="Confirm" class="btn btn-primary">
									<button type="submit" data-toggle="modal" ng-click="funOpenNeedModificationModal('ByQaDept')" class="btn btn-primary hideNeedModificationOnPdf">Need Modification</button>
								</span>
							 </span>
							<!----Action for update Report status By FINALIZER---->
							
							<!----Action for update Report status By QA APPROVAL---->
							<span ng-if="{{defined('IS_APPROVAL') && IS_APPROVAL}}">
								<span ng-if="viewOrderReport.status==7">
									<input type="submit" ng-click="funOpenNeedModificationModal('generateReportCriteriaId')" value="Generate Report" class="btn btn-primary">
									<button type="submit" data-toggle="modal" ng-click="funOpenNeedModificationModal('ByQaDept')" class="btn btn-primary hideNeedModificationOnPdf">Need Modification</button>
								</span>
								<!----Action for generate pdf Report By QA APPROVAL---->	
								<span ng-if="viewOrderReport.status > 7">
									<input type="submit" ng-click="funOpenNeedModificationModal('generateReportCriteriaId')" value="Download Report" class="btn btn-primary">
									<input type="hidden" name="report_file_name" id="report_file_name" ng-modal="viewOrderReport.report_file_name" ng-value="viewOrderReport.report_file_name">
								</span>
								<!-----/Action for generate pdf Report By QA APPROVAL----->	
							</span>
							<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
								<span ng-if="viewOrderReport.status==7">
									<input type="submit" ng-click="funOpenNeedModificationModal('generateReportCriteriaId')" value="Generate Report" class="btn btn-primary">
									<button type="submit" data-toggle="modal" ng-click="funOpenNeedModificationModal('ByQaDept')" class="btn btn-primary hideNeedModificationOnPdf">Need Modification</button>
								</span>
								<!----Action for generate pdf Report By QA APPROVAL---->	
								<span ng-if="viewOrderReport.status > 7">
									<input type="submit" ng-click="funOpenNeedModificationModal('generateReportCriteriaId')" value="Download Report" class="btn btn-primary">
									<input type="hidden" name="report_file_name" id="report_file_name" ng-modal="viewOrderReport.report_file_name" ng-value="viewOrderReport.report_file_name">
								</span>
								<!-----/Action for generate pdf Report By QA APPROVAL----->	
							</span>
							<!----Action for update Report status By APPROVAL---->						
					
					<!----------/btns according to current status of report---------------->
					<input type="hidden" id="order_no" ng-modal="viewOrderReport.order_no" ng-value="viewOrderReport.order_no">
					<button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button>
				</div>
			</div>
		</div>
	</div>
	@include('sales.templates.building.reports.finalize.needModification')
</form>

<!--generate Report Criteria Popup-->
@include('sales.reports.generateReportCriteriaPopup')
<!--/generate Report Criteria Popup-->