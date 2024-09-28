<div class="modal fade" id="editGstNumberPopUp" role="dialog">
	<div class="modal-dialog">
		<form name="editGstNumberPopUp"  method="POST">
			
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
 				</div>
				<div class="modal-body">
					<label>Are you really want to add this GST number for more customers ?</label>
				</div>
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>	
					<span>
						<button  type="submit" ng-click="updateCustomer('yes')" class="btn btn-default" type="button">Yes</button>
					</span>
					<button data-dismiss="modal" class="btn btn-default" type="button">No</button>					
				</div>
			</div>
		</form>		
	</div>
</div>