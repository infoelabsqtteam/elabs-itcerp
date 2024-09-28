app.controller('paymentSourcesController', function($scope, $http, BASE_URL, $window ,$ngConfirm){
	// sorting variables
	$scope.sortType     = 'payment_source_id'; 			 // set the default sort type
	$scope.sortReverse  = false; 						 // set the default sort order
	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';	
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
	//**********If DIV is hidden it will be visible and vice versa*******
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 		 = true;
	//**********/If DIV is hidden it will be visible and vice versa******
	
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
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//********** /hide Alert Msg**********************************************
	
	/*****************payment source add section start here *****************/	
    $scope.addPaymentSources = function(){
    	if(!$scope.paymentSourceForm.$valid)
      	return; 
	    $scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "payment-sources/save-payment-sources", {
            data: {formData:$(paymentSourceForm).serialize() },
        }).success(function (rseData, status, headers, config) {	
			if(rseData.success){ 
				//reload the all payment sources
				$scope.getPaymentSources();	
				//$scope.hideAddForm();
				//reset form
					 $scope.resetForm();
				$scope.successMsgShow(rseData.success);
			}else{
				$scope.errorMsgShow(rseData.error);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
        }).error(function (rseData, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
        });
    }
    /*reset form*/
    $scope.resetForm = function(){
		$scope.sources = {};	
		$scope.paymentSourceForm.$setUntouched();
		$scope.paymentSourceForm.$setPristine();	
	}
	//*****************payment source add section end here *****************/	

	//code used for sorting list order by fields 
	$scope.predicate = 'payment_source_id';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//function is used to fetch the list of payment sources 	
	$scope.getPaymentSources = function()
    { 
		$http.post(BASE_URL + "payment-sources/get-payment-sources", {
			
        }).success(function (data, status, headers, config) { 
			$scope.paymentSources = data.paymentSources;
			$scope.clearConsole(); 
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole(); 
        }); 
    };
	
	/**************multisearch start here**********************/
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	
	$scope.getMultiSearch = function()
    {  
		$scope.filterPayment='';
		$http.post(BASE_URL + "payment-sources/get-payment-sources-multisearch", {
            data: { formData:$scope.searchPayment },
        }).success(function (data, status, headers, config){ 
			$scope.paymentSources = data.paymentSources;
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
	    $scope.searchPayment={};
	    $scope.filterPayment='';
		$scope.getPaymentSources();
	}
	
	$scope.openMultisearch = function()
    { 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	}
	/**************multisearch end here**********************/
	
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
						$scope.deletePaymentSources(id);
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
	
	//Delete department from the database
	$scope.deletePaymentSources = function(id){   
		if(id){
				$scope.loaderShow();	
				$http.post(BASE_URL + "payment-sources/delete-payment-sources", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (result, status, headers, config) {  
					if(result.success){
						//reload the all employee
						$scope.getPaymentSources();
						$scope.successMsgShow(result.success);
					}else{
						$scope.errorMsgShow(result.error);
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
	
	// edit an department and its data
	$scope.editPaymentSources = function(id)
    {
		if(id != ''){
			$scope.department_id1=null;
			$http.post(BASE_URL + "payment-sources/edit-payment-sources", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (resData, status, headers, config) { 			
				if(resData.returnData.responseData){
					var responseD=resData.returnData.responseData;
					$scope.edit_payment_source_name = responseD.payment_source_name;
					$scope.edit_payment_source_description = responseD.payment_source_description;
					$scope.edit_payment_source_id = btoa(responseD.payment_source_id);
					$scope.status_types = {
						availableTypeOptions: [
						  {id: '1', name: 'Active'},
						  {id: '0', name: 'Deactive'}
						],
						selectedOption: {id: responseD.status } 
					};
					$scope.showEditForm();	
				}else{
					$scope.errorMsgShow(resData.error);
				}
				$scope.clearConsole();
			}).error(function (resData, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}else{
			
		}
    };	
	// update an department and its data
	$scope.updatePaymentSources = function(){
    	if(!$scope.editPaymentSourcesForm.$valid)
      	return;
        $scope.loaderShow();	
		// post all form data to save
        $http.post(BASE_URL + "payment-sources/update-payment-sources", { 
            data: {formData:$(editPaymentSourcesForm).serialize() },
        }).success(function (data, status, headers, config) { 	
			if(data.success){ 
				//reload the all companies
				$scope.getPaymentSources();
				//$scope.hideEditForm(); 				
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShow(data.error);
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
	$scope.hideSourceAddForm=false;
	$scope.hideSourceEditForm=true;
	$scope.showEditForm = function(){
		$scope.IsVisiableSuccessMsg 	 = true;
		$scope.IsVisiableErrorMsg 		 = true;
		$scope.hideSourceEditForm=false;
		$scope.hideSourceAddForm=true;
	};
	$scope.hideEditForm = function(){
		$scope.IsVisiableSuccessMsg 	 = true;
		$scope.IsVisiableErrorMsg 		 = true;
		$scope.hideSourceEditForm=true;
		$scope.hideSourceAddForm=false;
	};
	$scope.hideAddForm = function(){
		$scope.IsVisiableSuccessMsg 	 = true;
		$scope.IsVisiableErrorMsg 		 = true;
		$scope.hideSourceEditForm=false;
		$scope.hideSourceAddForm=true;
	};
	/***************** PAYMENT SOURCES SECTION END HERE *****************/
});