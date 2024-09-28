app.controller('customerComCrmEmailAddressController', function($scope, $http, BASE_URL,$ngConfirm,$timeout) {
	
	//define empty variables
	$scope.prodata 	        = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.searchKeyword 	= '';
	$scope.divisions 		= [];
	$scope.defaultMsg  		= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.downloadType		= '1';

	//sorting variables
	$scope.sortType     	= 'cccea_name';    	 // set the default sort type
	$scope.sortReverse  	= false;         	 // set the default sort order
	$scope.searchFish   	= '';    		 // set the default search/filter term
		
	//**********scroll to top function******************************************
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	};
	//**********/scroll to top function******************************************
	
	//**********loader show****************************************************
	$scope.loaderShow = function(){
		angular.element('#global_loader').fadeIn();
	};
	//**********/loader show**************************************************
    
	//**********loader show***************************************************
	$scope.loaderHide = function(){
		angular.element('#global_loader').fadeOut();
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
	
	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg 		= true;
	$scope.IsVisiableErrorMsg 			= true;
	$scope.IsVisiableSuccessMsgPopup 	= true;
	$scope.IsVisiableErrorMsgPopup 	 	= true;
	$scope.listCustomerComCrmFormBladeDiv 	= false;
	$scope.addCustomerComCrmFormBladeDiv 	= false;
	$scope.editCustomerComCrmFormBladeDiv 	= true;
	//**********/If DIV is hidden it will be visible and vice versa************
	
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
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	};
	//********** /hide Alert Msg**********************************************
	
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
	
	//**********confirm box******************************************************
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	};
	//********** /confirm box****************************************************
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id,divisionId){
		$ngConfirm({
			title     : false,
			content   : defaultDeleteMsg, //Defined in message.js and included in head
			animation : 'right',
			closeIcon : true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme   : 'bootstrap',
			buttons   : {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funDeleteCustomerComCrm(id,divisionId);
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'					
				}
			}
		});
	};
	//********** /confirm box****************************************************
	
	//**********Back Button*********************************************
	$scope.backButton = function(){
		$scope.addBWCustomerComCrm = {};
		$scope.erpAddBWCustomerComCrmForm.$setUntouched();
		$scope.erpAddBWCustomerComCrmForm.$setPristine();
		$scope.editBWCustomerComCrm = {};
		$scope.erpEditBWCustomerComCrmForm.$setUntouched();
		$scope.erpEditBWCustomerComCrmForm.$setPristine();
		$scope.listCustomerComCrmFormBladeDiv 	= false;
		$scope.addCustomerComCrmFormBladeDiv 	= false;
		$scope.editCustomerComCrmFormBladeDiv 	= true;
	};
	//**********/Back Button********************************************

	//**********Navigate Button*********************************************
	$scope.navigateCustomerComCrmPage = function(){
		$scope.listCustomerComCrmFormBladeDiv 	= false;
		$scope.addCustomerComCrmFormBladeDiv 	= false;
		$scope.editCustomerComCrmFormBladeDiv 	= true;
	};
	//**********/Navigate Button********************************************
    
	//**********Reset Button*********************************************
	$scope.resetButton = function(){
		$scope.addBWCustomerComCrm = {};
		$scope.erpAddBWCustomerComCrmForm.$setUntouched();
		$scope.erpAddBWCustomerComCrmForm.$setPristine();
		$scope.editBWCustomerComCrm = {};
		$scope.erpEditBWCustomerComCrmForm.$setUntouched();
		$scope.erpEditBWCustomerComCrmForm.$setPristine();
	};
	//**********/Reset Button*********************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		if(APPLICATION_MODE)console.clear();
	};
	//*********/Clearing Console*********************************************

	//********************Active/Inactive Dropdown****************************
	$scope.activeInactiveDropdownList = [
		{ id: '1', name: 'Active' },
		{ id: '2', name: 'Inactive' },
	];
	//*******************/Active/Inactive Dropdown****************************
	
	//************code used for sorting list order by fields*****************
	$scope.predicate = 'cccea_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields****************
	
	//*****************display division dropdown start************************
	$scope.branchDropDownOptions = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.branchDropDownOptions = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end*******************************
	
	//*****************display parent category*********************************
	$scope.parentCategoryList = [];	
	$http({
		method: 'POST',
		url: BASE_URL +'master/get-parent-product-category'
	}).success(function (result) { 
		$scope.parentCategoryList = result.parentCategoryList;
		$scope.clearConsole();
	});	
	//****************/display parent category*********************************

	//********** key Press Handler**********************************************
	$scope.funEnterPressHandler = function(e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			e.stopPropagation();
		}
	};
	//********** key Press Handler**********************************************
	
	//***************Getting all filter debit Note according to search value************
	$scope.funGetBranchWiseCustomerComCrms = function(){
		console.log();
		$scope.loaderShow();	
		var http_para_string = {};
		$http({
			url: BASE_URL + "master/com-crms/list",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			$scope.customerComCrmsList = result.customerComCrmsList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**********/Getting all CRM Data***************************************************
	
	//***************** Adding of Branch Wise CustomerComCrm ********************************
	$scope.funAddBranchWiseCustomerComCrm = function(){
		
		if(!$scope.erpAddBWCustomerComCrmForm.$valid)return;		
		if($scope.newAddBranchWiseCustomerComCrmFormflag)return;		
		$scope.newAddBranchWiseCustomerComCrmFormflag = true;		
		var formData = $(erpAddBWCustomerComCrmForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/com-crms/add",
			method: "POST",
			data: {formData :formData}
		}).success(function(result, status, headers, config) {
			$scope.newAddBranchWiseCustomerComCrmFormflag = false;
			if(result.error == 1){
				$scope.backButton();                
				$scope.funGetBranchWiseCustomerComCrms();
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config) {
			$scope.newAddBranchWiseCustomerComCrmFormflag = false;
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Branch Wise CustomerComCrm ***************************
	
	//**************** editing of CRM Data *************************************
	$scope.funEditCustomerComCrm = function(id){
		if(id){
			$scope.loaderShow();
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "master/com-crms/edit/"+id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
					$scope.listCustomerComCrmFormBladeDiv 	= false;
					$scope.addCustomerComCrmFormBladeDiv 	= true;
					$scope.editCustomerComCrmFormBladeDiv 	= false;
					$scope.editBWCustomerComCrm    			= result.editCustomerComCrmData;
					$scope.editBWCustomerComCrm.cccea_division_id  = {id: result.editCustomerComCrmData.cccea_division_id};
					$scope.editBWCustomerComCrm.cccea_product_category_id  = {id: result.editCustomerComCrmData.cccea_product_category_id};
					$scope.editBWCustomerComCrm.cccea_status  = {id: result.editCustomerComCrmData.cccea_status};
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config){
				$scope.loaderHide();
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************/editing of CRM Data *************************************
	
	//**************** Updating of CRM Data *************************************
	$scope.funUpdateBranchWiseCustomerComCrm = function(){		
		if(!$scope.erpEditBWCustomerComCrmForm.$valid)return;		
		var formData = $(erpEditBWCustomerComCrmForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/com-crms/update",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) { 
			if(result.error == 1){
                $scope.backButton();
				$scope.funGetBranchWiseCustomerComCrms();
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
	//*************** /Updating of CRM Data *************************************
		
	//**************** Deleting of CRM Data ***************************
	$scope.funDeleteCustomerComCrm = function(id){				
		if(id){
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/com-crms/delete/"+id,
			}).success(function(result, status, headers, config){				
				if(result.error == 1){
					$scope.funGetBranchWiseCustomerComCrms();
					$scope.successMsgShow(result.message);					
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config){
				$scope.loaderHide();
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});	
		}
	};
	//************** /Deleting of CRM Data *******************************

}).directive('datepicker', function() {
  return {
    require: 'ngModel',
    link: function(scope, el, attr, ngModel) {
      $(el).datepicker({
        onSelect: function(dateText) {
          scope.$apply(function() {
            ngModel.$setViewValue(dateText);
          });
        }
      });
    }
  };
});