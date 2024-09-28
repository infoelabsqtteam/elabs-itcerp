<div class="modal fade" id="previousOrderDetail" role="dialog">
	<div class="modal-dialog">
		<form name="previousOrderDetail"  method="POST">
			
			<div class="modal-content">
				<div class="modal-header alert-danger">
					<label style="font-size:14px">Order for this client already exist. Do you really want to save more orders ?</label>
 				</div>
				<div class="modal-body custom-scroll">
					<table class="col-sm-12 table-striped table-condensed cf" style="top:12px;">
						<thead class="cf">
							<tr>
								<th><label class="sortlabel">Sr.No. </label></th>
								<th><label class="sortlabel">Customer Name </label></th>
								<th><label class="sortlabel">Product Name </label></th>
								<th><label class="sortlabel">Order No. </label></th>
								<th><label class="sortlabel">Order Booking Date</label></th>
								<th><label class="sortlabel">Batch No.</label></th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="orderObj in orderData">
								<td>[[$index+1]].</td>
								<td>[[orderObj.customer_name]]</td>
								<td>[[orderObj.c_product_name]]</td>
								<td>[[orderObj.order_no]]</td>
								<td>[[orderObj.order_date]]</td>
								<td>[[orderObj.batch_no]]</td>
							</tr>
						</tbody>
					</table>
				</div>					
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>	
					<span>
						<button  type="submit" ng-click="funDuplicateCustomerOrder('yes')" class="btn btn-default" type="button">Yes</button>
					</span>
					<button data-dismiss="modal" class="btn btn-default" type="button">No</button>					
				</div>
			</div>
		</form>		
	</div>
</div>