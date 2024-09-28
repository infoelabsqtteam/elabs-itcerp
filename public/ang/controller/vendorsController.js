app.controller('vendorsController', function($scope, $http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.orderData 			= '';
	$scope.order_id 			= ''; 			
	$scope.order_no 			= ''; 			
	$scope.order_date 			= '';
	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup 	= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.buttonText   		= 'Add New';

	//sorting variables
	$scope.sortType     		= 'vendor_id';    // set the default sort type
	$scope.sortReverse  		= false;         // set the default sort order
	$scope.searchFish   		= '';    		 // set the default search/filter term
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
	}
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
	$scope.IsVisiableErrorMsg 		 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	$scope.IsViewVendorList 		 = false;
	$scope.IsViewVendorForm          = true;
	$scope.IsViewVendorDetail        = true;
	$scope.IsViewVendorEdit          = true;
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
	
	//**********backButton****************************************************
	$scope.backButton = function(){				
		$scope.openNewVendorForm();		
	}
	//**********/backButton***************************************************
	
	//**********resetButton****************************************************
	$scope.resetButton = function(){
		$scope.vendor = {};
		$scope.erpCreateVendorForm.$setUntouched();
		$scope.erpCreateVendorForm.$setPristine();
		$scope.erpUpdateVendorForm.$setUntouched();
		$scope.erpUpdateVendorForm.$setPristine();
		$scope.hideAlertMsg();
	}
	//**********/resetButton***************************************************
	
	//*****************generate unique code******************
	$scope.vendor_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'inventory/vendors/generate-vendor-number'
		}).success(function (result){
			$scope.vendor_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id,divisionId){
		console.log(defaultDeleteMsg);
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
						$scope.funDeleteVendor(id,divisionId);
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
	//**********/confirm box****************************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	}
	//*********/Clearing Console*********************************************
		
	//**********confirm box******************************************************
	$scope.openNewVendorForm = function(){		
		if(!$scope.IsViewVendorList){
			$scope.IsViewVendorForm   = false;
			$scope.IsViewVendorList   = true;
			$scope.IsViewVendorDetail = true;
			$scope.IsViewVendorEdit   = true;
		}else{
			$scope.IsViewVendorForm   = true;
			$scope.IsViewVendorList   = false;
			$scope.IsViewVendorDetail = true;
			$scope.IsViewVendorEdit   = true;
		}
		$scope.resetButton();		
	}
	//**********/confirm box****************************************************
	
	//************code used for sorting list order by fields*********************
	$scope.predicate = 'vendor_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*********************
	
	/*****************display state list dropdown start*****************/	
	$scope.stateNameCodeList = [];
	$scope.funGetStateOnLoad = function(){		
		$scope.loaderShow(); 
		$http({
			method: 'POST',
			url: BASE_URL +'statesList'
		}).success(function (result){ 
			if(result){ 
				$scope.stateNameCodeList = result;
			}
			$scope.loaderHide(); 
		});
	}
	/*****************display state list dropdown end*****************/
	
	//*****************display City based on State dropdown**********************
	$scope.funGetCityOnStateChange = function(stateId){		
		$scope.loaderShow(); 
		if(angular.isDefined(stateId)){
			$http({
				method: 'GET',
				url: BASE_URL +'get_cities_list/'+stateId
			}).success(function (result) {
				$scope.citieNameCodeList = result.stateCitiesList;
				$scope.loaderHide(); 
			});
		}		
	}
	//*****************/display City based on State dropdown*********************
	
	//**********Adding of New vendor******************************
	$scope.funSaveNewVendor = function(divisionId){
		$scope.hideAlertMsg();    	
		if(!$scope.erpCreateVendorForm.$valid)return;		
		if($scope.newSaveNewVendorflag)return;		
		$scope.newSaveNewVendorflag = true;
		$scope.loaderShow();
		$http({
			url: BASE_URL + "vendors/create_new_vendor",
			method: "POST",
			data: {formData : $(erpCreateVendorForm).serialize()}
		}).success(function(result, status, headers, config) {
			$scope.newSaveNewVendorflag = false;
			if(result.error == 1){				
				$scope.vendor = {};
				$scope.erpCreateVendorForm.$setUntouched();
				$scope.erpCreateVendorForm.$setPristine();		
				$scope.IsViewVendorForm   = true;
				$scope.IsViewVendorList   = false;
				$scope.IsViewVendorDetail = true;
				$scope.IsViewVendorEdit   = true;				
				$scope.funGetDivWiseVendorsList(divisionId);
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();		
		}).error(function(data, status, headers, config) {
			$scope.newSaveNewVendorflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
    }
	//**********/Adding of New vendor******************************
	
	//**********Listing all vendors*************************************************
	$scope.funGetDivWiseVendorsList = function(divisionId)
    {
		$scope.divisionID = divisionId;
		$scope.generateDefaultCode();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "vendors/list_all_vendors/"+divisionId,
			method: "GET",
		}).success(function(data, status, headers, config){
			$scope.vendorListData = data.vendorList;			
			$scope.loaderHide();
		}).error(function(data, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
    };
	//**********/Listing all vendors***********************************************
	
	//**********deleting of vendor*************************************************
	$scope.funDeleteVendor = function(vendorId,divisionId){
		if(vendorId){
			$scope.loaderShow();
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "vendors/delete_vendor/"+vendorId,
				method: "GET",
			}).success(function(data, status, headers, config){
				if(data.error == 1){
					$scope.successMsgShow(data.message);
					$scope.funGetDivWiseVendorsList(divisionId);
				}else{
					$scope.errorMsgShow(data.message);
				}	
				$scope.loaderHide();				
			}).error(function(data, status, headers, config){
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
			});	
		}
	};
	//**********/deleting of vendor***********************************************
	
	//**********viewing of vendor*************************************************
	$scope.funViewVendor = function(vendorId){		
		$scope.hideAlertMsg();
		$scope.loaderShow();		
		$http({
			url: BASE_URL + "vendors/view_vendor/"+vendorId,
			method: "GET",
		}).success(function(result, status, headers, config){
			if(result.error == 1){				
				$scope.IsViewVendorForm    = true;
				$scope.IsViewVendorList    = true;
				$scope.IsViewVendorEdit    = true;
				$scope.IsViewVendorDetail  = false;				
				var vendorDetailObj   	   = result.vendorDetailList;
				$scope.vendorId            = vendorDetailObj.vendor_id;
				$scope.vendorCode          = vendorDetailObj.vendor_code;
				$scope.vendorName          = vendorDetailObj.vendor_name;				
				$scope.vendorEmail         = vendorDetailObj.vendor_email;
				$scope.vendorDivisionName  = vendorDetailObj.division_name;
				$scope.venderState         = vendorDetailObj.state_name;
				$scope.vendorCity          = vendorDetailObj.city_name;
				$scope.vendorMobile        = vendorDetailObj.vendor_mobile;
				$scope.vendorPincode       = vendorDetailObj.vendor_pincode;				
				$scope.vendorAddress       = vendorDetailObj.vendor_address;				
				$scope.vendorCustCode      = vendorDetailObj.vendor_cust_code;				
				$scope.vendorWebsite       = vendorDetailObj.vendor_website;			
				$scope.vendorVATNo         = vendorDetailObj.vat_no;
				$scope.vendorGSTNo         = vendorDetailObj.gst_no;				
				$scope.contactPersonName   = vendorDetailObj.contact_person_name;
				$scope.contactPersonEmail  = vendorDetailObj.contact_person_email;
				$scope.contactPersonMobile = vendorDetailObj.contact_person_mobile;
				$scope.creditDays          = vendorDetailObj.credit_days;				
			}else{
				$scope.errorMsgShow(result.message);
			}	
			$scope.loaderHide();
		}).error(function(data, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
	};
	//**********/viewing of vendor***********************************************
	
	//**********Editing of vendor*************************************************
	$scope.funEditVendor = function(vendorId){
		$scope.hideAlertMsg();
		$scope.loaderShow();		
		$http({
			url: BASE_URL + "vendors/view_vendor/"+vendorId,
			method: "GET",
		}).success(function(result, status, headers, config){
			if(result.error == 1){				
				$scope.IsViewVendorForm     = true;
				$scope.IsViewVendorList     = true;
				$scope.IsViewVendorEdit     = false;
				$scope.IsViewVendorDetail   = true;				
				$scope.vendor               = result.vendorDetailList;
				$scope.funGetCityOnStateChange(result.vendorDetailList.vendor_state);
				$scope.vendorId             = result.vendorDetailList.vendor_id;
				$scope.vendor.edit_division_id  = {
					selectedOption: { division_id: result.vendorDetailList.division_id} 
				};
				$scope.vendor.vendor_state  = {
					selectedOption: { id: result.vendorDetailList.vendor_state} 
				};
				$scope.vendor.vendor_city = {
					selectedOption: { id: result.vendorDetailList.vendor_city} 
				};				
			}else{
				$scope.errorMsgShow(result.message);
			}	
			$scope.loaderHide();
		}).error(function(data, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
	};
	//**********/Editing of vendor***********************************************
	
	//**********Adding of New vendor******************************
	$scope.funUpdateVendor = function(divisionId){
		$scope.loaderShow();
		$scope.hideAlertMsg();    	
		if(!$scope.erpUpdateVendorForm.$valid)return;		
		if($scope.newUpdateVendorflag)return;		
		$scope.newUpdateVendorflag = true;
		$http({
			url: BASE_URL + "vendors/edit_vendor",
			method: "POST",
			data: {formData : $(erpUpdateVendorForm).serialize()}
		}).success(function(result, status, headers, config){
			$scope.moveToMsg();			
			$scope.newUpdateVendorflag = false;
			if(result.error == 1){
				$scope.funGetDivWiseVendorsList(divisionId);				
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();			
		}).error(function(data, status, headers, config){
			$scope.newUpdateVendorflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
    }
	//**********/Adding of New vendor******************************
	
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
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
	}	
});