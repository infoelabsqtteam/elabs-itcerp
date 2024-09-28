<div class="row" ng-if="editFormDiv">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<strong class="pull-left headerText">Edit Report Template</strong>
				<button title="Close" type='button' class='mT6 btn btn-primary pull-right'  style="position: relative;top: -3px;" ng-click='back()'>Back</button>
			</div>
			<form name="erpEditTemplateForm" id="erpEditTemplateForm" novalidate>

				<div class="row">
					
					<!--Template Type-->	
					<div class="col-xs-4">
						<label for="template_type_id">Template Type<em class="asteriskRed">*</em></label>
						<select
							class="form-control" 
							name="template_type_id"
							ng-model="editTemplate.template_type_id.selectedOption"
							ng-change="funCheckRequired(editTemplate.template_type_id.selectedOption.id)"
							ng-options="item.name for item in templatesTypeList track by item.id"
							ng-required="true">
						</select>				
						<span ng-messages="erpEditTemplateForm.template_type_id.$error" ng-if='erpEditTemplateForm.template_type_id.$dirty  || erpEditTemplateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Template Type is required</span>
						</span>
					</div>
					<!--/Template Type-->	
					
					<!--Branch Code-->
					<div class="col-xs-4" ng-if="requiredEditBranch">
						<label for="division_id">Branch<em class="asteriskRed">*</em></label>
							<select
								class="form-control" 
								id="division_id"
								name="division_id"
								ng-model="editTemplate.division_id.selectedOption"
								ng-options="item.name for item in divisionsCodeList track by item.id"
								ng-required="true">
							<option value="">Select Branch </option>
						</select>
						<span ng-messages="erpEditTemplateForm.division_id.$error" ng-if='erpEditTemplateForm.division_id.$dirty  || erpEditTemplateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Branch is required</span>
						</span>
					</div>
					<div class="col-xs-4" ng-if="!requiredEditBranch">
						<label for="division_id">Branch</label>
							<select
								class="form-control"
								id="division_id"
								name="division_id"
								ng-model="editTemplate.division_id.selectedOption"
								ng-options="item.name for item in divisionsCodeList track by item.id">
							<option value="">Select Branch </option>
						</select>
					</div>
					<!--/Branch Code-->
					
					<!--Department Name-->
					<div class="col-xs-4" ng-if="requiredEditDepartment">
						<label for="product_category_id">Department<em class="asteriskRed">*</em></label>						   
						<select
							class="form-control"
							id="product_category_id"
							name="product_category_id"
							ng-model="editTemplate.product_category_id.selectedOption"
							ng-options="item.name for item in parentCategoryList track by item.id"
							ng-required="true">
							<option value="">Select Department</option>
						</select>
						<span ng-messages="erpEditTemplateForm.product_category_id.$error" ng-if='erpEditTemplateForm.product_category_id.$dirty || erpEditTemplateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>
					<div class="col-xs-4" ng-if="!requiredEditDepartment">
						<label for="">Department</label>						   
						<select
							class="form-control"
							id="product_category_id"
							name="product_category_id"
							ng-model="editTemplate.product_category_id.selectedOption"
							ng-options="item.name for item in parentCategoryList track by item.id">
							<option value="">Select Department</option>
						</select>
					</div>
					<!--/Department Name-->
				</div>
				
				<!--/Header section starts-->
				<div class="row">
					<div class="col-xs-12">
						<label for="product_category_id" class="outer-lable">Header Section<em ng-if="requiredEditHeader" class="asteriskRed">*</em></label>	
						<div class="panel with-nav-tabs panel-default">
							<div class="panel-heading">
							    <ul class="nav nav-tabs">
								<li class="active"><a href="#headerHtmlTab" data-toggle="tab">HTML</a></li>
								<li><a href="#headerViewTab" data-toggle="tab" ng-click="funViewTemplate('header');">VIEW</a></li>
							    </ul>
							</div>
							<div class="panel-body">
								<div class="tab-content">
									<div class="tab-pane fade in active" id="headerHtmlTab">
										<textarea
											ng-if="requiredEditHeader"
											ng-required="true"
											name="header_content"
											ng-model="editTemplate.header_content"
											id="headerContent"
											style="width:100%;height:200px;white-space: unset;"
											placeholder="Header content">
										</textarea>
										<textarea
											ng-if="!requiredEditHeader"
											name="header_content"
											ng-model="editTemplate.header_content"
											id="headerContent"
											style="width:100%;height:200px;white-space: unset;"
											placeholder="Header content">
										</textarea>
									</div>
									<div class="tab-pane fade" id="headerViewTab">
										<div class="tab-pane fade in active" id="tab1default">
											<div ng-if="reportTemplateViewHeaderData" ng-bind-html="reportTemplateViewHeaderData"></div>
										</div>
									</div>
								</div>     
							</div>	
						</div>
					</div>					
				</div>
				<!--/Header section ends-->
					
				<!--Footer section starts-->
				<div class="col-xs-12">
					<label for="product_category_id" class="outer-lable">Footer Section<em class="asteriskRed" ng-if="requiredEditFooter">*</em></label>	
					<div class="panel with-nav-tabs panel-default">
						<div class="panel-heading">
						    <ul class="nav nav-tabs">
							<li class="active"><a href="#footerHtmlTab" data-toggle="tab">HTML</a></li>
							<li><a href="#footerViewTab" data-toggle="tab" ng-click="funViewTemplate('footer');">VIEW</a></li>
						    </ul>
						</div>
						<div class="panel-body">
							<div class="tab-content">
								<div class="tab-pane fade in active" id="footerHtmlTab">
									<textarea
										ng-if="requiredEditFooter"
										ng-required="true"
										name="footer_content"
										ng-model="editTemplate.footer_content"
										id="footerContent"
										style="width:100%;height:200px;white-space: unset;"
										placeholder="Footer content">
									</textarea>
									<textarea
										ng-if="!requiredEditFooter"
										name="footer_content"
										ng-model="editTemplate.footer_content"
										id="footerContent"
										style="width:100%;height:200px;white-space: unset;"
										placeholder="Footer content">
									</textarea>
								</div>
								<div class="tab-pane fade" id="footerViewTab">
									<div class="tab-pane fade in active" id="tab1default">
										<div ng-if="reportTemplateViewFooterData" ng-bind-html="reportTemplateViewFooterData"></div>
									</div>
								</div>
							</div>     
						</div>	
					</div>
				</div>
				<!--/Footer section-->
				
				<!--Update button-->
				<div class="row">					
					<div class="col-xs-2  pull-right">
						<label for="submit">{{ csrf_field() }}</label>
						<input type="hidden" name="template_id" ng-value="editTemplate.template_id" ng-model="editTemplate.ortd_id">
						<button type="submit" title="Update" ng-disabled="erpEditTemplateForm.$invalid" class='mT26 btn btn-primary  btn-sm' ng-click='funUpdateReportTemplate()'>Update</button>							
						<button title="Close" type='button' class='mT26 btn btn-default btn-sm' ng-click='back()'>Close</button>
					</div>				
				</div>
				<!--/Update button-->
			</form>	
		</div>
	</div>
</div>