<div class="row" ng-hide="IsViewLogList" id="IsViewLogList">
    
    <!--search-->
    <div class="row header">        
        <div role="new" class="navbar-form navbar-left">            
            <div><strong id="form_title" title="Refresh" ng-click="funViewOrderLog(OrderId,logOrderNumber,DivisionID)">View Order Log : Order Number - <td data-title="Order No" class="ng-binding">[[logOrderNumber]]</td>  </span></strong></div>
        </div>            
        <div role="new" class="navbar-form navbar-right">
            <div class="nav-custom">
			     <input type="text" placeholder="Search" ng-model="filterOrdersLog" class="form-control ng-pristine ng-untouched ng-valid">

                <button type="button" class="btn btn-primary" ng-click="backButton()">Back</button>
            </div>
        </div>
    </div>
    <!--/search-->    
        
    <!--display record--> 
    <div class="row" id="no-more-tables">
        <table class="col-sm-12 table-striped table-condensed cf">
            <thead class="cf">
                <tr>                            
                    <th>
                        <label ng-click="sortBy('order_no')" class="sortlabel"> Date </label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_no'" class="sortorder reverse"></span>
                    </th>                         
                    <th>
                        <label ng-click="sortBy('order_no')" class="sortlabel"> Employee </label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_no'" class="sortorder reverse"></span>
                    </th>                           
                    <th>
                        <label ng-click="sortBy('order_no')" class="sortlabel"> Status </label>
                        <span ng-class="{reverse:reverse}" ng-show="predicate === 'order_no'" class="sortorder reverse"></span>
                    </th> 
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="orderObj in orderLogList | filter:filterOrdersLog | itemsPerPage: 40 | orderBy:predicate:reverse">         
                                 
                    <td data-title="Order No" class="ng-binding">[[orderObj.opl_date]]</td>    
					<td data-title="Order No" class="ng-binding">[[orderObj.name | capitalize]]</td>               
                    <td data-title="Order No" class="ng-binding">
						<span class="[[orderObj.order_status_name]]">[[orderObj.order_status_name | capitalize]]
							<sub ng-if="orderObj.opl_amend_status==1" class="red">Amended</sub>
							<sub ng-if="orderObj.opl_current_stage==1" class="red">current stage</sub>
						</span>
					</td>
                </tr>                        
                <tr ng-if="!orderLogList.length"><td colspan="8">No order found.</td></tr>
            </tbody>
            <tfoot ng-if="orderLogList.length">
                <tr>
                    <td colspan="8">
                        <div class="box-footer clearfix">
                            <dir-pagination-controls></dir-pagination-controls>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>	
    </div>  
</div>