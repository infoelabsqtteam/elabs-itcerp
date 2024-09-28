app.controller('equipmentController', function($scope, $http, BASE_URL, $window, $ngConfirm) {

    //define empty variables
    $scope.equipData = '';
    $scope.equipmentForm = [];
    $scope.editEquipmentForm = [];

    //sorting variables
    $scope.sortType = 'equipment_code'; // set the default sort type
    $scope.sortReverse = false; // set the default sort order
    $scope.searchFish = ''; // set the default search/filter term
    $scope.uploadEquipmentFormDiv = true;

    //define empty variables
    $scope.prodata = '';
    $scope.editProductFormDiv = true;

    //**********scroll to top function**********
    $scope.moveToMsg = function() {
            $('html, body').animate({
                scrollTop: $(".alert").offset().top
            }, 500);
        }
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
        //console.clear();
    };
    //*********/Clearing Console********************************************

    //**********Read/hide More description********************************************
    $scope.toggleDescription = function(type, id) {
        angular.element('#' + type + 'limitedText-' + id).toggle();
        angular.element('#' + type + 'fullText-' + id).toggle();
    };
    //*********/Read More description********************************************

    //set the default search/filter term
    $scope.IsVisiableSuccessMsg = true;
    $scope.IsVisiableErrorMsg = true;

    $scope.IsViewEquipmentTypeList = false;
    $scope.IsViewEquipmentTypeAdd = false;
    $scope.IsViewEquipmentTypeEdit = true;
    $scope.IsViewEquipmentTypeUpload = true;
    $scope.IsViewEquipmentTypeSort = true;

    $scope.successMessage = '';
    $scope.errorMessage = '';
    $scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';

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

    //**********successMsgShowPopup**************************************************
    $scope.successMsgShowPopup = function(message) {
        $scope.successMessagePopup = message;
        $scope.IsVisiableSuccessMsgPopup = false;
        $scope.IsVisiableErrorMsgPopup = true;
        $scope.moveToMsg();
    };
    //********** /successMsgShowPopup************************************************

    //**********errorMsgShowPopup**************************************************
    $scope.errorMsgShowPopup = function(message) {
        $scope.errorMessagePopup = message;
        $scope.IsVisiableSuccessMsgPopup = true;
        $scope.IsVisiableErrorMsgPopup = false;
        $scope.moveToMsg();
    };
    //********** /hideAlertMsgPopup************************************************

    //**********hideAlertMsgPopup*************
    $scope.hideAlertMsgPopup = function() {
        $scope.IsVisiableSuccessMsgPopup = true;
        $scope.IsVisiableErrorMsgPopup = true;
    };
    //********** /hideAlertMsgPopup**********************************************

    //*********hideAlertMsg*************
    $scope.hideAlertMsg = function() {
            $scope.IsVisiableSuccessMsg = true;
            $scope.IsVisiableErrorMsg = true;
        }
        //**********/hideAlertMsg********************************************

    //**************code used for sorting list order by fields***************
    $scope.predicate = 'equipment_code';
    $scope.reverse = false;
    $scope.sortBy = function(predicate) {
        $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
        $scope.predicate = predicate;
    };
    //*************/code used for sorting list order by fields***************

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
            buttons: {
                OK: {
                    text: 'ok',
                    btnClass: 'btn-primary',
                    action: function() {
                        $scope.deleteEquipment(id);
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
    //***************Status List */
    $scope.statusList = [
        { id: 1, name: 'Active' },
        { id: 2, name: 'Inactive' }

    ];
    $scope.equipment_status = { selectedOption: { id: $scope.statusList[0].id, name: $scope.statusList[0].name } };

    //*****************generate unique code******************
    $scope.equipment_code = '';
    $scope.generateDefaultCode = function() {
        $scope.loaderShow();
        $http({
            method: 'GET',
            url: BASE_URL + 'equipment/generate-equipment-number'
        }).success(function(result) {
            $scope.equipment_code = result.uniqueCode;
            $scope.clearConsole();
            $scope.loaderHide();
        });
    };
    //*****************/generate unique code*****************

    // show form for equipment edit and its data
    $scope.showEditForm = function() {
        $scope.IsViewEquipmentTypeList = false;
        $scope.IsViewEquipmentTypeAdd = true;
        $scope.IsViewEquipmentTypeEdit = false;
        $scope.IsViewEquipmentTypeUpload = true;
        $scope.IsViewEquipmentTypeSort = true;
    };

    // show form for add new  equipment 
    $scope.showAddForm = function() {
        $scope.IsViewEquipmentTypeList = false;
        $scope.IsViewEquipmentTypeAdd = false;
        $scope.IsViewEquipmentTypeEdit = true;
        $scope.IsViewEquipmentTypeUpload = true;
        $scope.IsViewEquipmentTypeSort = true;
    };

    // show form for add new  equipment 
    $scope.showUploadForm = function() {
        $scope.resetUploadForm();
        $scope.IsViewEquipmentTypeList = false;
        $scope.IsViewEquipmentTypeAdd = true;
        $scope.IsViewEquipmentTypeEdit = true;
        $scope.IsViewEquipmentTypeUpload = false;
        $scope.IsViewEquipmentTypeSort = true;
    };

    // show form for add new  equipment 
    $scope.showSortForm = function() {
        $scope.IsViewEquipmentTypeList = true;
        $scope.IsViewEquipmentTypeAdd = true;
        $scope.IsViewEquipmentTypeEdit = true;
        $scope.IsViewEquipmentTypeUpload = true;
        $scope.IsViewEquipmentTypeSort = false;
    };

    //function is used to call the 
    $scope.addEquipment = function() {

        if (!$scope.equipmentForm.$valid) return;
        $scope.loaderMainShow();

        $http.post(BASE_URL + "equipment/add-equipment", {
            data: { formData: $(equipmentForm).serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.equipment = {};
                $scope.equipmentForm.$setUntouched();
                $scope.equipmentForm.$setPristine();
                $scope.getEquipments();
                $scope.successMsgShow(data.success);
            } else {
                $scope.errorMsgShow(data.error);
            }
            $scope.clearConsole();
            $scope.loaderMainHide();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
            $scope.loaderMainHide();
        });
    };

    //***********edit an equipment and its data***************************************
    $scope.editEquipment = function(id) {
        if (id) {
            $http.post(BASE_URL + "equipment/edit-equipment", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.responseData) {
                    $scope.showEditForm();
                    $scope.equipment_code1 = data.responseData.equipment_code;
                    $scope.equipment_name1 = data.responseData.equipment_name;
                    $scope.equipment_capacity1 = data.responseData.equipment_capacity;
                    $scope.equipment_description1 = data.responseData.equipment_description;
                    $scope.equipment_id1 = btoa(data.responseData.equipment_id);
                    $scope.equipment_status1 = { selectedOption: { id: data.responseData.status } };
                    $('html, body').animate({ scrollTop: $("#editEquipmentDiv").offset().top }, 500);
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
    //**********/edit an equipment and its data***************************************

    //**********update equipment and its data*************************************
    $scope.updateEquipment = function() {

        if (!$scope.editEquipmentForm.$valid) return;
        $scope.loaderMainShow();

        // post all form data to save
        $http.post(BASE_URL + "equipment/update-equipment", {
            data: { formData: $(editEquipmentForm).serialize() },
        }).success(function(data, status, headers, config) {
            if (data.success) {
                $scope.equipment = {};
                $scope.editEquipmentForm.$setUntouched();
                $scope.editEquipmentForm.$setPristine();
                $scope.getEquipments();
                $scope.showAddForm();
                $scope.successMsgShow(data.success);
            } else {
                $scope.errorMsgShow(data.error);
            }
            $scope.clearConsole();
            $scope.loaderMainHide();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
            $scope.clearConsole();
            $scope.loaderMainHide();
        });
    };
    //**********/update equipment and its data*************************************

    //function is used to fetch the list of compines	
    $scope.getEquipments = function() {

        $scope.generateDefaultCode();

        $http.post(BASE_URL + "equipment/get-equipments", {}).success(function(data, status, headers, config) {
            $scope.equipData = data.equipmentsList;
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
        });
    };

    /**************multisearch start here**********************/
    $scope.multiSearchTr = true;
    $scope.multisearchBtn = false;

    $scope.getMultiSearch = function() {
        $scope.filterEquipment = '';
        $http.post(BASE_URL + "equipment/get-equipments-multisearch", {
            data: { formData: $scope.searchEquipment },
        }).success(function(data, status, headers, config) {
            $scope.equipData = data.equipmentsList;
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
        $scope.searchEquipment = {};
        $scope.getEquipments();
    };

    $scope.refreshMultisearch = function() {
        $scope.searchEquipment = {};
        $scope.getEquipments();
    };

    $scope.openMultisearch = function() {
        $scope.multiSearchTr = false;
        $scope.multisearchBtn = true;
    };

    /**************multisearch end here**********************/
    // Delete equipment from the database
    $scope.deleteEquipment = function(id) {
        if (id) {
            $scope.loaderMainShow();
            $http.post(BASE_URL + "equipment/delete-equipment", {
                data: { "_token": "{{ csrf_token() }}", "id": id }
            }).success(function(data, status, headers, config) {
                if (data.success) {
                    // reload the all employee
                    $scope.getEquipments();
                    $scope.successMsgShow(data.success);
                } else {
                    $scope.errorMsgShow(data.error);
                }
                $scope.clearConsole();
                $scope.loaderMainHide();
            }).error(function(data, status, headers, config) {
                if (status == '500' || status == '400') {
                    $scope.errorMsgShow($scope.defaultMsg);
                }
                $scope.clearConsole();
                $scope.loaderMainHide();
            });
        }
    };

    //***************************upload csv**********************************************
    $(document).on('click', '#uploadEquipmentTypesBtnId', function(e) {

        e.preventDefault();
        var formdata = new FormData();
        formdata.append('equipmentFile', $('#equipmentTypesFile')[0].files[0]);
        $scope.loaderShow();

        $.ajax({
            url: BASE_URL + "master/equipment/upload-equipment-types-csv",
            type: "POST",
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {
                if (res.error == '1') {
                    $scope.successMsgShow(res.message);
                    $scope.getEquipments();
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
            angular.element('#equipmentTypesFile').val('');
            angular.element('.browseFileInput').val('');
        }
        //***************************upload csv**********************************************

    /***************************************************************************************
     *Function    : get Selected UnSelected Equipments
     *Created By : Praveen Singh
     *Created On : 30-Jan-2018
     ****************************************************************************************/
    $scope.getSelectedUnSelectedEquipments = function() {

        angular.element("#equipmentSorting").sortable();
        angular.element("#equipmentSorting").disableSelection();

        $http({
            url: BASE_URL + "master/equipment/get-selected-sorting-equipments",
            method: "POST",
        }).success(function(result, status, headers, config) {
            $scope.selectEquipmentList = result.selectEquipmentList;
            $scope.selectedEquipmentList = result.selectedEquipmentList;
            $scope.clearConsole();
        }).error(function(data, status, headers, config) {
            if (status == '500' || status == '404') {
                $scope.errorMsgShow($scope.defaultMsg);
            }
        });
    };
    //**************/function get Selected UnSelected Equipments****************************

    /***************************************************************************************
     *Function   : Save Update Select Sorting Equipment
     *Created By : Praveen Singh
     *Created On : 30-Jan-2018
     ****************************************************************************************/
    $scope.funSaveUpdateSelectSortingEquipment = function() {

        if (!$scope.erpSelectEquipmentSortingForm.$valid) return;
        if ($scope.newSelectEquipmentSortingFormflag) return;
        $scope.newSelectEquipmentSortingFormflag = true;
        $scope.loaderMainShow();

        var http_para_string = { formData: $(erpSelectEquipmentSortingForm).serialize() };

        $http({
            url: BASE_URL + "master/equipment/save-update-select-sorting-equipment",
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            data: http_para_string
        }).success(function(result, status, headers, config) {
            $scope.newSelectEquipmentSortingFormflag = false;
            if (result.error == 1) {
                $scope.getSelectedUnSelectedEquipments();
                $scope.successMsgShow(result.message);
            } else {
                $scope.errorMsgShow(result.message);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        }).error(function(result, status, headers, config) {
            $scope.newSelectEquipmentSortingFormflag = false;
            if (status == '500' || status == '404') {
                $scope.errorMsgShowPopup($scope.defaultMsg);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        });
    };
    //**********/Save Update Select Sorting Equipment***************************************

    /***************************************************************************************
     *Function   : Save Update Selected Sorting Equipment
     *Created By : Praveen Singh
     *Created On : 30-Jan-2018
     ****************************************************************************************/
    $scope.funSaveUpdateSelectedSortingEquipment = function() {

        if (!$scope.erpSelectedEquipmentSortingForm.$valid) return;
        if ($scope.newSelectedEquipmentSortingFormflag) return;
        $scope.newSelectedEquipmentSortingFormflag = true;
        $scope.loaderMainShow();

        var http_para_string = { formData: $(erpSelectedEquipmentSortingForm).serialize() };

        $http({
            url: BASE_URL + "master/equipment/save-update-selected-sorting-equipment",
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            data: http_para_string
        }).success(function(result, status, headers, config) {
            $scope.newSelectedEquipmentSortingFormflag = false;
            if (result.error == 1) {
                $scope.getSelectedUnSelectedEquipments();
                $scope.successMsgShow(result.message);
            } else {
                $scope.errorMsgShow(result.message);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        }).error(function(result, status, headers, config) {
            $scope.newSelectedEquipmentSortingFormflag = false;
            if (status == '500' || status == '404') {
                $scope.errorMsgShowPopup($scope.defaultMsg);
            }
            $scope.loaderMainHide();
            $scope.clearConsole();
        });
    };
    //**********/Save Update Selected Sorting Equipment**************************************
});