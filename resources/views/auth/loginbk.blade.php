@extends('layouts.public')

@section('content')

<!--Section-->
<section id="login-page">
	<div class="container">
		<section id="Main_text"><h1 class="text-center">ITC</h1></section>
		<section id="login">
			<div class="row">
				<div class="col-md-6  col-sm-8 col-sm-offset-4 col-md-offset-3">
					<div class="panel panel-default">
						<div class="panel-heading"> <strong class="">Login</strong></div>
						
						<!--error msg-->
						@if($errors->has('email'))
						<div class="col-md-12 alert alert-danger alert-custom">
							<span class="help-block helpblock"><strong>{{ $errors->first('email') }}</strong></span>		
						</div>
						@endif
						<!--/error msg-->
						
						<!--panel-body-->
						<div class="panel-body">
							<!--Login form-->
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
								<!--Email-->
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label for="email" class="col-md-4 control-label">E-Mail</label>
									<div class="col-md-6">
										<input id="email" type="email" Placeholder="E-Mail" class="form-control" name="email" value="@if(!empty(Cookie::get('itcerp_username'))) {{Cookie::get('itcerp_username')}} @else {{old('email')}} @endif" autofocus>	
									</div>
								</div>
								<!--/Email-->
								
								<!--Password-->
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label for="password" class="col-md-4 control-label">Password</label>	
									<div class="col-md-6">
										<input id="password" type="password" Placeholder="Password" value="@if(!empty(Cookie::get('itcerp_password'))){{Cookie::get('itcerp_password')}}@endif" class="form-control" name="password">
										@if ($errors->has('password'))
											<span class="help-block">
												<strong>{{ $errors->first('password') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<!--/Password-->
							
								<!--Remember Me-->
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<div class="checkbox">
											<label>
											<input type="checkbox"  @if(!empty($_COOKIE['itcerp_username'])) checked @endif name="remember"> Remember Me</label>
										</div>
									</div>
								</div>
								<!--/Remember Me-->
								
								<!--Login Button-->
								<div class="form-group">
									<div class="col-md-8 col-md-offset-4">
										{{ csrf_field() }}
										<button type="submit" class="btn btn-primary">Login</button>
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

@endsection