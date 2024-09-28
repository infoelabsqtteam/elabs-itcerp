app.controller('productCategoryController', function($scope,$timeout,$sce,$http, BASE_URL,$ngConfirm) {	
	
	//define empty variables
	$scope.currentModule		= 1;                   		//variable used in tree.js for tree popup 
	$scope.prodata 			= '';
	$scope.editProductFormDiv 	= true;
	
	//sorting variables
	$scope.sortType     		= 'p_category_code';    	// set the default sort type
	$scope.sortReverse  		= false;                	// set the default sort order
	$scope.searchFish   		= '';    			// set the default search/filter term
	$scope.ProductParentId		='0';
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';	
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.IsVisiableSuccessMsg 	= true;
	$scope.IsVisiableErrorMsg 	= true;
	$scope.uploadProductFormDiv 	= true;
	
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	}
	//**********/scroll to top function**********
	
	//**********loader show****************************************************
	$scope.loaderShow = function(){
		angular.element('#global_loader').fadeIn('slow');
	}
	//**********/loader show**************************************************
    
	//**********loader show***************************************************
	$scope.loaderHide = function(){
		angular.element('#global_loader').fadeOut('slow');
	}
	//**********/loader show**************************************************
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		if(APPLICATION_MODE)console.clear();
	}
	//*********/Clearing Console********************************************
	
	//**********successMsgShow**************************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 		= message;				
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShow = function(message){
		$scope.errorMessage 		= message;
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= false;
		$scope.moveToMsg();
	}
	//********** /errorMsgShow************************************************
	
	//*********hideAlertMsg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//**********/hideAlertMsg********************************************
	
	//************/show tree pop up*******************************************
	$scope.showProductCatTreeViewPopUp = function(currentModule){ 
		$scope.currentModule=currentModule; 
		$('#productCategoryPopup').modal('show');		
	}
	//**********/show tree pop up********************************************/
		
	//*******************filter product category from tree view****************
	$scope.filterSelectedProductCategoryId=function(selectedNode){
		$scope.getProductCategory(selectedNode.p_category_id);
		$scope.refreshMultisearch();
		$scope.seach_category_id = {
			selectedOption: { id: selectedNode.p_category_id} 
		};
		$('#productCategoryPopup').modal('hide');
		$scope.currentModule=1;
	}
	//*****************/filter product category from tree view******************
	
	//*****************display division code dropdown start*****************/	
	$scope.divisionCodeList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) { 
			if(result){ 
				$scope.divisionCodeList = result;
			}
			$scope.clearConsole();
		});
	/*****************display division code dropdown end*****************/
	
	/*****************display parent category dropdown code dropdown start*****************/	
	$scope.productCategoriesTree = [];
	$scope.getProductCategories = function(){	
		$http({
			method: 'POST',
			url: BASE_URL +'get-product-category-tree-view'
		}).success(function (result) {
			if(result.productCategoriesTree){  
				$scope.productCategoriesTree = result.productCategoriesTree;
			}
			$scope.clearConsole();
		});
	};
	$scope.getProductCategories();
	/*****************display parent category dropdown code dropdown start*****************/
	
	/*****************display parent category dropdown code dropdown start*****************/	
	$scope.categoryCodeList = [];
	$scope.categoryFun = function(){	
		$http({
			method: 'POST',
			url: BASE_URL +'product-category/get-categorycode-list'
		}).success(function (result) {
			if(result.productCategoryList){  
				$scope.categoryCodeList = result.productCategoryList;
			}
			$scope.clearConsole();
		});
	}
	/*****************display parent category code dropdown end*****************/	
	
	//*****************generate unique code******************
	$scope.p_category_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'product-categories/generate-product-category-number'
		}).success(function (result){
			$scope.p_category_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	$scope.generateDefaultCode();
	//*****************/generate unique code*****************
	
	//***************** product add form start here *****************/	
	$scope.addProductCategory = function(){
		
		if(!$scope.productCategoryAddForm.$valid)return; 
		$scope.loaderShow();
		
		// post all form data to save
		$http.post(BASE_URL + "product-category/add-category", {
		    data: {formData:$(productCategoryAddForm).serialize() },
		}).success(function (data, status, headers, config) {
			if(data.success){ 
				$scope.resetAddForm();
				$scope.generateDefaultCode();
				$scope.getProductCategory($scope.ProductParentId);				
				$scope.categoryFun();				
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShow(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	};	
	//***************** product add form end here *****************/	
	
	//***************** product add form end here *****************/
	$scope.addProductCategoryNode = function(node){  
		if(node.level==1 || node.level==0){ 
			$scope.setSelectedProductCategoryId(node); 
			$scope.editProductFormDiv = true;
			$scope.addProductFormDiv = false;
		}
	};
	//***************** product add form end here *****************/	
	
	//***************** product reset add form end here *****************/	
	$scope.resetAddForm=function(){
		$scope.p_category_name=null;
		$scope.parent_id=null;
		$scope.productCategoryAddForm.$setUntouched();
		$scope.productCategoryAddForm.$setPristine();
		$scope.resetProductCategory();
	}
	//***************** product reset add form end here *****************/	

	//code used for sorting list order by fields 
	$scope.predicate = 'p_category_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list of compines	
	$scope.getProductCategory = function(parent_id){
		
		//$scope.generateDefaultCode();
		$scope.ProductParentId=parent_id;
		$scope.loaderShow();		
		//$scope.getProductCategories();
		
		$http.post(BASE_URL + "product-category/get-category/"+parent_id, {
			//status: status, prod_id:prodID, cat_id:catID
		}).success(function (data, status, headers, config) { 
			if(data.productsList){		    
				$scope.prodata = data.productsList;
			}
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	
	/**************multisearch start here**********************/
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	
	$scope.getMultiSearch = function()
	{  
		$scope.loaderShow();
		$scope.filterProductCategory='';
		$scope.searchProductCat.search_parent_id=$scope.ProductParentId;
		$http.post(BASE_URL + "product-category/get-category-multisearch", {
			data: { formData:$scope.searchProductCat },
		}).success(function (data, status, headers, config){ 
			$scope.prodata = data.productsList;
			$scope.clearConsole();
			$scope.loaderHide();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '400'){
				//$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	
	$scope.closeMultisearch = function(){ 
		$scope.multiSearchTr=true;
		$scope.multisearchBtn=false;
		$scope.refreshMultisearch();
	};
	
	$scope.refreshMultisearch = function(){ 
		$scope.searchProductCat={};
		$scope.getProductCategory($scope.ProductParentId);
	};
	
	$scope.openMultisearch = function(){ 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	};
	/**************multisearch end here**********************/
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id){ 
		$ngConfirm({
			title     : false,
			content   : defaultDeleteMsg, //Defined in message.js and included in head
			animation : 'right',
			closeIcon : true,
			closeIconClass    : 'fa fa-close',
			backgroundDismiss : false,
			theme   : 'bootstrap',
			columnClass : 'col-sm-5 col-md-offset-3',
			buttons : {
				OK: {
					text: 'ok',
					btnClass: 'btn-primary',
					action: function () {
						$scope.deleteProductCategory(id);
					}
				},
				cancel: {
					text     : 'cancel',
					btnClass : 'btn-default ng-confirm-closeIcon'					
				}
			}
		});
	};
	//**********/confirm box****************************************************
	
	//Delete product from the database
	$scope.deleteProductCategory = function(id){ 
		if(id){
				$scope.loaderShow();
				$http.post(BASE_URL + "product-category/delete-category", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {
					if(data.success){
						//reload the all employee
						$scope.getProductCategory($scope.ProductParentId);
						$scope.categoryFun();
						$scope.successMsgShow(data.success);
					}else{
						$scope.errorMsgShow(data.error);
					}
					$scope.loaderHide();
					$scope.clearConsole();
				}).error(function (data, status, headers, config) {
					if(status == '500' || status == '400'){
						$scope.errorMsgShow($scope.defaultMsg);
					}
					$scope.loaderHide();
					$scope.clearConsole();
				});
			}
	};
	
	//edit an product and its data
	$scope.editProductCategory = function(id){  
		if(id){
			$scope.editProductCategoryId=id;
			$scope.searchProduct='';
			$http.post(BASE_URL + "product-category/edit-category", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){
					$scope.resetProductCategory();
					var resData=data.responseData;
					var nodeData=[];			
					nodeData.p_category_id=resData.parent_id;
					nodeData.p_category_name=resData.parent_category_name;
					nodeData.level=resData.parent_category_level; 
					$scope.setSelectedProductCategoryId(nodeData);
					$scope.editProCat = resData;			
					$scope.p_category_id = btoa(resData.p_category_id);	
					$scope.showEditForm();
				}else{
					$scope.errorMsgShow(data.error);
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}else{
			
		}
	};
	
	//update product and its data
	$scope.updateProductCategory = function(){ 
		
		if(!$scope.productCategoryEditForm.$valid)return; 
		$scope.loaderShow();
		
		// post all form data to save
		$http.post(BASE_URL + "product-category/update-category", { 
			data: {formData:$(productCategoryEditForm).serialize() },
		}).success(function (data, status, headers, config) { 		
			if(data.success){ 
				$scope.getProductCategory($scope.ProductParentId);	
				$scope.editProductCategory($scope.editProductCategoryId);				
				$scope.categoryFun();				
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShow(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}); 
	};
	
	// show form for product edit and its data
	$scope.showEditForm = function(){
		$scope.editProductFormDiv = false;
		$scope.addProductFormDiv = true;
		$scope.uploadProductFormDiv = true;
	};
	// show form for add new  product 
	$scope.showAddForm = function(){
		$scope.editProductFormDiv = true;
		$scope.addProductFormDiv = false;
		$scope.uploadProductFormDiv = true;
	};
	
	// show form for add new  product 
	$scope.showUploadProductCatForm = function(){
		$scope.resetUploadForm(); 
		$scope.editProductFormDiv = true;
		$scope.addProductFormDiv = true;
		$scope.uploadProductFormDiv = false;
	};
	
	$scope.backToAddForm = function(){
		$scope.resetAddForm();
		$scope.resetProductCategory();
		$scope.editProductFormDiv = true;
		$scope.addProductFormDiv = false;
		$scope.uploadProductFormDiv = true;
	};
	
	$scope.hideTreeForms = function(){
		$scope.editProductFormDiv = true;
		$scope.addProductFormDiv = true;
	};

	//****************dropdown filter show/hide******************//
	$scope.ProductCategoryFilterBtn  = false;
	$scope.ProductCategoryFilterInput  = true;
	//Show filter
	$scope.showProductFilter = function(){ 
		$scope.ProductCategoryFilterBtn  = true;
		$scope.ProductCategoryFilterInput  = false;
	};
	//hide filter
	$scope.hideProductFilter = function(){ 
		$scope.ProductCategoryFilterBtn  = false;
		$scope.ProductCategoryFilterInput  = true;
	};
	//****************/dropdown filter show/hide******************//	
	
	$scope.setSelectedProductCategoryId=function(node){  
		if(node.level==1 || node.level==0){ 
			$scope.testProductCategoryOptions = [{"id":node.p_category_id,"name":node.p_category_name}];
			$scope.addTestProductCategory = {
				selectedOption: {"id":node.p_category_id,"name":node.p_category_name } 
			};
			$scope.editTestProductCategory = {
				selectedOption: {"id":node.p_category_id,"name":node.p_category_name } 
			};
			$scope.selectedProductCategoryId=node.p_category_id;
			$scope.selectedProductCategoryName=node.p_category_name;
			$('#productCategoryPopup').modal('hide');
		} 
	};
	$scope.resetProductCategory=function(){
		$scope.selectedProductCategoryId='';
		$scope.selectedProductCategoryName='';
		$scope.testProductCategoryOptions={};
	};
	$scope.funSwithPage=function(url){
		if(url=='master/product-categories/tree-view'){
			window.location.href=BASE_URL +'master/product-categories';
		}else{
			window.location.href=BASE_URL +'master/product-categories/tree-view';
		}		
	};
	//***************** product SECTION END HERE *****************//
	
	//***************************upload csv**********************************************
	$(document).on('click', '#uploadProductMasterBtnId',function(e){
		
		e.preventDefault();
		var formdata = new FormData();
		formdata.append('productMasterFile',$('#uploadProductMasterFile')[0].files[0]);		
		$scope.loaderShow();
		
		$.ajax({
			url: BASE_URL + "master/product-categories/upload-product-categories-csv",
			type: "POST", 
			data: formdata,
			contentType: false,
			cache: false,  
			processData:false,
			success: function(res){ 
				if(res.error == '1'){  					
					$scope.successMsgShow(res.message);
					$scope.getProductCategory($scope.ProductParentId);
					$scope.uploadType = '';
					$scope.resetUploadForm();
					$scope.clearConsole();
					$scope.loaderHide();
					}else{
					$scope.errorMsgShow(res.message);
					$scope.clearConsole();
					$scope.loaderHide();
				}
				$scope.$apply();
			}
		});   
	});
	
	$scope.resetUploadForm = function(){
		angular.element('#uploadProductMasterFile').val('');
		angular.element('.browseFileInput').val('');
	}
	//***************************upload csv**********************************************
});
 