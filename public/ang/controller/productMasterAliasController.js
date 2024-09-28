app.controller('productMasterAliasController', function ($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {

	//define empty variables
	$scope.currentModule = 3;
	$scope.successMessage = '';
	$scope.errorMessage = '';
	$scope.selected = '';
	$scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType = 'c_product_id';    	// set the default sort type
	$scope.sortReverse = false;         	// set the default sort order
	$scope.searchFish = '';    		// set the default search/filter term

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

	//**********loader show***************************************************
	$scope.loaderHide = function () {
		angular.element('#global_loader').fadeOut();
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

	//********** key Press Handler**********************************************
	$scope.funEnterPressHandler = function (e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			e.stopPropagation();
		}
	}
	//**********/key Press Handler**********************************************

	//define default Initialization of variables
	$scope.customerProductAliasList = [];
	$scope.productMasterAliasFilter = [];
	$scope.editProductMasterAlias = {};
	$scope.selectedProductID = '0';

	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg = true;
	$scope.IsVisiableErrorMsg = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup = true;
	$scope.listCustomerProductFormBladeDiv = false;
	$scope.addCustomerProductFormBladeDiv = true;
	$scope.editCustomerProductFormBladeDiv = true;
	$scope.showAddMoreOption = false;
	$scope.ifAddMoreClickedOnAddEdit = false;
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

	//**********confirm box******************************************************
	$scope.showConfirmMessage = function (msg) {
		if (confirm(msg)) {
			return true;
		} else {
			return false;
		}
	};
	//**********/confirm box****************************************************

	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function (c_product_id, product_id, type) {
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
					action: function () {
						$scope.funDeleteCustomerProductMaster(c_product_id, product_id, type);
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

	//**********navigate Form*********************************************
	$scope.navigateCustomerProductForms = function () {
		if (!$scope.addCustomerProductFormBladeDiv) {
			$scope.addCustomerProductFormBladeDiv = true;
			$scope.editCustomerProductFormBladeDiv = true;
			$scope.listCustomerProductFormBladeDiv = false;
		} else if (!$scope.editCustomerProductFormBladeDiv) {
			$scope.addCustomerProductFormBladeDiv = true;
			$scope.editCustomerProductFormBladeDiv = true;
			$scope.listCustomerProductFormBladeDiv = false;
		} else {
			$scope.listCustomerProductFormBladeDiv = true;
			$scope.editCustomerProductFormBladeDiv = true;
			$scope.addCustomerProductFormBladeDiv = false;
		}
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************

	//**********Back Button*********************************************
	$scope.backButton = function () {
		$scope.addCustomerProduct = {};
		$scope.customerProduct = {};
		$scope.ifAddMoreClickedOnAddEdit = false
		$scope.erpAddCustomerProductForm.$setUntouched();
		$scope.erpAddCustomerProductForm.$setPristine();
		$scope.editCustomerProduct = {};
		$scope.erpEditCustomerProductForm.$setUntouched();
		$scope.erpEditCustomerProductForm.$setPristine();
		$scope.navigateCustomerProductForms();
	};
	//**********/Back Button********************************************

	//**********Reset Button*********************************************
	$scope.resetButton = function () {
		$scope.addCustomerProduct = {};
		$scope.erpAddCustomerProductForm.$setUntouched();
		$scope.erpAddCustomerProductForm.$setPristine();
		$scope.editCustomerProduct = {};
		$scope.erpEditCustomerProductForm.$setUntouched();
		$scope.erpEditCustomerProductForm.$setPristine();
	};
	//**********/Reset Button*********************************************

	//**********Clearing Console*********************************************
	$scope.clearConsole = function () {
		//console.clear();
	};
	//*********/Clearing Console*********************************************

	//************code used for sorting list order by fields*****************
	$scope.predicate = 'c_product_id';
	$scope.reverse = true;
	$scope.sortBy = function (predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************

	//*****************display parent category dropdown code dropdown*********
	$scope.customerWiseProductRate = [];
	$scope.funGetParentCategory = function () {
		$http({
			method: 'POST',
			url: BASE_URL + 'master/get-parent-product-category'
		}).success(function (result) {
			$scope.parentCategoryList = result.parentCategoryList;
			$scope.cirDepartmentID = $scope.parentCategoryList[0].id;
			$scope.productMasterAliasFilter.product_category_id = { selectedOption: { id: $scope.parentCategoryList[0].id } };
		});
	};
	//*****************display parent category dropdown code dropdown*********

	/***** listing of all Product Master Alias*****************/
	$scope.funGetCustomerProductsList = function (selected_product_id = null) {
		$scope.loaderMainShow();
		$http({
			url: BASE_URL + "master/invoicing/customer-product-master/get-products-list",
			method: "POST",
			data: { formData: 'product_id=' + selected_product_id + '&' + $(erpProductAliasFilterForm).serialize() },
		}).success(function (result, status, headers, config) {
			$scope.customerProductAliasList = result.getProductList;
			$scope.selectedProductID = result.product_id;
			$scope.customerProductsAliasCount = result.customerProductsCount;
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.loaderHide();
			$scope.loaderMainHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	var tempProductAliasSearchTerm;
	$scope.funSearchProductAliasTerm = function (keyword) {
		tempProductAliasSearchTerm = keyword;
		$timeout(function () {
			if (keyword == tempProductAliasSearchTerm) {
				$scope.funGetCustomerProductsList();
			}
		}, 800);
	};
	$scope.funGetProductListing = function (product_id) {
		$scope.loaderMainShow();
		$http({
			url: BASE_URL + "master/invoicing/customer-product-master/get-products-list/" + product_id,
			method: "POST",
		}).success(function (result, status, headers, config) {
			$scope.customerProductAliasList.rightSideDataList = result.customerProductsList;
			$scope.selectedProductID = product_id;
			$scope.loaderMainHide();
			$scope.clearConsole();
		}).error(function (result, status, headers, config) {
			$scope.loaderMainHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	/***** /listing of all Product Master Alias*****************/

	//*****************Adding of Customer Product Master**********************
	$scope.funAddCustomerProducts = function () {

		if (!$scope.erpAddCustomerProductForm.$valid) return;
		if ($scope.funAddCustomerProductsFormflag) return;
		$scope.funAddCustomerProductsFormflag = true;
		var formData = $(erpAddCustomerProductForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "master/invoicing/customer-product-master/add-customer-product",
			method: "POST",
			data: { formData: formData }
		}).success(function (data, status, headers, config) {
			$scope.funAddCustomerProductsFormflag = false;
			if (data.error == 1) {
				$scope.addCustomerProduct = '';
				$scope.ifAddMoreClickedOnAddEdit = false;
				$scope.successMsgShow(data.message);
			} else {
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.funAddCustomerProductsFormflag = false;
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//****************/Adding of Customer Product Master ***************************

	//****************Editing of Customer Product Master*************************************
	$scope.funEditCustomerProductMaster = function (c_product_id) {
		if (c_product_id) {

			$scope.loaderMainShow();
			$scope.hideAlertMsg();
			$scope.showAddMoreOption = false;
			$scope.editAllCustomerProductAliases = false;

			$http({
				url: BASE_URL + "master/invoicing/customer-product-master/edit-customer-product/" + c_product_id,
				method: "GET",
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.resetButton();
					$scope.listCustomerProductFormBladeDiv = true;
					$scope.addCustomerProductFormBladeDiv = true;
					$scope.editCustomerProductFormBladeDiv = false;
					$scope.productsListData = [{ id: result.productMasterData.product_id, name: result.productMasterData.product_name }];
					$scope.editCustomerProduct = result.customerProductMasterData;
					$timeout(function () {
						$scope.editProductMasterAlias.product_id = {selectedOption: { id: result.customerProductMasterData.product_id }};
					}, 100);					
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderMainHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				$scope.loaderMainHide();
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************/Editing of Customer Product Master*************************************

	//****************Editing of Customer Product Master 07-08-2017*************************************
	$scope.funEditAllCustomerProductMaster = function (c_product_id, product_id) {
		if (c_product_id) {
			$scope.customer_product_alias = [];
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$scope.showAddMoreOption = true;
			$http({
				url: BASE_URL + "master/invoicing/customer-product-master/edit-all-customer-product-aliases",
				method: "POST",
				data: { c_product_id: c_product_id, product_id: product_id }
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					$scope.resetButton();
					$scope.listCustomerProductFormBladeDiv = true;
					$scope.addCustomerProductFormBladeDiv = true;
					$scope.editCustomerProductFormBladeDiv = false;
					$scope.productsListData = [{ id: result.productMasterData.product_id, name: result.productMasterData.product_name }];
					$scope.editAllCustomerProductAliases = result.customerProductMasterData;
					$scope.editProductMasterAlias.c_product_id = c_product_id;
					$timeout(function () {
						$scope.editProductMasterAlias.product_id = {selectedOption: { id: result.productMasterData.product_id }};
					}, 100);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				$scope.loaderHide();
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************Updating of Customer Product Master************************************
	$scope.funUpdateCustomerProductMaster = function () {
		var formData = $(erpEditCustomerProductForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/invoicing/customer-product-master/update-customer-product",
			method: "POST",
			data: { formData: formData }
		}).success(function (result, status, headers, config) {
			if (result.error == 1) {
				$scope.backButton();
				$scope.funGetProductListing(result.product_id);
				$scope.successMsgShow(result.message);
			} else {
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.loaderHide();
			if (status == '500' || status == '404') {
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//***************/Updating of Customer Product Master*************************************

	//**************** Deleting of Adding of Customer Product Master**************************
	$scope.funDeleteCustomerProductMaster = function (c_product_id, productId, type) {
		if (c_product_id) {
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/invoicing/customer-product-master/delete-customer-product/" + c_product_id,
			}).success(function (result, status, headers, config) {
				if (result.error == 1) {
					if (type == 'deleteEditRow') {
						$scope.funEditAllCustomerProductMaster(c_product_id, productId);
					}
					$scope.funGetProductListing(productId);
					$scope.successMsgShow(result.message);
				} else {
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				$scope.loaderHide();
				if (status == '500' || status == '404') {
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//************** /Deleting of Adding of Customer Product Master *******************************

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
	}
	//**********/show tree pop up********************************************/

	//*****************get product category id from tree view*****************/
	$scope.getProductsAndStandards = function (node) {
		$scope.funGetProductList(node.p_category_id);
		$('#productCategoryPopup').modal('hide');
	}
	//*****************/get product category id from tree view*****************/

	//*****************display products list dropdown******************************
	$scope.productsListData = [];
	$scope.funGetProductList = function (p_parent_id) {
		$scope.loaderMainShow();
		$http({
			method: 'GET',
			url: BASE_URL + 'master/products-list/' + p_parent_id
		}).success(function (result) {
			$scope.productsListData = result.productsList;
			$scope.loaderMainHide();
			$scope.clearConsole();
		});
	}
	//*****************/display products list dropdown*********************

	/*****************add more row 07-8-2017 *****************/
	$scope.customer_product_alias = [];
	$scope.addRow = function (len = 1) {
		$scope.ifAddMoreClickedOnAddEdit = true;
		var mutipleRows = $('#mutipleRows').val();
		if (mutipleRows) { len = mutipleRows; }
		for (j = 1; j <= len; j++) {
			var newData = {
				level: $scope.level,
				name: $scope.name,
				remark: $scope.remark
			};
			$scope.customer_product_alias.push(newData);
		}
	};
	$scope.deleteRow = function (rowNo, c_product_id = null, productId = null, type = null) {

		if (rowNo >= 0) {
			if (c_product_id) {
				var deleteConfirm = confirm("Are you sure you want to delete this record permanently!");
				if (deleteConfirm == true) {
					$scope.funDeleteCustomerProductMaster(c_product_id, productId, type);
					$scope.customer_product_alias.splice(rowNo, 1);
				} else {
					return false;
				}
			} else {
				$scope.customer_product_alias.splice(rowNo, 1);
			}
		}
	};

	//**********/Getting all Payment Made*************************************

	//*********Loading Data************************
	$timeout(function () {
		$scope.getProductCategories(); 
	}, 10);
	$timeout(function () {
		$scope.funGetCustomerProductsList();
	}, 1000);
	//*********/Loading Data************************

});