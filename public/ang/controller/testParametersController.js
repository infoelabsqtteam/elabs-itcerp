app.controller('testParametersController', function ($scope, $timeout, $sce, $http, BASE_URL, $ngConfirm) {

    //define empty variables
    $scope.currentModule = 9; //variable used in tree.js for tree popup 
    $scope.allList = '';
    $scope.sortType = 'test_parameter_code'; // set the default sort type
    $scope.sortReverse = false; // set the default sort order
    $scope.searchFish = ''; // set the default search/filter term
    $scope.testParameterCategory = '0';
    $scope.EquipmentTypeId = '0';
    $scope.testParameterInvoicing = false;
    $scope.testParameterNablScope = false;
    $scope.searchParameter = {};

    //**********If DIV is hidden it will be visible and vice versa*************
    $scope.IsVisiableSuccessMsg = true;
    $scope.IsVisiableErrorMsg = true;
    $scope.addTestParameterFormDiv = false;
    $scope.editTestParameterFormDiv = true;
    $scope.successMessage = '';
    $scope.errorMessage = '';
    $scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';
    //**********/If DIV is hidden it will be visible and vice versa************

    //**********scroll to top function*****************************************
    $scope.moveToMsg = function () {
        $('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
    };
    //**********/scroll to top function****************************************

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

    //**********Clearing Console********************************************
    $scope.clearConsole = function () {
        if (APPLICATION_MODE) console.clear();
    };
    //*********/Clearing Console********************************************

    //********** key Press Handler**********************************************
    $scope.funEnterPressHandler = function (e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            e.stopPropagation();
        }
    }
    //**********/key Press Handler**********************************************

    //**********Read/hide More description********************************************
    $scope.toggleDescription = function (type, id) {
        angular.element('#' + type + 'limitedText-' + id).toggle();
        angular.element('#' + type + 'fullText-' + id).toggle();
    };
    //*********/Read More description********************************************

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

    //*********hideAlertMsg*************
    $scope.hideAlertMsg = function () {
        $scope.IsVisiableSuccessMsg = true;
        $scope.IsVisiableErrorMsg = true;
        $scope.savedMessageHide = true;
        $scope.notSavedMessageHide = true;
    };
    //**********/hideAlertMsg********************************************

    //**********successMsgShow**************************************************
    $scope.multipleMsgShow = function (msgArr) {
        $scope.savedMessageHide = false;
        $scope.notSavedMessageHide = false;
        $scope.duplicateMessageHide = false;
        if (angular.isDefined(msgArr.saved)) { $scope.savedMessage = msgArr.saved; }
        if (angular.isDefined(msgArr.notSaved)) { $scope.notSavedMessage = msgArr.notSaved; }
        if (angular.isDefined(msgArr.duplicate)) { $scope.duplicateMessage = msgArr.duplicate; }
    }
    //********** /successMsgShow************************************************

    //**********hide Multiple Alert Msg*************
    $scope.hideMultipleAlertMsg = function () {
        $scope.savedMessageHide = true;
        $scope.notSavedMessageHide = true;
        $scope.duplicateMessageHide = true;
        $scope.savedMessage = "";
        $scope.notSavedMessage = "";
        $scope.duplicateMessage = "";
    }
    //********** /hide Multiple Alert Msg**********************************************

    //*********reset Form************************************************
    $scope.resetForm = function () {
        $scope.hideAlertMsg();
        $scope.addTestParameter = {};
        $scope.equipmentSelectedOption = [];
        $scope.erpAddTestParameterForm.$setUntouched();
        $scope.erpAddTestParameterForm.$setPristine();
        $scope.editTestParameter = {};
        $scope.erpEditTestParameterForm.$setUntouched();
        $scope.erpEditTestParameterForm.$setPristine();
        $scope.testParameterCategoryList = {};
        $scope.hideMultipleAlertMsg();
        $scope.hideAlertMsg();
    };
    //********/reset Form************************************************

    //*********close Form************************************************
    $scope.closeButton = function () {
        $scope.hideAlertMsg();
        $scope.addTestParameter = {};
        $scope.erpAddTestParameterForm.$setUntouched();
        $scope.erpAddTestParameterForm.$setPristine();
        $scope.editTestParameter = {};
        $scope.erpEditTestParameterForm.$setUntouched();
        $scope.erpEditTestParameterForm.$setPristine();
        $scope.testParameterCategoryList = {};
        $scope.navigateForm();
    };
    //********/close Form************************************************

    //*********navigate Form************************************************
    $scope.navigateForm = function () {
        if ($scope.editTestParameterFormDiv) {
            $scope.editTestParameterFormDiv = false;
            $scope.addTestParameterFormDiv = true;
        } else {
            $scope.editTestParameterFormDiv = true;
            $scope.addTestParameterFormDiv = false;
        }
    };
    //*********navigate Form************************************************
    //***************Status List */
    $scope.addTestParameter = {};
    $scope.statusList = [
        { id: 1, name: 'Active' },
        { id: 2, name: 'Inactive' }

    ];

    $scope.addTestParameter.status = { selectedOption: { id: $scope.statusList[0].id, name: $scope.statusList[0].name } };
    //*****************code used for sorting list order by fields***************
    $scope.predicate = 'test_parameter_id';
    $scope.reverse = true;
    $scope.sortBy = function (predicate) {
        $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
        $scope.predicate = predicate;
    };
    //*****************code used for sorting list order by fields***************

    //*****************parameter category tree list data*****************
    $scope.parameterCategoriesTree = [];
    $scope.getParameterCategories = function () {
        $http({
            method: 'POST',
            url: BASE_URL + 'get-parameter-category-tree-view'
        }).success(function (result) {
            if (result.parameterCategoriesTree) {
                $scope.parameterCategoriesTree = result.parameterCategoriesTree;
            }
            $scope.clearConsole();
        });
    }
    //*****************/parameter category tree list data*****************

    //*****************parameter category tree list data*****************
    $scope.testParameterInvoicingParentList = [];
    $scope.getTestParameterInvoicingParents = function () {
        $http({
            method: 'POST',
            url: BASE_URL + 'master/test-parameter/test-parameter-invoicing-parents'
        }).success(function (result) {
            $scope.testParameterInvoicingParentList = result.testParameterInvoicingParentList;
            $scope.clearConsole();
        });
    }
    //*****************/parameter category tree list data*****************

    //**********confirm box******************************************************
    $scope.funConfirmDeleteMessage = function (testParameterCategory, id) {
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
                        $scope.deleteRecord(testParameterCategory, id);
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

    //*****************display parent category dropdown code dropdown start****
    $scope.fungetParentCategory = function () {
        $http({
            method: 'POST',
            url: BASE_URL + 'master/get-parent-product-category'
        }).success(function (result) {
            $scope.parentCategoryList = result.parentCategoryList;
            $scope.clearConsole();
        });
    };
    //*****************display parent category code dropdown end*********

    //*****************display category dropdown code dropdown start*****	
    $scope.categoryCodeList = [];
    $http({
        method: 'POST',
        url: BASE_URL + 'test-parameter/get-categorycode-list'
    }).success(function (result) {
        $scope.categoryCodeList = result.allCatList;
        $scope.clearConsole();
    });
    //*****************display city code dropdown end******************

    //*****************display equipment dropdown code start***********
    $scope.equipmentTypesList = [];
    $scope.funGetEquipmentsList = function () {
        $http({
            method: 'POST',
            url: BASE_URL + 'equipment-types-list'
        }).success(function (result) {
            if (result.equipmentTypesList) {
                $scope.equipmentTypesList = result.equipmentTypesList;
                //$scope.searchParameter.search_equipment_type_id = {id:$scope.equipmentTypesList[0].id};
            }
            $scope.clearConsole();
        });
    }
    //*****************display equipment code dropdown end***********

    //*****************Reset Equipment Types*************************	
    $scope.funResetEquipmentTypes = function (divid) {
        angular.element("#" + divid + " option:selected").prop("selected", false);
    };
    //****************/Reset Equipment Types*************************

    //*****************generate unique code******************
    $scope.test_parameter_code = '';
    $scope.generateDefaultCode = function () {
        $scope.loaderShow();
        $http({
            method: 'GET',
            url: BASE_URL + 'test-parameter/generate-parameter-number'
        }).success(function (result) {
            $scope.default_test_parameter_code = result.uniqueCode;
            $scope.clearConsole();
            $scope.loaderHide();
        });
    };
    //*****************/generate unique code*****************

    //*****************display parent category dropdown code dropdown start****
    $scope.funFilterCategoryTree = function (category_id) {
        if (category_id) {
            angular.element(".categoryTreeFilter ul").find("li").hide();
            angular.element(".categoryTreeFilter ul").find("li[data-filtertext=" + category_id + "]").show();
        } else {
            angular.element(".categoryTreeFilter ul").find("li").show();
        }
    };
    //*****************display parent category code dropdown end*****************

    //*****************display parent category dropdown code dropdown start****
    $scope.funSetSelectedParameterCategory = function (node) {
        if (node.test_para_cat_id && node.test_para_cat_name) {
            $scope.testParameterCategoryList = [{ "id": node.test_para_cat_id, "name": node.test_para_cat_name }];
            if ($scope.addTestParameter) {
                $scope.addTestParameter.test_parameter_category_id = {
                    selectedOption: { id: node.test_para_cat_id }
                };
            }
            if ($scope.editTestParameter) {
                $scope.editTestParameter.test_parameter_category_id = {
                    selectedOption: { id: node.test_para_cat_id }
                };
            }
            $('#parameterCategoryTreeView').modal('hide');
        }
    };
    //*****************display parent category code dropdown end*****************

    //************/show tree pop up*******************************************
    $scope.showParameterCatTreeViewPopUp = function (currentModule) {
        $scope.currentModule = currentModule;
        $('#parameterCategoryTreeView').modal('show');
    }
    //**********/show tree pop up********************************************/

    //*******************filter product category from tree view****************
    $scope.filterSelectedParameterCategoryId = function (selectedNode) {
        $scope.searchParameter.search_test_para_cat_id = selectedNode.test_para_cat_id;
        $('#parameterCategoryTreeView').modal('hide');
        $scope.currentModule = 9;
        $timeout(function () {
            $scope.funGetTestParameters();
        }, 500);
    };
    //*****************/filter product category from tree view******************

    //**************************Listing Test Parameters***************************
    $scope.funGetTestParameterHttpRequest = function () {

        $scope.loaderMainShow();
        $scope.generateDefaultCode();
        var http_para_string = { formData: $(erpAddTestParameterDownLoadForm).serialize() };

        $http({
            url: BASE_URL + "test-parameters/get-parameters",
            method: "POST",
            data: http_para_string,
        }).success(function (result, status, headers, config) {
            $scope.allList = result.allList;
            $scope.clearConsole();
            $scope.loaderMainHide();
        }).error(function (result, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        });
    };
    $scope.funGetTestParameters = function () {
        $scope.funGetTestParameterHttpRequest();
    };
    var tempParameterSearchTerm;
    $scope.funFilterTestParameter = function (keyword) {
        tempParameterSearchTerm = keyword;
        $timeout(function () {
            if (keyword == tempParameterSearchTerm) $scope.funGetTestParameterHttpRequest();
        }, 500);
    };
    var tempParameterMultiSearchTerm;
    $scope.getMultiSearch = function (multiKeyword) {
        tempParameterMultiSearchTerm = multiKeyword;
        $timeout(function () {
            if (multiKeyword == tempParameterMultiSearchTerm) $scope.funGetTestParameterHttpRequest();
        }, 500);
    };
    $scope.refreshTreeViewSearch = function () {
        $scope.searchParameter.search_test_para_cat_id = '';
        $('#parameterCategoryTreeView').modal('hide');
        $scope.currentModule = 9;
        $timeout(function () {
            $scope.funGetTestParameterHttpRequest();
        }, 500);
    };
    $scope.multiSearchTr = true;
    $scope.multisearchBtn = false;
    $scope.closeMultisearch = function () {
        $scope.multiSearchTr = true;
        $scope.multisearchBtn = false;
        $scope.refreshMultiSearch();
    };
    $scope.refreshMultiSearch = function () {
        $scope.searchParameter = {};
        $scope.allListPaginate = true;
        $scope.allParaListPaginate = false;
        $timeout(function () {
            $scope.funGetTestParameterHttpRequest();
        }, 500);
    };
    $scope.openMultisearch = function () {
        $scope.multiSearchTr = false;
        $scope.multisearchBtn = true;
    };
    //**************************/Listing Parameters***************************

    //****************Adding of Test Parameter***********************************
    $scope.addRecord = function (testParameterCategory) {

        if (!$scope.erpAddTestParameterForm.$valid) return;
        $scope.loaderShow();

        //post all form data to save
        $http.post(BASE_URL + "test-parameters/add-parameters", {
            data: { formData: $(erpAddTestParameterForm).serialize() },
        }).success(function (res, status, headers, config) {
            var resData = res.returnData;
            if (resData.success) {
                $scope.resetForm();
                $scope.generateDefaultCode();
                $scope.getTestParameterInvoicingParents();
                $scope.funGetTestParameters();
                $scope.multipleMsgShow(resData.dataArray);
            } else {
                $scope.errorMsgShow(resData.error);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (res, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMessage);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        });
    };
    //****************/Adding of Test Parameter***********************************

    //****************Editing of Test Parameter***********************************
    $scope.tpipStatusSelection = false;
    $scope.editRecord = function (id) {
        if (id) {
            $scope.resetForm();
            $scope.equipmentList = [];
            $scope.equipmentSelectedOption = [];
            $scope.loaderMainShow();

            $http.post(BASE_URL + "test-parameters/edit-parameters", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                if (data.responseData) {
                    $scope.showEditForm();
                    $scope.editTestParameter = data.responseData.testParametersList;
                    var cost_price = $scope.editTestParameter.cost_price;
                    var selling_price = $scope.editTestParameter.selling_price;
                    $scope.editTestParameter.cost_price = cost_price;
                    $scope.editTestParameter.selling_price = selling_price;
                    $scope.equipmentList = data.responseData.equipmentList;
                    angular.forEach($scope.equipmentList, function (value, key) {
                        $scope.equipmentSelectedOption.push(value.equipment_id.toString());
                    });
                    $scope.testParameterCategoryList = [{ "id": $scope.editTestParameter.test_parameter_category_id, "name": $scope.editTestParameter.test_para_cat_name }];
                    $scope.editTestParameter.test_parameter_category_id = {
                        selectedOption: { id: $scope.editTestParameter.test_parameter_category_id }
                    };
                    $scope.editTestParameter.test_parameter_invoicing_parent_id = {
                        selectedOption: { id: $scope.editTestParameter.test_parameter_invoicing_parent_id }
                    };
                    $scope.testParameterInvoicing = $scope.editTestParameter.test_parameter_invoicing ? true : false;
                    $scope.testParameterNablScope = $scope.editTestParameter.test_parameter_nabl_scope ? true : false;
                    $scope.tpipStatusSelection = $scope.editTestParameter.tpip_id ? true : false;
                    $scope.editTestParameter.status = { selectedOption: { id: $scope.editTestParameter.status } };
                    $timeout(function () {
                        $scope.editTestParameter.tpn_editor_status = $scope.editTestParameter.tpn_editor_status;
                        $scope.editTestParameter.tpd_editor_status = $scope.editTestParameter.tpd_editor_status;
                    }, 500);

                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderMainHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMessage);
                }
                $scope.loaderMainHide();
                $scope.clearConsole();
            });
        }
    };
    //****************/Editing of Test Parameter***********************************

    //****************Updating of Test Parameter***********************************
    $scope.updateRecord = function (testParameterCategory) {

        if (!$scope.erpEditTestParameterForm.$valid) return;
        $scope.loaderShow();

        //post all form data to save
        $http.post(BASE_URL + "test-parameters/update-parameters", {
            data: { formData: $(erpEditTestParameterForm).serialize() },
        }).success(function (res, status, headers, config) {
            var resData = res.returnData;
            if (resData.success) {
                $scope.getTestParameterInvoicingParents();
                $scope.funGetTestParameters();
                $scope.multipleMsgShow(resData.dataArray);
            } else {
                $scope.errorMsgShow(resData.error);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMessage);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        });
    };
    //****************/Updating of Test Parameter***********************************

    //****************Deleting of Test Parameter***********************************
    $scope.deleteRecord = function (testParameterCategory, id) {
        if (id) {
            $scope.loaderShow();
            $http.post(BASE_URL + "test-parameters/delete-parameters", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                if (data.success) {
                    $scope.funGetTestParameters();
                    $scope.successMsgShow(data.success);
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMessage);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            });
        }
    };
    //****************/Deleting of Test Parameter***********************************

    $scope.setSelectedParameterCategory = function (catid) {
        $scope.test_parameter_category_id1 = {
            selectedOption: { id: catid }
        };
    };

    $scope.setSelectedDeptOption = function (deptid) {
        $scope.dept = {
            selectedOption: { id: deptid }
        };
    };

    $scope.addTestParameterFormDiv = false;
    $scope.uploadTestParameterFormDiv = true;
    $scope.editTestParameterFormDiv = true;
    $scope.showAddForm = function () {
        $scope.hideAlertMsg();
        $scope.addTestParameterFormDiv = false;
        $scope.uploadTestParameterFormDiv = true;
        $scope.editTestParameterFormDiv = true;
        $scope.addTestParameterFormDiv = false;
    };
    $scope.showEditForm = function () {
        $scope.addTestParameterFormDiv = true;
        $scope.uploadTestParameterFormDiv = true;
        $scope.editTestParameterFormDiv = false;
    };
    $scope.hideEditForm = function () {
        $scope.addTestParameterFormDiv = false;
        $scope.uploadTestParameterFormDiv = true;
        $scope.editTestParameterFormDiv = true;
    };
    $scope.hideAddForm = function () {
        $scope.addTestParameterFormDiv = true;
        $scope.uploadTestParameterFormDiv = true;
        $scope.editTestParameterFormDiv = false;
    };
    $scope.showUploadForm = function () {
        $scope.resetUploadForm();
        $scope.addTestParameterFormDiv = true;
        $scope.addTestParameterFormDiv = true;
        $scope.uploadTestParameterFormDiv = false;
        $scope.editTestParameterFormDiv = true;
    };

    //****************dropdown filter show/hide******************/
    $scope.searchFilterBtn = false;
    $scope.searchFilterInput = true;
    $scope.searchDeptFilterBtn = false;
    $scope.searchDeptFilterInput = true;
    //Show filter
    $scope.showDropdownSearchFilter = function () {
        $scope.searchFilterBtn = true;
        $scope.searchFilterInput = false;
    };
    //hide filter
    $scope.hideDropdownSearchFilter = function () {
        $scope.searchFilterBtn = false;
        $scope.searchFilterInput = true;
    };
    //Show filter
    $scope.showDeptDropdownSearchFilter = function () {
        $scope.searchDeptFilterBtn = true;
        $scope.searchDeptFilterInput = false;
    };
    //hide filter
    $scope.hideDeptDropdownSearchFilter = function () {
        $scope.searchDeptFilterBtn = false;
        $scope.searchDeptFilterInput = true;
    };
    //****************/dropdown filter show/hide******************

    //*********tinymce editor for test parameter name*************
    $scope.tinyMceOptions = {
        height: 20,
        statusbar: false,
        menubar: false,
        resize: false,
        toolbar: 'bold italic underline superscript subscript | formats | removeformat'
    };
    //********/tinymce editor for test parameter name*************

    //***************************upload csv**********************************************
    $(document).on('click', '#uploadTestParameterBtnId', function (e) {
        e.preventDefault();
        var formdata = new FormData();
        formdata.append('testParameterFile', $('#uploadTestParameterFile')[0].files[0]);

        $scope.loaderShow();
        $.ajax({
            url: BASE_URL + "master/test-parameter/upload-parameters-csv",
            type: "POST",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == '1') {
                    $scope.successMsgShow(res.message);
                    $scope.funGetTestParameters();
                    $scope.uploadType = '';
                    $scope.resetUploadForm();
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

    $scope.resetUploadForm = function () {
        angular.element('#uploadTestParameterFile').val('');
        angular.element('.browseFileInput').val('');
    };
    //***************************upload csv**********************************************

    //*********Enable Disable Tiny Mce Editor************************
    $scope.funEnableDisableTinyMceEditor = function (checkboxId) {
        var checkboxchecked = angular.element('#' + checkboxId).prop('checked');
        if (checkboxId == 'tpn_edit_editor_status') {
            if (checkboxchecked) {
                $scope.editTestParameter.tpn_editor_status = 1;
            } else {
                $scope.editTestParameter.tpn_editor_status = 0;
            }
        }
        if (checkboxId == 'tpd_edit_editor_status') {
            if (checkboxchecked) {
                $scope.editTestParameter.tpd_editor_status = 1;
            } else {
                $scope.editTestParameter.tpd_editor_status = 0;
            }
        }
    };
    //********/Enable Disable Tiny Mce Editor************************

    //*********Loading Data************************
    $timeout(function () {
        $scope.getParameterCategories();
    }, 100);
    $timeout(function () {
        $scope.funGetEquipmentsList();
        $scope.funGetTestParameters();
    }, 1000);
    //*********/Loading Data************************
});