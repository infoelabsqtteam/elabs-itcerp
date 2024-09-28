app.controller('customerInvoicingRatesController', function($scope, $http, BASE_URL,$ngConfirm) {

	//define empty variables
	$scope.stateWiseProductRateList = '';
	$scope.successMessage 			= '';
	$scope.errorMessage   			= '';
	$scope.cirStateID       		= '0';
	$scope.defaultMsg  				= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     			= 'cir_id';    // set the default sort type
	$scope.sortReverse  			= false;         // set the default sort order
	$scope.searchFish   			= '';    		 // set the default search/filter term

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

	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg 	     = true;
	$scope.IsVisiableErrorMsg 	 	     = true;
	$scope.IsVisiableSuccessMsgPopup     = true;
	$scope.IsVisiableErrorMsgPopup 	     = true;
	$scope.listStateWiseProductRateDiv 	 = false;
	$scope.addStateWiseProductRateDiv 	 = true;
	$scope.editStateWiseProductRateDiv 	 = true;
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
	$scope.funConfirmDeleteMessage = function(id,cirStateId,type){
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
						if(type == 'stateWiseProductRate'){
							$scope.funDeleteStateWiseProductRate(id,cirStateId);
						}else if(type == 'customerWiseProductRate'){
							$scope.funDeleteCustomerWiseProductRate(id,cirStateId);
						}
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

	//**********navigate Form*********************************************
	$scope.navigateFormPage = function(cirStateID){
		if(!$scope.addStateWiseProductRateDiv){
			$scope.addStateWiseProductRateDiv 	= true;
            $scope.editStateWiseProductRateDiv 	= true;
            $scope.listStateWiseProductRateDiv  = false;
			$scope.funGetStateWiseProductRates(cirStateID);
		}else if(!$scope.editStateWiseProductRateDiv){
            $scope.addStateWiseProductRateDiv 	= true;
            $scope.editStateWiseProductRateDiv 	= true;
            $scope.listStateWiseProductRateDiv  = false;
        }else{
            $scope.listStateWiseProductRateDiv  = true;
            $scope.editStateWiseProductRateDiv 	= true;
            $scope.addStateWiseProductRateDiv 	= false;
		}
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************

	//**********Back Button*********************************************
	$scope.backButton = function(){
		$scope.addStateWiseProductRate = {};
		$scope.erpAddStateWiseProductRateForm.$setUntouched();
		$scope.erpAddStateWiseProductRateForm.$setPristine();
		$scope.editStateWiseProductRate = {};
		$scope.erpEditStateWiseProductRateForm.$setUntouched();
		$scope.erpEditStateWiseProductRateForm.$setPristine();
		$scope.navigateFormPage();
	};
	//**********/Back Button********************************************

    //**********Reset Button*********************************************
	$scope.resetButton = function(){
		$scope.addStateWiseProductRate = {};
		$scope.erpAddStateWiseProductRateForm.$setUntouched();
		$scope.erpAddStateWiseProductRateForm.$setPristine();
		$scope.editStateWiseProductRate = {};
		$scope.erpEditStateWiseProductRateForm.$setUntouched();
		$scope.erpEditStateWiseProductRateForm.$setPristine();
	};
	//**********/Reset Button*********************************************

	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	};
	//*********/Clearing Console*********************************************

	//************code used for sorting list order by fields*****************
	$scope.predicate = 'cir_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************

	//*****************display division dropdown start************************
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end*******************************

	//*****************display state code dropdown start***************************
	$scope.stateCodeList = [];
		$http({
			method: 'POST',
			url: BASE_URL +'statesList'
		}).success(function (result) {
			if(result){
				$scope.stateCodeList = result;
			}
			$scope.clearConsole();
		});
	//*****************display state code dropdown end*****************************

	//*****************display customer list dropdown******************************
	$scope.customerProductsList = [];
    $http({
        method: 'POST',
        url: BASE_URL +'master/invoicing/state-wise-product-rates/product-list'
    }).success(function (result) {
        $scope.customerProductsList = result.customerProductsList;
        $scope.clearConsole();
    });
	//*****************/display customer list code dropdown************************

	//**********Getting all Payment Made*******************************************
	$scope.funGetStateWiseProductRates = function(cirStateId){

		$scope.cirStateID = cirStateId;
		$scope.loaderShow();

		$http({
			url: BASE_URL + "master/invoicing/state-wise-product-rates/get-state-wise-product-rates/"+cirStateId,
			method: "GET",
		}).success(function(result, status, headers, config){
			$scope.stateWiseProductRateList = result.stateWiseProductRateList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
    };
	//**********/Getting all Payment Made***************************************************

	//***************** Adding of Branch Wise DebitNote **********************
	$scope.funAddStateWiseProductRate = function(cirStateID){

		if(!$scope.erpAddStateWiseProductRateForm.$valid)return;
		if($scope.newAddStateWiseProductRateFormflag)return;
		$scope.newAddStateWiseProductRateFormflag = true;
		var formData = $(erpAddStateWiseProductRateForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "master/invoicing/state-wise-product-rates/add-state-wise-product-rates",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.newAddStateWiseProductRateFormflag = false;
			if(data.error == 1){
				$scope.resetButton();
        $scope.funGetStateWiseProductRates(cirStateID);
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newAddStateWiseProductRateFormflag = false;
			$scope.loaderHide();
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Branch Wise DebitNote ***************************

	//**************** editing of Payment Made *************************************
	$scope.funEditStateWiseProductRate = function(cirId){
		if(cirId){
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/invoicing/state-wise-product-rates/view-state-wise-product-rates/"+cirId,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
					$scope.listStateWiseProductRateDiv  = true;
					$scope.addStateWiseProductRateDiv 	= true;
					$scope.editStateWiseProductRateDiv 	= false;
					$scope.editStateWiseProductRate     = result.stateWiseProductRateData;
					$scope.editStateWiseProductRate.cir_state_id  = {
						selectedOption: { id: result.stateWiseProductRateData.cir_state_id}
					};
					$scope.editStateWiseProductRate.cir_c_product_id  = {
						selectedOption: { id: result.stateWiseProductRateData.cir_c_product_id}
					};
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
	//****************/editing of Payment Made *************************************

	//**************** Updating of Payment Made *************************************
	$scope.funUpdateStateWiseProductRate = function(cirId){

		if(!$scope.erpEditStateWiseProductRateForm.$valid)return;
		var formData = $(erpEditStateWiseProductRateForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "master/invoicing/state-wise-product-rates/update-state-wise-product-rates",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) {
			if(result.error == 1){
				$scope.funEditStateWiseProductRate(cirId);
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//*************** /Updating of Payment Made *************************************

	//**************** Deleting of Branch Wise Payment Made ***************************
	$scope.funDeleteStateWiseProductRate = function(id,cirStateId){
		if(id){
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/invoicing/state-wise-product-rates/delete-state-wise-product-rates/"+id,
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.funGetStateWiseProductRates(cirStateId);
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
	//************** /Deleting of Branch Wise Payment Made *******************************
});
