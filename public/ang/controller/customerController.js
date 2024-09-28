app.controller('customerController', function ($scope, $timeout, $http, BASE_URL, $ngConfirm) {

	//define empty variables
	$scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.custdata = '';
	$scope.sortType = 'customer_code';    		// set the default sort type
	$scope.sortReverse = false;             		// set the default sort order
	$scope.searchFish = '';    			 		// set the default search/filter term
	$scope.limitFrom = 0;
	$scope.limitTo = TOTAL_RECORD;
	$scope.searchCustomer = {};
	$scope.addFormDiv = true;
	$scope.editFormDiv = true;
	$scope.viewCustomerDiv = true;
	$scope.listCustomer = false;
	$scope.isUploadFormListShow = true;
	$scope.isUploadFormShow = false;
	$scope.DiscountTypeYes = true;
	$scope.DiscountTypeNo = false;
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.successMessagePopup = '';
	$scope.errorMessagePopup = '';
	$scope.InvoicingTypeYes = true;
	$scope.DiscountTypeNo = false;
	$scope.addEmailFormDiv = true;
	$scope.isVisibleEmailListDiv = false;
	$scope.showCustomerEmailSection = false;
	$scope.editEmailFormDiv = false;
	$scope.showCustomerSection = true;
	$scope.customer = {};
	$files = [];
	$scope.countryCodeList = [];
	$scope.statesCodeList = [];
	$scope.citiesCodeList = [];
	$scope.citiesCodesList = [];

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
	};
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
	};
	//********** /successMsgShow************************************************

	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function (message) {
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
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
	//********** /hide Alert Msg**********************************************/

	//**********successMsgShowPopup**************************************************
	$scope.successMsgShowPopup = function (message) {
		$scope.successMessagePopup = message;
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup = true;
		$scope.moveToMsg();
	};
	//********** /successMsgShowPopup************************************************

	//**********errorMessagePopup**************************************************
	$scope.errorMsgShowPopup = function (message) {
		$scope.errorMessagePopup = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup = false;
		$scope.moveToMsg();
	};
	//********** /errorMessagePopup************************************************

	//**********successMsgShow**************************************************
	$scope.uploadMsgShow = function (uploadMsg) {
		$scope.uplodedMessageHide = false;
		$scope.notUplodedMessageHide = false;
		$scope.notUplodedMessageHide = false;
		$scope.uplodedMessage = uploadMsg.uploaded;
		$scope.notUplodedMessage = uploadMsg.notUploaded;
		$scope.notUplodedMessage = uploadMsg.duplicate;
	};
	//********** /successMsgShow************************************************

	//**********hide Alert Msg*************
	$scope.hideUploadAlertMsg = function () {
		$scope.uplodedMessageHide = true;
		$scope.notUplodedMessageHide = true;
		$scope.notUplodedMessageHide = true;
	};
	//********** /hide Alert Msg**********************************************

	//**********hideAlertMsgPopup**********************************************
	$scope.hideAlertMsgPopup = function () {
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup = true;
	};
	//********** /hideAlertMsgPopup**********************************************

	//**********hideAlertMsgPopup**********************************************
	$scope.resetFileInputData = function () {
		$files = [];
		var formdata = new FormData();
		formdata = null;
	};
	//********** /hideAlertMsgPopup**********************************************

	$scope.emailTypes = [
		{ 'id': 'P', 'name': 'Primary' },
		{ 'id': 'S', 'name': 'Secondary' },
	];
	$scope.emailStatusTypes = [
		{ 'id': '1', 'name': 'Active' },
		{ 'id': '2', 'name': 'Inactive' },
	];

	//*****************display state code dropdown start************************
	$scope.stateCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'statesList'
	}).success(function (result) {
		if (result) {
			$scope.stateCodeList = result;
		}
		$scope.clearConsole();
	});
	//*****************display state code dropdown end***************************

	/*****************display city code dropdown start*****************/
	$http({
		method: 'POST',
		url: BASE_URL + 'cities'
	}).success(function (result) {
		if (result) {
			$scope.citiesCodeList = result;
		}
		$scope.clearConsole();
	});
	/*****************display city code dropdown end*****************/

	/*****************display ownership type code dropdown start*****************/
	$scope.ownershipTypes = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'ownership_types'
	}).success(function (result) {
		if (result) {
			$scope.ownershipTypes = result;
		}
		$scope.clearConsole();
	});
	/*****************display ownership type code dropdown end*****************/

	/*****************display company type code dropdown start*****************/
	$scope.companyTypes = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'company_types'
	}).success(function (result) {
		if (result) {
			$scope.companyTypes = result;
		}
		$scope.clearConsole();
	});
	/*****************display company type code dropdown end*****************/

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
	//*****************/billing types***************

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
	$scope.customerPriorityTypes = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'customer-priority-list'
	}).success(function (result) {
		if (result) {
			$scope.customerPriorityTypes = result.samplePriorityCRMList;
		}
		$scope.clearConsole();
	});
	//*****************/discount types***************

	//*****************/discount types onchange***************
	$scope.funGetDiscountType = function (id, type) {
		if (id == '3') {
			$scope.DiscountTypeYes = false;
			$scope.DiscountTypeNo = true;
			if (type == 'add') {
				$scope.customer.discount_value = '';
				$('#discount_value').attr('readonly', 'true');
			} else {
				$scope.discount_value1 = '';
			}
		} else {
			$scope.DiscountTypeYes = true;
			$scope.DiscountTypeNo = false;
		}
	};
	//*****************/discount types onchange***************

	//*****************Customer types***************
	$scope.customerTypes = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'customer-types-list'
	}).success(function (result) {
		if (result) {
			$scope.customerTypes = result.customerTypes;
		}
		$scope.clearConsole();
	});
	//*****************/Customer types***************

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

	//*****************sale_executive dropdown list**********************
	$scope.salesExecutiveList = [];
	$scope.funGetSalesExecutives = function (division_id) {
		if (angular.isDefined(division_id)) {
			$http({
				method: 'GET',
				url: BASE_URL + 'customer/get_sales_executive_list/' + division_id
			}).success(function (result) {
				$scope.salesExecutiveList = result.executiveList;
				$scope.clearConsole();
			});
		}
	};
	//*****************/sale_executive dropdown list***********************

	//*****************display country code dropdown start*****************
	$http({
		method: 'POST',
		url: BASE_URL + 'allcountries'
	}).success(function (result) {
		if (result) {
			$scope.countryCodeList = result;
		}
		$scope.clearConsole();
	});
	//*****************display Country code dropdown end********************

	//*****************state dropdown on country change**********************
	$scope.funGetStateOnCountryChange = function (country_id) {
		if (country_id) {
			$http({
				method: 'POST',
				url: BASE_URL + 'get_states_list/' + country_id
			}).success(function (result) {
				$scope.statesCodeList = result.countryStatesList;
				$scope.clearConsole();
			});
		}
	};
	//****************/state dropdown on country change******************

	//*****************city dropdown on state change**********************
	$scope.funGetCityOnStateChange = function (state_id) {
		if (state_id) {
			$http({
				method: 'GET',
				url: BASE_URL + 'get_cities_list/' + state_id
			}).success(function (result) {
				$scope.citiesCodesList = result.stateCitiesList;
				$scope.customer.stateCode = result.stateCode.code;
				$scope.state_code1 = result.stateCode.code;
				$scope.clearConsole();
			});
		}
	};
	//****************/city dropdown on state change********************

	//*****************generate unique code******************
	$scope.customer_code = '';
	$scope.generateDefaultCode = function () {
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL + 'customer/generate-customer-number'
		}).success(function (result) {
			$scope.customer_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************

	//*****************Displaying Customer GST Categories*****************
	$http({
		method: 'POST',
		url: BASE_URL + 'customer/get-customer-gst-categories'
	}).success(function (result) {
		$scope.customerGSTCategoriesList = result.customerGstCategoriesList;
		$scope.clearConsole();
	});
	//****************/Displaying Customer GST Categories********************

	//*****************Displaying Customer GST Types******************
	$http({
		method: 'POST',
		url: BASE_URL + 'customer/get-customer-gst-types'
	}).success(function (result) {
		$scope.customerGstTypesList = result.customerGstTypesList;
		$scope.clearConsole();
	});
	//*****************/Displaying Customer GST Types********************

	//*****************Displaying Customer Tax Slab Types******************
	$http({
		method: 'POST',
		url: BASE_URL + 'customer/get-customer-gst-tax-slab-types'
	}).success(function (result) {
		$scope.customerGstTaxSlabTypesList = result.customerGstTaxSlabTypesList;
		$scope.clearConsole();
	});
	//*****************/Displaying Customer Tax Slab Types********************

	/***************** customer SECTION START HERE *****************/

	// function is used to call the
	$scope.addCustomer = function (allow = null) {
		$scope.loaderMainShow();
		//POST all form data to save
		$http.post(BASE_URL + "customer/add-customer", {
			data: { formData: $(customerForm).serialize(), allowGst: allow },
		}).success(function (resData, status, headers, config) {
			if (resData.success) {
				$scope.hideAddForm();
				$scope.customer = {};
				$scope.customerForm.$setUntouched();
				$scope.customerForm.$setPristine();
				if (resData.saveGst == 'yes') {
					$('#gstNumberPopUp').modal('hide');
				}
				$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
				$scope.successMsgShow(resData.success);
			} else {
				if (resData.customerGstCount >= 1) {
					$scope.formData = resData.formData;
					$scope.funOpenBootStrapModalPopup();
				} else {
					$scope.errorMsgShowPopup(resData.error);
				}
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});
	};
	$scope.funOpenBootStrapModalPopup = function () {
		$scope.successMsgOnPopup = '';
		$scope.errorMsgOnPopup = '';
		$('#gstNumberPopUp').modal({ backdrop: 'static', keyboard: true, show: true });
	};

	//*****************code used for sorting list order by fields********************
	$scope.predicate = 'customer_code';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//****************/code used for sorting list order by fields********************

	//********************LISTING OF CUSTOMER START**********************************
	var tempSearchKeyword;
	$scope.funGetCustomers = function (limitFrom, limitTo) {

		$scope.limitFrom = limitFrom;
		$scope.limitTo = limitTo;
		$scope.searchCustomer.limitFrom = limitFrom;
		$scope.searchCustomer.limitTo = limitTo;
		$scope.generateDefaultCode();

		$http.post(BASE_URL + 'customer/get-customers', {
			data: { formData: $scope.searchCustomer },
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
	$scope.funGetLimitFromCustomers = function (limitFrom) {
		tempSearchKeyword = limitFrom;
		$timeout(function () {
			if (tempSearchKeyword == limitFrom) {
				$scope.limitFrom = limitFrom;
				$scope.limitTo = $scope.limitTo;
				$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
			}
		}, 500);
	};
	$scope.funGetLimitToCustomers = function (limitTo) {
		tempSearchKeyword = limitTo;
		$timeout(function () {
			if (tempSearchKeyword == limitTo) {
				$scope.limitFrom = $scope.limitFrom;
				$scope.limitTo = limitTo;
				$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
			}
		}, 500);
	};
	//**************multisearch start here***********************************
	$scope.multiSearchTr = true;
	$scope.multisearchBtn = false;
	$scope.getMultiSearch = function (searchKeyword) {
		$scope.filterCustomers = '';
		tempSearchKeyword = searchKeyword;
		$timeout(function () {
			if (tempSearchKeyword == searchKeyword) {
				$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
			}
		}, 1000);
	};
	$scope.closeMultisearch = function () {
		$scope.multiSearchTr = true;
		$scope.multisearchBtn = false;
		$scope.refreshMultisearch();
	};
	$scope.refreshMultisearch = function () {
		$scope.searchCustomer = {};
		$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
	};
	$scope.openMultisearch = function () {
		$scope.multiSearchTr = false;
		$scope.multisearchBtn = true;
	};
	//**************multisearch end here****************************************
	//********************LISTING OF CUSTOMER ENDS**********************************

	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function (id, type) {
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
						} else if (type == 'deleteCustomerEmailRecord') {
							$scope.funDeleteCustomerEmail(id);
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
	//**********/confirm box****************************************************

	//*******************Delete customer from the database***********************
	$scope.deleteCustomer = function (id) {
		if (!id) return;
		$scope.loaderShow();
		$http.post(BASE_URL + "customer/delete-customer", {
			data: { "_token": "{{ csrf_token() }}", "id": id }
		}).success(function (resData, status, headers, config) {
			if (resData.success) {
				$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
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
	};
	//*******************Delete customer from the database****************************

	//**************Edit an customer and its data***************************************
	$scope.editCustomer = function (id, division_id) {
		if (id) {
			$scope.editCustomerId = id;
			$scope.customer_gst_no1 = '';
			$scope.editCustomerDivisionId = division_id;
			$scope.loaderMainShow();
			$scope.showEditForm();

			$http.post(BASE_URL + "customer/edit-customer", {
				data: { "_token": "{{ csrf_token() }}", "id": id }
			}).success(function (data, status, headers, config) {
				if (data.returnData.responseData) {
					var resData = data.returnData.responseData;
					var isAdmin = data.isAdmin;
					$scope.customer_id = btoa(resData.customer_id);
					$scope.customer_code1 = resData.customer_code;
					$scope.logic_customer_code1 = resData.logic_customer_code;
					$scope.customer_name1 = resData.customer_name;
					$scope.customer_email1 = resData.customer_email;
					$scope.customer_mobile1 = resData.customer_mobile;
					$scope.customer_phone1 = resData.customer_phone;
					$scope.customer_address1 = resData.customer_address;
					$scope.state_code1 = resData.state_code;
					$scope.sale_executive_id = resData.sale_executive;

					if (isAdmin) {
						$scope.funGetStateOnCountryChange(resData.customer_country);
						$scope.funGetCityOnStateChange(resData.customer_state);
					} else {
						$scope.countryCodeList = [{ id: resData.customer_country, name: resData.country_name }];
						$scope.statesCodeList = [{ id: resData.customer_state, name: resData.state_name }];
						$scope.citiesCodesList = [{ id: resData.customer_city, name: resData.city_name }];
					}

					$scope.customer_country1 = { selectedOption: { id: resData.customer_country } };
					$scope.customer_pincode1 = resData.customer_pincode;
					$scope.customer_state1 = { selectedOption: { id: resData.customer_state } };
					$scope.customer_city1 = { selectedOption: { id: resData.customer_city } };

					$scope.customer_type1 = { selectedOption: { id: resData.type_id } };
					$scope.billing_type1 = { selectedOption: { id: resData.billing_type } };
					$scope.invoicing_type_id1 = { selectedOption: { id: resData.invoicing_type_id } };

					if (resData.invoicing_type_id1 == '1') {
						$scope.invoicingTypeYes = false;
						$scope.invoicingTypeNo = true;
						$scope.customer.amount_value1 = '';
					} else {
						$scope.invoicingTypeYes = true;
						$scope.invoicingTypeNo = false;
					}
					$scope.amount_value1 = resData.fixed_rate_amount;

					$scope.division_id1 = {
						selectedOption: { id: division_id }
					};
					$scope.funGetSalesExecutives(division_id);
					$scope.sale_executive1 = {
						selectedOption: { id: resData.sale_executive }
					};
					if (resData.discount_type_id == '3') {
						$scope.DiscountTypeYes = false;
						$scope.DiscountTypeNo = true;
						$scope.discount_value1 = '';
					} else {
						$scope.DiscountTypeYes = true;
						$scope.DiscountTypeNo = false;
					}
					$scope.customer_priority1 = {
						selectedOption: { sample_priority_id: resData.sample_priority_id }
					};

					$scope.discount_types1 = {
						selectedOption: { id: resData.discount_type_id }
					};
					$scope.discount_value1 = resData.discount_value;
					$scope.mfg_lic_no1 = resData.mfg_lic_no;
					$scope.customer_vat_cst1 = resData.customer_vat_cst;
					$scope.ownership_type1 = {
						selectedOption: { id: resData.ownership_id }
					};
					$scope.company_type1 = {
						selectedOption: { id: resData.company_type_id }
					};

					$scope.customer_gst_category_id1 = { id: resData.customer_gst_category_id };
					$scope.customer_gst_type_id1 = { id: resData.customer_gst_type_id };
					$scope.customer_gst_tax_slab_type_id1 = { id: resData.customer_gst_tax_slab_type_id };
					$scope.customer_gst_no1 = resData.customer_gst_no;

					$scope.owner_name1 = resData.owner_name;
					$scope.customer_pan_no1 = resData.customer_pan_no;
					$scope.customer_tan_no1 = resData.customer_tan_no;
					$scope.bank_account_no1 = resData.bank_account_no;
					$scope.bank_account_name1 = resData.bank_account_name;
					$scope.bank_name1 = resData.bank_name;
					$scope.bank_branch_name1 = resData.bank_branch_name;
					$scope.bank_rtgs_ifsc_code1 = resData.bank_rtgs_ifsc_code;
					$scope.contact_name11 = resData.contact_name1;
					$scope.contact_designate11 = resData.contact_designate1;
					if (resData.contact_mobile1 != '0') {
						$scope.contact_mobile11 = parseInt(resData.contact_mobile1);
						$scope.disable_contact_mobile11 = false;
					} else {
						$scope.disable_contact_mobile11 = true;
					}
					$scope.contact_email11 = resData.contact_email1;
					$scope.contact_name22 = resData.contact_name2;
					$scope.contact_designate22 = resData.contact_designate2;
					if (resData.contact_mobile2 != '0') {
						$scope.contact_mobile22 = parseInt(resData.contact_mobile2);
					}
					$scope.contact_email22 = resData.contact_email2;
					$scope.contact_id = resData.contact_id;
				} else {
					$scope.errorMsgShowPopup(data.error);
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
	//*************/Edit an customer and its data***************************************

	//********************update customer and its data*********************************
	$scope.updateCustomer = function (allow = null) {

		//if(!$scope.editCustomerForm.$valid)return;
		$scope.loaderMainShow();

		// post all form data to save
		$http.post(BASE_URL + "customer/update-customer", {
			data: { formData: $(editCustomerForm).serialize(), 'allowGst': allow },
		}).success(function (resData, status, headers, config) {
			if (resData.success) {
				if (resData.updateGst == 'yes') {
					$('#editGstNumberPopUp').modal('hide');
				}
				$scope.successMsgShow(resData.success);
			} else {
				if (resData.customerGstCount >= 1) {
					$scope.formData = resData.formData;
					$scope.funOpenEditBootStrapModalPopup();
				} else {
					$scope.errorMsgShowPopup(resData.error);
				}
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});
	};
	//********************/Update customer and its data*********************************

	$scope.funOpenEditBootStrapModalPopup = function () {
		$scope.successMsgOnPopup = '';
		$scope.errorMsgOnPopup = '';
		$('#editGstNumberPopUp').modal({ backdrop: 'static', keyboard: true, show: true });
	};

	//view an customer and its data
	$scope.viewCustomerDetails = function (id, division_id) {
		$scope.loaderMainShow();
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		$scope.editFormDiv = true;
		$scope.listCustomer = true;
		$scope.addFormDiv = true;
		$scope.viewCustomerDiv = false;
		if (id) {
			$http.post(BASE_URL + "customer/edit-customer", {
				data: { "_token": "{{ csrf_token() }}", "id": id }
			}).success(function (data, status, headers, config) {
				if (data.returnData.responseData) {
					var resData = data.returnData.responseData;
					$scope.view_customer_code = resData.customer_code;
					$scope.view_customer_name = $scope.uc_first(resData.customer_name);
					$scope.view_customer_mobile = resData.customer_mobile;
					$scope.view_customer_email = resData.customer_email;
					$scope.view_customer_phone = resData.customer_phone;
					$scope.view_customer_address = $scope.uc_first(resData.customer_address);
					$scope.view_customer_pincode = resData.customer_pincode;
					$scope.view_customer_country = resData.country_name;
					$scope.view_customer_state = resData.state_name;
					$scope.view_customer_city = resData.city_name;
					$scope.view_customer_type = $scope.uc_first(resData.customer_type);
					$scope.view_billing_type = $scope.uc_first(resData.billing_type);
					$scope.view_invoicing_type = $scope.uc_first(resData.invoicing_type);
					$scope.view_division_id = resData.division_id;
					$scope.view_sale_executive = resData.name;
					$scope.view_discount_type = $scope.uc_first(resData.discount_type);
					$scope.view_discount_value = resData.discount_value;
					$scope.view_mfg_lic_no = resData.mfg_lic_no;
					$scope.view_customer_vat_cst = resData.customer_vat_cst;
					$scope.view_ownership_type = resData.ownership_name;
					$scope.view_company_type = resData.company_type_name;
					if (resData.owner_name) { $scope.view_owner_name = $scope.uc_first(resData.owner_name); }
					$scope.view_customer_pan_no = resData.customer_pan_no;
					$scope.view_customer_tan_no = resData.customer_tan_no;
					$scope.view_customer_gst_no = resData.customer_gst_no;
					$scope.view_customer_priority = resData.sample_priority_name;
					$scope.view_bank_account_no = resData.bank_account_no;
					if (resData.bank_account_name) { $scope.view_bank_account_name = $scope.uc_first(resData.bank_account_name); }
					$scope.view_bank_name = resData.bank_name;
					if (resData.bank_branch_name) { $scope.view_bank_branch_name = $scope.uc_first(resData.bank_branch_name); }
					$scope.view_bank_rtgs_ifsc_code = resData.bank_rtgs_ifsc_code;
					$scope.view_contact_name1 = $scope.uc_first(resData.contact_name1);
					$scope.view_contact_designate1 = $scope.uc_first(resData.contact_designate1);
					$scope.view_contact_mobile1 = resData.contact_mobile1;
					$scope.view_contact_email1 = resData.contact_email1;
					if (resData.contact_name2) { $scope.view_contact_name2 = $scope.uc_first(resData.contact_name2); }
					if (resData.contact_designate2) { $scope.view_contact_designate2 = $scope.uc_first(resData.contact_designate2); }
					if (resData.contact_mobile2) { $scope.view_contact_mobile2 = resData.contact_mobile2; }
					if (resData.contact_email2) { $scope.view_contact_email2 = resData.contact_email2; }
				} else {
					$scope.errorMsgShowPopup(data.error);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				$scope.loaderMainHide();
				$scope.clearConsole();
				if (status == '500' || status == '400') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	$scope.uc_first = function (string) {
		return string;
	};
	$scope.addCustomerForm = function () {
		$scope.generateDefaultCode();
		$scope.editFormDiv = true;
		$scope.listCustomer = true;
		$scope.addFormDiv = false;
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		$scope.customer = {};
		$scope.customerForm.$setUntouched();
		$scope.customerForm.$setPristine();
	};
	$scope.showEditForm = function () {
		$scope.editFormDiv = false;
		$scope.listCustomer = true;
		$scope.addFormDiv = true;
		$scope.viewCustomerDiv = true;
	};
	$scope.hideEditForm = function () {
		$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		$scope.editFormDiv = true;
		$scope.listCustomer = false;
		$scope.addFormDiv = true;
		$scope.viewCustomerDiv = true;
	};
	$scope.hideAddForm = function () {
		$scope.editFormDiv = true;
		$scope.listCustomer = false;
		$scope.addFormDiv = true;
		$scope.viewCustomerDiv = true;
		$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
	};
	$scope.resetForm = function () {
		$scope.division_id = { selectedOption: null };
		$scope.sale_executive = { selectedOption: null };
		$scope.customer_state = { selectedOption: null };
		$scope.customer = {};
		$scope.customerForm.$setUntouched();
		$scope.customerForm.$setPristine();
	};
	/***************** customer SECTION END HERE *****************/

	/***************** customer Upload SEction Start Here *****************/
	$scope.showUploadForm = function () {
		$scope.cusomersListHeader = '';
		$scope.cusomersListData = '';
		angular.element('#customerCSVForm')[0].reset();
		angular.element('#uploadCustomerPreviewListing').hide();
		angular.element('#uploadCustomerForm').show();
	};
	$scope.hideUploadForm = function () {
		angular.element('#uploadCustomerPreviewListing').show();
		angular.element('#uploadCustomerForm').hide();
	};
	$scope.cancelUpload = function () {
		$scope.cusomersListHeader = '';
		$scope.cusomersListData = '';
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		angular.element('#customerCSVForm')[0].reset();
		angular.element('#uploadCustomerPreviewListing').hide();
		angular.element('#uploadCustomerForm').show();
	};

	//function is used to upload customer csv in preview table
	$(document).on('click', '#customerUploadPreviewBtn', function (e) {
		$scope.loaderShow();
		e.preventDefault();
		var formdata = new FormData();
		formdata.append('customer', $('#customerFile')[0].files[0]);
		$.ajax({
			url: BASE_URL + "customers/upload-preview",
			type: "POST",
			data: formdata,
			contentType: false,
			cache: false,
			processData: false,
			success: function (res) {
				var resData = res.returnData; //alert(resData.error);
				if (resData.success) {
					$scope.cusomersListHeader = resData.newheader;
					$scope.cusomersListDataDisplay = resData.dataDisplay;
					$scope.cusomersListDataSubmit = resData.dataSubmit;
					$scope.numberOfSubmitedRecords = resData.numberOfSubmitedRecords;
					$scope.hideUploadForm();
					$scope.successMsgShow(resData.success);
					$scope.$apply();
					$scope.clearConsole();
					$scope.loaderHide();
				} else if (resData.error) {
					$scope.errorMsgShow(resData.error);
					$scope.$apply();
					$scope.clearConsole();
					$scope.loaderHide();
				}
			}
		});
	});

	$scope.customerUploadCSV = function () {
		$scope.loaderShow();
		// post all form data to save
		$http.post(BASE_URL + "customer/save-uploaded-customers", {
			data: { formData: $scope.cusomersListDataSubmit },
		}).success(function (res, status, headers, config) {
			var resData = res.returnData;
			if (resData.success) {
				$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
				$scope.showUploadForm();
				if (resData.upload) {
					$scope.hideAlertMsg();
					$scope.uploadMsgShow(resData.upload);
				} else {
					$scope.successMsgShow(resData.success);
				}
			} else {
				$scope.errorMsgShow(resData.error);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	/***************** customer Upload SEction SECTION END HERE *****************/

	/****************** CUSTOMER EMAIL ADDRESSES ************************/
	var globalCustomerID;
	$scope.funShowCustomerEmailAddressesForm = function (customerId, divisionId) {
		globalCustomerID = customerId;
		$scope.divisionID = divisionId;
		$scope.customerID = customerId;
		$scope.fungetCustomerEmailAddresses(customerId);
		$scope.showCustomerEmailSection = true;
		$scope.showCustomerSection = true;
		$scope.isVisibleEmailListDiv = true;
		$scope.addEmailFormDiv = false;
		$scope.editFormDiv = true;
		$scope.listCustomer = true;
		$scope.addFormDiv = true;
		$scope.viewCustomerDiv = true;
		$scope.editEmailFormDiv = false;
	};
	/************* GET CUSTOMER EMAILS LIST*****/
	$scope.fungetCustomerEmailAddresses = function (customer_id) {
		$scope.loaderMainShow();
		$http.post(BASE_URL + "master/customer/get-email-addresses/" + customer_id, {
		}).success(function (resData, status, headers, config) {
			$scope.customerData = resData.getCustomerData;
			$scope.loaderMainHide();
			$scope.customerEmailLists = resData.returnData;
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});
	};

	/************* BACK TO CUSTOMER LISTING *****/
	$scope.backButton = function () {
		$scope.addEmailFormDiv = true;
		$scope.isVisibleEmailListDiv = false;
		$scope.showCustomerEmailSection = false;
		$scope.listCustomer = false;
		$scope.showCustomerSection = true;

	};

	/************* CLOSE EDIT EMAIL FORM*****/
	$scope.closeButton = function () {
		$scope.addEmailFormDiv = false;
		$scope.editEmailFormDiv = false;
	};

	/************* RESET ADD FORM*****/
	$scope.resetButton = function () {
		$scope.addCustomerEmails = {};
	};

	/************* SAVE CUSTOMER MULTIPLE MAILS *****/
	$scope.funSaveCustomerEmailAddresses = function () {
		$scope.loaderMainShow();
		$http.post(BASE_URL + "master/customer/save-email-addresses", {
			data: { formData: $(erpCustomerEmailAddressesForm).serialize() },
		}).success(function (resData, status, headers, config) {
			if (resData.returnData.success) {
				$scope.addCustomerEmails = {};
				$scope.erpCustomerEmailAddressesForm.$setUntouched();
				$scope.erpCustomerEmailAddressesForm.$setPristine();
				$scope.fungetCustomerEmailAddresses(globalCustomerID);
				$scope.successMsgShow(resData.returnData.success);
			} else {
				$scope.errorMsgShow(resData.returnData.error);
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

	/************* SHOW CUSTOMER EMAIL EDIT FORM*****/
	$scope.funShowEditCustomerEmailAddresses = function (customerId, customerEmailId) {

		$scope.isVisibleEmailListDiv = true;
		$scope.editEmailFormDiv = true;
		$scope.addEmailFormDiv = true;
		$scope.customerEmailID = customerEmailId;
		$scope.customerID = customerId;
		$scope.loaderMainShow();

		$http.post(BASE_URL + "master/customer/edit-email-addresses/" + customerId + '/' + customerEmailId, {
		}).success(function (resData, status, headers, config) {
			$scope.editCustomerEmails = resData.getDetail;
			$scope.editCustomerEmails.customer_email_type = { selectedOption: { id: $scope.editCustomerEmails.customer_email_type } };
			$scope.editCustomerEmails.customer_email_status = { selectedOption: { id: $scope.editCustomerEmails.customer_email_status } };
			$scope.loaderMainHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});
	};

	/************* FUNCTION TO UPDATE CUSTOMER EMAIL****/
	$scope.funUpdateCustomerEmailAddresses = function (customer_id, customer_email_id) {
		$scope.loaderMainShow();
		$http.post(BASE_URL + "master/customer/update-email-address", {
			data: { formData: $(erpCustomerEmailEditAddressesForm).serialize() },
		}).success(function (resData, status, headers, config) {
			if (resData.returnData.success) {
				$scope.fungetCustomerEmailAddresses(customer_id);
				$scope.successMsgShow(resData.returnData.success);
			} else {
				$scope.errorMsgShow(resData.returnData.error);
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

	/************* FUNCTION TDELETE CUSTOMER MAIL****/
	$scope.funDeleteCustomerEmail = function (customer_email_id) {
		$scope.loaderMainShow();
		$http.post(BASE_URL + "master/customer/delete-email-address", {
			data: { id: customer_email_id },
		}).success(function (resData, status, headers, config) {
			if (resData.returnData.success) {
				$scope.successMsgShow(resData.returnData.success);
				$scope.fungetCustomerEmailAddresses(globalCustomerID);
			} else {
				$scope.errorMsgShow(resData.returnData.error);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderMainHide();
		});
	};

	//*************************Fuctions for GST Implementation****************************************
	$scope.funSetGstTypeGstTaxSlabInputTypeField = function () {
		$scope.customer.customer_gst_type_id = null;
		$scope.customer.customer_gst_no = null;
		$scope.customer.customer_gst_tax_slab_type_id = null;
	};
	$scope.funSetGstNumberValueForInputField = function (customer_gst_type_obj) {
		if (customer_gst_type_obj.id && customer_gst_type_obj.name) {
			if (customer_gst_type_obj.id != '4') {
				$scope.customer.customer_gst_no = customer_gst_type_obj.name;
			} else {
				$scope.customer.customer_gst_no = null;
			}
		}
	};
	$scope.funEditSetGstTypeGstTaxSlabInputTypeField = function () {
		$scope.customer_gst_type_id1 = null;
		$scope.customer_gst_no1 = null;
		$scope.customer_gst_tax_slab_type_id1 = null;
	};
	$scope.funEditSetGstNumberValueForInputField = function (customer_gst_type_obj) {
		if (customer_gst_type_obj.id && customer_gst_type_obj.name) {
			if (customer_gst_type_obj.id != '4') {
				$scope.customer_gst_no1 = customer_gst_type_obj.name;
			} else {
				$scope.customer_gst_no1 = null;
			}
		}
	};
	//************************/Fuctions for GST Implementation****************************************

	//************************Function Hold/Unhold*****************************************************
	$scope.funOpenCustomerHoldPopup = function (divid, action, customerId, customerName) {
		$scope.hideAlertMsgPopup();
		$scope.customerOnHold = {};
		$scope.erpHoldCustomerPopupForm.$setUntouched();
		$scope.erpHoldCustomerPopupForm.$setPristine();
		$scope.customerOnHoldList = [];
		$scope.customerOnHold.chd_customer_id = customerId;
		$scope.customerOnHold.chd_customer_name = customerName;
		$("#" + divid).modal(action);
		$scope.funGetHoldCustomerDtl(customerId);
	};
	$scope.funGetHoldCustomerDtl = function (chd_customer_id) {
		var http_para_string = { chd_customer_id: chd_customer_id };
		$http({
			url: BASE_URL + "master/customer/get-hold-customer-dtl",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.customerOnHoldList = result.customerOnHoldList;
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
	$scope.funHoldCustomer = function () {
		$scope.loaderMainShow();
		var http_para_string = { formData: $(erpHoldCustomerPopupForm).serialize() };
		$http({
			url: BASE_URL + "master/customer/hold-customer",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == '1') {
				$(erpCustomerOnHoldPopupDivId).modal('hide');
				$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShowPopup(result.message);
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
	$scope.funConfirmUnholdMessage = function (chd_customer_id, chd_customer_status) {
		$ngConfirm({
			title: false,
			content: 'Are you sure you want to perform this action?', //Defined in message.js and included in head
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
						$scope.funUnholdCustomer(chd_customer_id, chd_customer_status);
					}
				},
				cancel: {
					text: 'cancel',
					btnClass: 'btn-default ng-confirm-closeIcon'
				}
			}
		});
	};
	$scope.funUnholdCustomer = function (chd_customer_id, chd_customer_status) {
		$scope.loaderMainShow();
		var http_para_string = { chd_customer_id: chd_customer_id, chd_customer_status: chd_customer_status };
		$http({
			url: BASE_URL + "master/customer/unhold-customer",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == '1') {
				$scope.funGetCustomers($scope.limitFrom, $scope.limitTo);
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//***********************/Function Hold/Unhold***************************************

	//**********Uploading Sales Executive File Detail***********************************
	$scope.erpCustomerSalesExecutiveFileUploadFormValidator = false;
	var formdata = new FormData();
	$scope.getCustomerSalesExcutiveTheFiles = function ($files) {
		angular.forEach($files, function (value, key) {
			formdata.append('fileData', value);
			$timeout(function () { $scope.erpCustomerSalesExecutiveFileUploadFormValidator = true; }, 100);
		});
	};

	$scope.funUploadSalesExecutivesCsv = function () {
		if (!angular.element("#sales_executives_csv").val()) {
			$scope.errorMsgShowPopup(noFileSelected);
		} else {
			$scope.loaderMainShow();
			$scope.hideAlertMsgPopup();
			$http({
				url: BASE_URL + 'customers/upload-sales-excutives-csv',
				method: "POST",
				headers: { 'Content-Type': undefined },
				data: formdata,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					angular.element("#sales_executives_csv").val('');
					$scope.customerSalesExecutiveFileUploadForm = {};
					$scope.erpCustomerSalesExecutiveFileUploadForm.$setUntouched();
					$scope.erpCustomerSalesExecutiveFileUploadForm.$setPristine();
					$('#upload_sales_executives_csv_modal').modal('hide');
					$scope.successMsgShow(result.message);
				} else {
					angular.element("#sales_executives_csv").val('');
					$scope.errorMsgShowPopup(result.message);
				}
				$scope.resetFileInputData();
				$scope.erpCustomerSalesExecutiveFileUploadFormValidator = false;
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

}).directive('ngFiles', ['$parse', function ($parse) {
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