app.controller('defaultRemarkController', function($scope,$http, BASE_URL, $ngConfirm,$timeout) {
	
	//define empty variables
	$scope.defaultRemarksData 		 = '';
	$scope.erpEditDefaultRemarksForm         = '';
	$scope.editDefaultRemarksFormDiv 	= true;
        $scope.DepartmentTypeId			= '0';
	
	//sorting variables
	$scope.sortType     			= 'defaultremark_code';     // set the default sort type
	$scope.sortReverse  			= false;             // set the default sort order
	$scope.searchFish   			= '';    			 // set the default search/filter term
	$scope.IsVisiableSuccessMsg		= true;
	$scope.IsVisiableErrorMsg		= true;
	$scope.addDefaultRemarksFormDiv     	= false;
	$scope.editDefaultRemarksFormDiv    	= true;	
	$scope.successMessage 			= '';
	$scope.errorMessage   			= '';
	$scope.defaultRemarkData		= '';
	$scope.filterDefaultRemarkNotes 	= {};	
	$scope.defaultMsg  	    		= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.search =[];	
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
	
	//*********reset Form************************************************
	$scope.resetForm = function(){
		$scope.addDefaultRemarks = {}; 
		$scope.erpAddDefaultRemarksForm.$setUntouched();
		$scope.erpAddDefaultRemarksForm.$setPristine();
		$scope.editDefaultRemarks = {}; 
		$scope.erpEditDefaultRemarksForm.$setUntouched();
		$scope.erpEditDefaultRemarksForm.$setPristine();
	};
	//********/reset Form************************************************
	
	//*********navigate Form************************************************
	$scope.navigateForm = function(){
		if($scope.editDefaultRemarksFormDiv){
			$scope.editDefaultRemarksFormDiv = false;
			$scope.addDefaultRemarksFormDiv  = true;
		}else{
			$scope.editDefaultRemarksFormDiv = true;
			$scope.addDefaultRemarksFormDiv  = false;
		}
	};
	//*********navigate Form************************************************
	
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
						$scope.deleteDefaultRemarks(id);
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
	
	//code used for sorting list order by fields 
	$scope.predicate = 'default_remark_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//************code used for display remark type in dropdown*****************
	$scope.remarkTypeList = [
		{remark_type: 1, remark_type_name: 'Notes'},
		{remark_type: 2, remark_type_name: 'Remarks'}
	];
	//************code used for display remark type in dropdown*****************
	
	//************code used for display remark status in dropdown*****************
	$scope.remarkStatusList = [
		{remark_status: 1, remark_status_name: 'Active'},
		{remark_status: 2, remark_status_name: 'Inactive'}
	];
	//************code used for display remark status in dropdown*****************
	
	//*****************display parent category dropdown code dropdown start****
	$scope.fungetParentCategory = function(){	
		$http({
			method: 'POST',
			url: BASE_URL +'master/get-parent-product-category'
		}).success(function (result) { 
			$scope.parentCategoryList = result.parentCategoryList;
			$scope.clearConsole();
		});
	};
	//*****************display parent category code dropdown end*****************
	
	//*****************display division dropdown start************************	
	$scope.divisionsCodeList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			$scope.divisionsCodeList = result;
			$scope.clearConsole();
	});
	//*****************display division dropdown end***************************
	
	//***************** Default SECTION START HERE *****************/	
		
	//***************** Default SECTION START HERE *****************/	
	$scope.funAddDefaultRemarks  = function(division_id,department_id){
		
		if(!$scope.erpAddDefaultRemarksForm.$valid)return;
		$scope.loaderShow();
		
		// post all form data to save
		$http.post(BASE_URL + "master/add-default-remarks", {
		    data: {formData:$(erpAddDefaultRemarksForm).serialize() },
		}).success(function (result, status, headers, config) {
			if(result.success){
				$scope.resetForm();
				$scope.getBranchWiseDefaultRemarks();	
				$scope.successMsgShow(result.success);
                $scope.loaderHide(); 
			}else{
				$scope.errorMsgShow(result.error);
			}
			$scope.loaderHide(); 
			$scope.clearConsole();
		}).error(function (result, status, headers, config){
			if(status == '500' || status == '404'){
					$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide(); 
			$scope.clearConsole();
		});
	};
	
	//edit an method and its data
	$scope.funEditDefaultRemarks = function(id){
		if(id){
			$scope.searchDropdown='';
			$scope.resetForm();
			$scope.loaderShow(); 
			$http.post(BASE_URL + "master/edit-default-remarks", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){
					$scope.addDefaultRemarksFormDiv     = true;
					$scope.editDefaultRemarksFormDiv    = false;	
					$scope.editDefaultRemarks = data.responseData;
					$scope.editDefaultRemarks.division_id = {
						selectedOption: { id: data.responseData.division_id } 
					};
					$scope.editDefaultRemarks.product_category_id = {
						selectedOption: { id: data.responseData.product_category_id } 
					};
					$scope.editDefaultRemarks.remark_type = {
						selectedOption: { remark_type: data.responseData.type } 
					};
					$scope.editDefaultRemarks.remark_status = {
						selectedOption: { remark_status: data.responseData.remark_status} 
					};
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
	
	// update method and its data
	$scope.funUpdateDefaultRemarks = function(division_id,department_id){
		if(!$scope.erpEditDefaultRemarksForm.$valid)return;  
		$scope.loaderShow();
		
		$http.post(BASE_URL + "master/update-default-remarks", { 
			data: {formData:$(erpEditDefaultRemarksForm).serialize() },
		}).success(function (data, status, headers, config) { 
			if(data.success){
				$scope.resetForm();
				$scope.navigateForm();
				$scope.getBranchWiseDefaultRemarks();			
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
	
	//Delete method from the database
	$scope.deleteDefaultRemarks = function(id,division_id,department_id){ 
		if(id){
			$scope.loaderShow(); 
			$http.post(BASE_URL + "master/delete-default-remarks", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.success){
					$scope.defaultRemarkList(division_id,department_id);	
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
		
	/**************multisearch start here**********************/
	$scope.getMultiSearch=function(){
		$timeout(function (){$scope.getBranchWiseDefaultRemarks();}, 1000);
	};
	$scope.multiSearchTr  = true;
	$scope.multisearchBtn = false;
	
	$scope.getBranchWiseDefaultRemarks = function(){  
		$http.post(BASE_URL + "master/get-default-remarks", {
			data: { formData:$(erpFilterMultiSearchDefaultRemarksForm).serialize() },
		}).success(function (data, status, headers, config){ 
			$scope.defaultRemarkData = data.defaultRemarkList;
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '400'){
				scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};
	
	$scope.closeMultisearch = function(){ 
	    $scope.multiSearchTr=true;
		$scope.multisearchBtn=false;
		$scope.refreshMultisearch();
	};
	
	$scope.refreshMultisearch = function(){ 
	    $scope.filterDefaultRemarkNotes={};
		$scope.filterDefaultRemarksList='';
		$scope.getBranchWiseDefaultRemarks();
	};
	
	$scope.openMultisearch = function(){ 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	};
	
	/**************multisearch end here**********************/	
	$scope.setSelectedOption=function(eqid){
		$scope.equipment_type_id1 = {
			selectedOption: {id: eqid} 
		};
	};

	//show form for method edit and its data
	$scope.showEditForm = function(){
		 $scope.editDetectorFormDiv = false;
		 $scope.addDetectorFormDiv = true;
		 $scope.uploadEquipmentFormDiv = true;
	};
	
	//show form for add new  method 
	$scope.showAddForm = function(){
		 $scope.editDefaultRemarksFormDiv = true;
		 $scope.addDefaultRemarksFormDiv = false;
		 
	};
	
	//****************dropdown filter show/hide******************/
	$scope.searchFilterBtn  = false;
	$scope.searchFilterInput  = true;
	//Show filter
	$scope.showDropdownSearchFilter = function(){ 
		$scope.searchFilterBtn  = true;
		$scope.searchFilterInput  = false;
	};
	//hide filter
	$scope.hideDropdownSearchFilter = function(){ 
		$scope.searchFilterBtn  = false;
		$scope.searchFilterInput  = true;
	};
	//****************/dropdown filter show/hide******************/
	
	
	$scope.resetUploadForm = function(){
		angular.element('#detectorsFile').val('');
		angular.element('.browseFileInput').val('');
	}
	//***************************upload csv**********************************************	
	
	//***************** method SECTION END HERE *****************//

});
