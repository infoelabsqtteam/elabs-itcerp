app.controller('modulePermissionsController', function($scope, $http, BASE_URL) {

	//set the default search/filter term
	$scope.IsVisiableSuccessMsg=true;
	$scope.IsVisiableErrorMsg=true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	
	$scope.moduleFormData=[];
	$scope.hideLoader	= true;
	$scope.finalSubmitHide	= true;
	
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
	
	//*********hideAlertMsg*************
	$scope.hideAlertMsg = function(){
		$scope.IsVisiableSuccessMsg = true;
		$scope.IsVisiableErrorMsg 	= true;
	}
	//**********/hideAlertMsg********************************************
	
	/*****************display all modules at 0 level*****************/	
	$scope.modulesList = [];
	$scope.getAllModulesList=function(level){
		if(!angular.isUndefined(level)){
			$http({
					method: 'POST',
					url: BASE_URL +'roles/get-all-modules/'+level
				}).success(function (result) { 
					if(result.moduleList){			  
						$scope.moduleList = result.moduleList;  
					}
					$scope.clearConsole();
			});
		}
	}
	/*****************display all modules at 0 level******************/	
	
	/*****************roles list*****************/	
	$scope.roleList = [];
	$scope.getAllRolesList=function(){
		$http({
				method: 'POST',
				url: BASE_URL +'roles/get-roles-category'
			}).success(function (result) { 
				if(result.roleList){			  
					$scope.roleList = result.roleList;  
				}
				$scope.clearConsole();
		});
	}
	$scope.setCurrentRole=function(role_id){
		
		$scope.getSubModuleMenus(role_id,$scope.moduleIdScope,$scope.subModuleId);//  function call for get modules menus list

		if(!angular.isUndefined(role_id)){ 
			$scope.roleIdScope=role_id;
		}else{
			$scope.roleIdScope='';
		}
		$scope.finalSubmitHide	= true;
	}
	/****************roles list******************/
	
	/*****************subModuleList*****************/	
	$scope.subModuleList = [];
	$scope.getSubModules=function(module_id){

		 if(!angular.isUndefined(module_id)){
			$scope.moduleIdScope=module_id;
			$scope.subModuleList = [];
			$scope.hideLoader	= false;		
			$http({
					method: 'POST',
					url: BASE_URL +'roles/get-sub-modules/'+module_id
				}).success(function (result) { 
					if(result.subModuleList){
						
						$scope.getSubModuleMenus($scope.roleIdScope,$scope.moduleIdScope,$scope.subModuleId);// function call for get modules menus list
						
						$scope.hideLoader	= true;					
						$scope.subModuleList = result.subModuleList;
					}
					$scope.clearConsole();
			});
		 }else{
			//$scope.subModuleMenuList=[];
			$scope.finalSubmitHide	= true;
		}
	}
	/****************subModuleList*****************/
	
	/*****************subModuleMenuList****************/	
	$scope.subModuleMenuList = [];
	$scope.getSubModuleMenus=function(roleIdScope,moduleIdScope,sub_module_id){
		$scope.subModuleId = sub_module_id;
		$scope.labelsArray=[];
	    if(!angular.isUndefined(sub_module_id)){
			$scope.subModuleIdScope=sub_module_id;
			$scope.subModuleMenuList = [];
			$http({
					method: 'GET',
					url: BASE_URL +'roles/get-sub-modules-menue/'+roleIdScope+'/'+moduleIdScope+'/'+sub_module_id
				 }).success(function (result) { 
					if(result.subModuleMenuList){			  
						$scope.subModuleMenuList = result.subModuleMenuList;
						$scope.displayPermissonsList($scope.subModuleMenuList);					
					}else{
						$scope.finalSubmitHide	= true;
					}
					$scope.clearConsole();
					
			});
		}else{
			//$scope.subModuleMenuList=[];
			$scope.finalSubmitHide	= true;
		}
	}
	/***************subModuleMenuList******************/
	
	/****************display menu list*****************/
	$scope.displayPermissonsList=function(subModuleMenuList){
		$scope.labelsArray=[];
		angular.forEach(subModuleMenuList, function(data, key){
		  var dataObj = 
				[
					{
					  label: data.name,
					  id: data.id,
					  selected: data.selected, 
					  options:[
						{ optionValue: 'Add', selected: data.add},
						{ optionValue: 'Edit', selected: data.edit},
						{ optionValue: 'View', selected: data.view},
						{ optionValue: 'Delete', selected: data.delete}
					  ]
					}
				]
			$scope.labelsArray.push(dataObj);
	    });
	}
	/****************display menu list*****************/
	
	/****************check parent label will check all child*****************/
	$scope.checkMenuOptionList = function(label){
      label.options.forEach(function(e){ 
         e.selected = label.selected; 
      });
    };
	/****************check parent label will check all child*****************/
	
	$scope.childClick = function(parent,option){ 
		 parent.selected = true; 
    };
	
	$scope.cancelFun=function(){
		$scope.finalSubmitHide	= true;
		$scope.role_id={};
		$scope.module_id={};
		$scope.sub_module_id={};
		$scope.subModuleMenuList=[];
		$scope.labelsArray=[];
		$scope.roleIdScope='';
		$scope.moduleIdScope = '';
		$scope.subModuleId ='';
	}
	
	$scope.savePermissionsFun=function(){
		if(!$scope.permissionsForm.$valid)
      	return; 
	    $scope.loaderShow();
		$http.post(BASE_URL + "roles/save-module-permissions", {
            data: {formData:$(permissionsForm).serialize() },
        }).success(function (data, status, headers, config){
			if(data.success){
				$scope.department = {};	
				$scope.permissionsForm.$setUntouched();
				$scope.permissionsForm.$setPristine();	
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShow(data.error);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
        });
	}
});
