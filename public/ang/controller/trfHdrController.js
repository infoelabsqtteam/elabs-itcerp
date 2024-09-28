app.controller('trfHdrController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {
	
	$scope.trfData 			= '';
	$scope.trf_id 			= '';
	$scope.trf_no 			= '';
	$scope.trf_date 		= '';
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup 	= '';
	$scope.viewOrder            	= {};
	$scope.defaultMsg  	   	= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     		= 'order_date';     	//Set the default sort type
	$scope.sortReverse  		= false;         	//Set the default sort order
	$scope.searchFish   		= '';    		//Set the default search/filter term

	//**********scroll to top function**********
	$scope.moveToMsg=function(){
		angular.element('html, body').animate({scrollTop: $(".alert").offset().top},500);
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
	
	//********** key Press Handler**********************************************
	$scope.funEnterPressHandler = function(e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			e.stopPropagation();
		}
	}
	//**********/key Press Handler**********************************************

	//**********Read/hide More description************************************
	$scope.toggleDescription = function(type,id) {
		angular.element('#'+type+'limitedText-'+id).toggle();
		angular.element('#'+type+'fullText-'+id).toggle();
	};
	//*********/Read More description*****************************************

	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsViewList 			= false;
	$scope.IsViewDetailList		 	= true;
	$scope.IsVisiableSuccessMsg 	 	= true;
	$scope.IsVisiableErrorMsg 		= true;
	$scope.IsVisiableSuccessMsgPopup    	= true;
	$scope.IsVisiableErrorMsgPopup 	    	= true;
	$scope.openNewTrfForm 		 	 = function(){
		$scope.IsViewList 	    	 = false;
		$scope.IsViewDetailList 	 = true;
		$scope.hideAlertMsg();
	};
	//**********/If DIV is hidden it will be visible and vice versa************

	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage = message;
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg = true;
		$scope.moveToMsg();
	};
	//********** /successMsgShow************************************************

	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 	= message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = false;
		$scope.moveToMsg();
	};
	//********** /errorMsgShow************************************************

	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = true;
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

	//**********back Button****************************************************
	$scope.backButton = function(type){
		if (type == '1') {
			$scope.IsViewList 	= true;
			$scope.IsViewDetailList = false;
		}else{
			$scope.IsViewList 	= false;
			$scope.IsViewDetailList = true;
		}
	};
	//**********/back Button***************************************************

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
	$scope.predicate = 'order_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/Sorting list order*****************************************

	//**********Open need modification popup******************************
	$scope.funOpenBootStrapModalPopup=function(contentId){
		$scope.successMsgOnPopup = '';
		$scope.errorMsgOnPopup   = '';
		$('#'+contentId).modal({backdrop: 'static',keyboard: true,show: true});
	}
	//**********/Open need modification popup******************************
	
	//**********Close need modification popup******************************
	$scope.funCloseBootStrapModalPopup=function(contentId){
		$('#'+contentId).modal('hide');
	}
	//**********/Close need modification popup******************************

	//*****************display division dropdown start*************************
	$scope.divisionsCodeList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			$scope.divisionsCodeList = result;
			$scope.clearConsole();
	});
	//*****************display division dropdown end**************************

	//*****************display Test Product Category dropdown******************
	$scope.testProductCategoryList = [];
	$scope.funGetTestProductCategory = function(){
		$http({
				method: 'GET',
				url: BASE_URL +'orders/get_test_product_category/'+2
			}).success(function (result) {
			$scope.testProductCategoryList = result.productCatsList;
		});
	}
	//*****************/display Test Product Category dropdown*****************

	//**********Getting all Orders with search and filter******************************
	$scope.funGetTrfsHttpRequest = function(){

		$scope.loaderShow();
		var http_para_string = {formData : $(erpFilterTrfForm).serialize()};

		$http({
			url: BASE_URL + "sales/trfs/get-trf-list",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			$scope.trfData = result.trfDataList;
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function(data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	$scope.funGetTrfsList = function(divisionId){
		$scope.divisionID     = divisionId;
		$scope.searchStringID = $scope.searchStringID;
		$scope.funGetTrfsHttpRequest();
	};
	$scope.funFilterTrfByStatus = function(){
		$scope.funGetTrfsHttpRequest();
	};

	var tempOrderSearchTerm;
	$scope.funFilterTRfSearchBox = function(keyword){
		tempOrderSearchTerm = keyword;
		$timeout(function () {
			if (keyword == tempOrderSearchTerm) {
				$scope.searchStringID = keyword;
				$scope.funGetTrfsHttpRequest();
			}
		}, 800);
	};
	$scope.funRefreshTrfsList = function(){
		$scope.divisionID     = '';
		$scope.searchStringID = '';
		$scope.filterTrfs   = {};
		$scope.erpFilterTrfForm.$setUntouched();
		$scope.erpFilterTrfForm.$setPristine();
		$timeout(function () {
			$scope.funGetTrfsHttpRequest();
		}, 500);
	};
	//**********/Getting all Orders with search and filter**********************

	///**************multisearch start here*************************************
	$scope.multiSearchTr  = true;
	$scope.multisearchBtn = false;
	var tempSearchKeyword;
	$scope.getMultiSearch=function(searchKeyword){
		tempSearchKeyword = searchKeyword;
		$timeout(function () {
			if(tempSearchKeyword == searchKeyword){
				$scope.searchOrder = searchKeyword;
				$scope.funGetTrfsHttpRequest();
			}
		},1000);
	};
	$scope.closeMultisearch = function(){
		$scope.multiSearchTr	= true;
		$scope.multisearchBtn	= false;
		$scope.funRefreshTrfsList();
	};
	$scope.refreshMultisearch = function(divisionID){
		$scope.funRefreshTrfsList();
	};
	$scope.openMultisearch = function(){
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	};
	//**************multisearch end here******************************************
	
	//****************Viewing of TRF Detail******************************************
	$scope.funViewTrfDetail = function(trfId){

		$scope.loaderShow();
		var http_para_string = {trf_id : trfId};
		$scope.backButton(1);

		$http({
			url: BASE_URL + "sales/trfs/view-trf-detail-list",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			$scope.trfHdrList = result.response.trfHdr;
			$scope.trfHdrDtlList = result.response.trfHdrDtl;
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function(data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//****************/Viewing of TRF Detail******************************************
});