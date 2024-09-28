app.controller('dynamicFieldController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {
	
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

	//************code used for display status in dropdown*****************
	$scope.state ={};
	$scope.statusList = [
		{id: 1, name: 'Active'},
		{id: 0, name: 'Inactive'},
	];
	$scope.dynamic_field_status  = {selectedOption: { id: $scope.statusList[0].id,name:$scope.statusList[0].name}};
	/*****************display state code dropdown start*****************/
	
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
						$scope.deleteDynamicField(id);
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
	
	/***************** division SECTION START HERE *****************/	
	//function is used to create new division 
    $scope.addDynamicField = function(){		
    	if(!$scope.dynamicFieldForm.$valid) return; 
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "master/add-dynamic-field", {
            data: {formData:$(dynamicFieldForm).serialize() },
        }).success(function (resData, status, headers, config){	
			if(resData.success){ 	
				$scope.resetDynamicField();
				//reload the all companies
				$scope.getDynamicFields();			
				$scope.dynamic_field_status  = {selectedOption: { id: $scope.statusList[0].id,name:$scope.statusList[0].name}};
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
	$scope.resetDynamicField=function(){
		$scope.dynamic_field_name=null;
		$scope.dynamic_field_code=null;
		$scope.dynamic_field_status=null;
		$scope.dynamicFieldForm.$setUntouched();
		$scope.dynamicFieldForm.$setPristine();	
	};
	//code used for sorting list order by fields 
	$scope.predicate = 'dynamic_field_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	// function is used to fetch the list of compines 	
	$scope.getDynamicFields = function()
    {
		$http.post(BASE_URL + "master/get-dynamic-fields", {
			
        }).success(function (data, status, headers, config) { 
				if(data.dynamicFieldsList){
					$scope.dynamicFieldsData = data.dynamicFieldsList; 
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
		$http.post(BASE_URL + "master/get-divisions-multisearch", {
            data: { formData:$scope.searchDynamicField },
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
	    $scope.searchDynamicField={};
		$scope.getDynamicFields();
	}
	
	$scope.openMultisearch = function()
    { 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	}
	/**************multisearch end here**********************/

	// Delete division from the database
	$scope.deleteDynamicField = function(id){   
		if(id){			 
			$scope.loaderShow();
			$http.post(BASE_URL + "master/delete-dynamic-field", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (resData, status, headers, config) {  
				if(resData.success){ 
					$scope.getDynamicFields();
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
	$scope.editDynamicField = function(id){
		$scope.dynamic_field_name = ''; 			
		$scope.dynamic_field_code = ''; 			
		$scope.dynamic_field_status = ''; 			
		$scope.divi_id =''; 		
		if(id){
			$http.post(BASE_URL + "master/edit-dynamic-field", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) { 
				if(data.returnData.responseData){
					var responseD=data.returnData.responseData;
					$scope.showEditForm(); 	
					$scope.dynamic_field_name = responseD.dynamic_field_name; 			
					$scope.dynamic_field_code = responseD.dynamic_field_code;		
					// $scope.dynamic_field_status = { id: responseD.dynamic_field_status}; 
					$scope.dynamic_field_status = {
						selectedOption: { id: responseD.dynamic_field_status} 
					};
					$scope.odfs_id = responseD.odfs_id;
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
	$scope.updateDynamicField = function(){  	
    	if(!$scope.editDynamicFieldForm.$valid) return; 
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "master/update-dynamic-field", { 
            data: {formData:$("#edit_dynamic_field_form").serialize() },
        }).success(function (resData, status, headers, config) {   	
			if(resData.success){ 
				$scope.getDynamicFields();
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
	$scope.isDynamicFieldEditForm=true;
	
	$scope.showAddForm = function()
    {		
		$scope.generateDefaultCode();
		$scope.hideAlertMsg();	
		$scope.isDynamicFieldEditForm=true;
		$scope.isDynamicFieldAddForm=false;
	};	
	$scope.showEditForm = function()
    {	
		$scope.hideAlertMsg();	
		$scope.isDynamicFieldEditForm=false;
		$scope.isDynamicFieldAddForm=true;
	};
	$scope.hideEditForm = function()
    {		
		$scope.hideAlertMsg();
		$scope.isDynamicFieldEditForm=true;
		$scope.isDynamicFieldAddForm=false;
	};
	$scope.hideAddForm = function()
    {	
		$scope.hideAlertMsg();				
		$scope.isDynamicFieldEditForm=false;
		$scope.isDynamicFieldAddForm=true;
	};
	
	$scope.hideViewForm = function(){	
		$scope.hideAlertMsg();
		$scope.listDivisions=false;
		$scope.viewDivisionDiv=true;
	};
	
});
