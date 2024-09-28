<div class="row" ng-hide="hideStandardAddForm">
	<div class="panel panel-default">
		<div class="panel-body">			
			<div class="row header1">
				<span><strong class="pull-left headerText">Add Test Standard</strong></span>
				<span><button class="pull-right btn btn-primary mT3 mR3" ng-click="showUploadForm()">Upload</button></span>
			</div>			
			<form name='testStandardForm' id="testStandardForm" novalidate>						
				<div class="row">					
					
					<!--Test Standard Code-->
					<div class="col-xs-3">
						<span class="generate"><a href="javascript:;" ng-click="generateDefaultCode();">Generate</a></span> 
						<label for="test_std_code">Test Standard Code<em class="asteriskRed">*</em></label>						   
						<input readonly
							type="text"
							class="form-control"
							ng-value="default_test_std_code"
							name="test_std_code" 
							id="test_std_code"
							placeholder="Test Standard Code" />
						<span ng-messages="testStandardForm.test_std_code.$error" ng-if='testStandardForm.test_std_code.$dirty || testStandardForm.$submitted' role="alert">
							<span ng-message="required" class="error">Test standard code is required</span>
						</span>
					</div>
					<!--/Test Standard Code-->
					
					<!--Test Standard Name-->
					<div class="col-xs-3">
						<label for="test_std_name">Test Standard Name<em class="asteriskRed">*</em></label>
						<input
							type="text"
							class="form-control" 
							ng-model="addTestStandard.test_std_name"
							name="test_std_name" 
							id="test_std_name"
							ng-required='true'
							placeholder="Test Standard Name" />
						<span ng-messages="testStandardForm.test_std_name.$error" ng-if='testStandardForm.test_std_name.$dirty  || testStandardForm.$submitted' role="alert">
							<span ng-message="required" class="error">Test standard name is required</span>
						</span>
					</div>
					<!--/Test Standard Name-->
					
					<!--Test Standard Desc-->
					<div class="col-xs-3">
						<label for="test_std_desc">Test Standard Desc<em class="asteriskRed">*</em></label>
						<textarea
							type="text"
							class="form-control" 
							ng-model="addTestStandard.test_std_desc"
							name="test_std_desc" 
							id="test_std_desc"
							ng-required='true'
							placeholder="Test Standard Desc">
						</textarea>
						<span ng-messages="testStandardForm.test_std_desc.$error" ng-if='testStandardForm.test_std_desc.$dirty || testStandardForm.$submitted' role="alert">
							<span ng-message="required" class="error">Test standard Desc is required</span>
						</span>
					</div>
					<!--/Test Standard Desc-->
					<!--Parent Product Category-->
					<div class="col-xs-3" ng-init="fungetParentCategory()">																
						<label for="product_category_id" class="outer-lable">Product Section<em class="asteriskRed">*</em></label>	
						<select
							class="form-control"
							name="product_category_id"
							id="product_category_id"
							ng-model="addTestStandard.product_category_id"
							ng-options="item.id as item.name for item in (parentCategoryList | filter:searchProduct.text) track by item.id"
							ng-required='true'>
							<option value="">Select Product Section</option>
						</select>
						<span ng-messages="testStandardForm.product_category_id.$error" ng-if='testStandardForm.product_category_id.$dirty || testStandardForm.$submitted' role="alert">
							<span ng-message="required" class="error">Parent Product Category is required</span>
						</span>
					</div>
					<!--/Parent Product Category-->
				</div>
				<div class="row">
					
					<!-- status---->
					<div class="col-xs-3">
						<label for="status">Status<em class="asteriskRed">*</em></label>	
						<select class="form-control" 
							ng-required='true'  
							name="status" 
							id="status" 
							ng-options="status.name for status in statusList track by status.id"
							ng-model="addTestStandard.status.selectedOption">
							<option value="">Select Status</option>
						</select>				   

						<span ng-messages="testStandardForm.status.$error" ng-if='testStandardForm.status.$dirty  || testStandardForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>
					<!-- /status---->
					<!--save button-->
					<div class="mT26 col-xs-3">
						<div class="pull-left">
							<label for="submit">{{ csrf_field() }}</label>	
							<button title="Save" ng-disabled="testStandardForm.$invalid" type='submit' id='add_button' class='btn btn-primary' ng-click='funAddTestStandard()'>Save</button>
							<button title="Reset"  type="button" class="btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>
						</div>
					</div>
					<!--/save button-->					
				</div>					
			</form>				
		</div>
	</div>
</div>