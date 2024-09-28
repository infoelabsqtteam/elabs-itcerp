	<link href="{!! asset('public/css/demo-framework.css') !!}" rel="stylesheet" type="text/css"/>
	<div class="panel panel-default">
		<div class="panel-body">          
			<div class="row header-form">
				<div role="new" class="navbar-form navbar-left">            
					<span class="pull-left"><strong id="form_title">Manage Navigation Order</strong></span>
				<select class="form-control order-select" ng-required='true'  
							 name="ordering_type" id="ordering_type" ng-change="getOrderingType(orderType.selectedOption.id)"
							 ng-options="option.name for option in orderType.availableTypeOptions track by option.id"
							 ng-model="orderType.selectedOption"><option value="">Select Ordering Type</option></select>
				</div>							 
				<div role="new" class="navbar-right">
					<a type="submit" href="javascript:;" class="btn btn-default backtoNav" ng-click="backtoNavigationSetting()">Back</a>		 
				</div>
			</div>       
			<div class="advancedDemo row" ng-if="levelOneSorting">
				<div ng-repeat="containers in model">
					<div class="dropzone box box-yellow col-md-12">
						<ul dnd-list="containers"
							dnd-allowed-types="['container']"
							dnd-external-sources="false"
							dnd-dragover="dragoverCallback(index, external, type, callback)"
							dnd-drop="dropCallback(index, item, external, type)">
							<li class="col-md-12" ng-repeat="container in containers"
								dnd-draggable="container"
								dnd-type="'container'"
								dnd-effect-allowed="copyMove"
								dnd-moved="containers.splice($index, 1)"
								dnd-callback="container.items.length">
								<div class="container-element box box-blue">
									<h3>[[$index+1]]. [[container.name]] <i ng-click="displayContent(container.id,GlyphiconPlus)" id="id_[[container.id]]" class="glyphicon-plus mL5 glyphicon right-nav"></i></h3>
									<ul id="ul_id_[[container.id]]" style="display:none;"
									    dnd-list="container.items"
										dnd-allowed-types="['item']"
										dnd-horizontal-list="true"
										dnd-external-sources="false"
										dnd-effect-allowed="[[container.effectAllowed]]"
										dnd-dragover="dragoverCallback(index, external, type)"
										dnd-drop="dropCallback(index, item, external, type)"
										dnd-inserted="logListEvent('inserted at', index, external, type)"
										class="itemlist">
										<li ng-repeat="item in container.items"
											dnd-draggable="item"
											dnd-type="'item'"
											dnd-effect-allowed="[[item.effectAllowed]]"
											dnd-dragstart="logEvent('Started to drag an item')"
											dnd-moved="container.items.splice($index, 1)"
											dnd-dragend="logEvent('Drag operation ended. Drop effect: ' + dropEffect)">
											[[item.label]] 
										</li>
									</ul>
									<div class="clearfix"></div>
								</div>
							</li>
						</ul>
						<div class="col-xs-12 form-group text-right">
							 <button type="submit" class="btn btn-primary" ng-click="saveNavigationOrdering()">Update</button>
						</div>
					</div>
				</div>
		</div>			
		<div class="advancedDemo row" ng-if="levelTwoSorting">
			<div ng-repeat="containersLevelTwo in modelLevelTwo">
					<div class="dropzone box box-yellow">
						<ul dnd-list="containersLevelTwo"
							dnd-allowed-types="['container']"
							dnd-external-sources="true"
							dnd-dragover="dragoverCallback(index, external, type, callback)"
							dnd-drop="dropCallback(index, item, external, type)">
							<li ng-repeat="container in containersLevelTwo" ng-if="container.itemsLevelTwo.length">
								<div class="container-element box box-blue">
									<h3> [[container.name]] <i ng-click="displayContent(container.id,GlyphiconPlus)" id="id_[[container.id]]" class="glyphicon-plus mL5 glyphicon pull-right"></i></h3>
									<ul id="ul_id_[[container.id]]" style="display:none;"
										dnd-list="container.itemsLevelTwo"
										dnd-allowed-types="['item']"
										dnd-horizontal-list="true"
										dnd-external-sources="true"
										dnd-effect-allowed="[[container.effectAllowed]]"
										dnd-dragover="dragoverCallback(index, external, type)"
										dnd-drop="dropCallback(index, item, external, type)"
										dnd-inserted="logListEvent('inserted at', index, external, type)"
										class="itemlist">
										<li ng-repeat="item in container.itemsLevelTwo"
											dnd-draggable="item"
											dnd-type="'item'"
											dnd-effect-allowed="[[item.effectAllowed]]"
											dnd-dragstart="logEvent('Started to drag an item')"
											dnd-moved="container.itemsLevelTwo.splice($index, 1)"
											dnd-dragend="logEvent('Drag operation ended. Drop effect: ' + dropEffect)">
											[[item.label]] 
										</li>
									</ul>
									<div class="clearfix"></div>
								</div>
							</li>
						</ul>
						<div class="col-xs-12 form-group text-right">
							 <button type="submit" class="btn btn-primary" ng-click="saveNavigationMenuOrdering()">Update</button>
						</div>
					</div>
				</div>
		</div>
	</div>
</div> 


