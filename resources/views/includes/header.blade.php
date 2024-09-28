<header>
	<?php
	$helper	  		= new Helper();
	$path     		= explode("/",Request::path());
	$url      		= !empty($path[0]) ? trim($path[0]) : '';
	$company  		= $helper->getComapnyDetails();
	$userRoleData 	= $helper->getAllUserRolesList();
	?>
	<div class="container-fluid">
		<nav>
			<div class="row"> 				
				<div class="navbar navbar-default">
				
					<!-- navbar-header -->
					<div class="navbar-header">
						<button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<!-- /navbar-header -->
					
					<div class="collapse navbar-collapse">
						
						<!--left-navigation-->
						@if(defined('LEFTNAVIGATIONS') && !empty(LEFTNAVIGATIONS))
						<div class="left-nav">
							<ul class="nav navbar-nav">
								@foreach(LEFTNAVIGATIONS as $key => $navigationLevelOne)
									<li class="dropdown">
										@foreach($navigationLevelOne as $keynav => $navigationLevelTwo)
											@if(strtolower($keynav) == 'dashboard')
												<a href="{{ url('/') }}" title="<?php echo ucwords($keynav);?>" class="das-img">
													<img alt="<?php echo ucwords($keynav);?>" height="30px" src="{!! asset('public/images/template_logo.png') !!}">
												</a>
											@else
												<a @if(strpos(Request::path(),strtolower($keynav)) !== false) class="active-parent" @endif href="javascript:;" class="" data-toggle="dropdown"><?php echo ucwords($keynav);?><b class="caret"></b></a>
												<ul class="dropdown-menu">
													@foreach($navigationLevelTwo as $keynav => $navigation)
														<li>
															<a tabindex="-1" href="{{ url($navigation['menu_module_link']) }}"><?php echo trim($navigation['menu_module_name']);?></a>
														</li>
													@endforeach
												</ul>
											@endif				
										@endforeach
									</li>
								@endforeach
							</ul>							
						</div>
						@endif
						<!--/left-navigation-->
						
						<!--right-navigation-->
						@if(defined('RIGHTNAVIGATIONS') && !empty(RIGHTNAVIGATIONS))
						<div class="right-nav">
							
							@if(!defined('IS_ADMIN'))
								<!--Notification-->
								@include('notifications.userNotification')
								<!--/Notification-->
							@endif
						
							<ul class="nav navbar-nav">
								@foreach(RIGHTNAVIGATIONS as $key => $rightNavigationLevelOne)
									<li class="dropdown">
										@foreach($rightNavigationLevelOne as $keynav => $rightNavigationLevelTwo)											
											<a href="javascript:;" @if(strpos(Request::path(),'profile') !== false) class="active-parent" @endif class="" data-toggle="dropdown">
												<span class="fontbd font16">{{defined('ROLENAME') ? ROLENAME : '-'}}&nbsp;<i class="fa fa-user fa-2x" aria-hidden="true"></i>&nbsp;<b class="caret"></b></span>
											</a>
											<ul class="dropdown-menu">
												<li>
													<span class="profile">{{ ucfirst(Auth::user()->name) }}!</span>
												</li>
												@foreach($rightNavigationLevelTwo as $keynav => $rightNavigation)
													<li>
														@if(strtolower($rightNavigation['menu_module_name']) == 'logout')
															<a href="javascript:;" tabindex="-1" data-toggle="modal" data-target="#sessionLogout"><?php echo trim($rightNavigation['menu_module_name']);?></a>
														@else
															<a href="{{ url($rightNavigation['menu_module_link']) }}"><?php echo trim($rightNavigation['menu_module_name']);?></a>
														@endif
													</li>
												@endforeach
											</ul>											
										@endforeach
									</li>
								@endforeach
							</ul>	
						</div>
						@endif
						
					</div><!-- end collapse -->
				</div>	
			</div>
			
			<!--Bottom-navigation-->
			@if(defined('SUBNAVIGATIONS') && !empty(SUBNAVIGATIONS))
			<div class="row">
				
				<ul class="submenulist">
					@foreach(SUBNAVIGATIONS as $key => $subNavigation)
						<li>
							<a @if(Request::path() == $subNavigation['module_link']) class="active" @endif href="{{ url($subNavigation['module_link']) }}"><?php echo trim($subNavigation['module_name']);?></a>
						</li>
					@endforeach
					
					@if(!empty($userRoleData))
					<li class="txt-right">
						<form name="erpSwitchUserAssignedRoleForm" method="POST" action="{{ url('profile/switch-user-role') }}" id="erpSwitchUserAssignedRoleForm">
							<select
								id="role_id"
								onChange="funConfirmUserRoleSwitch(this.value,this.form)"
								name="role_id">
								<option value="">Select Role</option>
								@foreach($userRoleData as $key => $values)
									<option
									@if(defined('ROLEID') && ROLEID == $values->id)
									selected="selected"
									@endif
									value="{{$values->id}}">{{$values->name}}
									</option>
								@endforeach
							</select>
							<label for="submit">{{ csrf_field() }}</label>
							<input type="hidden" value="{{defined('ROLEID') ? ROLEID : '0'}}" id="selected_role_id">
							<script>function funConfirmUserRoleSwitch(value,form){if(value != '' && confirm("Do you really want to switch the role?")) {form.submit();}else{$("#role_id").val($("#selected_role_id").val())}}</script>
						</form>
					<li>
					@endif
				</ul>
					
				<!--Loader Div On Action-->
				<div id="global_loader" style="display:none;" class="global_loader_small"><div class="small_loader">ITC</div></div>
				<!--/Loader Div On Action-->
				
			</div>
			@endif
			<!--/Bottom-navigation-->			
		</nav>
	</div>
		
	<!--Loader Div On Load-->
	<div id="global_loader_onload" class="global_loader"><div class="loader">ITC</div></div>
	<!--/Loader Div On Load-->
	
	@if(defined('USERID') && !empty(USERID))
		<!--Logout Popup Model-->
		@include('includes.logoutPopupModel')
		<!--Logout Popup Model-->
	@endif
	
	<!--Session Validator Popup Model-->
	@include('includes.sessionValidatorPopupModel')
	<!--Session Validator Popup Model-->

	<style>
	.dropdown-submenu {position: relative;}	
	.dropdown-submenu .dropdown-menu {top: 0;left: 100%;margin-top: -1px;}
	</style>
</header>
	
<div id="container-main" style="display:none;">

	<!--error Popup Model-->
	@include('includes.errorPopupModel')
	<!--/error Popup Model-->