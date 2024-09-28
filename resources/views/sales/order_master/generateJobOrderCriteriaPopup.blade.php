<div class="modal fade" id="generateJobOrderCriteriaId" role="dialog">
	<div class="modal-dialog">
		<form name="generateAnalyticalSheetIPdf" action="{{ url('sales/orders/generate-analytical-sheet-I-pdf')}}" method="POST">
			
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-left">Select Report Type :</h4>
				</div>
				<div class="modal-body" ng-init="downloadType=1">
					<!--Admin Permisssion-->
					<div ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}" class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="1">Without Party Detail</label>
					</div>
					<div ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}" class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="2">With Party Detail</label>
					</div>					
					<!--/Admin Permisssion-->
					
					<!--Order Booker Permisssion-->
					<div ng-if="{{defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}" class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="1">Without Party Detail</label>
					</div>
					<div ng-if="{{defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}" class="radio text-left">
						<label><input type="radio" ng-model="downloadType" name="downloadType" value="2">With Party Detail</label>
					</div>
					<!--/Order Booker Permisssion-->					
				</div>
				<div class="modal-footer">
					<label for="submit">{{ csrf_field() }}</label>	
					<span ng-if="{{defined('IS_ADMIN') && IS_ADMIN}}">
						<input type="hidden" ng-value="viewOrder.order_id" name="order_id" ng-model="viewOrder.order_id">
						<input type="submit" formtarget="_blank" name="generate_analytical_sheet_I_pdf" value="Generate PDF" class="btn btn-primary">
					</span>
					<span ng-if="{{defined('IS_ORDER_BOOKER') && IS_ORDER_BOOKER}}">
						<input type="hidden" ng-value="viewOrder.order_id" name="order_id" ng-model="viewOrder.order_id">
						<input type="submit" formtarget="_blank" name="generate_analytical_sheet_I_pdf" value="Generate PDF" class="btn btn-primary">
					</span>
					<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>					
				</div>
			</div>
		</form>		
	</div>
</div>