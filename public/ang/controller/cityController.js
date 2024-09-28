app.controller('cityController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {

	//define empty variables
	$scope.cityData = '';
	$scope.editCityFormDiv = true;
	$scope.edit_city = [];

	//sorting variables
	$scope.sortType     = 'city_code';    // set the default sort type
	$scope.sortReverse  = false;             // set the default sort order
	$scope.searchFish   = '';    			 // set the default search/filter term
	
	//set the default search/filter term
	$scope.IsVisiableSuccessMsg=true;
	$scope.IsVisiableErrorMsg=true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
		
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
	
	/*****************display state code dropdown start*****************/	
	$scope.countryCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'countries'
	}).success(function (result) { 
		if(result){ 
			$scope.countryCodeList = result;
			$scope.funFilterStateOnCountryChange($scope.countryCodeList[0]['id']);
			$scope.filterCity = {country_id: { id: $scope.countryCodeList[0]['id']}};
		}
		$scope.clearConsole();
	});
	/*****************display state code dropdown end*****************/
	
	//*****************state dropdown on country change**********************
	$scope.funGetStateOnCountryChange = function(country_id){
		if(country_id){
			$http({
				method: 'POST',
				url: BASE_URL +'get_states_list/'+country_id
			}).success(function (result) {
				$scope.statesList = result.countryStatesList;
				$scope.clearConsole();
			});
		}
	};
	//****************/state dropdown on country change******************
	
	//***************** city SECTION START HERE *****************/	
	$scope.addCity = function(){
		
		if(!$scope.cityForm.$valid)return;
		$scope.loaderShow(); 
		
		// post all form data to save
		$http.post(BASE_URL + "cities/add-city", {
			data: {formData:$(cityForm).serialize() },
		}).success(function (data, status, headers, config) {
			if(data.success){
				$scope.resetCity();
				$scope.getCities();				
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
	
	/*reset city*/	
	$scope.resetCity=function(){
		$scope.city={};
		$scope.cityForm.$setUntouched();
		$scope.cityForm.$setPristine();	 	
	};
	
	//code used for sorting list order by fields 
	$scope.predicate = 'city_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list cities of states	
	$scope.getCities = function(id){
		
		$scope.loaderShow();		
		$scope.StateId = id;		
		var http_para_string = {formData : $(erpFilterCityListingForm).serialize()};

		$http({
			url: BASE_URL + "cities/get-city",
			method: "POST",
			data: http_para_string,
		}).success(function (data, status, headers, config) {
			$scope.cityData = data.citysList;
			$scope.clearConsole();
			$scope.loaderHide(); 
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};
	//****************/city dropdown on state change********************
	
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
						$scope.deleteCity(id);
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
	
	//Delete city from the database
	$scope.deleteCity = function(id){ 
		if(id){
			$scope.loaderShow(); 
			$http.post(BASE_URL + "cities/delete-city", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config){
				if(data.success){
					//reload the all employee
					$scope.getCities();
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
	
	// edit an city and its data
	$scope.editCity = function(id){
		
		if(id){
			$scope.loaderShow(); 
			$http.post(BASE_URL + "cities/edit-city", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){ 
					$scope.showEditForm();
					$scope.funGetStateOnCountryChange(data.responseData.country_id);
					$timeout(function (){
						$scope.edit_city.country_id = {
							selectedOption: { id: data.responseData.country_id} 
						};						
						$scope.edit_city.state_id = {
							selectedOption: { id: data.responseData.state_id} 
						};
					}, 500);
					$scope.city_id = btoa(data.responseData.city_id);	
					$scope.edit_city = data.responseData;	
					$('html, body').animate({ scrollTop: $("#editCityDiv").offset().top },500);	
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
	
	//update city and its data
	$scope.updateCity = function(){ 
    	if(!$scope.editCityForm.$valid)
      	return;  
		$scope.loaderShow(); 
        $http.post(BASE_URL + "cities/update-city", { 
            data: {formData:$(editCityForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				$scope.getCities();		
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

	// show form for city edit and its data
	$scope.showEditForm = function()
	{
		 $scope.editCityFormDiv = false;
		 $scope.addCityFormDiv = true;
	};
	// show form for add new  city 
	$scope.showAddForm = function()
	{
		 $scope.editCityFormDiv = true;
		 $scope.addCityFormDiv = false;
	};
	
	//*****************state dropdown on country change**********************
	$scope.filterStatesList = [];
	$scope.funFilterStateOnCountryChange = function(country_id){
		if(country_id){
			$http({
				method: 'POST',
				url: BASE_URL +'get_states_list/'+country_id
			}).success(function (result) {
				$scope.filterStatesList = result.countryStatesList;
				$scope.clearConsole();
			});
		}
	};
	//****************/state dropdown on country change******************
	/***************** city SECTION END HERE *****************/
});
