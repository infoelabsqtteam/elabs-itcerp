app.controller('creditNotesController', function ($scope, $http, BASE_URL, $ngConfirm, $timeout) {

	//define empty variables
	$scope.prodata = '';
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.selected = '';
	$scope.searchKeyword = '';
	$scope.divisions = [];
	$scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.downloadType = '1';

	//sorting variables
	$scope.sortType = 'credit_note_id';    	 // set the default sort type
	$scope.sortReverse = false;         	 // set the default sort order
	$scope.searchFish = '';    		 // set the default search/filter term

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
	$scope.listCreditNoteFormBladeDiv = false;
	$scope.addCreditNoteFormBladeDiv = true;
	$scope.editCreditNoteFormBladeDiv = true;
	$scope.IsViewAutoCreditNoteDetail = false;
	$scope.IsViewManualCreditNoteDetail = false;
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
	$scope.funConfirmDeleteMessage = function (id, divisionId) {
		$ngConfirm({
			title: false,
			content: defaultDeleteMsg, //Defined in message.js and included in head
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
						$scope.funDeleteCreditNote(id, divisionId);
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
	$scope.navigateCreditNotePage = function () {
		if (!$scope.addCreditNoteFormBladeDiv) {
			$scope.addCreditNoteFormBladeDiv = true;
			$scope.editCreditNoteFormBladeDiv = true;
			$scope.listCreditNoteFormBladeDiv = false;
			$scope.IsViewAutoCreditNoteDetail = false;
			$scope.IsViewManualCreditNoteDetail = false;
		} else if (!$scope.editCreditNoteFormBladeDiv) {
			$scope.addCreditNoteFormBladeDiv = true;
			$scope.editCreditNoteFormBladeDiv = true;
			$scope.listCreditNoteFormBladeDiv = false;
			$scope.IsViewAutoCreditNoteDetail = false;
			$scope.IsViewManualCreditNoteDetail = false;
		} else {
			$scope.listCreditNoteFormBladeDiv = true;
			$scope.editCreditNoteFormBladeDiv = true;
			$scope.addCreditNoteFormBladeDiv = false;
			$scope.IsViewAutoCreditNoteDetail = false;
			$scope.IsViewManualCreditNoteDetail = false;
		}
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************

	//**********Back Button*********************************************
	$scope.backButton = function () {
		$scope.addBWCreditNote = {};
		$scope.erpAddBWCreditNoteForm.$setUntouched();
		$scope.erpAddBWCreditNoteForm.$setPristine();
		$scope.editBWCreditNote = {};
		$scope.erpEditBWCreditNoteForm.$setUntouched();
		$scope.erpEditBWCreditNoteForm.$setPristine();
		$scope.IsViewAutoCreditNoteDetail = false;
		$scope.IsViewManualCreditNoteDetail = false;
		$scope.listCreditNoteFormBladeDiv = false;
		$scope.addCreditNoteFormBladeDiv = true;
		$scope.editCreditNoteFormBladeDiv = true;
	};
	//**********/Back Button********************************************

	//**********Reset Button*********************************************
	$scope.resetButton = function () {
		$scope.addBWCreditNote = {};
		$scope.erpAddBWCreditNoteForm.$setUntouched();
		$scope.erpAddBWCreditNoteForm.$setPristine();
		$scope.editBWCreditNote = {};
		$scope.erpEditBWCreditNoteForm.$setUntouched();
		$scope.erpEditBWCreditNoteForm.$setPristine();
	};
	//**********/Reset Button*********************************************

	//**********Clearing Console*********************************************
	$scope.clearConsole = function () {
		if (APPLICATION_MODE) console.clear();
	};
	//*********/Clearing Console*********************************************

	//************code used for sorting list order by fields*****************
	$scope.predicate = 'credit_note_id';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************

	//*****************display Vendor dropdown********************************
	$scope.defaultCreditNoteNumber = '';
	$scope.generateCreditNoteNumber = function () {
		$scope.hideAlertMsg();
		$http({
			method: 'GET',
			url: BASE_URL + 'payments/generate-credit-note-number'
		}).success(function (result) {
			$scope.defaultCreditNoteNumber = result.creditNoteNumber;
			$scope.clearConsole();
		});
	};
	//*****************/display Vendor dropdown*****************

	//*****************display division dropdown start************************
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end*******************************

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
	$scope.vendorListData = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'payments/vendors-list'
	}).success(function (result) {
		$scope.vendorListData = result.vendorListData;
		$scope.clearConsole();
	});
	//*****************/display customer list code dropdown*********************

	//*****************Get Invoice Detail***************************************
	$scope.addBWCreditNote = {};
	$scope.addBWCreditNote.product_category_id = '';
	$scope.addBWCreditNote.division_id = '';
	$scope.funGetInvoiceDetail = function (invoice_id) {
		$http({
			method: 'GET',
			url: BASE_URL + 'payments/credit-notes/get-invoice-department-detail/' + invoice_id
		}).success(function (result) {
			if (result.error == '1') {
				$scope.addBWCreditNote.product_category_id = { id: result.invoiceDetailList.product_category_id }
				$scope.addBWCreditNote.division_id = { id: result.invoiceDetailList.division_id }
			}
			$scope.clearConsole();
		});
	};
	//*****************/Get Invoice Detail***************************************

	//*****************Set/Unset Department,Branch and invoice*******************
	$scope.funGetCreditNoteType = function (credit_note_type_id) {
		$scope.addBWCreditNote.customer_id = '';
		$scope.addBWCreditNote.invoice_number = '';
		$scope.addBWCreditNote.product_category_id = '';
		$scope.addBWCreditNote.division_id = '';
	};
	//*****************/Set/Unset Department,Branch and invoice*******************

	//********************Credit/Debit note Type List Select Box****************
	$scope.creditNoteTypeList = [
		{ id: '1', name: 'Against Invoice' },
		{ id: '2', name: 'Against Fresh Ref. No.' },
	];
	//********************Credit/Debit note Type List Select Box****************

	//********** key Press Handler**********************************************
	$scope.funEnterPressHandler = function (e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			e.stopPropagation();
		}
	};
	//********** key Press Handler**********************************************

	//***************Getting all filter debit Note according to search value************
	$scope.funGetBranchWiseCreditNotesHttpRequest = function () {
		$scope.loaderShow();
		$scope.division_id = $scope.divisionID;
		var http_para_string = { formData: 'keyword=' + $scope.searchKeyword + '&' + 'division_id=' + $scope.division_id + '&' + $(erpCrebditNoteForm).serialize() };
		$http({
			url: BASE_URL + "payments/get-dw-credit-note",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.creditNotesList = result.creditNotesList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	$scope.funGetBranchWiseCreditNotes = function (divisionId) {
		$scope.divisionID = divisionId;
		$scope.searchKeyword = $scope.searchKeyword;
		$scope.funGetBranchWiseCreditNotesHttpRequest();
	};
	var tempCreditNoteSearch;
	$scope.funSearchCreditNote = function (keyword) {
		tempCreditNoteSearch = keyword;
		$timeout(function () {
			if (keyword == tempCreditNoteSearch) {
				$scope.searchKeyword = keyword;
				$scope.funGetBranchWiseCreditNotesHttpRequest();
			}
		}, 1000);
	};
	$scope.multiSearchTr = true;
	$scope.multisearchBtn = false;
	var tempSearchKeyword;
	$scope.getMultiSearch = function (creditSearchKeyword) {
		tempSearchKeyword = creditSearchKeyword;
		$timeout(function () {
			if (tempSearchKeyword == creditSearchKeyword) {
				$scope.searchCreditNote = creditSearchKeyword;
				$scope.funGetBranchWiseCreditNotesHttpRequest();
			}
		}, 1000);
	};
	$scope.closeMultisearch = function (division_id) {
		$scope.multiSearchTr = true;
		$scope.multisearchBtn = false;
		$scope.refreshMultisearch(division_id);
	};

	$scope.refreshMultisearch = function (division_id) {
		$scope.searchCredit = {};
		$scope.funGetBranchWiseCreditNotes(division_id);
		$scope.getMultiSearch($scope.searchCreditNote);
	};

	$scope.openMultisearch = function () {
		$scope.multiSearchTr = false;
		$scope.multisearchBtn = true;
	};
	//**********/Getting all Payment Made***************************************************

	//***************** Adding of Branch Wise CreditNote ********************************
	$scope.funAddBranchWiseCreditNote = function (divisionId) {

		if (!$scope.erpAddBWCreditNoteForm.$valid) return;
		if ($scope.newAddBranchWiseCreditNoteFormflag) return;
		$scope.newAddBranchWiseCreditNoteFormflag = true;
		var formData = $(erpAddBWCreditNoteForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "payments/add-dw-credit-note",
			method: "POST",
			data: { formData: formData }
		}).success(function (data, status, headers, config) {
			$scope.newAddBranchWiseCreditNoteFormflag = false;
			if (data.error == 1) {
				$scope.backButton();
				$scope.funGetBranchWiseCreditNotes(divisionId);
				$scope.generateCreditNoteNumber();
				$scope.successMsgShow(data.message);
			} else {
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newAddBranchWiseCreditNoteFormflag = false;
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Branch Wise CreditNote ***************************

	//**************** editing of Payment Made *************************************
	$scope.funEditCreditNote = function (credit_note_id, divisionId) {
		if (credit_note_id) {
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "payments/view-dw-credit-note/" + credit_note_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.resetButton();
					$scope.listCreditNoteFormBladeDiv = true;
					$scope.addCreditNoteFormBladeDiv = true;
					$scope.editCreditNoteFormBladeDiv = false;
					$scope.editBWCreditNote = result.creditNoteData;

					$scope.funGetInvoiceNumbers(result.creditNoteData.customer_id);

					$scope.editBWCreditNote.division_id = {
						selectedOption: { id: result.creditNoteData.division_id }
					};
					$scope.editBWCreditNote.customer_id = {
						selectedOption: { id: result.creditNoteData.customer_id }
					};
					$scope.editBWCreditNote.invoice_id = {
						selectedOption: { id: result.creditNoteData.invoice_id }
					};
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				$scope.loaderHide();
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************/editing of Payment Made *************************************

	//**************** Updating of Payment Made *************************************
	$scope.funUpdateBranchWiseCreditNote = function (divisionId) {
		if (!$scope.erpEditBWCreditNoteForm.$valid) return;
		var formData = $(erpEditBWCreditNoteForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "payments/update-dw-credit-note",
			method: "POST",
			data: { formData: formData }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.backButton();
				$scope.funGetBranchWiseCreditNotes(divisionId);
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//*************** /Updating of Payment Made *************************************

	//**************** Deleting of Branch Wise Payment Made ***************************
	$scope.funDeleteCreditNote = function (credit_note_id, divisionId) {
		if (credit_note_id) {
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "payments/delete-dw-credit-note/" + credit_note_id,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.funGetBranchWiseCreditNotes(divisionId);
					$scope.generateCreditNoteNumber();
					$scope.successMsgShow(result.message);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				$scope.loaderHide();
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//************** /Deleting of Branch Wise Payment Made *******************************

	/**** get customers invoice numbers*****/
	$scope.funGetInvoiceNumbers = function ($selectedCustomerId) {
		$http({
			method: "POST",
			url: BASE_URL + "payments/credit-notes/get-invoice-numbers/" + $selectedCustomerId,
		}).success(function (result, status, headers, config) {
			$scope.customerInvoiceNumberList = result.credit_note_customer_invoice;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	/****** view credit note details**********************************************/

	//**********display Invoices Acc To Billing Type******************************
	$scope.funViewCreditNoteDetails = function (creditNoteId) {
		if (creditNoteId) {

			$scope.creditNoteId = creditNoteId;
			$scope.IsViewAutoCreditNoteDetail = false;
			$scope.IsViewManualCreditNoteDetail = false;
			$scope.loaderMainShow();

			$http({
				url: BASE_URL + "payments/view-credit-note",
				method: "POST",
				data: { cn_id: creditNoteId }
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					//Assigning all data in a variable
					var invoiceObj = result.creditDetailList;

					//Hiding the Listing Content
					$scope.listCreditNoteFormBladeDiv = true;

					//Viewing of AUto/Manual Credit Notes
					if (invoiceObj.creditNoteHeader.credit_note_type_id == '1') {
						$scope.IsViewAutoCreditNoteDetail = true;
					} else {
						$scope.IsViewManualCreditNoteDetail = true;
					}

					$scope.creditNoteHeaders = invoiceObj.creditNoteHeader;
					$scope.creditNoteBody = invoiceObj.creditNoteBody;
					$scope.creditNoteFooters = invoiceObj.creditNoteFooter;
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
		}
	};
	//*********/display Invoices Acc To Billing Type******************************

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
	}
	//****************/city dropdown on state change*******************************

	//*****************Customer Selection from POPUP WINDOW***************

}).directive('datepicker', function () {
	return {
		require: 'ngModel',
		link: function (scope, el, attr, ngModel) {
			$(el).datepicker({
				onSelect: function (dateText) {
					scope.$apply(function () {
						ngModel.$setViewValue(dateText);
					});
				}
			});
		}
	};
});