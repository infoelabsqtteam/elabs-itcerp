app.controller('accountSettingController', function($scope, $http, BASE_URL) {

	$scope.IsVisiableSuccessMsg 	= true;
	$scope.IsVisiableErrorMsg   	= true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.userRoleList 		= [];
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	};
	//**********/scroll to top function**********
		
	//**********loader show****************************************************
	$scope.loaderShow = function(){
		angular.element('#global_loader').fadeIn('slow');
	};
	//**********/loader show**************************************************
    
	//**********loader show***************************************************
	$scope.loaderHide = function(){
		angular.element('#global_loader').fadeOut('slow');
	};
	//**********/loader show**************************************************
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		//console.clear();
	};
	//*********/Clearing Console********************************************
	
	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 		= message;				
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg 	= true;
		$scope.moveToMsg();
	};
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 		= message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= false;
		$scope.moveToMsg();
	};
	//********** /errorMsgShow************************************************
	
	//*********hideAlertMsg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	};
	//**********/hideAlertMsg********************************************
	
	//*****************roles list****************************************************
	$scope.getAllRolesList=function(){
		$http({
				method: 'POST',
				url: BASE_URL +'roles/get-users-roles'
			}).success(function (result){
				if(result.userRoleList){			  
					$scope.userRoleList = result.userRoleList;  
				}
				$scope.clearConsole();
		});
	};
	$scope.getAllRolesList();
	//****************roles list*****************************************************
	
	//*****************display user details on page load*****************
	$scope.funGetAccountDetails = function(){		
		$scope.loaderShow(); 
		$http({
			method: 'POST',
			url: BASE_URL +'profile/get-account-details'
		}).success(function (result){
			$scope.editEmployeeAccount 		= result.userDetail.userData;
			$scope.editEmployeeAccount.role_id 	= {id : result.userDetail.userData.role_id};
			$scope.departmentList      		= result.userDetail.departments;
			$scope.roleDataList    	   		= result.userDetail.roles;
			$scope.equipmentTypesList  		= result.userDetail.equipmentTypes;
			$scope.loaderHide(); 
			$scope.clearConsole();
		});
	};
	//******************display user details on page load*******************

	//*****************update password*************************************
	$scope.updatePassword = function(){
	    if(!$scope.passwordForm.$valid)return; 	
		$scope.loaderShow(); 
			$http.post(BASE_URL + "profile/update_password", {
			data: {formData:$(passwordForm).serialize() },
		}).success(function (data, status, headers, config) {
			if(data.success){ 
				$scope.password = {};	
				$scope.passwordForm.$setUntouched();
				$scope.passwordForm.$setPristine();	
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShow(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};		
	//*****************update password**********************************************
	
	//**************** Updating of Account Detail**********************************
	$scope.funUpdateEmployeeAccount = function(divisionId){		
		
		if(!$scope.erpEditEmployeeAccountForm.$valid)return;		
		
		var formData = $(erpEditEmployeeAccountForm).serialize();
		$scope.loaderShow();
		
		$http({
			url: BASE_URL + "profile/update-account",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) { 
			if(result.error == 1){
				window.location.reload();
				$scope.funGetAccountDetails();
				$scope.successMsgShow(result.message);					
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		}); 
	};
	//****************/Updating of Account Detail**********************************

}).directive('confirmPwd', function($interpolate, $parse) {
  return {
    require: 'ngModel',
    link: function(scope, elem, attr, ngModelCtrl) {
      var pwdToMatch = $parse(attr.confirmPwd);
      var pwdFn = $interpolate(attr.confirmPwd)(scope);

      scope.$watch(pwdFn, function(newVal) {
          ngModelCtrl.$setValidity('password', ngModelCtrl.$viewValue == newVal);
      });
      ngModelCtrl.$validators.password = function(modelValue, viewValue) {
        var value = modelValue || viewValue;
        return value == pwdToMatch(scope);
      };

    }
  };
});
