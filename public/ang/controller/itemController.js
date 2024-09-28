app.controller('itemController', function($scope, $http, BASE_URL,$ngConfirm) {
	
	//define empty variables
	$scope.prodata 	        = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.defaultMsg  	    = 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     	= 'item_id';    // set the default sort type
	$scope.sortReverse  	= false;         // set the default sort order
	$scope.searchFish   	= '';    		 // set the default search/filter term
	
	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 	 	 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	$scope.listItemFormDiv 	 	 = false;
	$scope.addItemFormDiv 	 	 = true;
	$scope.editItemFormDiv 	 	 = true;
	$scope.viewItemFormDiv 	     = true;
	$scope.IsEditModeItem     	 = false;	
	//**********/If DIV is hidden it will be visible and vice versa************
	
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
		//console.clear();
	}
	//*********/Clearing Console********************************************
	
	//**********Read/hide More description********************************************
	$scope.toggleDescription = function(type,id) {
		 angular.element('#'+type+'limitedText-'+id).toggle();
		 angular.element('#'+type+'fullText-'+id).toggle();
	};
	//*********/Read More description********************************************
	
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
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//********** /hide Alert Msg**********************************************
	
	//**********successMsgShowPopup**************************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		= message;				
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShowPopup************************************************
	
	//**********errorMsgShowPopup**************************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	}
	//********** /hideAlertMsgPopup************************************************
	
	//**********hideAlertMsgPopup*************
	$scope.hideAlertMsgPopup = function(){
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
	}
	//********** /hideAlertMsgPopup**********************************************
	
	//*****************generate unique code******************
	$scope.item_code  = '';
	$scope.generateDefaultCode = function(){
		$scope.loaderShow();
		$http({
			method: 'GET',
			url: BASE_URL +'inventory/items/get-item-number'
		}).success(function (result){
			$scope.item_code = result.uniqueCode;
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
						$scope.funDeleteItem(id);
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
	
	//**********confirm box******************************************************
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	}
	//********** /confirm box****************************************************
	
	//**********navigate Form*********************************************
	$scope.navigateItemPage = function(){
		if(!$scope.listItemFormDiv){
			$scope.listItemFormDiv 	 = true;
			$scope.addItemFormDiv 	 = false;
			$scope.editItemFormDiv 	 = true;
			$scope.viewItemFormDiv 	 = true;
		}else{
			$scope.listItemFormDiv 	 = false;
			$scope.addItemFormDiv 	 = true;			
			$scope.editItemFormDiv 	 = true;
			$scope.viewItemFormDiv 	 = true;
		}	
		$scope.hideAlertMsg();
	}
	//**********/navigate Form*********************************************
	
	//**********backButton Form*********************************************
	$scope.backButton = function(){
		$scope.listItemFormDiv 	 = false;
		$scope.addItemFormDiv 	 = true;			
		$scope.editItemFormDiv 	 = true;
		$scope.viewItemFormDiv 	 = true;
		$scope.itemMaster = {};
		$scope.erpAddItemForm.$setUntouched();
		$scope.erpAddItemForm.$setPristine();
		$scope.itemMasterEdit = {};
		$scope.erpEditItemForm.$setUntouched();
		$scope.erpEditItemForm.$setPristine();
		$scope.hideAlertMsg();
	}
	//**********/backButton Form*********************************************
	
	//**********Clearing Console*********************************************
	$scope.clearConsole = function(){
		//console.clear();
	}
	//*********/Clearing Console*********************************************
	
	//************code used for sorting list order by fields*********************
	$scope.predicate = 'item_code';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*********************
	
	/*****************display item category dropdown code dropdown start*****************/	
	$scope.itemCategoryList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'inventory/get-item-category-list'
		}).success(function (result) {
			if(result.itemCategoryList){  
				$scope.itemCategoryList = result.itemCategoryList;
			}
			$scope.clearConsole();
	});
	/*****************display item category code dropdown end*****************/
	
	/*****************display item category dropdown code dropdown start*****************/	
	$scope.itemUnitList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'units/get-units'
		}).success(function (result) {
			if(result.unitsList){  
				$scope.itemUnitList = result.unitsList;
			}
			$scope.clearConsole();
	});
	/*****************display item category code dropdown end*****************/
	
	//*******************listing of Items*************************************	
	$scope.funGetItems = function()
	{
		$scope.generateDefaultCode();
		$http({
			url: BASE_URL + "items/get-items",
			method: "POST"			
		}).success(function (data, status, headers, config) {
			$scope.itemDataList = data.itemsList;
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			$scope.newAddItemflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	};
	//****************/listing of Items*************************************	
	
	/**************multisearch start here**********************/
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	$scope.getMultiSearch = function()
    {  
		$scope.filterItems='';
		$http.post(BASE_URL + "items/get-items-multisearch",{
            data: { formData:$scope.searchItem },
        }).success(function (data, status, headers, config){ 
			$scope.itemDataList = data.itemsList;
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
	    $scope.searchItem={};
		$scope.filterItems='';
		$scope.funGetItems();
	}
	
	$scope.openMultisearch = function()
    { 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	}
	
	/**************multisearch end here**********************/
	
	//***************** Adding of item *************************************
	$scope.addItem = function(){
		if(!$scope.erpAddItemForm.$valid)return;		
		if($scope.newAddItemflag)return;		
		$scope.newAddItemflag = true;
		$scope.loaderShow();
		var formData = $(erpAddItemForm).serialize();	
		$http({
			url: BASE_URL + "items/add-item",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.newAddItemflag = false;
			if(data.error == 1){
				$scope.successMsgShow(data.message);
				$scope.funGetItems();
				$scope.listItemFormDiv 	 = false;
				$scope.addItemFormDiv 	 = true;			
				$scope.editItemFormDiv 	 = true;
				$scope.viewItemFormDiv 	 = true;				
				$scope.resetForm();
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newAddItemflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		});
	}
	//**************** /Adding of item *************************************
	/* resetForm*/
	$scope.resetForm = function(itemId){
		$scope.itemMaster = {};
		$scope.erpAddItemForm.$setUntouched();
		$scope.erpAddItemForm.$setPristine();
		$scope.itemMasterEdit = {};
		$scope.erpEditItemForm.$setUntouched();
		$scope.erpEditItemForm.$setPristine();
	} 

	//**************** editing of item *************************************
	$scope.funEditItem = function(itemId)
	{
		if(itemId){
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "items/view-item/"+itemId,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){				
					$scope.listItemFormDiv 	 = true;
					$scope.addItemFormDiv 	 = true;			
					$scope.editItemFormDiv 	 = false;
					$scope.viewItemFormDiv 	 = true;
					$scope.itemMaster = {};
					$scope.erpAddItemForm.$setUntouched();
					$scope.erpAddItemForm.$setPristine();
					$scope.itemMasterEdit = {};
					$scope.erpEditItemForm.$setUntouched();
					$scope.erpEditItemForm.$setPristine();				
					$scope.itemMasterEdit    = result.itemDetailList;
					$scope.itemName          = result.itemDetailList.item_name;
					$scope.itemMasterEdit.item_cat_id  = {
						selectedOption: { id: result.itemDetailList.item_cat_id} 
					};
					$scope.itemMasterEdit.item_unit  = {
						selectedOption: { unit_id: result.itemDetailList.item_unit} 
					};
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
			}).error(function(data, status, headers, config) {
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}
	}
	//****************/editing of item *************************************
	
	//**************** Updating of item *************************************
	$scope.funUpdateItem = function(){
		if(!$scope.erpEditItemForm.$valid)return; 
		$scope.loaderShow();
		$http.post(BASE_URL + "items/update-item", { 
		    data: {formData:$(erpEditItemForm).serialize() },
		}).success(function (result, status, headers, config) {		
			if(result.error == 1){
				$scope.funGetItems();
				$scope.successMsgShow(result.message);					
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}); 
	}
	//*************** /Updating of item *************************************
	
	//**************** viewing of item *************************************
	$scope.funViewItem = function(itemId){		
		if(itemId){
			$scope.hideAlertMsg();		
			$http({
				url: BASE_URL + "items/view-item/"+itemId,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){				
					$scope.listItemFormDiv 	  	= true;
					$scope.addItemFormDiv 	  	= true;			
					$scope.editItemFormDiv 	  	= true;
					$scope.viewItemFormDiv 	  	= false;
					$scope.itemId       		= result.itemDetailList.item_id;
					$scope.ItemImage	       	= result.itemDetailList.item_image;
					$scope.itemCategory       	= result.itemDetailList.item_cat_name;
					$scope.itemCode      	  	= result.itemDetailList.item_code;
					$scope.itemName           	= result.itemDetailList.item_name;
					$scope.itemBarcode        	= result.itemDetailList.item_barcode;
					$scope.itemDescription    	= result.itemDetailList.item_description;
					$scope.itemLongDescription  = result.itemDetailList.item_long_description;
					$scope.itemTechDescription  = result.itemDetailList.item_technical_description;
					$scope.itemSpecification    = result.itemDetailList.item_specification;
					$scope.itemUnit      		= result.itemDetailList.unit_name;
					$scope.itemShelfLifeDays    = result.itemDetailList.shelf_life_days;
					$scope.itemPerishable    	= result.itemDetailList.is_perishable ? 'Yes' : 'No';
					$scope.itemCreatedAt        = result.itemDetailList.created_at;					
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
			}).error(function(data, status, headers, config) {
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}
	}
	//****************/viewing of item *************************************
	
	//**************** Deleting of item *************************************
	$scope.funDeleteItem = function(item_id){
		
		if(item_id){
			$scope.hideAlertMsg();
			$scope.loaderShow();
			$http({
				url: BASE_URL + "items/delete-item/"+item_id,
				method: "GET",
			}).success(function(result, status, headers, config){				
				if(result.error == 1){
					$scope.funGetItems();					
					$scope.successMsgShow(result.message);					
				}else{
					$scope.errorMsgShow(result.message);
				}				
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config){
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});	
		}
	}
	//************** /Deleting of item *************************************
	
	//**************** Uploading of item Image *******************************
	$(document).on('change', '.uploadItemImage', function(e){
        
		e.preventDefault();
        var itermId = this.id;        
        var _this   = $(this);
        var data    = new FormData();
        data.append('item_image', _this[0].files[0]);
        data.append('item_id', itermId);
		
        $.ajax({
            url: BASE_URL + "items/upload-item-image",
            type: "POST", 
            data: data,
            contentType: false,
            cache: false,  
            processData:false,
            success: function(result){				
                if(result.error == 1){					
					$scope.funGetItems();
					$scope.successMsgShow(result.message);					
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
            }
        });
    });
	//**************** /Uploading of item Image **********************************
	
	//**************** Deleting of item *************************************
	$scope.funRemoveItemImage = function(item_id){
		
		if(!$scope.showConfirmMessage('Are you sure you want to remove this Item Image?'))return;			
		$scope.hideAlertMsg();
		
		if(item_id){
			$http({
				url: BASE_URL + "items/delete-item-image/"+item_id,
				method: "GET",
			}).success(function(result, status, headers, config){				
				if(result.error == 1){					
					$scope.funGetItems();
					$scope.successMsgShow(result.message);					
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
			}).error(function(data, status, headers, config){
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.clearConsole();
			});	
		}
	}
	//************** /Deleting of item *************************************
			
});