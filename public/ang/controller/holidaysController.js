app.controller('holidaysController', function($scope, $http, BASE_URL,$ngConfirm) {

	//define empty variables
	$scope.holidayData = '';
	$scope.editHolidayFormDiv = true;
	$scope.holiday = [];
	$scope.edit_holiday = [];

	//sorting variables
	$scope.sortType     = 'holiday_date';    // set the default sort type
	$scope.sortReverse  = true;             // set the default sort order
	$scope.searchFish   = '';    	       // set the default search/filter term
	
	//set the default search/filter term
	$scope.IsVisiableSuccessMsg 	= true;
	$scope.IsVisiableErrorMsg 	= true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	};
	//**********/scroll to top function**********
	
	//**********scroll to top function**********
	$scope.moveToContent=function(content_id){
		angular.element('html, body').animate({scrollTop: $("#"+content_id).offset().top},500);
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
		if(APPLICATION_MODE)console.clear();
	};
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
	
	/*reset holiday*/
	$scope.resetHoliday= function(){
		$scope.holiday={};
		$scope.holidayForm.$setUntouched();
		$scope.holidayForm.$setPristine();	
	}
	//code used for sorting list order by fields 
	$scope.predicate = 'holiday_date';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//*********hideAlertMsg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//**********/hideAlertMsg********************************************
	
	//*****************display Status dropdown start*************************
	$scope.statusList = [				  
		{id: '1', name: 'Active'},
		{id: '0', name: 'Deactive'}
	];
	$scope.holiday.holiday_status = {id: '1'};
	//*****************display Status dropdown start*************************
	
	//*****************display division dropdown start*************************
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end**************************
	
	/***************** holiday SECTION START HERE *****************/	
	$scope.addHoliday = function(){
		
		if(!$scope.holidayForm.$valid)return;
		$scope.loaderShow(); 
		
		// post all form data to save
		$http.post(BASE_URL + "holidays/add-holiday", {
		    data: {formData:$(holidayForm).serialize() },
		}).success(function (data, status, headers, config) {
			if(data.error == 1){
				$scope.holiday = {};
				$scope.holidayForm.$setUntouched();
				$scope.holidayForm.$setPristine();	
				$scope.getHolidays();
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
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
	
	//function is used to fetch the list of holidays	
	$scope.getHolidays = function() {
		$http.post(BASE_URL + "holidays/get-holidays", {
		}).success(function (data, status, headers, config) {
			$scope.holidayData = data.holidaysList;
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};
	
	// edit an holiday and its data
	$scope.editHoliday = function(id){
		if(id){
			$http.post(BASE_URL + "holidays/edit-holiday", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (result, status, headers, config) {
				if(result.responseData){
					$scope.showEditForm();
					$scope.edit_holiday = result.responseData;
					$scope.holiday_id = result.responseData.holiday_id;
					$scope.edit_holiday.holiday_status = {id: result.responseData.holiday_status};
					$scope.edit_holiday.division_id = {id: result.responseData.division_id};
					$scope.moveToContent('editHolidayDiv');
				}else{
					$scope.errorMsgShow(result.error);
				}
				$scope.clearConsole();
			}).error(function (result, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}
	};
	
	//update holiday and its data
	$scope.updateHoliday = function(){
		
		if(!$scope.editHolidayForm.$valid)return;  
		$scope.loaderShow(); 
		
		$http.post(BASE_URL + "holidays/update-holiday", { 
		    data: {formData:$(editHolidayForm).serialize() },
		}).success(function (data, status, headers, config) { 
			if(data.success){ 
				$scope.getHolidays();		
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

	//show form for holiday edit and its data
	$scope.showEditForm = function(){
		$scope.editHolidayFormDiv = false;
		$scope.addHolidayFormDiv = true;
	};
	
	// show form for add new  holiday 
	$scope.showAddForm = function(){
		$scope.editHolidayFormDiv = true;
		$scope.addHolidayFormDiv = false;
	};
	
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
						$scope.deleteState(id);
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
	
	// Delete holiday from the database
	$scope.deleteState = function(id){ 
		if(id){
			$scope.loaderShow(); 
			$http.post(BASE_URL + "holidays/delete-holiday", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.success){
					// reload the all holidays
					$scope.getHolidays();
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
	/***************** holiday SECTION END HERE *****************/
});