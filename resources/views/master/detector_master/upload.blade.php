<div ng-show="{{defined('ADD') && ADD}}">
	<div class="panel panel-default" ng-hide="uploadEquipmentFormDiv" >
		<div class="panel-body" ng-model="uploadEquipmentFormDiv" >
			<form name='detectorTestCSVForm' id='detectorTestCSVForm' novalidate>
				<label for="submit">{{ csrf_field() }}</label>					
				<div class="row header1">
					<strong class="pull-left headerText">Upload Detectors</strong>								
					<strong class="pull-right headerText">
						<input type="submit" ng-click="showAddForm()" value="Back" class="uploaCSVbtn btn btn-primary">
					</strong>								
				</div>
				<div class="row">							  
					<div class="form-group" style="margin-right: 132px;">
						<input type="file" name="detectorsFile" id="detectorsFile" class="uploadfile">
						<div class="input-group col-xs-12 uploadDiv">
						  <span class="testUploadIcon input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
						  <input type="text" class="browseFileInput form-control input-lg" style="width: 80%;left: 209px !important;" disabled placeholder="Upload Detectors">
						  <span class="input-group-btn">
							<button id="browseFile" class="btn btn-browse input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
						  </span>
						</div>
					</div>
					<button title="Save" type='submit' id="uploadDetectorsBtnId" class='btn btn-upload input-lg left-nav'> Upload </button>						
					<div>
					   <a class="right-nav downloadTestCsv" download href="{{url('public/sample/detector_master.csv')}}">Download Detector Master Sample</a>
					</div>			
				</div>
			</form>	
			<link href="{!! asset('public/css/file_upload.css') !!}" rel="stylesheet" type="text/css"/>	
			<script>
				$(document).on('click', '#browseFile', function(){
					 $('.uploadfile').trigger('click');
				});
				$(document).on('change', '.uploadfile', function(){
					  $('.browseFileInput').val($('#detectorFile').val().replace(/C:\\fakepath\\/i, ''));
				});
			</script>
		</div>
	</div>
</div>