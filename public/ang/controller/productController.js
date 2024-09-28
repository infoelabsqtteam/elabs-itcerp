app.controller('productController', function($location,$scope, $timeout,$http, BASE_URL ,$ngConfirm) {
	//define empty variables
	$scope.currentModule=2;                      //variable used in tree.js for tree popup 
	$scope.prodata = '';
	$scope.editProductFormDiv = true;
	$scope.uploadProductFormDiv = true;
	$scope.defaultMsg = 'Oops ! Sorry for inconvience server not responding or may be some error';
	$scope.seachCategoryId='0';	
	$scope.product_barcode= '';
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
	}
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
		//console.clear();
	}
	//*********/Clearing Console********************************************
	
	//************/show tree pop up*******************************************
	$scope.showProductCatTreeViewPopUp = function(currentModule){
		$scope.currentModule=currentModule; 
		$('#categoryPopup').modal('show');		
	}
	//**********/show tree pop up********************************************/
		
	//*******************filter product category from tree view****************
	$scope.filterSelectedProductCategoryId=function(selectedNode){ 
		$scope.getProducts(selectedNode.p_category_id);
		$scope.refreshMultisearch();
		$scope.seach_category_id = {
			selectedOption: { id: selectedNode.p_category_id} 
		};
		$scope.seach_category_id.searchProducthdr = $scope.keyword;

		$('#categoryPopup').modal('hide');
		$scope.currentModule=2;
	}
	//*****************/filter product category from tree view******************
	
	//**********Read/hide More description********************************************
	$scope.toggleDescription = function(type,id) {
		 angular.element('#'+type+'limitedText-'+id).toggle();
		 angular.element('#'+type+'fullText-'+id).toggle();
	};
	//*********/Read More description********************************************
	
	//sorting variables
	$scope.sortType     = 'product_code';    // set the default sort type
	$scope.sortReverse  = false;             // set the default sort order
	$scope.searchFish   = '';    			 // set the default search/filter term
	$scope.IsVisiableSuccessMsg=true;
	$scope.IsVisiableErrorMsg=true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
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
	
	/*****************display product category dropdown code dropdown start*****************/	
	$scope.productCategories = [];
		$http({
			method: 'GET',
			url: BASE_URL +'product-category/get-category-list/'+2
		}).success(function (result) {
			if(result.productCategories){  
				$scope.productCategories = result.productCategories;
			}
			$scope.clearConsole();
		});
	/*****************display product category code dropdown end*****************/	
	
	//*****************generate unique code******************
	$scope.product_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'products/generate-product-number'
		}).success(function (result){
			$scope.product_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************
	
	//*****************product category tree list data*****************
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
	}
	//*****************/product category tree list data*****************

	/***************** product SECTION START HERE *****************/	
	//function is used to call the 
    $scope.addProduct = function(seachCategoryId){
    	if(!$scope.productForm.$valid)
      	return; 
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "products/add-product", {
            data: {formData:$(productForm).serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){ 
				$scope.resetProductCategory();
				$scope.resetAddForm();
				$scope.successMsgShow(data.success);				
				$scope.getProducts(seachCategoryId);
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
    }	
	$scope.resetAddForm=function(){		
		$scope.resetProductCategory();
		$scope.product={};
		$scope.productForm.$setUntouched();
		$scope.productForm.$setPristine();
	}
	//code used for sorting list order by fields 
	$scope.predicate = 'product_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	
	/**************multisearch start here**********************/
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	
	$scope.getMultiSearch = function()
    {  
		$scope.filterPro='';
		$scope.searchProduct.search_p_category_id=$scope.seachCategoryId;
		$http.post(BASE_URL + "products/get-products-multisearch", {
            data: { formData:$scope.searchProduct },
        }).success(function (data, status, headers, config){ 
			$scope.prodata = data.productsList;
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '400'){
				//$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
    };
	
	$scope.closeMultisearch = function()
    { 
	    $scope.multiSearchTr=true;
		$scope.multisearchBtn=false;
		$scope.refreshMultisearch();
	}
	
	$scope.refreshMultisearch = function()
    { 
	    $scope.searchProduct={};
		$scope.filterPro='';
		$scope.getProducts($scope.seachCategoryId);
	}
	
	$scope.openMultisearch = function()
    { 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	}
	/**************multisearch end here**********************/
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(seachCategoryId,id){
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
						$scope.deleteProduct(seachCategoryId,id);
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
	
	// Delete product from the database
	$scope.deleteProduct = function(seachCategoryId,id)
    { 
		if(id){
				 $scope.loaderShow();
				 $http.post(BASE_URL + "products/delete-product", {
					data: {"_token": "{{ csrf_token() }}","id": id }
				}).success(function (data, status, headers, config) {
					if(data.success){
						// reload the all employee
						$scope.successMsgShow(data.success);
						$scope.getProducts(seachCategoryId);
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
	$scope.editProductFun = function(id)
    {  
		if(id){
			$http.post(BASE_URL + "products/edit-product", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (res, status, headers, config){
				if(res.responseData){
					$scope.resetProductCategory();	
					var editData=res.responseData
				    $scope.searchPro={};
					$scope.editProduct=editData;
				    $scope.showEditForm();
					var nodeData=[];			
					nodeData.p_category_id=editData.p_category_id;
					nodeData.p_category_name=editData.p_category_name;
					nodeData.level=editData.level; 
					$scope.setSelectedProductCategoryId(nodeData);
					$scope.product_id = btoa(editData.product_id);	
				}else{
					$scope.errorMsgShow(res.error);
				}
				$scope.clearConsole();
			}).error(function (res, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}
    };	
	// update product and its data
	$scope.updateProduct = function(seachCategoryId){  
    	if(!$scope.editProductForm.$valid)
      	return;
	    $scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "products/update-product", { 
            data: {formData:$(editProductForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				//reload the all companies
				$scope.getProducts(seachCategoryId);
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

	//show form for product edit and its data
	$scope.showEditForm = function()
    {
		$scope.editProductFormDiv = false;
		$scope.addProductFormDiv = true;
		$scope.uploadProductFormDiv = true;
	};
	
	//show form for add new  product 
	$scope.showAddForm = function(){
		$scope.resetAddForm();
		$scope.editProductFormDiv = true;
		$scope.addProductFormDiv = false;
		$scope.uploadProductFormDiv = true;
	};
	
	//show csv upload form 
	$scope.showUploadProductForm = function(){
		$scope.resetUploadForm();
		$scope.resetAddForm();
		$scope.editProductFormDiv = true;
		$scope.addProductFormDiv = true;
		$scope.uploadProductFormDiv = false;
	};

	$scope.setSelectedProductCategoryId=function(node){  
		if(node.level==2){ 
			$scope.testProductCategoryOptions = [{"id":node.p_category_id,"name":node.p_category_name}];
			$scope.addTestProductCategory = {
				selectedOption: {"id":node.p_category_id,"name":node.p_category_name } 
			};
			$scope.editTestProductCategory = {
				selectedOption: {"id":node.p_category_id,"name":node.p_category_name } 
			};
			$scope.selectedProductCategoryId=node.p_category_id;
			$scope.selectedProductCategoryName=node.p_category_name;
			$('#categoryPopup').modal('hide');
		} 
	}
	
	$scope.resetProductCategory=function(){
		$scope.selectedProductCategoryId='';
		$scope.selectedProductCategoryName='';
		$scope.addTestProductCategory={};
	}

//***************************upload csv**********************************************
	$(document).on('click', '#uploadProductMasterBtnId',function(e){ 
		e.preventDefault();
        var formdata = new FormData();
		formdata.append('productMasterFile',$('#uploadProductMasterFile')[0].files[0]);
		
		$scope.loaderShow();
		$.ajax({
            url: BASE_URL + "master/products/upload-products-csv",
            type: "POST", 
            data: formdata,
            contentType: false,
            cache: false,  
            processData:false,
            success: function(res){ 
				if(res.error == '1'){  					
					$scope.successMsgShow(res.message);
					$scope.getProducts($scope.seachCategoryId);
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
//**************function is used to fetch the list of stndrd wise test product ***************
	var tempProductSearchTerm;
	$scope.funFilterProductTest = function(p_category_id,keyword){
		tempProductSearchTerm = keyword;
		$timeout(function () {
			if (keyword == tempProductSearchTerm) {
				$scope.loaderShow();
				$scope.seachCategoryId 		= p_category_id;
				$scope.searchProduct		= {};
				$scope.allListPaginate		= true;
				$scope.allParaListPaginate	= false;
				$scope.keyword 			= keyword;
				$http({
					method: 'POST',
					url: BASE_URL + "products/get-products/"+p_category_id,
					data: {keyword : $scope.keyword},
				}).success(function (data, status, headers, config) {  
					    $scope.prodata = data.productsList;
					    $scope.loaderHide(); 
					    $scope.clearConsole();
				}).error(function (data, status, headers, config) {
					if(status == '500' || status == '404'){
						$scope.errorMsgShow($scope.defaultMsg);
					}
					$scope.clearConsole();
				});	    
			}
		}, 800);		
		
	};
	//function is used to fetch the list of compines	
	$scope.getProducts = function(seachCategoryId){
			$scope.generateDefaultCode();
			$scope.seachCategoryId  = seachCategoryId;
			$scope.keyword 			= $scope.keyword;

			if(angular.isDefined(seachCategoryId)){ var category_id=seachCategoryId; }else{ var category_id='0'; }
			$http({
				method: 'POST', //GET
				url: BASE_URL +'products/get-products/'+category_id,
				data: {keyword : $scope.keyword},
			}).success(function (data, status, headers, config) {				
				$scope.prodata = data.productsList;
				$scope.clearConsole();
			});		
	};
	$scope.funRefreshProductTest = function(p_category_id,keyword=null){
		$scope.loaderShow();
		$scope.seachCategoryId 	= '0';
		$scope.searchProduct		= {};
		$scope.allListPaginate		= true;
		$scope.allParaListPaginate	= false;
		$scope.keyword 			= '';
		$scope.seach_category_id 	= {};
		
		$http({
			method: 'POST',
			url: BASE_URL + "products/get-products/"+p_category_id,
			data: {keyword : keyword},
		}).success(function (data, status, headers, config) {  
			    $scope.prodata = data.productsList;
			    $scope.loaderHide(); 
			    $scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};
});
