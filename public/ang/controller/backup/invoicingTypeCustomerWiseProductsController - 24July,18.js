app.controller('invoicingTypeCustomerWiseProductsController', function($scope, $http, BASE_URL,$ngConfirm) {

	$scope.currentModule = 15;                      //variable used in tree.js for tree popup

	//define empty variables
	$scope.customerWiseProductRateList 	= '';
	$scope.successMessage 			= '';
	$scope.errorMessage   			= '';
	$scope.cirCustomerID       		= '0';
	$scope.cirCustomerCityID       		= '0';
	$scope.for_fixed_rate       		= false;
	$scope.defaultMsg  				= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.addCustomerWiseProductRate 	= {};
	$scope.editCustomerWiseProductRate 	= {};

	//sorting variables
	$scope.sortType     			= 'cir_id';    // set the default sort type
	$scope.sortReverse  			= false;         // set the default sort order
	$scope.searchFish   			= '';    		 // set the default search/filter term
	$scope.hideSaveBtn    			= true;
	$scope.hideResetBtn   			= true;
	$scope.hideUpdateBtn  			= false;
	$scope.hideCancelBtn  			= false;
	$scope.parentCategoryList 		= [];
	$scope.customerEditListData  		= [];
	$scope.customerWiseProductRate 		= '';
	$scope.addCWiseProductRate		= {};
	//**********scroll to top function******************************************
	$scope.moveToMsg=function(){
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	};
	//**** 12-04-18
	$scope.backOnList= function(){
		$scope.listCustomerWiseProductRateDiv 	 = false;
		$scope.addCustomerWiseProductRateDiv 	 = true;
		$scope.editCustomerWiseProductRateDiv 	 = true;
		$scope.editAllCustomerWiseProductRateDiv 	 = false;
	}
	//**********/scroll to top function******************************************

	//**********loader show****************************************************
	$scope.loaderShow = function(){
		angular.element('#global_loader').fadeIn();
	};
	//**********/loader show**************************************************

	//**********loader show***************************************************
	$scope.loaderHide = function(){
		angular.element('#global_loader').fadeOut();
	};
	//**********/loader show**************************************************

	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg 	     	 = true;
	$scope.IsVisiableErrorMsg 	 	     	 = true;
	$scope.IsVisiableSuccessMsgPopup     	 = true;
	$scope.IsVisiableErrorMsgPopup 	     	 = true;
	$scope.listCustomerWiseProductRateDiv 	 = false;
	$scope.addCustomerWiseProductRateDiv 	 = true;
	$scope.editCustomerWiseProductRateDiv 	 = true;
	//**********/If DIV is hidden it will be visible and vice versa************

	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 		= message;
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg 	= true;
		$scope.moveToMsg();
	};
	//********** /successMsgShow************************************************

	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 		= message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= false;
		$scope.moveToMsg();
	};
	//********** /errorMsgShow************************************************

	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	};
	//********** /hide Alert Msg**********************************************

	//**********successMsgShowPopup**************************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		= message;
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	= true;
		$scope.moveToMsg();
	};
	//********** /successMsgShowPopup************************************************

	//**********errorMsgShowPopup**************************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	};
	//********** /hideAlertMsgPopup************************************************

	//**********hideAlertMsgPopup*************
	$scope.hideAlertMsgPopup = function(){
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
	};
	//********** /hideAlertMsgPopup**********************************************

	//**********confirm box******************************************************
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	};
	//********** /confirm box****************************************************

	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id,cir_customer_id,cir_department_id,form_type){
		$ngConfirm({
			title     : false,
			content   : defaultDeleteMsg, //Defined in message.js and included in head
			animation : 'right',
			closeIcon : true,
			closeIconClass: 'fa fa-close',
			backgroundDismiss: false,
			theme   : 'bootstrap',
			buttons   : {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.funDeleteCustomerWiseProductRate(id,cir_customer_id,cir_department_id,form_type);
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'
				}
			}
		});
	};
	//********** /confirm box****************************************************
	/*****************display division dropdown start 12April,2018*****************/
	$scope.divisionsCodeList = [];
		$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			$scope.divisionsCodeList = result;
			$scope.defaultDivisionId = {
				selectedOption:{id:	$scope.divisionsCodeList[0].id }
			};
			$scope.clearConsole();
		});
	/*****************display division dropdown end*****************/
	//*****************state/city tree list data*****************
	$scope.stateCityTreeViewList = [];
	$scope.funGetStateCityTreeViewList = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'get-state-city-tree-view'
		}).success(function (result) {
			if(result.stateCityTreeViewList){
				$scope.stateCityTreeViewList = result.stateCityTreeViewList;
			}
			$scope.clearConsole();
		});
	}
	//*****************/state/city tree list data*****************

	//**********state/city tree popup***************************************************
	$scope.funShowStateCityTreeViewPopup = function(currentModule){
	   $scope.currentModule=currentModule;
	   $('#stateCityTreeViewPopup').modal('show');
	}
	//**********/state/city tree popup***************************************************

	//*******************filter state/city from tree view****************
	$scope.funGetSelectedStateId = function(selectedNode){
		$scope.funGetCustomerOnStateChange(selectedNode.state_id);
		$('#stateCityTreeViewPopup').modal('hide');
		$scope.currentModule=15;
	}
	//*****************/filter state/city from tree view******************

	//*****************display parent category dropdown code dropdown start 28-02-2017****
	var globalDepartID ;
	$scope.funGetParentCategory = function(){
		$scope.customerWiseProductRate = [];
		$http({
			method: 'POST',
			url: BASE_URL +'master/get-parent-product-category'
		}).success(function (result) {
			$scope.parentCategoryList = result.parentCategoryList;
			$scope.cirDepartmentID = $scope.parentCategoryList[0].id;
			$scope.customerWiseProductRate.cir_product_category_id = {selectedOption:{id:$scope.parentCategoryList[0].id }};
		});
	};
	//**********navigate Form*********************************************
$scope.navigateFormPage = function(type){
	if(type=='add'){
		$scope.addCustomerWiseProductRateDiv=false;
		$scope.editAllCustomerWiseProductRateDiv 	= false;
		$scope.listCustomerWiseProductRateDiv  = true;
		$scope.productAliasRateList =[];
	}else if(type=='list'){
		$scope.addCustomerWiseProductRateDiv=true;
		$scope.editAllCustomerWiseProductRateDiv 	= false;
		$scope.listCustomerWiseProductRateDiv  = false;
		$scope.editCustomerWiseProductRateDiv=true;
		$scope.productAliasRateList =[];
		}
}
	//**********navigate Form*********************************************


	//**********Back Button*********************************************
	$scope.backButton = function(){
		$scope.for_fixed_rate  = false;
		$scope.addCustomerWiseProductRate = {};
		$scope.erpAddCustomerWiseProductRateForm.$setUntouched();
		$scope.erpAddCustomerWiseProductRateForm.$setPristine();
		$scope.editCustomerWiseProductRate = {};
		$scope.erpEditCustomerWiseProductRateForm.$setUntouched();
		$scope.erpEditCustomerWiseProductRateForm.$setPristine();
		$scope.navigateFormPage();
	};
	//**********/Back Button********************************************

	//**********Reset Button*********************************************
	$scope.resetButton = function(){
		$scope.addCustomerWiseProductRate = {};
		$scope.erpAddCustomerWiseProductRateForm.$setUntouched();
		$scope.erpAddCustomerWiseProductRateForm.$setPristine();
		$scope.editCustomerWiseProductRate = {};

	};
	//**********/Reset Button*********************************************

	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	};
	//*********/Clearing Console*********************************************

	//************code used for sorting list order by fields*****************
	$scope.predicate = 'cir_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************

	//*****************display customer list dropdown******************************
	$scope.productAliasRateList = [];
	$scope.getProductAliasRateList = function(product_cat_id){
		$http({
			method: 'GET',
			url: BASE_URL +'product-master-alias-list/'+product_cat_id,
		}).success(function (result) {
			$scope.productAliasRateList = result.productAliasRateList;
			$scope.clearConsole();
		});
	}
	//*****************/display customer list code dropdown************************

	//*****************city dropdown on state change*******************************
	$scope.funGetCustomerOnStateChange = function(state_id){
		$http({
			method: 'GET',
			url: BASE_URL +'master/invoicing/customer-wise-product-rates/get_customer_list/'+state_id
		}).success(function (result) {
			$scope.customerListData = result.customerListData;
			$scope.clearConsole();
		});
	};
	//****************/city dropdown on state change*******************************

	//*****************display customer list dropdown******************************
	$scope.funGetCustomersCity = function(city_id,city_name,type){
		if(city_id){
			$scope.stateCitiesList = [{'id': city_id, 'name' : city_name}];
			if(type == 'edit'){
				$scope.editCustomerWiseProductRate.cir_city_id  = {
					selectedOption: { 'id': city_id, 'name' : city_name }
				};
			}else{
				$scope.addCustomerWiseProductRate.cir_city_id  = {
					selectedOption: { 'id': city_id, 'name' : city_name }
				};
			}
		}
	};
	//*****************/display customer list code dropdown*********************

	//*****************display customer list dropdown******************************
	$scope.customerProductsList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'master/invoicing/customer-wise-product-rates/product-list'
	}).success(function (result) {
		$scope.customerProductsList = result.customerProductsList;
		$scope.clearConsole();
	});
	//*****************/display customer list code dropdown************************

	//**********Getting all Payment Made*******************************************
	$scope.funGetCustomerWiseProductRates = function(cirCustomerID,cirDepartmentID,cirDivisionID,type){

		$scope.cirCustomerID   = cirCustomerID;
		$scope.cirDepartmentID = !cirDepartmentID ? 1 : cirDepartmentID ;
		$scope.cirDivisionID = !cirDivisionID ? 1 : cirDivisionID ;
		//$scope.filterLeftSideCustomerDataList ='';

		$scope.loaderShow();
		var http_para_string = {formData : 'cir_customer_id='+$scope.cirCustomerID+'&'+'dept_id='+$scope.cirDepartmentID+'&'+'division_id='+$scope.cirDivisionID};

		$http({
			url: BASE_URL + "master/invoicing/customer-wise-product-rates/get-customer-wise-product-rates",
			method: "POST",
			data: http_para_string,
		}).success(function(result, status, headers, config){
			$scope.customerWiseProductRateList = result.returnData;
			$scope.customerWiseProductRateListCount = type=='modify' ? $scope.customerWiseProductRateList.length : result.customerWiseProductRateListCount;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**********/Getting all Payment Made***************************************************

	//***************** Adding of Branch Wise DebitNote **********************
	$scope.funAddCustomerWiseProductRate = function(cirCustomerID,prodct_cat_id,type){

		$scope.prodctCategoryId = prodct_cat_id;
		$scope.selectedFormType = type;
		if(!$scope.erpAddCustomerWiseProductRateForm.$valid)return;
		if($scope.newAddCustomerWiseProductRateFormflag)return;
		$scope.newAddCustomerWiseProductRateFormflag = true;
		var formData = $(erpAddCustomerWiseProductRateForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "master/invoicing/customer-wise-product-rates/add-customer-wise-product-rates",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.newAddCustomerWiseProductRateFormflag = false;
			if(data.error == 1){
				if(type == 'add')
				{
					$scope.addCWiseProductRate={};
				}
				//$scope.funEditSelectedCustomerProductRate($scope.cirCustomerID,$scope.cirDepartmentID,type);
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newAddCustomerWiseProductRateFormflag = false;
			$scope.loaderHide();
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//****************/Adding of Branch Wise DebitNote ***************************

	//****************editing of Payment Made *************************************
	$scope.funEditCustomerWiseProductRate = function(cirId){
		if(cirId){
			$scope.for_fixed_rate  = false;
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/invoicing/customer-wise-product-rates/view-customer-wise-product-rates/"+cirId,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
					$scope.listCustomerWiseProductRateDiv  = true;
					$scope.addCustomerWiseProductRateDiv   = true;
					$scope.editCustomerWiseProductRateDiv  = false;

					$scope.editCustomerWiseProductRate = result.customerWiseProductRateData;
					$scope.customerEditListData        = [{id: result.customerWiseProductRateData.cir_customer_id, name:result.customerWiseProductRateData.customer_name}];
					$scope.funGetCustomersCity($scope.editCustomerWiseProductRate.city_id,$scope.editCustomerWiseProductRate.city_name,'edit');
					if(result.customerWiseProductRateData.cir_division_id){
						$scope.editCustomerWiseProductRate.cir_division_id = {
							selectedOption: { id: result.customerWiseProductRateData.cir_division_id }
						};
					}
					if(result.customerWiseProductRateData.cir_customer_id){
						$scope.editCustomerWiseProductRate.cir_customer_id = {
							selectedOption: { id: result.customerWiseProductRateData.cir_customer_id }
						};
					}
					if(result.customerWiseProductRateData.cir_product_category_id){
						$scope.editCustomerWiseProductRate.cir_product_category_id = {
							selectedOption: { id: result.customerWiseProductRateData.cir_product_category_id }
						};
					}
					if(result.customerWiseProductRateData.cir_c_product_id){
						$scope.editCustomerWiseProductRate.cir_c_product_id  = {
							selectedOption: { id: result.customerWiseProductRateData.cir_c_product_id}
						};
					}else{
						$scope.for_fixed_rate  = true;
					}
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config){
				$scope.loaderHide();
				if(status == '500' || status == '404'){
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************/editing of Payment Made *************************************

	//*****************get Customer List which contains products***************************
	$scope.funGetProductWiseCustomersList = function(deptId){
		$http({
			method: 'POST',
			url: BASE_URL +'master/invoicing/customer-wise-product-rates/product-alias-customers-list',
			data:{dept_id:deptId}
		}).success(function (result){
			if(result){
				$scope.productCustomersList = result.productCustomersList;
				if($scope.productCustomersList.length){
					$scope.cirCustomerID = $scope.productCustomersList[0].customer_id;
				}else{
					$scope.cirCustomerID = '0';
				}
				$scope.funGetCustomerWiseProductRates($scope.cirCustomerID,deptId,$scope.cirDivisionID);
			}
			$scope.clearConsole();
		});
	};
	//*****************get Customer List which contains products*****************************
$scope.editAllCustomerWiseProductRateDiv = false;

/****12 april,2018**/
//****************edit all  Products of selected state *************************************
$scope.funEditSelectedCustomerAllProductRate = function(cir_customer_id,cir_department_id,cirDivisionID){
	$scope.addCustomerWiseProductRateDiv=true;
	$scope.listCustomerWiseProductRateDiv  = true;
	$scope.editAllCustomerWiseProductRateDiv= true;
	if(cir_customer_id){
		$scope.for_fixed_rate  = false;
		$scope.cirCustomerID   = cir_customer_id;
		$scope.cirDepartmentID = cir_department_id;
		var cirDeptID 	       =  cir_department_id;
		$scope.loaderShow();
		$scope.hideAlertMsg();
		$http({
			url: BASE_URL + "master/invoicing/customer-wise-product-rates/get-selected-customer-product-rates/"+cir_customer_id+"/"+cirDeptID+"/"+cirDivisionID,
			method: "GET",
		}).success(function(result, status, headers, config){
			if(result.error == 1){
				$scope.productAliasRateList = result.productAliasRateList;
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	}
};
//**************** Updating of all products rate Made *************************************
$scope.funUpdateCustomerWiseAllProductRate = function(){
	var formData = $(erpEditCustomerWiseAllProductRateForm).serialize();
	$scope.loaderShow();

	$http({
		url: BASE_URL + "master/invoicing/customer-wise-product-rates/update-customer-wise-product-rates",
		method: "POST",
		data: {formData :formData}
	}).success(function (result, status, headers, config) {
		if(result.error == 1){
			$scope.funEditCustomerWiseProductRate(result.cir_id);
			$scope.successMsgShow(result.message);
		}else{
			$scope.errorMsgShow(result.message);
		}
		$scope.loaderHide();
		$scope.clearConsole();
	}).error(function (data, status, headers, config){
		$scope.loaderHide();
		if(status == '500' || status == '404'){
			$scope.errorMsgShowPopup($scope.defaultMsg);
		}
	});
};
//*************** /Updating of all products rate Made *************************************
//**************** Updating of fixed rate product *************************************
	$scope.funUpdateCustomerWiseFixedProductRate = function(cirId){

		if(!$scope.erpEditCustomerWiseProductRateForm.$valid) return;
		var formData = $(erpEditCustomerWiseProductRateForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "master/invoicing/customer-wise-product-rates/update-customer-wise-fixed-product-rates",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) {
			if(result.error == 1){
				$scope.funEditCustomerWiseProductRate(result.cir_id);
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//*************** /Updating of fixed rate product *************************************

	//**************** Deleting of Branch Wise Payment Made ***************************
	$scope.funDeleteCustomerWiseProductRate = function(id,cir_customer_id,cir_department_id,form_type){
		if(id){
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/invoicing/customer-wise-product-rates/delete-customer-wise-product-rates/"+id,
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.funGetCustomerWiseProductRates(cir_customer_id,cir_department_id,form_type);
					$scope.successMsgShow(result.message);
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config){
				$scope.loaderHide();
				if(status == '500' || status == '404'){
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//************** /Deleting of Branch Wise Payment Made *******************************
	//*** refreshList
		$scope.funrefreshCustomerWiseProductRates = function(cirCustomerID,cirDepartmentID,cirDivisionID,type){
			$scope.funGetCustomerWiseProductRates(cirCustomerID,cirDepartmentID,cirDivisionID,type);
			$scope.customerWiseProductRate.cir_product_category_id.selectedOption.id = cirDepartmentID;
			$scope.defaultDivisionId.selectedOption.id = cirDivisionID;
		}
});
