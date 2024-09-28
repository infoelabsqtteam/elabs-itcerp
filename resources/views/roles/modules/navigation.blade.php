@extends('layouts.app')

@section('content')
    
<div ng-controller="modulesController" class="container ng-scope" ng-init="">
    
    <!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
	
	<!--Navigation setting-->
	<div class="row" ng-hide="navFormBladeDiv">
		<div class="panel panel-default">
			<div class="panel-body">           
				<div class="row header-form">
					<div role="new" class="navbar-form navbar-left">            
						<span class="pull-left"><strong id="form_title">Navigation</strong></span>
					</div>            
					<div role="new" class="navbar-right">
						<button type="submit" class="btn btn-primary" ng-click="sortNavigationList()" style="margin-right: 19px !important;margin-top: 2px;">Order Navigation</button>
					</div>
				</div>               
				<form name="erpNavigationModuleForm" id="erpNavigationModuleForm" method="POST" novalidate>
					<div class="row">
						<!--Roles-->
						<div class="col-xs-6">
							<label>Select Roles<em class="asteriskRed">*</em></label>						   
							<select class="form-control" 
								name="role_id"
								ng-model="navigation.role_id"
								ng-required='true'
								ng-change="getSubModulesMenuItems(navigation.module_id.id,navigation.role_id.id)"
								ng-options="item.name for item in roleList track by item.id ">
								<option value="">Select Role</option>
							</select>
							<span ng-messages="erpNavigationModuleForm.role_id.$error" ng-if='erpNavigationModuleForm.role_id.$dirty  || erpNavigationModuleForm.$submitted' role="alert">
								<span ng-message="required" class="error">Select Role!</span>
							</span>
						</div>
						<!--/Roles-->
						
						<!--Modules-->
						<div class="col-xs-6">
							<label>Select Module<em class="asteriskRed">*</em></label>						   
							<select class="form-control" 
									name="module_id"
									ng-model="navigation.module_id"
									ng-change="getSubModulesMenuItems(navigation.module_id.id,navigation.role_id.id)"
									ng-required='true'
									ng-options="item.name for item in moduleList track by item.id ">
								<option value="">Select Module</option>
							</select>
							<span ng-messages="erpNavigationModuleForm.module_id.$error" ng-if='erpNavigationModuleForm.module_id.$dirty || erpNavigationModuleForm.$submitted' role="alert">
								<span ng-message="required" class="error">Select Module</span>
							</span>
						</div>
						<!--/Modules-->
					</div>

					<div class="row mT20" ng-if="menuSubmenuList.length">                    
						<div class="col-xs-12 form-group view-record">
							<label class="text-center" for="module_heading">Select the Module</label>
							<div id="no-more-tables" class="fixed_table">
								<table class="col-sm-12 table-striped table-condensed cf">										
									<tbody>									
										<tr ng-repeat="menuSubmenuLevelOne in menuSubmenuList">
											<td data-title="Module Name">
												<strong>
													<span class="nav-cat">
														<input ng-model="menuSubmenuLevelOne.selected" ng-disabled="modulesDisabled == 1 || modulesDisabled == 2 || modulesDisabled == 73" type="checkbox" ng-checked="selectedModuleList.indexOf(menuSubmenuLevelOne.id) > -1" name="module_menu_id[]" ng-value="menuSubmenuLevelOne.id" ng-click="funParentChildrenChecker(menuSubmenuLevelOne)">
													</span>
													<span>[[menuSubmenuLevelOne.module_name]]</span>
												</strong>
												<table ng-if="menuSubmenuLevelOne.children.length" class="col-sm-12 table-striped table-condensed">
													<tbody>
														<tr ng-repeat="subMenuSubmenuLevelTwo in menuSubmenuLevelOne.children track by $index">
															<td data-title="Children Name">
																<span class="nav-sub-cat">
																	<input ng-model="subMenuSubmenuLevelTwo.selected" ng-disabled="modulesDisabled == 1 || modulesDisabled == 2 || modulesDisabled == 73" type="checkbox" ng-checked="selectedModuleList.indexOf(subMenuSubmenuLevelTwo.id) > -1" name="module_menu_id[]" ng-value="subMenuSubmenuLevelTwo.id" ng-click="funChildrenParentChecker(this)">
																</span>
																<span>[[subMenuSubmenuLevelTwo.module_name]]</span>
															</td>
														</tr>
													</tbody>
												</table>
											</td>											
										</tr>											
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<!--Save Button-->
					<div class="row" ng-if="menuSubmenuList.length">
						<div class="col-xs-12 form-group text-right">
							<label for="submit">{{ csrf_field() }}</label>					
							<button type="submit" ng-disabled="erpNavigationModuleForm.$invalid || modulesDisabled == 1 || modulesDisabled == 2 || modulesDisabled == 73" class="btn btn-primary" ng-click="funSaveModuleNavigation()">Save</button>
							<button type="button" class="btn btn-default" ng-click="cancalSave()">Cancel</button>
						</div>
					</div>
					<!--/Save Button-->
                  
				</form>
			</div>
		</div>
	</div>
	<!--Navigation setting-->

	<!--Navigation oreder sorting setting-->
	<div class="row" ng-hide="sortNavigationListHide">
	  @include('roles.modules.navigation_sorting')
	</div> 
	<!--Navigation oreder sorting setting-->
	
</div>
    
<!--including angular controller file-->
<script type="text/javascript" src="{!! asset('public/ang/controller/modulesController.js') !!}"></script>
    
@endsection