app.controller('modulesController', function($scope, $http, BASE_URL, $ngConfirm) {
	//define empty variables
	$scope.dragDropRowsCount 		= 1000; /** used in $scope.dragoverCallback() function**/
	//define empty variables
	$scope.prodata 	        = '';
	$scope.successMessage 	= '';
	$scope.errorMessage   	= '';
	$scope.selected         = '';
	$scope.defaultMsg  		= 'Oops ! Sorry for inconvience server not responding or may be some error.';

	//sorting variables
	$scope.sortType     			= 'id';          // set the default sort type
	$scope.sortReverse  			= false;         // set the default sort order
	$scope.searchFish   			= '';    		 // set the default search/filter term
	$scope.sortNavigationListHide	= true;	
	$scope.levelOneSorting			= true;
	$scope.GlyphiconPlus			= 'glyphicon-plus';
	$scope.setEditReadOnly          = false;
	
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
	$scope.IsVisiableErrorMsg 	 	 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	$scope.listFormBladeDiv 	 	 = false;
	$scope.addFormBladeDiv 	 	 	 = false;
	$scope.editFormBladeDiv 	 	 = true;
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
	$scope.funConfirmDeleteMessage = function(id,currentModuleID){
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
						$scope.funDeleteModule(id,currentModuleID);
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
	};
	//********** /confirm box****************************************************
	
	//**********navigate Form*********************************************
	$scope.navigatePage = function(){
		if(!$scope.addFormBladeDiv){
			$scope.addFormBladeDiv 	= true;	
			$scope.editFormBladeDiv = false;
		}else{
			$scope.addFormBladeDiv 	= false;			
			$scope.editFormBladeDiv = true;
		}	
		$scope.hideAlertMsg();
	};
	//**********/navigate Form*********************************************
	
	//**********backButton Form*********************************************
	$scope.backButton = function(){
		$scope.addModule = {};
		$scope.erpAddModuleForm.$setUntouched();
		$scope.erpAddModuleForm.$setPristine();
		$scope.editModule = {};
		$scope.erpEditModuleForm.$setUntouched();
		$scope.erpEditModuleForm.$setPristine();
		$scope.navigatePage();
		$scope.moduleLevelList = [
			{module_level: 0, module_level_name: 'Level 0'},
			{module_level: 1, module_level_name: 'Level 1'},
			{module_level: 2, module_level_name: 'Level 2'}
		];
		$scope.funGetModuleCategory();
	};
	//**********/backButton Form*********************************************
	
	//**********backButton Form*********************************************
	$scope.resetButton = function(){
		$scope.addModule = {};
		$scope.erpAddModuleForm.$setUntouched();
		$scope.erpAddModuleForm.$setPristine();
		$scope.editModule = {};
		$scope.erpEditModuleForm.$setUntouched();
		$scope.erpEditModuleForm.$setPristine();
	};
	//**********/backButton Form*********************************************
	
	//************code used for sorting list order by fields*****************
	$scope.predicate = 'id';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//************/code used for sorting list order by fields*****************
	
	//************code used for sorting list order by fields*****************
	$scope.moduleLevelList = [
		{module_level: 0, module_level_name: 'Level 0'},
		{module_level: 1, module_level_name: 'Level 1'},
		{module_level: 2, module_level_name: 'Level 2'}
	];
	//************code used for sorting list order by fields*****************
	
	//************code used for sorting list order by fields*****************
	$scope.moduleStatusList = [
		{module_status: 1, module_status_name: 'Active'},
		{module_status: 0, module_status_name: 'Inactive'}
	];
	//************code used for sorting list order by fields*****************
	
	//*****************display Category dropdown start*****************
	$scope.funGetModuleCategory = function(){
		$http({
			method: 'POST',
			url: BASE_URL +'roles/get-module-category'
		}).success(function (result) {
			$scope.moduleCategoryList = result.moduleCategoryList; 
			$scope.clearConsole();
		});
	};
	$scope.funGetModuleCategory();
	//*****************display Category dropdown end*****************
	
	//**********Getting all module list***********************************
	$scope.funGetModuleList = function(id){
		$scope.searchModules={};
		$scope.currentModuleID = id;
		$http({
			url: BASE_URL + "roles/get-module-list/"+id,
			method: "GET",
		}).success(function(result, status, headers, config){
			$scope.moduleDataList = result.moduleDataList;
			$scope.clearConsole();
		}).error(function(result, status, headers, config) {
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
    };
	//**********/Getting all module list**********************************
	
	/**************multisearch start here**********************/
	$scope.multiSearchTr=true;
	$scope.multisearchBtn=false;
	
	$scope.getMultiSearch = function()
    {  
		$scope.filterModules='';
		$scope.searchModules.search_module_id=$scope.currentModuleID;
		$http.post(BASE_URL + "roles/modules/get-modules-multisearch", {
            data: { formData:$scope.searchModules },
        }).success(function (result, status, headers, config){ 
			$scope.moduleDataList = result.moduleDataList;
			$scope.clearConsole();
		}).error(function (result, status, headers, config){
			if(status == '500' || status == '400'){
				$scope.errorMsgShow($scope.defaultMsg);
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
	    $scope.searchModules={};
	    $scope.filterModules='';
		$scope.funGetModuleList($scope.currentModuleID);
	}
	
	$scope.openMultisearch = function()
    { 
	   $scope.multiSearchTr=false;
	   $scope.multisearchBtn=true;
	}
	/**************multisearch end here**********************/
	
	//***************** Adding of Modules********************************
	$scope.funAddModule = function(currentModuleID){		
		if(!$scope.erpAddModuleForm.$valid)return;		
		if($scope.erpAddModuleFormflag)return;		
		$scope.erpAddModuleFormflag = true;		
		var formData = $(erpAddModuleForm).serialize();
		
		$http({
			url: BASE_URL + "roles/add-new-module",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.erpAddModuleFormflag = false;
			if(data.error == 1){
				$scope.resetButton();				
				$scope.funGetModuleList(currentModuleID);
				$scope.funGetModuleCategory();				
				$scope.successMsgShow(data.message);				
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.erpAddModuleFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Modules ***************************
	
	//**************** editing of Module *************************************
	$scope.funEditModule = function(id,currentModuleID){
		if(id){
			$scope.hideAlertMsg();
			$http({
				url: BASE_URL + "roles/view-module/"+id,
				method: "GET",
			}).success(function(result, status, headers, config){
				if(result.error == 1){
					$scope.resetButton();
					$scope.addFormBladeDiv 	= true;	
					$scope.editFormBladeDiv = false;
					$scope.editModule       = result.moduleDetailList;
					if(result.moduleDetailList.module_level == 0){
						$scope.moduleCategoryList = []; 
					}else{
						$scope.funGetModuleCategory();
					}
					$scope.moduleLevelList = [
						{module_level: result.moduleDetailList.module_level, module_level_name: 'Level '+result.moduleDetailList.module_level}
					];
					$scope.editModule.parent_id  = {
						selectedOption: { id: result.moduleDetailList.parent_id} 
					};
					$scope.editModule.module_level  = {
						selectedOption: { module_level: result.moduleDetailList.module_level} 
					};
					$scope.editModule.module_status  = {
						selectedOption: { module_status: result.moduleDetailList.module_status} 
					};					
				}else{
					$scope.errorMsgShow(result.message);
				}
				$scope.clearConsole();
			}).error(function(data, status, headers, config) {
				if(status == '500' || status == '404'){				
					$scope.errorMsgShowPopup($scope.defaultMsg);
				}
			});
		}
	};
	//****************/editing of Module *************************************
	
	//**************** Updating of Module *************************************
	$scope.funUpdateModule = function(currentModuleID){		
		if(!$scope.erpEditModuleForm.$valid)return;		
		var formData = $(erpEditModuleForm).serialize();
		
		$http({
			url: BASE_URL + "roles/update-module",
			method: "POST",
			data: {formData :formData}
		}).success(function (result, status, headers, config) { 
			if(result.error == 1){				
				$scope.backButton();				
				$scope.funGetModuleList(currentModuleID);
				$scope.funGetModuleCategory();				
				$scope.successMsgShow(result.message);					
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();
		}).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		}); 
	};
	//*************** /Updating of Module *************************************
	
	//**************** Deleting of Branch Wise Store ***************************
	$scope.funDeleteModule = function(id,currentModuleID){
		
		$scope.hideAlertMsg();
		$http({
			url: BASE_URL + "roles/delete-module/"+id,
		}).success(function(result, status, headers, config){				
			if(result.error == 1){
				$scope.funGetModuleList(currentModuleID);				
				$scope.successMsgShow(result.message);					
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();
		}).error(function(data, status, headers, config){
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});	
	};
	//************** /Deleting of Branch Wise Store *******************************
	
	//*****************display all modules at 0 level******************************
	$scope.modulesList = [];
	$scope.getAllModulesList=function(level){
		$http({
				method: 'POST',
				url: BASE_URL +'roles/get-all-modules/'+level
			}).success(function (result) { 
				if(result.moduleList){			  
					$scope.moduleList = result.moduleList;
				}
				$scope.clearConsole();
		});
	};
	$scope.getAllModulesList();
	//*****************display all modules at 0 level********************************
	
	//*****************roles list****************************************************
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
	};
	$scope.getAllRolesList();
	//****************roles list*****************************************************
	
	//*****************display Menu list**********************************************
	$scope.modulesDisabled  = '';
	$scope.getSubModulesMenuItems = function(id,role_id){
		$scope.menuSubmenuList = [];
		if(id && role_id){
			$http({
				method: 'GET',
				url: BASE_URL +'roles/get-menu-submenu-list/'+id+'/'+role_id,
			}).success(function (result) {
				$scope.menuSubmenuList    = result.menuSubmenuList; 
				$scope.selectedModuleList = result.selectedModuleList;
				$scope.modulesDisabled    = role_id == '1' ? id : '0';
			});
		}
	};
	//*****************/display TMenu list*******************************************
	
	//***************** Adding of Modules********************************
	$scope.funSaveModuleNavigation = function(){
		
		if(!$scope.erpNavigationModuleForm.$valid)return;		
		if($scope.erpNavigationModuleFormflag)return;		
		$scope.erpNavigationModuleFormflag = true;		
		var formData = $(erpNavigationModuleForm).serialize();
		
		$http({
			url: BASE_URL + "roles/add-navigation-module",
			method: "POST",
			data: {formData :formData}
		}).success(function(data, status, headers, config) {
			$scope.erpNavigationModuleFormflag = false;
			if(data.error == 1){
				$scope.successMsgShow(data.message);				
			}else{
				$scope.errorMsgShow(data.message);
			}
			$scope.clearConsole();
		}).error(function(data, status, headers, config) {
			$scope.erpNavigationModuleFormflag = false;
			if(status == '500' || status == '404'){				
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
		});
	};
	//**************** /Adding of Modules ***************************
	
	//*****************Checking selected module**********************
	$scope.funCheckCheckedValue = function(dataArray,id){
		angular.forEach(dataArray, function(value, key){
			if(value == id){
				//console.log(value +'--'+id);
				return true;
			}
		});
	};
	//*****************/Checking selected module*********************
	
	//*****************Checking Parent/Children Checkbox*************
	$scope.funParentChildrenChecker = function(menuSubmenuLevelOne){
		menuSubmenuLevelOne.children.forEach(function(e){
			e.selected = menuSubmenuLevelOne.selected;
		});
    };	
	$scope.funChildrenParentChecker = function(evt){
		evt.menuSubmenuLevelOne.selected = true;		
    };
	//*****************/Cheching Parent/Children Checkbox*************
	
	//*****************display navigation list for sorting*************
	$scope.sortNavigationList = function(){
		$scope.getNavigationAllModulesList();
		$scope.navFormBladeDiv=true;		
		$scope.sortNavigationListHide=false;		
    };
	//*****************/display navigation list for sorting*************
	
	//*****************display navigation list for sorting*************
	$scope.backtoNavigationSetting = function(){
		$scope.allModulesList=[];
		$scope.navFormBladeDiv=false;		
		$scope.sortNavigationListHide=true;		
    };
	//*****************/display navigation list for sorting*************
	
	//*****************get all modules with children in tree formate******************************
	$scope.navigationAllModulesList = [];
	$scope.getNavigationAllModulesList=function(){ 
		$http({
				method: 'POST',
				url: BASE_URL +'roles/get-all-modules-list'
			 }).success(function (result) { 
				if(result.allModulesList){			  
					$scope.allModulesList = result.allModulesList;  
					$scope.moduleArr=[];
					$scope.effectType=[]; 
					angular.forEach( $scope.allModulesList, function(data, key){
						if(data.parent_id==0){ 
							var moduleObj = {'name' : data.module_name,'module_id' : data.id,children:data.children};
							$scope.moduleArr.push(moduleObj);
							$scope.effectType.push('move');
						}
					});
					$scope.displayModuleList();
				}
				$scope.clearConsole();
		});
	};
	//*****************get all modules with children in tree formate********************************
	
	//******************************display all modules with children in tree formate****************
	$scope.displayModuleList=function(){
		$scope.model = [[]];
		var mainModuleArr=$scope.moduleArr;
		var effectType = $scope.effectType;
		
		angular.forEach( effectType, function(effect, i){  
		  var modules = { name: mainModuleArr[i].name, id: mainModuleArr[i].module_id, items: [], effectAllowed: effect };		  
          
		  for(var k = 0; k < mainModuleArr[i].children.length; ++k){
			  modules.items.push({label: mainModuleArr[i].children[k].module_name ,id: mainModuleArr[i].children[k].id ,module_sort_by: mainModuleArr[i].children[k].module_sort_by , effectAllowed: effect});
		  }
		  $scope.model[i % $scope.model.length].push(modules);
		});
   };
   //**************************/display all modules with children in tree formate********************	
   
   //******************************save sorted modules list with children****************
	$scope.saveNavigationOrdering=function(){	
	    $scope.loaderShow();
		$http.post(BASE_URL + "roles/save-all-modules-sorted-list", {
            data: $scope.model,
        }).success(function (result, status, headers, config){
			if(result.error == '1'){
				$scope.successMsgShow(result.message);				
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
		}).error(function (result, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
        });
	};
   //**************************/save sorted modules list with children********************
   
   //**************************functions used for sorting*********************************************
   	$scope.dragoverCallback = function(index, external, type, callback) {
        $scope.logListEvent('dragged over', index, external, type);
        //Invoke callback to origin for container types.
        if (type == 'container' && !external){
            //console.log('Container being dragged contains ' + callback() + ' items');
        }
		  //return index < 10;
		  console.log($scope.dragDropRowsCount);
		  return  index <= $scope.dragDropRowsCount;
    };

    $scope.dropCallback = function(index, item, external, type) {
        $scope.logListEvent('dropped at', index, external, type);
        //Return false here to cancel drop. Return true if you insert the item yourself.
        return item;
    }; 

    $scope.logEvent = function(message) {
        //console.log(message);
    };

    $scope.logListEvent = function(action, index, external, type) {
        var message = external ? 'External ' : '';
        message += type + ' element was ' + action + ' position ' + index;
    }; 
	//**************************/functions used for sorting************************************
	
	//**********cancalSave Form*********************************************
	$scope.cancalSave = function(){
		$scope.navigation = {};
		$scope.erpNavigationModuleForm.$setUntouched();
		$scope.erpNavigationModuleForm.$setPristine();
		$scope.menuSubmenuList = [];
	};
	//**********/cancalSave Form*********************************************
	
	$scope.orderType = {
		availableTypeOptions: [
		  {id: 'module_ordering', name: 'Main Menu Ordering'},
		  {id: 'menu_ordering', name: 'Sub Menu Ordering'}
		],
		selectedOption: {id: 'module_ordering', name: 'Main Menu Ordering'},
	};
	
	$scope.getOrderingType=function(id){
		if(id=='module_ordering'){
			$scope.LevelOneSorting();
		}else{
			$scope.LevelTwoSorting();
		}
	};
	
	//**********LevelOneSorting*********************************************
	$scope.LevelOneSorting = function(){
		$scope.levelOneSorting=true;
		$scope.levelTwoSorting=false;
	};
	//**********/LevelOneSorting********************************************
	
	//**********LevelTwoSorting*********************************************
	$scope.LevelTwoSorting = function(){
		$scope.levelTwoSorting=true;
		$scope.levelOneSorting=false;
		if($scope.allModulesList){			  
				$scope.menuModuleArr=[];
				$scope.effectMenuType=[]; 
				angular.forEach( $scope.allModulesList, function(data, key){
					if(data.module_level==1){ 
						var moduleObj = {'name' : data.module_name,'module_id' : data.id,children:data.children};
						$scope.menuModuleArr.push(moduleObj);
						$scope.effectMenuType.push('');
					}
				});
				$scope.displayMenuOrderList();
		}
	};
	//**********/LevelTwoSorting*********************************************
	
	//**********display second level sorting*********************************************
	$scope.displayMenuOrderList = function(){
		$scope.modelLevelTwo = [[]];
		var mainModuleMenuArr=$scope.menuModuleArr;
		var effectMenuType = $scope.effectMenuType;
		
		angular.forEach( effectMenuType, function(effect, i){  
		  var modules = { name: mainModuleMenuArr[i].name, id: mainModuleMenuArr[i].module_id, itemsLevelTwo: [], effectAllowed: effect };		  
          
		  for(var k = 0; k < mainModuleMenuArr[i].children.length; ++k){
			  modules.itemsLevelTwo.push({label: mainModuleMenuArr[i].children[k].module_name ,id: mainModuleMenuArr[i].children[k].id ,module_sort_by: mainModuleMenuArr[i].children[k].module_sort_by , effectAllowed: effect});
		  }
		  $scope.modelLevelTwo[i % $scope.modelLevelTwo.length].push(modules);
		});
	};
	//**********/display second level sorting********************************************
	
	//******************************save sorted modules list with children****************
	$scope.saveNavigationMenuOrdering=function(){	
	    $scope.loaderShow();
		$http.post(BASE_URL + "roles/save-menu-level-two-sorted-list", {
            data: $scope.modelLevelTwo,
        }).success(function (result, status, headers, config){
			if(result.error == '1'){
				$scope.successMsgShow(result.message);				
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
		}).error(function (result, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();		
			$scope.loaderHide();
        });
	};
   //**************************/save sorted modules list with children********************
   
   //************************************accordian on ordering*****************************
   $scope.displayContent=function(ul_id){ 
       if(angular.element('#id_'+ul_id).hasClass('glyphicon-plus')){
		   $scope.GlyphiconPlus='glyphicon-minus';
		   angular.element('#id_'+ul_id).addClass('glyphicon-minus');
		   angular.element('#id_'+ul_id).removeClass('glyphicon-plus');
		   angular.element('#ul_id_'+ul_id).show();
	   }else if(angular.element('#id_'+ul_id).hasClass('glyphicon-minus')){
		   $scope.GlyphiconPlus='glyphicon-plus';
		   angular.element('#id_'+ul_id).addClass('glyphicon-plus');
		   angular.element('#id_'+ul_id).removeClass('glyphicon-minus');
		   angular.element('#ul_id_'+ul_id).hide();
	   }
   };
   //************************************accordian on ordering*****************************
});