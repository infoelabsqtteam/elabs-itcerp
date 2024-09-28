app.controller('companyController', function($scope, $http, BASE_URL,$ngConfirm) {
	
	//define empty variables
	$scope.compdata 	= '';
	$scope.comp_code 	= ''; 			
	$scope.comp_address = ''; 			
	$scope.comp_city 	= '';				
	$scope.comp_id 		= '';
	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     = 'company_code';    // set the default sort type
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
	
	//*********hideAlertMsg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	};
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
	//**********/confirm box****************************************************
	
	//*****************display city code dropdown start*****************	
	$scope.citiesCodeList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'cities'
		}).success(function (result) {
		  if(result){
			  $scope.citiesCodeList = result;
		  }	
		  $scope.clearConsole();				
	});
	//*****************display city code dropdown end*****************
	
	//***************** company SECTION START HERE *****************	
    $scope.addCompany = function(){		
    	if(!$scope.companyForm.$valid) return;
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "company/add-company", {
            data: {formData:$(companyForm).serialize() },
        }).success(function (resData, status, headers, config) {
			if(resData.success){ 
				$scope.company_name    = null; 			
				$scope.company_address = null; 			
				$scope.company_code    = null; 
				$scope.companyForm.$setUntouched();
				$scope.companyForm.$setPristine();	
				$scope.getCompanies();
				$scope.hideAddForm();
				$scope.successMsgShow(resData.success);
			}else{
				$scope.errorMsgShow(resData.error);
			}
			$scope.clearConsole();
			$scope.loaderHide();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderHide();
        });
    };
	
	//code used for sorting list order by fields 
	$scope.predicate = 'company_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list of compines	
	$scope.getCompanies = function(){ 
		$scope.loaderShow();
		$http.post(BASE_URL + "company/get-companies", {
         //status: status, prod_id:prodID, cat_id:catID
        }).success(function (data, status, headers, config){
			if(data.companiesList){
				$scope.compdata = data.companiesList;
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
        });
    };	
	
	// Delete company from the database
	$scope.deleteCompany = function(id){ 
		if(id){
			$scope.loaderShow();
			$http.post(BASE_URL + "company/delete-company", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (resData, status, headers, config) { 		
				if(resData.success){
					$scope.getCompanies();
					$scope.successMsgShow(resData.success);
				}else{
					$scope.errorMsgShow(resData.error);	
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (resData, status, headers, config) {   
				$scope.errorMsgShow($scope.defaultMsg);
				$scope.clearConsole();
			});
		}
    };
	
	// edit an company and its data
	$scope.editCompany = function(id){
		$scope.company_id1 = null; 			
		$scope.company_address1= null; 			
		$scope.company_code1 = null; 		
		if(id){
			$scope.loaderShow();
			$http.post(BASE_URL + "company/edit-company", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {	 
					if(data.returnData.responseData){      		
						var resData=data.returnData.responseData;   
						$scope.cityData = {
							selectedOption: {id: resData.company_city, name: resData.company_city} 
						};
						$scope.company_code1 = resData.company_code; 	 
						$scope.company_name1 = resData.company_name; 	
						$scope.company_address1 = resData.company_address;	
						$scope.company_id1 = btoa(resData.company_id);
						$scope.showEditForm();										
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
	
	// update company and its data
	$scope.updateCompany = function(){
		$scope.loaderShow();
    	if(!$scope.editCompanyForm.$valid)
      	return;  
		// post all form data to save
        $http.post(BASE_URL + "company/update-company", { 
            data: {formData:$(editCompanyForm).serialize() },
        }).success(function (resData, status, headers, config) { 	
			if(resData.success){  
				$scope.hideEditForm();	
				$scope.getCompanies();
				$scope.successMsgShow(resData.success);
			}else{
				$scope.errorMsgShow(resData.error);		
			}
			$scope.clearConsole();
			$scope.loaderHide();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderHide();
        }); 
    };
	
	$scope.hideCompanyEditForm=true;
	
	$scope.showEditForm = function(){
	     $scope.hideAlertMsg();		
		 $scope.hideCompanyEditForm=false;
	};
	$scope.hideEditForm = function(){
	     $scope.hideAlertMsg();		
		 $scope.hideCompanyEditForm=true;
	};
	/***************** company SECTION END HERE *****************/
});
