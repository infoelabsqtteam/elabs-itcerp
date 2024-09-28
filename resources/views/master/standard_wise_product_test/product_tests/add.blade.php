 <div ng-model="addProFormDiv" ng-hide="addProFormDiv">
		<div class="row header1">
			      <strong class="pull-left headerText">Add Standard Wise Product Test</strong>								
			      <strong class="pull-right headerText hidden">
					      <input type="submit" ng-click="showUploadCsvForm()" style="margin: -1px 0px;" value="Upload CSV" class="uploaCSVbtn btn btn-primary">
			      </strong>
			      
		</div>
		
		<form name='addtestProductForm' mg-model="addtestProForm" id="add_product_test_form" novalidate>
			<div class="row">
            <label for="submit">{{ csrf_field() }}</label>
			<div class="col-xs-2">
					<label for="wef">Product</label>
					<a title="Select Category" data-toggle="modal"  title="Select Product Category" ng-click="showProductCatTreeViewPopUp(3)"class='generate cursor-pointer'> Tree View </a>
					<select class="form-control" name="product_id"
							ng-model="product_id" id="product_id"
							ng-required='true'
							ng-options="item.name for item in productList track by item.id">
						<option value="">Select Product</option>
					</select>
					<span ng-messages="addtestProductForm.product_id.$error" 
					 ng-if='addtestProductForm.product_id.$dirty  || addtestProductForm.$submitted' role="alert">
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
						ng-model="test_standard_id"
						ng-required='true'  
						ng-options="item.id as item.name for item in (testStandardsList | filter:searchStandard.stdText) track by item.id">
					<option value="">Select Test Standard</option>
				</select>
				<span ng-messages="addtestProductForm.test_standard_id.$error" 
				ng-if='addtestProductForm.test_standard_id.$dirty  || addtestProductForm.$submitted' role="alert">
					<span ng-message="required" class="error">Test standard is required</span>
				</span>
			</div>
			<div class="col-xs-2 form-group">
					<label for="wef">Wef:</label>
					<div class="input-group date" data-provide="datepicker">
						<input type="text" class="backWhite form-control" 
							ng-model="wef" name="wef" readonly id="wef"  
							placeholder="Wef" />
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
					</div>
					<span ng-messages="addtestProductForm.wef.$error" 
					 ng-if='addtestProductForm.wef.$dirty  || addtestProductForm.$submitted' role="alert">
						<span ng-message="required" class="error">Wef is required</span>
					</span>
			</div>	
			<div class="col-xs-2 form-group">
					<label for="upto">Upto:</label>
					<div class="input-group date"  data-provide="datepicker">
						<input type="text" class="backWhite form-control" 
							ng-model="upto" readonly
							name="upto" 
							id="upto" 
							placeholder="upto" />
							<div class="input-group-addon">
									<span class="glyphicon glyphicon-th"></span>
							</div>
					</div>
					<span ng-messages="addtestProductForm.upto.$error" 
					 ng-if='addtestProductForm.upto.$dirty  || addtestProductForm.$submitted' role="alert">
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
		
    			<span ng-messages="addtestProductForm.status.$error" ng-if='addtestProductForm.status.$dirty  || addtestProductForm.$submitted' role="alert">
    				<span ng-message="required" class=" error">Status is required</span>
        		</span>
        	</div>
        	<!-- /status---->
			<div class="col-xs-2">
				<div class="">
					<a href="javascript:;" ng-if="{{defined('ADD') && ADD}}" title="Save" ng-disabled="addtestProductForm.$invalid"  type='submit' class='mT26 btn btn-primary' ng-click='addRecord()'> Save </a>
					<button title="Reset"  type="button" class="mT26 btn btn-default" ng-click="resetForm()" data-dismiss="modal">Reset</button>

				</div>
			</div>
		</div>
	</form>	
</div>