app.controller('MISReportsController', function($scope,$http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.successMessage 	   	= '';
	$scope.errorMessage   	   	= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup   	= '';
	$scope.generateMISReport        = {};
	$scope.MISReportDivName         = false;
	$scope.defaultMsg  	   	= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType    = 'order_date';   // set the default sort type
	$scope.sortReverse = false;         // set the default sort order
	$scope.searchFish  = '';    	// set the default search/filter term
	
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
	$scope.toggleDescription = function(type,id) {
		angular.element('#'+type+'limitedText-'+id).toggle();
		angular.element('#'+type+'fullText-'+id).toggle();
	};
	//*********/Read More description*****************************************
	
	//**********Read/hide More description************************************
	$scope.toggleContent = function(divId) {
		angular.element('#'+divId).toggle();
	};
	//*********/Read More description*****************************************
	
	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg 	 	= true;
	$scope.IsVisiableErrorMsg 	 	= true;
	$scope.IsVisiableSuccessMsgPopup 	= true;
	$scope.IsVisiableErrorMsgPopup 	 	= true;
	$scope.misGenerateReportFormDiv  	= true;
				
	$scope.closeButton = function(){
		$scope.MISReportDivName 	= false;
		$scope.MISReportData 		= [];
		angular.element('#misGenerateReportFormDiv').show();
		$scope.hideAlertMsg();
	};
	$scope.toggleButton = function(){
		$scope.toggleContent('misGenerateReportFormDiv');
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
	
	//*****************display MIS Reports Types Listing******************
	$scope.MISReportTypesList = [];
	$http({
		method: 'GET',
		url: BASE_URL +'MIS/get-mis-report-types',
	}).success(function (result) {
		$scope.MISReportTypesList = result.returnData;
	});
	//****************//display MIS Reports Types Listing******************
	
	//*****************display division dropdown start***********************	
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end***************************
	
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
	
	//*****************display parent category*********************************
	$scope.defaultOrderStagePhases = [];	
	$http({
	    method: 'POST',
	    url: BASE_URL +'MIS/default-order-stage-phases'
	}).success(function (result) { 
	    $scope.defaultOrderStagePhaseList = result.defaultOrderStagePhaseList;
	    $scope.clearConsole();
	});
	//****************/display parent category*********************************
	
	//*********Get From To Date Init**********************************************
	$scope.funGetFromToDateInit = function(){
		angular.element('#date_from, #date_to, #expected_due_date_from, #expected_due_date_to').datepicker({format: "dd/mm/yyyy"});	      
		$scope.generateMISReport.date_from 		= CURRENTDATE;
		$scope.generateMISReport.date_to   		= CURRENTDATE;
	};
	//********/Get From To Date Init**********************************************
	
	//*****************sale_executive dropdown list****************************
	$scope.salesExecutiveList = [];
	$scope.funGetSalesExecutives = function(division_id){ 		
		if(angular.isDefined(division_id)){
			$http({
				method: 'GET',
				url: BASE_URL +'customer/get_sales_executive_list/'+division_id
			}).success(function (result) {
				$scope.salesExecutiveList = result.executiveList;
				$scope.clearConsole();
			});
		}		
	}
	//*****************/sale_executive dropdown list*********************
	
	//*****************User Type Data dropdown list**********************
	$scope.userDefaultStatusList = [];
	$scope.funUserDefaultRoleList = function(){ 		
		$http({
			method: 'GET',
			url: BASE_URL +'MIS/get-default-roles',
		}).success(function (result) {
			$scope.userDefaultStatusList = result.itemsList;
			$scope.clearConsole();
		});				
	}
	//*****************/User Type Data dropdown list*********************
	
	//*****************sale_executive dropdown list**********************
	$scope.userNameByRoleList = [];
	$scope.funGetUserNameByRoles = function(roleId,divisionId,productCategoryId){ 		
		if(roleId){
			$scope.MISReportData = [];
			var http_para_string = {formData : 'role_id='+roleId+'&division_id='+divisionId+'&product_category_id='+productCategoryId};
			
			$http({
				method: 'POST',
				url: BASE_URL +'MIS/get-user-by-user-role-names',
				data: http_para_string,
			}).success(function (result) {
				$scope.userNameByRoleList = result.itemsList;
				$scope.clearConsole();
			});
		}		
	}
	//*****************/sale_executive dropdown list*********************
	
	//************Sorting list order******************************************
	$scope.funShowReportDataAreaDiv = function(MISReportType){
		$scope.MISReportResultDiv = MISReportType;
		$scope.MISReportData      = [];
	};
	//************/Sorting list order*****************************************
	
	//**********Generate MIS Report*******************************************
	$scope.funGenerateMISReport = function(divisionId){
		
		if($scope.newGenerateMISReportflag)return;
		$scope.newGenerateMISReportflag = true;
		$scope.MISReportDivName 	= $scope.MISReportResultDiv;
		$scope.MISReportData       	= [];
		$scope.loaderMainShow();
		
		$http({
			url: BASE_URL + "MIS/generate-mis-report",
			method: "POST",
			data: {formData : $(erpGenerateMISReportForm).serialize()}
		}).success(function(result, status, headers, config) {
			$scope.newGenerateMISReportflag = false;
			$scope.MISReportData = result.returnData;
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config) {
			$scope.newGenerateMISReportflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/Adding of Order*************************************************
	
	//**********Add Remove Division Required*************************************
	$scope.funAddRemoveDivisionRequired = function(checkboxdivId){
		var checked = angular.element('#'+checkboxdivId).prop('checked');
		$scope.generateMISReport.division_id 		= {};
		$scope.salesExecutiveList.sale_executive_id 	= {};
		$scope.salesExecutiveList 			= [];
		if(checked){
			$scope.salesExecutiveList = [];
		}
	};
	//*********/Add Remove Division Required*************************************
	
	//**********Add Remove Sales Executive Required******************************
	$scope.isSalesExecutiveRequired = true;
	$scope.funAddRemoveSalesExecutiveRequired = function(checkboxdivId){
		var checked = angular.element('#'+checkboxdivId).prop('checked');
		if(checked){
			$scope.isSalesExecutiveRequired = false;
			$scope.isSalesExecutiveDisabled = true;
			$scope.generateMISReport.sale_executive_id = {};
		}else{
			$scope.isSalesExecutiveRequired = true;
			$scope.isSalesExecutiveDisabled = false;
		}
	};
	//*********/Add Remove Sales Executive Required******************************
	
});