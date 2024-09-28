<div class="row" ng-hide="hideStandardEditForm">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row header1">
				<span><strong class="pull-left headerText">Edit Test Standard</strong></span>
			</div>
			<form name='editTestStdForm' id="edit_test_std_form" novalidate>			
				<div class="row">
					
					<!--Test Standard Code-->
					<div class="col-xs-3">  
						<label for="test_std_code1">Test Standard Code<em class="asteriskRed">*</em></label>						   
						<input readonly 
							type="text"
							class="form-control"  
							ng-model="editTestStandard.test_std_code"
							id="edit_test_std_code1"
							placeholder="Test Standard Code" />
					</div>
					<!--/Test Standard Code-->
					
					<!--Test Standard Name-->
					<div class="col-xs-3">
						<label for="comp_city1">Test Standard Name<em class="asteriskRed">*</em></label>
						<input type="text"
							class="form-control"
							id="edit_test_std_name"
							name="test_std_name"
							ng-model="editTestStandard.test_std_name"
							ng-required='true'
							placeholder="Test Standard Name" />
						<span ng-messages="editTestStdForm.test_std_name.$error" ng-if='editTestStdForm.test_std_name.$dirty  || editTestStdForm.$submitted' role="alert">
							<span ng-message="required" class="error">Test Standard Name</span>
						</span>
					</div>
					<!--/Test Standard Name-->
					
					<!--Test Standard Desc-->
					<div class="col-xs-3">
						<label for="test_std_desc">Test Standard Desc<em class="asteriskRed">*</em></label>
						<textarea
							type="text"
							class="form-control" 
							ng-model="editTestStandard.test_std_desc"
							name="test_std_desc" 
							id="edit_test_std_desc"
							ng-required='true'
							placeholder="Test Standard Desc">
						</textarea>
						<span ng-messages="editTestStdForm.test_std_desc.$error" ng-if='editTestStdForm.test_std_desc.$dirty || editTestStdForm.$submitted' role="alert">
							<span ng-message="required" class="error">Test standard Desc is required</span>
						</span>
					</div>
					<!--/Test Standard Desc-->
					
					<!--Parent Product Category-->
					<div class="col-xs-3">																
						<label for="product_category_id" class="outer-lable">Product Section<em class="asteriskRed">*</em></label>	
						<select class="form-control"
								name="product_category_id"
								id="edit_product_category_id"
								ng-model="editTestStandard.product_category_id.selectedOption"
								ng-options="item.name for item in parentCategoryList track by item.id"
								ng-required='true'>
							<option value="">Select Product Section</option>
						</select>
						<span ng-messages="editTestStdForm.product_category_id.$error" ng-if='editTestStdForm.product_category_id.$dirty || editTestStdForm.$submitted' role="alert">
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
							ng-model="editTestStandard.status.selectedOption">
							<option value="">Select Status</option>
						</select>				   

						<span ng-messages="editTestStdForm.status.$error" ng-if='editTestStdForm.status.$dirty  || editTestStdForm.$submitted' role="alert">
							<span ng-message="required" class="error">Status is required</span>
						</span>
					</div>
					<!-- /status---->
					<!--save button-->
					<div class="mT26 col-xs-3">
						<div class="pull-left">
							<label for="submit">{{ csrf_field() }}</label>	
							<input type="hidden" ng-model="editTestStandard.test_std_id" name="test_std_id" ng-value="editTestStandard.test_std_id">
							<button title="Update" ng-disabled="editTestStdForm.$invalid" type='submit' id='edit_button' class='btn btn-primary' ng-click='updateTestStd()'>Update</button>
							<button title="Close" type="button" class="btn btn-default" ng-click="closeButton()">Close</button>
						</div>
					</div>
					<!--/save button-->
				</div>
			</form>				
		</div>
	</div>
</div>