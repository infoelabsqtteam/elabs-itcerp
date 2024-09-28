app.controller('testParameterCategoryController', function($scope, $http, BASE_URL, $ngConfirm) {
    $scope.GlobalFilterCategoryId = '';
    //define empty variables
    $scope.allList = '';
    $scope.currentModule = 8; //variable used in tree.js for tree popup 

    //sorting variables
    $scope.sortType = 'test_para_cat_code'; // set the default sort type
    $scope.sortReverse = false; // set the default sort order
    $scope.searchFish = ''; // set the default search/filter term

    $scope.successMessage = '';
    $scope.errorMessage = '';
    $scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';

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

    //**********Clearing Console********************************************
    $scope.clearConsole = function() {
        //console.clear();
    };
    //*********/Clearing Console********************************************

    //**********Read/hide More description********************************************
    $scope.toggleDescription = function(type, id) {
        angular.element('#' + type + 'limitedText-' + id).toggle();
        angular.element('#' + type + 'fullText-' + id).toggle();
    };
    //*********/Read More description********************************************

    //**********If DIV is hidden it will be visible and vice versa*************
    $scope.IsVisiableSuccessMsg = true;
    $scope.IsVisiableErrorMsg = true;
    $scope.addTestParaCategoryDiv = false;
    $scope.editTestParaCategoryDiv = true;
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
        $scope.parent_id = {};
        $scope.addTestParaCategory = {};
        $scope.erpAddTestParaCategoryForm.$setUntouched();
        $scope.erpAddTestParaCategoryForm.$setPristine();
        $scope.editTestParaCategory = {};
        $scope.erpEditTestParaCategoryForm.$setUntouched();
        $scope.erpEditTestParaCategoryForm.$setPristine();
        $scope.ProductCategoryId = '';
        $scope.DisplayProductSection = true;
    };

    //********/reset Form************************************************
    //***************Status List */
    $scope.addTestParaCategory = {};
    $scope.statusList = [
        { id: 1, name: 'Active' },
        { id: 2, name: 'Inactive' }
    ];
    $scope.addTestParaCategory.status = { selectedOption: { id: $scope.statusList[0].id, name: $scope.statusList[0].name } };

    $scope.navigateForm = function() {
        if ($scope.editTestParaCategoryDiv) {
            $scope.addTestParaCategoryDiv = true;
            $scope.editTestParaCategoryDiv = false;
            $scope.uploadTestParaCategoryDiv = true;
        } else {
            $scope.addTestParaCategoryDiv = false;
            $scope.editTestParaCategoryDiv = true;
            $scope.uploadTestParaCategoryDiv = true;
        }
    };

    //*********close button************************************************
    $scope.closeButton = function() {
        $scope.addTestParaCategory = {};
        $scope.erpAddTestParaCategoryForm.$setUntouched();
        $scope.erpAddTestParaCategoryForm.$setPristine();
        $scope.editTestParaCategory = {};
        $scope.erpEditTestParaCategoryForm.$setUntouched();
        $scope.erpEditTestParaCategoryForm.$setPristine();
        $scope.navigateForm();
    };
    //********/close button************************************************

    //************/show tree pop up*******************************************
    $scope.showParameterCatTreeViewPopUp = function(currentModule) {
            $scope.currentModule = currentModule;
            $('#parameterCategoryTreeView').modal('show');
        }
        //**********/show tree pop up********************************************/

    //*******************filter product category from tree view****************
    $scope.filterSelectedParameterCategoryId = function(selectedNode) {
            $scope.funGetParameterCategoryList(selectedNode.test_para_cat_id);
            $scope.refreshMultisearch();
            $('#parameterCategoryTreeView').modal('hide');
            $scope.currentModule = 8;
        }
        //*****************/filter product category from tree view******************

    //*****************parameter category tree list data*****************
    $scope.parameterCategoriesTree = [];
    $scope.getParameterCategories = function() {
            $http({
                method: 'POST',
                url: BASE_URL + 'get-parameter-category-tree-view'
            }).success(function(result) {
                if (result.parameterCategoriesTree) {
                    $scope.parameterCategoriesTree = result.parameterCategoriesTree;
                    $scope.funFilterCategoryTree($scope.GlobalFilterCategoryId);
                }
                $scope.clearConsole();
            });
        }
        //*****************/parameter category tree list data*****************

    //*****************display parent category dropdown code dropdown start****
    $scope.DisplayProductSection = true;
    $scope.funSetSelectedParameterCategory = function(node) {
        if (node.test_para_cat_id && node.test_para_cat_name) {
            $scope.ProductCategoryId = node.product_category_id;
            $scope.DisplayProductSection = false;
            $scope.testParameterCategoryOptions = [{ "id": node.test_para_cat_id, "name": node.test_para_cat_name }];
            $scope.parent_id = {
                selectedOption: { id: node.test_para_cat_id }
            };
            $('#parameterCategoryTreeView').modal('hide');
        } else {
            $scope.ProductCategoryId = '';
            $scope.DisplayProductSection = true;
        }
    };
    //*****************display parent category code dropdown end*****************

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
                        $scope.deleteRecord(id);
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
    $scope.predicate = 'test_para_cat_code';
    $scope.reverse = true;
    $scope.sortBy = function(predicate) {
        $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
        $scope.predicate = predicate;
    };

    //**********Showing Category Popup Tree**************************************
    $scope.refreshCategoryTree = function(divId) {
        $http({
            method: 'GET',
            url: BASE_URL + 'test-parameter-categories/get-category-tree-popup'
        }).success(function(result) {});
    };
    //*********/Showing Category Popup Tree**************************************

    /*****************display category dropdown code dropdown start*****************/
    $scope.categoryCodeList = [];
    $scope.funGetParameterCategory = function() {
        $http({
            method: 'POST',
            url: BASE_URL + 'test-parameter-categories/get-categorycode-list'
        }).success(function(result) {
            if (result.allCatList) {
                $scope.categoryCodeList = result.allCatList;
            }
            $scope.clearConsole();
        });
    };
    /*****************display city code dropdown end*****************/

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

    //*****************generate unique code******************
    $scope.default_test_para_cat_code = '';
    $scope.generateDefaultCode = function() {
        $scope.loaderShow();
        $http({
            method: 'GET',
            url: BASE_URL + 'test-parameter-categories/generate-parameter-number'
        }).success(function(result) {
            $scope.default_test_para_cat_code = result.uniqueCode;
            $scope.clearConsole();
            $scope.loaderHide();
        });
    };
    //*****************/generate unique code*****************

    //function is used to fetch the list of test parameters category 	
    $scope.funGetParameterCategoryList = function(test_para_id) {
        $scope.getParameterCategories();
        $scope.generateDefaultCode();
        if (angular.isDefined(test_para_id)) { test_para_id = test_para_id; } else { test_para_id = 0; }
        $scope.GlobalPrameterParentId = test_para_id;
        $http({
            method: 'GET',
            url: BASE_URL + 'test-parameter-categories/get-category/' + test_para_id
        }).success(function(data, status, headers, config) {
            $scope.testParameterCategories = data.testParameterCategories;
            $scope.funFilterCategoryTree($scope.GlobalFilterCategoryId);
            $scope.filterParametersCategories = '';
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMessage);
            }
            $scope.clearConsole();
        });
    };

    /***************** test parameters category  SECTION START HERE *****************/
    //function is used to call the 
    $scope.addTestParaCat = function() {
        if (!$scope.erpAddTestParaCategoryForm.$valid) return;
        $scope.loaderShow();
        // post all form data to save
        $http.post(BASE_URL + "test-parameter-categories/add-category", {
            data: { formData: $(erpAddTestParaCategoryForm).serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.resetForm();
                $scope.funGetParameterCategoryList($scope.GlobalPrameterParentId);
                $scope.testParameterCategoryOptions = {};
                $scope.successMsgShow(data.success);
            } else {
                $scope.errorMsgShow(data.error);
            }
            $scope.clearConsole();
            $scope.loaderHide();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMessage);
            }
            $scope.clearConsole();
            $scope.loaderHide();
        });
    };

    // edit an test parameters category and its data
    $scope.funEditTestParameter = function(id) {
        if (id) {
            $scope.editProductCategoryId = id;
            $scope.resetForm();
            $http.post(BASE_URL + "test-parameter-categories/edit-category", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.returnData.responseData) {
                    $scope.showEditForm();
                    $scope.editTestParaCategory = data.returnData.responseData;
                    $scope.editTestParaCategory.status = {
                        selectedOption: { id: data.returnData.responseData.status }
                    };
                    if (data.returnData.parentResponseData) {

                        $scope.testParameterCategoryOptions = [{ "id": data.returnData.parentResponseData.test_para_cat_id, "name": data.returnData.parentResponseData.test_para_cat_name }];
                        $scope.editTestParaCategory.parent_id = {
                            selectedOption: { id: data.returnData.parentResponseData.test_para_cat_id }
                        };

                    }
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
        } else {

        }
    };

    // update test parameters category  and its data
    $scope.funUpdateTestParameter = function() {

        if (!$scope.erpEditTestParaCategoryForm.$valid) return;
        $scope.loaderShow();

        // post all form data to save
        $http.post(BASE_URL + "test-parameter-categories/update-category", {
            data: { formData: $(erpEditTestParaCategoryForm).serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.resetForm();
                $scope.funGetParameterCategoryList($scope.GlobalPrameterParentId);
                $scope.navigateForm();
                $scope.testParameterCategoryOptions = {};
                $scope.successMsgShow(data.success);
            } else {
                $scope.errorMsgShow(data.error);
            }
            $scope.clearConsole();
            $scope.loaderHide();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMessage);
            }
            $scope.clearConsole();
            $scope.loaderHide();
        });
    };

    //Delete test parameters category  from the database
    $scope.deleteRecord = function(id) {
        if (id) {
            $scope.loaderShow();
            $http.post(BASE_URL + "test-parameter-categories/delete-category", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.success) {
                    $scope.funGetParameterCategoryList($scope.GlobalPrameterParentId);
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

    /**************multisearch start here**********************/
    $scope.multiSearchTr = true;
    $scope.multisearchBtn = false;

    $scope.getMultiSearch = function() {
        $scope.filterParametersCategories = '';
        $http.post(BASE_URL + "test-parameter-categories/multisearch", {
            data: { formData: $scope.searchParaCat },
        }).success(function(data, status, headers, config) {
            $scope.testParameterCategories = data.testParameterCategories;
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
        $scope.searchParaCat = {};
        $scope.funGetParameterCategoryList($scope.GlobalPrameterParentId);
    };

    $scope.openMultisearch = function() {
        $scope.multiSearchTr = false;
        $scope.multisearchBtn = true;
    };

    /**************multisearch end here**********************/

    $scope.setSelectedOption = function(catid) {
        $scope.parent_category1 = {
            selectedOption: { id: catid }
        };
    };

    $scope.addTestParaCategoryDiv = false;
    $scope.uploadTestParaCategoryDiv = true;
    $scope.editTestParaCategoryDiv = true;
    $scope.showAddForm = function() {
        $scope.hideAlertMsg();
        $scope.addTestParaCategoryDiv = false;
        $scope.uploadTestParaCategoryDiv = true;
        $scope.editTestParaCategoryDiv = true;
    };
    $scope.showUploadForm = function() {
        $scope.hideAlertMsg();
        $scope.addTestParaCategoryDiv = true;
        $scope.uploadTestParaCategoryDiv = false;
        $scope.editTestParaCategoryDiv = true;
    };
    $scope.showAddForm = function() {
        $scope.hideAlertMsg();
        $scope.addTestParaCategoryDiv = false;
        $scope.uploadTestParaCategoryDiv = true;
        $scope.editTestParaCategoryDiv = true;
    };
    $scope.showEditForm = function() {
        $scope.addTestParaCategoryDiv = true;
        $scope.uploadTestParaCategoryDiv = true;
        $scope.editTestParaCategoryDiv = false;
    };
    $scope.hideEditForm = function() {
        $scope.addTestParaCategoryDiv = false;
        $scope.uploadTestParaCategoryDiv = true;
        $scope.editTestParaCategoryDiv = true;
    };
    $scope.hideAddForm = function() {
        $scope.addTestParaCategoryDiv = true;
        $scope.uploadTestParaCategoryDiv = true;
        $scope.editTestParaCategoryDiv = false;
    };
    $scope.hideTreeForms = function() {
        $scope.addTestParaCategoryDiv = true;
        $scope.uploadTestParaCategoryDiv = true;
        $scope.editTestParaCategoryDiv = true;
    };

    //***************** add category from tree  *****************/
    $scope.addParameterCategoryNode = function(node) {
            $scope.resetForm();
            if (node.level == 1 || node.level == 0) {
                $scope.funSetSelectedParameterCategory(node);
                $scope.showAddForm();
            }
        }
        //*****************add category from tree *****************/

    //****************dropdown filter show/hide******************/
    $scope.searchFilterBtn = false;
    $scope.searchFilterInput = true;
    //Show filter
    $scope.showDropdownSearchFilter = function() {
        $scope.searchFilterBtn = true;
        $scope.searchFilterInput = false;
    };
    //hide filter
    $scope.hideDropdownSearchFilter = function() {
        $scope.searchFilterBtn = false;
        $scope.searchFilterInput = true;
    };
    //****************/dropdown filter show/hide******************/

    $scope.funSwithPage = function(url) {
        if (url == 'master/test-parameter-categories/tree-view') {
            window.location.href = BASE_URL + 'master/test-parameter-categories';
        } else {
            window.location.href = BASE_URL + 'master/test-parameter-categories/tree-view';
        }
    }

    //*****************display parent category dropdown code dropdown start****
    $scope.funFilterCategoryTree = function(category_id) {
        //console.log("filter"+category_id); 
        $scope.GlobalFilterCategoryId = category_id;
        if (category_id) {
            angular.element(".categoryTreeFilter ul").find("li").hide();
            angular.element(".categoryTreeFilter ul").find("li[data-filtertext=" + category_id + "]").show();
        } else {
            angular.element(".categoryTreeFilter ul").find("li").show();
        }
    };
    //*****************display parent category code dropdown end*****************

    //***************************upload csv**********************************************
    $(document).on('click', '#uploadTestParameterBtnId', function(e) {
        e.preventDefault();
        var formdata = new FormData();
        formdata.append('testParameterFile', $('#uploadTestParameterFile')[0].files[0]);

        $scope.loaderShow();
        $.ajax({
            url: BASE_URL + "master/test-parameter-categories/upload-parameters-categories-csv",
            type: "POST",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {
                if (res.error == '1') {
                    $scope.successMsgShow(res.message);
                    $scope.funGetParameterCategoryList();
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

    $scope.resetUploadForm = function() {
            angular.element('#uploadTestParameterFile').val('');
            angular.element('.browseFileInput').val('');
        }
        //***************************upload csv**********************************************	

    //***************** test parameters category  SECTION END HERE *****************//
});