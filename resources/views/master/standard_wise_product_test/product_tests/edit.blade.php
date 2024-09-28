<div ng-hide="editProductTestDiv" id="editProTestDiv">
	<form name='editProductTestForm' id="edit_product_test_form" novalidate>
		<div class="row header1">
			<strong class="pull-left headerText">Edit Product Test</strong>								
		</div>
		<label for="submit">{{ csrf_field() }}</label>
		<div class="row ">
			<div class="col-xs-2">
			   <div class="">     
				<label for="test_code">Test Code<span class="asteriskRed">*</span>:</label>						   
					<input readonly type="text" class="form-control" 
							ng-model="editProductTest.test_code"
							id="test_code"
							ng-required='true'
							placeholder="Test Code" />
					<span ng-messages="editProductTestForm.test_code.$error" 
					ng-if='editProductTestForm.test_code.$dirty  || editProductTestForm.$submitted' role="alert">
						<span ng-message="required" class="error">Test code is required</span>
					</span>
			   </div>
			</div>
			<div class="col-xs-2">
					<label for="wef">Product<em class="asteriskRed">*</em></label>
					<a title="Select Category" data-toggle="modal"  title="Select Product Category" ng-click="showProductCatTreeViewPopUp(3)" class='generate cursor-pointer'> Tree View</a>
					<select class="form-control" 
							name="product_id"
							ng-model="product_id.selectedOption"
							id="product_id"
							ng-required='true'
							ng-options="item.name for item in productList track by item.id">
						<option value="">Select Product</option>
					</select>
					<span ng-messages="editProductTestForm.product_id.$error" 
					 ng-if='editProductTestForm.product_id.$dirty  || editProductTestForm.$submitted' role="alert">
						<span ng-message="required" class="error">Product is required</span>
					</span>
			</div>
			<div class="col-xs-2">
				<label for="test_standard_id" class="outer-lable">
					 <span class="filter-lable">Test Standard<em class="asteriskRed">*</em></span>
					 <span class="filterCatLink">
						<a title="Search Test Standard" ng-hide="searchStandardFilterBtn" href="javascript:;" ng-click="showStandardDropdownSearchFilter()"><i class="fa fa-filter"></i></a> 
					 </span>
					 <span ng-hide="searchStandardFilterInput" class="filter-span">
						<input type="text" class="filter-text" placeholder="Enter keyword" ng-model="searchStandard.stdText"/>
						<button title="Close Filter" type="button" class="close filter-close" ng-click="hideStandardDropdownSearchFilter()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 </span>	
				</label>						   
				<select class="form-control" name="test_standard_id"
						ng-required='true' 
						ng-change="setStandardSelectedOption(test_standard_id.selectedOption)" 
						ng-model="test_standard_id.selectedOption"
						ng-options="item.id as item.name for item in (testStandardsList | filter:searchStandard.stdText) track by item.id">
					<option value="">Select Test Standard</option>
				</select>				
				<span ng-messages="editProductTestForm.test_standard_id.$error" 
				ng-if='editProductTestForm.test_standard_id.$dirty  || editProductTestForm.$submitted' role="alert">
					<span ng-message="required" class="error">Test standard is required</span>
				</span>
			</div>					
			<div class="col-xs-2 form-group">
					<label for="wef">Wef:</label>
					<div class="input-group date"  data-provide="datepicker">
						<input type="text" class="backWhite form-control" 
							ng-model="editProductTest.wef"
							name="wef"  readonly
							id="wef"
							placeholder="Wef" />
							<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
							</div>
					</div>
					<span ng-messages="editProductTestForm.wef.$error" 
					 ng-if='editProductTestForm.wef.$dirty  || editProductTestForm.$submitted' role="alert">
						<span ng-message="required" class="error">Wef is required</span>
					</span>
			</div>	
			<div class="col-xs-2 form-group">
					<label for="upto">Upto:</label>
					<div class="input-group date"  data-provide="datepicker">
						<input type="text" class="backWhite form-control" 
							ng-model="editProductTest.upto" readonly
							name="upto" 
							id="upto"
							placeholder="upto" />
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
					</div>
					<span ng-messages="editProductTestForm.upto.$error" 
					 ng-if='editProductTestForm.upto.$dirty  || editProductTestForm.$submitted' role="alert">
						<span ng-message="required" class="error">Upto is required</span>
					</span>
			</div>
			<!-- status---->
			<div class="col-xs-2">
				<label for="status">Status<em class="asteriskRed">*</em></label>	
				<select class="form-control" 
					ng-required='true'  
					name="status" 
					id="status" 
					ng-options="status.name for status in statusList track by status.id"
					ng-model="status.selectedOption">
					<option value="">Select Status</option>
				</select>				   
			
				<span ng-messages="editProductTestForm.status.$error" ng-if='editProductTestForm.status.$dirty  || editProductTestForm.$submitted' role="alert">
					<span ng-message="required" class="error">Status is required</span>
				</span>
			</div>
			<!-- /status---->	
			<div class="col-xs-2">
				<div class="">
					<input type='hidden' ng-model="test_id" name="test_id" ng-value="test_id" ng-model="test_id"> 
					<a href="javascript:;" ng-show="{{defined('EDIT') && EDIT}}"  title="Update"  ng-disabled="editProductTestForm.$invalid"  type='submit' class='mT26 btn btn-primary' ng-click='updateProductTestRecord()'> Update </a>
					<button title="Close"  type='cancel' class='mT26 btn btn-default' ng-click='okRecord(currentTestId)'> Close </button>
				</div>
			</div>
		</div>
	</form>	
</div>	