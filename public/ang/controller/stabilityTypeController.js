app.controller('stabilityTypeController', function($scope, $http, BASE_URL,$ngConfirm) {
//alert('stabilityTypeController.js');

	//define empty variables
	$scope.stabilityTypeData = '';
	$scope.editFormDiv = true;

	//sorting variables
	$scope.sortType     = 'stb_stability_type_name';    // set the default sort type
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
	$scope.stabilityType = {};
	$scope.statusList = [				  
		  {id: '1', name: 'Active'},
		  {id: '0', name: 'Deactive'}					 
		 
	];	
	$scope.stabilityType.stb_stability_type_status = { selectedOption: {id: $scope.statusList[0].id ,name:$scope.statusList[0].name} };
		
	/***************** amendment SECTION START HERE *****************/
	
	/**
	 * Save amendment records
	 * created_by : Ruby
	 * created_on : 25-02-2019
	**/
	$scope.add = function(){
			if(!$scope.addForm.$valid)
				return;
			$scope.loaderShow(); 
		// post all form data to save
			$http.post(BASE_URL + "master/add-stability-type", {
				 data: {formData:$(addForm).serialize() },
			}).success(function (data, status, headers, config) {
			if(data.error == 1)	{
				$scope.getList();
				$scope.successMsgShow(data.message);
				$scope.resetForm();
				
			}else{
				$scope.errorMsgShow(data.message);
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
	/**
	 * Reset add  form
	 * created_by : Ruby
	 * created_on : 25-02-2019
	**/
	$scope.resetForm= function(){
		$scope.stabilityType.stb_stability_type_name='';
		$scope.addForm.$setUntouched();
		$scope.addForm.$setPristine();	
	}
	//code used for sorting list order by fields 
	$scope.predicate = 'stb_stability_type_name';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	// show form for amendment edit and its data
	$scope.showEditForm = function(){
		$scope.editFormDiv = false;
		$scope.addFormDiv = true;
	};
	
	// show form for add new  amendment 
	$scope.showAddForm = function(){
		$scope.editFormDiv = true;
		$scope.addFormDiv = false;
	};
	
	/**
	 * Get list  on page load.
	 * created_by : Ruby
	 * created_on : 25-02-2019
	**/
	$scope.getList = function() {
		$scope.searchdData = '';
		$scope.loaderShow(); 
		$http.post(BASE_URL + "master/get-stability-type-list", {
        }).success(function (data, status, headers, config) {
			$scope.stabilityTypeData = data.stabilityTypeDataList;
			$scope.clearConsole();
			$scope.loaderHide(); 

        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
					$scope.errorMsgShow($scope.defaultMsg);
					$scope.loaderHide(); 

			}
			$scope.clearConsole();
			$scope.loaderHide(); 
        });
    };
	 
	/**
	 * Edit  data
	 * created_by : Ruby
	 * created_on : 25-02-2019
	**/
	$scope.edit = function(id){
		if(id != ''){
			$scope.loaderShow(); 
			$http.post(BASE_URL + "master/edit-stability-type-master", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){
				    $scope.showEditForm();
					$scope.status = {
						selectedOption: { id: data.responseData.stb_stability_type_status} 
					};	
					$scope.stb_stability_type_id = data.responseData.stb_stability_type_id;	
					$scope.editData = data.responseData;	
					$('html, body').animate({ scrollTop: $("#editDiv").offset().top },500);
					$scope.loaderHide(); 

				}else{
					$scope.errorMsgShow(data.error);
					$scope.loaderHide(); 

				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
					$scope.loaderHide(); 

				}
				$scope.clearConsole();
				$scope.loaderHide(); 

			});
		}
    };
	 
	/**
	 * update  data
	 * created_by : Ruby
	 * created_on : 25-02-2019
	**/
	$scope.update = function(){ 
    	if(!$scope.editForm.$valid)
      	return;  
		$scope.loaderShow(); 
        $http.post(BASE_URL + "master/update-stability-type-master", { 
            data: {formData:$(editForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				$scope.getList();		
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
						$scope.delete(id);
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
	
	// Delete stability type from the database
	$scope.delete = function(id)
    { 
		if(id){
				$scope.loaderShow(); 
				$http.post(BASE_URL + "master/delete-stability-type-master", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {
					if(data.success){
						$scope.getList(); // refresh list
						$scope.successMsgShow(data.success);
					}else{
						$scope.errorMsgShow(data.error);	
					}
					$scope.loaderHide(); 
					$scope.clearConsole();
				}).error(function (data, status, headers, config) {
					if(status == '500' || status == '400'){
						$scope.errorMsgShow($scope.defaultMsg);
						$scope.loaderHide(); 
					}
					$scope.loaderHide(); 
					$scope.clearConsole();
				});
			}
    };
	
		
	

	
	
	/***************** stability type SECTION END HERE *****************/
});
