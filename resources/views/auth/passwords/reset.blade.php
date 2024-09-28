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
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form class="form-horizontal" id="erpUserPasswordResetForm" name="erpUserPasswordResetForm" role="form" method="POST" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input
					id="email"
					type="email"
					class="form-control"
					name="email"
					value="{{ $email or old('email') }}"
					ng-model="userPasswordReset.email"
					ng-required='true'
					autofocus>
				<span ng-messages="erpUserPasswordResetForm.email.$error" ng-if='erpUserPasswordResetForm.email.$dirty || erpUserPasswordResetForm.$submitted' role="alert">
					<span ng-message="required" class="error">E-Mail Address is required</span>
				</span>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input
					id="password"
					type="password"
					class="form-control"
					name="password"
					ng-model="userPasswordReset.password"
					ng-required='true'
					placeholder="Password">
				<span ng-messages="erpUserPasswordResetForm.password.$error" ng-if='erpUserPasswordResetForm.password.$dirty || erpUserPasswordResetForm.$submitted' role="alert">
					<span ng-message="required" class="error">Password is required</span>
				</span>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input
					id="password-confirm"
					type="password"
					class="form-control"
					name="password_confirmation"
					ng-model="userPasswordReset.password_confirmation"
					ng-required='true'
					placeholder="Password Confirmation">
				<span ng-messages="erpUserPasswordResetForm.password_confirmation.$error" ng-if='erpUserPasswordResetForm.password_confirmation.$dirty || erpUserPasswordResetForm.$submitted' role="alert">
					<span ng-message="required" class="error">Password Confirmation is required</span>
				</span>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" ng-disabled="erpUserPasswordResetForm.$invalid" class="btn btn-primary">Reset Password</button>
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