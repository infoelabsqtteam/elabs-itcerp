app.controller('testParameterBOMController', function($scope, $http, BASE_URL) {
	console.log('Angular Init');
	
	//define empty variables
	$scope.allList = '';	
	$scope.successMessage=true;
	$scope.errorMessage=true;
	//sorting variables
	$scope.sortType     = 'test_parameter_code';    // set the default sort type
	$scope.sortReverse  = false;            		// set the default sort order
	$scope.searchFish   = '';    			 		// set the default search/filter term
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
	}
	//**********/scroll to top function**********
	
	//TestProductList dropdown on page load
	$scope.productTestList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'get-product-test-hdr-list'
		}).success(function (result) { 
		$scope.productTestList = result;
	});
	//TestProductParametersList dropdown on page load
	$scope.testParameterList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'get-product-test-dtl-list'
		}).success(function (result) {  
		$scope.testParameterList = result;
	});
	//ItemList dropdown on page load
	$scope.itemList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'get-item-master-list'
		}).success(function (result) {  
		$scope.itemList = result;
	});
	
	
	/***************** test parameters category  SECTION START HERE *****************/	
	// function is used to call the 
    $scope.addRecord = function(){ console.log("inside insert");
    	if(!$scope.addForm.$valid)
      	return;
		// post all form data to save
        $http.post(BASE_URL + "test-parameter-BOM/add-BOM", {
            data: {formData:$("#add_form").serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){ 
				//reload the all test parameters category 
				$scope.getProductTestParameters();				
				$scope.alertSuccessMsg(data.success);
			}else{
				$scope.alertErrorMsg(data.error);
			}
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
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
		$http.post(BASE_URL + "test-parameter-BOM/get-BOM", {
        }).success(function (data, status, headers, config) {  
			$scope.allList = data.allList;
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
        });
    };	
	
	// Delete test parameters category  from the database
	$scope.deleteRecord = function(id)
    { 
		if(id != ''){
			var deleteConfirm = confirm("Are you sure you want to delete this record permanently!");
			if (deleteConfirm == true) { 
				$http.post(BASE_URL + "test-parameter-BOM/delete-BOM", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {
					if(data.success){
						// reload the all test parameters category 
						$scope.getProductTestParameters();
						$scope.alertSuccessMsg(data.success);
					}else{
						$scope.errorMsgShow(data.error);	
					}
				}).error(function (data, status, headers, config) {
					if(status == '500' || status == '400'){
						$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
					}
				});
			}
		}else{
			
		}
    };
	
	// edit an test parameters category and its data
	$scope.editRecord = function(id)
    { 
		if(id != ''){
			$http.post(BASE_URL + "test-parameter-BOM/edit-BOM", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config){ 
				if(data.returnData.responseData){  
					var responseD=data.returnData.responseData; 
					$scope.productTestsList1 = {
						availableOptions: data['productTestCodeList'],
						selectedOption: {id: responseD.test_id} 
					};
					$scope.testParametersList1 = {
						availableOptions: data['testParameterList'],
						selectedOption: {id: responseD.product_test_dtl_id} 
					};
					$scope.itemList1 = {
						availableOptions: data['itemList'],
						selectedOption: {id: responseD.item_id} 
					};
					$scope.showEditForm(); 	
					$scope.consumed_qty1 = responseD.consumed_qty; 							
					$scope.test_BOM_id1 =  btoa(responseD.test_BOM_id);				
				}else{
					$scope.alertErrorMsg(data.error);
				}
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
				}
			});
		}else{
			
		}
    };	
	// update test parameters category  and its data
	$scope.updateRecord = function(){ 
    	if(!$scope.edittestParaCatForm.$valid)
      	return;  console.log("valid updateTestStd");
		// post all form data to save
        $http.post(BASE_URL + "test-parameter-BOM/update-BOM", { 
            data: {formData:$("#edit_test_parameter_form").serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				//reload the all test parameters categories
				$scope.getProductTestParameters();				
				$scope.alertSuccessMsg(data.success); 
			}else{
				$scope.alertErrorMsg(data.error);
			}
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
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
