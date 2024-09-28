app.controller('samplesController', function ($scope, $timeout, $http, BASE_URL, $ngConfirm) {

	//define empty variables
	$scope.prodata = '';
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.selected = '';
	$scope.filterSample = {};
	$scope.addBranchWiseSample = {};
	$scope.filterSample.status = '0';
	$scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType = 'sample_id';    // set the default sort type
	$scope.sortReverse = false;         // set the default sort order
	$scope.searchFish = '';    		 // set the default search/filter term

	//**********scroll to top function**********
	$scope.moveToMsg = function () {
		$('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
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

	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup = true;
	$scope.isVisibleListSampleDiv = false;
	$scope.isVisibleAddSampleDiv = false;
	$scope.isVisibleEditSampleDiv = true;
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
	$scope.funConfirmDeleteMessage = function (id, divisionId, sampleStatus) {
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
						$scope.funDeleteSample(id, divisionId, sampleStatus);
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
	$scope.funConfirmCloseMessage = function (id, divisionId, sampleStatus) {
		$ngConfirm({
			title: false,
			content: SAMPLECLOSEMESSAGE, //Defined in message.js and included in head
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
						$scope.funCloseSampleReceiving(id, divisionId, sampleStatus);
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
	$scope.showConfirmMessage = function (msg) {
		if (confirm(msg)) {
			return true;
		} else {
			return false;
		}
	};
	//********** /confirm box****************************************************

	//**********navigate Form*********************************************
	$scope.navigateSamplePage = function () {
		if (!$scope.isVisibleAddSampleDiv) {
			$scope.isVisibleAddSampleDiv = true;
			$scope.isVisibleEditSampleDiv = false;
		} else {
			$scope.isVisibleAddSampleDiv = false;
			$scope.isVisibleEditSampleDiv = true;
		}
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************

	//**********back Button*********************************************
	$scope.backButton = function () {
		$scope.addBranchWiseSample = {};
		$scope.erpAddBranchWiseSampleForm.$setUntouched();
		$scope.erpAddBranchWiseSampleForm.$setPristine();
		$scope.editBranchWiseSample = {};
		$scope.erpEditBranchWiseSampleForm.$setUntouched();
		$scope.erpEditBranchWiseSampleForm.$setPristine();
		$scope.navigateSamplePage();
	};
	//**********/back Button*********************************************

	//**********back Button*********************************************
	$scope.resetButton = function () {
		$scope.addBranchWiseSample = {};
		$scope.erpAddBranchWiseSampleForm.$setUntouched();
		$scope.erpAddBranchWiseSampleForm.$setPristine();
		$scope.editBranchWiseSample = {};
		$scope.erpEditBranchWiseSampleForm.$setUntouched();
		$scope.erpEditBranchWiseSampleForm.$setPristine();
	};
	//**********/back Button*********************************************

	//**********Clearing Console*********************************************
	$scope.clearConsole = function () {
		if (APPLICATION_MODE) console.clear();
	};
	//*********/Clearing Console*********************************************

	//************code used for sorting list order by fields*****************
	$scope.predicate = 'sample_id';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************

	//*****************display division dropdown start************************	
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end***************************

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

	//*****************display division dropdown start************************	
	$scope.sampleModeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'sales/samples/get-sample-modes'
	}).success(function (result) {
		$scope.sampleModeList = result.sampleModeList;
		$scope.clearConsole();
	});
	//*****************display division dropdown end***************************

	//*****************display customer list dropdown******************************
	// $scope.customerListData = [];
	// $http({
	// 	method: 'POST',
	// 	url: BASE_URL + 'payments/customers-list'
	// }).success(function (result) {
	// 	$scope.customerListData = result.customersList;
	// 	$scope.clearConsole();
	// });
	//*****************/display customer list code dropdown*********************

	//*****************display customer list dropdown******************************
	$scope.sampleActionList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'sales/samples/get-sample-status'
	}).success(function (result) {
		$scope.sampleStatusList = result.sampleStatusList;
		$scope.clearConsole();
	});
	//*****************/display customer list code dropdown*********************

	//**************** To add New Customer ************************************
	$scope.isAddNewCustomerSample = false;
	$scope.funToAddNewCustomer = function (type) {
		if (type == '1') {
			$scope.isAddNewCustomerSample = true;
		} else {
			$scope.isAddNewCustomerSample = false;
		}
	};
	//************** /Deleting of Branch Wise Sample *******************************

	//**********Read/hide More description*********************************************
	$scope.toggleDescription = function (type, id) {
		angular.element('#' + type + 'LimitedText_' + id).toggle();
		angular.element('#' + type + 'FullText_' + id).toggle();
	};
	//*********/Read More description********************************************

	//**********Read/hide More remarks*******************************************
	$scope.toggleRemark = function (type, id) {
		angular.element('#' + type + 'LimitedText_' + id).toggle();
		angular.element('#' + type + 'FullText_' + id).toggle();
	};
	//*********/Read More remarks*************************************************

	//*****************Display TRF No. Dropdown list******************************
	$scope.trfSelectBoxList = [];
	$scope.showOrderDateCalender = '0';
	$scope.funTrfSelectBoxList = function (division_id, product_category_id) {
		$http({
			method: 'POST',
			url: BASE_URL + 'sales/samples/get-trf-number-list',
			data: { division_id: division_id, product_category_id: product_category_id },
		}).success(function (result) {
			$scope.trfSelectBoxList = result.trfSelectBoxList;
			$scope.showOrderDateCalender = result.showOrderDateCalender;
			$scope.clearConsole();
		});
	};
	//*****************Display /TRF No. Dropdown list******************************

	//**********Getting TRF Involved Customer***************************************
	$scope.funGetTrfInvolvedCustomer = function (trf_id) {
		$scope.addBranchWiseSample.customer_id = {};
		if (trf_id) {
			$http({
				url: BASE_URL + "sales/samples/get-trf-involved-customer/" + trf_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				$scope.addBranchWiseSample.customer_id = { id: result.customer_id };
				$scope.clearConsole();
			}).error(function (result, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});
		}
	};
	//**********/Getting TRF Involved Customer***************************************

	//**********Getting all Samples***********************************************
	$scope.funGetBranchWiseSamples = function (divisionId, sampleStatus) {
		$scope.divisionID = divisionId;
		$scope.sampleStatusID = sampleStatus;
		$scope.loaderShow();
		$http({
			url: BASE_URL + "sales/samples/get-division-wise-samples/" + divisionId + '/' + sampleStatus,
			method: "GET",
		}).success(function (result, status, headers, config) {
			$scope.sampleDataList = result.sampleDataList;
			$scope.filterSample.status = { id: sampleStatus };
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//**********/Getting all Orders*************************************************

	//***************** Adding of Branch Wise Sample ********************************
	$scope.funAddBranchWiseSample = function (divisionId, sampleStatusId) {

		if (!$scope.erpAddBranchWiseSampleForm.$valid) return;
		if ($scope.newAddBranchWiseSampleFormflag) return;
		$scope.newAddBranchWiseSampleFormflag = true;
		var formData = $(erpAddBranchWiseSampleForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "sales/samples/add-division-sample",
			method: "POST",
			data: { formData: formData }
		}).success(function (data, status, headers, config) {
			$scope.newAddBranchWiseSampleFormflag = false;
			if (data.error == 1) {
				$scope.resetButton();
				$scope.funGetBranchWiseSamples(divisionId, sampleStatusId);
				$scope.funTrfSelectBoxList();
				$scope.successMsgShow(data.message);
			} else {
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newAddBranchWiseSampleFormflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//**************** /Adding of Branch Wise Sample ***************************

	//**************** editing of item *************************************
	$scope.funEditSample = function (sampleId) {
		if (sampleId) {

			$scope.trfSelectBoxList = [];
			$scope.hideAlertMsg();
			$scope.loaderShow();

			$http({
				url: BASE_URL + "sales/samples/view-division-sample/" + sampleId,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					$scope.resetButton();
					$scope.isVisibleAddSampleDiv = true;
					$scope.isVisibleEditSampleDiv = false;
					$scope.editBranchWiseSample = result.sampleDetailList;

					if (result.trfDataList.trf_id) {
						$scope.trfSelectBoxList = [{ id: result.trfDataList.trf_id, name: result.trfDataList.trf_no }];
						$scope.editBranchWiseSample.trf_id = { id: result.sampleDetailList.trf_id };
					}
					if (result.sampleDetailList.customer_id) {
						$scope.customerListData = [{ id: result.sampleDetailList.customer_id, name: result.sampleDetailList.customerNewName}];
						$scope.isEditNewCustomerSample = false;
						$timeout(function () {$scope.editBranchWiseSample.customer_id = {selectedOption: { id: result.sampleDetailList.customer_id }};}, 100);						
					} else if (result.sampleDetailList.customer_name_new) {
						$scope.isEditNewCustomerSample = true;
					}
					$scope.editBranchWiseSample.division_id = {
						selectedOption: { id: result.sampleDetailList.division_id }
					};
					$scope.editBranchWiseSample.product_category_id = {
						selectedOption: { id: result.sampleDetailList.product_category_id }
					};
					$scope.editBranchWiseSample.sample_mode_id = {
						selectedOption: { id: result.sampleDetailList.sample_mode_id }
					};
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});
		}
	};
	//****************/editing of item *************************************

	//**************** Updating of item *************************************
	$scope.funUpdateBranchWiseSample = function (divisionId, sampleStatusId) {

		if (!$scope.erpEditBranchWiseSampleForm.$valid) return;
		var formData = $(erpEditBranchWiseSampleForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "sales/samples/update-division-sample",
			method: "POST",
			data: { formData: formData }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.funGetBranchWiseSamples(divisionId, sampleStatusId);
				$scope.customerListData = [];
				$scope.backButton();
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//*************** /Updating of item *************************************

	//**************** Deleting of Branch Wise Sample ***************************
	$scope.funDeleteSample = function (sampleId, divisionId, sampleStatus) {
		if (sampleId) {
			$scope.hideAlertMsg();
			$scope.loaderShow();
			$http({
				url: BASE_URL + "sales/samples/delete-division-sample/" + sampleId,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.funGetBranchWiseSamples(divisionId, sampleStatus);
					$scope.successMsgShow(result.message);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});
		}
	};
	//************** /Deleting of Branch Wise Sample *******************************

	//**************** Deleting of Branch Wise Sample ***************************
	$scope.funCloseSampleReceiving = function (sampleId, divisionId, sampleStatus) {
		if (sampleId) {
			$scope.hideAlertMsg();
			$scope.loaderShow();
			$http({
				url: BASE_URL + "sales/samples/close-division-sample/" + sampleId,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.funGetBranchWiseSamples(divisionId, sampleStatus);
					$scope.successMsgShow(result.message);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});
		}
	};
	//************** /Deleting of Branch Wise Sample *******************************

	//*****************Customer Selection from POPUP WINDOW***************

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
				url: BASE_URL + 'get_customer_list_by_state/' + state_id
			}).success(function (result) {
				$scope.customerListData = result.customerListData;
				$('#countryStateViewPopup').modal('hide');
				$scope.clearConsole();
			});
		}
	};
	//****************/city dropdown on state change*******************************

	//*****************Customer Selection from POPUP WINDOW***************
});