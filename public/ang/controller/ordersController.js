app.controller('ordersController', function ($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {

	//currentModule 20 and 21 are for country state tree view used in tree.js file
	//define empty variables
	$scope.currentModule = 7;
	$scope.orderData = '';
	$scope.order_id = '';
	$scope.order_no = '';
	$scope.order_date = '';
	$scope.ProductCategoryFilterBtn = false;
	$scope.ProductCategoryFilterInput = true;
	$scope.showAutoSearchList = false;
	$scope.buttonText = 'Add New';
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.successMessagePopup = '';
	$scope.errorMessagePopup = '';
	$scope.newOrderActive = '';
	$scope.viewOrder = {};
	$scope.testProductList = [];
	$scope.dt_standard_value_to_add = '';
	$scope.dt_standard_value_to_edit = '';
	$scope.cancelledOrder = [];
	$scope.cancelledOrder.order_no = '';
	$scope.cancelledOrder.order_id = '';
	$files = [];
	$scope.orderFileTRFUploadSelection = {};
	$scope.orderFileStpSelection = {};
	$scope.custSTPNoAccToSampleNameList = [];
	$scope.orderLinkedTrfDtlList = {};
	$scope.custSTPSampleSelectedList = {};
	$scope.oltdOrderID = '';
	$scope.oltdOrderNO = '';
	$scope.defaultMsg = 'Oops ! Sorry for inconvenience server not responding or may be some error.';

	//sorting variables
	$scope.sortType = 'order_date';     // set the default sort type
	$scope.sortReverse = false;         	// set the default sort order
	$scope.searchFish = '';    		    // set the default search/filter term
	$scope.headerNoteStatus = false;
	$scope.realTimeStabilityStatus = false;
	$scope.isTatInDaysEditableDivFlag = false;
	$scope.showAddPOType = false;
	$scope.showEditPOType = false;
	$scope.orderYearList = {};
	$scope.orderDateList = {};
	$scope.orderCurrentYear = '';
	$scope.orderCurrentDate = '';
	$scope.searchStringID = '';
	$scope.orderCustomer = {};
	$scope.orderSample = {};
	$scope.orderSample.po_type = false;
	$scope.updateOrder = {};
	$scope.updateOrder.po_type = false;
	$scope.orderProductTest = {};
	$scope.filterOrders = {};
	$scope.searchOrder = {};
	$scope.globalProductCategoryID = '1';
	$scope.istableTrTdVisibleID = '0';
	$scope.tableTrTdColspanLevelI = '12';
	$scope.tableTrTdColspanLevelII = '10';
	$scope.fixedRateInvoicingTypeID = '0';
	var formdata = new FormData();

	//**********scroll to top function**********
	$scope.moveToMsg = function () {
		angular.element('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
	};
	//**********/scroll to top function**********

	//**********loader show****************************************************
	$scope.loaderShow = function () {
		angular.element('#global_loader').fadeIn('slow');
	};
	//**********/loader show**************************************************

	//**********loader show***************************************************
	$scope.loaderHide = function () {
		angular.element('#global_loader').fadeOut('slow');
	};
	//**********/loader show**************************************************

	//**********loader show****************************************************
	$scope.loaderMainShow = function () {
		angular.element('#global_loader_onload').fadeIn('slow');
	};
	//**********/loader show**************************************************

	//**********loader show***************************************************
	$scope.loaderMainHide = function () {
		angular.element('#global_loader_onload').fadeOut('slow');
	};
	//**********/loader show**************************************************

	//********** key Press Handler**********************************************
	$scope.funEnterPressHandler = function (e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			e.stopPropagation();
		}
	}
	//**********/key Press Handler**********************************************

	//**********Read/hide More description************************************
	$scope.toggleDescription = function (type, id) {
		angular.element('#' + type + 'limitedText-' + id).toggle();
		angular.element('#' + type + 'fullText-' + id).toggle();
	};
	//*********/Read More description*****************************************

	//************/show tree pop up*******************************************
	$scope.showProductCatTreeViewPopUp = function (currentModule) {
		$scope.currentModule = currentModule;
		$scope.headerNoteStatus = false;
		$scope.realTimeStabilityStatus = false;
		$('#orderTestingProductCategory').modal('show');
	};
	//**********/show tree pop up********************************************"*

	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsNewOrder = true;
	$scope.IsUpdateOrder = true;
	$scope.IsSaveAsOrder = true;
	$scope.IsViewList = false;
	$scope.IsPreviewList = true;
	$scope.IsViewLogList = true;
	$scope.IsViewUploadList = true;
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup = true;
	$scope.IsViewHidden = true;
	$scope.IsViewAlternativeHidden = true;
	$scope.IsViewSaveAsAlternativeHidden = true;
	$scope.isViewOrderFoodSection = false;
	$scope.isViewOrderPharmaSection = false;
	$scope.isViewOrdersStatisticSection = false;
	$scope.isViewInteralTransferSampleLink = false;
	$scope.closeAutoSearchList = false;
	$scope.showPOType = false;
	$scope.orderHoldAddFlag = false;
	$scope.orderHoldEditFlag = false;
	$scope.isInvoicingNeededAddFlag = false;
	$scope.isInvoicingNeededEditFlag = false;
	$scope.isInvoicingEditCheckBoxFlag = false;
	$scope.orderHoldAddFlag = false;
	$scope.canAddChangePoTypeOrder = false;
	$scope.canAddChangeSampleTypeOrder = false;
	$scope.canAddChangeInvoicingTo = false;
	$scope.orderHoldEditFlag = false;
	$scope.canEditChangePoTypeOrder = false;
	$scope.canEditChangeSampleTypeOrder = false;
	$scope.canEditChangeInvoicingTo = false;
	$scope.isInvoicingNeededSaveAsFlag = false;
	$scope.isInvoicingSaveAsCheckBoxFlag = false;
	$scope.orderHoldSaveAsFlag = false;
	$scope.canSaveAsChangePoTypeOrder = false;
	$scope.canSaveAsChangeSampleTypeOrder = false;
	$scope.canSaveAsChangeInvoicingTo = false;

	$scope.openNewOrderForm = function () {
		$scope.resetForm();
		$scope.IsNewOrder = false;
		$scope.isSampleId = false;
		$scope.IsUpdateOrder = true;
		$scope.IsViewList = true;
		$scope.IsPreviewList = true;
		$scope.IsViewLogList = true;
		$scope.IsSaveAsOrder = true;
		$scope.IsViewHidden = true;
		$scope.showAutoSearchList = false;
		$scope.showAddSampleType = false;
		$scope.isInvoicingNeededAddFlag = false;
		$scope.funGetTestSampleRecevied();
		$scope.hideAlertMsg();
	};
	//**********/If DIV is hidden it will be visible and vice versa************

	//**********successMsgShow**************************************************
	$scope.successMsgShow = function (message) {
		$scope.successMessage = message;
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg = true;
		$scope.moveToMsg();
	};
	//********** /successMsgShow************************************************

	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function (message) {
		$scope.errorMessage = message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = false;
		$scope.moveToMsg();
	};
	//********** /errorMsgShow************************************************

	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function () {
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = true;
	};
	//********** /hide Alert Msg**********************************************

	//**********successMsgShowPopup**************************************************
	$scope.successMsgShowPopup = function (message) {
		$scope.successMessagePopup = message;
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup = true;
		$scope.moveToMsg();
	};
	//********** /successMsgShowPopup************************************************

	//**********errorMsgShowPopup**************************************************
	$scope.errorMsgShowPopup = function (message) {
		$scope.errorMessagePopup = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup = false;
		$scope.moveToMsg();
	};
	//********** /hideAlertMsgPopup************************************************

	//**********hideAlertMsgPopup*************
	$scope.hideAlertMsgPopup = function () {
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup = true;
	};
	//********** /hideAlertMsgPopup**********************************************

	//**********resetFileInputData**********************************************
	$scope.resetFileInputData = function (popup_id = null) {
		$files = [];
		var formdata = new FormData();
		if (popup_id) {
			angular.element("#" + popup_id).val('');
		}
		formdata = null;
	};
	//********** /resetFileInputData**********************************************

	//*****************product category tree list data*************************
	$scope.productCategoriesTree = [];
	$scope.getProductCategories = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'get-product-category-tree-view'
		}).success(function (result) {
			if (result.productCategoriesTree) {
				$scope.productCategoriesTree = result.productCategoriesTree;
			}
			$scope.clearConsole();
		});
	};
	$scope.stateCityTreeViewList = [];
	$scope.funGetOrderStateCityTreeViewList = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'get-state-city-tree-view'
		}).success(function (result) {
			if (result.stateCityTreeViewList) {
				$scope.stateCityTreeViewList = result.stateCityTreeViewList;
			}
			$scope.clearConsole();
		});
	};

	$scope.dynamicFieldsList = [];
	$scope.getDynamicFieldsData = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'get-dynamic-fields-list'
		}).success(function (result) {
			if (result.dynamicFieldsList) {
				$scope.dynamicFieldsList = result.dynamicFieldsList;
			}
			$scope.clearConsole();
		});
	};
	//*****************/product category tree list data************************

	//set category id and name selected from tree view
	$scope.setSelectedProductCategoryId = function (node) {
		if (node.level == '2') {
			$scope.selectedProductCategoryId = node.p_category_id;
			$scope.selectedProductCategoryName = node.p_category_name;
			$scope.funGetTestingProducts(node.p_category_id);
			$('#orderTestingProductCategory').modal('hide');
			$scope.resetPopupForm();
		}
	};

	//reset category id and name selected from tree view
	$scope.resetProductCategory = function () {
		$scope.selectedProductCategoryId = '';
		$scope.selectedProductCategoryName = '';
	};

	//**********back Button****************************************************
	$scope.backButton = function () {
		$scope.IsViewList = false;
		$scope.IsPreviewList = true;
		$scope.IsViewLogList = true;
		$scope.IsNewOrder = true;
		$scope.IsUpdateOrder = true;
		$scope.IsSaveAsOrder = true;
		$scope.IsViewHidden = true;
		$scope.IsViewUploadList = true;
		$scope.showAutoSearchList = false;
		$scope.headerNoteStatus = false;
		$scope.realTimeStabilityStatus = false;
		$scope.dt_standard_value_to_add = '';
		$scope.dt_standard_value_to_edit = '';
		$scope.orderCustomer = {};
		$scope.orderSample = {};
		$scope.orderProductTest = {};
		$scope.testProductStandardParamenters = {};
		$scope.invoicingTypeList = {};
		$scope.hideAlertMsg();
		angular.element('#sample').text('');
		$scope.fixedRateInvoicingTypeID = '0';
		$scope.orderFileTRFUploadSelection = {};
		$scope.orderFileStpSelection = {};
		$scope.orderLinkedTrfDtlList = {};
		$scope.custSTPSampleSelectedList = {};
	};
	//**********/back Button***************************************************

	//**********reset Form****************************************************
	$scope.resetForm = function () {
		$scope.order = {};
		$scope.orderSample = {};
		$scope.orderProductTest = {};
		$scope.order.sampling_date = '';
		$scope.erpCreateOrderForm.$setUntouched();
		$scope.erpCreateOrderForm.$setPristine();
		$scope.updateOrder = {};
		$scope.updateOrder.sampling_date = '';
		$scope.erpUpdateOrderForm.$setUntouched();
		$scope.erpUpdateOrderForm.$setPristine();
		$scope.saveAsOrder = {};
		$scope.invoicingTypeList = {};
		$scope.saveAsOrder.sampling_date = '';
		$scope.erpSaveAsOrderForm.$setUntouched();
		$scope.erpSaveAsOrderForm.$setPristine();
		$scope.testProductStandardParamenters = {};
		$scope.testProductList = [];
		$scope.testProductParamenters = {};
		$scope.orderHoldAddFlag = false;
		$scope.canAddChangePoTypeOrder = false;
		$scope.canAddChangeSampleTypeOrder = false;
		$scope.canAddChangeInvoicingTo = false;
		$scope.orderHoldEditFlag = false;
		$scope.canEditChangePoTypeOrder = false;
		$scope.canEditChangeSampleTypeOrder = false;
		$scope.canEditChangeInvoicingTo = false;
		$scope.orderHoldSaveAsFlag = false;
		$scope.canSaveAsChangePoTypeOrder = false;
		$scope.canSaveAsChangeSampleTypeOrder = false;
		$scope.canSaveAsChangeInvoicingTo = false;
		$scope.showAutoSearchList = false;
		$scope.headerNoteStatus = false;
		$scope.realTimeStabilityStatus = false;
		$scope.fixedRateInvoicingTypeID = '0';
		angular.element('#sample').text('');
		$scope.resetPopupForm();
		$scope.hideAlertMsg();
	};

	$scope.resetPopupForm = function () {
		$scope.selectedAll = false;
		$scope.productTestList = [];
		$scope.allPopupSelectedParametersArray = [];
		$scope.testProductParamentersList = [];
		$scope.testProductParamenters = [];
		var isRunning = true;
	};
	//**********/reset Form***************************************************

	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function (id, divisionId) {
		$ngConfirm({
			title: false,
			content: defaultDeleteMsg, //Defined in message.js and included in head
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme: 'bootstrap',
			columnClass: 'col-sm-5 col-md-offset-3',
			buttons: {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funDeleteOrder(id, divisionId);
					}
				},
				cancel: {
					text: 'cancel',
					btnClass: 'btn-default ng-confirm-closeIcon'
				}
			}
		});
	};
	//********** /confirm box****************************************************

	//**********confirm box******************************************************
	$scope.funConfirmSaveMore = function (divisionId) {

		$ngConfirm({
			title: false,
			content: defaultConfirmOrderMsg, //Defined in message.js and included in head
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme: 'bootstrap',
			columnClass: 'col-sm-5 col-md-offset-3',
			buttons: {
				OK: {
					text: 'Yes',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funPlaceAndSaveMoreOrder(divisionId, '0', '0');
					}
				},
				No: {
					text: 'No',
					btnClass: 'btn-default ng-confirm-closeIcon',
					action: function () {
						$scope.funPlaceAndSaveOrder(divisionId, '0');
					}
				}
			}
		});
	};
	//********** /confirm box****************************************************

	//**********confirm box******************************************************
	$scope.funConfirmSaveAsMore = function (divisionId, orderId) {
		$ngConfirm({
			title: false,
			content: defaultConfirmOrderMsg, //Defined in message.js and included in head
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme: 'bootstrap',
			columnClass: 'col-sm-5 col-md-offset-3',
			buttons: {
				OK: {
					text: 'Yes',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funPlaceAndSaveAsMoreOrder(divisionId, 1, orderId);
					}
				},
				No: {
					text: 'No',
					btnClass: 'btn-default ng-confirm-closeIcon',
					action: function () { }
				}
			}
		});
	};
	//********** /confirm box****************************************************

	//**********confirm box******************************************************
	$scope.showConfirmMessage = function (msg) {
		if (confirm(msg)) {
			return true;
		} else {
			return false;
		}
	};
	//**********/confirm box****************************************************

	//**********Clearing Console*********************************************
	$scope.clearConsole = function () {
		if (APPLICATION_MODE) console.clear();
	};
	//*********/Clearing Console*********************************************

	//************Sorting list order******************************************
	$scope.predicate = 'order_id';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	$scope.alterPropertyName = 'test_parameter_name';
	$scope.sortByAlternative = function (alterPropertyName) {
		$scope.reverse = ($scope.alterPropertyName === alterPropertyName) ? !$scope.reverse : false;
		$scope.alterPropertyName = alterPropertyName;
	};
	//************/Sorting list order*****************************************

	//********************Pre populated Select Box****************************
	$scope.sealedUnsealedList = [
		{ id: '1', name: 'Sealed' },
		{ id: '0', name: 'Unsealed' },
		{ id: '2', name: 'Intact' },
		{ id: '3', name: 'NA' },
		{ id: '4', name: 'Sealed by Officer Incharge' },
		{ id: '5', name: 'Sealed by Department' },
	];
	$scope.signedUnsignedList = [
		{ id: '1', name: 'Signed' },
		{ id: '0', name: 'Unsigned' },
		{ id: '2', name: 'NA' },
		{ id: '4', name: 'Signed by Officer Incharge' },
		{ id: '5', name: 'Signed by Department' },
	];
	//*******************/Pre populated Select Box****************************

	//**********Open need modification popup******************************
	$scope.funOpenBootStrapModalPopup = function (contentId) {
		$scope.successMsgOnPopup = '';
		$scope.errorMsgOnPopup = '';
		$('#' + contentId).modal({ backdrop: 'static', keyboard: true, show: true });
	}
	//**********/Open need modification popup******************************

	//**********Close need modification popup******************************
	$scope.funCloseBootStrapModalPopup = function (contentId) {
		$('#' + contentId).modal('hide');
	}
	//**********/Close need modification popup******************************

	//*****************display division dropdown start************************
	$scope.submissionTypeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'sales/samples/get-sample-modes'
	}).success(function (result) {
		$scope.submissionTypeList = result.sampleModeList;
		$scope.clearConsole();
	});
	//*****************display division dropdown end***************************

	//*****************display Invoicing Needed*******************************
	$scope.isInvoicingNeededFlag = false;
	$scope.funShowHideInvoicingNeeded = function () {
		if ($scope.invoicingNeededFlag == false) {
			$scope.order.reporting_to = '';
			$scope.order.invoicing_to = '';
		}
		$scope.isInvoicingNeededFlag = $scope.invoicingNeededFlag;
	};
	//*****************/display Invoicing Needed*******************************

	//*****************display division dropdown start*************************
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end**************************

	//*****************display Test Product Category dropdown******************
	$scope.testProductCategoryList = [];
	$scope.funGetTestProductCategory = function () {
		$http({
			method: 'GET',
			url: BASE_URL + 'orders/get_test_product_category/' + 2
		}).success(function (result) {
			$scope.testProductCategoryList = result.productCatsList;
		});
	}
	//*****************/display Test Product Category dropdown*****************

	//*****************display Test Product List dropdown**********************
	$scope.testSampleReceviedList = [];
	$scope.funGetTestSampleRecevied = function () {
		$http({
			method: 'GET',
			url: BASE_URL + 'sales/orders/get-test-sample-received'
		}).success(function (result) {
			$scope.testSampleReceviedList = result.testSampleReceviedList;
			$scope.clearConsole();
		});
	};
	//*****************/display Test Product List dropdown*********************

	//*****************display Test Product List dropdown**********************
	$scope.samplerDropdownList = [];
	$scope.funGetSamplerDropdownList = function (division_id) {
		$http({
			method: 'GET',
			url: BASE_URL + 'sales/orders/get-sampler-dropdown-list/'+division_id
		}).success(function (result) {
			$scope.samplerDropdownList = result.samplerDropdownList;
			$scope.clearConsole();
		});
	};
	//*****************/display Test Product List dropdown*********************
	
	//*****************display parent category*********************************
	$scope.parentCategoryList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'master/get-parent-product-category'
	}).success(function (result) {
		$scope.parentCategoryList = result.parentCategoryList;
		$scope.clearConsole();
	});
	//****************/display parent category*********************************

	//*****************display Test Product List dropdown**********************
	$scope.hasProductSampleNameList = false;
	$scope.funGetTestingProducts = function (p_category_id) {
		if (p_category_id) {
			$scope.productTestList = {};
			$scope.testProductParamenters = {};
			$scope.testProductStandardParamenters = {};
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/get_test_products/' + p_category_id
			}).success(function (result) {
				$scope.resetPopupForm();
				$scope.testProductList = result.productsList;
				$("#orderTestingProductCategory").modal('hide');
				$scope.hasProductSampleNameList = true;
			});
		}
	};
	//*****************/display Test Product List dropdown*********************

	//*****************display Test Product List dropdown**********************
	$scope.funGetTestProductAccordingToSampleName = function (sampleId) {
		if (sampleId) {
			$scope.testProductList = {};
			$scope.productTestList = {};
			$scope.testProductParamenters = {};
			$scope.testProductStandardParamenters = {};
			$scope.hasProductSampleNameList = false;
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/get_test_product_acc_sample_name/' + sampleId
			}).success(function (result) {
				if (result.productSampleNameList) {
					$scope.testProductList = result.productSampleNameList;
				} else {
					$scope.hasProductSampleNameList = true;
				}
			});
		}
	};
	//*****************/display Test Product List dropdown*********************
	//*****************display Test Product Standard dropdown******************
	$scope.productTestList = [];
	$scope.funGetTestProductStandard = function (product_id) {
		if (product_id) {
			$scope.testProductStandardParamenters = {};
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/get-product-tests/' + product_id
			}).success(function (result) {
				$scope.resetPopupForm();
				$scope.productTestList = result.productTestList;
				$scope.selectedProductCategoryId = result.productMasterList.p_category_id;
			});
		}
	};
	//*****************/display Test Product Standard dropdown******************

	//*****************display Test Product Standard dropdown*******************
	$scope.testProductParamentersList = [];
	$scope.funTestProductStandardParamentersList = function (testId) {
		if (testId) {
			$scope.globalTestId = testId;
			$scope.ProductTestId = testId;
			$scope.loaderMainShow();
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/get-product-test-parameters-list/' + testId,
			}).success(function (result) {
				$scope.testProductParamentersList = result.productTestParametersList;
				angular.element('#selectedAll').prop('checked', false);
				angular.element('#header_note').prop('checked', false);
				angular.element('#real_time_stability').prop('checked', false);
				if ($scope.realTimeStabilityStatus) {
					$('#real_time_stability').prop('checked', true);
				}
				if ($scope.headerNoteStatus) {
					$('#header_note').prop('checked', true);
				}
				$('#productParametersPopup').modal('show');
				if (result.testStandardList) {
					$scope.orderProductTest.test_standard = result.testStandardList.test_standard_id;
				}
				$scope.loaderMainHide();
			});
		}
	};
	//*****************/display Test Product Standard dropdown**************

	//*****************single checkbox***************************************
	$scope.funCheckParameterCheckedOrNotValues = function (dltId) {
		var paraStatus = angular.element('#checkOneParameter_' + dltId).prop('checked');
		if (paraStatus) {
			$scope.allPopupSelectedParametersArray.push(dltId);
		} else {
			angular.element('#selectedAll').prop('checked', false);
			$scope.allPopupSelectedParametersArray.pop(dltId);
		}
	};

	//*****************/single checkbox***************************************
	$scope.funHeaderNoteCheck = function () {
		$scope.headerNoteStatus = false;
		if (angular.element('#header_note').prop('checked')) {
			$scope.headerNoteStatus = true;
		} else {
			$scope.headerNoteStatus = false;
		}
	};
	$scope.funRealTimeStabilityStatusCheck = function () {
		$scope.realTimeStabilityStatus = false;
		if (angular.element('#real_time_stability').prop('checked')) {
			$scope.realTimeStabilityStatus = true;
		} else {
			$scope.realTimeStabilityStatus = false;
		}
	};
	//*****************display Test Product Standard dropdown*****************
	$scope.funGetTestProductStandardParamenters = function (global_sample_id, global_product_category_id, global_invoicing_type_id) {
		$scope.loaderMainShow();
		$http.post(BASE_URL + 'orders/get-product-test-parameters', {
			data: { formData: 'sample_id=' + global_sample_id + '&' + $(testParametersForm).serialize() },
		}).success(function (result, status, headers, config) {
			$scope.testProductParamenters = result.productTestParametersList;
			$timeout(function () { $scope.getClaimCalculation(result.productTestParametersList); }, 500);
			$scope.istableTrTdVisibleID = global_product_category_id == 2 && global_invoicing_type_id == 4 ? 1 : 0;
			$scope.tableTrTdColspanLevelI = global_product_category_id == 2 && global_invoicing_type_id == 4 ? 14 : 12;
			$scope.tableTrTdColspanLevelII = $scope.tableTrTdColspanLevelI - 1;
			$scope.realTimeStability = result.order_stability;
			$('#productParametersPopup').modal('hide');
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '400') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*****************/display Test Product Standard dropdown******************

	//********************* 03 May 2018****************************************/
	$scope.getClaimCalculation = function (productTestParametersList) {
		angular.forEach(productTestParametersList, function (value, key) {
			angular.forEach(value.categoryParams, function (categoryParams, key) {
				$scope.funChangeTestParameterValueAccToClaim(categoryParams.product_test_dtl_id, 'add');
			});
		});
	};
	$scope.getClaimCalculationOnEdit = function (productTestParametersList) {
		angular.forEach(productTestParametersList, function (value, key) {
			angular.forEach(value.categoryParams, function (categoryParams, key) {
				$scope.funOnNewChangeTestParameterValueAccToClaim(categoryParams.product_test_dtl_id)
			});
		});
	};


	//*****************display customer list dropdown***************************
	$scope.customerNameList = [];
	$scope.funGetCustomersList = function () {
		$http({
			method: 'GET',
			url: BASE_URL + 'orders/get_customer_list'
		}).success(function (result) {
			$scope.customerNameList = result.customersList;
		});
	};
	//*****************/display customer list code dropdown*********************

	$scope.definedTestStandardList = [];
	$scope.funGetdefinedTestStandardList = function (division_id, department_id) {
		$http({
			method: 'GET',
			url: BASE_URL + 'orders/get_defined_test_std/' + division_id + '/' + department_id
		}).success(function (result) {
			$scope.definedTestStandardList = result.definedTestStdList;
		});
	};
	//*****************/display customer list code dropdown*********************
	//*****************display Billing Type dropdown**********************
	$scope.billingTypeOnEditList = [];
	$scope.funGetBillingTypeList = function (orderId = null) {
		$http({
			method: 'POST',
			url: BASE_URL + 'sales/orders/customer_billing_type_list',
			data: { 'order_id': orderId }
		}).success(function (result) {
			$scope.billingTypeOnEditList = result.billingTypes;
		});
	};
	//*****************/display Billing Type dropdown*********************

	//*****************invoicing types*************************************
	$scope.invoicingTypesOnEditList = [];
	$scope.funGetInvoicingTypesList = function (orderId = null) {
		$http({
			method: 'POST',
			url: BASE_URL + 'sales/orders/customer-invoicing-types-list',
			data: { 'order_id': orderId }
		}).success(function (result) {
			if (result) {
				$scope.invoicingTypesOnEditList = result.invoicingTypes;
			}
			$scope.clearConsole();
		});
	};
	//*****************/invoicing types*************************************

	//*****************discount types****************************************
	$scope.discountTypeOnEditList = [];
	$scope.funGetDiscountTypeList = function (orderId = null) {
		$http({
			method: 'POST',
			url: BASE_URL + 'sales/orders/discount-types-list',
			data: { 'order_id': orderId }
		}).success(function (result) {
			if (result) {
				$scope.discountTypeOnEditList = result.discountTypes;
			}
			$scope.clearConsole();
		});
	};
	//*****************customer priority types*****************************************

	//*****************display sample Priority List dropdown***************************
	$scope.samplePriorityList = [];
	$scope.samplePriorityCRMList = [];
	$scope.funGetSamplePriorityList = function () {
		$http({
			method: 'GET',
			url: BASE_URL + 'orders/get_sample_priority_list'
		}).success(function (result) {
			$scope.samplePriorityList = result.samplePriorityList;
			$scope.samplePriorityCRMList = result.samplePriorityCRMList;
			$scope.clearConsole();
		});
	}
	$scope.funGetSamplePriorityList();
	//*****************/display sample Priority List code dropdown*********************

	//**********Getting all Orders with search and filter******************************
	$scope.funGetOrdersHttpRequest = function () {

		$scope.loaderShow();

		$scope.isInvoicingNeededFlag = false;
		var http_para_string = { formData: $(erpFilterOrderForm).serialize() };

		$http({
			url: BASE_URL + "orders/get_orders_list",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.orderData = result.orderList;
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	$scope.funGetOrdersList = function (divisionId) {
		$scope.divisionID = divisionId;
		$scope.searchStringID = $scope.searchStringID;
		$scope.funGetOrdersHttpRequest();
	};
	$scope.funFilterOrderByStatus = function () {
		$scope.funGetOrdersHttpRequest();
	};

	var tempOrderSearchTerm;
	$scope.funFilterOrderOnBarCodeScan = function (keyword) {
		tempOrderSearchTerm = keyword;
		$timeout(function () {
			if (keyword == tempOrderSearchTerm) {
				$scope.searchStringID = keyword;
				$scope.funGetOrdersHttpRequest();
			}
		}, 800);
	};
	$scope.funRefreshOrdersList = function () {
		$scope.divisionID = '';
		$scope.searchStringID = '';
		$scope.filterOrders = {};
		$scope.erpFilterOrderForm.$setUntouched();
		$scope.erpFilterOrderForm.$setPristine();
		$timeout(function () {
			$scope.funGetOrdersHttpRequest();
		}, 500);
	};
	//**********/Getting all Orders with search and filter**********************

	///**************multisearch start here**********************/
	$scope.multiSearchTr = true;
	$scope.multisearchBtn = false;
	var tempSearchKeyword;
	$scope.getMultiSearch = function (searchKeyword) {
		tempSearchKeyword = searchKeyword;
		$timeout(function () {
			if (tempSearchKeyword == searchKeyword) {
				$scope.searchOrder = searchKeyword;
				$scope.funGetOrdersHttpRequest();
			}
		}, 1000);
	};
	$scope.closeMultisearch = function () {
		$scope.multiSearchTr = true;
		$scope.multisearchBtn = false;
		$scope.funRefreshOrdersList();
	};
	$scope.refreshMultisearch = function (divisionID) {
		$scope.funRefreshOrdersList();
	};
	$scope.openMultisearch = function () {
		$scope.multiSearchTr = false;
		$scope.multisearchBtn = true;
	};
	//**************multisearch end here**********************/

	//*****************display customer list location and Mfg Lic Number********
	$scope.funGetCustomerAttachedSampleDetail = function (sampleId) {

		$scope.customerListData = [];
		$scope.customerData = [];
		$scope.testProductList = [];
		$scope.productTestList = [];
		$scope.testProductParamenters = [];
		$scope.allPopupSelectedParametersArray = [];
		$scope.orderSample = {};
		$scope.orderProductTest = {};
		$scope.selectedProductCategoryId = '';
		$scope.orderCustomer.customer_id = null;
		$scope.orderCustomer.city_name = null;
		$scope.orderCustomer.mfg_lic_no = null;
		$scope.orderCustomer.sale_executive_name = null;
		$scope.orderCustomer.sale_executive = null;
		$scope.orderCustomer.discount_type_name = null;
		$scope.orderCustomer.discount_type_id = null;
		$scope.orderCustomer.discount_value = null;
		$scope.orderCustomer.customer_priority_id = null;
		$scope.orderSample.po_type = false;
		$scope.showAddPOType = false;
		$scope.orderSample.invoicingNeeded = false;
		$scope.isInvoicingNeededAddFlag = false;
		$scope.fixedRateInvoicingTypeID = '0';
		$scope.loaderMainShow();
		$scope.funGetSamplePriorityList();

		if (sampleId) {
			$http({
				method: 'GET',
				url: BASE_URL + 'sales/orders/get-customer-attached-sample-detail/' + sampleId
			}).success(function (result) {
				if (result.error == 1) {

					//Internal Transfer Variables
					$scope.sampleID = sampleId;
					$scope.isViewInteralTransferSampleLink = true;
					$scope.orderCustomerDetail = result.customerAttachedSampleList;
					$scope.customerInvoicingTypeID = result.customerAttachedSampleList.invoicing_type_id;
					$scope.globalProductCategoryID = result.customerAttachedSampleList.product_category_id;
					$scope.globalDivisionID = result.customerAttachedSampleList.division_id;
					$scope.globalCustomerID = result.customerAttachedSampleList.customer_id;
					$scope.orderCustomer.submission_type = { selectedOption: { id: $scope.orderCustomerDetail.sample_mode_id } };
					$scope.funSubmissionTypeValue(result.customerAttachedSampleList.sample_mode_id);
					$scope.orderSample.order_date = result.currentDate;

					// defined test standard
					$scope.funGetdefinedTestStandardList($scope.globalDivisionID, $scope.globalProductCategoryID);

					//Billing Type selected according to customer
					$scope.billingTypeList = [{ id: result.customerAttachedSampleList.billing_type_id, name: result.customerAttachedSampleList.billing_type }];
					$scope.orderCustomer.billing_type_id = { id: result.customerAttachedSampleList.billing_type_id };

					//customers dropdown according to sample
					$scope.customerNameList = [{ customer_id: result.customerAttachedSampleList.customer_id, customer_name: result.customerAttachedSampleList.customer_name }];
					$scope.orderCustomer.customer_id = { customer_id: result.customerAttachedSampleList.customer_id };
					$scope.orderSample.customer_name = result.customerAttachedSampleList.customer_name;
					$scope.isSampleId = result.customerAttachedSampleList.sample_id;

					//branch selected according to sample
					$scope.orderCustomer.division_id = { selectedOption: { id: result.customerAttachedSampleList.division_id } };

					//invoicing type selected according to customer
					$scope.invoicingTypeList = [{ id: result.customerAttachedSampleList.invoicing_type_id, name: result.customerAttachedSampleList.invoicing_type }];
					$scope.orderSample.supplied_by = result.customerAttachedSampleList.customer_name;
					$scope.orderSample.manufactured_by = result.customerAttachedSampleList.customer_name;
					$scope.orderSample.suppliedBy = true;
					$scope.orderSample.manufacturedBy = true;
					$scope.orderCustomer.customer_city = result.customerAttachedSampleList.city_id;
					$scope.orderCustomer.customer_city_name = result.customerAttachedSampleList.city_name;
					$scope.orderCustomer.customer_address_detail = result.customerAttachedSampleList.customer_address_detail;
					$scope.orderCustomer.mfg_lic_no = result.customerAttachedSampleList.mfg_lic_no;
					$scope.orderCustomer.sale_executive_name = result.customerAttachedSampleList.name;
					$scope.orderCustomer.sale_executive = result.customerAttachedSampleList.sale_executive;
					$scope.orderCustomer.discount_type_name = result.customerAttachedSampleList.discount_type_name;
					$scope.orderCustomer.discount_type_id = result.customerAttachedSampleList.discount_type_id;
					$scope.orderCustomer.discount_value = result.customerAttachedSampleList.discount_value;
					$scope.orderCustomer.invoicing_type_id = { id: result.customerAttachedSampleList.invoicing_type_id };
					$scope.orderCustomer.sample_priority_id = { sample_priority_id: result.customerAttachedSampleList.customer_priority_id };
					$scope.orderAddSamplePriorityId = result.customerAttachedSampleList.customer_priority_id;
					if ($scope.orderAddSamplePriorityId == '4') {
						$scope.samplePriorityList = $scope.samplePriorityCRMList;
					} else {
						$scope.samplePriorityList = $scope.samplePriorityList;
					}
					$scope.funSetSurchargeValue(result.customerAttachedSampleList.customer_priority_id);
					$scope.IsVisiableErrorMsg = true;

					//Checking Billing Type is PO-Wise,then PO & PO-Date will be required
					$scope.orderSample.po_type = $scope.orderCustomerDetail.billing_type_id == '5' ? true : false;
					$scope.showAddPOType = $scope.orderCustomerDetail.billing_type_id == '5' ? true : false;

					//Setting for Tree View of Fixed Rate Customer
					$scope.fixedRateInvoicingTypeID = result.customerAttachedSampleList.fixed_rate_invoicing_type_id;

					//Seeting Default value in form if Sample receving belons to TRF No.
					if (result.trfHdrCount > '0') { $scope.funSetTrfDataInOrderForm(result.trfHdrData, $scope.sampleID, $scope.globalProductCategoryID, $scope.customerInvoicingTypeID) };

					//Getting Sampler Detail on Sample Name Change and based on Division.Added by Praveen Singh : 01-July-2022
					$scope.funGetSamplerDropdownList($scope.globalDivisionID);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			});
		}
	};

	//***************Setting Default value in form if Sample receving belons to TRF No.*************
	$scope.funSetTrfDataInOrderForm = function (trfHdrData, global_sample_id, global_product_category_id, global_invoicing_type_id) {
		if (trfHdrData.trfHdr) {

			//Customer Detail
			$scope.orderCustomer.mfg_lic_no = trfHdrData.trfHdr.trf_mfg_lic_no;

			//Sample Detail
			$scope.orderCustomer.division_id = { selectedOption: { id: trfHdrData.trfHdr.trf_division_id } };
			$scope.orderSample.sample_description = trfHdrData.trfHdr.trf_sample_name;
			$scope.orderSample.batch_no = trfHdrData.trfHdr.trf_batch_no;
			$scope.orderSample.mfg_date = trfHdrData.trfHdr.trf_mfg_date;
			$scope.orderSample.expiry_date = trfHdrData.trfHdr.trf_expiry_date;
			$scope.orderSample.batch_size = trfHdrData.trfHdr.trf_batch_size;
			$scope.orderSample.sample_qty = trfHdrData.trfHdr.trf_sample_qty;
			$scope.orderSample.supplied_by = trfHdrData.trfHdr.trf_supplied_by;
			$scope.orderSample.manufactured_by = trfHdrData.trfHdr.trf_manufactured_by;
			$scope.selectedProductCategoryId = trfHdrData.trfHdr.trf_product_category_id;
			$scope.orderProductTest.product_category_id = trfHdrData.trfHdr.trf_product_category_id;

			//Extra Detail
			if (trfHdrData.trfHdr.reporting_customer_id) {
				$scope.orderSample.invoicingNeeded = true;
				$scope.isInvoicingNeededAddFlag = true;
				$scope.customerListData = [{ id: trfHdrData.trfHdr.reporting_customer_id, name: trfHdrData.trfHdr.reporting_customer_name }];
				$scope.orderSample.reporting_to = { id: trfHdrData.trfHdr.reporting_customer_id };
			}
			if (trfHdrData.trfHdr.invoicing_customer_id) {
				$scope.orderSample.invoicingNeeded = true;
				$scope.isInvoicingNeededAddFlag = true;
				$scope.customerData = [{ id: trfHdrData.trfHdr.invoicing_customer_id, name: trfHdrData.trfHdr.invoicing_customer_name }];
				$scope.orderSample.invoicing_to = { id: trfHdrData.trfHdr.invoicing_customer_id };
			}

			//Products & Tests Detail
			if (trfHdrData.trfHdr.trf_product_id) {
				$scope.testProductList = [{ product_id: trfHdrData.trfHdr.trf_product_id, product_name: trfHdrData.trfHdr.trf_product_name }];
				$scope.orderProductTest.product_id = { product_id: trfHdrData.trfHdr.trf_product_id };
			}
			if (trfHdrData.trfHdr.trf_test_standard_id) {
				$scope.orderProductTest.test_standard = trfHdrData.trfHdr.trf_test_standard_id;
			}
			if (trfHdrData.trfHdr.trf_product_test_id) {
				$scope.productTestList = [{ test_id: trfHdrData.trfHdr.trf_product_test_id, test_code: trfHdrData.trfHdr.trf_product_test_name }];
				$scope.orderProductTest.product_test_id = { test_id: trfHdrData.trfHdr.trf_product_test_id };
				if (trfHdrData.trfHdrDtl.length) {
					angular.forEach(trfHdrData.trfHdrDtl, function (value, key) {
						if (value.trf_product_test_dtl_id) {
							$scope.allPopupSelectedParametersArray.push(value.trf_product_test_dtl_id);
						}
					});
					if ($scope.allPopupSelectedParametersArray != undefined && $scope.allPopupSelectedParametersArray.length > 0) {
						//$scope.funTestProductStandardParamentersList(trfHdrData.trfHdr.trf_product_test_id);
						$scope.funGetTrfTestProductStandardParamenters(trfHdrData.trfHdr.trf_id, global_sample_id, global_product_category_id, global_invoicing_type_id);
					}
				}
			}
		}
	};
	//*****************display TRF Test Product Standard dropdown*****************
	$scope.funGetTrfTestProductStandardParamenters = function (global_trf_id, global_sample_id, global_product_category_id, global_invoicing_type_id) {
		$http.post(BASE_URL + 'orders/get-product-test-parameters', {
			data: { formData: 'sample_id=' + global_sample_id + '&trf_id=' + global_trf_id },
		}).success(function (result, status, headers, config) {
			$scope.testProductParamenters = result.productTestParametersList;
			$timeout(function () { $scope.getClaimCalculation(result.productTestParametersList); }, 500);
			$scope.istableTrTdVisibleID = global_product_category_id == 2 && global_invoicing_type_id == 4 ? 1 : 0;
			$scope.tableTrTdColspanLevelI = global_product_category_id == 2 && global_invoicing_type_id == 4 ? 14 : 12;
			$scope.tableTrTdColspanLevelII = $scope.tableTrTdColspanLevelI - 1;
			$scope.realTimeStability = result.order_stability;
			$('#productParametersPopup').modal('hide');
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '400') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*****************/display TRF Test Product Standard dropdown******************

	//**************/Setting Default value in form if Sample receving belons to TRF No.*************

	//*****************display customer name on check of checkbox********
	$scope.checkCustomer = function (checked, customerName, type) {
		//For supplied_by customer
		if (type == "supplied_by") {
			if (checked == false) {
				$scope.orderSample.supplied_by = '';
			} else {
				$scope.orderSample.supplied_by = customerName;
			}
		} else if (type == "manufactured_by") {
			if (checked == false) {
				$scope.orderSample.manufactured_by = '';
			} else {
				$scope.orderSample.manufactured_by = customerName;
			}
		}
	}
	//*****************/display customer list location and Mfg Lic Number******

	//**********Adding of Order*************************************************
	$scope.funPlaceAndSaveOrder = function (divisionId, saveType) {

		if (!$scope.erpCreateOrderForm.$valid) return;
		if ($scope.newOrderflag) return;
		$scope.newOrderflag = true;
		$scope.loaderMainShow();
		var http_para_string = { formData: 'final_type_save=1' + '&' + 'duplicate_save=' + saveType + '&' + $(erpCreateOrderForm).serialize() };

		$http({
			url: BASE_URL + "orders/addOrder",
			method: "POST",
			headers: { 'Content-Type': 'application/json' },
			data: http_para_string
		}).success(function (result, status, headers, config) {
			$scope.newOrderflag = false;
			if (result.error == 1) {
				$('#previousOrderDetail').modal('hide');
				$scope.backButton();
				$scope.newOrderActive = result.data;
				//$scope.funGetOrdersList(divisionId);
				$scope.funGetTestSampleRecevied();
				$scope.resetPopupForm();
				$scope.successMsgShow(result.message);
			} else {
				if (result.previousOrderCount >= 1) {
					$scope.funOpenModalPopup(result.data, result.finalTypeSave);
				} else {
					$scope.errorMsgShow(result.message);
				}
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.newOrderflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/Adding of Order*************************************************

	//**********Adding of Order*************************************************
	$scope.funPlaceAndSaveMoreOrder = function (divisionId, finalTypeSave, saveType) {

		if (!$scope.erpCreateOrderForm.$valid) return;
		if ($scope.newOrderflag) return;
		$scope.newOrderflag = true;
		$scope.loaderMainShow();
		var http_para_string = { formData: 'final_type_save=' + finalTypeSave + '&' + 'duplicate_save=' + saveType + '&' + $(erpCreateOrderForm).serialize() };

		$http({
			url: BASE_URL + "orders/addOrder",
			method: "POST",
			headers: { 'Content-Type': 'application/json' },
			data: http_para_string
		}).success(function (result, status, headers, config) {
			$scope.newOrderflag = false;
			if (result.error == 1) {
				$('#previousOrderDetail').modal('hide');
				$scope.orderSample = {};
				$scope.newOrderActive = result.data;
				if (result.sample_id) {
					$scope.orderCustomer.sample_id = { id: result.sample_id };
					$scope.funGetCustomerAttachedSampleDetail(result.sample_id);
				}
				$scope.resetForm();
				$scope.resetPopupForm();
				$scope.funGetTestSampleRecevied();
				$scope.successMsgShow(result.message);
			} else {
				if (result.previousOrderCount >= 1) {
					$scope.funOpenModalPopup(result.data, result.finalTypeSave);
				} else {
					$scope.errorMsgShow(result.message);
				}
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.newOrderflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/Adding of Order*************************************************
	$scope.funOpenModalPopup = function (orderData, orderSaveType) {
		$scope.successMsgOnPopup = '';
		$scope.errorMsgOnPopup = '';
		$scope.orderData = orderData;
		$scope.finalSaveOrNot = orderSaveType;
		$('#previousOrderDetail').modal({ backdrop: 'static', keyboard: true, show: true });
	};
	$scope.funDuplicateCustomerOrder = function (saveType) {
		if ($scope.finalSaveOrNot != '1') {
			$scope.funPlaceAndSaveMoreOrder($scope.divisionID, '0', saveType);
		} else {
			$scope.funPlaceAndSaveOrder($scope.divisionID, saveType)
		}

	};
	//**********Editing of Order*************************************************
	$scope.funEditOrder = function (orderId) {

		$scope.orderEditSamplePriorityId = null;
		$scope.customerListData = [];
		$scope.customerData = [];
		$scope.updateOrder = {};
		$scope.updateOrder.product_test_id = {};
		$scope.order_id = orderId;
		$scope.updateOrder.selectedTestId = {};
		$scope.updateOrder.po_type = false;
		$scope.IsNewOrder = true;
		$scope.IsViewList = true;
		$scope.IsViewLogList = true;
		$scope.IsViewHidden = true;
		$scope.IsSaveAsOrder = true;
		$scope.IsUpdateOrder = false;
		$scope.order_field_name = {};
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "orders/edit_order/" + orderId,
			method: "GET",
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {

				$scope.updateOrder = result.orderList;
				$scope.updateOrderParameters = result.orderParameterList;
				$scope.istableTrTdVisibleID = $scope.updateOrder.product_category_id == 2 && $scope.updateOrder.invoicing_type_id == 4 ? 1 : 0;
				$scope.tableTrTdColspanLevelI = $scope.updateOrder.product_category_id == 2 && $scope.updateOrder.invoicing_type_id == 4 ? 14 : 12;
				$scope.tableTrTdColspanLevelII = $scope.tableTrTdColspanLevelI - 1;
				$scope.isRoleAdminForClientApproval = result.orderList.isRoleAdministrator;

				//Sample Information Detail
				$scope.sampleWithPlaceName = result.orderList.sample_no + '/' + result.orderList.customer_name + '/' + result.orderList.state_name + '/' + result.orderList.city_name;
				$scope.testSampleReceviedList = [{ id: result.orderList.sample_id, name: result.orderList.sample_no + '/' + result.orderList.customer_name + '/' + result.orderList.state_name + '/' + result.orderList.city_name }];
				$scope.funEditGetCustomerAttachedSampleDetail(orderId, result.orderList.sample_id);

				$scope.updateOrder.sale_executive = { selectedOption: { id: result.orderList.sale_executive } };
				$scope.updateOrder.selectedProductId = result.orderList.product_id;
				$scope.updateOrder.selectedTestId = result.orderList.product_test_id;
				$scope.testProductListing = [{ product_id: result.orderList.product_id, product_name: result.orderList.product_name }];
				$scope.updateOrder.product_id = { selectedOption: { product_id: result.orderList.product_id } };
				$scope.productTestListing = [{ test_id: result.orderList.product_test_id, test_code: result.orderList.test_code }];
				$scope.updateOrder.test_id = { selectedOption: { test_id: result.orderList.product_test_id } };
				$scope.updateOrder.pro_cat_id = $scope.updateOrderParameters[0].productCatID;

				$scope.funGetTestingProducts($scope.updateOrder.pro_cat_id);
				$scope.funGetTestProductStandard($scope.updateOrder.selectedProductId);
				$scope.funGetdefinedTestStandardList($scope.updateOrder.division_id, $scope.updateOrder.product_category_id);

				$scope.updateOrder.defined_test_standard = { selectedOption: { id: result.orderList.defined_test_standard } };
				var order_date_formated = result.orderList.order_date.split(" ");
				$scope.updateOrder.order_date = order_date_formated[0] ? order_date_formated[0] : '';

				$scope.funEditSetSurchargeValue(result.orderList.sample_priority_id);
				$scope.funEditSubmissionTypeValue(result.orderList.submission_type);

				//****************stability note Condition*********************
				if ($scope.updateOrder.stability_note) {
					$scope.realTimeStabilityStatus = true;
				} else {
					$scope.realTimeStabilityStatus = false;
				}
				//****************/stability note Condition*********************

				//****************Header note Condition*********************
				if ($scope.updateOrder.header_note) {
					$scope.headerNoteStatus = true;
				} else {
					$scope.headerNoteStatus = false;
				}
				//****************/Header note Condition*********************

				//****************Hold Condition*****************************
				if (result.orderList.hold_reason) {
					$scope.orderHoldEditFlag = true;
					$scope.isOrderHoldNeeded = true;
				} else {
					$scope.orderHoldEditFlag = false;
					$scope.isOrderHoldNeeded = false;
				}
				if ($scope.updateOrder.canHoldUnholdOrder > 0) {
					$scope.canHoldUnholdOrder = true;
				} else {
					$scope.canHoldUnholdOrder = false;
				}
				$timeout(function () { $scope.funOrderOnHoldOrNotOnEdit(); }, 500);
				//****************/Hold Condition*****************************

				//****************PO Condition*********************				
				if (result.orderList.po_no || result.orderList.po_date) {
					$scope.showEditPOType = true;
					$scope.updateOrder.po_type = true;
				} else {
					$scope.showEditPOType = false;
					$scope.updateOrder.po_type = false;
				}
				if (result.orderList.status > 8 && result.orderList.status != 12) {
					$scope.canChangePoTypeOrder = true;
				} else {
					$scope.canChangePoTypeOrder = false;
				}
				//****************/PO Condition*********************

				//****************Sample Type Condition************
				if ($scope.updateOrder.order_sample_type) {
					$scope.showEditSampleType = true;
				} else {
					$scope.showEditSampleType = false;
				}
				if (result.orderList.status > 8 && result.orderList.status != 12) {
					$scope.canChangeSampleTypeOrder = true;
				} else {
					$scope.canChangeSampleTypeOrder = false;
				}
				//****************Sample Type Condition************

				//********ReportingTo/InvoicingTo Condition***********
				if (result.orderList.reporting_to || result.orderList.invoicing_to) {
					$scope.isInvoicingEditCheckBoxFlag = true;
					$scope.isInvoicingNeededEditFlag = true;
				} else {
					$scope.isInvoicingEditCheckBoxFlag = false;
					$scope.isInvoicingNeededEditFlag = false;
				}
				if (result.orderList.status > 8 && result.orderList.status != 12) {
					$scope.canChangeInvoicingTo = true;
				} else {
					$scope.canChangeInvoicingTo = false;
				}
				//*******/ReportingTo/InvoicingTo Condition***********

				//********Tat In Days Condition***********
				if (result.orderList.tat_in_days) {
					$scope.isTatInDaysEditableDivFlag = true;
				} else {
					$scope.isTatInDaysEditableDivFlag = false;
				}
				//********Tat In Days Condition***********

				if (result.orderList.reportingCustomerId) {
					$scope.customerListData = [{ id: result.orderList.reportingCustomerId, name: result.orderList.reportingCustomerName }];
					$scope.updateOrder.reporting_to = { selectedOption: { id: result.orderList.reporting_to } };
				}
				if (result.orderList.invoicingCustomerId) {
					$scope.customerData = [{ id: result.orderList.invoicingCustomerId, name: result.orderList.invoicingCustomerName }];
					$scope.updateOrder.invoicing_to = { selectedOption: { id: result.orderList.invoicing_to } };
				}
				$scope.updateOrder.sample_id = { selectedOption: { id: result.orderList.sample_id } };
				$scope.updateOrder.division_id = { selectedOption: { id: result.orderList.division_id } };
				$scope.orderEditSamplePriorityId = result.orderList.sample_priority_id;
				$scope.updateOrder.sample_priority_id = { selectedOption: { sample_priority_id: result.orderList.sample_priority_id } };
				$scope.updateOrder.is_sealed = { selectedOption: { id: result.orderList.is_sealed } };
				$scope.updateOrder.is_signed = { selectedOption: { id: result.orderList.is_signed } };
				$scope.updateOrder.submission_type = { selectedOption: { id: result.orderList.submission_type } };
				$scope.funGetDiscountTypeInput(result.orderList.discount_type_id, 'edit');
				$scope.updateOrder.discount_type_id = { id: result.orderList.discount_type_id };
				$scope.updateOrder.billing_type_id = { id: result.orderList.billing_type_id };
				$scope.updateOrder.invoicing_type_id = { id: result.orderList.invoicing_type_id };
				$scope.updateOrder.order_sample_type = result.orderList.order_sample_type;
				$scope.updateOrder.sampler_id = { id: result.orderList.sampler_id };
				$scope.updateOrderParameters.test_parameter_id = {};

				//Getting Dynamic Field Array Data
				$scope.dynamicColumnNameList = result.orderList.order_dynamic_fields;
				$timeout(function () {
					angular.forEach($scope.dynamicColumnNameList, function (value, key) {
						$scope.order_field_name[key] = {
							name: value.order_field_name
						};
					});
				}, 500);

				//Client Approval Status
				$scope.funEditShowHideClientApprovalNeeded($scope.updateOrder.clientApprovalList);

			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//**********/fun edit of Order*************************************************

	//*****************display customer list location and Mfg Lic Number********
	$scope.funEditGetCustomerAttachedSampleDetail = function (orderId, sampleId) {

		$scope.updateOrder.customer_id = null;
		$scope.updateOrder.customer_city_name = null;
		$scope.updateOrder.discount_type_name = null;
		$scope.updateOrder.discount_value = null;
		$scope.updateOrder.discount_value_set = null;
		$scope.updateOrder.suppliedBy = true;
		$scope.updateOrder.manufacturedBy = true;
		$scope.funGetBillingTypeList(orderId);	       //Getting Default Billing Type
		$scope.funGetInvoicingTypesList(orderId);     //Getting Default Invoicing Type
		$scope.funGetDiscountTypeList(orderId);       //Getting Default Discount Type

		if (customer_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'sales/orders/get-edit-customer-attached-sample-detail/' + orderId + '/' + sampleId
			}).success(function (result) {
				if (result.error == 1) {
					$scope.sampleID = sampleId;
					$scope.customerNameList = result.customerNameList;
					$scope.salesExecutiveList = result.salesExecutiveList;
					$scope.discountTypeSetOnEditList = $scope.discountTypeOnEditList;
					$scope.billingTypeSetOnEditList = $scope.billingTypeOnEditList;
					$scope.invoicingTypesSetOnEditList = $scope.invoicingTypesOnEditList;
					$scope.customerInvoicingTypeID = result.customerAttachedSampleList.invoicing_type_id;
					$scope.globalProductCategoryID = result.customerAttachedSampleList.product_category_id;
					$scope.globalDivisionID = result.customerAttachedSampleList.division_id;
					$scope.globalCustomerID = result.customerAttachedSampleList.customer_id;
					$scope.updateOrder.customer_id = { selectedOption: { customer_id: result.customerAttachedSampleList.customer_id } };
					$scope.updateOrder.customer_name = result.customerAttachedSampleList.customer_name;
					$scope.updateOrder.customer_city_name = result.customerAttachedSampleList.city_name;
					$scope.updateOrder.discount_value = result.customerAttachedSampleList.discount_value;
					$scope.updateOrder.discount_value_set = result.customerAttachedSampleList.discount_value;
					$scope.updateOrder.mfg_lic_no = result.customerAttachedSampleList.mfg_lic_no;
					$scope.updateOrder.customer_address_detail = result.customerAttachedSampleList.customer_address_detail;

					//Getting Sampler Detail on Sample Name Change and based on Division.Added by Praveen Singh : 01-July-2022
					$scope.funGetSamplerDropdownList(result.customerAttachedSampleList.division_id);
				}
				$scope.clearConsole();
			});
		}
	};
	//*****************/display customer list location and Mfg Lic Number*************************

	//*****************display customer list location and Mfg Lic Number**************************
	$scope.funEditCustomerAttachedDetail = function (customer_id) {

		$scope.updateOrder.customer_city_name = null;
		$scope.updateOrder.sale_executive = null;
		$scope.updateOrder.discount_type_name = null;
		$scope.updateOrder.discount_type_id = null;
		$scope.updateOrder.discount_value = null;
		$scope.updateOrder.suppliedBy = true;
		$scope.updateOrder.manufacturedBy = true;

		if (customer_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/get_customer_attached_detail/' + customer_id + '/' + $scope.sampleID
			}).success(function (result) {
				if (result.error == 1) {
					$scope.globalCustomerID = customer_id;
					$scope.salesExecutiveList = result.salesExecutiveList;
					$scope.updateOrder.customer_city_name = result.customerAttachedList.city_name;
					$scope.updateOrder.customer_city = result.customerAttachedList.city_id;
					$scope.updateOrder.mfg_lic_no = result.customerAttachedList.mfg_lic_no;
					$scope.updateOrder.sale_executive = { selectedOption: { id: result.customerAttachedList.sale_executive } };
					$scope.updateOrder.discount_type_id = { id: result.customerAttachedList.discount_type_id };
					$scope.updateOrder.billing_type_id = { id: result.customerAttachedList.billing_type_id };
					$scope.updateOrder.invoicing_type_id = { id: result.customerAttachedList.invoicing_type_id };
					$scope.updateOrder.discount_value = result.customerAttachedList.discount_value;
					$scope.updateOrder.discount_value_set = result.customerAttachedList.discount_value;
					$scope.funEditShowPODetailType(result.customerAttachedList.billing_type_id);
					$scope.funGetDiscountTypeInput(result.customerAttachedList.discount_type_id, 'edit');
					$scope.funSetEditInvoicingType(customer_id, result.customerAttachedList.invoicing_type_id);
					$scope.IsVisiableErrorMsg = true;
					$scope.successMsgShow(result.message);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
			});
		}
	};
	//*****************/display customer list location and Mfg Lic Number*************************

	//*****************/discount types onchange**************************************************
	$scope.applyDiscountTypeYes = true;
	$scope.applyDiscountTypeNo = false;
	$scope.funGetDiscountTypeInput = function (discountTypeId, type) {
		if (discountTypeId && type == 'edit') {
			$scope.updateOrder.discount_value = null;
			if (discountTypeId == '3') {
				$scope.applyDiscountTypeYes = false;
				$scope.applyDiscountTypeNo = true;
				$scope.updateOrder.discount_value = null;
			} else {
				$scope.applyDiscountTypeYes = true;
				$scope.applyDiscountTypeNo = false;
				$scope.updateOrder.discount_value = $scope.updateOrder.discount_value_set;
			}
		}
	}
	//*****************/discount types onchange***************************************************

	//*****************display customer name on check of checkbox*********************************
	$scope.checkEditCustomer = function (checked, customerName, type) {
		// for supplied_by customer
		if (type == "supplied_by") {
			if (checked == false) {
				$scope.updateOrder.supplied_by = '';
			} else {
				$scope.updateOrder.supplied_by = customerName;
			}
		} else if (type == "manufactured_by") {
			if (checked == false) {
				$scope.updateOrder.manufactured_by = '';
			} else {
				$scope.updateOrder.manufactured_by = customerName;
			}
		}
	}
	//*****************/display customer name on check of checkbox********

	//*******************Order on hold **************************************
	$scope.funOrderOnHoldOrNot = function () {
		var orderHoldFlagNeeded = angular.element('#add_order_type').prop('checked');
		if (orderHoldFlagNeeded) {
			$scope.orderHoldAddFlag = true;
			//$scope.canAddChangePoTypeOrder = true;
			//$scope.canAddChangeSampleTypeOrder = true;
			//$scope.canAddChangeInvoicingTo = true;
		} else {
			$scope.orderHoldAddFlag = false;
			//$scope.canAddChangePoTypeOrder = false;
			//$scope.canAddChangeSampleTypeOrder = false;
			//$scope.canAddChangeInvoicingTo = false;
			$scope.orderSample.hold_reason = '';
		}
	};

	$scope.funOrderOnHoldOrNotOnEdit = function (orderId = null) {
		var orderHoldEditFlagNeeded = angular.element('#edit_hold_type').prop('checked');
		if (orderHoldEditFlagNeeded) {
			$scope.orderHoldEditFlag = true;
			//$scope.canEditChangePoTypeOrder      = true;
			//$scope.canEditChangeSampleTypeOrder  = true;
			//$scope.canEditChangeInvoicingTo      = true;
		} else {
			$scope.orderHoldEditFlag = false;
			//$scope.canEditChangePoTypeOrder      = false;
			//$scope.canEditChangeSampleTypeOrder  = false;
			//$scope.canEditChangeInvoicingTo      = false;
			if (orderId) { $scope.funUnHoldOrder(orderId, 'removeHold'); }
		}
	};
	//*******************/Order on hold **************************************

	$scope.closeEditParameter = function () {
		$scope.editOrderParameter = false;
	};

	//**********open Update Parameter*************************************************
	$scope.openUpdateParameter = function (para_cat_id, product_cat_id, subCatPara) {

		$scope.editOrderParameter = true;
		$scope.hideOrderParameter = false;
		$scope.AnalysisId = subCatPara.analysis_id;
		$scope.parameterList = [];
		$scope.methodList = [];
		$scope.equipmentList = [];

		if (subCatPara) {
			$scope.AnalysisId = subCatPara.analysis_id;
			$scope.loaderShow();
			$http.post(BASE_URL + "sales/orders/get-edit-form-dropdowns/" + para_cat_id + '/' + product_cat_id, {
			}).success(function (data, status, headers, config) {
				$scope.parameterList = data.parameterList;
				$scope.methodList = data.methodList;
				$scope.equipmentList = data.equipmentList;
				if ($scope.parameterList) {
					$scope.test_parameter_id = {
						selectedOption: { "id": subCatPara.test_parameter_id }
					};
				}
				if ($scope.equipmentList) {
					$scope.equipment_type_id = {
						selectedOption: { "id": subCatPara.equipment_type_id }
					};
				}
				if ($scope.methodList) {
					$scope.method_id = {
						selectedOption: { "id": subCatPara.method_id }
					};
				}
				$scope.clearConsole();
				$scope.loaderHide();
			}).error(function (data, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}
	};
	//*********/open Update Parameter*************************************************

	//**********fun Update of Order*************************************************
	$scope.funUpdateOrder = function (divisionId) {

		if (!$scope.erpUpdateOrderForm.$valid) return;
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "orders/updateOrder",
			method: "POST",
			headers: { 'Content-Type': 'application/json' },
			data: { formData: $(erpUpdateOrderForm).serialize() }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				//$scope.funGetOrdersList(divisionId);
				if (result.status == 12) {
					$scope.backButton();
				} else {
					$scope.funEditOrder(result.orderId);
				}
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/fun Update of Order*********************************************

	//**********Viewing of Order*************************************************
	$scope.funViewOrder = function (orderId) {

		$scope.IsNewOrder = true;
		$scope.IsUpdateOrder = true;
		$scope.IsViewList = true;
		$scope.IsViewLogList = true;
		$scope.IsSaveAsOrder = true;
		$scope.IsViewHidden = false;
		$scope.newOrderActive = '';
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "orders/view_order/" + orderId,
			method: "GET",
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.isViewOrdersStatisticSection = false;
				$scope.viewOrder = result.orderList;
				$scope.IsAllowGenerateJobOrderPdf = !$scope.viewOrder.job_order_file && !$scope.viewOrder.order_report_id ? true : false;
				$scope.IsAllowDownloadJobOrderPdf = $scope.viewOrder.job_order_file ? true : false;
				$scope.orderParametersList = result.orderParameterList;
				$scope.orderTrackingList = result.orderTrackingList;
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
		});
	};
	//**********/Viewing of Order*********************************************

	//**********Viewing of Order Statistics**********************************
	$scope.funViewOrdersStatistics = function (orderId) {

		$scope.IsNewOrder = true;
		$scope.IsUpdateOrder = true;
		$scope.IsViewList = true;
		$scope.IsViewLogList = true;
		$scope.IsSaveAsOrder = true;
		$scope.IsViewHidden = false;
		$scope.newOrderActive = '';
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "orders/view_order/" + orderId,
			method: "GET",
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.isViewOrdersStatisticSection = true;
				$scope.viewOrder = result.orderList;
				$scope.IsAllowGenerateJobOrderPdf = !$scope.viewOrder.job_order_file && !$scope.viewOrder.order_report_id ? true : false;
				$scope.IsAllowDownloadJobOrderPdf = $scope.viewOrder.job_order_file ? true : false;
				$scope.orderParametersList = result.orderParameterList;
				$scope.orderTrackingList = result.orderTrackingList;
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
		});
	};
	//**********/Viewing of Orders Statistics**************************************/

	//**********Viewing of Order Log**********************************
	$scope.funViewOrderLog = function (orderId, orderNo, divisionID) {

		$scope.OrderId = orderId;
		$scope.DivisionID = divisionID;
		$scope.logOrderNumber = orderNo;
		$scope.IsNewOrder = true;
		$scope.IsUpdateOrder = true;
		$scope.IsSaveAsOrder = true;
		$scope.IsViewList = true;
		$scope.IsViewLogList = false;
		$scope.IsViewHidden = true;
		$scope.newOrderActive = '';
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "sales/orders/view_order_log/" + orderId,
			method: "GET",
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.orderLogList = result.orderLogList;
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
		});
	};
	//**********/Viewing of Orders Log**************************************

	//**********deleting of Order********************************************
	$scope.funDeleteOrder = function (orderId, divisionId) {

		$scope.loaderShow();
		$scope.newOrderActive = '';

		$http({
			url: BASE_URL + "orders/delete_order/" + orderId,
			method: "GET",
		}).success(function (data, status, headers, config) {
			if (data.error == 1) {
				$scope.successMsgShow(data.message);
				$scope.funGetOrdersList(divisionId);
			} else {
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
	};
	//**********/deleting of Order*************************************************

	//**********Removing Test parameter****************************************
	$scope.removeTestParameterRow = function (product_test_dtl_id) {
		$("#alternative_paramter" + product_test_dtl_id).remove();
	};

	//**********//Removing Test parameter**************************************

	//**********alternative Test Parameter Popup**************************************
	$scope.alternativeTestParameterPopup = function (product_test_dtl_id) {
		if (angular.isDefined(product_test_dtl_id)) {
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/get-alter-product-test-parameters/' + product_test_dtl_id
			}).success(function (result) {
				$scope.alterTestProductStandardParamenters = result.alternativeTestProParamsList;
				$scope.IsViewAlternativeHidden = false;
				$("#viewAlternativeModal").modal("show");
			});
		}

	};
	//********** /alternative Test Parameter Popup**************************************

	//*****************display Test Product Standard dropdown**********************
	$scope.selectAlternativeTestParameterRow = function (product_test_param_altern_method_id, product_test_dtl_id) {
		if (angular.isDefined(product_test_param_altern_method_id) && angular.isDefined(product_test_dtl_id)) {
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/reselect_test_standard_parameters/' + product_test_param_altern_method_id,
			}).success(function (result) {
				angular.element("#test_parameter_name_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.test_parameter_name);
				angular.element("#equipment_name_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.equipment_name);
				angular.element("#method_name_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.method_name);
				angular.element("#standard_value_from_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.standard_value_from);
				angular.element("#standard_value_to_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.standard_value_to);
				if (result.alterSelectedTestProParamsList.time_taken_days) {
					angular.element("#time_taken_days_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.time_taken_days + ' Days');
				}
				if (result.alterSelectedTestProParamsList.time_taken_mins) {
					angular.element("#time_taken_mins_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.time_taken_mins + ' Mins');
				}
				angular.element("#product_test_parameter" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.product_test_dtl_id);
				angular.element("#test_parameter_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.test_parameter_id);
				angular.element("#equipment_type_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.equipment_type_id);
				angular.element("#method_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.method_id);
				angular.element("#standard_value_from" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_from);
				angular.element("#standard_value_to" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_to);
				angular.element("#org_standard_value_from" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_from);
				angular.element("#org_standard_value_to" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_to);
				angular.element("#time_taken_days" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.time_taken_days);
				angular.element("#time_taken_mins" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.time_taken_mins);
				angular.element("#cost_price" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.cost_price);
				angular.element("#selling_price" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.selling_price);
				angular.element("#test_param_alternative_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.product_test_param_altern_method_id);
				angular.element("#department_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.department_id);
				$scope.funChangeTestParameterValueAccToClaim(product_test_dtl_id);
				$("#viewAlternativeModal").modal("hide");
				$scope.funChangeTestParameterValueAccToClaimUnit(product_test_dtl_id);
			});
		}
	};
	//*****************/display Test Product Standard dropdown*********************

	//*****************display Test Product Standard dropdown* 28 nov*********************
	$scope.funChangeTestParameterValueAccToClaim = function (product_test_dtl_id, type) {
		var claimValue = angular.element('#claim_value' + product_test_dtl_id).val();

		if (type == "edit") {
			var standardValueFrom = angular.element('#edit_org_standard_value_from' + product_test_dtl_id).val();
			var standardValueTo = angular.element('#edit_org_standard_value_to' + product_test_dtl_id).val();
		} else {
			var standardValueFrom = angular.element('#org_standard_value_from' + product_test_dtl_id).val();
			var standardValueTo = angular.element('#org_standard_value_to' + product_test_dtl_id).val();
		}

		if (claimValue != '' && standardValueFrom && standardValueTo) {

			var standardValueFromClaimed = standardValueToClaimed = '0.00';
			var standardValueFromClaimed = !isNaN(standardValueFrom) ? ((standardValueFrom * claimValue) / 100).toFixed(9) : standardValueFrom;
			var standardValueToClaimed = !isNaN(standardValueTo) ? ((standardValueTo * claimValue) / 100).toFixed(9) : standardValueTo;
			angular.element('#standard_value_from_text' + product_test_dtl_id).html(standardValueFromClaimed);
			angular.element('#standard_value_to_text' + product_test_dtl_id).html(standardValueToClaimed);
			angular.element('#standard_value_from' + product_test_dtl_id).val(standardValueFromClaimed);
			angular.element('#standard_value_to' + product_test_dtl_id).val(standardValueToClaimed);
		} else {
			angular.element('#standard_value_from_text' + product_test_dtl_id).html(standardValueFrom);
			angular.element('#standard_value_to_text' + product_test_dtl_id).html(standardValueTo);
			angular.element('#standard_value_from' + product_test_dtl_id).val(standardValueFrom);
			angular.element('#standard_value_to' + product_test_dtl_id).val(standardValueTo);
		}

		$scope.funChangeTestParameterValueAccToClaimUnit(product_test_dtl_id);
	};
	//*****************display Test Product Standard dropdown* 28 nov*********************

	//*****************/display Test Product Standard dropdown*********************/
	$scope.funOnNewChangeTestParameterValueAccToClaim = function (product_test_dtl_id) {

		var claimValue = angular.element('#new_claim_value' + product_test_dtl_id).val();
		var standardValueFrom = angular.element('#new_org_standard_value_from' + product_test_dtl_id).val();
		var standardValueTo = angular.element('#new_org_standard_value_to' + product_test_dtl_id).val();

		if (claimValue != '' && standardValueFrom && standardValueTo) {

			var standardValueFromClaimed = standardValueToClaimed = '0.00';
			var standardValueFromClaimed = !isNaN(standardValueFrom) ? ((standardValueFrom * claimValue) / 100).toFixed(9) : standardValueFrom;
			var standardValueToClaimed = !isNaN(standardValueTo) ? ((standardValueTo * claimValue) / 100).toFixed(9) : standardValueTo;

			angular.element('#new_standard_value_from_text' + product_test_dtl_id).html(standardValueFromClaimed);
			angular.element('#new_standard_value_to_text' + product_test_dtl_id).html(standardValueToClaimed);
			angular.element('#new_standard_value_from' + product_test_dtl_id).val(standardValueFromClaimed);
			angular.element('#new_standard_value_to' + product_test_dtl_id).val(standardValueToClaimed);
		} else {
			angular.element('#new_standard_value_from_text' + product_test_dtl_id).html(standardValueFrom);
			angular.element('#new_standard_value_to_text' + product_test_dtl_id).html(standardValueTo);
			angular.element('#new_standard_value_from' + product_test_dtl_id).val(standardValueFrom);
			angular.element('#new_standard_value_to' + product_test_dtl_id).val(standardValueTo);
		}

		$scope.funOnNewChangeTestParameterValueAccToClaimUnit(product_test_dtl_id);
	};
	//*****************display Claim Value Unit**********************
	$scope.funOnNewChangeTestParameterValueAccToClaimUnit = function (product_test_dtl_id) {

		var claimValueInput = angular.element('#new_claim_value' + product_test_dtl_id).val();
		var claimValueUnitInput = angular.element('#new_claim_value_unit' + product_test_dtl_id).val();
		var standardValueFromUnit = angular.element('#new_standard_value_from' + product_test_dtl_id).val();
		var standardValueToUnit = angular.element('#new_standard_value_to' + product_test_dtl_id).val();

		if (claimValueInput && claimValueUnitInput && isNaN(claimValueUnitInput)) {
			if (!isNaN(standardValueFromUnit)) {
				angular.element('#new_standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit + ' ' + claimValueUnitInput);
			} else {
				angular.element('#new_standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit);
			}
			if (!isNaN(standardValueToUnit)) {
				angular.element('#new_standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit + ' ' + claimValueUnitInput);
			} else {
				angular.element('#new_standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit);
			}
		} else {
			claimValueUnitInput = '';
			angular.element('#standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit);
			angular.element('#standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit);
		}
	};

	//*****************display Claim Value Unit**********************
	$scope.funChangeTestParameterValueAccToClaimUnit = function (product_test_dtl_id) {

		var claimValueInput = angular.element('#claim_value' + product_test_dtl_id).val();
		var claimValueUnitInput = angular.element('#claim_value_unit' + product_test_dtl_id).val();
		var standardValueFromUnit = angular.element('#standard_value_from' + product_test_dtl_id).val();
		var standardValueToUnit = angular.element('#standard_value_to' + product_test_dtl_id).val();

		if (claimValueInput && claimValueUnitInput && isNaN(claimValueUnitInput)) {
			if (!isNaN(standardValueFromUnit)) {
				angular.element('#standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit + ' ' + claimValueUnitInput);
			} else {
				angular.element('#standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit);
			}
			if (!isNaN(standardValueToUnit)) {
				angular.element('#standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit + ' ' + claimValueUnitInput);
			} else {
				angular.element('#standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit);
			}
		} else {
			claimValueUnitInput = '';
			angular.element('#standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit);
			angular.element('#standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit);
		}
	};
	//*****************/display Claim Value Unit*********************

	//show category serach filter
	$scope.showProductFilter = function () {
		$scope.ProductCategoryFilterBtn = true;
		$scope.ProductCategoryFilterInput = false;
	};

	//hide category serach filter
	$scope.hideProductFilter = function () {
		$scope.ProductCategoryFilterBtn = false;
		$scope.ProductCategoryFilterInput = true;
	};

	//*****************display division dropdown*****************
	$scope.isSetSurchargeValueFlag = false;
	$scope.funSetSurchargeValue = function (samplePriorityId) {
		$scope.isSetSurchargeValueFlag = false;
		if (samplePriorityId == 3) {
			$scope.isSetSurchargeValueFlag = true;
		} else {
			$scope.orderSample.surcharge_value = '';
		}
	};
	//*****************/display division dropdown*****************/

	//*****************display division dropdown*****************
	$scope.advanceDetailsDisplay = false;
	$scope.funSubmissionTypeValue = function (submissionTypeId) {
		$scope.advanceDetailsDisplay = false;
		if (submissionTypeId == 1) {
			$scope.advanceDetailsDisplay = true;
		} else {
			$scope.orderSample.advance_details = '';
		}
	};
	//*****************/display division dropdown*****************

	//*****************display division dropdown*****************
	$scope.isEditSetSurchargeValueFlag = false;
	$scope.funEditSetSurchargeValue = function (samplePriorityId) {
		$scope.isEditSetSurchargeValueFlag = false;
		if (samplePriorityId == 3) {
			$scope.isEditSetSurchargeValueFlag = true;
		} else {
			$scope.updateOrder.surcharge_value = '';
		}
	};
	//*****************/display division dropdown*****************/

	//*****************display division dropdown*****************
	$scope.editAdvanceDetailsDisplay = false;
	$scope.funEditSubmissionTypeValue = function (submissionTypeId) {
		$scope.editAdvanceDetailsDisplay = false;
		if (submissionTypeId == 1) {
			$scope.editAdvanceDetailsDisplay = true;
		} else {
			$scope.updateOrder.advance_details = '';
		}
	};
	//*****************/display division dropdown*****************

	//***************************confirm box******************************************************
	$scope.funConfirmCancelOrderMessage = function (orderId, divisionId) {
		$ngConfirm({
			title: false,
			content: defaultCancelOrderMsg,    			//Defined in public/ang/modules/message-constant.js and included in head
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme: 'bootstrap',
			columnClass: 'col-sm-5 col-md-offset-3',
			buttons: {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funCancelOrder(orderId, divisionId);
					}
				},
				cancel: {
					text: 'cancel',
					btnClass: 'btn-default ng-confirm-closeIcon'
				}
			}
		});
	};
	//***************************/confirm box**************************************

	//*********************select all functionality************************************
	$scope.toggleAll = function () {
		$scope.allPopupSelectedParametersArray = [];
		var checkAllStatus = angular.element('#selectedAll').prop('checked');
		if (checkAllStatus) {
			angular.element('.parametersCheckBox').prop('checked', true);
			angular.element(".parametersCheckBox:checked").each(function () {
				$scope.allPopupSelectedParametersArray.push(this.value);
			});
		} else {
			angular.element('.parametersCheckBox').prop('checked', false);
		}
	};
	//*********************select all functionality************************************
	$scope.toggleAllOnEdit = function () {
		$scope.allPopupSelectedParametersArray = [];
		var checkAllStatus = angular.element('#selectedAllOnEdit').prop('checked');
		if (checkAllStatus) {
			angular.element('.parametersCheckBoxOnEdit').prop('checked', true);
			angular.element(".parametersCheckBoxOnEdit:checked").each(function () {
				$scope.allPopupSelectedParametersArray.push(this.value);
			});
		} else {
			angular.element('.parametersCheckBoxOnEdit').prop('checked', false);
		}
	};
	//**********generate order pdf report******************************************
	$scope.funGenerateJobOrderPDFBK = function (divId, orderNumber, orderId) {

		var order_name = orderNamePrefix + orderNumber + ".pdf";
		angular.element('.hideContentOnPdf').hide();
		$scope.showContentOnPdf = true;
		$scope.loaderMainShow();

		kendo.drawing.drawDOM(angular.element("#" + divId)).then(function (group) {
			kendo.drawing.pdf.toDataURL(group, function (dataURL) {

				var formD = new FormData();
				formD.append("job_order_file", order_name);
				formD.append("order_id", orderId);
				formD.append("order_file", dataURL);

				$http({
					url: BASE_URL + "sales/orders/upload_order_pdf",
					headers: { 'Content-Type': undefined },
					data: formD,
					method: "POST",
					processData: false,
					contentType: false,
				}).success(function (result, status, headers, config) {
					$scope.funViewOrder(result.formData.order_id);
					$scope.funViewOrdersStatistics(result.formData.order_id);
					angular.element('.hideContentOnPdf').show();
					$scope.showContentOnPdf = false;
					$scope.order_name = result.formData.job_order_file;
				}).error(function (result, status, headers, config) {
					$scope.errorMsgShow($scope.defaultMsg);
					$scope.loaderMainHide();
				});
			});
		});
	};
	//**********/generate order pdf report******************************************

	//**********generate order pdf report******************************************
	$scope.funGenerateJobOrderPDF = function (orderId) {
		$scope.loaderMainShow();
		$http({
			method: "POST",
			url: BASE_URL + "sales/orders/generate-job-order-pdf",
			data: { order_id: orderId },
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.funViewOrder(orderId);
				$scope.funViewOrdersStatistics(orderId);
				if (result.jobOrderFile) {
					window.open(BASE_URL + result.jobOrderFile, '_blank');
				}
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.errorMsgShow($scope.defaultMsg);
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/generate order pdf report******************************************

	//**********alternative Test Parameter Popup**************************************
	$scope.funOpenInternalTransferSamplePopup = function (sampleId) {
		if (sampleId) { $("#interal_transfer_sample_popup").modal("show"); }
	};
	//********** /alternative Test Parameter Popup**************************************

	//***************** Adding of Internal Transfer Sample ******************************
	$scope.funAddInternalTransferSample = function (sampleId) {

		if ($scope.erpAddInteralTransferSampleFormflag) return;
		$scope.erpAddInteralTransferSampleFormflag = true;
		var formData = $(erpAddInteralTransferSampleForm).serialize();

		$http({
			url: BASE_URL + "sales/samples/add-internal-transfer-sample",
			method: "POST",
			data: { formData: formData }
		}).success(function (data, status, headers, config) {
			$scope.erpAddInteralTransferSampleFormflag = false;
			if (data.error == 1) {
				$scope.addInternalTransferSample = {};
				$scope.isViewInteralTransferSampleLink = false;
				$("#interal_transfer_sample_popup").modal("hide");
				$scope.successMsgShow(data.message);
			} else {
				angular.element('#interal_transfer_sample').prop('checked', false);
				$("#interal_transfer_sample_popup").modal("hide");
				$scope.errorMsgShow(data.message);
			}
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.erpAddInteralTransferSampleFormflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Internal Transfer Sample *************************

	//*****************New Auto complete Section**********************************
	$scope.getAutoSearchSampleMatches = function (searchText, sampleId) {
		$http({
			method: 'GET',
			url: BASE_URL + 'sales/orders/get-sample-name-list/' + sampleId + '/' + searchText
		}).success(function (result) {
			$scope.resultItems = result.itemsList;
			$scope.customerInvoicingTypeID = result.invoicing_type_id;
			$scope.fixedRateInvoicingTypeID = result.fixed_rate_invoicing_type_id;
			$scope.sampleID = sampleId;
			if ($scope.resultItems.length >= 1) {
				$scope.showAutoSearchList = true;
				$scope.closeAutoSearchList = true;
			} else {
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
	$scope.funCloseAutoSearchList = function () {
		$scope.showAutoSearchList = false;
		$scope.resultItems = [];
	};
	//**********/Set parameter value when user selecet from auto search list****************

	//****************Set parameter value when user selecet from auto search list***********
	$scope.funsetSelectedSample = function (selectedSampleId, selectedSampleName, formType) {
		var selectedSampleNameArr = selectedSampleName.split("|");
		if (selectedSampleNameArr[0]) {
			if (formType == 'add') {
				$scope.orderSample.selected_sample_id = selectedSampleId;
				$scope.orderSample.sample_description = selectedSampleNameArr[0].trim();
				$scope.showAutoSearchList = false;
			} else if (formType == 'edit') {
				$scope.updateOrder.selected_sample_id = selectedSampleId;
				$scope.updateOrder.sample_description = selectedSampleNameArr[0].trim();
				$scope.showAutoSearchList = false;
			} else if (formType == 'saveAs') {
				$scope.saveAsOrder.selected_sample_id = selectedSampleId;
				$scope.saveAsOrder.sample_description = selectedSampleNameArr[0].trim();
				$scope.showAutoSearchList = false;
			}
		}
	};
	//****************/Set parameter value when user selecet from auto search list***********

	//*****************Hiding of Internal Transfer Sample ********************
	$scope.funHideInternalTransferSample = function () {
		angular.element('#interal_transfer_sample').prop('checked', false);
		$("#interal_transfer_sample_popup").modal("hide");
	};
	//*****************Hiding of Internal Transfer Sample ********************

	//*****************invoicing types*****************************************
	$scope.invoicingTypeList = [];
	$scope.funInvoicingTypeList = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'customer-invoicing-types-list'
		}).success(function (result) {
			if (result) {
				$scope.invoicingTypeList = result.invoicingTypes;
			}
			$scope.clearConsole();
		});
	};
	//*****************/invoicing types****************************************

	//*****************AutoSearch HeaderNote Matches***************************
	$scope.showHeaderNoteAutoSearchList = false;
	$scope.getAutoSearchHeaderNoteMatches = function (searchText) {
		$http({
			method: 'GET',
			url: BASE_URL + 'sales/orders/get-header-note-list/' + searchText
		}).success(function (result) {
			$scope.headerNotesList = result.itemsList;
			if ($scope.headerNotesList.length > 0) {
				$scope.showHeaderNoteAutoSearchList = true;
			} else {
				$scope.showHeaderNoteAutoSearchList = false;
			}
			$scope.clearConsole();
		});
		return $scope.headerNotesList;
	};

	//set parameter value when user selecet from auto search list
	$scope.funSetSelectedHeaderNote = function (selectedHeaderNote, selectedHeaderLimit, formType) {
		if (formType == 'add') {
			$scope.orderProductTest.header_note = selectedHeaderNote;
			$scope.dt_standard_value_to_add = selectedHeaderLimit;
			$scope.showHeaderNoteAutoSearchList = false;
		} else if (formType == 'edit') {
			$scope.updateOrder.header_note = selectedHeaderNote;
			$scope.dt_standard_value_to_edit = selectedHeaderLimit;
			$scope.showHeaderNoteAutoSearchList = false;
		}
	};
	//*****************/AutoSearch HeaderNote Matches***************************

	//*****************AutoSearch Stability Note Matches***************************
	$scope.showStabilityNoteAutoSearchList = false;
	$scope.getAutoSearchStabilityNoteMatches = function (searchText) {
		$http({
			method: 'GET',
			url: BASE_URL + 'sales/orders/get-stability-note-list/' + searchText
		}).success(function (result) {
			$scope.stabilityNotesList = result.itemsList;
			if ($scope.stabilityNotesList.length > 0) {
				$scope.showStabilityNoteAutoSearchList = true;
			} else {
				$scope.showStabilityNoteAutoSearchList = false;
			}
			$scope.clearConsole();
		});
		return $scope.stabilityNotesList;
	};

	//set parameter value when user selecet from auto search list
	$scope.funSetSelectedStabilityNote = function (selectedStabilityNote, formType) {
		if (formType == 'add') {
			$scope.orderProductTest.stability_note = selectedStabilityNote;
			$scope.showStabilityNoteAutoSearchList = false;
		} else if (formType == 'edit') {
			$scope.updateOrder.stability_note = selectedStabilityNote;
			$scope.showStabilityNoteAutoSearchList = false;
		}
	};
	//*****************/AutoSearch Stability Note Matches***************************

	//**********Getting all Order Dates*********************************************
	$scope.funGetOrdersDataList = function (divisionId) {
		$scope.loaderShow();
		$scope.divisionID = divisionId;
		$http({
			url: BASE_URL + "orders/get_order_dates_list/" + divisionId,
			method: "GET",
		}).success(function (result, status, headers, config) {
			$scope.orderYearList = result.returnData.orderYearList;
			$scope.orderDateList = result.returnData.orderDateList;
			$scope.orderCurrentYear = result.returnData.currentYear;
			$scope.orderCurrentDate = result.returnData.currentDate;
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//**********/Getting all Order Dates*********************************************

	//**********function Delete Test Parameter ************************************
	$scope.funDeleteTestParameterRow = function (order_id, analysis_id) {
		$scope.loaderShow();
		$scope.newOrderActive = '';

		$http({
			url: BASE_URL + "orders/delete_order_parameter/" + order_id + '/' + analysis_id,
			method: "GET",
		}).success(function (data, status, headers, config) {
			if (data.error == 1) {
				$scope.funEditOrder(order_id);
				$scope.successMsgShow(data.message);
			} else {
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
	};
	//********** /function Delete Test Parameter ************************************

	//**********confirm box******************************************************
	$scope.funConfirmDeleteParameter = function (order_id, analysis_id) {
		$ngConfirm({
			title: false,
			content: defaultDeleteMsg, //Defined in message.js and included in head
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme: 'bootstrap',
			columnClass: 'col-sm-5 col-md-offset-3',
			buttons: {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funDeleteTestParameterRow(order_id, analysis_id);
					}
				},
				cancel: {
					text: 'cancel',
					btnClass: 'btn-default ng-confirm-closeIcon'
				}
			}
		});
	};

	/******  confirm box when deleting order parameter from order edit.  **********/
	$scope.funConfirm = function (message) {
		$ngConfirm({
			title: false,
			content: message, //Defined in message.js and included in head
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme: 'bootstrap',
			columnClass: 'col-sm-5 col-md-offset-3',
			buttons: {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
				},
				cancel: {
					text: 'cancel',
					btnClass: 'btn-default ng-confirm-closeIcon'
				}
			}
		});
	};

	/******************************** Edit Order Parameter functions***********************/
	//********** Call to parameter popup *******************************************
	$scope.funAddMoreParameters = function (product_test_id, orderId) {
		allPopupSelectedParametersArray = [];
		$scope.funEditTestProductStandardParamentersList(product_test_id, orderId);

	};
	/******************** Popup parameters list********************/
	$scope.testProductParamentersList = [];
	$scope.funEditTestProductStandardParamentersList = function (testId, orderId) {
		if (testId) {
			$scope.ProductTestId = testId;
			$scope.orderID = orderId;
			$scope.loaderMainShow();
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/edit-get-product-test-parameters-list/' + testId + '/' + orderId,
			}).success(function (result) {
				$scope.testProductParamentersList = result.productTestParametersList;
				$('#selectedAll').prop('checked', true);
				angular.element('#edit_header_note').prop('checked', false);
				angular.element('#edit_real_time_stability').prop('checked', false);
				$('#editProductParametersPopup').modal('show');
				$scope.loaderMainHide();
			});
		}
	};
	/**************************** Show selected parameters on edit form ****************************/

	//**********************fun Edit Get Test Product Standard Paramenters***************************
	$scope.funSetEditInvoicingType = function (customer_id, invoicing_type_id) {
		$scope.globalCustomerID = customer_id;
		$scope.customerInvoicingTypeID = invoicing_type_id;
		if (invoicing_type_id && invoicing_type_id == '4') {
			$scope.funEditGetTestProductStandardParamenters($scope.sampleID, $scope.globalProductCategoryID, $scope.customerInvoicingTypeID, $scope.globalCustomerID);
		}
	};
	//**********************fun Edit Get Test Product Standard Paramenters***************************	

	//**********************fun Edit Get Test Product Standard Paramenters***************************
	$scope.funEditGetTestProductStandardParamenters = function (global_sample_id, global_product_category_id, global_invoicing_type_id, global_customer_id) {
		$scope.loaderMainShow();
		$http.post(BASE_URL + 'orders/edit-get-product-test-parameters', {
			data: { formData: 'sample_id=' + global_sample_id + '&invoicing_type_id=' + global_invoicing_type_id + '&customer_id=' + global_customer_id + '&' + $(testParametersForm).serialize() },
		}).success(function (result, status, headers, config) {
			$scope.testProductParamenters = result.productTestParametersList;
			$timeout(function () {
				$scope.getClaimCalculationOnEdit(result.productTestParametersList);
			}, 500);
			$scope.istableTrTdVisibleID = global_product_category_id == 2 && global_invoicing_type_id == 4 ? 1 : 0;
			$scope.tableTrTdColspanLevelI = global_product_category_id == 2 && global_invoicing_type_id == 4 ? 14 : 12;
			$scope.tableTrTdColspanLevelII = $scope.tableTrTdColspanLevelI - 1;
			$scope.realTimeStability = result.order_stability;
			$('#editProductParametersPopup').modal('hide');
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '400') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********************/fun Edit Get Test Product Standard Paramenters***************************

	//********** 29 nov alternative Test Parameter Popup**************************************
	$scope.funEditAlternativeTestParameterPopup = function (product_test_dtl_id) {
		if (angular.isDefined(product_test_dtl_id)) {
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/get-alter-product-test-parameters/' + product_test_dtl_id
			}).success(function (result) {
				$scope.alterTestProductStandardParamenters = result.alternativeTestProParamsList;
				$scope.IsViewAlternativeHidden = false;
				$("#viewEditAlternativeModal").modal("show");
			});
		}
	};

	/************************ remove newly added parameter from edit form*****************/
	$scope.funRemoveTestParameterRow = function (product_test_dtl_id) {
		$("#alternative_paramter" + product_test_dtl_id).remove();
		//$scope.testProductParamenters = [];
	};

	//*****************parameter checkbox count (parameter popup function)***************************************
	$scope.funCheckParameterCheckedOrNotValuesOnEdit = function (dltId) {
		var paraStatus = angular.element('#checkOneParameterOnEdit_' + dltId).prop('checked');
		if (paraStatus) {
			$scope.allPopupSelectedParametersArray.push(dltId);
		} else {
			angular.element('#selectedAllOnEdit').prop('checked', false);
			$scope.allPopupSelectedParametersArray.pop(dltId);
		}
	};

	//*****************/ header note  checkbox***************************************
	$scope.funHeaderNoteCheckOnEdit = function () {
		if (angular.element('#edit_header_note').prop('checked')) {
			$scope.headerNoteStatus = true;
		} else {
			$scope.headerNoteStatus = false;
		}
	};

	$scope.funRealTimeStabilityStatusCheckOnEdit = function () {
		if (angular.element('#edit_real_time_stability').prop('checked')) {
			$scope.realTimeStabilityStatus = true;
		} else {
			$scope.realTimeStabilityStatus = false;
		}
	};

	$scope.funAddShowHideInvoicingNeeded = function () {
		var invocingAddReportingChecked = angular.element('#invoicing_reporting_add_id').prop('checked');
		if (invocingAddReportingChecked) {
			$scope.isInvoicingNeededAddFlag = true;
		} else {
			$scope.isInvoicingNeededAddFlag = false;
		}
	};

	$scope.funEditShowHideInvoicingNeeded = function () {
		var invocingEditReportingChecked = angular.element('#invoicing_reporting_edit_id').prop('checked');
		if (invocingEditReportingChecked) {
			$scope.isInvoicingNeededEditFlag = true;
		} else {
			$scope.isInvoicingNeededEditFlag = false;
			$scope.updateOrder.reporting_to = '';
			$scope.updateOrder.invoicing_to = '';
		}
	};
	//*****************/display Invoicing Needed*******************************

	/********* reporting to fucntions*****/
	$scope.funShowReportingStateCityTreeViewPopup = function (currentModule) {
		$('#stateCityTreeViewPopup').modal('show');
		$scope.currentModule = currentModule;
	};

	//*******************filter state/city from tree view****************
	$scope.funGetSelectedStateId = function (selectedNode) {
		$scope.funGetReportingCustomerOnStateChange(selectedNode.state_id, 1);
		$('#stateCityTreeViewPopup').modal('hide');
		$scope.currentModule = 17;
		$('#editStateCityTreeViewPopup').modal('hide');
	};

	//*****************city dropdown on state change*******************************
	$scope.funGetReportingCustomerOnStateChange = function (state_id, type) {
		if (state_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'master/invoicing/customer-wise-product-rates/get_customer_list/' + state_id
			}).success(function (result) {
				if (type == '1') {
					$scope.customerListReportingData = result.customerListData;
				} else if (type == '2') {
					$scope.customerListInvoicingData = result.customerListData;
				}
				$scope.clearConsole();
			});
		}
	};
	/********* /reporting to fucntions*****/

	//**********invoicing functions***************************************************/
	$scope.funShowInvoicingStateCityTreeViewPopup = function (currentModule) {
		$scope.currentModule = currentModule;
		$('#stateCityTreeViewPopup').modal('show');
	};

	$scope.funGetSelectedInvoicingStateId = function (selectedNode) {
		$scope.funGetCustomerInvoicingOnStateChange(selectedNode.state_id);
		$('#stateCityTreeViewPopup').modal('hide');
		$('#editStateCityTreeViewPopup').modal('hide');
		$scope.currentModule = 18;
	};

	//*****************city dropdown on state change*******************************/
	$scope.funGetCustomerInvoicingOnStateChange = function (state_id) {
		if (state_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'master/invoicing/customer-wise-product-rates/get_customer_list/' + state_id
			}).success(function (result) {
				$scope.customerData = result.customerListData;
				$scope.clearConsole();
			});
		}
	};
	//**********/invoicing functions***************************************************/
	$scope.funShowEditReportingStateCityTreeViewPopup = function (currentModule) {
		$('#editStateCityTreeViewPopup').modal('show');
		$scope.currentModule = currentModule;
	};

	$scope.funShowEditInvoicingStateCityTreeViewPopup = function (currentModule) {
		$scope.currentModule = currentModule;
		$('#editStateCityTreeViewPopup').modal('show');
	};

	//**********confirm box******************************************************
	$scope.funConfirmMessage = function (order_id, message, type) {
		$ngConfirm({
			title: false,
			content: message,
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme: 'bootstrap',
			columnClass: 'col-sm-5 col-md-offset-3',
			buttons: {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						if (type == "unhold") {
							$scope.funUnHoldOrder(order_id, type);
						}
					}
				},
				cancel: {
					text: 'cancel',
					btnClass: 'btn-default ng-confirm-closeIcon',
				}
			}
		});
	};
	//********** /confirm box****************************************************
	$scope.funUnHoldOrder = function (order_id, type) {
		if (type == 'removeHold') { $scope.loaderShow(); } else { $scope.loaderMainShow(); }
		$http({
			url: BASE_URL + "sales/un-hold-order/" + order_id,
			method: "POST",
		}).success(function (result, status, headers, config) {
			if (type == 'removeHold') {
				$scope.loaderHide();
			} else {
				$scope.funGetOrdersHttpRequest();
				$scope.loaderMainHide();
				$scope.successMsgShow(result.message);
			}
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};

	/********* show PO type*****************************************/
	$scope.funAddShowPODetailType = function (billingTypeId) {
		if (billingTypeId == '5') {
			$scope.showAddPOType = true;
			$scope.orderSample.po_type = true;
		} else {
			var addPoTypeCheckedOrNot = angular.element('#add_po_type').prop('checked');
			if (addPoTypeCheckedOrNot) {
				$scope.showAddPOType = true;
			} else {
				$scope.showAddPOType = false;
				$scope.orderSample.po_no = '';
				$scope.orderSample.po_date = '';
			}
		}

	};
	$scope.funEditShowPODetailType = function (billingTypeId) {
		if (billingTypeId == '5') {
			$scope.updateOrder.po_type = true;
			$scope.showEditPOType = true;
		} else {
			var editPoTypeCheckedOrNot = angular.element('#edit_po_type').prop('checked');
			if (editPoTypeCheckedOrNot) {
				$scope.showEditPOType = true;
			} else {
				$scope.showEditPOType = false;
				$scope.updateOrder.po_no = '';
				$scope.updateOrder.po_date = '';
			}
		}
	};

	/********* show sample type***/
	$scope.funAddShowSampleType = function () {
		var addSampleTypeCheckedOrNot = angular.element('#add_sample_type').prop('checked');
		if (addSampleTypeCheckedOrNot) {
			$scope.showAddSampleType = true;
		} else {
			$scope.showAddSampleType = false;
			$scope.orderSample.inter_laboratory = false;
			$scope.orderSample.compensatory = false;
		}
	};
	/********* show sample type***/
	$scope.funShowEditSampleType = function () {
		var editSampleTypeCheckedOrNot = angular.element('#edit_sample_type').prop('checked');
		if (editSampleTypeCheckedOrNot) {
			$scope.showEditSampleType = true;
		} else {
			$scope.showEditSampleType = false;
		}
	};

	//*****************************Save AS New Order Functiobality************************************************************************
	//************************************************************************************************************************************

	//**********Editing of Order*************************************************
	$scope.funOpenSaveAsOrder = function (orderId) {

		$scope.orderSaveAsSamplePriorityId = null;
		$scope.customerListData = [];
		$scope.customerData = [];
		$scope.saveAsOrder = {};
		$scope.saveAsOrder.product_test_id = {};
		$scope.order_id = orderId;
		$scope.saveAsOrder.selectedTestId = {};
		$scope.IsNewOrder = true;
		$scope.IsViewList = true;
		$scope.IsViewLogList = true;
		$scope.IsViewHidden = true;
		$scope.IsUpdateOrder = true;
		$scope.IsSaveAsOrder = false;
		$scope.updateOrder.po_type = false;
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "orders/save_order/" + orderId,
			method: "GET",
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {

				$scope.saveAsOrder = result.orderList;
				$scope.saveAsOrderParameters = result.orderParameterList;

				$scope.istableTrTdVisibleID = $scope.saveAsOrderParameters.product_category_id == 2 && $scope.saveAsOrderParameters.invoicing_type_id == 4 ? 1 : 0;
				$scope.tableTrTdColspanLevelI = $scope.saveAsOrderParameters.product_category_id == 2 && $scope.saveAsOrderParameters.invoicing_type_id == 4 ? 14 : 12;
				$scope.tableTrTdColspanLevelII = $scope.tableTrTdColspanLevelI - 1;

				$scope.sampleWithPlaceName = result.orderList.sample_no + '/' + result.orderList.customer_name + '/' + result.orderList.city_name;
				$scope.testSampleReceviedList = [{ id: result.orderList.sample_id, name: result.orderList.sample_no + '/' + result.orderList.customer_name + '/' + result.orderList.city_name }];
				$scope.funSaveAsGetCustomerAttachedSampleDetail(result.orderList.sample_id);
				$scope.saveAsOrder.sale_executive = { selectedOption: { id: result.orderList.sale_executive } };

				$scope.saveAsOrder.selectedProductId = result.orderList.product_id;
				$scope.saveAsOrder.selectedTestId = result.orderList.product_test_id;
				$scope.testProductList = [{ product_id: result.orderList.product_id, product_name: result.orderList.product_name }];
				$scope.saveAsOrder.product_id = { selectedOption: { product_id: result.orderList.product_id } };
				$scope.productTestListing = [{ test_id: result.orderList.product_test_id, test_code: result.orderList.test_code }];
				$scope.saveAsOrder.test_id = { selectedOption: { test_id: result.orderList.product_test_id } };
				$scope.saveAsOrder.pro_cat_id = $scope.saveAsOrderParameters[0].productCatID;

				$scope.funGetTestingProducts($scope.saveAsOrder.pro_cat_id);
				$scope.funGetTestProductStandard($scope.saveAsOrder.selectedProductId);

				var order_date_formated = result.orderList.order_date.split(" ");
				$scope.saveAsOrder.order_date = order_date_formated[0] ? order_date_formated[0] : '';

				$scope.funSaveAsSetSurchargeValue(result.orderList.sample_priority_id);
				$scope.funSaveAsSubmissionTypeValue(result.orderList.submission_type);

				//****************stability note Condition*********************
				if ($scope.saveAsOrder.stability_note) {
					$scope.realTimeStabilityStatus = true;
				} else {
					$scope.realTimeStabilityStatus = false;
				}
				//****************/stability note Condition*********************

				//****************Header note Condition*********************
				if ($scope.saveAsOrder.header_note) {
					$scope.headerNoteStatus = true;
				} else {
					$scope.headerNoteStatus = false;
				}
				//****************/Header note Condition*********************

				//****************Hold Condition*****************************
				if (result.orderList.hold_reason) {
					$scope.orderHoldSaveAsFlag = true;
					$scope.isOrderHoldNeeded = true;
				} else {
					$scope.orderHoldSaveAsFlag = false;
					$scope.isOrderHoldNeeded = false;
				}
				if ($scope.saveAsOrder.canHoldUnholdOrder > 0) {
					$scope.canHoldUnholdOrder = true;
				} else {
					$scope.canHoldUnholdOrder = false;
				}
				$scope.funOrderOnHoldOrNotOnSaveAs();
				//****************/Hold Condition*****************************

				//****************PO Condition*********************
				if (result.orderList.po_no || result.orderList.po_date) {
					$scope.showSaveAsPOType = true;
					$scope.updateOrder.po_type = true;
				} else {
					$scope.showSaveAsPOType = false;
					$scope.updateOrder.po_type = false;
				}
				if (result.orderList.status > 8 && result.orderList.status != 12) {
					$scope.canChangePoTypeOrder = true;
				} else {
					$scope.canChangePoTypeOrder = false;
				}
				//****************/PO Condition*********************

				//****************Sample Type Condition************
				if ($scope.saveAsOrder.order_sample_type) {
					$scope.showSaveAsSampleType = true;
				} else {
					$scope.showSaveAsSampleType = false;
				}
				if (result.orderList.status > 1) {
					$scope.canChangeSampleTypeOrder = true;
				} else {
					$scope.canChangeSampleTypeOrder = false;
				}
				//****************Sample Type Condition************

				//********ReportingTo/InvoicingTo Condition***********
				if (result.orderList.reporting_to || result.orderList.invoicing_to) {
					$scope.isInvoicingSaveAsCheckBoxFlag = true;
					$scope.isInvoicingNeededSaveAsFlag = true;
				} else {
					$scope.isInvoicingSaveAsCheckBoxFlag = false;
					$scope.isInvoicingNeededSaveAsFlag = false;
				}
				if (result.orderList.status > 8 && result.orderList.status != 12) {
					$scope.canChangeInvoicingTo = true;
				} else {
					$scope.canChangeInvoicingTo = false;
				}
				//*******/ReportingTo/InvoicingTo Condition***********

				if (result.orderList.reportingCustomerId) {
					$scope.customerListData = [{ id: result.orderList.reportingCustomerId, name: result.orderList.reportingCustomerName }];
					$scope.saveAsOrder.reporting_to = { selectedOption: { id: result.orderList.reporting_to } };
				}
				if (result.orderList.invoicingCustomerId) {
					$scope.customerData = [{ id: result.orderList.invoicingCustomerId, name: result.orderList.invoicingCustomerName }];
					$scope.saveAsOrder.invoicing_to = { selectedOption: { id: result.orderList.invoicing_to } };
				}
				$scope.saveAsOrder.sample_id = { selectedOption: { id: result.orderList.sample_id } };
				$scope.saveAsOrder.division_id = { selectedOption: { id: result.orderList.division_id } };
				$scope.orderSaveAsSamplePriorityId = result.orderList.sample_priority_id;
				$scope.saveAsOrder.sample_priority_id = { selectedOption: { sample_priority_id: result.orderList.sample_priority_id } };
				$scope.saveAsOrder.is_sealed = { selectedOption: { id: result.orderList.is_sealed } };
				$scope.saveAsOrder.is_signed = { selectedOption: { id: result.orderList.is_signed } };
				$scope.saveAsOrder.submission_type = { selectedOption: { id: result.orderList.submission_type } };
				$scope.saveAsOrder.invoicing_type_id = { id: result.orderList.invoicing_type_id };
				$scope.saveAsOrder.order_sample_type = result.orderList.order_sample_type;
				$scope.saveAsOrderParameters.test_parameter_id = {};
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//**********/fun edit of Order*************************************************

	$scope.funSaveAsCustomerAttachedDetail = function (customer_id) {

		$scope.saveAsOrder.customer_city_name = null;
		$scope.saveAsOrder.sale_executive = null;
		$scope.saveAsOrder.discount_type_name = null;
		$scope.saveAsOrder.discount_type_id = null;
		$scope.saveAsOrder.discount_value = null;
		$scope.saveAsOrder.suppliedBy = true;
		$scope.saveAsOrder.manufacturedBy = true;

		if (customer_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/get_customer_attached_detail/' + customer_id + '/' + $scope.sampleID
			}).success(function (result) {
				if (result.error == 1) {
					$scope.salesExecutiveList = result.salesExecutiveList;
					$scope.invoicingTypeList = [{ id: result.customerAttachedList.invoicing_type_id, name: result.customerAttachedList.invoicing_type }];
					$scope.saveAsOrder.customer_city_name = result.customerAttachedList.city_name;
					$scope.saveAsOrder.customer_city = result.customerAttachedList.city_id;
					$scope.saveAsOrder.mfg_lic_no = result.customerAttachedList.mfg_lic_no;
					$scope.saveAsOrder.sale_executive = { selectedOption: { id: result.customerAttachedList.sale_executive } };
					$scope.saveAsOrder.discount_type_name = result.customerAttachedList.discount_type_name;
					$scope.saveAsOrder.discount_type_id = result.customerAttachedList.discount_type_id;
					$scope.saveAsOrder.discount_value = result.customerAttachedList.discount_value;
					$scope.saveAsOrder.billing_type_id = result.customerAttachedList.billing_type_id;
					$scope.saveAsOrder.invoicing_type_id = { id: result.customerAttachedList.invoicing_type_id };
				}
				$scope.clearConsole();
			});
		}
	};

	//*****************display customer list location and Mfg Lic Number********
	$scope.funSaveAsGetCustomerAttachedSampleDetail = function (sampleId) {

		$scope.saveAsOrder.customer_id = null;
		$scope.saveAsOrder.customer_city_name = null;
		$scope.saveAsOrder.discount_type_name = null;
		$scope.saveAsOrder.discount_type_id = null;
		$scope.saveAsOrder.discount_value = null;
		$scope.saveAsOrder.suppliedBy = true;
		$scope.saveAsOrder.manufacturedBy = true;

		if (customer_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'sales/orders/get-customer-attached-sample-detail/' + sampleId
			}).success(function (result) {
				if (result.error == 1) {
					$scope.sampleID = sampleId;
					$scope.customerNameList = [{ customer_id: result.customerAttachedSampleList.customer_id, customer_name: result.customerAttachedSampleList.customer_name }];
					$scope.salesExecutiveList = result.salesExecutiveList;
					$scope.invoicingTypeList = [{ id: result.customerAttachedSampleList.invoicing_type_id, name: result.customerAttachedSampleList.invoicing_type }];
					$scope.saveAsOrder.customer_name = result.customerAttachedSampleList.customer_name;
					$scope.saveAsOrder.customer_id = { selectedOption: { customer_id: result.customerAttachedSampleList.customer_id } };
					$scope.saveAsOrder.customer_city_name = result.customerAttachedSampleList.city_name;
					$scope.saveAsOrder.discount_type_name = result.customerAttachedSampleList.discount_type_name;
					$scope.saveAsOrder.discount_type_id = result.customerAttachedSampleList.discount_type_id;
					$scope.saveAsOrder.discount_value = result.customerAttachedSampleList.discount_value;
					$scope.saveAsOrder.billing_type_id = result.customerAttachedSampleList.billing_type_id;
				}
				$scope.clearConsole();
			});
		}
	};
	//*****************/display customer list location and Mfg Lic Number*************************

	//*****************display customer name on check of checkbox*********************************
	$scope.checkSaveAsCustomer = function (checked, customerName, type) {
		if (type == "supplied_by") {
			if (checked == false) {
				$scope.saveAsOrder.supplied_by = '';
			} else {
				$scope.saveAsOrder.supplied_by = customerName;
			}
		} else if (type == "manufactured_by") {
			if (checked == false) {
				$scope.saveAsOrder.manufactured_by = '';
			} else {
				$scope.saveAsOrder.manufactured_by = customerName;
			}
		}
	}
	//*****************/display customer name on check of checkbox***************************************

	//*****************display division dropdown*********************************************************
	$scope.isSaveAsSetSurchargeValueFlag = false;
	$scope.funSaveAsSetSurchargeValue = function (samplePriorityId) {
		$scope.isSaveAsSetSurchargeValueFlag = false;
		if (samplePriorityId == 3) {
			$scope.isSaveAsSetSurchargeValueFlag = true;
		} else {
			$scope.saveAsOrder.surcharge_value = '';
		}
	};
	//*****************/display division dropdown********************************************************

	//*****************display division dropdown*********************************************************
	$scope.saveAsAdvanceDetailsDisplay = false;
	$scope.funSaveAsSubmissionTypeValue = function (submissionTypeId) {
		$scope.saveAsAdvanceDetailsDisplay = false;
		if (submissionTypeId == 1) {
			$scope.saveAsAdvanceDetailsDisplay = true;
		} else {
			$scope.saveAsOrder.advance_details = '';
		}
	};
	//*****************/display division dropdown*********************************************************

	//*******************Order on hold *******************************************************************
	$scope.funOrderOnHoldOrNotOnSaveAs = function (orderId = null) {
		var orderHoldnSaveAsFlagNeeded = angular.element('#saveas_hold_type').prop('checked');
		if (orderHoldnSaveAsFlagNeeded) {
			$scope.orderHoldnSaveAsFlag = true;
			$scope.cannSaveAsChangePoTypeOrder = true;
			$scope.cannSaveAsChangeSampleTypeOrder = true;
			$scope.cannSaveAsChangeInvoicingTo = true;
		} else {
			$scope.orderHoldnSaveAsFlag = false;
			$scope.cannSaveAsChangePoTypeOrder = false;
			$scope.cannSaveAsChangeSampleTypeOrder = false;
			$scope.cannSaveAsChangeInvoicingTo = false;
			if (orderId) { $scope.funUnHoldOrder(orderId, 'removeHold'); }
		}
	};
	//*******************/Order on hold *******************************************************************

	//*******************Order on hold *******************************************************************
	$scope.funSaveAsShowPODetailType = function () {
		var saveasPoTypeCheckedOrNot = angular.element('#saveas_po_type').prop('checked');
		if (saveasPoTypeCheckedOrNot) {
			$scope.showSaveAsPOType = true;
		} else {
			$scope.showSaveAsPOType = false;
			$scope.saveAsOrder.po_no = '';
			$scope.saveAsOrder.po_date = '';
		}
	};
	//*******************/Order on hold *******************************************************************

	//********* show sample type********************************************************************
	$scope.funShowSaveAsSampleType = function () {
		var saveasSampleTypeCheckedOrNot = angular.element('#saveas_sample_type').prop('checked');
		if (saveasSampleTypeCheckedOrNot) {
			$scope.showSaveAsSampleType = true;
		} else {
			$scope.showSaveAsSampleType = false;
		}
	};
	//*********/show sample type********************************************************************

	//*****************display Invoicing Needed*******************************
	$scope.funSaveAsShowHideInvoicingNeeded = function () {
		var invocingSaveAsReportingChecked = angular.element('#invoicing_reporting_saveas_id').prop('checked');
		if (invocingSaveAsReportingChecked) {
			$scope.isInvoicingNeededSaveAsFlag = true;
		} else {
			$scope.isInvoicingNeededSaveAsFlag = false;
			$scope.saveAsOrder.reporting_to = '';
			$scope.saveAsOrder.invoicing_to = '';
		}
	};
	//*****************/display Invoicing Needed******************************************************

	//**********Invoicing functions********************************************************************
	$scope.funShowSaveAsReportingStateCityTreeViewPopup = function (currentModule) {
		$('#saveAsStateCityTreeViewPopup').modal('show');
		$scope.currentModule = currentModule;
	};

	$scope.funShowSaveAsInvoicingStateCityTreeViewPopup = function (currentModule) {
		$scope.currentModule = currentModule;
		$('#saveAsStateCityTreeViewPopup').modal('show');
	};
	//**********Invoicing functions********************************************************************

	//********** Call to parameter popup ************************************************************
	$scope.funAddMoreSaveAsParameters = function (product_test_id, orderId) {
		allPopupSelectedParametersArray = [];
		$scope.funSaveAsTestParamentersList(product_test_id, orderId);
	};

	$scope.testProductParamentersList = [];
	$scope.funSaveAsTestParamentersList = function (testId, orderId) {
		if (testId) {
			$scope.ProductTestId = testId;
			$scope.loaderMainShow();
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/edit-get-product-test-parameters-list/' + testId + '/' + orderId,
			}).success(function (result) {
				$scope.testProductParamentersList = result.productTestParametersList;
				$('#selectedAllOnSaveAs').prop('checked', true);
				angular.element('#saveas_header_note').prop('checked', false);
				angular.element('#saveas_real_time_stability').prop('checked', false);
				$('#saveAsProductParametersPopup').modal('show');
				$scope.loaderMainHide();
			});
		}
	};
	//**************************** Show selected parameters on edit form ********************************

	//*****************Header note & Stability Note*******************************************************
	$scope.funHeaderNoteCheckOnSaveAs = function () {
		if (angular.element('#saveas_header_note').prop('checked')) {
			$scope.saveAsHeaderNoteStatus = true;
		} else {
			$scope.saveAsHeaderNoteStatus = false;
		}
	};
	$scope.funRealTimeStabilityStatusCheckOnSaveAs = function () {
		if (angular.element('#saveas_real_time_stability').prop('checked')) {
			$scope.saveAsRealTimeStabilityStatus = true;
		} else {
			$scope.saveAsRealTimeStabilityStatus = false;
		}
	};
	//****************/Header note & Stability Note*******************************************************

	//*********************select all functionality************************************
	$scope.toggleAllOnSaveAs = function () {
		$scope.allPopupSelectedParametersArray = [];
		var checkAllStatus = angular.element('#selectedAllOnSaveAs').prop('checked');
		if (checkAllStatus) {
			angular.element('.parametersCheckBoxOnSaveAs').prop('checked', true);
			angular.element(".parametersCheckBoxOnSaveAs:checked").each(function () {
				$scope.allPopupSelectedParametersArray.push(this.value);
			});
		} else {
			angular.element('.parametersCheckBoxOnSaveAs').prop('checked', false);
		}
	};
	//*********************select all functionality************************************

	//********************function Save As Get Test Product Standard Paramenters************************
	$scope.funSaveAsGetTestProductStandardParamenters = function () {
		$scope.loaderMainShow();
		$http.post(BASE_URL + 'orders/edit-get-product-test-parameters', {
			data: { formData: $(testParametersSaveAsForm).serialize() },
		}).success(function (result, status, headers, config) {
			$scope.testProductParamentersSaveAs = result.productTestParametersList;
			$('#saveAsProductParametersPopup').modal('hide');
			$scope.clearConsole();
			$scope.loaderMainHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '400') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*******************/function Save As Get Test Product Standard Paramenters************************

	//*******************function Save As Alternative Test Paramenters**********************************
	$scope.funSaveAsAlternativeTestParameterPopup = function (product_test_dtl_id) {
		if (product_test_dtl_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/get-alter-product-test-parameters/' + product_test_dtl_id
			}).success(function (result) {
				$scope.saveAsAlterTestProductStandardParamenters = result.alternativeTestProParamsList;
				$scope.IsViewAlternativeHidden = true;
				$scope.IsViewSaveAsAlternativeHidden = false;
				$("#viewSaveAsAlternativeModal").modal("show");
			});
		}
	};
	//******************/function Save As Alternative Test Paramenters**********************************

	//******************function Save As Alternative Test Paramenters**********************************
	$scope.funGetSaveAsSearchParameters = function () {
		$scope.loaderShow();
		var http_para_string = { formData: 'keyword=' + $scope.searchParameter + '&' + 'test_id=' + $scope.ProductTestId + '&' + 'order_id=' + $scope.order_id };
		$http({
			url: BASE_URL + "sales/edit-order/search-parameters",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.testProductParamentersList = result.productTestParametersList;
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//******************/function Save As Alternative Test Paramenters**********************************

	//*****************display Test Product Standard dropdown**********************
	$scope.selectSaveAsAlternativeTestParameterRow = function (product_test_param_altern_method_id, product_test_dtl_id) {
		if (angular.isDefined(product_test_param_altern_method_id) && angular.isDefined(product_test_dtl_id)) {
			$http({
				method: 'GET',
				url: BASE_URL + 'orders/reselect_test_standard_parameters/' + product_test_param_altern_method_id,
			}).success(function (result) {
				angular.element("#test_parameter_name_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.test_parameter_name);
				angular.element("#equipment_name_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.equipment_name);
				angular.element("#method_name_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.method_name);
				angular.element("#standard_value_from_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.standard_value_from);
				angular.element("#standard_value_to_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.standard_value_to);
				if (result.alterSelectedTestProParamsList.time_taken_days) {
					angular.element("#time_taken_days_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.time_taken_days + ' Days');
				}
				if (result.alterSelectedTestProParamsList.time_taken_mins) {
					angular.element("#time_taken_mins_text" + product_test_dtl_id).html(result.alterSelectedTestProParamsList.time_taken_mins + ' Mins');
				}
				angular.element("#product_test_parameter" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.product_test_dtl_id);
				angular.element("#test_parameter_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.test_parameter_id);
				angular.element("#equipment_type_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.equipment_type_id);
				angular.element("#method_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.method_id);
				angular.element("#standard_value_from" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_from);
				angular.element("#standard_value_to" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_to);
				angular.element("#org_standard_value_from" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_from);
				angular.element("#org_standard_value_to" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.standard_value_to);
				angular.element("#time_taken_days" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.time_taken_days);
				angular.element("#time_taken_mins" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.time_taken_mins);
				angular.element("#cost_price" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.cost_price);
				angular.element("#selling_price" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.selling_price);
				angular.element("#test_param_alternative_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.product_test_param_altern_method_id);
				angular.element("#department_id" + product_test_dtl_id).val(result.alterSelectedTestProParamsList.department_id);
				$scope.funChangeTestParameterValueAccToClaim(product_test_dtl_id);
				$("#viewAlternativeModal").modal("hide");
				$scope.funChangeTestParameterValueAccToClaimUnit(product_test_dtl_id);
			});
		}
	};
	//*****************/display Test Product Standard dropdown*********************************************

	//*****************parameter checkbox count (parameter popup function)***************************************
	$scope.funCheckParameterCheckedOrNotValuesOnSaveAs = function (dltId) {
		var paraStatus = angular.element('#checkOneParameterOnSaveAs_' + dltId).prop('checked');
		if (paraStatus) {
			$scope.allPopupSelectedParametersArray.push(dltId);
		} else {
			angular.element('#selectedAllOnSaveAs').prop('checked', false);
			$scope.allPopupSelectedParametersArray.pop(dltId);
		}
	};
	//****************/parameter checkbox count (parameter popup function)***************************************

	//**********Adding of Order*************************************************
	$scope.funPlaceAndSaveAsOrder = function (divisionId, finalTypeSave, orderId) {

		if (!$scope.erpSaveAsOrderForm.$valid) return;
		if ($scope.newOrderflag) return;
		$scope.newOrderflag = true;
		$scope.loaderMainShow();

		var http_para_string = { formData: 'final_type_save=' + finalTypeSave + '&' + $(erpSaveAsOrderForm).serialize() };

		$http({
			url: BASE_URL + "orders/addOrder",
			method: "POST",
			headers: { 'Content-Type': 'application/json' },
			data: http_para_string
		}).success(function (result, status, headers, config) {
			$scope.newOrderflag = false;
			if (result.error == 1) {
				$scope.backButton();
				$scope.newOrderActive = result.data;
				$scope.funGetOrdersList(divisionId);
				$scope.funGetTestSampleRecevied();
				$scope.resetPopupForm();
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.newOrderflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/Adding of Order*************************************************

	//**********Adding of Order*************************************************
	$scope.funPlaceAndSaveAsMoreOrder = function (divisionId, finalTypeSave, orderId) {

		if (!$scope.erpSaveAsOrderForm.$valid) return;
		if ($scope.newOrderflag) return;
		$scope.newOrderflag = true;
		$scope.loaderMainShow();

		var http_para_string = { formData: 'final_type_save=' + finalTypeSave + '&' + $(erpSaveAsOrderForm).serialize() };

		$http({
			url: BASE_URL + "orders/addOrder",
			method: "POST",
			headers: { 'Content-Type': 'application/json' },
			data: http_para_string
		}).success(function (result, status, headers, config) {
			$scope.newOrderflag = false;
			if (result.error == 1) {
				$scope.orderSample = {};
				$scope.newOrderActive = result.data;
				$scope.headerNoteStatus = false;
				$scope.realTimeStabilityStatus = false;
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.newOrderflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/Adding of Order*************************************************

	//*****************display Test Product Standard dropdown* 28 nov*********************
	$scope.funChangeSaveAsTestParameterValueAccToClaim = function (product_test_dtl_id, type) {

		var claimValue = angular.element('#saveas_claim_value' + product_test_dtl_id).val();
		var standardValueFrom = angular.element('#saveas_org_standard_value_from' + product_test_dtl_id).val();
		var standardValueTo = angular.element('#saveas_org_standard_value_to' + product_test_dtl_id).val();

		if (claimValue != '' && standardValueFrom && standardValueTo) {

			var standardValueFromClaimed = standardValueToClaimed = '0.00';
			var standardValueFromClaimed = !isNaN(standardValueFrom) ? ((standardValueFrom * claimValue) / 100).toFixed(9) : standardValueFrom;
			var standardValueToClaimed = !isNaN(standardValueTo) ? ((standardValueTo * claimValue) / 100).toFixed(9) : standardValueTo;

			angular.element('#saveas_standard_value_from_text' + product_test_dtl_id).html(standardValueFromClaimed);
			angular.element('#saveas_standard_value_to_text' + product_test_dtl_id).html(standardValueToClaimed);
			angular.element('#saveas_standard_value_from' + product_test_dtl_id).val(standardValueFromClaimed);
			angular.element('#saveas_standard_value_to' + product_test_dtl_id).val(standardValueToClaimed);
		} else {
			angular.element('#saveas_standard_value_from_text' + product_test_dtl_id).html(standardValueFrom);
			angular.element('#saveas_standard_value_to_text' + product_test_dtl_id).html(standardValueTo);
			angular.element('#saveas_standard_value_from' + product_test_dtl_id).val(standardValueFrom);
			angular.element('#saveas_standard_value_to' + product_test_dtl_id).val(standardValueTo);
		}

		$scope.funChangeSaveAsTestParameterValueAccToClaimUnit(product_test_dtl_id);
	};
	//*****************display Test Product Standard dropdown* 28 nov*********************

	//*****************display Claim Value Unit**********************
	$scope.funChangeSaveAsTestParameterValueAccToClaimUnit = function (product_test_dtl_id) {

		var claimValueInput = angular.element('#saveas_claim_value' + product_test_dtl_id).val();
		var claimValueUnitInput = angular.element('#saveas_claim_value_unit' + product_test_dtl_id).val();
		var standardValueFromUnit = angular.element('#saveas_standard_value_from' + product_test_dtl_id).val();
		var standardValueToUnit = angular.element('#saveas_standard_value_to' + product_test_dtl_id).val();

		if (claimValueInput && claimValueUnitInput && isNaN(claimValueUnitInput)) {
			if (!isNaN(standardValueFromUnit)) {
				angular.element('#saveas_standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit + ' ' + claimValueUnitInput);
			} else {
				angular.element('#saveas_standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit);
			}
			if (!isNaN(standardValueToUnit)) {
				angular.element('#saveas_standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit + ' ' + claimValueUnitInput);
			} else {
				angular.element('#saveas_standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit);
			}
		} else {
			claimValueUnitInput = '';
			angular.element('#saveas_standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit);
			angular.element('#saveas_standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit);
		}
	};
	//*****************/display Claim Value Unit*********************

	//*****************************/Save AS New Order Functiobality*****************************************

	//**********Refreshing Invoicing Structure*************************************************
	$scope.funRefreshInvoicingStructure = function (sampleId) {
		if (sampleId) {
			var http_para_string = { formData: 'sample_id=' + sampleId };

			$http({
				url: BASE_URL + "sales/orders/refresh-invoicing-structure",
				method: "POST",
				data: http_para_string
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.invoicingTypeList = [{ id: result.currentInvoicingStructure.invoicing_type_id, name: result.currentInvoicingStructure.invoicing_type }];
					$scope.orderCustomer.invoicing_type_id = { id: result.currentInvoicingStructure.invoicing_type_id };
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (result, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			});
		}
	};
	//**********/Refreshing Invoicing Structure*************************************************

	//**********Checking of Order Amount*******************************************************
	$scope.funGetSearchSampleMatcheRate = function (sample_description, invoicing_type_id, sample_id, form_type) {
		if (sample_description != '' && (invoicing_type_id == '2' || invoicing_type_id == '3')) {

			if ($scope.newCheckPriceOrderflag) return;
			$scope.newCheckPriceOrderflag = true;
			$scope.hideAlertMsg();
			var http_para_string = { 'sample_description': sample_description, 'invoicing_type_id': invoicing_type_id, 'sample_id': sample_id };

			$http({
				url: BASE_URL + "orders/check-customer-wise-product-rate",
				method: "POST",
				data: http_para_string
			}).success(function (result, status, headers, config) {
				$scope.newCheckPriceOrderflag = false;
				if (result.error == 1) {
					if (form_type == 'add') {
						$scope.orderSample.selected_sample_id = result.sample_description_id;
						$scope.orderSample.sample_description = sample_description;
						$scope.showAutoSearchList = false;
					} else if (form_type == 'edit') {
						$scope.updateOrder.selected_sample_id = result.sample_description_id;
						$scope.updateOrder.sample_description = sample_description;
						$scope.showAutoSearchList = false;
					} else if (form_type == 'saveAs') {
						$scope.saveAsOrder.selected_sample_id = result.sample_description_id;
						$scope.saveAsOrder.sample_description = sample_description;
						$scope.showAutoSearchList = false;
					}
				} else {
					$scope.errorMsgShow(result.message);
					if (form_type == 'add') {
						$scope.orderSample.selected_sample_id = '';
						$scope.orderSample.sample_description = '';
						angular.element(add_sample_description).focus();
						$scope.showAutoSearchList = false;
					} else if (form_type == 'edit') {
						$scope.updateOrder.selected_sample_id = '';
						$scope.updateOrder.sample_description = '';
						angular.element(edit_sample_description).focus();
						$scope.showAutoSearchList = false;
					} else if (form_type == 'saveAs') {
						$scope.saveAsOrder.selected_sample_id = '';
						$scope.saveAsOrder.sample_description = '';
						angular.element(saveas_sample_description).focus();
						$scope.showAutoSearchList = false;
					}
				}
				$scope.clearConsole();
			}).error(function (result, status, headers, config) {
				$scope.newCheckPriceOrderflag = false;
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			});
		}
	};
	//**********Checking of Order Amount*************************************************

	//*****************/function Check Running Time Required Or Not*********************/
	$scope.funRequiredUnRequiredRunningTimeOrNot = function (product_test_dtl_id, type = 'add') {
		if (type == 'add') {
			var runningTimeValue = angular.element('#running_time_id_' + product_test_dtl_id).val();
			if (runningTimeValue && runningTimeValue == 5) {
				angular.element('#cwap_invoicing_required_' + product_test_dtl_id).val(0);
				angular.element('#no_of_injection_' + product_test_dtl_id).val('');
				angular.element('#no_of_injection_' + product_test_dtl_id).prop('readonly', true);
			} else {
				angular.element('#cwap_invoicing_required_' + product_test_dtl_id).val(1);
				angular.element('#no_of_injection_' + product_test_dtl_id).prop('readonly', false);
			}
		} else if (type == 'edit') {
			var newRunningTimeValue = angular.element('#new_running_time_id_' + product_test_dtl_id).val();
			if (newRunningTimeValue && newRunningTimeValue == 5) {
				angular.element('#new_cwap_invoicing_required_' + product_test_dtl_id).val(0);
				angular.element('#new_no_of_injection_' + product_test_dtl_id).val('');
				angular.element('#new_no_of_injection_' + product_test_dtl_id).prop('readonly', true);
			} else {
				angular.element('#new_cwap_invoicing_required_' + product_test_dtl_id).val(1);
				angular.element('#new_no_of_injection_' + product_test_dtl_id).prop('readonly', false);
			}
		}
	};
	//*****************/function Check Running Time Required Or Not*********************/

	//*****************display parent category dropdown code dropdown start****
	$scope.funFilterParameterPopupList = function (parameterName, className) {

		// Hide all table tbody rows
		angular.element('.' + className + ' tr').hide();

		// Searching text in columns and show match row
		angular.element('.' + className + ' tr td:contains("' + parameterName + '")').each(function () {
			angular.element(this).closest('tr').show();
		});

		// Case-insensitive searching (Note - remove the below script for Case sensitive search )
		angular.element.expr[":"].contains = angular.element.expr.createPseudo(function (arg) {
			return function (elem) {
				return angular.element(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
			};
		});
	};
	//*****************display parent category code dropdown end*******************************

	//************************** parameter search on add order******************************/
	var storeSearchVar;
	$scope.funSearchParameters = function (keyword, type) {
		storeSearchVar = keyword;
		$timeout(function () {
			if (type == 'add' && keyword == storeSearchVar) {
				$scope.searchParameter = keyword;
				$scope.funGetSearchParameters(); // http request
			} else if (type == 'edit' && keyword == storeSearchVar) {
				$scope.searchParameter = keyword;
				//$scope.funGetEditSearchParameters(); // http request
			} else if (type == 'saveAs') {
				$scope.searchParameter = keyword;
				$scope.funSaveAsEditSearchParameters(); // http request
			}
		}, 800);

		if ($scope.allPopupSelectedParametersArray != undefined && $scope.allPopupSelectedParametersArray.length > 0) {
			angular.forEach($scope.allPopupSelectedParametersArray, function (value, key) {
				if (type == 'add') {
					$('#checkOneParameter_' + value).prop('checked', true);
				} else if (type == 'edit') {
					$('#checkOneParameterOnEdit_' + value).prop('checked', true);
				} else if (type == 'saveAs') {
					$('#checkOneParameterOnSaveAs_' + value).prop('checked', true);
				}
			});
		}
	};

	$scope.funGetSearchParameters = function () {
		$scope.loaderShow();
		var http_para_string = { formData: 'keyword=' + $scope.searchParameter + '&' + 'test_id=' + $scope.ProductTestId };
		$http({
			url: BASE_URL + "sales/orders/search-parameters",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.testProductParamentersList = result.productTestParametersList;
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};

	$scope.funSaveAsEditSearchParameters = function () {
		$scope.loaderShow();
		var http_para_string = { formData: 'keyword=' + $scope.searchParameter + '&' + 'test_id=' + $scope.ProductTestId + '&' + 'order_id=' + $scope.order_id };
		$http({
			url: BASE_URL + "sales/edit-order/search-parameters",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.testProductParamentersList = result.productTestParametersList;
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//************************** /parameter search on add order******************************/

	//*************************Order Cancellation Section*************************************

	//*****************Cancellation Type List dropdown**********************
	$scope.cancellationTypeList = [];
	$scope.funGetCancellationTypeList = function () {
		$http({
			method: 'GET',
			url: BASE_URL + 'sales/orders/get-cancellation-type-list'
		}).success(function (result) {
			$scope.cancellationTypeList = result.cancellationTypeList;
			$scope.clearConsole();
		});
	};
	//*****************/Cancellation Type List dropdown*********************

	//**********Open Order Cancel Popup******************************
	$scope.funOpenOrderCancelPopup = function (contentId, order_no, order_id) {
		$scope.hideAlertMsg();
		$scope.funGetCancellationTypeList();
		$scope.cancelledOrder.order_no = order_no;
		$scope.cancelledOrder.order_id = order_id;
		$('#' + contentId).modal({ backdrop: 'static', keyboard: true, show: true });
	}
	//**********/Open Order Cancel Popup******************************

	//**********Cancellation of Order*************************************************
	$scope.funOrderCancellation = function () {
		$scope.loaderMainShow();
		var http_para_string = { formData: $(erpOrderCancellationInputPopupForm).serialize() };
		$http({
			url: BASE_URL + "orders/cancel-order-booking",
			method: "POST",
			data: http_para_string,
		}).success(function (data, status, headers, config) {
			if (data.error == 1) {
				$scope.cancelledOrder = {};
				$scope.funGetOrdersList($scope.divisionID);
				$scope.funCloseBootStrapModalPopup('orderCancellationInputPopupWindow');
				$scope.successMsgShow(data.message);
			} else {
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/Cancellation of Order************************************************

	//**********Cancellation order details******************************************************
	$scope.funGetCancelledOrderDetail = function (orderId) {
		var http_para_string = { formData: 'order_id=' + orderId };
		$http({
			url: BASE_URL + "orders/get-cancel-order-booking-detail",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.viewCancelledOrderDetail = result.cancelledOrderDetail;
				$scope.funOpenBootStrapModalPopup('orderCancellationOutputPopupWindow');
			} else {
				$scope.funCloseBootStrapModalPopup('orderCancellationOutputPopupWindow');
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/Cancellation order  details****************************************************

	//*************************/Order Cancellation Section*************************************

	//*****************display state code dropdown start*****************	
	$scope.countryCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'countries'
	}).success(function (result) {
		if (result) {
			$scope.countryCodeList = result;
			$scope.defaultCountryID = DEFAULTSTATE;
			if ($scope.defaultCountryID) {
				$scope.select_country_id = { id: $scope.defaultCountryID };
				$scope.funGetStateOnCountryChange($scope.defaultCountryID);
			}
		}
		$scope.clearConsole();
	});
	//*****************display state code dropdown end*****************

	//*****************state dropdown on country change**********************
	$scope.funGetStateOnCountryChange = function (country_id) {
		if (country_id) {
			$http({
				method: 'POST',
				url: BASE_URL + 'get_states_list/' + country_id
			}).success(function (result) {
				$scope.countryStatesList = result.countryStatesList;
				$scope.clearConsole();
			});
		}
	};
	//****************/state dropdown on country change******************

	/******  in add order module ***/
	$scope.countryStateTreeViewList = [];
	$scope.funGetCountryStateTreeViewList = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'get-country-state-tree-view'
		}).success(function (result) {
			if (result.countryStateTreeViewList) {
				$scope.countryStateTreeViewList = result.countryStateTreeViewList;
			}
			$scope.clearConsole();
		});
	}
	/********* reporting to fucntions*****/
	$scope.funShowReportingCountryStateViewPopup = function (currentModule) {
		$('#countryStateViewPopup').modal('show');
		$scope.selectedModuleID = currentModule;
	};

	/*******************filter state/city from tree view*****************/
	$scope.funGetSelectedReportingToStateId = function (state_id) {
		if (state_id) {
			$scope.funGetReportingInvoicingCustomerOnStateChange(state_id, 1);
			$('#countryStateViewPopup').modal('hide');
			$('#editCountryStateViewPopup').modal('hide');
		}
	};
	//**********invoicing functions***************************************************/
	$scope.funShowInvoicingCountryStateViewPopup = function (currentModule) {
		$scope.selectedModuleID = currentModule;
		$('#countryStateViewPopup').modal('show');
	};

	$scope.funGetSelectedInvoicingToStateId = function (state_id) {
		if (state_id) {
			$scope.funGetReportingInvoicingCustomerOnStateChange(state_id, 2);
			$('#countryStateViewPopup').modal('hide');
			$('#editCountryStateViewPopup').modal('hide');
		}
	};

	/***** edit order module*****/
	//**********/invoicing functions***************************************************/
	$scope.funShowEditReportingCountryStateViewPopup = function (currentModule) {
		$('#editCountryStateViewPopup').modal('show');
		$scope.selectedModuleID = currentModule;
	};

	$scope.funShowEditInvoicingCountryStateViewPopup = function (currentModule) {
		$scope.selectedModuleID = currentModule;
		$('#editCountryStateViewPopup').modal('show');
	};

	//*****************city dropdown on state change*******************************
	$scope.funGetReportingInvoicingCustomerOnStateChange = function (state_id, type) {
		if (state_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'master/invoicing/customer-wise-product-rates/get_customer_list/' + state_id
			}).success(function (result) {
				if (type == '1') {
					$scope.customerListData = result.customerListData;
				} else if (type == '2') {
					$scope.customerData = result.customerListData;
				}
				$scope.clearConsole();
			});
		}
	};
	//****************/city dropdown on state change*******************************

	//**********TRF & STP File Listing window*****************************************
	$scope.funOpenFileUploadWindow = function (orderId, orderNo) {

		$scope.IsViewUploadList = false;
		$scope.IsViewList = true;
		$scope.IsPreviewList = true;
		$scope.IsViewLogList = true;
		$scope.IsNewOrder = true;
		$scope.IsUpdateOrder = true;
		$scope.IsSaveAsOrder = true;
		$scope.IsViewHidden = true;
		$scope.oltdOrderID = orderId;
		$scope.oltdOrderNO = orderNo;
		var formdata = new FormData();
		$http({
			url: BASE_URL + 'sales/orders/get-trf-order-file-dtl',
			method: "POST",
			data: { order_id: orderId },
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.orderLinkedTrfDtlList = result.orderLinkedTrfDtlList;
				$scope.custSTPSampleDropDownList = result.custSTPSampleDropDownList;
				$scope.custSTPSampleSelectedList = result.custSTPSampleSelectedList;

				//Showing Selected STP Detail
				if (result.custSTPSampleSelectedList) {
					$scope.funGetCustomerStpNoList(result.custSTPSampleSelectedList.olsd_cstp_sample_name);
					$timeout(function () {
						$scope.orderFileStpSelection.olsd_cstp_file_name = { name: result.custSTPSampleSelectedList.olsd_cstp_sample_name };
						$scope.orderFileStpSelection.olsd_cstp_id = { id: result.custSTPSampleSelectedList.olsd_cstp_id };
					}, 100);
				}
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});

	};
	//**********/TRF & STP File Listing window*****************************************

	//**********/Uploading TRF File Detail***********************************
	$scope.getTheFiles = function ($files) {
		angular.forEach($files, function (value, key) {
			formdata.append('fileData', value);
		});
	};
	$scope.funUploadTrfFile = function (orderId, orderNo) {

		if (!orderId || !orderNo || !angular.element("#oltd_trf_no").val() || !angular.element("#oltd_file_name").val()) return;
		formdata.append('oltd_order_id', orderId);
		formdata.append('oltd_order_no', orderNo);
		formdata.append('oltd_trf_no', angular.element("#oltd_trf_no").val());
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + 'sales/orders/upload-trf-order-file',
			method: "POST",
			headers: { 'Content-Type': undefined },
			data: formdata,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				var formdata = new FormData();
				angular.element("#oltd_file_name").val('');
				$scope.orderFileTRFUploadSelection = {};
				$scope.erpOrderFileUploadForm.$setUntouched();
				$scope.erpOrderFileUploadForm.$setPristine();
				$scope.funOpenFileUploadWindow(orderId, orderNo);
				$scope.orderLinkedTrfDtlList = result.orderLinkedTrfDtlList;
				$scope.successMsgShow(result.message);
			} else {
				angular.element("#oltd_file_name").val('');
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});
	};
	//**********/Uploading TRF File Detail***********************************

	//****************Getting Customer TRF Number List***********************
	$scope.funGetCustomerStpNoList = function (trfSampleName) {

		$scope.custSTPNoAccToSampleNameList = [];
		if (!trfSampleName) return;

		$http({
			url: BASE_URL + 'sales/orders/get-customer-trf-accto-sample-name',
			method: "POST",
			data: { cstp_sample_name: window.btoa(trfSampleName) },
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.custSTPNoAccToSampleNameList = result.custSTPNoAccToSampleNameList;
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});
	};
	//****************/Getting Customer TRF Number List***********************

	//**********Saving of STP Detail of Order*********************************
	$scope.funSaveOrderStpDetail = function (orderId) {

		if (!orderId) return;
		$scope.loaderMainShow();
		var http_para_string = { formData: 'olsd_order_id=' + orderId + '&' + $(erpOrderSTPSelectionForm).serialize() };

		$http({
			url: BASE_URL + "sales/orders/saving-customer-stp-dtl",
			method: "POST",
			data: http_para_string,
		}).success(function (data, status, headers, config) {
			if (data.error == 1) {
				$scope.successMsgShow(data.message);
			} else {
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*********/Saving of STP Detail of Order*********************************

	//*********Adding and Removing of Dynamic Fields*****************************
	$scope.dynamicColumnNameList = [];
	$scope.funAddNewFieldColumn = function () {
		if (!$scope.dynamicColumnNameList.length) {
			var newColumnNo = 1;
		} else {
			var newColumnNo = $scope.dynamicColumnNameList.length + 1;
		}
		$scope.dynamicColumnNameList.push({ 'odf_id': 'odf_id' + newColumnNo, 'order_field_name': '', 'order_field_value': '' });
		console.log($scope.dynamicColumnNameList);
	};
	$scope.funRemoveNewFieldColumn = function (index, odf_id = null) {
		$scope.dynamicColumnNameList.splice(index, 1);
		if (odf_id != '' && !isNaN(odf_id) && angular.isNumber(odf_id)) {
			$http({
				url: BASE_URL + "sales/orders/remove-dynamic-field/" + odf_id,
			}).success(function (data, status, headers, config) {
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				$scope.clearConsole();
			});
		}
	};
	//*********/Adding and Removing of Dynamic Fields*****************************

	//*********Client Approval Process*****************************
	$scope.orderSample.ocad_approved_by = '';
	$scope.orderSample.ocad_date = '';
	$scope.orderSample.ocad_credit_period = '';
	$scope.orderSample.ocad_date_upto_amt = '';
	$scope.isClientApprovalNeededAddFlag = false;
	$scope.funAddShowHideClientApprovalNeeded = function () {
		$scope.isClientApprovalNeededAddFlag = angular.element('#client_approval_needed_add_id').prop('checked');
		if ($scope.isClientApprovalNeededAddFlag == false) {
			$scope.orderSample.ocad_approved_by = '';
			$scope.orderSample.ocad_date = '';
			$scope.orderSample.ocad_credit_period = '';
			$scope.orderSample.ocad_date_upto_amt = '';
			$scope.isClientApprovalNeededAddFlag = false;
		}
	};
	$scope.updateOrder.ocad_approved_by = '';
	$scope.updateOrder.ocad_date = '';
	$scope.updateOrder.ocad_credit_period = '';
	$scope.updateOrder.ocad_date_upto_amt = '';
	$scope.isClientApprovalNeededEditFlag = false;
	$scope.isRoleAdminForClientApprovalEdit = true;
	$scope.funEditShowHideClientApprovalNeeded = function (editClientApprovalData = {}) {
		if (editClientApprovalData) {
			$scope.updateOrder.client_approval_needed = true;
			$scope.updateOrder.ocad_approved_by = editClientApprovalData.ocad_approved_by;
			$scope.updateOrder.ocad_date = editClientApprovalData.ocad_date;
			$scope.updateOrder.ocad_credit_period = editClientApprovalData.ocad_credit_period;
			$scope.updateOrder.ocad_date_upto_amt = editClientApprovalData.ocad_date_upto_amt;
			$scope.isClientApprovalNeededEditFlag = true;
			if ($scope.isRoleAdminForClientApproval) {
				$scope.isRoleAdminForClientApprovalEdit = true;
			} else {
				if ($scope.updateOrder.ocad_approved_by) {
					$scope.isRoleAdminForClientApprovalEdit = false;
				} else {
					$scope.isRoleAdminForClientApprovalEdit = true;
				}
			}
		} else {
			$scope.isClientApprovalNeededEditFlag = angular.element('#client_approval_needed_edit_id').prop('checked');
			if ($scope.isClientApprovalNeededEditFlag == false) {
				$scope.updateOrder.ocad_approved_by = '';
				$scope.updateOrder.ocad_date = '';
				$scope.updateOrder.ocad_credit_period = '';
				$scope.updateOrder.ocad_date_upto_amt = '';
				$scope.isClientApprovalNeededEditFlag = false;
				$scope.isRoleAdminForClientApprovalEdit = true;
			}
		}
	};
	//*********/Client Approval Process***************************

	//**********Uploading Sales Executive File Detail***********************************
	$scope.erpOrderPoFileUploadFormValidator = false;
	$scope.getOrderPurchaseOrderCsvInputTheFiles = function ($files) {
		angular.forEach($files, function (value, key) {
			formdata.append('fileData', value);
			$timeout(function () { $scope.erpOrderPoFileUploadFormValidator = true; }, 100);
			$scope.hideAlertMsg();
		});
	};
	$scope.funUploadPurchaseOrderCsv = function () {
		if (!angular.element("#purchase_order_csv").val()) {
			$scope.errorMsgShowPopup(noFileSelected);
		} else {
			$scope.loaderMainShow();
			$scope.hideAlertMsgPopup();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + 'orders/upload-purchase-order-csv',
				method: "POST",
				headers: { 'Content-Type': undefined },
				data: formdata,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					angular.element("#purchase_order_csv").val('');
					$scope.orderPurchaseOrderCsvUpload = {};
					$scope.erpOrderPurchaseOrderCsvUploadForm.$setUntouched();
					$scope.erpOrderPurchaseOrderCsvUploadForm.$setPristine();
					$scope.successMsgShowPopup(result.message);
				} else {
					angular.element("#purchase_order_csv").val('');
					$scope.errorMsgShowPopup(result.message);
				}
				$scope.resetFileInputData('purchase_order_csv');
				$scope.erpOrderPoFileUploadFormValidator = false;
				$scope.clearConsole();
				$scope.loaderMainHide();
			}).error(function (result, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
				$scope.loaderMainHide();
			});
		}
	};
	//**********/Uploading Sales Executive File Detail***********************************

	//*********Loading Data************************
	$timeout(function () {
		$scope.getProductCategories();
	}, 100);
	$timeout(function () {
		$scope.getDynamicFieldsData();
		$scope.funGetOrdersList(angular.element('#container').attr('division_id'));
	}, 1000);
	//*********/Loading Data************************

}).directive('datepicker', function () {
	return {
		require: 'ngModel',
		link: function (scope, el, attr, ngModel) {
			$(el).datepicker({
				dateFormat: 'dd-mm-yy',
				onSelect: function (dateText) {
					scope.$apply(function () {
						ngModel.$setViewValue(dateText);
					});
				}
			});
		}
	};
}).directive('validNumber', function () {
	return {
		require: '?ngModel',
		link: function (scope, element, attrs, ordersController) {
			if (!ordersController) { return; }
			ordersController.$parsers.push(function (val) {
				if (angular.isUndefined(val)) { var val = ''; }
				var clean = val.replace(/[^-0-9\.]/g, '');
				var negativeCheck = clean.split('-');
				var decimalCheck = clean.split('.');
				if (!angular.isUndefined(negativeCheck[1])) {
					negativeCheck[1] = negativeCheck[1].slice(0, negativeCheck[1].length);
					clean = negativeCheck[0] + '-' + negativeCheck[1];
					if (negativeCheck[0].length > 0) {
						clean = negativeCheck[0];
					}
				}
				if (!angular.isUndefined(decimalCheck[1])) {
					decimalCheck[1] = decimalCheck[1].slice(0, 9);
					clean = decimalCheck[0] + '.' + decimalCheck[1];
				}
				if (val !== clean) {
					ordersController.$setViewValue(clean);
					ordersController.$render();
				}
				return clean;
			});
			element.bind('keypress', function (event) {
				if (event.keyCode === 32) {
					event.preventDefault();
				}
			});
		}
	};
}).directive('validAlphabet', function () {
	return {
		require: 'ngModel',
		link: function (scope, element, attr, ngModelCtrl) {
			function fromUser(text) {
				var transformedInput = text.replace(/[^A-Za-z ]/g, '');
				if (transformedInput !== text) {
					ngModelCtrl.$setViewValue(transformedInput);
					ngModelCtrl.$render();
				}
				return transformedInput;
			}
			ngModelCtrl.$parsers.push(fromUser);
		}
	};
}).directive('validFile', function () {
	return {
		require: 'ngModel',
		link: function (scope, elem, attrs, ngModel) {
			var validFormats = ['jpg', 'jpeg', 'pdf', 'JPG', 'JPEG', 'PDF'];
			elem.bind('change', function () {
				validImage(false);
				scope.$apply(function () {
					ngModel.$render();
				});
			});
			ngModel.$render = function () {
				ngModel.$setViewValue(elem.val());
			};
			function validImage(bool) {
				ngModel.$setValidity('extension', bool);
			}
			ngModel.$parsers.push(function (value) {
				var ext = value.substr(value.lastIndexOf('.') + 1);
				if (ext == '') return;
				if (validFormats.indexOf(ext) == -1) {
					return value;
				}
				validImage(true);
				return value;
			});
		}
	};
}).directive('fileModel', ['$parse', function ($parse) {
	return {
		restrict: 'A',
		link: function (scope, element, attrs) {
			var model = $parse(attrs.fileModel);
			var modelSetter = model.assign;
			element.bind('change', function () {
				scope.$apply(function () {
					modelSetter(scope, element[0].files[0]);
				});
			});
		}
	};
}]).directive('ngFiles', ['$parse', function ($parse) {
	function fn_link(scope, element, attrs) {
		var onChange = $parse(attrs.ngFiles);
		element.on('change', function (event) {
			onChange(scope, { $files: event.target.files });
		});
	};
	return {
		link: fn_link
	}
}]);
