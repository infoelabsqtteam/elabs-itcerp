<!--Account details-->
<div class="row" ng-hide="addFormBladeDiv">
    <div class="panel panel-default">
        <div class="panel-body">           
            
			<!--header-->
			<div class="row header-form">
                <div role="new" class="navbar-form navbar-left">            
                    <span class="pull-left"><strong id="form_title">Change Password</strong></span>
                </div>            
                <div role="new" class="navbar-form navbar-right"></div>
            </div>
			<!--/header-->
			
			<!--form-->
			<div class="">
				<form name="passwordForm" id="add_password_form" novalidate>
								
					<div class="row">
						
						<!--Old Password-->
						<div class="col-xs-6">
							<label for="password_code">Old Password<em class="asteriskRed">*</em></label>						   
							<input type="text" class="form-control" 
								ng-model="password.old_password"
								name="old_password" 
								id="old_password"
								ng-required='true'
								placeholder="Old Password" />
							<span ng-messages="passwordForm.old_password.$error" ng-if='passwordForm.old_password.$dirty  || passwordForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please enter old password!</span>
							</span>
						</div>
						<!--/Old Password-->
						
						<!--New Password-->
						<div class="col-xs-6 form-group">
							<label>New Password<em class="asteriskRed">*</em></label>
							<input type="password"
								name="password"
								id="user-password"
								class="form-control"
								ng-model="password.password"
								placeholder="New Password"
								ng-required='true' />
							<span ng-messages="passwordForm.password.$error" ng-if='passwordForm.password.$dirty  || passwordForm.$submitted' role="alert">
								<span ng-message="required" class="error">Please enter new password!</span>
							</span>
						</div>
						<!--/New Password-->
						
						<!--Confirm Password-->
						<div class="col-xs-6 form-group">                        
							<label>Confirm Password<em class="asteriskRed">*</em></label>
							<input type="password"
								class="form-control"
								name="password_confirmation"
								id="user-cpassword"
								ng-model="password.password_confirmation"
								confirm-pwd="password.password"
								ng-required='true'
								placeholder="Confirm Password"/>
							<span ng-messages="passwordForm.password_confirmation.$error" ng-if='passwordForm.password_confirmation.$dirty  || passwordForm.$submitted' role="alert">
							<span class="error" ng-message="required">Please enter the confirm password!</span>
								<span class="error" ng-message="password">New Password and Confirm Password are different!</span>
							</span>
						</div>
						<!--/Confirm Password-->
						
						<!--Update Button-->
						<div class="col-xs-6">
							<div class="">
								<label for="submit">{{ csrf_field() }}</label>		
								<button title="Save" type="submit" class="mT26 btn btn-primary" ng-disabled="passwordForm.$invalid" ng-click="updatePassword()"> Save </button>
								<button title="Reset" type="reset" class="mT26 btn btn-default"> Reset </button>
							</div>
						</div>
						<!--/Update Button-->
						
					</div>
				</form>
			</div>			
		</div>
	</div>
</div>