<div class="container-fluid pdng-20 botm-table">
	<div class="row">
		<form name="orderViewForm" novalidate>
			
			<!--Booking Detail-->
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Booking Date & Time</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
					    <span ng-if="viewOrder.booking_date_time">[[viewOrder.booking_date_time]] </span>
					    <span ng-if="!viewOrder.booking_date_time"></span>
					</div>
				    </div>
				</div>
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Security Code</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
						<span ng-if="viewOrder.barcode"><img height="22px" ng-src="[[viewOrder.barcode]]"></span>
						<span ng-if="!viewOrder.barcode"></span>
					</div>
				    </div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Booking Code</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
					    <span ng-if="viewOrder.order_no">[[viewOrder.order_no]] </span>
					    <span ng-if="!viewOrder.order_no"></span>
					</div>
				    </div>
				</div>
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Due Date</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
					    <span ng-if="viewOrder.expected_due_date">[[viewOrder.expected_due_date]] </span>
					    <span ng-if="!viewOrder.expected_due_date"></span>
					</div>
				    </div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Department</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
					    <span ng-if="viewOrder.department_name">[[viewOrder.department_name]] </span>
					    <span ng-if="!viewOrder.department_name"></span>
					</div>
				    </div>
				</div>
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Dept. Due Date</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
					    <span ng-if="viewOrder.order_dept_due_date">[[viewOrder.order_dept_due_date]] </span>
					    <span ng-if="!viewOrder.order_dept_due_date"></span>
					</div>
				    </div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Priority</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
						<span ng-if="viewOrder.sample_priority_name">[[viewOrder.sample_priority_name]]</span>
						<span ng-if="!viewOrder.sample_priority_name"></span>
					</div>
				    </div>
				</div>
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Report Due Date</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
					    <span ng-if="viewOrder.order_report_due_date">[[viewOrder.order_report_due_date]] </span>
					    <span ng-if="!viewOrder.order_report_due_date"></span>
					</div>
				    </div>
				</div>
			</div>
			<!--/Booking Detail-->
			
			<!--Party Detail-->
			<div class="col-md-12 col-xs-12 botm-row dis_flex mT30" style="padding:0">
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Submitter</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
					    <span ng-if="viewOrder.customer_name">[[viewOrder.customer_name]] </span>
					    <span ng-if="!viewOrder.customer_name"></span>
					</div>
				    </div>
				</div>
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">Quotation No.</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
					    <span ng-if="viewOrder.quotation_no">[[viewOrder.quotation_no]] </span>
					    <span ng-if="!viewOrder.quotation_no"></span>
					</div>
				    </div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-12 bord">
					<div class="col-sm-12 col-xs-12 ">
						<div class="col-sm-5 col-xs-5 upr-case">Submission Type</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.submission_type == '1'">Direct</span>
							<span ng-if="viewOrder.submission_type == '2'">Courier</span>
							<span ng-if="viewOrder.submission_type == '3'">Marketing Executive</span>
							<span ng-if="!viewOrder.submission_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12 ">
					<div class="col-sm-5 col-xs-5 upr-case">PO No. Ref.</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
						<span ng-if="viewOrder.po_no">[[viewOrder.po_no]]</span>
						<span ng-if="!viewOrder.po_no"></span>
					</div>
				    </div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-12 bord">
					<div class="col-sm-12 col-xs-12">
						<div class="col-sm-5 col-xs-5 upr-case">Client Contact</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.contact_name1">[[viewOrder.contact_name1]]</span>
							<span ng-if="!viewOrder.contact_name1"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-12 bord">
				    <div class="col-sm-12 col-xs-12">
					<div class="col-sm-5 col-xs-5 upr-case">Sample Receiving No.</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
						<span ng-if="viewOrder.sample_no">[[viewOrder.sample_no]]</span>
						<span ng-if="!viewOrder.sample_no"></span>
					</div>
				    </div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12">
						<div class="col-sm-5 col-xs-5 upr-case">Sampled by</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.submission_type == '1' || viewOrder.submission_type == '2'"></span>
							<span ng-if="viewOrder.submission_type == '3'">Interstellar Testing Centre Private Limited</span>
							<span ng-if="!viewOrder.submission_type"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-6 bord">
				    <div class="col-sm-12 col-xs-12">
					<div class="col-sm-5 col-xs-5 upr-case">Sampling Date & Time</div>
					<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
						<span ng-if="viewOrder.sampling_date">[[viewOrder.sampling_date]]</span>
						<span ng-if="!viewOrder.sampling_date"></span>
					</div>
				    </div>
				</div>
			</div>
			<!--/Party Detail-->
			
			<!--Sample Detail-->
			<div class="col-md-12 col-xs-12 botm-row dis_flex mT30" style="padding:0">
				<div class="col-md-12 col-xs-12 bord">
					<div class="col-sm-2 col-xs-2 upr-case pL30">Sample Name</div>
					<div class="col-sm-10 col-xs-10 upr-case f_weight pL20"><span class="pL40">:</span>
						<span ng-if="viewOrder.sample_description">[[viewOrder.sample_description]]</span>
						<span ng-if="!viewOrder.sample_description"></span>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12">
						<div class="col-sm-5 col-xs-5 upr-case">Batch No.</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.batch_no">[[viewOrder.batch_no]]</span>
							<span ng-if="!viewOrder.batch_no"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12">
						<div class="col-sm-5 col-xs-5 upr-case">Batch Size</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.batch_size">[[viewOrder.batch_size]]</span>
							<span ng-if="!viewOrder.batch_size"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12">
						<div class="col-sm-5 col-xs-5 upr-case">Sample Quantity</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.sample_qty">[[viewOrder.sample_qty]]</span>
							<span ng-if="!viewOrder.sample_qty"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12">
						<div class="col-sm-5 col-xs-5 upr-case">Brand</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.brand_type">[[viewOrder.brand_type]]</span>
							<span ng-if="!viewOrder.brand_type"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12">
						<div class="col-sm-5 col-xs-5 upr-case">DOM</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.mfg_date">[[viewOrder.mfg_date]]</span>
							<span ng-if="!viewOrder.mfg_date"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12">
						<div class="col-sm-5 col-xs-5 upr-case">Mfg. Lic. No.</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.mfg_lic_no">[[viewOrder.mfg_lic_no]]</span>
							<span ng-if="!viewOrder.mfg_lic_no"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12 ">
						<div class="col-sm-5 col-xs-5 upr-case">DOE</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.expiry_date">[[viewOrder.expiry_date]]</span>
							<span ng-if="!viewOrder.expiry_date"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12 ">
						<div class="col-sm-5 col-xs-5 upr-case">Sealed/Unsealed</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
						    <span ng-if="viewOrder.is_sealed==0">Unsealed</span>
						    <span ng-if="viewOrder.is_sealed==1">Sealed</span>
						    <span ng-if="viewOrder.is_sealed==2">Intact</span>
						    <span ng-if="!viewOrder.is_sealed==3"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12 ">
						<div class="col-sm-5 col-xs-5 upr-case">Packing Mode</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.packing_mode">[[viewOrder.packing_mode]]</span>
							<span ng-if="!viewOrder.packing_mode"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xs-6 bord">
					<div class="col-sm-12 col-xs-12 ">
						<div class="col-sm-5 col-xs-5 upr-case">Sign/Unsign</div>
						<div class="col-sm-7 col-xs-7 upr-case f_weight"><span>:</span>
							<span ng-if="viewOrder.is_signed==0">Unsigned</span>
							<span ng-if="viewOrder.is_signed==1">Signed</span>
						</div>
					</div>
				</div>
			</div>
			<!--/Sample Detail-->
			
			<!--Test Parameter Detail-->
			<div class="col-md-12 col-xs-12 botm-row text-center dis_flex mT30" style="padding:0">
				<div class="col-md-8 col-xs-8 bord text-left upr-case"><strong>Parameter</strong></div>
				<div class="col-md-4 col-xs-4 bord">
					<div class="col-md-12 col-xs-12">
						<div class="col-sm-4 col-xs-4 upr-case text-left">Test Method</div>
						<div class="col-sm-8 col-xs-8 upr-case text-right">ANALYSIS TAT (IN DAYS) : [[viewOrder.maxTatInDayNumber]]</div>
					</div>
				</div>
			</div>
			<div ng-repeat="categoryParameters in orderParametersList track by $index">	
				<div ng-repeat="subCategoryParameters in categoryParameters.categoryParams">
					<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
						<div class="col-md-8 col-xs-8 bord text-left">
							<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
							<span ng-if="!subCategoryParameters.test_parameter_name"><span>
						</div>
						<div class="col-md-4 col-xs-4 bord text-left">
							<span class="pL30" ng-if="subCategoryParameters.method_name">[[subCategoryParameters.method_name]]</span>
							<span class="pL30" ng-if="!subCategoryParameters.method_name"><span>
						</div>
					</div>
				</div>
			</div>
			<!--/Test Parameter Detail-->
		</form>
	</div>
	
	<div class="row">
		
		<div class="col-md-12 col-xs-12 mT30">
			<div class="col-md-4 col-sm-4 col-xs-4 mT30">
				<p class="imgCls" ng-if="viewOrder.user_signature"><img height="40px" width="100px" style="margin:-40px 0 0;" ng-alt="[[viewOrder.user_signature]]" ng-src="{{SITE_URL.SIGN_PATH}}[[viewOrder.user_signature]]"/></p>
				<p class="imgCls" style="float: left;" ng-if="viewOrder.createdByName">[[viewOrder.createdByName]]</p>
				<b style="margin-top: -33px; width: 100%; float: left;">Booking Person</b>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 text-center mT30"></div>
			<div class="col-md-4 col-sm-4 col-xs-4 text-right mT30">
				<h5><b>Checked By</b></h5>
			</div>
		</div>
		
		<div class="col-md-12 col-xs-12 text-center mT30" style="padding:0">
			<div class="col-md-5 col-xs-5 mT30"></div>
			<div class="col-md-7 col-xs-7 text-left mT30">
				<div ng-if="viewOrder.status != '10'" class="col-md-2 col-xs-2 botm-row">
					<span ng-if="{{defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}">
						<input type="button" ng-click="funOpenBootStrapModalPopup('generateJobOrderCriteriaId')" value="Generate Job-Order" class="btn btn-primary">
					</span>
					<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
						<input type="button" ng-click="funOpenBootStrapModalPopup('generateJobOrderCriteriaId')" value="Generate Job-Order" class="btn btn-primary">	
					</span>				
					<!--generate Report Criteria Popup-->
					@include('sales.order_master.generateJobOrderCriteriaPopup')
					<!--/generate Report Criteria Popup-->
				</div>
				<div class="col-md-2 col-xs-2 mL10">
					<button ng-click="backButton()" class="btn btn-primary hideContentOnPdf" type="button">Back</button>
				</div>
			</div>
		</div>
	</div>
</div>