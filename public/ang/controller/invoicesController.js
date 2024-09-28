app.controller('invoicesController', function ($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm, $sce) {

	//define empty variables
	$scope.divisionWiseInvoicesList = [];
	$scope.invoices = {};
	$scope.orderData = [];
	$scope.billingTypeOrderList = [];
	$scope.customerInvoicesList = [];
	$scope.purchaseOrderNoList = [];
	$scope.downloadContentList = '';
	$scope.order_id = '';
	$scope.order_no = '';
	$scope.order_date = '';
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.dispatchOrder = {};
	$scope.invoiceCancellation = {};
	$scope.divisionID = '';
	$scope.searchStringID = '';
	$scope.purchaseOrderNoData = [];
	$scope.dispatchInvoiceOrderList = [];
	$scope.currenDivisionID = '';
	$scope.currentProductCategoryID = '';
	$scope.currenBillingTypeID = '';
	$scope.currentCustomerID = '';
	$scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType = 'invoice_id';    // set the default sort type
	$scope.sortReverse = false;           // set the default sort order
	$scope.searchFish = '';    		   // set the default search/filter term
	$scope.countNum = '0';
	$scope.downloadType = '1';
	$scope.filterInvoices = {};
	$scope.keyword = '';
	$scope.billingTypeID = '0';
	$scope.generateInvoiceBtn = true;
	$scope.invoiceRate = false;
	$scope.stateAndCustomerWiseinvoiceRate = false;

	//**********scroll to top function**********
	$scope.moveToMsg = function () {
		$('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
	};
	//**********/scroll to top function**********

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

	//********** key Press Handler**********************************************
	$scope.funEnterPressHandler = function (e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			e.stopPropagation();
		}
	}
	//**********/key Press Handler**********************************************

	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsViewInvoiceListing = false;
	$scope.IsViewInvoiceGenForm = true;
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup = true;
	$scope.IsViewInvoiceOrders = true;
	$scope.IsViewInvoicesList = true;
	$scope.IsViewInvoiceDetail = true;
	$scope.IsEditInvoiceDetail = true;
	$scope.IsNotifySelector = true;
	$scope.dispatchOrderPopupWindow = false;
	$scope.multiSearchTr = true;
	$scope.multisearchBtn = false;
	$scope.viewInvoiceOrderDetail = true;
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

	//**********Open Pop Box******************************************************
	$scope.funOpenBootstrapPopup = function (divid, action, invoiceId, invoiceNo) {
		$scope.dispatchOrder.invoice_id = invoiceId;
		$scope.dispatchOrder.invoice_no = invoiceNo;
		$scope.invoiceCancellation.invoice_id = invoiceId;
		$scope.invoiceCancellation.invoice_no = invoiceNo;
		$("#" + divid).modal(action);
	};
	//********** /Open Pop Box****************************************************

	//**********reset Form****************************************************
	$scope.resetForm = function () {
		$scope.dispatchOrder = {};
		$scope.erpDispatchOrderPopupForm.$setUntouched();
		$scope.erpDispatchOrderPopupForm.$setPristine();
		$scope.hideAlertMsg();
	};

	//**********confirm box******************************************************
	$scope.funConfirmMessage = function (id, subid, message, type) {
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
						if (type == 'listInvoice') {
							$scope.funDeleteInvoice(id, subid);
						} else if (type == 'generateInvoice') {
							$scope.funGenerateInvoices(id, subid);
						} else if (type == 'dispatchOrder') {
							$scope.dispatchOrder(id, subid)
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

	//**********confirm box******************************************************
	$scope.funConfirmInvoiceGenerateMessage = function (message) {
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
						$scope.funGenerateInvoices();
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

	//**********Clearing Console*********************************************
	$scope.clearConsole = function () {
		if (APPLICATION_MODE) console.clear();
	};
	//*********/Clearing Console*********************************************

	//************code used for sorting list order by fields*********************
	$scope.predicate = 'invoice_id';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*********************

	//**********navigate Invoice Form*********************************************
	$scope.navigateInvoiceForm = function (type, division_id) {
		if (type == 1) {
			$scope.IsViewInvoiceListing = true;
			$scope.IsViewInvoiceGenForm = false;
			$scope.invoices = {};
		} else {
			$scope.IsViewInvoiceListing = false;
			$scope.IsViewInvoiceGenForm = true;
			$scope.funViewDivisionWiseInvoices(division_id);
		}
		$scope.IsViewInvoiceOrders = true;
		$scope.IsViewInvoicesList = true;
		$scope.IsViewInvoiceDetail = true;
		$scope.IsEditInvoiceDetail = true;
		$scope.orderData = [];
		$scope.billingTypeOrderList = [];
		$scope.purchaseOrderNoList = [];
		$scope.purchaseOrderNoData = [];
		$scope.billingTypeCustomerList = [];
		$scope.erpCreateInvoiceForm.$setUntouched();
		$scope.erpCreateInvoiceForm.$setPristine();
		$scope.hideAlertMsg();
	};
	//**********/navigate Invoice Form*********************************************

	//**********backButton****************************************************
	$scope.backButton = function (type) {
		if (type == 1) {
			$scope.IsViewInvoiceListing = false;
			$scope.IsViewInvoiceGenForm = true;
			$scope.IsViewInvoiceOrders = true;
			$scope.IsViewInvoicesList = true;
			$scope.IsViewInvoiceDetail = true;
			$scope.IsEditInvoiceDetail = true;
			$scope.viewInvoiceOrderDetail = true;
		} else {
			$scope.IsViewInvoiceListing = true;
			$scope.IsViewInvoiceGenForm = false;
			$scope.IsViewInvoiceOrders = true;
			$scope.IsViewInvoicesList = false;
			$scope.IsEditInvoiceDetail = true;
			$scope.IsViewInvoiceDetail = true;
			$scope.viewInvoiceOrderDetail = true;
		}
		$scope.hideAlertMsg();
	};
	//**********backButton***************************************************

	//*****************display division dropdown start*****************
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end*****************

	//*****************display Billing Type dropdown**********************
	$scope.billingTypeList = [];
	$scope.funListBillingType = function () {
		$http({
			method: 'GET',
			url: BASE_URL + 'invoices/customer_billing_type_list'
		}).success(function (result) {
			$scope.billingTypeList = result.billingTypes;
		});
	};
	//*****************/display Billing Type dropdown*********************

	//*****************display parent category dropdown code dropdown start****
	$scope.parentCategoryList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'scheduling/get-user-parent-product-category'
	}).success(function (result) {
		$scope.parentCategoryList = result.parentCategoryList;
		$scope.clearConsole();
	});
	//*****************display parent category code dropdown end*****************

	//*****************display customer list dropdown******************************
	$scope.funGetBillingTypeCustomerList = function () {
		$scope.IsViewInvoiceOrders = true;
		$scope.orderData = [];
		$scope.billingTypeOrderList = [];
		$scope.purchaseOrderNoList = [];
		$scope.purchaseOrderNoData = [];
		$scope.billingTypeCustomerList = [];
		$scope.invoices.customer_id = {};
		$scope.currenDivisionID = '';
		$scope.currentProductCategoryID = '';
		$scope.currenBillingTypeID = '';
		$scope.currentCustomerID = '';
		$scope.hideAlertMsg();
		$scope.loaderShow();
		$http({
			method: 'POST',
			url: BASE_URL + 'invoices/get_btype_customers_list',
			data: { formData: $(erpCreateInvoiceForm).serialize() }
		}).success(function (result) {
			$scope.billingTypeCustomerList = result.billingTypeCustomerList;
			$scope.IsViewInvoiceOrders = true;
			$scope.IsViewInvoicesList = true;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//*****************/display customer list code dropdown*********************

	//*********************get Report Url***************************************
	$scope.getReportUrl = function (report_name) {
		return $sce.trustAsResourceUrl(BASE_URL + 'public/images/sales/reports/' + report_name);
	};
	//*********************/get Report Url***************************************

	//**********display Invoices Acc To Billing Type******************************
	$scope.funGetDivisionWiseInvoicesHttpRequest = function () {

		$scope.loaderShow();
		var http_para_string = { formData: $(erpFilterInvoicesForm).serialize() };

		$http({
			method: "POST",
			url: BASE_URL + "invoices/get_all_invoice_list",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.divisionWiseInvoicesList = result.invoiceList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};

	$scope.funViewDivisionWiseInvoices = function (divisionId) {
		$scope.divisionID = divisionId;
		$scope.searchStringID = $scope.searchStringID;
		$scope.funGetDivisionWiseInvoicesHttpRequest();
	};

	$scope.funFilterInvoiceByStatus = function () {
		$scope.funGetDivisionWiseInvoicesHttpRequest();
	};

	var tempInvoiceSearchTerm;
	$scope.funFilterInvoiceSearch = function (keyword) {
		tempInvoiceSearchTerm = keyword;
		$timeout(function () {
			if (keyword == tempInvoiceSearchTerm) {
				$scope.searchStringID = keyword;
				$scope.funGetDivisionWiseInvoicesHttpRequest();
			}
		}, 800);
	};

	$scope.funRefreshInvoiceList = function () {
		$scope.divisionID = '';
		$scope.searchStringID = '';
		$scope.filterInvoices = {};
		$scope.erpFilterInvoicesForm.$setUntouched();
		$scope.erpFilterInvoicesForm.$setPristine();
		$timeout(function () {
			$scope.funGetDivisionWiseInvoicesHttpRequest();
		}, 500);
	};

	var tempSearchKeyword;
	$scope.getMultiSearch = function (invoiceSearchKeyword) {
		tempSearchKeyword = invoiceSearchKeyword;
		$timeout(function () {
			$scope.searchInvoice = invoiceSearchKeyword;
			if (tempSearchKeyword == invoiceSearchKeyword) {
				$scope.funGetDivisionWiseInvoicesHttpRequest();
			}
		}, 1000);
	};

	$scope.closeMultisearch = function () {
		$scope.multiSearchTr = true;
		$scope.multisearchBtn = false;
		$scope.refreshMultisearch();
	};

	$scope.refreshMultisearch = function (division_id) {
		$scope.filterInvoices = {};
		$scope.funViewDivisionWiseInvoices(division_id);
		$scope.getMultiSearch($scope.searchInvoice);
	};

	$scope.openMultisearch = function () {
		$scope.multiSearchTr = false;
		$scope.multisearchBtn = true;
	};
	//*********/display Invoices Acc To Billing Type*****************************

	//**********display Orders Acc To Billing Type*******************************
	$scope.funDisplayOrdersAccToBillingType = function () {

		if (!$scope.erpCreateInvoiceForm.$valid) return;
		if ($scope.newInvoiceFormflag) return;
		$scope.IsViewInvoiceListing = true;
		$scope.newInvoiceFormflag = true;
		$scope.IsViewInvoiceOrders = true;
		$scope.IsViewInvoicesList = true;
		$scope.IsEditInvoiceDetail = true;
		$scope.IsViewInvoiceDetail = true;
		$scope.hasGenerateInvoiceButton = false;
		$scope.generateInvoiceBtn = true;
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "invoices/list_orders_acct_billing_type",
			method: "POST",
			data: { formData: $(erpCreateInvoiceForm).serialize() }
		}).success(function (result, status, headers, config) {
			$scope.newInvoiceFormflag = false;
			if (result.error == 1) {
				$scope.IsViewInvoiceOrders = false;
				$scope.selectedAll = false;
				$scope.selected = false;
				$scope.generateInvoiceForm.$setUntouched();
				$scope.generateInvoiceForm.$setPristine();
				angular.element('#selectedAll,.orderCheckBox').prop('checked', false);
				$scope.baseUrl = BASE_URL + 'public/images/sales/reports/';
				$scope.billingTypeOrderList = result.customerBillingTypeOrders;
				$scope.currenDivisionID = result.division_id;
				$scope.currentProductCategoryID = result.product_category_id;
				$scope.currenBillingTypeID = result.billing_type;
				$scope.currentCustomerID = result.customer_id;
				$scope.hasGenerateInvoiceButton = result.hasGenerateInvoiceButton;
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newInvoiceFormflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*********/display Orders Acc To Billing Type*******************************

	//**********Invoce Generation according to Billing Type**********************
	$scope.funGenerateInvoices = function () {

		if ($scope.newInvoiceGenerateflag) return;
		$scope.IsViewInvoiceListing = true;
		$scope.newInvoiceGenerateflag = true;
		$scope.IsViewInvoicesList = true;
		$scope.IsViewInvoiceDetail = true;
		$scope.IsEditInvoiceDetail = true;
		$scope.isViewInvoiceReport = true;
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "invoices/generate_invoices",
			method: "POST",
			data: { formData: $(generateInvoiceForm).serialize() }
		}).success(function (result, status, headers, config) {
			$scope.newInvoiceGenerateflag = false;
			if (result.error == 1) {
				$scope.IsViewInvoiceOrders = true;
				angular.element('#selectedAll,.orderCheckBox,.po_orders_no').prop('checked', false);
				$scope.orderData = [];
				$scope.billingTypeOrderList = [];
				$scope.purchaseOrderNoList = [];
				$scope.funGetBillingTypeCustomerList();
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newInvoiceGenerateflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/Invoce Generation according to Billing Type**********************

	//**********display Invoices Acc To Billing Type******************************
	$scope.funInnerViewInvoice = function (invoiceNumber, invoice_id, type, arraylength) {
		if (invoice_id) {
			$http({
				url: BASE_URL + "invoices/view_invoice_detail/" + invoice_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1 && result.invoiceDetailList) {

					//Invoice Header
					$scope.invoiceObj = {};
					$scope.invoiceId = '';
					$scope.invoiceObj = result.invoiceDetailList;
					$scope.invoiceId = invoice_id;
					$scope.invoiceNo = $scope.invoiceObj.invoiceHeader.invoice_no;
					$scope.customerAddress = $scope.invoiceObj.invoiceHeader.customer_address;
					$scope.invoiceDate = $scope.invoiceObj.invoiceHeader.invoice_date;
					$scope.customerAddress = $scope.invoiceObj.invoiceHeader.customer_address;
					$scope.order_no = $scope.invoiceObj.invoiceHeader.order_no;
					$scope.billingType = $scope.invoiceObj.invoiceHeader.billing_type;

					//Invoice Body
					$scope.invoiceBody = $scope.invoiceObj.invoiceBody;

					//Invoice Footer
					$scope.total = $scope.invoiceObj.invoiceFooter.total;
					$scope.discount = $scope.invoiceObj.invoiceFooter.discount;
					$scope.sgstRate = $scope.invoiceObj.invoiceFooter.sgst_rate;
					$scope.sgstAmount = $scope.invoiceObj.invoiceFooter.sgst_amount;
					$scope.cgstRate = $scope.invoiceObj.invoiceFooter.cgst_rate;
					$scope.cgstAmount = $scope.invoiceObj.invoiceFooter.cgst_amount;
					$scope.igstRate = $scope.invoiceObj.invoiceFooter.igst_rate;
					$scope.igstAmount = $scope.invoiceObj.invoiceFooter.igst_amount;
					$scope.netTotal = $scope.invoiceObj.invoiceFooter.net_total;
					$scope.netTotalInWord = $scope.invoiceObj.invoiceFooter.net_total_in_words;

					$scope.IsViewInvoiceGenForm = false;
					$scope.IsViewInvoiceDetail = false;
					$scope.IsEditInvoiceDetail = true;
					$scope.generateInvoicePDF($scope.invoiceNo, $scope.invoiceNo, $scope.invoiceId, arraylength);
					$scope.backTypeValue = type;
				} else {
					$scope.errorMsgShow(result.message);
				}
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

	//**********display Invoices Acc To Billing Type******************************
	$scope.funDisplayInvoicesAccToBillingType = function () {

		if (!$scope.erpCreateInvoiceForm.$valid) return;
		if ($scope.newListInvoiceFormflag) return;
		$scope.IsViewInvoiceListing = true;
		$scope.newListInvoiceFormflag = true;
		$scope.IsViewInvoiceOrders = true;
		$scope.IsViewInvoicesList = true;
		$scope.IsViewInvoiceDetail = true;
		$scope.IsEditInvoiceDetail = true;
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "invoices/list_invoices_acct_billing_type",
			method: "POST",
			data: { formData: $(erpCreateInvoiceForm).serialize() }
		}).success(function (result, status, headers, config) {
			$scope.newListInvoiceFormflag = false;
			if (result.error == 1) {
				$scope.customerInvoicesList = result.customerInvoicesList;
				$scope.IsViewInvoicesList = false;
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newListInvoiceFormflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*********/display Invoices Acc To Billing Type******************************	

	//**********display Invoices Acc To Billing Type******************************
	$scope.funSelectViewInvoiceType = function (invoiceId, invoiceNo, type) {
		$ngConfirm({
			title: 'Invoice No. : ' + invoiceNo,
			content: 'Click on View Type!',
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: true,
			theme: 'bootstrap',
			columnClass: 'col-sm-5 col-md-offset-3',
			autoClose: 'OK|1900',
			buttons: {
				OK: {
					text: 'Normal',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funViewInvoice(invoiceId, type, 1);
					}
				},
				cancel: {
					text: 'State Wise',
					btnClass: 'btn-success',
					action: function () {
						$scope.funViewInvoice(invoiceId, type, 2);
					}
				}
			}
		});
	};
	$scope.funViewInvoice = function (invoice_id, type, invoiceTemplateType) {
		if (invoice_id) {

			$scope.hideAlertMsg();
			$scope.loaderMainShow();

			$http({
				url: BASE_URL + "invoices/view_invoice_detail/" + invoice_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					//Showing Div
					$scope.invoiceTemplateTypeDiv = invoiceTemplateType;
					$scope.IsViewInvoiceListing = true;
					$scope.IsViewInvoiceGenForm = true;
					$scope.IsViewInvoiceOrders = true;
					$scope.IsViewInvoicesList = true;
					$scope.IsEditInvoiceDetail = true;
					$scope.IsViewInvoiceDetail = false;

					//Invoice Header
					var invoiceObj = result.invoiceDetailList;
					$scope.invoiceId = invoice_id;
					$scope.invoiceNo = invoiceObj.invoiceHeader.invoice_no;
					$scope.divisionName = invoiceObj.invoiceHeader.division_name;
					$scope.pCategoryName = invoiceObj.invoiceHeader.p_category_name;
					$scope.customerName = invoiceObj.invoiceHeader.customer_name;
					$scope.customerStateName = invoiceObj.invoiceHeader.customer_state_name;
					$scope.customerCityName = invoiceObj.invoiceHeader.customer_city_name;
					$scope.customerGstNo = invoiceObj.invoiceHeader.customer_gst_no;
					$scope.customerAddress = invoiceObj.invoiceHeader.customer_address;
					$scope.invoiceDate = invoiceObj.invoiceHeader.invoice_date;
					$scope.invoiceFileName = invoiceObj.invoiceHeader.invoice_file_name;
					$scope.invoiceFileNameWithouthf = invoiceObj.invoiceHeader.invoice_file_name_without_hf;
					$scope.order_no = invoiceObj.invoiceHeader.order_no;
					$scope.billingType = invoiceObj.invoiceHeader.billing_type;
					$scope.invoiceBy = invoiceObj.invoiceHeader.invoice_by;
					$scope.userSignPath = invoiceObj.invoiceHeader.user_sign_path;
					$scope.InvoicerSign = invoiceObj.invoiceHeader.user_signature;
					$scope.headerContent = invoiceObj.invoiceHeader.header_content;
					$scope.footerContent = invoiceObj.invoiceHeader.footer_content;
					$scope.invoiceTemplateType = invoiceObj.invoiceHeader.invoice_template_type;

					//Invoice Body
					$scope.invoiceBody = invoiceObj.invoiceBody;

					//Invoice Footer
					$scope.total = invoiceObj.invoiceFooter.total;
					$scope.discount = invoiceObj.invoiceFooter.discount;
					$scope.discountText = invoiceObj.invoiceFooter.discount_text;
					$scope.netAmount = invoiceObj.invoiceFooter.net_amount;
					$scope.surchargeAmount = invoiceObj.invoiceFooter.surcharge_amount;
					$scope.extraAmount = invoiceObj.invoiceFooter.extra_amount;
					$scope.sgstRate = invoiceObj.invoiceFooter.sgst_rate;
					$scope.sgstAmount = invoiceObj.invoiceFooter.sgst_amount;
					$scope.cgstRate = invoiceObj.invoiceFooter.cgst_rate;
					$scope.cgstAmount = invoiceObj.invoiceFooter.cgst_amount;
					$scope.igstRate = invoiceObj.invoiceFooter.igst_rate;
					$scope.igstAmount = invoiceObj.invoiceFooter.igst_amount;
					$scope.netTotalWsw = invoiceObj.invoiceFooter.net_total_wsw;
					$scope.netTotalInWordWsw = invoiceObj.invoiceFooter.net_total_in_words_wsw;
					$scope.netTotal = invoiceObj.invoiceFooter.net_total;
					$scope.netTotalInWord = invoiceObj.invoiceFooter.net_total_in_words;
					$scope.backTypeValue = type;		//back button type
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

	//**********Viewing of Order*************************************************
	$scope.funShowReport = function (order_id) {
		if (order_id) {
			$scope.hideAlertMsg();
			$scope.loaderMainShow();
			$http({
				url: BASE_URL + "sales/invoice/view_order/" + order_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.viewOrderReport = result.orderList;
					$scope.orderParametersList = result.orderParameterList;
					if ($scope.viewOrderReport.invoicing_type_id == '1' || $scope.viewOrderReport.invoicing_type_id == '4') {
						$scope.invoiceRate = true;
						$scope.stateAndCustomerWiseinvoiceRate = false;
					} else if ($scope.viewOrderReport.invoicing_type_id == '3' || $scope.viewOrderReport.invoicing_type_id == '2') {
						$scope.stateAndCustomerWiseinvoiceRate = true;
						$scope.invoiceRate = false;
					} else {
						$scope.invoiceRate = false;
						$scope.stateAndCustomerWiseinvoiceRate = false;
					}
					$("#isViewInvoiceReport").modal('show');
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
	//**********/Viewing of Order*********************************************

	//**********deleting of Invoice********************************************
	$scope.funDeleteInvoice = function (invoiceId, divisionId) {

		$scope.loaderShow();

		$http({
			url: BASE_URL + "invoices/delete-invoice/" + invoiceId,
			method: "GET",
		}).success(function (data, status, headers, config) {
			if (data.error == 1) {
				$scope.successMsgShow(data.message);
				$scope.funViewDivisionWiseInvoices(divisionId);
			} else {
				$scope.errorMsgShow(data.message);
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
	//**********/deleting of Invoice*********************************************

	//**********generate order pdf report******************************
	$scope.generateInvoicePDF = function (invoiceId, downloadType, actionType) {
		if (invoiceId && downloadType) {

			$scope.successMsgOnPopup = '';
			$scope.errorMsgOnPopup = '';
			$scope.loaderMainShow();

			$http({
				method: "POST",
				url: BASE_URL + "sales/invoices/generate-invoice-pdf",
				data: { invoice_id: invoiceId, downloadType: downloadType, actionType: actionType },
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.successMsgOnPopup = result.message;
					if (result.invoiceFileName && result.invoiceDataList) {
						$scope.invoiceFileName = result.invoiceDataList.invoice_file_name;
						$scope.invoiceFileNameWithouthf = result.invoiceDataList.invoice_file_name_without_hf;
						window.open(BASE_URL + result.invoiceFileName, '_blank');
					}
				} else {
					$scope.errorMsgOnPopup = result.message;
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (result, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			});
		}
	};
	//**********/generate pdf report******************************************

	//************************* View Invoice Order Detail*********************
	$scope.funViewInvoiceOrderDetail = function (invoice_id, type) {

		$scope.viewInvoiceOrderDetail = false;
		$scope.IsViewInvoiceListing = true;
		$scope.IsViewInvoiceGenForm = true;
		$scope.IsViewInvoiceOrders = true;
		$scope.IsViewInvoicesList = true;
		$scope.IsViewInvoiceDetail = true;
		$scope.IsEditInvoiceDetail = true;
		$scope.downloadContentList = '';
		$scope.orderData = [];
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			method: 'POST',
			url: BASE_URL + 'sales/invoices/view-invoice-orders-listing',
			data: { 'formData': invoice_id },
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.orderData = result.orderDetail;
				$scope.downloadContentList = result.downloadList;
				$scope.invoiceNumber = result.invoiceNumber;
			}
			$scope.backTypeValue = type;
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//***************************/View Invoice Order Detail*********************

	//**********get Customer Purchase Order List*********************************
	$scope.funGetCustomerPurchaseOrderList = function (billingTypeId) {

		$scope.purchaseOrderNoList = [];
		$scope.billingTypeOrderList = [];

		if (billingTypeId == '5') {
			angular.element('.po_orders_no').prop('checked', false);
			$scope.loaderShow();
			$http({
				url: BASE_URL + 'sales/invoices/get-purchase-orders-listing',
				method: "POST",
				data: { formData: $(erpCreateInvoiceForm).serialize() }
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.purchaseOrderNoList = result.purchaseOrderNoList;
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (result, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});
		}
	};
	//**********/get Customer Purchase Order List*********************************

	//**********Download Invoice Button******************************
	$scope.printInvoiceButton = function (divName) {
		printElement(document.getElementById(divName));
		var modThis = document.querySelector("#printSection .notifyMe");
		modThis.appendChild(document.createTextNode(" new"));
		window.print();
		angular.element("#printButtonDiv").show();
	};

	function printElement(elem) {
		var domClone = elem.cloneNode(true);
		var $printSection = document.getElementById("printSection");
		if (!$printSection) {
			var $printSection = document.createElement("div");
			$printSection.id = "printSection";
			document.body.appendChild($printSection);
			angular.element("#printSection").hide();
		}
		angular.element("#printButtonDiv").hide();
		$printSection.innerHTML = "";
		$printSection.appendChild(domClone);
	};
	//**********/Download Invoice Button*****************************************

	//*********** select all checkboxes on invoices******************************
	$scope.toggleAll = function () {
		$scope.allPopupSelectedOrders = [];
		var checkAllStatus = angular.element('#selectedAll').prop('checked');
		if (checkAllStatus) {
			$scope.generateInvoiceBtn = false;
			angular.element('.orderCheckBox').prop('checked', true);
			angular.element(".orderCheckBox:checked").each(function () {
				$scope.order = $scope.allPopupSelectedOrders.push(this.value);
			});
		} else {
			$scope.generateInvoiceBtn = true;
			angular.element('.orderCheckBox').prop('checked', false);
		}
	};
	//***********/select all checkboxes on invoices********************************

	//******** select one checkbox*************************************************	
	$scope.funCheckAtLeastOneIsChecked = function (orderId) {
		var checkAtLeastOneIsChecked = angular.element('#invoicing_orders' + orderId).prop('checked');
		if (checkAtLeastOneIsChecked == false) {
			angular.element('#selectedAll').prop('checked', false);
		}
		var invoiceCheckboxesCount = angular.element('input[name="order_id[]"]:checked').length;
		if (invoiceCheckboxesCount > 0) {
			$scope.generateInvoiceBtn = false;
		} else {
			$scope.generateInvoiceBtn = true;
		}
	};
	//********/select one checkbox*************************************************

	//**********Dispatch Section Functions******************************************************
	$scope.funOpenDispatchInvoiceOrderPopup = function (divid, action, invoiceId, invoiceNo) {
		$scope.hideAlertMsgPopup();
		$scope.dispatchInvoiceOrderList = [];
		$scope.funGetDispatchDetail(invoiceId);
		$scope.dispatchOrder.invoice_id = invoiceId;
		$scope.dispatchOrder.invoice_no = invoiceNo;
		$scope.dispatchOrder.invoice_status = '1';
		$("#" + divid).modal(action);
	};
	$scope.displayCodeandLabel = function (customers, displayCode) {
		return displayCode ? customers.customer_id + ':' + customers.customer_name : customers.customer_id + ':' + customers.customer_name;
	};
	$scope.funGetDispatchDetail = function (invoiceId) {
		$scope.dispatchInvoiceOrderList = [];
		var http_para_string = { formData: 'invoice_id=' + invoiceId };
		$scope.loaderShow();
		$http({
			url: BASE_URL + "sales/invoices/invoice_dispatched_detail",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.viewDispatchData = result.dispatchDetail;
				$scope.dispatchInvoiceOrderList = result.dispatchDetail;
				$scope.dispatchOrder.invoice_id = result.invoice_id;
				$scope.dispatchOrder.invoice_status = result.invoice_status;
				angular.element('#modal-body-diol').addClass('height290');
			} else {
				angular.element('#modal-body-diol').removeClass('height290');
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	$scope.funDispatchOrder = function (divisionId) {
		if ($scope.newDispatchOrderPopupFormflag) return;
		$scope.newDispatchOrderPopupFormflag = true;
		$scope.loaderShow();
		$http({
			url: BASE_URL + "invoices/dispatch-invoice-reports",
			method: "POST",
			data: { formData: $(erpDispatchOrderPopupForm).serialize() },
		}).success(function (result, status, headers, config) {
			$scope.newDispatchOrderPopupFormflag = false;
			if (result.error == 1) {
				$scope.dispatchOrder = {};
				$scope.dispatchOrder.invoice_id = result.invoice_id;
				$scope.dispatchOrder.invoice_no = result.invoice_no;
				$scope.funViewDivisionWiseInvoices(divisionId);
				$scope.funGetDispatchDetail(result.invoice_id);
				$scope.successMsgShowPopup(result.message);
			} else {
				$scope.errorMsgShowPopup(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.newDispatchOrderPopupFormflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//**********/Dispatch Section Functions******************************************************

	//**********Process Invoice Cancellation************************************************
	$scope.funProcessInvoiceCancellation = function (invoiceId) {
		if ($scope.invoiceCancellation.invoice_id == invoiceId) {
			var http_para_string = { formData: $(erpInvoiceCancellationPopupWindowForm).serialize() };
			$scope.loaderMainShow();
			$http({
				url: BASE_URL + "sales/invoices/process-invoice-cancellation",
				method: "POST",
				data: http_para_string,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.successMsgShow(result.message);
					$scope.funViewDivisionWiseInvoices($scope.divisionID);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$(invoiceCancellationInputPopupWindow).modal('hide');
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (result, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			});
		} else {
			$scope.errorMsgShow($scope.defaultMsg);
		}
	};
	//*********/Process Invoice Cancellation************************************************

	//**********dispatch order  details******************************************************
	$scope.funGetCancelledInvoiceDetail = function (invoiceId, invoiceNo) {

		var http_para_string = { formData: 'invoice_id=' + invoiceId };
		$scope.loaderMainShow();
		$http({

			url: BASE_URL + "sales/invoices/get-cancelled-invoice-detail",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.viewInvoiceCancelledData = result.invoiceCancelledDetail;
				$(invoiceCancellationDetailPopupWindow).modal('show');
			} else {
				$(invoiceCancellationDetailPopupWindow).modal('hide');
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//********** dispatch order  details****************************************************

	//**********Viewing of related reports/orders of an invoice*************************************************
	$scope.funGetReportingToReports = function (type, invoiceId) {
		$scope.loaderMainShow();
		var http_para_string = { formData: 'invoice_id=' + invoiceId + '&downloadType=1' };
		$http({
			url: BASE_URL + "sales/invoices/get-related-reports-detail",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$(generateRelatedReportsCriteriaId).modal('show');
				$scope.viewInvoiceReportsData = result.invoiceReportsDetail;
				$scope.invoiceReportToContentDiv = '0';
			} else {
				$(generateRelatedReportsCriteriaId).modal('hide');
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});

	};
	$scope.funHideShowInvoiceReportToContent = function (divId) {
		$scope.invoiceReportToContentDiv = divId;
	};
	//**********/Viewing of related reports/orders of an invoice*********************************************

	//*********Editing Invoices Acc To Billing Type******************************
	$scope.invoiceTemplateTypeEditDiv = '0';
	$scope.funModifyInvoice = function (invoice_id, type, invoiceTemplateType) {
		if (invoice_id) {
			$scope.loaderMainShow();
			$http({
				url: BASE_URL + "invoices/edit-invoice-detail/" + invoice_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					//Showing Div
					$scope.invoiceTemplateTypeEditDiv = invoiceTemplateType;
					$scope.IsViewInvoiceListing = true;
					$scope.IsViewInvoiceGenForm = true;
					$scope.IsViewInvoiceOrders = true;
					$scope.IsViewInvoicesList = true;
					$scope.IsViewInvoiceDetail = true;
					$scope.IsEditInvoiceDetail = false;

					//Invoice Header
					var invoiceObj = result.invoiceDetailList;
					$scope.invoiceId = invoice_id;
					$scope.invoiceNo = invoiceObj.invoiceHeader.invoice_no;
					$scope.divisionName = invoiceObj.invoiceHeader.division_name;
					$scope.pCategoryName = invoiceObj.invoiceHeader.p_category_name;
					$scope.customerName = invoiceObj.invoiceHeader.customer_name;
					$scope.customerStateName = invoiceObj.invoiceHeader.customer_state_name;
					$scope.customerCityName = invoiceObj.invoiceHeader.customer_city_name;
					$scope.customerGstNo = invoiceObj.invoiceHeader.customer_gst_no;
					$scope.customerAddress = invoiceObj.invoiceHeader.customer_address;
					$scope.invoiceDate = invoiceObj.invoiceHeader.invoice_date;
					$scope.invoiceFileName = invoiceObj.invoiceHeader.invoice_file_name;
					$scope.invoiceFileNameWithouthf = invoiceObj.invoiceHeader.invoice_file_name_without_hf;
					$scope.order_no = invoiceObj.invoiceHeader.order_no;
					$scope.billingType = invoiceObj.invoiceHeader.billing_type;
					$scope.invoiceBy = invoiceObj.invoiceHeader.invoice_by;
					$scope.userSignPath = invoiceObj.invoiceHeader.user_sign_path;
					$scope.InvoicerSign = invoiceObj.invoiceHeader.user_signature;
					$scope.headerContent = invoiceObj.invoiceHeader.header_content;
					$scope.footerContent = invoiceObj.invoiceHeader.footer_content;
					$scope.invoiceTemplateType = invoiceObj.invoiceHeader.invoice_template_type;

					//Invoice Body
					$scope.invoiceBody = invoiceObj.invoiceBody;

					//Invoice Footer
					$scope.total = invoiceObj.invoiceFooter.total;
					$scope.discount = invoiceObj.invoiceFooter.discount;
					$scope.discountText = invoiceObj.invoiceFooter.discount_text;
					$scope.netAmount = invoiceObj.invoiceFooter.net_amount;
					$scope.surchargeAmount = invoiceObj.invoiceFooter.surcharge_amount;
					$scope.extraAmount = invoiceObj.invoiceFooter.extra_amount;
					$scope.sgstRate = invoiceObj.invoiceFooter.sgst_rate;
					$scope.sgstAmount = invoiceObj.invoiceFooter.sgst_amount;
					$scope.cgstRate = invoiceObj.invoiceFooter.cgst_rate;
					$scope.cgstAmount = invoiceObj.invoiceFooter.cgst_amount;
					$scope.igstRate = invoiceObj.invoiceFooter.igst_rate;
					$scope.igstAmount = invoiceObj.invoiceFooter.igst_amount;
					$scope.netTotalWsw = invoiceObj.invoiceFooter.net_total_wsw;
					$scope.netTotalInWordWsw = invoiceObj.invoiceFooter.net_total_in_words_wsw;
					$scope.netTotal = invoiceObj.invoiceFooter.net_total;
					$scope.netTotalInWord = invoiceObj.invoiceFooter.net_total_in_words;
					$scope.backTypeValue = type;		//back button type
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
	//*********/Editing Invoices Acc To Billing Type******************************

	//*********Updating Invoices Acc To Billing Type******************************
	$scope.funConfirmUpdateInvoiceDetail = function (invoice_id, type, invoiceTemplateType) {
		$ngConfirm({
			title: false,
			content: 'Do you really want to update this invoice?',
			animation: 'right',
			closeIcon: true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: true,
			theme: 'bootstrap',
			columnClass: 'col-sm-5 col-md-offset-3',
			buttons: {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funUpdateInvoiceDetail(invoice_id, type, invoiceTemplateType);
					}
				},
				cancel: {
					text: 'cancel',
					btnClass: 'btn-default ng-confirm-closeIcon'
				}
			}
		});
	};
	$scope.funUpdateInvoiceDetail = function (invoice_id, type, invoiceTemplateType) {

		if (!invoice_id)return;
		var http_para_string = { formData: $(erpEditInvoicesForm).serialize() };
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "invoices/update-invoice-detail",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.successMsgShow(result.message);
				$scope.funModifyInvoice(invoice_id, type, invoiceTemplateType)
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//*********Updating Invoices Acc To Billing Type******************************

}).directive('toggle', function () {
	return {
		restrict: 'A',
		link: function (scope, element, attrs) {
			if (attrs.toggle == "tooltip") {
				$(element).tooltip({
					html:true,
					container: 'body',
					animation:true,
					placement: 'top'
				});
			}
			if (attrs.toggle == "popover") {
				$(element).popover({
					html:true,
					container: 'body',
					animation:true,
					placement: 'top'
				});
			}
		}
	};
});