<div class="row" ng-if="addFormDiv">
	<div class="panel panel-default">
		<div class="panel-body">			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Report Template</strong></span>
				<button title="Close" type='button' class='mT6  btn btn-primary pull-right' style="position: relative;top: -3px;" ng-click='back();'>Back</button>
			</div>			
			<!--Add method form-->
			<form name="erpAddTemplateForm" id="erpAddTemplateForm" novalidate>							
				<div class="row">
					
					<!--Template Type-->	
					<div class="col-xs-4">
						<label for="">Template Type<em class="asteriskRed">*</em></label>
						<select
							class="form-control" 
							name="template_type_id"
							ng-model="template_type_id.selectedOption"
							ng-change="funCheckRequired(template_type_id.selectedOption.id)"
							ng-options="item.name for item in templatesTypeList track by item.id"
							ng-required="true">
						</select>
						<span ng-messages="erpAddTemplateForm.template_type_id.$error" ng-if='erpAddTemplateForm.template_type_id.$dirty  || erpAddTemplateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Template Type is required</span>
						</span>
					</div>
					<!--/Template Type-->	
					
					<!--Branch Code-->
					<div class="col-xs-4" ng-if="requiredBranch">
						<label for="division_id">Branch<em class="asteriskRed">*</em></label>
						<select
							class="form-control" 
							name="division_id"
							id="division_id"
							ng-model="addTemplate.division_id"
							ng-options="item.name for item in divisionsCodeList track by item.id"
							ng-required="true">
							<option value="">Select Branch</option>
						</select>
						<span ng-messages="erpAddTemplateForm.division_id.$error" ng-if='erpAddTemplateForm.division_id.$dirty  || erpAddTemplateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Branch is required</span>
						</span>
					</div>
					<div class="col-xs-4" ng-if="!requiredBranch">
						<label for="division_id">Branch</label>
						<select
							class="form-control" 
							name="division_id"
							id="division_id"
							ng-model="addTemplate.division_id"
							ng-options="item.name for item in divisionsCodeList track by item.id">
							<option value="">Select Branch</option>
						</select>
					</div>
					<!--/Branch Code-->
					
					<!--Department Name-->
					<div class="col-xs-4" ng-if="requiredDepartment">
						<label for="product_category_id">Department<em class="asteriskRed">*</em></label>						   
							<select
								class="form-control" 
								id="product_category_id"
								name="product_category_id"
								ng-model="addTemplate.product_category_id"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required="true">
							<option value="">Select Department</option>
						</select>
						<span ng-messages="erpAddTemplateForm.product_category_id.$error" ng-if='erpAddTemplateForm.product_category_id.$dirty || erpAddTemplateForm.$submitted' role="alert">
							<span ng-message="required" class="error">Department is required</span>
						</span>
					</div>
					<div class="col-xs-4" ng-if="!requiredDepartment">
						<label for="product_category_id">Department</label>						   
						<select
							class="form-control" 
							id="product_category_id"
							name="product_category_id"
							ng-model="addTemplate.product_category_id"
							ng-options="item.name for item in parentCategoryList track by item.id">
							<option value="">Select Department</option>
						</select>
					</div>	
				</div>
				
				<!--Header section starts-->
				<div class="row mT20">					
					<div class="col-xs-12">
						<label for="header_content" class="outer-lable">Header Section<em ng-if="requiredHeader" class="asteriskRed">*</em></label>	
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
											ng-if="requiredHeader"
											ng-required="true"
											name="header_content"
											ng-model="addTemplate.header_content"
											id="headerContent"
											style="width:100%;height:200px;white-space: unset;"
											placeholder="Header content">
										</textarea>
										<textarea
											ng-if="!requiredHeader"
											name="header_content"
											ng-model="addTemplate.header_content"
											id="headerContent"
											style="width:100%;height:200px;white-space: unset;"
											placeholder="Header content">
										</textarea>
										<span ng-messages="erpAddTemplateForm.header_content.$error" ng-if='erpAddTemplateForm.header_content.$dirty  || erpAddTemplateForm.$submitted' role="alert">
											<span ng-message="required" class="error">Header section is required</span>
										</span>
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
				<div class="col-xs-12 mT10">
					<label for="footer_content" class="outer-lable">Footer Section<em ng-if="requiredFooter" class="asteriskRed">*</em></label>	
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
										ng-if="requiredFooter"
										ng-required="true"
										name="footer_content"
										ng-model="addTemplate.footer_content"
										id="footerContent"
										style="width:100%;height:200px;white-space: unset;"
										placeholder="Footer content">
									</textarea>
									<textarea
										ng-if="!requiredFooter"
										name="footer_content"
										ng-model="addTemplate.footer_content"
										id="footerContent"
										style="width:100%;height:200px;white-space: unset;"
										placeholder="Footer content">
									</textarea>
								</div>
								<div class="tab-pane fade" id="footerViewTab">
									<div class="tab-pane fade in active" id="tab1default">
									<p  ng-if ="reportTemplateViewFooterData" ng-bind-html = "reportTemplateViewFooterData"></p>
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
				<!--/Footer section-->
				
				<!--save button-->
				<div class="col-xs-2 pull-right">
					<label for="submit">{{ csrf_field() }}</label>		
					<button title="Save" ng-disabled="erpAddTemplateForm.$invalid" type='submit' id='add_button' class='mT26 btn btn-primary' ng-click='funAddTemplateContent()'>Save</button>
					<button title="Reset" type="button" class="mT26 btn btn-default" ng-click="resetForm()">Reset</button>
				</div>
				<!--/save button-->						
			</form>
			<!--Add method form-->
		</div>
	</div>
</div>