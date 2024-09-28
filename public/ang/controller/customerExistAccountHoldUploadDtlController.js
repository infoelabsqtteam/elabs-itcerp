app.controller('customerExistAccountHoldUploadDtlController', function($scope, $http, BASE_URL,$ngConfirm,$timeout) {
	
	//define empty variables
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.searchKeyword 	= '';
	$scope.defaultMsg  		= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.downloadType	    = '1';
	$scope.customerExistAccountHoldUploadList = [];
	$scope.customerExistAccountDownloadList   = [];

	//************code used for sorting list order by fields*****************
	//sorting variables
	$scope.sortType     = 'ceahud_customer_name';    	 // set the default sort type
	$scope.sortReverse  = false;         	 			// set the default sort order
	$scope.searchFish   = '';    		 				// set the default search/filter term
	$scope.predicate 	= 'ceahud_id';
	$scope.reverse   	= true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields****************
		
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
	$scope.listMasterFormBladeDiv 		= false;
	$scope.addMasterFormBladeDiv 		= false;
	$scope.editMasterFormBladeDiv 		= true;
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
						return;
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
		$scope.addBWMaster = {};
		$scope.erpAddBWMasterForm.$setUntouched();
		$scope.erpAddBWMasterForm.$setPristine();
		$scope.editBWMaster = {};
		$scope.erpEditBWMasterForm.$setUntouched();
		$scope.erpEditBWMasterForm.$setPristine();
		$scope.listMasterFormBladeDiv 	= false;
		$scope.addMasterFormBladeDiv 	= false;
		$scope.editMasterFormBladeDiv 	= true;
	};
	//**********/Back Button********************************************

	//**********Navigate Button*********************************************
	$scope.navigateMasterPage = function(){
		$scope.listMasterFormBladeDiv 	= false;
		$scope.addMasterFormBladeDiv 	= false;
		$scope.editMasterFormBladeDiv 	= true;
	};
	//**********/Navigate Button********************************************
    
	//**********Reset Button*********************************************
	$scope.resetButton = function(){
		$scope.addBWMaster = {};
		$scope.erpAddBWMasterForm.$setUntouched();
		$scope.erpAddBWMasterForm.$setPristine();
		angular.element(erpAddBWMasterForm)[0].reset();
		$scope.addBWMaster.importFile = null;
		
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
	$scope.funGetUploadMasterList = function(){
		$scope.hideAlertMsg();
		$scope.loaderShow();	
		var http_para_string = {};
		$http({
			url: BASE_URL + "/master/customer/cust-exist-acct-hold-list",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			if (result.error == 1) {
				$scope.customerExistAccountHoldUploadList = result.customerExistAccountHoldUploadList;
				$scope.downloadContentList = result.downloadDataList;
				$scope.addMasterFormBladeDiv  = false;
			}else{
				$scope.customerExistAccountHoldUploadList = [];
				$scope.downloadContentList = [];
				$scope.errorMsgShow(result.message);
				$scope.addMasterFormBladeDiv  = true;
			}
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
	
	//*****************Uploading of Content********************************
    $scope.funAddUploadMaster = function () {

        if ($scope.newImportMasterTypeFormflag) return;
        $scope.newImportMasterTypeFormflag = true;

        //Validating Empty File Name
        var import_file_name = document.getElementById("importFile").value;
        if (!import_file_name) {
            $scope.newImportMasterTypeFormflag = false;
            $scope.errorMsgShow(noFileSelected);
        } else {

            //Creating File Data
            var fileData = document.getElementById("importFile").files[0];
            var formData = new FormData();
            formData.append('file', fileData);
            formData.append('file_name', import_file_name);
            formData.append('formData', $(erpAddBWMasterForm).serialize());

            //Displayig Loader
            $scope.loaderShow();
            $scope.hideAlertMsg();

            $http({
                url: BASE_URL + "/master/customer/upload-cust-exist-account",
                method: "POST",
                data: formData,
                headers: { 'Content-Type': undefined },
                transformRequest: angular.identity,
            }).success(function (result, status, headers, config) {
                $scope.newImportMasterTypeFormflag = false;
                if (result.error == 1) {
                    $scope.funGetUploadMasterList();
					$scope.successMsgShow(result.message);
					$scope.resetButton();
                } else {
                    $scope.errorMsgShow(result.message);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function (result, status, headers, config) {
                $scope.newImportMasterTypeFormflag = false;
                $scope.loaderHide();
                if (status == '500' || status == '404') {
                    $scope.errorMsgShowPopup($scope.defaultMsg);
                }
            });
        }
    };
    //*****************/Uploading of Content********************************

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
}).directive('validFile', function () {
	return {
		require: 'ngModel',
		link: function (scope, elem, attrs, ngModel) {
			var validFormats = ['csv','CSV'];
			elem.bind('change', function () {
				validImage(false);
				scope.$apply(function () {
					ngModel.$render();
				});
			});
			ngModel.$render = function () {
				ngModel.$setViewValue(elem.val());
			};
			function validImage(bool) {
				ngModel.$setValidity('extension', bool);
			}
			ngModel.$parsers.push(function (value) {
				var ext = value.substr(value.lastIndexOf('.') + 1);
				if (ext == '') return;
				if (validFormats.indexOf(ext) == -1) {
					return value;
				}
				validImage(true);
				return value;
			});
		}
	};
}).directive('fileModel', ['$parse', function ($parse) {
	return {
		restrict: 'A',
		link: function (scope, element, attrs) {
			var model = $parse(attrs.fileModel);
			var modelSetter = model.assign;
			element.bind('change', function () {
				scope.$apply(function () {
					modelSetter(scope, element[0].files[0]);
				});
			});
		}
	};
}]).directive('ngFiles', ['$parse', function ($parse) {
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