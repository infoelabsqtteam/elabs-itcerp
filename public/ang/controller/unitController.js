app.controller('unitController', function($scope, $http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.unitdata 	= '';
	$scope.unit_code 	= ''; 			
	$scope.unit_desc 	= ''; 			
	$scope.unit_city 	= '';				
	$scope.unit_id 		= '';
	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup 	= '';	
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     = 'unit_code';  	  // set the default sort type
	$scope.sortReverse  = false;             // set the default sort order
	$scope.searchFish   = '';    			 // set the default search/filter term
		
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
		//console.clear();
	};
	//*********/Clearing Console********************************************
	
	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 		 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
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
	}
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
	$scope.unit_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'inventory/units/generate-unit-number'
		}).success(function (result){
			$scope.unit_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id){
		$ngConfirm({
			title     : false,
			content   : defaultDeleteMsg, //Defined in message.js and included in head
			animation : 'right',
			closeIcon : true,
			closeIconClass    : 'fa fa-close',
			backgroundDismiss : false,
			theme: 'bootstrap',
			columnClass : 'col-sm-5 col-md-offset-3',
			buttons: {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.deleteUnit(id);
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
	
	/***************** unit SECTION START HERE *****************/	
	//function is used to call the 
    $scope.addUnit = function(){
    	if(!$scope.unitForm.$valid)
      	return;
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "units/add-unit", {
            data: {formData:$(unitForm).serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){ 
				//reload the all units
				$scope.resetForm();
				$scope.getUnits();				
				//$scope.hideAddForm();				
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShowPopup(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
        });
    };	
		
	//code used for sorting list order by fields 
	$scope.predicate = 'unit_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	/* reset form*/
	$scope.resetForm = function(){
		$scope.unit={};
		$scope.unitForm.$setUntouched();
		$scope.unitForm.$setPristine();	
	}
	
	//function is used to fetch the list of units	
	$scope.getUnits = function(){ 
		$scope.generateDefaultCode();
		$http.post(BASE_URL + "units/get-units", {
            //status: status, prod_id:prodID, cat_id:catID
        }).success(function (data, status, headers, config) {
			$scope.unitdata = data.unitsList;
			$scope.clearConsole();
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
        });
    };	
	
	// Delete unit from the database
	$scope.deleteUnit = function(id){ 
		if(id){			
			$scope.loaderShow();
			$http.post(BASE_URL + "units/delete-unit", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.success){
					// reload the all employee
					$scope.getUnits();
					$scope.successMsgShow(data.success);
				}else{
					$scope.errorMsgShow(data.error);	
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});
		}		
    };
	
	// edit an unit and its data
	$scope.editUnit = function(id){
		$scope.unit_code_val =''; 			
		$scope.unit_desc_val ='';  			
		$scope.unit_name_val =''; 
		if(id != ''){
			$http.post(BASE_URL + "units/edit-unit", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){ 
				    $scope.showEditForm();
					$scope.unit_code_val = data.responseData.unit_code; 			
					$scope.unit_desc_val = data.responseData.unit_desc; 			
					$scope.unit_name_val = data.responseData.unit_name;				
					$scope.unit_name_old = data.responseData.unit_name;				
					$scope.unit_id_val = btoa(data.responseData.unit_id);	
				}else{
					$scope.errorMsgShowPopup(data.error);
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}
    };
	
	// update unit and its data
	$scope.updateUnit = function(){ 
    	if(!$scope.editUnitForm.$valid)return; 
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "units/update-unit", { 
            data: {formData:$("#edit_unit_form").serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				//reload the all units
				$scope.getUnits();				
				//$scope.hideEditForm();				
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShowPopup(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }); 
    };
	$scope.hideUnitAddForm=false;
	$scope.hideUnitEditForm=true;
	$scope.showEditForm = function(){
		$scope.hideAlertMsgPopup();		
		$scope.hideAlertMsg();
		$scope.hideUnitAddForm=true;
		$scope.hideUnitEditForm=false;
	};
	$scope.hideEditForm = function(){
		$scope.hideAlertMsgPopup();		
		$scope.hideAlertMsg();
		$scope.hideUnitAddForm=false;
		$scope.hideUnitEditForm=true;
	};
	$scope.hideAddForm = function(){
		$scope.hideAlertMsgPopup();		
		$scope.hideAlertMsg();
		$scope.hideUnitAddForm=true;
		$scope.hideUnitEditForm=false;
	};
	/***************** unit SECTION END HERE *****************/
});
