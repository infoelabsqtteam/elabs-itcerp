app.controller('testStandardController', function($scope, $http, BASE_URL, $ngConfirm) {

    //define empty variables
    $scope.testStdData = '';
    $scope.comp_code = '';
    $scope.comp_address = '';
    $scope.comp_city = '';
    $scope.comp_id = '';
    $scope.GlobalProductCategoryId = 0;

    //sorting variables
    $scope.sortType = 'test_std_code'; // set the default sort type
    $scope.sortReverse = false; // set the default sort order
    $scope.searchFish = ''; // set the default search/filter term
    $scope.uploadEquipmentFormDiv = true;
    $scope.successMessage = '';
    $scope.errorMessage = '';
    $scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';

    //**********scroll to top function**********
    $scope.moveToMsg = function() {
            $('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
        }
        //**********/scroll to top function**********

    //**********loader show****************************************************
    $scope.loaderShow = function() {
            angular.element('#global_loader').fadeIn('slow');
        }
        //**********/loader show**************************************************

    //**********loader show***************************************************
    $scope.loaderHide = function() {
            angular.element('#global_loader').fadeOut('slow');
        }
        //**********/loader show**************************************************

    //**********Clearing Console********************************************
    $scope.clearConsole = function() {
            //console.clear();
        }
        //*********/Clearing Console********************************************

    //**********If DIV is hidden it will be visible and vice versa*************
    $scope.IsVisiableSuccessMsg = true;
    $scope.IsVisiableErrorMsg = true;
    $scope.hideStandardEditForm = true;
    $scope.hideStandardAddForm = false;
    //**********/If DIV is hidden it will be visible and vice versa************

    //**********successMsgShow**************************************************
    $scope.successMsgShow = function(message) {
        $scope.successMessage = message;
        $scope.IsVisiableSuccessMsg = false;
        $scope.IsVisiableErrorMsg = true;
        $scope.moveToMsg();
    };
    //********** /successMsgShow************************************************

    //**********errorMsgShow**************************************************
    $scope.errorMsgShow = function(message) {
        $scope.errorMessage = message;
        $scope.IsVisiableSuccessMsg = true;
        $scope.IsVisiableErrorMsg = false;
        $scope.moveToMsg();
    };
    //********** /errorMsgShow************************************************

    //*********hideAlertMsg*************
    $scope.hideAlertMsg = function() {
        $scope.IsVisiableSuccessMsg = true;
        $scope.IsVisiableErrorMsg = true;
    };
    //**********/hideAlertMsg********************************************

    //*********reset Form************************************************
    $scope.resetForm = function() {
        $scope.addTestStandard = {};
        $scope.testStandardForm.$setUntouched();
        $scope.testStandardForm.$setPristine();
        $scope.editTestStandard = {};
        $scope.editTestStdForm.$setUntouched();
        $scope.editTestStdForm.$setPristine();
    };
    //********/reset Form************************************************

    //*********reset Form************************************************
    $scope.closeButton = function() {
        $scope.addTestStandard = {};
        $scope.testStandardForm.$setUntouched();
        $scope.testStandardForm.$setPristine();
        $scope.editTestStandard = {};
        $scope.editTestStdForm.$setUntouched();
        $scope.editTestStdForm.$setPristine();
        $scope.navigateForm();
    };
    //********/reset Form************************************************

    //*********navigate Form************************************************
    $scope.navigateForm = function() {
        if ($scope.hideStandardEditForm) {
            $scope.hideStandardEditForm = false;
            $scope.hideStandardAddForm = true;
            $scope.uploadEquipmentFormDiv = true;
        } else {
            $scope.hideStandardEditForm = true;
            $scope.hideStandardAddForm = false;
            $scope.uploadEquipmentFormDiv = true;
        }
    };
    $scope.showUploadForm = function() {
        $scope.resetUploadForm();
        $scope.hideStandardEditForm = true;
        $scope.hideStandardAddForm = true;
        $scope.uploadEquipmentFormDiv = false;
    };
    $scope.hideUploadForm = function() {
        $scope.hideStandardEditForm = true;
        $scope.hideStandardAddForm = false;
        $scope.uploadEquipmentFormDiv = true;
    };
    //*********/navigate Form************************************************
    $scope.addTestStandard = {};
    $scope.statusList = [
        { id: 1, name: 'Active' },
        { id: 2, name: 'Inactive' }

    ];
    $scope.addTestStandard.status = { selectedOption: { id: $scope.statusList[0].id, name: $scope.statusList[0].name } };

    //**********confirm box******************************************************
    $scope.funConfirmDeleteMessage = function(id) {
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
                    action: function() {
                        $scope.deleteTestStd(id);
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

    //code used for sorting list order by fields 
    $scope.predicate = 'test_std_code';
    $scope.reverse = true;
    $scope.sortBy = function(predicate) {
        $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
        $scope.predicate = predicate;
    };

    /*****************display parent category dropdown code dropdown start*****************/
    $scope.parentCategoryList = [];
    $scope.fungetParentCategory = function() {
        $http({
            method: 'POST',
            url: BASE_URL + 'master/get-parent-product-category'
        }).success(function(result) {
            $scope.parentCategoryList = result.parentCategoryList;
            $scope.clearConsole();
        });
    };
    /*****************display parent category code dropdown end*****************/

    //*****************generate unique code******************
    $scope.generateDefaultCode = function() {
        $scope.loaderShow();
        $http({
            method: 'GET',
            url: BASE_URL + 'test-standards/generate-test-standard-number'
        }).success(function(result) {
            $scope.default_test_std_code = result.uniqueCode;
            $scope.clearConsole();
            $scope.loaderHide();
        });
    };
    //*****************/generate unique code*****************

    //******************fetch the list of test-standards*************	
    $scope.funGetTestStandard = function(product_category_id) {
        if (angular.isDefined(product_category_id)) { product_category_id = product_category_id; } else { product_category_id = 1; }
        $scope.GlobalProductCategoryId = product_category_id;
        $http.post(BASE_URL + "test-standards/get-test-standards/" + product_category_id, {}).success(function(data, status, headers, config) {
            $scope.generateDefaultCode();
            $scope.testStdData = data.testStdList;
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMessage);
            }
            $scope.clearConsole();
        });
    };
    //*****************/fetch the list of test-standards*************

    //**********Adding of Test Standard****************************** 
    $scope.funAddTestStandard = function() {

        if (!$scope.testStandardForm.$valid) return;
        $scope.loaderShow();
        // post all form data to save
        $http.post(BASE_URL + "test-standards/add-test-standards", {
            data: { formData: $(testStandardForm).serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.resetForm();
                $scope.funGetTestStandard($scope.GlobalProductCategoryId);
                $scope.successMsgShow(data.success);
            } else {
                $scope.errorMsgShow(data.error);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMessage);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        });
    };
    //**********/Adding of Test Standard****************************** 

    //*************Editing of Test Standard***************************
    $scope.editTestStd = function(id) {

        $scope.resetForm();
        $scope.hideAlertMsg();

        if (id) {
            $http.post(BASE_URL + "test-standards/edit-test-standards", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.responseData) {
                    $scope.hideStandardAddForm = true;
                    $scope.hideStandardEditForm = false;
                    $scope.uploadEquipmentFormDiv = true;
                    $scope.editTestStandard = data.responseData;
                    $scope.editTestStandard.product_category_id = {
                        selectedOption: { id: data.responseData.product_category_id }
                    };
                    $scope.editTestStandard.status = { selectedOption: { id: data.responseData.status } };

                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.clearConsole();
            }).error(function(data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMessage);
                }
                $scope.clearConsole();
            });
        }
    };
    //*************/Editing of Test Standard***************************

    //***********update company and its data**************************
    $scope.updateTestStd = function() {

        if (!$scope.editTestStdForm.$valid) return;
        $scope.loaderShow();

        // post all form data to save
        $http.post(BASE_URL + "test-standards/update-test-standards", {
            data: { formData: $("#edit_test_std_form").serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.resetForm();
                $scope.navigateForm();
                $scope.funGetTestStandard($scope.GlobalProductCategoryId);
                $scope.successMsgShow(data.success);
            } else {
                $scope.errorMsgShow(data.error);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMessage);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        });
    };
    //***********/update company and its data**************************

    //***************Delete company from the database**************
    $scope.deleteTestStd = function(id) {
        if (id) {
            $scope.loaderShow();
            $http.post(BASE_URL + "test-standards/delete-test-standards", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.success) {
                    // reload the all employee
                    $scope.funGetTestStandard($scope.GlobalProductCategoryId);
                    $scope.successMsgShow(data.success);
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function(data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMessage);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            });
        }
    };
    //***************/Delete company from the database**************

    /**************multisearch start here**********************/
    $scope.multiSearchTr = true;
    $scope.multisearchBtn = false;
    $scope.getMultiSearch = function() {
        $scope.filterTestStandards = '';
        $scope.searchStandard.search_product_category_id = $scope.GlobalProductCategoryId;
        $http.post(BASE_URL + "test-standards/get-test-standards-multistart", {
            data: { formData: $scope.searchStandard },
        }).success(function(data, status, headers, config) {
            $scope.testStdData = data.testStdList;
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '400') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    };

    $scope.closeMultisearch = function() {
        $scope.multiSearchTr = true;
        $scope.multisearchBtn = false;
        $scope.refreshMultisearch();
    };

    $scope.refreshMultisearch = function() {
        $scope.searchStandard = {};
        $scope.funGetTestStandard($scope.GlobalProductCategoryId);
    };

    $scope.openMultisearch = function() {
        $scope.multiSearchTr = false;
        $scope.multisearchBtn = true;
    };

    /**************multisearch end here**********************/
    //*****************display parent category dropdown code dropdown start****
    $scope.fungetParentCategory = function() {
        $http({
            method: 'POST',
            url: BASE_URL + 'master/get-parent-product-category'
        }).success(function(result) {
            $scope.parentCategoryList = result.parentCategoryList;
            $scope.clearConsole();
        });
    };
    //*****************display parent category code dropdown end*****************

    //***************************upload csv**********************************************
    $(document).on('click', '#uploadEquipmentTypesBtnId', function(e) {
        e.preventDefault();
        var formdata = new FormData();
        formdata.append('testStdFile', $('#testStandardFile')[0].files[0]);

        $scope.loaderShow();
        $.ajax({
            url: BASE_URL + "master/test-standards/upload-test-standards-csv",
            type: "POST",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {
                if (res.error == '1') {
                    $scope.successMsgShow(res.message);
                    $scope.funGetTestStandard($scope.GlobalProductCategoryId)
                    $scope.clearConsole();
                    $scope.loaderHide();
                } else {
                    $scope.errorMsgShow(res.message);
                    $scope.clearConsole();
                    $scope.loaderHide();
                }
                $scope.$apply();
            }
        });
    });

    $scope.resetUploadForm = function() {
            angular.element('#testStandardFile').val('');
            angular.element('.browseFileInput').val('');
        }
        //***************************upload csv**********************************************

    /***************** company SECTION END HERE *****************/
});