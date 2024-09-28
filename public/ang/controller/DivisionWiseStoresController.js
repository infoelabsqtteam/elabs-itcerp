app.controller('DivisionWiseStoresController', function($scope, $http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.prodata 	        = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.defaultMsg  		= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     	= 'store_id';    // set the default sort type
	$scope.sortReverse  	= false;         // set the default sort order
	$scope.searchFish   	= '';    		 // set the default search/filter term
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	};
	//**********/scroll to top function**********
	
	//**********loader show****************************************************
	$scope.loaderShow = function(){
        angular.element('#global_loader').fadeIn('slow');
	}
	//**********/loader show**************************************************
    
    //**********loader show***************************************************
	$scope.loaderHide = function(){
        angular.element('#global_loader').fadeOut('slow');
	}
	//**********/loader show**************************************************
	
	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 	 	 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	$scope.listStoreFormDiv 	 	 = false;
	$scope.addStoreFormDiv 	 	 	 = false;
	$scope.editStoreFormDiv 	 	 = true;
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
	
	//*****************generate unique code******************
	$scope.store_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'inventory/branch-stores/generate-store-number'
		}).success(function (result){
			$scope.store_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************
	
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
						$scope.funDeleteStore(id,divisionId);
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
	$scope.navigateStorePage = function(){
		if(!$scope.addStoreFormDiv){
			$scope.addStoreFormDiv 	= true;	
			$scope.editStoreFormDiv = false;
		}else{
			$scope.addStoreFormDiv 	= false;			
			$scope.editStoreFormDiv = true;
		}	
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************
	
	//**********backButton Form*********************************************
	$scope.backButton = function(){
		$scope.addBranchWiseStore = {};
		$scope.erpAddBranchWiseStoreForm.$setUntouched();
		$scope.erpAddBranchWiseStoreForm.$setPristine();
		$scope.editBranchWiseStore = {};
		$scope.erpEditBranchWiseStoreForm.$setUntouched();
		$scope.erpEditBranchWiseStoreForm.$setPristine();
		$scope.navigateStorePage();
	};
	//**********/backButton Form*********************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	};
	//*********/Clearing Console*********************************************
	
	//************code used for sorting list order by fields*****************
	$scope.predicate = 'store_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************
	
	/*****************display division dropdown start*****************/	
	$scope.divisionsCodeList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			$scope.divisionsCodeList = result;
			$scope.clearConsole();
	});
	/*****************display division dropdown end*****************/
	
	//**********Getting all Orders*************************************************
	$scope.funGetBranchWiseStores = function(divisionId){
		$scope.generateDefaultCode();
		$scope.divisionID = divisionId;		
		$http({
			url: BASE_URL + "branch-stores/get_division_stores_list/"+divisionId,
			method: "GET",
		}).success(function(result, status, headers, config){
			$scope.storeDataList = result.storeDataList;
			$scope.clearConsole();
		}).error(function(result, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
    };
	//**********/Getting all Orders*************************************************
	
	//***************** Adding of Branch Wise Store ********************************
	$scope.funAddBranchWiseStore = function(divisionId){
		
		if(!$scope.erpAddBranchWiseStoreForm.$valid)return;		
		if($scope.newAddBranchWiseStoreFormflag)return;		
		$scope.newAddBranchWiseStoreFormflag = true;		
		var formData = $(erpAddBranchWiseStoreForm).serialize();
		
		$http({
			url: BASE_URL + "branch-stores/add-division-store",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.newAddBranchWiseStoreFormflag = false;
			if(data.error == 1){				
				$scope.funGetBranchWiseStores(divisionId);			
				$scope.addBranchWiseStore = {};
				$scope.erpAddBranchWiseStoreForm.$setUntouched();
				$scope.erpAddBranchWiseStoreForm.$setPristine();
				$scope.editBranchWiseStore = {};
				$scope.erpEditBranchWiseStoreForm.$setUntouched();
				$scope.erpEditBranchWiseStoreForm.$setPristine();
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newAddBranchWiseStoreFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Branch Wise Store ***************************
	
	//**************** editing of item *************************************
	$scope.funEditStore = function(storeId,divisionId){
		if(storeId){
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "branch-stores/view-division-store/"+storeId,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.addBranchWiseStore = {};
					$scope.erpAddBranchWiseStoreForm.$setUntouched();
					$scope.erpAddBranchWiseStoreForm.$setPristine();
					$scope.editBranchWiseStore = {};
					$scope.erpEditBranchWiseStoreForm.$setUntouched();
					$scope.erpEditBranchWiseStoreForm.$setPristine();
					$scope.addStoreFormDiv 	= true;			
					$scope.editStoreFormDiv = false;
					$scope.editBranchWiseStore = result.storeDetailList;
					$scope.editBranchWiseStore.division_id  = {
						selectedOption: { division_id: result.storeDetailList.division_id} 
					};					
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
			}).error(function(data, status, headers, config) {
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************/editing of item *************************************
	
	//**************** Updating of item *************************************
	$scope.funUpdateBranchWiseStore = function(divisionId){
		
		if(!$scope.erpEditBranchWiseStoreForm.$valid)return;		
		var formData = $(erpEditBranchWiseStoreForm).serialize();
		
		$http({
			url: BASE_URL + "branch-stores/update-division-store",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) { 
			if(result.error == 1){
				$scope.funGetBranchWiseStores(divisionId);
				$scope.backButton();
				$scope.successMsgShow(result.message);					
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		}); 
	};
	//*************** /Updating of item *************************************
		
	//**************** Deleting of Branch Wise Store ***************************
	$scope.funDeleteStore = function(store_id,divisionId){	
		if(store_id){
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "branch-stores/delete-division-store/"+store_id,
			}).success(function(result, status, headers, config){				
				if(result.error == 1){
					$scope.funGetBranchWiseStores(divisionId);				
					$scope.successMsgShow(result.message);					
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
			}).error(function(data, status, headers, config){
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});	
		}
	};
	//************** /Deleting of Branch Wise Store *******************************
			
});