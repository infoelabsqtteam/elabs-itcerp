app.controller('schedulingsController', function ($scope, $http, BASE_URL, $ngConfirm) {

    //define empty variables
    $scope.prodata                   = '';
    $scope.successMessage            = '';
    $scope.errorMessage              = '';
    $scope.selected                  = '';
    $scope.jobAnalyticalSheetFile    = '';
    $scope.jobAnalyticalSheetCalFile = '';
    $scope.successMsgOnPopup         = '';
    $scope.errorMsgOnPopup           = '';
    $scope.defaultMsg                = 'Oops ! Sorry for inconvience server not responding or may be some error.';

    //sorting variables
    $scope.sortType                    = 'scheduling_id'; // set the default sort type
    $scope.sortReverse                 = false; // set the default sort order
    $scope.searchFish                  = ''; // set the default search/filter term
    $scope.selectedAssignedJobArr      = [];
    $scope.analyticalSheetDataList     = [];
    $scope.printOrderParametersList    = {};
    $scope.hplcEquipment               = false; // 01-08-2017
    $scope.chemicalEquipment           = false; // 03-08-2017
    $scope.downloadType                = '1';
    $scope.setJobSheetPrintBookingDate = '0';
    $scope.productTestDtlPopupID       = '';
    $scope.orderPopUpID                = '';
    $scope.updateOrderExpectedDueDate  = {};
    $scope.sampleWiseDisplay           = false;

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

    //**********loader show***************************************************
    $scope.loaderHide = function () {
        angular.element('#global_loader').fadeOut();
    };
    //**********/loader show**************************************************

    //**********If DIV is hidden it will be visible and vice versa*************	
    $scope.IsVisiableSuccessMsg = true;
    $scope.IsVisiableErrorMsg = true;
    $scope.IsVisiableSuccessMsgPopup = true;
    $scope.IsVisiableErrorMsgPopup = true;
    $scope.listPaymentFormBladeDiv = false;
    $scope.addPaymentFormBladeDiv = true;
    $scope.editPaymentFormBladeDiv = true;
    $scope.isFormSubmitted = false;
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

    /*****************Standard Value Type dropdown* in case of editing order parameter detail****************/
    $scope.standardValueTypes = '';
    $scope.standardValueTypes = function () {
        $scope.standardValueTypes.data_types = {
            availableTypeOptions: [
                { id: 'numeric', name: 'Numeric' },
                { id: 'alphanumeric', name: 'Alphanumeric' },
                { id: 'na', name: 'NA' },
            ],
            selectedOption: { id: 'numeric', name: 'Numeric' }
        };
    };
    //**********confirm box******************************************************
    $scope.funConfirmDeleteMessage = function (id, orderId, type) {
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
                        if (type == 'delete') {
                            $scope.funDeleteOrderParameter(id, orderId);
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
    //********** /confirm box***************************************************

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

    //**********navigate Form*********************************************
    $scope.navigatePaymentReceivedPage = function () {
        $scope.hideAlertMsg();
    };
    //**********/navigate Form*********************************************

    //**********Back Button*********************************************
    $scope.backButton = function () {
        $scope.jobSheetPrint = {};
        $scope.orderData = {};
        $scope.orderParametersList = {};
        $scope.printOrderParametersList = {};
        $scope.hideAlertMsg();
    };
    //**********/Back Button********************************************

    //**********Reset Button*********************************************
    $scope.resetButton = function (divisionID) {
        $scope.filterSchedulingJob = {};
        $('.schedulingEmployee').val('');
        $scope.funGetDivisionWisePendingJobs(divisionID);
        $scope.erpSchedulingFilterForm.$setUntouched();
        $scope.erpSchedulingFilterForm.$setPristine();
    };
    //**********/Reset Button*********************************************

    //************code used for sorting list order by fields*****************
    $scope.predicate = 'payment_received_hdr_id';
    $scope.reverse = true;
    $scope.sortBy = function (predicate) {
        $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
        $scope.predicate = predicate;
    };
    //************/code used for sorting list order by fields*****************

    //*****************display division dropdown start*****************/	
    $scope.divisionsCodeList = [];
    $http({
        method: 'POST',
        url: BASE_URL + 'division/get-divisioncodes'
    }).success(function (result) {
        $scope.divisionsCodeList = result;
        $scope.clearConsole();
    });
    //*****************display division dropdown end*****************/

    //*****************display parent category dropdown code dropdown start****
    $scope.funUserGetParentCategory = function () {
        $http({
            method: 'POST',
            url: BASE_URL + 'scheduling/get-user-parent-product-category'
        }).success(function (result) {
            $scope.parentCategoryList = result.parentCategoryList;
            $scope.clearConsole();
        });
    };
    $scope.funUserGetParentCategory();
    //*****************display parent category code dropdown end*****************

    //*****************display equipment dropdown code start*************
    $scope.equipmentTypesList = [];
    $http({
        method: 'POST',
        url: BASE_URL + 'equipment-types-list'
    }).success(function (result) {
        $scope.equipmentTypesList = result.equipmentTypesList;
        $scope.clearConsole();
    });
    //*****************display equipment code dropdown end****************

    //*****************sale_executive dropdown list**********************
    $scope.employeeDataList = [];
    $scope.funGetEmployeeList = function (division_id) {
        if (angular.isDefined(division_id)) {
            $http({
                method: 'GET',
                url: BASE_URL + 'scheduling/get-employee-division/' + division_id
            }).success(function (result) {
                $scope.employeeDataList = result.employeeList;
                $scope.clearConsole();
            });
        }
    };
    //*****************/sale_executive dropdown list*********************

    //**********Get Division Wise Pending Orders**************************
    $scope.funGetDivisionWisePendingJobs = function (divisionId) {
        $scope.divisionID = divisionId;
        $scope.isFormSubmitted = false;
        $scope.searchOrder = '';
        $scope.funFilterSchedulingJobs();
    };
    //*********/Get Division Wise Pending Orders**************************

    //**********Getting all Orders**********************************************
    $scope.funFilterSchedulingJobs = function (order_id=null) {
        $scope.hideAlertMsg();
        $scope.loaderMainShow();
        if(order_id){
            var dataSendUrl = 'unhold_order_id=' + order_id + '&' + $(erpSchedulingFilterForm).serialize();
        }else{
            var dataSendUrl = $(erpSchedulingFilterForm).serialize();
        }
        $http({
            url: BASE_URL + "scheduling/get-filter-scheduling-jobs",
            method: "POST",
            data: { formData: dataSendUrl }
        }).success(function (result, status, headers, config) {
            $scope.pendingJobData = result.pendingJobData;
            if(order_id){                
                angular.element('.pendingUnholdJob').removeClass('fontbd');
                angular.element('#selectedUnholdJob_'+order_id).removeClass('fontbd').addClass('fontbd');
            }
            $scope.loaderMainHide();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderMainHide();
        });
    };
    //**********/Getting all Orders**********************************************

    //**********Updating Scheduling Job for all Orders***************************
    $scope.funUpdateSchedulingJobs = function (divisionId) {
        $scope.hideAlertMsg();
        $scope.loaderMainShow();
        $http({
            url: BASE_URL + "scheduling/update-scheduling-jobs",
            method: "POST",
            data: { formData: $(erpSchedulingJobForm).serialize() }
        }).success(function (result, status, headers, config) {
            if (result.error == 1) {
                $scope.funFilterSchedulingJobs();
                $scope.funGetSchedulingUnholdJobs();
                $scope.successMsgShow(result.message);
                $scope.selectedJobArr = [];
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
        });
    };
    //*********/Updating Scheduling Job for all Orders***************************

    //**********Show/Hide Scheduling Job span and Input**************************
    angular.element('.spanInputDiv').hide();
    angular.element('.defaultSpanDiv').show();
    $scope.selectedJobArr = [];
    $scope.funShowHideDiv = function (schedulingId, employeeId, equipmentId) {
        angular.element('#spanTentativeDate' + schedulingId).hide();
        angular.element('#spanTentativeTime' + schedulingId).hide();
        angular.element('#spanEmployeeId' + schedulingId).hide();
        angular.element('.inputDiv' + schedulingId).show();
        angular.element('#employee_id_' + schedulingId).val(employeeId);
        $scope.equipment_id = equipmentId;
        $scope.selectedJobArr.push(schedulingId);
    };
    $scope.funCancelAction = function (schedulingId, orderId, equipmentId) {
        $scope.selectedJobArr.splice($scope.selectedJobArr.indexOf(schedulingId), 1);
        angular.element('#spanTentativeDate' + schedulingId).show();
        angular.element('#spanTentativeTime' + schedulingId).show();
        angular.element('#spanEmployeeId' + schedulingId).show();
        angular.element('.inputDiv' + schedulingId).hide();
        angular.element('#checkUncheckAllid' + schedulingId).prop('checked', false);
    };
    $scope.funCheckUncheckPendingJobAll = function (schedulingId, orderId, equipmentId) {
        if (equipmentId) {
            var checked = angular.element('#checkUncheckAllid' + schedulingId).prop('checked');
            if (checked) {
                angular.element('.checkUncheckAllClass' + orderId).hide();
                angular.element('#checkUncheckAllid' + schedulingId).show();
                angular.element('.spanTextContentClass' + equipmentId).hide();
                angular.element('.spanInputContentClass' + equipmentId).hide();
                angular.element('#spanTentativeDateContentId' + schedulingId).show();
                angular.element('#spanTentativeTimeContentId' + schedulingId).show();
                angular.element('#spanEmployeeContentId' + schedulingId).show();
                //angular.element('.spanTextContentClass').hide();
                //angular.element('.spanInputContentClass').hide();
            } else {
                angular.element('.checkUncheckAllClass' + orderId).show();
                angular.element('.checkUncheckAllClass' + orderId).prop('checked', false);
                angular.element('.spanTextContentClass' + equipmentId).show();
                angular.element('.spanInputContentClass' + equipmentId).hide();
                angular.element('.spanTextContentClass').show();
                angular.element('.spanInputContentClass').hide();

            }
        }
        //checkUncheckAllClass  26775
    };
    //**********Show/Hide Scheduling Job span and Input*******************

    //**********Get Division Wise Pending Orders**************************
    $scope.funGetDivisionWiseHttpRequest = function () {
        $scope.loaderMainShow();
        $http({
            url: BASE_URL + "scheduling/get-assigned-jobs",
            method: "POST",
            data: { formData: $(erpSchedulingFilterForm).serialize() }
        }).success(function (result, status, headers, config) {
            $scope.assignedJobData = result.assignedJobs;
            $scope.sampleWiseDisplay = result.sampleWiseDisplay;
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
    //*********/Get Division Wise Pending Orders**************************

    //**********Get Division Wise Pending Orders**************************
    $scope.funGetDivisionWiseAssignedJobs = function (divisionId) {
        $scope.divisionID = divisionId;
        $scope.isFormSubmitted = false;
        $scope.searchOrder = '';
        //$scope.funGetDivisionWiseHttpRequest();
    };
    //*********/Get Division Wise Pending Orders**************************

    //**********Getting all Orders**********************************************
    $scope.funFilterScheduledAssignedJobs = function () {
        $scope.hideAlertMsg();
        $scope.funGetDivisionWiseHttpRequest();
    };
    //**********/Getting all Orders**********************************************

    //**********Updating Scheduling Job for all Orders***************************
    $scope.funUpdateScheduledAssignedJobs = function (divisionId) {

        $scope.hideAlertMsg();
        $scope.loaderShow();

        $http({
            url: BASE_URL + "scheduling/update-scheduled-assigned-jobs",
            method: "POST",
            data: { formData: $(erpScheduledAssignedJobForm).serialize() }
        }).success(function (result, status, headers, config) {
            if (result.error == 1) {
                $scope.funGetDivisionWiseAssignedJobs(divisionId);
                $scope.successMsgShow(result.message);
                $scope.selectedAssignedJobArr = [];
            } else {
                $scope.errorMsgShow(result.message);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderHide();
        });
    };
    //*********/Updating Scheduling Job for all Orders***************************

    //**********Show/Hide Scheduling Job span and Input**************************
    $scope.funShowHideAssignedJobDiv = function (schedulingId) {
        angular.element('#spanStatus' + schedulingId).hide();
        angular.element('#spanReason' + schedulingId).hide();
        angular.element('.inputDiv' + schedulingId).show();
        $scope.selectedAssignedJobArr.push(schedulingId);
    };
    $scope.funCancelAssignedJobAction = function (schedulingId) {
        $scope.selectedAssignedJobArr.splice($scope.selectedAssignedJobArr.indexOf(schedulingId), 1);
        angular.element('#spanStatus' + schedulingId).show();
        angular.element('#spanReason' + schedulingId).show();
        angular.element('.inputDiv' + schedulingId).hide();
        //$scope.isFormSubmitted = false;	
    };
    //**********Show/Hide Scheduling Job span and Input**************************

    //**************** Job Sheet Printing 29 june,2017****************************
    $scope.funDateWiseOrders = function (selectedDate, divisionId) {
        $scope.printOrderParametersList = {};
        $scope.orderData = {};
        $scope.orderParametersList = {};
        $scope.divisionID = divisionId;
        $scope.setJobSheetPrintBookingDate = selectedDate;
        $http({
            method: 'Post',
            url: BASE_URL + 'scheduling/get-job-sheet-print-order-number',
            data: { formData: $(erpJobSheetPrintForm).serialize() },
        }).success(function (result) {
            $scope.ordersList = result.orderNumberList;
            $scope.clearConsole();
        });
    };
    //**************** / Job Sheet Printing****************************************

    /**************** get selected order detail 29 june,2017**********************/
    $scope.funGetOrderDetail = function () {
        $scope.orderParametersList = {};
        $scope.orderData = {};
        $scope.loaderMainShow();
        $http({
            method: 'Post',
            url: BASE_URL + 'scheduling/get-job-sheet-print-order-list',
            data: { formData: $(erpJobSheetPrintForm).serialize() },
        }).success(function (result) {
            $scope.orderData = result.orderList;
            $scope.clearConsole();
            $scope.loaderMainHide();
        }).error(function (result, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShowPopup($scope.defaultMsg);
            }
            $scope.loaderMainHide();
        });
    };
    /**************** / get selected order detail**********************/

    //**********Viewing of Order*************************************************
    var jobSheetOrderParametersList = {};
    $scope.funViewOrder = function (orderId) {
        $scope.disableDeleteIcon = false;
        $scope.hplcEquipment = false;
        $scope.GlobalOrderId = orderId;
        $scope.hideAlertMsg();
        $scope.loaderMainShow();
        $http({
            url: BASE_URL + 'scheduling/view_order/' + orderId,
            method: "GET",
        }).success(function (result, status, headers, config) {
            $scope.printOrderParametersList = {};
            if (result.error == 1) {
                $scope.viewOrderReport = result.orderList;
                $scope.product_test_id = result.orderList.product_test_id ? result.orderList.product_test_id : '0';
                if ($scope.viewOrderReport.status > 2) {
                    $scope.disableDeleteIcon = true;
                }
                $scope.orderParametersList = result.orderParameterList;
                jobSheetOrderParametersList = result.orderParameterList;
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

    //**********Viewing of Order Report***************************************
    $scope.funShowReport = function (orderId, divId, orderNumber) {
        //console.log(orderId);
        $scope.isViewInvoiceReport = false;
        $scope.hplcEquipment = false;
        $scope.chemicalEquipment = false;
        $scope.hideAlertMsg();
        $scope.closeEditOrder();
        $scope.loaderShow();

        var type = "returnJson";
        $http({
            url: BASE_URL + 'scheduling/view_order/' + orderId,
            method: "GET",
        }).success(function (result, status, headers, config) {
            if (result.error == 1) {
                $scope.orderParametersList = {};
                $scope.printOrderReport = result.orderList;
                $scope.printOrderParametersList = result.orderParameterList;
                $scope.jobAnalyticalSheetFile = result.orderList.job_analytical_sheet_file;
                $scope.jobAnalyticalSheetCalFile = result.orderList.job_analytical_sheet_cal_file;
            } else {
                $scope.errorMsgShow(result.message);
            }
            $scope.loaderHide();
        }).error(function (result, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShowPopup($scope.defaultMsg);
            }
            $scope.loaderHide();
        });
    };
    //**********/Viewing of Order*********************************************

    //********** check equipment type   *******************/
    $scope.checkEquipmentType = function (orderId, type) {
        $http.post(BASE_URL + 'scheduling/check-equipment/' + orderId + '/' + type, {
            //data: {formData:orderNumber },
        }).success(function (result) {
            if (result.error == '1') {
                if (result.attachedEquipment == 'hplc' && result.attachedEquipment == 'chemically') {
                    $scope.hplcEquipment = true;
                    $scope.chemicalEquipment = true;
                } else if (result.attachedEquipment == 'hplc') {
                    $scope.hplcEquipment = true;
                    $scope.chemicalEquipment = false;
                } else if (result.attachedEquipment == 'chemically') {
                    $scope.chemicalEquipment = true;
                    $scope.hplcEquipment = false;
                } else {
                    $scope.hplcEquipment = false;
                    $scope.chemicalEquipment = false;
                }
            }
        });
    };

    //**********generate job sheet print pdf 25-07-2017******************************************
    $scope.generateJobSheetPrintPDF = function (divId, orderNumber) {

        var jobSheet_name = jobSheetPrintNamePrefix + orderNumber + ".pdf";
        $scope.loaderMainShow();
        angular.element(".page-break").show();

        kendo.drawing.drawDOM(angular.element("#" + divId), {
            forcePageBreak: angular.element(".page-break"),
            paperSize: "A2",
            margin: { top: "1cm", right: "3cm", bottom: "1cm", left: "3cm", },
        }).then(function (group) {
            kendo.drawing.pdf.toDataURL(group, function (dataURL) {
                var formD = new FormData();
                formD.append("job_sheet_file_name", jobSheet_name);
                formD.append("job_sheet_file", dataURL);
                formD.append("order_number", orderNumber);
                $http({
                    url: BASE_URL + "scheduling/generate-job-sheet-pdf",
                    headers: { 'Content-Type': undefined },
                    data: formD,
                    method: "POST",
                    processData: false,
                    contentType: false,
                }).success(function (result, status, headers, config) {
                    if (result.error = '1') {
                        angular.element(".page-break").hide();
                        window.open(BASE_URL + result.pdfUrl, '_blank');
                    }
                    $scope.loaderMainHide();
                }).error(function (result, status, headers, config) {
                    if (status == '500' || status == '404') {
                        $scope.errorMsgShowPopup($scope.defaultMsg);
                    }
                    $scope.loaderMainHide();
                });
            });
        });
    };
    //**********generate order pdf report****************************** 

    //****** close print form*******************************************
    $scope.close = function () {
        $scope.printOrderParametersList = '';
        angular.element(".hideDiv").show();
    };
    //****** close print form*******************************************

    //****************************Open Update Order Form*********************************/
    $scope.openUpdateOrderForm = function (para_cat_id, product_cat_id, subCatPara) {
        $scope.parameterList = [];
        $scope.methodList = [];
        $scope.equipmentList = [];

        if (subCatPara) {
            var analysis_id = subCatPara.analysis_id;
            $scope.AnalysisId = subCatPara.analysis_id;
            $scope.loaderShow();
            $http.post(BASE_URL + "scheduling/get-edit-form-dropdowns/" + para_cat_id + '/' + product_cat_id, {
                data: subCatPara.analysis_id,
            }).success(function (result, status, headers, config) {
                $scope.standardValueTypes();
                $scope.parameterList = result.parameterList;
                $scope.methodList = result.methodList;
                $scope.equipmentList = result.equipmentList;
                $scope.nablScopeList = result.nablScopeList;
                $scope.nableScopeValue = (result.orderParameterList.order_parameter_nabl_scope == null) ? '0' : result.orderParameterList.order_parameter_nabl_scope;

                $('#claim_value' + analysis_id).val(result.orderParameterList.claim_value);
                $('#standard_value_from' + analysis_id).val(result.orderParameterList.standard_value_from);
                $('#standard_value_to' + analysis_id).val(result.orderParameterList.standard_value_to);
                if ($scope.parameterList) {
                    $scope.test_parameter_id = {
                        selectedOption: { "id": result.orderParameterList.test_parameter_id }
                    };
                }
                if ($scope.equipmentList) {
                    $scope.equipment_type_id = {
                        selectedOption: { "id": result.orderParameterList.equipment_type_id }
                    };
                }
                if ($scope.methodList) {
                    $scope.method_id = {
                        selectedOption: { "id": result.orderParameterList.method_id }
                    };
                }
                if ($scope.nablScopeList) {
                    $scope.order_parameter_nabl_scope = {
                        selectedOption: { "id": $scope.nableScopeValue }
                    };
                }
                if (subCatPara.standard_value_type) {
                    $scope.standardValueType = {
                        selectedOption: { "id": subCatPara.standard_value_type }
                    };
                }
                $scope.clearConsole();
                $scope.loaderHide();
            }).error(function (result, status, headers, config) {
                if (status == '500' || status == '404') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
            });
        }
    };

    //**************update test parameters category and its data************************
    $scope.updateOrderParameterDetails = function (GlobalOrderId) {

        $scope.loaderShow();
        $scope.AnalysisId = '';

        $http.post(BASE_URL + "scheduling/update-parameters-details", {
            data: { formData: $(updateTestParameterDetailForm).serialize() },
        }).success(function (resData, status, headers, config) {
            if (resData.success) {
                $scope.funViewOrder(GlobalOrderId);
                $scope.successMsgShow(resData.success);
            } else {
                $scope.errorMsgShow(resData.error);
                $('#updateTestParameterDetailForm').show();
            }
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
    //**************/Update test parameters category and its data*********************

    //**********Read/hide More description********************************************
    $scope.toggleDescription = function (type, id) {
        angular.element('#' + type + 'limitedText-' + id).toggle();
        angular.element('#' + type + 'fullText-' + id).toggle();
    };
    //*********/Read/hide More description********************************************

    /******parameterList close edit form*********/
    $scope.cancelEditOrder = function () {
        $scope.parameterList = [];
        $scope.methodList = [];
        $scope.equipmentList = [];
        $scope.AnalysisId = '';
    };
    /******parameterList close edit form*********/

    /******parameterList edit form back btn*********/
    $scope.closeEditOrder = function () {
        $scope.orderParametersList = [];
    };
    /******parameterList edit form back btn*********/

    /******parameterList edit form back btn*********/
    $scope.funChangeOrderId = function () {
        $scope.orderParametersList = [];
        $scope.orderData = [];
    };
    /******parameterList edit form back btn*********/

    //********************************/by nisha**************************************/

    //**********generate Analytical Sheet Pdf******************************************
    $scope.generateAnalyticalSheetPdf = function (orderId, downloadType, actionType) {
        $scope.successMsgOnPopup = '';
        $scope.errorMsgOnPopup = '';
        $scope.loaderMainShow();
        $http({
            method: "POST",
            url: BASE_URL + "scheduling/generate-analytical-sheet-pdf",
            data: { order_id: orderId, downloadType: downloadType, actionType: actionType },
        }).success(function (result, status, headers, config) {
            if (result.error == 1) {
                $scope.successMsgOnPopup = result.message;
                if (result.analyticalSheetFileName && result.analyticalDataList) {
                    $scope.jobAnalyticalSheetFile = result.analyticalDataList.job_analytical_sheet_file;
                    $scope.jobAnalyticalSheetCalFile = result.analyticalDataList.job_analytical_sheet_cal_file;
                    window.open(BASE_URL + result.analyticalSheetFileName, '_blank');
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
    };
    //**********/generate order pdf report******************************************

    //**********Generate Analyst Assigned Jobs**************************************
    $scope.funGenerateAnalystAssignedJobs = function () {

        $scope.hideAlertMsg();
        $scope.loaderMainShow();

        $http({
            url: BASE_URL + "scheduling/generate-analyst-sheet-pdf",
            method: "POST",
            data: { formData: $(erpSchedulingFilterForm).serialize() }
        }).success(function (result, status, headers, config) {
            $scope.loaderMainHide();
            if (result.error == 1) {
                $scope.successMsgShow(result.message);
                $scope.pendingJobData = result.pendingJobData;
                if (result.analystFileName) {
                    window.open(BASE_URL + result.analystFileName, '_blank');
                }
            } else {
                $scope.errorMsgShow($scope.message);
            }
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        });
    };
    //**********/Generate Analyst Assigned Jobs**************************************

    //**********Deleting of Order Parameter******************************************
    $scope.funDeleteOrderParameter = function (analysisId, orderId) {

        if(!orderId || !analysisId)return;

        $scope.loaderMainShow();
        $scope.hideAlertMsg();

        $http({
            url: BASE_URL + "scheduling/delete-order-parameter",
            method: "POST",
            data: { analysis_id: analysisId, order_id: orderId },
        }).success(function (result, status, headers, config) {
            if (result.error == 1) {
                $scope.funViewOrder(orderId);
                angular.element('#eddModelEddMsg'+orderId).html(result.expected_due_date);
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
    //*********/Deleting of Order Parameter******************************************

    //*********Adding of New Parameter in an order***********************************
    $scope.funAddMoreTestProductStandardParamentersPopup = function (orderId, testId) {
        if (orderId && testId) {
            $scope.testProductParamentersList = [];
            $scope.allPopupSelectedParametersArray = [];
            $scope.productTestDtlPopupID = testId;
            $scope.orderPopUpID = orderId;
            $scope.loaderMainShow();
            $http({
                method: 'POST',
                url: BASE_URL + 'scheduling/edit-get-product-test-parameters-list/' + testId + '/' + orderId,
            }).success(function (result) {
                $scope.testProductParamentersList = result.productTestParametersList;
                $('#selectedAll').prop('checked', true);
                $('#addSchedulingProductParametersPopup').modal('show');
                $scope.loaderMainHide();
            });
        }
    };

    $scope.funRefreshAddMoreTestProductStandardParamentersPopup = function (orderId, testId) {
        if (orderId && testId) {
            $scope.testProductParamentersList = [];
            $scope.productTestDtlPopupID = testId;
            $scope.orderPopUpID = orderId;
            $scope.loaderMainShow();
            $http({
                method: 'POST',
                url: BASE_URL + 'scheduling/edit-get-product-test-parameters-list/' + testId + '/' + orderId,
            }).success(function (result) {
                $scope.testProductParamentersList = result.productTestParametersList;
                $scope.loaderMainHide();
            });
        }
    };

    $scope.funUpdateOrderParameterDetailList = function (orderId) {

        $scope.loaderMainShow();

        $http.post(BASE_URL + "scheduling/save-parameters-details", {
            data: { formData: $(addNewParametersForm).serialize() },
        }).success(function (result, status, headers, config) {
            if (result.error == '1') {
                $scope.testProductParamenters = [];
                $scope.funViewOrder(orderId);
                angular.element('#eddModelEddMsg'+orderId).html(result.expected_due_date);
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
    //********/Adding of New Parameter in an order***********************************

    //********************parameterList close edit form***************
    $scope.cancelAddOrder = function () {
        $scope.testProductParamenters = [];
    };
    //*******************/parameterList close edit form***************

    //*****************display update Test Product Paramenter Popup dropdown***********
    $scope.funUpdateTestProductParamenterPopup = function () {
        $scope.loaderMainShow();
        $http.post(BASE_URL + 'scheduling/edit-get-product-test-parameters', {
            data: { formData: $(testParametersForm).serialize() },
        }).success(function (result, status, headers, config) {
            $scope.loaderMainHide();
            $scope.clearConsole();
            $scope.testProductParamenters = result.productTestParametersList;
            $('#addSchedulingProductParametersPopup').modal('hide');
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '400') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        });
    };
    //*****************/display update Test Product Paramenter Popup dropdown***********

    //*****************display Test Product Standard dropdown**********************
    $scope.funChangeTestParameterValueAccToClaim = function (product_test_dtl_id) {
        var claimValue = angular.element('#claim_value' + product_test_dtl_id).val();
        var standardValueFrom = angular.element('#org_standard_value_from' + product_test_dtl_id).val();
        var standardValueTo = angular.element('#org_standard_value_to' + product_test_dtl_id).val();

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
    //*****************/display Test Product Standard dropdown* *********************

    //*****************display Claim Value Unit**********************
    $scope.funChangeTestParameterValueAccToClaimUnit = function (product_test_dtl_id) {
        var claimValueInput = angular.element('#claim_value' + product_test_dtl_id).val();
        var claimValueUnitInput = angular.element('#claim_value_unit' + product_test_dtl_id).val();
        var standardValueFromUnit = angular.element('#standard_value_from' + product_test_dtl_id).val();
        var standardValueToUnit = angular.element('#standard_value_to' + product_test_dtl_id).val();
        if ($.isNumeric(claimValueUnitInput)) {
            claimValueUnitInput = '';
        }
        if (claimValueInput && claimValueUnitInput && standardValueFromUnit && standardValueToUnit) {
            angular.element('#standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit + ' ' + claimValueUnitInput);
            angular.element('#standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit + ' ' + claimValueUnitInput);
        } else {
            angular.element('#standard_value_from_text' + product_test_dtl_id).html(standardValueFromUnit);
            angular.element('#standard_value_to_text' + product_test_dtl_id).html(standardValueToUnit);
        }
    };

    //*****************display Test Product Standard dropdown* ********************************
    $scope.funEditChangeTestParameterValueAccToClaim = function (product_test_dtl_id, analysisId) {
        var claimValue = $('#claim_value' + analysisId).val();
        $('#standard_value_from' + analysisId).val('');
        $('#standard_value_to' + analysisId).val('');
        var standardValueFrom = $('#edit_standard_value_from' + analysisId).val();
        var standardValueTo = $('#edit_standard_value_to' + analysisId).val();
        if (claimValue != '' && standardValueFrom && standardValueTo) {
            var standardValueFromClaimed = standardValueToClaimed = '0.00';

            var standardValueFromClaimed = !isNaN(standardValueFrom) ? ((standardValueFrom * claimValue) / 100).toFixed(9) : standardValueFrom;
            var standardValueToClaimed = !isNaN(standardValueTo) ? ((standardValueTo * claimValue) / 100).toFixed(9) : standardValueTo;

            angular.element('#standard_value_from' + analysisId).val(standardValueFromClaimed);
            angular.element('#standard_value_to' + analysisId).val(standardValueToClaimed);
        } else {
            angular.element('#standard_value_from' + analysisId).val(standardValueFrom);
            angular.element('#standard_value_to' + analysisId).val(standardValueTo);
        }
        $scope.funChangeTestParameterValueAccToClaimUnit(product_test_dtl_id);
    };
    //*****************display Test Product Standard dropdown**************************

    //*********************select all functionality************************************
    $scope.toggleAll = function () {
        $scope.allPopupSelectedParametersArray = [];
        var checkAllStatus = $('#selectedAll').prop('checked');
        if (checkAllStatus) {
            $('.parametersCheckBox').prop('checked', true);
            $(".parametersCheckBox:checked").each(function () {
                $scope.allPopupSelectedParametersArray.push(this.value);
            });
        } else {
            angular.element('.parametersCheckBox').prop('checked', false);
        }
    };
    //********************/select all functionality**************************

    //*****************single checkbox***************************************
    $scope.funCheckParameterCheckedOrNotValues = function (dltId) {
        var paraStatus = $('#checkOneParameter_' + dltId).prop('checked');
        if (paraStatus) {
            $scope.allPopupSelectedParametersArray.push(dltId);
        } else {
            $('#selectedAll').prop('checked', false);
            $scope.allPopupSelectedParametersArray.pop(dltId);
        }
    };

    //*****************Update Expected Due Date***************************************
    $scope.funUpdateAndSendExpectedDueDatePopup = function (orderObj) {
        $scope.updateOrderExpectedDueDate.order_id = orderObj.order_id;
        $scope.updateOrderExpectedDueDate.order_no = orderObj.order_no;
        $scope.updateOrderExpectedDueDate.expected_due_date = orderObj.expected_due_date;
        $scope.updateOrderExpectedDueDate.tat_in_days = orderObj.tat_in_days;
        $('#updateOrderExpectedDueDatePopupWindow').modal('show');
    };
    $scope.funUpdateOrderExpectedDueDate = function () {
        $scope.hideAlertMsgPopup();
        $scope.hideAlertMsg();
        $scope.loaderMainShow();
        $http({
            method: 'Post',
            url: BASE_URL + 'scheduling/update-expected-due-date',
            data: { formData: $(erpUpdateOrderExpectedDueDatePopupForm).serialize() },
        }).success(function (result) {
            if (result.error == 1) {
                $scope.updateOrderExpectedDueDate = {};
                $scope.funGetOrderDetail();
                $('#updateOrderExpectedDueDatePopupWindow').modal('hide');
                $scope.successMsgShow(result.message);
            } else {
                $scope.errorMsgShowPopup(result.message);
            }
            $scope.clearConsole();
            $scope.loaderMainHide();
        }).error(function (result, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShowPopup($scope.defaultMsg);
            }
            $scope.clearConsole();
            $scope.loaderMainHide();
        });
    };
    //****************/Update Expected Due Date***************************************

    //**********Getting all Orders**********************************************
    $scope.pendingUnholdJobData = [];
    $scope.funGetSchedulingUnholdJobs = function () {
        $scope.hideAlertMsg();
        $http({
            url: BASE_URL + "scheduling/get-scheduling-unhold-jobs",
            method: "POST",
            data: { formData: '' }
        }).success(function (result, status, headers, config) {
            $scope.pendingUnholdJobData = result.pendingUnholdJobData;
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderMainHide();
        });
    };
    //**********/Getting all Orders**********************************************

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
}).directive('uiDate', function () {
    return {
        require: '?ngModel',
        link: function ($scope, element, attrs, controller) {
            element.mask("99-99-9999", {
                completed: function () {
                    controller.$setViewValue(this.val());
                    $scope.$apply();
                }
            });
        }
    };
}).directive('uiTime', function () {
    return {
        require: '?ngModel',
        link: function ($scope, element, attrs, controller) {
            element.mask("99:99:99", {
                completed: function () {
                    controller.$setViewValue(this.val());
                    $scope.$apply();
                }
            });
        }
    };
});