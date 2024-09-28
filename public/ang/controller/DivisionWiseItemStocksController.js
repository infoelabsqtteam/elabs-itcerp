app.controller('DivisionWiseItemStocksController', function($scope, $http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.itemStockDataList = '';
	$scope.successMessage 	 = '';
	$scope.errorMessage   	 = '';
	$scope.selected          = '';
	$scope.defaultMsg  		 = 'Oops! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     	= 'division_wise_item_id';    // set the default sort type
	$scope.sortReverse  	= false;         // set the default sort order
	$scope.searchFish   	= '';    		 // set the default search/filter term
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	}
	//**********/scroll to top function**********
	
	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 	 	 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	$scope.listItemStockFormDiv 	 = false;
	$scope.addItemStockFormDiv 	 	 = false;
	$scope.editItemStockFormDiv 	 = true;
	//**********/If DIV is hidden it will be visible and vice versa************
	
	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 		= message;				
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 		= message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= false;
		$scope.moveToMsg();
	}
	//********** /errorMsgShow************************************************
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//********** /hide Alert Msg**********************************************
	
	//**********successMsgShowPopup**************************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		= message;				
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShowPopup************************************************
	
	//**********errorMsgShowPopup**************************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	}
	//********** /hideAlertMsgPopup************************************************
	
	//**********hideAlertMsgPopup*************
	$scope.hideAlertMsgPopup = function(){
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
	}
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
						$scope.funDeleteItemStock(id,divisionId);
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
	}
	//********** /confirm box****************************************************
	
	//**********navigate Form*********************************************
	$scope.navigateItemStockPage = function(){
		if(!$scope.addItemStockFormDiv){
			$scope.addItemStockFormDiv 	= true;	
			$scope.editItemStockFormDiv = false;
		}else{
			$scope.addItemStockFormDiv 	= false;			
			$scope.editItemStockFormDiv = true;
		}	
		$scope.hideAlertMsg();
	}
	//**********/navigate Form*********************************************
	
	//**********backButton Form*********************************************
	$scope.backButton = function(){
		$scope.addBranchWiseItemStock = {};
		$scope.erpAddBranchWiseItemStockForm.$setUntouched();
		$scope.erpAddBranchWiseItemStockForm.$setPristine();
		$scope.editBranchWiseItemStock = {};
		$scope.erpEditBranchWiseItemStockForm.$setUntouched();
		$scope.erpEditBranchWiseItemStockForm.$setPristine();
		$scope.navigateItemStockPage();
	}
	//**********/backButton Form*********************************************
	
	//**********resetButton Form*********************************************
	$scope.resetButton = function(){
		$scope.addBranchWiseItemStock = {};
		$scope.erpAddBranchWiseItemStockForm.$setUntouched();
		$scope.erpAddBranchWiseItemStockForm.$setPristine();
		$scope.editBranchWiseItemStock = {};
		$scope.erpEditBranchWiseItemStockForm.$setUntouched();
		$scope.erpEditBranchWiseItemStockForm.$setPristine();
	}
	//**********/resetButton Form*********************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	}
	//*********/Clearing Console*********************************************
	
	//************code used for sorting list order by fields*****************
	$scope.predicate = 'store_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************
	
	//*****************display division dropdown start************************
	$scope.divisionsCodeList = [];
	$scope.funGetDivisions = function()
    {
		$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			$scope.divisionsCodeList = result;
			$scope.clearConsole();
		});
	};
	//*****************display division dropdown end**************************
	
	//*****************display Store dropdown start************************
	$scope.storeDataList = [];
	$scope.funGetBranchWiseStores = function(divisionId)
    {		
		$http({
			url: BASE_URL + "branch-item-stock/get-division-stores/"+divisionId,
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
	//*****************display Store dropdown end**************************
	
	//*******************display Item dropdown start*************************************	
	$scope.funGetDivisionStockItems = function()
	{
		$http({
			url: BASE_URL + "items/get-items",
			method: "POST"			
		}).success(function (data, status, headers, config) {
			$scope.itemDataList = data.itemsList;
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newAddItemflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//****************/display Item dropdown End*************************************
	
	//**********Getting all Orders********************************************
	$scope.funGetBranchWiseItemStocks = function(divisionId)
    {
		$scope.divisionID = divisionId;
		
		$http({
			url: BASE_URL + "branch-item-stock/get-division-item-stock/"+divisionId,
			method: "GET",
		}).success(function(result, status, headers, config){
			$scope.itemStockDataList = result.itemStockDataList;			
		}).error(function(result, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
    };
	//**********/Getting all Orders*************************************************
	
	//***************** Adding of Branch Wise Item Stock ********************************
	$scope.funAddBranchWiseItemStock = function(divisionId){
		
		if(!$scope.erpAddBranchWiseItemStockForm.$valid)return;		
		if($scope.newAddBranchWiseItemStockFormflag)return;		
		$scope.newAddBranchWiseItemStockFormflag = true;		
		var formData = $(erpAddBranchWiseItemStockForm).serialize();
		
		$http({
			url: BASE_URL + "branch-item-stock/add-division-item-stock",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.newAddBranchWiseItemStockFormflag = false;
			if(data.error == 1){
				$scope.resetButton();
				$scope.funGetBranchWiseItemStocks(divisionId);	
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newAddBranchWiseItemStockFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Branch Wise Item Stock ***************************
	
	//**************** editing of item *************************************
	$scope.funEditItemStock = function(stockId,divisionId){
		if(stockId){
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "branch-item-stock/view-division-item-stock/"+stockId,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
					$scope.addItemStockFormDiv 	= true;			
					$scope.editItemStockFormDiv = false;
					$scope.editBranchWiseItemStock = result.stockDetailList;
					$scope.funGetBranchWiseStores(result.stockDetailList.division_id);
					$scope.editBranchWiseItemStock.store_id  = {
						selectedOption: { store_id: result.stockDetailList.store_id} 
					};
					$scope.editBranchWiseItemStock.item_id  = {
						selectedOption: { item_id: result.stockDetailList.item_id} 
					};
					$scope.editBranchWiseItemStock.divisions  = {
						selectedOption: { division_id: result.stockDetailList.division_id} 
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
	$scope.funUpdateBranchWiseItemStock = function(divisionId){
		
		if(!$scope.erpEditBranchWiseItemStockForm.$valid)return;		
		var formData = $(erpEditBranchWiseItemStockForm).serialize();
		
		$http({
			url: BASE_URL + "branch-item-stock/update-division-item-stock",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) { 
			if(result.error == 1){
				$scope.funGetBranchWiseItemStocks(divisionId);
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
		
	//**************** Deleting of Branch Wise Item Stock ***************************
	$scope.funDeleteItemStock = function(stock_id,divisionId){
		if(stock_id){
			$http({
				url: BASE_URL + "branch-item-stock/delete-division-item-stock/"+stock_id,
			}).success(function(result, status, headers, config){				
				if(result.error == 1){
					$scope.funGetBranchWiseItemStocks(divisionId);				
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
	//************** /Deleting of Branch Wise Item Stock *******************************
			
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