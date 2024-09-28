app.controller('amendmentController', function($scope, $http, BASE_URL,$ngConfirm) {

	//define empty variables
	$scope.amendmentData = '';
	$scope.editAmendmentFormDiv = true;

	//sorting variables
	$scope.sortType     = 'oam_name';    // set the default sort type
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
	$scope.amendment = {};
	$scope.statusList = [				  
		  {id: '1', name: 'Active'},
		  {id: '0', name: 'Deactive'}					 
		 
	];	
		$scope.amendment.oam_status = { selectedOption: {id: $scope.statusList[0].id ,name:$scope.statusList[0].name} };

	
	/***************** amendment SECTION START HERE *****************/
	/**
	 * generate unique code
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	//$scope.amendment ={};
	$scope.amendment.oam_code ='';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'master/generate-amendment-number'
		}).success(function (result){
			$scope.amendment.oam_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	/**
	 * Save amendment records
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	$scope.addAmendment = function(){
		//$scope.generateDefaultCode();
			if(!$scope.amendmentForm.$valid)
				return;
			$scope.loaderShow(); 
		// post all form data to save
			$http.post(BASE_URL + "master/add-amendment", {
				 data: {formData:$(amendmentForm).serialize() },
			}).success(function (data, status, headers, config) {
			if(data.error == 1)	{
				$scope.getAmendmentList();
				$scope.successMsgShow(data.message);
				$scope.resetAmendmentForm();
				
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
	 * Reset add amendment form
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	$scope.resetAmendmentForm= function(){
		$scope.amendment.oam_name='';
		$scope.amendmentForm.$setUntouched();
		$scope.amendmentForm.$setPristine();	
	}
	//code used for sorting list order by fields 
	$scope.predicate = 'oam_name';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	// show form for amendment edit and its data
	$scope.showEditForm = function(){
		$scope.editAmendmentFormDiv = false;
		$scope.addAmendmentFormDiv = true;
	};
	
	// show form for add new  amendment 
	$scope.showAddForm = function(){
		$scope.editAmendmentFormDiv = true;
		$scope.addAmendmentFormDiv = false;
	};
	
	/**
	 * Get list of amendments on page load.
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	$scope.getAmendmentList = function() {
		$scope.searchAmendment= '';
		$scope.generateDefaultCode();
		$http.post(BASE_URL + "master/get-amendment", {
        }).success(function (data, status, headers, config) {
			$scope.amendmentData = data.amendmentsList;
			//console.log($scope.amendmentData);
			$scope.clearConsole();
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
					$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
        });
    };
	 
	/**
	 * Edit amendment data
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	$scope.editAmendment = function(id){
		if(id != ''){
			$http.post(BASE_URL + "master/edit-amendment", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){
				    $scope.showEditForm();
					$scope.status = {
						selectedOption: { id: data.responseData.oam_status} 
					};	
					$scope.oam_id = data.responseData.oam_id;	
					$scope.editData = data.responseData;	
					$('html, body').animate({ scrollTop: $("#editAmendmentDiv").offset().top },500);	
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
	 
	/**
	 * update amendment data
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	$scope.updateAmendment = function(){ 
    	if(!$scope.editAmendmentForm.$valid)
      	return;  
		$scope.loaderShow(); 
        $http.post(BASE_URL + "master/update-amendment", { 
            data: {formData:$(editAmendmentForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				$scope.getAmendmentList();		
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
						$scope.deleteAmendment(id);
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
	
	// Delete amendment from the database
	$scope.deleteAmendment = function(id)
    { 
		if(id){
				$scope.loaderShow(); 
				$http.post(BASE_URL + "master/delete-amendment", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {
					if(data.success){
						$scope.getAmendmentList(); // refresh list
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
	
		
	

	
	
	/***************** amendment SECTION END HERE *****************/
});
