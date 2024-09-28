<div class="container-fluid pdng-20">
	<span class="pull-right pull-custom">
		<button type="button" class="btn btn-primary hideContentOnPdf" ng-click="backButton()">Back</button>
	</span>
	
	<div class="col-sm-12 col-xs-12 dis_flex header-content mT10" ng-bind-html="viewOrderReport.header_content"></div>
	
	<div class="col-sm-12 col-xs-12 dis_flex hideContentOnPdf">	
		<div class="col-sm-4 col-xs-4">
		</div>
		<div class="col-sm-4 col-xs-4" ng-if="viewOrderReport.status==4">
			<span ><h3 class="text-center">ADD TEST REPORT PART(B & D) - [[viewOrderReport.department_name | capitalizeAll]]</h3></span>
		</div>
		<div class="col-sm-4 col-xs-4" ng-if="viewOrderReport.status==5">
			<span ><h3 class="text-center">REVIEW REPORT - [[viewOrderReport.department_name | capitalizeAll]]</h3></span>
		</div>
		<div class="col-sm-4 col-xs-4" ng-if="viewOrderReport.status==6">
			<span ><h3 class="text-center">FINALIZE REPORT - [[viewOrderReport.department_name | capitalizeAll]]</h3></span>
		</div>
		<div class="col-sm-4 col-xs-4" ng-if="viewOrderReport.status==7">
			<span><h3 class="text-center">APPROVE REPORT - [[viewOrderReport.department_name | capitalizeAll]]</h3></span>
		</div>
		<div class="col-sm-4 col-xs-4" ng-if="viewOrderReport.status==8">
			<span ><h3 class="text-center">APPROVED REPORT - [[viewOrderReport.department_name | capitalizeAll]]</h3></span>
		</div>
		<div class="col-sm-4 col-xs-4">
			<span class="text-right"><h2 class="selectCatPlus text-right">Document QF : 2501</h2></span><br><span class="pagenum"></span>
		</div>
	</div>
	
	<div class="col-sm-12 col-xs-12 dis_flex" ng-show="showContentOnPdf">
		<div class="col-sm-4 col-xs-4">
			<span ><h3 class="text-center"></h3></span>
		</div>
		<div class="col-sm-4 col-xs-4">
			<span ><h3 class="text-center">TEST REPORT - [[viewOrderReport.department_name | capitalizeAll]]</h3></span>
		</div>
		<div class="col-sm-4 col-xs-4">
			<span class="text-right"><h2 class="selectCatPlus text-right">Document QF : 2501</h2></span><br><span class="pagenum"></span>
		</div>
	</div>
	
	<div class="col-sm-12 col-xs-12 mB30" style="border:1px solid ">
		<div class="col-sm-6 col-xs-6">
			<p class="report">Issued To :</p>
			<address ng-bind="viewOrderReport.customer_name"></address>
			<address ng-bind="viewOrderReport.customer_address"></address>
			<address>[[viewOrderReport.city_name]] <span ng-if="viewOrderReport.state_name">( [[viewOrderReport.state_name]] )</span> </address> 
		</div>
		<div class="col-sm-6 col-xs-6 bord-left" style="">
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4 col-xs-6"><span ng-if="!viewOrderReport.report_no">Sample Reg. No.</span><span ng-if="viewOrderReport.report_no">Report No.</span></div><div class="col-sm-8 col-xs-6"><span>:</span> <span ng-bind="viewOrderReport.order_no"></span><span ng-if="viewOrderReport.is_amended_no">-[[viewOrderReport.is_amended_no]]</span></div></div>
			<div class="col-sm-12 col-xs-12 report" ng-if="viewOrderReport.nabl_no"><div class="col-sm-4 col-xs-6">NABL ULR No.</div><div class="col-sm-8 col-xs-6"><span>:</span> <span ng-bind="viewOrderReport.nabl_no"></span></div></div>
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4 col-xs-6">Sample Reg. Date</div><div class="col-sm-8 col-xs-6"><span>:</span> <span ng-bind="viewOrderReport.order_date"></span> </div></div>
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4 col-xs-6">Report Date</div><div class="col-sm-8 col-xs-6"><span>:</span> <span ng-bind="viewOrderReport.report_date"></span> </div></div>
			<!--<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4 col-xs-6">Report No.</div><div class="col-sm-8 col-xs-6"><span>:</span>  <span ng-bind="viewOrderReport.report_no"></span><span ng-if="viewOrderReport.is_amended_no">- [[viewOrderReport.is_amended_no]]</span></div></div>-->
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4 col-xs-6">Customer Ref. No.</div><div class="col-sm-8 col-xs-6"><span>:</span><span ng-bind="viewOrderReport.reference_no"></span></div></div>
			<div class="col-sm-12 col-xs-12 report"><div class="col-sm-4 col-xs-6">Letter Dated </div><div class="col-sm-8 col-xs-6"><span>:</span><span ng-bind="viewOrderReport.letter_no"></span></div></div>
		</div>
	</div>
</div>