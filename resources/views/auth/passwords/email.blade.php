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

			<form class="form-horizontal" id="erpUserEmailSendForm" name="erpUserEmailSendForm" role="form" method="POST" action="{{ url('/password/email') }}">
				{{ csrf_field() }}
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<label for="email" class="col-md-4 control-label">E-Mail Address</label>
					<div class="col-md-6">
						<input
							id="email"
							type="email"
							class="form-control"
							name="email"
							ng-model="userEmailSend.email"
							ng-required='true'
							value="{{ old('email') }}"
							placeholder="E-Mail Address">
						<span ng-messages="erpUserEmailSendForm.email.$error" ng-if='erpUserEmailSendForm.email.$dirty || erpUserEmailSendForm.$submitted' role="alert">
							<span ng-message="required" class="error">E-Mail Address is required</span>
						</span>
						@if ($errors->has('email'))
						    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
						@endif
					</div>
				</div>	
				<div class="form-group">
					<div class="col-md-6 col-md-offset-4">                                
					    <a class="btn btn-default pull-right mL10" href="{{url('/login')}}">Back</a>
					    <button type="submit" ng-disabled="erpUserEmailSendForm.$invalid" class="btn btn-primary pull-right">Send Password Reset Link</button>
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
