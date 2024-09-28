<div class="modal fade" id="upload_purchase_order_csv_modal" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" ng-click="resetFileInputData('purchase_order_csv');hideAlertMsgPopup();" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload PO CSV File:</h4>
            </div>

            <!--display Messge Div-->
            @include('includes.alertMessagePopup')
            <!--/display Messge Div-->

            <form method="POST" role="form" enctype="multipart/form-data" id="erpOrderPurchaseOrderCsvUploadForm" name="erpOrderPurchaseOrderCsvUploadForm" novalidate>         
                <div class="modal-body">
                    <label class="width100">Upload <em class="asteriskRed">*</em><span class="pull-right right">Download : <a href="{{ asset('public/sample/itcerp_upload_purchase_order_format.csv') }}" download="itcerp_upload_purchase_order_format.csv">CSV Format</a></span></label>
                    <input 
                        type="file"
                        class="form-control btn btn-default btn-sm" 
                        ng-files="getOrderPurchaseOrderCsvInputTheFiles($files)" 
                        id="purchase_order_csv" 
                        ng-model="orderPurchaseOrderCsvUpload.purchase_order_csv" 
                        name="purchase_order_csv">
                </div>
                <div class="modal-footer">
                    <label for="submit">{{ csrf_field() }}</label>
                    <button type="button" title="Upload File" ng-disabled="!erpOrderPoFileUploadFormValidator" class="btn btn-primary btn-sm" ng-click="funUploadPurchaseOrderCsv()">Update</button>
                    <button type="button" class="btn btn-default" ng-click="resetFileInputData('purchase_order_csv');hideAlertMsgPopup();" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>