app.controller('methodController', function($scope, $http, BASE_URL, $ngConfirm) {

    //define empty variables
    $scope.methodData = '';
    $scope.editMethodFormDiv = true;

    //sorting variables
    $scope.sortType = 'method_code'; // set the default sort type
    $scope.sortReverse = false; // set the default sort order
    $scope.searchFish = ''; // set the default search/filter term
    $scope.EquipmentTypeId = '0';
    $scope.uploadEquipmentFormDiv = true;
    //set the default search/filter term
    $scope.IsVisiableSuccessMsg = true;
    $scope.IsVisiableErrorMsg = true;
    $scope.addMethodFormDiv = false;
    $scope.editMethodFormDiv = true;
    $scope.successMessage = '';
    $scope.errorMessage = '';
    $scope.addMethod = {};
    $scope.editMethod = {};
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
        }
        //**********/loader show**************************************************

    //**********Clearing Console********************************************
    $scope.clearConsole = function() {
            //console.clear();
        }
        //*********/Clearing Console********************************************

    //**********Read/hide More description********************************************
    $scope.toggleDescription = function(type, id) {
        angular.element('#' + type + 'limitedText-' + id).toggle();
        angular.element('#' + type + 'fullText-' + id).toggle();
    };
    //*********/Read More description********************************************	

    //**********successMsgShow**************************************************
    $scope.successMsgShow = function(message) {
            $scope.successMessage = message;
            $scope.IsVisiableSuccessMsg = false;
            $scope.IsVisiableErrorMsg = true;
            $scope.moveToMsg();
        }
        //********** /successMsgShow************************************************

    //**********errorMsgShow**************************************************
    $scope.errorMsgShow = function(message) {
            $scope.errorMessage = message;
            $scope.IsVisiableSuccessMsg = true;
            $scope.IsVisiableErrorMsg = false;
            $scope.moveToMsg();
        }
        //********** /errorMsgShow************************************************

    //*********hideAlertMsg*************
    $scope.hideAlertMsg = function() {
            $scope.IsVisiableSuccessMsg = true;
            $scope.IsVisiableErrorMsg = true;
        }
        //**********/hideAlertMsg********************************************

    //*********reset Form************************************************
    $scope.resetForm = function() {
        $scope.addMethod = {};
        $scope.erpAddMethodForm.$setUntouched();
        $scope.erpAddMethodForm.$setPristine();
        $scope.editMethod = {};
        $scope.erpEditMethodForm.$setUntouched();
        $scope.erpEditMethodForm.$setPristine();
    };
    //********/reset Form************************************************

    //*********navigate Form************************************************
    $scope.navigateForm = function() {
        if ($scope.editMethodFormDiv) {
            $scope.editMethodFormDiv = false;
            $scope.addMethodFormDiv = true;
        } else {
            $scope.editMethodFormDiv = true;
            $scope.addMethodFormDiv = false;
        }
    };
    //*********navigate Form************************************************
    //***************Status List */
    $scope.methodStatusList = [
        { id: 1, name: 'Active' },
        { id: 2, name: 'Inactive' }

    ];
    $scope.addMethod.method_status = { selectedOption: { id: $scope.methodStatusList[0].id, name: $scope.methodStatusList[0].name } };

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
                        $scope.deleteMethod(id);
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
    $scope.predicate = 'method_code';
    $scope.reverse = true;
    $scope.sortBy = function(predicate) {
        $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
        $scope.predicate = predicate;
    };

    //*****************display equipment dropdown code start*************
    $scope.equipmentTypesList = [];
    $http({
        method: 'POST',
        url: BASE_URL + 'equipment-types-list'
    }).success(function(result) {
        $scope.equipmentTypesList = result.equipmentTypesList;
        $scope.clearConsole();
    });
    //*****************display equipment code dropdown end****************

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
    $scope.default_method_code = '';
    $scope.generateDefaultCode = function() {
        $scope.loaderShow();
        $http({
            method: 'GET',
            url: BASE_URL + 'method/generate-method-number'
        }).success(function(result) {
            $scope.default_method_code = result.uniqueCode;
            $scope.clearConsole();
            $scope.loaderHide();
        });
    };
    //*****************/generate unique code*****************
    $scope.getMethods = function(eq_type_id) {
        $scope.searchMethod = {};
        $scope.generateDefaultCode();
        if (angular.isDefined(eq_type_id)) {
            var eq_type_id = eq_type_id;
        } else {
            var eq_type_id = '0';
        }
        $scope.EquipmentTypeId = eq_type_id;
        $http.post(BASE_URL + "method/get-methods/" + eq_type_id, {}).success(function(data, status, headers, config) {
            $scope.methodData = data.methodsList;
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    };

    /*****************method SECTION START HERE *****************/
    $scope.funAddMethod = function() {

        if (!$scope.erpAddMethodForm.$valid) return;
        $scope.loaderShow();

        // post all form data to save
        $http.post(BASE_URL + "method/add-method", {
            data: { formData: $(erpAddMethodForm).serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.resetForm();
                $scope.getMethods($scope.EquipmentTypeId);
                $scope.successMsgShow(data.success);
            } else {
                $scope.errorMsgShow(data.error);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        });
    };

    //edit an method and its data
    $scope.funEditMethod = function(id) {
        if (id) {
            $scope.searchDropdown = '';
            $scope.resetForm();
            $http.post(BASE_URL + "method/edit-method", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.responseData) {
                    $scope.addMethodFormDiv = true;
                    $scope.editMethodFormDiv = false;
                    $scope.editMethod = data.responseData;
                    $scope.editMethod.equipment_type_id = {
                        selectedOption: { id: data.responseData.equipment_type_id }
                    };
                    $scope.editMethod.product_category_id = {
                        selectedOption: { id: data.responseData.p_category_id }
                    };
                    $scope.editMethod.status = { selectedOption: { id: data.responseData.status } };

                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.clearConsole();
            }).error(function(data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
            });
        }
    };

    // update method and its data
    $scope.funUpdateMethod = function() {

        if (!$scope.erpEditMethodForm.$valid) return;
        $scope.loaderShow();

        $http.post(BASE_URL + "method/update-method", {
            data: { formData: $(erpEditMethodForm).serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.resetForm();
                $scope.navigateForm();
                $scope.getMethods($scope.EquipmentTypeId);
                $scope.showAddForm();
                $scope.successMsgShow(data.success);
            } else {
                $scope.errorMsgShow(data.error);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        });
    };

    //Delete method from the database
    $scope.deleteMethod = function(id) {
        if (id) {
            $scope.loaderShow();
            $http.post(BASE_URL + "method/delete-method", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.success) {
                    $scope.getMethods($scope.EquipmentTypeId);
                    $scope.successMsgShow(data.success);
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.loaderHide();
                $scope.clearConsole();
            }).error(function(data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
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
        $scope.filterMethod = '';
        $scope.searchMethod.search_equipment_type_id = $scope.EquipmentTypeId;
        $http.post(BASE_URL + "method/get-methods-multisearch", {
            data: { formData: $scope.searchMethod },
        }).success(function(data, status, headers, config) {
            $scope.methodData = data.methodsList;
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '400') {
                //$scope.errorMsgShow($scope.defaultMsg);
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
        $scope.searchMethod = {};
        $scope.filterMethod = '';
        $scope.getMethods($scope.EquipmentTypeId);
    };

    $scope.openMultisearch = function() {
        $scope.multiSearchTr = false;
        $scope.multisearchBtn = true;
    };
    /**************multisearch end here**********************/
    $scope.setSelectedOption = function(eqid) {
        $scope.equipment_type_id1 = {
            selectedOption: { id: eqid }
        };
    };

    //show form for method edit and its data
    $scope.showEditForm = function() {
        $scope.editMethodFormDiv = false;
        $scope.addMethodFormDiv = true;
        $scope.uploadEquipmentFormDiv = true;
    };

    //show form for add new  method 
    $scope.showAddForm = function() {
        $scope.editMethodFormDiv = true;
        $scope.addMethodFormDiv = false;
        $scope.uploadEquipmentFormDiv = true;
    };

    //show form for add new  method 
    $scope.showAddForm = function() {
        $scope.editMethodFormDiv = true;
        $scope.addMethodFormDiv = false;
        $scope.uploadEquipmentFormDiv = true;
    };

    //show form for add new  equipment 
    $scope.showUploadForm = function() {
        $scope.resetUploadForm()
        $scope.editMethodFormDiv = true;
        $scope.addMethodFormDiv = true;
        $scope.uploadEquipmentFormDiv = false;
    };

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

    //***************************upload csv**********************************************
    $(document).on('click', '#uploadMethodsBtnId', function(e) {
        e.preventDefault();
        var formdata = new FormData();
        formdata.append('methodsFile', $('#methodFile')[0].files[0]);

        $scope.loaderShow();
        $.ajax({
            url: BASE_URL + "master/method/upload-methods-csv",
            type: "POST",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {
                if (res.error == '1') {
                    $scope.successMsgShow(res.message);
                    $scope.getMethods($scope.EquipmentTypeId);
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
            angular.element('#methodFile').val('');
            angular.element('.browseFileInput').val('');
        }
        //***************************upload csv**********************************************	

    //***************** method SECTION END HERE *****************//
});