app.controller('invoicingTypeCustomerWiseParametersController', function ($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {

	//define empty variables
	//$scope.currentModule 				= 15;   //variable used in tree.js for tree popup
	$scope.currentModule = 19;   //variable used in tree.js for country tree popup
	$scope.customerWiseParameterRateList = '';
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.cirCustomerID = '0';
	$scope.cirCustomerCityID = '0';
	$scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.addCustomerWiseParametersRateTop = {};
	$scope.addCustomerWiseParametersRateBottom = {};
	$scope.editCustomerWiseParametersRateTop = {};
	$scope.editCustomerWiseParametersRateBottom = {};

	//sorting variables
	$scope.sortType = 'cir_id';     // set the default sort type
	$scope.sortReverse = false;        // set the default sort order
	$scope.searchFish = '';           // set the default search/filter term
	$scope.productCategoryID = '0';
	$scope.customerList = [];
	$scope.erpAddCustomerWiseParametersRateForm = '';
	$scope.erpEditCustomerWiseParametersRateForm = '';
	$scope.deptID = '';
	//**********scroll to top function******************************************
	$scope.moveToMsg = function () {
		$('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
	};
	//**********/scroll to top function******************************************

	//**********loader show****************************************************
	$scope.loaderShow = function () {
		angular.element('#global_loader').fadeIn();
	};
	//**********/loader show**************************************************

	//**********loader show***************************************************
	$scope.loaderHide = function () {
		angular.element('#global_loader').fadeOut();
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

	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup = true;
	$scope.listCustomerWiseProductRateDiv = true;
	$scope.addCustomerWiseParametersRateDiv = false;
	$scope.editCustomerWiseProductRateDiv = false;
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

	//**********confirm box******************************************************
	$scope.showConfirmMessage = function (msg) {
		if (confirm(msg)) {
			return true;
		} else {
			return false;
		}
	};
	//********** /confirm box****************************************************

	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function (id,cirCustomerID, cirDeptId, cirDivisionID, cirSearchKeywordID) {
		$ngConfirm({
			title: false,
			content: defaultDeleteMsg,					 //Defined in message.js and included in head
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme: 'bootstrap',
			buttons: {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funDeleteCustomerWiseParameterRate(id,cirCustomerID, cirDeptId, cirDivisionID, cirSearchKeywordID);
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

	//**********navigate Form*********************************************
	$scope.navigateFormPage = function (type) {
		if (type == 'add') {
			$scope.listCustomerWiseProductRateDiv = false;
			$scope.addCustomerWiseParametersRateDiv = true;
			$scope.editCustomerWiseProductRateDiv = false;
			$scope.resetButton();
			$scope.customerListData = {};
		} else if (type == 'edit') {
			$scope.listCustomerWiseProductRateDiv = false;
			$scope.addCustomerWiseParametersRateDiv = false;
			$scope.editCustomerWiseProductRateDiv = true;
			$scope.resetButton();
		} else {
			$scope.deptID.selectedOption = globalDeptID;
			$scope.funGetParentCategory();
			$scope.listCustomerWiseProductRateDiv = true;
			$scope.addCustomerWiseParametersRateDiv = false;
			$scope.editCustomerWiseProductRateDiv = false;
			$scope.resetButton();

		}
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************

	//**********Back Button*********************************************
	$scope.backButton = function () {
	};
	//**********/Back Button********************************************

	//**********Reset Button*********************************************
	$scope.resetButton = function () {
		$scope.parentCategoryList = [];
		$scope.parametersCategoryList = [];
		$scope.customerWiseParametersRateAddListing = [];
		$scope.customerWiseParameterRateEditListing = [];
		$scope.addCustomerWiseParametersRateBottom = {};
		$scope.addCustomerWiseParametersRateTop = {};
		$scope.editCustomerWiseParametersRateTop = {};
		$scope.editCustomerWiseParametersRateBottom = {};

	};
	//**********/Reset Button*********************************************

	//**********Clearing Console*********************************************
	$scope.clearConsole = function () {
		//console.clear();
	};
	//*********/Clearing Console*********************************************

	//************code used for sorting list order by fields*****************
	$scope.predicate = 'cir_id';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************

	//*****************state/city tree list data*****************
	$scope.stateCityTreeViewList = [];
	$scope.funGetStateCityTreeViewList = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'get-state-city-tree-view'
		}).success(function (result) {
			if (result.stateCityTreeViewList) {
				$scope.stateCityTreeViewList = result.stateCityTreeViewList;
			}
			$scope.clearConsole();
		});
	}
	//*****************/state/city tree list data*****************

	/*****************display division dropdown start 12April,2018*****************/
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.defaultDivisionId = {
			selectedOption: { id: $scope.divisionsCodeList[1].id }
		};
		$scope.clearConsole();
	});
	/*****************display division dropdown end*****************/

	//**********state/city tree popup***************************************************
	$scope.funSetSelectedCustomer = function (customer_id) {
		$scope.cirCustomerID = customer_id;
		$scope.parentCategoryList = [];
		$scope.parametersCategoryList = [];
		$scope.customerWiseParametersRateAddListing = [];
		$scope.customerWiseParameterRateEditListing = [];
		$scope.addCustomerWiseParametersRateBottom = {};
		$scope.funGetParentCategory();
	};
	//**********/state/city tree popup***************************************************

	//**********state/city tree popup***************************************************
	$scope.funShowStateCityTreeViewPopup = function (currentModule) {
		$scope.currentModule = currentModule;
		$('#stateCityTreeViewPopup').modal('show');
	};
	//**********/state/city tree popup***************************************************

	//*******************filter state/city from tree view****************
	$scope.funGetSelectedStateId = function (selectedNode) {
		$scope.funGetCustomerOnStateChange(selectedNode.state_id);
		$('#stateCityTreeViewPopup').modal('hide');
		$scope.currentModule = 15;
	};
	//*****************/filter state/city from tree view*********************************

	//*****************display parent category dropdown code dropdown start****
	$scope.funGetParentCategory = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'master/get-parent-product-category'
		}).success(function (result) {
			$scope.parentCategoryList = result.parentCategoryList;
		});
	};
	//*****************display parent category code dropdown end*****************

	//*****************display customer list dropdown******************************
	$scope.funSetCustomerSelected = function (customer_id, customer_name, type) {
		if (customer_id) {
			$scope.customerListData = [{ 'id': customer_id, 'name': customer_name }];
			$scope.addCustomerWiseParametersRate.cir_customer_id = {
				selectedOption: { 'id': customer_id, 'name': customer_name }
			};

		}
	};
	//*****************/display customer list code dropdown*********************

	//*****************display parent category code dropdown end*****************/
	$scope.funGetParameterCatgeoryList = function (product_category_id) {

		$scope.productCategoryID = product_category_id;
		$scope.parametersCategoryList = [];
		$scope.customerWiseParametersRateAddListing = [];
		$scope.loaderShow();

		$http.post(BASE_URL + "master/invoicing/customer-wise-parameter-rates/parameter-category-list/" + product_category_id, {
		}).success(function (result, status, headers, config) {
			if (result.error == '1') {
				$scope.parametersCategoryList = result.testParameterList;
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//*****************************PARAMETER CATEGORY POPUP CODE START HERE****************

	//****************function Get Product Parameter List**************************
	$scope.funGetParameterListFromParaCategory = function () {

		$scope.hideAlertMsg();
		$scope.addCustomerWiseParametersRateBottom = {};
		$scope.customerWiseParametersRateAddListing = [];
		var http_para_string = { formData: $(erpAddCustomerWiseParametersRateForm).serialize() };
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "master/invoicing/customer-wise-parameter-rates/get-selected-customer-parameter-rates",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.customerWiseParametersRateAddListing = result.parametersRateList;
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//****************function Get Product Parameter List**************************

	//***************** Adding of Branch Wise DebitNote ***************************
	$scope.funAddCustomerWiseParametersRate = function () {

		if ($scope.newcustomerWiseParametersRateFormflag) return;
		$scope.newcustomerWiseParametersRateFormflag = true;
		$scope.loaderMainShow();
		var http_para_string = { formData: $(erpAddCustomerWiseParametersRateForm).serialize() };

		$http({
			url: BASE_URL + "master/invoicing/customer-wise-parameter-rates/add-customer-wise-parameter-rates",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.newcustomerWiseParametersRateFormflag = false;
			if (result.error == 1) {
				$scope.funGetParameterListFromParaCategory();
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
				$scope.loaderMainHide();
			}
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newcustomerWiseParametersRateFormflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*****************/Adding of Branch Wise DebitNote ***************************

	//**********Getting all Payment Made*******************************************
	var tempCustomerWiseParameterRatesSearchTerm;
	$scope.funSearchCustomerWiseParameterRates = function (cirCustomerID, cirDeptID, cirDivisionID, cirSearchKeywordID) {
		tempCustomerWiseParameterRatesSearchTerm = cirSearchKeywordID;
		$timeout(function () {
			if (cirSearchKeywordID == tempCustomerWiseParameterRatesSearchTerm) {
				$scope.funGetCustomerWiseParameterRates(cirCustomerID, cirDeptID, cirDivisionID, cirSearchKeywordID);
			}
		}, 800);
	};
	$scope.funGetCustomerWiseParameterRates = function (cirCustomerID, cirDeptID, cirDivisionID, cirSearchKeywordID='') {

		$scope.cirCustomerID = cirCustomerID;
		$scope.cirDeptID = !cirDeptID ? 1 : cirDeptID;
		$scope.cirDivisionID = !cirDivisionID ? 1 : cirDivisionID;
		$scope.cirSearchKeywordID = cirSearchKeywordID;

		$scope.loaderMainShow();
		var http_para_string = { formData: 'cir_search_keyword=' + $scope.cirSearchKeywordID + '&' + 'cir_customer_id=' + $scope.cirCustomerID + '&' + 'dept_id=' + $scope.cirDeptID + '&' + 'division_id=' + $scope.cirDivisionID };

		$http({
			url: BASE_URL + "master/invoicing/customer-wise-parameter-rates/get-customer-wise-parameter-rates",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.customerWiseParameterRateList = result.returnData;
			$scope.cirCustomerID = result.cir_customer_id;
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.loaderMainHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	$scope.funCustomerWiseParameterAccToDept = function (cirCustomerID, cirDeptID, cirdivisionID,cirSearchKeywordID) {
		globalDeptID = cirDeptID;
		globalDivisionID = cirdivisionID;
		$scope.funGetCustomerWiseParameterRates(cirCustomerID, cirDeptID, cirdivisionID,cirSearchKeywordID);
	};
	$scope.funGetRefreshCustomerWiseParameterRates = function (cirCustomerID, cirDeptID, cirdivisionID,cirSearchKeywordID) {
		$scope.funGetCustomerWiseParameterRates(cirCustomerID, cirDeptID, cirdivisionID,cirSearchKeywordID);
		$scope.deptID.selectedOption.id = cirDeptID;
		$scope.defaultDivisionId.selectedOption.id = cirdivisionID;
		$scope.cirSearchKeyword = cirSearchKeywordID;
		angular.element('#cirSearchKeyword').val('');
	};
	//**********/Getting all Payment Made***********************************************

	//****************Edit Products of selected state *************************************
	$scope.funEditSelectedCustomerParametersRate = function (cir_customer_id) {
		if (cir_customer_id) {

			$scope.cirCustomerID = cir_customer_id;
			$scope.listCustomerWiseProductRateDiv = false;
			$scope.addCustomerWiseParametersRateDiv = false;
			$scope.editCustomerWiseProductRateDiv = true;
			$scope.customerWiseParameterRateEditListing = [];

			$scope.loaderMainShow();
			$scope.hideAlertMsg();
			var http_para_string = { formData: 'cir_customer_id=' + cir_customer_id };

			$http({
				url: BASE_URL + "master/invoicing/customer-wise-parameter-rates/edit-selected-customer-parameter-rates",
				method: "POST",
				data: http_para_string,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.customerWiseParameterRateEditListing = result.returnData;
					$scope.allSelectedTestStandard = result.allSelectedTestStandard;
					$scope.customerListData = result.customersList;
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				$scope.loaderHide();
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************/Edit Products of selected state ***********************************

	//***************** Adding of Branch Wise DebitNote **********************************
	$scope.funUpdateCustomerWiseParametersRate = function (cirCustomerID) {

		if ($scope.newErpEditCustomerWiseProductRateFormflag) return;
		$scope.newErpEditCustomerWiseProductRateFormflag = true;
		var http_para_string = { formData: $(erpEditCustomerWiseProductRateForm).serialize() };
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "master/invoicing/customer-wise-parameter-rates/update-customer-wise-parameter-rates",
			method: "POST",
			data: http_para_string,
		}).success(function (data, status, headers, config) {
			$scope.newErpEditCustomerWiseProductRateFormflag = false;
			if (data.error == 1) {
				$scope.resetButton();
				//$scope.funGetCustomerWiseParameterRates($scope.cirCustomerID);
				$scope.funEditSelectedCustomerParametersRate(cirCustomerID);
				$scope.successMsgShow(data.message);
			} else {
				$scope.errorMsgShow(data.message);
				$scope.loaderMainHide();
			}
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newErpEditCustomerWiseProductRateFormflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//***************** Adding of Branch Wise DebitNote ********************************

	//**************Deleting of Branch Wise Payment Made *******************************
	$scope.funDeleteCustomerWiseParameterRate = function (id,cirCustomerID, cirDeptID, cirDivisionID, cirSearchKeywordID) {
		if (id) {
			$scope.loaderMainShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/invoicing/customer-wise-parameter-rates/delete-customer-wise-parameter-rates/" + id,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.funGetCustomerWiseParameterRates(cirCustomerID, cirDeptID, cirDivisionID, cirSearchKeywordID);
					$scope.successMsgShow(result.message);
				} else {
					$scope.errorMsgShow(result.message);
					$scope.loaderMainHide();
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				$scope.loaderMainHide();
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//************** /Deleting of Branch Wise Payment Made ****************************

	//**************Set Selected Test Standard*****************************************
	$scope.funAddSetSelectedTestStandard = function (cirSelectedTestStandardId, cirId) {
		$timeout(function () {
			$('#cir_test_standard_id_' + cirId + ' option[value=' + cirSelectedTestStandardId + ']').prop('selected', true);
		}, 100);
	}
	//*************/Set Selected Test Standard*****************************************

	//**************Set Selected Test Standard*****************************************
	$scope.funEditSetSelectedTestStandard = function (cirSelectedTestStandardId, indexId) {
		$timeout(function () {
			$('#cir_test_standard_id_' + indexId + ' option[value=' + cirSelectedTestStandardId + ']').prop('selected', true);
		}, 100);
	}
	//*************/Set Selected Test Standard*****************************************
	//*****************display parent category dropdown code dropdown start 01-03-2018****
	var globalDeptID;
	var globalDivisionID;
	$scope.funGetParentCategory = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'master/get-parent-product-category'
		}).success(function (result) {
			$scope.parentCategoryList = result.parentCategoryList;
			if (!globalDeptID) {
				$scope.deptID = {
					selectedOption: { id: $scope.parentCategoryList[0].id }
				};
			} else {
				$scope.deptID = {
					selectedOption: { id: globalDeptID }
				};
			}
		});
	};

	//*****************country/state tree list data******23-JAN-2019***********
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

	//*******************filter scountry/state from tree view****************
	$scope.funGetSelectedNodeId = function (selectedNode) {
		//console.log('plus-click'+selectedNode);
		$scope.funGetCustomerOnStateChange(selectedNode.state_id);
		$('#countryStateTreeViewPopup').modal('hide');
		$scope.currentModule = 19;
	}

	//*****  28-01-2019*************

	//*****************display country dropdown start*****************	
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
	//*****************display country dropdown end*****************

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

	//**********country/state tree popup***************************************************
	$scope.funShowCountryStateViewPopup = function (currentModule) {
		$scope.selectedModuleID = currentModule;
		$('#countryStateViewPopup').modal('show');
	};
	//**********/country/state tree tree popup***************************************************

	//*****************city dropdown on state change*******************************
	$scope.funGetCustomerOnStateChange = function (state_id) {
		if (state_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'master/invoicing/customer-wise-product-rates/get_customer_list/' + state_id
			}).success(function (result) {
				$scope.customerListData = result.customerListData;
				$('#countryStateViewPopup').modal('hide');
				$scope.clearConsole();
			});
		}
	}
	//****************/city dropdown on state change*******************************

	//*********Loading Data************************
	$timeout(function () {
		$scope.funGetParentCategory();
	}, 1000);
	$timeout(function () {
		$scope.funGetCustomerWiseParameterRates(0,1,1,'');
	}, 1000);
	//*********/Loading Data************************
});
