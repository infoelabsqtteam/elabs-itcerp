app.controller('scheduledMisReportDtlController', function($scope, $http, $timeout, BASE_URL, $window ,$ngConfirm){
	
	// sorting variables
	$scope.sortType           	= 'cgc_id'; 		//set the default sort type
	$scope.sortReverse  	  	= false; 		//set the default sort order
	$scope.successMessage 	  	= '';
	$scope.errorMessage   	  	= '';
	$scope.scheduledMisReport 	= {};
	$scope.editScheduledMisReport 	= {};
	$scope.defaultMsg  	  	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
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
		if(APPLICATION_MODE)console.clear();
	};
	//*********/Clearing Console********************************************
	
	//**********successMsgShow**************************************************
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.isViewAddScheduledMisReportForm = false;
	$scope.isViewEditScheduledMisReportForm = true;
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
	
	//********************Pre populated Select Box****************************
	$scope.activeInactionSelectboxList = [
		{id: '1', name: 'Active'},
		{id: '2', name: 'Inactive'}	
	];
	//*******************/Pre populated Select Box************************
	
	//**********Reset Form Button******************************************
	$scope.resetForm = function(type=false){
		if(type){
			$scope.funGetDivisionsCodeList();
			$scope.funGetParentCategoryList();
			$scope.funGetMISReportTypesList();
		}
		$scope.primaryEmailAddressList = [];
		$scope.secondayEmailAddressList = [];
		$scope.scheduledMisReport = {};	
		$scope.erpAddScheduledMisReportForm.$setUntouched();
		$scope.erpAddScheduledMisReportForm.$setPristine();
		$scope.editScheduledMisReport = {};
		$scope.erpEditScheduledMisReportForm.$setUntouched();
		$scope.erpEditScheduledMisReportForm.$setPristine();
	};
	//**********/Reset Form Button*****************************************
	
	//**********Back Button************************************************
	$scope.backButton = function(type){
		if (type){
			$scope.isViewAddScheduledMisReportForm = false;
			$scope.isViewEditScheduledMisReportForm = true;
		}else{
			$scope.isViewAddScheduledMisReportForm = true;
			$scope.isViewEditScheduledMisReportForm = false;
		}
		$scope.resetForm(type);
		$scope.hideAlertMsg();
	};
	//**********Back Button************************************************
	
	//*****************display division dropdown start***********************	
	$scope.divisionsCodeList = [];
	$scope.funGetDivisionsCodeList = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			$scope.divisionsCodeList = result;
			$scope.clearConsole();
		});
	};
	$scope.funGetDivisionsCodeList();
	//*****************display division dropdown end***************************
	
	//*****************display parent category*********************************
	$scope.parentCategoryList = [];
	$scope.funGetParentCategoryList = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'master/get-parent-product-category'
		}).success(function (result) { 
			$scope.parentCategoryList = result.parentCategoryList;
			$scope.clearConsole();
		});
	};
	$scope.funGetParentCategoryList();
	//****************/display parent category*********************************
	
	//*****************display MIS Reports Types Listing******************
	$scope.MISReportTypesList = [];
	$scope.funGetMISReportTypesList = function(){
		$http({
			method: 'GET',
			url: BASE_URL +'MIS/get-mis-report-types-dtl',
		}).success(function (result) {
			$scope.MISReportTypesList = result.reportFormType;
		});
	};
	$scope.funGetMISReportTypesList();
	//****************//display MIS Reports Types Listing******************
	
	//******************Listing of Customer GST Categories*************************	
	$scope.funGetScheduledMisReport = function(){
		$http({
			url: BASE_URL + "MIS/scheduled-reports/list",
			method: "POST",			
		}).success(function (result, status, headers, config) { 
			$scope.scheduledMisReportList = result.scheduledMisReportList;
			$scope.clearConsole(); 
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole(); 
		});
	};
	//******************/Listing of Customer GST Categories**********************

	//*****************Adding of Customer GST Category*********************
	$scope.funAddScheduledMisReport = function(){
		
		if(!$scope.erpAddScheduledMisReportForm.$valid)return;
		if($scope.erpAddScheduledMisReportFormFlag)return;
		$scope.erpAddScheduledMisReportFormFlag = true;
		$scope.loaderShow();	
		var http_para_string = {formData : $(erpAddScheduledMisReportForm).serialize()};
		
		$http({
			url: BASE_URL + "MIS/scheduled-reports/add",
			method: "POST",
			data: http_para_string
		}).success(function (result, status, headers, config) {
			$scope.erpAddScheduledMisReportFormFlag = false;
			if(result.error == 1){
				$scope.resetForm();
				$scope.funGetScheduledMisReport();
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
		}).error(function (result, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
		});
	};
	//*****************/Adding of Customer GST Category***************************
	
	//******************Editing of Customer GST Categories*************************
	$scope.funEditScheduledMisReport = function(id){		
		if(!id)return;
		$scope.backButton(false);
		$scope.primaryEmailAddressList = [];
		$scope.secondayEmailAddressList = [];
		$scope.loaderShow();
		$http({
			url: BASE_URL + "MIS/scheduled-reports/edit",
			method: "POST",
			data: {formData : 'id='+id}
		}).success(function (result, status, headers, config) { 			
			if(result.error == 1){
				$scope.editScheduledMisReport = result.editScheduledMisReportList;
				$scope.primaryEmailAddressList = result.editScheduledMisReportList.smrd_to_email_address;
				$scope.secondayEmailAddressList = result.editScheduledMisReportList.smrd_from_email_address;
				$scope.divisionsCodeList = [{id : result.editScheduledMisReportList.smrd_division_id, name : result.editScheduledMisReportList.smrd_division_name}];
				$scope.parentCategoryList = [{id : result.editScheduledMisReportList.smrd_product_category_id, name : result.editScheduledMisReportList.smrd_product_category_name}];
				$scope.MISReportTypesList = [{id : result.editScheduledMisReportList.smrd_mis_report_id, name : result.editScheduledMisReportList.smrd_mis_report_name}];
				$timeout(function (){
					$scope.editScheduledMisReport.smrd_division_id = {id : result.editScheduledMisReportList.smrd_division_id};
					$scope.editScheduledMisReport.smrd_product_category_id = {id : result.editScheduledMisReportList.smrd_product_category_id};
					$scope.editScheduledMisReport.smrd_mis_report_id = {id : result.editScheduledMisReportList.smrd_mis_report_id};
				}, 100);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if(status == '500' || status == '400'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});		
	};
	//******************/Editing of Customer GST Categories*************************
	
	//******************Updating of Customer GST Categories*************************
	$scope.funUpdateScheduledMisReport = function(){
		
		if(!$scope.erpEditScheduledMisReportForm.$valid)return;
		$scope.loaderShow();
		var http_para_string = {formData : $(erpEditScheduledMisReportForm).serialize()};
		
		$http({
			url: BASE_URL + "MIS/scheduled-reports/update",
			method: "POST",
			data: http_para_string
		}).success(function (result, status, headers, config) { 	
			if(result.error == 1){
				$scope.funGetScheduledMisReport();
				$scope.backButton(true);
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (result, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}); 
	};
	//******************/Updating of Customer GST Categories*************************
	
	//******************Deleting of Customer GST Categories*************************
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
						$scope.funDeleteScheduledMisReport(id);
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'					
				}
			}
		});
	};
	$scope.funDeleteScheduledMisReport = function(id){   
		if(!id)return;
		$scope.loaderShow();		
		$http({
			url: BASE_URL + "MIS/scheduled-reports/delete",
			method: "POST",
			data: {formData : 'smrd_id='+id}
		}).success(function (result, status, headers, config) {  
			if(result.error == 1){
				$scope.funGetScheduledMisReport();
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {   
			if(status == '500' || status == '400'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();	
			$scope.clearConsole();		
		});
	};
	//******************/Deleting of Customer GST Categories*************************
	
	//******************Add More Email Field*************************************
	$scope.primaryEmailAddressList = [];
	$scope.secondayEmailAddressList = [];
	$scope.funAddMoreEmailField = function(addPriSecEmailField,emailTypeField){		
		if(addPriSecEmailField){
			if(emailTypeField == true){
				$scope.secondayEmailAddressList.push(addPriSecEmailField);
			}else{
				$scope.primaryEmailAddressList.push(addPriSecEmailField);
			}
			$scope.scheduledMisReport.addPriSecEmailField = '';
			$scope.scheduledMisReport.emailTypeField = false;
			$scope.editScheduledMisReport.editPriSecEmailField = '';
			$scope.editScheduledMisReport.editEmailTypeField = false;
		}
	};	
	$scope.funRemoveEmailField = function(inputValue,type){
		if (type == 1) {
			$scope.primaryEmailAddressList.splice($scope.primaryEmailAddressList.indexOf(inputValue),1);
		}else if (type == 2) {
			$scope.secondayEmailAddressList.splice($scope.secondayEmailAddressList.indexOf(inputValue),1);
		}
	};
	//******************/Add More Email Field*************************************
	
});