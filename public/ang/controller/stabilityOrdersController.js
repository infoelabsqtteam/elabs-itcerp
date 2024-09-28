app.controller('stabilityOrdersController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {

	//define empty variables
	$scope.currentModule		= 7;
	$scope.orderData 		= '';
	$scope.order_id 		= '';
	$scope.order_no 		= '';
	$scope.order_date 		= '';	
	$scope.showAutoSearchList 	= false;	
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup 	= '';
	$scope.defaultMsg  	   	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.ProductCategoryFilterBtn = false;
	$scope.ProductCategoryFilterInput = true;	
	$scope.newOrderActive       	= '';
	$scope.viewOrder            	= {};
	$scope.testProductList          = [];
	$scope.dt_standard_value_to_add  = '';
	$scope.dt_standard_value_to_edit = '';
	$scope.cancelledOrder 		= [];
	$scope.cancelledOrder.order_no  = '';
	$scope.cancelledOrder.order_id  = '';	

	//sorting variables
	$scope.sortType     		= 'order_date';     // set the default sort type
	$scope.sortReverse  		= false;         	// set the default sort order
	$scope.searchFish   		= '';    		    // set the default search/filter term
	$scope.headerNoteStatus 	= false;
	$scope.realTimeStabilityStatus 	= false;
	$scope.showAddPOType 		= false;
	$scope.orderYearList    	= {};
	$scope.orderDateList    	= {};
	$scope.orderCurrentYear 	= '';
	$scope.orderCurrentDate 	= '';
	$scope.searchStringID   	= '';
	$scope.orderCustomer 		= {};
	$scope.orderSample		= {};
	$scope.orderSample.po_type  	= false;
	$scope.updateOrder 		= {};
	$scope.updateOrder.po_type 	= false;
	$scope.updateOrder.stb_test_standard_id = '';
	$scope.updateOrder.stb_order_hdr_id      = '';
	$scope.updateOrder.stb_order_hdr_dtl_id  = '';
	$scope.updateOrder.stb_stability_type_id = '';
	$scope.updateOrder.stability_note = '';
	$scope.orderProductTest		= {};
	$scope.filterOrders   		= {};
	$scope.searchOrder 		= {};
	$scope.globalProductCategoryID  = '1';
	$scope.istableTrTdVisibleID 	= '0';
	$scope.tableTrTdColspanLevelI   = '12';
	$scope.tableTrTdColspanLevelII  = '10';
	$scope.fixedRateInvoicingTypeID = '0';

	//**********scroll to top function**********
	$scope.moveToMsg=function(){
		angular.element('html, body').animate({scrollTop: $(".alert").offset().top},500);
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
	
	//********** key Press Handler**********************************************
	$scope.funEnterPressHandler = function(e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			e.stopPropagation();
		}
	}
	//**********/key Press Handler**********************************************

	//**********Read/hide More description************************************
	$scope.toggleDescription = function(type,id) {
		angular.element('#'+type+'limitedText-'+id).toggle();
		angular.element('#'+type+'fullText-'+id).toggle();
	};
	//*********/Read More description*****************************************

	//************/show tree pop up*******************************************
	$scope.showProductCatTreeViewPopUp = function(currentModule){
		$scope.currentModule           	= currentModule;
		$scope.headerNoteStatus 	= false;
		$scope.realTimeStabilityStatus 	= false;
		$('#orderTestingProductCategory').modal('show');
	};
	//**********/show tree pop up********************************************"*

	

	//set category id and name selected from tree view
	$scope.setSelectedProductCategoryId=function(node){
		if(node.level=='2'){
			$scope.selectedProductCategoryId   = node.p_category_id;
			$scope.selectedProductCategoryName = node.p_category_name;
			$scope.funGetTestingProducts(node.p_category_id);
			$('#orderTestingProductCategory').modal('hide');
			$scope.resetPopupForm();
		}
	};

	//reset category id and name selected from tree view
	$scope.resetProductCategory=function(){
		$scope.selectedProductCategoryId='';
		$scope.selectedProductCategoryName='';
	};

	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsNewOrder   			= true;
	$scope.IsUpdateOrder 		 	= true;
	$scope.IsViewList 			= false;
	$scope.IsPreviewList 			= true;
	$scope.IsViewLogList  			= true;
	$scope.IsVisiableSuccessMsg 	 	= true;
	$scope.IsVisiableErrorMsg 		= true;
	$scope.IsVisiableSuccessMsgPopup    	= true;
	$scope.IsVisiableErrorMsgPopup 	    	= true;
	$scope.IsViewHidden 			= true;
	$scope.IsViewAlternativeHidden 	    	= true;
	$scope.isViewOrderFoodSection       	= false;
	$scope.isViewOrderPharmaSection     	= false;
	$scope.isViewOrdersStatisticSection 	= false;
	$scope.isViewInteralTransferSampleLink 	= false;
	$scope.closeAutoSearchList 		= false;
	$scope.showPOType			= false;
	$scope.orderHoldAddFlag 		= false;
	$scope.isInvoicingNeededAddFlag 	= false;
	$scope.orderHoldAddFlag 	   	= false;
	$scope.canAddChangePoTypeOrder 	   	= false;
	$scope.canAddChangeSampleTypeOrder 	= false;
	$scope.canAddChangeInvoicingTo 	   	= false;

	$scope.openNewOrderForm 		 = function(){
		$scope.resetForm();
		$scope.IsNewOrder   		 = false;
		$scope.isSampleId   		 = false;
		$scope.IsUpdateOrder 		 = true;
		$scope.IsViewList 	    	 = true;
		$scope.IsPreviewList 		 = true;
		$scope.IsViewLogList		 = true;
		$scope.IsViewHidden 		 = true;
		$scope.showAutoSearchList 	 = false;
		$scope.showAddSampleType = false;
		$scope.isInvoicingNeededAddFlag = false;
		$scope.funGetTestSampleRecevied();
		$scope.hideAlertMsg();
	};
	//**********/If DIV is hidden it will be visible and vice versa************

	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage = message;
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg = true;
		$scope.moveToMsg();
	};
	//********** /successMsgShow************************************************

	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 	= message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = false;
		$scope.moveToMsg();
	};
	//********** /errorMsgShow************************************************

	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = true;
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

	//**********back Button****************************************************
	$scope.backButton = function(){
		$scope.IsViewList   	= false;
		$scope.IsPreviewList    = true;
		$scope.IsViewLogList  	= true;
		$scope.IsNewOrder   	= true;
		$scope.IsUpdateOrder 	= true;
		$scope.IsViewHidden 	= true;
		$scope.showAutoSearchList = false;
		$scope.headerNoteStatus   = false;
		$scope.realTimeStabilityStatus = false;
		$scope.dt_standard_value_to_add  = '';
		$scope.dt_standard_value_to_edit = '';
		$scope.orderCustomer 	= {};
		$scope.orderSample 	= {};
		$scope.orderProductTest = {};
		$scope.testProductStandardParamenters = {};
		$scope.invoicingTypeList = {};
		$scope.hideAlertMsg();
		angular.element('#sample').text('');
		$scope.fixedRateInvoicingTypeID = '0';
	};
	//**********/back Button***************************************************

	//**********reset Form****************************************************
	$scope.resetForm = function(){
		$scope.order 				= {};
		$scope.updateOrder 			= {};
		$scope.erpCreateOrderForm.$setUntouched();
		$scope.erpCreateOrderForm.$setPristine();
		$scope.updateOrder.sampling_date	= '';
		$scope.invoicingTypeList 		= {};	
		$scope.testProductStandardParamenters 	= {};
		$scope.testProductList 			= [];
		$scope.testProductParamenters 		= {};
		$scope.orderHoldAddFlag 	   	= false;
		$scope.canAddChangePoTypeOrder 	   	= false;
		$scope.canAddChangeSampleTypeOrder 	= false;
		$scope.canAddChangeInvoicingTo 	   	= false;		
		$scope.showAutoSearchList 	        = false;
		$scope.headerNoteStatus 		= false;
		$scope.realTimeStabilityStatus 		= false;
		$scope.fixedRateInvoicingTypeID 	= '0';
		angular.element('#sample').text('');
		$scope.resetPopupForm();
		$scope.hideAlertMsg();
	};
	$scope.resetPopupForm=function(){
		$scope.selectedAll=false;
		$scope.productTestList = [];
		$scope.allPopupSelectedParametersArray=[];
		$scope.testProductParamentersList = [];
		$scope.testProductParamenters=[];
		var isRunning=true;
	};
	//**********/reset Form***************************************************

	//**********confirm box******************************************************
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	};
	//**********/confirm box****************************************************

	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
	    if(APPLICATION_MODE)console.clear();
	};
	//*********/Clearing Console*********************************************

	//************Sorting list order******************************************
	$scope.predicate = 'stb_order_hdr_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
	    $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
	    $scope.predicate = predicate;
	};
	$scope.alterPropertyName = 'test_parameter_name';
	$scope.sortByAlternative = function(alterPropertyName) {
	    $scope.reverse = ($scope.alterPropertyName === alterPropertyName) ? !$scope.reverse : false;
	    $scope.alterPropertyName = alterPropertyName;
	};
	//************/Sorting list order*****************************************

	//**********Open need modification popup******************************
	$scope.funOpenBootStrapModalPopup=function(contentId){
		$scope.successMsgOnPopup = '';
		$scope.errorMsgOnPopup   = '';
		$('#'+contentId).modal({backdrop: 'static',keyboard: true,show: true});
	}
	//**********/Open need modification popup******************************
	
	//**********Close need modification popup******************************
	$scope.funCloseBootStrapModalPopup=function(contentId){
		$('#'+contentId).modal('hide');
	}
	//**********/Close need modification popup******************************

	//*****************display Invoicing Needed*******************************
	$scope.isInvoicingNeededFlag = false;
	$scope.funShowHideInvoicingNeeded = function (){
		if($scope.invoicingNeededFlag == false){
			$scope.order.reporting_to = '';
			$scope.order.invoicing_to = '';
		}
		$scope.isInvoicingNeededFlag = $scope.invoicingNeededFlag;
	};
	//*****************/display Invoicing Needed*******************************

	//*****************display parent category*********************************
	$scope.parentCategoryList = [];
	$http({
	    method: 'POST',
	    url: BASE_URL +'master/get-parent-product-category'
	}).success(function (result) {
	    $scope.parentCategoryList = result.parentCategoryList;
	    $scope.clearConsole();
	});
	//****************/display parent category*********************************

	//*****************display Test Product List dropdown**********************
	$scope.hasProductSampleNameList = false;
	$scope.funGetTestingProducts = function(p_category_id){
		if(p_category_id){
			$scope.productTestList 	= {};
			$scope.testProductParamenters = {};
			$scope.testProductStandardParamenters = {};
			$http({
				method: 'GET',
				url: BASE_URL +'orders/get_test_products/'+p_category_id
			}).success(function (result) {
				$scope.resetPopupForm();
				$scope.testProductList = result.productsList;
				$("#orderTestingProductCategory").modal('hide');
				$scope.hasProductSampleNameList = true;
			});
		}
	};
	//*****************/display Test Product List dropdown*********************
	
	//*****************display Test Product Standard dropdown******************
	$scope.productTestList = [];
	$scope.funGetTestProductStandard = function(product_id){
		if(product_id){
			$scope.testProductStandardParamenters = {};
			$http({
				method: 'GET',
				url: BASE_URL +'orders/get-product-tests/'+product_id
			}).success(function (result){
				$scope.resetPopupForm();
				$scope.productTestList           = result.productTestList;
				$scope.selectedProductCategoryId = result.productMasterList.p_category_id;
			});
		}
	};
	//*****************/display Test Product Standard dropdown******************

	//*****************display Test Product Standard dropdown*******************
	$scope.testProductParamentersList = [];
	$scope.funTestProductStandardParamentersList = function(testId){
		if(testId){
			$scope.globalTestId = testId;
			$scope.ProductTestId = testId;
			$scope.loaderMainShow();
			$http({
				method: 'GET',
				url: BASE_URL +'orders/get-product-test-parameters-list/'+testId,
			}).success(function (result){
				$scope.testProductParamentersList = result.productTestParametersList;
				angular.element('#selectedAll').prop('checked',false);
				angular.element('#header_note').prop('checked',false);
				angular.element('#real_time_stability').prop('checked',false);
				if($scope.realTimeStabilityStatus){
					$('#real_time_stability').prop('checked',true);
				}
				if($scope.headerNoteStatus){
					$('#header_note').prop('checked',true);
				}
				$('#productParametersPopup').modal('show');
				if(result.testStandardList){
					$scope.orderProductTest.test_standard = result.testStandardList.test_standard_id;
				}
				$scope.loaderMainHide();
			});
		}
	};
	//*****************/display Test Product Standard dropdown**************

	//*****************single checkbox***************************************
	$scope.funCheckParameterCheckedOrNotValues = function(dltId){
        var paraStatus = angular.element('#checkOneParameter_'+dltId).prop('checked');
		if(paraStatus){
			$scope.allPopupSelectedParametersArray.push(dltId);
		}else{
			angular.element('#selectedAll').prop('checked',false);
			$scope.allPopupSelectedParametersArray.pop(dltId);
		}
	};

	//*****************/single checkbox***************************************
	$scope.funHeaderNoteCheck = function(){
		$scope.headerNoteStatus = false;
		if(angular.element('#header_note').prop('checked')){
			$scope.headerNoteStatus = true;
		}else{
			$scope.headerNoteStatus = false;
		}
	};
	$scope.funRealTimeStabilityStatusCheck = function(){
		$scope.realTimeStabilityStatus = false;
		if(angular.element('#real_time_stability').prop('checked')){
			$scope.realTimeStabilityStatus = true;
		}else{
			$scope.realTimeStabilityStatus = false;
		}
	};
	//*****************display Test Product Standard dropdown*****************
	$scope.funGetTestProductStandardParamenters = function(global_sample_id,global_product_category_id,global_invoicing_type_id){
		$scope.loaderMainShow();
		$http.post(BASE_URL +'orders/get-product-test-parameters',{
			data: { formData:'sample_id='+global_sample_id+'&'+$(testParametersForm).serialize() },
		}).success(function (result, status, headers, config){
			$scope.testProductParamenters 	= result.productTestParametersList;
			$timeout(function(){$scope.getClaimCalculation(result.productTestParametersList);},500);
			$scope.istableTrTdVisibleID 	= global_product_category_id == 2 && global_invoicing_type_id == 4 ? 1 : 0;
			$scope.tableTrTdColspanLevelI  	= global_product_category_id == 2 && global_invoicing_type_id == 4 ? 14 : 12;
			$scope.tableTrTdColspanLevelII  = $scope.tableTrTdColspanLevelI - 1;
			$scope.realTimeStability  	= result.order_stability;
			$('#productParametersPopup').modal('hide');
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '400'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*****************/display Test Product Standard dropdown******************
	
	//********************* 03 May 2018****************************************/
	$scope.getClaimCalculation = function(productTestParametersList){
		angular.forEach(productTestParametersList, function (value, key) {
			angular.forEach(value.categoryParams, function (categoryParams, key) {
				$scope.funChangeTestParameterValueAccToClaim(categoryParams.product_test_dtl_id,'add');
			});
		});	
	};
	
	//*****************display customer list dropdown***************************
	$scope.customerNameList = [];
	$scope.funGetCustomersList = function(){
		$http({
			method: 'GET',
			url: BASE_URL +'orders/get_customer_list'
		}).success(function (result) {
			$scope.customerNameList = result.customersList;
		});
	};
	//*****************/display customer list code dropdown*********************
	
	//*****************Duplicate Customer Order***************************************
	$scope.funDuplicateCustomerOrder=function(saveType){
		if($scope.finalSaveOrNot!='1'){
			$scope.funPlaceAndSaveMoreOrder($scope.divisionID,'0',saveType);
		}else{
			$scope.funPlaceAndSaveOrder($scope.divisionID,saveType)
		}

	};
	//*****************/Duplicate Customer Order***************************************
	
	//*****************/discount types onchange**************************************************
	$scope.applyDiscountTypeYes = true;
	$scope.applyDiscountTypeNo = false;
	$scope.funGetDiscountTypeInput=function(discountTypeId,type){
		if(discountTypeId && type == 'edit'){
			$scope.updateOrder.discount_value = null;
			if(discountTypeId == '3'){
				$scope.applyDiscountTypeYes = false;
				$scope.applyDiscountTypeNo = true;
				$scope.updateOrder.discount_value = null;
			}else{
				$scope.applyDiscountTypeYes = true;
				$scope.applyDiscountTypeNo = false;
				$scope.updateOrder.discount_value = $scope.updateOrder.discount_value_set;
			}
		}
	}
	//*****************/discount types onchange*************************************************

	//*******************Order on hold **************************************
	$scope.funOrderOnHoldOrNot = function (){
		var orderHoldFlagNeeded = angular.element('#add_order_type').prop('checked');
		if(orderHoldFlagNeeded){
			$scope.orderHoldAddFlag 	   = true;
			$scope.canAddChangePoTypeOrder 	   = true;
			$scope.canAddChangeSampleTypeOrder = true;
			$scope.canAddChangeInvoicingTo 	   = true;
		}else{
			$scope.orderHoldAddFlag 	   = false;
			$scope.canAddChangePoTypeOrder 	   = false;
			$scope.canAddChangeSampleTypeOrder = false;
			$scope.canAddChangeInvoicingTo 	   = false;
			$scope.orderSample.hold_reason     = '';
		}
	};

	//*********************select all functionality************************************
	$scope.toggleAll = function () {
		$scope.allPopupSelectedParametersArray = [];
		var checkAllStatus = angular.element('#selectedAll').prop('checked');
		if(checkAllStatus){
			angular.element('.parametersCheckBox').prop('checked',true);
			angular.element(".parametersCheckBox:checked").each(function() {
				$scope.allPopupSelectedParametersArray.push(this.value);
			});
		}else{
			angular.element('.parametersCheckBox').prop('checked',false);
		}
	};
	//*********************select all functionality************************************

	//*****************invoicing types*****************************************
	$scope.invoicingTypeList = [];
	$scope.funInvoicingTypeList = function(){
	 	$http({
			method: 'POST',
			url: BASE_URL +'customer-invoicing-types-list'
		}).success(function (result) {
			if(result){
				$scope.invoicingTypeList = result.invoicingTypes;
			}
			$scope.clearConsole();
		});
	};
	//*****************/invoicing types****************************************	

	/********* show PO type*****************************************/
	$scope.funAddShowPODetailType = function(billingTypeId){
	    if(billingTypeId == '5') {
		$scope.showAddPOType = true;
		$scope.updateOrder.po_type  = true;
	    }else{
		var addPoTypeCheckedOrNot = angular.element('#add_po_type').prop('checked');
		if(addPoTypeCheckedOrNot){
		    $scope.showAddPOType = true;
		}else{
		    $scope.showAddPOType = false;
		    $scope.updateOrder.po_no = '';
		    $scope.updateOrder.po_date = '';
		}
	    }
	};
	/********* show PO type*****************************************/
	
	//**********Refreshing Invoicing Structure*************************************************
	$scope.funRefreshInvoicingStructure = function(sampleId){
	    if(sampleId){
		var http_para_string = {formData : 'sample_id='+sampleId};
		
		$http({
		    url: BASE_URL + "sales/orders/refresh-invoicing-structure",
		    method: "POST",
		    data: http_para_string
		}).success(function(result, status, headers, config) {
		    if(result.error == 1){
			$scope.invoicingTypeList = [{id: result.currentInvoicingStructure.invoicing_type_id, name: result.currentInvoicingStructure.invoicing_type}];
			$scope.orderCustomer.invoicing_type_id = {id:result.currentInvoicingStructure.invoicing_type_id};
		    }
		    $scope.loaderMainHide();
		    $scope.clearConsole();
		}).error(function(result, status, headers, config) {
		    if(status == '500' || status == '404'){
			$scope.errorMsgShowPopup($scope.defaultMsg);
		    }
		    $scope.loaderMainHide();
		    $scope.clearConsole();
		});
	    }
	};
	//**********/Refreshing Invoicing Structure********************************
	
	//*****************product category tree list data*************************
	$scope.productCategoriesTree = [];
	$scope.getProductCategories = function(){
	    $http({
		method: 'POST',
		url: BASE_URL +'get-product-category-tree-view'
	    }).success(function (result) {
		if(result.productCategoriesTree){
			$scope.productCategoriesTree = result.productCategoriesTree;
		}
		$scope.clearConsole();
	    });
	};
	$scope.stateCityTreeViewList = [];
	$scope.funGetOrderStateCityTreeViewList = function(){
	    $http({
		method: 'POST',
		url: BASE_URL +'get-state-city-tree-view'
	    }).success(function (result) {
		if(result.stateCityTreeViewList){
			$scope.stateCityTreeViewList = result.stateCityTreeViewList;
		}
		$scope.clearConsole();
	    });
	};
	//$timeout(function (){$scope.getProductCategories();$scope.funGetOrderStateCityTreeViewList();}, 10000);
	//*****************/product category tree list data************************
	
	//*****************display division dropdown start**************************************************
	$scope.divisionsCodeList = [];
	$http({
	    method: 'POST',
	    url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
	    $scope.divisionsCodeList = result;
	    $scope.clearConsole();
	});
	//*****************display division dropdown end*****************************************************
	
	//*****************display sample Priority List dropdown***************************
	$scope.samplePriorityList    = [];
	$scope.samplePriorityCRMList = [];
	$scope.funGetSamplePriorityList = function(){
	    $http({
		method: 'GET',
		url: BASE_URL +'orders/get_sample_priority_list'
	    }).success(function (result) {
		$scope.samplePriorityList    = result.samplePriorityList;
		$scope.samplePriorityCRMList = result.samplePriorityCRMList;
		$scope.clearConsole();
	    });
	}
	$scope.funGetSamplePriorityList();
	//*****************/display sample Priority List code dropdown*********************
	
	//*****************New Auto complete Section**********************************
	$scope.getAutoSearchSampleMatches = function(searchText,sampleId){
		$http({
			method: 'GET',
			url: BASE_URL +'sales/orders/get-sample-name-list/'+sampleId+'/'+searchText
		}).success(function (result) {
			$scope.resultItems 	        = result.itemsList;
			$scope.customerInvoicingTypeID  = result.invoicing_type_id;
			$scope.fixedRateInvoicingTypeID = result.fixed_rate_invoicing_type_id;
			$scope.sampleID 	        = sampleId;
			if($scope.resultItems.length >= 1){
				$scope.showAutoSearchList = true;
				$scope.closeAutoSearchList = true;
			}else{
				$scope.showAutoSearchList = false;
				$scope.hasProductSampleNameList = true;
				$scope.closeAutoSearchList = false;
			}
			$scope.clearConsole();
		});
		return $scope.resultItems;
	};
	//****************/New Auto complete Section**********************************
	
	//***********Set parameter value when user selecet from auto search list***************
	$scope.funCloseAutoSearchList = function(){
		$scope.showAutoSearchList = false;
		$scope.resultItems = [];
	};
	//**********/Set parameter value when user selecet from auto search list****************

	//****************Set parameter value when user selecet from auto search list***********
	$scope.funSetSelectedSampleDescription = function(selectedSampleId,selectedSampleName,formType){
	    var selectedSampleNameArr = selectedSampleName.split ("|");
	    if(selectedSampleNameArr[0]) {
		$scope.updateOrder.selected_sample_id = selectedSampleId;
		$scope.updateOrder.sample_description = selectedSampleNameArr[0].trim();
		$scope.showAutoSearchList = false;
	    }		
	};
	//****************/Set parameter value when user selecet from auto search list***********
	
	//**********Checking of Order Amount*******************************************************
	$scope.funGetSearchSampleMatcheRate = function(sample_description,invoicing_type_id,sample_id,form_type){
	    if(sample_description !='' && (invoicing_type_id == '2' || invoicing_type_id =='3')){

		if($scope.newCheckPriceOrderflag)return;
		$scope.newCheckPriceOrderflag = true;
		$scope.hideAlertMsg();
		var http_para_string = {'sample_description' : sample_description,'invoicing_type_id':invoicing_type_id,'sample_id':sample_id};

		$http({
			url: BASE_URL + "orders/check-customer-wise-product-rate",
			method: "POST",
			data: http_para_string
		}).success(function(result, status, headers, config) {
			$scope.newCheckPriceOrderflag = false;
			if(result.error == 1){
			    $scope.updateOrder.selected_sample_id = result.sample_description_id;
			    $scope.updateOrder.sample_description = sample_description;
			    $scope.showAutoSearchList = false;
			}else{
			    $scope.errorMsgShow(result.message);
			    $scope.updateOrder.selected_sample_id = '';
			    $scope.updateOrder.sample_description = '';
			    angular.element(add_sample_description).focus();
			    $scope.showAutoSearchList = false;
			}
			$scope.clearConsole();
		}).error(function(result, status, headers, config) {
			$scope.newCheckPriceOrderflag = false;
			if(status == '500' || status == '404'){
			    $scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	    }
	};
	//**********Checking of Order Amount*************************************************
	
	//*****************display Test Product List dropdown**********************
	$scope.funGetTestProductAccordingToSampleName = function(sampleId){
	    if(sampleId){
		$scope.testProductList 		      = {};
		$scope.productTestList 		      = {};
		$scope.testProductParamenters 	      = {};
		$scope.testProductStandardParamenters = {};
		$scope.hasProductSampleNameList       = false;
		$http({
		    method: 'GET',
		    url: BASE_URL +'orders/get_test_product_acc_sample_name/'+sampleId
		}).success(function (result) {
		    if(result.productSampleNameList){
			$scope.testProductList = result.productSampleNameList;
		    }else{
			$scope.hasProductSampleNameList = true;
		    }
		});
	    }
	};
	//*****************/display Test Product List dropdown*********************
	
	//*****************display customer name on check of checkbox********
	$scope.funCheckCustomerDetail = function(checked,customerName,type){
	    // for supplied_by customer
	    if(type=="supplied_by"){
		if(checked == false){
		    $scope.updateOrder.supplied_by = '';
		}else {
		    $scope.updateOrder.supplied_by = customerName;
		}
	    }else if(type=="manufactured_by"){
		if(checked == false){
		    $scope.updateOrder.manufactured_by = '';
		}else {
		    $scope.updateOrder.manufactured_by = customerName;
		}
	    }
	}
	//*****************/display customer list location and Mfg Lic Number******
	
	//*****************display division dropdown*****************
	$scope.isSetSurchargeValueFlag = false;
	$scope.funSetSurchargeValue = function(samplePriorityId){
	    $scope.isSetSurchargeValueFlag = false;
	    if(samplePriorityId == 3){
		$scope.isSetSurchargeValueFlag  = true;
	    }else{
		$scope.updateOrder.surcharge_value = '';
	    }
	};
	//*****************/display division dropdown*****************
	
	//********************Pre populated Select Box****************************
	$scope.sealedUnsealedList = [];
	$scope.funGetSealedUnsealedList = function(){
	    $http({
		method: 'GET',
		url: BASE_URL +'sales/stability-orders/get_sealed_unsealed_list'
	    }).success(function (result) {
		$scope.sealedUnsealedList = result.sealedUnsealedData;
		$scope.clearConsole();
	    });
	}
	$scope.funGetSealedUnsealedList();
	
	$scope.signedUnsignedList = [];
	$scope.funGetSignedUnsignedList = function(){
	    $http({
		method: 'GET',
		url: BASE_URL +'sales/stability-orders/get_signed_unsigned_list'
	    }).success(function (result) {
		$scope.signedUnsignedList = result.signedUnsignedData;
		$scope.clearConsole();
	    });
	}
	$scope.funGetSignedUnsignedList();
	//*******************/Pre populated Select Box****************************
	
	//*****************display division dropdown start************************
	$scope.submissionTypeList = [];
	$http({
	    method: 'POST',
	    url: BASE_URL +'sales/samples/get-sample-modes'
	}).success(function (result) {
	    $scope.submissionTypeList = result.sampleModeList;
	    $scope.clearConsole();
	});
	//*****************display division dropdown end***************************
	
	//*****************display division dropdown*****************
	$scope.advanceDetailsDisplay = false;
	$scope.funSubmissionTypeValue = function(submissionTypeId){
	    $scope.advanceDetailsDisplay = false;
	    if(submissionTypeId==1){
		$scope.advanceDetailsDisplay = true;
	    }else{
		$scope.updateOrder.advance_details = '';
	    }
	};
	//*****************/display division dropdown*****************
	
	//**********Getting Stability Order Notification List*************************************************
	$scope.funGetStabilityOrderNotificationList = function(){
		$scope.hideAlertMsg();
		angular.element('input.select_all_notification_ckbox').prop('checked',false);
		var http_para_string = {formData : ''};	
		$http({
			url: BASE_URL + "sales/stability-orders/get-stability-order-notifications",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			$scope.stabilityOrderNotificationList = result.stbNotificationList;
			if (result.stbNotificationList.length) {
				angular.element('#stb_order_notification').html('('+result.stbNotificationList.length+')').addClass('asteriskRed').show();
			}else{
				angular.element('#stb_order_notification').hide();
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function(data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//*********/Getting Stability Order Notification List*************************************************
	
	//*****************Getting Product Test Master in Tabular Format********************
	$scope.funGetStabilityOrderPrototypesDetail = function(stb_order_hdr_id,stb_order_hdr_dtl_id){
	    
		if($scope.newCreateOrderFromStabilityOrderPrototypesflag)return;
		$scope.newCreateOrderFromStabilityOrderPrototypesflag = true;	
		var http_para_string = {formData : 'stb_order_hdr_id='+stb_order_hdr_id+'&stb_order_hdr_dtl_id='+stb_order_hdr_dtl_id};
		$scope.loaderMainShow();
	
		$http({
			url: BASE_URL + "sales/stability-orders/get-stability-order-prototypes-detail",
			method: "POST",
			data: http_para_string
		}).success(function(result, status, headers, config) {
			$scope.newCreateOrderFromStabilityOrderPrototypesflag = false;
			if(result.error == 1){
				$scope.IsPreviewList = false;
				$scope.IsViewList = true;
				$scope.prototypesHdrList = result.returnData.stb_order_hdr;
				$scope.prototypesHdrDtlList = result.returnData.stb_order_hdr_dtl;
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config) {
			$scope.newCreateOrderFromStabilityOrderPrototypesflag = false;
			if(status == '500' || status == '404'){
			    $scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*****************/Getting Product Test Master in Tabular Format*******************************
	
	//*****************Getting Preview of Final Stability Order Prototypes**************************
	$scope.funFinalPreviewStabilityOrderPrototypes = function(stb_order_hdr_id,stb_order_hdr_dtl_id,stb_stability_type_id){
		
		if($scope.newPreviewFinalStabilityOrderPrototypesflag)return;
		$scope.newPreviewFinalStabilityOrderPrototypesflag = true;
		if(!stb_order_hdr_id || !stb_order_hdr_dtl_id || !stb_stability_type_id)return;
		
		$scope.stbOrderHdrID = stb_order_hdr_id;
		$scope.stbOrderHdrDtlID = stb_order_hdr_dtl_id;
		$scope.stbStabilityTypeID = stb_stability_type_id;

		$scope.IsNewOrder       = false;
		$scope.IsPreviewList    = false;
		$scope.IsViewList 	= true;
		$scope.IsViewLogList    = true;
		$scope.IsViewHidden     = true;
		$scope.IsSaveAsOrder    = true;
		$scope.IsUpdateOrder    = true;
		$scope.hideAlertMsg();
		$scope.loaderMainShow();
		
		var http_para_string = {formData : 'stb_order_hdr_id='+stb_order_hdr_id+'&stb_order_hdr_dtl_id='+stb_order_hdr_dtl_id+'&stb_stability_type_id='+stb_stability_type_id};

		$http({
		    url: BASE_URL + "sales/stability-orders/get-final-preview-of-each-stability-prototypes",
		    method: "POST",
		    data: http_para_string,
		}).success(function(result, status, headers, config){
			if(result.error == 1){
				
				$scope.updateOrder 			= result.stbOrderList;
				$scope.testProductParamentersList       = result.productTestParametersList;
				$scope.stbSampleID 			= result.stbOrderList.stb_sample_id;
				$scope.customerInvoicingTypeID 		= result.stbOrderList.stb_invoicing_type_id;
				$scope.globalProductCategoryID 		= result.stbOrderList.stb_product_category_id;
				$scope.globalCustomerID 	        = result.stbOrderList.stb_customer_id;
				$scope.globalBillingTypeID 		= result.stbOrderList.stb_billing_type_id;
				
				$scope.istableTrTdVisibleID 		= $scope.globalProductCategoryID == 2 && $scope.customerInvoicingTypeID == 4 ? 1 : 0;
				$scope.tableTrTdColspanLevelI  		= $scope.globalProductCategoryID == 2 && $scope.customerInvoicingTypeID == 4 ? 14 : 12;
				$scope.tableTrTdColspanLevelII  	= $scope.tableTrTdColspanLevelI - 1;
				
				//Customer Detail
				$scope.sampleWithPlaceName             = result.stbOrderList.sample_no+'/'+result.stbOrderList.customer_name+'/'+result.stbOrderList.state_name+'/'+result.stbOrderList.city_name;
				$scope.testSampleReceviedList          = [{id: result.stbOrderList.stb_sample_id, name: $scope.sampleWithPlaceName}];
				$scope.updateOrder.sample_id           = {id : result.stbOrderList.stb_sample_id};
				$scope.customerNameList 	       = [{customer_id: result.stbOrderList.stb_customer_id, customer_name: result.stbOrderList.customer_name}];
				$scope.updateOrder.customer_id         = {customer_id : result.stbOrderList.stb_customer_id};
				$scope.updateOrder.customer_name       = result.stbOrderList.customer_name;
				$scope.updateOrder.customer_city       = result.stbOrderList.stb_customer_city;
				$scope.updateOrder.customer_city_name  = result.stbOrderList.city_name;
				$scope.updateOrder.mfg_lic_no          = result.stbOrderList.stb_mfg_lic_no;
				$scope.updateOrder.sale_executive_name = result.stbOrderList.sale_executive_name;
				$scope.updateOrder.sale_executive      = result.stbOrderList.stb_sale_executive;
				$scope.updateOrder.discount_type_name  = result.stbOrderList.discount_type;
				$scope.updateOrder.discount_type_id    = result.stbOrderList.stb_discount_type_id;
				$scope.updateOrder.discount_value      = result.stbOrderList.stb_discount_value;
				$scope.invoicingTypeList 	       = [{id: result.stbOrderList.stb_invoicing_type_id, name: result.stbOrderList.invoicing_type}];
				$scope.updateOrder.invoicing_type_id   = {id:result.stbOrderList.stb_invoicing_type_id};
				$scope.billingTypeList 		       = [{id: result.stbOrderList.stb_billing_type_id, name: result.stbOrderList.billing_type}];
				$scope.updateOrder.billing_type_id     = {id:result.stbOrderList.stb_billing_type_id};
				
				//Sample Detail
				$scope.updateOrder.division_id        	= { id : result.stbOrderList.stb_division_id};
				var order_date_formated       		= result.currentDate.split(" ");
				$scope.updateOrder.order_date 		= order_date_formated[0] ? order_date_formated[0] : '';
				$scope.funSetSurchargeValue(result.stbOrderList.stb_sample_priority_id);
				$scope.orderAddSamplePriorityId     	= result.stbOrderList.stb_sample_priority_id;
				$scope.updateOrder.sample_priority_id 	= {sample_priority_id : result.stbOrderList.stb_sample_priority_id};
				if($scope.orderAddSamplePriorityId == '4'){
				    $scope.samplePriorityList = $scope.samplePriorityCRMList;
				}else{
				    $scope.samplePriorityList = $scope.samplePriorityList;
				}
				$scope.updateOrder.is_sealed          	= { id : result.stbOrderList.stb_is_sealed};
				$scope.updateOrder.is_signed          	= { id : result.stbOrderList.stb_is_signed};
				$scope.funSubmissionTypeValue(result.stbOrderList.stb_submission_type);
				$scope.updateOrder.submission_type    	= { id : result.stbOrderList.stb_submission_type};
				
				//Product Detail
				$scope.updateOrder.stability_note       = result.stbOrderHdrDetailDtl.stb_stability_type_name + ' '+ result.stbOrderHdrDetailDtl.stb_condition_temperature;
				$scope.testProductList 			= [{ product_id: result.stbOrderHdrDtl.stb_product_id, product_name:result.stbOrderHdrDtl.product_name}];
				$scope.updateOrder.product_id 		= { product_id : result.stbOrderHdrDtl.stb_product_id};
				$scope.productTestListing 		= [{ test_id : result.stbOrderHdrDtl.stb_product_test_id, test_code:result.stbOrderHdrDtl.test_code}];
				$scope.updateOrder.product_test_id 	= { test_id : result.stbOrderHdrDtl.stb_product_test_id};
				$scope.updateOrder.stb_test_standard_id = result.stbOrderHdrDtl.stb_test_standard_id;
				$scope.updateOrder.stb_order_hdr_id      = stb_order_hdr_id;
				$scope.updateOrder.stb_order_hdr_dtl_id  = stb_order_hdr_dtl_id;
				$scope.updateOrder.stb_stability_type_id = stb_stability_type_id;
				
				if(result.stbOrderList.stb_reporting_to || result.stbOrderList.stb_invoicing_to){
					$scope.updateOrder.invoicingNeeded = true;
					$scope.isInvoicingNeededAddFlag = true;
				}else{
					$scope.isInvoicingNeededAddFlag = false;
				}
				if(result.stbOrderList.reportingCustomerName){
					$scope.customerListData 	= [{ id : result.stbOrderList.stb_reporting_to ,name:result.stbOrderList.reportingCustomerName }];
					$scope.updateOrder.reporting_to = {id:result.stbOrderList.stb_reporting_to};
				}
				if(result.stbOrderList.invoicingCustomerName){
					$scope.customerData 		= [{ id : result.stbOrderList.stb_invoicing_to ,name:result.stbOrderList.invoicingCustomerName }];
					$scope.updateOrder.invoicing_to = {id:result.stbOrderList.stb_invoicing_to};
				}
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.newPreviewFinalStabilityOrderPrototypesflag = false;
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config){
			if(status == '500' || status == '404'){
			    $scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.newPreviewFinalStabilityOrderPrototypesflag = false;
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//*****************/Getting Preview of Final Stability Order Prototypes**************************
	
	//**********/Adding of Order**********************************************************************
	$scope.funConfirmPlaceAndSaveOrderMessage = function(stb_order_hdr_id,stb_order_hdr_dtl_id,stb_stability_type_id,message){
		$ngConfirm({
			title     : false,
			content   : message, //Defined in message.js and included in head
			animation : 'right',
			closeIcon : true,
			closeIconClass    : 'fa fa-close',
			backgroundDismiss : false,
			theme   : 'bootstrap',
			columnClass : 'col-sm-5 col-md-offset-3',
			buttons : {
				OK: {
					text: 'Yes',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funPlaceAndSaveOrder(stb_order_hdr_id,stb_order_hdr_dtl_id,stb_stability_type_id);
					}
				},
				cancel: {
					text     : 'No',
					btnClass : 'btn-default ng-confirm-closeIcon'
				}
			}
		});
	};
	$scope.funPlaceAndSaveOrder = function(stb_order_hdr_id,stb_order_hdr_dtl_id,stb_stability_type_id){

		if(!$scope.erpCreateOrderForm.$valid)return;
		if($scope.newOrderflag)return;
		$scope.newOrderflag = true;
		$scope.loaderMainShow();
		var http_para_string = {formData : $(erpCreateOrderForm).serialize()};
    
		$http({
			url: BASE_URL + "sales/stability-orders/add-order",
			method: "POST",
			headers: {'Content-Type': 'application/json'},
			data: http_para_string
		}).success(function(result, status, headers, config) {
			$scope.newOrderflag = false;
			if(result.error == 1){
			    $scope.IsNewOrder = true;
			    $scope.resetForm();
			    $scope.funGetStabilityOrderNotificationList();
			    $scope.funGetStabilityOrderPrototypesDetail(stb_order_hdr_id,stb_order_hdr_dtl_id);
			    $scope.successMsgShow(result.message);
			}else{
			    $scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config) {
			$scope.newOrderflag = false;
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	$scope.funOpenModalPopup = function(orderData,orderSaveType){
		$scope.successMsgOnPopup = '';
		$scope.errorMsgOnPopup   = '';
		$scope.orderData         = orderData;
		$scope.finalSaveOrNot    = orderSaveType;
		$('#previousOrderDetail').modal({backdrop: 'static',keyboard: true,show: true});
	};
	//**********/Adding of Order*************************************************
	
	//********* show PO type*****************************************
	$scope.funAddShowPODetailType = function(billingTypeId){
		if(billingTypeId == '5') {
			$scope.showAddPOType = true;
			$scope.updateOrder.po_type  = true;
		}else{
			var addPoTypeCheckedOrNot = angular.element('#add_po_type').prop('checked');
			if(addPoTypeCheckedOrNot){
			    $scope.showAddPOType = true;
			}else{
			    $scope.showAddPOType = false;
			    $scope.updateOrder.po_no = '';
			    $scope.updateOrder.po_date = '';
			}
		}
	};
	//********* show PO type*****************************************
	
	//*********Show sample type**************************************
	$scope.funAddShowSampleType = function(){
		var addSampleTypeCheckedOrNot = angular.element('#add_sample_type').prop('checked');
		if(addSampleTypeCheckedOrNot){
			$scope.showAddSampleType = true;
		}else{
			$scope.showAddSampleType = false;
			$scope.orderSample.inter_laboratory = false;
			$scope.orderSample.compensatory = false;
		}
	};
	//*********Show sample type**************************************
	
	//*********Show Hide Invoicing Needed**************************************
	$scope.funAddShowHideInvoicingNeeded = function (){
		var invocingAddReportingChecked = angular.element('#invoicing_reporting_add_id').prop('checked');
		if(invocingAddReportingChecked){
		    $scope.isInvoicingNeededAddFlag = true;
		}else{
		    $scope.isInvoicingNeededAddFlag = false;
		}
	};
	//*********Show Hide Invoicing Needed**************************************
	
	/********* reporting to fucntions*****/
	$scope.funShowReportingStateCityTreeViewPopup = function(currentModule){
		$('#stateCityTreeViewPopup').modal('show');
		$scope.currentModule=currentModule;
	};

	//*******************filter state/city from tree view****************
	$scope.funGetSelectedStateId = function(selectedNode){
		$scope.funGetReportingCustomerOnStateChange(selectedNode.state_id);
		$('#stateCityTreeViewPopup').modal('hide');
		$scope.currentModule = 17;
		$('#editStateCityTreeViewPopup').modal('hide');
	};

	//*****************city dropdown on state change*******************************
	$scope.funGetReportingCustomerOnStateChange = function(state_id){
		if(state_id){
			$http({
				method: 'GET',
				url: BASE_URL +'master/invoicing/customer-wise-product-rates/get_customer_list/'+state_id
			}).success(function (result) {
				$scope.customerListData = result.customerListData;
				$scope.clearConsole();
			});
		}
	};
	/********* /reporting to fucntions*****/

	//**********invoicing functions***************************************************
	$scope.funShowInvoicingStateCityTreeViewPopup = function(currentModule){
		$scope.currentModule=currentModule;
		$('#stateCityTreeViewPopup').modal('show');
	};
	$scope.funGetSelectedInvoicingStateId = function(selectedNode){
		$scope.funGetCustomerInvoicingOnStateChange(selectedNode.state_id);
		$('#stateCityTreeViewPopup').modal('hide');
		$('#editStateCityTreeViewPopup').modal('hide');
		$scope.currentModule = 18;
	};
	//*****************city dropdown on state change*******************************
	$scope.funGetCustomerInvoicingOnStateChange = function(state_id){
		if(state_id){
			$http({
				method: 'GET',
				url: BASE_URL +'master/invoicing/customer-wise-product-rates/get_customer_list/'+state_id
			}).success(function (result) {
				$scope.customerData = result.customerListData;
				$scope.clearConsole();
			});
		}
	};
	//**********invoicing functions***************************************************
	
	//*****************AutoSearch HeaderNote Matches***************************
	$scope.showHeaderNoteAutoSearchList = false;
	$scope.getAutoSearchHeaderNoteMatches = function(searchText){
		$http({
			method: 'GET',
			url: BASE_URL +'sales/orders/get-header-note-list/'+searchText
		}).success(function (result) {
			$scope.headerNotesList = result.itemsList;
			if($scope.headerNotesList.length > 0){
				$scope.showHeaderNoteAutoSearchList = true;
			}else{
				$scope.showHeaderNoteAutoSearchList = false;
			}
			$scope.clearConsole();
		});
		return $scope.headerNotesList;
	};

	//Set parameter value when user selecet from auto search list
	$scope.funSetSelectedHeaderNote = function(selectedHeaderNote,selectedHeaderLimit,formType){
		if(formType == 'add'){
			$scope.updateOrder.header_note = selectedHeaderNote;
			$scope.dt_standard_value_to_add = selectedHeaderLimit;
			$scope.showHeaderNoteAutoSearchList = false;
		}else if(formType == 'edit'){
			$scope.updateOrder.header_note = selectedHeaderNote;
			$scope.dt_standard_value_to_edit = selectedHeaderLimit;
			$scope.showHeaderNoteAutoSearchList = false;
		}
	};
	//*****************/AutoSearch HeaderNote Matches***************************
	
	//*****************AutoSearch Stability Note Matches***************************
	$scope.showStabilityNoteAutoSearchList = false;
	$scope.getAutoSearchStabilityNoteMatches = function(searchText){
		$http({
			method: 'GET',
			url: BASE_URL +'sales/orders/get-stability-note-list/'+searchText
		}).success(function (result) {
			$scope.stabilityNotesList = result.itemsList;
			if($scope.stabilityNotesList.length > 0){
				$scope.showStabilityNoteAutoSearchList = true;
			}else{
				$scope.showStabilityNoteAutoSearchList = false;
			}
			$scope.clearConsole();
		});
		return $scope.stabilityNotesList;
	};

	//set parameter value when user selecet from auto search list
	$scope.funSetSelectedStabilityNote = function(selectedStabilityNote,formType){
		if(formType == 'add'){
			$scope.updateOrder.stability_note = selectedStabilityNote;
			$scope.showStabilityNoteAutoSearchList = false;
		}else if(formType == 'edit'){
			$scope.updateOrder.stability_note = selectedStabilityNote;
			$scope.showStabilityNoteAutoSearchList = false;
		}
	};
	//*****************/AutoSearch Stability Note Matches***************************
	
	//**********alternative Test Parameter Popup**************************************
	$scope.alternativeTestParameterPopup = function(product_test_dtl_id){
		if(angular.isDefined(product_test_dtl_id)){
			$http({
				method: 'GET',
				url: BASE_URL +'orders/get-alter-product-test-parameters/'+product_test_dtl_id
			}).success(function (result) {
				$scope.alterTestProductStandardParamenters = result.alternativeTestProParamsList;
				$scope.IsViewAlternativeHidden 	= false;
				$("#viewAlternativeModal").modal("show");
			});
		}
	};
	//********** /alternative Test Parameter Popup**************************************

	//*****************display Test Product Standard dropdown**********************
	$scope.selectAlternativeTestParameterRow = function(product_test_param_altern_method_id,product_test_dtl_id){
		if(angular.isDefined(product_test_param_altern_method_id) && angular.isDefined(product_test_dtl_id)){
			$http({
				method: 'GET',
				url: BASE_URL +'orders/reselect_test_standard_parameters/'+product_test_param_altern_method_id,
			}).success(function (result){
				angular.element("#test_parameter_name_text"+product_test_dtl_id).html(result.alterSelectedTestProParamsList.test_parameter_name);
				angular.element("#equipment_name_text"+product_test_dtl_id).html(result.alterSelectedTestProParamsList.equipment_name);
				angular.element("#method_name_text"+product_test_dtl_id).html(result.alterSelectedTestProParamsList.method_name);
				angular.element("#standard_value_from_text"+product_test_dtl_id).html(result.alterSelectedTestProParamsList.standard_value_from);
				angular.element("#standard_value_to_text"+product_test_dtl_id).html(result.alterSelectedTestProParamsList.standard_value_to);
				if(result.alterSelectedTestProParamsList.time_taken_days){
				angular.element("#time_taken_days_text"+product_test_dtl_id).html(result.alterSelectedTestProParamsList.time_taken_days+' Days');
				}
				if(result.alterSelectedTestProParamsList.time_taken_mins){
				angular.element("#time_taken_mins_text"+product_test_dtl_id).html(result.alterSelectedTestProParamsList.time_taken_mins+' Mins');
				}
				angular.element("#product_test_parameter"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.product_test_dtl_id);
				angular.element("#test_parameter_id"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.test_parameter_id);
				angular.element("#equipment_type_id"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.equipment_type_id);
				angular.element("#method_id"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.method_id);
				angular.element("#standard_value_from"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_from);
				angular.element("#standard_value_to"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_to);
				angular.element("#org_standard_value_from"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_from);
				angular.element("#org_standard_value_to"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_to);
				angular.element("#time_taken_days"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.time_taken_days);
				angular.element("#time_taken_mins"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.time_taken_mins);
				angular.element("#cost_price"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.cost_price);
				angular.element("#selling_price"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.selling_price);
				angular.element("#test_param_alternative_id"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.product_test_param_altern_method_id);
				angular.element("#department_id"+product_test_dtl_id).val(result.alterSelectedTestProParamsList.department_id);
				$scope.funChangeTestParameterValueAccToClaim(product_test_dtl_id);
				$("#viewAlternativeModal").modal("hide");
				$scope.funChangeTestParameterValueAccToClaimUnit(product_test_dtl_id);
			});
		}
	};
	//*****************/display Test Product Standard dropdown*********************

	//*****************display Test Product Standard dropdown* 28 nov*********************
	$scope.funChangeTestParameterValueAccToClaim = function(product_test_dtl_id,type){
		var claimValue = angular.element('#claim_value'+product_test_dtl_id).val();
		
		if(type == "edit"){
			var standardValueFrom = angular.element('#edit_org_standard_value_from'+product_test_dtl_id).val();
			var standardValueTo   = angular.element('#edit_org_standard_value_to'+product_test_dtl_id).val();
		}else{
			var standardValueFrom = angular.element('#org_standard_value_from'+product_test_dtl_id).val();
			var standardValueTo   = angular.element('#org_standard_value_to'+product_test_dtl_id).val();
		}

		if(claimValue!='' && standardValueFrom && standardValueTo){
			var standardValueFromClaimed = standardValueToClaimed = '0.00';
			var standardValueFromClaimed = !isNaN(standardValueFrom) ? ((standardValueFrom * claimValue) / 100).toFixed(9) : standardValueFrom;
			var standardValueToClaimed   = !isNaN(standardValueTo) ? ((standardValueTo * claimValue) / 100).toFixed(9) : standardValueTo;
			angular.element('#standard_value_from_text'+product_test_dtl_id).html(standardValueFromClaimed);
			angular.element('#standard_value_to_text'+product_test_dtl_id).html(standardValueToClaimed);
			angular.element('#standard_value_from'+product_test_dtl_id).val(standardValueFromClaimed);
			angular.element('#standard_value_to'+product_test_dtl_id).val(standardValueToClaimed);
		}else{
			angular.element('#standard_value_from_text'+product_test_dtl_id).html(standardValueFrom);
			angular.element('#standard_value_to_text'+product_test_dtl_id).html(standardValueTo);
			angular.element('#standard_value_from'+product_test_dtl_id).val(standardValueFrom);
			angular.element('#standard_value_to'+product_test_dtl_id).val(standardValueTo);
		}

		$scope.funChangeTestParameterValueAccToClaimUnit(product_test_dtl_id);
	};
	//*****************display Test Product Standard dropdown* 28 nov*********************

	//*****************display Claim Value Unit**********************
	$scope.funChangeTestParameterValueAccToClaimUnit = function(product_test_dtl_id){

		var claimValueInput       = angular.element('#claim_value'+product_test_dtl_id).val();
		var claimValueUnitInput   = angular.element('#claim_value_unit'+product_test_dtl_id).val();
		var standardValueFromUnit = angular.element('#standard_value_from'+product_test_dtl_id).val();
		var standardValueToUnit   = angular.element('#standard_value_to'+product_test_dtl_id).val();

		if (claimValueInput && claimValueUnitInput && isNaN(claimValueUnitInput)){
			if (!isNaN(standardValueFromUnit)) {
				angular.element('#standard_value_from_text'+product_test_dtl_id).html(standardValueFromUnit+' '+claimValueUnitInput);
			}else{
				angular.element('#standard_value_from_text'+product_test_dtl_id).html(standardValueFromUnit);
			}
			if (!isNaN(standardValueToUnit)) {
				angular.element('#standard_value_to_text'+product_test_dtl_id).html(standardValueToUnit+' '+claimValueUnitInput);
			}else{
				angular.element('#standard_value_to_text'+product_test_dtl_id).html(standardValueToUnit);
			}
		}else{
			claimValueUnitInput = '';
			angular.element('#standard_value_from_text'+product_test_dtl_id).html(standardValueFromUnit);
			angular.element('#standard_value_to_text'+product_test_dtl_id).html(standardValueToUnit);
		}
	};
	//*****************/display Claim Value Unit*********************
	
	//*****************/function Check Running Time Required Or Not*********************/
	$scope.funRequiredUnRequiredRunningTimeOrNot = function(product_test_dtl_id,type='add'){		
		if(type == 'add'){
			var runningTimeValue = angular.element('#running_time_id_'+product_test_dtl_id).val();			
			if(runningTimeValue && runningTimeValue == 5){
			    angular.element('#cwap_invoicing_required_'+product_test_dtl_id).val(0);
			    angular.element('#no_of_injection_'+product_test_dtl_id).val('');
			    angular.element('#no_of_injection_'+product_test_dtl_id).prop('readonly', true);
			}else{
			    angular.element('#cwap_invoicing_required_'+product_test_dtl_id).val(1);
			    angular.element('#no_of_injection_'+product_test_dtl_id).prop('readonly', false);
			}
		}else if(type == 'edit'){
			var newRunningTimeValue = angular.element('#new_running_time_id_'+product_test_dtl_id).val();			
			if(newRunningTimeValue && newRunningTimeValue == 5){
			    angular.element('#new_cwap_invoicing_required_'+product_test_dtl_id).val(0);
			    angular.element('#new_no_of_injection_'+product_test_dtl_id).val('');
			    angular.element('#new_no_of_injection_'+product_test_dtl_id).prop('readonly', true);
			}else{
			    angular.element('#new_cwap_invoicing_required_'+product_test_dtl_id).val(1);
			    angular.element('#new_no_of_injection_'+product_test_dtl_id).prop('readonly', false);
			}
		}		
	};
	//*****************/function Check Running Time Required Or Not*********************
	
	//**********Confirm box for Send email***************************************
	
	//*********** select all checkboxes on invoices******************************
	$scope.funSelectNotificationAll = function () {
		$scope.allSelectedNotificationStbOrders = [];
		var checkAllStatus = angular.element('input#select_all_stb_notification').prop('checked');
		if(checkAllStatus){
			$scope.sendMailNotificationBtn = false;	
			angular.element("input.select_all_notification_ckbox").prop('checked',true);
			angular.element("input.select_all_notification_ckbox:checked").each(function() {
				$scope.allSelectedNotificationStbOrders.push(this.value);
			});
		}else{
			$scope.sendMailNotificationBtn = true;
			angular.element('input.select_all_notification_ckbox').prop('checked',false);
		}
	};
	//***********/select all checkboxes on invoices********************************
	
	//******** select one checkbox*************************************************
	$scope.sendMailNotificationBtn = true;	
	$scope.funCheckAtLeastOneNotificationIsChecked=function(stb_order_hdr_dtl_id){
		var checkAtLeastOneIsChecked = angular.element('#stb_order_hdr_dtl_id_'+stb_order_hdr_dtl_id).prop('checked');
		if(checkAtLeastOneIsChecked == false){
			angular.element('input#select_all_stb_notification').prop('checked',false);
		}		
		var stbNotiCheckboxesCount = angular.element('input[name="stb_order_hdr_dtl_id[]"]:checked').length;
		if(stbNotiCheckboxesCount > 0){
			$scope.sendMailNotificationBtn = false;	
		}else{
			$scope.sendMailNotificationBtn = true;
		}
	};
	//********/select one checkbox*************************************************
	
	$scope.funConfirmMailNotificationMessage = function(){
		$ngConfirm({
			title     : false,
			content   : defaultMailMsg, 
			animation : 'right',
			closeIcon : true,
			closeIconClass    : 'fa fa-close',
			backgroundDismiss : false,
			theme   : 'bootstrap',
			columnClass : 'col-sm-5 col-md-offset-3',
			buttons : {
				OK: {
					text: 'Yes',
					btnClass: 'btn-primary',
					action: function () {
					    $scope.funSendMailNotificationOfStabilityOrder();
					}
				},
				cancel: {
					text     : 'No',
					btnClass : 'btn-default ng-confirm-closeIcon'
				}
			}
		});
	};
	$scope.funSendMailNotificationOfStabilityOrder = function(){
		$scope.loaderMainShow();
		$scope.sendMailNotificationBtn = true;
		var http_para_string = {formData : $(erpSendMailNotificationOfStbOrderForm).serialize()};
		$http({
			url: BASE_URL + "sales/stability-orders/send-stability-order-notification-mail",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			if (result.error == 1) {
				angular.element('input.select_all_notification_ckbox').prop('checked',false);
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config) {
			if(status == '500' || status == '404'){
			    $scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*********/Confirm box for Send email***************************************

}).directive('datepicker', function() {
  return {
    require: 'ngModel',
    link: function(scope, el, attr, ngModel) {
      $(el).datepicker({
        onSelect: function(dateText) {
          scope.$apply(function() {
            ngModel.$setViewValue(dateText);
          });
        }
      });
    }
  };

}).directive('validNumber', function() {
    return {
	require: '?ngModel',
	link: function(scope, element, attrs, stabilityOrdersController) {
	    if(!stabilityOrdersController) {return;}
	    stabilityOrdersController.$parsers.push(function(val) {
		if (angular.isUndefined(val)) {var val = '';}
		var clean = val.replace(/[^-0-9\.]/g, '');
		var negativeCheck = clean.split('-');
		var decimalCheck = clean.split('.');
		if(!angular.isUndefined(negativeCheck[1])) {
		    negativeCheck[1] = negativeCheck[1].slice(0, negativeCheck[1].length);
		    clean =negativeCheck[0] + '-' + negativeCheck[1];
		    if(negativeCheck[0].length > 0) {
			clean =negativeCheck[0];
		    }
		}
		if(!angular.isUndefined(decimalCheck[1])) {
		    decimalCheck[1] = decimalCheck[1].slice(0,9);
		    clean = decimalCheck[0] + '.' + decimalCheck[1];
		}
		if (val !== clean) {
		    stabilityOrdersController.$setViewValue(clean);
		    stabilityOrdersController.$render();
		}
		return clean;
	    });
	    element.bind('keypress', function(event) {
		if(event.keyCode === 32) {
		    event.preventDefault();
		}
	    });
	}
    };
}).directive('validAlphabet', function() {
  return {
    require: 'ngModel',
    link: function (scope, element, attr, ngModelCtrl) {
      function fromUser(text) {
        var transformedInput = text.replace(/[^A-Za-z ]/g, '');
        if(transformedInput !== text) {
            ngModelCtrl.$setViewValue(transformedInput);
            ngModelCtrl.$render();
        }
        return transformedInput;
      }
      ngModelCtrl.$parsers.push(fromUser);
    }
  };
});