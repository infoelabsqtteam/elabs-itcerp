app.controller('detectorController', function($scope, $timeout, $http, BASE_URL, $ngConfirm) {
    //define empty variables
    $scope.detectorData = '';
    $scope.editDetectorFormDiv = true;

    //sorting variables
    $scope.sortType = 'detector_code'; // set the default sort type
    $scope.sortReverse = false; // set the default sort order
    $scope.searchFish = ''; // set the default search/filter term
    $scope.EquipmentTypeId = '0';
    $scope.uploadEquipmentFormDiv = true;
    //set the default search/filter term
    $scope.IsVisiableSuccessMsg = true;
    $scope.IsVisiableErrorMsg = true;
    $scope.addDetectorFormDiv = false;
    $scope.editDetectorFormDiv = true;
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
        $scope.addDetector = {};
        $scope.erpAddDetectorForm.$setUntouched();
        $scope.erpAddDetectorForm.$setPristine();
        $scope.editDetector = {};
        $scope.erpEditDetectorForm.$setUntouched();
        $scope.erpEditDetectorForm.$setPristine();
    };
    //********/reset Form************************************************

    //*********navigate Form************************************************
    $scope.navigateForm = function() {
        if ($scope.editDetectorFormDiv) {
            $scope.editDetectorFormDiv = false;
            $scope.addDetectorFormDiv = true;
        } else {
            $scope.editDetectorFormDiv = true;
            $scope.addDetectorFormDiv = false;
        }
    };
    //*********navigate Form************************************************
    //***************Status List */
    $scope.statusList = [
        { id: 1, name: 'Active' },
        { id: 2, name: 'Inactive' }

    ];
    $scope.detector_status = { selectedOption: { id: $scope.statusList[0].id, name: $scope.statusList[0].name } };

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
                        $scope.deleteDetector(id);
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
    $scope.predicate = 'detector_code';
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
            url: BASE_URL + 'master/detector/generate-detector-number'
        }).success(function(result) {
            $scope.default_detector_code = result.uniqueCode;
            $scope.clearConsole();
            $scope.loaderHide();
        });
    };
    //*****************/generate unique code*****************
    $scope.getAllDetectors = function(eq_type_id) {
        $scope.searchDetector = {};

        $scope.generateDefaultCode();
        if (angular.isDefined(eq_type_id)) {
            var eq_type_id = eq_type_id;
        } else {
            var eq_type_id = '0';
        }
        $scope.EquipmentTypeId = eq_type_id;
        $http.post(BASE_URL + "master/get-detector/" + eq_type_id, {}).success(function(data, status, headers, config) {
            $scope.detectorData = data.detectorsList;
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
        });
    };

    /*****************method SECTION START HERE *****************/
    $scope.funAddDetector = function() {

        if (!$scope.erpAddDetectorForm.$valid) return;
        $scope.loaderShow();

        // post all form data to save
        $http.post(BASE_URL + "master/detector/add-detector", {
            data: { formData: $(erpAddDetectorForm).serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.resetForm();
                $scope.getAllDetectors($scope.EquipmentTypeId);
                $scope.successMsgShow(data.success);
                $scope.loaderHide();
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
    $scope.funEditDetector = function(id) {
        if (id) {
            $scope.searchDropdown = '';
            $scope.resetForm();
            $http.post(BASE_URL + "master/detector/edit-detector", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.responseData) {
                    $scope.addDetectorFormDiv = true;
                    $scope.editDetectorFormDiv = false;
                    $scope.editDetector = data.responseData;
                    $scope.editDetector.equipment_type_id = {
                        selectedOption: { id: data.responseData.equipment_type_id }
                    };
                    $scope.editDetector.product_category_id = {
                        selectedOption: { id: data.responseData.p_category_id }
                    };
                    $scope.editDetector.status = { selectedOption: { id: data.responseData.status } };

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
    $scope.funUpdateDetector = function() {

        if (!$scope.erpEditDetectorForm.$valid) return;
        $scope.loaderShow();

        $http.post(BASE_URL + "master/detector/update-detector", {
            data: { formData: $(erpEditDetectorForm).serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.resetForm();
                $scope.navigateForm();
                $scope.getAllDetectors($scope.EquipmentTypeId);
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
    $scope.deleteDetector = function(id) {
        if (id) {
            $scope.loaderShow();
            $http.post(BASE_URL + "master/detector/delete-detector", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.success) {
                    $scope.getAllDetectors($scope.EquipmentTypeId);
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
    $scope.getMultiSearch = function() {
        $scope.loaderShow();
        $timeout(function() { $scope.callAtTimeout(); }, 3000);


    };
    $scope.multiSearchTr = true;
    $scope.multisearchBtn = false;

    $scope.callAtTimeout = function() {
        $scope.filterDetector = '';
        $http.post(BASE_URL + "master/detector/get-detectors-multisearch", {
            data: { formData: $(erpFilterMultiSearchDetectorForm).serialize() },
        }).success(function(data, status, headers, config) {
            $scope.detectorData = data.detectorsList;
            $scope.loaderHide();
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '400') {
                scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.loaderHide();
            $scope.clearConsole();
        });
    };

    $scope.closeMultisearch = function() {
        $scope.multiSearchTr = true;
        $scope.multisearchBtn = false;
        $scope.refreshMultisearch();
    };

    $scope.refreshMultisearch = function() {
        $scope.searchDetector = {};
        $scope.filterDetector = '';
        $scope.getAllDetectors($scope.EquipmentTypeId);
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
        $scope.editDetectorFormDiv = false;
        $scope.addDetectorFormDiv = true;
        $scope.uploadEquipmentFormDiv = true;
    };

    //show form for add new  method 
    $scope.showAddForm = function() {
        $scope.editDetectorFormDiv = true;
        $scope.addDetectorFormDiv = false;
        $scope.uploadEquipmentFormDiv = true;
    };

    //show form for add new  method 
    $scope.showAddForm = function() {
        $scope.editDetectorFormDiv = true;
        $scope.addDetectorFormDiv = false;
        $scope.uploadEquipmentFormDiv = true;
    };

    //show form for add new  equipment 
    $scope.showUploadForm = function() {
        $scope.resetUploadForm()
        $scope.editDetectorFormDiv = true;
        $scope.addDetectorFormDiv = true;
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
    $(document).on('click', '#uploadDetectorsBtnId', function(e) {
        e.preventDefault();
        var formdata = new FormData();
        formdata.append('detectorsFile', $('#detectorsFile')[0].files[0]);

        $scope.loaderShow();
        $.ajax({
            url: BASE_URL + "master/detectors/upload-detectors-csv",
            type: "POST",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {
                if (res.error == '1') {
                    $scope.successMsgShow(res.message);
                    $scope.getAllDetectors($scope.EquipmentTypeId);
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
            angular.element('#detectorsFile').val('');
            angular.element('.browseFileInput').val('');
        }
        //***************************upload csv**********************************************	

    //***************** method SECTION END HERE *****************//

});