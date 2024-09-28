@extends('layouts.app')

@section('content')

<div class="container">
	
    <!--Setting header-->
	<div class="row header">	
		<div role="new" class="navbar-form navbar-left">            
			<span class="pull-left"><strong id="form_title">Settings</strong></span>
		</div>  
    </div>
    <!--/Setting header-->
    
    <!--Setting Row-->
    <div class="row">
        <div class="panel panel-default ">
            
            <!--Item Master Setting-->
            <div class="panel-body">
                <div class="col-xs-11">Refresh All Branch Wise Items</div>
                <div class="col-xs-1"><a href="{{ url('branch-items/refresh') }}" class="btn btn-primary">{{ trans('Refresh') }} </a></div>
            </div>
            <!--/Item Master Setting-->
        </div>
    </div>
    <!--/Setting Row-->
    
</div>
    
@endsection