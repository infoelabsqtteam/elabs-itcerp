app.controller('unitConversionsController', function($scope, $http, BASE_URL) {
	console.log('Angular Init');
	//define empty variables
	$scope.unitcondata = '';
	$scope.from_unit = ''; 			
	$scope.to_unit = ''; 			

	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup 	= '';	
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
	}
	//**********/scroll to top function**********
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		//console.clear();
	}
	//*********/Clearing Console********************************************
	
	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 		 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	//**********/If DIV is hidden it will be visible and vice versa************
	
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
	
	//**********successMessagePopup**************************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		= message;				
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	= true;
		$scope.moveToMsg();
	}
	//********** /successMessagePopup************************************************
	
	//**********errorMsgShowPopup**************************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	}
	//********** /errorMsgShowPopup************************************************
	
	//**********hideAlertMsgPopup*************
	$scope.hideAlertMsgPopup = function(){ 
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
	}
	//**********hideAlertMsgPopup**********************************************/
	
	//sorting variables
	$scope.sortType     = 'from_unit';    // set the default sort type
	$scope.sortReverse  = false;             // set the default sort order
	$scope.searchFish   = '';    			 // set the default search/filter term
	
	/***************** unit SECTION START HERE *****************/	
	// function is used to call the 
    $scope.addUnitConversion = function(){
    	if(!$scope.unitConversionForm.$valid)
      	return;
		// post all form data to save
        $http.post(BASE_URL + "unit/add-conversions", {
            data: {formData:$(unitConversionForm).serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){ 
				//reload the all units
				$scope.unit={};
				$scope.unitConversionForm.$setUntouched();
				$scope.unitConversionForm.$setPristine();	
				$scope.getUnitConversions();				
				$scope.hideAddForm();				
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShowPopup(data.error);
			}
			$scope.clearConsole(); 
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
					$scope.errorMsgShowPopup('Oops ! Sorry for inconvience server not responding or may be some error');
			}
        });
    }	
	
	
	//code used for sorting list order by fields 
	$scope.predicate = 'from_unit';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list of units	
	$scope.getUnitConversions = function()
    { 
		$http.post(BASE_URL + "unit/get-conversions", {
            //status: status, prod_id:prodID, cat_id:catID
        }).success(function (data, status, headers, config) {
			$scope.unitcondata = data.unitConversionsList;
			$scope.clearConsole(); 
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
					$scope.errorMsgShow('Oops ! Sorry for inconvience server not responding or may be some error');
			}
        });
    };	
	
	// Delete unit from the database
	$scope.deleteConUnit = function(id)
    { 
		if(id != ''){
			var deleteConfirm = confirm("Are you sure you want to delete this record permanently!");
			if (deleteConfirm == true) { 
				$http.post(BASE_URL + "unit/delete-conversions", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {
					if(data.success){
						// reload the all employee
						$scope.getUnitConversions();
						$scope.successMsgShow(data.success);
					}else{
						$scope.errorMsgShow(data.error);	
					}
					$scope.clearConsole(); 
				}).error(function (data, status, headers, config) {
					if(status == '500' || status == '400'){
					$scope.errorMsgShow('Oops ! Sorry for inconvience server not responding or may be some error');
					}
				});
			}
		}else{
			
		}
    };
	
	// edit an unit and its data
	$scope.editConUnit = function(id)
    {
		$scope.from_unit_val = ''; 			
		$scope.to_unit_val = ''; 			
		$scope.confirm_factor_val = '';				
		$scope.unit_con_id_val = '';
		if(id != ''){
			$http.post(BASE_URL + "unit/edit-conversions", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){ 
				    $scope.showEditForm();
					$scope.from_unit_val = data.responseData.from_unit; 			
					$scope.to_unit_val = data.responseData.to_unit; 			
					$scope.confirm_factor_val = data.responseData.confirm_factor;				
					$scope.unit_con_id_val = btoa(data.responseData.unit_conversion_id);	
				}else{
					$scope.errorMsgShowPopup(data.error);
				}
				$scope.clearConsole(); 
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShowPopup('Oops ! Sorry for inconvience server not responding or may be some error');
				}
			});
		}else{
			
		}
    };	
	// update unit and its data
	$scope.updateUnitCon = function(){ 
    	if(!$scope.editUnitConForm.$valid)
      	return;  console.log("valid updateUnitCon");
		// post all form data to save
        $http.post(BASE_URL + "unit/update-conversions", { 
            data: {formData:$("#edit_unitcon_form").serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				//reload the all units
				$scope.getUnitConversions();				
				$scope.hideEditForm();				
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShowPopup(data.error);
			}
			$scope.clearConsole(); 
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
					$scope.errorMsgShowPopup('Oops ! Sorry for inconvience server not responding or may be some error');
			}
        }); 
    };
	
	$scope.showAddForm = function()
    {
		 $('#add_form').modal('show');
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
	/***************** unit SECTION END HERE *****************/
});
