<?php
/********************************************************************************
* Final Report : downloadType : 1
* Draft Report : downloadType : 2
* Print Report : downloadType : 3
* Send Mail to Party : sendMailType : 4
* With Equipment : reportWithEquipment : 5
* Without Equipment : reportWithoutEquipment : 6
* Final Report : With Digital Signature : signatureType : 8
* Final Report : Without Digital Signature : signatureType : 9
* Print Report : With Digital Signature : signatureType : 10
* Print Report : Without Digital Signature : signatureType : 11
* Without Partwise Report : withoutPartwiseReport : 12
* Without limit : reportWithoutLimit : 13
* With Form50 Report : reportWithOutForm50Format : 14
* Discipline and Group Wise Report : reportWithDisciplineGroup : 15
* NABL Code Fully Applicable in one complete Order : reportWithRightLogo : 7,16,17
* With EIC Report FORMAT: reportWithEICFormat (Only Visibile in Food Department): 18
* With Form39 Cosmetic Report for Chennai: reportCosmeticWithForm39Format : 19
* Without Form39 for Pharma Department : reportWithoutForm39Format : 20
* With Form39 Cosmetic Report for Panckulla: reportCosmeticWithForm39Format : 21
*********************************************************************************/
?>
<div class="modal fade" id="generateReportCriteriaId" role="dialog">
	<div class="modal-dialog modal-lg">
		<form name="generateReportPdf" id="generateReportPdf" action="{{ url('sales/reports/generate-report-pdf') }}" method="POST">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" ng-click="funCheckReportGeneratedOrNot(viewOrderReport.order_id,viewOrderReport.status)">&times;</button>
					<h4 class="modal-title text-left">Test Report No. : <strong ng-bind="viewOrderReport.order_no"></strong></h4>
				</div>
				<div class="modal-body custom-pt-scroll">
				
					<!--Admin Permisssion-->
					<div class="col-sm-12 col-xs-12" ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
						
						<div class="custom-model-header"><strong class="modal-title text-left">Select Report Type :</strong></div>	
						<div class="col-sm-12 col-xs-12 radio text-left" ng-init="downloadType=1;signatureType=8;funSetReportTypeSignatue(1,8);">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-checked="selectedSinatureTypeParent == '1'" ng-click="funSetReportTypeSignatue(1,8)" ng-model="downloadType" name="downloadType" value="1">Final Report</div>
							<div class="col-sm-8 col-xs-8">
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(1,8)" ng-checked="selectedSinatureTypeChild == '8'" ng-model="signatureType" name="signatureType" value="8">With Digital Signature</span>
								<Span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(1,9)" ng-checked="selectedSinatureTypeChild == '9'" ng-model="signatureType" name="signatureType" value="9">Without Digital Signature</span>
							</div>
						</div>
						<div class="col-sm-12 col-xs-12 radio text-left">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-checked="selectedSinatureTypeParent == '2'" ng-click="funSetReportTypeSignatue(2,0)" ng-model="downloadType" name="downloadType" value="2">Draft Report</div>
							<div class="col-sm-8 col-xs-8"></div>
						</div>
						<div class="col-sm-12 col-xs-12 radio text-left">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-model="downloadType" ng-checked="selectedSinatureTypeParent == '3'" ng-click="funSetReportTypeSignatue(3,10)" name="downloadType" value="3">Print Report</div>
							<div class="col-sm-8 col-xs-8">
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,10)" ng-checked="selectedSinatureTypeChild == '10'" ng-model="signatureType" name="signatureType" value="10">With Digital Signature</span>
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,11)" ng-checked="selectedSinatureTypeChild == '11'" ng-model="signatureType" name="signatureType" value="11">Without Digital Signature</span>
							</div>
						</div>
							
						<div class="custom-model-header"><strong class="modal-title text-left">Select Column Option :</strong></div>						
						<div class="col-sm-12 col-xs-12 text-left">							
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithEquipment" name="reportWithEquipment" value="5">With Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id != 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutEquipment" name="reportWithoutEquipment" value="6">Without Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutLimit" name="reportWithoutLimit" value="13">Without Limit</span>
							</div>
						</div>
							
						<div class="custom-model-header" id="select_report_option_parent"><strong class="modal-title text-left">Select Report Option :</strong></div>						
						<div class="col-sm-12 col-xs-12 text-left" id="select_report_option_child">							
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '0' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11 asteriskRed" title="Accreditation Cerificate Number Missing!" ng-if="viewOrderReport.order_nabl_scope == '3'">
									<input type="checkbox" ng-disabled="true" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="7">NABL Code Applicable
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="checkbox" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="checkbox" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">
									NABL Code Applicable([[viewOrderReport.order_nabl_scope]])
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '1' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11 asteriskRed" title="Accreditation Cerificate Number Missing!" ng-if="viewOrderReport.order_nabl_scope == '3'">
									<input type="radio" ng-disabled="true" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="7">NABL Code Not Applicable
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'F'" ng-init="reportWithRightLogo = 7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">&nbsp;With Accredited([[viewOrderReport.order_nabl_scope]])
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'" ng-init="reportWithRightLogo = 16">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="16">&nbsp;With Accredited(F)
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="17">&nbsp;With Non-Accredited
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '8'" >
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','withoutPartwiseReport')" class="withOrWithoutReportClass" id="withoutPartwiseReport" ng-model="withoutPartwiseReport" name="withoutPartwiseReport" value="12">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Partwise Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Form-50 Report</span>
								</span>
							</div>								
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id != '405' && (!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '6' || viewOrderReport.product_category_id == '8')">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithDisciplineGroup')" class="withOrWithoutReportClass" id="reportWithDisciplineGroup" ng-model="reportWithDisciplineGroup" name="reportWithDisciplineGroup" value="15">With Discipline-Group Report</span>
							</div>								
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '405'">
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithOutForm50Format')" class="withOrWithoutReportClass" id="reportWithOutForm50Format" ng-model="reportWithOutForm50Format" name="reportWithOutForm50Format" value="14">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Form50 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Partwise Report</span>
								</span>
								<span ng-if="viewOrderReport.division_id == '2'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39Format" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="19">With Form39 Report</span>
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39Format" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="21">
									<span ng-if="viewOrderReport.product_category_id != '405'">With New Form39 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">COS-24 Report</span>
								</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '1'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithEICFormat')" class="withOrWithoutReportClass" id="reportWithEICFormat" ng-model="reportWithEICFormat" name="reportWithEICFormat" value="18">With EIC Report</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.division_id == '1' && viewOrderReport.product_category_id == '2'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithoutForm39Format')" class="withOrWithoutReportClass" id="reportWithoutForm39Format" ng-model="reportWithoutForm39Format" name="reportWithoutForm39Format" value="20">Without Form39 Report</span>
							</div>							
						</div>

						<div class="custom-model-header"><strong class="modal-title text-left">Select Mail Option :</strong></div>						
						<div class="col-sm-12 col-xs-12 text-left">
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.status > 6">
								<span class="checkbox font11"><input type="checkbox" ng-model="sendMailType" name="sendMailType" value="4">Send Mail to Party</span>
							</div>
						</div>
					</div>
					<!--/Admin Permisssion-->
					
					<!--Approval Permisssion-->
					<div class="col-sm-12 col-xs-12" ng-if="{{defined('IS_APPROVAL') && IS_APPROVAL}}">
						
						<div class="custom-model-header"><strong class="modal-title text-left">Select Report Type :</strong></div>	
						<div class="col-sm-12 col-xs-12 radio text-left" ng-init="downloadType=1;signatureType=8;funSetReportTypeSignatue(1,8);">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-checked="selectedSinatureTypeParent == '1'" ng-click="funSetReportTypeSignatue(1,8)" ng-model="downloadType" name="downloadType" value="1">Final Report</div>
							<div class="col-sm-8 col-xs-8">
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(1,8)" ng-checked="selectedSinatureTypeChild == '8'" ng-model="signatureType" name="signatureType" value="8">With Digital Signature</span>
								<Span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(1,9)" ng-checked="selectedSinatureTypeChild == '9'" ng-model="signatureType" name="signatureType" value="9">Without Digital Signature</span>
							</div>
						</div>
						<div class="col-sm-12 col-xs-12 radio text-left">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-checked="selectedSinatureTypeParent == '2'" ng-click="funSetReportTypeSignatue(2,0)" ng-model="downloadType" name="downloadType" value="2">Draft Report</div>
							<div class="col-sm-8 col-xs-8"></div>
						</div>
						<div class="col-sm-12 col-xs-12 radio text-left">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-model="downloadType" ng-checked="selectedSinatureTypeParent == '3'" ng-click="funSetReportTypeSignatue(3,10)" name="downloadType" value="3">Print Report</div>
							<div class="col-sm-8 col-xs-8">
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,10)" ng-checked="selectedSinatureTypeChild == '10'" ng-model="signatureType" name="signatureType" value="10">With Digital Signature</span>
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,11)" ng-checked="selectedSinatureTypeChild == '11'" ng-model="signatureType" name="signatureType" value="11">Without Digital Signature</span>
							</div>
						</div>
							
						<div class="custom-model-header"><strong class="modal-title text-left">Select Column Option :</strong></div>						
						<div class="col-sm-12 col-xs-12 text-left">							
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithEquipment" name="reportWithEquipment" value="5">With Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id != 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutEquipment" name="reportWithoutEquipment" value="6">Without Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutLimit" name="reportWithoutLimit" value="13">Without Limit</span>
							</div>
						</div>
							
						<div class="custom-model-header" id="select_report_option_parent"><strong class="modal-title text-left">Select Report Option:</strong></div>						
						<div class="col-sm-12 col-xs-12 text-left" id="select_report_option_child">
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '0' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11 asteriskRed" title="Accreditation Cerificate Number Missing!" ng-if="viewOrderReport.order_nabl_scope == '3'">
									<input type="checkbox" ng-disabled="true" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="7">NABL Code Applicable
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="checkbox" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="checkbox" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">
									NABL Code Applicable([[viewOrderReport.order_nabl_scope]])
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '1' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11 asteriskRed" title="Accreditation Cerificate Number Missing!" ng-if="viewOrderReport.order_nabl_scope == '3'">
									<input type="radio" ng-disabled="true" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="7">NABL Code Not Applicable
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'F'" ng-init="reportWithRightLogo = 7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">&nbsp;With Accredited([[viewOrderReport.order_nabl_scope]])
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'" ng-init="reportWithRightLogo = 16">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="16">&nbsp;With Accredited(F)
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="17">&nbsp;With Non-Accredited
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '8'" >
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','withoutPartwiseReport')" class="withOrWithoutReportClass" id="withoutPartwiseReport" ng-model="withoutPartwiseReport" name="withoutPartwiseReport" value="12">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Partwise Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Form-50 Report</span>
								</span>
							</div>								
							<div class="col-sm-6 col-xs-6" ng-if="!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '6' || viewOrderReport.product_category_id == '8'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithDisciplineGroup')" class="withOrWithoutReportClass" id="reportWithDisciplineGroup" ng-model="reportWithDisciplineGroup" name="reportWithDisciplineGroup" value="15">With Discipline-Group Report</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '405'">
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithOutForm50Format')" class="withOrWithoutReportClass" id="reportWithOutForm50Format" ng-model="reportWithOutForm50Format" name="reportWithOutForm50Format" value="14">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Form50 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Partwise Report</span>
								</span>
								<span ng-if="viewOrderReport.division_id == '2'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39Format" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="19">With Form39 Report</span>
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39Format" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="21">
									<span ng-if="viewOrderReport.product_category_id != '405'">With New Form39 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">COS-24 Report</span>
								</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '1'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithEICFormat')" class="withOrWithoutReportClass" id="reportWithEICFormat" ng-model="reportWithEICFormat" name="reportWithEICFormat" value="18">With EIC Report</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.division_id == '1' && viewOrderReport.product_category_id == '2'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithoutForm39Format')" class="withOrWithoutReportClass" id="reportWithoutForm39Format" ng-model="reportWithoutForm39Format" name="reportWithoutForm39Format" value="20">Without Form39 Report</span>
							</div>						
						</div>

						<div class="custom-model-header"><strong class="modal-title text-left">Select Mail Option :</strong></div>						
						<div class="col-sm-12 col-xs-12 text-left">
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.status > 6">
								<span class="checkbox font11"><input type="checkbox" ng-model="sendMailType" name="sendMailType" value="4">Send Mail to Party</span>
							</div>
						</div>
					</div>
					<!--/Approval Permisssion-->
					
					<!-- Invoicer permission-->
					<div class="col-sm-12 col-xs-12" ng-if="{{defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR}}">					
						
						<div class="custom-model-header"><strong class="modal-title text-left">Select Report Type :</strong></div>	
						<div class="col-sm-12 col-xs-12 radio text-left" ng-init="downloadType=3;signatureType=11;funSetReportTypeSignatue(3,10);">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-model="downloadType" ng-checked="selectedSinatureTypeParent == '3'" ng-click="funSetReportTypeSignatue(3,10)" name="downloadType" value="3">Print Report</div>
							<div class="col-sm-8 col-xs-8">
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,10)" ng-checked="selectedSinatureTypeChild == '10'" ng-model="signatureType" name="signatureType" value="10">With Digital Signature</span>
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,11)" ng-checked="selectedSinatureTypeChild == '11'" ng-model="signatureType" name="signatureType" value="11">Without Digital Signature</span>
							</div>
						</div>
						
						<div class="custom-model-header"><strong class="modal-title text-left">Select Column Option :</strong></div>						
						<div class="col-sm-12 col-xs-12 text-left">							
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithEquipment" name="reportWithEquipment" value="5">With Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id != 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutEquipment" name="reportWithoutEquipment" value="6">Without Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutLimit" name="reportWithoutLimit" value="13">Without Limit</span>
							</div>
						</div>
							
						<div class="custom-model-header" id="select_report_option_parent"><strong class="modal-title text-left">Select Report Option :</strong></div>
						<div class="col-sm-12 col-xs-12 text-left" id="select_report_option_child">
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '0' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="checkbox" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="checkbox" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">
									NABL Code Applicable([[viewOrderReport.order_nabl_scope]])
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '1' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'F'" ng-init="reportWithRightLogo = 7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">&nbsp;With Accredited([[viewOrderReport.order_nabl_scope]])
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'" ng-init="reportWithRightLogo = 16">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="16">&nbsp;With Accredited(F)
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="17">&nbsp;With Non-Accredited
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '8'" >
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','withoutPartwiseReport')" class="withOrWithoutReportClass" id="withoutPartwiseReport" ng-model="withoutPartwiseReport" name="withoutPartwiseReport" value="12">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Partwise Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Form-50 Report</span>
								</span>
							</div>								
							<div class="col-sm-6 col-xs-6" ng-if="!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '6' || viewOrderReport.product_category_id == '8'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithDisciplineGroup')" class="withOrWithoutReportClass" id="reportWithDisciplineGroup" ng-model="reportWithDisciplineGroup" name="reportWithDisciplineGroup" value="15">With Discipline-Group Report</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '405'">
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithOutForm50Format')" class="withOrWithoutReportClass" id="reportWithOutForm50Format" ng-model="reportWithOutForm50Format" name="reportWithOutForm50Format" value="14">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Form50 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Partwise Report</span>
								</span>
								<span ng-if="viewOrderReport.division_id == '2'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39Format" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="19">With Form39 Report</span>
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39NewFormat" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="21">
									<span ng-if="viewOrderReport.product_category_id != '405'">With New Form39 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">COS-24 Report</span>
								</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '1'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithEICFormat')" class="withOrWithoutReportClass" id="reportWithEICFormat" ng-model="reportWithEICFormat" name="reportWithEICFormat" value="18">With EIC Report</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.division_id == '1' && viewOrderReport.product_category_id == '2'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithoutForm39Format')" class="withOrWithoutReportClass" id="reportWithoutForm39Format" ng-model="reportWithoutForm39Format" name="reportWithoutForm39Format" value="20">Without Form39 Report</span>
							</div>
						</div>
					</div>
					<!-- /Invoicer permission-->
					
					<!--Dispatcher Permisssion-->
					<div class="col-sm-12 col-xs-12" ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}">
						
						<div class="custom-model-header"><strong class="modal-title text-left">Select Report Type :</strong></div>
						<div class="col-sm-12 col-xs-12 radio text-left" ng-init="downloadType=3;signatureType=11;funSetReportTypeSignatue(3,10);">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-model="downloadType" ng-checked="selectedSinatureTypeParent == '3'" ng-click="funSetReportTypeSignatue(3,10)" name="downloadType" value="3">Print Report</div>
							<div class="col-sm-8 col-xs-8">
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,10)" ng-checked="selectedSinatureTypeChild == '10'" ng-model="signatureType" name="signatureType" value="10">With Digital Signature</span>
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,11)" ng-checked="selectedSinatureTypeChild == '11'" ng-model="signatureType" name="signatureType" value="11">Without Digital Signature</span>
							</div>
						</div>
						
						<div class="custom-model-header"><strong class="modal-title text-left">Select Column Option :</strong></div>
						<div class="col-sm-12 col-xs-12 text-left">
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithEquipment" name="reportWithEquipment" value="5">With Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id != 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutEquipment" name="reportWithoutEquipment" value="6">Without Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutLimit" name="reportWithoutLimit" value="13">Without Limit</span>
							</div>
						</div>
							
						<div class="custom-model-header" id="select_report_option_parent"><strong class="modal-title text-left">Select Report Option :</strong></div>						
						<div class="col-sm-12 col-xs-12 text-left" id="select_report_option_child">
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '0' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="checkbox" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="checkbox" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">
									NABL Code Applicable([[viewOrderReport.order_nabl_scope]])
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '1' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'F'" ng-init="reportWithRightLogo = 7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">&nbsp;With Accredited([[viewOrderReport.order_nabl_scope]])
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'" ng-init="reportWithRightLogo = 16">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="16">&nbsp;With Accredited(F)
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="17">&nbsp;With Non-Accredited
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '8'" >
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','withoutPartwiseReport')" class="withOrWithoutReportClass" id="withoutPartwiseReport" ng-model="withoutPartwiseReport" name="withoutPartwiseReport" value="12">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Partwise Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Form-50 Report</span>
								</span>
							</div>								
							<div class="col-sm-6 col-xs-6" ng-if="!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '6' || viewOrderReport.product_category_id == '8'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithDisciplineGroup')" class="withOrWithoutReportClass" id="reportWithDisciplineGroup" ng-model="reportWithDisciplineGroup" name="reportWithDisciplineGroup" value="15">With Discipline-Group Report</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '405'">
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithOutForm50Format')" class="withOrWithoutReportClass" id="reportWithOutForm50Format" ng-model="reportWithOutForm50Format" name="reportWithOutForm50Format" value="14">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Form50 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Partwise Report</span>
								</span>
								<span ng-if="viewOrderReport.division_id == '2'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39Format" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="19">With Form39 Report</span>
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39Format" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="21">
									<span ng-if="viewOrderReport.product_category_id != '405'">With New Form39 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">COS-24 Report</span>
								</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '1'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithEICFormat')" class="withOrWithoutReportClass" id="reportWithEICFormat" ng-model="reportWithEICFormat" name="reportWithEICFormat" value="18">With EIC Report</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.division_id == '1' && viewOrderReport.product_category_id == '2'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithoutForm39Format')" class="withOrWithoutReportClass" id="reportWithoutForm39Format" ng-model="reportWithoutForm39Format" name="reportWithoutForm39Format" value="20">Without Form39 Report</span>
							</div>
						</div>
					</div>					
					<!--/Dispatcher Permisssion-->
					
					<!--CRM Permisssion-->
					<div class="col-sm-12 col-xs-12" ng-if="{{defined('IS_CRM') && IS_CRM}}">
						
						<div class="custom-model-header"><strong class="modal-title text-left">Select Report Type :</strong></div>	
						<div class="col-sm-12 col-xs-12 radio text-left" ng-init="downloadType=1;signatureType=8;funSetReportTypeSignatue(1,8);">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-checked="selectedSinatureTypeParent == '1'" ng-click="funSetReportTypeSignatue(1,8)" ng-model="downloadType" name="downloadType" value="1">Final Report</div>
							<div class="col-sm-8 col-xs-8">
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(1,8)" ng-checked="selectedSinatureTypeChild == '8'" ng-model="signatureType" name="signatureType" value="8">With Digital Signature</span>
								<Span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(1,9)" ng-checked="selectedSinatureTypeChild == '9'" ng-model="signatureType" name="signatureType" value="9">Without Digital Signature</span>
							</div>
						</div>
						<div class="col-sm-12 col-xs-12 radio text-left">
							<div class="col-sm-4 col-xs-4"><input type="radio" ng-model="downloadType" ng-checked="selectedSinatureTypeParent == '3'" ng-click="funSetReportTypeSignatue(3,10)" name="downloadType" value="3">Print Report</div>
							<div class="col-sm-8 col-xs-8">
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,10)" ng-checked="selectedSinatureTypeChild == '10'" ng-model="signatureType" name="signatureType" value="10">With Digital Signature</span>
								<span class="col-sm-6 col-xs-6 font11"><input type="radio" ng-click="funSetReportTypeSignatue(3,11)" ng-checked="selectedSinatureTypeChild == '11'" ng-model="signatureType" name="signatureType" value="11">Without Digital Signature</span>
							</div>
						</div>
						
						<div class="custom-model-header"><strong class="modal-title text-left">Select Column Option :</strong></div>
						<div class="col-sm-12 col-xs-12 text-left">
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithEquipment" name="reportWithEquipment" value="5">With Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id != 2">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutEquipment" name="reportWithoutEquipment" value="6">Without Equipment</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id">
								<span class="checkbox font11"><input type="checkbox" ng-model="reportWithoutLimit" name="reportWithoutLimit" value="13">Without Limit</span>
							</div>
						</div>
							
						<div class="custom-model-header" id="select_report_option_parent"><strong class="modal-title text-left">Select Report Option :</strong></div>						
						<div class="col-sm-12 col-xs-12 text-left" id="select_report_option_child">
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '0' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="checkbox" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="checkbox" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">
									NABL Code Applicable([[viewOrderReport.order_nabl_scope]])
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.nabl_report_generation_type == '1' && viewOrderReport.status > '6' && viewOrderReport.order_nabl_scope != '2'">
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'F'" ng-init="reportWithRightLogo = 7">
									<input ng-if="viewOrderReport.order_nabl_scope == 'F'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="7">&nbsp;With Accredited([[viewOrderReport.order_nabl_scope]])
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'" ng-init="reportWithRightLogo = 16">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" ng-checked="true" name="reportWithRightLogo" value="16">&nbsp;With Accredited(F)
								</div>
								<div class="checkbox font11" ng-if="viewOrderReport.order_nabl_scope != '3' && viewOrderReport.order_nabl_scope == 'P'">
									<input ng-if="viewOrderReport.order_nabl_scope == 'P'" type="radio" ng-model="reportWithRightLogo" name="reportWithRightLogo" value="17">&nbsp;With Non-Accredited
								</div>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '8'" >
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','withoutPartwiseReport')" class="withOrWithoutReportClass" id="withoutPartwiseReport" ng-model="withoutPartwiseReport" name="withoutPartwiseReport" value="12">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Partwise Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Form-50 Report</span>
								</span>
							</div>								
							<div class="col-sm-6 col-xs-6" ng-if="!defaultProductCategoryIDS.includes(viewOrderReport.product_category_id) || || viewOrderReport.product_category_id == '1' || viewOrderReport.product_category_id == '6' || viewOrderReport.product_category_id == '8'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithDisciplineGroup')" class="withOrWithoutReportClass" id="reportWithDisciplineGroup" ng-model="reportWithDisciplineGroup" name="reportWithDisciplineGroup" value="15">With Discipline-Group Report</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '405'">
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithOutForm50Format')" class="withOrWithoutReportClass" id="reportWithOutForm50Format" ng-model="reportWithOutForm50Format" name="reportWithOutForm50Format" value="14">
									<span ng-if="viewOrderReport.product_category_id != '405'">Without Form50 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">Partwise Report</span>
								</span>
								<span ng-if="viewOrderReport.division_id == '2'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39Format" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="19">With Form39 Report</span>
								<span ng-if="viewOrderReport.division_id == '1'" class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportCosmeticWithForm39Format')" class="withOrWithoutReportClass" id="reportCosmeticWithForm39Format" ng-model="reportCosmeticWithForm39Format" name="reportCosmeticWithForm39Format" value="21">
									<span ng-if="viewOrderReport.product_category_id != '405'">With New Form39 Report</span>
									<span ng-if="viewOrderReport.product_category_id == '405'">COS-24 Report</span>
								</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.product_category_id == '1'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithEICFormat')" class="withOrWithoutReportClass" id="reportWithEICFormat" ng-model="reportWithEICFormat" name="reportWithEICFormat" value="18">With EIC Report</span>
							</div>
							<div class="col-sm-6 col-xs-6" ng-if="viewOrderReport.division_id == '1' && viewOrderReport.product_category_id == '2'">
								<span class="checkbox font11"><input type="checkbox" ng-click="funSelectedReportTypeEnableDisable('withOrWithoutReportClass','reportWithoutForm39Format')" class="withOrWithoutReportClass" id="reportWithoutForm39Format" ng-model="reportWithoutForm39Format" name="reportWithoutForm39Format" value="20">Without Form39 Report</span>
							</div>
						</div>
					</div>					
					<!--/CRM Permisssion-->
				</div>

				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>	
					<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
						<input type="hidden" ng-value="viewOrderReport.nabl_report_generation_type" name="nabl_report_generation_type" ng-model="viewOrderReport.nabl_report_generation_type">
						<input type="hidden" ng-value="viewOrderReport.order_id" name="order_id" ng-model="viewOrderReport.order_id">
						<input type="hidden" ng-if="viewOrderReport.isBackDateBookingAllowed" id="back_report_date" ng-model="viewOrderReport.report_date" ng-value="viewOrderReport.report_date" name="back_report_date">
						<input type="submit" formtarget="_blank" name="generate_report_pdf" value="Generate PDF" class="btn btn-primary">
					</span>
					<span ng-if="{{defined('IS_APPROVAL') && IS_APPROVAL}}">
						<input type="hidden" ng-value="viewOrderReport.nabl_report_generation_type" name="nabl_report_generation_type" ng-model="viewOrderReport.nabl_report_generation_type">
						<input type="hidden" ng-value="viewOrderReport.order_id" name="order_id" ng-model="viewOrderReport.order_id">
						<input type="hidden" ng-if="viewOrderReport.isBackDateBookingAllowed" id="back_report_date" ng-model="viewOrderReport.report_date" ng-value="viewOrderReport.report_date" name="back_report_date">
						<input type="submit" formtarget="_blank" name="generate_report_pdf" value="Generate PDF" class="btn btn-primary">
					</span>
					<span ng-if="{{defined('IS_INVOICE_GENERATOR') && IS_INVOICE_GENERATOR}}">
						<input type="hidden" ng-value="viewOrderReport.nabl_report_generation_type" name="nabl_report_generation_type" ng-model="viewOrderReport.nabl_report_generation_type">
						<input type="hidden" ng-value="viewOrderReport.order_id" name="order_id" ng-model="viewOrderReport.order_id">
						<input type="submit" formtarget="_blank" name="generate_report_pdf" value="Download PDF" class="btn btn-primary">
					</span>
					<span ng-if="{{defined('IS_DISPATCHER') && IS_DISPATCHER}}">
						<input type="hidden" ng-value="viewOrderReport.nabl_report_generation_type" name="nabl_report_generation_type" ng-model="viewOrderReport.nabl_report_generation_type">
						<input type="hidden" ng-value="viewOrderReport.order_id" name="order_id" ng-model="viewOrderReport.order_id">
						<input type="submit" formtarget="_blank" name="generate_report_pdf" value="Download PDF" class="btn btn-primary">
					</span>					
					<span ng-if="{{defined('IS_CRM') && IS_CRM}}">
						<input type="hidden" ng-value="viewOrderReport.nabl_report_generation_type" name="nabl_report_generation_type" ng-model="viewOrderReport.nabl_report_generation_type">
						<input type="hidden" ng-value="viewOrderReport.order_id" name="order_id" ng-model="viewOrderReport.order_id">
						<input type="submit" formtarget="_blank" name="generate_report_pdf" value="Download PDF" class="btn btn-primary">
					</span>
					<button type="button" class="btn btn-default" ng-click="funCheckReportGeneratedOrNot(viewOrderReport.order_id,viewOrderReport.status)">Close</button>						
				</div>

			</div>
		</form>		
	</div>
</div>