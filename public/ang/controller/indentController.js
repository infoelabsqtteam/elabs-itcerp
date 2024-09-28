app.controller('indentController', function($scope,$q, $timeout,$filter, $http, BASE_URL, $ngConfirm) { 
	//define empty variables
	$scope.indentdata      	= '';	
	$scope.sortType        	= 'indent_no';       // set the default sort type
	$scope.sortReverse     	= false;             // set the default sort order
	$scope.addFormHeading  	= "Add Indent";
	$scope.addFormDiv	   	= true;
	$scope.listIndent      	= false;
	$scope.editFormDiv		= true;	
	$scope.edit_indent_inputs 	= false;
	$scope.add_indent_inputs 	= true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.resultItems      	= [];
	
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
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteMessage = function(divisionID,id){
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
						$scope.deleteIndent(divisionID,id);
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
	
	/*****************display division code dropdown start*****************/	
	$scope.divisionsList = [];
	$http({
			method: 'POST',
			url: BASE_URL +'division/get-divisioncodes'
		}).success(function (result) {
			$scope.clearConsole();
			if(result){
				$scope.divisionsList = result;
			}
	});
	/*****************display division code dropdown end*****************/
	
	/*****************display division code dropdown end*****************/
	$http({
			method: 'POST',
			url: BASE_URL +'employeeList'
		 }).success(function(result) {
			$scope.clearConsole();
			if(result.employeeList){
				$scope.employeeList = result.employeeList;
			}
	});
	/*****************display division code dropdown end*****************/
	
	//*****************desc on change item dropdown*********************
	$scope.funGetItemDescOnChange = function(index,item_id){ 
		if(item_id!=0){ 	
			$http({
				method: 'GET',
				url: BASE_URL +'get_item_desc/'+item_id
			}).success(function (result) { 
				$scope.clearConsole();
				if(result.itemDesc){
					angular.element('textarea#description_'+index).val(result.itemDesc);
				}else{
					angular.element('textarea#description_'+index).val('');
				}
			}); 
		}
	};
	//****************/desc on change item dropdown********************
	
	//*****************desc on change item dropdown*********************
	$scope.funGetDivisionWiseEmp = function(division_id){  
		if(division_id){ 	
			$http({
				method: 'GET',
				url: BASE_URL +'employee/branch-wise-list/'+division_id
			}).success(function (result) { 
				$scope.clearConsole();
				if(result.employeeList){
					$scope.employeeList = result.employeeList;
				}
			}); 
		}
	};
	//****************/desc on change item dropdown********************
	
	//****************generate indent number*************************************
	$scope.funGetIndentNumber = function(){ 
		$http({
				method: 'GET',
				url: BASE_URL +'indent/get-indent-number/IN'
			}).success(function (result) {
				$scope.clearConsole();
				if(result.IndentNumber){
					$scope.IndentNumber = result.IndentNumber;
				}
		});
	};
	//*********************/generate indent number*********************************
	
	/*****************add more row*****************/
	$scope.indent_inputs = [];
    $scope.indent_inputs.push($scope.nativeLanguage);
    $scope.addRow = function(len=1){
		var mutipleRows=$('#mutipleRows').val(); 
		if(mutipleRows){ len = mutipleRows; }
		for(j= 1; j <=len; j++) {
			var newData = {
			  level: $scope.level,
			  name: $scope.name,
			  remark: $scope.remark
			};
			$scope.indent_inputs.push(newData);
		}  
    };
	
    $scope.deleteRow = function(rowNo) { 
		if(rowNo!=0){
			var indent_dtl_id =$('#indent_dtl_id'+rowNo).val(); 
			if(indent_dtl_id){
				var deleteConfirm = confirm("Are you sure you want to delete this record permanently!");
				if(deleteConfirm==true){
					$scope.deleteIndentDetail(indent_dtl_id);
					$scope.indent_inputs.splice(rowNo, 1);
				}else{
					return false;
				}		
			}else{
				$scope.indent_inputs.splice(rowNo, 1);
			}
		} 
    };
	
 	$scope.checkkey = function (event){
		 if(event.keyCode=='9'){  
			 $scope.addRow();	  
		  }  
	} 
	/*****************add more row end*****************/
	
	/***************** indent SECTION START HERE *****************/	
	//function is used to generate new indent slip
    $scope.addIndent = function(divisionID){
    	if(!$scope.indentForm.$valid) return;
		$scope.loaderShow();
        $http.post(BASE_URL + "indent/add-indent", {
            data: {formData:$(indentForm).serialize() },
        }).success(function (responseData, status, headers, config){ 
				var resData=responseData.returnData;
				if(resData.success){ 
					 $scope.getIndents(divisionID);
					 $scope.funGetIndentNumber();
					 $scope.hideAddForm();				
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
    }	 
	
	//code used for sorting list order by fields 
	$scope.predicate = 'indent_no';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	
	//function is used to fetch the list of indent slips	
	$scope.getIndents = function(divisionId)
    {  
		$scope.divisionID = divisionId;
		$http.post(BASE_URL + "indent/get-indents/"+divisionId, {
        }).success(function (data, status, headers, config) {
			$scope.clearConsole();
			if(data.indentsList){
				$scope.indentdata = data.indentsList;
			}
        }).error(function (data, status, headers, config) {
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
        });
    };	
	
	// Delete indent from the database
	$scope.deleteIndent = function(divisionID,indent_id){  
		if(indent_id){			
			$scope.loaderShow();
			$http.post(BASE_URL + "indent/delete-indent", {
				data: {"_token": "{{ csrf_token() }}","id": indent_id }
			}).success(function (resData, status, headers, config) {
				$scope.clearConsole();
				if(resData.success){  
					 $scope.getIndents(divisionID);
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
	//Delete indent detail from the database
	$scope.deleteIndentDetail = function(indent_dtl_id)
    { 
		if(indent_dtl_id != ''){ 
				$scope.loaderShow();
				$http.post(BASE_URL + "indent/delete-indent-detail", {
					data: {"_token": "{{ csrf_token() }}","id": indent_dtl_id }
				}).success(function (resData, status, headers, config) {
					$scope.clearConsole();
					if(resData.success){   
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
 	//edit an indent and its data
	$scope.funEditIndent = function(id){
		if(id){
			$scope.editIndentID=id;
			$http.post(BASE_URL + "indent/edit-indent", {
				data: {"_token": "{{ csrf_token() }}","id": id }
			}).success(function (data, status, headers, config){
					var indentHdr = data.returnData.indentHdr;  											
					var indentDtl = data.returnData.indentDtl;    
					if(indentHdr){ 
						$scope.indent_inputs = [];
						$scope.edit_indent_inputs = true;
						$scope.add_indent_inputs = false;
						$scope.editIndent = indentHdr;
						$scope.editIndent.indented_by = {
							selectedOption: { id: indentHdr.indented_by} 
						};
						$scope.editIndent.division_id = {
							selectedOption: { id: indentHdr.division_id} 
						};
						$scope.indent_inputs=indentDtl; 													
						$scope.funEditIndentForm();	 				
					}else{
						$scope.errorMsgShow(data.error);			
					} 
					$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == '500' || status == '400'){
					$scope.errorMsgShow($scope.defaultMsg);
				}
			});
		}
    };
	
	// update indent and its data
	$scope.updateIndent = function(IndentID,divisionID){
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "indent/update-indent", { 
            data: {formData:$(editIndentForm).serialize() },
        }).success(function (resData, status, headers, config) { 
			if(resData.success){
				 $scope.funEditIndent(IndentID);
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
	
	$scope.addIndentForm = function(){
		$scope.edit_indent_inputs = false;
		$scope.add_indent_inputs = true;
		$scope.indent_inputs = [];
		$scope.addRow();
		$scope.addFormHeading = "Add Indent";
		$scope.listIndent=true;
		$scope.addFormDiv=false;
		$scope.editFormDiv=true;	
		$scope.hideAlertMsg();
		$scope.resetForm();		
	};	
	$scope.funEditIndentForm = function(){
		$scope.listIndent=true;
		$scope.addFormDiv=true;
		$scope.editFormDiv=false;
		$scope.addFormHeading = "Edit Indent";		
		//$scope.hideAlertMsg();	
	};	
	$scope.hideAddForm = function(){				
		$scope.listIndent=false;
		$scope.addFormDiv=true;
		$scope.editFormDiv=true;	
		$scope.hideAlertMsg();
		$scope.resetForm();			
	};
	$scope.resetForm = function(){ 	
		$scope.indent={};
		$scope.indentForm.$setUntouched();
		$scope.indentForm.$setPristine();		
		$scope.editIndent={};
		$scope.editIndentForm.$setUntouched();
		$scope.editIndentForm.$setPristine();	
	};
	
	/***************** indent SECTION END HERE *****************/
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
			url: BASE_URL +'indent/get-items-list/'+searchText
		}).success(function(result){	
			 $scope.resultItems = result.itemsList;  
		     $scope.clearConsole();
		});
		return $scope.resultItems;		
	};
	//**************************/indent *******************************/
	
    $scope.clearConsole = function(){
		//console.clear();
	}	
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
        templateUrl: 'get_indent_inputs',
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