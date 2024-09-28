app.controller('customDefinedFieldsController', function($scope,$timeout,$http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.customDefinedFieldsData 		 = '';
	$scope.erpEditCustomDefinedFieldsForm    = '';
	$scope.editCustomDefinedFieldsFormDiv 	= true;
       
	//sorting variables
	$scope.sortType     			= 'customeDefinedFields_code';    // set the default sort type
	$scope.sortReverse  			= false;             		// set the default sort order
	$scope.searchFish   			= '';    			 // set the default search/filter term
	$scope.IsVisiableSuccessMsg		= true;
	$scope.IsVisiableErrorMsg		= true;
	$scope.addCustomDefinedFieldsFormDiv   = false;
	$scope.editCustomDefinedFieldsFormDiv  = true;	
	$scope.successMessage 			= '';
	$scope.errorMessage   			= '';
	$scope.customDefinedFieldsData		= {};
	$scope.filterCustomDefinedFields 	= {};	
	$scope.defaultMsg  	    		= 'Oops ! Sorry for inconvience server not responding or may be some error.';
		
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
	}
	//**********/loader show**************************************************
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		//console.clear();
	}
	//*********/Clearing Console********************************************
	
	//**********Read/hide More description********************************************
	$scope.toggleDescription = function(type,id) {
		 angular.element('#'+type+'limitedText-'+id).toggle();
		 angular.element('#'+type+'fullText-'+id).toggle();
	};
	//*********/Read More description********************************************	
	
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
	
	//*********hideAlertMsg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//**********/hideAlertMsg********************************************
	
	//*********reset Form************************************************
	$scope.resetForm = function(){
		$scope.addCustomDefinedFields = {}; 
		$scope.erpAddCustomDefinedFieldsForm.$setUntouched();
		$scope.erpAddCustomDefinedFieldsForm.$setPristine();
		$scope.editCustomDefinedFields = {}; 
		$scope.erpEditCustomDefinedFieldsForm.$setUntouched();
		$scope.erpEditCustomDefinedFieldsForm.$setPristine();
	};
	//********/reset Form************************************************
	
	//*********navigate Form************************************************
	$scope.navigateForm = function(){
		if($scope.editCustomDefinedFieldsFormDiv){
			$scope.editCustomDefinedFieldsFormDiv = false;
			$scope.addCustomDefinedFieldsFormDiv  = true;
		}else{
			$scope.editCustomDefinedFieldsFormDiv = true;
			$scope.addCustomDefinedFieldsFormDiv  = false;
		}
	};
	//*********navigate Form************************************************
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id){
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
						$scope.deleteCustomDefinedFields(id);
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'					
				}
			}
		});
	};
	//**********/confirm box****************************************************
	
	//code used for sorting list order by fields 
	$scope.predicate = 'custom_defined_fields';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//************code used for display remark status in dropdown*****************
	$scope.labelStatusList = [
		{label_status: '1', label_status_name: 'Active'},
		{label_status: '2', label_status_name: 'Inactive'}
	];
	//************code used for display remark status in dropdown*****************
	
	
	//***************** Default SECTION START HERE *****************/	
	$scope.getCustomDefinedList = function(){
		$scope.loaderShow();
		
		$http({
			url: BASE_URL + "master/get-custom-defined-list",
			method: "POST",
			data: {}
		}).success(function (result, status, headers, config) {
			$scope.customFieldData = result.customFieldList;
			$scope.loaderHide(); 
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide(); 
			$scope.clearConsole();
		});
	};
	//***************** Default SECTION START HERE *****************/	
	
	//***************** Default SECTION START HERE *****************/	
	$scope.funAddCustomDefinedFields  = function(){
		
		if(!$scope.erpAddCustomDefinedFieldsForm.$valid)return;
		$scope.loaderShow();
		
		// post all form data to save
		$http.post(BASE_URL + "master/add-custom-defined-fields", {
		    data: {formData:$(erpAddCustomDefinedFieldsForm).serialize() },
		}).success(function (result, status, headers, config) {
			if(result.success){
				$scope.resetForm();
				$scope.getCustomDefinedList();	
				$scope.successMsgShow(result.success);
                $scope.loaderHide(); 
			}else{
				$scope.errorMsgShow(result.error);
			}
			$scope.loaderHide(); 
			$scope.clearConsole();
		}).error(function (result, status, headers, config){
			if(status == '500' || status == '404'){
					$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide(); 
			$scope.clearConsole();
		});
	};
	
	//edit an method and its data
	$scope.funEditCustomDefinedFields = function(id){
		if(id){
			$scope.searchDropdown='';
			$scope.resetForm();
			$scope.loaderShow(); 
			$http.post(BASE_URL + "master/edit-custom-defined-fields", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){
					$scope.addCustomDefinedFieldsFormDiv     = true;
					$scope.editCustomDefinedFieldsFormDiv    = false;	
					$scope.editCustomDefinedFields = data.responseData;
					
					$scope.editCustomDefinedFields.label_status = {
						selectedOption: { label_status: data.responseData.label_status} 
					};
				}else{
					$scope.errorMsgShow(data.error);
				}
				$scope.loaderHide(); 
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
			
		}
		
	};
	
	// update method and its data
	$scope.funUpdateCustomDefinedFields = function(){
		if(!$scope.erpEditCustomDefinedFieldsForm.$valid)return;  
		$scope.loaderShow();
		
		$http.post(BASE_URL + "master/update-custom-defined-fields", { 
			data: {formData:$(erpEditCustomDefinedFieldsForm).serialize() },
		}).success(function (data, status, headers, config) { 
			if(data.success){
				$scope.resetForm();
				$scope.navigateForm();
				$scope.getCustomDefinedList();			
				$scope.showAddForm();				
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShow(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}); 
	};
	
	//Delete method from the database
	$scope.deleteCustomDefinedFields = function(id){ 
		if(id){
			$scope.loaderShow(); 
			$http.post(BASE_URL + "master/delete-custom-defined-fields", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.success){
					$scope.getCustomDefinedList();	
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
		
	

	//show form for method edit and its data
	$scope.showEditForm = function(){
		 $scope.editDetectorFormDiv = false;
		 $scope.addDetectorFormDiv = true;
		 $scope.uploadEquipmentFormDiv = true;
	};
	
	//show form for add new  method 
	$scope.showAddForm = function(){
		 $scope.editCustomDefinedFieldsFormDiv = true;
		 $scope.addCustomDefinedFieldsFormDiv = false;
		 
	};
	
	//****************dropdown filter show/hide******************/
	$scope.searchFilterBtn  = false;
	$scope.searchFilterInput  = true;
	//Show filter
	$scope.showDropdownSearchFilter = function(){ 
		$scope.searchFilterBtn  = true;
		$scope.searchFilterInput  = false;
	};
	//hide filter
	$scope.hideDropdownSearchFilter = function(){ 
		$scope.searchFilterBtn  = false;
		$scope.searchFilterInput  = true;
	};
	//****************/dropdown filter show/hide******************/
	
	
	$scope.resetUploadForm = function(){
		angular.element('#detectorsFile').val('');
		angular.element('.browseFileInput').val('');
	}
	//***************************upload csv**********************************************	
	
	//***************** method SECTION END HERE *****************//

});
