app.controller('customerDefinedStructureController', function ($scope, $http, BASE_URL, $ngConfirm) {

	//define empty variables
	$scope.custdata = [];
	$scope.sortType = 'customer_name';    		// set the default sort type
	$scope.sortReverse = false;             		// set the default sort order
	$scope.searchFish = '';    			 		// set the default search/filter term
	$scope.limitFrom = 0;
	$scope.limitTo = TOTAL_RECORD;

	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.successMessagePopup = '';
	$scope.errorMessagePopup = '';
	$scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.InvoicingTypeYes = true;
	$scope.DiscountTypeNo = false;
	$scope.tatEditableSelected = false;
	$scope.addCustomerInvoicingFormDiv = true;
	$scope.editCustomerInvoicingFormDiv = false;
	$scope.addCustomerInvoicing = {};

	//**********scroll to top function**********
	$scope.moveToMsg = function () {
		$('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
	}
	//**********/scroll to top function**********

	//**********loader show****************************************************
	$scope.loaderShow = function () {
		angular.element('#global_loader').fadeIn('slow');
	}
	//**********/loader show**************************************************

	//**********loader show***************************************************
	$scope.loaderHide = function () {
		angular.element('#global_loader').fadeOut('slow');
	}
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

	//**********Clearing Console********************************************
	$scope.clearConsole = function () {
		if (APPLICATION_MODE) console.clear();
	}
	//*********/Clearing Console********************************************

	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup = true;
	//**********/If DIV is hidden it will be visible and vice versa************

	//**********successMsgShow**************************************************
	$scope.successMsgShow = function (message) {
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		$scope.successMessage = message;
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg = true;
		$scope.moveToMsg();
	}
	//********** /successMsgShow************************************************

	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function (message) {
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		$scope.errorMessage = message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = false;
		$scope.moveToMsg();
	}
	//********** /errorMsgShow************************************************

	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function () {
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = true;
	}
	//********** /hide Alert Msg**********************************************/

	//**********successMsgShowPopup**************************************************
	$scope.successMsgShowPopup = function (message) {
		$scope.successMessagePopup = message;
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup = true;
		$scope.moveToMsg();
	}
	//********** /successMsgShowPopup************************************************

	//**********errorMessagePopup**************************************************
	$scope.errorMsgShowPopup = function (message) {
		$scope.errorMessagePopup = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup = false;
		$scope.moveToMsg();
	}
	//********** /errorMessagePopup************************************************

	//**********successMsgShow**************************************************
	$scope.uploadMsgShow = function (uploadMsg) {
		$scope.uplodedMessageHide = false;
		$scope.notUplodedMessageHide = false;
		$scope.notUplodedMessageHide = false;
		$scope.uplodedMessage = uploadMsg.uploaded;
		$scope.notUplodedMessage = uploadMsg.notUploaded;
		$scope.notUplodedMessage = uploadMsg.duplicate;
	}
	//********** /successMsgShow************************************************

	//**********hide Alert Msg*************
	$scope.hideUploadAlertMsg = function () {
		$scope.uplodedMessageHide = true;
		$scope.notUplodedMessageHide = true;
		$scope.notUplodedMessageHide = true;
	}
	//********** /hide Alert Msg**********************************************

	//**********hideAlertMsgPopup**********************************************
	$scope.hideAlertMsgPopup = function () {
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup = true;
	}
	//********** /hideAlertMsgPopup**********************************************
	/**************multisearch start here**********************/
	$scope.multiSearchTr = true;
	$scope.multisearchBtn = false;
	$scope.getMultiSearch = function () {
		$scope.filterCustomers = '';
		var formData = $(erpFilterMultiSearchDetectorForm).serialize();
		$http.post(BASE_URL + "customer/get-customers-defined-structure-multisearch", {
			data: formData,
		}).success(function (data, status, headers, config) {
			$scope.custDataList = data.customersList;
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '400') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};

	$scope.closeMultisearch = function () {
		$scope.multiSearchTr = true;
		$scope.multisearchBtn = false;
		$scope.refreshMultisearch();
	};

	$scope.refreshMultisearch = function () {
		$scope.searchCustomer = {};
		$scope.funGetCustomersDefinedInvoicings();
	};

	$scope.openMultisearch = function () {
		$scope.multiSearchTr = false;
		$scope.multisearchBtn = true;
	}
	/**************multisearch end here**********************/

	$scope.status = [
		{ 'id': '1', 'name': 'Active' },
		{ 'id': '0', 'name': 'Deactive' },
	];

	/*****************display division dropdown start*****************/
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	/*****************display division dropdown end*****************/
	//*****************invoicing types***************
	$scope.invoicingTypes = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'customer-invoicing-types-list'
	}).success(function (result) {
		if (result) {
			$scope.invoicingTypes = result.invoicingTypes;
		}
		$scope.clearConsole();
	});
	//*****************/invoicing types***************
	//*****************billing types***************
	$scope.billingTypes = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'customer-billing-types-list'
	}).success(function (result) {
		if (result) {
			$scope.billingTypes = result.billingTypes;
		}
		$scope.clearConsole();
	});
	//*****************discount types***************
	$scope.discountTypes = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'discount-types-list'
	}).success(function (result) {
		if (result) {
			$scope.discountTypes = result.discountTypes;
		}
		$scope.clearConsole();
	});
	//*****************customer priority types***************
	//function is used to fetch the list of compines
	$scope.funGetCustomers = function () {
		$http.post(BASE_URL + 'customer/get-all-customer-list', {
		}).success(function (data, status, headers, config) {
			if (data.customersList) {
				$scope.custdata = data.customersList;
			}
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
		});
	};
	/**** func to get parent departments**/
	$scope.funGetParentCategory = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'master/get-parent-product-category'
		}).success(function (result) {
			$scope.parentCategoryList = result.parentCategoryList;
			$scope.clearConsole();
		});
	};
	$scope.DiscountTypeYes = true;
	$scope.DiscountTypeNo = false;
	//*****************/discount types onchange***************

	$scope.funGetDiscountType = function (id, type) {
		if (id == '3') {
			$scope.DiscountTypeYes = false;
			$scope.DiscountTypeNo = true;
			if (type == 'add') {
				$scope.addCustomerInvoicing.discount_value = '';
				$('#discount_value_read_only').attr('readonly', 'true');
			} else {
				$scope.editCustomerInvoicing.discount_value = '';
			}
		} else {
			$scope.DiscountTypeYes = true;
			$scope.DiscountTypeNo = false;
		}
	}
	//*****************/discount types onchange***************
	$scope.funGetCustomersDefinedInvoicings = function () {
		$http.post(BASE_URL + 'customer/get-customers-defined-structure-list', {
		}).success(function (data, status, headers, config) {
			if (data.customersList) {
				$scope.custDataList = data.customersList;
				//$scope.funGetCustomers();
			}
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
		});

	};
	// function is used to add CustomerDefinedInvoicing the
	$scope.funAddCustomerDefinedInvoicing = function () {
		$scope.loaderMainShow();

		//post all form data to save
		$http.post(BASE_URL + "customer/add-customer-defined-structure", {
			data: { formData: $(erpAddCustomerInvoicingForm).serialize() },
		}).success(function (resData, status, headers, config) {

			if (resData.success) {
				$scope.successMsgShow(resData.success);
				$scope.addCustomerInvoicing = {};

			} else {
				$scope.errorMsgShow(resData.error);
			}
			$scope.funGetCustomersDefinedInvoicings();
			$scope.clearConsole();
			$scope.loaderMainHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});
	}



	//code used for sorting list order by fields
	$scope.predicate = 'customer_code';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};


	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function (id, type) {
		//console.log(type);console.log(id);
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
						if (type == 'deleteCustomerRecord') {
							$scope.deleteCustomer(id);
						}
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


	// Delete customer from the database
	$scope.deleteCustomer = function (id) {
		if (id) {
			$scope.loaderShow();
			$http.post(BASE_URL + "customer/customer-defined-structure/delete", {
				data: { "_token": "{{ csrf_token() }}", "id": id }
			}).success(function (resData, status, headers, config) {
				if (resData.success) {
					$scope.funGetCustomersDefinedInvoicings();
					$scope.successMsgShow(resData.success);
				} else {
					$scope.errorMsgShow(resData.error);
				}
				$scope.clearConsole();
				$scope.loaderHide();
			}).error(function (data, status, headers, config) {
				if (status == '500' || status == '400') {
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
				$scope.loaderHide();
			});
		}
	};

	//edit an customer and its data
	$scope.funEditCustomerDefinedInvoice = function (id) {
		if (id) {
			$scope.editCustomerId = id;
			$scope.loaderMainShow();
			$scope.editCustomerInvoicingFormDiv = true;
			$scope.addCustomerInvoicingFormDiv = false;
			$scope.tatEditableSelected = false;
			$scope.editCustomerInvoicing = {};

			$http.post(BASE_URL + "customer/customer-defined-structure/edit", {
				data: { "_token": "{{ csrf_token() }}", "id": id }
			}).success(function (data, status, headers, config) {
				if (data.returnData.responseData) {
					var resData = data.returnData.responseData;
					$scope.editCustomerInvoicing.cdit_id = resData.cdit_id;
					$scope.funGetCustomers();
					$scope.editCustomerInvoicing.division_id = { selectedOption: { id: resData.division_id, name: resData.division_name } };
					$scope.editCustomerInvoicing.customer_id = { selectedOption: { cust_id: resData.customer_id, customer_name: resData.customer_name } };
					$scope.editCustomerInvoicing.product_category_id = { selectedOption: { id: resData.id, name: resData.name } };
					$scope.editCustomerInvoicing.invoicing_type_id = { selectedOption: { id: resData.invoicing_type_id, name: resData.invoicing_type } };
					$scope.editCustomerInvoicing.billing_type_id = { selectedOption: { id: resData.billing_type_id, name: resData.billing_type } };
					$scope.editCustomerInvoicing.discount_type_id = { selectedOption: { id: resData.discount_type_id, name: resData.discount_type } };
					$scope.editCustomerInvoicing.discount_value = resData.discount_value;

					if (resData.customer_invoicing_type_status == '1') {
						$scope.editCustomerInvoicing.status = { selectedOption: { id: resData.customer_invoicing_type_status, name: 'Active' } };
					} else {
						$scope.editCustomerInvoicing.status = { selectedOption: { id: resData.customer_invoicing_type_status, name: 'Deactive' } };
					}
					if (resData.discount_type_id == '3') {
						$scope.DiscountTypeYes = false;
						$scope.DiscountTypeNo = true;
						$scope.editCustomerInvoicing.discount_value = '';
					} else {
						$scope.DiscountTypeYes = true;
						$scope.DiscountTypeNo = false;
					}
					if (resData.tat_editable == '1') {
						$scope.tatEditableSelected = true;
					} else {
						$scope.tatEditableSelected = false;
					}
				} else {
					$scope.errorMsgShow(data.error);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if (status == '500' || status == '400') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			});
		}
	};

	//update customer and its data
	$scope.funUpdateCustomerDefinedInvoice = function () {

		$scope.loaderMainShow();

		// post all form data to save
		$http.post(BASE_URL + "customer/customer-defined-structure/update", {
			data: { formData: $(erpEditCustomerInvoicingForm).serialize() },
		}).success(function (resData, status, headers, config) {
			if (resData.success) {
				$scope.funGetCustomersDefinedInvoicings();
				if ($scope.multiSearchTr == false) {
					$scope.getMultiSearch();
				}

				$scope.successMsgShow(resData.success);
			} else {
				$scope.errorMsgShow(resData.error);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});
	};

	$scope.addCustomerForm = function () {
		$scope.addCustomerInvoicingFormDiv = true;
		$scope.editCustomerInvoicingFormDiv = false;
	};

	$scope.resetForm = function () {
		$scope.addCustomerInvoicing = {};
	};

	/***************** customer SECTION END HERE *****************/

});
