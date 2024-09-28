app.controller('settingController', function($scope, $http, BASE_URL,$ngConfirm) {
	
	//set the default search/filter term
	$scope.IsVisiableSuccessMsg=true;
	$scope.IsVisiableErrorMsg=true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.isVisibleAddSettingDiv      = true;
	$scope.isVisibleListSettingDiv     = true;
	$scope.isVisibleEditSettingDiv     = false;
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.editForm 	= {};	 
	$scope.addSettings 	= {};
	
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
	}
	//**********/scroll to top function**********
	//*********code used for sorting list order by fields*****************
	$scope.predicate = 'name';
	$scope.reverse = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//********/code used for sorting list order by fields*****************	
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
	
	/**************** get setting group 04-08-2017************/
	$scope.settingGroupList = [];
    	$http({
    			method: 'POST',
   				url: BASE_URL +'settings/get-setting-group'
   		}).success(function (result) {
   			$scope.settingGroupList = result.settingGroup;
    			$scope.clearConsole();
   	});

   	/******** add default setting from admin end 04-08-2017***********/
	$scope.funAddDefaultSettings = function(){
		$scope.loaderShow();
		$http({
			method: 'POST',
			data: {formData : $(erpAddSettingForm).serialize()},
			url: BASE_URL +'settings/save-default-setting'
   		}).success(function (result) {
   			if(result.error == 1){
   				$scope.funGetGroupWiseSetting($scope.SettingGroupId);
				$scope.successMsgShow(result.message);
				$scope.resetButton();
			}else{
				$scope.errorMsgShow(result.message.error);
			}
			$scope.loaderHide();
		});
   	}
   	/* setting list 04-08-2017*/
   	$scope.getDefaultSettingList = function(){
   		$http({
			method: 'POST',
			url: BASE_URL +'settings/get-default-setting-list'
   		}).success(function (result) {
   			$scope.settingList  = result.settingList;
		});	
   	}

   
   	//******************   confirm box       ************************/
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
						$scope.deleteDefaultSetting(id);
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
	
	//Delete city from the database
	$scope.deleteDefaultSetting = function(setting_id){ 
		if(setting_id){
			$scope.loaderShow(); 
			$http.post(BASE_URL + "settings/delete-default-setting", {
				data: {"_token": "{{ csrf_token() }}","setting_id": setting_id }
			}).success(function (data, status, headers, config){
				if(data.success){
					$scope.isVisibleAddSettingDiv      	= true;
					$scope.isVisibleEditSettingDiv     	= false;
					//reload the settings
					$scope.funGetGroupWiseSetting($scope.SettingGroupId);
					$scope.getDefaultSettingList();
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
    	/*******************   setting group filter 05-08-2017 **************************/ 
	$scope.funGetGroupWiseSetting = function(setting_group_id){
		$scope.SettingGroupId = setting_group_id;
		$http({
			method: 'POST',
			data:{"setting_group_id": setting_group_id },
			url: BASE_URL +'settings/get-group-wise-setting-list'
   		}).success(function (result) {
   			$scope.settingList  = result.settingList;	
			$scope.clearConsole();
		});
	}
	/*******************   /setting group filter 05-08-2017 **************************/ 
	/****************    edit setting records ************************/
	$scope.funEditDefaultSettings = function(setting_id){
		
		$scope.isVisibleEditSettingDiv     = true;
		$scope.isVisibleAddSettingDiv      = false;
		
		$http({
		method: 'POST',
		data:{"setting_id": setting_id },
		url: BASE_URL +'settings/edit-default-setting'
			}).success(function (result) {
			var editFormData  		= result.editSettingForm;
			$scope.editForm.setting_group_id  = {
				selectedOption: { setting_group_id: result.editSettingForm.setting_group_id} 
			};	
			$scope.editForm.setting_key     = editFormData.setting_key;
			$scope.editForm.setting_value 	= editFormData.setting_value;
			$scope.setting_id 		        = editFormData.setting_id;
			$scope.clearConsole();
		});

	}
	/****************    /edit setting records ************************/
	$scope.funUpdateDefaultSetting= function(){
		$scope.loaderShow(); 
		$http({
			method: 'POST',
			data: {formData : $(erpEditSettingForm).serialize()},
			url: BASE_URL +'settings/update-default-setting'
   		}).success(function (result){
			//var setting_group_id =  result.setting_group_id;
   			if(result.error == 1){
   				//reload the settings
				$scope.funGetGroupWiseSetting($scope.SettingGroupId);
				$scope.successMsgShow(result.message);
			}else{
				$scope.errorMsgShow(result.message);
			}
			$scope.loaderHide(); 
			$scope.clearConsole();
		});

	}
   	/***************** reset add form 04-08-2017***********************/
   	$scope.resetButton = function(){
   		$scope.addSettings     = {};
   	}

   	$scope.backButton = function(){
   		$scope.editForm.setting_group_id  	= {};
		$scope.editForm.setting_key     	= {};
		$scope.editForm.setting_value 		= {};
   		$scope.isVisibleAddSettingDiv      	= true;
		$scope.isVisibleEditSettingDiv     	= false;	
   	}
});
