app.controller('DivisionWiseItemsController', function($scope, $http, BASE_URL) {
	
	//define empty variables
	$scope.prodata 	        = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.defaultMsg  	= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     	= 'item_id';    // set the default sort type
	$scope.sortReverse  	= false;         // set the default sort order
	$scope.searchFish   	= '';    		 // set the default search/filter term
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
	}
	//**********/scroll to top function**********
	
	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 	 	 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	$scope.listBranchItemFormDiv 	 = false;
	$scope.listBranchItemFormDivAll  = true;
	$scope.listBranchItemFormDivPaginate    = true;
	$scope.listBranchItemFormDivAllPaginate = false;	
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
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	}
	//********** /confirm box****************************************************
	
	//**********navigate Form*********************************************
	$scope.navigateItemPage = function(division_id){
		if(!$scope.listBranchItemFormDiv){
			$scope.listBranchItemFormDiv 	        = true;
			$scope.listBranchItemFormDivAll         = false;
			$scope.listBranchItemFormDivAllPaginate = true;
			$scope.listBranchItemFormDivPaginate    = false;
		}else{
			$scope.listBranchItemFormDiv 			= false;
			$scope.listBranchItemFormDivAll 		= true;
			$scope.listBranchItemFormDivAllPaginate = false;
			$scope.listBranchItemFormDivPaginate    = true;
		}
		$scope.funBranchWiseItems(division_id);
		$scope.hideAlertMsg();
	}
	//**********/navigate Form*********************************************
	
	//**********backButton Form*********************************************
	$scope.backButton = function(){
		$scope.listItemFormDiv 	 = false;
		$scope.addItemFormDiv 	 = true;			
		$scope.editItemFormDiv 	 = true;
		$scope.viewItemFormDiv 	 = true;
		$scope.itemMaster = {};
		$scope.erpAddItemForm.$setUntouched();
		$scope.erpAddItemForm.$setPristine();
		$scope.itemMasterEdit = {};
		$scope.erpEditItemForm.$setUntouched();
		$scope.erpEditItemForm.$setPristine();
		$scope.hideAlertMsg();
	}
	//**********/backButton Form*********************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	}
	//*********/Clearing Console*********************************************
	
	//************code used for sorting list order by fields*****************
	$scope.predicate = 'item_code';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************
	
	//*******************Branch Wise Items*************************************
	$scope.funBranchWiseItems = function(division_id)
	{
		$scope.divisionID = division_id;
		
		$http({
			url: BASE_URL + "branch-items/get-division-items/"+division_id,		
		}).success(function (data, status, headers, config) {
			$scope.itemDataDivisionList = data.itemsDivisionList;
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//****************/Branch Wise Items*************************************
	
	//*******************Edit Branch Wise Items*************************************
    $scope.getBranchWiseItemTemplate = function (itdDivId){		
		if (itdDivId === $scope.selected){
			return 'edit';
		}else{
			return 'display';
		}
    };	
	$scope.funEditBranchItem = function (itemDataListObj){
        $scope.selected       = angular.copy(itemDataListObj.division_wise_item_id);
		$scope.editBranchWise = angular.copy(itemDataListObj);
    };	
	$scope.resetButton = function () {
        $scope.selected = {};
    };
	//****************/Edit Branch Wise Items*************************************
	
	//**************** Updating branch Wise item *********************************
	$scope.funSaveBranchItem = function(itdDivId,division_id){
		
		var formData = { msl : $scope.editBranchWise.msl,rol : $scope.editBranchWise.rol,id  : itdDivId };
		
		if(itdDivId){
			$http({
				url: BASE_URL + "branch-items/add-division-items",
				method: "POST",
				data: {formData : formData}
			}).success(function(result, status, headers, config){
				if(result.error == 1){				
					$scope.funBranchWiseItems(division_id);					
					$scope.resetButton();
					$scope.successMsgShow(result.message);
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
	}
	//*************** /Updating branch Wise item *********************************
	
	//**********Updating branch Wise All item*************************************
	$scope.funSaveAllBranchItem = function(divisionId){		
    	
		if(!$scope.erpAddBranchWiseAllItemForm.$valid)return;		
		if($scope.newErpBranchWiseItemFormflag)return;		
		$scope.newErpBranchWiseItemFormflag = true;
	
		$http({
			url: BASE_URL + "branch-items/add-division-all-items",
			method: "POST",
			data: {formData : $(erpAddBranchWiseAllItemForm).serialize()}
		}).success(function(data, status, headers, config) {
			$scope.newErpBranchWiseItemFormflag = false;
			if(data.error == 1){				
				$scope.funBranchWiseItems(divisionId);
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newErpBranchWiseItemFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
    }
	//**********/Updating branch Wise All item*************************
	
	//*****************display division dropdown start*****************
	$scope.divisionsCodeList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			$scope.divisionsCodeList = result;
			$scope.clearConsole();
	});
	//*****************/display division dropdown end*****************
			
});