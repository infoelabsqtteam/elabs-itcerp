app.controller('orderHoldUnholdController', function($scope, $timeout, $http, BASE_URL, $ngConfirm) {
    
    //define empty variables
    $scope.custdata = '';
    $scope.searchFish = '';         // set the default search/filter term
    $scope.limitFrom = 0;
    $scope.limitTo = TOTAL_RECORD;
    $scope.searchCustomer = {};
    $scope.sendMailBtn = true;
    $scope.checkboxesCount = '0';

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
    $scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';
    $scope.InvoicingTypeYes = true;
    $scope.DiscountTypeNo = false;

    $scope.addEmailFormDiv = true;
    $scope.isVisibleEmailListDiv = false;
    $scope.showCustomerEmailSection = false;
    $scope.editEmailFormDiv = false;
    $scope.showCustomerSection = true;
    $scope.customer = {};

    //**********scroll to top function**********
    $scope.moveToMsg = function() {
        $('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
    };
    //**********/scroll to top function**********

    //**********loader show****************************************************
    $scope.loaderShow = function() {
        angular.element('#global_loader').fadeIn('slow');
    };
    //**********/loader show**************************************************

    //**********loader show***************************************************
    $scope.loaderHide = function() {
        angular.element('#global_loader').fadeOut('slow');
    };
    //**********/loader show**************************************************

    //**********loader show****************************************************
    $scope.loaderMainShow = function() {
        angular.element('#global_loader_onload').fadeIn('slow');
    };
    //**********/loader show**************************************************

    //**********loader show***************************************************
    $scope.loaderMainHide = function() {
        angular.element('#global_loader_onload').fadeOut('slow');
    };
    //**********/loader show**************************************************

    //**********Clearing Console********************************************
    $scope.clearConsole = function() {
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
    $scope.successMsgShow = function(message) {
        $scope.hideAlertMsgPopup();
        $scope.hideAlertMsg();
        $scope.successMessage = message;
        $scope.IsVisiableSuccessMsg = false;
        $scope.IsVisiableErrorMsg = true;
        $scope.moveToMsg();
    };
    //********** /successMsgShow************************************************

    //**********errorMsgShow**************************************************
    $scope.errorMsgShow = function(message) {
        $scope.hideAlertMsgPopup();
        $scope.hideAlertMsg();
        $scope.errorMessage = message;
        $scope.IsVisiableSuccessMsg = true;
        $scope.IsVisiableErrorMsg = false;
        $scope.moveToMsg();
    };
    //********** /errorMsgShow************************************************

    //**********hide Alert Msg*************
    $scope.hideAlertMsg = function() {
        $scope.IsVisiableSuccessMsg = true;
        $scope.IsVisiableErrorMsg = true;
    };
    //********** /hide Alert Msg**********************************************/

    //**********successMsgShowPopup**************************************************
    $scope.successMsgShowPopup = function(message) {
        $scope.successMessagePopup = message;
        $scope.IsVisiableSuccessMsgPopup = false;
        $scope.IsVisiableErrorMsgPopup = true;
        $scope.moveToMsg();
    };
    //********** /successMsgShowPopup************************************************

    //**********errorMessagePopup**************************************************
    $scope.errorMsgShowPopup = function(message) {
        $scope.errorMessagePopup = message;
        $scope.IsVisiableSuccessMsgPopup = true;
        $scope.IsVisiableErrorMsgPopup = false;
        $scope.moveToMsg();
    };
    //********** /errorMessagePopup************************************************

    //**********successMsgShow**************************************************
    $scope.uploadMsgShow = function(uploadMsg) {
        $scope.uplodedMessageHide = false;
        $scope.notUplodedMessageHide = false;
        $scope.notUplodedMessageHide = false;
        $scope.uplodedMessage = uploadMsg.uploaded;
        $scope.notUplodedMessage = uploadMsg.notUploaded;
        $scope.notUplodedMessage = uploadMsg.duplicate;
    };
    //********** /successMsgShow************************************************

    //**********hide Alert Msg*************
    $scope.hideUploadAlertMsg = function() {
        $scope.uplodedMessageHide = true;
        $scope.notUplodedMessageHide = true;
        $scope.notUplodedMessageHide = true;
    };
    //********** /hide Alert Msg**********************************************

    //**********hideAlertMsgPopup**********************************************
    $scope.hideAlertMsgPopup = function() {
        $scope.IsVisiableSuccessMsgPopup = true;
        $scope.IsVisiableErrorMsgPopup = true;
    };
    //********** /hideAlertMsgPopup**********************************************

    //*****************code used for sorting list order by fields********************
    $scope.predicate = 'chd_id';
    $scope.sortType = 'chd_id';     // set the default sort type
    $scope.sortReverse = false;     // set the default sort order
    $scope.reverse = false;
    $scope.sortBy = function(predicate) {
        $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
        $scope.predicate = predicate;
    };
    //****************/code used for sorting list order by fields********************

    //********************LISTING OF CUSTOMER START**********************************
    var tempSearchKeyword;
    $scope.funGetHoldCustomers = function(limitFrom, limitTo) {
        
        $scope.loaderShow();
        $scope.limitFrom = limitFrom;
        $scope.limitTo = limitTo;
        $scope.searchCustomer.limitFrom = limitFrom;
        $scope.searchCustomer.limitTo = limitTo;

        $http.post(BASE_URL + 'master/hold-unhold-customers/get-hold-customers', {
            data: { formData: $scope.searchCustomer },
        }).success(function(result, status, headers, config) {
            if (result.error == '0') {
                $scope.errorMsgShow(result.message);
            }
            $scope.custdata = result.customersList;
            $scope.clearConsole();
            $scope.loaderHide();
        }).error(function(result, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
        });
    };
    $scope.funGetLimitFromCustomers = function(limitFrom) {
        $scope.hideAlertMsg();
        tempSearchKeyword = limitFrom;
        $timeout(function() {
            if (tempSearchKeyword == limitFrom) {
                $scope.limitFrom = limitFrom;
                $scope.limitTo = $scope.limitTo;
                $scope.funGetHoldCustomers($scope.limitFrom, $scope.limitTo);
            }
        }, 500);
    };
    $scope.funGetLimitToCustomers = function(limitTo) {
        $scope.hideAlertMsg();
        tempSearchKeyword = limitTo;
        $timeout(function() {
            if (tempSearchKeyword == limitTo) {
                $scope.limitFrom = $scope.limitFrom;
                $scope.limitTo = limitTo;
                $scope.funGetHoldCustomers($scope.limitFrom, $scope.limitTo);
            }
        }, 500);
    };
    //**************multisearch start here***********************************
    $scope.multiSearchTr = true;
    $scope.multisearchBtn = false;
    $scope.getMultiSearch = function(searchKeyword) {
        $scope.hideAlertMsg();
        $scope.filterCustomers = '';
        tempSearchKeyword = searchKeyword;
        $timeout(function() {
            if (tempSearchKeyword == searchKeyword) {
                $scope.funGetHoldCustomers($scope.limitFrom, $scope.limitTo);
            }
        }, 1000);
    };
    $scope.closeMultisearch = function() {
        $scope.multiSearchTr = true;
        $scope.multisearchBtn = false;
        $scope.refreshMultisearch();
    };
    $scope.refreshMultisearch = function() {
        $scope.searchCustomer = {};
        $scope.funGetHoldCustomers($scope.limitFrom, $scope.limitTo);
    };
    $scope.openMultisearch = function() {
        $scope.multiSearchTr = false;
        $scope.multisearchBtn = true;
    };
    //**************multisearch end here****************************************

    /************* BACK TO CUSTOMER LISTING *****/
    $scope.backButton = function() {
        $scope.addEmailFormDiv = true;
        $scope.isVisibleEmailListDiv = false;
        $scope.showCustomerEmailSection = false;
        $scope.listCustomer = false;
        $scope.showCustomerSection = true;
    };

    /************* CLOSE EDIT EMAIL FORM*****/
    $scope.closeButton = function() {
        $scope.addEmailFormDiv = false;
        $scope.editEmailFormDiv = false;
    };

    /************* RESET ADD FORM*****/
    $scope.resetButton = function() {
        $scope.addCustomerEmails = {};
    };
    //************************Function Hold/Unhold*****************************************************

    // FUNCTION TO CONFIRM UNHOLD CONFIRM
    $scope.funConfirmUnholdMessage = function(chd_customer_id, chd_customer_status) {
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
                    action: function() {
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
    $scope.funUnholdCustomer = function(chd_customer_id, chd_customer_status) {
        $scope.loaderMainShow();
        var http_para_string = { chd_customer_id: chd_customer_id, chd_customer_status: chd_customer_status };
        $http({
            url: BASE_URL + "master/hold-unhold-customers/unhold-customer",
            method: "POST",
            data: http_para_string,
        }).success(function(result, status, headers, config) {
            if (result.error == '1') {
                $scope.funGetHoldCustomers($scope.limitFrom, $scope.limitTo);
                $scope.successMsgShow(result.message);
            } else {
                $scope.errorMsgShow(result.message);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        });
    };
    //***********************/Function Hold/Unhold*****************************************************
    
    //******** select one checkbox*************************************************
    $scope.funCheckAtLeastOneIsChecked=function(customerId){
        if(angular.element('#hold_unhold_'+customerId).prop('checked') == false){
            angular.element('#selectedAll').prop('checked',false);
        }		
        $scope.checkboxesCount = angular.element('input[name="customer_id[]"]:checked').length;
        if($scope.checkboxesCount > 0){
            $scope.sendMailBtn = false;	
        }else{
            $scope.sendMailBtn = true;
        }
    };
    //********/select one checkbox*************************************************
    
    //**********Send Mail Function and Confirm box*********************************
    $scope.funConfirmHoldMailMessage = function(){ 
        $ngConfirm({
            title     : false,
            content   : defaultMailMsg,
            animation : 'right',
            closeIcon : true,
            closeIconClass    : 'fa fa-close',
            backgroundDismiss : false,
            theme   : 'bootstrap',
            columnClass : 'col-sm-5 col-md-offset-3',
            buttons : {
                OK: {
                    text: 'ok',
                    btnClass: 'btn-primary',
                    action: function () {
                        $scope.funSendMailToHoldCustomers();
                    }
                },
                cancel: {
                    text     : 'cancel',
                    btnClass : 'btn-default ng-confirm-closeIcon'					
                }
            }
        });
    };
    $scope.funSendMailToHoldCustomers = function() {
        $scope.loaderMainShow();
        var http_para_string = {formData : $(erpHoldUnholdCustomerListForm).serialize()};
        $http({
            url: BASE_URL + "master/hold-unhold-customers/send-mail",
            method: "POST",
            data: http_para_string,
        }).success(function(result, status, headers, config) {
            if (result.error == '1') {
                angular.element('input[name="customer_id[]"]').prop('checked',false);
                $scope.successMsgShow(result.message);
            } else {
                $scope.errorMsgShow(result.message);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        });
    };
    //**********/Send Mail Function and Confirm box*********************************

});