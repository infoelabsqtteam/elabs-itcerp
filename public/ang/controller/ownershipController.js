app.controller('ownershipController', function($scope, $http, BASE_URL,$ngConfirm) {

	//define empty variables
	$scope.ownershipData = '';
	$scope.editOwnershipFormDiv = true;

	//sorting variables
	$scope.sortType     = 'ownership_code';    // set the default sort type
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
	
	/*****************display ownership code dropdown start*****************/	
	$scope.statesList = [];
		$http({
			method: 'POST',
			url: BASE_URL +'statesList'
		}).success(function (result) { 
			if(result){ 
				$scope.statesList = result;
			}
			$scope.clearConsole();
		});
	/*****************display ownership code dropdown end*****************/	
	
	//*****************generate unique code******************
	$scope.ownership_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'customers/generate-ownership-type-number'
		}).success(function (result){
			$scope.ownership_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************
	
	/***************** ownership SECTION START HERE *****************/	
    $scope.addOwnership = function(){
    	if(!$scope.ownershipForm.$valid)
      	return;
	    $scope.loaderShow(); 
		// post all form data to save
        $http.post(BASE_URL + "customers/add-ownership-type", {
            data: {formData:$(ownershipForm).serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){
				$scope.resetForm ();
				$scope.getOwnerships();				
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
    }
	/*reset form*/
    $scope.resetForm = function(){
		$scope.ownership={};
		$scope.ownershipForm.$setUntouched();
		$scope.ownershipForm.$setPristine();	
	}	
	//code used for sorting list order by fields 
	$scope.predicate = 'ownership_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list of compines	
	$scope.getOwnerships = function()
    {
		$scope.generateDefaultCode();
		$http.post(BASE_URL + "customers/get-ownership", {
        }).success(function (data, status, headers, config) {
			$scope.ownershipData = data.ownershipsList;
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
						$scope.deleteOwnership(id);
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
	
	// Delete ownership from the database
	$scope.deleteOwnership = function(id)
    { 
		if(id){
				$scope.loaderShow(); 
				$http.post(BASE_URL + "customers/delete-ownership-type", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config){
					if(data.success){
						//reload the all employee
						$scope.getOwnerships();
						$scope.successMsgShow(data.success);
					}else{
						$scope.errorMsgShow(data.success);
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
	
	// edit an ownership and its data
	$scope.editOwnership = function(id)
    {
		if(id != ''){
			$http.post(BASE_URL + "customers/edit-ownership-type", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){ 
				    $scope.showEditForm();
					$scope.state_id = {
						selectedOption: { id: data.responseData.state_id} 
					};	
					$scope.ownership_id = btoa(data.responseData.ownership_id);	
					$scope.edit_ownership = data.responseData;	
					$('html, body').animate({ scrollTop: $("#editOwnershipDiv").offset().top },500);	
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
	//update ownership and its data
	$scope.updateOwnership = function(){ 
    	if(!$scope.editOwnershipForm.$valid)
      	return;  
		$scope.loaderShow(); 
        $http.post(BASE_URL + "customers/update-ownership-type", { 
            data: {formData:$(editOwnershipForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				$scope.getOwnerships();	
				//$scope.showAddForm();			
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

	// show form for ownership edit and its data
	$scope.showEditForm = function()
    {
		 $scope.editOwnershipFormDiv = false;
		 $scope.addOwnershipFormDiv = true;
	};
	// show form for add new  ownership 
	$scope.showAddForm = function()
    {
		 $scope.editOwnershipFormDiv = true;
		 $scope.addOwnershipFormDiv = false;
	};
	
	/***************** ownership SECTION END HERE *****************/
});
