@extends('layouts.public')

@section('content')

<!--Section-->
<section id="login-page">
	<div ng-controller="LoginController" class="container">
		<section id="Main_text">
			<h1 class="text-center">
				<a class="noanchor" href="{{url('/')}}">
					<span><img alt="logo" height="29px" src="{!! asset('public/images/template_logo.png') !!}"></span>
					<span class="logo-txt">{{defined('SITE_NAME') && !empty(SITE_NAME) ? SITE_NAME : 'ITC LAB'}}</span>
				</a>
			</h1>
		</section>
		<section id="login">
			<div class="row">
				<div class="col-md-6  col-sm-8 col-sm-offset-4 col-md-offset-3">
				
					@if(Session::has('successMsg'))
						<div id="successMsg" role="alert" class="alert alert-success closeMessagegAlert">{{ Session::get('successMsg') }}</div>
					@endif
					@if(Session::has('errorMsg'))
						<div id="errorMsg" role="alert" class="alert alert-danger closeMessagegAlert">{{ Session::get('errorMsg') }}</div>
					@endif
					@if(Session::has('alertMsg'))
						<div id="alertMsg" role="alert" class="alert alert-info closeMessagegAlert">{{ Session::get('alertMsg') }}</div>
					@endif
					
					<div class="panel panel-default">
						<div class="panel-heading"> <strong class="">Login</strong></div>
						
						<!--error msg-->
						@if($errors->has('email'))
						<div class="col-md-12 alert alert-danger alert-custom"><span class="help-block helpblock">{{ $errors->first('email') }}</span></div>
						@elseif($errors->has('password'))
						<div class="col-md-12 alert alert-danger alert-custom"><span class="help-block helpblock">{{ $errors->first('password') }}</span></div>
						@endif
						<!--/error msg-->
						
						<!--panel-body-->
						<div class="panel-body">
							<!--Login form-->
							<form class="form-horizontal" role="form" method="POST" id="erpUserLoginForm" name="erpUserLoginForm" action="{{ url('/login') }}">
								
								<!--Email-->
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label for="email" class="col-md-4 control-label">E-Mail</label>
									<div class="col-md-6">
										<input
											id="email"
											type="email"
											placeholder="E-Mail"
											class="form-control"
											name="email"
											ng-model="userLogin.email"
											ng-required='true'
											value="@if(!empty(Cookie::get('itcerp_username')))
											{{Cookie::get('itcerp_username')}}
											@else
											{{old('email')}}
											@endif"
											autofocus>
										<span ng-messages="erpUserLoginForm.email.$error" ng-if='erpUserLoginForm.email.$dirty || erpUserLoginForm.$submitted' role="alert">
											<span ng-message="required" class="error">E-Mail is required</span>
										</span>
									</div>
								</div>
								<!--/Email-->
								
								<!--Password-->
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label for="password" class="col-md-4 control-label">Password</label>	
									<div class="col-md-6">
										<input
											id="password"
											type="password"
											placeholder="Password"
											ng-model="userLogin.Password"
											ng-required='true'
											value="@if(!empty(Cookie::get('itcerp_password'))){{Cookie::get('itcerp_password')}}@endif"
											class="form-control"
											name="password">
										<span ng-messages="erpUserLoginForm.password.$error" ng-if='erpUserLoginForm.password.$dirty || erpUserLoginForm.$submitted' role="alert">
											<span ng-message="required" class="error">Password is required</span>
										</span>
									</div>
								</div>
								<!--/Password-->
							
								<!--Remember Me-->
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<div class="checkbox">
											<label>
												<input
													type="checkbox"
													@if(!empty($_COOKIE['itcerp_username']))
													checked
													@endif
													name="remember"> Remember Me
											</label>
										</div>
									</div>
								</div>
								<!--/Remember Me-->
								
								<!--Login Button-->
								<div class="form-group">
									<div class="col-md-8 col-md-offset-4">
										<label>{{ csrf_field() }}</label>
										<button type="submit" ng-disabled="erpUserLoginForm.$invalid" class="btn btn-primary">Login</button>
										<a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
									</div>
								</div>
								<!--/Login Button-->
								
							</form>
							<!--/Login form-->
						</div>
						<!--/panel-body-->
					</div>
				</div>
			</div>
		</section>
	</div>
</section>
<!--/Section-->

<script type="text/javascript" src="{!! asset('public/ang/controller/LoginController.js') !!}"></script>
@endsection