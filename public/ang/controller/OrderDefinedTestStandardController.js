app.controller('OrderDefinedTestStandardController', function($scope, $http, BASE_URL,$ngConfirm,$timeout) {
	
	//define empty variables
	$scope.masterDataList   = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.searchKeyword 	= '';
	$scope.addMasterModel   = {};
	$scope.editMasterModel  = {};
	$scope.defaultMsg  	= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     	= 'or_discipline_id';    	 // set the default sort type
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
	$scope.IsVisiableSuccessMsg 	= true;
	$scope.IsVisiableErrorMsg 	= true;
	$scope.IsVisiableSuccessMsgPopup= true;
	$scope.IsVisiableErrorMsgPopup 	= true;
	$scope.listMasterFormBladeDiv 	= false;
	$scope.addMasterFormBladeDiv 	= true;
	$scope.editMasterFormBladeDiv 	= true;
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
	$scope.funConfirmDeleteMessage = function(id){
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
						$scope.funDeleteMaster(id);
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
		$scope.addMasterModel = {};
		$scope.erpAddMasterForm.$setUntouched();
		$scope.erpAddMasterForm.$setPristine();
		$scope.editMasterModel = {};
		$scope.erpEditMasterForm.$setUntouched();
		$scope.erpEditMasterForm.$setPristine();
		$scope.listMasterFormBladeDiv 	= false;
		$scope.addMasterFormBladeDiv 	= true;
		$scope.editMasterFormBladeDiv 	= true;
	};
	//**********/Back Button********************************************
    
	//**********Reset Button*********************************************
	$scope.resetButton = function(){
		$scope.addMasterModel = {};
		$scope.erpAddMasterForm.$setUntouched();
		$scope.erpAddMasterForm.$setPristine();
	};
	//**********/Reset Button*********************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		if(APPLICATION_MODE)console.clear();
	};
	//*********/Clearing Console*********************************************
	
	//************code used for sorting list order by fields*****************
	$scope.predicate = 'credit_note_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************
	
	//*****************display division dropdown start************************
	$scope.divisionDropdownList = [];
	$scope.funGetDivisionList = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			$scope.divisionDropdownList = result;
			$scope.clearConsole();
		});
	};
	$scope.funGetDivisionList();
	//*****************display division dropdown end*******************************
	
	//*****************display parent category*********************************
	$scope.parentCategoryDropdownList = [];
	$scope.funGetParentCategoryList = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'master/get-parent-product-category'
		}).success(function (result) { 
			$scope.parentCategoryDropdownList = result.parentCategoryList;
			$scope.clearConsole();
		});
	};
	$scope.funGetParentCategoryList();
	//****************/display parent category*********************************
	
	//*****************display test standard list*********************************
	$scope.testStandardCheckboxList = [];
	$scope.funGetTestStandardList = function(product_category_id){
		$http({
			method: 'GET',
			url: BASE_URL +'master/get-test-standard-list/'+product_category_id
		}).success(function (result) { 
			$scope.testStandardCheckboxList = result.testStdList;
			$scope.clearConsole();
		});
	};
	//****************/display test standard list*********************************

	//*****************Listing of Master********************************
	$scope.funListMaster = function(){
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/defined-test-standard/list",
			method: "POST",
		}).success(function(result, status, headers, config){
			$scope.masterDataList = result.masterDataList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//*****************/Listing of Master********************************

	//****************Array To String******************************
	$scope.funArrayToString = function(data,name){
		$scope.testStandard = _.map(data, name).join(', ');
	};	
	$scope.navigatePage = function(){ 
		if(!$scope.addMasterFormBladeDiv){
			$scope.addMasterFormBladeDiv = true;
			$scope.editMasterFormBladeDiv = true;
			$scope.listMasterFormBladeDiv = false;  
			     
		}else if(!$scope.editMasterFormBladeDiv){
			$scope.addMasterFormBladeDiv  = true;
			$scope.editMasterFormBladeDiv = false;
			$scope.listMasterFormBladeDiv = true;
			
		}else{
			$scope.listMasterFormBladeDiv = true;			
			$scope.editMasterFormBladeDiv = true;
			$scope.addMasterFormBladeDiv  = false;
			$scope.funGetDivisionList();
			$scope.funGetParentCategoryList();
		}
	};
	//*****************Adding of Master********************************
	$scope.funAddMaster = function(){		
		if(!$scope.erpAddMasterForm.$valid)return;		
		if($scope.newErpAddMasterFormflag)return;		
		$scope.newErpAddMasterFormflag = true;		
		var formData = $(erpAddMasterForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/defined-test-standard/add",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.newErpAddMasterFormflag = false;
			if(data.error == 1){               
				$scope.resetButton();
				$scope.funListMaster();
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newErpAddMasterFormflag = false;
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//*****************/Adding of Master********************************
	
	//****************Editing of Master*************************************
	$scope.funEditMaster = function(id){
		if(id){
			$scope.loaderShow();
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "master/defined-test-standard/view/"+id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
					$scope.addMasterFormBladeDiv = true;
					$scope.listMasterFormBladeDiv 	= true;
					$scope.editMasterFormBladeDiv = false;
					$scope.editMasterModel = result.editMasterData;
					$scope.funGetTestStandardList(result.editMasterData.odtsd_product_category_id);
					$timeout(function (){
						$scope.divisionDropdownList = [{id: result.editMasterData.odtsd_branch_id, name: result.editMasterData.odtsd_division_name}];
						$scope.parentCategoryDropdownList   = [{id: result.editMasterData.odtsd_product_category_id, name: result.editMasterData.odtsd_product_category_name}];
						$scope.editMasterModel.odtsd_branch_id 	= { selectedOption: { id : result.editMasterData.odtsd_branch_id} };
						$scope.editMasterModel.odtsd_product_category_id = { selectedOption: { id : result.editMasterData.odtsd_product_category_id} };
					}, 100);
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
	//****************/Editing of Master*************************************
	
	//****************Updating of Master*************************************
	$scope.funUpdateMaster = function(){
		if(!$scope.erpEditMasterForm.$valid)return;
		if($scope.newErpEditMasterFormflag)return;		
		$scope.newErpEditMasterFormflag = true;
		var formData = $(erpEditMasterForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/defined-test-standard/update",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) {
			$scope.newErpEditMasterFormflag = false;
			if(result.error == 1){
				$scope.funListMaster();
				$scope.successMsgShow(result.message);					
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			$scope.newErpEditMasterFormflag = false;
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		}); 
	};
	//****************/Updating of Master*************************************
	
	//****************Deleting of Master*************************************
	
	$scope.funDeleteMaster = function(id){	
					
		if(id){
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/defined-test-standard/delete/"+id,
			}).success(function(result, status, headers, config){				
				if(result.error == 1){
					$scope.funListMaster();
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
	//****************/Deleting of Master*************************************
	
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