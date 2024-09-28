app.controller('paymentReceivedHdrsController', function($scope, $http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.prodata 	        = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.defaultMsg  		= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     	= 'payment_received_hdr_id';    // set the default sort type
	$scope.sortReverse  	= false;         // set the default sort order
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
	
	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 	 	 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	$scope.listPaymentFormBladeDiv 	 = false;
	$scope.addPaymentFormBladeDiv 	 = true;
	$scope.editPaymentFormBladeDiv 	 = true;
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
	$scope.funConfirmDeleteMessage = function(id,divisionId){
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
						$scope.funDeletePaymentReceived(id,divisionId);
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
	
	//**********confirm box******************************************************
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	};
	//********** /confirm box****************************************************
	
	//**********navigate Form*********************************************
	$scope.navigatePaymentReceivedPage = function(){        
		if(!$scope.addPaymentFormBladeDiv){            
			$scope.addPaymentFormBladeDiv 	= true;
            $scope.editPaymentFormBladeDiv 	= true;
            $scope.listPaymentFormBladeDiv  = false;
		}else if(!$scope.editPaymentFormBladeDiv){
            $scope.addPaymentFormBladeDiv 	= true;
            $scope.editPaymentFormBladeDiv 	= true;
            $scope.listPaymentFormBladeDiv  = false;
        }else{
            $scope.listPaymentFormBladeDiv  = true;			
            $scope.editPaymentFormBladeDiv 	= true;
            $scope.addPaymentFormBladeDiv 	= false;
		}	
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************
	
	//**********Back Button*********************************************
	$scope.backButton = function(){
		$scope.addBWPaymentReceived = {};
		$scope.erpAddBWPaymentReceivedForm.$setUntouched();
		$scope.erpAddBWPaymentReceivedForm.$setPristine();
		$scope.editBWPaymentReceived = {};
		$scope.erpEditBWPaymentReceivedForm.$setUntouched();
		$scope.erpEditBWPaymentReceivedForm.$setPristine();
		$scope.navigatePaymentReceivedPage();
	};
	//**********/Back Button********************************************
    
    //**********Reset Button*********************************************
	$scope.resetButton = function(){
		$scope.addBWPaymentReceived = {};
		$scope.erpAddBWPaymentReceivedForm.$setUntouched();
		$scope.erpAddBWPaymentReceivedForm.$setPristine();
		$scope.editBWPaymentReceived = {};
		$scope.erpEditBWPaymentReceivedForm.$setUntouched();
		$scope.erpEditBWPaymentReceivedForm.$setPristine();
	};
	//**********/Reset Button*********************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	};
	//*********/Clearing Console*********************************************
	
	//************code used for sorting list order by fields*****************
	$scope.predicate = 'payment_received_hdr_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************
	
	//*****************display Vendor dropdown********************************
	$scope.defaultPaymentReceivedNumber  = '';
	$scope.generatePaymentReceivedNumber = function(){
		$scope.hideAlertMsg();
		$http({
			method: 'GET',
			url: BASE_URL +'payments/generate-payment-received-number'
		}).success(function (result) {
			$scope.defaultPaymentReceivedNumber = result.paymentReceivedNumber;
			$scope.clearConsole();
		});
	};
	//*****************/display Vendor dropdown*****************
	
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
    
    //*****************display customer list dropdown******************************
	$scope.customerListData = [];
    $http({
        method: 'POST',
        url: BASE_URL +'payments/customers-list'
    }).success(function (result) {
        $scope.customerListData = result.customersList;
        $scope.clearConsole();
    });
	//*****************/display customer list code dropdown*********************
    
    //*****************display customer list dropdown******************************
	$scope.paymentSourceList = [];
    $http({
        method: 'POST',
        url: BASE_URL +'payments/deposited-with-list'
    }).success(function (result) {
        $scope.paymentSourceList = result.paymentSourceList;
        $scope.clearConsole();
    });
	//*****************/display customer list code dropdown*********************
	
	//**********Getting all Payment Received*******************************************
	$scope.funGetBranchWisePaymentReceived = function(divisionId){
		$scope.searchPaymentReceived={};
		$scope.loaderShow();
		$scope.divisionID = divisionId;		
		$http({
			url: BASE_URL + "payments/get-dw-payments-received/"+divisionId,
			method: "GET",
		}).success(function(result, status, headers, config){
			$scope.paymentsReceivedList = result.paymentsReceivedList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config) {
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
    };
	//**********/Getting all Payment Received*************************************************
	
	//**************multisearch start here**********************/
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	
	$scope.getMultiSearch = function()
    {   
		$scope.searchPaymentReceived.search_division_id = $scope.divisionID;
		$scope.filterPaymentReceived='';
		$scope.loaderShow();
		$http.post(BASE_URL + "payment/payment-received/get-payment-received-multisearch", {
            data: { formData:$scope.searchPaymentReceived },
        }).success(function (data, status, headers, config){ 
			$scope.paymentsReceivedList = data.paymentsReceivedList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '400'){
				//$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
    };
	
	$scope.closeMultisearch = function()
    { 
	    $scope.multiSearchTr=true;
		$scope.multisearchBtn=false;
		$scope.refreshMultisearch();
	}
	
	$scope.refreshMultisearch = function()
    { 
	    $scope.searchPaymentReceived={};
	    $scope.filterPaymentReceived='';
		$scope.funGetBranchWisePaymentReceived($scope.divisionID);
	}
	
	$scope.openMultisearch = function()
    { 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	}
	//**************multisearch end here**********************/
	
	//***************** Adding of Branch Wise PaymentReceived ********************************
	$scope.funAddBranchWisePaymentReceived = function(divisionId){
		
		if(!$scope.erpAddBWPaymentReceivedForm.$valid)return;		
		if($scope.newAddBranchWisePaymentReceivedFormflag)return;		
		$scope.newAddBranchWisePaymentReceivedFormflag = true;		
		var formData = $(erpAddBWPaymentReceivedForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "payments/add-dw-payments-received",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.newAddBranchWisePaymentReceivedFormflag = false;
			if(data.error == 1){
				$scope.backButton();                
                $scope.funGetBranchWisePaymentReceived(divisionId);
				$scope.generatePaymentReceivedNumber();
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newAddBranchWisePaymentReceivedFormflag = false;
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Branch Wise PaymentReceived ***************************
	
	//**************** editing of Payment Received *************************************
	$scope.funEditPaymentReceived = function(payment_received_hdr_id,divisionId){
		if(payment_received_hdr_id){
			$scope.loaderShow();
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "payments/view-dw-payments-received/"+payment_received_hdr_id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
                    $scope.listPaymentFormBladeDiv  = true;
					$scope.addPaymentFormBladeDiv 	= true;			
					$scope.editPaymentFormBladeDiv  = false;
					$scope.editBWPaymentReceived    = result.paymentsReceived;
					$scope.editBWPaymentReceived.division_id  = {
						selectedOption: { id: result.paymentsReceived.division_id} 
					};
                    $scope.editBWPaymentReceived.customer_id  = {
						selectedOption: { id: result.paymentsReceived.customer_id} 
					};
                    $scope.editBWPaymentReceived.payment_source_id  = {
						selectedOption: { id: result.paymentsReceived.payment_source_id} 
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
	//****************/editing of Payment Received *************************************
	
	//**************** Updating of Payment Received *************************************
	$scope.funUpdateBranchWisePaymentReceived = function(divisionId){
		
		if(!$scope.erpEditBWPaymentReceivedForm.$valid)return;		
		var formData = $(erpEditBWPaymentReceivedForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "payments/update-dw-payments-received",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) { 
			if(result.error == 1){
                $scope.backButton();
				$scope.funGetBranchWisePaymentReceived(divisionId);
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
	//*************** /Updating of Payment Received **************************************
		
	//**************** Deleting of Branch Wise Payment Received ******************************
	$scope.funDeletePaymentReceived = function(payment_received_hdr_id,divisionId){
		if(payment_received_hdr_id){
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "payments/delete-dw-payments-received/"+payment_received_hdr_id,
			}).success(function(result, status, headers, config){				
				if(result.error == 1){
					$scope.funGetBranchWisePaymentReceived(divisionId);				
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
	//************** /Deleting of Branch Wise Payment Received *********************************
			
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
});