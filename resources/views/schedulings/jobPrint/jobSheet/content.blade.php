<!-- Table start here -->
<div class="container-fluid pdng-20 botm-table" >
	<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
		<div class="col-md-8 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-5 col-xs-5 upr-case" style="width: 145px;">Sampling Name</div><div class="col-sm-7  col-xs-7 upr-case f_weight"><span>:</span><span ng-if="printOrderReport.sample_description">[[printOrderReport.sample_description]]</span>
			<span ng-if="!printOrderReport.sample_description"></span>
			</div></div>
		</div>
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-6  col-xs-6 upr-case">Sample Code</div><div class="col-sm-6  col-xs-6 upr-case f_weight"><span>:</span><span ng-if="printOrderReport.order_no">[[printOrderReport.order_no]]</span>
			<span ng-if="!printOrderReport.order_no"></span></div></div>
		</div>
		
	</div>
	<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-5 col-xs-5 upr-case">Batch No.</div><div class="col-sm-7  col-xs-7 upr-case f_weight"><span>:</span><span ng-if ="printOrderReport.batch_no">[[printOrderReport.batch_no]]</span>
			<span ng-if="!printOrderReport.batch_no"></span>
			</div></div>
		</div>
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4  col-xs-5 upr-case">Batch Size</div><div class="col-sm-8  col-xs-7 upr-case f_weight"><span>:</span><span ng-if ="printOrderReport.batch_no">[[printOrderReport.batch_size]]</span><span ng-if="!printOrderReport.batch_size"></span>
			</div></div>
		</div>
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-6 col-xs-5 upr-case">Sealed/Unsealed</div><div class="col-sm-6  col-xs-7 upr-case f_weight"><span>:</span><span ng-if="printOrderReport.is_sealed==0">Unsealed</span>
			<span ng-if="printOrderReport.is_sealed==1">Sealed</span>
			<span ng-if="printOrderReport.is_sealed==2">Intact</span>
			<span ng-if="!printOrderReport.is_sealed==3"></span></div></div>
		</div>
		
	</div>
	<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-5 col-xs-5 upr-case">Sample Qty</div><div class="col-sm-7  col-xs-7 upr-case f_weight"><span>:</span><span ng-if="printOrderReport.sample_qty">[[printOrderReport.sample_qty]]</span><span ng-if="!printOrderReport.sample_qty"></span></div></div>
		</div>
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4  col-xs-5 upr-case">Mfg Date</div><div class="col-sm-8  col-xs-7 upr-case f_weight"><span>:</span><span ng-if="printOrderReport.mfg_date">[[printOrderReport.mfg_date]]</span>
						<span ng-if="!printOrderReport.mfg_date"></span></div></div>
		</div>
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-6 col-xs-5 upr-case">Sign/Unsign</div><div class="col-sm-6  col-xs-7 upr-case f_weight"><span>:</span><span ng-if="printOrderReport.is_signed==0">Unsigned</span>
				<span ng-if="printOrderReport.is_signed==1">Signed</span>
				<!--<span ng-if="!viewOrder.is_signed"></span>--></div></div>
		</div>
		
	</div>
	<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-5 col-xs-5 upr-case">Brand</div><div class="col-sm-7  col-xs-7 upr-case f_weight"><span>:</span></span<span ng-if="printOrderReport.brand_type">[[printOrderReport.brand_type]]</span>
						<span ng-if="!printOrderReport.brand_type"></span></div></div>
		</div>
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4  col-xs-5 upr-case">Exp. Date</div><div class="col-sm-8  col-xs-7 upr-case f_weight"><span>:</span>
				<span ng-if="printOrderReport.expiry_date">[[printOrderReport.expiry_date]]</span>
				<span ng-if="!printOrderReport.expiry_date"></span></div></div>
		</div>
		<div class="col-md-4 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-6 col-xs-5 upr-case">Party Code if any</div><div class="col-sm-6  col-xs-7 upr-case f_weight"><span>:</span></div></div>
		</div>
		
	</div>
	<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
		<div class="col-md-6 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-5 col-xs-5 upr-case" style="width: 145px;">Packing Mode</div><div class="col-sm-7  col-xs-7 upr-case f_weight"><span>:</span>
				<span ng-if="printOrderReport.packing_mode">[[printOrderReport.packing_mode]]</span>
				<span ng-if="!printOrderReport.packing_mode"></span></div></div>
		</div>
		<div class="col-md-6 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4  col-xs-5 upr-case" style="width: 145px;">Remarks </div><div class="col-sm-8  col-xs-7 upr-case f_weight"><span>:</span><span ng-if="printOrderReport.remarks">[[printOrderReport.packing_mode]]</span>
				<span ng-if="!printOrderReport.remarks"></span></div></div>
		</div>
		
	</div>
	<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
		<div class="col-md-12 col-xs-12 bord">
			<div class="col-sm-12 col-xs-12 "><h3 class="text-center"><span style="font-weight:400">Sample Particulars</span></h3></div>
		</div>
	</div>	
	<div class="col-md-12 col-xs-12 botm-row dis_flex" style="padding:0">
		<div class="col-md-12 col-xs-12 bord">			
			<div class="col-sm-12 col-xs-12 report"><div class="col-md-2 col-sm-5 col-xs-6 upr-case" style="width: 145px;">Testing As Per</div>
				<div class="col-md-10 col-sm-7 col-xs-6 upr-case f_weight"><span>:</span><span ng-if="printOrderReport.test_std_name">[[printOrderReport.test_std_name]]</span>
					<span ng-if="!printOrderReport.test_std_name"></span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
		<div class="col-md-1 col-xs-1 bord upr-case">S.No</div>
		<div class="col-md-3 col-xs-3 bord upr-case">Parameter</div>
		<div class="col-md-2 col-xs-2 bord upr-case">Instruments</div>
		<div class="col-md-2 col-xs-2 bord upr-case">Method</div>
		<div class="col-md-2 col-xs-2 bord upr-case">Claim</div>
		<div class="col-md-2 col-xs-2 bord upr-case">Result</div>
	</div>	
	<div ng-repeat="categoryParameters in printOrderParametersList">
		<div class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
			<div class="col-md-1 col-xs-1 bord upr-case">[[$index+1]].</div>
			<div class="col-md-3 col-xs-3 bord ">
				<div class="col-sm-12 col-xs-12  text-left">
					<b>[[categoryParameters.categoryName]]</b>
				</div>
			</div>
			<div class="col-md-8 col-xs-8 bord ">
				<div class="col-sm-12 col-xs-12  text-left">
						[[(categoryParameters.categoryName | lowercase) =='assay master' ? printOrderReport.header_note : '' ]]
				</div>
			</div>
			
		</div>
		<div ng-repeat="subCategoryParameters in categoryParameters.categoryParams track by $index">
			<div ng-if = "!subCategoryParameters.description.length" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
			
				<div class="col-md-1 col-xs-1 bord"></div>
				<div class="col-md-3 col-xs-3 bord text-left">
					<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
					<span ng-if="!subCategoryParameters.test_parameter_name"></span>
				</div>
				<div class="col-md-2 col-xs-2 bord">
					<span ng-if="(subCategoryParameters.equipment_name | lowercase) == 'n/a' || (!subCategoryParameters.equipment_name)"></span>
					<span ng-if="(subCategoryParameters.equipment_name | lowercase) != 'n/a'">[[subCategoryParameters.equipment_name ]]</span>
				</div>
				<div class="col-md-2 col-xs-2 bord">
					<span ng-if="subCategoryParameters.method_name">[[subCategoryParameters.method_name]]</span>
					<span ng-if="!subCategoryParameters.method_name"></span>
				</div>	
				<div class="col-md-2 col-xs-2 bord">
					<span ng-if="subCategoryParameters.claim_value">[[subCategoryParameters.claim_value]]</span>
					<span ng-if="!subCategoryParameters.claim_value"></span>
				</div>	
				<div class="col-md-2 col-xs-2 bord">
					<span ng-if="subCategoryParameters.test_result && printOrderReport.status >= 8">[[subCategoryParameters.test_result]]</span>
					<span ng-if="subCategoryParameters.test_result && printOrderReport.status < 8"></span>
					<span ng-if="!subCategoryParameters.test_result"></span>
				</div>
			</div>
			<div ng-if = "subCategoryParameters.description.length" class="col-md-12 col-xs-12 botm-row text-center dis_flex" style="padding:0">
				<div class="col-md-1 col-xs-1 bord"> </div>
				<div class="col-md-3 col-xs-3 bord text-left">
					<span ng-if="subCategoryParameters.test_parameter_name" ng-bind-html="subCategoryParameters.test_parameter_name"></span>
					<span ng-if="!subCategoryParameters.test_parameter_name"></span>
				</div>
				<div class="col-md-8 col-xs-8 bord">
					<span ng-if="subCategoryParameters.description">[[subCategoryParameters.description]]</span>
					<span ng-if="!subCategoryParameters.description"></span>
				</div>
			</div>
		</div>	
	</div>
</div>