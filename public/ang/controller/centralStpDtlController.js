app.controller('centralStpDtlController', function($scope, $http, BASE_URL, $window ,$ngConfirm){
	
	// sorting variables
	$scope.sortType           	= 'cstp_id'; 		//set the default sort type
	$scope.sortReverse  	  	= false; 		//set the default sort order
	$scope.customerGstCategoryList 	= [];
	$scope.editCustomerGstCategory 	= {};
	$scope.centralSTPDtl 		= {};
	$scope.centralSTPDtl.cstp_status= '';
	$scope.successMessage 	  	= '';
	$scope.errorMessage   	  	= '';
	$files				= [];
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
	$scope.isViewListDiv	    = false;
	$scope.isViewAddDiv	    = false;
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
	$scope.centralSTPDtl.cstp_status = {id : 1};
	//*******************/Pre populated Select Box************************
	
	//**********Reset Form Button******************************************
	$scope.resetForm = function(){
		$scope.centralSTPDtl = {};	
		$scope.erpAddCentralSTPForm.$setUntouched();
		$scope.erpAddCentralSTPForm.$setPristine();
		angular.element("#cstp_file_name").val('');
		var formdata = new FormData();
	};
	//**********/Reset Form Button*****************************************
	
	//**********Back Button************************************************
	$scope.backButton = function(type){
		if (type){
			$scope.isViewListDiv = false;
			$scope.isViewAddDiv = true;
		}else{
			$scope.isViewListDiv = true;
			$scope.isViewAddDiv = false;
		}
		$scope.resetForm();
		$scope.hideAlertMsg();
	};
	//**********Back Button************************************************
	
	//*****************display customer list dropdown******************************
	$scope.customerListData = [];
	$http({
		method: 'POST',
		url: BASE_URL +'master/stps/customers-list'
	}).success(function (result) {
		$scope.customerListData = result.customersList;
		$scope.clearConsole();
	});
	//*****************/display customer list code dropdown*********************
	
	//*****************city dropdown on state change**********************
	$scope.funGetCityOnCustomerChange = function(customer_id){
		if(customer_id){
			$http({
				method: 'GET',
				url: BASE_URL +'master/stps/get-customer-citiy/'+customer_id,
			}).success(function (result) {
				$scope.customerCityList = [{id : result.customerLocationLicNo.city_id,name: result.customerLocationLicNo.city_name}];
				$scope.centralSTPDtl.cstp_customer_city = {id : result.customerLocationLicNo.city_id};
				$scope.clearConsole();
			});
		}
	}
	//****************/city dropdown on state change********************
	
	//******************Listing of Customer GST Categories*************************	
	$scope.funGetCentralContentListing = function(){
		$http({
			url: BASE_URL + "master/stps/central-stp-listing",
			method: "POST",			
		}).success(function (result, status, headers, config) { 
			$scope.centralContentList = result.centralStpContentList;
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
	var formdata = new FormData();
	$scope.getTheFiles = function ($files) {
                angular.forEach($files, function (value, key) {
			formdata.append('fileData', value);
                });
        };
	$scope.funAddCentralContentDtl = function(){
		
		if(!$scope.erpAddCentralSTPForm.$valid)return;
		if($scope.erpAddCentralSTPFormFlag)return;
		if(!angular.element("#cstp_file_name").val())return;
		$scope.erpAddCentralSTPFormFlag = true;
		formdata.append('formData', $(erpAddCentralSTPForm).serialize());
		$scope.loaderShow();
		
		$http({
			url: BASE_URL + "master/stps/add-central-stp",
			method: "POST",
			headers: { 'Content-Type': undefined },
			data: formdata,
		}).success(function (result, status, headers, config) {
			$scope.erpAddCentralSTPFormFlag = false;
			if(result.error == 1){
				$scope.resetForm();				
				$scope.funGetCentralContentListing();
				$scope.successMsgShow(result.message);
			}else{
				angular.element("#cstp_file_name").val('');
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
						$scope.funDeleteCentralStp(id);
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'					
				}
			}
		});
	};
	$scope.funDeleteCentralStp = function(id){   
		if(!id)return;
		$scope.loaderShow();		
		$http({
			url: BASE_URL + "master/stps/delete-stp-dtl",
			method: "POST",
			data: {cstp_id : id},
		}).success(function (result, status, headers, config) {  
			if(result.error == 1){
				$scope.funGetCentralContentListing();
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
}).directive('fileUpload', function () {
    return {
        scope: true,        //create a new scope
        link: function (scope, el, attrs) {
            el.bind('change', function (event) {
                var files = event.target.files;
                //iterate files since 'multiple' may be specified on the element
                for (var i = 0;i<files.length;i++) {
                    //emit event upward
                    scope.$emit("fileSelected", { file: files[i] });
                }                                       
            });
        }
    };
}).directive('ngFiles', ['$parse', function ($parse) {
	function fn_link(scope, element, attrs) {
		var onChange = $parse(attrs.ngFiles);
		element.on('change', function (event) {
			onChange(scope, { $files: event.target.files });
		});
	};
	return {
	    link: fn_link
	}
}]);