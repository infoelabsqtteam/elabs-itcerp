app.controller('requisitionController', function($scope,$q, $timeout,$filter, $http, BASE_URL, $ngConfirm){ 
	
	//define empty variables
	$scope.requisitiondata = '';	
	$scope.sortType     = 'req_slip_no';    // set the default sort type
	$scope.sortReverse  = false;             // set the default sort order
	$scope.searchFish   = '';    			 // set the default search/filter term
	$scope.addFormDiv=true;
	$scope.listRequisition=false;
	$scope.editFormDiv=true;	
	$scope.viewRequisitionDiv=true;
	$scope.saveButton=true;
	$scope.UpdateButton=false;
	$scope.addFormHeading = "Add Requisition Slip";
	$scope.edit_MRS_inputs = false;
	$scope.add_MRS_inputs = true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.resultItems      = [];
	
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
	
	//**********Clearing Console********************************************
	$scope.clearConsole = function(){
		//console.clear();
	};
	//*********/Clearing Console********************************************
	
	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 		 = true;
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
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(id,divisionID){
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
						$scope.deleteRequisition(id,divisionID);
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
	
	/*****************display departments code dropdown start*****************/	
	$scope.departmentList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'requisition/departments-list'
	}).success(function (result) {
		if(result.departmentList){
			$scope.departmentList = result.departmentList;
		}
		$scope.clearConsole();
	});
	/*****************display departments code dropdown end*****************/
	/*****************display division code dropdown start*****************/	
	$scope.divisionsList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			if(result){
				$scope.divisionsList = result;
			}
			$scope.clearConsole();
	});
	/*****************display division code dropdown end*****************/
	
	/*****************display division code dropdown end*****************/
	$http({
		method: 'POST',
		url: BASE_URL +'employeeList'
	}).success(function(result) {
		if(result.employeeList){
			$scope.employeeList = result.employeeList;
		}
		$scope.clearConsole();
	});
	/*****************display division code dropdown end*****************/
	
	//*****************desc on change item dropdown*********************
	$scope.funGetDivisionWiseEmp = function(division_id){  
		if(division_id){ 	
			$http({
				method: 'GET',
				url: BASE_URL +'employee/branch-wise-list/'+division_id
			}).success(function (result) { 
				if(result.employeeList){
					$scope.employeeList = result.employeeList;
				}
				$scope.clearConsole();
			}); 
		}
	};
	//****************/desc on change item dropdown********************

	//*****************desc on change item dropdown*********************
	$scope.funGetItemDescOnChange = function(index,item_id){ 
		if(item_id!=0){ 	
			$http({
				method: 'GET',
				url: BASE_URL +'get_item_desc/'+item_id
			}).success(function (result) { 
				if(result.itemDesc){
					angular.element('textarea#description_'+index).val(result.itemDesc);
				}else{
					angular.element('textarea#description_'+index).val('');
				}
				$scope.clearConsole();
			}); 
		}
	};
	//****************/desc on change item dropdown********************
	
	/*****************add more row*****************/
	$scope.MRS_inputs = [];
    $scope.MRS_inputs.push($scope.nativeLanguage);
    $scope.addRow = function(len=1){
        var mutipleRows=$('#mutipleRows').val();
		if(mutipleRows){ len = mutipleRows; }
		for(j= 1; j <=len; j++) {
			var newData = {
			  level: $scope.level,
			  name: $scope.name,
			  remark: $scope.remark
			};
			$scope.MRS_inputs.push(newData);
		}
		$('#mutipleRows').val('');
    };
	
    $scope.deleteRow = function(rowNo) {  
	   if(rowNo!=0){
		    var slip_dtl_id =$('#req_slip_dlt_id'+rowNo).val();
			if(slip_dtl_id){
				var deleteConfirm = confirm("Are you sure you want to delete this record permanently!");
				if(deleteConfirm==true){
					$scope.deleteRequisitionDetail(slip_dtl_id);
					$scope.MRS_inputs.splice(rowNo, 1);
				}else{
					return false;
				}		
			}else{
				$scope.MRS_inputs.splice(rowNo, 1);
			} 
	   }
    };
	
 	$scope.checkkey = function (event){
		 if(event.keyCode=='9'){  
			 $scope.addRow();	  
		  }  
	};
	/*****************add more row end*****************/
	
	//****************generate indent number*************************************
	$scope.funGetRequisitionNumber = function(){ 
			$http({
				method: 'GET',
				url: BASE_URL +'requisition/get-requisition-number/MRS'
			}).success(function (result) {
				if(result.RequisitionNumber){
					$scope.RequisitionNumber = result.RequisitionNumber;
				}
				$scope.clearConsole();
			});
	};
	//*********************/generate indent number*********************************
	
	/***************** requisition SECTION START HERE *****************/	
	//function is used to generate new requisition slip
    $scope.addRequisition = function(divisionId){
    	if(!$scope.requisitionForm.$valid) return;
		$scope.loaderShow();
        $http.post(BASE_URL + "requisition/add-requisition", {
            data: {formData:$(requisitionForm).serialize() },
        }).success(function (responseData, status, headers, config){ 
			    var resData=responseData.returnData;
				if(resData.success){ 
					 $scope.getRequisitions(divisionId);
					 $scope.funGetRequisitionNumber();
					 $scope.hideAddForm();				
					 $scope.successMsgShow(resData.success);
					 $scope.requisition={};
					 $scope.requisitionForm.$setUntouched();
					 $scope.requisitionForm.$setPristine();					 
				}else{
					$scope.errorMsgShow(resData.error);
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
	
	//code used for sorting list order by fields 
	$scope.predicate = 'req_slip_no';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list of requisition slips	
	$scope.getRequisitions = function(divisionId){  
		$scope.divisionID = divisionId;
		$http.post(BASE_URL + "requisition/get-requisitions/"+divisionId, {
        }).success(function (data, status, headers, config) {
			if(data.requisitionsList){
				$scope.requisitiondata = data.requisitionsList;
			}
			$scope.clearConsole();
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
        });
    };	
	
	// Delete requisition from the database
	$scope.deleteRequisition = function(slip_id, divisionID){ 
		if(slip_id){			
			$scope.loaderShow();
			$http.post(BASE_URL + "requisition/delete-requisition", {
				data: {"_token": "{{ csrf_token() }}","id": slip_id }
			}).success(function (resData, status, headers, config) {
				if(resData.success){  
					 $scope.getRequisitions(divisionID);
					 $scope.successMsgShow(resData.success);
				}else{
					$scope.errorMsgShow(resData.error);
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
	
	//Delete requisition detail from the database
	$scope.deleteRequisitionDetail = function(slip_dtl_id){ 
		if(slip_dtl_id){ 
			$http.post(BASE_URL + "requisition/delete-requisition-detail", {
				data: {"_token": "{{ csrf_token() }}","id": slip_dtl_id }
			}).success(function (resData, status, headers, config) {
				if(resData.success){   
					 $scope.successMsgShow(resData.success);
				}else{
					$scope.errorMsgShow(resData.error);
				}
				$scope.clearConsole();	
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.clearConsole();
			});
		}
    }; 
	
	//edit an requisition and its data
	$scope.funEditRequisition = function(id){
		if(id){
			$scope.editRequisitionID=id;
			$http.post(BASE_URL + "requisition/edit-requisition", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config){	
					var reqhdr = data.returnData.reqhdr;  											
					var reqDtl = data.returnData.reqDtl;    
					if(reqhdr){ 
						$scope.MRS_inputs = [];
						$scope.edit_MRS_inputs = true;
						$scope.add_MRS_inputs = false;
						$scope.editRequisition = reqhdr;
						$scope.editRequisition.req_department_id = {
							selectedOption: { req_department_id: reqhdr.req_department_id} 
						};
						$scope.editRequisition.req_by = {
							selectedOption: { id: reqhdr.req_by} 
						};
						$scope.editRequisition.division_id = {
							selectedOption: { id: reqhdr.division_id} 
						};
						$scope.MRS_inputs=reqDtl; 													
						$scope.funEditRequisitionForm();	 				
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
		}
    };
	
	// update requisition and its data
	$scope.updateRequisition = function(RequisitionID,divisionID){	
    	if(!$scope.editRequisitionForm.$valid) return;	
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "requisition/update-requisition", { 
            data: {formData:$(editRequisitionForm).serialize() },
        }).success(function (resData, status, headers, config) { 
			if(resData.success){
				 $scope.funEditRequisition(RequisitionID);
				 //$scope.getRequisitions(divisionID);					
				 $scope.successMsgShow(resData.success);
			}else{
				$scope.errorMsgShow(resData.error);
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
	
	$scope.addRequisitionForm = function(){
		$scope.edit_MRS_inputs = false;
		$scope.add_MRS_inputs = true;
		$scope.MRS_inputs = [];
		$scope.addRow();
		$scope.addFormHeading = "Add Requisition Slip";
		$scope.saveButton=true;
		$scope.UpdateButton=false;
		$scope.listRequisition=true;
		$scope.addFormDiv=false;
		$scope.editFormDiv=true;	
		$scope.hideAlertMsg();
		$scope.resetForm();		
	};	
	$scope.funEditRequisitionForm = function(){
		$scope.addFormHeading = "Edit Requisition Slip";
		$scope.saveButton=false;
		$scope.UpdateButton=true;
		$scope.listRequisition=true;
		$scope.addFormDiv=true;
		$scope.editFormDiv=false;	
		//$scope.hideAlertMsg();	
	};	
	
	$scope.hideAddForm = function(){
		$scope.MRS_inputs = [];
		$scope.requisition={};
		$scope.requisitionForm.$setUntouched();
		$scope.requisitionForm.$setPristine();		
		$scope.editRequisition={};
		$scope.editRequisitionForm.$setUntouched();
		$scope.editRequisitionForm.$setPristine();		
		$('.descrip').val('');
		$('.Qty').val('');
		$scope.listRequisition=false;
		$scope.addFormDiv=true;
		$scope.editFormDiv=true;	
		$scope.viewRequisitionDiv=true;
		$scope.hideAlertMsg();	
	};
	
	$scope.resetForm = function(){ 
        $('.descrip').val('');
		$('.Qty').val('');	
		$scope.requisition={};
		$scope.requisitionForm.$setUntouched();
		$scope.requisitionForm.$setPristine();		
		$scope.editRequisition={};
		$scope.editRequisitionForm.$setUntouched();
		$scope.editRequisitionForm.$setPristine();	
	};

	/*****************requisition SECTION END HERE *****************/
	$scope.getMatches = function(searchText){
		var deferred = $q.defer();
		$timeout(function(){
			var items = $scope.getItems(searchText.toUpperCase()); 
			if(typeof items != 'undefined'){
				deferred.resolve(items);
			}
		},1000);
		return deferred.promise;
	};
	$scope.getItems = function(searchText){
		$http({
			method: 'GET',
			url: BASE_URL +'requisition/get-items-list/'+searchText
		}).success(function (result){	
			$scope.resultItems = result.itemsList;
			$scope.clearConsole();
		}); 
		return $scope.resultItems;	 
	};
	
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
}).directive('selectLast', function () {
    return {
        restrict: 'A',
        transclude: true,
        templateUrl: 'get_MRS_inputs',
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


