@extends('layouts.app')
@section('content')
<!--container-->
<div class="container" ng-controller="modulePermissionsController" ng-init="getAllModulesList(0);getAllRolesList();">
	
	<!--display Messge Div-->
	@include('includes.alertMessage')
	<!--/display Messge Div-->
 
	 <!--header-->
	<div class="row header">
		<div class="navbar-form navbar-left" role="search"><strong>Module Permissions</strong></div>
	</div>
	<!--/header-->
	
	<!--account details--->
	<div class="row">
		<div id="no-more-tables">
			<form name='permissionsForm' novalidate>
				<label for="submit">{{ csrf_field() }}</label>		
					<div class="row">
						<div class="col-xs-3">
								<label>Select Roles<em class="asteriskRed">*</em></label>						   
								<select class="form-control" 
										name="role_id"
										ng-model="role_id"
										ng-change="setCurrentRole(role_id.id)"
										ng-required='true'
										ng-options="item.name for item in roleList track by item.id ">
									<option value="">Select Role</option>
								</select>
								<span ng-messages="permissionsForm.role_id.$error" 
									 ng-if='permissionsForm.role_id.$dirty  || permissionsForm.$submitted' role="alert">
									<span ng-message="required" class="error">Select Role!</span>
								</span>
						</div>
						<div class="col-xs-4">
								<label>Select Module<em class="asteriskRed">*</em></label>						   
								<select class="form-control" 
										name="module_id"
										ng-model="module_id"
										ng-change="getSubModules(module_id.id)"
										ng-required='true'
										ng-options="item.name for item in moduleList track by item.id ">
									<option value="">Select Module</option>
								</select>
								<span ng-messages="permissionsForm.module_id.$error" 
									 ng-if='permissionsForm.module_id.$dirty  || permissionsForm.$submitted' role="alert">
									<span ng-message="required" class="error">Select Module</span>
								</span>
						</div>
						<div class="col-xs-4">
								<label>Select Sub Module Menu<em class="asteriskRed">*</em></label>						   
								<select class="form-control" 
										name="sub_module_id"
										ng-model="sub_module_id"
										ng-required='true'
										ng-change="getSubModuleMenus(roleIdScope,moduleIdScope,sub_module_id.id)"
										ng-options="item.name for item in subModuleList track by item.id ">
									<option value=""><span class="select-option">Select Sub Module</span></option>
								</select><i ng-hide="hideLoader" class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom select-box-spinner"></i>
								<span ng-messages="permissionsForm.sub_module_id.$error" 
									 ng-if='permissionsForm.sub_module_id.$dirty  || permissionsForm.$submitted' role="alert">
									<span ng-message="required" class="error">Select Sub Module Menu</span>
								</span>
						</div>				
						<!--<div class="col-xs-1">
							<div class="">
								<button title="Save" ng-disabled="permissionsForm.$invalid" ng-hide="continueSubmitHide" type='submit' class='mT26 btn btn-primary' ng-click='continueFun()' > Continue </button>
							</div>
						</div>-->
					</div>
					<hr>
					<div class="row" ng-if="labelsArray.length" ng-show="finalSubmitHide">
						<div class="col-xs-4" >
							<div class="container">
								<div class="row">  
								  <div class="col-lg-12">
								  <!---------------------------display permissionsmenu list-------------------->
									<div ng-repeat="labelObj in labelsArray" class="outerDiv"> 
										<div ng-repeat="label in labelObj"> 
										  <input type="checkbox" 
												 class="check-box" 
												 name="module_menu_id[]"
												 ng-value="label.id"
												 id="parent_[[label.id]][[$index]]" 
												 ng-model="label.selected" 
												 ng-click="checkMenuOptionList(label)"> 
										  <label for="parent_[[label.id]][[$index]]" class="check-box-label preLabel">[[label.label]]</label>
											  <ul class="check-box-ul">
												<li ng-repeat="option in label.options">
												  <input type="checkbox" 
														 ng-click="childClick(label,option)"
														 class="check-box"
														 name="[[label.id]][]"
														 ng-value="option.optionValue"
														 id="child_[[option.optionValue]][[label.id]]"
														 ng-model="option.selected" > 
												  <label for="child_[[option.optionValue]][[label.id]]" class="check-box-label">[[option.optionValue]]</label>
												</li>
											  </ul> 
										</div>
										<div ng-if="!labelsArray.length">No Record Found!</div>
									</div>  
								  <!---------------------------display permissionsmenu list-------------------->									
								  </div> 
								</div>
							</div> 
						</div>	 					
						<div class="col-xs-12" ng-if="labelsArray.length">
							<div class="pull-right">
								<button title="Save" ng-disabled="permissionsForm.$invalid" type='submit' class='mT26 btn btn-primary' ng-click='savePermissionsFun()'> Save </button>		
								<button title="Cancel" type='submit' class='mT26 btn btn-default' ng-click='cancelFun()'> Cancel </button>
							</div>
						</div>
					</div>
				</form>			
			</div>
		</div>
	<!--/account details-->
	
</div>
<script type="text/javascript" src="{!! asset('public/ang/controller/modulePermissionsController.js') !!}"></script>		
@endsection