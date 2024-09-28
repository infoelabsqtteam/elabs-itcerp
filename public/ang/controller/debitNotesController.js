app.controller('debitNotesController', function ($scope, $http, BASE_URL, $ngConfirm, $timeout) {

	//define empty variables
	$scope.prodata = '';
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.selected = '';
	$scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.searchKeyword = '';

	//sorting variables
	$scope.sortType = 'debit_note_id';    	// set the default sort type
	$scope.sortReverse = false;         	// set the default sort order
	$scope.searchFish = '';    		 // set the default search/filter term
	$scope.downloadType = '1';

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
	$scope.listDebitNoteFormBladeDiv = false;
	$scope.addDebitNoteFormBladeDiv = true;
	$scope.editDebitNoteFormBladeDiv = true;
	$scope.IsViewAutoDebitNoteDetail = false;
	$scope.IsViewManualDebitNoteDetail = false;
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
						$scope.funDeleteDebitNote(id, divisionId);
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
	$scope.navigateDebitNotePage = function () {
		if (!$scope.addDebitNoteFormBladeDiv) {
			$scope.addDebitNoteFormBladeDiv = true;
			$scope.editDebitNoteFormBladeDiv = true;
			$scope.listDebitNoteFormBladeDiv = false;
		} else if (!$scope.editDebitNoteFormBladeDiv) {
			$scope.addDebitNoteFormBladeDiv = true;
			$scope.editDebitNoteFormBladeDiv = true;
			$scope.listDebitNoteFormBladeDiv = false;
		} else {
			$scope.listDebitNoteFormBladeDiv = true;
			$scope.editDebitNoteFormBladeDiv = true;
			$scope.addDebitNoteFormBladeDiv = false;
		}
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************

	//**********Back Button*********************************************
	$scope.backButton = function () {
		$scope.addBWDebitNote = {};
		$scope.erpAddBWDebitNoteForm.$setUntouched();
		$scope.erpAddBWDebitNoteForm.$setPristine();
		$scope.editBWDebitNote = {};
		$scope.erpEditBWDebitNoteForm.$setUntouched();
		$scope.erpEditBWDebitNoteForm.$setPristine();
		$scope.IsViewAutoDebitNoteDetail = false;
		$scope.IsViewManualDebitNoteDetail = false;
		$scope.IsViewManualDebitNoteDetail = false;
		$scope.listDebitNoteFormBladeDiv = false;
		$scope.addDebitNoteFormBladeDiv = true;
		$scope.editDebitNoteFormBladeDiv = true;
	};
	//**********/Back Button********************************************

	//**********Reset Button*********************************************
	$scope.resetButton = function () {
		$scope.addBWDebitNote = {};
		$scope.erpAddBWDebitNoteForm.$setUntouched();
		$scope.erpAddBWDebitNoteForm.$setPristine();
		$scope.editBWDebitNote = {};
		$scope.erpEditBWDebitNoteForm.$setUntouched();
		$scope.erpEditBWDebitNoteForm.$setPristine();
	};
	//**********/Reset Button*********************************************

	//**********Clearing Console*********************************************
	$scope.clearConsole = function () {
		if (APPLICATION_MODE) console.clear();
	};
	//*********/Clearing Console*********************************************

	//************code used for sorting list order by fields*****************
	$scope.predicate = 'debit_note_id';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************

	//********************Credit/Debit note Type List Select Box****************
	$scope.debitNoteTypeList = [
		{ id: '1', name: 'Against Invoice' },
		{ id: '2', name: 'Against Fresh Ref. No.' },
	];
	//********************Credit/Debit note Type List Select Box****************

	//*****************display Vendor dropdown********************************
	$scope.defaultDebitNoteNumber = '';
	$scope.generateDebitNoteNumber = function () {
		$scope.hideAlertMsg();
		$http({
			method: 'GET',
			url: BASE_URL + 'payments/generate-debit-note-number'
		}).success(function (result) {
			$scope.defaultDebitNoteNumber = result.debitNoteNumber;
			$scope.clearConsole();
		});
	};
	//*****************/display Vendor dropdown******************************

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
	$scope.addBWDebitNote = {};
	$scope.addBWDebitNote.product_category_id = '';
	$scope.addBWDebitNote.division_id = '';
	$scope.funGetInvoiceDetail = function (invoice_id) {
		$http({
			method: 'GET',
			url: BASE_URL + 'payments/debit-notes/get-invoice-department-detail/' + invoice_id
		}).success(function (result) {
			if (result.error == '1') {
				$scope.addBWDebitNote.product_category_id = { id: result.invoiceDetailList.product_category_id }
				$scope.addBWDebitNote.division_id = { id: result.invoiceDetailList.division_id }
			}
			$scope.clearConsole();
		});
	};
	//*****************/Get Invoice Detail***************************************

	//*****************Set/Unset Department,Branch and invoice*******************
	$scope.funGetDebitNoteType = function (debit_note_type_id) {
		$scope.addBWDebitNote.customer_id = '';
		$scope.addBWDebitNote.invoice_number = '';
		$scope.addBWDebitNote.product_category_id = '';
		$scope.addBWDebitNote.division_id = '';
	};
	//*****************/Set/Unset Department,Branch and invoice*******************

	//*Getting all Payment Made*********************************************
	$scope.funGetBranchWiseDebitNotes_bk130818 = function (divisionId) {
		$scope.filterDebitNote = {};
		$scope.loaderShow();
		$scope.divisionID = divisionId;
		$scope.filterDebitNote.divisions = {};
		$http({
			url: BASE_URL + "payments/get-dw-debit-note/" + divisionId,
			method: "GET",
		}).success(function (result, status, headers, config) {
			$scope.debitNotesList = result.debitNotesList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**********/Getting all Payment Made*************************************/

	$scope.closeMultisearch = function (division_id) {
		$scope.multiSearchTr = true;
		$scope.multisearchBtn = false;
		$scope.refreshMultisearch(division_id);
	};

	$scope.openMultisearch = function () {
		$scope.multiSearchTr = false;
		$scope.multisearchBtn = true;
	};
	//**************multisearch end here**********************/	

	//***************** Adding of Branch Wise DebitNote **********************
	$scope.funAddBranchWiseDebitNote = function (divisionId) {

		if (!$scope.erpAddBWDebitNoteForm.$valid) return;
		if ($scope.newAddBranchWiseDebitNoteFormflag) return;
		$scope.newAddBranchWiseDebitNoteFormflag = true;
		var formData = $(erpAddBWDebitNoteForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "payments/add-dw-debit-note",
			method: "POST",
			data: { formData: formData }
		}).success(function (data, status, headers, config) {
			$scope.newAddBranchWiseDebitNoteFormflag = false;
			if (data.error == 1) {
				$scope.backButton();
				$scope.funGetBranchWiseDebitNotes(divisionId);
				$scope.generateDebitNoteNumber();
				$scope.successMsgShow(data.message);
			} else {
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newAddBranchWiseDebitNoteFormflag = false;
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Branch Wise DebitNote ***************************

	//**************** editing of Payment Made *************************************
	$scope.funEditDebitNote = function (debit_note_id, divisionId) {
		if (debit_note_id) {
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "payments/view-dw-debit-note/" + debit_note_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					$scope.resetButton();
					$scope.listDebitNoteFormBladeDiv = true;
					$scope.addDebitNoteFormBladeDiv = true;
					$scope.editDebitNoteFormBladeDiv = false;
					$scope.editBWDebitNote = result.debitNoteData;
					$scope.funGetInvoiceNumbers(result.debitNoteData.customer_id);
					$scope.editBWDebitNote.division_id = {
						selectedOption: { id: result.debitNoteData.division_id }
					};
					$scope.editBWDebitNote.customer_id = {
						selectedOption: { id: result.debitNoteData.customer_id }
					};
					$scope.editBWDebitNote.invoice_id = {
						selectedOption: { id: result.debitNoteData.invoice_id }
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
	$scope.funUpdateBranchWiseDebitNote = function (divisionId) {

		if (!$scope.erpEditBWDebitNoteForm.$valid) return;
		var formData = $(erpEditBWDebitNoteForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "payments/update-dw-debit-note",
			method: "POST",
			data: { formData: formData }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.backButton();
				$scope.funGetBranchWiseDebitNotes(divisionId);
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
	$scope.funDeleteDebitNote = function (debit_note_id, divisionId) {
		if (debit_note_id) {
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "payments/delete-dw-debit-note/" + debit_note_id,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.funGetBranchWiseDebitNotes(divisionId);
					$scope.generateDebitNoteNumber();
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

	//**** get customers invoice numbers*****/
	$scope.funGetInvoiceNumbers = function ($selectedCustomerId) {
		$http({
			method: "POST",
			url: BASE_URL + "payments/debit-notes/get-invoice-numbers/" + $selectedCustomerId,
		}).success(function (result, status, headers, config) {
			$scope.customerInvoiceNumberList = result.debit_note_customer_invoice;
			$scope.addBWDebitNote.product_category_id = '';
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});

	};

	//**********display Invoices Acc To Billing Type******************************
	$scope.funViewDebitNoteDetails = function (DebitNoteId) {
		if (DebitNoteId) {

			$scope.debit_note_id = DebitNoteId;
			$scope.IsViewAutoDebitNoteDetail = false;
			$scope.IsViewManualDebitNoteDetail = false;
			$scope.loaderMainShow();

			$http({
				url: BASE_URL + "payments/view-debit-note",
				method: "POST",
				data: { dn_id: DebitNoteId }
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					//Assigning all data in a variable
					var invoiceObj = result.debitDetailList;

					//Hiding the Listing Content
					$scope.listDebitNoteFormBladeDiv = true;

					//Viewing of AUto/Manual Debit Notes
					if (invoiceObj.debitNoteHeader.debit_note_type_id == '1') {
						$scope.IsViewAutoDebitNoteDetail = true;
					} else {
						$scope.IsViewManualDebitNoteDetail = true;
					}

					$scope.debitNoteHeaders = invoiceObj.debitNoteHeader;
					$scope.debitNoteBody = invoiceObj.debitNoteBody;
					$scope.debitNoteFooters = invoiceObj.debitNoteFooter;
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
	$scope.getMultiSearch_bk130818 = function () {

		$scope.searchDebit.search_division_id = $scope.divisionID;
		$scope.loaderShow();

		$http.post(BASE_URL + "payment/debit-notes/get-payment-debits-multisearch", {
			data: { formData: $scope.searchDebit },
		}).success(function (data, status, headers, config) {
			$scope.debitNotesList = data.debitNotesList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '400') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};

	$scope.funGetBranchWiseDebitNotes = function (divisionId) {
		$scope.divisionID = divisionId;
		$scope.searchKeyword = $scope.searchKeyword;
		$scope.funGetBranchWiseDebitNotesHttpRequest();
	};

	$scope.funGetBranchWiseDebitNotesHttpRequest = function () {
		$scope.loaderShow();
		$scope.division_id = $scope.divisionID;
		var http_para_string = { formData: 'keyword=' + $scope.searchKeyword + '&' + 'division_id=' + $scope.division_id + '&' + $(erpDebitNoteForm).serialize() };
		$http({
			url: BASE_URL + "payments/get-dw-debit-note",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.debitNotesList = result.debitNotesList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};

	//********** key Press Handler**********************************************
	$scope.funEnterPressHandler = function (e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			e.stopPropagation();
		}
	};

	//***************Getting all filter debit Note according to search value************
	var tempDebitNoteSearch;
	$scope.funSearchDebitNote = function (keyword) {
		tempDebitNoteSearch = keyword;
		$timeout(function () {
			if (keyword == tempDebitNoteSearch) {
				$scope.searchKeyword = keyword;
				$scope.funGetBranchWiseDebitNotesHttpRequest();
			}
		}, 1000);
	};

	//**************multisearch start here**********************/
	$scope.multiSearchTr = true;
	$scope.multisearchBtn = false;
	var tempSearchKeyword;
	$scope.getMultiSearch = function (debitSearchKeyword) {
		tempSearchKeyword = debitSearchKeyword;
		$timeout(function () {
			if (tempSearchKeyword == debitSearchKeyword) {
				$scope.searchDebitNote = debitSearchKeyword;
				$scope.funGetBranchWiseDebitNotesHttpRequest();
			}
		}, 1000);
	};

	//*** refresh multi search
	$scope.refreshMultisearch = function (divisionID) {
		$scope.searchDebit = '';
		$scope.funGetBranchWiseDebitNotes(divisionID);
		$scope.getMultiSearch($scope.searchDebitNote);
	};

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