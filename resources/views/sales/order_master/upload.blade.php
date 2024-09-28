<div class="row" ng-hide="IsViewUploadList">

    <!--Header-->
    <div class="header">        
        <div role="new" class="navbar-form navbar-left">            
            <strong id="form_title" title="Refresh" ng-click="funOpenFileUploadWindow(oltdOrderID,oltdOrderNO)">Upload Data : <span ng-bind="oltdOrderNO"></span></strong>
        </div>            
        <div role="new" class="navbar-form navbar-right">
            <div class="nav-custom">
                <button type="button" ng-click="backButton()" class="btn btn-primary" id="add_new_order" type="button">Back</button>
            </div>
        </div>
    </div>
    <!--/Header-->
    
    <!--Seperator-->
    <div class="mT10"></div>
    <!--Seperator-->

    <!--Upload Form/STP Selection-->
    <div class="row order-section">TRF Detail </div>
    <div id="no-more-tables">
        <div class="panel panel-default filterForm">
            <div class="panel-body">					               
                <div class="row">
                    <form method="POST" role="form" enctype="multipart/form-data" id="erpOrderFileUploadForm" name="erpOrderFileUploadForm" novalidate>
                        
                        <div ng-if="{{defined('ADD') && ADD}}">
                            <!--TRF No-->
                            <div class="col-xs-3 form-group">   
                                <label for="oltd_trf_no">TRF No.<em class="asteriskRed">*</em></label>						   
                                <input
                                    type="text"
                                    class="form-control" 
                                    name="oltd_trf_no"
                                    id="oltd_trf_no"
                                    ng-model="orderFileTRFUploadSelection.oltd_trf_no" 
                                    ng-required='true'
                                    placeholder="TRF No.">
                                <span ng-messages="erpOrderFileUploadForm.oltd_trf_no.$error" ng-if='erpOrderFileUploadForm.oltd_trf_no.$dirty || erpOrderFileUploadForm.$submitted' role="alert">
                                    <span ng-message="required" class="error">TRF No. is required</span>
                                </span>
                            </div>				
                            <!--/TRF No-->
                        
                            <!--TRF File Name-->
                            <div class="col-xs-3 form-group">   
                                <label for="oltd_file_name">TRF File Name<em class="asteriskRed">*</em></label>						   
                                <input
                                    type="file"
                                    class="btn btn-default col-xs-12 text-left" 
                                    name="oltd_file_name"
                                    ng-files="getTheFiles($files)"
                                    id="oltd_file_name"
                                    ng-model="orderFileTRFUploadSelection.oltd_file_name">
                                <span ng-messages="erpOrderFileUploadForm.oltd_file_name.$error" ng-if='erpOrderFileUploadForm.oltd_file_name.$dirty || erpOrderFileUploadForm.$submitted' role="alert">
                                    <span ng-message="required" class="error">TRF File Name is required</span>
                                </span>
                            </div>				
                            <!--/STP File Name-->
                            
                            <div class="col-xs-1 form-group text-left mT26">
                                <label for="submit">{{ csrf_field() }}</label>
                                <button type="button" title="Upload TRF" ng-disabled="erpOrderFileUploadForm.$invalid && oltdOrderID" class="btn btn-primary btn-sm" ng-click="funUploadTrfFile(oltdOrderID,oltdOrderNO)">Upload</button>
                            </div>
                        </div>

                        <!--TRF File Name-->
                        <div class="col-xs-5 form-group mT26" ng-if="orderLinkedTrfDtlList.oltd_file_name_link">   
                            <div class="col-xs-3 form-group pull-left"><label>Download TRF File : </label></div>	
                            <div class="col-xs-9 form-group pull-left"><a target="_blank" href="[[orderLinkedTrfDtlList.oltd_file_name_link]]" title="[[orderLinkedTrfDtlList.oltd_trf_no]]" ng-bind="orderLinkedTrfDtlList.oltd_trf_no"></a></div>	  
                        </div>				
                        <!--/STP File Name-->
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Upload Form/STP Selection-->
    
    <!--STP Selection-->
    <div  ng-if="{{defined('ADD') && ADD}}" class="row order-section mT20">STP Detail </div>
    <div  ng-if="{{defined('ADD') && ADD}}" id="no-more-tables">
        <div class="panel panel-default filterForm">
            <div class="panel-body">
                <div class="row mT30">
                    <form class="form-inline" method="POST" role="form" id="erpOrderSTPSelectionForm" name="erpOrderSTPSelectionForm" novalidate>
                        
                        <!--Sample Name-->
                        <div class="col-xs-4 form-group">
                            <label class="left-width" for="order_stp_id">Select STP<em class="asteriskRed">*</em></label>
                            <select 
                                class="form-control left-width"
                                name="olsd_cstp_file_name"
                                ng-model="orderFileStpSelection.olsd_cstp_file_name"
                                ng-change="funGetCustomerStpNoList(orderFileStpSelection.olsd_cstp_file_name.name)"
                                ng-options="custSTPSampleDropDown.name for custSTPSampleDropDown in custSTPSampleDropDownList track by custSTPSampleDropDown.name"
                                id="olsd_cstp_file_name"
                                ng-required="true">
                                <option value="">Select STP</option>
                            </select>
                        </div>
                        <!--/Sample Name-->
                        
                        <!--STP Nos-->
                        <div class="col-xs-4 form-group">
                            <label class="left-width" for="olsd_cstp_id">Select STP No.<em class="asteriskRed">*</em></label>
                            <select 
                                class="form-control left-width"
                                name="olsd_cstp_id"
                                ng-model="orderFileStpSelection.olsd_cstp_id"
                                ng-options="custSTPNoAccToSample.name for custSTPNoAccToSample in custSTPNoAccToSampleNameList track by custSTPNoAccToSample.id"
                                id="olsd_cstp_id"
                                ng-required="true">
                                <option value="">Select STP No.</option>
                            </select>
                        </div>
                        <!--/STP Nos-->

                        <div class="col-xs-2 form-group mT26">
                            <label for="submit">{{ csrf_field() }}</label>
                            <button type="button" title="Save STP" ng-disabled="erpOrderSTPSelectionForm.$invalid && oltdOrderID" class="btn btn-primary btn-sm" ng-click="funSaveOrderStpDetail(oltdOrderID)">Save</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--STP Selection-->
	
</div>