app.controller('paymentMadeHdrsController', function($scope, $http, BASE_URL,$ngConfirm) {
	
	//define empty variables
	$scope.prodata 	        = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.defaultMsg  		= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
	//sorting variables
	$scope.sortType     	= 'payment_made_hdr_id';    // set the default sort type
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
						$scope.funDeletePaymentMade(id,divisionId);
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
	$scope.navigatePaymentMadePage = function(){        
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
		$scope.addBWPaymentMade = {};
		$scope.erpAddBWPaymentMadeForm.$setUntouched();
		$scope.erpAddBWPaymentMadeForm.$setPristine();
		$scope.editBWPaymentMade = {};
		$scope.erpEditBWPaymentMadeForm.$setUntouched();
		$scope.erpEditBWPaymentMadeForm.$setPristine();
		$scope.navigatePaymentMadePage();
	};
	//**********/Back Button********************************************
    
    //**********Reset Button*********************************************
	$scope.resetButton = function(){
		$scope.addBWPaymentMade = {};
		$scope.erpAddBWPaymentMadeForm.$setUntouched();
		$scope.erpAddBWPaymentMadeForm.$setPristine();
		$scope.editBWPaymentMade = {};
		$scope.erpEditBWPaymentMadeForm.$setUntouched();
		$scope.erpEditBWPaymentMadeForm.$setPristine();
	};
	//**********/Reset Button*********************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	};
	//*********/Clearing Console*********************************************
	
	//************code used for sorting list order by fields*****************
	$scope.predicate = 'payment_made_hdr_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************
	
	//*****************display Vendor dropdown********************************
	$scope.defaultPaymentMadeNumber  = '';
	$scope.generatePaymentMadeNumber = function(){
		$scope.hideAlertMsg();
		$http({
			method: 'GET',
			url: BASE_URL +'payments/generate-payment-made-number'
		}).success(function (result) {
			$scope.defaultPaymentMadeNumber = result.paymentMadeNumber;
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
	$scope.vendorListData = [];
    $http({
        method: 'POST',
        url: BASE_URL +'payments/vendors-list'
    }).success(function (result) {
        $scope.vendorListData = result.vendorListData;
        $scope.clearConsole();
    });
	//*****************/display customer list code dropdown*********************
    
    //*****************display customer list dropdown******************************
	$scope.paymentSourceList = [];
    $http({
        method: 'POST',
        url: BASE_URL +'payments/paid-from-list'
    }).success(function (result) {
        $scope.paymentSourceList = result.paymentSourceList;
        $scope.clearConsole();
    });
	//*****************/display customer list code dropdown*********************
	
	//**********Getting all Payment Made*********************************************
	$scope.funGetBranchWisePaymentMade = function(divisionId){
		$scope.searchPaymentMade={};
		$scope.loaderShow();
		$scope.divisionID = divisionId;		
		$http({
			url: BASE_URL + "payments/get-dw-payments-made/"+divisionId,
			method: "GET",
		}).success(function(result, status, headers, config){
			$scope.paymentsMadeList = result.paymentsMadeList;
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
	
	//**************multisearch start here**********************/
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	
	$scope.getMultiSearch = function()
    {   
		$scope.searchPaymentMade.search_division_id = $scope.divisionID;
		$scope.filterPaymentMade='';
		$scope.loaderShow();
		$http.post(BASE_URL + "payment/payment-made/get-payment-made-multisearch", {
            data: { formData:$scope.searchPaymentMade },
        }).success(function (result, status, headers, config){ 
			$scope.paymentsMadeList = result.paymentsMadeList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
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
	    $scope.searchPaymentMade={};
	    $scope.filterPaymentMade='';
		$scope.funGetBranchWisePaymentMade($scope.divisionID);
	}
	
	$scope.openMultisearch = function()
    { 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	}
	//**************multisearch end here**********************/
	
	//***************** Adding of Branch Wise PaymentMade ********************************
	$scope.funAddBranchWisePaymentMade = function(divisionId){
		
		if(!$scope.erpAddBWPaymentMadeForm.$valid)return;		
		if($scope.newAddBranchWisePaymentMadeFormflag)return;		
		$scope.newAddBranchWisePaymentMadeFormflag = true;		
		var formData = $(erpAddBWPaymentMadeForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "payments/add-dw-payments-made",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.newAddBranchWisePaymentMadeFormflag = false;
			if(data.error == 1){
				$scope.backButton();                
                $scope.funGetBranchWisePaymentMade(divisionId);
				$scope.generatePaymentMadeNumber();
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config){
			$scope.loaderHide();
			$scope.newAddBranchWisePaymentMadeFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Branch Wise PaymentMade ***************************
	
	//**************** editing of Payment Made *************************************
	$scope.funEditPaymentMade = function(payment_made_hdr_id,divisionId){
		if(payment_made_hdr_id){
			$scope.loaderShow();
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "payments/view-dw-payments-made/"+payment_made_hdr_id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
                    $scope.listPaymentFormBladeDiv  = true;
					$scope.addPaymentFormBladeDiv 	= true;			
					$scope.editPaymentFormBladeDiv  = false;
					$scope.editBWPaymentMade    = result.paymentsMade;
					$scope.editBWPaymentMade.division_id  = {
						selectedOption: { id: result.paymentsMade.division_id} 
					};
                    $scope.editBWPaymentMade.vendor_id  = {
						selectedOption: { id: result.paymentsMade.vendor_id} 
					};
                    $scope.editBWPaymentMade.payment_source_id  = {
						selectedOption: { id: result.paymentsMade.payment_source_id} 
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
	$scope.funUpdateBranchWisePaymentMade = function(divisionId){
		
		if(!$scope.erpEditBWPaymentMadeForm.$valid)return;		
		var formData = $(erpEditBWPaymentMadeForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "payments/update-dw-payments-made",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) { 
			if(result.error == 1){
                $scope.backButton();
				$scope.funGetBranchWisePaymentMade(divisionId);
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
	$scope.funDeletePaymentMade = function(payment_made_hdr_id,divisionId){
		if(payment_made_hdr_id){
			$scope.loaderShow();
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "payments/delete-dw-payments-made/"+payment_made_hdr_id,
			}).success(function(result, status, headers, config){				
				if(result.error == 1){
					$scope.funGetBranchWisePaymentMade(divisionId);
					$scope.generatePaymentMadeNumber();
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