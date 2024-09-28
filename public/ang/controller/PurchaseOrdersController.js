app.controller('PurchaseOrdersController', function($scope, $q, $timeout, $filter, $http, BASE_URL, $ngConfirm) {
	
	//define empty variables
	$scope.prodata 	        = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.resultItems      = '';
	$scope.defaultDpoNo     = '';
	$scope.defaultMsg  	    = 'Oops! Sorry for inconvience server not responding or may be some error.';
	var totalQty   			= 0;
	var grossTotal 			= 0.00;
	var grandTotal 			= 0.00;

	//sorting variables
	$scope.sortType     	= 'po_hdr_id';    // set the default sort type
	$scope.sortReverse  	= false;         // set the default sort order
	$scope.searchFish   	= '';    		 // set the default search/filter term
		
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({scrollTop: $(".alert").offset().top},500);
	}
	//**********/scroll to top function**********
	
	//**********If DIV is hidden it will be visible and vice versa*************	
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 	 	 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	$scope.isVisibleListPODiv 	     = false;
	$scope.isVisibleAddPODiv         = true;
    $scope.isVisibleEditPODiv        = true;
	$scope.isVisibleViewPODiv 	     = true;
	//**********/If DIV is hidden it will be visible and vice versa**********
	
	//**********successMsgShow***********************************************
	$scope.successMsgShow = function(message){
		$scope.successMessage 		= message;				
		$scope.IsVisiableSuccessMsg = false;
		$scope.IsVisiableErrorMsg 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShow**********************************************
	
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
	
	//**********successMsgShowPopup*******************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		= message;				
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShowPopup******************************************
	
	//**********errorMsgShowPopup**********************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	}
	//********** /hideAlertMsgPopup*********************************************
	
	//**********hideAlertMsgPopup*************
	$scope.hideAlertMsgPopup = function(){
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
	}
	//********** /hideAlertMsgPopup********************************************
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id,division_id,dpo_po_type){
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
						$scope.funDeleteDpoOrPO(id,division_id,dpo_po_type);
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
	
	//**********confirm box****************************************************
	$scope.showConfirmMessage = function(msg){
		if(confirm(msg)){
			return true;
		}else{
			return false;
		}
	}
	//********** /confirm box**************************************************
    
    //**********loader show****************************************************
	$scope.loaderShow = function(){
        angular.element('#global_loader').fadeIn();
	}
	//**********/loader show**************************************************
    
    //**********loader show***************************************************
	$scope.loaderHide = function(){
        angular.element('#global_loader').fadeOut();
	}
	//**********/loader show**************************************************
	
	//**********navigate Form*************************************************
	$scope.navigateItemPage = function(){
		if(!$scope.isVisibleListPODiv){
            $scope.isVisibleListPODiv = true;
			$scope.isVisibleAddPODiv  = false;
			$scope.isVisibleEditPODiv = true;
			$scope.isVisibleViewPODiv = true;
        }else{
			$scope.isVisibleListPODiv = false;
			$scope.isVisibleAddPODiv  = true;
			$scope.isVisibleEditPODiv = true;
			$scope.isVisibleViewPODiv = true;
		}
		$scope.hideAlertMsg();
	}
	//**********/navigate Form*********************************************
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		//console.clear();
	}
	//*********/Clearing Console********************************************
	
	//************code used for sorting list order by fields****************
	$scope.predicate = 'po_hdr_id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate){
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
	//*****************/display division dropdown end*************************
	
	//*****************display Vendor dropdown********************************
	$scope.funGenerateDraftPONumber = function()
	{
		$http({
				method: 'GET',
				url: BASE_URL +'purchase-orders/generate-purchase-order-number'
			}).success(function (result) {
				$scope.defaultDpoNo = result.draftPONumber;
				$scope.clearConsole();
		});
	};
	//*****************/display Vendor dropdown*****************
	
	//*****************display PO Types dropdown************************
	$scope.dpoPoTypeList = {
		availableTypeOptions: [
			{id: '1', name: 'PO'},
			{id: '2', name: 'Draft PO'},
		],
		selectedOption: {id: '1', name: 'POs'}
	};
	//*****************/display division dropdown*************************
	
	//*****************Payment Term dropdown************************
	$scope.paymentTermList = {
		availableTypeOptions: [
			{payment_term: 'monthly', payment_term_name: 'Monthly'},
			{payment_term: 'quarterly', payment_term_name: 'Quarterly'},
			{payment_term: 'semi-annually', payment_term_name: 'Semi-Annually'},
			{payment_term: 'annually', payment_term_name: 'Annually'},
		]
	};
	//*****************/Payment Term dropdown*************************
	
	//*****************display Vendor dropdown********************************
	$scope.funGetDivisionWiseVendors = function(division_id)
	{
		$http({
				method: 'GET',
				url: BASE_URL +'vendors/list_all_vendors/'+division_id
			}).success(function (result) {
				$scope.vendorDataList = result.vendorList;
				$scope.clearConsole();
		});
	};
	//*****************/display Vendor dropdown*****************
    
    //*******************Branch Wise Purchase Orders**************************
	$scope.funGetDivisionWisePurchaseOrders = function(division_id,dpo_po_type)
	{
		$scope.divisionID = division_id;
		$scope.dpoPoType  = dpo_po_type;
		$scope.loaderShow();		
		$http({
			method: 'GET',
			url: BASE_URL + "purchase-orders/division-wise-purchase-orders/"+division_id+'/'+dpo_po_type,		
		}).success(function (result, status, headers, config) {
			$scope.purchaseOrderList = result.purchaseOrderList;
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
	};
	//****************/Branch Wise Purchase Orders****************************
	
	//*****************Add more button for Add*********************************
	$scope.po_inputs = [];
    $scope.po_inputs.push($scope.nativeLanguage);
    $scope.addPORow = function(){ 
        var newLanguage = {
          level: $scope.level,
          name: $scope.name,
          remark: $scope.remark
        };
        $scope.po_inputs.push(newLanguage);
    };
	
    $scope.deletePORow = function(rowNo) {  
		if(rowNo){		    
			$scope.po_inputs.splice(rowNo, 1);
			var running=true;			
			setInterval(function(){ if(running){$scope.updateTotalQtyGrossTotal();} running=false;}, 100);		   	   
		}
    };
	
 	$scope.addMoreOnTab = function (event){
		if(event.keyCode=='9'){  
			$scope.addPORow();	  
		}  
	};
	//*****************/Add more button for Add*********************************
	
	//*****************Add more button for Edit*********************************
	$scope.edit_po_inputs = [];
    $scope.edit_po_inputs.push($scope.nativeLanguage);
    $scope.editPORow = function(){ 
        var newLanguage = {
          level: $scope.level,
          name: $scope.name,
          remark: $scope.remark
        };
        $scope.edit_po_inputs.push(newLanguage);
    };
	
    $scope.deleteEditPORow = function(rowNo,po_dtl_id) {  
		if(rowNo){
			if(po_dtl_id){
				$scope.funDeleteDPOPODetail(po_dtl_id,rowNo);
			}else{
				$scope.edit_po_inputs.splice(rowNo, 1);
			}		    
			var editRunning=true;
			setInterval(function(){ if(editRunning){$scope.editUpdateTotalQtyGrossTotal();} editRunning=false;}, 500);	
		}
    };
	
 	$scope.editMoreOnTab = function (event){
		if(event.keyCode=='9'){  
			$scope.editPORow();	  
		}  
	};
	//*****************/Add more button for Edit*********************************
	
	//***************** Auto complete Section **********************************
	$scope.getMatches = function(searchText){
		var deferred = $q.defer();
		$timeout(function(){
			var items = $scope.getItems(searchText.toUpperCase());
			deferred.resolve(items);
		},1000);
		return deferred.promise;
	};
	$scope.getItems = function(searchText){
		$http({
			method: 'GET',
			url: BASE_URL +'purchase-orders/get-items-list/'+searchText
		}).success(function (result) {	
			$scope.resultItems = result.itemsList;
			$scope.clearConsole();
		});
		return $scope.resultItems;		
	};
	//***************** /Auto complete Section ********************************
	
	//*****************desc on change item dropdown*********************
	$scope.funGetItemDescOnChange = function(index,item_id,type){ 		
		if(item_id){
			$http({
				method: 'GET',
				url: BASE_URL +'purchase-orders/get_item_desc/'+item_id
			}).success(function (result){
				if(type){
					angular.element('textarea#description_'+index).val(result.itemDesc);
				}else{
					angular.element('textarea#edit_description_'+index).val(result.itemDesc);
				}				
				$scope.clearConsole();
			}); 
		}		
	}
	//****************/desc on change item dropdown********************
	
	//**********Adding of Branch wise PO********************************
	$scope.updateItemIndividualAmount = function(index){
				
		angular.element('#total_qty').val(0);
		angular.element('#gross_total').val(0);
		angular.element('#grand_total').val(0);
		
		//Updating Item Row
		var basicAmount = angular.element('#item_qty_'+index).val() * angular.element('#item_rate_'+index).val();
		angular.element('#item_amount_'+index).val(basicAmount.toFixed(2));		
		$scope.updateTotalQtyGrossTotal();
	}
	
	//Updating Item Qty and Item Amount
	$scope.updateTotalQtyGrossTotal = function(){
		
		var totalQty   = 0;
		var grossTotal = 0;
						
		angular.element('.itemQty').each(function(){
			if($(this).val()){
				totalQty += parseFloat($(this).val());
			}
		});
		angular.element('.itemAmount').each(function(){
			if($(this).val()){
				grossTotal += parseFloat($(this).val());
			}
		});
		
		angular.element('#total_qty').val(totalQty.toFixed(0));
		angular.element('#gross_total').val(grossTotal.toFixed(2));
		
		//Updating Discount,Excise.Sale tax
		$scope.updateDiscountExciseSaleTax(grossTotal.toFixed(2));		
	};
	$scope.updateDiscountExciseSaleTax = function(grossTotal){
		
		var item_discount = excise_duty_rate = sales_tax_rate = '0.00';
		
		item_discount    = angular.element('#item_discount').val();
		excise_duty_rate = angular.element('#excise_duty_rate').val();
		sales_tax_rate   = angular.element('#sales_tax_rate').val();
		
		var amount_after_discount         = ((item_discount * grossTotal) / 100);
		var amount_after_excise_duty_rate = ((excise_duty_rate * grossTotal) / 100);
		var amount_after_sales_tax_rate   = ((sales_tax_rate * grossTotal) / 100);
		
		var grand_total_step_1 = (grossTotal - amount_after_discount);
		var grand_total_step_2 = (amount_after_excise_duty_rate + amount_after_sales_tax_rate);
		var grand_total        = grand_total_step_1 + grand_total_step_2;
		
		angular.element('#amount_after_discount').val(amount_after_discount.toFixed(2));
		angular.element('#amount_after_excise_duty_rate').val(amount_after_excise_duty_rate.toFixed(2));
		angular.element('#amount_after_sales_tax_rate').val(amount_after_sales_tax_rate.toFixed(2));
		angular.element('#grand_total').val(grand_total.toFixed(2));	
	};
	//**********Adding of Branch wise PO********************************
	
	//**********Editing of Branch wise PO********************************
	$scope.editUpdateItemIndividualAmount = function(index){
		
		angular.element('#edit_total_qty').val(0);
		angular.element('#edit_gross_total').val(0);
		angular.element('#edit_grand_total').val(0);
		
		//Updating Item Row
		var editBasicAmount = angular.element('#edit_item_qty_'+index).val() * angular.element('#edit_item_rate_'+index).val();
		angular.element('#edit_item_amount_'+index).val(editBasicAmount);		
		$scope.editUpdateTotalQtyGrossTotal();
	}
	
	//Updating Item Qty and Item Amount
	$scope.editUpdateTotalQtyGrossTotal = function(){
		
		var editTotalQty   = 0;
		var editGrossTotal = 0;
						
		angular.element('.editItemQty').each(function(){
			if($(this).val()){
				editTotalQty += parseFloat($(this).val());
			}
		});
		angular.element('.editItemAmount').each(function(){
			if($(this).val()){
				editGrossTotal += parseFloat($(this).val());
			}
		});
		
		angular.element('#edit_total_qty').val(editTotalQty.toFixed(0));
		angular.element('#edit_gross_total').val(editGrossTotal.toFixed(2));
		
		//Updating Discount,Excise.Sale tax
		$scope.editUpdateDiscountExciseSaleTax(editGrossTotal.toFixed(2));		
	};
	$scope.editUpdateDiscountExciseSaleTax = function(editGrossTotal){
		
		var edit_item_discount = edit_excise_duty_rate = edit_sales_tax_rate = '0.00';
		
		edit_item_discount    = angular.element('#edit_item_discount').val();
		edit_excise_duty_rate = angular.element('#edit_excise_duty_rate').val();
		edit_sales_tax_rate   = angular.element('#edit_sales_tax_rate').val();
		
		var edit_amount_after_discount         = ((edit_item_discount * editGrossTotal) / 100);
		var edit_amount_after_excise_duty_rate = ((edit_excise_duty_rate * editGrossTotal) / 100);
		var edit_amount_after_sales_tax_rate   = ((edit_sales_tax_rate * editGrossTotal) / 100);
		
		var edit_grand_total_step_1 = (editGrossTotal - edit_amount_after_discount);
		var edit_grand_total_step_2 = (edit_amount_after_excise_duty_rate + edit_amount_after_sales_tax_rate);
		var edit_grand_total        = edit_grand_total_step_1 + edit_grand_total_step_2;
		
		angular.element('#edit_amount_after_discount').val(edit_amount_after_discount.toFixed(2));
		angular.element('#edit_amount_after_excise_duty_rate').val(edit_amount_after_excise_duty_rate.toFixed(2));
		angular.element('#edit_amount_after_sales_tax_rate').val(edit_amount_after_sales_tax_rate.toFixed(2));
		angular.element('#edit_grand_total').val(edit_grand_total.toFixed(2));	
	};
	//**********Adding of Branch wise PO********************************
	
	//**********Editing of Branch wise PO********************************
	$scope.funAddBranchWiseDraftPO = function(division_id,dpo_po_type,save_dpo_po_type){		
    	
		if(!$scope.erpAddBranchWisePOForm.$valid)return;		
		if($scope.newerpAddBranchWisePOFormflag)return;		
		$scope.newerpAddBranchWisePOFormflag = true;
		$scope.loaderShow();
		$http({
			url: BASE_URL + "purchase-orders/create-draft-purchase-order",
			method: "POST",
			data: {formData : 'dpo_po_type='+save_dpo_po_type+'&'+$(erpAddBranchWisePOForm).serialize()}
		}).success(function(data, status, headers, config) {
			$scope.newerpAddBranchWisePOFormflag = false;
			if(data.error == 1){
				$scope.resetForm();
				$scope.funGetDivisionWisePurchaseOrders(division_id,dpo_po_type);
				$scope.funGenerateDraftPONumber();
				$scope.successMsgShow(data.message);
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newerpAddBranchWisePOFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
    }
	//**********/Adding of Branch wise PO********************************
	
	//**********Adding of Branch wise PO********************************
	$scope.funAddBranchWisePO = function(division_id,dpo_po_type,save_dpo_po_type){		
    	
		if(!$scope.erpAddBranchWisePOForm.$valid)return;		
		if($scope.newerpAddBranchWisePOFormflag)return;		
		$scope.newerpAddBranchWisePOFormflag = true;
		$scope.loaderShow();
		$http({
			url: BASE_URL + "purchase-orders/create-purchase-order",
			method: "POST",
			data: {formData : 'dpo_po_type='+save_dpo_po_type+'&'+$(erpAddBranchWisePOForm).serialize()}
		}).success(function(data, status, headers, config) {
			$scope.newerpAddBranchWisePOFormflag = false;
			if(data.error == 1){					
				$scope.funGetDivisionWisePurchaseOrders(division_id,dpo_po_type);
				$scope.funGenerateDraftPONumber();
				$scope.successMsgShow(data.message);
				$scope.resetForm();
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.newerpAddBranchWisePOFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
    }
	//**********/Adding of Branch wise PO********************************
	
	//**********viewing of DPO/PO****************************************
	$scope.funViewDpoOrPO = function(po_hdr_id){		
		$scope.hideAlertMsg();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "purchase-orders/view-purchase-orders/"+po_hdr_id,
			method: "GET",
		}).success(function(result, status, headers, config){
			if(result.error == 1){				
				$scope.isVisibleListPODiv 	 = true;
				$scope.isVisibleAddPODiv 	 = true;			
				$scope.isVisibleEditPODiv 	 = true;
				$scope.isVisibleViewPODiv 	 = false;			
				$scope.viewBranchWiseDPOPOModel = result.poHeaderData;
				$scope.viewOPOFieldEnabled       = 2;
				if(result.poHeaderData.dpo_po_type == '1'){
					$scope.viewOPOFieldEnabled   = 1;
				}
				$scope.viewDPOPOStatus = result.poHeaderData.status;
				$scope.view_po_inputs  = result.poHeaderDetailData;
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide();
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
		});
	};
	//**********/viewing of DPO/PO***************************************
	
	//****************Editing of DPO/PO*************************************
	$scope.funOpenEditDPOForm = function(po_hdr_id)
	{
		if(po_hdr_id){
			$scope.hideAlertMsg();
			$scope.loaderShow();
			$http({
				url: BASE_URL + "purchase-orders/view-purchase-orders/"+po_hdr_id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.isVisibleListPODiv 	 = true;
					$scope.isVisibleAddPODiv 	 = true;			
					$scope.isVisibleEditPODiv 	 = false;
					$scope.isVisibleViewPODiv 	 = true;
					
					//Setting header data in model
					$scope.editBranchWiseDPOPOModel = result.poHeaderData;
					
					//Setting DPO and PO visibility Status in the FORM
					$scope.editOPOFieldEnabled = result.poHeaderData.dpo_po_type;
					
					$scope.funGetDivisionWiseVendors(result.poHeaderData.division_id);
					$scope.editBranchWiseDPOPOModel.division_id  = {
						selectedOption: { division_id: result.poHeaderData.division_id} 
					};
					$scope.editBranchWiseDPOPOModel.vendor_id  = {
						selectedOption: { vendor_id: result.poHeaderData.vendor_id} 
					};
					$scope.editBranchWiseDPOPOModel.payment_term  = {
						selectedOption: { payment_term: result.poHeaderData.payment_term} 
					};					
					//Amending a PO
					if($scope.editOPOFieldEnabled){
						$scope.editBranchWiseDPOPOModel.amendment_no     = result.poHeaderData.po_hdr_id;
						$scope.editBranchWiseDPOPOModel.amendment_date   = null;
						$scope.editBranchWiseDPOPOModel.amendment_detail = null;
					}					
					//Setting header detail data in model
					$scope.edit_po_inputs = result.poHeaderDetailData;
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config) {
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
			});
		}
	}
	//****************/Editing of DPO/PO*************************************
	
	//**************** Updating of DPO/PO *************************************
	$scope.funUpdateBranchWiseDraftPO = function(po_hdr_id,division_id,dpo_po_type){
		
		if(!$scope.erpEditBranchWisePOForm.$valid)return;		
		var formData = $(erpEditBranchWisePOForm).serialize();
		$scope.loaderShow();
		$http({
			url: BASE_URL + "purchase-orders/update-purchase-order",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) { 
			if(result.error == 1){
				$scope.funGetDivisionWisePurchaseOrders(division_id,dpo_po_type);
				$scope.funViewDpoOrPO(po_hdr_id);
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
		}); 
	};
	//*************** /Updating of DPO/PO*************************************
	
	//**********Deleting of DPO/PO***************************************
	$scope.funDeleteDpoOrPO = function(po_hdr_id,division_id,dpo_po_type){
		
		if(po_hdr_id){
			$scope.loaderShow();
			$http({
				url: BASE_URL + "purchase-orders/delete-purchase-orders/"+po_hdr_id,
				method: "GET",
			}).success(function(result, status, headers, config){				
				if(result.error == 1){					
					$scope.funGetDivisionWisePurchaseOrders(division_id,dpo_po_type);
					$scope.successMsgShow(result.message);
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(result, status, headers, config){
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
			});
		}
		return false;
	};
	//**********/Deleting of DPO/PO****************************************
	
	//**********Deleting of DPO/PO detail***************************************
	$scope.funDeleteDPOPODetail = function(po_dtl_id,rowNo){
		
		if(po_dtl_id && confirm('do you really want to delete this record?')){
			$scope.loaderShow();
			$http({
				url: BASE_URL + "purchase-orders/delete-purchase-order-detail/"+po_dtl_id,
				method: "GET",
			}).success(function(result, status, headers, config){				
				if(result.error == 1){
					$scope.edit_po_inputs.splice(rowNo, 1);					
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(result, status, headers, config){
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
			});
		}
		return false;
	};
	//**********/Deleting of DPO/PO detail****************************************
	
	//**********Conversion of DPO to PO****************************************
	$scope.funConvertDPOToPO = function(po_hdr_id,division_id,dpo_po_type){
		
		if(po_hdr_id && confirm('do you really want to convert this Draft PO into PO?')){
			$scope.loaderShow();	
			$http({
				url: BASE_URL + "purchase-orders/convert-dpo-to-purchase-orders/"+po_hdr_id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){				
					$scope.funGetDivisionWisePurchaseOrders(division_id,dpo_po_type);
					console.log(result.new_po_hdr_id);
					$scope.funViewDpoOrPO(result.new_po_hdr_id);				
					$scope.successMsgShow(result.message);
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(data, status, headers, config) {
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
			});
		}
	};
	//**********/Conversion of DPO to PO************************************
	
	//**************** Updating of DPO/PO *************************************
	$scope.funAmendBranchWisePO = function(po_hdr_id,division_id,dpo_po_type){
		
		if(!$scope.erpEditBranchWisePOForm.$valid)return;	
		
		if(po_hdr_id && confirm('do you really want to amend this PO?')){
			var formData = $(erpEditBranchWisePOForm).serialize();
			$scope.loaderShow();
			$http({
				url: BASE_URL + "purchase-orders/amend-purchase-order",
				method: "POST",
				data: {formData :'dpo_po_type=1&'+formData}
			}).success(function (result, status, headers, config) { 
				if(result.error == 1){
					$scope.funGetDivisionWisePurchaseOrders(division_id,dpo_po_type);
					$scope.funViewDpoOrPO(result.new_amend_po_id);
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
			});
		}
		return false;
	}
	//*************** /Updating of DPO/PO*************************************
	
	//**********Deleting of DPO/PO***************************************
	$scope.funFinalizePO = function(po_hdr_id,division_id,dpo_po_type){
		
		if(confirm('do you really want to shortclose this PO?')){
			$scope.loaderShow();
			$http({
				url: BASE_URL + "purchase-orders/finalize-purchase-order/"+po_hdr_id,
				method: "GET",
			}).success(function(result, status, headers, config){				
				if(result.error == 1){					
					$scope.funGetDivisionWisePurchaseOrders(division_id,dpo_po_type);
					$scope.funViewDpoOrPO(po_hdr_id);
					$scope.successMsgShow(result.message);
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function(result, status, headers, config){
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
				$scope.loaderHide();
			});
		}
		return false;
	};
	//**********/Deleting of DPO/PO****************************************
	
	//**********backButton Form*********************************************
	$scope.resetForm = function(){
		$scope.isVisibleListPODiv 	 = false;
		$scope.isVisibleAddPODiv 	 = true;			
		$scope.isVisibleEditPODiv 	 = true;
		$scope.isVisibleViewPODiv 	 = true;
		$scope.addBranchWiseDPOPOModel = {};
		$scope.erpAddBranchWisePOForm.$setUntouched();
		$scope.erpAddBranchWisePOForm.$setPristine();
		$scope.editBranchWiseDPOPOModel = {};
		$scope.erpEditBranchWisePOForm.$setUntouched();
		$scope.erpEditBranchWisePOForm.$setPristine();
		$scope.po_inputs = [];
		$scope.edit_indent_inputs = [];
		$scope.addPORow();
		$scope.editPORow();
		angular.element('#erpAddBranchWisePOForm')[0].reset();
		angular.element('#erpEditBranchWisePOForm')[0].reset();
	}
	//**********/backButton Form********************************************
			
}).directive('datepicker', function() {
  return {
    require: 'ngModel',
    link: function(scope, el, attr, ngModel) {
      $(el).datepicker({
        onSelect: function(dateText) {
          scope.$apply(function() {
            ngModel.$setViewValue(dateText);
          });
        }
      });
    }
  };
}).directive('selectItemFieldsAdd', function () {
    return {
        restrict: 'A',
        transclude: true,
        templateUrl: 'purchase-orders/add-po-inputs',
        replace: true
    };
}).directive( 'elemReady', function( $parse ) {
   return {
       restrict: 'A',
       link: function( $scope, elem, attrs ) {    
          elem.ready(function(){
            $scope.$apply(function(){
                var func = $parse(attrs.elemReady);
                func($scope);
            })
          })
       }
    }
});