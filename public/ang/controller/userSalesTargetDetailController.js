app.controller('userSalesTargetDetailController', function($scope, $http, BASE_URL, $window ,$ngConfirm){
	
	// sorting variables
	$scope.sortType           = 'ust_id'; 		//set the default sort type
	$scope.sortReverse  	  = false; 		//set the default sort order
	$scope.userSalesTargetDtl = {};
	$scope.successMessage 	  = '';
	$scope.errorMessage   	  = '';
	$scope.userSalesTargetDtl = {};
	$scope.userSalesTargetDtl.ust_status = '';
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
	$scope.isViewListDiv	    = false;
	$scope.isViewAddDiv	    = false;
	$scope.isViewEditDiv 	    = true;
	$scope.isViewUploadDiv 	    = true;
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
	
	//**********navigate Form*********************************************
	$scope.navigatePage = function(type){        
		if(type == '1'){            
			$scope.isViewListDiv	    = false;
			$scope.isViewAddDiv	    = true;
			$scope.isViewEditDiv 	    = true;
			$scope.isViewUploadDiv 	    = false;
		}else{
			$scope.isViewListDiv	    = false;
			$scope.isViewAddDiv	    = false;
			$scope.isViewEditDiv 	    = true;
			$scope.isViewUploadDiv 	    = true;
		}
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************
	
	//code used for sorting list order by fields 
	$scope.predicate = 'payment_source_id';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//**********Reset Form Button******************************************
	$scope.resetForm = function(){
		$scope.userSalesTargetDtl.ust_amount = '';
		$scope.userSalesTargetDtl.ust_date = '';
		$scope.userSalesTargetDtl.ust_status = {id : 1};
		$scope.erpAddUserSalesTargetForm.$setUntouched();
		$scope.erpAddUserSalesTargetForm.$setPristine();
	};
	$scope.resetUploadForm = function(){
		$scope.userSalesTargetUploadDtl = {};
		$scope.userSalesTargetUploadDtl.ust_sales_target_file = '';
		document.getElementById('ust_sales_target_file').value = '';
		$scope.erpUploadSalesTargetCsvForm.$setUntouched();
		$scope.erpUploadSalesTargetCsvForm.$setPristine();
	};
	//**********/Reset Form Button*****************************************
	
	//**********Back Button************************************************
	$scope.backButton = function(type){
		if (type == '1'){
			$scope.isViewListDiv = false;
			$scope.isViewAddDiv = false;
			$scope.isViewEditDiv = true;
			$scope.isViewUploadDiv = true;
			$scope.userSalesTargetDtl = {};
			$scope.userSalesTargetDtl.ust_status = {id : 1};
		}else if (type == '2'){
			$scope.isViewListDiv = false;
			$scope.isViewAddDiv = true;
			$scope.isViewEditDiv = true;
			$scope.isViewUploadDiv = false;
			$scope.userSalesTargetDtl = {};
			$scope.userSalesTargetDtl.ust_status = {id : 1};
		}else{
			$scope.isViewListDiv = false;
			$scope.isViewEditDiv = true;
			$scope.isViewAddDiv = false;
			$scope.isViewUploadDiv = true;
			$scope.userSalesTargetDtl = {};
			$scope.userSalesTargetDtl.ust_status = {id : 1};
		}
		$scope.hideAlertMsg();
	};
	//**********Back Button************************************************
	
	//********************Pre populated Select Box****************************
	$scope.activeInactionSelectboxList = [
		{id: '1', name: 'Active'},
		{id: '2', name: 'Inactive'}	
	];
	$scope.userSalesTargetDtl.ust_status = {id : 1};
	//*******************/Pre populated Select Box************************
	
	//*****************Division dropdown***********************************
	$scope.DivisionsList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.DivisionsList = result;
		$scope.clearConsole();
	});
	//*****************Division dropdown***********************************
	
	//*****************display parent category Created on(09-April-2020) *********************************
	$scope.parentCategoryDropdownList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'master/get-parent-product-category'
	}).success(function (result) { 
		$scope.parentCategoryDropdownList = result.parentCategoryList;
		$scope.clearConsole();
	});
	//****************/display parent category*********************************
	
	//*********** Get customer list on change of sales executive/employee*******/
	$scope.customerListData = [];
	$scope.funGetCustomerList=function(employee_id){
		if(employee_id){
			$http({
				method: 'GET',
				url: BASE_URL +'master/employee/get-customer-list/'+employee_id,
			}).success(function (result) {
				$scope.customerListData = result.customerListData;
				$scope.clearConsole();
			});
		}
	}
	//*********** Get customer list on change of sales executive/employee*******/
	
	//*****************Get Employee On Division Change**********************
	$scope.employeeListData = [];
	$scope.funGetEmployeeOnDivisionChange = function(division_id){
		if(division_id){
			$http({
				method: 'GET',
				url: BASE_URL +'master/employee/get-employee-list/'+division_id,
			}).success(function (result) {
				$scope.employeeListData = result.employeeListData;
				$scope.clearConsole();
			});
		}
	}
	//*****************Get Employee On Division Change**********************
	
	//*****************User Sales Type Dropdown***********************************
	$scope.userSalesTargetTypeList = [];
	$http({
		method: 'GET',
		url: BASE_URL +'master/employee/sales-target/get-sales-types'
	}).success(function (result) {
		$scope.userSalesTargetTypeList = result.userSalesTargetTypeList;
		$scope.clearConsole();
	});
	//****************/User Sales Type Dropdown***********************************
		
	//******************Listing of User Sales Target Detail*************************	
	$scope.funGetUserSalesTargetListing = function(){
		$http({
			url: BASE_URL + "master/employee/sales-target-listing",
			method: "POST",			
		}).success(function (result, status, headers, config) { 
			$scope.userSalesTargetList = result.userSalesTargetList;
			$scope.clearConsole(); 
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole(); 
		});
	};
	//******************/Listing of User Sales Target Detail**********************

	//*****************Adding of User Sales Target Detail*********************
	$scope.funAddUserSalesTargetDtl = function(){
		
		if(!$scope.erpAddUserSalesTargetForm.$valid)return;
		if($scope.erpAddUserSalesTargetFormFlag)return;
		$scope.erpAddUserSalesTargetFormFlag = true;
		$scope.loaderShow();
		
		$http({
			url: BASE_URL + "master/employee/add-sales-target",
			method: "POST",
			data: {formData : $(erpAddUserSalesTargetForm).serialize()}
		}).success(function (result, status, headers, config) {
			$scope.erpAddUserSalesTargetFormFlag = false;
			if(result.error == 1){
				$scope.resetForm();
				$scope.funGetUserSalesTargetListing();
				$scope.successMsgShow(result.message);
			}else{
				angular.element("#cpo_file_name").val('');
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
		}).error(function (result, status, headers, config){
			$scope.erpAddUserSalesTargetFormFlag = false;
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
		});
	};
	//*****************/Adding of User Sales Target Detail***************************
	
	//*****************Viewing of User Sales Target Detail*********************
	$scope.funEditUserSalesTarget = function(id){
		if(id){
			$scope.loaderShow();
			$http({
				url: BASE_URL + "master/employee/sales-target-view/"+id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				
				//Display Edit Window
				$scope.isViewEditDiv = false;
				$scope.isViewAddDiv = true;
				$scope.isViewUploadDiv = true;
				
				//Calling Default Function
				$scope.funGetEmployeeOnDivisionChange(result.userSalesTarget.ust_division_id);
				$scope.funGetCustomerList(result.userSalesTarget.ust_user_id);
				
				$scope.editUserSalesTargetDtl 				= result.userSalesTarget;
				$scope.editUserSalesTargetDtl.ust_division_id 		= {id : result.userSalesTarget.ust_division_id};
				$scope.editUserSalesTargetDtl.ust_user_id 		= {id : result.userSalesTarget.ust_user_id};
				$scope.editUserSalesTargetDtl.ust_status 		= {id : result.userSalesTarget.ust_status};
				$scope.editUserSalesTargetDtl.ust_product_category_id 	= {id:result.userSalesTarget.ust_product_category_id};
				$scope.editUserSalesTargetDtl.ust_customer_id 		= {id:result.userSalesTarget.ust_customer_id};
				$scope.editUserSalesTargetDtl.ust_type_id 		= {id:result.userSalesTarget.ust_type_id};
				
				//Calling Console Function
				$scope.clearConsole();		
				$scope.loaderHide();
			}).error(function (result, status, headers, config){
				if(status == '500' || status == '404'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();		
				$scope.loaderHide();
			});
		}
	};
	//*****************/Viewing of User Sales Target Detail***************************
	
	//*****************Updating of User Sales Target Detail*********************
	$scope.funUpdateUserSalesTargetDtl = function(){
		
		if(!$scope.erpEditUserSalesTargetForm.$valid)return;
		if($scope.erpEditUserSalesTargetFormFlag)return;
		$scope.erpEditUserSalesTargetFormFlag = true;
		$scope.loaderShow();
		
		$http({
			url: BASE_URL + "master/employee/sales-target-update",
			method: "POST",
			data: {formData : $(erpEditUserSalesTargetForm).serialize()}
		}).success(function (result, status, headers, config) {
			$scope.erpEditUserSalesTargetFormFlag = false;
			if(result.error == 1){
				$scope.funGetUserSalesTargetListing();
				$scope.successMsgShow(result.message);
			}else{
				angular.element("#cpo_file_name").val('');
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
		}).error(function (result, status, headers, config){
			$scope.erpEditUserSalesTargetFormFlag = false;
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
		});
	};
	//*****************/Adding of User Sales Target Detail***************************
	
	//******************Deleting of User Sales Target Detail*************************
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
						$scope.funDeleteUserSalesTargetDtl(id);
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'					
				}
			}
		});
	};
	$scope.funDeleteUserSalesTargetDtl = function(id){   
		if(!id)return;
		$scope.loaderShow();		
		$http({
			url: BASE_URL + "master/employee/delete-sales-target-dtl",
			method: "POST",
			data: {ust_id : id},
		}).success(function (result, status, headers, config) {  
			if(result.error == 1){
				$scope.funGetUserSalesTargetListing();
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
	//******************/Deleting of User Sales Target Detail*************************
	
	//*****************Upload User Sales Target Detail*********************
	$scope.userSalesTargetUploadDtl = {};
	$scope.funUploadSalesTargetCSV = function(id) {
		if($scope.erpUploadSalesTargetCsvFormFlag)return;
		$scope.erpUploadSalesTargetCsvFormFlag = true;
		var file = "";
		var filename = document.getElementById(id);
		if (filename) {
		    file = filename.files[0];
		}
		if (!file) {
			$scope.erpUploadSalesTargetCsvFormFlag = false;
			$scope.errorMsgShow(noFileSelected);
		}else{
			var form_data = $(erpUploadSalesTargetCsvForm).serialize();
			var formData = new FormData();
			formData.append('file', file);
			formData.append('formData', form_data);
			$scope.loaderShow();
			
			$http({
				url: BASE_URL + "master/employee/sales-target/upload-csv-file",
				method: "POST",
				data: formData,
				headers: { 'Content-Type': undefined },
				transformRequest: angular.identity,
			}).success(function(result, status, headers, config) {
				$scope.erpUploadSalesTargetCsvFormFlag = false;
				if (result.error == '1') {
					$scope.resetUploadForm();
					$scope.funGetUserSalesTargetListing();
					$scope.successMsgShow(result.message);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config) {
				$scope.erpUploadSalesTargetCsvFormFlag = false;
				$scope.loaderHide();
				if (status == '500' || status == '404') {
				    $scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************/Upload User Sales Target Detail*********************
});