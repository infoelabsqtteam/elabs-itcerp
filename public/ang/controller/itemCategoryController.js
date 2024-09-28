app.controller('itemCategoryController', function($scope, $http, BASE_URL, $ngConfirm) {

	//define empty variables
	$scope.itemdata         = '';
	$scope.editItemFormDiv  = true;
	
	//sorting variables
	$scope.sortType     = 'item_cat_code';    // set the default sort type
	$scope.sortReverse  = false;             // set the default sort order
	$scope.searchFish   = '';    			 // set the default search/filter term
	$scope.successMessage=true;
	$scope.errorMessage=true;
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	};
	//**********/scroll to top function**********
		
	//**********loader show****************************************************
	$scope.loaderShow = function(){
        angular.element('#global_loader').fadeIn('slow');
	};
	//**********/loader show**************************************************
    
    //**********loader show***************************************************
	$scope.loaderHide = function(){
        angular.element('#global_loader').fadeOut('slow');
	};
	//**********/loader show**************************************************
	
	//*****************generate unique code******************
	$scope.item_cat_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'inventory/item-categories/get-item-number'
		}).success(function (result){
			$scope.item_cat_code = result.uniqueCode;
			$scope.clearConsole();
			$scope.loaderHide();
		});
	};
	//*****************/generate unique code*****************
	
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
						$scope.deleteItemCategory(id);
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
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		//console.clear();
	};
	//*********/Clearing Console********************************************
	
	//*********code used for sorting list order by fields**********************
	$scope.predicate = 'item_cat_code';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//*********/code used for sorting list order by fields**********************
	
	/*****************display division code dropdown start*****************/	
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
	$scope.itemCategoryList = [];
	$scope.categoryFun = function(){	
		$http({
			method: 'POST',
			url: BASE_URL +'inventory/get-item-category-list'
		}).success(function (result) {
			if(result.itemCategoryList){  
				$scope.itemCategoryList = result.itemCategoryList;
			}
			$scope.clearConsole();
		});
	};
	/*****************display parent category code dropdown end*****************/	
	
	/***************** item SECTION START HERE *****************/	
	// function is used to call the 
    $scope.addItemCategory = function(){ 
    	if(!$scope.itemCategoryAddForm.$valid)
      	return;
		$scope.loaderShow();	
		// post all form data to save
        $http.post(BASE_URL + "inventory/add-item-category", {
            data: {formData:$(itemCategoryAddForm).serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){ 
				$scope.resetForm();
				$scope.getItemCategory();				
				$scope.categoryFun();				
				$scope.alertSuccessMsg(data.success);
			}else{
				$scope.alertErrorMsg(data.error);
			}
			$scope.loaderHide();	
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
			$scope.loaderHide();	
			$scope.clearConsole();
        });
    };
	/*reset form*/
	$scope.resetForm = function(){ 
		//$scope.item_cat_code=null;
		$scope.item_cat_name=null;
		$scope.item_parent_cat=null;
		$scope.itemCategoryAddForm.$setUntouched();
		$scope.itemCategoryAddForm.$setPristine();
	}
	//function is used to fetch the list of compines	
	$scope.getItemCategory = function()
    { 
		$scope.generateDefaultCode();
		$http.post(BASE_URL + "inventory/get-item-category", {
            //status: status, prod_id:prodID, cat_id:catID
        }).success(function (data, status, headers, config) { 
		   if(data.itemsList){
			   	$scope.itemdata = data.itemsList;
		   }
		   $scope.clearConsole();
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
			$scope.clearConsole();
        });
    };	
		
	/**************multisearch start here**********************/
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	$scope.getMultiSearch = function()
    {  
		$scope.searchItemCategory='';
		$http.post(BASE_URL + "inventory/get-item-category-multisearch",{
            data: { formData:$scope.searchItemCat },
        }).success(function (data, status, headers, config){ 
			if(data.itemsList){
			   	$scope.itemdata = data.itemsList;
		    }
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
	    $scope.searchItemCat={};
		$scope.searchItemCategory='';
		$scope.getItemCategory();
	}
	
	$scope.openMultisearch = function()
    { 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	}
	
	/**************multisearch end here**********************/
	// Delete item from the database
	$scope.deleteItemCategory = function(id){ 
		if(id){
			$scope.loaderShow();
			$http.post(BASE_URL + "inventory/delete-item-category", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.success){
					// reload the all employee
					$scope.getItemCategory();
					$scope.categoryFun();
					$scope.alertSuccessMsg(data.success);
				}else{
					$scope.alertErrorMsg(data.error);
				}
				$scope.loaderHide();	
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
				}
				$scope.loaderHide();	
				$scope.clearConsole();
			});
		}
    };
	
	// edit an item and its data
	$scope.editItemCategory = function(id)
    {  
		if(id){
			$scope.editItemCategoryId=id;
			$http.post(BASE_URL + "inventory/edit-item-category", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config) {
				if(data.responseData){ 
				    $scope.showEditForm();
					$scope.selectedParentCategory = {
						selectedOption: {id: data.responseData.item_parent_cat} 
					};
					$scope.item_cat_code1 = data.responseData.item_cat_code; 			
					$scope.item_cat_name1 = data.responseData.item_cat_name; 			
					$scope.item_name_old = data.responseData.item_cat_name; 			
					$scope.item_cat_id1 = btoa(data.responseData.item_cat_id);	
				}else{
					$scope.alertErrorMsg(data.error);
				}
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
				}
				$scope.clearConsole();
			});
		}else{
			
		}
    };	
	// update item and its data
	$scope.updateItemCategory = function(){ 
    	if(!$scope.itemCategoryEditForm.$valid)
      	return; 
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "inventory/update-item-category", { 
            data: {formData:$(itemCategoryEditForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				//reload the all companies
				$scope.editItemCategory($scope.editItemCategoryId);				
				$scope.categoryFun();
				//$scope.showAddForm();				
				$scope.alertSuccessMsg(data.success);
			}else{
				$scope.alertErrorMsg(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
					$scope.alertErrorMsg('Oops ! Sorry for inconvience server not responding or may be some error');
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }); 
    };
	
	// show form for item edit and its data
	$scope.showEditForm = function()
    {
		 $scope.editItemFormDiv = false;
		 $scope.addItemFormDiv = true;
	};
	// show form for add new  item 
	$scope.showAddForm = function()
    {
		 $scope.editItemFormDiv = true;
		 $scope.addItemFormDiv = false;
	};
	//alert success messages
	$scope.alertSuccessMsg = function(msg)
    {	
	    $scope.successMessage=false;
		$scope.errorMessage=true;
		$scope.successMsg=msg;
	};
	//alert error messages
	$scope.alertErrorMsg = function(err)
    {	
		$scope.successMessage=true;
		$scope.errorMessage=false;
		$scope.errorMsg=err;	
	};
	//hide alert messages
	$scope.hideAlert = function()
    {		
           $scope.successMessage=true;
		   $scope.errorMessage=true;
	};
	/***************** item SECTION END HERE *****************/
});
