app.controller('customerGstCategoryController', function($scope, $http, BASE_URL, $window ,$ngConfirm){
	
	// sorting variables
	$scope.sortType           = 'cgc_id'; 		//set the default sort type
	$scope.sortReverse  	  = false; 		//set the default sort order
	$scope.customerGstCategoryList = [];
	$scope.editCustomerGstCategory = {};
	$scope.cusomerGstCategory = {};
	$scope.cusomerGstCategory.cgc_status = '';
	$scope.successMessage 	  = '';
	$scope.errorMessage   	  = '';	
	$scope.defaultMsg  	  = 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
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
	$scope.isViewAddCustomerGstCategoryForm = false;
	$scope.isViewEditCustomerGstCategoryForm = true;
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
	$scope.cusomerGstCategory.cgc_status = {id : 1};
	//*******************/Pre populated Select Box************************
	
	//**********Reset Form Button******************************************
	$scope.resetForm = function(){
		$scope.cusomerGstCategory.cgc_name = null;	
		$scope.erpAddCustomerGstCategoryForm.$setUntouched();
		$scope.erpAddCustomerGstCategoryForm.$setPristine();
	};
	//**********/Reset Form Button*****************************************
	
	//**********Back Button************************************************
	$scope.backButton = function(type){
		if (type){
			$scope.isViewAddCustomerGstCategoryForm = false;
			$scope.isViewEditCustomerGstCategoryForm = true;
		}else{
			$scope.isViewAddCustomerGstCategoryForm = true;
			$scope.isViewEditCustomerGstCategoryForm = false;
		}
		$scope.resetForm();
		$scope.hideAlertMsg();
	};
	//**********Back Button************************************************
	
	//******************Listing of Customer GST Categories*************************	
	$scope.funGetCustomerGstCategory = function(){
		$http({
			url: BASE_URL + "master/customers/customer-gst-categories/list",
			method: "POST",			
		}).success(function (result, status, headers, config) { 
			$scope.customerGstCategoryList = result.customerGstCategoryList;
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
	$scope.funAddCustomerGstCategories = function(){
		
		if(!$scope.erpAddCustomerGstCategoryForm.$valid)return;
		if($scope.erpAddCustomerGstCategoryFormFlag)return;
		$scope.erpAddCustomerGstCategoryFormFlag = true;
		$scope.loaderShow();	
		var http_para_string = {formData : $(erpAddCustomerGstCategoryForm).serialize()};
		
		$http({
			url: BASE_URL + "master/customers/customer-gst-categories/add",
			method: "POST",
			data: http_para_string
		}).success(function (result, status, headers, config) {
			$scope.erpAddCustomerGstCategoryFormFlag = false;
			if(result.error == 1){
				$scope.resetForm();
				$scope.funGetCustomerGstCategory();
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
	$scope.funEditCgCategory = function(id){		
		if(!id)return;
		$scope.backButton(false);
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/customers/customer-gst-categories/edit",
			method: "POST",
			data: {formData : 'id='+id}
		}).success(function (result, status, headers, config) { 			
			if(result.error == 1){
				$scope.editCustomerGstCategory = result.customerGstCategory;
				$scope.editCustomerGstCategory.cgc_status = {id : result.customerGstCategory.cgc_status};
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
	$scope.funUpdateCustomerGstCategories = function(){
		
		if(!$scope.erpEditCustomerGstCategoryForm.$valid)return;
		$scope.loaderShow();
		var http_para_string = {formData : $(erpEditCustomerGstCategoryForm).serialize()};
		
		$http({
			url: BASE_URL + "master/customers/customer-gst-categories/update",
			method: "POST",
			data: http_para_string
		}).success(function (result, status, headers, config) { 	
			if(result.error == 1){
				$scope.funGetCustomerGstCategory();
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
						$scope.funDeleteCgCategory(id);
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'					
				}
			}
		});
	};
	$scope.funDeleteCgCategory = function(id){   
		if(!id)return;
		$scope.loaderShow();		
		$http({
			url: BASE_URL + "master/customers/customer-gst-categories/delete",
			method: "POST",
			data: {formData : 'cgc_id='+id}
		}).success(function (result, status, headers, config) {  
			if(result.error == 1){
				$scope.funGetCustomerGstCategory();
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
});