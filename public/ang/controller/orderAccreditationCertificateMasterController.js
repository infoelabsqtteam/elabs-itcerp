app.controller('orderAccreditationCertificateMasterController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {
	
	// sorting variables
	$scope.sortType     = 'company_code'; 			// set the default sort type
	$scope.sortReverse  = false;  					// set the default sort order
	$scope.searchFish   = '';   					// set the default search/filter term
	$scope.successMessage=true;
	$scope.errorMessage=true;
	$scope.listAccreditationCertificates=true;
	$scope.showAccreditationCertificateAddForm=true;
	$scope.showAccreditationCertificateEditForm=false;
	$scope.searchAccCertificate = {};
	$scope.accreditationCertificateForm ={};

	//define empty variables
	$scope.certificatesListData     = '';	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';	
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 	 = true;
	//**********/If DIV is hidden it will be visible and vice versa************
	
	//code used for sorting list order by fields 
	$scope.predicate = 'oac_name';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
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
	};
	//**********/loader show**************************************************
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		if(APPLICATION_MODE)console.clear();
	};
	//*********/Clearing Console********************************************
	
	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 		= message;				
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg 	= true;
		$scope.moveToMsg();
	};
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 		= message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= false;
		$scope.moveToMsg();
	};
	//********** /errorMsgShow************************************************
	
	//*********hideAlertMsg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	};
	//**********/hideAlertMsg********************************************

	//**********confirm box for deleting record******************************************************
	$scope.funConfirmDeleteMessage = function(oac_id){
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
						$scope.funDeleteAccreditationCertificate(oac_id);
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'					
				}
			}
		});
	};
	//********** /confirm box****************************************

	//*****************display division/branch dropdown start*********
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end*******************
	
	//*****************display parent category dropdown code dropdown start****
	$scope.parentCategoryList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'master/get-parent-product-category'
	}).success(function (result) { 
		$scope.parentCategoryList = result.parentCategoryList;
		$scope.clearConsole();
	});
	//*****************display parent category code dropdown end*****************

	//**************** acrreditation certificate status list ****************
	$scope.accreditation_certificate = {};
	$scope.accreditationStatusList = [
		{id: '1', name: 'Active'},
		{id: '2', name: 'Inactive'},
	];
	$scope.accreditation_certificate.oac_status = {selectedOption: { id : $scope.accreditationStatusList[0].id,name : $scope.accreditationStatusList[0].name} };

	//**************** acrreditation multi loaction lab values****************
	$scope.multiLocationLabArrayList = [0,1,2,3,4,5,6,7,8,9];
	$scope.funGetMultiLocationLab = function(){
		
	};
	$scope.funGetMultiLocationLab();
	//****************/acrreditation multi loaction lab values****************
	
	//**************** acrreditation certificate list****************
	$scope.getCertificatesList = function(){
		$scope.loaderShow(); 
		$scope.searchAccCertificate = '';
		$http.post(BASE_URL + "master/list-accreditation-certificate-master", {
		}).success(function (data, status, headers, config) { 
			if(data.certificatesList){
				$scope.searchAccCertificate = {};
				$scope.certificatesListData = data.certificatesList; 
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMessage);
			}
			$scope.clearConsole();
		});
	};

	//**************multisearch and search start here**************************
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	var tempSearchKeyword;
	$scope.getMultiSearch = function(searchKeyword){ 
		tempSearchKeyword = searchKeyword;
		$timeout(function () {
			if(tempSearchKeyword == searchKeyword){
				$scope.searchOrder = searchKeyword;
				$scope.funGetAccCertificateHttpRequest();
			}
		},1000);
	};
	//**************simple search function**************************	
	var tempAccCertificateSearchTerm;
	$scope.getAccCertificateKeywordSearch = function(keyword){
		tempAccCertificateSearchTerm = keyword;
		$timeout(function () {
			if (keyword == tempAccCertificateSearchTerm) {
				$scope.funGetAccCertificateHttpRequest();
			}
		}, 500);
	};
	//*********** http request used in searching**********************

	$scope.funGetAccCertificateHttpRequest = function(){
		$scope.loaderShow(); 
		$http.post(BASE_URL + "master/search-accreditation-certificate-master", {
			data: { formData:$(erpFilterAccreditationCertificateForm).serialize()},
		}).success(function (data, status, headers, config){ 
			if(data.certificatesList){
				$scope.certificatesListData = data.certificatesList; 
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '400'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};
	//*********** close Multi search**********************
	$scope.closeMultisearch = function(){ 
		$scope.multiSearchTr=true;
		$scope.multisearchBtn=false;
		$scope.refreshMultisearch();
	};
	//*********** refresh Multi search**********************	
	$scope.refreshMultisearch = function(){ 
		$scope.searchAccCertificate={};
		$scope.getCertificatesList();
	};
	//*********** open Multi search**********************
	$scope.openMultisearch = function(){ 
		$scope.multiSearchTr=false;
		$scope.multisearchBtn=true;
	};
	//************reset division add form***************
	$scope.resetForm=function(){
		$scope.accreditation_certificate={};
		$scope.accreditationCertificateForm.$setUntouched();
		$scope.accreditationCertificateForm.$setPristine();		
		$scope.edit_accreditation_certificate={};
		$scope.editAccreditationCertificateForm.$setUntouched();
		$scope.editAccreditationCertificateForm.$setPristine();
		$scope.accreditation_certificate.oac_status = {selectedOption: { id : $scope.accreditationStatusList[0].id,name : $scope.accreditationStatusList[0].name} };
	};
	/**************multisearch  and simple search ends here**********************/

	/**************save certificate data **********************/
	$scope.addAccreditationCertificate = function(){
		$scope.loaderShow();
		// post all form data to save
		$http.post(BASE_URL + "master/add-accreditation-certificate-master", {
			data: $(accreditationCertificateForm).serialize() ,
		}).success(function (resData, status, headers, config){	
			$scope.result = resData.returnData;
			if($scope.result.error == '1'){
				$scope.resetForm();
				$scope.getCertificatesList();				
				$scope.successMsgShow($scope.result.message);
			}else{
				$scope.errorMsgShow($scope.result.message);
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

	/**************edit certificate form **********************/
	
	// edit an division and its data
	$scope.edit_accreditation_certificate = {};
	$scope.funEditAccreditationCertificate = function(id){
		if(id){
			$http.post(BASE_URL + "master/edit-accreditation-certificate-master", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (result, status, headers, config) { 
				if(result.returnData.responseData){
					var responseD = result.returnData.responseData;
					$scope.edit_accreditation_certificate = result.returnData.responseData;						
					$scope.edit_accreditation_certificate.oac_division_id = {
						selectedOption: {id: responseD.oac_division_id} 
					};
					$scope.edit_accreditation_certificate.oac_status = {
						selectedOption: {id: responseD.oac_status} 
					};
					$scope.edit_accreditation_certificate.oac_product_category_id = {
						selectedOption: {id: responseD.oac_product_category_id} 
					};
					$scope.oac_id = btoa(responseD.oac_id);
					$scope.showEditForm();
				}else{
					$scope.errorMsgShow(result.error);
				}
				$scope.clearConsole();
			}).error(function (result, status, headers, config) {
				if(status == '500' || status == '400'){
				$scope.errorMsgShow($scope.defaultMessage);
				}
				$scope.clearConsole();
			});
		}
	};	

	//**************update certificate data *****************************
	$scope.funUpdateAccreditationCertificate = function(){		
		$scope.loaderShow();		
		// post all form data to save
		$http.post(BASE_URL + "master/update-accreditation-certificate-master", { 
			data: {formData:$(editAccreditationCertificateForm).serialize() },
		}).success(function (result, status, headers, config) {   	
			if(result.success){ 
				$scope.getCertificatesList();	
				$scope.successMsgShow(result.success);
			}else{
				$scope.errorMsgShow(result.error);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (result, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMessage);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}); 
	};
	//**************/update certificate data *****************************

	//************Delete  Accreditation Certificate from the database****************
	$scope.funDeleteAccreditationCertificate = function(oac_id){   
		if(oac_id){			 
			$scope.loaderShow();
			$http.post(BASE_URL + "master/delete-accreditation-certificate-master", {
				data: {"_token": "{{ csrf_token() }}","id": oac_id }
			}).success(function (resData, status, headers, config) {  
				if(resData.success){ 
					$scope.getCertificatesList();
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
	//************/Delete Accreditation Certificate from the database****************

	//***** show certicate add form**************************
	$scope.showAddForm = function(){		
		$scope.hideAlertMsg();	
		$scope.showAccreditationCertificateEditForm=false;
		$scope.hideDivisionAddForm=false;
	};
	//*****/show certicate add form**************************

	//***** show certicate edit form**************************
	$scope.showEditForm = function(){	
		$scope.hideAlertMsg();	
		$scope.showAccreditationCertificateEditForm=true;
		$scope.showAccreditationCertificateAddForm=false;
	};
	//*****/show certicate edit form**************************

	//***** hide certicate edit form**************************
	$scope.hideEditForm = function(){		
		$scope.hideAlertMsg();
		$scope.showAccreditationCertificateEditForm=false;
		$scope.showAccreditationCertificateAddForm=true;
	};
	//*****/hide certicate edit form**************************

	//***** hide certicate add form**************************
	$scope.hideAddForm = function(){	
		$scope.hideAlertMsg();				
		$scope.showAccreditationCertificateEditForm=false;
		$scope.hideDivisionAddForm=true;
	};
	//*****/hide certicate add form**************************
});
