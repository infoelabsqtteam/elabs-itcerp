app.controller('departmentController', function($scope, $http, BASE_URL, $window,$ngConfirm){
	
	//sorting variables
	$scope.sortType     		= 'department_code'; 			 // set the default sort type
	$scope.sortReverse  		= false; 				        // set the default sort order
	
	//define empty variables
	$scope.deptdata  		= '';
	$scope.dept_name 		='';
	$scope.dept_code 		='';
	$scope.dept_id   		='';
	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup 	= '';
	$scope.linkedWithProductCategory = {};
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
	//**********If DIV is hidden it will be visible and vice versa*******
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 	 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	//**********/If DIV is hidden it will be visible and vice versa******
	
	//**********successMsgShow*******************************************
	$scope.uploadMsgShow = function(uploadMsg){
		$scope.uplodedMessageHide    = false;
		$scope.notUplodedMessageHide = false;	
		$scope.notUplodedMessageHide = false;
		$scope.uplodedMessage    = uploadMsg.uploaded;
		$scope.notUplodedMessage = uploadMsg.notUploaded;	
		$scope.notUplodedMessage = uploadMsg.duplicate;	
	}
	//********** /successMsgShow******************************************
	
	//**********hide upload Alert Msg*************
	$scope.hideUploadAlertMsg = function(){
		$scope.uplodedMessageHide    = true;
		$scope.notUplodedMessageHide = true;	
		$scope.notUplodedMessageHide = true;	
	}
	//********** /hide upload Alert Msg************************************	
	
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
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//********** /hide Alert Msg**********************************************
	
	//code used for sorting list order by fields 
	$scope.predicate = 'department_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//*****************display company code dropdown start*****************/	
	$scope.companyCodeList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'company/get-companycodes'
		}).success(function (result) {
			$scope.company_id = result.id; 
			$scope.company_name = result.name; 
			$scope.clearConsole();
		});
	//*****************display company code dropdown end*****************/
	
	//*****************display department types dropdown start*****************/
	$scope.deptTypesList =[];
	$http({
		method: 'GET',
		url: BASE_URL +'department/generate-department-types'
	}).success(function (result) {
		$scope.deptTypesList = result.deptTypesList;
		$scope.clearConsole();
	});
	//*****************display department types dropdown end*****************/
	
	//*****************generate unique code******************
	$scope.department_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'department/generate-department-number'
		}).success(function (result){
			$scope.department_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************
	
	//**** Getting parent Product Category********************
	$scope.funGetParentCategory = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'master/get-parent-product-category'
		}).success(function (result) {
			$scope.parentCategoryList = result.parentCategoryList;
			$scope.clearConsole();
		});
	};
	//**** Getting parent Product Category********************
	
	//***************** department SECTION START HERE *****************/	
	$scope.addDepartment = function(){
		
		if(!$scope.departmentForm.$valid)return; 
		$scope.loaderShow();
		
		// post all form data to save
		$http.post(BASE_URL + "departments/add-department", {
		    data: {formData:$("#add_department_form").serialize() },
		}).success(function (data, status, headers, config) {	
			if(data.success){ 
				//reload the all departments
				$scope.getDepartments();			
				//reset form
				$scope.resetDept();
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
		
	$scope.resetDept=function(){
		$scope.department = {};	
		$scope.department_type = {};	
		$scope.departmentForm.$setUntouched();
		$scope.departmentForm.$setPristine();
	}
	
	// function is used to fetch the list of departments 	
	$scope.getDepartments = function(){
		
		$scope.generateDefaultCode();
		
		$http.post(BASE_URL + "departments/get-departments", {
		}).success(function (data, status, headers, config) { 
			$scope.deptdata = data.departmentsList;			
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
	$scope.getMultiSearch = function(){
		
		$scope.searchDepartments = '';
		
		$http.post(BASE_URL + "departments/get-departments-multisearch", {
			data: { formData:$scope.searchDept },
		}).success(function (data, status, headers, config){ 
			$scope.deptdata = data.departmentsList;
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '400'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};
	
	$scope.closeMultisearch = function(){
		
		$scope.multiSearchTr=true;
		$scope.multisearchBtn=false;
		$scope.refreshMultisearch();
	}
	
	$scope.refreshMultisearch = function(){
		
		$scope.searchDept={};
		$scope.getDepartments();
	}
	
	$scope.openMultisearch = function(){
		
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
						$scope.deleteDepartment(id);
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
	
	// Delete department from the database
	$scope.deleteDepartment = function(id){   
		if(id){
			$scope.loaderShow();	
			$http.post(BASE_URL + "departments/delete-department", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (result, status, headers, config) {  
				if(result.success){
					//reload the all employee
					$scope.getDepartments();
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
	
	//edit an department and its data
	$scope.editDepartment = function(id){
		if(id){
			$scope.department_id1=null;
			$http.post(BASE_URL + "departments/edit-department", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) { 			
				if(data.returnData.responseData){
					var responseD=data.returnData.responseData;
					$scope.company_id1 = responseD.company_id; 
					$scope.company_name1 = responseD.company_name;  
					$scope.department_type1 = {
						selectedOption: { id: responseD.department_type, name: responseD.department_type} 
					};
					$scope.showEditForm(); 	
					$scope.dept_name = responseD.department_name; 			
					$scope.dept_code = responseD.department_code;				
					$scope.department_id1 = btoa(responseD.department_id);				
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
	
	// update an department and its data
	$scope.updateDepartment = function(){
		
		if(!$scope.editDepartmentForm.$valid)return;
		$scope.loaderShow();	
		
		// post all form data to save
		$http.post(BASE_URL + "departments/update-department", { 
		    data: {formData:$("#edit_department_form").serialize() },
		}).success(function (data, status, headers, config) { 	
			if(data.success){ 
				//reload the all companies
				$scope.getDepartments();
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
	
	$scope.hideDepartmentAddForm = false;
	$scope.hideDepartmentEditForm = true;
	$scope.showEditForm = function(){
		$scope.IsVisiableSuccessMsg 	 = true;
		$scope.IsVisiableErrorMsg 		 = true;
		$scope.hideDepartmentEditForm = false;
		$scope.hideDepartmentAddForm = true;
	};
	$scope.hideEditForm = function(){
		$scope.IsVisiableSuccessMsg 	 = true;
		$scope.IsVisiableErrorMsg 		 = true;
		$scope.hideDepartmentEditForm = true;
		$scope.hideDepartmentAddForm = false;
	};
	$scope.hideAddForm = function(){
		$scope.IsVisiableSuccessMsg 	 = true;
		$scope.IsVisiableErrorMsg 		 = true;
		$scope.hideDepartmentEditForm = false;
		$scope.hideDepartmentAddForm = true;
	};
	/***************** department SECTION END HERE *****************/
	
	
	/***************** EMPLOYEE Upload Section Start Here*****************/
	$scope.getDepartmentsPreview = function(){};
	
	$scope.showUploadForm = function(){ 
		$scope.departmentsListHeader='';
		$scope.departmentsListData='';
		angular.element('#departmentCSVForm')[0].reset();
		angular.element('#uploadDepartmentPreviewListing').hide();
		angular.element('#uploadDepartmentForm').show();
	};
	$scope.hideUploadForm = function(){  
		angular.element('#uploadDepartmentPreviewListing').show();
		angular.element('#uploadDepartmentForm').hide();
	};
	$scope.cancelUpload = function(){ 
		$scope.departmentsListHeader='';
		$scope.departmentsListData='';
		$scope.hideAlertMsg();	
		angular.element('#departmentCSVForm')[0].reset();
		angular.element('#uploadDepartmentPreviewListing').hide();
		angular.element('#uploadDepartmentForm').show();
	};
	
	//function is used to upload department csv in preview table
	$(document).on('click', '#departmentUploadPreviewBtn',function(e){
		
		$scope.loaderShow();
		e.preventDefault();
		var formdata = new FormData();
		formdata.append('department',$('#departmentFile')[0].files[0]);
		
		$.ajax({
			url: BASE_URL + "departments/upload-preview",
			type: "POST", 
			data: formdata,
			contentType: false,
			cache: false,  
			processData:false,
			success: function(res){
				var resData=res.returnData; 
				if(resData.success){  
					$scope.departmentsListHeader=resData.newheader;
					$scope.departmentsListDataDisplay=resData.dataDisplay;
					$scope.departmentsListDataSubmit=resData.dataSubmit;
					$scope.numberOfSubmitedRecords=resData.numberOfSubmitedRecords;
					$scope.hideUploadForm();
					$scope.successMsgShow(resData.success);	
					$scope.clearConsole();
					$scope.loaderHide();
					$scope.$apply();
				}else if(resData.error){
				    $scope.errorMsgShow(resData.error);
					$scope.$apply();
					$scope.clearConsole();
					$scope.loaderHide();
				}
			}
		});
	});
	
	$scope.departmentUploadCSV = function(){
		
		$scope.loaderShow();
		
		// post all form data to save
		$http.post(BASE_URL + "departments/save-uploaded-departments", {
			data: {formData:$scope.departmentsListDataSubmit },
		}).success(function (res, status, headers, config){		
			var resData=res.returnData;
			if(resData.success){
				$scope.getDepartments();
				$scope.showUploadForm(); 
				if(resData.upload){
					$scope.hideAlertMsg();					 
					$scope.uploadMsgShow(resData.upload);					  
				}else{
					$scope.successMsgShow(resData.success); 
				}					 
			}else{
			       $scope.errorMsgShow(resData.error);
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
	/***************** department Upload SEction SECTION END HERE *****************/
	
	//***********linking of Department with Product Category***************************
	$scope.funGetLinkedWithProductCatDetail = function(){
		$scope.loaderShow();
		var http_para_string = {};
		$http({
			url: BASE_URL + "master/department/get-department-product-category-detail",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			if(result.error == 1){
				$scope.linkedWithProductCateList = result.linkedWithProductCateList;
				$(linkedWithProductCategoryPopupWindow).modal('show');
			}else{
				$(linkedWithProductCategoryPopupWindow).modal('hide');
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//***********/linking of Department with Product Category***************************
	
	//***********Updating of linked Department Detail***************************
	$scope.funUpdateLinkedWithProductCategory = function(){
		$scope.loaderShow();
		var http_para_string = {formData : $(linkedWithProductCategoryPopupForm).serialize()};
		$http({
			url: BASE_URL + "master/department/update-department-product-category-detail",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			if(result.error == 1){
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$(linkedWithProductCategoryPopupWindow).modal('hide');
			$scope.linkedWithProductCategory = {};
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//***********Updating of linked Department Detail***************************
	
});