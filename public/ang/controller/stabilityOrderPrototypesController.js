app.controller('stabilityOrderPrototypesController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {

    //define empty variables
    $scope.currentModule		= 7;
    $scope.orderData 			= '';
    $scope.order_id 			= '';
    $scope.order_no 			= '';
    $scope.order_date 			= '';
    $scope.showAutoSearchList 		= false;
    $scope.buttonText   		= 'Add New';
    $scope.successMessage 		= '';
    $scope.errorMessage   		= '';
    $scope.successMessagePopup 		= '';
    $scope.errorMessagePopup 		= '';
    $scope.orderStabilityCustomer   	= [];
    $scope.orderStabilitySample     	= [];
    $scope.orderAddStabilityPrototype  	= [];
    $scope.orderEditStabilityPrototype  = [];
    $scope.updateStabilityOrder	    	= [];
    $scope.customerListReportingData 	= [];
    $scope.customerListInvoicingData 	= [];
    $scope.disabledUnavailablePrototypeDates = [];
    $scope.updateStabilityOrder.suppliedBy 	= '';
    $scope.updateStabilityOrder.manufacturedBy 	= '';
    $scope.updateStabilityOrder.stb_sample_qty_prev = '';
    $scope.checkedAddStbCountForParamentersList = false;
    $scope.checkedEditStbCountForParamentersList= false;
    $scope.tableTrTdColspanI = 5;
    $scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';

    //sorting variables
    $scope.sortType     = 'order_date';         // set the default sort type
    $scope.sortReverse  = false;                // set the default sort order
    $scope.searchFish   = '';    		// set the default search/filter term

    //**********scroll to top function**********
    $scope.moveToMsg=function(){
	angular.element('html, body').animate({scrollTop: $(".alert").offset().top},500);
    };
    //**********/scroll to top function**********
    
    //**********scroll to top function**********
    $scope.moveToContent=function(content_id){
	angular.element('html, body').animate({scrollTop: $("#"+content_id).offset().top},500);
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
    
    //**********Toggle Content Function************************************
    $scope.toggleButton = function(attr,id) {
	angular.element('#'+attr).slideToggle('slow');
	angular.element('.'+attr).slideToggle('slow');
	if(angular.element('#'+id).hasClass('fa-angle-double-down')){
	    angular.element('#'+id).removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
	}else{
	    angular.element('#'+id).removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
	}
    };
    //*********/Toggle Content Function*****************************************

    //**********If DIV is hidden it will be visible and vice versa*************
    $scope.IsListStabilityOrder 	= false;
    $scope.IsNewStabilityOrder   	= true;
    $scope.IsUpdateStabilityOrder 	= true;
    $scope.IsVisiableSuccessMsg 	= true;
    $scope.IsVisiableErrorMsg 		= true;
    $scope.IsVisiableSuccessMsgPopup    = true;
    $scope.IsVisiableErrorMsgPopup 	= true;
    $scope.IsListPrototypeFormDiv 	= false;
    $scope.IsAddPrototypeFormDiv 	= false;
    $scope.IsEditPrototypeFormDiv 	= true;
    
    $scope.openNewOrderForm = function(){
        $scope.IsNewStabilityOrder  = false;
        $scope.IsListStabilityOrder =  true;
        $scope.funGetTestSampleRecevied();
        $scope.hideAlertMsg();
    };
    //**********/If DIV is hidden it will be visible and vice versa************
    
    //**********back Button****************************************************
    $scope.backButton = function(){
	$scope.IsListStabilityOrder =  false;
	$scope.IsNewStabilityOrder = true;
        $scope.IsUpdateStabilityOrder = true;
        $scope.showAutoSearchList = false;
	$scope.IsAddPrototypeFormDiv  = false;
	$scope.IsEditPrototypeFormDiv = true;
	$scope.orderAddStabilityPrototype = {};
	$scope.orderEditStabilityPrototype = {};	
	$scope.hideAlertMsg();
    };
    //**********/back Button***************************************************
    
    //**********Close Button****************************************************
    $scope.closeButton = function(type){
	if (type == 'add') {
	    $scope.IsAddPrototypeFormDiv  = false;
	    $scope.IsEditPrototypeFormDiv = true;
	}else{
	    $scope.IsAddPrototypeFormDiv  = true;
	    $scope.IsEditPrototypeFormDiv = false;
	}
	$scope.resetPrototypeForm();
    };
    //*********/Close Button****************************************************
    
    //**********reset Form****************************************************
    $scope.resetForm = function(){	
	$scope.orderStabilityCustomer   = {};
	$scope.orderStabilitySample     = {};
	$scope.erpCreateStabilityOrderCSForm.$setUntouched();
	$scope.erpCreateStabilityOrderCSForm.$setPristine();	
	$scope.updateStabilityOrder   = {};
	$scope.erpUpdateStabilityOrderCSForm.$setUntouched();
	$scope.erpUpdateStabilityOrderCSForm.$setPristine();
	$scope.hideAlertMsg();
    };
    $scope.resetPrototypeForm = function(){
	
	//Add Form
	$scope.orderAddStabilityPrototype  = {};
	$scope.testProductParamentersList = {};
	$scope.allAddSelectedStabilityConditionArray = [];
	$scope.allAddDefaultTestParametersArray = [];
	$scope.checkedAddStbCountForParamentersList = false;
	$scope.erpCreateStabilityOrderPrototypeForm.$setUntouched();
	$scope.erpCreateStabilityOrderPrototypeForm.$setPristine();
	
	//Edit Form
	$scope.orderEditStabilityPrototype = {};
	$scope.editTestProductParamentersList = {};
	$scope.allEditSelectedStabilityConditionArray = [];
	$scope.checkedEditStbCountForParamentersList = false;
	$scope.allEditDefaultTestParametersArray = [];
	$scope.erpUpdateStabilityOrderPrototypeForm.$setUntouched();
	$scope.erpUpdateStabilityOrderPrototypeForm.$setPristine();
	
	angular.element('.stb_sample_qty_class').removeClass('addErrorSet').css('border','1px solid #ccc').val('').hide();
	angular.element('.stb_condition_temp_class').removeClass('addErrorSet').css('border','1px solid #ccc').val('').hide();
	angular.element('.add_stb_condition_chk_class').prop('checked',false);
	$scope.hideAlertMsg();
    }
    //**********reset Form****************************************************

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
	$scope.successMessagePopup = message;
	$scope.IsVisiableSuccessMsgPopup = false;
	$scope.IsVisiableErrorMsgPopup = true;
	$scope.moveToMsg();
    };
    //********** /successMsgShowPopup************************************************

    //**********errorMsgShowPopup**************************************************
    $scope.errorMsgShowPopup = function(message){
	$scope.errorMessagePopup = message;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup = false;
	$scope.moveToMsg();
    };
    //********** /hideAlertMsgPopup************************************************

    //**********hideAlertMsgPopup*************
    $scope.hideAlertMsgPopup = function(){
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
    };
    //********** /hideAlertMsgPopup**********************************************

    //*****************display state code dropdown start*****************	
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
    //*****************display state code dropdown end*****************
    
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
    //****************/state dropdown on country change******************
    
    //**********confirm box******************************************************
    $scope.funConfirmPrototypeDeleteMessage = function(stb_order_hdr_dtl_id,stb_order_hdr_id){
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
			$scope.funDeletePrototypeStabilityOrder(stb_order_hdr_dtl_id,stb_order_hdr_id);
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
    $scope.predicate = 'stab_order_hdr_id';
    $scope.reverse   = true;
    $scope.sortBy = function(predicate) {
	    $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
	    $scope.predicate = predicate;
    };
    //************/Sorting list order*****************************************

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
    
    //*****************display Test Product List dropdown**********************
    $scope.testSampleReceviedList = [];
    $scope.funGetTestSampleRecevied = function(){
	$http({
	    method: 'GET',
	    url: BASE_URL +'sales/stability-orders/get-test-sample-received'
	}).success(function (result) {
	    $scope.testSampleReceviedList = result.testSampleReceviedList;
	    $scope.clearConsole();
	});
    };
    //*****************/display Test Product List dropdown*********************
    
    //*****************display division dropdown start******************************
    $scope.divisionsCodeList = [];
    $http({
        method: 'POST',
        url: BASE_URL +'division/get-divisioncodes'
    }).success(function (result) {
        $scope.divisionsCodeList = result;
        $scope.clearConsole();
    });
    //*****************display division dropdown end*********************************
    
    //*****************display sample Priority List dropdown***************************
    $scope.samplePriorityList    = [];
    $scope.samplePriorityCRMList = [];
    $scope.funGetSamplePriorityList = function(stb_customer_priority_id=null){
	$http({
	    method: 'GET',
	    url: BASE_URL +'sales/stability-orders/get_sample_priority_list'
	}).success(function (result){
	    $scope.samplePriorityList    = result.samplePriorityList;
	    $scope.samplePriorityCRMList = result.samplePriorityCRMList;
	    $scope.samplePriorityList    = stb_customer_priority_id == '4' ? $scope.samplePriorityCRMList : $scope.samplePriorityList;
	    $scope.clearConsole();
	});
    };
    //*****************/display sample Priority List code dropdown*********************
    
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
    //$timeout(function (){$scope.getProductCategories();$scope.funGetOrderStateCityTreeViewList();$scope.funGetCountryStateTreeViewList();}, 10000);
    
    //*****************/product category tree list data************************
    
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
    
    //*****************display Billing Type dropdown**********************
    $scope.billingTypeOnEditList = [];
    $scope.funGetBillingTypeList = function(){		
	$http({
	    method: 'POST',
	    url: BASE_URL +'sales/stability-orders/customer_billing_type_list',
	}).success(function (result) {
	    $scope.billingTypeOnEditList = result.billingTypes;
	});
    };
    //*****************/display Billing Type dropdown*********************
    
    //*****************invoicing types*************************************
    $scope.invoicingTypesOnEditList = [];
    $scope.funGetInvoicingTypesList = function(){	
	$http({
	    method: 'POST',
	    url: BASE_URL +'sales/stability-orders/customer-invoicing-types-list',
	}).success(function (result) {
	    if(result){
		$scope.invoicingTypesOnEditList = result.invoicingTypes;
	    }
	    $scope.clearConsole();
	});
    };
    //*****************/invoicing types*************************************
    
    //*****************discount types****************************************
    $scope.discountTypeOnEditList = [];
    $scope.funGetDiscountTypeList = function(){	
	$http({
	    method: 'POST',
	    url: BASE_URL +'sales/stability-orders/discount-types-list',
	}).success(function (result){
	    if(result){
		$scope.discountTypeOnEditList = result.discountTypes;
	    }
	    $scope.clearConsole();
	});
    };
    //*****************customer priority types*****************************************
    
    //*****************display division dropdown start************************
    $scope.submissionTypeList = [];
    $http({
	method: 'POST',
	url: BASE_URL +'sales/stability-orders/get-sample-modes'
    }).success(function (result) {
	$scope.submissionTypeList = result.sampleModeList;
	$scope.clearConsole();
    });
    //*****************display division dropdown end***************************
    
    //*****************display Test Product List dropdown**********************
    $scope.stabilityConditionList = [];
    $scope.funGetStabilityConditionList = function(){
	$http({
	    method: 'GET',
	    url: BASE_URL +'sales/stability-orders/get-stability-condition-master'
	}).success(function (result) {
	    $scope.stabilityConditionList = result.stabilityConditionData;
	    $scope.clearConsole();
	});
    };
    $scope.funGetStabilityConditionList();
    //*****************/display Test Product List dropdown*********************
    
    //********* reporting to fucntions*****/
    $scope.funShowReportingStateCityTreeViewPopup = function(currentModule){
	$(stateCityTreeViewStabilityPopup).modal('show');
	$scope.currentModule=currentModule;
    };
    //**********Invoicing functions***************************************************
    $scope.funShowEditReportingStateCityTreeViewPopup = function(currentModule){
	$(editStateCityTreeViewPopup).modal('show');
	$scope.currentModule=currentModule;
    };
    //*******************filter state/city from tree view****************
    $scope.funGetSelectedStateId = function(selectedNode){
	$scope.funGetReportingInvoicingCustomerOnStateChange(selectedNode.state_id,1);
	$(stateCityTreeViewStabilityPopup).modal('hide');
	$scope.currentModule = 17;
	$(editStateCityTreeViewPopup).modal('hide');
    };
    //**********invoicing functions*************************************************
    $scope.funShowInvoicingStateCityTreeViewPopup = function(currentModule){
	$scope.currentModule=currentModule;
	$(stateCityTreeViewStabilityPopup).modal('show');
    };
    $scope.funShowEditInvoicingStateCityTreeViewPopup = function(currentModule){
	$scope.currentModule=currentModule;
	$(editStateCityTreeViewPopup).modal('show');
    };
    //*******************filter state/city from tree view****************
    $scope.funGetSelectedInvoicingStateId = function(selectedNode){
	$scope.funGetReportingInvoicingCustomerOnStateChange(selectedNode.state_id,2);
	$(stateCityTreeViewStabilityPopup).modal('hide');
	$scope.currentModule = 18;
	$(editStateCityTreeViewPopup).modal('hide');
    };
    //**********/Invoicing functions***************************************************
    //*****************City dropdown on state change*******************************
    $scope.funGetReportingInvoicingCustomerOnStateChange = function(state_id,type){
	if(state_id){
	    $http({
		method: 'GET',
		url: BASE_URL +'sales/stability-orders/get_customer_list/'+state_id
	    }).success(function (result) {
		if (type == '1') {
		   $scope.customerListReportingData = result.customerListData;
		}else if (type == '2') {
		    $scope.customerListInvoicingData = result.customerListData;
		}
		$scope.clearConsole();
	    });
	}
    };
    //**********/invoicing functions***************************************************
    
    //************/Stability Order Listing****************************************
    $scope.filterStabiltyOrders   = {};
    $scope.multiSearchTr = true;
    $scope.funGetStabilityOrdersHttpRequest = function(){	
	$scope.loaderShow();
	var http_para_string = {formData : $(erpFilterStabilityOrderForm).serialize()};	
	$http({
	    url: BASE_URL + "sales/stability-orders/get_list",
	    method: "POST",
	    data: http_para_string,
	}).success(function(result, status, headers, config){
	    $scope.orderData = result.orderList;
	    $scope.clearConsole();
	    $scope.loaderHide();
	}).error(function(data, status, headers, config) {
	    if(status == '500' || status == '404'){
		$scope.errorMsgShowPopup($scope.defaultMsg);
	    }
	    $scope.loaderHide();
	    $scope.clearConsole();
	});
    };
    //**********Get stability order list*********************************
    $scope.funGetStabilityOrdersList = function(divisionId){
	$scope.divisionID     = divisionId;
	$scope.searchStringID = $scope.searchStringID;
	$scope.funGetStabilityOrdersHttpRequest();
    };
    //*******Search on stabilty orders(search button click)**************
    $scope.funFilterStabilityOrder = function(){
	$scope.funGetStabilityOrdersHttpRequest();
    };
    //*******Refresh  search and get list (refresh button click)********
    $scope.funRefreshStabilityOrdersList = function(){
	$scope.divisionID     = '';
	$scope.searchStringID = '';
	$scope.filterStabiltyOrders   = {};
	$scope.erpFilterStabilityOrderForm.$setUntouched();
	$scope.erpFilterStabilityOrderForm.$setPristine();
	$timeout(function () {
	    $scope.funGetStabilityOrdersHttpRequest();
	}, 500);
    };
    
    //*************** show hide  multi search ************************
    $scope.funOpenStabilityMultisearch = function(){
        $scope.multiSearchTr=false;
        $scope.multisearchBtn=true;
    };
   
    $scope.funCloseStabilityMultisearch = function(){
        $scope.multiSearchTr=true;
        $scope.multisearchBtn=false;
        $scope.funRefreshStabilityOrdersList();
    };
    //***********Refresh (multi search)*******************************
    $scope.funRefreshStabilityMultisearch = function(divisionID){
	$scope.funRefreshStabilityOrdersList();
    };
    //******* search on stabilty orders(multi search onchange)********
    var tempSearchKeyword;
    $scope.getStabilityMultiSearch=function(searchKeyword){
	tempSearchKeyword = searchKeyword;
	$timeout(function () {
	    if(tempSearchKeyword == searchKeyword){
		$scope.searchOrder = searchKeyword;
		$scope.funGetStabilityOrdersHttpRequest();
	    }
	},1000);
    };
    //************Global search****************************************
    var tempOrderSearchTerm;
    $scope.funFilterStabilityOrderOnSearch = function(keyword){
	tempOrderSearchTerm = keyword;
	$timeout(function () {
	    if (keyword == tempOrderSearchTerm) {
		$scope.searchStringID = keyword;
		$scope.funGetStabilityOrdersHttpRequest();
	    }
	}, 800);
    };
    //************/Stability Order Listing****************************************
        
    //*****************Listing of Customer Detail based on Smaple ID*******************
    $scope.funGetCustomerAttachedSampleDetail = function(stbSampleId){
	
	$scope.orderStabilityCustomer.stb_customer_id          = null;
	$scope.orderStabilityCustomer.stb_city_name            = null;
	$scope.orderStabilityCustomer.stb_mfg_lic_no           = null;
	$scope.orderStabilityCustomer.stb_sale_executive_name  = null;
	$scope.orderStabilityCustomer.stb_sale_executive       = null;
	$scope.orderStabilityCustomer.stb_discount_type_name   = null;
	$scope.orderStabilityCustomer.stb_discount_type_id     = null;
	$scope.orderStabilityCustomer.stb_discount_value       = null;
	$scope.orderStabilityCustomer.stb_customer_priority_id = null;
	$scope.orderStabilityCustomer.stb_po_no 	       = null;
	$scope.orderStabilityCustomer.stb_po_date 	       = null;
	$scope.orderStabilitySample.stb_sample_priority_id     = {};
	$scope.orderStabilitySample.stb_submission_type        = {};
	$scope.customerListReportingData 		       = [];
	$scope.customerListInvoicingData                       = [];
	
	if(stbSampleId){
	    $scope.loaderMainShow();	    
	    $http({
		method: 'GET',
		url: BASE_URL +'sales/stability-orders/get-customer-attached-sample-detail/'+stbSampleId
	    }).success(function (result) {
		if(result.error == 1){
		    
		    $scope.stbSampleID 			= stbSampleId;
		    $scope.orderStabilityCustomerDetail = result.customerAttachedSampleList;
		    $scope.customerInvoicingTypeID 	= result.customerAttachedSampleList.invoicing_type_id;
		    $scope.globalProductCategoryID 	= result.customerAttachedSampleList.product_category_id;
		    $scope.globalCustomerID 		= result.customerAttachedSampleList.customer_id;
		    
		    //Setting Submission Type
		    $scope.orderStabilitySample.stb_submission_type = { id : $scope.orderStabilityCustomerDetail.sample_mode_id};
		    $scope.funSubmissionTypeValue(result.customerAttachedSampleList.sample_mode_id);
		    
		    //Sample Priority Dropdown
		    $scope.samplePriorityList = result.samplePriorityList;
		    
		    //Billing Type selected according to customer
		    $scope.billingTypeList = [{id: result.customerAttachedSampleList.billing_type_id, name: result.customerAttachedSampleList.billing_type}];
		    $scope.orderStabilityCustomer.stb_billing_type_id = {id:result.customerAttachedSampleList.billing_type_id};
    
		    //customers dropdown according to sample
		    $scope.customerNameList = [{customer_id: result.customerAttachedSampleList.customer_id, customer_name: result.customerAttachedSampleList.customer_name}];
		    $scope.orderStabilityCustomer.stb_customer_id = {customer_id : result.customerAttachedSampleList.customer_id};
		    $scope.orderStabilitySample.stb_customer_name = result.customerAttachedSampleList.customer_name;
    
		    //branch selected according to sample
		    $scope.orderStabilitySample.stb_division_id = {selectedOption: { id: result.customerAttachedSampleList.division_id}};
		    
		    //Showing Selected Sample Priority in case of CRM
		    if(result.customerAttachedSampleList.customer_priority_id){
			$scope.orderStabilitySample.stb_sample_priority_id = {sample_priority_id:result.customerAttachedSampleList.customer_priority_id};
			$scope.funSetSurchargeValue(result.customerAttachedSampleList.customer_priority_id);
		    }	    
		
		    //invoicing type selected according to customer
		    $scope.invoicingTypeList    		 	  = [{id: result.customerAttachedSampleList.invoicing_type_id, name: result.customerAttachedSampleList.invoicing_type}];
		    $scope.orderStabilityCustomer.stb_customer_city       = result.customerAttachedSampleList.city_id;
		    $scope.orderStabilityCustomer.stb_customer_city_name  = result.customerAttachedSampleList.city_name;
		    $scope.orderStabilityCustomer.stb_mfg_lic_no          = result.customerAttachedSampleList.mfg_lic_no;
		    $scope.orderStabilityCustomer.stb_sale_executive_name = result.customerAttachedSampleList.name;
		    $scope.orderStabilityCustomer.stb_sale_executive      = result.customerAttachedSampleList.sale_executive;
		    $scope.orderStabilityCustomer.stb_discount_type_name  = result.customerAttachedSampleList.discount_type_name;
		    $scope.orderStabilityCustomer.stb_discount_type_id    = result.customerAttachedSampleList.discount_type_id;
		    $scope.orderStabilityCustomer.stb_discount_value      = result.customerAttachedSampleList.discount_value;
		    $scope.orderStabilityCustomer.stb_invoicing_type_id   = {id:result.customerAttachedSampleList.invoicing_type_id};
		    
		    //Checking Billing Type is PO-Wise,then PO & PO-Date will be required
		    $scope.showAddPOTypeInStabilityOrder = $scope.orderStabilityCustomerDetail.billing_type_id == '5' ? true : false;
		}else{
		    $scope.errorMsgShow(result.message);
		}
		$scope.loaderMainHide();
		$scope.clearConsole();
	    });
	}
    };
    //*****************Listing of Customer Detail based on Smaple ID******************************
    
    //*****************New Auto complete Section**********************************
    $scope.getAutoSearchSampleMatches = function(searchText,sampleId){
	$http({
	    method: 'GET',
	    url: BASE_URL +'sales/stability-orders/get-sample-name-list/'+sampleId+'/'+searchText
	}).success(function (result) {
	    $scope.resultItems 	        = result.itemsList;
	    $scope.customerInvoicingTypeID  = result.invoicing_type_id;
	    $scope.fixedRateInvoicingTypeID = result.fixed_rate_invoicing_type_id;
	    $scope.stbSampleID 	            = sampleId;
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
    $scope.funSetSelectedSampleStabilityOrder = function(selectedSampleId,selectedSampleName,selectedProductId,formType){
	var selectedSampleNameArr = selectedSampleName.split ("|");
	if(selectedSampleNameArr[0] !== '') {
	    if(formType == 'add'){
		$scope.orderStabilitySample.stb_product_id = selectedProductId;
		$scope.orderStabilitySample.stb_sample_description_id = selectedSampleId;
		$scope.orderStabilitySample.stb_sample_description_name = selectedSampleNameArr[0].trim();
		$scope.showAutoSearchList = false;
	    }else if(formType == 'edit'){
		$scope.updateStabilityOrder.stb_product_id = selectedProductId;
		$scope.updateStabilityOrder.stb_sample_description_id = selectedSampleId;
		$scope.updateStabilityOrder.stb_sample_description_name = selectedSampleNameArr[0].trim();
		$scope.showAutoSearchList = false;
	    }
	}
    };
    //****************/Set parameter value when user selecet from auto search list***************
    
    //*****************display customer name on check of checkbox********************************
    $scope.funCheckAddCustomer = function(checked,customerName,type){
	if(type == "stb_supplied_by"){
	    if(checked == false){
		$scope.orderStabilitySample.stb_supplied_by = '';
	    }else {
		$scope.orderStabilitySample.stb_supplied_by = customerName;
	    }
	}else if(type == "stb_manufactured_by"){
	    if(checked == false){
		$scope.orderStabilitySample.stb_manufactured_by = '';
	    }else {
		$scope.orderStabilitySample.stb_manufactured_by = customerName;
	    }
	}
    }
    //*****************/display customer list location and Mfg Lic Number************************
    
    //*****************display customer name on check of checkbox********************************
    $scope.funCheckEditCustomer = function(checked,customerName,type){
	if(type == "stb_supplied_by"){
	    if(checked == false){
		$scope.updateStabilityOrder.stb_supplied_by = '';
	    }else {
		$scope.updateStabilityOrder.stb_supplied_by = customerName;
	    }
	}else if(type == "stb_manufactured_by"){
	    if(checked == false){
		$scope.updateStabilityOrder.stb_manufactured_by = '';
	    }else {
		$scope.updateStabilityOrder.stb_manufactured_by = customerName;
	    }
	}
    }
    //*****************/display customer list location and Mfg Lic Number************************
    
    //*****************display division dropdown*****************
    $scope.isSetSurchargeValueFlag = false;
    $scope.funSetSurchargeValue = function(samplePriorityId){
	$scope.isSetSurchargeValueFlag = false;
	if(samplePriorityId == 3){
	    $scope.isSetSurchargeValueFlag  = true;
	}else{
	    $scope.orderStabilitySample.stb_surcharge_value = '';
	}
    };
    //*****************/display division dropdown*****************/

    //*****************display division dropdown*****************
    $scope.advanceDetailsDisplay = false;
    $scope.funSubmissionTypeValue = function(submissionTypeId){
	$scope.advanceDetailsDisplay = false;
	if(submissionTypeId==1){
	    $scope.advanceDetailsDisplay = true;
	}else{
	    $scope.orderStabilitySample.stb_advance_details = '';
	}
    };
    //*****************/display division dropdown*****************
    
    //*****************display division dropdown*****************
    $scope.isEditSetSurchargeValueFlag = false;
    $scope.funEditSetSurchargeValue = function(samplePriorityId){
	$scope.isEditSetSurchargeValueFlag = false;
	if(samplePriorityId == 3){
	    $scope.isEditSetSurchargeValueFlag  = true;
	}else{
	    $scope.updateStabilityOrder.stb_surcharge_value = '';
	}
    };
    //*****************/display division dropdown*****************/
    
    //*****************display division dropdown*****************
    $scope.editAdvanceDetailsDisplay = false;
    $scope.funEditSubmissionTypeValue = function(submissionTypeId){
	$scope.editAdvanceDetailsDisplay = false;
	if(submissionTypeId == 1){
	    $scope.editAdvanceDetailsDisplay = true;
	}else{
	    $scope.updateStabilityOrder.stb_advance_details = '';
	}
    };
    //*****************/display division dropdown*****************
    
    //***************Saving of Customer and Sample Detail of Stability Orders*********************
    $scope.funSaveCustomerSampleOfStabilityOrder = function(){
	
	if(!$scope.erpCreateStabilityOrderCSForm.$valid)return;
	if($scope.newStabilityOrderCSflag)return;
	$scope.newStabilityOrderCSflag = true;
	$scope.loaderMainShow();
	var http_para_string = {formData : $(erpCreateStabilityOrderCSForm).serialize()};

	$http({
	    url: BASE_URL + "sales/stability-orders/create-stability-order-cs",
	    method: "POST",
	    data: http_para_string
	}).success(function(result, status, headers, config) {
	    $scope.newStabilityOrderCSflag = false;
	    if(result.error == 1){
		$scope.backButton();
		$scope.resetForm();
		$scope.stbOrderHdrID = result.stb_order_hdr_id;
		$scope.funEditCustomerSampleOfStabilityOrder(result.stb_order_hdr_id);
		$scope.toggleButton('stbEditCustomerSample','button-editcustomersample-id');
		$scope.successMsgShow(result.message);
	    }else{
		$scope.errorMsgShow(result.message);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	}).error(function(result, status, headers, config) {
	    $scope.newStabilityOrderCSflag = false;
	    if(status == '500' || status == '404'){
		$scope.errorMsgShow($scope.defaultMsg);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	});	
    };
    //***************/Saving of Customer and Sample Detail of Stability Orders*********************
    
    //*****************/discount types onchange**************************************************
    $scope.applyDiscountTypeYes = true;
    $scope.applyDiscountTypeNo = false;
    $scope.funGetDiscountTypeInput = function(discountTypeId,type){
	if(discountTypeId && type == 'edit'){
	    $scope.updateStabilityOrder.stb_discount_value = null;
	    if(discountTypeId == '3'){
		$scope.applyDiscountTypeYes = false;
		$scope.applyDiscountTypeNo = true;
		$scope.updateStabilityOrder.stb_discount_value = null;
	    }else{
		$scope.applyDiscountTypeYes = true;
		$scope.applyDiscountTypeNo = false;
		$scope.updateStabilityOrder.stb_discount_value = $scope.updateStabilityOrder.stb_discount_value_set;
	    }
	}
    }
    //*****************/discount types onchange***************************************************
    
    //*****************display customer list location and Mfg Lic Number********
    $scope.funEditGetCustomerAttachedSampleDetail = function(stb_order_hdr_id,sampleId){
	
	$scope.funGetBillingTypeList();	       //Getting Default Billing Type
	$scope.funGetInvoicingTypesList();     //Getting Default Invoicing Type
	$scope.funGetDiscountTypeList();       //Getting Default Discount Type
	
	if(stb_order_hdr_id){
	    $http({
		method: 'GET',
		url: BASE_URL +'sales/stability-orders/get-edit-customer-attached-sample-detail/'+stb_order_hdr_id+'/'+sampleId
	    }).success(function (result){
		if(result.error == 1){
		    $scope.stbSampleID		       = sampleId;
		    $scope.customerNameList            = result.customerNameList;
		    $scope.salesExecutiveList 	       = result.salesExecutiveList;
		    $scope.discountTypeSetOnEditList   = $scope.discountTypeOnEditList;
		    $scope.billingTypeSetOnEditList    = $scope.billingTypeOnEditList;
		    $scope.invoicingTypesSetOnEditList = $scope.invoicingTypesOnEditList;
		    $scope.samplePrioritySetOnEditList = result.samplePriorityList;
		    $scope.customerInvoicingTypeID     = result.customerAttachedSampleList.stb_invoicing_type_id;
		    $scope.globalProductCategoryID     = result.customerAttachedSampleList.stb_product_category_id;
		    $scope.globalCustomerID 	       = result.customerAttachedSampleList.stb_customer_id;
		}
		$scope.clearConsole();
	    });
	}
    };
    //*****************/display customer list location and Mfg Lic Number*************************
    
    //**********Editing of Stability Order*********************************************************
    $scope.funEditCustomerSampleOfStabilityOrder  = function(stb_order_hdr_id){
	
	$scope.customerListReportingData = [];
	$scope.customerListInvoicingData = [];
	$scope.addedStabilityOrderPrototypeList = [];
	$scope.IsListStabilityOrder 	 = true;
	$scope.IsNewStabilityOrder 	 = true;
        $scope.IsUpdateStabilityOrder 	 = false;	
	$scope.hideAlertMsg();
	$scope.loaderMainShow();
	
	$http({
	    url: BASE_URL + "sales/stability-orders/edit-stability-order/"+stb_order_hdr_id,
	    method: "GET",
	}).success(function(result, status, headers, config){
	    if(result.error == 1){
		
		$scope.updateStabilityOrder = result.stbOrderList;
		$scope.stbSampleID = result.stbOrderList.stb_sample_id;
		$scope.stbOrderHdrID = result.stbOrderList.stb_order_hdr_id;
		$scope.isAllStbOrderPrototypeBooked = result.stbOrderList.stb_status == '0' ? false : true;
		$scope.funEditGetCustomerAttachedSampleDetail(stb_order_hdr_id,result.stbOrderList.stb_sample_id);
		
		//Setting previous Sample Qty
		$scope.updateStabilityOrder.stb_sample_qty_prev = result.stbOrderList.stb_sample_qty;
		
		//Sample Information Detail
		$scope.sampleWithPlaceName    = result.stbOrderList.sample_no+'/'+result.stbOrderList.customer_name+'/'+result.stbOrderList.state_name+'/'+result.stbOrderList.city_name;
		$scope.testSampleReceviedList = [{id: result.stbOrderList.stb_sample_id, name: $scope.sampleWithPlaceName}];
		$scope.updateStabilityOrder.stb_sample_id = { selectedOption: { id : result.stbOrderList.stb_sample_id} };		
				
		//Setting Customer Section
		$scope.updateStabilityOrder.stb_customer_name 	    = result.stbOrderList.customer_name;
		$scope.updateStabilityOrder.stb_customer_id         = {selectedOption: { customer_id : result.stbOrderList.stb_customer_id} };
		$scope.updateStabilityOrder.stb_customer_city_name  = result.stbOrderList.city_name;
		$scope.updateStabilityOrder.stb_customer_city       = result.stbOrderList.stb_customer_city;
		$scope.updateStabilityOrder.stb_mfg_lic_no          = result.stbOrderList.stb_mfg_lic_no;
		$scope.updateStabilityOrder.stb_sale_executive      = {selectedOption: { id : result.stbOrderList.stb_sale_executive} };
		$scope.updateStabilityOrder.stb_discount_value      = result.stbOrderList.stb_discount_value;
		$scope.updateStabilityOrder.stb_discount_value_set  = result.stbOrderList.stb_discount_value;
		$scope.funGetDiscountTypeInput(result.stbOrderList.stb_discount_type_id,'edit');
		$scope.updateStabilityOrder.stb_discount_type_id    = {id:result.stbOrderList.stb_discount_type_id};
		$scope.updateStabilityOrder.stb_billing_type_id     = {id:result.stbOrderList.stb_billing_type_id};
		$scope.updateStabilityOrder.stb_invoicing_type_id   = {id:result.stbOrderList.stb_invoicing_type_id};
		
		//Checking Billing Type is PO-Wise,then PO & PO-Date will be required
		$scope.showEditPOTypeInStabilityOrder = result.stbOrderList.stb_po_no || result.stbOrderList.stb_po_date ? true : false;
		
		//Reporting To and Invoicing To Setting
		if(result.stbOrderList.reportingCustomerName){
		    $scope.customerListReportingData = [{ id : result.stbOrderList.reportingCustomerId ,name:result.stbOrderList.reportingCustomerName}];
		    $scope.updateStabilityOrder.stb_reporting_to = {selectedOption:{id:result.stbOrderList.stb_reporting_to }};
		}
		if(result.stbOrderList.invoicingCustomerName){
		    $scope.customerListInvoicingData = [{ id : result.stbOrderList.invoicingCustomerId ,name:result.stbOrderList.invoicingCustomerName}];
		    $scope.updateStabilityOrder.stb_invoicing_to = {selectedOption:{id:result.stbOrderList.stb_invoicing_to }};
		}
		
		//Sample Detail
		$scope.updateStabilityOrder.stb_edit_division_id = result.stbOrderList.stb_division_id;
		$scope.updateStabilityOrder.stb_division_id = { selectedOption: { id : result.stbOrderList.stb_division_id} };
		$scope.updateStabilityOrder.suppliedBy = result.stbOrderList.stb_supplied_by ? true : false;
		$scope.updateStabilityOrder.manufacturedBy = result.stbOrderList.stb_manufactured_by ? true : false;
		
		//Sample Detail Dropdown Setting
		$scope.funEditSetSurchargeValue(result.stbOrderList.stb_sample_priority_id);
		$scope.funEditSubmissionTypeValue(result.stbOrderList.stb_submission_type);
		$scope.updateStabilityOrder.stb_sample_priority_id = { selectedOption: { sample_priority_id : result.stbOrderList.stb_sample_priority_id} };
		$scope.updateStabilityOrder.stb_is_sealed       = { selectedOption: { id : result.stbOrderList.stb_is_sealed} };
		$scope.updateStabilityOrder.stb_is_signed       = { selectedOption: { id : result.stbOrderList.stb_is_signed} };
		$scope.updateStabilityOrder.stb_submission_type = { selectedOption: { id : result.stbOrderList.stb_submission_type} };
		
		//Product Master Dropdown
		$scope.testProductMasterList = result.testProductMasterList;
		
		//Getting Added Stability Order Prototypes List
		$scope.funGetAddedStabilityOrderPrototypeList(result.stbOrderList.stb_order_hdr_id);
	    }else{
		$scope.errorMsgShow(result.message);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	}).error(function(result, status, headers, config){
	    if(status == '500' || status == '404'){
		$scope.errorMsgShowPopup($scope.defaultMsg);
	    }
	    $scope.loaderHide();
	    $scope.clearConsole();
	});
    };
    //**********Editing of Stability Order********************************************************

    //***************Saving of Prototype Detail of Stability Orders********************************
    $scope.funUpdateCustomerSampleOfStabilityOrder = function(){
	
	if(!$scope.erpUpdateStabilityOrderCSForm.$valid)return;
	if($scope.newUpdateStabilityOrderCSflag)return;
	$scope.newUpdateStabilityOrderCSflag = true;
	$scope.loaderMainShow();
	var http_para_string = {formData : $(erpUpdateStabilityOrderCSForm).serialize()};

	$http({
	    url: BASE_URL + "sales/stability-orders/update-stability-order-cs",
	    method: "POST",
	    data: http_para_string
	}).success(function(result, status, headers, config) {
	    $scope.newUpdateStabilityOrderCSflag = false;
	    if(result.error == 1){
		$scope.stbOrderHdrID = result.stb_order_hdr_id;
		$scope.funEditCustomerSampleOfStabilityOrder(result.stb_order_hdr_id);
		if(result.has_sample_updated == 1){
		    $scope.productTestMasterList = [];
		    $scope.allAddSelectedStabilityConditionArray = [];
		    $scope.testProductParamentersList = [];
		}
		$scope.successMsgShow(result.message);
	    }else{
		$scope.errorMsgShow(result.message);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	}).error(function(result, status, headers, config) {
	    $scope.newUpdateStabilityOrderCSflag = false;
	    if(status == '500' || status == '404'){
		$scope.errorMsgShow($scope.defaultMsg);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	});
    };
    //***************/Saving of Prototype Detail of Stability Orders********************************
    
    //*****************display Test Product Standard dropdown******************
    $scope.productTestMasterList = [];
    $scope.funGetProductTestMaster = function(product_id){
	if(product_id){
	    $http({
		method: 'GET',
		url: BASE_URL +'sales/stability-orders/get-product-tests/'+product_id
	    }).success(function (result){
		$scope.productTestMasterList = result.productTestList;
		$scope.productTestStandardList = result.testStandardList;
		$scope.stb_test_standard = result.testStandardList.test_standard_id;
	    });
	}
    };
    //*****************/display Test Product Standard dropdown******************
    
    //*****************single checkbox***************************************
    $scope.allAddSelectedStabilityConditionArray = [];
    $scope.funGetAddSampleQtyFieldOnStbSelection = function(stb_stability_type_id,divId){
	var paraStatus = angular.element('#'+divId+stb_stability_type_id).prop('checked');
	var checkedStbCount = angular.element('.add_stb_condition_chk_class:checkbox:checked').length;
	if(paraStatus){
	    angular.element('#stb_sample_qty'+stb_stability_type_id).addClass('addErrorSet').show();
	    angular.element('#stb_condition_temperature'+stb_stability_type_id).addClass('addErrorSet').show();
	    $scope.allAddSelectedStabilityConditionArray.push(stb_stability_type_id);
	}else{
	    angular.element('#stb_sample_qty'+stb_stability_type_id).removeClass('addErrorSet').val('').hide();
	    angular.element('#stb_condition_temperature'+stb_stability_type_id).removeClass('addErrorSet').val('').hide();
	    $scope.allAddSelectedStabilityConditionArray.splice( $scope.allAddSelectedStabilityConditionArray.indexOf(stb_stability_type_id), 1 );
	}
	if(checkedStbCount){
	    $scope.checkedAddStbCountForParamentersList = true;
	}else{
	    $scope.checkedAddStbCountForParamentersList = false;
	}
	$scope.tableTrTdColspanI = checkedStbCount ? checkedStbCount + 5 : 5;
    };
    //*****************single checkbox**************************************************
    
    //*****************single checkbox***************************************************
    $scope.allAddDefaultTestParametersArray = [];
    $scope.funAllAddDefaultTestParametersDetail = function(product_test_dtl_id,stb_stability_type_id,divId){    
	var product_stability_id = product_test_dtl_id+'-'+stb_stability_type_id;
	var paraCheckedStatus = angular.element('#'+divId+'_'+product_test_dtl_id+'_'+stb_stability_type_id).prop('checked');	
	if(paraCheckedStatus){
	    $scope.allAddDefaultTestParametersArray.push(product_stability_id);
	}else{
	    $scope.allAddDefaultTestParametersArray.splice( $scope.allAddDefaultTestParametersArray.indexOf(product_stability_id), 1 );
	    angular.element('#check_all_add_stb_condition_div_id'+stb_stability_type_id).prop('checked',false);
	}
    };
    //*****************single checkbox***************************************************

    //************Start Date/End Date Validation*****************************************
    $(document).ready(function () {
	$("#add_stb_start_date").datepicker({
	    dateFormat: "dd-mm-yy",
	    minDate: 0,
	    onSelect: function (date) {
		var dt1 = $('#add_stb_start_date').val();
		var dt2 = $('#add_stb_end_date');
		var startDate = $(this).datepicker('getDate');
		var minDate = $(this).datepicker('getDate');
		startDate.setDate(startDate.getDate() + 1800);
		dt2.datepicker('option', 'maxDate', startDate);
		dt2.datepicker('option', 'minDate', minDate);
		$timeout(function (){
		    $scope.orderAddStabilityPrototype.stb_start_date = dt1;
		    $scope.orderAddStabilityPrototype.stb_label_name = $.datepicker.formatDate("MM-yy", $('#add_stb_start_date').datepicker('getDate'));
		}, 100);
	    }
	});
	$('#add_stb_end_date').datepicker({
	    dateFormat: "dd-mm-yy",
	    onSelect: function (date) {
		var dt2 = $('#add_stb_end_date').val();
		$timeout(function (){
		    $scope.orderAddStabilityPrototype.stb_end_date = dt2;
		}, 100);
	    }
	});
    });
    //****************/Start Date/End Date Validation***********************************
    
    //*****************Getting Product Test Master in Tabular Format********************
    $scope.funGetAddProductTestMasterTabularList = function(){
	
	$scope.testProductParamentersList = [];
	if(!$scope.erpCreateStabilityOrderPrototypeForm.$valid)return;
	if($scope.newCreateStabilityOrderPrototypeflag)return;
	$scope.newCreateStabilityOrderPrototypeflag = true;
	var http_para_string = {formData : $(erpCreateStabilityOrderPrototypeForm).serialize()};

	$http({
	    url: BASE_URL + "sales/stability-orders/get-product-test-master-tabular-list",
	    method: "POST",
	    data: http_para_string
	}).success(function(result, status, headers, config) {
	    $scope.newCreateStabilityOrderPrototypeflag = false;
	    if(result.error == 1){
		$scope.testProductParamentersList = result.productTestParametersList;
		$scope.orderAddStabilityPrototype.stb_test_standard_id = result.testMasterList.test_standard_id;
	    }else{
		$scope.errorMsgShow(result.message);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	}).error(function(result, status, headers, config) {
	    $scope.newCreateStabilityOrderPrototypeflag = false;
	    if(status == '500' || status == '404'){
		$scope.errorMsgShow($scope.defaultMsg);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	});
    };
    //*****************/Getting Product Test Master in Tabular Format***************
    
    //*************Validate Sample Qty Temperature**********************************
    $scope.validateSampleQtyTempStbOrderPrototypeForm = function(className){
	var y, i, valid = true;
	y = document.getElementsByClassName(className);
	for (i = 0; i < y.length; i++) {
	    y[i].style.borderColor = "#CCC";
	    if (y[i].value == "") {
		y[i].style.borderColor = "red"; 
		valid = false;
		$scope.moveToContent('style-default');
	    }
	}
	return valid;
    };
    //************/Validate Sample Qty Temperature**********************************
    
    //***************Saving of Prototype Detail of Stability Orders*****************
    $scope.funSavePrototypeOfStabilityOrder = function(){
	
	if(!$scope.erpCreateStabilityOrderPrototypeForm.$valid)return;
	if(!$scope.validateSampleQtyTempStbOrderPrototypeForm('addErrorSet'))return;
	if($scope.newCreateStabilityOrderPrototypeflag)return;
	$scope.newCreateStabilityOrderPrototypeflag = true;
	$scope.loaderMainShow();
	var http_para_string = {formData : $(erpCreateStabilityOrderPrototypeForm).serialize()};

	$http({
	    url: BASE_URL + "sales/stability-orders/save-prototype-stability-order",
	    method: "POST",
	    data: http_para_string
	}).success(function(result, status, headers, config) {
	    $scope.newCreateStabilityOrderPrototypeflag = false;
	    if(result.error == 1){
		$scope.resetPrototypeForm();
		$scope.stbOrderHdrID = result.stb_order_hdr_id;
		$scope.stbOrderHdrDtlID = result.stb_order_hdr_dtl_id;
		$scope.allAddDefaultTestParametersArray = [];
		$scope.allAddSelectedStabilityConditionArray = [];
		$scope.funGetAddedStabilityOrderPrototypeList(result.stb_order_hdr_id);
		$scope.successMsgShow(result.message);
	    }else{
		$scope.errorMsgShow(result.message);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	}).error(function(result, status, headers, config) {
	    $scope.newCreateStabilityOrderPrototypeflag = false;
	    if(status == '500' || status == '404'){
		$scope.errorMsgShow($scope.defaultMsg);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	});
    };
    //***************/Saving of Prototype Detail of Stability Orders********************************
    
    //*****************Getting Added Stability Order Prototype List*********************************
    $scope.addedStabilityOrderPrototypeList = [];
    $scope.funGetAddedStabilityOrderPrototypeList = function(stb_order_hdr_id){	
	var http_para_string = {formData : 'stb_order_hdr_id='+stb_order_hdr_id};
	$http({
	    url: BASE_URL + "sales/stability-orders/get-added-stability-order-prototype-list",
	    method: "POST",
	    data: http_para_string
	}).success(function(result, status, headers, config){
	    if(result.error == 1){
		$scope.addedStabilityOrderPrototypeList = result.addedStabilityOrderPrototypeDetail;
		$scope.bookedStabilityConditionList = result.bookedStabilityConditionList;
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	}).error(function(result, status, headers, config) {
	    if(status == '500' || status == '404'){
		$scope.errorMsgShow($scope.defaultMsg);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	});
    };
    //****************/Getting Added Stability Order Prototype List*********************************
    
    //***************Editing of Prototype Detail of Stability Orders********************************
    $scope.funEditPrototypeOfStabilityOrder = function(stb_order_hdr_dtl_id,stb_order_hdr_id){
	
	if($scope.newEditStabilityOrderPrototypeflag)return;
	$scope.newEditStabilityOrderPrototypeflag = true;
	$scope.IsAddPrototypeFormDiv  = true;
	$scope.IsEditPrototypeFormDiv = false;
	$scope.closeButton('edit');
	$scope.loaderMainShow();
	var http_para_string = {formData : 'stb_order_hdr_id='+stb_order_hdr_id+'&stb_order_hdr_dtl_id='+stb_order_hdr_dtl_id};

	$http({
	    url: BASE_URL + "sales/stability-orders/edit-added-stability-order-prototype-list",
	    method: "POST",
	    data: http_para_string
	}).success(function(result, status, headers, config) {
	    $scope.newEditStabilityOrderPrototypeflag = false;	    
	    if(result.error == 1){
		
		//Setting Disbaled Mode for Edit/View
		$scope.isAllStbOrderPrototypeBooked = result.returnData.stb_order_hdr_detail_status == '0' ? false : true;
		
		//Setting Model Detail
		var editStabilityPrototypeHeaderList = result.returnData.header;
		$scope.orderEditStabilityPrototype = editStabilityPrototypeHeaderList;
		$scope.orderEditStabilityPrototype.stb_order_hdr_detail_status = result.returnData.stb_order_hdr_detail_status;
		
		//Setting Prototype Prototype Stability Condition Detail
		$scope.allEditSelectedStabilityConditionArray = result.returnData.stability_detail;
		
		//Setting Prototype Stability STF Detail
		$scope.allEditDefaultTestProductSTFArray = result.returnData.stb_product_test_stf_detail;
		
		//Setting Prototype Test Parameters Detail
		$scope.allEditDefaultTestParametersArray = result.returnData.header_detail;
		$scope.tableTrTdColspanI = result.returnData.stability_detail.length ? result.returnData.stability_detail.length + 5 : 5;
		
		//Setting Sample Qty Detail
		var allEditSelectedSampleQtyArray = result.returnData.sample_qty_detail;
		if (allEditSelectedSampleQtyArray) {
		    $timeout(function (){angular.element.each(allEditSelectedSampleQtyArray, function(key,val){angular.element('#edit_stb_sample_qty'+key).val(val).show();});}, 100);
		}
		
		//Setting Prototype Stability ondition Temperature Detail
		var allEditSelectedConditionTempArray = result.returnData.stb_condition_temperature;
		if (allEditSelectedConditionTempArray) {
		    $timeout(function (){angular.element.each(allEditSelectedConditionTempArray, function(key,val){angular.element('#edit_stb_condition_temperature'+key).val(val).show();});}, 100);
		}
		
		//Getting the Tabular View of Test Parameters
		$scope.funGetEditProductTestMasterTabularList(editStabilityPrototypeHeaderList.stb_product_test_id);	//Getting Tabular View On Selecting Edit Button
		
		//Setting Prototype Detail
		$scope.testProductMaster = [{ product_id: editStabilityPrototypeHeaderList.stb_product_id, product_name:editStabilityPrototypeHeaderList.product_name}];
		$scope.orderEditStabilityPrototype.stb_product_id = { selectedOption: { product_id : editStabilityPrototypeHeaderList.stb_product_id} };
		$scope.productTestMasterList = [{ test_id : editStabilityPrototypeHeaderList.stb_product_test_id, test_code:editStabilityPrototypeHeaderList.test_code}];
		$scope.orderEditStabilityPrototype.stb_product_test_id = { selectedOption: { test_id : editStabilityPrototypeHeaderList.stb_product_test_id } };	
	    }	    
	    $scope.moveToContent('stbPrototypeEditFormDiv');
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	}).error(function(result, status, headers, config) {
	    $scope.newEditStabilityOrderPrototypeflag = false;
	    if(status == '500' || status == '404'){
		$scope.errorMsgShow($scope.defaultMsg);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	});
    };
    //***************/Editing of Prototype Detail of Stability Orders********************************
    
    //*****************Getting Product Test Master in Tabular Format********************
    $scope.funGetEditProductTestMasterTabularList = function(stb_product_test_id=null){
	
	$scope.editTestProductParamentersList = [];
	if($scope.newUpdateStabilityOrderPrototypeflag)return;
	$scope.newUpdateStabilityOrderPrototypeflag = true;	
	var http_para_string = stb_product_test_id ? {formData : 'stb_product_test_id='+stb_product_test_id} : {formData : $(erpUpdateStabilityOrderPrototypeForm).serialize()};

	$http({
	    url: BASE_URL + "sales/stability-orders/get-product-test-master-tabular-list",
	    method: "POST",
	    data: http_para_string
	}).success(function(result, status, headers, config) {
	    $scope.newUpdateStabilityOrderPrototypeflag = false;
	    if(result.error == 1){
		$scope.checkedEditStbCountForParamentersList = true;
		$scope.editTestProductParamentersList = result.productTestParametersList;
		$scope.orderEditStabilityPrototype.stb_test_standard_id = result.testMasterList.test_standard_id;
	    }else{
		$scope.errorMsgShow(result.message);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	}).error(function(result, status, headers, config) {
	    $scope.newUpdateStabilityOrderPrototypeflag = false;
	    if(status == '500' || status == '404'){
		$scope.errorMsgShow($scope.defaultMsg);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	});
    };
    //*****************/Getting Product Test Master in Tabular Format*******************************
    
    //*****************single checkbox***************************************
    $scope.allEditSelectedStabilityConditionArray = [];
    $scope.funGetEditSampleQtyFieldOnStbSelection = function(stb_stability_type_id,divId){
	var paraStatus = angular.element('#'+divId+stb_stability_type_id).prop('checked');
	var checkedStbCount = angular.element('.edit_stb_condition_chk_class:checkbox:checked').length;
	if(paraStatus){
	    angular.element('#edit_stb_sample_qty'+stb_stability_type_id).addClass('editErrorSet').show();
	    angular.element('#edit_stb_condition_temperature'+stb_stability_type_id).addClass('editErrorSet').show();
	    $scope.allEditSelectedStabilityConditionArray.push(stb_stability_type_id);
	}else{
	    angular.element('#edit_stb_sample_qty'+stb_stability_type_id).removeClass('editErrorSet').val('').hide();
	    angular.element('#edit_stb_condition_temperature'+stb_stability_type_id).removeClass('editErrorSet').val('').hide();
	    $scope.allEditSelectedStabilityConditionArray.splice( $scope.allEditSelectedStabilityConditionArray.indexOf(stb_stability_type_id), 1 );
	}
	if(checkedStbCount){
	    $scope.checkedEditStbCountForParamentersList = true;
	}else{
	    $scope.checkedEditStbCountForParamentersList = false;
	}
	$scope.tableTrTdColspanI = checkedStbCount ? checkedStbCount + 5 : 5;
    };
    //*****************single checkbox**************************************************
    
    //*****************single checkbox***************************************************
    $scope.allEditDefaultTestParametersArray = [];
    $scope.funAllEditDefaultTestParametersDetail = function(product_test_dtl_id,stb_stability_type_id,divId){    
	var product_stability_id = product_test_dtl_id+'-'+stb_stability_type_id;
	var paraCheckedStatus = angular.element('#'+divId+'_'+product_test_dtl_id+'_'+stb_stability_type_id).prop('checked');
	if(paraCheckedStatus){
	    $scope.allEditDefaultTestParametersArray.push(product_stability_id);
	}else{
	    $scope.allEditDefaultTestParametersArray.splice( $scope.allEditDefaultTestParametersArray.indexOf(product_stability_id), 1 );
	    angular.element('#check_all_edit_stb_condition_div_id'+stb_stability_type_id).prop('checked',false);
	}
    };
    //*****************single checkbox***************************************************
    
    //***************Updating of Prototype Detail of Stability Orders********************************
    $scope.funUpdatePrototypeOfStabilityOrder = function(){
	
	if(!$scope.erpUpdateStabilityOrderPrototypeForm.$valid)return;
	if(!$scope.validateSampleQtyTempStbOrderPrototypeForm('editErrorSet'))return;
	if($scope.newErpUpdateStabilityOrderPrototypflag)return;
	$scope.newErpUpdateStabilityOrderPrototypflag = true;
	var http_para_string = {formData : $(erpUpdateStabilityOrderPrototypeForm).serialize()};
	$scope.loaderMainShow();
	
	$http({
	    url: BASE_URL + "sales/stability-orders/update-prototype-stability-order",
	    method: "POST",
	    data: http_para_string
	}).success(function(result, status, headers, config) {
	    $scope.newErpUpdateStabilityOrderPrototypflag = false;
	    if(result.error == 1){
		$scope.stbOrderHdrID = result.stb_order_hdr_id;
		$scope.stbOrderHdrDtlID = result.stb_order_hdr_dtl_id;
		$scope.funGetAddedStabilityOrderPrototypeList(result.stb_order_hdr_id);
		$scope.successMsgShow(result.message);
	    }else{
		$scope.errorMsgShow(result.message);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	}).error(function(result, status, headers, config) {
	    $scope.newErpUpdateStabilityOrderPrototypflag = false;
	    if(status == '500' || status == '404'){
		$scope.errorMsgShow($scope.defaultMsg);
	    }
	    $scope.loaderMainHide();
	    $scope.clearConsole();
	});
    };
    //***************/Updating of Prototype Detail of Stability Orders*******
    
    //**********Deleting of Prototype Detail of Stability Orders********************************************
    $scope.funDeletePrototypeStabilityOrder = function(stb_order_hdr_dtl_id,stb_order_hdr_id){
	$scope.loaderShow();
	$http({
	    url: BASE_URL + "sales/stability-orders/delete-prototype-stability-order/"+stb_order_hdr_dtl_id,
	    method: "GET",
	}).success(function(data, status, headers, config){
	    if(data.error == 1){
		$scope.funGetAddedStabilityOrderPrototypeList(stb_order_hdr_id);	
		$scope.successMsgShow(data.message);
		$scope.moveToContent('no-more-tables');
	    }else{
		$scope.errorMsgShow(data.message);
	    }
	    $scope.loaderHide();
	}).error(function(data, status, headers, config){
	    if(status == '500' || status == '404'){
		$scope.errorMsgShowPopup($scope.defaultMsg);
	    }
	    $scope.loaderHide();
	});
    };
    //**********/Deleting of Prototype Detail of Stability Orders*************************************************
    
    //**********Getting Stability Order Notification List*************************************************    
    $scope.funGetStabilityOrderNotificationList = function(){
	var http_para_string = {formData : ''};	
	$http({
	    url: BASE_URL + "sales/stability-orders/get-stability-order-notifications",
	    method: "POST",
	    data: http_para_string,
	}).success(function(result, status, headers, config){
	    $scope.stabilityOrderNotificationList = result.stbNotificationList;
	    if (result.stbNotificationList.length) {
		$(stability_order_notification_popup).modal('show');
		angular.element('#stb_order_notification').html('('+result.stbNotificationList.length+')').addClass('asteriskRed').show();
	    }else{
		$(stability_order_notification_popup).modal('hide');
		angular.element('#stb_order_notification').hide();
	    }
	    $scope.clearConsole();
	    $scope.loaderHide();
	}).error(function(data, status, headers, config) {
	    if(status == '500' || status == '404'){
		$scope.errorMsgShowPopup($scope.defaultMsg);
	    }
	    $scope.loaderHide();
	    $scope.clearConsole();
	});
    };
    //*********/Getting Stability Order Notification List************************
   
    //**********Confirm box for Send email***************************************
    $scope.funConfirmMailMessage = function(stb_order_hdr_id){
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
			$scope.funSendMailOfStabilityOrder(stb_order_hdr_id);
		    }
		},
		cancel: {
		    text     : 'No',
		    btnClass : 'btn-default ng-confirm-closeIcon'
		}
	    }
	});
    };
    $scope.funSendMailOfStabilityOrder = function(stb_order_hdr_id){
	$scope.loaderMainShow();
	$http({
	    url: BASE_URL + "sales/stability-orders/send-stability-order-mail/"+stb_order_hdr_id,
	    method: "GET",
        }).success(function(result, status, headers, config){
            if (result.error == 1) {
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
    
    //**********Confirm box to delete stability prototype Order*******************
    $scope.funConfirmDeleteMessage = function(id){
        $ngConfirm({
            title     : false,
            content   : defaultDeleteMsg, 	//Defined in message.js and included in head
            animation : 'right',
            closeIcon : true,
            closeIconClass    : 'fa fa-close',
            backgroundDismiss : false,
            theme   : 'bootstrap',
            columnClass : 'col-sm-5 col-md-offset-3',
            buttons : {
		OK: {
		    text: 'OK',
		    btnClass: 'btn-primary',
		    action: function () {
			$scope.funDeleteStabilityOrder(id);
		    }
		},
		cancel: {
		    text     : 'Cancel',
		    btnClass : 'btn-default ng-confirm-closeIcon'
		}
            }
        });
    };
    $scope.funDeleteStabilityOrder = function(stb_order_hdr_id){
	$scope.loaderShow();
        $http({
            url: BASE_URL + "sales/stability-orders/delete-stability-order/"+stb_order_hdr_id,
            method: "GET",
        }).success(function(data, status, headers, config){
            if(data.error == 1){
                $scope.successMsgShow(data.message);
                $scope.funGetStabilityOrdersList();
            }else{
                $scope.errorMsgShow(data.message);
            }
            $scope.loaderHide();
        }).error(function(data, status, headers, config){
            if(status == '500' || status == '404'){
                $scope.errorMsgShowPopup($scope.defaultMsg);
            }
            $scope.loaderHide();
        });
    };
    //**********/Confirm box to delete stability prototype Order****************
    
    //*****************Check All Checkbox***************************************
    $scope.funCheckAllStabilityParameters = function(stb_stability_type_id,divId,className){
	var paraStatusAll = angular.element('#'+divId+stb_stability_type_id).prop('checked');
	if(paraStatusAll){
	    angular.element('.'+className+stb_stability_type_id).prop('checked',true);
	}else{
	    angular.element('.'+className+stb_stability_type_id).prop('checked',false);
	}
	if (className == 'parametersAddCheckBox') {
	    var http_para_string = {formData : $(erpCreateStabilityOrderPrototypeForm).serialize()};
	}else if (className == 'parametersEditCheckBox') {
	    var http_para_string = {formData : $(erpUpdateStabilityOrderPrototypeForm).serialize()};
	}	
	$http({
	    url: BASE_URL + "sales/stability-orders/get-selected-testparameters-check-all",
	    method: "POST",
	    data: http_para_string,
	}).success(function(result, status, headers, config) {
	    if (className == 'parametersAddCheckBox') {
		$scope.allAddDefaultTestParametersArray = result.selectedTestParametersList
	    }else if (className == 'parametersEditCheckBox') {
		$scope.allEditDefaultTestParametersArray = result.selectedTestParametersList
	    }
	    $scope.clearConsole();
	});
    };
    //*****************/Check All Checkbox**************************************************
    
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
	    $scope.clearConsole();
	});
    };
    /********* reporting to fucntions*****/
    $scope.funShowReportingCountryViewPopup = function(selectedModule){
	$scope.selectedModuleID = selectedModule;
	$('#countryStateTreeViewPopup').modal('show');
    };
    $scope.funShowEditReportingCountryViewPopup = function(selectedModule){
	$scope.selectedModuleID = selectedModule;
	$('#editCountryStateTreeViewPopup').modal('show');
    };
    $scope.funGetSelectedReportingToStateId = function(state_id){
	if (state_id){
	    $scope.funGetReportingInvoicingCustomerOnStateChange(state_id,1);
	    $('#countryStateTreeViewPopup').modal('hide');
	    $('#editCountryStateTreeViewPopup').modal('hide');
	}
    };
    //**********invoicing functions***************************************************/
    $scope.funShowInvoicingCountryViewPopup = function(selectedModule){
	$scope.selectedModuleID = selectedModule;
	$('#countryStateTreeViewPopup').modal('show');
    };
    $scope.funShowEditInvoicingCountryViewPopup = function(selectedModule){
	$scope.selectedModuleID = selectedModule;
	$('#editCountryStateTreeViewPopup').modal('show');
    };
    $scope.funGetSelectedInvoicingToStateId = function(state_id){
	if (state_id){
	    $scope.funGetReportingInvoicingCustomerOnStateChange(state_id,2);
	    $('#countryStateTreeViewPopup').modal('hide');
	    $('#editCountryStateTreeViewPopup').modal('hide');
	}
    };
    //**********/invoicing functions***************************************************
    
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
}).directive('validDate', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attrs, control) {
            control.$parsers.push(function (viewValue) {
                var newDate = model.$viewValue;
                control.$setValidity("invalidDate", true);  
                if (typeof newDate === "object" || newDate == "") return newDate;  // pass through if we clicked date from popup
                if (!newDate.match(/^\d{1,2}\/\d{1,2}\/((\d{2})|(\d{4}))$/))
                    control.$setValidity("invalidDate", false);
                return viewValue;
            });
        }
    };
}).directive('validNumber', function() {
    return {
	require: '?ngModel',
	link: function(scope, element, attrs, stabilityOrderPrototypesController) {
	    if(!stabilityOrderPrototypesController) {return;}
	    stabilityOrderPrototypesController.$parsers.push(function(val) {
		if (angular.isUndefined(val)){var val = '';}
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
		    stabilityOrderPrototypesController.$setViewValue(clean);
		    stabilityOrderPrototypesController.$render();
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
});
