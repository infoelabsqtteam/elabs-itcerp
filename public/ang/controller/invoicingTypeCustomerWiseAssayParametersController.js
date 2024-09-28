app.controller('invoicingTypeCustomerWiseAssayParametersController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	//$scope.currentModule 				= 15;   //variable used in tree.js for tree popup
	$scope.currentModule 				= 19;   //variable used in tree.js for country tree popup
	$scope.customerWiseParameterRateList 		= '';
	$scope.successMessage 					= '';
	$scope.errorMessage   					= '';
	$scope.cirCustomerID       			= '0';
	$scope.cirCustomerCityID       		= '0';
	$scope.defaultMsg  				= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.addCustomerWiseAssayParametersRate	= {};
	$scope.editCustomerWiseAssayParametersRate    	= {};
	$scope.searchCustomerWiseAssayParameter         = {};
	
	//sorting variables
	$scope.sortType     				= 'cir_id';     // set the default sort type
	$scope.sortReverse  				= false;        // set the default sort order
	$scope.searchFish   				= '';           // set the default search/filter term
	$scope.productCategoryID 			= '0'; 
	$scope.customerList 				= [];
	$scope.erpAddCustomerWiseParametersRateForm 	= '';
	$scope.erpEditCustomerWiseParametersRateForm 	= '';
	$scope.deptID 					= '';
	
	//**********scroll to top function******************************************
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	};
	//**********/scroll to top function******************************************
	
	//**********loader show****************************************************
	$scope.loaderShow = function(){
		angular.element('#global_loader').fadeIn();
	};
	//**********/loader show**************************************************
    
	//**********loader show***************************************************
	$scope.loaderHide = function(){
		angular.element('#global_loader').fadeOut();
	};
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
	
	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg 	    = true;
	$scope.IsVisiableErrorMsg 	    = true;
	$scope.IsVisiableSuccessMsgPopup    = true;
	$scope.IsVisiableErrorMsgPopup 	    = true;
	$scope.listCustomerWiseAssayParameterRateDiv  = true;
	$scope.addCustomerWiseAssayParametersRateDiv  = false;
	$scope.editCustomerWiseAssayParametersRateDiv = false;
	$scope.searchCustomerWiseAssayParameter.cir_product_category_id = '';
	//**********/If DIV is hidden it will be visible and vice versa************
	
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
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	};
	//********** /hide Alert Msg**********************************************
	
	//**********successMsgShowPopup**************************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		= message;				
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	= true;
		$scope.moveToMsg();
	};
	//********** /successMsgShowPopup************************************************
	
	//**********errorMsgShowPopup**************************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	};
	//********** /hideAlertMsgPopup************************************************
	
	//**********hideAlertMsgPopup*************
	$scope.hideAlertMsgPopup = function(){
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
	};
	//********** /hideAlertMsgPopup**********************************************
	
	//**********confirm box******************************************************
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	};
	//********** /confirm box****************************************************
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id,cir_customer_id,cir_department_id){
		$ngConfirm({
			title     : false,
			content   : defaultDeleteMsg,					 //Defined in message.js and included in head
			animation : 'right',
			closeIcon : true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme   : 'bootstrap',
			buttons   : {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funDeleteCustomerWiseAssayParameterRate(id,cir_customer_id,cir_department_id);
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
	
	//**********navigate Form*********************************************
	$scope.navigateFormPage = function(type){
		if(type == 'add'){
			$scope.listCustomerWiseAssayParameterRateDiv  = false;
			$scope.addCustomerWiseAssayParametersRateDiv  = true;
			$scope.editCustomerWiseAssayParametersRateDiv = false;
			$scope.customerListData = {};
		}else if(type == 'edit'){
			$scope.listCustomerWiseAssayParameterRateDiv  = false;
			$scope.addCustomerWiseAssayParametersRateDiv  = false;
			$scope.editCustomerWiseAssayParametersRateDiv = true;
		}else{
			$scope.listCustomerWiseAssayParameterRateDiv  = true;
			$scope.addCustomerWiseAssayParametersRateDiv  = false;
			$scope.editCustomerWiseAssayParametersRateDiv = false;
		}
		$scope.resetButton();
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************
	
	//**********Back Button*********************************************
	$scope.backButton = function(){
	};
	//**********/Back Button********************************************

	//**********Reset Button*********************************************
	$scope.resetButton = function(){
		$scope.customerListData   		    = [];
		$scope.testParametersList   		    = [];
		$scope.parentCategoryLevelZeroList 	    = [];
		$scope.productCategoryLevelOneList  	    = [];
		$scope.productCategoryLevelTwoList   	    = [];		
		$scope.customerWiseParametersRateAddListing = [];
		$scope.customerWiseParameterRateEditListing = [];
		$scope.parameterEquipmentList 		    = [];
		$scope.detectorList 			    = [];
		$scope.addCustomerWiseAssayParametersRate   = {};
		$scope.addCustomerWiseAssayParametersRate   = {};
		$scope.editCustomerWiseAssayParametersRate  = {};
		$scope.editCustomerWiseAssayParametersRate  = {};
		$scope.showAutoSearchParameterList  	    = false;	
	};
	//**********/Reset Button*********************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	};
	//*********/Clearing Console*********************************************
	
	//************code used for sorting list order by fields*****************
	$scope.predicate = 'cir_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************
	
	//*****************state/city tree list data******************************
	$scope.stateCityTreeViewList = [];
	$scope.funGetStateCityTreeViewList = function(){ 	
		$http({
			method: 'POST',
			url: BASE_URL +'get-state-city-tree-view'
		}).success(function (result) {
			if(result.stateCityTreeViewList){  
				$scope.stateCityTreeViewList = result.stateCityTreeViewList;
			}
			$scope.clearConsole();
		});
	}
	//*****************/state/city tree list data**************************************
	
	//**********state/city tree popup***************************************************
	$scope.funShowStateCityTreeViewPopup = function(currentModule){  
	   $scope.currentModule=currentModule; 
	   $('#assayStateCityTreeViewPopup').modal('show');
	}
	//**********/state/city tree popup***************************************************
	
	//*******************filter state/city from tree view********************************
	$scope.funGetSelectedStateId = function(selectedNode){  
		$scope.funGetCustomerOnStateChange(selectedNode.state_id);
		$('#assayStateCityTreeViewPopup').modal('hide');
		$scope.currentModule = 15;
	}
	//*****************/filter state/city from tree view*********************************
	
	//*****************display division dropdown start 12April,2018*****************
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.defaultDivisionId = {selectedOption:{id:result[0].id }};
		$scope.clearConsole();
	});
	//*****************display division dropdown end*****************
	
	//**********state/city tree popup***************************************************
	$scope.funSetSelectedCustomer = function(customer_id){  
		$scope.cirCustomerID 			    = customer_id;
		$scope.parentCategoryList  		    = [];
		$scope.parametersCategoryList 		    = [];
		$scope.customerWiseParametersRateAddListing = [];
		$scope.customerWiseParameterRateEditListing = [];
		$scope.addCustomerWiseParametersRateBottom  = {};
		$scope.funGetParentCategory();
	}
	//**********/state/city tree popup***************************************************
	
	//*****************display parent category dropdown code dropdown start**************
	$scope.funGetParentCategory = function(){	
		$http({
			method: 'POST',
			url: BASE_URL +'master/get-parent-product-category'
		}).success(function (result) { 
			$scope.parentCategoryLevelZeroList = result.parentCategoryList;
		});
	};
	//*****************display parent category code dropdown end************************
	
	//*****************display parent category dropdown code dropdown start**************
	$scope.funGetRunningTimeList = function(){	
		$http({
			method: 'POST',
			url: BASE_URL +'master/invoicing/customer-wise-assay-parameter-rates/get-running-time-list'
		}).success(function (result) { 
			$scope.runningTimeList = result.runningTimeList;
		});
	};
	//*****************display parent category code dropdown end************************
	
	//*****************display Product category Level One*******************************
	$scope.funProductCategoryLevelOne = function(p_category_id){
		$scope.parentProductCategoryID = p_category_id;
		$http({
			method: 'POST',
			url: BASE_URL +'master/invoicing/customer-wise-assay-parameter-rates/get-product-category-level-one/'+p_category_id,
		}).success(function (result) { 
			$scope.productCategoryLevelOneList = result.productCategoryLevelOneList;
			$scope.funGetParameterCatgeoryList(p_category_id);
		});
	};
	//****************/display Product category Level One*******************************
	
	//*****************display Product category Level One*******************************
	$scope.funProductCategoryLevelTwo = function(p_category_id){	
		$http({
			method: 'POST',
			url: BASE_URL +'master/invoicing/customer-wise-assay-parameter-rates/get-product-category-level-two/'+p_category_id,
		}).success(function (result) { 
			$scope.productCategoryLevelTwoList = result.productCategoryLevelOneList;
		});
	};
	//****************/display Product category Level One*******************************
	
	//*****************display Parameter category dropdown end***************************
	$scope.funGetParameterCatgeoryList = function(product_category_id){		
		$http({
			method: 'POST',
			url: BASE_URL +'master/invoicing/customer-wise-assay-parameter-rates/parameter-category-list/'+product_category_id,
		}).success(function (result, status, headers, config) {
			$scope.parametersCategoryList = result.testParameterList;
			$scope.clearConsole();
		});
	}
	//****************/display Parameter category dropdown end***************************
	
	//************/show tree pop up*******************************************
	$scope.showAssayParameterCatTreeViewPopUp = function(currentModule){
		$scope.currentModule = currentModule;
		$scope.addCustomerWiseAssayParametersRate.test_parameter_id = '';
		$scope.addCustomerWiseAssayParametersRate.test_parameter_name = '';
		$('#assayParameterCategoryPopup').modal('show');		
	}
	//**********/show tree pop up********************************************
	
	//******************get parameters by parameter_category_id selected from parameter popup*****************
	$scope.funSetSelectedProductCategory = function(node){
		$scope.globalParameterCategoryID = node.test_para_cat_id;		
		$('#assayParameterCategoryPopup').modal('hide');
	};
	//*****************/get parameters by parameter_category_id selected from parameter popup******************
	
	//******************get parameters by parameter_category_id selected from parameter popup*****************
	$scope.funGetParameterListFromParaCategory = function(parameter_category_id){
		$scope.globalParameterCategoryID = parameter_category_id;
		$scope.addCustomerWiseAssayParametersRate.test_parameter_id = '';
		$scope.addCustomerWiseAssayParametersRate.test_parameter_name = '';
		$scope.funGetEquipmentTypeList();
	};
	//*****************/get parameters by parameter_category_id selected from parameter popup******************
	
	//****************function Get Product Parameter List**************************
	$scope.getAutoSearchAssayParameterMatches = function(parameter_name,test_parameter_cat_id){
		if(parameter_name && test_parameter_cat_id){
			$scope.parameterNameList = [];
			$http({
				method: 'POST',
				url: BASE_URL +'master/invoicing/customer-wise-assay-parameter-rates/get-test-parameter-list/'+parameter_name+'/'+test_parameter_cat_id
			}).success(function (result) {	
				$scope.testParametersList = result.testParametersList;
				$scope.showAutoSearchParameterList = true;
				$scope.clearConsole();
			});
		}
	};
	//****************/function Get Product Parameter List**************************
	
	//set parameter value when user selecet from auto search list
	$scope.funSetAutoSelectedAssayParameter = function(autoTestParameterId,autoTestParameterName,formType){
		if(formType == 'add'){
			$scope.addCustomerWiseAssayParametersRate.test_parameter_id = autoTestParameterId;
			$scope.addCustomerWiseAssayParametersRate.test_parameter_name = autoTestParameterName;
		}
		$scope.showAutoSearchParameterList = false;
	}
	
	//*****************get equipments list according to parameter name*****************//	
	$scope.funGetEquipmentTypeList =function(test_parameter_id){	
		$scope.parameterEquipmentList = [];
		$http({
			method: 'POST',
			url: BASE_URL +'master/invoicing/customer-wise-assay-parameter-rates/get-equipment-accto-parameter-list/'+test_parameter_id,
		}).success(function (result, status, headers, config){
			$scope.parameterEquipmentList = result.parameterEquipmentList;
			$scope.clearConsole();
		});		
	}  
	//*****************/get equipments list according to parameter name*****************//	
	
	//**********funGetCustomerWiseAssayParameterRates***********************************
	$scope.funGetDetectorAccToEquipment = function(equipment_type_id,parentProductCategoryId){
		if(equipment_type_id){
			$scope.detectorList = [];
			$http({
				method: 'POST',
				url: BASE_URL +'master/invoicing/customer-wise-assay-parameter-rates/get-detector-accto-equipment-list/'+equipment_type_id+'/'+parentProductCategoryId,
			}).success(function (result, status, headers, config){
				$scope.detectorList = result.detectorList;
				$scope.clearConsole();
			});
		}
	};
	//**********/funGetCustomerWiseAssayParameterRates**********************************
	
	//**********funGetCustomerWiseAssayParameterRates***********************************
	$scope.withDetectorContentDiv = $scope.withoutDetectorContentDiv = false;
	$scope.funCheckDetechtorAvailability = function(is_detector_condition){
		if(is_detector_condition == 1){
			$scope.withDetectorContentDiv = true;
			$scope.withoutDetectorContentDiv = false;
		}else if(is_detector_condition == 2){
			$scope.withDetectorContentDiv = false;
			$scope.withoutDetectorContentDiv = true;
		}
	};
	//**********/funGetCustomerWiseAssayParameterRates**********************************
	
	//***************** Adding of Branch Wise DebitNote ***************************
	$scope.funAddCustomerWiseAssayParametersRate = function(){
		
		if($scope.newcustomerWiseAssayParametersRateFormflag)return;		
		$scope.newcustomerWiseAssayParametersRateFormflag = true;
		$scope.loaderMainShow();
		var http_para_string = {formData : $(erpAddCustomerWiseAssayParametersRateForm).serialize()};
		
		$http({
			url: BASE_URL + "master/invoicing/customer-wise-assay-parameter-rates/add-customer-wise-assay-parameter-rates",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config) {
			$scope.newcustomerWiseAssayParametersRateFormflag = false;
			$scope.showAutoSearchParameterList = false;
			if(result.error == 1){
				$scope.funGetCustomerWiseAssayParameterList();
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newcustomerWiseAssayParametersRateFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*****************/Adding of Branch Wise DebitNote ***************************
	
	//****************function Get Product Parameter List**************************
	$scope.funGetCustomerWiseAssayParameterList = function(cir_customer_id,cir_department_id){
		
		$scope.cirCustomerID   = cir_customer_id;
		$scope.cirDepartmentID = !cir_department_id ? '2' : cir_department_id ;
		$scope.loaderMainShow();
		$scope.hideAlertMsg();
		var http_para_string = {formData : 'cir_customer_id='+$scope.cirCustomerID+'&'+'cir_product_category_id='+$scope.cirDepartmentID};
		
		$http({
			url: BASE_URL + "master/invoicing/customer-wise-assay-parameter-rates/get-customer-wise-assay-parameter-rates",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			$scope.customerWiseAssayParameterRateList = result.returnData;
			$scope.searchCustomerWiseAssayParameter.cir_product_category_id = {id : cir_department_id};
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config){
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};	
	//****************function Get Product Parameter List***********************************************
	
	//****************Edit Products of selected state *************************************
	$scope.funEditCustomerWiseAssayParametersRate = function(cir_customer_id,cir_department_id){ 
		if(cir_customer_id && cir_department_id){
			
			$scope.cirCustomerID   = cir_customer_id;
			$scope.cirDepartmentID = cir_department_id;
			var http_para_string = {formData : 'cir_customer_id='+cir_customer_id+'&cir_department_id='+cir_department_id};
			$scope.loaderMainShow();
			
			$http({
				url: BASE_URL + "master/invoicing/customer-wise-assay-parameter-rates/edit-customer-wise-assay-parameter-rates",
				method: "POST",
				data: http_para_string,
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.listCustomerWiseAssayParameterRateDiv  = false;
					$scope.addCustomerWiseAssayParametersRateDiv  = false;
					$scope.editCustomerWiseAssayParametersRateDiv = true;
					$scope.customerWiseAssayParameterRateEditListing = result.returnData;
					$scope.customerListData 		 	 = result.customersList;
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config){
				$scope.loaderHide();
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************/Edit Products of selected state ***********************************
	
	//***************** Adding of Branch Wise DebitNote **********************************
	$scope.funUpdateCustomerWiseAssayParametersRate = function(cir_customer_id,cir_department_id){
		
		if($scope.newErpEditCustomerWiseAssayParameterRateFormflag)return;		
		$scope.newErpEditCustomerWiseAssayParameterRateFormflag = true;
		var http_para_string = {formData : $(erpEditCustomerWiseAssayParametersRateForm).serialize()};
		$scope.loaderMainShow();
		
		$http({
			url: BASE_URL + "master/invoicing/customer-wise-assay-parameter-rates/update-customer-wise-assay-parameter-rates",
			method: "POST",
			data: http_para_string,
		}).success(function(data, status, headers, config) {
			$scope.newErpEditCustomerWiseAssayParameterRateFormflag = false;	
			if(data.error == 1){
				$scope.resetButton();
				$scope.funGetCustomerWiseAssayParameterList(cir_customer_id,cir_department_id);
				$scope.funEditCustomerWiseAssayParametersRate(cir_customer_id,cir_department_id);
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newErpEditCustomerWiseAssayParameterRateFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//***************** Adding of Branch Wise DebitNote ********************************
	
	//**************Deleting of Branch Wise Payment Made ***********************************************
	$scope.funDeleteCustomerWiseAssayParameterRate = function(id,cir_customer_id,cir_department_id){				
		if(id){
			$scope.cirCustomerID   = cir_customer_id;
			$scope.cirDepartmentID = cir_department_id;
			$scope.loaderMainShow();
			$scope.hideAlertMsg();
			
			$http({
				method: "POST",
				url: BASE_URL + "master/invoicing/customer-wise-assay-parameter-rates/delete-customer-wise-assay-parameter-rates/"+id,
			}).success(function(result, status, headers, config){				
				if(result.error == 1){
					$scope.funGetCustomerWiseAssayParameterList(cir_customer_id,cir_department_id);
					$scope.successMsgShow(result.message);					
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config){
				$scope.loaderMainHide();
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});	
		}
	};
	//************** /Deleting of Branch Wise Payment Made ****************************
	//*****************country/state tree list data******23-JAN-2019***********
	$scope.countryStateTreeViewList = [];
	$scope.funGetCountryStateTreeViewList = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'get-country-state-tree-view'
		}).success(function (result) {
			if(result.countryStateTreeViewList){
				$scope.countryStateTreeViewList = result.countryStateTreeViewList;
			}
			//console.log('customerWiseAssP'+$scope.countryStateTreeViewList);
			$scope.clearConsole();
		});
	}
	
	//*******************filter scountry/state from tree view****************
	$scope.funGetSelectedNodeId = function(selectedNode){
		$scope.funGetCustomerOnStateChange(selectedNode.state_id);
		$('#countryStateTreeViewPopup').modal('hide');
		$scope.currentModule=19;
	}
	
	//*****************display country dropdown start*****************	
    $scope.countryCodeList = [];
		$http({
			method: 'POST',
			url: BASE_URL +'countries'
		}).success(function (result) { 
		if(result){ 
			$scope.countryCodeList = result;
			$scope.defaultCountryID = DEFAULTSTATE;
				if ($scope.defaultCountryID) {
				  $scope.select_country_id = {id: $scope.defaultCountryID};
				  $scope.funGetStateOnCountryChange($scope.defaultCountryID);
				}
		}
		$scope.clearConsole();
   });
    //*****************display country dropdown end*****************
    
    //*****************state dropdown on country change**********************
   $scope.funGetStateOnCountryChange = function(country_id){
		if(country_id){
			$http({
				method: 'POST',
				url: BASE_URL +'get_states_list/'+country_id
			}).success(function (result) {
				$scope.countryStatesList = result.countryStatesList;
				$scope.clearConsole();
			});
		}
   };
	
	//**********country/state tree popup***************************************************
	$scope.funShowCountryStateViewPopup = function(currentModule){
	   $scope.selectedModuleID = currentModule;
	   $('#countryStateViewPopup').modal('show');
	};
	//**********/country/state tree tree popup***************************************************
	//*****************city dropdown on state change*************************************
	$scope.funGetCustomerOnStateChange = function(state_id){  
		if(state_id){
			$http({
				method: 'GET',
				url: BASE_URL +'master/invoicing/customer-wise-product-rates/get_customer_list/'+state_id
			}).success(function (result) {
				$scope.customerListData = result.customerListData;
				$('#countryStateViewPopup').modal('hide');
				$scope.clearConsole();
			});
		}		
	}
	//****************/city dropdown on state change***********************************
	
});