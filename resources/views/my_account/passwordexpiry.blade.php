@extends('layouts.public')

@section('content')
	
<section id="login-page">

	<div ng-controller="LoginController" class="container ng-scope">

		<section id="Main_text">
			<h1 class="text-center">
				<a class="noanchor" href="{{SITE_URL}}">
					<span><img alt="logo" height="29px" src="{!! asset('public/images/template_logo.png') !!}"></span>
					<span class="logo-txt">{{defined('SITE_NAME') && !empty(SITE_NAME) ? SITE_NAME : 'ITC LAB'}}</span>
				</a>
			</h1>
		</section>
		<section id="login">
			<div class="col-md-8 col-md-offset-2">
			
				@if(Session::has('successMsg'))
					<div id="successMsg" role="alert" class="alert alert-success closeMessagegAlert">{{ Session::get('successMsg') }}</div>
				@elseif(Session::has('errorMsg'))
					<div id="errorMsg" role="alert" class="alert alert-danger closeMessagegAlert">{{ Session::get('errorMsg') }}</div>
				@elseif(Session::has('alertMsg'))
					<div id="errorMsg" role="alert" class="alert alert-info closeMessagegAlert">{{ Session::get('alertMsg') }}</div>
				@endif
				
				<div class="panel panel-default mT10">
					<div class="panel-heading">{{$title}}</div>				
					<div class="panel-body">					
						<form class="form-horizontal" id="erpUserPasswordExpiryForm" name="erpUserPasswordExpiryForm" role="form" method="POST" action="{{ url('/change-expiry-password') }}">
							
							<div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
								<label for="email" class="col-md-4 control-label">Current Password<em class="asteriskRed">*</em></label>
								<div class="col-md-6">
									<input
										id="old_password"
										type="text"
										class="form-control"
										name="old_password"
										ng-model="userPasswordReset.old_password"
										ng-required='true'
										placeholder="Current Password"
										autocomplete="off"
										autofocus>
									<span ng-messages="erpUserPasswordExpiryForm.old_password.$error" ng-if='erpUserPasswordExpiryForm.old_password.$dirty || erpUserPasswordExpiryForm.$submitted' role="alert">
										<span ng-message="required" class="error">Current Password is required</span>
									</span>
									@if ($errors->has('old_password'))
										<span class="help-block">{{ $errors->first('old_password') }}</span>
									@endif
								</div>
							</div>
				
							<div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
							    <label for="password" class="col-md-4 control-label">New Password<em class="asteriskRed">*</em></label>
							    <div class="col-md-6">
								<input
									id="new_password"
									type="password"
									autocomplete="off"
									class="form-control"
									name="new_password"
									ng-model="userPasswordReset.new_password"
									ng-required='true'
									placeholder="New Password">
								<span ng-messages="erpUserPasswordExpiryForm.new_password.$error" ng-if='erpUserPasswordExpiryForm.new_password.$dirty || erpUserPasswordExpiryForm.$submitted' role="alert">
									<span ng-message="required" class="error">New Password is required</span>
								</span>
								@if ($errors->has('new_password'))
									<span class="help-block">{{ $errors->first('new_password') }}</span>
								@endif
							    </div>
							</div>
				
							<div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
								<label for="password-confirm" class="col-md-4 control-label">Confirm Password<em class="asteriskRed">*</em></label>
								<div class="col-md-6">
									<input
										id="password-confirm"
										autocomplete="off"
										type="password"
										class="form-control"
										name="confirm_password"
										ng-model="userPasswordReset.confirm_password"
										ng-required='true'
										placeholder="Password Confirmation">
									<span ng-messages="erpUserPasswordExpiryForm.confirm_password.$error" ng-if='erpUserPasswordExpiryForm.confirm_password.$dirty || erpUserPasswordExpiryForm.$submitted' role="alert">
										<span ng-message="required" class="error">Confirm Password is required</span>
									</span>
									@if ($errors->has('confirm_password'))
										<span class="help-block">{{ $errors->first('confirm_password') }}</span>
									@endif
								</div>
							</div>
				
							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<label for="csrf_field">{{ csrf_field() }}</label>
									<button type="submit" ng-disabled="erpUserPasswordExpiryForm.$invalid" class="btn btn-primary">{{$title}}</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>
	
<script type="text/javascript" src="{!! asset('public/ang/controller/LoginController.js') !!}"></script>
@endsection