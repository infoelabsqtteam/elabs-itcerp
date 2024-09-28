app.controller('stateController', function($scope, $http, BASE_URL,$ngConfirm) {

	//define empty variables
	$scope.stateData = '';
	$scope.editStateFormDiv = true;

	//sorting variables
	$scope.sortType     = 'state_code';    // set the default sort type
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
	
	//************code used for display status in dropdown*****************
	$scope.state ={};
	$scope.statusList = [
		{id: 1, name: 'Active'},
		{id: 0, name: 'Inactive'},
	];
	$scope.state.state_status  = {selectedOption: { id: $scope.statusList[0].id,name:$scope.statusList[0].name}};
	/*****************display state code dropdown start*****************/	
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
    $scope.addState = function(){
    	if(!$scope.stateForm.$valid)
      	return;
	    $scope.loaderShow(); 
		// post all form data to save
        $http.post(BASE_URL + "states/add-state", {
            data: {formData:$(stateForm).serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){
				$scope.resetState();	
				$scope.getStates();				
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
	$scope.resetState= function(){
		$scope.state={};
		$scope.stateForm.$setUntouched();
		$scope.stateForm.$setPristine();	
	}
	//code used for sorting list order by fields 
	$scope.predicate = 'state_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list of compines	
	$scope.getStates = function(){
		var http_para_string = {formData : $(erpFilterStateForm).serialize()};
		$http({
			url: BASE_URL + "states/get-states",
			method: "POST",
			data: http_para_string
		}).success(function (data, status, headers, config) {
			$scope.stateData = data.statesList;
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
	
	// Delete state from the database
	$scope.deleteState = function(id)
    { 
		if(id){
				$scope.loaderShow(); 
				$http.post(BASE_URL + "states/delete-state", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {
					if(data.success){
						// reload the all employee
						$scope.getStates();
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
	
	// edit an state and its data
	$scope.edit_state = {};
	$scope.editState = function(id)
    {
		if(id != ''){
			$http.post(BASE_URL + "states/edit-state", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){

				    $scope.showEditForm();
					$scope.country_id = {
						selectedOption: { id: data.responseData.country_id} 
					};
					$scope.state_status = {
						selectedOption: { id: data.responseData.state_status} 
					};
					
					$scope.state_id = btoa(data.responseData.state_id);	
					$scope.edit_state = data.responseData;	
					$('html, body').animate({ scrollTop: $("#editStateDiv").offset().top },500);	
				}else{
					$scope.errorMsgShow(data.error);
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}
    };	
	//update state and its data
	$scope.updateState = function(){ 
    	if(!$scope.editStateForm.$valid)
      	return;  
		$scope.loaderShow(); 
        $http.post(BASE_URL + "states/update-state", { 
            data: {formData:$(editStateForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				$scope.getStates();		
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

	// show form for state edit and its data
	$scope.showEditForm = function()
    {
		 $scope.editStateFormDiv = false;
		 $scope.addStateFormDiv = true;
	};
	// show form for add new  state 
	$scope.showAddForm = function()
    {
		 $scope.editStateFormDiv = true;
		 $scope.addStateFormDiv = false;
	};
	
	/***************** state SECTION END HERE *****************/
});
