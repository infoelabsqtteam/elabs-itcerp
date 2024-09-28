 <div ng-hide="uploadProFormDiv" class="uploadCSVForm">
	<form name='productTestCSVForm' id='productTestCSVForm' novalidate>
		<label for="submit">{{ csrf_field() }}</label>					
		<div class="row header1">
			<strong class="pull-left headerText">Upload Standard Wise Product Test</strong>								
			<strong class="pull-right headerText">
					<input type="submit" ng-click="showProTestAddForm()" value="Back" class="uploaCSVbtn btn btn-primary">
			</strong>								
		</div>
		<div class="row">							  
			<div class="form-group" style="margin-right: 132px;">
				<input type="file" ng-model="productTestModel.productTestFile" name="productTestFile" id="uploadProductTestFile" class="uploadfile">
				<div class="input-group col-xs-12 uploadDiv">
				  <span class="testUploadIcon input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
				  <input type="text" ng-model="productTestModel.productTestBrowseFile" class="browseFileInput form-control input-lg" style="width: 80%;left: 209px !important;" disabled placeholder="Upload Product Tests">
				  <span class="input-group-btn">
					<button id="browseFile" class="btn btn-browse input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
				  </span>
				</div>
			</div>
			<button title="Save" type='submit' ng-show="!uploadType.length" disabled class='btn btn-upload input-lg left-nav'> Upload </button>						
			<button title="Save" type='submit' ng-show="uploadType == 'TestHeader' || uploadType == 'TestDetails'" id="uploadProductTestBtnId" class='btn btn-upload input-lg left-nav'> Upload </button>						
			<div>
			   <span style="float: left;margin-left: 133px;margin-top: 3px;">
					<input type="radio" name="upload_type" value="TestHeader" ng-model="uploadType">Product Test Header
					<input type="radio" name="upload_type" value="TestDetails" ng-model="uploadType">Product Test Details
			   </span>
			   <a class="right-nav downloadTestCsv" ng-show="uploadType == 'TestHeader'" download href="{{url('public/sample/itcerp_product_test_master.csv')}}">Download Product Test Header Sample</a>
			   <a class="right-nav downloadTestCsv" ng-show="uploadType != 'TestHeader'" download href="{{url('public/sample/itcerp_product_test_dtl_master.csv')}}">Download Product Test Details Sample</a>
			</div>			
		</div>
	</form>	
	<link href="{!! asset('public/css/file_upload.css') !!}" rel="stylesheet" type="text/css"/>	
	<script>
		$(document).on('click', '#browseFile', function(){
			 $('.uploadfile').trigger('click');
		});
		$(document).on('change', '.uploadfile', function(){
			  $('.browseFileInput').val($('#uploadProductTestFile').val().replace(/C:\\fakepath\\/i, ''));
		});
	</script>
</div>