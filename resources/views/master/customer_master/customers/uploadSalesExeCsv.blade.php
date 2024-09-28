<div class="modal fade" id="upload_sales_executives_csv_modal" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Sales Excecutive CSV File</h4>
            </div>

            <!--display Messge Div-->
            @include('includes.alertMessagePopup')
            <!--/display Messge Div-->

            <form method="POST" role="form" enctype="multipart/form-data" id="erpCustomerSalesExecutiveFileUploadForm" name="erpCustomerSalesExecutiveFileUploadForm" novalidate>         
                <div class="modal-body">
                    <label class="width100">Upload <em class="error">*</em><span class="pull-right right">Download : <a href="{{ asset('public/sample/itcerp_upload_sales_executive.csv') }}" download="itcerp_upload_sales_executive.csv">CSV Format</a></span></label>
                    <input 
                        type="file"
                        class="form-control btn btn-default btn-sm" 
                        ng-files="getCustomerSalesExcutiveTheFiles($files)" 
                        id="sales_executives_csv" 
                        ng-model="customerSalesExecutiveFileUploadForm.sales_executives_csv" 
                        name="sales_executives_csv">
                </div>
                <div class="modal-footer">
                    <label for="submit">{{ csrf_field() }}</label>
                    <button type="button" title="Upload File" ng-disabled="!erpCustomerSalesExecutiveFileUploadFormValidator" class="btn btn-primary btn-sm" ng-click="funUploadSalesExecutivesCsv()">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>