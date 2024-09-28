<div class="row" ng-hide="editStateWiseProductRateDiv">
   <div class="row" id="no-more-tables">
      <div class="panel panel-default">
         <div class="panel-body">
            <div class="row header-form">
               <span class="pull-left headerText"><strong>Edit State Wise Product</strong></span>
               <span class="pull-right pull-custom"><button type="button" class="btn btn-primary" ng-click="navigateFormPage(cirStateID,'0','0','edit');">Back</button></span>
               <span style="width:15%;float: right;">
                  <input type="text" class="seachBox width65 form-control ng-pristine ng-untouched ng-valid" placeholder="Search Product" ng-model="filterInvoicingStateProductRateList">
               </span>
            </div>
            <form method="POST" role="form" id="erpEditStateWiseProductRateForm" name="erpEditStateWiseProductRateForm" novalidate>
               <table class="col-sm-12 table-striped table-condensed cf font15">
                  <thead class="cf">
                     <tr>
                        <th></th>
                        <th>
                           <label ng-click="sortBy('p_category_name')" class="sortlabel">Product Category Name</label>
                           <span ng-class="{reverse:reverse}" ng-show="predicate === 'p_category_name'" class="sortorder reverse"></span>
                        </th>
                        <th>
                           <label ng-click="sortBy('product_name')" class="sortlabel">Product Name</label>
                           <span ng-class="{reverse:reverse}" ng-show="predicate === 'product_name'" class="sortorder reverse"></span>
                        </th>
                        <th>
                           <label ng-click="sortBy('c_product_name')" class="sortlabel">Product Alias Name</label>
                           <span ng-class="{reverse:reverse}" ng-show="predicate === 'test_parc_product_namea_cat_name'" class="sortorder reverse"></span>
                        </th>
                        <th>
                           <label  class="sortlabel">Enter Rate</label>
                           <span  class="sortorder reverse"></span>
                        </th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr dir-paginate="productObj in productAliasRateList | filter : filterInvoicingStateProductRateList | itemsPerPage : {{ defined('PERPAGE') ? PERPAGE : 15 }} | orderBy:'-productObj.rate'">
                        <td></td>
                        <td>[[productObj.p_category_name]]</td>
                        <td>[[productObj.product_name]]
                           <input type="hidden" name="cir_inv_rate_id[]"  ng-model="productObj.cir_id" ng-value="[[productObj.cir_id]]">
                        </td>
                        <td>[[productObj.name]]
                           <input type="hidden" name="cir_c_product_id[]" ng-model="productObj.id" ng-value="productObj.id">
                        </td>
                        <td><input type="number"
                           min="0"
                           ng-if="productObj.rate"
                           class="form-control invoicing_rate width35"
                           ng-model="editCustomerWiseProductRate.invoicing_rate_[[productObj.id]]"
                           name="invoicing_rate[]"
                           value="[[productObj.rate]]"
                           class="form-control"
                           placeholder="Rate"></td>
                     </tr>
                     <tr ng-if="productAliasRateList.length">
                        <dir-pagination-controls></dir-pagination-controls>
         </div>
         </tr>
         <tr><div ng-if="!productAliasRateList.length">No record found.</div></tr>
         </tbody>
         </table>

            <div class="row">
            <!--Save Button-->
            <div class="col-xs-12 form-group text-right mT10">
               <label for="submit">{{ csrf_field() }}</label>
            <input type="hidden" ng-value="editStateWiseProductRate.cir_id" name="cir_id" ng-model="editStateWiseProductRate.cir_id">
               <button type="submit" ng-disabled="erpEditStateWiseProductRateForm.$invalid" class="btn btn-primary" ng-click="funUpdateStateWiseProductRate()">Update</button>
               <button type="button" class="btn btn-default" ng-click="backButton()">Cancel</button>
            </div>
            <!--Save Button-->
         </form>
      </div>
   </div>
</div>
</div>
