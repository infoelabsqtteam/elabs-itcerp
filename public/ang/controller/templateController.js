app.controller('templateController', function($scope,$timeout,$http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.templatesData		= '';

	//sorting variables
	$scope.sortType     		= 'ortd_id';     	//Set the default sort type
	$scope.sortReverse  		= false;             	//Set the default sort order
	$scope.searchFish   		= '';    		//Set the default search/filter term
	$scope.EquipmentTypeId		= '0';
	
	$scope.uploadEquipmentFormDiv 	= true;			//Set the default search/filter term
	$scope.IsVisiableSuccessMsg	= true;
	$scope.IsVisiableErrorMsg	= true;
	$scope.addFormDiv     		= false;
	$scope.editFormDiv    		= false;
	$scope.showTemplatesList 	= true;	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.erpAddTemplateForm 	= {};
	$scope.erpEditTemplateForm 	= {};
	$scope.searchTemplate		= {};

	//**********scroll to top function**********addForm
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
	
	//**********loader show****************************************************
	$scope.loaderMainShow = function(){
		angular.element('#global_loader_onload').fadeIn('slow');
	};
	//**********/loader show**************************************************
    
	//**********loader show***************************************************
	$scope.loaderMainHide = function(){
		angular.element('#global_loader_onload').fadeOut('slow');
	};
	//**********/loader show**************************************************
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		if(APPLICATION_MODE)console.clear();
	};
	//*********/Clearing Console********************************************
	
	//**********Read/hide More description********************************************
	$scope.toggleDescription = function(type,id) {
		angular.element('#'+type+'LimitedText-'+id).toggle();
		angular.element('#'+type+'FullText-'+id).toggle();
	};
	//*********/Read More description********************************************	
	
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
	
	//*********reset Form************************************************
	$scope.resetForm = function(){
		$scope.addTemplate.header_content = '';
		$scope.addTemplate.footer_content = ''; 
		$scope.editTemplate = {}; 
	};
	//********/reset Form************************************************
	
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
						$scope.deleteReportTemplates(id);
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
	
	//*********Code used for sorting list order by fields**************************
	$scope.predicate = 'detector_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//*********/Code used for sorting list order by fields**************************
	
	//*****************display division dropdown start*************************
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end**************************
	
	//*****************display parent category dropdown code dropdown start fungetParentCategory()****
	$scope.parentCategoryList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'master/get-parent-product-category'
	}).success(function (result) { 
		$scope.parentCategoryList = result.parentCategoryList;
		$scope.clearConsole();
	});
	
	//*****************display parent category code dropdown end*****************
	$scope.addForm = function(){
		$scope.addTemplate = {};
		$scope.showTemplatesList = false;
		$scope.addFormDiv = true;
		$scope.editFormDiv = false;
	};
	
	//**********************ALL TEMPLATES LISTING*********************************
	$scope.getTemplatesList = function(){
		$scope.loaderShow();
		$http.post(BASE_URL + "master/get-templates-list",{
		}).success(function (data, status, headers, config) {			
			$scope.templatesData = data.reportTemplateDetailList;
			$scope.loaderHide(); 
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};
	//**********************/ALL TEMPLATES LISTING*********************************
	
	//***********Convert templates htmls into view*********************************
	$scope.funViewTemplate = function(type){
		$scope.$reportTemplateViewData = {};
		if (type=="header") {
			$scope.headerData = $('#headerContent').val();
			$scope.reportTemplateViewHeaderData = $scope.headerData;
		}else{
			$scope.footerData = $('#footerContent').val();
			$scope.reportTemplateViewFooterData = $scope.footerData;
		}
	};	
	$scope.back= function(){
		$scope.addFormDiv=false;
		$scope.editFormDiv=false;
		$scope.showTemplatesList=true;
	};
	//**********/Convert templates htmls into view*********************************
	
	//*****************Adding of Templates*******************************************	
	$scope.funAddTemplateContent= function(){
		
		$scope.loaderMainShow();
		
		//Post all form data to save
		$http.post(BASE_URL + "master/templates/add-report-template", {
			data: {formData:$(erpAddTemplateForm).serialize() },
		}).success(function (data, status, headers, config) {
			if(data.success){
				$scope.resetForm();
				$scope.getTemplatesList();
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShow(data.error);
			}
			$scope.loaderMainHide(); 
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide(); 
			$scope.clearConsole();
		});
	};
	//****************/Adding of Templates*******************************************
	
	/*****edit template header footer content 15-05-2018***/
	$scope.funEditTemplateContent = function(id){
		if(id){
			$scope.loaderMainShow();
			$http.post(BASE_URL + "master/templates/edit-report-template", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){
					$scope.addFormDiv     = false;
					$scope.editFormDiv    = true;
					$scope.showTemplatesList = false;	
					$scope.editTemplate = data.responseData;
					$scope.funCheckRequired(data.responseData.template_type_id);
					$scope.editTemplate.template_type_id = {
						selectedOption: {id: data.responseData.template_type_id} 
					};
					$scope.editTemplate.division_id = {
						selectedOption: {id: data.responseData.division_id} 
					};  
					$scope.editTemplate.product_category_id = {
						selectedOption: { id: data.responseData.product_category_id } 
					};				
				}else{
					$scope.errorMsgShow(data.error);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			});
		}
	};
	
	//****************update template and its data************************************
	$scope.funUpdateReportTemplate = function(){
	
		$scope.loaderMainShow();
			
		$http.post(BASE_URL + "master/templates/update-report-template", { 
			data: {formData:$(erpEditTemplateForm).serialize() },
		}).success(function (data, status, headers, config) { 
			if(data.success){
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShow(data.error);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}); 
	};
	//***************/update template and its data************************************
	
	//***************Delete template content from the database*************************
	$scope.deleteReportTemplates = function(id){ 
		if(id){
			$scope.loaderMainShow(); 
			$http.post(BASE_URL + "master/templates/delete-report-template", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.success){
					$scope.getTemplatesList();
					$scope.successMsgShow(data.success);
				}else{
					$scope.errorMsgShow(data.error);
				}
				$scope.loaderMainHide(); 
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.loaderMainHide(); 
				$scope.clearConsole();
			});
		}
	};
	//**************/Delete template content from the database*************************
		
	//**************Multisearch*********************************************************
	$scope.getMultiSearch=function(){
	       $timeout(function (){$scope.callAtTimeout();}, 1000);
	};
	$scope.multiSearchTr  = true;
	$scope.multisearchBtn = false;	
	$scope.callAtTimeout = function(){
		$scope.loaderShow(); 
		$http.post(BASE_URL + "master/templates/get-report-template-multisearch", {
			data: { formData:$(erpFilterMultiSearchTemplateForm).serialize() },
		}).success(function (data, status, headers, config){ 
			$scope.templatesData = data.reportTemplateDetailList;
			$scope.loaderHide(); 
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
		$scope.searchTemplate={};
		$scope.getTemplatesList();
	};
	$scope.openMultisearch = function(){ 
		$scope.multiSearchTr=false;
		$scope.multisearchBtn=true;
	};
	//*************/Multisearch*********************************************************
	
	//*****************display template type start*************************
	$scope.templatesTypeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'master/template/get-template-type-list'
		}).success(function (result) {
			$scope.templatesTypeList = result.templateTypeList;
			$scope.template_type_id = {
				selectedOption: {id: result.templateTypeList[0].id} 
			};
			$scope.funCheckRequired($scope.template_type_id.selectedOption.id);
			$scope.clearConsole();
	});
	//*****************display division dropdown end**************************
	
	//*********Show/Hide required fields******************************************	
	$scope.funCheckRequired = function(templateTypeId){
		if(templateTypeId == '1'){	  //Report Template
			$scope.requiredBranch	  = true;
			$scope.requiredDepartment = true;
			$scope.requiredHeader     = true;
			$scope.requiredFooter     = true;
			$scope.requiredEditBranch     = true;
			$scope.requiredEditDepartment = true;
			$scope.requiredEditHeader     = true;
			$scope.requiredEditFooter     = true;
		}else if(templateTypeId== '2'){	  //Invoice Template
			$scope.requiredBranch	  = true;
			$scope.requiredDepartment = false;
			$scope.requiredHeader	  = true;
			$scope.requiredFooter	  = false;
			$scope.requiredEditBranch     = true;
			$scope.requiredEditDepartment = false;
			$scope.requiredEditHeader     = true;
			$scope.requiredEditFooter     = false;
		}else if(templateTypeId == '3'){	  //Common Template
			$scope.requiredBranch	  = false;
			$scope.requiredDepartment = false;
			$scope.requiredHeader	  = true;
			$scope.requiredFooter	  = false;
			$scope.requiredEditBranch     = false;
			$scope.requiredEditDepartment = false;
			$scope.requiredEditHeader     = true;
			$scope.requiredEditFooter     = false;
		}else if(templateTypeId == '4' || templateTypeId == '5' || templateTypeId == '6' || templateTypeId == '7'){	  //Mail Template
			$scope.requiredBranch	  = true;
			$scope.requiredDepartment = true;
			$scope.requiredHeader	  = false;
			$scope.requiredFooter	  = true;
			$scope.requiredEditBranch     = true;
			$scope.requiredEditDepartment = true;
			$scope.requiredEditHeader     = false;
			$scope.requiredEditFooter     = true;
		}else{
			$scope.requiredBranch	  = false;
			$scope.requiredDepartment = false;
			$scope.requiredHeader	  = false;
			$scope.requiredFooter	  = false;
			$scope.requiredEditBranch     = false;
			$scope.requiredEditDepartment = false;
			$scope.requiredEditHeader     = false;
			$scope.requiredEditFooter     = false;
		}
	}
	//*********/Show/Hide required fields******************************************	
});