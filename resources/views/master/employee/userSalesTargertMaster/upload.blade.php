<div class="row" ng-hide="isViewUploadDiv">	
	<div class="panel panel-default">
		<div class="panel-body">
		    
			<!--Header-->
			<div class="row header1">
				<div class="pull-left headerText fontbd">Upload Sales Target</div>
				<div class="pull-right"><a title="Upload Sales Target" class="btn btn-primary" ng-click="navigatePage(2)">Back</a></div>
			</div>
			<!--/Header-->
		    
			<!--Upload User Sales Target Form-->
			<form method="POST" name="erpUploadSalesTargetCsvForm" enctype="multipart/form-data" id="erpUploadSalesTargetCsvForm" novalidate>		
				<div class="row">			
				    
					<!--Branch-->
					<div class="col-xs-3 form-group">
						<label for="ust_sales_target_file">Upload CSV<em class="asteriskRed">*</em></label>						   
						<input type="file" ng-model="userSalesTargetUploadDtl.ust_sales_target_file" name="ust_sales_target_file" id="ust_sales_target_file" class="form-control">
						<span class="small color-green">Sample : <a download target="_blank" href="{{url('public/sample/erp_employee_sales_target.csv')}}">Download Sample</a></span>
					</div>
					<!--/Branch-->
	    
					<!--Button-->
					<div class="col-xs-2 form-group mT25">
						<div class="pull-left">
							<label for="csrf_field">{{ csrf_field() }}</label>	
							<button type="button" title="Upload" id="upload_button" class="btn btn-primary" ng-click="funUploadSalesTargetCSV('ust_sales_target_file')">Upload</button>
							<button type="button" title="Back" class="btn btn-default" ng-click="resetUploadForm()">Reset</button>
						</div>
					</div>
					<!--/Button-->
					
				</div>		    
			</form>
			<!--/Upload User Sales Target Form-->
		</div>
	</div>
</div>