app.controller('customerLocationController', function($scope, $http, BASE_URL) {
	console.log('Angular Init');
	
	// sorting variables
	$scope.sortType     = 'company_code'; 			// set the default sort type
	$scope.sortReverse  = false;  					// set the default sort order
	$scope.searchFish   = '';   					// set the default search/filter term
	$scope.successMessage=true;
	$scope.errorMessage=true;
	//define empty variables
	$scope.divsnData = '';
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
	}
	//**********/scroll to top function**********
	/*****************display customers-list dropdown start*****************/	
			$scope.customersList = [];
			$http({
					method: 'POST',
					url: BASE_URL +'customers-list'
				}).success(function (result) {
					if(result.customersList){
						$scope.customersList = result.customersList;
					}
			});
	/*****************display company code dropdown end*****************/	
	
	/***************** customerLocation SECTION START HERE *****************/	
	// function is used to create new customerLocation 
    $scope.addCustomerLocation = function(){  
    	if(!$scope.customerLocationForm.$valid)
      	return; console.log("validate");
		// post all form data to save
        $http.post(BASE_URL + "customerLocation/add-customerLocation", {
            data: {formData:$("#add_customerLocation_form").serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){ 
				//reload the all companies
				$scope.getCustomerLocations();				
				$scope.hideAddForm();				
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
	$scope.predicate = 'company_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	// function is used to fetch the list of compines 	
	$scope.getCustomerLocations = function()
    { 
		$http.post(BASE_URL + "customerLocation/get-customerLocations", {
			
        }).success(function (data, status, headers, config) { 
			$scope.divsnData = data.customerLocationsList; 
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
						$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
        });
    };
	
	// Delete customerLocation from the database
	$scope.deleteCustomerLocation = function(id)
    {   
		if(id != ''){
			var deleteConfirm = confirm("Are you sure you want to delete this record permanently!");
			if (deleteConfirm == true) { 
				$http.post(BASE_URL + "customerLocation/delete-customerLocation", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {  
					if(data.success){
						// reload the all employee
						$scope.getCustomerLocations();
						$scope.alertSuccessMsg(data.success);
					}else{
						$scope.alertErrorMsg(data.error);	
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
	
	// edit an customerLocation and its data
	$scope.editCustomerLocation = function(id)
    {
		$scope.divi_name = ''; 			
		$scope.divi_code = ''; 			
		$scope.divi_id =''; 		
		if(id != ''){
			$http.post(BASE_URL + "customerLocation/edit-customerLocation", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) { 
				if(data.returnData.responseData){
					var responseD=data.returnData.responseData;
					$scope.customerLocations = {
						availableOptions: data['companyData'],
						selectedOption: {id: responseD.customer_id, name: responseD.company_name} 
					};
					$scope.showEditForm(); 	
					$scope.divi_name = responseD.location_name; 			
					$scope.divi_code = responseD.location_code;				
					$scope.divi_id = btoa(responseD.location_id);				
				}else{
					$scope.alertErrorMsg(data.error);				}
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
						$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
				}
			});
		}else{
			
		}
    };	
	// update an customerLocation and its data
	$scope.updateCustomerLocation = function(){ 
    	if(!$scope.editCustomerLocationForm.$valid)
      	return; 
		// post all form data to save
        $http.post(BASE_URL + "customerLocation/update-customerLocation", { 
            data: {formData:$("#edit_customerLocation_form").serialize() },
        }).success(function (data, status, headers, config) { console.log(data);
			if(data.success){ 
				//reload the all companies
				$scope.getCustomerLocations();
				$scope.hideEditForm();				
				$scope.alertSuccessMsg(data.success);
			}else{
						$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
						$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
        }); 
    };	
	 
	$scope.showEditForm = function()
    {
		 $('#edit_form').modal('show');
	};
	$scope.hideEditForm = function()
    {
		 $('#edit_form').modal('hide');
	};
	$scope.hideAddForm = function()
    {
		 $('#add_form').modal('hide');
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
	/***************** customerLocation SECTION END HERE *****************/
});
