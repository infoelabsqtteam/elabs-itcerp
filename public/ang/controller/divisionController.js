app.controller('divisionController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {
	
	// sorting variables
	$scope.sortType     = 'company_code'; 			// set the default sort type
	$scope.sortReverse  = false;  					// set the default sort order
	$scope.searchFish   = '';   					// set the default search/filter term
	$scope.successMessage=true;
	$scope.errorMessage=true;
	$scope.listDivisions=false;
	$scope.viewDivisionDiv=true;
	//define empty variables
	$scope.divsnData = '';
	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';	
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 		 = true;
	//**********/If DIV is hidden it will be visible and vice versa************
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
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
						$scope.deleteDivision(id);
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
	
	/*****************display company code dropdown start*****************/	
	$scope.companyCodeList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'/company/get-companycodes'
		}).success(function (result) { 
		if(result){
		    $scope.company_id = result.id;
			$scope.company_name = result.name;
			$scope.company_id1 = result.id;
			$scope.company_name1 = result.name;
		}
		$scope.clearConsole();
	});
	/*****************display company code dropdown end*****************/
	
	/*****************display country code dropdown start*****************/
	$scope.countryCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'allcountries'
	}).success(function (result) {
		if(result){
			$scope.countryCodeList = result;
		}
		$scope.clearConsole();
	});
	/*****************display Country code dropdown end*****************/
	
	//*****************state dropdown on country change**********************
	$scope.funGetStateOnCountryChange = function(country_id){
		if(country_id){
			$http({
				method: 'POST',
				url: BASE_URL +'get_states_list/'+country_id
			}).success(function (result) {
				$scope.statesCodeList = result.countryStatesList;
				$scope.clearConsole();
			});
		}
	};
	//****************/state dropdown on country change******************

	//*****************city dropdown on state change**********************
	$scope.funGetCityOnStateChange = function(state_id){
		if(state_id){
			$http({
				method: 'GET',
				url: BASE_URL +'get_cities_list/'+state_id
			}).success(function (result) {
				$scope.citiesCodesList = result.stateCitiesList;
				$scope.clearConsole();
			});
		}
	}
	//****************/city dropdown on state change********************
	
	//*****************generate unique code******************
	$scope.division_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'divisions/generate-division-number'
		}).success(function (result){
			$scope.division_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************
	
	/***************** division SECTION START HERE *****************/	
	//function is used to create new division 
    $scope.addDivision = function(){		
    	if(!$scope.divisionForm.$valid) return; 
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "division/add-division", {
            data: {formData:$(divisionForm).serialize() },
        }).success(function (resData, status, headers, config){	
			if(resData.success){ 	
				$scope.resetDivision();
				//reload the all companies
				$scope.getDivisions();				
				$http({
						method: 'POST',
						url: BASE_URL +'/company/get-companycodes'
					}).success(function (result) { 
					if(result){
						$scope.company_id = result.id;
						$scope.company_name = result.name;
						$scope.company_id1 = result.id;
						$scope.company_name1 = result.name;
					}
				});				
				$scope.successMsgShow(resData.success);
			}else{
				$scope.errorMsgShow(resData.error);
			}
			$scope.clearConsole();
			$scope.loaderHide();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMessage);
			}
			$scope.clearConsole();
			$scope.loaderHide();
        });
    }	
	/* reset division add form*/
	$scope.resetDivision=function(){
		$scope.division_name=null;
		$scope.division_address=null;
		$scope.division_city=null;
		$scope.division_PAN=null;
		$scope.division_VAT_no=null;
		$scope.divisionForm.$setUntouched();
		$scope.divisionForm.$setPristine();	
	};
	//code used for sorting list order by fields 
	$scope.predicate = 'company_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	// function is used to fetch the list of compines 	
	$scope.getDivisions = function()
    {
		$scope.generateDefaultCode();
		$http.post(BASE_URL + "division/get-divisions", {
			
        }).success(function (data, status, headers, config) { 
				if(data.divisionsList){
					$scope.divsnData = data.divisionsList; 
				}
			$scope.clearConsole();
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMessage);
			}
			$scope.clearConsole();
        });
    };
	
	/**************multisearch start here**********************/
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	
	$scope.getMultiSearch = function()
    {  
		$scope.filterDivisions='';
		$http.post(BASE_URL + "division/get-divisions-multisearch", {
            data: { formData:$scope.searchDivision },
        }).success(function (data, status, headers, config){ 
			if(data.divisionsList){
				$scope.divsnData = data.divisionsList; 
			}
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '400'){
				//$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
    };
	
	$scope.closeMultisearch = function()
    { 
	    $scope.multiSearchTr=true;
		$scope.multisearchBtn=false;
		$scope.refreshMultisearch();
	}
	
	$scope.refreshMultisearch = function()
    { 
	    $scope.searchDivision={};
		$scope.getDivisions();
	}
	
	$scope.openMultisearch = function()
    { 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	}
	/**************multisearch end here**********************/

	// Delete division from the database
	$scope.deleteDivision = function(id){   
		if(id){			 
			$scope.loaderShow();
			$http.post(BASE_URL + "division/delete-division", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (resData, status, headers, config) {  
				if(resData.success){ 
					$scope.getDivisions();
					$scope.successMsgShow(resData.success);
				}else{
					$scope.errorMsgShow(resData.error);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {  
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMessage);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});
		}		
    };
	
	// edit an division and its data
	$scope.editDivision = function(id){
		$scope.divi_name = ''; 			
		$scope.divi_code = ''; 			
		$scope.divi_id =''; 		
		if(id){
			$http.post(BASE_URL + "division/edit-division", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) { 
				if(data.returnData.responseData){
					var responseD=data.returnData.responseData;
					$scope.funGetStateOnCountryChange(responseD.division_country);
					$scope.funGetCityOnStateChange(responseD.division_state);
					$scope.showEditForm(); 	
					$scope.divi_name = responseD.division_name; 			
					$scope.divi_code = responseD.division_code;					
					$scope.divi_address = responseD.division_address; 			
					$scope.divi_pan = responseD.division_PAN;				
					$scope.divi_vat = responseD.division_VAT_no;									
					$scope.divi_id = btoa(responseD.division_id);					
					$timeout(function (){
						$scope.divisions = {
							selectedOption: {id: responseD.company_id, name: responseD.company_name} 
						};
						$scope.division_country1 = {
							selectedOption: {id: responseD.division_country} 
						};
						$scope.division_state1 = {
							selectedOption: {id: responseD.division_state} 
						};
						$scope.division_city1 = {
							selectedOption: {id: responseD.city_id} 
						};
					}, 500);
				}else{
					$scope.errorMsgShow(data.error);
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
				$scope.errorMsgShow($scope.defaultMessage);
				}
				$scope.clearConsole();
			});
		}else{
			
		}
    };	
	// update an division and its data
	$scope.updateDivision = function(){  	
    	if(!$scope.editDivisionForm.$valid) return; 
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "division/update-division", { 
            data: {formData:$("#edit_division_form").serialize() },
        }).success(function (resData, status, headers, config) {   	
			if(resData.success){ 
				$scope.getDivisions();
				$scope.hideEditForm();	
				$scope.successMsgShow(resData.success);
			}else{
				$scope.errorMsgShow(resData.error);
			}
			$scope.clearConsole();
			$scope.loaderHide();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMessage);
			}
			$scope.clearConsole();
			$scope.loaderHide();
        }); 
    };	
	$scope.hideDivisionEditForm=true;
	
	$scope.showAddForm = function()
    {		
		$scope.generateDefaultCode();
		$scope.hideAlertMsg();	
		$scope.hideDivisionEditForm=true;
		$scope.hideDivisionAddForm=false;
	};	
	$scope.showEditForm = function()
    {	
		$scope.hideAlertMsg();	
		$scope.hideDivisionEditForm=false;
		$scope.hideDivisionAddForm=true;
	};
	$scope.hideEditForm = function()
    {		
		$scope.hideAlertMsg();
		$scope.hideDivisionEditForm=true;
		$scope.hideDivisionAddForm=false;
	};
	$scope.hideAddForm = function()
    {	
		$scope.hideAlertMsg();				
		$scope.hideDivisionEditForm=false;
		$scope.hideDivisionAddForm=true;
	};
	
	$scope.hideViewForm = function(){	
		$scope.hideAlertMsg();
		$scope.listDivisions=false;
		$scope.viewDivisionDiv=true;
	};
	
	$scope.viewDivision = function(id){
		$scope.hideAlertMsg();		
		$scope.hideAlertMsg();
		$scope.listDivisions=true;
		$scope.viewDivisionDiv=false;
		if(id != ''){
			$http.post(BASE_URL + "division/edit-division", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) { 
				if(data.returnData.responseData){
					var responseD=data.returnData.responseData;
					$scope.view_division = responseD;				
				}else{
					$scope.errorMsgShow(data.error);
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
				$scope.errorMsgShow($scope.defaultMessage);
				}
			});
		}else{
			
		}
	};
	
});
