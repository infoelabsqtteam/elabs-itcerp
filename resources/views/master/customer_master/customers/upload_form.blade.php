
<div id="uploadCustomerForm" class="uploadForm">	
	<form name='customerCSVForm' id='customerCSVForm' novalidate>
		<label for="submit">{{ csrf_field() }}</label>					
		<div class="row">						
			<span class="upload_span">Upload Customers</span>						
		</div>
		<div class="row">									  
			   <div class="form-group">
				<input type="file" name="customer" id="customerFile" class="uploadfile">
				<div class="input-group col-xs-12 uploadDiv">
				  <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
				  <input type="text" class="browseFileInput form-control input-lg" disabled placeholder="Upload Cutomers">
				  <span class="input-group-btn">
					<button id="browseFile" class=" btn btn-browse input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
				  </span>
				</div>
			  </div>
			<button title="Save" type='submit' class='btn btn-upload input-lg left-nav' id='customerUploadPreviewBtn' > Upload </button>						
		<a download class="right-nav" href="{{url('public/sample/itcerp_customer_master.csv')}}">Download Sample</a>
		</div>
	</form>	
<link href="{!! asset('public/css/file_upload.css') !!}" rel="stylesheet" type="text/css"/>	
<script>
	$(document).on('click', '#browseFile', function(){
		 $('.uploadfile').trigger('click');
	});
	$(document).on('change', '.uploadfile', function(){
		  $('.browseFileInput').val($('#customerFile').val().replace(/C:\\fakepath\\/i, ''));
	});
</script>
</div>