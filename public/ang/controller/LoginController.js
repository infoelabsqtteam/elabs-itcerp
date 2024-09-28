app.controller('LoginController', function($scope,$http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.successMessage 	   	= '';
	$scope.errorMessage   	   	= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup   	= '';
	$scope.defaultMsg  	   	= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType    		= 'id';   // set the default sort type
	$scope.sortReverse 		= false;         // set the default sort order
	$scope.searchFish  		= '';    	// set the default search/filter term
	
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
	
	//**********loader show****************************************************
	$scope.loaderMainShow = function(){
		angular.element('#global_loader_onload').fadeIn('slow');
	};
	//**********/loader show**************************************************
    
	//**********loader show***************************************************
	$scope.loaderMainHide = function(){
		angular.element('#global_loader_onload').fadeOut('slow');
	};
	//**********/loader show**************************************************
	
	//**********Read/hide More description************************************
	$scope.toggleContent = function(divId) {
		angular.element('#'+divId).toggle();
	};
	//*********/Read More description*****************************************
	
	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.closeButton = function(){
		$scope.hideAlertMsg();
	};
	//**********/If DIV is hidden it will be visible and vice versa************
	
	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 	    = message;				
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg   = true;
		$scope.moveToMsg();
	};
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 	     = message;
		$scope.IsVisiableSuccessMsg  = true;
		$scope.IsVisiableErrorMsg    = false;
		$scope.moveToMsg();
	};
	//********** /errorMsgShow************************************************
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	};
	//********** /hide Alert Msg**********************************************
	
	//**********successMsgShowPopup**************************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup       = message;				
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	 = true;
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
	
	//**********back Button****************************************************
	$scope.backButton = function(){			
		$scope.resetForm();
	};
	//**********/back Button***************************************************
	
	//**********reset Form****************************************************
	$scope.resetForm = function(){
		$scope.generateMISReport = {};	
		$scope.MISReportData = [];
	};
	
	//**********confirm box******************************************************
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	};
	//**********/confirm box****************************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		if(APPLICATION_MODE)console.clear();
	};
	//*********/Clearing Console*********************************************
	
	//************Sorting list order******************************************
	$scope.predicate = 'order_date';
	$scope.reverse   = false;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/Sorting list order*****************************************
	
});