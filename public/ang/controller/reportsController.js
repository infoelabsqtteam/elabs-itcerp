app.controller('reportsController', function ($scope, $q, $timeout, $http, BASE_URL, $filter, $ngConfirm) {

	//define empty variables
	$scope.orderData = '';
	$scope.order_id = '';
	$scope.order_no = '';
	$scope.order_date = '';
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.successMessagePopup = '';
	$scope.errorMessagePopup = '';
	$scope.successMsgOnPopup = '';
	$scope.errorMsgOnPopup = '';
	$scope.searchKeyword = '';
	$scope.defaultProductCategoryIDS = defaultProductCategoryIds;
	$scope.defaultMsg = 'Oops! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType = 'order_id';    // set the default sort type
	$scope.sortReverse = false;         // set the default sort order
	$scope.searchFish = '';    		 // set the default search/filter term	
	$scope.noteHtmlContent = '';
	$scope.reporterNoteHtmlContent = '';
	$scope.reviewerNoteHtmlContent = '';
	$scope.selectedAnalysisIdArr = [];
	$scope.analysisIdArr = [];
	$scope.errorParameterIdsArr = [];
	$scope.displayNote = true;
	$scope.viewEditPartAForm = false;
	$scope.viewNonEditablePartA = true;
	$scope.showContentOnPdf = false;
	$scope.noteRemarkReportOptions = [];
	$scope.testStandardsList = [];
	$scope.sampleNameOption = '';
	$scope.standardNameOption = '';
	$scope.amendmentNameOption = '';
	$scope.selectedSinatureTypeParent = '';
	$scope.selectedSinatureTypeChild = '';
	$scope.filterReport = {};
	$scope.searchReport = {};
	$scope.summaryStatistics = [];
	$scope.dispatchOrder = {};
	$scope.viewSInchargeDataList = [];
	$scope.dispatchOrderList = [];
	$scope.searchAllOnOff = '0';

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

	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup = true;
	$scope.IsViewReportFilter = false;
	$scope.IsViewReportList = false;

	/******display report according to current stage and product section*******/
	//view report for all stages scope
	$scope.IsViewDisplayTestReport = true;
	$scope.isViewReportFoodSection = false;
	$scope.isViewReportPharmaSection = false;
	$scope.isViewReportEnvironmentSection = false;
	$scope.isViewReportWaterSection = false;
	$scope.isViewReportHelmetSection = false;
	$scope.isViewReportAyurvedicSection = false;
	$scope.isViewReportBuildingSection = false;
	$scope.isViewReportTextileSection = false;
	$scope.isViewReportDefaultSection = false;

	//view report for reporter stages scope
	$scope.IsViewAddReportByReporterAndReviewer = true;
	$scope.isAddReportFoodSectionByReporterAndReviewer = false;
	$scope.isAddReportPharmaSectionByReporterAndReviewer = false;
	$scope.isAddReportEnvironmentSectionByReporterAndReviewer = false;
	$scope.isAddReportWaterSectionByReporterAndReviewer = false;
	$scope.isAddReportHelmetSectionByReporterAndReviewer = false;
	$scope.isAddReportAyurvedicSectionByReporterAndReviewer = false;
	$scope.isAddReportBuildingSectionByReporterAndReviewer = false;
	$scope.isAddReportTextileSectionByReporterAndReviewer = false;
	$scope.isAddReportDefaultSectionByReporterAndReviewer = false;

	//view report for tester stages scope
	$scope.IsViewAddReportByTester = true;
	$scope.isAddReportFoodSectionByTester = false;
	$scope.isAddReportPharmaSectionByTester = false;
	$scope.isAddReportEnvironmentSectionByTester = false;
	$scope.isAddReportWaterSectionByTester = false;
	$scope.isAddReportHelmetSectionByTester = false;
	$scope.isAddReportAyurvedicSectionByTester = false;
	$scope.isAddReportBuildingSectionByTester = false;
	$scope.isAddReportTextileSectionByTester = false;
	$scope.isAddReportDefaultSectionByTester = false;

	//view report for QA department scope
	$scope.IsViewAddReportByQADept = true;
	$scope.isAddReportFoodSectionByQADept = false;
	$scope.isAddReportPharmaSectionByQADept = false;
	$scope.isAddReportEnvironmentSectionByQADept = false;
	$scope.isAddReportWaterSectionByQADept = false;
	$scope.isAddReportHelmetSectionByQADept = false;
	$scope.isAddReportAyurvedicSectionByQADept = false;
	$scope.isAddReportBuildingSectionByQADept = false;
	$scope.isAddReportTextileSectionByQADept = false;
	$scope.isAddReportDefaultSectionByQADept = false;

	// view report for section incharge scope	
	$scope.IsViewAddReportBySectionIncharge = true;
	$scope.isAddReportFoodSectionBySectionIncharge = false;
	$scope.isAddReportWaterSectionBySectionIncharge = false;
	$scope.isAddReportHelmetSectionBySectionIncharge = false;
	$scope.isAddReportBuildingSectionBySectionIncharge = false;
	$scope.isAddReportTextileSectionBySectionIncharge = false;
	$scope.isAddReportEnvironmentSectionBySectionIncharge = false;
	$scope.isAddReportPharmaSectionBySectionIncharge = false;
	$scope.isAddReportAyurvedicSectionBySectionIncharge = false;
	$scope.isAddReportDefaultSectionBySectionIncharge = false;

	/******display report according to current stage and product section*******/

	//**********successMsgShow**************************************************
	$scope.successMsgShow = function (message) {
		$scope.successMessage = message;
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg = true;
		$('#successMessage').removeClass('ng-hide');
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

	//********** key Press Handler**********************************************
	$scope.funEnterPressHandler = function (e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			e.stopPropagation();
		}
	}
	//**********/key Press Handler**********************************************

	//Submission Type dropdown
	$scope.submissionTypeList = [
		{ id: '1', name: 'Direct' },
		{ id: '2', name: 'Courier' },
		{ id: '3', name: 'Marketing Executive' }
	];

	//**********backButton****************************************************
	$scope.backButton = function () {

		$scope.IsViewReportList = false;    		//display report listing

		//******display report according to current stage and product section*******//
		//view report for all stages scope
		$scope.IsViewDisplayTestReport = true;
		$scope.isViewReportFoodSection = false;
		$scope.isViewReportPharmaSection = false;
		$scope.isViewReportEnvironmentSection = false;
		$scope.isViewReportWaterSection = false;
		$scope.isViewReportHelmetSection = false;
		$scope.isViewReportAyurvedicSection = false;
		$scope.isViewReportBuildingSection = false;
		$scope.isViewReportTextileSection = false;
		$scope.isViewReportDefaultSection = false;

		//view report for reporter stages scope
		$scope.IsViewAddReportByReporterAndReviewer = true;
		$scope.isAddReportFoodSectionByReporterAndReviewer = false;
		$scope.isAddReportPharmaSectionByReporterAndReviewer = false;
		$scope.isAddReportEnvironmentSectionByReporterAndReviewer = false;
		$scope.isAddReportWaterSectionByReporterAndReviewer = false;
		$scope.isAddReportHelmetSectionByReporterAndReviewer = false;
		$scope.isAddReportAyurvedicSectionByReporterAndReviewer = false;
		$scope.isAddReportBuildingSectionByReporterAndReviewer = false;
		$scope.isAddReportTextileSectionByReporterAndReviewer = false;
		$scope.isAddReportDefaultSectionByReporterAndReviewer = false;

		//view report for tester stages scope
		$scope.IsViewAddReportByTester = true;
		$scope.isAddReportFoodSectionByTester = false;
		$scope.isAddReportPharmaSectionByTester = false;
		$scope.isAddReportEnvironmentSectionByTester = false;
		$scope.isAddReportWaterSectionByTester = false;
		$scope.isAddReportHelmetSectionByTester = false;
		$scope.isAddReportAyurvedicSectionByTester = false;
		$scope.isAddReportBuildingSectionByTester = false;
		$scope.isAddReportTextileSectionByTester = false;
		$scope.isAddReportDefaultSectionByTester = false;

		//view report for QA department scope
		$scope.IsViewAddReportByQADept = true;
		$scope.isAddReportFoodSectionByQADept = false;
		$scope.isAddReportPharmaSectionByQADept = false;
		$scope.isAddReportEnvironmentSectionByQADept = false;
		$scope.isAddReportWaterSectionByQADept = false;
		$scope.isAddReportHelmetSectionByQADept = false;
		$scope.isAddReportAyurvedicSectionByQADept = false;
		$scope.isAddReportBuildingSectionByQADept = false;
		$scope.isAddReportTextileSectionByQADept = false;
		$scope.isAddReportDefaultSectionByQADept = false;

		//view report for  section_incharge
		$scope.IsViewAddReportBySectionIncharge = true;
		$scope.isAddReportFoodSectionBySectionIncharge = false;
		$scope.isAddReportWaterSectionBySectionIncharge = false;
		$scope.isAddReportHelmetSectionBySectionIncharge = false;
		$scope.isAddReportBuildingSectionBySectionIncharge = false;
		$scope.isAddReportTextileSectionBySectionIncharge = false;
		$scope.isAddReportEnvironmentSectionBySectionIncharge = false;
		$scope.isAddReportPharmaSectionBySectionIncharge = false;
		$scope.isAddReportAyurvedicSectionBySectionIncharge = false;
		$scope.isAddReportDefaultSectionBySectionIncharge = false;

		$scope.IsViewReportFilter = false;
		$scope.selectedAnalysisIdArr = [];
		$scope.displayNote = true;
		$scope.noteHtmlContent = '';
		$scope.reporterNoteHtmlContent = '';
		$scope.reviewerNoteHtmlContent = '';
		$scope.hideAlertMsg();
	};
	//**********/backButton***************************************************

	//**********backButton****************************************************
	$scope.resetViewButton = function (loadType) {

		//Reloading Type
		if (!loadType) return;

		//******display report according to current stage and product section*******//
		//view report for all stages scope
		//$scope.IsViewDisplayTestReport   		= true;
		$scope.isViewReportFoodSection = false;
		$scope.isViewReportPharmaSection = false;
		$scope.isViewReportEnvironmentSection = false;
		$scope.isViewReportWaterSection = false;
		$scope.isViewReportHelmetSection = false;
		$scope.isViewReportAyurvedicSection = false;
		$scope.isViewReportBuildingSection = false;
		$scope.isViewReportTextileSection = false;
		$scope.isViewReportDefaultSection = false;

		//view report for reporter stages scope
		//$scope.IsViewAddReportByReporterAndReviewer  		= true;
		$scope.isAddReportFoodSectionByReporterAndReviewer = false;
		$scope.isAddReportPharmaSectionByReporterAndReviewer = false;
		$scope.isAddReportEnvironmentSectionByReporterAndReviewer = false;
		$scope.isAddReportWaterSectionByReporterAndReviewer = false;
		$scope.isAddReportHelmetSectionByReporterAndReviewer = false;
		$scope.isAddReportAyurvedicSectionByReporterAndReviewer = false;
		$scope.isAddReportBuildingSectionByReporterAndReviewer = false;
		$scope.isAddReportTextileSectionByReporterAndReviewer = false;
		$scope.isAddReportDefaultSectionByReporterAndReviewer = false;

		//view report for tester stages scope
		//$scope.IsViewAddReportByTester 				= true;
		$scope.isAddReportFoodSectionByTester = false;
		$scope.isAddReportPharmaSectionByTester = false;
		$scope.isAddReportEnvironmentSectionByTester = false;
		$scope.isAddReportWaterSectionByTester = false;
		$scope.isAddReportHelmetSectionByTester = false;
		$scope.isAddReportAyurvedicSectionByTester = false;
		$scope.isAddReportBuildingSectionByTester = false;
		$scope.isAddReportTextileSectionByTester = false;
		$scope.isAddReportDefaultSectionByTester = false;

		//view report for QA department scope
		//$scope.IsViewAddReportByQADept  			= true;
		$scope.isAddReportFoodSectionByQADept = false;
		$scope.isAddReportPharmaSectionByQADept = false;
		$scope.isAddReportEnvironmentSectionByQADept = false;
		$scope.isAddReportWaterSectionByQADept = false;
		$scope.isAddReportHelmetSectionByQADept = false;
		$scope.isAddReportAyurvedicSectionByQADept = false;
		$scope.isAddReportBuildingSectionByQADept = false;
		$scope.isAddReportTextileSectionByQADept = false;
		$scope.isAddReportDefaultSectionByQADept = false;

		//view report for  section_incharge
		//$scope.IsViewAddReportBySectionIncharge  		= true;
		$scope.isAddReportFoodSectionBySectionIncharge = false;
		$scope.isAddReportWaterSectionBySectionIncharge = false;
		$scope.isAddReportHelmetSectionBySectionIncharge = false;
		$scope.isAddReportBuildingSectionBySectionIncharge = false;
		$scope.isAddReportTextileSectionBySectionIncharge = false;
		$scope.isAddReportEnvironmentSectionBySectionIncharge = false;
		$scope.isAddReportPharmaSectionBySectionIncharge = false;
		$scope.isAddReportAyurvedicSectionBySectionIncharge = false;
		$scope.isAddReportDefaultSectionBySectionIncharge = false;
	};
	//**********/backButton***************************************************

	//**********confirm box******************************************************
	$scope.funConfirmMessage = function (id, divisionId, message, type) {
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
					action: function () {
						if (type == 'listReport') {
							$scope.funDeleteReport(id, divisionId);
						} else if (type == 'addReport') {
							$scope.saveOrderForInvoice(id, divisionId);
						} else if (type == 'amendReport') {
							$scope.funAmendReport(id, divisionId); // 12 dec,2017
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

	//**********Open Pop Box******************************************************
	$scope.funOpenBootstrapPopup = function (divid, action, orderId, orderNo) {
		$scope.dispatchOrder.order_id = orderId;
		$scope.dispatchOrder.order_no = orderNo;
		$("#" + divid).modal(action);
	};
	//**********Open Pop Box******************************************************

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

	//************Sorting of list***********************************************
	$scope.predicate = 'order_date';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/Sorting of list**********************************************

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

	/*****************display division dropdown start*****************/
	$scope.funGetReportOptionsList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'sales/reports/get-order-report-options'
	}).success(function (result) {
		$scope.reportOptionsList = result.reportOptionsList;
		$scope.clearConsole();
	});
	/*****************display division dropdown end*****************/

	//*****************display status Report List dropdown********************
	$scope.statusReportList = [];
	$http({
		method: 'GET',
		url: BASE_URL + 'reports/get_status_list'
	}).success(function (result) {
		$scope.statusReportList = result.statusReportList;
		$scope.clearConsole();
	});
	//*****************/display status Report List dropdown*********************

	//*****************display sample Priority List dropdown***************************
	$scope.samplePriorityList = [];
	$scope.funGetSamplePriorityList = function () {
		$http({
			method: 'GET',
			url: BASE_URL + 'orders/get_sample_priority_list'
		}).success(function (result) {
			$scope.samplePriorityList = result.samplePriorityList;
			$scope.clearConsole();
		});
	}
	//*****************/display sample Priority List code dropdown*********************

	//*****************Loading of Data based on User Roles******************************
	$scope.filterReport.order_search_dispatch_pendency = false;
	$scope.filterReport.order_search_all = false;
	$scope.funFilterBranchWiseReportScopeList = function (divisionId, orderId) {
		if (CURRENTROLE == '1') {
			$scope.funGetBranchWiseReportList(divisionId);
		} else if ($scope.filterReport.order_search_dispatch_pendency == false && $scope.filterReport.order_search_all == false) {
			$scope.getBranchWiseReports.forEach(function (element, index, array) {
				if (element.order_id == orderId) {
					$scope.getBranchWiseReports.splice(index, 1);
				}
			});
		}
	};
	//*****************/Loading of Data based on User Roles******************************

	//**********Getting all Orders**********************************************
	$scope.funGetBranchWiseReportHttpRequest = function () {

		$scope.hideAlertMsg();
		$scope.loaderShow();
		var http_para_string = { formData: 'keyword=' + $scope.searchKeyword + '&' + $(erpFilterReportForm).serialize() };

		$http({
			url: BASE_URL + "reports/get_branch_wise_reports",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.getBranchWiseReports = result.getBranchWiseReports;
			$scope.summaryStatistics = result.summaryStatistics;
			$scope.searchAllOnOff = result.searchAllOnOff;
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
	$scope.funGetBranchWiseReportList = function (divisionId) {
		$scope.divisionID = divisionId;
		$scope.searchKeyword = $scope.searchKeyword;
		$scope.funGetBranchWiseReportHttpRequest();
	};

	//***************Getting all filter Report By Status Orders************
	var tempReportSearchTerm;
	$scope.funSearchReportByStatus = function (keyword) {
		tempReportSearchTerm = keyword;
		$timeout(function () {
			if (keyword == tempReportSearchTerm) {
				$scope.searchKeyword = keyword;
				$scope.funGetBranchWiseReportHttpRequest();
			}
		}, 800);
	};

	//***************Getting all filter Report By Status Orders************
	$scope.funFilterReportByStatus = function () {
		$scope.funGetBranchWiseReportHttpRequest();
	};
	//***************/Getting all filter Report By Status Orders*******

	//**********refresh Report By Status*******************************
	$scope.funRefreshReportByStatus = function () {
		$scope.filterReport = {};
		$scope.erpFilterReportForm.$setUntouched();
		$scope.erpFilterReportForm.$setPristine();
		$timeout(function () {
			$scope.divisionID = '';
			$scope.searchKeyword = '';
			$scope.funGetBranchWiseReportHttpRequest();
		}, 500);
	};
	//**********/refresh Report By Status*****************************

	//**********/Getting all Orders**********************************************

	/**************multisearch start here**********************/
	$scope.multiSearchTr = true;
	$scope.multisearchBtn = false;
	var tempSearchKeyword;
	$scope.getMultiSearch = function (reportSearchKeyword) {
		tempSearchKeyword = reportSearchKeyword;
		$timeout(function () {
			if (tempSearchKeyword == reportSearchKeyword) {
				$scope.searchReport = reportSearchKeyword;
				$scope.funGetBranchWiseReportHttpRequest();
			}
		}, 1000);
	};

	$scope.closeMultisearch = function () {
		$scope.multiSearchTr = true;
		$scope.multisearchBtn = false;
		$scope.refreshMultisearch();
	};

	$scope.refreshMultisearch = function (division_id) {
		$scope.filterReport = {};
		$scope.funGetBranchWiseReportList(division_id);
		$scope.getMultiSearch($scope.searchReport);
	};

	$scope.openMultisearch = function () {
		$scope.multiSearchTr = false;
		$scope.multisearchBtn = true;
	};
	/**************multisearch end here**********************/

	//**********Function for View Test Parameters Tester*************************************************
	$scope.funAddTestParametersReportByTester = function (order_id, loadType = true) {

		if (order_id) {

			$scope.resetViewButton(loadType);
			$scope.hasPermToSaveTestResult = true;
			$scope.hasPermToInvoiceTestResult = true;
			$scope.hideAlertMsg();
			$scope.loaderMainShow();

			$http({
				url: BASE_URL + "reports/view_order_by_tester/" + order_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					//******display report according to current stage and product section****************************

					//Setting Index Page View
					$scope.IsViewReportList = true;							//Hiding Report Listing View
					$scope.IsViewDisplayTestReport = true;					//Hiding Tester Report Viewing
					$scope.IsViewAddReportByReporterAndReviewer = true;		//Hiding Reviewer View
					$scope.IsViewAddReportBySectionIncharge = true;			//Hiding Section Incharge View
					$scope.IsViewAddReportByQADept = true;					//Hiding QA department View
					$scope.IsViewAddReportByTester = false;					//Showing Add Tester View

					//Setting View According to Department Wise
					if (defaultProductCategoryIds.includes(result.orderList.product_category_id)) {
						$scope.isAddReportFoodSectionByTester = result.orderList.product_category_id == '1' ? true : false;
						$scope.isAddReportPharmaSectionByTester = result.orderList.product_category_id == '2' ? true : false;
						$scope.isAddReportWaterSectionByTester = result.orderList.product_category_id == '3' ? true : false;
						$scope.isAddReportHelmetSectionByTester = result.orderList.product_category_id == '4' ? true : false;
						$scope.isAddReportAyurvedicSectionByTester = result.orderList.product_category_id == '5' ? true : false;
						$scope.isAddReportBuildingSectionByTester = result.orderList.product_category_id == '6' ? true : false;
						$scope.isAddReportTextileSectionByTester = result.orderList.product_category_id == '7' ? true : false;
						$scope.isAddReportEnvironmentSectionByTester = result.orderList.product_category_id == '8' ? true : false;
					} else {
						$scope.isAddReportDefaultSectionByTester = true;
					}

					$scope.addOrderReport = result.orderList;
					$scope.addOrderReport.withAmendment = new Date();
					$scope.orderParametersList = result.orderParameterList;
					if (result.hasPermToSaveTestResult == 1) { $scope.hasPermToSaveTestResult = false; }
					if (result.hasPermToInvoiceTestResult == 1) { $scope.hasPermToInvoiceTestResult = false; }
					$scope.hasMicrobiologicalEquipment = result.hasMicrobiologicalEquipment;
					$scope.orderTrackRecord = result.orderTrackRecord;
					$scope.colCount = $scope.addOrderReport.hasReportAWUISetting == 1

					//******/Display report according to current stage and product section**********************************
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
	//**********/Function for View Test Parameters Tester*********************************************

	//**********Viewing of Test Report****************************************************************
	$scope.funViewTestReport = function (order_id, loadType = true) {
		if (order_id) {

			$scope.resetViewButton(loadType);
			$scope.hideAlertMsg();
			$scope.templateOrderId = order_id;
			$scope.loaderMainShow();

			$http({
				url: BASE_URL + "reports/view_order/" + order_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					if (result.orderList) {

						//******Display report according to current stage and product section*******

						//Viewing View Test Report
						$scope.IsViewReportList = true;
						$scope.IsViewDisplayTestReport = false;
						$scope.displayNote = false;
						$scope.IsViewAddReportByReporterAndReviewer = true;			//view report for reporter stages ,files inside add/reporter_and_reviewer/ folder
						$scope.IsViewAddReportByTester = true;			//view report for tester stages ,files inside add/tester/ folder
						$scope.IsViewAddReportByQADept = true;			//view report for QA department 
						$scope.IsViewAddReportBySectionIncharge = true;			//view report for section incharge stage

						if (defaultProductCategoryIds.includes(result.orderList.product_category_id)) {
							$scope.isViewReportFoodSection = result.orderList.product_category_id == '1' ? true : false;
							$scope.isViewReportPharmaSection = result.orderList.product_category_id == '2' ? true : false;
							$scope.isViewReportWaterSection = result.orderList.product_category_id == '3' ? true : false;
							$scope.isViewReportHelmetSection = result.orderList.product_category_id == '4' ? true : false;
							$scope.isViewReportAyurvedicSection = result.orderList.product_category_id == '5' ? true : false;
							$scope.isViewReportBuildingSection = result.orderList.product_category_id == '6' ? true : false;
							$scope.isViewReportTextileSection = result.orderList.product_category_id == '7' ? true : false;
							$scope.isViewReportEnvironmentSection = result.orderList.product_category_id == '8' ? true : false;
						} else {
							$scope.isViewReportDefaultSection = true;
						}

						$scope.viewOrderReport = result.orderList;
						$scope.orderParametersList = result.orderParameterList;
						$scope.hasMicrobiologicalEquipment = result.hasMicrobiologicalEquipment;
						$scope.orderTrackRecord = result.orderTrackRecord;

						//******/Display report according to current stage and product section*******
					}
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
	//**********/Viewing of Test Report****************************************************************

	//**********Viewing of Order report by Section Incharge********************************************
	$scope.funAddTestParametersReportBySectionIncharge = function (order_id, loadType = true) {
		if (order_id) {

			$scope.resetViewButton(loadType);
			$scope.viewEditPartAForm = false;
			$scope.viewNonEditablePartA = true;
			$scope.viewOrderReport = '';
			$scope.showAmendmentWith = false;
			$scope.showGradeType = false;
			$scope.templateOrderIdByReporter = order_id;
			$scope.hasPermToSaveTestResult = true;
			$scope.hasPermToInvoiceTestResult = true;
			$scope.hideAlertMsg();
			$scope.loaderMainShow();

			$http({
				url: BASE_URL + "reports/view_order_by_section_incharge/" + order_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					//******Display report according to current stage and product section*******

					//view report for all stages scope
					$scope.IsViewReportList = true;
					$scope.IsViewDisplayTestReport = true;
					$scope.IsViewAddReportByTester = true;
					$scope.IsViewAddReportByReporterAndReviewer = true;
					$scope.IsViewAddReportByQADept = true;
					$scope.IsViewAddReportBySectionIncharge = false;

					//view report for reporter stages scope
					if (defaultProductCategoryIds.includes(result.orderList.product_category_id)) {
						$scope.isAddReportFoodSectionBySectionIncharge = result.orderList.product_category_id == '1' ? true : false;
						$scope.isAddReportPharmaSectionBySectionIncharge = result.orderList.product_category_id == '2' ? true : false;
						$scope.isAddReportWaterSectionBySectionIncharge = result.orderList.product_category_id == '3' ? true : false;
						$scope.isAddReportHelmetSectionBySectionIncharge = result.orderList.product_category_id == '4' ? true : false;
						$scope.isAddReportAyurvedicSectionBySectionIncharge = result.orderList.product_category_id == '5' ? true : false;
						$scope.isAddReportBuildingSectionBySectionIncharge = result.orderList.product_category_id == '6' ? true : false;
						$scope.isAddReportTextileSectionBySectionIncharge = result.orderList.product_category_id == '7' ? true : false;
						$scope.isAddReportEnvironmentSectionBySectionIncharge = result.orderList.product_category_id == '8' ? true : false;
					} else {
						$scope.isAddReportDefaultSectionBySectionIncharge = true;
					}

					$scope.viewOrderReport = result.orderList;
					$scope.checkedAmendmentNo = !$scope.viewOrderReport.is_amended_no ? '' : 'checked';
					$scope.disabledAmendmentNo = !$scope.viewOrderReport.is_amended_no ? '' : 'disabled';

					if (result.orderList) {
						$scope.funGetRemark(result.orderList, result.orderList.with_amendment_no);
						$scope.viewOrderReport.test_standard_value = { selectedOption: { id: result.orderList.test_standard_value, name: result.orderList.test_standard_value } };
						$scope.viewOrderReport.submissionType = { selectedOption: { id: result.orderList.submission_type } };

						$scope.viewOrderReport.ref_sample_value = { selectedoption: { id: result.orderList.ref_sample_value } };
						$scope.viewOrderReport.result_drived_value = { selectedoption: { id: result.orderList.result_drived_value } };
						$scope.viewOrderReport.deviation_value = { selectedoption: { id: result.orderList.deviation_value } };
					}
					$scope.orderParametersList = result.orderParameterList;
					$scope.hasMicrobiologicalEquipment = result.hasMicrobiologicalEquipment;
					$scope.orderTrackRecord = result.orderTrackRecord;

					//Function for getting all Notes and Remarks
					$scope.funNoteRemarkReportOptions(result.orderList);

					//******/Display report according to current stage and product section*******
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
	//**********/Viewing of Order report by Section Incharge****************************************

	//***********Viewing of Order report by Reviewer************************************************
	$scope.funAddTestParametersReportByReporter = function (order_id, loadType = true) {
		if (order_id) {

			$scope.resetViewButton(loadType);
			$scope.viewEditPartAForm = false;
			$scope.viewNonEditablePartA = true;
			$scope.viewOrderReport = '';
			$scope.showAmendmentWith = false;
			$scope.showGradeType = false;
			$scope.templateOrderIdByReporter = order_id;
			$scope.hasPermToSaveTestResult = true;
			$scope.hasPermToInvoiceTestResult = true;
			$scope.hideAlertMsg();
			$scope.loaderMainShow();

			$http({
				url: BASE_URL + "reports/view_order_by_reporter/" + order_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					//**********Displaying Inactive Analyst Message************
					if (result.inactive_analyst_message) { $scope.successMsgShow(result.inactive_analyst_message) };
					//*********/Displaying Inactive Analyst Message************

					//******Display report according to current stage and product section*******

					//view report for all stages scope
					$scope.IsViewReportList = true;
					$scope.IsViewDisplayTestReport = true;
					$scope.IsViewAddReportByTester = true;
					$scope.IsViewAddReportBySectionIncharge = true;
					$scope.IsViewAddReportByQADept = true;
					$scope.IsViewAddReportByReporterAndReviewer = false;

					//view report for reporter stages scope
					if (defaultProductCategoryIds.includes(result.orderList.product_category_id)) {
						$scope.isAddReportFoodSectionByReporterAndReviewer = result.orderList.product_category_id == '1' ? true : false;
						$scope.isAddReportPharmaSectionByReporterAndReviewer = result.orderList.product_category_id == '2' ? true : false;
						$scope.isAddReportWaterSectionByReporterAndReviewer = result.orderList.product_category_id == '3' ? true : false;
						$scope.isAddReportHelmetSectionByReporterAndReviewer = result.orderList.product_category_id == '4' ? true : false;
						$scope.isAddReportAyurvedicSectionByReporterAndReviewer = result.orderList.product_category_id == '5' ? true : false;
						$scope.isAddReportBuildingSectionByReporterAndReviewer = result.orderList.product_category_id == '6' ? true : false;
						$scope.isAddReportTextileSectionByReporterAndReviewer = result.orderList.product_category_id == '7' ? true : false;
						$scope.isAddReportEnvironmentSectionByReporterAndReviewer = result.orderList.product_category_id == '8' ? true : false;
					} else {
						$scope.isAddReportDefaultSectionByReporterAndReviewer = true;
					}

					$scope.viewOrderReport = result.orderList;
					$scope.checkedAmendmentNo = !$scope.viewOrderReport.is_amended_no ? '' : 'checked';
					$scope.disabledAmendmentNo = !$scope.viewOrderReport.is_amended_no ? '' : 'disabled';

					if (result.orderList) {
						$scope.funGetRemark(result.orderList, result.orderList.with_amendment_no);
						$scope.viewOrderReport.test_standard_value = { selectedOption: { id: result.orderList.test_standard_value, name: result.orderList.test_standard_value } };
						$scope.viewOrderReport.submissionType = { selectedOption: { id: result.orderList.submission_type } };

						$scope.viewOrderReport.ref_sample_value = { selectedoption: { id: result.orderList.ref_sample_value } };
						$scope.viewOrderReport.result_drived_value = { selectedoption: { id: result.orderList.result_drived_value } };
						$scope.viewOrderReport.deviation_value = { selectedoption: { id: result.orderList.deviation_value } };
					}
					$scope.orderParametersList = result.orderParameterList;
					$scope.hasMicrobiologicalEquipment = result.hasMicrobiologicalEquipment;
					$scope.orderTrackRecord = result.orderTrackRecord;

					//Function for getting all Notes and Remarks
					$scope.funNoteRemarkReportOptions(result.orderList);

					//******/Display report according to current stage and product section*******
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
	//**********/Viewing of Order report by Reviewer********************************************

	//**********Viewing of Order by QA department***************************************************
	$scope.funAddTestReportByFinalizerAndQA = function (order_id, loadType = true) {
		if (order_id) {

			$scope.resetViewButton(loadType);
			$scope.displayNote = false;
			$scope.templateOrderId = order_id;
			$scope.hideAlertMsg();
			$scope.loaderMainShow();

			$http({
				url: BASE_URL + "reports/view_order/" + order_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					if (result.orderList.customer_hold_message && result.orderList.status == '7') {
						$scope.successMsgShow(result.orderList.customer_hold_message);
					} else if (result.orderList) {

						//******Display report according to current stage and product section*******

						//view report for all stages common files inside view folder folder
						$scope.IsViewReportList = true;
						$scope.IsViewDisplayTestReport = true;
						$scope.IsViewAddReportByTester = true;			//view report for tester stages ,files inside add/tester/ folder
						$scope.IsViewAddReportByReporterAndReviewer = true;			//view report for reporter stages ,files inside add/reporter_and_reviewer/ folder
						$scope.IsViewAddReportBySectionIncharge = true;			//view report for section incharge
						//$scope.isAddReportDefaultSectionByQADept 	= false;
						$scope.IsViewAddReportByQADept = false;		//view report for QA department

						if (defaultProductCategoryIds.includes(result.orderList.product_category_id)) {
							$scope.isAddReportFoodSectionByQADept = result.orderList.product_category_id == '1' ? true : false;
							$scope.isAddReportPharmaSectionByQADept = result.orderList.product_category_id == '2' ? true : false;
							$scope.isAddReportWaterSectionByQADept = result.orderList.product_category_id == '3' ? true : false;
							$scope.isAddReportHelmetSectionByQADept = result.orderList.product_category_id == '4' ? true : false;
							$scope.isAddReportAyurvedicSectionByQADept = result.orderList.product_category_id == '5' ? true : false;
							$scope.isAddReportBuildingSectionByQADept = result.orderList.product_category_id == '6' ? true : false;
							$scope.isAddReportTextileSectionByQADept = result.orderList.product_category_id == '7' ? true : false;
							$scope.isAddReportEnvironmentSectionByQADept = result.orderList.product_category_id == '8' ? true : false;
						} else {
							$scope.isAddReportDefaultSectionByQADept = true;
						}

						$scope.viewOrderReport = result.orderList;
						$scope.orderParametersList = result.orderParameterList;
						$scope.hasMicrobiologicalEquipment = result.hasMicrobiologicalEquipment;
						$scope.orderTrackRecord = result.orderTrackRecord;

						//******Display report according to current stage and product section************************
					}
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
	//**********/Viewing of Order by QA department***************************************************************

	//**********move order to next stage by QA department********************************************************
	$scope.funMoveOrderToNextStage = function (order_id) {

		$scope.hideAlertMsg();
		$scope.loaderMainShow();
		$scope.templateOrderId = order_id;
		$http({
			url: BASE_URL + "sales/reports/move_order_to_next_stage",
			method: "POST",
			data: { formData: $(orderReportForm).serialize() }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.backButton();
				$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
				//$scope.funGetBranchWiseReportList($scope.divisionID);
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
	//*********move order to next stage by QA department*****************************

	//**********move order to next stage by QA department******************************
	$scope.funMoveOrderToNextStageForPdfGen = function (order_id) {

		$scope.hideAlertMsg();
		$scope.templateOrderId = order_id;
		$http({
			url: BASE_URL + "sales/reports/move_order_to_next_stage",
			method: "POST",
			data: { formData: $(orderReportForm).serialize() }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
				//$scope.funGetBranchWiseReportList($scope.divisionID);			
				$scope.backButton();
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
	//*********move order to next stage by QA department*****************************

	//**********open need modification popup******************************
	$scope.funOpenNeedModificationModal = function (type) {

		$scope.noteHtmlContent = '';
		$scope.reporterNoteHtmlContent = '';
		$scope.reviewerNoteHtmlContent = '';

		if (type == 'NeedModificationBySectionIncharge') {
			$('#needModificationBySectionInchargeModal').modal({ backdrop: 'static', keyboard: true, show: true });
		} else if (type == 'NeedModificationByReviewer') {
			$('#needModificationByReviewerModal').modal({ backdrop: 'static', keyboard: true, show: true });
		} else if (type == 'ByQaDept') {
			$('#needModificationModal').modal({ backdrop: 'static', keyboard: true, show: true });
		} else if (type == 'generateReportCriteriaId') {
			$scope.successMsgOnPopup = '';
			$scope.errorMsgOnPopup = '';
			$('.hideNeedModificationOnPdf').addClass('disabled').hide();
			$('#generateReportCriteriaId').modal({ backdrop: 'static', keyboard: true, show: true });
		}

		var select_report_option_child = document.querySelector('#select_report_option_child');
     	var select_report_option_child_count = select_report_option_child.querySelectorAll('div').length;
		if(!select_report_option_child_count){
			angular.element('#select_report_option_parent').hide();
			angular.element('#select_report_option_child').hide();
		}
	};
	//**********open need modification popup******************************	

	//***********************Check Report Generated Or Not****************
	$scope.funCheckReportGeneratedOrNot = function (order_id, orderStatus) {
		$('#generateReportCriteriaId').modal('hide');
		if (order_id && orderStatus <= '7') {
			$http({
				url: BASE_URL + "reports/view_order/" + order_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					if (result.orderList.status <= '7') {
						$('.hideNeedModificationOnPdf').removeClass('disabled').show();
					} else {
						$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
						//$scope.funGetBranchWiseReportList($scope.divisionID);
					}
				}
			}).error(function (data, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShow($scope.defaultMsg);
				}
			});
		}
	};
	//**********************/Check Report Generated Or Not****************

	//**********by reporter set analyst id checkbox selected array**********
	$scope.funSelectedAnalysisIdArr = function (analysisId, type) {
		if (angular.element('#analysis_id_' + analysisId).prop('checked') == true) {
			$scope.selectedAnalysisIdArr.push(analysisId);
		} else {
			$scope.selectedAnalysisIdArr.splice($scope.selectedAnalysisIdArr.indexOf(analysisId), 1);
		}
	};
	//**********by reporter set analyst id checkbox selected array**********

	//**********move order to previous stage by reporter (move report to testing stage)******************************
	$scope.funNeedReportModificationBySectionIncharge = function (order_id) {

		$scope.hideAlertMsg();
		$scope.loaderMainShow();
		$scope.templateOrderId = order_id;

		$http({
			url: BASE_URL + "sales/reports/need_report_modification_by_section_incharge",
			method: "POST",
			data: { formData: $(orderReportFormByReporter).serialize() }
		}).success(function (result, status, headers, config) {
			$('#needModificationBySectionInchargeModal').modal('hide');
			if (result.error == 1) {
				$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
				//$scope.funGetBranchWiseReportList($scope.divisionID);				
				$scope.backButton();
				$scope.selectedAnalysisIdArr = [];
				$scope.successMsgShow(result.message);
			} else if (result.error == 2) {
				$scope.funAddTestParametersReportBySectionIncharge(order_id, false);
				$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
				//$scope.funGetBranchWiseReportList($scope.divisionID);
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$('#needModificationByReporterModal').modal('hide');
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********move order to previous stage by reporter (move report to previous stage)***************

	//**********move order to previous stage by reviewer (move report to testing stage)****************
	$scope.funNeedReportModificationByReviewer = function (order_id) {

		$scope.hideAlertMsg();
		$scope.loaderMainShow();
		$scope.templateOrderId = order_id;

		$http({
			url: BASE_URL + "sales/reports/need_report_modification_by_reviewer",
			method: "POST",
			data: { formData: $(orderReportFormByReporter).serialize() }
		}).success(function (result, status, headers, config) {
			$('#needModificationByReviewerModal').modal('hide');
			if (result.error == 1) {
				$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
				//$scope.funGetBranchWiseReportList($scope.divisionID);				
				$scope.backButton();
				$scope.selectedAnalysisIdArr = [];
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$('#needModificationByReviewerModal').modal('hide');
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********move order to previous stage by reviewer (move report to previous stage)************

	//**********move order to previous stage by QA department******************************
	$scope.funNeedReportModification = function (order_id) {

		$scope.hideAlertMsg();
		$scope.loaderMainShow();
		$scope.templateOrderId = order_id;

		$http({
			url: BASE_URL + "sales/reports/need_report_modification",
			method: "POST",
			data: { formData: $(orderReportForm).serialize() }
		}).success(function (result, status, headers, config) {
			$('#needModificationModal').modal('hide');
			if (result.error == 1) {
				$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
				//$scope.funGetBranchWiseReportList($scope.divisionID);				
				$scope.backButton();
				$scope.selectedAnalysisIdArr = [];
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$('#needModificationModal').modal('hide');
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********move order to previous stage by QA department******************************

	//*****************display division dropdown*****************
	$scope.advanceDetailsDisplay = false;
	$scope.funSubmissionTypeValue = function (submissionTypeId) {
		$scope.advanceDetailsDisplay = false;
		if (submissionTypeId == 1) {
			$scope.advanceDetailsDisplay = true;
		} else {
			$scope.viewOrderReport.advance_details = '';
		}
	};
	//*****************/display division dropdown*****************

	//**********remark options dropdown values**********
	$scope.funGetRemark = function (reportDetails, with_amendment_no) {

		$scope.AmendmentTextScope = "";
		$scope.SampleNameScope = "";
		$scope.TestStandardNameScope = "";

		if (with_amendment_no) {
			$scope.AmendmentTextScope = "with upto date amdts with";
		} else {
			$scope.AmendmentTextScope = "";
		}

		if (reportDetails.sample_description) { $scope.SampleNameScope = reportDetails.sample_description.trim(); }
		if (reportDetails.test_std_name) { $scope.TestStandardNameScope = reportDetails.test_std_name.trim(); }
	};

	//**********show/hide grade value input on report template**********
	$scope.showGradeType = false;
	$scope.show_grade_type = function () {
		if ($scope.showGradeType == false) {
			$scope.showGradeType = true;
		} else {
			$scope.showGradeType = false;
		}
	};
	//**********current report template******************************/

	//**********show/hide grade value input on report template**********
	$scope.showAmendmentWith = false;
	$scope.show_amendment_input = function () {
		if ($scope.showAmendmentWith == false) {
			$scope.showAmendmentWith = true;
		} else {
			$scope.showAmendmentWith = false;
		}
	};
	//**********current report template******************************

	//**********generate order pdf report******************************
	$scope.generateReportPDF = function (order_id, downloadType, actionType) {
		if (order_id && downloadType) {
			$scope.successMsgOnPopup = '';
			$scope.errorMsgOnPopup = '';
			$scope.loaderMainShow();
			$http({
				method: "POST",
				url: BASE_URL + "sales/reports/generate-report-pdf",
				data: { order_id: order_id, downloadType: downloadType, actionType: actionType },
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.successMsgOnPopup = result.message;
					if (result.reportFileName) {
						angular.element('.hideNeedModificationOnPdf').hide();
						//actionType ? $scope.funGetBranchWiseReportList($scope.divisionID) : '';						
						actionType ? $scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id) : '';
						$scope.viewOrderReport.report_file_name = result.reportDataList.report_file_name;
						$scope.viewOrderReport.report_file_name_without_hf = result.reportDataList.report_file_name_without_hf;
						window.open(BASE_URL + result.reportFileName, '_blank');
					}
				} else {
					$scope.errorMsgOnPopup = result.message;
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (result, status, headers, config) {
				$scope.errorMsgShow($scope.defaultMsg);
				$scope.loaderMainHide();
				$scope.clearConsole();
			});
		}
	};
	//**********/generate pdf report******************************************

	//**********order report status confirm box******************************************************
	$scope.funConfirmReportStatus = function (order_id, type, reportConfirmMessage) {
		if (type == 'NeedModification' || type == 'NeedModificationBySectionIncharge' || type == 'NeedModificationByReviewer') {
			if (type == 'NeedModification') {
				$scope.funNeedReportModification(order_id);
			} else if (type == 'NeedModificationBySectionIncharge') {
				$scope.funNeedReportModificationBySectionIncharge(order_id);
			} else if (type == 'NeedModificationByReviewer') {
				$scope.funNeedReportModificationByReviewer(order_id);
			}
		} else {
			$ngConfirm({
				title: false,
				content: reportConfirmMessage,    //Defined in message.js and included in head
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
							if (type == 'BySectionIncharge') {
								$scope.saveFinalReportBySectionIncharge(order_id, 'confirm');
							} else if (type == 'ByReviewer') {
								$scope.saveFinalReportByReviewer(order_id, 'confirm');
							} else if (type == 'ConfirmReportByQADepartment') {
								$scope.funMoveOrderToNextStage(order_id);
							} else if (type == 'ConfirmReportByQA') {
								$scope.generateReportPDF('printReportTemplateDiv', order_id);
							}
						}
					},
					cancel: {
						text: 'cancel',
						btnClass: 'btn-default ng-confirm-closeIcon'
					}
				}
			});
		}
	};
	//**********/order report status confirm box***************************************************

	//**********save report by reporter*********************************************************
	$scope.saveFinalReportBySectionIncharge = function (order_id, formtype) {
		$scope.hideAlertMsg();
		$scope.loaderMainShow();
		$http({
			url: BASE_URL + "reports/save_final_report_by_section_incharge/" + formtype,
			method: "POST",
			data: { formData: $(orderReportFormByReporter).serialize() }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.showGradeType = false;
				$scope.showAmendmentWith = false;
				if (formtype == 'confirm') {
					$scope.backButton();
					$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
				} else {
					$scope.funAddTestParametersReportBySectionIncharge(order_id, false);
				}
				//$scope.funGetBranchWiseReportList($scope.divisionID);
				$scope.successMsgShow(result.message);
			} else if (result.error == 2) {
				$scope.funAddTestParametersReportBySectionIncharge(order_id, false);
				$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
				//$scope.funGetBranchWiseReportList($scope.divisionID);
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
	//**********save report by reporter******************************

	//**********save report by reviewer******************************
	$scope.saveFinalReportByReviewer = function (order_id, formtype) {
		$scope.hideAlertMsg();
		$scope.loaderMainShow();
		$http({
			url: BASE_URL + "reports/save_final_report_by_reviewer/" + formtype,
			method: "POST",
			data: { formData: $(orderReportFormByReporter).serialize() }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.viewEditTestStandard = false;
				$scope.viewNonEditTestStandard = true;
				$scope.viewEditAmendmentWith = false;
				$scope.viewNonEditAmendmentWith = true;
				$scope.viewEditAmendmentNo = false;
				if (formtype == 'confirm') {
					$scope.backButton();
					$scope.funFilterBranchWiseReportScopeList($scope.divisionID, order_id);
				} else {
					$scope.funAddTestParametersReportByReporter(order_id, false);
				}
				//$scope.funGetBranchWiseReportList($scope.divisionID);
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
	//**********save report by reviewer******************************

	//**********Adding of Order Parameter Result************************
	$scope.funSaveTestParametersResultByTester = function (division_id) {

		if ($scope.newTestReportflag) return;
		$scope.newTestReportflag = true;
		$scope.hideAlertMsg();
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "reports/save_order_test_param_result_by_tester",
			method: "POST",
			data: { formData: $(erpSaveTestParamResultReportForm).serialize() }
		}).success(function (result, status, headers, config) {
			$scope.newTestReportflag = false;
			if (result.error == 1) {
				$scope.showGradeType = false;
				$scope.funGetBranchWiseReportList(division_id);
				$scope.funAddTestParametersReportByTester(result.orderId, false);
				$scope.successMsgShow(result.message);
				if (result.hasPermToInvoiceTestResult == 1) {
					$scope.hasPermToInvoiceTestResult = false;
				}
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newTestReportflag = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********Adding of Order Parameter Result******************************

	//**********Adding of Order Parameter Result******************************
	$scope.saveOrderForInvoice = function (order_id, division_id) {

		if ($scope.newTestReportFinalise) return;
		$scope.newTestReportFinalise = true;
		$scope.hideAlertMsg();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "reports/save_order_invoice/" + order_id,
			method: "GET",
		}).success(function (result, status, headers, config) {
			$scope.newTestReportFinalise = false;
			if (result.error == 1) {
				$scope.IsViewReportList = false;
				$scope.IsViewAddReportByTester = true;
				$scope.IsViewDisplayTestReport = true;
				$scope.IsViewAddReportByReporterAndReviewer = true;
				$scope.IsViewAddReportByQADept = true;
				$scope.IsViewAddReportBySectionIncharge = true;
				$scope.orderParametersList = {};
				$scope.erpSaveTestParamResultReportForm.$setUntouched();
				$scope.erpSaveTestParamResultReportForm.$setPristine();
				$scope.successMsgShow(result.message);
				$scope.funFilterBranchWiseReportScopeList(division_id, order_id);
				//$scope.funGetBranchWiseReportList(division_id);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newTestReportFinalise = false;
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//**********Adding of Order Parameter Result******************************

	//**********deleting of reports**********************************************
	$scope.funDeleteReport = function (order_id, divisionId) {

		$scope.loaderMainShow();
		$http({
			url: BASE_URL + "reports/delete-report/" + order_id,
			method: "GET",
		}).success(function (data, status, headers, config) {
			if (data.error == 1) {
				$scope.successMsgShow(data.message);
				$scope.funFilterBranchWiseReportScopeList(divisionId, order_id);
				//$scope.funGetBranchWiseReportList(divisionId);
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
	//**********/deleting of reports*********************************************

	//**********confirm box******************************************************
	$scope.openFilterForm = function () {
		if ($scope.IsViewReportFilter) {
			$scope.IsViewReportFilter = false;
		} else {
			$scope.IsViewReportFilter = true;
		}
	};
	//**********/confirm box****************************************************

	//**********Read/hide More description************************************
	$scope.toggleDescription = function (type, id) {
		angular.element('#' + type + 'limitedText-' + id).toggle();
		angular.element('#' + type + 'fullText-' + id).toggle();
	};
	//*********/Read More description*****************************************

	//**********Read/hide More description* 15 dec,2017***********************************
	$scope.viewNonEditTestStandard = true;
	$scope.viewEditTestStandard = false;
	$scope.viewEditAmendmentWith = false;
	$scope.viewNonEditAmendmentWith = true;
	$scope.viewEditAmendmentNo = false;
	$scope.toggelEditReportPartAForm = function (product_cat_id, testStdId, report_id) {

		if ($scope.viewEditPartAForm == true) {
			$scope.viewEditPartAForm = false;
			$scope.viewEditTestStandard = false;
			$scope.viewEditAmendmentWith = false;
			$scope.viewEditAmendmentNo = false;
			$scope.viewNonEditablePartA = true;
			$scope.viewNonEditTestStandard = true;
			$scope.viewNonEditAmendmentWith = true;
			$scope.viewNonEditAmendmentNo = false;
		} else {
			$scope.viewEditOrderReport = {};
			$scope.viewNonEditTestStandard = false;
			$scope.viewNonEditablePartA = false;
			$scope.viewNonEditAmendmentWith = false;
			$scope.viewEditPartAForm = true;
			$scope.viewEditTestStandard = true;
			$scope.viewEditAmendmentWith = true;
			$scope.viewEditAmendmentNo = true;

			$http({
				url: BASE_URL + "sales/reports/get-edit-report-detail/" + report_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.viewEditOrderReport = result.viewEditReportData;
				}
			}).error(function (result, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});
			$scope.funGetTestStandards(product_cat_id, testStdId);
		}

	};
	$scope.funGetTestStandards = function (productCategoryId, testStandardId) {
		$http({
			url: BASE_URL + "sales/reports/get-test-Standards/" + productCategoryId,
			method: "GET",
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.testStandardList = result.getAllTestStdAccToDept;
				$scope.viewOrderReport.test_standard_id = { selectedOption: { test_std_id: testStandardId } };
			}
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	//*********/Read More description*****************************************

	//***************** Auto complete Section **********************************
	$scope.getMatches = function (searchText) {
		var deferred = $q.defer();
		$timeout(function () {
			var items = $scope.getItems(searchText.toUpperCase());
			if (typeof items != 'undefined') {
				deferred.resolve(items);
			}
		}, 1000);
		return deferred.promise;
	};
	$scope.getItems = function (searchText) {
		$http({
			method: 'GET',
			url: BASE_URL + 'sales/orders/get-sample-name-list/' + searchText
		}).success(function (result) {
			$scope.resultItems = result.itemsList;
			$scope.clearConsole();
		});
		return $scope.resultItems;
	};
	//***************** /Auto complete Section ********************************

	//**********Note Remark Report Options*************************************
	$scope.funNoteRemarkReportOptions = function (orderListData) {
		if (orderListData.division_id && orderListData.product_category_id) {

			$scope.sampleNameOption = orderListData.sample_description;
			$scope.standardNameOption = orderListData.test_std_name;
			$scope.amendmentNameOption = orderListData.with_amendment_no;
			$scope.loaderShow();

			$http({
				url: BASE_URL + "sales/reports/get-note-remark-report-options",
				method: "POST",
				data: { division_id: orderListData.division_id, product_category_id: orderListData.product_category_id }
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.noteRemarkReportOptions = result.returnData;
					$scope.getProductStandards(orderListData.product_category_id);
				}
				$scope.loaderHide();
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
	//**********Note Remark Report Options*************************************

	//**********display test Standards List dropdown code dropdown start***	
	$scope.getProductStandards = function (product_cat_parent_id) {
		$http({
			method: 'GET',
			url: BASE_URL + 'standard-wise-product/get-teststandars-list/' + product_cat_parent_id
		}).success(function (result) {
			$scope.testStandardsList = result.testStandardsList;
			$scope.clearConsole();
		});
	}
	//*****************/display parent category dropdown code dropdown start****

	//*********Get From To Date Init**********************************************
	$scope.funGetReviewReportDateInit = function () {
		angular.element('#report_date').datepicker({ dateFormat: "dd-mm-yy" });
	};
	//********/Get From To Date Init**********************************************

	//**********Dispatch order Section******************************************************
	$scope.funOpenOrderDispatchPopup = function (divid, action, orderId, orderNo) {
		$scope.hideAlertMsgPopup();
		$scope.dispatchOrderList = [];
		$scope.funGetDispatchDetail(orderId);
		$scope.dispatchOrder.order_id = orderId;
		$scope.dispatchOrder.order_no = orderNo;
		$("#" + divid).modal(action);
	};
	//******dispatch order details**********
	$scope.funGetDispatchDetail = function (orderId) {
		$scope.dispatchOrderList = [];
		var http_para_string = { formData: 'order_id=' + orderId };
		$http({
			url: BASE_URL + "reports/order_dispatched_detail",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.dispatchOrder.order_id = result.order_id;
				$scope.viewDispatchData = result.dispatchDetail;
				$scope.dispatchOrderList = result.dispatchDetail;
				angular.element('#modal-body-dol').addClass('height290');
			} else {
				angular.element('#modal-body-dol').removeClass('height290');
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//******/dispatch order details**********

	//***function Dispatch Report***********
	$scope.funDispatchReport = function () {
		$scope.loaderMainShow();
		$http({
			url: BASE_URL + "sales/reports/dispatch_report",
			method: "POST",
			data: { formData: $(erpDispatchOrderPopupForm).serialize() },
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.dispatchOrder = {};
				$scope.erpDispatchOrderPopupForm.$setUntouched();
				$scope.erpDispatchOrderPopupForm.$setPristine();
				$scope.dispatchOrder.order_id = result.order_id;
				$scope.dispatchOrder.order_no = result.order_no;
				$scope.funGetDispatchDetail(result.order_id);
				//$scope.funGetBranchWiseReportList($scope.divisionID);
				$scope.funFilterBranchWiseReportScopeList($scope.divisionID, result.order_id);
				$scope.successMsgShowPopup(result.message);
			} else {
				$scope.errorMsgShowPopup(result.message);
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
	//***/function Dispatch Report***********

	//**********/Dispatch order Section******************************************************

	//********* function Amend Report******************************/
	$scope.funAmendReport = function (order_id, divisionId) {
		$scope.loaderMainShow();
		$http({
			url: BASE_URL + "reports/amend-report/" + order_id,
			method: "GET",
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.IsVisiableSuccessMsg = false;
				$scope.successMsgShow(result.message);
				$scope.funFilterBranchWiseReportScopeList(divisionId, result.order_id);
				//$scope.funGetBranchWiseReportList(divisionId);
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
	//********* /function Amend Report******************************/

	//********* function Set Report Type Signature******************/
	$scope.funSetReportTypeSignatue = function (parentTypeId, childTypeId) {
		$timeout(function () {
			$scope.selectedSinatureTypeParent = parentTypeId;
			$scope.selectedSinatureTypeChild = childTypeId;
		}, 200);
	};
	//*********/function Set Report Type Signature******************/

	//**********View Allocated Section Incharges***********************************************
	$scope.funViewAllocatedSectionIncharges = function (orderId, orderNo) {
		var http_para_string = { formData: 'order_id=' + orderId };
		$http({
			url: BASE_URL + "reports/get-order-section-incharge-detail",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.viewSInchargeDataList = result.viewSInchargeData;
				$scope.viewSInchargeDataList.order_id = orderId;
				$scope.viewSInchargeDataList.order_no = orderNo;
				$(sectionInchargeOrderDetailPopupWindow).modal('show');
			} else {
				$(sectionInchargeOrderDetailPopupWindow).modal('hide');
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/View Allocated Section Incharges***********************************************

	//**********View Allocated Section Incharges***********************************************
	$scope.funViewRefreshedAllocatedSectionIncharges = function (orderId, orderNo) {
		var http_para_string = { formData: 'order_id=' + orderId };
		$http({
			url: BASE_URL + "reports/get-refreshed-order-section-incharge-detail",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.viewSInchargeDataList = result.viewSInchargeData;
				$scope.viewSInchargeDataList.order_id = orderId;
				$scope.viewSInchargeDataList.order_no = orderNo;
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//**********/View Allocated Section Incharges***********************************************

	//**********by reporter set analyst id checkbox selected array**********
	$scope.funSelectedReportTypeEnableDisable = function (className, idName) {
		if (angular.element('#' + idName).prop('checked') == true) {
			angular.element('.' + className).attr('disabled', true);
			angular.element('#' + idName).removeAttr('disabled');
		} else {
			angular.element('.' + className).removeAttr('disabled');
		}
	};
	//**********by reporter set analyst id checkbox selected array**********

	//**********Copy Pesticide Residue Result*******************************
	$scope.funCopyPesticideResidueResult = function (className) {
		if (angular.element('.' + className).is(":visible") == true) {
			var classNameValue = angular.element('.' + className).val();
			if (classNameValue) {
				angular.element('.' + className).each(function (index, element) {
					if (element.value == '') {
						element.value = classNameValue;
					}
				});
			}

		}
	};
	//*********/Copy Pesticide Residue Result*******************************

	//**************Set Selected Test Standard*****************************************
	$scope.funEditSetSelectedAWISValue = function(selectBoxId,selectBoxValue,indexId){
		$timeout(function(){
			$('#'+selectBoxId+indexId+' option[value='+selectBoxValue+']').prop('selected', true);
		},100);
	}
	//**************Set Selected Test Standard*****************************************
});