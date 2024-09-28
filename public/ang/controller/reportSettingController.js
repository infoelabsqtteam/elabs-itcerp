app.controller('reportSettingController', function($scope, $timeout, $http, BASE_URL, $window ,$ngConfirm){
	
	// sorting variables
	$scope.sortType           		 = 'cgc_id'; 		//set the default sort type
	$scope.sortReverse  	  		 = false; 		//set the default sort order
	$scope.successMessage 	  		 = '';
	$scope.errorMessage   	  		 = '';
	$scope.branchWiseSelectedColumnList 	 = [];
	$scope.allSelectedOrderMasterColumnArray = [];
	$scope.customerDefinedTestReportField 	 = {};
	$scope.divisionID 			 = '1';
	$scope.productCategoryID 		 = '1';
	$scope.defaultMsg  	  		 = 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
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
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		if(APPLICATION_MODE)console.clear();
	};
	//*********/Clearing Console********************************************
	
	//**********successMsgShow**************************************************
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.successMsgShow = function(message){
		$scope.successMessage 	= message;				
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg = true;
		$scope.moveToMsg();
	};
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage = message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = false;
		$scope.moveToMsg();
	};
	//********** /errorMsgShow************************************************
	
	//**********hide Alert Msg************************************************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	};
	//********** /hide Alert Msg**********************************************
	
	//code used for sorting list order by fields 
	$scope.predicate = 'payment_source_id';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//**********Back Button************************************************
	$scope.backButton = function(){
		$scope.hideAlertMsg();
	};
	//**********Back Button************************************************
	
	//*****************display division dropdown start*************************
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;		
		$scope.divisionID = result.slice(0, 1)[0]['id'];		
		$scope.clearConsole();
	});
	//*****************display division dropdown end**************************
	
	//*****************display parent category dropdown code dropdown start fungetParentCategory()****
	$scope.parentCategoryList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'master/get-parent-product-category'
	}).success(function (result) { 
		$scope.parentCategoryList = result.parentCategoryList;
		$scope.productCategoryID = result.parentCategoryList.slice(0, 1)[0]['id'];		
		$scope.clearConsole();
	});
	
	//*****************display parent category dropdown code dropdown start fungetParentCategory()****
	$scope.orderMasterColumnList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'sales/reports/get-order-master-columns'
	}).success(function (result) { 
		$scope.orderMasterColumnList = result.orderMasterColumnList;
		$scope.clearConsole();
	});
	
	$scope.allSelectedOrderMasterColumnArray = [];
	$scope.validateCheckboxField = function(value){
		var indexOf = $scope.allSelectedOrderMasterColumnArray.indexOf(value);
		if(indexOf < 0) {
			$scope.allSelectedOrderMasterColumnArray.push(value);
		}else{
			$scope.allSelectedOrderMasterColumnArray.splice(indexOf, 1);
		}
	}

	//******************Listing of Customer GST Categories*************************	
	$scope.getCustomerDifinedTestReportFields = function(division_id,product_category_id){		
		if(division_id && product_category_id){
			$scope.allSelectedOrderMasterColumnArray = [];
			$scope.divisionID = division_id;
			$scope.productCategoryID = product_category_id;
			var http_para_string = {formData : 'ors_division_id='+division_id+'&ors_product_category_id='+product_category_id};
			$http({
				url: BASE_URL + "sales/reports/get-report-column-list",
				method: "POST",
				data: http_para_string
			}).success(function (result, status, headers, config) { 
				$scope.branchWiseSelectedColumnList = result.branchWiseSelectedColumnList;
				$scope.allSelectedOrderMasterColumnArray = result.allSelectedOrderMasterColumnList;
				$scope.customerDefinedTestReportField.ors_division_id = {id: $scope.divisionID};
				$scope.customerDefinedTestReportField.ors_product_category_id = {id: $scope.productCategoryID};
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '404'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole(); 
			});
		}else{
			$scope.branchWiseSelectedColumnList = [];
			$scope.allSelectedOrderMasterColumnArray = [];
		}
	};
	//******************/Listing of Customer GST Categories**********************

	//*****************Adding of Customer GST Category*********************
	$scope.funSaveUpdateColumnName = function(){
		
		if(!$scope.erpCustomerDifinedTestReportFieldForm.$valid)return;
		if($scope.erpCustomerDifinedTestReportFieldFlag)return;
		$scope.erpCustomerDifinedTestReportFieldFlag = true;
		var http_para_string = {formData : $(erpCustomerDifinedTestReportFieldForm).serialize()};
		$scope.loaderMainShow();
		
		$http({
			url: BASE_URL + "sales/reports/save-update-report-column-settings",
			method: "POST",
			data: http_para_string
		}).success(function (result, status, headers, config) {
			$scope.erpCustomerDifinedTestReportFieldFlag = false;
			if(result.error == 1){
				$scope.divisionID = result.formData.division_id;
				$scope.productCategoryID = result.formData.product_category_id;
				$scope.getCustomerDifinedTestReportFields($scope.divisionID,$scope.productCategoryID);
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();		
			$scope.loaderMainHide();
		}).error(function (result, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();		
			$scope.loaderMainHide();
		});
	};
	//*****************/Adding of Customer GST Category***************************
});