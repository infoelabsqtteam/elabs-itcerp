app.controller('invoicingTypeStateWiseProductsController', function($scope, $http, BASE_URL,$ngConfirm) {
	//define empty variables
	$scope.stateWiseProductRateList = '';
	$scope.successMessage 			= '';
	$scope.errorMessage   			= '';
	$scope.cirStateID       		= '0';
	$scope.defaultMsg  				= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.formTitle = "Add State Wise Product";
	//sorting variables
	$scope.sortType     			= 'cir_id';    // set the default sort type
	$scope.sortReverse  			= false;         // set the default sort order
	$scope.searchFish   			= '';    		 // set the default search/filter term
	$scope.hideSaveBtn    = true;
	$scope.hideResetBtn   = true;
	$scope.hideUpdateBtn  = false;
	$scope.hideCancelBtn  = false;
	$scope.disableDeptOnEdit =true;
	$scope.addSWiseProductRate ={};
	//$scope.parentCategoryList = [];
	//**********scroll to top function******************************************
	$scope.moveToMsg=function(){
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	};
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
	$scope.IsVisiableSuccessMsg 	     = true;
	$scope.IsVisiableErrorMsg 	 	     = true;
	$scope.IsVisiableSuccessMsgPopup     = true;
	$scope.IsVisiableErrorMsgPopup 	     = true;
	$scope.listStateWiseProductRateDiv 	 = false;
	$scope.addStateWiseProductRateDiv 	 = true;
	$scope.editStateWiseProductRateDiv 	 = true;
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
	$scope.funConfirmDeleteMessage = function(id,cirStateId,type){
		$ngConfirm({
			title     : false,
			content   : defaultDeleteMsg,					 //Defined in message.js and included in head
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
						if(type == 'stateWiseProductRate'){
							$scope.funDeleteStateWiseProductRate(id,cirStateId);
						}else if(type == 'customerWiseProductRate'){
							$scope.funDeleteCustomerWiseProductRate(id,cirStateId);
						}
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

	$scope.funSetProductName=function(productName){
		$scope.editStateWiseProductRate.product_name = productName;
	}
	//*****************display parent category dropdown code dropdown start*** 27/02/18*
	$scope.funGetParentCategory = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'master/get-parent-product-category'
		}).success(function (result) {
			$scope.parentCategoryList = result.parentCategoryList;
			$scope.dept_id =$scope.parentCategoryList[0].id;
			$scope.deptID = {
				selectedOption:{id:$scope.parentCategoryList[0].id }
			};
		});
	};
	//*****************display parent category code dropdown end*****************
	/*****************display division dropdown start 11April,2018*****************/
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
	$scope.funGetStateAccToDept=function(dept_id,division_id){
		$scope.funGetProductWiseStatesList(dept_id,division_id);
	}
	$scope.addForm = true;

	//**********navigate Form*********27/02/18************************************
	$scope.navigateFormPage = function(cirStateID,cirID,divisionID,type){
		$scope.prod_cat_id = cirID;
		$scope.formType = type;
	    if(type == 'add'){
			$scope.disableDeptOnEdit =true;
			$scope.addForm = true;
			$scope.addStateWiseProductRate=[];

			//$scope.addStateWiseProductRate = '';
			$scope.addStateWiseProductRate.cir_product_category_id = '';
			$scope.hideSaveBtn   = true;
			$scope.hideResetBtn   = true;
			$scope.hideUpdateBtn  = false;
			$scope.hideCancelBtn  = false;
			$scope.formTitle = "Add State Wise Product";
			$scope.productAliasRateList = [];
			$scope.formType = type;
			//$scope.getProductAliasRateList();
			$scope.funGetProductWiseStatesList($scope.deptID.selectedOption.id,$scope.defaultDivisionId.selectedOption.id);

		}else if(type == 'modify'){
			$scope.addForm = false;
			$scope.formType = type;
			$scope.hideSaveBtn   = false;
			$scope.hideResetBtn   = false;
			$scope.hideUpdateBtn  = true;
			$scope.hideCancelBtn  = true;
		    $scope.formTitle = "Edit State Wise Product";
			$scope.cir_product_category_id = cirID;
			$scope.funEditSelectedStateProductRate(cirStateID,$scope.deptID.selectedOption.id,divisionID,type);
		}
		if(!$scope.addStateWiseProductRateDiv){
			$scope.addStateWiseProductRateDiv 	= true;
            $scope.editStateWiseProductRateDiv 	= true;
            $scope.listStateWiseProductRateDiv  = false;
			$scope.funGetStateWiseProductRates(cirStateID,$scope.deptID.selectedOption.id,$scope.defaultDivisionId.selectedOption.id,type);
		}else if(!$scope.editStateWiseProductRateDiv){
            $scope.addStateWiseProductRateDiv 	= true;
            $scope.editStateWiseProductRateDiv 	= true;
            $scope.listStateWiseProductRateDiv  = false;
        }else{
            $scope.listStateWiseProductRateDiv  = true;
            $scope.editStateWiseProductRateDiv 	= true;
            $scope.addStateWiseProductRateDiv 	= false;
			$scope.resetButton();
		}
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************

	//**********Back Button*********************************************
	$scope.backButton = function(){
		$scope.addStateWiseProductRate = {};
		$scope.erpAddStateWiseProductRateForm.$setUntouched();
		$scope.erpAddStateWiseProductRateForm.$setPristine();
		$scope.editStateWiseProductRate = {};
		$scope.erpEditStateWiseProductRateForm.$setUntouched();
		$scope.erpEditStateWiseProductRateForm.$setPristine();
		$scope.navigateFormPage();
	};
	//**********/Back Button********************************************

    //**********Reset Button*********************************************
	$scope.resetButton = function(){
		angular.element('.invoicing_rate').val('');
		$scope.addStateWiseProductRate = {};
		$scope.erpAddStateWiseProductRateForm.$setUntouched();
		$scope.erpAddStateWiseProductRateForm.$setPristine();
		$scope.editStateWiseProductRate = {};
		$scope.erpEditStateWiseProductRateForm.$setUntouched();
		$scope.erpEditStateWiseProductRateForm.$setPristine();
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

	//*****************display division dropdown start************************
	$scope.divisionsCodeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'division/get-divisioncodes'
	}).success(function (result) {
		$scope.divisionsCodeList = result;
		$scope.clearConsole();
	});
	//*****************display division dropdown end*******************************

	//*****************display state code dropdown start***************************
	$scope.stateCodeList = [];

	$http({
		method: 'POST',
		url: BASE_URL +'statesList'
	}).success(function (result) {
		if(result){
			$scope.stateCodeList = result;
		}
		$scope.clearConsole();
	});

	$scope.enableDepartmentField= function(state_id){
		if(state_id){
			$scope.disableDeptOnEdit =false;
		}


	}
	//*****************display state code dropdown end*****************************

	//*****************get Product State List which contains products** 27/02/18*************************
	$scope.funGetProductWiseStatesList = function(deptId,divisionId){
		$http({
			method: 'POST',
			url: BASE_URL +'master/invoicing/state-wise-product-rates/product-alias-states-list',
			data:{selectedDept:deptId,selectedDivision:divisionId}
		}).success(function (result) {
			if(result){
				$scope.productStatesList = result.productStatesList;
				if($scope.productStatesList  && $scope.productStatesList[0]){
					$scope.cirStateID = $scope.productStatesList[0].id;
				}else{
					$scope.cirStateID = '0';
				}
				$scope.funGetStateWiseProductRates($scope.cirStateID,deptId,divisionId,$scope.formType);
			}
			$scope.clearConsole();
		});
	}
	//*****************display state code dropdown end*****************************

	//*****************display customer list dropdown******************************
	$scope.productAliasRateList = [];
	$scope.getProductAliasRateList = function(productCatId){
		$http({
			method: 'GET',
			url: BASE_URL +'product-master-alias-list/'+productCatId,
		}).success(function (result) {
			$scope.productAliasRateList = result.productAliasRateList;
			$scope.clearConsole();
		});
	}
	//*****************/display customer list code dropdown************************

	//**********Getting all State Wise Product *******************************************
	$scope.funGetStateWiseProductRates = function(cirStateId,cirProdctCatId,cirDivisionId,type){
		$scope.cirStateID = cirStateId;
		var cirCatId = !cirProdctCatId ? $scope.deptID.selectedOption.id : cirProdctCatId ;
		$scope.loaderShow();
		$http({
			url: BASE_URL + "master/invoicing/state-wise-product-rates/get-state-wise-product-rates",
			method: "POST",
			data:{cirStateId:cirStateId,cirCatId:cirCatId,cirDivisionId:cirDivisionId}
		}).success(function(result, status, headers, config){
			$scope.stateWiseProductRateList = result.stateWiseProductRateList;
			$scope.stateWiseCustomerInvoicingCount = type =='modify' ? $scope.stateWiseProductRateList.length  : result.stateWiseCustomerInvoicingCount;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(result, status, headers, config){
			$scope.loaderHide();
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
    };
	//**********/Getting all State Wise Product ***************************************************

	//***************** Adding of State Wise Product ** *** 27/02/18*******************
	$scope.funAddStateWiseProductRate = function(cirStateID,prod_cat_id,type){

		if(!$scope.erpAddStateWiseProductRateForm.$valid)return;
		if($scope.newAddStateWiseProductRateFormflag)return;
		$scope.newAddStateWiseProductRateFormflag = true;
		var formData = $(erpAddStateWiseProductRateForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "master/invoicing/state-wise-product-rates/add-state-wise-product-rates",
			method: "POST",
			data: {formData :formData,saveType:type}
		}).success(function(data, status, headers, config) {
			$scope.newAddStateWiseProductRateFormflag = false;
			if(data.error == 1){

				if(type == 'add'){
				$scope.addSWiseProductRate = {};
				}
        $scope.funGetStateWiseProductRates(cirStateID,$scope.deptID.selectedOption.id,$scope.defaultDivisionId.selectedOption.id,$scope.formType);
				//$scope.funEditSelectedStateProductRate(cirStateID,prod_cat_id,type)
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newAddStateWiseProductRateFormflag = false;
			$scope.loaderHide();
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of State Wise Product *****************************

	//**************** editing of State Wise Product ** *************************************
	$scope.funEditStateWiseProductRate = function(cirId){
		if(cirId){
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$scope.getProductAliasRateList();
			$http({
				url: BASE_URL + "master/invoicing/state-wise-product-rates/view-state-wise-product-rates/"+cirId,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
					$scope.listStateWiseProductRateDiv  = true;
					$scope.addStateWiseProductRateDiv 	= true;
					$scope.editStateWiseProductRateDiv 	= false;
					$scope.editStateWiseProductRate     = result.stateWiseProductRateData;
					$scope.editStateWiseProductRate.cir_state_id  = {
						selectedOption: { id: result.stateWiseProductRateData.cir_state_id}
					};
					$scope.editStateWiseProductRate.cir_product_category_id  = {
						selectedOption: { id: result.stateWiseProductRateData.cir_product_category_id}
					};
					$scope.editStateWiseProductRate.cir_c_product_id  = {
						selectedOption: { id: result.stateWiseProductRateData.cir_c_product_id}
					};
					$scope.editStateWiseProductRate.cir_division_id  = {
						selectedOption: { id: result.stateWiseProductRateData.cir_division_id}
					};
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
	//****************/editing of State Wise Product **************************************

	//**************** Updating of State Wise Product ***************************************
	$scope.funUpdateStateWiseProductRate_13A18 = function(cirId){

		if(!$scope.erpEditStateWiseProductRateForm.$valid)return;
		var formData = $(erpEditStateWiseProductRateForm).serialize();
		$scope.loaderShow();

		$http({
			url: BASE_URL + "master/invoicing/state-wise-product-rates/update-state-wise-product-rates",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) {
			if(result.error == 1){
				$scope.cirStateID = result.stateId;
				$scope.funEditStateWiseProductRate(cirId);
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
	//*************** /Updating of State Wise Product ***************************************
//**************** Updating of State Wise Product ***************************************
	$scope.funUpdateStateWiseProductRate = function(){

		if(!$scope.erpEditStateWiseProductRateForm.$valid)return;
		var formData = $(erpEditStateWiseProductRateForm).serialize();
		//console.log(formData);
		$scope.loaderShow();

		$http({
			url: BASE_URL + "master/invoicing/state-wise-product-rates/update-state-wise-product-rates",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) {
			if(result.error == 1){
				$scope.cirStateID = result.stateId;
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
	//*************** /Updating of State Wise Product ***************************************
	//**************** Deleting of State Wise Product ** ***************************
	$scope.funDeleteStateWiseProductRate = function(id,cirStateId){
		if(id){
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/invoicing/state-wise-product-rates/delete-state-wise-product-rates/"+id,
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.funGetStateWiseProductRates(cirStateId,$scope.deptID.selectedOption.id,$scope.defaultDivisionId.selectedOption.id,$scope.formType);
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
	//************** /Deleting of State Wise Product *******************************

	
//****************modify Products of selected state ******* 27/02/18******************************
	$scope.funEditSelectedStateProductRate = function(cirStateID,prod_cat_id,divisionID,formType){
		$scope.editStateWiseProductRateDiv=false;
		$scope.listStateWiseProductRateDiv=true;
		//console.log(prod_cat_id);
		var type=formType;
		$scope.divisionId = divisionID;
		var cirID = type == 'modify' ? $scope.cir_product_category_id : '0';
		var divisionID = type == 'modify' ? $scope.divisionId : '0';
		if(cirStateID){
			$scope.cirStateID = cirStateID;
			$scope.loaderShow();
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "master/invoicing/state-wise-product-rates/get-selected-state-product-rates/"+cirStateID+"/"+prod_cat_id+"/"+divisionID,
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
	/**********get refresh list of products and states with department***/
	$scope.funGetStateWiseRefreshProductRates=function(cirStateID,deptID,divisionID){
		$scope.funGetStateWiseProductRates(cirStateID,deptID,divisionID,$scope.formType);
		$scope.funGetProductWiseStatesList(deptID,divisionID);
		$scope.defaultDivisionId.selectedOption.id = divisionID;
		$scope.deptID.selectedOption.id = deptID;
	};
});
