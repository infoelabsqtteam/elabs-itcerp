app.controller('inquiryController', function($scope, $http, BASE_URL,$ngConfirm) {
	//define empty variables
	$scope.inquiryReportdata = '';
	$scope.inquiryFolloupdata = '';
	$scope.IsViewReportFilter = false;
	$scope.inquiryList = true;
	$scope.searchInquiryInput = true;
	$scope.successMessage 		= '';
	$scope.errorMessage   		= '';
	$scope.successMessagePopup 	= '';
	$scope.errorMessagePopup 	= '';	
	$scope.defaultMsg  	    	= 'Oops ! Sorry for inconvience server not responding or may be some error.';
	$scope.inquirydataPaginate=false;
	$scope.inquiryFolloupdataPaginate=false;
	
	$scope.currentInquiryID='';
	$scope.currentInquiryNumber='';
	$scope.currentInquiryStatus='';
	
	//**********scroll to top function**********
	$scope.moveToMsg=function(){ 
		$('html, body').animate({
			scrollTop: $(".alert").offset().top
		},500);
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
	
	//**********If DIV is hidden it will be visible and vice versa*************
	$scope.IsVisiableSuccessMsg 	 = true;
	$scope.IsVisiableErrorMsg 		 = true;
	$scope.IsVisiableSuccessMsgPopup = true;
	$scope.IsVisiableErrorMsgPopup 	 = true;
	$scope.IsVisiableErrorMsgPopup_edit	 = true;
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
	
	//**********successMsgShow**************************************************
	$scope.successMsgShowPopup = function(message){
		$scope.successMessagePopup 		= message;				
		$scope.IsVisiableSuccessMsgPopup = false;
		$scope.IsVisiableErrorMsgPopup 	= true;
		$scope.moveToMsg();
	}
	//********** /successMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShowPopup = function(message){
		$scope.errorMessagePopup 		 = message;
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = false;
		$scope.moveToMsg();
	}
	//********** /errorMsgShow************************************************
	
	//**********errorMsgShow**************************************************
	$scope.errorMsgShowPopup_edit = function(message){
		$scope.errorMessagePopup_edit 		 = message;
		$scope.IsVisiableErrorMsgPopup_edit 	 = false;
		$scope.moveToMsg();
	}
	//********** /errorMsgShow************************************************
	
	//**********hide Alert Msg*************
	$scope.hideAlertMsgPopup = function(){
		$scope.IsVisiableSuccessMsgPopup = true;
		$scope.IsVisiableErrorMsgPopup 	 = true;
		$scope.IsVisiableErrorMsgPopup_edit = true;
	}
	//**********hide Alert Msg**********************************************
	
	/*****************display customer name in dropdown start*****************/	
	$scope.customerList = [];
	$http.post(BASE_URL + "inquiry/get-customers", {
		//status: status, prod_id:prodID, cat_id:catID
	}).success(function (data, status, headers, config) {
		$scope.customerList = data;
		$scope.clearConsole();
	}).error(function (data, status, headers, config) {
		console.log(' :( Error : '+status);		
		if(status == 500 || status == 400 || status == 401){
			 $scope.errorMsgShowPopup($scope.defaultMsg);
		}
	});	
	/*****************display customer name in dropdown end *****************/
	
	/*****************display employeeList name in dropdown start*****************/	
	$scope.employeeList = [];
	$http({
		method: 'POST',
		url: BASE_URL +'employeeList'
	}).success(function (result) {
		if(result.employeeList){
			$scope.employeeList = result.employeeList;
		}
		$scope.clearConsole();
	});	
	/*****************display employeeList name in dropdown end *****************/
	
	/*****************display followupsMode statusList name in dropdown start*****************/	
	$scope.followupsMode = {
		followupdsModeTypeOptions: [
		  {id: 'visit', name: 'Visit'},
		  {id: 'phone', name: 'Phone'},
		  {id: 'email', name: 'Email'},
		  {id: 'other', name: 'Other'},
		]
	};
	
	/*****************display statusDropdown  in dropdown start*****************/	
	$scope.statusDropdown = {
		Options: [
		  {id: 'open', name: 'Open'},
		  {id: 'closed', name: 'Closed'},
		  {id: 'won', name: 'Won'},
		],
		selectedOption: {id: ''} 
	};
	
	/***************** InquiryE SECTION START HERE *****************/		
    $scope.inquiry = {
      name: '',
      email: '',
      userPassword: '',
      confirmPassword: '',
      isSale: '',
    };
	
	$scope.addNewInquiry= function(){
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		$('#add_new').modal('show');
	}
	//function is used to call the 
    $scope.addInquiry = function(){
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
    	if(!$scope.inquiryForm.$valid)
      	return;
        $scope.loaderShow();	
		// post all form data to save
        $http.post(BASE_URL + "inquiry/add-inquiry", {
            data: {formData:$("#add_inquiry_form").serialize() },
        }).success(function (data, status, headers, config) {
			if(data.success){
				//reload the all inquiry
				$scope.getInquiry();	
				$scope.inquiry = {};	
				$scope.inquiryForm.$setUntouched();
				$scope.inquiryForm.$setPristine();					
				$('#add_new').modal('hide');
				$scope.successMsgShow(data.success);
			}else{
				$scope.errorMsgShowPopup(data.error);
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '400'){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.loaderHide();
			$scope.clearConsole();
        });
    }
	
	//function is used to fetch the list of inquiry 	
	$scope.getInquiry = function()
    { 
		$scope.inquirydataPaginate=true;
		$scope.inquiryFolloupdataPaginate=false;
		$scope.inquiryFolloupdata='';
		
		$scope.currentInquiryID='';
		$scope.currentInquiryNumber='';
		$scope.currentInquiryStatus='';
		
		$http.post(BASE_URL + "inquiry/get-inquiry", {
            //status: status, prod_id:prodID, cat_id:catID
        }).success(function (data, status, headers, config) {
			//check if return any inquiry list 
			if(data.inquiryList){
				$scope.inquirydata = data.inquiryList;				
			}
			$scope.clearConsole();
        }).error(function (data, status, headers, config) {
			if(status == 500 || status == 400 || status == 401){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
        });
    }
	
	//function is used to fetch the list of inquiry 	
	$scope.getStatusWiseListInquiry = function(current_status)
    { 
		$http.post(BASE_URL + "inquiry/get-inquiry", {
            data: { 'current_status':current_status },
        }).success(function (data, status, headers, config) {
			//check if return any inquiry list 
			if(data.inquiryList){
				$scope.inquirydata = data.inquiryList;				
			}
			$scope.clearConsole();
        }).error(function (data, status, headers, config) {
			if(status == 500 || status == 400 || status == 401){
				$scope.errorMsgShow($scope.defaultMsg);
			}
			$scope.clearConsole();
        });
    };
	
	//function is used to add the followup in inquiry
    $scope.addFollowupShow = function(id,inquiry_no,inquiry_status){   
		$('#add_followup').modal('show'); 
		$http.post(BASE_URL + "inquiry/get-previous-inquiry", {
			data: { 'inq_id':id },
		}).success(function (data, status, headers, config) { console.log(data.responseData);  			  
			  $scope.previousData = data.responseData;	
			  $scope.clearConsole();		
		}).error(function (data, status, headers, config) {
			if(status == 500 || status == 400 || status == 401){
				$scope.errorMsgShowPopup($scope.defaultMsg);
			}
			$scope.clearConsole();
		});
	}
    $scope.addFollowup = function(id,inquiry_no,inquiry_status){  
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		if(!$scope.followupForm.$valid)
      	return;
		$scope.loaderShow();	
		//post all form data to save
        $http.post(BASE_URL + "inquiry/add-followup", {
            data: {formData:$(followupForm).serialize() },
        }).success(function (data, status, headers, config) {	
			if(data.success){  		
				$('#add_followup').modal('hide');
				$scope.successMsgShowPopup(data.success);
				$scope.resetAddFollowup();	
				$scope.renderInquiryFolloups(id,inquiry_no,inquiry_status);	
			}else{ 
				$scope.errorMsgShowPopup_edit(data.error);						
			}
			$scope.loaderHide();
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == 500 || status == 400 || status == 401){
				$scope.errorMsgShowPopup_edit($scope.defaultMsg); 			
			}
			$scope.loaderHide();
			$scope.clearConsole();
        });
    }
	$scope.resetAddFollowup=function(){	 
		$scope.followup_date=null;
		$scope.followup_detail=null;
		$scope.followup_by = {
			selectedOption: {id: ''} 
		};
		$scope.followupsMode = {
		followupdsModeTypeOptions: [
			  {id: 'visit', name: 'Visit'},
			  {id: 'phone', name: 'Phone'},
			  {id: 'email', name: 'Email'},
			  {id: 'other', name: 'Other'},
			],
			selectedOption: {id: ''} 
	   }; 
		$scope.followupForm.$setUntouched();
		$scope.followupForm.$setPristine();
	}
	//function is used to render the folloups list for an inquiry
    $scope.renderInquiryFolloups = function(id,inquiry_no,inquiry_status){  
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		
		$scope.inquirydataPaginate=false;
		$scope.inquiryFolloupdataPaginate=true;
		$scope.inquirydata = '';	
		
		$scope.currentInquiryID=id;
		$scope.currentInquiryNumber=inquiry_no;
		$scope.currentInquiryStatus=inquiry_status;
		
		$http.post(BASE_URL + "inquiry/get-inquiry-followup", {
            data: {inquiry_id: id },
        }).success(function (data, status, headers, config) {			
			if(data.error){
				$scope.inquiryFolloupdata = '';			 
			}else{ 
				$scope.inquiryFolloupdata = data.folloupsList;
			}
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == 500 || status == 400 || status == 401){
				//$scope.errorMsgShowPopup($scope.defaultMsg);				
			}
			$scope.clearConsole();
        });
    }
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteInquiryFolloup = function(followup_id,id,inquiry_no,inquiry_status){
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
						$scope.deleteInquiryFolloup(followup_id,id,inquiry_no,inquiry_status);
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
	
	//function is used to delete the inquries folloup
    $scope.deleteInquiryFolloup = function(followup_id,id,inquiry_no,inquiry_status){ 
			$scope.loaderShow(); 
			$http.post(BASE_URL + "inquiry/delete-inquiry-followup", {
				data: {followup_id: followup_id },
			}).success(function (data, status, headers, config) {		
				if(data.success){
					$scope.renderInquiryFolloups(id,inquiry_no,inquiry_status);
					$scope.successMsgShowPopup(data.success);
				}else{
					$scope.errorMsgShowPopup(data.error);							
				}
				$scope.loaderHide(); 
				$scope.clearConsole();
			}).error(function (data, status, headers, config){
				if(status == 500 || status == 400 || status == 401){
					$scope.errorMsgShowPopup($scope.defaultMsg);				
				}
				$scope.loaderHide(); 
				$scope.clearConsole();
			});
    }
	
	//**********confirm box******************************************************
	$scope.funConfirmDeleteInquiry = function(id){
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
					action: function (){
						$scope.deleteInquiry(id);
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
	
	//Delete an inquiry from the database
	$scope.deleteInquiry = function(id)
    {
			if(id != ''){
				$scope.loaderShow(); 
				$http.post(BASE_URL + "inquiry/delete-inquiry", {
					data: {"_token": "{{ csrf_token() }}","inquiry_id": id }
				}).success(function (data, status, headers, config) {					
					if(data.success){
						// reload the all inquiry
						$scope.getInquiry();
						$scope.successMsgShow(data.success);
					}else{
						$scope.errorMsgShow(data.error);					
					}
					$scope.loaderHide(); 
					$scope.clearConsole();
				}).error(function (data, status, headers, config) {
					if(status == 500 || status == 400 || status == 401){
						$scope.errorMsgShowPopup($scope.defaultMsg);
					}
					$scope.loaderHide(); 
					$scope.clearConsole();
				});
			}	
			return true;
    };	
	//edit an inquiry and its data
	$scope.editInquiry = function(id)
    {  
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		if(id != ''){
			$http.post(BASE_URL + "inquiry/edit-inquiry", {
				data: {"_token": "{{ csrf_token() }}","inquiry_id": id }
			}).success(function (data, status, headers, config) { 		
				if(data.responseData){
					$scope.inquiry.customer_id = {
						selectedOption: {id: data.responseData.customer_id} 
					};
					$scope.inquiry=	data.responseData; 
					$scope.inquiry_status = {
						Options: [
						  {id: 'open', name: 'Open'},
						  {id: 'closed', name: 'Closed'},
						  {id: 'won', name: 'Won'},
						],
						selectedOption: {id: data.responseData.inquiry_status} 
					};
					$scope.clearConsole();	
					$('#edit_new').modal();
				}else{
					$scope.errorMsgShowPopup(data.error);
				} 
			}).error(function (data, status, headers, config) {
				if(status == 500 || status == 400 || status == 401){
					$scope.errorMsgShowPopup($scope.defaultMsg);				
				}
				$scope.clearConsole();
			});
		}else{
			
		}
    };
	
	//update inquiry status
	$scope.updateInquiry = function(){ 
    	if(!$scope.inquiryEditForm.$valid)
      	return;
		$scope.loaderShow(); 
		// post all form data to save
        $http.post(BASE_URL + "inquiry/update-inquiry", { 
            data: {formData:$(inquiryEditForm).serialize() },
        }).success(function (data, status, headers, config) { 			
			if(data.success){ 
				//reload the all companies
				$scope.getInquiry();
				$('#edit_new').modal('hide');
				$scope.successMsgShowPopup(data.success);
			}else{
				$scope.errorMsgShowPopup(data.error);
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
    };
	
	//edit an inquiry follow up and its data
	$scope.editFollowupInquiry = function(id)
    {  
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
		if(id != ''){
			$http.post(BASE_URL + "inquiry/edit-followup", {
				data: {"_token": "{{ csrf_token() }}","followup_id": id }
			}).success(function (data, status, headers, config) { 			
				if(data.responseData){ 
					$scope.followupsMode = {
						followupdsModeTypeOptions: [
						  {id: 'visit', name: 'Visit'},
						  {id: 'phone', name: 'Phone'},
						  {id: 'email', name: 'Email'},
						  {id: 'other', name: 'Other'},
						],
						selectedOption: {id: data.responseData.mode} 
					}; 
					$scope.followupinquiry_status = {
						Options: [
						  {id: 'open', name: 'Open'},
						  {id: 'closed', name: 'Closed'},
						  {id: 'won', name: 'Won'},
						],
						selectedOption: {id: data.responseData.status} 
					};
					$scope.followup_by = {
						selectedOption: {id: data.responseData.followup_by} 
					};
					$scope.followUp=data.responseData;				
					$('#edit_followup').modal(); 
				}else{
					$scope.errorMsgShowPopup(data.error);
				} 
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == 500 || status == 400 || status == 401){
					$scope.errorMsgShowPopup($scope.defaultMsg);				
				}
				$scope.clearConsole();
			});
		}else{
			
		}
    };
	
	//update inquiry  followup status
	$scope.updateFollowup = function(id,inquiry_no,inquiry_status){
    	if(!$scope.followupEditForm.$valid)
      	return;
		$scope.loaderShow();
		// post all form data to save
        $http.post(BASE_URL + "inquiry/update-inquiry-followup", { 
            data: {formData:$(followupEditForm).serialize() },
        }).success(function (data, status, headers, config) { 
			if(data.success){ 
				//reload the all companies
				if(data.inquiry_status != 'open'){
					$scope.add_followup_btn=false;
				}
				$scope.currentInquiryStatus=data.inquiry_status;
				$scope.renderInquiryFolloups(id,inquiry_no,inquiry_status);
				$('#edit_followup').modal('hide');
				$scope.successMsgShowPopup(data.success);	
			}else{
				$scope.errorMsgShowPopup_edit(data.error);	
			}		
				$scope.loaderHide();
				$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == '500' || status == '404'){
				$scope.errorMsgShowPopup_edit($scope.defaultMsg);
			}
				$scope.loaderHide();
				$scope.clearConsole();
        }); 
    };
	
	$scope.closeFollowupPopup = function(id){ 
		$('#modalPopup').modal('hide');
		$scope.getInquiry();
	}
//---------------------report filter section start here--------------------------------------------
	$scope.filterInquiryFollowups=true;
	$scope.closeFollowup=true;
	$scope.loadersmall 	 = true;				
	$scope.headerFollowupsReports=true;
	$scope.filterInquiryReports=false;
	$scope.IsNotifySelector  = true;
	//sorting variables
	$scope.sortType     = 'inquiry_no';    // set the default sort type
	$scope.sortReverse  = false;         // set the default sort order
	$scope.searchFish   = '';    		 // set the default search/filter term
	
	$scope.predicate = 'inquiry_no';
	$scope.reverse   = true;
	$scope.sortBy = function(predicate) {
		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
		$scope.predicate = predicate;
	};
	//generate inquiry report and its data
	$scope.generateInquiryReport = function()
    {  	
		if(!$scope.inquiryReportForm.$valid)
      	return;
		//$scope.loadersmall 	 = false;
		$scope.loaderShow();
			$http.post(BASE_URL + "inquiry/get-inquiry-report", {
				data: {formData:$(inquiryReportForm).serialize() },
			}).success(function (data, status, headers, config) {      
				if(data.reportData){
					//$scope.loadersmall 	 = true;
					$scope.inquiryReportdata = data.reportData;
					$scope.filterInquiryFollowups=true;
					$scope.inquiryList=false;
					$scope.headerFollowupsReports=true;
					$scope.filterInquiryReports=false;
					$scope.searchInquiryInput = false;					
				}else{
					$scope.inquiryReportdata="";
					//$scope.loadersmall 	 = true;
				}
				$scope.loaderHide();
				$scope.clearConsole();
			}).error(function (data, status, headers, config) {
				if(status == 500 || status == 400 || status == 401){
					$scope.errorMsgShow($scope.defaultMsg);
				}
				$scope.loaderHide();
				$scope.clearConsole();
			});
    };	
	
	$scope.generateInquiryFollowupsReport = function(inquiry_id)
    {
		$scope.hideAlertMsgPopup();
		$scope.hideAlertMsg();
        $http.post(BASE_URL + "inquiry/get-inquiry-followup", {
            data: {'inquiry_id': inquiry_id },
        }).success(function (data, status, headers, config) { 			
			if(data.error){
				$scope.inquiryReportFolloupdata = '';			 
			}else{
				$scope.followupInquiryRepordata = data.inquiryList;
				$scope.inquiryReportFolloupdata = data.folloupsList;
				$scope.filterInquiryFollowups=false;
				$scope.inquiryList=true;
				$scope.closeFollowup=false;
				$scope.headerFollowupsReports=false;
				$scope.filterInquiryReports=true;
			}
			$scope.clearConsole();
        }).error(function (data, status, headers, config){
			if(status == 500 || status == 400 || status == 401){
				//$scope.errorMsgShowPopup($scope.defaultMsg);				
			}
			$scope.clearConsole();
        });
    };	
	$scope.openFilterForm = function(){ 
		if($scope.IsViewReportFilter){
			$scope.IsViewReportFilter = false;
		}else{
			$scope.IsViewReportFilter = true;
		}
	}
	$scope.resetReportForm = function(){ 
		$scope.inquiryList=true;
		$scope.report={};
		$scope.report.customer_id='';
		$scope.inquiryReportForm.$setUntouched();
		$scope.inquiryReportForm.$setPristine();
		$scope.report = {
			customer_id: {id: ''} 
		};
		$scope.report = {
			employee_id: {id: ''} 
		};
		$scope.report = {
			status: {id: ''} 
		};
		$scope.report = {
			mode: {id: ''} 
		};
		angular.element("#customerSelect option").each(function()
		{
			angular.element(this).prop('selected', false);
		});
		angular.element("#employeeSelect option").each(function()
		{
			angular.element(this).prop('selected', false);
		});
		angular.element("#modeSelect option").each(function()
		{
			angular.element(this).prop('selected', false);
		});
		angular.element("#statusSelect option").each(function()
		{
			angular.element(this).prop('selected', false);
		});
	}
	$scope.checkAll = function(name){ 
		if(angular.element("#select_"+name).is(':checked')==true){
			angular.element("#"+name+"Select option").each(function()
			{
				angular.element(this).prop('selected', true);
			});
		}else{
			angular.element("#"+name+"Select option").each(function()
			{
				angular.element(this).prop('selected', false);
			});
		}
	}
	$scope.selectClick = function(name){  
		angular.element("#select_"+name).prop('checked', false);
	}
	
	$scope.closeFollowupReports = function(){
		$scope.filterInquiryFollowups=true;
		$scope.inquiryList=false;
		$scope.headerFollowupsReports=true;
		$scope.filterInquiryReports=false;
		$scope.closeFollowup=true;
		$scope.openFilter=false;
		$scope.IsViewReportFilter = true;
		$scope.IsViewReportFilter = false; 
	}
	$scope.printInquiryReport = function (divName){
		printElement(document.getElementById(divName));		
		var modThis = document.querySelector("#"+divName +" .notifyMe");
		modThis.appendChild(document.createTextNode("new"));		
		window.print();
		angular.element("#printButton").show();
		angular.element("#IsViewReportFilter").show();
		angular.element("#filterInquiryReports").show();
	}
	function printElement(elem){	
		var domClone = elem.cloneNode(true);		
		var $printSection = document.getElementById("printSection");		
		if (!$printSection) {
			var $printSection = document.createElement("div");
			$printSection.id = "printSection";
			document.body.appendChild($printSection);
			angular.element("#printSection").hide();			
		}
		angular.element("#printButton").hide();
		angular.element("#IsViewReportFilter").hide();
		angular.element("#filterInquiryReports").hide();
		$printSection.innerHTML = "";		
		$printSection.appendChild(domClone);
	}
})
.directive('confirmPwd', function($interpolate, $parse) {
  return {
    require: 'ngModel',
    link: function(scope, elem, attr, ngModelCtrl) {

      var pwdToMatch = $parse(attr.confirmPwd);
      var pwdFn = $interpolate(attr.confirmPwd)(scope);

      scope.$watch(pwdFn, function(newVal) {
          ngModelCtrl.$setValidity('password', ngModelCtrl.$viewValue == newVal);
      })

      ngModelCtrl.$validators.password = function(modelValue, viewValue) {
        var value = modelValue || viewValue;
        return value == pwdToMatch(scope);
      };

    }
  }
});
app.directive('datepicker', function() {
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
});