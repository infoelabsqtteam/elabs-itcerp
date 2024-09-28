<div class="row" ng-hide="listProductTestsDiv">
	<div class="panel panel-default">
		<div class="panel-body">							
			<div class="row header-form">
				<div role="new" class="navbar-form navbar-left">            
					<span class="pull-left"><strong id="form_title" ng-click="funTestProductStandardParamentersList(GlobalViewProductTestId)">Standard Wise Product Test Parameters ([[allProductTestParametersArr.length]])</strong></span>
				</div>							 
				<div role="new" class="navbar-right">
					<a type="submit" href="javascript:;" class="btn btn-default backtoNav" ng-click="hideProTestParametersListView()">Back</a>		 
				</div>
			</div>
			<div class="row bgWhite">
				<div class="col-xs-4">
					<label>Test Code:</label>
					<span class="color-green" ng-bind="test_code_text" ng-model="test_code_text"></span>
				</div>
					
				<div class="col-xs-4">
					<label>Product Category:</label>
					<span class="color-green" ng-bind="product_category_text" ng-model="product_category_text"></span>
				</div>
				<div class="col-xs-4">
					<label>Product Name:</label>
					<span class="color-green" ng-bind="product_text" ng-model="product_text"></span>
				</div>
				<div class="col-xs-4">
					<label>Test Standard Name:</label>
					<span class="color-green" ng-bind="test_standard_text" ng-model="test_standard_text"></span>
				</div>				
				<div class="col-xs-4">
					<label>Wef:</label>
					<span class="color-green" ng-bind="wef_text" ng-model="wef_text"></span>
				</div>					
				<div class="col-xs-4">
					<label>Upto:</label>
					<span class="color-green" ng-bind="upto_text" ng-model="upto_text"></span>
				</div>
			</div>
			
			<hr>
				
			<div class="advancedDemo" ng-if="!allProductTestParametersList.length"><h4>No Parameter Available!</h4></div>
				
			<div class="advancedDemo" ng-if="allProductTestParametersList.length">
				<div ng-repeat="containers in model">
					<div class="dropzone box box-yellow" style = "width: 100%;">
						<ul dnd-list="containers"
							dnd-allowed-types="['container']"
							dnd-external-sources="false"
							dnd-dragover="dragoverCallback(index, external, type, callback)"
							dnd-drop="dropCallback(index, item, external, type)">
							<li ng-repeat="container in containers"
								dnd-draggable="container"
								dnd-type="'container'"
								dnd-effect-allowed="copyMove"
								dnd-moved="containers.splice($index, 1)"
								dnd-callback="container.items.length">
								<div class="container-element box box-blue">
									<h3 ng-click="displayContent(container.id,GlyphiconPlus)">[[$index+1]].[[container.categoryName]]<i id="id_[[container.id]]" class="glyphicon-plus mL5 glyphicon right-nav"></i></h3>
									<ul id="ul_id_[[container.id]]" style="display:none;"
										dnd-list="container.items"
										dnd-allowed-types="['item']"
										dnd-horizontal-list="true"
										dnd-external-sources="false"
										dnd-effect-allowed="[[container.effectAllowed]]"
										dnd-dragover="dragoverCallback(index, external, type)"
										dnd-drop="dropCallback(index, item, external, type)"
										dnd-inserted="logListEvent('inserted at', index, external, type)"
										class="itemlist parameterUl">
										<li ng-repeat="item in container.items"
											dnd-draggable="item"
											dnd-type="'item'"
											dnd-effect-allowed="[[item.effectAllowed]]"
											dnd-dragstart="logEvent('Started to drag an item')"
											dnd-moved="container.items.splice($index, 1)"
											dnd-dragend="logEvent('Drag operation ended. Drop effect: ' + dropEffect)">
											<span ng-bind-html="item.label"></span>
										</li>
									</ul>
									<div class="clearfix"></div>
								</div>
							</li>
						</ul>
						<div class="col-xs-12 form-group mT30">
							<button type="button" class="dropdown btn btn-primary dropdown-toggle" data-toggle="dropdown">Download</button>
							<div class="dropdown-menu">
							    <form class="form-inline" method="POST" role="form" name="erpTestMasterParameterForm" action="{{ url('standard-wise-product/product-test/generate-test-master-documents') }}">
								<label for="submit">{{ csrf_field() }}</label>
								<input type="hidden" ng-model="currentTestId" name="test_id" ng-value="currentTestId">
								<input type="submit" formtarget="_blank" name="generate_product_test_documents" value="PDF" class="dropdown-item">
								<input type="submit" formtarget="_blank" name="generate_product_test_documents" value="Excel" class="dropdown-item">
							    </form>
							</div>
							<button type="submit" class="btn btn-primary" ng-click="saveNavigationOrdering()">Update</button>
							<button type="submit" class="btn btn-primary" ng-click="funShowClonePopup(currentTestId,parentCategoryId,product_cat_id)">Clone</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<link href="{!! asset('public/css/demo-framework.css') !!}" rel="stylesheet" type="text/css"/>
</div>
	
<!-- ************************************** clone product test form  end**************************** -->
@include('master.standard_wise_product_test.product_tests.productTestClonePopup')
<!-- ************************************** clone product test form  end**************************** -->