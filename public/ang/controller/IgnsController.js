app.controller('IgnsController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.prodata 	        = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.resultItems      = '';
	$scope.defaultDpoNo     = '';
	$scope.defaultMsg  	    = 'Oops! Sorry for inconvience server not responding or may be some error.';
	$scope.deleteMsg  	    = 'do you really want to delete this record?';
    
    //Initializing the scope of the form    
    $scope.erpAddBranchWiseIGNForm  = {};
    $scope.erpEditBranchWiseIGNForm = {};

	//sorting variables
	$scope.sortType     	= 'ign_hdr_id';    // set the default sort type
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
	$scope.isVisibleListIGNDiv 	     = false;
	$scope.isVisibleAddIGNDiv        = true;
    $scope.isVisibleEditIGNDiv       = true;
	$scope.isVisibleViewIGNDiv 	     = true;
	$scope.purchaseOrderNoList       = [];
	$scope.ign_inputs      			 = [];
	$scope.view_ign_inputs 			 = [];
	//**********/If DIV is hidden it will be visible and vice versa**********
	
	//**********successMsgShow***********************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 		= message;				
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShow**********************************************
	
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
	
	//**********successMsgShowPopup*******************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		 = message;				
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	 = true;
		$scope.moveToMsg();
	}
	//********** /successMsgShowPopup******************************************
	
	//**********errorMsgShowPopup**********************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	}
	//********** /hideAlertMsgPopup*********************************************
	
	//**********hideAlertMsgPopup*************
	$scope.hideAlertMsgPopup = function(){
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
	}
	//********** /hideAlertMsgPopup********************************************
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id,division_id){
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
						$scope.funDeleteIGNDetail(id,division_id);
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
	
	//**********confirm box****************************************************
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	}
	//********** /confirm box**************************************************
    
    //**********loader show****************************************************
	$scope.loaderShow = function(){
        angular.element('#global_loader').fadeIn();
	}
	//**********/loader show**************************************************
    
    //**********loader show***************************************************
	$scope.loaderHide = function(){
        angular.element('#global_loader').fadeOut();
	}
	//**********/loader show**************************************************
	
	//**********navigate Form*************************************************
	$scope.navigateItemPage = function(page){
		if(page == 1){ // Add
            $scope.isVisibleListIGNDiv = true;
			$scope.isVisibleAddIGNDiv  = false;
			$scope.isVisibleEditIGNDiv = true;
			$scope.isVisibleViewIGNDiv = true;
        }else if(page == 2){ //List
			$scope.isVisibleListIGNDiv = false;
			$scope.isVisibleAddIGNDiv  = true;
			$scope.isVisibleEditIGNDiv = true;
			$scope.isVisibleViewIGNDiv = true;
			$scope.hideAlertMsg();
		}else if(page == 3){ //View
			$scope.isVisibleListIGNDiv = true;
			$scope.isVisibleAddIGNDiv  = true;
			$scope.isVisibleEditIGNDiv = true;
			$scope.isVisibleViewIGNDiv = false;
		}else if(page == 4){ // Edit
			$scope.isVisibleListIGNDiv = true;
			$scope.isVisibleAddIGNDiv  = true;
			$scope.isVisibleEditIGNDiv = false;
			$scope.isVisibleViewIGNDiv = true;
		}
	}
	//**********/navigate Form*********************************************
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		//console.clear();
	}
	//*********/Clearing Console********************************************
	
	//************code used for sorting list order by fields****************
	$scope.predicate = 'ign_hdr_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate){
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
	//*****************/display division dropdown end*************************
	
	//*****************display Vendor dropdown********************************
	$scope.funGetDivisionWiseVendors = function(division_id)
	{
		$http({
				method: 'GET',
				url: BASE_URL +'vendors/list_all_vendors/'+division_id
			}).success(function (result) {
				$scope.vendorDataList = result.vendorList;
				$scope.clearConsole();
		});
	};
	//*****************/display Vendor dropdown********************************
    
    //*****************display Vendor dropdown********************************
	$scope.funGenerateIGNNumber = function()
	{
		$http({
				method: 'GET',
				url: BASE_URL +'igns/generate-ign-number'
			}).success(function (result) {
				$scope.defaultIGNNo = result.ignNumber;
				$scope.clearConsole();
		});
	};
	//*****************/display Vendor dropdown*******************************
    
    //*****************display Vendor dropdown********************************
	$scope.funGetDivisionWiseVendors = function(division_id)
	{
		$http({
				method: 'GET',
				url: BASE_URL +'vendors/list_all_vendors/'+division_id
			}).success(function (result) {
				$scope.vendorDataList = result.vendorList;
				$scope.clearConsole();
		});
	};
	//*****************/display Vendor dropdown**********************************
    
    //*****************display employeeList name in dropdown start***************
	$scope.executiveList = [];
	$http({
		url: BASE_URL +'igns/get_employee_list'
	}).success(function (result) {
		if(result.executiveList){
			$scope.executiveList = result.executiveList;
		}
		$scope.clearConsole();
	});	
	//*****************display employeeList name in dropdown end *************
	
	//*****************Add more button for Add*********************************
    $scope.ign_inputs.push($scope.nativeLanguage);
    $scope.addIGNRow = function(){ 
        var newLanguage = {
          level: $scope.level,
          name: $scope.name,
          remark: $scope.remark
        };
        $scope.ign_inputs.push(newLanguage);
    };
	
    $scope.deleteIGNRowAdd = function(rowNo) {  
		if(rowNo){		    
			$scope.ign_inputs.splice(rowNo, 1);	   	   
		}
    };
	
 	$scope.addMoreOnTab = function (event){
		if(event.keyCode=='9'){  
			$scope.addIGNRow();	  
		}  
	};
	//*****************/Add more button for Add*********************************
	
	//*****************Add more button for Edit*********************************
	$scope.edit_ign_inputs = [];
    $scope.edit_ign_inputs.push($scope.nativeLanguage);
    $scope.editIGNRow = function(){ 
        var newLanguage = {
          level: $scope.level,
          name: $scope.name,
          remark: $scope.remark
        };
        $scope.edit_ign_inputs.push(newLanguage);
		$scope.editBranchWiseIGN.total_bill_amount    = '';
		$scope.editBranchWiseIGN.total_pass_amount    = '';
		$scope.editBranchWiseIGN.total_landing_amount = '';
    };
	
    $scope.deleteIGNRowEdit = function(rowNo,ign_hdr_dtl_id) {  
		if(rowNo){
			if(ign_hdr_dtl_id){
				$scope.funDeleteIGNDtlData(ign_hdr_dtl_id,rowNo);				
			}else{
				$scope.edit_ign_inputs.splice(rowNo, 1);
				$scope.editBranchWiseIGN.total_bill_amount    = '';
				$scope.editBranchWiseIGN.total_pass_amount    = '';
				$scope.editBranchWiseIGN.total_landing_amount = '';
			}
		}
    };
	
 	$scope.editMoreOnTab = function (event){
		if(event.keyCode=='9'){  
			$scope.deleteEditIGNRow();	  
		}  
	};
	//*****************/Add more button for Edit*********************************
	
	//**********Deleting of IGN dtl detail***************************************
	$scope.funDeleteIGNDtlData = function(ign_hdr_dtl_id,rowNo){
		
		if(ign_hdr_dtl_id && confirm('do you really want to delete this record?')){
			$scope.loaderShow();
			$http({
				url: BASE_URL + "igns/delete-ign-dtl-row/"+ign_hdr_dtl_id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.edit_ign_inputs.splice(rowNo, 1);
					$scope.editBranchWiseIGN.total_bill_amount    = '';
					$scope.editBranchWiseIGN.total_pass_amount    = '';
					$scope.editBranchWiseIGN.total_landing_amount = '';
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(result, status, headers, config){
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
			});
		}
		return false;
	};
	//**********/Deleting of IGN dtl detail************************************
	
	//***************** Auto complete Section **********************************
	$scope.getMatches = function(searchText){
		var deferred = $q.defer();
		$timeout(function(){
			var items = $scope.getItems(searchText);
			deferred.resolve(items);
		},1000);
		return deferred.promise;
	};
	$scope.getItems = function(searchText){
		$http({
			method: 'GET',
			url: BASE_URL +'igns/get-items-list/'+searchText
		}).success(function (result) {	
			$scope.resultItems = result.itemsList;
			$scope.clearConsole();
		});
		return $scope.resultItems;		
	};
	//***************** /Auto complete Section ********************************
	
	//*****************PO No List on change item dropdown*********************
	$scope.funGetPONoOnChange = function(index,item_id,selectedPoHrdId){		
		if(item_id){
			var running=true;
			$scope.purchaseOrderNoList[index] = null;
			$http({
				url: BASE_URL +'igns/get-purchase-order-po-nos/'+item_id
			}).success(function (result){
				$scope.purchaseOrderNoList[index] = result.purchaseOrderNoList;				
				setInterval(function(){if(running){angular.element('#edit_po_hdr_id_'+index).val(selectedPoHrdId);}running=false;}, 500);			
				$scope.clearConsole();
			}); 
		}		
	}
	//****************/PO No List on change item dropdown********************
    
    //*******************Branch Wise IGN Detail**************************
	$scope.funGetDivisionWiseIgnList = function(division_id)
	{
		$scope.divisionID = division_id;
		$scope.loaderShow();		
		$http({
			url: BASE_URL + "igns/division-wise-ign-list/"+division_id,		
		}).success(function (result, status, headers, config) {
			$scope.IGNDataList = result.IGNDataList;
            $scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
            $scope.loaderHide();
		});
	};
	//****************/Branch Wise IGN Detail*********************
    
    //**********Adding of Branch wise IGN Detail******************
	$scope.funAddBranchWiseIGNDetail = function(division_id){   	
		if(!$scope.erpAddBranchWiseIGNForm.$valid)return;		
		if($scope.newerpAddBranchWiseIGNFormflag)return;		
		$scope.newerpAddBranchWiseIGNFormflag = true;
		$scope.loaderShow();
		$http({
			url: BASE_URL + "igns/save-ign-data",
			method: "POST",
			data: {formData : $(erpAddBranchWiseIGNForm).serialize()}
		}).success(function(data, status, headers, config) {
			$scope.newerpAddBranchWiseIGNFormflag = false;
			if(data.error == 1){
				$scope.addBranchWiseIGN = {};
				$scope.erpAddBranchWiseIGNForm.$setUntouched();
				$scope.erpAddBranchWiseIGNForm.$setPristine();
				$scope.funGetDivisionWiseIgnList(division_id);				
				$scope.navigateItemPage(2);
				$scope.ign_inputs = [];
				$scope.purchaseOrderNoList = [];
				$scope.addIGNRow();
				$scope.funGenerateIGNNumber();
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newerpAddBranchWiseIGNFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
    }
	//**********/Adding of Branch wise IGN Detail****************************
	
	//**********viewing of IGN Detail****************************************
	$scope.funViewIGNDetail = function(ign_hdr_id){		
		$scope.hideAlertMsg();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "igns/view-ign-detail/"+ign_hdr_id,
			method: "GET",
		}).success(function(result, status, headers, config){
			if(result.error == 1){
				$scope.navigateItemPage(3);						
				$scope.viewBranchWiseIGN   = result.ignHeaderData;
				$scope.view_ign_inputs     = result.ignHeaderDetailData;
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
	};
	//**********/viewing of IGN Detail***************************************
	
	//****************Editing Form of IGN Detail***********************************
	$scope.funOpenBranchWiseIGNDetailForm = function(ign_hdr_id)
	{
		if(ign_hdr_id){
			$scope.hideAlertMsg();
			$scope.loaderShow();
			$http({
				url: BASE_URL + "igns/view-ign-detail/"+ign_hdr_id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){					
					$scope.navigateItemPage(4);	
					
					//Setting header data in model
					$scope.editBranchWiseIGN = result.ignHeaderData;					
					$scope.funGetDivisionWiseVendors(result.ignHeaderData.division_id);
					
					$scope.editBranchWiseIGN.division_id  = {
						selectedOption: { division_id: result.ignHeaderData.division_id} 
					};
					$scope.editBranchWiseIGN.vendor_id  = {
						selectedOption: { vendor_id: result.ignHeaderData.vendor_id} 
					};
					$scope.editBranchWiseIGN.employee_id  = {
						selectedOption: { id: result.ignHeaderData.employee_id} 
					};								
					//Setting header detail data in model
					$scope.edit_ign_inputs = result.ignHeaderDetailData;
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config) {
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
			});
		}
	}
	//****************/Editing Form of IGN Detail*********************************
	
	//**************** Updating of DPO/PO ****************************************
	$scope.funUpdateBranchWiseIGNDetail = function(division_id,ign_hdr_id){
		
		if(!$scope.erpEditBranchWiseIGNForm.$valid)return;		
		var formData = $(erpEditBranchWiseIGNForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "igns/update-ign-data",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) { 
			if(result.error == 1){
				$scope.funGetDivisionWiseIgnList(division_id);
				$scope.funViewIGNDetail(ign_hdr_id);
				$scope.successMsgShow(result.message);					
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		}); 
	}
	//*************** /Updating of DPO/PO*****************************************
	
	//**********Deleting of IGN Detail********************************************
	$scope.funDeleteIGNDetail = function(ign_hdr_id,division_id){
		
		if(ign_hdr_id){
			$scope.loaderShow();
			$http({
				url: BASE_URL + "igns/delete-ign-record/"+ign_hdr_id,
			}).success(function(result, status, headers, config){				
				if(result.error == 1){					
					$scope.funGetDivisionWiseIgnList(division_id);
					$scope.successMsgShow(result.message);
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(result, status, headers, config){
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
			});
		}
		return false;
	};
	//**********/Deleting of IGN Detail************************************
	
	//**********backButton Form*********************************************
	$scope.resetForm = function(){
	}
	//**********/backButton Form********************************************
			
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
}).directive('selectItemFieldsAdd', function () {
    return {
        restrict: 'A',
        transclude: true,
        templateUrl: 'igns/add-ign-inputs',
        replace: true
    };
}).directive( 'elemReady', function( $parse ) {
   return {
       restrict: 'A',
       link: function( $scope, elem, attrs ) {    
          elem.ready(function(){
            $scope.$apply(function(){
                var func = $parse(attrs.elemReady);
                func($scope);
            })
          })
       }
    }
});