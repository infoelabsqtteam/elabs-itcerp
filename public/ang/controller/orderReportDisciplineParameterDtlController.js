app.controller('orderReportDisciplineParameterDtlController', function($scope, $http, BASE_URL,$ngConfirm,$timeout) {
	
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
	$scope.addMasterFormBladeDiv 	= false;
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
						$scope.funDeleteMaster(id,divisionId);
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
		$scope.addMasterFormBladeDiv 	= false;
		$scope.editMasterFormBladeDiv 	= true;
		$scope.addMasterModel.org_group_status = {id : 1};
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
	$scope.predicate = 'ordp_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************
	
	//****************Array To String******************************
	$scope.funArrayToString = function(data,name){
		$scope.employeeTitle = _.map(data, name).join(', ');
	};
	//***************/Array To String******************************
	
	//********************Pre populated Select Box****************************
	$scope.activeInactionSelectboxList = [
		{id: '1', name: 'Active'},
		{id: '2', name: 'Inactive'}	
	];
	$scope.addMasterModel.org_group_status = {id : 1};
	//*******************/Pre populated Select Box************************
	
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
	
	//*****************Display Discipline category*********************************
	$scope.disciplineDropdownList = [];
	$scope.funGetDisciplineDropdownList = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'master/discipline-parameter-category/get-discipline-list'
		}).success(function (result) { 
			$scope.disciplineDropdownList = result.disciplineList;
			$scope.clearConsole();
		});
	};
	$scope.funGetDisciplineDropdownList();
	//****************/Display Discipline category*********************************
	
	//*****************parameter category tree list data*****************
	$scope.parameterCatDropdownList = [];
	$scope.funGetParameterCatDropdownList = function(product_category_id){	
		$http({
			method: 'GET',
			url: BASE_URL +'master/discipline-parameter-category/get-parameter-category-list/'+product_category_id,
			data:{product_category_id : product_category_id}
		}).success(function (result) {
			$scope.parameterCatDropdownList = result.testParameterList;
			$scope.clearConsole();
		});
	};
	$scope.funGetParameterCatDropdownList(1);
	//*****************/parameter category tree list data*****************
	
	//*****************Listing of Master********************************
	$scope.funListMaster = function(){
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/discipline-parameter-category/list",
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
	
	//*****************Adding of Master********************************
	$scope.funAddMaster = function(){
		
		if(!$scope.erpAddMasterForm.$valid)return;		
		if($scope.newErpAddMasterFormflag)return;		
		$scope.newErpAddMasterFormflag = true;		
		var formData = $(erpAddMasterForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/discipline-parameter-category/add",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.newErpAddMasterFormflag = false;
			if(data.error == 1){
				angular.element('.refreshCheckBox').prop('checked',false);
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
				url: BASE_URL + "master/discipline-parameter-category/view/"+id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
					$scope.addMasterFormBladeDiv = true;
					$scope.editMasterFormBladeDiv = false;
					$scope.editMasterModel = result.editMasterData;
					$scope.funGetParameterCatDropdownList(result.editMasterData.ordp_product_category_id);
					$scope.editMasterModel.ordp_division_id = {id: result.editMasterData.ordp_division_id};
					$scope.editMasterModel.ordp_product_category_id = {id: result.editMasterData.ordp_product_category_id};
					$scope.editMasterModel.ordp_discipline_id = {id: result.editMasterData.ordp_discipline_id};
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
			url: BASE_URL + "master/discipline-parameter-category/update",
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
	$scope.funConfirmDeleteMessage = function(id){
		$ngConfirm({
			title     : false,
			content   : defaultDeleteMsg,
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
	$scope.funDeleteMaster = function(id){				
		if(id){
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/discipline-parameter-category/delete/"+id,
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
});