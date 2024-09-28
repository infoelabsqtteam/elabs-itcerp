app.controller('dashboardController', function($scope,$timeout,$http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	//sorting variables
	$scope.sortType     		= 'ortd_id';     // set the default sort type
	$scope.sortReverse  		= false;         // set the default sort order
	$scope.searchFish   		= '';    	// set the default search/filter term
	
	//set the default search/filter term
	$scope.IsVisiableSuccessMsg	= true;
	$scope.IsVisiableErrorMsg	= true;
	$scope.addFormDiv     		= false; 	// 14-05-2018
	$scope.editFormDiv    		= false;	

	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//**********scroll to top function**********addForm
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
	}
	//**********/loader show**************************************************
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		if(APPLICATION_MODE)console.clear();
	}
	//*********/Clearing Console********************************************
	
	//**********Read/hide More description********************************************
	$scope.toggleDescription = function(type,id) {
		angular.element('#'+type+'LimitedText-'+id).toggle();
		angular.element('#'+type+'FullText-'+id).toggle();
	};
	//*********/Read More description********************************************	
	
	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 		= message;				
		$scope.IsVisiableSuccessMsg 	= false;
		$scope.IsVisiableErrorMsg 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 		= message;
		$scope.IsVisiableSuccessMsg 	= true;
		$scope.IsVisiableErrorMsg 	= false;
		$scope.moveToMsg();
	}
	//********** /errorMsgShow************************************************
	
	//**********successMsgShowPopup**************************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		= message;
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	= true;
		$scope.moveToMsg();
	};
	//********** /successMsgShowPopup************************************************

	//**********errorMsgShowPopup**************************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	};
	//********** /hideAlertMsgPopup************************************************

	//**********hideAlertMsgPopup*************
	$scope.hideAlertMsgPopup = function(){
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
	};
	//********** /hideAlertMsgPopup**********************************************
	
	//*********hideAlertMsg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg 	= true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//**********/hideAlertMsg********************************************
	
	//*********reset Form************************************************
	$scope.resetForm = function(){
		$scope.addTemplate = {}; 
		$scope.editTemplate = {}; 
	};
	//********/reset Form************************************************
	
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id){
		$ngConfirm({
			title     : false,
			content   : defaultDeleteMsg, //Defined in message.js and included in head
			animation : 'right',
			closeIcon : true,
			closeIconClass    : 'fa fa-close',
			backgroundDismiss : false,
			theme   : 'bootstrap',
			columnClass : 'col-sm-5 col-md-offset-3',
			buttons : {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'					
				}
			}
		});
	};
	//**********/confirm box****************************************************
	
	//code used for sorting list order by fields 
	$scope.predicate = 'detector_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//*******************Initialization of Script***********************************
	$scope.initializeNotificationScript = function(){

		// ANIMATEDLY DISPLAY THE NOTIFICATION COUNTER.
		angular.element('#notification_button')
		    .css({ 'background-color': 'red' })
		    .animate({ 'background-color': 'yellow', opacity: 1 },1000);
		
		angular.element('#notification_button').click(function () {		
			// TOGGLE (SHOW OR HIDE) NOTIFICATION WINDOW.
			angular.element('#notifications').fadeToggle('fast', 'linear', function () {
				if (angular.element('#notifications').is(':hidden')) {
					angular.element('#notification_button').css('background-color', '#FFF');
				}else{
					angular.element('#notification_button').css('background-color', '#FFF');
				}
			});		
			angular.element('#notification_counter').fadeOut('slow');     // HIDE THE COUNTER		
			return false;
		});		
		// HIDE NOTIFICATIONS WHEN CLICKED ANYWHERE ON THE PAGE.
		angular.element(document).click(function () {
			angular.element('#notifications').hide();		
			if (angular.element('#notification_counter').is(':hidden')) {
				angular.element('#notification_button').css('background-color', '#FFF');
			}
		});		
		angular.element('#notifications').click(function () {
			return false;       // DO NOTHING WHEN CONTAINER IS CLICKED.
		});		
	};
	//*******************/Initialization of Script***********************************
	
	//*******************Initialization of User Role COntent*************************
	$scope.initializeUserRoleContent = function(){
		
		$http({
			url: BASE_URL + "dashboard/get-content-acto-roles",
			method: "GET",
		}).success(function(result, status, headers, config){
			if(result.error == 1){
				$scope.userRoleContentData = result.returnData;
				angular.element("#notification_container_ul").fadeIn('slow');
			}
			$scope.clearConsole();
		}).error(function(data, status, headers, config){
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};
	//******************/Initialization of User Role COntent*************************
	
	//*******************Check Function Efficiency*************************
	$scope.CheckFunctionEfficiency = function(){
		$http({
			url: BASE_URL + "check-function-eff-before",
			method: "POST",
		}).success(function(result, status, headers, config){
		}).error(function(data, status, headers, config){
		});
		$http({
			url: BASE_URL + "check-function-eff-after",
			method: "POST",
		}).success(function(result, status, headers, config){
		}).error(function(data, status, headers, config){
		});
	};
	//******************/Check Function Efficiency*************************

});
