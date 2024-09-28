app.controller('themeController', function($scope, $http, BASE_URL, $window){
		// sorting variables
	$scope.sortType     = 'department_code'; 			 // set the default sort type
	$scope.sortReverse  = false; 						 // set the default sort order
	
	//define empty variables
	$scope.deptdata = '';
	$scope.dept_name ='';
	$scope.dept_code='';
	$scope.dept_id='';
	
	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup 	= '';	
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
	//**********If DIV is hidden it will be visible and vice versa*******
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 		 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	//**********/If DIV is hidden it will be visible and vice versa******
	
	//**********successMsgShow*******************************************
	$scope.uploadMsgShow = function(uploadMsg){
		$scope.uplodedMessageHide    = false;
		$scope.notUplodedMessageHide = false;	
		$scope.notUplodedMessageHide = false;
		$scope.uplodedMessage    = uploadMsg.uploaded;
		$scope.notUplodedMessage = uploadMsg.notUploaded;	
		$scope.notUplodedMessage = uploadMsg.duplicate;	
	}
	//********** /successMsgShow******************************************
	
	//**********hide upload Alert Msg*************
	$scope.hideUploadAlertMsg = function(){
		$scope.uplodedMessageHide    = true;
		$scope.notUplodedMessageHide = true;	
		$scope.notUplodedMessageHide = true;	
	}
	//********** /hide upload Alert Msg************************************	
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
	}
	//**********/scroll to top function**********
	 
    //**********loader show****************************************************
	$scope.loaderShow = function(){
        angular.element('#global_loader').fadeIn('slow');
	}
	//**********/loader show**************************************************
    
    //**********loader show***************************************************
	$scope.loaderHide = function(){
        angular.element('#global_loader').fadeOut('slow');
	}
	//**********/loader show**************************************************
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		//console.clear();
	}
	//*********/Clearing Console********************************************
	
	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 		= message;				
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 		= message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= false;
		$scope.moveToMsg();
	}
	//********** /errorMsgShow************************************************
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//********** /hide Alert Msg**********************************************
	
	//**********successMsgShow**************************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		= message;				
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		console.log($scope.errorMessagePopup);
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	}
	//********** /errorMsgShow************************************************
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsgPopup = function(){
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
	}
	//********** /hide Alert Msg**********************************************
	
	//code used for sorting list order by fields 
	$scope.predicate = 'btn_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	// function is used to fetch the list of departments 	
	$scope.getAllParameters = function()
    { 
		$http.post(BASE_URL + "theme/get-btn-parameters", {
			
        }).success(function (data, status, headers, config) { 
			$scope.btnsParameterList = data.btnsParameterList;
			$scope.clearConsole(); 
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole(); 
        });
    };
	
});