app.controller('productTestParameterController', function($scope, $http, BASE_URL) {
		
	//define empty variables
	$scope.allList = '';	
	
	//sorting variables
	$scope.sortType     = 'test_parameter_code';    // set the default sort type
	$scope.sortReverse  = false;            		// set the default sort order
	$scope.searchFish   = '';    			 		// set the default search/filter term
	$scope.successMessage=true;
	$scope.errorMessage=true;
		
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
	
	//productTestList dropdown on page load
	$scope.productTestList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'standard-wise-product/get-producttest-list'
		}).success(function (result) { 
		if(result.length){
			$scope.productTestList = result;
		}
		$scope.clearConsole();
	});	
	
	//equipment dropdown on page load
	$scope.equipmentList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'get-equipment-list'
		}).success(function (result) { 
		if(result.equipmentList.length)
		{
			$scope.equipmentList = result.equipmentList;
		}
		$scope.clearConsole();
	});	
	
	//method dropdown on page load
	$scope.methodtList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'get-method-list'
		}).success(function (result) { 
		if(result.methodtList.length)
		{
			$scope.methodtList = result.methodtList;
		}
		$scope.clearConsole();
	});
	
	//testParameterList dropdown on page load
	$scope.testParameterList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'standard-wise-product/get-testparameter-list'
		}).success(function (result) {  
			$scope.testParameterList = result;
			$scope.clearConsole();
		});
	
	
	/***************** test parameters category  SECTION START HERE *****************/	
	// function is used to call the 
    $scope.addRecord = function(){ 
    	if(!$scope.addtestParameterForm.$valid)
      	return;
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "standard-wise-product/add-parameters-details", {
            data: {formData:$("#add_test_parameter_form").serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){ 
				//reload the all test parameters category 
				$scope.getProductTestParameters();				
				$scope.alertSuccessMsg(data.success);
			}else{
				$scope.alertErrorMsg(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
			$scope.loaderHide();
			$scope.clearConsole();
        });
    }	
	
	//code used for sorting list order by fields 
	$scope.predicate = 'test_parameter_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//function is used to fetch the list of test parameters category 	
	$scope.getProductTestParameters = function()
    { 
		$http.post(BASE_URL + "standard-wise-product/get-parameters-details", {
        }).success(function (data, status, headers, config) {  
			$scope.allList = data.allList;
			$scope.clearConsole();
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
			$scope.clearConsole();
        });
    };	
	
	// Delete test parameters category  from the database
	$scope.deleteRecord = function(id)
    { 
		if(id != ''){
			var deleteConfirm = confirm("Are you sure you want to delete this record permanently!");
			if (deleteConfirm == true) { 
				$scope.loaderShow();
				$http.post(BASE_URL + "standard-wise-product/delete-parameters-details", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {
					if(data.success){
						// reload the all test parameters category 
						$scope.getProductTestParameters();
						$scope.alertSuccessMsg(data.success);
					}else{
						$scope.errorMsgShow(data.error);	
					}
					$scope.loaderHide();
					$scope.clearConsole();
				}).error(function (data, status, headers, config) {
					if(status == '500' || status == '400'){
						$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
					}
					$scope.loaderHide();
					$scope.clearConsole();
				});
			}
		}else{
			
		}
    };
	
	// edit an test parameters category and its data
	$scope.editRecord = function(id)
    { 
		if(id != ''){
			$http.post(BASE_URL + "standard-wise-product/edit-parameters-details", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config){ 
				if(data.returnData.responseData){  
					var responseD=data.returnData.responseData; 
					$scope.productTests = {
						availableOptions: data['productTestList'],
						selectedOption: {id: responseD.test_id} 
					};
					$scope.testParameters = {
						availableOptions: data['testParameterList'],
						selectedOption: {id: responseD.test_parameter_id} 
					};
					$scope.testEquipment1 = {
						selectedOption: {id: responseD.equipment_id} 
					};
					$scope.testMethod1 = {
						selectedOption: {id: responseD.method_id} 
					};
					$scope.showEditForm(); 	
					$scope.standard_value_type1 = responseD.standard_value_type; 			
					$scope.standard_value_from1 = responseD.standard_value_from;				
					$scope.standard_value_to1 = responseD.standard_value_to;				
					$scope.cost_price1 = {
									   value: responseD.cost_price
									 };					
					$scope.product_test_dtl_id1 =  btoa(responseD.product_test_dtl_id);				
				}else{
					$scope.alertErrorMsg(data.error);
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
				}
				$scope.clearConsole();
			});
		}else{
			
		}
    };	
	// update test parameters category  and its data
	$scope.updateRecord = function(){ 
    	if(!$scope.edittestParaCatForm.$valid)
      	return; 
	    $scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "standard-wise-product/update-parameters-details", { 
            data: {formData:$("#edit_test_parameter_form").serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				//reload the all test parameters categories
				$scope.getProductTestParameters();				
				$scope.alertSuccessMsg(data.success);
			}else{
				$scope.alertErrorMsg(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }); 
    };
	
	// show form for test parameters category  edit and its data
	$scope.showEditForm = function()
    {
		 $('#editTestParameterDiv').show();
		 $('#addTestParameterDiv').hide();
	};
	// show form for add new  test parameters category  
	$scope.showAddForm = function()
    {
		 $('#editTestParameterDiv').hide();
		 $('#addTestParameterDiv').show();
	};
	//alert success messages
	$scope.alertSuccessMsg = function(msg)
    {	
	    $scope.successMessage=false;
		$scope.errorMessage=true;
		$scope.successMsg=msg;
	};
	//alert error messages
	$scope.alertErrorMsg = function(err)
    {	
		$scope.successMessage=true;
		$scope.errorMessage=false;
		$scope.errorMsg=err;	
	};
	//hide alert messages
	$scope.hideAlert = function()
    {		
           $scope.successMessage=true;
		   $scope.errorMessage=true;
	};
	/***************** test parameters category  SECTION END HERE *****************/
});
