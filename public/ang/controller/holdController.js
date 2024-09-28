app.controller('holdController', function($scope, $http, BASE_URL,$ngConfirm) {

	//define empty variables
	$scope.holdData = '';
	$scope.editHoldFormDiv = true;

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
	$scope.hold = {};
	$scope.statusList = [				  
		  {id: '1', name: 'Active'},
		  {id: '0', name: 'Deactive'}					 
		 
	];	
	$scope.hold.oh_status = { selectedOption: {id: $scope.statusList[0].id ,name:$scope.statusList[0].name} };
		
	/***************** amendment SECTION START HERE *****************/
	/**
	 * generate unique code
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	//$scope.hold 			=	{};
	$scope.hold.oh_code 	=	'';
	
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'master/generate-hold-number'
		}).success(function (result){
			$scope.hold.oh_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	/**
	 * Save amendment records
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	$scope.add = function(){
			if(!$scope.holdForm.$valid)
				return;
			$scope.loaderShow(); 
		// post all form data to save
			$http.post(BASE_URL + "master/add-hold", {
				 data: {formData:$(holdForm).serialize() },
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
	 * created_on : 27-12-2018
	**/
	$scope.resetForm= function(){
		$scope.hold.oh_name='';
		$scope.holdForm.$setUntouched();
		$scope.holdForm.$setPristine();	
	}
	//code used for sorting list order by fields 
	$scope.predicate = 'oh_name';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	// show form for amendment edit and its data
	$scope.showEditForm = function(){
		$scope.editHoldFormDiv = false;
		$scope.addHoldFormDiv = true;
	};
	
	// show form for add new  amendment 
	$scope.showAddForm = function(){
		$scope.editHoldFormDiv = true;
		$scope.addHoldFormDiv = false;
	};
	
	/**
	 * Get list  on page load.
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	$scope.getList = function() {
		$scope.generateDefaultCode();
		$scope.searchHoldData = '';
		$http.post(BASE_URL + "master/get-hold-list", {
        }).success(function (data, status, headers, config) {
			$scope.holdData = data.holdsList;
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
	 * Edit  data
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	$scope.edit = function(id){
		if(id != ''){
			$http.post(BASE_URL + "master/edit-hold", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){
				    $scope.showEditForm();
					$scope.status = {
						selectedOption: { id: data.responseData.oh_status} 
					};	
					$scope.oh_id = data.responseData.oh_id;	
					$scope.editData = data.responseData;	
					$('html, body').animate({ scrollTop: $("#editHoldDiv").offset().top },500);	
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
	 * update  data
	 * created_by : Ruby
	 * created_on : 27-12-2018
	**/
	$scope.update = function(){ 
    	if(!$scope.editHoldForm.$valid)
      	return;  
		$scope.loaderShow(); 
        $http.post(BASE_URL + "master/update-hold", { 
            data: {formData:$(editHoldForm).serialize() },
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
	
	// Delete amendment from the database
	$scope.delete = function(id)
    { 
		if(id){
				$scope.loaderShow(); 
				$http.post(BASE_URL + "master/delete-hold", {
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
					}
					$scope.loaderHide(); 
					$scope.clearConsole();
				});
			}
    };
	
		
	

	
	
	/***************** amendment SECTION END HERE *****************/
});
