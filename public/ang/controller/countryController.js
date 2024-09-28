app.controller('countryController', function($scope, $http, BASE_URL,$ngConfirm) {

	//define empty variables
	$scope.countryData = '';
	$scope.editCountryFormDiv = false;
	$scope.addCountryFormDiv =  true;
	//sorting variables
	$scope.sortType     = 'country_code';    // set the default sort type
	$scope.sortReverse  = false;             // set the default sort order
	$scope.searchFish   = '';    			 // set the default search/filter term
	
	//set the default search/filter term
	$scope.IsVisiableSuccessMsg=true;
	$scope.IsVisiableErrorMsg=true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.addCountry 			= {};	
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
	
	//************code used for display status in dropdown*****************
	
	$scope.statusList = [
		{id: 1, name: 'Active'},
		{id: 0, name: 'Inactive'},
	];
	$scope.country_status  = {selectedOption: { id: $scope.statusList[0].id,name:$scope.statusList[0].name}};
	/*****************display country code dropdown start*****************/	
	$scope.countryCodeList = [];
		$http({
			method: 'POST',
			url: BASE_URL +'countries'
		}).success(function (result) { 
			if(result){ 
				$scope.countryCodeList = result;
				$scope.filterState = {country_id: { id: result[0]['id']}};
			}
			$scope.clearConsole();
		});
	/*****************display state code dropdown end*****************/	
	
	/***************** state SECTION START HERE *****************/	
    $scope.funAddCountryData = function(){
    	if(!$scope.addCountryForm.$valid)
      	return;
	    $scope.loaderShow(); 
		// post all form data to save
        $http.post(BASE_URL + "master/country/add-country", {
            data: {formData:$(addCountryForm).serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){
				$scope.resetCountry();	
				$scope.getCountriesList();				
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
	/*reset state*/
	$scope.resetCountry= function(){
		$scope.addCountry={};
		$scope.addCountryForm.$setUntouched();
		$scope.addCountryForm.$setPristine();	
	}
	//code used for sorting list order by fields 
	$scope.predicate = 'state_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list of compines	
	$scope.getCountriesList = function(){
		var http_para_string = {formData : $(erpFilterStateForm).serialize()};
		$http({
			url: BASE_URL + "master/get-countries",
			method: "POST",
			data: http_para_string
		}).success(function (data, status, headers, config) {
			$scope.countryData = data.countriesList;
			//console.log($scope.countryData);
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
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
						$scope.funDeleteCountry(id);
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
	
	// Delete state from the database
	$scope.funDeleteCountry = function(id)
    { 
		if(id){
				$scope.loaderShow(); 
				$http.post(BASE_URL + "master/country/delete-country", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {
					if(data.success){
						// reload the all employee
						$scope.getCountriesList();
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
	
	// edit an country and its data
	$scope.edit_country = {};
	$scope.editCountry = function(id) {
		$scope.loaderShow(); 

		if(id != ''){
			$http.post(BASE_URL + "master/country/edit-country", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){ 
				   $scope.showEditForm();
					$scope.country_id = btoa(data.responseData.country_id);	
					$scope.edit_country = data.responseData;
					$scope.edit_country_status = {selectedOption:{id:data.responseData.country_status}};
					$scope.loaderHide();
				}else{
					$scope.errorMsgShow(data.error);
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
				$scope.loaderHide();
			});
		}
    };	
	//update country and its data
	$scope.funUpdateCountryData = function(){ 
    	if(!$scope.editCountryForm.$valid)
      	return;  
		$scope.loaderShow(); 
        $http.post(BASE_URL + "master/country/update-country", { 
            data: {formData:$(editCountryForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				$scope.getCountriesList();		
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

	// show form for country edit and its data
	$scope.showEditForm = function()
    {
		 $scope.editCountryFormDiv = true;
		 $scope.addCountryFormDiv = false;
	};
	// show form for add new  country 
	$scope.showAddForm = function()
    {
		 $scope.editCountryFormDiv = false;
		 $scope.addCountryFormDiv = true;
	};
	
	/***************** country SECTION END HERE *****************/
});
