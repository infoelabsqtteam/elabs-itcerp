app.controller('standardWiseProductTestController', function ($scope, $q, $timeout, $http, BASE_URL, $filter, $ngConfirm) {

    //define empty variables
    $scope.dragDropRowsCount = 1000; /** used in $scope.dragoverCallback() function**/
    $scope.currentModule = 3;
    $scope.allList = '';
    $scope.addParaFormDiv = true;
    $scope.editProductTestDiv = true;
    $scope.paraTable = true;
    $scope.editParaFormDiv = true;
    $scope.currentProductDetail = true;
    $scope.testProductAltMethodForm = true;
    $scope.typeAlpha = true;
    $scope.editLable = true;
    $scope.globalProductCategoryId = '0';
    $scope.globalParameterCategoryId = '0';
    $scope.successMessage = '';
    $scope.errorMessage = '';
    $scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';
    $scope.CheckboxMsg = 'Please check atleast one checkbox!';
    $scope.IsVisiableSuccessMsg = true;
    $scope.IsVisiableErrorMsg = true;
    $scope.globalProductCategoryId = '0';

    //Pagination constant
    $scope.allListPaginate = false;
    $scope.allParaListPaginate = false;
    $scope.allAltMethodListPaginate = false;
    $scope.productTestParameter = {};
    $scope.editProductTestParameter = {};

    $scope.addAltMethod = {};
    $scope.editAltMethod = {};
    $scope.searchProductTest = '';
    $scope.showDescriptionTextarea = false;
    $scope.nullValue = "";
    $scope.IsVisiablePopUpSuccessMsg = true;
    $scope.IsVisiablePopUpErrorMsg = true;
    $scope.stdValFromToHide = true;
    $scope.addDefaultParameterNablScope = false;
    $scope.editDefaultParameterNablScope = false;

    //**********scroll to top function**********
    $scope.moveToMsg = function () {
        $('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
    }
    //**********/scroll to top function**********

    //**********loader show****************************************************
    $scope.loaderShow = function () {
        angular.element('#global_loader').fadeIn('slow');
    }
    //**********/loader show**************************************************

    //**********loader show***************************************************
    $scope.loaderHide = function () {
        angular.element('#global_loader').fadeOut('slow');
    }
    //**********/loader show**************************************************

    //**********Clearing Console********************************************
    $scope.clearConsole = function () {
        //console.clear();
    }
    //*********/Clearing Console********************************************

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

    //**********successMsgShow**************************************************
    $scope.successMsgShow = function (message) {
        $scope.successMessage = message;
        $scope.IsVisiableSuccessMsg = false;
        $scope.IsVisiableErrorMsg = true;
        $scope.moveToMsg();
    };
    $scope.successPopUpMsgShow = function (message) {
        $scope.successMessage = message;
        $scope.IsVisiablePopUpSuccessMsg = false;
        $scope.IsVisiablePopUpErrorMsg = true;
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
    $scope.errorPopUpMsgShow = function (message) {
        $scope.errorMessage = message;
        $scope.IsVisiablePopUpSuccessMsg = true;
        $scope.IsVisiablePopUpErrorMsg = false;
        $scope.moveToMsg();
    };
    //********** /errorMsgShow************************************************

    //*********hideAlertMsg*************
    $scope.hideAlertMsg = function () {
        $scope.IsVisiableSuccessMsg = true;
        $scope.IsVisiableErrorMsg = true;
        $scope.IsVisiablePopUpSuccessMsg = true;
        $scope.IsVisiablePopUpErrorMsg = true;
    };
    //**********/hideAlertMsg********************************************

    /*****************claim types dropdown start*****************/
    $scope.productTestParameter.claimDependentList = {
        claimOptions: [
            { id: '1', name: 'Yes' },
            { id: '0', name: 'No' },
        ],
        selectedClaim: { id: '1' }
    };
    //***************Status List */
    $scope.statusList = [
        { id: 1, name: 'Active' },
        { id: 2, name: 'Inactive' }

    ];
    $scope.status = { selectedOption: { id: $scope.statusList[0].id, name: $scope.statusList[0].name } };
    $scope.productTestParameter.status = { selectedOption: { id: $scope.statusList[0].id, name: $scope.statusList[0].name } };
    /*****************display claim types dropdown end*****************/
    $scope.altMethodTable = true;
    $scope.editAlternateFormDiv = true;
    $scope.editAltMethodLable = true;
    $scope.inputTypeInt = function () {
        var standard_value_from = angular.element(document.querySelector('#standard_value_from'));
        var standard_value_from1 = angular.element(document.querySelector('#standard_value_from1'));
        var alt_standard_value_from = angular.element(document.querySelector('#alt_standard_value_from'));
        var alt_standard_value_from1 = angular.element(document.querySelector('#alt_standard_value_from1'));
        standard_value_from.attr('type', "number");
        standard_value_from1.attr('type', "number");
        alt_standard_value_from.attr('type', "number");
        alt_standard_value_from1.attr('type', "number");

        var standard_value_to = angular.element(document.querySelector('#standard_value_to'));
        var standard_value_to1 = angular.element(document.querySelector('#standard_value_to1'));
        var alt_standard_value_to = angular.element(document.querySelector('#alt_standard_value_to'));
        var alt_standard_value_to1 = angular.element(document.querySelector('#alt_standard_value_to1'));
        standard_value_to.attr('type', "number");
        standard_value_to1.attr('type', "number");
        alt_standard_value_to.attr('type', "number");
        alt_standard_value_to1.attr('type', "number");
    };
    $scope.inputTypeText = function () {
        var standard_value_from = angular.element(document.querySelector('#standard_value_from'));
        var standard_value_from1 = angular.element(document.querySelector('#standard_value_from1'));
        var alt_standard_value_from = angular.element(document.querySelector('#alt_standard_value_from'));
        var alt_standard_value_from1 = angular.element(document.querySelector('#alt_standard_value_from1'));
        standard_value_from.attr('type', "text");
        standard_value_from1.attr('type', "text");
        alt_standard_value_from.attr('type', "text");
        alt_standard_value_from1.attr('type', "text");

        var standard_value_to = angular.element(document.querySelector('#standard_value_to'));
        var standard_value_to1 = angular.element(document.querySelector('#standard_value_to1'));
        var alt_standard_value_to = angular.element(document.querySelector('#alt_standard_value_to'));
        var alt_standard_value_to1 = angular.element(document.querySelector('#alt_standard_value_to1'));
        standard_value_to.attr('type', "text");
        standard_value_to1.attr('type', "text");
        alt_standard_value_to.attr('type', "text");
        alt_standard_value_to1.attr('type', "text");
    };

    //sorting variables
    $scope.sortType = 'test_code'; // set the default sort type
    $scope.sortReverse = false; // set the default sort order
    $scope.searchFish = ''; // set the default search/filter term

    //*****************product category tree list data*****************
    $scope.productCategoriesTree = [];
    $scope.getProductCategories = function () {
        $http({
            method: 'POST',
            url: BASE_URL + 'get-product-category-tree-view'
        }).success(function (result) {
            if (result.productCategoriesTree) {
                $scope.productCategoriesTree = result.productCategoriesTree;
            }
            $scope.clearConsole();
        });
    };
    //*****************/product category tree list data*****************

    //************/show tree pop up*******************************************
    $scope.showProductCatTreeViewPopUp = function (currentModule) {
        $scope.currentModule = currentModule;
        $('#productCategoryPopup').modal('show');
    };
    //**********/show tree pop up********************************************/

    //*******************filter product category from tree view****************
    $scope.filterSelectedProductCategoryId = function (selectedNode) {
        $scope.getStandatdProductTest(selectedNode.p_category_id);
        $scope.product_category = {
            selectedOption: { id: selectedNode.p_category_id }
        };
        $('#productCategoryPopup').modal('hide');
        $scope.currentModule = 3;
    };
    //*****************/filter product category from tree view******************

    //*****************get product category id from tree view*****************/
    $scope.getProductsAndStandards = function (node) {
        $scope.getCatProducts(node.p_category_id);
        $scope.getProductStandards(node.parent_id);
        $('#productCategoryPopup').modal('hide');
    };
    //*****************/get product category id from tree view*****************/

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
    };
    //*****************/parameter category tree list data*****************

    //************/show tree pop up*******************************************
    $scope.showParameterCatTreeViewPopUp = function (currentModule) {
        $scope.currentModule = currentModule;
        $scope.funFilterCategoryTree($scope.globalProductCategoryId);
        $('#parameterCategoryPopup').modal('show');
    };
    //**********/show tree pop up********************************************/

    //*******************filter product category from tree view****************
    $scope.filterSelectedParameterCategoryId = function (selectedNode) {
        $scope.funGetParameterCategoryList(selectedNode.test_para_cat_id);
        $scope.refreshMultisearch();
        $('#parameterCategoryPopup').modal('hide');
    };
    //*****************/filter product category from tree view******************

    //*****************display product category filter List dropdown code start*********/
    $scope.productCatList = [];
    $http({
        method: 'GET',
        url: BASE_URL + 'product-category/get-category-list/' + 2
    }).success(function (result) {
        if (result.productCategories) {
            $scope.productCategories = result.productCategories;
        }
        $scope.clearConsole();
    });
    //*****************/display product category filter List dropdown code start*****************/

    //*****************display product List dropdown code start*****************/	
    $scope.getCatProducts = function (p_parent_id) {
        $scope.productList = [];
        $http({
            method: 'GET',
            url: BASE_URL + 'products/get-products-dropdown/' + p_parent_id
        }).success(function (result) {
            $scope.productList = result.productsList;
            $scope.clearConsole();
        });
    }
    //*****************/display product List code dropdown end*****************/	

    /*****************display test Standards List dropdown code dropdown start*****************/
    $scope.getProductStandards = function (product_cat_parent_id) {
        $scope.testStandardsList = [];
        $http({
            method: 'GET',
            url: BASE_URL + 'standard-wise-product/get-teststandars-list/' + product_cat_parent_id
        }).success(function (result) {
            $scope.globalProductCategoryId = result.globalProductCategoryId;
            $scope.testStandardsList = result.testStandardsList;
            $scope.clearConsole();
        });
    }
    //*****************/display parent category dropdown code dropdown start****

    //******************filter parameter category according to product category*************/
    $scope.funFilterCategoryTree = function (product_category_parent_id) {
        if (product_category_parent_id) {
            angular.element(".categoryTreeFilter ul").find("li").hide();
            angular.element(".categoryTreeFilter ul").find("li[data-filtertext=" + product_category_parent_id + "]").show();
        } else {
            angular.element(".categoryTreeFilter ul").find("li").show();
        }
    };
    //******************/filter parameter category according to product category*****************/

    //******************get parameters by parameter_category_id selected from parameter popup*****************
    $scope.funSetSelectedProductCategory = function (node) {
        $scope.showDescriptionTextarea = false;
        $scope.editProductTestParameter.test_parameter_name = '';
        $scope.productTestParameter.test_parameter_name = '';
        $scope.parameterNameList = [];
        $scope.parameterEquipmentList = [];
        if (node.product_category_id != '2') {
            $scope.methodList = [];
        }
        $scope.globalParameterCategoryId = node.test_para_cat_id;
        $('#parameterCategoryPopup').modal('hide');
    };
    //*****************/get parameters by parameter_category_id selected from parameter popup******************	

    //****************method dropdown filter show/hide******************//
    $scope.searchMethodFilterBtn = false;
    $scope.searchMethodFilterInput = true;
    $scope.showMethodDropdownSearchFilter = function () {
        $scope.searchMethodFilterBtn = true;
        $scope.searchMethodFilterInput = false;
    };
    $scope.hideMethodDropdownSearchFilter = function () {
        $scope.searchMethodFilterBtn = false;
        $scope.searchMethodFilterInput = true;
    };
    //****************/method dropdown filter show/hide******************

    //****************method dropdown filter show/hide******************
    $scope.searchEquipmentFilterBtn = false;
    $scope.searchEquipmentFilterInput = true;
    $scope.showEquipmentDropdownSearchFilter = function () {
        $scope.searchEquipmentFilterBtn = true;
        $scope.searchEquipmentFilterInput = false;
    };
    $scope.hideEquipmentDropdownSearchFilter = function () {
        $scope.searchEquipmentFilterBtn = false;
        $scope.searchEquipmentFilterInput = true;
    };
    //****************/method dropdown filter show/hide******************

    //****************test Standard dropdown filter show/hide******************
    $scope.searchStandardFilterBtn = false;
    $scope.searchStandardFilterInput = true;
    $scope.showStandardDropdownSearchFilter = function () {
        $scope.searchStandardFilterBtn = true;
        $scope.searchStandardFilterInput = false;
    };
    $scope.hideStandardDropdownSearchFilter = function () {
        $scope.searchStandardFilterBtn = false;
        $scope.searchStandardFilterInput = true;
    };
    //****************/test Standard dropdown filter show/hide******************

    //******************Add test parameters category and its data***************
    $scope.addParameters = function (id, product_parent_id) {

        $scope.productTestParameter.time_taken_mins = '00:00';
        $scope.stdValFromToHide = true;
        $scope.loadParameterTreeView = true;
        $scope.parameterNameList = [];
        $scope.methodList = [];
        $scope.parameterEquipmentList = [];

        if (id) {
            $scope.loaderShow();
            $scope.currentTestId = id;
            $scope.globalProductCategoryId = product_parent_id;

            $http.post(BASE_URL + "standard-wise-product/getproduct", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                if (data.returnData.responseData) {
                    if ($scope.globalProductCategoryId == 2) {
                        $scope.getMethodsList($scope.globalProductCategoryId, 0);
                    }
                    var responseD = data.returnData.responseData;
                    $scope.getProductTestParameters(id);
                    $scope.current_test_id = btoa(responseD.test_id);
                    $scope.test_code_text = responseD.test_code;
                    $scope.product_text = responseD.product_name;
                    $scope.product_category_text = responseD.p_category_name;
                    $scope.test_standard_text = responseD.test_std_name;
                    $scope.wef_text = responseD.wef;
                    $scope.upto_text = responseD.upto;
                    $scope.addParaFormDiv = false;
                    $scope.addProFormDiv = true;
                    $scope.uploadProFormDiv = true;
                    $scope.viewProductTestParametersDiv = true;
                    $scope.proTable = true;
                    $scope.paraTable = false;
                    $scope.currentProductDetail = false;
                    $scope.productTestParameter.claimDependentList = {
                        claimOptions: [
                            { id: '1', name: 'Yes' },
                            { id: '0', name: 'No' },
                        ],
                        selectedClaim: { id: '1' }
                    };
                    $scope.productTestParameter.data_types = {
                        availableTypeOptions: [
                            { id: 'numeric', name: 'Numeric' },
                            { id: 'alphanumeric', name: 'Alphanumeric' },
                            { id: 'na', name: 'NA' },
                        ],
                        selectedOption: { id: 'numeric', name: 'Numeric' }
                    };
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            });
        }
    };
    //******************/Add test parameters category and its data**********************

    /*****************test product header SECTION START HERE *****************/
    $scope.addRecord = function () {

        $scope.loaderShow();
        if (!$scope.addtestProductForm.$valid) return;

        // post all form data to save
        $http.post(BASE_URL + "standard-wise-product/add-product", {
            data: { formData: $(addtestProductForm).serialize() },
        }).success(function (data, status, headers, config) {
            if (data.success) {
                $scope.hideStandardDropdownSearchFilter();
                $scope.getStandatdProductTest($scope.ProductCategoryId);
                $scope.addParameters(data.test_id, $scope.globalProductCategoryId);
                $scope.addParaFormDiv = false;
                $scope.successMsgShow(data.success);
                //reset form
                $scope.resetForm();
            } else {
                $scope.errorMsgShow(data.error);
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

    //*********reset Form************************************************
    $scope.resetForm = function () {
        $scope.test_code = null;
        $scope.product_id = null;
        $scope.p_category_id = null;
        $scope.test_standard_id = null;
        $scope.wef = null;
        $scope.upto = null;
        $scope.addtestProductForm.$setUntouched();
        $scope.addtestProductForm.$setPristine();
    };
    //*********/reset Form************************************************

    //**************function is used to fetch the list of test parameters category**************
    $scope.getProductTestParameters = function (test_id) {

        $scope.loaderShow();
        $scope.allListPaginate = false;
        $scope.allParaListPaginate = true;
        $scope.allAltMethodListPaginate = false;
        $scope.allAltMethodList = '';

        //Loading Parameter Tree View
        if ($scope.loadParameterTreeView) $scope.getParameterCategories();

        $http.post(BASE_URL + "standard-wise-product/get-parameters-details", {
            data: { "_token": "{{ csrf_token() }}", "id": test_id }
        }).success(function (data, status, headers, config) {
            if (data.allParaList) {
                $scope.allParaList = data.allParaList;
                $scope.allParaList_test_id = data.allParaList_test_id;
            } else {
                $scope.allParaList = '';
                $scope.allParaList_test_id = '';
            }
            $scope.loadParameterTreeView = false;
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
    //**************/function is used to fetch the list of test parameters category**************

    /**************multisearch for test parameters start here**********************/
    $scope.multiSearchTrPara = true;
    $scope.multisearchBtnPara = false;
    $scope.getMultiSearchPara = function (test_id) {

        $scope.allListPaginate = false;
        $scope.allParaListPaginate = true;
        $scope.allAltMethodListPaginate = false;
        $scope.loaderShow();
        $scope.allList = '';
        $scope.allAltMethodList = '';
        $scope.searchParametersPara = '';
        $scope.searchStdTestPara.search_test_id = test_id;

        $http.post(BASE_URL + "standard-wise-product/get-parameters-details-multisearch", {
            data: { formData: $scope.searchStdTestPara },
        }).success(function (data, status, headers, config) {
            if (data.allParaList) {
                $scope.allParaList = data.allParaList;
                $scope.allParaList_test_id = data.allParaList_test_id;
            } else {
                $scope.allParaList = '';
                $scope.allParaList_test_id = '';
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    };

    $scope.closeMultisearchPara = function () {
        $scope.multiSearchTrPara = true;
        $scope.multisearchBtnPara = false;
        $scope.refreshMultisearchPara();
    };

    $scope.refreshMultisearchPara = function () {
        $scope.searchStdTestPara = {};
        $scope.searchProducthdrPara = '';
        $scope.searchParameters = '';
        $scope.getProductTestParameters($scope.allParaList_test_id);
    };

    $scope.openMultisearchPara = function () {
        $scope.multiSearchTrPara = false;
        $scope.multisearchBtnPara = true;
    }
    /**************multisearch end here**********************/

    //code used for sorting list order by fields 
    $scope.predicate = 'test_code';
    $scope.reverse = true;
    $scope.sortBy = function (predicate) {
        $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
        $scope.predicate = predicate;
    };

    //**************function is used to fetch the list of stndrd wise test product ***************
    var tempArticleSearchTerm;
    $scope.funFilterStandatdProductTest = function (p_category_id, keyword) {
        tempArticleSearchTerm = keyword;
        $timeout(function () {
            if (keyword == tempArticleSearchTerm) {
                $scope.loaderShow();
                $scope.ProductCategoryId = p_category_id;
                $scope.searchStdTest = {};
                $scope.allListPaginate = true;
                $scope.allParaListPaginate = false;
                $scope.allAltMethodListPaginate = false;
                $scope.allParaList = '';
                $scope.allAltMethodList = '';
                $scope.keyword = keyword;

                $http({
                    method: 'POST',
                    url: BASE_URL + "standard-wise-product/get-products-tests/" + p_category_id,
                    data: { "product_category_id": p_category_id, "keyword": $scope.keyword },
                }).success(function (data, status, headers, config) {
                    $scope.allList = data.allList;
                    $scope.loaderHide();
                    $scope.clearConsole();
                }).error(function (data, status, headers, config) {
                    if (status == '500' || status == '404') {
                        $scope.errorMsgShow($scope.defaultMsg);
                    }
                    $scope.clearConsole();
                });
            }
        }, 1000);

    };

    //function is used to fetch the list of stndrd wise test product  	
    $scope.getStandatdProductTest = function (p_category_id) {
        $scope.loaderShow();
        $scope.ProductCategoryId = p_category_id;
        $scope.searchStdTest = {};
        $scope.allListPaginate = true;
        $scope.allParaListPaginate = false;
        $scope.allAltMethodListPaginate = false;
        $scope.allParaList = '';
        $scope.allAltMethodList = '';
        $scope.keyword = $scope.keyword;
        $http({
            method: 'POST',
            url: BASE_URL + "standard-wise-product/get-products-tests/" + p_category_id,
            data: { "product_category_id": p_category_id, "keyword": $scope.keyword },
        }).success(function (data, status, headers, config) {
            $scope.allList = data.allList;
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    };

    //function is used to fetch the list of stndrd wise test product  	
    $scope.funRefreshStandatdProductTest = function (p_category_id, keyword = null) {

        $scope.loaderShow();
        $scope.ProductCategoryId = '0';
        $scope.searchStdTest = {};
        $scope.allListPaginate = true;
        $scope.allParaListPaginate = false;
        $scope.allAltMethodListPaginate = false;
        $scope.allParaList = '';
        $scope.allAltMethodList = '';
        $scope.keyword = '';
        $scope.product_category = {};
        $scope.searchProductTest = '';

        $http({
            method: 'POST',
            url: BASE_URL + "standard-wise-product/get-products-tests/" + p_category_id,
            data: { keyword: keyword },
        }).success(function (data, status, headers, config) {
            $scope.allList = data.allList;
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    };
    //*************/function is used to fetch the list of stndrd wise test product ***************	

    /**************multisearch start here**********************/
    $scope.multiSearchTr = true;
    $scope.multisearchBtn = false;
    $scope.getMultiSearch = function () {

        $scope.loaderShow();
        $scope.allListPaginate = true;
        $scope.allParaListPaginate = false;
        $scope.allAltMethodListPaginate = false;
        $scope.allParaList = '';
        $scope.allAltMethodList = '';
        $scope.searchProducthdr = '';
        $scope.searchStdTest.search_p_category_id = $scope.ProductCategoryId;

        $http.post(BASE_URL + "standard-wise-product/get-std-test-multisearch", {
            data: { formData: $scope.searchStdTest },
        }).success(function (data, status, headers, config) {
            $scope.allList = data.allList
            $scope.loaderHide();
            $scope.clearConsole();
        });
    };

    $scope.closeMultisearch = function () {
        $scope.multiSearchTr = true;
        $scope.multisearchBtn = false;
        $scope.refreshMultisearch();
    };

    $scope.refreshMultisearch = function () {
        $scope.searchStdTest = {};
        $scope.searchProducthdr = '';
        $scope.getStandatdProductTest($scope.ProductCategoryId);
    };

    $scope.openMultisearch = function () {
        $scope.multiSearchTr = false;
        $scope.multisearchBtn = true;
    };
    /**************multisearch end here**********************/

    //**********confirm box******************************************************
    $scope.funConfirmDeleteProductTestMessage = function (id) {
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
                        $scope.deleteProductTestRecord(id);
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

    //Delete stndrd wise test product  from the database
    $scope.deleteProductTestRecord = function (id) {
        if (id) {
            $scope.loaderShow();

            $http.post(BASE_URL + "standard-wise-product/delete-product", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                if (data.success) {
                    $scope.getStandatdProductTest($scope.ProductCategoryId);
                    $scope.successMsgShow(data.success);
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.clearConsole();
                $scope.loaderHide();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
            });
        }
    };

    $scope.setProductSelectedOption = function (proid) {
        $scope.product_id = {
            selectedOption: { id: proid }
        };
    };
    $scope.setStandardSelectedOption = function (stdid) {
        $scope.test_standard_id = {
            selectedOption: { id: stdid }
        };
    };

    //********************Edit an stndrd wise test product and its data********************
    $scope.editProductTestRecord = function (id, p_category_id, product_cat_parent_id) {
        if (id) {
            $scope.loaderShow();
            $scope.addParameterBtn = true;
            $scope.getCatProducts(p_category_id);
            $scope.getProductStandards(product_cat_parent_id);
            $scope.closeAllDropdownFilters();
            $http.post(BASE_URL + "standard-wise-product/edit-product", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                if (data.returnData.responseData) {
                    var responseD = data.returnData.responseData;
                    $scope.editProductTest = responseD;
                    $scope.test_id = btoa(responseD.test_id);
                    $scope.setStandardSelectedOption(responseD.test_standard_id);
                    $scope.setProductSelectedOption(responseD.product_id);
                    $scope.status = { selectedOption: { id: responseD.status } }
                    $scope.showProTestEditForm();
                    $scope.loaderHide();
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
            });
        } else {

        }
    };
    //********************Edit an stndrd wise test product and its data********************

    //*********************Update stndrd wise test product and its data********************
    $scope.updateProductTestRecord = function () {
        if (!$scope.editProductTestForm.$valid)
            return;
        $scope.loaderShow();
        //post all form data to save
        $http.post(BASE_URL + "standard-wise-product/update-product", {
            data: { formData: $(editProductTestForm).serialize() },
        }).success(function (data, status, headers, config) {
            if (data.success) {
                $scope.getStandatdProductTest($scope.ProductCategoryId);
                $scope.successMsgShow(data.success);
            } else {
                $scope.errorMsgShow(data.error);
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
    //*********************Update stndrd wise test product and its data********************

    //*************show form for stndrd wise test product edit and its data****************
    $scope.showProTestEditForm = function () {
        $scope.addProFormDiv = true;
        $scope.uploadProFormDiv = true;
        $scope.viewProductTestParametersDiv = true;
        $scope.addParaFormDiv = true;
        $scope.paraTable = true;
        $scope.editProductTestDiv = false;
    };
    //*************/show form for stndrd wise test product edit and its data****************

    //***************show form for upload scv header and test details***********************
    $scope.showUploadCsvForm = function () {
        $scope.addProFormDiv = true;
        $scope.uploadProFormDiv = false;
        $scope.viewProductTestParametersDiv = true;
        $scope.addParaFormDiv = true;
        $scope.paraTable = true;
        $scope.editProductTestDiv = true;
        $scope.uploadType = '';
    };
    //***************/show form for upload scv header and test details***********************

    //show form for add new  stndrd wise test product category  
    $scope.showProTestAddForm = function () {
        $scope.addProFormDiv = false;
        $scope.uploadProFormDiv = true;
        $scope.viewProductTestParametersDiv = true;
        $scope.addParaFormDiv = true;
        $scope.paraTable = true;
        $scope.editProductTestDiv = true;
    };

    //show form for add new  stndrd wise test product category
    $scope.showProTestParametersListView = function (test_id, product_cat_id, product_section_id) {
        $scope.proTable = true;
        $scope.addProFormDiv = true;
        $scope.uploadProFormDiv = true;
        $scope.viewProductTestParametersDiv = false;
        $scope.addParaFormDiv = true;
        $scope.paraTable = true;
        $scope.editProductTestDiv = true;
        $scope.productAndParameter = true;
        $scope.CurrentProductDeptId = product_section_id;
        $scope.getStdTestDetails(test_id, product_section_id);
        $scope.funTestProductStandardParamentersList(test_id);
        $scope.product_cat_id = product_cat_id;
        $scope.getParentCategoryId(test_id);
    };

    $scope.getParentCategoryId = function (testId) {
        $http.post(BASE_URL + "standard-wise-product/get-parent-category/" + testId, {}).success(function (data, status, headers, config) {
            $scope.parentCategoryId = data.product_parent_id;
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    };

    //********************show form for add new std wise test product category**************
    $scope.hideProTestParametersListView = function (test_id, product_section_id) {
        $scope.proTable = false;
        $scope.addProFormDiv = false;
        $scope.uploadProFormDiv = true;
        $scope.viewProductTestParametersDiv = true;
        $scope.addParaFormDiv = true;
        $scope.paraTable = true;
        $scope.editProductTestDiv = true;
        $scope.productAndParameter = false;
        $scope.currentProductDetail = true;
    };
    //********************show form for add new  stndrd wise test product category**************

    //*****************Get methods list according to parameter name*****************/	
    $scope.getMethodsList = function (product_category_id, equipment_id) {

        $scope.methodList = [];
        $scope.loaderShow();

        $http.post(BASE_URL + "methods-acc-product-category/" + product_category_id + '/' + equipment_id, {}).success(function (data, status, headers, config) {
            $scope.methodList = data.methodList;
            if (product_category_id == 2) {
                if (data.selectedMethod) {
                    $scope.method_id = {
                        selectedOption: { id: data.selectedMethod, name: '' }
                    };
                }
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    }
    //*****************/Get methods list according to parameter name*****************

    //*****************Get equipments list according to parameter name***************
    $scope.getEquipList = function (product_category_id, test_parameter_id) {
        if (test_parameter_id) {
            if (product_category_id != 2) {
                $scope.methodList = [];
            }
            $scope.loaderShow();
            $http.post(BASE_URL + "equipment-acc-parameter-category/" + product_category_id + '/' + test_parameter_id, {}).success(function (data, status, headers, config) {
                $scope.parameterEquipmentList = data.parameterEquipmentList;
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '404') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
            });
        }
    };
    //*****************/Get equipments list according to parameter name*****************

    //*****************Add test parameter details***************
    $scope.addParametersRecord = function () {

        if (!$scope.addtestParameterForm.$valid) return;
        $scope.loaderShow();

        //post all form data to save
        $http.post(BASE_URL + "standard-wise-product/add-parameters-details", {
            data: { formData: $(addtestParameterForm).serialize() },
        }).success(function (data, status, headers, config) {
            if (data.success) {
                $scope.getProductTestParameters(data.test_id);
                $scope.successMsgShow(data.success);
                $scope.resetParaForm();
            } else {
                $scope.errorMsgShow(data.error);
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
    $scope.resetParaForm = function () {
        $scope.stdValFromToHide = true;
        $scope.parameterEquipmentList = [];
        if ($scope.globalProductCategoryId != 2) {
            $scope.methodList = [];
        }
        $scope.productTestParameter = {};
        $scope.productTestParameter.time_taken_mins = '00:00';
        $scope.productTestParameter.data_types = {
            availableTypeOptions: [
                { id: 'numeric', name: 'Numeric' },
                { id: 'alphanumeric', name: 'Alphanumeric' },
                { id: 'na', name: 'NA' },
            ],
            selectedOption: { id: 'numeric', name: 'Numeric' }
        };
        $scope.productTestParameter.claimDependentList = {
            claimOptions: [
                { id: '1', name: 'Yes' },
                { id: '0', name: 'No' },
            ],
            selectedClaim: { id: '1' }
        };
        $scope.addtestParameterForm.$setUntouched();
        $scope.addtestParameterForm.$setPristine();
    };
    //*****************/Add test parameter details***************//

    //**********confirm box******************************************************
    $scope.funConfirmDeleteTestParameterMessage = function (parameter_id, test_id) {
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
                        $scope.deleteParameterRecord(parameter_id, test_id);
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

    //*******************delete std test parameter******************************
    $scope.deleteParameterRecord = function (parameter_id, test_id) {
        if (parameter_id) {
            $scope.loaderShow();
            $http.post(BASE_URL + "standard-wise-product/delete-parameters-details", {
                data: { "_token": "{{ csrf_token() }}", "id": parameter_id }
            }).success(function (data, status, headers, config) {
                if (data.success) {
                    $scope.getProductTestParameters(test_id);
                    $scope.successMsgShow(data.success);
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
            });
        }
    };
    //*******************/delete std test parameter******************************

    //************Edit an test parameters category and its data****************************
    $scope.editParameterRecord = function (id, test_id, testParameterCategory, test_parameter_id, equipment_id) {

        $scope.parameterEquipmentList = [];
        $scope.methodList = [];
        $scope.closeAllDropdownFilters();
        $scope.editDefaultParameterNablScope = false;

        if (id) {
            $scope.loaderMainShow();
            $http.post(BASE_URL + "standard-wise-product/edit-parameters-details", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                if (data.returnData.responseData) {
                    var responseD = data.returnData.responseData;
                    $scope.funsetAutoSelectedParameter(responseD.test_parameter_id, responseD.test_parameter_name, 'edit');
                    $scope.getEquipDetectorsList($scope.globalProductCategoryId, equipment_id);
                    $scope.getMethodsList($scope.globalProductCategoryId, equipment_id);
                    $scope.funCheckParameterDescription(responseD.test_parameter_name);
                    $scope.editProductTestParameter = responseD;
                    $scope.editProductTestParameter.claimDependentList = {
                        claimOptions: [
                            { id: '1', name: 'Yes' },
                            { id: '0', name: 'No' },
                        ],
                        selectedClaim: { id: responseD.claim_dependent }
                    };
                    $scope.editProductTestParameter.data_types = {
                        availableTypeOptions: [
                            { id: 'numeric', name: 'Numeric' },
                            { id: 'alphanumeric', name: 'Alphanumeric' },
                            { id: 'na', name: 'NA' },
                        ],
                        selectedOption: { id: responseD.standard_value_type }
                    };

                    $scope.stdValFromToHide = true;
                    if (responseD.standard_value_type == 'na') {
                        $scope.stdValFromToHide = false;
                    }
                    $scope.setMethodSelectedOption(responseD.method_id);
                    $scope.setEquipmentSelectedOption(responseD.equipment_id);
                    $scope.setDetectorSelectedOption(responseD.detector_id);

                    if (responseD.standard_value_type == 'numeric') {
                        $scope.inputTypeInt();
                        $scope.editProductTestParameter.standard_value_from = parseInt(responseD.standard_value_from);
                        $scope.editProductTestParameter.standard_value_to = parseInt(responseD.standard_value_to);
                    } else {
                        $scope.inputTypeText();
                        $scope.editProductTestParameter.standard_value_from = responseD.standard_value_from;
                        $scope.editProductTestParameter.standard_value_to = responseD.standard_value_to;
                    }

                    $scope.editProductTestParameter.parameter_decimal_place = responseD.parameter_decimal_place;
                    $scope.editProductTestParameter.cost_price = responseD.cost_price;
                    $scope.editProductTestParameter.selling_price = responseD.selling_price;
                    $scope.editProductTestParameter.time_taken_days = parseInt(responseD.time_taken_days);
                    $scope.editDefaultParameterNablScope = responseD.parameter_nabl_scope ? true : false;
                    $scope.para_test_id = btoa(responseD.test_id);
                    $scope.product_test_dtl_id = btoa(responseD.product_test_dtl_id);
                    $scope.showEditParameterForm();
                    $scope.editProductTestParameter.status = { selectedOption: { id: responseD.prodTestDtlStatus } };

                    $('html, body').animate({ scrollTop: $("#currentProDetailDiv").offset().top }, 500);
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderMainHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
            });
        }
    };
    $scope.setMethodSelectedOption = function (methodid) {
        $scope.editProductTestParameter.method_id = {
            selectedOption: { id: methodid }
        };
    };
    $scope.setEquipmentSelectedOption = function (equipment_id) {
        $scope.editProductTestParameter.equipment_type_id = {
            selectedOption: { id: equipment_id }
        };
    };
    $scope.setDetectorSelectedOption = function (detector_id) {
        $scope.editProductTestParameter.detector_id = {
            selectedOption: { id: detector_id }
        };
    };
    //************/Edit an test parameters category and its data****************************

    //***********************update test parameters category  and its data*****************
    $scope.updateParameterRecord = function (test_id) {

        if (!$scope.editParaForm.$valid) return;
        $scope.loaderShow();

        // post all form data to save
        $http.post(BASE_URL + "standard-wise-product/update-parameters-details", {
            data: { formData: $(editParaForm).serialize() },
        }).success(function (resData, status, headers, config) {
            if (resData.success) {
                $scope.getProductTestParameters(atob(test_id));
                $scope.successMsgShow(resData.success);
            } else {
                $scope.errorMsgShow(resData.error);
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
    //**********************/update test parameters category  and its data*****************

    //**************show form for test parameters category edit and its data*************
    $scope.showEditParameterForm = function () {
        $scope.editLable = false;
        $scope.addLable = true;
        $scope.paraBtns = true;
        $scope.editParaFormDiv = false;
        $scope.addParaFormDiv = true;
        $scope.addAltMethodBtn = true;
    };
    //**************/show form for test parameters category edit and its data*************

    //**************show form for add new  test parameters category***********************
    $scope.showAddParameterForm = function () {
        $scope.editLable = true;
        $scope.addLable = false;
        $scope.okHideBtn = false;
        $scope.paraBtns = false;
        $scope.editParaFormDiv = true;
        $scope.addParaFormDiv = false;
        $scope.addAltMethodBtn = false;
    };
    //*************/show form for add new  test parameters category***********************

    //*************show form for add new  test parameters category************************
    $scope.cancelEditPara = function () {
        $scope.editLable = true;
        $scope.addLable = false;
        $scope.paraBtns = false;
        $scope.okHideBtn = false;
        $scope.editParaFormDiv = true;
        $scope.addParaFormDiv = false;
        $scope.addAltMethodBtn = false;
        $scope.resetParaForm();
        $scope.hideAlertMsg();
    };
    //*************/show form for add new  test parameters category************************

    //********************Back to add std wise product page*******************************
    $scope.okRecord = function (currentTestId) {
        $scope.addProFormDiv = false;
        $scope.uploadProFormDiv = true;
        $scope.viewProductTestParametersDiv = false;
        $scope.proTable = false;
        $scope.addParaFormDiv = true;
        $scope.paraTable = true;
        $scope.editProductTestDiv = true;
        $scope.addParameterBtn = false;
        $scope.currentProductDetail = true;
        $scope.productTestParameter = {};
        $scope.method_id = { selectedOption: { id: '', name: '' } };
        $scope.addtestParameterForm.$setUntouched();
        $scope.addtestParameterForm.$setPristine();
        $scope.hideProTestParametersListView();
        $scope.hideAlertMsg();
    };
    //********************/Back to add std wise product page*******************************

    //*****************Standard Value Type dropdown****************************
    $scope.productTestParameter.data_types = {
        availableTypeOptions: [
            { id: 'numeric', name: 'Numeric' },
            { id: 'alphanumeric', name: 'Alphanumeric' },
            { id: 'na', name: 'NA' },
        ],
        selectedOption: { id: 'numeric', name: 'Numeric' }
    };
    $scope.onTypeChange = function (id) {
        $scope.stdValFromToHide = true;
        if (id == 'numeric') {
            $scope.inputTypeInt();
        } else if (id == 'na') {
            $scope.stdValFromToHide = false;
        } else {
            $scope.inputTypeText();
        }
    };
    //*****************display department types dropdown end*****************

    //*****************ALTERNATE METHOD SECTION START HERE****************	
    $scope.addAlternateMethod = function (test_id, product_test_dtl_id, test_parameter_id, equipment_type_id) {
        $scope.addAltMethod.alt_time_taken_mins = '00:00';
        if (test_parameter_id) {
            $scope.methodList = [];
            $scope.getEquipList($scope.globalProductCategoryId, test_parameter_id);
            if ($scope.globalProductCategoryId == 2) {
                $scope.getMethodsList($scope.globalProductCategoryId, 0);
            } else {
                $scope.getMethodsList($scope.globalProductCategoryId, equipment_type_id);
            }
            $scope.loaderShow();
            $scope.allListPaginate = false;
            $scope.allParaListPaginate = false;
            $scope.allAltMethodListPaginate = true;

            $http.post(BASE_URL + "product-test-details/get-product-test-details", {
                data: { "_token": "{{ csrf_token() }}", "test_id": test_id, "product_test_dtl_id": product_test_dtl_id, "id": test_parameter_id }
            }).success(function (data, status, headers, config) {
                if (data.proTestParameter) {
                    var responseD = data.proTestParameter;
                    $scope.addAltMethod = responseD;

                    $scope.alt_test_id = btoa(test_id);
                    $scope.alt_dtl_id = btoa(product_test_dtl_id);
                    $scope.alt_test_parameter_id = btoa(test_parameter_id);
                    $scope.alt_test_parameter_name = responseD.test_parameter_name;

                    $scope.showAddAlternateForm();
                    $scope.getProductTestParaAltMethods(test_id, product_test_dtl_id, test_parameter_id);

                    if (data.testDetails.test_std_name) {
                        $scope.altStdName = data.testDetails.test_std_name;
                        $scope.alt_test_code = data.testDetails.test_code;
                        $scope.alt_product_name = data.testDetails.product_name;
                        if ($scope.allAltMethodList.length) { var length = "(" + $scope.allAltMethodList.length + ")"; } else { var length = ""; }
                        $scope.altHeading = "All Standard Wise Product Test Parameters Alternative Methods for " + data.testDetails.test_std_name + " " + length;
                    }
                    $scope.addAltMethod.altClaimDependent = {
                        availableTypeOptions: [
                            { id: '1', name: 'Yes' },
                            { id: '0', name: 'No' },
                        ],
                        selectedClaim: { id: '1' }
                    };
                    $scope.addAltMethod.altdataTypes = {
                        availableTypeOptions: [
                            { id: 'numeric', name: 'Numeric' },
                            { id: 'alphanumeric', name: 'Alphanumeric' },
                            { id: 'na', name: 'NA' },
                        ],
                        selectedOption: { id: 'numeric', name: 'Numeric' }
                    };
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
            });
        }
    };
    //*****************/ALTERNATE METHOD SECTION START HERE****************	

    //******************save alternate method data***********************
    $scope.saveAlternateMethod = function () {

        if (!$scope.addAltMethodForm.$valid) return;
        $scope.loaderShow();

        // post all form data to save
        $http.post(BASE_URL + "product-test-details/add-alternative-method", {
            data: { formData: $(addAltMethodForm).serialize() },
        }).success(function (resData, status, headers, config) {
            if (resData.success) {
                $scope.getProductTestParaAltMethods(resData.test_id, resData.product_test_dtl_id, resData.test_parameter_id);
                $scope.successMsgShow(resData.success);
                $scope.resetAltMethodForm();
            } else {
                $scope.errorMsgShow(resData.error);
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
    $scope.resetAltMethodForm = function () {
        $scope.addAltMethod = {};
        $scope.addAltMethod.altClaimDependent = {
            availableTypeOptions: [
                { id: '1', name: 'Yes' },
                { id: '0', name: 'No' },
            ],
            selectedClaim: { id: '1' }
        };
        $scope.addAltMethod.altdataTypes = {
            availableTypeOptions: [
                { id: 'numeric', name: 'Numeric' },
                { id: 'alphanumeric', name: 'Alphanumeric' },
                { id: 'na', name: 'NA' },
            ],
            selectedOption: { id: 'numeric', name: 'Numeric' }
        };
        $scope.addAltMethod.alt_time_taken_mins = '00:00';
        $scope.addAltMethodForm.$setUntouched();
        $scope.addAltMethodForm.$setPristine();
    };
    //******************/save alternate method data***********************

    //*****************function is used to fetch the list of test parameters category****************** 	
    $scope.getProductTestParaAltMethods = function (test_id, product_test_dtl_id, test_parameter_id) {

        $scope.loaderShow();
        $scope.allListPaginate = false;
        $scope.allParaListPaginate = false;
        $scope.allAltMethodListPaginate = true;
        $scope.altTestId = test_id;
        $scope.altProductTestDtlId = product_test_dtl_id;
        $scope.altTestParameterId = test_parameter_id;

        $http.post(BASE_URL + "product-test-details/get-alt-methods-list", {
            data: { "_token": "{{ csrf_token() }}", "test_id": test_id, "product_test_dtl_id": product_test_dtl_id, "id": test_parameter_id }
        }).success(function (resData, status, headers, config) {
            if (resData.allAltMethodList) {
                $scope.allAltMethodList = resData.allAltMethodList;
                $scope.allParaList_test_id = resData.allParaList_test_id;
            } else {
                $scope.allAltMethodList = '';
                $scope.allParaList_test_id = '';
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    };
    //*****************/function is used to fetch the list of test parameters category****************** 	

    //**************multisearch for test parameters start here**********************
    $scope.multiSearchTrParaAlt = true;
    $scope.multisearchBtnParaAlt = false;
    $scope.getMultiSearchParaAlt = function (test_id, product_test_dtl_id, test_parameter_id) {

        $scope.loaderShow();
        $scope.allListPaginate = false;
        $scope.allParaListPaginate = false;
        $scope.allAltMethodListPaginate = true;
        $scope.searchAlt = '';
        $scope.searchStdTestParaAlt.search_test_id = test_id;
        $scope.searchStdTestParaAlt.search_product_test_dtl_id = product_test_dtl_id;
        $scope.searchStdTestParaAlt.search_test_parameter_id = test_parameter_id;

        $http.post(BASE_URL + "product-test-details/get-alt-methods-list-multisearch", {
            data: { formData: $scope.searchStdTestParaAlt },
        }).success(function (resData, status, headers, config) {
            if (resData.allAltMethodList) {
                $scope.allAltMethodList = resData.allAltMethodList;
                $scope.allParaList_test_id = resData.allParaList_test_id;
            } else {
                $scope.allAltMethodList = '';
                $scope.allParaList_test_id = '';
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') { }
            $scope.clearConsole();
        });
    };
    $scope.closeMultisearchParaAlt = function () {
        $scope.multiSearchTrParaAlt = true;
        $scope.multisearchBtnParaAlt = false;
        $scope.refreshMultisearchParaAlt();
    };
    $scope.refreshMultisearchParaAlt = function (alt_test_id, alt_dtl_id, alt_test_parameter_id) {
        $scope.searchStdTestParaAlt = {};
        $scope.searchAlt = '';
        $scope.getProductTestParaAltMethods(atob(alt_test_id), atob(alt_dtl_id), atob(alt_test_parameter_id));
    };
    $scope.openMultisearchParaAlt = function () {
        $scope.multiSearchTrParaAlt = false;
        $scope.multisearchBtnParaAlt = true;
    }
    //**************/multisearch for test parameters start here**********************

    //**********confirm box******************************************************
    $scope.funConfirmDeleteAltMethodMessage = function (id, test_id, product_test_dtl_id, test_parameter_id) {
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
                        $scope.deleteAltMethodRecord(id, test_id, product_test_dtl_id, test_parameter_id);
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

    //********************delete alternative method**************************
    $scope.deleteAltMethodRecord = function (id, test_id, product_test_dtl_id, test_parameter_id) {
        if (id) {
            $scope.loaderShow();

            $http.post(BASE_URL + "product-test-details/delete-alt-method", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                if (data.success) {
                    $scope.getProductTestParaAltMethods(test_id, product_test_dtl_id, test_parameter_id);
                    $scope.successMsgShow(data.success);
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.clearConsole();
                $scope.loaderHide();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
            });
        }
    };
    //********************/delete alternative method**************************

    $scope.setAddAltEquipmentSelectedOption = function (eqid) {
        $scope.addAltMethod.alt_equipment_type_id = {
            selectedOption: { id: eqid }
        };
    };

    $scope.setAddAltMethodSelectedOption = function (methodid) {
        $scope.addAltMethod.alt_method_id = {
            selectedOption: { id: methodid }
        };
    };

    $scope.setAltEquipmentSelectedOption = function (eqid) {
        $scope.editAltMethod.alt_equipment_type_id = {
            selectedOption: { id: eqid }
        };
    };

    $scope.setAltMethodSelectedOption = function (methodid) {
        $scope.editAltMethod.alt_method_id = {
            selectedOption: { id: methodid }
        };
    };
    $scope.setAltDetectorSelectedOption = function (detectorid) {
        $scope.editAltMethod.alt_detector_id = {
            selectedOption: { id: detectorid }
        };
    };

    //****************Edit an test parameters alternative method*********************
    $scope.editAltMethodRecord = function (id, equipment_type_id) {
        $scope.closeAllDropdownFilters();
        if (id) {
            $scope.loaderShow();
            if ($scope.globalProductCategoryId != 2) {
                $scope.getMethodsList($scope.globalProductCategoryId, equipment_type_id);
            }
            $http.post(BASE_URL + "product-test-details/edit-alt-method", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                $scope.showEditAlternateForm();
                if (data.returnData.responseData) {
                    var responseD = data.returnData.responseData;
                    $scope.editAltMethod = responseD;
                    $scope.editAltMethod.alt_method_id = {
                        selectedOption: { id: '' }
                    };
                    $scope.getEquipDetectorsList($scope.globalProductCategoryId, equipment_type_id);
                    $scope.setAltEquipmentSelectedOption(responseD.equipment_type_id);
                    $scope.setAltDetectorSelectedOption(responseD.detector_id);
                    $scope.editAltMethod.altdataTypes = {
                        availableTypeOptions: [
                            { id: 'numeric', name: 'Numeric' },
                            { id: 'alphanumeric', name: 'Alphanumeric' },
                            { id: 'na', name: 'NA' },
                        ],
                        selectedOption: { id: responseD.standard_value_type }
                    };
                    $scope.editAltMethod.altClaimDependent = {
                        availableTypeOptions: [
                            { id: '1', name: 'Yes' },
                            { id: '0', name: 'No' },
                        ],
                        selectedClaim: { id: responseD.claim_dependent }
                    };
                    if (responseD.standard_value_type == 'na') {
                        $scope.stdValFromToHide = false;
                    }
                    if (responseD.standard_value_type == 'numeric') {
                        $scope.inputTypeInt();
                        $scope.editAltMethod.alt_standard_value_from = parseInt(responseD.standard_value_from);
                        $scope.editAltMethod.alt_standard_value_to = parseInt(responseD.standard_value_to);
                    } else {
                        $scope.inputTypeText();
                        $scope.editAltMethod.alt_standard_value_from = responseD.standard_value_from;
                        $scope.editAltMethod.alt_standard_value_to = responseD.standard_value_to;
                    }
                    $scope.setAltMethodSelectedOption(responseD.method_id);
                    $scope.editAltMethod.alt_cost_price = parseInt(responseD.cost_price);
                    $scope.editAltMethod.alt_selling_price = parseInt(responseD.selling_price);
                    $scope.editAltMethod.alt_time_taken_mins = responseD.time_taken_mins;
                    $scope.editAltMethod.alt_time_taken_days = parseInt(responseD.time_taken_days);

                    $scope.alt_test_id1 = responseD.test_id;
                    $scope.alt_product_test_dtl_id1 = responseD.product_test_dtl_id;
                    $scope.alt_test_parameter_id1 = responseD.test_parameter_id;
                    $scope.product_test_param_altern_method_id = btoa(responseD.product_test_param_altern_method_id);
                    $scope.showEditAlternateForm();
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
            });
        }
    };
    //****************/Edit an test parameters alternative method*********************

    //******************update test parameters alternative-method category and its data**************
    $scope.updateAlternateMethod = function () {

        if (!$scope.editAltMethodForm.$valid) return;
        $scope.loaderShow();

        $http.post(BASE_URL + "product-test-details/update-alt-method", {
            data: { formData: $(editAltMethodForm).serialize() },
        }).success(function (resData, status, headers, config) {
            if (resData.success) {
                $scope.getProductTestParaAltMethods(resData.test_id, resData.product_test_dtl_id, resData.test_parameter_id);
                $scope.successMsgShow(resData.success);
            } else {
                $scope.errorMsgShow(resData.error);
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
    //******************/update test parameters alternative-method category and its data**************

    //show form for add new  test parameters category  
    $scope.showAddAlternateForm = function () {
        $scope.okAltHideBtn = false;
        $scope.productAndParameter = true;
        $scope.paraTable = true;
        $scope.altMethodTable = false;
        $scope.testProductAltMethodForm = false;
    };

    // show form for add new  test parameters category  
    $scope.showEditAlternateForm = function () {
        $scope.editAltMethodLable = false;
        $scope.addAltMethodLable = true;
        $scope.okAltHideBtn = true;
        $scope.editAlternateFormDiv = false;
        $scope.addAlternateFormDiv = true;
    };

    //show form for add new  test parameters category  
    $scope.cancelEditAlt = function () {
        $scope.editAltMethodLable = true;
        $scope.addAltMethodLable = false;
        $scope.okAltHideBtn = false;
        $scope.editAlternateFormDiv = true;
        $scope.addAlternateFormDiv = false;
    };
    //back to add std wise product parameters page
    $scope.returnToParameterList = function (currentTestId) {
        $scope.productTestParameter = {};
        $scope.claimDependent = {
            availableTypeOptions: [
                { id: '1', name: 'Yes' },
                { id: '0', name: 'No' },
            ],
            selectedOption: { id: '1' }
        };
        $scope.method_id = { selectedOption: { id: '', name: '' } };
        $scope.productTestParameter.time_taken_mins = '00:00';
        $scope.time_taken_days = '';
        $scope.addtestParameterForm.$setUntouched();
        $scope.addtestParameterForm.$setPristine();
        $scope.productAndParameter = false;
        $scope.paraTable = false;
        $scope.altMethodTable = true;
        $scope.testProductAltMethodForm = true;
        $scope.parameterNameList = [];
        $scope.parameterEquipmentList = [];
        $scope.methodList = [];
        $scope.resetParaForm();
    };
    /*****************ALTERNATE METHOD SECTION END HERE****************/

    //****************Equipment dropdown filter show/hide******************
    $scope.closeAllDropdownFilters = function () {
        $scope.searchStandard = {};
        $scope.searchDept = {};
        $scope.searchParaCat = {};
        $scope.searchPara = {};
        $scope.searchEquipment = {};
        $scope.searchMethod = {};
        $scope.hideMethodDropdownSearchFilter();
    };
    //****************/Equipment dropdown filter show/hide******************

    //*******on change on add parameter input display auto suggestion dropdown**************
    $scope.getAutoSearchParameterMatches = function (parameter_name, product_cat_id, parameter_cat_id) {
        $scope.parameterNameList = [];
        $scope.parameterEquipmentList = [];
        $http({
            method: 'GET',
            url: BASE_URL + 'standard-wise-product/auto-get-parameter-list/' + parameter_name + '/' + product_cat_id + '/' + parameter_cat_id
        }).success(function (result) {
            $scope.parameterNameList = result.parameterNameList;
            $scope.showAutoSearchParameterList = true;
            $scope.clearConsole();
        });
    };
    //*******/on change on add parameter input display auto suggestion dropdown**************

    //*****************set parameter value when user selecet from auto search list***********************
    $scope.funsetAutoSelectedParameter = function (autpParameterId, autpParameterName, formType) {

        $scope.funCheckParameterDescription(autpParameterName);
        $scope.funGetSelectParameterPrice(autpParameterId);

        if (formType == 'add') {
            $scope.productTestParameter.test_parameter_id = autpParameterId;
            $scope.productTestParameter.test_parameter_name = autpParameterName;
        } else if (formType == 'edit') {
            $scope.editProductTestParameter.test_parameter_id = autpParameterId;
            $scope.editProductTestParameter.test_parameter_name = autpParameterName;
        }
        $scope.getEquipList($scope.globalProductCategoryId, autpParameterId);
        $scope.parameterNameList = [];
    };
    //*****************/set parameter value when user selecet from auto search list***********************

    //**************Setting parameter NABL Scope************************************
    $scope.funSetTestParameterDetail = function (test_parameter_obj, formType) {
        if (formType == 'add') {
            $scope.addDefaultParameterNablScope = test_parameter_obj.test_parameter_nabl_scope ? true : false;
            $scope.productTestParameter.parameter_decimal_place = test_parameter_obj.test_parameter_decimal_place;
        } else if (formType == 'edit') {
            $scope.editDefaultParameterNablScope = test_parameter_obj.test_parameter_nabl_scope ? true : false;
            $scope.editProductTestParameter.parameter_decimal_place = test_parameter_obj.test_parameter_decimal_place;
        }
    };
    //**************/Setting parameter NABL Scope************************************

    /***** get selected parameter price******/
    $scope.funGetSelectParameterPrice = function (selectedParameterId) {
        $http.post(BASE_URL + "standard-wise-product/get-selected-parameter-price/" + selectedParameterId, {}).success(function (data, status, headers, config) {
            $scope.productTestParameter.cost_price = data.cost_price;
            $scope.productTestParameter.selling_price = data.selling_price;
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '400') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        });
    };
    $scope.funCheckParameterDescription = function (parameter_name) {
        if (parameter_name && (parameter_name.toLowerCase() == 'reference to protocol' || parameter_name.toLowerCase() == 'description')) {
            $scope.showDescriptionTextarea = true;
        } else {
            $scope.showDescriptionTextarea = false;
        }
    };
    $scope.closeAutoSearch = function () {
        $scope.parameterNameList = [];
        $scope.showAutoSearchParameterList = false;
    };
    //***************** /Auto complete Parameter Section ********************************

    //*****************get product test parameter list view*******************
    $scope.getStdTestDetails = function (id, product_parent_id) {
        $scope.currentTestId = '';
        $scope.CurrentProductDeptId = product_parent_id;
        if (id) {
            $scope.loaderShow();
            $scope.currentTestId = id;
            $http.post(BASE_URL + "standard-wise-product/getproduct", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                if (data.returnData.responseData) {
                    var responseD = data.returnData.responseData;
                    $scope.test_code_text = responseD.test_code;
                    $scope.product_text = responseD.product_name;
                    $scope.product_category_text = responseD.p_category_name;
                    $scope.test_standard_text = responseD.test_std_name;
                    $scope.wef_text = responseD.wef;
                    $scope.upto_text = responseD.upto;
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            });
        }
    };
    //*****************/get product test parameter list view**************

    //*****************get all TEST PARAMETERS with children in tree formate******************************
    $scope.allProductTestParametersList = [];
    $scope.funTestProductStandardParamentersList = function (testId) {
        $scope.GlobalViewProductTestId = testId;
        $http({
            method: 'GET',
            url: BASE_URL + 'master/standard-wise-product/get-product-test-parameters-list-with-child-view/' + testId,
        }).success(function (result) {
            if (result.productTestParametersList) {
                $scope.allProductTestParametersList = result.productTestParametersList;
                $scope.allProductTestParametersListCount = $scope.allProductTestParametersList.length;
                $scope.allProductTestParametersArr = [];
                $scope.effectType = [];
                angular.forEach($scope.allProductTestParametersList, function (data, key) {
                    var allProductTestParaObj = { 'name': data.categoryName, 'test_para_cat_id': data.categoryId, children: data.categoryParams };
                    $scope.allProductTestParametersArr.push(allProductTestParaObj);
                    $scope.effectType.push('move');
                });
                $scope.displayModuleList();
            }
            $scope.clearConsole();
        });
    };
    //*****************get all TEST PARAMETERS with children in tree formate********************************

    //******************************display all TEST PARAMETERS  with children in tree formate****************
    $scope.displayModuleList = function () {
        $scope.model = [
            []
        ];
        var mainTestParaArr = $scope.allProductTestParametersArr;
        var effectType = $scope.effectType;
        angular.forEach(effectType, function (effect, i) {
            var finalTestParaArr = { categoryName: mainTestParaArr[i].name, id: mainTestParaArr[i].test_para_cat_id, items: [], effectAllowed: effect };
            for (var k = 0; k < mainTestParaArr[i].children.length; ++k) {
                finalTestParaArr.items.push({ label: mainTestParaArr[i].children[k].test_parameter_name, id: mainTestParaArr[i].children[k].product_test_dtl_id, module_sort_by: mainTestParaArr[i].children[k].parameter_sort_by, effectAllowed: effect });
            }
            $scope.model[i % $scope.model.length].push(finalTestParaArr);
        });
    };
    //********/display all TEST PARAMETERS  with children in tree formate********************

    //**************************functions used for sorting***********************************
    $scope.dragoverCallback = function (index, external, type, callback) {
        $scope.logListEvent('dragged over', index, external, type);
        //Invoke callback to origin for container types.
        if (type == 'container' && !external) {
            //console.log('Container being dragged contains ' + callback() + ' items');
        }
        return index <= $scope.dragDropRowsCount;
    };
    $scope.dropCallback = function (index, item, external, type) {
        $scope.logListEvent('dropped at', index, external, type);
        //Return false here to cancel drop. Return true if you insert the item yourself.
        return item;
    };
    $scope.logEvent = function (message) {
        //console.log(message);
    };
    $scope.logListEvent = function (action, index, external, type) {
        var message = external ? 'External ' : '';
        message += type + ' element was ' + action + ' position ' + index;
    };
    //**************************/functions used for sorting************************************

    //******************************save sorted TEST PARAMETERS  list with children****************
    $scope.saveNavigationOrdering = function () {
        $scope.loaderShow();
        $http.post(BASE_URL + "master/standard-wise-product/save-product-test-parameters-list-with-child-view", {
            data: $scope.model,
        }).success(function (result, status, headers, config) {
            if (result.error == '1') {
                $scope.successMsgShow(result.message);
            } else {
                $scope.errorMsgShow(result.message);
            }
            $scope.clearConsole();
            $scope.loaderHide();
        }).error(function (result, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
            $scope.loaderHide();
        });
    };
    //**************************/save sorted TEST PARAMETERS  list with children********************	

    //************************************accordian on ordering*****************************
    $scope.displayContent = function (ul_id) {
        if (angular.element('#id_' + ul_id).hasClass('glyphicon-plus')) {
            $scope.GlyphiconPlus = 'glyphicon-minus';
            angular.element('#id_' + ul_id).addClass('glyphicon-minus');
            angular.element('#id_' + ul_id).removeClass('glyphicon-plus');
            angular.element('#ul_id_' + ul_id).show();
        } else if (angular.element('#id_' + ul_id).hasClass('glyphicon-minus')) {
            $scope.GlyphiconPlus = 'glyphicon-plus';
            angular.element('#id_' + ul_id).addClass('glyphicon-plus');
            angular.element('#id_' + ul_id).removeClass('glyphicon-minus');
            angular.element('#ul_id_' + ul_id).hide();
        }
    };
    //***************** /product test parameters sorting and list view ********************************

    //**************************print parameter list***********************************
    $scope.printParametersList = function (test_id) {
        $scope.loaderMainShow();
        $http({
            method: "POST",
            url: BASE_URL + "master/standard-wise-product/generate-test-parameter-list-pdf",
            data: { test_id: test_id },
        }).success(function (result, status, headers, config) {
            if (result.error == 1) {
                if (result.testParametersFile) {
                    window.open(BASE_URL + result.testParametersFile, '_blank');
                }
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        }).error(function (result, status, headers, config) {
            $scope.errorMsgShow($scope.defaultMsg);
            $scope.loaderMainHide();
            $scope.clearConsole();
        });
    }
    //**************************/print parameter list************************************

    //***************************upload csv**********************************************
    $(document).on('click', '#uploadProductTestBtnId', function (e) {
        e.preventDefault();
        var formdata = new FormData();
        formdata.append('productTestFile', $('#uploadProductTestFile')[0].files[0]);

        if ($scope.uploadType == 'TestHeader') {
            var url = BASE_URL + "master/standard-wise-product/upload-product-test-header";
            $scope.funUploadProductTestHeader(formdata, url);
        } else if ($scope.uploadType == 'TestDetails') {
            var url = BASE_URL + "master/standard-wise-product/upload-product-test-details";
            $scope.funUploadProductTestDetails(formdata, url);
        }
    });
    //***************************/upload csv**********************************************

    //***************upload product test header*****************************
    $scope.funUploadProductTestHeader = function (formdata, url) {
        $scope.loaderShow();
        $.ajax({
            url: url,
            type: "POST",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == '1') {
                    $scope.successMsgShow(res.message);
                    $scope.uploadType = '';
                    angular.element('#uploadProductTestFile').val('');
                    angular.element('.browseFileInput').val('');
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
    };
    //***************/upload product test header*****************************

    //**************upload product test details*********************
    $scope.funUploadProductTestDetails = function (formdata, url) {
        $scope.loaderShow();
        $.ajax({
            url: url,
            type: "POST",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                if (res.error == '1') {
                    $scope.successMsgShow(res.message);
                    $scope.uploadType = '';
                    angular.element('#uploadProductTestFile').val('');
                    angular.element('.browseFileInput').val('');
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
    };
    //**************/upload product test details*********************

    //*****************get product test parameter list view*******************
    $scope.funGetStdTestDetails = function (id, product_parent_id) {
        $scope.currentTestId = '';
        $scope.CurrentProductDeptId = product_parent_id;
        $scope.getCatProducts(product_parent_id);
        if (id) {
            $scope.loaderShow();
            $scope.currentTestId = id;
            $http.post(BASE_URL + "standard-wise-product/getproduct", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function (data, status, headers, config) {
                if (data.returnData.responseData) {
                    var responseD = data.returnData.responseData;
                    $scope.test_code_text = responseD.test_code;
                    $scope.product_text = responseD.product_name;
                    $scope.test_standard_text = responseD.test_std_name;
                    $scope.wef_text = responseD.wef;
                    $scope.upto_text = responseD.upto;
                    $scope.test_standard_id = { selectedOption: { id: responseD.test_std_id, test_std_name: responseD.test_std_name } };
                    $scope.product_id = responseD.product_id;
                    $scope.setProductSelectedOption(responseD.product_id);

                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            });
        }
    };
    //*****************/get product test parameter list view**************

    //*****************fun Show Clone Popup*******************************
    $scope.funShowClonePopup = function (testId, productDeptId, productCatId) {
        $scope.IsVisiablePopUpSuccessMsg = true;
        $scope.IsVisiablePopUpErrorMsg = true;
        $scope.test_id = testId;
        $scope.CurrentProductDeptId = productDeptId;
        $scope.funGetStdTestDetails(testId, productDeptId);
        $scope.getProductStandards(productDeptId); // test standard list
        $scope.getCloneProductTestParameters(testId, productCatId)
        $('#productTestClonePopup').modal('show');
    };
    //*****************/fun Show Clone Popup*************************************

    //************function is used to fetch the list of test parameters category*******	
    $scope.getCloneProductTestParameters = function (test_id, productCatId) {
        $scope.loaderShow();
        $scope.allListPaginate = false;
        $scope.allParaListPaginate = true;
        $scope.allAltMethodListPaginate = false;
        $scope.getCatProducts(productCatId);
        $http.post(BASE_URL + "standard-wise-product/get-parameters-details", {
            data: { "_token": "{{ csrf_token() }}", "id": test_id }
        }).success(function (data, status, headers, config) {
            if (data.allParaList) {
                $scope.allParaList = data.allParaList;
                $scope.allParaList_test_id = data.allParaList_test_id;
            } else {
                $scope.allParaList = '';
                $scope.allParaList_test_id = '';
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    };
    //************/function is used to fetch the list of test parameters category*******	

    //****************************save records******************************************
    $scope.funAddMoreProductTest = function () {
        var checkBoxChecked = $('input:checkbox:checked').length;
        if (checkBoxChecked >= 1) {
            $scope.loaderMainShow();
            $http.post(BASE_URL + "standard-wise-product/add-more-product-test", {
                data: { formData: $(CloneProductTestForm).serialize() },
            }).success(function (data, status, headers, config) {
                if (data.error == '1') {
                    $('#productTestClonePopup').modal('hide');
                    $scope.successMsgShow(data.message);
                } else {
                    $scope.errorPopUpMsgShow(data.message);
                }
                $scope.loaderMainHide();
                $scope.clearConsole();
            }).error(function (data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorPopUpMsgShow($scope.defaultMsg);
                }
                $scope.loaderMainHide();
                $scope.clearConsole();
            });
        } else {
            $scope.errorPopUpMsgShow($scope.CheckboxMsg);
        }
    };
    //****************************/save records******************************************

    //****************************Close records******************************************
    $scope.funClose = function () {
        $('#productTestClonePopup').modal('hide');
    };
    //****************************/Close records******************************************

    //*********************select all functionality************************************
    $scope.toggleAll = function () {
        $scope.allPopupSelectedParametersArray = [];
        var checkAllStatus = angular.element('#selectedAll').prop('checked');
        if (checkAllStatus) {
            angular.element('.parametersCheckBox').prop('checked', true);
            angular.element(".parametersCheckBox:checked").each(function () {
                $scope.allPopupSelectedParametersArray.push(this.value);
            });
        } else {
            angular.element('.parametersCheckBox').prop('checked', false);
        }
    };
    //********************/select all functionality************************************

    //*****************single checkbox***************************************
    $scope.allPopupSelectedParametersArray = [];
    $scope.funCheckParameterCheckedOrNot = function (dltId) {
        var paraStatus = angular.element('#checkOneParameter_' + dltId).prop('checked');
        if (paraStatus) {
            $scope.allPopupSelectedParametersArray.push(dltId);
        } else {
            angular.element('#selectedAll').prop('checked', false);
            $scope.allPopupSelectedParametersArray.pop(dltId);
        }
    };
    //*****************/single checkbox***************************************

    //************** getDetectorsList according to selected equipment.*********
    $scope.getEquipDetectorsList = function (product_category_id, equipment_id) {
        $scope.loaderShow();
        $http.post(BASE_URL + "detectors-acc-product-category/" + product_category_id + '/' + equipment_id, {}).success(function (data, status, headers, config) {
            $scope.detectorList = data.detectorList;
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function (data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });

    };
    //**************/getDetectorsList according to selected equipment.*********

    //*********Loading Data************************
	$timeout(function () { 
		$scope.getProductCategories(); 
	}, 100);
	$timeout(function () {
        $scope.inputTypeInt();   
        $scope.hideProTestParametersListView();
		$scope.getStandatdProductTest(0);
	}, 1000);
	//*********/Loading Data************************

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
            element.mask("99:99", {
                completed: function () {
                    controller.$setViewValue(this.val());
                    $scope.$apply();
                }
            });
        }
    };
}).directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if (event.which === 13) {
                scope.$apply(function () {
                    scope.$eval(attrs.ngEnter);
                });
                event.preventDefault();
            }
        });
    };
});