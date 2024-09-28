app.controller('companyTypeController', function($scope, $http, BASE_URL,$ngConfirm) {

	//define empty variables
	$scope.companyData = '';
	$scope.editCompanyFormDiv = true;

	//sorting variables
	$scope.sortType     = 'company_type_code';    // set the default sort type
	$scope.sortReverse  = false;           		  // set the default sort order
	$scope.searchFish   = '';    				 // set the default search/filter term
	
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
	
	/*****************display company code dropdown start*****************/	
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
	/*****************display company code dropdown end*****************/	
	
	//*****************generate unique code******************
	$scope.company_type_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'customers/generate-company-type-number'
		}).success(function (result){
			$scope.company_type_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************
	
	/***************** company SECTION START HERE *****************/	
    $scope.addCompany = function(){
    	if(!$scope.companyForm.$valid)
      	return;
	    $scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "customers/add-company-type", {
            data: {formData:$(companyForm).serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){
				$scope.resetForm();
				$scope.getCompanyTypes();				
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
	
	/* reset form*/
	$scope.resetForm = function(){
		$scope.company={};
		$scope.companyForm.$setUntouched();
		$scope.companyForm.$setPristine();		
	}
	//code used for sorting list order by fields 
	$scope.predicate = 'company_type_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list of compines	
	$scope.getCompanyTypes = function(){
		$scope.loaderShow();
		$scope.generateDefaultCode();
		$http.post(BASE_URL + "customers/get-company-type", {
        }).success(function (data, status, headers, config){
			$scope.loaderHide();
			$scope.companyData = data.companysList;
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
						$scope.deleteCompany(id);
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
	
	// Delete company type from the database
	$scope.deleteCompany = function(id){ 
		if(id){
				$scope.loaderShow(); 
				$http.post(BASE_URL + "customers/delete-company-type", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config){
					if(data.success){
						//reload the all employee
						$scope.getCompanyTypes();
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
	
	// edit an company and its data
	$scope.editCompany = function(id)
    {
		if(id){
			$scope.loaderShow();
			$http.post(BASE_URL + "customers/edit-company-type", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){ 
				    $scope.showEditForm();
					$scope.state_id = {
						selectedOption: { id: data.responseData.state_id} 
					};	
					$scope.company_type_id = btoa(data.responseData.company_type_id);	
					$scope.edit_company = data.responseData;	
					$('html, body').animate({ scrollTop: $("#editCompanyDiv").offset().top },500);
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
			});
		}
    };	
	//update company and its data
	$scope.updateCompany = function(){ 
    	if(!$scope.editCompanyForm.$valid)
      	return;  
		$scope.loaderShow(); 
        $http.post(BASE_URL + "customers/update-company-type", { 
            data: {formData:$(editCompanyForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				$scope.getCompanyTypes();				
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShow(data.error);
			}
			//$scope.showAddForm();
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

	// show form for company edit and its data
	$scope.showEditForm = function()
    {
		 $scope.editCompanyFormDiv = false;
		 $scope.addCompanyFormDiv = true;
	};
	// show form for add new  company 
	$scope.showAddForm = function()
    {
		 $scope.editCompanyFormDiv = true;
		 $scope.addCompanyFormDiv = false;
	};
	
	/***************** company SECTION END HERE *****************/
});
