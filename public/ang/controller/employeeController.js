app.controller('employeeController', function ($scope, $q, $timeout, $http, BASE_URL, $filter, $ngConfirm) {

	//define empty variables
	$scope.empdata = '';
	$scope.sortType = 'name';    // set the default sort type
	$scope.sortReverse = false;     // set the default sort order
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.searchEmployee = {};
	$scope.defaultMsg = 'Oops! Sorry for inconvience server not responding or may be some error.';

	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.isEmployeeListDiv = false;
	$scope.isEmployeeAddDiv = true;
	$scope.isEmployeeEditDiv = true;
	$scope.isEmployeeMISEmailDiv = true;
	$scope.searchHide = true;
	$scope.multiSearchTr = true;
	$scope.multisearchBtn = false;
	$scope.addEmployee = {};
	$scope.customPermissionList = [
		{ per_key: 'hold_unhold_permission', per_name: 'Hold/Unhold Permission', per_value: '1' },
		{ per_key: 'account_hold_upload_permission', per_name: 'Account Hold Upload Permission', per_value: '1' }
	];

	//**********/If DIV is hidden it will be visible and vice versa************
	//**********scroll to top function*****************************************
	$scope.moveToMsg = function () {
		$('html, body').animate({ scrollTop: $(".alert").offset().top }, 500);
	};
	//**********/scroll to top function*******************************************

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
	//************code used for display status in dropdown*****************
	$scope.employeeStatusList = [
		{ id: 1, name: 'Active' },
		{ id: 2, name: 'Inactive' }

	];
	$scope.addEmployee.status = { selectedOption: { id: $scope.employeeStatusList[0].id, name: $scope.employeeStatusList[0].name } };
	//************code used for display remark type in dropdown*****************

	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function (id, division_id) {
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
						$scope.deleteEmployee(id, division_id);
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

	//**********successMsgShow**************************************************
	$scope.uploadMsgShow = function (uploadMsg) {
		$scope.uplodedMessageHide = false;
		$scope.notUplodedMessageHide = false;
		$scope.notUplodedMessageHide = false;
		$scope.uplodedMessage = uploadMsg.uploaded;
		$scope.notUplodedMessage = uploadMsg.notUploaded;
		$scope.notUplodedMessage = uploadMsg.duplicate;
	};
	//********** /successMsgShow************************************************

	//**********hide upload Alert Msg*************
	$scope.hideUploadAlertMsg = function () {
		$scope.uplodedMessageHide = true;
		$scope.notUplodedMessageHide = true;
		$scope.notUplodedMessageHide = true;
	};
	//********** /hide upload Alert Msg**********************************************

	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function () {
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg = true;
	};
	//********** /hide Alert Msg**********************************************

	//**********navigate Form*********************************************
	$scope.navigatePage = function () {
		if (!$scope.isEmployeeAddDiv) {
			$scope.isEmployeeAddDiv = true;
			$scope.isEmployeeEditDiv = true;
			$scope.isEmployeeMISEmailDiv = true;
			$scope.isEmployeeListDiv = false;
		} else if (!$scope.isEmployeeEditDiv) {
			$scope.isEmployeeAddDiv = true;
			$scope.isEmployeeEditDiv = false;
			$scope.isEmployeeMISEmailDiv = true;
			$scope.isEmployeeListDiv = false;
		} else if (!$scope.isEmployeeMISEmailDiv) {
			$scope.isEmployeeListDiv = true;
			$scope.isEmployeeEditDiv = true;
			$scope.isEmployeeAddDiv = true;
			$scope.isEmployeeMISEmailDiv = false;
		} else {
			$scope.isEmployeeListDiv = true;
			$scope.isEmployeeEditDiv = true;
			$scope.isEmployeeMISEmailDiv = true;
			$scope.isEmployeeAddDiv = false;
		}
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************

	//**********back Button****************************************************
	$scope.backButton = function () {
		$scope.isAnyRoleFlag = false;
		$scope.IsDepartRoleVisible = false;
		$scope.isEquipmentTypeAddFlag = true;
		$scope.isEquipmentTypeEditFlag = true;
		$scope.isEmployeeListDiv = false;
		$scope.isEmployeeAddDiv = true;
		$scope.isEmployeeEditDiv = true;
		$scope.isEmployeeMISEmailDiv = true;
		$scope.addEmployee = {};
		$scope.erpAddEmployeeForm.$setUntouched();
		$scope.erpAddEmployeeForm.$setPristine();
		$scope.editEmployee = {};
		$scope.erpEditEmployeeForm.$setUntouched();
		$scope.erpEditEmployeeForm.$setPristine();
		$scope.selectedDepartments = {};
		$scope.selectedRoles = {};
		$scope.selectedEquipments = {};
		$scope.hideAlertMsg();
	};
	//**********/back Button***************************************************

	//**********reset Form****************************************************
	$scope.resetForm = function () {
		$scope.IsDepartRoleVisible = false;
		$scope.isEquipmentTypeAddFlag = true;
		$scope.isEquipmentTypeEditFlag = true;
		$scope.addEmployee = {};
		$scope.erpAddEmployeeForm.$setUntouched();
		$scope.erpAddEmployeeForm.$setPristine();
		$scope.editEmployee = {};
		$scope.erpEditEmployeeForm.$setUntouched();
		$scope.erpEditEmployeeForm.$setPristine();
		$scope.selectedDepartments = {};
		$scope.selectedRoles = {};
		$scope.selectedEquipments = {};
	};
	//**********/reset Form***************************************************

	//*********code used for sorting list order by fields*****************
	$scope.predicate = 'name';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//********/code used for sorting list order by fields*****************

	//*****************display Invoicing Needed***********************************
	$scope.IsDepartRoleVisible = false;
	$scope.funShowHideIsDepartRoleDetail = function (nameAttr) {
		var isSalesPersonCount = angular.element('[name="' + nameAttr + '"]:checked').length;
		if (isSalesPersonCount) {
			$scope.IsDepartRoleVisible = true;
		} else {
			$scope.IsDepartRoleVisible = false;
		}
	};
	//*****************/display Invoicing Needed***********************************

	//*****************display Invoicing Needed***********************************
	$scope.isEquipmentTypeAddFlag = $scope.isCustomPermissionFlag = true;
	$scope.funAddShowHideEquipmentTypes = function (roleId) {
		if (angular.element('.addSelectedRoleClass:checked').length) {
			$scope.isEquipmentTypeAddFlag = false;
		} else {
			$scope.isEquipmentTypeAddFlag = true;
		}
		if (angular.element('#add_role_id_' + roleId + ':checked').length) {
			if (roleId == '6') {
				roleId = roleId + 1;
				angular.element("#add_role_id_" + roleId).prop('checked', false);
			} else if (roleId == '7') {
				roleId = roleId - 1;
				angular.element("#add_role_id_" + roleId).prop('checked', false);
			} else if (roleId == '13') {
				if (angular.element('#add_role_id_' + roleId + ':checked').length) {
					$scope.isCustomPermissionFlag = false;
				} else {
					$scope.isCustomPermissionFlag = true;
				}
			}
		}
	};
	//*****************/display Invoicing Needed***********************************

	//*****************display Invoicing Needed***********************************
	$scope.isEquipmentTypeEditFlag = $scope.isCustomPermissionEditFlag = true;
	$scope.funEditShowHideEquipmentTypes = function (roleId) {
		if (angular.element('.editSelectedRoleClass:checked').length) {
			$scope.isEquipmentTypeEditFlag = false;
		} else {
			$scope.isEquipmentTypeEditFlag = true;
		}
		if (angular.element('#edit_role_id_' + roleId + ':checked').length) {
			if (roleId == '6') {
				roleId = roleId + 1;
				angular.element("#edit_role_id_" + roleId).prop('checked', false);
			} else if (roleId == '7') {
				roleId = roleId - 1;
				angular.element("#edit_role_id_" + roleId).prop('checked', false);
			} else if (roleId == '13') {
				if (angular.element('#edit_role_id_' + roleId + ':checked').length) {
					$scope.isCustomPermissionEditFlag = false;
				} else {
					$scope.isCustomPermissionEditFlag = true;
				}
			}
		}
	};
	//*****************/display Invoicing Needed***********************************

	//*****************display equipment dropdown code start*****************/	
	$scope.equipmentTypesList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'equipment-types-list'
	}).success(function (result) {
		if (result.equipmentTypesList) {
			$scope.equipmentTypesList = result.equipmentTypesList;
		}
		$scope.clearConsole();
	});
	//*****************display equipment code dropdown end*****************/

	//*****************display equipment dropdown code start*****************/	
	$scope.departmentList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'departments'
	}).success(function (result) {
		if (result.departmentList) {
			$scope.departmentList = result.departmentList;
		}
		$scope.clearConsole();
	});
	//*****************display equipment code dropdown end*****************/

	//*****************display division code dropdown start*****************/	
	$scope.DivisionsList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'division/get-divisioncodes'
	}).success(function (result) {
		if (result) {
			$scope.DivisionsList = result;
		}
		$scope.clearConsole();
	});
	//*****************display division code dropdown end*****************

	//*****************display equipment dropdown code start**************
	$scope.roleDataList = [];
	$http({
		method: 'POST',
		url: BASE_URL + 'master/employee/get-role-list'
	}).success(function (result) {
		$scope.roleDataList = result.roleDataList;
		$scope.clearConsole();
	});
	//*****************display equipment code dropdown end***************

	//***********Listing of employee*************************************
	$scope.funGetEmployeesHttpRequest = function () {
		$scope.loaderShow();
		var http_para_string = { formData: $(erpEmployeesFilterForm).serialize() };
		$http({
			url: BASE_URL + "master/employees/get-branch-wise-employees",
			method: "POST",
			data: http_para_string,
		}).success(function (result, status, headers, config) {
			$scope.empdata = result.employeeList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};
	$scope.funGetEmployees = function () {
		$scope.funGetEmployeesHttpRequest();
	};

	var tempEmployeeSearchTerm;
	$scope.getEmployeeKeywordSearch = function (keyword) {
		tempEmployeeSearchTerm = keyword;
		$timeout(function () {
			if (keyword == tempEmployeeSearchTerm) {
				$scope.funGetEmployeesHttpRequest();
			}
		}, 800);
	};

	$scope.getEmployeeMultiSearch = function () {
		$timeout(function () {
			$scope.funGetEmployeesHttpRequest();
		}, 800);
	};
	$scope.closeMultisearch = function () {
		$scope.multiSearchTr = true;
		$scope.multisearchBtn = false;
		$scope.refreshMultisearch();
	};
	$scope.refreshMultisearch = function () {
		$scope.searchEmployee = {};
		$scope.erpEmployeesFilterForm.$setUntouched();
		$scope.erpEmployeesFilterForm.$setPristine();
		$timeout(function () {
			$scope.funGetEmployeesHttpRequest();
		}, 800);
	};
	$scope.openMultisearch = function () {
		$scope.multiSearchTr = false;
		$scope.multisearchBtn = true;
	};
	//***********/Listing of employee*************************************

	//***********Adding of employee*************************************
	$scope.funAddNewEmployee = function (divisionId) {

		if (!$scope.erpAddEmployeeForm.$valid) return;
		$scope.loaderMainShow();

		//post all form data to save
		$http({
			url: BASE_URL + "user/add-employee",
			method: "POST",
			headers: { 'Content-Type': 'application/json' },
			data: { formData: $(erpAddEmployeeForm).serialize() }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.backButton();
				$scope.funGetEmployees();
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '400') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//***********/Adding of employee*************************************
	$scope.isEquipmentFlag = true;
	$scope.isAnyRoleFlag = false;
	//***********Editing an employee*******************************
	$scope.funEditEmployee = function (id) {
		if (id) {
			$scope.loaderMainShow();
			$http.post(BASE_URL + "user/edit-employee", {
				data: { "_token": "{{ csrf_token() }}", "user_id": id }
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {

					$scope.resetForm();
					$scope.isEmployeeAddDiv = true;
					$scope.isEmployeeEditDiv = false;
					$scope.isEmployeeListDiv = true;
					$scope.isEmployeeMISEmailDiv = true;
					$scope.editEmployee = result.userData;
					$scope.selectedDepartments = result.deptDetail;
					$scope.selectedRoles = result.rolesDetail;
					$scope.testerRole = result.rolesDetail.indexOf(6);
					$scope.sectionInchargeRole = result.rolesDetail.indexOf(7);
					$scope.selectedEquipments = result.equipDetail;
					$scope.selectedCustomPermissions = result.customPermDetail;
					$scope.editEmployee.password = null;
					$scope.editEmployee.is_sales_person = result.userData.is_sales_person ? true : false;
					$scope.editEmployee.is_sampler_person = result.userData.is_sampler_person ? true : false;
					$scope.isEquipmentTypeEditFlag = result.equipDetail.length ? false : true;
					$scope.isCustomPermissionEditFlag = result.customPermDetail.length ? false : (result.rolesDetail.indexOf(13) > -1 ? false : true);
					$scope.editEmployee.division_id = { selectedOption: { id: result.userData.division_id } };
					$scope.editEmployee.status = { selectedOption: { id: result.userData.status } };
					$scope.isAnyRoleFlag = ($scope.selectedRoles.length > 0) ? true : false;
					if (result.userData.is_sales_person) { $scope.IsDepartRoleVisible = result.userData.is_sales_person ? true : false; }
					if (result.userData.is_sampler_person) { $scope.IsDepartRoleVisible = result.userData.is_sampler_person ? true : false; }
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if (status == '500' || status == '400') {
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			});
		}
	};
	//******************/Editing an employee********************************

	//******************Updating an employee********************************
	$scope.funUpdateEmployee = function (divisionId) {

		if (!$scope.erpEditEmployeeForm.$valid) return;
		$scope.loaderMainShow();

		$http({
			url: BASE_URL + "user/update-employee",
			method: "POST",
			headers: { 'Content-Type': 'application/json' },
			data: { formData: $(erpEditEmployeeForm).serialize() }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.funGetEmployees();
				$scope.funEditEmployee(result.userId);
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '400') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	};
	//******************/Updating an employee******************************

	//*************delete an employee*******************************************
	$scope.deleteEmployee = function (id, division_id) {
		if (id) {
			$scope.loaderShow();
			$http.post(BASE_URL + "user/delete-employee", {
				data: { "_token": "{{ csrf_token() }}", "user_id": id }
			}).success(function (data, status, headers, config) {
				if (data.success) {
					$scope.funGetEmployees();
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
				$scope.loaderHide();
			});
		}
	};
	//************/delete an employee*******************************************

	/***************** EMPLOYEEE SECTION END HERE *****************/

	/***************** EMPLOYEE Upload Section Start Here*****************/
	$scope.showUploadForm = function () {
		$scope.employeesListHeader = '';
		$scope.employeesListData = '';
		angular.element('#employeeCSVForm')[0].reset();
		angular.element('#uploadEmployeePreviewListing').hide();
		angular.element('#uploadEmployeeForm').show();
	};
	$scope.hideUploadForm = function () {
		angular.element('#uploadEmployeePreviewListing').show();
		angular.element('#uploadEmployeeForm').hide();
	};
	$scope.cancelUpload = function () {
		$scope.employeesListHeader = '';
		$scope.employeesListData = '';
		$scope.hideAlertMsg();
		angular.element('#employeeCSVForm')[0].reset();
		angular.element('#uploadEmployeePreviewListing').hide();
		angular.element('#uploadEmployeeForm').show();
	};

	//function is used to upload employee csv in preview table
	$(document).on('click', '#employeeUploadPreviewBtn', function (e) {
		$scope.loaderShow();
		e.preventDefault();
		var formdata = new FormData();
		formdata.append('employee', $('#employeeFile')[0].files[0]);
		$.ajax({
			url: BASE_URL + "employees/upload-preview",
			type: "POST",
			data: formdata,
			contentType: false,
			cache: false,
			processData: false,
			success: function (res) {
				var resData = res.returnData;
				if (resData.success) {
					$scope.employeesListHeader = resData.newheader;
					$scope.employeesListDataDisplay = resData.dataDisplay;
					$scope.numberOfSubmitedRecords = resData.numberOfSubmitedRecords;
					$scope.employeesListDataSubmit = resData.dataSubmit;
					$scope.hideUploadForm();
					$scope.successMsgShow(resData.success);
					$scope.$apply();
					$scope.clearConsole();
					$scope.loaderHide();
				} else if (resData.error) {
					$scope.errorMsgShow(resData.error);
					$scope.$apply();
					$scope.clearConsole();
					$scope.loaderHide();
				}
			}
		});
	});

	//****************employee Upload CSV**************************	
	$scope.employeeUploadCSV = function () {
		$scope.loaderShow();
		// post all form data to save
		$http.post(BASE_URL + "employees/save-uploaded-employees", {
			data: { formData: $scope.employeesListDataSubmit },
		}).success(function (res, status, headers, config) {
			var resData = res.returnData;
			if (resData.success) {
				$scope.funGetEmployees();
				$scope.showUploadForm();
				if (resData.upload) {
					$scope.hideAlertMsg();
					$scope.uploadMsgShow(resData.upload);
				} else {
					$scope.successMsgShow(resData.success);
				}
			} else {
				$scope.errorMsgShow(resData.error);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if (status == '500' || status == '404') {
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//****************/employee Upload CSV*************************	

	//****************Department filter show/hide******************
	$scope.searchDeptFilterBtn = false;
	$scope.searchDeptFilterInput = true;
	$scope.showDeptSearchFilter = function () {
		$scope.searchDeptFilterBtn = true;
		$scope.searchDeptFilterInput = false;
	};
	$scope.hideDeptSearchFilter = function () {
		$scope.searchDeptFilterBtn = false;
		$scope.searchDeptFilterInput = true;
	};
	//****************/Department filter show/hide****************

	//****************equipment filter show/hide******************
	$scope.searchEqFilterBtn = false;
	$scope.searchEqFilterInput = true;
	$scope.showEqSearchFilter = function () {
		$scope.searchEqFilterBtn = true;
		$scope.searchEqFilterInput = false;
	};
	$scope.hideEqSearchFilter = function () {
		$scope.searchEqFilterBtn = false;
		$scope.searchEqFilterInput = true;
	};
	//****************/equipment filter show/hide******************

	//****************Array To String******************************
	$scope.funArrayToString = function (data, name) {
		$scope.employeeTitle = _.map(data, name).join(', ');
	};
	//***************/Array To String******************************

	//**************** Uploading of signature  Image ***************************
	$(document).on('change', '.uploadSignImage', function (e) {

		e.preventDefault();
		var userId = this.id;
		var _this = $(this);
		var data = new FormData();
		data.append('user_signature', _this[0].files[0]);
		data.append('user_id', userId);

		$.ajax({
			url: BASE_URL + "master/employee/upload-signature-image",
			type: "POST",
			data: data,
			contentType: false,
			cache: false,
			processData: false,
			success: function (result) {
				if (result.error == 1) {
					$scope.funGetEmployeesHttpRequest();
					$scope.successMsgShow(result.message);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
			}
		});
	});
	//**************** /Uploading of item Image **********************************
	//**********confirm box******************************************************
	$scope.showConfirmMessage = function (msg) {
		if (confirm(msg)) {
			return true;
		} else {
			return false;
		}
	}
	//********** /confirm box****************************************************
	//**************** Deleting of item *************************************
	$scope.funRemoveItemImage = function (user_id) {

		if (!$scope.showConfirmMessage('Are you sure you want to remove this signature Image?')) return;
		$scope.hideAlertMsg();

		if (user_id) {
			$http({
				url: BASE_URL + "master/employee/delete-signature-image/" + user_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.funGetEmployeesHttpRequest();
					$scope.successMsgShow(result.message);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if (status == '500' || status == '404') {
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}
	}
	//************** /Deleting of item *************************************
	/***************** employee Upload SEction SECTION END HERE *****************/

}).directive('confirmPwd', function ($interpolate, $parse) {
	return {
		require: 'ngModel',
		link: function (scope, elem, attr, ngModelCtrl) {
			var pwdToMatch = $parse(attr.confirmPwd);
			var pwdFn = $interpolate(attr.confirmPwd)(scope);
			scope.$watch(pwdFn, function (newVal) {
				ngModelCtrl.$setValidity('password', ngModelCtrl.$viewValue == newVal);
			});
			ngModelCtrl.$validators.password = function (modelValue, viewValue) {
				var value = modelValue || viewValue;
				return value == pwdToMatch(scope);
			};
		}
	}
});